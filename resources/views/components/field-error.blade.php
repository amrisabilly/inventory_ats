@props(['field'])

@error($field)
    <p class="mt-1 text-xs text-danger">{{ $message }}</p>
@enderror
