@extends('layouts.app', ['title' => 'Catat Stok Opname', 'breadcrumb' => 'Persediaan'])
@section('content')
    <x-card title="Catat stok opname">
        <form method="POST" action="{{ route('stok-opname.store') }}" class="max-w-xl space-y-4">@csrf<div><label
                    class="mb-1 block text-sm font-medium">Material</label><select class="form-input js-material-select"
                    name="material_id" required>
                    @foreach ($materials as $material)
                        <option value="{{ $material->id }}" data-stock="{{ $material->stok_sistem }}">
                            {{ $material->nama_material }}</option>
                    @endforeach
                </select><input type="hidden" class="js-stock-system" value="{{ $materials->first()?->stok_sistem ?? 0 }}">
            </div>
            <div class="rounded-lg bg-surface px-4 py-3 text-sm text-text-secondary">Stok sistem: <strong
                    class="js-stock-system-label">{{ $materials->first()?->stok_sistem ?? 0 }}</strong> | Selisih: <strong
                    class="js-stock-difference">0</strong></div>
            <div><label class="mb-1 block text-sm font-medium">Stok fisik</label><input class="form-input js-stock-physical"
                    type="number" min="0" name="stok_fisik" required><x-field-error field="stok_fisik" /></div>
            <div><label class="mb-1 block text-sm font-medium">Tanggal opname</label><input class="form-input"
                    type="date" name="tanggal_opname" value="{{ old('tanggal_opname', now()->toDateString()) }}"
                    required><x-field-error field="tanggal_opname" /></div><x-button type="submit">Simpan opname</x-button>
        </form>
    </x-card>
@endsection
