<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\Category;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class ArchiveController extends Controller
{
    public function index(Request $request): Response
    {
        $period = $request->string('period')->toString();
        $category = $request->string('category')->toString();

        $query = Article::query()
            ->published()
            ->with(['category:id,name,slug', 'journalist:id,name,slug'])
            ->latest('published_at');

        if (preg_match('/^\d{4}-\d{2}$/', $period)) {
            [$year, $month] = explode('-', $period);
            $query
                ->whereYear('published_at', (int) $year)
                ->whereMonth('published_at', (int) $month);
        }

        if ($category !== '') {
            $query->whereHas('category', fn ($q) => $q->where('slug', $category));
        }

        $articles = $query->paginate(20)->withQueryString();

        $periods = Article::query()
            ->published()
            ->get(['published_at'])
            ->groupBy(fn ($article) => $article->published_at->format('Y-m'))
            ->map(fn ($items, $key) => [
                'period' => $key,
                'label' => \Carbon\Carbon::createFromFormat('Y-m', $key)->translatedFormat('F Y'),
                'count' => $items->count(),
            ])
            ->values();

        $categories = Category::query()
            ->where('is_active', true)
            ->orderBy('name')
            ->get(['name', 'slug']);

        return Inertia::render('Portal/ArchiveIndex', [
            'articles' => $articles,
            'periods' => $periods,
            'categories' => $categories,
            'filters' => [
                'period' => $period,
                'category' => $category,
            ],
        ]);
    }
}
