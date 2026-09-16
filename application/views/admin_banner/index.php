<?php $this->load->view('templates/header'); ?>
<?php $this->load->view('templates/sidebar'); ?>

<div class="content">

    <!-- Page Header -->
    <div class="page-header">
        <div class="page-header-info">
            <span class="page-badge-label">
                <i class="bi bi-images"></i> Media Beranda
            </span>
            <h1 class="page-title">Banner Slider Beranda</h1>
            <p class="page-subtitle">Kelola gambar carousel dan highlight informasi utama yang tampil di beranda website.</p>
        </div>
        <div class="page-actions">
            <a href="<?= base_url() ?>" target="_blank" class="btn-modern-secondary">
                <i class="bi bi-box-arrow-up-right"></i> Lihat Beranda Publik
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
        <!-- Form Tambah Banner (Kiri) -->
        <div class="col-lg-4">
            <div class="modern-card">
                <div class="modern-card-header">
                    <div>
                        <h2 class="modern-card-title"><i class="bi bi-plus-circle-fill text-success"></i> Tambah Banner</h2>
                        <p class="modern-card-subtitle">Rekomendasi rasio landscape 16:9 (1600x700px).</p>
                    </div>
                </div>
                <div class="modern-card-body">
                    <form method="post" action="<?= base_url('admin_banner/save') ?>" enctype="multipart/form-data">
                        <div class="mb-3">
                            <label class="form-label-modern">Judul Banner <span class="text-danger">*</span></label>
                            <input type="text" name="judul" class="input-modern" placeholder="Contoh: Selamat Datang di MAN 3 Banjar" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label-modern">Subjudul</label>
                            <input type="text" name="subjudul" class="input-modern" placeholder="Contoh: Madrasah Unggul Berkarakter">
                        </div>

                        <div class="mb-3">
                            <label class="form-label-modern">Deskripsi Singkat</label>
                            <textarea name="deskripsi" class="textarea-modern" rows="3" placeholder="Teks penjelasan singkat pada slide..."></textarea>
                        </div>

                        <div class="mb-3">
                            <label class="form-label-modern">File Gambar <span class="text-danger">*</span></label>
                            <input type="file" name="gambar" class="input-modern" accept="image/*" required>
                            <span class="form-help-modern">Format: JPG, PNG, WEBP. Maksimal 8MB.</span>
                        </div>

                        <div class="row g-2 mb-3">
                            <div class="col-6">
                                <label class="form-label-modern">Teks Tombol</label>
                                <input type="text" name="button_text" class="input-modern" placeholder="Lihat Selengkapnya">
                            </div>
                            <div class="col-6">
                                <label class="form-label-modern">URL Tombol</label>
                                <input type="text" name="button_url" class="input-modern" placeholder="https://...">
                            </div>
                        </div>

                        <div class="row g-2 mb-4">
                            <div class="col-6">
                                <label class="form-label-modern">Status</label>
                                <select name="status" class="select-modern">
                                    <option value="Published">Published</option>
                                    <option value="Draft">Draft</option>
                                </select>
                            </div>
                            <div class="col-6">
                                <label class="form-label-modern">Urutan</label>
                                <input type="number" name="urutan" class="input-modern" value="0">
                            </div>
                        </div>

                        <button type="submit" class="btn-modern-primary w-100 justify-content-center">
                            <i class="bi bi-cloud-arrow-up-fill"></i> Simpan &amp; Tayangkan
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Daftar Banner (Kanan) -->
        <div class="col-lg-8">
            <div class="modern-card">
                <div class="modern-card-header">
                    <div>
                        <h2 class="modern-card-title"><i class="bi bi-collection-play-fill text-success"></i> Koleksi Banner Aktif</h2>
                        <p class="modern-card-subtitle">Banner berstatus Published akan dirotasi pada carousel beranda.</p>
                    </div>
                    <span class="pill-status pill-status-neutral"><?= count($banner ?? []) ?> Total</span>
                </div>
                <div class="modern-card-body">
                    <?php if(!empty($banner)): ?>
                        <div class="row g-3">
                            <?php foreach($banner as $b): ?>
                                <div class="col-md-6">
                                    <div class="modern-card h-100 d-flex flex-column border">
                                        <!-- Thumbnail Image -->
                                        <div style="height: 180px; background: #0f172a; position: relative; overflow: hidden; border-radius: 18px 18px 0 0;">
                                            <?php $file = FCPATH.'assets/banner/'.$b->gambar; ?>
                                            <?php if(!empty($b->gambar) && file_exists($file)): ?>
                                                <img src="<?= base_url('assets/banner/'.$b->gambar) ?>" alt="<?= htmlspecialchars($b->judul, ENT_QUOTES, 'UTF-8') ?>" style="width:100%; height:100%; object-fit:cover;">
                                            <?php else: ?>
                                                <div class="h-100 d-flex align-items-center justify-content-center text-muted small">
                                                    <i class="bi bi-image me-1"></i> Gambar tidak ditemukan
                                                </div>
                                            <?php endif; ?>

                                            <div style="position:absolute; top:12px; left:12px;">
                                                <?php if($b->status == 'Published'): ?>
                                                    <span class="pill-status pill-status-success shadow-sm">Published</span>
                                                <?php else: ?>
                                                    <span class="pill-status pill-status-warning shadow-sm">Draft</span>
                                                <?php endif; ?>
                                            </div>

                                            <div style="position:absolute; top:12px; right:12px;">
                                                <span class="badge bg-dark bg-opacity-75 text-white rounded-pill px-2 py-1 small">
                                                    Posisi #<?= (int)$b->urutan ?>
                                                </span>
                                            </div>
                                        </div>

                                        <!-- Info -->
                                        <div class="p-3 d-flex flex-column flex-grow-1">
                                            <h6 class="fw-bold mb-1 text-truncate" title="<?= htmlspecialchars($b->judul) ?>"><?= htmlspecialchars($b->judul) ?></h6>
                                            <?php if(!empty($b->subjudul)): ?>
                                                <div class="text-success small fw-semibold mb-2"><?= htmlspecialchars($b->subjudul) ?></div>
                                            <?php endif; ?>
                                            <p class="text-muted small mb-3 text-truncate-2 flex-grow-1" style="display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;">
                                                <?= htmlspecialchars($b->deskripsi ?? '-') ?>
                                            </p>

                                            <!-- Actions Toolbar -->
                                            <div class="d-flex align-items-center justify-content-between pt-2 border-top gap-1">
                                                <div>
                                                    <?php if($b->status == 'Published'): ?>
                                                        <a href="<?= base_url('admin_banner/draft/'.$b->id) ?>" class="btn-modern-light py-1 px-2 small text-warning" title="Ubah ke Draft">
                                                            <i class="bi bi-pause-circle"></i> Draft
                                                        </a>
                                                    <?php else: ?>
                                                        <a href="<?= base_url('admin_banner/publish/'.$b->id) ?>" class="btn-modern-light py-1 px-2 small text-success" title="Tayangkan">
                                                            <i class="bi bi-play-circle"></i> Publish
                                                        </a>
                                                    <?php endif; ?>
                                                </div>
                                                <div class="d-flex gap-1">
                                                    <button type="button" class="btn-icon-modern" onclick="openEditBanner(<?= (int)$b->id ?>)" title="Edit Banner">
                                                        <i class="bi bi-pencil-square"></i>
                                                    </button>
                                                    <a href="<?= base_url('admin_banner/delete/'.$b->id) ?>" class="btn-icon-modern btn-danger-icon" onclick="return confirm('Hapus banner ini permanen?')" title="Hapus Banner">
                                                        <i class="bi bi-trash"></i>
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Modal Edit Modern -->
                                <div class="modal fade" id="editBannerModal<?= (int)$b->id ?>" tabindex="-1" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered modal-lg">
                                        <div class="modal-content border-0 rounded-4 shadow-lg overflow-hidden">
                                            <div class="modal-header border-bottom px-4 py-3 bg-light">
                                                <h5 class="modal-title fw-bold" style="font-size:16px;">
                                                    <i class="bi bi-pencil-square text-success me-2"></i> Edit Banner Slider
                                                </h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body p-4">
                                                <form method="post" action="<?= base_url('admin_banner/update/'.$b->id) ?>" enctype="multipart/form-data">
                                                    <div class="mb-3">
                                                        <label class="form-label-modern">Judul Banner <span class="text-danger">*</span></label>
                                                        <input type="text" name="judul" class="input-modern" value="<?= htmlspecialchars($b->judul, ENT_QUOTES, 'UTF-8') ?>" required>
                                                    </div>

                                                    <div class="mb-3">
                                                        <label class="form-label-modern">Subjudul</label>
                                                        <input type="text" name="subjudul" class="input-modern" value="<?= htmlspecialchars($b->subjudul ?? '', ENT_QUOTES, 'UTF-8') ?>">
                                                    </div>

                                                    <div class="mb-3">
                                                        <label class="form-label-modern">Deskripsi Singkat</label>
                                                        <textarea name="deskripsi" class="textarea-modern" rows="3"><?= htmlspecialchars($b->deskripsi ?? '', ENT_QUOTES, 'UTF-8') ?></textarea>
                                                    </div>

                                                    <div class="mb-3">
                                                        <label class="form-label-modern">Ganti Gambar (Opsional)</label>
                                                        <input type="file" name="gambar" class="input-modern" accept="image/*">
                                                        <span class="form-help-modern">Kosongkan jika tidak ingin mengganti gambar.</span>
                                                    </div>

                                                    <div class="row g-2 mb-3">
                                                        <div class="col-6">
                                                            <label class="form-label-modern">Teks Tombol</label>
                                                            <input type="text" name="button_text" class="input-modern" value="<?= htmlspecialchars($b->button_text ?? '', ENT_QUOTES, 'UTF-8') ?>">
                                                        </div>
                                                        <div class="col-6">
                                                            <label class="form-label-modern">URL Tombol</label>
                                                            <input type="text" name="button_url" class="input-modern" value="<?= htmlspecialchars($b->button_url ?? '', ENT_QUOTES, 'UTF-8') ?>">
                                                        </div>
                                                    </div>

                                                    <div class="row g-2 mb-4">
                                                        <div class="col-6">
                                                            <label class="form-label-modern">Status</label>
                                                            <select name="status" class="select-modern">
                                                                <option value="Published" <?= $b->status == 'Published' ? 'selected' : '' ?>>Published</option>
                                                                <option value="Draft" <?= $b->status == 'Draft' ? 'selected' : '' ?>>Draft</option>
                                                            </select>
                                                        </div>
                                                        <div class="col-6">
                                                            <label class="form-label-modern">Urutan</label>
                                                            <input type="number" name="urutan" class="input-modern" value="<?= (int)$b->urutan ?>">
                                                        </div>
                                                    </div>

                                                    <div class="d-flex justify-content-end gap-2 pt-2 border-top">
                                                        <button type="button" class="btn-modern-secondary" data-bs-dismiss="modal">Batal</button>
                                                        <button type="submit" class="btn-modern-primary">Simpan Perubahan</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php else: ?>
                        <div class="empty-state-modern">
                            <div class="empty-state-icon">
                                <i class="bi bi-images"></i>
                            </div>
                            <div class="empty-state-title">Belum ada banner slider</div>
                            <p class="empty-state-desc">Tambahkan banner slide pertama Anda melalui formulir di sisi kiri.</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

</div>

<script>
function openEditBanner(id){
    var modal = new bootstrap.Modal(document.getElementById('editBannerModal' + id));
    modal.show();
}
</script>

<?php $this->load->view('templates/footer'); ?>
