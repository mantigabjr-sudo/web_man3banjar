<?php $this->load->view('templates/header'); ?>
<?php $this->load->view('templates/sidebar'); ?>

<div class="content">

<h2 class="glow"><?= $title ?></h2>
<p class="soft-text mb-4">Hanya peserta diterima dan belum dimigrasikan yang tampil di halaman ini.</p>

<?php if($this->session->flashdata('success')): ?>
<div class="alert alert-success">
    <?= $this->session->flashdata('success') ?>
</div>
<?php endif; ?>

<?php if($this->session->flashdata('error')): ?>
<div class="alert alert-danger">
    <?= $this->session->flashdata('error') ?>
</div>
<?php endif; ?>

<div class="card p-4">

<div class="d-flex justify-content-between align-items-center mb-3">
    <h5 class="m-0 text-success fw-bold">Daftar Pendaftar (Belum Migrasi)</h5>
    <?php if(!empty($pendaftar)): ?>
    <a href="<?= base_url('admin_ppdb/migrasi_ppdb_all') ?>" class="btn btn-warning fw-bold d-flex align-items-center gap-2 px-3 border-0" style="border-radius:14px; color:#92400e;" onclick="return confirm('Apakah Anda yakin ingin memigrasikan SEMUA pendaftar yang ada di daftar ini ke data Siswa Aktif?')">
        <i class="bi bi-database-fill-up"></i> Jalankan Migrasi Massal
    </a>
    <?php endif; ?>
</div>

<table class="table table-bordered table-striped datatable nowrap" style="width:100%">
<thead class="table-success">
<tr>
    <th>No</th>
    <th>No Pendaftaran</th>
    <th>Nama</th>
    <th>NISN</th>
    <th>Asal Sekolah</th>
    <th>Tanggal Diterima</th>
    <th>Aksi</th>
</tr>
</thead>

<tbody>
<?php $no=1; foreach($pendaftar as $p): ?>
<tr>
    <td><?= $no++ ?></td>
    <td><?= $p->no_pendaftaran ?></td>
    <td><?= $p->nama_lengkap ?></td>
    <td><?= $p->nisn ?></td>
    <td><?= $p->asal_sekolah ?></td>
    <td><?= tanggal_jam_indo($p->accepted_at) ?></td>
    <td>
        <a href="<?= base_url('admin_ppdb/detail/'.$p->id) ?>" class="btn btn-info btn-sm">
            Detail
        </a>
	<?php if(empty($p->is_migrated) || $p->is_migrated == 0): ?>
		<a href="<?= base_url('admin_ppdb/migrasi_ppdb/'.$p->id) ?>"
		   class="btn btn-success btn-sm"
		   onclick="return confirm('Migrasikan peserta ini ke data siswa?')">
			Migrasi ke Siswa
		</a>
	<?php else: ?>
		<span class="badge bg-success">
			Sudah Migrasi
		</span>
	<?php endif; ?>
    </td>
</tr>
<?php endforeach; ?>
</tbody>
</table>

</div>

</div>

<?php $this->load->view('templates/footer'); ?>