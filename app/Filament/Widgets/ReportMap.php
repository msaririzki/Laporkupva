<?php

namespace App\Filament\Widgets;

use App\Filament\Resources\Reports\ReportResource;
use App\Models\Report;
use Filament\Widgets\Widget;

class ReportMap extends Widget
{
    protected static bool $isLazy = false;

    protected static ?int $sort = 2;

    protected string $view = 'filament.widgets.report-map';

    protected int|string|array $columnSpan = 'full';

    /** @return array<string, mixed> */
    protected function getViewData(): array
    {
        $markers = Report::query()
            ->latest()
            ->limit(500)
            ->get(['id', 'public_code', 'status', 'incident_type', 'business_name', 'regency', 'latitude', 'longitude', 'created_at'])
            ->map(fn (Report $report): array => [
                'code' => $report->public_code,
                'status' => $report->status->label(),
                'statusKey' => $report->status->value,
                'business' => $report->business_name ?: 'Tempat tidak disebutkan',
                'regency' => $report->regency,
                'latitude' => (float) $report->latitude,
                'longitude' => (float) $report->longitude,
                'date' => $report->created_at->translatedFormat('d M Y'),
                'url' => ReportResource::getUrl('view', ['record' => $report]),
            ])
            ->values()
            ->all();

        return [
            'markers' => $markers,
            'reportCount' => count($markers),
        ];
    }
}
