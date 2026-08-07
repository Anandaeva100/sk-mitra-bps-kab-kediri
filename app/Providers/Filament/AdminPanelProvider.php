<?php

namespace App\Providers\Filament;

use App\Filament\Resources\MonitoringSurveyResource;
use App\Filament\Pages\RekapanSemuaData;
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

            // Custom CSS: Menghilangkan tombol dropdown & merapatkan sidebar
            ->renderHook(
                PanelsRenderHook::STYLES_AFTER,
                fn (): string => new HtmlString('
                    <style>
                        /* =====================================================
                        SIDEBAR - CUSTOM SPACING
                        ===================================================== */

                        /* Sembunyikan tombol icon toggle collapse/dropdown */
                        .fi-sidebar-group-button svg,
                        .fi-sidebar-group-button .fi-icon-btn {
                            display: none !important;
                        }

                        /* Header group tidak bisa diklik */
                        .fi-sidebar-group-button {
                            pointer-events: none !important;
                            cursor: default !important;
                        }

                        /* Semua item dalam group selalu ditampilkan */
                        .fi-sidebar-group-items {
                            display: flex !important;
                            flex-direction: column !important;
                            gap: 0 !important;
                        }

                        /* =====================================================
                        ITEM MENU
                        ===================================================== */

                        /* Hilangkan jarak antar item */
                        .fi-sidebar-item {
                            margin-top: 0 !important;
                            margin-bottom: 0 !important;
                        }

                        /* Tinggi dan padding item menu */
                        .fi-sidebar-item-button {
                            min-height: 2.25rem !important;
                            padding-top: 0.25rem !important;
                            padding-bottom: 0.25rem !important;
                        }

                        /* =====================================================
                        LABEL GROUP
                        ===================================================== */

                        .fi-sidebar-group-label {
                            margin-top: 0.5rem !important;
                            margin-bottom: 0.2rem !important;
                            padding-top: 0 !important;
                            padding-bottom: 0 !important;
                        }

                        /* Group pertama tidak perlu jarak atas terlalu besar */
                        .fi-sidebar-group:first-child {
                            padding-top: 0 !important;
                            margin-top: 0 !important;
                        }

                        /* =====================================================
                        JARAK ANTAR GROUP
                        ===================================================== */

                        .fi-sidebar-group {
                            margin-top: 0 !important;
                            margin-bottom: 0.5rem !important;
                            padding-top: 0 !important;
                            padding-bottom: 0 !important;
                        }

                        /* Mengurangi gap bawaan antar navigation group */
                        .fi-sidebar-nav-groups {
                            gap: 0.25rem !important;
                        }

                        /* =====================================================
                        SIDEBAR NAVIGATION
                        ===================================================== */

                        .fi-sidebar-nav {
                            padding-top: 1.25rem !important;
                            padding-bottom: 0.5rem !important;
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
                RekapanSemuaData::class,
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