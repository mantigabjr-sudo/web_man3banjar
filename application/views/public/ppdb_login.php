<?php
$nama_madrasah = 'MAN 3 Banjar';
$nama = htmlspecialchars($nama_ppdb ?? 'PMB');
$judul_panjang = !empty($settings->judul_panjang_ppdb) ? htmlspecialchars($settings->judul_panjang_ppdb) : 'Penerimaan Murid Baru';

$logo_file = FCPATH.'assets/img/logo-madrasah.png';
$logo_url  = base_url('assets/img/logo-madrasah.png');
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Login Calon Siswa <?= $nama ?> | <?= $nama_madrasah ?></title>

    <link rel="icon" type="image/png" href="<?= base_url('assets/img/favicon.png') ?>">
    <link rel="shortcut icon" type="image/png" href="<?= base_url('assets/img/favicon.png') ?>">
    <link rel="apple-touch-icon" href="<?= base_url('assets/img/favicon.png') ?>">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">

    <link rel="stylesheet" href="<?= base_url('assets/css/ppdb-login.css?v=2') ?>">
</head>

<body>

<div class="ppdb-login-page">

    <nav class="ppdb-login-nav">
        <div class="container">
            <div class="ppdb-login-nav-inner">

                <a href="<?= base_url('pmb') ?>" class="ppdb-login-brand">
                    <div class="ppdb-login-logo">
                        <?php if(file_exists($logo_file)): ?>
                            <img src="<?= $logo_url ?>" alt="Logo <?= $nama_madrasah ?>">
                        <?php else: ?>
                            M3
                        <?php endif; ?>
                    </div>

                    <div>
                        <strong><?= $nama ?> <?= $nama_madrasah ?></strong>
                        <small>Portal <?= $judul_panjang ?></small>
                    </div>
                </a>

                <div class="ppdb-login-nav-actions">
                    <a href="<?= base_url('pmb') ?>" class="btn-nav-soft">
                        Daftar <?= $nama ?>
                    </a>

                    <a href="<?= base_url() ?>" class="btn-nav-outline">
                        Beranda
                    </a>
                </div>

            </div>
        </div>
    </nav>

    <main class="ppdb-login-main">
        <div class="container">

            <div class="ppdb-login-grid">

                <section class="ppdb-login-info">
                    <div class="info-badge">
                        <i class="bi bi-shield-lock-fill me-1"></i> Login Calon Siswa
                    </div>

                    <h1>
                        Masuk ke Akun <?= $nama ?> Online
                    </h1>

                    <p>
                        Gunakan NISN dan password yang dibuat saat registrasi awal untuk melengkapi data pendaftaran, mengunggah berkas, dan memantau status seleksi <?= $nama ?>.
                    </p>

                    <div class="info-card-list">

                        <div class="info-card">
                            <div class="info-icon">1</div>
                            <div>
                                <strong>Lengkapi Biodata</strong>
                                <small>Isi data diri, alamat, orang tua/wali, dan sekolah asal.</small>
                            </div>
                        </div>

                        <div class="info-card">
                            <div class="info-icon">2</div>
                            <div>
                                <strong>Upload Dokumen</strong>
                                <small>Unggah KK, akta, ijazah/SKL, dan dokumen pendukung lainnya.</small>
                            </div>
                        </div>

                        <div class="info-card">
                            <div class="info-icon">3</div>
                            <div>
                                <strong>Pantau Status Kelulusan</strong>
                                <small>Cek perkembangan seleksi langsung melalui akun peserta.</small>
                            </div>
                        </div>

                    </div>
                </section>

                <section class="ppdb-login-box">

                    <div class="login-box-head">
                        <div class="login-mini-logo">
                            <?php if(file_exists($logo_file)): ?>
                                <img src="<?= $logo_url ?>" alt="Logo">
                            <?php else: ?>
                                M3
                            <?php endif; ?>
                        </div>

                        <h3>Login Calon Siswa <?= $nama ?></h3>
                        <p>Masukkan NISN dan password akun pendaftaran Anda.</p>
                    </div>

                    <?php if($this->session->flashdata('error')): ?>
                        <div class="alert alert-danger rounded-4 fw-bold">
                            <?= $this->session->flashdata('error') ?>
                        </div>
                    <?php endif; ?>

                    <?php if($this->session->flashdata('success')): ?>
                        <div class="alert alert-success rounded-4 fw-bold">
                            <?= $this->session->flashdata('success') ?>
                        </div>
                    <?php endif; ?>

                    <form method="post" action="<?= base_url('ppdb/auth') ?>">

                        <div class="login-field">
                            <label>NISN</label>
                            <input type="text"
                                   name="username"
                                   class="login-input"
                                   placeholder="Masukkan 10 digit NISN"
                                   maxlength="10"
                                   pattern="[0-9]{10}"
                                   inputmode="numeric"
                                   required
                                   oninput="this.value=this.value.replace(/[^0-9]/g,'').slice(0,10)">
                        </div>

                        <div class="login-field">
                            <label>Password</label>

                            <div class="password-wrap">
                                <input type="password"
                                       name="password"
                                       id="passwordInput"
                                       class="login-input password-input"
                                       placeholder="Masukkan password"
                                       required>

                                <button type="button"
                                        class="password-toggle"
                                        id="togglePassword">
                                    Lihat
                                </button>
                            </div>
                        </div>

                        <button class="btn-login-submit">
                            Login Peserta
                        </button>

                    </form>

                    <div class="login-help-box">
                        <p>
                            Belum punya akun <?= htmlspecialchars($nama_ppdb ?? 'PPDB') ?>?
                        </p>

                        <a href="<?= base_url('ppdb') ?>">
                            Daftar Sekarang
                        </a>
                    </div>

                    <div class="login-note">
                        <strong>Catatan:</strong>
                        Gunakan NISN dan password yang dibuat saat registrasi awal. Hubungi panitia <?= htmlspecialchars($nama_ppdb ?? 'PPDB') ?> jika lupa password atau data tidak ditemukan.
                    </div>

                </section>

            </div>

        </div>
    </main>

</div>

<script>
document.addEventListener('DOMContentLoaded', function(){

    const input = document.getElementById('passwordInput');
    const button = document.getElementById('togglePassword');

    if(input && button){
        button.addEventListener('click', function(){

            if(input.type === 'password'){
                input.type = 'text';
                button.textContent = 'Sembunyi';
            } else {
                input.type = 'password';
                button.textContent = 'Lihat';
            }

        });
    }

});
</script>

</body>
</html>