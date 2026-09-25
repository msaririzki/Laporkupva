<?php

namespace App\Filament\Auth;

use Filament\Auth\Pages\EditProfile as BaseEditProfile;
use Filament\Forms\Components\FileUpload;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use SensitiveParameter;

class EditProfile extends BaseEditProfile
{
    protected string $view = 'filament.auth.edit-profile';

    public function getTitle(): string|Htmlable
    {
        return 'Profil saya';
    }

    public function getHeading(): string|Htmlable
    {
        return 'Profil saya';
    }

    public function getSubheading(): string|Htmlable|null
    {
        return 'Kelola identitas dan keamanan akun admin TAMBORA.';
    }

    public function defaultForm(Schema $schema): Schema
    {
        return parent::defaultForm($schema)
            ->inlineLabel(false);
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Identitas admin')
                    ->description('Foto dan nama ini tampil pada menu akun agar pengguna mudah dikenali.')
                    ->icon('heroicon-o-identification')
                    ->columns([
                        'default' => 1,
                        'md' => 2,
                    ])
                    ->schema([
                        FileUpload::make('avatar_path')
                            ->label('Foto profil')
                            ->helperText('JPG, PNG, atau WebP. Maksimal 2 MB; gambar otomatis dipotong dan diperkecil.')
                            ->disk('public')
                            ->directory('admin-avatars')
                            ->visibility('public')
                            ->avatar()
                            ->acceptedFileTypes([
                                'image/jpeg',
                                'image/png',
                                'image/webp',
                            ])
                            ->rule('extensions:jpg,jpeg,png,webp')
                            ->maxSize(2048)
                            ->imageEditor()
                            ->circleCropper()
                            ->preventFilePathTampering()
                            ->columnSpanFull()
                            ->extraAttributes(['class' => 'tambora-profile-avatar-upload']),
                        $this->getNameFormComponent()
                            ->label('Nama lengkap')
                            ->placeholder('Nama admin')
                            ->prefixIcon('heroicon-m-user'),
                        $this->getEmailFormComponent()
                            ->label('Alamat email')
                            ->placeholder('nama@domain.com')
                            ->prefixIcon('heroicon-m-envelope'),
                    ]),
                Section::make('Keamanan akun')
                    ->description('Kosongkan bagian ini jika Anda tidak ingin mengganti kata sandi.')
                    ->icon('heroicon-o-lock-closed')
                    ->collapsible()
                    ->collapsed()
                    ->columns([
                        'default' => 1,
                        'md' => 2,
                    ])
                    ->schema([
                        $this->getPasswordFormComponent()
                            ->label('Kata sandi baru')
                            ->placeholder('Masukkan kata sandi baru')
                            ->prefixIcon('heroicon-m-key'),
                        $this->getPasswordConfirmationFormComponent()
                            ->label('Ulangi kata sandi baru')
                            ->placeholder('Ulangi kata sandi baru')
                            ->prefixIcon('heroicon-m-check-circle'),
                        $this->getCurrentPasswordFormComponent()
                            ->label('Kata sandi saat ini')
                            ->belowContent('Diperlukan untuk mengonfirmasi perubahan email atau kata sandi.')
                            ->placeholder('Masukkan kata sandi saat ini')
                            ->prefixIcon('heroicon-m-shield-check')
                            ->columnSpanFull(),
                    ]),
            ]);
    }

    protected function handleRecordUpdate(Model $record, #[SensitiveParameter] array $data): Model
    {
        $previousAvatarPath = $record->getAttribute('avatar_path');
        $record = parent::handleRecordUpdate($record, $data);
        $currentAvatarPath = $record->getAttribute('avatar_path');

        if (filled($previousAvatarPath) && ($previousAvatarPath !== $currentAvatarPath)) {
            Storage::disk('public')->delete($previousAvatarPath);
        }

        return $record;
    }

    protected function afterSave(): void
    {
        $this->dispatch('refresh-topbar');
    }

    protected function getSavedNotificationTitle(): ?string
    {
        return 'Profil berhasil diperbarui';
    }
}
