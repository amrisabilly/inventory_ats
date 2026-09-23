<?php

namespace App\Models;

use App\Models\Material;
use App\Models\PurchaseOrder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DetailPo extends Model
{
    use HasFactory;

    protected $fillable = ['purchase_order_id', 'material_id', 'jumlah_material', 'jumlah_diterima', 'jumlah_cacat', 'keterangan_cacat'];

    protected $casts = [
        'jumlah_material' => 'integer',
        'jumlah_diterima' => 'integer',
        'jumlah_cacat' => 'integer',
    ];

    public function purchaseOrder(): BelongsTo
    {
        return $this->belongsTo(PurchaseOrder::class);
    }

    public function material(): BelongsTo
    {
        return $this->belongsTo(Material::class);
    }
}
