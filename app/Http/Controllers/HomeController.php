<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\Category;
use App\Models\Subscription;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Inertia\Inertia;
use Inertia\Response;

class HomeController extends Controller
{
    public function __invoke(): Response
    {
        $data = Cache::remember('homepage_data', now()->addMinutes(5), function () {
            $headline = Article::query()
                ->published()
                ->with(['category:id,name,slug', 'journalist:id,name,slug'])
                ->where('is_headline', true)
                ->latest('published_at')
                ->limit(5)
                ->get();

            if ($headline->isEmpty()) {
                $headline = Article::query()
                    ->published()
                    ->with(['category:id,name,slug', 'journalist:id,name,slug'])
                    ->latest('published_at')
                    ->limit(5)
                    ->get();
            }

            $latest = Article::query()
                ->published()
                ->with(['category:id,name,slug', 'journalist:id,name,slug'])
                ->latest('published_at')
                ->limit(10)
                ->get();

            $popular = Article::query()
                ->published()
                ->with(['category:id,name,slug'])
                ->where('published_at', '>=', now()->subDays(7))
                ->orderByDesc('view_count')
                ->limit(10)
                ->get();

            $categories = Category::query()
                ->where('is_active', true)
                ->orderBy('name')
                ->get(['id', 'name', 'slug']);

            // Load semua artikel untuk 4 kategori sekaligus (hindari N+1)
            $topCategories = Category::query()
                ->where('is_active', true)
                ->orderBy('name')
                ->limit(4)
                ->get(['id', 'name', 'slug']);

            $categoryIds = $topCategories->pluck('id');

            $articlesByCategory = Article::query()
                ->published()
                ->with(['category:id,name,slug', 'journalist:id,name,slug'])
                ->whereIn('category_id', $categoryIds)
                ->latest('published_at')
                ->limit(20)
                ->get()
                ->groupBy('category_id');

            $categoryArticles = $topCategories->map(function (Category $category) use ($articlesByCategory) {
                $category->setRelation(
                    'articles',
                    $articlesByCategory->get($category->id, collect())->take(4)
                );
                return $category;
            });

            return compact('headline', 'latest', 'popular', 'categories', 'categoryArticles');
        });

        return Inertia::render('Portal/Home', [
            'headline'         => $data['headline'],
            'latest'           => $data['latest'],
            'popular'          => $data['popular'],
            'categories'       => $data['categories'],
            'categoryArticles' => $data['categoryArticles'],
        ]);
    }
}
