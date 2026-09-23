@extends('layouts.app', ['title' => 'Stok Opname', 'breadcrumb' => 'Persediaan'])
@section('content')
    <div class="mb-5 flex items-center justify-between">
        <h2 class="text-xl font-semibold">Stok Opname</h2><a href="{{ route('stok-opname.create') }}"><x-button>Catat
                opname</x-button></a>
    </div><x-card><x-data-table id="stok-opname-table" :ajax="route('stok-opname.data')" :columns="[
        ['data' => 'material_nama', 'title' => 'Material'],
        ['data' => 'petugas', 'title' => 'Petugas'],
        ['data' => 'stok_sistem', 'title' => 'Stok Sistem'],
        ['data' => 'stok_fisik', 'title' => 'Stok Fisik'],
        ['data' => 'selisih_stok', 'title' => 'Selisih'],
        ['data' => 'tanggal_opname', 'title' => 'Tanggal'],
    ]" /></x-card>
@endsection
