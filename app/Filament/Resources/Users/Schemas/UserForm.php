<?php

namespace App\Filament\Resources\Users\Schemas;

use App\Enums\UserRole;
use App\Rules\NoHtml;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Illuminate\Validation\Rules\Password;

class UserForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Akun admin')
                    ->description('Admin dapat mengelola laporan dan data KUPVA, tetapi tidak dapat membuat akun admin lain.')
                    ->columns(2)
                    ->schema([
                        TextInput::make('name')
                            ->label('Nama lengkap')
                            ->required()
                            ->maxLength(255)
                            ->rule(new NoHtml),
                        TextInput::make('email')
                            ->label('Alamat email')
                            ->email()
                            ->unique(ignoreRecord: true)
                            ->required()
                            ->maxLength(255),
                        TextInput::make('password')
                            ->label('Kata sandi')
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
                            ->helperText('Nonaktifkan untuk mencabut akses Admin tanpa menghapus riwayat aktivitasnya.')
                            ->default(true)
                            ->required(),
                        Hidden::make('role')->default(UserRole::Admin->value),
                    ]),
            ]);
    }
}
