<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePurchaseOrderRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'permintaan_produksi_id' => ['nullable', 'integer', 'exists:permintaan_produksis,id'],
            'tanggal_po' => ['required', 'date'],
            'details' => ['required', 'array', 'min:1'],
            'details.*.material_id' => ['required', 'integer', 'distinct', 'exists:materials,id'],
            'details.*.jumlah_material' => ['required', 'integer', 'min:1'],
        ];
    }
}
