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
                            <i class="bi bi-youtube me-1"></i> MEDIA VIDEO YOUTUBE
                        </span>
                        <h2 class="fw-bold mb-2 text-white">Video YouTube Website</h2>
                        <p class="mb-3 text-white-50" style="font-size: 14px; max-width: 620px;">
                            Kelola video YouTube yang disematkan pada beranda madrasah. Cukup masukkan link URL atau ID video YouTube untuk menampilkan video beserta thumbnail otomatis.
                        </p>
                        <div class="d-flex flex-wrap gap-2 pt-1">
                            <a href="<?= base_url() ?>" target="_blank" class="btn btn-light text-dark fw-bold rounded-pill px-3 shadow-sm">
                                <i class="bi bi-box-arrow-up-right text-success me-1"></i> Pratinjau di Beranda
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Hiasan Bulat Transparan -->
            <div style="position: absolute; right: -40px; top: -40px; width: 180px; height: 180px; background: radial-gradient(circle, rgba(255,255,255,0.2) 0%, transparent 70%); border-radius: 50%;"></div>
        </div>

        <div class="row g-4">
            <!-- Form Tambah Video -->
            <div class="col-lg-4">
                <div class="card border-0 rounded-4 shadow-sm mb-4">
                    <div class="card-header bg-white border-bottom pt-3 pb-2 px-4">
                        <h6 class="fw-bold mb-0 text-dark"><i class="bi bi-plus-circle-fill text-success me-2"></i> Tambah Video Baru</h6>
                    </div>
                    <div class="card-body p-4">
                        <form method="post" action="<?= base_url('admin_website/add_video') ?>">
                            <div class="mb-3">
                                <label class="form-label fw-bold small text-muted">Judul Video</label>
                                <input type="text" name="judul" class="form-control rounded-3" placeholder="Contoh: Profil MAN 3 Banjar 2026" required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-bold small text-muted">Link / URL YouTube</label>
                                <input type="text" name="link" class="form-control rounded-3" id="inputYoutubeLink" placeholder="https://www.youtube.com/watch?v=..." required>
                                <div class="form-text small mt-1">Bisa URL panjang YouTube, link share <code>youtu.be/xxx</code>, atau YouTube Shorts.</div>
                            </div>

                            <div class="mb-4">
                                <label class="form-label fw-bold small text-muted">Pratinjau Thumbnail</label>
                                <div class="p-2 border rounded-3 bg-light text-center" id="ytPreview" style="min-height: 120px; display: flex; align-items: center; justify-content: center; color: #94a3b8; font-size: 12px;">
                                    Thumbnail Otomatis YouTube
                                </div>
                            </div>

                            <button type="submit" class="btn btn-success fw-bold rounded-pill w-100 py-2 shadow-sm">
                                <i class="bi bi-save me-1"></i> Simpan Video
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Tabel Daftar Video -->
            <div class="col-lg-8">
                <div class="card border-0 rounded-4 shadow-sm mb-4">
                    <div class="card-header bg-white border-bottom pt-3 pb-2 px-4 d-flex justify-content-between align-items-center">
                        <h6 class="fw-bold mb-0 text-dark"><i class="bi bi-play-circle-fill text-success me-2"></i> Video Tersimpan</h6>
                        <span class="badge bg-success-subtle text-success rounded-pill fw-bold px-3 py-1"><?= count($videos ?? []) ?> Video</span>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0" style="width:100%">
                                <thead class="bg-light">
                                    <tr>
                                        <th style="width:120px;" class="ps-4">Thumbnail</th>
                                        <th>Judul &amp; URL Video</th>
                                        <th style="width:100px;" class="text-end pe-4">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if(empty($videos)): ?>
                                        <tr>
                                            <td colspan="3" class="text-center py-5 text-muted">
                                                <i class="bi bi-youtube fs-1 text-secondary mb-2 d-block opacity-50"></i>
                                                Belum ada video YouTube yang ditambahkan.
                                            </td>
                                        </tr>
                                    <?php else: ?>
                                        <?php foreach($videos as $v): ?>
                                            <?php
                                            $yt_id = '';
                                            if(preg_match('%(?:youtube(?:-nocookie)?\.com/(?:[^/]+/.+/|(?:v|e(?:mbed)?)/|.*[?&]v=)|youtu\.be/)([^"&?/ ]{11})%i', $v->link, $match)){
                                                $yt_id = $match[1];
                                            }
                                            ?>
                                            <tr>
                                                <td class="ps-4">
                                                    <?php if(!empty($yt_id)): ?>
                                                        <img src="https://img.youtube.com/vi/<?= $yt_id ?>/mqdefault.jpg" 
                                                             alt="Thumbnail" 
                                                             class="rounded-3 shadow-sm"
                                                             style="width:100px; height:58px; object-fit:cover; border:1px solid #e2e8f0;">
                                                    <?php else: ?>
                                                        <div class="rounded-3 bg-light border d-flex align-items-center justify-content-center text-muted small" style="width:100px; height:58px;">
                                                            No Image
                                                        </div>
                                                    <?php endif; ?>
                                                </td>
                                                <td>
                                                    <div class="fw-bold text-dark" style="font-size:14px;"><?= htmlspecialchars($v->judul ?? '-', ENT_QUOTES, 'UTF-8') ?></div>
                                                    <a href="<?= htmlspecialchars($v->link ?? '', ENT_QUOTES, 'UTF-8') ?>" target="_blank" class="small text-success text-decoration-none d-inline-flex align-items-center gap-1 mt-1">
                                                        <i class="bi bi-box-arrow-up-right"></i> Buka di YouTube
                                                    </a>
                                                </td>
                                                <td class="text-end pe-4">
                                                    <a href="<?= base_url('admin_website/delete_video/'.$v->id) ?>" 
                                                       class="btn btn-sm btn-light rounded-pill px-3 py-1 text-danger shadow-sm"
                                                       onclick="return confirm('Hapus video ini?')">
                                                        <i class="bi bi-trash-fill me-1"></i> Hapus
                                                    </a>
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
    const inputLink = document.getElementById('inputYoutubeLink');
    const preview = document.getElementById('ytPreview');

    if(inputLink && preview){
        inputLink.addEventListener('input', function(){
            const url = this.value;
            const regExp = /^.*(youtu.be\/|v\/|u\/\w\/|embed\/|watch\?v=|\&v=)([^#\&\?]*).*/;
            const match = url.match(regExp);

            if(match && match[2].length === 11){
                preview.innerHTML = '<img src="https://img.youtube.com/vi/' + match[2] + '/mqdefault.jpg" style="max-width:100%; border-radius:10px;">';
            } else {
                preview.innerHTML = 'Thumbnail Otomatis YouTube';
            }
        });
    }
});
</script>

<?php $this->load->view('templates/footer'); ?>