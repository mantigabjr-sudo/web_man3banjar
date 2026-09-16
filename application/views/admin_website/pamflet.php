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
                            <i class="bi bi-file-earmark-image-fill me-1"></i> PAMFLET &amp; POSTER
                        </span>
                        <h2 class="fw-bold mb-2 text-white">Pamflet &amp; Poster Pengumuman</h2>
                        <p class="mb-3 text-white-50" style="font-size: 14px; max-width: 620px;">
                            Kelola arsip pamflet digital, poster kegiatan, brosur PMB, dan infografis pengumuman yang dapat dilihat dan diunduh oleh publik.
                        </p>
                        <div class="d-flex flex-wrap gap-2 pt-1">
                            <a href="<?= base_url() ?>" target="_blank" class="btn btn-light text-dark fw-bold rounded-pill px-3 shadow-sm">
                                <i class="bi bi-box-arrow-up-right text-success me-1"></i> Lihat di Website
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Hiasan Bulat Transparan -->
            <div style="position: absolute; right: -40px; top: -40px; width: 180px; height: 180px; background: radial-gradient(circle, rgba(255,255,255,0.2) 0%, transparent 70%); border-radius: 50%;"></div>
        </div>

        <div class="row g-4">
            <!-- Form Upload Pamflet -->
            <div class="col-lg-4">
                <div class="card border-0 rounded-4 shadow-sm mb-4">
                    <div class="card-header bg-white border-bottom pt-3 pb-2 px-4">
                        <h6 class="fw-bold mb-0 text-dark"><i class="bi bi-cloud-arrow-up-fill text-success me-2"></i> Unggah Pamflet Baru</h6>
                    </div>
                    <div class="card-body p-4">
                        <form method="post" action="<?= base_url('admin_website/add_pamflet') ?>" enctype="multipart/form-data">
                            <div class="mb-3">
                                <label class="form-label fw-bold small text-muted">Judul Pamflet / Pengumuman</label>
                                <input type="text" name="judul" class="form-control rounded-3" placeholder="Contoh: Brosur PPDB 2026/2027" required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-bold small text-muted">Pilih File Gambar</label>
                                <input type="file" name="gambar" class="form-control rounded-3 mb-2" id="inputPamflet" accept="image/*" required>
                                
                                <div class="p-2 border rounded-3 bg-light text-center" id="previewPamflet" style="min-height: 120px; display: flex; align-items: center; justify-content: center; color: #94a3b8; font-size: 12px;">
                                    Pratinjau Pamflet
                                </div>
                            </div>

                            <button type="submit" class="btn btn-success fw-bold rounded-pill w-100 py-2 shadow-sm">
                                <i class="bi bi-cloud-arrow-up-fill me-1"></i> Unggah Pamflet
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Tabel Pamflet -->
            <div class="col-lg-8">
                <div class="card border-0 rounded-4 shadow-sm mb-4">
                    <div class="card-header bg-white border-bottom pt-3 pb-2 px-4 d-flex justify-content-between align-items-center">
                        <h6 class="fw-bold mb-0 text-dark"><i class="bi bi-file-earmark-image text-success me-2"></i> Arsip Pamflet Tersimpan</h6>
                        <span class="badge bg-success-subtle text-success rounded-pill fw-bold px-3 py-1"><?= count($pamflet ?? []) ?> Pamflet</span>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0" style="width:100%">
                                <thead class="bg-light">
                                    <tr>
                                        <th style="width:100px;" class="ps-4">Preview</th>
                                        <th>Judul Pamflet</th>
                                        <th style="width:140px;">Tanggal Unggah</th>
                                        <th style="width:130px;" class="text-end pe-4">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if(empty($pamflet)): ?>
                                        <tr>
                                            <td colspan="4" class="text-center py-5 text-muted">
                                                <i class="bi bi-file-earmark-image fs-1 text-secondary mb-2 d-block opacity-50"></i>
                                                Belum ada pamflet yang diunggah.
                                            </td>
                                        </tr>
                                    <?php else: ?>
                                        <?php foreach($pamflet as $p): ?>
                                            <tr>
                                                <td class="ps-4">
                                                    <img src="<?= base_url('uploads/pamflet/'.$p->gambar) ?>" 
                                                         alt="Pamflet" 
                                                         class="rounded-3 shadow-sm"
                                                         style="width:80px; height:90px; object-fit:cover; border:1px solid #e2e8f0;">
                                                </td>
                                                <td>
                                                    <div class="fw-bold text-dark" style="font-size:14px;"><?= htmlspecialchars($p->judul ?? '-', ENT_QUOTES, 'UTF-8') ?></div>
                                                    <div class="small text-muted mt-1"><?= htmlspecialchars($p->gambar ?? '', ENT_QUOTES, 'UTF-8') ?></div>
                                                </td>
                                                <td>
                                                    <span class="small text-muted"><?= !empty($p->created_at) ? date('d M Y', strtotime($p->created_at)) : '-' ?></span>
                                                </td>
                                                <td class="text-end pe-4">
                                                    <div class="d-inline-flex gap-1">
                                                        <a href="<?= base_url('uploads/pamflet/'.$p->gambar) ?>" target="_blank" class="btn btn-sm btn-light rounded-pill px-2 py-1 text-primary shadow-sm" title="Lihat Asli">
                                                            <i class="bi bi-eye-fill"></i>
                                                        </a>
                                                        <a href="<?= base_url('admin_website/delete_pamflet/'.$p->id) ?>" 
                                                           class="btn btn-sm btn-light rounded-pill px-2 py-1 text-danger shadow-sm"
                                                           onclick="return confirm('Hapus pamflet ini?')"
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
    const inputP = document.getElementById('inputPamflet');
    const previewP = document.getElementById('previewPamflet');

    if(inputP && previewP){
        inputP.addEventListener('change', function(){
            const file = this.files[0];
            if(!file){
                previewP.innerHTML = 'Pratinjau Pamflet';
                return;
            }
            const reader = new FileReader();
            reader.onload = function(e){
                previewP.innerHTML = '<img src="'+e.target.result+'" style="max-width:100%; max-height:160px; border-radius:10px; object-fit:cover;">';
            };
            reader.readAsDataURL(file);
        });
    }
});
</script>