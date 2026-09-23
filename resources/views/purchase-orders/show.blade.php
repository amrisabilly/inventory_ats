@extends('layouts.app', ['title' => 'Detail Purchase Order', 'breadcrumb' => 'Pengadaan'])
@section('content')
    <x-card title="{{ $purchaseOrder->nomor_po }}">
        <div class="mb-5 flex flex-wrap items-center gap-3">
            <x-badge>{{ str_replace('_', ' ', $purchaseOrder->status_po) }}</x-badge><span
                class="text-sm text-text-secondary">{{ $purchaseOrder->tanggal_po?->format('d/m/Y') }}</span></div>
        @if (auth()->user()->role === 'manajer' && $purchaseOrder->status_po === 'diajukan')
            <div class="mb-5 flex gap-3">
                <form method="POST" action="{{ route('purchase-orders.approve', $purchaseOrder) }}">@csrf<x-button
                        type="submit">Approve PO</x-button></form><button type="button"
                    class="js-reject-trigger rounded-lg border border-danger px-4 py-2 text-sm font-medium text-danger"
                    data-target="#reject-modal">Reject PO</button>
            </div>
        @endif
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm">
                <thead>
                    <tr class="border-b border-border">
                        <th class="px-3 py-2">Material</th>
                        <th class="px-3 py-2">Diajukan</th>
                        <th class="px-3 py-2">Diterima</th>
                        <th class="px-3 py-2">Cacat</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($purchaseOrder->detailPos as $detail)
                        <tr class="border-b border-border">
                            <td class="px-3 py-2">{{ $detail->material->nama_material }}</td>
                            <td class="px-3 py-2">{{ $detail->jumlah_material }}</td>
                            <td class="px-3 py-2">{{ $detail->jumlah_diterima ?? '-' }}</td>
                            <td class="px-3 py-2">{{ $detail->jumlah_cacat }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @if (auth()->user()->role === 'staff_workshop' && $purchaseOrder->status_po === 'approved')
            <form method="POST" action="{{ route('purchase-orders.terima-material', $purchaseOrder) }}"
                class="mt-6 space-y-4">@csrf @foreach ($purchaseOrder->detailPos as $index => $detail)
                    <div class="grid gap-3 rounded-lg border border-border p-4 md:grid-cols-4"><input type="hidden"
                            name="items_diterima[{{ $index }}][detail_po_id]" value="{{ $detail->id }}">
                        <div><label
                                class="mb-1 block text-xs font-medium">{{ $detail->material->nama_material }}</label><input
                                class="form-input" type="number" min="0" max="{{ $detail->jumlah_material }}"
                                name="items_diterima[{{ $index }}][jumlah_diterima]"
                                value="{{ $detail->jumlah_material }}" required></div>
                        <div><label class="mb-1 block text-xs font-medium">Jumlah cacat</label><input class="form-input"
                                type="number" min="0" max="{{ $detail->jumlah_material }}"
                                name="items_diterima[{{ $index }}][jumlah_cacat]" value="0"></div>
                        <div class="md:col-span-2"><label class="mb-1 block text-xs font-medium">Keterangan
                                cacat</label><input class="form-input"
                                name="items_diterima[{{ $index }}][keterangan_cacat]"></div>
                    </div>
                @endforeach
                <x-button type="submit">Simpan penerimaan</x-button>
            </form>
        @endif
    </x-card>
    @if (auth()->user()->role === 'manajer' && $purchaseOrder->status_po === 'diajukan')
        <div id="reject-modal" class="fixed inset-0 z-50 hidden bg-slate-900/40 p-4">
            <div class="mx-auto mt-24 max-w-lg rounded-xl bg-white p-6">
                <div class="mb-4 flex justify-between">
                    <h2 class="font-semibold">Alasan penolakan</h2><button type="button"
                        class="js-modal-close text-text-secondary" data-target="#reject-modal">Tutup</button>
                </div>
                <form method="POST" action="{{ route('purchase-orders.reject', $purchaseOrder) }}">@csrf
                    <textarea class="form-input mb-4" name="catatan_penolakan" rows="4" required></textarea><x-button type="submit" variant="danger">Tolak PO</x-button>
                </form>
            </div>
        </div>
    @endif
@endsection
