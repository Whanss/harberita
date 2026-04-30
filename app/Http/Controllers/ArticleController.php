<?php

namespace App\Http\Controllers;

use Artesaos\SEOTools\Facades\SEOTools;
use App\Models\Article;
use App\Models\Comment;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;
use Inertia\Response;

class ArticleController extends Controller
{
    public function show(Article $article): Response
    {
        abort_unless($article->status === 'published' && $article->published_at?->isPast(), 404);

        DB::table('articles')
            ->where('id', $article->id)
            ->update(['view_count' => DB::raw('view_count + 1')]);

        $article->refresh()->load([
            'category:id,name,slug',
            'journalist:id,name,slug,position,bio',
            'comments' => fn ($query) => $query
                ->where('status', 'approved')
                ->with('user:id,name')
                ->latest(),
        ]);

        $articleUrl = route('articles.show', $article->slug);
        $description = $article->excerpt ?: str((string) $article->content)->stripTags()->limit(160)->toString();

        SEOTools::setTitle($article->title);
        SEOTools::setDescription($description);
        SEOTools::opengraph()->setUrl($articleUrl);
        SEOTools::opengraph()->setTitle($article->title);
        SEOTools::opengraph()->setDescription($description);

        if ($article->featured_image) {
            SEOTools::opengraph()->addImage(url('storage/'.$article->featured_image));
        }

        return Inertia::render('Portal/ArticleShow', [
            'article' => $article,
            'shareUrl' => $articleUrl,
        ]);
    }

    public function storeComment(Request $request, Article $article): RedirectResponse
    {
        abort_unless($article->status === 'published' && $article->published_at?->isPast(), 404);

        $validated = $request->validate([
            'content' => ['required', 'string', 'min:5', 'max:1000'],
        ]);

        $contentWithoutHtml = strip_tags($validated['content']);
        $sanitizedContent = preg_replace('/https?:\/\/\S+|www\.\S+/i', '', $contentWithoutHtml);
        $sanitizedContent = trim(preg_replace('/\s+/', ' ', (string) $sanitizedContent) ?? '');

        if (mb_strlen($sanitizedContent) < 5) {
            return back()->withErrors([
                'content' => 'Komentar tidak boleh hanya berisi URL atau tag HTML.',
            ]);
        }

        Comment::query()->create([
            'article_id' => $article->id,
            'user_id' => $request->user()->id,
            'content' => $sanitizedContent,
            'status' => 'pending',
        ]);

        return back()->with('success', 'Komentar berhasil dikirim dan menunggu moderasi admin.');
    }
}
