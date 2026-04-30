<?php

namespace App\Http\Controllers;

use App\Models\Journalist;
use Inertia\Inertia;
use Inertia\Response;

class JournalistController extends Controller
{
    public function show(Journalist $journalist): Response
    {
        abort_unless($journalist->is_active, 404);

        $articles = $journalist->articles()
            ->published()
            ->with(['category:id,name,slug'])
            ->latest('published_at')
            ->paginate(12)
            ->withQueryString();

        return Inertia::render('Portal/JournalistShow', [
            'journalist' => $journalist,
            'articles' => $articles,
        ]);
    }
}
