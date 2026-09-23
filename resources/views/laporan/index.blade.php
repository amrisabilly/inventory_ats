@extends('layouts.app', ['title' => 'Laporan', 'breadcrumb' => 'Laporan'])
@section('content')
    <div class="mb-5">
        <h2 class="text-xl font-semibold">Laporan</h2>
        <form method="GET" class="mt-4 grid gap-3 rounded-xl border border-border bg-white p-4 sm:grid-cols-4"><select
                class="form-input" name="jenis">
                <option value="material" @selected($jenis === 'material')>Material</option>
                <option value="stok" @selected($jenis === 'stok')>Stok</option>
                <option value="opname" @selected($jenis === 'opname')>Stok opname</option>
                <option value="po" @selected($jenis === 'po')>Purchase order</option>
            </select><input class="form-input" type="date" name="dari" value="{{ $dari }}"><input
                class="form-input" type="date" name="sampai" value="{{ $sampai }}">
            <div class="flex gap-2"><x-button type="submit">Filter</x-button><a
                    href="{{ route('laporan.cetak-pdf', ['jenis' => $jenis, 'dari' => $dari, 'sampai' => $sampai]) }}"><x-button
                        type="button" variant="secondary">Cetak PDF</x-button></a></div>
        </form>
    </div>
    <x-card>
        @if ($jenis === 'po')
            <x-data-table id="laporan-table" :ajax="route('laporan.data', ['jenis' => $jenis, 'dari' => $dari, 'sampai' => $sampai])" :columns="[
                ['data' => 'nomor_po', 'title' => 'Nomor PO'],
                ['data' => 'pengaju', 'title' => 'Pengaju'],
                ['data' => 'status_po', 'title' => 'Status'],
                ['data' => 'tanggal_po', 'title' => 'Tanggal PO'],
            ]" />
        @elseif ($jenis === 'opname')
            <x-data-table id="laporan-table" :ajax="route('laporan.data', ['jenis' => $jenis, 'dari' => $dari, 'sampai' => $sampai])" :columns="[
                ['data' => 'material_nama', 'title' => 'Material'],
                ['data' => 'petugas', 'title' => 'Petugas'],
                ['data' => 'stok_sistem', 'title' => 'Stok Sistem'],
                ['data' => 'stok_fisik', 'title' => 'Stok Fisik'],
                ['data' => 'selisih_stok', 'title' => 'Selisih'],
                ['data' => 'tanggal_opname', 'title' => 'Tanggal'],
            ]" />
        @else
            <x-data-table id="laporan-table" :ajax="route('laporan.data', ['jenis' => $jenis, 'dari' => $dari, 'sampai' => $sampai])" :columns="[
                ['data' => 'nama_material', 'title' => 'Material'],
                ['data' => 'satuan', 'title' => 'Satuan'],
                ['data' => 'stok_sistem', 'title' => 'Stok Sistem'],
                ['data' => 'stok_wip', 'title' => 'Stok WIP'],
                ['data' => 'created_at', 'title' => 'Dibuat'],
            ]" />
        @endif
    </x-card>
@endsection
