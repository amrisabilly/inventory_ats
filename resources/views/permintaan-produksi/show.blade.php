@extends('layouts.app', ['title' => 'Detail Permintaan Produksi', 'breadcrumb' => 'Produksi'])
@section('content')
    <x-card title="Detail permintaan">
        <dl class="grid max-w-xl gap-4 sm:grid-cols-2">
            <div>
                <dt class="text-sm text-text-secondary">Produk</dt>
                <dd class="font-medium">{{ $permintaanProduksi->produk->nama_produk }}</dd>
            </div>
            <div>
                <dt class="text-sm text-text-secondary">Jumlah</dt>
                <dd class="font-medium">{{ $permintaanProduksi->jumlah_produksi }}</dd>
            </div>
            <div>
                <dt class="text-sm text-text-secondary">Status</dt>
                <dd><x-badge>{{ str_replace('_', ' ', $permintaanProduksi->status_permintaan) }}</x-badge></dd>
            </div>
            <div>
                <dt class="text-sm text-text-secondary">Tanggal</dt>
                <dd class="font-medium">{{ $permintaanProduksi->tanggal_permintaan?->format('d/m/Y') }}</dd>
            </div>
        </dl>
        <div class="mt-6 flex flex-wrap gap-3">
            @if (auth()->user()->role === 'admin' && $permintaanProduksi->status_permintaan === 'pending')
                <form method="POST" action="{{ route('permintaan-produksi.proses', $permintaanProduksi) }}">@csrf<x-button
                        type="submit">Proses permintaan</x-button></form>
                @endif @if (auth()->user()->role === 'staff_workshop' && $permintaanProduksi->status_permintaan === 'pending')
                    <form method="POST" action="{{ route('permintaan-produksi.mulai', $permintaanProduksi) }}">
                        @csrf<x-button type="submit">Mulai produksi</x-button></form>
                    @endif @if (auth()->user()->role === 'staff_workshop' && $permintaanProduksi->status_permintaan === 'work_in_process')
                        <form method="POST" action="{{ route('permintaan-produksi.selesai', $permintaanProduksi) }}">
                            @csrf<x-button type="submit">Selesaikan produksi</x-button></form>
                    @endif
        </div>
    </x-card>
@endsection
