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
        if($total <= 0) return 0;
        return round(($value / $total) * 100);
    }
}

$total             = isset($total) ? (int)$total : 0;
$lengkapi          = isset($lengkapi) ? (int)$lengkapi : 0;
$upload            = isset($upload) ? (int)$upload : 0;
$verifikasi        = isset($verifikasi) ? (int)$verifikasi : 0;
$lulus_verifikasi  = isset($lulus_verifikasi) ? (int)$lulus_verifikasi : 0;
$perbaikan         = isset($perbaikan) ? (int)$perbaikan : 0;
$diterima          = isset($diterima) ? (int)$diterima : 0;
$ditolak           = isset($ditolak) ? (int)$ditolak : 0;
$belum_migrasi     = isset($belum_migrasi) ? (int)$belum_migrasi : 0;
$sudah_migrasi     = isset($sudah_migrasi) ? (int)$sudah_migrasi : 0;

$tahun_ajaran = !empty($settings->tahun_ajaran) ? $settings->tahun_ajaran : date('Y').'/'.(date('Y')+1);
$status_ppdb  = !empty($settings->status_ppdb) ? $settings->status_ppdb : 'Dibuka';

// Statistik Jalur Pendaftaran
$jalur_reguler   = $this->db->where('jalur_pendaftaran', 'Reguler')->count_all_results('ppdb');
$jalur_prestasi  = $this->db->where('jalur_pendaftaran', 'Prestasi')->count_all_results('ppdb');
$jalur_tahfidz   = $this->db->where('jalur_pendaftaran', 'Tahfidz')->count_all_results('ppdb');
$jalur_afirmasi  = $this->db->where('jalur_pendaftaran', 'Afirmasi')->count_all_results('ppdb');
?>

<div class="content">

<style>
    .ppdb-dash-hero {
        background: linear-gradient(135deg, #064e3b 0%, #059669 60%, #10b981 100%);
        border-radius: 24px;
        padding: 28px;
        color: #ffffff;
        box-shadow: 0 14px 34px rgba(5, 150, 105, 0.2);
        margin-bottom: 24px;
        position: relative;
        overflow: hidden;
    }
    .ppdb-dash-hero::after {
        content: "";
        position: absolute;
        top: -60px;
        right: -60px;
        width: 220px;
        height: 220px;
        background: radial-gradient(circle, rgba(255,255,255,0.2) 0%, transparent 70%);
        border-radius: 50%;
    }
    .stat-card-modern {
        background: #ffffff;
        border: 1.5px solid #e2e8f0;
        border-radius: 18px;
        padding: 20px;
        box-shadow: 0 4px 16px rgba(0,0,0,0.04);
        transition: transform 0.2s, box-shadow 0.2s;
        height: 100%;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
    }
    .stat-card-modern:hover {
        transform: translateY(-3px);
        box-shadow: 0 10px 25px rgba(0,0,0,0.08);
        border-color: #cbd5e1;
    }
    .stat-icon-wrap {
        width: 44px;
        height: 44px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 20px;
        flex-shrink: 0;
    }
</style>

<div class="ppdb-admin-page">

    <!-- ═══ 1. HERO BANNER EXECUTIVE ═══ -->
    <div class="ppdb-dash-hero">
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-3">
            <div>
                <span class="badge bg-white text-success fw-bold px-3 py-1 rounded-pill mb-2" style="font-size: 11.5px;">
                    <i class="bi bi-mortarboard-fill me-1"></i> PMB TAHUN AJARAN <?= htmlspecialchars($tahun_ajaran) ?>
                </span>
                <h2 class="fw-bold mb-1 text-white">Dashboard Penerimaan Murid Baru (PMB)</h2>
                <p class="mb-0 text-white-50 small">Pusat monitoring pendaftaran, verifikasi berkas digital, penjadwalan ujian, dan penetapan kelulusan.</p>
            </div>
            <div class="d-flex flex-wrap gap-2">
                <a href="<?= base_url('admin_ppdb') ?>" class="btn btn-light text-success fw-bold rounded-pill px-4 shadow-sm" style="font-size: 13.5px;">
                    <i class="bi bi-people-fill me-1"></i> Data Calon Siswa (<?= $total ?>)
                </a>
                <button type="button" class="btn btn-warning text-dark fw-bold rounded-pill px-3 shadow-sm" data-bs-toggle="modal" data-bs-target="#modalBagiJadwalDash" style="font-size: 13.5px;">
                    <i class="bi bi-calendar-range-fill me-1"></i> Atur Jadwal Tes
                </button>
            </div>
        </div>
    </div>

    <!-- ═══ 2. KARTU STATISTIK SELEKSI UTAMA ═══ -->
    <div class="row g-3 mb-4">
        <!-- Total -->
        <div class="col-xl-3 col-md-6">
            <a href="<?= base_url('admin_ppdb') ?>" class="text-decoration-none">
                <div class="stat-card-modern">
                    <div class="d-flex justify-content-between align-items-start mb-2">
                        <div>
                            <span class="text-muted small fw-bold d-block">TOTAL PENDAFTAR</span>
                            <h2 class="fw-bold text-dark mb-0 mt-1"><?= number_format($total) ?></h2>
                        </div>
                        <div class="stat-icon-wrap bg-success-subtle text-success">
                            <i class="bi bi-people-fill"></i>
                        </div>
                    </div>
                    <small class="text-success fw-bold"><i class="bi bi-arrow-right-circle me-1"></i> Seluruh calon peserta masuk</small>
                </div>
            </a>
        </div>

        <!-- Menunggu Verifikasi -->
        <div class="col-xl-3 col-md-6">
            <a href="<?= base_url('admin_ppdb/verifikasi') ?>" class="text-decoration-none">
                <div class="stat-card-modern" style="border-left: 4px solid #0284c7 !important;">
                    <div class="d-flex justify-content-between align-items-start mb-2">
                        <div>
                            <span class="text-muted small fw-bold d-block">MENUNGGU VERIFIKASI</span>
                            <h2 class="fw-bold text-primary mb-0 mt-1"><?= number_format($verifikasi) ?></h2>
                        </div>
                        <div class="stat-icon-wrap bg-info-subtle text-primary">
                            <i class="bi bi-hourglass-split"></i>
                        </div>
                    </div>
                    <small class="text-muted"><i class="bi bi-file-earmark-check me-1"></i> <?= ppdb_admin_percent($verifikasi, $total) ?>% perlu dicek panitia</small>
                </div>
            </a>
        </div>

        <!-- Lulus Verifikasi / Siap Tes -->
        <div class="col-xl-3 col-md-6">
            <a href="<?= base_url('admin_ppdb?status=Lulus Verifikasi') ?>" class="text-decoration-none">
                <div class="stat-card-modern" style="border-left: 4px solid #059669 !important;">
                    <div class="d-flex justify-content-between align-items-start mb-2">
                        <div>
                            <span class="text-muted small fw-bold d-block">LULUS VERIFIKASI (TES)</span>
                            <h2 class="fw-bold text-success mb-0 mt-1"><?= number_format($lulus_verifikasi) ?></h2>
                        </div>
                        <div class="stat-icon-wrap bg-success-subtle text-success">
                            <i class="bi bi-calendar-check-fill"></i>
                        </div>
                    </div>
                    <small class="text-success fw-bold"><i class="bi bi-printer-fill me-1"></i> Kartu ujian aktif &amp; siap tes</small>
                </div>
            </a>
        </div>

        <!-- Diterima (Lulus) -->
        <div class="col-xl-3 col-md-6">
            <a href="<?= base_url('admin_ppdb/diterima') ?>" class="text-decoration-none">
                <div class="stat-card-modern" style="border-left: 4px solid #16a34a !important;">
                    <div class="d-flex justify-content-between align-items-start mb-2">
                        <div>
                            <span class="text-muted small fw-bold d-block">PESERTA DITERIMA</span>
                            <h2 class="fw-bold text-success mb-0 mt-1"><?= number_format($diterima) ?></h2>
                        </div>
                        <div class="stat-icon-wrap bg-success-subtle text-success">
                            <i class="bi bi-patch-check-fill"></i>
                        </div>
                    </div>
                    <small class="text-muted"><i class="bi bi-box-arrow-in-right me-1"></i> Siap migrasi ke data siswa</small>
                </div>
            </a>
        </div>
    </div>

    <!-- ═══ 3. BREAKDOWN STATUS & DISTRIBUSI JALUR ═══ -->
    <div class="row g-4 mb-4">
        <!-- Distribusi Jalur Pendaftaran -->
        <div class="col-lg-5">
            <div class="card border-0 shadow-sm rounded-4 p-4 h-100 bg-white" style="border: 1.5px solid #e2e8f0 !important;">
                <h5 class="fw-bold text-dark mb-1"><i class="bi bi-pie-chart-fill text-success me-2"></i> Distribusi Jalur Pendaftaran</h5>
                <p class="text-muted small mb-3">Rincian peminatan jalur pendaftaran calon murid baru.</p>

                <div class="d-flex flex-column gap-3">
                    <!-- Reguler -->
                    <div>
                        <div class="d-flex justify-content-between small fw-bold mb-1">
                            <span>Jalur Reguler / Umum</span>
                            <span><?= $jalur_reguler ?> Siswa (<?= ppdb_admin_percent($jalur_reguler, $total) ?>%)</span>
                        </div>
                        <div class="progress" style="height: 9px; border-radius: 6px;">
                            <div class="progress-bar bg-success" style="width: <?= ppdb_admin_percent($jalur_reguler, $total) ?>%;"></div>
                        </div>
                    </div>

                    <!-- Prestasi -->
                    <div>
                        <div class="d-flex justify-content-between small fw-bold mb-1">
                            <span>Jalur Prestasi (Akademik/Non-Akademik)</span>
                            <span><?= $jalur_prestasi ?> Siswa (<?= ppdb_admin_percent($jalur_prestasi, $total) ?>%)</span>
                        </div>
                        <div class="progress" style="height: 9px; border-radius: 6px;">
                            <div class="progress-bar bg-primary" style="width: <?= ppdb_admin_percent($jalur_prestasi, $total) ?>%;"></div>
                        </div>
                    </div>

                    <!-- Tahfidz -->
                    <div>
                        <div class="d-flex justify-content-between small fw-bold mb-1">
                            <span>Jalur Tahfidz Al-Qur'an</span>
                            <span><?= $jalur_tahfidz ?> Siswa (<?= ppdb_admin_percent($jalur_tahfidz, $total) ?>%)</span>
                        </div>
                        <div class="progress" style="height: 9px; border-radius: 6px;">
                            <div class="progress-bar bg-warning" style="width: <?= ppdb_admin_percent($jalur_tahfidz, $total) ?>%;"></div>
                        </div>
                    </div>

                    <!-- Afirmasi -->
                    <div>
                        <div class="d-flex justify-content-between small fw-bold mb-1">
                            <span>Jalur Afirmasi (KIP / PKH / KKS)</span>
                            <span><?= $jalur_afirmasi ?> Siswa (<?= ppdb_admin_percent($jalur_afirmasi, $total) ?>%)</span>
                        </div>
                        <div class="progress" style="height: 9px; border-radius: 6px;">
                            <div class="progress-bar bg-info" style="width: <?= ppdb_admin_percent($jalur_afirmasi, $total) ?>%;"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Status Metrics -->
        <div class="col-lg-7">
            <div class="card border-0 shadow-sm rounded-4 p-4 h-100 bg-white" style="border: 1.5px solid #e2e8f0 !important;">
                <h5 class="fw-bold text-dark mb-1"><i class="bi bi-funnel-fill text-success me-2"></i> Status Alur Administrasi Peserta</h5>
                <p class="text-muted small mb-3">Monitoring kelengkapan berkas dan tahapan calon siswa.</p>

                <div class="row g-3">
                    <div class="col-sm-6">
                        <div class="p-3 rounded-3 bg-light border">
                            <span class="text-muted small fw-bold d-block">1. Tahap Lengkapi Biodata</span>
                            <div class="d-flex justify-content-between align-items-center mt-1">
                                <h4 class="fw-bold text-warning mb-0"><?= $lengkapi ?></h4>
                                <a href="<?= base_url('admin_ppdb?status=Lengkapi Biodata') ?>" class="btn btn-sm btn-outline-secondary rounded-pill px-2 py-0" style="font-size: 11px;">Lihat</a>
                            </div>
                        </div>
                    </div>

                    <div class="col-sm-6">
                        <div class="p-3 rounded-3 bg-light border">
                            <span class="text-muted small fw-bold d-block">2. Tahap Upload Berkas</span>
                            <div class="d-flex justify-content-between align-items-center mt-1">
                                <h4 class="fw-bold text-info mb-0"><?= $upload ?></h4>
                                <a href="<?= base_url('admin_ppdb?status=Upload Berkas') ?>" class="btn btn-sm btn-outline-secondary rounded-pill px-2 py-0" style="font-size: 11px;">Lihat</a>
                            </div>
                        </div>
                    </div>

                    <div class="col-sm-6">
                        <div class="p-3 rounded-3 bg-light border">
                            <span class="text-muted small fw-bold d-block">3. Perlu Perbaikan Berkas</span>
                            <div class="d-flex justify-content-between align-items-center mt-1">
                                <h4 class="fw-bold text-danger mb-0"><?= $perbaikan ?></h4>
                                <a href="<?= base_url('admin_ppdb?status=Perlu Perbaikan') ?>" class="btn btn-sm btn-outline-secondary rounded-pill px-2 py-0" style="font-size: 11px;">Lihat</a>
                            </div>
                        </div>
                    </div>

                    <div class="col-sm-6">
                        <div class="p-3 rounded-3 bg-light border">
                            <span class="text-muted small fw-bold d-block">4. Peserta Ditolak</span>
                            <div class="d-flex justify-content-between align-items-center mt-1">
                                <h4 class="fw-bold text-secondary mb-0"><?= $ditolak ?></h4>
                                <a href="<?= base_url('admin_ppdb/ditolak') ?>" class="btn btn-sm btn-outline-secondary rounded-pill px-2 py-0" style="font-size: 11px;">Lihat</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- ═══ 4. TABEL PENDAFTAR TERBARU (DENGAN TANGGAL DAFTAR) ═══ -->
    <div class="card border-0 shadow-sm rounded-4 p-4 bg-white" style="border: 1.5px solid #e2e8f0 !important;">
        <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
            <div>
                <h5 class="fw-bold text-dark mb-0"><i class="bi bi-clock-history text-success me-2"></i> Pendaftar Terbaru Masuk</h5>
                <small class="text-muted">Daftar calon peserta yang baru saja mendaftar melalui portal PMB.</small>
            </div>
            <a href="<?= base_url('admin_ppdb') ?>" class="btn btn-outline-success btn-sm rounded-pill px-3 fw-bold">
                Lihat Seluruh Data Calon Peserta &rarr;
            </a>
        </div>

        <div class="table-responsive">
            <table class="table table-bordered table-striped align-middle nowrap" style="width: 100%;">
                <thead class="table-success">
                    <tr>
                        <th style="width: 40px;">No</th>
                        <th>Tanggal Daftar</th>
                        <th>Calon Peserta</th>
                        <th>Jalur &amp; Asal Sekolah</th>
                        <th>Kontak WA</th>
                        <th>Status Seleksi</th>
                        <th style="width: 120px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if(!empty($terbaru)): ?>
                        <?php $no=1; foreach($terbaru as $p): ?>
                            <tr>
                                <td><?= $no++ ?></td>

                                <!-- Kolom Tanggal Daftar -->
                                <td>
                                    <?php if(!empty($p->created_at) && $p->created_at != '0000-00-00 00:00:00'): ?>
                                        <strong class="text-dark d-block" style="font-size: 12.5px;">
                                            <i class="bi bi-calendar3 me-1 text-success"></i> <?= date('d M Y', strtotime($p->created_at)) ?>
                                        </strong>
                                        <small class="text-muted" style="font-size: 11px;">
                                            <i class="bi bi-clock me-1"></i> <?= date('H:i', strtotime($p->created_at)) ?> WITA
                                        </small>
                                    <?php else: ?>
                                        <span class="text-muted small">-</span>
                                    <?php endif; ?>
                                </td>

                                <!-- Calon Peserta -->
                                <td>
                                    <strong class="text-dark d-block"><?= htmlspecialchars($p->nama_lengkap ?? '-') ?></strong>
                                    <small class="text-muted">NISN: <?= htmlspecialchars($p->nisn ?? '-') ?> &bull; No: <?= htmlspecialchars($p->no_pendaftaran ?? '-') ?></small>
                                </td>

                                <!-- Jalur & Sekolah -->
                                <td>
                                    <span class="badge bg-light text-success border border-success mb-1">
                                        <?= htmlspecialchars($p->jalur_pendaftaran ?? 'Reguler') ?>
                                    </span>
                                    <div class="small text-muted"><?= htmlspecialchars($p->asal_sekolah ?? '-') ?></div>
                                </td>

                                <!-- Kontak WA -->
                                <td>
                                    <?php 
                                    $clean_hp = preg_replace('/[^0-9]/', '', $p->no_hp ?? '');
                                    if(substr($clean_hp, 0, 1) === '0') $clean_hp = '62' . substr($clean_hp, 1);
                                    ?>
                                    <?php if(!empty($clean_hp)): ?>
                                        <a href="https://wa.me/<?= $clean_hp ?>" target="_blank" class="badge bg-light text-success border border-success text-decoration-none py-1 px-2">
                                            <i class="bi bi-whatsapp"></i> <?= htmlspecialchars($p->no_hp) ?>
                                        </a>
                                    <?php else: ?>
                                        <span class="text-muted small">-</span>
                                    <?php endif; ?>
                                </td>

                                <!-- Status -->
                                <td>
                                    <?php if($p->status == 'Diterima'): ?>
                                        <span class="badge bg-success">Diterima</span>
                                    <?php elseif($p->status == 'Ditolak'): ?>
                                        <span class="badge bg-danger">Ditolak</span>
                                    <?php elseif($p->status == 'Lulus Verifikasi' || $p->status == 'Menuju Tes'): ?>
                                        <span class="badge bg-success">Lulus Verifikasi</span>
                                    <?php elseif($p->status == 'Perlu Perbaikan'): ?>
                                        <span class="badge bg-warning text-dark">Perlu Perbaikan</span>
                                    <?php elseif($p->status == 'Menunggu Verifikasi Berkas'): ?>
                                        <span class="badge bg-primary">Menunggu Verifikasi</span>
                                    <?php else: ?>
                                        <span class="badge bg-secondary"><?= htmlspecialchars($p->status ?? '-') ?></span>
                                    <?php endif; ?>
                                </td>

                                <!-- Aksi -->
                                <td>
                                    <div class="d-flex gap-1">
                                        <a href="<?= base_url('admin_ppdb/detail/'.$p->id) ?>" class="btn btn-sm btn-outline-secondary rounded-pill px-2 py-1" style="font-size: 11px;">
                                            Detail
                                        </a>
                                        <a href="<?= base_url('ppdb/cetak_kartu/'.$p->id) ?>" target="_blank" class="btn btn-sm btn-success rounded-pill px-2 py-1" style="font-size: 11px;">
                                            <i class="bi bi-printer"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="7" class="text-center text-muted py-4">Belum ada data pendaftar masuk.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

</div>

<!-- ═══ MODAL ATUR & BAGIKAN JADWAL TES OTOMATIS (DARI DASHBOARD) ═══ -->
<div class="modal fade" id="modalBagiJadwalDash" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 20px; overflow: hidden;">
            <div class="modal-header bg-warning text-dark px-4 py-3">
                <h5 class="modal-title fw-bold"><i class="bi bi-calendar-check-fill me-2"></i>Atur &amp; Bagikan Jadwal Ujian Masuk PMB</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form method="post" action="<?= base_url('admin_ppdb/generate_jadwal_otomatis') ?>">
                <div class="modal-body p-4 bg-light">
                    <p class="text-muted small mb-3">
                        Fitur cerdas ini akan membagi peserta pendaftaran ke dalam hari-hari ujian seleksi secara otomatis (misalnya <strong>50 peserta per hari</strong>), menerbitkan <strong>Nomor Peserta Tes resmi</strong>, dan mengatur tanggal serta lokasi tes.
                    </p>

                    <div class="card p-3 border-0 shadow-sm rounded-3 mb-3 bg-white">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label fw-bold small text-dark">Tanggal Mulai Ujian Hari Pertama <span class="text-danger">*</span></label>
                                <input type="date" name="tanggal_mulai" class="form-control" value="<?= date('Y-m-d', strtotime('+3 days')) ?>" required>
                                <small class="text-muted" style="font-size: 11px;">Hari pertama pelaksanaan ujian seleksi PMB.</small>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-bold small text-dark">Kapasitas / Kuota Peserta Per Hari <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <input type="number" name="kuota_per_hari" class="form-control" value="50" min="1" max="500" required>
                                    <span class="input-group-text bg-light fw-bold">Peserta / Hari</span>
                                </div>
                                <small class="text-muted" style="font-size: 11px;">Contoh: 50 orang per hari, sisanya otomatis ke hari berikutnya.</small>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-bold small text-dark">Waktu / Jam Pelaksanaan Tes <span class="text-danger">*</span></label>
                                <input type="text" name="jam_tes" class="form-control" value="08:00 - 11.30 WITA" required>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-bold small text-dark">Ruang / Lokasi Tes <span class="text-danger">*</span></label>
                                <input type="text" name="ruang_tes" class="form-control" value="Ruang CBT &amp; Kampus MAN 3 Banjar" required>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-bold small text-dark">Format Awalan (Prefix) No. Peserta Tes</label>
                                <input type="text" name="prefix_nomor" class="form-control font-monospace" value="TES-<?= date('Y') ?>-">
                                <small class="text-muted" style="font-size: 11px;">Hasil: TES-<?= date('Y') ?>-0001, TES-<?= date('Y') ?>-0002, dst.</small>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-bold small text-dark">Target Peserta yang Dijadwalkan</label>
                                <select name="target_peserta" class="form-select">
                                    <option value="lulus_verifikasi">Peserta Lulus Verifikasi (Rekomendasi)</option>
                                    <option value="belum_jadwal">Semua Peserta yang Belum Punya Jadwal</option>
                                    <option value="semua">Semua Peserta Aktif (Otomatis Set Lulus)</option>
                                </select>
                            </div>

                            <div class="col-12">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" name="skip_minggu" value="1" id="skipMingguCheckDash" checked>
                                    <label class="form-check-label fw-bold small" for="skipMingguCheckDash">
                                        Lewati Hari Minggu (Jika jadwal jatuh di hari Minggu, otomatis lompat ke hari Senin)
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer bg-white border-top px-4 py-3">
                    <button type="button" class="btn btn-secondary rounded-pill px-4" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-warning fw-bold rounded-pill px-4 shadow-sm" onclick="return confirm('Jalankan pembagian jadwal ujian otomatis sekarang?')">
                        <i class="bi bi-magic me-1"></i> Generate &amp; Bagikan Jadwal Otomatis
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

</div>

<?php $this->load->view('templates/footer'); ?>