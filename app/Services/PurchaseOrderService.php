<?php

namespace App\Services;

use App\Models\PurchaseOrder;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use InvalidArgumentException;
use RuntimeException;

class PurchaseOrderService
{
    public function buatPO(array $data, User $admin): PurchaseOrder
    {
        return DB::transaction(function () use ($data, $admin): PurchaseOrder {
            if (empty($data['details']) || !is_array($data['details'])) {
                throw new InvalidArgumentException('Detail purchase order wajib diisi.');
            }

            $year = now()->year;
            $lastNumber = (int) PurchaseOrder::query()
                ->where('nomor_po', 'like', "PO-{$year}-%")
                ->lockForUpdate()
                ->max(DB::raw("CAST(SUBSTRING(nomor_po, 10) AS UNSIGNED)"));

            $purchaseOrder = PurchaseOrder::create([
                'nomor_po' => sprintf('PO-%d-%04d', $year, $lastNumber + 1),
                'user_id' => $admin->id,
                'permintaan_produksi_id' => $data['permintaan_produksi_id'] ?? null,
                'status_po' => 'diajukan',
                'tanggal_po' => $data['tanggal_po'] ?? now()->toDateString(),
            ]);

            $purchaseOrder->detailPos()->createMany(array_map(
                fn(array $detail): array => [
                    'material_id' => $detail['material_id'],
                    'jumlah_material' => $detail['jumlah_material'],
                ],
                $data['details']
            ));

            return $purchaseOrder->load('detailPos');
        });
    }

    public function approvePO(PurchaseOrder $po): void
    {
        DB::transaction(function () use ($po): void {
            if ($po->status_po !== 'diajukan') {
                throw new RuntimeException('PO hanya dapat disetujui saat berstatus diajukan.');
            }

            $po->update(['status_po' => 'approved']);
            if ($po->permintaanProduksi) {
                $po->permintaanProduksi->update(['status_permintaan' => 'menunggu_material']);
            }
        });
    }

    public function rejectPO(PurchaseOrder $po, string $catatan): void
    {
        DB::transaction(function () use ($po, $catatan): void {
            if ($po->status_po !== 'diajukan') {
                throw new RuntimeException('PO hanya dapat ditolak saat berstatus diajukan.');
            }
            if (trim($catatan) === '') {
                throw new InvalidArgumentException('Catatan penolakan wajib diisi.');
            }

            $po->update(['status_po' => 'rejected', 'catatan_penolakan' => $catatan]);
        });
    }

    public function revisiPO(PurchaseOrder $po, array $data): void
    {
        DB::transaction(function () use ($po, $data): void {
            if ($po->status_po !== 'rejected') {
                throw new RuntimeException('Hanya PO rejected yang dapat direvisi.');
            }
            if (empty($data['details']) || !is_array($data['details'])) {
                throw new InvalidArgumentException('Detail revisi purchase order wajib diisi.');
            }

            $minimumByMaterial = $po->detailPos()
                ->pluck('jumlah_material', 'material_id')
                ->map(fn ($jumlah) => (int) $jumlah)
                ->all();
            foreach ($data['details'] as $detail) {
                $minimum = $minimumByMaterial[(int) $detail['material_id']] ?? 1;
                if ((int) $detail['jumlah_material'] < $minimum) {
                    throw new InvalidArgumentException("Jumlah material tidak boleh kurang dari {$minimum}, sesuai nominal PO sebelumnya.");
                }
            }

            $po->update([
                'status_po' => 'diajukan',
                'tanggal_po' => $data['tanggal_po'],
                'catatan_penolakan' => null,
            ]);
            $po->detailPos()->delete();
            $po->detailPos()->createMany(array_map(
                fn (array $detail): array => [
                    'material_id' => $detail['material_id'],
                    'jumlah_material' => $detail['jumlah_material'],
                ],
                $data['details']
            ));
        });
    }

    public function terimaMaterialPO(PurchaseOrder $po, array $itemsDiterima): void
    {
        DB::transaction(function () use ($po, $itemsDiterima): void {
            if ($po->status_po !== 'approved') {
                throw new RuntimeException('Material hanya dapat diterima dari PO yang approved.');
            }

            $po->load('detailPos.material', 'permintaanProduksi');
            $hasDefect = false;
            foreach ($itemsDiterima as $item) {
                $detail = $po->detailPos->firstWhere('id', $item['detail_po_id'] ?? null);
                if (!$detail || $detail->jumlah_diterima !== null) {
                    throw new RuntimeException('Detail penerimaan material tidak valid atau sudah diterima.');
                }

                $diterima = (int) ($item['jumlah_diterima'] ?? 0);
                $cacat = (int) ($item['jumlah_cacat'] ?? 0);
                if ($diterima < 0 || $diterima > $detail->jumlah_material || $cacat < 0 || $cacat > $diterima) {
                    throw new InvalidArgumentException('Jumlah diterima atau jumlah cacat tidak valid.');
                }

                $detail->update([
                    'jumlah_diterima' => $diterima,
                    'jumlah_cacat' => $cacat,
                    'keterangan_cacat' => $item['keterangan_cacat'] ?? null,
                ]);
                $detail->material->increment('stok_sistem', $diterima - $cacat);
                $hasDefect = $hasDefect || $cacat > 0;
            }

            if ($po->permintaanProduksi) {
                $po->permintaanProduksi->update([
                    'status_permintaan' => $hasDefect ? 'material_po_cacat' : 'siap_diproduksi',
                ]);
            }
        });
    }
}
