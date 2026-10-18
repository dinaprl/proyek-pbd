<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Data Satuan</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f4f6f9;
            margin: 0;
            padding: 30px;
            color: #243044;
        }
        .container {
            max-width: 900px;
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
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
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
    <h1>Data Satuan</h1>
    <p>Kelola daftar satuan barang.</p>

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

    <a href="{{ route('satuan.create') }}" class="btn primary">
        + Tambah Satuan
    </a>

    <table>
        <thead>
            <tr>
                <th>ID Satuan</th>
                <th>Nama Satuan</th>
                <th>Status</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($satuans as $satuan)
                <tr>
                    <td>{{ $satuan->idsatuan }}</td>
                    <td>{{ $satuan->nama_satuan }}</td>
                    <td>
                        {{ $satuan->status_deskripsi == 1 ? 'Aktif' : 'Tidak Aktif' }}
                    </td>
                    <td>
                        <div class="actions">
                            <a href="{{ route('satuan.edit', $satuan->idsatuan) }}"
                               class="btn warning">Edit</a>

                            <form action="{{ route('satuan.destroy', $satuan->idsatuan) }}"
                                  method="POST"
                                  onsubmit="return confirm('Yakin ingin menghapus satuan ini?')">
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
                    <td colspan="4">Belum ada data satuan.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <p><a href="{{ url('/') }}">← Kembali ke Beranda</a></p>
</div>
</body>
</html>
<a href="{{ url('/dashboard') }}" class="back-link">
    ← Kembali ke Beranda
</a>
