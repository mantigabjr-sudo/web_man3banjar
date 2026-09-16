<?php $this->load->view('templates/header'); ?>
<?php $this->load->view('templates/sidebar'); ?>

<div class="content">

<style>
.video-admin-hero{
    background:
        radial-gradient(circle at top right, rgba(34,197,94,.16), transparent 34%),
        linear-gradient(135deg,#ecfdf5,#ffffff);
    border:1px solid #dcfce7;
    border-radius:24px;
    padding:22px;
    box-shadow:0 14px 35px rgba(15,23,42,.06);
    margin-bottom:20px;
}

.video-admin-hero p{
    color:#64748b;
    font-weight:700;
    margin:5px 0 0;
}

.video-admin-grid{
    display:grid;
    grid-template-columns:390px 1fr;
    gap:18px;
    align-items:start;
}

.video-card{
    background:#fff;
    border:1px solid #e2e8f0;
    border-radius:24px;
    box-shadow:0 14px 35px rgba(15,23,42,.06);
    overflow:hidden;
}

.video-head{
    padding:18px 20px;
    border-bottom:1px solid #e2e8f0;
}

.video-head h5{
    margin:0;
    color:#14532d;
    font-weight:950;
}

.video-head small{
    display:block;
    color:#64748b;
    font-weight:700;
    margin-top:4px;
}

.video-body{
    padding:20px;
}

.video-field{
    margin-bottom:15px;
}

.video-field label{
    display:block;
    color:#334155;
    font-size:13px;
    font-weight:850;
    margin-bottom:7px;
}

.video-input,
.video-textarea{
    width:100%;
    border:1px solid #cbd5e1;
    background:#f8fafc;
    border-radius:16px;
    padding:11px 13px;
    color:#0f172a;
    font-weight:700;
    outline:none;
}

.video-input{
    min-height:46px;
}

.video-textarea{
    min-height:120px;
    resize:vertical;
    line-height:1.6;
}

.video-input:focus,
.video-textarea:focus{
    background:white;
    border-color:#22c55e;
    box-shadow:0 0 0 4px rgba(34,197,94,.12);
}

.btn-video-save{
    width:100%;
    min-height:46px;
    border:0;
    border-radius:16px;
    background:linear-gradient(135deg,#15803d,#22c55e);
    color:white;
    font-weight:950;
    box-shadow:0 12px 26px rgba(22,163,74,.22);
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

.video-actions{
    display:flex;
    gap:7px;
    flex-wrap:wrap;
}

.video-action{
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

.video-action-publish{
    background:#dcfce7;
    color:#166534;
}

.video-action-draft{
    background:#fef3c7;
    color:#92400e;
}

.video-action-delete{
    background:#fee2e2;
    color:#991b1b;
}

.video-action-publish:hover{color:#166534;}
.video-action-draft:hover{color:#92400e;}
.video-action-delete:hover{color:#991b1b;}

.dataTables_wrapper{
    padding:18px 20px 20px;
}

@media(max-width:1200px){
    .video-admin-grid{
        grid-template-columns:1fr;
    }
}

@media(max-width:768px){
    .video-admin-hero,
    .video-card{
        border-radius:20px;
    }

    .video-actions{
        display:grid;
        grid-template-columns:1fr;
    }

    .video-action{
        width:100%;
    }
}
</style>

<div class="video-admin-hero">
    <div class="d-flex justify-content-between align-items-center gap-3 flex-wrap">
        <div>
            <h2 class="glow mb-1">Video Profil</h2>
            <p>Kelola video profil madrasah dari YouTube. Hanya video status Published yang tampil di website.</p>
        </div>
        <div class="d-flex gap-2 flex-wrap">
            <a href="<?= base_url('admin_cloud_sync/sync_website') ?>" class="btn btn-outline-success fw-bold d-inline-flex align-items-center rounded-4 px-3" style="border-width:2px; font-weight:800;" onclick="return confirm('Kirim video YouTube lokal ke man3banjar.sch.id?');">
                🚀 Kirim ke Website Online
            </a>
            <a href="<?= base_url('admin_cloud_sync/pull_website') ?>" class="btn btn-outline-primary fw-bold d-inline-flex align-items-center rounded-4 px-3" style="border-width:2px; font-weight:800;" onclick="return confirm('Tarik data video YouTube terbaru dari man3banjar.sch.id ke lokal?');">
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

<div class="video-admin-grid">

    <div class="video-card">
        <div class="video-head">
            <h5>Tambah Video</h5>
            <small>Video baru akan tersimpan sebagai Draft.</small>
        </div>

        <div class="video-body">
            <form method="post" action="<?= base_url('admin_website/save_video') ?>">

                <div class="video-field">
                    <label>Judul Video</label>
                    <input type="text" name="judul" class="video-input" required>
                </div>

                <div class="video-field">
                    <label>URL YouTube</label>
                    <input type="text"
                           name="youtube_url"
                           class="video-input"
                           placeholder="https://www.youtube.com/watch?v=xxxx"
                           required>
                </div>

                <div class="video-field">
                    <label>Deskripsi</label>
                    <textarea name="deskripsi" class="video-textarea"></textarea>
                </div>

                <button class="btn-video-save">
                    Simpan Video
                </button>

            </form>
        </div>
    </div>

    <div class="video-card">
        <div class="video-head">
            <h5>Daftar Video Profil</h5>
            <small>Publish salah satu video agar tampil di halaman website.</small>
        </div>

        <div class="table-responsive">
            <table class="table table-bordered table-striped datatable nowrap" style="width:100%">
                <thead class="table-success">
                    <tr>
                        <th>Judul</th>
                        <th>Status</th>
                        <th>Tanggal</th>
                        <th style="width:220px;">Aksi</th>
                    </tr>
                </thead>

                <tbody>
                    <?php foreach($video as $v): ?>
                        <tr>
                            <td>
                                <strong><?= htmlspecialchars($v->judul, ENT_QUOTES, 'UTF-8') ?></strong>
                                <div class="text-muted small fw-bold">
                                    <?= htmlspecialchars($v->youtube_url, ENT_QUOTES, 'UTF-8') ?>
                                </div>
                            </td>

                            <td>
                                <?php if($v->status == 'Published'): ?>
                                    <span class="status-pill status-published">Published</span>
                                <?php else: ?>
                                    <span class="status-pill status-draft">Draft</span>
                                <?php endif; ?>
                            </td>

                            <td>
                                <?= !empty($v->created_at) ? date('d M Y', strtotime($v->created_at)) : '-' ?>
                            </td>

                            <td>
                                <div class="video-actions">
                                    <?php if($v->status == 'Published'): ?>
                                        <a href="<?= base_url('admin_website/draft_video/'.$v->id) ?>"
                                           class="video-action video-action-draft"
                                           onclick="return confirm('Jadikan Draft?')">
                                            Draftkan
                                        </a>
                                    <?php else: ?>
                                        <a href="<?= base_url('admin_website/publish_video/'.$v->id) ?>"
                                           class="video-action video-action-publish"
                                           onclick="return confirm('Publish video ini?')">
                                            Publish
                                        </a>
                                    <?php endif; ?>

                                    <a href="<?= base_url('admin_website/delete_video/'.$v->id) ?>"
                                       class="video-action video-action-delete"
                                       onclick="return confirm('Hapus video ini?')">
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

<?php $this->load->view('templates/footer'); ?>