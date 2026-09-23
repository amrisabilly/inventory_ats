<?php

namespace App\Models;

use App\Models\DetailBom;
use App\Models\Produk;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Bom extends Model
{
    use HasFactory;

    protected $fillable = ['produk_id', 'nama_bom'];

    public function produk(): BelongsTo
    {
        return $this->belongsTo(Produk::class);
    }

    public function detailBoms(): HasMany
    {
        return $this->hasMany(DetailBom::class);
    }
}
