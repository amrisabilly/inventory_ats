@extends('layouts.app', ['title' => 'Detail BOM', 'breadcrumb' => 'Master Data / Produk'])

@section('content')
    <div class="mb-5 flex items-center justify-between">
        <div>
            <h2 class="text-xl font-semibold">{{ $produk->nama_produk }}</h2>
            <p class="mt-1 text-sm text-text-secondary">Bill of Material / perencanaan produksi</p>
        </div>
        <a href="{{ route('produk.edit', $produk) }}"><x-button variant="secondary">Edit BOM</x-button></a>
    </div>
    @foreach ($produk->boms as $bom)
        <x-card title="{{ $bom->nama_bom }}" class="mb-5">
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm">
                    <thead>
                        <tr class="border-b border-border">
                            <th class="px-3 py-3">Produk</th>
                            <th class="px-3 py-3">Material</th>
                            <th class="px-3 py-3">Kebutuhan per unit</th>
                            <th class="px-3 py-3">Satuan</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($bom->detailBoms as $detail)
                            <tr class="border-b border-border">
                                <td class="px-3 py-3 font-medium">{{ $produk->nama_produk }}</td>
                                <td class="px-3 py-3">{{ $detail->material->nama_material }}</td>
                                <td class="px-3 py-3">{{ $detail->jumlah_kebutuhan }}</td>
                                <td class="px-3 py-3">{{ $detail->material->satuan }}</td>
                        </tr>@empty<tr>
                                <td colspan="4" class="px-3 py-6 text-center text-text-secondary">Belum ada detail
                                    material.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </x-card>
    @endforeach
@endsection
