@php($bom = $produk->boms->first())
@if ($bom)
    <a href="{{ route('produk.show', $produk) }}" class="text-xs font-medium text-info hover:underline">Lihat BOM
        ({{ $bom->detailBoms->count() }} material)</a>
@else
    <span class="text-xs text-text-secondary">Belum ada BOM</span>
@endif
