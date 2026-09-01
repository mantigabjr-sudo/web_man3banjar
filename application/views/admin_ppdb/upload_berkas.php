<?php $this->load->view('templates/header'); ?>
<?php $this->load->view('templates/sidebar'); ?>

<div class="content">

<h2 class="glow">Upload / Edit Berkas Peserta</h2>
<p class="soft-text mb-4"><?= $p->nama_lengkap ?> - <?= $p->nisn ?></p>

<?php if($this->session->flashdata('error')): ?>
<div class="alert alert-danger">
    <?= $this->session->flashdata('error') ?>
</div>
<?php endif; ?>

<div class="card p-4">

<form method="post" enctype="multipart/form-data"
action="<?= base_url('admin_ppdb/save_upload_berkas/'.$p->id) ?>">

<div class="row g-4">

<?php
$files = [
    'foto' => 'Pas Foto',
    'kk_file' => 'Kartu Keluarga',
    'akta_file' => 'Akta Kelahiran',
	'sk_kelas9_file'   => 'Surat Keterangan Kelas 9',
    'rapor_file' => 'Rapor / Nilai',
    'skl_file' => 'Surat Keterangan Lulus',
    'nisn_file' => 'Surat Aktif NISN',
    'ijazah_file' => 'Ijazah',
    'sertifikat_file' => 'Sertifikat Prestasi / Tahfidz'
];
?>

<?php foreach($files as $field => $label): ?>
<div class="col-md-6">
    <label class="form-label"><?= $label ?></label>
    <input type="file" name="<?= $field ?>" class="form-control" <?= $field === 'ijazah_file' ? 'required' : '' ?>>
    
    <?php if(!empty($p->$field)): ?>
    <div class="mt-2 text-success" style="font-size:13px;">
        <i class="bi bi-check-circle-fill"></i> Sudah diupload: 
        <a href="<?= base_url('uploads/temp/ppdb/'.$p->$field) ?>" target="_blank"><?= $p->$field ?></a>
    </div>
    <?php else: ?>
    <small class="text-muted">Belum upload</small>
    <?php endif; ?>
</div>
<?php endforeach; ?>

<div class="col-md-12 mt-4">
    <button class="btn btn-success w-100 py-3">
        Simpan Berkas
    </button>
</div>

<div class="col-md-12">
    <a href="<?= base_url('admin_ppdb/detail/'.$p->id) ?>" class="btn btn-secondary w-100">
        Kembali
    </a>
</div>

</div>

</form>

</div>

</div>

<?php $this->load->view('templates/footer'); ?>