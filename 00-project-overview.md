# 00 — Project Overview (Konteks Utama)

> **Cara pakai:** Lampirkan file ini di SETIAP sesi chat dengan AI coding assistant (Claude Code, Cursor, Copilot Chat, dll), bersama satu file tahap lain (01, 02, 03, dst). File ini adalah "memory" proyek supaya AI selalu paham konteks bisnisnya walau kamu kerja bertahap.

## 1. Nama Proyek
**Sistem Informasi Pengelolaan Material & Purchase Order berbasis Web (Studi Kasus: Perusahaan Mebel)**

## 2. Latar Belakang Singkat
Perusahaan mebel masih mengelola material, stok, dan purchase order (PO) secara manual/kertas. Hal ini menyebabkan: data stok tidak realtime, pencarian data lambat, risiko data hilang/rusak/duplikat, dan proses pengadaan material lambat. Sistem ini dibangun untuk menjadikan proses tersebut terkomputerisasi dan terpusat.

## 3. Tech Stack yang Digunakan
- **Backend**: Laravel 11.x (PHP 8.2+)
- **Database**: MySQL
- **Frontend**: Blade Templating + Tailwind CSS (via Vite, bukan CDN) + Alpine.js untuk
  interaktivitas ringan. **Tidak pakai** Livewire, React, Vue, atau SPA framework apa pun —
  murni Blade multi-page seperti Laravel klasik.
- **Auth**: Laravel built-in Auth (Breeze-style, tapi dibuat manual sesuai kebutuhan role)
- **Export PDF**: `barryvdh/laravel-dompdf`
- **Icon**: Heroicons / Lucide (via CDN atau blade component)
- **Data Table**: `yajra/laravel-datatables-oracle` (server-side processing: search, sort,
  pagination otomatis dari backend) dipasangkan dengan **DataTables.net** di frontend. Dipakai
  di SEMUA tabel index/list (dashboard, material, produk, permintaan produksi, PO, stok opname,
  laporan). Catatan: DataTables.net butuh jQuery — ini **satu-satunya** tempat jQuery dipakai di
  project ini, di luar itu tetap pakai Alpine.js untuk interaktivitas lain.

> Jika kamu (AI assistant) menerima file ini, ikuti stack di atas kecuali user secara eksplisit meminta yang lain.

## 4. Aktor & Hak Akses (Role)
| Role | Deskripsi Hak Akses |
|---|---|
| **Manajer** | Login, membuat permintaan produksi (produk + jumlah unit), approve/reject Purchase Order, melihat dashboard & laporan |
| **Admin** | Login, kelola data user, kelola data material, kelola data produk & BOM, memproses permintaan produksi (hitung kebutuhan material via BOM, cek stok), mengajukan PO ke manajer, melihat dashboard |
| **Staff Workshop** | Login, memproses produksi (ambil material dari stok, update status ke WIP), menerima material dari PO (termasuk lapor material cacat), menyelesaikan produksi, melakukan stok opname |

## 5. Modul / Fitur Utama (Product Backlog)
1. Login & hak akses berbasis role
2. Manajemen data material (CRUD + foto)
3. Manajemen data produk & Bill of Material (BOM)
4. Permintaan produksi (dibuat manajer)
5. Proses permintaan produksi oleh admin (hitung kebutuhan material dari BOM, cek stok otomatis)
6. Pengajuan Purchase Order (jika stok kurang)
7. Approval / Reject Purchase Order oleh manajer (dengan catatan alasan penolakan)
8. Proses produksi oleh staff workshop (alokasi stok ke WIP)
9. Penerimaan material PO oleh staff (termasuk pelaporan material cacat)
10. Penyelesaian produksi (update status selesai, kurangi stok WIP)
11. Stok opname (cocokkan stok fisik vs sistem)
12. Dashboard ringkasan (material, stok, PO)
13. Laporan (material, stok, stok opname, PO) — bisa dicetak ke PDF

## 6. Entitas Data Utama (ringkas — detail lengkap ada di `02-database-schema.md`)
`users`, `produk`, `bom`, `detail_bom`, `material`, `permintaan_produksi`, `purchase_order`, `detail_po`, `stok_opname`

## 7. Alur Bisnis Inti (Ringkasan End-to-End)
1. **Manajer** membuat *permintaan produksi* → pilih produk + jumlah unit.
2. **Admin** memproses permintaan itu → sistem ambil data **BOM** produk tsb, hitung total kebutuhan material, lalu bandingkan dengan **stok material** saat ini.
   - Jika stok cukup → status permintaan produksi = `pending` (siap dikerjakan staff).
   - Jika stok kurang → Admin mengajukan **Purchase Order** → status permintaan = `menunggu_po`.
3. **Manajer** approve/reject PO.
   - Approve → status PO = `approved`, status permintaan produksi = `menunggu_material`.
   - Reject → wajib isi `catatan_penolakan`, status PO = `rejected`.
4. **Staff Workshop**:
   - Jika status permintaan `pending` → mulai produksi → stok material dikurangi & dipindah ke stok **WIP**, status → `work_in_process`.
   - Jika status `menunggu_material` (PO approved) → terima material PO → cek kondisi (jika ada cacat, input material cacat) → update stok → status kembali `pending`.
   - Setelah produksi selesai → tandai selesai → stok WIP dikurangi, status → `selesai`.
5. **Staff Workshop** juga bisa melakukan **stok opname** kapan saja (input stok fisik, sistem hitung selisih, update stok bila beda).
6. **Manajer/Admin** bisa melihat **dashboard** & **laporan** (bisa dicetak PDF).

## 8. Status Enum Penting
- `permintaan_produksi.status_permintaan`: `pending`, `menunggu_po`, `menunggu_material`, `work_in_process`, `material_po_cacat`, `selesai`
- `purchase_order.status_po`: `diajukan`, `approved`, `rejected`

## 9. Catatan untuk AI Assistant
- Selalu buat kode yang **konsisten dengan penamaan tabel/kolom** di `02-database-schema.md` begitu file itu dilampirkan.
- Gunakan **service class / form request** untuk logika bisnis kompleks (hitung kebutuhan material, cek stok) — jangan taruh semua logic di controller.
- Ikuti **best practice Laravel** (Eloquent relationship, validation via Form Request, route model binding, policy/middleware untuk role).
- Jika ada bagian yang ambigu, buat asumsi yang masuk akal dan sebutkan asumsinya secara singkat, jangan berhenti bertanya kecuali benar-benar krusial.
