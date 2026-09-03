<?php
if(!function_exists('ppdb_edit_e')){
    function ppdb_edit_e($text){
        return htmlspecialchars((string)$text, ENT_QUOTES, 'UTF-8');
    }
}
if(!function_exists('ppdb_edit_selected')){
    function ppdb_edit_selected($value, $current){
        return (string)$value === (string)$current ? 'selected' : '';
    }
}
$nama_lengkap   = $siswa->nama_lengkap ?? 'Peserta PMB';
$no_pendaftaran = $siswa->no_pendaftaran ?? '-';

$foto_path = !empty($siswa->foto) ? FCPATH.'uploads/temp/ppdb/'.$siswa->foto : '';
$foto_url  = !empty($siswa->foto) ? base_url('uploads/temp/ppdb/'.$siswa->foto) : '';
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <title>Edit Data Pendaftaran | PMB MAN 3 Banjar</title>

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
            <strong>Edit Data Pendaftaran</strong>
            <small><?= htmlspecialchars($nama_lengkap) ?></small>
        </div>
    </div>
    <a href="<?= base_url('ppdb/detail') ?>" class="btn btn-sm btn-outline-secondary px-3 py-1 rounded-pill" style="font-size: 11.5px; font-weight: 700;">
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

        <div class="card border-0 shadow-sm rounded-4 p-4 mb-4" style="background: #ffffff; border: 1.5px solid var(--border-subtle) !important;">
            <div class="d-flex align-items-center justify-content-between mb-3 pb-2 border-bottom">
                <div>
                    <h4 class="fw-bold mb-1 text-dark"><i class="bi bi-pencil-square text-success me-2"></i> Edit Data Pendaftaran</h4>
                    <p class="text-muted small mb-0">Perbarui data pendaftaran Anda jika terdapat kesalahan penulisan.</p>
                </div>
                <a href="<?= base_url('ppdb/detail') ?>" class="btn btn-outline-secondary btn-sm rounded-pill d-none d-md-inline-block">
                    <i class="bi bi-arrow-left"></i> Kembali
                </a>
            </div>

            <form method="post" action="<?= base_url('ppdb/update_detail') ?>">

                <!-- Seksi 1: Data Pokok Pendaftaran -->
                <div class="p-3 rounded-3 bg-light border-start border-4 border-success mb-3">
                    <span class="fw-bold small text-success"><i class="bi bi-card-heading me-1"></i> Data Pokok &amp; Identitas Calon Siswa</span>
                </div>

                <div class="row g-3 mb-4">
                    <div class="col-md-4">
                        <label class="form-label text-muted small fw-bold">Nomor Pendaftaran</label>
                        <input type="text" class="form-control bg-light" value="<?= htmlspecialchars($siswa->no_pendaftaran ?? '-') ?>" disabled>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label text-muted small fw-bold">NISN</label>
                        <input type="text" class="form-control bg-light" value="<?= htmlspecialchars($siswa->nisn ?? '-') ?>" disabled>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label text-muted small fw-bold">Status Pendaftaran</label>
                        <input type="text" class="form-control bg-light" value="<?= htmlspecialchars($siswa->status ?? '-') ?>" disabled>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label text-muted small fw-bold">Jalur Pendaftaran <span class="text-danger">*</span></label>
                        <select name="jalur_pendaftaran" class="form-select" required>
                            <option value="Reguler" <?= ppdb_edit_selected('Reguler', $siswa->jalur_pendaftaran ?? '') ?>>Jalur Reguler / Umum</option>
                            <option value="Prestasi" <?= ppdb_edit_selected('Prestasi', $siswa->jalur_pendaftaran ?? '') ?>>Jalur Prestasi (Akademik / Non-Akademik)</option>
                            <option value="Tahfidz" <?= ppdb_edit_selected('Tahfidz', $siswa->jalur_pendaftaran ?? '') ?>>Jalur Tahfidz Al-Qur'an</option>
                            <option value="Afirmasi" <?= ppdb_edit_selected('Afirmasi', $siswa->jalur_pendaftaran ?? '') ?>>Jalur Afirmasi (KIP / PKH / KKS)</option>
                        </select>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label text-muted small fw-bold">Asal Sekolah (SMP / MTs) <span class="text-danger">*</span></label>
                        <input type="text" name="asal_sekolah" class="form-control" value="<?= htmlspecialchars($siswa->asal_sekolah ?? '') ?>" required>
                    </div>

                    <div class="col-md-12">
                        <label class="form-label text-muted small fw-bold">Nama Lengkap Calon Siswa <span class="text-danger">*</span></label>
                        <input type="text" name="nama_lengkap" class="form-control" value="<?= htmlspecialchars($siswa->nama_lengkap ?? '') ?>" required minlength="3">
                    </div>

                    <div class="col-md-6">
                        <label class="form-label text-muted small fw-bold">Tempat Lahir <span class="text-danger">*</span></label>
                        <input type="text" name="tempat_lahir" class="form-control" value="<?= htmlspecialchars($siswa->tempat_lahir ?? '') ?>" required>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label text-muted small fw-bold">Tanggal Lahir <span class="text-danger">*</span></label>
                        <input type="date" name="tanggal_lahir" class="form-control" value="<?= htmlspecialchars($siswa->tanggal_lahir ?? '') ?>" required>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label text-muted small fw-bold">Jenis Kelamin <span class="text-danger">*</span></label>
                        <select name="jk" class="form-select" required>
                            <option value="L" <?= ppdb_edit_selected('L', $siswa->jk ?? '') ?>>Laki-laki</option>
                            <option value="P" <?= ppdb_edit_selected('P', $siswa->jk ?? '') ?>>Perempuan</option>
                        </select>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label text-muted small fw-bold">Agama <span class="text-danger">*</span></label>
                        <select name="agama" class="form-select" required>
                            <option value="Islam" <?= ppdb_edit_selected('Islam', $siswa->agama ?? 'Islam') ?>>Islam</option>
                            <option value="Kristen" <?= ppdb_edit_selected('Kristen', $siswa->agama ?? '') ?>>Kristen</option>
                            <option value="Katolik" <?= ppdb_edit_selected('Katolik', $siswa->agama ?? '') ?>>Katolik</option>
                            <option value="Hindu" <?= ppdb_edit_selected('Hindu', $siswa->agama ?? '') ?>>Hindu</option>
                            <option value="Budha" <?= ppdb_edit_selected('Budha', $siswa->agama ?? '') ?>>Budha</option>
                        </select>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label text-muted small fw-bold">NIK (16 Digit)</label>
                        <input type="text" name="nik" class="form-control" value="<?= htmlspecialchars($siswa->nik ?? '') ?>" maxlength="16">
                    </div>

                    <div class="col-md-6">
                        <label class="form-label text-muted small fw-bold">Nomor Kartu Keluarga (KK)</label>
                        <input type="text" name="no_kk" class="form-control" value="<?= htmlspecialchars($siswa->no_kk ?? '') ?>" maxlength="16">
                    </div>

                    <div class="col-md-6">
                        <label class="form-label text-muted small fw-bold">Anak Ke-</label>
                        <input type="number" name="anak_ke" class="form-control" value="<?= htmlspecialchars($siswa->anak_ke ?? '') ?>" min="1">
                    </div>

                    <div class="col-md-6">
                        <label class="form-label text-muted small fw-bold">Jumlah Saudara</label>
                        <input type="number" name="jumlah_saudara" class="form-control" value="<?= htmlspecialchars($siswa->jumlah_saudara ?? '') ?>" min="0">
                    </div>
                </div>

                <!-- Seksi 2: Kontak & Alamat -->
                <div class="p-3 rounded-3 bg-light border-start border-4 border-success mb-3">
                    <span class="fw-bold small text-success"><i class="bi bi-geo-alt me-1"></i> Kontak &amp; Alamat Tempat Tinggal</span>
                </div>

                <div class="row g-3 mb-4">
                    <div class="col-md-6">
                        <label class="form-label text-muted small fw-bold">Nomor WhatsApp / HP Aktif <span class="text-danger">*</span></label>
                        <input type="text" name="no_hp" class="form-control" value="<?= htmlspecialchars($siswa->no_hp ?? '') ?>" required maxlength="15">
                    </div>

                    <div class="col-md-6">
                        <label class="form-label text-muted small fw-bold">Alamat Email (Opsional)</label>
                        <input type="email" name="email" class="form-control" value="<?= htmlspecialchars($siswa->email ?? '') ?>" placeholder="contoh: siswa@gmail.com">
                    </div>

                    <div class="col-12">
                        <label class="form-label text-muted small fw-bold">Alamat Lengkap</label>
                        <textarea name="alamat" class="form-control" rows="2"><?= htmlspecialchars($siswa->alamat ?? '') ?></textarea>
                    </div>

                    <div class="col-md-3 col-6">
                        <label class="form-label text-muted small fw-bold">RT</label>
                        <input type="text" name="rt" class="form-control" value="<?= htmlspecialchars($siswa->rt ?? '') ?>">
                    </div>

                    <div class="col-md-3 col-6">
                        <label class="form-label text-muted small fw-bold">RW</label>
                        <input type="text" name="rw" class="form-control" value="<?= htmlspecialchars($siswa->rw ?? '') ?>">
                    </div>

                    <div class="col-md-6">
                        <label class="form-label text-muted small fw-bold">Desa / Kelurahan</label>
                        <input type="text" name="desa" class="form-control" value="<?= htmlspecialchars($siswa->desa ?? '') ?>">
                    </div>

                    <div class="col-md-4">
                        <label class="form-label text-muted small fw-bold">Kecamatan</label>
                        <input type="text" name="kecamatan" class="form-control" value="<?= htmlspecialchars($siswa->kecamatan ?? '') ?>">
                    </div>

                    <div class="col-md-4">
                        <label class="form-label text-muted small fw-bold">Kabupaten / Kota</label>
                        <input type="text" name="kabupaten" class="form-control" value="<?= htmlspecialchars($siswa->kabupaten ?? '') ?>">
                    </div>

                    <div class="col-md-4">
                        <label class="form-label text-muted small fw-bold">Provinsi</label>
                        <input type="text" name="provinsi" class="form-control" value="<?= htmlspecialchars($siswa->provinsi ?? '') ?>">
                    </div>

                    <div class="col-md-4">
                        <label class="form-label text-muted small fw-bold">Kode Pos</label>
                        <input type="text" name="kode_pos" class="form-control" value="<?= htmlspecialchars($siswa->kode_pos ?? '') ?>">
                    </div>
                </div>

                <!-- Seksi 3: Data Orang Tua -->
                <div class="p-3 rounded-3 bg-light border-start border-4 border-success mb-3">
                    <span class="fw-bold small text-success"><i class="bi bi-people me-1"></i> Data Orang Tua / Wali</span>
                </div>

                <div class="row g-3 mb-4">
                    <div class="col-md-6">
                        <label class="form-label text-muted small fw-bold">Nama Ayah / Wali <span class="text-danger">*</span></label>
                        <input type="text" name="nama_ayah" class="form-control" value="<?= htmlspecialchars($siswa->nama_ayah ?? ($siswa->nama_ortu ?? '')) ?>" required>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label text-muted small fw-bold">Pekerjaan Ayah</label>
                        <input type="text" name="pekerjaan_ayah" class="form-control" value="<?= htmlspecialchars($siswa->pekerjaan_ayah ?? '') ?>">
                    </div>

                    <div class="col-md-6">
                        <label class="form-label text-muted small fw-bold">Nama Ibu</label>
                        <input type="text" name="nama_ibu" class="form-control" value="<?= htmlspecialchars($siswa->nama_ibu ?? '') ?>">
                    </div>

                    <div class="col-md-6">
                        <label class="form-label text-muted small fw-bold">Pekerjaan Ibu</label>
                        <input type="text" name="pekerjaan_ibu" class="form-control" value="<?= htmlspecialchars($siswa->pekerjaan_ibu ?? '') ?>">
                    </div>

                    <div class="col-md-6">
                        <label class="form-label text-muted small fw-bold">Penghasilan Orang Tua</label>
                        <select name="penghasilan_ortu" class="form-select">
                            <option value="">-- Pilih Penghasilan --</option>
                            <option value="< 1 Juta" <?= ppdb_edit_selected('< 1 Juta', $siswa->penghasilan_ortu ?? '') ?>>&lt; Rp 1.000.000</option>
                            <option value="1-3 Juta" <?= ppdb_edit_selected('1-3 Juta', $siswa->penghasilan_ortu ?? '') ?>>Rp 1.000.000 - Rp 3.000.000</option>
                            <option value="3-5 Juta" <?= ppdb_edit_selected('3-5 Juta', $siswa->penghasilan_ortu ?? '') ?>>Rp 3.000.000 - Rp 5.000.000</option>
                            <option value="> 5 Juta" <?= ppdb_edit_selected('> 5 Juta', $siswa->penghasilan_ortu ?? '') ?>>&gt; Rp 5.000.000</option>
                        </select>
                    </div>
                </div>

                <!-- Tombol Aksi -->
                <div class="d-flex flex-column flex-md-row justify-content-end gap-2 pt-3 border-top">
                    <a href="<?= base_url('ppdb/detail') ?>" class="btn btn-outline-secondary px-4 py-2 rounded-pill fw-bold" style="font-size: 14px;">
                        Batal
                    </a>
                    <button type="submit" class="btn btn-success px-4 py-2 rounded-pill fw-bold shadow-sm" style="background:#059669; border-color:#059669; font-size: 14px;">
                        <i class="bi bi-check2-circle me-1"></i> Simpan Perubahan Data
                    </button>
                </div>

            </form>

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