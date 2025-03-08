<?php

namespace App\Providers\Filament;

use Filament\Pages;
use Filament\Panel;
use App\Models\Siswa;
use Filament\Widgets;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Illuminate\Support\Facades\Log;
use Filament\Support\Enums\MaxWidth;
use Illuminate\Support\Facades\Auth;
use Filament\Navigation\NavigationItem;
use Filament\Http\Middleware\Authenticate;
use Illuminate\Session\Middleware\StartSession;
use Devonab\FilamentEasyFooter\EasyFooterPlugin;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\AuthenticateSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;

class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->id('admin')
            ->path('admin')
            ->login()
            ->colors([
                'primary' => Color::Cyan,
            ])
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\\Filament\\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\\Filament\\Pages')
            ->pages([
                Pages\Dashboard::class,
            ])
            ->spa()
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\\Filament\\Widgets')
            ->widgets([
                // Widgets\AccountWidget::class,
                // Widgets\FilamentInfoWidget::class,
            ])
            ->databaseNotifications()
            ->maxContentWidth(MaxWidth::Full)
            ->middleware([
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                AuthenticateSession::class,
                ShareErrorsFromSession::class,
                VerifyCsrfToken::class,
                SubstituteBindings::class,
                DisableBladeIconComponents::class,
                DispatchServingFilamentEvent::class,
            ])
            ->authMiddleware([
                Authenticate::class,
            ])
            ->plugins([
                EasyFooterPlugin::make()
                    ->withFooterPosition('footer')
                    ->withLoadTime('Halaman ini dimuat dalam')
                    ->withLinks([
                        // ['title' => 'Official Website', 'url' => 'https://www.mtsn1pandeglang.sch.id/'],
                        // ['title' => 'Kebijakan Privasi', 'url' => 'https://www.mtsn1pandeglang.sch.id/kebijakan-privasi/'],
                        // ['title' => 'Tentang Kami', 'url' => 'https://www.mtsn1pandeglang.sch.id/about/'],
                        // ['title' => 'Kontak', 'url' => 'https://wa.me/6289612050291/?text=Hallo,%20PTSP%20MTs%20Negeri%201%20Pandeglang.'],
                        ['title' => 'Dibuat dan dikembangkan dengan ❤ oleh Yahya Zulfikri', 'url' => 'https://instagram.com/zulfikriyahya_'],
                    ])
                    ->withBorder()
                // ->hiddenFromPagesEnabled()
                // ->hiddenFromPages(['admin/login', 'admin/forgot-password', 'admin/register', 'admin/email/verify']),
            ]);
    }
}
