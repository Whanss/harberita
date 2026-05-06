<?php

namespace App\Providers;

use Filament\Support\Facades\FilamentView;
use Filament\View\PanelsRenderHook;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void {}

    public function boot(): void
    {
        // CSS sudah ada di resources/css/filament/admin/theme.css (di-compile via Vite)
        // JS sidebar behavior dimuat sebagai file statis
        FilamentView::registerRenderHook(
            PanelsRenderHook::BODY_END,
            fn (): string => '<script src="' . asset('js/admin-sidebar.js') . '" defer></script>',
        );
    }
}
