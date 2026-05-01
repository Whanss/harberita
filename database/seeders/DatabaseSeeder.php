<?php

namespace Database\Seeders;

use App\Models\Article;
use App\Models\Category;
use App\Models\Comment;
use App\Models\Journalist;
use App\Models\Reader;
use App\Models\Subscription;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Admin user
        $admin = User::factory()->create([
            'name'     => 'Admin Portal',
            'email'    => 'admin@portalberita.com',
            'password' => Hash::make('password'),
            'is_admin' => true,
        ]);

        // Regular readers untuk komentar
        $readers = Reader::factory(5)->create();

        // Kategori
        $categories = collect([
            ['name' => 'Politik',    'description' => 'Berita seputar politik nasional dan internasional.'],
            ['name' => 'Ekonomi',    'description' => 'Berita ekonomi, bisnis, dan keuangan.'],
            ['name' => 'Olahraga',   'description' => 'Berita olahraga terkini dari dalam dan luar negeri.'],
            ['name' => 'Teknologi',  'description' => 'Berita teknologi, gadget, dan inovasi digital.'],
            ['name' => 'Hiburan',    'description' => 'Berita hiburan, selebriti, dan budaya pop.'],
            ['name' => 'Kesehatan',  'description' => 'Berita kesehatan, medis, dan gaya hidup sehat.'],
        ])->map(fn ($data) => Category::create([...$data, 'is_active' => true]));

        // Jurnalis
        $journalists = collect([
            ['name' => 'Budi Santoso',    'position' => 'Editor Utama',       'bio' => 'Budi adalah editor utama dengan pengalaman lebih dari 15 tahun di dunia jurnalistik.'],
            ['name' => 'Siti Rahayu',     'position' => 'Reporter Senior',    'bio' => 'Siti meliput berita politik dan pemerintahan sejak 2010.'],
            ['name' => 'Ahmad Fauzi',     'position' => 'Reporter Olahraga',  'bio' => 'Ahmad adalah reporter olahraga yang berpengalaman meliput berbagai event internasional.'],
            ['name' => 'Dewi Kusuma',     'position' => 'Reporter Teknologi', 'bio' => 'Dewi fokus pada liputan teknologi dan startup digital Indonesia.'],
            ['name' => 'Rizky Pratama',   'position' => 'Reporter Ekonomi',   'bio' => 'Rizky meliput berita ekonomi makro dan pasar modal.'],
        ])->map(fn ($data) => Journalist::create([...$data, 'is_active' => true]));

        // Artikel per kategori
        $articleData = [
            'Politik' => [
                ['title' => 'Pemerintah Umumkan Kebijakan Baru Terkait Pemilu 2026', 'headline' => true,  'views' => 15420],
                ['title' => 'DPR Sahkan RUU Omnibus Law Setelah Perdebatan Panjang',  'headline' => true,  'views' => 12300],
                ['title' => 'Partai Oposisi Ajukan Mosi Tidak Percaya ke Kabinet',    'headline' => false, 'views' => 8900],
                ['title' => 'Presiden Lantik 12 Menteri Baru dalam Reshuffle Kabinet','headline' => false, 'views' => 7650],
                ['title' => 'KPU Tetapkan Jadwal Resmi Pilkada Serentak 2026',        'headline' => false, 'views' => 5430],
            ],
            'Ekonomi' => [
                ['title' => 'Rupiah Menguat ke Level Terbaik dalam 6 Bulan Terakhir', 'headline' => true,  'views' => 11200],
                ['title' => 'Inflasi Mei 2026 Tercatat 2,8 Persen, Terendah Tahun Ini','headline' => false, 'views' => 9800],
                ['title' => 'Bank Indonesia Pertahankan Suku Bunga Acuan di 5,75%',   'headline' => false, 'views' => 7200],
                ['title' => 'Startup Unicorn Baru Indonesia Raih Pendanaan Rp 2 Triliun','headline' => false, 'views' => 6100],
                ['title' => 'Ekspor Nonmigas Indonesia Naik 12% di Kuartal Pertama',  'headline' => false, 'views' => 4300],
            ],
            'Olahraga' => [
                ['title' => 'Timnas Indonesia Lolos ke Semifinal Piala Asia 2026',    'headline' => true,  'views' => 28900],
                ['title' => 'Persija Jakarta Juara Liga 1 Musim 2025/2026',           'headline' => true,  'views' => 22100],
                ['title' => 'Atlet Bulu Tangkis Indonesia Raih Emas di All England',  'headline' => false, 'views' => 18700],
                ['title' => 'MotoGP Mandalika 2026 Resmi Dibuka, Ribuan Penonton Hadir','headline' => false, 'views' => 15400],
                ['title' => 'Kevin Sanjaya Umumkan Pensiun dari Bulu Tangkis Profesional','headline' => false, 'views' => 12300],
            ],
            'Teknologi' => [
                ['title' => 'Apple Luncurkan iPhone 17 dengan Fitur AI Revolusioner', 'headline' => false, 'views' => 19800],
                ['title' => 'Google Rilis Gemini Ultra 2.0, Saingi GPT-5 OpenAI',    'headline' => false, 'views' => 16500],
                ['title' => 'Starlink Perluas Layanan ke 500 Desa Terpencil Indonesia','headline' => false, 'views' => 13200],
                ['title' => 'Pemerintah Luncurkan Platform Digital UMKM Nasional',    'headline' => false, 'views' => 9800],
                ['title' => 'Hacker Bobol Data 10 Juta Pengguna Aplikasi Populer',    'headline' => false, 'views' => 8700],
            ],
            'Hiburan' => [
                ['title' => 'Film "Laskar Pelangi 2" Pecahkan Rekor Box Office Indonesia','headline' => false, 'views' => 14500],
                ['title' => 'Raisa Umumkan Konser Tunggal di 5 Kota Besar Indonesia', 'headline' => false, 'views' => 11200],
                ['title' => 'Serial Netflix Indonesia "Gadis Kretek 2" Trending Global','headline' => false, 'views' => 9800],
                ['title' => 'Dian Sastro Raih Penghargaan Aktris Terbaik Asia',       'headline' => false, 'views' => 7600],
                ['title' => 'Festival Film Cannes 2026: Sutradara Indonesia Masuk Nominasi','headline' => false, 'views' => 5400],
            ],
            'Kesehatan' => [
                ['title' => 'WHO Umumkan Vaksin Baru Malaria 95% Efektif',            'headline' => false, 'views' => 12300],
                ['title' => 'Kemenkes Luncurkan Program Deteksi Dini Kanker Gratis',  'headline' => false, 'views' => 9800],
                ['title' => 'Studi Baru: Tidur 7-8 Jam Kurangi Risiko Alzheimer 40%','headline' => false, 'views' => 8700],
                ['title' => 'BPOM Setujui Obat Diabetes Generasi Terbaru',            'headline' => false, 'views' => 6500],
                ['title' => 'Tips Menjaga Kesehatan Mental di Era Digital',           'headline' => false, 'views' => 5200],
            ],
        ];

        $allArticles = collect();

        foreach ($articleData as $categoryName => $articles) {
            $category   = $categories->firstWhere('name', $categoryName);
            $journalist = $journalists->random();

            foreach ($articles as $i => $data) {
                $article = Article::create([
                    'category_id'   => $category->id,
                    'journalist_id' => $journalist->id,
                    'title'         => $data['title'],
                    'excerpt'       => 'Ini adalah ringkasan singkat dari artikel berjudul "' . $data['title'] . '". Baca selengkapnya untuk informasi lebih detail.',
                    'content'       => $this->generateContent($data['title']),
                    'is_headline'   => $data['headline'],
                    'view_count'    => $data['views'],
                    'status'        => 'published',
                    'published_at'  => now()->subHours(rand(1, 720)),
                    'notified_subscribers_at' => now()->subHours(rand(1, 720)),
                ]);

                $allArticles->push($article);
            }
        }

        // Komentar
        $allArticles->random(10)->each(function (Article $article) use ($readers) {
            $count = rand(2, 6);
            for ($i = 0; $i < $count; $i++) {
                Comment::create([
                    'article_id' => $article->id,
                    'reader_id'  => $readers->random()->id,
                    'content'    => collect([
                        'Artikel yang sangat informatif dan bermanfaat!',
                        'Terima kasih atas liputannya, sangat membantu.',
                        'Saya setuju dengan analisis yang disampaikan.',
                        'Berita ini perlu disebarluaskan lebih lanjut.',
                        'Semoga pemerintah segera menindaklanjuti hal ini.',
                        'Informasi yang sangat berguna untuk masyarakat.',
                    ])->random(),
                    'status'      => collect(['approved', 'approved', 'approved', 'pending'])->random(),
                    'approved_at' => now()->subHours(rand(1, 48)),
                ]);
            }
        });

        // Subscriber
        $subscriberEmails = [
            'subscriber1@gmail.com',
            'subscriber2@yahoo.com',
            'pembaca@hotmail.com',
            'berita.fan@gmail.com',
            'news.reader@outlook.com',
            'info.seeker@gmail.com',
            'daily.news@yahoo.com',
            'portal.fan@gmail.com',
        ];

        foreach ($subscriberEmails as $email) {
            Subscription::create([
                'email'         => $email,
                'is_active'     => true,
                'subscribed_at' => now()->subDays(rand(1, 90)),
            ]);
        }

        // 1 subscriber tidak aktif
        Subscription::create([
            'email'             => 'unsubscribed@gmail.com',
            'is_active'         => false,
            'subscribed_at'     => now()->subDays(60),
            'unsubscribed_at'   => now()->subDays(10),
        ]);
    }

    private function generateContent(string $title): string
    {
        return "<p><strong>{$title}</strong> — Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.</p>

<p>Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>

<h2>Latar Belakang</h2>
<p>Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium doloremque laudantium, totam rem aperiam, eaque ipsa quae ab illo inventore veritatis et quasi architecto beatae vitae dicta sunt explicabo.</p>

<p>Nemo enim ipsam voluptatem quia voluptas sit aspernatur aut odit aut fugit, sed quia consequuntur magni dolores eos qui ratione voluptatem sequi nesciunt. Neque porro quisquam est, qui dolorem ipsum quia dolor sit amet.</p>

<h2>Perkembangan Terkini</h2>
<p>At vero eos et accusamus et iusto odio dignissimos ducimus qui blanditiis praesentium voluptatum deleniti atque corrupti quos dolores et quas molestias excepturi sint occaecati cupiditate non provident.</p>

<p>Similique sunt in culpa qui officia deserunt mollitia animi, id est laborum et dolorum fuga. Et harum quidem rerum facilis est et expedita distinctio.</p>

<h2>Kesimpulan</h2>
<p>Nam libero tempore, cum soluta nobis est eligendi optio cumque nihil impedit quo minus id quod maxime placeat facere possimus, omnis voluptas assumenda est, omnis dolor repellendus.</p>";
    }
}
