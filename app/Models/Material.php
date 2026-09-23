<?php

namespace App\Models;

use App\Models\DetailBom;
use App\Models\DetailPo;
use App\Models\StokOpname;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Material extends Model
{
    use HasFactory;

    protected $fillable = ['nama_material', 'satuan', 'stok_sistem', 'stok_wip', 'foto'];

    protected $casts = [
        'stok_sistem' => 'integer',
        'stok_wip' => 'integer',
    ];

    public function detailBoms(): HasMany
    {
        return $this->hasMany(DetailBom::class);
    }

    public function detailPos(): HasMany
    {
        return $this->hasMany(DetailPo::class);
    }

    public function stokOpnames(): HasMany
    {
        return $this->hasMany(StokOpname::class);
    }
}
