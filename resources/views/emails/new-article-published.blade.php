<!doctype html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Berita Baru: {{ $article->title }}</title>
    <style>
        body { margin: 0; padding: 0; background: #f4f4f5; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif; }
        .wrapper { max-width: 560px; margin: 40px auto; background: #ffffff; border-radius: 12px; overflow: hidden; box-shadow: 0 4px 24px rgba(0,0,0,0.08); }
        .header { background: #dc2626; padding: 20px 40px; display: flex; align-items: center; justify-content: space-between; }
        .header h1 { margin: 0; color: #ffffff; font-size: 18px; font-weight: 800; }
        .badge { background: rgba(255,255,255,0.2); color: #fff; font-size: 11px; font-weight: 700; padding: 3px 10px; border-radius: 20px; letter-spacing: 0.5px; }
        .article-image { width: 100%; height: 220px; object-fit: cover; display: block; }
        .article-image-placeholder { width: 100%; height: 220px; background: #f3f4f6; display: flex; align-items: center; justify-content: center; color: #d1d5db; font-size: 13px; }
        .body { padding: 28px 40px; }
        .category { display: inline-block; background: #fef2f2; color: #dc2626; font-size: 11px; font-weight: 700; padding: 3px 10px; border-radius: 4px; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 12px; }
        .body h2 { margin: 0 0 12px; font-size: 20px; color: #111827; font-weight: 800; line-height: 1.3; }
        .body p { margin: 0 0 20px; font-size: 15px; color: #4b5563; line-height: 1.6; }
        .meta { display: flex; gap: 16px; margin-bottom: 24px; font-size: 13px; color: #9ca3af; }
        .btn { display: inline-block; background: #dc2626; color: #ffffff; text-decoration: none; padding: 12px 28px; border-radius: 8px; font-size: 14px; font-weight: 700; }
        .divider { border: none; border-top: 1px solid #e5e7eb; margin: 28px 0; }
        .unsubscribe { font-size: 12px; color: #9ca3af; text-align: center; }
        .unsubscribe a { color: #6b7280; }
        .footer { background: #f9fafb; padding: 20px 40px; text-align: center; font-size: 12px; color: #9ca3af; border-top: 1px solid #e5e7eb; }
    </style>
</head>
<body>
    <div class="wrapper">
        <div class="header">
            <h1>{{ config('app.name') }}</h1>
            <span class="badge">BERITA BARU</span>
        </div>

        @if($article->featured_image)
            <img src="{{ url('storage/' . $article->featured_image) }}" alt="{{ $article->title }}" class="article-image">
        @else
            <div class="article-image-placeholder">Tidak ada gambar</div>
        @endif

        <div class="body">
            @if($article->category)
                <span class="category">{{ $article->category->name }}</span>
            @endif

            <h2>{{ $article->title }}</h2>

            <div class="meta">
                @if($article->journalist)
                    <span>✍️ {{ $article->journalist->name }}</span>
                @endif
                <span>🕐 {{ $article->published_at?->locale('id')->diffForHumans() }}</span>
            </div>

            @if($article->excerpt)
                <p>{{ $article->excerpt }}</p>
            @endif

            <a href="{{ route('articles.show', $article->slug) }}" class="btn">Baca Selengkapnya →</a>

            <hr class="divider">

            <p class="unsubscribe">
                Tidak ingin menerima email ini?
                <a href="{{ route('subscriptions.unsubscribe', $subscription->token) }}">Berhenti berlangganan</a>
            </p>
        </div>
        <div class="footer">
            &copy; {{ date('Y') }} {{ config('app.name') }}. Semua hak dilindungi.
        </div>
    </div>
</body>
</html>
