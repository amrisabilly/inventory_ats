@extends('layouts.app', ['title' => 'Tambah Produk', 'breadcrumb' => 'Master Data / Produk'])
@section('content')
    <x-card title="Tambah produk dan BOM">
        <form method="POST" action="{{ route('produk.store') }}" class="max-w-xl space-y-4">@csrf
            @include('produk.form', ['produk' => null])<x-button type="submit">Simpan</x-button></form>
    </x-card>
@endsection
