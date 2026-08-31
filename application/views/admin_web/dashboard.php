<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?> - <?= $setting->nama_sekolah ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <style>
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background: #f8fafc; }
        .sidebar { background: #0f172a; min-height: 100vh; color: #fff; }
        .sidebar a { color: #94a3b8; text-decoration: none; padding: 10px 16px; display: flex; align-items: center; gap: 10px; border-radius: 8px; margin-bottom: 4px; font-weight: 600; }
        .sidebar a:hover, .sidebar a.active { background: #1e293b; color: #38bdf8; }
        .card-stat { border: none; border-radius: 16px; box-shadow: 0 4px 20px rgba(0,0,0,0.03); }
    </style>
</head>
<body>
<div class="container-fluid">
    <div class="row">
        <!-- Sidebar -->
        <div class="col-md-2 p-3 sidebar">
            <h5 class="fw-bold text-white mb-4 d-flex align-items-center gap-2">
                <i class="bi bi-clouds-fill text-info"></i> Cloud Admin
            </h5>
            <small class="text-muted d-block mb-2 text-uppercase" style="font-size: 11px;">Website</small>
            <a href="<?= base_url('admin') ?>" class="active"><i class="bi bi-speedometer2"></i> Dashboard</a>
            <a href="<?= base_url('admin/berita') ?>"><i class="bi bi-newspaper"></i> Berita & Info</a>
            <a href="<?= base_url('admin/banner') ?>"><i class="bi bi-images"></i> Banner Header</a>
            <a href="<?= base_url('admin/galeri') ?>"><i class="bi bi-camera-fill"></i> Galeri Foto</a>
            <a href="<?= base_url('admin/setting') ?>"><i class="bi bi-gear-fill"></i> Profil Sekolah</a>

            <small class="text-muted d-block mt-4 mb-2 text-uppercase" style="font-size: 11px;">PMB / PPDB</small>
            <a href="<?= base_url('admin/ppdb') ?>"><i class="bi bi-people-fill"></i> Data Pendaftar</a>
            
            <div class="mt-5 pt-4 border-top border-secondary">
                <a href="<?= base_url() ?>" target="_blank" class="text-info"><i class="bi bi-box-arrow-up-right"></i> Lihat Web</a>
                <a href="<?= base_url('logout') ?>" class="text-danger"><i class="bi bi-box-arrow-left"></i> Keluar</a>
            </div>
        </div>

        <!-- Main Content -->
        <div class="col-md-10 p-4">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h3 class="fw-bold text-dark mb-0">Dashboard Cloud</h3>
                    <p class="text-muted mb-0">Kelola Website Profil &amp; Portal PPDB Online 24/7 (DomaiNesia)</p>
                </div>
                <div class="badge bg-success bg-opacity-10 text-success p-2 px-3 fw-bold rounded-pill">
                    <i class="bi bi-check-circle-fill me-1"></i> Cloud Server Aktif
                </div>
            </div>

            <!-- Stats Grid -->
            <div class="row g-3 mb-4">
                <div class="col-md-4">
                    <div class="card card-stat p-3 bg-white">
                        <div class="d-flex align-items-center gap-3">
                            <div class="p-3 bg-primary bg-opacity-10 text-primary rounded-3 fs-3"><i class="bi bi-person-lines-fill"></i></div>
                            <div>
                                <small class="text-muted fw-bold">TOTAL PENDAFTAR PMB</small>
                                <h3 class="fw-bold text-dark mb-0"><?= $total_pendaftar ?> Siswa</h3>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="card card-stat p-3 bg-white">
                        <div class="d-flex align-items-center gap-3">
                            <div class="p-3 bg-info bg-opacity-10 text-info rounded-3 fs-3"><i class="bi bi-newspaper"></i></div>
                            <div>
                                <small class="text-muted fw-bold">ARTIKEL &amp; BERITA</small>
                                <h3 class="fw-bold text-dark mb-0"><?= $total_berita ?> Pos</h3>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="card card-stat p-3 bg-white">
                        <div class="d-flex align-items-center gap-3">
                            <div class="p-3 bg-success bg-opacity-10 text-success rounded-3 fs-3"><i class="bi bi-arrow-repeat"></i></div>
                            <div>
                                <small class="text-muted fw-bold">REST API SINKRONISASI</small>
                                <h5 class="fw-bold text-success mb-0">Ready (Handshake OK)</h5>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Pendaftar Terbaru -->
            <div class="card card-stat p-4 bg-white">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="fw-bold mb-0">Pendaftar PMB Terbaru Masuk</h5>
                    <a href="<?= base_url('admin/ppdb') ?>" class="btn btn-sm btn-outline-primary fw-bold">Lihat Semua Pendaftar</a>
                </div>

                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>NO PENDAFTARAN</th>
                                <th>NAMA LENGKAP</th>
                                <th>NISN</th>
                                <th>SEKOLAH ASAL</th>
                                <th>TANGGAL DAFTAR</th>
                                <th>STATUS SINKRON LOKAL</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if(!empty($pendaftar_baru)): ?>
                                <?php foreach($pendaftar_baru as $pb): ?>
                                    <tr>
                                        <td><strong class="text-primary font-monospace"><?= $pb->no_pendaftaran ?></strong></td>
                                        <td><strong><?= htmlspecialchars($pb->nama_lengkap) ?></strong></td>
                                        <td><?= htmlspecialchars($pb->nisn) ?></td>
                                        <td><?= htmlspecialchars($pb->sekolah_asal) ?></td>
                                        <td><?= date('d/m/Y H:i', strtotime($pb->created_at)) ?></td>
                                        <td>
                                            <?php if($pb->is_synced): ?>
                                                <span class="badge bg-success bg-opacity-10 text-success fw-bold px-2 py-1"><i class="bi bi-check-all me-1"></i> Sudah Ditarik ke Lokal</span>
                                            <?php else: ?>
                                                <span class="badge bg-warning bg-opacity-20 text-dark fw-bold px-2 py-1"><i class="bi bi-hourglass-split me-1"></i> Menunggu Ditarik Lokal</span>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="6" class="text-center text-muted py-4">Belum ada data pendaftar masuk.</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
