<?php

namespace Tests\Feature;

use Illuminate\Support\Facades\DB;
use Symfony\Component\Process\Process;
use Tests\TestCase;

class SqliteConcurrencyTest extends TestCase
{
    public function test_a_transaction_reserves_its_write_before_a_competing_queue_worker_can_commit(): void
    {
        $databasePath = tempnam(sys_get_temp_dir(), 'tambora-sqlite-');
        $connection = DB::build([
            ...config('database.connections.sqlite'),
            'url' => null,
            'database' => $databasePath,
        ]);
        $workerCode = <<<'PHP'
            $pdo = new PDO('sqlite:'.$argv[1]);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $pdo->exec('PRAGMA busy_timeout = 250');
            try {
                $pdo->exec('UPDATE counters SET value = value + 1');
                echo 'committed';
            } catch (PDOException $exception) {
                if (($exception->errorInfo[1] ?? null) !== 5) {
                    throw $exception;
                }
                echo 'blocked';
            }
            PHP;

        try {
            $connection->statement('CREATE TABLE counters (value INTEGER NOT NULL)');
            $connection->table('counters')->insert(['value' => 0]);
            $connection->beginTransaction();
            $this->assertSame(0, $connection->table('counters')->value('value'));

            $competingWriter = new Process([PHP_BINARY, '-r', $workerCode, $databasePath]);
            $competingWriter->mustRun();
            $this->assertSame('blocked', $competingWriter->getOutput());
            $connection->table('counters')->increment('value');
            $connection->commit();

            $competingWriter->mustRun();
            $this->assertSame('committed', $competingWriter->getOutput());
            $this->assertSame(2, $connection->table('counters')->value('value'));
        } finally {
            if ($connection->transactionLevel() > 0) {
                $connection->rollBack();
            }

            $connection->disconnect();

            foreach ([$databasePath, $databasePath.'-wal', $databasePath.'-shm'] as $path) {
                if (is_file($path)) {
                    unlink($path);
                }
            }
        }
    }
}
