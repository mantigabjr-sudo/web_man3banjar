<?php $this->load->view('templates/header'); ?>
<?php $this->load->view('templates/sidebar'); ?>

<?php
if(!function_exists('ppdb_peng_e')){
    function ppdb_peng_e($text){
        return htmlspecialchars((string)$text, ENT_QUOTES, 'UTF-8');
    }
}
?>

<div class="content">

<style>
.ppdb-peng-page{
    max-width:1400px;
    margin:0 auto;
}

.ppdb-peng-hero{
    background:
        radial-gradient(circle at top right, rgba(250,204,21,.18), transparent 30%),
        linear-gradient(135deg,#064e3b,#16a34a);
    color:white;
    border-radius:28px;
    padding:24px;
    margin-bottom:20px;
    box-shadow:0 22px 60px rgba(22,163,74,.20);
    display:flex;
    justify-content:space-between;
    gap:18px;
    align-items:center;
    flex-wrap:wrap;
}

.ppdb-peng-hero h2{
    margin:0;
    font-weight:950;
}

.ppdb-peng-hero p{
    margin:6px 0 0;
    color:rgba(255,255,255,.78);
    font-weight:650;
}

.ppdb-peng-card{
    background:white;
    border:1px solid #e2e8f0;
    border-radius:24px;
    box-shadow:0 16px 42px rgba(15,23,42,.07);
    overflow:hidden;
    margin-bottom:20px;
}

.ppdb-peng-head{
    padding:18px 20px;
    border-bottom:1px solid #e2e8f0;
    background:#ffffff;
}

.ppdb-peng-head h5{
    margin:0;
    color:#14532d;
    font-weight:950;
}

.ppdb-peng-body{
    padding:20px;
}

.ppdb-field{
    margin-bottom:14px;
}

.ppdb-field label{
    display:block;
    font-size:13px;
    font-weight:900;
    color:#166534;
    margin-bottom:6px;
}

.ppdb-input,
.ppdb-textarea{
    width:100%;
    border:1px solid #d1fae5;
    background:#f8fafc;
    border-radius:15px;
    padding:11px 13px;
    font-weight:700;
    outline:none;
}

.ppdb-input{
    min-height:46px;
}

.ppdb-textarea{
    min-height:130px;
    resize:vertical;
}

.ppdb-input:focus,
.ppdb-textarea:focus{
    background:white;
    border-color:#22c55e;
    box-shadow:0 0 0 4px rgba(34,197,94,.12);
}

.btn-peng-save{
    min-height:44px;
    border:0;
    border-radius:15px;
    background:linear-gradient(135deg,#15803d,#22c55e);
    color:white;
    font-weight:950;
    padding:0 18px;
}

.peng-status{
    display:inline-flex;
    padding:7px 10px;
    border-radius:999px;
    font-size:12px;
    font-weight:950;
}

.peng-aktif{
    background:#dcfce7;
    color:#166534;
}

.peng-draft{
    background:#fef3c7;
    color:#92400e;
}

.peng-popup{
    background:#dbeafe;
    color:#1d4ed8;
}

.btn-action{
    min-height:34px;
    border-radius:12px;
    padding:0 12px;
    font-size:12px;
    font-weight:950;
    display:inline-flex;
    align-items:center;
    justify-content:center;
    text-decoration:none;
    border:0;
}

.btn-edit{
    background:#e0f2fe;
    color:#075985;
}

.btn-delete{
    background:#fee2e2;
    color:#991b1b;
}

@media(max-width:768px){
    .ppdb-peng-hero,
    .ppdb-peng-card{
        border-radius:20px;
    }
}
</style>

<div class="ppdb-peng-page">

    <div class="ppdb-peng-hero">
        <div>
            <h2>Pengumuman PPDB</h2>
            <p>Kelola jadwal tes, pengumuman kelulusan, daftar ulang, dan informasi peserta.</p>
        </div>

        <a href="<?= base_url('admin_ppdb/dashboard') ?>" class="btn btn-light rounded-pill fw-bold">
            Kembali Dashboard
        </a>
    </div>

    <?php if($this->session->flashdata('success')): ?>
        <div class="alert alert-success rounded-4 fw-bold">
            <?= $this->session->flashdata('success') ?>
        </div>
    <?php endif; ?>

    <?php if($this->session->flashdata('error')): ?>
        <div class="alert alert-danger rounded-4 fw-bold">
            <?= $this->session->flashdata('error') ?>
        </div>
    <?php endif; ?>

    <div class="row g-4">

        <div class="col-lg-4">
            <div class="ppdb-peng-card">
                <div class="ppdb-peng-head">
                    <h5>Tambah Pengumuman</h5>
                </div>

                <div class="ppdb-peng-body">
                    <form method="post" action="<?= base_url('admin_ppdb/add_pengumuman') ?>">

                        <div class="ppdb-field">
                            <label>Judul</label>
                            <input type="text" name="judul" class="ppdb-input" required>
                        </div>

                        <div class="ppdb-field">
                            <label>Kategori</label>
                            <select name="kategori" class="ppdb-input">
                                <option value="Informasi">Informasi</option>
                                <option value="Jadwal Tes">Jadwal Tes</option>
                                <option value="Kelulusan">Kelulusan</option>
                                <option value="Daftar Ulang">Daftar Ulang</option>
                            </select>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="ppdb-field">
                                    <label>Tanggal Mulai</label>
                                    <input type="date" name="tanggal_mulai" class="ppdb-input">
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="ppdb-field">
                                    <label>Tanggal Selesai</label>
                                    <input type="date" name="tanggal_selesai" class="ppdb-input">
                                </div>
                            </div>
                        </div>

                        <div class="ppdb-field">
                            <label>Waktu</label>
                            <input type="text" name="waktu" class="ppdb-input" placeholder="Contoh: 08.00 - selesai">
                        </div>

                        <div class="ppdb-field">
                            <label>Lokasi</label>
                            <input type="text" name="lokasi" class="ppdb-input" placeholder="Contoh: Aula MAN 3 Banjar">
                        </div>

                        <div class="ppdb-field">
                            <label>Target Peserta</label>
                            <select name="target_status" class="ppdb-input">
                                <option value="Semua">Semua Peserta</option>
                                <option value="Lengkapi Biodata">Lengkapi Biodata</option>
                                <option value="Upload Berkas">Upload Berkas</option>
                                <option value="Menunggu Verifikasi Berkas">Menunggu Verifikasi Berkas</option>
                                <option value="Perlu Perbaikan">Perlu Perbaikan</option>
                                <option value="Diterima">Diterima</option>
                                <option value="Ditolak">Ditolak</option>
                            </select>
                        </div>

                        <div class="ppdb-field">
                            <label>Isi Pengumuman</label>
                            <textarea name="isi" class="ppdb-textarea" required></textarea>
                        </div>

                        <div class="ppdb-field">
                            <label>Link Tambahan</label>
                            <input type="url" name="link" class="ppdb-input" placeholder="https://...">
                        </div>

                        <div class="ppdb-field">
                            <label>Status</label>
                            <select name="status" class="ppdb-input">
                                <option value="Draft">Draft</option>
                                <option value="Aktif">Aktif</option>
                            </select>
                        </div>

                        <label class="d-flex gap-2 align-items-center fw-bold text-success mb-3">
                            <input type="checkbox" name="tampil_popup" value="1">
                            Tampilkan sebagai popup dashboard
                        </label>

                        <button class="btn-peng-save w-100">
                            Simpan Pengumuman
                        </button>

                    </form>
                </div>
            </div>
        </div>

        <div class="col-lg-8">
            <div class="ppdb-peng-card">
                <div class="ppdb-peng-head">
                    <h5>Daftar Pengumuman</h5>
                </div>

                <div class="ppdb-peng-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped align-middle">
                            <thead class="table-success">
                                <tr>
                                    <th>Judul</th>
                                    <th>Kategori</th>
                                    <th>Target</th>
                                    <th>Status</th>
                                    <th>Popup</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>

                            <tbody>
                                <?php foreach($pengumuman as $g): ?>
                                    <tr>
                                        <td>
                                            <strong><?= ppdb_peng_e($g->judul) ?></strong>
                                            <br>
                                            <small class="text-muted">
                                                <?= !empty($g->tanggal_mulai) ? date('d-m-Y', strtotime($g->tanggal_mulai)) : '-' ?>
                                                s/d
                                                <?= !empty($g->tanggal_selesai) ? date('d-m-Y', strtotime($g->tanggal_selesai)) : '-' ?>
                                            </small>
                                        </td>

                                        <td><?= ppdb_peng_e($g->kategori) ?></td>
                                        <td><?= ppdb_peng_e($g->target_status) ?></td>

                                        <td>
                                            <?php if($g->status == 'Aktif'): ?>
                                                <span class="peng-status peng-aktif">Aktif</span>
                                            <?php else: ?>
                                                <span class="peng-status peng-draft">Draft</span>
                                            <?php endif; ?>
                                        </td>

                                        <td>
                                            <?php if($g->tampil_popup == 1): ?>
                                                <span class="peng-status peng-popup">Popup</span>
                                            <?php else: ?>
                                                -
                                            <?php endif; ?>
                                        </td>

                                        <td>
                                            <button type="button"
                                                    class="btn-action btn-edit"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#modalEditPengumuman<?= $g->id ?>">
                                                Edit
                                            </button>

                                            <a href="<?= base_url('admin_ppdb/delete_pengumuman/'.$g->id) ?>"
                                               class="btn-action btn-delete"
                                               onclick="return confirm('Hapus pengumuman ini?')">
                                                Hapus
                                            </a>
                                        </td>
                                    </tr>

                                    <div class="modal fade" id="modalEditPengumuman<?= $g->id ?>" tabindex="-1">
                                        <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
                                            <div class="modal-content rounded-4 border-0">

                                                <form method="post" action="<?= base_url('admin_ppdb/update_pengumuman/'.$g->id) ?>">

                                                    <div class="modal-header">
                                                        <div>
                                                            <h5 class="modal-title fw-bold text-success">
                                                                Edit Pengumuman
                                                            </h5>
                                                            <small class="text-muted fw-bold">
                                                                Perbarui informasi PPDB.
                                                            </small>
                                                        </div>

                                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                    </div>

                                                    <div class="modal-body">

                                                        <div class="ppdb-field">
                                                            <label>Judul</label>
                                                            <input type="text"
                                                                   name="judul"
                                                                   class="ppdb-input"
                                                                   value="<?= ppdb_peng_e($g->judul) ?>"
                                                                   required>
                                                        </div>

                                                        <div class="ppdb-field">
                                                            <label>Kategori</label>
                                                            <select name="kategori" class="ppdb-input">
                                                                <?php foreach(['Informasi','Jadwal Tes','Kelulusan','Daftar Ulang'] as $kat): ?>
                                                                    <option value="<?= $kat ?>" <?= $g->kategori == $kat ? 'selected' : '' ?>>
                                                                        <?= $kat ?>
                                                                    </option>
                                                                <?php endforeach; ?>
                                                            </select>
                                                        </div>

                                                        <div class="row">
                                                            <div class="col-md-6">
                                                                <div class="ppdb-field">
                                                                    <label>Tanggal Mulai</label>
                                                                    <input type="date"
                                                                           name="tanggal_mulai"
                                                                           class="ppdb-input"
                                                                           value="<?= ppdb_peng_e($g->tanggal_mulai) ?>">
                                                                </div>
                                                            </div>

                                                            <div class="col-md-6">
                                                                <div class="ppdb-field">
                                                                    <label>Tanggal Selesai</label>
                                                                    <input type="date"
                                                                           name="tanggal_selesai"
                                                                           class="ppdb-input"
                                                                           value="<?= ppdb_peng_e($g->tanggal_selesai) ?>">
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div class="ppdb-field">
                                                            <label>Waktu</label>
                                                            <input type="text"
                                                                   name="waktu"
                                                                   class="ppdb-input"
                                                                   value="<?= ppdb_peng_e($g->waktu) ?>">
                                                        </div>

                                                        <div class="ppdb-field">
                                                            <label>Lokasi</label>
                                                            <input type="text"
                                                                   name="lokasi"
                                                                   class="ppdb-input"
                                                                   value="<?= ppdb_peng_e($g->lokasi) ?>">
                                                        </div>

                                                        <div class="ppdb-field">
                                                            <label>Target Peserta</label>
                                                            <select name="target_status" class="ppdb-input">
                                                                <?php foreach(['Semua','Lengkapi Biodata','Upload Berkas','Menunggu Verifikasi Berkas','Perlu Perbaikan','Diterima','Ditolak'] as $target): ?>
                                                                    <option value="<?= $target ?>" <?= $g->target_status == $target ? 'selected' : '' ?>>
                                                                        <?= $target ?>
                                                                    </option>
                                                                <?php endforeach; ?>
                                                            </select>
                                                        </div>

                                                        <div class="ppdb-field">
                                                            <label>Isi Pengumuman</label>
                                                            <textarea name="isi" class="ppdb-textarea" required><?= ppdb_peng_e($g->isi) ?></textarea>
                                                        </div>

                                                        <div class="ppdb-field">
                                                            <label>Link Tambahan</label>
                                                            <input type="url"
                                                                   name="link"
                                                                   class="ppdb-input"
                                                                   value="<?= ppdb_peng_e($g->link) ?>">
                                                        </div>

                                                        <div class="ppdb-field">
                                                            <label>Status</label>
                                                            <select name="status" class="ppdb-input">
                                                                <option value="Draft" <?= $g->status == 'Draft' ? 'selected' : '' ?>>Draft</option>
                                                                <option value="Aktif" <?= $g->status == 'Aktif' ? 'selected' : '' ?>>Aktif</option>
                                                            </select>
                                                        </div>

                                                        <label class="d-flex gap-2 align-items-center fw-bold text-success">
                                                            <input type="checkbox"
                                                                   name="tampil_popup"
                                                                   value="1"
                                                                   <?= $g->tampil_popup == 1 ? 'checked' : '' ?>>
                                                            Tampilkan sebagai popup dashboard
                                                        </label>

                                                    </div>

                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">
                                                            Batal
                                                        </button>

                                                        <button class="btn btn-success rounded-pill px-4 fw-bold">
                                                            Simpan Perubahan
                                                        </button>
                                                    </div>

                                                </form>

                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach; ?>

                                <?php if(empty($pengumuman)): ?>
                                    <tr>
                                        <td colspan="6" class="text-center text-muted fw-bold py-4">
                                            Belum ada pengumuman PPDB.
                                        </td>
                                    </tr>
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

<?php $this->load->view('templates/footer'); ?>