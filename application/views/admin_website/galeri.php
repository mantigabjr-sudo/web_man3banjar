<?php $this->load->view('templates/header'); ?>
<?php $this->load->view('templates/sidebar'); ?>

<div class="content">

<style>
.galeri-admin-hero{
    background:
        radial-gradient(circle at top right, rgba(34,197,94,.16), transparent 34%),
        linear-gradient(135deg,#ecfdf5,#ffffff);
    border:1px solid #dcfce7;
    border-radius:24px;
    padding:22px;
    box-shadow:0 14px 35px rgba(15,23,42,.06);
    margin-bottom:20px;
}

.galeri-admin-hero p{
    color:#64748b;
    font-weight:700;
    margin:5px 0 0;
}

.galeri-admin-grid{
    display:grid;
    grid-template-columns:390px 1fr;
    gap:18px;
    align-items:start;
}

.galeri-card{
    background:#fff;
    border:1px solid #e2e8f0;
    border-radius:24px;
    box-shadow:0 14px 35px rgba(15,23,42,.06);
    overflow:hidden;
}

.galeri-head{
    padding:18px 20px;
    border-bottom:1px solid #e2e8f0;
}

.galeri-head h5{
    margin:0;
    color:#14532d;
    font-weight:950;
}

.galeri-head small{
    display:block;
    color:#64748b;
    font-weight:700;
    margin-top:4px;
}

.galeri-body{
    padding:20px;
}

.galeri-field{
    margin-bottom:15px;
}

.galeri-field label{
    display:block;
    color:#334155;
    font-size:13px;
    font-weight:850;
    margin-bottom:7px;
}

.galeri-input,
.galeri-textarea{
    width:100%;
    border:1px solid #cbd5e1;
    background:#f8fafc;
    border-radius:16px;
    padding:11px 13px;
    color:#0f172a;
    font-weight:700;
    outline:none;
}

.galeri-input{
    min-height:46px;
}

.galeri-textarea{
    min-height:120px;
    resize:vertical;
    line-height:1.6;
}

.galeri-input:focus,
.galeri-textarea:focus{
    background:white;
    border-color:#22c55e;
    box-shadow:0 0 0 4px rgba(34,197,94,.12);
}

.galeri-upload{
    border:1px dashed #86efac;
    background:#f0fdf4;
    border-radius:18px;
    padding:14px;
}

.galeri-preview{
    width:100%;
    height:230px;
    border-radius:15px;
    background:#ffffff;
    border:1px solid #dcfce7;
    color:#166534;
    display:flex;
    align-items:center;
    justify-content:center;
    font-weight:900;
    margin-bottom:12px;
    overflow:hidden;
}

.galeri-preview img{
    width:100%;
    height:100%;
    object-fit:cover;
}

.btn-galeri-save{
    width:100%;
    min-height:46px;
    border:0;
    border-radius:16px;
    background:linear-gradient(135deg,#15803d,#22c55e);
    color:white;
    font-weight:950;
    box-shadow:0 12px 26px rgba(22,163,74,.22);
}

.galeri-thumb{
    width:100px;
    height:70px;
    object-fit:cover;
    border-radius:14px;
    border:1px solid #e2e8f0;
    background:#ecfdf5;
}

.status-pill{
    display:inline-flex;
    padding:7px 11px;
    border-radius:999px;
    font-size:12px;
    font-weight:950;
}

.status-published{
    background:#dcfce7;
    color:#166534;
}

.status-draft{
    background:#fef3c7;
    color:#92400e;
}

.galeri-actions{
    display:flex;
    gap:7px;
    flex-wrap:wrap;
}

.galeri-action{
    display:inline-flex;
    min-height:34px;
    padding:0 11px;
    border-radius:12px;
    align-items:center;
    justify-content:center;
    font-size:12px;
    font-weight:950;
    text-decoration:none;
}

.galeri-action-view{background:#e0f2fe;color:#075985;}
.galeri-action-publish{background:#dcfce7;color:#166534;}
.galeri-action-draft{background:#fef3c7;color:#92400e;}
.galeri-action-delete{background:#fee2e2;color:#991b1b;}

.galeri-action-view:hover{color:#075985;}
.galeri-action-publish:hover{color:#166534;}
.galeri-action-draft:hover{color:#92400e;}
.galeri-action-delete:hover{color:#991b1b;}

.dataTables_wrapper{
    padding:18px 20px 20px;
}

@media(max-width:1200px){
    .galeri-admin-grid{
        grid-template-columns:1fr;
    }
}

@media(max-width:768px){
    .galeri-admin-hero,
    .galeri-card{
        border-radius:20px;
    }

    .galeri-actions{
        display:grid;
        grid-template-columns:1fr;
    }

    .galeri-action{
        width:100%;
    }
}
</style>

<div class="galeri-admin-hero">
    <div class="d-flex justify-content-between align-items-center gap-3 flex-wrap">
        <div>
            <h2 class="glow mb-1">Galeri Madrasah</h2>
            <p>Upload dokumentasi kegiatan madrasah untuk ditampilkan pada halaman website.</p>
        </div>
        <div class="d-flex gap-2 flex-wrap">
            <a href="<?= base_url('admin_cloud_sync/sync_website') ?>" class="btn btn-outline-success fw-bold d-inline-flex align-items-center rounded-4 px-3" style="border-width:2px; font-weight:800;" onclick="return confirm('Kirim galeri foto lokal ke man3banjar.sch.id?');">
                🚀 Kirim ke Website Online
            </a>
            <a href="<?= base_url('admin_cloud_sync/pull_website') ?>" class="btn btn-outline-primary fw-bold d-inline-flex align-items-center rounded-4 px-3" style="border-width:2px; font-weight:800;" onclick="return confirm('Tarik data galeri foto terbaru dari man3banjar.sch.id ke lokal?');">
                📥 Tarik dari Online
            </a>
        </div>
    </div>
</div>

<?php if($this->session->flashdata('success')): ?>
    <div class="alert alert-success rounded-4">
        <?= $this->session->flashdata('success') ?>
    </div>
<?php endif; ?>

<?php if($this->session->flashdata('error')): ?>
    <div class="alert alert-danger rounded-4">
        <?= $this->session->flashdata('error') ?>
    </div>
<?php endif; ?>

<div class="galeri-admin-grid">

    <div class="galeri-card">
        <div class="galeri-head">
            <h5>Tambah Galeri</h5>
            <small>Galeri baru akan tersimpan sebagai Draft.</small>
        </div>

        <div class="galeri-body">
            <form method="post"
                  action="<?= base_url('admin_website/save_galeri') ?>"
                  enctype="multipart/form-data">

                <div class="galeri-field">
                    <label>Judul Galeri</label>
                    <input type="text" name="judul" class="galeri-input" required>
                </div>

                <div class="galeri-field">
                    <label>Tanggal</label>
                    <input type="date" name="tanggal" class="galeri-input" value="<?= date('Y-m-d') ?>">
                </div>

                <div class="galeri-field">
                    <label>Deskripsi</label>
                    <textarea name="deskripsi" class="galeri-textarea"></textarea>
                </div>

                <div class="galeri-field">
                    <label>Gambar Galeri</label>

                    <div class="galeri-upload">
                        <div class="galeri-preview" id="previewGaleri">
                            Preview Galeri
                        </div>

                        <input type="file"
                               name="gambar"
                               id="gambarGaleri"
                               class="form-control"
                               accept="image/*"
                               required>
                    </div>
                </div>

                <button class="btn-galeri-save">
                    Simpan Galeri
                </button>

            </form>
        </div>
    </div>

    <div class="galeri-card">
        <div class="galeri-head">
            <h5>Daftar Galeri</h5>
            <small>Publish galeri agar tampil di halaman website.</small>
        </div>

        <div class="table-responsive">
            <table class="table table-bordered table-striped datatable nowrap" style="width:100%">
                <thead class="table-success">
                    <tr>
                        <th style="width:110px;">Gambar</th>
                        <th>Judul</th>
                        <th>Tanggal</th>
                        <th>Status</th>
                        <th style="width:230px;">Aksi</th>
                    </tr>
                </thead>

                <tbody>
                    <?php foreach($galeri as $g): ?>
                        <?php
                        $gambar_file = !empty($g->gambar) ? FCPATH.'assets/galeri/'.$g->gambar : '';
                        ?>
                        <tr>
                            <td>
                                <?php if(!empty($g->gambar) && file_exists($gambar_file)): ?>
                                    <img src="<?= base_url('assets/galeri/'.$g->gambar) ?>"
                                         class="galeri-thumb">
                                <?php else: ?>
                                    -
                                <?php endif; ?>
                            </td>

                            <td>
                                <strong><?= htmlspecialchars($g->judul, ENT_QUOTES, 'UTF-8') ?></strong>
                                <div class="text-muted small fw-bold">
                                    <?= htmlspecialchars($g->deskripsi ?? '', ENT_QUOTES, 'UTF-8') ?>
                                </div>
                            </td>

                            <td>
                                <?= !empty($g->tanggal) ? date('d M Y', strtotime($g->tanggal)) : '-' ?>
                            </td>

                            <td>
                                <?php if($g->status == 'Published'): ?>
                                    <span class="status-pill status-published">Published</span>
                                <?php else: ?>
                                    <span class="status-pill status-draft">Draft</span>
                                <?php endif; ?>
                            </td>

                            <td>
                                <div class="galeri-actions">
                                    <?php if(!empty($g->gambar) && file_exists($gambar_file)): ?>
                                        <a href="<?= base_url('assets/galeri/'.$g->gambar) ?>"
                                           target="_blank"
                                           class="galeri-action galeri-action-view">
                                            Lihat
                                        </a>
                                    <?php endif; ?>

                                    <?php if($g->status == 'Published'): ?>
                                        <a href="<?= base_url('admin_website/draft_galeri/'.$g->id) ?>"
                                           class="galeri-action galeri-action-draft"
                                           onclick="return confirm('Jadikan Draft?')">
                                            Draftkan
                                        </a>
                                    <?php else: ?>
                                        <a href="<?= base_url('admin_website/publish_galeri/'.$g->id) ?>"
                                           class="galeri-action galeri-action-publish"
                                           onclick="return confirm('Publish galeri ini?')">
                                            Publish
                                        </a>
                                    <?php endif; ?>

                                    <a href="<?= base_url('admin_website/delete_galeri/'.$g->id) ?>"
                                       class="galeri-action galeri-action-delete"
                                       onclick="return confirm('Hapus galeri ini?')">
                                        Hapus
                                    </a>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>

            </table>
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
                preview.innerHTML = 'Preview Galeri';
                return;
            }

            const reader = new FileReader();

            reader.onload = function(e){
                preview.innerHTML = '<img src="'+e.target.result+'" alt="Preview">';
            };

            reader.readAsDataURL(file);
        });
    }

});
</script>

<?php $this->load->view('templates/footer'); ?>