# Product Requirements Document

## TAMBORA

**Tracking Aktivitas Money Changer Bermasalah, Observasi, dan Reporting App**  
**Domain:** `laporkupva.id`  
**Wilayah awal:** Provinsi Nusa Tenggara Barat  
**Versi:** 1.1  
**Status:** Draft produk  
**Tanggal:** 24 September 2026

---

## 1. Ringkasan

TAMBORA adalah aplikasi pelaporan anonim untuk membantu masyarakat melaporkan dugaan pelanggaran atau aktivitas bermasalah pada Kegiatan Usaha Penukaran Valuta Asing Bukan Bank (KUPVA BB) atau money changer di Provinsi Nusa Tenggara Barat.

Aplikasi mempunyai dua bagian:

1. **Portal masyarakat** untuk membuat laporan anonim dan memeriksa progres.
2. **Panel Admin** untuk mengelola laporan, lokasi, status, data KUPVA, dan hasil penanganan.

Panel Admin menggunakan satu dashboard utama yang modern, elegan, dan rapi. Dashboard tersebut juga harus langsung layak di-screenshot untuk kebutuhan presentasi. Tidak ada mode presentasi atau dashboard terpisah.

Semua laporan yang berhasil dikirim akan diproses. MVP tidak menyediakan status ditolak, tidak valid, atau duplikat.

---

## 2. Tujuan Produk

- Memudahkan masyarakat melapor tanpa memberikan identitas.
- Membantu masyarakat menentukan lokasi kejadian secara akurat.
- Memberikan kode pelacakan agar progres laporan dapat diperiksa.
- Menyatukan laporan, bukti, lokasi, status, koordinasi, dan hasil penanganan.
- Membantu Admin mengelola laporan melalui antarmuka yang sederhana.
- Menyediakan dashboard dengan peta dan visual data yang siap digunakan saat presentasi.

---

## 3. Keputusan yang Sudah Disepakati

| Area | Keputusan |
|---|---|
| Nama aplikasi | TAMBORA |
| Domain | `laporkupva.id` |
| Cakupan awal | Provinsi Nusa Tenggara Barat |
| Branding | Logo Bank Indonesia dan logo TAMBORA |
| Pelapor | Anonim dan tanpa akun |
| Data identitas | Nama, NIK, email, dan nomor telepon tidak diminta |
| Pelacakan | Kode laporan dan PIN rahasia |
| Pemrosesan | Semua laporan yang berhasil dikirim diproses |
| Pengelola | Super Admin dan Admin |
| Pembuatan Admin | Hanya Super Admin |
| Framework | Laravel |
| Panel internal | Filament dengan UI khusus TAMBORA |
| Wilayah peta | NTB |

---

## 4. Pengguna

### 4.1 Pelapor anonim

Masyarakat yang melihat, mengetahui, atau mengalami kejadian terkait KUPVA atau money changer bermasalah.

Pelapor dapat:

- Membuat laporan tanpa login.
- Menentukan lokasi kejadian.
- Mengunggah bukti.
- Mendapatkan kode laporan dan PIN.
- Memeriksa progres laporan.
- Menjawab pertanyaan Admin secara anonim.

### 4.2 Admin

Admin mengelola kegiatan operasional laporan.

Admin dapat:

- Melihat dashboard.
- Melihat dan mencari laporan.
- Membuka detail laporan.
- Memperbarui status.
- Mencatat koordinasi dengan APH.
- Mencatat kunjungan lapangan atau penertiban.
- Menambahkan hasil penanganan.
- Mengirim pertanyaan anonim kepada pelapor.
- Mengelola data KUPVA.

Admin tidak dapat membuat akun Admin lain.

### 4.3 Super Admin

Super Admin memiliki seluruh kemampuan Admin dan dapat:

- Membuat akun Admin.
- Mengubah atau menonaktifkan akun Admin.
- Mengoreksi perubahan status yang keliru.
- Mengakses seluruh histori aktivitas.

---

## 5. Portal Masyarakat

### 5.1 Halaman

Portal publik hanya mempunyai halaman utama berikut:

| URL | Halaman |
|---|---|
| `/` | Beranda |
| `/lapor` | Buat laporan anonim |
| `/status` | Cek status laporan |
| `/panduan` | Panduan dan FAQ |
| `/privasi` | Informasi privasi |

Halaman bukti pengiriman dan detail progres merupakan bagian dari alur laporan, bukan menu utama.

### 5.2 Beranda

Beranda menampilkan:

- Logo Bank Indonesia dan TAMBORA.
- Penjelasan singkat mengenai layanan.
- Tombol **Buat Laporan Anonim**.
- Tombol **Cek Status Laporan**.
- Penjelasan bahwa identitas pelapor tidak diminta.
- Ringkasan cara kerja dalam tiga langkah.

Bahasa yang digunakan harus mudah dipahami. Istilah KUPVA selalu disertai penjelasan **tempat penukaran valuta asing atau money changer**.

---

## 6. Alur Membuat Laporan

Form dibuat sebagai wizard agar pengguna hanya melihat satu kelompok pertanyaan pada setiap langkah.

### Langkah 1: Jenis kejadian

Pilihan awal:

- Diduga tidak memiliki izin.
- Kurs atau biaya tidak transparan.
- Bukti transaksi tidak diberikan.
- Aktivitas transaksi mencurigakan.
- Lokasi usaha tidak sesuai.
- Pelanggaran atau masalah lainnya.

### Langkah 2: Lokasi kejadian

Pelapor memilih salah satu:

1. **Gunakan lokasi saya sekarang**
2. **Cari lokasi kejadian**

Jika menggunakan lokasi sekarang:

- Browser meminta izin GPS.
- Sistem menampilkan titik di peta.
- Pelapor dapat menggeser pin agar lebih akurat.

Jika mencari lokasi:

- Pelapor dapat mencari kabupaten/kota, kecamatan, desa, alamat, nama tempat, atau patokan di NTB.
- Sistem menampilkan hasil pencarian pada peta.
- Pelapor memilih hasil dan menggeser pin ke titik yang tepat.

Field lokasi:

- Kabupaten/kota.
- Kecamatan.
- Desa/kelurahan.
- Alamat atau patokan.
- Latitude dan longitude.
- Tingkat akurasi jika menggunakan GPS.

Lokasi yang disimpan adalah **lokasi kejadian atau usaha terlapor**, bukan lokasi pelapor.

### Langkah 3: Detail kejadian

- Nama KUPVA atau nama usaha jika diketahui.
- Tanggal kejadian.
- Perkiraan waktu, opsional.
- Cerita atau deskripsi kejadian.
- Informasi apakah kegiatan masih berlangsung.

### Langkah 4: Bukti

Pelapor dapat mengunggah:

- Foto JPG, PNG, atau WebP.
- Dokumen PDF.
- Maksimal lima berkas.
- Maksimal 10 MB per berkas.

Bukti bersifat opsional agar masyarakat tetap dapat melapor dalam kondisi yang tidak aman untuk mengambil foto.

### Langkah 5: Periksa dan kirim

Sistem menampilkan ringkasan:

- Jenis kejadian.
- Lokasi dan peta.
- Detail kejadian.
- Bukti yang akan dikirim.

Pelapor menyetujui pernyataan itikad baik, kemudian memilih **Kirim Laporan Anonim**.

### Bukti pengiriman

Setelah berhasil, sistem menampilkan:

- Kode laporan acak.
- PIN rahasia enam digit.
- QR code untuk membuka halaman pelacakan.
- Tanggal pengiriman.
- Tombol simpan bukti sebagai gambar atau PDF.

Pelapor harus diberi peringatan untuk menyimpan kode dan PIN karena sistem tidak mempunyai data identitas untuk melakukan pemulihan.

---

## 7. Status Laporan

| Urutan | Status publik | Keterangan |
|---:|---|---|
| 1 | Laporan dikirim | Laporan berhasil tersimpan |
| 2 | Laporan diterima | Admin mulai memproses laporan |
| 3 | Koordinasi dengan APH | Laporan dikoordinasikan untuk tindak lanjut |
| 4 | Kunjungan lapangan / penertiban | Tindakan lapangan dilakukan |
| 5 | Laporan hasil | Ringkasan hasil penanganan tersedia |
| 6 | Selesai | Proses laporan ditutup |

Aturan status:

- Status pertama dibuat otomatis.
- Admin memperbarui status secara berurutan.
- Setiap perubahan mencatat waktu dan Admin yang melakukan perubahan.
- Admin tidak dapat mengembalikan status ke tahap sebelumnya.
- Super Admin dapat melakukan koreksi dengan alasan wajib.
- Riwayat lama tidak boleh dihapus.
- Tidak ada status ditolak, tidak valid, atau duplikat pada MVP.

---

## 8. Cek Status dan Komunikasi Anonim

Pelapor memasukkan:

- Kode laporan.
- PIN rahasia.

Setelah berhasil, sistem menampilkan:

- Timeline enam status.
- Status aktif.
- Tanggal pembaruan terakhir.
- Pembaruan yang ditulis Admin untuk pelapor.
- Ringkasan hasil penanganan.
- Kotak komunikasi anonim.

Admin dapat meminta informasi tambahan melalui kotak komunikasi. Pelapor dapat menjawab tanpa memberikan nama atau kontak.

Informasi berikut tidak boleh tampil kepada pelapor:

- Catatan internal.
- Nama petugas.
- Dokumen internal.
- Jadwal operasi yang belum dilaksanakan.
- Strategi koordinasi atau penertiban.

---

## 9. Panel Admin

### 9.1 Menu utama

Panel dibuat sederhana dengan menu:

1. **Dashboard**
2. **Laporan**
3. **Data KUPVA**
4. **Manajemen Admin**, hanya untuk Super Admin

Koordinasi, peta, analitik, komunikasi, histori, dan audit tidak dibuat sebagai menu terpisah. Fitur tersebut ditempatkan pada Dashboard atau Detail Laporan.

### 9.2 URL panel

| URL | Fungsi |
|---|---|
| `/admin/login` | Login |
| `/admin` | Dashboard |
| `/admin/laporan` | Daftar laporan |
| `/admin/laporan/{id}` | Detail dan penanganan laporan |
| `/admin/kupva` | Data KUPVA |
| `/admin/users` | Manajemen Admin khusus Super Admin |

### 9.3 Dashboard

Dashboard merupakan halaman utama untuk operasional sekaligus tampilan yang layak di-screenshot saat presentasi.

Bagian atas menampilkan lima KPI:

- Total laporan.
- Laporan baru.
- Sedang dikoordinasikan atau ditangani.
- Kunjungan lapangan atau penertiban.
- Selesai.

Bagian utama menampilkan:

- **Peta persebaran lokasi usaha atau pihak terlapor.**
- Distribusi status laporan.
- Tren laporan per bulan.
- Kabupaten/kota dengan laporan terbanyak.
- Daftar laporan terbaru.

Filter dashboard:

- Periode.
- Kabupaten/kota.
- Jenis kejadian.
- Status laporan.

Dashboard harus tertata baik pada resolusi laptop dan layar 1920x1080. Informasi penting harus terlihat pada tampilan pertama tanpa scroll panjang.

### 9.4 Peta dashboard

Peta mengambil titik dari lokasi yang dipilih masyarakat saat membuat laporan.

Peta tidak menampilkan lokasi pelapor. Peta hanya menampilkan lokasi kejadian atau usaha terlapor.

Kemampuan peta:

- Marker dikelompokkan saat tampilan peta masih jauh.
- Warna marker mengikuti status laporan.
- Filter mengikuti filter dashboard.
- Klik marker menampilkan kode laporan, jenis kejadian, wilayah, tanggal, dan status.
- Klik ringkasan membuka Detail Laporan.
- Peta otomatis menyesuaikan area NTB.

Istilah yang digunakan pada UI adalah **lokasi terlapor** atau **lokasi kejadian**, bukan tersangka, karena laporan masyarakat masih memerlukan proses penanganan.

### 9.5 Daftar laporan

Kolom utama:

- Kode laporan.
- Tanggal laporan.
- Jenis kejadian.
- Nama usaha jika diketahui.
- Kabupaten/kota.
- Status.
- Pembaruan terakhir.

Kemampuan:

- Pencarian.
- Filter status, wilayah, jenis, dan periode.
- Pengurutan.
- Pagination.
- Ekspor Excel atau PDF.
- Buka detail laporan.

Tidak ada tombol hapus laporan.

### 9.6 Detail laporan

Detail laporan memuat semua proses dalam satu halaman:

- Ringkasan laporan.
- Peta lokasi terlapor.
- Bukti dari pelapor.
- Timeline status.
- Tombol pembaruan status.
- Catatan internal.
- Pembaruan publik.
- Komunikasi anonim.
- Catatan koordinasi dengan APH.
- Catatan kunjungan lapangan atau penertiban.
- Ringkasan hasil.
- Histori aktivitas.

Tampilan desktop menggunakan dua kolom:

- Kolom kiri untuk laporan, lokasi, dan bukti.
- Kolom kanan untuk status, tindakan, komunikasi, dan histori.

Catatan internal dan pembaruan publik harus mempunyai label serta warna yang berbeda agar tidak tertukar.

### 9.7 Data KUPVA

Data minimum:

- Nama KUPVA atau usaha.
- Nomor izin jika tersedia.
- Status izin.
- Alamat.
- Kabupaten/kota.
- Kecamatan.
- Desa/kelurahan.
- Titik lokasi.
- Masa berlaku izin jika tersedia.

Admin dapat mencari, menambah, mengubah, mengimpor, dan mengekspor data KUPVA. Data tidak dihapus permanen, tetapi dapat dinonaktifkan.

### 9.8 Manajemen Admin

Hanya Super Admin yang dapat:

- Membuat Admin.
- Mengubah nama dan email Admin.
- Menonaktifkan Admin.
- Mengatur ulang akses Admin.

Admin biasa tidak melihat menu ini dan tidak dapat mengakses URL secara langsung.

---

## 10. Hak Akses

| Kemampuan | Admin | Super Admin |
|---|---:|---:|
| Melihat dashboard | Ya | Ya |
| Mengelola laporan | Ya | Ya |
| Memperbarui status | Ya | Ya |
| Menulis catatan internal | Ya | Ya |
| Menulis pembaruan publik | Ya | Ya |
| Mengelola komunikasi anonim | Ya | Ya |
| Mencatat koordinasi dan tindakan | Ya | Ya |
| Mengelola data KUPVA | Ya | Ya |
| Ekspor data | Ya | Ya |
| Membuat atau menonaktifkan Admin | Tidak | Ya |
| Mengoreksi status ke tahap sebelumnya | Tidak | Ya, dengan alasan |
| Melihat histori sistem lengkap | Tidak | Ya |

---

## 11. UI/UX

### 11.1 Portal masyarakat

- Mobile-first.
- Bahasa sederhana.
- Satu tindakan utama pada setiap layar.
- Ukuran tombol mudah ditekan.
- Progress langkah selalu terlihat.
- Data form tidak hilang ketika pengguna kembali ke langkah sebelumnya.
- Validasi menjelaskan kesalahan dan cara memperbaikinya.
- Tidak menggunakan istilah teknis tanpa penjelasan.

### 11.2 Panel Admin

- Sidebar navy yang ringkas dan dapat diciutkan.
- Latar belakang abu kebiruan yang lembut.
- Kartu putih dengan border dan bayangan ringan.
- Logo Bank Indonesia dan TAMBORA pada area header/sidebar.
- Tipografi Plus Jakarta Sans atau Inter.
- Ikon menggunakan satu gaya.
- Warna status konsisten pada kartu, tabel, timeline, dan peta.
- Animasi ringan dan tidak menghambat pekerjaan.
- Dashboard terlihat profesional tanpa terlalu banyak kartu atau grafik.

### 11.3 Warna status

| Status | Warna |
|---|---|
| Laporan dikirim | Biru |
| Laporan diterima | Biru tua |
| Koordinasi dengan APH | Ungu |
| Kunjungan/penertiban | Oranye |
| Laporan hasil | Teal |
| Selesai | Hijau |

---

## 12. Privasi dan Keamanan

### 12.1 Anonimitas

- Tidak meminta nama, NIK, email, atau nomor telepon.
- Tidak membuat akun masyarakat.
- Lokasi GPS hanya diminta setelah persetujuan pengguna.
- Lokasi yang disimpan adalah titik kejadian yang sudah dikonfirmasi.
- Alamat IP tidak disimpan sebagai bagian dari data laporan.
- Metadata EXIF foto dihapus.
- Tidak menggunakan fingerprinting perangkat.

### 12.2 Keamanan aplikasi

- HTTPS wajib.
- Validasi dilakukan di server.
- Rate limiting pada pengiriman laporan dan cek status.
- Perlindungan brute force kode dan PIN.
- PIN disimpan dalam bentuk hash.
- Kode laporan tidak menggunakan nomor berurutan.
- Validasi tipe dan ukuran berkas.
- Pemindaian berkas berbahaya.
- Hak akses diterapkan melalui policy Laravel.
- Setiap perubahan status dan data penting masuk histori aktivitas.
- Backup database dan berkas dilakukan secara berkala.

---

## 13. Data Inti

Entitas utama:

- Admin.
- Laporan.
- Lokasi laporan.
- Bukti laporan.
- Histori status.
- Catatan internal.
- Pembaruan publik.
- Pesan anonim.
- Catatan koordinasi.
- Tindakan lapangan.
- Hasil penanganan.
- Data KUPVA.
- Histori aktivitas.

Data laporan minimum:

- Kode internal.
- Kode publik.
- Hash PIN.
- Jenis kejadian.
- Deskripsi kejadian.
- Nama usaha jika diketahui.
- Tanggal kejadian.
- Lokasi administratif.
- Latitude dan longitude.
- Status saat ini.
- Waktu dibuat dan diperbarui.

---

## 14. Kebutuhan Fungsional Utama

| ID | Kebutuhan |
|---|---|
| FR-01 | Masyarakat dapat melapor tanpa login |
| FR-02 | Sistem tidak meminta identitas pelapor |
| FR-03 | Pelapor dapat menggunakan GPS atau mencari lokasi |
| FR-04 | Pelapor dapat menggeser pin lokasi |
| FR-05 | Pelapor dapat mengunggah bukti |
| FR-06 | Sistem menghasilkan kode laporan, PIN, dan QR code |
| FR-07 | Pelapor dapat melihat enam status laporan |
| FR-08 | Pelapor dan Admin dapat berkomunikasi secara anonim |
| FR-09 | Admin dapat melihat laporan pada tabel dan peta |
| FR-10 | Admin dapat memperbarui status secara berurutan |
| FR-11 | Admin dapat mencatat koordinasi, tindakan, dan hasil |
| FR-12 | Dashboard menampilkan KPI, peta, status, dan tren |
| FR-13 | Admin dapat mengelola data KUPVA |
| FR-14 | Hanya Super Admin yang dapat mengelola akun Admin |
| FR-15 | Sistem mencatat histori aktivitas penting |
| FR-16 | Laporan tidak dapat dihapus melalui panel |

---

## 15. Kebutuhan Nonfungsional

- Portal dapat digunakan mulai lebar layar 360 px.
- Panel Admin optimal pada laptop dan desktop.
- Dashboard tertata baik pada resolusi 1920x1080.
- Halaman publik utama ditargetkan dimuat kurang dari 2,5 detik pada koneksi seluler yang wajar.
- Dashboard ditargetkan dimuat kurang dari 4 detik pada jumlah data normal.
- Kontras warna minimal mengikuti WCAG AA.
- Teks portal publik minimum 16 px.
- Target sentuh minimum 44x44 px.
- Mendukung browser modern Chrome, Edge, Firefox, dan Safari.
- Sistem menggunakan HTTPS dan backup otomatis.

---

## 16. Rekomendasi Teknologi

- Laravel versi stabil saat implementasi dimulai.
- Blade dan Livewire untuk portal masyarakat.
- Tailwind CSS untuk design system.
- Filament untuk panel Admin.
- Custom Filament widgets untuk dashboard dan peta.
- PostgreSQL sebagai database.
- Leaflet untuk peta.
- Object storage S3-compatible untuk bukti.
- Queue untuk kompresi gambar, penghapusan EXIF, dan proses berkas.

MVP dibangun sebagai satu aplikasi Laravel agar pengembangan, pengujian, dan deployment lebih cepat.

---

## 17. Kriteria Penerimaan

### Pelaporan

- Laporan dapat dikirim tanpa akun dan identitas.
- Lokasi dapat dipilih melalui GPS atau pencarian.
- Pin dapat digeser sebelum laporan dikirim.
- Sistem menghasilkan kode, PIN, dan QR code.
- Bukti pengiriman dapat disimpan.

### Pelacakan

- Kode dan PIN yang benar membuka progres laporan.
- Timeline menampilkan enam status.
- Pelapor dapat membaca pembaruan dan menjawab pertanyaan anonim.
- Informasi internal tidak terlihat oleh pelapor.

### Admin

- Dashboard menampilkan KPI dan peta lokasi terlapor.
- Marker peta berasal dari titik yang dipilih pada laporan masyarakat.
- Admin dapat mencari, memfilter, dan membuka laporan.
- Semua proses laporan tersedia pada satu halaman detail.
- Status hanya dapat diperbarui secara berurutan.
- Laporan tidak dapat dihapus.
- Admin tidak dapat membuat Admin lain.

### UI

- Dashboard tidak membutuhkan halaman atau mode khusus untuk presentasi.
- Tampilan dashboard sudah rapi untuk di-screenshot.
- Informasi penting terlihat tanpa scroll panjang.
- Warna status konsisten.
- Tampilan tidak terlihat seperti tema Filament bawaan.

---

## 18. Di Luar MVP

- Peluncuran di luar NTB.
- Akun untuk masyarakat.
- Akun untuk APH atau Pemda.
- Integrasi otomatis dengan sistem eksternal.
- Status ditolak, tidak valid, atau duplikat.
- Deteksi duplikat otomatis.
- AI dan predictive analytics.
- Notifikasi WhatsApp, SMS, atau email kepada pelapor.
- Dashboard atau mode presentasi terpisah.

---

## 19. Hal yang Ditetapkan Sebelum Produksi

1. File resmi logo Bank Indonesia dan TAMBORA.
2. Pedoman penggunaan logo dan warna.
3. Sumber data awal KUPVA NTB.
4. Penyedia tile peta dan pencarian lokasi.
5. Target waktu penanganan setiap status.
6. Masa penyimpanan laporan dan bukti.
7. Batas informasi hasil yang boleh ditampilkan kepada pelapor.
8. Infrastruktur hosting produksi.

---

## 20. Referensi

- `Dukungan Pendukung_PUNGGAWA_Mtr.pptx`
- `08. CP Probis_Action Plan_Punggawa - rev.pptx`
- <https://github.com/RikiSanjayaa/monitoring-ppa>
- <https://laporkupva.id>

