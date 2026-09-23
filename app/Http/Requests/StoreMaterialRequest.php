<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreMaterialRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'nama_material' => ['required', 'string', 'max:150'],
            'satuan' => ['required', 'string', 'max:30'],
            'stok_sistem' => ['required', 'integer', 'min:0'],
            'stok_wip' => ['nullable', 'integer', 'min:0'],
            'foto' => ['nullable', 'image', 'max:2048'],
        ];
    }
}
