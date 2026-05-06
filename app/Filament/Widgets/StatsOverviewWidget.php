<?php

namespace App\Filament\Widgets;

use App\Models\Article;
use App\Models\Category;
use App\Models\Subscription;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Facades\Cache;

class StatsOverviewWidget extends BaseWidget
{
    protected static ?int $sort = 1;

    protected int | string | array $columnSpan = 'full';

    protected ?string $pollingInterval = null;

    protected function getStats(): array
    {
        $stats = Cache::remember('admin_stats_overview', now()->addMinutes(5), function () {
            return [
                'totalArticles'     => Article::count(),
                'publishedArticles' => Article::where('status', 'published')->count(),
                'totalCategories'   => Category::where('is_active', true)->count(),
                'activeSubscribers' => Subscription::where('is_active', true)->count(),
                'totalViews'        => Article::sum('view_count'),
            ];
        });

        return [
            Stat::make('Total Artikel', $stats['totalArticles'])
                ->description("{$stats['publishedArticles']} dipublikasikan")
                ->descriptionIcon('heroicon-o-document-text')
                ->color('info'),

            Stat::make('Kategori Aktif', $stats['totalCategories'])
                ->description('Kategori berita')
                ->descriptionIcon('heroicon-o-tag')
                ->color('success'),

            Stat::make('Subscriber Aktif', $stats['activeSubscribers'])
                ->description('Langganan email')
                ->descriptionIcon('heroicon-o-envelope')
                ->color('warning'),

            Stat::make('Total Views', number_format($stats['totalViews']))
                ->description('Semua artikel')
                ->descriptionIcon('heroicon-o-eye')
                ->color('danger'),
        ];
    }
}
