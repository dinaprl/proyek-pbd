<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Dashboard | TOKO CERIA</title>

    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: Arial, Helvetica, sans-serif;
            background: #f3f5f9;
            color: #273449;
        }

        .layout {
            display: flex;
            min-height: 100vh;
        }

        /* Sidebar */
        .sidebar {
            width: 250px;
            flex-shrink: 0;
            background: #172554;
            color: white;
            padding: 30px 20px;
        }

        .brand {
            font-size: 23px;
            font-weight: bold;
            margin-bottom: 8px;
        }

        .brand-subtitle {
            font-size: 13px;
            color: #cbd5e1;
            line-height: 1.6;
            margin-bottom: 38px;
        }

        .menu-label {
            color: #94a3b8;
            font-size: 12px;
            font-weight: bold;
            letter-spacing: 1.2px;
            margin: 24px 12px 12px;
        }

        .nav-link {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 13px 14px;
            margin-bottom: 7px;
            border-radius: 9px;
            color: #e2e8f0;
            text-decoration: none;
            font-size: 15px;
            transition: .2s;
        }

        .nav-link:hover,
        .nav-link.active {
            background: #2860ef;
            color: white;
        }

        .nav-icon {
            width: 25px;
            text-align: center;
            font-size: 19px;
        }

        .sidebar-footer {
            margin-top: 45px;
            border-top: 1px solid #334155;
            padding: 20px 10px 0;
            font-size: 12px;
            line-height: 1.8;
            color: #cbd5e1;
        }

        /* Main content */
        .main {
            flex: 1;
            min-width: 0;
            padding: 30px 38px;
        }

        .topbar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 20px;
            margin-bottom: 35px;
        }

        .breadcrumb {
            font-size: 14px;
            color: #64748b;
            margin-bottom: 8px;
        }

        .page-title {
            font-size: 32px;
            font-weight: 700;
            letter-spacing: -.5px;
        }

        .admin-badge {
            display: inline-flex;
            align-items: center;
            gap: 9px;
            background: white;
            border: 1px solid #e2e8f0;
            padding: 11px 16px;
            border-radius: 12px;
            font-size: 14px;
            white-space: nowrap;
        }

        .admin-circle {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 35px;
            height: 35px;
            background: #dbeafe;
            color: #1d4ed8;
            border-radius: 50%;
            font-weight: bold;
        }

        /* Welcome banner */
        .welcome {
            background: linear-gradient(120deg, #1d4ed8, #3b82f6);
            color: white;
            padding: 32px;
            border-radius: 16px;
            margin-bottom: 32px;
            position: relative;
            overflow: hidden;
        }

        .welcome h2 {
            font-size: 27px;
            margin-bottom: 12px;
        }

        .welcome p {
            font-size: 15px;
            line-height: 1.8;
            color: #eff6ff;
            max-width: 650px;
        }

        .welcome-tag {
            display: inline-block;
            margin-top: 20px;
            padding: 9px 13px;
            background: rgba(255,255,255,.16);
            border: 1px solid rgba(255,255,255,.25);
            border-radius: 8px;
            font-size: 13px;
        }

        /* Section */
        .section-heading {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 15px;
            margin-bottom: 20px;
        }

        .section-heading h2 {
            font-size: 23px;
        }

        .section-heading p {
            margin-top: 7px;
            color: #64748b;
            font-size: 14px;
        }

        .menu-count {
            font-size: 13px;
            color: #64748b;
            white-space: nowrap;
        }

        /* Menu cards */
        .card-grid {
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: 20px;
        }

        .menu-card {
            display: flex;
            flex-direction: column;
            min-height: 220px;
            padding: 24px;
            background: white;
            border: 1px solid #e2e8f0;
            border-radius: 14px;
            text-decoration: none;
            color: inherit;
            transition: transform .2s, box-shadow .2s, border-color .2s;
        }

        .menu-card:hover {
            transform: translateY(-4px);
            border-color: #93b4ff;
            box-shadow: 0 10px 25px rgba(37, 99, 235, .09);
        }

        .card-icon {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 53px;
            height: 53px;
            background: #eff6ff;
            border-radius: 13px;
            font-size: 26px;
            margin-bottom: 21px;
        }

        .menu-card h3 {
            font-size: 19px;
            margin-bottom: 10px;
        }

        .menu-card p {
            font-size: 14px;
            color: #64748b;
            line-height: 1.7;
            flex: 1;
        }

        .card-link {
            display: flex;
            justify-content: space-between;
            align-items: center;
            color: #2860ef;
            font-size: 14px;
            font-weight: bold;
            margin-top: 22px;
        }

        .footer {
            margin-top: 40px;
            padding-top: 20px;
            border-top: 1px solid #e2e8f0;
            font-size: 13px;
            color: #64748b;
            text-align: center;
        }

        @media (max-width: 1100px) {
            .card-grid {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }

            .sidebar {
                width: 220px;
            }

            .main {
                padding: 25px;
            }
        }

        @media (max-width: 700px) {
            .layout {
                flex-direction: column;
            }

            .sidebar {
                width: 100%;
                padding: 20px;
            }

            .brand-subtitle {
                margin-bottom: 20px;
            }

            .sidebar .menu-label,
            .sidebar-footer {
                display: none;
            }

            .sidebar nav {
                display: grid;
                grid-template-columns: repeat(2, minmax(0, 1fr));
                gap: 5px;
            }

            .nav-link {
                margin: 0;
                padding: 11px 8px;
                font-size: 13px;
            }

            .main {
                padding: 22px 16px;
            }

            .topbar {
                align-items: flex-start;
                flex-direction: column;
            }

            .page-title {
                font-size: 27px;
            }

            .welcome {
                padding: 24px;
            }

            .welcome h2 {
                font-size: 23px;
            }

            .card-grid {
                grid-template-columns: 1fr;
            }

            .section-heading {
                align-items: flex-start;
                flex-direction: column;
            }
        }
    </style>
</head>

<body>
<div class="layout">

    <aside class="sidebar">
        <div class="brand">TOKO CERIA</div>
        <div class="brand-subtitle">
            Sistem Informasi<br>Pengelolaan Toko
        </div>

        <nav>
            <a href="{{ url('/dashboard') }}" class="nav-link active">
                <span class="nav-icon">⌂</span>
                Dashboard
            </a>

            <div class="menu-label" style="grid-column:1/-1">
                MASTER DATA
            </div>

            <a href="{{ route('user.index') }}" class="nav-link">
                <span class="nav-icon">♙</span>
                User
            </a>

            <a href="{{ route('role.index') }}" class="nav-link">
                <span class="nav-icon">♧</span>
                Role
            </a>

            <a href="{{ route('barang.index') }}" class="nav-link">
                <span class="nav-icon">▣</span>
                Barang
            </a>

            <a href="{{ route('satuan.index') }}" class="nav-link">
                <span class="nav-icon">▤</span>
                Satuan
            </a>

            <a href="{{ route('vendor.index') }}" class="nav-link">
                <span class="nav-icon">▦</span>
                Vendor
            </a>

            <a href="{{ route('margin_penjualan.index') }}" class="nav-link">
                <span class="nav-icon">％</span>
                Margin Penjualan
            </a>

        </nav>

        <div class="sidebar-footer">
            <strong>TOKO CERIA</strong><br>
            Sistem Pengelolaan Toko
        </div>
    </aside>

    <main class="main">

        <header class="topbar">
            <div>
                <div class="breadcrumb">Beranda / Dashboard</div>
                <h1 class="page-title">Dashboard</h1>
            </div>

            <div class="admin-badge">
                <span class="admin-circle">A</span>
                <span>
                    <strong>Administrator</strong><br>
                    <small style="color:#64748b">Panel Pengelolaan</small>
                </span>
            </div>
        </header>

        <section class="welcome">
            <h2>Selamat Datang di TOKO CERIA!</h2>
            <p>
                Selamat datang di sistem pengelolaan TOKO CERIA.
                Kelola data pengguna, barang, satuan, vendor,
                dan role dalam satu tempat dengan mudah dan praktis.
            </p>
            <span class="welcome-tag">
                ✦ Sistem Informasi Pengelolaan Toko
            </span>
        </section>

        <section>
            <div class="section-heading">
                <div>
                    <h2>Menu Master Data</h2>
                    <p>Pilih salah satu menu untuk mulai mengelola data.</p>
                </div>

                <span class="menu-count">6 menu tersedia</span>
            </div>

            <div class="card-grid">

                <a href="{{ route('user.index') }}" class="menu-card">
                    <div class="card-icon">👤</div>
                    <h3>Master User</h3>
                    <p>
                        Kelola akun pengguna dan tentukan role
                        untuk setiap user.
                    </p>
                    <div class="card-link">
                        Kelola User <span>→</span>
                    </div>
                </a>

                <a href="{{ route('role.index') }}" class="menu-card">
                    <div class="card-icon">🔐</div>
                    <h3>Master Role</h3>
                    <p>
                        Kelola daftar role atau peran pengguna
                        dalam sistem.
                    </p>
                    <div class="card-link">
                        Kelola Role <span>→</span>
                    </div>
                </a>

                <a href="{{ route('barang.index') }}" class="menu-card">
                    <div class="card-icon">📦</div>
                    <h3>Master Barang</h3>
                    <p>
                        Kelola informasi barang, harga, jenis,
                        dan satuan barang.
                    </p>
                    <div class="card-link">
                        Kelola Barang <span>→</span>
                    </div>
                </a>

                <a href="{{ route('satuan.index') }}" class="menu-card">
                    <div class="card-icon">📏</div>
                    <h3>Master Satuan</h3>
                    <p>
                        Kelola satuan pengukuran seperti Pcs,
                        Box, Rim, Botol, dan Pack.
                    </p>
                    <div class="card-link">
                        Kelola Satuan <span>→</span>
                    </div>
                </a>

                <a href="{{ route('vendor.index') }}" class="menu-card">
                    <div class="card-icon">🏢</div>
                    <h3>Master Vendor</h3>
                    <p>
                        Kelola nama vendor, badan hukum,
                        dan status vendor.
                    </p>
                    <div class="card-link">
                        Kelola Vendor <span>→</span>
                    </div>
                </a>


                <a href="{{ route('margin_penjualan.index') }}" class="menu-card">
                    <div class="card-icon">％</div>
                    <h3>Margin Penjualan</h3>
                    <p>
                        Kelola persentase margin penjualan
                        beserta status dan informasi pembuatnya.
                    </p>
                    <div class="card-link">
                        Kelola Margin <span>→</span>
                    </div>
                </a>

            </div>
        </section>

        <footer class="footer">
            © {{ date('Y') }} TOKO CERIA. All rights reserved.
        </footer>

    </main>
</div>
</body>
</html>
