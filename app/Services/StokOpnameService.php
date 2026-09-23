<?php

namespace App\Services;

use App\Models\Material;
use App\Models\StokOpname;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use InvalidArgumentException;

class StokOpnameService
{
    public function catatOpname(Material $material, int $stokFisik, User $staff): StokOpname
    {
        return DB::transaction(function () use ($material, $stokFisik, $staff): StokOpname {
            if ($stokFisik < 0) {
                throw new InvalidArgumentException('Stok fisik tidak boleh bernilai negatif.');
            }

            $material = Material::query()->whereKey($material->id)->lockForUpdate()->firstOrFail();
            $stokSistem = $material->stok_sistem;
            $opname = StokOpname::create([
                'material_id' => $material->id,
                'user_id' => $staff->id,
                'stok_sistem' => $stokSistem,
                'stok_fisik' => $stokFisik,
                'selisih_stok' => $stokFisik - $stokSistem,
                'tanggal_opname' => now()->toDateString(),
            ]);
            $material->update(['stok_sistem' => $stokFisik]);

            return $opname;
        });
    }
}
