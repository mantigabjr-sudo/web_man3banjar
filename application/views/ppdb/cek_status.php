<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; background: #f8fafc; color: #334155; }
        .cek-card { background: #ffffff; border-radius: 24px; box-shadow: 0 10px 30px rgba(0,0,0,0.06); }
    </style>
</head>
<body class="py-5">

<div class="container">
    <div class="row justify-content-center">
        <div class="col-lg-6">
            
            <div class="text-center mb-4">
                <a href="<?= base_url('ppdb') ?>" class="btn btn-outline-secondary btn-sm mb-3 rounded-pill px-3">
                    <i class="bi bi-arrow-left me-1"></i> Kembali ke Portal PPDB
                </a>
                <h3 class="fw-extrabold text-dark">Cek Status Pendaftaran &amp; Kelulusan</h3>
                <p class="text-muted"><?= htmlspecialchars($setting->nama_sekolah) ?></p>
            </div>

            <div class="card cek-card p-4 p-md-5 mb-4">
                <form action="<?= base_url('ppdb/cek_status') ?>" method="POST">
                    <div class="mb-3">
                        <label class="form-label fw-bold small text-muted">NISN SISWA (10 DIGIT)</label>
                        <input type="text" name="nisn" class="form-control form-control-lg rounded-3" placeholder="Masukkan 10 digit NISN" required>
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-bold small text-muted">TANGGAL LAHIR</label>
                        <input type="date" name="tanggal_lahir" class="form-control form-control-lg rounded-3" required>
                    </div>

                    <button type="submit" class="btn btn-primary btn-lg w-100 fw-bold rounded-3 shadow-sm">
                        <i class="bi bi-search me-1"></i> Periksa Status
                    </button>
                </form>
            </div>

            <?php if($this->session->flashdata('error_cek')): ?>
                <div class="alert alert-danger text-center rounded-4 py-3 shadow-sm">
                    <i class="bi bi-exclamation-circle-fill me-1"></i> <?= $this->session->flashdata('error_cek') ?>
                </div>
            <?php endif; ?>

            <?php if(!empty($hasil)): ?>
                <div class="card cek-card p-4 p-md-5 border border-success">
                    <div class="text-center mb-3">
                        <span class="badge bg-success bg-opacity-10 text-success p-2 px-3 fw-bold rounded-pill mb-2">
                            Status: <?= strtoupper($hasil->status_pendaftaran) ?>
                        </span>
                        <h4 class="fw-bold text-dark mb-0"><?= htmlspecialchars($hasil->nama_lengkap) ?></h4>
                        <small class="text-muted font-monospace">No. Daftar: <?= $hasil->no_pendaftaran ?></small>
                    </div>

                    <div class="bg-light p-3 rounded-3 mb-3 small">
                        <div class="row mb-1">
                            <div class="col-5 text-muted">Asal Sekolah:</div>
                            <div class="col-7 fw-bold"><?= htmlspecialchars($hasil->sekolah_asal) ?></div>
                        </div>
                        <div class="row">
                            <div class="col-5 text-muted">Tanggal Daftar:</div>
                            <div class="col-7"><?= date('d/m/Y H:i', strtotime($hasil->created_at)) ?></div>
                        </div>
                    </div>

                    <?php if($hasil->status_pendaftaran == 'Lulus'): ?>
                        <div class="alert alert-success text-center py-2 mb-3 small fw-bold">
                            🎉 SELAMAT! Anda Dinyatakan LULUS Seleksi PPDB. Silakan lakukan daftar ulang di madrasah.
                        </div>
                    <?php endif; ?>

                    <a href="<?= base_url('ppdb/cetak/' . $hasil->no_pendaftaran) ?>" target="_blank" class="btn btn-outline-primary w-100 fw-bold rounded-3">
                        <i class="bi bi-printer me-1"></i> Cetak Ulang Bukti Pendaftaran
                    </a>
                </div>
            <?php endif; ?>

        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
