<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Edit Barang</title>
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
        label { display: block; margin: 16px 0 7px; font-weight: bold; }
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
    <h1>Edit Barang</h1>
    <p>Ubah informasi barang, lalu simpan perubahan.</p>

    @if ($errors->any())
        <div class="error">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('barang.update', $barang->idbarang) }}"
          method="POST">
        @csrf
        @method('PUT')

        <label for="jenis">Jenis Barang</label>
        <input type="text" id="jenis" name="jenis"
               value="{{ old('jenis', $barang->jenis) }}" required>

        <label for="nama">Nama Barang</label>
        <input type="text" id="nama" name="nama"
               value="{{ old('nama', $barang->nama) }}" required>

        <label for="idsatuan">Satuan</label>
        <select id="idsatuan" name="idsatuan" required>
            <option value="">-- Pilih Satuan --</option>
            @foreach ($satuans as $satuan)
                <option value="{{ $satuan->idsatuan }}"
                    {{ old('idsatuan', $barang->idsatuan) == $satuan->idsatuan ? 'selected' : '' }}>
                    {{ $satuan->nama_satuan }}
                </option>
            @endforeach
        </select>

        <label for="status">Status</label>
        <select id="status" name="status" required>
            <option value="1"
                {{ old('status', $barang->status) == 1 ? 'selected' : '' }}>
                Aktif
            </option>
            <option value="0"
                {{ old('status', $barang->status) == 0 ? 'selected' : '' }}>
                Tidak Aktif
            </option>
        </select>

        <label for="harga">Harga (Rp)</label>
        <input type="number" id="harga" name="harga"
               value="{{ old('harga', $barang->harga) }}"
               min="0" step="0.01" required>

        <div class="actions">
            <button type="submit" class="btn primary">
                Simpan Perubahan
            </button>
            <a href="{{ route('barang.index') }}" class="btn secondary">
                Batal
            </a>
        </div>
    </form>
</div>
</body>
</html>
