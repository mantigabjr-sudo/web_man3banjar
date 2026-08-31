<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?></title>
    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary: #059669;
            --primary-dark: #047857;
            --secondary: #0284c7;
            --dark: #0f172a;
        }
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background: #f8fafc;
            color: #334155;
        }
        .navbar-custom {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            box-shadow: 0 4px 20px rgba(0,0,0,0.05);
        }
        .hero-ppdb {
            background: linear-gradient(135deg, #064e3b 0%, #065f46 50%, #0f766e 100%);
            color: white;
            padding: 80px 0 100px 0;
            border-radius: 0 0 40px 40px;
        }
        .card-custom {
            border: none;
            border-radius: 20px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.05);
            transition: all 0.3s ease;
        }
        .card-custom:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 35px rgba(0,0,0,0.1);
        }
        .btn-ppdb {
            background: #f59e0b;
            color: #000;
            font-weight: 700;
            padding: 12px 28px;
            border-radius: 12px;
            border: none;
            transition: 0.2s;
        }
        .btn-ppdb:hover {
            background: #d97706;
            color: #fff;
        }
        .step-number {
            width: 45px;
            height: 45px;
            background: #ecfdf5;
            color: var(--primary);
            font-weight: 800;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 20px;
        }
    </style>
</head>
<body>

<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-custom sticky-top py-3">
    <div class="container">
        <a class="navbar-brand d-flex align-items-center gap-2 fw-bold text-dark" href="<?= base_url() ?>">
            <i class="bi bi-mortarboard-fill text-success fs-3"></i>
            <div>
                <span class="d-block lh-1 text-success fw-extrabold"><?= htmlspecialchars($setting->nama_sekolah) ?></span>
                <small class="text-muted" style="font-size: 11px;">PORTAL PMB / PPDB ONLINE 24/7</small>
            </div>
        </a>
        <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#navMenu">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navMenu">
            <ul class="navbar-nav ms-auto mb-2 mb-lg-0 align-items-center gap-2">
                <li class="nav-item"><a class="nav-link fw-semibold" href="<?= base_url() ?>">Beranda Web</a></li>
                <li class="nav-item"><a class="nav-link fw-semibold" href="<?= base_url('ppdb') ?>">Informasi PPDB</a></li>
                <li class="nav-item"><a class="nav-link fw-semibold" href="<?= base_url('ppdb/cek_status') ?>">Cek Status Kelulusan</a></li>
                <li class="nav-item ms-lg-2">
                    <a href="<?= base_url('ppdb/daftar') ?>" class="btn btn-success fw-bold px-4 py-2 rounded-pill shadow-sm">
                        <i class="bi bi-pencil-square me-1"></i> Daftar Sekarang
                    </a>
                </li>
            </ul>
        </div>
    </div>
</nav>

<!-- Hero Section -->
<section class="hero-ppdb text-center">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-9">
                <span class="badge bg-warning text-dark px-3 py-2 rounded-pill fw-bold mb-3">
                    <i class="bi bi-stars me-1"></i> Tahun Pelajaran <?= htmlspecialchars($ppdb_setting->tahun_ajaran) ?>
                </span>
                <h1 class="display-4 fw-extrabold mb-3">Penerimaan Siswa Baru Online</h1>
                <p class="lead mb-4 text-light opacity-90">
                    Mari bergabung bersama <?= htmlspecialchars($setting->nama_sekolah) ?>. Madrasah Mandiri Berprestasi, Unggul dalam IPTEK &amp; IMTAQ.
                </p>
                <div class="d-flex justify-content-center gap-3 flex-wrap">
                    <a href="<?= base_url('ppdb/daftar') ?>" class="btn btn-ppdb shadow-lg">
                        <i class="bi bi-box-arrow-in-right me-1"></i> Formulir Pendaftaran Online
                    </a>
                    <a href="<?= base_url('ppdb/cek_status') ?>" class="btn btn-outline-light fw-bold px-4 py-3 rounded-3">
                        <i class="bi bi-search me-1"></i> Cek Status Pendaftaran
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Informasi Kuota & Alur -->
<section class="py-5" style="margin-top: -50px;">
    <div class="container">
        <div class="row g-4">
            <!-- Card Kuota -->
            <div class="col-md-4">
                <div class="card card-custom p-4 bg-white h-100 text-center">
                    <div class="p-3 bg-success bg-opacity-10 text-success rounded-circle d-inline-flex fs-1 mx-auto mb-3">
                        <i class="bi bi-people-fill"></i>
                    </div>
                    <h5 class="fw-bold mb-1">Kuota Penerimaan</h5>
                    <h2 class="fw-extrabold text-success mb-2"><?= $ppdb_setting->kuota_siswa ?> Siswa</h2>
                    <p class="text-muted small mb-0">Total kapasitas penerimaan siswa baru jenjang Madrasah Aliyah kelas X.</p>
                </div>
            </div>

            <!-- Card Waktu Pendaftaran -->
            <div class="col-md-4">
                <div class="card card-custom p-4 bg-white h-100 text-center">
                    <div class="p-3 bg-primary bg-opacity-10 text-primary rounded-circle d-inline-flex fs-1 mx-auto mb-3">
                        <i class="bi bi-calendar-check-fill"></i>
                    </div>
                    <h5 class="fw-bold mb-1">Jadwal Pendaftaran</h5>
                    <h5 class="fw-bold text-primary mb-2">24 Jam Online</h5>
                    <p class="text-muted small mb-0">Pendaftaran dibuka sepanjang hari melalui portal resmi ini tanpa perlu antri.</p>
                </div>
            </div>

            <!-- Card Bantuan Panitia -->
            <div class="col-md-4">
                <div class="card card-custom p-4 bg-white h-100 text-center">
                    <div class="p-3 bg-warning bg-opacity-10 text-warning rounded-circle d-inline-flex fs-1 mx-auto mb-3">
                        <i class="bi bi-headset"></i>
                    </div>
                    <h5 class="fw-bold mb-1">Layanan Bantuan</h5>
                    <h6 class="fw-bold text-dark mb-2"><?= htmlspecialchars($ppdb_setting->kontak_panitia) ?></h6>
                    <p class="text-muted small mb-0">Hubungi panitia via WhatsApp jika mengalami kendala saat mendaftar online.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Alur Pendaftaran -->
<section class="py-5 bg-white">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="fw-bold text-dark">Alur Pendaftaran Mudah</h2>
            <p class="text-muted">4 Langkah praktis mendaftar di <?= htmlspecialchars($setting->nama_sekolah) ?></p>
        </div>

        <div class="row g-4">
            <div class="col-md-3">
                <div class="card card-custom p-4 bg-light h-100 border-0">
                    <div class="step-number mb-3">1</div>
                    <h5 class="fw-bold mb-2">Isi Formulir</h5>
                    <p class="text-muted small mb-0">Lengkapi data pribadi, NISN, data orang tua, dan asal sekolah pada form online.</p>
                </div>
            </div>

            <div class="col-md-3">
                <div class="card card-custom p-4 bg-light h-100 border-0">
                    <div class="step-number mb-3">2</div>
                    <h5 class="fw-bold mb-2">Upload Berkas</h5>
                    <p class="text-muted small mb-0">Unggah foto/scan dokumen Kartu Keluarga, Akta Kelahiran, Ijazah, dan Pas Foto.</p>
                </div>
            </div>

            <div class="col-md-3">
                <div class="card card-custom p-4 bg-light h-100 border-0">
                    <div class="step-number mb-3">3</div>
                    <h5 class="fw-bold mb-2">Cetak Bukti</h5>
                    <p class="text-muted small mb-0">Simpan nomor pendaftaran unik dan cetak kartu bukti pendaftaran Anda.</p>
                </div>
            </div>

            <div class="col-md-3">
                <div class="card card-custom p-4 bg-light h-100 border-0">
                    <div class="step-number mb-3">4</div>
                    <h5 class="fw-bold mb-2">Verifikasi &amp; Lulus</h5>
                    <p class="text-muted small mb-0">Pantau status kelulusan Anda secara mandiri di menu Cek Kelulusan.</p>
                </div>
            </div>
        </div>

        <div class="text-center mt-5">
            <a href="<?= base_url('ppdb/daftar') ?>" class="btn btn-success btn-lg fw-bold px-5 py-3 rounded-pill shadow">
                <i class="bi bi-arrow-right-circle me-1"></i> Mulai Pendaftaran Sekarang
            </a>
        </div>
    </div>
</section>

<!-- Footer -->
<footer class="bg-dark text-white py-4 mt-5">
    <div class="container text-center">
        <p class="mb-1 fw-bold"><?= htmlspecialchars($setting->nama_sekolah) ?></p>
        <small class="text-muted"><?= htmlspecialchars($setting->alamat) ?> | Telp: <?= htmlspecialchars($setting->telepon) ?></small>
        <div class="mt-3 text-muted small">&copy; <?= date('Y') ?> <?= htmlspecialchars($setting->nama_sekolah) ?>. All rights reserved.</div>
    </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
