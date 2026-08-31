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
        .success-card { background: #ffffff; border-radius: 24px; box-shadow: 0 15px 35px rgba(0,0,0,0.06); }
    </style>
</head>
<body class="py-5">

<div class="container">
    <div class="row justify-content-center">
        <div class="col-lg-7">
            <div class="card success-card p-4 p-md-5 text-center">
                <div class="p-3 bg-success bg-opacity-10 text-success rounded-circle d-inline-flex fs-1 mx-auto mb-3">
                    <i class="bi bi-check-circle-fill"></i>
                </div>
                <h3 class="fw-extrabold text-dark mb-2">Pendaftaran Berhasil Terkirim!</h3>
                <p class="text-muted mb-4">Terima kasih telah mendaftar di <?= htmlspecialchars($setting->nama_sekolah) ?>. Simpan dan cetak bukti pendaftaran di bawah ini.</p>

                <div class="bg-light p-4 rounded-4 mb-4 text-start border">
                    <div class="row g-2">
                        <div class="col-sm-5 text-muted small fw-bold">NOMOR PENDAFTARAN:</div>
                        <div class="col-sm-7 fw-extrabold text-success font-monospace fs-5"><?= $pendaftar->no_pendaftaran ?></div>

                        <div class="col-sm-5 text-muted small fw-bold">NAMA LENGKAP:</div>
                        <div class="col-sm-7 fw-bold"><?= htmlspecialchars($pendaftar->nama_lengkap) ?></div>

                        <div class="col-sm-5 text-muted small fw-bold">NISN:</div>
                        <div class="col-sm-7"><?= htmlspecialchars($pendaftar->nisn) ?></div>

                        <div class="col-sm-5 text-muted small fw-bold">SEKOLAH ASAL:</div>
                        <div class="col-sm-7"><?= htmlspecialchars($pendaftar->sekolah_asal) ?></div>

                        <div class="col-sm-5 text-muted small fw-bold">WAKTU DAFTAR:</div>
                        <div class="col-sm-7"><?= date('d F Y, H:i', strtotime($pendaftar->created_at)) ?> WITA</div>
                    </div>
                </div>

                <div class="d-flex justify-content-center gap-3 flex-wrap">
                    <a href="<?= base_url('ppdb/cetak/' . $pendaftar->no_pendaftaran) ?>" target="_blank" class="btn btn-primary fw-bold px-4 py-2 rounded-pill shadow-sm">
                        <i class="bi bi-printer-fill me-1"></i> Cetak Bukti Pendaftaran
                    </a>
                    <a href="<?= base_url('ppdb') ?>" class="btn btn-outline-secondary fw-bold px-4 py-2 rounded-pill">
                        <i class="bi bi-house-door me-1"></i> Kembali ke Beranda
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

</body>
</html>
