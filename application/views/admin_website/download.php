<?php $this->load->view('templates/header'); ?>
<?php $this->load->view('templates/sidebar'); ?>

<?php
$area_names = [
    // Pokja Zona Integritas (WBK / WBBM)
    'area1' => 'Pokja I: Manajemen Perubahan',
    'area2' => 'Pokja II: Penataan Tatalaksana',
    'area3' => 'Pokja III: Penataan Manajemen SDM',
    'area4' => 'Pokja IV: Penguatan Akuntabilitas',
    'area5' => 'Pokja V: Penguatan Pengawasan',
    'area6' => 'Pokja VI: Kualitas Pelayanan Publik',

    // Kurikulum & Modul Ajar (Akademik)
    'modul_ajar' => 'Modul Ajar & RPP',
    'silabus' => 'Silabus & ATP',
    'kosp' => 'KOSP & Kurikulum Operasional',
    'jadwal' => 'Jadwal & Kalender Akademik',
    'bank_soal' => 'Bank Soal & Asesmen',
    'prota_promes' => 'Prota & Promes',
    'bahan_ajar' => 'Bahan Ajar & Modul Digital',

    // Kepegawaian & Tata Usaha
    'sk_tugas' => 'SK & Surat Tugas',
    'surat_edaran' => 'Surat Edaran Dinas',
    'sop_tu' => 'SOP Administrasi Madrasah',
    'blanko_pegawai' => 'Blanko Kepegawaian & Cuti',
    'laporan_kinerja' => 'Laporan Kinerja / SKP',
    'notula_rapat' => 'Notula Rapat Dinas',

    // Kesiswaan & Ekstrakurikuler
    'tatib_siswa' => 'Tata Tertib Siswa',
    'osim_mpk' => 'Dokumen OSIM & MPK',
    'ekskul' => 'Ekstrakurikuler',
    'prestasi' => 'Prestasi Siswa',
    'bk_konseling' => 'BP / BK & Konseling',
    'beasiswa_pip' => 'Beasiswa / PIP',

    // Sarpras & Laboratorium
    'inventaris' => 'Inventaris & Aset BMN',
    'sop_lab' => 'SOP Laboratorium',
    'jadwal_lab' => 'Jadwal Penggunaan Lab',
    'berita_acara' => 'Berita Acara Sarpras',
    'pemeliharaan' => 'Pemeliharaan Sarpras',

    // Formulir Publik & Brosur
    'ppdb' => 'PPDB & Brosur Pendaftaran',
    'brosur' => 'Informasi & Pamflet Publik',
    'blanko_surat' => 'Blanko Surat Permohonan',
    'kalender' => 'Kalender Madrasah',
    'majalah_buletin' => 'Buletin Madrasah',
    'lainnya' => 'Lain-lain / Dokumen Pendukung'
];
?>

<div class="content">
    <div class="container-fluid py-4">

        <!-- ═══ ALERT NOTIFIKASI ═══ -->
        <?php if($this->session->flashdata('success')): ?>
            <div class="alert alert-success alert-dismissible fade show rounded-4 border-0 shadow-sm mb-4 d-flex align-items-center gap-2" role="alert">
                <i class="bi bi-check-circle-fill text-success fs-5"></i>
                <div class="fw-semibold"><?= $this->session->flashdata('success') ?></div>
                <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>
        <?php if($this->session->flashdata('error')): ?>
            <div class="alert alert-danger alert-dismissible fade show rounded-4 border-0 shadow-sm mb-4 d-flex align-items-center gap-2" role="alert">
                <i class="bi bi-exclamation-triangle-fill text-danger fs-5"></i>
                <div class="fw-semibold"><?= $this->session->flashdata('error') ?></div>
                <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>

        <!-- ═══ 1. HERO PAGE HEADER (Standar LabSys 2026 - Clean Solid White Card) ═══ -->
        <div class="card border-0 rounded-4 shadow-sm mb-4 bg-white border" style="border-color: #e2e8f0 !important;">
            <div class="card-body p-4">
                <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
                    <div>
                        <span class="badge px-3 py-1 rounded-pill fw-bold mb-2" 
                              style="background-color: #ecfdf5; color: #047857; border: 1px solid #a7f3d0; font-size: 11.5px;">
                            <i class="bi bi-shield-check me-1"></i> Zona Integritas &amp; Cloud Drive
                        </span>
                        <h3 class="fw-bold mb-1 text-dark">Pusat Dokumen &amp; Eviden ZI</h3>
                        <p class="text-muted mb-0 small" style="font-size: 13.5px;">
                            Tata kelola dokumen eviden 6 Pokja Pembangunan ZI (WBK), administrasi madrasah, dan berkas unduhan publik.
                        </p>
                    </div>
                    <div class="d-flex align-items-center gap-2 flex-wrap">
                        <button type="button" class="btn btn-success rounded-pill px-3 py-2 fw-bold text-white shadow-sm" style="font-size: 13px;" data-bs-toggle="modal" data-bs-target="#modalTambahDokumen">
                            <i class="bi bi-cloud-arrow-up-fill me-1"></i> + Unggah Dokumen
                        </button>
                        <button type="button" class="btn btn-outline-warning rounded-pill px-3 py-2 fw-bold text-dark" style="font-size: 13px;" data-bs-toggle="modal" data-bs-target="#modalUbahPinZi" title="Atur PIN Akses Eviden ZI">
                            <i class="bi bi-key-fill text-warning me-1"></i> PIN ZI: <span class="badge bg-warning-subtle text-dark font-monospace"><?= htmlspecialchars($pin_zi ?? '123456') ?></span>
                        </button>
                        <a href="<?= base_url('website/zona_integritas') ?>" target="_blank" class="btn btn-outline-success rounded-pill px-3 py-2 fw-semibold" style="font-size: 13px;">
                            <i class="bi bi-shield-lock me-1"></i> Portal ZI
                        </a>
                        <a href="<?= base_url('website/download') ?>" target="_blank" class="btn btn-outline-secondary rounded-pill px-3 py-2 fw-semibold" style="font-size: 13px;">
                            <i class="bi bi-box-arrow-up-right me-1"></i> Unduhan Publik
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- ═══ 2. KPI / STATISTIC CARDS STRIP (Standar LabSys 2026) ═══ -->
        <div class="row g-3 mb-4">
            <!-- Total Dokumen -->
            <div class="col-6 col-lg-3">
                <div class="card border-0 rounded-4 shadow-sm p-3 h-100 bg-white">
                    <div class="d-flex align-items-center gap-3">
                        <div class="rounded-3 p-2.5 bg-primary-subtle text-primary fs-4 d-flex align-items-center justify-content-center" style="width: 48px; height: 48px;">
                            <i class="bi bi-folder2-open"></i>
                        </div>
                        <div>
                            <span class="text-muted small fw-bold text-uppercase d-block" style="font-size: 11px;">Total Berkas</span>
                            <h4 class="fw-bold mb-0 text-dark"><?= number_format($stats['total'] ?? count($downloads ?? [])) ?></h4>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Eviden ZI (6 Pokja) -->
            <div class="col-6 col-lg-3">
                <div class="card border-0 rounded-4 shadow-sm p-3 h-100 bg-white">
                    <div class="d-flex align-items-center gap-3">
                        <div class="rounded-3 p-2.5 bg-success-subtle text-success fs-4 d-flex align-items-center justify-content-center" style="width: 48px; height: 48px;">
                            <i class="bi bi-shield-check"></i>
                        </div>
                        <div>
                            <span class="text-muted small fw-bold text-uppercase d-block" style="font-size: 11px;">Eviden ZI (6 Pokja)</span>
                            <h4 class="fw-bold mb-0 text-success"><?= number_format($stats['zi_total'] ?? 0) ?></h4>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Modul Kurikulum & Akademik -->
            <div class="col-6 col-lg-3">
                <div class="card border-0 rounded-4 shadow-sm p-3 h-100 bg-white">
                    <div class="d-flex align-items-center gap-3">
                        <div class="rounded-3 p-2.5 bg-info-subtle text-info fs-4 d-flex align-items-center justify-content-center" style="width: 48px; height: 48px;">
                            <i class="bi bi-journal-bookmark-fill"></i>
                        </div>
                        <div>
                            <span class="text-muted small fw-bold text-uppercase d-block" style="font-size: 11px;">Akademik &amp; Modul</span>
                            <h4 class="fw-bold mb-0 text-info"><?= number_format($stats['akademik'] ?? 0) ?></h4>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Administrasi & Publik -->
            <div class="col-6 col-lg-3">
                <div class="card border-0 rounded-4 shadow-sm p-3 h-100 bg-white">
                    <div class="d-flex align-items-center gap-3">
                        <div class="rounded-3 p-2.5 bg-warning-subtle text-warning fs-4 d-flex align-items-center justify-content-center" style="width: 48px; height: 48px;">
                            <i class="bi bi-building"></i>
                        </div>
                        <div>
                            <span class="text-muted small fw-bold text-uppercase d-block" style="font-size: 11px;">Kepegawaian &amp; TU</span>
                            <h4 class="fw-bold mb-0 text-warning"><?= number_format(($stats['kepegawaian'] ?? 0) + ($stats['kesiswaan'] ?? 0) + ($stats['sarpras'] ?? 0) + ($stats['umum'] ?? 0)) ?></h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- ═══ 3. FILTER, SEARCH & CATEGORY CHIPS STRIP ═══ -->
        <div class="card border-0 rounded-4 shadow-sm mb-4 bg-white">
            <div class="card-body p-3 p-md-4">
                <div class="row g-3 align-items-center">
                    
                    <!-- Search Input -->
                    <div class="col-lg-4 col-md-6">
                        <div class="input-group">
                            <span class="input-group-text bg-light border-end-0 rounded-start-pill text-muted ps-3">
                                <i class="bi bi-search"></i>
                            </span>
                            <input type="text" id="adminTableSearch" class="form-control bg-light border-start-0 rounded-end-pill py-2" placeholder="Cari nama dokumen, pengunggah, keterangan..." style="font-size: 13.5px;">
                        </div>
                    </div>

                    <!-- Filter Kategori Pilar -->
                    <div class="col-lg-3 col-md-6">
                        <select id="filterPilar" class="form-select rounded-pill px-3 py-2 fw-semibold" style="font-size: 13.5px;" onchange="filterAdminTable()">
                            <option value="all">Semua Kategori Pilar</option>
                            <option value="zi">⭐ Zona Integritas (WBK)</option>
                            <option value="akademik">📚 Kurikulum &amp; Modul Ajar</option>
                            <option value="kepegawaian">🗄️ Kepegawaian &amp; Tata Usaha</option>
                            <option value="kesiswaan">🏆 Kesiswaan &amp; Ekskul</option>
                            <option value="sarpras">🔬 Sarpras &amp; Lab</option>
                            <option value="umum">📄 Formulir Publik &amp; Brosur</option>
                        </select>
                    </div>

                    <!-- Filter Khusus Pokja ZI -->
                    <div class="col-lg-3 col-md-6">
                        <select id="filterPokja" class="form-select rounded-pill px-3 py-2 text-success fw-bold" style="font-size: 13.5px;" onchange="filterAdminTable()">
                            <option value="all">Semua Pokja ZI (I s.d VI)</option>
                            <option value="area1">Pokja I: Manajemen Perubahan</option>
                            <option value="area2">Pokja II: Penataan Tatalaksana</option>
                            <option value="area3">Pokja III: Penataan Manajemen SDM</option>
                            <option value="area4">Pokja IV: Penguatan Akuntabilitas</option>
                            <option value="area5">Pokja V: Penguatan Pengawasan</option>
                            <option value="area6">Pokja VI: Kualitas Pelayanan Publik</option>
                        </select>
                    </div>

                    <!-- Reset Filter Button -->
                    <div class="col-lg-2 col-md-6 text-lg-end">
                        <button type="button" class="btn btn-outline-secondary btn-sm rounded-pill px-3 py-2 fw-semibold w-100 w-lg-auto" onclick="resetAdminFilter()">
                            <i class="bi bi-arrow-counterclockwise me-1"></i> Reset Filter
                        </button>
                    </div>

                </div>
            </div>
        </div>

        <!-- ═══ 4. TABEL DATA UTAMA (Full Width - Gaya Tabel Ijazah) ═══ -->
        <div class="card border-0 rounded-4 shadow-sm overflow-hidden bg-white mb-4">
            
            <!-- Header Table Strip -->
            <div class="d-flex justify-content-between align-items-center p-3 px-4 border-bottom bg-light bg-opacity-50 flex-wrap gap-2">
                <div class="fw-bold text-dark d-flex align-items-center gap-2" style="font-size: 14.5px;">
                    <i class="bi bi-table text-success fs-5"></i> Daftar Dokumen &amp; Berkas
                </div>
                <div class="d-flex align-items-center gap-2">
                    <span class="small text-muted" style="font-size: 13px;">
                        Menampilkan <strong id="visibleCount"><?= count($downloads ?? []) ?></strong> dari <strong><?= count($downloads ?? []) ?></strong> berkas
                    </span>
                </div>
            </div>

            <!-- Table Responsive Container -->
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0" id="tableAdminDownload" style="font-size: 13.5px; width: 100%;">
                    <thead class="bg-light text-muted small text-uppercase">
                        <tr>
                            <th class="ps-4 text-center" style="width: 50px;">No</th>
                            <th style="width: 60px;">Format</th>
                            <th>Nama Dokumen &amp; Keterangan</th>
                            <th style="width: 220px;">Kategori / Pokja</th>
                            <th style="width: 170px;">Pengunggah (PIC)</th>
                            <th style="width: 130px;">Tanggal</th>
                            <th class="text-end pe-4" style="width: 130px;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if(empty($downloads)): ?>
                            <tr id="emptyRow">
                                <td colspan="7" class="text-center py-5 text-muted">
                                    <i class="bi bi-file-earmark-arrow-down fs-1 text-secondary mb-2 d-block opacity-50"></i>
                                    Belum ada berkas unduhan atau eviden yang tersimpan.
                                </td>
                            </tr>
                        <?php else: ?>
                            <?php $no = 1; foreach($downloads as $d): ?>
                                <?php 
                                $is_drive = (!empty($d->link_drive) || ($d->file_path ?? '') === 'drive_link');
                                $ext = strtolower(pathinfo($d->file_path ?? '', PATHINFO_EXTENSION)); 
                                
                                if($is_drive){
                                    $iconBoxBg = '#ecfdf5';
                                    $iconBoxColor = '#059669';
                                    $iconClass = 'bi-google';
                                    $extLabel = 'DRIVE';
                                    $file_url = $d->link_drive;
                                } else {
                                    $iconBoxBg = '#f1f5f9';
                                    $iconBoxColor = '#475569';
                                    $iconClass = 'bi-file-earmark-fill';
                                    $extLabel = strtoupper($ext ?: 'FILE');

                                    if($ext == 'pdf') {
                                        $iconBoxBg = '#fef2f2';
                                        $iconBoxColor = '#dc2626';
                                        $iconClass = 'bi-file-earmark-pdf-fill';
                                    } elseif(in_array($ext, ['doc','docx'])) {
                                        $iconBoxBg = '#eff6ff';
                                        $iconBoxColor = '#2563eb';
                                        $iconClass = 'bi-file-earmark-word-fill';
                                    } elseif(in_array($ext, ['xls','xlsx'])) {
                                        $iconBoxBg = '#f0fdf4';
                                        $iconBoxColor = '#16a34a';
                                        $iconClass = 'bi-file-earmark-excel-fill';
                                    } elseif(in_array($ext, ['ppt','pptx'])) {
                                        $iconBoxBg = '#fffbeb';
                                        $iconBoxColor = '#d97706';
                                        $iconClass = 'bi-file-earmark-ppt-fill';
                                    } elseif(in_array($ext, ['zip','rar'])) {
                                        $iconBoxBg = '#faf5ff';
                                        $iconBoxColor = '#9333ea';
                                        $iconClass = 'bi-file-earmark-zip-fill';
                                    }
                                    
                                    $file_url = base_url('assets/downloads/'.$d->file_path);
                                }

                                $kategori = strtolower($d->kategori_pilar ?? 'umum');
                                $area = strtolower($d->area_zi ?? '');
                                ?>
                                <tr class="admin-doc-row"
                                    data-pilar="<?= $kategori ?>"
                                    data-pokja="<?= $area ?>"
                                    data-title="<?= htmlspecialchars(strtolower($d->judul ?? ''), ENT_QUOTES, 'UTF-8') ?>"
                                    data-keterangan="<?= htmlspecialchars(strtolower($d->keterangan ?? ''), ENT_QUOTES, 'UTF-8') ?>"
                                    data-pengunggah="<?= htmlspecialchars(strtolower($d->pengunggah ?? ''), ENT_QUOTES, 'UTF-8') ?>">
                                    
                                    <td class="ps-4 text-center text-muted fw-semibold row-number">
                                        <?= $no++ ?>
                                    </td>

                                    <!-- Format Icon -->
                                    <td>
                                        <div class="rounded-3 d-flex align-items-center justify-content-center" 
                                             style="width: 38px; height: 38px; background: <?= $iconBoxBg ?>; color: <?= $iconBoxColor ?>; font-size: 18px;" 
                                             title="<?= $extLabel ?>">
                                            <i class="bi <?= $iconClass ?>"></i>
                                        </div>
                                    </td>

                                    <!-- Nama Dokumen & Catatan -->
                                    <td>
                                        <div>
                                            <a href="<?= $file_url ?>" target="_blank" class="fw-bold text-dark text-decoration-none hover-primary" style="font-size: 14px;">
                                                <?= htmlspecialchars($d->judul ?? '-', ENT_QUOTES, 'UTF-8') ?>
                                            </a>
                                        </div>

                                        <?php if(!empty($d->keterangan)): ?>
                                            <div class="small text-muted mt-1 text-truncate" style="max-width: 460px; font-size: 12px;">
                                                <?= htmlspecialchars($d->keterangan, ENT_QUOTES, 'UTF-8') ?>
                                            </div>
                                        <?php endif; ?>

                                        <?php if($is_drive): ?>
                                            <div class="small text-success mt-1" style="font-size: 11px;">
                                                <i class="bi bi-link-45deg"></i> Google Drive Cloud Link
                                            </div>
                                        <?php else: ?>
                                            <div class="small text-muted mt-1 font-monospace" style="font-size: 10.5px;">
                                                <i class="bi bi-hdd-fill me-1"></i><?= htmlspecialchars($d->file_path ?? '', ENT_QUOTES, 'UTF-8') ?>
                                            </div>
                                        <?php endif; ?>
                                    </td>

                                    <!-- Kategori / Pokja -->
                                    <td>
                                        <?php if($kategori === 'zi' && !empty($area)): ?>
                                            <span class="badge bg-success-subtle text-success rounded-pill fw-bold px-2.5 py-1">
                                                <i class="bi bi-shield-check me-1"></i> <?= str_replace('AREA', 'POKJA ', strtoupper($area)) ?>
                                            </span>
                                            <div class="small text-muted mt-1" style="font-size: 11px;">
                                                <?= $area_names[$area] ?? 'Zona Integritas' ?>
                                            </div>
                                        <?php elseif(!empty($area) && isset($area_names[$area])): ?>
                                            <span class="badge bg-secondary-subtle text-secondary rounded-pill fw-bold px-2.5 py-1">
                                                <?= strtoupper($kategori) ?>
                                            </span>
                                            <div class="small text-dark fw-semibold mt-1" style="font-size: 11px;">
                                                <?= htmlspecialchars($area_names[$area], ENT_QUOTES, 'UTF-8') ?>
                                            </div>
                                        <?php else: ?>
                                            <span class="badge bg-secondary-subtle text-secondary rounded-pill fw-bold px-2.5 py-1">
                                                <?= strtoupper($kategori) ?>
                                            </span>
                                        <?php endif; ?>
                                    </td>

                                    <!-- Pengunggah (PIC) -->
                                    <td>
                                        <div class="fw-semibold text-dark small">
                                            <i class="bi bi-person-fill text-muted me-1"></i><?= htmlspecialchars($d->pengunggah ?: 'Admin', ENT_QUOTES, 'UTF-8') ?>
                                        </div>
                                        <?php if(!empty($d->lini_unit)): ?>
                                            <div class="small text-muted" style="font-size: 11px;"><?= htmlspecialchars($d->lini_unit, ENT_QUOTES, 'UTF-8') ?></div>
                                        <?php endif; ?>
                                    </td>

                                    <!-- Tanggal -->
                                    <td>
                                        <div class="small text-dark fw-medium">
                                            <i class="bi bi-calendar3 me-1 text-muted"></i><?= !empty($d->tanggal) ? date('d M Y', strtotime($d->tanggal)) : (!empty($d->created_at) ? date('d M Y', strtotime($d->created_at)) : '-') ?>
                                        </div>
                                    </td>

                                    <!-- Aksi Rounded-Pill -->
                                    <td class="text-end pe-4">
                                        <div class="d-inline-flex gap-1">
                                            <?php if($is_drive): ?>
                                                <a href="<?= $file_url ?>" target="_blank" class="btn btn-outline-success btn-sm rounded-pill px-3 py-1 fw-bold" title="Buka Link Google Drive">
                                                    <i class="bi bi-box-arrow-up-right me-1"></i> Buka
                                                </a>
                                            <?php else: ?>
                                                <a href="<?= $file_url ?>" target="_blank" class="btn btn-outline-primary btn-sm rounded-pill px-3 py-1 fw-bold" download title="Unduh Berkas">
                                                    <i class="bi bi-download me-1"></i> Unduh
                                                </a>
                                            <?php endif; ?>

                                            <a href="<?= base_url('admin_website/delete_download/'.$d->id) ?>" 
                                               class="btn btn-outline-danger btn-sm rounded-pill px-2.5 py-1"
                                               onclick="return confirm('Apakah Anda yakin ingin menghapus berkas dokumen ini?')"
                                               title="Hapus Berkas">
                                                <i class="bi bi-trash-fill"></i>
                                            </a>
                                        </div>
                                    </td>

                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

        </div>

    </div>
</div>

<!-- ═══ 5. MODAL UNGGAH / TAMBAH DOKUMEN (Clean Modern Dialog) ═══ -->
<div class="modal fade" id="modalTambahDokumen" tabindex="-1" aria-labelledby="modalTambahDokumenLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content rounded-4 border-0 shadow-lg">
            
            <div class="modal-header border-0 bg-success text-white px-4 py-3" style="background: linear-gradient(135deg, #064e3b 0%, #059669 100%);">
                <div>
                    <h5 class="modal-title fw-bold" id="modalTambahDokumenLabel">
                        <i class="bi bi-cloud-arrow-up-fill me-1 text-warning"></i> Unggah / Tambah Dokumen &amp; Eviden
                    </h5>
                    <p class="small text-white-50 mb-0">Tambahkan berkas digital madrasah atau eviden 6 Pokja Zona Integritas.</p>
                </div>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <form method="post" action="<?= base_url('admin_website/save_download') ?>" enctype="multipart/form-data">
                <div class="modal-body p-4">

                    <!-- Kategori Pilar & Pokja -->
                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label class="form-label fw-bold small text-muted">Kategori Dokumen / Pilar <span class="text-danger">*</span></label>
                            <select name="kategori_pilar" id="adminKategoriPilar" class="form-select rounded-3" required onchange="handleAdminKategoriChange(this.value)">
                                <option value="zi" selected>⭐ Zona Integritas (WBK / WBBM)</option>
                                <option value="akademik">📚 Kurikulum &amp; Modul Ajar</option>
                                <option value="kepegawaian">🗄️ Kepegawaian &amp; Tata Usaha</option>
                                <option value="kesiswaan">🏆 Kesiswaan &amp; Ekstrakurikuler</option>
                                <option value="sarpras">🔬 Sarpras &amp; Laboratorium</option>
                                <option value="umum">📄 Formulir Publik &amp; Brosur</option>
                            </select>
                        </div>
                        <div class="col-md-6" id="adminWrapperAreaZi">
                            <label class="form-label fw-bold small text-muted" id="adminLabelSubKategori">Pokja Perubahan ZI <span class="text-danger">*</span></label>
                            <select name="area_zi" id="adminAreaZi" class="form-select rounded-3 text-success fw-bold" required>
                                <option value="area1">Pokja I: Manajemen Perubahan</option>
                                <option value="area2">Pokja II: Penataan Tatalaksana</option>
                                <option value="area3">Pokja III: Penataan Manajemen SDM</option>
                                <option value="area4">Pokja IV: Penguatan Akuntabilitas</option>
                                <option value="area5">Pokja V: Penguatan Pengawasan</option>
                                <option value="area6">Pokja VI: Kualitas Pelayanan Publik</option>
                            </select>
                        </div>
                    </div>

                    <!-- Judul Dokumen -->
                    <div class="mb-3">
                        <label class="form-label fw-bold small text-muted">Nama / Judul Dokumen <span class="text-danger">*</span></label>
                        <input type="text" name="judul" class="form-control rounded-3" placeholder="Contoh: SK Tim Pokja Pembangunan ZI WBK 2026" required>
                    </div>

                    <!-- Tanggal & Unit Kerja -->
                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label class="form-label fw-bold small text-muted">Tanggal Dokumen</label>
                            <input type="date" name="tanggal" class="form-control rounded-3" value="<?= date('Y-m-d') ?>">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold small text-muted">Lini / Unit Kerja</label>
                            <input type="text" name="lini_unit" class="form-control rounded-3" placeholder="Contoh: Tim Pokja I / Bagian Kurikulum">
                        </div>
                    </div>

                    <!-- Pengunggah (PIC) & Keterangan -->
                    <div class="row g-3 mb-3">
                        <div class="col-md-5">
                            <label class="form-label fw-bold small text-muted">Nama Pengunggah (PIC)</label>
                            <input type="text" name="pengunggah" class="form-control rounded-3" value="<?= htmlspecialchars($this->session->userdata('username') ?? 'Admin') ?>" placeholder="Nama staf / PIC">
                        </div>
                        <div class="col-md-7">
                            <label class="form-label fw-bold small text-muted">Keterangan / Nomor Dokumen (Opsional)</label>
                            <input type="text" name="keterangan" class="form-control rounded-3" placeholder="Nomor surat atau peruntukan berkas...">
                        </div>
                    </div>

                    <!-- Jenis Sumber Dokumen -->
                    <div class="mb-2 p-3 rounded-4 bg-light">
                        <label class="form-label fw-bold small text-muted d-block mb-2">Jenis Sumber Dokumen <span class="text-danger">*</span></label>
                        <div class="d-flex gap-4 mb-3">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="tipe_sumber" id="sumberFileRadio" value="file" checked onchange="toggleAdminSumber('file')">
                                <label class="form-check-label small fw-semibold" for="sumberFileRadio">
                                    <i class="bi bi-file-earmark-arrow-up text-primary me-1"></i> Upload File Fisik
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="tipe_sumber" id="sumberDriveRadio" value="drive_link" onchange="toggleAdminSumber('drive_link')">
                                <label class="form-check-label small fw-semibold" for="sumberDriveRadio">
                                    <i class="bi bi-google text-success me-1"></i> Tautan Google Drive
                                </label>
                            </div>
                        </div>

                        <div id="adminFilePane">
                            <input type="file" name="file_download" id="adminFileInput" class="form-control rounded-3" required>
                            <div class="form-text small mt-1">Mendukung file: PDF, DOCX, XLSX, PPTX, ZIP (Maks 20MB).</div>
                        </div>

                        <div id="adminDrivePane" style="display: none;">
                            <input type="url" name="link_drive" id="adminDriveInput" class="form-control rounded-3" placeholder="https://drive.google.com/drive/folders/...">
                            <div class="form-text small mt-1">Tempel link folder atau file Google Drive yang sudah diset akses publik.</div>
                        </div>
                    </div>

                </div>

                <div class="modal-footer border-0 bg-light p-3 rounded-bottom-4">
                    <button type="button" class="btn btn-outline-secondary btn-sm rounded-pill px-4" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-success btn-sm rounded-pill px-4 fw-bold shadow-sm">
                        <i class="bi bi-cloud-arrow-up-fill me-1"></i> Simpan Dokumen
                    </button>
                </div>
            </form>

        </div>
    </div>
</div>

<!-- ═══ 6. MODAL UBAH PIN AKSES ZONA INTEGRITAS ═══ -->
<div class="modal fade" id="modalUbahPinZi" tabindex="-1" aria-labelledby="modalUbahPinZiLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-sm">
        <div class="modal-content rounded-4 border-0 shadow-lg">
            <div class="modal-header border-0 bg-success text-white py-3 px-4" style="background: linear-gradient(135deg, #064e3b 0%, #059669 100%);">
                <h6 class="modal-title fw-bold" id="modalUbahPinZiLabel">
                    <i class="bi bi-shield-lock-fill text-warning me-1"></i> PIN Eviden ZI
                </h6>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="<?= base_url('admin_website/update_pin_zi') ?>" method="POST">
                <div class="modal-body p-4 text-center">
                    <p class="small text-muted mb-3">
                        PIN ini digunakan oleh Tim Pokja &amp; Tim Penilai (TPI/TPN) untuk membuka dokumen eviden ZI di portal website.
                    </p>
                    <div class="mb-3">
                        <label class="form-label small fw-bold text-muted text-uppercase">PIN Aktif Saat Ini</label>
                        <input type="text" name="pin_zi" class="form-control text-center fw-bold fs-4 rounded-3 text-success font-monospace" value="<?= htmlspecialchars($pin_zi ?? '123456') ?>" required maxlength="20" autocomplete="off">
                    </div>
                </div>
                <div class="modal-footer border-0 bg-light p-3 rounded-bottom-4">
                    <button type="button" class="btn btn-outline-secondary btn-sm rounded-pill px-3" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-success btn-sm rounded-pill px-4 fw-bold">Simpan PIN</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
// Admin Dynamic Sub-Categories Configuration
const adminSubKategoriConfig = {
    zi: {
        label: 'Pokja Perubahan ZI <span class="text-danger">*</span>',
        cssClass: 'text-success fw-bold',
        options: [
            { value: 'area1', label: 'Pokja I: Manajemen Perubahan' },
            { value: 'area2', label: 'Pokja II: Penataan Tatalaksana' },
            { value: 'area3', label: 'Pokja III: Penataan Manajemen SDM' },
            { value: 'area4', label: 'Pokja IV: Penguatan Akuntabilitas' },
            { value: 'area5', label: 'Pokja V: Penguatan Pengawasan' },
            { value: 'area6', label: 'Pokja VI: Kualitas Pelayanan Publik' }
        ]
    },
    akademik: {
        label: 'Sub-Kategori Kurikulum & Modul <span class="text-danger">*</span>',
        cssClass: 'text-primary fw-bold',
        options: [
            { value: 'modul_ajar', label: 'Modul Ajar & RPP' },
            { value: 'silabus', label: 'Silabus & ATP (Alur Tujuan Belajar)' },
            { value: 'kosp', label: 'KOSP & Dokumen Kurikulum' },
            { value: 'jadwal', label: 'Jadwal Pelajaran & Kalender' },
            { value: 'bank_soal', label: 'Bank Soal, Kisi-Kisi & Asesmen' },
            { value: 'prota_promes', label: 'Program Tahunan & Semester (Prota/Promes)' },
            { value: 'bahan_ajar', label: 'Bahan Ajar, PPT & Buku Digital' },
            { value: 'lainnya', label: 'Dokumen Akademik Lainnya' }
        ]
    },
    kepegawaian: {
        label: 'Sub-Kategori Kepegawaian & TU <span class="text-danger">*</span>',
        cssClass: 'text-secondary fw-bold',
        options: [
            { value: 'sk_tugas', label: 'SK Kepala Madrasah & Surat Tugas' },
            { value: 'surat_edaran', label: 'Surat Edaran & Instruksi Dinas' },
            { value: 'sop_tu', label: 'Standar Operasional Prosedur (SOP)' },
            { value: 'blanko_pegawai', label: 'Blanko Kepegawaian & Form Cuti' },
            { value: 'laporan_kinerja', label: 'Laporan Kinerja, SKP & Eviden' },
            { value: 'notula_rapat', label: 'Notula & Presensi Rapat Dinas' },
            { value: 'lainnya', label: 'Administrasi TU Lainnya' }
        ]
    },
    kesiswaan: {
        label: 'Sub-Kategori Kesiswaan & Ekskul <span class="text-danger">*</span>',
        cssClass: 'text-warning fw-bold',
        options: [
            { value: 'tatib_siswa', label: 'Tata Tertib Siswa & Buku Saku' },
            { value: 'osim_mpk', label: 'Dokumen OSIM & MPK' },
            { value: 'ekskul', label: 'Program & Laporan Ekstrakurikuler' },
            { value: 'prestasi', label: 'Piagam & Rekap Prestasi Siswa' },
            { value: 'bk_konseling', label: 'Program BP/BK & Konseling' },
            { value: 'beasiswa_pip', label: 'Data Beasiswa & Bantuan Siswa (PIP)' },
            { value: 'lainnya', label: 'Dokumen Kesiswaan Lainnya' }
        ]
    },
    sarpras: {
        label: 'Sub-Kategori Sarpras & Lab <span class="text-danger">*</span>',
        cssClass: 'text-info fw-bold',
        options: [
            { value: 'inventaris', label: 'Daftar Inventaris & Aset BMN' },
            { value: 'sop_lab', label: 'SOP Tata Tertib Lab / Workshop' },
            { value: 'jadwal_lab', label: 'Jadwal Pemakaian Ruang Lab' },
            { value: 'berita_acara', label: 'Berita Acara Kerusakan / Penghapusan' },
            { value: 'pemeliharaan', label: 'Jadwal Perawatan & Riwayat Aset' },
            { value: 'lainnya', label: 'Dokumen Sarpras & Lab Lainnya' }
        ]
    },
    umum: {
        label: 'Sub-Kategori Formulir & Publikasi <span class="text-danger">*</span>',
        cssClass: 'text-dark fw-bold',
        options: [
            { value: 'ppdb', label: 'Brosur & Formulir Pendaftaran PPDB' },
            { value: 'brosur', label: 'Brosur Profil Madrasah & Pengumuman' },
            { value: 'blanko_surat', label: 'Blanko Surat Permohonan Siswa' },
            { value: 'kalender', label: 'Kalender Madrasah & Agenda Resmi' },
            { value: 'majalah_buletin', label: 'Majalah & Buletin Madrasah' },
            { value: 'lainnya', label: 'Dokumen Publik Lainnya' }
        ]
    }
};

function handleAdminKategoriChange(val, selectedVal = null) {
    const labelEl = document.getElementById('adminLabelSubKategori');
    const select = document.getElementById('adminAreaZi');
    if (!select) return;

    const config = adminSubKategoriConfig[val] || adminSubKategoriConfig['zi'];
    if (labelEl) {
        labelEl.innerHTML = config.label;
    }

    select.className = 'form-select rounded-3 ' + (config.cssClass || 'text-dark fw-bold');
    select.innerHTML = '';

    config.options.forEach((opt, idx) => {
        const optionEl = document.createElement('option');
        optionEl.value = opt.value;
        optionEl.textContent = opt.label;
        if (selectedVal && selectedVal === opt.value) {
            optionEl.selected = true;
        } else if (!selectedVal && idx === 0) {
            optionEl.selected = true;
        }
        select.appendChild(optionEl);
    });

    select.setAttribute('required', 'required');
}

document.addEventListener('DOMContentLoaded', function() {
    const adminPilar = document.getElementById('adminKategoriPilar');
    if (adminPilar) {
        handleAdminKategoriChange(adminPilar.value);
    }
});

function toggleAdminSumber(type) {
    const paneFile = document.getElementById('adminFilePane');
    const paneDrive = document.getElementById('adminDrivePane');
    const inputFile = document.getElementById('adminFileInput');
    const inputDrive = document.getElementById('adminDriveInput');

    if(type === 'file'){
        paneFile.style.display = 'block';
        paneDrive.style.display = 'none';
        inputFile.setAttribute('required', 'required');
        inputDrive.removeAttribute('required');
    } else {
        paneFile.style.display = 'none';
        paneDrive.style.display = 'block';
        inputFile.removeAttribute('required');
        inputDrive.setAttribute('required', 'required');
    }
}

// Client-side Instant Filter & Search
function filterAdminTable() {
    const searchVal = (document.getElementById('adminTableSearch')?.value || '').toLowerCase().trim();
    const pilarVal = document.getElementById('filterPilar')?.value || 'all';
    const pokjaVal = document.getElementById('filterPokja')?.value || 'all';

    const rows = document.querySelectorAll('.admin-doc-row');
    let visibleCount = 0;

    rows.forEach(row => {
        const rowPilar = (row.getAttribute('data-pilar') || '').toLowerCase().trim();
        const rowPokja = (row.getAttribute('data-pokja') || '').toLowerCase().trim();
        const rowTitle = row.getAttribute('data-title') || '';
        const rowKeterangan = row.getAttribute('data-keterangan') || '';
        const rowPengunggah = row.getAttribute('data-pengunggah') || '';

        // Match Pilar
        let matchPilar = (pilarVal === 'all') || (rowPilar === pilarVal);

        // Match Pokja (if ZI or pokja filter is active)
        let matchPokja = (pokjaVal === 'all') || (rowPokja === pokjaVal);

        // Match Search Query
        let matchSearch = true;
        if (searchVal !== '') {
            matchSearch = rowTitle.includes(searchVal) || rowKeterangan.includes(searchVal) || rowPengunggah.includes(searchVal);
        }

        if (matchPilar && matchPokja && matchSearch) {
            row.style.display = '';
            visibleCount++;
            const numEl = row.querySelector('.row-number');
            if (numEl) numEl.textContent = visibleCount;
        } else {
            row.style.display = 'none';
        }
    });

    const countEl = document.getElementById('visibleCount');
    if (countEl) countEl.textContent = visibleCount;

    const emptyRow = document.getElementById('emptyRow');
    if (emptyRow) {
        emptyRow.style.display = (visibleCount === 0) ? '' : 'none';
    }
}

document.getElementById('adminTableSearch')?.addEventListener('input', filterAdminTable);

function resetAdminFilter() {
    const searchInput = document.getElementById('adminTableSearch');
    const pilarSelect = document.getElementById('filterPilar');
    const pokjaSelect = document.getElementById('filterPokja');

    if (searchInput) searchInput.value = '';
    if (pilarSelect) pilarSelect.value = 'all';
    if (pokjaSelect) pokjaSelect.value = 'all';

    filterAdminTable();
}
</script>

<?php $this->load->view('templates/footer'); ?>
