<?php $this->load->view('templates/header'); ?>
<?php $this->load->view('templates/sidebar'); ?>

<?php
$judul = $berita->judul ?? '';
$isi = $berita->isi ?? '';
$status = !empty($berita->status_berita) ? $berita->status_berita : 'Draft';
$kategori = !empty($berita->kategori) ? $berita->kategori : 'Kegiatan';
$kategori_options = ['Prestasi','Kegiatan','Pengumuman','PPDB','Akademik','Keagamaan','Ekstrakurikuler'];

$gambar = $berita->gambar ?? '';
$gambar_file = !empty($gambar) ? FCPATH.'assets/news/'.$gambar : '';

$poster = $berita->poster_gambar ?? '';
$poster_file = !empty($poster) ? FCPATH.'assets/news/poster/'.$poster : '';
?>

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
                            <i class="bi bi-pencil-fill me-1"></i> EDITOR ARTIKEL
                        </span>
                        <h2 class="fw-bold mb-2 text-white">Edit Konten Berita</h2>
                        <p class="mb-3 text-white-50" style="font-size: 14px; max-width: 620px;">
                            Perbarui naskah artikel, kategori warta, foto sampul, foto kegiatan tambahan, serta konfigurasi pamflet media sosial.
                        </p>
                        <div class="d-flex flex-wrap gap-2 pt-1">
                            <a href="<?= base_url('berita') ?>" class="btn btn-light text-dark fw-bold rounded-pill px-3 shadow-sm">
                                <i class="bi bi-arrow-left me-1"></i> Kembali ke Daftar
                            </a>
                            <a href="<?= base_url('berita/detail/'.$berita->id) ?>" target="_blank" class="btn btn-outline-light fw-bold rounded-pill px-3 shadow-sm">
                                <i class="bi bi-box-arrow-up-right me-1"></i> Pratinjau Berita
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Hiasan Bulat Transparan -->
            <div style="position: absolute; right: -40px; top: -40px; width: 180px; height: 180px; background: radial-gradient(circle, rgba(255,255,255,0.2) 0%, transparent 70%); border-radius: 50%;"></div>
        </div>

        <form method="post" action="<?= base_url('berita/update/'.$berita->id) ?>" enctype="multipart/form-data">
            <div class="row g-4">
                
                <!-- Kolom Kiri: Naskah & Galeri Kegiatan -->
                <div class="col-lg-8">
                    <div class="card border-0 rounded-4 shadow-sm mb-4">
                        <div class="card-header bg-white border-bottom pt-3 pb-2 px-4">
                            <h6 class="fw-bold mb-0 text-dark"><i class="bi bi-journal-text text-success me-2"></i> Konten &amp; Naskah Berita</h6>
                        </div>
                        <div class="card-body p-4">
                            <div class="mb-3">
                                <label class="form-label fw-bold small text-muted">Judul Berita</label>
                                <input type="text" name="judul" class="form-control rounded-3" value="<?= htmlspecialchars($judul, ENT_QUOTES, 'UTF-8') ?>" required>
                            </div>

                            <div class="row g-3 mb-3">
                                <div class="col-md-6">
                                    <label class="form-label fw-bold small text-muted">Kategori Berita</label>
                                    <select name="kategori" class="form-select rounded-3" required>
                                        <?php foreach($kategori_options as $opt): ?>
                                            <option value="<?= $opt ?>" <?= $kategori == $opt ? 'selected' : '' ?>><?= $opt ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-bold small text-muted">Status Publikasi</label>
                                    <select name="status_berita" class="form-select rounded-3">
                                        <option value="Draft" <?= $status == 'Draft' ? 'selected' : '' ?>>Draft</option>
                                        <option value="Published" <?= $status == 'Published' ? 'selected' : '' ?>>Published</option>
                                    </select>
                                </div>
                            </div>

                            <div class="mb-0">
                                <label class="form-label fw-bold small text-muted">Isi Naskah Berita</label>
                                <textarea name="isi" class="form-control rounded-3" rows="12" style="line-height:1.7;" required><?= htmlspecialchars($isi, ENT_QUOTES, 'UTF-8') ?></textarea>
                            </div>
                        </div>
                    </div>

                    <!-- Foto Kegiatan Tambahan -->
                    <div class="card border-0 rounded-4 shadow-sm mb-4">
                        <div class="card-header bg-white border-bottom pt-3 pb-2 px-4">
                            <h6 class="fw-bold mb-0 text-dark"><i class="bi bi-images text-success me-2"></i> Foto Kegiatan Tambahan (Multi-Foto)</h6>
                        </div>
                        <div class="card-body p-4">
                            <div class="p-3 bg-light rounded-4 border mb-4 text-center">
                                <input type="file" name="gambar_multi[]" class="form-control rounded-3 mb-2" id="gambarMultiEditBerita" accept="image/*" multiple>
                                <small class="text-muted d-block">Pilih beberapa gambar sekaligus untuk menambahkan foto kegiatan ke galeri berita &amp; pamflet.</small>
                                <div class="d-flex flex-wrap gap-2 justify-content-center mt-3" id="previewMultiEditBerita"></div>
                            </div>

                            <label class="form-label fw-bold text-muted small text-uppercase">Foto Kegiatan Tersimpan:</label>
                            <?php if(!empty($gambar_berita)): ?>
                                <div class="row g-3">
                                    <?php foreach($gambar_berita as $g): ?>
                                        <?php $file = FCPATH.'assets/news/'.$g->gambar; ?>
                                        <?php if(!empty($g->gambar) && file_exists($file)): ?>
                                            <div class="col-6 col-md-4">
                                                <div class="border rounded-3 overflow-hidden shadow-sm">
                                                    <img src="<?= base_url('assets/news/'.$g->gambar) ?>" 
                                                         alt="Foto kegiatan" 
                                                         style="width:100%; height:120px; object-fit:cover; display:block;">
                                                    <div class="p-2 bg-white d-flex justify-content-between align-items-center">
                                                        <a href="<?= base_url('assets/news/'.$g->gambar) ?>" target="_blank" class="btn btn-sm btn-light rounded-pill px-2 py-0 text-primary small">
                                                            <i class="bi bi-eye-fill"></i>
                                                        </a>
                                                        <a href="<?= base_url('berita/delete_gambar/'.$g->id) ?>" class="btn btn-sm btn-light rounded-pill px-2 py-0 text-danger small" onclick="return confirm('Hapus foto ini?')">
                                                            <i class="bi bi-trash-fill"></i>
                                                        </a>
                                                    </div>
                                                </div>
                                            </div>
                                        <?php endif; ?>
                                    <?php endforeach; ?>
                                </div>
                            <?php else: ?>
                                <div class="text-center py-4 text-muted small bg-light rounded-3 border">
                                    <i class="bi bi-images fs-2 d-block text-secondary mb-1"></i>
                                    Belum ada foto kegiatan tambahan yang diunggah.
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <!-- Kolom Kanan: Sampul, Pamflet & Action -->
                <div class="col-lg-4">
                    <!-- Foto Sampul -->
                    <div class="card border-0 rounded-4 shadow-sm mb-4">
                        <div class="card-header bg-white border-bottom pt-3 pb-2 px-4">
                            <h6 class="fw-bold mb-0 text-dark"><i class="bi bi-image-fill text-success me-2"></i> Sampul Utama</h6>
                        </div>
                        <div class="card-body p-4">
                            <div class="text-center mb-3">
                                <div id="previewEditBerita" class="rounded-3 border overflow-hidden bg-light d-flex align-items-center justify-content-center" style="min-height:160px;">
                                    <?php if(!empty($gambar) && file_exists($gambar_file)): ?>
                                        <img src="<?= base_url('assets/news/'.$gambar) ?>" alt="Sampul" style="width:100%; max-height:220px; object-fit:cover;">
                                    <?php else: ?>
                                        <span class="text-muted small">Belum ada foto sampul</span>
                                    <?php endif; ?>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-bold small text-muted">Ganti Foto Sampul</label>
                                <input type="file" name="gambar" class="form-control rounded-3" id="gambarEditBerita" accept="image/*">
                            </div>

                            <?php if(!empty($gambar) && file_exists($gambar_file)): ?>
                                <div class="form-check p-2 bg-light rounded-3 border">
                                    <input class="form-check-input ms-0 me-2" type="checkbox" name="hapus_gambar_utama" value="1" id="chkHapusSampul">
                                    <label class="form-check-label small text-danger fw-bold" for="chkHapusSampul">
                                        Hapus sampul saat menyimpan
                                    </label>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>

                    <!-- Poster Pamflet -->
                    <div class="card border-0 rounded-4 shadow-sm mb-4">
                        <div class="card-header bg-white border-bottom pt-3 pb-2 px-4">
                            <h6 class="fw-bold mb-0 text-dark"><i class="bi bi-magic text-success me-2"></i> Poster Pamflet Otomatis</h6>
                        </div>
                        <div class="card-body p-4">
                            <?php if(!empty($poster) && file_exists($poster_file)): ?>
                                <div class="mb-3 border rounded-3 overflow-hidden shadow-sm">
                                    <img src="<?= base_url('assets/news/poster/'.$poster) ?>" alt="Poster Berita" style="width:100%; display:block;">
                                </div>
                            <?php else: ?>
                                <div class="text-center py-3 text-muted small bg-light rounded-3 mb-3 border">
                                    Poster belum digenerate.
                                </div>
                            <?php endif; ?>

                            <div class="mb-2">
                                <label class="form-label fw-bold small text-muted">Mode Gambar</label>
                                <select name="poster_fit_mode" class="form-select form-select-sm rounded-3">
                                    <option value="cover" <?= ($berita->poster_fit_mode ?? 'cover') == 'cover' ? 'selected' : '' ?>>Penuh / Crop Rapi</option>
                                    <option value="contain" <?= ($berita->poster_fit_mode ?? '') == 'contain' ? 'selected' : '' ?>>Gambar Utuh</option>
                                </select>
                            </div>

                            <div class="mb-2">
                                <label class="form-label fw-bold small text-muted">Fokus Posisi</label>
                                <select name="poster_focus" class="form-select form-select-sm rounded-3">
                                    <option value="center" <?= ($berita->poster_focus ?? 'center') == 'center' ? 'selected' : '' ?>>Tengah (Center)</option>
                                    <option value="top" <?= ($berita->poster_focus ?? '') == 'top' ? 'selected' : '' ?>>Atas (Top)</option>
                                    <option value="bottom" <?= ($berita->poster_focus ?? '') == 'bottom' ? 'selected' : '' ?>>Bawah (Bottom)</option>
                                </select>
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-bold small text-muted">Tata Letak (Layout)</label>
                                <select name="poster_layout" class="form-select form-select-sm rounded-3">
                                    <option value="auto" <?= ($berita->poster_layout ?? 'auto') == 'auto' ? 'selected' : '' ?>>Otomatis</option>
                                    <option value="single" <?= ($berita->poster_layout ?? '') == 'single' ? 'selected' : '' ?>>1 Foto Besar</option>
                                    <option value="two" <?= ($berita->poster_layout ?? '') == 'two' ? 'selected' : '' ?>>2 Foto</option>
                                    <option value="three" <?= ($berita->poster_layout ?? '') == 'three' ? 'selected' : '' ?>>3 Foto</option>
                                    <option value="grid" <?= ($berita->poster_layout ?? '') == 'grid' ? 'selected' : '' ?>>Grid 5 Foto</option>
                                </select>
                            </div>

                            <div class="d-grid gap-2">
                                <a href="<?= base_url('berita/regenerate_pamflet/'.$berita->id) ?>" 
                                   class="btn btn-warning btn-sm rounded-pill fw-bold shadow-sm"
                                   onclick="return confirm('Generate ulang pamflet sekarang? Simpan perubahan naskah lebih dahulu jika ada yang baru diedit.')">
                                    <i class="bi bi-magic me-1"></i> Generate Ulang Poster
                                </a>
                                <?php if(!empty($poster) && file_exists($poster_file)): ?>
                                    <a href="<?= base_url('berita/download_pamflet/'.$berita->id) ?>" class="btn btn-success btn-sm rounded-pill fw-bold shadow-sm">
                                        <i class="bi bi-download me-1"></i> Download JPG
                                    </a>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>

                    <!-- Tombol Simpan -->
                    <div class="card border-0 rounded-4 shadow-sm p-3 mb-4">
                        <button type="submit" class="btn btn-success fw-bold rounded-pill w-100 py-3 shadow-sm fs-6">
                            <i class="bi bi-check-circle-fill me-2"></i> Simpan Perubahan Berita
                        </button>
                    </div>
                </div>

            </div>
        </form>

    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function(){
    const inputMain = document.getElementById('gambarEditBerita');
    const previewMain = document.getElementById('previewEditBerita');

    if(inputMain && previewMain){
        inputMain.addEventListener('change', function(){
            const file = this.files[0];
            if(!file){ return; }
            const reader = new FileReader();
            reader.onload = function(e){ 
                previewMain.innerHTML = '<img src="'+e.target.result+'" alt="Preview Sampul" style="width:100%; max-height:220px; object-fit:cover;">'; 
            };
            reader.readAsDataURL(file);
        });
    }

    const inputMulti = document.getElementById('gambarMultiEditBerita');
    const previewMulti = document.getElementById('previewMultiEditBerita');

    if(inputMulti && previewMulti){
        inputMulti.addEventListener('change', function(){
            previewMulti.innerHTML = '';
            Array.from(this.files).forEach(function(file){
                const reader = new FileReader();
                reader.onload = function(e){
                    const img = document.createElement('img');
                    img.src = e.target.result;
                    img.style.width = '70px';
                    img.style.height = '70px';
                    img.style.objectFit = 'cover';
                    img.style.borderRadius = '8px';
                    img.style.border = '1px solid #cbd5e1';
                    previewMulti.appendChild(img);
                };
                reader.readAsDataURL(file);
            });
        });
    }
});
</script>

<?php $this->load->view('templates/footer'); ?>