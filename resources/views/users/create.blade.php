@extends('layouts.app', ['title' => 'Tambah User', 'breadcrumb' => 'Administrasi / User'])
@section('content')
    <x-card title="Tambah user">
        <form method="POST" action="{{ route('users.store') }}" enctype="multipart/form-data" class="max-w-xl space-y-4">@csrf
            @include('users.form', ['user' => null])<x-button type="submit">Simpan</x-button></form>
    </x-card>
@endsection
