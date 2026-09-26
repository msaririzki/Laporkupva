<?php

namespace Tests\Feature;

use App\Models\AnonymousMessage;
use App\Models\Report;
use App\Models\User;
use App\Notifications\Admin\NewReporterMessage;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class AdminReportConversationTest extends TestCase
{
    use LazilyRefreshDatabase;

    public function test_report_detail_renders_the_direct_conversation_component(): void
    {
        $admin = User::factory()->create();
        $report = Report::factory()->create();

        $this->actingAs($admin)
            ->get("/admin/laporan/{$report->getRouteKey()}")
            ->assertOk()
            ->assertSeeLivewire('admin.report-conversation')
            ->assertSee('Balas pelapor')
            ->assertSee('Identitas pelapor terlindungi')
            ->assertSee('Tulis balasan untuk pelapor')
            ->assertSee('data-notification-conversation-navigation', false);
    }

    public function test_active_admin_can_reply_directly_and_opening_conversation_marks_it_as_read(): void
    {
        $admin = User::factory()->create();
        $report = Report::factory()->create();
        $reporterMessage = AnonymousMessage::factory()->create([
            'report_id' => $report->getKey(),
            'body' => 'Lokasi berada di samping pasar.',
            'read_at' => null,
        ]);
        $admin->notify(new NewReporterMessage($report, $reporterMessage->body));

        Livewire::actingAs($admin)
            ->test('admin.report-conversation', ['record' => $report])
            ->assertSee('Lokasi berada di samping pasar.')
            ->set('body', 'Terima kasih, petunjuk lokasi sudah kami catat.')
            ->call('send')
            ->assertHasNoErrors()
            ->assertSet('body', '')
            ->assertSee('Terima kasih, petunjuk lokasi sudah kami catat.');

        $this->assertDatabaseHas(AnonymousMessage::class, [
            'report_id' => $report->getKey(),
            'user_id' => $admin->getKey(),
            'sender_type' => 'admin',
            'body' => 'Terima kasih, petunjuk lokasi sudah kami catat.',
        ]);
        $this->assertNotNull($reporterMessage->refresh()->read_at);
        $this->assertNotNull($admin->notifications()->sole()->read_at);
    }

    public function test_admin_reply_rejects_html_markup(): void
    {
        $admin = User::factory()->create();
        $report = Report::factory()->create();

        Livewire::actingAs($admin)
            ->test('admin.report-conversation', ['record' => $report])
            ->set('body', '<script>alert("message")</script>')
            ->call('send')
            ->assertHasErrors(['body']);

        $this->assertDatabaseCount(AnonymousMessage::class, 0);
    }
}
