<?php
if(!function_exists('ppdb_upload_e')){
    function ppdb_upload_e($text){
        return htmlspecialchars((string)$text, ENT_QUOTES, 'UTF-8');
    }
}

$nama_lengkap   = !empty($siswa->nama_lengkap) ? $siswa->nama_lengkap : 'Peserta ' . htmlspecialchars(!empty($nama_ppdb) ? $nama_ppdb : 'PPDB');
$no_pendaftaran = !empty($siswa->no_pendaftaran) ? $siswa->no_pendaftaran : '-';
$jalur          = !empty($siswa->jalur_pendaftaran) ? $siswa->jalur_pendaftaran : 'Reguler';

$foto_path = !empty($siswa->foto) ? FCPATH.'uploads/temp/ppdb/'.$siswa->foto : '';
$foto_url  = !empty($siswa->foto) ? base_url('uploads/temp/ppdb/'.$siswa->foto) : '';

// Berkas selaras dengan pengumuman persyaratan resmi PMB
$berkas = [
    [
        'field' => 'foto',
        'label' => 'Pas Foto Formal 3x4 (Warna)',
        'required' => true,
        'badge' => 'WAJIB',
        'badge_class' => 'bg-danger',
        'note' => 'Format JPG / PNG latar belakang merah/biru, maksimal 2MB.',
        'accept' => 'image/jpeg,image/png'
    ],
    [
        'field' => 'kk_file',
        'label' => 'Kartu Keluarga (KK)',
        'required' => true,
        'badge' => 'WAJIB',
        'badge_class' => 'bg-danger',
        'note' => 'Scan/foto asli atau fotokopi jelas nomor KK & NIK keluarga.',
        'accept' => 'image/jpeg,image/png,application/pdf'
    ],
    [
        'field' => 'akta_file',
        'label' => 'Akta Kelahiran Calon Siswa',
        'required' => true,
        'badge' => 'WAJIB',
        'badge_class' => 'bg-danger',
        'note' => 'Scan/foto Akta Kelahiran yang jelas dan terbaca.',
        'accept' => 'image/jpeg,image/png,application/pdf'
    ],
    [
        'field' => 'sk_kelas9_file',
        'label' => 'Surat Keterangan Kelas 9 / SKL / Ijazah',
        'required' => true,
        'badge' => 'WAJIB',
        'badge_class' => 'bg-danger',
        'note' => 'Surat Keterangan Aktif Kelas 9 dari SMP/MTs asal atau SKL/Ijazah.',
        'accept' => 'image/jpeg,image/png,application/pdf'
    ],
    [
        'field' => 'sertifikat_file',
        'label' => 'Sertifikat Prestasi / Tahfidz / KIP',
        'required' => in_array($jalur, ['Prestasi', 'Tahfidz', 'Afirmasi']),
        'badge' => in_array($jalur, ['Prestasi', 'Tahfidz', 'Afirmasi']) ? 'WAJIB JALUR '.$jalur : 'OPSIONAL',
        'badge_class' => in_array($jalur, ['Prestasi', 'Tahfidz', 'Afirmasi']) ? 'bg-primary' : 'bg-secondary',
        'note' => 'Piagam juara lomba minimal tingkat Kab/Kota, Syahadah Tahfidz, atau Kartu KIP/PKH/KKS.',
        'accept' => 'image/jpeg,image/png,application/pdf'
    ],
    [
        'field' => 'nisn_file',
        'label' => 'Bukti Cetak NISN Aktif',
        'required' => false,
        'badge' => 'OPSIONAL',
        'badge_class' => 'bg-secondary',
        'note' => 'Tangkapan layar/cetak bukti NISN aktif dari web Kemdikbud/Kemenag.',
        'accept' => 'image/jpeg,image/png,application/pdf'
    ],
    [
        'field' => 'rapor_file',
        'label' => 'Rapor / Nilai Semester 1-5',
        'required' => false,
        'badge' => 'OPSIONAL',
        'badge_class' => 'bg-secondary',
        'note' => 'Scan nilai rapor SMP/MTs (boleh menyusul jika belum lengkap).',
        'accept' => 'image/jpeg,image/png,application/pdf'
    ],
    [
        'field' => 'ijazah_file',
        'label' => 'Ijazah SD/MI / Dokumen Pendukung Lain',
        'required' => false,
        'badge' => 'OPSIONAL',
        'badge_class' => 'bg-secondary',
        'note' => 'Ijazah jenjang sebelumnya atau dokumen penunjang tambahan.',
        'accept' => 'image/jpeg,image/png,application/pdf'
    ]
];

$total_berkas = count($berkas);
$uploaded_berkas = 0;

foreach($berkas as $b){
    $field = $b['field'];
    if(!empty($siswa->$field)){
        $uploaded_berkas++;
    }
}

$upload_percent = $total_berkas > 0 ? round(($uploaded_berkas / $total_berkas) * 100) : 0;

$verifikasi_berkas = [];
if(!empty($siswa->verifikasi_berkas_json)){
    $verifikasi_berkas = json_decode($siswa->verifikasi_berkas_json, true);
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <title>Upload Berkas Dokumen | PMB MAN 3 Banjar</title>

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
            <strong>Upload Berkas Dokumen</strong>
            <small><?= $nama_lengkap ?></small>
        </div>
    </div>
    <a href="<?= base_url('ppdb/dashboard') ?>" class="btn btn-sm btn-outline-secondary px-3 py-1 rounded-pill" style="font-size: 11.5px; font-weight: 700;">
        <i class="bi bi-arrow-left"></i> Kembali
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
                    <strong>PMB MAN 3</strong>
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
                <a href="<?= base_url('ppdb/dashboard') ?>">
                    <i class="bi bi-grid-fill"></i> Dashboard
                </a>
                <a href="<?= base_url('ppdb/biodata') ?>">
                    <i class="bi bi-person-lines-fill"></i> Lengkapi Biodata
                </a>
                <a href="<?= base_url('ppdb/upload') ?>" class="active">
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

        <!-- Progress Card -->
        <div class="card border-0 shadow-sm rounded-4 p-4 mb-4" style="background: #ffffff; border: 1.5px solid var(--border-color) !important;">
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-2 mb-3">
                <div>
                    <h5 class="fw-bold mb-1 text-dark"><i class="bi bi-cloud-arrow-up-fill text-success me-2"></i> Unggah Berkas Pendaftaran</h5>
                    <p class="text-muted small mb-0">Format file: JPG, PNG, atau PDF (Ukuran maksimal 2 MB per berkas). Pastikan dokumen jelas dan terbaca.</p>
                </div>
                <div class="text-md-end">
                    <span class="badge bg-success bg-opacity-10 text-success fw-bold px-3 py-2 rounded-pill" style="font-size: 13px;">
                        <?= $uploaded_berkas ?> dari <?= $total_berkas ?> Dokumen Terunggah (<?= $upload_percent ?>%)
                    </span>
                </div>
            </div>

            <div class="progress" style="height: 8px; border-radius: 6px;">
                <div class="progress-bar bg-success" role="progressbar" style="width: <?= $upload_percent ?>%;"></div>
            </div>
        </div>

        <!-- Form Upload -->
        <form method="post" action="<?= base_url('ppdb/save_upload') ?>" enctype="multipart/form-data">

            <div class="d-flex flex-column gap-3 mb-4">
                <?php foreach($berkas as $b): ?>
                    <?php 
                    $field = $b['field'];
                    $has_file = !empty($siswa->$field);
                    $file_name = $siswa->$field ?? '';
                    $file_url = $has_file ? base_url('uploads/temp/ppdb/'.$file_name) : '';
                    $is_pdf = strtolower(pathinfo($file_name, PATHINFO_EXTENSION)) === 'pdf';

                    // Status verifikasi dokumen
                    $vb_status = $verifikasi_berkas[$field]['status'] ?? 'Belum Diverifikasi';
                    $vb_catatan = $verifikasi_berkas[$field]['catatan'] ?? '';
                    ?>

                    <div class="card border-0 shadow-sm rounded-4 p-3 p-md-4 <?= $has_file ? 'bg-light' : 'bg-white' ?>" style="border: 1.5px solid <?= $has_file ? '#a7f3d0' : '#e2e8f0' ?> !important;">
                        <div class="row align-items-center g-3">
                            
                            <!-- Info Dokumen -->
                            <div class="col-md-5">
                                <div class="d-flex align-items-center gap-2 mb-1">
                                    <span class="badge <?= $b['badge_class'] ?> px-2 py-1 rounded-pill" style="font-size: 10px; font-weight: 800;">
                                        <?= $b['badge'] ?>
                                    </span>
                                    <?php if($has_file): ?>
                                        <span class="badge bg-success bg-opacity-10 text-success px-2 py-1 rounded-pill" style="font-size: 10px; font-weight: 700;">
                                            <i class="bi bi-check-circle-fill me-1"></i> Sudah Diupload
                                        </span>
                                    <?php else: ?>
                                        <span class="badge bg-secondary bg-opacity-10 text-secondary px-2 py-1 rounded-pill" style="font-size: 10px; font-weight: 700;">
                                            Belum Ada File
                                        </span>
                                    <?php endif; ?>
                                </div>

                                <h6 class="fw-bold mb-1 text-dark" style="font-size: 14.5px;"><?= $b['label'] ?></h6>
                                <p class="text-muted small mb-0" style="font-size: 12px;"><?= $b['note'] ?></p>

                                <?php if($vb_status == 'Perlu Perbaikan'): ?>
                                    <div class="mt-2 p-2 rounded-2 bg-danger bg-opacity-10 text-danger small fw-semibold" style="font-size: 11.5px;">
                                        <i class="bi bi-exclamation-triangle-fill me-1"></i> Perbaikan: <?= htmlspecialchars($vb_catatan) ?>
                                    </div>
                                <?php endif; ?>
                            </div>

                            <!-- Preview File (Jika Ada) -->
                            <div class="col-md-3 text-center text-md-start">
                                <?php if($has_file): ?>
                                    <div class="d-inline-flex align-items-center gap-2 p-2 rounded-3 bg-white border">
                                        <?php if($is_pdf): ?>
                                            <i class="bi bi-file-earmark-pdf-fill text-danger fs-3"></i>
                                        <?php else: ?>
                                            <img src="<?= $file_url ?>" alt="Preview" style="width: 38px; height: 38px; object-fit: cover; border-radius: 6px;">
                                        <?php endif; ?>
                                        <div class="text-start" style="max-width: 140px; overflow: hidden;">
                                            <a href="<?= $file_url ?>" target="_blank" class="fw-bold text-success text-decoration-none small d-block text-truncate" title="Klik untuk lihat file">
                                                <i class="bi bi-eye"></i> Lihat File
                                            </a>
                                            <small class="text-muted" style="font-size: 10.5px;"><?= $is_pdf ? 'Dokumen PDF' : 'Gambar' ?></small>
                                        </div>
                                    </div>
                                <?php else: ?>
                                    <span class="text-muted small italic" style="font-size: 12px;"><i class="bi bi-dash-circle me-1"></i> Belum ada file</span>
                                <?php endif; ?>
                            </div>

                            <!-- Input File -->
                            <div class="col-md-4">
                                <label class="form-label text-muted small mb-1 fw-bold"><?= $has_file ? 'Ganti / Upload Ulang File:' : 'Pilih File Dokumen:' ?></label>
                                <input type="file" name="<?= $field ?>" class="form-control form-control-sm rounded-3" accept="<?= $b['accept'] ?>" style="font-size: 13px; padding: 7px 10px;">
                            </div>

                        </div>
                    </div>
                <?php endforeach; ?>
            </div>

            <!-- Tombol Simpan -->
            <div class="card border-0 shadow-lg rounded-4 p-3 bg-white sticky-bottom" style="z-index: 50; border: 1.5px solid #cbd5e1 !important;">
                <div class="d-flex flex-column flex-md-row justify-content-between align-items-center gap-2">
                    <small class="text-muted"><i class="bi bi-info-circle me-1"></i> Klik tombol simpan setelah memilih file baru di atas.</small>
                    <div class="d-flex gap-2 w-100 w-md-auto">
                        <a href="<?= base_url('ppdb/dashboard') ?>" class="btn btn-outline-secondary px-4 py-2 rounded-pill fw-bold" style="font-size: 14px;">
                            Kembali
                        </a>
                        <button type="submit" class="btn btn-success px-4 py-2 rounded-pill fw-bold shadow-sm flex-grow-1 flex-md-grow-0" style="background:#059669; border-color:#059669; font-size: 14px;">
                            <i class="bi bi-cloud-arrow-up-fill me-1"></i> Simpan Semua Berkas
                        </button>
                    </div>
                </div>
            </div>

        </form>

    </main>
</div>

<!-- ═══ MOBILE BOTTOM NAVIGATION BAR ═══ -->
<nav class="peserta-bottom-nav">
    <a href="<?= base_url('ppdb/dashboard') ?>">
        <i class="bi bi-grid-fill"></i>
        <span>Dashboard</span>
    </a>
    <a href="<?= base_url('ppdb/biodata') ?>">
        <i class="bi bi-person-lines-fill"></i>
        <span>Biodata</span>
    </a>
    <a href="<?= base_url('ppdb/upload') ?>" class="active">
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