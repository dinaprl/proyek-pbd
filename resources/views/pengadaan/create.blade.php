<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Tambah Pengadaan</title>

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

        h2 {
            margin-top: 25px;
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
            display: block;
            width: 100%;
            padding: 10px;
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
            font-size: 14px;
        }

        button:hover {
            opacity: 0.9;
        }

        .btn-primary {
            background: #6c63ff;
            color: white;
        }

        .btn-add {
            background: #198754;
            color: white;
        }

        .btn-delete {
            background: #dc3545;
            color: white;
        }

        .btn-back {
            display: inline-block;
            margin-top: 10px;
        }

        .error {
            color: #dc3545;
            background: #fff0f0;
            padding: 10px 20px;
            border-radius: 5px;
            margin-top: 20px;
        }

        .success {
            color: #198754;
            margin-top: 15px;
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
            background: #f0efff;
            border-radius: 8px;
        }

        .ringkasan p {
            margin: 12px 0;
        }

        .total {
            font-size: 21px;
            font-weight: bold;
        }

        .btn-primary,
        .btn-add,
        .btn-delete {
            transition: 0.2s;
        }
    </style>
</head>

<body>

    <h1>Tambah Pengadaan</h1>

    <a class="btn-back" href="{{ route('pengadaan.index') }}">
        Kembali ke Data Pengadaan
    </a>

    @if (session('success'))
        <p class="success">{{ session('success') }}</p>
    @endif

    @if ($errors->any())
        <div class="error">
            <strong>Pengadaan belum dapat disimpan:</strong>
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('pengadaan.store') }}" method="POST">
        @csrf

        <label for="vendor_idvendor">Vendor</label>

        <select name="vendor_idvendor" id="vendor_idvendor" required>
            <option value="">Pilih Vendor</option>

            @foreach ($vendor as $item)
                <option
                    value="{{ $item->idvendor }}"
                    {{ old('vendor_idvendor') == $item->idvendor ? 'selected' : '' }}
                >
                    {{ $item->nama_vendor }}
                </option>
            @endforeach
        </select>

        <h2>Daftar Barang</h2>

        <div id="daftar-barang">

            @php
                $barangLama = old('barang_id', ['']);
                $jumlahLama = old('jumlah', [1]);
                $hargaLama = old('harga_satuan', [0]);
            @endphp

            @foreach ($barangLama as $i => $barangId)
                <div class="barang-row">

                    <h3>
                        Barang <span class="nomor-barang">{{ $i + 1 }}</span>
                    </h3>

                    <label>Nama Barang</label>

                    <select name="barang_id[]" required>
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

                    <label>Jumlah Pesanan</label>

                    <input
                        type="number"
                        name="jumlah[]"
                        min="1"
                        step="1"
                        value="{{ $jumlahLama[$i] ?? 1 }}"
                        required
                    >

                    <label>Harga Satuan (Rp)</label>

                    <input
                        type="number"
                        name="harga_satuan[]"
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

        <label for="ppn_persen">PPN (%)</label>

        <input
            type="number"
            name="ppn_persen"
            id="ppn_persen"
            min="0"
            max="100"
            step="0.01"
            value="{{ old('ppn_persen', 11) }}"
            required
        >

        <div class="ringkasan">
            <h2>Ringkasan Pengadaan</h2>

            <p>
                Subtotal:
                <strong id="subtotal">Rp0</strong>
            </p>

            <p>
                PPN:
                <strong id="nilai-ppn">Rp0</strong>
            </p>

            <p class="total">
                Total:
                <span id="total">Rp0</span>
            </p>
        </div>

        <button type="submit" class="btn-primary">
            Simpan Pengadaan
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

                <label>Nama Barang</label>

                <select name="barang_id[]" required>
                    <option value="">Pilih Barang</option>
                    @foreach ($barang as $item)
                        <option value="{{ $item->idbarang }}">
                            {{ $item->nama }}
                        </option>
                    @endforeach
                </select>

                <label>Jumlah Pesanan</label>

                <input
                    type="number"
                    name="jumlah[]"
                    min="1"
                    step="1"
                    value="1"
                    required
                >

                <label>Harga Satuan (Rp)</label>

                <input
                    type="number"
                    name="harga_satuan[]"
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
            let subtotal = 0;

            const rows = daftarBarang.querySelectorAll('.barang-row');

            rows.forEach(row => {
                const jumlah = Number(
                    row.querySelector('[name="jumlah[]"]').value
                ) || 0;

                const harga = Number(
                    row.querySelector('[name="harga_satuan[]"]').value
                ) || 0;

                subtotal += jumlah * harga;
            });

            const ppnPersen = Number(
                document.getElementById('ppn_persen').value
            ) || 0;

            const ppn = Math.round(subtotal * ppnPersen / 100);
            const total = subtotal + ppn;

            document.getElementById('subtotal').textContent =
                formatRupiah(subtotal);

            document.getElementById('nilai-ppn').textContent =
                formatRupiah(ppn);

            document.getElementById('total').textContent =
                formatRupiah(total);
        }

        daftarBarang.addEventListener('input', hitungTotal);
        daftarBarang.addEventListener('change', hitungTotal);

        document.getElementById('ppn_persen')
            .addEventListener('input', hitungTotal);

        perbaruiNomorBarang();
        hitungTotal();
    </script>

</body>
</html>
