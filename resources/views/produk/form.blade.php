<div>
    <label class="mb-1 block text-sm font-medium">Nama produk</label><input class="form-input" name="nama_produk"
        value="{{ old('nama_produk', $produk?->nama_produk) }}" required><x-field-error field="nama_produk" />
</div>
<div><label class="mb-1 block text-sm font-medium">Deskripsi</label>
    <textarea class="form-input" name="deskripsi">{{ old('deskripsi', $produk?->deskripsi) }}</textarea><x-field-error field="deskripsi" />
</div>
<div><label class="mb-1 block text-sm font-medium">Nama BOM</label><input class="form-input" name="nama_bom"
        value="{{ old('nama_bom', $produk?->boms->first()?->nama_bom) }}" required><x-field-error field="nama_bom" />
</div>

<div class="js-repeater border-t border-border pt-4">
    <div class="mb-3 flex items-center justify-between">
        <p class="text-sm font-semibold">Detail BOM</p><button type="button"
            class="js-add-row text-sm font-medium text-primary hover:underline">+ Tambah material</button>
    </div>
    <div class="js-repeater-list space-y-3">
        @php($details = old('bom_details', $produk?->boms->first()?->detailBoms?->toArray() ?: [['material_id' => '', 'jumlah_kebutuhan' => 1]]))
        @foreach ($details as $index => $detail)
            <div class="js-repeater-row grid gap-3 sm:grid-cols-[1fr_10rem_auto]"><select class="form-input"
                    name="bom_details[{{ $index }}][material_id]" required>
                    <option value="">Pilih material</option>
                    @foreach ($materials as $material)
                        <option value="{{ $material->id }}" @selected((string) ($detail['material_id'] ?? '') === (string) $material->id)>{{ $material->nama_material }}
                        </option>
                    @endforeach
                </select>
                <input class="form-input" type="number" min="1"
                    name="bom_details[{{ $index }}][jumlah_kebutuhan]"
                    value="{{ $detail['jumlah_kebutuhan'] ?? 1 }}" required><button type="button"
                    class="js-remove-row rounded-lg px-3 text-sm text-danger hover:bg-red-50">Hapus</button>
            </div>
        @endforeach
    </div>
    <template class="js-repeater-template">
        <div class="js-repeater-row grid gap-3 sm:grid-cols-[1fr_10rem_auto]"><select class="form-input"
                name="bom_details[__INDEX__][material_id]" required>
                <option value="">Pilih material</option>
                @foreach ($materials as $material)
                    <option value="{{ $material->id }}">{{ $material->nama_material }}</option>
                @endforeach
            </select><input class="form-input" type="number" min="1"
                name="bom_details[__INDEX__][jumlah_kebutuhan]" value="1" required><button type="button"
                class="js-remove-row rounded-lg px-3 text-sm text-danger hover:bg-red-50">Hapus</button></div>
    </template>
</div>
