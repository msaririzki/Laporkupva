<?php

namespace App\Filament\Resources\Users\Tables;

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
                TextColumn::make('name')->label('Nama')->searchable()->sortable()->weight('bold'),
                TextColumn::make('email')->label('Alamat email')->searchable()->copyable(),
                TextColumn::make('role')->label('Peran')->badge(),
                IconColumn::make('is_active')->label('Akses aktif')->boolean(),
                TextColumn::make('created_at')->label('Dibuat')->dateTime('d M Y, H:i')->sortable(),
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->emptyStateHeading('Belum ada akun admin')
            ->emptyStateDescription('Buat akun admin untuk membantu mengelola laporan.')
            ->emptyStateIcon('heroicon-o-user-group');
    }
}
