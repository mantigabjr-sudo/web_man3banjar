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
            $info['url'] = base_url('uploads/download/'.$d->file_path);

            $abs = FCPATH.'uploads/download/'.$d->file_path;
            if(file_exists($abs)){
                $bytes = filesize($abs);
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
            }
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
$active_view = isset($active_view) ? $active_view : 'grid';
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
                ?>
                <div class="zi-file-card zi-item-row"
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
                            <?php if($file_meta['is_pdf']): ?>
                                <button type="button" class="btn btn-sm btn-outline-danger rounded-pill flex-grow-1 fw-bold" onclick="previewPdf('<?= $file_meta['url'] ?>', '<?= htmlspecialchars($d->judul, ENT_QUOTES, 'UTF-8') ?>')">
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

                            <?php if(!empty($zi_unlocked)): ?>
                                <button type="button" class="btn btn-sm btn-outline-warning rounded-pill px-2.5 py-1 text-dark" title="Edit Eviden ZI" onclick="openEditModalZi(<?= htmlspecialchars(json_encode($d), ENT_QUOTES, 'UTF-8') ?>)">
                                    <i class="bi bi-pencil-square"></i>
                                </button>
                                <a href="<?= base_url('website/delete_zi/'.$d->id) ?>" class="btn btn-sm btn-outline-danger rounded-pill px-2.5 py-1" title="Hapus Eviden" onclick="return confirm('Apakah Anda yakin ingin menghapus eviden \'<?= htmlspecialchars(addslashes($d->judul), ENT_QUOTES, 'UTF-8') ?>\'?')">
                                    <i class="bi bi-trash-fill"></i>
                                </a>
                            <?php endif; ?>
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
                        <th class="ps-4" style="width: 50px;">Format</th>
                        <th>Nama Dokumen / Eviden ZI</th>
                        <th>Pokja ZI</th>
                        <th>Pengunggah (Pokja)</th>
                        <th>Tanggal</th>
                        <th class="text-end pe-4" style="width: 140px;">Aksi</th>
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
                            ?>
                            <tr class="zi-item-row"
                                data-area="<?= $area ?>"
                                data-ext="<?= strtolower($file_meta['ext']) ?>"
                                data-title="<?= htmlspecialchars(strtolower($d->judul ?? ''), ENT_QUOTES, 'UTF-8') ?>"
                                data-pengunggah="<?= htmlspecialchars(strtolower($d->pengunggah ?? ''), ENT_QUOTES, 'UTF-8') ?>">
                                <td class="ps-4">
                                    <div class="file-type-iconbox" style="width: 36px; height: 36px; font-size: 18px; background: <?= $file_meta['bg'] ?>; color: <?= $file_meta['color'] ?>;">
                                        <i class="bi <?= $file_meta['icon'] ?>"></i>
                                    </div>
                                </td>
                                <td>
                                    <a href="<?= $file_meta['url'] ?>" target="_blank" class="fw-bold text-dark text-decoration-none">
                                        <?= htmlspecialchars($d->judul ?? '-', ENT_QUOTES, 'UTF-8') ?>
                                    </a>
                                    <?php if(!empty($d->keterangan)): ?>
                                        <div class="small text-muted text-truncate" style="max-width: 380px;">
                                            <?= htmlspecialchars($d->keterangan, ENT_QUOTES, 'UTF-8') ?>
                                        </div>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php if($area === 'shared'): ?>
                                        <span class="file-badge-pill bg-primary-subtle text-primary border border-primary-subtle">
                                            <i class="bi bi-collection-fill"></i> DOKUMEN BERSAMA
                                        </span>
                                    <?php else: ?>
                                        <span class="file-badge-pill bg-success-subtle text-success border border-success-subtle">
                                            <i class="bi bi-shield-check"></i> <?= str_replace('AREA', 'POKJA ', strtoupper($area)) ?>
                                        </span>
                                    <?php endif; ?>
                                    <div class="small text-muted mt-1" style="font-size: 11px;">
                                        <?= $area_names[$area] ?? 'Zona Integritas' ?>
                                    </div>
                                </td>
                                <td>
                                    <div class="fw-semibold text-dark small">
                                        <i class="bi bi-person-fill text-muted me-1"></i><?= htmlspecialchars($d->pengunggah ?: 'Tim Pokja ZI', ENT_QUOTES, 'UTF-8') ?>
                                    </div>
                                    <?php if(!empty($d->lini_unit)): ?>
                                        <div class="small text-muted" style="font-size: 11px;"><?= htmlspecialchars($d->lini_unit, ENT_QUOTES, 'UTF-8') ?></div>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <div class="small text-dark fw-medium">
                                        <?= !empty($d->tanggal) ? date('d M Y', strtotime($d->tanggal)) : '-' ?>
                                    </div>
                                    <div class="small text-secondary" style="font-size: 11px;">
                                        <?= $file_meta['size'] ?>
                                    </div>
                                </td>
                                <td class="text-end pe-4">
                                    <div class="d-inline-flex gap-1">
                                        <?php if($file_meta['is_pdf']): ?>
                                            <button type="button" class="btn btn-sm btn-outline-danger rounded-pill px-3 py-1 fw-bold" onclick="previewPdf('<?= $file_meta['url'] ?>', '<?= htmlspecialchars($d->judul, ENT_QUOTES, 'UTF-8') ?>')">
                                                <i class="bi bi-eye"></i>
                                            </button>
                                        <?php endif; ?>

                                        <?php if($file_meta['is_link']): ?>
                                            <a href="<?= $file_meta['url'] ?>" target="_blank" class="btn btn-sm btn-outline-success rounded-pill px-3 py-1 fw-bold">
                                                <i class="bi bi-box-arrow-up-right me-1"></i> Drive
                                            </a>
                                        <?php else: ?>
                                            <a href="<?= $file_meta['url'] ?>" target="_blank" download class="btn btn-sm btn-outline-success rounded-pill px-3 py-1 fw-bold">
                                                <i class="bi bi-download me-1"></i> Unduh
                                            </a>
                                        <?php endif; ?>

                                        <!-- Tombol Salin ke Pokja (Selalu Aktif) -->
                                        <button type="button" class="btn btn-sm <?= ($area === 'shared') ? 'btn-primary text-white fw-bold px-3' : 'btn-outline-primary' ?> rounded-pill px-2.5 py-1" title="Salin Dokumen ini ke Pokja" onclick="openCopyModalZi(<?= htmlspecialchars(json_encode($d), ENT_QUOTES, 'UTF-8') ?>)">
                                            <i class="bi bi-copy <?= ($area === 'shared') ? 'me-1' : '' ?>"></i><?= ($area === 'shared') ? 'Salin ke Pokja' : '' ?>
                                        </button>

                                        <?php if(!empty($zi_unlocked)): ?>
                                            <button type="button" class="btn btn-sm btn-outline-warning rounded-pill px-2.5 py-1 text-dark" title="Edit Eviden ZI" onclick="openEditModalZi(<?= htmlspecialchars(json_encode($d), ENT_QUOTES, 'UTF-8') ?>)">
                                                <i class="bi bi-pencil-square"></i>
                                            </button>
                                            <a href="<?= base_url('website/delete_zi/'.$d->id) ?>" class="btn btn-sm btn-outline-danger rounded-pill px-2.5 py-1" title="Hapus Eviden" onclick="return confirm('Apakah Anda yakin ingin menghapus eviden \'<?= htmlspecialchars(addslashes($d->judul), ENT_QUOTES, 'UTF-8') ?>\'?')">
                                                <i class="bi bi-trash-fill"></i>
                                            </a>
                                        <?php endif; ?>
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
                            <select name="area_zi" class="form-select rounded-3 text-success fw-bold" required>
                                <option value="shared" <?= $active_area === 'shared' ? 'selected' : '' ?>>🏛️ Dokumen Bersama / Induk ZI (Dapat disalin ke Pokja I–VI)</option>
                                <option value="area1" <?= $active_area === 'area1' ? 'selected' : '' ?>>Pokja I: Manajemen Perubahan (Budaya Kerja &amp; Komitmen)</option>
                                <option value="area2" <?= $active_area === 'area2' ? 'selected' : '' ?>>Pokja II: Penataan Tatalaksana (SOP &amp; Digitalisasi e-Office)</option>
                                <option value="area3" <?= $active_area === 'area3' ? 'selected' : '' ?>>Pokja III: Penataan Manajemen SDM (Disiplin &amp; Kinerja GTK)</option>
                                <option value="area4" <?= $active_area === 'area4' ? 'selected' : '' ?>>Pokja IV: Penguatan Akuntabilitas (LAKIP &amp; Capaian Sasaran)</option>
                                <option value="area5" <?= $active_area === 'area5' ? 'selected' : '' ?>>Pokja V: Penguatan Pengawasan (Gratifikasi, WBS, SPI)</option>
                                <option value="area6" <?= $active_area === 'area6' ? 'selected' : '' ?>>Pokja VI: Peningkatan Kualitas Pelayanan Publik (IKM &amp; Layanan)</option>
                            </select>
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

                        <!-- Pane File -->
                        <div id="paneZiFile">
                            <input type="file" name="file_download" id="inputZiFile" class="form-control rounded-3" required accept=".pdf,.doc,.docx,.xls,.xlsx,.ppt,.pptx,.zip,.rar">
                            <div class="form-text small text-muted mt-1">
                                <i class="bi bi-info-circle me-1"></i> Format: <strong>PDF, DOC, DOCX, XLS, XLSX, PPT, PPTX, ZIP, RAR</strong> (Maksimal 20MB).
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

<!-- ═══ 5. MODAL PRATINJAU PDF ═══ -->
<div class="modal fade" id="modalPreviewPdfZi" tabindex="-1" aria-labelledby="modalPreviewPdfZiLabel" aria-hidden="true">
    <div class="modal-dialog modal-fullscreen-md-down modal-xl modal-dialog-centered">
        <div class="modal-content rounded-4 border-0 shadow-lg" style="height: 90vh;">
            <div class="modal-header border-0 bg-dark text-white py-2 px-4">
                <h6 class="modal-title fw-semibold text-truncate me-2" id="modalPreviewPdfZiLabel">Pratinjau Eviden PDF</h6>
                <div class="d-flex align-items-center gap-2">
                    <a href="#" id="btnPdfDownloadDirectZi" download class="btn btn-sm btn-success rounded-pill px-3 fw-bold">
                        <i class="bi bi-download me-1"></i> Unduh PDF
                    </a>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
            </div>
            <div class="modal-body p-0" style="height: calc(100% - 56px);">
                <iframe id="iframePdfViewerZi" src="" style="width:100%; height:100%; border:none;"></iframe>
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

        let matchArea = (currentArea === 'all' || area === currentArea);
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

    // Jalankan filter awal sesuai URL atau default
    applyFilters();
});

function previewPdf(pdfUrl, title) {
    const modalEl = document.getElementById('modalPreviewPdfZi');
    const labelEl = document.getElementById('modalPreviewPdfZiLabel');
    const iframeEl = document.getElementById('iframePdfViewerZi');
    const btnDownload = document.getElementById('btnPdfDownloadDirectZi');

    labelEl.textContent = title;
    iframeEl.src = pdfUrl;
    btnDownload.href = pdfUrl;

    const bsModal = new bootstrap.Modal(modalEl);
    bsModal.show();
}

function openEditModalZi(doc) {
    if (!doc) return;

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
                    <p class="small text-muted mb-0">Perbaiki nama dokumen, pindah Pokja, atau perbarui file/link Drive.</p>
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
                            <select name="area_zi" id="editZiArea" class="form-select rounded-3 text-success fw-bold" required>
                                <option value="shared">🏛️ Dokumen Bersama / Induk ZI (Dapat disalin ke Pokja I–VI)</option>
                                <option value="area1">Pokja I: Manajemen Perubahan (Budaya Kerja &amp; Komitmen)</option>
                                <option value="area2">Pokja II: Penataan Tatalaksana (SOP &amp; Digitalisasi e-Office)</option>
                                <option value="area3">Pokja III: Penataan Manajemen SDM (Disiplin &amp; Kinerja GTK)</option>
                                <option value="area4">Pokja IV: Penguatan Akuntabilitas (LAKIP &amp; Capaian Sasaran)</option>
                                <option value="area5">Pokja V: Penguatan Pengawasan (Gratifikasi, WBS, SPI)</option>
                                <option value="area6">Pokja VI: Peningkatan Kualitas Pelayanan Publik (IKM &amp; Layanan)</option>
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
                            <label class="form-label fw-bold small text-muted">Nama Pengunggah (PIC) <span class="text-danger">*</span></label>
                            <input type="text" name="pengunggah" id="editZiPengunggah" class="form-control rounded-3" required>
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
                            <input type="file" name="file_download" id="editZiFileInput" class="form-control rounded-3">
                            <div class="form-text small mt-1 text-muted" id="editZiFileNote">
                                Biarkan kosong jika tidak ingin mengganti file lama.
                            </div>
                        </div>

                        <div id="editZiDrivePane" style="display: none;">
                            <input type="url" name="link_drive" id="editZiLinkDrive" class="form-control rounded-3" placeholder="https://drive.google.com/file/d/...">
                            <div class="form-text small mt-1">Tempel tautan Google Drive / Cloud URL yang baru.</div>
                        </div>
                    </div>

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
                            <input type="text" name="pengunggah" id="copyZiPengunggah" class="form-control rounded-3" required value="<?= $is_user_logged_in ? htmlspecialchars($logged_user_name) : '' ?>">
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

<?php $this->load->view('public/partials/archive_footer'); ?>
