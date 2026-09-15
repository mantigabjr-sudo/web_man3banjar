<div class="content">
    <div class="container-fluid py-4">

        <!-- ALERT NOTIFIKASI -->
        <?php if($this->session->flashdata('success')): ?>
            <div class="alert alert-success alert-dismissible fade show rounded-4 border-0 shadow-sm mb-4" role="alert">
                <i class="bi bi-check-circle-fill me-2 fs-5"></i>
                <?= $this->session->flashdata('success') ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>
        <?php if($this->session->flashdata('error')): ?>
            <div class="alert alert-danger alert-dismissible fade show rounded-4 border-0 shadow-sm mb-4" role="alert">
                <i class="bi bi-exclamation-triangle-fill me-2 fs-5"></i>
                <?= $this->session->flashdata('error') ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>

        <!-- ═══ HEADER BANNER ═══ -->
        <div class="card border-0 rounded-4 shadow-sm mb-4 text-white position-relative overflow-hidden" 
             style="background: linear-gradient(135deg, #064e3b 0%, #059669 60%, #10b981 100%);">
            <div class="card-body p-4 p-md-5 position-relative" style="z-index: 2;">
                <div class="row align-items-center g-3">
                    <div class="col-lg-7">
                        <span class="badge bg-white text-success fw-bold px-3 py-1 rounded-pill mb-2" style="font-size: 11.5px;">
                            <i class="bi bi-mortarboard-fill me-1"></i> VERIFIKASI FOTO IJAZAH KELAS XII
                        </span>
                        <h2 class="fw-bold mb-2 text-white">Monitoring Verifikasi Mandiri Foto Siswa</h2>
                        <p class="mb-3 text-white-50" style="font-size: 14px; max-width: 620px;">
                            Siswa kelas XII memverifikasi dan memilih fotonya masing-masing melalui portal web. Foto yang terverifikasi otomatis tersimpan dengan format nama <code>{NISN}.jpg</code> siap cetak.
                        </p>
                        <div class="d-flex flex-wrap gap-2 pt-1">
                            <button class="btn btn-light text-dark fw-bold rounded-pill px-3 shadow-sm" data-bs-toggle="modal" data-bs-target="#modalUploadFoto">
                                <i class="bi bi-cloud-arrow-up-fill text-primary me-1"></i> Upload Foto Mentah / ZIP
                            </button>
                            <a href="<?= base_url('admin_foto_ijazah/scan_folder') ?>" class="btn btn-outline-light fw-bold rounded-pill px-3 shadow-sm" onclick="return confirm('Scan seluruh file gambar di folder uploads/foto_ijazah/mentah/?')">
                                <i class="bi bi-arrow-repeat me-1"></i> Scan Folder Mentah
                            </a>
                            <button type="button" class="btn btn-info text-white fw-bold rounded-pill px-3 shadow-sm" id="btnBukaModalSync">
                                <i class="bi bi-images me-1"></i> Sinkron Foto Mentah ke Cloud
                            </button>
                            <button type="button" class="btn btn-warning text-dark fw-bold rounded-pill px-3 shadow-sm" id="btnKirimVerifikasiCloud">
                                <i class="bi bi-cloud-arrow-up-fill me-1"></i> Kirim Verifikasi ke Hosting
                            </button>
                            <button type="button" class="btn btn-light text-dark fw-bold rounded-pill px-3 shadow-sm" id="btnTarikVerifikasiCloud">
                                <i class="bi bi-cloud-arrow-down-fill me-1"></i> Tarik dari Hosting
                            </button>
                            <div class="dropdown d-inline-block">
                                <button class="btn btn-outline-light fw-bold rounded-pill px-3 shadow-sm dropdown-toggle" type="button" id="dropdownDownloadZip" data-bs-toggle="dropdown" aria-expanded="false">
                                    <i class="bi bi-file-earmark-zip-fill me-1"></i> Download ZIP
                                </button>
                                <ul class="dropdown-menu shadow-lg border-0 rounded-4 p-2" aria-labelledby="dropdownDownloadZip" style="min-width: 250px;">
                                    <li><h6 class="dropdown-header text-uppercase text-muted fw-bold" style="font-size: 11px;">Pilihan Kualitas &amp; Ukuran</h6></li>
                                    <li>
                                        <a class="dropdown-item rounded-3 py-2 d-flex align-items-center gap-2" href="<?= base_url('admin_foto_ijazah/download_zip' . (!empty($selected_kelas) ? '?kelas_id='.$selected_kelas.'&compress_1mb=1' : '?compress_1mb=1')) ?>">
                                            <i class="bi bi-lightning-charge-fill text-warning fs-5"></i>
                                            <div>
                                                <strong class="d-block text-dark" style="font-size: 13px;">Maksimal 1 MB (~800 - 950 KB)</strong>
                                                <small class="text-muted" style="font-size: 11px;">Kualitas tajam &amp; pas untuk EMIS/PDUM</small>
                                            </div>
                                        </a>
                                    </li>
                                    <li><hr class="dropdown-divider my-1"></li>
                                    <li>
                                        <a class="dropdown-item rounded-3 py-2 d-flex align-items-center gap-2" href="<?= base_url('admin_foto_ijazah/download_zip' . (!empty($selected_kelas) ? '?kelas_id='.$selected_kelas : '')) ?>">
                                            <i class="bi bi-file-earmark-image text-primary fs-5"></i>
                                            <div>
                                                <strong class="d-block text-dark" style="font-size: 13px;">Ukuran Asli (Original)</strong>
                                                <small class="text-muted" style="font-size: 11px;">Resolusi penuh kamera (2-4 MB)</small>
                                            </div>
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-5">
                        <div class="p-3 bg-white bg-opacity-10 rounded-4 border border-white border-opacity-25 backdrop-blur">
                            <div class="d-flex align-items-center justify-content-between mb-2">
                                <span class="text-white fw-bold small"><i class="bi bi-link-45deg me-1"></i> Tautan Portal Siswa:</span>
                                <button class="btn btn-xs btn-light rounded-pill px-2 py-0 fw-bold" onclick="copyPortalLink()" style="font-size: 11px;">
                                    <i class="bi bi-clipboard me-1"></i> Salin Link
                                </button>
                            </div>
                            <input type="text" id="portalLinkInput" class="form-control form-control-sm bg-white text-dark fw-bold rounded-3" readonly value="<?= base_url('verifikasi_foto_ijazah') ?>">
                            <small class="text-white-50 d-block mt-2" style="font-size: 11px;">
                                Bagikan tautan di atas ke grup WhatsApp kelas XII agar siswa melakukan verifikasi mandiri.
                            </small>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Hiasan Bulat -->
            <div style="position: absolute; right: -40px; top: -40px; width: 180px; height: 180px; background: radial-gradient(circle, rgba(255,255,255,0.2) 0%, transparent 70%); border-radius: 50%;"></div>
        </div>

        <!-- ═══ STATISTIK RINGKASAN ═══ -->
        <div class="row g-3 mb-4">
            <div class="col-md-4">
                <div class="card border-0 rounded-4 shadow-sm h-100 p-3 bg-white border-start border-4 border-primary">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <span class="text-muted small fw-bold text-uppercase">Total Siswa Kelas XII</span>
                            <h3 class="fw-bold text-dark mb-0 mt-1"><?= number_format($total_siswa_xii) ?></h3>
                            <small class="text-muted">Seluruh kelas XII terdaftar</small>
                        </div>
                        <div class="p-3 bg-primary-subtle text-primary rounded-4 fs-3">
                            <i class="bi bi-people-fill"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card border-0 rounded-4 shadow-sm h-100 p-3 bg-white border-start border-4 border-success">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <span class="text-muted small fw-bold text-uppercase">Sudah Terverifikasi</span>
                            <h3 class="fw-bold text-success mb-0 mt-1"><?= number_format($total_verified) ?></h3>
                            <small class="text-success fw-semibold">
                                <?= $total_siswa_xii > 0 ? round(($total_verified / $total_siswa_xii) * 100) : 0 ?>% dari total siswa
                            </small>
                        </div>
                        <div class="p-3 bg-success-subtle text-success rounded-4 fs-3">
                            <i class="bi bi-check-circle-fill"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card border-0 rounded-4 shadow-sm h-100 p-3 bg-white border-start border-4 border-warning">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <span class="text-muted small fw-bold text-uppercase">Foto Mentah Tersedia</span>
                            <h3 class="fw-bold text-warning mb-0 mt-1"><?= number_format($total_pending_photos) ?></h3>
                            <small class="text-muted">Siap diklaim oleh siswa</small>
                        </div>
                        <div class="p-3 bg-warning-subtle text-warning rounded-4 fs-3">
                            <i class="bi bi-images"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- ═══ PROGRES PER KELAS ═══ -->
        <div class="card border-0 rounded-4 shadow-sm mb-4">
            <div class="card-header bg-white border-0 pt-3 pb-0 px-4">
                <h6 class="fw-bold mb-0 text-dark"><i class="bi bi-bar-chart-fill text-success me-2"></i> Progres Verifikasi Per Kelas XII</h6>
            </div>
            <div class="card-body px-4 py-3">
                <div class="row g-3">
                    <?php foreach($rekap_kelas as $rk): ?>
                        <div class="col-md-4 col-sm-6">
                            <div class="p-3 rounded-3 bg-light border">
                                <div class="d-flex justify-content-between align-items-center mb-1">
                                    <span class="fw-bold text-dark"><?= htmlspecialchars($rk['nama_kelas']) ?></span>
                                    <span class="badge bg-success-subtle text-success fw-bold"><?= $rk['verified'] ?> / <?= $rk['total'] ?> Siswa</span>
                                </div>
                                <div class="progress" style="height: 7px;">
                                    <div class="progress-bar bg-success rounded" role="progressbar" style="width: <?= $rk['persen'] ?>%;" aria-valuenow="<?= $rk['persen'] ?>" aria-valuemin="0" aria-valuemax="100"></div>
                                </div>
                                <div class="d-flex justify-content-between align-items-center mt-2" style="font-size: 11px;">
                                    <span class="text-muted"><?= $rk['persen'] ?>% Selesai</span>
                                    <a href="<?= base_url('admin_foto_ijazah?kelas_id='.$rk['id']) ?>" class="text-success text-decoration-none fw-bold">Lihat Siswa &rarr;</a>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>

        <!-- ═══ TABEL & GALERI TABS ═══ -->
        <div class="card border-0 rounded-4 shadow-sm">
            <div class="card-header bg-white border-bottom pt-3 pb-2 px-4">
                <ul class="nav nav-pills card-header-pills" id="fotoTabs" role="tablist">
                    <li class="nav-item">
                        <button class="nav-link active fw-bold rounded-pill px-3" id="tab-siswa-btn" data-bs-toggle="pill" data-bs-target="#tab-siswa" type="button">
                            <i class="bi bi-people-fill me-1"></i> Data Siswa &amp; Verifikasi (<?= count($siswa_list) ?>)
                        </button>
                    </li>
                    <li class="nav-item">
                        <button class="nav-link fw-bold rounded-pill px-3 text-dark" id="tab-mentah-btn" data-bs-toggle="pill" data-bs-target="#tab-mentah" type="button">
                            <i class="bi bi-image me-1"></i> Foto Mentah Belum Diklaim (<?= count($unclaimed_photos) ?>)
                        </button>
                    </li>
                </ul>
            </div>

            <div class="card-body p-4">
                <div class="tab-content" id="fotoTabsContent">

                    <!-- TAB 1: DATA SISWA -->
                    <div class="tab-pane fade show active" id="tab-siswa" role="tabpanel">
                        <!-- Filter Form -->
                        <form method="GET" action="<?= base_url('admin_foto_ijazah') ?>" class="row g-2 mb-3 align-items-center">
                            <div class="col-md-4 col-sm-6">
                                <select name="kelas_id" class="form-select form-select-sm rounded-pill" onchange="this.form.submit()">
                                    <option value="">-- Semua Kelas XII --</option>
                                    <?php foreach($kelas_list as $kl): ?>
                                        <option value="<?= $kl['id'] ?>" <?= ($selected_kelas == $kl['id']) ? 'selected' : '' ?>>
                                            <?= htmlspecialchars($kl['nama_kelas']) ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="col-md-3 col-sm-6">
                                <select name="status" class="form-select form-select-sm rounded-pill" onchange="this.form.submit()">
                                    <option value="">-- Semua Status --</option>
                                    <option value="verified" <?= ($selected_status == 'verified') ? 'selected' : '' ?>>Sudah Terverifikasi</option>
                                    <option value="unverified" <?= ($selected_status == 'unverified') ? 'selected' : '' ?>>Belum Verifikasi</option>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <a href="<?= base_url('admin_foto_ijazah') ?>" class="btn btn-sm btn-outline-secondary rounded-pill w-100">
                                    <i class="bi bi-x-circle me-1"></i> Reset
                                </a>
                            </div>
                        </form>

                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0" style="font-size: 13.5px;">
                                <thead class="table-light">
                                    <tr>
                                        <th style="width: 50px;">No</th>
                                        <th style="width: 70px;">Foto</th>
                                        <th>NISN</th>
                                        <th>Nama Lengkap</th>
                                        <th>Kelas</th>
                                        <th>Status Verifikasi</th>
                                        <th>Waktu Verifikasi</th>
                                        <th style="width: 120px;" class="text-center">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if(empty($siswa_list)): ?>
                                        <tr>
                                            <td colspan="8" class="text-center py-4 text-muted">
                                                <i class="bi bi-inbox fs-1 d-block mb-2"></i>
                                                Tidak ada data siswa yang cocok dengan filter.
                                            </td>
                                        </tr>
                                    <?php else: ?>
                                        <?php $no = 1; foreach($siswa_list as $s): ?>
                                            <tr>
                                                <td><?= $no++ ?></td>
                                                <td>
                                                    <?php if(!empty($s['file_verified']) && file_exists(FCPATH . 'uploads/foto_ijazah/verified/' . $s['file_verified'])): ?>
                                                        <img src="<?= base_url('uploads/foto_ijazah/verified/' . $s['file_verified']) ?>" 
                                                             alt="Foto" class="rounded-3 shadow-sm" style="width: 48px; height: 60px; object-fit: cover; cursor: pointer;"
                                                             onclick="previewModal('<?= base_url('uploads/foto_ijazah/verified/' . $s['file_verified']) ?>', '<?= htmlspecialchars(addslashes($s['nama_lengkap'])) ?>', '<?= $s['nisn'] ?>')">
                                                    <?php else: ?>
                                                        <div class="bg-light rounded-3 d-flex align-items-center justify-content-center text-muted border" style="width: 48px; height: 60px; font-size: 20px;">
                                                            <i class="bi bi-person"></i>
                                                        </div>
                                                    <?php endif; ?>
                                                </td>
                                                <td>
                                                    <span class="badge bg-light text-dark border fw-mono">
                                                        <?= !empty($s['nisn']) ? htmlspecialchars($s['nisn']) : '<span class="text-danger">Belum ada NISN</span>' ?>
                                                    </span>
                                                </td>
                                                <td>
                                                    <strong><?= htmlspecialchars($s['nama_lengkap']) ?></strong>
                                                    <div class="small text-muted">NIS: <?= htmlspecialchars($s['nis'] ?? '-') ?></div>
                                                </td>
                                                <td><span class="badge bg-secondary-subtle text-secondary fw-semibold"><?= htmlspecialchars($s['nama_kelas']) ?></span></td>
                                                <td>
                                                    <?php if(!empty($s['verif_id'])): ?>
                                                        <span class="badge bg-success-subtle text-success fw-bold px-3 py-1 rounded-pill">
                                                            <i class="bi bi-check-circle-fill me-1"></i> Terverifikasi
                                                        </span>
                                                        <div class="small text-muted mt-1" style="font-size: 11px;">
                                                            File: <?= htmlspecialchars($s['file_verified']) ?>
                                                        </div>
                                                    <?php else: ?>
                                                        <span class="badge bg-warning-subtle text-warning fw-bold px-3 py-1 rounded-pill">
                                                            <i class="bi bi-clock-history me-1"></i> Belum Verifikasi
                                                        </span>
                                                    <?php endif; ?>
                                                </td>
                                                <td>
                                                    <?php if(!empty($s['verified_at'])): ?>
                                                        <span class="text-dark small"><?= date('d/m/Y H:i', strtotime($s['verified_at'])) ?></span>
                                                        <div class="small text-muted" style="font-size: 10.5px;">IP: <?= htmlspecialchars($s['ip_address'] ?? '-') ?></div>
                                                    <?php else: ?>
                                                        <span class="text-muted">-</span>
                                                    <?php endif; ?>
                                                </td>
                                                <td class="text-center">
                                                    <?php if(!empty($s['verif_id'])): ?>
                                                        <div class="d-flex justify-content-center gap-1">
                                                            <button type="button" 
                                                                    class="btn btn-outline-primary btn-sm rounded-pill px-2 py-1" 
                                                                    title="Ganti dengan foto lain"
                                                                    onclick="openPilihFotoModal(<?= $s['siswa_id'] ?>, '<?= htmlspecialchars(addslashes($s['nama_lengkap'])) ?>', '<?= htmlspecialchars($s['nisn'] ?? '-') ?>', '<?= htmlspecialchars($s['nama_kelas']) ?>')">
                                                                <i class="bi bi-arrow-repeat"></i> Ganti
                                                            </button>
                                                            <a href="<?= base_url('admin_foto_ijazah/reset_klaim/' . $s['verif_id']) ?>" 
                                                               class="btn btn-outline-danger btn-sm rounded-pill px-2 py-1" 
                                                               title="Batalkan Verifikasi Siswa Ini"
                                                               onclick="return confirm('Apakah Anda yakin ingin membatalkan verifikasi foto untuk <?= htmlspecialchars(addslashes($s['nama_lengkap'])) ?>? Foto akan dikembalikan ke galeri belum diklaim.');">
                                                                <i class="bi bi-arrow-counterclockwise"></i>
                                                            </a>
                                                        </div>
                                                    <?php else: ?>
                                                        <button type="button" 
                                                                class="btn btn-primary btn-sm rounded-pill px-3 py-1 fw-bold shadow-sm"
                                                                onclick="openPilihFotoModal(<?= $s['siswa_id'] ?>, '<?= htmlspecialchars(addslashes($s['nama_lengkap'])) ?>', '<?= htmlspecialchars($s['nisn'] ?? '-') ?>', '<?= htmlspecialchars($s['nama_kelas']) ?>')">
                                                            <i class="bi bi-person-check-fill me-1"></i> Verifikasi
                                                        </button>
                                                    <?php endif; ?>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- TAB 2: FOTO MENTAH BELUM DIKLAIM -->
                    <div class="tab-pane fade" id="tab-mentah" role="tabpanel">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <span class="text-muted small">Total <strong><?= count($unclaimed_photos) ?></strong> foto mentah di folder server yang belum diklaim siswa.</span>
                            <button class="btn btn-sm btn-primary rounded-pill px-3" data-bs-toggle="modal" data-bs-target="#modalUploadFoto">
                                <i class="bi bi-plus-circle me-1"></i> Tambah Foto Baru
                            </button>
                        </div>

                        <?php if(empty($unclaimed_photos)): ?>
                            <div class="text-center py-5 text-muted">
                                <i class="bi bi-images fs-1 d-block mb-2 text-muted"></i>
                                Tidak ada foto mentah yang belum diklaim. Semua foto telah terverifikasi atau belum diunggah.
                            </div>
                        <?php else: ?>
                            <div class="row g-3">
                                <?php foreach($unclaimed_photos as $up): ?>
                                    <div class="col-xl-2 col-lg-3 col-md-4 col-6">
                                        <div class="card h-100 border rounded-3 overflow-hidden shadow-sm">
                                            <div style="height: 180px; overflow: hidden; background: #f8fafc; position: relative;">
                                                <img src="<?= base_url('uploads/foto_ijazah/mentah/' . $up['file_mentah']) ?>" 
                                                     alt="Foto Mentah" style="width: 100%; height: 100%; object-fit: cover; cursor: pointer;"
                                                     onclick="previewModal('<?= base_url('uploads/foto_ijazah/mentah/' . $up['file_mentah']) ?>', '<?= htmlspecialchars(addslashes($up['file_mentah'])) ?>', 'Belum Diklaim')">
                                            </div>
                                            <div class="p-2 text-center bg-white">
                                                <small class="text-truncate d-block fw-semibold mb-1" style="font-size: 11px;" title="<?= htmlspecialchars($up['file_mentah']) ?>">
                                                    <?= htmlspecialchars($up['file_mentah']) ?>
                                                </small>
                                                <button type="button" 
                                                        class="btn btn-sm btn-outline-success rounded-pill px-2 py-1 w-100 fw-bold mb-1" 
                                                        style="font-size: 11px;"
                                                        onclick="openPasangKeSiswaModal(<?= $up['id'] ?>, '<?= htmlspecialchars(addslashes($up['file_mentah'])) ?>', '<?= base_url('uploads/foto_ijazah/mentah/' . $up['file_mentah']) ?>')">
                                                    <i class="bi bi-person-plus-fill me-1"></i> Pasangkan ke Siswa
                                                </button>
                                                <a href="<?= base_url('admin_foto_ijazah/hapus_mentah/' . $up['id']) ?>" 
                                                   class="btn btn-link text-danger p-0" style="font-size: 11px;"
                                                   onclick="return confirm('Hapus file foto mentah ini dari server?');">
                                                    <i class="bi bi-trash"></i> Hapus
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>
                    </div>

                </div>
            </div>
        </div>

    </div>
</div>

<!-- ═══ MODAL UPLOAD FOTO MENTAH ═══ -->
<div class="modal fade" id="modalUploadFoto" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content rounded-4 border-0 shadow">
            <div class="modal-header border-bottom">
                <h5 class="modal-title fw-bold text-dark"><i class="bi bi-cloud-arrow-up-fill text-primary me-2"></i> Upload Foto Mentah</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <?= form_open_multipart('admin_foto_ijazah/upload_foto') ?>
            <div class="modal-body p-4">
                <div class="alert alert-info border-0 rounded-3 small mb-3">
                    <i class="bi bi-info-circle-fill me-1 text-primary"></i>
                    <strong>Kapasitas Diperbesar:</strong> Batas unggah file ZIP/foto kini hingga <strong>1 GB</strong> (dan hingga 500 file sekaligus). 
                </div>

                <div class="p-3 bg-light rounded-3 border mb-3">
                    <label class="form-label fw-bold text-success mb-1">
                        <i class="bi bi-star-fill text-warning me-1"></i> Cara Paling Cepat &amp; Praktis (Copy Folder):
                    </label>
                    <p class="small text-muted mb-2" style="font-size: 12px;">
                        Copy-paste file foto langsung ke folder server:
                        <code class="d-block p-1 bg-white border rounded my-1 text-dark select-all">e:\KHAIDIR\WEB\labsys\uploads\foto_ijazah\mentah\</code>
                        Lalu klik tombol <strong>"Scan Folder Mentah"</strong>. Semua foto langsung terbaca tanpa proses upload web!
                    </p>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-bold small text-dark"><i class="bi bi-file-earmark-zip-fill text-primary me-1"></i> Opsi 1: Upload File Arsip ZIP (Hingga 1 GB):</label>
                    <input type="file" name="zip_file" class="form-control rounded-3" accept=".zip">
                    <small class="text-muted" style="font-size: 11px;">Jadikan foto-foto Anda satu file .zip, lalu upload di sini. Sistem otomatis mengekstrak seluruh isinya.</small>
                </div>

                <div class="text-center text-muted fw-bold small my-2">-- ATAU --</div>

                <div class="mb-3">
                    <label class="form-label fw-bold small text-dark"><i class="bi bi-images text-secondary me-1"></i> Opsi 2: Upload Gambar Langsung (Hingga 500 Foto Sekaligus):</label>
                    <input type="file" name="foto_files[]" class="form-control rounded-3" accept="image/*" multiple>
                    <small class="text-muted" style="font-size: 11px;">Pilih banyak foto sekaligus (Ctrl+A atau seleksi foto) lalu klik Mulai Unggah.</small>
                </div>
            </div>
            <div class="modal-footer border-top bg-light rounded-bottom-4">
                <button type="button" class="btn btn-light rounded-pill px-3" data-bs-dismiss="modal">Batal</button>
                <button type="submit" class="btn btn-primary rounded-pill px-4 fw-bold">
                    <i class="bi bi-upload me-1"></i> Mulai Unggah
                </button>
            </div>
            <?= form_close() ?>
        </div>
    </div>
</div>

<!-- ═══ MODAL PREVIEW GAMBAR BESAR ═══ -->
<div class="modal fade" id="modalPreviewImage" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-sm">
        <div class="modal-content rounded-4 border-0 shadow overflow-hidden">
            <div class="modal-header py-2 px-3 bg-light border-0">
                <h6 class="modal-title fw-bold text-dark" id="previewTitle" style="font-size: 13px;">Pratinjau</h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-2 text-center bg-dark">
                <img id="previewSrc" src="" alt="Pratinjau" class="img-fluid rounded" style="max-height: 420px; object-fit: contain;">
            </div>
            <div class="modal-footer py-2 px-3 bg-light border-0 justify-content-between">
                <span class="badge bg-success" id="previewNisn">-</span>
                <button type="button" class="btn btn-sm btn-secondary rounded-pill px-3" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>

<!-- ═══ MODAL PILIH FOTO UNTUK SISWA (DARI TABEL SISWA) ═══ -->
<div class="modal fade" id="modalPilihFotoUntukSiswa" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content rounded-4 border-0 shadow overflow-hidden">
            <div class="modal-header py-3 px-4 bg-light border-bottom">
                <div>
                    <h6 class="modal-title fw-bold text-dark mb-0">
                        <i class="bi bi-person-check-fill text-primary me-2"></i>
                        Verifikasi Foto Langsung: <span id="modalTargetSiswaNama" class="text-success">-</span>
                    </h6>
                    <small class="text-muted" style="font-size: 12px;">
                        Kelas: <strong id="modalTargetSiswaKelas">-</strong> &bull; NISN: <strong id="modalTargetSiswaNisn">-</strong>
                    </small>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <?= form_open('admin_foto_ijazah/verifikasi_langsung') ?>
            <input type="hidden" name="siswa_id" id="modalTargetSiswaId" value="">
            <input type="hidden" name="foto_id" id="modalTargetFotoId" value="">

            <div class="modal-body p-4">
                <div class="alert alert-info border-0 rounded-3 small py-2 px-3 mb-3 d-flex align-items-center justify-content-between">
                    <span>
                        <i class="bi bi-info-circle-fill me-1"></i> Klik pada salah satu foto di bawah ini untuk memasangkannya ke siswa ini.
                    </span>
                    <span id="labelFotoTerpilih" class="badge bg-secondary fw-normal">Belum ada foto dipilih</span>
                </div>

                <div class="mb-3">
                    <div class="input-group input-group-sm">
                        <span class="input-group-text bg-light border-end-0 rounded-start-pill"><i class="bi bi-search"></i></span>
                        <input type="text" id="searchPhotoInModal" class="form-control border-start-0 rounded-end-pill" placeholder="Cari nama file foto..." oninput="filterModalPhotos(this.value)">
                    </div>
                </div>

                <?php if(empty($unclaimed_photos)): ?>
                    <div class="text-center py-5 text-muted border rounded-3 bg-light">
                        <i class="bi bi-image fs-1 d-block mb-2 text-muted"></i>
                        Tidak ada foto mentah yang tersedia. Silakan unggah foto mentah terlebih dahulu melalui tombol <strong>Upload Foto Mentah / ZIP</strong>.
                    </div>
                <?php else: ?>
                    <div class="row g-2" id="gridModalPhotos" style="max-height: 380px; overflow-y: auto; padding-right: 4px;">
                        <?php foreach($unclaimed_photos as $up): ?>
                            <div class="col-lg-2 col-md-3 col-4 modal-photo-item" data-filename="<?= strtolower($up['file_mentah']) ?>">
                                <div class="card h-100 border rounded-3 overflow-hidden shadow-sm card-selectable-photo" 
                                     id="cardPhoto_<?= $up['id'] ?>"
                                     style="cursor: pointer; transition: all 0.2s;"
                                     onclick="selectPhotoForStudent(<?= $up['id'] ?>, '<?= htmlspecialchars(addslashes($up['file_mentah'])) ?>')">
                                    <div style="height: 120px; overflow: hidden; background: #0f172a; position: relative;">
                                        <img src="<?= base_url('uploads/foto_ijazah/mentah/' . $up['file_mentah']) ?>" 
                                             alt="Foto Mentah" style="width: 100%; height: 100%; object-fit: cover;" loading="lazy">
                                        <span class="badge-checked position-absolute top-0 end-0 m-1 badge bg-success rounded-pill d-none">
                                            <i class="bi bi-check-lg"></i>
                                        </span>
                                    </div>
                                    <div class="p-1 text-center bg-white">
                                        <small class="text-truncate d-block" style="font-size: 10.5px;" title="<?= htmlspecialchars($up['file_mentah']) ?>">
                                            <?= htmlspecialchars($up['file_mentah']) ?>
                                        </small>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>

            <div class="modal-footer border-top bg-light rounded-bottom-4 py-2 px-4 justify-content-between">
                <button type="button" class="btn btn-sm btn-light rounded-pill px-3" data-bs-dismiss="modal">Batal</button>
                <button type="submit" id="btnSubmitPilihFoto" class="btn btn-sm btn-success rounded-pill px-4 fw-bold shadow-sm" disabled>
                    <i class="bi bi-check-circle-fill me-1"></i> Tetapkan &amp; Verifikasi Foto Ini
                </button>
            </div>
            <?= form_close() ?>
        </div>
    </div>
</div>

<!-- ═══ MODAL PASANGKAN FOTO KE SISWA (DARI TAB FOTO MENTAH) ═══ -->
<div class="modal fade" id="modalPasangFotoKeSiswa" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content rounded-4 border-0 shadow overflow-hidden">
            <div class="modal-header py-3 px-4 bg-light border-bottom">
                <h6 class="modal-title fw-bold text-dark mb-0">
                    <i class="bi bi-person-plus-fill text-success me-2"></i>
                    Pasangkan Foto Ini ke Siswa
                </h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <?= form_open('admin_foto_ijazah/verifikasi_langsung') ?>
            <input type="hidden" name="foto_id" id="modalPasangFotoId" value="">

            <div class="modal-body p-4">
                <div class="d-flex gap-3 align-items-center mb-4 p-3 bg-light rounded-3 border">
                    <div style="width: 70px; height: 90px; border-radius: 8px; overflow: hidden; background: #000; flex-shrink: 0;">
                        <img id="modalPasangFotoPreview" src="" alt="Pratinjau" style="width: 100%; height: 100%; object-fit: cover;">
                    </div>
                    <div class="overflow-hidden">
                        <small class="text-muted d-block" style="font-size: 11px;">File Mentah:</small>
                        <strong id="modalPasangFotoFilename" class="text-dark d-block text-truncate" style="font-size: 13px;">-</strong>
                        <span class="badge bg-warning-subtle text-warning fw-bold mt-1" style="font-size: 11px;">Belum Terverifikasi</span>
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-bold small text-dark">Pilih Siswa yang Sesuai dengan Foto Ini:</label>
                    <select name="siswa_id" id="selectPasangSiswa" class="form-select rounded-3" required>
                        <option value="">-- Pilih Siswa Kelas XII --</option>
                        
                        <?php if(!empty($all_unverified_siswa)): ?>
                            <optgroup label="⭐ Siswa Belum Verifikasi Foto (<?= count($all_unverified_siswa) ?> siswa)">
                                <?php foreach($all_unverified_siswa as $us): ?>
                                    <option value="<?= $us['id'] ?>">
                                        [<?= $us['nama_kelas'] ?>] <?= !empty($us['nisn']) ? $us['nisn'] . ' - ' : '' ?><?= htmlspecialchars($us['nama_lengkap']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </optgroup>
                        <?php endif; ?>

                        <?php if(!empty($siswa_list)): ?>
                            <optgroup label="Semua Siswa Kelas XII (Termasuk yang Ingin Ganti Foto)">
                                <?php foreach($siswa_list as $sl): ?>
                                    <option value="<?= $sl['siswa_id'] ?>">
                                        [<?= $sl['nama_kelas'] ?>] <?= !empty($sl['nisn']) ? $sl['nisn'] . ' - ' : '' ?><?= htmlspecialchars($sl['nama_lengkap']) ?> <?= !empty($sl['verif_id']) ? '(Sudah Ada Foto)' : '' ?>
                                    </option>
                                <?php endforeach; ?>
                            </optgroup>
                        <?php endif; ?>
                    </select>
                    <small class="text-muted mt-1 d-block" style="font-size: 11px;">
                        <i class="bi bi-info-circle me-1"></i> Foto otomatis disimpan dengan nama resmi <code>{NISN}.jpg</code>.
                    </small>
                </div>
            </div>

            <div class="modal-footer border-top bg-light rounded-bottom-4 py-2 px-4 justify-content-between">
                <button type="button" class="btn btn-sm btn-light rounded-pill px-3" data-bs-dismiss="modal">Batal</button>
                <button type="submit" class="btn btn-sm btn-success rounded-pill px-4 fw-bold shadow-sm">
                    <i class="bi bi-check-circle-fill me-1"></i> Simpan Verifikasi
                </button>
            </div>
            <?= form_close() ?>
        </div>
    </div>
</div>

<script>
function copyPortalLink(){
    const input = document.getElementById('portalLinkInput');
    input.select();
    input.setSelectionRange(0, 99999);
    navigator.clipboard.writeText(input.value);
    alert('Tautan portal siswa berhasil disalin ke clipboard!');
}

function previewModal(url, title, nisn){
    document.getElementById('previewSrc').src = url;
    document.getElementById('previewTitle').innerText = title;
    document.getElementById('previewNisn').innerText = nisn ? 'NISN: ' + nisn : '';
    const modal = new bootstrap.Modal(document.getElementById('modalPreviewImage'));
    modal.show();
}

// ═══ LOGIKA MODAL PILIH FOTO UNTUK SISWA ═══
function openPilihFotoModal(siswaId, nama, nisn, kelas){
    document.getElementById('modalTargetSiswaId').value = siswaId;
    document.getElementById('modalTargetFotoId').value = '';
    document.getElementById('modalTargetSiswaNama').innerText = nama;
    document.getElementById('modalTargetSiswaKelas').innerText = kelas;
    document.getElementById('modalTargetSiswaNisn').innerText = nisn;
    
    document.getElementById('labelFotoTerpilih').className = 'badge bg-secondary fw-normal';
    document.getElementById('labelFotoTerpilih').innerText = 'Belum ada foto dipilih';
    document.getElementById('btnSubmitPilihFoto').disabled = true;

    // Reset seleksi kartu
    document.querySelectorAll('.card-selectable-photo').forEach(card => {
        card.style.border = '1px solid #dee2e6';
        card.querySelector('.badge-checked')?.classList.add('d-none');
    });

    const modal = new bootstrap.Modal(document.getElementById('modalPilihFotoUntukSiswa'));
    modal.show();
}

function selectPhotoForStudent(fotoId, filename){
    document.getElementById('modalTargetFotoId').value = fotoId;

    // Reset kartu lain
    document.querySelectorAll('.card-selectable-photo').forEach(card => {
        card.style.border = '1px solid #dee2e6';
        card.querySelector('.badge-checked')?.classList.add('d-none');
    });

    // Aktifkan kartu terpilih
    const activeCard = document.getElementById('cardPhoto_' + fotoId);
    if(activeCard){
        activeCard.style.border = '2.5px solid #10b981';
        activeCard.querySelector('.badge-checked')?.classList.remove('d-none');
    }

    const lbl = document.getElementById('labelFotoTerpilih');
    lbl.className = 'badge bg-success fw-bold';
    lbl.innerText = 'Foto Terpilih: ' + filename;

    document.getElementById('btnSubmitPilihFoto').disabled = false;
}

function filterModalPhotos(keyword){
    keyword = keyword.toLowerCase().trim();
    const items = document.querySelectorAll('.modal-photo-item');
    items.forEach(item => {
        const fn = item.getAttribute('data-filename') || '';
        item.style.display = fn.includes(keyword) ? '' : 'none';
    });
}

// ═══ LOGIKA MODAL PASANGKAN FOTO KE SISWA ═══
function openPasangKeSiswaModal(fotoId, filename, previewUrl){
    document.getElementById('modalPasangFotoId').value = fotoId;
    document.getElementById('modalPasangFotoFilename').innerText = filename;
    document.getElementById('modalPasangFotoPreview').src = previewUrl;
    document.getElementById('selectPasangSiswa').value = '';

    const modal = new bootstrap.Modal(document.getElementById('modalPasangFotoKeSiswa'));
    modal.show();
}

// ═══════════════════════════════════════════════════════════════════════
// SINKRONISASI FOTO MENTAH DENGAN CLOUD HOSTING VIA AJAX BATCH
// ═══════════════════════════════════════════════════════════════════════

let unsyncedList = [];
let syncInProgress = false;

document.getElementById('btnBukaModalSync')?.addEventListener('click', function(){
    const modal = new bootstrap.Modal(document.getElementById('modalSyncFotoHosting'));
    modal.show();

    // Reset UI
    document.getElementById('syncCheckingBox').classList.remove('d-none');
    document.getElementById('syncActionBox').classList.add('d-none');
    document.getElementById('btnStartSync').disabled = true;
    document.getElementById('syncLocalCount').innerText = '-';
    document.getElementById('syncCloudCount').innerText = '-';
    document.getElementById('syncUnsyncedCount').innerText = '-';
    document.getElementById('syncLogBox').innerHTML = '<div>[System] Memeriksa status file di server hosting...</div>';

    fetch('<?= base_url("admin_foto_ijazah/ajax_cek_sync_mentah") ?>')
        .then(res => res.json())
        .then(data => {
            document.getElementById('syncCheckingBox').classList.add('d-none');
            document.getElementById('syncActionBox').classList.remove('d-none');

            if(data.status === 'success'){
                document.getElementById('syncLocalCount').innerText = data.total_local;
                document.getElementById('syncCloudCount').innerText = data.total_cloud;
                document.getElementById('syncUnsyncedCount').innerText = data.unsynced_count;

                unsyncedList = data.unsynced_files || [];

                if(unsyncedList.length > 0){
                    document.getElementById('btnStartSync').disabled = false;
                    document.getElementById('syncNoticeText').innerHTML = 'Terdapat <strong>' + unsyncedList.length + ' foto</strong> yang belum ada di server hosting. Siap disinkronkan.';
                    appendSyncLog('[Ready] ' + unsyncedList.length + ' foto perlu diunggah. Klik Mulai Sinkronkan.');
                } else {
                    document.getElementById('btnStartSync').disabled = true;
                    document.getElementById('syncNoticeText').innerHTML = '🎉 <strong>Seluruh foto sudah lengkap tersinkron di server hosting!</strong> Tidak ada file baru yang perlu diunggah.';
                    appendSyncLog('[Success] Semua foto di lokal sudah ada di server hosting.');
                }
            } else {
                appendSyncLog('[Error] ' + (data.message || 'Gagal memeriksa server hosting.'));
                alert(data.message || 'Gagal menghubungi server hosting.');
            }
        })
        .catch(err => {
            document.getElementById('syncCheckingBox').classList.add('d-none');
            document.getElementById('syncActionBox').classList.remove('d-none');
            appendSyncLog('[Error] Terjadi kesalahan koneksi internet atau server lokal.');
        });
});

function appendSyncLog(msg){
    const box = document.getElementById('syncLogBox');
    const line = document.createElement('div');
    line.innerText = msg;
    box.appendChild(line);
    box.scrollTop = box.scrollHeight;
}

// Mulai proses upload satu per satu
document.getElementById('btnStartSync')?.addEventListener('click', async function(){
    if(unsyncedList.length === 0 || syncInProgress) return;

    syncInProgress = true;
    this.disabled = true;
    document.getElementById('btnCloseModalSync').disabled = true;
    document.getElementById('btnBatalSync').disabled = true;

    const progressWrapper = document.getElementById('syncProgressWrapper');
    const progressBar = document.getElementById('syncProgressBar');
    const progressLabel = document.getElementById('syncProgressLabel');
    const progressPercent = document.getElementById('syncProgressPercent');

    progressWrapper.classList.remove('d-none');

    const total = unsyncedList.length;
    let suksesCount = 0;
    let gagalCount = 0;

    for(let i = 0; i < total; i++){
        const filename = unsyncedList[i];
        const currentNum = i + 1;
        const pct = Math.round((currentNum / total) * 100);

        progressLabel.innerText = 'Mengunggah (' + currentNum + '/' + total + '): ' + filename;
        progressPercent.innerText = pct + '%';
        progressBar.style.width = pct + '%';

        appendSyncLog('(' + currentNum + '/' + total + ') Mengirim ' + filename + '...');

        try {
            const formData = new FormData();
            formData.append('filename', filename);

            const res = await fetch('<?= base_url("admin_foto_ijazah/ajax_upload_single_mentah") ?>', {
                method: 'POST',
                body: formData
            });
            const result = await res.json();

            if(result.status === 'success'){
                suksesCount++;
                appendSyncLog('  -> [OK] ' + filename + ' berhasil disimpan di hosting.');
            } else {
                gagalCount++;
                appendSyncLog('  -> [GAGAL] ' + filename + ': ' + (result.message || 'Error'));
            }
        } catch(e){
            gagalCount++;
            appendSyncLog('  -> [ERROR] ' + filename + ': Masalah jaringan.');
        }
    }

    syncInProgress = false;
    document.getElementById('btnCloseModalSync').disabled = false;
    document.getElementById('btnBatalSync').disabled = false;

    progressLabel.innerText = 'Selesai: ' + suksesCount + ' berhasil, ' + gagalCount + ' gagal.';
    appendSyncLog('══════════════════════════════════════════');
    appendSyncLog('🎉 PROSES SINKRONISASI SELESAI!');
    appendSyncLog('Berhasil diunggah ke hosting: ' + suksesCount + ' file.');
    if(gagalCount > 0){
        appendSyncLog('Gagal: ' + gagalCount + ' file.');
    }

    alert('🎉 Sinkronisasi selesai! ' + suksesCount + ' file foto berhasil diunggah ke server hosting.');
    location.reload();
});

// ═══════════════════════════════════════════════════════════════════════
// TARIK HASIL VERIFIKASI DARI CLOUD HOSTING KE LOKAL
// ═══════════════════════════════════════════════════════════════════════
document.getElementById('btnTarikVerifikasiCloud')?.addEventListener('click', function(){
    if(!confirm('Tarik data siswa dan foto resmi ({NISN}.jpg) yang sudah diverifikasi siswa di website online?')) return;

    const btn = this;
    const origHtml = btn.innerHTML;
    btn.disabled = true;
    btn.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span> Menarik data...';

    fetch('<?= base_url("admin_foto_ijazah/ajax_pull_verified_cloud") ?>')
        .then(res => res.json())
        .then(data => {
            btn.disabled = false;
            btn.innerHTML = origHtml;

            if(data.status === 'success'){
                alert(data.message || 'Berhasil menarik data verifikasi dari cloud!');
                location.reload();
            } else {
                alert(data.message || 'Gagal menarik data dari server hosting.');
            }
        })
        .catch(err => {
            btn.disabled = false;
            btn.innerHTML = origHtml;
            alert('Terjadi kesalahan koneksi saat menarik data dari hosting.');
        });
});

// ═══════════════════════════════════════════════════════════════════════
// KIRIM HASIL VERIFIKASI DARI LOKAL KE CLOUD HOSTING
// ═══════════════════════════════════════════════════════════════════════
document.getElementById('btnKirimVerifikasiCloud')?.addEventListener('click', function(){
    if(!confirm('Kirim seluruh data siswa yang sudah diverifikasi di komputer lokal ini ke website online man3banjar.sch.id?')) return;

    const btn = this;
    const origHtml = btn.innerHTML;
    btn.disabled = true;
    btn.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span> Mengirim data...';

    fetch('<?= base_url("admin_foto_ijazah/ajax_push_verified_cloud") ?>')
        .then(res => res.json())
        .then(data => {
            btn.disabled = false;
            btn.innerHTML = origHtml;

            if(data.status === 'success'){
                alert(data.message || 'Berhasil menyinkronkan data verifikasi ke hosting!');
            } else {
                alert(data.message || 'Gagal mengirim data ke server hosting.');
            }
        })
        .catch(err => {
            btn.disabled = false;
            btn.innerHTML = origHtml;
            alert('Terjadi kesalahan koneksi saat mengirim data ke hosting.');
        });
});
</script>

<!-- ═══ MODAL SINKRONISASI FOTO KE HOSTING ═══ -->
<div class="modal fade" id="modalSyncFotoHosting" tabindex="-1" aria-labelledby="modalSyncFotoHostingLabel" aria-hidden="true" data-bs-backdrop="static">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content rounded-4 border-0 shadow">
            <div class="modal-header bg-success text-white rounded-top-4 p-4">
                <div class="d-flex align-items-center gap-2">
                    <i class="bi bi-cloud-arrow-up-fill fs-4"></i>
                    <div>
                        <h5 class="modal-title fw-bold mb-0" id="modalSyncFotoHostingLabel">Sinkronisasi Foto Mentah ke Cloud Hosting</h5>
                        <small class="text-white-50">Kirim foto hasil kamera dari komputer lokal ke website man3banjar.sch.id</small>
                    </div>
                </div>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close" id="btnCloseModalSync"></button>
            </div>
            <div class="modal-body p-4">
                
                <!-- Status Box Info -->
                <div class="row g-3 mb-4">
                    <div class="col-md-4">
                        <div class="p-3 bg-light rounded-4 border text-center">
                            <span class="text-muted small fw-bold d-block text-uppercase">Foto di Komputer Ini</span>
                            <h3 class="fw-bold text-dark mb-0 mt-1" id="syncLocalCount">-</h3>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="p-3 bg-light rounded-4 border text-center">
                            <span class="text-muted small fw-bold d-block text-uppercase">Sudah di Hosting</span>
                            <h3 class="fw-bold text-success mb-0 mt-1" id="syncCloudCount">-</h3>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="p-3 bg-warning bg-opacity-10 rounded-4 border border-warning text-center">
                            <span class="text-warning-emphasis small fw-bold d-block text-uppercase">Belum Dikirim</span>
                            <h3 class="fw-bold text-warning-emphasis mb-0 mt-1" id="syncUnsyncedCount">-</h3>
                        </div>
                    </div>
                </div>

                <!-- Loading State saat Cek Server -->
                <div id="syncCheckingBox" class="text-center py-4">
                    <div class="spinner-border text-success mb-2" role="status"></div>
                    <div class="small fw-bold text-muted">Menghubungi server hosting dan membandingkan file...</div>
                </div>

                <!-- Action & Progress Box -->
                <div id="syncActionBox" class="d-none">
                    
                    <div id="syncReadyNotice" class="alert alert-info border-0 rounded-4 p-3 small mb-3">
                        <i class="bi bi-info-circle-fill me-1"></i>
                        <span id="syncNoticeText">File foto akan dikirim satu per satu secara otomatis agar tidak membebani server dan tidak gagal batas upload.</span>
                    </div>

                    <!-- Progress Bar -->
                    <div id="syncProgressWrapper" class="d-none mb-3">
                        <div class="d-flex justify-content-between align-items-center small fw-bold mb-1">
                            <span id="syncProgressLabel">Mengunggah file...</span>
                            <span id="syncProgressPercent" class="text-success">0%</span>
                        </div>
                        <div class="progress" style="height: 12px; border-radius: 8px;">
                            <div class="progress-bar bg-success progress-bar-striped progress-bar-animated" id="syncProgressBar" role="progressbar" style="width: 0%;"></div>
                        </div>
                    </div>

                    <!-- Log Box -->
                    <div class="border rounded-3 p-3 bg-dark text-light font-monospace small" id="syncLogBox" style="max-height: 180px; overflow-y: auto; font-size: 11px;">
                        <div>[Ready] Klik tombol Mulai Sinkronkan di bawah untuk mulai mengirim foto ke hosting.</div>
                    </div>

                </div>

            </div>
            <div class="modal-footer bg-light rounded-bottom-4 p-3 d-flex justify-content-between">
                <button type="button" class="btn btn-outline-secondary rounded-pill px-4 fw-bold" data-bs-dismiss="modal" id="btnBatalSync">Tutup</button>
                <button type="button" class="btn btn-success rounded-pill px-4 fw-bold" id="btnStartSync" disabled>
                    <i class="bi bi-play-fill me-1"></i> Mulai Sinkronkan ke Hosting
                </button>
            </div>
        </div>
    </div>
</div>
