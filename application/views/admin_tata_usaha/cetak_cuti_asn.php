<?php
defined('BASEPATH') OR exit('No direct script access allowed');

// Helper format tanggal Indonesia
function tgl_indo_lengkap($tanggal) {
    if (empty($tanggal) || $tanggal == '0000-00-00') return '-';
    $bulan = [
        1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
        5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
        9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
    ];
    $ts = strtotime($tanggal);
    $d = date('d', $ts);
    $m = (int)date('m', $ts);
    $y = date('Y', $ts);
    return $d . ' ' . ($bulan[$m] ?? '') . ' ' . $y;
}

function tgl_indo_pendek($tanggal) {
    if (empty($tanggal) || $tanggal == '0000-00-00') return '-';
    $bulan = [
        1 => 'Jan', 2 => 'Feb', 3 => 'Mar', 4 => 'Apr',
        5 => 'Mei', 6 => 'Jun', 7 => 'Jul', 8 => 'Agu',
        9 => 'Sep', 10 => 'Okt', 11 => 'Nov', 12 => 'Des'
    ];
    $ts = strtotime($tanggal);
    $d = date('d', $ts);
    $m = (int)date('m', $ts);
    $y = date('Y', $ts);
    return $d . ' ' . ($bulan[$m] ?? '') . ' ' . $y;
}

// Cek apakah pemohon adalah Kepala Madrasah
$clean_nip_pemohon = !empty($cuti->nip) ? preg_replace('/\D/', '', $cuti->nip) : '';
$is_kamad = ($clean_nip_pemohon === '197103152001121001');

// Penentuan Atasan Langsung sesuai aturan resmi Sheet Blanko
if ($is_kamad) {
    $atasan_jabatan = 'Plt. Kasi Pendmad Kemenag Kab. Banjar';
    $atasan_nama    = 'M. Yusup, S.Ag., M.Pd.';
    $atasan_nip     = '197603092007101004';
} else {
    $atasan_jabatan = 'Kepala Madrasah';
    $atasan_nama    = !empty($cuti->atasan_langsung_nama) ? $cuti->atasan_langsung_nama : 'Nor Ifansyah, S.Pd., M.Sc';
    $atasan_nip     = !empty($cuti->atasan_langsung_nip) ? $cuti->atasan_langsung_nip : '197103152001121001';
}

// Pejabat yang Berwenang Memberikan Cuti
$pejabat_jabatan = !empty($cuti->pejabat_berwenang_jabatan) ? $cuti->pejabat_berwenang_jabatan : 'Kepala Kantor Kemenag Kab. Banjar,';
// Pastikan ada koma di akhir jabatan pejabat sesuai blanko resmi
if (substr(trim($pejabat_jabatan), -1) !== ',') {
    $pejabat_jabatan = trim($pejabat_jabatan) . ',';
}
$pejabat_nama = !empty($cuti->pejabat_berwenang_nama) ? $cuti->pejabat_berwenang_nama : 'Dr. H. Ahmad Kamal, S.H.I., M.Ag.';
$pejabat_nip  = !empty($cuti->pejabat_berwenang_nip) ? $cuti->pejabat_berwenang_nip : '198107032005011007';

// Simbol checkmark resmi BKN
$v = '<span style="font-family: Arial, sans-serif; font-weight: bold; font-size: 11pt;">&#8730;</span>';
$strip = '-';

// Penentuan jenis cuti yang diambil
$is_tahunan = in_array($cuti->jenis_cuti, ['Cuti Tahunan', 'Cuti Tahunan N-1', 'Cuti Tahunan N-2', 'Cuti Tahun N-1', 'Cuti Tahun N-2']);
$is_besar   = ($cuti->jenis_cuti == 'Cuti Besar');
$is_sakit   = ($cuti->jenis_cuti == 'Cuti Sakit');
$is_lahir   = ($cuti->jenis_cuti == 'Cuti Melahirkan');
$is_penting = in_array($cuti->jenis_cuti, ['Cuti Alasan Penting', 'Cuti Karena Alasan Penting']);
$is_cltn    = in_array($cuti->jenis_cuti, ['Cuti di Luar Tanggungan Negara', 'Cuti diluar Tanggungan Negara']);

// Saldo Cuti Tahunan (N-2, N-1, N)
$s_n2 = (!empty($cuti->catatan_cuti_tahunan_n2_sisa) && $cuti->catatan_cuti_tahunan_n2_sisa != '0' && $cuti->catatan_cuti_tahunan_n2_sisa != '-') ? $cuti->catatan_cuti_tahunan_n2_sisa : '-';
$s_n1 = (!empty($cuti->catatan_cuti_tahunan_n1_sisa) && $cuti->catatan_cuti_tahunan_n1_sisa != '0' && $cuti->catatan_cuti_tahunan_n1_sisa != '-') ? $cuti->catatan_cuti_tahunan_n1_sisa : '-';
$s_n  = (!empty($cuti->catatan_cuti_tahunan_n_sisa) && $cuti->catatan_cuti_tahunan_n_sisa != '0' && $cuti->catatan_cuti_tahunan_n_sisa != '-') ? $cuti->catatan_cuti_tahunan_n_sisa : '-';

// Keputusan Persetujuan
$is_atasan_setuju = ($cuti->atasan_langsung_keputusan == 'DI SETUJUI' || in_array($cuti->status, ['Disetujui', 'Disetujui Kepala', 'Selesai', 'Aktif']));
$is_pejabat_setuju = ($cuti->pejabat_berwenang_keputusan == 'DI SETUJUI' || in_array($cuti->status, ['Disetujui', 'Disetujui Kepala', 'Selesai', 'Aktif']));

// Format tanggal
$tgl_surat_indo = 'Banjar, ' . tgl_indo_lengkap($cuti->tanggal_surat);
$tgl_rentang = tgl_indo_pendek($cuti->tanggal_mulai) . ' s.d ' . tgl_indo_pendek($cuti->tanggal_selesai);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Formulir Cuti - <?= htmlspecialchars($cuti->nama_lengkap) ?> (<?= htmlspecialchars($cuti->nip) ?>)</title>
    <style>
        @page {
            size: A4 portrait;
            margin: 6mm 10mm 6mm 10mm;
        }

        * {
            box-sizing: border-box;
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }

        body {
            font-family: 'Calibri', 'Segoe UI', Arial, sans-serif;
            font-size: 9pt;
            line-height: 1.15;
            color: #000;
            background: #e2e8f0;
            margin: 0;
            padding: 20px 0;
        }

        .screen-toolbar {
            width: 210mm;
            margin: 0 auto 12px auto;
            display: flex;
            justify-content: space-between;
            align-items: center;
            background: #1e293b;
            color: #fff;
            padding: 10px 16px;
            border-radius: 8px;
            box-shadow: 0 4px 10px rgba(0,0,0,0.15);
        }

        .btn-print {
            background: #16a34a;
            color: #fff;
            border: none;
            padding: 8px 20px;
            font-size: 10pt;
            font-weight: bold;
            border-radius: 6px;
            cursor: pointer;
            transition: all 0.2s;
        }

        .btn-print:hover {
            background: #15803d;
        }

        .page {
            width: 210mm;
            min-height: 297mm;
            padding: 6mm 10mm;
            margin: 0 auto;
            background: #ffffff;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        }

        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .text-left { text-align: left; }
        .fw-bold { font-weight: bold; }
        .uppercase { text-transform: uppercase; }

        /* Header kanan atas */
        .kepada-box {
            width: 48%;
            margin-left: auto;
            margin-bottom: 6px;
            font-size: 9pt;
            line-height: 1.25;
        }

        .form-title {
            font-size: 10.5pt;
            font-weight: bold;
            text-align: center;
            margin: 6px 0 6px 0;
            letter-spacing: 0.5px;
        }

        /* Tabel Presisi Grid 8 Kolom BKN */
        table.bkn-grid {
            width: 100%;
            table-layout: fixed;
            border-collapse: collapse;
            margin-bottom: 3.5px;
            font-size: 8.8pt;
        }

        table.bkn-grid td, 
        table.bkn-grid th {
            border: 1px solid #000;
            padding: 2.2px 4px;
            vertical-align: middle;
            overflow: hidden;
            word-wrap: break-word;
        }

        .sec-header {
            font-weight: bold;
            background-color: #ffffff;
            padding: 2.5px 5px !important;
            font-size: 8.8pt;
        }

        .cell-label {
            padding-left: 5px;
        }

        .check-cell {
            text-align: center;
            font-weight: bold;
        }

        .sig-space {
            height: 44px;
        }

        .notes-text {
            font-size: 7pt;
            line-height: 1.15;
            color: #000;
        }

        @media print {
            html, body {
                background: transparent;
                margin: 0;
                padding: 0;
                height: 100%;
            }
            .screen-toolbar {
                display: none !important;
            }
            .page {
                width: 100%;
                min-height: auto;
                margin: 0;
                padding: 0;
                box-shadow: none;
            }
            table.bkn-grid {
                page-break-inside: avoid;
            }
        }
    </style>
</head>
<body>

    <!-- TOOLBAR LAYAR (Tidak ikut dicetak) -->
    <div class="screen-toolbar">
        <div>
            <strong style="font-size: 11pt;">🖨️ Cetak Formulir Permintaan & Pemberian Cuti ASN</strong>
            <div style="font-size: 8.5pt; color: #94a3b8;">Format Standar BKN (Perka BKN No. 24/2017) &bull; Pas 1 Lembar A4 Portrait</div>
        </div>
        <div style="display:flex; gap: 10px; align-items:center;">
            <button class="btn-print" onclick="window.print()">
                <span style="font-size: 13pt; vertical-align: middle; margin-right: 4px;">🖨️</span> Cetak / Simpan PDF
            </button>
            <button onclick="window.close()" style="background:#475569; color:#fff; border:none; padding:8px 14px; border-radius:6px; cursor:pointer;">
                Tutup
            </button>
        </div>
    </div>

    <!-- HALAMAN A4 RESMI -->
    <div class="page">

        <!-- KEPADA & TANGGAL (Kolom F-H) -->
        <div class="kepada-box">
            <div style="margin-bottom: 2px;"><?= $tgl_surat_indo ?></div>
            <div>Kepada</div>
            <div>Yth. <?= !empty($cuti->yth_kepada) ? $cuti->yth_kepada : 'Kepala Kantor Kemenag Kab. Banjar' ?></div>
            <div>Di - <?= !empty($cuti->di_tempat) ? $cuti->di_tempat : 'Martapura' ?></div>
        </div>

        <!-- JUDUL FORMULIR -->
        <div class="form-title">
            FORMULIR PERMINTAAN DAN PEMBERIAN CUTI
        </div>

        <!-- I. DATA PEGAWAI -->
        <table class="bkn-grid">
            <colgroup>
                <col style="width: 16.5%;">  <!-- A -->
                <col style="width: 12.35%;"> <!-- B -->
                <col style="width: 12.35%;"> <!-- C -->
                <col style="width: 12.35%;"> <!-- D -->
                <col style="width: 12.0%;">  <!-- E -->
                <col style="width: 9.8%;">   <!-- F -->
                <col style="width: 12.35%;"> <!-- G -->
                <col style="width: 12.25%;"> <!-- H -->
            </colgroup>
            <tbody>
                <tr>
                    <td colspan="8" class="sec-header">I. DATA PEGAWAI</td>
                </tr>
                <tr>
                    <td class="cell-label">Nama</td>
                    <td colspan="4" class="fw-bold" style="padding-left: 5px;"><?= htmlspecialchars($cuti->nama_lengkap) ?></td>
                    <td class="cell-label text-center">NIP</td>
                    <td colspan="2" class="text-center fw-bold"><?= !empty($cuti->nip) ? htmlspecialchars($cuti->nip) : '-' ?></td>
                </tr>
                <tr>
                    <td class="cell-label">Jabatan</td>
                    <td colspan="4" style="padding-left: 5px;"><?= !empty($cuti->ptk_jabatan) ? htmlspecialchars($cuti->ptk_jabatan) : '-' ?></td>
                    <td class="cell-label text-center">Masa Kerja</td>
                    <td colspan="2" class="text-center"><?= !empty($cuti->masa_kerja) ? htmlspecialchars($cuti->masa_kerja) : '-' ?></td>
                </tr>
                <tr>
                    <td class="cell-label">Unit Kerja</td>
                    <td colspan="7" style="padding-left: 5px;">MAN 3 Banjar</td>
                </tr>
            </tbody>
        </table>

        <!-- II. JENIS CUTI YANG DIAMBIL** -->
        <table class="bkn-grid">
            <colgroup>
                <col style="width: 16.5%;">
                <col style="width: 12.35%;">
                <col style="width: 12.35%;">
                <col style="width: 12.35%;">
                <col style="width: 12.0%;">
                <col style="width: 9.8%;">
                <col style="width: 12.35%;">
                <col style="width: 12.25%;">
            </colgroup>
            <tbody>
                <tr>
                    <td colspan="8" class="sec-header">II. JENIS CUTI YANG DIAMBIL**</td>
                </tr>
                <tr>
                    <td class="cell-label">Cuti Tahunan</td>
                    <td colspan="3" class="check-cell"><?= $is_tahunan ? $v : $strip ?></td>
                    <td colspan="3" class="cell-label">Cuti Besar</td>
                    <td class="check-cell"><?= $is_besar ? $v : $strip ?></td>
                </tr>
                <tr>
                    <td class="cell-label">Cuti Sakit</td>
                    <td colspan="3" class="check-cell"><?= $is_sakit ? $v : $strip ?></td>
                    <td colspan="3" class="cell-label">Cuti Melahirkan</td>
                    <td class="check-cell"><?= $is_lahir ? $v : $strip ?></td>
                </tr>
                <tr>
                    <td class="cell-label">Cuti Alasan Penting</td>
                    <td colspan="3" class="check-cell"><?= $is_penting ? $v : $strip ?></td>
                    <td colspan="3" class="cell-label">Cuti diluar Tanggungan Negara</td>
                    <td class="check-cell"><?= $is_cltn ? $v : $strip ?></td>
                </tr>
            </tbody>
        </table>

        <!-- III. ALASAN CUTI -->
        <table class="bkn-grid">
            <colgroup>
                <col style="width: 16.5%;">
                <col style="width: 12.35%;">
                <col style="width: 12.35%;">
                <col style="width: 12.35%;">
                <col style="width: 12.0%;">
                <col style="width: 9.8%;">
                <col style="width: 12.35%;">
                <col style="width: 12.25%;">
            </colgroup>
            <tbody>
                <tr>
                    <td colspan="8" class="sec-header">III. ALASAN CUTI</td>
                </tr>
                <tr>
                    <td colspan="8" style="padding: 4px 6px; min-height: 22px;">
                        <?= !empty($cuti->alasan_cuti) ? htmlspecialchars($cuti->alasan_cuti) : '-' ?>
                    </td>
                </tr>
            </tbody>
        </table>

        <!-- IV. LAMANYA CUTI -->
        <table class="bkn-grid">
            <colgroup>
                <col style="width: 16.5%;">
                <col style="width: 12.35%;">
                <col style="width: 12.35%;">
                <col style="width: 12.35%;">
                <col style="width: 12.0%;">
                <col style="width: 9.8%;">
                <col style="width: 12.35%;">
                <col style="width: 12.25%;">
            </colgroup>
            <tbody>
                <tr>
                    <td colspan="8" class="sec-header">IV. LAMANYA CUTI</td>
                </tr>
                <tr>
                    <td class="cell-label">Selama &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <strong><?= $cuti->lama_cuti_jumlah ?></strong></td>
                    <td colspan="2" class="cell-label">(Hari/Bulan/Tahun)*</td>
                    <td colspan="2" class="cell-label text-center">Mulai Tanggal</td>
                    <td colspan="3" class="fw-bold" style="padding-left: 6px;"><?= $tgl_rentang ?></td>
                </tr>
            </tbody>
        </table>

        <!-- V. CATATAN CUTI*** -->
        <table class="bkn-grid">
            <colgroup>
                <col style="width: 16.5%;">
                <col style="width: 12.35%;">
                <col style="width: 12.35%;">
                <col style="width: 12.35%;">
                <col style="width: 12.0%;">
                <col style="width: 9.8%;">
                <col style="width: 12.35%;">
                <col style="width: 12.25%;">
            </colgroup>
            <tbody>
                <tr>
                    <td colspan="8" class="sec-header">V. CATATAN CUTI***</td>
                </tr>
                <tr>
                    <td colspan="4" class="fw-bold" style="padding-left: 5px;">1. Cuti Tahunan</td>
                    <td colspan="3" class="cell-label">2. Cuti Besar</td>
                    <td class="check-cell"><?= !empty($cuti->catatan_cuti_besar) ? $cuti->catatan_cuti_besar : '-' ?></td>
                </tr>
                <tr class="text-center fw-bold" style="background-color: #fafafa;">
                    <td>Tahun</td>
                    <td>Sisa</td>
                    <td colspan="2">Keterangan</td>
                    <td colspan="3" class="cell-label text-left" style="font-weight: normal;">3. Cuti Sakit</td>
                    <td class="check-cell" style="font-weight: bold;"><?= !empty($cuti->catatan_cuti_sakit) ? $cuti->catatan_cuti_sakit : '-' ?></td>
                </tr>
                <tr>
                    <td class="text-center">N-2</td>
                    <td class="text-center"><?= $s_n2 ?></td>
                    <td colspan="2" class="text-center"><?= !empty($cuti->catatan_cuti_tahunan_n2_ket) ? htmlspecialchars($cuti->catatan_cuti_tahunan_n2_ket) : '' ?></td>
                    <td colspan="3" class="cell-label">4. Cuti Melahirkan</td>
                    <td class="check-cell"><?= !empty($cuti->catatan_cuti_melahirkan) ? $cuti->catatan_cuti_melahirkan : '-' ?></td>
                </tr>
                <tr>
                    <td class="text-center">N-1</td>
                    <td class="text-center"><?= $s_n1 ?></td>
                    <td colspan="2" class="text-center"><?= !empty($cuti->catatan_cuti_tahunan_n1_ket) ? htmlspecialchars($cuti->catatan_cuti_tahunan_n1_ket) : '' ?></td>
                    <td colspan="3" class="cell-label">5. Cuti Alasan Penting</td>
                    <td class="check-cell"><?= !empty($cuti->catatan_cuti_alasan_penting) ? $cuti->catatan_cuti_alasan_penting : '-' ?></td>
                </tr>
                <tr>
                    <td class="text-center">N</td>
                    <td class="text-center fw-bold"><?= $s_n ?></td>
                    <td colspan="2" class="text-center"><?= !empty($cuti->catatan_cuti_tahunan_n_ket) ? htmlspecialchars($cuti->catatan_cuti_tahunan_n_ket) : '' ?></td>
                    <td colspan="3" class="cell-label">6. Cuti diluar Tanggungan Negara</td>
                    <td class="check-cell"><?= !empty($cuti->catatan_cuti_diluar_tanggungan) ? $cuti->catatan_cuti_diluar_tanggungan : '-' ?></td>
                </tr>
            </tbody>
        </table>

        <!-- VI. ALAMAT SELAMA MENJALANKAN CUTI -->
        <table class="bkn-grid">
            <colgroup>
                <col style="width: 16.5%;">
                <col style="width: 12.35%;">
                <col style="width: 12.35%;">
                <col style="width: 12.35%;">
                <col style="width: 12.0%;">
                <col style="width: 9.8%;">
                <col style="width: 12.35%;">
                <col style="width: 12.25%;">
            </colgroup>
            <tbody>
                <tr>
                    <td colspan="8" class="sec-header">VI. ALAMAT SELAMA MENJALANKAN CUTI</td>
                </tr>
                <tr>
                    <td colspan="5" rowspan="4" style="vertical-align: top; padding: 6px; border-right: 1px solid #000;">
                        <div style="min-height: 80px; line-height: 1.3;">
                            <?= !empty($cuti->alamat_cuti) ? nl2br(htmlspecialchars($cuti->alamat_cuti)) : '-' ?>
                        </div>
                    </td>
                    <td class="cell-label text-center" style="border-bottom: 1px solid #000;">Telp.</td>
                    <td colspan="2" class="fw-bold" style="padding-left: 6px; border-bottom: 1px solid #000;">
                        <?= !empty($cuti->telepon_cuti) ? htmlspecialchars($cuti->telepon_cuti) : '-' ?>
                    </td>
                </tr>
                <tr>
                    <td colspan="3" class="text-center" style="border-top: none; border-bottom: none; padding-top: 5px;">
                        Hormat Saya,
                    </td>
                </tr>
                <tr>
                    <td colspan="3" class="sig-space" style="border-top: none; border-bottom: none;"></td>
                </tr>
                <tr>
                    <td colspan="3" class="text-center" style="border-top: none; padding-bottom: 5px;">
                        <div class="fw-bold"><?= htmlspecialchars($cuti->nama_lengkap) ?></div>
                        <div>NIP. <?= !empty($cuti->nip) ? htmlspecialchars($cuti->nip) : '-' ?></div>
                    </td>
                </tr>
            </tbody>
        </table>

        <!-- VII. PERTIMBANGAN ATASAN LANGSUNG** -->
        <table class="bkn-grid">
            <colgroup>
                <col style="width: 16.5%;">
                <col style="width: 12.35%;">
                <col style="width: 12.35%;">
                <col style="width: 12.35%;">
                <col style="width: 12.0%;">
                <col style="width: 9.8%;">
                <col style="width: 12.35%;">
                <col style="width: 12.25%;">
            </colgroup>
            <tbody>
                <tr>
                    <td colspan="8" class="sec-header">VII. PERTIMBANGAN ATASAN LANGSUNG**</td>
                </tr>
                <tr class="text-center fw-bold" style="font-size: 8.5pt;">
                    <td>DI SETUJUI</td>
                    <td colspan="2">PERUBAHAN****</td>
                    <td colspan="2">DI TANGGUHKAN****</td>
                    <td colspan="3">TIDAK DI SETUJUI****</td>
                </tr>
                <tr class="text-center" style="height: 18px;">
                    <td class="check-cell"><?= $is_atasan_setuju ? $v : '' ?></td>
                    <td colspan="2" class="check-cell"><?= $cuti->atasan_langsung_keputusan == 'PERUBAHAN' ? $v : '' ?></td>
                    <td colspan="2" class="check-cell"><?= $cuti->atasan_langsung_keputusan == 'DI TANGGUHKAN' ? $v : '' ?></td>
                    <td colspan="3" class="check-cell"><?= $cuti->atasan_langsung_keputusan == 'TIDAK DI SETUJUI' ? $v : '' ?></td>
                </tr>
                <tr>
                    <td colspan="5" rowspan="3" style="border-right: 1px solid #000; border-top: none; border-bottom: 1px solid #000;"></td>
                    <td colspan="3" class="text-center" style="border-left: none; border-top: none; border-bottom: none; padding-top: 5px;">
                        <?= $atasan_jabatan ?>
                    </td>
                </tr>
                <tr>
                    <td colspan="3" class="sig-space" style="border-left: none; border-top: none; border-bottom: none;"></td>
                </tr>
                <tr>
                    <td colspan="3" class="text-center" style="border-left: none; border-top: none; border-bottom: 1px solid #000; padding-bottom: 5px;">
                        <div class="fw-bold"><?= $atasan_nama ?></div>
                        <div>NIP. <?= $atasan_nip ?></div>
                    </td>
                </tr>
            </tbody>
        </table>

        <!-- VIII. KEPUTUSAN PEJABAT YANG BERWENANG MEMBERIKAN CUTI** -->
        <table class="bkn-grid">
            <colgroup>
                <col style="width: 16.5%;">
                <col style="width: 12.35%;">
                <col style="width: 12.35%;">
                <col style="width: 12.35%;">
                <col style="width: 12.0%;">
                <col style="width: 9.8%;">
                <col style="width: 12.35%;">
                <col style="width: 12.25%;">
            </colgroup>
            <tbody>
                <tr>
                    <td colspan="8" class="sec-header">VIII. KEPUTUSAN PEJABAT YANG BERWENANG MEMBERIKAN CUTI**</td>
                </tr>
                <tr class="text-center fw-bold" style="font-size: 8.5pt;">
                    <td>DI SETUJUI</td>
                    <td colspan="2">PERUBAHAN****</td>
                    <td colspan="2">DI TANGGUHKAN****</td>
                    <td colspan="3">TIDAK DI SETUJUI****</td>
                </tr>
                <tr class="text-center" style="height: 18px;">
                    <td class="check-cell"><?= $is_pejabat_setuju ? $v : '' ?></td>
                    <td colspan="2" class="check-cell"><?= $cuti->pejabat_berwenang_keputusan == 'PERUBAHAN' ? $v : '' ?></td>
                    <td colspan="2" class="check-cell"><?= $cuti->pejabat_berwenang_keputusan == 'DI TANGGUHKAN' ? $v : '' ?></td>
                    <td colspan="3" class="check-cell"><?= $cuti->pejabat_berwenang_keputusan == 'TIDAK DI SETUJUI' ? $v : '' ?></td>
                </tr>
                <tr>
                    <!-- CATATAN BKN DI SISI KIRI (Kolom A-E) -->
                    <td colspan="5" rowspan="3" style="vertical-align: top; padding: 4px 6px; border-right: 1px solid #000; border-top: none; border-bottom: 1px solid #000;">
                        <div class="notes-text">
                            <strong>Catatan :</strong><br>
                            * Coret tidak perlu<br>
                            ** Pilih salah satu dengan memberi tanda centang (√)<br>
                            ***Diisi oleh pejabat yang menangani bidang kepegawaian sebelum pegawai mengajukan cuti<br>
                            **** diberi tanda centang dan alasannya<br>
                            N = Cuti tahun berjalan<br>
                            N-1 = Sisa cuti 1 tahun sebelumnya<br>
                            N-2 = Sisa cuti 2 tahun sebelumnya
                        </div>
                    </td>
                    <!-- TANDA TANGAN PEJABAT DI SISI KANAN (Kolom F-H) -->
                    <td colspan="3" class="text-center" style="border-left: none; border-top: none; border-bottom: none; padding-top: 5px;">
                        <?= $pejabat_jabatan ?>
                    </td>
                </tr>
                <tr>
                    <td colspan="3" class="sig-space" style="border-left: none; border-top: none; border-bottom: none;"></td>
                </tr>
                <tr>
                    <td colspan="3" class="text-center" style="border-left: none; border-top: none; border-bottom: 1px solid #000; padding-bottom: 5px;">
                        <div class="fw-bold"><?= $pejabat_nama ?></div>
                        <div>NIP. <?= $pejabat_nip ?></div>
                    </td>
                </tr>
            </tbody>
        </table>

    </div>

</body>
</html>
