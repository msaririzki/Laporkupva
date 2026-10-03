<?php

namespace App\Filament\Resources\Kupvas\Pages;

use App\Filament\Resources\Kupvas\KupvaResource;
use Filament\Actions\Action;
use Filament\Resources\Pages\CreateRecord;
use Filament\Support\Enums\Alignment;
use Filament\Support\Enums\Width;
use Filament\Support\Icons\Heroicon;

class CreateKupva extends CreateRecord
{
    protected static string $resource = KupvaResource::class;

    protected ?string $heading = 'Tambah data KUPVA';

    protected ?string $subheading = 'Isi nama, nomor izin, dan lokasi. Status awal otomatis aktif dan beroperasi.';

    protected Width|string|null $maxContentWidth = Width::SevenExtraLarge;

    public static string|Alignment $formActionsAlignment = Alignment::End;

    /** @param array<string, mixed> $data */
    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['license_status'] = 'active';
        $data['is_active'] = true;

        return $data;
    }

    protected function getCreateFormAction(): Action
    {
        return parent::getCreateFormAction()
            ->label('Simpan KUPVA')
            ->icon(Heroicon::OutlinedCheckBadge);
    }

    protected function getCreateAnotherFormAction(): Action
    {
        return parent::getCreateAnotherFormAction()
            ->label('Simpan & tambah lagi')
            ->icon(Heroicon::OutlinedPlusCircle);
    }

    protected function getCancelFormAction(): Action
    {
        return parent::getCancelFormAction()
            ->label('Kembali')
            ->icon(Heroicon::OutlinedArrowLeft);
    }

    protected function getCreatedNotificationTitle(): ?string
    {
        return 'Data KUPVA berhasil ditambahkan';
    }
}
