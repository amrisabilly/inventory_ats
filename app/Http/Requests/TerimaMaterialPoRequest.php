<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TerimaMaterialPoRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'items_diterima' => ['required', 'array', 'min:1'],
            'items_diterima.*.detail_po_id' => ['required', 'integer', 'exists:detail_pos,id'],
            'items_diterima.*.jumlah_diterima' => ['required', 'integer', 'min:0'],
            'items_diterima.*.jumlah_cacat' => ['nullable', 'integer', 'min:0'],
            'items_diterima.*.keterangan_cacat' => ['nullable', 'string', 'max:2000'],
        ];
    }
}
