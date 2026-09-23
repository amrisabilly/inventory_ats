<div><label class="mb-1 block text-sm font-medium">Nama material</label><input class="form-input" name="nama_material"
        value="{{ old('nama_material', $material?->nama_material) }}" required><x-field-error field="nama_material" />
</div>
<div><label class="mb-1 block text-sm font-medium">Satuan</label><input class="form-input" name="satuan"
        value="{{ old('satuan', $material?->satuan) }}" required><x-field-error field="satuan" /></div>
<div><label class="mb-1 block text-sm font-medium">Stok sistem</label><input class="form-input" type="number"
        min="0" name="stok_sistem" value="{{ old('stok_sistem', $material?->stok_sistem ?? 0) }}"
        required><x-field-error field="stok_sistem" /></div>
<div><label class="mb-1 block text-sm font-medium">Stok WIP</label><input class="form-input" type="number"
        min="0" name="stok_wip" value="{{ old('stok_wip', $material?->stok_wip ?? 0) }}"><x-field-error
        field="stok_wip" /></div>
<div><label class="mb-1 block text-sm font-medium">Foto</label><input type="file" name="foto" accept="image/*">
</div>
