<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($title ?? 'Bukti Verifikasi Foto') ?> - MAN 3 Banjar</title>
    
    <!-- Bootstrap 5 CSS & Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    
    <!-- Google Fonts Inter -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <style>
        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, #ecfdf5 0%, #f1f5f9 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 24px 14px;
        }
        .cert-card {
            background: #ffffff;
            border-radius: 28px;
            box-shadow: 0 24px 60px rgba(5, 150, 105, 0.14), 0 4px 16px rgba(0,0,0,0.04);
            border: 1.5px solid #d1fae5;
            overflow: hidden;
            width: 100%;
            max-width: 580px;
            position: relative;
        }
        .cert-header {
            background: linear-gradient(135deg, #064e3b 0%, #059669 60%, #10b981 100%);
            color: #ffffff;
            padding: 28px 24px 20px;
            text-align: center;
            position: relative;
        }
        .cert-header::after {
            content: "";
            position: absolute;
            top: -20px;
            right: -20px;
            width: 100px;
            height: 100px;
            background: radial-gradient(circle, rgba(255,255,255,0.2) 0%, transparent 70%);
            border-radius: 50%;
        }
        .photo-frame {
            width: 130px;
            height: 165px;
            border-radius: 16px;
            overflow: hidden;
            border: 4px solid #ffffff;
            box-shadow: 0 10px 25px rgba(0,0,0,0.15);
            background: #0f172a;
            margin: 0 auto;
            position: relative;
        }
        .photo-frame img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        .stamp-verified {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 6px 16px;
            border-radius: 999px;
            background: #ecfdf5;
            color: #047857;
            font-weight: 800;
            font-size: 13px;
            border: 2px solid #a7f3d0;
            box-shadow: 0 4px 12px rgba(16, 185, 129, 0.15);
        }
        @media print {
            body {
                background: white;
                padding: 0;
            }
            .cert-card {
                box-shadow: none;
                border: 1px solid #000;
                max-width: 100%;
            }
            .no-print {
                display: none !important;
            }
        }
    </style>
</head>
<body>

<div class="cert-card">
    <!-- Header -->
    <div class="cert-header">
        <span class="badge bg-white text-success fw-bold px-3 py-1 rounded-pill mb-2" style="font-size: 11px;">
            <i class="bi bi-mortarboard-fill me-1"></i> BUKTI RESMI DIGITAL
        </span>
        <h4 class="fw-bold mb-1 text-white">Tanda Terima Verifikasi Foto Ijazah</h4>
        <p class="mb-0 text-white-50 small">Madrasah Aliyah Negeri 3 Banjar &bull; Tahun Ajaran 2025/2026</p>
    </div>

    <!-- Body -->
    <div class="p-4 p-md-5 text-center">

        <!-- Notifikasi -->
        <?php if($this->session->flashdata('success')): ?>
            <div class="alert alert-success border-0 rounded-4 p-3 mb-4 text-start small d-flex align-items-center gap-2">
                <i class="bi bi-check-circle-fill text-success fs-4"></i>
                <div><?= $this->session->flashdata('success') ?></div>
            </div>
        <?php endif; ?>

        <!-- Foto Terverifikasi -->
        <div class="mb-3">
            <div class="photo-frame">
                <?php if(!empty($verif['file_verified']) && file_exists(FCPATH . 'uploads/foto_ijazah/verified/' . $verif['file_verified'])): ?>
                    <img src="<?= base_url('uploads/foto_ijazah/verified/' . $verif['file_verified']) ?>" alt="Foto Terverifikasi">
                <?php else: ?>
                    <div class="d-flex align-items-center justify-content-center h-100 text-white">
                        <i class="bi bi-person fs-1"></i>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Stempel Verified -->
        <div class="mb-4">
            <span class="stamp-verified">
                <i class="bi bi-patch-check-fill text-success fs-5"></i> FOTO TERVERIFIKASI RESMI
            </span>
        </div>

        <!-- Tabel Rincian -->
        <div class="card bg-light border-0 rounded-4 p-3 text-start mb-4">
            <div class="row g-2" style="font-size: 13.5px;">
                <div class="col-5 text-muted">Nama Lengkap:</div>
                <div class="col-7 fw-bold text-dark"><?= htmlspecialchars($siswa['nama_lengkap']) ?></div>

                <div class="col-5 text-muted">NISN Siswa:</div>
                <div class="col-7 fw-bold text-success font-monospace"><?= htmlspecialchars($verif['nisn'] ?? $siswa['nisn']) ?></div>

                <div class="col-5 text-muted">Kelas:</div>
                <div class="col-7 fw-bold text-dark"><?= htmlspecialchars($siswa['nama_kelas']) ?></div>

                <div class="col-5 text-muted">Nama File Resmi:</div>
                <div class="col-7 fw-bold text-primary font-monospace"><?= htmlspecialchars($verif['file_verified']) ?></div>

                <div class="col-5 text-muted">Waktu Verifikasi:</div>
                <div class="col-7 text-dark"><?= date('d F Y, H:i:s', strtotime($verif['verified_at'] ?? date('Y-m-d H:i:s'))) ?> WITA</div>
            </div>
        </div>

        <p class="text-muted small mb-4" style="font-size: 12px;">
            Foto di atas telah tercatat dan terkunci di database madrasah sebagai foto resmi ijazah Anda. Silakan simpan halaman ini sebagai bukti tanda terima.
        </p>

        <!-- Tombol Aksi -->
        <div class="d-flex gap-2 justify-content-center flex-wrap no-print">
            <button onclick="window.print()" class="btn btn-outline-success fw-bold rounded-pill px-4">
                <i class="bi bi-printer-fill me-1"></i> Cetak / Simpan PDF
            </button>
            <a href="<?= base_url('verifikasi_foto_ijazah/logout') ?>" class="btn btn-secondary fw-bold rounded-pill px-4">
                <i class="bi bi-check-lg me-1"></i> Selesai
            </a>
        </div>

    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
