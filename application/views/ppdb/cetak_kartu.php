<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>KARTU BUKTI PENDAFTARAN - <?= $pendaftar->no_pendaftaran ?></title>
    <style>
        body { font-family: Arial, Helvetica, sans-serif; font-size: 13px; color: #000; margin: 20px; }
        .card-print { border: 2px solid #000; padding: 25px; max-width: 680px; margin: 0 auto; border-radius: 8px; }
        .header-print { text-align: center; border-bottom: 2px solid #000; padding-bottom: 15px; margin-bottom: 20px; }
        .header-print h2 { margin: 0 0 5px 0; font-size: 18px; text-transform: uppercase; }
        .header-print h3 { margin: 0 0 5px 0; font-size: 15px; font-weight: normal; }
        .header-print p { margin: 0; font-size: 11px; color: #444; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        table td { padding: 6px 4px; vertical-align: top; }
        .no-reg { font-size: 18px; font-weight: bold; font-family: monospace; background: #eee; padding: 6px 12px; display: inline-block; border: 1px dashed #000; }
        .footer-print { margin-top: 30px; display: flex; justify-content: space-between; }
        @media print {
            .no-print { display: none; }
            body { margin: 0; }
        }
    </style>
</head>
<body>

<div class="no-print" style="text-align:center; margin-bottom: 20px;">
    <button onclick="window.print()" style="padding: 10px 20px; font-size: 14px; font-weight: bold; cursor: pointer; background: #059669; color:#fff; border:none; border-radius:6px;">🖨️ Cetak / Simpan PDF</button>
</div>

<div class="card-print">
    <div class="header-print">
        <h2><?= htmlspecialchars($setting->nama_sekolah) ?></h2>
        <h3>KARTU BUKTI PENDAFTARAN SISWA BARU (PPDB ONLINE)</h3>
        <p>Tahun Pelajaran: <?= htmlspecialchars($pendaftar->tahun_ajaran) ?> &bull; <?= htmlspecialchars($setting->alamat) ?></p>
    </div>

    <div style="text-align: center; margin-bottom: 20px;">
        <span class="no-reg"><?= $pendaftar->no_pendaftaran ?></span>
    </div>

    <table>
        <tr>
            <td style="width: 30%;"><strong>Nama Lengkap</strong></td>
            <td style="width: 3%;">:</td>
            <td><strong><?= htmlspecialchars($pendaftar->nama_lengkap) ?></strong></td>
        </tr>
        <tr>
            <td><strong>NISN</strong></td>
            <td>:</td>
            <td><?= htmlspecialchars($pendaftar->nisn) ?></td>
        </tr>
        <tr>
            <td><strong>NIK</strong></td>
            <td>:</td>
            <td><?= htmlspecialchars($pendaftar->nik ? $pendaftar->nik : '-') ?></td>
        </tr>
        <tr>
            <td><strong>Jenis Kelamin</strong></td>
            <td>:</td>
            <td><?= $pendaftar->jenis_kelamin == 'L' ? 'Laki-Laki' : 'Perempuan' ?></td>
        </tr>
        <tr>
            <td><strong>Tempat, Tgl Lahir</strong></td>
            <td>:</td>
            <td><?= htmlspecialchars($pendaftar->tempat_lahir) ?>, <?= date('d F Y', strtotime($pendaftar->tanggal_lahir)) ?></td>
        </tr>
        <tr>
            <td><strong>Asal Sekolah</strong></td>
            <td>:</td>
            <td><?= htmlspecialchars($pendaftar->sekolah_asal) ?></td>
        </tr>
        <tr>
            <td><strong>Nama Orang Tua / Wali</strong></td>
            <td>:</td>
            <td><?= htmlspecialchars($pendaftar->nama_ayah ? $pendaftar->nama_ayah : $pendaftar->nama_ibu) ?></td>
        </tr>
        <tr>
            <td><strong>No. HP / WhatsApp</strong></td>
            <td>:</td>
            <td><?= htmlspecialchars($pendaftar->no_hp_siswa ? $pendaftar->no_hp_siswa : $pendaftar->no_hp_ortu) ?></td>
        </tr>
        <tr>
            <td><strong>Waktu Mendaftar</strong></td>
            <td>:</td>
            <td><?= date('d/m/Y H:i', strtotime($pendaftar->created_at)) ?> WITA</td>
        </tr>
    </table>

    <div style="border-top: 1px dashed #999; padding-top: 10px; font-size: 11px; color: #555;">
        <strong>Catatan Penting:</strong>
        <ol style="margin: 5px 0 0 15px; padding: 0;">
            <li>Simpan kartu bukti pendaftaran ini untuk keperluan verifikasi berkas dan daftar ulang.</li>
            <li>Pantau pengumuman seleksi berkala melalui website resmi: <u><?= base_url() ?></u>.</li>
        </ol>
    </div>
</div>

</body>
</html>
