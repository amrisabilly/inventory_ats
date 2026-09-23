<?php

namespace App\Models;

use App\Models\DetailPo;
use App\Models\PermintaanProduksi;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PurchaseOrder extends Model
{
    use HasFactory;

    protected $fillable = ['nomor_po', 'user_id', 'permintaan_produksi_id', 'status_po', 'tanggal_po', 'catatan_penolakan'];

    protected $casts = ['tanggal_po' => 'date'];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function permintaanProduksi(): BelongsTo
    {
        return $this->belongsTo(PermintaanProduksi::class);
    }

    public function detailPos(): HasMany
    {
        return $this->hasMany(DetailPo::class);
    }
}
