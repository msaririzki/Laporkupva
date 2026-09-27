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

    protected ?string $subheading = 'Lengkapi identitas, izin, dan lokasi usaha. Data dapat diperbarui kembali setelah disimpan.';

    protected Width|string|null $maxContentWidth = Width::SevenExtraLarge;

    public static string|Alignment $formActionsAlignment = Alignment::End;

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
