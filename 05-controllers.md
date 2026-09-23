# 05 — Controllers, Routes & Access Control

> **Cara pakai:** Lampirkan `00-project-overview.md` + hasil model/service dari tahap 04 bersama file ini.

## Prompt untuk AI Assistant

```
Berdasarkan model & service yang sudah dibuat, buatkan seluruh Controller, Form Request
(validasi), Route, dan Middleware/Policy untuk kontrol akses berbasis role.

MIDDLEWARE & AKSES:
1. Buat middleware `role` custom (manual, tanpa package tambahan seperti spatie/laravel-permission)
   berbasis kolom `role` di tabel `users`. Daftarkan sebagai alias di `bootstrap/app.php` (Laravel
   11) dengan nama `role`, dipakai seperti `->middleware('role:admin')` atau
   `->middleware('role:admin,manajer')` untuk multi-role.
2. Terapkan middleware role di route group sesuai hak akses tiap fitur (lihat tabel di
   project overview bagian 4).

CONTROLLER YANG DIBUTUHKAN (resource-style, gunakan Route::resource jika cocok):
1. `AuthController` — login, logout (redirect ke dashboard sesuai role setelah login)
2. `DashboardController` — index() menampilkan ringkasan berbeda per role (total material,
   stok kritis <10% misal, jumlah PO pending, jumlah permintaan produksi aktif)
3. `UserController` (admin only) — CRUD user
4. `MaterialController` (admin) — CRUD material, upload foto, endpoint khusus untuk lihat stok
5. `ProdukController` (admin) — CRUD produk beserta BOM & detail BOM (nested form: 1 produk
   punya 1+ BOM, 1 BOM punya banyak detail material)
6. `PermintaanProduksiController`:
   - manajer: create/store (buat permintaan)
   - admin: index (lihat semua), method khusus `proses()` yang memanggil
     `ProduksiService::prosesPermintaanProduksi()`
   - staff: method khusus `mulai()` dan `selesai()` yang memanggil method service terkait
7. `PurchaseOrderController`:
   - admin: create/store (panggil `PurchaseOrderService::buatPO()`)
   - manajer: method `approve()` dan `reject()`
   - staff: method `terimaMaterial()` untuk input penerimaan barang (termasuk cacat)
8. `StokOpnameController` (staff) — create/store (panggil `StokOpnameService::catatOpname()`)
9. `LaporanController` (manajer/admin) — index dengan filter tanggal & jenis laporan (material,
   stok, stok opname, PO), method `cetakPdf()` yang generate PDF via barryvdh/laravel-dompdf

FORM REQUEST VALIDASI:
- Buat Form Request terpisah untuk tiap store/update yang menerima input signifikan (jangan
  validasi inline di controller). Contoh: `StorePermintaanProduksiRequest`,
  `StorePurchaseOrderRequest`, `RejectPurchaseOrderRequest` (wajib field catatan_penolakan),
  `TerimaMaterialPoRequest`, `StoreStokOpnameRequest`, dll.

ROUTE:
- Kelompokkan route dalam `routes/web.php` dengan prefix & middleware role yang jelas, beri
  nama route yang konsisten (contoh: `permintaan-produksi.proses`, `purchase-order.approve`).
- Redirect setelah login menuju route dashboard sesuai role (`dashboard.manajer`,
  `dashboard.admin`, `dashboard.staff` atau satu `dashboard` yang render view berbeda).

Controller harus TIPIS — cukup panggil service/model, validasi via Form Request, lalu
redirect/return view dengan pesan sukses/error (gunakan session flash message).

DATATABLES (SERVER-SIDE):
Untuk setiap controller yang punya tabel index/list (Material, Produk, PermintaanProduksi,
PurchaseOrder, StokOpname, Laporan, User), tambahkan method terpisah bernama `data()` yang:
- Di-route sebagai endpoint AJAX terpisah, contoh: `materials.data`, `purchase-order.data`.
- Menggunakan facade `Yajra\DataTables\Facades\DataTables` untuk generate response JSON dari
  Eloquent query (pakai `->of($query)->addColumn('aksi', fn($row) => view('...partial-aksi',
  compact('row'))->render())->rawColumns(['aksi', 'status'])->make(true)`).
- Kolom `status` di-render sebagai badge warna (pakai partial Blade kecil, jangan taruh HTML
  mentah di controller).
- View index-nya sendiri (`index.blade.php`) HANYA berisi `<x-data-table>` kosong yang
  fetch data dari endpoint `data()` tsb — tidak lagi pakai `->paginate()` manual di controller
  untuk tabel-tabel ini.
```

## Ringkasan Endpoint yang Harus Ada
| Endpoint (nama route) | Role | Fungsi |
|---|---|---|
| `login`, `logout` | semua | Autentikasi |
| `dashboard` | semua (beda konten) | Ringkasan |
| `users.*` | admin | CRUD user |
| `materials.*` | admin | CRUD material |
| `produk.*` | admin | CRUD produk + BOM |
| `permintaan-produksi.store` | manajer | Buat permintaan |
| `permintaan-produksi.proses` | admin | Hitung BOM & cek stok |
| `permintaan-produksi.mulai` | staff | Mulai produksi (→ WIP) |
| `permintaan-produksi.selesai` | staff | Selesaikan produksi |
| `purchase-order.store` | admin | Ajukan PO |
| `purchase-order.approve` / `.reject` | manajer | Approval PO |
| `purchase-order.terima-material` | staff | Terima barang PO |
| `stok-opname.store` | staff | Catat opname |
| `laporan.index` / `.cetak-pdf` | manajer/admin | Laporan & cetak PDF |
| `{modul}.data` (mis. `materials.data`, `purchase-order.data`) | sesuai modul | Endpoint AJAX JSON untuk DataTables server-side |
