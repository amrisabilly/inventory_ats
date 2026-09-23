@extends('layouts.app', ['title' => 'Edit Material', 'breadcrumb' => 'Master Data / Material'])
@section('content')
    <x-card title="Edit material">
        <form method="POST" action="{{ route('materials.update', $material) }}" enctype="multipart/form-data"
            class="max-w-xl space-y-4">@csrf @method('PUT') @include('materials.form', compact('material'))<x-button type="submit">Simpan
                perubahan</x-button></form>
    </x-card>
@endsection
