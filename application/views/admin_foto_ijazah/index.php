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
                            <a href="<?= base_url('admin_foto_ijazah/download_zip' . (!empty($selected_kelas) ? '?kelas_id='.$selected_kelas : '')) ?>" class="btn btn-warning text-dark fw-bold rounded-pill px-3 shadow-sm">
                                <i class="bi bi-file-earmark-zip-fill me-1"></i> Download ZIP ({NISN}.jpg)
                            </a>
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
                                                        <a href="<?= base_url('admin_foto_ijazah/reset_klaim/' . $s['verif_id']) ?>" 
                                                           class="btn btn-outline-danger btn-sm rounded-pill px-2 py-1" 
                                                           title="Batalkan Verifikasi Siswa Ini"
                                                           onclick="return confirm('Apakah Anda yakin ingin membatalkan verifikasi foto untuk <?= htmlspecialchars(addslashes($s['nama_lengkap'])) ?>? Foto akan dikembalikan ke galeri belum diklaim.');">
                                                            <i class="bi bi-arrow-counterclockwise me-1"></i> Reset
                                                        </a>
                                                    <?php else: ?>
                                                        <span class="text-muted small">Menunggu Siswa</span>
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
                                                <small class="text-truncate d-block fw-semibold" style="font-size: 11px;" title="<?= htmlspecialchars($up['file_mentah']) ?>">
                                                    <?= htmlspecialchars($up['file_mentah']) ?>
                                                </small>
                                                <a href="<?= base_url('admin_foto_ijazah/hapus_mentah/' . $up['id']) ?>" 
                                                   class="btn btn-link text-danger p-0 mt-1" style="font-size: 11px;"
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
                    <i class="bi bi-info-circle-fill me-1"></i> Anda dapat mengunggah <strong>banyak file foto sekaligus</strong> (.jpg, .jpeg, .png) atau mengunggah <strong>1 file ZIP</strong> berisi kumpulan foto. Sistem otomatis mengekstraknya.
                </div>

                <div class="mb-3">
                    <label class="form-label fw-bold small text-dark">Pilih Kelas (Opsional):</label>
                    <select name="kelas_id" class="form-select rounded-3">
                        <option value="">-- Semua Kelas XII (Global Pool) --</option>
                        <?php foreach($kelas_list as $kl): ?>
                            <option value="<?= $kl['id'] ?>"><?= htmlspecialchars($kl['nama_kelas']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-bold small text-dark">Opsi 1: Upload File Arsip ZIP:</label>
                    <input type="file" name="zip_file" class="form-control rounded-3" accept=".zip">
                    <small class="text-muted" style="font-size: 11px;">Maksimal ukuran file ZIP 250 MB.</small>
                </div>

                <div class="text-center text-muted fw-bold small my-2">-- ATAU --</div>

                <div class="mb-3">
                    <label class="form-label fw-bold small text-dark">Opsi 2: Upload Gambar Banyak (Multi-Select):</label>
                    <input type="file" name="foto_files[]" class="form-control rounded-3" accept="image/*" multiple>
                    <small class="text-muted" style="font-size: 11px;">Tekan tombol Ctrl / Shift di keyboard untuk memilih banyak foto sekaligus.</small>
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
</script>
