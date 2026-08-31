<?php
$nama_madrasah = $nama_madrasah ?? 'MAN 3 Banjar';
$page_title = $page_title ?? 'Website Madrasah';

$logo_file = FCPATH.'assets/img/logo-madrasah.png';
$logo_url  = base_url('assets/img/logo-madrasah.png');

if(!function_exists('web_clean')){
    function web_clean($text){
        return htmlspecialchars((string)$text, ENT_QUOTES, 'UTF-8');
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title><?= htmlspecialchars($page_title, ENT_QUOTES, 'UTF-8') ?> | <?= htmlspecialchars($nama_madrasah, ENT_QUOTES, 'UTF-8') ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Arsip informasi, berita, pengumuman, kegiatan, dan publikasi resmi <?= htmlspecialchars($nama_madrasah, ENT_QUOTES, 'UTF-8') ?>.">

    <link rel="icon" type="image/png" href="<?= base_url('assets/img/favicon.png') ?>">
    <link rel="shortcut icon" type="image/png" href="<?= base_url('assets/img/favicon.png') ?>">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link rel="stylesheet" href="<?= base_url('assets/css/website-home.css?v=21') ?>">
    <link rel="stylesheet" href="<?= base_url('assets/css/website-news-polish.css?v=2') ?>">
</head>

<body>

<div class="web-topbar">
    <div class="container web-topbar-inner">
        <span>Portal Resmi <?= htmlspecialchars($nama_madrasah, ENT_QUOTES, 'UTF-8') ?></span>
        <span>Berita • PPDB • Akademik • Tata Usaha</span>
    </div>
</div>

<nav class="navbar navbar-expand-lg web-navbar sticky-top">
    <div class="container">
        <a href="<?= base_url() ?>" class="navbar-brand web-brand">
            <div class="web-brand-logo">
                <?php if(file_exists($logo_file)): ?>
                    <img src="<?= $logo_url ?>" alt="<?= htmlspecialchars($nama_madrasah, ENT_QUOTES, 'UTF-8') ?>">
                <?php else: ?>
                    M3
                <?php endif; ?>
            </div>

            <div class="web-brand-text">
                <strong><?= htmlspecialchars($nama_madrasah, ENT_QUOTES, 'UTF-8') ?></strong>
                <small>Portal Digital Madrasah</small>
            </div>
        </a>

        <button class="navbar-toggler web-toggler"
                type="button"
                data-bs-toggle="collapse"
                data-bs-target="#webNavbar">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="webNavbar">
            <ul class="navbar-nav ms-auto me-lg-3 mt-3 mt-lg-0 web-menu">
                <li class="nav-item">
                    <a href="<?= base_url() ?>" class="nav-link">Beranda</a>
                </li>

                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        Profil Madrasah
                    </a>
                    <ul class="dropdown-menu shadow-sm">
                        <li><a class="dropdown-item" href="<?= base_url('website/sejarah') ?>">Sejarah</a></li>
                        <li><a class="dropdown-item" href="<?= base_url('website/visi_misi') ?>">Visi &amp; Misi</a></li>
                        <li><a class="dropdown-item" href="<?= base_url('website/fasilitas') ?>">Fasilitas</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item" href="<?= base_url('website/struktur/tenaga-pendidik') ?>">Tenaga Pendidik</a></li>
                        <li><a class="dropdown-item" href="<?= base_url('website/struktur/kependidikan') ?>">Kependidikan</a></li>
                        <li><a class="dropdown-item" href="<?= base_url('website/struktur/koordinator') ?>">Koordinator</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item" href="<?= base_url('website/ptk') ?>">Direktori PTK Lengkap</a></li>
                    </ul>
                </li>

                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        Informasi
                    </a>
                    <ul class="dropdown-menu shadow-sm">
                        <li><a class="dropdown-item" href="<?= base_url('website/berita') ?>">Berita Terbaru</a></li>
                        <li><a class="dropdown-item" href="<?= base_url('website/data_siswa') ?>">Data Siswa (Keadaan)</a></li>
                        <li><a class="dropdown-item" href="<?= base_url('website/pamflet') ?>">Pengumuman / Pamflet</a></li>
                        <li><a class="dropdown-item" href="<?= base_url('website/galeri') ?>">Galeri Kegiatan</a></li>
                        <li><a class="dropdown-item" href="<?= base_url('website/download') ?>">Download File</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item" href="<?= base_url() ?>#media">Media & Video</a></li>
                    </ul>
                </li>

                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        PPDB Online
                    </a>
                    <ul class="dropdown-menu shadow-sm">
                        <li><a class="dropdown-item" href="<?= base_url('ppdb') ?>">Informasi Pendaftaran</a></li>
                        <li><a class="dropdown-item" href="<?= base_url('ppdb') ?>">Cek Status Kelulusan</a></li>
                    </ul>
                </li>

                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        Layanan Akademik
                    </a>
                    <ul class="dropdown-menu shadow-sm">
                        <li><a class="dropdown-item fw-bold text-success" href="<?= base_url('website/monitoring_kbm') ?>"><i class="bi bi-broadcast text-danger me-1"></i> Live Monitoring KBM</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item" href="https://rdm.man3banjar.com" target="_blank" rel="noopener">Rapor Digital (RDM)</a></li>
                    </ul>
                </li>

                <li class="nav-item">
                    <a href="<?= base_url('website/monitoring_kbm') ?>" class="nav-link fw-bold text-success d-none d-xl-block">
                        <i class="bi bi-broadcast text-danger me-1"></i> Live KBM
                    </a>
                </li>

                <li class="nav-item">
                    <a href="<?= base_url('website/alumni') ?>" class="nav-link">Alumni</a>
                </li>

                <li class="nav-item">
                    <a href="<?= base_url() ?>#kontak" class="nav-link">Kontak</a>
                </li>
            </ul>


        </div>
    </div>
</nav>