<div class="flex items-center gap-2">
    @isset($showUrl)
        <a href="{{ $showUrl }}" class="text-xs font-medium text-info hover:underline">Lihat</a>
    @endisset
    @isset($editUrl)
        <a href="{{ $editUrl }}" class="text-xs font-medium text-primary hover:underline">Edit</a>
    @endisset
    @isset($deleteUrl)
        <form method="POST" action="{{ $deleteUrl }}" onsubmit="return confirm('Hapus data ini?')">
            @csrf @method('DELETE')
            <button class="text-xs font-medium text-danger hover:underline">Hapus</button>
        </form>
    @endisset
</div>
