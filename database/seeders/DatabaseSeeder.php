<?php

namespace Database\Seeders;

use App\Enums\UserRole;
use App\Models\Report;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        $email = config('tambora.seed_super_admin_email');
        $password = config('tambora.seed_super_admin_password');

        if (! is_string($email) || ! is_string($password) || $email === '' || $password === '') {
            $this->command?->warn('Akun Super Admin tidak dibuat. Isi SEED_SUPER_ADMIN_EMAIL dan SEED_SUPER_ADMIN_PASSWORD terlebih dahulu.');

            return;
        }

        $superAdmin = User::query()->updateOrCreate(
            ['email' => $email],
            [
                'name' => 'Super Admin TAMBORA',
                'password' => $password,
                'role' => UserRole::SuperAdmin,
                'email_verified_at' => now(),
            ],
        );

        if (! app()->isLocal() || Report::query()->exists()) {
            return;
        }

        $this->call(DemoDataSeeder::class);
    }
}
