<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\Category;
use App\Models\Subscription;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Inertia\Response;

class HomeController extends Controller
{
    public function __invoke(): Response
    {
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

        // Load 4 artikel terbaru per kategori untuk section kategori di homepage
        $categoryArticles = Category::query()
            ->where('is_active', true)
            ->orderBy('name')
            ->limit(4)
            ->get(['id', 'name', 'slug'])
            ->map(function (Category $category) {
                $category->setRelation('articles', $category->articles()
                    ->published()
                    ->with(['category:id,name,slug', 'journalist:id,name,slug'])
                    ->latest('published_at')
                    ->limit(4)
                    ->get()
                );
                return $category;
            });

        return Inertia::render('Portal/Home', [
            'headline'         => $headline,
            'latest'           => $latest,
            'popular'          => $popular,
            'categories'       => $categories,
            'categoryArticles' => $categoryArticles,
        ]);
    }
}
