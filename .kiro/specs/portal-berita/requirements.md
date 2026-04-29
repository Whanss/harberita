# Dokumen Requirements

## Pendahuluan

Portal Berita adalah platform web berbasis Laravel 12 + Vue 3 yang memungkinkan pembaca mengakses berita terkini, menelusuri konten berdasarkan kategori, mencari berita dengan kata kunci, serta berinteraksi melalui komentar dan fitur berbagi. Admin dapat mengelola seluruh konten melalui panel Filament v5 di `/admin`. Platform ini mendukung konten multimedia (foto dan video), sistem langganan via email/notifikasi, arsip berita, profil jurnalis, serta halaman kontak redaksi.

---

## Glosarium

- **Portal_Berita**: Sistem keseluruhan platform portal berita.
- **Artikel**: Satu unit konten berita yang terdiri dari judul, isi, kategori, penulis, dan metadata terkait.
- **Kategori**: Pengelompokan artikel berdasarkan topik (Politik, Ekonomi, Olahraga, Hiburan, Teknologi, dll).
- **Jurnalis**: Pengguna terdaftar dengan peran penulis yang dapat membuat dan mengelola artikel.
- **Admin**: Pengguna dengan akses penuh ke panel Filament untuk mengelola seluruh konten dan konfigurasi.
- **Pembaca**: Pengguna yang mengakses portal untuk membaca berita, dapat terdaftar (authenticated) atau tamu (guest).
- **Slug**: Versi URL-friendly dari judul artikel, dibuat otomatis menggunakan `spatie/laravel-sluggable`.
- **Media**: Aset gambar atau video yang terlampir pada artikel, dikelola menggunakan `spatie/laravel-medialibrary`.
- **Komentar**: Tanggapan teks yang ditulis oleh Pembaca terdaftar pada sebuah Artikel.
- **Langganan**: Mekanisme di mana Pembaca mendaftarkan email atau mengaktifkan notifikasi push untuk menerima berita terbaru.
- **Arsip**: Kumpulan Artikel lama yang tetap dapat diakses dan ditelusuri berdasarkan periode waktu.
- **Scout**: Laravel Scout, digunakan sebagai mesin pencarian full-text untuk Artikel.
- **SEO_Tools**: Paket `artesaos/seotools` yang digunakan untuk mengatur meta tag, Open Graph, dan data terstruktur.

---

## Requirements

### Requirement 1: Berita Utama (Headline)

**User Story:** Sebagai Pembaca, saya ingin melihat berita utama di halaman depan, sehingga saya dapat langsung mengetahui berita terpenting hari ini.

#### Acceptance Criteria

1. THE Portal_Berita SHALL menampilkan minimal 1 dan maksimal 5 Artikel yang ditandai sebagai headline di bagian paling atas halaman utama.
2. WHEN Pembaca membuka halaman utama, THE Portal_Berita SHALL memuat daftar Artikel headline dalam waktu kurang dari 3 detik.
3. THE Portal_Berita SHALL menampilkan judul, gambar utama, ringkasan (maksimal 200 karakter), kategori, dan waktu publikasi untuk setiap Artikel headline.
4. WHEN Pembaca mengklik sebuah Artikel headline, THE Portal_Berita SHALL mengarahkan Pembaca ke halaman detail Artikel yang sesuai.
5. WHILE tidak ada Artikel yang ditandai sebagai headline, THE Portal_Berita SHALL menampilkan 5 Artikel terbaru sebagai pengganti di bagian headline.

---

### Requirement 2: Kategori Berita

**User Story:** Sebagai Pembaca, saya ingin menelusuri berita berdasarkan kategori, sehingga saya dapat menemukan berita sesuai minat saya dengan mudah.

#### Acceptance Criteria

1. THE Portal_Berita SHALL menyediakan navigasi kategori yang menampilkan semua Kategori aktif.
2. WHEN Pembaca memilih sebuah Kategori, THE Portal_Berita SHALL menampilkan daftar Artikel yang termasuk dalam Kategori tersebut, diurutkan berdasarkan waktu publikasi terbaru.
3. THE Portal_Berita SHALL menampilkan nama Kategori, jumlah Artikel, dan deskripsi singkat pada halaman daftar Kategori.
4. WHEN sebuah Kategori tidak memiliki Artikel yang dipublikasikan, THE Portal_Berita SHALL menampilkan pesan "Belum ada artikel dalam kategori ini."
5. THE Portal_Berita SHALL mendukung pagination dengan 12 Artikel per halaman pada halaman Kategori.
6. WHEN Admin menonaktifkan sebuah Kategori, THE Portal_Berita SHALL menyembunyikan Kategori tersebut dari navigasi publik.

---

### Requirement 3: Pencarian Berita

**User Story:** Sebagai Pembaca, saya ingin mencari berita berdasarkan kata kunci, sehingga saya dapat menemukan Artikel yang relevan dengan topik yang saya cari.

#### Acceptance Criteria

1. THE Portal_Berita SHALL menyediakan kolom pencarian yang dapat diakses dari semua halaman publik.
2. WHEN Pembaca memasukkan kata kunci dengan minimal 3 karakter dan menekan tombol cari atau Enter, THE Scout SHALL melakukan pencarian full-text pada judul dan isi Artikel yang dipublikasikan.
3. THE Portal_Berita SHALL menampilkan hasil pencarian dengan judul, ringkasan, kategori, dan tanggal publikasi setiap Artikel yang cocok.
4. WHEN pencarian tidak menghasilkan Artikel yang cocok, THE Portal_Berita SHALL menampilkan pesan "Tidak ditemukan hasil untuk kata kunci '[kata kunci]'."
5. THE Portal_Berita SHALL menampilkan jumlah total hasil pencarian di bagian atas daftar hasil.
6. THE Portal_Berita SHALL mendukung pagination dengan 10 Artikel per halaman pada halaman hasil pencarian.
7. IF kata kunci yang dimasukkan kurang dari 3 karakter, THEN THE Portal_Berita SHALL menampilkan pesan validasi "Kata kunci minimal 3 karakter."

---

### Requirement 4: Berita Terpopuler

**User Story:** Sebagai Pembaca, saya ingin melihat berita yang paling banyak dibaca atau dibagikan, sehingga saya dapat mengikuti topik yang sedang ramai dibicarakan.

#### Acceptance Criteria

1. THE Portal_Berita SHALL menampilkan daftar 10 Artikel terpopuler berdasarkan jumlah penayangan (view count) dalam 7 hari terakhir di widget sidebar atau seksi khusus.
2. WHEN Pembaca membuka halaman detail Artikel, THE Portal_Berita SHALL menambahkan 1 pada penghitung penayangan Artikel tersebut.
3. THE Portal_Berita SHALL menampilkan judul, gambar thumbnail, jumlah penayangan, dan tanggal publikasi untuk setiap Artikel terpopuler.
4. WHEN Pembaca mengklik sebuah Artikel terpopuler, THE Portal_Berita SHALL mengarahkan Pembaca ke halaman detail Artikel yang sesuai.
5. THE Portal_Berita SHALL memperbarui daftar Artikel terpopuler setiap 1 jam sekali menggunakan cache.

---

### Requirement 5: Berita Terbaru

**User Story:** Sebagai Pembaca, saya ingin melihat berita yang baru saja dipublikasikan, sehingga saya selalu mendapatkan informasi terkini.

#### Acceptance Criteria

1. THE Portal_Berita SHALL menampilkan daftar Artikel terbaru di halaman utama, diurutkan berdasarkan waktu publikasi dari yang terbaru.
2. THE Portal_Berita SHALL menampilkan minimal 10 Artikel terbaru di halaman utama dengan judul, gambar thumbnail, kategori, ringkasan (maksimal 150 karakter), dan waktu publikasi relatif (contoh: "2 jam yang lalu").
3. THE Portal_Berita SHALL menyediakan halaman khusus "Berita Terbaru" yang mendukung pagination dengan 15 Artikel per halaman.
4. WHEN sebuah Artikel baru dipublikasikan oleh Admin, THE Portal_Berita SHALL menampilkan Artikel tersebut di daftar berita terbaru dalam waktu kurang dari 1 menit.
5. THE Portal_Berita SHALL hanya menampilkan Artikel dengan status "published" dan tanggal publikasi yang tidak melebihi waktu saat ini.

---

### Requirement 6: Galeri Foto

**User Story:** Sebagai Pembaca, saya ingin melihat galeri foto terkait berita, sehingga saya mendapatkan gambaran visual yang lebih lengkap tentang suatu peristiwa.

#### Acceptance Criteria

1. THE Portal_Berita SHALL menampilkan galeri foto pada halaman detail Artikel yang memiliki lebih dari 1 gambar terlampir.
2. THE Portal_Berita SHALL menggunakan `spatie/laravel-medialibrary` untuk menyimpan dan mengelola semua Media gambar yang terlampir pada Artikel.
3. WHEN Pembaca mengklik sebuah gambar dalam galeri, THE Portal_Berita SHALL menampilkan gambar tersebut dalam tampilan lightbox dengan navigasi ke gambar sebelumnya dan berikutnya.
4. THE Portal_Berita SHALL menampilkan keterangan (caption) gambar di bawah setiap gambar dalam lightbox jika keterangan tersedia.
5. THE Portal_Berita SHALL menyediakan halaman "Galeri Foto" khusus yang menampilkan koleksi foto dari berbagai Artikel, diurutkan berdasarkan tanggal terbaru.
6. THE Portal_Berita SHALL menggunakan `intervention/image` untuk menghasilkan thumbnail gambar dengan ukuran 400x300 piksel untuk tampilan grid galeri.
7. IF sebuah file gambar yang diunggah melebihi ukuran 10 MB, THEN THE Portal_Berita SHALL menolak unggahan dan menampilkan pesan error "Ukuran file maksimal 10 MB."

---

### Requirement 7: Video Berita

**User Story:** Sebagai Pembaca, saya ingin menonton video berita terkait, sehingga saya dapat memahami berita secara lebih mendalam melalui konten visual.

#### Acceptance Criteria

1. THE Portal_Berita SHALL mendukung penyisipan video dari YouTube dan Vimeo melalui URL embed pada Artikel.
2. WHEN Pembaca membuka halaman detail Artikel yang memiliki video, THE Portal_Berita SHALL menampilkan video player yang dapat diputar langsung di halaman tersebut.
3. THE Portal_Berita SHALL menyediakan halaman "Video Berita" khusus yang menampilkan daftar Artikel yang memiliki konten video, diurutkan berdasarkan tanggal terbaru.
4. THE Portal_Berita SHALL menampilkan thumbnail video, judul, durasi (jika tersedia), dan tanggal publikasi pada daftar halaman Video Berita.
5. IF URL video yang dimasukkan Admin bukan URL YouTube atau Vimeo yang valid, THEN THE Portal_Berita SHALL menampilkan pesan error "URL video tidak valid. Gunakan URL dari YouTube atau Vimeo."

---

### Requirement 8: Komentar

**User Story:** Sebagai Pembaca terdaftar, saya ingin memberikan komentar pada berita, sehingga saya dapat berbagi pendapat dan berdiskusi dengan pembaca lain.

#### Acceptance Criteria

1. THE Portal_Berita SHALL menampilkan seksi komentar di bagian bawah setiap halaman detail Artikel.
2. WHEN Pembaca terdaftar mengirimkan komentar dengan panjang antara 5 dan 1000 karakter, THE Portal_Berita SHALL menyimpan Komentar dan menampilkannya dalam daftar komentar Artikel tersebut.
3. THE Portal_Berita SHALL menampilkan nama pengguna, waktu komentar, dan isi Komentar untuk setiap Komentar yang disetujui.
4. WHILE Pembaca belum login, THE Portal_Berita SHALL menampilkan tombol "Login untuk berkomentar" sebagai pengganti form komentar.
5. IF isi Komentar mengandung URL atau tag HTML, THEN THE Portal_Berita SHALL membersihkan konten tersebut sebelum menyimpan Komentar.
6. THE Portal_Berita SHALL mendukung moderasi komentar, di mana Admin dapat menyetujui, menolak, atau menghapus Komentar melalui panel Filament.
7. WHEN Admin menghapus sebuah Komentar, THE Portal_Berita SHALL menghapus Komentar tersebut secara permanen dari database.

---

### Requirement 9: Berbagi Berita

**User Story:** Sebagai Pembaca, saya ingin membagikan berita ke media sosial, sehingga saya dapat menyebarkan informasi kepada orang lain.

#### Acceptance Criteria

1. THE Portal_Berita SHALL menampilkan tombol berbagi untuk platform Facebook, Twitter/X, WhatsApp, dan Telegram pada setiap halaman detail Artikel.
2. WHEN Pembaca mengklik tombol berbagi, THE Portal_Berita SHALL membuka jendela berbagi platform yang sesuai dengan URL Artikel dan judul Artikel yang sudah terisi.
3. THE Portal_Berita SHALL menggunakan `SEO_Tools` untuk mengatur meta tag Open Graph sehingga pratinjau tautan di media sosial menampilkan judul, deskripsi, dan gambar Artikel yang benar.
4. THE Portal_Berita SHALL menampilkan tombol salin tautan (copy link) yang menyalin URL Artikel ke clipboard Pembaca.
5. WHEN Pembaca berhasil menyalin tautan, THE Portal_Berita SHALL menampilkan notifikasi "Tautan berhasil disalin!" selama 3 detik.

---

### Requirement 10: Langganan Berita

**User Story:** Sebagai Pembaca, saya ingin berlangganan berita melalui email atau notifikasi, sehingga saya mendapatkan pemberitahuan otomatis ketika ada berita baru.

#### Acceptance Criteria

1. THE Portal_Berita SHALL menyediakan form langganan email yang dapat diakses dari halaman utama dan footer.
2. WHEN Pembaca memasukkan alamat email yang valid dan mengklik tombol "Langganan", THE Portal_Berita SHALL menyimpan alamat email tersebut ke daftar Langganan dan mengirimkan email konfirmasi.
3. IF alamat email yang dimasukkan sudah terdaftar dalam daftar Langganan, THEN THE Portal_Berita SHALL menampilkan pesan "Email ini sudah terdaftar sebagai pelanggan."
4. IF alamat email yang dimasukkan tidak dalam format yang valid, THEN THE Portal_Berita SHALL menampilkan pesan validasi "Format email tidak valid."
5. WHEN sebuah Artikel baru dipublikasikan, THE Portal_Berita SHALL mengirimkan email notifikasi ke semua pelanggan aktif yang berisi judul, ringkasan, dan tautan Artikel tersebut.
6. THE Portal_Berita SHALL menyertakan tautan berhenti berlangganan (unsubscribe) yang unik di setiap email notifikasi.
7. WHEN Pembaca mengklik tautan berhenti berlangganan, THE Portal_Berita SHALL menonaktifkan Langganan tersebut dan menampilkan halaman konfirmasi "Anda telah berhenti berlangganan."

---

### Requirement 11: Arsip Berita

**User Story:** Sebagai Pembaca, saya ingin mengakses berita lama melalui arsip, sehingga saya dapat menemukan kembali berita dari periode waktu tertentu.

#### Acceptance Criteria

1. THE Portal_Berita SHALL menyediakan halaman Arsip yang menampilkan daftar Artikel dikelompokkan berdasarkan bulan dan tahun publikasi.
2. WHEN Pembaca memilih periode bulan dan tahun tertentu, THE Portal_Berita SHALL menampilkan semua Artikel yang dipublikasikan pada periode tersebut, diurutkan dari yang terbaru.
3. THE Portal_Berita SHALL menampilkan jumlah Artikel untuk setiap periode bulan dan tahun di navigasi Arsip.
4. THE Portal_Berita SHALL mendukung pagination dengan 20 Artikel per halaman pada halaman Arsip.
5. THE Portal_Berita SHALL menyediakan filter Kategori pada halaman Arsip sehingga Pembaca dapat mempersempit hasil berdasarkan Kategori dan periode waktu secara bersamaan.

---

### Requirement 12: Profil Jurnalis

**User Story:** Sebagai Pembaca, saya ingin melihat profil jurnalis atau penulis berita, sehingga saya dapat mengetahui latar belakang penulis dan membaca artikel lain dari penulis yang sama.

#### Acceptance Criteria

1. THE Portal_Berita SHALL menampilkan nama Jurnalis, foto profil, jabatan, dan bio singkat (maksimal 500 karakter) pada setiap halaman detail Artikel di bagian bawah konten.
2. THE Portal_Berita SHALL menyediakan halaman profil publik untuk setiap Jurnalis yang dapat diakses melalui URL `/jurnalis/{slug}`.
3. WHEN Pembaca membuka halaman profil Jurnalis, THE Portal_Berita SHALL menampilkan semua Artikel yang ditulis oleh Jurnalis tersebut, diurutkan berdasarkan waktu publikasi terbaru.
4. THE Portal_Berita SHALL mendukung pagination dengan 12 Artikel per halaman pada halaman profil Jurnalis.
5. WHEN Admin memperbarui data profil Jurnalis melalui panel Filament, THE Portal_Berita SHALL memperbarui tampilan profil publik Jurnalis tersebut secara langsung.

---

### Requirement 13: Halaman Kontak

**User Story:** Sebagai Pembaca, saya ingin melihat informasi kontak redaksi, sehingga saya dapat menghubungi tim editorial untuk pertanyaan, koreksi, atau pengiriman berita.

#### Acceptance Criteria

1. THE Portal_Berita SHALL menyediakan halaman Kontak yang dapat diakses melalui navigasi utama dengan URL `/kontak`.
2. THE Portal_Berita SHALL menampilkan informasi kontak redaksi yang meliputi: nama redaksi, alamat, nomor telepon, dan alamat email pada halaman Kontak.
3. THE Portal_Berita SHALL menyediakan form kontak pada halaman Kontak dengan field: nama pengirim, alamat email pengirim, subjek, dan pesan.
4. WHEN Pembaca mengisi form kontak dengan data yang valid dan mengklik tombol "Kirim", THE Portal_Berita SHALL mengirimkan pesan ke alamat email redaksi dan menampilkan notifikasi "Pesan Anda berhasil dikirim."
5. IF salah satu field wajib pada form kontak tidak diisi, THEN THE Portal_Berita SHALL menampilkan pesan validasi pada field yang kosong tersebut.
6. THE Portal_Berita SHALL membatasi pengiriman form kontak maksimal 5 kali per jam per alamat IP untuk mencegah spam.

---

### Requirement 14: Manajemen Artikel oleh Admin

**User Story:** Sebagai Admin, saya ingin membuat, mengedit, dan mengelola artikel berita melalui panel admin, sehingga konten portal selalu terbarui dan terorganisir dengan baik.

#### Acceptance Criteria

1. THE Portal_Berita SHALL menyediakan resource Artikel di panel Filament yang memungkinkan Admin membuat Artikel baru dengan field: judul, slug (otomatis dari judul), isi (rich text editor), ringkasan, Kategori, Jurnalis, gambar utama, galeri foto, URL video, tag, status (draft/published/archived), dan tanggal publikasi.
2. WHEN Admin menyimpan Artikel baru dengan status "published", THE Portal_Berita SHALL membuat Slug unik secara otomatis menggunakan `spatie/laravel-sluggable` berdasarkan judul Artikel.
3. THE Portal_Berita SHALL memungkinkan Admin mengunggah gambar utama dan gambar galeri menggunakan `spatie/laravel-medialibrary` dengan validasi format file (JPEG, PNG, WebP) dan ukuran maksimal 10 MB per file.
4. WHEN Admin mempublikasikan sebuah Artikel, THE Portal_Berita SHALL mengatur meta tag SEO (title, description, Open Graph) secara otomatis menggunakan `SEO_Tools` berdasarkan judul dan ringkasan Artikel.
5. THE Portal_Berita SHALL memungkinkan Admin mengubah status Artikel antara "draft", "published", dan "archived" kapan saja.
6. WHEN Admin mengarsipkan sebuah Artikel, THE Portal_Berita SHALL menyembunyikan Artikel tersebut dari semua halaman publik kecuali halaman Arsip.
7. THE Portal_Berita SHALL menyediakan resource Kategori di panel Filament yang memungkinkan Admin membuat, mengedit, dan menonaktifkan Kategori.
8. THE Portal_Berita SHALL menyediakan resource Jurnalis di panel Filament yang memungkinkan Admin mengelola data profil Jurnalis.
9. THE Portal_Berita SHALL menyediakan resource Komentar di panel Filament yang memungkinkan Admin memoderasi (menyetujui, menolak, menghapus) Komentar.
10. THE Portal_Berita SHALL menyediakan resource Langganan di panel Filament yang memungkinkan Admin melihat daftar pelanggan dan menonaktifkan Langganan tertentu.
11. WHEN Admin menandai sebuah Artikel sebagai headline, THE Portal_Berita SHALL menampilkan Artikel tersebut di seksi headline halaman utama.
