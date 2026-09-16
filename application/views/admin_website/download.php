<?php $this->load->view('templates/header'); ?>
<?php $this->load->view('templates/sidebar'); ?>

<div class="content">

    <!-- Page Header -->
    <div class="page-header">
        <div class="page-header-info">
            <span class="page-badge-label">
                <i class="bi bi-cloud-arrow-down-fill"></i> Pusat Unduhan
            </span>
            <h1 class="page-title">Data Unduhan Berkas</h1>
            <p class="page-subtitle">Kelola file formulir, panduan, silabus, dan dokumen publik yang dapat diunduh bebas oleh siswa/wali.</p>
        </div>
        <div class="page-actions">
            <a href="<?= base_url('website/download') ?>" target="_blank" class="btn-modern-secondary">
                <i class="bi bi-box-arrow-up-right"></i> Lihat di Website
            </a>
        </div>
    </div>

    <?php if($this->session->flashdata('success')): ?>
        <div class="alert alert-success border-0 rounded-4 shadow-sm mb-4 d-flex align-items-center gap-2">
            <i class="bi bi-check-circle-fill fs-5"></i>
            <div><?= $this->session->flashdata('success') ?></div>
        </div>
    <?php endif; ?>
    <?php if($this->session->flashdata('error')): ?>
        <div class="alert alert-danger border-0 rounded-4 shadow-sm mb-4 d-flex align-items-center gap-2">
            <i class="bi bi-exclamation-triangle-fill fs-5"></i>
            <div><?= $this->session->flashdata('error') ?></div>
        </div>
    <?php endif; ?>

    <div class="row g-4">
        <!-- Form Upload Berkas (Kiri) -->
        <div class="col-lg-4">
            <div class="modern-card">
                <div class="modern-card-header">
                    <div>
                        <h2 class="modern-card-title"><i class="bi bi-cloud-arrow-up-fill text-success"></i> Upload Dokumen Baru</h2>
                        <p class="modern-card-subtitle">File otomatis tayang di halaman unduhan publik.</p>
                    </div>
                </div>
                <div class="modern-card-body">
                    <form method="post" action="<?= base_url('admin_website/save_download') ?>" enctype="multipart/form-data">
                        <div class="mb-3">
                            <label class="form-label-modern">Nama / Judul Dokumen <span class="text-danger">*</span></label>
                            <input type="text" name="judul" class="input-modern" placeholder="Contoh: Formulir Pendaftaran Ulang PPDB" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label-modern">Tanggal Dokumen</label>
                            <input type="date" name="tanggal" class="input-modern" value="<?= date('Y-m-d') ?>">
                        </div>

                        <div class="mb-3">
                            <label class="form-label-modern">Keterangan Dokumen</label>
                            <textarea name="keterangan" class="textarea-modern" rows="3" placeholder="Informasi singkat peruntukan file ini..."></textarea>
                        </div>

                        <div class="mb-4">
                            <label class="form-label-modern">File Berkas <span class="text-danger">*</span></label>
                            <input type="file" name="file_download" class="input-modern" accept=".pdf,.doc,.docx,.xls,.xlsx,.ppt,.pptx,.zip,.rar" required>
                            <span class="form-help-modern">Format: PDF, Word, Excel, PowerPoint, ZIP, RAR. Maks 10MB.</span>
                        </div>

                        <button type="submit" class="btn-modern-primary w-100 justify-content-center">
                            <i class="bi bi-cloud-arrow-up-fill"></i> Upload &amp; Publikasikan
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Daftar Dokumen (Kanan) -->
        <div class="col-lg-8">
            <div class="modern-card">
                <div class="modern-card-header">
                    <div>
                        <h2 class="modern-card-title"><i class="bi bi-folder2-open text-success"></i> Berkas Terpublikasi</h2>
                        <p class="modern-card-subtitle">Daftar file yang siap diunduh oleh publik.</p>
                    </div>
                    <span class="pill-status pill-status-neutral"><?= count($downloads ?? []) ?> File</span>
                </div>
                <div class="modern-card-body p-0">
                    <div class="table-responsive">
                        <table class="table-modern datatable">
                            <thead>
                                <tr>
                                    <th style="width: 50px;">Tipe</th>
                                    <th>Nama Berkas &amp; Deskripsi</th>
                                    <th>Tanggal</th>
                                    <th class="text-end" style="width: 130px;">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if(!empty($downloads)): ?>
                                    <?php foreach($downloads as $d): ?>
                                        <?php
                                            $ext = strtolower(pathinfo($d->file_path, PATHINFO_EXTENSION));
                                            $icon = 'bi-file-earmark-text text-secondary';
                                            if($ext == 'pdf') $icon = 'bi-file-earmark-pdf-fill text-danger';
                                            elseif(in_array($ext, ['doc','docx'])) $icon = 'bi-file-earmark-word-fill text-primary';
                                            elseif(in_array($ext, ['xls','xlsx'])) $icon = 'bi-file-earmark-excel-fill text-success';
                                            elseif(in_array($ext, ['zip','rar'])) $icon = 'bi-file-earmark-zip-fill text-warning';
                                        ?>
                                        <tr>
                                            <td>
                                                <div style="width: 44px; height: 44px; border-radius: 12px; background: #f8fafc; border: 1px solid #e2e8f0; display:flex; align-items:center; justify-content:center; font-size: 20px;">
                                                    <i class="bi <?= $icon ?>"></i>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="fw-bold" style="font-size: 14px;"><?= htmlspecialchars($d->judul) ?></div>
                                                <div class="text-muted small text-truncate" style="max-width: 320px;">
                                                    <?= htmlspecialchars($d->keterangan ?? '') ?>
                                                </div>
                                            </td>
                                            <td class="text-muted small">
                                                <?= !empty($d->tanggal) ? date('d M Y', strtotime($d->tanggal)) : '-' ?>
                                            </td>
                                            <td class="text-end">
                                                <div class="d-inline-flex gap-1">
                                                    <a href="<?= base_url('assets/downloads/'.$d->file_path) ?>" target="_blank" class="btn-icon-modern" title="Download File">
                                                        <i class="bi bi-download text-success"></i>
                                                    </a>
                                                    <a href="<?= base_url('admin_website/delete_download/'.$d->id) ?>" class="btn-icon-modern btn-danger-icon" onclick="return confirm('Hapus file ini permanen?')" title="Hapus File">
                                                        <i class="bi bi-trash"></i>
                                                    </a>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="4">
                                            <div class="empty-state-modern py-4">
                                                <div class="empty-state-icon">
                                                    <i class="bi bi-folder-x"></i>
                                                </div>
                                                <div class="empty-state-title">Belum ada file dokumen</div>
                                                <p class="empty-state-desc">Unggah file dokumen pertama Anda melalui formulir di samping.</p>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>

<?php $this->load->view('templates/footer'); ?>
