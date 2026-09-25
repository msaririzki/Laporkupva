<?php

namespace App\Filament\AvatarProviders;

use Filament\AvatarProviders\Contracts\AvatarProvider;
use Filament\Facades\Filament;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class LocalInitialsAvatarProvider implements AvatarProvider
{
    public function get(Model $record): string
    {
        $segments = Str::of(Filament::getNameForDefaultAvatar($record))
            ->trim()
            ->explode(' ')
            ->filter()
            ->take(2);

        $initials = $segments
            ->map(fn (string $segment): string => Str::upper(Str::substr($segment, 0, 1)))
            ->implode('');

        $initials = filled($initials) ? $initials : 'AD';
        $escapedInitials = htmlspecialchars($initials, ENT_XML1 | ENT_QUOTES, 'UTF-8');
        $svg = <<<SVG
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 128 128" role="img" aria-label="Avatar {$escapedInitials}">
                <defs>
                    <linearGradient id="avatar" x1="0" y1="0" x2="1" y2="1">
                        <stop offset="0" stop-color="#173d70"/>
                        <stop offset="1" stop-color="#3f77b4"/>
                    </linearGradient>
                </defs>
                <rect width="128" height="128" rx="64" fill="url(#avatar)"/>
                <circle cx="35" cy="25" r="34" fill="#ffffff" fill-opacity=".10"/>
                <text x="64" y="69" fill="#ffffff" font-family="Arial, sans-serif" font-size="42" font-weight="700" text-anchor="middle" dominant-baseline="middle">{$escapedInitials}</text>
            </svg>
            SVG;

        return 'data:image/svg+xml;base64,'.base64_encode($svg);
    }
}
