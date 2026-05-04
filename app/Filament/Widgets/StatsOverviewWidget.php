<?php

namespace App\Filament\Widgets;

use App\Models\Article;
use App\Models\Category;
use App\Models\Subscription;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverviewWidget extends BaseWidget
{
    protected static ?int $sort = 1;

    protected int | string | array $columnSpan = 'full';

    protected ?string $pollingInterval = null;

    protected function getStats(): array
    {
        $totalArticles = Article::count();
        $publishedArticles = Article::where('status', 'published')->count();
        $totalCategories = Category::where('is_active', true)->count();
        $activeSubscribers = Subscription::where('is_active', true)->count();
        $totalViews = Article::sum('view_count');

        return [
            Stat::make('Total Artikel', $totalArticles)
                ->description("{$publishedArticles} dipublikasikan")
                ->descriptionIcon('heroicon-o-document-text')
                ->color('info'),

            Stat::make('Kategori Aktif', $totalCategories)
                ->description('Kategori berita')
                ->descriptionIcon('heroicon-o-tag')
                ->color('success'),

            Stat::make('Subscriber Aktif', $activeSubscribers)
                ->description('Langganan email')
                ->descriptionIcon('heroicon-o-envelope')
                ->color('warning'),

            Stat::make('Total Views', number_format($totalViews))
                ->description('Semua artikel')
                ->descriptionIcon('heroicon-o-eye')
                ->color('danger'),
        ];
    }
}
