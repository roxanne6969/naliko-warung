# ☕ Naliko Warung - Sistem Informasi Manajemen Pemesanan & Kasir

Sistem informasi manajemen pemesanan, kasir (POS), dan verifikasi pembayaran berbasis web yang dirancang khusus untuk cafe Naliko Warung. Aplikasi ini dibuat untuk mendigitalisasi alur pemesanan produk, mempermudah pelacakan status pesanan secara real-time oleh pelanggan, serta membantu kasir dalam mengelola antrean dapur dan verifikasi transaksi keuangan.

---

## 🚀 Fitur Utama Sistem

### 🛒 1. Sisi Pelanggan (Customer Flow)
- Katalog Menu Digital: Mengakses daftar makanan dan minuman lengkap dengan kategori, harga terbaru, dan status ketersediaan stok secara real-time.
- Keranjang & Pesan Instan: Mengatur jumlah pesanan, memasukkan nomor meja, serta menambahkan catatan khusus untuk dapur langsung dari perangkat masing-masing.
- Fleksibilitas Metode Pembayaran: Mendukung pilihan pembayaran instan menggunakan QRIS (dengan fitur unggah bukti transfer) atau Cash/Tunai langsung di meja kasir.
- Pelacakan Status Real-Time: Menampilkan halaman indikator status pesanan interaktif (Menunggu Konfirmasi ⏳ -> Sedang Diproses 👨‍🍳 -> Pesanan Siap ✅ -> Selesai 🎉) dengan fitur penyegaran otomatis (auto-refresh).

### 💳 2. Sisi Kasir (Kasir & POS Flow)
- Point of Sale (POS) Offline: Melayani dan menginput pesanan pelanggan yang datang langsung secara manual di kasir.
- Verifikasi Pembayaran Digital: Memeriksa kesesuaian nominal, metode transfer, dan foto bukti pembayaran yang diunggah oleh pelanggan online sebelum meneruskan pesanan ke dapur.
- Manajemen Antrean Dapur: Memperbarui status pengerjaan makanan dan minuman dari ruang kasir agar sinkron dengan halaman pelacakan pelanggan.
- Riwayat Penjualan: Mencatat seluruh transaksi finansial yang sukses ke dalam sistem log riwayat terpusat lengkap dengan sistem halaman (pagination).

### 👨‍💻 3. Sisi Admin (Back-Office Management)
- Manajemen Produk & Kategori: Mengelola menu kafe, mengubah harga, mengunggah foto produk, serta memantau manajemen stok barang.
- Manajemen Pengguna: Mengatur hak akses dan pembuatan akun untuk staf kasir maupun sesama admin.
- Laporan Finansial: Memantau akumulasi total pesanan harian, total pendapatan bersih, hingga statistik menu terlaris melalui dashboard summary.

---

## 🛠️ Arsitektur & Teknologi

Aplikasi ini dibangun menggunakan ekosistem modern PHP dan JavaScript dengan performa tinggi:
- Framework Utama: Laravel 11 (PHP)
- Interaktivitas Frontend: Livewire & Alpine.js (Reactive Component)
- Desain & Antarmuka: Tailwind CSS (Modern Utility-first CSS)
- Sistem Ikon Pro: Blade Heroicons (SVG Component Integration)
- Database Management: MySQL

---

## ⚙️ Panduan Instalasi & Menjalankan Proyek di Lokal

### 1. Kloning Repositori
git clone https://github.com/roxanne6969/naliko-warung.git
cd naliko-warung

### 2. Instalasi Dependensi Backend (Composer)
composer install

### 3. Instalasi & Kompilasi Dependensi Frontend (NPM)
npm install
npm run build

### 4. Konfigurasi Environment File
Duplikat file .env.example menjadi .env lalu buat database baru bernama naliko_warung.
cp .env.example .env
php artisan key:generate

### 5. Migrasi Database & Seeding Data Awal
php artisan migrate --seed

### 6. Menjalankan Server Lokal Laravel
php artisan serve & npm run dev
