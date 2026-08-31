<?php $this->load->view('public/partials/archive_header'); ?>

<style>
:root{
    --live-green:#16a34a;
    --live-amber:#f59e0b;
    --live-blue:#2563eb;
    --live-red:#ef4444;
}

.kbm-hero{
    background:
        radial-gradient(circle at 10% 20%, rgba(255,255,255,0.18), transparent 30%),
        radial-gradient(circle at 90% 80%, rgba(34,197,94,0.3), transparent 40%),
        linear-gradient(135deg, #0f4422 0%, #15803d 50%, #16a34a 100%);
    color: white;
    border-radius: 28px;
    padding: 28px 32px;
    margin-bottom: 24px;
    box-shadow: 0 20px 45px rgba(22,163,74,0.22);
    position: relative;
    overflow: hidden;
}

.kbm-hero::after{
    content: '';
    position: absolute;
    right: -40px;
    bottom: -40px;
    width: 220px;
    height: 220px;
    background: radial-gradient(circle, rgba(255,255,255,0.15), transparent 70%);
    border-radius: 50%;
}

.kbm-hero-top{
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
    gap: 16px;
    position: relative;
    z-index: 2;
}

.live-pulse-badge{
    display: inline-flex;
    align-items: center;
    gap: 8px;
    background: rgba(0,0,0,0.25);
    backdrop-filter: blur(10px);
    border: 1px solid rgba(255,255,255,0.3);
    padding: 6px 14px;
    border-radius: 999px;
    font-size: 12px;
    font-weight: 800;
    letter-spacing: 0.5px;
    text-transform: uppercase;
}

.pulse-dot{
    width: 10px;
    height: 10px;
    background-color: #22c55e;
    border-radius: 50%;
    box-shadow: 0 0 0 0 rgba(34, 197, 94, 0.7);
    animation: livePulse 1.6s infinite;
    display: inline-block;
}

.pulse-dot-red{
    background-color: #ef4444 !important;
    box-shadow: 0 0 0 0 rgba(239, 68, 68, 0.7) !important;
    animation: livePulseRed 1.6s infinite !important;
}

@keyframes livePulse {
    0% { transform: scale(0.95); box-shadow: 0 0 0 0 rgba(34, 197, 94, 0.7); }
    70% { transform: scale(1); box-shadow: 0 0 0 10px rgba(34, 197, 94, 0); }
    100% { transform: scale(0.95); box-shadow: 0 0 0 0 rgba(34, 197, 94, 0); }
}

@keyframes livePulseRed {
    0% { transform: scale(0.95); box-shadow: 0 0 0 0 rgba(239, 68, 68, 0.7); }
    70% { transform: scale(1); box-shadow: 0 0 0 10px rgba(239, 68, 68, 0); }
    100% { transform: scale(0.95); box-shadow: 0 0 0 0 rgba(239, 68, 68, 0); }
}

.live-clock{
    font-size: 32px;
    font-weight: 900;
    letter-spacing: 1px;
    font-family: 'Courier New', Courier, monospace;
    background: rgba(255,255,255,0.15);
    padding: 4px 16px;
    border-radius: 16px;
    border: 1px solid rgba(255,255,255,0.2);
    display: inline-block;
}

.current-jam-alert{
    background: rgba(255,255,255,0.15);
    border: 1px solid rgba(255,255,255,0.3);
    backdrop-filter: blur(12px);
    border-radius: 16px;
    padding: 12px 18px;
    margin-top: 18px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    flex-wrap: wrap;
    gap: 12px;
    position: relative;
    z-index: 2;
}

.kbm-stats-grid{
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 14px;
    margin-bottom: 24px;
}

.kbm-stat-card{
    background: white;
    border-radius: 20px;
    padding: 18px 20px;
    border: 1px solid #e2e8f0;
    box-shadow: 0 10px 25px rgba(15,23,42,0.04);
    display: flex;
    align-items: center;
    gap: 16px;
}

.kbm-stat-icon{
    width: 48px;
    height: 48px;
    border-radius: 14px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 22px;
}

.kbm-stat-card small{
    display: block;
    font-size: 12px;
    font-weight: 800;
    color: #64748b;
    text-transform: uppercase;
}

.kbm-stat-card strong{
    display: block;
    font-size: 24px;
    font-weight: 900;
    color: #0f172a;
    line-height: 1.1;
    margin-top: 3px;
}

.kbm-filter-bar{
    background: white;
    border: 1px solid #e2e8f0;
    border-radius: 22px;
    padding: 20px;
    margin-bottom: 24px;
    box-shadow: 0 8px 25px rgba(15,23,42,0.04);
}

.kbm-quick-pills{
    display: flex;
    gap: 8px;
    overflow-x: auto;
    white-space: nowrap;
    padding-bottom: 6px;
    margin-bottom: 16px;
}

.kbm-quick-pill{
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 8px 16px;
    border-radius: 999px;
    font-size: 13px;
    font-weight: 800;
    background: #f8fafc;
    border: 1px solid #e2e8f0;
    color: #475569;
    text-decoration: none;
    transition: all 0.2s ease;
}

.kbm-quick-pill:hover{
    background: #ecfdf5;
    color: #166534;
    border-color: #bbf7d0;
}

.kbm-quick-pill.active{
    background: #16a34a;
    color: white;
    border-color: #16a34a;
    box-shadow: 0 4px 12px rgba(22,163,74,0.3);
}

.kbm-grid{
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
    gap: 16px;
    margin-bottom: 30px;
}

.kbm-card{
    background: white;
    border-radius: 22px;
    border: 1px solid #e2e8f0;
    padding: 18px;
    box-shadow: 0 10px 24px rgba(15,23,42,0.04);
    transition: transform 0.15s ease, box-shadow 0.15s ease;
    position: relative;
    overflow: hidden;
}

.kbm-card:hover{
    transform: translateY(-2px);
    box-shadow: 0 14px 30px rgba(15,23,42,0.08);
}

/* KARTU AKTIF SEKARANG (HIGHLIGHT) */
.kbm-card.card-active-now{
    border: 2px solid #22c55e !important;
    background: linear-gradient(180deg, #f0fdf4 0%, #ffffff 40%) !important;
    box-shadow: 0 14px 35px rgba(34,197,94,0.18) !important;
}

.live-active-tag{
    background: #15803d;
    color: white;
    font-size: 11px;
    font-weight: 900;
    padding: 4px 10px;
    border-radius: 8px;
    display: inline-flex;
    align-items: center;
    gap: 6px;
    margin-bottom: 10px;
    letter-spacing: 0.5px;
}

.kbm-card-head{
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    gap: 10px;
    border-bottom: 1px dashed #e2e8f0;
    padding-bottom: 12px;
    margin-bottom: 12px;
}

.kbm-kelas-badge{
    background: #0f172a;
    color: white;
    font-weight: 900;
    padding: 6px 12px;
    border-radius: 12px;
    font-size: 13px;
}

.kbm-jam-badge{
    background: #f1f5f9;
    color: #475569;
    font-weight: 800;
    font-size: 11.5px;
    padding: 4px 10px;
    border-radius: 8px;
    border: 1px solid #e2e8f0;
}

.kbm-jam-badge.jam-active-highlight{
    background: #dcfce7;
    color: #166534;
    border-color: #86efac;
    font-weight: 900;
}

.kbm-mapel{
    font-size: 16px;
    font-weight: 900;
    color: #0f172a;
    margin-bottom: 4px;
}

.kbm-guru{
    font-size: 13.5px;
    font-weight: 700;
    color: #334155;
    display: flex;
    align-items: center;
    gap: 6px;
}

.kbm-status-pill{
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 6px 12px;
    border-radius: 999px;
    font-size: 12px;
    font-weight: 800;
}

.pill-hadir{
    background: #dcfce7;
    color: #166534;
    border: 1px solid #bbf7d0;
}

.pill-tugas-luar{
    background: #fef3c7;
    color: #92400e;
    border: 1px solid #fde68a;
}

.pill-cuti{
    background: #ede9fe;
    color: #5b21b6;
    border: 1px solid #ddd6fe;
}

.pill-sakit{
    background: #fee2e2;
    color: #991b1b;
    border: 1px solid #fecaca;
}

.pill-pending{
    background: #f8fafc;
    color: #64748b;
    border: 1px solid #e2e8f0;
}

/* ═══════════════════════════════════════════════════════
   MODE DISPLAY TV (FOKUS 100% KARTU KBM - PORTRAIT 32" & LANDSCAPE)
   ═══════════════════════════════════════════════════════ */
.tv-mode-header {
    display: none;
}

.tv-mode-active {
    position: fixed !important;
    top: 0 !important; 
    left: 0 !important; 
    right: 0 !important; 
    bottom: 0 !important;
    z-index: 99999999 !important;
    background: #090d16 !important;
    color: white !important;
    overflow-y: auto !important;
    padding: 16px 20px !important;
    margin: 0 !important;
    max-width: 100% !important;
    width: 100vw !important;
    height: 100vh !important;
}

/* SEMBUNYIKAN SEMUA ELEMEN NON-CARD DI MODE TV */
.tv-mode-active .kbm-hero,
.tv-mode-active .kbm-stats-grid,
.tv-mode-active .kbm-filter-bar,
.tv-mode-active header,
.tv-mode-active footer,
.tv-mode-active .public-navbar {
    display: none !important;
}

.tv-mode-active .tv-mode-header {
    display: block !important;
    background: #111a2c;
    border: 1px solid #1e2d44;
    border-radius: 16px;
    padding: 12px 20px;
    margin-bottom: 14px;
}

.tv-mode-active #sectionCardsView {
    display: block !important;
}

.tv-mode-active .kbm-grid {
    display: grid !important;
    grid-template-columns: repeat(auto-fill, minmax(280px, 1fr)) !important;
    gap: 12px !important;
    margin-bottom: 0 !important;
}

.tv-mode-active .kbm-card {
    background: #111a2c !important;
    border: 1px solid #1e2d44 !important;
    color: white !important;
    padding: 14px 16px !important;
    border-radius: 16px !important;
    box-shadow: 0 4px 15px rgba(0,0,0,0.3) !important;
}

.tv-mode-active .kbm-card.card-active-now {
    border: 1.5px solid #22c55e !important;
    background: linear-gradient(180deg, #102d1d 0%, #111a2c 65%) !important;
    box-shadow: 0 0 16px rgba(34,197,94,0.2) !important;
}

.tv-mode-active .kbm-mapel {
    color: #f8fafc !important;
    font-size: 15px !important;
}

.tv-mode-active .kbm-guru {
    color: #cbd5e1 !important;
    font-size: 12.5px !important;
}

.tv-mode-active .kbm-card-head {
    border-color: #1e2d44 !important;
    padding-bottom: 8px !important;
    margin-bottom: 8px !important;
}

.tv-mode-active .kbm-kelas-badge {
    background: #090d16 !important;
    border: 1px solid #24344d !important;
    font-size: 12px !important;
    padding: 4px 10px !important;
}

.tv-mode-active .kbm-jam-badge {
    background: #1e293b !important;
    color: #94a3b8 !important;
    border-color: #334155 !important;
    font-size: 11px !important;
    padding: 3px 8px !important;
}

.tv-mode-active .kbm-jam-badge.jam-active-highlight {
    background: #064e3b !important;
    color: #6ee7b7 !important;
    border-color: #059669 !important;
}

/* KHUSUS MODE TV PORTRAIT (Orientasi Vertikal TV 32" 1080x1920 Kiosk) */
@media (orientation: portrait), (max-aspect-ratio: 1/1) {
    .tv-mode-active {
        padding: 12px 14px !important;
    }

    .tv-mode-active .kbm-grid {
        grid-template-columns: repeat(2, 1fr) !important;
        gap: 10px !important;
    }
    
    .tv-mode-active .kbm-card {
        padding: 10px 12px !important;
        border-radius: 12px !important;
    }
    
    .tv-mode-active .live-active-tag {
        font-size: 9.5px !important;
        padding: 2px 6px !important;
        margin-bottom: 6px !important;
    }
    
    .tv-mode-active .kbm-mapel {
        font-size: 13.5px !important;
        margin-bottom: 2px !important;
    }
    
    .tv-mode-active .kbm-guru {
        font-size: 11px !important;
    }

    .tv-mode-active .kbm-status-pill {
        font-size: 10.5px !important;
        padding: 3px 8px !important;
    }
}

/* UNTUK LAYAR BESAR TV 32" / 43" 1080p Horizontal (Landscape) */
@media (min-width: 1400px) and (orientation: landscape) {
    .tv-mode-active .kbm-grid {
        grid-template-columns: repeat(4, 1fr) !important;
        gap: 14px !important;
    }
}

@media(max-width: 992px){
    .kbm-stats-grid{
        grid-template-columns: repeat(2, 1fr);
    }
}

@media(max-width: 576px){
    .kbm-stats-grid{
        grid-template-columns: 1fr;
    }
    .kbm-hero{
        padding: 20px;
    }
}
</style>

<div class="container py-4" id="kbmMonitoringWrapper">

    <!-- ═══ HEADER KHUSUS MODE TV (MINIMALIST LIVE HUD) ═══ -->
    <div class="tv-mode-header">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
            <div class="d-flex align-items-center gap-3">
                <span class="live-pulse-badge" style="background: rgba(34,197,94,0.2); border-color: #22c55e; color: #4ade80;">
                    <span class="pulse-dot"></span> LIVE MONITORING KBM PIKET
                </span>
                <span class="badge bg-success bg-opacity-20 text-success border border-success border-opacity-25 px-3 py-2 fw-bold" style="font-size: 13px;">
                    ⏱️ <?= $jam_aktif_label ?>
                </span>
            </div>

            <div class="d-flex align-items-center gap-3">
                <div class="font-monospace fw-bold text-white fs-4" id="tvLiveClock">00:00:00</div>
                <div class="text-white small fw-bold opacity-75 d-none d-sm-block">
                    <?= $hari ?>, <?= date('d M Y', strtotime($tanggal)) ?>
                </div>
                <button type="button" class="btn btn-sm btn-outline-danger fw-bold rounded-pill px-3" onclick="toggleTvMode()">
                    <i class="bi bi-fullscreen-exit me-1"></i> Keluar TV Mode
                </button>
            </div>
        </div>
    </div>

    <!-- HERO HEADER (HANYA MUNCUL DI MODE NORMAL) -->
    <div class="kbm-hero">
        <div class="kbm-hero-top">
            <div>
                <div class="live-pulse-badge mb-2">
                    <span class="pulse-dot"></span> Real-Time Live Monitoring KBM
                </div>
                <h2 style="font-weight: 900; margin: 0; letter-spacing: -0.5px;">Monitoring Jadwal Mengajar &amp; Guru Piket</h2>
                <p style="margin: 6px 0 0; opacity: 0.9; font-size: 14px;">
                    <?= $nama_madrasah ?> • Tahun Pelajaran <?= $tahun_ajaran ?> (Semester <?= $semester ?>)
                </p>
            </div>

            <div class="text-end">
                <div class="live-clock" id="liveClockDisplay">00:00:00</div>
                <div style="font-size: 13px; font-weight: 700; margin-top: 4px; opacity: 0.95;">
                    <?= $hari ?>, <?= date('d F Y', strtotime($tanggal)) ?>
                </div>
            </div>
        </div>

        <!-- STATUS JAM KEAKTIFAN SEKARANG -->
        <div class="current-jam-alert">
            <div class="d-flex align-items-center gap-3">
                <div style="font-size: 28px; line-height: 1;">
                    <?= $jam_ke_aktif ? '⏱️' : '☕' ?>
                </div>
                <div>
                    <div style="font-size: 11.5px; font-weight: 800; text-transform: uppercase; letter-spacing: 0.5px; opacity: 0.85;">
                        Status Waktu KBM Saat Ini (<?= date('H:i') ?> WITA/WIB)
                    </div>
                    <div style="font-size: 16.5px; font-weight: 900; letter-spacing: -0.3px;">
                        <?= $jam_aktif_label ?>
                    </div>
                </div>
            </div>

            <div class="d-flex align-items-center gap-2">
                <?php if($is_today && $jam_ke_aktif !== null): ?>
                    <span class="badge rounded-pill bg-success text-white px-3 py-2 fw-bold">
                        <span class="pulse-dot me-1" style="background:#fff;"></span> Sedang Berlangsung: Jam Ke-<?= $jam_ke_aktif ?>
                    </span>
                <?php else: ?>
                    <span class="badge rounded-pill bg-light text-dark px-3 py-2 fw-bold">
                        <?= $jam_aktif_label ?>
                    </span>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- STATS COUNTER (HANYA MUNCUL DI MODE NORMAL) -->
    <div class="kbm-stats-grid">
        <div class="kbm-stat-card">
            <div class="kbm-stat-icon" style="background:#eff6ff; color:#2563eb;">
                <i class="bi bi-calendar-check-fill"></i>
            </div>
            <div>
                <small>Total Sesi Jadwal</small>
                <strong><?= $total_jadwal ?> Sesi</strong>
            </div>
        </div>

        <div class="kbm-stat-card">
            <div class="kbm-stat-icon" style="background:#ecfdf5; color:#16a34a;">
                <i class="bi bi-person-check-fill"></i>
            </div>
            <div>
                <small>KBM Aktif / Hadir</small>
                <strong style="color:#16a34a;"><?= $count_hadir ?> Kelas</strong>
            </div>
        </div>

        <div class="kbm-stat-card">
            <div class="kbm-stat-icon" style="background:#fef3c7; color:#d97706;">
                <i class="bi bi-briefcase-fill"></i>
            </div>
            <div>
                <small>Tugas Luar / Izin / Cuti</small>
                <strong style="color:#d97706;"><?= $count_tu ?> Guru</strong>
            </div>
        </div>

        <div class="kbm-stat-card">
            <div class="kbm-stat-icon" style="background:#f8fafc; color:#64748b;">
                <i class="bi bi-clock-history"></i>
            </div>
            <div>
                <small>Sesi Belum Diisi</small>
                <strong style="color:#0f172a;"><?= $count_pending ?> Kelas</strong>
            </div>
        </div>
    </div>

    <!-- TOOLBAR FILTER & CONTROLS (HANYA MUNCUL DI MODE NORMAL) -->
    <div class="kbm-filter-bar">

        <!-- QUICK TABS -->
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-3">
            <div class="kbm-quick-pills mb-0">
                <a href="<?= base_url('website/monitoring_kbm?tanggal='.$tanggal) ?>" 
                   class="kbm-quick-pill <?= empty($tingkat) && empty($only_active) && empty($filter_jam) ? 'active' : '' ?>">
                    <i class="bi bi-grid me-1"></i> Semua KBM (<?= $total_jadwal ?>)
                </a>

                <?php if($is_today && $jam_ke_aktif !== null): ?>
                    <a href="<?= base_url('website/monitoring_kbm?tanggal='.$tanggal.'&only_active=1') ?>" 
                       class="kbm-quick-pill <?= !empty($only_active) ? 'active' : '' ?>" style="border-color:#86efac;">
                        <span class="pulse-dot me-1"></span> 🔥 Aktif Jam Ke-<?= $jam_ke_aktif ?> Sekarang (<?= $count_active_now ?>)
                    </a>
                <?php endif; ?>

                <a href="<?= base_url('website/monitoring_kbm?tanggal='.$tanggal.'&tingkat=X') ?>" 
                   class="kbm-quick-pill <?= $tingkat == 'X' || $tingkat == '10' ? 'active' : '' ?>">
                    Kelas X (10)
                </a>
                <a href="<?= base_url('website/monitoring_kbm?tanggal='.$tanggal.'&tingkat=XI') ?>" 
                   class="kbm-quick-pill <?= $tingkat == 'XI' || $tingkat == '11' ? 'active' : '' ?>">
                    Kelas XI (11)
                </a>
                <a href="<?= base_url('website/monitoring_kbm?tanggal='.$tanggal.'&tingkat=XII') ?>" 
                   class="kbm-quick-pill <?= $tingkat == 'XII' || $tingkat == '12' ? 'active' : '' ?>">
                    Kelas XII (12)
                </a>
            </div>

            <div>
                <button type="button" class="btn btn-sm btn-dark rounded-pill px-3 fw-bold shadow-sm" onclick="toggleTvMode()">
                    <i class="bi bi-display me-1 text-success"></i> Aktifkan Mode TV
                </button>
            </div>
        </div>

        <form method="get" action="<?= base_url('website/monitoring_kbm') ?>" id="filterForm">
            <div class="row g-3 align-items-center">
                <div class="col-lg-3 col-md-4">
                    <label class="form-label small fw-bold text-muted text-uppercase mb-1">Tanggal KBM</label>
                    <input type="date" name="tanggal" value="<?= $tanggal ?>" class="form-control form-control-sm rounded-3 fw-bold" onchange="this.form.submit()">
                </div>

                <div class="col-lg-3 col-md-4">
                    <label class="form-label small fw-bold text-muted text-uppercase mb-1">Tingkat</label>
                    <select name="tingkat" class="form-select form-select-sm rounded-3 fw-bold" onchange="this.form.submit()">
                        <option value="">Semua Tingkat</option>
                        <option value="X" <?= $tingkat == 'X' || $tingkat == '10' ? 'selected' : '' ?>>Kelas X (10)</option>
                        <option value="XI" <?= $tingkat == 'XI' || $tingkat == '11' ? 'selected' : '' ?>>Kelas XI (11)</option>
                        <option value="XII" <?= $tingkat == 'XII' || $tingkat == '12' ? 'selected' : '' ?>>Kelas XII (12)</option>
                    </select>
                </div>

                <div class="col-lg-3 col-md-4">
                    <label class="form-label small fw-bold text-muted text-uppercase mb-1">Pilih Kelas</label>
                    <select name="kelas_id" class="form-select form-select-sm rounded-3 fw-bold" onchange="this.form.submit()">
                        <option value="">Semua Kelas (<?= count($kelas_list) ?> Kelas)</option>
                        <?php foreach($kelas_list as $k): ?>
                            <option value="<?= $k->id ?>" <?= $kelas_id == $k->id ? 'selected' : '' ?>><?= $k->nama_kelas ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="col-lg-3 col-md-12">
                    <label class="form-label small fw-bold text-muted text-uppercase mb-1">Pilih Jam Ke-</label>
                    <select name="jam_ke" class="form-select form-select-sm rounded-3 fw-bold" onchange="this.form.submit()">
                        <option value="">Semua Jam</option>
                        <?php if(!empty($jam_slots)): ?>
                            <?php foreach($jam_slots as $js): ?>
                                <option value="<?= $js->jam_ke ?>" <?= $filter_jam == $js->jam_ke ? 'selected' : '' ?>>
                                    Jam Ke-<?= $js->jam_ke ?> (<?= substr($js->jam_mulai,0,5) ?>)
                                </option>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <?php for($i=1; $i<=10; $i++): ?>
                                <option value="<?= $i ?>" <?= $filter_jam == $i ? 'selected' : '' ?>>Jam Ke-<?= $i ?></option>
                            <?php endfor; ?>
                        <?php endif; ?>
                    </select>
                </div>
            </div>
        </form>

        <div class="mt-3 pt-3 border-top d-flex justify-content-between align-items-center flex-wrap gap-2">
            <div class="d-flex align-items-center gap-2 flex-grow-1" style="max-width: 400px;">
                <i class="bi bi-search text-muted"></i>
                <input type="text" id="liveSearchInput" class="form-control form-control-sm border-0 bg-light rounded-pill px-3" placeholder="Ketik cepat nama guru atau mata pelajaran...">
            </div>

            <div class="d-flex align-items-center gap-2">
                <span class="badge bg-success bg-opacity-10 text-success fw-bold px-2 py-1"><i class="bi bi-check-circle-fill me-1"></i> Hadir</span>
                <span class="badge bg-warning bg-opacity-10 text-warning fw-bold px-2 py-1"><i class="bi bi-briefcase-fill me-1"></i> Tugas Luar</span>
                <span class="badge bg-danger bg-opacity-10 text-danger fw-bold px-2 py-1"><i class="bi bi-heart-pulse-fill me-1"></i> Sakit</span>
                <span class="badge bg-purple bg-opacity-10 text-primary fw-bold px-2 py-1"><i class="bi bi-calendar-x-fill me-1"></i> Cuti</span>
            </div>
        </div>
    </div>

    <!-- ═══ GRID KARTU KBM (TAMPILAN UTAMA DI MODE BIASA MAUPUN MODE TV) ═══ -->
    <div id="sectionCardsView">
        <?php if(!empty($jadwal_list)): ?>
            <div class="kbm-grid" id="kbmCardGrid">
                <?php foreach($jadwal_list as $j): ?>
                    <?php
                        $isHadir = (!empty($j->absen_id) && $j->status_input == 'Terkirim');
                        $isTugasLuar = (isset($j->status_input) && $j->status_input == 'Tugas Luar');
                        $isIzin = (isset($j->status_input) && ($j->status_input == 'Izin Guru' || $j->status_input == 'Izin'));
                        $isSakit = (isset($j->status_input) && $j->status_input == 'Sakit');
                        $isCuti = (isset($j->status_input) && $j->status_input == 'Sedang Cuti');

                        $isActiveNow = !empty($j->is_active_now);
                        $searchable = strtolower($j->nama_kelas . ' ' . $j->nama_mapel . ' ' . $j->nama_guru . ' jam ' . $j->daftar_jam);
                    ?>
                    <div class="kbm-card <?= $isActiveNow ? 'card-active-now' : '' ?>" data-search="<?= htmlspecialchars($searchable) ?>">
                        
                        <?php if($isActiveNow): ?>
                            <div class="live-active-tag">
                                <span class="pulse-dot" style="background:#fff;"></span> SEDANG AKTIF SAAT INI (JAM KE-<?= $jam_ke_aktif ?>)
                            </div>
                        <?php endif; ?>

                        <div class="kbm-card-head">
                            <span class="kbm-kelas-badge">
                                <i class="bi bi-door-open me-1"></i> <?= $j->nama_kelas ?>
                            </span>
                            <span class="kbm-jam-badge <?= $isActiveNow ? 'jam-active-highlight' : '' ?>">
                                <i class="bi bi-clock me-1"></i> Jam <?= $j->daftar_jam ?> <?= !empty($j->jam_rentang) ? '('.$j->jam_rentang.')' : '' ?>
                            </span>
                        </div>

                        <div class="kbm-mapel <?= $isActiveNow ? 'text-success' : '' ?>">
                            <?= $j->nama_mapel ?>
                        </div>

                        <div class="kbm-guru">
                            <i class="bi bi-person-circle text-muted"></i> 
                            <?= $j->nama_guru ?>
                        </div>

                        <!-- STATUS BADGE PRESENSI -->
                        <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mt-3 pt-2 border-top">
                            <div>
                                <?php if($isHadir): ?>
                                    <span class="kbm-status-pill pill-hadir">
                                        <i class="bi bi-check-circle-fill"></i> KBM Hadir
                                    </span>
                                <?php elseif($isTugasLuar): ?>
                                    <span class="kbm-status-pill pill-tugas-luar">
                                        <i class="bi bi-briefcase-fill"></i> Tugas Luar
                                    </span>
                                <?php elseif($isSakit): ?>
                                    <span class="kbm-status-pill pill-sakit">
                                        <i class="bi bi-heart-pulse-fill"></i> Sakit
                                    </span>
                                <?php elseif($isCuti): ?>
                                    <span class="kbm-status-pill pill-cuti">
                                        <i class="bi bi-calendar-x-fill"></i> Cuti
                                    </span>
                                <?php elseif($isIzin): ?>
                                    <span class="kbm-status-pill pill-tugas-luar">
                                        <i class="bi bi-envelope-paper-fill"></i> Izin
                                    </span>
                                <?php else: ?>
                                    <span class="kbm-status-pill pill-pending">
                                        <i class="bi bi-hourglass-split"></i> Belum Diisi
                                    </span>
                                <?php endif; ?>
                            </div>
                        </div>

                        <?php if(!empty($j->materi_pembahasan)): ?>
                            <div class="small text-muted mt-2 pt-2 border-top">
                                <strong class="text-dark"><i class="bi bi-journal-text me-1"></i> Materi:</strong> 
                                <?= htmlspecialchars($j->materi_pembahasan) ?>
                            </div>
                        <?php endif; ?>

                        <?php if(!empty($j->keterangan_status) && in_array($j->status_input, ['Tugas Luar', 'Izin Guru', 'Sakit', 'Sedang Cuti'])): ?>
                            <div class="small text-muted mt-1 fst-italic">
                                <?= htmlspecialchars($j->keterangan_status) ?>
                            </div>
                        <?php endif; ?>

                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <div class="text-center py-5 bg-white rounded-4 border shadow-sm my-4">
                <i class="bi bi-calendar-x text-muted" style="font-size: 48px;"></i>
                <h5 class="fw-bold mt-3 text-dark">Tidak Ada Jadwal KBM Sesuai Filter</h5>
                <p class="text-muted small">Tidak ditemukan sesi KBM untuk kriteria kelas/tingkat/jam terpilih pada hari <?= $hari ?>, <?= date('d/m/Y', strtotime($tanggal)) ?>.</p>
                <a href="<?= base_url('website/monitoring_kbm?tanggal='.$tanggal) ?>" class="btn btn-sm btn-success rounded-3 mt-2">
                    Tampilkan Semua Jadwal Hari Ini
                </a>
            </div>
        <?php endif; ?>
    </div>

</div>

<script>
// Digital Clock Live
function updateLiveClock(){
    const now = new Date();
    const h = String(now.getHours()).padStart(2, '0');
    const m = String(now.getMinutes()).padStart(2, '0');
    const s = String(now.getSeconds()).padStart(2, '0');
    
    const clockElem = document.getElementById('liveClockDisplay');
    if(clockElem) clockElem.innerText = h + ':' + m + ':' + s;

    const tvClock = document.getElementById('tvLiveClock');
    if(tvClock) tvClock.innerText = h + ':' + m + ':' + s;
}
setInterval(updateLiveClock, 1000);
updateLiveClock();

// Live Instant Filter
const searchInput = document.getElementById('liveSearchInput');
if(searchInput){
    searchInput.addEventListener('input', function(){
        const val = this.value.toLowerCase().trim();
        const cards = document.querySelectorAll('.kbm-card');
        cards.forEach(card => {
            const searchData = card.getAttribute('data-search') || '';
            if(searchData.includes(val)){
                card.style.display = 'block';
            } else {
                card.style.display = 'none';
            }
        });
    });
}

// Toggle Fullscreen TV Mode
function toggleTvMode(){
    const wrapper = document.getElementById('kbmMonitoringWrapper');
    if(!wrapper) return;

    wrapper.classList.toggle('tv-mode-active');
    
    if(wrapper.classList.contains('tv-mode-active')){
        if (document.documentElement.requestFullscreen) {
            document.documentElement.requestFullscreen().catch(err => {});
        }
    } else {
        if (document.exitFullscreen) {
            document.exitFullscreen().catch(err => {});
        }
    }
}
</script>

<?php $this->load->view('public/partials/archive_footer'); ?>
