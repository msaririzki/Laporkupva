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
        Schema::create('reports', function (Blueprint $table) {
            $table->id();
            $table->string('public_code', 20)->unique();
            $table->string('tracking_pin_hash');
            $table->string('status', 32)->default('submitted');
            $table->string('incident_type', 64);
            $table->string('business_name')->nullable();
            $table->date('incident_date');
            $table->time('incident_time')->nullable();
            $table->text('description');
            $table->boolean('is_ongoing')->default(false);
            $table->string('province')->default('Nusa Tenggara Barat');
            $table->string('regency');
            $table->string('district')->nullable();
            $table->string('village')->nullable();
            $table->text('address')->nullable();
            $table->decimal('latitude', 10, 7);
            $table->decimal('longitude', 10, 7);
            $table->decimal('location_accuracy', 8, 2)->nullable();
            $table->text('public_update')->nullable();
            $table->text('internal_notes')->nullable();
            $table->timestamp('received_at')->nullable();
            $table->timestamp('coordinated_at')->nullable();
            $table->timestamp('field_action_at')->nullable();
            $table->timestamp('result_reported_at')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->timestamps();

            $table->index(['status', 'created_at']);
            $table->index(['regency', 'created_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reports');
    }
};
