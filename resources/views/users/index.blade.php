@extends('layouts.app', ['title' => 'User', 'breadcrumb' => 'Administrasi'])
@section('content')
    <div class="mb-5 flex items-center justify-between">
        <h2 class="text-xl font-semibold">Data User</h2><a href="{{ route('users.create') }}"><x-button>Tambah
                user</x-button></a>
    </div>
    <x-card><x-data-table id="users-table" :ajax="route('users.data')" :columns="[
        ['data' => 'nama', 'title' => 'Nama'],
        ['data' => 'username', 'title' => 'Username'],
        ['data' => 'email', 'title' => 'Email'],
        ['data' => 'role', 'title' => 'Role'],
        ['data' => 'aksi', 'title' => 'Aksi', 'orderable' => false, 'searchable' => false],
    ]" /></x-card>
@endsection
