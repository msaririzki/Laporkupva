<?php

namespace App\Filament\Resources\ReportProgressRequests\Tables;

use App\Models\ReportProgressRequest;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class ReportProgressRequestsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->defaultSort('created_at', 'desc')
            ->paginated([10, 25, 50])
            ->searchPlaceholder('Cari nomor laporan atau pengaju…')
            ->poll('30s')
            ->columns([
                TextColumn::make('report.public_code')->label('Nomor laporan')->searchable()->weight('bold')
                    ->description(fn (ReportProgressRequest $record): ?string => $record->report->business_name),
                TextColumn::make('to_status')->label('Tahap yang diajukan')->badge()->wrap(),
                TextColumn::make('requester.name')->label('Diajukan oleh')->searchable()->placeholder('Petugas'),
                TextColumn::make('internal_note')->label('Catatan Operator')->placeholder('Tidak ada catatan tambahan')
                    ->limit(90)->wrap(),
                TextColumn::make('photo_count')->label('Foto')->state(fn (ReportProgressRequest $record): int => count($record->activity_photos ?? []))
                    ->suffix(' foto'),
                TextColumn::make('created_at')->label('Diajukan pada')->dateTime('d M Y, H:i')->sortable(),
            ])
            ->recordActions([
                ViewAction::make()->label('Tinjau pengajuan')->button()->size('sm'),
            ])
            ->recordActionsColumnLabel('Aksi')
            ->emptyStateHeading('Tidak ada pengajuan pada kategori ini')
            ->emptyStateDescription('Pengajuan progres dari Operator akan tampil di sini untuk ditinjau Administrator.')
            ->emptyStateIcon('heroicon-o-check-badge');
    }
}
