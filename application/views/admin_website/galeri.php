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
                            Kelola album foto dokumentasi kegiatan belajar, perlombaan, upacara, ekstrakurikuler, dan momen prestasi MAN 3 Banjar yang ditampilkan pada galeri website.
                        </p>
                        <div class="d-flex flex-wrap gap-2 pt-1">
                            <a href="<?= base_url('galeri') ?>" target="_blank" class="btn btn-light text-dark fw-bold rounded-pill px-3 shadow-sm">
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
                        <form method="post" action="<?= base_url('admin_website/add_galeri') ?>" enctype="multipart/form-data">
                            <div class="mb-3">
                                <label class="form-label fw-bold small text-muted">Judul / Keterangan Foto</label>
                                <input type="text" name="judul" class="form-control rounded-3" placeholder="Contoh: Upacara Peringatan Hari Santri" required>
                            </div>

                            <div class="mb-4">
                                <label class="form-label fw-bold small text-muted">Pilih File Foto</label>
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
                                        <th>Judul &amp; Keterangan</th>
                                        <th style="width:130px;">Tanggal</th>
                                        <th style="width:130px;" class="text-end pe-4">Aksi</th>
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
                                            <tr>
                                                <td class="ps-4">
                                                    <img src="<?= base_url('uploads/galeri/'.$g->gambar) ?>" 
                                                         alt="Galeri" 
                                                         class="rounded-3 shadow-sm"
                                                         style="width:80px; height:60px; object-fit:cover; border:1px solid #e2e8f0;">
                                                </td>
                                                <td>
                                                    <div class="fw-bold text-dark" style="font-size:14px;"><?= htmlspecialchars($g->judul ?? '-', ENT_QUOTES, 'UTF-8') ?></div>
                                                    <div class="small text-muted mt-1"><?= htmlspecialchars($g->gambar ?? '', ENT_QUOTES, 'UTF-8') ?></div>
                                                </td>
                                                <td>
                                                    <span class="small text-muted"><?= !empty($g->created_at) ? date('d M Y', strtotime($g->created_at)) : '-' ?></span>
                                                </td>
                                                <td class="text-end pe-4">
                                                    <div class="d-inline-flex gap-1">
                                                        <a href="<?= base_url('uploads/galeri/'.$g->gambar) ?>" target="_blank" class="btn btn-sm btn-light rounded-pill px-2 py-1 text-primary shadow-sm" title="Lihat Foto">
                                                            <i class="bi bi-eye-fill"></i>
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