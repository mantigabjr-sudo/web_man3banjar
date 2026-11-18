<?php 
// Load Header Resmi Website Madrasah
$this->load->view('public/partials/archive_header'); 

// Hitung data statistik ringkasan secara konsisten
$stat_total   = isset($stat_total) ? (int)$stat_total : count($riwayat_cuti);
$stat_aktif   = isset($stat_aktif) ? (int)$stat_aktif : 0;
$stat_draft   = isset($stat_draft) ? (int)$stat_draft : 0;
$stat_pegawai = isset($stat_pegawai) ? (int)$stat_pegawai : count($pegawai_list);

if (!isset($stat_aktif) && !empty($riwayat_cuti)) {
    foreach ($riwayat_cuti as $r_item) {
        if ($r_item->status == 'Aktif') {
            $stat_aktif++;
        } elseif (in_array($r_item->status, ['Draft', 'Disetujui Kepala'])) {
            $stat_draft++;
        }
    }
}

if (!function_exists('cuti_initials')) {
    function cuti_initials($name) {
        $clean = trim(preg_replace('/,.*$/', '', (string)$name));
        $parts = preg_split('/\s+/', $clean);
        $init = '';
        foreach ($parts as $p) {
            $p = preg_replace('/[^a-zA-Z]/', '', $p);
            if ($p !== '') {
                $init .= strtoupper($p[0]);
                if (strlen($init) >= 2) break;
            }
        }
        return $init ?: 'ASN';
    }
}

if (!function_exists('cuti_jenis_badge_class')) {
    function cuti_jenis_badge_class($jenis) {
        $j = strtolower((string)$jenis);
        if (strpos($j, 'sakit') !== false) return 'cuti-type-sakit';
        if (strpos($j, 'melahirkan') !== false) return 'cuti-type-lahir';
        if (strpos($j, 'besar') !== false) return 'cuti-type-besar';
        if (strpos($j, 'penting') !== false) return 'cuti-type-penting';
        if (strpos($j, 'tanggungan') !== false) return 'cuti-type-cltn';
        return 'cuti-type-tahunan';
    }
}
?>

<!-- Select2 CSS & FontAwesome -->
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<link href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" rel="stylesheet" />
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" rel="stylesheet">

<style>
:root {
    --cuti-brand-dark: #0f4422;
    --cuti-brand: #15803d;
    --cuti-brand-light: #ecfdf5;
    --cuti-ink: #0f172a;
    --cuti-muted: #64748b;
    --cuti-border: #e2e8f0;
    --cuti-surface: #ffffff;
    --cuti-bg-soft: #f8fafc;
}

/* ═══ 1. HERO HEADER (SELARAS IDENTITAS MAN 3 BANJAR) ═══ */
.kbm-hero {
    background: linear-gradient(135deg, #0f4422 0%, #15803d 55%, #16a34a 100%);
    color: #ffffff;
    border-radius: 20px;
    padding: 26px 30px;
    margin-bottom: 20px;
    position: relative;
    overflow: hidden;
    box-shadow: 0 12px 28px rgba(15, 68, 34, 0.16);
}

.kbm-hero::after {
    content: '';
    position: absolute;
    right: -60px;
    top: -60px;
    width: 240px;
    height: 240px;
    background: radial-gradient(circle, rgba(255,255,255,0.12), transparent 70%);
    border-radius: 50%;
    pointer-events: none;
}

.kbm-hero-top {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    flex-wrap: wrap;
    gap: 16px;
    position: relative;
    z-index: 2;
}

.live-pulse-badge {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    background: rgba(0, 0, 0, 0.22);
    border: 1px solid rgba(255, 255, 255, 0.24);
    padding: 5px 12px;
    border-radius: 999px;
    font-size: 11.5px;
    font-weight: 700;
    letter-spacing: 0.4px;
    text-transform: uppercase;
}

.pulse-dot {
    width: 8px;
    height: 8px;
    background-color: #4ade80;
    border-radius: 50%;
    box-shadow: 0 0 0 0 rgba(74, 222, 128, 0.7);
    animation: livePulse 2s infinite;
    display: inline-block;
}

@keyframes livePulse {
    0% { transform: scale(0.95); box-shadow: 0 0 0 0 rgba(74, 222, 128, 0.65); }
    70% { transform: scale(1); box-shadow: 0 0 0 8px rgba(74, 222, 128, 0); }
    100% { transform: scale(0.95); box-shadow: 0 0 0 0 rgba(74, 222, 128, 0); }
}

.live-clock-box {
    text-align: right;
    background: rgba(255, 255, 255, 0.12);
    border: 1px solid rgba(255, 255, 255, 0.2);
    padding: 8px 16px;
    border-radius: 14px;
}

.live-clock {
    font-size: 24px;
    font-weight: 800;
    letter-spacing: 0.5px;
    font-variant-numeric: tabular-nums;
    line-height: 1.1;
}

.hero-action-bar {
    background: rgba(255, 255, 255, 0.12);
    border: 1px solid rgba(255, 255, 255, 0.2);
    border-radius: 14px;
    padding: 12px 16px;
    margin-top: 18px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    flex-wrap: wrap;
    gap: 12px;
    position: relative;
    z-index: 2;
}

/* ═══ 2. 4 KPI STATS GRID (PROPORSIONAL & PALET TERPADU) ═══ */
.kbm-stats-grid {
    display: grid;
    grid-template-columns: repeat(4, minmax(0, 1fr));
    gap: 14px;
    margin-bottom: 20px;
}

.kbm-stat-card {
    background: var(--cuti-surface);
    border-radius: 16px;
    padding: 16px 18px;
    border: 1px solid var(--cuti-border);
    box-shadow: 0 1px 3px rgba(15, 23, 42, 0.03);
    display: flex;
    align-items: center;
    gap: 14px;
    transition: border-color 0.2s ease, box-shadow 0.2s ease;
}

.kbm-stat-card:hover {
    border-color: #cbd5e1;
    box-shadow: 0 6px 16px rgba(15, 23, 42, 0.05);
}

.kbm-stat-icon {
    width: 44px;
    height: 44px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 20px;
    flex-shrink: 0;
}

.kbm-stat-body {
    min-width: 0;
}

.kbm-stat-card small {
    display: block;
    font-size: 11px;
    font-weight: 700;
    color: var(--cuti-muted);
    text-transform: uppercase;
    letter-spacing: 0.35px;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}

.kbm-stat-card strong {
    display: block;
    font-size: 21px;
    font-weight: 800;
    color: var(--cuti-ink);
    line-height: 1.2;
    margin-top: 2px;
    font-variant-numeric: tabular-nums;
}

/* ═══ 3. FILTER & TAB BAR ═══ */
.kbm-filter-bar {
    background: var(--cuti-surface);
    border: 1px solid var(--cuti-border);
    border-radius: 16px;
    padding: 16px 18px;
    margin-bottom: 20px;
    box-shadow: 0 1px 3px rgba(15, 23, 42, 0.03);
}

.kbm-quick-pills {
    display: flex;
    gap: 6px;
    overflow-x: auto;
    white-space: nowrap;
    padding-bottom: 2px;
}

.kbm-quick-pill {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 8px 15px;
    border-radius: 10px;
    font-size: 13px;
    font-weight: 700;
    background: var(--cuti-bg-soft);
    border: 1px solid var(--cuti-border);
    color: #475569;
    text-decoration: none;
    cursor: pointer;
    transition: all 0.18s ease;
}

.kbm-quick-pill:hover {
    background: #ecfdf5;
    color: #15803d;
    border-color: #bbf7d0;
}

.kbm-quick-pill.active {
    background: #15803d !important;
    color: #ffffff !important;
    border-color: #15803d !important;
}

.cuti-filter-input {
    border: 1px solid #cbd5e1;
    background: #ffffff;
    border-radius: 10px;
    font-size: 13.5px;
    color: var(--cuti-ink);
}

.cuti-filter-input:focus {
    border-color: #15803d;
    box-shadow: 0 0 0 3px rgba(21, 128, 61, 0.12);
}

/* ═══ 4. CARD CONTAINER BARU (BERSIH, TERSTRUKTUR, MUDAH DIPINDAI) ═══ */
.kbm-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
    gap: 16px;
    margin-bottom: 28px;
}

.cuti-item-card {
    background: var(--cuti-surface);
    border-radius: 16px;
    border: 1px solid var(--cuti-border);
    padding: 18px;
    box-shadow: 0 1px 3px rgba(15, 23, 42, 0.03);
    transition: border-color 0.2s ease, box-shadow 0.2s ease, transform 0.2s ease;
    display: flex;
    flex-direction: column;
    justify-content: space-between;
    gap: 14px;
}

.cuti-item-card:hover {
    border-color: #86efac;
    box-shadow: 0 10px 24px rgba(15, 23, 42, 0.06);
    transform: translateY(-2px);
}

.cuti-card-top {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    gap: 10px;
}

.cuti-person {
    display: flex;
    align-items: flex-start;
    gap: 11px;
    min-width: 0;
}

.cuti-avatar {
    width: 42px;
    height: 42px;
    border-radius: 11px;
    background: #ecfdf5;
    color: #047857;
    border: 1px solid #d1fae5;
    font-weight: 800;
    font-size: 13.5px;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
    object-fit: cover;
}

.cuti-person-info {
    min-width: 0;
}

.cuti-person-name {
    font-size: 14.5px;
    font-weight: 800;
    color: var(--cuti-ink);
    line-height: 1.25;
    margin-bottom: 2px;
    word-break: break-word;
}

.cuti-person-nip {
    font-size: 11.5px;
    font-family: ui-monospace, SFMono-Regular, Menlo, Monaco, Consolas, monospace;
    color: var(--cuti-muted);
    line-height: 1.3;
}

.cuti-person-jabatan {
    font-size: 11.5px;
    color: #64748b;
    margin-top: 2px;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}

/* Soft semantic badges untuk jenis cuti */
.cuti-type-badge {
    display: inline-flex;
    align-items: center;
    padding: 4px 10px;
    border-radius: 8px;
    font-size: 11px;
    font-weight: 700;
    white-space: nowrap;
    flex-shrink: 0;
}

.cuti-type-tahunan { background: #ecfdf5; color: #047857; border: 1px solid #bbf7d0; }
.cuti-type-sakit   { background: #fef2f2; color: #b91c1c; border: 1px solid #fecaca; }
.cuti-type-lahir   { background: #eff6ff; color: #1d4ed8; border: 1px solid #bfdbfe; }
.cuti-type-besar   { background: #fffbeb; color: #b45309; border: 1px solid #fde68a; }
.cuti-type-penting { background: #f1f5f9; color: #334155; border: 1px solid #cbd5e1; }
.cuti-type-cltn    { background: #f8fafc; color: #475569; border: 1px solid #cbd5e1; }

/* Blok Jadwal & Alasan */
.cuti-schedule-box {
    background: var(--cuti-bg-soft);
    border: 1px solid #edf2f7;
    border-radius: 12px;
    padding: 11px 13px;
}

.cuti-schedule-row {
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 8px;
    flex-wrap: wrap;
}

.cuti-date-range {
    font-size: 12.5px;
    font-weight: 700;
    color: var(--cuti-ink);
    display: inline-flex;
    align-items: center;
    gap: 6px;
}

.cuti-duration-chip {
    background: #ffffff;
    border: 1px solid #e2e8f0;
    color: #047857;
    font-weight: 800;
    font-size: 11.5px;
    padding: 3px 9px;
    border-radius: 8px;
    white-space: nowrap;
}

.cuti-alasan-text {
    font-size: 12px;
    color: #475569;
    margin-top: 8px;
    padding-top: 8px;
    border-top: 1px dashed #e2e8f0;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
    line-height: 1.45;
}

/* Footer kartu */
.cuti-card-footer {
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 8px;
    padding-top: 12px;
    border-top: 1px solid #f1f5f9;
}

.kbm-status-pill {
    display: inline-flex;
    align-items: center;
    gap: 5px;
    padding: 4px 11px;
    border-radius: 999px;
    font-size: 11.5px;
    font-weight: 700;
}

.pill-hadir      { background: #dcfce7; color: #166534; border: 1px solid #bbf7d0; }
.pill-tugas-luar { background: #e0f2fe; color: #075985; border: 1px solid #bae6fd; }
.pill-sakit      { background: #fee2e2; color: #991b1b; border: 1px solid #fecaca; }
.pill-pending    { background: #fef3c7; color: #92400e; border: 1px solid #fde68a; }

.cuti-meta-date {
    font-size: 11.5px;
    color: var(--cuti-muted);
    white-space: nowrap;
}

/* ═══ FORM & SALDO STYLING ═══ */
.cuti-card-main {
    border-radius: 16px;
    background: #ffffff;
    border: 1px solid var(--cuti-border);
    box-shadow: 0 1px 3px rgba(15, 23, 42, 0.03);
}

.saldo-box {
    background: #f8fafc;
    border: 1px solid #e2e8f0;
    border-radius: 12px;
    padding: 14px;
    text-align: center;
    transition: border-color 0.2s ease;
}

.saldo-box:hover {
    border-color: #15803d;
}

.saldo-num {
    font-size: 1.6rem;
    font-weight: 800;
    color: #15803d;
    line-height: 1.2;
}

.saldo-label {
    font-size: 0.75rem;
    color: #64748b;
    text-transform: uppercase;
    font-weight: 700;
    letter-spacing: 0.4px;
}

.jenis-cuti-card {
    border: 1.5px solid #e2e8f0;
    border-radius: 14px;
    padding: 14px 16px;
    cursor: pointer;
    transition: all 0.18s ease;
    height: 100%;
    background: #ffffff;
}

.jenis-cuti-card:hover {
    border-color: #15803d;
    background-color: #f0fdf4;
}

.jenis-cuti-card.active {
    border-color: #15803d;
    background-color: #ecfdf5;
    box-shadow: 0 0 0 1px #15803d;
}

.select2-container--bootstrap-5 .select2-selection {
    min-height: 46px;
    font-size: 0.95rem;
    border-radius: 10px;
    border: 1px solid #cbd5e1;
}

.table-monitoring th {
    font-size: 11.5px;
    text-transform: uppercase;
    letter-spacing: 0.4px;
    font-weight: 700;
}

.table-monitoring td {
    font-size: 13.5px;
}

@media(max-width: 992px) {
    .kbm-stats-grid {
        grid-template-columns: repeat(2, minmax(0, 1fr));
    }
}

@media(max-width: 576px) {
    .kbm-grid {
        grid-template-columns: 1fr;
    }
    .kbm-hero {
        padding: 20px;
    }
    .live-clock-box {
        width: 100%;
        text-align: left;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    .live-clock {
        font-size: 20px;
    }
}
</style>

<div class="container py-4" id="cutiMonitoringWrapper">

    <!-- ═══ 1. HERO HEADER ═══ -->
    <div class="kbm-hero">
        <div class="kbm-hero-top">
            <div>
                <div class="live-pulse-badge mb-2">
                    <span class="pulse-dot"></span> Layanan &amp; Monitoring Cuti ASN
                </div>
                <h2 style="font-weight: 800; margin: 0; letter-spacing: -0.4px; font-size: clamp(1.35rem, 2.4vw, 1.85rem);">
                    Portal Cuti Pegawai ASN <?= htmlspecialchars($nama_madrasah ?? 'MAN 3 Banjar', ENT_QUOTES, 'UTF-8') ?>
                </h2>
                <p style="margin: 6px 0 0; opacity: 0.88; font-size: 13.5px;">
                    Pengajuan mandiri, pengecekan sisa hak cuti, dan pemantauan status persetujuan berbasis Peraturan BKN No. 24/2017.
                </p>
            </div>

            <div class="live-clock-box">
                <div class="live-clock" id="liveClockDisplay">--:--:--</div>
                <div class="text-white small fw-semibold opacity-90 mt-1" id="liveDateDisplay" style="font-size: 12px;">
                    <i class="bi bi-calendar3 me-1"></i> <?= date('d M Y') ?>
                </div>
            </div>
        </div>

        <!-- BARIS AKSI LANGSUNG -->
        <div class="hero-action-bar">
            <div class="d-flex align-items-center gap-2 small">
                <i class="bi bi-shield-check fs-5 text-white"></i>
                <span>Tersinkronisasi dengan database kepegawaian Tata Usaha Tahun <?= date('Y') ?>.</span>
            </div>
            <div class="d-flex align-items-center gap-2 flex-wrap">
                <button type="button" class="btn btn-light btn-sm fw-bold rounded-pill px-3 py-1.5 text-success" onclick="switchViewSection('form')">
                    <i class="bi bi-plus-circle-fill me-1"></i> Ajukan Cuti Baru
                </button>
                <button type="button" class="btn btn-outline-light btn-sm fw-semibold rounded-pill px-3 py-1.5" onclick="switchViewSection('lacak')">
                    <i class="bi bi-search me-1"></i> Cek Saldo Cuti
                </button>
            </div>
        </div>
    </div>

    <!-- ═══ 2. 4 KPI STATS GRID ═══ -->
    <div class="kbm-stats-grid">
        <div class="kbm-stat-card">
            <div class="kbm-stat-icon" style="background: #f1f5f9; color: #334155;">
                <i class="bi bi-folder2-open"></i>
            </div>
            <div class="kbm-stat-body">
                <small>Total Permohonan</small>
                <strong><?= $stat_total ?> Berkas</strong>
            </div>
        </div>

        <div class="kbm-stat-card">
            <div class="kbm-stat-icon" style="background: #dcfce7; color: #15803d;">
                <i class="bi bi-check-circle-fill"></i>
            </div>
            <div class="kbm-stat-body">
                <small>Disetujui / Aktif</small>
                <strong class="text-success"><?= $stat_aktif ?> Berkas</strong>
            </div>
        </div>

        <div class="kbm-stat-card">
            <div class="kbm-stat-icon" style="background: <?= $stat_draft > 0 ? '#fef3c7' : '#f1f5f9' ?>; color: <?= $stat_draft > 0 ? '#b45309' : '#64748b' ?>;">
                <i class="bi bi-hourglass-split"></i>
            </div>
            <div class="kbm-stat-body">
                <small>Menunggu Proses</small>
                <strong style="color: <?= $stat_draft > 0 ? '#b45309' : '#0f172a' ?>;"><?= $stat_draft ?> Berkas</strong>
            </div>
        </div>

        <div class="kbm-stat-card">
            <div class="kbm-stat-icon" style="background: #ecfdf5; color: #047857;">
                <i class="bi bi-people-fill"></i>
            </div>
            <div class="kbm-stat-body">
                <small>Pegawai ASN</small>
                <strong><?= $stat_pegawai ?> Orang</strong>
            </div>
        </div>
    </div>

    <!-- NOTIFIKASI FLASH DATA -->
    <?php if($this->session->flashdata('success')): ?>
        <div class="alert alert-success alert-dismissible fade show shadow-sm border-0 mb-4" style="border-radius: 14px; background: #ecfdf5; color: #065f46; border-left: 4px solid #15803d !important;" role="alert">
            <div class="d-flex align-items-center">
                <i class="bi bi-check-circle-fill fs-4 me-3 text-success"></i>
                <div>
                    <h6 class="fw-bold mb-0">Berhasil!</h6>
                    <small><?= $this->session->flashdata('success') ?></small>
                </div>
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <?php if($this->session->flashdata('error')): ?>
        <div class="alert alert-danger alert-dismissible fade show shadow-sm border-0 mb-4" style="border-radius: 14px; background: #fef2f2; color: #991b1b; border-left: 4px solid #dc2626 !important;" role="alert">
            <div class="d-flex align-items-center">
                <i class="bi bi-exclamation-triangle-fill fs-4 me-3 text-danger"></i>
                <div>
                    <h6 class="fw-bold mb-0">Pemberitahuan</h6>
                    <small><?= $this->session->flashdata('error') ?></small>
                </div>
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <!-- ═══ 3. NAVIGASI TAB & FILTER BAR ═══ -->
    <div class="kbm-filter-bar">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
            <div class="kbm-quick-pills mb-0">
                <button type="button" class="kbm-quick-pill active" id="pill-monitoring" onclick="switchViewSection('monitoring')">
                    <i class="bi bi-grid-3x3-gap-fill"></i> Daftar Cuti (<?= $stat_total ?>)
                </button>
                <button type="button" class="kbm-quick-pill" id="pill-form" onclick="switchViewSection('form')">
                    <i class="bi bi-pencil-square"></i> Buat Pengajuan
                </button>
                <button type="button" class="kbm-quick-pill" id="pill-lacak" onclick="switchViewSection('lacak')">
                    <i class="bi bi-search"></i> Cek Saldo &amp; Lacak
                </button>
                <button type="button" class="kbm-quick-pill" id="pill-regulasi" onclick="switchViewSection('regulasi')">
                    <i class="bi bi-journal-text"></i> Regulasi BKN
                </button>
            </div>

            <div class="d-flex align-items-center gap-2">
                <div class="btn-group btn-group-sm" role="group" id="btnGroupTampilan">
                    <button type="button" class="btn btn-outline-success active fw-semibold" id="btnViewCards" onclick="setMonitoringLayout('cards')" title="Mode Kartu Grid">
                        <i class="bi bi-grid-fill me-1"></i> Kartu
                    </button>
                    <button type="button" class="btn btn-outline-success fw-semibold" id="btnViewTable" onclick="setMonitoringLayout('table')" title="Mode Tabel Lengkap">
                        <i class="bi bi-table me-1"></i> Tabel
                    </button>
                </div>

                <?php if(!empty($is_admin)): ?>
                    <button type="button" class="btn btn-sm btn-outline-secondary fw-semibold rounded-pill px-3" onclick="syncGoogleSheet(this)" title="Sinkron Spreadsheet">
                        <i class="bi bi-arrow-repeat"></i> Sinkron Sheet
                    </button>
                <?php endif; ?>
            </div>
        </div>

        <!-- FILTER & LIVE SEARCH -->
        <div id="filterSearchRow" class="pt-3 mt-3 border-top">
            <div class="row g-2 align-items-center">
                <div class="col-md-5">
                    <div class="input-group">
                        <span class="input-group-text bg-white border-end-0 text-muted" style="border-color: #cbd5e1; border-radius: 10px 0 0 10px;">
                            <i class="bi bi-search"></i>
                        </span>
                        <input type="text" class="form-control border-start-0 py-2 cuti-filter-input" id="liveSearchInput" placeholder="Cari nama pegawai, NIP, atau alasan cuti..." style="border-radius: 0 10px 10px 0;">
                    </div>
                </div>

                <div class="col-md-3">
                    <select class="form-select py-2 cuti-filter-input" id="filterJenisSelect" onchange="applyMonitoringFilters()">
                        <option value="">Semua Jenis Cuti</option>
                        <option value="Cuti Tahunan">Cuti Tahunan</option>
                        <option value="Cuti Sakit">Cuti Sakit</option>
                        <option value="Cuti Besar">Cuti Besar</option>
                        <option value="Cuti Melahirkan">Cuti Melahirkan</option>
                        <option value="Cuti Alasan Penting">Cuti Alasan Penting</option>
                        <option value="Cuti diluar Tanggungan">Cuti Luar Tanggungan</option>
                    </select>
                </div>

                <div class="col-md-4">
                    <select class="form-select py-2 cuti-filter-input" id="filterStatusSelect" onchange="applyMonitoringFilters()">
                        <option value="">Semua Status</option>
                        <option value="Aktif">Disetujui (Aktif)</option>
                        <option value="Disetujui Kepala">Disetujui Kepala Madrasah</option>
                        <option value="Draft">Menunggu Verifikasi</option>
                        <option value="Ditolak">Ditolak</option>
                    </select>
                </div>
            </div>
        </div>
    </div>

    <!-- ═══ 4. VIEW SECTIONS ═══ -->

    <!-- ── SECTION A: MONITORING (KARTU GRID & TABEL) ── -->
    <div id="section-monitoring">

        <!-- A.1 MODE KARTU GRID -->
        <div id="containerCardsView" class="kbm-grid">
            <?php if (empty($riwayat_cuti)): ?>
                <div class="text-center py-5 bg-white rounded-4 border" style="grid-column: 1 / -1;">
                    <i class="bi bi-calendar-x text-muted" style="font-size: 44px;"></i>
                    <h5 class="fw-bold mt-3 text-dark">Belum Ada Permohonan Cuti</h5>
                    <p class="text-muted small mb-3">Belum ada pengajuan cuti pegawai ASN yang tercatat di sistem.</p>
                    <button type="button" class="btn btn-sm btn-success rounded-pill px-4 fw-semibold" onclick="switchViewSection('form')">
                        <i class="bi bi-plus-circle me-1"></i> Ajukan Cuti Sekarang
                    </button>
                </div>
            <?php else: ?>
                <?php foreach ($riwayat_cuti as $r): ?>
                    <?php
                    $tgl_surat   = !empty($r->tanggal_surat) && $r->tanggal_surat != '0000-00-00' ? date('d M Y', strtotime($r->tanggal_surat)) : '-';
                    $tgl_mulai   = !empty($r->tanggal_mulai) && $r->tanggal_mulai != '0000-00-00' ? date('d M Y', strtotime($r->tanggal_mulai)) : '-';
                    $tgl_selesai = !empty($r->tanggal_selesai) && $r->tanggal_selesai != '0000-00-00' ? date('d M Y', strtotime($r->tanggal_selesai)) : '-';

                    $search_text = strtolower(($r->nama_lengkap ?? '') . ' ' . ($r->nip ?? '') . ' ' . ($r->jenis_cuti ?? '') . ' ' . ($r->alasan_cuti ?? ''));
                    $jenis_cls   = cuti_jenis_badge_class($r->jenis_cuti ?? '');
                    $has_foto    = !empty($r->ptk_foto) && file_exists(FCPATH . 'uploads/ptk/foto/' . $r->ptk_foto);

                    $status_pill_class = 'pill-pending';
                    $status_label = '<i class="bi bi-clock-history"></i> Menunggu TU';
                    if ($r->status == 'Aktif') {
                        $status_pill_class = 'pill-hadir';
                        $status_label = '<i class="bi bi-check-all"></i> Disetujui (Aktif)';
                    } elseif ($r->status == 'Disetujui Kepala') {
                        $status_pill_class = 'pill-tugas-luar';
                        $status_label = '<i class="bi bi-check-circle-fill"></i> Disetujui Kamad';
                    } elseif ($r->status == 'Ditolak') {
                        $status_pill_class = 'pill-sakit';
                        $status_label = '<i class="bi bi-x-circle-fill"></i> Ditolak';
                    }
                    ?>
                    <div class="cuti-item-card monitoring-item" data-search="<?= htmlspecialchars($search_text) ?>" data-jenis="<?= htmlspecialchars($r->jenis_cuti) ?>" data-status="<?= htmlspecialchars($r->status) ?>">
                        <div class="d-flex flex-column gap-3">
                            <!-- Identitas Pegawai + Jenis Cuti -->
                            <div class="cuti-card-top">
                                <div class="cuti-person">
                                    <?php if ($has_foto): ?>
                                        <img src="<?= base_url('uploads/ptk/foto/' . $r->ptk_foto) ?>" alt="<?= htmlspecialchars($r->nama_lengkap ?? 'ASN') ?>" class="cuti-avatar" loading="lazy">
                                    <?php else: ?>
                                        <div class="cuti-avatar"><?= cuti_initials($r->nama_lengkap ?? 'ASN') ?></div>
                                    <?php endif; ?>
                                    <div class="cuti-person-info">
                                        <div class="cuti-person-name"><?= htmlspecialchars($r->nama_lengkap ?: 'Pegawai ASN') ?></div>
                                        <div class="cuti-person-nip">NIP. <?= htmlspecialchars($r->nip ?: '-') ?></div>
                                        <?php if (!empty($r->ptk_jabatan)): ?>
                                            <div class="cuti-person-jabatan" title="<?= htmlspecialchars($r->ptk_jabatan) ?>">
                                                <?= htmlspecialchars($r->ptk_jabatan) ?>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                </div>
                                <span class="cuti-type-badge <?= $jenis_cls ?>">
                                    <?= htmlspecialchars($r->jenis_cuti) ?>
                                </span>
                            </div>

                            <!-- Jadwal & Alasan -->
                            <div class="cuti-schedule-box">
                                <div class="cuti-schedule-row">
                                    <span class="cuti-date-range">
                                        <i class="bi bi-calendar3 text-success"></i>
                                        <span><?= $tgl_mulai ?> &ndash; <?= $tgl_selesai ?></span>
                                    </span>
                                    <span class="cuti-duration-chip">
                                        <?= (int)$r->lama_cuti_jumlah ?> <?= htmlspecialchars($r->lama_cuti_satuan) ?>
                                    </span>
                                </div>
                                <?php if (!empty($r->alasan_cuti)): ?>
                                    <div class="cuti-alasan-text" title="<?= htmlspecialchars($r->alasan_cuti) ?>">
                                        <?= htmlspecialchars($r->alasan_cuti) ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>

                        <!-- Footer Status & Tanggal Surat -->
                        <div class="cuti-card-footer">
                            <span class="kbm-status-pill <?= $status_pill_class ?>">
                                <?= $status_label ?>
                            </span>
                            <span class="cuti-meta-date">
                                <i class="bi bi-file-earmark-text me-1"></i><?= $tgl_surat ?>
                            </span>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>

        <!-- A.2 MODE TABEL LENGKAP -->
        <div id="containerTableView" style="display: none;" class="card cuti-card-main mb-4 overflow-hidden">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0 table-monitoring">
                        <thead style="background: #f8fafc; color: #475569; border-bottom: 1px solid #e2e8f0;">
                            <tr class="text-nowrap">
                                <th class="text-center py-3" width="4%">No</th>
                                <th width="11%">Tanggal Surat</th>
                                <th width="26%">Nama Pegawai &amp; NIP</th>
                                <th width="14%">Jenis Cuti</th>
                                <th width="9%" class="text-center">Durasi</th>
                                <th width="16%">Rentang Tanggal</th>
                                <th width="10%">Alasan</th>
                                <th width="10%" class="text-center">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($riwayat_cuti)): ?>
                                <tr>
                                    <td colspan="8" class="text-center text-muted py-5">Belum ada permohonan cuti yang tercatat.</td>
                                </tr>
                            <?php else: ?>
                                <?php $no = 1; foreach ($riwayat_cuti as $r): ?>
                                    <?php
                                    $tgl_surat   = !empty($r->tanggal_surat) && $r->tanggal_surat != '0000-00-00' ? date('d/m/Y', strtotime($r->tanggal_surat)) : '-';
                                    $tgl_mulai   = !empty($r->tanggal_mulai) && $r->tanggal_mulai != '0000-00-00' ? date('d M Y', strtotime($r->tanggal_mulai)) : '-';
                                    $tgl_selesai = !empty($r->tanggal_selesai) && $r->tanggal_selesai != '0000-00-00' ? date('d M Y', strtotime($r->tanggal_selesai)) : '-';

                                    $search_text = strtolower(($r->nama_lengkap ?? '') . ' ' . ($r->nip ?? '') . ' ' . ($r->jenis_cuti ?? '') . ' ' . ($r->alasan_cuti ?? ''));
                                    $jenis_cls   = cuti_jenis_badge_class($r->jenis_cuti ?? '');

                                    $status_pill_class = 'pill-pending';
                                    $status_label = '<i class="bi bi-clock-history"></i> Menunggu TU';
                                    if ($r->status == 'Disetujui Kepala') {
                                        $status_pill_class = 'pill-tugas-luar';
                                        $status_label = '<i class="bi bi-check-circle-fill"></i> Disetujui Kamad';
                                    } elseif ($r->status == 'Aktif') {
                                        $status_pill_class = 'pill-hadir';
                                        $status_label = '<i class="bi bi-check-all"></i> Disetujui (Aktif)';
                                    } elseif ($r->status == 'Ditolak') {
                                        $status_pill_class = 'pill-sakit';
                                        $status_label = '<i class="bi bi-x-circle-fill"></i> Ditolak';
                                    }
                                    ?>
                                    <tr class="table-monitoring-row monitoring-item" data-search="<?= htmlspecialchars($search_text) ?>" data-jenis="<?= htmlspecialchars($r->jenis_cuti) ?>" data-status="<?= htmlspecialchars($r->status) ?>">
                                        <td class="text-center text-muted small py-3"><?= $no++ ?></td>
                                        <td class="small text-muted text-nowrap"><?= $tgl_surat ?></td>
                                        <td>
                                            <div class="fw-bold" style="color: #0f172a; font-size: 13.5px;"><?= htmlspecialchars($r->nama_lengkap ?? '-') ?></div>
                                            <div class="text-muted font-monospace" style="font-size: 11.5px;">NIP. <?= htmlspecialchars($r->nip ?? '-') ?></div>
                                        </td>
                                        <td>
                                            <span class="cuti-type-badge <?= $jenis_cls ?>">
                                                <?= htmlspecialchars($r->jenis_cuti) ?>
                                            </span>
                                        </td>
                                        <td class="text-center fw-bold text-nowrap" style="font-size: 13px;">
                                            <?= (int)$r->lama_cuti_jumlah ?> <?= htmlspecialchars($r->lama_cuti_satuan) ?>
                                        </td>
                                        <td class="small text-nowrap">
                                            <div class="fw-semibold text-dark"><?= $tgl_mulai ?></div>
                                            <div class="text-muted">s.d <?= $tgl_selesai ?></div>
                                        </td>
                                        <td class="small text-muted" style="max-width: 190px;">
                                            <div class="text-truncate" title="<?= htmlspecialchars($r->alasan_cuti ?: '-') ?>">
                                                <?= htmlspecialchars($r->alasan_cuti ?: '-') ?>
                                            </div>
                                        </td>
                                        <td class="text-center text-nowrap">
                                            <span class="kbm-status-pill <?= $status_pill_class ?>"><?= $status_label ?></span>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="text-muted small d-flex align-items-center justify-content-between flex-wrap gap-2 mb-4 px-1">
            <span><i class="bi bi-info-circle me-1"></i> Pencetakan formulir resmi BKN dilakukan oleh Tata Usaha atau melalui akun pegawai masing-masing.</span>
        </div>

    </div>

    <!-- ── SECTION B: FORMULIR PENGAJUAN MANDIRI ── -->
    <div id="section-form" style="display: none;">

        <div class="alert alert-success border-0 shadow-sm d-flex align-items-center mb-4 py-3 px-4 rounded-4" style="background-color: #ecfdf5; border-left: 5px solid #10b981 !important;">
            <i class="bi bi-info-circle-fill fs-3 text-success me-3"></i>
            <div>
                <strong class="d-block text-success" style="font-size: 15px;">Layanan Pengajuan Cuti Mandiri (Mode Terbuka / Tanpa Login)</strong>
                <span class="text-secondary small">
                    Silakan pilih nama atau masukkan NIP Anda di bawah. Sistem akan otomatis melengkapi profil, masa kerja, dan saldo cuti Anda. Pengajuan ini berstatus <strong>Menunggu Persetujuan</strong> dan akan diverifikasi oleh Kepala Madrasah &amp; Tata Usaha.
                </span>
            </div>
        </div>

        <form action="<?= base_url('cuti/submit') ?>" method="POST" enctype="multipart/form-data" id="formCuti">

            <!-- LANGKAH 1: IDENTITAS PEGAWAI (AUTOCOMPLETE NIP) -->
            <div class="card cuti-card-main mb-4">
                <div class="card-body p-4 p-md-5">
                    <div class="d-flex align-items-center gap-2 mb-2">
                        <span class="badge bg-success-subtle text-success px-3 py-2 rounded-pill fw-bold" style="font-size: 12px;">
                            <i class="bi bi-person-badge-fill me-1"></i> Langkah 1
                        </span>
                    </div>
                    <h4 class="fw-bold mb-1" style="color: #0f172a;">Identitas Pegawai Pemohon</h4>
                    <p class="text-muted mb-4 small">Ketik nama lengkap atau 18 digit NIP Anda untuk mengisi data secara otomatis.</p>

                    <div class="row g-3">
                        <div class="col-md-12">
                            <label class="form-label fw-bold text-secondary small">Pilih Pegawai / Masukkan NIP <span class="text-danger">*</span></label>
                            <select class="form-select select2-pegawai" id="select_ptk" name="ptk_selector" required>
                                <option value="">-- Ketik Nama atau NIP Anda di sini --</option>
                                <?php foreach ($pegawai_list as $p): ?>
                                    <option value="<?= $p->nip ?>" data-id="<?= $p->id ?>" data-nama="<?= htmlspecialchars($p->nama_lengkap) ?>">
                                        <?= htmlspecialchars($p->nama_lengkap) ?> - NIP. <?= $p->nip ?: '(Non-NIP)' ?> (<?= $p->status_kepegawaian ?: 'ASN' ?>)
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>

                    <input type="hidden" name="ptk_id" id="val_ptk_id">

                    <!-- DETAIL PEGAWAI (MUNCUL OTOMATIS) -->
                    <div id="detail_pegawai_box" class="mt-4 pt-4 border-top" style="display: none;">
                        <div class="row g-3">
                            <div class="col-md-4">
                                <label class="form-label text-secondary small fw-bold">Nama Lengkap</label>
                                <input type="text" class="form-control bg-light border-0 fw-bold py-2" id="res_nama" readonly style="border-radius: 12px;">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label text-secondary small fw-bold">NIP</label>
                                <input type="text" class="form-control bg-light border-0 py-2" id="res_nip" readonly style="border-radius: 12px;">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label text-secondary small fw-bold">Status Kepegawaian</label>
                                <input type="text" class="form-control bg-light border-0 py-2" id="res_status" readonly style="border-radius: 12px;">
                            </div>
                            <div class="col-md-5">
                                <label class="form-label text-secondary small fw-bold">Jabatan</label>
                                <input type="text" class="form-control bg-light border-0 py-2" id="res_jabatan" readonly style="border-radius: 12px;">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label text-secondary small fw-bold">Pangkat / Golongan</label>
                                <input type="text" class="form-control bg-light border-0 py-2" id="res_pangkat" readonly style="border-radius: 12px;">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label text-secondary small fw-bold">Masa Kerja (TMT NIP)</label>
                                <input type="text" class="form-control bg-light border-0 fw-bold text-success py-2" id="res_masakerja" readonly style="border-radius: 12px;">
                            </div>
                        </div>

                        <!-- KOTAK REKAPITULASI SALDO CUTI BKN -->
                        <div class="mt-4 p-4 rounded-4" style="background: #f8fafc; border: 1px solid #e2e8f0;">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <div class="fw-bold text-dark" style="font-size: 14.5px;">
                                    <i class="bi bi-calculator text-success me-2"></i>Catatan Saldo Cuti Tahunan (Perka BKN No. 24/2017)
                                </div>
                                <span class="badge bg-success px-3 py-2 rounded-pill fw-bold" id="res_total_badge">Total Hak Cuti: 0 Hari</span>
                            </div>
                            <div class="row g-3">
                                <div class="col-6 col-md-3">
                                    <div class="saldo-box">
                                        <div class="saldo-label">Tahun N-2 (<span id="lbl_thn_n2"><?= date('Y')-2 ?></span>)</div>
                                        <div class="saldo-num" id="res_sisa_n2">-</div>
                                        <div class="small text-muted" style="font-size: 11px;">Sisa (Maks 6 Hari)</div>
                                    </div>
                                </div>
                                <div class="col-6 col-md-3">
                                    <div class="saldo-box">
                                        <div class="saldo-label">Tahun N-1 (<span id="lbl_thn_n1"><?= date('Y')-1 ?></span>)</div>
                                        <div class="saldo-num" id="res_sisa_n1">-</div>
                                        <div class="small text-muted" style="font-size: 11px;">Sisa (Maks 6 Hari)</div>
                                    </div>
                                </div>
                                <div class="col-6 col-md-3">
                                    <div class="saldo-box">
                                        <div class="saldo-label">Tahun N (<span id="lbl_thn_n"><?= date('Y') ?></span>)</div>
                                        <div class="saldo-num" id="res_sisa_n">12</div>
                                        <div class="small text-muted" style="font-size: 11px;">Jatah Berjalan</div>
                                    </div>
                                </div>
                                <div class="col-6 col-md-3">
                                    <div class="saldo-box bg-success-subtle border-success">
                                        <div class="saldo-label text-success">Total Tersedia</div>
                                        <div class="saldo-num text-success" id="res_sisa_total">12</div>
                                        <div class="small text-success fw-bold" style="font-size: 11px;">Hari Kerja Aktif</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>

            <!-- LANGKAH 2: PILIHAN JENIS CUTI RESMI BKN -->
            <div class="card cuti-card-main mb-4">
                <div class="card-body p-4 p-md-5">
                    <div class="d-flex align-items-center gap-2 mb-2">
                        <span class="badge bg-success-subtle text-success px-3 py-2 rounded-pill fw-bold" style="font-size: 12px;">
                            <i class="bi bi-list-check me-1"></i> Langkah 2
                        </span>
                    </div>
                    <h4 class="fw-bold mb-1" style="color: #0f172a;">Jenis Cuti Yang Diambil</h4>
                    <p class="text-muted mb-4 small">Pilih salah satu dari 6 jenis cuti ASN yang diatur pemerintah.</p>

                    <div class="row g-3">
                        <!-- Cuti Tahunan -->
                        <div class="col-md-4 col-sm-6">
                            <div class="jenis-cuti-card active" onclick="pilihJenisCuti('Cuti Tahunan', this)">
                                <div class="d-flex justify-content-between align-items-center mb-1">
                                    <h6 class="fw-bold mb-0 text-success">Cuti Tahunan</h6>
                                    <i class="bi bi-check-circle-fill text-success check-icon fs-5"></i>
                                </div>
                                <small class="text-muted d-block" style="font-size: 12px;">12 hari kerja per tahun. Akumulasi N-1 &amp; N-2 maks 6 hari.</small>
                            </div>
                        </div>
                        <!-- Cuti Besar -->
                        <div class="col-md-4 col-sm-6">
                            <div class="jenis-cuti-card" onclick="pilihJenisCuti('Cuti Besar', this)">
                                <div class="d-flex justify-content-between align-items-center mb-1">
                                    <h6 class="fw-bold mb-0">Cuti Besar</h6>
                                    <i class="bi bi-circle text-muted check-icon fs-5"></i>
                                </div>
                                <small class="text-muted d-block" style="font-size: 12px;">Masa kerja &ge; 5 tahun terus-menerus. Paling lama 3 bulan.</small>
                            </div>
                        </div>
                        <!-- Cuti Sakit -->
                        <div class="col-md-4 col-sm-6">
                            <div class="jenis-cuti-card" onclick="pilihJenisCuti('Cuti Sakit', this)">
                                <div class="d-flex justify-content-between align-items-center mb-1">
                                    <h6 class="fw-bold mb-0">Cuti Sakit</h6>
                                    <i class="bi bi-circle text-muted check-icon fs-5"></i>
                                </div>
                                <small class="text-muted d-block" style="font-size: 12px;">Wajib melampirkan surat keterangan dokter / rumah sakit.</small>
                            </div>
                        </div>
                        <!-- Cuti Melahirkan -->
                        <div class="col-md-4 col-sm-6">
                            <div class="jenis-cuti-card" onclick="pilihJenisCuti('Cuti Melahirkan', this)">
                                <div class="d-flex justify-content-between align-items-center mb-1">
                                    <h6 class="fw-bold mb-0">Cuti Melahirkan</h6>
                                    <i class="bi bi-circle text-muted check-icon fs-5"></i>
                                </div>
                                <small class="text-muted d-block" style="font-size: 12px;">Kelahiran anak ke-1 s.d ke-3. Lamanya 3 bulan penuh.</small>
                            </div>
                        </div>
                        <!-- Cuti Karena Alasan Penting -->
                        <div class="col-md-4 col-sm-6">
                            <div class="jenis-cuti-card" onclick="pilihJenisCuti('Cuti Alasan Penting', this)">
                                <div class="d-flex justify-content-between align-items-center mb-1">
                                    <h6 class="fw-bold mb-0">Cuti Alasan Penting</h6>
                                    <i class="bi bi-circle text-muted check-icon fs-5"></i>
                                </div>
                                <small class="text-muted d-block" style="font-size: 12px;">Keluarga sakit/wafat, nikah, bencana, istri sesar (maks 1 bulan).</small>
                            </div>
                        </div>
                        <!-- Cuti di Luar Tanggungan Negara -->
                        <div class="col-md-4 col-sm-6">
                            <div class="jenis-cuti-card" onclick="pilihJenisCuti('Cuti diluar Tanggungan Negara', this)">
                                <div class="d-flex justify-content-between align-items-center mb-1">
                                    <h6 class="fw-bold mb-0">Cuti di Luar Tanggungan</h6>
                                    <i class="bi bi-circle text-muted check-icon fs-5"></i>
                                </div>
                                <small class="text-muted d-block" style="font-size: 12px;">Masa kerja &ge; 5 tahun untuk alasan mendesak, s.d 3 tahun.</small>
                            </div>
                        </div>
                    </div>

                    <input type="hidden" name="jenis_cuti" id="input_jenis_cuti" value="Cuti Tahunan">

                    <!-- SUB-PILIHAN ALOKASI TAHUN (N / N-1 / N-2) -->
                    <div id="sub_opsi_cuti_tahunan" class="mt-4 p-4 bg-light rounded-4 border">
                        <label class="form-label fw-bold small text-dark mb-2">
                            <i class="bi bi-sliders me-1 text-success"></i>Pilih Sumber Kuota Cuti Tahunan yang Digunakan:
                        </label>
                        <div class="row g-3">
                            <div class="col-md-4">
                                <div class="form-check p-3 bg-white rounded-3 border h-100">
                                    <input class="form-check-input ms-1" type="radio" name="alokasi_cuti_tahunan" id="opt_thn_n" value="Cuti Tahunan" checked onchange="ubahAlokasiCuti(this.value)">
                                    <label class="form-check-label ms-2 small fw-semibold" for="opt_thn_n">
                                        Tahun Berjalan (N / <?= date('Y') ?>)
                                        <div class="text-success fw-bold">Sisa: <span id="lbl_opt_sisa_n">12</span> Hari</div>
                                    </label>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-check p-3 bg-white rounded-3 border h-100" id="box_opt_n1">
                                    <input class="form-check-input ms-1" type="radio" name="alokasi_cuti_tahunan" id="opt_thn_n1" value="Cuti Tahunan N-1" onchange="ubahAlokasiCuti(this.value)">
                                    <label class="form-check-label ms-2 small fw-semibold" for="opt_thn_n1">
                                        Sisa Tahun Lalu (N-1 / <?= date('Y')-1 ?>)
                                        <div class="text-primary fw-bold">Sisa: <span id="lbl_opt_sisa_n1">-</span> Hari</div>
                                    </label>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-check p-3 bg-white rounded-3 border h-100" id="box_opt_n2">
                                    <input class="form-check-input ms-1" type="radio" name="alokasi_cuti_tahunan" id="opt_thn_n2" value="Cuti Tahunan N-2" onchange="ubahAlokasiCuti(this.value)">
                                    <label class="form-check-label ms-2 small fw-semibold" for="opt_thn_n2">
                                        Sisa 2 Thn Lalu (N-2 / <?= date('Y')-2 ?>)
                                        <div class="text-muted fw-bold">Sisa: <span id="lbl_opt_sisa_n2">-</span> Hari</div>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- LANGKAH 3: ALASAN, TANGGAL & DURASI CUTI -->
            <div class="card cuti-card-main mb-4">
                <div class="card-body p-4 p-md-5">
                    <div class="d-flex align-items-center gap-2 mb-2">
                        <span class="badge bg-success-subtle text-success px-3 py-2 rounded-pill fw-bold" style="font-size: 12px;">
                            <i class="bi bi-calendar-range me-1"></i> Langkah 3
                        </span>
                    </div>
                    <h4 class="fw-bold mb-1" style="color: #0f172a;">Waktu, Alasan, dan Durasi Cuti</h4>
                    <p class="text-muted mb-4 small">Lama cuti kerja dihitung otomatis berdasarkan kalender kerja efektif madrasah.</p>

                    <div class="row g-3">
                        <div class="col-md-12">
                            <label class="form-label fw-bold text-secondary small">Alasan Menjalankan Cuti <span class="text-danger">*</span></label>
                            <input type="text" class="form-control bg-light border-0 py-2" name="alasan_cuti" id="input_alasan" placeholder="Contoh: Acara Keluarga / Pernikahan / Menemani Orang Tua Sakit / Melahirkan" required style="border-radius: 12px; font-size: 14px;">
                            <div class="d-flex gap-2 mt-2 flex-wrap">
                                <button type="button" class="btn btn-sm btn-white border rounded-pill px-3 py-1 text-secondary" style="font-size: 12px;" onclick="isiAlasan('Acara Keluarga')">Acara Keluarga</button>
                                <button type="button" class="btn btn-sm btn-white border rounded-pill px-3 py-1 text-secondary" style="font-size: 12px;" onclick="isiAlasan('Pernikahan Keluarga')">Pernikahan Keluarga</button>
                                <button type="button" class="btn btn-sm btn-white border rounded-pill px-3 py-1 text-secondary" style="font-size: 12px;" onclick="isiAlasan('Mengantar anak ke pondok pesantren')">Antar Anak ke Pondok</button>
                                <button type="button" class="btn btn-sm btn-white border rounded-pill px-3 py-1 text-secondary" style="font-size: 12px;" onclick="isiAlasan('Orangtua (Mertua) Sakit')">Orangtua Sakit</button>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label fw-bold text-secondary small">Tanggal Mulai Cuti <span class="text-danger">*</span></label>
                            <input type="date" class="form-control bg-light border-0 py-2" name="tanggal_mulai" id="tgl_mulai" required style="border-radius: 12px; font-size: 14px;" onchange="hitungHariKerja()">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-bold text-secondary small">Tanggal Selesai Cuti <span class="text-danger">*</span></label>
                            <input type="date" class="form-control bg-light border-0 py-2" name="tanggal_selesai" id="tgl_selesai" required style="border-radius: 12px; font-size: 14px;" onchange="hitungHariKerja()">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-bold text-secondary small">Lamanya Cuti</label>
                            <div class="input-group">
                                <input type="number" class="form-control bg-light border-0 fw-bold text-success py-2" name="lama_cuti_jumlah" id="lama_cuti_jumlah" value="1" min="1" required style="border-radius: 12px 0 0 12px; font-size: 15px;">
                                <input type="text" class="form-control bg-light border-0 text-muted py-2" name="lama_cuti_satuan" id="lama_cuti_satuan" value="Hari" readonly style="border-radius: 0 12px 12px 0; max-width: 80px; font-size: 14px;">
                            </div>
                        </div>

                        <!-- INFO KALKULASI HARI KERJA -->
                        <div class="col-12" id="info_hitung_hari" style="display: none;">
                            <div class="p-3 rounded-3 bg-light border text-dark small d-flex align-items-center">
                                <i class="bi bi-info-circle-fill text-success me-2 fs-5"></i>
                                <span id="txt_hitung_hari"></span>
                            </div>
                        </div>

                        <!-- UPLOAD DOKUMEN PENDUKUNG -->
                        <div class="col-md-12 mt-3">
                            <label class="form-label fw-bold text-secondary small">Dokumen Pendukung / Surat Dokter (Opsional)</label>
                            <input type="file" class="form-control bg-light border-0" name="file_lampiran" id="file_lampiran" accept=".pdf,.doc,.docx,.jpg,.jpeg,.png" style="border-radius: 12px; font-size: 13px;">
                            <div class="form-text small text-muted">Format: PDF, JPG, PNG (Maksimal 5MB). Wajib dilampirkan jika mengajukan <strong>Cuti Sakit</strong>.</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- LANGKAH 4: ALAMAT & NOMOR TELEPON SELAMA CUTI -->
            <div class="card cuti-card-main mb-4">
                <div class="card-body p-4 p-md-5">
                    <div class="d-flex align-items-center gap-2 mb-2">
                        <span class="badge bg-success-subtle text-success px-3 py-2 rounded-pill fw-bold" style="font-size: 12px;">
                            <i class="bi bi-geo-alt-fill me-1"></i> Langkah 4
                        </span>
                    </div>
                    <h4 class="fw-bold mb-1" style="color: #0f172a;">Alamat &amp; Kontak Selama Cuti</h4>
                    <p class="text-muted mb-4 small">Alamat dan nomor telepon yang dapat dihubungi selama menjalankan cuti.</p>

                    <div class="row g-3">
                        <div class="col-md-8">
                            <label class="form-label fw-bold text-secondary small">Alamat Lengkap Selama Cuti <span class="text-danger">*</span></label>
                            <textarea class="form-control bg-light border-0 p-3" name="alamat_cuti" id="input_alamat" rows="3" placeholder="Tuliskan nama jalan, RT/RW, desa/kelurahan, kecamatan, kab/kota, provinsi" required style="border-radius: 14px; font-size: 14px;"></textarea>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-bold text-secondary small">Nomor Telepon / WhatsApp <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-0" style="border-radius: 12px 0 0 12px;"><i class="bi bi-whatsapp text-muted"></i></span>
                                <input type="text" class="form-control bg-light border-0 py-2" name="telepon_cuti" id="input_telepon" placeholder="08xxxxxxxxxx" required style="border-radius: 0 12px 12px 0; font-size: 14px;">
                            </div>
                            <div class="form-text small text-muted mt-2">Pastikan nomor aktif dan dapat dihubungi jika ada keperluan mendesak.</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- TOMBOL KIRIM PENGAJUAN -->
            <div class="text-center my-4 pb-4">
                <button type="submit" class="btn btn-success px-5 py-3 fw-bold d-inline-flex align-items-center gap-2 shadow" style="border-radius: 14px; background: #059669; border-color: #059669; font-size: 16px;">
                    <i class="bi bi-send-fill"></i> Kirim Pengajuan Cuti Mandiri
                </button>
                <div class="small text-muted mt-2">
                    <i class="bi bi-info-circle text-primary me-1"></i>Permohonan akan berstatus <strong>Menunggu Persetujuan</strong> dan diverifikasi oleh Kepala Madrasah &amp; Admin TU.
                </div>
            </div>

        </form>

    </div>

    <!-- ── SECTION C: CEK SALDO & LACAK STATUS PRIBADI ── -->
    <div id="section-lacak" style="display: none;">
        <div class="card cuti-card-main mb-4">
            <div class="card-body p-4 p-md-5">
                <div class="d-flex align-items-center gap-2 mb-2">
                    <span class="badge bg-success-subtle text-success px-3 py-2 rounded-pill fw-bold" style="font-size: 12px;">
                        <i class="bi bi-search me-1"></i> Lacak Status &amp; Sisa Cuti
                    </span>
                </div>
                <h4 class="fw-bold mb-1" style="color: #0f172a;">Cek Sisa Jatah Hak Cuti Pribadi (N, N-1, N-2)</h4>
                <p class="text-muted mb-4 small">Masukkan 18 digit NIP Anda untuk melihat status permohonan terkini dan kalkulasi saldo cuti Anda.</p>

                <div class="row g-3 align-items-center mb-4">
                    <div class="col-md-7">
                        <label class="form-label small fw-bold text-secondary">Nomor Induk Pegawai (NIP)</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-0" style="border-radius: 12px 0 0 12px;"><i class="bi bi-person-vcard text-muted"></i></span>
                            <input type="text" class="form-control bg-light border-0 py-2" id="cari_nip_status" placeholder="Masukkan 18 digit NIP Anda..." style="font-size: 14px;">
                            <button class="btn btn-success px-4 fw-bold" type="button" onclick="cekStatusCuti()" style="border-radius: 0 12px 12px 0; background: #059669; border-color: #059669;">
                                <i class="bi bi-search me-1"></i> Cek Saldo &amp; Status
                            </button>
                        </div>
                    </div>
                </div>

                <!-- HASIL PENCARIAN STATUS & SALDO -->
                <div id="hasil_status_box" class="pt-4 border-top" style="display: none;">
                    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-3">
                        <div>
                            <h5 class="fw-bold text-dark mb-0" id="status_nama_pegawai"></h5>
                            <div class="text-muted small" id="status_nip_pegawai"></div>
                        </div>
                        <span class="badge bg-success px-3 py-2 rounded-pill fs-6" id="status_sisa_cuti"></span>
                    </div>

                    <!-- RIWAYAT PERMOHONAN PEGAWAI TERSEBUT -->
                    <div class="table-responsive mt-3" style="border-radius: 14px; border: 1px solid #e2e8f0;">
                        <table class="table table-hover align-middle mb-0" style="font-size: 13px;">
                            <thead class="bg-light">
                                <tr>
                                    <th>No</th>
                                    <th>Tanggal Surat</th>
                                    <th>Jenis Cuti</th>
                                    <th>Durasi</th>
                                    <th>Rentang Waktu</th>
                                    <th>Alasan</th>
                                    <th class="text-center">Status</th>
                                </tr>
                            </thead>
                            <tbody id="body_riwayat_pribadi">
                            </tbody>
                        </table>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <!-- ── SECTION D: KETENTUAN PEMERINTAH (BKN) ── -->
    <div id="section-regulasi" style="display: none;">
        <div class="card cuti-card-main mb-4">
            <div class="card-body p-4 p-md-5">
                <div class="d-flex align-items-center gap-2 mb-2">
                    <span class="badge bg-success-subtle text-success px-3 py-2 rounded-pill fw-bold" style="font-size: 12px;">
                        <i class="bi bi-book-half me-1"></i> Regulasi Resmi BKN
                    </span>
                </div>
                <h4 class="fw-bold mb-1" style="color: #0f172a;">Ketentuan dan Tata Cara Cuti Pegawai ASN</h4>
                <p class="text-muted mb-4 small">Berdasarkan Peraturan BKN No. 24 Tahun 2017 &amp; Peraturan BKN No. 7 Tahun 2021.</p>

                <div class="accordion accordion-flush" id="accordionRegulasi">
                    <!-- 1. Cuti Tahunan -->
                    <div class="accordion-item border-bottom py-2">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed fw-bold text-dark" type="button" data-bs-toggle="collapse" data-bs-target="#reg-1">
                                <i class="bi bi-calendar-check text-success me-2 fs-5"></i> 1. Cuti Tahunan (12 Hari Kerja)
                            </button>
                        </h2>
                        <div id="reg-1" class="accordion-collapse collapse" data-bs-parent="#accordionRegulasi">
                            <div class="accordion-body text-secondary small">
                                <ul>
                                    <li>PNS dan PPPK yang telah bekerja paling kurang 1 (satu) tahun secara terus menerus berhak atas cuti tahunan.</li>
                                    <li>Lamanya hak atas cuti tahunan adalah <strong>12 (dua belas) hari kerja</strong>.</li>
                                    <li>Hak atas cuti tahunan yang tidak digunakan dalam tahun yang bersangkutan dapat digunakan dalam tahun berikutnya paling lama 6 (enam) hari kerja.</li>
                                    <li>Hak atas cuti tahunan yang tidak digunakan 2 (dua) tahun atau lebih berturut-turut dapat digunakan dalam tahun berikutnya untuk paling lama 24 (dua puluh empat) hari kerja termasuk cuti tahunan dalam tahun berjalan.</li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <!-- 2. Cuti Besar -->
                    <div class="accordion-item border-bottom py-2">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed fw-bold text-dark" type="button" data-bs-toggle="collapse" data-bs-target="#reg-2">
                                <i class="bi bi-award text-success me-2 fs-5"></i> 2. Cuti Besar (Maksimal 3 Bulan)
                            </button>
                        </h2>
                        <div id="reg-2" class="accordion-collapse collapse" data-bs-parent="#accordionRegulasi">
                            <div class="accordion-body text-secondary small">
                                <ul>
                                    <li>PNS yang telah bekerja paling singkat 5 (lima) tahun secara terus menerus berhak atas cuti besar paling lama <strong>3 (tiga) bulan</strong>.</li>
                                    <li>PNS yang menggunakan hak atas cuti besar tidak berhak lagi atas cuti tahunan dalam tahun yang bersangkutan.</li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <!-- 3. Cuti Sakit -->
                    <div class="accordion-item border-bottom py-2">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed fw-bold text-dark" type="button" data-bs-toggle="collapse" data-bs-target="#reg-3">
                                <i class="bi bi-heart-pulse text-danger me-2 fs-5"></i> 3. Cuti Sakit (Wajib Surat Dokter)
                            </button>
                        </h2>
                        <div id="reg-3" class="accordion-collapse collapse" data-bs-parent="#accordionRegulasi">
                            <div class="accordion-body text-secondary small">
                                <ul>
                                    <li>Setiap PNS yang menderita sakit berhak atas cuti sakit.</li>
                                    <li>PNS yang sakit 1 (satu) hari menyampaikan surat keterangan sakit tertulis. Sakit lebih dari 1 (satu) hari s.d 14 (empat belas) hari wajib melampirkan surat keterangan dokter.</li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <!-- 4. Cuti Melahirkan -->
                    <div class="accordion-item border-bottom py-2">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed fw-bold text-dark" type="button" data-bs-toggle="collapse" data-bs-target="#reg-4">
                                <i class="bi bi-person-heart text-info me-2 fs-5"></i> 4. Cuti Melahirkan (3 Bulan)
                            </button>
                        </h2>
                        <div id="reg-4" class="accordion-collapse collapse" data-bs-parent="#accordionRegulasi">
                            <div class="accordion-body text-secondary small">
                                <ul>
                                    <li>Untuk kelahiran anak pertama sampai dengan anak ketiga pada saat menjadi PNS/PPPK.</li>
                                    <li>Lamanya cuti melahirkan adalah <strong>3 (tiga) bulan</strong>.</li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <!-- 5. Cuti Alasan Penting -->
                    <div class="accordion-item border-bottom py-2">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed fw-bold text-dark" type="button" data-bs-toggle="collapse" data-bs-target="#reg-5">
                                <i class="bi bi-exclamation-diamond text-warning me-2 fs-5"></i> 5. Cuti Karena Alasan Penting (Maksimal 1 Bulan)
                            </button>
                        </h2>
                        <div id="reg-5" class="accordion-collapse collapse" data-bs-parent="#accordionRegulasi">
                            <div class="accordion-body text-secondary small">
                                <ul>
                                    <li>Ibu, bapak, isteri/suami, anak, adik, kakak, mertua/menantu sakit keras atau meninggal dunia.</li>
                                    <li>PNS melangsungkan perkawinan yang pertama.</li>
                                    <li>Istri melahirkan dengan operasi caesar.</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

</div>

<!-- JQUERY & SELECT2 JS -->
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<script>
// 1. Digital Clock Live (Persis Monitoring KBM)
function updateLiveClock() {
    const now = new Date();
    const h = String(now.getHours()).padStart(2, '0');
    const m = String(now.getMinutes()).padStart(2, '0');
    const s = String(now.getSeconds()).padStart(2, '0');
    
    const clockElem = document.getElementById('liveClockDisplay');
    if(clockElem) clockElem.innerText = h + ':' + m + ':' + s;
}
setInterval(updateLiveClock, 1000);
updateLiveClock();

// Set live date text
const days = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
const months = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
const now = new Date();
const dateStr = days[now.getDay()] + ', ' + now.getDate() + ' ' + months[now.getMonth()] + ' ' + now.getFullYear();
const dateElem = document.getElementById('liveDateDisplay');
if(dateElem) dateElem.innerHTML = '<i class="bi bi-calendar3 me-1"></i> ' + dateStr;

// 2. Switch View Section (Monitoring / Form / Lacak / Regulasi)
function switchViewSection(section) {
    // Hide all
    $('#section-monitoring').hide();
    $('#section-form').hide();
    $('#section-lacak').hide();
    $('#section-regulasi').hide();

    // Remove active pill
    $('.kbm-quick-pill').removeClass('active');

    if (section === 'monitoring') {
        $('#section-monitoring').fadeIn(200);
        $('#pill-monitoring').addClass('active');
        $('#filterSearchRow').show();
        $('#btnGroupTampilan').show();
    } else if (section === 'form') {
        $('#section-form').fadeIn(200);
        $('#pill-form').addClass('active');
        $('#filterSearchRow').hide();
        $('#btnGroupTampilan').hide();
    } else if (section === 'lacak') {
        $('#section-lacak').fadeIn(200);
        $('#pill-lacak').addClass('active');
        $('#filterSearchRow').hide();
        $('#btnGroupTampilan').hide();
    } else if (section === 'regulasi') {
        $('#section-regulasi').fadeIn(200);
        $('#pill-regulasi').addClass('active');
        $('#filterSearchRow').hide();
        $('#btnGroupTampilan').hide();
    }
}

// 3. Layout Toggle (Cards vs Table)
function setMonitoringLayout(mode) {
    if (mode === 'cards') {
        $('#containerCardsView').fadeIn(150);
        $('#containerTableView').hide();
        $('#btnViewCards').addClass('active');
        $('#btnViewTable').removeClass('active');
    } else {
        $('#containerCardsView').hide();
        $('#containerTableView').fadeIn(150);
        $('#btnViewTable').addClass('active');
        $('#btnViewCards').removeClass('active');
    }
}

// 4. Instant Live Search & Filter
function applyMonitoringFilters() {
    const searchVal = $('#liveSearchInput').val().toLowerCase().trim();
    const jenisVal  = $('#filterJenisSelect').val();
    const statusVal = $('#filterStatusSelect').val();

    $('.monitoring-item').each(function() {
        const itemSearch = $(this).attr('data-search') || '';
        const itemJenis  = $(this).attr('data-jenis') || '';
        const itemStatus = $(this).attr('data-status') || '';

        let matchSearch = (searchVal === '' || itemSearch.includes(searchVal));
        let matchJenis  = (jenisVal === '' || itemJenis.includes(jenisVal));
        let matchStatus = (statusVal === '' || itemStatus === statusVal);

        if (matchSearch && matchJenis && matchStatus) {
            $(this).show();
        } else {
            $(this).hide();
        }
    });
}

$('#liveSearchInput').on('input', applyMonitoringFilters);

// 5. Select2 Initialization & Form Logic
$(document).ready(function() {
    $('#select_ptk').select2({
        theme: 'bootstrap-5',
        placeholder: '-- Ketik Nama Lengkap atau NIP Anda --',
        allowClear: true,
        width: '100%'
    });

    $('#select_ptk').on('change', function() {
        const nip = $(this).val();
        if (!nip) {
            $('#detail_pegawai_box').slideUp(200);
            $('#val_ptk_id').val('');
            return;
        }

        // Tampilkan loading skeleton / status
        $('#detail_pegawai_box').slideDown(200);
        $('#res_nama').val('Memuat data...');
        $('#res_nip').val(nip);

        $.ajax({
            url: '<?= base_url("cuti/ajax_get_ptk") ?>',
            type: 'POST',
            data: { nip: nip },
            dataType: 'json',
            success: function(res) {
                if (res.status === 'success') {
                    const d = res.data;
                    $('#val_ptk_id').val(d.id);
                    $('#res_nama').val(d.nama_lengkap);
                    $('#res_nip').val(d.nip || '-');
                    $('#res_status').val(d.status_kepegawaian);
                    $('#res_jabatan').val(d.jabatan);
                    $('#res_pangkat').val(d.pangkat_golongan);
                    $('#res_masakerja').val(d.masa_kerja || '-');

                    // Alamat & No HP jika ada
                    if (d.alamat && !$('#input_alamat').val()) $('#input_alamat').val(d.alamat);
                    if (d.no_hp && !$('#input_telepon').val()) $('#input_telepon').val(d.no_hp);

                    // Saldo Cuti
                    const s = d.saldo;
                    $('#res_sisa_n2').text(s.sisa_n2);
                    $('#res_sisa_n1').text(s.sisa_n1);
                    $('#res_sisa_n').text(s.sisa_n);
                    $('#res_sisa_total').text(s.total_tersedia);
                    $('#res_total_badge').text('Total Hak Cuti: ' + s.total_tersedia + ' Hari');

                    // Label Opsi Cuti Tahunan
                    $('#lbl_opt_sisa_n').text(s.sisa_n);
                    $('#lbl_opt_sisa_n1').text(s.sisa_n1);
                    $('#lbl_opt_sisa_n2').text(s.sisa_n2);

                    if (s.sisa_n1 <= 0) {
                        $('#box_opt_n1').addClass('opacity-50');
                        $('#opt_thn_n1').prop('disabled', true);
                    } else {
                        $('#box_opt_n1').removeClass('opacity-50');
                        $('#opt_thn_n1').prop('disabled', false);
                    }

                    if (s.sisa_n2 <= 0) {
                        $('#box_opt_n2').addClass('opacity-50');
                        $('#opt_thn_n2').prop('disabled', true);
                    } else {
                        $('#box_opt_n2').removeClass('opacity-50');
                        $('#opt_thn_n2').prop('disabled', false);
                    }

                    hitungHariKerja();
                } else {
                    alert(res.message);
                    $('#detail_pegawai_box').slideUp(200);
                }
            },
            error: function() {
                alert('Gagal menghubungi server untuk mengambil data pegawai.');
            }
        });
    });
});

// Pilih Jenis Cuti
function pilihJenisCuti(jenis, el) {
    $('.jenis-cuti-card').removeClass('active');
    $('.jenis-cuti-card .check-icon').removeClass('bi-check-circle-fill text-success').addClass('bi-circle text-muted');

    $(el).addClass('active');
    $(el).find('.check-icon').removeClass('bi-circle text-muted').addClass('bi-check-circle-fill text-success');

    $('#input_jenis_cuti').val(jenis);

    if (jenis === 'Cuti Tahunan') {
        $('#sub_opsi_cuti_tahunan').slideDown(200);
    } else {
        $('#sub_opsi_cuti_tahunan').slideUp(200);
    }

    hitungHariKerja();
}

function ubahAlokasiCuti(val) {
    $('#input_jenis_cuti').val(val);
}

function isiAlasan(text) {
    $('#input_alasan').val(text);
}

// Hitung Hari Kerja Otomatis
function hitungHariKerja() {
    const tglMulai   = $('#tgl_mulai').val();
    const tglSelesai = $('#tgl_selesai').val();
    const jenisCuti  = $('#input_jenis_cuti').val();

    if (!tglMulai || !tglSelesai) return;

    $.ajax({
        url: '<?= base_url("cuti/ajax_hitung_hari") ?>',
        type: 'POST',
        data: {
            tanggal_mulai: tglMulai,
            tanggal_selesai: tglSelesai,
            jenis_cuti: jenisCuti
        },
        dataType: 'json',
        success: function(res) {
            if (res.status === 'success') {
                $('#lama_cuti_jumlah').val(res.hari_rekomendasi);
                $('#info_hitung_hari').show();

                let infoTxt = `Dihitung <strong>${res.hari_rekomendasi} hari kerja efektif</strong> (dari total ${res.calendar_days} hari kalender). Akhir pekan (Sabtu & Minggu) dan Libur Nasional otomatis dilewati.`;
                if (res.holidays && res.holidays.length > 0) {
                    infoTxt += `<br><span class="text-danger fw-bold"><i class="bi bi-calendar-event me-1"></i>Termasuk Libur Resmi:</span> ` + res.holidays.join(', ');
                }
                $('#txt_hitung_hari').html(infoTxt);
            }
        }
    });
}

// Cek Status & Saldo Cuti Pribadi
function cekStatusCuti() {
    const nip = $('#cari_nip_status').val().trim();
    if (!nip) {
        alert('Silakan masukkan 18 digit NIP Anda.');
        return;
    }

    $.ajax({
        url: '<?= base_url("cuti/ajax_cek_status") ?>',
        type: 'POST',
        data: { nip: nip },
        dataType: 'json',
        success: function(res) {
            if (res.status === 'success') {
                $('#hasil_status_box').slideDown(200);
                $('#status_nama_pegawai').text(res.data.ptk.nama_lengkap);
                $('#status_nip_pegawai').text('NIP. ' + res.data.ptk.nip + ' (' + res.data.ptk.status_kepegawaian + ')');

                const s = res.data.saldo;
                $('#status_sisa_cuti').text('Sisa Cuti ' + <?= date('Y') ?> + ': ' + s.sisa_n + ' Hari (Total: ' + s.total_tersedia + ' Hari)');

                let rowsHtml = '';
                if (!res.data.riwayat || res.data.riwayat.length === 0) {
                    rowsHtml = '<tr><td colspan="7" class="text-center text-muted py-3">Belum ada riwayat permohonan cuti untuk pegawai ini.</td></tr>';
                } else {
                    res.data.riwayat.forEach(function(item, idx) {
                        let stBadge = '<span class="badge bg-warning text-dark">Menunggu TU</span>';
                        if (item.status === 'Aktif') stBadge = '<span class="badge bg-success">Disetujui (Aktif)</span>';
                        else if (item.status === 'Disetujui Kepala') stBadge = '<span class="badge bg-info text-dark">Disetujui Kamad</span>';
                        else if (item.status === 'Ditolak') stBadge = '<span class="badge bg-danger">Ditolak</span>';

                        rowsHtml += `
                            <tr>
                                <td>${idx + 1}</td>
                                <td>${item.tanggal_surat || '-'}</td>
                                <td><strong>${item.jenis_cuti}</strong></td>
                                <td>${item.lama_cuti_jumlah} ${item.lama_cuti_satuan}</td>
                                <td>${item.tanggal_mulai} s.d ${item.tanggal_selesai}</td>
                                <td>${item.alasan_cuti || '-'}</td>
                                <td class="text-center">${stBadge}</td>
                            </tr>
                        `;
                    });
                }
                $('#body_riwayat_pribadi').html(rowsHtml);
            } else {
                alert(res.message);
                $('#hasil_status_box').slideUp(200);
            }
        },
        error: function() {
            alert('Gagal menghubungi server.');
        }
    });
}

// Sinkron Google Sheet (Khusus Admin)
function syncGoogleSheet(btn) {
    const $btn = $(btn);
    const origHtml = $btn.html();
    $btn.prop('disabled', true).html('<i class="bi bi-arrow-repeat me-1 spinner-border spinner-border-sm"></i> Menyinkronkan...');

    $.ajax({
        url: '<?= base_url("cuti/sync_kuota_sheet") ?>',
        type: 'POST',
        dataType: 'json',
        success: function(res) {
            $btn.prop('disabled', false).html(origHtml);
            alert(res.message);
            const curNip = $('#select_ptk').val();
            if (curNip) {
                $('#select_ptk').trigger('change');
            }
        },
        error: function() {
            $btn.prop('disabled', false).html(origHtml);
            alert('Gagal menghubungi server untuk sinkronisasi Google Spreadsheet.');
        }
    });
}
</script>

<?php 
// Load Footer Resmi Website Madrasah
$this->load->view('public/partials/archive_footer'); 
?>
