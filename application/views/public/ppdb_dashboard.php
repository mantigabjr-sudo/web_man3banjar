<?php
if(!function_exists('ppdb_dash_e')){
    function ppdb_dash_e($text){
        return htmlspecialchars((string)$text, ENT_QUOTES, 'UTF-8');
    }
}

if(!function_exists('ppdb_dash_value')){
    function ppdb_dash_value($text, $default = '-'){
        $text = trim((string)$text);
        return $text !== '' ? ppdb_dash_e($text) : $default;
    }
}

$nama            = htmlspecialchars($nama_ppdb ?? 'PMB');
$nama_lengkap    = $siswa->nama_lengkap ?? 'Calon Siswa ' . $nama;
$no_pendaftaran  = $siswa->no_pendaftaran ?? '-';
$nisn            = $siswa->nisn ?? '-';
$asal_sekolah    = $siswa->asal_sekolah ?? '-';
$no_hp           = $siswa->no_hp ?? '-';
$jalur           = $siswa->jalur_pendaftaran ?? 'Reguler';
$email           = $siswa->email ?? '-';

$status_text     = $status_text ?? ($siswa->status ?? '-');
$status_seleksi  = $status_seleksi ?? '-';
$progress        = isset($progress) ? (int)$progress : 0;
$desc            = $desc ?? 'Silakan lengkapi data pendaftaran Anda.';
$action_link     = $action_link ?? base_url('ppdb/biodata');
$action_text     = $action_text ?? 'Lanjutkan Pendaftaran';

if($progress < 0) $progress = 0;
if($progress > 100) $progress = 100;

// Penentuan langkah aktif (1..5)
$step_active = 1;
if($status_text == 'Lengkapi Biodata'){
    $step_active = 2;
} elseif($status_text == 'Upload Berkas' || $status_text == 'Perlu Perbaikan'){
    $step_active = 3;
} elseif($status_text == 'Menunggu Verifikasi Berkas' || $status_text == 'Lulus Verifikasi' || $status_text == 'Menuju Tes'){
    $step_active = 4;
} elseif($status_text == 'Diterima' || $status_text == 'Ditolak'){
    $step_active = 5;
}

$foto_path = !empty($siswa->foto) ? FCPATH.'uploads/temp/ppdb/'.$siswa->foto : '';
$foto_url  = !empty($siswa->foto) ? base_url('uploads/temp/ppdb/'.$siswa->foto) : '';

$status_badge_color = 'background: rgba(255,255,255,0.25); color: #fff;';
if($status_text == 'Diterima'){
    $status_badge_color = 'background: #22c55e; color: #fff;';
} elseif($status_text == 'Ditolak'){
    $status_badge_color = 'background: #ef4444; color: #fff;';
} elseif($status_text == 'Perlu Perbaikan'){
    $status_badge_color = 'background: #f97316; color: #fff;';
} elseif($status_text == 'Lulus Verifikasi' || $status_text == 'Menuju Tes'){
    $status_badge_color = 'background: #10b981; color: #fff; font-weight: 800;';
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <title>Dashboard Calon Siswa <?= $nama ?> | MAN 3 Banjar</title>

    <link rel="icon" type="image/png" href="<?= base_url('assets/img/favicon.png') ?>">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link rel="stylesheet" href="<?= base_url('assets/css/ppdb-peserta.css?v=3') ?>">
</head>
<body>

<!-- ═══ MOBILE HEADER ═══ -->
<div class="peserta-mobile-head">
    <div class="brand">
        <?php if(file_exists(FCPATH.'assets/img/logo-madrasah.png')): ?>
            <img src="<?= base_url('assets/img/logo-madrasah.png') ?>" alt="Logo">
        <?php else: ?>
            <span class="badge bg-success">M3</span>
        <?php endif; ?>
        <div>
            <strong><?= $nama ?> MAN 3 Banjar</strong>
            <small><?= ppdb_dash_value($no_pendaftaran) ?></small>
        </div>
    </div>
    <a href="<?= base_url('ppdb/logout') ?>" class="btn btn-sm btn-outline-danger px-3 py-1 rounded-pill" style="font-size: 11.5px; font-weight: 700;">
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
                    <strong><?= $nama ?> MAN 3</strong>
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
                    <strong><?= ppdb_dash_value($nama_lengkap) ?></strong>
                    <small><?= ppdb_dash_value($no_pendaftaran) ?></small>
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

        <!-- Flash Messages -->
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

        <!-- Perhatian Perbaikan Dokumen (Jika Ada) -->
        <?php 
        $verifikasi_berkas = [];
        if(!empty($siswa->verifikasi_berkas_json)){
            $verifikasi_berkas = json_decode($siswa->verifikasi_berkas_json, true);
        }
        $ada_perbaikan = false;
        if($status_text == 'Perlu Perbaikan' && !empty($verifikasi_berkas)){
            foreach($verifikasi_berkas as $f => $val){
                if(isset($val['status']) && $val['status'] == 'Perlu Perbaikan'){
                    $ada_perbaikan = true;
                }
            }
        }
        ?>

        <?php if($ada_perbaikan): ?>
            <div class="alert alert-danger rounded-4 border-0 p-3 p-md-4 mb-4 shadow-sm" style="background-color: #fff1f2; border: 1.5px solid #fecdd3 !important;">
                <h6 class="fw-bold mb-2 text-danger d-flex align-items-center gap-2">
                    <i class="bi bi-exclamation-circle-fill"></i> Perhatian: Dokumen Perlu Diperbaiki
                </h6>
                <p class="mb-2 text-secondary small">Panitia menemukan catatan pada berkas yang Anda unggah. Silakan perbaiki dan unggah ulang:</p>
                <ul class="mb-3 ps-3">
                    <?php foreach($verifikasi_berkas as $field => $item_vb): ?>
                        <?php if(isset($item_vb['status']) && $item_vb['status'] == 'Perlu Perbaikan'): ?>
                            <li class="mb-1 small fw-semibold text-dark">
                                <span class="badge bg-danger me-1"><?= ucfirst(str_replace('_file','',$field)) ?></span>
                                Catatan: <span class="text-danger">"<?= ppdb_dash_e($item_vb['catatan'] ?? '-') ?>"</span>
                            </li>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </ul>
                <a href="<?= base_url('ppdb/upload') ?>" class="btn btn-danger btn-sm rounded-pill fw-bold px-4">
                    <i class="bi bi-arrow-repeat me-1"></i> Perbaiki Berkas Sekarang
                </a>
            </div>
        <?php endif; ?>

        <!-- 1. Hero Card Profile -->
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
                        <h1><?= ppdb_dash_value($nama_lengkap) ?></h1>
                        <p>No. Pendaftaran: <strong><?= ppdb_dash_value($no_pendaftaran) ?></strong> • NISN: <strong><?= ppdb_dash_value($nisn) ?></strong></p>
                    </div>
                </div>

                <div>
                    <span class="peserta-hero-badge" style="<?= $status_badge_color ?>">
                        <i class="bi bi-check-circle-fill"></i> <?= ppdb_dash_value($status_text) ?>
                    </span>
                </div>
            </div>

            <div class="peserta-hero-tags">
                <div class="peserta-tag-pill"><i class="bi bi-diagram-3 me-1"></i> Jalur: <strong><?= ppdb_dash_value($jalur) ?></strong></div>
                <div class="peserta-tag-pill"><i class="bi bi-building me-1"></i> Asal: <strong><?= ppdb_dash_value($asal_sekolah) ?></strong></div>
                <div class="peserta-tag-pill"><i class="bi bi-telephone me-1"></i> WA: <strong><?= ppdb_dash_value($no_hp) ?></strong></div>
                <?php if($email != '-'): ?>
                    <div class="peserta-tag-pill"><i class="bi bi-envelope me-1"></i> <?= ppdb_dash_value($email) ?></div>
                <?php endif; ?>
            </div>
        </div>

        <!-- 2. Visual Stepper Timeline (5 Tahap) -->
        <div class="peserta-stepper-card">
            <div class="stepper-header">
                <h5><i class="bi bi-signpost-split-fill text-success me-1"></i> Tahapan Pendaftaran Anda</h5>
                <span class="badge bg-success bg-opacity-10 text-success fw-bold px-3 py-1 rounded-pill">Progress <?= $progress ?>%</span>
            </div>

            <div class="stepper-track">
                <div class="stepper-step completed">
                    <div class="stepper-circle"><i class="bi bi-check-lg"></i></div>
                    <div class="stepper-label">1. Buat Akun</div>
                </div>
                <div class="stepper-step <?= $step_active >= 2 ? ($step_active > 2 ? 'completed' : 'active') : '' ?>">
                    <div class="stepper-circle"><?= $step_active > 2 ? '<i class="bi bi-check-lg"></i>' : '2' ?></div>
                    <div class="stepper-label">2. Biodata</div>
                </div>
                <div class="stepper-step <?= $step_active >= 3 ? ($step_active > 3 ? 'completed' : 'active') : '' ?>">
                    <div class="stepper-circle"><?= $step_active > 3 ? '<i class="bi bi-check-lg"></i>' : '3' ?></div>
                    <div class="stepper-label">3. Dokumen</div>
                </div>
                <div class="stepper-step <?= $step_active >= 4 ? ($step_active > 4 ? 'completed' : 'active') : '' ?>">
                    <div class="stepper-circle"><?= $step_active > 4 ? '<i class="bi bi-check-lg"></i>' : '4' ?></div>
                    <div class="stepper-label">4. Ujian Seleksi</div>
                </div>
                <div class="stepper-step <?= $step_active == 5 ? 'completed' : '' ?>">
                    <div class="stepper-circle"><?= $step_active == 5 ? '<i class="bi bi-check-lg"></i>' : '5' ?></div>
                    <div class="stepper-label">5. Kelulusan</div>
                </div>
            </div>
        </div>

        <!-- 3. Banner Jadwal Tes & Kartu Ujian (Jika Sudah Ada / Lulus Verifikasi) -->
        <?php if(!empty($siswa->no_peserta_tes) || in_array($siswa->status, ['Lulus Verifikasi', 'Menuju Tes', 'Diterima'])): ?>
            <div class="card border-0 shadow-sm rounded-4 p-3 p-md-4 mb-4" style="background: linear-gradient(135deg, #ecfdf5, #f0fdf4); border: 1.5px solid #a7f3d0 !important;">
                <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-3">
                    <div>
                        <span class="badge bg-success px-3 py-1 rounded-pill mb-2 fw-bold" style="font-size: 11px;">JADWAL TES SUDAH TERBIT</span>
                        <h5 class="fw-bold text-dark mb-1">
                            No. Peserta Ujian: <span class="text-success font-monospace"><?= htmlspecialchars($siswa->no_peserta_tes ?? $siswa->no_pendaftaran) ?></span>
                        </h5>
                        <div class="d-flex flex-wrap gap-3 text-secondary small mt-2">
                            <span><i class="bi bi-calendar-event text-success"></i> Tanggal: <strong><?= !empty($siswa->tanggal_tes) ? (function_exists('tanggal_indo') ? tanggal_indo($siswa->tanggal_tes) : $siswa->tanggal_tes) : 'Sesuai Jadwal Panitia' ?></strong></span>
                            <span><i class="bi bi-clock text-success"></i> Jam: <strong><?= !empty($siswa->jam_tes) ? htmlspecialchars($siswa->jam_tes) : '08:00 - 11.30 WITA' ?></strong></span>
                            <span><i class="bi bi-geo-alt text-success"></i> Lokasi: <strong><?= !empty($siswa->ruang_tes) ? htmlspecialchars($siswa->ruang_tes) : 'Kampus MAN 3 Banjar' ?></strong></span>
                        </div>
                    </div>
                    <div>
                        <a href="<?= base_url('ppdb/cetak_kartu') ?>" target="_blank" class="btn btn-success fw-bold px-4 py-2 rounded-pill shadow-sm d-inline-flex align-items-center gap-2" style="background: #059669; border-color: #059669; font-size: 14px;">
                            <i class="bi bi-printer-fill fs-5"></i> Cetak Kartu Ujian (PDF)
                        </a>
                    </div>
                </div>
            </div>
        <?php endif; ?>

        <!-- 4. Quick Action Cards -->
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
                <div>
                    <a href="<?= base_url('ppdb/biodata') ?>" class="btn btn-outline-success w-100 fw-bold py-2 rounded-pill" style="font-size: 13.5px;">
                        <i class="bi bi-pencil-square me-1"></i> Buka Form Biodata &rarr;
                    </a>
                </div>
            </div>

            <!-- Card 2: Upload Berkas -->
            <div class="card-action-modern">
                <div class="card-action-top">
                    <div class="card-action-icon" style="background: #eff6ff; color: #2563eb;">
                        <i class="bi bi-cloud-arrow-up-fill"></i>
                    </div>
                    <div class="card-action-text">
                        <h6>2. Unggah Dokumen Digital</h6>
                        <p>Upload scan/foto Pas Foto, KK, Akta Kelahiran, dan Surat Keterangan Kelas 9.</p>
                    </div>
                </div>
                <div>
                    <a href="<?= base_url('ppdb/upload') ?>" class="btn btn-outline-primary w-100 fw-bold py-2 rounded-pill" style="font-size: 13.5px;">
                        <i class="bi bi-folder2-open me-1"></i> Upload Dokumen &rarr;
                    </a>
                </div>
            </div>

            <!-- Card 3: Detail Pendaftaran -->
            <div class="card-action-modern">
                <div class="card-action-top">
                    <div class="card-action-icon" style="background: #f8fafc; color: #475569;">
                        <i class="bi bi-file-earmark-text-fill"></i>
                    </div>
                    <div class="card-action-text">
                        <h6>3. Detail &amp; Edit Pendaftaran</h6>
                        <p>Periksa kembali seluruh data yang telah tersimpan dan edit jika terdapat kesalahan.</p>
                    </div>
                </div>
                <div>
                    <a href="<?= base_url('ppdb/detail') ?>" class="btn btn-outline-secondary w-100 fw-bold py-2 rounded-pill" style="font-size: 13.5px;">
                        <i class="bi bi-eye-fill me-1"></i> Lihat Rangkuman &rarr;
                    </a>
                </div>
            </div>
        </div>

        <!-- 5. Pengumuman Panitia (Jika Ada) -->
        <?php if(!empty($pengumuman_ppdb)): ?>
            <div class="card border-0 shadow-sm rounded-4 p-4 mb-4" style="background: #ffffff; border: 1.5px solid var(--border-color) !important;">
                <div class="d-flex align-items-center gap-2 mb-3">
                    <i class="bi bi-megaphone-fill text-success fs-5"></i>
                    <h6 class="fw-bold mb-0 text-dark">Pengumuman &amp; Informasi Panitia</h6>
                </div>
                <div class="d-flex flex-column gap-3">
                    <?php foreach($pengumuman_ppdb as $p_item): ?>
                        <div class="p-3 rounded-3 bg-light border-start border-4 border-success">
                            <div class="d-flex justify-content-between align-items-center mb-1">
                                <span class="badge bg-success px-2 py-1" style="font-size: 10.5px;"><?= htmlspecialchars($p_item->kategori ?? 'Umum') ?></span>
                                <small class="text-muted" style="font-size: 11px;"><?= !empty($p_item->created_at) ? date('d-m-Y', strtotime($p_item->created_at)) : '' ?></small>
                            </div>
                            <h6 class="fw-bold text-dark mb-1" style="font-size: 14px;"><?= htmlspecialchars($p_item->judul) ?></h6>
                            <p class="text-secondary small mb-0"><?= nl2br(htmlspecialchars($p_item->isi)) ?></p>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php endif; ?>

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