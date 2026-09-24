# TAMBORA · Lapor KUPVA

Aplikasi pelaporan anonim dan pengawasan KUPVA untuk wilayah Provinsi Nusa Tenggara Barat. Portal masyarakat tersedia tanpa akun, sedangkan operasional laporan dikelola melalui panel Admin berbasis Filament.

## Fitur utama

- Pelaporan anonim tanpa nama, NIK, email, atau nomor telepon.
- Pemilihan lokasi terlapor melalui GPS, pencarian, dan pin peta.
- Lampiran bukti privat.
- Kode laporan dan PIN untuk pelacakan enam tahap penanganan.
- Komunikasi anonim dua arah antara pelapor dan petugas.
- Dashboard Admin dengan KPI, peta, grafik status/wilayah, dan laporan terbaru.
- Peran Super Admin dan Admin dengan pembatasan akses.
- Pengelolaan data KUPVA tanpa penghapusan permanen.

## Menjalankan aplikasi

Persyaratan utama: PHP 8.4, Composer, Node.js, dan database yang didukung Laravel.

```powershell
composer install
Copy-Item .env.example .env
php artisan key:generate
npm install
php artisan migrate
npm run build
composer run dev
```

Isi `SEED_SUPER_ADMIN_EMAIL` dan `SEED_SUPER_ADMIN_PASSWORD` pada `.env`, lalu jalankan `php artisan db:seed` untuk membuat akun Super Admin dan data demo pada lingkungan lokal.

Portal publik tersedia di `/`, sedangkan panel internal berada di `/admin`.

## Dokumen produk

- [Product Requirements Document](PRD-TAMBORA.md)

## Status

MVP sedang dalam tahap implementasi dan verifikasi. Fondasi pelaporan publik, pelacakan, komunikasi anonim, dashboard, pengelolaan laporan, data KUPVA, serta manajemen Admin telah tersedia.
