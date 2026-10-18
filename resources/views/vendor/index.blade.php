
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Data Vendor</title>

    <style>
        * { box-sizing: border-box; }

        body {
            margin: 0;
            background: #f3f5f9;
            color: #273449;
            font-family: Arial, Helvetica, sans-serif;
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
            letter-spacing: -1px;
        }

        .description {
            margin: 0 0 26px;
            font-size: 20px;
        }

        .btn {
            display: inline-block;
            border: 0;
            border-radius: 9px;
            padding: 12px 19px;
            font-size: 17px;
            text-decoration: none;
            cursor: pointer;
            font-family: inherit;
        }

        .btn-add {
            background: #2860ef;
            color: white;
            margin-bottom: 30px;
            padding: 14px 20px;
        }

        .table-wrapper { overflow-x: auto; }

        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 18px;
            background: white;
        }

        thead { background: #f6f8fb; }

        th, td {
            padding: 18px;
            text-align: left;
            border-bottom: 1px solid #dfe3eb;
        }

        th {
            font-size: 19px;
            font-weight: 600;
        }

        tbody tr:hover { background: #fafbfe; }

        .btn-edit {
            background: #ffca05;
            color: #111827;
        }

        .btn-delete {
            background: #e92329;
            color: white;
        }

        .action-buttons {
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .success {
            background: #e4f8eb;
            color: #176b37;
            padding: 14px 18px;
            margin-bottom: 22px;
            border-radius: 8px;
        }

        .errors {
            color: #a61b1b;
            background: #fde8e8;
            padding: 12px 20px;
            border-radius: 8px;
            margin-bottom: 20px;
        }

        .back-link {
            display: inline-block;
            margin-top: 24px;
            color: #6415b5;
            font-size: 19px;
            text-decoration: underline;
        }

        .empty {
            text-align: center;
            color: #777;
            padding: 30px;
        }

        @media (max-width: 768px) {
            .container { padding: 24px 18px; }
            h1 { font-size: 34px; }
            th, td { padding: 13px; }
        }
    </style>
</head>

<body>
<main class="container">
    <h1>Data Vendor</h1>

    <p class="description">
        Kelola daftar vendor dan informasi badan hukumnya.
    </p>

    @if (session('success'))
        <div class="success">{{ session('success') }}</div>
    @endif

    @if ($errors->any())
        <div class="errors">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <a href="{{ route('vendor.create') }}" class="btn btn-add">
        + Tambah Vendor
    </a>

    <div class="table-wrapper">
        <table>
            <thead>
                <tr>
                    <th>ID Vendor</th>
                    <th>Nama Vendor</th>
                    <th>Badan Hukum</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>

            <tbody>
                @forelse ($vendors as $vendor)
                    <tr>
                        <td>{{ $vendor->idvendor }}</td>
                        <td>{{ $vendor->nama_vendor }}</td>
                        <td>
                            @switch($vendor->badan_hukum_deskripsi)
                                @case('P')
                                    Perseroan Terbatas (PT)
                                    @break
                                @case('C')
                                    Commanditaire Vennootschap (CV)
                                    @break
                                @case('U')
                                    Usaha Dagang (UD)
                                    @break
                                @default
                                    {{ $vendor->badan_hukum_deskripsi }}
                            @endswitch
                        </td>
                        <td>
                            @if ($vendor->status_deskripsi === 'A')
                                Aktif
                            @else
                                {{ $vendor->status_deskripsi }}
                            @endif
                        </td>
                        <td>
                            <div class="action-buttons">
                                <a
                                    href="{{ route('vendor.edit', $vendor->idvendor) }}"
                                    class="btn btn-edit">
                                    Edit
                                </a>

                                <form
                                    action="{{ route('vendor.destroy', $vendor->idvendor) }}"
                                    method="POST"
                                    onsubmit="return confirm('Yakin ingin menghapus vendor ini?')">
                                    @csrf
                                    @method('DELETE')

                                    <button type="submit" class="btn btn-delete">
                                        Hapus
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="empty">
                            Belum ada data vendor.
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
