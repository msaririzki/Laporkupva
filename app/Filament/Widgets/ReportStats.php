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

    protected ?string $pollingInterval = '30s';

    protected ?string $heading = 'Ringkasan pengawasan';

    protected ?string $description = 'Kondisi laporan masyarakat yang diperbarui secara berkala.';

    protected function getStats(): array
    {
        $total = Report::query()->count();
        $new = Report::query()->where('status', ReportStatus::Submitted)->count();
        $inProgress = Report::query()->whereNotIn('status', [ReportStatus::Submitted, ReportStatus::Completed])->count();
        $completed = Report::query()->where('status', ReportStatus::Completed)->count();
        $completionRate = $total > 0 ? round(($completed / $total) * 100, 1) : 0;

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
                ->description('Koordinasi hingga laporan hasil')
                ->descriptionIcon('heroicon-m-arrow-path')
                ->color('info')
                ->url(ReportResource::getUrl('index')),
            Stat::make('Penyelesaian', number_format($completionRate, 1, ',', '.').'%')
                ->description(number_format($completed, 0, ',', '.').' laporan selesai')
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
