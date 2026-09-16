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
        <?php if($this->session->flashdata('info')): ?>
            <div class="alert alert-info alert-dismissible fade show rounded-4 border-0 shadow-sm mb-4" role="alert">
                <i class="bi bi-info-circle-fill me-2 fs-5"></i>
                <?= $this->session->flashdata('info') ?>
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
                            <i class="bi bi-diagram-3-fill me-1"></i> STRUKTUR &amp; SUSUNAN ORGANISASI
                        </span>
                        <h2 class="fw-bold mb-2 text-white">Struktur Organisasi Madrasah</h2>
                        <p class="mb-3 text-white-50" style="font-size: 14px; max-width: 620px;">
                            Kelola susunan pimpinan kepala madrasah, wakil kepala, dewan guru, wali kelas, staf tata usaha, dan koordinator ekstrakurikuler yang tampil di website.
                        </p>
                        <div class="d-flex flex-wrap gap-2 pt-1">
                            <button type="button" class="btn btn-light text-dark fw-bold rounded-pill px-3 shadow-sm" data-bs-toggle="modal" data-bs-target="#modalTambah">
                                <i class="bi bi-plus-circle-fill text-success me-1"></i> Tambah Manual
                            </button>
                            <a href="<?= base_url('admin_struktur/sync/'.$kategori_slug) ?>" class="btn btn-outline-light fw-bold rounded-pill px-3 shadow-sm" onclick="return confirm('Sistem akan mencari data PTK yang jabatannya cocok dan memasukkannya otomatis. Lanjutkan?');">
                                <i class="bi bi-arrow-repeat me-1"></i> Sinkronisasi PTK Otomatis
                            </a>
                            <a href="<?= base_url('struktur') ?>" target="_blank" class="btn btn-outline-light fw-bold rounded-pill px-3 shadow-sm">
                                <i class="bi bi-box-arrow-up-right me-1"></i> Halaman Publik
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Hiasan Bulat Transparan -->
            <div style="position: absolute; right: -40px; top: -40px; width: 180px; height: 180px; background: radial-gradient(circle, rgba(255,255,255,0.2) 0%, transparent 70%); border-radius: 50%;"></div>
        </div>

        <div class="row g-4">
            <!-- Sidebar Kategori Navigasi Pills -->
            <div class="col-lg-3 mb-4">
                <div class="card border-0 rounded-4 shadow-sm p-3">
                    <div class="px-2 pb-2 mb-2 border-bottom">
                        <span class="fw-bold small text-muted text-uppercase" style="letter-spacing:0.5px;">Kategori Organisasi</span>
                    </div>
                    <div class="nav flex-column gap-1">
                        <?php foreach($kategori_options as $slug => $label): ?>
                            <?php 
                            $icon = 'bi-people-fill';
                            if($slug == 'kepala-madrasah') $icon = 'bi-person-badge-fill';
                            if($slug == 'wakamad') $icon = 'bi-shield-shaded';
                            if($slug == 'guru') $icon = 'bi-mortarboard-fill';
                            if($slug == 'wali-kelas') $icon = 'bi-card-list';
                            if($slug == 'tata-usaha') $icon = 'bi-laptop-fill';
                            if($slug == 'koordinator-eskul') $icon = 'bi-trophy-fill';
                            $isActive = ($slug == $kategori_slug);
                            ?>
                            <a class="nav-link rounded-pill px-3 py-2 fw-bold d-flex align-items-center gap-2 <?= $isActive ? 'active bg-success text-white shadow-sm' : 'text-dark bg-light' ?>" 
                               href="<?= base_url('admin_struktur/index/'.$slug) ?>"
                               style="font-size: 13px;">
                                <i class="bi <?= $icon ?> <?= $isActive ? 'text-white' : 'text-success' ?>"></i>
                                <span><?= htmlspecialchars($label, ENT_QUOTES, 'UTF-8') ?></span>
                            </a>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>

            <!-- Tabel Anggota Struktur -->
            <div class="col-lg-9">
                <div class="card border-0 rounded-4 shadow-sm mb-4">
                    <div class="card-header bg-white border-bottom pt-3 pb-2 px-4 d-flex justify-content-between align-items-center flex-wrap gap-2">
                        <div>
                            <h6 class="fw-bold mb-0 text-dark"><i class="bi bi-diagram-3 text-success me-2"></i> Daftar <?= htmlspecialchars($kategori, ENT_QUOTES, 'UTF-8') ?></h6>
                        </div>
                        <button type="button" class="btn btn-success btn-sm fw-bold rounded-pill px-3 shadow-sm" data-bs-toggle="modal" data-bs-target="#modalTambah">
                            <i class="bi bi-plus-lg me-1"></i> Tambah Anggota
                        </button>
                    </div>

                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0" style="width:100%">
                                <thead class="bg-light">
                                    <tr>
                                        <th style="width:80px;" class="ps-4 text-center">Urutan</th>
                                        <th>Nama Pegawai &amp; NIP</th>
                                        <th>Jabatan Publikasi</th>
                                        <th style="width:120px;" class="text-end pe-4">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if(empty($struktur)): ?>
                                        <tr>
                                            <td colspan="4" class="text-center py-5 text-muted">
                                                <i class="bi bi-diagram-3 fs-1 text-secondary mb-2 d-block opacity-50"></i>
                                                <div class="fw-bold">Belum ada data pada kategori ini.</div>
                                                <small class="text-secondary d-block mt-1">Gunakan tombol <strong>Sinkronisasi PTK Otomatis</strong> atau tambah secara manual.</small>
                                            </td>
                                        </tr>
                                    <?php else: ?>
                                        <?php foreach($struktur as $s): ?>
                                            <tr>
                                                <td class="ps-4 text-center">
                                                    <span class="badge bg-light text-dark border rounded-pill px-3 py-1 fw-bold">#<?= (int)$s->urutan ?></span>
                                                </td>
                                                <td>
                                                    <div class="fw-bold text-dark" style="font-size:14px;"><?= htmlspecialchars($s->nama_lengkap, ENT_QUOTES, 'UTF-8') ?></div>
                                                    <div class="small text-muted">NIP: <?= htmlspecialchars($s->nip, ENT_QUOTES, 'UTF-8') ?: '-' ?></div>
                                                </td>
                                                <td>
                                                    <span class="badge bg-success-subtle text-success fw-bold rounded-pill px-3 py-1">
                                                        <i class="bi bi-tag-fill me-1"></i> <?= htmlspecialchars($s->jabatan, ENT_QUOTES, 'UTF-8') ?>
                                                    </span>
                                                </td>
                                                <td class="text-end pe-4">
                                                    <div class="d-inline-flex gap-1">
                                                        <button type="button" class="btn btn-sm btn-light rounded-pill px-2 py-1 text-primary shadow-sm action-btn-edit" 
                                                                data-id="<?= $s->id ?>" 
                                                                data-ptk="<?= $s->ptk_id ?>" 
                                                                data-jabatan="<?= htmlspecialchars($s->jabatan, ENT_QUOTES, 'UTF-8') ?>" 
                                                                data-urutan="<?= $s->urutan ?>" 
                                                                title="Edit">
                                                            <i class="bi bi-pencil-fill"></i>
                                                        </button>
                                                        <a href="<?= base_url('admin_struktur/delete/'.$s->id.'/'.$kategori_slug) ?>" 
                                                           class="btn btn-sm btn-light rounded-pill px-2 py-1 text-danger shadow-sm" 
                                                           onclick="return confirm('Hapus data ini dari struktur?')" 
                                                           title="Hapus">
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

<!-- Modal Tambah -->
<div class="modal fade" id="modalTambah" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 rounded-4 shadow-lg overflow-hidden">
            <form action="<?= base_url('admin_struktur/add') ?>" method="post">
                <input type="hidden" name="kategori" value="<?= htmlspecialchars($kategori, ENT_QUOTES, 'UTF-8') ?>">
                <input type="hidden" name="kategori_slug" value="<?= $kategori_slug ?>">

                <div class="modal-header px-4 pt-4 pb-3 border-0 bg-light">
                    <div>
                        <h5 class="modal-title fw-bold text-dark mb-1">Tambah ke <?= htmlspecialchars($kategori, ENT_QUOTES, 'UTF-8') ?></h5>
                        <p class="text-muted small mb-0">Pilih personil PTK dan tentukan jabatan yang tampil.</p>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body px-4 py-3">
                    <div class="mb-3">
                        <label class="form-label fw-bold small text-muted">Pilih Pegawai (PTK)</label>
                        <select name="ptk_id" class="form-select rounded-3" required>
                            <option value="">-- Pilih PTK --</option>
                            <?php foreach($list_ptk as $ptk): ?>
                                <option value="<?= $ptk->id ?>"><?= htmlspecialchars($ptk->nama_lengkap, ENT_QUOTES, 'UTF-8') ?> (<?= $ptk->nip ?: 'NIP: -' ?>)</option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold small text-muted">Jabatan Publikasi</label>
                        <input type="text" name="jabatan" class="form-control rounded-3" placeholder="Contoh: Kaur Tata Usaha / Koordinator..." required>
                    </div>

                    <div class="mb-2">
                        <label class="form-label fw-bold small text-muted">Nomor Urutan Tampil</label>
                        <input type="number" name="urutan" class="form-control rounded-3" value="1" min="1">
                        <small class="text-muted d-block mt-1">Angka lebih kecil tampil di urutan atas.</small>
                    </div>
                </div>

                <div class="modal-footer px-4 pb-4 pt-2 border-0 bg-light d-flex justify-content-end gap-2">
                    <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-success rounded-pill px-4 fw-bold shadow-sm">
                        <i class="bi bi-save me-1"></i> Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Edit -->
<div class="modal fade" id="modalEdit" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 rounded-4 shadow-lg overflow-hidden">
            <form action="<?= base_url('admin_struktur/update') ?>" method="post">
                <input type="hidden" name="kategori" value="<?= htmlspecialchars($kategori, ENT_QUOTES, 'UTF-8') ?>">
                <input type="hidden" name="kategori_slug" value="<?= $kategori_slug ?>">
                <input type="hidden" name="id" id="edit_id">

                <div class="modal-header px-4 pt-4 pb-3 border-0 bg-light">
                    <div>
                        <h5 class="modal-title fw-bold text-dark mb-1">Edit Posisi Struktur</h5>
                        <p class="text-muted small mb-0">Ubah personil, teks jabatan, atau nomor urutan.</p>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body px-4 py-3">
                    <div class="mb-3">
                        <label class="form-label fw-bold small text-muted">Pilih Pegawai (PTK)</label>
                        <select name="ptk_id" id="edit_ptk_id" class="form-select rounded-3" required>
                            <?php foreach($list_ptk as $ptk): ?>
                                <option value="<?= $ptk->id ?>"><?= htmlspecialchars($ptk->nama_lengkap, ENT_QUOTES, 'UTF-8') ?> (<?= $ptk->nip ?: 'NIP: -' ?>)</option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold small text-muted">Jabatan Publikasi</label>
                        <input type="text" name="jabatan" id="edit_jabatan" class="form-control rounded-3" required>
                    </div>

                    <div class="mb-2">
                        <label class="form-label fw-bold small text-muted">Nomor Urutan Tampil</label>
                        <input type="number" name="urutan" id="edit_urutan" class="form-control rounded-3" min="1" required>
                    </div>
                </div>

                <div class="modal-footer px-4 pb-4 pt-2 border-0 bg-light d-flex justify-content-end gap-2">
                    <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-success rounded-pill px-4 fw-bold shadow-sm">
                        <i class="bi bi-save me-1"></i> Update Data
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const editBtns = document.querySelectorAll('.action-btn-edit');
    editBtns.forEach(btn => {
        btn.addEventListener('click', function() {
            document.getElementById('edit_id').value = this.dataset.id;
            document.getElementById('edit_ptk_id').value = this.dataset.ptk;
            document.getElementById('edit_jabatan').value = this.dataset.jabatan;
            document.getElementById('edit_urutan').value = this.dataset.urutan;
            var myModal = new bootstrap.Modal(document.getElementById('modalEdit'));
            myModal.show();
        });
    });
});
</script>

<?php $this->load->view('templates/footer'); ?>
