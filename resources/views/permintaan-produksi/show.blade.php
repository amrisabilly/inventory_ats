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
        <div class="mt-8">
            <div class="mb-3 flex items-end justify-between">
                <div>
                    <h3 class="font-semibold">Perhitungan kebutuhan material</h3>
                    <p class="mt-1 text-sm text-text-secondary">BOM per unit dikalikan jumlah produksi, lalu dibandingkan
                        dengan stok sistem.</p>
                </div>
                <span class="text-sm font-medium text-text-secondary">{{ $permintaanProduksi->jumlah_produksi }} unit</span>
            </div>
            @if ($ketersediaan !== [])
                <div class="overflow-x-auto rounded-lg border border-border">
                    <table class="w-full text-left text-sm">
                        <thead class="bg-surface">
                            <tr>
                                <th class="px-3 py-3">Material</th>
                                <th class="px-3 py-3">BOM / unit</th>
                                <th class="px-3 py-3">Total kebutuhan</th>
                                <th class="px-3 py-3">Stok sistem</th>
                                <th class="px-3 py-3">Stok WIP</th>
                                <th class="px-3 py-3">Kekurangan</th>
                                <th class="px-3 py-3">Kondisi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($ketersediaan as $item)
                                @php($bomPerUnit = intdiv($item['dibutuhkan'], max(1, $permintaanProduksi->jumlah_produksi)))
                                <tr class="border-t border-border">
                                    <td class="px-3 py-3 font-medium">{{ $item['material']->nama_material }}</td>
                                    <td class="px-3 py-3">{{ $bomPerUnit }} {{ $item['material']->satuan }}</td>
                                    <td class="px-3 py-3">{{ $item['dibutuhkan'] }} {{ $item['material']->satuan }}</td>
                                    <td class="px-3 py-3">{{ $item['stok_sistem'] }}</td>
                                    <td class="px-3 py-3">{{ $item['stok_wip'] }}</td>
                                    <td
                                        class="px-3 py-3 font-semibold {{ $item['kekurangan'] > 0 ? 'text-danger' : 'text-success' }}">
                                        {{ $item['kekurangan'] }}</td>
                                    <td class="px-3 py-3"><x-badge
                                            :variant="$item['cukup'] ? 'success' : 'danger'">{{ $item['cukup'] ? 'Cukup' : 'Kurang' }}</x-badge></td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <x-alert type="warning">Produk ini belum memiliki BOM, sehingga permintaan belum dapat diproses.</x-alert>
            @endif
        </div>
        <div class="mt-6 flex flex-wrap gap-3">
            @if (auth()->user()->role === 'admin' && $permintaanProduksi->status_permintaan === 'pending')
                <form method="POST" action="{{ route('permintaan-produksi.proses', $permintaanProduksi) }}">@csrf<x-button
                        type="submit">Proses permintaan</x-button></form>
                @endif @if (auth()->user()->role === 'staff_workshop' &&
                        in_array($permintaanProduksi->status_permintaan, ['pending', 'siap_diproduksi'], true))
                    <form method="POST" action="{{ route('permintaan-produksi.mulai', $permintaanProduksi) }}">
                        @csrf<x-button type="submit">Mulai produksi</x-button></form>
                    @endif @if (auth()->user()->role === 'staff_workshop' && $permintaanProduksi->status_permintaan === 'work_in_process')
                        <form method="POST" action="{{ route('permintaan-produksi.selesai', $permintaanProduksi) }}">
                            @csrf<x-button type="submit">Selesaikan produksi</x-button></form>
                    @endif
        </div>
    </x-card>
@endsection
