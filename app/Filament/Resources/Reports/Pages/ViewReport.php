<?php

namespace App\Filament\Resources\Reports\Pages;

use App\Filament\Resources\Reports\ReportResource;
use Filament\Actions\Action;
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
            Action::make('openConversation')
                ->label('Buka percakapan')
                ->icon(Heroicon::OutlinedChatBubbleLeftRight)
                ->iconButton()
                ->tooltip('Buka percakapan')
                ->color('gray')
                ->extraAttributes(['data-open-conversation' => 'true'])
                ->actionJs(<<<'JS'
                    if (typeof window.tamboraFocusConversationReply === 'function') {
                        window.tamboraFocusConversationReply(true)
                    } else {
                        const conversation = document.getElementById('infolist.komunikasi-anonim::section')
                        const replyField = conversation?.querySelector('#admin-report-reply')
                        const focusTarget = replyField ?? conversation

                        focusTarget?.scrollIntoView({ behavior: 'smooth', block: 'center' })
                        window.setTimeout(() => replyField?.focus({ preventScroll: true }), 350)
                    }
                    JS),
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
