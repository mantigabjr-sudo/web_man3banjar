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
                            <i class="bi bi-camera-reels-fill me-1"></i> DOKUMENTASI VISUAL MADRASAH
                        </span>
                        <h2 class="fw-bold mb-2 text-white">Galeri Foto Kegiatan</h2>
                        <p class="mb-3 text-white-50" style="font-size: 14px; max-width: 620px;">
                            Kelola album foto dokumentasi kegiatan belajar, perlombaan, upacara, ekstrakurikuler, dan momen prestasi MAN 3 Banjar. Terbitkan foto agar tampil pada galeri website publik.
                        </p>
                        <div class="d-flex flex-wrap gap-2 pt-1">
                            <a href="<?= base_url('website/galeri') ?>" target="_blank" class="btn btn-light text-dark fw-bold rounded-pill px-3 shadow-sm">
                                <i class="bi bi-box-arrow-up-right text-success me-1"></i> Pratinjau Galeri Publik
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Hiasan Bulat Transparan -->
            <div style="position: absolute; right: -40px; top: -40px; width: 180px; height: 180px; background: radial-gradient(circle, rgba(255,255,255,0.2) 0%, transparent 70%); border-radius: 50%;"></div>
        </div>

        <div class="row g-4">
            <!-- Form Upload Galeri -->
            <div class="col-lg-4">
                <div class="card border-0 rounded-4 shadow-sm mb-4">
                    <div class="card-header bg-white border-bottom pt-3 pb-2 px-4">
                        <h6 class="fw-bold mb-0 text-dark"><i class="bi bi-cloud-arrow-up-fill text-success me-2"></i> Tambah Foto Galeri</h6>
                    </div>
                    <div class="card-body p-4">
                        <form method="post" action="<?= base_url('admin_website/save_galeri') ?>" enctype="multipart/form-data">
                            <div class="mb-3">
                                <label class="form-label fw-bold small text-muted">Judul / Keterangan Foto <span class="text-danger">*</span></label>
                                <input type="text" name="judul" class="form-control rounded-3" placeholder="Contoh: Upacara Hari Santri Nasional" required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-bold small text-muted">Tanggal Kegiatan</label>
                                <input type="date" name="tanggal" class="form-control rounded-3" value="<?= date('Y-m-d') ?>">
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-bold small text-muted">Deskripsi Tambahan (Opsional)</label>
                                <textarea name="deskripsi" class="form-control rounded-3" rows="3" placeholder="Cerita singkat atau catatan dokumentasi..."></textarea>
                            </div>

                            <div class="mb-4">
                                <label class="form-label fw-bold small text-muted">Pilih File Foto <span class="text-danger">*</span></label>
                                <input type="file" name="gambar" class="form-control rounded-3 mb-2" id="inputGaleri" accept="image/*" required>
                                
                                <div class="p-2 border rounded-3 bg-light text-center" id="previewGaleri" style="min-height: 120px; display: flex; align-items: center; justify-content: center; color: #94a3b8; font-size: 12px;">
                                    Pratinjau Foto
                                </div>
                            </div>

                            <button type="submit" class="btn btn-success fw-bold rounded-pill w-100 py-2 shadow-sm">
                                <i class="bi bi-cloud-arrow-up-fill me-1"></i> Unggah ke Galeri
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Tabel Galeri -->
            <div class="col-lg-8">
                <div class="card border-0 rounded-4 shadow-sm mb-4">
                    <div class="card-header bg-white border-bottom pt-3 pb-2 px-4 d-flex justify-content-between align-items-center">
                        <h6 class="fw-bold mb-0 text-dark"><i class="bi bi-images text-success me-2"></i> Koleksi Foto Galeri</h6>
                        <span class="badge bg-success-subtle text-success rounded-pill fw-bold px-3 py-1"><?= count($galeri ?? []) ?> Foto</span>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0" style="width:100%">
                                <thead class="bg-light">
                                    <tr>
                                        <th style="width:100px;" class="ps-4">Preview</th>
                                        <th>Judul &amp; Tanggal</th>
                                        <th style="width:110px;" class="text-center">Status</th>
                                        <th style="width:140px;" class="text-end pe-4">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if(empty($galeri)): ?>
                                        <tr>
                                            <td colspan="4" class="text-center py-5 text-muted">
                                                <i class="bi bi-images fs-1 text-secondary mb-2 d-block opacity-50"></i>
                                                Belum ada foto galeri yang diunggah.
                                            </td>
                                        </tr>
                                    <?php else: ?>
                                        <?php foreach($galeri as $g): ?>
                                            <?php
                                            $galeri_url = base_url('assets/galeri/'.$g->gambar);
                                            ?>
                                            <tr>
                                                <td class="ps-4">
                                                    <a href="<?= $galeri_url ?>" target="_blank">
                                                        <img src="<?= $galeri_url ?>" 
                                                             alt="Galeri" 
                                                             class="rounded-3 shadow-sm"
                                                             style="width:80px; height:60px; object-fit:cover; border:1px solid #e2e8f0;">
                                                    </a>
                                                </td>
                                                <td>
                                                    <div class="fw-bold text-dark" style="font-size:14px;"><?= htmlspecialchars($g->judul ?? '-', ENT_QUOTES, 'UTF-8') ?></div>
                                                    <?php if(!empty($g->deskripsi)): ?>
                                                        <div class="small text-muted text-truncate" style="max-width: 260px;"><?= htmlspecialchars($g->deskripsi, ENT_QUOTES, 'UTF-8') ?></div>
                                                    <?php endif; ?>
                                                    <div class="small text-muted mt-1">
                                                        <i class="bi bi-calendar3 me-1"></i><?= !empty($g->tanggal) ? date('d M Y', strtotime($g->tanggal)) : (!empty($g->created_at) ? date('d M Y', strtotime($g->created_at)) : '-') ?>
                                                    </div>
                                                </td>
                                                <td class="text-center">
                                                    <?php if($g->status === 'Published'): ?>
                                                        <span class="badge bg-success-subtle text-success fw-bold rounded-pill px-3 py-1">
                                                            <i class="bi bi-check-circle-fill me-1"></i> Published
                                                        </span>
                                                    <?php else: ?>
                                                        <span class="badge bg-secondary-subtle text-secondary fw-bold rounded-pill px-3 py-1">
                                                            <i class="bi bi-clock me-1"></i> Draft
                                                        </span>
                                                    <?php endif; ?>
                                                </td>
                                                <td class="text-end pe-4">
                                                    <div class="d-inline-flex gap-1">
                                                        <?php if($g->status === 'Published'): ?>
                                                            <a href="<?= base_url('admin_website/draft_galeri/'.$g->id) ?>" 
                                                               class="btn btn-sm btn-light rounded-pill px-2 py-1 text-warning shadow-sm"
                                                               title="Jadikan Draft">
                                                                <i class="bi bi-eye-slash-fill"></i>
                                                            </a>
                                                        <?php else: ?>
                                                            <a href="<?= base_url('admin_website/publish_galeri/'.$g->id) ?>" 
                                                               class="btn btn-sm btn-light rounded-pill px-2 py-1 text-success shadow-sm"
                                                               title="Terbitkan ke Website (Publish)">
                                                                <i class="bi bi-eye-fill"></i>
                                                            </a>
                                                        <?php endif; ?>

                                                        <a href="<?= $galeri_url ?>" target="_blank" class="btn btn-sm btn-light rounded-pill px-2 py-1 text-primary shadow-sm" title="Lihat Asli">
                                                            <i class="bi bi-box-arrow-up-right"></i>
                                                        </a>
                                                        <a href="<?= base_url('admin_website/delete_galeri/'.$g->id) ?>" 
                                                           class="btn btn-sm btn-light rounded-pill px-2 py-1 text-danger shadow-sm"
                                                           onclick="return confirm('Hapus foto ini dari galeri?')"
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

<script>
document.addEventListener('DOMContentLoaded', function(){
    const inputG = document.getElementById('inputGaleri');
    const previewG = document.getElementById('previewGaleri');

    if(inputG && previewG){
        inputG.addEventListener('change', function(){
            const file = this.files[0];
            if(!file){
                previewG.innerHTML = 'Pratinjau Foto';
                return;
            }
            const reader = new FileReader();
            reader.onload = function(e){
                previewG.innerHTML = '<img src="'+e.target.result+'" style="max-width:100%; max-height:160px; border-radius:10px; object-fit:cover;">';
            };
            reader.readAsDataURL(file);
        });
    }
});
</script>

<?php $this->load->view('templates/footer'); ?>