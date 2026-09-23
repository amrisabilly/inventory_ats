@extends('layouts.app', ['title' => 'Produk & BOM', 'breadcrumb' => 'Master Data'])
@section('content')
    <div class="mb-5 flex items-center justify-between">
        <h2 class="text-xl font-semibold">Produk & BOM</h2><a href="{{ route('produk.create') }}"><x-button>Tambah
                produk</x-button></a>
    </div><x-card><x-data-table id="produk-table" :ajax="route('produk.data')" :columns="[
        ['data' => 'nama_produk', 'title' => 'Produk'],
        ['data' => 'deskripsi', 'title' => 'Deskripsi'],
        ['data' => 'jumlah_bom', 'title' => 'Jumlah BOM'],
        ['data' => 'bom', 'title' => 'Bill of Material', 'orderable' => false, 'searchable' => false],
        ['data' => 'aksi', 'title' => 'Aksi', 'orderable' => false, 'searchable' => false],
    ]" /></x-card>
@endsection
