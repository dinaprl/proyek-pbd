<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Data Margin Penjualan</title>
    <style>
        body { font-family: Arial, sans-serif; background: #f4f6f9; margin: 0; padding: 30px; color: #243044; }
        .container { max-width: 1100px; margin: auto; background: white; padding: 25px; border-radius: 12px; }
        h1 { margin-top: 0; }
        .btn { display: inline-block; padding: 9px 13px; border: 0; border-radius: 6px; text-decoration: none; cursor: pointer; font-size: 14px; }
        .primary { background: #2563eb; color: white; }
        .warning { background: #facc15; color: #111827; }
        .danger { background: #dc2626; color: white; }
        .success { background: #dcfce7; color: #166534; padding: 12px; border-radius: 6px; margin: 15px 0; }
        .table-wrap { overflow-x: auto; margin-top: 20px; }
        table { width: 100%; border-collapse: collapse; }
        th, td { padding: 12px; border-bottom: 1px solid #e5e7eb; text-align: left; }
        th { background: #f8fafc; }
        .actions { display: flex; gap: 6px; }
        form { margin: 0; }
    </style>
</head>
<body>
<div class="container">
    <h1>Data Margin Penjualan</h1>
    <p>Kelola persentase margin penjualan.</p>

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

    <a href="{{ route('margin_penjualan.create') }}" class="btn primary">
        + Tambah Margin
    </a>

    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Persentase</th>
                    <th>Status</th>
                    <th>Pengguna</th>
                    <th>Dibuat</th>
                    <th>Diperbarui</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($margins as $margin)
                    <tr>
                        <td>{{ $margin->idmargin_penjualan }}</td>
                        <td>{{ $margin->persen }}%</td>
                        <td>{{ $margin->status_deskripsi == 1 ? 'Aktif' : 'Tidak Aktif' }}</td>
                        <td>{{ $margin->username ?? 'Pengguna tidak ditemukan' }}</td>
                        <td>{{ $margin->created_at ?? '-' }}</td>
                        <td>{{ $margin->updated_at ?? '-' }}</td>
                        <td>
                            <div class="actions">
                                <a href="{{ route('margin_penjualan.edit', $margin->idmargin_penjualan) }}"
                                   class="btn warning">Edit</a>

                                <form action="{{ route('margin_penjualan.destroy', $margin->idmargin_penjualan) }}"
                                      method="POST"
                                      onsubmit="return confirm('Yakin ingin menghapus data margin ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn danger">Hapus</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7">Belum ada data margin penjualan.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <p><a href="{{ url('/') }}">← Kembali ke Beranda</a></p>
</div>
</body>
</html>
<a href="{{ url('/dashboard') }}" class="back-link">
    ← Kembali ke Beranda
</a>
