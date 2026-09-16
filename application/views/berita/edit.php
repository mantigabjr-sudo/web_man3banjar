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

<style>
.news-edit-page{max-width:1320px;margin:0 auto;}
.news-edit-hero{background:radial-gradient(circle at top right, rgba(34,197,94,.16), transparent 34%),linear-gradient(135deg,#ecfdf5,#ffffff);border:1px solid #dcfce7;border-radius:26px;padding:22px;box-shadow:0 14px 35px rgba(15,23,42,.06);margin-bottom:20px;}
.news-edit-hero p{color:#64748b;font-weight:700;margin:5px 0 0;}
.news-edit-layout{display:grid;grid-template-columns:minmax(0,1fr) 420px;gap:20px;align-items:start;}
.news-edit-card{background:#ffffff;border:1px solid #e2e8f0;border-radius:24px;box-shadow:0 14px 35px rgba(15,23,42,.06);overflow:hidden;margin-bottom:18px;}
.news-edit-head{padding:18px 20px;border-bottom:1px solid #e2e8f0;background:radial-gradient(circle at top right, rgba(34,197,94,.08), transparent 30%),#ffffff;}
.news-edit-head h5{margin:0;color:#14532d;font-weight:950;}.news-edit-head small{display:block;color:#64748b;font-weight:700;margin-top:4px;}
.news-edit-body{padding:22px;}.news-field{margin-bottom:16px;}.news-field label{display:block;color:#334155;font-size:13px;font-weight:850;margin-bottom:7px;}
.news-input,.news-textarea,.news-select{width:100%;border:1px solid #cbd5e1;background:#f8fafc;border-radius:16px;padding:11px 13px;color:#0f172a;font-weight:700;outline:none;}
.news-input,.news-select{min-height:46px;}.news-textarea{min-height:330px;resize:vertical;line-height:1.7;}.news-input:focus,.news-textarea:focus,.news-select:focus{background:#ffffff;border-color:#22c55e;box-shadow:0 0 0 4px rgba(34,197,94,.12);}
.news-main-preview{width:100%;min-height:250px;border-radius:22px;background:#f8fafc;border:1px solid #e2e8f0;display:flex;align-items:center;justify-content:center;color:#64748b;font-weight:900;overflow:hidden;margin-bottom:14px;}.news-main-preview img{width:100%;max-height:330px;object-fit:cover;display:block;}
.news-check{display:flex;gap:9px;align-items:flex-start;padding:12px;border-radius:16px;background:#fef2f2;border:1px solid #fecaca;color:#991b1b;font-weight:800;margin-top:12px;}.news-check input{margin-top:3px;}
.news-upload-box{border:1px dashed #86efac;background:#f0fdf4;border-radius:20px;padding:14px;}.news-multi-preview{display:grid;grid-template-columns:repeat(3,1fr);gap:10px;margin-top:12px;}.news-multi-preview img{width:100%;height:90px;object-fit:cover;border-radius:14px;border:1px solid #bbf7d0;background:#fff;}
.news-gallery-grid{display:grid;grid-template-columns:repeat(2,1fr);gap:12px;}.news-gallery-item{position:relative;overflow:hidden;border-radius:20px;background:#f8fafc;border:1px solid #e2e8f0;}.news-gallery-item img{width:100%;height:145px;object-fit:cover;display:block;}.news-gallery-actions{padding:10px;display:grid;gap:8px;}.news-gallery-actions a{min-height:34px;border-radius:12px;display:flex;align-items:center;justify-content:center;font-size:12px;font-weight:950;text-decoration:none;}.btn-gallery-view{background:#e0f2fe;color:#075985;}.btn-gallery-delete{background:#fee2e2;color:#991b1b;}.btn-gallery-view:hover{color:#075985;}.btn-gallery-delete:hover{color:#991b1b;}
.news-empty-box{border:1px dashed #cbd5e1;background:#f8fafc;color:#64748b;border-radius:20px;padding:22px;text-align:center;font-weight:850;}.news-status{display:inline-flex;padding:8px 12px;border-radius:999px;font-size:12px;font-weight:950;}.news-status-draft{background:#fef3c7;color:#92400e;}.news-status-published{background:#dcfce7;color:#166534;}.news-category-badge{display:inline-flex;align-items:center;min-height:32px;border-radius:999px;padding:0 12px;background:#e0f2fe;color:#075985;font-size:12px;font-weight:950;}
.news-info-list{display:grid;gap:12px;}.news-info-item{background:#f8fafc;border:1px solid #e2e8f0;border-radius:18px;padding:14px;}.news-info-item small{display:block;color:#64748b;font-size:12px;font-weight:850;margin-bottom:4px;}.news-info-item strong{color:#0f172a;font-weight:950;}
.news-action-bar{position:sticky;bottom:0;z-index:20;margin-top:20px;background:rgba(255,255,255,.92);backdrop-filter:blur(16px);border:1px solid #e2e8f0;border-radius:24px;padding:14px;box-shadow:0 -10px 35px rgba(15,23,42,.08);display:flex;justify-content:space-between;gap:12px;flex-wrap:wrap;}.news-action-left,.news-action-right{display:flex;gap:10px;flex-wrap:wrap;}
.btn-save-news,.btn-back-news,.btn-preview-news,.btn-poster-news,.btn-download-news{min-height:44px;border-radius:15px;padding:0 16px;display:inline-flex;align-items:center;justify-content:center;text-decoration:none;font-weight:950;border:0;}.btn-save-news{background:linear-gradient(135deg,#15803d,#22c55e);color:#ffffff;box-shadow:0 12px 26px rgba(22,163,74,.22);}.btn-back-news{background:#f1f5f9;color:#334155;}.btn-preview-news{background:#e0f2fe;color:#075985;}.btn-poster-news{background:#fef3c7;color:#92400e;}.btn-download-news{background:#dcfce7;color:#166534;}.btn-back-news:hover{color:#334155;}.btn-preview-news:hover{color:#075985;}.btn-poster-news:hover{color:#92400e;}.btn-download-news:hover{color:#166534;}.poster-preview{width:100%;border-radius:20px;overflow:hidden;border:1px solid #e2e8f0;background:#f8fafc;margin-bottom:14px;}.poster-preview img{width:100%;display:block;}
@media(max-width:1100px){.news-edit-layout{grid-template-columns:1fr;}.news-gallery-grid{grid-template-columns:repeat(3,1fr);}}
@media(max-width:768px){.news-edit-hero,.news-edit-card,.news-action-bar{border-radius:20px;}.news-edit-body{padding:18px;}.news-gallery-grid,.news-multi-preview{grid-template-columns:repeat(2,1fr);}.news-action-bar,.news-action-left,.news-action-right{display:grid;grid-template-columns:1fr;}.btn-save-news,.btn-back-news,.btn-preview-news,.btn-poster-news,.btn-download-news{width:100%;}}
</style>

<div class="news-edit-page">
    <div class="news-edit-hero">
        <h2 class="glow mb-1">Edit Berita</h2>
        <p>Kelola konten, kategori, gambar utama, foto kegiatan tambahan, dan poster pamflet berita.</p>
    </div>

    <?php if($this->session->flashdata('success')): ?>
        <div class="alert alert-success rounded-4"><?= $this->session->flashdata('success') ?></div>
    <?php endif; ?>

    <?php if($this->session->flashdata('error')): ?>
        <div class="alert alert-danger rounded-4"><?= $this->session->flashdata('error') ?></div>
    <?php endif; ?>

    <form method="post" action="<?= base_url('berita/update/'.$berita->id) ?>" enctype="multipart/form-data">
        <div class="news-edit-layout">
            <div>
                <div class="news-edit-card">
                    <div class="news-edit-head">
                        <h5>Konten Berita</h5>
                        <small>Ubah judul, kategori, isi, dan status publikasi berita.</small>
                    </div>
                    <div class="news-edit-body">
                        <div class="news-field">
                            <label>Judul Berita</label>
                            <input type="text" name="judul" class="news-input" value="<?= htmlspecialchars($judul, ENT_QUOTES, 'UTF-8') ?>" required>
                        </div>

                        <div class="news-field">
                            <label>Kategori Berita</label>
                            <select name="kategori" class="news-select" required>
                                <?php foreach($kategori_options as $opt): ?>
                                    <option value="<?= $opt ?>" <?= $kategori == $opt ? 'selected' : '' ?>><?= $opt ?></option>
                                <?php endforeach; ?>
                            </select>
                            <small class="text-muted fw-bold d-block mt-2">Kategori akan tampil sebagai label pada website dan memudahkan filter berita.</small>
                        </div>

                        <div class="news-field">
                            <label>Isi Berita</label>
                            <textarea name="isi" class="news-textarea" required><?= htmlspecialchars($isi, ENT_QUOTES, 'UTF-8') ?></textarea>
                        </div>

                        <div class="news-field">
                            <label>Status Berita</label>
                            <select name="status_berita" class="news-select">
                                <option value="Draft" <?= $status == 'Draft' ? 'selected' : '' ?>>Draft</option>
                                <option value="Published" <?= $status == 'Published' ? 'selected' : '' ?>>Published</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="news-edit-card">
                    <div class="news-edit-head">
                        <h5>Foto Kegiatan Tambahan</h5>
                        <small>Upload beberapa foto kegiatan untuk galeri berita dan poster pamflet.</small>
                    </div>
                    <div class="news-edit-body">
                        <div class="news-field">
                            <label>Tambah Foto Kegiatan Baru</label>
                            <div class="news-upload-box">
                                <input type="file" name="gambar_multi[]" id="gambarMultiEditBerita" class="form-control" accept="image/*" multiple>
                                <small class="text-muted fw-bold d-block mt-2">Bisa pilih banyak gambar sekaligus. Gambar lama tidak hilang kecuali dihapus manual.</small>
                                <div class="news-multi-preview" id="previewMultiEditBerita"></div>
                            </div>
                        </div>

                        <hr>
                        <h6 class="fw-bold text-success mb-3">Foto Kegiatan Saat Ini</h6>

                        <?php if(!empty($gambar_berita)): ?>
                            <div class="news-gallery-grid">
                                <?php foreach($gambar_berita as $g): ?>
                                    <?php $file = FCPATH.'assets/news/'.$g->gambar; ?>
                                    <?php if(!empty($g->gambar) && file_exists($file)): ?>
                                        <div class="news-gallery-item">
                                            <img src="<?= base_url('assets/news/'.$g->gambar) ?>" alt="Foto kegiatan">
                                            <div class="news-gallery-actions">
                                                <a href="<?= base_url('assets/news/'.$g->gambar) ?>" target="_blank" class="btn-gallery-view">Lihat</a>
                                                <a href="<?= base_url('berita/delete_gambar/'.$g->id) ?>" class="btn-gallery-delete" onclick="return confirm('Hapus foto kegiatan ini?')">Hapus</a>
                                            </div>
                                        </div>
                                    <?php endif; ?>
                                <?php endforeach; ?>
                            </div>
                        <?php else: ?>
                            <div class="news-empty-box">Belum ada foto kegiatan tambahan.</div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <div>
                <div class="news-edit-card">
                    <div class="news-edit-head">
                        <h5>Gambar Utama</h5>
                        <small>Gambar ini dipakai sebagai thumbnail berita.</small>
                    </div>
                    <div class="news-edit-body">
                        <div class="news-main-preview" id="previewEditBerita">
                            <?php if(!empty($gambar) && file_exists($gambar_file)): ?>
                                <img src="<?= base_url('assets/news/'.$gambar) ?>" alt="Gambar Utama">
                            <?php else: ?>
                                Belum Ada Gambar Utama
                            <?php endif; ?>
                        </div>

                        <div class="news-field">
                            <label>Ganti Gambar Utama</label>
                            <input type="file" name="gambar" class="form-control" id="gambarEditBerita" accept="image/*">
                            <small class="text-muted fw-bold d-block mt-2">Kosongkan jika tidak ingin mengganti gambar utama.</small>
                        </div>

                        <?php if(!empty($gambar) && file_exists($gambar_file)): ?>
                            <label class="news-check">
                                <input type="checkbox" name="hapus_gambar_utama" value="1">
                                <span>Hapus gambar utama saat menyimpan perubahan.</span>
                            </label>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="news-edit-card">
                    <div class="news-edit-head">
                        <h5>Poster Pamflet</h5>
                        <small>Poster otomatis untuk dibagikan ke media sosial.</small>
                    </div>
                    <div class="news-edit-body">
                        <?php if(!empty($poster) && file_exists($poster_file)): ?>
                            <div class="poster-preview"><img src="<?= base_url('assets/news/poster/'.$poster) ?>" alt="Poster Berita"></div>
                        <?php else: ?>
                            <div class="news-empty-box mb-3">Poster belum dibuat.</div>
                        <?php endif; ?>

                        <div class="news-field">
                            <label>Mode Gambar Pamflet</label>
                            <select name="poster_fit_mode" class="news-select">
                                <option value="cover" <?= ($berita->poster_fit_mode ?? 'cover') == 'cover' ? 'selected' : '' ?>>Penuh / Crop Rapi</option>
                                <option value="contain" <?= ($berita->poster_fit_mode ?? '') == 'contain' ? 'selected' : '' ?>>Gambar Utuh / Tidak Terpotong</option>
                            </select>
                        </div>

                        <div class="news-field">
                            <label>Fokus Gambar</label>
                            <select name="poster_focus" class="news-select">
                                <option value="center" <?= ($berita->poster_focus ?? 'center') == 'center' ? 'selected' : '' ?>>Tengah</option>
                                <option value="top" <?= ($berita->poster_focus ?? '') == 'top' ? 'selected' : '' ?>>Atas</option>
                                <option value="bottom" <?= ($berita->poster_focus ?? '') == 'bottom' ? 'selected' : '' ?>>Bawah</option>
                                <option value="left" <?= ($berita->poster_focus ?? '') == 'left' ? 'selected' : '' ?>>Kiri</option>
                                <option value="right" <?= ($berita->poster_focus ?? '') == 'right' ? 'selected' : '' ?>>Kanan</option>
                            </select>
                        </div>

                        <div class="news-field">
                            <label>Layout Foto Pamflet</label>
                            <select name="poster_layout" class="news-select">
                                <option value="auto" <?= ($berita->poster_layout ?? 'auto') == 'auto' ? 'selected' : '' ?>>Otomatis</option>
                                <option value="single" <?= ($berita->poster_layout ?? '') == 'single' ? 'selected' : '' ?>>1 Foto Besar</option>
                                <option value="two" <?= ($berita->poster_layout ?? '') == 'two' ? 'selected' : '' ?>>2 Foto</option>
                                <option value="three" <?= ($berita->poster_layout ?? '') == 'three' ? 'selected' : '' ?>>3 Foto</option>
                                <option value="grid" <?= ($berita->poster_layout ?? '') == 'grid' ? 'selected' : '' ?>>Grid 5 Foto</option>
                            </select>
                        </div>

                        <div class="news-info-list">
                            <div class="news-info-item"><small>Kategori</small><span class="news-category-badge"><?= htmlspecialchars($kategori, ENT_QUOTES, 'UTF-8') ?></span></div>
                            <div class="news-info-item"><small>Status Saat Ini</small><?php if($status == 'Published'): ?><span class="news-status news-status-published">Published</span><?php else: ?><span class="news-status news-status-draft">Draft</span><?php endif; ?></div>
                            <div class="news-info-item"><small>Tanggal Dibuat</small><strong><?= !empty($berita->created_at) ? date('d M Y H:i', strtotime($berita->created_at)) : '-' ?></strong></div>
                            <div class="news-info-item"><small>Tanggal Publish</small><strong><?= !empty($berita->published_at) ? date('d M Y H:i', strtotime($berita->published_at)) : '-' ?></strong></div>
                            <div class="news-info-item"><small>Poster Terakhir Dibuat</small><strong><?= !empty($berita->poster_generated_at) ? date('d M Y H:i', strtotime($berita->poster_generated_at)) : '-' ?></strong></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="news-action-bar">
            <div class="news-action-left">
                <a href="<?= base_url('berita') ?>" class="btn-back-news">Kembali</a>
                <a href="<?= base_url('berita/detail/'.$berita->id) ?>" target="_blank" class="btn-preview-news">Preview Berita</a>
            </div>
            <div class="news-action-right">
                <a href="<?= base_url('berita/regenerate_pamflet/'.$berita->id) ?>" class="btn-poster-news" onclick="return confirm('Generate ulang poster pamflet? Simpan perubahan dulu jika ada data yang baru diedit.')">Generate Poster</a>
                <a href="<?= base_url('berita/download_pamflet/'.$berita->id) ?>" class="btn-download-news">Download JPG</a>
                <button type="submit" class="btn-save-news">Simpan Perubahan</button>
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
            reader.onload = function(e){ previewMain.innerHTML = '<img src="'+e.target.result+'" alt="Preview Gambar Utama">'; };
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
                    img.alt = 'Preview Foto Kegiatan';
                    previewMulti.appendChild(img);
                };
                reader.readAsDataURL(file);
            });
        });
    }
});
</script>

<?php $this->load->view('templates/footer'); ?>