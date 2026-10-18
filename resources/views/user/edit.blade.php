<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Edit User</title>

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
            background: #fff;
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(25, 40, 70, .06);
        }

        h1 { margin: 0 0 12px; font-size: 38px; }

        p { color: #586579; margin-bottom: 28px; }

        .form-group { margin-bottom: 22px; }

        label {
            display: block;
            font-weight: 600;
            margin-bottom: 9px;
        }

        input {
            width: 100%;
            padding: 13px 15px;
            border: 1px solid #d6dce6;
            border-radius: 8px;
            font-size: 16px;
        }

        input:focus {
            outline: none;
            border-color: #2860ef;
            box-shadow: 0 0 0 3px rgba(40, 96, 239, .12);
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

        .btn-secondary {
            color: #273449;
            background: #e8ecf3;
            margin-left: 8px;
        }

        .errors {
            color: #a61b1b;
            background: #fde8e8;
            padding: 12px 20px;
            border-radius: 8px;
            margin-bottom: 20px;
        }

        .hint {
            display: block;
            margin-top: 7px;
            color: #667085;
            font-size: 13px;
        }

        @media (max-width: 600px) {
            .container { margin: 15px; padding: 25px 20px; }
            h1 { font-size: 30px; }
        }
    </style>
</head>

<body>
    <main class="container">
        <h1>Edit User</h1>
        <p>Perbarui informasi pengguna dan role-nya.</p>

        @if ($errors->any())
            <div class="errors">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('user.update', $user->iduser) }}"
              method="POST">
            @csrf
            @method('PUT')

            <div class="form-group">
                <label>ID User</label>
                <input type="text" value="{{ $user->iduser }}" disabled>
            </div>

            <div class="form-group">
                <label for="username">Username</label>
                <input
                    id="username"
                    type="text"
                    name="username"
                    value="{{ old('username', $user->username) }}"
                    maxlength="100"
                    required>
            </div>

            <div class="form-group">
                <label for="password">Password Baru</label>
                <input
                    id="password"
                    type="password"
                    name="password"
                    minlength="8"
                    autocomplete="new-password"
                    placeholder="Kosongkan jika tidak ingin mengubah password">
                <span class="hint">
                    Password lama tidak ditampilkan demi keamanan.
                </span>
            </div>

            <div class="form-group">
                <label for="idrole">ID Role</label>
                <input
                    id="idrole"
                    type="number"
                    name="idrole"
                    value="{{ old('idrole', $user->idrole) }}"
                    min="1"
                    required>
            </div>

            <button type="submit" class="btn btn-primary">
                Simpan Perubahan
            </button>

            <a href="{{ route('user.index') }}"
               class="btn btn-secondary">
                Kembali
            </a>
        </form>
    </main>
</body>
</html>
