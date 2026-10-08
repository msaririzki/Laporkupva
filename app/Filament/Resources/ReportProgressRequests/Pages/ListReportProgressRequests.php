<?php

namespace App\Filament\Resources\ReportProgressRequests\Pages;

use App\Filament\Resources\ReportProgressRequests\ReportProgressRequestResource;
use App\Models\ReportProgressRequest;
use Filament\Resources\Pages\ListRecords;
use Filament\Schemas\Components\Tabs\Tab;
use Illuminate\Database\Eloquent\Builder;

class ListReportProgressRequests extends ListRecords
{
    protected static string $resource = ReportProgressRequestResource::class;

    protected function getHeaderActions(): array
    {
        return [];
    }

    public function getSubheading(): ?string
    {
        return 'Tinjau catatan kegiatan, foto dokumentasi, dan pesan untuk pelapor sebelum memberikan keputusan.';
    }

    /** @return array<string, Tab> */
    public function getTabs(): array
    {
        $counts = ReportProgressRequest::query()->selectRaw('status, count(*) as total')->groupBy('status')->pluck('total', 'status');

        return [
            'pending' => Tab::make('Menunggu persetujuan')->badge($counts['pending'] ?? 0)->badgeColor('warning')
                ->modifyQueryUsing(fn (Builder $query): Builder => $query->where('status', 'pending')),
            'approved' => Tab::make('Disetujui')->badge($counts['approved'] ?? 0)->badgeColor('success')
                ->modifyQueryUsing(fn (Builder $query): Builder => $query->where('status', 'approved')),
            'rejected' => Tab::make('Ditolak')->badge($counts['rejected'] ?? 0)->badgeColor('danger')
                ->modifyQueryUsing(fn (Builder $query): Builder => $query->where('status', 'rejected')),
        ];
    }
}
