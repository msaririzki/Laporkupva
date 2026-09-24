<?php

namespace App\Filament\Widgets;

use App\Models\Report;
use Carbon\CarbonImmutable;
use Filament\Widgets\ChartWidget;

class MonthlyReportTrend extends ChartWidget
{
    protected static bool $isLazy = false;

    protected static ?int $sort = 3;

    protected int|string|array $columnSpan = 1;

    protected ?string $heading = 'Tren laporan bulanan';

    protected ?string $description = 'Jumlah laporan yang masuk selama enam bulan terakhir.';

    protected ?string $maxHeight = '310px';

    protected ?array $options = [
        'plugins' => [
            'legend' => ['display' => false],
        ],
        'scales' => [
            'y' => [
                'beginAtZero' => true,
                'ticks' => ['precision' => 0],
                'grid' => ['color' => 'rgba(148, 163, 184, 0.16)'],
            ],
            'x' => [
                'grid' => ['display' => false],
            ],
        ],
    ];

    protected function getData(): array
    {
        $start = CarbonImmutable::now()->startOfMonth()->subMonths(5);
        $months = collect(range(0, 5))
            ->map(fn (int $month): CarbonImmutable => $start->addMonths($month));
        $counts = Report::query()
            ->where('created_at', '>=', $start)
            ->get(['created_at'])
            ->countBy(fn (Report $report): string => $report->created_at->format('Y-m'));

        return [
            'datasets' => [[
                'label' => 'Laporan masuk',
                'data' => $months
                    ->map(fn (CarbonImmutable $month): int => (int) ($counts[$month->format('Y-m')] ?? 0))
                    ->all(),
                'borderColor' => '#2563eb',
                'backgroundColor' => 'rgba(37, 99, 235, 0.12)',
                'fill' => true,
                'tension' => 0.35,
                'pointBackgroundColor' => '#ffffff',
                'pointBorderColor' => '#2563eb',
                'pointBorderWidth' => 3,
                'pointRadius' => 4,
            ]],
            'labels' => $months
                ->map(fn (CarbonImmutable $month): string => $month->translatedFormat('M Y'))
                ->all(),
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}
