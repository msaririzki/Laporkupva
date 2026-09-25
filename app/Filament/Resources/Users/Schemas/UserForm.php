<?php

namespace App\Filament\Resources\Users\Schemas;

use App\Enums\UserRole;
use App\Rules\NoHtml;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Illuminate\Validation\Rules\Password;

class UserForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Informasi admin')
                    ->description('Lengkapi identitas dan akses akun dengan data yang benar.')
                    ->icon(Heroicon::OutlinedUserCircle)
                    ->iconColor('primary')
                    ->extraAttributes(['class' => 'admin-account-section'])
                    ->columnSpanFull()
                    ->columns(2)
                    ->schema([
                        TextInput::make('name')
                            ->label('Nama lengkap')
                            ->placeholder('Nama admin')
                            ->prefixIcon(Heroicon::OutlinedUser)
                            ->autocomplete('name')
                            ->required()
                            ->maxLength(255)
                            ->rule(new NoHtml),
                        TextInput::make('email')
                            ->label('Alamat email')
                            ->placeholder('nama@contoh.com')
                            ->prefixIcon(Heroicon::OutlinedEnvelope)
                            ->autocomplete('email')
                            ->email()
                            ->unique(ignoreRecord: true)
                            ->required()
                            ->maxLength(255),
                        TextInput::make('password')
                            ->label('Kata sandi')
                            ->placeholder(fn (string $operation): string => $operation === 'edit' ? 'Kosongkan jika tidak diubah' : 'Buat kata sandi yang kuat')
                            ->prefixIcon(Heroicon::OutlinedLockClosed)
                            ->autocomplete('new-password')
                            ->password()
                            ->revealable()
                            ->required(fn (string $operation): bool => $operation === 'create')
                            ->dehydrated(fn (?string $state): bool => filled($state))
                            ->minLength(12)
                            ->rule(Password::min(12)->mixedCase()->numbers()->symbols())
                            ->helperText(fn (string $operation): string => $operation === 'edit' ? 'Kosongkan jika tidak ingin mengubah kata sandi.' : 'Minimal 12 karakter dengan huruf besar, huruf kecil, angka, dan simbol.')
                            ->columnSpanFull(),
                        Toggle::make('is_active')
                            ->label('Akun aktif')
                            ->helperText(fn (string $operation): string => $operation === 'edit' ? 'Nonaktifkan untuk mencabut akses tanpa menghapus riwayat aktivitas.' : 'Admin dapat langsung masuk dan mulai bekerja setelah akun disimpan.')
                            ->extraFieldWrapperAttributes(['class' => 'admin-account-status'])
                            ->columnSpanFull()
                            ->default(true)
                            ->required(),
                        Hidden::make('role')->default(UserRole::Admin->value),
                    ]),
            ]);
    }
}
