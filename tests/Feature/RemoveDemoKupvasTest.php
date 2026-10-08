<?php

namespace Tests\Feature;

use App\Models\Kupva;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class RemoveDemoKupvasTest extends TestCase
{
    use LazilyRefreshDatabase;

    public function test_cleaning_removes_only_identified_demo_offices_and_keeps_a_backup(): void
    {
        $demo = Kupva::factory()->create(['name' => 'KUPVA Demo Cakranegara 01', 'address' => 'Cakranegara (data demo)', 'office_type' => null]);
        $official = Kupva::factory()->create(['name' => 'PT Echa Creative Mandiri', 'office_type' => 'KP']);
        $similar = Kupva::factory()->create(['name' => 'KUPVA Demo Nama Usaha', 'address' => 'Jalan Utama 10', 'office_type' => null]);
        Storage::fake('local');

        $this->artisan('kupvas:remove-demo', ['--delete' => true])->assertSuccessful();

        $this->assertDatabaseMissing('kupvas', ['id' => $demo->id]);
        $this->assertDatabaseHas('kupvas', ['id' => $official->id]);
        $this->assertDatabaseHas('kupvas', ['id' => $similar->id]);
        $backups = Storage::disk('local')->files('backups');
        $this->assertCount(1, $backups);
        $this->assertStringContainsString($demo->name, Storage::disk('local')->get($backups[0]));
        $this->assertStringNotContainsString($official->name, Storage::disk('local')->get($backups[0]));
    }

    public function test_preview_does_not_delete_records_or_create_a_backup(): void
    {
        $demo = Kupva::factory()->create(['name' => 'KUPVA Demo Cakranegara 01', 'address' => 'Cakranegara (data demo)', 'office_type' => null]);
        Storage::fake('local');

        $this->artisan('kupvas:remove-demo')->assertSuccessful();

        $this->assertDatabaseHas('kupvas', ['id' => $demo->id]);
        $this->assertSame([], Storage::disk('local')->files('backups'));
    }
}
