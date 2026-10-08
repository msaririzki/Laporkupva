<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('kupvas', function (Blueprint $table) {
            $table->dropUnique('kupvas_license_number_unique');
            $table->index('license_number');
            $table->index(['name', 'office_type']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (DB::table('kupvas')->select('license_number')->whereNotNull('license_number')
            ->groupBy('license_number')->havingRaw('COUNT(*) > 1')->exists()) {
            throw new RuntimeException('Nomor izin digunakan oleh beberapa kantor. Batas unik lama tidak dapat dipulihkan tanpa mengubah data kantor.');
        }

        Schema::table('kupvas', function (Blueprint $table) {
            $table->dropIndex(['name', 'office_type']);
            $table->dropIndex(['license_number']);
            $table->unique('license_number');
        });
    }
};
