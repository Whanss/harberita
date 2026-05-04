<!doctype html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Konfirmasi Langganan</title>
    <style>
        body { margin: 0; padding: 0; background: #f4f4f5; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif; }
        .wrapper { max-width: 560px; margin: 40px auto; background: #ffffff; border-radius: 12px; overflow: hidden; box-shadow: 0 4px 24px rgba(0,0,0,0.08); }
        .header { background: #dc2626; padding: 32px 40px; text-align: center; }
        .header h1 { margin: 0; color: #ffffff; font-size: 22px; font-weight: 800; letter-spacing: -0.5px; }
        .header p { margin: 4px 0 0; color: rgba(255,255,255,0.8); font-size: 13px; }
        .body { padding: 36px 40px; }
        .body h2 { margin: 0 0 12px; font-size: 20px; color: #111827; font-weight: 700; }
        .body p { margin: 0 0 16px; font-size: 15px; color: #4b5563; line-height: 1.6; }
        .check-list { list-style: none; padding: 0; margin: 0 0 24px; }
        .check-list li { padding: 6px 0; font-size: 14px; color: #374151; display: flex; align-items: center; gap: 8px; }
        .check-list li::before { content: "✓"; color: #dc2626; font-weight: 700; }
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
            <p>Hasberita.id — Berita Terpercaya</p>
        </div>
        <div class="body">
            <h2>Langganan Berhasil! 🎉</h2>
            <p>Terima kasih telah berlangganan <strong>{{ config('app.name') }}</strong>. Kamu akan mendapatkan notifikasi email setiap kali ada berita terbaru yang dipublikasikan.</p>

            <ul class="check-list">
                <li>Notifikasi berita terbaru langsung ke inbox</li>
                <li>Ringkasan artikel pilihan redaksi</li>
                <li>Bisa berhenti berlangganan kapan saja</li>
            </ul>

            <a href="{{ url('/') }}" class="btn">Baca Berita Sekarang</a>

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
