<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Pendaftaran Berhasil | PMB MAN 3 Banjar</title>
    <link rel="icon" type="image/png" href="<?= base_url('assets/img/favicon.png') ?>">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #f0fdf4, #ecfdf5, #f8fafc);
            font-family: system-ui, -apple-system, sans-serif;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }
        .success-card {
            max-width: 520px;
            width: 100%;
            background: #ffffff;
            border-radius: 24px;
            border: 1.5px solid #e2e8f0;
            box-shadow: 0 20px 45px rgba(5,150,105,.12);
            overflow: hidden;
            text-align: center;
        }
        .account-box {
            background: #f8fafc;
            border: 1.5px dashed #cbd5e1;
            border-radius: 16px;
            padding: 18px 20px;
            margin: 20px 0;
            text-align: left;
        }
        .account-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 8px 0;
            border-bottom: 1px solid #e2e8f0;
        }
        .account-row:last-child {
            border-bottom: none;
        }
    </style>
</head>
<body>

<div class="success-card p-4 p-md-5" id="buktiCard">
    <div class="d-inline-flex align-items-center justify-content-center bg-success text-white rounded-circle mb-3" style="width: 64px; height: 64px; font-size: 32px;">
        <i class="bi bi-check-lg"></i>
    </div>

    <h3 class="fw-bold text-success mb-1">Pendaftaran Berhasil!</h3>
    <p class="text-muted small mb-0">Akun pendaftaran online Anda di <strong>MAN 3 Banjar</strong> telah berhasil dibuat.</p>

    <div class="account-box">
        <div class="account-row">
            <span class="text-secondary small fw-semibold">No. Pendaftaran</span>
            <strong class="text-dark"><?= htmlspecialchars($no_pendaftaran ?? '-') ?></strong>
        </div>
        <div class="account-row">
            <span class="text-secondary small fw-semibold">Username Login (NISN)</span>
            <strong class="text-success"><?= htmlspecialchars($username ?? '-') ?></strong>
        </div>
        <div class="account-row">
            <span class="text-secondary small fw-semibold">Password Akun</span>
            <strong class="text-primary font-monospace"><?= htmlspecialchars($password ?? '-') ?></strong>
        </div>
    </div>

    <div class="alert alert-warning py-2 px-3 small rounded-3 text-start mb-4" style="font-size: 12px;">
        <i class="bi bi-exclamation-triangle-fill me-1"></i> <strong>Penting:</strong> Simpan atau screenshot Username dan Password di atas untuk login ke Dashboard Peserta.
    </div>

    <div class="d-grid gap-2">
        <a href="<?= base_url('ppdb/login') ?>" class="btn btn-success fw-bold py-2 rounded-pill shadow-sm" style="background:#059669; border-color:#059669;">
            <i class="bi bi-box-arrow-in-right me-1"></i> Masuk ke Dashboard Peserta
        </a>
        <button type="button" onclick="downloadBukti()" class="btn btn-outline-primary fw-bold py-2 rounded-pill">
            <i class="bi bi-download me-1"></i> Simpan / Unduh Bukti Akun (PNG)
        </button>
        <a href="<?= base_url('ppdb') ?>" class="btn btn-light text-muted small rounded-pill mt-1">
            Kembali ke Beranda
        </a>
    </div>
</div>

<script src="https://html2canvas.hertzen.com/dist/html2canvas.min.js"></script>
<script>
function downloadBukti(){
    html2canvas(document.querySelector("#buktiCard")).then(canvas => {
        let link = document.createElement('a');
        link.download = 'bukti-akun-pmb-<?= $username ?? 'peserta' ?>.png';
        link.href = canvas.toDataURL();
        link.click();
    });
}
</script>
</body>
</html>