# 03 — Migrations

> **Cara pakai:** Lampirkan `00-project-overview.md` + `02-database-schema.md` bersama file ini.

## Prompt untuk AI Assistant

```
Berdasarkan skema database final di `02-database-schema.md`, buatkan seluruh file migration
Laravel untuk project ini.

KETENTUAN:
1. Buat migration dengan urutan yang benar sesuai dependency foreign key (tabel independen dulu,
   baru tabel yang punya FK).
2. Gunakan `foreignId()->constrained()->cascadeOnDelete()` untuk FK wajib (not null), dan
   `foreignId()->nullable()->constrained()->nullOnDelete()` untuk FK nullable.
3. Gunakan `$table->enum()` untuk kolom status sesuai daftar enum di project overview.
4. Tambahkan index pada kolom yang sering difilter (status_permintaan, status_po, material_id
   di tabel yang relevan).
5. Modifikasi migration `users` bawaan Laravel (tambah kolom nama, username, role, no_hp, foto)
   alih-alih membuat tabel users baru — jelaskan perubahan apa saja yang kamu buat pada migration
   default itu.
6. Setelah semua migration jadi, buatkan juga `DatabaseSeeder.php` sederhana yang membuat:
   - 1 user role manajer, 1 admin, 1 staff_workshop (password default: "password", beri tahu
     saya kredensialnya di akhir)
   - beberapa data dummy material (5-8 item) dan produk (2-3 item) beserta BOM-nya, supaya saya
     bisa langsung testing alur bisnis dari awal.
7. Tampilkan juga perintah `php artisan migrate:fresh --seed` yang perlu saya jalankan.

Ikuti nama tabel & kolom PERSIS seperti di `02-database-schema.md`. Jangan menambahkan tabel
atau kolom baru yang tidak ada di skema tanpa menyebutkannya dulu sebagai catatan terpisah.
```

## Checklist Verifikasi Setelah AI Selesai
- [ ] Urutan migration tidak error saat `php artisan migrate:fresh`
- [ ] Semua FK mengarah ke tabel & kolom yang benar
- [ ] Kolom enum sesuai daftar status di `00-project-overview.md` bagian 8
- [ ] Seeder berhasil membuat 3 user (manajer/admin/staff) + data dummy material/produk/BOM
- [ ] Tidak ada tabel/kolom tambahan di luar skema tanpa penjelasan
