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

        <!-- ALERT NOTIFIKASI -->
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

        <!-- ═══ HEADER BANNER ═══ -->
        <div class="card border-0 rounded-4 shadow-sm mb-4 text-white position-relative overflow-hidden" 
             style="background: linear-gradient(135deg, #064e3b 0%, #059669 60%, #10b981 100%);">
            <div class="card-body p-4 p-md-5 position-relative" style="z-index: 2;">
                <div class="row align-items-center g-3">
                    <div class="col-lg-7">
                        <span class="badge bg-white text-success fw-bold px-3 py-1 rounded-pill mb-2" style="font-size: 11.5px;">
                            <i class="bi bi-cloud-check-fill me-1"></i> PUSAT DOKUMEN &amp; EVIDEN ZI
                        </span>
                        <h2 class="fw-bold mb-2 text-white">Kelola Cloud Drive &amp; Unduhan Berkas</h2>
                        <p class="mb-3 text-white-50" style="font-size: 14px; max-width: 620px;">
                            Pusat tata kelola dokumen madrasah: eviden penilaian Zona Integritas (WBK/WBBM Pokja I s.d. VI), modul kurikulum, administrasi kepegawaian/TU, dan formulir publik.
                        </p>
                        <div class="d-flex flex-wrap gap-2 pt-1">
                            <a href="<?= base_url('website/download') ?>" target="_blank" class="btn btn-light text-dark fw-bold rounded-pill px-3 shadow-sm d-inline-flex align-items-center gap-1">
                                <i class="bi bi-box-arrow-up-right text-success"></i> Unduhan Publik
                            </a>
                            <a href="<?= base_url('website/zona_integritas') ?>" target="_blank" class="btn btn-warning text-dark fw-bold rounded-pill px-3 shadow-sm d-inline-flex align-items-center gap-1">
                                <i class="bi bi-shield-lock-fill"></i> Portal Eviden ZI (<?= $stats['zi_total'] ?? 0 ?>)
                            </a>
                        </div>
                    </div>

                    <!-- Widget PIN Akses ZI -->
                    <div class="col-lg-5 text-lg-end">
                        <div class="p-3 rounded-4 d-inline-block text-start" style="background: rgba(255,255,255,0.15); backdrop-filter: blur(8px); border: 1px solid rgba(255,255,255,0.3); min-width: 250px;">
                            <div class="d-flex align-items-center justify-content-between mb-2">
                                <span class="small text-white-50 text-uppercase fw-bold" style="font-size: 11px;">
                                    <i class="bi bi-shield-lock-fill text-warning me-1"></i> PIN Akses Eviden ZI
                                </span>
                                <button type="button" class="btn btn-xs btn-light rounded-pill px-2 py-0 fw-bold" style="font-size: 11px;" data-bs-toggle="modal" data-bs-target="#modalUbahPinZi">
                                    <i class="bi bi-pencil-square"></i> Ubah
                                </button>
                            </div>
                            <div class="d-flex align-items-center gap-2">
                                <span class="h3 fw-bold text-warning mb-0 font-monospace tracking-wide" style="letter-spacing: 3px;">
                                    <?= htmlspecialchars($pin_zi ?? '123456') ?>
                                </span>
                                <span class="badge bg-white-subtle text-white border border-white-50 rounded-pill small" style="font-size: 10px;">Aktif</span>
                            </div>
                            <div class="small text-white-50 mt-1" style="font-size: 11px;">
                                Berikan PIN ini kepada Tim Penilai (TPI/TPN) atau Tim Pokja.
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div style="position: absolute; right: -40px; top: -40px; width: 180px; height: 180px; background: radial-gradient(circle, rgba(255,255,255,0.2) 0%, transparent 70%); border-radius: 50%;"></div>
        </div>

        <div class="row g-4">
            <!-- Form Upload Berkas -->
            <div class="col-lg-4">
                <div class="card border-0 rounded-4 shadow-sm mb-4">
                    <div class="card-header bg-white border-bottom pt-3 pb-2 px-4">
                        <h6 class="fw-bold mb-0 text-dark"><i class="bi bi-cloud-arrow-up-fill text-success me-2"></i> Unggah / Tambah Dokumen</h6>
                    </div>
                    <div class="card-body p-4">
                        <form method="post" action="<?= base_url('admin_website/save_download') ?>" enctype="multipart/form-data">
                            
                            <div class="mb-3">
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

                            <div class="mb-3" id="adminWrapperAreaZi">
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

                            <div class="mb-3">
                                <label class="form-label fw-bold small text-muted">Nama / Judul Dokumen <span class="text-danger">*</span></label>
                                <input type="text" name="judul" class="form-control rounded-3" placeholder="Contoh: SK Tim Pokja ZI WBK 2026" required>
                            </div>

                            <div class="row g-2 mb-3">
                                <div class="col-6">
                                    <label class="form-label fw-bold small text-muted">Tanggal Dokumen</label>
                                    <input type="date" name="tanggal" class="form-control rounded-3" value="<?= date('Y-m-d') ?>">
                                </div>
                                <div class="col-6">
                                    <label class="form-label fw-bold small text-muted">Lini / Unit Kerja</label>
                                    <input type="text" name="lini_unit" class="form-control rounded-3" placeholder="Contoh: Tim Pokja I">
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-bold small text-muted">Nama Pengunggah (PIC)</label>
                                <input type="text" name="pengunggah" class="form-control rounded-3" value="<?= $this->session->userdata('username') ?? 'Admin' ?>" placeholder="Nama staf / guru pengunggah">
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-bold small text-muted">Keterangan Singkat (Opsional)</label>
                                <textarea name="keterangan" class="form-control rounded-3" rows="2" placeholder="Catatan peruntukan berkas..."></textarea>
                            </div>

                            <!-- Pilihan Sumber -->
                            <div class="mb-4">
                                <label class="form-label fw-bold small text-muted d-block">Jenis Sumber Dokumen <span class="text-danger">*</span></label>
                                <div class="d-flex gap-3 mb-2">
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="tipe_sumber" id="sumberFileRadio" value="file" checked onchange="toggleAdminSumber('file')">
                                        <label class="form-check-label small fw-semibold" for="sumberFileRadio">Upload File Fisik</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="tipe_sumber" id="sumberDriveRadio" value="drive_link" onchange="toggleAdminSumber('drive_link')">
                                        <label class="form-check-label small fw-semibold" for="sumberDriveRadio">Link Google Drive</label>
                                    </div>
                                </div>

                                <div id="adminFilePane">
                                    <input type="file" name="file_download" id="adminFileInput" class="form-control rounded-3" required>
                                    <div class="form-text small mt-1">Mendukung: PDF, DOCX, XLSX, PPTX, ZIP (Maks 20MB).</div>
                                </div>

                                <div id="adminDrivePane" style="display: none;">
                                    <input type="url" name="link_drive" id="adminDriveInput" class="form-control rounded-3" placeholder="https://drive.google.com/drive/folders/...">
                                    <div class="form-text small mt-1">Tempel link folder atau file Google Drive yang sudah diset publik.</div>
                                </div>
                            </div>

                            <button type="submit" class="btn btn-success fw-bold rounded-pill w-100 py-2 shadow-sm">
                                <i class="bi bi-cloud-arrow-up-fill me-1"></i> Simpan Dokumen
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Tabel Daftar Berkas -->
            <div class="col-lg-8">
                <div class="card border-0 rounded-4 shadow-sm mb-4">
                    <div class="card-header bg-white border-bottom pt-3 pb-2 px-4 d-flex justify-content-between align-items-center flex-wrap gap-2">
                        <h6 class="fw-bold mb-0 text-dark"><i class="bi bi-folder-fill text-success me-2"></i> Daftar Dokumen Tersedia</h6>
                        <div class="d-flex align-items-center gap-2">
                            <span class="badge bg-success-subtle text-success rounded-pill fw-bold px-3 py-1"><?= count($downloads ?? []) ?> Total Dokumen</span>
                            <span class="badge bg-primary-subtle text-primary rounded-pill fw-bold px-3 py-1"><?= $stats['zi_total'] ?? 0 ?> Eviden ZI</span>
                        </div>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0" style="width:100%">
                                <thead class="bg-light">
                                    <tr>
                                        <th style="width:50px;" class="ps-4">Tipe</th>
                                        <th>Nama Berkas &amp; Keterangan</th>
                                        <th style="width:150px;">Kategori</th>
                                        <th style="width:140px;">Pengunggah</th>
                                        <th style="width:110px;">Tanggal</th>
                                        <th style="width:130px;" class="text-end pe-4">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if(empty($downloads)): ?>
                                        <tr>
                                            <td colspan="6" class="text-center py-5 text-muted">
                                                <i class="bi bi-file-earmark-arrow-down fs-1 text-secondary mb-2 d-block opacity-50"></i>
                                                Belum ada berkas unduhan yang diunggah.
                                            </td>
                                        </tr>
                                    <?php else: ?>
                                        <?php foreach($downloads as $d): ?>
                                            <?php 
                                            $is_drive = (!empty($d->link_drive) || ($d->file_path ?? '') === 'drive_link');
                                            $ext = strtolower(pathinfo($d->file_path ?? '', PATHINFO_EXTENSION)); 
                                            
                                            if($is_drive){
                                                $iconClass = 'bi-google text-success';
                                                $file_url = $d->link_drive;
                                            } else {
                                                $iconClass = 'bi-file-earmark text-secondary';
                                                if($ext == 'pdf') $iconClass = 'bi-file-earmark-pdf-fill text-danger';
                                                elseif(in_array($ext, ['doc','docx'])) $iconClass = 'bi-file-earmark-word-fill text-primary';
                                                elseif(in_array($ext, ['xls','xlsx'])) $iconClass = 'bi-file-earmark-excel-fill text-success';
                                                elseif(in_array($ext, ['ppt','pptx'])) $iconClass = 'bi-file-earmark-ppt-fill text-warning';
                                                elseif(in_array($ext, ['zip','rar'])) $iconClass = 'bi-file-earmark-zip-fill text-purple';
                                                
                                                $file_url = base_url('assets/downloads/'.$d->file_path);
                                            }

                                            $kategori = strtolower($d->kategori_pilar ?? 'umum');
                                            $area = strtolower($d->area_zi ?? '');
                                            ?>
                                            <tr>
                                                <td class="ps-4">
                                                    <i class="bi <?= $iconClass ?> fs-3"></i>
                                                </td>
                                                <td>
                                                    <div class="fw-bold text-dark" style="font-size:14px;"><?= htmlspecialchars($d->judul ?? '-', ENT_QUOTES, 'UTF-8') ?></div>
                                                    <?php if(!empty($d->keterangan)): ?>
                                                        <div class="small text-muted text-truncate" style="max-width: 250px;"><?= htmlspecialchars($d->keterangan, ENT_QUOTES, 'UTF-8') ?></div>
                                                    <?php endif; ?>
                                                    <?php if($is_drive): ?>
                                                        <div class="small text-success mt-1" style="font-size:11px;">
                                                            <i class="bi bi-link-45deg"></i> Google Drive Cloud Link
                                                        </div>
                                                    <?php else: ?>
                                                        <div class="small text-muted mt-1 font-monospace" style="font-size:11px;">
                                                            <?= htmlspecialchars($d->file_path ?? '', ENT_QUOTES, 'UTF-8') ?>
                                                        </div>
                                                    <?php endif; ?>
                                                </td>
                                                <td>
                                                    <?php if($kategori === 'zi' && !empty($area)): ?>
                                                        <span class="badge bg-success-subtle text-success rounded-pill fw-bold">
                                                            <i class="bi bi-shield-check"></i> <?= str_replace('AREA', 'POKJA ', strtoupper($area)) ?>
                                                        </span>
                                                        <div class="small text-muted" style="font-size:10px;"><?= $area_names[$area] ?? 'Zona Integritas' ?></div>
                                                    <?php elseif(!empty($area) && isset($area_names[$area])): ?>
                                                        <span class="badge bg-secondary-subtle text-secondary rounded-pill fw-bold">
                                                            <?= strtoupper($kategori) ?>
                                                        </span>
                                                        <div class="small text-dark fw-semibold mt-1" style="font-size:11px;">
                                                            <?= htmlspecialchars($area_names[$area], ENT_QUOTES, 'UTF-8') ?>
                                                        </div>
                                                    <?php else: ?>
                                                        <span class="badge bg-secondary-subtle text-secondary rounded-pill fw-bold">
                                                            <?= strtoupper($kategori) ?>
                                                        </span>
                                                    <?php endif; ?>
                                                </td>
                                                <td>
                                                    <div class="small fw-semibold text-dark"><?= htmlspecialchars($d->pengunggah ?: 'PTK', ENT_QUOTES, 'UTF-8') ?></div>
                                                    <?php if(!empty($d->lini_unit)): ?>
                                                        <div class="small text-muted" style="font-size:11px;"><?= htmlspecialchars($d->lini_unit, ENT_QUOTES, 'UTF-8') ?></div>
                                                    <?php endif; ?>
                                                </td>
                                                <td>
                                                    <span class="small text-muted">
                                                        <i class="bi bi-calendar3 me-1"></i><?= !empty($d->tanggal) ? date('d M Y', strtotime($d->tanggal)) : (!empty($d->created_at) ? date('d M Y', strtotime($d->created_at)) : '-') ?>
                                                    </span>
                                                </td>
                                                <td class="text-end pe-4">
                                                    <div class="d-inline-flex gap-1">
                                                        <?php if($is_drive): ?>
                                                            <a href="<?= $file_url ?>" target="_blank" class="btn btn-sm btn-light rounded-pill px-3 py-1 text-success shadow-sm" title="Buka Link Google Drive">
                                                                <i class="bi bi-box-arrow-up-right"></i>
                                                            </a>
                                                        <?php else: ?>
                                                            <a href="<?= $file_url ?>" target="_blank" class="btn btn-sm btn-light rounded-pill px-3 py-1 text-primary shadow-sm" download title="Unduh Berkas">
                                                                <i class="bi bi-download me-1"></i>
                                                            </a>
                                                        <?php endif; ?>
                                                        <a href="<?= base_url('admin_website/delete_download/'.$d->id) ?>" 
                                                           class="btn btn-sm btn-light rounded-pill px-2 py-1 text-danger shadow-sm"
                                                           onclick="return confirm('Hapus berkas unduhan ini?')"
                                                           title="Hapus">
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
        </div>

    </div>
</div>

<!-- MODAL UBAH PIN AKSES ZONA INTEGRITAS -->
<div class="modal fade" id="modalUbahPinZi" tabindex="-1" aria-labelledby="modalUbahPinZiLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-sm">
        <div class="modal-content rounded-4 border-0 shadow-lg">
            <div class="modal-header border-0 bg-success text-white py-3 px-4" style="background: linear-gradient(135deg, #064e3b 0%, #059669 100%);">
                <h6 class="modal-title fw-bold" id="modalUbahPinZiLabel">
                    <i class="bi bi-shield-lock-fill text-warning me-1"></i> Ubah PIN Eviden ZI
                </h6>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="<?= base_url('admin_website/update_pin_zi') ?>" method="POST">
                <div class="modal-body p-4 text-center">
                    <p class="small text-muted mb-3">
                        PIN ini digunakan oleh Tim Pokja dan Tim Penilai (TPI/TPN) untuk membuka dokumen eviden ZI di portal website.
                    </p>
                    <div class="mb-3">
                        <label class="form-label small fw-bold text-muted text-uppercase">PIN Baru</label>
                        <input type="text" name="pin_zi" class="form-control text-center fw-bold fs-4 rounded-3 text-success" value="<?= htmlspecialchars($pin_zi ?? '123456') ?>" required maxlength="20" autocomplete="off">
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
</script>

<?php $this->load->view('templates/footer'); ?>
