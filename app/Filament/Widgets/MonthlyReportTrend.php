<?php

namespace App\Filament\Widgets;

use App\Models\Report;
use Carbon\CarbonImmutable;
use Filament\Widgets\ChartWidget;

class MonthlyReportTrend extends ChartWidget
{
    protected static bool $isLazy = false;

    protected static ?int $sort = 4;

    protected string $view = 'filament.widgets.collapsed-chart-widget';

    protected bool $isCollapsible = true;

    protected ?string $pollingInterval = null;

    protected int|string|array $columnSpan = [
        'default' => 1,
        'md' => 1,
        'xl' => 7,
    ];

    public ?string $filter = 'last_6_months';

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
        $period = $this->resolvePeriod();
        $counts = Report::query()
            ->whereBetween('created_at', [$period['start'], $period['end']])
            ->get(['created_at'])
            ->countBy(fn (Report $report): string => $report->created_at->format($period['keyFormat']));

        $points = collect(range(0, $period['pointCount'] - 1))
            ->map(fn (int $offset): CarbonImmutable => $period['granularity'] === 'day'
                ? $period['start']->addDays($offset)
                : $period['start']->addMonths($offset));

        return [
            'datasets' => [[
                'label' => 'Laporan masuk',
                'data' => $points
                    ->map(fn (CarbonImmutable $point): int => (int) ($counts[$point->format($period['keyFormat'])] ?? 0))
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
            'labels' => $points
                ->map(fn (CarbonImmutable $point): string => $point->translatedFormat($period['labelFormat']))
                ->all(),
        ];
    }

    public function getHeading(): string
    {
        $filter = $this->getActiveFilter();

        return match ($filter) {
            'this_month' => 'Tren bulan ini',
            'last_3_months' => 'Tren 3 bulan',
            'last_12_months' => 'Tren 12 bulan',
            'last_6_months' => 'Tren 6 bulan',
            default => 'Tren '.CarbonImmutable::parse(mb_substr($filter, 6).'-01')->translatedFormat('F Y'),
        };
    }

    public function getDescription(): ?string
    {
        return str_starts_with($this->getActiveFilter(), 'month_') || $this->getActiveFilter() === 'this_month'
            ? 'Jumlah laporan masuk per hari.'
            : 'Jumlah laporan masuk per bulan.';
    }

    /**
     * @return array<string, string>
     */
    protected function getFilters(): ?array
    {
        $filters = [
            'this_month' => 'Bulan ini',
            'last_3_months' => '3 bulan terakhir',
            'last_6_months' => '6 bulan terakhir',
            'last_12_months' => '12 bulan terakhir',
        ];

        foreach (range(1, 24) as $monthOffset) {
            $month = CarbonImmutable::now()->startOfMonth()->subMonths($monthOffset);
            $filters['month_'.$month->format('Y-m')] = 'Bulan tertentu · '.$month->translatedFormat('F Y');
        }

        return $filters;
    }

    private function getActiveFilter(): string
    {
        $filter = $this->filter ?? 'last_6_months';

        return array_key_exists($filter, $this->getFilters() ?? [])
            ? $filter
            : 'last_6_months';
    }

    /**
     * @return array{
     *     start: CarbonImmutable,
     *     end: CarbonImmutable,
     *     granularity: 'day'|'month',
     *     pointCount: int,
     *     keyFormat: string,
     *     labelFormat: string
     * }
     */
    private function resolvePeriod(): array
    {
        $filter = $this->getActiveFilter();
        $now = CarbonImmutable::now();

        if ($filter === 'this_month' || str_starts_with($filter, 'month_')) {
            $month = $filter === 'this_month'
                ? $now
                : CarbonImmutable::parse(mb_substr($filter, 6).'-01');
            $start = $month->startOfMonth();
            $end = $month->endOfMonth();

            return [
                'start' => $start,
                'end' => $end,
                'granularity' => 'day',
                'pointCount' => $start->daysInMonth,
                'keyFormat' => 'Y-m-d',
                'labelFormat' => 'd M',
            ];
        }

        $monthCount = match ($filter) {
            'last_3_months' => 3,
            'last_12_months' => 12,
            default => 6,
        };
        $start = $now->startOfMonth()->subMonths($monthCount - 1);

        return [
            'start' => $start,
            'end' => $now->endOfMonth(),
            'granularity' => 'month',
            'pointCount' => $monthCount,
            'keyFormat' => 'Y-m',
            'labelFormat' => 'M Y',
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}
