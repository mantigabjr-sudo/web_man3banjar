<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; background: #f1f5f9; color: #334155; }
        .form-card { background: #ffffff; border-radius: 24px; box-shadow: 0 10px 30px rgba(0,0,0,0.06); }
        .section-title { font-weight: 800; color: #065f46; display: flex; align-items: center; gap: 8px; border-bottom: 2px solid #ecfdf5; padding-bottom: 8px; margin-bottom: 20px; }
    </style>
</head>
<body class="py-4">

<div class="container">
    <div class="row justify-content-center">
        <div class="col-lg-9">
            
            <div class="text-center mb-4">
                <a href="<?= base_url('ppdb') ?>" class="btn btn-outline-secondary btn-sm mb-3 rounded-pill px-3">
                    <i class="bi bi-arrow-left me-1"></i> Kembali ke Informasi PPDB
                </a>
                <h2 class="fw-extrabold text-dark">Formulir Pendaftaran Siswa Baru</h2>
                <p class="text-muted"><?= htmlspecialchars($setting->nama_sekolah) ?> &bull; Tahun Pelajaran <?= htmlspecialchars($ppdb_setting->tahun_ajaran) ?></p>
            </div>

            <?php if($this->session->flashdata('error')): ?>
                <div class="alert alert-danger py-3 rounded-3 shadow-sm mb-4">
                    <i class="bi bi-exclamation-triangle-fill me-2"></i> <?= $this->session->flashdata('error') ?>
                </div>
            <?php endif; ?>

            <div class="card form-card p-4 p-md-5">
                <form action="<?= base_url('ppdb/daftar') ?>" method="POST" enctype="multipart/form-data">
                    
                    <!-- 1. DATA PRIBADI -->
                    <div class="section-title">
                        <i class="bi bi-person-circle fs-4"></i> A. Data Pribadi Calon Siswa
                    </div>

                    <div class="row g-3 mb-4">
                        <div class="col-md-8">
                            <label class="form-label fw-bold small">Nama Lengkap (Sesuai Ijazah/Akta) <span class="text-danger">*</span></label>
                            <input type="text" name="nama_lengkap" class="form-control" placeholder="Contoh: Muhammad Rizki" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-bold small">NISN (10 Digit) <span class="text-danger">*</span></label>
                            <input type="text" name="nisn" class="form-control" maxlength="10" placeholder="0012345678" required>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label fw-bold small">NIK / No. KTP Calon Siswa</label>
                            <input type="text" name="nik" class="form-control" maxlength="16" placeholder="63030xxxxxx">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-bold small">Jenis Kelamin <span class="text-danger">*</span></label>
                            <select name="jenis_kelamin" class="form-select" required>
                                <option value="">-- Pilih --</option>
                                <option value="L">Laki-Laki</option>
                                <option value="P">Perempuan</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-bold small">Agama</label>
                            <input type="text" name="agama" class="form-control" value="Islam">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-bold small">Tempat Lahir <span class="text-danger">*</span></label>
                            <input type="text" name="tempat_lahir" class="form-control" placeholder="Contoh: Martapura" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold small">Tanggal Lahir <span class="text-danger">*</span></label>
                            <input type="date" name="tanggal_lahir" class="form-control" required>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-bold small">Nomor WhatsApp / HP Siswa</label>
                            <input type="text" name="no_hp_siswa" class="form-control" placeholder="0812xxxxxx">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold small">Alamat Tempat Tinggal</label>
                            <input type="text" name="alamat_siswa" class="form-control" placeholder="Jl. Desa, RT/RW, Kecamatan">
                        </div>
                    </div>

                    <!-- 2. DATA SEKOLAH ASAL -->
                    <div class="section-title">
                        <i class="bi bi-building fs-4"></i> B. Asal Sekolah (SMP / MTs)
                    </div>

                    <div class="row g-3 mb-4">
                        <div class="col-md-8">
                            <label class="form-label fw-bold small">Nama Asal Sekolah / Madrasah <span class="text-danger">*</span></label>
                            <input type="text" name="sekolah_asal" class="form-control" placeholder="Contoh: MTsN 1 Banjar / SMPN 2 Martapura" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-bold small">Nilai Rata-rata Rapor (SKL)</label>
                            <input type="number" step="0.01" name="nilai_rata_rata" class="form-control" placeholder="85.50">
                        </div>
                    </div>

                    <!-- 3. DATA ORANG TUA -->
                    <div class="section-title">
                        <i class="bi bi-people-fill fs-4"></i> C. Data Orang Tua / Wali
                    </div>

                    <div class="row g-3 mb-4">
                        <div class="col-md-6">
                            <label class="form-label fw-bold small">Nama Ayah Kandung</label>
                            <input type="text" name="nama_ayah" class="form-control" placeholder="Nama Ayah">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold small">Pekerjaan Ayah</label>
                            <input type="text" name="pekerjaan_ayah" class="form-control" placeholder="Pekerjaan Ayah">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-bold small">Nama Ibu Kandung</label>
                            <input type="text" name="nama_ibu" class="form-control" placeholder="Nama Ibu">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold small">Pekerjaan Ibu</label>
                            <input type="text" name="pekerjaan_ibu" class="form-control" placeholder="Pekerjaan Ibu">
                        </div>

                        <div class="col-md-12">
                            <label class="form-label fw-bold small">Nomor HP / WhatsApp Orang Tua <span class="text-danger">*</span></label>
                            <input type="text" name="no_hp_ortu" class="form-control" placeholder="08xxxxxxxxxx" required>
                        </div>
                    </div>

                    <!-- 4. UPLOAD DOKUMEN -->
                    <div class="section-title">
                        <i class="bi bi-cloud-arrow-up-fill fs-4"></i> D. Unggah Dokumen Berkas (PDF / Gambar)
                    </div>

                    <div class="row g-3 mb-5">
                        <div class="col-md-6">
                            <label class="form-label fw-bold small">Scan / Foto Kartu Keluarga (KK)</label>
                            <input type="file" name="berkas_kk" class="form-control" accept=".jpg,.jpeg,.png,.pdf">
                            <small class="text-muted" style="font-size: 11px;">Format JPG/PNG/PDF, Maks. 5MB</small>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold small">Scan / Foto Akta Kelahiran</label>
                            <input type="file" name="berkas_akta" class="form-control" accept=".jpg,.jpeg,.png,.pdf">
                            <small class="text-muted" style="font-size: 11px;">Format JPG/PNG/PDF, Maks. 5MB</small>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-bold small">Scan / Foto Surat Keterangan Lulus / Ijazah</label>
                            <input type="file" name="berkas_ijazah" class="form-control" accept=".jpg,.jpeg,.png,.pdf">
                            <small class="text-muted" style="font-size: 11px;">Format JPG/PNG/PDF, Maks. 5MB</small>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold small">Pas Foto Siswa (Terbaru)</label>
                            <input type="file" name="berkas_foto" class="form-control" accept=".jpg,.jpeg,.png">
                            <small class="text-muted" style="font-size: 11px;">Format JPG/PNG (Latar Merah/Biru)</small>
                        </div>
                    </div>

                    <div class="p-3 bg-light rounded-3 mb-4 text-center">
                        <div class="form-check d-inline-block text-start">
                            <input class="form-check-input" type="checkbox" id="setuju" required>
                            <label class="form-check-label small fw-bold" for="setuju">
                                Saya menyatakan bahwa seluruh data yang diisikan adalah benar dan dapat dipertanggungjawabkan.
                            </label>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-success btn-lg w-100 fw-bold py-3 rounded-3 shadow">
                        <i class="bi bi-check-circle-fill me-1"></i> Kirim Formulir Pendaftaran
                    </button>
                </form>
            </div>

        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
