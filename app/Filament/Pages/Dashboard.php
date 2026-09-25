<?php

namespace App\Filament\Pages;

use Filament\Pages\Dashboard as BaseDashboard;

class Dashboard extends BaseDashboard
{
    protected static ?string $navigationLabel = 'Dasbor';

    public function getHeading(): string
    {
        return 'Ringkasan Laporan';
    }

    /**
     * @return int|array<string, int>
     */
    public function getColumns(): int|array
    {
        return [
            'md' => 2,
            'xl' => 12,
        ];
    }
}
