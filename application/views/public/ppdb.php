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

/* ─── Modal Wizard ─── */
.ppdb-modal-content {
    border: 0;
    border-radius: var(--radius-lg);
    overflow: hidden;
}
.ppdb-modal-body {
    max-height: calc(100vh - 190px);
    overflow-y: auto;
    padding: 28px;
}
.ppdb-modal-footer {
    position: sticky;
    bottom: 0;
    background: #fff;
    border-top: 1px solid var(--c-slate-200);
    z-index: 3;
    padding: 16px 24px;
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
    font-size: 14px;
    transition: all .2s;
}
.ppdb-input:focus, .ppdb-select:focus {
    border-color: #059669;
    box-shadow: 0 0 0 4px rgba(5,150,105,.12);
}

.wizard-step { display: none; }
.wizard-step.active { display: block; animation: fadeIn .3s ease; }
@keyframes fadeIn { from { opacity: 0; transform: translateY(8px); } to { opacity: 1; transform: translateY(0); } }

.wizard-progress {
    display: flex; justify-content: space-between;
    margin-bottom: 24px; position: relative;
}
.wizard-progress::before {
    content: ""; position: absolute;
    top: 50%; left: 0; right: 0;
    height: 3px; background: var(--c-slate-200);
    z-index: 0; transform: translateY(-50%);
    border-radius: 3px;
}
.wizard-progress-bar {
    position: absolute; top: 50%; left: 0;
    height: 3px; background: #059669;
    z-index: 1; transform: translateY(-50%);
    transition: width .4s ease; border-radius: 3px;
    width: 0%;
}
.wizard-dot {
    width: 32px; height: 32px; border-radius: 50%;
    background: #fff; border: 3px solid var(--c-slate-200);
    position: relative; z-index: 2;
    display: flex; align-items: center; justify-content: center;
    font-weight: 800; color: var(--c-slate-500); font-size: 13px;
    transition: all .3s;
}
.wizard-dot.active { border-color: #059669; color: #059669; }
.wizard-dot.completed { background: #059669; border-color: #059669; color: #fff; }

/* Responsive */
@media (max-width: 991px) {
    .nav-links { display: none; }
    .nav-mobile-toggle { 
        display: flex;
        align-items: center;
        justify-content: center;
        width: 38px;
        height: 38px;
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
    .nav-mobile-menu-wrapper {
        display: none;
        position: absolute;
        top: 100%;
        left: 0;
        width: 100%;
        background: #ffffff;
        border-top: 1px solid var(--c-slate-200);
        border-bottom: 2px solid #059669;
        box-shadow: 0 15px 35px rgba(0,0,0,0.12);
        z-index: 1050;
    }
    .nav-mobile-menu-wrapper.show {
        display: block;
        animation: ppdbMenuFade .25s ease forwards;
    }
    @keyframes ppdbMenuFade {
        from { opacity: 0; transform: translateY(-8px); }
        to { opacity: 1; transform: translateY(0); }
    }
    .nav-mobile-menu-inner {
        display: flex;
        flex-direction: column;
        gap: 6px;
        padding: 16px 14px 20px;
    }
    .mobile-nav-link {
        padding: 12px 16px;
        border-radius: var(--radius-sm);
        font-weight: 600;
        font-size: 14px;
        color: var(--c-slate-800);
        text-align: left;
        background: #f8fafc;
        border: 1px solid #f1f5f9;
        width: 100%;
        display: flex;
        align-items: center;
        gap: 12px;
        transition: all .2s;
    }
    .mobile-nav-link:hover { 
        background: var(--c-emerald-50); 
        color: #059669;
        border-color: #a7f3d0;
    }
    .mobile-btn-daftar {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 10px;
        padding: 13px 16px;
        border-radius: var(--radius-sm);
        font-weight: 800;
        font-size: 14px;
        color: #ffffff !important;
        background: linear-gradient(135deg, #059669, #10b981);
        border: none;
        box-shadow: 0 4px 12px rgba(5,150,105,0.25);
        margin-bottom: 4px;
        width: 100%;
    }
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

        <button class="nav-mobile-toggle" id="navToggle" aria-label="Buka Menu Navigasi">
            <i class="bi bi-list" id="navToggleIcon"></i>
        </button>
    </div>

    <!-- Mobile Dropdown Menu dengan Solid Background -->
    <div class="nav-mobile-menu-wrapper" id="navMobileMenu">
        <div class="container">
            <div class="nav-mobile-menu-inner">
                <?php if($is_open): ?>
                    <button type="button" class="mobile-btn-daftar" data-bs-toggle="modal" data-bs-target="#modalDaftarPpdb" onclick="document.getElementById('navMobileMenu').classList.remove('show'); document.getElementById('navToggleIcon').className='bi bi-list';">
                        <i class="bi bi-pencil-square"></i> Form Pendaftaran Baru
                    </button>
                <?php endif; ?>
                <a href="<?= base_url('ppdb/login') ?>" class="mobile-nav-link text-primary fw-bold"><i class="bi bi-box-arrow-in-right fs-5"></i> Login Akun Peserta</a>
                <a href="#informasi" class="mobile-nav-link" onclick="document.getElementById('navMobileMenu').classList.remove('show'); document.getElementById('navToggleIcon').className='bi bi-list';"><i class="bi bi-info-circle fs-5 text-success"></i> Informasi Pendaftaran</a>
                <a href="#alur" class="mobile-nav-link" onclick="document.getElementById('navMobileMenu').classList.remove('show'); document.getElementById('navToggleIcon').className='bi bi-list';"><i class="bi bi-diagram-3 fs-5 text-success"></i> Alur Pendaftaran</a>
                <a href="#persyaratan" class="mobile-nav-link" onclick="document.getElementById('navMobileMenu').classList.remove('show'); document.getElementById('navToggleIcon').className='bi bi-list';"><i class="bi bi-card-checklist fs-5 text-success"></i> Persyaratan Berkas</a>
                <a href="#faq" class="mobile-nav-link" onclick="document.getElementById('navMobileMenu').classList.remove('show'); document.getElementById('navToggleIcon').className='bi bi-list';"><i class="bi bi-question-circle fs-5 text-success"></i> FAQ / Tanya Jawab</a>
                <a href="<?= base_url() ?>" class="mobile-nav-link text-secondary"><i class="bi bi-arrow-left fs-5"></i> Kembali ke Website Utama</a>
            </div>
        </div>
    </div>
</nav>

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
            <h2>4 Langkah Mudah Menjadi Santri / Siswa</h2>
            <p>Ikuti tahapan alur penerimaan peserta didik baru berikut ini dengan cermat.</p>
        </div>

        <div class="timeline">
            <div class="timeline-item">
                <div class="timeline-dot">1</div>
                <h5>1. Buat Akun Registrasi Awal</h5>
                <p>Klik tombol <strong>"Daftar Sekarang"</strong> pada halaman ini, lalu isikan data pokok: Nama Lengkap, NISN (10 Digit), Tempat/Tanggal Lahir, Asal Sekolah, No. HP/WhatsApp, dan Buat Password Akun.</p>
            </div>

            <div class="timeline-item">
                <div class="timeline-dot">2</div>
                <h5>2. Login ke Dashboard Peserta</h5>
                <p>Gunakan <strong>NISN</strong> dan <strong>Password</strong> yang telah Anda daftarkan untuk masuk ke portal akun peserta PPDB Anda.</p>
            </div>

            <div class="timeline-item">
                <div class="timeline-dot">3</div>
                <h5>3. Lengkapi Biodata & Unggah Berkas</h5>
                <p>Isi formulir biodata lengkap (data orang tua, alamat tempat tinggal, nilai) dan unggah foto/scan berkas wajib seperti Kartu Keluarga, Akta Kelahiran, dan Ijazah/SKL.</p>
            </div>

            <div class="timeline-item">
                <div class="timeline-dot">4</div>
                <h5>4. Verifikasi Panitia & Cetak Kartu</h5>
                <p>Panitia akan memverifikasi kelengkapan dokumen Anda. Setelah dinyatakan lengkap dan valid, Anda dapat langsung mengunduh dan mencetak <strong>Kartu Pendaftaran Resmi</strong>.</p>
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

<!-- ═══ PERSYARATAN BERKAS ═══ -->
<section class="section <?= empty($settings->pamflet_ppdb) ? 'section-alt' : '' ?>" id="persyaratan">
    <div class="container">
        <div class="section-head">
            <div class="section-tag"><i class="bi bi-clipboard-check"></i> Persyaratan Berkas</div>
            <h2>Dokumen yang Perlu Disiapkan</h2>
            <p>Pastikan Anda telah menyiapkan dokumen-dokumen berikut sebelum mengisi biodata lengkap di dashboard.</p>
        </div>

        <div class="req-grid">
            <?php foreach($syarat_list as $syarat): ?>
            <div class="req-item">
                <div class="req-check"><i class="bi bi-check-lg"></i></div>
                <span><?= htmlspecialchars($syarat) ?></span>
            </div>
            <?php endforeach; ?>
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

<!-- ═══ MODAL REGISTRASI AWAL ═══ -->
<div class="modal fade" id="modalDaftarPpdb" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content ppdb-modal-content">

            <form method="post" action="<?= base_url('ppdb/submit') ?>">

                <div class="modal-header">
                    <div>
                        <h5 class="modal-title fw-bold" style="color: #064e3b;">
                            Registrasi Awal <?= $nama ?>
                        </h5>
                        <small class="text-muted fw-bold">
                            Buat akun pendaftaran Anda terlebih dahulu (3 Langkah).
                        </small>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body ppdb-modal-body" id="wizardBody">

                    <div class="wizard-progress px-3 mt-2">
                        <div class="wizard-progress-bar" id="wizardProgress" style="width: 0%;"></div>
                        <div class="wizard-dot active" data-step="1">1</div>
                        <div class="wizard-dot" data-step="2">2</div>
                        <div class="wizard-dot" data-step="3">3</div>
                    </div>

                    <!-- STEP 1 -->
                    <div class="wizard-step active" id="step1">
                        <h6 class="fw-bold mb-3" style="color: #059669;"><i class="bi bi-person-badge me-1"></i> Langkah 1: Identitas Utama</h6>
                        <div class="row g-3">
                            <div class="col-md-12">
                                <label class="ppdb-form-label">Nama Lengkap Calon Siswa</label>
                                <input type="text" id="w_nama" name="nama_lengkap" class="form-control ppdb-input" placeholder="Masukkan nama lengkap sesuai ijazah/akta" required minlength="3">
                            </div>
                            <div class="col-md-6">
                                <label class="ppdb-form-label">NISN (10 Digit)</label>
                                <input type="text" id="w_nisn" name="nisn" class="form-control ppdb-input" placeholder="Contoh: 0081234567" required maxlength="10" pattern="[0-9]{10}" oninput="this.value=this.value.replace(/[^0-9]/g,'').slice(0,10)">
                                <div class="form-text">NISN akan digunakan sebagai Username login.</div>
                            </div>
                            <div class="col-md-6">
                                <label class="ppdb-form-label">Jenis Kelamin</label>
                                <select id="w_jk" name="jk" class="form-select ppdb-select" required>
                                    <option value="">-- Pilih Jenis Kelamin --</option>
                                    <option value="L">Laki-laki</option>
                                    <option value="P">Perempuan</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <!-- STEP 2 -->
                    <div class="wizard-step" id="step2">
                        <h6 class="fw-bold mb-3" style="color: #059669;"><i class="bi bi-calendar-event me-1"></i> Langkah 2: Kelahiran & Asal Sekolah</h6>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="ppdb-form-label">Tempat Lahir</label>
                                <input type="text" id="w_tempat" name="tempat_lahir" class="form-control ppdb-input" placeholder="Contoh: Banjarmasin" required>
                            </div>
                            <div class="col-md-6">
                                <label class="ppdb-form-label">Tanggal Lahir</label>
                                <input type="date" id="w_tanggal" name="tanggal_lahir" class="form-control ppdb-input" required>
                            </div>
                            <div class="col-md-12">
                                <label class="ppdb-form-label">Asal Sekolah (SMP / MTs)</label>
                                <input type="text" id="w_sekolah" name="asal_sekolah" class="form-control ppdb-input" placeholder="Contoh: MTsN 1 Banjar / SMPN 2 Martapura" required>
                            </div>
                        </div>
                    </div>

                    <!-- STEP 3 -->
                    <div class="wizard-step" id="step3">
                        <h6 class="fw-bold mb-3" style="color: #059669;"><i class="bi bi-shield-lock me-1"></i> Langkah 3: Kontak & Password</h6>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="ppdb-form-label">Nama Ayah / Ibu / Wali</label>
                                <input type="text" id="w_ortu" name="nama_ortu" class="form-control ppdb-input" placeholder="Nama lengkap orang tua/wali" required>
                            </div>
                            <div class="col-md-6">
                                <label class="ppdb-form-label">Nomor WhatsApp / HP Aktif</label>
                                <input type="text" id="w_hp" name="no_hp" class="form-control ppdb-input" placeholder="08xxxxxxxxxx" required maxlength="15" pattern="[0-9]{10,15}" oninput="this.value=this.value.replace(/[^0-9]/g,'').slice(0,15)">
                            </div>
                            <div class="col-md-12">
                                <label class="ppdb-form-label">Password Akun Pendaftaran</label>
                                <input type="password" id="w_pass" name="password" class="form-control ppdb-input" placeholder="Minimal 6 karakter" required minlength="6">
                                <div class="form-text">Gunakan password yang mudah diingat untuk login ke dashboard pendaftar.</div>
                            </div>
                        </div>
                    </div>

                </div>

                <div class="modal-footer ppdb-modal-footer d-flex justify-content-between">
                    <button type="button" class="btn btn-light rounded-pill px-4 fw-bold" id="btnPrev" style="display:none;">
                        ← Kembali
                    </button>
                    <div>
                        <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal" id="btnCancel">Batal</button>
                        <button type="button" class="btn btn-primary rounded-pill px-4 fw-bold" id="btnNext" style="background:#059669; border-color:#059669;">Lanjut →</button>
                        <button type="submit" class="btn btn-success rounded-pill px-4 fw-bold" id="btnSubmit" style="display:none; background:#16a34a; border-color:#16a34a;">Buat Akun Sekarang</button>
                    </div>
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
document.addEventListener('DOMContentLoaded', function() {

    /* ─── Navbar scroll effect ─── */
    const nav = document.getElementById('mainNav');
    if(nav) {
        window.addEventListener('scroll', function() {
            nav.classList.toggle('scrolled', window.scrollY > 10);
        });
    }

    /* ─── Mobile nav toggle ─── */
    const toggle = document.getElementById('navToggle');
    const mobileMenu = document.getElementById('navMobileMenu');
    const icon = document.getElementById('navToggleIcon');
    if(toggle && mobileMenu) {
        toggle.addEventListener('click', function(e) {
            e.stopPropagation();
            const isOpen = mobileMenu.classList.toggle('show');
            if(icon) {
                icon.className = isOpen ? 'bi bi-x-lg' : 'bi bi-list';
            }
        });
        document.addEventListener('click', function(e) {
            if(!mobileMenu.contains(e.target) && !toggle.contains(e.target)) {
                mobileMenu.classList.remove('show');
                if(icon) icon.className = 'bi bi-list';
            }
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

    /* ─── Wizard ─── */
    let currentStep = 1;
    const totalSteps = 3;
    const btnNext = document.getElementById('btnNext');
    const btnPrev = document.getElementById('btnPrev');
    const btnSubmit = document.getElementById('btnSubmit');
    const btnCancel = document.getElementById('btnCancel');
    const progressBar = document.getElementById('wizardProgress');
    const dots = document.querySelectorAll('.wizard-dot');

    function updateWizard() {
        document.querySelectorAll('.wizard-step').forEach(function(el, i) {
            el.classList.toggle('active', i + 1 === currentStep);
        });
        dots.forEach(function(dot, i) {
            const s = i + 1;
            if(s < currentStep) { dot.classList.add('completed'); dot.classList.remove('active'); dot.innerHTML = '✓'; }
            else if(s === currentStep) { dot.classList.add('active'); dot.classList.remove('completed'); dot.innerHTML = s; }
            else { dot.classList.remove('active','completed'); dot.innerHTML = s; }
        });
        if(progressBar) {
            progressBar.style.width = ((currentStep - 1) / (totalSteps - 1)) * 100 + '%';
        }
        if(btnPrev) btnPrev.style.display = currentStep > 1 ? 'inline-block' : 'none';
        if(btnCancel) btnCancel.style.display = currentStep === 1 ? 'inline-block' : 'none';
        if(currentStep === totalSteps) { 
            if(btnNext) btnNext.style.display = 'none'; 
            if(btnSubmit) btnSubmit.style.display = 'inline-block'; 
        } else { 
            if(btnNext) btnNext.style.display = 'inline-block'; 
            if(btnSubmit) btnSubmit.style.display = 'none'; 
        }
    }

    function validateStep(step) {
        let valid = true, msg = '';
        if(step === 1) {
            const n = document.getElementById('w_nama') ? document.getElementById('w_nama').value.trim() : '';
            const ni = document.getElementById('w_nisn') ? document.getElementById('w_nisn').value.trim() : '';
            const jk = document.getElementById('w_jk') ? document.getElementById('w_jk').value : '';
            if(n.length < 3) { valid = false; msg = 'Nama lengkap minimal 3 karakter'; }
            else if(ni.length !== 10) { valid = false; msg = 'NISN harus 10 digit angka'; }
            else if(jk === '') { valid = false; msg = 'Pilih jenis kelamin'; }
        } else if(step === 2) {
            const t = document.getElementById('w_tempat') ? document.getElementById('w_tempat').value.trim() : '';
            const d = document.getElementById('w_tanggal') ? document.getElementById('w_tanggal').value : '';
            const s = document.getElementById('w_sekolah') ? document.getElementById('w_sekolah').value.trim() : '';
            if(!t || !d || !s) { valid = false; msg = 'Mohon lengkapi semua data pada langkah ini'; }
        }
        if(!valid && msg) {
            Swal.fire({ icon:'warning', title:'Data Belum Lengkap', text: msg, confirmButtonColor:'#059669' });
        }
        return valid;
    }

    if(btnNext) { 
        btnNext.addEventListener('click', function() { 
            if(validateStep(currentStep)) { 
                currentStep++; 
                updateWizard(); 
            } 
        }); 
    }
    if(btnPrev) { 
        btnPrev.addEventListener('click', function() { 
            currentStep--; 
            updateWizard(); 
        }); 
    }

    const modalEl = document.getElementById('modalDaftarPpdb');
    if(modalEl) { 
        modalEl.addEventListener('show.bs.modal', function() { 
            currentStep = 1; 
            updateWizard(); 
        }); 
    }
});
</script>

</body>
</html>