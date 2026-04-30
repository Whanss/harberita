<!doctype html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Pesan Kontak</title>
</head>
<body>
    <h2>Pesan Baru dari Halaman Kontak</h2>
    <p><strong>Nama:</strong> {{ $payload['name'] }}</p>
    <p><strong>Email:</strong> {{ $payload['email'] }}</p>
    <p><strong>Subjek:</strong> {{ $payload['subject'] }}</p>
    <p><strong>Pesan:</strong></p>
    <p>{{ $payload['message'] }}</p>
</body>
</html>
