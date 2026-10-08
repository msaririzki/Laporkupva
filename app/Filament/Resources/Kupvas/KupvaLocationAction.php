<?php

namespace App\Filament\Resources\Kupvas;

use App\Models\Kupva;
use App\Rules\NoHtml;
use Filament\Actions\Action;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\ViewField;
use Filament\Notifications\Notification;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Components\View;
use Filament\Support\Enums\Width;
use Illuminate\Support\Facades\Gate;
use Livewire\Component;

class KupvaLocationAction
{
    public static function make(): Action
    {
        return Action::make('correctLocation')
            ->label('Koreksi lokasi')
            ->icon('heroicon-o-map-pin')
            ->authorize(fn (Kupva $record): bool => Gate::allows('update', $record))
            ->modalHeading(fn (Kupva $record): string => 'Koreksi lokasi · '.$record->name)
            ->modalDescription('Tempel koordinat atau tautan Google Maps, lalu periksa penanda. Klik peta atau geser penanda untuk menyesuaikan titik usaha.')
            ->modalWidth(Width::ThreeExtraLarge)
            ->modalSubmitActionLabel('Simpan titik yang benar')
            ->fillForm(fn (Kupva $record): array => ['latitude' => $record->latitude, 'longitude' => $record->longitude])
            ->schema([
                View::make('filament.kupvas.location-help')->columnSpanFull(),
                TextInput::make('location_input')
                    ->label('Koordinat atau tautan Google Maps')
                    ->placeholder('-8.5830695, 116.1161800 atau https://maps.app.goo.gl/...')
                    ->maxLength(2048)->rule(new NoHtml)
                    ->helperText('Tekan tombol baca untuk melihat titiknya sebelum disimpan. Tautan tanpa koordinat titik usaha perlu diganti dengan koordinat langsung.')
                    ->suffixAction(Action::make('readLocation')->label('Baca titik')->button()->icon('heroicon-o-arrow-down-tray')
                        ->action(function (Get $get, Set $set, Kupva $record, TextInput $schemaComponent): void {
                            Gate::authorize('update', $record);
                            $point = app(KupvaLocationInput::class)->resolve((string) $get('location_input'), $schemaComponent->getStatePath());
                            $set('latitude', $point['latitude']);
                            $set('longitude', $point['longitude']);
                            $set('location_input', null);
                        }))->columnSpanFull(),
                TextInput::make('latitude')->label('Latitude')->numeric()->live(onBlur: true)
                    ->afterStateUpdated(fn (Set $set) => $set('location_input', null))
                    ->requiredWithout('location_input')->minValue(-11)->maxValue(-8),
                TextInput::make('longitude')->label('Longitude')->numeric()->live(onBlur: true)
                    ->afterStateUpdated(fn (Set $set) => $set('location_input', null))
                    ->requiredWithout('location_input')->minValue(115)->maxValue(120),
                ViewField::make('location_preview')->label('Titik lokasi usaha')->view('filament.kupvas.location-editor')
                    ->dehydrated(false)->columnSpanFull(),
            ])
            ->action(function (array $data, Kupva $record, Component $livewire): void {
                Gate::authorize('update', $record);
                $point = filled($data['location_input'] ?? null)
                    ? app(KupvaLocationInput::class)->resolve($data['location_input'], $livewire->getSchema($livewire->getMountedActionSchemaName())->getStatePath().'.location_input')
                    : ['latitude' => $data['latitude'], 'longitude' => $data['longitude']];
                $record->update([...$point, 'location_source' => 'manual', 'location_match_address' => null]);

                if (method_exists($livewire, 'refreshFormData')) {
                    $livewire->refreshFormData(['latitude', 'longitude', 'location_source', 'location_match_address']);
                }

                Notification::make()->title('Titik lokasi berhasil diperbarui')
                    ->body('Lokasi sudah ditandai sebagai titik yang diperiksa petugas dan digunakan pada peta masyarakat.')
                    ->success()->send();
            });
    }
}
