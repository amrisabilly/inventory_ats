@extends('layouts.guest', ['title' => 'Masuk'])

@section('content')
    <div class="rounded-xl bg-white p-8 shadow-panel">
        <div class="mb-8">
            <p class="text-sm font-semibold text-secondary">Inventory ATS</p>
            <h1 class="mt-2 text-2xl font-semibold">Masuk ke sistem</h1>
            <p class="mt-1 text-sm text-text-secondary">Gunakan username dan password akun kamu.</p>
        </div>
        @if ($errors->any())
            <x-alert type="danger" class="mb-5">{{ $errors->first() }}</x-alert>
        @endif
        <form method="POST" action="{{ route('login.store') }}" class="space-y-5">
            @csrf
            <div><label class="mb-1 block text-sm font-medium">Username</label><input class="form-input" name="username"
                    value="{{ old('username') }}" required autofocus></div>
            <div><label class="mb-1 block text-sm font-medium">Password</label><input class="form-input" type="password"
                    name="password" required></div>
            <label class="flex items-center gap-2 text-sm text-text-secondary"><input type="checkbox" name="remember"> Ingat
                saya</label>
            <x-button type="submit" class="w-full">Masuk</x-button>
        </form>
    </div>
@endsection
