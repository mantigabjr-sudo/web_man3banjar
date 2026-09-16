<?php $this->load->view('templates/header'); ?>
<?php $this->load->view('templates/sidebar'); ?>

<div class="content">

    <!-- Page Header -->
    <div class="page-header">
        <div class="page-header-info">
            <span class="page-badge-label">
                <i class="bi bi-play-btn-fill"></i> Video Streaming
            </span>
            <h1 class="page-title">Video Profil Madrasah</h1>
            <p class="page-subtitle">Kelola sematan video profil dari YouTube yang ditayangkan pada halaman depan website.</p>
        </div>
        <div class="page-actions">
            <a href="<?= base_url() ?>" target="_blank" class="btn-modern-secondary">
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
        <!-- Form Tambah Video (Kiri) -->
        <div class="col-lg-4">
            <div class="modern-card">
                <div class="modern-card-header">
                    <div>
                        <h2 class="modern-card-title"><i class="bi bi-plus-circle-fill text-success"></i> Tambah Video YouTube</h2>
                        <p class="modern-card-subtitle">Video baru otomatis tersimpan sebagai Draft.</p>
                    </div>
                </div>
                <div class="modern-card-body">
                    <form method="post" action="<?= base_url('admin_website/save_video') ?>">
                        <div class="mb-3">
                            <label class="form-label-modern">Judul Video <span class="text-danger">*</span></label>
                            <input type="text" name="judul" class="input-modern" placeholder="Contoh: Profil Resmi MAN 3 Banjar 2026" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label-modern">URL YouTube <span class="text-danger">*</span></label>
                            <input type="text" name="youtube_url" class="input-modern" placeholder="https://www.youtube.com/watch?v=..." required>
                            <span class="form-help-modern">Format tautan standar watch atau share YouTube.</span>
                        </div>

                        <div class="mb-4">
                            <label class="form-label-modern">Deskripsi Singkat</label>
                            <textarea name="deskripsi" class="textarea-modern" rows="3" placeholder="Keterangan singkat tentang isi video..."></textarea>
                        </div>

                        <button type="submit" class="btn-modern-primary w-100 justify-content-center">
                            <i class="bi bi-cloud-arrow-up-fill"></i> Simpan Video
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Daftar Video (Kanan) -->
        <div class="col-lg-8">
            <div class="modern-card">
                <div class="modern-card-header">
                    <div>
                        <h2 class="modern-card-title"><i class="bi bi-collection-play-fill text-success"></i> Katalog Video</h2>
                        <p class="modern-card-subtitle">Hanya 1 video dengan status <strong>Published</strong> yang tayang di halaman utama.</p>
                    </div>
                    <span class="pill-status pill-status-neutral"><?= count($video ?? []) ?> Video</span>
                </div>
                <div class="modern-card-body p-0">
                    <div class="table-responsive">
                        <table class="table-modern">
                            <thead>
                                <tr>
                                    <th style="width: 80px;">Preview</th>
                                    <th>Judul &amp; URL Video</th>
                                    <th>Status</th>
                                    <th>Tanggal</th>
                                    <th class="text-end" style="width: 140px;">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if(!empty($video)): ?>
                                    <?php foreach($video as $v): ?>
                                        <?php 
                                            // Ekstrak YouTube ID untuk thumbnail
                                            preg_match('%(?:youtube(?:-nocookie)?\.com/(?:[^/]+/.+/|(?:v|e(?:mbed)?)/|.*[?&]v=)|youtu\.be/)([^"&?/ ]{11})%i', $v->youtube_url, $match);
                                            $yt_id = $match[1] ?? '';
                                        ?>
                                        <tr>
                                            <td>
                                                <div style="width: 72px; height: 48px; border-radius: 8px; overflow: hidden; background: #0f172a; position: relative;">
                                                    <?php if(!empty($yt_id)): ?>
                                                        <img src="https://img.youtube.com/vi/<?= $yt_id ?>/mqdefault.jpg" alt="Thumbnail" style="width:100%; height:100%; object-fit:cover;">
                                                    <?php else: ?>
                                                        <div class="w-100 h-100 d-flex align-items-center justify-content-center text-white-50 fs-6">
                                                            <i class="bi bi-youtube"></i>
                                                        </div>
                                                    <?php endif; ?>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="fw-bold" style="font-size: 14px;"><?= htmlspecialchars($v->judul) ?></div>
                                                <a href="<?= htmlspecialchars($v->youtube_url) ?>" target="_blank" class="text-muted small text-decoration-none text-truncate d-block" style="max-width: 260px;">
                                                    <i class="bi bi-box-arrow-up-right me-1"></i><?= htmlspecialchars($v->youtube_url) ?>
                                                </a>
                                            </td>
                                            <td>
                                                <?php if($v->status == 'Published'): ?>
                                                    <span class="pill-status pill-status-success">Published</span>
                                                <?php else: ?>
                                                    <span class="pill-status pill-status-warning">Draft</span>
                                                <?php endif; ?>
                                            </td>
                                            <td class="text-muted small">
                                                <?= !empty($v->created_at) ? date('d M Y', strtotime($v->created_at)) : '-' ?>
                                            </td>
                                            <td class="text-end">
                                                <div class="d-inline-flex gap-1">
                                                    <?php if($v->status == 'Published'): ?>
                                                        <a href="<?= base_url('admin_website/draft_video/'.$v->id) ?>" class="btn-icon-modern" onclick="return confirm('Ubah status ke Draft?')" title="Jadikan Draft">
                                                            <i class="bi bi-pause-circle text-warning"></i>
                                                        </a>
                                                    <?php else: ?>
                                                        <a href="<?= base_url('admin_website/publish_video/'.$v->id) ?>" class="btn-icon-modern" onclick="return confirm('Jadikan video utama di beranda?')" title="Tayangkan">
                                                            <i class="bi bi-play-circle text-success"></i>
                                                        </a>
                                                    <?php endif; ?>
                                                    <a href="<?= base_url('admin_website/delete_video/'.$v->id) ?>" class="btn-icon-modern btn-danger-icon" onclick="return confirm('Hapus video ini?')" title="Hapus Video">
                                                        <i class="bi bi-trash"></i>
                                                    </a>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="5">
                                            <div class="empty-state-modern py-4">
                                                <div class="empty-state-icon">
                                                    <i class="bi bi-youtube"></i>
                                                </div>
                                                <div class="empty-state-title">Belum ada video profil</div>
                                                <p class="empty-state-desc">Tambahkan tautan YouTube melalui formulir di sisi kiri.</p>
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