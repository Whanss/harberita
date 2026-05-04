<?php

namespace App\Providers;

use Filament\Support\Facades\FilamentView;
use Filament\View\PanelsRenderHook;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void {}

    public function boot(): void
    {
        FilamentView::registerRenderHook(
            PanelsRenderHook::HEAD_END,
            fn (): string => Blade::render('<style>
                /* ── Global Transitions ── */
                * {
                    transition: background-color 0.2s ease, border-color 0.2s ease, box-shadow 0.2s ease;
                }

                /* ── Sidebar Animations & Collapse State ── */
                @keyframes sidebarExpand {
                    from {
                        width: 60px;
                        opacity: 0.8;
                    }
                    to {
                        width: 200px;
                        opacity: 1;
                    }
                }

                @keyframes sidebarCollapse {
                    from {
                        width: 200px;
                        opacity: 1;
                    }
                    to {
                        width: 60px;
                        opacity: 0.8;
                    }
                }

                /* ── Sidebar Modern Design ── */
                .fi-sidebar {
                    width: 200px !important;
                    min-width: 200px !important;
                    max-width: 200px !important;
                    border-right: 1px solid rgba(113, 113, 122, 0.1) !important;
                    box-shadow: -2px 0 8px rgba(0, 0, 0, 0.1) !important;
                    transition: width 0.4s cubic-bezier(0.4, 0, 0.2, 1),
                                min-width 0.4s cubic-bezier(0.4, 0, 0.2, 1) !important;
                }

                /* Collapsed sidebar state */
                .fi-sidebar.fi-collapsed {
                    width: 60px !important;
                    min-width: 60px !important;
                    max-width: 60px !important;
                }

                /* Sidebar text hidden on collapse */
                .fi-sidebar.fi-collapsed .fi-sidebar-group-label,
                .fi-sidebar.fi-collapsed .fi-sidebar-item-label {
                    display: none !important;
                }

                /* Dark mode sidebar: gray gradient (not blue) */
                .dark .fi-sidebar {
                    background: linear-gradient(180deg, #27272a 0%, #18181b 100%) !important;
                    border-right-color: rgba(113, 113, 122, 0.2) !important;
                    box-shadow: -2px 0 12px rgba(0, 0, 0, 0.2) !important;
                }

                /* Modern topbar */
                .dark .fi-topbar-nav {
                    background: linear-gradient(90deg, #27272a 0%, #1f1f23 100%) !important;
                    border-bottom: 1px solid rgba(113, 113, 122, 0.2) !important;
                    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.15) !important;
                }

                /* Main content background */
                .dark .fi-main {
                    background-color: #0f0f12 !important;
                }

                /* Modern cards dengan glow effect */
                .dark .fi-card,
                .dark .fi-section,
                .dark .fi-wi-stats-overview-stat,
                .dark .fi-ta-ctn {
                    background: linear-gradient(135deg, rgba(39, 39, 42, 0.8) 0%, rgba(24, 24, 27, 0.8) 100%) !important;
                    border: 1px solid rgba(113, 113, 122, 0.2) !important;
                    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2) !important;
                    border-radius: 10px !important;
                }

                /* Smooth nav item interactions */
                .dark .fi-sidebar-item-button {
                    border-radius: 8px !important;
                    margin-left: 8px !important;
                    margin-right: 8px !important;
                    transition: all 0.3s ease !important;
                }

                .dark .fi-sidebar-item-button:hover {
                    background: rgba(113, 113, 122, 0.15) !important;
                    border-color: rgba(113, 113, 122, 0.3) !important;
                    transform: translateX(4px);
                }

                .dark .fi-sidebar-item-button.fi-active {
                    background: linear-gradient(135deg, rgba(113, 113, 122, 0.2) 0%, rgba(120, 113, 108, 0.1) 100%) !important;
                    border-left: 3px solid #71717a !important;
                    padding-left: calc(12px - 3px) !important;
                    box-shadow: inset 0 0 8px rgba(113, 113, 122, 0.1) !important;
                }

                /* ── Nav items compact ── */
                .fi-sidebar-item-button {
                    padding-top: 8px !important;
                    padding-bottom: 8px !important;
                    font-size: 0.8125rem !important;
                    font-weight: 500 !important;
                }

                /* ── Nav group label modern ── */
                .fi-sidebar-group-label {
                    font-size: 0.65rem !important;
                    padding-top: 12px !important;
                    padding-bottom: 6px !important;
                    letter-spacing: 0.08em !important;
                    font-weight: 700 !important;
                    color: rgba(113, 113, 122, 0.7) !important;
                    text-transform: uppercase !important;
                    padding-left: 8px !important;
                    transition: opacity 0.3s ease !important;
                }

                /* ── Reduced gaps ── */
                .fi-sidebar-nav {
                    gap: 2px !important;
                }

                .fi-sidebar-group {
                    gap: 4px !important;
                }

                /* ── Stats card icon colors with glow ── */
                .fi-wi-stats-overview-stat:nth-child(1) {
                    border-left: 3px solid #3b82f6 !important;
                }

                .fi-wi-stats-overview-stat:nth-child(1) svg {
                    color: #3b82f6 !important;
                    filter: drop-shadow(0 0 4px rgba(59, 130, 246, 0.4)) !important;
                }

                .fi-wi-stats-overview-stat:nth-child(2) {
                    border-left: 3px solid #f59e0b !important;
                }

                .fi-wi-stats-overview-stat:nth-child(2) svg {
                    color: #f59e0b !important;
                    filter: drop-shadow(0 0 4px rgba(245, 158, 11, 0.4)) !important;
                }

                .fi-wi-stats-overview-stat:nth-child(3) {
                    border-left: 3px solid #10b981 !important;
                }

                .fi-wi-stats-overview-stat:nth-child(3) svg {
                    color: #10b981 !important;
                    filter: drop-shadow(0 0 4px rgba(16, 185, 129, 0.4)) !important;
                }

                .fi-wi-stats-overview-stat:nth-child(4) {
                    border-left: 3px solid #8b5cf6 !important;
                }

                .fi-wi-stats-overview-stat:nth-child(4) svg {
                    color: #8b5cf6 !important;
                    filter: drop-shadow(0 0 4px rgba(139, 92, 246, 0.4)) !important;
                }

                /* ── Stats card styling ── */
                .fi-wi-stats-overview-stat {
                    padding: 16px 18px !important;
                    border-radius: 10px !important;
                    transition: all 0.3s ease !important;
                }

                .fi-wi-stats-overview-stat:hover {
                    transform: translateY(-2px) !important;
                    box-shadow: 0 8px 16px rgba(0, 0, 0, 0.3) !important;
                }

                /* ── Brand/Logo styling ── */
                .fi-topbar .fi-logo-section {
                    font-weight: 700 !important;
                    font-size: 1rem !important;
                    background: linear-gradient(135deg, #6366f1 0%, #8b5cf6 100%) !important;
                    -webkit-background-clip: text !important;
                    -webkit-text-fill-color: transparent !important;
                    background-clip: text !important;
                }

                /* ── Smooth scrollbar ── */
                .fi-sidebar::-webkit-scrollbar {
                    width: 6px !important;
                }

                .fi-sidebar::-webkit-scrollbar-track {
                    background: transparent !important;
                }

                .fi-sidebar::-webkit-scrollbar-thumb {
                    background: rgba(99, 102, 241, 0.3) !important;
                    border-radius: 3px !important;
                }

                .fi-sidebar::-webkit-scrollbar-thumb:hover {
                    background: rgba(99, 102, 241, 0.5) !important;
                }

                [x-cloak] { display: none !important; }
            </style>'),
        );
    }
}
