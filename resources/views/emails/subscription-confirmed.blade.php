<!doctype html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Konfirmasi Langganan</title>
</head>
<body>
    <h2>Langganan Berhasil</h2>
    <p>Terima kasih telah berlangganan Portal Berita.</p>
    <p>Kami akan mengirimkan notifikasi ketika ada berita terbaru.</p>
    <p>
        Jika ingin berhenti berlangganan, klik tautan berikut:
        <a href="{{ route('subscriptions.unsubscribe', $subscription->token) }}">Berhenti berlangganan</a>
    </p>
</body>
</html>
