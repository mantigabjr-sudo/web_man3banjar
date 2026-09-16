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

    <div class="page-header">
        <div class="page-title-group">
            <span class="page-category">Kelola Website / Berita</span>
            <h1 class="page-title">Edit Konten Berita</h1>
            <p class="page-subtitle">Perbarui teks artikel, kategori, foto sampul, foto kegiatan, dan poster pamflet media sosial.</p>
        </div>
        <div class="header-actions d-flex gap-2">
            <a href="<?= base_url('berita') ?>" class="btn-modern btn-modern-light text-decoration-none">
                <i class="fa fa-arrow-left me-1"></i> Kembali ke Daftar
            </a>
            <a href="<?= base_url('berita/detail/'.$berita->id) ?>" target="_blank" class="btn-modern btn-modern-light text-decoration-none">
                <i class="fa fa-external-link-alt me-1"></i> Pratinjau
            </a>
        </div>
    </div>

    <?php if($this->session->flashdata('success')): ?>
        <div class="alert alert-success d-flex align-items-center mb-4" role="alert">
            <i class="fa fa-check-circle me-2 fs-5"></i>
            <div><?= $this->session->flashdata('success') ?></div>
        </div>
    <?php endif; ?>

    <?php if($this->session->flashdata('error')): ?>
        <div class="alert alert-danger d-flex align-items-center mb-4" role="alert">
            <i class="fa fa-exclamation-circle me-2 fs-5"></i>
            <div><?= $this->session->flashdata('error') ?></div>
        </div>
    <?php endif; ?>

    <form method="post" action="<?= base_url('berita/update/'.$berita->id) ?>" enctype="multipart/form-data">
        <div class="row g-4">
            
            <!-- Kolom Kiri: Konten Utama & Galeri Kegiatan -->
            <div class="col-lg-8">
                <div class="modern-card mb-4">
                    <div class="modern-card-header">
                        <h2 class="modern-card-title">Konten & Naskah Berita</h2>
                        <p class="modern-card-subtitle">Ubah naskah, kategori serta status penerbitan berita ini.</p>
                    </div>
                    <div class="modern-card-body">
                        <div class="mb-3">
                            <label class="form-label fw-bold">Judul Berita</label>
                            <input type="text" 
                                   name="judul" 
                                   class="input-modern" 
                                   value="<?= htmlspecialchars($judul, ENT_QUOTES, 'UTF-8') ?>" 
                                   required>
                        </div>

                        <div class="row g-3 mb-3">
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Kategori Berita</label>
                                <select name="kategori" class="input-modern" required>
                                    <?php foreach($kategori_options as $opt): ?>
                                        <option value="<?= $opt ?>" <?= $kategori == $opt ? 'selected' : '' ?>><?= $opt ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-bold">Status Publikasi</label>
                                <select name="status_berita" class="input-modern">
                                    <option value="Draft" <?= $status == 'Draft' ? 'selected' : '' ?>>Draft (Belum Tampil)</option>
                                    <option value="Published" <?= $status == 'Published' ? 'selected' : '' ?>>Published (Tayang)</option>
                                </select>
                            </div>
                        </div>

                        <div class="mb-0">
                            <label class="form-label fw-bold">Isi Naskah Berita</label>
                            <textarea name="isi" 
                                      class="input-modern" 
                                      rows="12" 
                                      style="line-height:1.7;" 
                                      required><?= htmlspecialchars($isi, ENT_QUOTES, 'UTF-8') ?></textarea>
                        </div>
                    </div>
                </div>

                <!-- Foto Tambahan -->
                <div class="modern-card">
                    <div class="modern-card-header">
                        <h2 class="modern-card-title">Foto Kegiatan Tambahan</h2>
                        <p class="modern-card-subtitle">Unggah beberapa foto dokumentasi kegiatan untuk galeri dan bahan poster otomatis.</p>
                    </div>
                    <div class="modern-card-body">
                        <div class="mb-4 p-3 bg-light rounded-4 border border-dashed text-center">
                            <input type="file" name="gambar_multi[]" id="gambarMultiEditBerita" class="form-control" accept="image/*" multiple>
                            <div class="small text-muted mt-2">Pilih beberapa foto sekaligus. Gambar lama tetap tersimpan.</div>
                            <div class="d-flex flex-wrap gap-2 justify-content-center mt-3" id="previewMultiEditBerita"></div>
                        </div>

                        <label class="form-label fw-bold text-muted small text-uppercase">Foto Kegiatan Tersimpan:</label>
                        <?php if(!empty($gambar_berita)): ?>
                            <div class="row g-3">
                                <?php foreach($gambar_berita as $g): ?>
                                    <?php $file = FCPATH.'assets/news/'.$g->gambar; ?>
                                    <?php if(!empty($g->gambar) && file_exists($file)): ?>
                                        <div class="col-6 col-md-4">
                                            <div class="border rounded-3 overflow-hidden shadow-sm position-relative">
                                                <img src="<?= base_url('assets/news/'.$g->gambar) ?>" 
                                                     alt="Foto kegiatan" 
                                                     style="width:100%; height:130px; object-fit:cover; display:block;">
                                                <div class="p-2 bg-white d-flex justify-content-between align-items-center">
                                                    <a href="<?= base_url('assets/news/'.$g->gambar) ?>" target="_blank" class="btn btn-sm btn-light py-0 px-2 text-primary" style="font-size:11px;">
                                                        <i class="fa fa-eye"></i>
                                                    </a>
                                                    <a href="<?= base_url('berita/delete_gambar/'.$g->id) ?>" class="btn btn-sm btn-light py-0 px-2 text-danger" onclick="return confirm('Hapus foto kegiatan ini?')" style="font-size:11px;">
                                                        <i class="fa fa-trash"></i>
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    <?php endif; ?>
                                <?php endforeach; ?>
                            </div>
                        <?php else: ?>
                            <div class="text-center py-4 text-muted small border rounded-3 bg-light">
                                <i class="fa fa-images d-block fs-3 mb-1 text-secondary"></i>
                                Belum ada foto kegiatan tambahan yang diunggah.
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <!-- Kolom Kanan: Foto Utama, Poster Pamflet, & Info -->
            <div class="col-lg-4">
                
                <!-- Foto Sampul Utama -->
                <div class="modern-card mb-4">
                    <div class="modern-card-header">
                        <h2 class="modern-card-title">Sampul Utama</h2>
                        <p class="modern-card-subtitle">Gambar thumbnail yang tampil di halaman depan.</p>
                    </div>
                    <div class="modern-card-body">
                        <div class="text-center mb-3">
                            <div id="previewEditBerita" style="border-radius:14px; overflow:hidden; border:1px solid #e2e8f0; background:#f8fafc; min-height:160px; display:flex; align-items:center; justify-content:center;">
                                <?php if(!empty($gambar) && file_exists($gambar_file)): ?>
                                    <img src="<?= base_url('assets/news/'.$gambar) ?>" alt="Sampul Utama" style="width:100%; max-height:220px; object-fit:cover;">
                                <?php else: ?>
                                    <span class="text-muted small">Belum ada foto sampul</span>
                                <?php endif; ?>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold small text-muted">Ganti Foto Sampul</label>
                            <input type="file" name="gambar" class="input-modern py-2" id="gambarEditBerita" accept="image/*">
                        </div>

                        <?php if(!empty($gambar) && file_exists($gambar_file)): ?>
                            <div class="form-check p-2 bg-light rounded-3 border">
                                <input class="form-check-input ms-0 me-2" type="checkbox" name="hapus_gambar_utama" value="1" id="chkHapusSampul">
                                <label class="form-check-label small text-danger fw-bold" for="chkHapusSampul">
                                    Hapus foto sampul saat menyimpan
                                </label>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Pengaturan Poster Otomatis -->
                <div class="modern-card mb-4">
                    <div class="modern-card-header">
                        <h2 class="modern-card-title">Poster Pamflet</h2>
                        <p class="modern-card-subtitle">Generate pamflet otomatis untuk Instagram/WA.</p>
                    </div>
                    <div class="modern-card-body">
                        <?php if(!empty($poster) && file_exists($poster_file)): ?>
                            <div class="mb-3 border rounded-3 overflow-hidden shadow-sm">
                                <img src="<?= base_url('assets/news/poster/'.$poster) ?>" alt="Poster Berita" style="width:100%; display:block;">
                            </div>
                        <?php else: ?>
                            <div class="text-center py-3 text-muted small bg-light rounded-3 mb-3 border">
                                <i class="fa fa-id-badge d-block fs-3 mb-1 text-secondary"></i>
                                Poster belum digenerate.
                            </div>
                        <?php endif; ?>

                        <div class="mb-2">
                            <label class="form-label fw-bold small text-muted">Mode Gambar</label>
                            <select name="poster_fit_mode" class="input-modern py-1 px-2" style="font-size:13px;">
                                <option value="cover" <?= ($berita->poster_fit_mode ?? 'cover') == 'cover' ? 'selected' : '' ?>>Penuh / Crop Rapi</option>
                                <option value="contain" <?= ($berita->poster_fit_mode ?? '') == 'contain' ? 'selected' : '' ?>>Gambar Utuh / Tidak Terpotong</option>
                            </select>
                        </div>

                        <div class="mb-2">
                            <label class="form-label fw-bold small text-muted">Fokus Posisi</label>
                            <select name="poster_focus" class="input-modern py-1 px-2" style="font-size:13px;">
                                <option value="center" <?= ($berita->poster_focus ?? 'center') == 'center' ? 'selected' : '' ?>>Tengah (Center)</option>
                                <option value="top" <?= ($berita->poster_focus ?? '') == 'top' ? 'selected' : '' ?>>Atas (Top)</option>
                                <option value="bottom" <?= ($berita->poster_focus ?? '') == 'bottom' ? 'selected' : '' ?>>Bawah (Bottom)</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold small text-muted">Tata Letak (Layout)</label>
                            <select name="poster_layout" class="input-modern py-1 px-2" style="font-size:13px;">
                                <option value="auto" <?= ($berita->poster_layout ?? 'auto') == 'auto' ? 'selected' : '' ?>>Otomatis</option>
                                <option value="single" <?= ($berita->poster_layout ?? '') == 'single' ? 'selected' : '' ?>>1 Foto Besar</option>
                                <option value="two" <?= ($berita->poster_layout ?? '') == 'two' ? 'selected' : '' ?>>2 Foto</option>
                                <option value="three" <?= ($berita->poster_layout ?? '') == 'three' ? 'selected' : '' ?>>3 Foto</option>
                                <option value="grid" <?= ($berita->poster_layout ?? '') == 'grid' ? 'selected' : '' ?>>Grid 5 Foto</option>
                            </select>
                        </div>

                        <div class="d-grid gap-2">
                            <a href="<?= base_url('berita/regenerate_pamflet/'.$berita->id) ?>" 
                               class="btn-modern btn-modern-light text-center py-2 text-decoration-none"
                               onclick="return confirm('Generate ulang pamflet sekarang? Pastikan perubahan teks sudah disimpan lebih dahulu.')">
                                <i class="fa fa-magic me-1 text-warning"></i> Generate Ulang Poster
                            </a>
                            <?php if(!empty($poster) && file_exists($poster_file)): ?>
                                <a href="<?= base_url('berita/download_pamflet/'.$berita->id) ?>" 
                                   class="btn-modern btn-modern-light text-center py-2 text-decoration-none text-success">
                                    <i class="fa fa-download me-1"></i> Download Gambar JPG
                                </a>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <!-- Info Metadata -->
                <div class="modern-card mb-4">
                    <div class="modern-card-body p-3">
                        <div class="d-flex justify-content-between py-2 border-bottom">
                            <span class="small text-muted">ID Artikel</span>
                            <span class="fw-bold small">#<?= $berita->id ?></span>
                        </div>
                        <div class="d-flex justify-content-between py-2 border-bottom">
                            <span class="small text-muted">Kategori</span>
                            <span class="badge bg-light text-primary border"><?= htmlspecialchars($kategori, ENT_QUOTES, 'UTF-8') ?></span>
                        </div>
                        <div class="d-flex justify-content-between py-2 border-bottom">
                            <span class="small text-muted">Status</span>
                            <span class="pill-status <?= $status == 'Published' ? 'pill-success' : 'pill-warning' ?>" style="font-size:11px; padding:3px 8px;">
                                <?= $status ?>
                            </span>
                        </div>
                        <div class="d-flex justify-content-between py-2 border-bottom">
                            <span class="small text-muted">Dibuat Pada</span>
                            <span class="small fw-bold"><?= !empty($berita->created_at) ? date('d M Y H:i', strtotime($berita->created_at)) : '-' ?></span>
                        </div>
                        <div class="d-flex justify-content-between py-2">
                            <span class="small text-muted">Dipublikasi</span>
                            <span class="small fw-bold"><?= !empty($berita->published_at) ? date('d M Y H:i', strtotime($berita->published_at)) : '-' ?></span>
                        </div>
                    </div>
                </div>

                <!-- Tombol Simpan Utama -->
                <div class="modern-card p-3">
                    <button type="submit" class="btn-modern btn-modern-primary w-100 py-3 fs-6">
                        <i class="fa fa-save me-2"></i> Simpan Perubahan Berita
                    </button>
                </div>

            </div>

        </div>
    </form>

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