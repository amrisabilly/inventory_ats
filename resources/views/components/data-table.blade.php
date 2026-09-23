@props(['id', 'ajax', 'columns' => [], 'pageLength' => 10])

<div class="overflow-x-auto">
    <table id="{{ $id }}" class="w-full" style="width: 100%">
        <thead>
            <tr>
                @foreach ($columns as $column)
                    <th>{{ $column['title'] ?? '' }}</th>
                @endforeach
            </tr>
        </thead>
    </table>
</div>

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            window.initDataTable('#{{ $id }}', {
                ajax: @json($ajax),
                pageLength: {{ $pageLength }},
                columns: @json($columns),
            });
        });
    </script>
@endpush
