<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Inertia\Inertia;
use Inertia\Response;

class CategoryController extends Controller
{
    public function show(Category $category): Response
    {
        abort_unless($category->is_active, 404);

        $articles = $category->articles()
            ->published()
            ->with(['category:id,name,slug', 'journalist:id,name,slug'])
            ->latest('published_at')
            ->paginate(12)
            ->withQueryString();

        return Inertia::render('Portal/CategoryShow', [
            'category' => $category,
            'articles' => $articles,
        ]);
    }
}
