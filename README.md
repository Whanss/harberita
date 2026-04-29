# 📰 Dokumentasi Setup Portal Berita
**Laravel + MySQL + Vue.js 3 + Tailwind + Filament**

---

## 📋 Table of Contents
1. [Requirements](#requirements)
2. [Installation & Setup](#installation--setup)
3. [Database Schema](#database-schema)
4. [File Structure](#file-structure)
5. [Configuration](#configuration)
6. [Fitur-Fitur](#fitur-fitur)
7. [Deployment](#deployment)
8. [Troubleshooting](#troubleshooting)

---

## 🔧 Requirements

### Server Requirements
- **PHP:** 8.2 atau lebih tinggi
- **MySQL:** 8.0 atau PostgreSQL 10+
- **Node.js:** 18+ (untuk frontend build)
- **Composer:** Latest version
- **Git:** Untuk version control

### System Requirements
```
RAM: Minimal 2GB (recommended 4GB)
Disk Space: 5GB
OS: Linux/Ubuntu (recommended), macOS, atau Windows dengan WSL2
```

---

## 🚀 Installation & Setup

### Step 1: Create Project
```bash
composer create-project laravel/laravel portal-berita
cd portal-berita
```

### Step 2: Install Dependencies

**Composer Packages:**
```bash
# Core packages
composer require filament/filament
composer require spatie/laravel-medialibrary
composer require spatie/laravel-slugs
composer require artesaos/seotools
composer require laravel/scout
composer require pusher/pusher-php-server
composer require intervention/image
composer require laravel/sanctum
composer require laravel/breeze

# Optional packages
composer require spatie/laravel-permission
composer require spatie/laravel-tags
composer require laravel/telescope --dev
```

**NPM Packages:**
```bash
npm install
npm install vue@3
npm install tailwindcss postcss autoprefixer
npm install axios
npm install @vueuse/core
npm install sweetalert2
npm install swiper
npm install marked
```

### Step 3: Environment Setup

**Copy .env file:**
```bash
cp .env.example .env
php artisan key:generate
```

**Edit .env file:**
```env
APP_NAME="Portal Berita"
APP_URL=http://localhost:8000

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=portal_berita
DB_USERNAME=root
DB_PASSWORD=

MAIL_MAILER=smtp
MAIL_HOST=smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=your_username
MAIL_PASSWORD=your_password
MAIL_FROM_ADDRESS=noreply@portalberita.com

PUSHER_APP_ID=
PUSHER_APP_KEY=
PUSHER_APP_SECRET=
PUSHER_HOST=
PUSHER_PORT=443
PUSHER_SCHEME=https
PUSHER_APP_CLUSTER=mt1
```

### Step 4: Database Setup

**Create Database:**
```bash
mysql -u root -p
CREATE DATABASE portal_berita;
EXIT;
```

**Run Migrations:**
```bash
php artisan migrate
```

**Seed Sample Data (optional):**
```bash
php artisan tinker
# atau buat seeder tersendiri
```

### Step 5: Filament Setup

```bash
php artisan filament:install --panels=admin
php artisan make:filament-user
```

### Step 6: Frontend Build

```bash
npm run dev
# atau untuk production:
npm run build
```

### Step 7: Run Application

```bash
php artisan serve
# Akses: http://localhost:8000
# Admin Panel: http://localhost:8000/admin
```

---

## 🗄️ Database Schema

### Table: users
```sql
CREATE TABLE users (
    id BIGINT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(255) NOT NULL,
    email VARCHAR(255) UNIQUE NOT NULL,
    email_verified_at TIMESTAMP NULL,
    password VARCHAR(255) NOT NULL,
    role ENUM('admin', 'editor', 'author', 'user') DEFAULT 'user',
    is_active BOOLEAN DEFAULT TRUE,
    avatar_url VARCHAR(255) NULL,
    bio TEXT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);
```

### Table: categories
```sql
CREATE TABLE categories (
    id BIGINT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(255) NOT NULL UNIQUE,
    slug VARCHAR(255) NOT NULL UNIQUE,
    description TEXT NULL,
    icon VARCHAR(255) NULL,
    color VARCHAR(7) NULL,
    is_active BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);
```

### Table: articles
```sql
CREATE TABLE articles (
    id BIGINT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
    title VARCHAR(255) NOT NULL,
    slug VARCHAR(255) NOT NULL UNIQUE,
    content LONGTEXT NOT NULL,
    excerpt VARCHAR(500) NULL,
    featured_image_url VARCHAR(255) NULL,
    category_id BIGINT UNSIGNED,
    author_id BIGINT UNSIGNED NOT NULL,
    status ENUM('draft', 'published', 'archived') DEFAULT 'draft',
    is_featured BOOLEAN DEFAULT FALSE,
    is_trending BOOLEAN DEFAULT FALSE,
    views_count INT DEFAULT 0,
    published_at TIMESTAMP NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (category_id) REFERENCES categories(id) ON DELETE SET NULL,
    FOREIGN KEY (author_id) REFERENCES users(id) ON DELETE CASCADE,
    INDEX (slug),
    INDEX (status),
    INDEX (published_at),
    FULLTEXT INDEX ft_search (title, content, excerpt)
);
```

### Table: comments
```sql
CREATE TABLE comments (
    id BIGINT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
    article_id BIGINT UNSIGNED NOT NULL,
    user_id BIGINT UNSIGNED,
    parent_id BIGINT UNSIGNED NULL,
    name VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL,
    content TEXT NOT NULL,
    is_approved BOOLEAN DEFAULT FALSE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (article_id) REFERENCES articles(id) ON DELETE CASCADE,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL,
    FOREIGN KEY (parent_id) REFERENCES comments(id) ON DELETE CASCADE,
    INDEX (article_id)
);
```

### Table: media
```sql
CREATE TABLE media (
    id BIGINT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
    model_type VARCHAR(255) NOT NULL,
    model_id BIGINT UNSIGNED NOT NULL,
    collection_name VARCHAR(255) NOT NULL,
    name VARCHAR(255) NOT NULL,
    file_name VARCHAR(255) NOT NULL,
    mime_type VARCHAR(255),
    disk VARCHAR(255),
    size INT,
    conversions_disk VARCHAR(255),
    responsive_images JSON,
    manipulations JSON,
    custom_properties JSON,
    generated_conversions JSON,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX (model_type, model_id)
);
```

### Table: subscriptions
```sql
CREATE TABLE subscriptions (
    id BIGINT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
    email VARCHAR(255) UNIQUE NOT NULL,
    name VARCHAR(255),
    is_active BOOLEAN DEFAULT TRUE,
    frequency ENUM('monthly') DEFAULT 'monthly',
    categories JSON NULL,
    verified_at TIMESTAMP NULL,
    token VARCHAR(255) NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);
```

### Table: article_views
```sql
CREATE TABLE article_views (
    id BIGINT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
    article_id BIGINT UNSIGNED NOT NULL,
    user_ip VARCHAR(45),
    user_agent VARCHAR(500),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (article_id) REFERENCES articles(id) ON DELETE CASCADE,
    INDEX (article_id, created_at)
);
```

### Table: social_shares
```sql
CREATE TABLE social_shares (
    id BIGINT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
    article_id BIGINT UNSIGNED NOT NULL,
    platform ENUM('facebook', 'twitter', 'whatsapp', 'telegram', 'linkedin') NOT NULL,
    share_count INT DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (article_id) REFERENCES articles(id) ON DELETE CASCADE,
    UNIQUE KEY (article_id, platform)
);
```

---

## 📁 File Structure

```
portal-berita/
├── app/
│   ├── Models/
│   │   ├── User.php
│   │   ├── Category.php
│   │   ├── Article.php
│   │   ├── Comment.php
│   │   └── Subscription.php
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── ArticleController.php
│   │   │   ├── CategoryController.php
│   │   │   ├── CommentController.php
│   │   │   └── SubscriptionController.php
│   │   └── Middleware/
│   ├── Filament/
│   │   └── Resources/
│   │       ├── ArticleResource.php
│   │       ├── CategoryResource.php
│   │       ├── CommentResource.php
│   │       └── UserResource.php
│   └── Mail/
│       ├── NewsletterMail.php
│       └── ArticlePublishedMail.php
├── database/
│   ├── migrations/
│   ├── seeders/
│   │   ├── UserSeeder.php
│   │   ├── CategorySeeder.php
│   │   └── ArticleSeeder.php
├── resources/
│   ├── views/
│   │   ├── layouts/
│   │   │   └── app.blade.php
│   │   ├── components/
│   │   │   ├── navbar.blade.php
│   │   │   ├── sidebar.blade.php
│   │   │   └── footer.blade.php
│   │   ├── articles/
│   │   │   ├── index.blade.php
│   │   │   ├── show.blade.php
│   │   │   └── search.blade.php
│   │   └── pages/
│   │       ├── home.blade.php
│   │       └── contact.blade.php
│   └── js/
│       ├── app.js
│       ├── bootstrap.js
│       └── components/
│           ├── ArticleCard.vue
│           ├── ArticleDetail.vue
│           ├── CommentSection.vue
│           └── SearchBar.vue
├── routes/
│   ├── web.php
│   ├── api.php
│   └── auth.php
├── public/
│   ├── uploads/
│   │   ├── articles/
│   │   ├── authors/
│   │   └── categories/
│   └── assets/
├── storage/
│   └── app/
│       └── public/
├── config/
│   ├── filament.php
│   ├── scout.php
│   └── media-library.php
└── docker-compose.yml (optional)
```

---

## ⚙️ Configuration

### 1. Tailwind CSS Setup

**tailwind.config.js:**
```javascript
export default {
  content: [
    "./resources/**/*.blade.php",
    "./resources/**/*.js",
    "./resources/**/*.vue",
    "./node_modules/flowbite/**/*.js"
  ],
  theme: {
    extend: {
      colors: {
        primary: '#1F2937',
        secondary: '#6366F1',
        accent: '#F97316',
      }
    }
  },
  plugins: [
    require('flowbite/plugin')
  ]
}
```

### 2. Vue Configuration

**resources/js/app.js:**
```javascript
import './bootstrap';
import { createApp } from 'vue';
import { createPinia } from 'pinia';

// Import components
import ArticleCard from './components/ArticleCard.vue';
import ArticleDetail from './components/ArticleDetail.vue';
import CommentSection from './components/CommentSection.vue';
import SearchBar from './components/SearchBar.vue';

const app = createApp({});
const pinia = createPinia();

app.use(pinia);

app.component('ArticleCard', ArticleCard);
app.component('ArticleDetail', ArticleDetail);
app.component('CommentSection', CommentSection);
app.component('SearchBar', SearchBar);

app.mount('#app');
```

### 3. Filament Configuration

**config/filament.php:**
```php
return [
    'brand' => 'Portal Berita Admin',
    'dark_mode' => true,
    'locale' => 'id',
    'panels' => [
        'admin' => [
            'path' => 'admin',
            'auth' => [
                'guard' => 'web',
            ],
            'pages' => [
                'dashboard' => \App\Filament\Pages\Dashboard::class,
            ],
            'resources' => [
                \App\Filament\Resources\ArticleResource::class,
                \App\Filament\Resources\CategoryResource::class,
                \App\Filament\Resources\CommentResource::class,
                \App\Filament\Resources\UserResource::class,
            ],
        ],
    ],
];
```

### 4. Scout Configuration (Search)

**config/scout.php:**
```php
return [
    'driver' => env('SCOUT_DRIVER', 'database'),
    
    'meilisearch' => [
        'host' => env('MEILISEARCH_HOST', 'localhost:7700'),
        'key' => env('MEILISEARCH_KEY', null),
    ],
];
```

---

## ✨ Fitur-Fitur

### 1. Berita Utama (Featured Articles)
- Admin bisa set artikel sebagai featured
- Ditampilkan di homepage dengan banner besar
- Database query: `where('is_featured', true)->where('status', 'published')`

### 2. Kategori Berita
- Multiple categories untuk setiap artikel
- Filter berita berdasarkan kategori
- Breadcrumb navigation

### 3. Pencarian Berita
- Full-text search dengan MySQL/Meilisearch
- Filter by category, date, author
- Search suggestions/autocomplete

### 4. Berita Terpopuler
- Track views per artikel (article_views table)
- Sort by views_count
- Update trending status daily via cron job

### 5. Berita Terbaru
- Order by published_at DESC
- Pagination (20 items per page)
- Load more functionality

### 6. Galeri Foto
- Menggunakan Spatie Media Library
- Multiple images per artikel
- Image optimization & thumbnail generation
- Lightbox gallery view

### 7. Video Berita
- Embed YouTube/Vimeo links
- Stored in articles table (video_url field)
- Responsive iframe embed

### 8. Komentar
- Nested comments (reply to comments)
- Moderation system (approve/reject)
- Spam prevention
- User & guest comments

### 9. Share Social Media
- Facebook, Twitter, WhatsApp, Telegram, LinkedIn
- Share buttons di artikel
- Track share count per platform

### 10. Langganan Email
- Subscribe dengan email
- Frequency options ( monthly)
- Email verification
- Newsletter automation dengan Laravel Queue

### 11. Arsip Berita
- Filter by date range
- Sitemap untuk SEO
- Soft delete untuk deleted articles

### 12. Profil Penulis
- Author page dengan articles list
- Author bio & avatar
- Follow author functionality (optional)

### 13. Kontak
- Contact form di website
- Email notification ke admin
- Message storage di database

### 14. Upload Berita (Admin)
- Filament Resource untuk CRUD articles
- Rich text editor (TinyMCE/Quill)
- Featured image upload
- Draft & publish status
- Schedule publish date

---

## 🚀 Deployment

### 1. Preparation Checklist
```bash
# 1. Environment
cp .env.example .env
php artisan key:generate

# 2. Database migration
php artisan migrate --force

# 3. Storage link
php artisan storage:link

# 4. Cache clear
php artisan config:cache
php artisan route:cache
php artisan view:cache

# 5. Frontend build
npm run build

# 6. Optimize
php artisan optimize
```

### 2. Shared Hosting (cPanel)
```bash
# Upload files via FTP/SFTP ke public_html
# Struktur yang paling penting:
# public/ -> public_html/
# app/, config/, database/, routes/, etc -> public_html/app, dst

# SSH Commands:
cd public_html
composer install --no-dev
php artisan migrate --force
php artisan storage:link
chmod -R 755 storage bootstrap/cache
```

### 3. VPS/Cloud (AWS, DigitalOcean)
```bash
# Install dependencies
sudo apt update
sudo apt install php8.2 php8.2-fpm mysql-server nodejs npm nginx

# Setup project
git clone <your-repo>
cd portal-berita
composer install
npm install && npm run build
php artisan migrate
php artisan storage:link

# Nginx configuration
sudo cp .nginx.conf /etc/nginx/sites-available/portal-berita
sudo ln -s /etc/nginx/sites-available/portal-berita /etc/nginx/sites-enabled/
sudo nginx -t
sudo systemctl restart nginx

# SSL (Let's Encrypt)
sudo apt install certbot python3-certbot-nginx
sudo certbot --nginx -d yourdomain.com
```

### 4. Docker Setup
```dockerfile
# Dockerfile
FROM php:8.2-fpm
RUN apt-get update && apt-get install -y \
    mysql-client \
    git \
    curl \
    libpng-dev \
    libjpeg-dev \
    && docker-php-ext-install pdo pdo_mysql gd

WORKDIR /var/www/html
COPY . .
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
RUN composer install
RUN npm install && npm run build
```

```yaml
# docker-compose.yml
version: '3.8'
services:
  mysql:
    image: mysql:8.0
    environment:
      MYSQL_DATABASE: portal_berita
      MYSQL_ROOT_PASSWORD: root
    volumes:
      - mysql_data:/var/lib/mysql

  app:
    build: .
    ports:
      - "8000:8000"
    volumes:
      - .:/var/www/html
    depends_on:
      - mysql
    command: php artisan serve --host=0.0.0.0

volumes:
  mysql_data:
```

---

## 🔧 Troubleshooting

### Error: "Class 'Filament\Facades\Filament' not found"
```bash
php artisan cache:clear
php artisan config:clear
composer dump-autoload
```

### Error: "SQLSTATE[HY000]: General error: 1030 Got error..."
```bash
# Biasanya disk space penuh atau table corrupted
# Check:
df -h
# Repair table:
php artisan tinker
# Atau gunakan MySQL repair:
REPAIR TABLE articles;
```

### Error: "File not found: storage/app/public"
```bash
php artisan storage:link
chmod -R 775 storage/
chmod -R 775 bootstrap/cache/
```

### Slow Query Performance
```bash
# Add indexes:
php artisan tinker
# Atau edit migration & re-run
php artisan migrate:refresh
```

### Email Not Sending
```bash
# Check .env MAIL configuration
# Test:
php artisan tinker
Mail::raw('Test email', function($m) { $m->to('test@test.com'); });
```

### Vue Components Not Rendering
```bash
# Rebuild assets:
npm run dev
# atau 
npm run build

# Check app.blade.php has:
<script src="{{ asset('js/app.js') }}"></script>
<link rel="stylesheet" href="{{ asset('css/app.css') }}">
```

---

## 📚 Useful Commands

```bash
# Development
php artisan serve
npm run dev

# Database
php artisan migrate
php artisan migrate:rollback
php artisan tinker

# Cache
php artisan cache:clear
php artisan config:cache
php artisan route:cache

# Queue (untuk email newsletter)
php artisan queue:work

# Artisan Make Commands
php artisan make:model Article -m -c -r
php artisan make:migration create_articles_table
php artisan make:controller ArticleController
php artisan make:filament-resource Article

# Testing
php artisan test
pest

# Production
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan optimize
```

---

## 📖 Resources & Documentation

- **Laravel:** https://laravel.com/docs
- **Filament:** https://filamentphp.com/docs
- **Vue.js 3:** https://vuejs.org/
- **Tailwind CSS:** https://tailwindcss.com/
- **Spatie Packages:** https://spatie.be/open-source
- **MySQL:** https://dev.mysql.com/doc/

---

**Last Updated:** 2024
**Author:** Portal Berita Dev Team
**Version:** 1.0.0

---

## 🎯 Next Steps
1. ✅ Setup project locally
2. ✅ Migrate database
3. ✅ Create Filament resources
4. ✅ Build Vue components
5. ✅ Test semua fitur
6. ✅ Deploy ke production

Semoga sukses! 🚀
