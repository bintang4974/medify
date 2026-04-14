<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Kategori {{ $data->nama }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            color: #333;
        }
        h1 { font-size: 18px; margin-bottom: 5px; }
        .info-table td { padding: 3px 8px 3px 0; }
        .info-table th { padding: 3px 8px 3px 0; text-align: left; }
        .items-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        .items-table th, .items-table td {
            border: 1px solid #ccc;
            padding: 6px 8px;
            text-align: left;
        }
        .items-table th {
            background-color: #f0f0f0;
            font-weight: bold;
        }
        .items-table tr:nth-child(even) { background-color: #fafafa; }
        .footer {
            position: fixed;
            bottom: 20px;
            left: 0;
            right: 0;
            text-align: center;
            font-size: 10px;
            color: #888;
            border-top: 1px solid #ddd;
            padding-top: 6px;
        }
        .header-section { margin-bottom: 20px; border-bottom: 2px solid #333; padding-bottom: 10px; }
    </style>
</head>
<body>
    <div class="header-section">
        <h1>Data Kategori Item</h1>
        <table class="info-table">
            <tr>
                <th>Nama Kategori</th>
                <td>: {{ $data->nama }}</td>
            </tr>
            <tr>
                <th>Kode Kategori</th>
                <td>: {{ $data->kode }}</td>
            </tr>
        </table>
    </div>

    <h3>Daftar Item dalam Kategori ini</h3>

    @if($data->masterItems->isEmpty())
        <p><em>Tidak ada item dalam kategori ini.</em></p>
    @else
    <table class="items-table">
        <thead>
            <tr>
                <th>No</th>
                <th>Kode</th>
                <th>Nama Item</th>
                <th>Harga Beli</th>
                <th>Laba</th>
                <th>Harga Jual</th>
                <th>Supplier</th>
                <th>Jenis</th>
            </tr>
        </thead>
        <tbody>
            @foreach($data->masterItems as $i => $item)
            <tr>
                <td>{{ $i + 1 }}</td>
                <td>{{ $item->kode }}</td>
                <td>{{ $item->nama }}</td>
                <td>Rp {{ number_format($item->harga_beli, 0, ',', '.') }}</td>
                <td>{{ $item->laba }}%</td>
                <td>Rp {{ number_format(round($item->harga_beli + $item->harga_beli * $item->laba / 100), 0, ',', '.') }}</td>
                <td>{{ $item->supplier }}</td>
                <td>{{ $item->jenis }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @endif

    <div class="footer">
        Dicetak pada: {{ $printed_at->format('d/m/Y H:i:s') }} &nbsp;|&nbsp; Medify - Kategori Item Report
    </div>
</body>
</html>
