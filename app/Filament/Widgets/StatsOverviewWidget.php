<?php

namespace App\Filament\Widgets;

use App\Models\Article;
use App\Models\Category;
use App\Models\Comment;
use App\Models\Subscription;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverviewWidget extends BaseWidget
{
    protected static ?int $sort = 1;

    protected function getStats(): array
    {
        $totalArticles = Article::count();
        $publishedArticles = Article::where('status', 'published')->count();
        $totalCategories = Category::where('is_active', true)->count();
        $pendingComments = Comment::where('status', 'pending')->count();
        $activeSubscribers = Subscription::where('is_active', true)->count();
        $totalViews = Article::sum('view_count');

        return [
            Stat::make('Total Artikel', $totalArticles)
                ->description("{$publishedArticles} dipublikasikan")
                ->descriptionIcon('heroicon-m-document-text')
                ->color('primary')
                ->chart(
                    Article::selectRaw('COUNT(*) as count')
                        ->where('created_at', '>=', now()->subDays(7))
                        ->groupByRaw('DATE(created_at)')
                        ->pluck('count')
                        ->toArray()
                ),

            Stat::make('Kategori Aktif', $totalCategories)
                ->description('Kategori berita')
                ->descriptionIcon('heroicon-m-tag')
                ->color('success'),

            Stat::make('Komentar Pending', $pendingComments)
                ->description('Menunggu moderasi')
                ->descriptionIcon('heroicon-m-chat-bubble-left-ellipsis')
                ->color($pendingComments > 0 ? 'warning' : 'success'),

            Stat::make('Subscriber Aktif', $activeSubscribers)
                ->description('Langganan email')
                ->descriptionIcon('heroicon-m-envelope')
                ->color('info'),

            Stat::make('Total Views', number_format($totalViews))
                ->description('Semua artikel')
                ->descriptionIcon('heroicon-m-eye')
                ->color('primary'),
        ];
    }
}
