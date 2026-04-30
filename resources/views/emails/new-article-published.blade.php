<!doctype html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Berita Baru</title>
</head>
<body>
    <h2>Berita Baru Dipublikasikan</h2>
    <p><strong>{{ $article->title }}</strong></p>
    <p>{{ $article->excerpt }}</p>
    <p>
        Baca selengkapnya:
        <a href="{{ route('articles.show', $article->slug) }}">{{ route('articles.show', $article->slug) }}</a>
    </p>
    <p>
        Berhenti berlangganan:
        <a href="{{ route('subscriptions.unsubscribe', $subscription->token) }}">Klik di sini</a>
    </p>
</body>
</html>
