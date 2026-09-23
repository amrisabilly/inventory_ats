@extends('layouts.app', ['title' => 'Buat Permintaan Produksi', 'breadcrumb' => 'Produksi'])
@section('content')
    <x-card title="Buat permintaan produksi">
        <form method="POST" action="{{ route('permintaan-produksi.store') }}" class="max-w-xl space-y-4">@csrf<div><label
                    class="mb-1 block text-sm font-medium">Produk</label><select class="form-input" name="produk_id" required>
                    @foreach ($produk as $item)
                        <option value="{{ $item->id }}">{{ $item->nama_produk }}</option>
                    @endforeach
                </select></div>
            <div><label class="mb-1 block text-sm font-medium">Jumlah produksi</label><input class="form-input" type="number"
                    min="1" name="jumlah_produksi" required></div>
            <div><label class="mb-1 block text-sm font-medium">Tanggal permintaan</label><input class="form-input"
                    type="date" name="tanggal_permintaan" value="{{ now()->toDateString() }}" required></div><x-button
                type="submit">Simpan</x-button>
        </form>
    </x-card>
@endsection
