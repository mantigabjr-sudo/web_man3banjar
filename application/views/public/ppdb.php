<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">

<title><?= htmlspecialchars($nama_ppdb ?? 'PMB') ?> MAN 3 Banjar — <?= !empty($settings->judul_panjang_ppdb) ? htmlspecialchars($settings->judul_panjang_ppdb) : 'Penerimaan Murid Baru' ?></title>
<meta name="description" content="Halaman pendaftaran <?= !empty($settings->judul_panjang_ppdb) ? htmlspecialchars($settings->judul_panjang_ppdb) : 'Penerimaan Murid Baru' ?> (<?= htmlspecialchars($nama_ppdb ?? 'PMB') ?>) MAN 3 Banjar. Daftar online, lengkapi data, dan pantau status pendaftaran Anda.">

<link rel="icon" type="image/png" href="<?= base_url('assets/img/favicon.png') ?>">
<link rel="shortcut icon" type="image/png" href="<?= base_url('assets/img/favicon.png') ?>">
<link rel="apple-touch-icon" href="<?= base_url('assets/img/favicon.png') ?>">

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<?php
/* ─── Data Logic ─── */
$nama          = htmlspecialchars($nama_ppdb ?? 'PMB');
$judul_panjang = !empty($settings->judul_panjang_ppdb) ? htmlspecialchars($settings->judul_panjang_ppdb) : ($nama == 'PMB' ? 'Penerimaan Murid Baru' : 'Penerimaan Peserta Didik Baru');
$ta            = isset($settings->tahun_ajaran) ? htmlspecialchars($settings->tahun_ajaran) : date('Y').'/'.(date('Y')+1);
$tgl_mulai     = $settings->tanggal_mulai ?? '';
$tgl_selesai   = $settings->tanggal_selesai ?? '';
$today         = date('Y-m-d');

$nama_madrasah = !empty($profil_website->nama_madrasah) ? $profil_website->nama_madrasah : 'MAN 3 Banjar';

$is_open        = true;
$countdown_date = '';
$status_label   = 'Pendaftaran Sedang Dibuka';
$status_color   = '#16a34a';
$status_bg      = '#dcfce7';
$status_icon    = 'bi-check-circle-fill';

if(!empty($tgl_mulai) && $today < $tgl_mulai) {
    $is_open        = false;
    $countdown_date = $tgl_mulai;
    $status_label   = 'Pendaftaran Segera Dibuka';
    $status_color   = '#d97706';
    $status_bg      = '#fef3c7';
    $status_icon    = 'bi-clock-fill';
} elseif(!empty($tgl_selesai) && $today > $tgl_selesai) {
    $is_open        = false;
    $status_label   = 'Pendaftaran Telah Ditutup';
    $status_color   = '#dc2626';
    $status_bg      = '#fee2e2';
    $status_icon    = 'bi-x-circle-fill';
}

$persyaratan_default = [
    "Kartu Keluarga (KK) asli atau fotokopi legalisir.",
    "Akta Kelahiran calon peserta didik baru.",
    "Nomor Induk Siswa Nasional (NISN) aktif dari sekolah asal.",
    "Ijazah / Surat Keterangan Lulus (SKL) dari SMP/MTs asal.",
    "Pas foto formal terbaru calon peserta didik (background merah/biru).",
    "Nomor WhatsApp aktif calon peserta didik atau orang tua/wali."
];
$syarat_list = $persyaratan_default;
if(!empty($settings->persyaratan_ppdb)) {
    $custom_syarat = array_filter(array_map('trim', explode("\n", $settings->persyaratan_ppdb)));
    if(!empty($custom_syarat)) {
        $syarat_list = $custom_syarat;
    }
}

$wa_number = '';
if(!empty($profil_website->whatsapp)){
    $wa_number = preg_replace('/[^0-9]/', '', $profil_website->whatsapp);
    if(substr($wa_number, 0, 1) == '0') $wa_number = '62'.substr($wa_number, 1);
} elseif(!empty($profil_website->telepon)){
    $wa_number = preg_replace('/[^0-9]/', '', $profil_website->telepon);
    if(substr($wa_number, 0, 1) == '0') $wa_number = '62'.substr($wa_number, 1);
}
?>

<style>
/* ═══════════════════════════════════════════════════
   DESIGN SYSTEM — PPDB MAN 3 BANJAR (MODERN & CLEAN)
   ═══════════════════════════════════════════════════ */
:root {
    --c-emerald-950: #064e3b;
    --c-emerald-900: #065f46;
    --c-emerald-800: #047857;
    --c-emerald-700: #059669;
    --c-emerald-600: #10b981;
    --c-emerald-500: #34d399;
    --c-emerald-400: #6ee7b7;
    --c-emerald-100: #d1fae5;
    --c-emerald-50:  #ecfdf5;

    --c-slate-900: #0f172a;
    --c-slate-800: #1e293b;
    --c-slate-700: #334155;
    --c-slate-600: #475569;
    --c-slate-500: #64748b;
    --c-slate-400: #94a3b8;
    --c-slate-300: #cbd5e1;
    --c-slate-200: #e2e8f0;
    --c-slate-100: #f1f5f9;
    --c-slate-50:  #f8fafc;

    --radius-sm: 10px;
    --radius-md: 16px;
    --radius-lg: 24px;
    --radius-xl: 32px;
    --radius-full: 9999px;

    --shadow-sm: 0 1px 3px rgba(0,0,0,.06), 0 1px 2px rgba(0,0,0,.04);
    --shadow-md: 0 4px 20px rgba(0,0,0,.07);
    --shadow-lg: 0 12px 36px rgba(0,0,0,.09);
    --shadow-green: 0 10px 30px rgba(5,150,105,.22);

    --font: 'Inter', system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif;
}

* { box-sizing: border-box; margin: 0; padding: 0; }
html { scroll-behavior: smooth; }
body {
    font-family: var(--font);
    color: var(--c-slate-800);
    background: #fdfdfd;
    line-height: 1.6;
    -webkit-font-smoothing: antialiased;
}

a { text-decoration: none; color: inherit; }

/* ─── Topbar ─── */
.ppdb-topbar {
    background: #022c22;
    color: rgba(255,255,255,.8);
    font-size: 12px;
    font-weight: 600;
    padding: 7px 0;
    border-bottom: 1px solid rgba(255,255,255,.1);
}
.ppdb-topbar-inner {
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 12px;
    flex-wrap: wrap;
}

/* ─── Navbar ─── */
.ppdb-nav {
    position: sticky;
    top: 0;
    z-index: 1000;
    background: rgba(255,255,255,.94);
    backdrop-filter: blur(16px);
    -webkit-backdrop-filter: blur(16px);
    border-bottom: 1px solid var(--c-slate-200);
    transition: all .25s ease;
}
.ppdb-nav.scrolled {
    box-shadow: var(--shadow-md);
    background: rgba(255,255,255,.98);
}
.ppdb-nav .container {
    display: flex;
    align-items: center;
    justify-content: space-between;
    height: 70px;
    gap: 16px;
}
.nav-brand {
    display: flex;
    align-items: center;
    gap: 12px;
    font-weight: 800;
    color: var(--c-emerald-950);
    font-size: 16px;
    white-space: nowrap;
}
.nav-brand-logo {
    width: 44px; height: 44px;
    border-radius: 50%;
    background: #fff;
    border: 2px solid var(--c-emerald-500);
    padding: 3px;
    display: flex; align-items: center; justify-content: center;
    flex-shrink: 0;
}
.nav-brand-logo img { width: 100%; height: 100%; object-fit: contain; }
.nav-brand-text strong { display: block; font-size: 15px; color: var(--c-emerald-950); line-height: 1.2; }
.nav-brand-text small { display: block; font-size: 11px; color: var(--c-slate-500); font-weight: 600; }

.nav-links {
    display: flex;
    align-items: center;
    gap: 8px;
}
.nav-links a {
    padding: 8px 16px;
    border-radius: var(--radius-full);
    font-weight: 600;
    font-size: 13.5px;
    color: var(--c-slate-600);
    transition: all .2s ease;
}
.nav-links a:hover {
    color: var(--c-emerald-700);
    background: var(--c-emerald-50);
}
.nav-cta {
    display: flex;
    align-items: center;
    gap: 10px;
}
.btn-nav-back {
    padding: 8px 16px;
    border-radius: var(--radius-full);
    font-weight: 600;
    font-size: 13px;
    color: var(--c-slate-600);
    border: 1px solid var(--c-slate-300);
    background: #fff;
    transition: all .2s;
}
.btn-nav-back:hover { background: var(--c-slate-100); color: var(--c-slate-900); }

.btn-nav-login {
    padding: 8px 18px;
    border-radius: var(--radius-full);
    font-weight: 700;
    font-size: 13px;
    color: var(--c-emerald-800);
    background: var(--c-emerald-50);
    border: 1.5px solid var(--c-emerald-400);
    transition: all .2s;
    display: inline-flex; align-items: center; gap: 6px;
}
.btn-nav-login:hover { background: var(--c-emerald-100); color: var(--c-emerald-900); }

.btn-nav-daftar {
    padding: 8px 22px;
    border-radius: var(--radius-full);
    font-weight: 700;
    font-size: 13px;
    color: #fff;
    background: linear-gradient(135deg, #059669, #10b981);
    border: none;
    box-shadow: var(--shadow-green);
    transition: all .2s;
    cursor: pointer;
    display: inline-flex; align-items: center; gap: 6px;
}
.btn-nav-daftar:hover { transform: translateY(-1.5px); box-shadow: 0 12px 30px rgba(5,150,105,.3); }
.btn-nav-daftar:disabled, .btn-nav-daftar.disabled {
    opacity: .65; cursor: not-allowed; transform: none; box-shadow: none;
}

.nav-mobile-toggle {
    display: none;
    background: none; border: none;
    font-size: 24px; color: var(--c-slate-700);
    cursor: pointer; padding: 4px;
}

/* ─── Hero ─── */
.hero {
    padding: 70px 0 60px;
    text-align: center;
    position: relative;
    overflow: hidden;
    background:
        radial-gradient(ellipse at top center, rgba(16,185,129,.14) 0%, transparent 60%),
        linear-gradient(180deg, #ffffff 0%, #f8fafc 100%);
    border-bottom: 1px solid var(--c-slate-200);
}
.hero-status {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 8px 20px;
    border-radius: var(--radius-full);
    font-weight: 700;
    font-size: 13.5px;
    margin-bottom: 24px;
    box-shadow: var(--shadow-sm);
    letter-spacing: .2px;
}
.hero h1 {
    font-size: clamp(32px, 5.5vw, 54px);
    font-weight: 900;
    line-height: 1.15;
    letter-spacing: -1.2px;
    color: var(--c-emerald-950);
    margin-bottom: 18px;
    max-width: 800px;
    margin-left: auto;
    margin-right: auto;
}
.hero h1 span {
    color: #059669;
}
.hero-sub {
    font-size: clamp(15px, 2.5vw, 17px);
    color: var(--c-slate-600);
    max-width: 680px;
    margin: 0 auto 28px;
    font-weight: 500;
    line-height: 1.65;
}

.hero-info-bar {
    display: inline-flex;
    align-items: center;
    gap: 16px;
    background: #fff;
    padding: 10px 24px;
    border-radius: var(--radius-full);
    border: 1.5px solid var(--c-emerald-100);
    box-shadow: var(--shadow-md);
    margin-bottom: 32px;
    flex-wrap: wrap;
    justify-content: center;
}
.hero-info-item {
    font-size: 13.5px;
    font-weight: 700;
    color: var(--c-emerald-900);
    display: flex;
    align-items: center;
    gap: 8px;
}
.hero-info-item i { color: #059669; font-size: 16px; }
.hero-info-divider {
    width: 1px;
    height: 18px;
    background: var(--c-slate-300);
}

.hero-actions {
    display: flex;
    justify-content: center;
    gap: 14px;
    flex-wrap: wrap;
}
.btn-hero-main {
    min-height: 52px;
    padding: 0 32px;
    border-radius: var(--radius-full);
    font-weight: 800;
    font-size: 15px;
    color: #fff;
    background: linear-gradient(135deg, #059669, #10b981);
    border: none;
    box-shadow: var(--shadow-green);
    display: inline-flex;
    align-items: center;
    gap: 10px;
    cursor: pointer;
    transition: all .25s ease;
}
.btn-hero-main:hover {
    transform: translateY(-2px);
    box-shadow: 0 16px 36px rgba(5,150,105,.3);
    color: #fff;
}
.btn-hero-main.disabled {
    opacity: .65; cursor: not-allowed; transform: none; box-shadow: none;
}
.btn-hero-soft {
    min-height: 52px;
    padding: 0 28px;
    border-radius: var(--radius-full);
    font-weight: 700;
    font-size: 15px;
    color: var(--c-emerald-900);
    background: #fff;
    border: 2px solid var(--c-emerald-200);
    display: inline-flex;
    align-items: center;
    gap: 10px;
    transition: all .25s ease;
}
.btn-hero-soft:hover {
    background: var(--c-emerald-50);
    border-color: #059669;
    color: #059669;
    transform: translateY(-2px);
}

/* ─── Sections ─── */
.section {
    padding: 70px 0;
}
.section-alt {
    background: #f8fafc;
}
.section-head {
    text-align: center;
    max-width: 680px;
    margin: 0 auto 48px;
}
.section-tag {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 6px 14px;
    border-radius: var(--radius-full);
    background: var(--c-emerald-100);
    color: var(--c-emerald-800);
    font-size: 12.5px;
    font-weight: 800;
    text-transform: uppercase;
    letter-spacing: .5px;
    margin-bottom: 12px;
}
.section-head h2 {
    font-size: clamp(24px, 4vw, 36px);
    font-weight: 900;
    color: var(--c-emerald-950);
    margin-bottom: 12px;
    letter-spacing: -.5px;
}
.section-head p {
    color: var(--c-slate-600);
    font-size: 15px;
    line-height: 1.6;
    margin: 0;
}

/* ─── Info Cards ─── */
.info-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
    gap: 24px;
}
.info-card {
    background: #fff;
    border: 1px solid var(--c-slate-200);
    border-radius: var(--radius-lg);
    padding: 32px 28px;
    box-shadow: var(--shadow-sm);
    transition: all .3s ease;
    display: flex;
    flex-direction: column;
    height: 100%;
}
.info-card:hover {
    transform: translateY(-5px);
    box-shadow: var(--shadow-lg);
    border-color: var(--c-emerald-400);
}
.info-card-icon {
    width: 56px;
    height: 56px;
    border-radius: var(--radius-md);
    background: linear-gradient(135deg, var(--c-emerald-50), var(--c-emerald-100));
    color: #059669;
    font-size: 26px;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-bottom: 20px;
    border: 1px solid var(--c-emerald-200);
}
.info-card h5 {
    font-size: 18px;
    font-weight: 800;
    color: var(--c-slate-900);
    margin-bottom: 10px;
}
.info-card p {
    font-size: 14px;
    color: var(--c-slate-600);
    line-height: 1.65;
    margin: 0;
}

/* ─── Timeline Alur ─── */
.timeline {
    position: relative;
    max-width: 820px;
    margin: 0 auto;
    padding-left: 56px;
}
.timeline::before {
    content: "";
    position: absolute;
    top: 24px;
    bottom: 24px;
    left: 20px;
    width: 3px;
    background: linear-gradient(180deg, #059669, #34d399, var(--c-slate-200));
    border-radius: 3px;
}
.timeline-item {
    position: relative;
    margin-bottom: 32px;
    background: #fff;
    padding: 24px 28px;
    border-radius: var(--radius-md);
    border: 1px solid var(--c-slate-200);
    box-shadow: var(--shadow-sm);
    transition: all .25s ease;
}
.timeline-item:hover {
    box-shadow: var(--shadow-md);
    border-color: var(--c-emerald-300);
    transform: translateX(4px);
}
.timeline-dot {
    position: absolute;
    left: -56px;
    top: 20px;
    width: 42px;
    height: 42px;
    border-radius: 50%;
    background: #059669;
    color: #fff;
    font-weight: 800;
    font-size: 16px;
    display: flex;
    align-items: center;
    justify-content: center;
    box-shadow: 0 0 0 5px #fff, 0 4px 12px rgba(5,150,105,.3);
}
.timeline-item h5 {
    font-size: 17px;
    font-weight: 800;
    color: var(--c-emerald-950);
    margin-bottom: 8px;
}
.timeline-item p {
    font-size: 14px;
    color: var(--c-slate-600);
    margin: 0;
    line-height: 1.6;
}

/* ─── Persyaratan Grid ─── */
.req-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(320px, 1fr));
    gap: 16px;
    max-width: 860px;
    margin: 0 auto;
}
.req-item {
    background: #fff;
    border: 1px solid var(--c-slate-200);
    border-radius: var(--radius-md);
    padding: 16px 20px;
    display: flex;
    align-items: center;
    gap: 14px;
    box-shadow: var(--shadow-sm);
    transition: all .2s;
}
.req-item:hover {
    border-color: var(--c-emerald-400);
    background: var(--c-emerald-50);
}
.req-check {
    width: 32px;
    height: 32px;
    border-radius: 50%;
    background: #059669;
    color: #fff;
    font-size: 16px;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
}
.req-item span {
    font-size: 14.5px;
    font-weight: 600;
    color: var(--c-slate-800);
}

/* ─── FAQ Accordion ─── */
.faq-wrap {
    max-width: 820px;
    margin: 0 auto;
}
.faq-wrap .accordion-item {
    border: 1px solid var(--c-slate-200);
    border-radius: var(--radius-md) !important;
    margin-bottom: 14px;
    overflow: hidden;
    background: #fff;
    box-shadow: var(--shadow-sm);
}
.faq-wrap .accordion-button {
    font-weight: 700;
    font-size: 15px;
    color: var(--c-slate-900);
    padding: 18px 24px;
    background: #fff;
    border: none;
    box-shadow: none;
}
.faq-wrap .accordion-button:not(.collapsed) {
    background: var(--c-emerald-50);
    color: var(--c-emerald-900);
}
.faq-wrap .accordion-body {
    padding: 8px 24px 22px;
    font-size: 14.5px;
    color: var(--c-slate-600);
    line-height: 1.7;
    background: var(--c-emerald-50);
}

/* ─── CTA Banner ─── */
.cta-banner {
    border-radius: var(--radius-xl);
    background:
        radial-gradient(ellipse at top right, rgba(250,204,21,.25) 0%, transparent 50%),
        linear-gradient(135deg, #022c22 0%, #065f46 100%);
    color: #fff;
    padding: 48px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 24px;
    flex-wrap: wrap;
    box-shadow: var(--shadow-lg);
}
.cta-banner h3 {
    font-size: 26px;
    font-weight: 900;
    margin-bottom: 8px;
}
.cta-banner p {
    font-size: 15px;
    color: rgba(255,255,255,.8);
    margin: 0;
}
.btn-cta-white {
    min-height: 50px;
    padding: 0 32px;
    border-radius: var(--radius-full);
    font-weight: 800;
    font-size: 14.5px;
    color: #064e3b;
    background: #fff;
    border: none;
    display: inline-flex;
    align-items: center;
    gap: 8px;
    cursor: pointer;
    box-shadow: 0 4px 16px rgba(0,0,0,.15);
    transition: all .2s;
}
.btn-cta-white:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 24px rgba(0,0,0,.2);
    color: #022c22;
}

/* ─── Official Full Footer ─── */
.ppdb-official-footer {
    background: #022c22;
    color: rgba(255,255,255,.7);
    padding: 60px 0 24px;
    font-size: 13.5px;
    border-top: 3px solid #059669;
}
.footer-logo-wrap {
    display: flex;
    align-items: center;
    gap: 14px;
    margin-bottom: 16px;
}
.footer-logo-img {
    width: 52px;
    height: 52px;
    border-radius: 50%;
    border: 2px solid #34d399;
    background: #fff;
    padding: 4px;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
}
.footer-logo-img img { width: 100%; height: 100%; object-fit: contain; }
.footer-title h4 { font-weight: 800; font-size: 13.5px; color: #fff; margin: 0; line-height: 1.2; }
.footer-title h3 { font-weight: 900; font-size: 17px; color: #34d399; margin: 0; line-height: 1.2; }
.footer-title small { font-size: 11px; color: rgba(255,255,255,.5); font-weight: 600; }

.footer-heading {
    color: #fff;
    font-weight: 800;
    font-size: 15px;
    margin-bottom: 20px;
    display: flex;
    align-items: center;
    gap: 8px;
}
.footer-heading i { color: #34d399; font-size: 17px; }

.footer-links-list {
    list-style: none;
    padding: 0;
    margin: 0;
    display: flex;
    flex-direction: column;
    gap: 10px;
}
.footer-links-list a {
    color: rgba(255,255,255,.75);
    display: flex;
    align-items: center;
    gap: 8px;
    transition: all .2s ease;
}
.footer-links-list a:hover {
    color: #fff;
    padding-left: 6px;
}
.footer-links-list a i { font-size: 10px; color: #34d399; }

.footer-contact-item {
    display: flex;
    gap: 12px;
    align-items: flex-start;
    margin-bottom: 14px;
}
.footer-contact-item i {
    color: #34d399;
    font-size: 16px;
    margin-top: 3px;
    flex-shrink: 0;
}

.footer-social-box {
    display: flex;
    gap: 10px;
    margin-top: 20px;
}
.footer-social-btn {
    width: 36px;
    height: 36px;
    border-radius: 8px;
    background: rgba(255,255,255,.1);
    display: flex;
    align-items: center;
    justify-content: center;
    color: #fff;
    transition: all .2s;
}
.footer-social-btn:hover {
    background: #059669;
    color: #fff;
    transform: translateY(-2px);
}

.footer-bottom {
    border-top: 1px solid rgba(255,255,255,.1);
    margin-top: 40px;
    padding-top: 20px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
    gap: 12px;
    font-size: 12.5px;
    color: rgba(255,255,255,.5);
}

/* ─── Floating WA ─── */
.floating-wa {
    position: fixed;
    bottom: 28px;
    right: 28px;
    width: 56px;
    height: 56px;
    border-radius: 50%;
    background: #25d366;
    color: #fff;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 28px;
    box-shadow: 0 8px 24px rgba(37,211,102,.4);
    z-index: 999;
    transition: all .25s ease;
}
.floating-wa:hover {
    transform: scale(1.1);
    color: #fff;
    box-shadow: 0 12px 30px rgba(37,211,102,.6);
}

/* ─── Modal Registrasi Single Page & Scrolling ─── */
.ppdb-modal-content {
    border: 0;
    border-radius: var(--radius-lg);
    background: #fff;
    box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
    display: flex;
    flex-direction: column;
    max-height: 90vh;
}

#modalDaftarPpdb .modal-dialog {
    max-width: 800px;
    margin: 1.75rem auto;
}

#modalDaftarPpdb form {
    display: flex;
    flex-direction: column;
    max-height: 90vh;
    min-height: 0;
    height: 100%;
}

#modalDaftarPpdb .modal-header {
    flex-shrink: 0;
    border-bottom: 1px solid var(--c-slate-200);
}

#modalDaftarPpdb .modal-body {
    flex: 1 1 auto;
    overflow-y: auto !important;
    -webkit-overflow-scrolling: touch;
    padding: 24px;
    max-height: calc(90vh - 140px);
}

#modalDaftarPpdb .modal-footer {
    flex-shrink: 0;
    border-top: 1px solid var(--c-slate-200);
    background: #f8fafc;
}

.ppdb-form-label {
    font-weight: 700;
    color: #064e3b;
    font-size: 13.5px;
    margin-bottom: 6px;
}
.ppdb-input, .ppdb-select {
    min-height: 48px;
    border-radius: var(--radius-sm);
    border: 1.5px solid var(--c-slate-200);
    padding: 10px 14px;
    font-weight: 600;
    font-size: 15px;
    transition: all .2s;
}
.ppdb-input:focus, .ppdb-select:focus {
    border-color: #059669;
    box-shadow: 0 0 0 4px rgba(5,150,105,.12);
}

/* ─── Responsive Mobile Mode ─── */
@media (max-width: 768px) {
    #modalDaftarPpdb .modal-dialog {
        margin: 0.5rem auto;
        width: 95%;
        max-width: 95%;
    }
    .ppdb-modal-content {
        max-height: 94vh;
        border-radius: 18px !important;
    }
    #modalDaftarPpdb form {
        max-height: 94vh;
    }
    #modalDaftarPpdb .modal-header {
        padding: 16px 16px 12px !important;
    }
    #modalDaftarPpdb .modal-header h5 {
        font-size: 17px !important;
    }
    #modalDaftarPpdb .modal-body {
        padding: 14px 16px !important;
        max-height: calc(94vh - 130px) !important;
    }
    #modalDaftarPpdb .modal-footer {
        padding: 12px 16px !important;
        flex-direction: column-reverse;
        gap: 8px;
    }
    #modalDaftarPpdb .modal-footer button {
        width: 100%;
        min-height: 44px;
    }
    .ppdb-input, .ppdb-select {
        min-height: 44px;
        font-size: 16px; /* Mencegah auto zoom pada Safari iOS / Chrome Android */
    }
}

/* Responsive */
@media (max-width: 991px) {
    .nav-links { display: none; }
    .nav-mobile-toggle { 
        display: flex;
        align-items: center;
        justify-content: center;
        width: 40px;
        height: 40px;
        border-radius: 10px;
        background: var(--c-slate-100);
        color: var(--c-slate-700);
        border: 1px solid var(--c-slate-200);
        cursor: pointer;
        transition: all .2s;
    }
    .nav-mobile-toggle:hover {
        background: var(--c-emerald-50);
        color: #059669;
    }
}

.ppdb-offcanvas {
    max-width: 330px !important;
    border-left: 3px solid #059669 !important;
    background: #ffffff;
    z-index: 1060;
}
.offcanvas-nav-item {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 12px 16px;
    border-radius: 12px;
    font-weight: 600;
    font-size: 14px;
    color: #334155;
    background: #f8fafc;
    border: 1px solid #f1f5f9;
    transition: all .2s;
    text-decoration: none;
}
.offcanvas-nav-item:hover {
    background: #ecfdf5;
    color: #059669;
    border-color: #a7f3d0;
}

@media (max-width: 768px) {
    .ppdb-topbar-inner {
        justify-content: center;
        text-align: center;
        font-size: 11.5px;
        padding: 2px 8px;
    }
    .ppdb-topbar-inner span:last-child {
        display: none;
    }
    .ppdb-nav .ppdb-nav-container {
        height: 62px;
        gap: 8px;
        padding-left: 12px;
        padding-right: 12px;
    }
    .nav-brand {
        gap: 8px;
    }
    .nav-brand-logo {
        width: 36px;
        height: 36px;
        padding: 2px;
    }
    .nav-brand-text strong {
        font-size: 13.5px;
    }
    .nav-brand-text small {
        font-size: 10px;
    }
    .nav-cta {
        gap: 6px;
    }
    .btn-nav-daftar {
        display: none; /* Sembunyikan di navbar mobile agar tidak berdesakan */
    }
    .btn-nav-login {
        padding: 7px 12px;
        font-size: 12px;
        gap: 5px;
    }
    .hero { 
        padding: 40px 14px 36px; 
    }
    .hero-status {
        font-size: 12px;
        padding: 6px 15px;
        margin-bottom: 16px;
    }
    .hero h1 {
        font-size: clamp(24px, 6.8vw, 36px);
        letter-spacing: -0.6px;
        margin-bottom: 12px;
        line-height: 1.25;
    }
    .hero-sub {
        font-size: 13.5px;
        line-height: 1.6;
        margin-bottom: 20px;
        padding: 0 4px;
    }
    .hero-info-bar {
        flex-direction: column;
        gap: 8px;
        padding: 12px 18px;
        border-radius: 20px;
        margin-bottom: 24px;
        width: 100%;
        max-width: 360px;
        margin-left: auto;
        margin-right: auto;
    }
    .hero-info-divider {
        display: none; /* Hilangkan garis menggantung di mobile */
    }
    .hero-info-item {
        font-size: 12.5px;
        justify-content: center;
        text-align: center;
    }
    .btn-hero-main {
        width: 100%;
        max-width: 320px;
        justify-content: center;
    }
    .cta-banner { text-align: center; justify-content: center; padding: 28px 16px; border-radius: 20px; }
    .timeline { padding-left: 42px; }
    .timeline-dot { left: -42px; width: 32px; height: 32px; font-size: 13px; }
    .req-grid { grid-template-columns: 1fr; }
}

@media (max-width: 420px) {
    .nav-brand-text strong {
        font-size: 12px;
        max-width: 140px;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }
}
</style>
</head>

<body>

<!-- ═══ TOPBAR ═══ -->
<div class="ppdb-topbar">
    <div class="container ppdb-topbar-inner">
        <span><i class="bi bi-building"></i> Portal <?= $judul_panjang ?> (<?= $nama ?>) <?= $nama_madrasah ?></span>
        <span class="d-none d-md-inline"><i class="bi bi-shield-check"></i> Sistem Digital Terpadu LabSys</span>
    </div>
</div>

<!-- ═══ NAVBAR ═══ -->
<nav class="ppdb-nav" id="mainNav">
    <div class="container ppdb-nav-container">
        <a href="<?= base_url() ?>" class="nav-brand">
            <div class="nav-brand-logo">
                <img src="<?= base_url('assets/img/logo-madrasah.png') ?>" alt="Logo <?= $nama_madrasah ?>" onerror="this.src='https://cdn-icons-png.flaticon.com/512/2997/2997295.png'">
            </div>
            <div class="nav-brand-text">
                <strong><?= $nama ?> <?= $nama_madrasah ?></strong>
                <small>Tahun Ajaran <?= $ta ?></small>
            </div>
        </a>

        <div class="nav-links">
            <a href="#informasi">Informasi</a>
            <a href="#alur">Alur Pendaftaran</a>
            <a href="#persyaratan">Persyaratan</a>
            <a href="#faq">FAQ</a>
        </div>

        <div class="nav-cta">
            <a href="<?= base_url() ?>" class="btn-nav-back d-none d-md-inline-flex">
                <i class="bi bi-arrow-left"></i> Web Utama
            </a>
            <a href="<?= base_url('ppdb/login') ?>" class="btn-nav-login">
                <i class="bi bi-box-arrow-in-right"></i> <span>Login Peserta</span>
            </a>
            <?php if($is_open): ?>
                <button type="button" class="btn-nav-daftar" data-bs-toggle="modal" data-bs-target="#modalDaftarPpdb">
                    <i class="bi bi-pencil-square"></i> Daftar
                </button>
            <?php else: ?>
                <button type="button" class="btn-nav-daftar disabled" disabled>Daftar</button>
            <?php endif; ?>
        </div>

        <button class="nav-mobile-toggle" type="button" data-bs-toggle="offcanvas" data-bs-target="#ppdbOffcanvasNav" aria-controls="ppdbOffcanvasNav" aria-label="Buka Menu Navigasi">
            <i class="bi bi-list fs-4"></i>
        </button>
    </div>
</nav>

<!-- ═══ OFFCANVAS MOBILE DRAWER MENU ═══ -->
<div class="offcanvas offcanvas-end ppdb-offcanvas" tabindex="-1" id="ppdbOffcanvasNav" aria-labelledby="ppdbOffcanvasLabel">
    <div class="offcanvas-header border-bottom py-3" style="background: #022c22; color: #fff;">
        <div class="d-flex align-items-center gap-3">
            <div class="nav-brand-logo bg-white p-1" style="width: 38px; height: 38px; border-radius: 50%;">
                <img src="<?= base_url('assets/img/logo-madrasah.png') ?>" alt="Logo <?= $nama_madrasah ?>" onerror="this.src='https://cdn-icons-png.flaticon.com/512/2997/2997295.png'" style="width: 100%; height: 100%; object-fit: contain;">
            </div>
            <div>
                <h6 class="offcanvas-title fw-bold mb-0 text-white" id="ppdbOffcanvasLabel" style="font-size: 14px;"><?= $nama ?> <?= $nama_madrasah ?></h6>
                <small class="text-white-50" style="font-size: 11px;">Tahun Ajaran <?= $ta ?></small>
            </div>
        </div>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="offcanvas" aria-label="Tutup"></button>
    </div>
    <div class="offcanvas-body p-3 d-flex flex-column justify-content-between">
        <div class="d-flex flex-column gap-2">
            <?php if($is_open): ?>
                <button type="button" class="btn btn-success fw-bold py-3 mb-2 rounded-3 shadow-sm d-flex align-items-center justify-content-center gap-2" style="background: linear-gradient(135deg, #059669, #10b981); border: 0; font-size: 14px;" data-bs-dismiss="offcanvas" data-bs-toggle="modal" data-bs-target="#modalDaftarPpdb">
                    <i class="bi bi-pencil-square fs-5"></i> Form Pendaftaran Baru
                </button>
            <?php endif; ?>

            <a href="<?= base_url('ppdb/login') ?>" class="offcanvas-nav-item text-primary fw-bold" style="background: #eff6ff; border-color: #bfdbfe;">
                <i class="bi bi-box-arrow-in-right fs-5 text-primary"></i> Login Akun Peserta
            </a>

            <div class="my-1 border-top"></div>

            <a href="#informasi" class="offcanvas-nav-item" data-bs-dismiss="offcanvas">
                <i class="bi bi-info-circle fs-5 text-success"></i> Informasi Pendaftaran
            </a>
            <a href="#alur" class="offcanvas-nav-item" data-bs-dismiss="offcanvas">
                <i class="bi bi-diagram-3 fs-5 text-success"></i> Alur Pendaftaran
            </a>
            <a href="#persyaratan" class="offcanvas-nav-item" data-bs-dismiss="offcanvas">
                <i class="bi bi-card-checklist fs-5 text-success"></i> Persyaratan Berkas
            </a>
            <a href="#faq" class="offcanvas-nav-item" data-bs-dismiss="offcanvas">
                <i class="bi bi-question-circle fs-5 text-success"></i> FAQ / Tanya Jawab
            </a>

            <div class="my-1 border-top"></div>

            <a href="<?= base_url() ?>" class="offcanvas-nav-item text-secondary">
                <i class="bi bi-arrow-left fs-5"></i> Kembali ke Web Utama
            </a>
        </div>

        <div class="pt-3 border-top text-center text-muted" style="font-size: 11.5px;">
            <div class="fw-bold text-dark mb-1"><?= $nama_madrasah ?></div>
            <div>Sistem Digital Terpadu LabSys</div>
        </div>
    </div>
</div>

<!-- ═══ HERO ═══ -->
<header class="hero">
    <div class="container">
        <div class="hero-status" style="background: <?= $status_bg ?>; color: <?= $status_color ?>;">
            <i class="bi <?= $status_icon ?>"></i> <?= $status_label ?>
        </div>

        <h1><?= $judul_panjang ?><br><span><?= $nama_madrasah ?></span></h1>

        <p class="hero-sub">
            Daftar secara mandiri dengan cepat, mudah, dan transparan. Lengkapi biodata, unggah berkas digital, dan pantau status kelulusan Anda secara real-time.
        </p>

        <!-- Info Bar -->
        <div class="hero-info-bar">
            <div class="hero-info-item">
                <i class="bi bi-mortarboard-fill"></i> Tahun Ajaran <?= $ta ?>
            </div>
            <?php if(!empty($tgl_mulai) && !empty($tgl_selesai)): ?>
                <div class="hero-info-divider"></div>
                <div class="hero-info-item">
                    <i class="bi bi-calendar-check-fill"></i> <?= function_exists('tanggal_indo') ? tanggal_indo($tgl_mulai) : $tgl_mulai ?> — <?= function_exists('tanggal_indo') ? tanggal_indo($tgl_selesai) : $tgl_selesai ?>
                </div>
            <?php endif; ?>
        </div>

        <!-- CTA Buttons -->
        <div class="hero-actions">
            <?php if($is_open): ?>
                <button type="button" class="btn-hero-main" data-bs-toggle="modal" data-bs-target="#modalDaftarPpdb">
                    <i class="bi bi-pencil-square"></i> Daftar Sekarang
                </button>
            <?php else: ?>
                <button type="button" class="btn-hero-main disabled" disabled>
                    <i class="bi bi-lock-fill"></i> <?= $status_label ?>
                </button>
            <?php endif; ?>

            <a href="<?= base_url('ppdb/login') ?>" class="btn-hero-soft">
                <i class="bi bi-person-circle"></i> Sudah Mendaftar? Login
            </a>
        </div>
    </div>
</header>

<!-- ═══ INFORMASI ═══ -->
<section class="section section-alt" id="informasi">
    <div class="container">
        <div class="section-head">
            <div class="section-tag"><i class="bi bi-info-circle"></i> Keunggulan Sistem</div>
            <h2>Mengapa Mendaftar di <?= $nama_madrasah ?>?</h2>
            <p>Sistem <?= $nama ?> kami dirancang modern untuk memberikan kemudahan, transparansi, dan kecepatan pelayanan bagi seluruh calon peserta didik dan wali murid.</p>
        </div>

        <div class="info-grid">
            <div class="info-card">
                <div class="info-card-icon"><i class="bi bi-laptop"></i></div>
                <h5>Pendaftaran Mandiri 24/7</h5>
                <p>Calon peserta didik dapat mendaftar kapan saja dan di mana saja melalui smartphone, tablet, maupun komputer tanpa harus antre di sekolah.</p>
            </div>

            <div class="info-card">
                <div class="info-card-icon"><i class="bi bi-cloud-arrow-up"></i></div>
                <h5>Upload Berkas Digital</h5>
                <p>Dokumen Kartu Keluarga, Akta Kelahiran, dan Ijazah diunggah langsung ke portal digital dengan aman dan tersimpan rapi di sistem madrasah.</p>
            </div>

            <div class="info-card">
                <div class="info-card-icon"><i class="bi bi-speedometer2"></i></div>
                <h5>Dashboard & Pantau Status</h5>
                <p>Setiap pendaftar memiliki akun dashboard pribadi untuk melengkapi biodata, memantau verifikasi berkas, dan mengunduh bukti kartu pendaftaran.</p>
            </div>
        </div>
    </div>
</section>

<!-- ═══ ALUR PENDAFTARAN ═══ -->
<section class="section" id="alur">
    <div class="container">
        <div class="section-head">
            <div class="section-tag"><i class="bi bi-signpost-split"></i> Alur Pendaftaran</div>
            <h2>5 Tahap Menjadi Siswa <?= $nama_madrasah ?></h2>
            <p>Proses seleksi penerimaan murid baru dilaksanakan secara terstruktur, transparan, dan berbasis sistem digital terpadu.</p>
        </div>

        <div class="timeline">
            <div class="timeline-item">
                <div class="timeline-dot">1</div>
                <h5>1. Registrasi Akun Pendaftaran</h5>
                <p>Klik tombol <strong>"Daftar Sekarang"</strong> untuk membuat akun. Cukup isikan data pokok diri, tentukan <strong>Jalur Pendaftaran</strong> (Reguler, Prestasi, Tahfidz, Afirmasi), nomor WhatsApp, email, dan buat password akun Anda dalam satu formulir mudah.</p>
            </div>

            <div class="timeline-item">
                <div class="timeline-dot">2</div>
                <h5>2. Lengkapi Biodata &amp; Unggah Dokumen Digital</h5>
                <p>Login ke <strong>Dashboard Peserta</strong> menggunakan NISN dan Password. Lengkapi formulir biodata keluarga, alamat, dan unggah scan/foto dokumen wajib (Pas Foto, KK, Akta Kelahiran, Surat Keterangan Kelas 9 / SKL, serta Sertifikat Prestasi/Tahfidz jika ada).</p>
            </div>

            <div class="timeline-item">
                <div class="timeline-dot">3</div>
                <h5>3. Verifikasi Panitia &amp; Penetapan Jadwal Tes</h5>
                <p>Tim panitia madrasah melakukan verifikasi keabsahan dokumen yang diunggah. Peserta yang dinyatakan <strong>Lulus Verifikasi</strong> akan langsung memperoleh <strong>Nomor Peserta Ujian Resmi</strong> dan jadwal tes seleksi.</p>
            </div>

            <div class="timeline-item">
                <div class="timeline-dot">4</div>
                <h5>4. Cetak Kartu Peserta &amp; Ikuti Ujian Seleksi</h5>
                <p>Unduh dan cetak <strong>Kartu Tanda Peserta Ujian</strong> ber-QR code dari dashboard. Hadir di kampus <?= $nama_madrasah ?> sesuai jadwal untuk mengikuti seleksi (Tes Potensi Akademik, Baca Tulis Al-Qur'an / BTQ, dan Wawancara).</p>
            </div>

            <div class="timeline-item">
                <div class="timeline-dot">5</div>
                <h5>5. Pengumuman Kelulusan &amp; Daftar Ulang</h5>
                <p>Pengumuman kelulusan akhir dapat dipantau langsung di Dashboard Akun Peserta. Peserta yang dinyatakan <strong>Diterima</strong> dapat segera melakukan proses registrasi / daftar ulang administrasi di madrasah.</p>
            </div>
        </div>
    </div>
</section>

<!-- ═══ PAMFLET / BROSUR ═══ -->
<?php if(!empty($settings->pamflet_ppdb)): ?>
<section class="section section-alt" id="pamflet">
    <div class="container text-center">
        <div class="section-head">
            <div class="section-tag"><i class="bi bi-image"></i> Brosur Resmi</div>
            <h2>Pamflet Informasi <?= $nama ?></h2>
            <p>Unduh atau simpan brosur resmi <?= $nama_madrasah ?> untuk informasi persyaratan dan jadwal lengkap.</p>
        </div>
        <div class="text-center">
            <img src="<?= base_url('uploads/ppdb_pamflet/'.$settings->pamflet_ppdb) ?>" alt="Pamflet <?= $nama ?>" class="img-fluid rounded-4 shadow-lg" style="max-height: 650px;">
        </div>
    </div>
</section>
<?php endif; ?>

<!-- ═══ PERSYARATAN BERKAS & DOKUMEN ═══ -->
<section class="section <?= empty($settings->pamflet_ppdb) ? 'section-alt' : '' ?>" id="persyaratan">
    <div class="container">
        <div class="section-head">
            <div class="section-tag"><i class="bi bi-clipboard-check"></i> Persyaratan Berkas</div>
            <h2>Dokumen yang Wajib Disiapkan</h2>
            <p>Pastikan Anda menyiapkan dokumen fisik maupun format digital (Foto/Scan JPG, PNG, PDF maks 2MB) sebelum melengkapi berkas di dashboard pendaftar.</p>
        </div>

        <div class="row g-4 justify-content-center">
            <!-- Dokumen Wajib Umum -->
            <div class="col-lg-6">
                <div class="card border-0 shadow-sm rounded-4 h-100 p-4" style="background: #ffffff; border: 1.5px solid #e2e8f0 !important;">
                    <div class="d-flex align-items-center gap-2 mb-3">
                        <span class="badge bg-success py-2 px-3 rounded-pill fw-bold" style="font-size: 13px;"><i class="bi bi-star-fill me-1"></i> DOKUMEN WAJIB (SEMUA JALUR)</span>
                    </div>
                    <ul class="list-unstyled d-flex flex-column gap-3 mb-0">
                        <li class="d-flex align-items-start gap-3">
                            <div class="req-check" style="background:#059669; width:28px; height:28px; font-size:14px;"><i class="bi bi-check-lg"></i></div>
                            <div>
                                <strong class="text-dark d-block">Pas Foto Formal Berwarna 3x4</strong>
                                <small class="text-muted">Terbaru dengan latar belakang (background) merah atau biru (format JPG/PNG maks 2MB).</small>
                            </div>
                        </li>
                        <li class="d-flex align-items-start gap-3">
                            <div class="req-check" style="background:#059669; width:28px; height:28px; font-size:14px;"><i class="bi bi-check-lg"></i></div>
                            <div>
                                <strong class="text-dark d-block">Kartu Keluarga (KK)</strong>
                                <small class="text-muted">Scan / foto asli atau fotokopi yang jelas dan terbaca Nomor KK serta NIK keluarga.</small>
                            </div>
                        </li>
                        <li class="d-flex align-items-start gap-3">
                            <div class="req-check" style="background:#059669; width:28px; height:28px; font-size:14px;"><i class="bi bi-check-lg"></i></div>
                            <div>
                                <strong class="text-dark d-block">Akta Kelahiran Calon Siswa</strong>
                                <small class="text-muted">Scan / foto asli atau fotokopi Akta Kelahiran calon peserta didik baru.</small>
                            </div>
                        </li>
                        <li class="d-flex align-items-start gap-3">
                            <div class="req-check" style="background:#059669; width:28px; height:28px; font-size:14px;"><i class="bi bi-check-lg"></i></div>
                            <div>
                                <strong class="text-dark d-block">Surat Keterangan Kelas 9 / SKL / Ijazah</strong>
                                <small class="text-muted">Surat Keterangan Aktif Siswa Kelas 9 dari SMP/MTs asal, atau Surat Keterangan Lulus (SKL) / Ijazah jika sudah terbit.</small>
                            </div>
                        </li>
                        <li class="d-flex align-items-start gap-3">
                            <div class="req-check" style="background:#059669; width:28px; height:28px; font-size:14px;"><i class="bi bi-check-lg"></i></div>
                            <div>
                                <strong class="text-dark d-block">Nomor Induk Siswa Nasional (NISN)</strong>
                                <small class="text-muted">NISN 10 digit yang valid dan aktif terdaftar di data EMIS / Dapodik Kemdikbud.</small>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>

            <!-- Dokumen Khusus Berdasarkan Jalur -->
            <div class="col-lg-6">
                <div class="card border-0 shadow-sm rounded-4 h-100 p-4" style="background: #f8fafc; border: 1.5px solid #cbd5e1 !important;">
                    <div class="d-flex align-items-center gap-2 mb-3">
                        <span class="badge bg-primary py-2 px-3 rounded-pill fw-bold" style="font-size: 13px;"><i class="bi bi-award-fill me-1"></i> DOKUMEN KHUSUS BERDASARKAN JALUR</span>
                    </div>
                    <ul class="list-unstyled d-flex flex-column gap-3 mb-0">
                        <li class="d-flex align-items-start gap-3">
                            <div class="req-check" style="background:#0284c7; width:28px; height:28px; font-size:14px;"><i class="bi bi-trophy-fill"></i></div>
                            <div>
                                <strong class="text-dark d-block">Jalur Prestasi (Akademik &amp; Non-Akademik)</strong>
                                <small class="text-muted">Sertifikat / Piagam Juara Lomba (KSM, OSN, O2SN, FLS2N, MTQ, Pramuka, Olahraga, Seni) minimal tingkat Kabupaten/Kota atau Provinsi/Nasional.</small>
                            </div>
                        </li>
                        <li class="d-flex align-items-start gap-3">
                            <div class="req-check" style="background:#0d9488; width:28px; height:28px; font-size:14px;"><i class="bi bi-book-half"></i></div>
                            <div>
                                <strong class="text-dark d-block">Jalur Tahfidz Al-Qur'an</strong>
                                <small class="text-muted">Syahadah / Piagam / Surat Keterangan Hafalan Al-Qur'an resmi dari Pondok Pesantren, Lembaga Tahfidz, atau Madrasah asal.</small>
                            </div>
                        </li>
                        <li class="d-flex align-items-start gap-3">
                            <div class="req-check" style="background:#d97706; width:28px; height:28px; font-size:14px;"><i class="bi bi-people-fill"></i></div>
                            <div>
                                <strong class="text-dark d-block">Jalur Afirmasi / KIP</strong>
                                <small class="text-muted">Kartu Indonesia Pintar (KIP), Kartu Program Keluarga Harapan (PKH), Kartu Keluarga Sejahtera (KKS), atau Surat Keterangan Tidak Mampu (SKTM).</small>
                            </div>
                        </li>
                        <li class="d-flex align-items-start gap-3">
                            <div class="req-check" style="background:#64748b; width:28px; height:28px; font-size:14px;"><i class="bi bi-info-circle-fill"></i></div>
                            <div>
                                <strong class="text-dark d-block">Ketentuan Unggah File</strong>
                                <small class="text-muted">Format file yang didukung adalah <strong>JPG, PNG, atau PDF</strong> dengan ukuran maksimal <strong>2 MB</strong> per file. Pastikan dokumen tidak buram/terpotong.</small>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ═══ FAQ ═══ -->
<section class="section" id="faq">
    <div class="container">
        <div class="section-head">
            <div class="section-tag"><i class="bi bi-question-circle"></i> Tanya Jawab (FAQ)</div>
            <h2>Pertanyaan yang Sering Diajukan</h2>
            <p>Jawaban atas pertanyaan umum seputar pelaksanaan pendaftaran peserta didik baru.</p>
        </div>

        <div class="faq-wrap">
            <div class="accordion" id="faqAccordion">
                <div class="accordion-item">
                    <h2 class="accordion-header">
                        <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#faq1" aria-expanded="true">
                            Bagaimana cara mendaftar di <?= $nama_madrasah ?>?
                        </button>
                    </h2>
                    <div id="faq1" class="accordion-collapse collapse show" data-bs-parent="#faqAccordion">
                        <div class="accordion-body">
                            Pendaftaran dilakukan secara online. Klik tombol <strong>"Daftar Sekarang"</strong> di bagian atas halaman ini untuk membuat akun, lalu login menggunakan NISN dan Password Anda untuk melengkapi data serta mengunggah berkas.
                        </div>
                    </div>
                </div>

                <div class="accordion-item">
                    <h2 class="accordion-header">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq2">
                            Apakah bisa mendaftar menggunakan HP / Smartphone?
                        </button>
                    </h2>
                    <div id="faq2" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                        <div class="accordion-body">
                            <strong>Sangat bisa!</strong> Sistem <?= $nama ?> <?= $nama_madrasah ?> sudah 100% responsif dan nyaman digunakan lewat HP, tablet, maupun laptop. Anda dapat langsung memfoto berkas dari kamera HP dan mengunggahnya.
                        </div>
                    </div>
                </div>

                <div class="accordion-item">
                    <h2 class="accordion-header">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq3">
                            Kapan pengumuman hasil seleksi kelulusan diumumkan?
                        </button>
                    </h2>
                    <div id="faq3" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                        <div class="accordion-body">
                            Pengumuman hasil seleksi akan tampil secara langsung di dalam <strong>Dashboard Akun Peserta</strong> Anda setelah panitia selesai melakukan verifikasi berkas dan tes/wawancara.
                        </div>
                    </div>
                </div>

                <div class="accordion-item">
                    <h2 class="accordion-header">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq4">
                            Bagaimana jika saya lupa password akun pendaftaran?
                        </button>
                    </h2>
                    <div id="faq4" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                        <div class="accordion-body">
                            Silakan hubungi Panitia <?= $nama ?> melalui kontak WhatsApp resmi yang ada di website ini dengan menyebutkan Nama Lengkap, NISN, dan Asal Sekolah untuk bantuan reset password.
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ═══ CTA BANNER ═══ -->
<section class="section section-alt">
    <div class="container">
        <div class="cta-banner">
            <div>
                <h3>Siap Bergabung dengan <?= $nama_madrasah ?>?</h3>
                <p>Raih prestasi gemilang dan wujudkan masa depan berakhlak mulia bersama kami.</p>
            </div>

            <?php if($is_open): ?>
                <button type="button" class="btn-cta-white" data-bs-toggle="modal" data-bs-target="#modalDaftarPpdb">
                    <i class="bi bi-pencil-square"></i> Daftar Sekarang
                </button>
            <?php else: ?>
                <button type="button" class="btn-cta-white" disabled>
                    <i class="bi bi-lock-fill"></i> <?= $status_label ?>
                </button>
            <?php endif; ?>
        </div>
    </div>
</section>

<!-- ═══ FOOTER RESMI MADRASAH (IDENTIK DENGAN WEB UTAMA) ═══ -->
<footer class="ppdb-official-footer">
    <div class="container">
        <div class="row g-4 mb-4">
            <!-- Kolom 1: Logo & Deskripsi -->
            <div class="col-lg-3 col-md-6">
                <div class="footer-logo-wrap">
                    <div class="footer-logo-img">
                        <img src="<?= base_url('assets/img/logo-madrasah.png') ?>" alt="Logo <?= $nama_madrasah ?>" onerror="this.src='https://cdn-icons-png.flaticon.com/512/2997/2997295.png'">
                    </div>
                    <div class="footer-title">
                        <h4>MADRASAH ALIYAH</h4>
                        <h3>NEGERI 3 BANJAR</h3>
                        <small>Kabupaten Banjar</small>
                    </div>
                </div>
                <p style="line-height: 1.7; font-size: 13px; text-align: justify; margin: 0; color: rgba(255,255,255,0.75);">
                    <?= !empty($profil_website->isi_profil) ? strip_tags($profil_website->isi_profil) : 'Terwujudnya Madrasah Model Sebagai Pusat Keunggulan dan Rujukan Dalam Kualitas Akademik dan Non Akademik Serta Akhlaq Karimah.' ?>
                </p>
            </div>

            <!-- Kolom 2: Tautan Cepat -->
            <div class="col-lg-3 col-md-6">
                <div class="footer-heading">
                    <i class="bi bi-link-45deg"></i> Tautan Cepat
                </div>
                <ul class="footer-links-list">
                    <li><a href="<?= base_url('website/sejarah') ?>"><i class="bi bi-chevron-right"></i> Sejarah Madrasah</a></li>
                    <li><a href="<?= base_url('website/visi_misi') ?>"><i class="bi bi-chevron-right"></i> Visi &amp; Misi</a></li>
                    <li><a href="<?= base_url('website/fasilitas') ?>"><i class="bi bi-chevron-right"></i> Fasilitas Kampus</a></li>
                    <li><a href="<?= base_url('website/berita') ?>"><i class="bi bi-chevron-right"></i> Portal Berita Madrasah</a></li>
                    <li><a href="<?= base_url('ppdb/login') ?>"><i class="bi bi-chevron-right"></i> Login Calon Siswa</a></li>
                </ul>
            </div>

            <!-- Kolom 3: Hubungi Kami -->
            <div class="col-lg-3 col-md-6">
                <div class="footer-heading">
                    <i class="bi bi-telephone-fill"></i> Hubungi Kami
                </div>
                <div>
                    <div class="footer-contact-item">
                        <i class="bi bi-geo-alt-fill"></i>
                        <span><?= !empty($profil_website->alamat) ? htmlspecialchars($profil_website->alamat) : 'Kabupaten Banjar, Kalimantan Selatan' ?></span>
                    </div>
                    <div class="footer-contact-item">
                        <i class="bi bi-telephone-fill"></i>
                        <span><?= !empty($profil_website->telepon) ? htmlspecialchars($profil_website->telepon) : '-' ?></span>
                    </div>
                    <div class="footer-contact-item">
                        <i class="bi bi-envelope-fill"></i>
                        <span><?= !empty($profil_website->email) ? htmlspecialchars($profil_website->email) : 'man.tigabjr@gmail.com' ?></span>
                    </div>
                </div>

                <!-- Social Media -->
                <div class="footer-social-box">
                    <?php if(!empty($profil_website->facebook_url)): ?>
                        <a href="<?= htmlspecialchars($profil_website->facebook_url) ?>" target="_blank" class="footer-social-btn" title="Facebook">
                            <i class="bi bi-facebook"></i>
                        </a>
                    <?php endif; ?>
                    <?php if(!empty($profil_website->instagram_url)): ?>
                        <a href="<?= htmlspecialchars($profil_website->instagram_url) ?>" target="_blank" class="footer-social-btn" title="Instagram">
                            <i class="bi bi-instagram"></i>
                        </a>
                    <?php endif; ?>
                    <?php if(!empty($profil_website->youtube_url)): ?>
                        <a href="<?= htmlspecialchars($profil_website->youtube_url) ?>" target="_blank" class="footer-social-btn" title="YouTube">
                            <i class="bi bi-youtube"></i>
                        </a>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Kolom 4: Lokasi Peta (Google Maps) -->
            <div class="col-lg-3 col-md-6">
                <div class="footer-heading">
                    <i class="bi bi-map-fill"></i> Lokasi Kampus
                </div>
                <?php if(!empty($profil_website->maps_embed_url)): ?>
                    <div style="border-radius: 12px; overflow: hidden; height: 170px; border: 1px solid rgba(255,255,255,0.15);">
                        <iframe src="<?= function_exists('web_clean') ? web_clean($profil_website->maps_embed_url) : $profil_website->maps_embed_url ?>" style="width: 100%; height: 100%; border: 0;" allowfullscreen="" loading="lazy"></iframe>
                    </div>
                <?php else: ?>
                    <div style="background: rgba(255,255,255,.06); border-radius: 12px; padding: 20px; text-align: center;">
                        <i class="bi bi-geo-alt text-success" style="font-size: 32px;"></i>
                        <p style="margin: 6px 0 0; font-size: 12px;">MAN 3 Banjar, Martapura</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <div class="footer-bottom">
            <span>&copy; <?= date('Y') ?> <?= $nama_madrasah ?> — <?= $judul_panjang ?>.</span>
            <span>Powered by <strong style="color: #34d399;">LabSys</strong> Madrasah Digital</span>
        </div>
    </div>
</footer>

<!-- ═══ WA FLOATING ═══ -->
<?php if(!empty($wa_number)): ?>
    <a href="https://wa.me/<?= $wa_number ?>?text=Assalamualaikum%20Admin%20PPDB%20<?= urlencode($nama_madrasah) ?>"
       class="floating-wa" target="_blank" title="Chat WhatsApp Panitia PPDB">
        <i class="bi bi-whatsapp"></i>
    </a>
<?php endif; ?>

<!-- ═══ MODAL REGISTRASI AWAL (SINGLE PAGE USER-FRIENDLY) ═══ -->
<div class="modal fade" id="modalDaftarPpdb" tabindex="-1" aria-labelledby="modalDaftarPpdbLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content ppdb-modal-content">

            <form method="post" action="<?= base_url('ppdb/submit') ?>" id="formRegistrasiPpdb">

                <div class="modal-header px-4 pt-3 pb-3 border-0 bg-light">
                    <div>
                        <h5 class="modal-title fw-bold" id="modalDaftarPpdbLabel" style="color: #064e3b; font-size: 19px;">
                            <i class="bi bi-person-plus-fill me-1 text-success"></i> Formulir Pendaftaran Baru <?= $nama ?>
                        </h5>
                        <p class="text-muted small mb-0 mt-1">
                            Isikan data diri Anda dengan benar di bawah ini untuk membuat akun pendaftaran online.
                        </p>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body px-4 py-3">
                    <div class="row g-3">
                        <!-- Identitas Calon Siswa -->
                        <div class="col-12">
                            <div class="p-2 px-3 rounded-3 bg-light border-start border-4 border-success mb-2">
                                <span class="fw-bold small text-success"><i class="bi bi-person-badge me-1"></i> Data Identitas Calon Siswa</span>
                            </div>
                        </div>

                        <div class="col-md-12">
                            <label class="ppdb-form-label fw-bold small text-secondary">Nama Lengkap Siswa <span class="text-danger">*</span></label>
                            <input type="text" name="nama_lengkap" class="form-control ppdb-input" placeholder="Masukkan nama lengkap sesuai ijazah / akta kelahiran" required minlength="3">
                        </div>

                        <div class="col-md-6">
                            <label class="ppdb-form-label fw-bold small text-secondary">NISN (10 Digit Angka) <span class="text-danger">*</span></label>
                            <input type="text" name="nisn" class="form-control ppdb-input" placeholder="Contoh: 0081234567" required maxlength="10" pattern="[0-9]{10}" oninput="this.value=this.value.replace(/[^0-9]/g,'').slice(0,10)">
                            <div class="form-text text-muted" style="font-size: 11.5px;"><i class="bi bi-info-circle me-1"></i>NISN digunakan sebagai Username login Anda.</div>
                        </div>

                        <div class="col-md-6">
                            <label class="ppdb-form-label fw-bold small text-secondary">Jenis Kelamin <span class="text-danger">*</span></label>
                            <select name="jk" class="form-select ppdb-select" required>
                                <option value="">-- Pilih Jenis Kelamin --</option>
                                <option value="L">Laki-laki</option>
                                <option value="P">Perempuan</option>
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label class="ppdb-form-label fw-bold small text-secondary">Tempat Lahir <span class="text-danger">*</span></label>
                            <input type="text" name="tempat_lahir" class="form-control ppdb-input" placeholder="Contoh: Martapura / Banjarmasin" required>
                        </div>

                        <div class="col-md-6">
                            <label class="ppdb-form-label fw-bold small text-secondary">Tanggal Lahir <span class="text-danger">*</span></label>
                            <input type="date" name="tanggal_lahir" class="form-control ppdb-input" required>
                        </div>

                        <div class="col-md-6">
                            <label class="ppdb-form-label fw-bold small text-secondary">Asal Sekolah (SMP / MTs) <span class="text-danger">*</span></label>
                            <input type="text" name="asal_sekolah" class="form-control ppdb-input" placeholder="Contoh: MTsN 1 Banjar / SMPN 2 Martapura" required>
                        </div>

                        <div class="col-md-6">
                            <label class="ppdb-form-label fw-bold small text-secondary">Jalur Pendaftaran <span class="text-danger">*</span></label>
                            <select name="jalur_pendaftaran" class="form-select ppdb-select" required>
                                <option value="Reguler">Jalur Reguler / Umum</option>
                                <option value="Prestasi">Jalur Prestasi (Akademik / Non-Akademik)</option>
                                <option value="Tahfidz">Jalur Tahfidz Al-Qur'an</option>
                                <option value="Afirmasi">Jalur Afirmasi (KIP / PKH / KKS)</option>
                            </select>
                        </div>

                        <!-- Data Kontak & Akun -->
                        <div class="col-12 mt-4">
                            <div class="p-2 px-3 rounded-3 bg-light border-start border-4 border-success mb-2">
                                <span class="fw-bold small text-success"><i class="bi bi-telephone-inbound me-1"></i> Data Kontak, Orang Tua &amp; Keamanan Akun</span>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <label class="ppdb-form-label fw-bold small text-secondary">Nama Orang Tua / Wali <span class="text-danger">*</span></label>
                            <input type="text" name="nama_ortu" class="form-control ppdb-input" placeholder="Nama lengkap ayah/ibu/wali" required>
                        </div>

                        <div class="col-md-6">
                            <label class="ppdb-form-label fw-bold small text-secondary">Nomor WhatsApp / HP Aktif <span class="text-danger">*</span></label>
                            <input type="text" name="no_hp" class="form-control ppdb-input" placeholder="Contoh: 081234567890" required maxlength="15" pattern="[0-9]{10,15}" oninput="this.value=this.value.replace(/[^0-9]/g,'').slice(0,15)">
                            <div class="form-text text-muted" style="font-size: 11.5px;">Untuk menerima notifikasi status verifikasi &amp; jadwal tes.</div>
                        </div>

                        <div class="col-md-6">
                            <label class="ppdb-form-label fw-bold small text-secondary">Alamat Email (Opsional)</label>
                            <input type="email" name="email" class="form-control ppdb-input" placeholder="contoh: siswa@gmail.com">
                            <div class="form-text text-muted" style="font-size: 11.5px;">Boleh dikosongkan jika belum memiliki email.</div>
                        </div>

                        <div class="col-md-6">
                            <label class="ppdb-form-label fw-bold small text-secondary">Buat Password Akun <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <input type="password" id="inputRegPassword" name="password" class="form-control ppdb-input" placeholder="Minimal 6 karakter" required minlength="6">
                                <button class="btn btn-outline-secondary" type="button" onclick="togglePasswordVisibility('inputRegPassword', this)">
                                    <i class="bi bi-eye"></i>
                                </button>
                            </div>
                            <div class="form-text text-muted" style="font-size: 11.5px;">Buat password yang mudah Anda ingat.</div>
                        </div>
                    </div>
                </div>

                <div class="modal-footer px-4 py-3 bg-light border-top d-flex justify-content-between">
                    <button type="button" class="btn btn-light rounded-pill px-4 fw-bold" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-success rounded-pill px-4 py-2 fw-bold shadow-sm" style="background:#059669; border-color:#059669; font-size: 15px;">
                        <i class="bi bi-check-circle-fill me-1"></i> Daftar Sekarang (Buat Akun)
                    </button>
                </div>

            </form>

        </div>
    </div>
</div>

<!-- ═══ FLASHDATA ═══ -->
<?php if($this->session->flashdata('error')): ?>
<script>
document.addEventListener('DOMContentLoaded', function(){
    Swal.fire({ icon:'error', title:'Pendaftaran Gagal', text:'<?= addslashes($this->session->flashdata("error")) ?>', confirmButtonColor:'#059669' });
});
</script>
<?php endif; ?>

<?php if($this->session->flashdata('success')): ?>
<script>
document.addEventListener('DOMContentLoaded', function(){
    Swal.fire({ icon:'success', title:'Pendaftaran Berhasil', text:'<?= addslashes($this->session->flashdata("success")) ?>', confirmButtonColor:'#059669' });
});
</script>
<?php endif; ?>

<!-- ═══ SCRIPTS ═══ -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<script>
function togglePasswordVisibility(inputId, btn) {
    const input = document.getElementById(inputId);
    const icon = btn.querySelector('i');
    if (input.type === 'password') {
        input.type = 'text';
        icon.className = 'bi bi-eye-slash';
    } else {
        input.type = 'password';
        icon.className = 'bi bi-eye';
    }
}

document.addEventListener('DOMContentLoaded', function() {

    /* ─── Navbar scroll effect ─── */
    const nav = document.getElementById('mainNav');
    if(nav) {
        window.addEventListener('scroll', function() {
            nav.classList.toggle('scrolled', window.scrollY > 10);
        });
    }

    /* ─── Smooth scroll for anchors ─── */
    document.querySelectorAll('a[href^="#"]').forEach(function(a) {
        a.addEventListener('click', function(e) {
            const id = this.getAttribute('href');
            if(id === '#') return;
            const el = document.querySelector(id);
            if(el) {
                e.preventDefault();
                const offset = (nav ? nav.offsetHeight : 70) + 16;
                const top = el.getBoundingClientRect().top + window.pageYOffset - offset;
                window.scrollTo({ top: top, behavior: 'smooth' });
            }
        });
    });
});
</script>

</body>
</html>