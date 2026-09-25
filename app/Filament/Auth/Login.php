<?php

namespace App\Filament\Auth;

use Filament\Actions\Action;
use Filament\Auth\Pages\Login as BaseLogin;
use Filament\Schemas\Components\Component;
use Illuminate\Contracts\Support\Htmlable;

class Login extends BaseLogin
{
    public function getTitle(): string|Htmlable
    {
        return 'Masuk Admin';
    }

    public function getHeading(): string|Htmlable|null
    {
        return 'Masuk ke TAMBORA';
    }

    public function getSubheading(): string|Htmlable|null
    {
        return 'Gunakan akun admin Anda.';
    }

    public function hasLogo(): bool
    {
        return false;
    }

    protected function getEmailFormComponent(): Component
    {
        return parent::getEmailFormComponent()
            ->label('Email admin')
            ->placeholder('nama@domain.com')
            ->prefixIcon('heroicon-m-envelope');
    }

    protected function getPasswordFormComponent(): Component
    {
        return parent::getPasswordFormComponent()
            ->label('Kata sandi')
            ->placeholder('Masukkan kata sandi')
            ->prefixIcon('heroicon-m-lock-closed');
    }

    protected function getAuthenticateFormAction(): Action
    {
        return parent::getAuthenticateFormAction()
            ->label('Masuk')
            ->icon('heroicon-m-arrow-right-on-rectangle');
    }
}
