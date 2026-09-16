<?php $this->load->view('templates/header'); ?>
<?php $this->load->view('templates/sidebar'); ?>

<div class="content">

<style>
.pamflet-admin-hero{
    background:
        radial-gradient(circle at top right, rgba(34,197,94,.16), transparent 34%),
        linear-gradient(135deg,#ecfdf5,#ffffff);
    border:1px solid #dcfce7;
    border-radius:24px;
    padding:22px;
    box-shadow:0 14px 35px rgba(15,23,42,.06);
    margin-bottom:20px;
}

.pamflet-admin-hero p{
    color:#64748b;
    font-weight:700;
    margin:5px 0 0;
}

.pamflet-admin-grid{
    display:grid;
    grid-template-columns:390px 1fr;
    gap:18px;
    align-items:start;
}

.pamflet-card{
    background:#fff;
    border:1px solid #e2e8f0;
    border-radius:24px;
    box-shadow:0 14px 35px rgba(15,23,42,.06);
    overflow:hidden;
}

.pamflet-head{
    padding:18px 20px;
    border-bottom:1px solid #e2e8f0;
}

.pamflet-head h5{
    margin:0;
    color:#14532d;
    font-weight:950;
}

.pamflet-head small{
    display:block;
    color:#64748b;
    font-weight:700;
    margin-top:4px;
}

.pamflet-body{
    padding:20px;
}

.pamflet-field{
    margin-bottom:15px;
}

.pamflet-field label{
    display:block;
    color:#334155;
    font-size:13px;
    font-weight:850;
    margin-bottom:7px;
}

.pamflet-input,
.pamflet-textarea{
    width:100%;
    border:1px solid #cbd5e1;
    background:#f8fafc;
    border-radius:16px;
    padding:11px 13px;
    color:#0f172a;
    font-weight:700;
    outline:none;
}

.pamflet-input{
    min-height:46px;
}

.pamflet-textarea{
    min-height:120px;
    resize:vertical;
    line-height:1.6;
}

.pamflet-input:focus,
.pamflet-textarea:focus{
    background:white;
    border-color:#22c55e;
    box-shadow:0 0 0 4px rgba(34,197,94,.12);
}

.pamflet-upload{
    border:1px dashed #86efac;
    background:#f0fdf4;
    border-radius:18px;
    padding:14px;
}

.pamflet-preview{
    width:100%;
    height:260px;
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

.pamflet-preview img{
    width:100%;
    height:100%;
    object-fit:cover;
}

.btn-pamflet-save{
    width:100%;
    min-height:46px;
    border:0;
    border-radius:16px;
    background:linear-gradient(135deg,#15803d,#22c55e);
    color:white;
    font-weight:950;
    box-shadow:0 12px 26px rgba(22,163,74,.22);
}

.pamflet-thumb{
    width:86px;
    height:110px;
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

.pamflet-actions{
    display:flex;
    gap:7px;
    flex-wrap:wrap;
}

.pamflet-action{
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

.pamflet-action-view{
    background:#e0f2fe;
    color:#075985;
}

.pamflet-action-publish{
    background:#dcfce7;
    color:#166534;
}

.pamflet-action-draft{
    background:#fef3c7;
    color:#92400e;
}

.pamflet-action-delete{
    background:#fee2e2;
    color:#991b1b;
}

.pamflet-action-view:hover{color:#075985;}
.pamflet-action-publish:hover{color:#166534;}
.pamflet-action-draft:hover{color:#92400e;}
.pamflet-action-delete:hover{color:#991b1b;}

.dataTables_wrapper{
    padding:18px 20px 20px;
}

@media(max-width:1200px){
    .pamflet-admin-grid{
        grid-template-columns:1fr;
    }
}

@media(max-width:768px){
    .pamflet-admin-hero,
    .pamflet-card{
        border-radius:20px;
    }

    .pamflet-actions{
        display:grid;
        grid-template-columns:1fr;
    }

    .pamflet-action{
        width:100%;
    }
}
</style>

<div class="pamflet-admin-hero">
    <div class="d-flex justify-content-between align-items-center gap-3 flex-wrap">
        <div>
            <h2 class="glow mb-1">Pamflet Informasi</h2>
            <p>Kelola pamflet, poster, dan informasi visual yang tampil di halaman website madrasah.</p>
        </div>
        <div class="d-flex gap-2 flex-wrap">
            <a href="<?= base_url('admin_cloud_sync/sync_website') ?>" class="btn btn-outline-success fw-bold d-inline-flex align-items-center rounded-4 px-3" style="border-width:2px; font-weight:800;" onclick="return confirm('Kirim pamflet visual lokal ke man3banjar.sch.id?');">
                🚀 Kirim ke Website Online
            </a>
            <a href="<?= base_url('admin_cloud_sync/pull_website') ?>" class="btn btn-outline-primary fw-bold d-inline-flex align-items-center rounded-4 px-3" style="border-width:2px; font-weight:800;" onclick="return confirm('Tarik data pamflet terbaru dari man3banjar.sch.id ke lokal?');">
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

<div class="pamflet-admin-grid">

    <div class="pamflet-card">
        <div class="pamflet-head">
            <h5>Tambah Pamflet</h5>
            <small>Pamflet baru akan tersimpan sebagai Draft.</small>
        </div>

        <div class="pamflet-body">
            <form method="post"
                  action="<?= base_url('admin_website/save_pamflet') ?>"
                  enctype="multipart/form-data">

                <div class="pamflet-field">
                    <label>Judul Pamflet</label>
                    <input type="text" name="judul" class="pamflet-input" required>
                </div>

                <div class="pamflet-field">
                    <label>Tanggal</label>
                    <input type="date" name="tanggal" class="pamflet-input" value="<?= date('Y-m-d') ?>">
                </div>

                <div class="pamflet-field">
                    <label>Deskripsi</label>
                    <textarea name="deskripsi" class="pamflet-textarea"></textarea>
                </div>

                <div class="pamflet-field">
                    <label>Gambar Pamflet</label>

                    <div class="pamflet-upload">
                        <div class="pamflet-preview" id="previewPamflet">
                            Preview Pamflet
                        </div>

                        <input type="file"
                               name="gambar"
                               id="gambarPamflet"
                               class="form-control"
                               accept="image/*"
                               required>
                    </div>
                </div>

                <button class="btn-pamflet-save">
                    Simpan Pamflet
                </button>

            </form>
        </div>
    </div>

    <div class="pamflet-card">
        <div class="pamflet-head">
            <h5>Daftar Pamflet</h5>
            <small>Publish pamflet agar tampil di halaman website.</small>
        </div>

        <div class="table-responsive">
            <table class="table table-bordered table-striped datatable nowrap" style="width:100%">
                <thead class="table-success">
                    <tr>
                        <th style="width:100px;">Gambar</th>
                        <th>Judul</th>
                        <th>Tanggal</th>
                        <th>Status</th>
                        <th style="width:230px;">Aksi</th>
                    </tr>
                </thead>

                <tbody>
                    <?php foreach($pamflet as $p): ?>
                        <?php
                        $gambar_file = !empty($p->gambar) ? FCPATH.'assets/pamflet/'.$p->gambar : '';
                        ?>
                        <tr>
                            <td>
                                <?php if(!empty($p->gambar) && file_exists($gambar_file)): ?>
                                    <img src="<?= base_url('assets/pamflet/'.$p->gambar) ?>"
                                         class="pamflet-thumb">
                                <?php else: ?>
                                    -
                                <?php endif; ?>
                            </td>

                            <td>
                                <strong><?= htmlspecialchars($p->judul, ENT_QUOTES, 'UTF-8') ?></strong>
                                <div class="text-muted small fw-bold">
                                    <?= htmlspecialchars($p->deskripsi ?? '', ENT_QUOTES, 'UTF-8') ?>
                                </div>
                            </td>

                            <td>
                                <?= !empty($p->tanggal) ? date('d M Y', strtotime($p->tanggal)) : '-' ?>
                            </td>

                            <td>
                                <?php if($p->status == 'Published'): ?>
                                    <span class="status-pill status-published">Published</span>
                                <?php else: ?>
                                    <span class="status-pill status-draft">Draft</span>
                                <?php endif; ?>
                            </td>

                            <td>
                                <div class="pamflet-actions">
                                    <?php if(!empty($p->gambar) && file_exists($gambar_file)): ?>
                                        <a href="<?= base_url('assets/pamflet/'.$p->gambar) ?>"
                                           target="_blank"
                                           class="pamflet-action pamflet-action-view">
                                            Lihat
                                        </a>
                                    <?php endif; ?>

                                    <?php if($p->status == 'Published'): ?>
                                        <a href="<?= base_url('admin_website/draft_pamflet/'.$p->id) ?>"
                                           class="pamflet-action pamflet-action-draft"
                                           onclick="return confirm('Jadikan Draft?')">
                                            Draftkan
                                        </a>
                                    <?php else: ?>
                                        <a href="<?= base_url('admin_website/publish_pamflet/'.$p->id) ?>"
                                           class="pamflet-action pamflet-action-publish"
                                           onclick="return confirm('Publish pamflet ini?')">
                                            Publish
                                        </a>
                                    <?php endif; ?>

                                    <a href="<?= base_url('admin_website/delete_pamflet/'.$p->id) ?>"
                                       class="pamflet-action pamflet-action-delete"
                                       onclick="return confirm('Hapus pamflet ini?')">
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

    const input = document.getElementById('gambarPamflet');
    const preview = document.getElementById('previewPamflet');

    if(input && preview){
        input.addEventListener('change', function(){
            const file = this.files[0];

            if(!file){
                preview.innerHTML = 'Preview Pamflet';
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