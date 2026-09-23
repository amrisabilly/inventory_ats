# 01 — UI/UX Design System & Color Palette

> **Cara pakai:** Lampirkan bersama `00-project-overview.md`. Tahap ini menghasilkan fondasi desain (warna, tipografi, layout dasar) sebelum masuk ke database/backend.

## Prompt untuk AI Assistant

```
Kamu adalah frontend/UI designer sekaligus Laravel developer. Berdasarkan project overview
yang saya lampirkan, buatkan fondasi desain (design system) untuk aplikasi web ini.

TARGET: Aplikasi internal (dashboard-style), dipakai oleh 3 role (Manajer, Admin, Staff
Workshop) di lingkungan perusahaan mebel. Kesan yang diinginkan: profesional, terpercaya,
bersih, mudah dibaca untuk data tabel & angka (karena banyak data stok/angka).

TUGAS:
1. Setup Tailwind CSS di project Laravel menggunakan Vite (setup default Laravel, BUKAN CDN),
   karena project ini pakai Blade templating standar dan butuh custom Tailwind config yang
   persist. Pastikan `resources/css/app.css`, `vite.config.js`, dan `tailwind.config.js`
   terhubung dengan benar, lalu load via `@vite(['resources/css/app.css', 'resources/js/app.js'])`
   di layout.
2. Implementasikan color palette di bawah sebagai custom Tailwind theme (tailwind.config.js)
   dengan nama token semantik (primary, secondary, accent, success, warning, danger, surface,
   dst), BUKAN cuma warna mentah, supaya konsisten dipakai di semua view nanti.
3. Buat layout dasar Blade: `layouts/app.blade.php` (untuk halaman setelah login, ada sidebar +
   topbar) dan `layouts/guest.blade.php` (untuk halaman login).
4. Buat komponen Blade reusable dasar: button (primary/secondary/danger), badge/status pill
   (untuk status permintaan produksi & PO), card, alert/notifikasi, table wrapper.
5. Sidebar navigasi menyesuaikan role yang login (gunakan @can atau cek role langsung).
6. Install & setup **DataTables** untuk semua tabel (lihat detail di bawah), termasuk custom
   styling agar tampilannya menyatu dengan color palette & Tailwind, bukan tampilan default
   Bootstrap-nya DataTables.
```

## Setup DataTables (untuk semua tabel index/list)

```
Install package backend & frontend berikut:

composer require yajra/laravel-datatables-oracle
npm install jquery datatables.net datatables.net-dt

Lalu:
1. Publish config: php artisan vendor:publish --tag=datatables
2. Import jQuery & datatables.net di resources/js/app.js:
   import $ from 'jquery';
   window.$ = window.jQuery = $;
   import 'datatables.net-dt';
3. Import CSS default datatables.net-dt di resources/css/app.css, lalu override warna-warna
   defaultnya (header, border, pagination button, search box) supaya konsisten dengan token
   Tailwind di tailwind.config.js (primary, border, surface, dst) — jangan biarkan tampilan
   bawaan Bootstrap-nya DataTables.net.
4. Buat 1 Blade component `<x-data-table>` reusable yang menerima: id tabel, url endpoint ajax,
   dan definisi kolom (array), lalu otomatis inisialisasi DataTables dengan opsi:
   - serverSide: true, processing: true
   - language: teks bahasa Indonesia (search: "Cari...", pagination, dst)
   - pageLength default 10, dengan opsi ganti ke 25/50/100
5. Style tombol aksi di dalam tabel (Lihat/Edit/Hapus) tetap pakai komponen button yang sudah
   dibuat, jangan style manual di JS.
```


## Color Palette yang Direkomendasikan

Tema: **"Industrial Trust"** — biru navy sebagai warna kepercayaan/korporat (cocok untuk sistem approval & data), dipadu abu netral untuk data-heavy UI, aksen warna status yang jelas (karena sistem ini sarat status: pending/approved/rejected/cacat/dll).

| Token | Hex | Kegunaan |
|---|---|---|
| `primary` | `#1E3A5F` (navy) | Warna utama brand: sidebar, tombol utama, header |
| `primary-light` | `#2C5282` | Hover state, link aktif |
| `secondary` | `#8C6A4E` (warm wood brown) | Aksen "mebel/kayu", dipakai terbatas (ikon, highlight, badge kategori produk) |
| `surface` | `#F7F8FA` | Background halaman |
| `surface-card` | `#FFFFFF` | Background card/table |
| `border` | `#E2E5E9` | Border tabel, divider |
| `text-primary` | `#1A202C` | Teks utama |
| `text-secondary` | `#64748B` | Teks sekunder/label |
| `success` | `#2F855A` | Status: approved, stok cukup, selesai |
| `warning` | `#C05621` | Status: pending, menunggu PO/material |
| `danger` | `#C53030` | Status: rejected, stok kritis, material cacat |
| `info` | `#2B6CB0` | Status: WIP / dalam proses |

**Alasan pemilihan:** navy + abu netral umum dipakai untuk sistem manajemen/ERP karena netral dan tidak melelahkan mata saat menatap banyak tabel data. Aksen coklat kayu (`secondary`) dipakai tipis-tipis sebagai identitas "mebel" tanpa mengganggu keterbacaan data. Warna status (success/warning/danger/info) dipilih dengan kontras cukup untuk badge status yang krusial di sistem ini (status PO, status permintaan produksi).

### Alternatif (jika ingin nuansa lebih hangat/kayu)
Jika ingin kesan "mebel" lebih kuat, tukar `primary` → `#5C4033` (coklat tua) dan `secondary` → `#1E3A5F` (navy jadi aksen). Sebutkan pilihan ini ke AI assistant jika ingin dipakai.

## Tipografi
- Font: **Inter** (Google Fonts) untuk seluruh UI — netral, sangat mudah dibaca di ukuran kecil (cocok untuk tabel data & dashboard).
- Ukuran dasar: `text-sm` (14px) untuk tabel/data, `text-base` untuk body, `text-2xl`/`text-3xl` untuk heading dashboard.

## Struktur Layout yang Diminta
- **Sidebar** kiri (collapsible di mobile): logo, menu sesuai role, user info + logout di bawah.
- **Topbar**: breadcrumb/judul halaman, notifikasi (opsional), avatar user.
- **Konten utama**: card-based, tabel dengan pagination, badge status berwarna sesuai palet di atas.
- **Dashboard**: grid card ringkasan (total material, stok kritis, PO pending, permintaan produksi aktif) + tabel aktivitas terbaru.

## Output yang Diharapkan dari AI Assistant
- `tailwind.config.js` dengan token warna di atas
- `resources/views/layouts/app.blade.php`
- `resources/views/layouts/guest.blade.php`
- `resources/views/components/` (button, badge, card, alert)
- Screenshot/deskripsi struktur sidebar per role (boleh berupa komentar/dokumentasi di kode)
