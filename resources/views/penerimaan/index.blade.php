<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Data Penerimaan</title>

    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 30px;
            background: #f5f6fa;
        }

        h1 {
            color: #333;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            background: white;
        }

        th, td {
            padding: 12px;
            border: 1px solid #ddd;
            text-align: left;
        }

        th {
            background: #2860ef;
            color: white;
        }

        .button {
            display: inline-block;
            padding: 10px 15px;
            background: #2860ef;
            color: white;
            text-decoration: none;
            border-radius: 5px;
        }

        .success {
            padding: 12px;
            margin: 15px 0;
            background: #d4edda;
            color: #155724;
        }
    </style>
</head>
<body>

    <h1>Data Penerimaan</h1>

    <a href="{{ route('dashboard') }}">Kembali ke Dashboard</a>

    <br><br>

    <a href="{{ route('penerimaan.create') }}" class="button">
        + Tambah Penerimaan
    </a>

    @if (session('success'))
        <div class="success">
            {{ session('success') }}
        </div>
    @endif

    <br><br>

    <table>
        <thead>
            <tr>
                <th>ID Penerimaan</th>
                <th>Tanggal</th>
                <th>ID Pengadaan</th>
                <th>Petugas</th>
                <th>Total Pengadaan</th>
                <th>Status</th>
            </tr>
        </thead>

        <tbody>
            @forelse ($penerimaan as $item)
                <tr>
                    <td>{{ $item->idpenerimaan }}</td>

                    <td>{{ $item->created_at }}</td>

                    <td>{{ $item->idpengadaan }}</td>

                    <td>
                        {{ $item->pengadaan->vendor->nama_vendor ?? '-' }}
                    </td>

                    <td>
                        @foreach ($item->detail as $detail)
                            {{ $detail->barang->nama ?? '-' }}
                            — {{ $detail->jumlah_terima }}
                            <br>
                        @endforeach
                    </td>

                    <td>{{ $item->status }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" style="text-align: center;">
                        Belum ada data penerimaan.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>

</body>
</html>
