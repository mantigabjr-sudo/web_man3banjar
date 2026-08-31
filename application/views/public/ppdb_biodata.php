<?php
if(!function_exists('ppdb_bio_e')){
    function ppdb_bio_e($text){
        return htmlspecialchars((string)$text, ENT_QUOTES, 'UTF-8');
    }
}

if(!function_exists('ppdb_bio_value')){
    function ppdb_bio_value($text){
        return htmlspecialchars((string)$text, ENT_QUOTES, 'UTF-8');
    }
}

if(!function_exists('ppdb_bio_selected')){
    function ppdb_bio_selected($value, $current){
        return (string)$value === (string)$current ? 'selected' : '';
    }
}

$nama_lengkap   = $siswa->nama_lengkap ?? 'Peserta ' . htmlspecialchars($nama_ppdb ?? 'PPDB');
$no_pendaftaran = $siswa->no_pendaftaran ?? '-';

$foto_path = !empty($siswa->foto) ? FCPATH.'uploads/temp/ppdb/'.$siswa->foto : '';
$foto_url  = !empty($siswa->foto) ? base_url('uploads/temp/ppdb/'.$siswa->foto) : '';
?>

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">

<title>Lengkapi Biodata <?= htmlspecialchars($nama_ppdb ?? 'PPDB') ?></title>

<link rel="icon" type="image/png" href="<?= base_url('assets/img/favicon.png') ?>">
<link rel="shortcut icon" type="image/png" href="<?= base_url('assets/img/favicon.png') ?>">

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="<?= base_url('assets/css/ppdb-peserta.css?v=2') ?>">
</head>

<body>

<div class="peserta-shell">

    <aside class="peserta-sidebar">
        <div class="peserta-brand">
            <div class="peserta-brand-logo">
                <?php if(file_exists(FCPATH.'assets/img/logo-madrasah.png')): ?>
                    <img src="<?= base_url('assets/img/logo-madrasah.png') ?>" alt="Logo">
                <?php else: ?>
                    M3
                <?php endif; ?>
            </div>

            <div>
                <strong><?= htmlspecialchars($nama_ppdb ?? 'PPDB') ?> MAN 3</strong>
                <small>Portal Peserta</small>
            </div>
        </div>

        <div class="peserta-profile-mini">
            <?php if(!empty($siswa->foto) && file_exists($foto_path)): ?>
                <img src="<?= $foto_url ?>" alt="Foto Peserta">
            <?php else: ?>
                <div class="peserta-avatar">
                    <?= strtoupper(substr($nama_lengkap, 0, 1)) ?>
                </div>
            <?php endif; ?>

            <strong><?= ppdb_bio_e($nama_lengkap) ?></strong>
            <small><?= ppdb_bio_e($no_pendaftaran) ?></small>
        </div>

        <nav class="peserta-menu">
            <a href="<?= base_url('ppdb/dashboard') ?>">
                <span>⌂</span> Dashboard
            </a>

            <a href="<?= base_url('ppdb/biodata') ?>" class="active">
                <span>◎</span> Lengkapi Biodata
            </a>

            <a href="<?= base_url('ppdb/upload') ?>">
                <span>⇧</span> Upload Berkas
            </a>

            <a href="<?= base_url('ppdb/detail') ?>">
                <span>▣</span> Detail Pendaftaran
            </a>

            <a href="<?= base_url('ppdb/logout') ?>" class="logout">
                <span>×</span> Logout
            </a>
        </nav>
    </aside>

    <main class="peserta-main">

        <div class="peserta-mobile-head">
            <div>
                <strong><?= htmlspecialchars($nama_ppdb ?? 'PPDB') ?> MAN 3</strong>
                <small>Lengkapi Biodata</small>
            </div>

            <a href="<?= base_url('ppdb/logout') ?>">
                Logout
            </a>
        </div>

        <?php if($this->session->flashdata('success')): ?>
            <div class="alert alert-success peserta-alert">
                <?= $this->session->flashdata('success') ?>
            </div>
        <?php endif; ?>

        <?php if($this->session->flashdata('error')): ?>
            <div class="alert alert-danger peserta-alert">
                <?= $this->session->flashdata('error') ?>
            </div>
        <?php endif; ?>

        <section class="peserta-form-hero">
            <div>
                <div class="peserta-kicker">
                    Biodata Peserta
                </div>

                <h1>Lengkapi Biodata Pendaftaran</h1>

                <p>
                    Isi data pribadi, alamat, dan data orang tua dengan benar. Data ini akan digunakan panitia untuk proses verifikasi <?= htmlspecialchars($nama_ppdb ?? 'PPDB') ?>.
                </p>

                <div class="peserta-hero-meta">
                    <span>No. Pendaftaran: <strong><?= ppdb_bio_e($no_pendaftaran) ?></strong></span>
                    <span>Nama: <strong><?= ppdb_bio_e($nama_lengkap) ?></strong></span>
                </div>
            </div>

            <div class="form-hero-actions">
                <a href="<?= base_url('ppdb/dashboard') ?>" class="peserta-btn peserta-btn-soft">
                    Kembali Dashboard
                </a>

                <a href="<?= base_url('ppdb/detail') ?>" class="peserta-btn peserta-btn-soft">
                    Lihat Detail
                </a>
            </div>
        </section>

        <form method="post" action="<?= base_url('ppdb/save_biodata') ?>">

            <div class="bio-layout">

                <div class="bio-main">

                    <div class="bio-card" id="data-pribadi">
                        <div class="bio-card-head">
                            <div>
                                <h5>Data Pribadi</h5>
                                <small>Lengkapi identitas dasar calon peserta didik.</small>
                            </div>
                            <span>01</span>
                        </div>

                        <div class="bio-card-body">
                            <div class="bio-form-grid">

                                <div class="bio-field">
                                    <label>NIK</label>
                                    <input type="text"
                                           name="nik"
                                           class="bio-input"
                                           value="<?= ppdb_bio_value($siswa->nik ?? '') ?>"
                                           maxlength="16"
                                           inputmode="numeric"
                                           required
                                           oninput="this.value=this.value.replace(/[^0-9]/g,'').slice(0,16)">
                                    <small class="text-muted d-block mt-1" style="font-size: 12.5px; font-weight: 600;">Pastikan 16 digit NIK valid sesuai KTP/KK.</small>
                                </div>

                                <div class="bio-field">
                                    <label>Nomor KK</label>
                                    <input type="text"
                                           name="no_kk"
                                           class="bio-input"
                                           value="<?= ppdb_bio_value($siswa->no_kk ?? '') ?>"
                                           maxlength="16"
                                           inputmode="numeric"
                                           required
                                           oninput="this.value=this.value.replace(/[^0-9]/g,'').slice(0,16)">
                                    <small class="text-muted d-block mt-1" style="font-size: 12.5px; font-weight: 600;">Pastikan 16 digit No KK valid (ada di bagian atas KK).</small>
                                </div>

                                <div class="bio-field">
                                    <label>Agama</label>
                                    <select name="agama" class="bio-input" required>
                                        <option value="">Pilih Agama</option>
                                        <option value="Islam" <?= ppdb_bio_selected('Islam', $siswa->agama ?? '') ?>>Islam</option>
                                        <option value="Kristen" <?= ppdb_bio_selected('Kristen', $siswa->agama ?? '') ?>>Kristen</option>
                                        <option value="Katolik" <?= ppdb_bio_selected('Katolik', $siswa->agama ?? '') ?>>Katolik</option>
                                        <option value="Hindu" <?= ppdb_bio_selected('Hindu', $siswa->agama ?? '') ?>>Hindu</option>
                                        <option value="Budha" <?= ppdb_bio_selected('Budha', $siswa->agama ?? '') ?>>Budha</option>
                                    </select>
                                </div>

                                <div class="bio-field">
                                    <label>Anak Ke</label>
                                    <input type="number"
                                           name="anak_ke"
                                           class="bio-input"
                                           min="1"
                                           value="<?= ppdb_bio_value($siswa->anak_ke ?? '') ?>"
                                           required>
                                </div>

                                <div class="bio-field">
                                    <label>Jumlah Saudara</label>
                                    <input type="number"
                                           name="jumlah_saudara"
                                           class="bio-input"
                                           min="0"
                                           value="<?= ppdb_bio_value($siswa->jumlah_saudara ?? '') ?>"
                                           required>
                                </div>

                            </div>
                        </div>
                    </div>

                    <div class="bio-card" id="alamat">
                        <div class="bio-card-head">
                            <div>
                                <h5>Data Alamat</h5>
                                <small>Isi alamat lengkap sesuai domisili peserta.</small>
                            </div>
                            <span>02</span>
                        </div>

                        <div class="bio-card-body">
                            <div class="bio-form-grid">

                                <div class="bio-field full">
                                    <label>Alamat Lengkap</label>
                                    <textarea name="alamat"
                                              class="bio-textarea"
                                              required><?= ppdb_bio_value($siswa->alamat ?? '') ?></textarea>
                                    <small>Contoh: Jalan, gang, nomor rumah, atau keterangan alamat lainnya.</small>
                                </div>

                                <div class="bio-field small">
                                    <label>RT</label>
                                    <input type="text"
                                           name="rt"
                                           class="bio-input"
                                           value="<?= ppdb_bio_value($siswa->rt ?? '') ?>"
                                           required>
                                </div>

                                <div class="bio-field small">
                                    <label>RW</label>
                                    <input type="text"
                                           name="rw"
                                           class="bio-input"
                                           value="<?= ppdb_bio_value($siswa->rw ?? '') ?>"
                                           required>
                                </div>

                                <div class="bio-field">
                                    <label>Desa / Kelurahan</label>
                                    <input type="text"
                                           name="desa"
                                           class="bio-input"
                                           value="<?= ppdb_bio_value($siswa->desa ?? '') ?>"
                                           required>
                                </div>

                                <div class="bio-field">
                                    <label>Kecamatan</label>
                                    <input type="text"
                                           name="kecamatan"
                                           class="bio-input"
                                           value="<?= ppdb_bio_value($siswa->kecamatan ?? '') ?>"
                                           required>
                                </div>

                                <div class="bio-field">
                                    <label>Kabupaten / Kota</label>
                                    <input type="text"
                                           name="kabupaten"
                                           class="bio-input"
                                           value="<?= ppdb_bio_value($siswa->kabupaten ?? '') ?>"
                                           required>
                                </div>

                                <div class="bio-field">
                                    <label>Provinsi</label>
                                    <input type="text"
                                           name="provinsi"
                                           class="bio-input"
                                           value="<?= ppdb_bio_value($siswa->provinsi ?? '') ?>"
                                           required>
                                </div>

                                <div class="bio-field">
                                    <label>Kode Pos</label>
                                    <input type="text"
                                           name="kode_pos"
                                           class="bio-input"
                                           value="<?= ppdb_bio_value($siswa->kode_pos ?? '') ?>"
                                           maxlength="5"
                                           inputmode="numeric"
                                           required
                                           oninput="this.value=this.value.replace(/[^0-9]/g,'').slice(0,5)">
                                    <small class="text-muted d-block mt-1" style="font-size: 12.5px; font-weight: 600;">Masukkan 5 digit angka kode pos wilayah domisili.</small>
                                </div>

                            </div>
                        </div>
                    </div>

                    <div class="bio-card" id="orang-tua">
                        <div class="bio-card-head">
                            <div>
                                <h5>Data Orang Tua</h5>
                                <small>Lengkapi data ayah dan ibu kandung.</small>
                            </div>
                            <span>03</span>
                        </div>

                        <div class="bio-card-body">
                            <div class="bio-form-grid">

                                <div class="bio-field">
                                    <label>Nama Ayah Kandung</label>
                                    <input type="text"
                                           name="nama_ayah"
                                           class="bio-input"
                                           value="<?= ppdb_bio_value($siswa->nama_ayah ?? '') ?>"
                                           required>
                                </div>

                                <div class="bio-field">
                                    <label>Pekerjaan Ayah</label>
                                    <input type="text"
                                           name="pekerjaan_ayah"
                                           class="bio-input"
                                           value="<?= ppdb_bio_value($siswa->pekerjaan_ayah ?? '') ?>"
                                           required>
                                </div>

                                <div class="bio-field">
                                    <label>Nama Ibu Kandung</label>
                                    <input type="text"
                                           name="nama_ibu"
                                           class="bio-input"
                                           value="<?= ppdb_bio_value($siswa->nama_ibu ?? '') ?>"
                                           required>
                                </div>

                                <div class="bio-field">
                                    <label>Pekerjaan Ibu</label>
                                    <input type="text"
                                           name="pekerjaan_ibu"
                                           class="bio-input"
                                           value="<?= ppdb_bio_value($siswa->pekerjaan_ibu ?? '') ?>"
                                           required>
                                </div>

                                <div class="bio-field full">
                                    <label>Penghasilan Orang Tua</label>
                                    <select name="penghasilan_ortu" class="bio-input" required>
                                        <option value="">Pilih Penghasilan</option>
                                        <option value="< 1 Juta" <?= ppdb_bio_selected('< 1 Juta', $siswa->penghasilan_ortu ?? '') ?>>&lt; 1 Juta</option>
                                        <option value="1-3 Juta" <?= ppdb_bio_selected('1-3 Juta', $siswa->penghasilan_ortu ?? '') ?>>1 - 3 Juta</option>
                                        <option value="3-5 Juta" <?= ppdb_bio_selected('3-5 Juta', $siswa->penghasilan_ortu ?? '') ?>>3 - 5 Juta</option>
                                        <option value="> 5 Juta" <?= ppdb_bio_selected('> 5 Juta', $siswa->penghasilan_ortu ?? '') ?>>&gt; 5 Juta</option>
                                    </select>
                                </div>

                            </div>
                        </div>
                    </div>

                </div>

                <aside class="bio-sidebar">

                    <div class="bio-side-card">
                        <h5>Ringkasan</h5>
                        <p>
                            Pastikan data yang diisi benar. Setelah disimpan, lanjutkan ke tahap upload berkas.
                        </p>

                        <div class="bio-side-list">
                            <a href="#data-pribadi">
                                <span>01</span>
                                Data Pribadi
                            </a>

                            <a href="#alamat">
                                <span>02</span>
                                Data Alamat
                            </a>

                            <a href="#orang-tua">
                                <span>03</span>
                                Data Orang Tua
                            </a>
                        </div>
                    </div>

                    <div class="bio-side-card bio-note-card">
                        <h5>Catatan</h5>
                        <p>
                            NIK, Nomor KK, alamat, dan data orang tua akan digunakan untuk validasi data peserta. Jika ada perubahan setelah diverifikasi, hubungi panitia <?= htmlspecialchars($nama_ppdb ?? 'PPDB') ?>.
                        </p>
                    </div>

                </aside>

            </div>

            <div class="bio-submit-bar">
                <div>
                    <strong>Simpan Biodata</strong>
                    <small>Periksa kembali data sebelum disimpan.</small>
                </div>

                <div class="bio-submit-actions">
                    <a href="<?= base_url('ppdb/dashboard') ?>" class="peserta-btn peserta-btn-soft">
                        Batal
                    </a>

                    <button type="submit" class="peserta-btn peserta-btn-main">
                        Simpan Biodata
                    </button>
                </div>
            </div>

        </form>

    </main>

</div>

<nav class="peserta-bottom-nav">
    <a href="<?= base_url('ppdb/dashboard') ?>">
        <span>⌂</span>
        Dashboard
    </a>

    <a href="<?= base_url('ppdb/biodata') ?>" class="active">
        <span>◎</span>
        Biodata
    </a>

    <a href="<?= base_url('ppdb/upload') ?>">
        <span>⇧</span>
        Berkas
    </a>

    <a href="<?= base_url('ppdb/detail') ?>">
        <span>▣</span>
        Detail
    </a>
</nav>

</body>
</html>