<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Data Role</title>
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
    <h1>Data Role</h1>
    <p>Kelola daftar hak akses pengguna.</p>

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

    <a href="{{ route('role.create') }}" class="btn primary">
        + Tambah Role
    </a>

    <table>
        <thead>
            <tr>
                <th>ID Role</th>
                <th>Nama Role</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($roles as $role)
                <tr>
                    <td>{{ $role->idrole }}</td>
                    <td>{{ $role->nama_role }}</td>
                    <td>
                        <div class="actions">
                            <a href="{{ route('role.edit', $role->idrole) }}"
                               class="btn warning">Edit</a>

                            <form action="{{ route('role.destroy', $role->idrole) }}"
                                  method="POST"
                                  onsubmit="return confirm('Yakin ingin menghapus role ini?')">
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
                    <td colspan="3">Belum ada data role.</td>
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
