```php
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Tambah Penerimaan</title>

    <style>
        * {
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
            margin: 30px;
            background: #f5f6fa;
            color: #222;
        }

        h1 {
            font-size: 36px;
            margin-bottom: 10px;
        }

        a {
            color: #6c2eb9;
        }

        form {
            max-width: 850px;
            padding: 25px;
            background: white;
            border-radius: 10px;
            margin-top: 25px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
        }

        label {
            display: block;
            margin-top: 15px;
            font-weight: bold;
        }

        input,
        select {
            width: 100%;
            padding: 11px;
            margin-top: 6px;
            border: 1px solid #ccc;
            border-radius: 5px;
            background: white;
        }

        button {
            padding: 11px 16px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            margin-top: 15px;
        }

        .btn-primary {
            background: #198754;
            color: white;
        }

        .btn-add {
            background: #0d6efd;
            color: white;
        }

        .btn-delete {
            background: #dc3545;
            color: white;
        }

        .error {
            color: #dc3545;
            background: #fff0f0;
            padding: 10px 20px;
            border-radius: 5px;
            margin-top: 20px;
        }

        .barang-row {
            padding: 18px;
            margin-top: 15px;
            border: 1px solid #ddd;
            border-radius: 8px;
            background: #fafafa;
        }

        .barang-row h3 {
            margin-top: 0;
        }

        .ringkasan {
            margin-top: 25px;
            padding: 18px;
            background: #eaf7ef;
            border-radius: 8px;
        }

        .ringkasan p {
            margin: 10px 0;
        }

        .total {
            font-size: 21px;
            font-weight: bold;
        }

        .info {
            margin-top: 15px;
            padding: 12px;
            background: #eef4ff;
            border-radius: 5px;
            color: #234;
        }
    </style>
</head>

<body>

    <h1>Tambah Penerimaan</h1>

    <a href="{{ route('penerimaan.index') }}">
        Kembali ke Data Penerimaan
    </a>

    @if ($errors->any())
        <div class="error">
            <strong>Penerimaan belum dapat disimpan:</strong>
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('penerimaan.store') }}" method="POST">
        @csrf

        <label for="idpengadaan">Pengadaan</label>

        <select name="idpengadaan" id="idpengadaan" required>
            <option value="">Pilih Pengadaan</option>

            @foreach ($pengadaan as $item)
                <option
                    value="{{ $item->idpengadaan }}"
                    {{ old('idpengadaan') == $item->idpengadaan ? 'selected' : '' }}
                >
                    Pengadaan #{{ $item->idpengadaan }}
                    — {{ $item->vendor->nama_vendor ?? '-' }}
                </option>
            @endforeach
        </select>

        <div class="info">
            Pilih pengadaan yang akan diterima. Pastikan barang dan jumlah
            yang dimasukkan sesuai dengan barang yang benar-benar diterima.
        </div>

        <h2>Daftar Barang yang Diterima</h2>

        <div id="daftar-barang">

            @php
                $barangLama = old('barang_idbarang', ['']);
                $jumlahLama = old('jumlah_terima', [1]);
                $hargaLama = old('harga_satuan_terima', [0]);
            @endphp

            @foreach ($barangLama as $i => $barangId)
                <div class="barang-row">

                    <h3>
                        Barang <span class="nomor-barang">{{ $i + 1 }}</span>
                    </h3>

                    <label>Barang yang Diterima</label>

                    <select name="barang_idbarang[]" required>
                        <option value="">Pilih Barang</option>

                        @foreach ($barang as $item)
                            <option
                                value="{{ $item->idbarang }}"
                                {{ (string) $barangId === (string) $item->idbarang ? 'selected' : '' }}
                            >
                                {{ $item->nama }}
                            </option>
                        @endforeach
                    </select>

                    <label>Jumlah Diterima</label>

                    <input
                        type="number"
                        name="jumlah_terima[]"
                        min="1"
                        step="1"
                        value="{{ $jumlahLama[$i] ?? 1 }}"
                        required
                    >

                    <label>Harga Satuan Penerimaan (Rp)</label>

                    <input
                        type="number"
                        name="harga_satuan_terima[]"
                        min="0"
                        step="1"
                        value="{{ $hargaLama[$i] ?? 0 }}"
                        required
                    >

                    <button
                        type="button"
                        class="btn-delete"
                        onclick="hapusBarang(this)"
                    >
                        Hapus Barang
                    </button>

                </div>
            @endforeach

        </div>

        <button
            type="button"
            class="btn-add"
            onclick="tambahBarang()"
        >
            + Tambah Barang
        </button>

        <div class="ringkasan">
            <h2>Ringkasan Penerimaan</h2>

            <p>
                Total Barang:
                <strong id="total-jumlah">0</strong>
            </p>

            <p>
                Total Nilai Penerimaan:
                <strong id="total-nilai">Rp0</strong>
            </p>

            <small>
                Ringkasan ini dihitung otomatis berdasarkan jumlah diterima
                dikalikan harga satuan setiap barang.
            </small>
        </div>

        <button type="submit" class="btn-primary">
            Simpan Penerimaan
        </button>

    </form>

    <script>
        const daftarBarang = document.getElementById('daftar-barang');

        function formatRupiah(nilai) {
            return new Intl.NumberFormat('id-ID', {
                style: 'currency',
                currency: 'IDR',
                maximumFractionDigits: 0
            }).format(nilai);
        }

        function perbaruiNomorBarang() {
            const rows = daftarBarang.querySelectorAll('.barang-row');

            rows.forEach((row, index) => {
                row.querySelector('.nomor-barang').textContent = index + 1;
            });
        }

        function tambahBarang() {
            const row = document.createElement('div');

            row.className = 'barang-row';

            row.innerHTML = `
                <h3>
                    Barang <span class="nomor-barang"></span>
                </h3>

                <label>Barang yang Diterima</label>

                <select name="barang_idbarang[]" required>
                    <option value="">Pilih Barang</option>

                    @foreach ($barang as $item)
                        <option value="{{ $item->idbarang }}">
                            {{ $item->nama }}
                        </option>
                    @endforeach
                </select>

                <label>Jumlah Diterima</label>

                <input
                    type="number"
                    name="jumlah_terima[]"
                    min="1"
                    step="1"
                    value="1"
                    required
                >

                <label>Harga Satuan Penerimaan (Rp)</label>

                <input
                    type="number"
                    name="harga_satuan_terima[]"
                    min="0"
                    step="1"
                    value="0"
                    required
                >

                <button
                    type="button"
                    class="btn-delete"
                    onclick="hapusBarang(this)"
                >
                    Hapus Barang
                </button>
            `;

            daftarBarang.appendChild(row);

            perbaruiNomorBarang();
            hitungTotal();
        }

        function hapusBarang(button) {
            const rows = daftarBarang.querySelectorAll('.barang-row');

            if (rows.length <= 1) {
                alert('Minimal harus ada satu barang.');
                return;
            }

            button.closest('.barang-row').remove();

            perbaruiNomorBarang();
            hitungTotal();
        }

        function hitungTotal() {
            let totalJumlah = 0;
            let totalNilai = 0;

            const rows = daftarBarang.querySelectorAll('.barang-row');

            rows.forEach(row => {
                const jumlah = Number(
                    row.querySelector('[name="jumlah_terima[]"]').value
                ) || 0;

                const harga = Number(
                    row.querySelector('[name="harga_satuan_terima[]"]').value
                ) || 0;

                totalJumlah += jumlah;
                totalNilai += jumlah * harga;
            });

            document.getElementById('total-jumlah').textContent =
                totalJumlah;

            document.getElementById('total-nilai').textContent =
                formatRupiah(totalNilai);
        }

        daftarBarang.addEventListener('input', hitungTotal);
        daftarBarang.addEventListener('change', hitungTotal);

        perbaruiNomorBarang();
        hitungTotal();
    </script>

</body>
</html>
