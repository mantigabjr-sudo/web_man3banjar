<?php
$nama_siswa = $siswa->nama_lengkap ?? '-';
$nisn = $siswa->nisn ?? '-';
$nik = $siswa->nik ?? '-';
$no_pendaftaran = $siswa->no_pendaftaran ?? '-';
$no_peserta = !empty($siswa->no_peserta_tes) ? $siswa->no_peserta_tes : $no_pendaftaran;
$jalur = $siswa->jalur_pendaftaran ?? 'Reguler';
$asal_sekolah = $siswa->asal_sekolah ?? '-';
$tempat_lahir = $siswa->tempat_lahir ?? '-';
$tanggal_lahir = !empty($siswa->tanggal_lahir) ? date('d F Y', strtotime($siswa->tanggal_lahir)) : '-';
$jk = ($siswa->jk ?? '') == 'L' ? 'Laki-laki' : 'Perempuan';
$no_hp = $siswa->no_hp ?? '-';
$email = $siswa->email ?? '';

$tgl_tes = !empty($siswa->tanggal_tes) ? date('d F Y', strtotime($siswa->tanggal_tes)) : 'Sesuai Jadwal Panitia';
$jam_tes = !empty($siswa->jam_tes) ? $siswa->jam_tes : '08:00 - 11.30 WITA';
$ruang_tes = !empty($siswa->ruang_tes) ? $siswa->ruang_tes : 'Kampus MAN 3 Banjar';

// Logo Kemenag Base64 / Local URL
$logo_kemenag_path = FCPATH.'assets/brand/logo-kemenag.png';
if(!file_exists($logo_kemenag_path)){
    $logo_kemenag_path = FCPATH.'assets/img/logo-kemenag.png';
}
$logo_kemenag_src = file_exists($logo_kemenag_path) ? 'data:image/png;base64,'.base64_encode(file_get_contents($logo_kemenag_path)) : base_url('assets/brand/logo-kemenag.png');

// Logo Madrasah Base64 / Local URL
$logo_madrasah_path = FCPATH.'assets/img/logo-madrasah.png';
$logo_madrasah_src = file_exists($logo_madrasah_path) ? 'data:image/png;base64,'.base64_encode(file_get_contents($logo_madrasah_path)) : base_url('assets/img/logo-madrasah.png');

// Foto Siswa
$foto_path = !empty($siswa->foto) ? FCPATH.'uploads/temp/ppdb/'.$siswa->foto : '';
$foto_src = (file_exists($foto_path) && !empty($siswa->foto)) ? base_url('uploads/temp/ppdb/'.$siswa->foto) : '';

// Data QR Code
$qr_data = "KARTU-PMB|NO:{$no_peserta}|NISN:{$nisn}|NAMA:{$nama_siswa}|MAN3BANJAR";
$qr_url = "https://api.qrserver.com/v1/create-qr-code/?size=150x150&data=".urlencode($qr_data);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kartu Peserta Tes PMB - <?= htmlspecialchars($nama_siswa) ?></title>
    
    <link rel="icon" type="image/png" href="<?= base_url('assets/img/favicon.png') ?>">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    
    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            font-family: Arial, Helvetica, sans-serif;
        }

        body {
            background-color: #f1f5f9;
            padding: 24px;
            color: #0f172a;
        }

        /* ── Action Bar (Hidden when Printing) ── */
        .print-btn-bar {
            max-width: 820px;
            margin: 0 auto 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 12px;
        }

        .btn-action {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 10px 20px;
            border-radius: 50px;
            font-size: 13.5px;
            font-weight: 700;
            text-decoration: none;
            cursor: pointer;
            border: none;
            transition: all 0.2s;
        }

        .btn-back {
            background: #ffffff;
            color: #475569;
            border: 1.5px solid #cbd5e1;
        }

        .btn-back:hover {
            background: #f8fafc;
            color: #0f172a;
        }

        .btn-print {
            background: #059669;
            color: #ffffff;
            box-shadow: 0 4px 14px rgba(5, 150, 105, 0.3);
        }

        .btn-print:hover {
            background: #047857;
            transform: translateY(-1px);
        }

        /* ── KARTU CONTAINER ── */
        .kartu-container {
            max-width: 820px;
            margin: 0 auto;
            background: #ffffff;
            border: 2px solid #059669;
            border-radius: 12px;
            padding: 24px 28px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.08);
            position: relative;
        }

        /* ── KOP SURAT ── */
        .kop-surat {
            display: flex;
            align-items: center;
            justify-content: space-between;
            border-bottom: 3px double #059669;
            padding-bottom: 12px;
            margin-bottom: 16px;
            gap: 16px;
        }

        .kop-logo {
            width: 72px;
            height: 72px;
            object-fit: contain;
            flex-shrink: 0;
        }

        .kop-text {
            text-align: center;
            flex-grow: 1;
        }

        .kop-text h4 {
            font-size: 13.5px;
            font-weight: 700;
            color: #334155;
            letter-spacing: 0.5px;
            margin-bottom: 2px;
            text-transform: uppercase;
        }

        .kop-text h2 {
            font-size: 19px;
            font-weight: 900;
            color: #064e3b;
            margin-bottom: 4px;
            letter-spacing: 0.5px;
        }

        .kop-text p {
            font-size: 10.5px;
            color: #64748b;
            line-height: 1.35;
            margin: 0;
        }

        /* ── JUDUL KARTU ── */
        .kartu-title-box {
            background: #ecfdf5;
            border: 1.5px solid #a7f3d0;
            border-radius: 8px;
            padding: 8px 14px;
            text-align: center;
            margin-bottom: 18px;
        }

        .kartu-title-box h3 {
            font-size: 14.5px;
            font-weight: 900;
            color: #065f46;
            margin: 0 0 2px 0;
            letter-spacing: 0.5px;
        }

        .kartu-title-box span {
            font-size: 11.5px;
            font-weight: 700;
            color: #047857;
        }

        /* ── KONTEN BIODATA & FOTO ── */
        .kartu-grid {
            display: grid;
            grid-template-columns: 140px 1fr;
            gap: 20px;
            align-items: start;
            margin-bottom: 18px;
        }

        .foto-box {
            width: 140px;
            height: 186px;
            border: 2px dashed #94a3b8;
            border-radius: 8px;
            background: #f8fafc;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
            text-align: center;
            flex-shrink: 0;
        }

        .foto-box img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .biodata-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 12px;
        }

        .biodata-table td {
            padding: 5px 4px;
            vertical-align: top;
        }

        .biodata-table td.lbl {
            width: 155px;
            color: #475569;
            font-weight: 600;
        }

        .biodata-table td.sep {
            width: 12px;
            text-align: center;
            color: #64748b;
            font-weight: 700;
        }

        .biodata-table td.val {
            color: #0f172a;
            font-weight: 700;
        }

        .badge-no-peserta {
            background: #0284c7;
            color: #ffffff;
            padding: 3px 10px;
            border-radius: 6px;
            font-family: monospace;
            font-size: 13.5px;
            font-weight: 800;
            display: inline-block;
            letter-spacing: 0.5px;
        }

        /* ── KOTAK JADWAL UJIAN ── */
        .jadwal-box {
            background: #fffbeb;
            border: 1.5px solid #fde68a;
            border-radius: 8px;
            padding: 12px 16px;
            margin-bottom: 16px;
        }

        .jadwal-title {
            font-size: 12px;
            font-weight: 800;
            color: #92400e;
            margin-bottom: 8px;
            display: flex;
            align-items: center;
            gap: 6px;
            text-transform: uppercase;
        }

        .jadwal-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 12px;
        }

        .jadwal-item {
            background: #ffffff;
            border: 1px solid #fef3c7;
            padding: 8px 12px;
            border-radius: 6px;
        }

        .jadwal-item small {
            display: block;
            font-size: 10px;
            font-weight: 700;
            color: #78350f;
            text-transform: uppercase;
        }

        .jadwal-item strong {
            display: block;
            font-size: 12px;
            color: #0f172a;
            margin-top: 2px;
        }

        /* ── TATA TERTIB ── */
        .rules-box {
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            padding: 10px 14px;
            margin-bottom: 18px;
            font-size: 10.5px;
            color: #334155;
            line-height: 1.45;
        }

        .rules-box strong {
            display: block;
            font-size: 11px;
            color: #0f172a;
            margin-bottom: 4px;
            text-transform: uppercase;
        }

        .rules-box ol {
            padding-left: 16px;
            margin: 0;
        }

        /* ── TANDA TANGAN & QR ── */
        .footer-sign {
            display: flex;
            justify-content: space-between;
            align-items: flex-end;
            margin-top: 10px;
        }

        .qr-wrap {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .qr-wrap img {
            width: 68px;
            height: 68px;
            border: 1px solid #cbd5e1;
            padding: 2px;
            border-radius: 4px;
            background: #fff;
        }

        .qr-wrap span {
            font-size: 10px;
            color: #64748b;
            line-height: 1.3;
        }

        .sign-box {
            text-align: center;
            min-width: 220px;
        }

        .sign-box p {
            font-size: 11px;
            color: #475569;
            margin-bottom: 45px;
        }

        .sign-box strong {
            display: block;
            font-size: 12px;
            color: #0f172a;
            text-decoration: underline;
        }

        .sign-box span {
            font-size: 10.5px;
            color: #64748b;
        }

        /* ── PRINT MEDIA QUERY ── */
        @media print {
            body {
                background: #ffffff;
                padding: 0;
            }

            .print-btn-bar {
                display: none !important;
            }

            .kartu-container {
                border: 2px solid #064e3b;
                box-shadow: none;
                padding: 16px;
                max-width: 100%;
                border-radius: 0;
            }

            @page {
                size: A4 portrait;
                margin: 10mm;
            }
        }
    </style>
</head>
<body>

    <!-- Tombol Cetak / Navigasi -->
    <div class="print-btn-bar">
        <a href="<?= base_url('ppdb/dashboard') ?>" class="btn-action btn-back">
            <i class="bi bi-arrow-left"></i> Kembali ke Dashboard
        </a>
        <button onclick="window.print()" class="btn-action btn-print">
            <i class="bi bi-printer-fill"></i> Cetak Kartu Peserta (PDF / Print)
        </button>
    </div>

    <!-- Kartu Container -->
    <div class="kartu-container">
        
        <!-- KOP MADRASAH RESMI -->
        <div class="kop-surat">
            <img src="<?= $logo_kemenag_src ?>" class="kop-logo" alt="Logo Kemenag">
            <div class="kop-text">
                <h4>KEMENTERIAN AGAMA REPUBLIK INDONESIA</h4>
                <h2><?= strtoupper(htmlspecialchars($profil_website->nama_madrasah ?? 'MAN 3 BANJAR', ENT_QUOTES, 'UTF-8')) ?></h2>
                <p><?= htmlspecialchars($profil_website->alamat ?? 'Jl. A. Yani No.Km. 15.200, Gambut, Kec. Gambut, Kabupaten Banjar, Kalimantan Selatan 70652', ENT_QUOTES, 'UTF-8') ?> | Telp/WA: <?= htmlspecialchars($profil_website->no_telepon ?? ($profil_website->wa_number ?? '-'), ENT_QUOTES, 'UTF-8') ?> | Website: https://man3banjar.sch.id/</p>
            </div>
            <img src="<?= $logo_madrasah_src ?>" class="kop-logo" alt="Logo Madrasah">
        </div>

        <!-- JUDUL KARTU -->
        <div class="kartu-title-box">
            <h3>KARTU TANDA PESERTA SELEKSI &amp; UJIAN <?= htmlspecialchars($nama_ppdb ?? 'PMB', ENT_QUOTES, 'UTF-8') ?></h3>
            <span>TAHUN AJARAN <?= htmlspecialchars($settings->tahun_ajaran ?? '2026/2027', ENT_QUOTES, 'UTF-8') ?></span>
        </div>

        <!-- BIODATA & FOTO -->
        <div class="kartu-grid">
            <div class="foto-box">
                <?php if(!empty($foto_src)): ?>
                    <img src="<?= $foto_src ?>" alt="Foto <?= htmlspecialchars($nama_siswa, ENT_QUOTES, 'UTF-8') ?>">
                <?php else: ?>
                    <div style="color: #94a3b8; font-size: 11px;">
                        <i class="bi bi-person-bounding-box" style="font-size: 38px; color: #cbd5e1;"></i>
                        <div style="margin-top: 4px; font-weight: 700;">PAS FOTO 3×4</div>
                    </div>
                <?php endif; ?>
            </div>

            <div>
                <table class="biodata-table">
                    <tr>
                        <td class="lbl">Nomor Peserta Tes</td>
                        <td class="sep">:</td>
                        <td class="val">
                            <span class="badge-no-peserta"><?= htmlspecialchars($no_peserta, ENT_QUOTES, 'UTF-8') ?></span>
                        </td>
                    </tr>
                    <tr>
                        <td class="lbl">Nomor Pendaftaran</td>
                        <td class="sep">:</td>
                        <td class="val"><?= htmlspecialchars($no_pendaftaran, ENT_QUOTES, 'UTF-8') ?></td>
                    </tr>
                    <tr>
                        <td class="lbl">Nama Lengkap</td>
                        <td class="sep">:</td>
                        <td class="val" style="color:#065f46; font-size:13px;"><?= strtoupper(htmlspecialchars($nama_siswa, ENT_QUOTES, 'UTF-8')) ?></td>
                    </tr>
                    <tr>
                        <td class="lbl">NISN / NIK</td>
                        <td class="sep">:</td>
                        <td class="val"><?= htmlspecialchars($nisn, ENT_QUOTES, 'UTF-8') ?> / <?= htmlspecialchars($nik, ENT_QUOTES, 'UTF-8') ?></td>
                    </tr>
                    <tr>
                        <td class="lbl">Tempat, Tanggal Lahir</td>
                        <td class="sep">:</td>
                        <td class="val"><?= htmlspecialchars($tempat_lahir, ENT_QUOTES, 'UTF-8') ?>, <?= htmlspecialchars($tanggal_lahir, ENT_QUOTES, 'UTF-8') ?></td>
                    </tr>
                    <tr>
                        <td class="lbl">Jenis Kelamin</td>
                        <td class="sep">:</td>
                        <td class="val"><?= htmlspecialchars($jk, ENT_QUOTES, 'UTF-8') ?></td>
                    </tr>
                    <tr>
                        <td class="lbl">Asal Sekolah (SMP/MTs)</td>
                        <td class="sep">:</td>
                        <td class="val"><?= htmlspecialchars($asal_sekolah, ENT_QUOTES, 'UTF-8') ?></td>
                    </tr>
                    <tr>
                        <td class="lbl">Jalur Pendaftaran</td>
                        <td class="sep">:</td>
                        <td class="val" style="color:#059669;"><strong><?= htmlspecialchars($jalur, ENT_QUOTES, 'UTF-8') ?></strong> (Kelas X - Fase E Umum)</td>
                    </tr>
                    <tr>
                        <td class="lbl">No. WhatsApp / Email</td>
                        <td class="sep">:</td>
                        <td class="val"><?= htmlspecialchars($no_hp, ENT_QUOTES, 'UTF-8') ?><?= !empty($email) ? ' &bull; '.htmlspecialchars($email, ENT_QUOTES, 'UTF-8') : '' ?></td>
                    </tr>
                </table>
            </div>
        </div>

        <!-- JADWAL & LOKASI SELEKSI / UJIAN -->
        <div class="jadwal-box">
            <div class="jadwal-title">
                <i class="bi bi-clock-history"></i> Jadwal &amp; Lokasi Seleksi / Ujian
            </div>
            <div class="jadwal-grid">
                <div class="jadwal-item">
                    <small>Tanggal Ujian</small>
                    <strong><?= htmlspecialchars($tgl_tes, ENT_QUOTES, 'UTF-8') ?></strong>
                </div>
                <div class="jadwal-item">
                    <small>Waktu / Jam</small>
                    <strong><?= htmlspecialchars($jam_tes, ENT_QUOTES, 'UTF-8') ?></strong>
                </div>
                <div class="jadwal-item">
                    <small>Ruang / Lokasi</small>
                    <strong><?= htmlspecialchars($ruang_tes, ENT_QUOTES, 'UTF-8') ?></strong>
                </div>
            </div>
        </div>

        <!-- TATA TERTIB & KETENTUAN -->
        <div class="rules-box">
            <strong>Tata Tertib &amp; Ketentuan Peserta Ujian:</strong>
            <ol>
                <li>Peserta wajib membawa <strong>Kartu Tanda Peserta Ujian</strong> ini dan Kartu Identitas (NISN / Kartu Pelajar).</li>
                <li>Membawa alat tulis lengkap (Pensil 2B, Pulpen Hitam, Penghapus, dan Papan Ujian).</li>
                <li>Mengenakan pakaian seragam sekolah asal (SMP/MTs) rapi, sopan, bersepatu, dan menutup aurat.</li>
                <li>Hadir di lokasi ujian paling lambat <strong>15 menit</strong> sebelum jadwal ujian dimulai.</li>
            </ol>
        </div>

        <!-- FOOTER TANDA TANGAN -->
        <div class="footer-sign">
            <div class="qr-wrap">
                <img src="<?= $qr_url ?>" alt="QR Code Verifikasi">
                <span>Scan untuk Verifikasi<br>Keabsahan Kartu</span>
            </div>

            <div class="sign-box">
                <p>Banjar, <?= date('d F Y') ?><br><strong>Panitia PMB MAN 3 Banjar</strong></p>
                <strong>Panitia Penerimaan Murid Baru</strong>
                <span>NIP. -</span>
            </div>
        </div>

    </div>

</body>
</html>
