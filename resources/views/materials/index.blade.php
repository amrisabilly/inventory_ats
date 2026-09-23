@extends('layouts.app', ['title' => 'Material', 'breadcrumb' => 'Master Data'])
@section('content')
    <div class="mb-5 flex items-center justify-between">
        <h2 class="text-xl font-semibold">Data Material</h2><a href="{{ route('materials.create') }}"><x-button>Tambah
                material</x-button></a>
    </div><x-card><x-data-table id="materials-table" :ajax="route('materials.data')" :columns="[
        ['data' => 'nama_material', 'title' => 'Material'],
        ['data' => 'satuan', 'title' => 'Satuan'],
        ['data' => 'stok_sistem', 'title' => 'Stok Sistem'],
        ['data' => 'stok_wip', 'title' => 'Stok WIP'],
        ['data' => 'aksi', 'title' => 'Aksi', 'orderable' => false, 'searchable' => false],
    ]" /></x-card>
@endsection
