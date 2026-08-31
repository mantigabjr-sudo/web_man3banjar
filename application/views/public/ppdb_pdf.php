<?php
if(!function_exists('pdf_e')){
    function pdf_e($text){
        return htmlspecialchars((string)$text, ENT_QUOTES, 'UTF-8');
    }
}

if(!function_exists('pdf_value')){
    function pdf_value($text, $default = '-'){
        $text = trim((string)$text);
        return $text !== '' ? pdf_e($text) : $default;
    }
}

if(!function_exists('pdf_tanggal')){
    function pdf_tanggal($date){
        if(empty($date) || $date == '0000-00-00'){
            return '-';
        }

        if(function_exists('tanggal_indo')){
            return tanggal_indo($date);
        }

        return date('d-m-Y', strtotime($date));
    }
}

$logo_path = FCPATH.'assets/img/logo.png';
$logo_url  = base_url('assets/img/logo.png');

$foto_path = !empty($siswa->foto) ? FCPATH.'uploads/temp/ppdb/'.$siswa->foto : '';
$foto_url  = !empty($siswa->foto) ? base_url('uploads/temp/ppdb/'.$siswa->foto) : '';

$status = !empty($siswa->status) ? $siswa->status : '-';

$berkas_wajib = [
    'foto' => 'Pas Foto',
    'kk_file' => 'Kartu Keluarga',
    'akta_file' => 'Akta Kelahiran',
    'sk_kelas9_file' => 'Surat Keterangan Kelas 9'
];

$berkas_tambahan = [
    'rapor_file' => 'Rapor / Nilai',
    'skl_file' => 'SKL',
    'nisn_file' => 'Surat Aktif NISN',
    'ijazah_file' => 'Ijazah SD/MI',
    'sertifikat_file' => 'Sertifikat'
];

$wajib_lengkap = true;
$wajib_uploaded = 0;

foreach($berkas_wajib as $field => $label){
    if(!empty($siswa->$field)){
        $wajib_uploaded++;
    } else {
        $wajib_lengkap = false;
    }
}

$tambahan_uploaded = 0;
foreach($berkas_tambahan as $field => $label){
    if(!empty($siswa->$field)){
        $tambahan_uploaded++;
    }
}

$filename_pdf = 'bukti-ppdb-'.preg_replace('/[^0-9A-Za-z_-]/', '', $siswa->nisn ?? 'peserta').'.pdf';
?>

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Bukti Pendaftaran PPDB</title>

<script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>

<style>
*{
    box-sizing:border-box;
}

body{
    margin:0;
    background:#e5e7eb;
    font-family:Arial, Helvetica, sans-serif;
    color:#0f172a;
}

#pdfArea{
    width:794px;
    height:1123px;
    background:#ffffff;
    margin:0 auto;
    padding:20px 26px;
    overflow:hidden;
}

.pdf-page{
    height:1083px;
    border:2px solid #16a34a;
    border-radius:12px;
    padding:14px 16px;
    overflow:hidden;
}

.header{
    width:100%;
    border-bottom:3px solid #16a34a;
    padding-bottom:8px;
    margin-bottom:8px;
}

.logo-cell{
    width:72px;
    vertical-align:middle;
}

.logo-img{
    width:64px;
    height:64px;
    object-fit:contain;
}

.logo-fallback{
    width:64px;
    height:64px;
    border-radius:50%;
    background:#dcfce7;
    color:#15803d;
    font-weight:bold;
    text-align:center;
    line-height:64px;
    border:2px solid #bbf7d0;
}

.instansi{
    margin:0;
    font-size:18px;
    font-weight:bold;
    color:#14532d;
    text-transform:uppercase;
}

.sub{
    margin:2px 0;
    font-size:12px;
    color:#334155;
    font-weight:bold;
}

.small{
    margin:1px 0;
    font-size:9.5px;
    color:#64748b;
}

.title{
    margin:8px 0;
    padding:7px;
    text-align:center;
    background:#dcfce7;
    border:1px solid #bbf7d0;
    color:#14532d;
    font-size:13px;
    font-weight:bold;
    border-radius:999px;
    letter-spacing:.4px;
}

.top-table{
    width:100%;
    margin-bottom:7px;
}

.identity-box{
    background:#f0fdf4;
    border:1px solid #bbf7d0;
    border-radius:9px;
    padding:8px 10px;
}

.identity-table{
    width:100%;
    border-collapse:collapse;
}

.identity-table td{
    padding:2px 3px;
    font-size:10.2px;
    vertical-align:top;
}

.identity-table td:first-child{
    width:125px;
    color:#14532d;
    font-weight:bold;
}

.photo{
    width:82px;
    height:106px;
    object-fit:cover;
    border:2px solid #16a34a;
    border-radius:7px;
}

.no-photo{
    width:82px;
    height:106px;
    border:1px dashed #94a3b8;
    border-radius:7px;
    font-size:9px;
    text-align:center;
    line-height:106px;
    color:#64748b;
}

.status-badge{
    display:inline-block;
    padding:3px 8px;
    border-radius:999px;
    font-size:9px;
    font-weight:bold;
}

.status-success{
    background:#dcfce7;
    color:#166534;
}

.status-danger{
    background:#fee2e2;
    color:#991b1b;
}

.status-info{
    background:#dbeafe;
    color:#1d4ed8;
}

.status-warning{
    background:#fef3c7;
    color:#92400e;
}

.two-col{
    width:100%;
    border-collapse:collapse;
}

.two-col > tbody > tr > td{
    width:50%;
    vertical-align:top;
}

.section{
    background:#15803d;
    color:#ffffff;
    font-size:10.5px;
    font-weight:bold;
    padding:5px 7px;
    border-radius:5px;
    margin-top:6px;
    margin-bottom:3px;
}

.data-table{
    width:100%;
    border-collapse:collapse;
    margin-bottom:3px;
}

.data-table th{
    width:145px;
    background:#f0fdf4;
    color:#166534;
    text-align:left;
    border:1px solid #d1fae5;
    padding:4px 5px;
    font-size:9.5px;
    vertical-align:top;
}

.data-table td{
    border:1px solid #e5e7eb;
    padding:4px 5px;
    font-size:9.5px;
    vertical-align:top;
    line-height:1.25;
}

.berkas-table{
    width:100%;
    border-collapse:collapse;
}

.berkas-table th{
    background:#f0fdf4;
    color:#166534;
    border:1px solid #d1fae5;
    padding:4px 5px;
    font-size:9.3px;
    text-align:left;
}

.berkas-table td{
    border:1px solid #e5e7eb;
    padding:4px 5px;
    font-size:9.3px;
    line-height:1.22;
}

.tag{
    display:inline-block;
    padding:2px 6px;
    border-radius:999px;
    font-size:8.5px;
    font-weight:bold;
}

.tag-ok{
    background:#dcfce7;
    color:#166534;
}

.tag-no{
    background:#fee2e2;
    color:#991b1b;
}

.tag-wajib{
    background:#fef3c7;
    color:#92400e;
}

.note{
    margin-top:6px;
    font-size:9.3px;
    line-height:1.35;
    padding:7px 9px;
    background:#f8fafc;
    border-left:4px solid #16a34a;
    color:#334155;
}

.footer{
    width:100%;
    margin-top:8px;
    font-size:9.2px;
    border-collapse:collapse;
}

.footer td{
    vertical-align:bottom;
}

.signature{
    text-align:center;
    width:210px;
}

.space{
    height:34px;
}
</style>
</head>

<body>

<div id="pdfArea">
<div class="pdf-page">

<table class="header">
<tr>
    <td class="logo-cell">
        <?php if(file_exists($logo_path)): ?>
            <img src="<?= $logo_url ?>" class="logo-img">
        <?php else: ?>
            <div class="logo-fallback">M3</div>
        <?php endif; ?>
    </td>

    <td>
        <p class="instansi">MAN 3 Banjar</p>
        <p class="sub">Panitia Penerimaan Peserta Didik Baru</p>
        <p class="small">Bukti pendaftaran dicetak dari Sistem PPDB Online MAN 3 Banjar</p>
    </td>
</tr>
</table>

<div class="title">
    BUKTI PENDAFTARAN PESERTA DIDIK BARU
</div>

<table class="top-table">
<tr>
    <td style="vertical-align:top;padding-right:8px;">
        <div class="identity-box">
            <table class="identity-table">
                <tr>
                    <td>No Pendaftaran</td>
                    <td>: <?= pdf_value($siswa->no_pendaftaran ?? '') ?></td>
                </tr>
                <tr>
                    <td>Nama Lengkap</td>
                    <td>: <?= pdf_value($siswa->nama_lengkap ?? '') ?></td>
                </tr>
                <tr>
                    <td>NISN</td>
                    <td>: <?= pdf_value($siswa->nisn ?? '') ?></td>
                </tr>
                <tr>
                    <td>Status</td>
                    <td>:
                        <?php
                        $status_class = 'status-warning';

                        if($status == 'Diterima'){
                            $status_class = 'status-success';
                        } elseif($status == 'Ditolak'){
                            $status_class = 'status-danger';
                        } elseif($status == 'Menunggu Verifikasi Berkas' || $status == 'Upload Berkas'){
                            $status_class = 'status-info';
                        }
                        ?>

                        <span class="status-badge <?= $status_class ?>">
                            <?= pdf_value($status) ?>
                        </span>
                    </td>
                </tr>
                <tr>
                    <td>Tanggal Cetak</td>
                    <td>: <?= pdf_tanggal(date('Y-m-d')) ?></td>
                </tr>
            </table>
        </div>
    </td>

    <td style="width:90px;text-align:center;vertical-align:top;">
        <?php if(!empty($siswa->foto) && file_exists($foto_path)): ?>
            <img src="<?= $foto_url ?>" class="photo">
        <?php else: ?>
            <div class="no-photo">Pas Foto</div>
        <?php endif; ?>
    </td>
</tr>
</table>

<table class="two-col">
<tr>
<td style="padding-right:4px;">

<div class="section">A. Data Pendaftaran</div>
<table class="data-table">
<tr><th>Nama Lengkap</th><td><?= pdf_value($siswa->nama_lengkap ?? '') ?></td></tr>
<tr><th>NISN</th><td><?= pdf_value($siswa->nisn ?? '') ?></td></tr>
<tr><th>TTL</th><td><?= pdf_value($siswa->tempat_lahir ?? '') ?>, <?= pdf_tanggal($siswa->tanggal_lahir ?? '') ?></td></tr>
<tr><th>Jenis Kelamin</th><td><?= ($siswa->jk ?? '') == 'L' ? 'Laki-laki' : (($siswa->jk ?? '') == 'P' ? 'Perempuan' : '-') ?></td></tr>
<tr><th>Asal Sekolah</th><td><?= pdf_value($siswa->asal_sekolah ?? '') ?></td></tr>
<tr><th>No HP</th><td><?= pdf_value($siswa->no_hp ?? '') ?></td></tr>
</table>

</td>

<td style="padding-left:4px;">

<div class="section">B. Data Pribadi</div>
<table class="data-table">
<tr><th>NIK</th><td><?= pdf_value($siswa->nik ?? '') ?></td></tr>
<tr><th>No KK</th><td><?= pdf_value($siswa->no_kk ?? '') ?></td></tr>
<tr><th>Agama</th><td><?= pdf_value($siswa->agama ?? '') ?></td></tr>
<tr><th>Anak Ke</th><td><?= pdf_value($siswa->anak_ke ?? '') ?></td></tr>
<tr><th>Jumlah Saudara</th><td><?= pdf_value($siswa->jumlah_saudara ?? '') ?></td></tr>
</table>

</td>
</tr>
</table>

<div class="section">C. Alamat</div>
<table class="data-table">
<tr>
    <th>Alamat Lengkap</th>
    <td>
        <?= pdf_value($siswa->alamat ?? '') ?>,
        RT <?= pdf_value($siswa->rt ?? '') ?>/RW <?= pdf_value($siswa->rw ?? '') ?>,
        <?= pdf_value($siswa->desa ?? '') ?>,
        <?= pdf_value($siswa->kecamatan ?? '') ?>,
        <?= pdf_value($siswa->kabupaten ?? '') ?>,
        <?= pdf_value($siswa->provinsi ?? '') ?>,
        <?= pdf_value($siswa->kode_pos ?? '') ?>
    </td>
</tr>
</table>

<div class="section">D. Data Orang Tua / Wali</div>
<table class="data-table">
<tr><th>Nama Ayah</th><td><?= pdf_value($siswa->nama_ayah ?? '') ?></td></tr>
<tr><th>Pekerjaan Ayah</th><td><?= pdf_value($siswa->pekerjaan_ayah ?? '') ?></td></tr>
<tr><th>Nama Ibu</th><td><?= pdf_value($siswa->nama_ibu ?? '') ?></td></tr>
<tr><th>Pekerjaan Ibu</th><td><?= pdf_value($siswa->pekerjaan_ibu ?? '') ?></td></tr>
<tr><th>Penghasilan Orang Tua</th><td><?= pdf_value($siswa->penghasilan_ortu ?? '') ?></td></tr>
</table>

<div class="section">E. Status Berkas</div>
<table class="berkas-table">
<thead>
<tr>
    <th>Nama Berkas</th>
    <th style="width:105px;">Ketentuan</th>
    <th style="width:95px;">Status</th>
</tr>
</thead>
<tbody>

<?php foreach($berkas_wajib as $field => $label): ?>
<tr>
    <td><?= pdf_e($label) ?></td>
    <td><span class="tag tag-wajib">Wajib</span></td>
    <td>
        <?php if(!empty($siswa->$field)): ?>
            <span class="tag tag-ok">Sudah</span>
        <?php else: ?>
            <span class="tag tag-no">Belum</span>
        <?php endif; ?>
    </td>
</tr>
<?php endforeach; ?>

<tr>
    <td>Ijazah</td>
    <td>Ijazah SD/MI boleh menyusul</td>
    <td>
        <?php if(!empty($siswa->ijazah_file)): ?>
            <span class="tag tag-ok">Sudah</span>
        <?php else: ?>
            <span class="tag tag-no">Belum</span>
        <?php endif; ?>
    </td>
</tr>

<tr>
    <td>Rapor, SKL, NISN, Sertifikat</td>
    <td>Opsional/Menyusul</td>
    <td><?= $tambahan_uploaded ?> file</td>
</tr>

</tbody>
</table>

<div class="note">
    <b>Catatan:</b>
    Surat Keterangan Kelas 9 termasuk berkas wajib.
    Ijazah dapat menggunakan ijazah SD/MI atau menyusul sesuai ketentuan panitia.
    Bukti ini wajib disimpan dan dapat dibawa saat verifikasi atau daftar ulang.
</div>

<table class="footer">
<tr>
    <td style="color:#64748b;line-height:1.35;">
        Dicetak melalui sistem PPDB Online MAN 3 Banjar.
        Data bersumber dari isian peserta dan dapat diverifikasi kembali oleh panitia.
        <br>
        Berkas wajib: <b><?= $wajib_uploaded ?>/<?= count($berkas_wajib) ?></b>
        — Status wajib: <b><?= $wajib_lengkap ? 'Lengkap' : 'Belum Lengkap' ?></b>.
    </td>

    <td class="signature">
        Banjar, <?= pdf_tanggal(date('Y-m-d')) ?><br>
        Panitia PPDB
        <div class="space"></div>
        <b>MAN 3 Banjar</b>
    </td>
</tr>
</table>

</div>
</div>

<script>
window.onload = function(){

    var element = document.getElementById('pdfArea');

    var opt = {
        margin: 0,
        filename: '<?= $filename_pdf ?>',
        image: {
            type: 'jpeg',
            quality: 0.98
        },
        html2canvas: {
            scale: 2,
            useCORS: true,
            scrollY: 0
        },
        jsPDF: {
            unit: 'px',
            format: [794,1123],
            orientation: 'portrait'
        }
    };

    html2pdf().set(opt).from(element).save().then(function(){
        window.history.back();
    });
};
</script>

</body>
</html>