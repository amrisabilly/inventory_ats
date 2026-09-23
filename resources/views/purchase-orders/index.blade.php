@extends('layouts.app', ['title' => 'Purchase Order', 'breadcrumb' => 'Pengadaan'])
@section('content')
    <div class="mb-5">
        <h2 class="text-xl font-semibold">Purchase Order</h2>
        <p class="mt-1 text-sm text-text-secondary">PO dibuat otomatis saat stok material tidak mencukupi kebutuhan BOM.</p>
    </div><x-card><x-data-table id="purchase-orders-table" :ajax="route('purchase-orders.data')" :columns="[
        ['data' => 'nomor_po', 'title' => 'Nomor PO'],
        ['data' => 'pengaju', 'title' => 'Pengaju'],
        ['data' => 'status_po', 'title' => 'Status'],
        ['data' => 'tanggal_po', 'title' => 'Tanggal'],
        ['data' => 'aksi', 'title' => 'Aksi', 'orderable' => false, 'searchable' => false],
    ]" /></x-card>
@endsection
