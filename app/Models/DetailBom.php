<?php

namespace App\Models;

use App\Models\Bom;
use App\Models\Material;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DetailBom extends Model
{
    use HasFactory;

    protected $fillable = ['bom_id', 'material_id', 'jumlah_kebutuhan'];

    protected $casts = ['jumlah_kebutuhan' => 'integer'];

    public function bom(): BelongsTo
    {
        return $this->belongsTo(Bom::class);
    }

    public function material(): BelongsTo
    {
        return $this->belongsTo(Material::class);
    }
}
