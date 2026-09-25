<?php

namespace App\Filament\Resources\Users\Pages;

use App\Filament\Resources\Users\UserResource;
use Filament\Actions\Action;
use Filament\Resources\Pages\CreateRecord;
use Filament\Support\Enums\Alignment;
use Filament\Support\Enums\Width;
use Filament\Support\Icons\Heroicon;

class CreateUser extends CreateRecord
{
    protected static string $resource = UserResource::class;

    protected static bool $canCreateAnother = false;

    protected ?string $heading = 'Tambah admin';

    protected ?string $subheading = 'Buat akun baru untuk membantu mengelola laporan dan data KUPVA.';

    protected Width|string|null $maxContentWidth = Width::FiveExtraLarge;

    public static string|Alignment $formActionsAlignment = Alignment::End;

    protected function getCreateFormAction(): Action
    {
        return parent::getCreateFormAction()
            ->label('Simpan admin')
            ->icon(Heroicon::OutlinedUserPlus);
    }

    protected function getCancelFormAction(): Action
    {
        return parent::getCancelFormAction()
            ->label('Kembali')
            ->icon(Heroicon::OutlinedArrowLeft);
    }

    protected function getCreatedNotificationTitle(): ?string
    {
        return 'Akun admin berhasil dibuat';
    }
}
