@extends('layouts.app', ['title' => 'Ajukan Purchase Order', 'breadcrumb' => 'Pengadaan'])
@section('content')
    <x-card title="Ajukan purchase order">
        <form method="POST" action="{{ route('purchase-orders.store') }}" class="max-w-3xl space-y-5 js-repeater">
            @csrf
            <div><label class="mb-1 block text-sm font-medium">Permintaan produksi</label><select class="form-input"
                    name="permintaan_produksi_id">
                    <option value="">PO manual</option>
                    @foreach ($permintaan as $item)
                        <option value="{{ $item->id }}" @selected(old('permintaan_produksi_id') == $item->id)>#{{ $item->id }} -
                            {{ $item->produk->nama_produk ?? 'Produk' }}</option>
                    @endforeach
                </select>
                <x-field-error field="permintaan_produksi_id" />
            </div>
            <div><label class="mb-1 block text-sm font-medium">Tanggal PO</label><input class="form-input" type="date"
                    name="tanggal_po" value="{{ old('tanggal_po', now()->toDateString()) }}" required><x-field-error
                    field="tanggal_po" /></div>
            <div class="border-t border-border pt-4">
                <div class="mb-3 flex items-center justify-between">
                    <p class="text-sm font-semibold">Detail material</p><button type="button"
                        class="js-add-row text-sm font-medium text-primary hover:underline">+ Tambah material</button>
                </div>
                <div class="js-repeater-list space-y-3">
                    @foreach (old('details', [['material_id' => '', 'jumlah_material' => 1]]) as $index => $detail)
                        <div class="js-repeater-row grid gap-3 sm:grid-cols-[1fr_10rem_auto]"><select class="form-input"
                                name="details[{{ $index }}][material_id]" required>
                                <option value="">Pilih material</option>
                                @foreach ($materials as $material)
                                    <option value="{{ $material->id }}" @selected((string) ($detail['material_id'] ?? '') === (string) $material->id)>
                                        {{ $material->nama_material }}</option>
                                @endforeach
                            </select>
                            <input class="form-input" type="number" min="1"
                                name="details[{{ $index }}][jumlah_material]"
                                value="{{ $detail['jumlah_material'] ?? 1 }}" required><button type="button"
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
            </div>
            <x-button type="submit">Ajukan PO</x-button>
        </form>
    </x-card>
@endsection
