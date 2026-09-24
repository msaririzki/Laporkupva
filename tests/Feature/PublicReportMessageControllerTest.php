<?php

namespace Tests\Feature;

use App\Models\AnonymousMessage;
use App\Models\Report;
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
