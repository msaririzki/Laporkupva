<?php

namespace App\Filament\Resources\Kupvas\Pages;

use App\Filament\Resources\Kupvas\KupvaLocationAction;
use App\Filament\Resources\Kupvas\KupvaResource;
use App\Jobs\ResolveKupvaLocation;
use Filament\Actions\Action;
use Filament\Actions\EditAction;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ViewRecord;
use Illuminate\Support\Facades\Gate;

class ViewKupva extends ViewRecord
{
    protected static string $resource = KupvaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            KupvaLocationAction::make(),
            Action::make('findLocation')
                ->label('Cari lokasi dari alamat')
                ->icon('heroicon-o-map-pin')
                ->authorize(fn (): bool => Gate::allows('update', $this->getRecord()))
                ->visible(fn (): bool => $this->getRecord()->latitude === null && $this->getRecord()->longitude === null)
                ->action(function (): void {
                    $record = $this->getRecord()->fresh();
                    Gate::authorize('update', $record);
                    ResolveKupvaLocation::dispatch($record->id)->afterCommit();
                    Notification::make()->title('Pencarian lokasi sedang diproses')
                        ->body('Alamat digunakan untuk mencari lokasi. Hasil otomatis diberi label perkiraan. Muat ulang halaman untuk melihat hasilnya.')
                        ->success()->send();
                }),
            EditAction::make(),
        ];
    }
}
