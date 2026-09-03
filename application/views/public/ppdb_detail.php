<?php
if(!function_exists('ppdb_e')){
    function ppdb_e($text){
        return htmlspecialchars((string)$text, ENT_QUOTES, 'UTF-8');
    }
}

if(!function_exists('ppdb_value')){
    function ppdb_value($text, $default = '-'){
        $text = trim((string)$text);
        return $text !== '' ? ppdb_e($text) : $default;
    }
}

if(!function_exists('ppdb_tanggal')){
    function ppdb_tanggal($date){
        if(empty($date) || $date == '0000-00-00'){
            return '-';
        }

        if(function_exists('tanggal_indo')){
            return tanggal_indo($date);
        }

        return date('d-m-Y', strtotime($date));
    }
}

$status = !empty($siswa->status) ? $siswa->status : '-';

$status_class = 'status-gray';
if($status == 'Diterima'){
    $status_class = 'status-green';
} elseif($status == 'Ditolak'){
    $status_class = 'status-red';
} elseif($status == 'Perlu Perbaikan'){
    $status_class = 'status-orange';
} elseif($status == 'Menunggu Verifikasi Berkas' || $status == 'Upload Berkas'){
    $status_class = 'status-blue';
}

$foto_path = !empty($siswa->foto) ? FCPATH.'uploads/temp/ppdb/'.$siswa->foto : '';
$foto_url  = !empty($siswa->foto) ? base_url('uploads/temp/ppdb/'.$siswa->foto) : '';

$files = [
    'foto'             => 'Pas Foto',
    'kk_file'          => 'Kartu Keluarga',
    'akta_file'        => 'Akta Kelahiran',
	'sk_kelas9_file'   => 'Surat Keterangan Kelas 9',
    'rapor_file'       => 'Rapor / Nilai',
    'skl_file'         => 'Surat Keterangan Lulus',
    'nisn_file'        => 'Surat Aktif NISN',
    'ijazah_file'      => 'Ijazah',
    'sertifikat_file'  => 'Sertifikat Prestasi / Tahfidz'
];

$completed_files = 0;
foreach($files as $field => $label){
    if(!empty($siswa->$field)){
        $completed_files++;
    }
}

$total_files = count($files);
$file_percent = $total_files > 0 ? round(($completed_files / $total_files) * 100) : 0;
?>

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">

<title>Detail Pendaftaran PPDB</title>

<link rel="icon" type="image/png" href="<?= base_url('assets/img/favicon.png') ?>">
<link rel="shortcut icon" type="image/png" href="<?= base_url('assets/img/favicon.png') ?>">

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

<style>
:root{
    --green:#16a34a;
    --green-2:#22c55e;
    --green-dark:#064e3b;
    --green-soft:#ecfdf5;
    --text:#0f172a;
    --muted:#64748b;
    --border:#e2e8f0;
    --soft:#f8fafc;
    --yellow:#facc15;
    --red:#ef4444;
    --orange:#f97316;
    --blue:#0ea5e9;
}

*{
    box-sizing:border-box;
}

body{
    margin:0;
    min-height:100vh;
    background:
        radial-gradient(circle at top left, rgba(34,197,94,.16), transparent 32%),
        radial-gradient(circle at top right, rgba(14,165,233,.10), transparent 30%),
        linear-gradient(135deg,#f8fafc,#ffffff 46%,#ecfdf5);
    color:var(--text);
    font-family:Inter, system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif;
}

a{
    text-decoration:none;
}

.ppdb-detail-page{
    max-width:1220px;
    margin:0 auto;
    padding:34px 14px 50px;
}

.ppdb-top-nav{
    display:flex;
    justify-content:space-between;
    align-items:center;
    gap:14px;
    flex-wrap:wrap;
    margin-bottom:18px;
}

.ppdb-brand{
    display:flex;
    align-items:center;
    gap:10px;
    color:#064e3b;
    font-weight:950;
}

.ppdb-brand-logo{
    width:46px;
    height:46px;
    border-radius:17px;
    background:linear-gradient(135deg,#064e3b,#22c55e);
    color:white;
    display:flex;
    align-items:center;
    justify-content:center;
    font-weight:950;
    box-shadow:0 12px 28px rgba(22,163,74,.22);
    overflow:hidden;
}

.ppdb-brand-logo img{
    width:100%;
    height:100%;
    object-fit:cover;
}

.ppdb-brand small{
    display:block;
    color:var(--muted);
    font-size:12px;
    font-weight:750;
}

.nav-actions{
    display:flex;
    gap:9px;
    flex-wrap:wrap;
}

.nav-btn{
    min-height:40px;
    padding:0 14px;
    border-radius:14px;
    display:inline-flex;
    align-items:center;
    justify-content:center;
    font-size:13px;
    font-weight:950;
}

.nav-btn-soft{
    background:#ecfdf5;
    color:#166534;
    border:1px solid #bbf7d0;
}

.nav-btn-gray{
    background:#f1f5f9;
    color:#334155;
    border:1px solid #e2e8f0;
}

.nav-btn-primary{
    background:linear-gradient(135deg,#15803d,#22c55e);
    color:white;
    box-shadow:0 12px 26px rgba(22,163,74,.22);
}

.nav-btn-soft:hover{color:#166534;background:#dcfce7;}
.nav-btn-gray:hover{color:#334155;background:#e2e8f0;}
.nav-btn-primary:hover{color:white;}

.alert{
    border-radius:18px;
    border:0;
    font-weight:800;
}

.detail-hero{
    position:relative;
    overflow:hidden;
    background:
        radial-gradient(circle at top right, rgba(250,204,21,.22), transparent 32%),
        linear-gradient(135deg,#064e3b,#15803d 58%,#22c55e);
    border-radius:34px;
    padding:28px;
    color:white;
    box-shadow:0 24px 70px rgba(22,163,74,.22);
    margin-bottom:22px;
}

.detail-hero:after{
    content:"";
    position:absolute;
    right:-80px;
    top:-80px;
    width:240px;
    height:240px;
    border-radius:50%;
    background:rgba(255,255,255,.10);
}

.detail-hero-inner{
    position:relative;
    z-index:2;
    display:grid;
    grid-template-columns:160px 1fr auto;
    gap:22px;
    align-items:center;
}

.profile-photo{
    width:150px;
    height:190px;
    object-fit:cover;
    border-radius:28px;
    border:5px solid rgba(255,255,255,.28);
    box-shadow:0 18px 45px rgba(15,23,42,.22);
    background:#ecfdf5;
}

.profile-empty{
    width:150px;
    height:190px;
    border-radius:28px;
    border:5px solid rgba(255,255,255,.28);
    box-shadow:0 18px 45px rgba(15,23,42,.22);
    background:rgba(255,255,255,.14);
    color:rgba(255,255,255,.82);
    display:flex;
    align-items:center;
    justify-content:center;
    text-align:center;
    font-size:13px;
    font-weight:850;
    padding:14px;
}

.hero-info h1{
    font-size:34px;
    line-height:1.15;
    letter-spacing:-.8px;
    font-weight:950;
    margin:0 0 8px;
}

.hero-meta{
    display:flex;
    gap:9px;
    flex-wrap:wrap;
    align-items:center;
    color:rgba(255,255,255,.78);
    font-weight:750;
    margin-bottom:14px;
}

.status-pill{
    display:inline-flex;
    align-items:center;
    padding:8px 12px;
    border-radius:999px;
    font-size:12px;
    font-weight:950;
}

.status-green{
    background:#dcfce7;
    color:#166534;
}

.status-red{
    background:#fee2e2;
    color:#991b1b;
}

.status-orange{
    background:#ffedd5;
    color:#9a3412;
}

.status-blue{
    background:#dbeafe;
    color:#1d4ed8;
}

.status-gray{
    background:#f1f5f9;
    color:#475569;
}

.hero-mini-grid{
    display:grid;
    grid-template-columns:repeat(3,1fr);
    gap:10px;
    max-width:680px;
}

.hero-mini{
    padding:12px;
    border-radius:18px;
    background:rgba(255,255,255,.12);
    border:1px solid rgba(255,255,255,.18);
}

.hero-mini small{
    display:block;
    color:rgba(255,255,255,.70);
    font-size:11px;
    font-weight:850;
    margin-bottom:4px;
}

.hero-mini strong{
    color:white;
    font-weight:950;
}

.hero-action-box{
    display:grid;
    gap:9px;
    min-width:180px;
}

.hero-action-btn{
    min-height:42px;
    padding:0 14px;
    border-radius:15px;
    display:flex;
    align-items:center;
    justify-content:center;
    font-size:13px;
    font-weight:950;
    white-space:nowrap;
}

.hero-action-edit{
    background:#facc15;
    color:#422006;
}

.hero-action-pdf{
    background:white;
    color:#166534;
}

.hero-action-back{
    background:rgba(255,255,255,.14);
    color:white;
    border:1px solid rgba(255,255,255,.23);
}

.hero-action-edit:hover{color:#422006;}
.hero-action-pdf:hover{color:#166534;}
.hero-action-back:hover{color:white;background:rgba(255,255,255,.18);}

.detail-layout{
    display:grid;
    grid-template-columns:minmax(0,1fr) 360px;
    gap:20px;
    align-items:start;
}

.info-card{
    background:rgba(255,255,255,.94);
    backdrop-filter:blur(14px);
    border:1px solid rgba(226,232,240,.9);
    border-radius:28px;
    box-shadow:0 18px 50px rgba(15,23,42,.07);
    overflow:hidden;
    margin-bottom:20px;
}

.info-head{
    padding:18px 22px;
    border-bottom:1px solid #e2e8f0;
    background:
        radial-gradient(circle at top right, rgba(34,197,94,.08), transparent 30%),
        #ffffff;
}

.info-head h5{
    color:#14532d;
    font-size:18px;
    font-weight:950;
    margin:0;
}

.info-head small{
    display:block;
    color:#64748b;
    font-size:12px;
    font-weight:750;
    margin-top:4px;
}

.info-body{
    padding:22px;
}

.info-grid{
    display:grid;
    grid-template-columns:repeat(2,1fr);
    gap:13px;
}

.info-item{
    padding:14px;
    border-radius:18px;
    background:#f8fafc;
    border:1px solid #e2e8f0;
}

.info-item.full{
    grid-column:1 / -1;
}

.info-item small{
    display:block;
    color:#64748b;
    font-size:12px;
    font-weight:850;
    margin-bottom:5px;
}

.info-item strong{
    display:block;
    color:#0f172a;
    font-weight:900;
    line-height:1.45;
}

.file-grid{
    display:grid;
    grid-template-columns:repeat(2,1fr);
    gap:13px;
}

.file-card{
    border:1px solid #e2e8f0;
    border-radius:20px;
    background:#f8fafc;
    padding:14px;
    min-height:118px;
    display:flex;
    flex-direction:column;
    justify-content:space-between;
    gap:12px;
}

.file-card-top{
    display:flex;
    gap:12px;
    align-items:flex-start;
}

.file-icon{
    width:42px;
    height:42px;
    border-radius:16px;
    background:#ecfdf5;
    color:#166534;
    display:flex;
    align-items:center;
    justify-content:center;
    font-weight:950;
    flex-shrink:0;
}

.file-card strong{
    display:block;
    color:#0f172a;
    font-weight:950;
    margin-bottom:4px;
}

.file-card small{
    color:#64748b;
    font-weight:750;
}

.file-action{
    min-height:36px;
    border-radius:13px;
    display:inline-flex;
    align-items:center;
    justify-content:center;
    padding:0 12px;
    font-size:12px;
    font-weight:950;
}

.file-open{
    background:#dcfce7;
    color:#166534;
}

.file-open:hover{
    color:#166534;
    background:#bbf7d0;
}

.file-missing{
    background:#f1f5f9;
    color:#64748b;
}

.sidebar-card{
    background:white;
    border:1px solid #e2e8f0;
    border-radius:28px;
    box-shadow:0 18px 50px rgba(15,23,42,.07);
    overflow:hidden;
    margin-bottom:20px;
}

.sidebar-body{
    padding:20px;
}

.progress-box{
    margin-bottom:16px;
}

.progress-label{
    display:flex;
    justify-content:space-between;
    gap:10px;
    color:#334155;
    font-size:13px;
    font-weight:900;
    margin-bottom:8px;
}

.progress-track{
    height:10px;
    border-radius:999px;
    background:#f1f5f9;
    overflow:hidden;
}

.progress-track span{
    display:block;
    height:100%;
    border-radius:999px;
    background:linear-gradient(135deg,#16a34a,#22c55e);
}

.timeline{
    display:grid;
    gap:12px;
}

.timeline-item{
    display:flex;
    gap:12px;
    align-items:flex-start;
}

.timeline-dot{
    width:28px;
    height:28px;
    border-radius:12px;
    background:#dcfce7;
    color:#166534;
    display:flex;
    align-items:center;
    justify-content:center;
    font-size:12px;
    font-weight:950;
    flex-shrink:0;
}

.timeline-item strong{
    display:block;
    color:#0f172a;
    font-weight:950;
    font-size:13px;
}

.timeline-item small{
    display:block;
    color:#64748b;
    font-weight:700;
    line-height:1.45;
}

.note-box{
    border-radius:20px;
    padding:15px;
    background:#fffbeb;
    border:1px solid #fde68a;
    color:#92400e;
    font-size:13px;
    font-weight:700;
    line-height:1.6;
}

@media(max-width:1050px){
    .detail-hero-inner{
        grid-template-columns:150px 1fr;
    }

    .hero-action-box{
        grid-column:1 / -1;
        grid-template-columns:repeat(3,1fr);
    }

    .detail-layout{
        grid-template-columns:1fr;
    }
}

@media(max-width:768px){
    .ppdb-detail-page{
        padding:20px 12px 38px;
    }

    .ppdb-top-nav{
        display:grid;
        grid-template-columns:1fr;
    }

    .nav-actions{
        display:grid;
        grid-template-columns:1fr 1fr;
    }

    .nav-btn{
        width:100%;
    }

    .detail-hero{
        border-radius:24px;
        padding:22px;
    }

    .detail-hero-inner{
        grid-template-columns:1fr;
        text-align:center;
    }

    .profile-photo,
    .profile-empty{
        margin:0 auto;
        width:130px;
        height:165px;
        border-radius:24px;
    }

    .hero-info h1{
        font-size:26px;
    }

    .hero-meta{
        justify-content:center;
    }

    .hero-mini-grid{
        grid-template-columns:1fr;
    }

    .hero-action-box{
        grid-template-columns:1fr;
    }

    .info-card,
    .sidebar-card{
        border-radius:22px;
    }

    .info-body{
        padding:18px;
    }

    .info-grid,
    .file-grid{
        grid-template-columns:1fr;
    }
}
</style>
</head>

<body>

<div class="ppdb-detail-page">

    <div class="ppdb-top-nav">
        <a href="<?= base_url() ?>" class="ppdb-brand">
            <div class="ppdb-brand-logo">
                <?php if(file_exists(FCPATH.'assets/img/logo-madrasah.png')): ?>
                    <img src="<?= base_url('assets/img/logo-madrasah.png') ?>" alt="Logo">
                <?php else: ?>
                    M3
                <?php endif; ?>
            </div>
            <div>
                <div>PPDB MAN 3 Banjar</div>
                <small>Detail Pendaftaran Peserta</small>
            </div>
        </a>

        <div class="nav-actions">
            <a href="<?= base_url('ppdb/dashboard') ?>" class="nav-btn nav-btn-gray">
                Dashboard
            </a>
            <a href="<?= base_url('ppdb/logout') ?>" class="nav-btn nav-btn-soft">
                Logout
            </a>
        </div>
    </div>

    <?php if($this->session->flashdata('success')): ?>
        <div class="alert alert-success">
            <?= $this->session->flashdata('success') ?>
        </div>
    <?php endif; ?>

    <?php if($this->session->flashdata('error')): ?>
        <div class="alert alert-danger">
            <?= $this->session->flashdata('error') ?>
        </div>
    <?php endif; ?>

    <section class="detail-hero">
        <div class="detail-hero-inner">

            <div>
                <?php if(!empty($siswa->foto) && file_exists($foto_path)): ?>
                    <img src="<?= $foto_url ?>" class="profile-photo" alt="Foto Peserta">
                <?php else: ?>
                    <div class="profile-empty">
                        Belum ada foto
                    </div>
                <?php endif; ?>
            </div>

            <div class="hero-info">
                <h1><?= ppdb_value($siswa->nama_lengkap ?? '') ?></h1>

                <div class="hero-meta">
                    <span>No. Pendaftaran: <?= ppdb_value($siswa->no_pendaftaran ?? '') ?></span>
                    <span>•</span>
                    <span>NISN: <?= ppdb_value($siswa->nisn ?? '') ?></span>
                </div>

                <div class="mb-3">
                    <span class="status-pill <?= $status_class ?>">
                        <?= ppdb_value($status) ?>
                    </span>
                </div>

                <div class="hero-mini-grid">
                    <div class="hero-mini">
                        <small>Asal Sekolah</small>
                        <strong><?= ppdb_value($siswa->asal_sekolah ?? '') ?></strong>
                    </div>

                    <div class="hero-mini">
                        <small>Nomor HP</small>
                        <strong><?= ppdb_value($siswa->no_hp ?? '') ?></strong>
                    </div>

                    <div class="hero-mini">
                        <small>Berkas Upload</small>
                        <strong><?= $completed_files ?>/<?= $total_files ?> Berkas</strong>
                    </div>
                </div>
            </div>

            <div class="hero-action-box">
                <?php if(!in_array($status, ['Diterima','Ditolak'])): ?>
                    <a href="<?= base_url('ppdb/edit_detail') ?>" class="hero-action-btn hero-action-edit">
                        Edit Detail
                    </a>
                <?php endif; ?>

                <a href="<?= base_url('ppdb/download_pdf') ?>" class="hero-action-btn hero-action-pdf">
                    Download Bukti Pendaftaran
                </a>

                <a href="<?= base_url('ppdb/dashboard') ?>" class="hero-action-btn hero-action-back">
                    Kembali
                </a>
            </div>

        </div>
    </section>

    <div class="detail-layout">

        <main>

            <div class="info-card">
                <div class="info-head">
                    <h5>Data Pendaftaran</h5>
                    <small>Informasi utama akun dan data registrasi awal peserta.</small>
                </div>

                <div class="info-body">
                    <div class="info-grid">
                        <div class="info-item">
                            <small>No Pendaftaran</small>
                            <strong><?= ppdb_value($siswa->no_pendaftaran ?? '') ?></strong>
                        </div>

                        <div class="info-item">
                            <small>NISN</small>
                            <strong><?= ppdb_value($siswa->nisn ?? '') ?></strong>
                        </div>

                        <div class="info-item full">
                            <small>Nama Lengkap</small>
                            <strong><?= ppdb_value($siswa->nama_lengkap ?? '') ?></strong>
                        </div>

                        <div class="info-item">
                            <small>Jenis Kelamin</small>
                            <strong>
                                <?= ($siswa->jk ?? '') == 'L' ? 'Laki-laki' : (($siswa->jk ?? '') == 'P' ? 'Perempuan' : '-') ?>
                            </strong>
                        </div>

                        <div class="info-item">
                            <small>Tempat, Tanggal Lahir</small>
                            <strong>
                                <?= ppdb_value($siswa->tempat_lahir ?? '') ?>,
                                <?= ppdb_tanggal($siswa->tanggal_lahir ?? '') ?>
                            </strong>
                        </div>

                        <div class="info-item">
                            <small>Asal Sekolah</small>
                            <strong><?= ppdb_value($siswa->asal_sekolah ?? '') ?></strong>
                        </div>

                        <div class="info-item">
                            <small>No HP</small>
                            <strong><?= ppdb_value($siswa->no_hp ?? '') ?></strong>
                        </div>

                        <div class="info-item">
                            <small>Jalur Pendaftaran</small>
                            <strong style="color: #059669;"><?= ppdb_value($siswa->jalur_pendaftaran ?? 'Reguler') ?></strong>
                        </div>

                        <div class="info-item">
                            <small>Alamat Email</small>
                            <strong><?= ppdb_value($siswa->email ?? '-') ?></strong>
                        </div>

                        <div class="info-item">
                            <small>Nama Ayah / Wali Awal</small>
                            <strong><?= ppdb_value($siswa->nama_ortu ?? '') ?></strong>
                        </div>

                        <div class="info-item">
                            <small>Status Pendaftaran</small>
                            <strong><?= ppdb_value($status) ?></strong>
                        </div>
                    </div>
                </div>
            </div>

            <div class="info-card">
                <div class="info-head">
                    <h5>Data Pribadi</h5>
                    <small>Identitas pribadi calon peserta didik.</small>
                </div>

                <div class="info-body">
                    <div class="info-grid">
                        <div class="info-item">
                            <small>NIK</small>
                            <strong><?= ppdb_value($siswa->nik ?? '') ?></strong>
                        </div>

                        <div class="info-item">
                            <small>No KK</small>
                            <strong><?= ppdb_value($siswa->no_kk ?? '') ?></strong>
                        </div>

                        <div class="info-item">
                            <small>Agama</small>
                            <strong><?= ppdb_value($siswa->agama ?? '') ?></strong>
                        </div>

                        <div class="info-item">
                            <small>Anak Ke</small>
                            <strong><?= ppdb_value($siswa->anak_ke ?? '') ?></strong>
                        </div>

                        <div class="info-item">
                            <small>Jumlah Saudara</small>
                            <strong><?= ppdb_value($siswa->jumlah_saudara ?? '') ?></strong>
                        </div>
                    </div>
                </div>
            </div>

            <div class="info-card">
                <div class="info-head">
                    <h5>Alamat</h5>
                    <small>Alamat lengkap sesuai data pendaftaran.</small>
                </div>

                <div class="info-body">
                    <div class="info-grid">
                        <div class="info-item full">
                            <small>Alamat Lengkap</small>
                            <strong>
                                <?= ppdb_value($siswa->alamat ?? '') ?>,
                                RT <?= ppdb_value($siswa->rt ?? '') ?>/RW <?= ppdb_value($siswa->rw ?? '') ?>,
                                <?= ppdb_value($siswa->desa ?? '') ?>,
                                <?= ppdb_value($siswa->kecamatan ?? '') ?>,
                                <?= ppdb_value($siswa->kabupaten ?? '') ?>,
                                <?= ppdb_value($siswa->provinsi ?? '') ?>,
                                <?= ppdb_value($siswa->kode_pos ?? '') ?>
                            </strong>
                        </div>
                    </div>
                </div>
            </div>

            <div class="info-card">
                <div class="info-head">
                    <h5>Data Orang Tua</h5>
                    <small>Data orang tua/wali yang terisi pada formulir PPDB.</small>
                </div>

                <div class="info-body">
                    <div class="info-grid">
                        <div class="info-item">
                            <small>Nama Ayah</small>
                            <strong><?= ppdb_value($siswa->nama_ayah ?? '') ?></strong>
                        </div>

                        <div class="info-item">
                            <small>Pekerjaan Ayah</small>
                            <strong><?= ppdb_value($siswa->pekerjaan_ayah ?? '') ?></strong>
                        </div>

                        <div class="info-item">
                            <small>Nama Ibu</small>
                            <strong><?= ppdb_value($siswa->nama_ibu ?? '') ?></strong>
                        </div>

                        <div class="info-item">
                            <small>Pekerjaan Ibu</small>
                            <strong><?= ppdb_value($siswa->pekerjaan_ibu ?? '') ?></strong>
                        </div>

                        <div class="info-item full">
                            <small>Penghasilan Orang Tua</small>
                            <strong><?= ppdb_value($siswa->penghasilan_ortu ?? '') ?></strong>
                        </div>
                    </div>
                </div>
            </div>

            <div class="info-card">
                <div class="info-head">
                    <h5>Berkas Upload</h5>
                    <small>Dokumen yang telah diunggah peserta saat proses PPDB.</small>
                </div>

                <div class="info-body">
                    <div class="file-grid">
                        <?php foreach($files as $field => $label): ?>
                            <?php
                            $filename = !empty($siswa->$field) ? $siswa->$field : '';
                            $file_path = !empty($filename) ? FCPATH.'uploads/temp/ppdb/'.$filename : '';
                            $file_url = !empty($filename) ? base_url('uploads/temp/ppdb/'.$filename) : '';
                            $ext = !empty($filename) ? strtoupper(pathinfo($filename, PATHINFO_EXTENSION)) : '';
                            ?>

                            <div class="file-card">
                                <div class="file-card-top">
                                    <div class="file-icon">
                                        <?= !empty($ext) ? ppdb_e(substr($ext,0,3)) : '—' ?>
                                    </div>

                                    <div>
                                        <strong><?= ppdb_e($label) ?></strong>
                                        <small>
                                            <?= !empty($filename) ? ppdb_e($filename) : 'Belum upload' ?>
                                        </small>
                                    </div>
                                </div>

                                <?php if(!empty($filename) && file_exists($file_path)): ?>
                                    <a target="_blank" href="<?= $file_url ?>" class="file-action file-open">
                                        Lihat File
                                    </a>
                                <?php elseif(!empty($filename)): ?>
                                    <span class="file-action file-missing">
                                        File tidak ditemukan
                                    </span>
                                <?php else: ?>
                                    <span class="file-action file-missing">
                                        Belum Upload
                                    </span>
                                <?php endif; ?>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>

        </main>

        <aside>

            <div class="sidebar-card">
                <div class="info-head">
                    <h5>Progress Berkas</h5>
                    <small>Kelengkapan dokumen peserta.</small>
                </div>

                <div class="sidebar-body">
                    <div class="progress-box">
                        <div class="progress-label">
                            <span>Kelengkapan</span>
                            <span><?= $file_percent ?>%</span>
                        </div>

                        <div class="progress-track">
                            <span style="width:<?= $file_percent ?>%;"></span>
                        </div>
                    </div>

                    <div class="info-grid" style="grid-template-columns:1fr;">
                        <div class="info-item">
                            <small>Berkas Terunggah</small>
                            <strong><?= $completed_files ?> dari <?= $total_files ?> berkas</strong>
                        </div>

                        <div class="info-item">
                            <small>Status Peserta</small>
                            <strong><?= ppdb_value($status) ?></strong>
                        </div>
                    </div>
                </div>
            </div>

            <div class="sidebar-card">
                <div class="info-head">
                    <h5>Alur Setelah Ini</h5>
                    <small>Pantau proses pendaftaran Anda.</small>
                </div>

                <div class="sidebar-body">
                    <div class="timeline">
                        <div class="timeline-item">
                            <div class="timeline-dot">1</div>
                            <div>
                                <strong>Lengkapi Data</strong>
                                <small>Pastikan biodata, alamat, dan data orang tua sudah benar.</small>
                            </div>
                        </div>

                        <div class="timeline-item">
                            <div class="timeline-dot">2</div>
                            <div>
                                <strong>Upload Berkas</strong>
                                <small>Pastikan dokumen yang diminta sudah diunggah dengan jelas.</small>
                            </div>
                        </div>

                        <div class="timeline-item">
                            <div class="timeline-dot">3</div>
                            <div>
                                <strong>Verifikasi Panitia</strong>
                                <small>Panitia akan memeriksa data dan berkas pendaftaran.</small>
                            </div>
                        </div>

                        <div class="timeline-item">
                            <div class="timeline-dot">4</div>
                            <div>
                                <strong>Hasil Pendaftaran</strong>
                                <small>Status diterima atau ditolak akan tampil di dashboard peserta.</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="sidebar-card">
                <div class="sidebar-body">
                    <div class="note-box">
                        <strong>Catatan:</strong><br>
                        Jika ada data yang salah, silakan klik tombol Edit Detail selama status belum Diterima atau Ditolak. Jika tombol edit tidak tersedia, hubungi panitia PPDB.
                    </div>
                </div>
            </div>

        </aside>

    </div>

</div>

</body>
</html>