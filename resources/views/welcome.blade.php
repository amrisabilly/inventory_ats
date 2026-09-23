@extends('layouts.app', ['title' => 'Dashboard', 'breadcrumb' => 'Overview'])

@section('content')
    <div class="mb-8 flex flex-col justify-between gap-4 sm:flex-row sm:items-end">
        <div>
            <p class="mb-2 text-sm font-medium text-secondary">Selamat datang di Inventory ATS</p>
            <h2 class="text-2xl font-semibold tracking-tight">Ringkasan operasional</h2>
            <p class="mt-1 text-sm text-text-secondary">Pantau material, stok, dan purchase order dalam satu tempat.</p>
        </div>
        <x-button>Tambah data</x-button>
    </div>

    <div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
        @foreach ([['label' => 'Total Material', 'value' => '0', 'class' => 'text-primary'], ['label' => 'Stok Kritis', 'value' => '0', 'class' => 'text-danger'], ['label' => 'PO Menunggu', 'value' => '0', 'class' => 'text-warning'], ['label' => 'Produksi Aktif', 'value' => '0', 'class' => 'text-info']] as $stat)
            <x-card>
                <p class="text-sm text-text-secondary">{{ $stat['label'] }}</p>
                <p class="mt-3 text-3xl font-semibold {{ $stat['class'] }}">{{ $stat['value'] }}</p>
                <p class="mt-2 text-xs text-text-secondary">Belum ada data</p>
            </x-card>
        @endforeach
    </div>

    <div class="mt-6"><x-card title="Aktivitas terbaru"
            description="Data akan tampil setelah modul transaksi mulai digunakan.">
            <div class="flex min-h-40 items-center justify-center text-sm text-text-secondary">Belum ada aktivitas terbaru.
            </div>
        </x-card></div>
@endsection
