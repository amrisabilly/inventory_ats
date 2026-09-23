<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreProdukRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'nama_produk' => ['required', 'string', 'max:150'],
            'deskripsi' => ['nullable', 'string'],
            'nama_bom' => ['required', 'string', 'max:150'],
            'bom_details' => ['required', 'array', 'min:1'],
            'bom_details.*.material_id' => ['required', 'integer', 'distinct', 'exists:materials,id'],
            'bom_details.*.jumlah_kebutuhan' => ['required', 'integer', 'min:1'],
        ];
    }
}
