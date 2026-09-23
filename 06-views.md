# 06 — Blade Views

> **Cara pakai:** Lampirkan `00-project-overview.md` + `01-ui-design-color-palette.md` + hasil controller dari tahap 05. Ini tahap terakhir, kerjakan per-modul (jangan minta semua view sekaligus dalam 1 prompt kalau contextnya besar — lihat saran pemisahan di bawah).

## Prompt Umum untuk AI Assistant

```
Berdasarkan controller & route yang sudah ada, dan design system (color palette, layout,
komponen) di `01-ui-design-color-palette.md`, buatkan Blade view untuk modul berikut: [SEBUTKAN
NAMA MODUL, lihat daftar pemisahan di bawah].

KETENTUAN UMUM SEMUA VIEW:
1. Gunakan layout `layouts.app` yang sudah dibuat di tahap desain, jangan buat layout baru.
2. Gunakan komponen Blade yang sudah ada (button, badge status, card, alert, table wrapper).
   Badge status WAJIB pakai warna sesuai token di color palette (success/warning/danger/info)
   sesuai nilai enum status (pending, approved, rejected, work_in_process, dst).
3. Semua tabel data (index/list) WAJIB pakai komponen `<x-data-table>` (DataTables server-side)
   yang sudah dibuat di tahap 01 — bukan pagination manual Laravel. Arahkan `url` komponen ke
   endpoint `data()` milik controller terkait (lihat `05-controllers.md`). Definisikan kolom yang
   ditampilkan sesuai kebutuhan tiap modul (contoh: tabel material → nama_material, satuan,
   stok_sistem, stok_wip, aksi).
4. Semua form pakai `@csrf`, tampilkan error validasi per-field dari `$errors` Laravel, dan
   pertahankan old input dengan `old()`.
5. Tampilkan flash message sukses/error dari session di layout (`session('success')`,
   `session('error')`).
6. Untuk elemen yang butuh interaktivitas ringan (misal: dynamic form tambah baris detail
   BOM/PO, konfirmasi sebelum approve/reject/hapus, buka/tutup modal), gunakan **jQuery**
   (sudah ter-install untuk DataTables) — **jangan pakai Alpine.js**. Taruh script di
   `resources/js/app.js` atau file JS terpisah per modul yang di-import ke `app.js`, jangan
   inline `<script>` besar di tengah Blade.
7. Halaman khusus per-role hanya tampil di sidebar/menu untuk role yang berhak (cek lagi
   `00-project-overview.md` bagian 4).
```

## Saran Pemisahan Prompt per Modul (supaya tidak kena limit context)
Kerjakan satu per satu, ganti bagian `[SEBUTKAN NAMA MODUL]` di atas dengan salah satu berikut:

1. **Auth & Dashboard** — `login.blade.php`, `dashboard/manajer.blade.php`,
   `dashboard/admin.blade.php`, `dashboard/staff.blade.php` (masing-masing dashboard beda card
   ringkasan sesuai role)
2. **Master Data: User & Material** — index/create/edit untuk `users`, `materials` (termasuk
   upload & preview foto material)
3. **Master Data: Produk & BOM** — index/create/edit `produk`, dengan form nested untuk
   tambah/hapus baris detail BOM secara dinamis (jQuery: tombol "+ Tambah Material" meng-clone
   baris template & re-index nama input)
4. **Permintaan Produksi** — index (list dengan filter status), create (form manajer),
   halaman detail yang menampilkan tombol aksi berbeda tergantung status & role yang login
   (admin: tombol "Proses", staff: tombol "Mulai Produksi" / "Selesaikan Produksi")
5. **Purchase Order** — index, create (form admin, bisa tambah banyak baris material),
   halaman detail dengan tombol Approve/Reject (manajer, modal alasan penolakan saat reject),
   form terima material (staff, per baris ada input jumlah diterima + jumlah cacat + keterangan)
6. **Stok Opname** — index (riwayat opname), create (pilih material, input stok fisik, tampilkan
   otomatis selisih sebelum submit via event `input`/`change` jQuery)
7. **Laporan** — halaman filter (jenis laporan, rentang tanggal), tabel hasil, tombol "Cetak PDF"

## Contoh Prompt Spesifik (modul Purchase Order)
```
Buatkan Blade view untuk modul Purchase Order:
- resources/views/purchase-order/index.blade.php — tabel PO dengan kolom nomor_po, tanggal_po,
  status (badge warna), jumlah item, aksi (lihat detail). Filter status via dropdown di atas tabel.
- resources/views/purchase-order/create.blade.php — form admin: pilih material (dropdown/search),
  jumlah, bisa tambah baris lagi (jQuery: clone baris template), submit ke route purchase-order.store.
- resources/views/purchase-order/show.blade.php — detail PO + tabel detail material yang diajukan.
  Jika status "diajukan" dan user login = manajer: tampilkan tombol Approve & Reject (Reject
  membuka modal wajib isi catatan_penolakan). Jika status "approved" dan user login = staff:
  tampilkan form "Terima Material" per baris (jumlah_diterima, jumlah_cacat, keterangan_cacat).
Ikuti pola komponen & warna dari 01-ui-design-color-palette.md.
```

## Checklist Akhir Sebelum Sidang/Demo
- [ ] Semua role bisa login dan diarahkan ke dashboard masing-masing
- [ ] Alur end-to-end (permintaan produksi → proses → PO jika perlu → approve → produksi → selesai) bisa dijalankan tanpa error dari UI
- [ ] Badge status konsisten warnanya di semua halaman
- [ ] Laporan bisa difilter dan dicetak ke PDF
- [ ] Validasi form menampilkan pesan error yang jelas (bahasa Indonesia)
