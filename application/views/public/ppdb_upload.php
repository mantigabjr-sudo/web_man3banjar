<?php
$nama_lengkap   = $siswa->nama_lengkap ?? 'Peserta ' . htmlspecialchars($nama_ppdb ?? 'PPDB');
$no_pendaftaran = $siswa->no_pendaftaran ?? '-';
$jalur          = $siswa->jalur_pendaftaran ?? 'Reguler';

$foto_path = !empty($siswa->foto) ? FCPATH.'uploads/temp/ppdb/'.$siswa->foto : '';
$foto_url  = !empty($siswa->foto) ? base_url('uploads/temp/ppdb/'.$siswa->foto) : '';

$file_labels = [
    'foto'            => ['title' => 'Pas Foto Formal (Warna)', 'desc' => 'Foto resmi background merah/biru, wajah terlihat jelas (JPG/PNG maks 2MB)', 'type' => 'wajib'],
    'kk_file'         => ['title' => 'Kartu Keluarga (KK)', 'desc' => 'Scan / Foto asli Kartu Keluarga yang jelas (PDF/JPG/PNG maks 2MB)', 'type' => 'wajib'],
    'akta_file'       => ['title' => 'Akta Kelahiran Siswa', 'desc' => 'Scan / Foto akta kelahiran resmi calon siswa (PDF/JPG/PNG maks 2MB)', 'type' => 'wajib'],
    'sk_kelas9_file'  => ['title' => 'Surat Keterangan Kelas 9 / SKL / Ijazah', 'desc' => 'Surat ket. aktif kelas 9 SMP/MTs, SKL, atau Ijazah SMP/MTs (PDF/JPG/PNG maks 2MB)', 'type' => 'wajib'],
    'sertifikat_file' => ['title' => 'Piagam / Sertifikat Prestasi / Tahfidz / KIP', 'desc' => 'Wajib untuk Jalur Prestasi / Tahfidz / Afirmasi, Opsional untuk Reguler (PDF/JPG maks 2MB)', 'type' => in_array($jalur, ['Prestasi', 'Tahfidz', 'Afirmasi']) ? 'wajib_jalur' : 'opsional'],
    'nisn_file'       => ['title' => 'Bukti Cetak / Tangkapan Layar NISN', 'desc' => 'Bukti NISN aktif dari situs Kemdikbud/Kemenag (PDF/JPG/PNG maks 2MB)', 'type' => 'opsional'],
    'rapor_file'      => ['title' => 'Buku Rapor Semester 1 - 5', 'desc' => 'Scan halaman nilai rapor SMP/MTs semester 1-5 (PDF/JPG/PNG maks 2MB)', 'type' => 'opsional'],
    'ijazah_file'     => ['title' => 'Ijazah SD / MI / Berkas Pendukung Lain', 'desc' => 'Dokumen pendukung tambahan jika diperlukan (PDF/JPG/PNG maks 2MB)', 'type' => 'opsional']
];

$uploaded_count = 0;
$total_required = 4 + (in_array($jalur, ['Prestasi', 'Tahfidz', 'Afirmasi']) ? 1 : 0);
$required_uploaded = 0;

foreach($file_labels as $key => $meta){
    if(!empty($siswa->$key)){
        $uploaded_count++;
        if($meta['type'] == 'wajib' || $meta['type'] == 'wajib_jalur'){
            $required_uploaded++;
        }
    }
}
$progress_berkas = round(($required_uploaded / max(1, $total_required)) * 100);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <title>Unggah Berkas Dokumen | PMB MAN 3 Banjar</title>

    <link rel="icon" type="image/png" href="<?= base_url('assets/img/favicon.png') ?>">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link rel="stylesheet" href="<?= base_url('assets/css/ppdb-peserta.css?v=4') ?>">
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
            <strong>Unggah Berkas Pendaftaran</strong>
            <small><?= htmlspecialchars($nama_lengkap) ?></small>
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

        <!-- Progress Kelengkapan Berkas Banner -->
        <div class="card border-0 shadow-sm rounded-4 p-3 p-md-4 mb-4" style="background: #ffffff; border: 1.5px solid var(--border-subtle) !important;">
            <div class="d-flex justify-content-between align-items-center mb-2 flex-wrap gap-2">
                <div>
                    <h5 class="fw-bold mb-1 text-dark"><i class="bi bi-cloud-arrow-up text-success me-2"></i> Unggah Dokumen Berkas PMB</h5>
                    <p class="text-muted small mb-0">Pastikan format file PDF, JPG, atau PNG dengan ukuran maksimal 2MB per file.</p>
                </div>
                <span class="badge <?= $progress_berkas >= 100 ? 'bg-success' : 'bg-warning text-dark' ?> px-3 py-2 rounded-pill fw-bold">
                    <?= $progress_berkas >= 100 ? '<i class="bi bi-check-circle-fill me-1"></i> Berkas Wajib Lengkap' : 'Kelengkapan: '.$progress_berkas.'%' ?>
                </span>
            </div>
            <div class="progress" style="height: 8px; border-radius: 10px;">
                <div class="progress-bar bg-success" role="progressbar" style="width: <?= $progress_berkas ?>%;" aria-valuenow="<?= $progress_berkas ?>" aria-valuemin="0" aria-valuemax="100"></div>
            </div>
        </div>

        <form method="post" action="<?= base_url('ppdb/save_upload') ?>" enctype="multipart/form-data">

            <div class="row g-3 mb-4">
                <?php foreach($file_labels as $field => $meta): ?>
                    <?php 
                        $has_file = !empty($siswa->$field);
                        $file_link = $has_file ? base_url('uploads/temp/ppdb/'.$siswa->$field) : '';
                    ?>
                    <div class="col-12">
                        <div class="card border-0 shadow-sm rounded-4 p-3 p-md-4" style="background:#ffffff; border: 1.5px solid <?= $has_file ? '#bbf7d0' : 'var(--border-subtle)' ?> !important;">
                            <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-3">
                                
                                <div class="flex-grow-1">
                                    <div class="d-flex align-items-center gap-2 mb-1">
                                        <h6 class="fw-bold mb-0 text-dark"><?= $meta['title'] ?></h6>
                                        <?php if($meta['type'] == 'wajib'): ?>
                                            <span class="badge bg-danger px-2 py-1" style="font-size: 10px;">WAJIB</span>
                                        <?php elseif($meta['type'] == 'wajib_jalur'): ?>
                                            <span class="badge bg-primary px-2 py-1" style="font-size: 10px;">WAJIB JALUR (<?= strtoupper($jalur) ?>)</span>
                                        <?php else: ?>
                                            <span class="badge bg-secondary px-2 py-1" style="font-size: 10px;">OPSIONAL</span>
                                        <?php endif; ?>
                                    </div>
                                    <p class="text-muted small mb-2" style="font-size: 12px;"><?= $meta['desc'] ?></p>

                                    <!-- Status file yang tersimpan -->
                                    <div class="d-flex align-items-center gap-2 flex-wrap">
                                        <?php if($has_file): ?>
                                            <span class="badge bg-success-subtle text-success border border-success-subtle px-2 py-1 rounded-pill" style="font-size: 11.5px;">
                                                <i class="bi bi-check-circle-fill me-1"></i> Terunggah: <?= htmlspecialchars($siswa->$field) ?>
                                            </span>
                                            <a href="<?= $file_link ?>" target="_blank" class="btn btn-sm btn-outline-success rounded-pill px-3 py-0" style="font-size: 11px; font-weight:700;">
                                                <i class="bi bi-eye me-1"></i> Lihat Berkas
                                            </a>
                                        <?php else: ?>
                                            <span class="badge bg-light text-secondary border px-2 py-1 rounded-pill" style="font-size: 11px;">
                                                <i class="bi bi-dash-circle me-1"></i> Belum ada berkas terunggah
                                            </span>
                                        <?php endif; ?>
                                    </div>
                                </div>

                                <div class="w-100 w-md-auto" style="min-width: 240px;">
                                    <label class="form-label text-muted small fw-bold mb-1">Pilih File Baru:</label>
                                    <input type="file" name="<?= $field ?>" class="form-control form-control-sm" accept=".pdf,.jpg,.jpeg,.png">
                                </div>

                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>

            <!-- Sticky Save Bar -->
            <div class="card border-0 shadow-lg rounded-4 p-3 bg-white sticky-bottom" style="z-index: 50; border: 1.5px solid #cbd5e1 !important;">
                <div class="d-flex flex-column flex-md-row justify-content-between align-items-center gap-2">
                    <small class="text-muted"><i class="bi bi-shield-check me-1"></i> Klik simpan berkas setelah memilih dokumen.</small>
                    <div class="d-flex gap-2 w-100 w-md-auto">
                        <a href="<?= base_url('ppdb/dashboard') ?>" class="btn btn-outline-secondary px-4 py-2 rounded-pill fw-bold" style="font-size: 14px;">
                            Batal
                        </a>
                        <button type="submit" class="btn btn-success px-4 py-2 rounded-pill fw-bold shadow-sm flex-grow-1 flex-md-grow-0" style="background:#059669; border-color:#059669; font-size: 14px;">
                            <i class="bi bi-cloud-arrow-up-fill me-1"></i> Simpan &amp; Upload Berkas
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