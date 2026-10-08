<?php

namespace App\Filament\Resources\ReportProgressRequests;

use App\Filament\Resources\ReportProgressRequests\Pages\ListReportProgressRequests;
use App\Filament\Resources\ReportProgressRequests\Pages\ViewReportProgressRequest;
use App\Filament\Resources\ReportProgressRequests\Schemas\ReportProgressRequestInfolist;
use App\Filament\Resources\ReportProgressRequests\Tables\ReportProgressRequestsTable;
use App\Models\ReportProgressRequest;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class ReportProgressRequestResource extends Resource
{
    protected static ?string $model = ReportProgressRequest::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedCheckBadge;

    protected static ?string $modelLabel = 'pengajuan progres';

    protected static ?string $pluralModelLabel = 'Persetujuan Progres';

    protected static ?string $navigationLabel = 'Persetujuan Progres';

    protected static ?string $slug = 'persetujuan-progres';

    protected static ?int $navigationSort = 2;

    public static function getNavigationBadge(): ?string
    {
        $pendingCount = ReportProgressRequest::query()->where('status', 'pending')->count();

        return $pendingCount > 0 ? (string) $pendingCount : null;
    }

    public static function getNavigationBadgeColor(): ?string
    {
        return 'warning';
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->with(['report', 'requester', 'reviewer']);
    }

    public static function infolist(Schema $schema): Schema
    {
        return ReportProgressRequestInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return ReportProgressRequestsTable::configure($table);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListReportProgressRequests::route('/'),
            'view' => ViewReportProgressRequest::route('/{record}'),
        ];
    }
}
