<?php

namespace App\Filament\Resources\ReportProgressRequests\Schemas;

use App\Models\ReportProgressRequest;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\View;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;

class ReportProgressRequestInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Catatan Operator')
                ->description('Penjelasan kegiatan untuk tahap yang sedang diajukan. Catatan ini tidak ditampilkan kepada pelapor.')
                ->icon(Heroicon::OutlinedPencilSquare)
                ->schema([
                    TextEntry::make('internal_note')->label('Catatan Operator')->hiddenLabel()->placeholder('Operator tidak menambahkan catatan kegiatan.')
                        ->formatStateUsing(fn (string $state): string => nl2br(e(trim($state))))->html(),
                ])->columnSpanFull(),
            Section::make('Foto dokumentasi pengajuan')
                ->description('Dokumentasi yang dilampirkan untuk pengajuan ini.')
                ->icon(Heroicon::OutlinedPhoto)
                ->schema([View::make('filament.schemas.components.report-progress-photos')])->columnSpanFull(),
            Section::make('Informasi untuk pelapor')
                ->description('Pesan ini akan ditampilkan kepada pelapor setelah pengajuan disetujui.')
                ->icon(Heroicon::OutlinedChatBubbleLeftRight)
                ->schema([
                    TextEntry::make('public_note')->label('Informasi untuk pelapor')->hiddenLabel()->formatStateUsing(fn (string $state): string => nl2br(e(trim($state))))->html(),
                ])->columnSpanFull(),
            Section::make('Keputusan Administrator')
                ->visible(fn (ReportProgressRequest $record): bool => $record->status !== 'pending')
                ->schema([
                    TextEntry::make('status')->label('Keputusan')->badge()
                        ->formatStateUsing(fn (string $state): string => $state === 'approved' ? 'Disetujui' : 'Ditolak')
                        ->color(fn (string $state): string => $state === 'approved' ? 'success' : 'danger'),
                    TextEntry::make('reviewer.name')->label('Ditinjau oleh')->placeholder('Administrator'),
                    TextEntry::make('reviewed_at')->label('Ditinjau pada')->dateTime('d M Y, H:i'),
                    TextEntry::make('rejection_reason')->label('Alasan penolakan')
                        ->visible(fn (ReportProgressRequest $record): bool => $record->status === 'rejected')
                        ->formatStateUsing(fn (string $state): string => nl2br(e(trim($state))))->html()->columnSpanFull(),
                ])->columns(['default' => 1, 'sm' => 3])->columnSpanFull(),
        ]);
    }
}
