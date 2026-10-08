<?php

namespace App\Filament\Resources\Kupvas\Pages;

use App\Filament\Resources\Kupvas\KupvaLocationAction;
use App\Filament\Resources\Kupvas\KupvaResource;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditKupva extends EditRecord
{
    protected static string $resource = KupvaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            KupvaLocationAction::make(),
            ViewAction::make(),
        ];
    }

    /** @param array<string, mixed> $data
     * @return array<string, mixed>
     */
    protected function mutateFormDataBeforeSave(array $data): array
    {
        $coordinate = fn (mixed $value): ?float => blank($value) ? null : round((float) $value, 7);
        $coordinatesChanged = $coordinate($data['latitude'] ?? null) !== $coordinate($this->getRecord()->latitude)
            || $coordinate($data['longitude'] ?? null) !== $coordinate($this->getRecord()->longitude);

        if ($coordinatesChanged) {
            $data['location_source'] = filled($data['latitude'] ?? null) && filled($data['longitude'] ?? null) ? 'manual' : null;
            $data['location_match_address'] = null;
        }

        return $data;
    }
}
