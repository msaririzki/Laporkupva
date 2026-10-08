<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('kupvas', function (Blueprint $table) {
            $table->string('location_source', 32)->nullable();
            $table->text('location_match_address')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('kupvas', function (Blueprint $table) {
            $table->dropColumn(['location_source', 'location_match_address']);
        });
    }
};
