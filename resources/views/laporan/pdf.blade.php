<!doctype html>
<html>

<head>
    <meta charset="utf-8">
    <title>Laporan {{ $jenis }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px
        }

        table {
            border-collapse: collapse;
            width: 100%
        }

        th,
        td {
            border: 1px solid #ddd;
            padding: 6px;
            text-align: left
        }
    </style>
</head>

<body>
    <h1>Laporan {{ ucfirst($jenis) }}</h1>
    <table>
        @if ($jenis === 'po')
            <thead>
                <tr>
                    <th>Nomor PO</th>
                    <th>Pengaju</th>
                    <th>Status</th>
                    <th>Tanggal PO</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($data as $item)
                    <tr>
                        <td>{{ $item->nomor_po }}</td>
                        <td>{{ $item->user?->nama ?? '-' }}</td>
                        <td>{{ $item->status_po }}</td>
                        <td>{{ $item->tanggal_po?->format('d/m/Y') }}</td>
                    </tr>
                @endforeach
            </tbody>
        @elseif ($jenis === 'opname')
            <thead>
                <tr>
                    <th>Material</th>
                    <th>Petugas</th>
                    <th>Stok Sistem</th>
                    <th>Stok Fisik</th>
                    <th>Selisih</th>
                    <th>Tanggal</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($data as $item)
                    <tr>
                        <td>{{ $item->material?->nama_material ?? '-' }}</td>
                        <td>{{ $item->user?->nama ?? '-' }}</td>
                        <td>{{ $item->stok_sistem }}</td>
                        <td>{{ $item->stok_fisik }}</td>
                        <td>{{ $item->selisih_stok }}</td>
                        <td>{{ $item->tanggal_opname?->format('d/m/Y') }}</td>
                    </tr>
                @endforeach
            </tbody>
        @else
            <thead>
                <tr>
                    <th>Material</th>
                    <th>Satuan</th>
                    <th>Stok Sistem</th>
                    <th>Stok WIP</th>
                    <th>Dibuat</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($data as $item)
                    <tr>
                        <td>{{ $item->nama_material }}</td>
                        <td>{{ $item->satuan }}</td>
                        <td>{{ $item->stok_sistem }}</td>
                        <td>{{ $item->stok_wip }}</td>
                        <td>{{ $item->created_at?->format('d/m/Y') }}</td>
                    </tr>
                @endforeach
            </tbody>
        @endif
    </table>
</body>

</html>
