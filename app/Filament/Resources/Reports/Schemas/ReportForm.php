<?php

namespace App\Filament\Resources\Reports\Schemas;

use App\Rules\NoHtml;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class ReportForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Catatan penanganan internal')
                    ->description('Catatan ini hanya terlihat oleh admin dan tidak ditampilkan kepada pelapor.')
                    ->schema([
                        Textarea::make('internal_notes')
                            ->label('Catatan internal')
                            ->rows(8)
                            ->maxLength(5000)
                            ->rule(new NoHtml)
                            ->columnSpanFull(),
                    ]),
            ]);
    }
}
