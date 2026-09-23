@extends('layouts.app', ['title' => 'Edit User', 'breadcrumb' => 'Administrasi / User'])
@section('content')
    <x-card title="Edit user">
        <form method="POST" action="{{ route('users.update', $user) }}" enctype="multipart/form-data"
            class="max-w-xl space-y-4">@csrf @method('PUT') @include('users.form', compact('user'))<x-button type="submit">Simpan
                perubahan</x-button></form>
    </x-card>
@endsection
