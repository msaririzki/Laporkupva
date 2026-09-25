<?php

namespace App\Filament\Resources\Reports\Pages;

use App\Filament\Resources\Reports\ReportResource;
use Filament\Resources\Pages\ListRecords;

class ListReports extends ListRecords
{
    protected static string $resource = ReportResource::class;

    public function getSubheading(): ?string
    {
        return 'Temukan dan tindak lanjuti laporan masyarakat di seluruh NTB.';
    }

    protected function getHeaderActions(): array
    {
        return [];
    }
}
