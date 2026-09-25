<?php

namespace App\Filament\Resources\Reports\Pages;

use App\Filament\Resources\Reports\ReportResource;
use Filament\Resources\Pages\ListRecords;

class ListReports extends ListRecords
{
    protected static string $resource = ReportResource::class;

    public function getSubheading(): ?string
    {
        return 'Pantau, saring, dan tindak lanjuti laporan masyarakat dari seluruh wilayah NTB.';
    }

    protected function getHeaderActions(): array
    {
        return [];
    }
}
