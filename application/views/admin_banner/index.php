<?php $this->load->view('templates/header'); ?>
<?php $this->load->view('templates/sidebar'); ?>

<div class="content">
<style>
.banner-page{max-width:1320px;margin:0 auto;}.banner-hero{background:radial-gradient(circle at top right,rgba(34,197,94,.16),transparent 34%),linear-gradient(135deg,#ecfdf5,#fff);border:1px solid #dcfce7;border-radius:26px;padding:22px;box-shadow:0 14px 35px rgba(15,23,42,.06);margin-bottom:20px;display:flex;justify-content:space-between;gap:14px;align-items:center;}.banner-hero h2{font-weight:950;color:#14532d;margin:0}.banner-hero p{color:#64748b;font-weight:700;margin:5px 0 0}.banner-grid{display:grid;grid-template-columns:420px minmax(0,1fr);gap:20px;align-items:start}.banner-card{background:#fff;border:1px solid #e2e8f0;border-radius:24px;box-shadow:0 14px 35px rgba(15,23,42,.06);overflow:hidden;margin-bottom:18px}.banner-head{padding:18px 20px;border-bottom:1px solid #e2e8f0;background:#fff}.banner-head h5{font-weight:950;color:#14532d;margin:0}.banner-head small{display:block;color:#64748b;font-weight:700;margin-top:4px}.banner-body{padding:20px}.banner-field{margin-bottom:14px}.banner-field label{display:block;font-weight:850;color:#334155;font-size:13px;margin-bottom:7px}.banner-input,.banner-textarea,.banner-select{width:100%;border:1px solid #cbd5e1;background:#f8fafc;border-radius:16px;padding:11px 13px;color:#0f172a;font-weight:700;outline:none}.banner-input,.banner-select{min-height:46px}.banner-textarea{min-height:110px;resize:vertical}.banner-input:focus,.banner-textarea:focus,.banner-select:focus{background:#fff;border-color:#22c55e;box-shadow:0 0 0 4px rgba(34,197,94,.12)}.btn-banner-save{border:0;border-radius:16px;min-height:46px;padding:0 16px;font-weight:950;color:#fff;background:linear-gradient(135deg,#15803d,#22c55e);box-shadow:0 12px 26px rgba(22,163,74,.22);width:100%}.banner-list{display:grid;grid-template-columns:repeat(2,minmax(0,1fr));gap:16px}.banner-item{background:#fff;border:1px solid #e2e8f0;border-radius:24px;overflow:hidden;box-shadow:0 12px 32px rgba(15,23,42,.06)}.banner-img{height:190px;background:#f8fafc;display:flex;align-items:center;justify-content:center;color:#64748b;font-weight:900;overflow:hidden}.banner-img img{width:100%;height:100%;object-fit:cover}.banner-info{padding:16px}.banner-title-row{display:flex;align-items:flex-start;justify-content:space-between;gap:10px;margin-bottom:8px}.banner-info h5{font-weight:950;color:#0f172a;margin:0;line-height:1.25}.banner-info p{color:#64748b;font-weight:700;font-size:13px;margin:0 0 12px;line-height:1.55}.banner-badges{display:flex;flex-wrap:wrap;gap:8px;margin-bottom:12px}.banner-badge{display:inline-flex;align-items:center;min-height:30px;padding:0 10px;border-radius:999px;font-size:12px;font-weight:950}.banner-published{background:#dcfce7;color:#166534}.banner-draft{background:#fef3c7;color:#92400e}.banner-order{background:#e0f2fe;color:#075985}.banner-actions{display:flex;flex-wrap:wrap;gap:8px}.banner-actions a,.banner-actions button{border:0;text-decoration:none;display:inline-flex;align-items:center;justify-content:center;min-height:36px;border-radius:12px;padding:0 10px;font-size:12px;font-weight:950}.act-edit{background:#e0f2fe;color:#075985}.act-publish{background:#dcfce7;color:#166534}.act-draft{background:#fef3c7;color:#92400e}.act-delete{background:#fee2e2;color:#991b1b}.banner-empty{border:1px dashed #cbd5e1;border-radius:24px;background:#f8fafc;padding:34px;text-align:center;color:#64748b;font-weight:850}.banner-modal{display:none;position:fixed;inset:0;z-index:3000;background:rgba(15,23,42,.45);align-items:center;justify-content:center;padding:18px}.banner-modal.show{display:flex}.banner-modal-card{width:min(720px,100%);max-height:92vh;overflow:auto;background:#fff;border-radius:26px;box-shadow:0 24px 80px rgba(15,23,42,.25)}.banner-modal-head{padding:18px 20px;border-bottom:1px solid #e2e8f0;display:flex;justify-content:space-between;gap:12px;align-items:center}.banner-modal-head h5{margin:0;font-weight:950;color:#14532d}.banner-modal-close{border:0;background:#f1f5f9;border-radius:12px;width:38px;height:38px;font-weight:950}.banner-modal-body{padding:20px}@media(max-width:1100px){.banner-grid{grid-template-columns:1fr}.banner-list{grid-template-columns:1fr 1fr}}@media(max-width:768px){.banner-hero{display:block;border-radius:20px}.banner-list{grid-template-columns:1fr}.banner-card,.banner-item{border-radius:20px}}
</style>

<div class="banner-page">
    <div class="banner-hero">
        <div>
            <h2>Banner Slider Homepage</h2>
            <p>Kelola gambar utama yang tampil pada slider halaman awal website madrasah.</p>
        </div>
        <div class="d-flex gap-2 flex-wrap">
            <a href="<?= base_url('admin_cloud_sync/sync_website') ?>" class="btn btn-outline-success fw-bold d-inline-flex align-items-center rounded-4 px-3" style="border-width:2px; font-weight:800;" onclick="return confirm('Kirim seluruh banner slider lokal ke man3banjar.sch.id?');">
                🚀 Kirim ke Website Online
            </a>
            <a href="<?= base_url('admin_cloud_sync/pull_website') ?>" class="btn btn-outline-primary fw-bold d-inline-flex align-items-center rounded-4 px-3" style="border-width:2px; font-weight:800;" onclick="return confirm('Tarik data banner slider terbaru dari man3banjar.sch.id ke lokal?');">
                📥 Tarik dari Online
            </a>
            <a href="<?= base_url() ?>" target="_blank" class="btn btn-success rounded-4 fw-bold">Lihat Homepage</a>
        </div>
    </div>

    <?php if($this->session->flashdata('success')): ?>
        <div class="alert alert-success rounded-4 fw-bold"><?= $this->session->flashdata('success') ?></div>
    <?php endif; ?>
    <?php if($this->session->flashdata('error')): ?>
        <div class="alert alert-danger rounded-4 fw-bold"><?= $this->session->flashdata('error') ?></div>
    <?php endif; ?>

    <div class="banner-grid">
        <div class="banner-card">
            <div class="banner-head">
                <h5>Tambah Banner</h5>
                <small>Gunakan gambar landscape. Rekomendasi 1600x700 px atau lebih.</small>
            </div>
            <div class="banner-body">
                <form method="post" action="<?= base_url('admin_banner/save') ?>" enctype="multipart/form-data">
                    <div class="banner-field"><label>Judul</label><input type="text" name="judul" class="banner-input" required></div>
                    <div class="banner-field"><label>Subjudul</label><input type="text" name="subjudul" class="banner-input" placeholder="Contoh: PPDB 2026/2027 Dibuka"></div>
                    <div class="banner-field"><label>Deskripsi</label><textarea name="deskripsi" class="banner-textarea" placeholder="Teks singkat yang tampil di slider"></textarea></div>
                    <div class="banner-field"><label>Gambar Banner</label><input type="file" name="gambar" class="form-control" accept="image/*" required></div>
                    <div class="banner-field"><label>Teks Tombol</label><input type="text" name="button_text" class="banner-input" placeholder="Contoh: Lihat Informasi"></div>
                    <div class="banner-field"><label>URL Tombol</label><input type="text" name="button_url" class="banner-input" placeholder="Contoh: <?= base_url('website/berita') ?>"></div>
                    <div class="banner-field"><label>Status</label><select name="status" class="banner-select"><option value="Draft">Draft</option><option value="Published">Published</option></select></div>
                    <div class="banner-field"><label>Urutan</label><input type="number" name="urutan" class="banner-input" value="0"></div>
                    <button class="btn-banner-save">Simpan Banner</button>
                </form>
            </div>
        </div>

        <div class="banner-card">
            <div class="banner-head">
                <h5>Daftar Banner</h5>
                <small>Banner Published akan tampil di slider homepage sesuai urutan.</small>
            </div>
            <div class="banner-body">
                <?php if(!empty($banner)): ?>
                    <div class="banner-list">
                        <?php foreach($banner as $b): ?>
                            <div class="banner-item">
                                <div class="banner-img">
                                    <?php $file = FCPATH.'assets/banner/'.$b->gambar; ?>
                                    <?php if(!empty($b->gambar) && file_exists($file)): ?>
                                        <img src="<?= base_url('assets/banner/'.$b->gambar) ?>" alt="<?= htmlspecialchars($b->judul, ENT_QUOTES, 'UTF-8') ?>">
                                    <?php else: ?>
                                        Gambar tidak ditemukan
                                    <?php endif; ?>
                                </div>
                                <div class="banner-info">
                                    <div class="banner-title-row">
                                        <h5><?= htmlspecialchars($b->judul, ENT_QUOTES, 'UTF-8') ?></h5>
                                    </div>
                                    <div class="banner-badges">
                                        <?php if($b->status == 'Published'): ?><span class="banner-badge banner-published">Published</span><?php else: ?><span class="banner-badge banner-draft">Draft</span><?php endif; ?>
                                        <span class="banner-badge banner-order">Urutan <?= (int)$b->urutan ?></span>
                                    </div>
                                    <p><?= htmlspecialchars($b->deskripsi ?? '-', ENT_QUOTES, 'UTF-8') ?></p>
                                    <div class="banner-actions">
                                        <button type="button" class="act-edit" onclick="openEditBanner(<?= (int)$b->id ?>)">Edit</button>
                                        <?php if($b->status == 'Published'): ?><a class="act-draft" href="<?= base_url('admin_banner/draft/'.$b->id) ?>">Draft</a><?php else: ?><a class="act-publish" href="<?= base_url('admin_banner/publish/'.$b->id) ?>">Publish</a><?php endif; ?>
                                        <a class="act-delete" href="<?= base_url('admin_banner/delete/'.$b->id) ?>" onclick="return confirm('Hapus banner ini?')">Hapus</a>
                                    </div>
                                </div>
                            </div>

                            <div class="banner-modal" id="editBanner<?= (int)$b->id ?>">
                                <div class="banner-modal-card">
                                    <div class="banner-modal-head"><h5>Edit Banner</h5><button type="button" class="banner-modal-close" onclick="closeEditBanner(<?= (int)$b->id ?>)">×</button></div>
                                    <div class="banner-modal-body">
                                        <form method="post" action="<?= base_url('admin_banner/update/'.$b->id) ?>" enctype="multipart/form-data">
                                            <div class="banner-field"><label>Judul</label><input type="text" name="judul" class="banner-input" value="<?= htmlspecialchars($b->judul, ENT_QUOTES, 'UTF-8') ?>" required></div>
                                            <div class="banner-field"><label>Subjudul</label><input type="text" name="subjudul" class="banner-input" value="<?= htmlspecialchars($b->subjudul ?? '', ENT_QUOTES, 'UTF-8') ?>"></div>
                                            <div class="banner-field"><label>Deskripsi</label><textarea name="deskripsi" class="banner-textarea"><?= htmlspecialchars($b->deskripsi ?? '', ENT_QUOTES, 'UTF-8') ?></textarea></div>
                                            <div class="banner-field"><label>Ganti Gambar</label><input type="file" name="gambar" class="form-control" accept="image/*"><small class="text-muted fw-bold">Kosongkan jika gambar tidak diganti.</small></div>
                                            <div class="banner-field"><label>Teks Tombol</label><input type="text" name="button_text" class="banner-input" value="<?= htmlspecialchars($b->button_text ?? '', ENT_QUOTES, 'UTF-8') ?>"></div>
                                            <div class="banner-field"><label>URL Tombol</label><input type="text" name="button_url" class="banner-input" value="<?= htmlspecialchars($b->button_url ?? '', ENT_QUOTES, 'UTF-8') ?>"></div>
                                            <div class="banner-field"><label>Status</label><select name="status" class="banner-select"><option value="Draft" <?= $b->status == 'Draft' ? 'selected' : '' ?>>Draft</option><option value="Published" <?= $b->status == 'Published' ? 'selected' : '' ?>>Published</option></select></div>
                                            <div class="banner-field"><label>Urutan</label><input type="number" name="urutan" class="banner-input" value="<?= (int)$b->urutan ?>"></div>
                                            <button class="btn-banner-save">Simpan Perubahan</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php else: ?>
                    <div class="banner-empty">Belum ada banner slider. Tambahkan banner pertama dari form di kiri.</div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
</div>

<script>
function openEditBanner(id){document.getElementById('editBanner'+id).classList.add('show');}
function closeEditBanner(id){document.getElementById('editBanner'+id).classList.remove('show');}
document.addEventListener('click',function(e){if(e.target.classList.contains('banner-modal')){e.target.classList.remove('show');}});
</script>

<?php $this->load->view('templates/footer'); ?>
