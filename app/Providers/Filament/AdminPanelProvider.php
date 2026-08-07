<?php

namespace App\Providers\Filament;

use App\Filament\Resources\MonitoringSurveyResource;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Pages;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\View\PanelsRenderHook;
use Filament\Widgets;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\AuthenticateSession;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\Support\HtmlString;
use Illuminate\View\Middleware\ShareErrorsFromSession;

class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->id('admin')
            ->path('admin')
            ->login()
            ->registration()

            // -----------------------------------------------------------------
            // Brand
            // -----------------------------------------------------------------
            ->brandName('SI-Mantra')

            ->brandLogo(fn () => new HtmlString('
                <div class="flex items-center gap-x-3">
                    <img src="' . asset('images/logobps.png') . '" class="h-8 w-auto" alt="Logo BPS">
                    <span class="text-base font-bold text-gray-900 dark:text-white tracking-wide">
                        SI-Mantra
                    </span>
                </div>
            '))

            // Sidebar dapat di-collapse
            ->sidebarCollapsibleOnDesktop()

            // Warna utama
            ->colors([
                'primary' => Color::Amber,
            ])

            // Custom CSS: Menghilangkan Tombol Dropdown & Rapat Langsung di Bawah Label
            ->renderHook(
                PanelsRenderHook::STYLES_AFTER,
                fn (): string => new HtmlString('
                    <style>
                        /* Sembunyikan tombol icon toggle collapse/dropdown pada header grup */
                        .fi-sidebar-group-button svg,
                        .fi-sidebar-group-button .fi-icon-btn {
                            display: none !important;
                        }

                        /* Menonaktifkan efek kursor klik/pointer pada header grup agar seperti teks biasa */
                        .fi-sidebar-group-button {
                            pointer-events: none !important;
                            cursor: default !important;
                        }

                        /* Memastikan item grup selalu terlihat (tidak bisa tersembunyi) */
                        .fi-sidebar-group-items {
                            display: flex !important;
                            flex-direction: column !important;
                            gap: 0px !important;
                        }

                        /* Kerapatan item menu navigasi */
                        .fi-sidebar-item {
                            margin-top: 0px !important;
                            margin-bottom: 0px !important;
                        }

                        /* Memperkecil padding atas-bawah tombol menu */
                        .fi-sidebar-item-button {
                            padding-top: 0.15rem !important;
                            padding-bottom: 0.15rem !important;
                            min-height: 2rem !important;
                        }

                        /* Mengatur jarak judul grup (INPUT DATA, DATA, MONITORING, SISTEM) */
                        .fi-sidebar-group-label {
                            margin-top: 0.25rem !important;
                            margin-bottom: 0rem !important;
                            padding-top: 0.1rem !important;
                            padding-bottom: 0.1rem !important;
                        }

                        /* Mengatur jarak antar grup */
                        .fi-sidebar-group {
                            margin-bottom: 0.15rem !important;
                            padding-top: 0px !important;
                            padding-bottom: 0px !important;
                        }
                    </style>
                ')
            )

            // Resources
            ->discoverResources(
                in: app_path('Filament/Resources'),
                for: 'App\\Filament\\Resources'
            )

            ->discoverPages(
                in: app_path('Filament/Pages'),
                for: 'App\\Filament\\Pages'
            )

            ->pages([
                Pages\Dashboard::class,
            ])

            ->resources([
                MonitoringSurveyResource::class,
            ])

            ->discoverWidgets(
                in: app_path('Filament/Widgets'),
                for: 'App\\Filament\\Widgets'
            )

            ->widgets([
                Widgets\AccountWidget::class,
            ])

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
            ]);
    }
}