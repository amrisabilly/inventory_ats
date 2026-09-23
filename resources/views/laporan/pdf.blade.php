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
        <thead>
            <tr>
                <th>ID</th>
                <th>Data</th>
                <th>Dibuat</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($data as $item)
                <tr>
                    <td>{{ $item->id }}</td>
                    <td>{{ $item->nama_material ?? ($item->nomor_po ?? ($item->material?->nama_material ?? '-')) }}</td>
                    <td>{{ $item->created_at }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>

</html>
