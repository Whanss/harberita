<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Inertia\Inertia;
use Inertia\Response;

class CategoryController extends Controller
{
    public function show(Category $category, \Illuminate\Http\Request $request): Response
    {
        abort_unless($category->is_active, 404);

        $sort = $request->get('sort', 'latest');

        $query = $category->articles()
            ->published()
            ->with(['category:id,name,slug', 'journalist:id,name,slug']);

        if ($sort === 'popular') {
            $query->orderByDesc('view_count');
        } elseif ($sort === 'week') {
            $query->where('published_at', '>=', now()->subDays(7))->orderByDesc('view_count');
        } elseif ($sort === 'month') {
            $query->where('published_at', '>=', now()->subDays(30))->orderByDesc('view_count');
        } else {
            $query->latest('published_at');
        }

        $articles = $query->paginate(12)->withQueryString();

        // Kalau week/month kosong, fallback ke semua artikel terbaru
        if (in_array($sort, ['week', 'month']) && $articles->isEmpty()) {
            $articles = $category->articles()
                ->published()
                ->with(['category:id,name,slug', 'journalist:id,name,slug'])
                ->latest('published_at')
                ->paginate(12)
                ->withQueryString();
        }
        return Inertia::render('Portal/CategoryShow', [
            'category'     => $category,
            'articles'     => $articles,
            'currentSort'  => $sort,
            'totalArticles'=> $category->articles()->published()->count(),
        ]);
    }
}
