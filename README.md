# Hasberita.id — Portal Berita

Portal berita modern berbasis Laravel 12 + Vue 3 + Inertia.js dengan panel admin Filament.

---

## Tech Stack

| Layer | Teknologi |
|---|---|
| Backend | Laravel 12, PHP 8.2+ |
| Frontend | Vue 3, Inertia.js, TypeScript |
| Styling | Tailwind CSS v3 |
| Admin Panel | Filament v5 |
| Database | MySQL (XAMPP) |
| Email | Resend API |
| Build Tool | Vite |
| Queue | Database Queue |

---

## Fitur

- Berita Utama / Headline dengan hero layout
- Kategori berita (20 kategori)
- Pencarian berita
- Berita terpopuler & terbaru
- Video embed (YouTube / Vimeo)
- Komentar pembaca
- Bagikan ke media sosial
- Langganan newsletter via email
- Arsip berita dengan filter
- Profil jurnalis
- Halaman kontak redaksi
- Panel admin Filament (`/admin`)
- Notifikasi email ke subscriber saat artikel dipublikasikan

---

## Instalasi Lokal (XAMPP)

### Prasyarat

- [XAMPP](https://www.apachefriends.org/) dengan PHP 8.2+ dan MySQL
- [Composer](https://getcomposer.org/)
- [Node.js](https://nodejs.org/) v18+
- Git

### 1. Clone Repository

```bash
git clone https://github.com/Whanss/hasberita.git
cd hasberita
```

### 2. Install Dependensi PHP

```bash
composer install
```

### 3. Install Dependensi Node

```bash
npm install
```

### 4. Konfigurasi Environment

Salin file `.env.example` menjadi `.env`:

```bash
cp .env.example .env
```

Generate application key:

```bash
php artisan key:generate
```

### 5. Konfigurasi Database

Buka **phpMyAdmin** (`http://localhost/phpmyadmin`) dan buat database baru:

```sql
CREATE DATABASE portalberita CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

Edit file `.env`, sesuaikan bagian database:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=portalberita
DB_USERNAME=root
DB_PASSWORD=
```

> Jika XAMPP MySQL kamu menggunakan password, isi `DB_PASSWORD` sesuai.

### 6. Konfigurasi Email (Resend)

Daftar di [resend.com](https://resend.com) dan dapatkan API key. Edit `.env`:

```env
MAIL_MAILER=resend
MAIL_FROM_ADDRESS="noreply@hasberita.id"
MAIL_FROM_NAME="Hasberita.id"
RESEND_API_KEY=re_xxxxxxxxxxxxxxxx
```

> Untuk development, bisa ganti `MAIL_MAILER=log` agar email tidak benar-benar terkirim, hanya masuk ke `storage/logs/laravel.log`.

### 7. Konfigurasi Queue

Untuk development, queue sudah dikonfigurasi menggunakan database:

```env
QUEUE_CONNECTION=database
```

### 8. Jalankan Migrasi & Seeder

```bash
php artisan migrate
php artisan db:seed --class=AdminSeeder
php artisan db:seed --class=ArticleSeeder
```

Atau sekaligus:

```bash
php artisan migrate --seed
```

### 9. Buat Storage Link

```bash
php artisan storage:link
```

### 10. Build Frontend

Untuk development (hot reload):

```bash
npm run dev
```

Untuk production build:

```bash
npm run build
```

### 11. Jalankan Server

```bash
php artisan serve
```

Buka browser: `http://127.0.0.1:8000`

Panel admin: `http://127.0.0.1:8000/admin`

### 12. Jalankan Queue Worker (opsional, untuk email)

Buka terminal baru:

```bash
php artisan queue:work
```

---

## Akun Default

| Role | Email | Password |
|---|---|---|
| Admin | admin@gmail.com | admin123 |

---

## Struktur Direktori Penting

```
app/
├── Filament/           # Panel admin (Resources, Pages, Widgets)
├── Http/
│   ├── Controllers/    # Controller portal & auth
│   └── Middleware/     # HandleInertiaRequests
├── Mail/               # Mailable classes (email)
├── Models/             # Eloquent models
└── Providers/          # AppServiceProvider, AdminPanelProvider

resources/
├── js/
│   ├── layouts/        # PortalLayout, AuthLayout
│   ├── pages/
│   │   └── Portal/     # Halaman portal (Home, ArticleShow, dll)
│   └── components/     # Komponen Vue
└── views/
    ├── app.blade.php   # Root template Inertia
    └── emails/         # Template email

database/
├── migrations/         # Skema database
└── seeders/
    ├── AdminSeeder.php     # Seed user admin
    └── ArticleSeeder.php   # Seed 20 kategori + 1 artikel per kategori
```

---

## Deployment ke Production

### Prasyarat Server

- PHP 8.2+ dengan ekstensi: `mbstring`, `openssl`, `pdo`, `tokenizer`, `xml`, `ctype`, `json`, `bcmath`, `fileinfo`, `gd`
- MySQL 8.0+
- Node.js 18+ (untuk build, tidak perlu di server production)
- Nginx atau Apache
- Composer
- Supervisor (untuk queue worker)

---

### Langkah Deployment

#### 1. Upload File ke Server

Gunakan Git:

```bash
git clone https://github.com/Whanss/hasberita.git /var/www/hasberita
cd /var/www/hasberita
```

Atau upload via FTP/SFTP ke direktori web server.

#### 2. Install Dependensi (tanpa dev)

```bash
composer install --optimize-autoloader --no-dev
```

#### 3. Konfigurasi `.env` Production

```bash
cp .env.example .env
php artisan key:generate
```

Edit `.env` untuk production:

```env
APP_NAME="Hasberita.id"
APP_ENV=production
APP_DEBUG=false
APP_URL=https://hasberita.id

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=nama_database_production
DB_USERNAME=user_db
DB_PASSWORD=password_db_kuat

QUEUE_CONNECTION=database

MAIL_MAILER=resend
MAIL_FROM_ADDRESS="noreply@hasberita.id"
MAIL_FROM_NAME="Hasberita.id"
RESEND_API_KEY=re_xxxxxxxxxxxxxxxx

FILESYSTEM_DISK=public
SESSION_DRIVER=file
CACHE_STORE=database
```

#### 4. Migrasi Database

```bash
php artisan migrate --force
php artisan db:seed --class=AdminSeeder --force
```

#### 5. Build Frontend

Lakukan di lokal atau di server (jika Node.js tersedia):

```bash
npm install
npm run build
```

Upload folder `public/build/` ke server jika build dilakukan lokal.

#### 6. Storage Link & Cache

```bash
php artisan storage:link
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan icons:cache
php artisan filament:cache-components
```

#### 7. Set Permission

```bash
chmod -R 775 storage bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache
```

#### 8. Konfigurasi Nginx

Contoh konfigurasi Nginx untuk domain `hasberita.id`:

```nginx
server {
    listen 80;
    server_name hasberita.id www.hasberita.id;
    root /var/www/hasberita/public;

    add_header X-Frame-Options "SAMEORIGIN";
    add_header X-Content-Type-Options "nosniff";

    index index.php;

    charset utf-8;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location = /favicon.ico { access_log off; log_not_found off; }
    location = /robots.txt  { access_log off; log_not_found off; }

    error_page 404 /index.php;

    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.2-fpm.sock;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
        fastcgi_hide_header X-Powered-By;
    }

    location ~ /\.(?!well-known).* {
        deny all;
    }
}
```

Aktifkan HTTPS dengan Certbot:

```bash
sudo certbot --nginx -d hasberita.id -d www.hasberita.id
```

#### 9. Setup Queue Worker dengan Supervisor

Buat file konfigurasi Supervisor:

```bash
sudo nano /etc/supervisor/conf.d/hasberita-worker.conf
```

Isi dengan:

```ini
[program:hasberita-worker]
process_name=%(program_name)s_%(process_num)02d
command=php /var/www/hasberita/artisan queue:work database --sleep=3 --tries=3 --max-time=3600
autostart=true
autorestart=true
stopasgroup=true
killasgroup=true
user=www-data
numprocs=2
redirect_stderr=true
stdout_logfile=/var/www/hasberita/storage/logs/worker.log
stopwaitsecs=3600
```

Aktifkan:

```bash
sudo supervisorctl reread
sudo supervisorctl update
sudo supervisorctl start hasberita-worker:*
```

#### 10. Setup Cron (Laravel Scheduler)

```bash
sudo crontab -e
```

Tambahkan:

```
* * * * * cd /var/www/hasberita && php artisan schedule:run >> /dev/null 2>&1
```

---

### Update Deployment (setelah ada perubahan kode)

```bash
cd /var/www/hasberita

# Pull perubahan terbaru
git pull origin main

# Install dependensi baru (jika ada)
composer install --optimize-autoloader --no-dev

# Jalankan migrasi baru (jika ada)
php artisan migrate --force

# Build frontend (jika ada perubahan JS/CSS)
npm run build

# Clear & rebuild cache
php artisan optimize:clear
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Restart queue worker
sudo supervisorctl restart hasberita-worker:*
```

---

## Konfigurasi Fitur Langganan (Subscribe)

Fitur langganan mengirim email ke semua subscriber setiap kali artikel baru dipublikasikan.

### 1. Daftar Akun Resend

1. Buka [resend.com](https://resend.com) dan buat akun gratis
2. Masuk ke dashboard → **API Keys** → **Create API Key**
3. Salin API key yang dihasilkan

### 2. Verifikasi Domain (untuk production)

Di dashboard Resend:
1. Masuk ke **Domains** → **Add Domain**
2. Masukkan domain kamu (contoh: `hasberita.id`)
3. Tambahkan DNS record yang diberikan Resend ke domain kamu (biasanya di Cloudflare / cPanel)
4. Tunggu verifikasi selesai (biasanya 5–30 menit)

> Untuk **development/testing**, kamu bisa pakai domain `onboarding@resend.dev` bawaan Resend tanpa verifikasi, tapi email hanya bisa dikirim ke email yang terdaftar di akun Resend kamu.

### 3. Konfigurasi `.env`

```env
MAIL_MAILER=resend
MAIL_FROM_ADDRESS="noreply@hasberita.id"
MAIL_FROM_NAME="Hasberita.id"
RESEND_API_KEY=re_xxxxxxxxxxxxxxxx

QUEUE_CONNECTION=database
```

### 4. Jalankan Queue Worker

Email dikirim secara background (tidak blocking). Pastikan queue worker selalu jalan:

**Development:**
```bash
php artisan queue:work
```

**Production** — gunakan Supervisor (lihat bagian Deployment di atas).

### 5. Test Kirim Email

Publish sebuah artikel dari panel admin (`/admin` → Artikel → ubah status ke **Dipublikasikan**).

Cek status job di queue:
```bash
php artisan queue:work --verbose
```

Jika ada job gagal:
```bash
php artisan queue:failed        # lihat daftar job gagal
php artisan queue:retry all     # coba ulang semua job gagal
php artisan queue:flush         # hapus semua job gagal
```

### Catatan Penting

- Subscriber harus **login** terlebih dahulu sebagai reader, lalu klik tombol **Langganan Gratis** di footer
- Email konfirmasi langganan dikirim otomatis saat subscribe
- Subscriber bisa berhenti langganan via link di setiap email notifikasi
- Notifikasi hanya dikirim **sekali** per artikel (dicatat di kolom `notified_subscribers_at`)

---



### Ganti Nama Aplikasi

Edit `.env`:

```env
APP_NAME="Nama Portal Kamu"
```

### Ganti Informasi Kontak Redaksi

Edit `resources/js/pages/Portal/Contact.vue` — bagian "Informasi Redaksi".

### Ganti Warna Utama

Warna merah (`brand`) didefinisikan di `tailwind.config.js`. Cari `brand` dan ganti nilai hex-nya.

### Tambah/Edit Kategori

Masuk ke panel admin `/admin` → Kategori → Tambah Kategori.

### Tambah Jurnalis

Masuk ke panel admin `/admin` → Jurnalis → Tambah Jurnalis.

---

## Troubleshooting

**Error: `php_network_getaddresses` saat kirim email**
→ Pastikan `RESEND_API_KEY` sudah benar dan koneksi internet aktif. Untuk development, ganti ke `MAIL_MAILER=log`.

**Gambar tidak muncul**
→ Jalankan `php artisan storage:link` dan pastikan `FILESYSTEM_DISK=public` di `.env`.

**Queue job gagal**
→ Cek `php artisan queue:failed` untuk melihat error. Jalankan `php artisan queue:work` di terminal.

**500 Error setelah deploy**
→ Cek `storage/logs/laravel.log`. Pastikan `APP_DEBUG=false` dan semua cache sudah di-clear.

**Filament tidak bisa login**
→ Pastikan user di tabel `users` memiliki kolom `is_admin = 1` atau jalankan ulang `php artisan db:seed --class=AdminSeeder`.

---

## Developer

Dikembangkan oleh [@Whanss](https://github.com/Whanss)

---

## Lisensi

MIT License
