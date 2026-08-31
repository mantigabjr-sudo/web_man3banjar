<!DOCTYPE html>
<html>
<head>
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>PPDB Ditutup</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<body style="background:linear-gradient(135deg,#f8fafc,#ecfdf5);">

<div class="container py-5">
<div class="card p-5 text-center rounded-4 shadow-sm">

<h2 class="text-danger">PPDB Sedang Ditutup</h2>

<p class="text-muted mt-3">
    Pendaftaran peserta didik baru untuk saat ini belum dibuka atau sudah ditutup.
</p>

<?php if(!empty($settings->pengumuman_ppdb)): ?>
<div class="alert alert-success mt-3">
    <?= nl2br($settings->pengumuman_ppdb) ?>
</div>
<?php endif; ?>

<a href="<?= base_url() ?>" class="btn btn-success mt-3">
    Kembali ke Beranda
</a>

</div>
</div>

<script>
Swal.fire({
    icon:'warning',
    title:'PPDB Belum Dibuka / Sudah Ditutup',
    text:'Silakan pantau informasi resmi MAN 3 Banjar.',
    confirmButtonColor:'#16a34a'
});
</script>

</body>
</html>