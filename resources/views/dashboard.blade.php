@extends('layouts.app', ['title' => 'Dashboard', 'breadcrumb' => 'Overview'])

@section('content')
    <div class="mb-8">
        <p class="text-sm font-medium text-secondary">Ringkasan operasional</p>
        <h2 class="mt-1 text-2xl font-semibold">Dashboard {{ ucfirst(str_replace('_', ' ', $role)) }}</h2>
    </div>
    <div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
        @foreach ([['label' => 'Total Material', 'value' => $totalMaterial, 'class' => 'text-primary'], ['label' => 'Stok Kritis', 'value' => $stokKritis, 'class' => 'text-danger'], ['label' => 'PO Menunggu', 'value' => $poMenunggu, 'class' => 'text-warning'], ['label' => 'Produksi Aktif', 'value' => $produksiAktif, 'class' => 'text-info']] as $stat)
            <x-card>
                <p class="text-sm text-text-secondary">{{ $stat['label'] }}</p>
                <p class="mt-3 text-3xl font-semibold {{ $stat['class'] }}">{{ $stat['value'] }}</p>
            </x-card>
        @endforeach
    </div>
    <div class="mt-6"><x-card title="Akses cepat">
            <div class="flex flex-wrap gap-3">
                @if ($role === 'manajer')
                    <a href="{{ route('permintaan-produksi.create') }}"><x-button>Buat permintaan produksi</x-button></a><a
                        href="{{ route('purchase-orders.index') }}"><x-button variant="secondary">Review purchase
                            order</x-button></a>
                @elseif($role === 'admin')
                    <a href="{{ route('materials.create') }}"><x-button>Tambah material</x-button></a><a
                        href="{{ route('purchase-orders.create') }}"><x-button variant="secondary">Ajukan purchase
                        order</x-button></a>@else<a href="{{ route('stok-opname.create') }}"><x-button>Catat stok
                            opname</x-button></a><a href="{{ route('permintaan-produksi.index') }}"><x-button
                            variant="secondary">Lihat produksi</x-button></a>
                @endif
            </div>
        </x-card></div>
@endsection
