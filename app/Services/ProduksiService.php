<?php

namespace App\Services;

use App\Models\Material;
use App\Models\PermintaanProduksi;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use RuntimeException;

class ProduksiService
{
    public function __construct(private readonly PurchaseOrderService $purchaseOrderService) {}

    public function hitungKebutuhanMaterial(PermintaanProduksi $permintaan): array
    {
        return DB::transaction(function () use ($permintaan): array {
            $permintaan->loadMissing('produk.boms.detailBoms');
            $bom = $permintaan->produk->boms->first();

            if (!$bom) {
                throw new RuntimeException('Produk belum memiliki BOM.');
            }

            $kebutuhan = [];
            foreach ($bom->detailBoms as $detail) {
                $kebutuhan[$detail->material_id] = ($kebutuhan[$detail->material_id] ?? 0)
                    + ($detail->jumlah_kebutuhan * $permintaan->jumlah_produksi);
            }

            return $kebutuhan;
        });
    }

    public function cekKetersediaanStok(array $kebutuhan): array
    {
        return DB::transaction(function () use ($kebutuhan): array {
            $materials = Material::query()->whereIn('id', array_keys($kebutuhan))->get()->keyBy('id');
            $hasil = [];

            foreach ($kebutuhan as $materialId => $jumlahDibutuhkan) {
                $material = $materials->get($materialId);
                if (!$material) {
                    throw new RuntimeException("Material dengan ID {$materialId} tidak ditemukan.");
                }

                // WIP sudah dialokasikan untuk produksi lain, jadi tidak boleh dipakai
                // untuk memenuhi permintaan baru. Tetap dikembalikan sebagai informasi.
                $stokSiapPakai = $material->stok_sistem;
                $hasil[$materialId] = [
                    'material' => $material,
                    'dibutuhkan' => $jumlahDibutuhkan,
                    'tersedia' => $stokSiapPakai,
                    'stok_sistem' => $material->stok_sistem,
                    'stok_wip' => $material->stok_wip,
                    'stok_teralokasi' => $material->stok_sistem + $material->stok_wip,
                    'kekurangan' => max(0, $jumlahDibutuhkan - $stokSiapPakai),
                    'cukup' => $stokSiapPakai >= $jumlahDibutuhkan,
                ];
            }

            return $hasil;
        });
    }

    public function prosesPermintaanProduksi(PermintaanProduksi $permintaan, User $admin): void
    {
        DB::transaction(function () use ($permintaan, $admin): void {
            if ($permintaan->status_permintaan !== 'pending') {
                throw new RuntimeException('Permintaan produksi hanya dapat diproses saat berstatus pending.');
            }

            $kebutuhan = $this->hitungKebutuhanMaterial($permintaan);
            $ketersediaan = $this->cekKetersediaanStok($kebutuhan);
            $materialKurang = collect($ketersediaan)
                ->filter(fn(array $item) => !$item['cukup'])
                ->map(fn(array $item): array => [
                    'material_id' => $item['material']->id,
                    'jumlah_material' => $item['kekurangan'],
                ])
                ->values()
                ->all();

            if ($materialKurang !== []) {
                $this->purchaseOrderService->buatPO([
                    'permintaan_produksi_id' => $permintaan->id,
                    'tanggal_po' => now()->toDateString(),
                    'details' => $materialKurang,
                ], $admin);
            }

            $permintaan->update([
                'status_permintaan' => $materialKurang === [] ? 'siap_diproduksi' : 'menunggu_po',
            ]);
        });
    }

    public function mulaiProduksi(PermintaanProduksi $permintaan): void
    {
        DB::transaction(function () use ($permintaan): void {
            if (!in_array($permintaan->status_permintaan, ['pending', 'siap_diproduksi'], true)) {
                throw new RuntimeException('Produksi hanya dapat dimulai saat material siap diproduksi.');
            }

            $kebutuhan = $this->hitungKebutuhanMaterial($permintaan);
            $materials = Material::query()->whereIn('id', array_keys($kebutuhan))->lockForUpdate()->get()->keyBy('id');
            foreach ($kebutuhan as $materialId => $jumlah) {
                $material = $materials->get($materialId);
                if (!$material || $material->stok_sistem < $jumlah) {
                    throw new RuntimeException('Stok material tidak mencukupi untuk memulai produksi.');
                }
                $material->decrement('stok_sistem', $jumlah);
                $material->increment('stok_wip', $jumlah);
            }

            $permintaan->update(['status_permintaan' => 'work_in_process']);
        });
    }

    public function selesaikanProduksi(PermintaanProduksi $permintaan): void
    {
        DB::transaction(function () use ($permintaan): void {
            if ($permintaan->status_permintaan !== 'work_in_process') {
                throw new RuntimeException('Produksi hanya dapat diselesaikan saat berstatus work_in_process.');
            }

            $kebutuhan = $this->hitungKebutuhanMaterial($permintaan);
            $materials = Material::query()->whereIn('id', array_keys($kebutuhan))->lockForUpdate()->get()->keyBy('id');
            foreach ($kebutuhan as $materialId => $jumlah) {
                $material = $materials->get($materialId);
                if (!$material || $material->stok_wip < $jumlah) {
                    throw new RuntimeException('Stok WIP material tidak mencukupi untuk menyelesaikan produksi.');
                }
                $material->decrement('stok_wip', $jumlah);
            }

            $permintaan->update(['status_permintaan' => 'selesai']);
        });
    }
}
