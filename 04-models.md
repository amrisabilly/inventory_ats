# 04 — Eloquent Models & Business Logic

> **Cara pakai:** Lampirkan `00-project-overview.md` + `02-database-schema.md` (+ hasil migration dari tahap 03 kalau ada) bersama file ini.

## Prompt untuk AI Assistant

```
Berdasarkan skema database dan migration yang sudah dibuat, buatkan seluruh Eloquent Model
untuk project ini, lengkap dengan relasi antar model.

MODEL YANG DIBUTUHKAN:
User, Produk, Bom, DetailBom, Material, PermintaanProduksi, PurchaseOrder, DetailPo, StokOpname

KETENTUAN:
1. Definisikan relasi lengkap (hasMany, belongsTo, hasManyThrough bila perlu) sesuai ERD:
   - User hasMany PermintaanProduksi, PurchaseOrder, StokOpname
   - Produk hasMany Bom, hasMany PermintaanProduksi
   - Bom belongsTo Produk, hasMany DetailBom
   - DetailBom belongsTo Bom, belongsTo Material
   - Material hasMany DetailBom, DetailPo, StokOpname
   - PermintaanProduksi belongsTo Produk, belongsTo User, hasMany PurchaseOrder (opsional)
   - PurchaseOrder belongsTo User, belongsTo PermintaanProduksi (nullable), hasMany DetailPo
   - DetailPo belongsTo PurchaseOrder, belongsTo Material
2. Tambahkan `$fillable` (jangan pakai `$guarded = []` demi keamanan) sesuai kolom masing-masing
   tabel.
3. Tambahkan cast yang sesuai (misal tanggal → 'date', enum → tetap string tapi validasi di
   Form Request).
4. Buat SATU buah SERVICE CLASS bernama `App\Services\ProduksiService` yang berisi method-method
   logika bisnis inti (jangan taruh logic ini di controller/model):
   - `hitungKebutuhanMaterial(PermintaanProduksi $permintaan): array` — hitung total kebutuhan
     tiap material berdasarkan BOM produk × jumlah_produksi
   - `cekKetersediaanStok(array $kebutuhan): array` — bandingkan kebutuhan vs stok_sistem tiap
     material, kembalikan info material mana yang kurang & jumlah kekurangannya
   - `prosesPermintaanProduksi(PermintaanProduksi $permintaan): void` — orchestrate 2 method di
     atas lalu update status_permintaan sesuai hasil (pending jika cukup, menunggu_po jika kurang)
   - `mulaiProduksi(PermintaanProduksi $permintaan): void` — kurangi stok_sistem material sesuai
     BOM, tambahkan ke stok_wip, ubah status jadi work_in_process
   - `selesaikanProduksi(PermintaanProduksi $permintaan): void` — kurangi stok_wip material
     sesuai BOM, ubah status jadi selesai
5. Buat SERVICE CLASS `App\Services\PurchaseOrderService` dengan method:
   - `buatPO(array $data, User $admin): PurchaseOrder` — generate nomor_po otomatis format
     `PO-{tahun}-{4 digit urut}`, simpan header + detail
   - `approvePO(PurchaseOrder $po): void` — ubah status_po approved, ubah status permintaan
     produksi terkait jadi menunggu_material
   - `rejectPO(PurchaseOrder $po, string $catatan): void` — wajibkan catatan, ubah status_po
     rejected
   - `terimaMaterialPO(PurchaseOrder $po, array $itemsDiterima): void` — update jumlah_diterima
     & jumlah_cacat per detail_po, tambahkan stok_sistem material sesuai jumlah_diterima minus
     cacat, jika ada cacat maka set status permintaan produksi terkait jadi material_po_cacat,
     jika tidak ada cacat set jadi pending
6. Buat SERVICE CLASS `App\Services\StokOpnameService` dengan method:
   - `catatOpname(Material $material, int $stokFisik, User $staff): StokOpname` — hitung selisih,
     simpan record, update stok_sistem material sesuai stok_fisik

Semua service method HARUS dibungkus `DB::transaction()` karena melibatkan update multi-tabel.
Tambahkan validasi/guard clause sederhana (misal: tolak jika status permintaan sudah selesai)
dan lempar Exception dengan pesan jelas jika terjadi kondisi tidak valid.
```

## Peta Business Logic ke Service (untuk cek ulang)
| Alur di Skripsi | Service & Method |
|---|---|
| Admin proses permintaan produksi (hitung BOM, cek stok) | `ProduksiService::prosesPermintaanProduksi()` |
| Admin ajukan PO | `PurchaseOrderService::buatPO()` |
| Manajer approve/reject PO | `PurchaseOrderService::approvePO()` / `rejectPO()` |
| Staff mulai produksi (alokasi stok → WIP) | `ProduksiService::mulaiProduksi()` |
| Staff terima material PO (termasuk cacat) | `PurchaseOrderService::terimaMaterialPO()` |
| Staff selesaikan produksi | `ProduksiService::selesaikanProduksi()` |
| Staff stok opname | `StokOpnameService::catatOpname()` |
