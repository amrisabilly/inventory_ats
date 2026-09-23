@php
    $variant = match ($status) {
        'approved', 'selesai', 'pending' => 'success',
        'rejected', 'material_po_cacat' => 'danger',
        'diajukan', 'menunggu_po', 'menunggu_material' => 'warning',
        'work_in_process' => 'info',
        default => 'neutral',
    };
@endphp
<x-badge :variant="$variant">{{ str_replace('_', ' ', ucfirst($status)) }}</x-badge>
