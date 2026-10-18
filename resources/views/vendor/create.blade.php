
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Tambah Vendor</title>

    <style>
        * { box-sizing: border-box; }

        body {
            margin: 0;
            background: #f3f5f9;
            color: #273449;
            font-family: Arial, Helvetica, sans-serif;
        }

        .container {
            max-width: 850px;
            margin: 35px auto;
            padding: 35px 40px;
            background: white;
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(25,40,70,.06);
        }

        h1 { margin: 0 0 12px; font-size: 38px; }
        p { color: #586579; margin-bottom: 28px; }
        .form-group { margin-bottom: 22px; }

        label {
            display: block;
            font-weight: 600;
            margin-bottom: 9px;
        }

        input, select {
            width: 100%;
            padding: 13px 15px;
            border: 1px solid #d6dce6;
            border-radius: 8px;
            font-size: 16px;
            background: white;
            color: #273449;
        }

        .btn {
            display: inline-block;
            border: 0;
            border-radius: 8px;
            padding: 13px 20px;
            font-size: 16px;
            text-decoration: none;
            cursor: pointer;
        }

        .btn-primary { color: white; background: #2860ef; }
        .btn-secondary { color: #273449; background: #e8ecf3; margin-left: 8px; }
        .errors { color: #a61b1b; background: #fde8e8; padding: 12px 20px; border-radius: 8px; margin-bottom: 20px; }

        @media (max-width: 600px) {
            .container { margin: 15px; padding: 25px 20px; }
            h1 { font-size: 30px; }
        }
    </style>
</head>

<body>
<main class="container">
    <h1>Tambah Vendor</h1>
    <p>Tambahkan informasi vendor baru ke dalam sistem.</p>

    @if ($errors->any())
        <div class="errors">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('vendor.store') }}" method="POST">
        @csrf

        <div class="form-group">
            <label for="nama_vendor">Nama Vendor</label>
            <input
                id="nama_vendor"
                type="text"
                name="nama_vendor"
                value="{{ old('nama_vendor') }}"
                maxlength="255"
                required>
        </div>

        <div class="form-group">
            <label for="badan_hukum">Badan Hukum</label>
            <select id="badan_hukum" name="badan_hukum" required>
                <option value="">-- Pilih Badan Hukum --</option>
                <option value="P" @selected(old('badan_hukum') === 'P')>
                    PT - Perseroan Terbatas
                </option>
                <option value="C" @selected(old('badan_hukum') === 'C')>
                    CV - Commanditaire Vennootschap
                </option>
                <option value="U" @selected(old('badan_hukum') === 'U')>
                    UD - Usaha Dagang
                </option>
            </select>
        </div>

        <div class="form-group">
            <label for="status">Status</label>
            <select id="status" name="status" required>
                <option value="">-- Pilih Status --</option>
                <option value="A" @selected(old('status') === 'A')>
                    Aktif (A)
                </option>
            </select>
        </div>

        <button type="submit" class="btn btn-primary">
            Simpan Vendor
        </button>

        <a href="{{ route('vendor.index') }}" class="btn btn-secondary">
            Kembali
        </a>
    </form>
</main>
</body>
</html>
