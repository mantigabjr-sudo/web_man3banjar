<?php $this->load->view('templates/header'); ?>
<?php $this->load->view('templates/sidebar'); ?>

<?php
if(!function_exists('admin_ppdb_e')){
    function admin_ppdb_e($text){
        return htmlspecialchars((string)$text, ENT_QUOTES, 'UTF-8');
    }
}

if(!function_exists('admin_ppdb_value')){
    function admin_ppdb_value($text, $default = '-'){
        $text = trim((string)$text);
        return $text !== '' ? admin_ppdb_e($text) : $default;
    }
}

if(!function_exists('admin_ppdb_tanggal')){
    function admin_ppdb_tanggal($date){
        if(empty($date) || $date == '0000-00-00'){
            return '-';
        }

        if(function_exists('tanggal_indo')){
            return tanggal_indo($date);
        }

        return date('d-m-Y', strtotime($date));
    }
}

$status = !empty($p->status) ? $p->status : '-';

$status_class = 'pill-gray';

if($status == 'Diterima'){
    $status_class = 'pill-green';
} elseif($status == 'Ditolak'){
    $status_class = 'pill-red';
} elseif($status == 'Perlu Perbaikan'){
    $status_class = 'pill-orange';
} elseif($status == 'Menunggu Verifikasi Berkas' || $status == 'Upload Berkas'){
    $status_class = 'pill-blue';
}

$is_migrated = !empty($p->is_migrated) && $p->is_migrated == 1;

$foto_path = !empty($p->foto) ? FCPATH.'uploads/temp/ppdb/'.$p->foto : '';
$foto_url  = !empty($p->foto) ? base_url('uploads/temp/ppdb/'.$p->foto) : '';

$files = [
    'foto'             => 'Pas Foto',
    'kk_file'          => 'Kartu Keluarga',
    'akta_file'        => 'Akta Kelahiran',
	'sk_kelas9_file'   => 'Surat Keterangan Kelas 9',
    'rapor_file'       => 'Rapor / Nilai',
    'skl_file'         => 'Surat Keterangan Lulus',
    'nisn_file'        => 'Surat Aktif NISN',
    'ijazah_file'      => 'Ijazah',
    'sertifikat_file'  => 'Sertifikat Prestasi / Tahfidz'
];

$completed_files = 0;

foreach($files as $field => $label){
    if(!empty($p->$field)){
        $completed_files++;
    }
}

$total_files = count($files);
$file_percent = $total_files > 0 ? round(($completed_files / $total_files) * 100) : 0;

$verifikasi_berkas = [];
if(!empty($p->verifikasi_berkas_json)){
    $verifikasi_berkas = json_decode($p->verifikasi_berkas_json, true);
}
?>

<div class="content">

<?php $this->load->view('admin_ppdb/partials/detail_style'); ?>

<div class="admin-ppdb-detail">

    <?php if($this->session->flashdata('success')): ?>
        <div class="alert alert-success admin-alert">
            <?= $this->session->flashdata('success') ?>
        </div>
    <?php endif; ?>

    <?php if($this->session->flashdata('error')): ?>
        <div class="alert alert-danger admin-alert">
            <?= $this->session->flashdata('error') ?>
        </div>
    <?php endif; ?>

    <section class="admin-detail-hero">
        <div class="admin-detail-hero-inner">

            <div>
                <?php if(!empty($p->foto) && file_exists($foto_path)): ?>
                    <img src="<?= $foto_url ?>" class="admin-profile-photo" alt="Foto Peserta">
                <?php else: ?>
                    <div class="admin-profile-empty">
                        Tidak ada foto
                    </div>
                <?php endif; ?>
            </div>

            <div class="admin-hero-info">
                <h2><?= admin_ppdb_value($p->nama_lengkap ?? '') ?></h2>

                <div class="admin-hero-meta">
                    <span>No. Pendaftaran: <?= admin_ppdb_value($p->no_pendaftaran ?? '') ?></span>
                    <span>•</span>
                    <span>NISN: <?= admin_ppdb_value($p->nisn ?? '') ?></span>
                </div>

                <div class="admin-status-row">
                    <span class="admin-pill <?= $status_class ?>">
                        <?= admin_ppdb_value($status) ?>
                    </span>

                    <?php if($is_migrated): ?>
                        <span class="admin-pill pill-blue">
                            Sudah Migrasi
                        </span>
                    <?php else: ?>
                        <span class="admin-pill pill-gray">
                            Belum Migrasi
                        </span>
                    <?php endif; ?>
                </div>

                <div class="admin-hero-mini">
                    <div class="admin-mini-item">
                        <small>Asal Sekolah</small>
                        <strong><?= admin_ppdb_value($p->asal_sekolah ?? '') ?></strong>
                    </div>

                    <div class="admin-mini-item">
                        <small>No HP</small>
                        <strong><?= admin_ppdb_value($p->no_hp ?? '') ?></strong>
                    </div>

                    <div class="admin-mini-item">
                        <small>Kelengkapan Berkas</small>
                        <strong><?= $completed_files ?>/<?= $total_files ?> Berkas</strong>
                    </div>
                </div>
            </div>

            <div class="admin-action-panel">
                <a href="<?= base_url('admin_ppdb/edit/'.$p->id) ?>" class="admin-action-btn action-blue">
                    Edit Data
                </a>

                <a href="<?= base_url('admin_ppdb/upload_berkas/'.$p->id) ?>" class="admin-action-btn action-yellow">
                    Upload Berkas
                </a>

                <a href="<?= base_url('ppdb/cetak_kartu/'.$p->id) ?>" target="_blank" class="admin-action-btn action-green">
                    Cetak Kartu Peserta
                </a>

                <a href="<?= base_url('admin_ppdb/pdf/'.$p->id) ?>" class="admin-action-btn action-white">
                    Download PDF
                </a>

                <?php if(($p->status == 'Diterima' || $p->status == 'Ditolak') && !$is_migrated): ?>
                    <a href="<?= base_url('admin_ppdb/batal_status/'.$p->id) ?>"
                       class="admin-action-btn action-yellow"
                       onclick="return confirm('Batalkan status peserta ini?')">
                        Batal Status
                    </a>
                <?php endif; ?>

                <?php if($p->status != 'Diterima' && $p->status != 'Ditolak'): ?>
                    <a href="<?= base_url('admin_ppdb/perbaikan/'.$p->id) ?>"
                       class="admin-action-btn action-red"
                       onclick="return confirm('Tandai peserta ini perlu perbaikan?')">
                        Perlu Perbaikan
                    </a>
                <?php endif; ?>

                <a href="<?= base_url('admin_ppdb') ?>" class="admin-action-btn action-soft">
                    Kembali
                </a>
            </div>

        </div>
    </section>

    <div class="admin-detail-layout">

        <main>

            <div id="printArea">

                <div class="admin-card">
                    <div class="admin-card-head">
                        <h5>Data Pendaftaran</h5>
                        <small>Informasi utama registrasi peserta PPDB.</small>
                    </div>

                    <div class="admin-card-body">
                        <div class="admin-info-grid">
                            <div class="admin-info-item">
                                <small>No Pendaftaran</small>
                                <strong><?= admin_ppdb_value($p->no_pendaftaran ?? '') ?></strong>
                            </div>

                            <div class="admin-info-item">
                                <small>NISN</small>
                                <strong><?= admin_ppdb_value($p->nisn ?? '') ?></strong>
                            </div>

                            <div class="admin-info-item full">
                                <small>Nama Lengkap</small>
                                <strong><?= admin_ppdb_value($p->nama_lengkap ?? '') ?></strong>
                            </div>

                            <div class="admin-info-item">
                                <small>Jenis Kelamin</small>
                                <strong>
                                    <?= ($p->jk ?? '') == 'L' ? 'Laki-laki' : (($p->jk ?? '') == 'P' ? 'Perempuan' : '-') ?>
                                </strong>
                            </div>

                            <div class="admin-info-item">
                                <small>Tempat, Tanggal Lahir</small>
                                <strong>
                                    <?= admin_ppdb_value($p->tempat_lahir ?? '') ?>,
                                    <?= admin_ppdb_tanggal($p->tanggal_lahir ?? '') ?>
                                </strong>
                            </div>

                            <div class="admin-info-item">
                                <small>Asal Sekolah</small>
                                <strong><?= admin_ppdb_value($p->asal_sekolah ?? '') ?></strong>
                            </div>

                            <div class="admin-info-item">
                                <small>No HP</small>
                                <strong><?= admin_ppdb_value($p->no_hp ?? '') ?></strong>
                            </div>

                            <div class="admin-info-item">
                                <small>Nama Ayah / Wali Awal</small>
                                <strong><?= admin_ppdb_value($p->nama_ortu ?? '') ?></strong>
                            </div>

                            <div class="admin-info-item">
                                <small>Jalur Pendaftaran</small>
                                <strong class="text-success"><?= admin_ppdb_value($p->jalur_pendaftaran ?? 'Reguler') ?></strong>
                            </div>

                            <div class="admin-info-item">
                                <small>Peminatan (Jurusan) Pilihan</small>
                                <strong>1. <?= admin_ppdb_value($p->pilihan_jurusan_1 ?? 'MIPA') ?> | 2. <?= admin_ppdb_value($p->pilihan_jurusan_2 ?? 'IPS') ?></strong>
                            </div>

                            <div class="admin-info-item">
                                <small>Nomor Peserta Tes</small>
                                <strong><?= !empty($p->no_peserta_tes) ? '<span class="badge bg-success fs-6">'.$p->no_peserta_tes.'</span>' : '<span class="text-muted">Belum terbit</span>' ?></strong>
                            </div>

                            <div class="admin-info-item">
                                <small>Jadwal &amp; Ruang Tes</small>
                                <strong>
                                    <?= !empty($p->tanggal_tes) ? admin_ppdb_tanggal($p->tanggal_tes) : 'Sesuai Pengumuman' ?> 
                                    (<?= admin_ppdb_value($p->jam_tes ?? '08:00 WITA') ?>) - <?= admin_ppdb_value($p->ruang_tes ?? 'Kampus MAN 3 Banjar') ?>
                                </strong>
                            </div>

                            <div class="admin-info-item full">
                                <small>Catatan Verifikasi Panitia</small>
                                <strong><?= admin_ppdb_value($p->catatan_verifikasi ?? '-') ?></strong>
                            </div>

                            <div class="admin-info-item">
                                <small>Status</small>
                                <strong><?= admin_ppdb_value($status) ?></strong>
                            </div>

                            <div class="admin-info-item">
                                <small>Status Migrasi</small>
                                <strong><?= $is_migrated ? 'Sudah Migrasi' : 'Belum Migrasi' ?></strong>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="admin-card">
                    <div class="admin-card-head">
                        <h5>Data Pribadi</h5>
                        <small>Identitas pribadi calon peserta didik.</small>
                    </div>

                    <div class="admin-card-body">
                        <div class="admin-info-grid">
                            <div class="admin-info-item">
                                <small>NIK</small>
                                <strong><?= admin_ppdb_value($p->nik ?? '') ?></strong>
                            </div>

                            <div class="admin-info-item">
                                <small>No KK</small>
                                <strong><?= admin_ppdb_value($p->no_kk ?? '') ?></strong>
                            </div>

                            <div class="admin-info-item">
                                <small>Agama</small>
                                <strong><?= admin_ppdb_value($p->agama ?? '') ?></strong>
                            </div>

                            <div class="admin-info-item">
                                <small>Anak Ke</small>
                                <strong><?= admin_ppdb_value($p->anak_ke ?? '') ?></strong>
                            </div>

                            <div class="admin-info-item">
                                <small>Jumlah Saudara</small>
                                <strong><?= admin_ppdb_value($p->jumlah_saudara ?? '') ?></strong>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="admin-card">
                    <div class="admin-card-head">
                        <h5>Alamat</h5>
                        <small>Alamat lengkap peserta sesuai formulir PPDB.</small>
                    </div>

                    <div class="admin-card-body">
                        <div class="admin-info-grid">
                            <div class="admin-info-item full">
                                <small>Alamat Lengkap</small>
                                <strong>
                                    <?= admin_ppdb_value($p->alamat ?? '') ?>,
                                    RT <?= admin_ppdb_value($p->rt ?? '') ?>/RW <?= admin_ppdb_value($p->rw ?? '') ?>,
                                    <?= admin_ppdb_value($p->desa ?? '') ?>,
                                    <?= admin_ppdb_value($p->kecamatan ?? '') ?>,
                                    <?= admin_ppdb_value($p->kabupaten ?? '') ?>,
                                    <?= admin_ppdb_value($p->provinsi ?? '') ?>,
                                    <?= admin_ppdb_value($p->kode_pos ?? '') ?>
                                </strong>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="admin-card">
                    <div class="admin-card-head">
                        <h5>Data Orang Tua</h5>
                        <small>Informasi orang tua/wali peserta.</small>
                    </div>

                    <div class="admin-card-body">
                        <div class="admin-info-grid">
                            <div class="admin-info-item">
                                <small>Nama Ayah</small>
                                <strong><?= admin_ppdb_value($p->nama_ayah ?? '') ?></strong>
                            </div>

                            <div class="admin-info-item">
                                <small>Pekerjaan Ayah</small>
                                <strong><?= admin_ppdb_value($p->pekerjaan_ayah ?? '') ?></strong>
                            </div>

                            <div class="admin-info-item">
                                <small>Nama Ibu</small>
                                <strong><?= admin_ppdb_value($p->nama_ibu ?? '') ?></strong>
                            </div>

                            <div class="admin-info-item">
                                <small>Pekerjaan Ibu</small>
                                <strong><?= admin_ppdb_value($p->pekerjaan_ibu ?? '') ?></strong>
                            </div>

                            <div class="admin-info-item full">
                                <small>Penghasilan Orang Tua</small>
                                <strong><?= admin_ppdb_value($p->penghasilan_ortu ?? '') ?></strong>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="admin-card">
                    <div class="admin-card-head d-flex justify-content-between align-items-center flex-wrap gap-2">
                        <div>
                            <h5>Berkas Upload & Verifikasi</h5>
                            <small>Dokumen peserta yang sudah diunggah beserta status verifikasinya.</small>
                        </div>
                        <?php if($p->status != 'Diterima'): ?>
                            <button type="button" class="btn btn-sm btn-success rounded-3 text-white fw-bold px-3 py-1.5" data-bs-toggle="modal" data-bs-target="#modalVerifikasiSemuaBerkas" style="background:#16a34a; border:none; border-radius:10px;">
                                Verifikasi Berkas
                            </button>
                        <?php endif; ?>
                    </div>

                    <div class="admin-card-body">
                        <div class="admin-file-grid">
                            <?php foreach($files as $field => $label): ?>
                                <?php
                                $filename = !empty($p->$field) ? $p->$field : '';
                                $file_path = !empty($filename) ? FCPATH.'uploads/temp/ppdb/'.$filename : '';
                                $file_url = !empty($filename) ? base_url('uploads/temp/ppdb/'.$filename) : '';
                                $ext = !empty($filename) ? strtoupper(pathinfo($filename, PATHINFO_EXTENSION)) : '';
                                
                                $status_verif = isset($verifikasi_berkas[$field]['status']) ? $verifikasi_berkas[$field]['status'] : 'Menunggu';
                                $catatan_verif = isset($verifikasi_berkas[$field]['catatan']) ? $verifikasi_berkas[$field]['catatan'] : '';
                                
                                $badge_style = 'background-color: #f1f5f9; color: #475569;';
                                if($status_verif == 'Sesuai'){
                                    $badge_style = 'background-color: #dcfce7; color: #166534;';
                                } elseif($status_verif == 'Perlu Perbaikan'){
                                    $badge_style = 'background-color: #fee2e2; color: #991b1b;';
                                }
                                ?>

                                <div class="admin-file-card" style="position: relative;">
                                    <div class="admin-file-top">
                                        <div class="admin-file-icon">
                                            <?= !empty($ext) ? admin_ppdb_e(substr($ext,0,3)) : '—' ?>
                                        </div>

                                        <div>
                                            <strong><?= admin_ppdb_e($label) ?></strong>
                                            <small>
                                                <?= !empty($filename) ? admin_ppdb_e($filename) : 'Belum upload' ?>
                                            </small>
                                        </div>
                                    </div>

                                    <?php if(!empty($filename)): ?>
                                        <div class="mt-2.5 p-2 rounded-3 d-flex flex-column gap-1" style="<?= $badge_style ?> font-size:12px; border-radius:10px;">
                                            <span class="fw-bold">Verifikasi: <?= $status_verif ?></span>
                                            <?php if($status_verif == 'Perlu Perbaikan' && !empty($catatan_verif)): ?>
                                                <span class="fw-semibold text-danger">Revisi: <?= admin_ppdb_e($catatan_verif) ?></span>
                                            <?php endif; ?>
                                        </div>
                                    <?php endif; ?>

                                    <div class="mt-3">
                                        <?php if(!empty($filename) && file_exists($file_path)): ?>
                                            <a target="_blank" href="<?= $file_url ?>" class="admin-file-action file-open w-100 text-center d-block" style="text-decoration:none; border-radius:8px;">
                                                Lihat File
                                            </a>
                                        <?php elseif(!empty($filename)): ?>
                                            <span class="admin-file-action file-missing w-100 text-center d-block" style="border-radius:8px;">
                                                File tidak ditemukan
                                            </span>
                                        <?php else: ?>
                                            <span class="admin-file-action file-missing w-100 text-center d-block" style="border-radius:8px;">
                                                Belum Upload
                                            </span>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>

            </div>

        </main>

        <aside class="sidebar-stack">

            <?php if($this->session->flashdata('password_baru_ppdb')): ?>
                <div class="admin-password-box">
                    <div>Password baru peserta:</div>

                    <div class="admin-copy-group">
                        <input type="text"
                               class="form-control"
                               id="passwordBaruPpdb"
                               value="<?= admin_ppdb_e($this->session->flashdata('password_baru_ppdb')) ?>"
                               readonly>

                        <button type="button"
                                class="btn btn-dark"
                                onclick="copyPasswordPpdb()">
                            Copy
                        </button>
                    </div>

                    <small>
                        Password hanya ditampilkan sekali setelah reset.
                    </small>
                </div>
            <?php endif; ?>

            <div class="admin-card">
                <div class="admin-card-head">
                    <h5>Akun Peserta</h5>
                    <small>Reset password login peserta PPDB.</small>
                </div>

                <div class="admin-card-body">
                    <div class="admin-side-list">
                        <div class="admin-side-item">
                            <small>Username / NISN</small>
                            <strong><?= admin_ppdb_value($p->nisn ?? '') ?></strong>
                        </div>

                        <div class="admin-side-item">
                            <small>Password</small>
                            <strong>Password tidak dapat ditampilkan</strong>
                        </div>

                        <?php if(!empty($p->password_updated_at)): ?>
                            <div class="admin-side-item">
                                <small>Terakhir Reset Password</small>
                                <strong><?= date('d M Y H:i', strtotime($p->password_updated_at)) ?></strong>
                            </div>
                        <?php endif; ?>

                        <button type="button"
                                class="admin-action-btn action-white w-100"
                                data-bs-toggle="modal"
                                data-bs-target="#modalResetPasswordPpdb">
                            Reset Password
                        </button>
                    </div>
                </div>
            </div>

            <div class="admin-card">
                <div class="admin-card-head">
                    <h5>Progress Berkas</h5>
                    <small>Kelengkapan dokumen peserta.</small>
                </div>

                <div class="admin-card-body">
                    <div class="admin-progress-label">
                        <span>Kelengkapan</span>
                        <span><?= $file_percent ?>%</span>
                    </div>

                    <div class="admin-progress-track">
                        <span style="width:<?= $file_percent ?>%;"></span>
                    </div>

                    <div class="admin-side-list">
                        <div class="admin-side-item">
                            <small>Berkas Terunggah</small>
                            <strong><?= $completed_files ?> dari <?= $total_files ?> berkas</strong>
                        </div>

                        <div class="admin-side-item">
                            <small>Status Peserta</small>
                            <strong><?= admin_ppdb_value($status) ?></strong>
                        </div>

                        <div class="admin-side-item">
                            <small>Status Migrasi</small>
                            <strong><?= $is_migrated ? 'Sudah Migrasi' : 'Belum Migrasi' ?></strong>
                        </div>
                    </div>
                </div>
            </div>

            <div class="admin-card">
                <div class="admin-card-head">
                    <h5>Zona Berbahaya</h5>
                    <small>Penghapusan data PPDB peserta.</small>
                </div>

                <div class="admin-card-body">
                    <div class="admin-zone-danger">
                        <h6>Hapus Peserta</h6>
                        <p>
                            Menghapus peserta akan menghapus data PPDB dan file upload sementara. Data yang sudah dimigrasikan tidak boleh dihapus.
                        </p>

                        <a href="<?= base_url('admin_ppdb/delete/'.$p->id) ?>"
                           class="admin-delete-btn"
                           onclick="return confirm('Yakin hapus peserta ini beserta file upload temp?')">
                            Hapus Peserta
                        </a>
                    </div>
                </div>
            </div>

        </aside>

    </div>

</div>

<!-- MODAL RESET PASSWORD -->
<div class="modal fade" id="modalResetPasswordPpdb" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content rounded-4 border-0">

            <form method="post" action="<?= base_url('admin_ppdb/reset_password/'.$p->id) ?>">

                <div class="modal-header">
                    <div>
                        <h5 class="modal-title fw-bold text-success">
                            Reset Password Peserta
                        </h5>
                        <small class="text-muted fw-bold">
                            Password baru akan dipakai peserta untuk login PPDB.
                        </small>
                    </div>

                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">

                    <div class="mb-3">
                        <label class="form-label fw-bold">Nama Peserta</label>
                        <input type="text"
                               class="form-control"
                               value="<?= admin_ppdb_value($p->nama_lengkap ?? '') ?>"
                               readonly>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">NISN</label>
                        <input type="text"
                               class="form-control"
                               value="<?= admin_ppdb_value($p->nisn ?? '') ?>"
                               readonly>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Password Baru</label>

                        <div class="input-group">
                            <input type="text"
                                   name="password_baru"
                                   id="inputPasswordBaru"
                                   class="form-control"
                                   minlength="6"
                                   required
                                   placeholder="Minimal 6 karakter">

                            <button type="button"
                                    class="btn btn-outline-success"
                                    onclick="generatePasswordPpdb()">
                                Generate
                            </button>
                        </div>

                        <small class="text-muted fw-bold">
                            Password baru akan tampil sekali setelah disimpan.
                        </small>
                    </div>

                </div>

                <div class="modal-footer">
                    <button type="button"
                            class="btn btn-light rounded-pill px-4"
                            data-bs-dismiss="modal">
                        Batal
                    </button>

                    <button class="btn btn-success rounded-pill px-4 fw-bold"
                            onclick="return confirm('Reset password peserta ini?')">
                        Simpan Password Baru
                    </button>
                </div>

            </form>

        </div>
    </div>
</div>

<script>
function generatePasswordPpdb(){
    const input = document.getElementById('inputPasswordBaru');

    if(!input){
        return;
    }

    const chars = 'ABCDEFGHJKLMNPQRSTUVWXYZabcdefghijkmnpqrstuvwxyz23456789';
    let pass = '';

    for(let i = 0; i < 8; i++){
        pass += chars.charAt(Math.floor(Math.random() * chars.length));
    }

    input.value = pass;
}

function copyPasswordPpdb(){
    const input = document.getElementById('passwordBaruPpdb');

    if(!input){
        return;
    }

    input.select();
    input.setSelectionRange(0, 99999);
    document.execCommand('copy');

    alert('Password berhasil disalin.');
}
</script>

</div>

<!-- MODAL VERIFIKASI SEMUA BERKAS -->
<div class="modal fade" id="modalVerifikasiSemuaBerkas" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content rounded-4 border-0">
            <form method="post" action="<?= base_url('admin_ppdb/update_verifikasi_berkas/'.$p->id) ?>">
                <div class="modal-header">
                    <div>
                        <h5 class="modal-title fw-bold text-success">Verifikasi Berkas Pendaftaran</h5>
                        <small class="text-muted fw-bold">Periksa berkas fisik dan tentukan kesesuaian dengan data isian.</small>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body px-4 py-3" style="max-height: 70vh; overflow-y: auto;">
                    <table class="table align-middle">
                        <thead>
                            <tr>
                                <th style="width: 30%;">Nama Dokumen</th>
                                <th style="width: 15%;">Tindakan</th>
                                <th style="width: 25%;">Status Verifikasi</th>
                                <th style="width: 30%;">Catatan Revisi (Jika Salah)</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach($files as $field => $label): ?>
                                <?php
                                $filename = !empty($p->$field) ? $p->$field : '';
                                $file_path = !empty($filename) ? FCPATH.'uploads/temp/ppdb/'.$filename : '';
                                $file_url = !empty($filename) ? base_url('uploads/temp/ppdb/'.$filename) : '';
                                
                                $status_verif = isset($verifikasi_berkas[$field]['status']) ? $verifikasi_berkas[$field]['status'] : 'Menunggu';
                                $catatan_verif = isset($verifikasi_berkas[$field]['catatan']) ? $verifikasi_berkas[$field]['catatan'] : '';
                                ?>
                                <tr>
                                    <td>
                                        <span class="fw-bold d-block"><?= admin_ppdb_e($label) ?></span>
                                        <small class="text-muted d-block font-monospace" style="font-size:11px;">
                                            <?= !empty($filename) ? admin_ppdb_e($filename) : 'Belum diunggah' ?>
                                        </small>
                                    </td>
                                    <td>
                                        <?php if(!empty($filename) && file_exists($file_path)): ?>
                                            <div class="d-flex flex-column gap-1">
                                                <a href="<?= $file_url ?>" target="_blank" class="btn btn-xs btn-outline-success py-1 px-2 fw-bold text-center" style="font-size:10px; border-radius:6px;">
                                                    Lihat File
                                                </a>
                                                <button type="button" class="btn btn-xs btn-outline-dark btn-ocr-trigger-ppdb py-1 px-2 fw-bold text-center" style="font-size:10px; border-radius:6px; background:transparent;" data-id="<?= $p->id ?>" data-field="<?= $field ?>" data-doc-name="<?= admin_ppdb_e($label) ?>">
                                                    🔍 OCR
                                                </button>
                                            </div>
                                        <?php else: ?>
                                            <span class="text-muted font-monospace" style="font-size:11px;">—</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <?php if(!empty($filename)): ?>
                                            <select name="status_berkas[<?= $field ?>]" class="form-select py-1 px-2 fw-semibold" style="font-size: 13px; border-radius:8px;" required>
                                                <option value="Menunggu" <?= $status_verif == 'Menunggu' ? 'selected' : '' ?>>Menunggu</option>
                                                <option value="Sesuai" <?= $status_verif == 'Sesuai' ? 'selected' : '' ?>>Sesuai (Lolos)</option>
                                                <option value="Perlu Perbaikan" <?= $status_verif == 'Perlu Perbaikan' ? 'selected' : '' ?>>Perlu Perbaikan (Tolak)</option>
                                            </select>
                                        <?php else: ?>
                                            <span class="badge bg-light text-muted border rounded-3 px-2 py-1fw-medium" style="font-size: 11px;">Belum Diupload</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <?php if(!empty($filename)): ?>
                                            <input type="text" name="catatan_berkas[<?= $field ?>]" class="form-control py-1 px-2" style="font-size: 13px; border-radius:8px;" value="<?= admin_ppdb_e($catatan_verif) ?>" placeholder="Contoh: Nama tidak sesuai">
                                        <?php else: ?>
                                            <span class="text-muted font-monospace" style="font-size:11px;">—</span>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-success rounded-pill px-4 fw-bold">Simpan Verifikasi Berkas</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Hasil Analisis OCR PPDB -->
<div class="modal fade" id="modalOcrResultPpdb" tabindex="-1" aria-hidden="true" style="z-index: 1060;">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content rounded-4 border-0" style="border-radius: 20px; box-shadow:0 15px 50px rgba(0,0,0,0.15);">
            <div class="modal-header border-bottom-0">
                <h5 class="modal-title fw-bold text-success d-flex align-items-center gap-2">
                    <svg width="22" height="22" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 15.75l-2.489-2.489m0 0a3.375 3.375 0 10-4.773-4.773 3.375 3.375 0 004.774 4.774zM21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    Hasil Analisis OCR Otomatis (<span id="ocrDocNamePpdb">Dokumen</span>)
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body px-4 py-3">
                <div id="ocrLoadingPpdb" class="text-center py-5">
                    <div class="spinner-border text-success" role="status" style="width: 3rem; height: 3rem;"></div>
                    <p class="mt-3 text-secondary fw-semibold">Sedang mengekstrak & mencocokkan data berkas fisik dengan database...</p>
                    <small class="text-muted">Proses ini menggunakan mesin Tesseract OCR lokal di server.</small>
                </div>
                
                <div id="ocrContentPpdb" style="display: none;">
                    <div class="alert alert-info rounded-3 py-2 px-3 mb-3 fw-bold" style="font-size: 13px;">
                        Tingkat kecocokan data yang terdaftar dengan teks pada dokumen fisik:
                    </div>
                    
                    <div class="table-responsive mb-3">
                        <table class="table table-bordered table-striped align-middle">
                            <thead class="table-success">
                                <tr>
                                    <th>Field Data</th>
                                    <th>Nilai di Database</th>
                                    <th>Status Pencocokan</th>
                                    <th>Keterangan</th>
                                </tr>
                            </thead>
                            <tbody id="ocrResultTableBodyPpdb">
                                <!-- Baris hasil diisi via Javascript -->
                            </tbody>
                        </table>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label fw-bold" style="font-size: 13px; color:#475569;">Teks Hasil Ekstraksi Kasar (Raw OCR Text)</label>
                        <textarea id="ocrRawTextPpdb" class="form-control bg-light" rows="5" style="border-radius:10px; font-size: 12.5px; font-family: monospace;" readonly></textarea>
                    </div>
                </div>
            </div>
            <div class="modal-footer border-top-0 pt-0">
                <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const ocrTriggersPpdb = document.querySelectorAll('.btn-ocr-trigger-ppdb');
    const modalOcrPpdb = new bootstrap.Modal(document.getElementById('modalOcrResultPpdb'));
    
    ocrTriggersPpdb.forEach(btn => {
        btn.addEventListener('click', function() {
            const id = this.getAttribute('data-id');
            const field = this.getAttribute('data-field');
            const docName = this.getAttribute('data-doc-name');
            
            document.getElementById('ocrDocNamePpdb').textContent = docName;
            document.getElementById('ocrLoadingPpdb').style.display = 'block';
            document.getElementById('ocrContentPpdb').style.display = 'none';
            
            // Sembunyikan sementara modal verifikasi berkas agar tidak tumpang tindih
            const modalVerifEl = document.getElementById('modalVerifikasiSemuaBerkas');
            const modalVerif = bootstrap.Modal.getInstance(modalVerifEl);
            if (modalVerif) {
                modalVerif.hide();
            }
            
            modalOcrPpdb.show();
            
            fetch('<?= base_url("admin_ppdb/ajax_check_ocr_match") ?>', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: new URLSearchParams({
                    'id': id,
                    'field': field
                })
            })
            .then(response => response.json())
            .then(data => {
                document.getElementById('ocrLoadingPpdb').style.display = 'none';
                
                if (data.status === 'success') {
                    document.getElementById('ocrContentPpdb').style.display = 'block';
                    document.getElementById('ocrRawTextPpdb').value = data.extracted_text;
                    
                    let htmlTable = '';
                    for (const key in data.results) {
                        const item = data.results[key];
                        const badgeClass = item.matched ? 'bg-success' : 'bg-danger';
                        const badgeText = item.matched ? 'Cocok' : 'Tidak Cocok / Tidak Ditemukan';
                        
                        htmlTable += `
                            <tr>
                                <td class="fw-bold">${item.label}</td>
                                <td>${item.db_value}</td>
                                <td>
                                    <span class="badge ${badgeClass} text-white fw-bold rounded-pill px-2.5 py-1" style="font-size: 11px;">
                                        ${badgeText}
                                    </span>
                                </td>
                                <td><small class="text-secondary fw-semibold">${item.detail}</small></td>
                            </tr>
                        `;
                    }
                    document.getElementById('ocrResultTableBodyPpdb').innerHTML = htmlTable;
                } else {
                    modalOcrPpdb.hide();
                    alert('Gagal menganalisis berkas: ' + data.message);
                    if (modalVerif) modalVerif.show();
                }
            })
            .catch(error => {
                document.getElementById('ocrLoadingPpdb').style.display = 'none';
                modalOcrPpdb.hide();
                alert('Terjadi kesalahan jaringan atau server saat melakukan OCR.');
                console.error(error);
                if (modalVerif) modalVerif.show();
            });
        });
    });
    
    // Tampilkan kembali modal verifikasi setelah modal OCR ditutup
    const ocrResultModalEl = document.getElementById('modalOcrResultPpdb');
    ocrResultModalEl.addEventListener('hidden.bs.modal', function () {
        const modalVerifEl = document.getElementById('modalVerifikasiSemuaBerkas');
        const modalVerif = bootstrap.Modal.getInstance(modalVerifEl) || new bootstrap.Modal(modalVerifEl);
        modalVerif.show();
    });
});
</script>

<?php $this->load->view('templates/footer'); ?>