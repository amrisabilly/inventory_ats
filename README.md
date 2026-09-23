# Kumpulan Prompt Laravel — Sistem PO & Material Mebel

Dibuat dari `Template_Skripsi_Sempro.md` (Bab III Metodologi). Dipecah per tahap supaya tiap prompt bisa dilampirkan satu-satu ke AI coding assistant (Claude Code, Cursor, Copilot Chat, dsb) di VSCode tanpa kena limit context.

## Urutan Pemakaian

| # | File | Isi | Wajib Lampirkan Bersama |
|---|---|---|---|
| 0 | `00-project-overview.md` | Konteks proyek, role, alur bisnis — **lampirkan di SETIAP sesi** | — |
| 1 | `01-ui-design-color-palette.md` | Design system, palet warna, layout dasar | 00 |
| 2 | `02-database-schema.md` | ERD & struktur tabel final | 00 |
| 3 | `03-migrations.md` | Prompt generate migration + seeder | 00 + 02 |
| 4 | `04-models.md` | Prompt generate Eloquent model + service class (business logic) | 00 + 02 (+ hasil 03) |
| 5 | `05-controllers.md` | Prompt generate controller, route, middleware role, form request | 00 (+ hasil 04) |
| 6 | `06-views.md` | Prompt generate Blade view, per modul (jangan sekaligus semua) | 00 + 01 (+ hasil 05) |

## Cara Kerja yang Disarankan
1. Buka sesi baru → lampirkan `00-project-overview.md` + file tahap yang sedang dikerjakan.
2. Copy prompt di dalam blok ```code``` pada file tersebut ke chat AI assistant.
3. Setelah AI selesai generate kode, review hasilnya, jalankan/test.
4. Lanjut ke file tahap berikutnya di sesi baru (supaya context tetap ringkas).
5. Untuk tahap 06 (views), kerjakan per modul sesuai daftar pemisahan di dalam file — jangan minta semua view dalam satu prompt.

## Catatan
- Palet warna & stack teknologi adalah **rekomendasi**, sudah disesuaikan dengan konteks sistem (aplikasi internal manajemen material mebel, banyak tabel data & status). Bisa diubah, tinggal edit bagian terkait di `01-ui-design-color-palette.md`.
- Semua nama tabel/kolom di `02-database-schema.md` menjadi acuan tunggal untuk semua tahap berikutnya — kalau mau ubah skema, ubah di file ini dulu baru lanjut ke tahap migration.
