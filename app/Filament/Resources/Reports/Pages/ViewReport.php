<?php

namespace App\Filament\Resources\Reports\Pages;

use App\Filament\Resources\Reports\ReportResource;
use Filament\Actions\ActionGroup;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;
use Filament\Support\Enums\Width;
use Filament\Support\Icons\Heroicon;

class ViewReport extends ViewRecord
{
    protected static string $resource = ReportResource::class;

    protected Width|string|null $maxContentWidth = Width::Full;

    public function getTitle(): string
    {
        return "Laporan {$this->getRecord()->public_code}";
    }

    protected function getHeaderActions(): array
    {
        return [
            ReportResource::advanceStatusAction(),
            ActionGroup::make([
                ReportResource::addActivityEvidenceAction(),
                EditAction::make()
                    ->label('Edit catatan internal')
                    ->icon(Heroicon::OutlinedPencilSquare),
                ReportResource::correctStatusAction(),
            ])
                ->label('Lainnya')
                ->icon(Heroicon::OutlinedEllipsisHorizontal)
                ->color('gray')
                ->button(),
        ];
    }
}
