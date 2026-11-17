<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cetak Formulir Cuti ASN</title>
    <style>
        body {
            font-family: 'Times New Roman', Times, serif;
            font-size: 9pt;
            line-height: 1.15;
            color: #000;
            background: #fff;
            margin: 0;
            padding: 0;
        }

        .page {
            width: 210mm;
            min-height: 297mm;
            padding: 5mm 12mm;
            margin: 0 auto;
            background: white;
            box-sizing: border-box;
        }

        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .text-left { text-align: left; }
        .font-weight-bold { font-weight: bold; }
        
        .header-section {
            margin-bottom: 5px;
        }

        .kepada-section {
            width: 50%;
            margin-left: auto;
            margin-bottom: 6px;
        }

        .title {
            font-size: 11pt;
            font-weight: bold;
            text-align: center;
            margin-bottom: 8px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 4px;
        }

        table, th, td {
            border: 1px solid #000;
        }

        th, td {
            padding: 2.5px 5px;
            vertical-align: top;
        }

        .no-border {
            border: none !important;
        }

        .no-border th, .no-border td {
            border: none !important;
        }

        /* Checkbox symbol */
        .check {
            text-align: center;
            font-weight: bold;
        }

        /* Layout specific adjustments */
        .w-20 { width: 20%; }
        .w-30 { width: 30%; }
        .w-40 { width: 40%; }
        .w-50 { width: 50%; }
        .w-10 { width: 10%; }

        .signature-box {
            width: 40%;
            float: right;
            text-align: center;
            margin-top: 5px;
        }

        .signature-space {
            height: 55px; /* Ruang kosong yang cukup dan konsisten untuk tanda tangan */
        }

        .clear {
            clear: both;
        }

        .notes {
            font-size: 7.5pt;
            margin-top: 5px;
            line-height: 1.05;
        }

        @media print {
            body { background: transparent; }
            .page { margin: 0; padding: 0; width: 100%; height: auto; box-shadow: none; }
            @page { size: A4 portrait; margin: 4mm 10mm 4mm 10mm; }
        }
    </style>
</head>
<body>

<div class="page">

    <div class="header-section text-right">
        <?= $cuti->di_tempat ?>, <?= date('d F Y', strtotime($cuti->tanggal_surat)) ?><br>
    </div>

    <div class="kepada-section">
        Kepada<br>
        Yth. <?= !empty($cuti->yth_kepada) ? $cuti->yth_kepada : 'Kepala Kantor Kemenag Kab. Banjar' ?><br>
        Di - <?= !empty($cuti->di_tempat) ? $cuti->di_tempat : 'Martapura' ?>
    </div>

    <div class="title">
        FORMULIR PERMINTAAN DAN PEMBERIAN CUTI
    </div>

    <!-- I. DATA PEGAWAI -->
    <table>
        <tr>
            <td colspan="4">I. DATA PEGAWAI</td>
        </tr>
        <tr>
            <td class="w-20">Nama</td>
            <td class="w-30 font-weight-bold"><?= $cuti->nama_lengkap ?></td>
            <td class="w-20">NIP</td>
            <td class="w-30 font-weight-bold"><?= !empty($cuti->nip) ? $cuti->nip : '-' ?></td>
        </tr>
        <tr>
            <td>Jabatan</td>
            <td><?= !empty($cuti->ptk_jabatan) ? $cuti->ptk_jabatan : '-' ?></td>
            <td>Masa Kerja</td>
            <td><?= !empty($cuti->masa_kerja) ? $cuti->masa_kerja : '-' ?></td>
        </tr>
        <tr>
            <td>Unit Kerja</td>
            <td colspan="3">MAN 3 Banjar</td>
        </tr>
    </table>

    <!-- II. JENIS CUTI -->
    <table>
        <tr>
            <td colspan="4">II. JENIS CUTI YANG DIAMBIL**</td>
        </tr>
        <tr>
            <td class="w-40">Cuti Tahunan</td>
            <td class="w-10 check"><?= $cuti->jenis_cuti == 'Cuti Tahunan' ? 'v' : '-' ?></td>
            <td class="w-40">Cuti Besar</td>
            <td class="w-10 check"><?= $cuti->jenis_cuti == 'Cuti Besar' ? 'v' : '-' ?></td>
        </tr>
        <tr>
            <td>Cuti Sakit</td>
            <td class="check"><?= $cuti->jenis_cuti == 'Cuti Sakit' ? 'v' : '-' ?></td>
            <td>Cuti Melahirkan</td>
            <td class="check"><?= $cuti->jenis_cuti == 'Cuti Melahirkan' ? 'v' : '-' ?></td>
        </tr>
        <tr>
            <td>Cuti Alasan Penting</td>
            <td class="check"><?= $cuti->jenis_cuti == 'Cuti Alasan Penting' ? 'v' : '-' ?></td>
            <td>Cuti diluar Tanggungan Negara</td>
            <td class="check"><?= $cuti->jenis_cuti == 'Cuti diluar Tanggungan Negara' ? 'v' : '-' ?></td>
        </tr>
    </table>

    <!-- III. ALASAN CUTI -->
    <table>
        <tr>
            <td>III. ALASAN CUTI</td>
        </tr>
        <tr>
            <td style="min-height: 20px; padding-bottom: 3px;">
                <?= !empty($cuti->alasan_cuti) ? $cuti->alasan_cuti : '-' ?>
            </td>
        </tr>
    </table>

    <!-- IV. LAMANYA CUTI -->
    <table>
        <tr>
            <td colspan="4">IV. LAMANYA CUTI</td>
        </tr>
        <tr>
            <td class="w-20">Selama</td>
            <td class="w-30 text-center"><?= $cuti->lama_cuti_jumlah ?> (<?= $cuti->lama_cuti_satuan ?>)*</td>
            <td class="w-20">Mulai Tanggal</td>
            <td class="w-30 text-center">
                <?= date('d M Y', strtotime($cuti->tanggal_mulai)) ?> s.d <?= date('d M Y', strtotime($cuti->tanggal_selesai)) ?>
            </td>
        </tr>
    </table>

    <!-- V. CATATAN CUTI -->
    <table>
        <tr>
            <td colspan="5">V. CATATAN CUTI***</td>
        </tr>
        <tr>
            <td colspan="3" class="w-50">1. Cuti Tahunan</td>
            <td class="w-40">2. Cuti Besar</td>
            <td class="w-10 text-center"><?= !empty($cuti->catatan_cuti_besar) ? $cuti->catatan_cuti_besar : '-' ?></td>
        </tr>
        <tr>
            <td class="w-15 text-center">Tahun</td>
            <td class="w-10 text-center">Sisa</td>
            <td class="w-25 text-center">Keterangan</td>
            <td>3. Cuti Sakit</td>
            <td class="text-center"><?= !empty($cuti->catatan_cuti_sakit) ? $cuti->catatan_cuti_sakit : '-' ?></td>
        </tr>
        <tr>
            <td class="text-center">N-2</td>
            <td class="text-center"><?= !empty($cuti->catatan_cuti_tahunan_n2_sisa) ? $cuti->catatan_cuti_tahunan_n2_sisa : '-' ?></td>
            <td class="text-center"><?= !empty($cuti->catatan_cuti_tahunan_n2_ket) ? $cuti->catatan_cuti_tahunan_n2_ket : '' ?></td>
            <td>4. Cuti Melahirkan</td>
            <td class="text-center"><?= !empty($cuti->catatan_cuti_melahirkan) ? $cuti->catatan_cuti_melahirkan : '-' ?></td>
        </tr>
        <tr>
            <td class="text-center">N-1</td>
            <td class="text-center"><?= !empty($cuti->catatan_cuti_tahunan_n1_sisa) ? $cuti->catatan_cuti_tahunan_n1_sisa : '-' ?></td>
            <td class="text-center"><?= !empty($cuti->catatan_cuti_tahunan_n1_ket) ? $cuti->catatan_cuti_tahunan_n1_ket : '' ?></td>
            <td>5. Cuti Alasan Penting</td>
            <td class="text-center"><?= !empty($cuti->catatan_cuti_alasan_penting) ? $cuti->catatan_cuti_alasan_penting : '-' ?></td>
        </tr>
        <tr>
            <td class="text-center">N</td>
            <td class="text-center"><?= !empty($cuti->catatan_cuti_tahunan_n_sisa) ? $cuti->catatan_cuti_tahunan_n_sisa : '-' ?></td>
            <td class="text-center"><?= !empty($cuti->catatan_cuti_tahunan_n_ket) ? $cuti->catatan_cuti_tahunan_n_ket : '' ?></td>
            <td>6. Cuti diluar Tanggungan Negara</td>
            <td class="text-center"><?= !empty($cuti->catatan_cuti_diluar_tanggungan) ? $cuti->catatan_cuti_diluar_tanggungan : '-' ?></td>
        </tr>
    </table>

    <!-- VI. ALAMAT SELAMA CUTI -->
    <table>
        <tr>
            <td colspan="3">VI. ALAMAT SELAMA MENJALANKAN CUTI</td>
        </tr>
        <tr>
            <td style="height: 45px;" class="w-50">
                <?= !empty($cuti->alamat_cuti) ? nl2br($cuti->alamat_cuti) : '-' ?>
            </td>
            <td class="w-50" style="padding:0; border:none; vertical-align:top;">
                <table style="margin:0; border:none; width:100%; height:100%;">
                    <tr>
                        <td style="border-top:none; border-right:none; border-bottom:1px solid #000; width:30%;">Telp.</td>
                        <td style="border-top:none; border-right:none; border-bottom:1px solid #000; width:70%;">
                            <?= !empty($cuti->telepon_cuti) ? $cuti->telepon_cuti : '-' ?>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="2" class="text-center" style="border:none; padding-top:10px; padding-bottom:3px;">
                            Hormat Saya,
                            <div class="signature-space"></div>
                            <?= $cuti->nama_lengkap ?><br>
                            NIP. <?= !empty($cuti->nip) ? $cuti->nip : '-' ?>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>

    <!-- VII. PERTIMBANGAN ATASAN LANGSUNG -->
    <table>
        <tr>
            <td colspan="4">VII. PERTIMBANGAN ATASAN LANGSUNG**</td>
        </tr>
        <tr class="text-center">
            <td class="w-25">DI SETUJUI</td>
            <td class="w-25">PERUBAHAN****</td>
            <td class="w-25">DI TANGGUHKAN****</td>
            <td class="w-25">TIDAK DI SETUJUI****</td>
        </tr>
        <tr class="text-center" style="height:20px;">
            <td><?= $cuti->atasan_langsung_keputusan == 'DI SETUJUI' ? 'v' : '' ?></td>
            <td><?= $cuti->atasan_langsung_keputusan == 'PERUBAHAN' ? 'v' : '' ?></td>
            <td><?= $cuti->atasan_langsung_keputusan == 'DITANGGUHKAN' ? 'v' : '' ?></td>
            <td><?= $cuti->atasan_langsung_keputusan == 'TIDAK DI SETUJUI' ? 'v' : '' ?></td>
        </tr>
        <tr>
            <td colspan="3" style="border-right:none;"></td>
            <td class="text-center" style="border-left:none; padding-top:5px; padding-bottom:3px;">
                Atasan Langsung,
                <div class="signature-space"></div>
                <?= !empty($cuti->atasan_langsung_nama) ? $cuti->atasan_langsung_nama : '-' ?><br>
                NIP. <?= !empty($cuti->atasan_langsung_nip) ? $cuti->atasan_langsung_nip : '-' ?>
            </td>
        </tr>
    </table>

    <!-- VIII. KEPUTUSAN PEJABAT -->
    <table>
        <tr>
            <td colspan="4">VIII. KEPUTUSAN PEJABAT YANG BERWENANG MEMBERIKAN CUTI**</td>
        </tr>
        <tr class="text-center">
            <td class="w-25">DI SETUJUI</td>
            <td class="w-25">PERUBAHAN****</td>
            <td class="w-25">DI TANGGUHKAN****</td>
            <td class="w-25">TIDAK DI SETUJUI****</td>
        </tr>
        <tr class="text-center" style="height:20px;">
            <td><?= $cuti->pejabat_berwenang_keputusan == 'DI SETUJUI' ? 'v' : '' ?></td>
            <td><?= $cuti->pejabat_berwenang_keputusan == 'PERUBAHAN' ? 'v' : '' ?></td>
            <td><?= $cuti->pejabat_berwenang_keputusan == 'DITANGGUHKAN' ? 'v' : '' ?></td>
            <td><?= $cuti->pejabat_berwenang_keputusan == 'TIDAK DI SETUJUI' ? 'v' : '' ?></td>
        </tr>
        <tr>
            <td colspan="3" style="border-right:none;"></td>
            <td class="text-center" style="border-left:none; padding-top:5px; padding-bottom:5px;">
                <?= !empty($cuti->pejabat_berwenang_jabatan) ? $cuti->pejabat_berwenang_jabatan : 'Kepala Kantor Kemenag Kab. Banjar' ?>,
                <div class="signature-space"></div>
                <?= !empty($cuti->pejabat_berwenang_nama) ? $cuti->pejabat_berwenang_nama : '-' ?><br>
                NIP. <?= !empty($cuti->pejabat_berwenang_nip) ? $cuti->pejabat_berwenang_nip : '-' ?>
            </td>
        </tr>
    </table>

    <div class="notes">
        Catatan :<br>
        * Coret tidak perlu<br>
        ** Pilih salah satu dengan memberi tanda centang (v)<br>
        *** Diisi oleh pejabat yang menangani bidang kepegawaian sebelum pegawai mengajukan cuti<br>
        **** diberi tanda centang dan alasannya<br>
        N = Cuti tahun berjalan<br>
        N-1 = Sisa cuti 1 tahun sebelumnya<br>
        N-2 = Sisa cuti 2 tahun sebelumnya
    </div>

</div>

<script>
    window.onload = function() {
        window.print();
    }
</script>

</body>
</html>
