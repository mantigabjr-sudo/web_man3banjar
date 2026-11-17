<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Akses Cetak Terkunci - MAN 3 Banjar</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f0f4f8;
            color: #2d3748;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .locked-card {
            background: #ffffff;
            border-radius: 16px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
            border: none;
            max-width: 580px;
            width: 100%;
            overflow: hidden;
            text-align: center;
        }

        .locked-header {
            background: linear-gradient(135deg, #1e293b 0%, #334155 100%);
            color: white;
            padding: 35px 25px 25px 25px;
        }

        .icon-lock {
            width: 75px;
            height: 75px;
            background: rgba(255, 255, 255, 0.1);
            border: 2px solid rgba(255, 255, 255, 0.3);
            border-radius: 50%;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 2rem;
            color: #f59e0b;
            margin-bottom: 15px;
        }

        .detail-box {
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 10px;
            padding: 14px 18px;
            text-align: left;
            margin-bottom: 20px;
            font-size: 0.9rem;
        }
    </style>
</head>
<body>

    <div class="card locked-card">
        <div class="locked-header">
            <div class="icon-lock">
                <i class="fa fa-lock"></i>
            </div>
            <h4 class="fw-bold mb-1">Akses Cetak Formulir Terbatas</h4>
            <p class="mb-0 text-white-50 small">Memerlukan Login Akun Pegawai atau Hak Akses Admin</p>
        </div>

        <div class="card-body p-4 p-md-5">

            <div class="alert alert-warning text-start small mb-4">
                <div class="d-flex">
                    <i class="fa fa-shield-halved fa-2x text-warning me-3"></i>
                    <div>
                        <strong>Pencetakan Terkunci:</strong> Demi keamanan data dan kepatuhan administrasi kepegawaian, formulir cuti resmi BKN hanya dapat dicetak oleh <strong>Admin Tata Usaha</strong> atau oleh <strong>Pegawai Pemohon</strong> melalui akun masing-masing setelah mendapatkan persetujuan resmi.
                    </div>
                </div>
            </div>

            <div class="detail-box">
                <div class="d-flex justify-content-between border-bottom pb-2 mb-2">
                    <span class="text-muted">Nomor Permohonan</span>
                    <strong class="text-dark">#CT-<?= str_pad($cuti->id, 5, '0', STR_PAD_LEFT) ?></strong>
                </div>
                <div class="d-flex justify-content-between border-bottom pb-2 mb-2">
                    <span class="text-muted">Nama Pegawai</span>
                    <strong><?= htmlspecialchars($cuti->nama_lengkap) ?></strong>
                </div>
                <div class="d-flex justify-content-between border-bottom pb-2 mb-2">
                    <span class="text-muted">Jenis Cuti</span>
                    <span><?= htmlspecialchars($cuti->jenis_cuti) ?> (<?= $cuti->lama_cuti_jumlah ?> <?= $cuti->lama_cuti_satuan ?>)</span>
                </div>
                <div class="d-flex justify-content-between">
                    <span class="text-muted">Status Pengajuan</span>
                    <?php if ($cuti->status == 'Draft'): ?>
                        <span class="badge bg-warning text-dark"><i class="fa fa-clock me-1"></i>Menunggu Verifikasi TU</span>
                    <?php elseif ($cuti->status == 'Disetujui Kepala'): ?>
                        <span class="badge bg-info text-dark"><i class="fa fa-check-circle me-1"></i>Disetujui Kepala Madrasah</span>
                    <?php elseif ($cuti->status == 'Aktif'): ?>
                        <span class="badge bg-success"><i class="fa fa-check-double me-1"></i>Disetujui Penuh (Aktif)</span>
                    <?php elseif ($cuti->status == 'Ditolak'): ?>
                        <span class="badge bg-danger"><i class="fa fa-times-circle me-1"></i>Ditolak</span>
                    <?php else: ?>
                        <span class="badge bg-secondary"><?= $cuti->status ?></span>
                    <?php endif; ?>
                </div>
            </div>

            <p class="text-muted small mb-4">
                Jika Anda adalah pegawai yang bersangkutan atau pimpinan/admin, silakan login ke akun Sistem Informasi Madrasah untuk mencetak formulir resmi yang telah disetujui.
            </p>

            <div class="d-grid gap-2">
                <a href="<?= site_url('auth') ?>" class="btn btn-success fw-semibold py-2">
                    <i class="fa fa-right-to-bracket me-2"></i>Login ke Akun Madrasah
                </a>
                <a href="<?= site_url('cuti') ?>" class="btn btn-outline-secondary py-2">
                    <i class="fa fa-arrow-left me-2"></i>Kembali ke Halaman Cuti
                </a>
            </div>

        </div>
    </div>

</body>
</html>
