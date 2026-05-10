<?php

namespace App\Http\Controllers;

use Artesaos\SEOTools\Facades\SEOTools;
use App\Models\Article;
use App\Models\Comment;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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
            'journalist:id,name,slug,position,bio,photo_path',
            'comments' => fn ($query) => $query
                ->where('status', 'approved')
                ->with('reader:id,name')
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
            'article'  => $article,
            'shareUrl' => $articleUrl,
            'related'  => Article::query()
                ->published()
                ->where('category_id', $article->category_id)
                ->where('id', '!=', $article->id)
                ->with(['category:id,name,slug', 'journalist:id,name,slug'])
                ->latest('published_at')
                ->limit(4)
                ->get(['id','title','slug','featured_image','excerpt','published_at','view_count','category_id','journalist_id']),
        ]);
    }

    private function sanitizeComment(string $content): string
    {
        $content = strip_tags($content);
        $content = preg_replace('/https?:\/\/\S+/i', '[link diblokir]', $content);
        $content = preg_replace('/www\.\S+/i', '[link diblokir]', $content);
        $content = preg_replace('/\b\S+\.(com|net|org|id|io|co|xyz|info|biz|me|tv|cc|ru|cn)\b/i', '[link diblokir]', $content);
        return trim(preg_replace('/\s+/', ' ', $content) ?? '');
    }

    public function storeComment(Request $request, Article $article): RedirectResponse
    {
        abort_unless($article->status === 'published' && $article->published_at?->isPast(), 404);

        $validated = $request->validate([
            'content' => ['required', 'string', 'min:5', 'max:1000'],
        ]);

        $sanitizedContent = $this->sanitizeComment($validated['content']);

        if (mb_strlen($sanitizedContent) < 5) {
            return back()->withErrors([
                'content' => 'Komentar tidak boleh hanya berisi URL atau tag HTML.',
            ]);
        }

        Comment::query()->create([
            'article_id' => $article->id,
            'reader_id'  => Auth::guard('reader')->id(),
            'content'    => $sanitizedContent,
            'status'     => 'approved',
        ]);

        return back()->with('success', 'Komentar berhasil dikirim.');
    }

    public function updateComment(Request $request, Comment $comment): RedirectResponse
    {
        abort_unless($comment->reader_id === Auth::guard('reader')->id(), 403);

        $validated = $request->validate([
            'content' => ['required', 'string', 'min:5', 'max:1000'],
        ]);

        $sanitizedContent = $this->sanitizeComment($validated['content']);

        if (mb_strlen($sanitizedContent) < 5) {
            return back()->withErrors([
                'content' => 'Komentar tidak boleh hanya berisi URL atau tag HTML.',
            ]);
        }

        $comment->update(['content' => $sanitizedContent]);

        return back()->with('success', 'Komentar berhasil diperbarui.');
    }

    public function destroyComment(Comment $comment): RedirectResponse
    {
        abort_unless($comment->reader_id === Auth::guard('reader')->id(), 403);

        $comment->delete();

        return back()->with('success', 'Komentar berhasil dihapus.');
    }
}
