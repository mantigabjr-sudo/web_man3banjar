<?php $this->load->view('templates/header', ['title' => 'Struktur Organisasi']); ?>
<?php $this->load->view('templates/sidebar'); ?>

<div class="content">

    <div class="page-header">
        <div class="page-title-group">
            <span class="page-category">Kelola Website</span>
            <h1 class="page-title">Struktur Organisasi</h1>
            <p class="page-subtitle">Kelola susunan pimpinan madrasah, wakamad, dewan guru, wali kelas, staf TU, dan pembina eskul.</p>
        </div>
        <div class="header-actions">
            <a href="<?= base_url('admin_struktur/sync/'.$kategori_slug) ?>" 
               class="btn-modern btn-modern-light text-primary" 
               onclick="return confirm('Sistem akan mencari data PTK yang jabatannya cocok dan memasukkannya ke kategori ini secara otomatis. Lanjutkan?');">
                <i class="fa fa-sync-alt me-1"></i> Sinkronisasi PTK Otomatis
            </a>
            <button type="button" class="btn-modern btn-modern-primary" data-bs-toggle="modal" data-bs-target="#modalTambah">
                <i class="fa fa-plus me-1"></i> Tambah Manual
            </button>
        </div>
    </div>

    <?php if($this->session->flashdata('success')): ?>
        <div class="alert alert-success d-flex align-items-center mb-4" role="alert">
            <i class="fa fa-check-circle me-2 fs-5"></i>
            <div><?= $this->session->flashdata('success') ?></div>
        </div>
    <?php endif; ?>
    <?php if($this->session->flashdata('info')): ?>
        <div class="alert alert-info d-flex align-items-center mb-4" role="alert">
            <i class="fa fa-info-circle me-2 fs-5"></i>
            <div><?= $this->session->flashdata('info') ?></div>
        </div>
    <?php endif; ?>
    <?php if($this->session->flashdata('error')): ?>
        <div class="alert alert-danger d-flex align-items-center mb-4" role="alert">
            <i class="fa fa-exclamation-circle me-2 fs-5"></i>
            <div><?= $this->session->flashdata('error') ?></div>
        </div>
    <?php endif; ?>

    <div class="row g-4">
        <!-- Sidebar Kategori Organisasi -->
        <div class="col-lg-3">
            <div class="modern-card p-2">
                <div class="p-2 border-bottom mb-2">
                    <span class="fw-bold small text-muted text-uppercase" style="letter-spacing:0.5px;">Kategori Peran</span>
                </div>
                <div class="nav flex-column gap-1">
                    <?php foreach($kategori_options as $slug => $label): ?>
                        <?php 
                        $icon = 'fa-users';
                        if($slug == 'kepala-madrasah') $icon = 'fa-user-tie';
                        if($slug == 'wakamad') $icon = 'fa-user-shield';
                        if($slug == 'guru') $icon = 'fa-chalkboard-teacher';
                        if($slug == 'wali-kelas') $icon = 'fa-id-card-alt';
                        if($slug == 'tata-usaha') $icon = 'fa-laptop-house';
                        if($slug == 'koordinator-eskul') $icon = 'fa-volleyball-ball';
                        $isActive = ($slug == $kategori_slug);
                        ?>
                        <a class="d-flex align-items-center px-3 py-2 text-decoration-none rounded-3 <?= $isActive ? 'bg-primary text-white fw-bold shadow-sm' : 'text-dark hover-bg-light' ?>" 
                           href="<?= base_url('admin_struktur/index/'.$slug) ?>"
                           style="<?= $isActive ? 'background: #0f172a !important;' : 'background: transparent;' ?>">
                            <i class="fas <?= $icon ?> me-3 text-center" style="width: 20px; font-size: 14px; <?= $isActive ? 'color:#fff;' : 'color:#64748b;' ?>"></i>
                            <span style="font-size: 13px;"><?= htmlspecialchars($label, ENT_QUOTES, 'UTF-8') ?></span>
                        </a>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>

        <!-- Tabel Daftar Pejabat / Anggota -->
        <div class="col-lg-9">
            <div class="modern-card">
                <div class="modern-card-header d-flex justify-content-between align-items-center flex-wrap gap-2">
                    <div>
                        <h2 class="modern-card-title"><?= htmlspecialchars($kategori, ENT_QUOTES, 'UTF-8') ?></h2>
                        <p class="modern-card-subtitle">Daftar personil dan posisi pada kategori ini.</p>
                    </div>
                    <button type="button" class="btn-modern btn-modern-primary py-2 px-3" data-bs-toggle="modal" data-bs-target="#modalTambah" style="font-size:13px;">
                        <i class="fa fa-plus me-1"></i> Tambah Anggota
                    </button>
                </div>

                <div class="modern-card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-modern align-middle mb-0" style="width:100%">
                            <thead>
                                <tr>
                                    <th style="width:70px;" class="ps-4 text-center">Urutan</th>
                                    <th>Nama Pegawai & NIP</th>
                                    <th>Jabatan Publikasi</th>
                                    <th style="width:120px;" class="text-end pe-4">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if(empty($struktur)): ?>
                                    <tr>
                                        <td colspan="4" class="text-center py-5 text-muted">
                                            <i class="fas fa-sitemap fs-1 text-secondary mb-2 d-block opacity-50"></i>
                                            <div class="fw-bold">Belum ada data struktur pada kategori ini.</div>
                                            <div class="small mt-1 text-muted">Gunakan tombol <strong>Sinkronisasi PTK Otomatis</strong> atau tambah secara manual.</div>
                                        </td>
                                    </tr>
                                <?php else: ?>
                                    <?php foreach($struktur as $s): ?>
                                        <tr>
                                            <td class="ps-4 text-center">
                                                <span class="badge bg-light text-dark border px-2 py-1" style="font-size: 12px; font-weight:700;">
                                                    #<?= (int)$s->urutan ?>
                                                </span>
                                            </td>
                                            <td>
                                                <div style="font-weight:700; color:#0f172a; font-size:14px;">
                                                    <?= htmlspecialchars($s->nama_lengkap, ENT_QUOTES, 'UTF-8') ?>
                                                </div>
                                                <div style="font-size:12px; color:#64748b; margin-top:2px;">
                                                    NIP: <?= htmlspecialchars($s->nip, ENT_QUOTES, 'UTF-8') ?: '<span class="text-muted">-</span>' ?>
                                                </div>
                                            </td>
                                            <td>
                                                <span class="pill-status pill-info" style="font-size:12px;">
                                                    <i class="fa fa-tag me-1"></i> <?= htmlspecialchars($s->jabatan, ENT_QUOTES, 'UTF-8') ?>
                                                </span>
                                            </td>
                                            <td class="text-end pe-4">
                                                <div class="d-inline-flex gap-1">
                                                    <button type="button" class="btn-modern btn-modern-light py-1 px-2 action-btn-edit" 
                                                            data-id="<?= $s->id ?>" 
                                                            data-ptk="<?= $s->ptk_id ?>" 
                                                            data-jabatan="<?= htmlspecialchars($s->jabatan, ENT_QUOTES, 'UTF-8') ?>" 
                                                            data-urutan="<?= $s->urutan ?>" 
                                                            title="Edit Posisi">
                                                        <i class="fas fa-pencil-alt text-primary"></i>
                                                    </button>
                                                    <a href="<?= base_url('admin_struktur/delete/'.$s->id.'/'.$kategori_slug) ?>" 
                                                       class="btn-modern btn-modern-light py-1 px-2 text-danger text-decoration-none" 
                                                       onclick="return confirm('Hapus data ini dari struktur?')" 
                                                       title="Hapus">
                                                        <i class="fas fa-trash-alt"></i>
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

<!-- Modal Tambah -->
<div class="modal fade" id="modalTambah" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg" style="border-radius:24px; overflow:hidden;">
            <form action="<?= base_url('admin_struktur/add') ?>" method="post">
                <input type="hidden" name="kategori" value="<?= htmlspecialchars($kategori, ENT_QUOTES, 'UTF-8') ?>">
                <input type="hidden" name="kategori_slug" value="<?= $kategori_slug ?>">
                
                <div class="modal-header px-4 pt-4 pb-3 border-0 bg-light">
                    <div>
                        <h5 class="modal-title fw-bold text-dark mb-1">Tambah ke <?= htmlspecialchars($kategori, ENT_QUOTES, 'UTF-8') ?></h5>
                        <p class="text-muted small mb-0">Pilih anggota dan tentukan jabatan yang tampil di website.</p>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body px-4 py-3">
                    <div class="mb-3">
                        <label class="form-label fw-bold small text-muted">Pilih Pegawai (PTK)</label>
                        <select name="ptk_id" class="input-modern" required>
                            <option value="">-- Pilih PTK --</option>
                            <?php foreach($list_ptk as $ptk): ?>
                                <option value="<?= $ptk->id ?>"><?= htmlspecialchars($ptk->nama_lengkap, ENT_QUOTES, 'UTF-8') ?> (<?= $ptk->nip ?: 'NIP: -' ?>)</option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold small text-muted">Jabatan / Posisi</label>
                        <input type="text" name="jabatan" class="input-modern" placeholder="Contoh: Kepala Madrasah / Koordinator..." required>
                    </div>

                    <div class="mb-2">
                        <label class="form-label fw-bold small text-muted">Nomor Urutan Tampil</label>
                        <input type="number" name="urutan" class="input-modern" value="1" min="1">
                        <div class="form-text small mt-1">Angka lebih kecil akan ditempatkan di posisi paling atas.</div>
                    </div>
                </div>

                <div class="modal-footer px-4 pb-4 pt-2 border-0 bg-light d-flex justify-content-end gap-2">
                    <button type="button" class="btn-modern btn-modern-light" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn-modern btn-modern-primary">
                        <i class="fas fa-save me-1"></i> Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Edit -->
<div class="modal fade" id="modalEdit" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg" style="border-radius:24px; overflow:hidden;">
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
                        <select name="ptk_id" id="edit_ptk_id" class="input-modern" required>
                            <?php foreach($list_ptk as $ptk): ?>
                                <option value="<?= $ptk->id ?>"><?= htmlspecialchars($ptk->nama_lengkap, ENT_QUOTES, 'UTF-8') ?> (<?= $ptk->nip ?: 'NIP: -' ?>)</option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold small text-muted">Jabatan / Posisi</label>
                        <input type="text" name="jabatan" id="edit_jabatan" class="input-modern" required>
                    </div>

                    <div class="mb-2">
                        <label class="form-label fw-bold small text-muted">Nomor Urutan Tampil</label>
                        <input type="number" name="urutan" id="edit_urutan" class="input-modern" min="1" required>
                    </div>
                </div>

                <div class="modal-footer px-4 pb-4 pt-2 border-0 bg-light d-flex justify-content-end gap-2">
                    <button type="button" class="btn-modern btn-modern-light" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn-modern btn-modern-primary">
                        <i class="fas fa-save me-1"></i> Update Data
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
