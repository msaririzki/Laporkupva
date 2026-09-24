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

    protected int|array|null $columns = [
        'md' => 2,
        'xl' => 5,
    ];

    protected ?string $pollingInterval = '30s';

    protected ?string $heading = 'Ringkasan pengawasan';

    protected ?string $description = 'Kondisi laporan masyarakat yang diperbarui secara berkala.';

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
                ->description('Seluruh laporan masyarakat')
                ->descriptionIcon('heroicon-m-clipboard-document-list')
                ->chart($this->sevenDayChart())
                ->color('primary')
                ->url(ReportResource::getUrl('index')),
            Stat::make('Laporan baru', number_format($new, 0, ',', '.'))
                ->description('Perlu pemeriksaan awal')
                ->descriptionIcon('heroicon-m-inbox-arrow-down')
                ->color($new > 0 ? 'warning' : 'gray')
                ->url(ReportResource::getUrl('index')),
            Stat::make('Sedang diproses', number_format($inProgress, 0, ',', '.'))
                ->description('Diterima, koordinasi, dan hasil')
                ->descriptionIcon('heroicon-m-arrow-path')
                ->color('info')
                ->url(ReportResource::getUrl('index')),
            Stat::make('Tindakan lapangan', number_format($fieldAction, 0, ',', '.'))
                ->description('Kunjungan atau penertiban')
                ->descriptionIcon('heroicon-m-map-pin')
                ->color('warning')
                ->url(ReportResource::getUrl('index')),
            Stat::make('Selesai', number_format($completed, 0, ',', '.'))
                ->description($total > 0 ? number_format(($completed / $total) * 100, 1, ',', '.').'% dari total laporan' : 'Belum ada laporan')
                ->descriptionIcon('heroicon-m-check-circle')
                ->color('success')
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
