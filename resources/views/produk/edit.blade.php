@extends('layouts.app', ['title' => 'Edit Produk', 'breadcrumb' => 'Master Data / Produk'])
@section('content')
    <x-card title="Edit produk dan BOM">
        <form method="POST" action="{{ route('produk.update', $produk) }}" class="max-w-xl space-y-4">@csrf @method('PUT')
            @include('produk.form', compact('produk'))<x-button type="submit">Simpan perubahan</x-button></form>
    </x-card>
@endsection
