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

    <div class="page-header">
        <div class="page-title-group">
            <span class="page-category">Kelola Website</span>
            <h1 class="page-title">Kelola Berita & Artikel</h1>
            <p class="page-subtitle">Publikasikan warta kegiatan, prestasi siswa, dan pengumuman resmi madrasah.</p>
        </div>
        <div class="header-actions d-flex gap-2">
            <a href="<?= base_url('admin_cloud_sync/sync_berita') ?>" 
               class="btn-modern btn-modern-light text-primary" 
               onclick="return confirm('Kirim seluruh berita lokal ke website online man3banjar.sch.id?');">
                <i class="fa fa-cloud-upload-alt me-1"></i> Sinkronisasi Cloud
            </a>
            <button type="button"
                    class="btn-modern btn-modern-primary"
                    data-bs-toggle="modal"
                    data-bs-target="#modalTambahBerita">
                <i class="fa fa-plus me-1"></i> Tulis Berita Baru
            </button>
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

    <!-- Metrics Stat Strip -->
    <div class="row g-3 mb-4">
        <div class="col-md-4">
            <div class="stat-card">
                <div class="d-flex justify-content-between align-items-center">
                    <span class="stat-title">Total Seluruh Berita</span>
                    <div class="stat-icon-wrapper" style="background:#eff6ff; color:#2563eb;">
                        <i class="fa fa-newspaper"></i>
                    </div>
                </div>
                <div class="stat-value"><?= (int)$total_semua ?></div>
                <div class="stat-desc text-muted">Artikel terdaftar di sistem</div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="stat-card">
                <div class="d-flex justify-content-between align-items-center">
                    <span class="stat-title">Telah Diterbitkan (Published)</span>
                    <div class="stat-icon-wrapper" style="background:#ecfdf5; color:#059669;">
                        <i class="fa fa-check-double"></i>
                    </div>
                </div>
                <div class="stat-value text-success"><?= (int)$total_published ?></div>
                <div class="stat-desc text-muted">Aktif tampil di website publik</div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="stat-card">
                <div class="d-flex justify-content-between align-items-center">
                    <span class="stat-title">Draf (Menunggu Publish)</span>
                    <div class="stat-icon-wrapper" style="background:#fffbeb; color:#d97706;">
                        <i class="fa fa-pencil-alt"></i>
                    </div>
                </div>
                <div class="stat-value text-warning"><?= (int)$total_draft ?></div>
                <div class="stat-desc text-muted">Belum tayang ke umum</div>
            </div>
        </div>
    </div>

    <!-- Filter Bar Card -->
    <div class="modern-card mb-4">
        <div class="modern-card-body p-3">
            <form method="get" action="<?= base_url('berita') ?>" class="row g-2 align-items-end">
                <div class="col-lg-3 col-md-6">
                    <label class="form-label small fw-bold text-muted mb-1">Status Publikasi</label>
                    <select name="status" class="input-modern py-1 px-2" style="font-size:13px;">
                        <option value="">Semua Status</option>
                        <option value="Draft" <?= $filter_status == 'Draft' ? 'selected' : '' ?>>Draft Saja</option>
                        <option value="Published" <?= $filter_status == 'Published' ? 'selected' : '' ?>>Published Saja</option>
                    </select>
                </div>

                <div class="col-lg-2 col-md-6">
                    <label class="form-label small fw-bold text-muted mb-1">Pilih Bulan</label>
                    <input type="month"
                           name="bulan"
                           class="input-modern py-1 px-2"
                           style="font-size:13px;"
                           value="<?= $filter_bulan ?>">
                </div>

                <div class="col-lg-2 col-md-6">
                    <label class="form-label small fw-bold text-muted mb-1">Dari Tanggal</label>
                    <input type="date"
                           name="tanggal_awal"
                           class="input-modern py-1 px-2"
                           style="font-size:13px;"
                           value="<?= $filter_tanggal_awal ?>">
                </div>

                <div class="col-lg-2 col-md-6">
                    <label class="form-label small fw-bold text-muted mb-1">Sampai Tanggal</label>
                    <input type="date"
                           name="tanggal_akhir"
                           class="input-modern py-1 px-2"
                           style="font-size:13px;"
                           value="<?= $filter_tanggal_akhir ?>">
                </div>

                <div class="col-lg-3 col-md-12 d-flex gap-2">
                    <button type="submit" class="btn-modern btn-modern-primary flex-fill py-2" style="font-size:13px;">
                        <i class="fa fa-filter me-1"></i> Filter
                    </button>
                    <a href="<?= base_url('berita') ?>" class="btn-modern btn-modern-light py-2 px-3 text-muted" style="font-size:13px;" title="Reset Filter">
                        <i class="fa fa-undo"></i>
                    </a>
                </div>
            </form>
        </div>
    </div>

    <!-- Data Table Card -->
    <div class="modern-card">
        <div class="modern-card-header d-flex flex-wrap justify-content-between align-items-center gap-3">
            <div>
                <h2 class="modern-card-title">Daftar Berita</h2>
                <p class="modern-card-subtitle">Menampilkan <?= count($berita) ?> berita sesuai kriteria pencarian.</p>
            </div>
            <a href="<?= base_url() ?>" target="_blank" class="btn-modern btn-modern-light text-decoration-none">
                <i class="fa fa-external-link-alt me-1"></i> Lihat Tampilan Depan
            </a>
        </div>

        <div class="modern-card-body p-0">
            <div class="table-responsive">
                <table class="table table-modern datatable align-middle mb-0" style="width:100%">
                    <thead>
                        <tr>
                            <th style="width:90px;" class="ps-4">Sampul</th>
                            <th>Judul & Info Berita</th>
                            <th style="width:120px;">Status</th>
                            <th style="width:130px;">Tanggal</th>
                            <th style="width:240px;" class="text-end pe-4">Aksi</th>
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
                                    <td class="ps-4">
                                        <?php if(!empty($gambar) && file_exists($gambar_file)): ?>
                                            <img src="<?= base_url('assets/news/'.$gambar) ?>"
                                                 alt="<?= $judul ?>"
                                                 style="width:70px; height:50px; object-fit:cover; border-radius:10px; border:1px solid #e2e8f0;">
                                        <?php else: ?>
                                            <div style="width:70px; height:50px; border-radius:10px; background:#f8fafc; border:1px dashed #cbd5e1; display:flex; align-items:center; justify-content:center; color:#94a3b8; font-size:10px; text-align:center;">
                                                No Cover
                                            </div>
                                        <?php endif; ?>
                                    </td>

                                    <td>
                                        <div style="font-weight:700; color:#0f172a; font-size:14px; line-height:1.4;">
                                            <a href="<?= base_url('berita/edit/'.$b->id) ?>" class="text-dark text-decoration-none">
                                                <?= $judul ?>
                                            </a>
                                        </div>
                                        <div style="font-size:12px; color:#64748b; margin-top:3px;">
                                            <span class="badge bg-light text-secondary border me-1">#<?= $b->id ?></span>
                                            <?php if(!empty($b->kategori)): ?>
                                                <span class="badge bg-light text-primary border me-1"><?= htmlspecialchars($b->kategori, ENT_QUOTES, 'UTF-8') ?></span>
                                            <?php endif; ?>
                                            <?php if(!empty($b->published_at)): ?>
                                                <span class="text-muted"><i class="fa fa-clock me-1"></i><?= date('d/m/Y H:i', strtotime($b->published_at)) ?></span>
                                            <?php endif; ?>
                                        </div>
                                    </td>

                                    <td>
                                        <?php if($status == 'Published'): ?>
                                            <span class="pill-status pill-success">
                                                <i class="fa fa-check-circle me-1" style="font-size:10px;"></i> Published
                                            </span>
                                        <?php else: ?>
                                            <span class="pill-status pill-warning">
                                                <i class="fa fa-pencil-alt me-1" style="font-size:10px;"></i> Draft
                                            </span>
                                        <?php endif; ?>
                                    </td>

                                    <td>
                                        <div style="font-size:13px; font-weight:600; color:#475569;">
                                            <?= $tanggal ?>
                                        </div>
                                    </td>

                                    <td class="text-end pe-4">
                                        <div class="d-inline-flex gap-1 align-items-center">
                                            <!-- Tombol Edit Utama -->
                                            <a href="<?= base_url('berita/edit/'.$b->id) ?>"
                                               class="btn-modern btn-modern-light py-1 px-2 text-decoration-none"
                                               style="font-size:12px;"
                                               title="Edit Berita">
                                                <i class="fa fa-pencil-alt text-primary"></i> Edit
                                            </a>

                                            <!-- Tombol Lihat -->
                                            <a href="<?= base_url('berita/detail/'.$b->id) ?>"
                                               target="_blank"
                                               class="btn-modern btn-modern-light py-1 px-2 text-decoration-none"
                                               style="font-size:12px;"
                                               title="Buka Halaman Berita">
                                                <i class="fa fa-external-link-alt text-secondary"></i>
                                            </a>

                                            <!-- Toggle Status Publish / Draft -->
                                            <?php if($status == 'Published'): ?>
                                                <a href="<?= base_url('berita/draft/'.$b->id) ?>"
                                                   class="btn-modern btn-modern-light py-1 px-2 text-warning text-decoration-none"
                                                   style="font-size:12px;"
                                                   onclick="return confirm('Kembalikan berita ini menjadi status Draft?')"
                                                   title="Jadikan Draft">
                                                    <i class="fa fa-undo"></i>
                                                </a>
                                            <?php else: ?>
                                                <a href="<?= base_url('berita/publish/'.$b->id) ?>"
                                                   class="btn-modern btn-modern-light py-1 px-2 text-success text-decoration-none"
                                                   style="font-size:12px;"
                                                   onclick="return confirm('Publikasikan berita ini sekarang?')"
                                                   title="Publish ke Publik">
                                                    <i class="fa fa-paper-plane"></i>
                                                </a>
                                            <?php endif; ?>

                                            <!-- Dropdown Aksi Lainnya (Pamflet, Share, Hapus) -->
                                            <div class="dropdown d-inline-block">
                                                <button class="btn-modern btn-modern-light py-1 px-2" 
                                                        type="button" 
                                                        data-bs-toggle="dropdown" 
                                                        aria-expanded="false"
                                                        style="font-size:12px;">
                                                    <i class="fa fa-ellipsis-v"></i>
                                                </button>
                                                <ul class="dropdown-menu dropdown-menu-end shadow-sm border-0 rounded-3 p-2" style="min-width: 190px;">
                                                    <li>
                                                        <a class="dropdown-item py-2 rounded-2 small fw-bold text-success" href="<?= base_url('berita/regenerate_pamflet/'.$b->id) ?>" onclick="return confirm('Buat ulang pamflet poster berita ini?')">
                                                            <i class="fa fa-magic me-2"></i> Generate Pamflet
                                                        </a>
                                                    </li>
                                                    <li>
                                                        <a class="dropdown-item py-2 rounded-2 small fw-bold" href="<?= base_url('berita/download_pamflet/'.$b->id) ?>">
                                                            <i class="fa fa-download me-2 text-primary"></i> Download Poster JPG
                                                        </a>
                                                    </li>
                                                    <?php if(!empty($b->poster_gambar)): ?>
                                                        <li>
                                                            <a class="dropdown-item py-2 rounded-2 small fw-bold" href="<?= base_url('assets/news/poster/'.$b->poster_gambar) ?>" target="_blank">
                                                                <i class="fa fa-image me-2 text-info"></i> Pratinjau Poster
                                                            </a>
                                                        </li>
                                                    <?php endif; ?>
                                                    <li>
                                                        <a class="dropdown-item py-2 rounded-2 small fw-bold" href="https://wa.me/?text=<?= urlencode($b->judul.' - '.base_url('berita/detail/'.$b->id)) ?>" target="_blank">
                                                            <i class="fab fa-whatsapp me-2 text-success"></i> Bagikan ke WhatsApp
                                                        </a>
                                                    </li>
                                                    <li><hr class="dropdown-divider my-1"></li>
                                                    <li>
                                                        <a class="dropdown-item py-2 rounded-2 small fw-bold text-danger" href="<?= base_url('berita/delete/'.$b->id) ?>" onclick="return confirm('Apakah Anda yakin ingin menghapus berita ini secara permanen?')">
                                                            <i class="fa fa-trash me-2"></i> Hapus Berita
                                                        </a>
                                                    </li>
                                                </ul>
                                            </div>
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

</div>

<!-- Modal Tambah Berita Modern -->
<div class="modal fade" id="modalTambahBerita" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content border-0 shadow-lg" style="border-radius:24px; overflow:hidden;">

            <form method="post" action="<?= base_url('berita/add') ?>" enctype="multipart/form-data">

                <div class="modal-header px-4 pt-4 pb-3 border-0 bg-light">
                    <div>
                        <h5 class="modal-title fw-bold text-dark mb-1">Tulis Berita Baru</h5>
                        <p class="text-muted small mb-0">Berita akan disimpan terlebih dahulu sebagai <strong>Draft</strong>.</p>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body px-4 py-3">
                    <div class="mb-3">
                        <label class="form-label fw-bold small text-muted">Judul Berita</label>
                        <input type="text"
                               name="judul"
                               class="input-modern"
                               placeholder="Contoh: Tim Robotik MAN 3 Banjar Raih Medali Emas..."
                               required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold small text-muted">Isi Berita</label>
                        <textarea name="isi"
                                  class="input-modern"
                                  rows="8"
                                  placeholder="Tuliskan isi artikel berita secara lengkap..."
                                  style="line-height:1.7;"
                                  required></textarea>
                    </div>

                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label class="form-label fw-bold small text-muted">Foto Sampul Utama</label>
                            <input type="file"
                                   name="gambar"
                                   class="input-modern py-2"
                                   id="gambarBerita"
                                   accept="image/*">
                            <div class="form-text small mt-1">Foto utama untuk thumbnail artikel.</div>

                            <div class="mt-2 text-center p-2 border rounded-3 bg-light" id="previewBerita" style="min-height:100px; display:flex; align-items:center; justify-content:center; color:#94a3b8; font-size:12px;">
                                Pratinjau Sampul
                            </div>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-bold small text-muted">Foto Kegiatan Tambahan (Opsional)</label>
                            <input type="file" 
                                   name="gambar_multi[]" 
                                   class="input-modern py-2" 
                                   accept="image/*" 
                                   multiple>
                            <div class="form-text small mt-1">Dapat memilih lebih dari 1 foto sekaligus untuk galeri berita & bahan pamflet.</div>
                        </div>
                    </div>
                </div>

                <div class="modal-footer px-4 pb-4 pt-2 border-0 bg-light d-flex justify-content-end gap-2">
                    <button type="button" class="btn-modern btn-modern-light" data-bs-dismiss="modal">
                        Batal
                    </button>
                    <button type="submit" class="btn-modern btn-modern-primary">
                        <i class="fa fa-save me-1"></i> Simpan Draft Berita
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
                preview.innerHTML = 'Pratinjau Sampul';
                return;
            }
            const reader = new FileReader();
            reader.onload = function(e){
                preview.innerHTML = '<img src="'+e.target.result+'" alt="Preview" style="max-height:120px; border-radius:8px; object-fit:cover;">';
            };
            reader.readAsDataURL(file);
        });
    }
});
</script>

<?php $this->load->view('templates/footer'); ?>