<?php

namespace App\Filament\Resources\Users\Tables;

use App\Models\User;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class UsersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->defaultSort('name')
            ->columns([
                TextColumn::make('name')
                    ->label('Nama admin')
                    ->searchable()
                    ->sortable()
                    ->weight('bold')
                    ->description(fn (User $record): string => $record->email),
                TextColumn::make('email')->label('Alamat email')->searchable()->copyable()->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('role')->label('Peran')->badge()->visibleFrom('md'),
                IconColumn::make('is_active')->label('Akses aktif')->boolean()->visibleFrom('md'),
                TextColumn::make('created_at')->label('Dibuat')->dateTime('d M Y, H:i')->sortable()->visibleFrom('lg'),
            ])
            ->recordActions([
                EditAction::make()->iconButton()->tooltip('Ubah akun admin'),
            ])
            ->emptyStateHeading('Belum ada akun admin')
            ->emptyStateDescription('Buat akun admin untuk membantu mengelola laporan.')
            ->emptyStateIcon('heroicon-o-user-group');
    }
}
