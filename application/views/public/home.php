<?php
$nama_madrasah = 'MAN 3 Banjar';

$logo_file = FCPATH . 'assets/img/logo-madrasah.png';
$logo_url  = base_url('assets/img/logo-madrasah.png');

$berita_utama   = !empty($berita) ? $berita[0] : null;
$berita_lainnya = !empty($berita) ? array_slice($berita, 1, 4) : [];

if(!function_exists('web_clean')){
    function web_clean($text){
        return htmlspecialchars((string)$text, ENT_QUOTES, 'UTF-8');
    }
}

if(!function_exists('web_limit')){
    function web_limit($text, $limit = 130){
        $text = trim(strip_tags((string)$text));
        if(function_exists('mb_strimwidth')){
            return mb_strimwidth($text, 0, $limit, '...');
        }
        return strlen($text) > $limit ? substr($text, 0, $limit).'...' : $text;
    }
}

if(!function_exists('web_youtube_embed')){
    function web_youtube_embed($url){
        if(preg_match('/youtu\.be\/([^\?]+)/', $url, $match)){
            return 'https://www.youtube.com/embed/'.$match[1];
        }
        if(preg_match('/v=([^&]+)/', $url, $match)){
            return 'https://www.youtube.com/embed/'.$match[1];
        }
        if(strpos($url, 'embed') !== false){
            return $url;
        }
        return '';
    }
}

$video_embed = !empty($video_profil->youtube_url)
    ? web_youtube_embed($video_profil->youtube_url)
    : '';

$wa_number = '';
if(!empty($profil_website->whatsapp)){
    $wa_number = preg_replace('/[^0-9]/', '', $profil_website->whatsapp);
    if(substr($wa_number, 0, 1) == '0') $wa_number = '62'.substr($wa_number, 1);
} elseif(!empty($profil_website->telepon)){
    $wa_number = preg_replace('/[^0-9]/', '', $profil_website->telepon);
    if(substr($wa_number, 0, 1) == '0') $wa_number = '62'.substr($wa_number, 1);
}

// ═══ PENGATURAN DINAMIS ADMIN ═══
$hero_badge = !empty($profil_website->hero_badge) 
    ? $profil_website->hero_badge 
    : 'Portal Resmi & Layanan Digital Terpadu';

$hero_judul = !empty($profil_website->hero_judul) 
    ? $profil_website->hero_judul 
    : 'Mewujudkan Generasi Islami, Unggul, Berkarakter & Melek Digital';

$hero_desc = !empty($profil_website->hero_deskripsi) 
    ? $profil_website->hero_deskripsi 
    : 'Selamat datang di Portal Resmi Madrasah Aliyah Negeri 3 Banjar. Pusat informasi akademik terpadu, sarana transparansi pembelajaran, dan wahana prestasi peserta didik.';

$hero_btn1_teks = !empty($profil_website->hero_tombol_utama_teks) 
    ? $profil_website->hero_tombol_utama_teks 
    : 'Pendaftaran Siswa Baru (PPDB)';

$hero_btn1_raw  = !empty($profil_website->hero_tombol_utama_url) ? trim($profil_website->hero_tombol_utama_url) : 'pmb';
$hero_btn1_url  = (strpos($hero_btn1_raw, 'http://') === 0 || strpos($hero_btn1_raw, 'https://') === 0) 
    ? $hero_btn1_raw 
    : base_url($hero_btn1_raw);

$hero_btn2_teks = !empty($profil_website->hero_tombol_kedua_teks) 
    ? $profil_website->hero_tombol_kedua_teks 
    : 'Jelajahi Profil Madrasah';

$hero_btn2_raw  = !empty($profil_website->hero_tombol_kedua_url) ? trim($profil_website->hero_tombol_kedua_url) : 'website/profil';
$hero_btn2_url  = (strpos($hero_btn2_raw, 'http://') === 0 || strpos($hero_btn2_raw, 'https://') === 0) 
    ? $hero_btn2_raw 
    : base_url($hero_btn2_raw);

// Sambutan Kepala Madrasah Dinamis
$sambutan_judul = !empty($profil_website->sambutan_judul)
    ? $profil_website->sambutan_judul
    : 'Sambutan Kepala Madrasah';

$sambutan_nama = !empty($profil_website->sambutan_nama)
    ? $profil_website->sambutan_nama
    : (!empty($kepala_madrasah->nama_lengkap) ? $kepala_madrasah->nama_lengkap : 'Drs. H. Ahmad Sauqi, M.Pd');

$sambutan_jabatan = !empty($profil_website->sambutan_jabatan)
    ? $profil_website->sambutan_jabatan
    : (!empty($kepala_madrasah->jabatan) ? $kepala_madrasah->jabatan : 'Kepala Madrasah Aliyah Negeri 3 Banjar');

$sambutan_isi = !empty($profil_website->sambutan_isi)
    ? $profil_website->sambutan_isi
    : "Assalamu’alaikum Warahmatullahi Wabarakatuh.\n\nPuji syukur kita panjatkan ke hadirat Allah SWT atas limpahan rahmat dan karunia-Nya. Di era transformasi digital saat ini, MAN 3 Banjar terus berinovasi mewujudkan madrasah yang mandiri, berprestasi, dan berakhlakul karimah. Portal ini hadir sebagai jembatan informasi dan pelayanan terpadu bagi siswa, orang tua, dan masyarakat luas.";

$sambutan_foto_url = '';
if(!empty($profil_website->sambutan_foto) && file_exists(FCPATH . 'uploads/website/' . $profil_website->sambutan_foto)){
    $sambutan_foto_url = base_url('uploads/website/' . $profil_website->sambutan_foto);
} elseif(!empty($kepala_madrasah->foto) && file_exists(FCPATH . 'uploads/ptk/foto/' . $kepala_madrasah->foto)){
    $sambutan_foto_url = base_url('uploads/ptk/foto/' . $kepala_madrasah->foto);
}

// URL RDM (Rapor Digital Siswa) Dinamis
$rdm_raw = !empty($profil_website->rdm_url) ? trim($profil_website->rdm_url) : 'https://rdm.man3banjar.com';
$rdm_url = (strpos($rdm_raw, 'http://') === 0 || strpos($rdm_raw, 'https://') === 0) 
    ? $rdm_raw 
    : 'https://' . $rdm_raw;
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title><?= $nama_madrasah ?> — Portal Resmi &amp; Layanan Digital Madrasah</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Portal resmi <?= $nama_madrasah ?>. Informasi akademik terpadu, PPDB online, monitoring KBM realtime, profil guru dan layanan digital.">

    <link rel="icon" type="image/png" sizes="32x32" href="<?= base_url('assets/brand/logo-man3.png') ?>">
    <link rel="icon" type="image/png" sizes="16x16" href="<?= base_url('assets/brand/logo-man3.png') ?>">
    <link rel="shortcut icon" href="<?= base_url('favicon.ico') ?>" type="image/x-icon">
    <link rel="apple-touch-icon" href="<?= base_url('assets/brand/logo-man3.png') ?>">

    <!-- Bootstrap 5, Icons, & Modern Typography -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?= base_url('assets/css/website-home.css?v=24') ?>">

    <style>
    /* ═══ CLEAN INSTITUTIONAL DESIGN TOKENS ═══ */
    :root {
        --c-brand-green: #059669;
        --c-brand-dark: #064e3b;
        --c-brand-surface: #ecfdf5;
        --c-text-primary: #0f172a;
        --c-text-secondary: #475569;
        --c-text-muted: #64748b;
        --c-border-subtle: #e2e8f0;
        --c-bg-light: #f8fafc;
        --c-bg-white: #ffffff;
        --shadow-clean: 0 4px 20px rgba(15, 23, 42, 0.04);
        --shadow-clean-hover: 0 10px 30px rgba(15, 23, 42, 0.08);
        --radius-card: 18px;
    }

    body {
        font-family: 'Plus Jakarta Sans', system-ui, -apple-system, sans-serif;
        color: var(--c-text-primary);
        background-color: var(--c-bg-white);
        line-height: 1.6;
        -webkit-font-smoothing: antialiased;
    }

    /* ═══ TOPBAR CLEAN ═══ */
    .clean-topbar {
        background: #022c22;
        color: #d1fae5;
        font-size: 12.5px;
        padding: 8px 0;
        border-bottom: 1px solid rgba(255,255,255,0.08);
    }
    .clean-topbar a { color: #d1fae5; text-decoration: none; }
    .clean-topbar a:hover { color: #ffffff; }

    /* ═══ NAVBAR CLEAN ═══ */
    .clean-navbar {
        background: #ffffff;
        border-bottom: 1px solid var(--c-border-subtle);
        box-shadow: 0 2px 10px rgba(0,0,0,0.03);
        transition: all 0.25s ease;
    }
    .clean-navbar.scrolled {
        box-shadow: 0 6px 20px rgba(15,23,42,0.06);
    }

    /* ═══ CLEAN HERO SECTION (TERANG, SEGAR, MODERN) ═══ */
    .clean-hero {
        background: 
            radial-gradient(circle at 95% 5%, rgba(16, 185, 129, 0.08) 0%, transparent 40%),
            radial-gradient(circle at 5% 95%, rgba(245, 158, 11, 0.06) 0%, transparent 40%),
            linear-gradient(180deg, #f0fdf4 0%, #ffffff 100%);
        border-bottom: 1px solid var(--c-border-subtle);
        padding: 60px 0 75px;
        position: relative;
    }

    .clean-hero-badge {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        background: #ffffff;
        border: 1px solid #bbf7d0;
        padding: 6px 16px;
        border-radius: 999px;
        font-size: 12.5px;
        font-weight: 700;
        color: var(--c-brand-green);
        box-shadow: 0 2px 8px rgba(5, 150, 105, 0.08);
        margin-bottom: 20px;
    }

    .clean-hero-title {
        font-size: clamp(2.1rem, 3.8vw, 3.2rem);
        font-weight: 900;
        line-height: 1.2;
        letter-spacing: -0.6px;
        color: var(--c-text-primary);
        margin-bottom: 18px;
    }

    .clean-hero-title span.text-highlight {
        color: var(--c-brand-green);
        position: relative;
    }

    .clean-hero-desc {
        font-size: clamp(15px, 1.2vw, 16.5px);
        line-height: 1.75;
        color: var(--c-text-secondary);
        margin-bottom: 28px;
        max-width: 700px;
        margin-inline: auto;
    }

    .btn-clean-primary {
        background: var(--c-brand-green);
        color: #ffffff !important;
        font-weight: 800;
        font-size: 14.5px;
        padding: 12px 26px;
        border-radius: 999px;
        border: none;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        box-shadow: 0 6px 20px rgba(5, 150, 105, 0.25);
        transition: all 0.2s ease;
        text-decoration: none;
    }

    .btn-clean-primary:hover {
        background: var(--c-brand-dark);
        transform: translateY(-2px);
        box-shadow: 0 8px 24px rgba(5, 150, 105, 0.35);
    }

    .btn-clean-outline {
        background: #ffffff;
        color: var(--c-text-primary) !important;
        font-weight: 700;
        font-size: 14.5px;
        padding: 12px 22px;
        border-radius: 999px;
        border: 1.5px solid var(--c-border-subtle);
        display: inline-flex;
        align-items: center;
        gap: 8px;
        transition: all 0.2s ease;
        text-decoration: none;
    }

    .btn-clean-outline:hover {
        background: #f8fafc;
        border-color: #cbd5e1;
        transform: translateY(-2px);
    }

    /* Live Pulse */
    @keyframes liveDotPulse {
        0% { transform: scale(0.95); box-shadow: 0 0 0 0 rgba(239, 68, 68, 0.7); }
        70% { transform: scale(1); box-shadow: 0 0 0 7px rgba(239, 68, 68, 0); }
        100% { transform: scale(0.95); box-shadow: 0 0 0 0 rgba(239, 68, 68, 0); }
    }
    .live-dot {
        width: 8px;
        height: 8px;
        background: #ef4444;
        border-radius: 50%;
        display: inline-block;
        animation: liveDotPulse 1.8s infinite;
    }

    /* Sambutan Card di Hero Kanan */
    .clean-headmaster-box {
        background: #ffffff;
        border: 1px solid var(--c-border-subtle);
        border-radius: 22px;
        padding: 26px;
        box-shadow: 0 14px 40px rgba(15, 23, 42, 0.06);
        position: relative;
    }

    .clean-headmaster-box::before {
        content: "";
        position: absolute;
        top: 0;
        left: 28px;
        width: 60px;
        height: 4px;
        background: var(--c-brand-green);
        border-radius: 0 0 4px 4px;
    }

    .hm-avatar-ring {
        width: 68px;
        height: 68px;
        border-radius: 50%;
        border: 2px solid #bbf7d0;
        padding: 2px;
        object-fit: cover;
        flex-shrink: 0;
        background: #ffffff;
    }

    .hm-quote-bubble {
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        border-radius: 14px;
        padding: 14px 16px;
        font-size: 13.5px;
        color: #334155;
        line-height: 1.65;
        margin: 16px 0;
        font-style: italic;
    }

    /* ═══ QUICK ACCESS HUB ═══ */
    .clean-quick-hub {
        margin-top: -34px;
        position: relative;
        z-index: 20;
        margin-bottom: 45px;
    }

    .clean-hub-container {
        background: #ffffff;
        border: 1px solid var(--c-border-subtle);
        border-radius: 20px;
        box-shadow: 0 12px 35px rgba(15, 23, 42, 0.06);
        padding: 16px;
    }

    .clean-quick-grid {
        display: grid;
        grid-template-columns: repeat(6, 1fr);
        gap: 12px;
    }

    @media (max-width: 991px) {
        .clean-quick-grid { grid-template-columns: repeat(3, 1fr); }
        .clean-quick-hub { margin-top: -20px; }
    }
    @media (max-width: 576px) {
        .clean-quick-grid { grid-template-columns: repeat(2, 1fr); }
        .clean-quick-hub { margin-top: -14px; }
    }

    .clean-hub-item {
        display: flex;
        flex-direction: column;
        align-items: center;
        text-align: center;
        padding: 14px 10px;
        border-radius: 14px;
        background: #f8fafc;
        border: 1px solid #f1f5f9;
        text-decoration: none;
        transition: all 0.2s ease;
    }

    .clean-hub-item:hover {
        background: #ffffff;
        transform: translateY(-3px);
        box-shadow: 0 8px 20px rgba(0,0,0,0.06);
        border-color: #cbd5e1;
    }

    .clean-hub-icon {
        width: 44px;
        height: 44px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 20px;
        margin-bottom: 8px;
        transition: transform 0.2s;
    }

    .clean-hub-item:hover .clean-hub-icon {
        transform: scale(1.1);
    }

    .clean-hub-title {
        font-size: 13px;
        font-weight: 800;
        color: var(--c-text-primary);
        line-height: 1.25;
    }

    .clean-hub-sub {
        font-size: 11px;
        color: var(--c-text-muted);
        margin-top: 2px;
    }

    /* ═══ SECTION HEADER STANDARD CLEAN ═══ */
    .clean-sec-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-end;
        flex-wrap: wrap;
        gap: 16px;
        margin-bottom: 32px;
    }

    .clean-pill-badge {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        background: #ecfdf5;
        color: #059669;
        border: 1px solid #a7f3d0;
        font-size: 12px;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        padding: 4px 12px;
        border-radius: 999px;
        margin-bottom: 8px;
    }

    .clean-sec-title {
        font-size: clamp(22px, 2.4vw, 28px);
        font-weight: 900;
        color: var(--c-text-primary);
        margin: 0 0 6px;
        line-height: 1.25;
    }

    .clean-sec-sub {
        color: var(--c-text-muted);
        font-size: 14.5px;
        margin: 0;
    }

    /* ═══ NEWS CARD CLEAN ═══ */
    .clean-news-main {
        background: #ffffff;
        border: 1px solid var(--c-border-subtle);
        border-radius: 20px;
        overflow: hidden;
        box-shadow: var(--shadow-clean);
        text-decoration: none;
        display: flex;
        flex-direction: column;
        height: 100%;
        transition: transform 0.25s, box-shadow 0.25s;
    }

    .clean-news-main:hover {
        transform: translateY(-3px);
        box-shadow: var(--shadow-clean-hover);
    }

    .clean-news-main img {
        width: 100%;
        height: 250px;
        object-fit: cover;
    }

    .clean-news-side {
        background: #ffffff;
        border: 1px solid var(--c-border-subtle);
        border-radius: 16px;
        padding: 14px;
        display: flex;
        gap: 14px;
        align-items: center;
        text-decoration: none;
        transition: all 0.2s ease;
        margin-bottom: 12px;
    }

    .clean-news-side:hover {
        transform: translateX(4px);
        border-color: #059669;
        box-shadow: 0 6px 18px rgba(0,0,0,0.04);
    }

    .clean-news-side img {
        width: 76px;
        height: 76px;
        border-radius: 12px;
        object-fit: cover;
        flex-shrink: 0;
    }

    /* ═══ PTK CARD CLEAN ═══ */
    .clean-ptk-card {
        background: #ffffff;
        border: 1px solid var(--c-border-subtle);
        border-radius: 16px;
        padding: 20px 14px;
        text-align: center;
        transition: all 0.2s ease;
        height: 100%;
        display: flex;
        flex-direction: column;
        align-items: center;
    }

    .clean-ptk-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 10px 25px rgba(0,0,0,0.06);
        border-color: #cbd5e1;
    }

    .clean-ptk-photo {
        width: 80px;
        height: 98px;
        border-radius: 12px;
        object-fit: cover;
        margin-bottom: 12px;
        border: 1px solid #e2e8f0;
    }

    .clean-ptk-avatar {
        width: 80px;
        height: 98px;
        border-radius: 12px;
        background: #f1f5f9;
        color: #059669;
        font-size: 28px;
        font-weight: 800;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 12px;
    }

    /* ═══ SERVICE PILL CARD ═══ */
    .clean-service-box {
        background: #ffffff;
        border: 1px solid var(--c-border-subtle);
        border-radius: 16px;
        padding: 22px;
        transition: all 0.2s ease;
        height: 100%;
    }

    .clean-service-box:hover {
        transform: translateY(-3px);
        border-color: #059669;
        box-shadow: var(--shadow-clean-hover);
    }
    </style>
</head>

<body>

<!-- ═══ 1. TOPBAR CLEAN ═══ -->
<div class="clean-topbar">
    <div class="container d-flex justify-content-between align-items-center flex-wrap gap-2">
        <div class="d-flex align-items-center gap-3">
            <span><i class="bi bi-building"></i> <strong><?= $nama_madrasah ?></strong></span>
            <?php if(!empty($profil_website->nsm)): ?>
                <span class="d-none d-md-inline text-white-50">| NSM: <span class="text-white"><?= htmlspecialchars($profil_website->nsm, ENT_QUOTES, 'UTF-8') ?></span></span>
            <?php endif; ?>
            <?php if(!empty($profil_website->npsn)): ?>
                <span class="d-none d-md-inline text-white-50">| NPSN: <span class="text-white"><?= htmlspecialchars($profil_website->npsn, ENT_QUOTES, 'UTF-8') ?></span></span>
            <?php endif; ?>
        </div>
        <div class="d-flex align-items-center gap-3">
            <?php if(!empty($profil_website->jam_layanan)): ?>
                <span class="text-white-50"><i class="bi bi-clock me-1"></i><?= htmlspecialchars($profil_website->jam_layanan, ENT_QUOTES, 'UTF-8') ?></span>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- ═══ 2. NAVBAR CLEAN DENGAN SELURUH SUBMENU ASLI ═══ -->
<nav class="navbar navbar-expand-lg clean-navbar sticky-top" id="mainNav">
    <div class="container">
        <a href="<?= base_url() ?>" class="navbar-brand d-flex align-items-center gap-2 text-decoration-none">
            <div style="width: 40px; height: 40px; flex-shrink: 0; display:flex; align-items:center; justify-content:center;">
                <?php if(file_exists($logo_file)): ?>
                    <img src="<?= $logo_url ?>" alt="<?= $nama_madrasah ?>" style="width:100%; height:100%; object-fit:contain;">
                <?php else: ?>
                    <span class="fw-black text-success fs-4">M3</span>
                <?php endif; ?>
            </div>
            <div>
                <strong style="font-size: 16px; color: #064e3b; display:block; line-height:1.2;"><?= $nama_madrasah ?></strong>
                <small style="font-size: 11px; color: #64748b; font-weight:600;">Portal Digital Madrasah</small>
            </div>
        </a>

        <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#webNavbar" aria-controls="webNavbar" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="webNavbar">
            <ul class="navbar-nav ms-auto me-lg-3 mt-3 mt-lg-0 web-menu">
                <li class="nav-item">
                    <a href="<?= base_url() ?>" class="nav-link active">Beranda</a>
                </li>

                <!-- Dropdown Profil & Struktur Lengkap -->
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        Profil Madrasah
                    </a>
                    <ul class="dropdown-menu shadow-sm border-0 py-2" style="border-radius:14px;">
                        <li><a class="dropdown-item py-2" href="<?= base_url('website/sejarah') ?>">Sejarah</a></li>
                        <li><a class="dropdown-item py-2" href="<?= base_url('website/visi_misi') ?>">Visi &amp; Misi</a></li>
                        <li><a class="dropdown-item py-2" href="<?= base_url('website/fasilitas') ?>">Fasilitas Madrasah</a></li>
                        <li><hr class="dropdown-divider my-1"></li>
                        <li><h6 class="dropdown-header text-uppercase fw-bold text-success" style="font-size: 10px; letter-spacing: 0.5px;"><i class="bi bi-diagram-3 me-1"></i> Struktur Organisasi</h6></li>
                        <li>
                            <a class="dropdown-item py-1.5" href="<?= base_url('website/struktur/tenaga-pendidik') ?>">
                                <div class="fw-semibold" style="font-size: 13px;">Tenaga Pendidik</div>
                                <small class="text-muted d-block" style="font-size: 10.5px;">Bagan Struktur Dewan Guru</small>
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item py-1.5" href="<?= base_url('website/struktur/kependidikan') ?>">
                                <div class="fw-semibold" style="font-size: 13px;">Kependidikan</div>
                                <small class="text-muted d-block" style="font-size: 10.5px;">Staf Tata Usaha &amp; Layanan</small>
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item py-1.5" href="<?= base_url('website/struktur/koordinator') ?>">
                                <div class="fw-semibold" style="font-size: 13px;">Koordinator &amp; Ekskul</div>
                                <small class="text-muted d-block" style="font-size: 10.5px;">Pembina Ekstrakurikuler</small>
                            </a>
                        </li>
                        <li><hr class="dropdown-divider my-1"></li>
                        <li>
                            <a class="dropdown-item py-1.5 text-success fw-bold" href="<?= base_url('website/ptk') ?>">
                                <i class="bi bi-people-fill me-1"></i> Direktori PTK Lengkap
                            </a>
                        </li>
                    </ul>
                </li>

                <!-- Dropdown Informasi Lengkap -->
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        Informasi
                    </a>
                    <ul class="dropdown-menu shadow-sm border-0 py-2" style="border-radius:14px;">
                        <li><a class="dropdown-item py-2" href="<?= base_url('website/berita') ?>">Berita Terbaru</a></li>
                        <li><a class="dropdown-item py-2" href="<?= base_url('website/data_siswa') ?>">Data Siswa (Keadaan)</a></li>
                        <li><a class="dropdown-item py-2" href="<?= base_url('website/pamflet') ?>">Pengumuman / Pamflet</a></li>
                        <li><a class="dropdown-item py-2" href="<?= base_url('website/galeri') ?>">Galeri Kegiatan</a></li>
                        <li><a class="dropdown-item py-2" href="<?= base_url('website/download') ?>"><i class="bi bi-folder-fill text-success me-2"></i> Download File (Publik)</a></li>
                        <li><a class="dropdown-item py-2 fw-semibold text-success" href="<?= base_url('website/zona_integritas') ?>"><i class="bi bi-shield-lock-fill text-warning me-2"></i> Eviden Zona Integritas (WBK)</a></li>
                        <li><hr class="dropdown-divider my-1"></li>
                        <li><a class="dropdown-item py-2" href="#media">Video Profil</a></li>
                    </ul>
                </li>

                <!-- PMB Online -->
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        PMB Online
                    </a>
                    <ul class="dropdown-menu shadow-sm border-0 py-2" style="border-radius:14px;">
                        <li><a class="dropdown-item py-2 fw-bold text-success" href="<?= base_url('pmb') ?>"><i class="bi bi-pencil-square me-1"></i> Informasi Pendaftaran</a></li>
                        <li><a class="dropdown-item py-2" href="<?= base_url('pmb/login') ?>">Login Calon Siswa</a></li>
                    </ul>
                </li>

                <!-- Layanan Akademik -->
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        Layanan Akademik
                    </a>
                    <ul class="dropdown-menu shadow-sm border-0 py-2" style="border-radius:14px;">
                        <li><a class="dropdown-item py-2 fw-bold text-success" href="<?= base_url('website/monitoring_kbm') ?>"><i class="bi bi-broadcast text-danger me-1"></i> Live Monitoring KBM</a></li>
                        <li><a class="dropdown-item py-2 fw-bold text-dark" href="<?= base_url('verifikasi_foto_ijazah') ?>"><i class="bi bi-mortarboard-fill text-success me-1"></i> Verifikasi Foto Ijazah XII</a></li>
                        <li><hr class="dropdown-divider my-1"></li>
                        <li><a class="dropdown-item py-2" href="<?= htmlspecialchars($rdm_url, ENT_QUOTES, 'UTF-8') ?>" target="_blank" rel="noopener">Rapor Digital Siswa (RDM)</a></li>
                    </ul>
                </li>

                <li class="nav-item">
                    <a href="<?= base_url('website/monitoring_kbm') ?>" class="nav-link fw-bold text-success d-none d-xl-block">
                        <span class="live-dot me-1"></span> Live KBM
                    </a>
                </li>

                <li class="nav-item">
                    <a href="<?= base_url('website/alumni') ?>" class="nav-link">Alumni</a>
                </li>

                <li class="nav-item">
                    <a href="<?= base_url('website/kontak') ?>" class="nav-link">Kontak</a>
                </li>
            </ul>

            <div class="d-flex align-items-center">
                <a href="<?= $hero_btn1_url ?>" class="btn-clean-primary" style="padding:8px 20px; font-size:13.5px;">
                    <i class="bi bi-pencil-square"></i> PMB Online
                </a>
            </div>
        </div>
    </div>
</nav>

<!-- ═══ 3. CLEAN HERO SECTION (TERANG, LAPANG, & SIMETRIS) ═══ -->
<header class="clean-hero text-center">
    <div class="container position-relative">
        <div class="mx-auto" style="max-width: 880px;">
            
            <div class="clean-hero-badge mx-auto">
                <i class="bi bi-patch-check-fill text-warning"></i>
                <span><?= htmlspecialchars($hero_badge, ENT_QUOTES, 'UTF-8') ?></span>
            </div>

            <h1 class="clean-hero-title">
                <?= htmlspecialchars($hero_judul, ENT_QUOTES, 'UTF-8') ?>
            </h1>

            <p class="clean-hero-desc mx-auto">
                <?= nl2br(htmlspecialchars($hero_desc, ENT_QUOTES, 'UTF-8')) ?>
            </p>

            <!-- Tombol Aksi Dinamis (2 Tombol Pilihan Admin di Tengah) -->
            <div class="d-flex justify-content-center flex-wrap gap-3 mb-4">
                <?php if(!empty($hero_btn1_teks)): ?>
                    <a href="<?= $hero_btn1_url ?>" class="btn-clean-primary">
                        <span><?= htmlspecialchars($hero_btn1_teks, ENT_QUOTES, 'UTF-8') ?></span>
                        <i class="bi bi-arrow-right"></i>
                    </a>
                <?php endif; ?>

                <?php if(!empty($hero_btn2_teks)): ?>
                    <a href="<?= $hero_btn2_url ?>" class="btn-clean-outline">
                        <i class="bi bi-buildings"></i>
                        <span><?= htmlspecialchars($hero_btn2_teks, ENT_QUOTES, 'UTF-8') ?></span>
                    </a>
                <?php endif; ?>
            </div>

            <!-- Mini KPI Badges di Tengah -->
            <div class="d-flex justify-content-center flex-wrap align-items-center gap-4 pt-3 border-top text-muted small" style="border-color:#e2e8f0 !important;">
                <div class="d-flex align-items-center gap-2">
                    <i class="bi bi-award-fill text-warning fs-5"></i>
                    <span class="text-dark">Akreditasi <strong>A (Unggul)</strong></span>
                </div>
                <div class="d-flex align-items-center gap-2">
                    <i class="bi bi-diagram-3-fill text-primary fs-5"></i>
                    <span class="text-dark"><strong>MIPA • IPS • Keagamaan</strong></span>
                </div>
                <div class="d-flex align-items-center gap-2">
                    <i class="bi bi-cpu-fill text-success fs-5"></i>
                    <span class="text-dark"><strong>Layanan Digital Terpadu</strong></span>
                </div>
            </div>

            <!-- Showcase Slider (Hanya jika admin mengunggah banner slider aktif) -->
            <?php if(!empty($banner_slider)): ?>
                <div class="mt-4 mx-auto shadow-sm" style="max-width:820px; border-radius:24px; overflow:hidden; border:1px solid #e2e8f0; background:#064e3b;">
                    <div id="heroActivityCarousel" class="carousel slide carousel-fade" data-bs-ride="carousel" data-bs-interval="4500">
                        <?php if(count($banner_slider) > 1): ?>
                            <div class="carousel-indicators mb-2">
                                <?php foreach($banner_slider as $idx => $b): ?>
                                    <button type="button" 
                                            data-bs-target="#heroActivityCarousel" 
                                            data-bs-slide-to="<?= $idx ?>" 
                                            class="<?= $idx === 0 ? 'active' : '' ?>" 
                                            aria-current="<?= $idx === 0 ? 'true' : 'false' ?>" 
                                            style="width:20px; height:4px; border-radius:2px; border:none;"></button>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>

                        <div class="carousel-inner">
                            <?php foreach($banner_slider as $idx => $b): ?>
                                <div class="carousel-item <?= $idx === 0 ? 'active' : '' ?>">
                                    <div class="position-relative" style="height:360px;">
                                        <img src="<?= base_url('assets/banner/'.$b->gambar) ?>" 
                                             alt="<?= htmlspecialchars($b->judul ?? 'Kegiatan Madrasah', ENT_QUOTES, 'UTF-8') ?>"
                                             style="width:100%; height:100%; object-fit:cover;">
                                        <div class="position-absolute bottom-0 start-0 w-100 p-3 p-md-4 text-white text-start" 
                                             style="background: linear-gradient(180deg, transparent 0%, rgba(2,44,34,0.88) 100%);">
                                            <?php if(!empty($b->subjudul)): ?>
                                                <span class="badge bg-warning text-dark fw-bold px-2.5 py-0.5 rounded-pill mb-1" style="font-size:10.5px;">
                                                    <?= htmlspecialchars($b->subjudul, ENT_QUOTES, 'UTF-8') ?>
                                                </span>
                                            <?php endif; ?>
                                            <h5 class="fw-bold mb-0 text-white" style="font-size:16px; line-height:1.35; text-shadow:0 2px 4px rgba(0,0,0,0.5);">
                                                <?= htmlspecialchars($b->judul ?? 'Aktivitas & Prestasi Madrasah', ENT_QUOTES, 'UTF-8') ?>
                                            </h5>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>

                        <?php if(count($banner_slider) > 1): ?>
                            <button class="carousel-control-prev" type="button" data-bs-target="#heroActivityCarousel" data-bs-slide="prev" style="width:40px; opacity:0.85;">
                                <span class="d-flex align-items-center justify-content-center bg-dark bg-opacity-50 text-white rounded-circle" style="width:34px; height:34px;">
                                    <i class="bi bi-chevron-left" style="font-size:14px;"></i>
                                </span>
                            </button>
                            <button class="carousel-control-next" type="button" data-bs-target="#heroActivityCarousel" data-bs-slide="next" style="width:40px; opacity:0.85;">
                                <span class="d-flex align-items-center justify-content-center bg-dark bg-opacity-50 text-white rounded-circle" style="width:34px; height:34px;">
                                    <i class="bi bi-chevron-right" style="font-size:14px;"></i>
                                </span>
                            </button>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endif; ?>

        </div>
    </div>
</header>

<!-- ═══ 4. QUICK ACCESS HUB (6 MENU PINTAS MELAYANG) ═══ -->
<div class="container clean-quick-hub">
    <div class="clean-hub-container">
        <div class="clean-quick-grid">
            <a href="<?= base_url('pmb') ?>" class="clean-hub-item">
                <div class="clean-hub-icon" style="background: #ecfdf5; color: #059669;">
                    <i class="bi bi-pencil-square"></i>
                </div>
                <div class="clean-hub-title">PMB Online</div>
                <div class="clean-hub-sub">Penerimaan Siswa</div>
            </a>

            <a href="<?= base_url('website/monitoring_kbm') ?>" class="clean-hub-item">
                <div class="clean-hub-icon" style="background: #fff1f2; color: #e11d48; position: relative;">
                    <span class="live-dot" style="position: absolute; top: 8px; right: 8px;"></span>
                    <i class="bi bi-broadcast"></i>
                </div>
                <div class="clean-hub-title">Live KBM</div>
                <div class="clean-hub-sub">Pantau Belajar</div>
            </a>

            <a href="<?= base_url('verifikasi_foto_ijazah') ?>" class="clean-hub-item">
                <div class="clean-hub-icon" style="background: #fffbeb; color: #d97706;">
                    <i class="bi bi-mortarboard-fill"></i>
                </div>
                <div class="clean-hub-title">Foto Ijazah XII</div>
                <div class="clean-hub-sub">Validasi Kelulusan</div>
            </a>

            <a href="<?= htmlspecialchars($rdm_url, ENT_QUOTES, 'UTF-8') ?>" target="_blank" rel="noopener" class="clean-hub-item">
                <div class="clean-hub-icon" style="background: #f0fdfa; color: #0d9488;">
                    <i class="bi bi-journal-bookmark-fill"></i>
                </div>
                <div class="clean-hub-title">RDM Rapor</div>
                <div class="clean-hub-sub">Rapor Digital Siswa</div>
            </a>

            <a href="<?= base_url('website/ptk') ?>" class="clean-hub-item">
                <div class="clean-hub-icon" style="background: #eff6ff; color: #2563eb;">
                    <i class="bi bi-people-fill"></i>
                </div>
                <div class="clean-hub-title">Direktori PTK</div>
                <div class="clean-hub-sub">Dewan Guru &amp; Staf</div>
            </a>

            <a href="<?= base_url('website/data_siswa') ?>" class="clean-hub-item">
                <div class="clean-hub-icon" style="background: #fdf4ff; color: #9333ea;">
                    <i class="bi bi-pie-chart-fill"></i>
                </div>
                <div class="clean-hub-title">Data Siswa</div>
                <div class="clean-hub-sub">Statistik Siswa</div>
            </a>
        </div>
    </div>
</div>

<!-- ═══ 5. BANNER SLIDER BERANDA (Telah Terintegrasi di Showcase Hero Kanan) ═══ -->
<?php /* Banner slider kegiatan madrasah telah aktif di Showcase Hero Section */ ?>

<!-- ═══ 6. BANNER PENGUMUMAN BERANDA (JIKA AKTIF DI ADMIN) ═══ -->
<?php if(!empty($pengumuman_beranda) && !empty($pengumuman_beranda->aktif)): ?>
    <div class="container my-4">
        <div class="p-3 p-md-4 rounded-4 border d-flex flex-column flex-md-row align-items-center justify-content-between gap-3 shadow-xs" 
             style="background: #f0fdf4; border-color: #bbf7d0 !important;">
            <div class="d-flex align-items-center gap-3">
                <div class="p-2.5 rounded-3 bg-white text-success fw-bold fs-3 d-none d-sm-flex align-items-center justify-content-center shadow-xs" style="width: 48px; height: 48px; flex-shrink: 0; border: 1px solid #dcfce7;">
                    <i class="bi bi-megaphone-fill"></i>
                </div>
                <div>
                    <?php if(!empty($pengumuman_beranda->badge)): ?>
                        <span class="badge bg-warning text-dark fw-bold px-2.5 py-0.5 rounded-pill mb-1" style="font-size: 11px;">
                            <?= htmlspecialchars($pengumuman_beranda->badge, ENT_QUOTES, 'UTF-8') ?>
                        </span>
                    <?php endif; ?>
                    <h5 class="fw-bold mb-1 text-dark" style="font-size: 16px;">
                        <?= htmlspecialchars($pengumuman_beranda->judul, ENT_QUOTES, 'UTF-8') ?>
                    </h5>
                    <?php if(!empty($pengumuman_beranda->isi)): ?>
                        <p class="mb-0 text-muted small">
                            <?= nl2br(htmlspecialchars($pengumuman_beranda->isi, ENT_QUOTES, 'UTF-8')) ?>
                        </p>
                    <?php endif; ?>
                </div>
            </div>
            <?php if(!empty($pengumuman_beranda->tombol_teks)): ?>
                <div>
                    <a href="<?= !empty($pengumuman_beranda->tombol_url) ? base_url($pengumuman_beranda->tombol_url) : '#' ?>" class="btn btn-success fw-bold rounded-pill px-4 py-2 shadow-xs text-nowrap" style="font-size: 13px;">
                        <?= htmlspecialchars($pengumuman_beranda->tombol_teks, ENT_QUOTES, 'UTF-8') ?>
                    </a>
                </div>
            <?php endif; ?>
        </div>
    </div>
<?php endif; ?>

<!-- ═══ 7. WARTA BERITA TERKINI ═══ -->
<section class="py-5" id="berita" style="background: #ffffff;">
    <div class="container py-2">
        <div class="clean-sec-header">
            <div>
                <div class="clean-pill-badge"><i class="bi bi-newspaper"></i> Warta Madrasah</div>
                <h2 class="clean-sec-title">Informasi &amp; Kabar Terbaru</h2>
                <p class="clean-sec-sub">Kegiatan, prestasi santri, dan pengumuman resmi <?= $nama_madrasah ?>.</p>
            </div>
            <a href="<?= base_url('website/berita') ?>" class="btn btn-outline-success fw-bold rounded-pill px-4 py-2" style="font-size: 13.5px; border-width: 1.5px;">
                Semua Berita <i class="bi bi-arrow-right ms-1"></i>
            </a>
        </div>

        <?php if(!empty($berita_utama)): ?>
            <div class="row g-4">
                <!-- Berita Utama -->
                <?php
                $u_judul = web_clean($berita_utama->judul ?? '');
                $u_isi   = web_limit($berita_utama->isi ?? '', 160);
                $u_img   = $berita_utama->gambar ?? '';
                $u_file  = !empty($u_img) ? FCPATH . 'assets/news/' . $u_img : '';
                $u_key   = !empty($berita_utama->slug) ? $berita_utama->slug : $berita_utama->id;
                ?>
                <div class="col-lg-6">
                    <a href="<?= base_url('berita/detail/'.$u_key) ?>" class="clean-news-main">
                        <?php if(!empty($u_img) && file_exists($u_file)): ?>
                            <img src="<?= base_url('assets/news/'.$u_img) ?>" alt="<?= $u_judul ?>">
                        <?php else: ?>
                            <div class="d-flex align-items-center justify-content-center bg-light text-muted fs-1" style="height:250px;">
                                <i class="bi bi-newspaper"></i>
                            </div>
                        <?php endif; ?>
                        <div class="p-4 d-flex flex-column flex-grow-1 justify-content-between">
                            <div>
                                <div class="d-flex align-items-center gap-2 mb-2">
                                    <span class="badge bg-success-subtle text-success border border-success-subtle rounded-pill font-monospace" style="font-size: 11px;">
                                        <?= !empty($berita_utama->kategori) ? web_clean($berita_utama->kategori) : 'Umum' ?>
                                    </span>
                                    <small class="text-muted">
                                        <i class="bi bi-calendar3 me-1"></i><?= !empty($berita_utama->created_at) ? date('d M Y', strtotime($berita_utama->created_at)) : date('d M Y') ?>
                                    </small>
                                </div>
                                <h3 class="fw-bold text-dark mb-2" style="font-size: 19px; line-height: 1.35;">
                                    <?= $u_judul ?>
                                </h3>
                                <p class="text-muted small mb-3">
                                    <?= web_clean($u_isi) ?>
                                </p>
                            </div>
                            <span class="text-success fw-bold small d-inline-flex align-items-center gap-1">
                                Baca Selengkapnya <i class="bi bi-arrow-right"></i>
                            </span>
                        </div>
                    </a>
                </div>

                <!-- Berita Samping Stack -->
                <div class="col-lg-6">
                    <?php if(!empty($berita_lainnya)): ?>
                        <?php foreach($berita_lainnya as $b): ?>
                            <?php
                            $b_judul = web_clean($b->judul ?? '');
                            $b_img   = $b->gambar ?? '';
                            $b_file  = !empty($b_img) ? FCPATH . 'assets/news/' . $b_img : '';
                            $b_key   = !empty($b->slug) ? $b->slug : $b->id;
                            ?>
                            <a href="<?= base_url('berita/detail/'.$b_key) ?>" class="clean-news-side">
                                <?php if(!empty($b_img) && file_exists($b_file)): ?>
                                    <img src="<?= base_url('assets/news/'.$b_img) ?>" alt="<?= $b_judul ?>">
                                <?php else: ?>
                                    <div class="d-flex align-items-center justify-content-center bg-light text-muted rounded-3" style="width:76px; height:76px; flex-shrink:0;">
                                        <i class="bi bi-newspaper fs-4"></i>
                                    </div>
                                <?php endif; ?>
                                <div>
                                    <div class="d-flex align-items-center gap-2 mb-1">
                                        <small class="badge bg-secondary-subtle text-secondary rounded-pill" style="font-size:10px;">
                                            <?= !empty($b->kategori) ? web_clean($b->kategori) : 'Info' ?>
                                        </small>
                                        <small class="text-muted" style="font-size:11px;">
                                            <?= !empty($b->created_at) ? date('d M Y', strtotime($b->created_at)) : '-' ?>
                                        </small>
                                    </div>
                                    <h5 class="fw-bold text-dark mb-0" style="font-size: 14.5px; line-height: 1.35;">
                                        <?= $b_judul ?>
                                    </h5>
                                </div>
                            </a>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>
        <?php else: ?>
            <div class="text-center py-5 bg-light rounded-4 border text-muted">Belum ada berita yang dipublikasikan.</div>
        <?php endif; ?>
    </div>
</section>

<!-- ═══ 8. SEKILAS MADRASAH & SAMBUTAN RESMI KEPALA MADRASAH ═══ -->
<section class="py-5" id="sambutan-lengkap" style="background: #f8fafc; border-top: 1px solid #e2e8f0; border-bottom: 1px solid #e2e8f0;">
    <div class="container py-3">
        <div class="row align-items-center g-5">
            <!-- Sisi Kiri: Foto Portrait Resmi Pimpinan Madrasah (3:4) -->
            <div class="col-lg-4 text-center">
                <div class="p-3 bg-white rounded-4 border shadow-sm mx-auto" style="max-width: 310px;">
                    <div style="width: 100%; height: 350px; border-radius: 16px; overflow: hidden; background: #e2e8f0; margin-bottom: 14px;">
                        <?php if(!empty($sambutan_foto_url)): ?>
                            <img src="<?= $sambutan_foto_url ?>" alt="<?= htmlspecialchars($sambutan_nama, ENT_QUOTES, 'UTF-8') ?>" style="width: 100%; height: 100%; object-fit: cover;">
                        <?php else: ?>
                            <div class="w-100 h-100 d-flex align-items-center justify-content-center bg-light text-success fs-1">
                                <i class="bi bi-person-fill"></i>
                            </div>
                        <?php endif; ?>
                    </div>
                    <span class="badge bg-success-subtle text-success border border-success-subtle rounded-pill px-3 py-1 font-monospace mb-1" style="font-size: 11px;">
                        Kepala Madrasah
                    </span>
                    <h5 class="fw-bold text-dark mb-0 mt-1" style="font-size: 15.5px; line-height: 1.3;">
                        <?= htmlspecialchars($sambutan_nama, ENT_QUOTES, 'UTF-8') ?>
                    </h5>
                    <small class="text-muted d-block mt-0.5" style="font-size: 12px;">
                        <?= htmlspecialchars($sambutan_jabatan, ENT_QUOTES, 'UTF-8') ?>
                    </small>
                </div>
            </div>

            <!-- Sisi Kanan: Isi Sambutan Resmi & Visi Misi -->
            <div class="col-lg-8">
                <div class="clean-pill-badge"><i class="bi bi-chat-quote-fill"></i> Kata Sambutan Resmi</div>
                <h2 class="clean-sec-title mb-3"><?= htmlspecialchars($sambutan_judul, ENT_QUOTES, 'UTF-8') ?></h2>
                
                <div class="p-4 bg-white rounded-4 border-start border-4 border-success mb-4 shadow-xs" style="font-size: 15px; line-height: 1.85; color: #334155;">
                    <?= nl2br(htmlspecialchars($sambutan_isi, ENT_QUOTES, 'UTF-8')) ?>
                </div>

                <!-- Kartu Ringkasan Visi & Misi Berdampingan -->
                <div class="row g-3">
                    <div class="col-md-6">
                        <div class="p-3 bg-white rounded-3 border h-100 shadow-xs">
                            <div class="d-flex align-items-center gap-2 mb-1.5">
                                <i class="bi bi-eye-fill text-success fs-5"></i>
                                <strong class="text-dark" style="font-size: 14px;">Visi Madrasah</strong>
                            </div>
                            <p class="text-muted small fst-italic mb-0" style="font-size: 12.5px; line-height: 1.6;">
                                "<?= !empty($profil_website->visi) ? web_clean(strip_tags($profil_website->visi)) : 'Terwujudnya Madrasah Model Sebagai Pusat Keunggulan dan Rujukan Dalam Kualitas Akademik dan Non Akademik Serta Berakhlakul Karimah' ?>"
                            </p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="p-3 bg-white rounded-3 border h-100 shadow-xs">
                            <div class="d-flex align-items-center gap-2 mb-1.5">
                                <i class="bi bi-bullseye text-primary fs-5"></i>
                                <strong class="text-dark" style="font-size: 14px;">Misi Madrasah</strong>
                            </div>
                            <p class="text-muted small mb-2" style="font-size: 12.5px; line-height: 1.6;">
                                <?= !empty($profil_website->misi) ? web_clean(mb_strimwidth(strip_tags($profil_website->misi), 0, 115, '...')) : 'Menyelenggarakan pendidikan yang memadukan kedalaman ilmu agama dan sains teknologi.' ?>
                            </p>
                            <a href="<?= base_url('website/visi_misi') ?>" class="text-primary fw-bold text-decoration-none d-inline-flex align-items-center gap-1" style="font-size: 12px;">
                                <span>Selengkapnya</span> <i class="bi bi-arrow-right"></i>
                            </a>
                        </div>
                    </div>
                </div>

                <div class="d-flex align-items-center gap-3 pt-3">
                    <a href="<?= base_url('website/sejarah') ?>" class="btn btn-outline-success fw-bold rounded-pill px-4 py-2" style="font-size: 13px; border-width: 1.5px;">
                        <i class="bi bi-clock-history me-1"></i> Sejarah Madrasah
                    </a>
                    <a href="<?= base_url('website/fasilitas') ?>" class="btn btn-outline-secondary fw-bold rounded-pill px-4 py-2" style="font-size: 13px; border-width: 1.5px;">
                        <i class="bi bi-buildings me-1"></i> Fasilitas Kampus
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ═══ 9. MEDIA CENTER (VIDEO PROFIL & PAMFLET) ═══ -->
<section class="py-5" id="media" style="background: #ffffff;">
    <div class="container py-2">
        <div class="clean-sec-header">
            <div>
                <div class="clean-pill-badge"><i class="bi bi-play-circle-fill"></i> Media Center</div>
                <h2 class="clean-sec-title">Video Profil &amp; Pamflet Informasi</h2>
                <p class="clean-sec-sub">Publikasi audio visual dan infografis kegiatan resmi <?= $nama_madrasah ?>.</p>
            </div>
        </div>

        <div class="row g-4 align-items-stretch">
            <!-- Video Profil YouTube -->
            <div class="col-lg-7">
                <div class="p-4 bg-light rounded-4 border h-100 d-flex flex-column justify-content-between">
                    <?php if(!empty($video_embed)): ?>
                        <div class="ratio ratio-16x9 rounded-3 overflow-hidden border mb-3">
                            <iframe src="<?= htmlspecialchars($video_embed, ENT_QUOTES, 'UTF-8') ?>" 
                                    title="Video Profil MAN 3 Banjar" 
                                    allowfullscreen loading="lazy"></iframe>
                        </div>
                        <div>
                            <h4 class="fw-bold text-dark mb-1" style="font-size: 17px;">
                                <?= !empty($video_profil->judul) ? web_clean($video_profil->judul) : 'Video Profil Madrasah' ?>
                            </h4>
                            <p class="text-muted small mb-2">
                                <?= !empty($video_profil->deskripsi) ? web_clean($video_profil->deskripsi) : 'Dokumentasi lingkungan dan sarana madrasah.' ?>
                            </p>
                            <?php if(!empty($video_profil->youtube_url)): ?>
                                <a href="<?= htmlspecialchars($video_profil->youtube_url, ENT_QUOTES, 'UTF-8') ?>" target="_blank" rel="noopener" class="text-danger fw-bold small text-decoration-none">
                                    <i class="bi bi-youtube"></i> Buka di YouTube →
                                </a>
                            <?php endif; ?>
                        </div>
                    <?php else: ?>
                        <div class="text-center py-5 my-auto text-muted">
                            <i class="bi bi-youtube fs-1 text-danger"></i>
                            <h5 class="fw-bold text-dark mt-2">Video Profil Belum Diatur</h5>
                            <p class="small text-muted mb-0">Dapat dikelola melalui menu Admin Website &gt; Video.</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Pamflet Visual -->
            <div class="col-lg-5">
                <div class="p-4 bg-light rounded-4 border h-100 d-flex flex-column">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h5 class="fw-bold text-dark mb-0" style="font-size: 15px;">
                            <i class="bi bi-image text-success me-1"></i> Pamflet Terbaru
                        </h5>
                        <a href="<?= base_url('website/pamflet') ?>" class="text-success fw-bold small text-decoration-none">Semua Pamflet →</a>
                    </div>

                    <?php if(!empty($pamflet)): ?>
                        <div class="d-flex flex-column gap-2.5 flex-grow-1">
                            <?php foreach(array_slice($pamflet, 0, 3) as $p): ?>
                                <?php
                                $p_img = $p->gambar ?? '';
                                $p_file = !empty($p_img) ? FCPATH . 'assets/pamflet/' . $p_img : '';
                                $p_url = !empty($p_img) ? base_url('assets/pamflet/' . $p_img) : '#';
                                ?>
                                <a href="<?= htmlspecialchars($p_url, ENT_QUOTES, 'UTF-8') ?>" target="_blank" class="d-flex align-items-center gap-3 p-2.5 bg-white rounded-3 border text-decoration-none shadow-xs">
                                    <?php if(!empty($p_img) && file_exists($p_file)): ?>
                                        <img src="<?= $p_url ?>" alt="<?= web_clean($p->judul ?? 'Pamflet') ?>" class="rounded-2" style="width:58px; height:58px; object-fit:cover;">
                                    <?php else: ?>
                                        <div class="rounded-2 bg-light text-muted d-flex align-items-center justify-content-center" style="width:58px; height:58px;">
                                            <i class="bi bi-file-earmark-image fs-4"></i>
                                        </div>
                                    <?php endif; ?>
                                    <div>
                                        <small class="text-muted d-block" style="font-size:11px;">
                                            <i class="bi bi-clock me-1"></i><?= !empty($p->tanggal) ? date('d M Y', strtotime($p->tanggal)) : '-' ?>
                                        </small>
                                        <strong class="text-dark d-block text-truncate" style="font-size:13px; max-width:230px;">
                                            <?= web_clean($p->judul ?? 'Pamflet Informasi') ?>
                                        </strong>
                                        <span class="text-success small fw-semibold" style="font-size:11px;">Lihat pamflet →</span>
                                    </div>
                                </a>
                            <?php endforeach; ?>
                        </div>
                    <?php else: ?>
                        <div class="text-center py-5 my-auto text-muted">
                            <i class="bi bi-images fs-1 text-muted"></i>
                            <p class="small mt-2 mb-0">Pamflet informasi belum tersedia.</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ═══ 10. KILAS PTK MADRASAH (DEWAN GURU & TATA USAHA) ═══ -->
<?php if(!empty($ptk_website)): ?>
<section class="py-5" id="ptk" style="background: #f8fafc; border-top: 1px solid #e2e8f0;">
    <div class="container py-2">
        <div class="clean-sec-header">
            <div>
                <div class="clean-pill-badge"><i class="bi bi-people-fill"></i> Tenaga Pendidik &amp; Kependidikan</div>
                <h2 class="clean-sec-title">Guru &amp; Staf Madrasah</h2>
                <p class="clean-sec-sub">Pendidik profesional yang berdedikasi membimbing dan melayani siswa.</p>
            </div>
            <a href="<?= base_url('website/ptk') ?>" class="btn btn-outline-success fw-bold rounded-pill px-4 py-2" style="font-size: 13.5px; border-width: 1.5px;">
                Direktori PTK Lengkap →
            </a>
        </div>

        <div class="row g-3">
            <?php foreach(array_slice($ptk_website, 0, 8) as $p): ?>
                <?php 
                $foto_file = !empty($p->foto) ? FCPATH . 'uploads/ptk/foto/' . $p->foto : ''; 
                $is_kependidikan = (isset($p->jenis_ptk) && strtolower($p->jenis_ptk) == 'kependidikan');
                ?>
                <div class="col-6 col-md-4 col-lg-3">
                    <div class="clean-ptk-card">
                        <?php if(!empty($p->foto) && file_exists($foto_file)): ?>
                            <img src="<?= base_url('uploads/ptk/foto/' . $p->foto) ?>" alt="<?= web_clean($p->nama_lengkap ?? 'PTK') ?>" class="clean-ptk-photo">
                        <?php else: ?>
                            <div class="clean-ptk-avatar">
                                <?= !empty($p->nama_lengkap) ? strtoupper(substr($p->nama_lengkap,0,1)) : 'P' ?>
                            </div>
                        <?php endif; ?>
                        
                        <h5 class="fw-bold text-dark mb-1 text-truncate w-100" style="font-size: 13.5px;" title="<?= web_clean($p->nama_lengkap ?? '-') ?>">
                            <?= web_clean($p->nama_lengkap ?? '-') ?>
                        </h5>
                        <p class="text-muted small mb-2 text-truncate w-100" style="font-size: 11.5px;">
                            <?= !empty($p->jabatan) ? web_clean($p->jabatan) : 'PTK Madrasah' ?>
                        </p>
                        
                        <?php if($is_kependidikan): ?>
                            <span class="badge bg-secondary-subtle text-secondary rounded-pill mt-auto" style="font-size:10px;">Kependidikan</span>
                        <?php else: ?>
                            <span class="badge bg-success-subtle text-success rounded-pill mt-auto" style="font-size:10px;">Pendidik</span>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>
<?php endif; ?>

<!-- ═══ 11. GALERI DOKUMENTASI KEGIATAN ═══ -->
<?php if(!empty($galeri)): ?>
<section class="py-5" id="galeri" style="background: #ffffff; border-top: 1px solid #e2e8f0;">
    <div class="container py-2">
        <div class="clean-sec-header">
            <div>
                <div class="clean-pill-badge"><i class="bi bi-images"></i> Dokumentasi</div>
                <h2 class="clean-sec-title">Galeri Kegiatan Santri</h2>
                <p class="clean-sec-sub">Potret dinamika akademik, kepramukaan, dan keagamaan di <?= $nama_madrasah ?>.</p>
            </div>
            <a href="<?= base_url('website/galeri') ?>" class="btn btn-outline-success fw-bold rounded-pill px-4 py-2" style="font-size: 13.5px; border-width: 1.5px;">
                Semua Galeri →
            </a>
        </div>

        <div class="row g-3">
            <?php foreach(array_slice($galeri, 0, 6) as $g): ?>
                <?php
                $g_img  = $g->gambar ?? '';
                $g_file = !empty($g_img) ? FCPATH . 'assets/galeri/' . $g_img : '';
                ?>
                <?php if(!empty($g_img) && file_exists($g_file)): ?>
                    <div class="col-6 col-md-4 col-lg-2">
                        <a href="<?= base_url('assets/galeri/'.$g_img) ?>" target="_blank" class="d-block rounded-3 overflow-hidden border shadow-xs" style="height:140px;">
                            <img src="<?= base_url('assets/galeri/'.$g_img) ?>" alt="<?= web_clean($g->judul ?? 'Galeri') ?>" style="width:100%; height:100%; object-fit:cover; transition:transform 0.25s;" onmouseover="this.style.transform='scale(1.06)'" onmouseout="this.style.transform='scale(1)'">
                        </a>
                    </div>
                <?php endif; ?>
            <?php endforeach; ?>
        </div>
    </div>
</section>
<?php endif; ?>

<!-- ═══ 12. MENU LAYANAN DIGITAL & CARD PMB TERPADU ═══ -->
<section class="py-5" id="layanan" style="background: #f8fafc; border-top: 1px solid #e2e8f0;">
    <div class="container py-2">
        <div class="row g-4 align-items-center">
            <div class="col-lg-8">
                <div class="clean-pill-badge"><i class="bi bi-grid-3x3-gap"></i> Layanan Terpadu</div>
                <h2 class="clean-sec-title">Ekosistem Digital Madrasah</h2>
                <p class="clean-sec-sub mb-4">Kemudahan akses pelayanan informasi, administrasi, dan akademik madrasah.</p>

                <div class="row g-3">
                    <div class="col-sm-6">
                        <div class="clean-service-box">
                            <i class="bi bi-person-plus text-success fs-3 mb-2 d-block"></i>
                            <h5 class="fw-bold text-dark" style="font-size: 15px;">PPDB Online</h5>
                            <p class="text-muted small mb-0">Pendaftaran santri baru secara online, cepat, dan transparan.</p>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="clean-service-box">
                            <i class="bi bi-journal-bookmark text-primary fs-3 mb-2 d-block"></i>
                            <h5 class="fw-bold text-dark" style="font-size: 15px;">Akademik &amp; KBM</h5>
                            <p class="text-muted small mb-0">Pantau proses mengajar harian guru dan nilai digital siswa.</p>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="clean-service-box">
                            <i class="bi bi-file-earmark-text text-warning fs-3 mb-2 d-block"></i>
                            <h5 class="fw-bold text-dark" style="font-size: 15px;">Tata Usaha</h5>
                            <p class="text-muted small mb-0">Pelayanan administrasi persuratan, inventaris, dan kepegawaian.</p>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="clean-service-box">
                            <i class="bi bi-shield-check text-info fs-3 mb-2 d-block"></i>
                            <h5 class="fw-bold text-dark" style="font-size: 15px;">Verifikasi Ijazah</h5>
                            <p class="text-muted small mb-0">Otentikasi foto dan kelengkapan berkas kelulusan kelas XII.</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="p-4 rounded-4 border text-white text-center shadow-clean" style="background: linear-gradient(135deg, #064e3b 0%, #059669 100%);">
                    <span class="badge bg-warning text-dark fw-bold px-3 py-1 rounded-pill mb-3" style="font-size:11px;">PMB ONLINE DIBUKA</span>
                    <h3 class="fw-bold text-white mb-2" style="font-size: 22px;">Penerimaan Murid Baru</h3>
                    <p class="text-white-50 small mb-4">
                        Mari bergabung bersama keluarga besar <?= $nama_madrasah ?>. Fasilitas representatif dan dewan guru berpengalaman.
                    </p>
                    <a href="<?= base_url('pmb') ?>" class="btn btn-warning text-dark fw-bold rounded-pill px-4 py-2.5 w-100 shadow-sm" style="font-size:14px;">
                        <i class="bi bi-pencil-square me-1"></i> Daftar Sekarang
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ═══ 13. FOOTER RESMI 4 KOLOM DENGAN PETA GOOGLE MAPS ═══ -->
<footer style="background: #022c22; color: rgba(255,255,255,0.7); padding: 65px 0 25px; font-size: 13.5px;">
    <div class="container">
        <div class="row g-5 mb-5">
            <!-- Kolom 1: Profil Madrasah -->
            <div class="col-lg-3 col-md-6">
                <div class="d-flex align-items-center gap-3 mb-3">
                    <div style="width: 48px; height: 48px; border-radius: 50%; background: #ffffff; padding: 3px; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                        <?php if(file_exists($logo_file)): ?>
                            <img src="<?= $logo_url ?>" alt="Logo" style="width: 100%; height: 100%; object-fit: contain;">
                        <?php else: ?>
                            <strong style="color: #064e3b;">M3</strong>
                        <?php endif; ?>
                    </div>
                    <div>
                        <h4 style="font-weight: 800; font-size: 13px; color: #a7f3d0; margin: 0; line-height: 1.2;">MADRASAH ALIYAH</h4>
                        <h3 style="font-weight: 900; font-size: 18px; color: #ffffff; margin: 0; line-height: 1.2;">NEGERI 3 BANJAR</h3>
                    </div>
                </div>
                <p style="line-height: 1.7; font-size: 13px; color: rgba(255,255,255,0.7); margin-bottom: 16px;">
                    <?= !empty($profil_website->isi_profil) ? strip_tags($profil_website->isi_profil) : 'Pusat keunggulan pendidikan madrasah yang memadukan nilai keagamaan, sains teknologi, dan budi pekerti luhur.' ?>
                </p>
                <div class="d-flex flex-wrap gap-2">
                    <span class="badge bg-success-subtle text-success border border-success-subtle rounded-pill font-monospace" style="font-size:11px;">NSM: <?= !empty($profil_website->nsm) ? htmlspecialchars($profil_website->nsm, ENT_QUOTES, 'UTF-8') : '131163030003' ?></span>
                    <span class="badge bg-info-subtle text-info border border-info-subtle rounded-pill font-monospace" style="font-size:11px;">NPSN: <?= !empty($profil_website->npsn) ? htmlspecialchars($profil_website->npsn, ENT_QUOTES, 'UTF-8') : '30315264' ?></span>
                </div>
            </div>

            <!-- Kolom 2: Tautan Cepat -->
            <div class="col-lg-3 col-md-6">
                <h5 style="color: #ffffff; font-weight: 800; font-size: 15px; margin-bottom: 20px;">
                    Tautan Cepat
                </h5>
                <ul class="list-unstyled d-flex flex-column gap-2 mb-0">
                    <li><a href="<?= base_url('website/sejarah') ?>" class="text-white-50 text-decoration-none">Sejarah Madrasah</a></li>
                    <li><a href="<?= base_url('website/visi_misi') ?>" class="text-white-50 text-decoration-none">Visi &amp; Misi</a></li>
                    <li><a href="<?= base_url('website/fasilitas') ?>" class="text-white-50 text-decoration-none">Fasilitas Kampus</a></li>
                    <li><a href="<?= base_url('website/ptk') ?>" class="text-white-50 text-decoration-none">Direktori Guru &amp; PTK</a></li>
                    <li><a href="<?= base_url('website/berita') ?>" class="text-white-50 text-decoration-none">Warta Berita Terkini</a></li>
                </ul>
            </div>

            <!-- Kolom 3: Kontak & Saluran Komunikasi -->
            <div class="col-lg-3 col-md-6">
                <h5 style="color: #ffffff; font-weight: 800; font-size: 15px; margin-bottom: 20px;">
                    Hubungi Kami
                </h5>
                <div class="d-flex flex-column gap-2.5">
                    <div class="d-flex gap-2 align-items-start">
                        <i class="bi bi-geo-alt-fill text-warning mt-1"></i>
                        <span class="small"><?= !empty($profil_website->alamat) ? htmlspecialchars($profil_website->alamat, ENT_QUOTES, 'UTF-8') : 'Kabupaten Banjar, Kalimantan Selatan' ?></span>
                    </div>
                    <?php if(!empty($profil_website->telepon)): ?>
                        <div class="d-flex gap-2 align-items-center">
                            <i class="bi bi-telephone-fill text-success"></i>
                            <span class="small"><?= htmlspecialchars($profil_website->telepon, ENT_QUOTES, 'UTF-8') ?></span>
                        </div>
                    <?php endif; ?>
                    <?php if(!empty($profil_website->email)): ?>
                        <div class="d-flex gap-2 align-items-center">
                            <i class="bi bi-envelope-fill text-info"></i>
                            <span class="small"><?= htmlspecialchars($profil_website->email, ENT_QUOTES, 'UTF-8') ?></span>
                        </div>
                    <?php endif; ?>

                    <!-- Media Sosial -->
                    <div class="d-flex gap-2 mt-2">
                        <?php if(!empty($profil_website->facebook_url)): ?>
                            <a href="<?= htmlspecialchars($profil_website->facebook_url, ENT_QUOTES, 'UTF-8') ?>" target="_blank" class="btn btn-sm btn-outline-light rounded-circle" style="width:34px; height:34px; padding:0; display:flex; align-items:center; justify-content:center;">
                                <i class="bi bi-facebook"></i>
                            </a>
                        <?php endif; ?>
                        <?php if(!empty($profil_website->instagram_url)): ?>
                            <a href="<?= htmlspecialchars($profil_website->instagram_url, ENT_QUOTES, 'UTF-8') ?>" target="_blank" class="btn btn-sm btn-outline-light rounded-circle" style="width:34px; height:34px; padding:0; display:flex; align-items:center; justify-content:center;">
                                <i class="bi bi-instagram"></i>
                            </a>
                        <?php endif; ?>
                        <?php if(!empty($profil_website->youtube_url)): ?>
                            <a href="<?= htmlspecialchars($profil_website->youtube_url, ENT_QUOTES, 'UTF-8') ?>" target="_blank" class="btn btn-sm btn-outline-light rounded-circle" style="width:34px; height:34px; padding:0; display:flex; align-items:center; justify-content:center;">
                                <i class="bi bi-youtube"></i>
                            </a>
                        <?php endif; ?>
                        <?php if(!empty($wa_number)): ?>
                            <a href="https://wa.me/<?= $wa_number ?>" target="_blank" class="btn btn-sm btn-success rounded-circle" style="width:34px; height:34px; padding:0; display:flex; align-items:center; justify-content:center;">
                                <i class="bi bi-whatsapp"></i>
                            </a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <!-- Kolom 4: Peta Lokasi Google Maps -->
            <div class="col-lg-3 col-md-6">
                <h5 style="color: #ffffff; font-weight: 800; font-size: 15px; margin-bottom: 20px;">
                    Peta Lokasi
                </h5>
                <?php if(!empty($profil_website->maps_embed_url)): ?>
                    <div style="border-radius: 12px; overflow: hidden; height: 160px; border: 1px solid rgba(255,255,255,0.15);">
                        <iframe src="<?= web_clean($profil_website->maps_embed_url) ?>" style="width: 100%; height: 100%; border: 0;" allowfullscreen="" loading="lazy"></iframe>
                    </div>
                <?php else: ?>
                    <div class="p-3 rounded-3 text-white-50 text-center border border-white border-opacity-10 small">
                        Peta lokasi dapat diatur di menu Admin Website &gt; Profil.
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <div class="pt-4 border-top border-white border-opacity-10 d-flex justify-content-between align-items-center flex-wrap gap-2 text-white-50 small">
            <span>&copy; <?= date('Y') ?> <?= $nama_madrasah ?>. Seluruh Hak Cipta Dilindungi. | capthdr</span>
            <span>Sistem Informasi Manajemen Madrasah Terpadu</span>
        </div>
    </div>
</footer>

<!-- ═══ 14. FLOATING WA ACTION ═══ -->
<?php if(!empty($wa_number)): ?>
    <a href="https://wa.me/<?= $wa_number ?>?text=Assalamualaikum%20admin%20<?= urlencode($nama_madrasah) ?>"
       class="web-floating-wa" target="_blank" title="Hubungi Kami via WhatsApp">
        <i class="bi bi-whatsapp"></i>
    </a>
<?php endif; ?>

<!-- Bootstrap 5 JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    /* Navbar Scrolled Class */
    const nav = document.getElementById('mainNav');
    if(nav) {
        window.addEventListener('scroll', function() {
            nav.classList.toggle('scrolled', window.scrollY > 20);
        });
    }

    /* Smooth Scroll */
    document.querySelectorAll('a[href^="#"]').forEach(function(anchor) {
        anchor.addEventListener('click', function(e) {
            const targetId = this.getAttribute('href');
            if(targetId === '#') return;
            const targetEl = document.querySelector(targetId);
            if(targetEl) {
                e.preventDefault();
                const navHeight = nav ? nav.offsetHeight : 70;
                window.scrollTo({
                    top: targetEl.getBoundingClientRect().top + window.pageYOffset - navHeight - 10,
                    behavior: 'smooth'
                });
            }
        });
    });
});

// Floating Toast Pengganti Popup 'localhost says'
function showGlobalWebToast(message, type) {
    type = type || 'info';
    var bg = '#059669';
    var icon = 'bi-info-circle-fill';
    if (type === 'danger' || type === 'error') {
        bg = '#dc2626';
        icon = 'bi-exclamation-triangle-fill';
    } else if (type === 'warning') {
        bg = '#d97706';
        icon = 'bi-exclamation-circle-fill';
    } else if (type === 'success') {
        bg = '#16a34a';
        icon = 'bi-check-circle-fill';
    }

    var existing = document.getElementById('webGlobalToastContainer');
    if (!existing) {
        existing = document.createElement('div');
        existing.id = 'webGlobalToastContainer';
        existing.style.cssText = 'position:fixed; top:24px; right:20px; z-index:99999; display:flex; flex-direction:column; gap:10px; max-width:380px; width:calc(100% - 40px); pointer-events:none;';
        document.body.appendChild(existing);
    }

    var toast = document.createElement('div');
    toast.style.cssText = 'background:' + bg + '; color:#fff; padding:14px 18px; border-radius:14px; box-shadow:0 12px 35px rgba(0,0,0,0.25); display:flex; align-items:center; gap:12px; font-size:13.5px; font-weight:600; pointer-events:auto; border:1px solid rgba(255,255,255,0.2); animation:slideIn 0.3s ease;';
    toast.innerHTML = '<i class="bi ' + icon + '" style="font-size:20px; flex-shrink:0;"></i>' +
        '<div style="flex-grow:1; line-height:1.4;">' + message.replace(/\n/g, '<br>') + '</div>' +
        '<button type="button" style="background:none; border:none; color:#fff; font-size:18px; cursor:pointer; opacity:0.8; padding:0 4px; line-height:1;" onclick="this.parentElement.remove()">✕</button>';

    existing.appendChild(toast);
    setTimeout(function() {
        toast.style.transition = 'opacity 0.4s ease, transform 0.4s ease';
        toast.style.opacity = '0';
        toast.style.transform = 'translateY(-10px)';
        setTimeout(function() { toast.remove(); }, 400);
    }, 4500);
}

window.alert = function(msg) {
    showGlobalWebToast(msg, 'warning');
};
</script>

</body>
</html>
