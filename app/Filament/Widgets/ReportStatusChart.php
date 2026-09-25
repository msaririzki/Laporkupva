<?php

namespace App\Filament\Widgets;

use App\Enums\ReportStatus;
use App\Models\Report;
use Filament\Widgets\Widget;

class ReportStatusChart extends Widget
{
    protected static bool $isLazy = false;

    protected static ?int $sort = 3;

    protected string $view = 'filament.widgets.report-status-overview';

    protected int|string|array $columnSpan = [
        'default' => 1,
        'md' => 1,
        'xl' => 4,
    ];

    /**
     * @return array{
     *     statuses: list<array{label: string, count: int, percentage: float, percentageLabel: string, dotClass: string, barClass: string}>,
     *     total: int,
     *     chartBackground: string
     * }
     */
    protected function getViewData(): array
    {
        $counts = Report::query()
            ->selectRaw('status, COUNT(*) as aggregate')
            ->groupBy('status')
            ->pluck('aggregate', 'status');
        $statuses = ReportStatus::cases();
        $total = collect($statuses)
            ->sum(fn (ReportStatus $status): int => (int) ($counts[$status->value] ?? 0));
        $offset = 0.0;
        $gradientSegments = [];

        $statusSummaries = collect($statuses)
            ->map(function (ReportStatus $status) use ($counts, $total, &$offset, &$gradientSegments): array {
                $count = (int) ($counts[$status->value] ?? 0);
                $percentage = $total > 0 ? ($count / $total) * 100 : 0.0;
                $colors = $this->colorsFor($status);
                $segmentEnd = $offset + $percentage;

                if ($percentage > 0) {
                    $gradientSegments[] = sprintf(
                        '%s %.4F%% %.4F%%',
                        $colors['hex'],
                        $offset,
                        $segmentEnd,
                    );
                }

                $offset = $segmentEnd;

                return [
                    'label' => $this->labelFor($status),
                    'count' => $count,
                    'percentage' => $percentage,
                    'percentageLabel' => number_format($percentage, 1, ',', '.').'%',
                    'dotClass' => $colors['dotClass'],
                    'barClass' => $colors['barClass'],
                ];
            })
            ->values()
            ->all();

        return [
            'statuses' => $statusSummaries,
            'total' => $total,
            'chartBackground' => $gradientSegments === []
                ? 'conic-gradient(#e2e8f0 0% 100%)'
                : 'conic-gradient('.implode(', ', $gradientSegments).')',
        ];
    }

    private function labelFor(ReportStatus $status): string
    {
        return match ($status) {
            ReportStatus::Submitted => 'Baru',
            ReportStatus::Received => 'Diterima',
            ReportStatus::Coordination => 'Koordinasi',
            ReportStatus::FieldAction => 'Tindakan lapangan',
            ReportStatus::ResultReport => 'Hasil',
            ReportStatus::Completed => 'Selesai',
        };
    }

    /**
     * @return array{hex: string, dotClass: string, barClass: string}
     */
    private function colorsFor(ReportStatus $status): array
    {
        return match ($status) {
            ReportStatus::Submitted => ['hex' => '#3b82f6', 'dotClass' => 'bg-blue-500', 'barClass' => 'bg-blue-500'],
            ReportStatus::Received => ['hex' => '#1d4ed8', 'dotClass' => 'bg-blue-700', 'barClass' => 'bg-blue-700'],
            ReportStatus::Coordination => ['hex' => '#8b5cf6', 'dotClass' => 'bg-violet-500', 'barClass' => 'bg-violet-500'],
            ReportStatus::FieldAction => ['hex' => '#f97316', 'dotClass' => 'bg-orange-500', 'barClass' => 'bg-orange-500'],
            ReportStatus::ResultReport => ['hex' => '#14b8a6', 'dotClass' => 'bg-teal-500', 'barClass' => 'bg-teal-500'],
            ReportStatus::Completed => ['hex' => '#10b981', 'dotClass' => 'bg-emerald-500', 'barClass' => 'bg-emerald-500'],
        };
    }
}
