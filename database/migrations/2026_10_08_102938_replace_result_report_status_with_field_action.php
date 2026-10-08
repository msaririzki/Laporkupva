<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::table('reports')->where('status', 'result_report')->update(['status' => 'field_action']);

        foreach (['from_status', 'to_status'] as $column) {
            DB::table('report_status_histories')->where($column, 'result_report')->update([$column => 'field_action']);
        }
    }

    public function down(): void
    {
        // The removed status cannot be reconstructed without changing unrelated reports.
    }
};
