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
                            <i class="bi bi-newspaper me-1"></i> WARTA &amp; INFORMASI PUBLIK
                        </span>
                        <h2 class="fw-bold mb-2 text-white">Kelola Berita &amp; Artikel Madrasah</h2>
                        <p class="mb-3 text-white-50" style="font-size: 14px; max-width: 620px;">
                            Tulis berita kegiatan, pengumuman, prestasi siswa, dan publikasikan ke portal website. Berita baru tersimpan sebagai <strong>Draft</strong> hingga Anda memutuskan untuk menerbitkannya.
                        </p>
                        <div class="d-flex flex-wrap gap-2 pt-1">
                            <button type="button" class="btn btn-light text-dark fw-bold rounded-pill px-3 shadow-sm" data-bs-toggle="modal" data-bs-target="#modalTambahBerita">
                                <i class="bi bi-plus-circle-fill text-success me-1"></i> Tulis Berita Baru
                            </button>
                            <a href="<?= base_url('website/berita') ?>" target="_blank" class="btn btn-outline-light fw-bold rounded-pill px-3 shadow-sm">
                                <i class="bi bi-box-arrow-up-right me-1"></i> Lihat Berita Publik
                            </a>
                            <a href="<?= base_url() ?>" target="_blank" class="btn btn-outline-light fw-bold rounded-pill px-3 shadow-sm">
                                <i class="bi bi-globe me-1"></i> Halaman Depan
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Hiasan Bulat Transparan -->
            <div style="position: absolute; right: -40px; top: -40px; width: 180px; height: 180px; background: radial-gradient(circle, rgba(255,255,255,0.2) 0%, transparent 70%); border-radius: 50%;"></div>
        </div>

        <!-- ═══ STATISTIK RINGKASAN (BORDER-START 4PX) ═══ -->
        <div class="row g-3 mb-4">
            <div class="col-md-4">
                <div class="card border-0 rounded-4 shadow-sm h-100 p-3 bg-white border-start border-4 border-primary">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <span class="text-muted small fw-bold text-uppercase">Total Seluruh Berita</span>
                            <h3 class="fw-bold text-dark mb-0 mt-1"><?= (int)$total_semua ?></h3>
                            <small class="text-muted">Artikel terdaftar di database</small>
                        </div>
                        <div class="p-3 bg-primary-subtle text-primary rounded-4 fs-3">
                            <i class="bi bi-journal-text"></i>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card border-0 rounded-4 shadow-sm h-100 p-3 bg-white border-start border-4 border-success">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <span class="text-muted small fw-bold text-uppercase">Telah Diterbitkan</span>
                            <h3 class="fw-bold text-success mb-0 mt-1"><?= (int)$total_published ?></h3>
                            <small class="text-success fw-semibold">Aktif tampil di website publik</small>
                        </div>
                        <div class="p-3 bg-success-subtle text-success rounded-4 fs-3">
                            <i class="bi bi-check-circle-fill"></i>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card border-0 rounded-4 shadow-sm h-100 p-3 bg-white border-start border-4 border-warning">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <span class="text-muted small fw-bold text-uppercase">Draf (Belum Tayang)</span>
                            <h3 class="fw-bold text-warning mb-0 mt-1"><?= (int)$total_draft ?></h3>
                            <small class="text-muted">Siap untuk dipublikasikan</small>
                        </div>
                        <div class="p-3 bg-warning-subtle text-warning rounded-4 fs-3">
                            <i class="bi bi-pencil-square"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- ═══ FILTER DATA ═══ -->
        <div class="card border-0 rounded-4 shadow-sm mb-4">
            <div class="card-body p-3">
                <form method="get" action="<?= base_url('berita') ?>" class="row g-2 align-items-end">
                    <div class="col-lg-3 col-md-6">
                        <label class="form-label small fw-bold text-muted mb-1">Status Publikasi</label>
                        <select name="status" class="form-select form-select-sm rounded-3">
                            <option value="">Semua Status</option>
                            <option value="Draft" <?= $filter_status == 'Draft' ? 'selected' : '' ?>>Draft</option>
                            <option value="Published" <?= $filter_status == 'Published' ? 'selected' : '' ?>>Published</option>
                        </select>
                    </div>

                    <div class="col-lg-2 col-md-6">
                        <label class="form-label small fw-bold text-muted mb-1">Pilih Bulan</label>
                        <input type="month" name="bulan" class="form-control form-control-sm rounded-3" value="<?= $filter_bulan ?>">
                    </div>

                    <div class="col-lg-2 col-md-6">
                        <label class="form-label small fw-bold text-muted mb-1">Dari Tanggal</label>
                        <input type="date" name="tanggal_awal" class="form-control form-control-sm rounded-3" value="<?= $filter_tanggal_awal ?>">
                    </div>

                    <div class="col-lg-2 col-md-6">
                        <label class="form-label small fw-bold text-muted mb-1">Sampai Tanggal</label>
                        <input type="date" name="tanggal_akhir" class="form-control form-control-sm rounded-3" value="<?= $filter_tanggal_akhir ?>">
                    </div>

                    <div class="col-lg-3 col-md-12 d-flex gap-2">
                        <button type="submit" class="btn btn-success btn-sm fw-bold rounded-pill flex-fill py-2 shadow-sm">
                            <i class="bi bi-funnel-fill me-1"></i> Terapkan Filter
                        </button>
                        <a href="<?= base_url('berita') ?>" class="btn btn-light btn-sm fw-bold rounded-pill py-2 px-3 border shadow-sm text-muted">
                            <i class="bi bi-arrow-counterclockwise"></i> Reset
                        </a>
                    </div>
                </form>
            </div>
        </div>

        <!-- ═══ TABEL BERITA ═══ -->
        <div class="card border-0 rounded-4 shadow-sm mb-4">
            <div class="card-header bg-white border-bottom pt-3 pb-2 px-4 d-flex justify-content-between align-items-center">
                <h6 class="fw-bold mb-0 text-dark"><i class="bi bi-newspaper text-success me-2"></i> Daftar Berita (<?= count($berita ?? []) ?> Artikel)</h6>
                <button type="button" class="btn btn-success btn-sm fw-bold rounded-pill px-3 shadow-sm" data-bs-toggle="modal" data-bs-target="#modalTambahBerita">
                    <i class="bi bi-plus-lg me-1"></i> Tambah Berita
                </button>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle datatable mb-0" style="width:100%">
                        <thead class="bg-light">
                            <tr>
                                <th style="width:100px;" class="ps-4">Sampul</th>
                                <th>Judul &amp; Informasi</th>
                                <th style="width:120px;" class="text-center">Status</th>
                                <th style="width:130px;">Tanggal</th>
                                <th style="width:220px;" class="text-end pe-4">Aksi</th>
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
                                                     class="rounded-3 shadow-sm"
                                                     style="width:80px; height:56px; object-fit:cover; border:1px solid #e2e8f0;">
                                            <?php else: ?>
                                                <div class="rounded-3 bg-light border d-flex align-items-center justify-content-center text-muted small" style="width:80px; height:56px; font-size:10px;">
                                                    No Cover
                                                </div>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <div class="fw-bold text-dark" style="font-size:14.5px; line-height:1.35;">
                                                <a href="<?= base_url('berita/edit/'.$b->id) ?>" class="text-dark text-decoration-none">
                                                    <?= $judul ?>
                                                </a>
                                            </div>
                                            <div class="small text-muted mt-1">
                                                <span class="badge bg-light text-secondary border rounded-pill me-1">#<?= $b->id ?></span>
                                                <?php if(!empty($b->kategori)): ?>
                                                    <span class="badge bg-success-subtle text-success rounded-pill me-1"><?= htmlspecialchars($b->kategori, ENT_QUOTES, 'UTF-8') ?></span>
                                                <?php endif; ?>
                                                <?php if(!empty($b->published_at)): ?>
                                                    <span><i class="bi bi-clock me-1"></i><?= date('d/m/Y H:i', strtotime($b->published_at)) ?></span>
                                                <?php endif; ?>
                                            </div>
                                        </td>
                                        <td class="text-center">
                                            <?php if($status == 'Published'): ?>
                                                <span class="badge bg-success-subtle text-success fw-bold rounded-pill px-3 py-1">Published</span>
                                            <?php else: ?>
                                                <span class="badge bg-warning-subtle text-warning fw-bold rounded-pill px-3 py-1">Draft</span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <span class="small text-muted fw-semibold"><?= $tanggal ?></span>
                                        </td>
                                        <td class="text-end pe-4">
                                            <div class="d-inline-flex gap-1 align-items-center">
                                                <a href="<?= base_url('berita/edit/'.$b->id) ?>" class="btn btn-sm btn-light rounded-pill px-2 py-1 text-primary shadow-sm" title="Edit">
                                                    <i class="bi bi-pencil-fill me-1"></i> Edit
                                                </a>

                                                <a href="<?= base_url('berita/detail/'.$b->id) ?>" target="_blank" class="btn btn-sm btn-light rounded-pill px-2 py-1 text-secondary shadow-sm" title="Lihat">
                                                    <i class="bi bi-box-arrow-up-right"></i>
                                                </a>

                                                <?php if($status == 'Published'): ?>
                                                    <a href="<?= base_url('berita/draft/'.$b->id) ?>" class="btn btn-sm btn-light rounded-pill px-2 py-1 text-warning shadow-sm" onclick="return confirm('Kembalikan ke Draft?')" title="Kembalikan ke Draft">
                                                        <i class="bi bi-arrow-counterclockwise"></i>
                                                    </a>
                                                <?php else: ?>
                                                    <a href="<?= base_url('berita/publish/'.$b->id) ?>" class="btn btn-sm btn-light rounded-pill px-2 py-1 text-success shadow-sm" onclick="return confirm('Terbitkan berita ini?')" title="Publish">
                                                        <i class="bi bi-send-fill"></i>
                                                    </a>
                                                <?php endif; ?>

                                                <div class="dropdown d-inline-block">
                                                    <button class="btn btn-sm btn-light rounded-pill px-2 py-1 shadow-sm" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                                        <i class="bi bi-three-dots-vertical"></i>
                                                    </button>
                                                    <ul class="dropdown-menu dropdown-menu-end shadow-lg border-0 rounded-4 p-2" style="min-width: 190px;">
                                                        <li>
                                                            <a class="dropdown-item rounded-3 py-2 small fw-bold text-success" href="<?= base_url('berita/regenerate_pamflet/'.$b->id) ?>" onclick="return confirm('Buat ulang pamflet poster berita ini?')">
                                                                <i class="bi bi-magic me-2"></i> Generate Pamflet
                                                            </a>
                                                        </li>
                                                        <li>
                                                            <a class="dropdown-item rounded-3 py-2 small fw-bold" href="<?= base_url('berita/download_pamflet/'.$b->id) ?>">
                                                                <i class="bi bi-download me-2 text-primary"></i> Download Poster JPG
                                                            </a>
                                                        </li>
                                                        <?php if(!empty($b->poster_gambar)): ?>
                                                            <li>
                                                                <a class="dropdown-item rounded-3 py-2 small fw-bold" href="<?= base_url('assets/news/poster/'.$b->poster_gambar) ?>" target="_blank">
                                                                    <i class="bi bi-image me-2 text-info"></i> Pratinjau Poster
                                                                </a>
                                                            </li>
                                                        <?php endif; ?>
                                                        <li>
                                                            <a class="dropdown-item rounded-3 py-2 small fw-bold text-success" href="https://wa.me/?text=<?= urlencode($b->judul.' - '.base_url('berita/detail/'.$b->id)) ?>" target="_blank">
                                                                <i class="bi bi-whatsapp me-2"></i> Bagikan ke WA
                                                            </a>
                                                        </li>
                                                        <li><hr class="dropdown-divider my-1"></li>
                                                        <li>
                                                            <a class="dropdown-item rounded-3 py-2 small fw-bold text-danger" href="<?= base_url('berita/delete/'.$b->id) ?>" onclick="return confirm('Hapus berita ini permanen?')">
                                                                <i class="bi bi-trash-fill me-2"></i> Hapus Berita
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
</div>

<!-- Modal Tambah Berita -->
<div class="modal fade" id="modalTambahBerita" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content border-0 rounded-4 shadow-lg overflow-hidden">
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
                        <input type="text" name="judul" class="form-control rounded-3" placeholder="Contoh: Prestasi Siswa MAN 3 Banjar di KSM..." required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold small text-muted">Isi Berita</label>
                        <textarea name="isi" class="form-control rounded-3" rows="8" placeholder="Tulis naskah berita lengkap..." style="line-height:1.7;" required></textarea>
                    </div>

                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label class="form-label fw-bold small text-muted">Foto Sampul Utama</label>
                            <input type="file" name="gambar" class="form-control rounded-3 mb-2" id="gambarBerita" accept="image/*">
                            <div class="p-2 border rounded-3 bg-light text-center" id="previewBerita" style="min-height:90px; display:flex; align-items:center; justify-content:center; color:#94a3b8; font-size:12px;">
                                Pratinjau Sampul
                            </div>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-bold small text-muted">Foto Kegiatan Tambahan (Multi)</label>
                            <input type="file" name="gambar_multi[]" class="form-control rounded-3" accept="image/*" multiple>
                            <small class="text-muted d-block mt-1" style="font-size:11px;">Bisa pilih beberapa foto sekaligus untuk bahan galeri &amp; poster pamflet.</small>
                        </div>
                    </div>
                </div>

                <div class="modal-footer px-4 pb-4 pt-2 border-0 bg-light d-flex justify-content-end gap-2">
                    <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-success rounded-pill px-4 fw-bold shadow-sm">
                        <i class="bi bi-save me-1"></i> Simpan Draft Berita
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function(){
    const inputG = document.getElementById('gambarBerita');
    const previewB = document.getElementById('previewBerita');

    if(inputG && previewB){
        inputG.addEventListener('change', function(){
            const file = this.files[0];
            if(!file){
                previewB.innerHTML = 'Pratinjau Sampul';
                return;
            }
            const reader = new FileReader();
            reader.onload = function(e){
                previewB.innerHTML = '<img src="'+e.target.result+'" style="max-height:100px; border-radius:8px; object-fit:cover;">';
            };
            reader.readAsDataURL(file);
        });
    }
});
</script>

<?php $this->load->view('templates/footer'); ?>