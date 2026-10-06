<?php $this->load->view('public/partials/archive_header'); ?>

<?php
if (!function_exists('get_drive_file_meta')) {
    function get_drive_file_meta($row) {
        $is_drive_link = (!empty($row->link_drive) || ($row->file_path ?? '') === 'drive_link');
        $ext = strtolower(pathinfo($row->file_path ?? '', PATHINFO_EXTENSION));

        if ($is_drive_link) {
            return [
                'type' => 'DRIVE',
                'ext' => 'DRIVE',
                'icon' => 'bi-google',
                'color' => '#0f9d58',
                'bg' => '#e6f4ea',
                'text' => '#137333',
                'badge_class' => 'bg-success-subtle text-success border border-success-subtle',
                'size' => 'Cloud Link',
                'is_link' => true,
                'url' => $row->link_drive
            ];
        }

        $info = [
            'type' => strtoupper($ext ?: 'FILE'),
            'ext' => strtoupper($ext ?: 'FILE'),
            'icon' => 'bi-file-earmark-text',
            'color' => '#64748b',
            'bg' => '#f1f5f9',
            'text' => '#475569',
            'badge_class' => 'bg-secondary-subtle text-secondary',
            'size' => 'Unknown',
            'is_link' => false,
            'url' => base_url('assets/downloads/' . ($row->file_path ?? ''))
        ];

        switch ($ext) {
            case 'pdf':
                $info['icon'] = 'bi-filetype-pdf';
                $info['color'] = '#dc2626';
                $info['bg'] = '#fef2f2';
                $info['text'] = '#991b1b';
                $info['badge_class'] = 'bg-danger-subtle text-danger border border-danger-subtle';
                break;
            case 'doc':
            case 'docx':
                $info['icon'] = 'bi-filetype-docx';
                $info['color'] = '#2563eb';
                $info['bg'] = '#eff6ff';
                $info['text'] = '#1e40af';
                $info['badge_class'] = 'bg-primary-subtle text-primary border border-primary-subtle';
                break;
            case 'xls':
            case 'xlsx':
                $info['icon'] = 'bi-filetype-xlsx';
                $info['color'] = '#059669';
                $info['bg'] = '#ecfdf5';
                $info['text'] = '#065f46';
                $info['badge_class'] = 'bg-emerald-subtle text-success border border-success-subtle';
                break;
            case 'ppt':
            case 'pptx':
                $info['icon'] = 'bi-filetype-pptx';
                $info['color'] = '#ea580c';
                $info['bg'] = '#fff7ed';
                $info['text'] = '#9a3412';
                $info['badge_class'] = 'bg-warning-subtle text-warning border border-warning-subtle';
                break;
            case 'zip':
            case 'rar':
                $info['icon'] = 'bi-file-earmark-zip-fill';
                $info['color'] = '#7c3aed';
                $info['bg'] = '#f5f3ff';
                $info['text'] = '#5b21b6';
                $info['badge_class'] = 'bg-purple-subtle text-purple border border-purple-subtle';
                break;
        }

        if(!empty($row->file_path)){
            $filepath = FCPATH . 'assets/downloads/' . $row->file_path;
            if (file_exists($filepath)) {
                $bytes = filesize($filepath);
                if ($bytes >= 1048576) {
                    $info['size'] = number_format($bytes / 1048576, 2) . ' MB';
                } elseif ($bytes >= 1024) {
                    $info['size'] = number_format($bytes / 1024, 1) . ' KB';
                } else {
                    $info['size'] = $bytes . ' B';
                }
            }
        }

        return $info;
    }
}

$area_zi_names = [
    // Pokja Zona Integritas (WBK / WBBM)
    'area1' => 'Pokja I: Manajemen Perubahan',
    'area2' => 'Pokja II: Penataan Tatalaksana',
    'area3' => 'Pokja III: Penataan Manajemen SDM',
    'area4' => 'Pokja IV: Penguatan Akuntabilitas',
    'area5' => 'Pokja V: Penguatan Pengawasan',
    'area6' => 'Pokja VI: Peningkatan Kualitas Pelayanan Publik',

    // Kurikulum & Modul Ajar (Akademik)
    'modul_ajar' => 'Modul Ajar & RPP',
    'silabus' => 'Silabus & ATP',
    'kosp' => 'KOSP & Kurikulum Operasional',
    'jadwal' => 'Jadwal & Kalender Akademik',
    'bank_soal' => 'Bank Soal & Asesmen',
    'prota_promes' => 'Prota & Promes',
    'bahan_ajar' => 'Bahan Ajar & Modul Digital',

    // Kepegawaian & Tata Usaha
    'sk_tugas' => 'SK & Surat Tugas',
    'surat_edaran' => 'Surat Edaran Dinas',
    'sop_tu' => 'SOP Administrasi Madrasah',
    'blanko_pegawai' => 'Blanko Kepegawaian & Cuti',
    'laporan_kinerja' => 'Laporan Kinerja / SKP',
    'notula_rapat' => 'Notula Rapat Dinas',

    // Kesiswaan & Ekstrakurikuler
    'tatib_siswa' => 'Tata Tertib Siswa',
    'osim_mpk' => 'Dokumen OSIM & MPK',
    'ekskul' => 'Ekstrakurikuler',
    'prestasi' => 'Prestasi Siswa',
    'bk_konseling' => 'BP / BK & Konseling',
    'beasiswa_pip' => 'Beasiswa / PIP',

    // Sarpras & Laboratorium
    'inventaris' => 'Inventaris & Aset BMN',
    'sop_lab' => 'SOP Laboratorium',
    'jadwal_lab' => 'Jadwal Penggunaan Lab',
    'berita_acara' => 'Berita Acara Sarpras',
    'pemeliharaan' => 'Pemeliharaan Sarpras',

    // Formulir Publik & Brosur
    'ppdb' => 'PPDB & Brosur Pendaftaran',
    'brosur' => 'Informasi & Pamflet Publik',
    'blanko_surat' => 'Blanko Surat Permohonan',
    'kalender' => 'Kalender Madrasah',
    'majalah_buletin' => 'Buletin Madrasah',
    'lainnya' => 'Lain-lain / Dokumen Pendukung'
];
$sub_kategori_names = $area_zi_names;

$active_filter = isset($active_filter) ? $active_filter : 'all';
$active_view = isset($active_view) ? $active_view : 'grid';
$is_user_logged_in = (bool)$this->session->userdata('logged_in');
$logged_user_name = $this->session->userdata('username') ?? '';
?>

<style>
/* ══════════════════════════════════════════════════════════════════
   MADRASAH CLOUD DRIVE & EVIDEN ZI - MODERN DESIGN SYSTEM
   Google Drive Inspired, Glassmorphism, Micro-Interactions
══════════════════════════════════════════════════════════════════ */
:root {
    --drive-primary: #059669;
    --drive-dark: #064e3b;
    --drive-accent: #10b981;
    --drive-surface: #ffffff;
    --drive-bg: #f8fafc;
    --drive-border: #e2e8f0;
}

.web-drive-hero {
    background: radial-gradient(circle at 85% 15%, rgba(16, 185, 129, 0.25), transparent 45%),
                radial-gradient(circle at 15% 85%, rgba(6, 78, 59, 0.4), transparent 50%),
                linear-gradient(135deg, #064e3b 0%, #059669 65%, #10b981 100%);
    color: #ffffff;
    padding: 60px 0 85px 0;
    position: relative;
    overflow: hidden;
}

.web-drive-hero::after {
    content: '';
    position: absolute;
    bottom: -1px;
    left: 0;
    right: 0;
    height: 35px;
    background: var(--drive-bg);
    border-radius: 35px 35px 0 0;
}

.drive-hero-badge {
    background: rgba(255, 255, 255, 0.16);
    backdrop-filter: blur(8px);
    border: 1px solid rgba(255, 255, 255, 0.3);
    color: #ffffff;
    font-size: 0.78rem;
    font-weight: 700;
    padding: 6px 14px;
    border-radius: 30px;
    display: inline-flex;
    align-items: center;
    gap: 6px;
    margin-bottom: 12px;
}

.drive-main-card {
    background: #ffffff;
    border-radius: 24px;
    border: 1px solid rgba(226, 232, 240, 0.9);
    box-shadow: 0 15px 35px -5px rgba(15, 23, 42, 0.05), 0 5px 15px -3px rgba(15, 23, 42, 0.02);
    margin-top: -55px;
    position: relative;
    z-index: 10;
    padding: 24px 28px;
}

/* Clean Category Pills & Responsive ZI Area Grid (No horizontal overflow) */
.drive-category-pills {
    display: flex;
    flex-wrap: wrap;
    gap: 8px;
    margin-bottom: 14px;
}
.category-pill-btn {
    background: #f1f5f9;
    color: #475569;
    border: 1px solid #e2e8f0;
    border-radius: 30px;
    padding: 7px 15px;
    font-size: 0.82rem;
    font-weight: 700;
    display: inline-flex;
    align-items: center;
    gap: 6px;
    cursor: pointer;
    transition: all 0.15s ease;
    user-select: none;
}
.category-pill-btn:hover {
    background: #e2e8f0;
    color: #0f172a;
}
.category-pill-btn.active {
    background: #059669;
    color: #ffffff;
    border-color: #059669;
    box-shadow: 0 4px 12px rgba(5, 150, 105, 0.25);
}
.category-pill-btn .pill-counter {
    background: rgba(15, 23, 42, 0.08);
    color: inherit;
    font-size: 0.72rem;
    font-weight: 700;
    padding: 2px 7px;
    border-radius: 20px;
}
.category-pill-btn.active .pill-counter {
    background: rgba(255, 255, 255, 0.25);
    color: #ffffff;
}

/* Sub-Area Zona Integritas 6-Column Responsive Grid */
.drive-zi-section {
    background: #f8fafc;
    border: 1px solid #e2e8f0;
    border-radius: 16px;
    padding: 12px 14px;
    margin-bottom: 20px;
}
.drive-zi-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 8px;
}
@media (max-width: 991px) {
    .drive-zi-grid {
        grid-template-columns: repeat(2, 1fr);
    }
}
@media (max-width: 576px) {
    .drive-zi-grid {
        grid-template-columns: 1fr;
    }
}

.zi-compact-card {
    background: #ffffff;
    border: 1.5px solid #e2e8f0;
    border-radius: 12px;
    padding: 8px 12px;
    display: flex;
    align-items: center;
    gap: 10px;
    cursor: pointer;
    transition: all 0.15s ease;
    user-select: none;
}
.zi-compact-card:hover {
    background: #ffffff;
    border-color: #10b981;
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(16, 185, 129, 0.08);
}
.zi-compact-card.active {
    background: #ecfdf5;
    border-color: #059669;
    box-shadow: 0 4px 12px rgba(5, 150, 105, 0.12);
}
.zi-compact-card.active .zi-compact-title {
    color: #065f46;
    font-weight: 800;
}
.zi-compact-icon {
    width: 32px;
    height: 32px;
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 0.95rem;
    flex-shrink: 0;
}
.zi-compact-title {
    font-size: 0.8rem;
    font-weight: 700;
    color: #1e293b;
    line-height: 1.25;
}
.zi-compact-sub {
    font-size: 0.7rem;
    color: #64748b;
    font-weight: 600;
}

/* Toolbar Control */
.drive-toolbar {
    background: #f8fafc;
    border: 1px solid #e2e8f0;
    border-radius: 18px;
    padding: 12px 18px;
    margin-bottom: 24px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    flex-wrap: wrap;
    gap: 12px;
}

.drive-search-input {
    background: #ffffff;
    border: 1px solid #cbd5e1;
    border-radius: 12px;
    padding: 8px 16px 8px 38px;
    font-size: 0.88rem;
    color: #1e293b;
    width: 280px;
    transition: all 0.2s ease;
}
.drive-search-input:focus {
    outline: none;
    border-color: #059669;
    box-shadow: 0 0 0 3px rgba(5, 150, 105, 0.15);
}

.btn-upload-drive {
    background: linear-gradient(135deg, #059669 0%, #10b981 100%);
    color: #ffffff !important;
    border: none;
    border-radius: 12px;
    padding: 9px 18px;
    font-weight: 700;
    font-size: 0.88rem;
    display: inline-flex;
    align-items: center;
    gap: 8px;
    box-shadow: 0 4px 14px rgba(5, 150, 105, 0.28);
    transition: all 0.2s ease;
    text-decoration: none;
    cursor: pointer;
}
.btn-upload-drive:hover {
    transform: translateY(-1px);
    box-shadow: 0 6px 20px rgba(5, 150, 105, 0.38);
}

/* Grid View Items */
.drive-grid-container {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(290px, 1fr));
    gap: 18px;
}

.drive-file-card {
    background: #ffffff;
    border: 1px solid #e2e8f0;
    border-radius: 18px;
    padding: 16px;
    display: flex;
    flex-direction: column;
    justify-content: space-between;
    transition: all 0.2s ease;
    box-shadow: 0 4px 12px rgba(15, 23, 42, 0.02);
    position: relative;
}
.drive-file-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 10px 22px rgba(15, 23, 42, 0.06);
    border-color: #cbd5e1;
}

.file-type-iconbox {
    width: 46px;
    height: 46px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.4rem;
    flex-shrink: 0;
}

.file-badge-pill {
    font-size: 0.7rem;
    font-weight: 700;
    padding: 3px 8px;
    border-radius: 6px;
    display: inline-flex;
    align-items: center;
    gap: 4px;
}

.file-title-link {
    font-weight: 750;
    color: #0f172a;
    font-size: 0.93rem;
    text-decoration: none;
    line-height: 1.35;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
    margin-bottom: 4px;
}
.file-title-link:hover {
    color: #059669;
}

.file-meta-box {
    background: #f8fafc;
    border-radius: 10px;
    padding: 8px 10px;
    font-size: 0.76rem;
    color: #64748b;
    margin: 10px 0 12px 0;
}
.file-meta-row {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 2px;
}
.file-meta-row:last-child {
    margin-bottom: 0;
}

.btn-card-download {
    background: #ecfdf5;
    color: #047857;
    border: 1px solid #a7f3d0;
    border-radius: 10px;
    padding: 6px 12px;
    font-weight: 700;
    font-size: 0.8rem;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    gap: 5px;
    transition: all 0.15s ease;
}
.btn-card-download:hover {
    background: #059669;
    color: #ffffff;
    border-color: #059669;
}

.btn-card-preview {
    background: #f8fafc;
    color: #334155;
    border: 1px solid #cbd5e1;
    border-radius: 10px;
    padding: 6px 10px;
    font-weight: 700;
    font-size: 0.8rem;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    gap: 4px;
    transition: all 0.15s ease;
}
.btn-card-preview:hover {
    background: #f1f5f9;
    color: #0f172a;
}

/* Upload Modal Custom Dropzone */
.modal-drive-content {
    border-radius: 22px;
    border: none;
    overflow: hidden;
    box-shadow: 0 25px 50px -12px rgba(15, 23, 42, 0.25);
}
.modal-drive-header {
    background: linear-gradient(135deg, #064e3b 0%, #059669 100%);
    color: #ffffff;
    padding: 20px 24px;
    border: none;
}
.nav-pills-sumber .nav-link {
    border-radius: 10px;
    font-size: 0.85rem;
    font-weight: 700;
    color: #64748b;
    padding: 8px 16px;
    border: 1px solid #e2e8f0;
}
.nav-pills-sumber .nav-link.active {
    background: #059669;
    color: #ffffff;
    border-color: #059669;
}
</style>

<!-- ═══ 1. HERO BANNER DOKUMEN RESMI MADRASAH ═══ -->
<header class="web-drive-hero">
    <div class="container position-relative" style="z-index: 2;">
        <div class="d-flex align-items-center gap-2 mb-2">
            <a href="<?= base_url() ?>" class="text-white text-decoration-none opacity-75 small">
                <i class="bi bi-house-door"></i> Beranda
            </a>
            <span class="text-white opacity-50 small">/</span>
            <span class="text-white fw-bold small">Pusat Unduhan Berkas</span>
        </div>

        <div class="row align-items-center g-3">
            <div class="col-lg-8">
                <span class="drive-hero-badge">
                    <i class="bi bi-file-earmark-check-fill text-warning"></i> REPOSITORI DOKUMEN RESMI MADRASAH
                </span>
                <h1 class="display-6 fw-bold mb-2 text-white">Pusat Unduhan &amp; Berkas Madrasah</h1>
                <p class="lead text-white-50 mb-3" style="font-size: 1.05rem; max-width: 650px;">
                    Akses dan unduh formulir PPDB, silabus &amp; modul ajar kurikulum, pengumuman resmi, dan dokumen administrasi publik <?= htmlspecialchars($nama_madrasah ?? 'MAN 3 Banjar', ENT_QUOTES, 'UTF-8') ?>.
                </p>
                <div class="d-flex flex-wrap align-items-center gap-2 pt-1">
                    <a href="<?= base_url('website/zona_integritas') ?>" class="btn btn-warning rounded-pill px-4 py-2 fw-bold text-dark d-inline-flex align-items-center gap-2 shadow-sm">
                        <i class="bi bi-shield-lock-fill"></i> Portal Eviden ZI (Terproteksi PIN)
                    </a>
                </div>
            </div>
            <div class="col-lg-4 text-lg-end d-none d-lg-block">
                <div class="p-3 rounded-4" style="background: rgba(255,255,255,0.12); backdrop-filter: blur(8px); border: 1px solid rgba(255,255,255,0.25); display: inline-block; text-align: left;">
                    <div class="small text-white-50 text-uppercase fw-bold mb-1">Status Dokumen Publik</div>
                    <div class="d-flex align-items-center gap-3">
                        <div>
                            <span class="h3 fw-bold text-white mb-0 d-block"><?= $drive_stats['total'] ?? 0 ?></span>
                            <small class="text-white-50">Total Dokumen</small>
                        </div>
                        <div class="vr bg-white opacity-25"></div>
                        <div>
                            <span class="h3 fw-bold text-warning mb-0 d-block"><?= $drive_stats['akademik'] ?? 0 ?></span>
                            <small class="text-white-50">Kurikulum/Modul</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</header>

<!-- ═══ 2. KONTEN UTAMA DRIVE (CARD SECTION) ═══ -->
<section style="background: var(--drive-bg); padding: 0 0 80px 0; min-height: 600px;">
    <div class="container">
        
        <!-- ALERT FLASH NOTIFIKASI -->
        <?php if($this->session->flashdata('success')): ?>
            <div class="alert alert-success alert-dismissible fade show rounded-4 border-0 shadow-sm mb-4 d-flex align-items-center gap-3" role="alert" style="margin-top: -30px; position: relative; z-index: 20;">
                <i class="bi bi-check-circle-fill text-success fs-4"></i>
                <div class="fw-semibold"><?= $this->session->flashdata('success') ?></div>
                <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>
        <?php if($this->session->flashdata('error')): ?>
            <div class="alert alert-danger alert-dismissible fade show rounded-4 border-0 shadow-sm mb-4 d-flex align-items-center gap-3" role="alert" style="margin-top: -30px; position: relative; z-index: 20;">
                <i class="bi bi-exclamation-triangle-fill text-danger fs-4"></i>
                <div class="fw-semibold"><?= $this->session->flashdata('error') ?></div>
                <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>

        <div class="drive-main-card">
            
            <!-- BANNER KHUSUS ZONA INTEGRITAS (TERPISAH & TERPROTEKSI PIN) -->
            <div class="alert alert-success border-0 rounded-4 p-3 d-flex align-items-center justify-content-between flex-wrap gap-3 mb-4 shadow-sm" style="background: linear-gradient(135deg, #ecfdf5 0%, #d1fae5 100%); border: 1px solid #a7f3d0 !important;">
                <div class="d-flex align-items-center gap-3">
                    <div style="width: 44px; height: 44px; border-radius: 12px; background: #059669; color: #fff; display: flex; align-items: center; justify-content: center; font-size: 22px;" class="flex-shrink-0">
                        <i class="bi bi-shield-lock-fill text-warning"></i>
                    </div>
                    <div>
                        <div class="d-flex align-items-center gap-2 mb-1 flex-wrap">
                            <strong class="text-success" style="font-size: 14.5px;">Portal Eviden Zona Integritas (WBK/WBBM)</strong>
                            <span class="badge bg-success-subtle text-success border border-success-subtle rounded-pill font-monospace" style="font-size: 11px;"><i class="bi bi-shield-lock-fill me-1"></i>Akses Terproteksi PIN</span>
                        </div>
                        <span class="small text-muted d-block">Dokumen 6 Pokja Pembangunan ZI dipisahkan ke portal khusus terproteksi PIN bagi Tim Pokja ZI dan Tim Penilai (TPI/TPN).</span>
                    </div>
                </div>
                <div>
                    <a href="<?= base_url('website/zona_integritas?lock=1') ?>" class="btn btn-success rounded-pill px-4 py-2 fw-bold text-nowrap shadow-sm">
                        <i class="bi bi-shield-lock-fill me-1"></i> Buka Portal Eviden ZI (PIN)
                    </a>
                </div>
            </div>

            <!-- A. DIREKTORI KATEGORI BERKAS PUBLIK -->
            <div class="d-flex justify-content-between align-items-center mb-2 flex-wrap gap-2">
                <h6 class="fw-bold text-dark text-uppercase small mb-0">
                    <i class="bi bi-folder2-open text-success me-1"></i> Kategori Dokumen Publik
                </h6>
                <small class="text-muted">Pilih kategori untuk menyaring dokumen</small>
            </div>

            <!-- Category Pills Publik (Wrap secara rapi, tanpa scrollbar panjang) -->
            <div class="drive-category-pills mb-3">
                <button type="button" class="category-pill-btn <?= ($active_filter === 'all') ? 'active' : '' ?>" id="pillCatAll" onclick="selectCategory('all')">
                    <i class="bi bi-grid-fill"></i> Semua Berkas 
                    <span class="pill-counter"><?= $drive_stats['total'] ?? 0 ?></span>
                </button>
                <button type="button" class="category-pill-btn <?= ($active_filter === 'akademik') ? 'active' : '' ?>" id="pillCatAkademik" onclick="selectCategory('akademik')">
                    <i class="bi bi-book-half text-primary"></i> Kurikulum &amp; Modul 
                    <span class="pill-counter"><?= $drive_stats['akademik'] ?? 0 ?></span>
                </button>
                <button type="button" class="category-pill-btn <?= ($active_filter === 'kepegawaian') ? 'active' : '' ?>" id="pillCatKepegawaian" onclick="selectCategory('kepegawaian')">
                    <i class="bi bi-file-earmark-person-fill text-secondary"></i> Kepegawaian &amp; TU 
                    <span class="pill-counter"><?= $drive_stats['kepegawaian'] ?? 0 ?></span>
                </button>
                <button type="button" class="category-pill-btn <?= ($active_filter === 'kesiswaan') ? 'active' : '' ?>" id="pillCatKesiswaan" onclick="selectCategory('kesiswaan')">
                    <i class="bi bi-trophy-fill text-warning"></i> Kesiswaan 
                    <span class="pill-counter"><?= $drive_stats['kesiswaan'] ?? 0 ?></span>
                </button>
                <button type="button" class="category-pill-btn <?= ($active_filter === 'sarpras') ? 'active' : '' ?>" id="pillCatSarpras" onclick="selectCategory('sarpras')">
                    <i class="bi bi-building text-info"></i> Sarpras &amp; Lab 
                    <span class="pill-counter"><?= $drive_stats['sarpras'] ?? 0 ?></span>
                </button>
                <button type="button" class="category-pill-btn <?= ($active_filter === 'umum') ? 'active' : '' ?>" id="pillCatUmum" onclick="selectCategory('umum')">
                    <i class="bi bi-file-earmark-text-fill text-dark"></i> Formulir Publik 
                    <span class="pill-counter"><?= $drive_stats['umum'] ?? 0 ?></span>
                </button>
            </div>

            <!-- B. DRIVE TOOLBAR (SEARCH, TIPE FILTER, VIEW TOGGLE, UPLOAD BTN) -->
            <div class="drive-toolbar">
                <div class="d-flex align-items-center gap-2 flex-wrap">
                    <!-- Live Search -->
                    <div class="position-relative">
                        <i class="bi bi-search position-absolute top-50 translate-middle-y text-muted" style="left: 14px;"></i>
                        <input type="text" id="driveSearchInput" class="drive-search-input" placeholder="Cari dokumen, pengunggah, kata kunci...">
                    </div>

                    <!-- Tipe Berkas Filter -->
                    <select id="driveTypeFilter" class="form-select rounded-3 py-2 text-secondary fw-semibold" style="width: auto; font-size: 0.86rem; border-color: #cbd5e1;">
                        <option value="all">Semua Tipe Berkas</option>
                        <option value="pdf">PDF Dokumen</option>
                        <option value="docx">Word (.docx / .doc)</option>
                        <option value="xlsx">Excel (.xlsx / .xls)</option>
                        <option value="pptx">PowerPoint (.pptx)</option>
                        <option value="zip">Arsip (.zip / .rar)</option>
                        <option value="drive">Tautan Google Drive</option>
                    </select>

                    <button type="button" class="btn btn-outline-secondary rounded-3 py-2 px-3 fw-semibold small" onclick="resetFilters()">
                        <i class="bi bi-arrow-clockwise"></i> Reset
                    </button>
                </div>

                <div class="d-flex align-items-center gap-2">
                    <!-- Switch Grid / List -->
                    <div class="btn-group rounded-3 shadow-sm p-1" style="background:#f1f5f9;" role="group">
                        <button type="button" id="btnViewGrid" class="btn btn-sm <?= ($active_view === 'grid') ? 'btn-white bg-white text-dark shadow-xs' : 'text-muted' ?> fw-bold rounded-2 px-3" onclick="switchView('grid')">
                            <i class="bi bi-grid-fill me-1"></i> Grid
                        </button>
                        <button type="button" id="btnViewList" class="btn btn-sm <?= ($active_view === 'list') ? 'btn-white bg-white text-dark shadow-xs' : 'text-muted' ?> fw-bold rounded-2 px-3" onclick="switchView('list')">
                            <i class="bi bi-list-ul me-1"></i> Tabel
                        </button>
                    </div>
                </div>
            </div>

            <!-- C. ACTIVE BREADCRUMB / STATUS INDIKATOR -->
            <div class="d-flex align-items-center justify-content-between mb-3 px-1">
                <div class="small fw-semibold text-muted d-flex align-items-center gap-1">
                    <span>Folder:</span>
                    <strong id="activeFolderLabel" class="text-dark">
                        <?php 
                        if($active_filter === 'all') echo 'Semua Berkas';
                        elseif($active_filter === 'zi') echo 'Zona Integritas (Semua Pokja)';
                        elseif(isset($area_zi_names[$active_filter])) echo $area_zi_names[$active_filter];
                        else echo ucfirst($active_filter);
                        ?>
                    </strong>
                    <span id="filteredCountBadge" class="badge bg-light text-secondary rounded-pill ms-2 border">
                        <?= count($downloads ?? []) ?> Berkas
                    </span>
                </div>
            </div>

            <!-- D. TAMPILAN BERKAS (GRID VIEW) -->
            <div id="driveGridView" class="drive-grid-container" style="<?= ($active_view === 'grid') ? '' : 'display:none;' ?>">
                <?php if(empty($downloads)): ?>
                    <div class="w-100 text-center py-5 text-muted" style="grid-column: 1 / -1;">
                        <div class="d-inline-flex align-items-center justify-content-center bg-light rounded-circle mb-3 shadow-xs" style="width: 76px; height: 76px;">
                            <i class="bi bi-folder-x fs-1 text-secondary opacity-75"></i>
                        </div>
                        <h6 class="fw-bold text-dark fs-5 mb-2">Belum Ada Dokumen di Folder Ini</h6>
                        <p class="small text-muted mb-3 mx-auto" style="max-width: 480px;">Silakan pilih kategori berkas di atas atau gunakan kotak pencarian untuk melihat dokumen lainnya.</p>
                    </div>
                <?php else: ?>
                    <?php foreach($downloads as $d): ?>
                        <?php 
                        $file_meta = get_drive_file_meta($d);
                        $kategori = strtolower($d->kategori_pilar ?? 'umum');
                        $area = strtolower($d->area_zi ?? '');
                        ?>
                        <div class="drive-file-card drive-item-row" 
                             data-kategori="<?= $kategori ?>" 
                             data-area="<?= $area ?>" 
                             data-ext="<?= strtolower($file_meta['ext']) ?>" 
                             data-title="<?= htmlspecialchars(strtolower($d->judul ?? ''), ENT_QUOTES, 'UTF-8') ?>"
                             data-pengunggah="<?= htmlspecialchars(strtolower($d->pengunggah ?? ''), ENT_QUOTES, 'UTF-8') ?>">
                            
                            <div>
                                <div class="d-flex align-items-start justify-content-between gap-2 mb-2">
                                    <div class="file-type-iconbox" style="background: <?= $file_meta['bg'] ?>; color: <?= $file_meta['color'] ?>;">
                                        <i class="bi <?= $file_meta['icon'] ?>"></i>
                                    </div>
                                    <div class="text-end">
                                        <?php if($kategori === 'zi' && !empty($area)): ?>
                                            <span class="file-badge-pill bg-success-subtle text-success border border-success-subtle">
                                                <i class="bi bi-shield-check"></i> <?= str_replace('AREA', 'POKJA ', strtoupper($area)) ?>
                                            </span>
                                        <?php elseif(!empty($area) && isset($sub_kategori_names[$area])): ?>
                                            <span class="file-badge-pill <?= $file_meta['badge_class'] ?>">
                                                <?= htmlspecialchars($sub_kategori_names[$area], ENT_QUOTES, 'UTF-8') ?>
                                            </span>
                                        <?php else: ?>
                                            <span class="file-badge-pill <?= $file_meta['badge_class'] ?>">
                                                <?= strtoupper($kategori) ?>
                                            </span>
                                        <?php endif; ?>
                                    </div>
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
                                        <strong class="text-dark text-truncate" style="max-width: 140px;"><?= htmlspecialchars($d->pengunggah ?: 'PTK Madrasah', ENT_QUOTES, 'UTF-8') ?></strong>
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

                            <div class="d-flex align-items-center justify-content-between pt-2 border-top gap-1">
                                <?php if($file_meta['is_link']): ?>
                                    <a href="<?= $file_meta['url'] ?>" target="_blank" class="btn btn-sm btn-outline-success rounded-pill w-100 fw-bold d-inline-flex align-items-center justify-content-center gap-1">
                                        <i class="bi bi-box-arrow-up-right"></i> Buka Google Drive
                                    </a>
                                <?php else: ?>
                                    <?php if(strtolower($file_meta['ext']) === 'pdf'): ?>
                                        <button type="button" class="btn-card-preview" onclick="previewPdf('<?= $file_meta['url'] ?>', '<?= htmlspecialchars(addslashes($d->judul ?? ''), ENT_QUOTES, 'UTF-8') ?>')">
                                            <i class="bi bi-eye"></i> Pratinjau
                                        </button>
                                    <?php endif; ?>
                                    <a href="<?= $file_meta['url'] ?>" target="_blank" download class="btn-card-download <?= (strtolower($file_meta['ext']) !== 'pdf') ? 'w-100 justify-content-center' : '' ?>">
                                        <i class="bi bi-download"></i> Unduh
                                    </a>
                                <?php endif; ?>
                            </div>

                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>

            <!-- E. TAMPILAN BERKAS (TABLE LIST VIEW) -->
            <div id="driveListView" class="table-responsive" style="<?= ($active_view === 'list') ? '' : 'display:none;' ?>">
                <table class="table table-hover align-middle mb-0" id="driveTable" style="width: 100%;">
                    <thead style="background: #f8fafc; border-bottom: 2px solid #e2e8f0;">
                        <tr>
                            <th style="width: 45px;" class="ps-3">Tipe</th>
                            <th>Nama Dokumen &amp; Keterangan</th>
                            <th style="width: 170px;">Kategori / Pokja</th>
                            <th style="width: 170px;">Pengunggah &amp; Lini</th>
                            <th style="width: 130px;">Tanggal &amp; Size</th>
                            <th style="width: 120px;" class="text-end pe-3">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if(empty($downloads)): ?>
                            <tr>
                                <td colspan="6" class="text-center py-5 text-muted">
                                    Belum ada berkas pada folder ini.
                                </td>
                            </tr>
                        <?php else: ?>
                            <?php foreach($downloads as $d): ?>
                                <?php 
                                $file_meta = get_drive_file_meta($d);
                                $kategori = strtolower($d->kategori_pilar ?? 'umum');
                                $area = strtolower($d->area_zi ?? '');
                                ?>
                                <tr class="drive-item-row"
                                    data-kategori="<?= $kategori ?>" 
                                    data-area="<?= $area ?>" 
                                    data-ext="<?= strtolower($file_meta['ext']) ?>" 
                                    data-title="<?= htmlspecialchars(strtolower($d->judul ?? ''), ENT_QUOTES, 'UTF-8') ?>"
                                    data-pengunggah="<?= htmlspecialchars(strtolower($d->pengunggah ?? ''), ENT_QUOTES, 'UTF-8') ?>">
                                    
                                    <td class="ps-3">
                                        <div class="file-type-iconbox" style="width: 38px; height: 38px; font-size: 1.15rem; background: <?= $file_meta['bg'] ?>; color: <?= $file_meta['color'] ?>;">
                                            <i class="bi <?= $file_meta['icon'] ?>"></i>
                                        </div>
                                    </td>
                                    <td>
                                        <a href="<?= $file_meta['url'] ?>" target="_blank" class="fw-bold text-dark text-decoration-none d-block mb-1" style="font-size: 0.92rem;">
                                            <?= htmlspecialchars($d->judul ?? '-', ENT_QUOTES, 'UTF-8') ?>
                                        </a>
                                        <?php if(!empty($d->keterangan)): ?>
                                            <div class="small text-muted text-truncate" style="max-width: 350px;">
                                                <?= htmlspecialchars($d->keterangan, ENT_QUOTES, 'UTF-8') ?>
                                            </div>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <?php if($kategori === 'zi' && !empty($area)): ?>
                                            <span class="file-badge-pill bg-success-subtle text-success border border-success-subtle">
                                                <i class="bi bi-shield-check"></i> <?= str_replace('AREA', 'POKJA ', strtoupper($area)) ?>
                                            </span>
                                            <div class="small text-muted mt-1" style="font-size: 11px;">
                                                <?= $sub_kategori_names[$area] ?? 'Zona Integritas' ?>
                                            </div>
                                        <?php elseif(!empty($area) && isset($sub_kategori_names[$area])): ?>
                                            <span class="file-badge-pill <?= $file_meta['badge_class'] ?>">
                                                <?= strtoupper($kategori) ?>
                                            </span>
                                            <div class="small text-dark fw-semibold mt-1" style="font-size: 11.5px;">
                                                <?= htmlspecialchars($sub_kategori_names[$area], ENT_QUOTES, 'UTF-8') ?>
                                            </div>
                                        <?php else: ?>
                                            <span class="file-badge-pill <?= $file_meta['badge_class'] ?>">
                                                <?= strtoupper($kategori) ?>
                                            </span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <div class="fw-semibold text-dark small">
                                            <i class="bi bi-person-fill text-muted me-1"></i><?= htmlspecialchars($d->pengunggah ?: 'PTK Madrasah', ENT_QUOTES, 'UTF-8') ?>
                                        </div>
                                        <?php if(!empty($d->lini_unit)): ?>
                                            <div class="small text-muted" style="font-size: 11px;"><?= htmlspecialchars($d->lini_unit, ENT_QUOTES, 'UTF-8') ?></div>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <div class="small text-dark fw-medium">
                                            <i class="bi bi-calendar3 text-muted me-1"></i><?= !empty($d->tanggal) ? date('d M Y', strtotime($d->tanggal)) : '-' ?>
                                        </div>
                                        <div class="small text-secondary" style="font-size: 11px;">
                                            <?= $file_meta['size'] ?>
                                        </div>
                                    </td>
                                    <td class="text-end pe-3">
                                        <div class="d-inline-flex gap-1">
                                            <?php if($file_meta['is_link']): ?>
                                                <a href="<?= $file_meta['url'] ?>" target="_blank" class="btn btn-sm btn-outline-success rounded-pill px-3 py-1 fw-bold">
                                                    <i class="bi bi-box-arrow-up-right me-1"></i> Drive
                                                </a>
                                            <?php else: ?>
                                                <?php if(strtolower($file_meta['ext']) === 'pdf'): ?>
                                                    <button type="button" class="btn btn-sm btn-light rounded-pill px-2 py-1 text-secondary" onclick="previewPdf('<?= $file_meta['url'] ?>', '<?= htmlspecialchars(addslashes($d->judul ?? ''), ENT_QUOTES, 'UTF-8') ?>')" title="Pratinjau PDF">
                                                        <i class="bi bi-eye"></i>
                                                    </button>
                                                <?php endif; ?>
                                                <a href="<?= $file_meta['url'] ?>" target="_blank" download class="btn btn-sm btn-success rounded-pill px-3 py-1 fw-bold">
                                                    <i class="bi bi-download me-1"></i> Unduh
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

            <!-- F. NO SEARCH RESULTS BANNER -->
            <div id="noResultsNotice" class="text-center py-5 text-muted" style="display: none;">
                <i class="bi bi-search fs-1 text-secondary opacity-50 mb-2 d-block"></i>
                <h6 class="fw-bold text-dark">Tidak Ada Dokumen yang Cocok</h6>
                <p class="small text-muted mb-2">Coba ubah kata kunci pencarian atau ganti filter folder di atas.</p>
                <button type="button" class="btn btn-sm btn-outline-secondary rounded-pill px-3" onclick="resetFilters()">
                    Kembali ke Semua Berkas
                </button>
            </div>

        </div>
    </div>
</section>

<!-- ═══ 3. MODAL PRATINJAU PDF ═══ -->
<div class="modal fade" id="modalPreviewPdf" tabindex="-1" aria-labelledby="modalPreviewPdfLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-xl modal-dialog-scrollable">
        <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden" style="height: 88vh;">
            <div class="modal-header bg-dark text-white py-2 px-3">
                <div class="d-flex align-items-center gap-2">
                    <i class="bi bi-file-earmark-pdf-fill text-danger fs-5"></i>
                    <h6 class="modal-title fw-bold text-white mb-0" id="modalPreviewPdfLabel">Pratinjau Dokumen</h6>
                </div>
                <div class="d-flex align-items-center gap-2">
                    <a id="btnPdfDownloadDirect" href="#" target="_blank" download class="btn btn-sm btn-outline-light rounded-pill px-3">
                        <i class="bi bi-download me-1"></i> Unduh Asli
                    </a>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
            </div>
            <div class="modal-body p-0 bg-secondary" style="height: 100%;">
                <iframe id="iframePdfViewer" src="" style="width: 100%; height: 100%; border: none;"></iframe>
            </div>
        </div>
    </div>
</div>

<script>
// ══════════════════════════════════════════════════════════════════
// JAVASCRIPT GOOGLE DRIVE FILTERING, SWITCH VIEW & PREVIEW
// ══════════════════════════════════════════════════════════════════
let currentFolder = '<?= $active_filter ?>';
let currentSearch = '';
let currentType = 'all';

function switchView(viewMode) {
    const gridView = document.getElementById('driveGridView');
    const listView = document.getElementById('driveListView');
    const btnGrid = document.getElementById('btnViewGrid');
    const btnList = document.getElementById('btnViewList');

    if (viewMode === 'grid') {
        gridView.style.display = 'grid';
        listView.style.display = 'none';
        btnGrid.classList.add('bg-white', 'text-dark', 'shadow-xs');
        btnGrid.classList.remove('text-muted');
        btnList.classList.remove('bg-white', 'text-dark', 'shadow-xs');
        btnList.classList.add('text-muted');
    } else {
        gridView.style.display = 'none';
        listView.style.display = 'block';
        btnList.classList.add('bg-white', 'text-dark', 'shadow-xs');
        btnList.classList.remove('text-muted');
        btnGrid.classList.remove('bg-white', 'text-dark', 'shadow-xs');
        btnGrid.classList.add('text-muted');
    }
}

function selectCategory(catKey) {
    currentFolder = catKey;

    // Update Category Pills active state
    document.querySelectorAll('.category-pill-btn').forEach(btn => btn.classList.remove('active'));
    
    let activeId = 'pillCatAll';
    if(catKey === 'akademik') activeId = 'pillCatAkademik';
    else if(catKey === 'kepegawaian') activeId = 'pillCatKepegawaian';
    else if(catKey === 'kesiswaan') activeId = 'pillCatKesiswaan';
    else if(catKey === 'sarpras') activeId = 'pillCatSarpras';
    else if(catKey === 'umum') activeId = 'pillCatUmum';

    const pillEl = document.getElementById(activeId);
    if(pillEl) pillEl.classList.add('active');

    // Deselect all area cards
    document.querySelectorAll('.zi-area-card').forEach(c => c.classList.remove('active'));

    // Toggle ZI Sub-area section visibility
    const subAreaWrap = document.getElementById('wrapperZiSubArea');
    if (subAreaWrap) {
        if (catKey === 'all' || catKey === 'zi' || catKey.startsWith('area')) {
            subAreaWrap.style.display = 'block';
        } else {
            subAreaWrap.style.display = 'none';
        }
    }

    updateFolderLabel(catKey);
    applyAllFilters();
}

function selectArea(areaKey) {
    currentFolder = areaKey;

    // Set ZI Pill active
    document.querySelectorAll('.category-pill-btn').forEach(btn => btn.classList.remove('active'));
    const pillZi = document.getElementById('pillCatZi');
    if(pillZi) pillZi.classList.add('active');

    // Ensure subAreaWrap is visible
    const subAreaWrap = document.getElementById('wrapperZiSubArea');
    if (subAreaWrap) subAreaWrap.style.display = 'block';

    // Highlight clicked area card
    document.querySelectorAll('.zi-area-card').forEach(c => {
        if (c.getAttribute('data-area') === areaKey) {
            c.classList.add('active');
        } else {
            c.classList.remove('active');
        }
    });

    updateFolderLabel(areaKey);
    applyAllFilters();
}

function updateFolderLabel(folderKey) {
    const labelEl = document.getElementById('activeFolderLabel');
    const folderLabels = {
        'all': 'Semua Berkas',
        'zi': 'Zona Integritas (Semua 6 Pokja)',
        'area1': 'Pokja I: Manajemen Perubahan',
        'area2': 'Pokja II: Penataan Tatalaksana',
        'area3': 'Pokja III: Penataan Manajemen SDM',
        'area4': 'Pokja IV: Penguatan Akuntabilitas',
        'area5': 'Pokja V: Penguatan Pengawasan',
        'area6': 'Pokja VI: Peningkatan Kualitas Pelayanan Publik',
        'akademik': 'Kurikulum & Modul Ajar',
        'kepegawaian': 'Kepegawaian & Tata Usaha',
        'kesiswaan': 'Kesiswaan & Ekstrakurikuler',
        'sarpras': 'Sarana Prasarana & Laboratorium',
        'umum': 'Formulir Publik & Brosur'
    };
    if (labelEl) {
        labelEl.textContent = folderLabels[folderKey] || folderKey.toUpperCase();
    }
}

function applyAllFilters() {
    const gridRows = document.querySelectorAll('#driveGridView .drive-item-row');
    const listRows = document.querySelectorAll('#driveListView tbody .drive-item-row');
    let visibleCount = 0;

    const filterItem = (row, isCounted) => {
        const kategori = (row.getAttribute('data-kategori') || '').toLowerCase().trim();
        const area = (row.getAttribute('data-area') || '').toLowerCase().trim();
        const ext = (row.getAttribute('data-ext') || '').toLowerCase().trim();
        const title = (row.getAttribute('data-title') || '').toLowerCase().trim();
        const pengunggah = (row.getAttribute('data-pengunggah') || '').toLowerCase().trim();

        // 1. Folder / Kategori match
        let matchFolder = false;
        if (!currentFolder || currentFolder === 'all') {
            matchFolder = true;
        } else if (currentFolder === 'zi') {
            matchFolder = (kategori === 'zi' || kategori.startsWith('area') || area.startsWith('area'));
        } else if (currentFolder.startsWith('area')) {
            matchFolder = (area === currentFolder || kategori === currentFolder);
        } else {
            matchFolder = (kategori === currentFolder);
        }

        // 2. Search query match
        let matchSearch = true;
        if (currentSearch && currentSearch.trim() !== '') {
            const q = currentSearch.toLowerCase().trim();
            matchSearch = (title.includes(q) || pengunggah.includes(q) || kategori.includes(q) || area.includes(q));
        }

        // 3. File type match
        let matchType = true;
        if (currentType && currentType !== 'all') {
            if (currentType === 'pdf') {
                matchType = (ext === 'pdf');
            } else if (currentType === 'docx' || currentType === 'doc') {
                matchType = (ext === 'doc' || ext === 'docx');
            } else if (currentType === 'xlsx' || currentType === 'xls') {
                matchType = (ext === 'xls' || ext === 'xlsx');
            } else if (currentType === 'pptx' || currentType === 'ppt') {
                matchType = (ext === 'ppt' || ext === 'pptx');
            } else if (currentType === 'zip') {
                matchType = (ext === 'zip' || ext === 'rar');
            } else if (currentType === 'drive') {
                matchType = (ext === 'drive');
            } else {
                matchType = (ext === currentType);
            }
        }

        const isMatch = matchFolder && matchSearch && matchType;
        if (isMatch) {
            row.style.display = '';
            if (isCounted) visibleCount++;
        } else {
            row.style.display = 'none';
        }
        return isMatch;
    };

    gridRows.forEach(row => filterItem(row, true));
    listRows.forEach(row => filterItem(row, false));

    // Update count badge
    const countBadge = document.getElementById('filteredCountBadge');
    if (countBadge) {
        countBadge.textContent = visibleCount + ' Berkas';
    }

    // Toggle Empty state jika tidak ada yang cocok
    let emptyGridMsg = document.getElementById('emptyFilterMsgGrid');
    const gridView = document.getElementById('driveGridView');
    if (gridView) {
        if (visibleCount === 0 && (gridRows.length > 0)) {
            if (!emptyGridMsg) {
                emptyGridMsg = document.createElement('div');
                emptyGridMsg.id = 'emptyFilterMsgGrid';
                emptyGridMsg.className = 'col-12 text-center py-5 text-muted';
                emptyGridMsg.innerHTML = '<i class="bi bi-search fs-1 text-secondary opacity-50 mb-2 d-block"></i><h6 class="fw-bold text-dark">Tidak Ada Berkas yang Cocok</h6><p class="small text-muted mb-0">Coba ubah kata kunci pencarian atau pilih kategori lain.</p>';
                gridView.appendChild(emptyGridMsg);
            }
            emptyGridMsg.style.display = 'block';
        } else if (emptyGridMsg) {
            emptyGridMsg.style.display = 'none';
        }
    }
}

function filterByFolder(folderKey) {
    if (folderKey.startsWith('area')) {
        selectArea(folderKey);
    } else {
        selectCategory(folderKey);
    }
}

function resetFilters() {
    currentSearch = '';
    currentType = 'all';
    currentFolder = 'all';

    document.getElementById('driveSearchInput').value = '';
    document.getElementById('driveTypeFilter').value = 'all';

    selectCategory('all');
}

// Event Listeners for Search & Type Filter & Initial Filter
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('driveSearchInput');
    if (searchInput) {
        searchInput.addEventListener('input', function(e) {
            currentSearch = e.target.value;
            applyAllFilters();
        });
    }

    const typeFilter = document.getElementById('driveTypeFilter');
    if (typeFilter) {
        typeFilter.addEventListener('change', function(e) {
            currentType = e.target.value;
            applyAllFilters();
        });
    }

    // Jalankan filter awal sesuai filter aktif
    applyAllFilters();
});

// PDF Preview Function

// PDF Preview Function
function previewPdf(pdfUrl, title) {
    const modalEl = document.getElementById('modalPreviewPdf');
    const labelEl = document.getElementById('modalPreviewPdfLabel');
    const iframeEl = document.getElementById('iframePdfViewer');
    const btnDownload = document.getElementById('btnPdfDownloadDirect');

    labelEl.textContent = title;
    iframeEl.src = pdfUrl;
    btnDownload.href = pdfUrl;

    const bsModal = new bootstrap.Modal(modalEl);
    bsModal.show();
}
</script>

<?php $this->load->view('public/partials/archive_footer'); ?>
