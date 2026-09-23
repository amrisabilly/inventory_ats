<?php

namespace App\Models;

use App\Models\Bom;
use App\Models\PermintaanProduksi;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Produk extends Model
{
    use HasFactory;

    protected $table = 'produk';

    protected $fillable = ['nama_produk', 'deskripsi'];

    public function boms(): HasMany
    {
        return $this->hasMany(Bom::class);
    }

    public function permintaanProduksi(): HasMany
    {
        return $this->hasMany(PermintaanProduksi::class);
    }
}
