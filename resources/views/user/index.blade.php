<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Data User</title>

    <style>
        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            background: #f3f5f9;
            font-family: Arial, Helvetica, sans-serif;
            color: #273449;
        }

        .container {
            min-height: 100vh;
            margin-left: 4px;
            padding: 28px 42px;
            background: #fff;
        }

        h1 {
            margin: 0 0 30px;
            font-size: 44px;
            font-weight: 700;
            letter-spacing: -1px;
        }

        .description {
            margin: 0 0 26px;
            font-size: 20px;
            color: #273449;
        }

        .btn {
            display: inline-block;
            border: none;
            border-radius: 9px;
            padding: 14px 20px;
            font-size: 17px;
            text-decoration: none;
            cursor: pointer;
            font-family: inherit;
        }

        .btn-add {
            background: #2860ef;
            color: #fff;
            margin-bottom: 30px;
        }

        .btn-add:hover {
            background: #174bd0;
        }

        .table-wrapper {
            width: 100%;
            overflow-x: auto;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 18px;
            background: #fff;
        }

        thead {
            background: #f6f8fb;
        }

        th, td {
            padding: 20px 18px;
            text-align: left;
            border-bottom: 1px solid #dfe3eb;
        }

        th {
            font-size: 19px;
            font-weight: 600;
        }

        tbody tr:hover {
            background: #fafbfe;
        }

        .btn-edit {
            background: #ffca05;
            color: #111827;
            margin-right: 5px;
        }

        .btn-delete {
            background: #e92329;
            color: #fff;
        }

        .btn-edit:hover {
            background: #edb900;
        }

        .btn-delete:hover {
            background: #c9181e;
        }

        .action-buttons {
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .action-buttons .btn {
            padding: 12px 19px;
        }

        .back-link {
            display: inline-block;
            margin-top: 24px;
            color: #6415b5;
            font-size: 19px;
            text-decoration: underline;
        }

        .alert-success {
            padding: 14px 18px;
            margin-bottom: 22px;
            border-radius: 8px;
            background: #e4f8eb;
            color: #176b37;
            font-size: 16px;
        }

        .empty {
            text-align: center;
            color: #777;
            padding: 30px;
        }

        @media (max-width: 768px) {
            .container {
                padding: 24px 18px;
            }

            h1 {
                font-size: 34px;
            }

            th, td {
                padding: 14px;
            }
        }
    </style>
</head>

<body>
    <main class="container">
        <h1>Data User</h1>

        <p class="description">
            Kelola daftar pengguna dan role sistem.
        </p>

        @if (session('success'))
            <div class="alert-success">
                {{ session('success') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="alert-success"
                 style="background:#fde8e8;color:#a61b1b">
                <ul style="margin:0;padding-left:20px">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <a href="{{ route('user.create') }}" class="btn btn-add">
            + Tambah User
        </a>

        <div class="table-wrapper">
            <table>
                <thead>
                    <tr>
                        <th>ID User</th>
                        <th>Username</th>
                        <th>ID Role</th>
                        <th>Aksi</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse ($users as $user)
                        <tr>
                            <td>{{ $user->iduser }}</td>
                            <td>{{ $user->username }}</td>
                            <td>{{ $user->nama_role }}</td>
                            <td>
                                <div class="action-buttons">
                                    <a
                                        href="{{ route('user.edit', $user->iduser) }}"
                                        class="btn btn-edit">
                                        Edit
                                    </a>

                                    <form
                                        action="{{ route('user.destroy', $user->iduser) }}"
                                        method="POST"
                                        onsubmit="return confirm('Yakin ingin menghapus user ini?')">
                                        @csrf
                                        @method('DELETE')

                                        <button type="submit"
                                                class="btn btn-delete">
                                            Hapus
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="empty">
                                Belum ada data user.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <a href="{{ url('/dashboard') }}" class="back-link">
            ← Kembali ke Beranda
        </a>
    </main>
</body>
</html>
<a href="{{ url('/dashboard') }}" class="back-link">
    ← Kembali ke Beranda
</a>
