<?php $this->load->view('templates/header'); ?>
<?php $this->load->view('templates/sidebar'); ?>

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

        <!-- ═══ HEADER BANNER (TEMA FOTO IJAZAH) ═══ -->
        <div class="card border-0 rounded-4 shadow-sm mb-4 text-white position-relative overflow-hidden" 
             style="background: linear-gradient(135deg, #064e3b 0%, #059669 60%, #10b981 100%);">
            <div class="card-body p-4 p-md-5 position-relative" style="z-index: 2;">
                <div class="row align-items-center g-3">
                    <div class="col-lg-8">
                        <span class="badge bg-white text-success fw-bold px-3 py-1 rounded-pill mb-2" style="font-size: 11.5px;">
                            <i class="bi bi-file-earmark-arrow-down-fill me-1"></i> DOKUMEN &amp; BERKAS PUBLIK
                        </span>
                        <h2 class="fw-bold mb-2 text-white">Unduhan Berkas &amp; Formulir</h2>
                        <p class="mb-3 text-white-50" style="font-size: 14px; max-width: 620px;">
                            Kelola berkas publik seperti formulir pendaftaran, SK penetapan, kalender akademik, modul, dan dokumen resmi madrasah dalam format PDF, Word, Excel, maupun ZIP.
                        </p>
                        <div class="d-flex flex-wrap gap-2 pt-1">
                            <a href="<?= base_url('website/download') ?>" target="_blank" class="btn btn-light text-dark fw-bold rounded-pill px-3 shadow-sm">
                                <i class="bi bi-box-arrow-up-right text-success me-1"></i> Pratinjau Unduhan Publik
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Hiasan Bulat Transparan -->
            <div style="position: absolute; right: -40px; top: -40px; width: 180px; height: 180px; background: radial-gradient(circle, rgba(255,255,255,0.2) 0%, transparent 70%); border-radius: 50%;"></div>
        </div>

        <div class="row g-4">
            <!-- Form Upload Berkas -->
            <div class="col-lg-4">
                <div class="card border-0 rounded-4 shadow-sm mb-4">
                    <div class="card-header bg-white border-bottom pt-3 pb-2 px-4">
                        <h6 class="fw-bold mb-0 text-dark"><i class="bi bi-cloud-arrow-up-fill text-success me-2"></i> Unggah Berkas Baru</h6>
                    </div>
                    <div class="card-body p-4">
                        <form method="post" action="<?= base_url('admin_website/save_download') ?>" enctype="multipart/form-data">
                            <div class="mb-3">
                                <label class="form-label fw-bold small text-muted">Nama / Judul Dokumen <span class="text-danger">*</span></label>
                                <input type="text" name="judul" class="form-control rounded-3" placeholder="Contoh: Formulir Pendaftaran PPDB 2026" required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-bold small text-muted">Tanggal Dokumen</label>
                                <input type="date" name="tanggal" class="form-control rounded-3" value="<?= date('Y-m-d') ?>">
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-bold small text-muted">Keterangan Singkat (Opsional)</label>
                                <textarea name="keterangan" class="form-control rounded-3" rows="2" placeholder="Catatan peruntukan berkas..."></textarea>
                            </div>

                            <div class="mb-4">
                                <label class="form-label fw-bold small text-muted">Pilih Berkas Dokumen <span class="text-danger">*</span></label>
                                <input type="file" name="file_download" class="form-control rounded-3" required>
                                <div class="form-text small mt-1">Mendukung format: PDF, DOC, DOCX, XLS, XLSX, PPT, PPTX, ZIP, RAR (Maks 10MB).</div>
                            </div>

                            <button type="submit" class="btn btn-success fw-bold rounded-pill w-100 py-2 shadow-sm">
                                <i class="bi bi-cloud-arrow-up-fill me-1"></i> Unggah Berkas
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Tabel Daftar Berkas -->
            <div class="col-lg-8">
                <div class="card border-0 rounded-4 shadow-sm mb-4">
                    <div class="card-header bg-white border-bottom pt-3 pb-2 px-4 d-flex justify-content-between align-items-center">
                        <h6 class="fw-bold mb-0 text-dark"><i class="bi bi-folder-fill text-success me-2"></i> Daftar Dokumen Tersedia</h6>
                        <span class="badge bg-success-subtle text-success rounded-pill fw-bold px-3 py-1"><?= count($downloads ?? []) ?> Berkas</span>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0" style="width:100%">
                                <thead class="bg-light">
                                    <tr>
                                        <th style="width:60px;" class="ps-4">Tipe</th>
                                        <th>Nama Berkas</th>
                                        <th style="width:130px;">Tanggal</th>
                                        <th style="width:140px;" class="text-end pe-4">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if(empty($downloads)): ?>
                                        <tr>
                                            <td colspan="4" class="text-center py-5 text-muted">
                                                <i class="bi bi-file-earmark-arrow-down fs-1 text-secondary mb-2 d-block opacity-50"></i>
                                                Belum ada berkas unduhan yang diunggah.
                                            </td>
                                        </tr>
                                    <?php else: ?>
                                        <?php foreach($downloads as $d): ?>
                                            <?php 
                                            $ext = strtolower(pathinfo($d->file_path ?? '', PATHINFO_EXTENSION)); 
                                            $iconClass = 'bi-file-earmark text-secondary';
                                            if($ext == 'pdf') $iconClass = 'bi-file-earmark-pdf-fill text-danger';
                                            elseif(in_array($ext, ['doc','docx'])) $iconClass = 'bi-file-earmark-word-fill text-primary';
                                            elseif(in_array($ext, ['xls','xlsx'])) $iconClass = 'bi-file-earmark-excel-fill text-success';
                                            elseif(in_array($ext, ['ppt','pptx'])) $iconClass = 'bi-file-earmark-ppt-fill text-warning';
                                            elseif(in_array($ext, ['zip','rar'])) $iconClass = 'bi-file-earmark-zip-fill text-warning';
                                            
                                            $file_url = base_url('assets/downloads/'.$d->file_path);
                                            ?>
                                            <tr>
                                                <td class="ps-4">
                                                    <i class="bi <?= $iconClass ?> fs-3"></i>
                                                </td>
                                                <td>
                                                    <div class="fw-bold text-dark" style="font-size:14px;"><?= htmlspecialchars($d->judul ?? '-', ENT_QUOTES, 'UTF-8') ?></div>
                                                    <?php if(!empty($d->keterangan)): ?>
                                                        <div class="small text-muted text-truncate" style="max-width: 280px;"><?= htmlspecialchars($d->keterangan, ENT_QUOTES, 'UTF-8') ?></div>
                                                    <?php endif; ?>
                                                    <div class="small text-muted mt-1" style="font-size:11px;">
                                                        <code><?= htmlspecialchars($d->file_path ?? '', ENT_QUOTES, 'UTF-8') ?></code>
                                                    </div>
                                                </td>
                                                <td>
                                                    <span class="small text-muted">
                                                        <i class="bi bi-calendar3 me-1"></i><?= !empty($d->tanggal) ? date('d M Y', strtotime($d->tanggal)) : (!empty($d->created_at) ? date('d M Y', strtotime($d->created_at)) : '-') ?>
                                                    </span>
                                                </td>
                                                <td class="text-end pe-4">
                                                    <div class="d-inline-flex gap-1">
                                                        <a href="<?= $file_url ?>" target="_blank" class="btn btn-sm btn-light rounded-pill px-3 py-1 text-primary shadow-sm" download title="Unduh Berkas">
                                                            <i class="bi bi-download me-1"></i> Unduh
                                                        </a>
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

<?php $this->load->view('templates/footer'); ?>
