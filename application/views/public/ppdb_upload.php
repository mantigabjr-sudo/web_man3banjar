<?php
if(!function_exists('ppdb_upload_e')){
    function ppdb_upload_e($text){
        return htmlspecialchars((string)$text, ENT_QUOTES, 'UTF-8');
    }
}

if(!function_exists('ppdb_upload_value')){
    function ppdb_upload_value($text, $default = '-'){
        $text = trim((string)$text);
        return $text !== '' ? ppdb_upload_e($text) : $default;
    }
}

$nama_lengkap   = !empty($siswa->nama_lengkap) ? $siswa->nama_lengkap : 'Peserta ' . htmlspecialchars(!empty($nama_ppdb) ? $nama_ppdb : 'PPDB');
$no_pendaftaran = !empty($siswa->no_pendaftaran) ? $siswa->no_pendaftaran : '-';

$foto_path = !empty($siswa->foto) ? FCPATH.'uploads/temp/ppdb/'.$siswa->foto : '';
$foto_url  = !empty($siswa->foto) ? base_url('uploads/temp/ppdb/'.$siswa->foto) : '';

$berkas = [
    [
        'field' => 'foto',
        'label' => 'Pas Foto',
        'required' => true,
        'note' => 'Pas foto terbaru peserta.',
    ],
    [
        'field' => 'kk_file',
        'label' => 'Kartu Keluarga',
        'required' => true,
        'note' => 'Upload Kartu Keluarga yang jelas dan terbaca.',
    ],
    [
        'field' => 'akta_file',
        'label' => 'Akta Kelahiran',
        'required' => true,
        'note' => 'Upload akta kelahiran peserta.',
    ],
    [
        'field' => 'rapor_file',
        'label' => 'Rapor / Nilai',
        'required' => false,
        'note' => 'Boleh menyusul jika belum tersedia.',
    ],
    [
        'field' => 'skl_file',
        'label' => 'Surat Keterangan Lulus',
        'required' => false,
        'note' => 'Boleh menyusul jika belum tersedia.',
    ],
    [
        'field' => 'nisn_file',
        'label' => 'Surat Aktif NISN',
        'required' => false,
        'note' => 'Boleh menyusul jika belum tersedia.',
    ],
    [
        'field' => 'sk_kelas9_file',
        'label' => 'Surat Keterangan Kelas 9',
        'required' => true,
        'note' => 'Wajib diupload. Surat keterangan bahwa peserta masih/terdaftar sebagai siswa kelas 9.',
    ],
    [
        'field' => 'ijazah_file',
        'label' => 'Ijazah',
        'required' => true,
        'note' => 'Wajib diupload. Silakan upload ijazah SD/MI atau ijazah terakhir.',
    ],
    [
        'field' => 'sertifikat_file',
        'label' => 'Sertifikat Prestasi / Tahfidz',
        'required' => false,
        'note' => 'Opsional jika memiliki prestasi atau tahfidz.',
    ],
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
<meta name="viewport" content="width=device-width, initial-scale=1">

<title>Upload Berkas <?= htmlspecialchars($nama_ppdb ?? 'PPDB') ?></title>

<link rel="icon" type="image/png" href="<?= base_url('assets/img/favicon.png') ?>">
<link rel="shortcut icon" type="image/png" href="<?= base_url('assets/img/favicon.png') ?>">

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="<?= base_url('assets/css/ppdb-peserta.css?v=3') ?>">
</head>

<body>

<div class="peserta-shell">

    <aside class="peserta-sidebar">
        <div class="peserta-brand">
            <div class="peserta-brand-logo">
                <?php if(file_exists(FCPATH.'assets/img/logo-madrasah.png')): ?>
                    <img src="<?= base_url('assets/img/logo-madrasah.png') ?>" alt="Logo">
                <?php else: ?>
                    M3
                <?php endif; ?>
            </div>

            <div>
                <strong><?= htmlspecialchars($nama_ppdb ?? 'PPDB') ?> MAN 3</strong>
                <small>Portal Peserta</small>
            </div>
        </div>

        <div class="peserta-profile-mini">
            <?php if(!empty($siswa->foto) && file_exists($foto_path)): ?>
                <img src="<?= $foto_url ?>" alt="Foto Peserta">
            <?php else: ?>
                <div class="peserta-avatar">
                    <?= strtoupper(substr($nama_lengkap, 0, 1)) ?>
                </div>
            <?php endif; ?>

            <strong><?= ppdb_upload_e($nama_lengkap) ?></strong>
            <small><?= ppdb_upload_e($no_pendaftaran) ?></small>
        </div>

        <nav class="peserta-menu">
            <a href="<?= base_url('ppdb/dashboard') ?>">
                <span>⌂</span> Dashboard
            </a>

            <a href="<?= base_url('ppdb/biodata') ?>">
                <span>◎</span> Lengkapi Biodata
            </a>

            <a href="<?= base_url('ppdb/upload') ?>" class="active">
                <span>⇧</span> Upload Berkas
            </a>

            <a href="<?= base_url('ppdb/detail') ?>">
                <span>▣</span> Detail Pendaftaran
            </a>

            <a href="<?= base_url('ppdb/logout') ?>" class="logout">
                <span>×</span> Logout
            </a>
        </nav>
    </aside>

    <main class="peserta-main">

        <div class="peserta-mobile-head">
            <div>
                <strong><?= htmlspecialchars($nama_ppdb ?? 'PPDB') ?> MAN 3</strong>
                <small>Upload Berkas</small>
            </div>

            <a href="<?= base_url('ppdb/logout') ?>">
                Logout
            </a>
        </div>

        <?php if($this->session->flashdata('success')): ?>
            <div class="alert alert-success peserta-alert">
                <?= $this->session->flashdata('success') ?>
            </div>
        <?php endif; ?>

        <?php if($this->session->flashdata('error')): ?>
            <div class="alert alert-danger peserta-alert">
                <?= $this->session->flashdata('error') ?>
            </div>
        <?php endif; ?>

        <section class="peserta-form-hero">
            <div>
                <div class="peserta-kicker">
                    Upload Berkas <?= htmlspecialchars($nama_ppdb ?? 'PPDB') ?>
                </div>

                <h1>Lengkapi Dokumen Pendaftaran</h1>

                <p>
                    Upload dokumen dengan format PDF, JPG, JPEG, atau PNG. Maksimal ukuran file mengikuti pengaturan sistem, disarankan maksimal 1MB per file.
                </p>

                <div class="peserta-hero-meta">
                    <span>No. Pendaftaran: <strong><?= ppdb_upload_e($no_pendaftaran) ?></strong></span>
                    <span>Nama: <strong><?= ppdb_upload_e($nama_lengkap) ?></strong></span>
                </div>
            </div>

            <div class="upload-progress-panel">
                <small>Kelengkapan Berkas</small>
                <strong><?= $uploaded_berkas ?>/<?= $total_berkas ?> Berkas</strong>

                <div class="upload-progress-track">
                    <span style="width:<?= $upload_percent ?>%;"></span>
                </div>

                <div class="upload-percent">
                    <?= $upload_percent ?>%
                </div>
            </div>
        </section>

        <form method="post"
              enctype="multipart/form-data"
              action="<?= base_url('ppdb/save_upload') ?>">

            <div class="upload-layout">

                <main class="upload-main">

                    <div class="upload-grid">

                        <?php foreach($berkas as $item): ?>
                            <?php
                            $field = $item['field'];
                            $label = $item['label'];
                            $required = $item['required'];
                            $note = $item['note'];

                            $filename = !empty($siswa->$field) ? $siswa->$field : '';
                            $file_path = !empty($filename) ? FCPATH.'uploads/temp/ppdb/'.$filename : '';
                            $file_url  = !empty($filename) ? base_url('uploads/temp/ppdb/'.$filename) : '';
                            $ext = !empty($filename) ? strtoupper(pathinfo($filename, PATHINFO_EXTENSION)) : '';

                            $status_verifikasi_file = '';
                            $catatan_revisi_file = '';
                            if(isset($verifikasi_berkas[$field])){
                                $status_verifikasi_file = $verifikasi_berkas[$field]['status'] ?? '';
                                $catatan_revisi_file = $verifikasi_berkas[$field]['catatan'] ?? '';
                            }
                            ?>

                            <div class="upload-card">
                                <div class="upload-card-head">
                                    <div class="upload-icon">
                                        <?= !empty($ext) ? ppdb_upload_e(substr($ext, 0, 3)) : 'DOC' ?>
                                    </div>

                                    <div>
                                        <h5><?= ppdb_upload_e($label) ?></h5>

                                        <?php if($required): ?>
                                            <span class="upload-badge required">Wajib</span>
                                        <?php else: ?>
                                            <span class="upload-badge optional">Opsional / Menyusul</span>
                                        <?php endif; ?>
                                    </div>
                                </div>

                                <p class="upload-note">
                                    <?= ppdb_upload_e($note) ?>
                                </p>

                                <?php if(!empty($filename) && file_exists($file_path)): ?>
                                    <div class="upload-status uploaded">
                                        <strong>Sudah diupload</strong>
                                        <small><?= ppdb_upload_e($filename) ?></small>

                                        <a href="<?= $file_url ?>" target="_blank">
                                            Lihat File
                                        </a>
                                    </div>
                                <?php elseif(!empty($filename)): ?>
                                    <div class="upload-status missing">
                                        <strong>File tidak ditemukan</strong>
                                        <small>Silakan upload ulang dokumen ini.</small>
                                    </div>
                                <?php else: ?>
                                    <div class="upload-status empty">
                                        <strong>Belum upload</strong>
                                        <small>Silakan pilih file dokumen.</small>
                                    </div>
                                <?php endif; ?>

                                <?php if($status_verifikasi_file == 'Perlu Perbaikan'): ?>
                                    <div class="alert alert-danger py-2 px-3 rounded-3 mb-2" style="font-size: 13px; border-radius: 8px; border: 1px solid #fecdd3 !important; background-color: #fff1f2; color: #b91c1c; font-weight: normal;">
                                        <strong class="d-flex align-items-center gap-1 mb-1 fw-bold text-danger">
                                            <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z"/></svg>
                                            Perlu Perbaikan:
                                        </strong>
                                        <span class="italic">"<?= ppdb_upload_e($catatan_revisi_file) ?>"</span>
                                    </div>
                                <?php elseif($status_verifikasi_file == 'Sesuai'): ?>
                                    <div class="alert alert-success py-2 px-3 rounded-3 mb-2" style="font-size: 13px; border-radius: 8px; border: 1px solid #bbf7d0 !important; background-color: #f0fdf4; color: #15803d; font-weight: normal;">
                                        <strong class="d-flex align-items-center gap-1 fw-bold text-success">
                                            <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                            Sesuai (Terverifikasi)
                                        </strong>
                                    </div>
                                <?php endif; ?>

                                <div class="upload-input-wrap">
                                    <label>
                                        <?= !empty($filename) ? 'Ganti File' : 'Upload File' ?>
                                    </label>

                                    <input type="file"
                                           name="<?= ppdb_upload_e($field) ?>"
                                           class="form-control upload-input"
                                           accept=".pdf,.jpg,.jpeg,.png"
                                           <?= ($required && empty($filename)) ? 'required' : '' ?>>

                                    <small>
                                        Format PDF/JPG/PNG. Kosongkan jika tidak ingin mengganti file yang sudah ada.
                                    </small>
                                </div>
                            </div>

                        <?php endforeach; ?>

                    </div>

                </main>

                <aside class="upload-sidebar">

                    <div class="upload-side-card">
                        <h5>Ringkasan Upload</h5>

                        <div class="upload-side-progress">
                            <div>
                                <span>Kelengkapan</span>
                                <strong><?= $upload_percent ?>%</strong>
                            </div>

                            <div class="upload-progress-track">
                                <span style="width:<?= $upload_percent ?>%;"></span>
                            </div>
                        </div>

                        <div class="upload-side-list">
                            <div>
                                <small>Sudah Upload</small>
                                <strong><?= $uploaded_berkas ?> berkas</strong>
                            </div>

                            <div>
                                <small>Total Berkas</small>
                                <strong><?= $total_berkas ?> berkas</strong>
                            </div>

                            <div>
                                <small>Berkas Wajib Baru</small>
                                <strong>Surat Keterangan Kelas 9</strong>
                            </div>
                        </div>
                    </div>

                    <div class="upload-side-card upload-note-card">
                        <h5>Catatan Penting</h5>
                        <p>
                            Surat Keterangan Kelas 9 wajib diupload. Untuk ijazah, peserta boleh upload ijazah SD/MI, atau menyusul jika belum tersedia.
                        </p>
                    </div>

                </aside>

            </div>

            <div class="bio-submit-bar">
                <div>
                    <strong>Simpan Berkas</strong>
                    <small>Periksa kembali file sebelum disimpan.</small>
                </div>

                <div class="bio-submit-actions">
                    <a href="<?= base_url('ppdb/dashboard') ?>" class="peserta-btn peserta-btn-soft">
                        Kembali
                    </a>

                    <button type="submit" class="peserta-btn peserta-btn-main">
                        Simpan Berkas
                    </button>
                </div>
            </div>

        </form>

    </main>

</div>

<nav class="peserta-bottom-nav">
    <a href="<?= base_url('ppdb/dashboard') ?>">
        <span>⌂</span>
        Dashboard
    </a>

    <a href="<?= base_url('ppdb/biodata') ?>">
        <span>◎</span>
        Biodata
    </a>

    <a href="<?= base_url('ppdb/upload') ?>" class="active">
        <span>⇧</span>
        Berkas
    </a>

    <a href="<?= base_url('ppdb/detail') ?>">
        <span>▣</span>
        Detail
    </a>
</nav>

</body>
</html>