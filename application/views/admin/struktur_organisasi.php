<?php $this->load->view('templates/header', ['title' => 'Struktur Organisasi']); ?>
<?php $this->load->view('templates/sidebar'); ?>

<div class="content">

<style>
/* Hero Section */
.struktur-hero {
    background: radial-gradient(circle at top right, rgba(34, 197, 94, 0.15), transparent 45%),
                linear-gradient(135deg, #f0fdf4, #ffffff);
    border: 1px solid #dcfce7;
    border-radius: 24px;
    padding: 24px 28px;
    box-shadow: 0 10px 30px rgba(15, 23, 42, 0.04);
    margin-bottom: 24px;
}
.struktur-hero h2 {
    font-weight: 800;
    color: #14532d;
    letter-spacing: -0.5px;
}
.struktur-hero p {
    color: #475569;
    font-size: 14px;
    font-weight: 600;
}

/* Category Sidebar Navigation */
.kurikulum-nav {
    background: #ffffff;
    border: 1px solid #f1f5f9;
    border-radius: 24px !important;
    padding: 16px !important;
    box-shadow: 0 10px 30px rgba(15, 23, 42, 0.04) !important;
}

.kurikulum-nav .nav-link {
    border-radius: 16px !important;
    padding: 14px 18px !important;
    font-weight: 700 !important;
    font-size: 14px !important;
    border: 1px solid transparent;
    margin-bottom: 8px !important;
    transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1) !important;
    display: flex;
    align-items: center;
}

/* Non-active nav item */
.kurikulum-nav .nav-link.text-secondary {
    background: #f8fafc;
    color: #475569 !important;
    border-color: #f1f5f9;
}
.kurikulum-nav .nav-link.text-secondary:hover {
    background: #f0fdf4;
    color: #059669 !important;
    border-color: #bbf7d0;
    transform: translateX(4px);
}
.kurikulum-nav .nav-link.text-secondary i {
    color: #10b981 !important; /* Emerald icon color */
    transition: transform 0.25s;
}
.kurikulum-nav .nav-link.text-secondary:hover i {
    transform: scale(1.15);
}

/* Active nav item */
.kurikulum-nav .nav-link.active {
    background: linear-gradient(135deg, #10b981, #059669) !important;
    color: #ffffff !important;
    box-shadow: 0 10px 22px rgba(16, 185, 129, 0.25) !important;
    border-color: transparent !important;
}
.kurikulum-nav .nav-link.active i {
    color: #ffffff !important;
}

/* Main Card on the right */
.struktur-card {
    background: #ffffff;
    border: 1px solid #f1f5f9;
    border-radius: 24px;
    box-shadow: 0 10px 30px rgba(15, 23, 42, 0.04);
    overflow: hidden;
}

.struktur-card-header {
    background: #ffffff;
    border-bottom: 1px solid #f1f5f9;
    padding: 24px 28px 20px;
}

.struktur-card-header h5 {
    font-weight: 850;
    color: #0f172a;
    font-size: 18px;
    letter-spacing: -0.3px;
    margin: 0;
}

/* Buttons inside card body */
.btn-premium-success {
    background: linear-gradient(135deg, #10b981, #059669);
    border: none;
    color: white !important;
    font-weight: 700;
    padding: 12px 22px;
    border-radius: 14px;
    box-shadow: 0 8px 20px rgba(16, 185, 129, 0.2);
    transition: all 0.2s ease;
    display: inline-flex;
    align-items: center;
    gap: 8px;
}
.btn-premium-success:hover {
    transform: translateY(-2px);
    box-shadow: 0 10px 24px rgba(16, 185, 129, 0.28);
}

.btn-premium-sync {
    background: linear-gradient(135deg, #3b82f6, #2563eb);
    border: none;
    color: white !important;
    font-weight: 700;
    padding: 12px 22px;
    border-radius: 14px;
    box-shadow: 0 8px 20px rgba(59, 130, 246, 0.2);
    transition: all 0.2s ease;
    display: inline-flex;
    align-items: center;
    gap: 8px;
    text-decoration: none;
}
.btn-premium-sync:hover {
    transform: translateY(-2px);
    box-shadow: 0 10px 24px rgba(59, 130, 246, 0.28);
}

/* Table Style overrides */
.table-responsive-wrapper {
    border-radius: 18px;
    border: 1px solid #e2e8f0;
    overflow: hidden;
    background: #ffffff;
}

.struktur-table {
    margin-bottom: 0;
    width: 100%;
}

.struktur-table thead {
    background: #f8fafc;
}

.struktur-table th {
    font-weight: 750;
    color: #475569;
    font-size: 13px;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    padding: 16px 20px;
    border-bottom: 2px solid #e2e8f0;
}

.struktur-table tbody tr {
    transition: background-color 0.2s;
    border-bottom: 1px solid #f1f5f9;
}

.struktur-table tbody tr:last-child {
    border-bottom: none;
}

.struktur-table tbody tr:hover {
    background-color: #f8fafc;
}

.struktur-table td {
    padding: 18px 20px;
    color: #334155;
    vertical-align: middle;
}

/* Badges and Names */
.urutan-badge {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 38px;
    height: 38px;
    background: #f1f5f9;
    color: #475569;
    font-weight: 850;
    font-size: 14px;
    border-radius: 12px;
    border: 1px solid #e2e8f0;
}

.pegawai-name {
    font-weight: 800;
    color: #0f172a;
    font-size: 15px;
    line-height: 1.3;
}

.pegawai-nip {
    font-size: 12px;
    color: #64748b;
    font-weight: 600;
    margin-top: 4px;
}

.jabatan-badge {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    background: #ecfdf5;
    color: #047857;
    font-weight: 700;
    font-size: 13px;
    padding: 6px 14px;
    border-radius: 10px;
    border: 1px solid #d1fae5;
}

/* Action buttons styling */
.action-btn-group {
    display: inline-flex;
    gap: 8px;
}

.action-btn {
    width: 36px;
    height: 36px;
    border-radius: 10px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    border: none;
    font-size: 14px;
    transition: all 0.2s;
    text-decoration: none !important;
    cursor: pointer;
}

.action-btn-edit {
    background: #e0f2fe;
    color: #0369a1;
}

.action-btn-edit:hover {
    background: #bae6fd;
    color: #0369a1;
    transform: translateY(-2px);
    box-shadow: 0 4px 10px rgba(3, 105, 161, 0.15);
}

.action-btn-delete {
    background: #fee2e2;
    color: #b91c1c;
}

.action-btn-delete:hover {
    background: #fecaca;
    color: #b91c1c;
    transform: translateY(-2px);
    box-shadow: 0 4px 10px rgba(185, 28, 28, 0.15);
}

/* Modern Modals styling */
.modal-content {
    border-radius: 24px;
    border: none;
    box-shadow: 0 20px 40px rgba(15, 23, 42, 0.12);
    overflow: hidden;
}

.modal-header {
    background: #f8fafc;
    border-bottom: 1px solid #f1f5f9;
    padding: 20px 24px;
}

.modal-title {
    font-weight: 850;
    color: #0f172a;
    font-size: 18px;
}

.modal-body {
    padding: 24px;
}

.modal-footer {
    background: #f8fafc;
    border-top: 1px solid #f1f5f9;
    padding: 16px 24px;
}

.modal-form-label {
    font-weight: 700;
    color: #475569;
    font-size: 13px;
    margin-bottom: 8px;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    display: block;
}

.modal-form-control {
    border-radius: 12px !important;
    border: 1px solid #cbd5e1 !important;
    padding: 11px 14px !important;
    font-size: 14px !important;
    font-weight: 600 !important;
    background: #ffffff !important;
    transition: all 0.2s !important;
    width: 100%;
}

.modal-form-control:focus {
    border-color: #10b981 !important;
    box-shadow: 0 0 0 4px rgba(16, 185, 129, 0.15) !important;
}
</style>

    <div class="struktur-hero">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
            <div>
                <h2 class="mb-1"><i class="fas fa-sitemap me-2"></i> Struktur Organisasi</h2>
                <p class="mb-0">Kelola pembagian peran, jabatan, dan susunan organisasi madrasah.</p>
            </div>
        </div>
    </div>

    <?php if($this->session->flashdata('success')): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert" style="border-radius: 16px; border: none; background: #ecfdf5; color: #065f46; font-weight: 600; padding: 16px 20px; box-shadow: 0 4px 12px rgba(16,185,129,0.08);">
            <i class="fas fa-check-circle me-2"></i> <?= $this->session->flashdata('success') ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>
    <?php if($this->session->flashdata('info')): ?>
        <div class="alert alert-info alert-dismissible fade show" role="alert" style="border-radius: 16px; border: none; background: #eff6ff; color: #1e40af; font-weight: 600; padding: 16px 20px; box-shadow: 0 4px 12px rgba(59,130,246,0.08);">
            <i class="fas fa-info-circle me-2"></i> <?= $this->session->flashdata('info') ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>
    <?php if($this->session->flashdata('error')): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert" style="border-radius: 16px; border: none; background: #fef2f2; color: #991b1b; font-weight: 600; padding: 16px 20px; box-shadow: 0 4px 12px rgba(239,68,68,0.08);">
            <i class="fas fa-exclamation-circle me-2"></i> <?= $this->session->flashdata('error') ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <div class="row">
        <div class="col-lg-3 mb-4">
            <div class="kurikulum-nav">
                <div class="nav flex-column nav-pills" role="tablist" aria-orientation="vertical">
                    <?php foreach($kategori_options as $slug => $label): ?>
                        <?php 
                        $icon = 'fa-users';
                        if($slug == 'kepala-madrasah') $icon = 'fa-user-tie';
                        if($slug == 'wakamad') $icon = 'fa-user-shield';
                        if($slug == 'guru') $icon = 'fa-chalkboard-teacher';
                        if($slug == 'wali-kelas') $icon = 'fa-id-card-alt';
                        if($slug == 'tata-usaha') $icon = 'fa-laptop-house';
                        if($slug == 'koordinator-eskul') $icon = 'fa-volleyball-ball';
                        ?>
                        <a class="nav-link <?= $slug == $kategori_slug ? 'active' : 'text-secondary' ?>" 
                           href="<?= base_url('admin_struktur/index/'.$slug) ?>">
                            <i class="fas <?= $icon ?> me-3 text-center" style="width: 20px; font-size: 15px;"></i>
                            <span><?= htmlspecialchars($label, ENT_QUOTES, 'UTF-8') ?></span>
                        </a>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
        <div class="col-lg-9">
            <div class="struktur-card">
                <div class="struktur-card-header d-flex justify-content-between align-items-center flex-wrap gap-3">
                    <h5>Daftar <?= htmlspecialchars($kategori, ENT_QUOTES, 'UTF-8') ?></h5>
                </div>
                <div class="card-body p-4">
                    
                    <div class="d-flex flex-wrap gap-3 mb-4">
                        <button type="button" class="btn-premium-success" data-bs-toggle="modal" data-bs-target="#modalTambah">
                            <i class="fas fa-plus"></i> Tambah Manual
                        </button>
                        <a href="<?= base_url('admin_struktur/sync/'.$kategori_slug) ?>" class="btn-premium-sync" onclick="return confirm('Sistem akan mencari data PTK yang jabatannya cocok dan memasukkannya ke kategori ini secara otomatis. Lanjutkan?');">
                            <i class="fas fa-sync-alt"></i> Tarik Data Otomatis (Sinkronisasi PTK)
                        </a>
                    </div>

                    <div class="table-responsive-wrapper">
                        <table class="table table-hover align-middle struktur-table">
                            <thead>
                                <tr>
                                    <th width="90" class="text-center">Urutan</th>
                                    <th>Nama Pegawai / NIP</th>
                                    <th>Jabatan (Tampil di Website)</th>
                                    <th width="120" class="text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if(empty($struktur)): ?>
                                    <tr>
                                        <td colspan="4" class="text-center py-5 text-muted">
                                            <div style="font-size: 48px; color: #cbd5e1; margin-bottom: 12px;"><i class="fas fa-folder-open"></i></div>
                                            <div class="fw-bold">Belum ada data struktur di kategori ini.</div>
                                            <small class="text-secondary d-block mt-1">Gunakan tombol di atas untuk menambah atau menarik data otomatis.</small>
                                        </td>
                                    </tr>
                                <?php else: ?>
                                    <?php foreach($struktur as $s): ?>
                                        <tr>
                                            <td class="text-center">
                                                <span class="urutan-badge"><?= (int)$s->urutan ?></span>
                                            </td>
                                            <td>
                                                <div class="pegawai-name"><?= htmlspecialchars($s->nama_lengkap, ENT_QUOTES, 'UTF-8') ?></div>
                                                <div class="pegawai-nip">
                                                    <i class="fas fa-id-badge me-1 text-success"></i> <?= htmlspecialchars($s->nip, ENT_QUOTES, 'UTF-8') ?: '-' ?>
                                                </div>
                                            </td>
                                            <td>
                                                <span class="jabatan-badge">
                                                    <i class="fas fa-tag me-1"></i> <?= htmlspecialchars($s->jabatan, ENT_QUOTES, 'UTF-8') ?>
                                                </span>
                                            </td>
                                            <td class="text-center">
                                                <div class="action-btn-group">
                                                    <button type="button" class="action-btn action-btn-edit" 
                                                            data-id="<?= $s->id ?>" 
                                                            data-ptk="<?= $s->ptk_id ?>" 
                                                            data-jabatan="<?= htmlspecialchars($s->jabatan, ENT_QUOTES, 'UTF-8') ?>" 
                                                            data-urutan="<?= $s->urutan ?>" title="Edit">
                                                        <i class="fas fa-pen"></i>
                                                    </button>
                                                    <a href="<?= base_url('admin_struktur/delete/'.$s->id.'/'.$kategori_slug) ?>" class="action-btn action-btn-delete" onclick="return confirm('Hapus data ini dari struktur?')" title="Hapus">
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
<div class="modal fade" id="modalTambah" tabindex="-1" aria-labelledby="modalTambahLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form action="<?= base_url('admin_struktur/add') ?>" method="post">
                <input type="hidden" name="kategori" value="<?= htmlspecialchars($kategori, ENT_QUOTES, 'UTF-8') ?>">
                <input type="hidden" name="kategori_slug" value="<?= $kategori_slug ?>">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalTambahLabel"><i class="fas fa-plus-circle text-success me-2"></i>Tambah ke <?= htmlspecialchars($kategori, ENT_QUOTES, 'UTF-8') ?></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="modal-form-label">Pilih PTK / Pegawai</label>
                        <select name="ptk_id" class="modal-form-control form-select" required>
                            <option value="">-- Pilih PTK --</option>
                            <?php foreach($list_ptk as $ptk): ?>
                                <option value="<?= $ptk->id ?>"><?= htmlspecialchars($ptk->nama_lengkap, ENT_QUOTES, 'UTF-8') ?> (<?= $ptk->nip ?: 'NIP: -' ?>)</option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="modal-form-label">Jabatan (Tampil di Website)</label>
                        <input type="text" name="jabatan" class="modal-form-control form-control" placeholder="Contoh: Kaur Tata Usaha / Staf Tata Usaha" required>
                    </div>
                    <div class="mb-3">
                        <label class="modal-form-label">Urutan Tampil</label>
                        <input type="number" name="urutan" class="modal-form-control form-control" value="1" min="1">
                        <small class="text-muted fw-bold d-block mt-2"><i class="fas fa-info-circle text-info me-1"></i> Nilai urutan lebih kecil akan diletakkan di posisi lebih atas.</small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary px-4" data-bs-dismiss="modal" style="border-radius: 12px; font-weight: 600;">Batal</button>
                    <button type="submit" class="btn btn-success px-4" style="border-radius: 12px; font-weight: 600; background: linear-gradient(135deg, #10b981, #059669); border: none;"><i class="fas fa-save me-1"></i> Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Edit -->
<div class="modal fade" id="modalEdit" tabindex="-1" aria-labelledby="modalEditLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form action="<?= base_url('admin_struktur/update') ?>" method="post">
                <input type="hidden" name="kategori" value="<?= htmlspecialchars($kategori, ENT_QUOTES, 'UTF-8') ?>">
                <input type="hidden" name="kategori_slug" value="<?= $kategori_slug ?>">
                <input type="hidden" name="id" id="edit_id">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalEditLabel"><i class="fas fa-edit text-primary me-2"></i>Edit Data Struktur</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="modal-form-label">Pilih PTK / Pegawai</label>
                        <select name="ptk_id" id="edit_ptk_id" class="modal-form-control form-select" required>
                            <?php foreach($list_ptk as $ptk): ?>
                                <option value="<?= $ptk->id ?>"><?= htmlspecialchars($ptk->nama_lengkap, ENT_QUOTES, 'UTF-8') ?> (<?= $ptk->nip ?: 'NIP: -' ?>)</option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="modal-form-label">Jabatan (Tampil di Website)</label>
                        <input type="text" name="jabatan" id="edit_jabatan" class="modal-form-control form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="modal-form-label">Urutan Tampil</label>
                        <input type="number" name="urutan" id="edit_urutan" class="modal-form-control form-control" min="1" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary px-4" data-bs-dismiss="modal" style="border-radius: 12px; font-weight: 600;">Batal</button>
                    <button type="submit" class="btn btn-primary px-4" style="border-radius: 12px; font-weight: 600; background: linear-gradient(135deg, #3b82f6, #2563eb); border: none;"><i class="fas fa-save me-1"></i> Update</button>
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
