<?php
$nama_lengkap    = $siswa->nama_lengkap ?? 'Peserta PMB';
$no_pendaftaran  = $siswa->no_pendaftaran ?? '-';
$nisn            = $siswa->nisn ?? '-';
$status_db       = $siswa->status ?? 'Lengkapi Biodata';
$jalur           = $siswa->jalur_pendaftaran ?? 'Reguler';
$asal_sekolah    = $siswa->asal_sekolah ?? '-';
$no_hp           = $siswa->no_hp ?? '-';
$email           = $siswa->email ?? '';

$foto_path = !empty($siswa->foto) ? FCPATH.'uploads/temp/ppdb/'.$siswa->foto : '';
$foto_url  = !empty($siswa->foto) ? base_url('uploads/temp/ppdb/'.$siswa->foto) : '';

// Tentukan step aktif (1 s/d 5)
$step_current = 1;
if($status_db == 'Lengkapi Biodata'){
    $step_current = 2;
} elseif($status_db == 'Upload Berkas'){
    $step_current = 3;
} elseif(in_array($status_db, ['Menunggu Verifikasi Berkas', 'Perlu Perbaikan'])){
    $step_current = 3;
} elseif($status_db == 'Lulus Verifikasi' || !empty($siswa->no_peserta_tes)){
    $step_current = 4;
} elseif(in_array($status_db, ['Diterima', 'Ditolak'])){
    $step_current = 5;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <title>Dashboard Calon Siswa | PMB MAN 3 Banjar</title>

    <link rel="icon" type="image/png" href="<?= base_url('assets/img/favicon.png') ?>">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link rel="stylesheet" href="<?= base_url('assets/css/ppdb-peserta.css?v=4') ?>">
</head>
<body>

<!-- ═══ MOBILE TOP HEADER ═══ -->
<div class="peserta-mobile-head">
    <div class="brand">
        <?php if(file_exists(FCPATH.'assets/img/logo-madrasah.png')): ?>
            <img src="<?= base_url('assets/img/logo-madrasah.png') ?>" alt="Logo">
        <?php else: ?>
            <span class="badge bg-success">M3</span>
        <?php endif; ?>
        <div>
            <strong>PMB MAN 3 Banjar</strong>
            <small><?= htmlspecialchars($nama_lengkap) ?></small>
        </div>
    </div>
    <a href="<?= base_url('ppdb/logout') ?>" class="btn btn-sm btn-outline-danger px-3 py-1 rounded-pill" style="font-size: 11px; font-weight: 700;">
        <i class="bi bi-box-arrow-right"></i> Keluar
    </a>
</div>

<div class="peserta-shell">

    <!-- ═══ DESKTOP SIDEBAR ═══ -->
    <aside class="peserta-sidebar">
        <div>
            <div class="peserta-brand">
                <div class="peserta-brand-logo">
                    <?php if(file_exists(FCPATH.'assets/img/logo-madrasah.png')): ?>
                        <img src="<?= base_url('assets/img/logo-madrasah.png') ?>" alt="Logo">
                    <?php else: ?>
                        M3
                    <?php endif; ?>
                </div>
                <div>
                    <strong>PMB MAN 3 BANJAR</strong>
                    <small>Portal Calon Siswa</small>
                </div>
            </div>

            <div class="peserta-profile-mini">
                <?php if(!empty($siswa->foto) && file_exists($foto_path)): ?>
                    <img src="<?= $foto_url ?>" alt="Foto">
                <?php else: ?>
                    <div class="peserta-avatar"><?= strtoupper(substr($nama_lengkap, 0, 1)) ?></div>
                <?php endif; ?>
                <div class="info-wrap">
                    <strong><?= htmlspecialchars($nama_lengkap) ?></strong>
                    <small><?= htmlspecialchars($no_pendaftaran) ?></small>
                </div>
            </div>

            <nav class="peserta-menu">
                <a href="<?= base_url('ppdb/dashboard') ?>" class="active">
                    <i class="bi bi-grid-fill"></i> Dashboard
                </a>
                <a href="<?= base_url('ppdb/biodata') ?>">
                    <i class="bi bi-person-lines-fill"></i> Lengkapi Biodata
                </a>
                <a href="<?= base_url('ppdb/upload') ?>">
                    <i class="bi bi-cloud-arrow-up-fill"></i> Upload Berkas
                </a>
                <a href="<?= base_url('ppdb/detail') ?>">
                    <i class="bi bi-file-earmark-person-fill"></i> Detail Pendaftaran
                </a>
                <a href="<?= base_url('ppdb/cetak_kartu') ?>" target="_blank">
                    <i class="bi bi-printer-fill"></i> Cetak Kartu Ujian
                </a>
            </nav>
        </div>

        <div>
            <a href="<?= base_url('ppdb/logout') ?>" class="peserta-menu a logout d-flex align-items-center gap-2 p-2 rounded-3 text-decoration-none">
                <i class="bi bi-box-arrow-left"></i> Logout / Keluar
            </a>
        </div>
    </aside>

    <!-- ═══ MAIN CONTENT ═══ -->
    <main class="peserta-main">

        <!-- Flash Alert -->
        <?php if($this->session->flashdata('success')): ?>
            <div class="alert alert-success alert-dismissible fade show rounded-4 mb-3 border-0 shadow-sm" role="alert">
                <i class="bi bi-check-circle-fill me-2"></i> <?= $this->session->flashdata('success') ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>

        <?php if($this->session->flashdata('error')): ?>
            <div class="alert alert-danger alert-dismissible fade show rounded-4 mb-3 border-0 shadow-sm" role="alert">
                <i class="bi bi-exclamation-triangle-fill me-2"></i> <?= $this->session->flashdata('error') ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>

        <!-- ═══ 1. HERO PROFILE CARD ═══ -->
        <div class="peserta-hero-card">
            <div class="peserta-hero-top">
                <div class="peserta-hero-user">
                    <div class="peserta-hero-avatar">
                        <?php if(!empty($siswa->foto) && file_exists($foto_path)): ?>
                            <img src="<?= $foto_url ?>" alt="Foto">
                        <?php else: ?>
                            <?= strtoupper(substr($nama_lengkap, 0, 1)) ?>
                        <?php endif; ?>
                    </div>
                    <div class="peserta-hero-meta">
                        <h1><?= htmlspecialchars($nama_lengkap) ?></h1>
                        <p>No. Pendaftaran: <strong><?= htmlspecialchars($no_pendaftaran) ?></strong> &bull; NISN: <strong><?= htmlspecialchars($nisn) ?></strong></p>
                    </div>
                </div>
                <div class="peserta-hero-badge">
                    <i class="bi bi-patch-check-fill"></i>
                    <span><?= htmlspecialchars($status_db) ?></span>
                </div>
            </div>

            <div class="peserta-hero-tags">
                <div class="peserta-tag-pill"><i class="bi bi-diagram-3 me-1"></i> Jalur: <strong>&nbsp;<?= htmlspecialchars($jalur) ?></strong></div>
                <div class="peserta-tag-pill"><i class="bi bi-building me-1"></i> Asal: <strong>&nbsp;<?= htmlspecialchars($asal_sekolah) ?></strong></div>
                <div class="peserta-tag-pill"><i class="bi bi-whatsapp me-1"></i> WA: <strong>&nbsp;<?= htmlspecialchars($no_hp) ?></strong></div>
                <?php if(!empty($email)): ?>
                    <div class="peserta-tag-pill"><i class="bi bi-envelope me-1"></i> <?= htmlspecialchars($email) ?></div>
                <?php endif; ?>
            </div>
        </div>

        <!-- ═══ 2. JADWAL SELEKSI / STATUS KARTU (JIKA SUDAH JADWAL) ═══ -->
        <?php if(!empty($siswa->no_peserta_tes) || in_array($status_db, ['Lulus Verifikasi', 'Diterima'])): ?>
            <div class="card border-0 shadow-sm rounded-4 p-3 p-md-4 mb-4" style="background: linear-gradient(135deg, #065f46 0%, #047857 100%); color:#ffffff;">
                <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-3">
                    <div>
                        <div class="badge bg-white text-success fw-bold mb-2 px-3 py-1" style="font-size: 11.5px;">
                            <i class="bi bi-check2-all me-1"></i> SIAP UJIAN SELEKSI
                        </div>
                        <h4 class="fw-bold mb-1 text-white">Jadwal Ujian Masuk &amp; Tes Seleksi</h4>
                        <div class="small opacity-90 mt-1 d-flex flex-wrap gap-3">
                            <span><i class="bi bi-card-text me-1"></i> No. Peserta: <strong><?= htmlspecialchars($siswa->no_peserta_tes ?? $siswa->no_pendaftaran) ?></strong></span>
                            <span><i class="bi bi-calendar-event me-1"></i> Tanggal: <strong><?= !empty($siswa->tanggal_tes) ? date('d F Y', strtotime($siswa->tanggal_tes)) : 'Menunggu Info Panitia' ?></strong></span>
                            <span><i class="bi bi-geo-alt me-1"></i> Ruang: <strong><?= htmlspecialchars($siswa->ruang_tes ?? 'Kampus MAN 3 Banjar') ?></strong></span>
                        </div>
                    </div>
                    <a href="<?= base_url('ppdb/cetak_kartu') ?>" target="_blank" class="btn btn-light px-4 py-2 rounded-pill fw-bold shadow-sm text-success" style="font-size: 14px; white-space:nowrap;">
                        <i class="bi bi-printer-fill me-1"></i> Cetak Kartu Ujian (PDF)
                    </a>
                </div>
            </div>
        <?php endif; ?>

        <!-- ═══ 3. VISUAL STEPPER TIMELINE ═══ -->
        <div class="peserta-stepper-card">
            <div class="stepper-header">
                <h5><i class="bi bi-signpost-split text-success me-2"></i> Tahapan Pendaftaran Anda</h5>
                <span class="badge bg-success px-3 py-1 rounded-pill" style="font-size: 11.5px;">Progress <?= (int)$progress ?>%</span>
            </div>

            <div class="stepper-track">
                <!-- Step 1: Buat Akun -->
                <div class="stepper-step completed">
                    <div class="stepper-circle"><i class="bi bi-check-lg"></i></div>
                    <div class="stepper-label">1. Buat Akun</div>
                </div>

                <!-- Step 2: Biodata -->
                <div class="stepper-step <?= $step_current > 2 ? 'completed' : ($step_current == 2 ? 'active' : '') ?>">
                    <div class="stepper-circle"><?= $step_current > 2 ? '<i class="bi bi-check-lg"></i>' : '2' ?></div>
                    <div class="stepper-label">2. Biodata</div>
                </div>

                <!-- Step 3: Berkas -->
                <div class="stepper-step <?= $step_current > 3 ? 'completed' : ($step_current == 3 ? 'active' : '') ?>">
                    <div class="stepper-circle"><?= $step_current > 3 ? '<i class="bi bi-check-lg"></i>' : '3' ?></div>
                    <div class="stepper-label">3. Dokumen</div>
                </div>

                <!-- Step 4: Ujian Seleksi -->
                <div class="stepper-step <?= $step_current > 4 ? 'completed' : ($step_current == 4 ? 'active' : '') ?>">
                    <div class="stepper-circle"><?= $step_current > 4 ? '<i class="bi bi-check-lg"></i>' : '4' ?></div>
                    <div class="stepper-label">4. Ujian Seleksi</div>
                </div>

                <!-- Step 5: Kelulusan -->
                <div class="stepper-step <?= $step_current == 5 ? 'completed active' : '' ?>">
                    <div class="stepper-circle"><?= $step_current == 5 ? '<i class="bi bi-check-lg"></i>' : '5' ?></div>
                    <div class="stepper-label">5. Kelulusan</div>
                </div>
            </div>
        </div>

        <!-- ═══ 4. KARTU AKSI CEPAT (MENU UTAMA PESERTA) ═══ -->
        <h5 class="fw-bold mb-3 text-dark"><i class="bi bi-lightning-charge-fill text-warning me-1"></i> Aksi &amp; Menu Utama</h5>

        <div class="peserta-grid-cards">

            <!-- Card 1: Biodata -->
            <div class="card-action-modern">
                <div class="card-action-top">
                    <div class="card-action-icon">
                        <i class="bi bi-person-lines-fill"></i>
                    </div>
                    <div class="card-action-text">
                        <h6>1. Lengkapi Biodata Diri</h6>
                        <p>Isi data diri calon siswa, data orang tua/wali, serta alamat tempat tinggal lengkap.</p>
                    </div>
                </div>
                <a href="<?= base_url('ppdb/biodata') ?>" class="btn btn-outline-success w-100 rounded-pill fw-bold" style="font-size: 13.5px;">
                    <i class="bi bi-pencil-square me-1"></i> Buka Form Biodata &rarr;
                </a>
            </div>

            <!-- Card 2: Upload Berkas -->
            <div class="card-action-modern">
                <div class="card-action-top">
                    <div class="card-action-icon" style="background:#e0f2fe; color:#0284c7;">
                        <i class="bi bi-cloud-arrow-up-fill"></i>
                    </div>
                    <div class="card-action-text">
                        <h6>2. Unggah Dokumen Digital</h6>
                        <p>Upload scan/foto Pas Foto, KK, Akta Kelahiran, dan Surat Keterangan Kelas 9.</p>
                    </div>
                </div>
                <a href="<?= base_url('ppdb/upload') ?>" class="btn btn-outline-primary w-100 rounded-pill fw-bold" style="font-size: 13.5px;">
                    <i class="bi bi-upload me-1"></i> Upload Dokumen &rarr;
                </a>
            </div>

            <!-- Card 3: Detail Pendaftaran -->
            <div class="card-action-modern">
                <div class="card-action-top">
                    <div class="card-action-icon" style="background:#fef3c7; color:#d97706;">
                        <i class="bi bi-file-earmark-person-fill"></i>
                    </div>
                    <div class="card-action-text">
                        <h6>3. Detail &amp; Edit Pendaftaran</h6>
                        <p>Periksa seluruh data formulir dan cetak bukti tanda bukti pendaftaran (PDF).</p>
                    </div>
                </div>
                <div class="d-flex gap-2">
                    <a href="<?= base_url('ppdb/detail') ?>" class="btn btn-outline-secondary flex-grow-1 rounded-pill fw-bold" style="font-size: 13.5px;">
                        Detail Data
                    </a>
                    <a href="<?= base_url('ppdb/cetak_kartu') ?>" target="_blank" class="btn btn-success flex-grow-1 rounded-pill fw-bold" style="background:#059669; border-color:#059669; font-size: 13.5px;">
                        <i class="bi bi-printer me-1"></i> Kartu Tes
                    </a>
                </div>
            </div>

        </div>

    </main>
</div>

<!-- ═══ MOBILE BOTTOM NAVIGATION BAR ═══ -->
<nav class="peserta-bottom-nav">
    <a href="<?= base_url('ppdb/dashboard') ?>" class="active">
        <i class="bi bi-grid-fill"></i>
        <span>Dashboard</span>
    </a>
    <a href="<?= base_url('ppdb/biodata') ?>">
        <i class="bi bi-person-lines-fill"></i>
        <span>Biodata</span>
    </a>
    <a href="<?= base_url('ppdb/upload') ?>">
        <i class="bi bi-cloud-arrow-up-fill"></i>
        <span>Berkas</span>
    </a>
    <a href="<?= base_url('ppdb/cetak_kartu') ?>" target="_blank">
        <i class="bi bi-printer-fill"></i>
        <span>Kartu Tes</span>
    </a>
    <a href="<?= base_url('ppdb/detail') ?>">
        <i class="bi bi-person-badge"></i>
        <span>Akun</span>
    </a>
</nav>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>