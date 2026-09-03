<?php
$nama_lengkap   = $siswa->nama_lengkap ?? 'Peserta PMB';
$no_pendaftaran = $siswa->no_pendaftaran ?? '-';
$nisn           = $siswa->nisn ?? '-';
$status_db      = $siswa->status ?? 'Lengkapi Biodata';
$jalur          = $siswa->jalur_pendaftaran ?? 'Reguler';

$foto_path = !empty($siswa->foto) ? FCPATH.'uploads/temp/ppdb/'.$siswa->foto : '';
$foto_url  = !empty($siswa->foto) ? base_url('uploads/temp/ppdb/'.$siswa->foto) : '';

$file_items = [
    'foto'            => 'Pas Foto Formal (Warna)',
    'kk_file'         => 'Kartu Keluarga (KK)',
    'akta_file'       => 'Akta Kelahiran Siswa',
    'sk_kelas9_file'  => 'Surat Keterangan Kelas 9 / SKL / Ijazah',
    'sertifikat_file' => 'Piagam / Sertifikat Prestasi / KIP',
    'nisn_file'       => 'Bukti Cetak / Tangkapan Layar NISN',
    'rapor_file'      => 'Buku Rapor Semester 1 - 5',
    'ijazah_file'     => 'Ijazah SD / MI / Dokumen Lain'
];
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <title>Detail Data Pendaftaran | PMB MAN 3 Banjar</title>

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
            <strong>Detail Pendaftaran</strong>
            <small><?= htmlspecialchars($nama_lengkap) ?></small>
        </div>
    </div>
    <a href="<?= base_url('ppdb/dashboard') ?>" class="btn btn-sm btn-outline-secondary px-3 py-1 rounded-pill" style="font-size: 11.5px; font-weight: 700;">
        <i class="bi bi-arrow-left"></i> Dashboard
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
                <a href="<?= base_url('ppdb/upload') ?>">
                    <i class="bi bi-cloud-arrow-up-fill"></i> Upload Berkas
                </a>
                <a href="<?= base_url('ppdb/detail') ?>" class="active">
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

        <!-- Top Action Bar -->
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-3 mb-4">
            <div>
                <h4 class="fw-bold mb-1 text-dark"><i class="bi bi-person-vcard text-success me-2"></i> Detail Data Calon Siswa</h4>
                <p class="text-muted small mb-0">Rangkuman seluruh identitas, kontak, data orang tua, dan dokumen pendaftaran.</p>
            </div>
            <div class="d-flex flex-wrap gap-2 w-100 w-md-auto">
                <a href="<?= base_url('ppdb/edit_detail') ?>" class="btn btn-outline-success px-3 py-2 rounded-pill fw-bold" style="font-size: 13.5px;">
                    <i class="bi bi-pencil-square me-1"></i> Edit Data
                </a>
                <?php 
                    $is_lulus_verifikasi = in_array($status_db, ['Lulus Verifikasi', 'Diterima']) || !empty($siswa->no_peserta_tes);
                ?>
                <?php if($is_lulus_verifikasi): ?>
                    <a href="<?= base_url('ppdb/cetak_kartu') ?>" target="_blank" class="btn btn-success px-3 py-2 rounded-pill fw-bold" style="background:#059669; border-color:#059669; font-size: 13.5px;">
                        <i class="bi bi-printer me-1"></i> Cetak Kartu Tes
                    </a>
                <?php else: ?>
                    <button type="button" class="btn btn-light border text-muted px-3 py-2 rounded-pill fw-bold" style="font-size: 12px;" onclick="alert('Kartu Ujian Seleksi belum dapat dicetak.\nKartu hanya dapat dicetak setelah dokumen diverifikasi dan dinyatakan LULUS VERIFIKASI oleh Panitia PMB.');">
                        <i class="bi bi-lock-fill me-1"></i> Kartu Tes (Terkunci)
                    </button>
                <?php endif; ?>
                <a href="<?= base_url('ppdb/download_pdf') ?>" class="btn btn-primary px-3 py-2 rounded-pill fw-bold" style="font-size: 13.5px;">
                    <i class="bi bi-file-earmark-pdf me-1"></i> Bukti PDF
                </a>
            </div>
        </div>

        <!-- 1. IDENTITAS POKOK & SELEKSI -->
        <div class="card border-0 shadow-sm rounded-4 p-4 mb-4" style="background: #ffffff; border: 1.5px solid var(--border-subtle) !important;">
            <div class="d-flex align-items-center gap-2 mb-3 pb-2 border-bottom">
                <i class="bi bi-patch-check-fill text-success fs-5"></i>
                <h5 class="fw-bold mb-0 text-dark">1. Data Pendaftaran &amp; Status Seleksi</h5>
            </div>
            <div class="row g-3">
                <div class="col-md-4 col-6">
                    <small class="text-muted d-block">Nomor Pendaftaran</small>
                    <strong class="text-dark"><?= htmlspecialchars($siswa->no_pendaftaran ?? '-') ?></strong>
                </div>
                <div class="col-md-4 col-6">
                    <small class="text-muted d-block">NISN (Username)</small>
                    <strong class="text-dark"><?= htmlspecialchars($siswa->nisn ?? '-') ?></strong>
                </div>
                <div class="col-md-4 col-12">
                    <small class="text-muted d-block">Status Seleksi</small>
                    <span class="badge bg-success px-3 py-1 rounded-pill"><?= htmlspecialchars($siswa->status ?? '-') ?></span>
                </div>
                <div class="col-md-4 col-6">
                    <small class="text-muted d-block">Jalur Pendaftaran</small>
                    <strong class="text-success"><?= htmlspecialchars($siswa->jalur_pendaftaran ?? 'Reguler') ?></strong>
                </div>
                <div class="col-md-4 col-6">
                    <small class="text-muted d-block">Asal Sekolah</small>
                    <strong class="text-dark"><?= htmlspecialchars($siswa->asal_sekolah ?? '-') ?></strong>
                </div>
                <div class="col-md-4 col-12">
                    <small class="text-muted d-block">No. Peserta Ujian Seleksi</small>
                    <strong class="text-primary font-monospace"><?= htmlspecialchars($siswa->no_peserta_tes ?? '-') ?></strong>
                </div>
            </div>
        </div>

        <!-- 2. IDENTITAS DIRI SISWA -->
        <div class="card border-0 shadow-sm rounded-4 p-4 mb-4" style="background: #ffffff; border: 1.5px solid var(--border-subtle) !important;">
            <div class="d-flex align-items-center gap-2 mb-3 pb-2 border-bottom">
                <i class="bi bi-person-lines-fill text-success fs-5"></i>
                <h5 class="fw-bold mb-0 text-dark">2. Identitas Calon Siswa</h5>
            </div>
            <div class="row g-3">
                <div class="col-md-6">
                    <small class="text-muted d-block">Nama Lengkap</small>
                    <strong class="text-dark"><?= htmlspecialchars($siswa->nama_lengkap ?? '-') ?></strong>
                </div>
                <div class="col-md-6">
                    <small class="text-muted d-block">Tempat, Tanggal Lahir</small>
                    <strong class="text-dark"><?= htmlspecialchars($siswa->tempat_lahir ?? '-') ?>, <?= !empty($siswa->tanggal_lahir) ? date('d-m-Y', strtotime($siswa->tanggal_lahir)) : '-' ?></strong>
                </div>
                <div class="col-md-4 col-6">
                    <small class="text-muted d-block">Jenis Kelamin</small>
                    <strong class="text-dark"><?= ($siswa->jk ?? '') == 'L' ? 'Laki-laki' : 'Perempuan' ?></strong>
                </div>
                <div class="col-md-4 col-6">
                    <small class="text-muted d-block">Agama</small>
                    <strong class="text-dark"><?= htmlspecialchars($siswa->agama ?? '-') ?></strong>
                </div>
                <div class="col-md-4 col-6">
                    <small class="text-muted d-block">NIK Siswa</small>
                    <strong class="text-dark"><?= htmlspecialchars($siswa->nik ?? '-') ?></strong>
                </div>
                <div class="col-md-4 col-6">
                    <small class="text-muted d-block">Nomor Kartu Keluarga (KK)</small>
                    <strong class="text-dark"><?= htmlspecialchars($siswa->no_kk ?? '-') ?></strong>
                </div>
                <div class="col-md-4 col-6">
                    <small class="text-muted d-block">Anak Ke- / Jml Saudara</small>
                    <strong class="text-dark"><?= htmlspecialchars($siswa->anak_ke ?? '-') ?> dari <?= htmlspecialchars($siswa->jumlah_saudara ?? '-') ?> bersaudara</strong>
                </div>
                <div class="col-md-4 col-6">
                    <small class="text-muted d-block">No. WhatsApp / HP</small>
                    <strong class="text-dark"><?= htmlspecialchars($siswa->no_hp ?? '-') ?></strong>
                </div>
                <div class="col-md-4 col-12">
                    <small class="text-muted d-block">Email</small>
                    <strong class="text-dark"><?= htmlspecialchars($siswa->email ?? '-') ?></strong>
                </div>
                <div class="col-12">
                    <small class="text-muted d-block">Alamat Lengkap</small>
                    <strong class="text-dark"><?= htmlspecialchars($siswa->alamat ?? '-') ?> (RT <?= htmlspecialchars($siswa->rt ?? '-') ?> / RW <?= htmlspecialchars($siswa->rw ?? '-') ?>), Desa <?= htmlspecialchars($siswa->desa ?? '-') ?>, Kec. <?= htmlspecialchars($siswa->kecamatan ?? '-') ?>, <?= htmlspecialchars($siswa->kabupaten ?? '-') ?>, <?= htmlspecialchars($siswa->provinsi ?? '-') ?> <?= htmlspecialchars($siswa->kode_pos ?? '') ?></strong>
                </div>
            </div>
        </div>

        <!-- 3. DATA ORANG TUA / WALI -->
        <div class="card border-0 shadow-sm rounded-4 p-4 mb-4" style="background: #ffffff; border: 1.5px solid var(--border-subtle) !important;">
            <div class="d-flex align-items-center gap-2 mb-3 pb-2 border-bottom">
                <i class="bi bi-people-fill text-success fs-5"></i>
                <h5 class="fw-bold mb-0 text-dark">3. Data Orang Tua / Wali</h5>
            </div>
            <div class="row g-3">
                <div class="col-md-6">
                    <small class="text-muted d-block">Nama Ayah / Wali</small>
                    <strong class="text-dark"><?= htmlspecialchars($siswa->nama_ayah ?? ($siswa->nama_ortu ?? '-')) ?></strong>
                </div>
                <div class="col-md-6">
                    <small class="text-muted d-block">Pekerjaan Ayah</small>
                    <strong class="text-dark"><?= htmlspecialchars($siswa->pekerjaan_ayah ?? '-') ?></strong>
                </div>
                <div class="col-md-6">
                    <small class="text-muted d-block">Nama Ibu Kandung</small>
                    <strong class="text-dark"><?= htmlspecialchars($siswa->nama_ibu ?? '-') ?></strong>
                </div>
                <div class="col-md-6">
                    <small class="text-muted d-block">Pekerjaan Ibu</small>
                    <strong class="text-dark"><?= htmlspecialchars($siswa->pekerjaan_ibu ?? '-') ?></strong>
                </div>
                <div class="col-md-6">
                    <small class="text-muted d-block">Penghasilan Orang Tua</small>
                    <strong class="text-dark"><?= htmlspecialchars($siswa->penghasilan_ortu ?? '-') ?></strong>
                </div>
            </div>
        </div>

        <!-- 4. BERKAS TERUNGGAH -->
        <div class="card border-0 shadow-sm rounded-4 p-4 mb-4" style="background: #ffffff; border: 1.5px solid var(--border-subtle) !important;">
            <div class="d-flex justify-content-between align-items-center mb-3 pb-2 border-bottom">
                <div class="d-flex align-items-center gap-2">
                    <i class="bi bi-folder-check text-success fs-5"></i>
                    <h5 class="fw-bold mb-0 text-dark">4. Status Berkas &amp; Dokumen</h5>
                </div>
                <a href="<?= base_url('ppdb/upload') ?>" class="btn btn-sm btn-outline-success rounded-pill px-3">
                    <i class="bi bi-cloud-arrow-up me-1"></i> Update Berkas
                </a>
            </div>
            <div class="row g-3">
                <?php foreach($file_items as $f_key => $f_title): ?>
                    <?php 
                        $f_exists = !empty($siswa->$f_key);
                        $f_url = $f_exists ? base_url('uploads/temp/ppdb/'.$siswa->$f_key) : '';
                    ?>
                    <div class="col-md-6">
                        <div class="p-3 rounded-3 border d-flex justify-content-between align-items-center" style="background: <?= $f_exists ? '#f0fdf4' : '#f8fafc' ?>;">
                            <div>
                                <small class="text-muted d-block" style="font-size:11px;"><?= $f_title ?></small>
                                <?php if($f_exists): ?>
                                    <strong class="text-success small"><i class="bi bi-check-circle-fill me-1"></i> Terunggah</strong>
                                <?php else: ?>
                                    <span class="text-muted small"><i class="bi bi-dash-circle me-1"></i> Belum ada</span>
                                <?php endif; ?>
                            </div>
                            <?php if($f_exists): ?>
                                <a href="<?= $f_url ?>" target="_blank" class="btn btn-sm btn-light border rounded-pill px-3 py-1 fw-bold" style="font-size:11px;">
                                    <i class="bi bi-eye me-1"></i> Lihat
                                </a>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>

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
    <a href="<?= base_url('ppdb/upload') ?>">
        <i class="bi bi-cloud-arrow-up-fill"></i>
        <span>Berkas</span>
    </a>
    <a href="<?= base_url('ppdb/cetak_kartu') ?>" target="_blank">
        <i class="bi bi-printer-fill"></i>
        <span>Kartu Tes</span>
    </a>
    <a href="<?= base_url('ppdb/detail') ?>" class="active">
        <i class="bi bi-person-badge"></i>
        <span>Akun</span>
    </a>
</nav>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>