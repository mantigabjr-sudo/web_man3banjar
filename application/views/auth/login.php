<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="theme-color" content="#16a34a">
<link rel="manifest" href="<?= base_url('manifest.json') ?>">

<title>Login Sistem</title>
<link rel="icon" href="<?= base_url('assets/brand/logo-man3.png') ?>" type="image/png">

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

<style>
*{
    box-sizing:border-box;
}

body{
    margin:0;
    min-height:100vh;
    font-family:'Segoe UI',sans-serif;
    background:
        radial-gradient(circle at top left, rgba(34,197,94,.20), transparent 32%),
        radial-gradient(circle at bottom right, rgba(250,204,21,.18), transparent 30%),
        linear-gradient(135deg,#f8fafc,#ecfdf5);
    display:flex;
    align-items:center;
    justify-content:center;
    padding:24px;
}

.login-shell{
    width:100%;
    max-width:1100px;
    display:grid;
    grid-template-columns:1.05fr .95fr;
    background:white;
    border-radius:34px;
    overflow:hidden;
    box-shadow:0 30px 80px rgba(15,23,42,.14);
    border:1px solid #e2e8f0;
}

.login-brand{
    position:relative;
    min-height:620px;
    padding:44px;
    background:
        radial-gradient(circle at top right, rgba(250,204,21,.25), transparent 34%),
        linear-gradient(135deg,#064e3b,#16a34a);
    color:white;
    overflow:hidden;
}

.login-brand::before{
    content:"";
    position:absolute;
    width:340px;
    height:340px;
    border-radius:50%;
    background:rgba(255,255,255,.08);
    right:-120px;
    bottom:-120px;
}

.logo-box{
    width:76px;
    height:76px;
    border-radius:24px;
    background:rgba(255,255,255,.16);
    border:1px solid rgba(255,255,255,.22);
    display:flex;
    align-items:center;
    justify-content:center;
    font-size:34px;
    font-weight:950;
    margin-bottom:28px;
}

.login-brand h1{
    font-size:42px;
    line-height:1.1;
    font-weight:950;
    margin-bottom:16px;
}

.login-brand p{
    color:rgba(255,255,255,.78);
    font-size:15px;
    line-height:1.8;
    font-weight:650;
    max-width:520px;
}

.brand-points{
    display:grid;
    gap:14px;
    margin-top:34px;
}

.brand-point{
    display:flex;
    gap:12px;
    align-items:flex-start;
    padding:14px;
    border-radius:20px;
    background:rgba(255,255,255,.10);
    border:1px solid rgba(255,255,255,.13);
}

.brand-point i{
    width:34px;
    height:34px;
    border-radius:50%;
    background:rgba(255,255,255,.18);
    display:flex;
    align-items:center;
    justify-content:center;
    flex-shrink:0;
}

.brand-point strong{
    display:block;
    font-size:14px;
    font-weight:900;
}

.brand-point small{
    display:block;
    color:rgba(255,255,255,.72);
    font-size:12px;
    font-weight:650;
    margin-top:3px;
}

.login-panel{
    padding:44px;
    display:flex;
    align-items:center;
}

.login-card{
    width:100%;
}

.login-top{
    margin-bottom:24px;
}

.login-top small{
    display:inline-flex;
    padding:8px 12px;
    border-radius:999px;
    background:#dcfce7;
    color:#166534;
    font-weight:900;
    font-size:12px;
    margin-bottom:14px;
}

.login-top h2{
    color:#0f172a;
    font-size:30px;
    font-weight:950;
    margin-bottom:6px;
}

.login-top p{
    color:#64748b;
    font-size:14px;
    font-weight:650;
    margin:0;
}

.role-tabs{
    display:grid;
    grid-template-columns:1fr 1fr;
    gap:10px;
    background:#f8fafc;
    border:1px solid #e2e8f0;
    padding:8px;
    border-radius:20px;
    margin-bottom:22px;
}

.role-tab{
    border:0;
    min-height:50px;
    border-radius:16px;
    background:transparent;
    color:#475569;
    font-weight:950;
    transition:.2s;
}

.role-tab.active{
    background:linear-gradient(135deg,#15803d,#22c55e);
    color:white;
    box-shadow:0 10px 25px rgba(34,197,94,.22);
}

.login-field{
    margin-bottom:16px;
}

.login-field label{
    display:block;
    color:#166534;
    font-size:13px;
    font-weight:900;
    margin-bottom:7px;
}

.input-wrap{
    position:relative;
}

.input-wrap i{
    position:absolute;
    left:15px;
    top:50%;
    transform:translateY(-50%);
    color:#16a34a;
    font-size:18px;
}

.login-input{
    width:100%;
    min-height:54px;
    border:1px solid #d1fae5;
    background:#f8fafc;
    border-radius:17px;
    padding:12px 48px;
    color:#0f172a;
    font-weight:750;
    outline:none;
}

.login-input:focus{
    background:white;
    border-color:#22c55e;
    box-shadow:0 0 0 4px rgba(34,197,94,.12);
}

.toggle-password{
    position:absolute;
    right:12px;
    top:50%;
    transform:translateY(-50%);
    border:0;
    background:transparent;
    color:#64748b;
    font-size:18px;
}

.login-options{
    display:flex;
    justify-content:space-between;
    gap:12px;
    align-items:center;
    flex-wrap:wrap;
    margin:6px 0 20px;
}

.login-options label{
    color:#64748b;
    font-size:13px;
    font-weight:750;
}

.login-options a{
    color:#16a34a;
    font-size:13px;
    font-weight:900;
    text-decoration:none;
}

.btn-login{
    width:100%;
    min-height:54px;
    border:0;
    border-radius:18px;
    background:linear-gradient(135deg,#15803d,#22c55e);
    color:white;
    font-weight:950;
    box-shadow:0 14px 30px rgba(34,197,94,.24);
}

.btn-login:hover{
    color:white;
    filter:brightness(.98);
}

.back-home{
    margin-top:18px;
    display:flex;
    justify-content:center;
}

.back-home a{
    color:#475569;
    font-size:13px;
    font-weight:850;
    text-decoration:none;
}

.alert{
    border-radius:18px;
    font-weight:750;
}

@media(max-width:992px){
    .login-shell{
        grid-template-columns:1fr;
        max-width:600px;
    }

    .login-brand{
        min-height:auto;
        padding:34px;
    }

    .login-brand h1{
        font-size:32px;
    }

    .login-panel{
        padding:34px;
    }
}

@media(max-width:576px){
    body{
        padding:14px;
    }

    .login-shell{
        border-radius:26px;
    }

    .login-brand,
    .login-panel{
        padding:24px;
    }

    .role-tabs{
        grid-template-columns:1fr;
    }

    .login-top h2{
        font-size:26px;
    }
}
</style>
</head>

<body>

<div class="login-shell">

    <section class="login-brand">
        <div class="logo-box" style="background: white; border: none; padding: 5px;">
            <img src="<?= base_url('assets/brand/logo-man3.png') ?>" alt="Logo Madrasah" style="width: 100%; height: 100%; object-fit: contain;">
        </div>

        <h1>Sistem Informasi Madrasah</h1>

        <p>
            Portal terpadu untuk mengelola layanan administrasi, akademik, PPDB, berita,
            mutasi, inventaris, dan data madrasah secara digital.
        </p>

        <div class="brand-points">
            <div class="brand-point">
                <i class="bi bi-check2-circle"></i>
                <div>
                    <strong>Satu Pintu (Single Sign-On)</strong>
                    <small>Sistem secara otomatis mendeteksi apakah Anda Admin, Guru, TU, atau Kepsek.</small>
                </div>
            </div>

            <div class="brand-point">
                <i class="bi bi-shield-lock-fill"></i>
                <div>
                    <strong>Keamanan Kredensial</strong>
                    <small>Bagi Guru (ASN), gunakan NIP. Bagi Guru (Non-ASN), gunakan NIK atau ID khusus.</small>
                </div>
            </div>

            <div class="brand-point">
                <i class="bi bi-cloud-check"></i>
                <div>
                    <strong>Data Terintegrasi</strong>
                    <small>PPDB, siswa, mutasi, berita, e-raport dan layanan madrasah terkelola dalam satu tempat.</small>
                </div>
            </div>
        </div>
    </section>

    <section class="login-panel">
        <div class="login-card">

            <div class="login-top">
                <small id="roleLabel">Portal Terpadu</small>
                <h2>Selamat Datang</h2>
                <p>Gunakan username dan password yang telah diberikan.</p>
            </div>

            <?php if($this->session->flashdata('error')): ?>
                <div class="alert alert-danger">
                    <?= htmlspecialchars($this->session->flashdata('error'), ENT_QUOTES, 'UTF-8') ?>
                </div>
            <?php endif; ?>

            <?php if($this->session->flashdata('success')): ?>
                <div class="alert alert-success">
                    <?= htmlspecialchars($this->session->flashdata('success'), ENT_QUOTES, 'UTF-8') ?>
                </div>
            <?php endif; ?>

            <form method="post" action="<?= base_url('auth/login') ?>">

                <div class="login-field">
                    <label>Username</label>
                    <div class="input-wrap">
                        <i class="bi bi-person"></i>
                        <input type="text"
                               name="username"
                               class="login-input"
                               placeholder="Masukkan username"
                               autocomplete="username"
                               required>
                    </div>
                </div>

                <div class="login-field">
                    <label>Password</label>
                    <div class="input-wrap">
                        <i class="bi bi-key"></i>
                        <input type="password"
                               name="password"
                               id="passwordInput"
                               class="login-input"
                               placeholder="Masukkan password"
                               autocomplete="current-password"
                               required>

                        <button type="button" class="toggle-password" id="togglePassword">
                            <i class="bi bi-eye"></i>
                        </button>
                    </div>
                </div>

                <div class="login-options">
                    <label>
                        <input type="checkbox" name="remember" value="1">
                        Ingat saya
                    </label>

                    <a href="<?= base_url() ?>">
                        Kembali ke Website
                    </a>
                </div>

                <button class="btn-login" id="loginButton">
                    Masuk
                </button>

            </form>

            <div class="back-home" style="margin-top: 25px; padding-top: 15px; border-top: 1px solid #e2e8f0;">
                <a href="<?= base_url('ppdb/login') ?>">
                    <i class="bi bi-box-arrow-in-right"></i> Pergi ke Portal Login PPDB
                </a>
            </div>

        </div>
    </section>

</div>

<script>

document.getElementById('togglePassword').addEventListener('click', function(){
    const input = document.getElementById('passwordInput');
    const icon = this.querySelector('i');

    if(input.type === 'password'){
        input.type = 'text';
        icon.classList.remove('bi-eye');
        icon.classList.add('bi-eye-slash');
    }else{
        input.type = 'password';
        icon.classList.remove('bi-eye-slash');
        icon.classList.add('bi-eye');
    }
});
</script>

<script>
if ('serviceWorker' in navigator) {
  window.addEventListener('load', function() {
    navigator.serviceWorker.register('<?= base_url('sw.js') ?>').then(function(registration) {
      console.log('ServiceWorker registration successful');
    }, function(err) {
      console.log('ServiceWorker registration failed: ', err);
    });
  });
}
</script>

</body>
</html>