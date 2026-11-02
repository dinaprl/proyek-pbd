<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Data Pengadaan</title>

    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 30px;
            background: #f5f6fa;
        }

        h1 {
            color: #333;
        }

        .button {
            display: inline-block;
            padding: 10px 15px;
            margin-bottom: 20px;
            background: #2860ef;
            color: white;
            text-decoration: none;
            border-radius: 5px;
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

        .success {
            padding: 12px;
            margin-bottom: 15px;
            background: #d4edda;
            color: #155724;
        }
    </style>
</head>
<body>

    <h1>Data Pengadaan</h1>

    <a href="{{ route('dashboard') }}">Kembali ke Dashboard</a>

    <br><br>

    <a href="{{ route('pengadaan.create') }}" class="button">
        + Tambah Pengadaan
    </a>

    @if (session('success'))
        <div class="success">
            {{ session('success') }}
        </div>
    @endif

    <table>
        <thead>
            <tr>
                <th>ID Pengadaan</th>
                <th>Tanggal</th>
                <th>Vendor</th>
                <th>Subtotal</th>
                <th>PPN</th>
                <th>Total</th>
                <th>Status</th>
            </tr>
        </thead>

        <tbody>
            @forelse ($pengadaan as $item)
                <tr>
                    <td>{{ $item->idpengadaan }}</td>

                    <td>{{ $item->timestamp }}</td>

                    <td>
                        {{ $item->vendor->nama_vendor ?? '-' }}
                    </td>

                    <td>
                        Rp {{ number_format($item->subtotal_nilai, 0, ',', '.') }}
                    </td>

                    <td>
                        Rp {{ number_format($item->ppn, 0, ',', '.') }}
                    </td>

                    <td>
                        Rp {{ number_format($item->total_nilai, 0, ',', '.') }}
                    </td>

                    <td>{{ $item->status }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" style="text-align: center;">
                        Belum ada data pengadaan.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>

</body>
</html>
