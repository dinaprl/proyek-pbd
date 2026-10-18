<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Data Barang</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f4f6f9;
            margin: 0;
            padding: 30px;
            color: #243044;
        }
        .container {
            max-width: 1100px;
            margin: auto;
            background: white;
            padding: 25px;
            border-radius: 12px;
        }
        h1 { margin-top: 0; }
        .btn {
            display: inline-block;
            padding: 9px 13px;
            border: 0;
            border-radius: 6px;
            text-decoration: none;
            cursor: pointer;
            font-size: 14px;
        }
        .primary { background: #2563eb; color: white; }
        .warning { background: #facc15; color: #111827; }
        .danger { background: #dc2626; color: white; }
        .success {
            background: #dcfce7;
            color: #166534;
            padding: 12px;
            border-radius: 6px;
            margin: 15px 0;
        }
        .table-wrap { overflow-x: auto; margin-top: 20px; }
        table { width: 100%; border-collapse: collapse; }
        th, td {
            padding: 12px;
            border-bottom: 1px solid #e5e7eb;
            text-align: left;
        }
        th { background: #f8fafc; }
        .actions { display: flex; gap: 6px; }
        form { margin: 0; }
    </style>
</head>
<body>
<div class="container">
    <h1>Data Barang</h1>
    <p>Kelola data barang yang tersedia.</p>

    @if (session('success'))
        <div class="success">{{ session('success') }}</div>
    @endif

    @if ($errors->any())
        <div class="success" style="background:#fee2e2;color:#991b1b">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <a href="{{ route('barang.create') }}" class="btn primary">
        + Tambah Barang
    </a>

    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Jenis</th>
                    <th>Nama Barang</th>
                    <th>Satuan</th>
                    <th>Status</th>
                    <th>Harga</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($barangs as $barang)
                    <tr>
                        <td>{{ $barang->idbarang }}</td>
                        <td>{{ $barang->jenis }}</td>
                        <td>{{ $barang->nama }}</td>
                        <td>{{ $barang->nama_satuan ?? '-' }}</td>
                        <td>
                            {{ $barang->status == 1 ? 'Aktif' : 'Tidak Aktif' }}
                        </td>
                        <td>Rp {{ number_format($barang->harga, 0, ',', '.') }}</td>
                        <td>
                            <div class="actions">
                                <a href="{{ route('barang.edit', $barang->idbarang) }}"
                                   class="btn warning">Edit</a>

                                <form action="{{ route('barang.destroy', $barang->idbarang) }}"
                                      method="POST"
                                      onsubmit="return confirm('Yakin ingin menghapus barang ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn danger">
                                        Hapus
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7">Belum ada data barang.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
</body>
</html>
<a href="{{ url('/dashboard') }}" class="back-link">
    ← Kembali ke Beranda
</a>
