<?php

namespace App\Filament\Resources\Users\Tables;

use App\Models\User;
use Filament\Actions\Action;
use Filament\Actions\EditAction;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class UsersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->defaultSort('name')
            ->paginated([10, 25, 50])
            ->defaultPaginationPageOption(10)
            ->searchPlaceholder('Cari nama atau email admin…')
            ->searchDebounce('350ms')
            ->stackedOnMobile()
            ->columns([
                TextColumn::make('name')
                    ->label('Nama admin')
                    ->searchable()
                    ->weight('bold')
                    ->description(fn (User $record): string => $record->email),
                TextColumn::make('email')->label('Alamat email')->searchable()->copyable()->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('role')->label('Peran')->badge()->visibleFrom('md'),
                IconColumn::make('is_active')->label('Akses aktif')->boolean()->visibleFrom('md'),
                TextColumn::make('created_at')->label('Dibuat')->dateTime('d M Y, H:i')->visibleFrom('lg'),
            ])
            ->columnManagerTriggerAction(
                fn (Action $action): Action => $action
                    ->button()
                    ->label('Atur')
                    ->icon(Heroicon::OutlinedViewColumns)
                    ->color('gray'),
            )
            ->recordActions([
                EditAction::make()->iconButton()->tooltip('Ubah akun admin'),
            ])
            ->recordClasses('admin-user-row')
            ->emptyStateHeading('Belum ada akun admin')
            ->emptyStateDescription('Buat akun admin untuk membantu mengelola laporan.')
            ->emptyStateIcon('heroicon-o-user-group');
    }
}
