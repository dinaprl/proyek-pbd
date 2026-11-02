<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Edit Pengadaan</title>

    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f3f5f9;
            color: #273449;
            margin: 0;
        }

        .container {
            max-width: 1000px;
            margin: 32px auto;
            background: white;
            padding: 28px;
            border-radius: 14px;
        }

        label {
            display: block;
            margin: 12px 0 6px;
            font-weight: bold;
        }

        input, select {
            padding: 10px;
            border: 1px solid #cbd5e1;
            border-radius: 7px;
            width: 100%;
            box-sizing: border-box;
        }

        .row {
            display: grid;
            grid-template-columns: 2fr 1fr 1fr;
            gap: 12px;
            margin-bottom: 12px;
        }

        .btn {
            display: inline-block;
            padding: 10px 15px;
            border: 0;
            border-radius: 7px;
            background: #2860ef;
            color: white;
            text-decoration: none;
            cursor: pointer;
            margin: 8px 4px 8px 0;
        }

        .secondary {
            background: #64748b;
        }

        .danger {
            background: #dc2626;
        }

        .errors {
            padding: 12px;
            background: #fee2e2;
            color: #991b1b;
            border-radius: 8px;
        }
    </style>
</head>

<body>
<main class="container">

    <h1>Edit Pengadaan #{{ $pengadaan->idpengadaan }}</h1>

    <p>
        Ubah data pengadaan sebelum barang diterima.
        Perubahan ini tidak menambah stok.
    </p>

    <a href="{{ route('pengadaan.index') }}" class="btn secondary">
        Kembali
    </a>

    @if ($errors->any())
        <div class="errors">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form
        method="POST"
        action="{{ route('pengadaan.update', $pengadaan->idpengadaan) }}"
    >
        @csrf
        @method('PUT')

        <label>Vendor</label>

        <select name="vendor_idvendor" required>
            @foreach ($vendors as $vendor)
                <option
                    value="{{ $vendor->idvendor }}"
                    @selected(
                        old('vendor_idvendor', $pengadaan->vendor_idvendor)
                        == $vendor->idvendor
                    )
                >
                    {{ $vendor->nama_vendor }}
                </option>
            @endforeach
        </select>

        <h3>Detail Barang</h3>

        <div id="items">

            @foreach ($details as $detail)
                <div class="row item">

                    <div>
                        <label>Barang</label>

                        <select name="barang_id[]" required>
                            @foreach ($barangs as $barang)
                                <option
                                    value="{{ $barang->idbarang }}"
                                    @selected(
                                        old(
                                            'barang_id.' . $loop->parent->index,
                                            $detail->idbarang
                                        ) == $barang->idbarang
                                    )
                                >
                                    {{ $barang->nama }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label>Jumlah</label>

                        <input
                            type="number"
                            name="jumlah[]"
                            min="1"
                            value="{{ $detail->jumlah }}"
                            required
                        >
                    </div>

                    <div>
                        <label>Harga Satuan</label>

                        <input
                            type="number"
                            name="harga_satuan[]"
                            min="0"
                            value="{{ $detail->harga_satuan }}"
                            required
                        >
                    </div>

                    <button
                        type="button"
                        class="btn danger"
                        onclick="this.closest('.item').remove()"
                    >
                        Hapus Baris
                    </button>

                </div>
            @endforeach

        </div>

        <button
            type="button"
            class="btn secondary"
            onclick="addItem()"
        >
            + Tambah Barang
        </button>

        <label>PPN (%)</label>

        <input
            type="number"
            name="ppn_persen"
            min="0"
            max="100"
            step="0.01"
            value="{{ old('ppn_persen', $ppnPersen) }}"
        >

        <br><br>

        <button type="submit" class="btn">
            Simpan Perubahan
        </button>

    </form>

</main>

<script>
function addItem() {
    const container = document.getElementById('items');
    const firstItem = container.querySelector('.item');

    if (!firstItem) {
        alert('Tidak ada baris barang untuk disalin.');
        return;
    }

    const clone = firstItem.cloneNode(true);

    clone.querySelectorAll('select').forEach(select => {
        select.selectedIndex = 0;
    });

    clone.querySelectorAll('input').forEach(input => {
        input.value = '';
    });

    container.appendChild(clone);
}
</script>

</body>
</html>
