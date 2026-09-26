<?php

namespace App\Providers\Filament;

use App\Filament\Auth\EditProfile;
use App\Filament\Auth\Login;
use App\Filament\AvatarProviders\LocalInitialsAvatarProvider;
use App\Filament\Pages\Dashboard;
use App\Filament\Resources\Kupvas\KupvaResource;
use App\Filament\Resources\Kupvas\Pages\ViewKupva;
use App\Filament\Resources\Reports\Pages\ViewReport;
use App\Filament\Resources\Reports\ReportResource;
use Filament\Auth\MultiFactor\App\AppAuthentication;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Navigation\NavigationItem;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\Support\Icons\Heroicon;
use Filament\View\PanelsRenderHook;
use Illuminate\Contracts\View\View;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\PreventRequestForgery;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;

class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        $panel = $panel
            ->default()
            ->id('admin')
            ->path('admin')
            ->viteTheme('resources/css/filament/admin/theme.css')
            ->login(Login::class)
            ->profile(EditProfile::class, isSimple: false)
            ->defaultAvatarProvider(LocalInitialsAvatarProvider::class)
            ->brandName('TAMBORA · BI NTB')
            ->brandLogo(asset('images/brand/tambora.webp'))
            ->brandLogoHeight('3rem')
            ->favicon(asset('images/brand/tambora.webp'))
            ->darkMode(false)
            ->globalSearch(false)
            ->databaseNotifications()
            ->databaseNotificationsPolling('60s')
            ->colors([
                'primary' => Color::Blue,
            ])
            ->spa()
            ->spaUrlExceptions([
                url('/admin/ekspor/*'),
                url('/admin/lampiran-laporan/*'),
            ])
            ->sidebarCollapsibleOnDesktop()
            ->sidebarWidth('17rem')
            ->navigationItems([
                NavigationItem::make('Portal Publik')
                    ->icon(Heroicon::OutlinedGlobeAlt)
                    ->url(fn (): string => route('home'))
                    ->openUrlInNewTab()
                    ->sort(1000),
            ])
            ->renderHook(
                PanelsRenderHook::SIMPLE_LAYOUT_START,
                fn (): View => view('filament.auth.login-intro'),
                scopes: Login::class,
            )
            ->renderHook(
                PanelsRenderHook::SIMPLE_PAGE_END,
                fn (): View => view('filament.auth.login-home-link'),
                scopes: Login::class,
            )
            ->renderHook(
                PanelsRenderHook::BODY_END,
                fn (): View => view('filament.shared.notification-conversation-navigation'),
            )
            ->renderHook(
                PanelsRenderHook::BODY_END,
                fn (): View => view('filament.shared.floating-back-button', [
                    'url' => ReportResource::getUrl('index'),
                    'label' => 'Kembali ke daftar laporan',
                ]),
                scopes: ViewReport::class,
            )
            ->renderHook(
                PanelsRenderHook::BODY_END,
                fn (): View => view('filament.shared.floating-back-button', [
                    'url' => KupvaResource::getUrl('index'),
                    'label' => 'Kembali ke daftar KUPVA',
                ]),
                scopes: ViewKupva::class,
            )
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\Filament\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\Filament\Pages')
            ->pages([
                Dashboard::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\Filament\Widgets')
            ->widgets([])
            ->middleware([
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                AuthenticateSession::class,
                ShareErrorsFromSession::class,
                PreventRequestForgery::class,
                SubstituteBindings::class,
                DisableBladeIconComponents::class,
                DispatchServingFilamentEvent::class,
            ])
            ->authMiddleware([
                Authenticate::class,
            ]);

        if ((bool) config('tambora.require_admin_mfa')) {
            $panel->multiFactorAuthentication([
                AppAuthentication::make()
                    ->brandName('TAMBORA · BI NTB')
                    ->recoverable()
                    ->recoveryCodeCount(10)
                    ->codeWindow(4),
            ], isRequired: true);
        }

        return $panel;
    }
}
