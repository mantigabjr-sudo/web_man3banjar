<?php $this->load->view('templates/header'); ?>
<?php $this->load->view('templates/sidebar'); ?>

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
                            <i class="bi bi-images me-1"></i> MEDIA BERANDA WEBSITE
                        </span>
                        <h2 class="fw-bold mb-2 text-white">Banner Slider Beranda</h2>
                        <p class="mb-3 text-white-50" style="font-size: 14px; max-width: 620px;">
                            Kelola gambar carousel sorotan utama di halaman muka website madrasah. Rekomendasi resolusi gambar adalah <strong>1920 &times; 800 piksel</strong> untuk hasil maksimal di seluruh perangkat.
                        </p>
                        <div class="d-flex flex-wrap gap-2 pt-1">
                            <a href="<?= base_url() ?>" target="_blank" class="btn btn-light text-dark fw-bold rounded-pill px-3 shadow-sm">
                                <i class="bi bi-box-arrow-up-right text-success me-1"></i> Lihat Beranda Website
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Hiasan Bulat Transparan -->
            <div style="position: absolute; right: -40px; top: -40px; width: 180px; height: 180px; background: radial-gradient(circle, rgba(255,255,255,0.2) 0%, transparent 70%); border-radius: 50%;"></div>
        </div>

        <div class="row g-4">
            <!-- Form Tambah Banner -->
            <div class="col-lg-4">
                <div class="card border-0 rounded-4 shadow-sm mb-4">
                    <div class="card-header bg-white border-bottom pt-3 pb-2 px-4">
                        <h6 class="fw-bold mb-0 text-dark"><i class="bi bi-cloud-arrow-up-fill text-success me-2"></i> Tambah Banner Baru</h6>
                    </div>
                    <div class="card-body p-4">
                        <form method="post" action="<?= base_url('admin_banner/save') ?>" enctype="multipart/form-data">
                            <div class="mb-3">
                                <label class="form-label fw-bold small text-muted">Judul Banner <span class="text-danger">*</span></label>
                                <input type="text" name="judul" class="form-control rounded-3" placeholder="Contoh: Selamat Datang di MAN 3 Banjar" required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-bold small text-muted">Subjudul (Opsional)</label>
                                <input type="text" name="subjudul" class="form-control rounded-3" placeholder="Contoh: Madrasah Berkarakter Islami & Berprestasi">
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-bold small text-muted">Deskripsi Singkat (Opsional)</label>
                                <textarea name="deskripsi" class="form-control rounded-3" rows="2" placeholder="Teks penjelasan singkat banner..."></textarea>
                            </div>

                            <div class="row g-2 mb-3">
                                <div class="col-6">
                                    <label class="form-label fw-bold small text-muted">Teks Tombol</label>
                                    <input type="text" name="button_text" class="form-control rounded-3" placeholder="Contoh: Selengkapnya">
                                </div>
                                <div class="col-6">
                                    <label class="form-label fw-bold small text-muted">Tautan Tombol</label>
                                    <input type="text" name="button_url" class="form-control rounded-3" placeholder="https://...">
                                </div>
                            </div>

                            <div class="row g-2 mb-3">
                                <div class="col-6">
                                    <label class="form-label fw-bold small text-muted">No. Urut</label>
                                    <input type="number" name="urutan" class="form-control rounded-3" value="1" min="1">
                                </div>
                                <div class="col-6">
                                    <label class="form-label fw-bold small text-muted">Status</label>
                                    <select name="status" class="form-select rounded-3">
                                        <option value="Published">Published (Tampil)</option>
                                        <option value="Draft">Draft (Disimpan)</option>
                                    </select>
                                </div>
                            </div>

                            <div class="mb-4">
                                <label class="form-label fw-bold small text-muted">Unggah File Gambar <span class="text-danger">*</span></label>
                                <input type="file" name="gambar" class="form-control rounded-3 mb-2" id="inputGambarBanner" accept="image/*" required>
                                
                                <div class="p-2 border rounded-3 bg-light text-center" id="previewBox" style="min-height: 120px; display: flex; align-items: center; justify-content: center; color: #94a3b8; font-size: 12px;">
                                    Pratinjau Gambar Banner
                                </div>
                            </div>

                            <button type="submit" class="btn btn-success fw-bold rounded-pill w-100 py-2 shadow-sm">
                                <i class="bi bi-cloud-arrow-up-fill me-1"></i> Simpan Banner
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Tabel Daftar Banner -->
            <div class="col-lg-8">
                <div class="card border-0 rounded-4 shadow-sm mb-4">
                    <div class="card-header bg-white border-bottom pt-3 pb-2 px-4 d-flex justify-content-between align-items-center">
                        <h6 class="fw-bold mb-0 text-dark"><i class="bi bi-images text-success me-2"></i> Daftar Banner Slider Tersimpan</h6>
                        <span class="badge bg-success-subtle text-success rounded-pill fw-bold px-3 py-1"><?= count($banner ?? []) ?> Banner</span>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0" style="width:100%">
                                <thead class="bg-light">
                                    <tr>
                                        <th style="width:110px;" class="ps-4">Preview</th>
                                        <th>Judul &amp; Informasi</th>
                                        <th style="width:80px;" class="text-center">Urutan</th>
                                        <th style="width:110px;" class="text-center">Status</th>
                                        <th style="width:140px;" class="text-end pe-4">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if(empty($banner)): ?>
                                        <tr>
                                            <td colspan="5" class="text-center py-5 text-muted">
                                                <i class="bi bi-images fs-1 text-secondary mb-2 d-block opacity-50"></i>
                                                Belum ada banner slider yang diunggah.
                                            </td>
                                        </tr>
                                    <?php else: ?>
                                        <?php foreach($banner as $b): ?>
                                            <?php
                                            $img_file = FCPATH.'assets/banner/'.$b->gambar;
                                            $img_url = base_url('assets/banner/'.$b->gambar);
                                            ?>
                                            <tr>
                                                <td class="ps-4">
                                                    <a href="<?= $img_url ?>" target="_blank">
                                                        <img src="<?= $img_url ?>" 
                                                             alt="Banner" 
                                                             class="rounded-3 shadow-sm"
                                                             style="width:90px; height:50px; object-fit:cover; border:1px solid #e2e8f0;">
                                                    </a>
                                                </td>
                                                <td>
                                                    <div class="fw-bold text-dark" style="font-size:14px;"><?= htmlspecialchars($b->judul ?? '-', ENT_QUOTES, 'UTF-8') ?></div>
                                                    <?php if(!empty($b->subjudul)): ?>
                                                        <div class="small text-success fw-semibold"><?= htmlspecialchars($b->subjudul, ENT_QUOTES, 'UTF-8') ?></div>
                                                    <?php endif; ?>
                                                    <?php if(!empty($b->deskripsi)): ?>
                                                        <div class="small text-muted text-truncate" style="max-width: 260px;"><?= htmlspecialchars($b->deskripsi, ENT_QUOTES, 'UTF-8') ?></div>
                                                    <?php endif; ?>
                                                    <?php if(!empty($b->button_text)): ?>
                                                        <span class="badge bg-light text-secondary border rounded-pill mt-1" style="font-size:10.5px;">
                                                            <i class="bi bi-link-45deg me-1"></i><?= htmlspecialchars($b->button_text, ENT_QUOTES, 'UTF-8') ?>
                                                        </span>
                                                    <?php endif; ?>
                                                </td>
                                                <td class="text-center">
                                                    <span class="badge bg-light text-dark border rounded-pill px-3 py-1 fw-bold">#<?= (int)$b->urutan ?></span>
                                                </td>
                                                <td class="text-center">
                                                    <?php if($b->status === 'Published'): ?>
                                                        <span class="badge bg-success-subtle text-success fw-bold rounded-pill px-3 py-1">
                                                            <i class="bi bi-check-circle-fill me-1"></i> Published
                                                        </span>
                                                    <?php else: ?>
                                                        <span class="badge bg-secondary-subtle text-secondary fw-bold rounded-pill px-3 py-1">
                                                            <i class="bi bi-clock me-1"></i> Draft
                                                        </span>
                                                    <?php endif; ?>
                                                </td>
                                                <td class="text-end pe-4">
                                                    <div class="d-inline-flex gap-1">
                                                        <?php if($b->status === 'Published'): ?>
                                                            <a href="<?= base_url('admin_banner/draft/'.$b->id) ?>" 
                                                               class="btn btn-sm btn-light rounded-pill px-2 py-1 text-warning shadow-sm"
                                                               title="Jadikan Draft (Sembunyikan dari beranda)">
                                                                <i class="bi bi-eye-slash-fill"></i>
                                                            </a>
                                                        <?php else: ?>
                                                            <a href="<?= base_url('admin_banner/publish/'.$b->id) ?>" 
                                                               class="btn btn-sm btn-light rounded-pill px-2 py-1 text-success shadow-sm"
                                                               title="Terbitkan ke Beranda Website">
                                                                <i class="bi bi-eye-fill"></i>
                                                            </a>
                                                        <?php endif; ?>

                                                        <button type="button" 
                                                                class="btn btn-sm btn-light rounded-pill px-2 py-1 text-primary shadow-sm btn-edit-banner"
                                                                data-id="<?= $b->id ?>"
                                                                data-judul="<?= htmlspecialchars($b->judul ?? '', ENT_QUOTES, 'UTF-8') ?>"
                                                                data-subjudul="<?= htmlspecialchars($b->subjudul ?? '', ENT_QUOTES, 'UTF-8') ?>"
                                                                data-deskripsi="<?= htmlspecialchars($b->deskripsi ?? '', ENT_QUOTES, 'UTF-8') ?>"
                                                                data-btntext="<?= htmlspecialchars($b->button_text ?? '', ENT_QUOTES, 'UTF-8') ?>"
                                                                data-btnurl="<?= htmlspecialchars($b->button_url ?? '', ENT_QUOTES, 'UTF-8') ?>"
                                                                data-urutan="<?= $b->urutan ?>"
                                                                data-status="<?= $b->status ?>"
                                                                title="Edit Banner">
                                                            <i class="bi bi-pencil-fill"></i>
                                                        </button>
                                                        <a href="<?= base_url('admin_banner/delete/'.$b->id) ?>" 
                                                            class="btn btn-sm btn-light rounded-pill px-2 py-1 text-danger shadow-sm"
                                                            onclick="return confirm('Hapus banner ini dari slider?')"
                                                            title="Hapus Banner">
                                                            <i class="bi bi-trash-fill"></i>
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
            </div>
        </div>

    </div>
</div>

<!-- Modal Edit Banner -->
<div class="modal fade" id="modalEditBanner" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 rounded-4 shadow-lg overflow-hidden">
            <form method="post" id="formEditBanner" action="" enctype="multipart/form-data">
                <input type="hidden" name="id" id="edit_id">

                <div class="modal-header px-4 pt-4 pb-3 border-0 bg-light">
                    <div>
                        <h5 class="modal-title fw-bold text-dark mb-1">Edit Banner Slider</h5>
                        <p class="text-muted small mb-0">Ubah judul, teks tombol, urutan, atau ganti file gambar.</p>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body px-4 py-3">
                    <div class="mb-3">
                        <label class="form-label fw-bold small text-muted">Judul Banner <span class="text-danger">*</span></label>
                        <input type="text" name="judul" id="edit_judul" class="form-control rounded-3" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold small text-muted">Subjudul</label>
                        <input type="text" name="subjudul" id="edit_subjudul" class="form-control rounded-3">
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold small text-muted">Deskripsi Singkat</label>
                        <textarea name="deskripsi" id="edit_deskripsi" class="form-control rounded-3" rows="2"></textarea>
                    </div>

                    <div class="row g-2 mb-3">
                        <div class="col-6">
                            <label class="form-label fw-bold small text-muted">Teks Tombol</label>
                            <input type="text" name="button_text" id="edit_btntext" class="form-control rounded-3">
                        </div>
                        <div class="col-6">
                            <label class="form-label fw-bold small text-muted">Tautan Tombol</label>
                            <input type="text" name="button_url" id="edit_btnurl" class="form-control rounded-3">
                        </div>
                    </div>

                    <div class="row g-2 mb-3">
                        <div class="col-6">
                            <label class="form-label fw-bold small text-muted">No. Urut</label>
                            <input type="number" name="urutan" id="edit_urutan" class="form-control rounded-3" min="1">
                        </div>
                        <div class="col-6">
                            <label class="form-label fw-bold small text-muted">Status</label>
                            <select name="status" id="edit_status" class="form-select rounded-3">
                                <option value="Published">Published (Tampil)</option>
                                <option value="Draft">Draft (Disimpan)</option>
                            </select>
                        </div>
                    </div>

                    <div class="mb-2">
                        <label class="form-label fw-bold small text-muted">Ganti Gambar (Kosongkan jika tidak diganti)</label>
                        <input type="file" name="gambar" class="form-control rounded-3" accept="image/*">
                    </div>
                </div>

                <div class="modal-footer px-4 pb-4 pt-2 border-0 bg-light d-flex justify-content-end gap-2">
                    <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-success rounded-pill px-4 fw-bold shadow-sm">
                        <i class="bi bi-check-circle-fill me-1"></i> Update Banner
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function(){
    const inputGambar = document.getElementById('inputGambarBanner');
    const previewBox = document.getElementById('previewBox');

    if(inputGambar && previewBox){
        inputGambar.addEventListener('change', function(){
            const file = this.files[0];
            if(!file){
                previewBox.innerHTML = 'Pratinjau Gambar Banner';
                return;
            }
            const reader = new FileReader();
            reader.onload = function(e){
                previewBox.innerHTML = '<img src="'+e.target.result+'" style="max-width:100%; max-height:160px; border-radius:10px; object-fit:cover;">';
            };
            reader.readAsDataURL(file);
        });
    }

    const editBtns = document.querySelectorAll('.btn-edit-banner');
    editBtns.forEach(btn => {
        btn.addEventListener('click', function(){
            const id = this.dataset.id;
            document.getElementById('edit_id').value = id;
            document.getElementById('edit_judul').value = this.dataset.judul;
            document.getElementById('edit_subjudul').value = this.dataset.subjudul;
            document.getElementById('edit_deskripsi').value = this.dataset.deskripsi;
            document.getElementById('edit_btntext').value = this.dataset.btntext;
            document.getElementById('edit_btnurl').value = this.dataset.btnurl;
            document.getElementById('edit_urutan').value = this.dataset.urutan;
            document.getElementById('edit_status').value = this.dataset.status;

            document.getElementById('formEditBanner').action = '<?= base_url('admin_banner/update/') ?>' + id;

            var modal = new bootstrap.Modal(document.getElementById('modalEditBanner'));
            modal.show();
        });
    });
});
</script>

<?php $this->load->view('templates/footer'); ?>
