<?php

namespace App\Filament\Widgets;

use App\Filament\Resources\Kupvas\KupvaResource;
use App\Filament\Resources\Reports\ReportResource;
use App\Filament\Resources\Users\UserResource;
use Filament\Widgets\Widget;

class WelcomeOverview extends Widget
{
    protected static bool $isDiscovered = false;

    protected static bool $isLazy = false;

    protected static ?int $sort = 0;

    protected string $view = 'filament.widgets.welcome-overview';

    protected int|string|array $columnSpan = [
        'default' => 1,
        'md' => 2,
        'xl' => 12,
    ];

    /** @return array<string, mixed> */
    protected function getViewData(): array
    {
        return [
            'adminName' => auth()->user()?->name ?? 'Admin',
            'currentDate' => now()->translatedFormat('l, d F Y'),
            'reportsUrl' => ReportResource::getUrl('index'),
            'kupvasUrl' => KupvaResource::getUrl('index'),
            'usersUrl' => UserResource::getUrl('index'),
            'canManageAdmins' => auth()->user()?->isSuperAdmin() === true,
        ];
    }
}
