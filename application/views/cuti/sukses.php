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

        .btn-print {
            background: linear-gradient(135deg, #198754 0%, #157347 100%);
            border: none;
            padding: 12px 28px;
            font-weight: 600;
            font-size: 1.05rem;
            border-radius: 8px;
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
            <p class="mb-0 opacity-90 small">Formulir Permintaan dan Pemberian Cuti Resmi Telah Dibuat</p>
        </div>

        <div class="card-body p-4 p-md-5">

            <div class="alert alert-success d-flex align-items-center text-start mb-4 py-2 px-3 small">
                <i class="fa fa-info-circle fa-2x me-3 text-success"></i>
                <div>
                    Permohonan cuti Anda telah tercatat di sistem <strong>LabSys Urusan Tata Usaha</strong> dengan status <strong>Draft (Menunggu Persetujuan Atasan Langsung)</strong>.
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
                    <td><span class="badge bg-warning text-dark"><i class="fa fa-hourglass-half me-1"></i><?= $cuti->status ?></span></td>
                </tr>
            </table>

            <div class="d-grid gap-2 d-md-flex justify-content-center">
                <a href="<?= site_url('cuti/cetak/' . $cuti->id) ?>" target="_blank" class="btn btn-success btn-print text-white shadow">
                    <i class="fa fa-print me-2"></i>Cetak / Unduh Formulir PDF (BKN)
                </a>
                <a href="<?= site_url('cuti') ?>" class="btn btn-outline-secondary px-4 py-2">
                    <i class="fa fa-arrow-left me-1"></i>Kembali
                </a>
            </div>

            <div class="mt-4 pt-3 border-top text-muted small">
                <i class="fa fa-file-pdf text-danger me-1"></i>Anda dapat mencetak formulir di atas dan menyerahkannya ke Urusan Tata Usaha atau menunggu proses verifikasi lebih lanjut.
            </div>

        </div>
    </div>

</body>
</html>
