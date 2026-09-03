<?php
if(!function_exists('ppdb_bio_e')){
    function ppdb_bio_e($text){
        return htmlspecialchars((string)$text, ENT_QUOTES, 'UTF-8');
    }
}

if(!function_exists('ppdb_bio_selected')){
    function ppdb_bio_selected($value, $current){
        return (string)$value === (string)$current ? 'selected' : '';
    }
}

$nama_lengkap   = $siswa->nama_lengkap ?? 'Peserta ' . htmlspecialchars($nama_ppdb ?? 'PPDB');
$no_pendaftaran = $siswa->no_pendaftaran ?? '-';

$foto_path = !empty($siswa->foto) ? FCPATH.'uploads/temp/ppdb/'.$siswa->foto : '';
$foto_url  = !empty($siswa->foto) ? base_url('uploads/temp/ppdb/'.$siswa->foto) : '';
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <title>Lengkapi Biodata Diri | PMB MAN 3 Banjar</title>

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
            <strong>Lengkapi Biodata Diri</strong>
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
                <a href="<?= base_url('ppdb/biodata') ?>" class="active">
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

        <!-- Form Lengkapi Biodata -->
        <form method="post" action="<?= base_url('ppdb/save_biodata') ?>">

            <!-- Seksi 1: Data Identitas Diri -->
            <div class="card border-0 shadow-sm rounded-4 p-4 mb-4" style="background: #ffffff; border: 1.5px solid var(--border-color) !important;">
                <div class="d-flex align-items-center gap-2 mb-3 pb-2 border-bottom">
                    <i class="bi bi-person-badge-fill text-success fs-5"></i>
                    <h5 class="fw-bold mb-0 text-dark">1. Identitas Pokok Calon Siswa</h5>
                </div>

                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label text-muted small fw-bold">Nomor Pendaftaran (Otomatis)</label>
                        <input type="text" class="form-control bg-light" value="<?= htmlspecialchars($siswa->no_pendaftaran ?? '-') ?>" disabled>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label text-muted small fw-bold">NISN (Username Login)</label>
                        <input type="text" class="form-control bg-light" value="<?= htmlspecialchars($siswa->nisn ?? '-') ?>" disabled>
                    </div>

                    <div class="col-md-12">
                        <label class="form-label text-muted small fw-bold">Nama Lengkap Siswa <span class="text-danger">*</span></label>
                        <input type="text" class="form-control bg-light" value="<?= htmlspecialchars($siswa->nama_lengkap ?? '') ?>" disabled>
                        <div class="form-text text-muted" style="font-size: 11px;">Nama sesuai ijazah/akta kelahiran (diedit via menu Detail jika ada kekeliruan).</div>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label text-muted small fw-bold">NIK (Nomor Induk Kependudukan) Siswa <span class="text-danger">*</span></label>
                        <input type="text" name="nik" class="form-control" placeholder="16 digit angka NIK di Kartu Keluarga" value="<?= htmlspecialchars($siswa->nik ?? '') ?>" required maxlength="16" pattern="[0-9]{16}">
                    </div>

                    <div class="col-md-6">
                        <label class="form-label text-muted small fw-bold">Nomor Kartu Keluarga (KK) <span class="text-danger">*</span></label>
                        <input type="text" name="no_kk" class="form-control" placeholder="16 digit angka Nomor KK" value="<?= htmlspecialchars($siswa->no_kk ?? '') ?>" required maxlength="16" pattern="[0-9]{16}">
                    </div>

                    <div class="col-md-4">
                        <label class="form-label text-muted small fw-bold">Agama <span class="text-danger">*</span></label>
                        <select name="agama" class="form-select" required>
                            <option value="Islam" <?= ppdb_bio_selected('Islam', $siswa->agama ?? 'Islam') ?>>Islam</option>
                            <option value="Kristen" <?= ppdb_bio_selected('Kristen', $siswa->agama ?? '') ?>>Kristen</option>
                            <option value="Katolik" <?= ppdb_bio_selected('Katolik', $siswa->agama ?? '') ?>>Katolik</option>
                            <option value="Hindu" <?= ppdb_bio_selected('Hindu', $siswa->agama ?? '') ?>>Hindu</option>
                            <option value="Budha" <?= ppdb_bio_selected('Budha', $siswa->agama ?? '') ?>>Budha</option>
                        </select>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label text-muted small fw-bold">Anak Ke- <span class="text-danger">*</span></label>
                        <input type="number" name="anak_ke" class="form-control" placeholder="Contoh: 1" value="<?= htmlspecialchars($siswa->anak_ke ?? '') ?>" required min="1">
                    </div>

                    <div class="col-md-4">
                        <label class="form-label text-muted small fw-bold">Jumlah Saudara Kandung <span class="text-danger">*</span></label>
                        <input type="number" name="jumlah_saudara" class="form-control" placeholder="Contoh: 2" value="<?= htmlspecialchars($siswa->jumlah_saudara ?? '') ?>" required min="0">
                    </div>
                </div>
            </div>

            <!-- Seksi 2: Alamat Tempat Tinggal -->
            <div class="card border-0 shadow-sm rounded-4 p-4 mb-4" style="background: #ffffff; border: 1.5px solid var(--border-color) !important;">
                <div class="d-flex align-items-center gap-2 mb-3 pb-2 border-bottom">
                    <i class="bi bi-geo-alt-fill text-success fs-5"></i>
                    <h5 class="fw-bold mb-0 text-dark">2. Alamat Tempat Tinggal</h5>
                </div>

                <div class="row g-3">
                    <div class="col-12">
                        <label class="form-label text-muted small fw-bold">Alamat Lengkap (Jalan / Gang / Komplek / No. Rumah) <span class="text-danger">*</span></label>
                        <textarea name="alamat" class="form-control" rows="2" placeholder="Contoh: Jl. Pangeran Hidayatullah No. 12" required><?= htmlspecialchars($siswa->alamat ?? '') ?></textarea>
                    </div>

                    <div class="col-md-3 col-6">
                        <label class="form-label text-muted small fw-bold">RT <span class="text-danger">*</span></label>
                        <input type="text" name="rt" class="form-control" placeholder="Contoh: 03" value="<?= htmlspecialchars($siswa->rt ?? '') ?>" required>
                    </div>

                    <div class="col-md-3 col-6">
                        <label class="form-label text-muted small fw-bold">RW <span class="text-danger">*</span></label>
                        <input type="text" name="rw" class="form-control" placeholder="Contoh: 01" value="<?= htmlspecialchars($siswa->rw ?? '') ?>" required>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label text-muted small fw-bold">Desa / Kelurahan <span class="text-danger">*</span></label>
                        <input type="text" name="desa" class="form-control" placeholder="Contoh: Keraton / Jawa" value="<?= htmlspecialchars($siswa->desa ?? '') ?>" required>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label text-muted small fw-bold">Kecamatan <span class="text-danger">*</span></label>
                        <input type="text" name="kecamatan" class="form-control" placeholder="Contoh: Martapura" value="<?= htmlspecialchars($siswa->kecamatan ?? '') ?>" required>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label text-muted small fw-bold">Kabupaten / Kota <span class="text-danger">*</span></label>
                        <input type="text" name="kabupaten" class="form-control" placeholder="Contoh: Kabupaten Banjar" value="<?= htmlspecialchars($siswa->kabupaten ?? '') ?>" required>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label text-muted small fw-bold">Provinsi <span class="text-danger">*</span></label>
                        <input type="text" name="provinsi" class="form-control" placeholder="Contoh: Kalimantan Selatan" value="<?= htmlspecialchars($siswa->provinsi ?? 'Kalimantan Selatan') ?>" required>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label text-muted small fw-bold">Kode Pos</label>
                        <input type="text" name="kode_pos" class="form-control" placeholder="Contoh: 70611" value="<?= htmlspecialchars($siswa->kode_pos ?? '') ?>">
                    </div>
                </div>
            </div>

            <!-- Seksi 3: Data Orang Tua / Wali -->
            <div class="card border-0 shadow-sm rounded-4 p-4 mb-4" style="background: #ffffff; border: 1.5px solid var(--border-color) !important;">
                <div class="d-flex align-items-center gap-2 mb-3 pb-2 border-bottom">
                    <i class="bi bi-people-fill text-success fs-5"></i>
                    <h5 class="fw-bold mb-0 text-dark">3. Data Orang Tua / Wali</h5>
                </div>

                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label text-muted small fw-bold">Nama Ayah Kandung / Wali <span class="text-danger">*</span></label>
                        <input type="text" name="nama_ayah" class="form-control" placeholder="Nama lengkap ayah" value="<?= htmlspecialchars($siswa->nama_ayah ?? ($siswa->nama_ortu ?? '')) ?>" required>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label text-muted small fw-bold">Pekerjaan Ayah <span class="text-danger">*</span></label>
                        <input type="text" name="pekerjaan_ayah" class="form-control" placeholder="Contoh: PNS / Wiraswasta / Petani / Karyawan" value="<?= htmlspecialchars($siswa->pekerjaan_ayah ?? '') ?>" required>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label text-muted small fw-bold">Nama Ibu Kandung <span class="text-danger">*</span></label>
                        <input type="text" name="nama_ibu" class="form-control" placeholder="Nama lengkap ibu" value="<?= htmlspecialchars($siswa->nama_ibu ?? '') ?>" required>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label text-muted small fw-bold">Pekerjaan Ibu <span class="text-danger">*</span></label>
                        <input type="text" name="pekerjaan_ibu" class="form-control" placeholder="Contoh: Ibu Rumah Tangga / Guru / Pedagang" value="<?= htmlspecialchars($siswa->pekerjaan_ibu ?? '') ?>" required>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label text-muted small fw-bold">Rata-rata Penghasilan Orang Tua / Bulan <span class="text-danger">*</span></label>
                        <select name="penghasilan_ortu" class="form-select" required>
                            <option value="">-- Pilih Range Penghasilan --</option>
                            <option value="< 1 Juta" <?= ppdb_bio_selected('< 1 Juta', $siswa->penghasilan_ortu ?? '') ?>>&lt; Rp 1.000.000</option>
                            <option value="1-3 Juta" <?= ppdb_bio_selected('1-3 Juta', $siswa->penghasilan_ortu ?? '') ?>>Rp 1.000.000 - Rp 3.000.000</option>
                            <option value="3-5 Juta" <?= ppdb_bio_selected('3-5 Juta', $siswa->penghasilan_ortu ?? '') ?>>Rp 3.000.000 - Rp 5.000.000</option>
                            <option value="> 5 Juta" <?= ppdb_bio_selected('> 5 Juta', $siswa->penghasilan_ortu ?? '') ?>>&gt; Rp 5.000.000</option>
                        </select>
                    </div>
                </div>
            </div>

            <!-- Tombol Simpan -->
            <div class="card border-0 shadow-lg rounded-4 p-3 bg-white sticky-bottom" style="z-index: 50; border: 1.5px solid #cbd5e1 !important;">
                <div class="d-flex flex-column flex-md-row justify-content-between align-items-center gap-2">
                    <small class="text-muted"><i class="bi bi-shield-check me-1"></i> Data yang Anda simpan akan diverifikasi oleh panitia PMB.</small>
                    <div class="d-flex gap-2 w-100 w-md-auto">
                        <a href="<?= base_url('ppdb/dashboard') ?>" class="btn btn-outline-secondary px-4 py-2 rounded-pill fw-bold" style="font-size: 14px;">
                            Batal
                        </a>
                        <button type="submit" class="btn btn-success px-4 py-2 rounded-pill fw-bold shadow-sm flex-grow-1 flex-md-grow-0" style="background:#059669; border-color:#059669; font-size: 14px;">
                            <i class="bi bi-check2-circle me-1"></i> Simpan Biodata &amp; Lanjut Upload Berkas
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
    <a href="<?= base_url('ppdb/biodata') ?>" class="active">
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