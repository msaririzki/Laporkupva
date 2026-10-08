<?php

namespace App\Console\Commands;

use App\Models\Kupva;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

#[Signature('kupvas:remove-demo {--delete : Hapus data demo yang tercantum dalam pratinjau}')]
#[Description('Bersihkan data KUPVA demo tanpa mengubah data BI atau laporan masyarakat')]
class RemoveDemoKupvas extends Command
{
    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $query = Kupva::query()->where('name', 'like', 'KUPVA Demo %')->where('address', 'like', '%(data demo)%')->whereNull('office_type');
        $records = $query->orderBy('id')->get();

        foreach ($records as $record) {
            $this->line("#{$record->id} {$record->name}");
        }

        if ($this->option('delete')) {
            if ($records->isNotEmpty() && ! Storage::disk('local')->put('backups/kupva-demo-'.now()->format('Ymd-His').'.json', $records->toJson(JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE))) {
                $this->error('Cadangan data belum tersimpan. Pembersihan tidak dijalankan.');

                return self::FAILURE;
            }

            $count = $query->whereIn('id', $records->modelKeys())->delete();
            $this->info("{$count} data KUPVA demo dihapus. Data BI tetap tersimpan.");
        } else {
            $this->info("{$records->count()} data demo ditemukan. Gunakan --delete untuk membersihkan.");
        }

        return self::SUCCESS;
    }
}
