<?php

namespace Database\Seeders;

use App\Models\Bom;
use App\Models\DetailBom;
use App\Models\Material;
use App\Models\Produk;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::create([
            'nama' => 'Manajer Utama',
            'username' => 'manajer',
            'email' => 'manajer@inventory.test',
            'role' => 'manajer',
            'password' => Hash::make('password'),
        ]);
        User::create([
            'nama' => 'Admin Gudang',
            'username' => 'admin',
            'email' => 'admin@inventory.test',
            'role' => 'admin',
            'password' => Hash::make('password'),
        ]);
        User::create([
            'nama' => 'Staff Workshop',
            'username' => 'staff',
            'email' => 'staff@inventory.test',
            'role' => 'staff_workshop',
            'password' => Hash::make('password'),
        ]);

        $materials = collect([
            ['nama_material' => 'Kayu Jati', 'satuan' => 'papan', 'stok_sistem' => 120],
            ['nama_material' => 'Kayu Mahoni', 'satuan' => 'papan', 'stok_sistem' => 90],
            ['nama_material' => 'Multipleks 12 mm', 'satuan' => 'lembar', 'stok_sistem' => 60],
            ['nama_material' => 'Sekrup 4 cm', 'satuan' => 'pcs', 'stok_sistem' => 500],
            ['nama_material' => 'Lem Kayu', 'satuan' => 'kg', 'stok_sistem' => 40],
            ['nama_material' => 'Cat Melamin', 'satuan' => 'liter', 'stok_sistem' => 25],
        ])->map(fn(array $data) => Material::create($data));

        $produkData = [
            ['nama_produk' => 'Meja Makan Jati', 'deskripsi' => 'Meja makan kayu jati untuk enam orang.'],
            ['nama_produk' => 'Kursi Mahoni', 'deskripsi' => 'Kursi makan dengan rangka kayu mahoni.'],
            ['nama_produk' => 'Rak Buku Minimalis', 'deskripsi' => 'Rak buku dari multipleks dengan finishing melamin.'],
        ];

        $bomMaterials = [
            [1 => 4, 4 => 16, 5 => 1, 6 => 1],
            [2 => 2, 4 => 8, 5 => 1, 6 => 1],
            [3 => 3, 4 => 12, 5 => 1, 6 => 1],
        ];

        foreach ($produkData as $index => $data) {
            $produk = Produk::create($data);
            $bom = Bom::create(['produk_id' => $produk->id, 'nama_bom' => "BOM {$produk->nama_produk}"]);
            foreach ($bomMaterials[$index] as $materialIndex => $jumlah) {
                DetailBom::create([
                    'bom_id' => $bom->id,
                    'material_id' => $materials[$materialIndex - 1]->id,
                    'jumlah_kebutuhan' => $jumlah,
                ]);
            }
        }
    }
}
