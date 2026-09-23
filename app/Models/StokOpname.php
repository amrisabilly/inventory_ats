<?php

namespace App\Models;

use App\Models\Material;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StokOpname extends Model
{
    use HasFactory;

    protected $fillable = ['material_id', 'user_id', 'stok_sistem', 'stok_fisik', 'selisih_stok', 'tanggal_opname'];

    protected $casts = [
        'stok_sistem' => 'integer',
        'stok_fisik' => 'integer',
        'selisih_stok' => 'integer',
        'tanggal_opname' => 'date',
    ];

    public function material(): BelongsTo
    {
        return $this->belongsTo(Material::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
