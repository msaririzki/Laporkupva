<?php

namespace App\Filament\Widgets;

use App\Enums\ReportStatus;
use App\Models\Report;
use Filament\Widgets\ChartWidget;

class ReportStatusChart extends ChartWidget
{
    protected static bool $isLazy = false;

    protected static ?int $sort = 3;

    protected int|string|array $columnSpan = 1;

    protected ?string $heading = 'Distribusi status laporan';

    protected ?string $description = 'Komposisi seluruh tahapan penanganan saat ini.';

    protected ?string $maxHeight = '310px';

    protected function getData(): array
    {
        $counts = Report::query()
            ->selectRaw('status, COUNT(*) as aggregate')
            ->groupBy('status')
            ->pluck('aggregate', 'status');
        $statuses = ReportStatus::cases();

        return [
            'datasets' => [[
                'label' => 'Laporan',
                'data' => collect($statuses)
                    ->map(fn (ReportStatus $status): int => (int) ($counts[$status->value] ?? 0))
                    ->all(),
                'backgroundColor' => [
                    '#3b82f6',
                    '#1d4ed8',
                    '#8b5cf6',
                    '#f97316',
                    '#14b8a6',
                    '#10b981',
                ],
                'borderColor' => '#ffffff',
                'borderWidth' => 3,
                'hoverOffset' => 8,
            ]],
            'labels' => collect($statuses)
                ->map(fn (ReportStatus $status): string => $status->label())
                ->all(),
        ];
    }

    protected function getType(): string
    {
        return 'doughnut';
    }
}
