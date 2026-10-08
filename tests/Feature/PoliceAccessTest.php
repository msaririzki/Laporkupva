<?php

namespace Tests\Feature;

use App\Filament\Resources\Kupvas\KupvaResource;
use App\Filament\Resources\Kupvas\Pages\ListKupvas;
use App\Filament\Resources\Reports\Pages\ViewReport;
use App\Filament\Resources\Reports\ReportResource;
use App\Filament\Resources\Users\Pages\CreateUser;
use App\Filament\Resources\Users\UserResource;
use App\Models\AnonymousMessage;
use App\Models\Kupva;
use App\Models\Report;
use App\Models\ReportEvidence;
use App\Models\User;
use Filament\Facades\Filament;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Illuminate\Support\Facades\Gate;
use Livewire\Livewire;
use PHPUnit\Framework\Attributes\TestWith;
use Tests\TestCase;

class PoliceAccessTest extends TestCase
{
    use LazilyRefreshDatabase;

    public function test_police_can_view_reports_without_reporter_identity_or_write_actions(): void
    {
        $police = User::factory()->police()->create();
        $report = Report::factory()->create([
            'reporter_name' => 'Identitas Rahasia Pelapor',
            'reporter_email' => 'rahasia@example.test',
            'reporter_phone' => '+6281234567890',
        ]);

        $this->actingAs($police)->get(ReportResource::getUrl('index'))->assertOk();
        $this->get(ReportResource::getUrl('view', ['record' => $report]))
            ->assertOk()->assertSee($report->public_code)
            ->assertDontSee('Identitas Rahasia Pelapor')->assertDontSee('rahasia@example.test')
            ->assertDontSee('+6281234567890')->assertDontSee('Nama pelapor')->assertDontSee('Nomor HP pelapor')
            ->assertDontSee('Email pelapor')->assertDontSee('Update progres')->assertDontSee('Tulis balasan untuk pelapor');

        $restrictedReport = ReportResource::getEloquentQuery()->findOrFail($report->id);
        $this->assertArrayNotHasKey('reporter_name', $restrictedReport->getAttributes());
        $this->assertArrayNotHasKey('reporter_email', $restrictedReport->getAttributes());
        $this->assertArrayNotHasKey('reporter_phone', $restrictedReport->getAttributes());
        Livewire::test(ViewReport::class, ['record' => $report->id])
            ->assertActionHidden('advanceStatus')->assertActionHidden('addActivityEvidence')->assertActionHidden('correctStatus');
    }

    public function test_police_cannot_open_edit_create_or_account_management_pages(): void
    {
        $police = User::factory()->police()->create();
        $report = Report::factory()->create();
        $kupva = Kupva::factory()->create();
        $operator = User::factory()->create();
        $this->actingAs($police);

        $this->get(ReportResource::getUrl('edit', ['record' => $report]))->assertForbidden();
        $this->get(KupvaResource::getUrl('view', ['record' => $kupva]))->assertOk();
        $this->get(KupvaResource::getUrl('edit', ['record' => $kupva]))->assertForbidden();
        $this->get(KupvaResource::getUrl('create'))->assertForbidden();
        $this->get(UserResource::getUrl('index'))->assertForbidden();
        $this->get(UserResource::getUrl('create'))->assertForbidden();
        $this->get(UserResource::getUrl('edit', ['record' => $operator]))->assertForbidden();
        Livewire::test(ListKupvas::class)->assertActionHidden('importSpreadsheet');
    }

    #[TestWith(['super_admin', true, true])]
    #[TestWith(['admin', true, false])]
    #[TestWith(['police', false, false])]
    public function test_roles_keep_expected_view_and_write_permissions(string $role, bool $canWrite, bool $canManageAccounts): void
    {
        $user = User::factory()->create(['role' => $role]);
        $report = Report::factory()->create();
        $kupva = Kupva::factory()->create();
        $evidence = ReportEvidence::factory()->create(['report_id' => $report]);
        $gate = Gate::forUser($user);

        $this->assertTrue($user->canAccessPanel(Filament::getPanel('admin')));
        $this->assertTrue($gate->allows('view', $report));
        $this->assertTrue($gate->allows('view', $kupva));
        $this->assertTrue($gate->allows('view', $evidence));
        $this->assertSame($canWrite, $gate->allows('update', $report));
        $this->assertSame($canWrite, $gate->allows('update', $kupva));
        $this->assertSame($canWrite, $gate->allows('delete', $kupva));
        $this->assertSame($canWrite, $gate->allows('create', ReportEvidence::class));
        $this->assertSame($canWrite, $user->canViewReporterIdentity());
        $this->assertSame($canManageAccounts, $gate->allows('create', User::class));
        $this->assertSame($canManageAccounts, $gate->allows('update', $user));
    }

    public function test_administrator_can_create_and_manage_a_police_account(): void
    {
        $administrator = User::factory()->superAdmin()->create();
        $this->actingAs($administrator);

        Livewire::test(CreateUser::class)->fillForm([
            'name' => 'Petugas Polisi', 'email' => 'polisi@example.test',
            'password' => 'Strong!Police2026', 'role' => 'police', 'is_active' => true,
        ])->call('create')->assertHasNoFormErrors()->assertNotified();

        $this->assertDatabaseHas('users', ['email' => 'polisi@example.test', 'role' => 'police']);
        $this->get(UserResource::getUrl('index'))->assertOk()->assertSee('Petugas Polisi')->assertSee('APH');
    }

    public function test_account_form_rejects_a_role_outside_operator_and_police(): void
    {
        $this->actingAs(User::factory()->superAdmin()->create());

        Livewire::test(CreateUser::class)->fillForm([
            'name' => 'Akun Baru', 'email' => 'baru@example.test',
            'password' => 'Strong!Account2026', 'role' => 'super_admin', 'is_active' => true,
        ])->call('create')->assertHasFormErrors(['role']);

        $this->assertDatabaseMissing('users', ['email' => 'baru@example.test']);
    }

    public function test_inactive_police_cannot_access_the_panel_or_reports(): void
    {
        $police = User::factory()->police()->create(['is_active' => false]);
        $report = Report::factory()->create();

        $this->actingAs($police)->get(ReportResource::getUrl('view', ['record' => $report]))->assertForbidden();
        $this->assertFalse(Gate::forUser($police)->allows('view', $report));
    }

    public function test_police_cannot_advance_a_report_even_when_calling_the_model_directly(): void
    {
        $police = User::factory()->police()->create();
        $report = Report::factory()->create();

        try {
            $report->advanceStatus($police, 'Perubahan tanpa izin');
            $this->fail('Polisi tidak boleh mengubah status laporan.');
        } catch (AuthorizationException) {
            $this->assertSame('submitted', $report->fresh()->status->value);
            $this->assertDatabaseCount('report_status_histories', 0);
        }
    }

    public function test_police_report_export_does_not_include_reporter_identity(): void
    {
        $police = User::factory()->police()->create();
        $report = Report::factory()->create([
            'reporter_name' => 'Identitas Rahasia',
            'reporter_email' => 'rahasia@example.test',
            'reporter_phone' => '+6281234567890',
        ]);

        $response = $this->actingAs($police)->get(route('admin.reports.export'))->assertOk();

        $csv = $response->streamedContent();
        $this->assertStringContainsString($report->public_code, $csv);
        $this->assertStringNotContainsString('Identitas Rahasia', $csv);
        $this->assertStringNotContainsString('rahasia@example.test', $csv);
        $this->assertStringNotContainsString('+6281234567890', $csv);
    }

    public function test_police_can_read_conversation_without_marking_messages_read_or_sending_replies(): void
    {
        $police = User::factory()->police()->create();
        $report = Report::factory()->create();
        $message = AnonymousMessage::factory()->create([
            'report_id' => $report, 'sender_type' => 'reporter', 'read_at' => null,
            'body' => 'Lokasi berada di sebelah pasar.',
        ]);
        $this->actingAs($police);

        Livewire::test('admin.report-conversation', ['record' => $report])
            ->assertSee('Lokasi berada di sebelah pasar.')->assertSee('Akses hanya baca.')
            ->assertDontSee('wire:submit="send"', false)
            ->set('body', 'Balasan tanpa izin')->call('send')->assertForbidden();

        $this->assertNull($message->fresh()->read_at);
        $this->assertDatabaseCount('anonymous_messages', 1);
    }
}
