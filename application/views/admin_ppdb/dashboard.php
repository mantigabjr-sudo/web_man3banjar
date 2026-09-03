<?php $this->load->view('templates/header'); ?>
<?php $this->load->view('templates/sidebar'); ?>

<?php
if(!function_exists('ppdb_admin_e')){
    function ppdb_admin_e($text){
        return htmlspecialchars((string)$text, ENT_QUOTES, 'UTF-8');
    }
}

if(!function_exists('ppdb_admin_percent')){
    function ppdb_admin_percent($value, $total){
        $value = (int)$value;
        $total = (int)$total;

        if($total <= 0){
            return 0;
        }

        return round(($value / $total) * 100);
    }
}

$total          = isset($total) ? (int)$total : 0;
$lengkapi       = isset($lengkapi) ? (int)$lengkapi : 0;
$upload         = isset($upload) ? (int)$upload : 0;
$verifikasi     = isset($verifikasi) ? (int)$verifikasi : 0;
$perbaikan      = isset($perbaikan) ? (int)$perbaikan : 0;
$diterima       = isset($diterima) ? (int)$diterima : 0;
$ditolak        = isset($ditolak) ? (int)$ditolak : 0;
$belum_migrasi  = isset($belum_migrasi) ? (int)$belum_migrasi : 0;
$sudah_migrasi  = isset($sudah_migrasi) ? (int)$sudah_migrasi : 0;

$tahun_ajaran = !empty($settings->tahun_ajaran) ? $settings->tahun_ajaran : '-';
$status_ppdb  = !empty($settings->status_ppdb) ? $settings->status_ppdb : 'Ditutup';

$cards = [
    [
        'title' => 'Total Pendaftar',
        'value' => $total,
        'url' => 'admin_ppdb',
        'icon' => 'TP',
        'desc' => 'Semua calon peserta',
        'class' => 'green'
    ],
    [
        'title' => 'Lengkapi Biodata',
        'value' => $lengkapi,
        'url' => 'admin_ppdb?status=Lengkapi Biodata',
        'icon' => 'BD',
        'desc' => ppdb_admin_percent($lengkapi, $total).'% dari total',
        'class' => 'blue'
    ],
    [
        'title' => 'Upload Berkas',
        'value' => $upload,
        'url' => 'admin_ppdb?status=Upload Berkas',
        'icon' => 'UP',
        'desc' => ppdb_admin_percent($upload, $total).'% dari total',
        'class' => 'cyan'
    ],
    [
        'title' => 'Menunggu Verifikasi',
        'value' => $verifikasi,
        'url' => 'admin_ppdb/verifikasi',
        'icon' => 'VF',
        'desc' => ppdb_admin_percent($verifikasi, $total).'% dari total',
        'class' => 'purple'
    ],
    [
        'title' => 'Lulus Verifikasi (Tes)',
        'value' => isset($lulus_verifikasi) ? $lulus_verifikasi : 0,
        'url' => 'admin_ppdb?status=Lulus Verifikasi',
        'icon' => 'TS',
        'desc' => 'Sudah mendapat No. Ujian',
        'class' => 'cyan'
    ],
    [
        'title' => 'Perlu Perbaikan',
        'value' => $perbaikan,
        'url' => 'admin_ppdb?status=Perlu Perbaikan',
        'icon' => 'PB',
        'desc' => ppdb_admin_percent($perbaikan, $total).'% dari total',
        'class' => 'orange'
    ],
    [
        'title' => 'Diterima (Lulus)',
        'value' => $diterima,
        'url' => 'admin_ppdb/diterima',
        'icon' => 'DT',
        'desc' => ppdb_admin_percent($diterima, $total).'% dari total',
        'class' => 'success'
    ],
    [
        'title' => 'Ditolak',
        'value' => $ditolak,
        'url' => 'admin_ppdb/ditolak',
        'icon' => 'DK',
        'desc' => ppdb_admin_percent($ditolak, $total).'% dari total',
        'class' => 'red'
    ],
    [
        'title' => 'Belum Migrasi',
        'value' => $belum_migrasi,
        'url' => 'admin_ppdb/migrasi_data',
        'icon' => 'BM',
        'desc' => 'Belum masuk data siswa',
        'class' => 'dark'
    ],
    [
        'title' => 'Sudah Migrasi',
        'value' => $sudah_migrasi,
        'url' => 'admin_ppdb?migrated=1',
        'icon' => 'SM',
        'desc' => 'Sudah menjadi siswa',
        'class' => 'teal'
    ],
];
?>

<div class="content">

<style>
.ppdb-admin-page{
    max-width:1400px;
    margin:0 auto;
}

.ppdb-admin-hero{
    position:relative;
    overflow:hidden;
    background:
        radial-gradient(circle at top right, rgba(250,204,21,.20), transparent 30%),
        radial-gradient(circle at bottom left, rgba(34,197,94,.18), transparent 34%),
        linear-gradient(135deg,#064e3b,#15803d 56%,#22c55e);
    border-radius:30px;
    padding:26px;
    color:white;
    box-shadow:0 22px 60px rgba(22,163,74,.22);
    margin-bottom:22px;
}

.ppdb-admin-hero:after{
    content:"";
    position:absolute;
    right:-70px;
    top:-70px;
    width:210px;
    height:210px;
    border-radius:50%;
    background:rgba(255,255,255,.10);
}

.ppdb-admin-hero-inner{
    position:relative;
    z-index:2;
    display:flex;
    justify-content:space-between;
    align-items:center;
    gap:18px;
    flex-wrap:wrap;
}

.ppdb-admin-title h2{
    margin:0;
    font-weight:950;
    letter-spacing:-.5px;
}

.ppdb-admin-title p{
    color:rgba(255,255,255,.78);
    font-weight:650;
    margin:7px 0 0;
}

.ppdb-hero-badges{
    display:flex;
    gap:10px;
    flex-wrap:wrap;
    margin-top:14px;
}

.ppdb-hero-badge{
    display:inline-flex;
    align-items:center;
    gap:7px;
    padding:9px 13px;
    border-radius:999px;
    background:rgba(255,255,255,.14);
    border:1px solid rgba(255,255,255,.22);
    color:white;
    font-size:13px;
    font-weight:900;
}

.ppdb-status-open{
    background:#dcfce7;
    color:#166534;
}

.ppdb-status-close{
    background:#fee2e2;
    color:#991b1b;
}

.ppdb-hero-actions{
    display:flex;
    gap:10px;
    flex-wrap:wrap;
}

.ppdb-hero-btn{
    min-height:44px;
    padding:0 16px;
    border-radius:15px;
    display:inline-flex;
    align-items:center;
    justify-content:center;
    font-weight:950;
    text-decoration:none;
}

.ppdb-hero-btn-light{
    background:white;
    color:#166534;
}

.ppdb-hero-btn-soft{
    background:rgba(255,255,255,.14);
    color:white;
    border:1px solid rgba(255,255,255,.24);
}

.ppdb-hero-btn-light:hover{
    color:#166534;
}

.ppdb-hero-btn-soft:hover{
    color:white;
    background:rgba(255,255,255,.18);
}

.ppdb-stat-grid{
    display:grid;
    grid-template-columns:repeat(3,1fr);
    gap:16px;
    margin-bottom:22px;
}

.ppdb-stat-card{
    position:relative;
    overflow:hidden;
    display:block;
    background:white;
    border:1px solid #e2e8f0;
    border-radius:26px;
    padding:20px;
    color:#0f172a;
    text-decoration:none;
    box-shadow:0 16px 42px rgba(15,23,42,.07);
    transition:.22s ease;
}

.ppdb-stat-card:hover{
    transform:translateY(-4px);
    color:#0f172a;
    box-shadow:0 24px 60px rgba(15,23,42,.11);
}

.ppdb-stat-card:after{
    content:"";
    position:absolute;
    right:-36px;
    top:-36px;
    width:115px;
    height:115px;
    border-radius:50%;
    background:#ecfdf5;
}

.ppdb-stat-top{
    position:relative;
    z-index:2;
    display:flex;
    justify-content:space-between;
    gap:14px;
    align-items:flex-start;
    margin-bottom:16px;
}

.ppdb-stat-icon{
    width:50px;
    height:50px;
    border-radius:19px;
    display:flex;
    align-items:center;
    justify-content:center;
    font-size:14px;
    font-weight:950;
    flex-shrink:0;
}

.ppdb-stat-title{
    color:#64748b;
    font-size:13px;
    font-weight:850;
    margin-bottom:4px;
}

.ppdb-stat-number{
    color:#0f172a;
    font-size:38px;
    line-height:1;
    font-weight:950;
    letter-spacing:-.8px;
    margin:0;
}

.ppdb-stat-desc{
    position:relative;
    z-index:2;
    color:#64748b;
    font-size:12px;
    font-weight:750;
}

.ppdb-stat-line{
    position:relative;
    z-index:2;
    height:7px;
    border-radius:999px;
    background:#f1f5f9;
    overflow:hidden;
    margin-top:14px;
}

.ppdb-stat-line span{
    display:block;
    height:100%;
    border-radius:999px;
    width:40%;
}

.ppdb-stat-card.green .ppdb-stat-icon,
.ppdb-stat-card.green .ppdb-stat-line span{background:#dcfce7;color:#166534;}
.ppdb-stat-card.green .ppdb-stat-line span{background:#22c55e;}

.ppdb-stat-card.blue .ppdb-stat-icon{background:#dbeafe;color:#1d4ed8;}
.ppdb-stat-card.blue .ppdb-stat-line span{background:#3b82f6;}

.ppdb-stat-card.cyan .ppdb-stat-icon{background:#cffafe;color:#0e7490;}
.ppdb-stat-card.cyan .ppdb-stat-line span{background:#06b6d4;}

.ppdb-stat-card.purple .ppdb-stat-icon{background:#ede9fe;color:#6d28d9;}
.ppdb-stat-card.purple .ppdb-stat-line span{background:#8b5cf6;}

.ppdb-stat-card.orange .ppdb-stat-icon{background:#ffedd5;color:#c2410c;}
.ppdb-stat-card.orange .ppdb-stat-line span{background:#f97316;}

.ppdb-stat-card.success .ppdb-stat-icon{background:#dcfce7;color:#166534;}
.ppdb-stat-card.success .ppdb-stat-line span{background:#22c55e;}

.ppdb-stat-card.red .ppdb-stat-icon{background:#fee2e2;color:#991b1b;}
.ppdb-stat-card.red .ppdb-stat-line span{background:#ef4444;}

.ppdb-stat-card.dark .ppdb-stat-icon{background:#e2e8f0;color:#334155;}
.ppdb-stat-card.dark .ppdb-stat-line span{background:#475569;}

.ppdb-stat-card.teal .ppdb-stat-icon{background:#ccfbf1;color:#0f766e;}
.ppdb-stat-card.teal .ppdb-stat-line span{background:#14b8a6;}

.ppdb-main-grid{
    display:grid;
    grid-template-columns:minmax(0,1fr) 360px;
    gap:18px;
    align-items:start;
}

.ppdb-panel-card{
    background:white;
    border:1px solid #e2e8f0;
    border-radius:26px;
    box-shadow:0 16px 42px rgba(15,23,42,.07);
    overflow:hidden;
    margin-bottom:18px;
}

.ppdb-panel-head{
    padding:18px 20px;
    border-bottom:1px solid #e2e8f0;
    display:flex;
    justify-content:space-between;
    gap:14px;
    align-items:center;
    background:
        radial-gradient(circle at top right, rgba(34,197,94,.08), transparent 34%),
        #ffffff;
}

.ppdb-panel-head h5{
    color:#14532d;
    font-weight:950;
    margin:0;
}

.ppdb-panel-head small{
    color:#64748b;
    font-weight:750;
}

.ppdb-panel-body{
    padding:20px;
}

.ppdb-quick-grid{
    display:grid;
    grid-template-columns:repeat(2,1fr);
    gap:11px;
}

.ppdb-quick-link{
    min-height:58px;
    border-radius:18px;
    padding:12px;
    background:#f8fafc;
    border:1px solid #e2e8f0;
    color:#334155;
    font-weight:900;
    display:flex;
    align-items:center;
    justify-content:space-between;
    gap:12px;
    transition:.2s ease;
}

.ppdb-quick-link:hover{
    background:#ecfdf5;
    border-color:#bbf7d0;
    color:#166534;
}

.ppdb-quick-link.danger:hover{
    background:#fee2e2;
    border-color:#fecaca;
    color:#991b1b;
}

.ppdb-quick-arrow{
    width:28px;
    height:28px;
    border-radius:10px;
    display:flex;
    align-items:center;
    justify-content:center;
    background:white;
    color:#16a34a;
    flex-shrink:0;
}

.ppdb-danger-box{
    background:#fff7ed;
    border:1px solid #fed7aa;
    border-radius:22px;
    padding:16px;
}

.ppdb-danger-box h6{
    color:#9a3412;
    font-weight:950;
    margin-bottom:8px;
}

.ppdb-danger-box p{
    color:#9a3412;
    font-size:13px;
    font-weight:700;
    line-height:1.55;
}

.ppdb-danger-actions{
    display:grid;
    gap:9px;
}

.ppdb-danger-btn{
    min-height:42px;
    border-radius:14px;
    display:flex;
    align-items:center;
    justify-content:center;
    text-decoration:none;
    font-weight:950;
    background:#fee2e2;
    color:#991b1b;
}

.ppdb-danger-btn:hover{
    color:#991b1b;
    background:#fecaca;
}

.ppdb-table-wrap{
    padding:18px 20px 20px;
}

.ppdb-table{
    vertical-align:middle;
}

.ppdb-table th{
    color:#14532d;
    font-size:13px;
    font-weight:950;
    white-space:nowrap;
}

.ppdb-table td{
    font-size:13px;
    font-weight:700;
    color:#334155;
    white-space:nowrap;
}

.ppdb-name-cell strong{
    display:block;
    color:#0f172a;
    font-weight:950;
}

.ppdb-name-cell small{
    display:block;
    color:#64748b;
    font-weight:700;
}

.status-pill{
    display:inline-flex;
    align-items:center;
    padding:7px 10px;
    border-radius:999px;
    font-size:12px;
    font-weight:950;
}

.status-green{
    background:#dcfce7;
    color:#166534;
}

.status-blue{
    background:#dbeafe;
    color:#1d4ed8;
}

.status-orange{
    background:#ffedd5;
    color:#c2410c;
}

.status-red{
    background:#fee2e2;
    color:#991b1b;
}

.status-gray{
    background:#f1f5f9;
    color:#475569;
}

.btn-detail-ppdb{
    min-height:34px;
    padding:0 12px;
    border-radius:12px;
    background:#e0f2fe;
    color:#075985;
    display:inline-flex;
    align-items:center;
    justify-content:center;
    font-size:12px;
    font-weight:950;
    text-decoration:none;
}

.btn-detail-ppdb:hover{
    color:#075985;
    background:#bae6fd;
}

.ppdb-summary-list{
    display:grid;
    gap:11px;
}

.ppdb-summary-item{
    padding:14px;
    border-radius:18px;
    background:#f8fafc;
    border:1px solid #e2e8f0;
}

.ppdb-summary-item small{
    display:block;
    color:#64748b;
    font-size:12px;
    font-weight:850;
    margin-bottom:5px;
}

.ppdb-summary-item strong{
    color:#0f172a;
    font-weight:950;
}

.ppdb-empty{
    border:1px dashed #cbd5e1;
    background:#f8fafc;
    color:#64748b;
    border-radius:22px;
    padding:28px;
    text-align:center;
    font-weight:850;
}

@media(max-width:1200px){
    .ppdb-stat-grid{
        grid-template-columns:repeat(2,1fr);
    }

    .ppdb-main-grid{
        grid-template-columns:1fr;
    }
}

@media(max-width:768px){
    .ppdb-admin-hero,
    .ppdb-panel-card{
        border-radius:22px;
    }

    .ppdb-admin-hero{
        padding:22px;
    }

    .ppdb-hero-actions,
    .ppdb-quick-grid,
    .ppdb-stat-grid{
        grid-template-columns:1fr;
        display:grid;
    }

    .ppdb-hero-btn,
    .ppdb-quick-link{
        width:100%;
    }

    .ppdb-panel-head{
        display:block;
    }

    .ppdb-panel-head small{
        display:block;
        margin-top:4px;
    }

    .ppdb-table-wrap{
        padding:14px;
    }
}
</style>

<div class="ppdb-admin-page">

    <div class="ppdb-admin-hero">
        <div class="ppdb-admin-hero-inner">

            <div class="ppdb-admin-title">
                <h2 class="glow">Dashboard PPDB</h2>
                <p>Monitoring pendaftaran, verifikasi, seleksi, dan migrasi data peserta didik baru.</p>

                <div class="ppdb-hero-badges">
                    <span class="ppdb-hero-badge">
                        Tahun Ajaran: <strong><?= ppdb_admin_e($tahun_ajaran) ?></strong>
                    </span>

                    <?php if($status_ppdb == 'Dibuka'): ?>
                        <span class="ppdb-hero-badge ppdb-status-open">PPDB Dibuka</span>
                    <?php else: ?>
                        <span class="ppdb-hero-badge ppdb-status-close">PPDB Ditutup</span>
                    <?php endif; ?>

                    <span class="ppdb-hero-badge">
                        Total: <strong><?= $total ?></strong> Pendaftar
                    </span>
                </div>
            </div>

            <div class="ppdb-hero-actions">
                <a href="<?= base_url('admin_ppdb/settings') ?>" class="ppdb-hero-btn ppdb-hero-btn-light">
                    Pengaturan PPDB
                </a>

                <a href="<?= base_url('ppdb') ?>" target="_blank" class="ppdb-hero-btn ppdb-hero-btn-soft">
                    Lihat Halaman PPDB
                </a>
            </div>

        </div>
    </div>

    <?php if($this->session->flashdata('success')): ?>
        <div class="alert alert-success rounded-4 fw-bold">
            <?= $this->session->flashdata('success') ?>
        </div>
    <?php endif; ?>

    <!-- PANDUAN ALUR KERJA PANITIA (USER FRIENDLY) -->
    <div class="card border-0 shadow-sm rounded-4 p-4 mb-4" style="background: linear-gradient(135deg, #ffffff, #f8fafc); border: 1.5px solid #e2e8f0 !important;">
        <div class="d-flex align-items-center justify-content-between mb-3 flex-wrap gap-2">
            <div>
                <h5 class="fw-bold text-dark mb-1"><i class="bi bi-compass-fill text-success me-2"></i>Panduan Alur Kerja Panitia PMB</h5>
                <small class="text-muted">Ikuti 5 langkah kerja berurutan ini untuk memproses pendaftaran siswa dari awal hingga selesai.</small>
            </div>
            <a href="<?= base_url('admin_ppdb/monitoring_berkas') ?>" class="btn btn-sm btn-success fw-bold px-3 py-2 rounded-pill shadow-sm" style="background: #059669; border-color: #059669;">
                <i class="bi bi-shield-check me-1"></i> Mulai Verifikasi Berkas
            </a>
        </div>

        <div class="row g-3">
            <div class="col-md-2 col-sm-6">
                <div class="p-3 bg-white border rounded-3 text-center h-100 shadow-none">
                    <div class="badge bg-light text-success fw-bold rounded-circle mb-2" style="width:32px; height:32px; line-height:22px; font-size:14px;">1</div>
                    <strong class="d-block text-dark small">Pendaftar Masuk</strong>
                    <small class="text-muted" style="font-size: 11px;">Calon siswa registrasi online via portal.</small>
                </div>
            </div>

            <div class="col-md-2 col-sm-6">
                <div class="p-3 bg-white border rounded-3 text-center h-100 shadow-none">
                    <div class="badge bg-light text-primary fw-bold rounded-circle mb-2" style="width:32px; height:32px; line-height:22px; font-size:14px;">2</div>
                    <strong class="d-block text-dark small">Cek &amp; Verifikasi</strong>
                    <small class="text-muted" style="font-size: 11px;">Periksa KK, Akta, dan Ijazah di menu Berkas.</small>
                </div>
            </div>

            <div class="col-md-3 col-sm-6">
                <div class="p-3 bg-white border rounded-3 text-center h-100 shadow-none" style="border-color: #a7f3d0 !important; background: #ecfdf5 !important;">
                    <div class="badge bg-success text-white fw-bold rounded-circle mb-2" style="width:32px; height:32px; line-height:22px; font-size:14px;">3</div>
                    <strong class="d-block text-success small">Terbitkan No. Tes &amp; Jadwal</strong>
                    <small class="text-muted" style="font-size: 11px;">Sistem otomatis beri No. Tes &amp; Cetak Kartu.</small>
                </div>
            </div>

            <div class="col-md-2 col-sm-6">
                <div class="p-3 bg-white border rounded-3 text-center h-100 shadow-none">
                    <div class="badge bg-light text-warning fw-bold rounded-circle mb-2" style="width:32px; height:32px; line-height:22px; font-size:14px;">4</div>
                    <strong class="d-block text-dark small">Ujian &amp; Kelulusan</strong>
                    <small class="text-muted" style="font-size: 11px;">Input status Diterima / Ditolak setelah tes.</small>
                </div>
            </div>

            <div class="col-md-3 col-sm-6">
                <div class="p-3 bg-white border rounded-3 text-center h-100 shadow-none">
                    <div class="badge bg-light text-dark fw-bold rounded-circle mb-2" style="width:32px; height:32px; line-height:22px; font-size:14px;">5</div>
                    <strong class="d-block text-dark small">Migrasi ke Siswa Aktif</strong>
                    <small class="text-muted" style="font-size: 11px;">Pindahkan peserta Diterima jadi Siswa Resmi.</small>
                </div>
            </div>
        </div>
    </div>

    <div class="ppdb-stat-grid">
        <?php foreach($cards as $c): ?>
            <?php
            $percent = ppdb_admin_percent($c['value'], $total);
            if($c['title'] == 'Total Pendaftar'){
                $percent = 100;
            }
            ?>
            <a href="<?= base_url($c['url']) ?>" class="ppdb-stat-card <?= $c['class'] ?>">
                <div class="ppdb-stat-top">
                    <div>
                        <div class="ppdb-stat-title"><?= ppdb_admin_e($c['title']) ?></div>
                        <h1 class="ppdb-stat-number"><?= (int)$c['value'] ?></h1>
                    </div>

                    <div class="ppdb-stat-icon">
                        <?= ppdb_admin_e($c['icon']) ?>
                    </div>
                </div>

                <div class="ppdb-stat-desc">
                    <?= ppdb_admin_e($c['desc']) ?>
                </div>

                <div class="ppdb-stat-line">
                    <span style="width:<?= $percent ?>%;"></span>
                </div>
            </a>
        <?php endforeach; ?>
    </div>

    <div class="ppdb-main-grid">

        <div>

            <div class="ppdb-panel-card">
                <div class="ppdb-panel-head">
                    <div>
                        <h5>Pendaftar Terbaru</h5>
                        <small>Daftar peserta terbaru yang masuk ke sistem PPDB.</small>
                    </div>

                    <a href="<?= base_url('admin_ppdb') ?>" class="btn-detail-ppdb">
                        Lihat Semua
                    </a>
                </div>

                <div class="ppdb-table-wrap">
                    <?php if(!empty($terbaru)): ?>
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped datatable nowrap ppdb-table" style="width:100%">
                                <thead class="table-success">
                                    <tr>
                                        <th>No</th>
                                        <th>No Pendaftaran</th>
                                        <th>Nama</th>
                                        <th>NISN</th>
                                        <th>Status</th>
                                        <th>Detail</th>
                                    </tr>
                                </thead>

                                <tbody>
                                    <?php $no=1; foreach($terbaru as $p): ?>
                                        <?php
                                        $nama = !empty($p->nama_lengkap) ? $p->nama_lengkap : '-';
                                        $nisn = !empty($p->nisn) ? $p->nisn : '-';
                                        $no_pendaftaran = !empty($p->no_pendaftaran) ? $p->no_pendaftaran : '-';

                                        if(isset($p->status)){
                                            $status_row = $p->status;
                                        } elseif(isset($p->status_pendaftaran)){
                                            $status_row = $p->status_pendaftaran;
                                        } else {
                                            $status_row = '-';
                                        }

                                        $status_class = 'status-gray';

                                        if(in_array($status_row, ['Diterima', 'Sudah Migrasi'])){
                                            $status_class = 'status-green';
                                        } elseif(in_array($status_row, ['Menunggu Verifikasi', 'Upload Berkas'])){
                                            $status_class = 'status-blue';
                                        } elseif(in_array($status_row, ['Perlu Perbaikan', 'Lengkapi Biodata'])){
                                            $status_class = 'status-orange';
                                        } elseif(in_array($status_row, ['Ditolak'])){
                                            $status_class = 'status-red';
                                        }
                                        ?>

                                        <tr>
                                            <td><?= $no++ ?></td>
                                            <td><?= ppdb_admin_e($no_pendaftaran) ?></td>
                                            <td>
                                                <div class="ppdb-name-cell">
                                                    <strong><?= ppdb_admin_e($nama) ?></strong>
                                                    <small>Calon peserta</small>
                                                </div>
                                            </td>
                                            <td><?= ppdb_admin_e($nisn) ?></td>
                                            <td>
                                                <span class="status-pill <?= $status_class ?>">
                                                    <?= ppdb_admin_e($status_row) ?>
                                                </span>
                                            </td>
                                            <td>
                                                <a href="<?= base_url('admin_ppdb/detail/'.$p->id) ?>" class="btn-detail-ppdb">
                                                    Detail
                                                </a>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php else: ?>
                        <div class="ppdb-empty">
                            Belum ada pendaftar terbaru.
                        </div>
                    <?php endif; ?>
                </div>
            </div>

        </div>

        <div>

            <div class="ppdb-panel-card">
                <div class="ppdb-panel-head">
                    <div>
                        <h5>Akses Cepat</h5>
                        <small>Menu penting pengelolaan PPDB.</small>
                    </div>
                </div>

                <div class="ppdb-panel-body">
                    <div class="ppdb-quick-grid">
                        <a href="<?= base_url('admin_ppdb') ?>" class="ppdb-quick-link">
                            <span>Calon Peserta</span>
                            <span class="ppdb-quick-arrow">›</span>
                        </a>

                        <a href="<?= base_url('admin_ppdb/verifikasi') ?>" class="ppdb-quick-link">
                            <span>Verifikasi Berkas</span>
                            <span class="ppdb-quick-arrow">›</span>
                        </a>

                        <a href="<?= base_url('admin_ppdb/diterima') ?>" class="ppdb-quick-link">
                            <span>Diterima</span>
                            <span class="ppdb-quick-arrow">›</span>
                        </a>

                        <a href="<?= base_url('admin_ppdb/migrasi_data') ?>" class="ppdb-quick-link">
                            <span>Migrasi Data</span>
                            <span class="ppdb-quick-arrow">›</span>
                        </a>

                        <a href="<?= base_url('admin_ppdb/export_all') ?>" class="ppdb-quick-link">
                            <span>Export Semua</span>
                            <span class="ppdb-quick-arrow">›</span>
                        </a>

                        <a href="<?= base_url('admin_ppdb/export_diterima') ?>" class="ppdb-quick-link">
                            <span>Export Diterima</span>
                            <span class="ppdb-quick-arrow">›</span>
                        </a>
						<a href="<?= base_url('admin_ppdb/pengumuman') ?>" class="ppdb-quick-link">
							<span>Pengumuman</span>
							<span class="ppdb-quick-arrow">›</span>
						</a>
                    </div>
                </div>
            </div>

            <div class="ppdb-panel-card">
                <div class="ppdb-panel-head">
                    <div>
                        <h5>Ringkasan Data</h5>
                        <small>Informasi singkat proses PPDB.</small>
                    </div>
                </div>

                <div class="ppdb-panel-body">
                    <div class="ppdb-summary-list">

                        <div class="ppdb-summary-item">
                            <small>Progress diterima</small>
                            <strong><?= ppdb_admin_percent($diterima, $total) ?>% dari total pendaftar</strong>
                        </div>

                        <div class="ppdb-summary-item">
                            <small>Belum migrasi ke siswa</small>
                            <strong><?= $belum_migrasi ?> data</strong>
                        </div>

                        <div class="ppdb-summary-item">
                            <small>Sudah migrasi ke siswa</small>
                            <strong><?= $sudah_migrasi ?> data</strong>
                        </div>

                        <div class="ppdb-summary-item">
                            <small>Status sistem</small>
                            <strong><?= ppdb_admin_e($status_ppdb) ?></strong>
                        </div>

                    </div>
                </div>
            </div>

            <div class="ppdb-panel-card">
                <div class="ppdb-panel-body">
                    <div class="ppdb-danger-box">
                        <h6>Area Perawatan Data</h6>
                        <p>
                            Gunakan menu ini dengan hati-hati. Cleanup hanya menghapus file sementara lama, sedangkan reset akan mengosongkan data PPDB tahun ini.
                        </p>

                        <div class="ppdb-danger-actions">
                            <a href="<?= base_url('admin_ppdb/cleanup_temp') ?>"
                               onclick="return confirm('Jalankan cleanup file temp lebih dari 2 bulan?')"
                               class="ppdb-danger-btn">
                                Cleanup Temp
                            </a>

                            <a href="<?= base_url('admin_ppdb/reset') ?>"
                               onclick="return confirm('Yakin kosongkan seluruh data PPDB dan file temp? Data siswa resmi tidak akan dihapus.')"
                               class="ppdb-danger-btn">
                                Reset PPDB Tahun Ini
                            </a>
                        </div>
                    </div>
                </div>
            </div>

        </div>

    </div>

</div>

</div>

<?php $this->load->view('templates/footer'); ?>