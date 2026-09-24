<?php

namespace App\Filament\Resources\Kupvas;

use App\Filament\Resources\Kupvas\Pages\CreateKupva;
use App\Filament\Resources\Kupvas\Pages\EditKupva;
use App\Filament\Resources\Kupvas\Pages\ListKupvas;
use App\Filament\Resources\Kupvas\Pages\ViewKupva;
use App\Filament\Resources\Kupvas\Schemas\KupvaForm;
use App\Filament\Resources\Kupvas\Schemas\KupvaInfolist;
use App\Filament\Resources\Kupvas\Tables\KupvasTable;
use App\Models\Kupva;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class KupvaResource extends Resource
{
    protected static ?string $model = Kupva::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedBuildingOffice2;

    protected static ?string $modelLabel = 'KUPVA';

    protected static ?string $pluralModelLabel = 'Data KUPVA';

    protected static ?string $navigationLabel = 'Data KUPVA';

    protected static ?string $slug = 'kupva';

    protected static ?int $navigationSort = 2;

    protected static ?string $recordTitleAttribute = 'name';

    public static function form(Schema $schema): Schema
    {
        return KupvaForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return KupvaInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return KupvasTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListKupvas::route('/'),
            'create' => CreateKupva::route('/create'),
            'view' => ViewKupva::route('/{record}'),
            'edit' => EditKupva::route('/{record}/edit'),
        ];
    }
}
