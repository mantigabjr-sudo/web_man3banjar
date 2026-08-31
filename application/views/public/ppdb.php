<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">

<title><?= htmlspecialchars($nama_ppdb ?? 'PPDB') ?> MAN 3 Banjar — Pendaftaran Peserta Didik Baru</title>
<meta name="description" content="Halaman pendaftaran peserta didik baru (<?= htmlspecialchars($nama_ppdb ?? 'PPDB') ?>) MAN 3 Banjar. Daftar online, lengkapi data, dan pantau status pendaftaran Anda.">

<link rel="icon" type="image/png" href="<?= base_url('assets/img/favicon.png') ?>">
<link rel="shortcut icon" type="image/png" href="<?= base_url('assets/img/favicon.png') ?>">
<link rel="apple-touch-icon" href="<?= base_url('assets/img/favicon.png') ?>">

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<?php
/* ─── Data Logic ─── */
$nama    = htmlspecialchars($nama_ppdb ?? 'PPDB');
$ta      = isset($settings->tahun_ajaran) ? htmlspecialchars($settings->tahun_ajaran) : '';
$tgl_mulai   = $settings->tanggal_mulai ?? '';
$tgl_selesai = $settings->tanggal_selesai ?? '';
$today       = date('Y-m-d');

$is_open        = true;
$countdown_date = '';
$status_label   = 'Pendaftaran Dibuka';
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
    "Kartu Keluarga atau dokumen keluarga yang berlaku.",
    "Akta kelahiran calon peserta didik.",
    "NISN aktif dari sekolah asal.",
    "Ijazah/SKL atau surat keterangan dari sekolah asal.",
    "Nomor HP aktif untuk informasi pendaftaran."
];
$syarat_list = $persyaratan_default;
if(!empty($settings->persyaratan_ppdb)) {
    $syarat_list = array_filter(array_map('trim', explode("\n", $settings->persyaratan_ppdb)));
}
?>

<style>
/* ═══════════════════════════════════════════════════
   DESIGN SYSTEM — PPDB MAN 3 BANJAR
   ═══════════════════════════════════════════════════ */
:root {
    --c-emerald-950: #022c22;
    --c-emerald-900: #064e3b;
    --c-emerald-800: #065f46;
    --c-emerald-700: #047857;
    --c-emerald-600: #059669;
    --c-emerald-500: #10b981;
    --c-emerald-400: #34d399;
    --c-emerald-300: #6ee7b7;
    --c-emerald-200: #a7f3d0;
    --c-emerald-100: #d1fae5;
    --c-emerald-50:  #ecfdf5;

    --c-slate-900: #0f172a;
    --c-slate-700: #334155;
    --c-slate-500: #64748b;
    --c-slate-300: #cbd5e1;
    --c-slate-200: #e2e8f0;
    --c-slate-100: #f1f5f9;
    --c-slate-50:  #f8fafc;

    --radius-sm: 12px;
    --radius-md: 18px;
    --radius-lg: 24px;
    --radius-xl: 32px;
    --radius-full: 999px;

    --shadow-sm: 0 1px 3px rgba(0,0,0,.06), 0 1px 2px rgba(0,0,0,.04);
    --shadow-md: 0 4px 16px rgba(0,0,0,.06);
    --shadow-lg: 0 12px 40px rgba(0,0,0,.08);
    --shadow-xl: 0 20px 60px rgba(0,0,0,.1);
    --shadow-green: 0 12px 40px rgba(16,185,129,.18);

    --font: 'Inter', system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif;
}

* { box-sizing: border-box; margin: 0; }

body {
    font-family: var(--font);
    color: var(--c-slate-900);
    background: var(--c-slate-50);
    -webkit-font-smoothing: antialiased;
}

a { text-decoration: none; color: inherit; }

/* ─── Navbar ─── */
.ppdb-nav {
    position: sticky;
    top: 0;
    z-index: 100;
    background: rgba(255,255,255,.85);
    backdrop-filter: blur(20px) saturate(180%);
    -webkit-backdrop-filter: blur(20px) saturate(180%);
    border-bottom: 1px solid var(--c-slate-200);
    transition: box-shadow .3s;
}
.ppdb-nav.scrolled {
    box-shadow: var(--shadow-md);
}
.ppdb-nav .container {
    display: flex;
    align-items: center;
    justify-content: space-between;
    height: 64px;
    gap: 16px;
}
.nav-brand {
    display: flex;
    align-items: center;
    gap: 10px;
    font-weight: 800;
    color: var(--c-emerald-900);
    font-size: 15px;
    white-space: nowrap;
}
.nav-brand-logo {
    width: 40px; height: 40px;
    background: transparent;
    color: var(--c-emerald-900);
    display: flex; align-items: center; justify-content: center;
    font-weight: 900; font-size: 16px;
    flex-shrink: 0;
}
.nav-brand-logo img { width: 100%; height: 100%; object-fit: contain; }
.nav-links {
    display: flex;
    align-items: center;
    gap: 6px;
}
.nav-links a {
    padding: 6px 14px;
    border-radius: var(--radius-full);
    font-weight: 600;
    font-size: 13px;
    color: var(--c-slate-500);
    transition: all .2s;
}
.nav-links a:hover {
    color: var(--c-emerald-700);
    background: var(--c-emerald-50);
}
.nav-cta {
    display: flex;
    align-items: center;
    gap: 8px;
}
.btn-nav-login {
    padding: 8px 18px;
    border-radius: var(--radius-full);
    font-weight: 700;
    font-size: 13px;
    color: var(--c-emerald-700);
    background: var(--c-emerald-50);
    border: 1px solid var(--c-emerald-200);
    transition: all .2s;
}
.btn-nav-login:hover { background: var(--c-emerald-100); color: var(--c-emerald-800); }
.btn-nav-daftar {
    padding: 8px 18px;
    border-radius: var(--radius-full);
    font-weight: 700;
    font-size: 13px;
    color: #fff;
    background: linear-gradient(135deg, var(--c-emerald-700), var(--c-emerald-500));
    border: none;
    box-shadow: var(--shadow-green);
    transition: all .2s;
    cursor: pointer;
}
.btn-nav-daftar:hover { transform: translateY(-1px); box-shadow: 0 16px 48px rgba(16,185,129,.25); }
.btn-nav-daftar:disabled, .btn-nav-daftar.disabled {
    opacity: .6; cursor: not-allowed; transform: none;
    box-shadow: none;
}

/* Mobile nav toggle */
.nav-mobile-toggle {
    display: none;
    background: none; border: none;
    font-size: 22px; color: var(--c-slate-700);
    cursor: pointer; padding: 4px;
}

/* ─── Hero ─── */
.hero {
    padding: 80px 0 60px;
    text-align: center;
    position: relative;
    overflow: hidden;
    background:
        radial-gradient(ellipse at 20% 0%, rgba(16,185,129,.12) 0%, transparent 50%),
        radial-gradient(ellipse at 80% 0%, rgba(14,165,233,.08) 0%, transparent 50%),
        linear-gradient(180deg, #fff 0%, var(--c-slate-50) 100%);
}
.hero::after {
    content: "";
    position: absolute;
    bottom: 0; left: 0; right: 0;
    height: 1px;
    background: linear-gradient(90deg, transparent, var(--c-emerald-200), transparent);
}
.hero-status {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 8px 18px;
    border-radius: var(--radius-full);
    font-weight: 700;
    font-size: 13px;
    margin-bottom: 20px;
    letter-spacing: .3px;
}
.hero h1 {
    font-size: clamp(32px, 5vw, 56px);
    font-weight: 900;
    line-height: 1.08;
    letter-spacing: -1.5px;
    color: var(--c-emerald-950);
    margin-bottom: 16px;
    max-width: 700px;
    margin-left: auto;
    margin-right: auto;
}
.hero-sub {
    font-size: 17px;
    font-weight: 500;
    color: var(--c-slate-500);
    line-height: 1.7;
    max-width: 560px;
    margin: 0 auto 24px;
}
.hero-info-bar {
    display: inline-flex;
    align-items: center;
    gap: 20px;
    background: #fff;
    border: 1px solid var(--c-slate-200);
    border-radius: var(--radius-full);
    padding: 10px 24px;
    margin-bottom: 28px;
    box-shadow: var(--shadow-sm);
    flex-wrap: wrap;
    justify-content: center;
}
.hero-info-item {
    display: flex;
    align-items: center;
    gap: 8px;
    font-size: 13px;
    font-weight: 600;
    color: var(--c-slate-700);
    white-space: nowrap;
}
.hero-info-item i {
    color: var(--c-emerald-500);
    font-size: 16px;
}
.hero-info-divider {
    width: 1px;
    height: 20px;
    background: var(--c-slate-200);
}
.hero-actions {
    display: flex;
    gap: 12px;
    justify-content: center;
    flex-wrap: wrap;
    margin-bottom: 10px;
}
.btn-hero-main {
    min-height: 52px;
    padding: 0 32px;
    border-radius: var(--radius-md);
    font-weight: 800;
    font-size: 15px;
    color: #fff;
    background: linear-gradient(135deg, var(--c-emerald-800), var(--c-emerald-500));
    border: none;
    box-shadow: var(--shadow-green);
    display: inline-flex;
    align-items: center;
    gap: 8px;
    cursor: pointer;
    transition: all .25s;
}
.btn-hero-main:hover { transform: translateY(-2px); box-shadow: 0 18px 50px rgba(16,185,129,.28); color: #fff; }
.btn-hero-main:disabled, .btn-hero-main.disabled {
    opacity: .6; cursor: not-allowed; transform: none; box-shadow: none;
}
.btn-hero-soft {
    min-height: 52px;
    padding: 0 28px;
    border-radius: var(--radius-md);
    font-weight: 700;
    font-size: 15px;
    color: var(--c-emerald-700);
    background: #fff;
    border: 1.5px solid var(--c-emerald-200);
    display: inline-flex;
    align-items: center;
    gap: 8px;
    transition: all .2s;
}
.btn-hero-soft:hover { background: var(--c-emerald-50); color: var(--c-emerald-800); }

/* ─── Countdown ─── */
.countdown-wrap {
    margin-top: 32px;
    display: inline-flex;
    flex-direction: column;
    align-items: center;
}
.countdown-label {
    font-size: 12px;
    font-weight: 700;
    color: var(--c-emerald-700);
    text-transform: uppercase;
    letter-spacing: 1.5px;
    margin-bottom: 12px;
}
.countdown-grid {
    display: flex;
    gap: 10px;
}
.countdown-cell {
    background: #fff;
    border: 1.5px solid var(--c-emerald-200);
    border-radius: var(--radius-md);
    min-width: 72px;
    padding: 14px 8px;
    text-align: center;
    box-shadow: var(--shadow-sm);
}
.countdown-cell .num {
    font-size: 28px;
    font-weight: 900;
    color: var(--c-emerald-800);
    line-height: 1;
}
.countdown-cell .unit {
    font-size: 10px;
    font-weight: 700;
    color: var(--c-slate-500);
    text-transform: uppercase;
    letter-spacing: 1px;
    margin-top: 4px;
}

/* ─── Sections Generic ─── */
.section {
    padding: 72px 0;
}
.section-alt {
    background: #fff;
}
.section-head {
    text-align: center;
    max-width: 640px;
    margin: 0 auto 40px;
}
.section-tag {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 6px 14px;
    border-radius: var(--radius-full);
    background: var(--c-emerald-50);
    color: var(--c-emerald-700);
    font-size: 12px;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: .8px;
    margin-bottom: 14px;
    border: 1px solid var(--c-emerald-100);
}
.section-head h2 {
    font-size: 32px;
    font-weight: 900;
    color: var(--c-slate-900);
    line-height: 1.2;
    margin-bottom: 12px;
    letter-spacing: -.5px;
}
.section-head p {
    font-size: 15px;
    color: var(--c-slate-500);
    font-weight: 500;
    line-height: 1.7;
}

/* ─── Info Cards (3 columns) ─── */
.info-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 20px;
}
.info-card {
    background: #fff;
    border: 1px solid var(--c-slate-200);
    border-radius: var(--radius-lg);
    padding: 28px 24px;
    box-shadow: var(--shadow-sm);
    transition: all .3s;
    position: relative;
    overflow: hidden;
}
.info-card::before {
    content: "";
    position: absolute;
    top: 0; left: 0; right: 0;
    height: 3px;
    background: linear-gradient(90deg, var(--c-emerald-400), var(--c-emerald-600));
    opacity: 0;
    transition: opacity .3s;
}
.info-card:hover {
    transform: translateY(-4px);
    box-shadow: var(--shadow-lg);
}
.info-card:hover::before { opacity: 1; }
.info-card-icon {
    width: 48px; height: 48px;
    border-radius: var(--radius-sm);
    background: var(--c-emerald-50);
    color: var(--c-emerald-600);
    display: flex; align-items: center; justify-content: center;
    font-size: 22px;
    margin-bottom: 16px;
}
.info-card h5 {
    font-weight: 800;
    font-size: 16px;
    color: var(--c-slate-900);
    margin-bottom: 8px;
}
.info-card p {
    font-size: 14px;
    color: var(--c-slate-500);
    font-weight: 500;
    line-height: 1.6;
    margin: 0;
}

/* ─── Timeline ─── */
.timeline {
    max-width: 640px;
    margin: 0 auto;
    position: relative;
    padding-left: 48px;
}
.timeline::before {
    content: "";
    position: absolute;
    left: 19px;
    top: 8px;
    bottom: 8px;
    width: 2px;
    background: linear-gradient(180deg, var(--c-emerald-300), var(--c-emerald-100));
    border-radius: 2px;
}
.timeline-item {
    position: relative;
    padding-bottom: 36px;
}
.timeline-item:last-child { padding-bottom: 0; }
.timeline-dot {
    position: absolute;
    left: -48px;
    top: 2px;
    width: 38px; height: 38px;
    border-radius: 50%;
    background: linear-gradient(135deg, var(--c-emerald-500), var(--c-emerald-400));
    color: #fff;
    display: flex; align-items: center; justify-content: center;
    font-weight: 900;
    font-size: 14px;
    box-shadow: 0 4px 16px rgba(16,185,129,.25);
    z-index: 2;
}
.timeline-item h5 {
    font-weight: 800;
    font-size: 17px;
    color: var(--c-slate-900);
    margin-bottom: 6px;
}
.timeline-item p {
    font-size: 14px;
    color: var(--c-slate-500);
    font-weight: 500;
    line-height: 1.65;
    margin: 0;
}

/* ─── Pamflet ─── */
.pamflet-frame {
    display: inline-block;
    padding: 6px;
    border-radius: var(--radius-xl);
    background: linear-gradient(135deg, var(--c-emerald-400), #0ea5e9);
    box-shadow: var(--shadow-xl);
}
.pamflet-frame img {
    display: block;
    max-height: 700px;
    width: auto;
    max-width: 100%;
    object-fit: contain;
    border-radius: calc(var(--radius-xl) - 4px);
    background: #fff;
}

/* ─── Requirements ─── */
.req-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 14px;
}
.req-item {
    display: flex;
    gap: 12px;
    align-items: flex-start;
    padding: 16px 18px;
    background: #fff;
    border: 1px solid var(--c-slate-200);
    border-radius: var(--radius-md);
    transition: all .2s;
}
.req-item:hover {
    border-color: var(--c-emerald-300);
    box-shadow: var(--shadow-sm);
}
.req-check {
    width: 28px; height: 28px;
    border-radius: 8px;
    background: var(--c-emerald-50);
    color: var(--c-emerald-600);
    display: flex; align-items: center; justify-content: center;
    font-size: 14px;
    flex-shrink: 0;
    font-weight: 700;
}
.req-item span {
    font-size: 14px;
    font-weight: 600;
    color: var(--c-slate-700);
    line-height: 1.5;
    padding-top: 3px;
}

/* ─── FAQ Accordion ─── */
.faq-wrap {
    max-width: 720px;
    margin: 0 auto;
}
.faq-wrap .accordion-item {
    border: 1px solid var(--c-slate-200);
    border-radius: var(--radius-md) !important;
    margin-bottom: 10px;
    overflow: hidden;
}
.faq-wrap .accordion-button {
    font-weight: 700;
    font-size: 15px;
    color: var(--c-slate-900);
    padding: 18px 22px;
    background: #fff;
    box-shadow: none !important;
}
.faq-wrap .accordion-button:not(.collapsed) {
    background: var(--c-emerald-50);
    color: var(--c-emerald-800);
}
.faq-wrap .accordion-button::after {
    background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 16' fill='%23064e3b'%3e%3cpath fill-rule='evenodd' d='M1.646 4.646a.5.5 0 0 1 .708 0L8 10.293l5.646-5.647a.5.5 0 0 1 .708.708l-6 6a.5.5 0 0 1-.708 0l-6-6a.5.5 0 0 1 0-.708z'/%3e%3c/svg%3e");
}
.faq-wrap .accordion-body {
    font-size: 14px;
    color: var(--c-slate-500);
    font-weight: 500;
    line-height: 1.7;
    padding: 4px 22px 18px;
}

/* ─── CTA Banner ─── */
.cta-banner {
    border-radius: var(--radius-xl);
    background:
        radial-gradient(ellipse at top right, rgba(250,204,21,.2) 0%, transparent 40%),
        linear-gradient(135deg, var(--c-emerald-950), var(--c-emerald-700));
    color: #fff;
    padding: 48px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 24px;
    flex-wrap: wrap;
    box-shadow: var(--shadow-xl);
    position: relative;
    overflow: hidden;
}
.cta-banner::after {
    content: "";
    position: absolute;
    right: -60px; bottom: -60px;
    width: 200px; height: 200px;
    border-radius: 50%;
    background: rgba(255,255,255,.06);
}
.cta-banner h3 {
    font-weight: 900;
    font-size: 24px;
    margin-bottom: 8px;
}
.cta-banner p {
    color: rgba(255,255,255,.75);
    font-weight: 500;
    margin: 0;
    font-size: 15px;
}
.btn-cta-white {
    min-height: 48px;
    padding: 0 28px;
    border-radius: var(--radius-md);
    font-weight: 800;
    font-size: 14px;
    color: var(--c-emerald-800);
    background: #fff;
    border: none;
    display: inline-flex;
    align-items: center;
    gap: 8px;
    cursor: pointer;
    transition: all .2s;
    box-shadow: var(--shadow-md);
    position: relative;
    z-index: 2;
    flex-shrink: 0;
}
.btn-cta-white:hover { transform: translateY(-2px); box-shadow: var(--shadow-lg); color: var(--c-emerald-900); }
.btn-cta-white:disabled { opacity: .6; cursor: not-allowed; transform: none; }

/* ─── Footer ─── */
.ppdb-footer {
    background: var(--c-emerald-950);
    color: rgba(255,255,255,.6);
    padding: 40px 0 28px;
    font-size: 13px;
    font-weight: 500;
}
.ppdb-footer a {
    color: rgba(255,255,255,.6);
    transition: color .2s;
}
.ppdb-footer a:hover { color: #fff; }
.footer-grid {
    display: grid;
    grid-template-columns: 2fr 1fr 1fr;
    gap: 32px;
    margin-bottom: 32px;
}
.footer-brand {
    font-weight: 800;
    font-size: 16px;
    color: #fff;
    margin-bottom: 10px;
}
.footer-links { list-style: none; padding: 0; margin: 0; }
.footer-links li { margin-bottom: 8px; }
.footer-links a:hover { color: var(--c-emerald-300); }
.footer-divider {
    border: none;
    border-top: 1px solid rgba(255,255,255,.1);
    margin-bottom: 20px;
}

/* ─── Modal ─── */
.ppdb-modal-content {
    border: 0;
    border-radius: var(--radius-lg);
    overflow: hidden;
}
.ppdb-modal-body {
    max-height: calc(100vh - 190px);
    overflow-y: auto;
    padding: 24px;
}
.ppdb-modal-footer {
    position: sticky;
    bottom: 0;
    background: #fff;
    border-top: 1px solid var(--c-slate-200);
    z-index: 3;
}
.ppdb-form-label {
    font-weight: 700;
    color: var(--c-emerald-700);
    font-size: 13px;
    margin-bottom: 6px;
}
.ppdb-input, .ppdb-select {
    min-height: 50px;
    border-radius: var(--radius-sm);
    border: 1.5px solid var(--c-emerald-100);
    padding: 10px 14px;
    font-weight: 600;
    font-size: 14px;
    transition: all .2s;
}
.ppdb-input:focus, .ppdb-select:focus {
    border-color: var(--c-emerald-500);
    box-shadow: 0 0 0 4px rgba(16,185,129,.1);
}
.form-text {
    font-size: 12px;
    color: var(--c-slate-500);
    font-weight: 500;
    margin-top: 4px;
}

/* ─── Wizard ─── */
.wizard-step { display: none; animation: fadeIn .35s ease; }
.wizard-step.active { display: block; }
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
    height: 3px; background: var(--c-emerald-500);
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
.wizard-dot.active { border-color: var(--c-emerald-500); color: var(--c-emerald-500); }
.wizard-dot.completed { background: var(--c-emerald-500); border-color: var(--c-emerald-500); color: #fff; }

/* ─── Animations ─── */
@keyframes fadeIn {
    from { opacity: 0; transform: translateY(10px); }
    to { opacity: 1; transform: translateY(0); }
}
.reveal {
    opacity: 0;
    transform: translateY(24px);
    transition: opacity .6s ease, transform .6s ease;
}
.reveal.visible {
    opacity: 1;
    transform: translateY(0);
}

/* ─── Responsive ─── */
@media (max-width: 991px) {
    .nav-links { display: none; }
    .nav-mobile-toggle { display: block; }
    .info-grid { grid-template-columns: 1fr; }
    .footer-grid { grid-template-columns: 1fr; gap: 24px; }
    .cta-banner { padding: 32px 24px; }
}
@media (max-width: 768px) {
    .hero { padding: 52px 0 40px; }
    .hero h1 { letter-spacing: -.8px; }
    .hero-info-bar { padding: 8px 16px; gap: 10px; }
    .hero-info-divider { display: none; }
    .hero-actions { flex-direction: column; align-items: stretch; }
    .btn-hero-main, .btn-hero-soft { justify-content: center; }
    .countdown-grid { gap: 6px; }
    .countdown-cell { min-width: 62px; padding: 10px 6px; }
    .countdown-cell .num { font-size: 22px; }
    .req-grid { grid-template-columns: 1fr; }
    .timeline { padding-left: 42px; }
    .timeline::before { left: 15px; }
    .timeline-dot { left: -42px; width: 32px; height: 32px; font-size: 12px; }
    .cta-banner { text-align: center; justify-content: center; }
    .ppdb-modal-footer .btn { width: 100%; }
}

/* Mobile nav drawer */
.nav-mobile-menu {
    display: none;
    flex-direction: column;
    gap: 4px;
    padding: 12px 0;
    border-top: 1px solid var(--c-slate-200);
}
.nav-mobile-menu.show { display: flex; }
.nav-mobile-menu a {
    padding: 10px 16px;
    border-radius: var(--radius-sm);
    font-weight: 600;
    font-size: 14px;
    color: var(--c-slate-700);
}
.nav-mobile-menu a:hover { background: var(--c-emerald-50); color: var(--c-emerald-700); }
</style>
</head>

<body>

<!-- ═══ NAVBAR ═══ -->
<nav class="ppdb-nav" id="mainNav">
    <div class="container">
        <a href="<?= base_url() ?>" class="nav-brand">
            <div class="nav-brand-logo">
                <?php if(file_exists(FCPATH.'assets/img/logo-madrasah.png')): ?>
                    <img src="<?= base_url('assets/img/logo-madrasah.png') ?>" alt="Logo">
                <?php else: ?>
                    M3
                <?php endif; ?>
            </div>
            <div>
                <div><?= $nama ?> MAN 3 Banjar</div>
            </div>
        </a>

        <div class="nav-links" id="navDesktopLinks">
            <a href="#informasi">Informasi</a>
            <a href="#alur">Alur</a>
            <a href="#persyaratan">Persyaratan</a>
            <a href="#faq">FAQ</a>
        </div>

        <div class="nav-cta">
            <a href="<?= base_url('ppdb/login') ?>" class="btn-nav-login">
                <i class="bi bi-box-arrow-in-right"></i> Login
            </a>
            <?php if($is_open): ?>
                <button type="button" class="btn-nav-daftar" data-bs-toggle="modal" data-bs-target="#modalDaftarPpdb">
                    Daftar
                </button>
            <?php else: ?>
                <button type="button" class="btn-nav-daftar disabled" disabled>Daftar</button>
            <?php endif; ?>
        </div>

        <button class="nav-mobile-toggle" id="navToggle" aria-label="Menu">
            <i class="bi bi-list"></i>
        </button>
    </div>

    <div class="container">
        <div class="nav-mobile-menu" id="navMobileMenu">
            <a href="#informasi">Informasi</a>
            <a href="#alur">Alur Pendaftaran</a>
            <a href="#persyaratan">Persyaratan</a>
            <a href="#faq">FAQ</a>
            <a href="<?= base_url('ppdb/login') ?>"><i class="bi bi-box-arrow-in-right"></i> Login Peserta</a>
        </div>
    </div>
</nav>

<!-- ═══ HERO ═══ -->
<header class="hero">
    <div class="container">
        <!-- Status Badge -->
        <div class="hero-status" style="background: <?= $status_bg ?>; color: <?= $status_color ?>;">
            <i class="bi <?= $status_icon ?>"></i> <?= $status_label ?>
        </div>

        <h1><?= $nama ?><br>MAN 3 Banjar</h1>

        <p class="hero-sub">
            Daftar secara online dengan mudah dan cepat. Lengkapi data, unggah berkas, dan pantau status pendaftaran — semuanya dari satu akun.
        </p>

        <!-- Info Bar -->
        <?php if(!empty($ta) || (!empty($tgl_mulai) && !empty($tgl_selesai))): ?>
        <div class="hero-info-bar">
            <?php if(!empty($ta)): ?>
            <div class="hero-info-item">
                <i class="bi bi-mortarboard-fill"></i> Tahun Ajaran <?= $ta ?>
            </div>
            <?php endif; ?>

            <?php if(!empty($tgl_mulai) && !empty($tgl_selesai)): ?>
            <?php if(!empty($ta)): ?><div class="hero-info-divider"></div><?php endif; ?>
            <div class="hero-info-item">
                <i class="bi bi-calendar-range"></i> <?= tanggal_indo($tgl_mulai) ?> — <?= tanggal_indo($tgl_selesai) ?>
            </div>
            <?php endif; ?>
        </div>
        <?php endif; ?>

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

        <!-- Countdown Timer -->
        <?php if(!$is_open && !empty($countdown_date)): ?>
        <div class="countdown-wrap" id="countdownWrap">
            <div class="countdown-label"><i class="bi bi-clock-history"></i> Pendaftaran dibuka dalam</div>
            <div class="countdown-grid">
                <div class="countdown-cell"><div class="num" id="cd-days">00</div><div class="unit">Hari</div></div>
                <div class="countdown-cell"><div class="num" id="cd-hours">00</div><div class="unit">Jam</div></div>
                <div class="countdown-cell"><div class="num" id="cd-mins">00</div><div class="unit">Menit</div></div>
                <div class="countdown-cell"><div class="num" id="cd-secs">00</div><div class="unit">Detik</div></div>
            </div>
        </div>
        <script>
        (function(){
            const target = new Date("<?= $countdown_date ?>T00:00:00").getTime();
            const tick = setInterval(function(){
                const d = target - Date.now();
                if(d < 0){ clearInterval(tick); location.reload(); return; }
                document.getElementById("cd-days").textContent  = String(Math.floor(d/864e5)).padStart(2,'0');
                document.getElementById("cd-hours").textContent = String(Math.floor(d%864e5/36e5)).padStart(2,'0');
                document.getElementById("cd-mins").textContent  = String(Math.floor(d%36e5/6e4)).padStart(2,'0');
                document.getElementById("cd-secs").textContent  = String(Math.floor(d%6e4/1e3)).padStart(2,'0');
            }, 1000);
        })();
        </script>
        <?php endif; ?>
    </div>
</header>

<!-- ═══ INFORMASI ═══ -->
<section class="section section-alt" id="informasi">
    <div class="container">
        <div class="section-head reveal">
            <div class="section-tag"><i class="bi bi-info-circle"></i> Informasi</div>
            <h2>Mengapa Mendaftar di MAN 3 Banjar?</h2>
            <p>Sistem pendaftaran kami dirancang agar proses penerimaan peserta didik baru berjalan transparan, efisien, dan mudah diakses.</p>
        </div>

        <div class="info-grid">
            <div class="info-card reveal">
                <div class="info-card-icon"><i class="bi bi-globe2"></i></div>
                <h5>Pendaftaran Online</h5>
                <p>Calon peserta dapat mendaftar kapan saja dan dari mana saja melalui perangkat apa pun — PC, tablet, maupun HP.</p>
            </div>

            <div class="info-card reveal">
                <div class="info-card-icon"><i class="bi bi-layers-half"></i></div>
                <h5>Pengisian Bertahap</h5>
                <p>Data pribadi, alamat, data orang tua, dan berkas tidak harus diisi sekaligus. Anda bisa melengkapinya setelah login.</p>
            </div>

            <div class="info-card reveal">
                <div class="info-card-icon"><i class="bi bi-graph-up-arrow"></i></div>
                <h5>Pantau Status Real-Time</h5>
                <p>Setiap pendaftar memiliki dashboard pribadi untuk memantau progress pendaftaran, verifikasi berkas, dan pengumuman.</p>
            </div>
        </div>
    </div>
</section>

<!-- ═══ ALUR ═══ -->
<section class="section" id="alur">
    <div class="container">
        <div class="section-head reveal">
            <div class="section-tag"><i class="bi bi-signpost-split"></i> Alur Pendaftaran</div>
            <h2>4 Langkah Mudah Mendaftar</h2>
            <p>Ikuti tahapan berikut agar proses pendaftaran Anda berjalan lancar.</p>
        </div>

        <div class="timeline reveal">
            <div class="timeline-item">
                <div class="timeline-dot">1</div>
                <h5>Buat Akun Pendaftaran</h5>
                <p>Klik tombol <strong>"Daftar Sekarang"</strong> di halaman ini, lalu isi form registrasi awal berupa nama lengkap, NISN, data kelahiran, asal sekolah, kontak, dan password.</p>
            </div>

            <div class="timeline-item">
                <div class="timeline-dot">2</div>
                <h5>Login ke Dashboard Peserta</h5>
                <p>Setelah akun berhasil dibuat, login menggunakan <strong>NISN</strong> dan <strong>password</strong> yang Anda buat. Anda akan masuk ke dashboard peserta.</p>
            </div>

            <div class="timeline-item">
                <div class="timeline-dot">3</div>
                <h5>Lengkapi Biodata & Upload Berkas</h5>
                <p>Di dashboard, lengkapi biodata lanjutan (data orang tua, alamat, dll.) dan unggah dokumen wajib seperti KK, akta kelahiran, dan ijazah/SKL.</p>
            </div>

            <div class="timeline-item">
                <div class="timeline-dot">4</div>
                <h5>Pantau Status Pendaftaran</h5>
                <p>Setelah semua data dan berkas lengkap, panitia akan memverifikasi data Anda. Pantau progress dan pengumuman melalui dashboard peserta.</p>
            </div>
        </div>
    </div>
</section>

<!-- ═══ PAMFLET ═══ -->
<?php if(!empty($settings->pamflet_ppdb)): ?>
<section class="section section-alt" id="pamflet">
    <div class="container text-center">
        <div class="section-head reveal">
            <div class="section-tag"><i class="bi bi-image"></i> Brosur Resmi</div>
            <h2>Pamflet <?= $nama ?></h2>
            <p>Informasi lengkap dalam satu gambar. Silakan pelajari dan bagikan kepada calon pendaftar.</p>
        </div>
        <div class="reveal">
            <div class="pamflet-frame">
                <img src="<?= base_url('uploads/ppdb_pamflet/'.$settings->pamflet_ppdb) ?>" alt="Pamflet <?= $nama ?>">
            </div>
        </div>
    </div>
</section>
<?php endif; ?>

<!-- ═══ PERSYARATAN ═══ -->
<section class="section <?= empty($settings->pamflet_ppdb) ? 'section-alt' : '' ?>" id="persyaratan">
    <div class="container">
        <div class="section-head reveal">
            <div class="section-tag"><i class="bi bi-clipboard-check"></i> Persyaratan</div>
            <h2>Siapkan Berkas Pendaftaran</h2>
            <p>Pastikan Anda telah menyiapkan berkas-berkas berikut sebelum memulai proses pendaftaran.</p>
        </div>

        <div class="req-grid reveal" style="max-width: 760px; margin: 0 auto;">
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
<section class="section section-alt" id="faq">
    <div class="container">
        <div class="section-head reveal">
            <div class="section-tag"><i class="bi bi-question-circle"></i> FAQ</div>
            <h2>Pertanyaan yang Sering Diajukan</h2>
            <p>Temukan jawaban untuk pertanyaan umum seputar pendaftaran.</p>
        </div>

        <div class="faq-wrap reveal">
            <div class="accordion" id="faqAccordion">
                <div class="accordion-item">
                    <h2 class="accordion-header">
                        <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#faq1" aria-expanded="true">
                            Bagaimana cara mendaftar <?= $nama ?>?
                        </button>
                    </h2>
                    <div id="faq1" class="accordion-collapse collapse show" data-bs-parent="#faqAccordion">
                        <div class="accordion-body">
                            Klik tombol <strong>"Daftar Sekarang"</strong> di halaman ini, lalu isi formulir registrasi awal. Setelah akun berhasil dibuat, login menggunakan NISN dan password untuk melengkapi data dan mengunggah berkas.
                        </div>
                    </div>
                </div>

                <div class="accordion-item">
                    <h2 class="accordion-header">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq2">
                            Apakah bisa mendaftar lewat HP?
                        </button>
                    </h2>
                    <div id="faq2" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                        <div class="accordion-body">
                            Ya, sistem pendaftaran kami sepenuhnya responsif dan dapat diakses melalui HP, tablet, maupun komputer. Pastikan koneksi internet Anda stabil saat mengisi data dan mengunggah berkas.
                        </div>
                    </div>
                </div>

                <div class="accordion-item">
                    <h2 class="accordion-header">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq3">
                            Apa saja berkas yang harus disiapkan?
                        </button>
                    </h2>
                    <div id="faq3" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                        <div class="accordion-body">
                            Secara umum, Anda perlu menyiapkan: Kartu Keluarga, Akta Kelahiran, NISN aktif, Ijazah/SKL, Pas Foto, dan Nomor HP aktif. Daftar lengkap dapat dilihat di bagian <strong>Persyaratan</strong> di atas.
                        </div>
                    </div>
                </div>

                <div class="accordion-item">
                    <h2 class="accordion-header">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq4">
                            Bagaimana mengetahui status pendaftaran saya?
                        </button>
                    </h2>
                    <div id="faq4" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                        <div class="accordion-body">
                            Setelah mendaftar dan login, Anda akan memiliki <strong>dashboard peserta</strong> yang menampilkan progress pendaftaran, status verifikasi berkas, serta pengumuman resmi dari panitia secara real-time.
                        </div>
                    </div>
                </div>

                <div class="accordion-item">
                    <h2 class="accordion-header">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq5">
                            Siapa yang bisa dihubungi jika ada kendala?
                        </button>
                    </h2>
                    <div id="faq5" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                        <div class="accordion-body">
                            Jika mengalami kendala teknis atau pertanyaan seputar pendaftaran, silakan hubungi panitia <?= $nama ?> MAN 3 Banjar melalui nomor kontak yang tertera di brosur resmi atau kunjungi langsung kantor madrasah pada jam kerja.
                        </div>
                    </div>
                </div>

                <div class="accordion-item">
                    <h2 class="accordion-header">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq6">
                            Apakah data yang sudah diisi bisa diubah setelahnya?
                        </button>
                    </h2>
                    <div id="faq6" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                        <div class="accordion-body">
                            Ya, selama status pendaftaran Anda belum masuk tahap verifikasi akhir, Anda bisa mengedit biodata dan mengunggah ulang berkas melalui dashboard peserta. Jika status sudah terverifikasi, hubungi panitia untuk perubahan data.
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ═══ CTA BAWAH ═══ -->
<section class="section">
    <div class="container">
        <div class="cta-banner reveal">
            <div>
                <h3>Siap Bergabung dengan MAN 3 Banjar?</h3>
                <p>Daftarkan diri Anda sekarang dan mulai perjalanan pendidikan terbaik bersama kami.</p>
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

<!-- ═══ FOOTER ═══ -->
<footer class="ppdb-footer">
    <div class="container">
        <div class="footer-grid">
            <div>
                <div class="footer-brand"><?= $nama ?> MAN 3 Banjar</div>
                <p style="line-height: 1.7;">
                    Sistem Penerimaan Peserta Didik Baru<br>
                    Madrasah Aliyah Negeri 3 Banjar<br>
                    Kalimantan Selatan
                </p>
            </div>
            <div>
                <div class="footer-brand" style="font-size: 13px;">Tautan</div>
                <ul class="footer-links">
                    <li><a href="<?= base_url() ?>">Beranda Madrasah</a></li>
                    <li><a href="<?= base_url('ppdb/login') ?>">Login Peserta</a></li>
                    <li><a href="#faq">FAQ</a></li>
                </ul>
            </div>
            <div>
                <div class="footer-brand" style="font-size: 13px;">Navigasi</div>
                <ul class="footer-links">
                    <li><a href="#informasi">Informasi</a></li>
                    <li><a href="#alur">Alur Pendaftaran</a></li>
                    <li><a href="#persyaratan">Persyaratan</a></li>
                </ul>
            </div>
        </div>
        <hr class="footer-divider">
        <div class="text-center">
            &copy; <?= date('Y') ?> MAN 3 Banjar — Powered by <strong style="color: rgba(255,255,255,.8);">LabSys</strong>
        </div>
    </div>
</footer>

<!-- ═══ MODAL DAFTAR ═══ -->
<div class="modal fade" id="modalDaftarPpdb" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content ppdb-modal-content">

            <form method="post" action="<?= base_url('ppdb/submit') ?>">

                <div class="modal-header">
                    <div>
                        <h5 class="modal-title fw-bold" style="color: var(--c-emerald-700);">
                            Registrasi Awal <?= $nama ?>
                        </h5>
                        <small class="text-muted fw-bold">
                            Buat akun pendaftaran terlebih dahulu.
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
                        <h6 class="fw-bold mb-3" style="color: var(--c-emerald-700);">Langkah 1: Identitas Utama</h6>
                        <div class="row g-3">
                            <div class="col-md-12">
                                <label class="ppdb-form-label">Nama Lengkap</label>
                                <input type="text" id="w_nama" name="nama_lengkap" class="form-control ppdb-input" placeholder="Masukkan nama lengkap" required minlength="3">
                            </div>
                            <div class="col-md-6">
                                <label class="ppdb-form-label">NISN</label>
                                <input type="text" id="w_nisn" name="nisn" class="form-control ppdb-input" placeholder="10 digit angka" required maxlength="10" pattern="[0-9]{10}" oninput="this.value=this.value.replace(/[^0-9]/g,'').slice(0,10)">
                                <div class="form-text">Pastikan NISN benar dan aktif.</div>
                            </div>
                            <div class="col-md-6">
                                <label class="ppdb-form-label">Jenis Kelamin</label>
                                <select id="w_jk" name="jk" class="form-select ppdb-select" required>
                                    <option value="">Pilih Jenis Kelamin</option>
                                    <option value="L">Laki-laki</option>
                                    <option value="P">Perempuan</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <!-- STEP 2 -->
                    <div class="wizard-step" id="step2">
                        <h6 class="fw-bold mb-3" style="color: var(--c-emerald-700);">Langkah 2: Kelahiran & Asal Sekolah</h6>
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
                                <label class="ppdb-form-label">Asal Sekolah</label>
                                <input type="text" id="w_sekolah" name="asal_sekolah" class="form-control ppdb-input" placeholder="Nama sekolah asal (SMP/MTs)" required>
                            </div>
                        </div>
                    </div>

                    <!-- STEP 3 -->
                    <div class="wizard-step" id="step3">
                        <h6 class="fw-bold mb-3" style="color: var(--c-emerald-700);">Langkah 3: Kontak & Keamanan</h6>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="ppdb-form-label">Nama Ayah / Wali</label>
                                <input type="text" id="w_ortu" name="nama_ortu" class="form-control ppdb-input" placeholder="Nama lengkap ayah/wali" required>
                            </div>
                            <div class="col-md-6">
                                <label class="ppdb-form-label">Nomor HP Aktif</label>
                                <input type="text" id="w_hp" name="no_hp" class="form-control ppdb-input" placeholder="08xxxxxxxxxx" required maxlength="15" pattern="[0-9]{10,15}" oninput="this.value=this.value.replace(/[^0-9]/g,'').slice(0,15)">
                            </div>
                            <div class="col-md-12">
                                <label class="ppdb-form-label">Password Akun</label>
                                <input type="password" id="w_pass" name="password" class="form-control ppdb-input" placeholder="Minimal 6 karakter" required minlength="6">
                                <div class="form-text">Gunakan kombinasi yang mudah diingat untuk login nanti.</div>
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
                        <button type="button" class="btn btn-primary rounded-pill px-4 fw-bold" id="btnNext">Lanjut →</button>
                        <button type="submit" class="btn btn-success rounded-pill px-4 fw-bold" id="btnSubmit" style="display:none;">Buat Akun <?= $nama ?></button>
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
    Swal.fire({ icon:'error', title:'Pendaftaran Gagal', text:'<?= $this->session->flashdata("error") ?>', confirmButtonColor:'#059669' });
});
</script>
<?php endif; ?>

<?php if($this->session->flashdata('success')): ?>
<script>
document.addEventListener('DOMContentLoaded', function(){
    Swal.fire({ icon:'success', title:'Pendaftaran Berhasil', text:'<?= $this->session->flashdata("success") ?>', confirmButtonColor:'#059669' });
});
</script>
<?php endif; ?>

<!-- ═══ SCRIPTS ═══ -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function() {

    /* ─── Navbar scroll effect ─── */
    const nav = document.getElementById('mainNav');
    window.addEventListener('scroll', function() {
        nav.classList.toggle('scrolled', window.scrollY > 10);
    });

    /* ─── Mobile nav toggle ─── */
    const toggle = document.getElementById('navToggle');
    const mobileMenu = document.getElementById('navMobileMenu');
    if(toggle && mobileMenu) {
        toggle.addEventListener('click', function() {
            mobileMenu.classList.toggle('show');
            const icon = toggle.querySelector('i');
            icon.classList.toggle('bi-list');
            icon.classList.toggle('bi-x-lg');
        });
        mobileMenu.querySelectorAll('a').forEach(function(a) {
            a.addEventListener('click', function() { mobileMenu.classList.remove('show'); });
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
                const offset = nav.offsetHeight + 16;
                const top = el.getBoundingClientRect().top + window.pageYOffset - offset;
                window.scrollTo({ top: top, behavior: 'smooth' });
            }
        });
    });

    /* ─── Reveal on scroll ─── */
    const reveals = document.querySelectorAll('.reveal');
    if('IntersectionObserver' in window) {
        const observer = new IntersectionObserver(function(entries) {
            entries.forEach(function(entry) {
                if(entry.isIntersecting) {
                    entry.target.classList.add('visible');
                    observer.unobserve(entry.target);
                }
            });
        }, { threshold: 0.15, rootMargin: '0px 0px -40px 0px' });
        reveals.forEach(function(el) { observer.observe(el); });
    } else {
        reveals.forEach(function(el) { el.classList.add('visible'); });
    }

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
        progressBar.style.width = ((currentStep - 1) / (totalSteps - 1)) * 100 + '%';
        btnPrev.style.display = currentStep > 1 ? 'inline-block' : 'none';
        btnCancel.style.display = currentStep === 1 ? 'inline-block' : 'none';
        if(currentStep === totalSteps) { btnNext.style.display = 'none'; btnSubmit.style.display = 'inline-block'; }
        else { btnNext.style.display = 'inline-block'; btnSubmit.style.display = 'none'; }
    }

    function validateStep(step) {
        let valid = true, msg = '';
        if(step === 1) {
            const n = document.getElementById('w_nama').value.trim();
            const ni = document.getElementById('w_nisn').value.trim();
            const jk = document.getElementById('w_jk').value;
            if(n.length < 3) { valid = false; msg = 'Nama minimal 3 karakter'; }
            else if(ni.length !== 10) { valid = false; msg = 'NISN harus 10 digit angka'; }
            else if(jk === '') { valid = false; msg = 'Pilih jenis kelamin'; }
        } else if(step === 2) {
            const t = document.getElementById('w_tempat').value.trim();
            const d = document.getElementById('w_tanggal').value;
            const s = document.getElementById('w_sekolah').value.trim();
            if(!t || !d || !s) { valid = false; msg = 'Mohon lengkapi semua data pada langkah ini'; }
        }
        if(!valid && msg) {
            Swal.fire({ icon:'warning', title:'Data Belum Lengkap', text: msg, confirmButtonColor:'#059669' });
        }
        return valid;
    }

    if(btnNext) { btnNext.addEventListener('click', function() { if(validateStep(currentStep)) { currentStep++; updateWizard(); } }); }
    if(btnPrev) { btnPrev.addEventListener('click', function() { currentStep--; updateWizard(); }); }

    const modalEl = document.getElementById('modalDaftarPpdb');
    if(modalEl) { modalEl.addEventListener('show.bs.modal', function() { currentStep = 1; updateWizard(); }); }
});
</script>

</body>
</html>