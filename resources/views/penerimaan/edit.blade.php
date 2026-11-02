<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Edit Penerimaan</title>

    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f3f5f9;
            color: #273449;
            margin: 0;
        }

        .container {
            max-width: 1100px;
            margin: 32px auto;
            background: white;
            padding: 28px;
            border-radius: 14px;
        }

        label {
            display: block;
            font-weight: bold;
            margin: 12px 0 6px;
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
            align-items: end;
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

        .notice {
            padding: 12px;
            background: #fef3c7;
            color: #92400e;
            border-radius: 8px;
        }
    </style>
</head>

<body>
<main class="container">

    <h1>Edit Penerimaan #{{ $penerimaan->idpenerimaan }}</h1>

    <p>
        Ubah data barang yang benar-benar diterima.
        Perubahan detail akan diselaraskan dengan kartu stok.
    </p>

    <div class="notice">
        Pastikan penerimaan yang diedit memang sesuai dengan kondisi
        barang sebenarnya. Perubahan ini memengaruhi catatan stok.
    </div>

    <br>

    <a href="{{ route('penerimaan.index') }}" class="btn secondary">
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
        action="{{ route('penerimaan.update', $penerimaan->idpenerimaan) }}"
    >
        @csrf
        @method('PUT')

        <label>Pengadaan</label>

        <select name="idpengadaan" required>
            @foreach ($pengadaans as $p)
                <option
                    value="{{ $p->idpengadaan }}"
                    @selected(
                        old('idpengadaan', $penerimaan->idpengadaan)
                        == $p->idpengadaan
                    )
                >
                    #{{ $p->idpengadaan }}
                    — {{ $p->nama_vendor }}
                    ({{ $p->timestamp }})
                </option>
            @endforeach
        </select>

        <h3>Detail Barang Diterima</h3>

        <div id="items">

            @foreach ($details as $detail)
                <div class="row item">

                    <div>
                        <input
                            type="hidden"
                            name="iddetail_penerimaan[]"
                            value="{{ $detail->iddetail_penerimaan }}"
                        >

                        <label>Barang</label>

                        <select name="barang_id[]" required>
                            @foreach ($barangs as $barang)
                                <option
                                    value="{{ $barang->idbarang }}"
                                    @selected(
                                        $detail->barang_idbarang
                                        == $barang->idbarang
                                    )
                                >
                                    {{ $barang->nama }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label>Jumlah Diterima</label>

                        <input
                            type="number"
                            name="jumlah_terima[]"
                            min="1"
                            value="{{ $detail->jumlah_terima }}"
                            required
                        >
                    </div>

                    <div>
                        <label>Harga Satuan</label>

                        <input
                            type="number"
                            name="harga_satuan_terima[]"
                            min="0"
                            value="{{ $detail->harga_satuan_terima }}"
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

    // Baris baru harus dibuat sebagai detail baru.
    const hiddenId = clone.querySelector(
        'input[name="iddetail_penerimaan[]"]'
    );

    if (hiddenId) {
        hiddenId.value = '';
    }

    clone.querySelectorAll('select').forEach(select => {
        select.selectedIndex = 0;
    });

    clone.querySelectorAll('input[type="number"]').forEach(input => {
        input.value = '';
    });

    container.appendChild(clone);
}
</script>

</body>
</html>
