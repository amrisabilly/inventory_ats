<?php

namespace App\Models;

use App\Models\Produk;
use App\Models\PurchaseOrder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PermintaanProduksi extends Model
{
    use HasFactory;

    protected $fillable = ['produk_id', 'user_id', 'jumlah_produksi', 'status_permintaan', 'tanggal_permintaan'];

    protected $casts = [
        'jumlah_produksi' => 'integer',
        'tanggal_permintaan' => 'date',
    ];

    public function produk(): BelongsTo
    {
        return $this->belongsTo(Produk::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function purchaseOrders(): HasMany
    {
        return $this->hasMany(PurchaseOrder::class);
    }
}
