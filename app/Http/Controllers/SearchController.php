<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Article;
use Inertia\Inertia;
use Inertia\Response;

class SearchController extends Controller
{
    public function index(Request $request): Response
    {
        $validated = $request->validate([
            'q' => ['required', 'string', 'min:3'],
        ], [
            'q.min' => 'Kata kunci minimal 3 karakter.',
        ]);

        $keyword = $validated['q'];

        $results = Article::query()
            ->published()
            ->with(['category:id,name,slug'])
            ->where(fn ($query) => $query
                ->where('title', 'like', "%{$keyword}%")
                ->orWhere('content', 'like', "%{$keyword}%"))
            ->latest('published_at')
            ->paginate(10)
            ->withQueryString();

        return Inertia::render('Portal/SearchIndex', [
            'q' => $keyword,
            'results' => $results,
        ]);
    }
}
