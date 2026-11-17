<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pengajuan Cuti Berhasil - MAN 3 Banjar</title>
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

        .success-card {
            background: #ffffff;
            border-radius: 16px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
            border: none;
            max-width: 650px;
            width: 100%;
            overflow: hidden;
            text-align: center;
        }

        .success-header {
            background: linear-gradient(135deg, #0d5c3a 0%, #198754 100%);
            color: white;
            padding: 35px 25px 25px 25px;
        }

        .icon-circle {
            width: 75px;
            height: 75px;
            background: rgba(255, 255, 255, 0.2);
            border: 3px solid #ffffff;
            border-radius: 50%;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 2.2rem;
            margin-bottom: 15px;
        }

        .detail-table {
            width: 100%;
            text-align: left;
            font-size: 0.95rem;
        }

        .detail-table td {
            padding: 8px 12px;
            border-bottom: 1px solid #f1f5f9;
        }
    </style>
</head>
<body>

    <div class="card success-card">
        <div class="success-header">
            <div class="icon-circle">
                <i class="fa fa-check text-white"></i>
            </div>
            <h3 class="fw-bold mb-1">Pengajuan Cuti Berhasil Dikirim!</h3>
            <p class="mb-0 opacity-90 small">Permohonan Mandiri (Mode Bypass Tanpa Login) Telah Didaftarkan</p>
        </div>

        <div class="card-body p-4 p-md-5">

            <div class="alert alert-info d-flex align-items-center text-start mb-4 py-2 px-3 small">
                <i class="fa fa-info-circle fa-2x me-3 text-primary"></i>
                <div>
                    Permohonan cuti Anda telah tercatat dengan status <strong>Draft (Menunggu Persetujuan Atasan Langsung & Admin TU)</strong>. Pengajuan ini akan ditinjau dan disetujui terlebih dahulu oleh Kepala Madrasah.
                </div>
            </div>

            <table class="detail-table mb-4">
                <tr>
                    <td class="text-muted" width="40%">ID Registrasi Cuti</td>
                    <td class="fw-bold text-success">#CT-<?= str_pad($cuti->id, 5, '0', STR_PAD_LEFT) ?></td>
                </tr>
                <tr>
                    <td class="text-muted">Nama Pegawai</td>
                    <td class="fw-bold"><?= htmlspecialchars($cuti->nama_lengkap) ?></td>
                </tr>
                <tr>
                    <td class="text-muted">NIP</td>
                    <td><?= htmlspecialchars($cuti->nip) ?></td>
                </tr>
                <tr>
                    <td class="text-muted">Jenis Cuti</td>
                    <td><span class="badge bg-success-subtle text-success px-2 py-1"><?= htmlspecialchars($cuti->jenis_cuti) ?></span></td>
                </tr>
                <tr>
                    <td class="text-muted">Lamanya Cuti</td>
                    <td class="fw-bold"><?= $cuti->lama_cuti_jumlah ?> <?= $cuti->lama_cuti_satuan ?></td>
                </tr>
                <tr>
                    <td class="text-muted">Tanggal Pelaksanaan</td>
                    <td><?= date('d M Y', strtotime($cuti->tanggal_mulai)) ?> s.d <?= date('d M Y', strtotime($cuti->tanggal_selesai)) ?></td>
                </tr>
                <tr>
                    <td class="text-muted">Alasan Cuti</td>
                    <td><?= htmlspecialchars($cuti->alasan_cuti) ?></td>
                </tr>
                <tr>
                    <td class="text-muted">Status Pengajuan</td>
                    <td><span class="badge bg-warning text-dark"><i class="fa fa-hourglass-half me-1"></i>Menunggu Verifikasi TU</span></td>
                </tr>
            </table>

            <div class="p-3 bg-light rounded-3 text-start small border mb-4">
                <div class="fw-bold text-dark mb-1"><i class="fa fa-shield-halved text-success me-1"></i>Informasi Pencetakan Formulir Resmi:</div>
                <div class="text-muted">
                    Sesuai prosedur resmi, formulir cuti BKN tidak dapat dicetak secara bebas. Pencetakan hanya dapat dilakukan oleh <strong>Admin Tata Usaha</strong> atau melalui <strong>Akun Pribadi Pegawai masing-masing</strong> setelah permohonan disetujui oleh Kepala Madrasah.
                </div>
            </div>

            <div class="d-grid gap-2 d-md-flex justify-content-center">
                <a href="<?= site_url('cuti') ?>#tab-status" class="btn btn-success px-4 py-2 fw-semibold">
                    <i class="fa fa-list-check me-1"></i>Lihat Status di Monitoring
                </a>
                <a href="<?= site_url('auth') ?>" class="btn btn-outline-primary px-3 py-2 fw-semibold">
                    <i class="fa fa-right-to-bracket me-1"></i>Login Akun Madrasah
                </a>
                <a href="<?= site_url('cuti') ?>" class="btn btn-outline-secondary px-3 py-2">
                    <i class="fa fa-arrow-left me-1"></i>Kembali
                </a>
            </div>

        </div>
    </div>

</body>
</html>
