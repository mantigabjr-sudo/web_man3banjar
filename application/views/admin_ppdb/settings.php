<?php $this->load->view('templates/header'); ?>
<?php $this->load->view('templates/sidebar'); ?>

<div class="content">

<h2 class="glow">Pengaturan PPDB</h2>
<p class="soft-text mb-4">Atur tahun ajaran dan status pendaftaran.</p>

<?php if($this->session->flashdata('success')): ?>
<div class="alert alert-success">
    <?= $this->session->flashdata('success') ?>
</div>
<?php endif; ?>

<div class="card p-4">

<form method="post" action="<?= base_url('admin_ppdb/update_settings') ?>" enctype="multipart/form-data">

<div class="row g-4">

<div class="col-md-3">
    <label class="form-label fw-bold">Tahun Ajaran Aktif</label>
    <input type="text" name="tahun_ajaran" class="form-control"
           value="<?= $settings ? $settings->tahun_ajaran : '' ?>"
           placeholder="Contoh: 2026/2027" required>
</div>

<div class="col-md-3">
    <label class="form-label fw-bold">Singkatan Istilah</label>
    <input type="text" name="nama_ppdb" class="form-control"
           value="<?= isset($settings->nama_ppdb) && $settings->nama_ppdb ? $settings->nama_ppdb : 'PMB' ?>"
           placeholder="Contoh: PMB, PPDB, SPMB" required>
</div>

<div class="col-md-3">
    <label class="form-label fw-bold">Nama Lengkap Istilah</label>
    <input type="text" name="judul_panjang_ppdb" class="form-control"
           value="<?= isset($settings->judul_panjang_ppdb) && $settings->judul_panjang_ppdb ? $settings->judul_panjang_ppdb : 'Penerimaan Murid Baru' ?>"
           placeholder="Contoh: Penerimaan Murid Baru">
</div>

<div class="col-md-3">
    <label class="form-label fw-bold">Status Pendaftaran</label>
    <select name="status_ppdb" class="form-select" required>
        <option value="Dibuka" <?= $settings && $settings->status_ppdb=='Dibuka'?'selected':'' ?>>Dibuka</option>
        <option value="Ditutup" <?= $settings && $settings->status_ppdb=='Ditutup'?'selected':'' ?>>Ditutup</option>
    </select>
</div>

<div class="col-md-6">
    <label class="form-label">Tanggal Mulai</label>
    <input type="date" name="tanggal_mulai" class="form-control"
           value="<?= $settings ? $settings->tanggal_mulai : '' ?>">
</div>

<div class="col-md-6">
    <label class="form-label">Tanggal Selesai</label>
    <input type="date" name="tanggal_selesai" class="form-control"
           value="<?= $settings ? $settings->tanggal_selesai : '' ?>">
</div>

<!-- Pengaturan Jadwal Tes Seleksi / Ujian PMB -->
<div class="col-12 mt-2">
    <div class="p-3 border rounded-3 bg-light">
        <h6 class="fw-bold text-success mb-3"><i class="bi bi-clock-history me-1"></i> Pengaturan Jadwal &amp; Lokasi Tes Seleksi PMB (Otomatis Dikaitkan Saat Verifikasi)</h6>
        <div class="row g-3">
            <div class="col-md-4">
                <label class="form-label fw-semibold small">Tanggal Pelaksanaan Ujian/Tes</label>
                <input type="date" name="default_tanggal_tes" class="form-control"
                       value="<?= isset($settings->default_tanggal_tes) ? $settings->default_tanggal_tes : '' ?>">
            </div>
            <div class="col-md-4">
                <label class="form-label fw-semibold small">Waktu / Jam Pelaksanaan</label>
                <input type="text" name="default_jam_tes" class="form-control"
                       value="<?= isset($settings->default_jam_tes) ? htmlspecialchars($settings->default_jam_tes) : '08:00 - 11.30 WITA' ?>"
                       placeholder="Contoh: 08:00 - 11.30 WITA">
            </div>
            <div class="col-md-4">
                <label class="form-label fw-semibold small">Ruang / Lokasi Ujian</label>
                <input type="text" name="default_ruang_tes" class="form-control"
                       value="<?= isset($settings->default_ruang_tes) ? htmlspecialchars($settings->default_ruang_tes) : 'Kampus MAN 3 Banjar' ?>"
                       placeholder="Contoh: Kampus MAN 3 Banjar / Gedung CBT">
            </div>
            <div class="col-md-12">
                <label class="form-label fw-semibold small">Materi Ujian &amp; Catatan untuk Peserta (Tampil di Kartu &amp; Pesan WA)</label>
                <input type="text" name="materi_tes_info" class="form-control"
                       value="<?= isset($settings->materi_tes_info) ? htmlspecialchars($settings->materi_tes_info) : 'Tes Potensi Akademik, Baca Tulis Al-Qur\'an (BTQ), dan Wawancara' ?>"
                       placeholder="Contoh: Tes Akademik, BTQ, Wawancara">
            </div>
        </div>
    </div>
</div>

<div class="col-md-12">
    <label class="form-label">Pengumuman PPDB</label>
    <textarea name="pengumuman_ppdb" class="form-control" rows="5"><?= $settings ? $settings->pengumuman_ppdb : '' ?></textarea>
</div>

<div class="col-md-12">
    <label class="form-label">Persyaratan Pendaftaran</label>
    <div class="form-text text-muted mb-2">Gunakan baris baru (Enter) untuk memisahkan setiap poin persyaratan.</div>
    <textarea name="persyaratan_ppdb" class="form-control" rows="6" placeholder="1. Fotocopy KK...&#10;2. Fotocopy Akta..."><?= isset($settings->persyaratan_ppdb) ? htmlspecialchars($settings->persyaratan_ppdb) : '' ?></textarea>
</div>

<div class="col-md-12">
    <label class="form-label">Pamflet / Poster Promosi (Opsional)</label>
    <?php if(!empty($settings->pamflet_ppdb)): ?>
        <div class="mb-2 d-flex flex-column align-items-start gap-2">
            <img src="<?= base_url('uploads/ppdb_pamflet/'.$settings->pamflet_ppdb) ?>" alt="Pamflet" style="max-height: 150px; border-radius: 8px;">
            <a href="<?= base_url('admin_ppdb/delete_pamflet') ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('Yakin ingin menghapus pamflet ini?');">
                <i class="bi bi-trash"></i> Hapus Pamflet
            </a>
        </div>
    <?php endif; ?>
    <input type="file" name="pamflet_ppdb" class="form-control" accept="image/jpeg,image/png,image/jpg">
    <div class="form-text">Biarkan kosong jika tidak ingin mengubah pamflet. Format yang didukung: JPG, PNG. Max 2MB.</div>
</div>

<div class="col-md-12">
    <button class="btn btn-success w-100 py-3">
        Simpan Pengaturan
    </button>
</div>

</div>

</form>

</div>

</div>

<?php $this->load->view('templates/footer'); ?>