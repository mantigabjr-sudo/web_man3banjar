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
            <a href="<?= base_url('admin') ?>"><i class="bi bi-speedometer2"></i> Dashboard</a>
            <a href="<?= base_url('admin/berita') ?>"><i class="bi bi-newspaper"></i> Berita & Info</a>
            <a href="<?= base_url('admin/banner') ?>"><i class="bi bi-images"></i> Banner Header</a>
            <a href="<?= base_url('admin/galeri') ?>"><i class="bi bi-camera-fill"></i> Galeri Foto</a>
            <a href="<?= base_url('admin/setting') ?>"><i class="bi bi-gear-fill"></i> Profil Sekolah</a>

            <small class="text-muted d-block mt-4 mb-2 text-uppercase" style="font-size: 11px;">PMB / PPDB</small>
            <a href="<?= base_url('admin/ppdb') ?>" class="active"><i class="bi bi-people-fill"></i> Data Pendaftar</a>
            
            <div class="mt-5 pt-4 border-top border-secondary">
                <a href="<?= base_url() ?>" target="_blank" class="text-info"><i class="bi bi-box-arrow-up-right"></i> Lihat Web</a>
                <a href="<?= base_url('logout') ?>" class="text-danger"><i class="bi bi-box-arrow-left"></i> Keluar</a>
            </div>
        </div>

        <!-- Main Content -->
        <div class="col-md-10 p-4">
            <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
                <div>
                    <h3 class="fw-bold text-dark mb-0">Data Pendaftar PMB / PPDB Online</h3>
                    <p class="text-muted mb-0">Tahun Ajaran <?= htmlspecialchars($ppdb_setting->tahun_ajaran) ?></p>
                </div>
                <div class="d-flex gap-2">
                    <span class="badge bg-primary bg-opacity-10 text-primary p-2 px-3 fw-bold rounded-pill">
                        Total Pendaftar: <?= count($pendaftar_list) ?>
                    </span>
                </div>
            </div>

            <div class="card card-stat p-4 bg-white">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th style="width: 40px;">NO</th>
                                <th>NO PENDAFTARAN</th>
                                <th>NAMA LENGKAP</th>
                                <th>NISN</th>
                                <th>JK</th>
                                <th>SEKOLAH ASAL</th>
                                <th>NO HP</th>
                                <th>STATUS DAFTAR</th>
                                <th>SINKRON LOKAL</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if(!empty($pendaftar_list)): ?>
                                <?php $no = 1; ?>
                                <?php foreach($pendaftar_list as $p): ?>
                                    <tr>
                                        <td class="text-muted fw-bold"><?= $no++ ?></td>
                                        <td><strong class="text-primary font-monospace"><?= $p->no_pendaftaran ?></strong></td>
                                        <td><strong><?= htmlspecialchars($p->nama_lengkap) ?></strong></td>
                                        <td><?= htmlspecialchars($p->nisn) ?></td>
                                        <td><span class="badge <?= $p->jenis_kelamin == 'L' ? 'bg-primary' : 'bg-danger' ?>"><?= $p->jenis_kelamin ?></span></td>
                                        <td><?= htmlspecialchars($p->sekolah_asal) ?></td>
                                        <td><?= htmlspecialchars($p->no_hp_siswa ? $p->no_hp_siswa : $p->no_hp_ortu) ?></td>
                                        <td>
                                            <span class="badge bg-info bg-opacity-10 text-info fw-bold"><?= $p->status_pendaftaran ?></span>
                                        </td>
                                        <td>
                                            <?php if($p->is_synced): ?>
                                                <span class="badge bg-success bg-opacity-10 text-success fw-bold px-2 py-1"><i class="bi bi-check-all me-1"></i> Ditarik ke Lokal</span>
                                            <?php else: ?>
                                                <span class="badge bg-warning bg-opacity-20 text-dark fw-bold px-2 py-1"><i class="bi bi-hourglass-split me-1"></i> Belum Ditarik</span>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="9" class="text-center text-muted py-4">Belum ada data calon siswa yang mendaftar.</td>
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
