<div class="flex items-center gap-2">
    @if (!empty($processUrl))
        <a href="{{ $processUrl }}" class="text-xs font-medium text-primary hover:underline">Proses</a>
    @elseif (isset($showUrl))
        <a href="{{ $showUrl }}" class="text-xs font-medium text-info hover:underline">Lihat</a>
    @endif
    @if (!empty($editUrl))
        <a href="{{ $editUrl }}" class="text-xs font-medium text-primary hover:underline">Edit</a>
    @endif
    @isset($deleteUrl)
        <form method="POST" action="{{ $deleteUrl }}" onsubmit="return confirm('Hapus data ini?')">
            @csrf @method('DELETE')
            <button class="text-xs font-medium text-danger hover:underline">Hapus</button>
        </form>
    @endisset
</div>
