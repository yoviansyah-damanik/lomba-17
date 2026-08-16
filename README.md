# Penilaian Lomba

Aplikasi web mobile-friendly untuk mengelola penilaian lomba antar sekolah (SD/SMP/SMA) — mulai dari pendaftaran peserta, penugasan juri per kriteria, proses penilaian oleh juri, hingga papan peringkat live dan rekap nilai. Dibangun untuk **Kwarcab Kota Padangsidimpuan**.

## Modul Aplikasi

### Dashboard
Ringkasan aktivitas untuk admin (jumlah peserta per jenjang, tren penilaian masuk 7 hari terakhir, progres tiap lomba dan tiap juri) dan untuk juri (daftar kriteria miliknya beserta progres penilaian).

### Lomba & Kriteria (Admin)
- CRUD lomba: nama, jadwal mulai/selesai (format 24 jam), status Live Rank (buka/tutup) dan opsi sembunyikan identitas peserta di papan peringkat.
- CRUD kriteria per lomba, dengan **jenjang sekolah** yang bisa dipilih per kriteria (SD/SMP/SMA) dan **penugasan juri khusus per jenjang** — satu kriteria bisa dinilai juri berbeda untuk SD, SMP, dan SMA.
- **Peserta Lomba**: pendaftaran peserta ke lomba tanpa harus tahu identitas sekolah di awal — admin bisa membuat slot massal per jenjang (NPP & nama sementara dibuat otomatis), lalu menyinkronkan identitas sekolah asli kapan saja. Tersedia juga jalur pendaftaran langsung dari direktori Peserta yang identitasnya sudah diketahui.

### Peserta (Admin)
Direktori sekolah peserta (nama sekolah + jenjang), lengkap dengan riwayat keikutsertaan di tiap lomba beserta peringkat yang pernah diraih.

### Juri (Admin)
Manajemen akun juri beserta riwayat penugasan kriteria dan progres penilaian yang sudah dimasukkan.

### Penilaian (Juri)
Alur kerja juri di lapangan: cari peserta berdasarkan NPP + jenjang pada lomba yang sedang berlangsung, isi nilai (0–31) per kriteria yang menjadi tanggung jawabnya, dengan:
- Input nilai bertombol +/- (mudah disentuh di HP) selain diketik langsung.
- **Catatan bersuara (speech-to-text)** realtime per kriteria, dengan tombol untuk mengosongkan catatan.
- Modal konfirmasi ringkasan nilai sebelum benar-benar tersimpan.
- Form otomatis kembali ke mode pencarian setelah tersimpan, siap untuk peserta berikutnya.

### Live Rank
Papan peringkat publik (tanpa login) per lomba, diperbarui otomatis (`wire:poll`), dengan filter jenjang dan opsi identitas disamarkan. Skor yang belum dinilai lengkap oleh seluruh juri ditandai badge **"Sementara"**.

### Rekap
Tabel rekap nilai per lomba (skor per kriteria + total, status kelengkapan penilaian, catatan juri) dengan filter jenjang, cetak langsung dari browser, dan unduh PDF.

## Tech Stack

- **Laravel 13** + **Livewire 4** (komponen class-based, modular per modul)
- **Tailwind CSS** — desain mobile-first, mendukung mode gelap
- **MySQL/MariaDB**
- **barryvdh/laravel-dompdf** — ekspor PDF Rekap
- **Web Speech API** — catatan penilaian via suara (Alpine.js)
- `wire:poll` — pembaruan Live Rank tanpa websocket

## Instalasi

```bash
composer install
npm install

cp .env.example .env
php artisan key:generate

# sesuaikan koneksi database di .env, lalu:
php artisan migrate --seed
```

## Menjalankan Aplikasi

```bash
php artisan serve
npm run dev
```

## Menjalankan Test

```bash
php artisan test
```

## Developer

**Yoviansyah Rizki Pratama**
WA: 081222778197
Email: yoviansyahrizkypratama@gmail.com
