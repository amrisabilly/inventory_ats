@extends('layouts.app', ['title' => 'Permintaan Produksi', 'breadcrumb' => 'Produksi'])
@section('content')
    <div class="mb-5 flex items-center justify-between">
        <h2 class="text-xl font-semibold">Permintaan Produksi</h2>
        @if (auth()->user()->role === 'manajer')
            <a href="{{ route('permintaan-produksi.create') }}"><x-button>Tambah permintaan</x-button></a>
        @endif
    </div><x-card><x-data-table id="permintaan-table" :ajax="route('permintaan-produksi.data')" :columns="[
        ['data' => 'produk_nama', 'title' => 'Produk'],
        ['data' => 'pembuat', 'title' => 'Pembuat'],
        ['data' => 'jumlah_produksi', 'title' => 'Jumlah'],
        ['data' => 'status_permintaan', 'title' => 'Status'],
        ['data' => 'tanggal_permintaan', 'title' => 'Tanggal'],
        ['data' => 'aksi', 'title' => 'Aksi', 'orderable' => false, 'searchable' => false],
    ]" /></x-card>
@endsection
