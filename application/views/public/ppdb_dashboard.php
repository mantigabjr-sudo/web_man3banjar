<?php
if(!function_exists('ppdb_dash_e')){
    function ppdb_dash_e($text){
        return htmlspecialchars((string)$text, ENT_QUOTES, 'UTF-8');
    }
}

if(!function_exists('ppdb_dash_value')){
    function ppdb_dash_value($text, $default = '-'){
        $text = trim((string)$text);
        return $text !== '' ? ppdb_dash_e($text) : $default;
    }
}

$nama            = htmlspecialchars($nama_ppdb ?? 'PMB');
$nama_lengkap    = $siswa->nama_lengkap ?? 'Calon Siswa ' . $nama;
$no_pendaftaran  = $siswa->no_pendaftaran ?? '-';
$nisn            = $siswa->nisn ?? '-';
$asal_sekolah    = $siswa->asal_sekolah ?? '-';
$no_hp           = $siswa->no_hp ?? '-';

$status_text     = $status_text ?? ($siswa->status ?? '-');
$status_seleksi  = $status_seleksi ?? '-';
$progress        = isset($progress) ? (int)$progress : 0;
$desc            = $desc ?? 'Silakan lengkapi data pendaftaran Anda.';
$action_link     = $action_link ?? base_url('pmb/biodata');
$action_text     = $action_text ?? 'Lanjutkan Pendaftaran';

if($progress < 0) $progress = 0;
if($progress > 100) $progress = 100;

$status_class = 'ppdb-status-warning';

if($status_text == 'Diterima'){
    $status_class = 'ppdb-status-success';
} elseif($status_text == 'Ditolak'){
    $status_class = 'ppdb-status-danger';
} elseif($status_text == 'Perlu Perbaikan'){
    $status_class = 'ppdb-status-orange';
} elseif($status_text == 'Upload Berkas' || $status_text == 'Menunggu Verifikasi Berkas'){
    $status_class = 'ppdb-status-info';
}

$foto_path = !empty($siswa->foto) ? FCPATH.'uploads/temp/ppdb/'.$siswa->foto : '';
$foto_url  = !empty($siswa->foto) ? base_url('uploads/temp/ppdb/'.$siswa->foto) : '';
?>

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">

<title>Dashboard Calon Siswa <?= $nama ?> | MAN 3 Banjar</title>

<link rel="icon" type="image/png" href="<?= base_url('assets/img/favicon.png') ?>">
<link rel="shortcut icon" type="image/png" href="<?= base_url('assets/img/favicon.png') ?>">
<link rel="apple-touch-icon" href="<?= base_url('assets/img/favicon.png') ?>">

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
<link rel="stylesheet" href="<?= base_url('assets/css/ppdb-peserta.css?v=1') ?>">
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

            <strong><?= ppdb_dash_value($nama_lengkap) ?></strong>
            <small><?= ppdb_dash_value($no_pendaftaran) ?></small>
        </div>

        <nav class="peserta-menu">
            <a href="<?= base_url('ppdb/dashboard') ?>" class="active">
                <span>⌂</span> Dashboard
            </a>

            <a href="<?= base_url('ppdb/biodata') ?>">
                <span>◎</span> Lengkapi Biodata
            </a>

            <a href="<?= base_url('ppdb/upload') ?>">
                <span>⇧</span> Upload Berkas
            </a>

            <a href="<?= base_url('ppdb/detail') ?>">
                <span>▣</span> Detail Pendaftaran
            </a>

            <a href="<?= base_url('ppdb/cetak_kartu') ?>" target="_blank" class="text-success fw-bold">
                <span>📇</span> Cetak Kartu Peserta
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
                <small>Dashboard Peserta</small>
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

        <?php 
        $verifikasi_berkas = [];
        if(!empty($siswa->verifikasi_berkas_json)){
            $verifikasi_berkas = json_decode($siswa->verifikasi_berkas_json, true);
        }
        $ada_perbaikan = false;
        if($status_text == 'Perlu Perbaikan' && !empty($verifikasi_berkas)){
            foreach($verifikasi_berkas as $f => $val){
                if(isset($val['status']) && $val['status'] == 'Perlu Perbaikan'){
                    $ada_perbaikan = true;
                }
            }
        }
        ?>

        <?php if($ada_perbaikan): ?>
            <div class="alert alert-danger rounded-4 border-0 p-4 mb-4 shadow-sm" style="border-radius: 16px; background-color: #fff1f2; border: 1px solid #fecdd3 !important;">
                <h5 class="fw-bold mb-2 text-danger d-flex align-items-center gap-2">
                    <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z"/></svg>
                    Perhatian: Dokumen Perlu Perbaikan
                </h5>
                <p class="mb-3 text-secondary" style="font-size: 14px;">Panitia menemukan ketidaksesuaian data pada berkas yang Anda unggah. Silakan perbaiki dan unggah kembali dokumen-dokumen berikut:</p>
                <ul class="mb-0 ps-3">
                    <?php foreach($verifikasi_berkas as $field => $item_vb): ?>
                        <?php if(isset($item_vb['status']) && $item_vb['status'] == 'Perlu Perbaikan'): ?>
                            <?php
                            $label_map = [
                                'foto' => 'Pas Foto',
                                'kk_file' => 'Kartu Keluarga',
                                'akta_file' => 'Akta Kelahiran',
                                'rapor_file' => 'Rapor / Nilai',
                                'skl_file' => 'Surat Keterangan Lulus',
                                'nisn_file' => 'Surat Aktif NISN',
                                'sk_kelas9_file' => 'Surat Keterangan Kelas 9',
                                'ijazah_file' => 'Ijazah',
                                'sertifikat_file' => 'Sertifikat Prestasi'
                            ];
                            $doc_label = isset($label_map[$field]) ? $label_map[$field] : $field;
                            ?>
                            <li class="mb-2 fw-semibold" style="font-size: 13.5px; color:#1e293b;">
                                <span class="text-dark"><?= $doc_label ?></span>: 
                                <span class="text-danger italic">"<?= ppdb_dash_e($item_vb['catatan'] ?? '') ?>"</span>
                            </li>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </ul>
                <div class="mt-3">
                    <a href="<?= base_url('ppdb/upload') ?>" class="btn btn-danger btn-sm rounded-3 fw-bold px-4 py-2" style="background:#dc2626; border:none; border-radius:8px;">Perbaiki Dokumen Sekarang</a>
                </div>
            </div>
        <?php endif; ?>

        <section class="peserta-hero">
            <div class="peserta-hero-content">
                <div class="peserta-kicker">
                    Dashboard Peserta <?= htmlspecialchars($nama_ppdb ?? 'PPDB') ?>
                </div>

                <h1>
                    Halo, <?= ppdb_dash_value($nama_lengkap) ?>
                </h1>

                <p>
                    Pantau progress pendaftaran, lengkapi biodata, upload berkas, dan cek status seleksi melalui dashboard peserta.
                </p>

                <div class="peserta-hero-meta">
                    <span>No. Pendaftaran: <strong><?= ppdb_dash_value($no_pendaftaran) ?></strong></span>
                    <span>NISN: <strong><?= ppdb_dash_value($nisn) ?></strong></span>
                </div>
            </div>

            <div class="peserta-status-panel">
                <small>Status Pendaftaran</small>
                <span class="peserta-status-pill <?= $status_class ?>">
                    <?= ppdb_dash_value($status_text) ?>
                </span>

                <div class="peserta-status-note">
                    <?= ppdb_dash_value($desc) ?>
                </div>
            </div>
        </section>

        <section class="peserta-grid">

            <div class="peserta-card peserta-progress-card">
                <div class="peserta-card-head">
                    <div>
                        <h5>Progress Pendaftaran</h5>
                        <small>Pastikan data dan berkas sudah lengkap.</small>
                    </div>

                    <strong><?= $progress ?>%</strong>
                </div>

                <div class="peserta-progress-track">
                    <span style="width:<?= $progress ?>%;"></span>
                </div>

                <p>
                    <?= ppdb_dash_value($desc) ?>
                </p>

                <div class="peserta-action-row">
                    <a href="<?= $action_link ?>" class="peserta-btn peserta-btn-main">
                        <?= ppdb_dash_value($action_text) ?>
                    </a>

                    <a href="<?= base_url('ppdb/detail') ?>" class="peserta-btn peserta-btn-soft">
                        Lihat Detail
                    </a>
                </div>
            </div>

            <div class="peserta-card peserta-selection-card">
                <div class="peserta-card-head">
                    <div>
                        <h5>Status Seleksi &amp; Ujian</h5>
                        <small>Informasi jadwal tes dan kartu peserta.</small>
                    </div>
                </div>

                <div class="selection-result">
                    <?= ppdb_dash_value($status_seleksi) ?>
                </div>

                <?php if(!empty($siswa->no_peserta_tes) || $siswa->status == 'Lulus Verifikasi' || $siswa->status == 'Menuju Tes' || $siswa->status == 'Diterima'): ?>
                    <div style="background: #ecfdf5; border: 1px solid #a7f3d0; border-radius: 10px; padding: 12px; margin: 12px 0;">
                        <div style="font-size: 11px; color: #065f46; font-weight: 700;">NOMOR PESERTA UJIAN:</div>
                        <div style="font-size: 16px; color: #047857; font-weight: 900;"><?= htmlspecialchars($siswa->no_peserta_tes ?? $siswa->no_pendaftaran, ENT_QUOTES, 'UTF-8') ?></div>
                        <div style="font-size: 12px; color: #334155; margin-top: 4px;">
                            <i class="bi bi-calendar-event"></i> Jadwal: <?= !empty($siswa->tanggal_tes) ? (function_exists('tanggal_indo') ? tanggal_indo($siswa->tanggal_tes) : $siswa->tanggal_tes) : 'Sesuai Jadwal Panitia' ?>
                        </div>
                    </div>
                <?php endif; ?>

                <div class="selection-desc mt-2">
                    <a href="<?= base_url('ppdb/cetak_kartu') ?>" target="_blank" class="btn btn-sm btn-success w-100 fw-bold py-2 shadow-sm" style="border-radius: 8px; background: #059669; border-color: #059669;">
                        <i class="bi bi-printer-fill me-1"></i> Cetak Kartu Peserta Ujian
                    </a>
                </div>
            </div>

        </section>

        <section class="peserta-info-grid">

            <div class="peserta-info-card">
                <span>NISN</span>
                <strong><?= ppdb_dash_value($nisn) ?></strong>
            </div>

            <div class="peserta-info-card">
                <span>Asal Sekolah</span>
                <strong><?= ppdb_dash_value($asal_sekolah) ?></strong>
            </div>

            <div class="peserta-info-card">
                <span>No HP</span>
                <strong><?= ppdb_dash_value($no_hp) ?></strong>
            </div>

        </section>
<?php if(!empty($pengumuman_ppdb)): ?>
<section class="peserta-card ppdb-announcement-card">

    <div class="peserta-card-head">
        <div>
            <h5>Pengumuman <?= htmlspecialchars($nama_ppdb ?? 'PPDB') ?></h5>
            <small>Informasi terbaru dari panitia <?= htmlspecialchars($nama_ppdb ?? 'PPDB') ?>.</small>
        </div>
    </div>

    <div class="ppdb-announcement-list">
        <?php foreach($pengumuman_ppdb as $g): ?>
            <div class="ppdb-announcement-item">
                <div class="ppdb-announcement-top">
                    <span class="ppdb-announcement-category">
                        <?= htmlspecialchars($g->kategori, ENT_QUOTES, 'UTF-8') ?>
                    </span>

                    <small>
                        <?= !empty($g->tanggal_mulai) ? date('d-m-Y', strtotime($g->tanggal_mulai)) : '-' ?>
                        <?php if(!empty($g->tanggal_selesai)): ?>
                            s/d <?= date('d-m-Y', strtotime($g->tanggal_selesai)) ?>
                        <?php endif; ?>
                    </small>
                </div>

                <h6><?= htmlspecialchars($g->judul, ENT_QUOTES, 'UTF-8') ?></h6>

                <p>
                    <?= nl2br(htmlspecialchars($g->isi, ENT_QUOTES, 'UTF-8')) ?>
                </p>

                <?php if(!empty($g->waktu) || !empty($g->lokasi)): ?>
                    <div class="ppdb-announcement-meta">
                        <?php if(!empty($g->waktu)): ?>
                            <span>Waktu: <?= htmlspecialchars($g->waktu, ENT_QUOTES, 'UTF-8') ?></span>
                        <?php endif; ?>

                        <?php if(!empty($g->lokasi)): ?>
                            <span>Lokasi: <?= htmlspecialchars($g->lokasi, ENT_QUOTES, 'UTF-8') ?></span>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>

                <?php if(!empty($g->link)): ?>
                    <a href="<?= htmlspecialchars($g->link, ENT_QUOTES, 'UTF-8') ?>"
                       target="_blank"
                       class="ppdb-announcement-link">
                        Buka Link
                    </a>
                <?php endif; ?>
            </div>
        <?php endforeach; ?>
    </div>

</section>
<?php endif; ?>
        <section class="peserta-bottom-grid">

            <div class="peserta-card">
                <div class="peserta-card-head">
                    <div>
                        <h5>Menu Cepat</h5>
                        <small>Akses fitur utama <?= htmlspecialchars($nama_ppdb ?? 'PPDB') ?>.</small>
                    </div>
                </div>

                <div class="quick-menu-grid">
                    <a href="<?= base_url('ppdb/biodata') ?>" class="quick-menu-card">
                        <span>◎</span>
                        <strong>Biodata</strong>
                        <small>Lengkapi data peserta</small>
                    </a>

                    <a href="<?= base_url('ppdb/upload') ?>" class="quick-menu-card">
                        <span>⇧</span>
                        <strong>Berkas</strong>
                        <small>Upload dokumen <?= htmlspecialchars($nama_ppdb ?? 'PPDB') ?></small>
                    </a>

                    <a href="<?= base_url('ppdb/detail') ?>" class="quick-menu-card">
                        <span>▣</span>
                        <strong>Detail</strong>
                        <small>Lihat data pendaftaran</small>
                    </a>

                    <a href="<?= base_url('ppdb/download_pdf') ?>" class="quick-menu-card">
                        <span>PDF</span>
                        <strong>PDF</strong>
                        <small>Download bukti pendaftaran</small>
                    </a>
                </div>
            </div>

            <div class="peserta-card">
                <div class="peserta-card-head">
                    <div>
                        <h5>Alur <?= htmlspecialchars($nama_ppdb ?? 'PPDB') ?></h5>
                        <small>Langkah yang perlu diselesaikan.</small>
                    </div>
                </div>

                <?php
                $current_step = 2;
                if($status_text == 'Upload Berkas'){ $current_step = 3; }
                elseif(in_array($status_text, ['Menunggu Verifikasi', 'Menunggu Verifikasi Berkas', 'Perlu Perbaikan'])){ $current_step = 4; }
                elseif(in_array($status_text, ['Diterima', 'Tidak Diterima', 'Ditolak'])){ $current_step = 5; }

                function tl_class($step_num, $current_step){
                    if($step_num < $current_step) return ['dot' => 'completed', 'item' => 'completed'];
                    if($step_num == $current_step) return ['dot' => 'active', 'item' => 'active'];
                    return ['dot' => 'pending', 'item' => 'pending'];
                }
                ?>
                <div class="peserta-timeline">
                    <?php $c1 = tl_class(1, $current_step); ?>
                    <div class="timeline-item <?= $c1['item'] ?>">
                        <div class="timeline-dot <?= $c1['dot'] ?>"><?= $c1['dot']=='completed'?'✓':'1' ?></div>
                        <div>
                            <strong>Registrasi Akun</strong>
                            <small>Akun <?= htmlspecialchars($nama_ppdb ?? 'PPDB') ?> berhasil dibuat.</small>
                        </div>
                    </div>

                    <?php $c2 = tl_class(2, $current_step); ?>
                    <div class="timeline-item <?= $c2['item'] ?>">
                        <div class="timeline-dot <?= $c2['dot'] ?>"><?= $c2['dot']=='completed'?'✓':'2' ?></div>
                        <div>
                            <strong>Lengkapi Biodata</strong>
                            <small>Isi data pribadi, alamat, orang tua, dan sekolah asal.</small>
                        </div>
                    </div>

                    <?php $c3 = tl_class(3, $current_step); ?>
                    <div class="timeline-item <?= $c3['item'] ?>">
                        <div class="timeline-dot <?= $c3['dot'] ?>"><?= $c3['dot']=='completed'?'✓':'3' ?></div>
                        <div>
                            <strong>Upload Berkas</strong>
                            <small>Unggah dokumen yang diminta oleh panitia.</small>
                        </div>
                    </div>

                    <?php $c4 = tl_class(4, $current_step); ?>
                    <div class="timeline-item <?= $c4['item'] ?>">
                        <div class="timeline-dot <?= $c4['dot'] ?>"><?= $c4['dot']=='completed'?'✓':'4' ?></div>
                        <div>
                            <strong>Verifikasi Panitia</strong>
                            <small>Panitia memeriksa data dan dokumen peserta.</small>
                        </div>
                    </div>

                    <?php $c5 = tl_class(5, $current_step); ?>
                    <div class="timeline-item <?= $c5['item'] ?>">
                        <div class="timeline-dot <?= $c5['dot'] ?>"><?= $c5['dot']=='completed'?'✓':'5' ?></div>
                        <div>
                            <strong>Hasil Seleksi</strong>
                            <small>Status diterima atau ditolak akan tampil di dashboard.</small>
                        </div>
                    </div>
                </div>
            </div>

        </section>

    </main>

</div>

<nav class="peserta-bottom-nav">
    <a href="<?= base_url('ppdb/dashboard') ?>" class="active">
        <span>⌂</span>
        Dashboard
    </a>

    <a href="<?= base_url('ppdb/biodata') ?>">
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

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<?php if($this->session->flashdata('success')): ?>
<script>
Swal.fire({
    icon:'success',
    title:'Berhasil',
    text:'<?= ppdb_dash_e($this->session->flashdata("success")) ?>',
    confirmButtonColor:'#16a34a'
});
</script>
<?php endif; ?>

<?php if($this->session->flashdata('error')): ?>
<script>
Swal.fire({
    icon:'error',
    title:'Terjadi Kesalahan',
    text:'<?= ppdb_dash_e($this->session->flashdata("error")) ?>',
    confirmButtonColor:'#16a34a'
});
</script>
<?php endif; ?>

<?php if(!empty($popup_pengumuman)): ?>
<?php
$popup_data = [
    'judul' => $popup_pengumuman->judul,
    'kategori' => $popup_pengumuman->kategori,
    'isi' => nl2br(htmlspecialchars($popup_pengumuman->isi, ENT_QUOTES, 'UTF-8')),
    'tanggal_mulai' => !empty($popup_pengumuman->tanggal_mulai) ? date('d-m-Y', strtotime($popup_pengumuman->tanggal_mulai)) : '',
    'tanggal_selesai' => !empty($popup_pengumuman->tanggal_selesai) ? date('d-m-Y', strtotime($popup_pengumuman->tanggal_selesai)) : '',
    'waktu' => $popup_pengumuman->waktu,
    'lokasi' => $popup_pengumuman->lokasi,
    'link' => $popup_pengumuman->link
];
?>

<script>
document.addEventListener('DOMContentLoaded', function(){

    const info = <?= json_encode($popup_data) ?>;

    let html = `
        <div style="text-align:left">
            <div style="
                display:inline-flex;
                padding:7px 12px;
                border-radius:999px;
                background:#dcfce7;
                color:#166534;
                font-weight:800;
                font-size:12px;
                margin-bottom:12px;
            ">
                ${info.kategori}
            </div>

            <div style="
                color:#334155;
                font-size:14px;
                line-height:1.7;
                margin-bottom:12px;
            ">
                ${info.isi}
            </div>
    `;

    if(info.tanggal_mulai || info.tanggal_selesai || info.waktu || info.lokasi){
        html += `<div style="
            background:#f8fafc;
            border:1px solid #e2e8f0;
            border-radius:14px;
            padding:12px;
            color:#334155;
            font-size:13px;
            line-height:1.6;
            margin-top:10px;
        ">`;

        if(info.tanggal_mulai){
            html += `<b>Tanggal:</b> ${info.tanggal_mulai}`;
            if(info.tanggal_selesai){
                html += ` s/d ${info.tanggal_selesai}`;
            }
            html += `<br>`;
        }

        if(info.waktu){
            html += `<b>Waktu:</b> ${info.waktu}<br>`;
        }

        if(info.lokasi){
            html += `<b>Lokasi:</b> ${info.lokasi}`;
        }

        html += `</div>`;
    }

    html += `</div>`;

    Swal.fire({
        icon: 'info',
        title: info.judul,
        html: html,
        confirmButtonText: 'Saya Mengerti',
        confirmButtonColor: '#16a34a',
        showDenyButton: info.link ? true : false,
        denyButtonText: 'Buka Link',
        denyButtonColor: '#0ea5e9',
        width: 620
    }).then(function(result){
        if(result.isDenied && info.link){
            window.open(info.link, '_blank');
        }
    });

});
</script>
<?php endif; ?>
</body>
</html>