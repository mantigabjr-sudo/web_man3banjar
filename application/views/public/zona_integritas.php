<?php
$page_title = 'Portal Eviden Zona Integritas (WBK/WBBM)';
$this->load->view('public/partials/archive_header');

// Helper metadata file
if(!function_exists('get_zi_file_meta')){
    function get_zi_file_meta($d){
        $info = [
            'ext'         => 'file',
            'icon'        => 'bi-file-earmark-fill',
            'color'       => '#059669',
            'bg'          => '#ecfdf5',
            'badge_class' => 'bg-success-subtle text-success',
            'is_link'     => false,
            'is_pdf'      => false,
            'url'         => '#',
            'size'        => '-'
        ];

        if(!empty($d->tipe_sumber) && $d->tipe_sumber === 'drive_link'){
            $info['ext']         = 'drive';
            $info['icon']        = 'bi-google';
            $info['color']       = '#10b981';
            $info['bg']          = '#ecfdf5';
            $info['badge_class'] = 'bg-success text-white';
            $info['is_link']     = true;
            $info['url']         = $d->link_drive ?? '#';
            $info['size']        = 'Google Cloud';
            return $info;
        }

        if(!empty($d->file_path)){
            $ext = strtolower(pathinfo($d->file_path, PATHINFO_EXTENSION));
            $info['ext'] = $ext;
            // Prioritaskan direktori assets/downloads/ tempat penyimpanan berkas resmi
            $rel_path = 'assets/downloads/' . $d->file_path;
            if(!file_exists(FCPATH . $rel_path) && file_exists(FCPATH . 'uploads/download/' . $d->file_path)){
                $rel_path = 'uploads/download/' . $d->file_path;
            }

            $info['url'] = base_url($rel_path);

            $abs = FCPATH . $rel_path;
            if(file_exists($abs)){
                $bytes = @filesize($abs);
                if($bytes >= 1048576){
                    $info['size'] = round($bytes / 1048576, 2) . ' MB';
                } elseif($bytes >= 1024){
                    $info['size'] = round($bytes / 1024, 1) . ' KB';
                } else {
                    $info['size'] = $bytes . ' B';
                }
            }

            if($ext === 'pdf'){
                $info['icon']        = 'bi-file-earmark-pdf-fill';
                $info['color']       = '#dc2626';
                $info['bg']          = '#fef2f2';
                $info['badge_class'] = 'bg-danger-subtle text-danger';
                $info['is_pdf']      = true;
            } elseif(in_array($ext, ['doc','docx'])){
                $info['icon']        = 'bi-file-earmark-word-fill';
                $info['color']       = '#2563eb';
                $info['bg']          = '#eff6ff';
                $info['badge_class'] = 'bg-primary-subtle text-primary';
            } elseif(in_array($ext, ['xls','xlsx'])){
                $info['icon']        = 'bi-file-earmark-excel-fill';
                $info['color']       = '#059669';
                $info['bg']          = '#ecfdf5';
                $info['badge_class'] = 'bg-success-subtle text-success';
            } elseif(in_array($ext, ['ppt','pptx'])){
                $info['icon']        = 'bi-file-earmark-ppt-fill';
                $info['color']       = '#ea580c';
                $info['bg']          = '#fff7ed';
                $info['badge_class'] = 'bg-warning-subtle text-warning';
            } elseif(in_array($ext, ['zip','rar'])){
                $info['icon']        = 'bi-file-earmark-zip-fill';
                $info['color']       = '#7c3aed';
                $info['bg']          = '#f5f3ff';
                $info['badge_class'] = 'bg-purple-subtle text-purple';
            } elseif(in_array($ext, ['jpg','jpeg','png','webp'])){
                $info['icon']        = 'bi-file-earmark-image-fill';
                $info['color']       = '#0284c7';
                $info['bg']          = '#e0f2fe';
                $info['badge_class'] = 'bg-info-subtle text-info';
            }

            $info['can_preview'] = in_array($ext, ['pdf', 'doc', 'docx', 'xls', 'xlsx', 'ppt', 'pptx', 'jpg', 'jpeg', 'png', 'webp']);
        }

        return $info;
    }
}

$area_names = [
    'shared' => 'Dokumen Bersama / Induk ZI',
    'area1'  => 'Pokja I: Manajemen Perubahan',
    'area2'  => 'Pokja II: Penataan Tatalaksana',
    'area3'  => 'Pokja III: Penataan Manajemen SDM',
    'area4'  => 'Pokja IV: Penguatan Akuntabilitas',
    'area5'  => 'Pokja V: Penguatan Pengawasan',
    'area6'  => 'Pokja VI: Peningkatan Kualitas Pelayanan Publik'
];

$active_area = isset($active_area) ? $active_area : 'all';
$active_view = isset($active_view) ? $active_view : 'list';
$is_user_logged_in = (bool)$this->session->userdata('logged_in');
$logged_user_name = $this->session->userdata('username') ?? '';
?>

<style>
:root {
    --zi-primary: #059669;
    --zi-dark: #064e3b;
    --zi-accent: #10b981;
    --zi-gold: #f59e0b;
}

.zi-hero-section {
    background: radial-gradient(circle at 85% 15%, rgba(245, 158, 11, 0.25), transparent 45%),
                radial-gradient(circle at 15% 85%, rgba(6, 78, 59, 0.5), transparent 50%),
                linear-gradient(135deg, #064e3b 0%, #059669 65%, #10b981 100%);
    color: #ffffff;
    padding: 55px 0 80px 0;
    position: relative;
    overflow: hidden;
}

.zi-hero-section::after {
    content: '';
    position: absolute;
    bottom: -1px;
    left: 0;
    right: 0;
    height: 30px;
    background: #f8fafc;
    border-radius: 30px 30px 0 0;
}

.zi-hero-badge {
    background: rgba(255, 255, 255, 0.18);
    backdrop-filter: blur(10px);
    border: 1px solid rgba(255, 255, 255, 0.35);
    color: #ffffff;
    font-size: 12px;
    font-weight: 700;
    padding: 6px 16px;
    border-radius: 999px;
    display: inline-flex;
    align-items: center;
    gap: 8px;
    margin-bottom: 12px;
}

.zi-kpi-summary-strip {
    background: #ffffff;
    border-radius: 20px;
    padding: 16px 24px;
    margin-top: -45px;
    position: relative;
    z-index: 10;
    box-shadow: 0 10px 30px -10px rgba(6, 78, 59, 0.12), 0 0 0 1px rgba(226, 232, 240, 0.8);
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(130px, 1fr));
    gap: 12px;
}

.zi-kpi-item {
    text-align: center;
    padding: 8px 12px;
    border-radius: 12px;
    transition: all 0.2s ease;
    border: 1px solid transparent;
    cursor: pointer;
    text-decoration: none;
    display: block;
}

.zi-kpi-item:hover, .zi-kpi-item.active {
    background-color: #ecfdf5;
    border-color: #a7f3d0;
}

.zi-kpi-num {
    font-size: 22px;
    font-weight: 800;
    color: #064e3b;
    line-height: 1.2;
}

.zi-kpi-label {
    font-size: 11px;
    font-weight: 700;
    color: #64748b;
    text-transform: uppercase;
    letter-spacing: 0.3px;
}

/* 6 Area Filter Grid */
.zi-area-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 10px;
    margin-bottom: 24px;
}

@media (max-width: 992px) {
    .zi-area-grid {
        grid-template-columns: repeat(2, 1fr);
    }
}
@media (max-width: 576px) {
    .zi-area-grid {
        grid-template-columns: 1fr;
    }
}

.zi-area-card {
    background: #ffffff;
    border: 1px solid #e2e8f0;
    border-radius: 14px;
    padding: 12px 14px;
    text-decoration: none;
    color: #334155;
    display: flex;
    align-items: center;
    gap: 10px;
    transition: all 0.2s ease;
}

.zi-area-card:hover {
    border-color: #059669;
    background-color: #f0fdf4;
    color: #064e3b;
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(5, 150, 105, 0.1);
}

.zi-area-card.active {
    background: linear-gradient(135deg, #064e3b 0%, #059669 100%);
    border-color: #064e3b;
    color: #ffffff;
    box-shadow: 0 6px 16px -2px rgba(6, 78, 59, 0.3);
}

.zi-area-card.active .badge-count {
    background: rgba(255, 255, 255, 0.25);
    color: #ffffff;
}

.zi-area-card .badge-count {
    background: #e2e8f0;
    color: #475569;
    border-radius: 999px;
    font-size: 11px;
    font-weight: 700;
    padding: 3px 8px;
    margin-left: auto;
}

/* Toolbar & Cards */
.zi-toolbar {
    background: #ffffff;
    border-radius: 16px;
    padding: 14px 18px;
    border: 1px solid #e2e8f0;
    margin-bottom: 24px;
    display: flex;
    flex-wrap: wrap;
    align-items: center;
    justify-content: space-between;
    gap: 12px;
}

.zi-file-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
    gap: 18px;
}

.zi-empty-state {
    grid-column: 1 / -1 !important;
    width: 100% !important;
    text-align: center;
    padding: 3.5rem 1rem;
    margin: 0 auto;
}

.zi-file-card {
    background: #ffffff;
    border: 1px solid #e2e8f0;
    border-radius: 18px;
    padding: 18px;
    transition: all 0.25s ease;
    display: flex;
    flex-direction: column;
    justify-content: space-between;
    position: relative;
}

.zi-file-card:hover {
    border-color: #10b981;
    transform: translateY(-4px);
    box-shadow: 0 12px 25px -5px rgba(6, 78, 59, 0.12);
}

.file-type-iconbox {
    width: 46px;
    height: 46px;
    border-radius: 12px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    font-size: 22px;
}

.file-badge-pill {
    font-size: 11px;
    font-weight: 700;
    padding: 4px 10px;
    border-radius: 999px;
    letter-spacing: 0.3px;
}

.file-title-link {
    color: #0f172a;
    font-weight: 700;
    font-size: 14.5px;
    line-height: 1.45;
    text-decoration: none;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
    margin: 10px 0 8px 0;
}

.file-title-link:hover {
    color: #059669;
}

.file-meta-box {
    background-color: #f8fafc;
    border-radius: 12px;
    padding: 10px 12px;
    font-size: 12px;
    margin-top: 10px;
}

.file-meta-row {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 2px 0;
}

/* ═══ DRAG & DROP UPLOAD ZONE ═══ */
.zi-dropzone {
    border: 2px dashed #93c5fd;
    border-radius: 16px;
    background: #f8fafc;
    padding: 24px 20px;
    text-align: center;
    cursor: pointer;
    transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
    position: relative;
    user-select: none;
}

.zi-dropzone:hover {
    border-color: #059669;
    background: #f0fdf4;
    transform: translateY(-1px);
    box-shadow: 0 4px 14px rgba(5, 150, 105, 0.08);
}

.zi-dropzone.is-dragover {
    border-color: #059669 !important;
    background: #ecfdf5 !important;
    border-style: solid !important;
    transform: scale(1.01);
    box-shadow: 0 0 0 4px rgba(16, 185, 129, 0.2), 0 8px 24px rgba(5, 150, 105, 0.15) !important;
}

.zi-dropzone-icon {
    width: 52px;
    height: 52px;
    border-radius: 14px;
    background: #e0f2fe;
    color: #0284c7;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    font-size: 26px;
    margin-bottom: 10px;
    transition: transform 0.2s ease, background 0.2s ease;
}

.zi-dropzone:hover .zi-dropzone-icon,
.zi-dropzone.is-dragover .zi-dropzone-icon {
    transform: translateY(-3px) scale(1.08);
}

.zi-dropzone.is-dragover .zi-dropzone-icon {
    background: #d1fae5;
    color: #059669;
}

.zi-dropzone-file-selected {
    background: #ffffff;
    border: 1px solid #e2e8f0;
    border-radius: 12px;
    padding: 12px 14px;
    text-align: left;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.04);
}

/* ═══ MONITORING PMPZI KEMENAG STYLES ═══ */
.pmpzi-matrix-card {
    background: #ffffff;
    border: 1px solid #cbd5e1;
    border-radius: 20px;
    box-shadow: 0 10px 30px -5px rgba(0,0,0,0.06);
    overflow: hidden;
}
.pmpzi-header-banner {
    background: linear-gradient(135deg, #064e3b 0%, #047857 50%, #059669 100%);
    color: #ffffff;
    padding: 24px 28px;
    position: relative;
}
.pmpzi-table {
    margin-bottom: 0;
    font-size: 13.5px;
    border-collapse: separate;
    border-spacing: 0;
}
.pmpzi-table thead th {
    background-color: #f8fafc;
    color: #475569;
    font-weight: 700;
    font-size: 12px;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    padding: 14px 18px;
    border-bottom: 2px solid #cbd5e1;
}
.pmpzi-table tbody td {
    padding: 13px 18px;
    vertical-align: middle;
    border-bottom: 1px solid #f1f5f9;
}
.pmpzi-section-title-row {
    background: #f1f5f9 !important;
    font-weight: 800;
    color: #1e293b;
    font-size: 13px;
    letter-spacing: 0.3px;
}
.pmpzi-subtotal-row {
    background: #e2e8f0 !important;
    font-weight: 800;
    color: #0f172a;
    font-size: 13.5px;
}
.pmpzi-total-row {
    background: #cbd5e1 !important;
    font-weight: 900;
    color: #0f172a;
    font-size: 14.5px;
}
.pmpzi-btn-ev {
    background: #f59e0b;
    color: #ffffff;
    font-weight: 800;
    font-size: 11px;
    padding: 3px 9px;
    border-radius: 6px;
    border: none;
    display: inline-flex;
    align-items: center;
    gap: 4px;
    cursor: pointer;
    transition: all 0.2s ease;
    text-decoration: none;
}
.pmpzi-btn-ev:hover {
    background: #d97706;
    color: #ffffff;
    transform: translateY(-1px) scale(1.05);
    box-shadow: 0 4px 10px rgba(245, 158, 11, 0.3);
}
.pmpzi-btn-pokja {
    background: #0284c7;
    color: #ffffff;
    font-weight: 600;
    font-size: 13px;
    padding: 6px 12px;
    border-radius: 8px;
    display: inline-flex;
    align-items: center;
    gap: 6px;
    border: none;
    text-decoration: none;
    transition: all 0.2s;
}
.pmpzi-btn-pokja:hover {
    background: #0369a1;
    color: #ffffff;
    transform: translateY(-1px);
}
.pmpzi-score-val {
    font-family: monospace;
    font-weight: 700;
    font-size: 14px;
    color: #0f172a;
}
.pmpzi-progress-wrap {
    display: flex;
    align-items: center;
    gap: 10px;
}
.pmpzi-progress-bar-bg {
    width: 90px;
    height: 8px;
    background-color: #e2e8f0;
    border-radius: 999px;
    overflow: hidden;
}
.pmpzi-progress-bar-fill {
    height: 100%;
    border-radius: 999px;
}
.pmpzi-badge-pct {
    font-family: monospace;
    font-size: 12.5px;
    font-weight: 700;
}
.pmpzi-kpi-card {
    border-radius: 16px;
    border: 1px solid #e2e8f0;
    background: #ffffff;
    padding: 18px 20px;
    box-shadow: 0 4px 12px rgba(0,0,0,0.03);
    transition: transform 0.2s;
}
.pmpzi-kpi-card:hover {
    transform: translateY(-2px);
}
</style>

<!-- ═══ 1. HERO HEADER ZONA INTEGRITAS ═══ -->
<section class="zi-hero-section">
    <div class="container position-relative">
        <div class="row align-items-center g-4">
            <div class="col-lg-8">
                <span class="zi-hero-badge">
                    <i class="bi bi-shield-check text-warning"></i> LEMBAR KERJA EVALUASI (LKE) ZI WBK / WBBM
                </span>
                <h1 class="fw-bold mb-2 text-white display-6">
                    Portal Eviden Zona Integritas
                </h1>
                <p class="text-white-50 mb-4" style="font-size: 15px; max-width: 680px; line-height: 1.6;">
                    Pusat penyimpanan eviden digital penilaian mandiri pembangunan Zona Integritas (WBK/WBBM) <strong>MAN 3 Banjar</strong>. Mencakup 6 Area Perubahan reformasi birokrasi madrasah.
                </p>
                <div class="d-flex flex-wrap gap-2 align-items-center">
                    <span class="badge bg-warning text-dark px-3 py-2 rounded-pill fw-bold">
                        <i class="bi bi-unlock-fill me-1"></i> Sesi Akses Terbuka
                    </span>
                    <a href="<?= base_url('website/lock_zi') ?>" class="btn btn-outline-light rounded-pill px-3 py-2 fw-semibold" onclick="return confirm('Kunci kembali akses portal eviden ZI?')">
                        <i class="bi bi-lock-fill me-1"></i> Kunci Sesi Kembali
                    </a>
                    <a href="<?= base_url('website/download') ?>" class="btn btn-link text-white-50 text-decoration-none px-2 small">
                        <i class="bi bi-arrow-left me-1"></i> Kembali ke Berkas Publik
                    </a>
                </div>
            </div>
            <div class="col-lg-4 text-lg-end">
                <button type="button" class="btn btn-light text-success fw-bold rounded-pill px-4 py-3 shadow-lg d-inline-flex align-items-center gap-2" data-bs-toggle="modal" data-bs-target="#modalUploadZi">
                    <i class="bi bi-cloud-arrow-up-fill fs-5"></i>
                    <span>+ Unggah Eviden ZI</span>
                </button>
            </div>
        </div>
    </div>
</section>

<!-- ═══ SUB-NAV TAMPILAN: MONITORING PMPZI VS ARSIP BERKAS ═══ -->
<div class="container my-4">
    <div class="p-3 bg-white rounded-4 shadow-sm border border-slate-200">
        <div class="d-flex flex-wrap align-items-center justify-content-between gap-3">
            <div class="d-flex flex-wrap align-items-center gap-2">
                <span class="badge bg-success-subtle text-success px-3 py-2 rounded-pill fw-bold" style="font-size: 12.5px;">
                    <i class="bi bi-shield-fill-check me-1"></i> Instrumen PMPZI Kemenag RI 2026
                </span>
                <span class="text-muted small d-none d-md-inline">
                    Satuan Kerja: <strong>MAN 3 Banjar</strong> (Kode Satker: <code>309442</code>)
                </span>
            </div>

            <div class="d-flex flex-wrap align-items-center gap-2.5">
                <!-- Nav Pills View Switcher -->
                <div class="nav nav-pills bg-light p-1 rounded-pill shadow-xs" role="tablist">
                    <button type="button" class="nav-link <?= ($main_tab ?? 'monitoring') === 'monitoring' ? 'active bg-success text-white' : 'text-dark' ?> rounded-pill fw-bold px-3 py-1.5 d-inline-flex align-items-center gap-2 transition" id="ziTabMonitoringBtn" onclick="switchZiMainView('monitoring')">
                        <i class="bi bi-speedometer2 text-warning fs-6"></i>
                        <span>Monitoring LKE</span>
                        <span class="badge <?= ($main_tab ?? 'monitoring') === 'monitoring' ? 'bg-white text-success' : 'bg-success text-white' ?> rounded-pill font-monospace" style="font-size: 11px;"><?= $pmpzi_matrix['indeks_satker'] ?? '68.67' ?></span>
                    </button>
                    <button type="button" class="nav-link <?= ($main_tab ?? 'monitoring') === 'berkas' ? 'active bg-primary text-white' : 'text-dark' ?> rounded-pill fw-bold px-3 py-1.5 d-inline-flex align-items-center gap-2 transition" id="ziTabBerkasBtn" onclick="switchZiMainView('berkas')">
                        <i class="bi bi-folder2-open text-primary fs-6"></i>
                        <span>Arsip Eviden</span>
                        <span class="badge <?= ($main_tab ?? 'monitoring') === 'berkas' ? 'bg-white text-primary' : 'bg-primary text-white' ?> rounded-pill font-monospace" style="font-size: 11px;"><?= $zi_stats['total'] ?? 0 ?> Berkas</span>
                    </button>
                </div>

                <!-- Tombol Sinkronisasi PMPZI dengan Opsi Rollback (HANYA MUNCUL JIKA ADMIN LOGIN) -->
                <?php if (!empty($is_user_logged_in)): ?>
                <div class="btn-group shadow-xs">
                    <button type="button" class="btn btn-success rounded-start-pill fw-bold px-3 py-1.5 d-inline-flex align-items-center gap-2" id="btnMainSyncPmpzi" onclick="startPmpziSync(false)">
                        <i class="bi bi-arrow-repeat fs-6" id="iconSyncSpinner"></i>
                        <span id="textBtnSync">Sinkronkan PMPZI</span>
                    </button>
                    <button type="button" class="btn btn-success dropdown-toggle dropdown-toggle-split rounded-end-pill px-2.5 py-1.5" data-bs-toggle="dropdown" aria-expanded="false" title="Pilihan Sinkronisasi & Rollback">
                        <span class="visually-hidden">Pilihan Sinkronisasi</span>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end shadow-lg border-0 rounded-4 p-2 mt-1" style="min-width: 280px; z-index: 1050;">
                        <li>
                            <button class="dropdown-item rounded-3 py-2 fw-semibold d-flex align-items-center gap-2.5 text-success" type="button" onclick="startPmpziSync(false)">
                                <i class="bi bi-cloud-arrow-down-fill fs-5 text-success"></i>
                                <div>
                                    <div class="lh-1 text-dark">Sinkronkan Sekarang</div>
                                    <small class="text-muted fw-normal" style="font-size: 11px;">Tarik skor nilai & berkas fisik terbaru</small>
                                </div>
                            </button>
                        </li>
                        <li><hr class="dropdown-divider my-1"></li>
                        <li>
                            <button class="dropdown-item rounded-3 py-2 fw-semibold d-flex align-items-center gap-2.5 text-danger" type="button" onclick="confirmPmpziRollback()">
                                <i class="bi bi-arrow-counterclockwise fs-5 text-danger"></i>
                                <div>
                                    <div class="lh-1 text-danger">Rollback Sinkronisasi</div>
                                    <small class="text-muted fw-normal" style="font-size: 11px;">Pulihkan database ke kondisi awal</small>
                                </div>
                            </button>
                        </li>
                    </ul>
                </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Strip Informasi Status Auto-Sync Terkini -->
        <div class="d-flex flex-wrap align-items-center justify-content-between gap-2 pt-2.5 mt-2.5 border-top border-slate-100 small text-muted">
            <div class="d-flex flex-wrap align-items-center gap-2">
                <span class="badge bg-success-subtle text-success border border-success-subtle rounded-pill px-2.5 py-1 fw-bold" style="font-size: 11px;">
                    <i class="bi bi-broadcast me-1"></i> Auto-Sync: Aktif Otomatis
                </span>
                <span style="font-size: 12px;">
                    Terakhir disinkronkan: <strong class="text-dark font-monospace" id="pmpziLastSyncDisplay"><?= htmlspecialchars($pmpzi_sync_meta['last_sync_formatted'] ?? '13 Okt 2026, 14:02 WITA') ?></strong>
                </span>
            </div>
            <div class="text-muted small d-none d-sm-inline" style="font-size: 11.5px;">
                <i class="bi bi-shield-check text-success me-1"></i> Nilai LKE & Eviden tersambung langsung dengan portal resmi PMPZI Kemenag RI
            </div>
        </div>
    </div>
</div>

<!-- ═══ 2. SECTION MONITORING PMPZI KEMENAG (SESUAI PERMENPAN 90/2021) ═══ -->
<div id="ziSectionMonitoring" style="display: <?= ($main_tab ?? 'monitoring') === 'monitoring' ? 'block' : 'none' ?>;">
    <div class="container pb-5">
        
        <!-- KPI Strip Ringkasan Capaian -->
        <div class="row g-3 mb-4">
            <div class="col-6 col-lg-3">
                <div class="pmpzi-kpi-card text-center">
                    <span class="small text-muted text-uppercase fw-bold d-block mb-1" style="font-size: 11px; letter-spacing: 0.5px;">Indeks PMPZI Satker</span>
                    <div class="fs-2 fw-bold text-success font-monospace mb-1"><?= $pmpzi_matrix['indeks_satker'] ?? '68.67' ?></div>
                    <span class="badge bg-success-subtle text-success rounded-pill" style="font-size: 11px;">Memenuhi Syarat WBK</span>
                </div>
            </div>
            <div class="col-6 col-lg-3">
                <div class="pmpzi-kpi-card text-center">
                    <span class="small text-muted text-uppercase fw-bold d-block mb-1" style="font-size: 11px; letter-spacing: 0.5px;">Komponen Pengungkit (60)</span>
                    <div class="fs-2 fw-bold text-primary font-monospace mb-1"><?= $pmpzi_matrix['pengungkit']['nilai'] ?? '38.67' ?></div>
                    <span class="badge bg-primary-subtle text-primary rounded-pill font-monospace" style="font-size: 11px;"><?= $pmpzi_matrix['pengungkit']['persen'] ?? '64.45' ?>% Capaian</span>
                </div>
            </div>
            <div class="col-6 col-lg-3">
                <div class="pmpzi-kpi-card text-center">
                    <span class="small text-muted text-uppercase fw-bold d-block mb-1" style="font-size: 11px; letter-spacing: 0.5px;">Komponen Hasil (40)</span>
                    <div class="fs-2 fw-bold text-info font-monospace mb-1"><?= $pmpzi_matrix['hasil']['nilai'] ?? '30.00' ?></div>
                    <span class="badge bg-info-subtle text-info rounded-pill font-monospace" style="font-size: 11px;"><?= $pmpzi_matrix['hasil']['persen'] ?? '75.00' ?>% Capaian</span>
                </div>
            </div>
            <div class="col-6 col-lg-3">
                <div class="pmpzi-kpi-card text-center">
                    <span class="small text-muted text-uppercase fw-bold d-block mb-1" style="font-size: 11px; letter-spacing: 0.5px;">Eviden Terunggah LabSys</span>
                    <div class="fs-2 fw-bold text-warning font-monospace mb-1"><?= $zi_stats['total'] ?? 0 ?></div>
                    <span class="badge bg-warning-subtle text-warning-emphasis rounded-pill" style="font-size: 11px;">+<?= $zi_stats['shared'] ?? 0 ?> Dok. Bersama</span>
                </div>
            </div>
        </div>

        <!-- Matriks Tabel Monitoring PMPZI -->
        <div class="pmpzi-matrix-card">
            
            <!-- Banner Header PMPZI -->
            <div class="pmpzi-header-banner d-flex flex-wrap align-items-center justify-content-between gap-3">
                <div>
                    <div class="d-flex align-items-center gap-2 mb-1">
                        <span class="badge bg-warning text-dark fw-bold rounded-pill px-2.5 py-1" style="font-size: 11px;">
                            <i class="bi bi-patch-check-fill me-1"></i> Resmi Kemenag RI
                        </span>
                        <span class="badge bg-danger text-white fw-bold rounded-pill px-2.5 py-1" style="font-size: 11px;">
                            Sesuai Permenpan 90 Tahun 2021
                        </span>
                    </div>
                    <h4 class="fw-bold mb-1 text-white">Penilaian Mandiri Pembangunan Zona Integritas Tahun 2026</h4>
                    <p class="small text-white-50 mb-0">Satuan Kerja: <strong><?= $pmpzi_matrix['satker'] ?? 'MADRASAH ALIYAH NEGERI 3 BANJAR' ?></strong> (Kode: <?= $pmpzi_matrix['kode_satker'] ?? '309442' ?>)</p>
                </div>
                <div class="text-end">
                    <button type="button" class="btn btn-light text-success fw-bold rounded-pill px-3 py-2 btn-sm shadow-xs" data-bs-toggle="modal" data-bs-target="#modalUploadZi">
                        <i class="bi bi-cloud-arrow-up-fill me-1"></i> Unggah Eviden Pokja
                    </button>
                </div>
            </div>

            <!-- Tabel 1: Komponen Pengungkit (60) -->
            <div class="table-responsive">
                <table class="table pmpzi-table align-middle">
                    <thead>
                        <tr>
                            <th class="text-center" style="width: 60px;">No.</th>
                            <th>Komponen Pengungkit (Bobot 60)</th>
                            <th class="text-center" style="width: 120px;">Nilai</th>
                            <th class="text-center" style="width: 200px;">Capaian (%)</th>
                            <th class="text-center" style="width: 140px;">Berkas LabSys</th>
                            <th class="text-end" style="width: 150px;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        
                        <!-- ═══ HEADER ROW: ASPEK PEMENUHAN ═══ -->
                        <tr class="pmpzi-section-title-row">
                            <td colspan="6" class="py-2.5 px-3">
                                <div class="d-flex align-items-center justify-content-between">
                                    <span><i class="bi bi-bookmark-check-fill text-success me-2"></i>ASPEK PEMENUHAN (BOBOT 30)</span>
                                    <span class="badge bg-white text-dark border font-monospace">Subtotal Target: 30.00</span>
                                </div>
                            </td>
                        </tr>

                        <?php if(!empty($pmpzi_matrix['pengungkit']['pemenuhan']['items'])): ?>
                            <?php foreach($pmpzi_matrix['pengungkit']['pemenuhan']['items'] as $it): 
                                $pct = (float)$it['persen'];
                                $bar_color = ($pct >= 80) ? 'bg-success' : (($pct >= 60) ? 'bg-primary' : (($pct >= 40) ? 'bg-warning' : 'bg-danger'));
                                $badge_color = ($pct >= 80) ? 'text-success bg-success-subtle' : (($pct >= 60) ? 'text-primary bg-primary-subtle' : (($pct >= 40) ? 'text-warning bg-warning-subtle' : 'text-danger bg-danger-subtle'));
                            ?>
                                <tr>
                                    <td class="text-center fw-bold text-muted"><?= $it['no'] ?></td>
                                    <td>
                                        <div class="d-flex align-items-center flex-wrap gap-2">
                                            <a href="#" onclick="openPmpziDetailModal('<?= $it['kode_ev'] ?>', '<?= htmlspecialchars($it['nama'], ENT_QUOTES) ?>', 'Pemenuhan', '<?= $it['nilai'] ?>', '<?= $it['persen'] ?>'); return false;" class="pmpzi-btn-pokja">
                                                <span><?= htmlspecialchars($it['nama']) ?> (<?= $it['bobot'] ?>)</span>
                                            </a>
                                            <button type="button" class="pmpzi-btn-ev" title="Lihat detail instrumen eviden PMPZI" onclick="openPmpziDetailModal('<?= $it['kode_ev'] ?>', '<?= htmlspecialchars($it['nama'], ENT_QUOTES) ?>', 'Pemenuhan', '<?= $it['nilai'] ?>', '<?= $it['persen'] ?>')">
                                                Ev
                                            </button>
                                        </div>
                                        <small class="text-muted d-block mt-1" style="font-size: 11.5px;"><?= htmlspecialchars($it['keterangan'] ?? '') ?></small>
                                    </td>
                                    <td class="text-center font-monospace fw-bold fs-6 text-dark"><?= number_format($it['nilai'], 2) ?></td>
                                    <td class="text-center">
                                        <div class="d-inline-flex flex-column align-items-center">
                                            <div class="pmpzi-progress-wrap mb-1">
                                                <div class="pmpzi-progress-bar-bg">
                                                    <div class="pmpzi-progress-bar-fill <?= $bar_color ?>" style="width: <?= min(100, $pct) ?>%;"></div>
                                                </div>
                                                <span class="badge <?= $badge_color ?> rounded-pill pmpzi-badge-pct"><?= number_format($pct, 2) ?>%</span>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="text-center">
                                        <span class="badge bg-light text-dark border rounded-pill px-2.5 py-1.5 font-monospace fw-bold" style="font-size: 12px;">
                                            <i class="bi bi-file-earmark-check text-success me-1"></i><?= $it['berkas_count'] ?> Berkas
                                        </span>
                                    </td>
                                    <td class="text-end">
                                        <button type="button" class="btn btn-outline-primary btn-sm rounded-pill px-3 py-1 fw-bold" onclick="openPmpziDetailModal('<?= $it['kode_ev'] ?>', '<?= htmlspecialchars($it['nama'], ENT_QUOTES) ?>', 'Pemenuhan', '<?= $it['nilai'] ?>', '<?= $it['persen'] ?>')">
                                            <i class="bi bi-list-check me-1"></i> Rincian Butir
                                        </button>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>

                        <!-- SUBTOTAL PEMENUHAN ROW -->
                        <tr class="pmpzi-subtotal-row">
                            <td colspan="2" class="text-uppercase ps-4">
                                <i class="bi bi-calculator me-1"></i> TOTAL ASPEK PEMENUHAN (30)
                            </td>
                            <td class="text-center font-monospace fs-6"><?= number_format($pmpzi_matrix['pengungkit']['pemenuhan']['nilai'] ?? 21.59, 2) ?></td>
                            <td class="text-center font-monospace fs-6 text-primary"><?= number_format($pmpzi_matrix['pengungkit']['pemenuhan']['persen'] ?? 71.97, 2) ?>%</td>
                            <td colspan="2" class="text-end pe-4 text-muted small">Target 30 Poin Pemenuhan</td>
                        </tr>

                        <!-- ═══ HEADER ROW: ASPEK REFORM ═══ -->
                        <tr class="pmpzi-section-title-row">
                            <td colspan="6" class="py-2.5 px-3">
                                <div class="d-flex align-items-center justify-content-between">
                                    <span><i class="bi bi-arrow-repeat text-primary me-2"></i>ASPEK REFORM (BOBOT 30)</span>
                                    <span class="badge bg-white text-dark border font-monospace">Subtotal Target: 30.00</span>
                                </div>
                            </td>
                        </tr>

                        <?php if(!empty($pmpzi_matrix['pengungkit']['reform']['items'])): ?>
                            <?php foreach($pmpzi_matrix['pengungkit']['reform']['items'] as $it): 
                                $pct = (float)$it['persen'];
                                $bar_color = ($pct >= 80) ? 'bg-success' : (($pct >= 60) ? 'bg-primary' : (($pct >= 40) ? 'bg-warning' : 'bg-danger'));
                                $badge_color = ($pct >= 80) ? 'text-success bg-success-subtle' : (($pct >= 60) ? 'text-primary bg-primary-subtle' : (($pct >= 40) ? 'text-warning bg-warning-subtle' : 'text-danger bg-danger-subtle'));
                            ?>
                                <tr>
                                    <td class="text-center fw-bold text-muted"><?= $it['no'] ?></td>
                                    <td>
                                        <div class="d-flex align-items-center flex-wrap gap-2">
                                            <a href="#" onclick="openPmpziDetailModal('<?= $it['kode_ev'] ?>', '<?= htmlspecialchars($it['nama'], ENT_QUOTES) ?>', 'Reform', '<?= $it['nilai'] ?>', '<?= $it['persen'] ?>'); return false;" class="pmpzi-btn-pokja">
                                                <span><?= htmlspecialchars($it['nama']) ?> (<?= $it['bobot'] ?>)</span>
                                            </a>
                                            <button type="button" class="pmpzi-btn-ev" title="Lihat detail instrumen eviden PMPZI" onclick="openPmpziDetailModal('<?= $it['kode_ev'] ?>', '<?= htmlspecialchars($it['nama'], ENT_QUOTES) ?>', 'Reform', '<?= $it['nilai'] ?>', '<?= $it['persen'] ?>')">
                                                Ev
                                            </button>
                                        </div>
                                        <small class="text-muted d-block mt-1" style="font-size: 11.5px;"><?= htmlspecialchars($it['keterangan'] ?? '') ?></small>
                                    </td>
                                    <td class="text-center font-monospace fw-bold fs-6 text-dark"><?= number_format($it['nilai'], 2) ?></td>
                                    <td class="text-center">
                                        <div class="d-inline-flex flex-column align-items-center">
                                            <div class="pmpzi-progress-wrap mb-1">
                                                <div class="pmpzi-progress-bar-bg">
                                                    <div class="pmpzi-progress-bar-fill <?= $bar_color ?>" style="width: <?= min(100, $pct) ?>%;"></div>
                                                </div>
                                                <span class="badge <?= $badge_color ?> rounded-pill pmpzi-badge-pct"><?= number_format($pct, 2) ?>%</span>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="text-center">
                                        <span class="badge bg-light text-dark border rounded-pill px-2.5 py-1.5 font-monospace fw-bold" style="font-size: 12px;">
                                            <i class="bi bi-file-earmark-check text-success me-1"></i><?= $it['berkas_count'] ?> Berkas
                                        </span>
                                    </td>
                                    <td class="text-end">
                                        <button type="button" class="btn btn-outline-primary btn-sm rounded-pill px-3 py-1 fw-bold" onclick="openPmpziDetailModal('<?= $it['kode_ev'] ?>', '<?= htmlspecialchars($it['nama'], ENT_QUOTES) ?>', 'Reform', '<?= $it['nilai'] ?>', '<?= $it['persen'] ?>')">
                                            <i class="bi bi-list-check me-1"></i> Rincian Butir
                                        </button>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>

                        <!-- SUBTOTAL REFORM ROW -->
                        <tr class="pmpzi-subtotal-row">
                            <td colspan="2" class="text-uppercase ps-4">
                                <i class="bi bi-calculator me-1"></i> TOTAL ASPEK REFORM (30)
                            </td>
                            <td class="text-center font-monospace fs-6"><?= number_format($pmpzi_matrix['pengungkit']['reform']['nilai'] ?? 17.08, 2) ?></td>
                            <td class="text-center font-monospace fs-6 text-primary"><?= number_format($pmpzi_matrix['pengungkit']['reform']['persen'] ?? 56.93, 2) ?>%</td>
                            <td colspan="2" class="text-end pe-4 text-muted small">Target 30 Poin Reform</td>
                        </tr>

                        <!-- TOTAL KOMPONEN PENGUNGKIT -->
                        <tr class="pmpzi-total-row">
                            <td colspan="2" class="text-uppercase ps-4 text-success">
                                <i class="bi bi-award-fill me-1"></i> TOTAL KOMPONEN PENGUNGKIT (60)
                            </td>
                            <td class="text-center font-monospace fs-5 text-dark"><?= number_format($pmpzi_matrix['pengungkit']['nilai'] ?? 38.67, 2) ?></td>
                            <td class="text-center font-monospace fs-5 text-success"><?= number_format($pmpzi_matrix['pengungkit']['persen'] ?? 64.45, 2) ?>%</td>
                            <td colspan="2" class="text-end pe-4 fw-bold text-success">Bobot Pengungkit: 60.00</td>
                        </tr>

                    </tbody>
                </table>
            </div>

            <!-- Tabel 2: Komponen Hasil (40) -->
            <div class="table-responsive border-top">
                <table class="table pmpzi-table align-middle">
                    <thead>
                        <tr class="bg-light">
                            <th class="text-center" style="width: 60px;">No.</th>
                            <th>Komponen Hasil (Bobot 40)</th>
                            <th class="text-center" style="width: 120px;">Nilai</th>
                            <th class="text-center" style="width: 200px;">Capaian (%)</th>
                            <th class="text-end" colspan="2" style="width: 290px;">Keterangan Hasil</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if(!empty($pmpzi_matrix['hasil']['items'])): ?>
                            <?php foreach($pmpzi_matrix['hasil']['items'] as $h): ?>
                                <tr>
                                    <td class="text-center fw-bold text-muted"><?= $h['no'] ?></td>
                                    <td>
                                        <div class="d-flex align-items-center gap-2">
                                            <span class="badge bg-success text-white px-3 py-2 rounded-3 fs-6 fw-bold">
                                                <?= htmlspecialchars($h['nama']) ?> (<?= $h['bobot'] ?>)
                                            </span>
                                            <span class="pmpzi-btn-ev">Ev</span>
                                        </div>
                                    </td>
                                    <td class="text-center font-monospace fw-bold fs-6 text-dark"><?= number_format($h['nilai'], 2) ?></td>
                                    <td class="text-center">
                                        <div class="d-inline-flex align-items-center gap-2">
                                            <div class="pmpzi-progress-bar-bg" style="width: 100px;">
                                                <div class="pmpzi-progress-bar-fill bg-success" style="width: <?= min(100, $h['persen']) ?>%;"></div>
                                            </div>
                                            <span class="badge bg-success-subtle text-success rounded-pill pmpzi-badge-pct"><?= number_format($h['persen'], 2) ?>%</span>
                                        </div>
                                    </td>
                                    <td colspan="2" class="text-end pe-4 text-muted small">
                                        <?= htmlspecialchars($h['keterangan'] ?? '') ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>

                        <!-- TOTAL KOMPONEN HASIL -->
                        <tr class="pmpzi-total-row">
                            <td colspan="2" class="text-uppercase ps-4 text-info">
                                <i class="bi bi-graph-up-arrow me-1"></i> TOTAL KOMPONEN HASIL (40)
                            </td>
                            <td class="text-center font-monospace fs-5 text-dark"><?= number_format($pmpzi_matrix['hasil']['nilai'] ?? 30.00, 2) ?></td>
                            <td class="text-center font-monospace fs-5 text-info"><?= number_format($pmpzi_matrix['hasil']['persen'] ?? 75.00, 2) ?>%</td>
                            <td colspan="2" class="text-end pe-4 fw-bold text-info">Bobot Hasil: 40.00</td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- Box Indeks PMPZI Satuan Kerja Highlight -->
            <div class="text-center py-4 bg-light rounded-bottom-4 border-top">
                <div class="text-muted small text-uppercase fw-bold mb-1" style="letter-spacing: 1px;">Indeks PMPZI Satuan Kerja MAN 3 Banjar</div>
                <div class="display-5 fw-bold text-primary font-monospace mb-1"><?= $pmpzi_matrix['indeks_satker'] ?? '68.67' ?></div>
                <div class="badge bg-success-subtle text-success px-3.5 py-2 rounded-pill fw-bold fs-6 mb-3">
                    <i class="bi bi-award-fill me-1"></i> Memenuhi Ambang Batas Pengusulan WBK Kemenag (≥ 60.00)
                </div>
                <div class="d-flex flex-wrap justify-content-center gap-2">
                    <button type="button" class="btn btn-warning text-dark fw-bold rounded-pill px-4 py-2.5 shadow-sm" onclick="switchZiMainView('berkas')">
                        <i class="bi bi-folder-check me-1"></i> Buka Bank & Arsip Berkas Eviden Pokja
                    </button>
                    <button type="button" class="btn btn-outline-success fw-bold rounded-pill px-4 py-2.5" data-bs-toggle="modal" data-bs-target="#modalUploadZi">
                        <i class="bi bi-cloud-arrow-up-fill me-1"></i> + Unggah Eviden Baru
                    </button>
                </div>
            </div>

        </div>

    </div>
</div>

<!-- ═══ 3. SECTION DAFTAR & BANK BERKAS EVIDEN POKJA ═══ -->
<div id="ziSectionBerkas" style="display: <?= ($main_tab ?? 'monitoring') === 'berkas' ? 'block' : 'none' ?>;">

<!-- ═══ 2. KPI STRIP 6 AREA ZI ═══ -->
<div class="container">
    <div class="zi-kpi-summary-strip">
        <a href="<?= base_url('website/zona_integritas') ?>" data-area="all" onclick="selectZiArea('all'); return false;" class="zi-kpi-item <?= $active_area === 'all' ? 'active' : '' ?>">
            <div class="zi-kpi-num"><?= $zi_stats['total'] ?? 0 ?></div>
            <div class="zi-kpi-label">Semua Eviden</div>
        </a>
        <a href="<?= base_url('website/zona_integritas?area=shared') ?>" data-area="shared" onclick="selectZiArea('shared'); return false;" class="zi-kpi-item <?= $active_area === 'shared' ? 'active' : '' ?>">
            <div class="zi-kpi-num text-primary"><?= $zi_stats['shared'] ?? 0 ?></div>
            <div class="zi-kpi-label">Dokumen Bersama</div>
        </a>
        <a href="<?= base_url('website/zona_integritas?area=area1') ?>" data-area="area1" onclick="selectZiArea('area1'); return false;" class="zi-kpi-item <?= $active_area === 'area1' ? 'active' : '' ?>">
            <div class="zi-kpi-num text-success"><?= $zi_stats['area1'] ?? 0 ?></div>
            <div class="zi-kpi-label">Pokja I</div>
        </a>
        <a href="<?= base_url('website/zona_integritas?area=area2') ?>" data-area="area2" onclick="selectZiArea('area2'); return false;" class="zi-kpi-item <?= $active_area === 'area2' ? 'active' : '' ?>">
            <div class="zi-kpi-num text-success"><?= $zi_stats['area2'] ?? 0 ?></div>
            <div class="zi-kpi-label">Pokja II</div>
        </a>
        <a href="<?= base_url('website/zona_integritas?area=area3') ?>" data-area="area3" onclick="selectZiArea('area3'); return false;" class="zi-kpi-item <?= $active_area === 'area3' ? 'active' : '' ?>">
            <div class="zi-kpi-num text-success"><?= $zi_stats['area3'] ?? 0 ?></div>
            <div class="zi-kpi-label">Pokja III</div>
        </a>
        <a href="<?= base_url('website/zona_integritas?area=area4') ?>" data-area="area4" onclick="selectZiArea('area4'); return false;" class="zi-kpi-item <?= $active_area === 'area4' ? 'active' : '' ?>">
            <div class="zi-kpi-num text-success"><?= $zi_stats['area4'] ?? 0 ?></div>
            <div class="zi-kpi-label">Pokja IV</div>
        </a>
        <a href="<?= base_url('website/zona_integritas?area=area5') ?>" data-area="area5" onclick="selectZiArea('area5'); return false;" class="zi-kpi-item <?= $active_area === 'area5' ? 'active' : '' ?>">
            <div class="zi-kpi-num text-success"><?= $zi_stats['area5'] ?? 0 ?></div>
            <div class="zi-kpi-label">Pokja V</div>
        </a>
        <a href="<?= base_url('website/zona_integritas?area=area6') ?>" data-area="area6" onclick="selectZiArea('area6'); return false;" class="zi-kpi-item <?= $active_area === 'area6' ? 'active' : '' ?>">
            <div class="zi-kpi-num text-success"><?= $zi_stats['area6'] ?? 0 ?></div>
            <div class="zi-kpi-label">Pokja VI</div>
        </a>
    </div>
</div>

<!-- ═══ 3. KONTEN DOKUMEN EVIDEN ═══ -->
<div class="container py-5">
    
    <!-- Notifikasi Flash -->
    <?php if($this->session->flashdata('success')): ?>
        <div class="alert alert-success alert-dismissible fade show rounded-4 border-0 shadow-sm mb-4 d-flex align-items-center gap-2" role="alert">
            <i class="bi bi-check-circle-fill text-success fs-5"></i>
            <div class="fw-semibold"><?= $this->session->flashdata('success') ?></div>
            <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>
    <?php if($this->session->flashdata('error')): ?>
        <div class="alert alert-danger alert-dismissible fade show rounded-4 border-0 shadow-sm mb-4 d-flex align-items-center gap-2" role="alert">
            <i class="bi bi-exclamation-triangle-fill text-danger fs-5"></i>
            <div class="fw-semibold"><?= $this->session->flashdata('error') ?></div>
            <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <!-- Navigasi Pokja & Bank Dokumen Bersama -->
    <div class="mb-4">
        <!-- 1. KARTU KHUSUS BANK DOKUMEN BERSAMA / INDUK ZI -->
        <div class="mb-3">
            <a href="<?= base_url('website/zona_integritas?area=shared') ?>" data-area="shared" onclick="selectZiArea('shared'); return false;" class="zi-area-card <?= $active_area === 'shared' ? 'active' : '' ?> p-3 shadow-xs" style="border-left: 5px solid #2563eb; background: <?= $active_area === 'shared' ? 'linear-gradient(135deg, #1e3a8a 0%, #2563eb 100%)' : '#ffffff' ?>;">
                <div class="d-flex align-items-center justify-content-center rounded-3 flex-shrink-0" style="width: 44px; height: 44px; font-size: 20px; background: <?= $active_area === 'shared' ? 'rgba(255,255,255,0.2)' : '#eff6ff' ?>; color: <?= $active_area === 'shared' ? '#ffffff' : '#2563eb' ?>;">
                    <i class="bi bi-collection-fill"></i>
                </div>
                <div class="text-truncate flex-grow-1 ms-1">
                    <div class="d-flex align-items-center gap-2">
                        <strong class="d-block text-truncate" style="font-size: 14.5px;">Dokumen Bersama / Induk ZI (Bank Berkas Lintas Pokja)</strong>
                        <span class="badge <?= $active_area === 'shared' ? 'bg-white text-primary' : 'bg-primary-subtle text-primary' ?> rounded-pill" style="font-size: 11px;">Bisa Disalin ke Pokja I–VI</span>
                    </div>
                    <span class="small <?= $active_area === 'shared' ? 'text-white-50' : 'text-muted' ?>" style="font-size: 11.5px;">SK Tim Pokja, Renstra, Komitmen Bersama, Notulen Pleno yang dapat disalin langsung ke folder pokja kerja Anda.</span>
                </div>
                <span class="badge-count <?= $active_area === 'shared' ? 'bg-white text-primary' : 'bg-primary text-white' ?> px-2.5 py-1 fw-bold"><?= $zi_stats['shared'] ?? 0 ?> Berkas</span>
            </a>
        </div>

        <div class="d-flex align-items-center justify-content-between mb-2">
            <label class="form-label fw-bold text-muted small text-uppercase mb-0" style="letter-spacing: 0.5px;">Pilih 6 Pokja Pembangunan ZI:</label>
            <button type="button" class="btn btn-sm btn-link text-success fw-bold p-0 text-decoration-none" onclick="selectZiArea('all')">
                <i class="bi bi-grid me-1"></i> Tampilkan Semua Pokja
            </button>
        </div>
        <div class="zi-area-grid">
            <a href="<?= base_url('website/zona_integritas?area=area1') ?>" data-area="area1" onclick="selectZiArea('area1'); return false;" class="zi-area-card <?= $active_area === 'area1' ? 'active' : '' ?>">
                <i class="bi bi-signpost-split-fill text-success"></i>
                <div class="text-truncate">
                    <strong class="d-block text-truncate" style="font-size: 13.5px;">Pokja I: Manajemen Perubahan</strong>
                    <span class="small opacity-75" style="font-size: 11px;">Budaya kerja & komitmen</span>
                </div>
                <span class="badge-count"><?= $zi_stats['area1'] ?? 0 ?></span>
            </a>
            <a href="<?= base_url('website/zona_integritas?area=area2') ?>" data-area="area2" onclick="selectZiArea('area2'); return false;" class="zi-area-card <?= $active_area === 'area2' ? 'active' : '' ?>">
                <i class="bi bi-diagram-3-fill text-primary"></i>
                <div class="text-truncate">
                    <strong class="d-block text-truncate" style="font-size: 13.5px;">Pokja II: Penataan Tatalaksana</strong>
                    <span class="small opacity-75" style="font-size: 11px;">SOP & digitalisasi e-office</span>
                </div>
                <span class="badge-count"><?= $zi_stats['area2'] ?? 0 ?></span>
            </a>
            <a href="<?= base_url('website/zona_integritas?area=area3') ?>" data-area="area3" onclick="selectZiArea('area3'); return false;" class="zi-area-card <?= $active_area === 'area3' ? 'active' : '' ?>">
                <i class="bi bi-people-fill text-info"></i>
                <div class="text-truncate">
                    <strong class="d-block text-truncate" style="font-size: 13.5px;">Pokja III: Penataan Manajemen SDM</strong>
                    <span class="small opacity-75" style="font-size: 11px;">Disiplin & kompetensi guru/staf</span>
                </div>
                <span class="badge-count"><?= $zi_stats['area3'] ?? 0 ?></span>
            </a>
            <a href="<?= base_url('website/zona_integritas?area=area4') ?>" data-area="area4" onclick="selectZiArea('area4'); return false;" class="zi-area-card <?= $active_area === 'area4' ? 'active' : '' ?>">
                <i class="bi bi-clipboard-data-fill text-warning"></i>
                <div class="text-truncate">
                    <strong class="d-block text-truncate" style="font-size: 13.5px;">Pokja IV: Penguatan Akuntabilitas</strong>
                    <span class="small opacity-75" style="font-size: 11px;">LAKIP & capaian kinerja</span>
                </div>
                <span class="badge-count"><?= $zi_stats['area4'] ?? 0 ?></span>
            </a>
            <a href="<?= base_url('website/zona_integritas?area=area5') ?>" data-area="area5" onclick="selectZiArea('area5'); return false;" class="zi-area-card <?= $active_area === 'area5' ? 'active' : '' ?>">
                <i class="bi bi-shield-lock-fill text-danger"></i>
                <div class="text-truncate">
                    <strong class="d-block text-truncate" style="font-size: 13.5px;">Pokja V: Penguatan Pengawasan</strong>
                    <span class="small opacity-75" style="font-size: 11px;">Gratifikasi, WBS & benturan</span>
                </div>
                <span class="badge-count"><?= $zi_stats['area5'] ?? 0 ?></span>
            </a>
            <a href="<?= base_url('website/zona_integritas?area=area6') ?>" data-area="area6" onclick="selectZiArea('area6'); return false;" class="zi-area-card <?= $active_area === 'area6' ? 'active' : '' ?>">
                <i class="bi bi-stars text-success"></i>
                <div class="text-truncate">
                    <strong class="d-block text-truncate" style="font-size: 13.5px;">Pokja VI: Kualitas Pelayanan</strong>
                    <span class="small opacity-75" style="font-size: 11px;">Inovasi layanan & survei IKM</span>
                </div>
                <span class="badge-count"><?= $zi_stats['area6'] ?? 0 ?></span>
            </a>
        </div>
    </div>

    <!-- Toolbar Pencarian & Filter File -->
    <div class="zi-toolbar">
        <div class="d-flex align-items-center gap-2 flex-grow-1" style="max-width: 400px;">
            <div class="input-group">
                <span class="input-group-text bg-light border-end-0 rounded-start-pill text-muted ps-3">
                    <i class="bi bi-search"></i>
                </span>
                <input type="text" id="ziSearchInput" class="form-control bg-light border-start-0 rounded-end-pill py-2" placeholder="Cari nama eviden, SK, SOP...">
            </div>
        </div>

        <div class="d-flex align-items-center gap-2 flex-wrap">
            <select id="ziTypeFilter" class="form-select rounded-pill px-3 py-2 small fw-semibold" style="width: auto;">
                <option value="all">Semua Format File</option>
                <option value="pdf">Hanya Dokumen PDF</option>
                <option value="doc">Microsoft Word (DOCX)</option>
                <option value="xls">Excel / Spreadsheet</option>
                <option value="drive">Google Drive Link</option>
            </select>

            <div class="btn-group" role="group">
                <button type="button" class="btn btn-outline-secondary px-3 py-2 <?= $active_view === 'grid' ? 'active' : '' ?>" id="btnViewGrid" title="Tampilan Kotak (Grid)">
                    <i class="bi bi-grid-fill"></i>
                </button>
                <button type="button" class="btn btn-outline-secondary px-3 py-2 <?= $active_view === 'list' ? 'active' : '' ?>" id="btnViewList" title="Tampilan Tabel (List)">
                    <i class="bi bi-list-ul"></i>
                </button>
            </div>
        </div>
    </div>

    <!-- Tampilan Grid Dokumen Eviden ZI -->
    <div id="ziContainerGrid" class="zi-file-grid" style="<?= $active_view === 'grid' ? '' : 'display:none;' ?>">
        <?php if(empty($downloads)): ?>
            <div class="zi-empty-state text-center py-5 text-muted w-100" style="grid-column: 1 / -1;">
                <div class="d-inline-flex align-items-center justify-content-center bg-light rounded-circle mb-3 shadow-xs" style="width: 76px; height: 76px;">
                    <i class="bi bi-folder-x fs-1 text-secondary opacity-75"></i>
                </div>
                <h6 class="fw-bold text-dark fs-5 mb-2">Belum Ada Dokumen Eviden ZI di Folder Ini</h6>
                <p class="small text-muted mb-3 mx-auto" style="max-width: 480px;">Klik tombol <strong>+ Unggah Eviden ZI</strong> untuk menambahkan berkas baru.</p>
            </div>
        <?php else: ?>
            <?php foreach($downloads as $d): ?>
                <?php 
                $file_meta = get_zi_file_meta($d);
                $area = strtolower($d->area_zi ?? 'area1');
                $init_hide = ($active_area === 'all' && $area === 'shared');
                ?>
                <div class="zi-file-card zi-item-row"
                     <?= $init_hide ? 'style="display: none;"' : '' ?>
                     data-area="<?= $area ?>"
                     data-ext="<?= strtolower($file_meta['ext']) ?>"
                     data-title="<?= htmlspecialchars(strtolower($d->judul ?? ''), ENT_QUOTES, 'UTF-8') ?>"
                     data-pengunggah="<?= htmlspecialchars(strtolower($d->pengunggah ?? ''), ENT_QUOTES, 'UTF-8') ?>">
                    
                    <div>
                        <div class="d-flex align-items-start justify-content-between gap-2 mb-2">
                            <div class="file-type-iconbox" style="background: <?= $file_meta['bg'] ?>; color: <?= $file_meta['color'] ?>;">
                                <i class="bi <?= $file_meta['icon'] ?>"></i>
                            </div>
                            <?php if($area === 'shared'): ?>
                                <span class="file-badge-pill bg-primary-subtle text-primary border border-primary-subtle">
                                    <i class="bi bi-collection-fill"></i> DOKUMEN BERSAMA
                                </span>
                            <?php else: ?>
                                <span class="file-badge-pill bg-success-subtle text-success border border-success-subtle">
                                    <i class="bi bi-shield-check"></i> <?= str_replace('AREA', 'POKJA ', strtoupper($area)) ?>
                                </span>
                            <?php endif; ?>
                        </div>

                        <a href="<?= $file_meta['url'] ?>" target="_blank" class="file-title-link" title="<?= htmlspecialchars($d->judul ?? '', ENT_QUOTES, 'UTF-8') ?>">
                            <?= htmlspecialchars($d->judul ?? '-', ENT_QUOTES, 'UTF-8') ?>
                        </a>

                        <?php if(!empty($d->keterangan)): ?>
                            <p class="small text-muted mb-2 text-truncate" title="<?= htmlspecialchars($d->keterangan, ENT_QUOTES, 'UTF-8') ?>">
                                <?= htmlspecialchars($d->keterangan, ENT_QUOTES, 'UTF-8') ?>
                            </p>
                        <?php endif; ?>

                        <div class="file-meta-box">
                            <div class="file-meta-row">
                                <span><i class="bi bi-person-fill text-muted me-1"></i> Pengunggah:</span>
                                <strong class="text-dark text-truncate" style="max-width: 140px;"><?= htmlspecialchars($d->pengunggah ?: 'Tim Pokja ZI', ENT_QUOTES, 'UTF-8') ?></strong>
                            </div>
                            <div class="file-meta-row">
                                <span><i class="bi bi-calendar3 text-muted me-1"></i> Tanggal:</span>
                                <span><?= !empty($d->tanggal) ? date('d M Y', strtotime($d->tanggal)) : date('d M Y') ?></span>
                            </div>
                            <div class="file-meta-row">
                                <span><i class="bi bi-hdd-network text-muted me-1"></i> Ukuran:</span>
                                <span class="fw-semibold text-secondary"><?= $file_meta['size'] ?></span>
                            </div>
                        </div>
                    </div>

                    <div>
                        <div class="d-flex gap-2 mt-3 pt-2 border-top align-items-center">
                            <?php if(!empty($file_meta['can_preview'])): ?>
                                <button type="button" class="btn btn-sm btn-outline-danger rounded-pill flex-grow-1 fw-bold" onclick="previewDoc('<?= $file_meta['url'] ?>', '<?= htmlspecialchars(addslashes($d->judul), ENT_QUOTES, 'UTF-8') ?>', '<?= $file_meta['ext'] ?>')">
                                    <i class="bi bi-eye-fill me-1"></i> Pratinjau
                                </button>
                            <?php endif; ?>

                            <?php if($file_meta['is_link']): ?>
                                <a href="<?= $file_meta['url'] ?>" target="_blank" class="btn btn-sm btn-success rounded-pill flex-grow-1 fw-bold">
                                    <i class="bi bi-box-arrow-up-right me-1"></i> Buka Drive
                                </a>
                            <?php else: ?>
                                <a href="<?= $file_meta['url'] ?>" target="_blank" download class="btn btn-sm btn-outline-success rounded-pill flex-grow-1 fw-bold">
                                    <i class="bi bi-download me-1"></i> Unduh
                                </a>
                            <?php endif; ?>

                            <!-- Tombol Salin ke Pokja (Selalu Aktif) -->
                            <button type="button" class="btn btn-sm btn-outline-primary rounded-pill px-2.5 py-1" title="Salin / Duplikasi Dokumen ke Pokja" onclick="openCopyModalZi(<?= htmlspecialchars(json_encode($d), ENT_QUOTES, 'UTF-8') ?>)">
                                <i class="bi bi-copy"></i>
                            </button>

                            <!-- Tombol Edit / Revisi Eviden ZI -->
                            <button type="button" class="btn btn-sm btn-outline-warning rounded-pill px-2.5 py-1 text-dark" title="Edit / Revisi Eviden ZI (Nama PIC, Pokja, atau File)" onclick="openEditModalZi(<?= htmlspecialchars(json_encode($d), ENT_QUOTES, 'UTF-8') ?>)">
                                <i class="bi bi-pencil-square"></i>
                            </button>

                            <!-- Tombol Hapus Eviden ZI -->
                            <button type="button" class="btn btn-sm btn-outline-danger rounded-pill px-2.5 py-1" title="Hapus Berkas Eviden ZI" onclick="openDeleteModalZi(<?= htmlspecialchars(json_encode($d), ENT_QUOTES, 'UTF-8') ?>)">
                                <i class="bi bi-trash-fill"></i>
                            </button>
                        </div>

                        <?php if($area === 'shared'): ?>
                            <button type="button" class="btn btn-sm btn-primary rounded-pill w-100 mt-2 fw-bold shadow-xs d-flex align-items-center justify-content-center gap-1.5" onclick="openCopyModalZi(<?= htmlspecialchars(json_encode($d), ENT_QUOTES, 'UTF-8') ?>)">
                                <i class="bi bi-copy"></i>
                                <span>Salin ke Pokja Saya</span>
                            </button>
                        <?php endif; ?>
                    </div>

                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>

    <!-- Tampilan Tabel List Dokumen Eviden ZI -->
    <div id="ziContainerList" class="card border-0 rounded-4 shadow-sm overflow-hidden" style="<?= $active_view === 'list' ? '' : 'display:none;' ?>">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0" style="font-size: 13.5px;">
                <thead class="bg-light text-muted small text-uppercase">
                    <tr>
                        <th class="ps-4 text-center" style="width: 60px;">Format</th>
                        <th>Nama Dokumen / Eviden ZI</th>
                        <th style="white-space: nowrap;">Pokja ZI</th>
                        <th style="white-space: nowrap;">Pengunggah (Pokja)</th>
                        <th style="white-space: nowrap;">Tanggal</th>
                        <th class="text-end pe-4" style="width: 220px; white-space: nowrap;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if(empty($downloads)): ?>
                        <tr>
                            <td colspan="6" class="text-center py-4 text-muted">Belum ada eviden ZI.</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach($downloads as $d): ?>
                            <?php 
                            $file_meta = get_zi_file_meta($d);
                            $area = strtolower($d->area_zi ?? 'area1');
                            $init_hide = ($active_area === 'all' && $area === 'shared');
                            ?>
                            <tr class="zi-item-row"
                                <?= $init_hide ? 'style="display: none;"' : '' ?>
                                data-area="<?= $area ?>"
                                data-ext="<?= strtolower($file_meta['ext']) ?>"
                                data-title="<?= htmlspecialchars(strtolower($d->judul ?? ''), ENT_QUOTES, 'UTF-8') ?>"
                                data-pengunggah="<?= htmlspecialchars(strtolower($d->pengunggah ?? ''), ENT_QUOTES, 'UTF-8') ?>">
                                <td class="ps-4 text-center">
                                    <div class="file-type-iconbox mx-auto" style="width: 36px; height: 36px; font-size: 18px; background: <?= $file_meta['bg'] ?>; color: <?= $file_meta['color'] ?>;">
                                        <i class="bi <?= $file_meta['icon'] ?>"></i>
                                    </div>
                                </td>
                                <td>
                                    <a href="<?= $file_meta['url'] ?>" target="_blank" class="fw-bold text-dark text-decoration-none hover-primary">
                                        <?= htmlspecialchars($d->judul ?? '-', ENT_QUOTES, 'UTF-8') ?>
                                    </a>
                                    <?php if(!empty($d->keterangan)): ?>
                                        <div class="small text-muted text-truncate" style="max-width: 380px; font-size: 12px;">
                                            <?= htmlspecialchars($d->keterangan, ENT_QUOTES, 'UTF-8') ?>
                                        </div>
                                    <?php endif; ?>
                                </td>
                                <td style="white-space: nowrap;">
                                    <?php if($area === 'shared'): ?>
                                        <span class="badge bg-primary-subtle text-primary border border-primary-subtle rounded-pill px-2.5 py-1 fw-bold" style="font-size: 11px;">
                                            <i class="bi bi-collection-fill me-1"></i> DOKUMEN BERSAMA
                                        </span>
                                    <?php else: ?>
                                        <span class="badge bg-success-subtle text-success border border-success-subtle rounded-pill px-2.5 py-1 fw-bold" style="font-size: 11px;">
                                            <i class="bi bi-shield-check me-1"></i> <?= str_replace('AREA', 'POKJA ', strtoupper($area)) ?>
                                        </span>
                                    <?php endif; ?>
                                    <div class="text-muted mt-0.5" style="font-size: 11px;">
                                        <?= $area_names[$area] ?? 'Zona Integritas' ?>
                                    </div>
                                </td>
                                <td>
                                    <div class="fw-semibold text-dark small" style="white-space: nowrap;">
                                        <i class="bi bi-person-fill text-muted me-1"></i><?= htmlspecialchars($d->pengunggah ?: 'Tim Pokja ZI', ENT_QUOTES, 'UTF-8') ?>
                                    </div>
                                    <?php if(!empty($d->lini_unit)): ?>
                                        <div class="text-muted text-truncate" style="font-size: 11px; max-width: 200px;"><?= htmlspecialchars($d->lini_unit, ENT_QUOTES, 'UTF-8') ?></div>
                                    <?php endif; ?>
                                </td>
                                <td style="white-space: nowrap;">
                                    <div class="text-dark fw-semibold" style="font-size: 12.5px;">
                                        <?= !empty($d->tanggal) ? date('d M Y', strtotime($d->tanggal)) : '-' ?>
                                    </div>
                                    <div class="text-secondary" style="font-size: 11px;">
                                        <?= $file_meta['size'] ?>
                                    </div>
                                </td>
                                <td class="text-end pe-4" style="white-space: nowrap;">
                                    <div class="d-inline-flex align-items-center gap-1.5">
                                        <?php if(!empty($file_meta['can_preview'])): ?>
                                            <button type="button" class="btn btn-sm btn-outline-danger rounded-pill px-2.5 py-1 d-inline-flex align-items-center gap-1" onclick="previewDoc('<?= $file_meta['url'] ?>', '<?= htmlspecialchars(addslashes($d->judul), ENT_QUOTES, 'UTF-8') ?>', '<?= $file_meta['ext'] ?>')" title="Pratinjau Berkas">
                                                <i class="bi bi-eye-fill"></i>
                                                <span class="d-none d-lg-inline" style="font-size: 12px;">Lihat</span>
                                            </button>
                                        <?php endif; ?>

                                        <?php if($file_meta['is_link']): ?>
                                            <a href="<?= $file_meta['url'] ?>" target="_blank" class="btn btn-sm btn-outline-success rounded-pill px-2.5 py-1 d-inline-flex align-items-center gap-1" title="Buka Google Drive">
                                                <i class="bi bi-box-arrow-up-right"></i>
                                                <span style="font-size: 12px;">Drive</span>
                                            </a>
                                        <?php else: ?>
                                            <a href="<?= $file_meta['url'] ?>" target="_blank" download class="btn btn-sm btn-outline-success rounded-pill px-2.5 py-1 d-inline-flex align-items-center gap-1" title="Unduh Berkas">
                                                <i class="bi bi-download"></i>
                                                <span style="font-size: 12px;">Unduh</span>
                                            </a>
                                        <?php endif; ?>

                                        <!-- Tombol Salin ke Pokja -->
                                        <button type="button" class="btn btn-sm btn-outline-primary rounded-pill px-2.5 py-1 d-inline-flex align-items-center gap-1" title="Salin Dokumen ke Pokja" onclick="openCopyModalZi(<?= htmlspecialchars(json_encode($d), ENT_QUOTES, 'UTF-8') ?>)">
                                            <i class="bi bi-copy"></i>
                                            <span class="d-none d-xl-inline" style="font-size: 12px;">Salin</span>
                                        </button>

                                        <!-- Dropdown Menu Opsi Lanjutan (Edit & Hapus) -->
                                        <div class="dropdown d-inline-block">
                                            <button type="button" class="btn btn-sm btn-light border rounded-pill px-2 py-1 shadow-xs" data-bs-toggle="dropdown" aria-expanded="false" title="Menu Opsi Lainnya">
                                                <i class="bi bi-three-dots-vertical"></i>
                                            </button>
                                            <ul class="dropdown-menu dropdown-menu-end shadow-sm border-0 rounded-3 p-1.5" style="font-size: 12.5px; min-width: 175px;">
                                                <li><h6 class="dropdown-header text-uppercase text-muted fw-bold py-1" style="font-size: 10px;">Kelola Eviden</h6></li>
                                                <li>
                                                    <button type="button" class="dropdown-item rounded-2 py-1.5 d-flex align-items-center gap-2" onclick="openEditModalZi(<?= htmlspecialchars(json_encode($d), ENT_QUOTES, 'UTF-8') ?>)">
                                                        <i class="bi bi-pencil-square text-warning"></i>
                                                        <span>Edit / Revisi</span>
                                                    </button>
                                                </li>
                                                <li><hr class="dropdown-divider my-1"></li>
                                                <li>
                                                    <button type="button" class="dropdown-item rounded-2 py-1.5 text-danger d-flex align-items-center gap-2" onclick="openDeleteModalZi(<?= htmlspecialchars(json_encode($d), ENT_QUOTES, 'UTF-8') ?>)">
                                                        <i class="bi bi-trash-fill text-danger"></i>
                                                        <span>Hapus Berkas</span>
                                                    </button>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

</div>
</div> <!-- ═══ End #ziSectionBerkas ═══ -->

<!-- ═══ 4. MODAL UNGGAH EVIDEN ZI ═══ -->
<div class="modal fade" id="modalUploadZi" tabindex="-1" aria-labelledby="modalUploadZiLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content rounded-4 border-0 shadow-lg">
            
            <div class="modal-header border-0 bg-success text-white px-4 py-3" style="background: linear-gradient(135deg, #064e3b 0%, #059669 100%);">
                <div>
                    <h5 class="modal-title fw-bold" id="modalUploadZiLabel">
                        <i class="bi bi-shield-plus me-1 text-warning"></i> Unggah Eviden Zona Integritas (WBK)
                    </h5>
                    <p class="small text-white-50 mb-0">Setorkan dokumen bukti fisik/digital untuk 6 Pokja Pembangunan ZI MAN 3 Banjar.</p>
                </div>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <form action="<?= base_url('website/upload_drive') ?>" method="POST" enctype="multipart/form-data">
                <input type="hidden" name="kategori_pilar" value="zi">
                <input type="hidden" name="redirect_to" value="zona_integritas">

                <div class="modal-body p-4">
                    
                    <!-- 1. Pokja ZI -->
                    <div class="row g-3 mb-3">
                        <div class="col-md-12">
                            <label class="form-label fw-bold small text-muted">Pokja Perubahan ZI <span class="text-danger">*</span></label>
                            <select name="area_zi" id="uploadZiArea" class="form-select rounded-3 text-dark fw-bold" required>
                                <option value="" disabled selected>-- Silakan Pilih Pokja ZI Tujuan Terlebih Dahulu --</option>
                                <option value="area1">Pokja I: Manajemen Perubahan (Budaya Kerja &amp; Komitmen)</option>
                                <option value="area2">Pokja II: Penataan Tatalaksana (SOP &amp; Digitalisasi e-Office)</option>
                                <option value="area3">Pokja III: Penataan Manajemen SDM (Disiplin &amp; Kinerja GTK)</option>
                                <option value="area4">Pokja IV: Penguatan Akuntabilitas (LAKIP &amp; Capaian Sasaran)</option>
                                <option value="area5">Pokja V: Penguatan Pengawasan (Gratifikasi, WBS, SPI)</option>
                                <option value="area6">Pokja VI: Peningkatan Kualitas Pelayanan Publik (IKM &amp; Layanan)</option>
                                <option value="shared">🏛️ Dokumen Bersama / Induk ZI (Dapat disalin ke Pokja I–VI)</option>
                            </select>
                            <div class="form-text small text-muted mt-1">
                                <i class="bi bi-info-circle text-primary me-1"></i>Pilih Pokja tujuan berkas eviden. Dokumen bersama diperuntukkan khusus bagi berkas induk lintas pokja.
                            </div>
                        </div>
                    </div>

                    <!-- 2. Nama Dokumen & Tanggal -->
                    <div class="row g-3 mb-3">
                        <div class="col-md-8">
                            <label class="form-label fw-bold small text-muted">Nama / Judul Dokumen Eviden <span class="text-danger">*</span></label>
                            <input type="text" name="judul" class="form-control rounded-3" placeholder="Contoh: SK Tim Pokja Pembangunan ZI WBK 2026" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-bold small text-muted">Tanggal Dokumen</label>
                            <input type="date" name="tanggal" class="form-control rounded-3" value="<?= date('Y-m-d') ?>">
                        </div>
                    </div>

                    <!-- 3. Keterangan Singkat -->
                    <div class="mb-3">
                        <label class="form-label fw-bold small text-muted">Keterangan / Nomor Dokumen (Opsional)</label>
                        <textarea name="keterangan" class="form-control rounded-3" rows="2" placeholder="Catatan peruntukan eviden, indikator LKE, atau nomor surat..."></textarea>
                    </div>

                    <!-- 4. Pengunggah & Tim Pokja -->
                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label class="form-label fw-bold small text-muted">Nama Pengunggah (PTK / Tim Pokja) <span class="text-danger">*</span></label>
                            <?php if(!empty($ptk_list)): ?>
                                <input class="form-control rounded-3" list="ptkSuggestionsZi" name="pengunggah" placeholder="Ketik nama staf / guru..." required value="<?= $is_user_logged_in ? htmlspecialchars($logged_user_name) : '' ?>">
                                <datalist id="ptkSuggestionsZi">
                                    <?php foreach($ptk_list as $p): ?>
                                        <option value="<?= htmlspecialchars($p->nama_lengkap ?? '', ENT_QUOTES, 'UTF-8') ?>">
                                            <?= htmlspecialchars(($p->nip ? 'NIP: '.$p->nip.' - ' : '').($p->jenis_ptk ?? 'Guru'), ENT_QUOTES, 'UTF-8') ?>
                                        </option>
                                    <?php endforeach; ?>
                                </datalist>
                            <?php else: ?>
                                <input type="text" name="pengunggah" class="form-control rounded-3" placeholder="Nama Guru / Staf Pengunggah" required value="<?= $is_user_logged_in ? htmlspecialchars($logged_user_name) : '' ?>">
                            <?php endif; ?>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold small text-muted">Lini / Tim Pokja</label>
                            <select name="lini_unit" class="form-select rounded-3">
                                <option value="Tim Pokja I">Tim Pokja I (Manajemen Perubahan)</option>
                                <option value="Tim Pokja II">Tim Pokja II (Tatalaksana)</option>
                                <option value="Tim Pokja III">Tim Pokja III (Manajemen SDM)</option>
                                <option value="Tim Pokja IV">Tim Pokja IV (Akuntabilitas)</option>
                                <option value="Tim Pokja V">Tim Pokja V (Pengawasan)</option>
                                <option value="Tim Pokja VI">Tim Pokja VI (Pelayanan Publik)</option>
                                <option value="Sekretariat ZI">Sekretariat Tim ZI</option>
                            </select>
                        </div>
                    </div>

                    <!-- 5. Pilihan Sumber Berkas -->
                    <div class="mb-3">
                        <label class="form-label fw-bold small text-muted d-block">Pilihan Sumber Berkas Eviden <span class="text-danger">*</span></label>
                        <input type="hidden" name="tipe_sumber" id="inputZiTipeSumber" value="file">

                        <div class="d-flex gap-2 mb-3">
                            <button type="button" class="btn btn-sm btn-outline-success active rounded-pill px-3 py-2 fw-semibold" id="btnZiSumberFile" onclick="switchZiSumber('file')">
                                <i class="bi bi-file-earmark-arrow-up-fill me-1"></i> Unggah File Langsung (Maks 20MB)
                            </button>
                            <button type="button" class="btn btn-sm btn-outline-secondary rounded-pill px-3 py-2 fw-semibold" id="btnZiSumberDrive" onclick="switchZiSumber('drive_link')">
                                <i class="bi bi-google me-1"></i> Link Google Drive / Cloud
                            </button>
                        </div>

                        <!-- Pane File dengan Drag and Drop Zone -->
                        <div id="paneZiFile">
                            <div class="zi-dropzone" id="ziUploadDropzone" onclick="document.getElementById('inputZiFile').click()">
                                <input type="file" name="file_download" id="inputZiFile" style="display:none;" required accept=".pdf,.doc,.docx,.xls,.xlsx,.ppt,.pptx,.zip,.rar" onchange="handleZiFileSelected(this, 'ziUploadDropzone')">
                                
                                <div class="zi-dropzone-prompt" id="ziUploadDropzonePrompt">
                                    <div class="zi-dropzone-icon">
                                        <i class="bi bi-cloud-arrow-up-fill"></i>
                                    </div>
                                    <div class="fw-bold text-dark mb-1" style="font-size: 14.5px;">
                                        Tarik &amp; Lepas (Drag &amp; Drop) Berkas ke Sini
                                    </div>
                                    <div class="small text-muted mb-2">
                                        atau <span class="text-success text-decoration-underline fw-bold">Klik untuk Memilih Berkas</span> dari komputer
                                    </div>
                                    <div class="badge bg-light text-secondary border px-2.5 py-1 font-monospace" style="font-size: 11px;">
                                        PDF, DOC, DOCX, XLS, XLSX, PPT, PPTX, ZIP, RAR (Maks. 20MB)
                                    </div>
                                </div>

                                <div class="zi-dropzone-file-selected" id="ziUploadDropzoneSelected" style="display:none;">
                                    <div class="d-flex align-items-center justify-content-between gap-2">
                                        <div class="d-flex align-items-center gap-2.5 overflow-hidden">
                                            <div id="ziUploadFileIcon" class="file-type-iconbox" style="width: 42px; height: 42px; font-size: 20px; border-radius: 12px; flex-shrink: 0; background: #ecfdf5; color: #059669;">
                                                <i class="bi bi-file-earmark-check-fill"></i>
                                            </div>
                                            <div class="text-truncate">
                                                <strong class="d-block text-dark text-truncate" id="ziUploadFileName" style="font-size: 13.5px;">nama-file.pdf</strong>
                                                <span class="small text-muted" id="ziUploadFileSize" style="font-size: 11.5px;">1.2 MB</span>
                                            </div>
                                        </div>
                                        <button type="button" class="btn btn-sm btn-outline-danger rounded-pill px-3 py-1 flex-shrink-0 fw-semibold" title="Batalkan & Ganti Berkas" onclick="event.stopPropagation(); resetZiFileDropzone('inputZiFile', 'ziUploadDropzone');">
                                            <i class="bi bi-x-lg me-1"></i> Ganti
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Pane Link Drive -->
                        <div id="paneZiDrive" style="display: none;">
                            <input type="url" name="link_drive" id="inputZiDrive" class="form-control rounded-3" placeholder="https://drive.google.com/drive/folders/...">
                            <div class="form-text small text-muted mt-1">
                                <i class="bi bi-google me-1"></i> Pastikan tautan Google Drive / Folder Eviden sudah diatur <strong>"Anyone with the link can view"</strong>.
                            </div>
                        </div>
                    </div>

                </div>

                <div class="modal-footer border-0 bg-light px-4 py-3 rounded-bottom-4">
                    <button type="button" class="btn btn-outline-secondary rounded-pill px-4" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-success rounded-pill px-4 fw-bold">
                        <i class="bi bi-cloud-arrow-up-fill me-1"></i> Simpan Eviden ZI
                    </button>
                </div>
            </form>

        </div>
    </div>
</div>

<!-- ═══ 5. MODAL PRATINJAU DOKUMEN (PDF, WORD, EXCEL, PPT, FOTO) ═══ -->
<div class="modal fade" id="modalPreviewPdfZi" tabindex="-1" aria-labelledby="modalPreviewPdfZiLabel" aria-hidden="true">
    <div class="modal-dialog modal-fullscreen-md-down modal-xl modal-dialog-centered">
        <div class="modal-content rounded-4 border-0 shadow-lg overflow-hidden" style="height: 90vh;">
            <div class="modal-header border-0 bg-dark text-white py-2.5 px-4 d-flex align-items-center justify-content-between">
                <div class="d-flex align-items-center gap-2 overflow-hidden me-2">
                    <span id="previewDocBadge" class="badge bg-danger-subtle text-danger px-2.5 py-1 font-monospace" style="font-size: 11px;">PDF</span>
                    <h6 class="modal-title fw-semibold text-truncate mb-0" id="modalPreviewPdfZiLabel" style="font-size: 14.5px;">Pratinjau Eviden</h6>
                </div>
                <div class="d-flex align-items-center gap-2 flex-shrink-0">
                    <div class="btn-group btn-group-sm" id="btnGroupViewerSwitch" style="display:none;">
                        <button type="button" class="btn btn-outline-light active py-1 px-2.5" id="btnViewerOffice" onclick="switchViewerEngine('office')">
                            <i class="bi bi-microsoft me-1"></i> Office
                        </button>
                        <button type="button" class="btn btn-outline-light py-1 px-2.5" id="btnViewerGoogle" onclick="switchViewerEngine('google')">
                            <i class="bi bi-google me-1"></i> Google Docs
                        </button>
                    </div>
                    <a href="#" id="btnPdfDownloadDirectZi" download class="btn btn-sm btn-success rounded-pill px-3 fw-bold">
                        <i class="bi bi-download me-1"></i> <span id="btnPdfDownloadLabel">Unduh</span>
                    </a>
                    <a href="#" id="btnPreviewOpenTab" target="_blank" class="btn btn-sm btn-outline-light rounded-pill px-2.5" title="Buka di Tab Baru">
                        <i class="bi bi-box-arrow-up-right"></i>
                    </a>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
            </div>
            <div class="modal-body p-0 position-relative" style="height: calc(100% - 56px); background: #0f172a;">
                <!-- Loading spinner saat render Office/Google viewer -->
                <div id="previewLoadingSpinner" class="position-absolute top-50 start-50 translate-middle text-center p-4 bg-white rounded-4 shadow-sm" style="display:none; z-index: 5; max-width: 340px;">
                    <div class="spinner-border text-primary mb-2" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                    <div class="small text-dark fw-bold" id="previewLoadingText">Menghubungkan ke Viewer Dokumen...</div>
                    <div class="small text-muted mt-1" style="font-size: 11px;">Memuat pratinjau online tanpa mengunduh berkas.</div>
                </div>

                <!-- Iframe Viewer untuk PDF & Office Embed -->
                <iframe id="iframePdfViewerZi" src="" style="width:100%; height:100%; border:none; position:relative; z-index:2; background:#ffffff;"></iframe>

                <!-- Image Viewer untuk foto eviden -->
                <div id="previewImageViewer" style="display:none; width:100%; height:100%; overflow:auto; text-align:center; padding:20px; align-items:center; justify-content:center; background:#0f172a;">
                    <img id="previewImageTag" src="" alt="Pratinjau Eviden" style="max-width:100%; max-height:100%; object-fit:contain; border-radius:12px; box-shadow:0 10px 30px rgba(0,0,0,0.5);">
                </div>
            </div>
        </div>
    </div>
</div>

<!-- ═══ MODAL PROGRES SINKRONISASI PMPZI KEMENAG ═══ -->
<div class="modal fade" id="modalPmpziSyncProgress" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" style="max-width: 480px;">
        <div class="modal-content border-0 shadow-2xl rounded-4 overflow-hidden">
            <div class="modal-header border-0 bg-gradient text-white p-4" style="background: linear-gradient(135deg, #064e3b 0%, #059669 100%);">
                <div class="d-flex align-items-center gap-3">
                    <div class="d-inline-flex align-items-center justify-content-center bg-white bg-opacity-20 rounded-circle text-white" style="width: 48px; height: 48px;">
                        <i class="bi bi-arrow-repeat fs-3" id="pmpziSyncModalIcon"></i>
                    </div>
                    <div>
                        <h5 class="modal-title fw-bold mb-0 text-white" id="pmpziSyncModalTitle">Sinkronisasi PMPZI Kemenag</h5>
                        <p class="small text-white-50 mb-0" id="pmpziSyncModalSubtitle">Menghubungkan ke portal pmpzi.kemenag.go.id...</p>
                    </div>
                </div>
            </div>
            <div class="modal-body p-4">
                <!-- Status List -->
                <div class="pmpzi-sync-steps mb-4">
                    <div class="d-flex align-items-center gap-3 py-2 border-bottom border-light" id="syncStep1">
                        <div class="spinner-border spinner-border-sm text-success" role="status" id="spinStep1"></div>
                        <i class="bi bi-check-circle-fill text-success fs-5 d-none" id="checkStep1"></i>
                        <span class="small fw-semibold text-dark" id="textStep1">1. Autentikasi & Pemecahan Captcha Otomatis</span>
                    </div>
                    <div class="d-flex align-items-center gap-3 py-2 border-bottom border-light text-muted" id="syncStep2">
                        <div class="spinner-border spinner-border-sm text-success d-none" role="status" id="spinStep2"></div>
                        <i class="bi bi-check-circle-fill text-success fs-5 d-none" id="checkStep2"></i>
                        <i class="bi bi-circle text-muted fs-6" id="circleStep2"></i>
                        <span class="small fw-semibold" id="textStep2">2. Penarikan Nilai & Capaian LKE 6 Pokja</span>
                    </div>
                    <div class="d-flex align-items-center gap-3 py-2 text-muted" id="syncStep3">
                        <div class="spinner-border spinner-border-sm text-success d-none" role="status" id="spinStep3"></div>
                        <i class="bi bi-check-circle-fill text-success fs-5 d-none" id="checkStep3"></i>
                        <i class="bi bi-circle text-muted fs-6" id="circleStep3"></i>
                        <span class="small fw-semibold" id="textStep3">3. Penyelarasan Dokumen & Eviden Digital</span>
                    </div>
                </div>

                <!-- Progress Bar -->
                <div class="progress rounded-pill mb-3" style="height: 10px; background-color: #e2e8f0;">
                    <div class="progress-bar progress-bar-striped progress-bar-animated bg-success" id="pmpziProgressBar" role="progressbar" style="width: 25%;"></div>
                </div>

                <!-- Alert Result Message (Hidden by default) -->
                <div class="alert alert-success border-0 rounded-3 p-3 small mb-0 d-none" id="pmpziSyncSuccessBox">
                    <div class="d-flex align-items-center gap-2 mb-1 fw-bold text-success">
                        <i class="bi bi-check-circle-fill fs-5"></i> Sinkronisasi Selesai!
                    </div>
                    <div class="text-secondary small" id="pmpziSyncSuccessMsg">
                        Nilai LKE dan seluruh berkas eviden telah berhasil disinkronkan.
                    </div>
                </div>
                <div class="alert alert-danger border-0 rounded-3 p-3 small mb-0 d-none" id="pmpziSyncErrorBox">
                    <div class="d-flex align-items-center gap-2 mb-1 fw-bold text-danger">
                        <i class="bi bi-exclamation-triangle-fill fs-5"></i> Sinkronisasi Gagal
                    </div>
                    <div class="text-secondary small" id="pmpziSyncErrorMsg">
                        Terjadi kendala koneksi ke server PMPZI Kemenag.
                    </div>
                </div>
            </div>
            <div class="modal-footer border-0 bg-light p-3">
                <button type="button" class="btn btn-secondary rounded-pill px-4 fw-semibold small d-none" id="btnSyncModalClose" data-bs-dismiss="modal">Tutup</button>
                <button type="button" class="btn btn-success rounded-pill px-4 fw-bold small d-none" id="btnSyncModalReload" onclick="window.location.reload()">
                    <i class="bi bi-arrow-clockwise me-1"></i> Muat Ulang Halaman
                </button>
            </div>
        </div>
    </div>
</div>

<!-- ═══ MODAL KONFIRMASI ROLLBACK PMPZI ═══ -->
<div class="modal fade" id="modalPmpziRollbackConfirm" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" style="max-width: 440px;">
        <div class="modal-content border-0 shadow-2xl rounded-4 overflow-hidden">
            <div class="modal-body text-center p-4">
                <div class="d-inline-flex align-items-center justify-content-center bg-danger-subtle text-danger rounded-circle mb-3" style="width: 68px; height: 68px;">
                    <i class="bi bi-arrow-counterclockwise fs-1"></i>
                </div>
                <h5 class="fw-bold text-dark mb-2">Rollback Sinkronisasi PMPZI?</h5>
                <p class="text-muted small mb-3" style="line-height: 1.5;">
                    Tindakan ini akan <strong>menghapus hanya berkas dan data yang ditarik dari PMPZI</strong>. Sebanyak <strong>24 berkas awal madrasah dijamin 100% aman dan tidak akan hilang</strong>.
                </p>
                <div class="p-3 bg-light rounded-3 text-start small border border-light-subtle mb-3">
                    <div class="d-flex align-items-center gap-2 text-dark mb-1">
                        <i class="bi bi-shield-check text-success"></i> Berkas Awal LabSys: <strong>Tetap Utuh</strong>
                    </div>
                    <div class="d-flex align-items-center gap-2 text-danger">
                        <i class="bi bi-trash3 text-danger"></i> Berkas Sync PMPZI: <strong>Dibersihkan</strong>
                    </div>
                </div>
                <div class="d-flex gap-2 justify-content-center">
                    <button type="button" class="btn btn-light rounded-pill px-4 fw-semibold text-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="button" class="btn btn-danger rounded-pill px-4 fw-bold" id="btnConfirmRollbackAction" onclick="executePmpziRollback()">
                        <i class="bi bi-arrow-counterclockwise me-1"></i> Ya, Rollback Sekarang
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function switchZiSumber(type) {
    const inputSumber = document.getElementById('inputZiTipeSumber');
    const paneFile = document.getElementById('paneZiFile');
    const paneDrive = document.getElementById('paneZiDrive');
    const inputFile = document.getElementById('inputZiFile');
    const inputDrive = document.getElementById('inputZiDrive');
    const btnFile = document.getElementById('btnZiSumberFile');
    const btnDrive = document.getElementById('btnZiSumberDrive');

    inputSumber.value = type;

    if (type === 'file') {
        btnFile.classList.add('active', 'btn-outline-success');
        btnFile.classList.remove('btn-outline-secondary');
        btnDrive.classList.remove('active', 'btn-outline-success');
        btnDrive.classList.add('btn-outline-secondary');

        paneFile.style.display = 'block';
        paneDrive.style.display = 'none';
        inputFile.setAttribute('required', 'required');
        inputDrive.removeAttribute('required');
    } else {
        btnDrive.classList.add('active', 'btn-outline-success');
        btnDrive.classList.remove('btn-outline-secondary');
        btnFile.classList.remove('active', 'btn-outline-success');
        btnFile.classList.add('btn-outline-secondary');

        paneFile.style.display = 'none';
        paneDrive.style.display = 'block';
        inputFile.removeAttribute('required');
        inputDrive.setAttribute('required', 'required');
    }
}

// Client-side instant filter & search
let currentArea = '<?= $active_area ?>';
let currentSearch = '';
let currentType = 'all';

function selectZiArea(areaKey) {
    currentArea = areaKey;

    // Update active class on KPI items
    document.querySelectorAll('.zi-kpi-item').forEach(el => {
        if (el.getAttribute('data-area') === areaKey) {
            el.classList.add('active');
        } else {
            el.classList.remove('active');
        }
    });

    // Update active class on Area cards
    document.querySelectorAll('.zi-area-card').forEach(el => {
        if (el.getAttribute('data-area') === areaKey) {
            el.classList.add('active');
        } else {
            el.classList.remove('active');
        }
    });

    applyFilters();
}

function applyFilters() {
    const gridRows = document.querySelectorAll('#ziContainerGrid .zi-item-row');
    const listRows = document.querySelectorAll('#ziContainerList tbody .zi-item-row');
    let visibleCount = 0;

    const checkRow = (row, isCounted) => {
        const area = (row.getAttribute('data-area') || '').toLowerCase().trim();
        const ext = (row.getAttribute('data-ext') || '').toLowerCase().trim();
        const title = (row.getAttribute('data-title') || '').toLowerCase().trim();
        const pengunggah = (row.getAttribute('data-pengunggah') || '').toLowerCase().trim();

        // Dokumen Bersama (shared) dipisahkan dari Semua Eviden Pokja I - VI agar tidak dobel saat disalin ke Pokja
        let matchArea = false;
        if (currentArea === 'all') {
            matchArea = (area !== 'shared'); // Tab 'Semua Eviden' murni menampilkan eviden definitif Pokja I s/d Pokja VI
        } else {
            matchArea = (area === currentArea); // Tab spesifik (misal 'shared', 'area1', dst)
        }
        let matchSearch = (!currentSearch || title.includes(currentSearch) || pengunggah.includes(currentSearch));
        let matchType = (currentType === 'all' || 
                        (currentType === 'pdf' && ext === 'pdf') ||
                        (currentType === 'doc' && (ext === 'doc' || ext === 'docx')) ||
                        (currentType === 'xls' && (ext === 'xls' || ext === 'xlsx')) ||
                        (currentType === 'drive' && ext === 'drive'));

        const isMatch = matchArea && matchSearch && matchType;
        if (isMatch) {
            row.style.display = '';
            if (isCounted) visibleCount++;
        } else {
            row.style.display = 'none';
        }
        return isMatch;
    };

    gridRows.forEach(row => checkRow(row, true));
    listRows.forEach(row => checkRow(row, false));

    // Toggle Empty state jika tidak ada hasil
    let emptyGrid = document.getElementById('ziEmptyFilterMsg');
    const gridContainer = document.getElementById('ziContainerGrid');
    if (gridContainer) {
        if (visibleCount === 0 && gridRows.length > 0) {
            if (!emptyGrid) {
                emptyGrid = document.createElement('div');
                emptyGrid.id = 'ziEmptyFilterMsg';
                emptyGrid.className = 'zi-empty-state w-100 text-center py-5 text-muted';
                emptyGrid.style.gridColumn = '1 / -1';
                emptyGrid.innerHTML = '<div class="d-inline-flex align-items-center justify-content-center bg-light rounded-circle mb-3 shadow-xs" style="width: 76px; height: 76px;"><i class="bi bi-search fs-1 text-secondary opacity-75"></i></div><h6 class="fw-bold text-dark fs-5 mb-2">Tidak Ada Eviden yang Cocok</h6><p class="small text-muted mb-0 mx-auto" style="max-width: 480px;">Belum ada eviden yang diunggah untuk Pokja ini atau kata kunci pencarian Anda.</p>';
                gridContainer.appendChild(emptyGrid);
            }
            emptyGrid.style.display = 'block';
        } else if (emptyGrid) {
            emptyGrid.style.display = 'none';
        }
    }
}

document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('ziSearchInput');
    const typeFilter = document.getElementById('ziTypeFilter');
    const btnViewGrid = document.getElementById('btnViewGrid');
    const btnViewList = document.getElementById('btnViewList');
    const gridContainer = document.getElementById('ziContainerGrid');
    const listContainer = document.getElementById('ziContainerList');

    if (btnViewGrid && btnViewList) {
        btnViewGrid.addEventListener('click', function() {
            btnViewGrid.classList.add('active');
            btnViewList.classList.remove('active');
            gridContainer.style.display = 'grid';
            listContainer.style.display = 'none';
        });
        btnViewList.addEventListener('click', function() {
            btnViewList.classList.add('active');
            btnViewGrid.classList.remove('active');
            gridContainer.style.display = 'none';
            listContainer.style.display = 'block';
        });
    }

    if (searchInput) {
        searchInput.addEventListener('input', function(e) {
            currentSearch = e.target.value.toLowerCase().trim();
            applyFilters();
        });
    }

    if (typeFilter) {
        typeFilter.addEventListener('change', function(e) {
            currentType = e.target.value;
            applyFilters();
        });
    }

    // Inisialisasi Drag and Drop Dropzone
    setupZiDropzones();

    // Jalankan filter awal sesuai URL atau default
    applyFilters();
});

// ═══ LOGIKA DRAG & DROP UPLOAD EVIDEN ZI ═══
function setupZiDropzones() {
    ['ziUploadDropzone', 'ziEditDropzone'].forEach(function(dropzoneId) {
        const dropzone = document.getElementById(dropzoneId);
        if (!dropzone) return;

        ['dragenter', 'dragover'].forEach(function(eventName) {
            dropzone.addEventListener(eventName, function(e) {
                e.preventDefault();
                e.stopPropagation();
                dropzone.classList.add('is-dragover');
            }, false);
        });

        ['dragleave', 'drop'].forEach(function(eventName) {
            dropzone.addEventListener(eventName, function(e) {
                e.preventDefault();
                e.stopPropagation();
                dropzone.classList.remove('is-dragover');
            }, false);
        });

        dropzone.addEventListener('drop', function(e) {
            const dt = e.dataTransfer;
            if (dt && dt.files && dt.files.length > 0) {
                const input = dropzone.querySelector('input[type="file"]');
                if (input) {
                    input.files = dt.files;
                    handleZiFileSelected(input, dropzoneId);
                }
            }
        }, false);
    });
}

function handleZiFileSelected(input, dropzoneId) {
    if (!input || !input.files || input.files.length === 0) return;
    const file = input.files[0];
    const isUpload = (dropzoneId === 'ziUploadDropzone');
    
    const promptEl = document.getElementById(isUpload ? 'ziUploadDropzonePrompt' : 'ziEditDropzonePrompt');
    const selectedEl = document.getElementById(isUpload ? 'ziUploadDropzoneSelected' : 'ziEditDropzoneSelected');
    const nameEl = document.getElementById(isUpload ? 'ziUploadFileName' : 'ziEditFileName');
    const sizeEl = document.getElementById(isUpload ? 'ziUploadFileSize' : 'ziEditFileSize');
    const iconEl = document.getElementById(isUpload ? 'ziUploadFileIcon' : 'ziEditFileIcon');

    if (nameEl) nameEl.textContent = file.name;
    if (sizeEl) {
        let sizeStr = '';
        if (file.size >= 1048576) {
            sizeStr = (file.size / 1048576).toFixed(2) + ' MB';
        } else if (file.size >= 1024) {
            sizeStr = (file.size / 1024).toFixed(1) + ' KB';
        } else {
            sizeStr = file.size + ' B';
        }
        sizeEl.textContent = sizeStr;
    }

    // Penyesuaian ikon format berkas
    const ext = file.name.split('.').pop().toLowerCase();
    if (iconEl) {
        if (ext === 'pdf') {
            iconEl.style.background = '#fef2f2';
            iconEl.style.color = '#dc2626';
            iconEl.innerHTML = '<i class="bi bi-file-earmark-pdf-fill"></i>';
        } else if (['doc', 'docx'].includes(ext)) {
            iconEl.style.background = '#eff6ff';
            iconEl.style.color = '#2563eb';
            iconEl.innerHTML = '<i class="bi bi-file-earmark-word-fill"></i>';
        } else if (['xls', 'xlsx'].includes(ext)) {
            iconEl.style.background = '#ecfdf5';
            iconEl.style.color = '#059669';
            iconEl.innerHTML = '<i class="bi bi-file-earmark-excel-fill"></i>';
        } else if (['ppt', 'pptx'].includes(ext)) {
            iconEl.style.background = '#fff7ed';
            iconEl.style.color = '#ea580c';
            iconEl.innerHTML = '<i class="bi bi-file-earmark-ppt-fill"></i>';
        } else if (['zip', 'rar'].includes(ext)) {
            iconEl.style.background = '#f5f3ff';
            iconEl.style.color = '#7c3aed';
            iconEl.innerHTML = '<i class="bi bi-file-earmark-zip-fill"></i>';
        } else {
            iconEl.style.background = '#f1f5f9';
            iconEl.style.color = '#475569';
            iconEl.innerHTML = '<i class="bi bi-file-earmark-fill"></i>';
        }
    }

    if (promptEl) promptEl.style.display = 'none';
    if (selectedEl) selectedEl.style.display = 'block';

    // Auto-fill nama eviden jika input judul masih kosong pada modal Unggah
    if (isUpload) {
        const inputJudul = document.querySelector('#modalUploadZi input[name="judul"]');
        if (inputJudul && !inputJudul.value.trim()) {
            const cleanName = file.name.replace(/\.[^/.]+$/, '').replace(/[_-]+/g, ' ').trim();
            inputJudul.value = cleanName;
        }
    }
}

function resetZiFileDropzone(inputId, dropzoneId) {
    const input = document.getElementById(inputId);
    if (input) input.value = '';

    const isUpload = (dropzoneId === 'ziUploadDropzone');
    const promptEl = document.getElementById(isUpload ? 'ziUploadDropzonePrompt' : 'ziEditDropzonePrompt');
    const selectedEl = document.getElementById(isUpload ? 'ziUploadDropzoneSelected' : 'ziEditDropzoneSelected');

    if (promptEl) promptEl.style.display = 'block';
    if (selectedEl) selectedEl.style.display = 'none';
}

let currentPreviewUrl = '';
let currentPreviewExt = '';

function previewDoc(fileUrl, title, ext) {
    if (!fileUrl) return;

    // Pastikan URL selalu menggunakan skema absolut atau rute assets/downloads
    if (!fileUrl.startsWith('http://') && !fileUrl.startsWith('https://') && !fileUrl.startsWith('/')) {
        fileUrl = '<?= base_url('assets/downloads/') ?>' + fileUrl;
    }

    // Auto-deteksi ekstensi berkas riil dari nama file / URL jika ext generik atau tidak terdeteksi
    const cleanPath = fileUrl.split('?')[0].split('#')[0];
    const dotIdx = cleanPath.lastIndexOf('.');
    if (dotIdx !== -1) {
        const detectedExt = cleanPath.substring(dotIdx + 1).toLowerCase();
        if (['pdf', 'doc', 'docx', 'xls', 'xlsx', 'ppt', 'pptx', 'jpg', 'jpeg', 'png', 'webp', 'gif'].includes(detectedExt)) {
            ext = detectedExt;
        }
    }

    ext = (ext || 'pdf').toLowerCase().trim();
    currentPreviewUrl = fileUrl;
    currentPreviewExt = ext;

    const modalEl = document.getElementById('modalPreviewPdfZi');
    const labelEl = document.getElementById('modalPreviewPdfZiLabel');
    const badgeEl = document.getElementById('previewDocBadge');
    const iframeEl = document.getElementById('iframePdfViewerZi');
    const imgViewer = document.getElementById('previewImageViewer');
    const imgTag = document.getElementById('previewImageTag');
    const btnDownload = document.getElementById('btnPdfDownloadDirectZi');
    const btnDownloadLabel = document.getElementById('btnPdfDownloadLabel');
    const btnOpenTab = document.getElementById('btnPreviewOpenTab');
    const btnGroupSwitch = document.getElementById('btnGroupViewerSwitch');
    const spinner = document.getElementById('previewLoadingSpinner');

    if (labelEl) labelEl.textContent = title || 'Pratinjau Eviden';
    if (btnDownload) {
        btnDownload.href = fileUrl;
        const cleanTitle = (title ? title.replace(/[^a-zA-Z0-9_\-]/g, '_') : 'dokumen_eviden');
        btnDownload.setAttribute('download', cleanTitle + '.' + ext);
    }
    if (btnOpenTab) btnOpenTab.href = fileUrl;
    if (btnDownloadLabel) btnDownloadLabel.textContent = 'Unduh ' + ext.toUpperCase();

    // Sesuaikan badge format berkas
    if (badgeEl) {
        badgeEl.textContent = ext.toUpperCase();
        if (ext === 'pdf') {
            badgeEl.className = 'badge bg-danger-subtle text-danger px-2.5 py-1 font-monospace';
        } else if (['doc', 'docx'].includes(ext)) {
            badgeEl.className = 'badge bg-primary-subtle text-primary px-2.5 py-1 font-monospace';
        } else if (['xls', 'xlsx'].includes(ext)) {
            badgeEl.className = 'badge bg-success-subtle text-success px-2.5 py-1 font-monospace';
        } else if (['ppt', 'pptx'].includes(ext)) {
            badgeEl.className = 'badge bg-warning-subtle text-warning px-2.5 py-1 font-monospace';
        } else {
            badgeEl.className = 'badge bg-info-subtle text-info px-2.5 py-1 font-monospace';
        }
    }

    // Tampilkan sesuai tipe berkas
    if (['jpg', 'jpeg', 'png', 'webp', 'gif'].includes(ext)) {
        if (iframeEl) iframeEl.style.display = 'none';
        if (imgViewer) imgViewer.style.display = 'flex';
        if (imgTag) imgTag.src = fileUrl;
        if (btnGroupSwitch) btnGroupSwitch.style.display = 'none';
        if (spinner) spinner.style.display = 'none';
    } else {
        if (imgViewer) imgViewer.style.display = 'none';
        if (iframeEl) iframeEl.style.display = 'block';

        if (ext === 'pdf') {
            if (btnGroupSwitch) btnGroupSwitch.style.display = 'none';
            if (spinner) spinner.style.display = 'none';
            iframeEl.src = fileUrl;
        } else if (['doc', 'docx', 'xls', 'xlsx', 'ppt', 'pptx'].includes(ext)) {
            if (btnGroupSwitch) btnGroupSwitch.style.display = 'inline-flex';
            switchViewerEngine('office');
        } else {
            if (btnGroupSwitch) btnGroupSwitch.style.display = 'none';
            iframeEl.src = fileUrl;
        }
    }

    const bsModal = new bootstrap.Modal(modalEl);
    bsModal.show();
}

function switchViewerEngine(engine) {
    const iframeEl = document.getElementById('iframePdfViewerZi');
    const spinner = document.getElementById('previewLoadingSpinner');
    const loadingText = document.getElementById('previewLoadingText');
    const btnOffice = document.getElementById('btnViewerOffice');
    const btnGoogle = document.getElementById('btnViewerGoogle');

    if (!iframeEl || !currentPreviewUrl) return;

    if (spinner) {
        spinner.style.display = 'block';
        if (loadingText) {
            loadingText.textContent = 'Menghubungkan ke ' + (engine === 'office' ? 'Microsoft Office Online' : 'Google Docs') + ' Viewer...';
        }
    }

    // Pastikan URL absolut lengkap untuk viewer pihak ketiga
    let fullUrl = currentPreviewUrl;
    if (fullUrl.startsWith('/') || !fullUrl.startsWith('http')) {
        fullUrl = window.location.origin + (fullUrl.startsWith('/') ? '' : '/') + fullUrl;
    }

    if (engine === 'office') {
        if (btnOffice) btnOffice.classList.add('active');
        if (btnGoogle) btnGoogle.classList.remove('active');
        iframeEl.src = 'https://view.officeapps.live.com/op/embed.aspx?src=' + encodeURIComponent(fullUrl);
    } else {
        if (btnGoogle) btnGoogle.classList.add('active');
        if (btnOffice) btnOffice.classList.remove('active');
        iframeEl.src = 'https://docs.google.com/viewer?url=' + encodeURIComponent(fullUrl) + '&embedded=true';
    }

    iframeEl.onload = function() {
        if (spinner) spinner.style.display = 'none';
    };
}

function previewPdf(pdfUrl, title) {
    previewDoc(pdfUrl, title, 'pdf');
}

function openEditModalZi(doc) {
    if (!doc) return;

    resetZiFileDropzone('editZiFileInput', 'ziEditDropzone');

    document.getElementById('editZiId').value = doc.id || '';
    document.getElementById('editZiJudul').value = doc.judul || '';
    document.getElementById('editZiTanggal').value = doc.tanggal || '<?= date('Y-m-d') ?>';
    document.getElementById('editZiArea').value = (doc.area_zi || 'area1').toLowerCase();
    document.getElementById('editZiPengunggah').value = doc.pengunggah || '';
    document.getElementById('editZiLiniUnit').value = doc.lini_unit || '';
    document.getElementById('editZiKeterangan').value = doc.keterangan || '';

    const isDrive = (doc.tipe_sumber === 'drive_link') || (!doc.file_path && doc.link_drive) || (doc.file_path === 'drive_link');
    if (isDrive) {
        document.getElementById('editZiRadioDrive').checked = true;
        document.getElementById('editZiLinkDrive').value = doc.link_drive || '';
        toggleEditZiSumber('drive_link');
    } else {
        document.getElementById('editZiRadioFile').checked = true;
        toggleEditZiSumber('file');
        const note = document.getElementById('editZiFileNote');
        if (note) {
            note.textContent = doc.file_path ? 'File saat ini: ' + doc.file_path + '. Biarkan kosong jika tidak ingin mengganti.' : 'Biarkan kosong jika tidak ingin mengganti.';
        }
    }

    const editModal = new bootstrap.Modal(document.getElementById('modalEditZi'));
    editModal.show();
}

function toggleEditZiSumber(type) {
    const paneFile = document.getElementById('editZiFilePane');
    const paneDrive = document.getElementById('editZiDrivePane');
    const inputDrive = document.getElementById('editZiLinkDrive');

    if (type === 'file') {
        paneFile.style.display = 'block';
        paneDrive.style.display = 'none';
        if (inputDrive) inputDrive.removeAttribute('required');
    } else {
        paneFile.style.display = 'none';
        paneDrive.style.display = 'block';
        if (inputDrive) inputDrive.setAttribute('required', 'required');
    }
}

function openCopyModalZi(doc) {
    if (!doc) return;

    document.getElementById('copyZiSourceId').value = doc.id || '';
    document.getElementById('copyZiSourceJudul').textContent = doc.judul || '-';
    document.getElementById('copyZiJudul').value = doc.judul || '';
    
    const sourceArea = (doc.area_zi || 'area1').toLowerCase();
    const areaMap = {
        'shared': 'Dokumen Bersama / Induk ZI',
        'area1': 'Pokja I: Manajemen Perubahan',
        'area2': 'Pokja II: Penataan Tatalaksana',
        'area3': 'Pokja III: Penataan Manajemen SDM',
        'area4': 'Pokja IV: Penguatan Akuntabilitas',
        'area5': 'Pokja V: Penguatan Pengawasan',
        'area6': 'Pokja VI: Kualitas Pelayanan'
    };
    
    const areaBadge = document.getElementById('copyZiSourceArea');
    if (areaBadge) {
        areaBadge.textContent = areaMap[sourceArea] || 'Pokja ZI';
        if (sourceArea === 'shared') {
            areaBadge.className = 'badge bg-primary text-white';
        } else {
            areaBadge.className = 'badge bg-secondary-subtle text-secondary';
        }
    }
    
    const pengunggahText = document.getElementById('copyZiSourcePengunggah');
    if (pengunggahText) {
        pengunggahText.textContent = 'Oleh: ' + (doc.pengunggah || 'Tim ZI');
    }

    // Default target Pokja
    const targetSelect = document.getElementById('copyZiTargetPokja');
    if (targetSelect) {
        if (currentArea !== 'all' && currentArea !== 'shared' && currentArea !== sourceArea) {
            targetSelect.value = currentArea;
        } else if (sourceArea === 'shared') {
            targetSelect.value = 'area1';
        } else {
            targetSelect.value = (sourceArea === 'area1') ? 'area2' : 'area1';
        }
    }

    const copyModal = new bootstrap.Modal(document.getElementById('modalCopyZi'));
    copyModal.show();
}

function openDeleteModalZi(doc) {
    if (!doc) return;

    document.getElementById('deleteZiId').value = doc.id || '';
    document.getElementById('deleteZiJudul').textContent = doc.judul || '-';
    
    const sourceArea = (doc.area_zi || 'area1').toLowerCase();
    const areaMap = {
        'shared': 'Dokumen Bersama / Induk ZI',
        'area1': 'Pokja I: Manajemen Perubahan',
        'area2': 'Pokja II: Penataan Tatalaksana',
        'area3': 'Pokja III: Penataan Manajemen SDM',
        'area4': 'Pokja IV: Penguatan Akuntabilitas',
        'area5': 'Pokja V: Penguatan Pengawasan',
        'area6': 'Pokja VI: Kualitas Pelayanan'
    };
    
    const areaBadge = document.getElementById('deleteZiArea');
    if (areaBadge) {
        areaBadge.textContent = areaMap[sourceArea] || 'Pokja ZI';
    }
    
    const pengunggahText = document.getElementById('deleteZiPengunggah');
    if (pengunggahText) {
        pengunggahText.textContent = 'Pengunggah: ' + (doc.pengunggah || 'Tim ZI');
    }

    const deleteModal = new bootstrap.Modal(document.getElementById('modalDeleteZi'));
    deleteModal.show();
}

// Inisialisasi & sinkronisasi dropdown Pokja pada Modal Unggah
document.addEventListener('DOMContentLoaded', function() {
    const modalUploadZiEl = document.getElementById('modalUploadZi');
    if (modalUploadZiEl) {
        modalUploadZiEl.addEventListener('show.bs.modal', function () {
            const selectArea = document.getElementById('uploadZiArea');
            if (selectArea) {
                // Jika sedang di tab Pokja spesifik (area1-area6), otomatis sinkronkan.
                // Jika di tab 'all' atau 'shared', set placeholder agar wajib dipilih oleh pengunggah.
                if (typeof currentArea !== 'undefined' && currentArea.startsWith('area')) {
                    selectArea.value = currentArea;
                    const liniSelect = document.querySelector('#modalUploadZi select[name="lini_unit"]');
                    const pokjaMap = {
                        'area1': 'Tim Pokja I', 'area2': 'Tim Pokja II', 'area3': 'Tim Pokja III',
                        'area4': 'Tim Pokja IV', 'area5': 'Tim Pokja V', 'area6': 'Tim Pokja VI'
                    };
                    if (liniSelect && pokjaMap[currentArea]) liniSelect.value = pokjaMap[currentArea];
                } else {
                    selectArea.value = '';
                }
            }
        });
    }

    const uploadZiAreaSelect = document.getElementById('uploadZiArea');
    if (uploadZiAreaSelect) {
        uploadZiAreaSelect.addEventListener('change', function () {
            const liniSelect = document.querySelector('#modalUploadZi select[name="lini_unit"]');
            if (!liniSelect) return;
            const pokjaMap = {
                'area1': 'Tim Pokja I',
                'area2': 'Tim Pokja II',
                'area3': 'Tim Pokja III',
                'area4': 'Tim Pokja IV',
                'area5': 'Tim Pokja V',
                'area6': 'Tim Pokja VI',
                'shared': 'Sekretariat ZI'
            };
            if (pokjaMap[this.value]) {
                liniSelect.value = pokjaMap[this.value];
            }
        });
    }
});
</script>

<!-- ═══ MODAL EDIT EVIDEN ZI ═══ -->
<div class="modal fade" id="modalEditZi" tabindex="-1" aria-labelledby="modalEditZiLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content rounded-4 border-0 shadow-lg">
            
            <div class="modal-header border-0 bg-warning-subtle text-dark px-4 py-3 border-bottom">
                <div>
                    <h5 class="modal-title fw-bold" id="modalEditZiLabel">
                        <i class="bi bi-pencil-square me-1 text-warning"></i> Edit / Revisi Eviden ZI
                    </h5>
                    <p class="small text-muted mb-0">Perbaiki nama dokumen, nama pengunggah, pindah Pokja, atau perbarui file/link Drive.</p>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <form action="<?= base_url('website/update_zi') ?>" method="POST" enctype="multipart/form-data">
                <input type="hidden" name="id" id="editZiId">

                <div class="modal-body p-4">
                    
                    <!-- 1. Pokja ZI -->
                    <div class="row g-3 mb-3">
                        <div class="col-md-12">
                            <label class="form-label fw-bold small text-muted">Pokja Perubahan ZI <span class="text-danger">*</span></label>
                            <select name="area_zi" id="editZiArea" class="form-select rounded-3 text-dark fw-bold" required>
                                <option value="area1">Pokja I: Manajemen Perubahan (Budaya Kerja &amp; Komitmen)</option>
                                <option value="area2">Pokja II: Penataan Tatalaksana (SOP &amp; Digitalisasi e-Office)</option>
                                <option value="area3">Pokja III: Penataan Manajemen SDM (Disiplin &amp; Kinerja GTK)</option>
                                <option value="area4">Pokja IV: Penguatan Akuntabilitas (LAKIP &amp; Capaian Sasaran)</option>
                                <option value="area5">Pokja V: Penguatan Pengawasan (Gratifikasi, WBS, SPI)</option>
                                <option value="area6">Pokja VI: Peningkatan Kualitas Pelayanan Publik (IKM &amp; Layanan)</option>
                                <option value="shared">🏛️ Dokumen Bersama / Induk ZI (Dapat disalin ke Pokja I–VI)</option>
                            </select>
                        </div>
                    </div>

                    <!-- 2. Nama Dokumen & Tanggal -->
                    <div class="row g-3 mb-3">
                        <div class="col-md-8">
                            <label class="form-label fw-bold small text-muted">Nama / Judul Dokumen Eviden <span class="text-danger">*</span></label>
                            <input type="text" name="judul" id="editZiJudul" class="form-control rounded-3" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-bold small text-muted">Tanggal Dokumen</label>
                            <input type="date" name="tanggal" id="editZiTanggal" class="form-control rounded-3">
                        </div>
                    </div>

                    <!-- 3. Keterangan Singkat -->
                    <div class="mb-3">
                        <label class="form-label fw-bold small text-muted">Keterangan / Nomor Dokumen (Opsional)</label>
                        <textarea name="keterangan" id="editZiKeterangan" class="form-control rounded-3" rows="2" placeholder="Catatan peruntukan eviden atau nomor surat..."></textarea>
                    </div>

                    <!-- 4. Pengunggah & Tim Pokja -->
                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label class="form-label fw-bold small text-muted">Nama Pengunggah (PTK / Tim Pokja) <span class="text-danger">*</span></label>
                            <input type="text" name="pengunggah" id="editZiPengunggah" list="ptkSuggestionsZi" class="form-control rounded-3" placeholder="Ketik atau pilih nama staf / guru..." required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold small text-muted">Lini / Tim Pokja Kerja</label>
                            <input type="text" name="lini_unit" id="editZiLiniUnit" class="form-control rounded-3" placeholder="Contoh: Tim Pokja I">
                        </div>
                    </div>

                    <!-- 5. Sumber Dokumen -->
                    <div class="p-3 rounded-4 bg-light">
                        <label class="form-label fw-bold small text-muted d-block mb-2">Sumber Dokumen</label>
                        <div class="d-flex gap-4 mb-2">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="tipe_sumber" id="editZiRadioFile" value="file" onchange="toggleEditZiSumber('file')">
                                <label class="form-check-label small fw-semibold" for="editZiRadioFile">File Fisik</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="tipe_sumber" id="editZiRadioDrive" value="drive_link" onchange="toggleEditZiSumber('drive_link')">
                                <label class="form-check-label small fw-semibold" for="editZiRadioDrive">Tautan Google Drive</label>
                            </div>
                        </div>

                        <div id="editZiFilePane">
                            <div class="zi-dropzone py-3" id="ziEditDropzone" onclick="document.getElementById('editZiFileInput').click()">
                                <input type="file" name="file_download" id="editZiFileInput" style="display:none;" accept=".pdf,.doc,.docx,.xls,.xlsx,.ppt,.pptx,.zip,.rar" onchange="handleZiFileSelected(this, 'ziEditDropzone')">
                                
                                <div class="zi-dropzone-prompt" id="ziEditDropzonePrompt">
                                    <div class="zi-dropzone-icon" style="width:42px; height:42px; font-size:20px; margin-bottom:6px;">
                                        <i class="bi bi-arrow-repeat"></i>
                                    </div>
                                    <div class="fw-bold text-dark mb-1" style="font-size: 13.5px;">
                                        Tarik Berkas Pengganti ke Sini atau <span class="text-primary text-decoration-underline fw-semibold">Pilih File Baru</span>
                                    </div>
                                    <div class="small text-muted" id="editZiFileNote" style="font-size: 11.5px;">
                                        Biarkan kosong jika tidak ingin mengganti file lama.
                                    </div>
                                </div>

                                <div class="zi-dropzone-file-selected" id="ziEditDropzoneSelected" style="display:none;">
                                    <div class="d-flex align-items-center justify-content-between gap-2">
                                        <div class="d-flex align-items-center gap-2.5 overflow-hidden">
                                            <div id="ziEditFileIcon" class="file-type-iconbox" style="width: 38px; height: 38px; font-size: 18px; border-radius: 10px; flex-shrink: 0; background: #ecfdf5; color: #059669;">
                                                <i class="bi bi-file-earmark-check-fill"></i>
                                            </div>
                                            <div class="text-truncate">
                                                <strong class="d-block text-dark text-truncate" id="ziEditFileName" style="font-size: 13.5px;">file-baru.pdf</strong>
                                                <span class="small text-muted" id="ziEditFileSize" style="font-size: 11.5px;">1.2 MB</span>
                                            </div>
                                        </div>
                                        <button type="button" class="btn btn-sm btn-outline-danger rounded-pill px-2.5 py-1 flex-shrink-0" title="Batal Ganti" onclick="event.stopPropagation(); resetZiFileDropzone('editZiFileInput', 'ziEditDropzone');">
                                            <i class="bi bi-x-lg me-1"></i> Batal
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div id="editZiDrivePane" style="display: none;">
                            <input type="url" name="link_drive" id="editZiLinkDrive" class="form-control rounded-3" placeholder="https://drive.google.com/file/d/...">
                            <div class="form-text small mt-1">Tempel tautan Google Drive / Cloud URL yang baru.</div>
                        </div>
                    </div>

                    <?php if(empty($zi_unlocked) && empty($is_user_logged_in)): ?>
                    <div class="mt-3 p-3 bg-warning-subtle border border-warning-subtle rounded-3">
                        <label class="form-label fw-bold small text-dark mb-1">
                            <i class="bi bi-shield-lock-fill me-1 text-warning"></i> PIN Akses Tim ZI <span class="text-danger">*</span>
                        </label>
                        <input type="password" name="pin" class="form-control rounded-3" placeholder="Masukkan 6-digit PIN Tim ZI" required autocomplete="off">
                        <div class="form-text small text-muted" style="font-size: 11px;">
                            Masukkan PIN untuk memverifikasi hak perubahan / revisi berkas ini.
                        </div>
                    </div>
                    <?php endif; ?>

                </div>

                <div class="modal-footer border-0 bg-light p-3 rounded-bottom-4">
                    <button type="button" class="btn btn-outline-secondary btn-sm rounded-pill px-4" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-success btn-sm rounded-pill px-4 fw-bold shadow-sm">
                        <i class="bi bi-check-lg me-1"></i> Simpan Perubahan
                    </button>
                </div>
            </form>

        </div>
    </div>
</div>

<!-- ═══ MODAL HAPUS EVIDEN ZI ═══ -->
<div class="modal fade" id="modalDeleteZi" tabindex="-1" aria-labelledby="modalDeleteZiLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content rounded-4 border-0 shadow-lg">
            
            <div class="modal-header border-0 bg-danger text-white px-4 py-3" style="background: linear-gradient(135deg, #991b1b 0%, #ef4444 100%);">
                <div>
                    <h5 class="modal-title fw-bold" id="modalDeleteZiLabel">
                        <i class="bi bi-trash3-fill me-1 text-warning"></i> Hapus Berkas Eviden ZI
                    </h5>
                    <p class="small text-white-50 mb-0">Hapus berkas yang salah diunggah dari portal Pokja ZI.</p>
                </div>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <form action="<?= base_url('website/delete_zi') ?>" method="POST">
                <input type="hidden" name="id" id="deleteZiId">

                <div class="modal-body p-4">
                    <!-- Info Dokumen yang akan dihapus -->
                    <div class="p-3 bg-light rounded-3 mb-3 border border-danger-subtle">
                        <span class="small text-danger fw-bold d-block mb-1">Dokumen yang akan dihapus:</span>
                        <h6 class="fw-bold text-dark mb-1" id="deleteZiJudul">-</h6>
                        <div class="d-flex align-items-center gap-2 small text-muted mt-2">
                            <span id="deleteZiArea" class="badge bg-secondary-subtle text-secondary">-</span>
                            <span id="deleteZiPengunggah">-</span>
                        </div>
                    </div>

                    <div class="alert alert-warning border-0 rounded-3 p-2.5 small mb-3 d-flex align-items-start gap-2">
                        <i class="bi bi-exclamation-triangle-fill text-warning fs-5 flex-shrink-0 mt-0.5"></i>
                        <span>Pastikan berkas ini memang salah unggah. Dokumen yang dihapus tidak akan tampil lagi di portal eviden Pokja.</span>
                    </div>

                    <?php if(empty($zi_unlocked) && empty($is_user_logged_in)): ?>
                    <div class="mb-3 p-3 bg-danger-subtle border border-danger-subtle rounded-3">
                        <label class="form-label fw-bold small text-danger mb-1">
                            <i class="bi bi-shield-lock-fill me-1"></i> Masukkan PIN Tim ZI <span class="text-danger">*</span>
                        </label>
                        <input type="password" name="pin" class="form-control rounded-3" placeholder="Masukkan 6-digit PIN Tim ZI" required autocomplete="off">
                        <div class="form-text small text-muted" style="font-size: 11px;">
                            Masukkan PIN untuk memverifikasi hak penghapusan berkas ini.
                        </div>
                    </div>
                    <?php endif; ?>
                </div>

                <div class="modal-footer border-0 bg-light p-3 rounded-bottom-4">
                    <button type="button" class="btn btn-outline-secondary btn-sm rounded-pill px-4" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-danger btn-sm rounded-pill px-4 fw-bold shadow-sm">
                        <i class="bi bi-trash-fill me-1"></i> Ya, Hapus Berkas Ini
                    </button>
                </div>
            </form>

        </div>
    </div>
</div>

<!-- ═══ MODAL SALIN DOKUMEN KE POKJA ═══ -->
<div class="modal fade" id="modalCopyZi" tabindex="-1" aria-labelledby="modalCopyZiLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content rounded-4 border-0 shadow-lg">
            
            <div class="modal-header border-0 text-white px-4 py-3" style="background: linear-gradient(135deg, #1e3a8a 0%, #2563eb 100%);">
                <div>
                    <h5 class="modal-title fw-bold" id="modalCopyZiLabel">
                        <i class="bi bi-copy me-1 text-warning"></i> Salin Dokumen ke Pokja Anda
                    </h5>
                    <p class="small text-white-50 mb-0">Duplikasi berkas eviden ini langsung ke folder Pokja kerja Anda.</p>
                </div>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <form action="<?= base_url('website/copy_zi') ?>" method="POST">
                <input type="hidden" name="source_id" id="copyZiSourceId">

                <div class="modal-body p-4">
                    <!-- Info Dokumen Asal -->
                    <div class="p-3 bg-light rounded-3 mb-3 border">
                        <span class="small text-muted d-block mb-1">Dokumen Sumber:</span>
                        <h6 class="fw-bold text-dark mb-1" id="copyZiSourceJudul">-</h6>
                        <div class="d-flex align-items-center gap-2 small text-muted">
                            <span id="copyZiSourceArea" class="badge bg-secondary-subtle text-secondary">-</span>
                            <span id="copyZiSourcePengunggah">-</span>
                        </div>
                    </div>

                    <!-- Pilih Pokja Tujuan -->
                    <div class="mb-3">
                        <label class="form-label fw-bold small text-muted">Salin ke Pokja Tujuan <span class="text-danger">*</span></label>
                        <select name="target_pokja" id="copyZiTargetPokja" class="form-select rounded-3 text-primary fw-bold" required>
                            <option value="area1">Pokja I: Manajemen Perubahan (Budaya Kerja &amp; Komitmen)</option>
                            <option value="area2">Pokja II: Penataan Tatalaksana (SOP &amp; e-Office)</option>
                            <option value="area3">Pokja III: Penataan Manajemen SDM (Disiplin &amp; Kinerja)</option>
                            <option value="area4">Pokja IV: Penguatan Akuntabilitas (LAKIP &amp; Sasaran)</option>
                            <option value="area5">Pokja V: Penguatan Pengawasan (Gratifikasi &amp; WBS)</option>
                            <option value="area6">Pokja VI: Kualitas Pelayanan (Inovasi &amp; IKM)</option>
                            <option value="shared">Dokumen Bersama / Induk ZI</option>
                        </select>
                    </div>

                    <!-- Nama Dokumen Baru -->
                    <div class="mb-3">
                        <label class="form-label fw-bold small text-muted">Nama Dokumen di Pokja Baru <span class="text-danger">*</span></label>
                        <input type="text" name="judul" id="copyZiJudul" class="form-control rounded-3" required>
                        <div class="form-text small text-muted">Dapat disesuaikan dengan kode indikator atau nama eviden Pokja Anda.</div>
                    </div>

                    <!-- PIC / Pengunggah Pokja -->
                    <div class="row g-2 mb-3">
                        <div class="col-md-7">
                            <label class="form-label fw-bold small text-muted">Nama PIC Pokja Pengambil <span class="text-danger">*</span></label>
                            <input type="text" name="pengunggah" id="copyZiPengunggah" list="ptkSuggestionsZi" class="form-control rounded-3" placeholder="Ketik atau pilih nama staf / guru..." required value="<?= $is_user_logged_in ? htmlspecialchars($logged_user_name) : '' ?>">
                        </div>
                        <div class="col-md-5">
                            <label class="form-label fw-bold small text-muted">Tanggal Salin</label>
                            <input type="date" name="tanggal" id="copyZiTanggal" class="form-control rounded-3" value="<?= date('Y-m-d') ?>">
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold small text-muted">Catatan Pokja (Opsional)</label>
                        <textarea name="keterangan" id="copyZiKeterangan" class="form-control rounded-3" rows="2" placeholder="Contoh: Bukti dukung indikator Fungsionalisasi SOP Pokja II"></textarea>
                    </div>

                    <?php if(empty($zi_unlocked) && empty($is_user_logged_in)): ?>
                    <div class="mb-3 p-3 bg-primary-subtle border border-primary-subtle rounded-3">
                        <label class="form-label fw-bold small text-primary mb-1">
                            <i class="bi bi-shield-lock-fill me-1"></i> PIN Akses Tim ZI <span class="text-danger">*</span>
                        </label>
                        <input type="password" name="pin" class="form-control rounded-3" placeholder="Masukkan 6-digit PIN Tim ZI" required autocomplete="off">
                        <div class="form-text small text-muted" style="font-size: 11px;">
                            Masukkan PIN untuk memverifikasi hak akses duplikasi berkas ke Pokja Anda.
                        </div>
                    </div>
                    <?php endif; ?>

                    <div class="alert alert-info border-0 rounded-3 p-2 small mb-0 d-flex align-items-center gap-2">
                        <i class="bi bi-info-circle-fill text-primary fs-5"></i>
                        <span>Berkas fisik/tautan Drive otomatis terhubung tanpa mengunggah ulang (sangat hemat penyimpanan server).</span>
                    </div>
                </div>

                <div class="modal-footer border-0 bg-light p-3 rounded-bottom-4">
                    <button type="button" class="btn btn-outline-secondary btn-sm rounded-pill px-4" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary btn-sm rounded-pill px-4 fw-bold shadow-sm">
                        <i class="bi bi-copy me-1"></i> Konfirmasi Salin ke Pokja
                    </button>
                </div>
            </form>

        </div>
    </div>
</div>

<!-- ═══ MODAL DETAIL EVIDEN & INSTRUMEN PMPZI ═══ -->
<div class="modal fade" id="modalPmpziEvidenceDetail" tabindex="-1" aria-labelledby="modalPmpziEvidenceDetailLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-xl modal-dialog-scrollable">
        <div class="modal-content rounded-4 border-0 shadow-lg">
            
            <div class="modal-header border-0 bg-success text-white px-4 py-3" style="background: linear-gradient(135deg, #064e3b 0%, #059669 100%);">
                <div>
                    <div class="d-flex align-items-center gap-2 mb-1">
                        <span class="badge bg-warning text-dark fw-bold rounded-pill" style="font-size: 11px;">
                            <i class="bi bi-shield-check me-1"></i> PMPZI Kemenag RI 2026
                        </span>
                        <span class="badge bg-white text-dark fw-bold rounded-pill" style="font-size: 11px;" id="modalPmpziBadgeKode">
                            KODE: -
                        </span>
                    </div>
                    <h5 class="modal-title fw-bold text-white mb-0" id="modalPmpziEvidenceDetailLabel">
                        Rincian Instrumen Eviden PMPZI
                    </h5>
                    <p class="small text-white-50 mb-0" id="modalPmpziSubLabel">
                        Area &amp; Aspek Penilaian
                    </p>
                </div>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body p-0">
                <!-- Strip Capaian Area -->
                <div class="bg-light p-3 border-bottom d-flex flex-wrap align-items-center justify-content-between gap-3 px-4">
                    <div class="d-flex align-items-center gap-3">
                        <div>
                            <span class="small text-muted d-block" style="font-size: 11px;">Nilai Capaian:</span>
                            <span class="fw-bold font-monospace fs-5 text-dark" id="modalPmpziNilai">-</span>
                        </div>
                        <div class="vr"></div>
                        <div>
                            <span class="small text-muted d-block" style="font-size: 11px;">Persentase Capaian:</span>
                            <span class="badge bg-success-subtle text-success fs-6 font-monospace fw-bold" id="modalPmpziPersen">- %</span>
                        </div>
                    </div>
                    <div class="d-flex align-items-center gap-2">
                        <button type="button" class="btn btn-outline-success btn-sm rounded-pill px-3 fw-bold" id="btnModalUploadForThisPokja">
                            <i class="bi bi-cloud-arrow-up-fill me-1"></i> + Unggah Eviden untuk Pokja Ini
                        </button>
                        <button type="button" class="btn btn-primary btn-sm rounded-pill px-3 fw-bold shadow-xs" id="btnModalViewAllInArchive">
                            <i class="bi bi-folder2-open me-1"></i> Buka di Bank Berkas
                        </button>
                    </div>
                </div>

                <!-- Container Tabel Butir Pertanyaan -->
                <div class="p-4">
                    <div class="table-responsive">
                        <table class="table table-bordered align-top mb-0" style="font-size: 13px;" id="tablePmpziDetailItems">
                            <thead class="table-light">
                                <tr class="text-uppercase" style="font-size: 11.5px; letter-spacing: 0.5px;">
                                    <th class="text-center" style="width: 50px;">NO</th>
                                    <th style="min-width: 320px;">PENILAIAN (KRITERIA &amp; PERTANYAAN RESMI)</th>
                                    <th class="text-center" style="width: 80px;">JAWABAN</th>
                                    <th style="min-width: 250px;">NAMA / PERSYARATAN DOKUMEN PENDUKUNG</th>
                                    <th style="min-width: 240px;">EVIDEN TERUNGGAH DI LABSYS</th>
                                </tr>
                            </thead>
                            <tbody id="tbodyPmpziDetailItems">
                                <!-- Rendered dynamically via JavaScript -->
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="modal-footer border-0 bg-light p-3 rounded-bottom-4 justify-content-between">
                <span class="text-muted small" style="font-size: 12px;">
                    <i class="bi bi-info-circle me-1"></i>Instrumen penilaian diekstrak langsung dari formulir resmi PMPZI Kemenag RI Permenpan RB 90/2021.
                </span>
                <button type="button" class="btn btn-outline-secondary btn-sm rounded-pill px-4" data-bs-dismiss="modal">Tutup</button>
            </div>

        </div>
    </div>
</div>

<script>
const pmpziMatrixMaster = <?= json_encode($pmpzi_matrix ?? []) ?>;
const allDownloadDocs = <?= json_encode(array_map(function($d){
    $meta = get_zi_file_meta($d);
    $ext = $meta['ext'] ?? strtolower(pathinfo($d->file_path ?? '', PATHINFO_EXTENSION));
    if(empty($ext) || $ext === 'file') $ext = 'pdf';
    return [
        'id'          => $d->id,
        'judul'       => $d->judul,
        'area_zi'     => strtolower($d->area_zi ?? ''),
        'tipe_sumber' => $d->tipe_sumber,
        'file_path'   => $d->file_path,
        'file_ext'    => $ext,
        'file_url'    => $meta['url'],
        'is_link'     => (bool)$meta['is_link'],
        'link_drive'  => $d->link_drive,
        'pengunggah'  => $d->pengunggah,
        'keterangan'  => $d->keterangan ?? '',
        'tanggal'     => $d->tanggal
    ];
}, $downloads)) ?>;

// Helper membuka atau mempratinjau eviden ZI secara aman & presisi
function openOrPreviewZiDoc(docId) {
    const d = allDownloadDocs.find(item => String(item.id) === String(docId));
    if (!d) return;

    if (d.is_link || d.tipe_sumber === 'drive_link') {
        const url = d.link_drive || d.file_url;
        if (url && url !== '#') {
            window.open(url, '_blank');
        } else {
            showGlobalWebToast('Tautan Google Drive belum tersedia.', 'warning');
        }
        return;
    }

    let targetUrl = d.file_url;
    if (!targetUrl || targetUrl === '#') {
        targetUrl = (d.file_path && (d.file_path.startsWith('http') || d.file_path.startsWith('/'))) 
            ? d.file_path 
            : '<?= base_url('assets/downloads/') ?>' + (d.file_path || '');
    }

    let ext = d.file_ext;
    if (!ext || ext === 'file') {
        ext = targetUrl.split('?')[0].split('.').pop().toLowerCase();
    }

    previewDoc(targetUrl, d.judul, ext);
}

// Render baris dokumen eviden dengan badge identitas sumber (PMPZI vs Pokja LabSys)
function renderPmpziEvidenceRow(d) {
    const isPmpzi = (d.pengunggah && d.pengunggah.includes('PMPZI')) || (d.keterangan && d.keterangan.includes('[SYNC_PMPZI'));
    const sourceBadge = isPmpzi 
        ? '<span class="badge bg-success-subtle text-success border border-success-subtle rounded-pill" style="font-size:9.5px; padding: 2px 7px;"><i class="bi bi-cloud-check-fill me-1"></i>PMPZI</span>'
        : `<span class="badge bg-primary-subtle text-primary border border-primary-subtle rounded-pill" style="font-size:9.5px; padding: 2px 7px;" title="Diunggah oleh ${d.pengunggah || 'Tim Pokja'}"><i class="bi bi-person-fill me-1"></i>${d.pengunggah || 'LabSys'}</span>`;
    
    const isDrive = (d.is_link || d.tipe_sumber === 'drive_link');
    let extIcon = 'bi-file-earmark-pdf-fill text-danger';
    if (isDrive) {
        extIcon = 'bi-google text-success';
    } else if (['doc', 'docx'].includes(d.file_ext)) {
        extIcon = 'bi-file-earmark-word-fill text-primary';
    } else if (['xls', 'xlsx'].includes(d.file_ext)) {
        extIcon = 'bi-file-earmark-excel-fill text-success';
    }

    const actionBtn = isDrive
        ? `<a href="${d.link_drive || d.file_url}" target="_blank" class="btn btn-xs btn-outline-success rounded-pill py-0.5 px-2.5 fw-semibold d-inline-flex align-items-center gap-1" style="font-size: 11px;"><i class="bi bi-box-arrow-up-right"></i> Drive</a>`
        : `<button type="button" class="btn btn-xs btn-outline-primary rounded-pill py-0.5 px-2.5 fw-semibold d-inline-flex align-items-center gap-1" style="font-size: 11px;" onclick="openOrPreviewZiDoc(${d.id})"><i class="bi bi-eye"></i> Buka</button>`;

    return `
        <div class="p-2 mb-1.5 bg-light rounded-3 border d-flex align-items-center justify-content-between gap-2">
            <div class="d-flex align-items-center gap-1.5 text-truncate" style="max-width: calc(100% - 75px);">
                <i class="bi ${extIcon} flex-shrink-0" style="font-size: 14px;"></i>
                <span class="text-truncate fw-medium small" title="${d.judul}">${d.judul}</span>
                ${sourceBadge}
            </div>
            <div class="flex-shrink-0">
                ${actionBtn}
            </div>
        </div>
    `;
}

function switchZiMainView(mode) {
    const secMonitoring = document.getElementById('ziSectionMonitoring');
    const secBerkas = document.getElementById('ziSectionBerkas');
    const btnMonitoring = document.getElementById('ziTabMonitoringBtn');
    const btnBerkas = document.getElementById('ziTabBerkasBtn');

    if (mode === 'monitoring') {
        if (secMonitoring) secMonitoring.style.display = 'block';
        if (secBerkas) secBerkas.style.display = 'none';

        if (btnMonitoring) {
            btnMonitoring.classList.add('active', 'bg-success', 'text-white');
            btnMonitoring.classList.remove('text-dark');
            const b = btnMonitoring.querySelector('.badge');
            if (b) b.className = 'badge bg-white text-success rounded-pill font-monospace';
        }
        if (btnBerkas) {
            btnBerkas.classList.remove('active', 'bg-primary', 'text-white');
            btnBerkas.classList.add('text-dark');
            const b = btnBerkas.querySelector('.badge');
            if (b) b.className = 'badge bg-primary text-white rounded-pill font-monospace';
        }
        const url = new URL(window.location);
        url.searchParams.set('tab', 'monitoring');
        window.history.replaceState({}, '', url);
    } else {
        if (secMonitoring) secMonitoring.style.display = 'none';
        if (secBerkas) secBerkas.style.display = 'block';

        if (btnBerkas) {
            btnBerkas.classList.add('active', 'bg-primary', 'text-white');
            btnBerkas.classList.remove('text-dark');
            const b = btnBerkas.querySelector('.badge');
            if (b) b.className = 'badge bg-white text-primary rounded-pill font-monospace';
        }
        if (btnMonitoring) {
            btnMonitoring.classList.remove('active', 'bg-success', 'text-white');
            btnMonitoring.classList.add('text-dark');
            const b = btnMonitoring.querySelector('.badge');
            if (b) b.className = 'badge bg-success text-white rounded-pill font-monospace';
        }
        const url = new URL(window.location);
        url.searchParams.set('tab', 'berkas');
        window.history.replaceState({}, '', url);
    }
}

function openPmpziDetailModal(kodeEv, namaArea, aspek, nilai, persen) {
    const modalEl = document.getElementById('modalPmpziEvidenceDetail');
    if (!modalEl) return;

    // Mapping kode_ev ke area_key
    const codeAreaMap = {
        'A.1.1': 'area1', 'A.2.1': 'area1',
        'A.1.2': 'area2', 'A.2.2': 'area2',
        'A.1.3': 'area3', 'A.2.3': 'area3',
        'A.1.4': 'area4', 'A.2.4': 'area4',
        'A.1.5': 'area5', 'A.2.5': 'area5',
        'A.1.6': 'area6', 'A.2.6': 'area6',
        'B.1': 'area4', 'B.2': 'area6'
    };
    const targetAreaKey = codeAreaMap[kodeEv] || 'area1';

    // Set Labels
    document.getElementById('modalPmpziBadgeKode').textContent = 'KODE: ' + kodeEv;
    document.getElementById('modalPmpziEvidenceDetailLabel').textContent = 'EVIDENCE ' + namaArea.toUpperCase() + ' - ASPEK ' + aspek.toUpperCase();
    document.getElementById('modalPmpziSubLabel').textContent = 'Instrumen Penilaian PMPZI Tahun 2026 (Sesuai Permenpan 90/2021)';
    document.getElementById('modalPmpziNilai').textContent = parseFloat(nilai).toFixed(2);
    document.getElementById('modalPmpziPersen').textContent = parseFloat(persen).toFixed(2) + '%';

    // Setup Action Buttons
    const btnUpload = document.getElementById('btnModalUploadForThisPokja');
    if (btnUpload) {
        btnUpload.onclick = function() {
            const bsDetail = bootstrap.Modal.getInstance(modalEl);
            if (bsDetail) bsDetail.hide();
            
            const modalUpload = new bootstrap.Modal(document.getElementById('modalUploadZi'));
            const selectArea = document.getElementById('uploadZiArea');
            if (selectArea) {
                selectArea.value = targetAreaKey;
                selectArea.dispatchEvent(new Event('change'));
            }
            modalUpload.show();
        };
    }

    const btnViewArchive = document.getElementById('btnModalViewAllInArchive');
    if (btnViewArchive) {
        btnViewArchive.onclick = function() {
            const bsDetail = bootstrap.Modal.getInstance(modalEl);
            if (bsDetail) bsDetail.hide();
            switchZiMainView('berkas');
            selectZiArea(targetAreaKey);
        };
    }

    // Ambil berkas LabSys yang relevan
    const relevantDocs = allDownloadDocs.filter(d => d.area_zi === targetAreaKey);

    // Cari detail butir dari master JSON
    let subKomponenList = [];
    const pokjaList = pmpziMatrixMaster.detail_lke && pmpziMatrixMaster.detail_lke.pokja ? pmpziMatrixMaster.detail_lke.pokja : [];
    
    for (const p of pokjaList) {
        if (p.aspek) {
            for (const asp of p.aspek) {
                if (asp.kode_evidence === kodeEv && asp.sub_komponen) {
                    subKomponenList = asp.sub_komponen;
                    break;
                }
            }
        }
        if (subKomponenList.length > 0) break;
    }

    const tbody = document.getElementById('tbodyPmpziDetailItems');
    tbody.innerHTML = '';

    if (subKomponenList.length === 0) {
        let fallbackDocsHtml = '';
        if (relevantDocs.length > 0) {
            fallbackDocsHtml = relevantDocs.map(d => renderPmpziEvidenceRow(d)).join('');
        } else {
            fallbackDocsHtml = '<span class="text-muted small"><i class="bi bi-exclamation-circle text-warning me-1"></i>Belum ada berkas</span>';
        }

        tbody.innerHTML = `
            <tr>
                <td class="text-center fw-bold">1</td>
                <td>
                    <strong class="text-primary d-block mb-1">${namaArea}</strong>
                    <span class="text-muted small">Kriteria pemenuhan eviden reformasi birokrasi dan capaian kinerja satuan kerja madrasah.</span>
                </td>
                <td class="text-center"><span class="badge bg-success-subtle text-success">Y / A</span></td>
                <td>
                    <span class="small text-dark fw-semibold">Dokumen Bukti Fisik / Digital:</span>
                    <p class="small text-muted mb-0">Buku panduan, SK, laporan monev, dokumentasi kegiatan, atau tautan sistem digital.</p>
                </td>
                <td>
                    ${fallbackDocsHtml}
                    <button type="button" class="btn btn-outline-success btn-sm rounded-pill mt-2 py-0.5 px-2 w-100" style="font-size: 11px;" onclick="document.getElementById('btnModalUploadForThisPokja').click()">
                        <i class="bi bi-plus me-1"></i>Unggah Eviden
                    </button>
                </td>
            </tr>
        `;
    } else {
        let globalItemIndex = 1;
        subKomponenList.forEach(sub => {
            const trSub = document.createElement('tr');
            trSub.className = 'table-light fw-bold';
            trSub.innerHTML = `
                <td class="text-center bg-light text-muted fw-bold">${sub.kode || ''}</td>
                <td colspan="4" class="text-success fw-bold py-2">
                    <i class="bi bi-folder2 me-1"></i> ${sub.judul}
                </td>
            `;
            tbody.appendChild(trSub);

            if (sub.indikator && sub.indikator.length > 0) {
                sub.indikator.forEach(ind => {
                    const tr = document.createElement('tr');
                    
                    let docsHtml = '';
                    if (relevantDocs.length > 0) {
                        docsHtml = relevantDocs.slice(0, 2).map(d => renderPmpziEvidenceRow(d)).join('');
                        if (relevantDocs.length > 2) {
                            docsHtml += `<small class="text-muted d-block mt-1">+${relevantDocs.length - 2} berkas lainnya di LabSys</small>`;
                        }
                    } else {
                        docsHtml = '<span class="text-muted small" style="font-size: 11.5px;"><i class="bi bi-exclamation-circle text-warning me-1"></i>Belum ada berkas</span>';
                    }

                    tr.innerHTML = `
                        <td class="text-center fw-bold text-muted">${globalItemIndex++}</td>
                        <td>
                            <strong class="text-primary d-block mb-1" style="font-size: 13px;">${ind.pertanyaan}</strong>
                            <div class="small text-muted" style="font-size: 11.5px; line-height: 1.4;">
                                <strong>Kriteria:</strong> ${ind.keterangan ? ind.keterangan.replace(/\\n/g, '<br>') : '-'}
                            </div>
                        </td>
                        <td class="text-center align-middle">
                            <span class="badge bg-success-subtle text-success border border-success-subtle font-monospace fw-bold" style="font-size: 12px;">Y / A</span>
                        </td>
                        <td>
                            <div class="small text-dark fw-semibold mb-1" style="font-size: 12px;">
                                <i class="bi bi-file-earmark-text text-secondary me-1"></i>Dokumen Wajib:
                            </div>
                            <div class="small text-muted" style="font-size: 11.5px; line-height: 1.4;">
                                ${ind.eviden_wajib || 'SK Tim, Laporan, Dokumentasi rapat / notula, foto kegiatan, atau link aplikasi.'}
                            </div>
                        </td>
                        <td>
                            ${docsHtml}
                            <button type="button" class="btn btn-outline-success btn-sm rounded-pill mt-1 py-0.5 px-2 w-100" style="font-size: 11px;" onclick="document.getElementById('btnModalUploadForThisPokja').click()">
                                <i class="bi bi-plus me-1"></i>Unggah Eviden
                            </button>
                        </td>
                    `;
                    tbody.appendChild(tr);
                });
            }
        });
    }

    const bsModal = new bootstrap.Modal(modalEl);
    bsModal.show();
}

// ═════════════════════════════════════════════════════════════════
// ═══ KONTROL SINKRONISASI & ROLLBACK PMPZI KEMENAG RI 2026 ═══
// ═════════════════════════════════════════════════════════════════

let pmpziSyncModalInstance = null;
let pmpziRollbackModalInstance = null;

function startPmpziSync(isSilent = false) {
    const modalEl = document.getElementById('modalPmpziSyncProgress');
    const btnSyncSpinner = document.getElementById('iconSyncSpinner');
    const textBtnSync = document.getElementById('textBtnSync');

    if (btnSyncSpinner) btnSyncSpinner.classList.add('spinner-border', 'spinner-border-sm');
    if (textBtnSync) textBtnSync.textContent = 'Menyinkronkan...';

    if (!isSilent && modalEl) {
        // Reset state modal progres
        document.getElementById('spinStep1').classList.remove('d-none');
        document.getElementById('checkStep1').classList.add('d-none');
        
        document.getElementById('spinStep2').classList.add('d-none');
        document.getElementById('checkStep2').classList.add('d-none');
        document.getElementById('circleStep2').classList.remove('d-none');
        
        document.getElementById('spinStep3').classList.add('d-none');
        document.getElementById('checkStep3').classList.add('d-none');
        document.getElementById('circleStep3').classList.remove('d-none');

        document.getElementById('pmpziProgressBar').style.width = '25%';
        document.getElementById('pmpziSyncSuccessBox').classList.add('d-none');
        document.getElementById('pmpziSyncErrorBox').classList.add('d-none');
        document.getElementById('btnSyncModalClose').classList.add('d-none');
        document.getElementById('btnSyncModalReload').classList.add('d-none');

        pmpziSyncModalInstance = new bootstrap.Modal(modalEl);
        pmpziSyncModalInstance.show();

        // Simulasi progres transisi visual yang halus
        setTimeout(() => {
            document.getElementById('spinStep1').classList.add('d-none');
            document.getElementById('checkStep1').classList.remove('d-none');
            document.getElementById('spinStep2').classList.remove('d-none');
            document.getElementById('circleStep2').classList.add('d-none');
            document.getElementById('pmpziProgressBar').style.width = '60%';
        }, 1200);

        setTimeout(() => {
            document.getElementById('spinStep2').classList.add('d-none');
            document.getElementById('checkStep2').classList.remove('d-none');
            document.getElementById('spinStep3').classList.remove('d-none');
            document.getElementById('circleStep3').classList.add('d-none');
            document.getElementById('pmpziProgressBar').style.width = '85%';
        }, 2400);
    }

    const syncUrl = '<?= base_url('website/sync_pmpzi_ajax') ?>' + (isSilent ? '?auto=1' : '');
    fetch(syncUrl, {
        method: 'POST',
        headers: { 'X-Requested-With': 'XMLHttpRequest' }
    })
    .then(response => {
        if (!response.ok) throw new Error('HTTP error ' + response.status);
        return response.json();
    })
    .then(data => {
        if (btnSyncSpinner) btnSyncSpinner.classList.remove('spinner-border', 'spinner-border-sm');
        if (textBtnSync) textBtnSync.textContent = 'Sinkronkan PMPZI';

        if (data.status === 'success') {
            // Update teks display waktu di sub-nav
            const lastSyncEl = document.getElementById('pmpziLastSyncDisplay');
            if (lastSyncEl && data.last_sync) lastSyncEl.textContent = data.last_sync;

            if (!isSilent) {
                document.getElementById('spinStep3').classList.add('d-none');
                document.getElementById('checkStep3').classList.remove('d-none');
                document.getElementById('pmpziProgressBar').style.width = '100%';

                const successBox = document.getElementById('pmpziSyncSuccessBox');
                const successMsg = document.getElementById('pmpziSyncSuccessMsg');
                if (successMsg) {
                    successMsg.innerHTML = `
                        <strong>${data.message || 'Sinkronisasi berhasil!'}</strong><br>
                        ${data.new_files_count ? data.new_files_count + ' berkas dokumen baru ditambahkan ke LabSys.' : 'Seluruh berkas fisik sudah mutakhir.'}
                    `;
                }
                successBox.classList.remove('d-none');
                document.getElementById('btnSyncModalReload').classList.remove('d-none');
            } else {
                console.log('[LabSys Auto-Sync] Berhasil:', data.message);
            }
        } else {
            if (!isSilent) {
                const errorBox = document.getElementById('pmpziSyncErrorBox');
                const errorMsg = document.getElementById('pmpziSyncErrorMsg');
                if (errorMsg) errorMsg.textContent = data.message || 'Gagal menyinkronkan data.';
                errorBox.classList.remove('d-none');
                document.getElementById('btnSyncModalClose').classList.remove('d-none');
            } else {
                console.warn('[LabSys Auto-Sync] Gagal:', data.message);
            }
        }
    })
    .catch(err => {
        if (btnSyncSpinner) btnSyncSpinner.classList.remove('spinner-border', 'spinner-border-sm');
        if (textBtnSync) textBtnSync.textContent = 'Sinkronkan PMPZI';
        console.error('[PMPZI Sync Error]', err);

        if (!isSilent) {
            const errorBox = document.getElementById('pmpziSyncErrorBox');
            const errorMsg = document.getElementById('pmpziSyncErrorMsg');
            if (errorMsg) errorMsg.textContent = 'Gagal terhubung ke endpoint sinkronisasi: ' + err.message;
            errorBox.classList.remove('d-none');
            document.getElementById('btnSyncModalClose').classList.remove('d-none');
        }
    });
}

function confirmPmpziRollback() {
    const modalEl = document.getElementById('modalPmpziRollbackConfirm');
    if (modalEl) {
        pmpziRollbackModalInstance = new bootstrap.Modal(modalEl);
        pmpziRollbackModalInstance.show();
    }
}

function executePmpziRollback() {
    const btnRollback = document.getElementById('btnConfirmRollbackAction');
    if (btnRollback) {
        btnRollback.disabled = true;
        btnRollback.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Memulihkan...';
    }

    fetch('<?= base_url('website/rollback_pmpzi_ajax') ?>', {
        method: 'POST',
        headers: { 'X-Requested-With': 'XMLHttpRequest' }
    })
    .then(response => response.json())
    .then(data => {
        if (data.status === 'success') {
            alert('Rollback Berhasil: ' + (data.message || 'Database telah dikembalikan ke kondisi semula.'));
            window.location.reload();
        } else {
            alert('Rollback Gagal: ' + (data.message || 'Terjadi kesalahan sistem.'));
            if (btnRollback) {
                btnRollback.disabled = false;
                btnRollback.innerHTML = '<i class="bi bi-arrow-counterclockwise me-1"></i> Ya, Rollback Sekarang';
            }
        }
    })
    .catch(err => {
        alert('Kendala koneksi rollback: ' + err.message);
        if (btnRollback) {
            btnRollback.disabled = false;
            btnRollback.innerHTML = '<i class="bi bi-arrow-counterclockwise me-1"></i> Ya, Rollback Sekarang';
        }
    });
}

// ═══ HOOK AUTO-SYNC OTOMATIS BERKALA DI LATAR BELAKANG ═══
document.addEventListener('DOMContentLoaded', function() {
    const isPmpziExpired = <?= !empty($pmpzi_sync_meta['is_expired']) ? 'true' : 'false' ?>;
    if (isPmpziExpired) {
        // Pemicu auto-sync halus di latar belakang setelah 3 detik render halaman
        setTimeout(() => {
            console.log('[LabSys Auto-Sync] Sesi sinkronisasi kadaluarsa (>6 jam). Memulai auto-sync latar belakang...');
            startPmpziSync(true);
        }, 3000);
    }
});
</script>

<?php $this->load->view('public/partials/archive_footer'); ?>
