<?php

namespace App\Filament\Widgets;

use App\Enums\ReportStatus;
use App\Filament\Resources\Reports\ReportResource;
use App\Models\Report;
use Carbon\CarbonImmutable;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class ReportStats extends StatsOverviewWidget
{
    protected static bool $isLazy = false;

    protected static ?int $sort = 1;

    protected int|string|array $columnSpan = [
        'default' => 1,
        'md' => 2,
        'xl' => 12,
    ];

    protected int|array|null $columns = [
        'sm' => 2,
        'lg' => 3,
        'xl' => 5,
    ];

    protected ?string $pollingInterval = '30s';

    protected function getStats(): array
    {
        $summary = Report::query()
            ->toBase()
            ->selectRaw('COUNT(*) as total')
            ->selectRaw('SUM(CASE WHEN status = ? THEN 1 ELSE 0 END) as submitted', [ReportStatus::Submitted->value])
            ->selectRaw('SUM(CASE WHEN status IN (?, ?, ?) THEN 1 ELSE 0 END) as in_progress', [
                ReportStatus::Received->value,
                ReportStatus::Coordination->value,
                ReportStatus::ResultReport->value,
            ])
            ->selectRaw('SUM(CASE WHEN status = ? THEN 1 ELSE 0 END) as field_action', [ReportStatus::FieldAction->value])
            ->selectRaw('SUM(CASE WHEN status = ? THEN 1 ELSE 0 END) as completed', [ReportStatus::Completed->value])
            ->first();

        $total = (int) ($summary->total ?? 0);
        $new = (int) ($summary->submitted ?? 0);
        $inProgress = (int) ($summary->in_progress ?? 0);
        $fieldAction = (int) ($summary->field_action ?? 0);
        $completed = (int) ($summary->completed ?? 0);

        return [
            Stat::make('Total laporan', number_format($total, 0, ',', '.'))
                ->chart($this->sevenDayChart())
                ->color('primary')
                ->extraAttributes(['class' => 'dashboard-stat-card'])
                ->url(ReportResource::getUrl('index')),
            Stat::make('Laporan baru', number_format($new, 0, ',', '.'))
                ->color($new > 0 ? 'warning' : 'gray')
                ->extraAttributes(['class' => 'dashboard-stat-card'])
                ->url(ReportResource::getUrl('index')),
            Stat::make('Sedang ditangani', number_format($inProgress, 0, ',', '.'))
                ->color('info')
                ->extraAttributes(['class' => 'dashboard-stat-card'])
                ->url(ReportResource::getUrl('index')),
            Stat::make('Ke lapangan', number_format($fieldAction, 0, ',', '.'))
                ->color('warning')
                ->extraAttributes(['class' => 'dashboard-stat-card'])
                ->url(ReportResource::getUrl('index')),
            Stat::make('Selesai', number_format($completed, 0, ',', '.'))
                ->color('success')
                ->extraAttributes(['class' => 'dashboard-stat-card'])
                ->url(ReportResource::getUrl('index')),
        ];
    }

    /** @return array<int, int> */
    private function sevenDayChart(): array
    {
        $start = CarbonImmutable::today()->subDays(6);
        $counts = Report::query()
            ->where('created_at', '>=', $start)
            ->selectRaw('DATE(created_at) as report_date, COUNT(*) as aggregate')
            ->groupBy('report_date')
            ->pluck('aggregate', 'report_date');

        return collect(range(0, 6))
            ->map(fn (int $day): int => (int) ($counts[$start->addDays($day)->toDateString()] ?? 0))
            ->all();
    }
}
