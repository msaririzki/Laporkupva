<?php

namespace Tests\Feature;

use App\Models\AnonymousMessage;
use App\Models\Report;
use App\Models\User;
use App\Notifications\Admin\NewReporterMessage;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Tests\TestCase;

class PublicReportMessageControllerTest extends TestCase
{
    use LazilyRefreshDatabase;

    public function test_verified_reporter_can_send_an_anonymous_message(): void
    {
        $report = Report::factory()->create();

        $this->withSession($this->trackingSessionFor($report))
            ->post(route('reports.messages.store', ['report' => $report->public_code]), [
                'body' => '  Lokasinya berada di sebelah timur pasar.  ',
            ])
            ->assertRedirect(route('reports.status', ['report' => $report->public_code]))
            ->assertSessionHas('message_sent');

        $this->assertDatabaseHas(AnonymousMessage::class, [
            'report_id' => $report->getKey(),
            'user_id' => null,
            'sender_type' => 'reporter',
            'body' => 'Lokasinya berada di sebelah timur pasar.',
        ]);
    }

    public function test_new_reporter_message_notifies_each_active_admin(): void
    {
        $admin = User::factory()->create();
        $superAdmin = User::factory()->superAdmin()->create();
        $inactiveAdmin = User::factory()->create(['is_active' => false]);
        $report = Report::factory()->create(['public_code' => 'LKP-AB12-CD34']);

        $this->withSession($this->trackingSessionFor($report))
            ->post(route('reports.messages.store', ['report' => $report->public_code]), [
                'body' => 'Lokasi tepatnya berada di samping pintu timur pasar.',
            ])
            ->assertRedirect(route('reports.status', ['report' => $report->public_code]));

        $adminNotification = $admin->notifications()->sole();

        $this->assertSame(NewReporterMessage::class, $adminNotification->type);
        $this->assertSame('Pesan baru dari pelapor', $adminNotification->data['title']);
        $this->assertSame($report->getKey(), $adminNotification->data['report_id']);
        $this->assertStringContainsString($report->public_code, $adminNotification->data['body']);
        $this->assertStringContainsString('samping pintu timur pasar', $adminNotification->data['body']);
        $this->assertStringContainsString("/admin/laporan/{$report->getRouteKey()}", $adminNotification->data['actions'][0]['url']);
        $this->assertStringEndsWith('#komunikasi-anonim', $adminNotification->data['actions'][0]['url']);
        $this->assertNull($adminNotification->data['actions'][0]['alpineClickHandler']);
        $this->assertTrue($adminNotification->data['actions'][0]['shouldMarkAsRead']);
        $this->assertSame(1, $superAdmin->notifications()->count());
        $this->assertSame(0, $inactiveAdmin->notifications()->count());
    }

    public function test_message_requires_an_active_verified_tracking_session(): void
    {
        $report = Report::factory()->create();

        $this->post(route('reports.messages.store', ['report' => $report->public_code]), [
            'body' => 'Informasi tambahan.',
        ])->assertNotFound();

        $this->assertDatabaseCount(AnonymousMessage::class, 0);
    }

    public function test_message_body_is_validated(): void
    {
        $report = Report::factory()->create();

        $this->withSession($this->trackingSessionFor($report))
            ->from(route('reports.status', ['report' => $report->public_code]))
            ->post(route('reports.messages.store', ['report' => $report->public_code]), [
                'body' => ' ',
            ])
            ->assertRedirect(route('reports.status', ['report' => $report->public_code]))
            ->assertSessionHasErrors('body');

        $this->assertDatabaseCount(AnonymousMessage::class, 0);
    }

    public function test_message_rejects_html_markup(): void
    {
        $report = Report::factory()->create();

        $this->withSession($this->trackingSessionFor($report))
            ->from(route('reports.status', ['report' => $report->public_code]))
            ->post(route('reports.messages.store', ['report' => $report->public_code]), [
                'body' => '<script>alert("message")</script>',
            ])
            ->assertRedirect(route('reports.status', ['report' => $report->public_code]))
            ->assertSessionHasErrors('body');

        $this->assertDatabaseCount(AnonymousMessage::class, 0);
    }

    public function test_message_content_is_escaped_on_the_public_status_page(): void
    {
        $report = Report::factory()->create();
        AnonymousMessage::factory()->create([
            'report_id' => $report->getKey(),
            'sender_type' => 'admin',
            'body' => '<script>alert("message")</script>',
        ]);

        $this->withSession($this->trackingSessionFor($report))
            ->get(route('reports.status', ['report' => $report->public_code]))
            ->assertOk()
            ->assertDontSee('<script>alert("message")</script>', false)
            ->assertSee('&lt;script&gt;alert(&quot;message&quot;)&lt;/script&gt;', false);
    }

    /** @return array<string, int> */
    private function trackingSessionFor(Report $report): array
    {
        return [
            "tracked_reports.{$report->getKey()}" => now()->addMinutes(30)->getTimestamp(),
        ];
    }
}
