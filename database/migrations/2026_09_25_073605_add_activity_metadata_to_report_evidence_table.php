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
        Schema::table('report_evidence', function (Blueprint $table) {
            $table->foreignId('report_status_history_id')
                ->nullable()
                ->after('report_id')
                ->constrained('report_status_histories')
                ->nullOnDelete();
            $table->foreignId('uploaded_by_user_id')
                ->nullable()
                ->after('report_status_history_id')
                ->constrained('users')
                ->nullOnDelete();
            $table->string('source', 32)
                ->default('reporter_submission')
                ->after('uploaded_by_user_id');
            $table->text('caption')->nullable()->after('size');

            $table->index(['report_id', 'source']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('report_evidence', function (Blueprint $table) {
            $table->dropIndex(['report_id', 'source']);
            $table->dropConstrainedForeignId('uploaded_by_user_id');
            $table->dropConstrainedForeignId('report_status_history_id');
            $table->dropColumn(['source', 'caption']);
        });
    }
};
