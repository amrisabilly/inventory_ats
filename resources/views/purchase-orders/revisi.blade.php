@extends('layouts.app', ['title' => 'Revisi Purchase Order', 'breadcrumb' => 'Pengadaan / Revisi'])

@section('content')
    <x-card title="Revisi {{ $purchaseOrder->nomor_po }}">
        <div class="mb-5 rounded-lg border border-red-200 bg-red-50 p-4 text-sm text-danger">
            <p class="font-semibold">PO ditolak oleh manager.</p>
            <p class="mt-1">Alasan: {{ $purchaseOrder->catatan_penolakan ?: 'Tidak ada catatan.' }}</p>
        </div>

        <form method="POST" action="{{ route('purchase-orders.revisi', $purchaseOrder) }}"
            class="max-w-3xl space-y-5 js-repeater">
            @csrf
            @method('PUT')
            <div>
                <label class="mb-1 block text-sm font-medium">Tanggal PO</label>
                <input class="form-input" type="date" name="tanggal_po"
                    value="{{ old('tanggal_po', $purchaseOrder->tanggal_po?->toDateString()) }}" required>
                <x-field-error field="tanggal_po" />
            </div>

            <div class="border-t border-border pt-4">
                <div class="mb-3 flex items-center justify-between">
                    <p class="text-sm font-semibold">Detail material revisi</p>
                    <button type="button" class="js-add-row text-sm font-medium text-primary hover:underline">+ Tambah
                        material</button>
                </div>
                <div class="js-repeater-list space-y-3">
                    @foreach (old('details', $purchaseOrder->detailPos->map(fn($detail) => ['material_id' => $detail->material_id, 'jumlah_material' => $detail->jumlah_material])->toArray()) as $index => $detail)
                        <div class="js-repeater-row grid gap-3 sm:grid-cols-[1fr_10rem_auto]">
                            <select class="form-input" name="details[{{ $index }}][material_id]" required>
                                <option value="">Pilih material</option>
                                @foreach ($materials as $material)
                                    <option value="{{ $material->id }}" @selected((string) ($detail['material_id'] ?? '') === (string) $material->id)>
                                        {{ $material->nama_material }}</option>
                                @endforeach
                            </select>
                            @php($minimumJumlah = $purchaseOrder->detailPos->firstWhere('material_id', (int) ($detail['material_id'] ?? 0))?->jumlah_material ?? 1)
                            <input class="form-input" type="number" min="{{ $minimumJumlah }}"
                                name="details[{{ $index }}][jumlah_material]"
                                value="{{ $detail['jumlah_material'] ?? $minimumJumlah }}" required>
                            <x-field-error field="details.{{ $index }}.jumlah_material" />
                            <button type="button"
                                class="js-remove-row rounded-lg px-3 text-sm text-danger hover:bg-red-50">Hapus</button>
                        </div>
                    @endforeach
                </div>
                <template class="js-repeater-template">
                    <div class="js-repeater-row grid gap-3 sm:grid-cols-[1fr_10rem_auto]"><select class="form-input"
                            name="details[__INDEX__][material_id]" required>
                            <option value="">Pilih material</option>
                            @foreach ($materials as $material)
                                <option value="{{ $material->id }}">{{ $material->nama_material }}</option>
                            @endforeach
                        </select><input class="form-input" type="number" min="1"
                            name="details[__INDEX__][jumlah_material]" value="1" required><button type="button"
                            class="js-remove-row rounded-lg px-3 text-sm text-danger hover:bg-red-50">Hapus</button></div>
                </template>
                <x-field-error field="details" />
            </div>

            <x-button type="submit">Ajukan Ulang PO</x-button>
        </form>
    </x-card>
@endsection
