<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Tambah Margin Penjualan</title>
    <style>
        body { font-family: Arial, sans-serif; background: #f4f6f9; padding: 30px; color: #243044; }
        .container { max-width: 600px; margin: auto; background: white; padding: 25px; border-radius: 12px; }
        label { display: block; margin: 16px 0 7px; font-weight: bold; }
        input, select { box-sizing: border-box; width: 100%; padding: 11px; border: 1px solid #cbd5e1; border-radius: 6px; }
        .btn { display: inline-block; padding: 10px 15px; border: 0; border-radius: 6px; text-decoration: none; cursor: pointer; }
        .primary { background: #2563eb; color: white; }
        .secondary { background: #e5e7eb; color: #111827; }
        .error { color: #dc2626; font-size: 14px; }
        .actions { display: flex; gap: 10px; margin-top: 22px; }
    </style>
</head>
<body>
<div class="container">
    <h1>Tambah Margin Penjualan</h1>
    <p>Masukkan persentase margin dan pengguna yang membuatnya.</p>

    @if ($errors->any())
        <div class="error">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('margin_penjualan.store') }}" method="POST">
        @csrf

        <label for="persen">Persentase Margin (%)</label>
        <input type="number" id="persen" name="persen"
               value="{{ old('persen') }}" min="0" max="100"
               step="0.01" required>

        <label for="status">Status</label>
        <select id="status" name="status" required>
            <option value="1" {{ old('status', '1') == '1' ? 'selected' : '' }}>
                Aktif
            </option>
            <option value="0" {{ old('status') === '0' ? 'selected' : '' }}>
                Tidak Aktif
            </option>
        </select>

        <label for="iduser">Pengguna</label>
        <select id="iduser" name="iduser" required>
            <option value="">-- Pilih Pengguna --</option>
            @foreach ($users as $user)
                <option value="{{ $user->iduser }}"
                    {{ old('iduser') == $user->iduser ? 'selected' : '' }}>
                    {{ $user->username }}
                </option>
            @endforeach
        </select>

        <div class="actions">
            <button type="submit" class="btn primary">Simpan</button>
            <a href="{{ route('margin_penjualan.index') }}" class="btn secondary">
                Kembali
            </a>
        </div>
    </form>
</div>
</body>
</html>
