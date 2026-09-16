<?php $this->load->view('templates/header'); ?>
<?php $this->load->view('templates/sidebar'); ?>

<?php
$total_semua = isset($total_semua) ? $total_semua : 0;
$total_published = isset($total_published) ? $total_published : 0;
$total_draft = isset($total_draft) ? $total_draft : 0;

$filter_status = $filter_status ?? '';
$filter_bulan = $filter_bulan ?? '';
$filter_tanggal_awal = $filter_tanggal_awal ?? '';
$filter_tanggal_akhir = $filter_tanggal_akhir ?? '';
?>

<div class="content">

<style>
.news-admin-hero{
    background:
        radial-gradient(circle at top right, rgba(34,197,94,.16), transparent 34%),
        linear-gradient(135deg,#ecfdf5,#ffffff);
    border:1px solid #dcfce7;
    border-radius:24px;
    padding:22px;
    box-shadow:0 14px 35px rgba(15,23,42,.06);
    margin-bottom:20px;
}

.news-admin-hero-row{
    display:flex;
    justify-content:space-between;
    align-items:flex-start;
    gap:14px;
    flex-wrap:wrap;
}

.news-admin-hero p{
    color:#64748b;
    font-weight:700;
    margin:5px 0 0;
}

.news-admin-btn{
    min-height:44px;
    border:0;
    border-radius:16px;
    padding:0 18px;
    background:linear-gradient(135deg,#15803d,#22c55e);
    color:white;
    font-weight:950;
    display:inline-flex;
    align-items:center;
    justify-content:center;
    text-decoration:none;
    box-shadow:0 12px 26px rgba(22,163,74,.22);
}

.news-admin-btn:hover{
    color:white;
}

.news-admin-summary{
    display:grid;
    grid-template-columns:repeat(3,1fr);
    gap:14px;
    margin-bottom:20px;
}

.news-admin-stat{
    background:#ffffff;
    border:1px solid #e2e8f0;
    border-radius:22px;
    padding:18px;
    box-shadow:0 12px 30px rgba(15,23,42,.05);
}

.news-admin-stat small{
    display:block;
    color:#64748b;
    font-size:13px;
    font-weight:800;
}

.news-admin-stat strong{
    display:block;
    color:#16a34a;
    font-size:30px;
    line-height:1;
    font-weight:950;
    margin-top:7px;
}

.news-admin-card{
    background:#ffffff;
    border:1px solid #e2e8f0;
    border-radius:24px;
    box-shadow:0 14px 35px rgba(15,23,42,.06);
    overflow:hidden;
    margin-bottom:20px;
}

.news-admin-head{
    padding:18px 20px;
    border-bottom:1px solid #e2e8f0;
    background:#ffffff;
    display:flex;
    justify-content:space-between;
    align-items:center;
    gap:12px;
    flex-wrap:wrap;
}

.news-admin-head h5{
    margin:0;
    color:#14532d;
    font-weight:950;
}

.news-admin-head small{
    display:block;
    color:#64748b;
    font-weight:700;
    margin-top:4px;
}

.news-filter-body{
    padding:18px 20px;
}

.news-filter-grid{
    display:grid;
    grid-template-columns:1fr 1fr 1fr 1fr auto auto;
    gap:12px;
    align-items:end;
}

.news-filter-field label{
    display:block;
    color:#334155;
    font-size:13px;
    font-weight:850;
    margin-bottom:7px;
}

.news-filter-input,
.news-filter-select{
    width:100%;
    min-height:44px;
    border:1px solid #cbd5e1;
    background:#f8fafc;
    border-radius:15px;
    padding:9px 12px;
    color:#0f172a;
    font-weight:700;
    outline:none;
}

.news-filter-input:focus,
.news-filter-select:focus{
    background:#ffffff;
    border-color:#22c55e;
    box-shadow:0 0 0 4px rgba(34,197,94,.12);
}

.news-filter-btn{
    min-height:44px;
    border:0;
    border-radius:15px;
    padding:0 16px;
    font-weight:950;
    white-space:nowrap;
}

.news-filter-submit{
    background:#dcfce7;
    color:#166534;
}

.news-filter-reset{
    display:inline-flex;
    align-items:center;
    justify-content:center;
    background:#f1f5f9;
    color:#334155;
    text-decoration:none;
}

.news-thumb{
    width:92px;
    height:66px;
    object-fit:cover;
    border-radius:14px;
    border:1px solid #e2e8f0;
    background:#ecfdf5;
}

.news-thumb-empty{
    width:92px;
    height:66px;
    border-radius:14px;
    border:1px solid #bbf7d0;
    background:#ecfdf5;
    color:#166534;
    display:flex;
    align-items:center;
    justify-content:center;
    font-size:11px;
    font-weight:950;
    text-align:center;
}

.news-title{
    color:#0f172a;
    font-weight:950;
    line-height:1.35;
}

.news-sub{
    margin-top:4px;
    color:#64748b;
    font-size:12px;
    font-weight:700;
}

.news-date-pill{
    display:inline-flex;
    padding:7px 11px;
    border-radius:999px;
    background:#f1f5f9;
    color:#475569;
    font-size:12px;
    font-weight:900;
    white-space:nowrap;
}

.news-status{
    display:inline-flex;
    padding:7px 11px;
    border-radius:999px;
    font-size:12px;
    font-weight:950;
    white-space:nowrap;
}

.news-status-draft{
    background:#fef3c7;
    color:#92400e;
}

.news-status-published{
    background:#dcfce7;
    color:#166534;
}

.news-actions{
    display:flex;
    gap:7px;
    flex-wrap:wrap;
}

.news-action{
    display:inline-flex;
    align-items:center;
    justify-content:center;
    min-height:36px;
    padding:0 12px;
    border-radius:13px;
    font-size:12px;
    font-weight:950;
    text-decoration:none;
    border:0;
}

.news-action-view{
    background:#e0f2fe;
    color:#075985;
}

.news-action-publish{
    background:#dcfce7;
    color:#166534;
}

.news-action-draft{
    background:#fef3c7;
    color:#92400e;
}

.news-action-delete{
    background:#fee2e2;
    color:#991b1b;
}

.news-action-view:hover{color:#075985;}
.news-action-publish:hover{color:#166534;}
.news-action-draft:hover{color:#92400e;}
.news-action-delete:hover{color:#991b1b;}

.dataTables_wrapper{
    padding:18px 20px 20px;
}

.dataTables_length label,
.dataTables_filter label,
.dataTables_info,
.dataTables_paginate{
    color:#475569;
    font-size:13px;
    font-weight:800;
}

.dataTables_filter input,
.dataTables_length select{
    border:1px solid #cbd5e1 !important;
    border-radius:12px !important;
    padding:7px 10px !important;
    background:#f8fafc !important;
    outline:none !important;
}

.dataTables_filter input:focus{
    border-color:#22c55e !important;
    box-shadow:0 0 0 4px rgba(34,197,94,.12) !important;
}

/* Modal */
.news-modal .modal-content{
    border:0;
    border-radius:26px;
    overflow:hidden;
    box-shadow:0 28px 80px rgba(15,23,42,.22);
}

.news-modal .modal-header{
    background:
        radial-gradient(circle at top right, rgba(34,197,94,.14), transparent 34%),
        #ffffff;
    border-bottom:1px solid #e2e8f0;
    padding:20px 22px;
}

.news-modal .modal-title{
    color:#14532d;
    font-weight:950;
}

.news-modal .modal-body{
    padding:22px;
}

.news-field{
    margin-bottom:15px;
}

.news-field label{
    display:block;
    color:#334155;
    font-size:13px;
    font-weight:850;
    margin-bottom:7px;
}

.news-input,
.news-textarea{
    width:100%;
    border:1px solid #cbd5e1;
    background:#f8fafc;
    border-radius:16px;
    padding:11px 13px;
    color:#0f172a;
    font-weight:700;
    outline:none;
}

.news-input{
    min-height:46px;
}

.news-textarea{
    min-height:170px;
    resize:vertical;
    line-height:1.6;
}

.news-input:focus,
.news-textarea:focus{
    background:#ffffff;
    border-color:#22c55e;
    box-shadow:0 0 0 4px rgba(34,197,94,.12);
}
.news-action-edit{
    background:#ede9fe;
    color:#5b21b6;
}

.news-action-edit:hover{
    color:#5b21b6;
}
.news-upload{
    border:1px dashed #86efac;
    background:#f0fdf4;
    border-radius:18px;
    padding:14px;
}

.news-preview{
    width:100%;
    height:210px;
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

.news-preview img{
    width:100%;
    height:100%;
    object-fit:cover;
}

.btn-news-submit{
    min-height:46px;
    border:0;
    border-radius:16px;
    padding:0 18px;
    background:linear-gradient(135deg,#15803d,#22c55e);
    color:#ffffff;
    font-weight:950;
    box-shadow:0 12px 26px rgba(22,163,74,.22);
}

.btn-news-cancel{
    min-height:46px;
    border:0;
    border-radius:16px;
    padding:0 18px;
    background:#f1f5f9;
    color:#334155;
    font-weight:950;
}

@media(max-width:1200px){
    .news-filter-grid{
        grid-template-columns:repeat(2,1fr);
    }
}

@media(max-width:768px){
    .news-admin-summary{
        grid-template-columns:1fr;
    }

    .news-admin-hero,
    .news-admin-card{
        border-radius:20px;
    }

    .news-filter-grid{
        grid-template-columns:1fr;
    }

    .news-filter-btn,
    .news-filter-reset{
        width:100%;
    }

    .dataTables_wrapper{
        padding:15px;
    }

    .news-actions{
        display:grid;
        grid-template-columns:1fr;
    }

    .news-action{
        width:100%;
    }
}
.berita-modal-dialog{
    max-width:900px;
}

.berita-modal-content{
    border:0;
    border-radius:24px;
    overflow:hidden;
    max-height:calc(100vh - 24px);
}

.berita-modal-body{
    max-height:calc(100vh - 190px);
    overflow-y:auto;
    padding:22px;
}

.berita-modal-footer{
    position:sticky;
    bottom:0;
    background:#ffffff;
    border-top:1px solid #e2e8f0;
    z-index:5;
}

.berita-modal-body::-webkit-scrollbar{
    width:8px;
}

.berita-modal-body::-webkit-scrollbar-track{
    background:#f1f5f9;
    border-radius:999px;
}

.berita-modal-body::-webkit-scrollbar-thumb{
    background:#86efac;
    border-radius:999px;
}

.modal{
    overflow-y:auto !important;
}

@media(max-width:768px){
    .berita-modal-dialog{
        margin:10px;
    }

    .berita-modal-content{
        max-height:calc(100vh - 20px);
    }

    .berita-modal-body{
        max-height:calc(100vh - 165px);
        padding:18px;
    }

    .berita-modal-footer{
        display:grid;
        grid-template-columns:1fr;
        gap:8px;
    }

    .berita-modal-footer button{
        width:100%;
    }
}
</style>

<div class="news-admin-hero">
    <div class="news-admin-hero-row">
        <div>
            <h2 class="glow mb-1">Kelola Berita</h2>
            <p>Berita baru akan tersimpan sebagai Draft. Klik Publish agar tampil di halaman depan website.</p>
        </div>

        <div class="d-flex gap-2 flex-wrap">
            <a href="<?= base_url('admin_cloud_sync/sync_berita') ?>" class="btn btn-outline-primary fw-bold d-inline-flex align-items-center rounded-4 px-3" style="border-width:2px; font-weight:800;" onclick="return confirm('Kirim seluruh berita lokal ke website online man3banjar.sch.id?');">
                🚀 Kirim ke Website Online
            </a>
            <button type="button"
                    class="news-admin-btn"
                    data-bs-toggle="modal"
                    data-bs-target="#modalTambahBerita">
                + Tambah Berita
            </button>
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

<div class="news-admin-summary">
    <div class="news-admin-stat">
        <small>Total Berita</small>
        <strong><?= (int)$total_semua ?></strong>
    </div>

    <div class="news-admin-stat">
        <small>Published</small>
        <strong><?= (int)$total_published ?></strong>
    </div>

    <div class="news-admin-stat">
        <small>Draft</small>
        <strong><?= (int)$total_draft ?></strong>
    </div>
</div>

<div class="news-admin-card">
    <div class="news-admin-head">
        <div>
            <h5>Filter Berita</h5>
            <small>Gunakan filter status, bulan, atau rentang tanggal.</small>
        </div>
    </div>

    <div class="news-filter-body">
        <form method="get" action="<?= base_url('berita') ?>">
            <div class="news-filter-grid">

                <div class="news-filter-field">
                    <label>Status</label>
                    <select name="status" class="news-filter-select">
                        <option value="">Semua Status</option>
                        <option value="Draft" <?= $filter_status == 'Draft' ? 'selected' : '' ?>>Draft</option>
                        <option value="Published" <?= $filter_status == 'Published' ? 'selected' : '' ?>>Published</option>
                    </select>
                </div>

                <div class="news-filter-field">
                    <label>Bulan</label>
                    <input type="month"
                           name="bulan"
                           class="news-filter-input"
                           value="<?= $filter_bulan ?>">
                </div>

                <div class="news-filter-field">
                    <label>Tanggal Awal</label>
                    <input type="date"
                           name="tanggal_awal"
                           class="news-filter-input"
                           value="<?= $filter_tanggal_awal ?>">
                </div>

                <div class="news-filter-field">
                    <label>Tanggal Akhir</label>
                    <input type="date"
                           name="tanggal_akhir"
                           class="news-filter-input"
                           value="<?= $filter_tanggal_akhir ?>">
                </div>

                <button class="news-filter-btn news-filter-submit">
                    Tampilkan
                </button>

                <a href="<?= base_url('berita') ?>" class="news-filter-btn news-filter-reset">
                    Reset
                </a>

            </div>
        </form>
    </div>
</div>

<div class="news-admin-card">
    <div class="news-admin-head">
        <div>
            <h5>Daftar Berita</h5>
            <small><?= count($berita) ?> data ditampilkan sesuai filter.</small>
        </div>

        <a href="<?= base_url() ?>" target="_blank" class="news-action news-action-view">
            Lihat Halaman Website
        </a>
    </div>

    <div class="table-responsive">
        <table class="table table-bordered table-striped datatable nowrap" style="width:100%">
            <thead class="table-success">
                <tr>
                    <th style="width:110px;">Gambar</th>
                    <th>Judul</th>
                    <th style="width:130px;">Status</th>
                    <th style="width:145px;">Tanggal</th>
                    <th style="width:230px;">Aksi</th>
                </tr>
            </thead>

            <tbody>
                <?php if(!empty($berita)): ?>
                    <?php foreach($berita as $b): ?>
                        <?php
                        $judul = htmlspecialchars($b->judul ?? '', ENT_QUOTES, 'UTF-8');
                        $gambar = $b->gambar ?? '';
                        $gambar_file = FCPATH.'assets/news/'.$gambar;
                        $tanggal = !empty($b->created_at) ? date('d M Y', strtotime($b->created_at)) : '-';
                        $status = !empty($b->status_berita) ? $b->status_berita : 'Draft';
                        ?>

                        <tr>
                            <td>
                                <?php if(!empty($gambar) && file_exists($gambar_file)): ?>
                                    <img src="<?= base_url('assets/news/'.$gambar) ?>"
                                         class="news-thumb"
                                         alt="<?= $judul ?>">
                                <?php else: ?>
                                    <div class="news-thumb-empty">
                                        Tanpa Gambar
                                    </div>
                                <?php endif; ?>
                            </td>

                            <td>
                                <div class="news-title"><?= $judul ?></div>
                                <div class="news-sub">
                                    ID Berita: <?= $b->id ?>
                                    <?php if(!empty($b->published_at)): ?>
                                        • Publish: <?= date('d M Y H:i', strtotime($b->published_at)) ?>
                                    <?php endif; ?>
                                </div>
                            </td>

                            <td>
                                <?php if($status == 'Published'): ?>
                                    <span class="news-status news-status-published">Published</span>
                                <?php else: ?>
                                    <span class="news-status news-status-draft">Draft</span>
                                <?php endif; ?>
                            </td>

                            <td>
                                <span class="news-date-pill"><?= $tanggal ?></span>
                            </td>

                            <td>
                                <div class="news-actions">
									<a href="<?= base_url('berita/detail/'.$b->id) ?>"
									   target="_blank"
									   class="news-action news-action-view">
										Lihat
									</a>

									<a href="<?= base_url('berita/edit/'.$b->id) ?>"
									   class="news-action news-action-edit">
										Edit
									</a>
									<a href="<?= base_url('berita/regenerate_pamflet/'.$b->id) ?>"
									   class="btn btn-warning btn-sm"
									   onclick="return confirm('Buat ulang pamflet berita ini?')">
										Generate Pamflet
									</a>

									<a href="<?= base_url('berita/download_pamflet/'.$b->id) ?>"
									   class="btn btn-success btn-sm">
										Download JPG
									</a>

									<?php if(!empty($b->poster_gambar)): ?>
										<a href="<?= base_url('assets/news/poster/'.$b->poster_gambar) ?>"
										   target="_blank"
										   class="btn btn-info btn-sm">
											Preview
										</a>
									<?php endif; ?>

									<a href="https://wa.me/?text=<?= urlencode($b->judul.' - '.base_url('berita/detail/'.$b->id)) ?>"
									   target="_blank"
									   class="btn btn-primary btn-sm">
										Share WA
									</a>
									<?php if($status == 'Published'): ?>
										<a href="<?= base_url('berita/draft/'.$b->id) ?>"
										   class="news-action news-action-draft"
										   onclick="return confirm('Kembalikan berita ini menjadi Draft?')">
											Draftkan
										</a>
									<?php else: ?>
										<a href="<?= base_url('berita/publish/'.$b->id) ?>"
										   class="news-action news-action-publish"
										   onclick="return confirm('Publish berita ini ke halaman depan?')">
											Publish
										</a>
									<?php endif; ?>

									<a href="<?= base_url('berita/delete/'.$b->id) ?>"
									   class="news-action news-action-delete"
									   onclick="return confirm('Hapus berita ini?')">
										Hapus
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

<div class="modal fade" id="modalTambahBerita" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable berita-modal-dialog">
        <div class="modal-content berita-modal-content">

            <form method="post"
                  action="<?= base_url('berita/add') ?>"
                  enctype="multipart/form-data">

                <div class="modal-header">
                    <div>
                        <h5 class="modal-title">Tambah Berita Baru</h5>
                        <small class="text-muted fw-bold">
                            Berita akan disimpan sebagai Draft terlebih dahulu.
                        </small>
                    </div>

                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body berita-modal-body">

                    <div class="news-field">
                        <label>Judul Berita</label>
                        <input type="text"
                               name="judul"
                               class="news-input"
                               placeholder="Contoh: Prestasi Siswa MAN 3 Banjar"
                               required>
                    </div>

                    <div class="news-field">
                        <label>Isi Berita</label>
                        <textarea name="isi"
                                  class="news-textarea"
                                  placeholder="Tulis isi berita..."
                                  required></textarea>
                    </div>
					<!---<div class="mb-3">
						<label class="form-label fw-bold">Gambar Utama</label>
						<input type="file" name="gambar" class="form-control" accept="image/*">
						<small class="text-muted">
							Gambar utama dipakai untuk thumbnail berita.
						</small>
					</div>-->
					<div class="news-field">
                        <label>Gambar Berita</label>

                        <div class="news-upload">
                            <div class="news-preview" id="previewBerita">
                                Preview Gambar
                            </div>

                            <input type="file"
                                   name="gambar"
                                   class="form-control"
                                   id="gambarBerita"
                                   accept="image/*">
                        </div>
                    </div>
					<div class="mb-3">
						<label class="form-label fw-bold">Gambar Kegiatan Tambahan</label>
						<input type="file" name="gambar_multi[]" class="form-control" accept="image/*" multiple>
						<small class="text-muted">
							Bisa upload beberapa foto kegiatan. Foto-foto ini akan dipakai untuk galeri berita dan pamflet.
						</small>
					</div>
                   

                </div>

                <div class="modal-footer berita-modal-footer">
                    <button type="button" class="btn-news-cancel" data-bs-dismiss="modal">
                        Batal
                    </button>

                    <button class="btn-news-submit">
                        Simpan Draft
                    </button>
                </div>

            </form>

        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function(){

    const inputGambar = document.getElementById('gambarBerita');
    const preview = document.getElementById('previewBerita');

    if(inputGambar && preview){
        inputGambar.addEventListener('change', function(){
            const file = this.files[0];

            if(!file){
                preview.innerHTML = 'Preview Gambar';
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