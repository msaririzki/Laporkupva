<?php

namespace App\Filament\Widgets;

use App\Models\Report;
use Filament\Widgets\ChartWidget;

class RegionalReportChart extends ChartWidget
{
    protected static bool $isLazy = false;

    protected static ?int $sort = 5;

    protected int|string|array $columnSpan = 1;

    protected ?string $heading = 'Wilayah laporan terbanyak';

    protected ?string $description = 'Enam kabupaten/kota dengan laporan terbanyak.';

    protected ?string $maxHeight = '310px';

    protected ?array $options = [
        'indexAxis' => 'y',
        'plugins' => [
            'legend' => ['display' => false],
        ],
        'scales' => [
            'x' => [
                'beginAtZero' => true,
                'ticks' => ['precision' => 0],
                'grid' => ['color' => 'rgba(148, 163, 184, 0.16)'],
            ],
            'y' => [
                'grid' => ['display' => false],
            ],
        ],
    ];

    protected function getData(): array
    {
        $regions = Report::query()
            ->selectRaw('regency, COUNT(*) as aggregate')
            ->groupBy('regency')
            ->orderByDesc('aggregate')
            ->limit(6)
            ->get();

        return [
            'datasets' => [[
                'label' => 'Laporan',
                'data' => $regions->pluck('aggregate')->map(fn (mixed $count): int => (int) $count)->all(),
                'backgroundColor' => '#2563eb',
                'borderRadius' => 8,
                'barThickness' => 22,
            ]],
            'labels' => $regions->pluck('regency')->all(),
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }
}
