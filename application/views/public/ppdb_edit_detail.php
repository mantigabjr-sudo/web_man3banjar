<!DOCTYPE html>
<html>
<head>
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Edit Detail Pendaftaran</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<style>
body{
    background:linear-gradient(135deg,#f8fafc,#ecfdf5);
    font-family:'Segoe UI',sans-serif;
}
.box{
    background:white;
    border-radius:28px;
    padding:35px;
    box-shadow:0 15px 35px rgba(0,0,0,.06);
}
.form-label{
    font-weight:600;
    color:#166534;
}
</style>
</head>
<body>
<div class="container py-5">
<div class="box">

<h2 class="glow">Edit Data Peserta PPDB</h2>
<p class="soft-text mb-4">
Anda dapat memperbaiki data peserta. NISN, nomor pendaftaran, dan status tidak diubah dari halaman ini.
</p>

<div class="card p-4">

<form method="post" action="<?= base_url('ppdb/update_detail') ?>">

<div class="row g-4">

<div class="col-md-4">
<label class="form-label">Nomor Pendaftaran</label>
<input type="text" class="form-control" value="<?= $siswa->no_pendaftaran ?>" disabled>
</div>

<div class="col-md-4">
<label class="form-label">NISN</label>
<input type="text" class="form-control" value="<?= $siswa->nisn ?>" disabled>
</div>

<div class="col-md-4">
<label class="form-label">Status</label>
<input type="text" class="form-control" value="<?= $siswa->status ?>" disabled>
</div>

<div class="col-md-12">
<label class="form-label">Nama Lengkap</label>
<input type="text" name="nama_lengkap" class="form-control" value="<?= $siswa->nama_lengkap ?>" required>
</div>

<div class="col-md-6">
<label class="form-label">Tempat Lahir</label>
<input type="text" name="tempat_lahir" class="form-control" value="<?= $siswa->tempat_lahir ?>" required>
</div>

<div class="col-md-6">
<label class="form-label">Tanggal Lahir</label>
<input type="date" name="tanggal_lahir" class="form-control" value="<?= $siswa->tanggal_lahir ?>" required>
</div>

<div class="col-md-6">
<label class="form-label">Jenis Kelamin</label>
<select name="jk" class="form-select" required>
    <option value="L" <?= $siswa->jk=='L'?'selected':'' ?>>Laki-laki</option>
    <option value="P" <?= $siswa->jk=='P'?'selected':'' ?>>Perempuan</option>
</select>
</div>

<div class="col-md-6">
<label class="form-label">Asal Sekolah</label>
<input type="text" name="asal_sekolah" class="form-control" value="<?= $siswa->asal_sekolah ?>" required>
</div>

<div class="col-md-6">
<label class="form-label">No HP</label>
<input type="text" name="no_hp" class="form-control" value="<?= $siswa->no_hp ?>" required>
</div>

<div class="col-md-6">
<label class="form-label">Nama Ayah / Wali Awal</label>
<input type="text" name="nama_ortu" class="form-control" value="<?= $siswa->nama_ortu ?>" required>
</div>

<div class="col-12">
<h5 class="text-success mt-4">Data Pribadi</h5>
</div>

<div class="col-md-6">
<label class="form-label">NIK</label>
<input type="text" name="nik" class="form-control" value="<?= $siswa->nik ?>">
</div>

<div class="col-md-6">
<label class="form-label">Nomor KK</label>
<input type="text" name="no_kk" class="form-control" value="<?= $siswa->no_kk ?>">
</div>

<div class="col-md-4">
<label class="form-label">Agama</label>
<select name="agama" class="form-select">
    <option value="">Pilih Agama</option>
    <option value="Islam" <?= $siswa->agama=='Islam'?'selected':'' ?>>Islam</option>
    <option value="Kristen" <?= $siswa->agama=='Kristen'?'selected':'' ?>>Kristen</option>
    <option value="Katolik" <?= $siswa->agama=='Katolik'?'selected':'' ?>>Katolik</option>
    <option value="Hindu" <?= $siswa->agama=='Hindu'?'selected':'' ?>>Hindu</option>
    <option value="Budha" <?= $siswa->agama=='Budha'?'selected':'' ?>>Budha</option>
</select>
</div>

<div class="col-md-4">
<label class="form-label">Anak Ke</label>
<input type="number" name="anak_ke" class="form-control" value="<?= $siswa->anak_ke ?>">
</div>

<div class="col-md-4">
<label class="form-label">Jumlah Saudara</label>
<input type="number" name="jumlah_saudara" class="form-control" value="<?= $siswa->jumlah_saudara ?>">
</div>

<div class="col-12">
<h5 class="text-success mt-4">Alamat</h5>
</div>

<div class="col-md-12">
<label class="form-label">Alamat Lengkap</label>
<textarea name="alamat" class="form-control"><?= $siswa->alamat ?></textarea>
</div>

<div class="col-md-2">
<label class="form-label">RT</label>
<input type="text" name="rt" class="form-control" value="<?= $siswa->rt ?>">
</div>

<div class="col-md-2">
<label class="form-label">RW</label>
<input type="text" name="rw" class="form-control" value="<?= $siswa->rw ?>">
</div>

<div class="col-md-4">
<label class="form-label">Desa</label>
<input type="text" name="desa" class="form-control" value="<?= $siswa->desa ?>">
</div>

<div class="col-md-4">
<label class="form-label">Kecamatan</label>
<input type="text" name="kecamatan" class="form-control" value="<?= $siswa->kecamatan ?>">
</div>

<div class="col-md-6">
<label class="form-label">Kabupaten</label>
<input type="text" name="kabupaten" class="form-control" value="<?= $siswa->kabupaten ?>">
</div>

<div class="col-md-6">
<label class="form-label">Provinsi</label>
<input type="text" name="provinsi" class="form-control" value="<?= $siswa->provinsi ?>">
</div>

<div class="col-md-12">
<label class="form-label">Kode Pos</label>
<input type="text" name="kode_pos" class="form-control" value="<?= $siswa->kode_pos ?>">
</div>

<div class="col-12">
<h5 class="text-success mt-4">Data Orang Tua</h5>
</div>

<div class="col-md-6">
<label class="form-label">Nama Ayah</label>
<input type="text" name="nama_ayah" class="form-control" value="<?= $siswa->nama_ayah ?>">
</div>

<div class="col-md-6">
<label class="form-label">Pekerjaan Ayah</label>
<input type="text" name="pekerjaan_ayah" class="form-control" value="<?= $siswa->pekerjaan_ayah ?>">
</div>

<div class="col-md-6">
<label class="form-label">Nama Ibu</label>
<input type="text" name="nama_ibu" class="form-control" value="<?= $siswa->nama_ibu ?>">
</div>

<div class="col-md-6">
<label class="form-label">Pekerjaan Ibu</label>
<input type="text" name="pekerjaan_ibu" class="form-control" value="<?= $siswa->pekerjaan_ibu ?>">
</div>

<div class="col-md-12">
<label class="form-label">Penghasilan Orang Tua</label>
<select name="penghasilan_ortu" class="form-select">
    <option value="">Pilih Penghasilan</option>
    <option value="< 1 Juta" <?= $siswa->penghasilan_ortu=='< 1 Juta'?'selected':'' ?>>&lt; 1 Juta</option>
    <option value="1-3 Juta" <?= $siswa->penghasilan_ortu=='1-3 Juta'?'selected':'' ?>>1 - 3 Juta</option>
    <option value="3-5 Juta" <?= $siswa->penghasilan_ortu=='3-5 Juta'?'selected':'' ?>>3 - 5 Juta</option>
    <option value="> 5 Juta" <?= $siswa->penghasilan_ortu=='> 5 Juta'?'selected':'' ?>>&gt; 5 Juta</option>
</select>
</div>

<div class="col-md-12 mt-4">
<button class="btn btn-success w-100 py-3">
Simpan Perubahan
</button>
</div>

<div class="col-md-12">
<a href="<?= base_url('ppdb/detail') ?>" class="btn btn-secondary w-100">
Kembali
</a>
</div>

</div>

</form>

</div>

</div>

</div>
</body>
</html>