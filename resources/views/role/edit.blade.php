<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Edit Role</title>
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
        input {
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
    <h1>Edit Role</h1>
    <p>Ubah nama role, kemudian simpan perubahan.</p>

    @if ($errors->any())
        <div class="error">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('role.update', $role->idrole) }}" method="POST">
        @csrf
        @method('PUT')

        <label for="nama_role">Nama Role</label>
        <input
            type="text"
            id="nama_role"
            name="nama_role"
            value="{{ old('nama_role', $role->nama_role) }}"
            maxlength="100"
            required
        >

        <div class="actions">
            <button type="submit" class="btn primary">
                Simpan Perubahan
            </button>
            <a href="{{ route('role.index') }}" class="btn secondary">
                Batal
            </a>
        </div>
    </form>
</div>
</body>
</html>
