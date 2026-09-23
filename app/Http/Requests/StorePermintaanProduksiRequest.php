<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePermintaanProduksiRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'produk_id' => ['required', 'integer', 'exists:produk,id'],
            'jumlah_produksi' => ['required', 'integer', 'min:1'],
            'tanggal_permintaan' => ['required', 'date'],
        ];
    }
}
