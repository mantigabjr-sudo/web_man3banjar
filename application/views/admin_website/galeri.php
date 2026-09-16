<?php $this->load->view('templates/header'); ?>
<?php $this->load->view('templates/sidebar'); ?>

<div class="content">

    <!-- Page Header -->
    <div class="page-header">
        <div class="page-header-info">
            <span class="page-badge-label">
                <i class="bi bi-camera-reels-fill"></i> Dokumentasi Kegiatan
            </span>
            <h1 class="page-title">Galeri Foto Madrasah</h1>
            <p class="page-subtitle">Kelola dokumentasi visual kegiatan belajar mengajar, acara madrasah, dan prestasi siswa.</p>
        </div>
        <div class="page-actions">
            <a href="<?= base_url('website/galeri') ?>" target="_blank" class="btn-modern-secondary">
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
        <!-- Form Tambah Galeri (Kiri) -->
        <div class="col-lg-4">
            <div class="modern-card">
                <div class="modern-card-header">
                    <div>
                        <h2 class="modern-card-title"><i class="bi bi-plus-circle-fill text-success"></i> Upload Foto Galeri</h2>
                        <p class="modern-card-subtitle">Foto tersimpan sebagai Draft sebelum dipublish.</p>
                    </div>
                </div>
                <div class="modern-card-body">
                    <form method="post" action="<?= base_url('admin_website/save_galeri') ?>" enctype="multipart/form-data">
                        <div class="mb-3">
                            <label class="form-label-modern">Judul / Nama Kegiatan <span class="text-danger">*</span></label>
                            <input type="text" name="judul" class="input-modern" placeholder="Contoh: Upacara Hari Guru 2026" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label-modern">Tanggal Kegiatan</label>
                            <input type="date" name="tanggal" class="input-modern" value="<?= date('Y-m-d') ?>">
                        </div>

                        <div class="mb-3">
                            <label class="form-label-modern">Deskripsi / Keterangan</label>
                            <textarea name="deskripsi" class="textarea-modern" rows="3" placeholder="Penjelasan singkat suasana kegiatan..."></textarea>
                        </div>

                        <div class="mb-4">
                            <label class="form-label-modern">File Gambar <span class="text-danger">*</span></label>
                            <div class="p-3 border rounded-3 bg-light text-center mb-2" id="previewWrapper" style="min-height: 140px; display:flex; align-items:center; justify-content:center;">
                                <div id="previewGaleri" class="text-muted small">
                                    <i class="bi bi-image fs-3 d-block mb-1 text-secondary"></i>
                                    Preview foto akan tampil di sini
                                </div>
                            </div>
                            <input type="file" name="gambar" id="gambarGaleri" class="input-modern" accept="image/*" required>
                            <span class="form-help-modern">Format: JPG, PNG, WEBP. Maks 4MB.</span>
                        </div>

                        <button type="submit" class="btn-modern-primary w-100 justify-content-center">
                            <i class="bi bi-cloud-arrow-up-fill"></i> Simpan Galeri
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Daftar Galeri (Kanan) -->
        <div class="col-lg-8">
            <div class="modern-card">
                <div class="modern-card-header">
                    <div>
                        <h2 class="modern-card-title"><i class="bi bi-images text-success"></i> Koleksi Foto Galeri</h2>
                        <p class="modern-card-subtitle">Foto berstatus Published akan tampil pada halaman galeri publik.</p>
                    </div>
                    <span class="pill-status pill-status-neutral"><?= count($galeri ?? []) ?> Foto</span>
                </div>
                <div class="modern-card-body p-0">
                    <div class="table-responsive">
                        <table class="table-modern datatable">
                            <thead>
                                <tr>
                                    <th style="width: 70px;">Foto</th>
                                    <th>Judul Kegiatan</th>
                                    <th>Tanggal</th>
                                    <th>Status</th>
                                    <th class="text-end" style="width: 140px;">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if(!empty($galeri)): ?>
                                    <?php foreach($galeri as $g): ?>
                                        <?php $gambar_file = !empty($g->gambar) ? FCPATH.'assets/galeri/'.$g->gambar : ''; ?>
                                        <tr>
                                            <td>
                                                <div style="width: 56px; height: 56px; border-radius: 10px; overflow: hidden; background: #f1f5f9; border: 1px solid #e2e8f0; display:flex; align-items:center; justify-content:center;">
                                                    <?php if(!empty($g->gambar) && file_exists($gambar_file)): ?>
                                                        <img src="<?= base_url('assets/galeri/'.$g->gambar) ?>" alt="Galeri" style="width:100%; height:100%; object-fit:cover;">
                                                    <?php else: ?>
                                                        <i class="bi bi-image text-muted"></i>
                                                    <?php endif; ?>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="fw-bold" style="font-size: 14px;"><?= htmlspecialchars($g->judul) ?></div>
                                                <?php if(!empty($g->deskripsi)): ?>
                                                    <div class="text-muted small text-truncate" style="max-width: 280px;"><?= htmlspecialchars($g->deskripsi) ?></div>
                                                <?php endif; ?>
                                            </td>
                                            <td class="text-muted small">
                                                <?= !empty($g->tanggal) ? date('d M Y', strtotime($g->tanggal)) : '-' ?>
                                            </td>
                                            <td>
                                                <?php if($g->status == 'Published'): ?>
                                                    <span class="pill-status pill-status-success">Published</span>
                                                <?php else: ?>
                                                    <span class="pill-status pill-status-warning">Draft</span>
                                                <?php endif; ?>
                                            </td>
                                            <td class="text-end">
                                                <div class="d-inline-flex gap-1">
                                                    <?php if(!empty($g->gambar) && file_exists($gambar_file)): ?>
                                                        <a href="<?= base_url('assets/galeri/'.$g->gambar) ?>" target="_blank" class="btn-icon-modern" title="Lihat Foto">
                                                            <i class="bi bi-eye"></i>
                                                        </a>
                                                    <?php endif; ?>
                                                    <?php if($g->status == 'Published'): ?>
                                                        <a href="<?= base_url('admin_website/draft_galeri/'.$g->id) ?>" class="btn-icon-modern" onclick="return confirm('Ubah ke Draft?')" title="Kembalikan ke Draft">
                                                            <i class="bi bi-pause-circle text-warning"></i>
                                                        </a>
                                                    <?php else: ?>
                                                        <a href="<?= base_url('admin_website/publish_galeri/'.$g->id) ?>" class="btn-icon-modern" onclick="return confirm('Publish foto ini?')" title="Tayangkan">
                                                            <i class="bi bi-play-circle text-success"></i>
                                                        </a>
                                                    <?php endif; ?>
                                                    <a href="<?= base_url('admin_website/delete_galeri/'.$g->id) ?>" class="btn-icon-modern btn-danger-icon" onclick="return confirm('Hapus foto ini?')" title="Hapus Foto">
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
                                                    <i class="bi bi-images"></i>
                                                </div>
                                                <div class="empty-state-title">Belum ada foto kegiatan</div>
                                                <p class="empty-state-desc">Tambahkan foto pertama Anda melalui formulir di samping.</p>
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

<script>
document.addEventListener('DOMContentLoaded', function(){
    const input = document.getElementById('gambarGaleri');
    const preview = document.getElementById('previewGaleri');

    if(input && preview){
        input.addEventListener('change', function(){
            const file = this.files[0];
            if(!file){
                preview.innerHTML = '<i class="bi bi-image fs-3 d-block mb-1 text-secondary"></i>Preview foto akan tampil di sini';
                return;
            }
            const reader = new FileReader();
            reader.onload = function(e){
                preview.innerHTML = '<img src="'+e.target.result+'" alt="Preview" style="max-height:160px; max-width:100%; border-radius:10px; object-fit:contain;">';
            };
            reader.readAsDataURL(file);
        });
    }
});
</script>

<?php $this->load->view('templates/footer'); ?>