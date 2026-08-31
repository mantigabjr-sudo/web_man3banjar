<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?> - <?= $setting->nama_sekolah ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <style>
        body {
            background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        .login-card {
            background: #ffffff;
            border-radius: 24px;
            padding: 40px;
            width: 100%;
            max-width: 420px;
            box-shadow: 0 20px 40px rgba(0,0,0,0.3);
        }
    </style>
</head>
<body>

<div class="login-card text-center">
    <div class="mb-4">
        <div class="p-3 bg-primary bg-opacity-10 text-primary rounded-circle d-inline-flex fs-1 mb-2">
            <i class="bi bi-clouds-fill"></i>
        </div>
        <h4 class="fw-bold text-dark mb-1">Pengelola Web Cloud</h4>
        <small class="text-muted"><?= htmlspecialchars($setting->nama_sekolah) ?></small>
    </div>

    <?php if($this->session->flashdata('error_login')): ?>
        <div class="alert alert-danger py-2 text-start small">
            <i class="bi bi-exclamation-triangle-fill me-1"></i> <?= $this->session->flashdata('error_login') ?>
        </div>
    <?php endif; ?>

    <form action="<?= base_url('login') ?>" method="POST" class="text-start">
        <div class="mb-3">
            <label class="form-label fw-bold small text-muted">USERNAME</label>
            <input type="text" name="username" class="form-control form-control-lg rounded-3" placeholder="Masukkan username" required autofocus>
        </div>

        <div class="mb-4">
            <label class="form-label fw-bold small text-muted">PASSWORD</label>
            <input type="password" name="password" class="form-control form-control-lg rounded-3" placeholder="Masukkan password" required>
        </div>

        <button type="submit" class="btn btn-primary btn-lg w-100 fw-bold rounded-3 shadow-sm">
            <i class="bi bi-box-arrow-in-right me-1"></i> Masuk ke Dashboard
        </button>
    </form>

    <div class="mt-4 pt-3 border-top text-center">
        <a href="<?= base_url() ?>" class="text-muted text-decoration-none small">
            <i class="bi bi-arrow-left me-1"></i> Kembali ke Website Utama
        </a>
    </div>
</div>

</body>
</html>
