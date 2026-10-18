<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Tambah Satuan</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f4f6f9;
            padding: 30px;
            color: #243044;
        }
        .container {
            max-width: 600px;
            margin: auto;
            background: white;
            padding: 25px;
            border-radius: 12px;
        }
        label {
            display: block;
            margin: 16px 0 7px;
            font-weight: bold;
        }
        input, select {
            box-sizing: border-box;
            width: 100%;
            padding: 11px;
            border: 1px solid #cbd5e1;
            border-radius: 6px;
        }
        .btn {
            display: inline-block;
            padding: 10px 15px;
            border: 0;
            border-radius: 6px;
            text-decoration: none;
            cursor: pointer;
        }
        .primary { background: #2563eb; color: white; }
        .secondary { background: #e5e7eb; color: #111827; }
        .error { color: #dc2626; font-size: 14px; }
        .actions { display: flex; gap: 10px; margin-top: 22px; }
    </style>
</head>
<body>
<div class="container">
    <h1>Tambah Satuan</h1>
    <p>Masukkan nama satuan dan statusnya.</p>

    @if ($errors->any())
        <div class="error">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('satuan.store') }}" method="POST">
        @csrf

        <label for="nama_satuan">Nama Satuan</label>
        <input
            type="text"
            id="nama_satuan"
            name="nama_satuan"
            value="{{ old('nama_satuan') }}"
            placeholder="Contoh: Pcs"
            maxlength="100"
            required
        >

        <label for="status">Status</label>
        <select id="status" name="status" required>
            <option value="1"
                {{ old('status', '1') == '1' ? 'selected' : '' }}>
                Aktif
            </option>
            <option value="0"
                {{ old('status') === '0' ? 'selected' : '' }}>
                Tidak Aktif
            </option>
        </select>

        <div class="actions">
            <button type="submit" class="btn primary">Simpan</button>
            <a href="{{ route('satuan.index') }}" class="btn secondary">
                Kembali
            </a>
        </div>
    </form>
</div>
</body>
</html>
