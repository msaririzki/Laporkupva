<?php

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;

enum UserRole: string implements HasLabel
{
    case SuperAdmin = 'super_admin';
    case Admin = 'admin';
    case Police = 'police';

    public function label(): string
    {
        return match ($this) {
            self::SuperAdmin => 'Administrator',
            self::Admin => 'Operator',
            self::Police => 'APH',
        };
    }

    public function getLabel(): ?string
    {
        return $this->label();
    }
}
