# 02 — Database Schema & ERD

> **Cara pakai:** Lampirkan bersama `00-project-overview.md`. Ini adalah "sumber kebenaran" struktur tabel yang HARUS diikuti persis oleh tahap migration, model, controller, dan view berikutnya.

## Prompt untuk AI Assistant

```
Berdasarkan project overview yang saya lampirkan, ini adalah rancangan skema database final
(hasil dari Class Diagram & ERD penelitian). Jangan ubah nama tabel/kolom di bawah ini kecuali
kamu menemukan error struktural yang jelas — kalau ada saran perbaikan, sebutkan dulu sebagai
catatan, jangan langsung diubah.

Untuk saat ini TUGASMU HANYA: reviu skema ini, lengkapi tipe data yang belum saya tentukan
(gunakan tipe data Laravel/MySQL yang paling tepat), tentukan foreign key & relasi, dan
tuliskan versi final skema ini dalam format tabel per tabel (siap dipakai sebagai acuan
migration di tahap berikutnya). Jangan buat file migration dulu di tahap ini.
```

## Entity Relationship (ringkas)
```
users (1) ──< permintaan_produksi (dibuat oleh manajer)
users (1) ──< purchase_order (diajukan oleh admin)
users (1) ──< stok_opname (dilakukan oleh staff)

produk (1) ──< bom (1) ──< detail_bom (>*) material
material (1) ──< detail_bom
material (1) ──< detail_po
material (1) ──< stok_opname

produk (1) ──< permintaan_produksi
purchase_order (1) ──< detail_po
```

## Rancangan Tabel

### 1. `users`
| Kolom | Tipe | Keterangan |
|---|---|---|
| id | bigint, PK | |
| nama | varchar(150) | |
| username | varchar(100), unique | |
| password | varchar, hashed | |
| role | enum('manajer','admin','staff_workshop') | |
| email | varchar(150), unique, nullable | |
| no_hp | varchar(20), nullable | |
| foto | varchar(255), nullable | path foto profil |
| timestamps | | |

### 2. `produk`
| Kolom | Tipe | Keterangan |
|---|---|---|
| id | bigint, PK | (dipakai sebagai kode_produk) |
| nama_produk | varchar(150) | |
| deskripsi | text, nullable | |
| timestamps | | |

### 3. `boms` (Bill of Material)
| Kolom | Tipe | Keterangan |
|---|---|---|
| id | bigint, PK | |
| produk_id | FK → produk.id | |
| nama_bom | varchar(150) | |
| timestamps | | |

### 4. `detail_boms`
| Kolom | Tipe | Keterangan |
|---|---|---|
| id | bigint, PK | |
| bom_id | FK → boms.id | |
| material_id | FK → materials.id | |
| jumlah_kebutuhan | integer | kebutuhan per 1 unit produk |
| timestamps | | |

### 5. `materials`
| Kolom | Tipe | Keterangan |
|---|---|---|
| id | bigint, PK | |
| nama_material | varchar(150) | |
| satuan | varchar(30) | contoh: pcs, meter, kg |
| stok_sistem | integer, default 0 | stok tersedia (bisa dipakai) |
| stok_wip | integer, default 0 | stok yang sedang dipakai produksi |
| foto | varchar(255), nullable | |
| timestamps | | |

### 6. `permintaan_produksis`
| Kolom | Tipe | Keterangan |
|---|---|---|
| id | bigint, PK | |
| produk_id | FK → produk.id | |
| user_id | FK → users.id | manajer pembuat |
| jumlah_produksi | integer | |
| status_permintaan | enum('pending','menunggu_po','menunggu_material','work_in_process','material_po_cacat','selesai') | default 'pending' |
| tanggal_permintaan | date | |
| timestamps | | |

### 7. `purchase_orders`
| Kolom | Tipe | Keterangan |
|---|---|---|
| id | bigint, PK | |
| nomor_po | varchar(50), unique | auto-generate, contoh PO-2026-0001 |
| user_id | FK → users.id | admin pengaju |
| permintaan_produksi_id | FK → permintaan_produksis.id, nullable | PO bisa terkait 1 permintaan produksi |
| status_po | enum('diajukan','approved','rejected') | default 'diajukan' |
| tanggal_po | date | |
| catatan_penolakan | text, nullable | wajib diisi jika rejected |
| timestamps | | |

### 8. `detail_pos`
| Kolom | Tipe | Keterangan |
|---|---|---|
| id | bigint, PK | |
| purchase_order_id | FK → purchase_orders.id | |
| material_id | FK → materials.id | |
| jumlah_material | integer | jumlah diajukan |
| jumlah_diterima | integer, nullable | diisi staff saat terima barang |
| jumlah_cacat | integer, default 0 | diisi staff jika ada cacat |
| keterangan_cacat | text, nullable | |
| timestamps | | |

### 9. `stok_opnames`
| Kolom | Tipe | Keterangan |
|---|---|---|
| id | bigint, PK | |
| material_id | FK → materials.id | |
| user_id | FK → users.id | staff yang melakukan opname |
| stok_sistem | integer | snapshot stok sistem saat opname |
| stok_fisik | integer | hasil hitung fisik |
| selisih_stok | integer | stok_fisik - stok_sistem |
| tanggal_opname | date | |
| timestamps | | |

## Catatan Desain
- Semua nama tabel memakai konvensi Laravel (snake_case, plural) meskipun di class diagram singular — ini agar Eloquent bisa auto-detect tanpa `$table` custom.
- FK `permintaan_produksi_id` di `purchase_orders` bersifat nullable karena satu PO idealnya terkait 1 permintaan produksi, tapi beri fleksibilitas untuk PO manual di luar alur permintaan produksi.
- Pertimbangkan index pada kolom yang sering di-filter: `status_permintaan`, `status_po`, `material_id`.
