<?php

namespace Database\Seeders;

use App\Models\Article;
use App\Models\Category;
use App\Models\Journalist;
use Illuminate\Database\Seeder;

class ArticleSeeder extends Seeder
{
    /**
     * 20 kategori, masing-masing 1 artikel published.
     */
    public function run(): void
    {
        $categories = [
            ['name' => 'Politik',        'description' => 'Berita seputar politik nasional dan internasional.'],
            ['name' => 'Ekonomi',        'description' => 'Berita ekonomi, bisnis, dan keuangan.'],
            ['name' => 'Olahraga',       'description' => 'Berita olahraga terkini dari dalam dan luar negeri.'],
            ['name' => 'Teknologi',      'description' => 'Berita teknologi, gadget, dan inovasi digital.'],
            ['name' => 'Hiburan',        'description' => 'Berita hiburan, selebriti, dan budaya pop.'],
            ['name' => 'Kesehatan',      'description' => 'Berita kesehatan, medis, dan gaya hidup sehat.'],
            ['name' => 'Pendidikan',     'description' => 'Berita dunia pendidikan dan akademik.'],
            ['name' => 'Hukum',          'description' => 'Berita hukum, kriminal, dan peradilan.'],
            ['name' => 'Lingkungan',     'description' => 'Berita lingkungan hidup dan iklim.'],
            ['name' => 'Internasional',  'description' => 'Berita dari mancanegara dan hubungan luar negeri.'],
            ['name' => 'Sosial',         'description' => 'Berita isu sosial dan kemasyarakatan.'],
            ['name' => 'Budaya',         'description' => 'Berita seni, budaya, dan tradisi.'],
            ['name' => 'Otomotif',       'description' => 'Berita kendaraan, mobil, dan motor.'],
            ['name' => 'Properti',       'description' => 'Berita properti, perumahan, dan investasi.'],
            ['name' => 'Kuliner',        'description' => 'Berita kuliner, makanan, dan minuman.'],
            ['name' => 'Travel',         'description' => 'Berita wisata dan destinasi perjalanan.'],
            ['name' => 'Agama',          'description' => 'Berita keagamaan dan spiritual.'],
            ['name' => 'Militer',        'description' => 'Berita pertahanan, militer, dan keamanan negara.'],
            ['name' => 'Sains',          'description' => 'Berita ilmu pengetahuan dan penelitian.'],
            ['name' => 'Gaya Hidup',     'description' => 'Berita gaya hidup, fashion, dan tren.'],
        ];

        // 1 artikel per kategori — judul berbeda agar mudah dibedakan
        $articles = [
            'Politik'       => 'Pemerintah Umumkan Kebijakan Baru Terkait Pemilu 2026',
            'Ekonomi'       => 'Rupiah Menguat ke Level Terbaik dalam 6 Bulan Terakhir',
            'Olahraga'      => 'Timnas Indonesia Lolos ke Semifinal Piala Asia 2026',
            'Teknologi'     => 'Apple Luncurkan iPhone 17 dengan Fitur AI Revolusioner',
            'Hiburan'       => 'Film "Laskar Pelangi 2" Pecahkan Rekor Box Office Indonesia',
            'Kesehatan'     => 'WHO Umumkan Vaksin Baru Malaria 95% Efektif',
            'Pendidikan'    => 'Kemendikbud Luncurkan Kurikulum Merdeka Versi 3.0',
            'Hukum'         => 'KPK Tetapkan Tersangka Baru Kasus Korupsi Dana Desa',
            'Lingkungan'    => 'Kebakaran Hutan Kalimantan Meluas, 50.000 Hektar Terdampak',
            'Internasional' => 'PBB Serukan Gencatan Senjata Segera di Timur Tengah',
            'Sosial'        => 'Angka Kemiskinan Indonesia Turun ke 8,5 Persen di 2026',
            'Budaya'        => 'Batik Indonesia Resmi Masuk Warisan Budaya UNESCO Ke-2',
            'Otomotif'      => 'Toyota Luncurkan Mobil Listrik Pertama Rakitan Indonesia',
            'Properti'      => 'Harga Rumah di Jakarta Naik 15 Persen Sepanjang 2025',
            'Kuliner'       => 'Rendang Dinobatkan Makanan Terenak Dunia Versi CNN 2026',
            'Travel'        => 'Bali Masuk 10 Destinasi Wisata Terbaik Dunia Versi TripAdvisor',
            'Agama'         => 'Pemerintah Tetapkan 1 Ramadan 1448 H Jatuh pada 15 Januari',
            'Militer'       => 'TNI Tambah 12 Kapal Perang Baru untuk Perkuat Armada Laut',
            'Sains'         => 'Ilmuwan Indonesia Temukan Spesies Baru di Hutan Papua',
            'Gaya Hidup'    => 'Tren Hidup Minimalis Makin Populer di Kalangan Milenial Indonesia',
        ];

        // Pastikan ada minimal 1 jurnalis
        $journalist = Journalist::first();
        if (! $journalist) {
            $journalist = Journalist::create([
                'name'      => 'Redaksi Hasberita',
                'position'  => 'Editor',
                'bio'       => 'Tim redaksi Hasberita.id.',
                'is_active' => true,
            ]);
        }

        foreach ($categories as $catData) {
            // Buat atau ambil kategori
            $category = Category::firstOrCreate(
                ['name' => $catData['name']],
                ['description' => $catData['description'], 'is_active' => true]
            );

            $title = $articles[$catData['name']];

            // Skip kalau artikel dengan judul ini sudah ada
            if (Article::where('title', $title)->exists()) {
                $this->command->line("  Skip: \"{$title}\" sudah ada.");
                continue;
            }

            Article::create([
                'category_id'             => $category->id,
                'journalist_id'           => $journalist->id,
                'title'                   => $title,
                'excerpt'                 => "Ringkasan berita kategori {$catData['name']}: {$title}. Baca selengkapnya untuk informasi lebih detail.",
                'content'                 => $this->generateContent($title, $catData['name']),
                'is_headline'             => false,
                'view_count'              => rand(100, 5000),
                'status'                  => 'published',
                'published_at'            => now()->subHours(rand(1, 168)),
                'notified_subscribers_at' => now()->subHours(rand(1, 168)),
            ]);

            $this->command->info("  ✓ [{$catData['name']}] {$title}");
        }

        $this->command->info('ArticleSeeder selesai — 20 kategori, 1 artikel per kategori.');
    }

    private function generateContent(string $title, string $category): string
    {
        return "<p><strong>{$title}</strong> — Berita terbaru dari kategori <em>{$category}</em>. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris.</p>

<p>Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>

<h2>Latar Belakang</h2>
<p>Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium doloremque laudantium, totam rem aperiam, eaque ipsa quae ab illo inventore veritatis et quasi architecto beatae vitae dicta sunt explicabo. Nemo enim ipsam voluptatem quia voluptas sit aspernatur aut odit aut fugit.</p>

<p>Neque porro quisquam est, qui dolorem ipsum quia dolor sit amet, consectetur, adipisci velit, sed quia non numquam eius modi tempora incidunt ut labore et dolore magnam aliquam quaerat voluptatem.</p>

<h2>Perkembangan Terkini</h2>
<p>At vero eos et accusamus et iusto odio dignissimos ducimus qui blanditiis praesentium voluptatum deleniti atque corrupti quos dolores et quas molestias excepturi sint occaecati cupiditate non provident, similique sunt in culpa qui officia deserunt mollitia animi.</p>

<p>Nam libero tempore, cum soluta nobis est eligendi optio cumque nihil impedit quo minus id quod maxime placeat facere possimus, omnis voluptas assumenda est, omnis dolor repellendus.</p>

<h2>Kesimpulan</h2>
<p>Temporibus autem quibusdam et aut officiis debitis aut rerum necessitatibus saepe eveniet ut et voluptates repudiandae sint et molestiae non recusandae. Itaque earum rerum hic tenetur a sapiente delectus.</p>";
    }
}
