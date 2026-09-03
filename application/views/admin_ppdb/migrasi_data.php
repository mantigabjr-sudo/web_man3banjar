<?php $this->load->view('templates/header'); ?>
<?php $this->load->view('templates/sidebar'); ?>

<div class="content">

<div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
    <div>
        <h2 class="glow mb-1"><?= $title ?></h2>
        <p class="soft-text m-0">Memindahkan calon peserta didik yang <strong>Diterima</strong> menjadi <strong>Siswa Resmi Aktif Madrasah</strong>.</p>
    </div>
    <a href="<?= base_url('admin_ppdb') ?>" class="btn btn-outline-secondary btn-sm fw-bold rounded-pill px-3">
        ← Kembali ke Daftar PMB
    </a>
</div>

<?php if($this->session->flashdata('success')): ?>
<div class="alert alert-success rounded-4 fw-bold shadow-sm">
    <i class="bi bi-check-circle-fill me-2"></i><?= $this->session->flashdata('success') ?>
</div>
<?php endif; ?>

<?php if($this->session->flashdata('error')): ?>
<div class="alert alert-danger rounded-4 fw-bold shadow-sm">
    <i class="bi bi-exclamation-triangle-fill me-2"></i><?= $this->session->flashdata('error') ?>
</div>
<?php endif; ?>

<!-- Box Informasi & Panduan Migrasi Data -->
<div class="card border-0 shadow-sm rounded-4 p-4 mb-4" style="background: linear-gradient(135deg, #f0fdf4, #ecfdf5); border: 1.5px solid #bbf7d0 !important;">
    <div class="d-flex align-items-start gap-3">
        <div class="badge bg-success rounded-circle p-2 mt-1" style="font-size: 20px;">
            <i class="bi bi-database-fill-check"></i>
        </div>
        <div>
            <h5 class="fw-bold text-success mb-1">Apa fungsi Migrasi Data?</h5>
            <p class="text-dark small mb-2" style="line-height: 1.6;">
                Proses migrasi akan secara otomatis membuatkan data <strong>Siswa Baru</strong> di modul kesiswaan, memindahkan biodata lengkap (NISN, Nama, Tempat/Tgl Lahir, Data Ortu, Alamat), serta membuatkan <strong>Akun Portal Siswa</strong> dengan Username/Password sesuai NISN peserta.
            </p>
            <small class="text-muted"><i class="bi bi-info-circle me-1"></i>Hanya peserta dengan status <strong>Diterima</strong> dan <strong>Belum Dimigrasikan</strong> yang tampil pada daftar di bawah.</small>
        </div>
    </div>
</div>

<div class="card border-0 shadow-sm rounded-4 p-4 bg-white" style="border: 1px solid #e2e8f0 !important;">

<div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
    <div>
        <h5 class="m-0 text-success fw-bold"><i class="bi bi-people-fill me-1"></i> Daftar Calon Siswa Siap Migrasi</h5>
        <small class="text-muted">Total: <?= count($pendaftar) ?> calon siswa</small>
    </div>
    <?php if(!empty($pendaftar)): ?>
    <a href="<?= base_url('admin_ppdb/migrasi_ppdb_all') ?>" class="btn btn-warning fw-bold d-flex align-items-center gap-2 px-3 py-2 shadow-sm border-0" style="border-radius:12px; color:#78350f; background: #fef08a;" onclick="return confirm('Apakah Anda yakin ingin memigrasikan SEMUA pendaftar yang ada di daftar ini ke data Siswa Aktif Madrasah?')">
        <i class="bi bi-database-fill-up fs-6"></i> Jalankan Migrasi Massal (Semua)
    </a>
    <?php endif; ?>
</div>

<div class="table-responsive">
<table class="table table-bordered table-striped align-middle datatable nowrap" style="width:100%">
<thead class="table-success">
<tr>
    <th style="width: 40px;">No</th>
    <th>Nama &amp; No Pendaftaran</th>
    <th>NISN</th>
    <th>Jalur &amp; Peminatan</th>
    <th>Asal Sekolah</th>
    <th>Tanggal Diterima</th>
    <th style="min-width: 140px;">Aksi</th>
</tr>
</thead>

<tbody>
<?php $no=1; foreach($pendaftar as $p): ?>
<tr>
    <td><?= $no++ ?></td>
    <td>
        <strong class="text-dark d-block"><?= htmlspecialchars($p->nama_lengkap ?? '-') ?></strong>
        <small class="text-muted"><?= htmlspecialchars($p->no_pendaftaran ?? '-') ?></small>
    </td>
    <td><?= htmlspecialchars($p->nisn ?? '-') ?></td>
    <td>
        <span class="badge bg-light text-success border border-success fw-bold d-inline-block mb-1">
            <?= htmlspecialchars($p->jalur_pendaftaran ?? 'Reguler') ?>
        </span>
        <div class="text-muted small">Pilihan: <?= htmlspecialchars($p->pilihan_jurusan_1 ?? 'MIPA') ?></div>
    </td>
    <td><?= htmlspecialchars($p->asal_sekolah ?? '-') ?></td>
    <td><?= !empty($p->accepted_at) ? (function_exists('tanggal_jam_indo') ? tanggal_jam_indo($p->accepted_at) : $p->accepted_at) : '-' ?></td>
    <td>
        <div class="d-flex gap-1">
            <a href="<?= base_url('admin_ppdb/detail/'.$p->id) ?>" class="btn btn-sm btn-outline-info fw-bold py-1 px-2" style="border-radius: 6px; font-size: 11.5px;">
                <i class="bi bi-eye"></i> Detail
            </a>
            <?php if(empty($p->is_migrated) || $p->is_migrated == 0): ?>
                <a href="<?= base_url('admin_ppdb/migrasi_ppdb/'.$p->id) ?>"
                   class="btn btn-sm btn-success fw-bold py-1 px-2" style="border-radius: 6px; font-size: 11.5px;"
                   onclick="return confirm('Migrasikan peserta ini ke data Siswa Aktif?')">
                    <i class="bi bi-person-plus-fill"></i> Migrasi Siswa
                </a>
            <?php else: ?>
                <span class="badge bg-success py-1 px-2">
                    <i class="bi bi-check-all"></i> Sudah Migrasi
                </span>
            <?php endif; ?>
        </div>
    </td>
</tr>
<?php endforeach; ?>
</tbody>
</table>
</div>

</div>

</div>

<?php $this->load->view('templates/footer'); ?>