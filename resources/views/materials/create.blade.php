@extends('layouts.app', ['title' => 'Tambah Material', 'breadcrumb' => 'Master Data / Material'])
@section('content')
    <x-card title="Tambah material">
        <form method="POST" action="{{ route('materials.store') }}" enctype="multipart/form-data" class="max-w-xl space-y-4">
            @csrf @include('materials.form', ['material' => null])<x-button type="submit">Simpan</x-button></form>
    </x-card>
@endsection
