<?php $this->load->view('templates/header'); ?>
<?php $this->load->view('templates/sidebar'); ?>

<div class="content">

<style>
.download-admin-hero{
    background:
        radial-gradient(circle at top right, rgba(16,185,129,.16), transparent 34%),
        linear-gradient(135deg,#ecfdf5,#ffffff);
    border:1px solid #dcfce7;
    border-radius:24px;
    padding:22px;
    box-shadow:0 14px 35px rgba(15,23,42,.06);
    margin-bottom:20px;
}

.download-admin-hero p{
    color:#64748b;
    font-weight:700;
    margin:5px 0 0;
}

.download-admin-grid{
    display:grid;
    grid-template-columns:390px 1fr;
    gap:18px;
    align-items:start;
}

.download-card{
    background:#fff;
    border:1px solid #e2e8f0;
    border-radius:24px;
    box-shadow:0 14px 35px rgba(15,23,42,.06);
    overflow:hidden;
}

.download-head{
    padding:18px 20px;
    border-bottom:1px solid #e2e8f0;
}

.download-head h5{
    margin:0;
    color:#14532d;
    font-weight:950;
}

.download-head small{
    display:block;
    color:#64748b;
    font-weight:700;
    margin-top:4px;
}

.download-body{
    padding:20px;
}

.download-field{
    margin-bottom:15px;
}

.download-field label{
    display:block;
    color:#334155;
    font-size:13px;
    font-weight:850;
    margin-bottom:7px;
}

.download-input,
.download-textarea{
    width:100%;
    border:1px solid #cbd5e1;
    background:#f8fafc;
    border-radius:16px;
    padding:11px 13px;
    color:#0f172a;
    font-weight:700;
    outline:none;
}

.download-input{
    min-height:46px;
}

.download-textarea{
    min-height:100px;
    resize:vertical;
    line-height:1.6;
}

.download-input:focus,
.download-textarea:focus{
    background:white;
    border-color:#10b981;
    box-shadow:0 0 0 4px rgba(16,185,129,.12);
}

.btn-download-save{
    width:100%;
    min-height:46px;
    border:0;
    border-radius:16px;
    background:linear-gradient(135deg,#059669,#10b981);
    color:white;
    font-weight:950;
    box-shadow:0 12px 26px rgba(16,185,129,.22);
}

.download-actions{
    display:flex;
    gap:7px;
    flex-wrap:wrap;
}

.download-action{
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

.download-action-view{
    background:#e0f2fe;
    color:#075985;
}

.download-action-delete{
    background:#fee2e2;
    color:#991b1b;
}

.download-action-view:hover{color:#075985;}
.download-action-delete:hover{color:#991b1b;}

.dataTables_wrapper{
    padding:18px 20px 20px;
}

@media(max-width:1200px){
    .download-admin-grid{
        grid-template-columns:1fr;
    }
}

@media(max-width:768px){
    .download-admin-hero,
    .download-card{
        border-radius:20px;
    }
}
</style>

<div class="download-admin-hero">
    <div class="d-flex justify-content-between align-items-center gap-3 flex-wrap">
        <div>
            <h2 class="glow mb-1">Data Download File</h2>
            <p>Kelola file dokumen, formulir, dan format yang dapat diunduh oleh pengunjung madrasah.</p>
        </div>
        <div class="d-flex gap-2 flex-wrap">
            <a href="<?= base_url('admin_cloud_sync/sync_website') ?>" class="btn btn-outline-success fw-bold d-inline-flex align-items-center rounded-4 px-3" style="border-width:2px; font-weight:800;" onclick="return confirm('Kirim berkas unduhan lokal ke man3banjar.sch.id?');">
                🚀 Kirim ke Website Online
            </a>
            <a href="<?= base_url('admin_cloud_sync/pull_website') ?>" class="btn btn-outline-primary fw-bold d-inline-flex align-items-center rounded-4 px-3" style="border-width:2px; font-weight:800;" onclick="return confirm('Tarik data berkas unduhan terbaru dari man3banjar.sch.id ke lokal?');">
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

<div class="download-admin-grid">

    <div class="download-card">
        <div class="download-head">
            <h5>Upload File Baru</h5>
            <small>File yang diupload akan otomatis tampil di halaman publik.</small>
        </div>

        <div class="download-body">
            <form method="post"
                  action="<?= base_url('admin_website/save_download') ?>"
                  enctype="multipart/form-data">

                <div class="download-field">
                    <label>Judul Dokumen</label>
                    <input type="text" name="judul" class="download-input" placeholder="Misal: Formulir Pendaftaran PPDB" required>
                </div>

                <div class="download-field">
                    <label>Tanggal Dokumen</label>
                    <input type="date" name="tanggal" class="download-input" value="<?= date('Y-m-d') ?>">
                </div>

                <div class="download-field">
                    <label>Keterangan Singkat</label>
                    <textarea name="keterangan" class="download-textarea" placeholder="Deskripsi dokumen (opsional)"></textarea>
                </div>

                <div class="download-field">
                    <label>File (PDF/DOC/XLS/ZIP)</label>
                    <input type="file"
                           name="file_download"
                           class="form-control download-input"
                           style="padding-top: 10px;"
                           accept=".pdf,.doc,.docx,.xls,.xlsx,.ppt,.pptx,.zip,.rar"
                           required>
                    <small class="text-muted d-block mt-2" style="font-size: 11px;">Maksimal ukuran file 10 MB.</small>
                </div>

                <button class="btn-download-save mt-2">
                    <i class="bi bi-cloud-arrow-up"></i> Upload Dokumen
                </button>

            </form>
        </div>
    </div>

    <div class="download-card">
        <div class="download-head">
            <h5>Daftar File Download</h5>
            <small>Daftar seluruh file yang tersedia untuk diunduh.</small>
        </div>

        <div class="table-responsive">
            <table class="table table-bordered table-striped datatable nowrap" style="width:100%">
                <thead class="table-success">
                    <tr>
                        <th style="width:50px;">No</th>
                        <th>Judul Dokumen</th>
                        <th>File</th>
                        <th>Tanggal</th>
                        <th style="width:140px;">Aksi</th>
                    </tr>
                </thead>

                <tbody>
                    <?php $no=1; foreach($downloads as $d): ?>
                        <tr>
                            <td><?= $no++ ?></td>
                            <td>
                                <strong><?= htmlspecialchars($d->judul, ENT_QUOTES, 'UTF-8') ?></strong>
                                <div class="text-muted small fw-bold mt-1">
                                    <?= htmlspecialchars($d->keterangan ?? '', ENT_QUOTES, 'UTF-8') ?>
                                </div>
                            </td>
                            <td>
                                <a href="<?= base_url('assets/downloads/'.$d->file_path) ?>" target="_blank" class="text-decoration-none fw-bold" style="color: #059669;">
                                    <i class="bi bi-file-earmark-arrow-down"></i> <?= $d->file_path ?>
                                </a>
                            </td>
                            <td>
                                <?= !empty($d->tanggal) ? date('d M Y', strtotime($d->tanggal)) : '-' ?>
                            </td>
                            <td>
                                <div class="download-actions">
                                    <a href="<?= base_url('admin_website/delete_download/'.$d->id) ?>"
                                       class="download-action download-action-delete"
                                       onclick="return confirm('Hapus file ini?')">
                                        <i class="bi bi-trash me-1"></i> Hapus
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
