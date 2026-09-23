<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;

class RevisePurchaseOrderRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'tanggal_po' => ['required', 'date'],
            'details' => ['required', 'array', 'min:1'],
            'details.*.material_id' => ['required', 'integer', 'distinct', 'exists:materials,id'],
            'details.*.jumlah_material' => ['required', 'integer', 'min:1'],
        ];
    }

    public function withValidator(Validator $validator): void
    {
        $purchaseOrder = $this->route('purchaseOrder');
        $minimumByMaterial = $purchaseOrder?->detailPos
            ?->pluck('jumlah_material', 'material_id')
            ->map(fn ($jumlah) => (int) $jumlah)
            ->all() ?? [];

        $validator->after(function (Validator $validator) use ($minimumByMaterial): void {
            foreach ($this->input('details', []) as $index => $detail) {
                $materialId = (int) ($detail['material_id'] ?? 0);
                $jumlahBaru = (int) ($detail['jumlah_material'] ?? 0);
                $minimum = $minimumByMaterial[$materialId] ?? 1;

                if ($jumlahBaru < $minimum) {
                    $validator->errors()->add(
                        "details.{$index}.jumlah_material",
                        "Jumlah tidak boleh kurang dari {$minimum}, sesuai nominal PO sebelumnya."
                    );
                }
            }
        });
    }
}