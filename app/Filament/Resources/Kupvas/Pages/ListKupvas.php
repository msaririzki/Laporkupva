<?php

namespace App\Filament\Resources\Kupvas\Pages;

use App\Filament\Resources\Kupvas\KupvaResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListKupvas extends ListRecords
{
    protected static string $resource = KupvaResource::class;

    public function getSubheading(): ?string
    {
        return 'Kelola referensi penyelenggara KUPVA dan pantau status izin operasionalnya.';
    }

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
