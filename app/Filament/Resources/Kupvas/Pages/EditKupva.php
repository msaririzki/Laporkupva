<?php

namespace App\Filament\Resources\Kupvas\Pages;

use App\Filament\Resources\Kupvas\KupvaResource;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditKupva extends EditRecord
{
    protected static string $resource = KupvaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
        ];
    }
}
