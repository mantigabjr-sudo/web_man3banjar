<?php $this->load->view('templates/header'); ?>
<?php $this->load->view('templates/sidebar'); ?>

<div class="content">

    <!-- Page Header -->
    <div class="page-header">
        <div class="page-header-info">
            <span class="page-badge-label">
                <i class="bi bi-card-image"></i> Publikasi Visual
            </span>
            <h1 class="page-title">Pamflet Informasi</h1>
            <p class="page-subtitle">Kelola pamflet digital, pengumuman grafis, dan brosur kegiatan madrasah.</p>
        </div>
        <div class="page-actions">
            <a href="<?= base_url('website/pamflet') ?>" target="_blank" class="btn-modern-secondary">
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
        <!-- Form Tambah Pamflet (Kiri) -->
        <div class="col-lg-4">
            <div class="modern-card">
                <div class="modern-card-header">
                    <div>
                        <h2 class="modern-card-title"><i class="bi bi-plus-circle-fill text-success"></i> Upload Pamflet Baru</h2>
                        <p class="modern-card-subtitle">Pamflet otomatis tersimpan sebagai Draft.</p>
                    </div>
                </div>
                <div class="modern-card-body">
                    <form method="post" action="<?= base_url('admin_website/save_pamflet') ?>" enctype="multipart/form-data">
                        <div class="mb-3">
                            <label class="form-label-modern">Judul Pamflet <span class="text-danger">*</span></label>
                            <input type="text" name="judul" class="input-modern" placeholder="Contoh: Brosur PPDB 2026/2027" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label-modern">Tanggal Penerbitan</label>
                            <input type="date" name="tanggal" class="input-modern" value="<?= date('Y-m-d') ?>">
                        </div>

                        <div class="mb-3">
                            <label class="form-label-modern">Deskripsi Singkat</label>
                            <textarea name="deskripsi" class="textarea-modern" rows="3" placeholder="Informasi singkat isi pamflet..."></textarea>
                        </div>

                        <div class="mb-4">
                            <label class="form-label-modern">File Gambar Pamflet <span class="text-danger">*</span></label>
                            <div class="p-3 border rounded-3 bg-light text-center mb-2" id="previewWrapper" style="min-height: 140px; display:flex; align-items:center; justify-content:center;">
                                <div id="previewPamflet" class="text-muted small">
                                    <i class="bi bi-image fs-3 d-block mb-1 text-secondary"></i>
                                    Preview pamflet akan tampil di sini
                                </div>
                            </div>
                            <input type="file" name="gambar" id="gambarPamflet" class="input-modern" accept="image/*" required>
                            <span class="form-help-modern">Format JPG, PNG, WEBP. Maks 4MB.</span>
                        </div>

                        <button type="submit" class="btn-modern-primary w-100 justify-content-center">
                            <i class="bi bi-cloud-arrow-up-fill"></i> Simpan Pamflet
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Daftar Pamflet (Kanan) -->
        <div class="col-lg-8">
            <div class="modern-card">
                <div class="modern-card-header">
                    <div>
                        <h2 class="modern-card-title"><i class="bi bi-card-heading text-success"></i> Arsip Pamflet</h2>
                        <p class="modern-card-subtitle">Pamflet Published akan tampil di galeri pamflet publik.</p>
                    </div>
                    <span class="pill-status pill-status-neutral"><?= count($pamflet ?? []) ?> Pamflet</span>
                </div>
                <div class="modern-card-body p-0">
                    <div class="table-responsive">
                        <table class="table-modern datatable">
                            <thead>
                                <tr>
                                    <th style="width: 70px;">Media</th>
                                    <th>Judul &amp; Keterangan</th>
                                    <th>Tanggal</th>
                                    <th>Status</th>
                                    <th class="text-end" style="width: 140px;">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if(!empty($pamflet)): ?>
                                    <?php foreach($pamflet as $p): ?>
                                        <?php $gambar_file = !empty($p->gambar) ? FCPATH.'assets/pamflet/'.$p->gambar : ''; ?>
                                        <tr>
                                            <td>
                                                <div style="width: 54px; height: 54px; border-radius: 10px; overflow: hidden; background: #f1f5f9; border: 1px solid #e2e8f0; display:flex; align-items:center; justify-content:center;">
                                                    <?php if(!empty($p->gambar) && file_exists($gambar_file)): ?>
                                                        <img src="<?= base_url('assets/pamflet/'.$p->gambar) ?>" alt="Pamflet" style="width:100%; height:100%; object-fit:cover;">
                                                    <?php else: ?>
                                                        <i class="bi bi-image text-muted"></i>
                                                    <?php endif; ?>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="fw-bold" style="font-size: 14px;"><?= htmlspecialchars($p->judul) ?></div>
                                                <?php if(!empty($p->deskripsi)): ?>
                                                    <div class="text-muted small text-truncate" style="max-width: 280px;"><?= htmlspecialchars($p->deskripsi) ?></div>
                                                <?php endif; ?>
                                            </td>
                                            <td class="text-muted small">
                                                <?= !empty($p->tanggal) ? date('d M Y', strtotime($p->tanggal)) : '-' ?>
                                            </td>
                                            <td>
                                                <?php if($p->status == 'Published'): ?>
                                                    <span class="pill-status pill-status-success">Published</span>
                                                <?php else: ?>
                                                    <span class="pill-status pill-status-warning">Draft</span>
                                                <?php endif; ?>
                                            </td>
                                            <td class="text-end">
                                                <div class="d-inline-flex gap-1">
                                                    <?php if(!empty($p->gambar) && file_exists($gambar_file)): ?>
                                                        <a href="<?= base_url('assets/pamflet/'.$p->gambar) ?>" target="_blank" class="btn-icon-modern" title="Lihat Ukuran Asli">
                                                            <i class="bi bi-eye"></i>
                                                        </a>
                                                    <?php endif; ?>
                                                    <?php if($p->status == 'Published'): ?>
                                                        <a href="<?= base_url('admin_website/draft_pamflet/'.$p->id) ?>" class="btn-icon-modern" onclick="return confirm('Ubah status ke Draft?')" title="Kembalikan ke Draft">
                                                            <i class="bi bi-pause-circle text-warning"></i>
                                                        </a>
                                                    <?php else: ?>
                                                        <a href="<?= base_url('admin_website/publish_pamflet/'.$p->id) ?>" class="btn-icon-modern" onclick="return confirm('Publikasikan pamflet ini?')" title="Tayangkan">
                                                            <i class="bi bi-play-circle text-success"></i>
                                                        </a>
                                                    <?php endif; ?>
                                                    <a href="<?= base_url('admin_website/delete_pamflet/'.$p->id) ?>" class="btn-icon-modern btn-danger-icon" onclick="return confirm('Hapus pamflet ini?')" title="Hapus Pamflet">
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
                                                    <i class="bi bi-card-image"></i>
                                                </div>
                                                <div class="empty-state-title">Belum ada pamflet informasi</div>
                                                <p class="empty-state-desc">Unggah pamflet pertama Anda melalui formulir di samping.</p>
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
    const input = document.getElementById('gambarPamflet');
    const preview = document.getElementById('previewPamflet');

    if(input && preview){
        input.addEventListener('change', function(){
            const file = this.files[0];
            if(!file){
                preview.innerHTML = '<i class="bi bi-image fs-3 d-block mb-1 text-secondary"></i>Preview pamflet akan tampil di sini';
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