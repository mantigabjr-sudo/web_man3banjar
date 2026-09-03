<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kartu Peserta Tes PMB - <?= htmlspecialchars($siswa->nama_lengkap ?? 'Peserta', ENT_QUOTES, 'UTF-8') ?></title>
    <link rel="icon" type="image/png" href="<?= base_url('assets/img/favicon.png') ?>">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        @page {
            size: A4 portrait;
            margin: 10mm 12mm 10mm 12mm;
        }
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }
        body {
            font-family: 'Inter', system-ui, -apple-system, sans-serif;
            background: #f1f5f9;
            color: #0f172a;
            padding: 20px;
            font-size: 12px;
            line-height: 1.4;
        }
        .kartu-container {
            max-width: 780px;
            margin: 0 auto;
            background: #ffffff;
            border: 2px solid #059669;
            border-radius: 12px;
            padding: 24px;
            box-shadow: 0 10px 25px rgba(0,0,0,0.08);
            position: relative;
        }
        .kop-surat {
            display: flex;
            align-items: center;
            justify-content: space-between;
            border-bottom: 2.5px solid #064e3b;
            padding-bottom: 12px;
            margin-bottom: 16px;
        }
        .kop-logo {
            width: 60px;
            height: 60px;
            object-fit: contain;
        }
        .kop-text {
            text-align: center;
            flex-grow: 1;
            padding: 0 15px;
        }
        .kop-text h4 {
            font-size: 13px;
            font-weight: 700;
            color: #334155;
            letter-spacing: 0.5px;
            margin-bottom: 2px;
        }
        .kop-text h2 {
            font-size: 18px;
            font-weight: 900;
            color: #064e3b;
            margin-bottom: 2px;
        }
        .kop-text p {
            font-size: 10.5px;
            color: #64748b;
            line-height: 1.3;
        }
        .kartu-title-box {
            background: #ecfdf5;
            border: 1px solid #a7f3d0;
            border-radius: 8px;
            padding: 8px 14px;
            text-align: center;
            margin-bottom: 18px;
        }
        .kartu-title-box h3 {
            font-size: 15px;
            font-weight: 900;
            color: #065f46;
            letter-spacing: 0.5px;
            text-transform: uppercase;
        }
        .kartu-title-box span {
            font-size: 11.5px;
            font-weight: 700;
            color: #047857;
        }
        .kartu-grid {
            display: grid;
            grid-template-columns: 140px 1fr;
            gap: 20px;
            margin-bottom: 18px;
        }
        .foto-box {
            width: 140px;
            height: 180px;
            border: 2px dashed #cbd5e1;
            border-radius: 8px;
            background: #f8fafc;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            overflow: hidden;
            text-align: center;
            padding: 4px;
        }
        .foto-box img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            border-radius: 6px;
        }
        .biodata-table {
            width: 100%;
            border-collapse: collapse;
        }
        .biodata-table td {
            padding: 4px 6px;
            vertical-align: top;
            font-size: 12px;
        }
        .biodata-table td.label {
            width: 160px;
            font-weight: 600;
            color: #475569;
        }
        .biodata-table td.colon {
            width: 10px;
            font-weight: bold;
        }
        .biodata-table td.value {
            font-weight: 700;
            color: #0f172a;
        }
        .badge-highlight {
            display: inline-block;
            background: #065f46;
            color: #ffffff;
            font-size: 14px;
            font-weight: 900;
            padding: 4px 12px;
            border-radius: 6px;
            letter-spacing: 1px;
        }
        .jadwal-tes-box {
            background: #fffbeb;
            border: 1.5px solid #fde68a;
            border-radius: 10px;
            padding: 12px 16px;
            margin-bottom: 16px;
        }
        .jadwal-tes-box h4 {
            font-size: 13px;
            font-weight: 800;
            color: #92400e;
            margin-bottom: 8px;
            display: flex;
            align-items: center;
            gap: 6px;
        }
        .jadwal-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 10px;
        }
        .jadwal-item {
            background: #ffffff;
            border: 1px solid #fef3c7;
            padding: 8px 10px;
            border-radius: 6px;
        }
        .jadwal-item small {
            display: block;
            font-size: 10px;
            color: #78350f;
            font-weight: 600;
            text-transform: uppercase;
        }
        .jadwal-item strong {
            display: block;
            font-size: 12px;
            color: #451a03;
        }
        .petunjuk-box {
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            padding: 10px 14px;
            margin-bottom: 18px;
            font-size: 11px;
            color: #334155;
        }
        .petunjuk-box ol {
            margin-left: 18px;
            margin-top: 4px;
        }
        .petunjuk-box li {
            margin-bottom: 2px;
        }
        .ttd-box {
            display: flex;
            justify-content: space-between;
            align-items: flex-end;
            margin-top: 10px;
            padding-top: 10px;
        }
        .qr-wrap {
            text-align: center;
        }
        .qr-placeholder {
            width: 80px;
            height: 80px;
            border: 1px solid #cbd5e1;
            padding: 4px;
            border-radius: 6px;
            display: inline-block;
        }
        .print-btn-bar {
            max-width: 780px;
            margin: 0 auto 16px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .btn-action {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 10px 20px;
            border-radius: 8px;
            font-weight: 700;
            font-size: 13px;
            cursor: pointer;
            text-decoration: none;
            border: none;
            transition: all 0.2s;
        }
        .btn-print { background: #059669; color: #fff; }
        .btn-back { background: #e2e8f0; color: #334155; }
        .btn-print:hover { background: #047857; }
        .btn-back:hover { background: #cbd5e1; }

        @media print {
            body {
                background: #fff;
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
        
        <!-- KOP MADRASAH -->
        <div class="kop-surat">
            <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/a/a2/Kementerian_Agama_Republik_Indonesia_logo.svg/1200px-Kementerian_Agama_Republik_Indonesia_logo.svg.png" class="kop-logo" alt="Logo Kemenag">
            <div class="kop-text">
                <h4>KEMENTERIAN AGAMA REPUBLIK INDONESIA</h4>
                <h2><?= strtoupper(htmlspecialchars($profil_website->nama_madrasah ?? 'MAN 3 BANJAR', ENT_QUOTES, 'UTF-8')) ?></h2>
                <p><?= htmlspecialchars($profil_website->alamat ?? 'Jl. Pendidikan No. 1, Kab. Banjar, Kalimantan Selatan', ENT_QUOTES, 'UTF-8') ?> | Telp/WA: <?= htmlspecialchars($profil_website->no_telepon ?? ($profil_website->wa_number ?? '-'), ENT_QUOTES, 'UTF-8') ?> | Website: <?= base_url() ?></p>
            </div>
            <img src="<?= base_url('assets/img/logo-madrasah.png') ?>" class="kop-logo" alt="Logo Madrasah" onerror="this.src='https://cdn-icons-png.flaticon.com/512/2997/2997295.png'">
        </div>

        <!-- JUDUL KARTU -->
        <div class="kartu-title-box">
            <h3>KARTU TANDA PESERTA SELEKSI &amp; UJIAN <?= htmlspecialchars($nama_ppdb ?? 'PMB', ENT_QUOTES, 'UTF-8') ?></h3>
            <span>TAHUN AJARAN <?= htmlspecialchars($settings->tahun_ajaran ?? '2026/2027', ENT_QUOTES, 'UTF-8') ?></span>
        </div>

        <!-- BIODATA & FOTO -->
        <div class="kartu-grid">
            <div class="foto-box">
                <?php 
                $foto_src = '';
                if(!empty($siswa->foto)){
                    $foto_src = base_url('uploads/temp/ppdb/' . $siswa->foto);
                }
                ?>
                <?php if(!empty($foto_src)): ?>
                    <img src="<?= $foto_src ?>" alt="Foto <?= htmlspecialchars($siswa->nama_lengkap, ENT_QUOTES, 'UTF-8') ?>" onerror="this.parentElement.innerHTML='<div class=\'text-muted text-center\'><i class=\'bi bi-person fs-1\'></i><br><small>Pas Foto 3x4</small></div>'">
                <?php else: ?>
                    <div style="color: #94a3b8; font-size: 11px;">
                        <i class="bi bi-person-bounding-box" style="font-size: 38px; color: #cbd5e1;"></i>
                        <div style="margin-top: 4px; font-weight: 600;">PAS FOTO 3x4</div>
                    </div>
                <?php endif; ?>
            </div>

            <div>
                <table class="biodata-table">
                    <tr>
                        <td class="label">Nomor Peserta Tes</td>
                        <td class="colon">:</td>
                        <td class="value">
                            <?php if(!empty($siswa->no_peserta_tes)): ?>
                                <span class="badge-highlight"><?= htmlspecialchars($siswa->no_peserta_tes, ENT_QUOTES, 'UTF-8') ?></span>
                            <?php else: ?>
                                <span class="badge-highlight" style="background:#0284c7;"><?= htmlspecialchars($siswa->no_pendaftaran, ENT_QUOTES, 'UTF-8') ?></span>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <tr>
                        <td class="label">Nomor Pendaftaran</td>
                        <td class="colon">:</td>
                        <td class="value"><?= htmlspecialchars($siswa->no_pendaftaran ?? '-', ENT_QUOTES, 'UTF-8') ?></td>
                    </tr>
                    <tr>
                        <td class="label">Nama Lengkap</td>
                        <td class="colon">:</td>
                        <td class="value" style="font-size: 13.5px; color: #064e3b;"><?= strtoupper(htmlspecialchars($siswa->nama_lengkap ?? '-', ENT_QUOTES, 'UTF-8')) ?></td>
                    </tr>
                    <tr>
                        <td class="label">NISN / NIK</td>
                        <td class="colon">:</td>
                        <td class="value"><?= htmlspecialchars($siswa->nisn ?? '-', ENT_QUOTES, 'UTF-8') ?> / <?= htmlspecialchars($siswa->nik ?? '-', ENT_QUOTES, 'UTF-8') ?></td>
                    </tr>
                    <tr>
                        <td class="label">Tempat, Tanggal Lahir</td>
                        <td class="colon">:</td>
                        <td class="value">
                            <?= htmlspecialchars($siswa->tempat_lahir ?? '-', ENT_QUOTES, 'UTF-8') ?>, 
                            <?= !empty($siswa->tanggal_lahir) ? (function_exists('tanggal_indo') ? tanggal_indo($siswa->tanggal_lahir) : $siswa->tanggal_lahir) : '-' ?>
                        </td>
                    </tr>
                    <tr>
                        <td class="label">Jenis Kelamin</td>
                        <td class="colon">:</td>
                        <td class="value"><?= ($siswa->jk == 'L' ? 'Laki-laki' : 'Perempuan') ?></td>
                    </tr>
                    <tr>
                        <td class="label">Asal Sekolah</td>
                        <td class="colon">:</td>
                        <td class="value"><?= htmlspecialchars($siswa->asal_sekolah ?? '-', ENT_QUOTES, 'UTF-8') ?></td>
                    </tr>
                    <tr>
                        <td class="label">Jalur Pendaftaran</td>
                        <td class="colon">:</td>
                        <td class="value"><span style="color: #059669; font-weight:800;"><?= htmlspecialchars($siswa->jalur_pendaftaran ?? 'Reguler', ENT_QUOTES, 'UTF-8') ?></span> (Kelas X - Fase E Umum)</td>
                    </tr>
                    <tr>
                        <td class="label">No. WhatsApp / Email</td>
                        <td class="colon">:</td>
                        <td class="value"><?= htmlspecialchars($siswa->no_hp ?? '-', ENT_QUOTES, 'UTF-8') ?><?= !empty($siswa->email) ? ' &bull; ' . htmlspecialchars($siswa->email, ENT_QUOTES, 'UTF-8') : '' ?></td>
                    </tr>
                </table>
            </div>
        </div>

        <!-- JADWAL & LOKASI TES -->
        <div class="jadwal-tes-box">
            <h4><i class="bi bi-clock-history"></i> JADWAL &amp; LOKASI SELEKSI / UJIAN</h4>
            <div class="jadwal-grid">
                <div class="jadwal-item">
                    <small>Tanggal Ujian</small>
                    <strong>
                        <?php 
                        $tgl_tes = !empty($siswa->tanggal_tes) ? $siswa->tanggal_tes : ($settings->default_tanggal_tes ?? null);
                        echo !empty($tgl_tes) ? (function_exists('tanggal_indo') ? tanggal_indo($tgl_tes) : $tgl_tes) : 'Sesuai Jadwal Panitia';
                        ?>
                    </strong>
                </div>
                <div class="jadwal-item">
                    <small>Waktu / Jam</small>
                    <strong><?= htmlspecialchars($siswa->jam_tes ?? ($settings->default_jam_tes ?? '08:00 - Selesai WITA'), ENT_QUOTES, 'UTF-8') ?></strong>
                </div>
                <div class="jadwal-item">
                    <small>Ruang / Lokasi</small>
                    <strong><?= htmlspecialchars($siswa->ruang_tes ?? ($settings->default_ruang_tes ?? 'Kampus MAN 3 Banjar'), ENT_QUOTES, 'UTF-8') ?></strong>
                </div>
            </div>
        </div>

        <!-- PETUNJUK & TATA TERTIB -->
        <div class="petunjuk-box">
            <strong>TATA TERTIB &amp; KETENTUAN PESERTA UJIAN:</strong>
            <ol>
                <li>Peserta wajib membawa <strong>Kartu Tanda Peserta Ujian</strong> ini dan Kartu Identitas (NISN / Kartu Pelajar).</li>
                <li>Membawa alat tulis lengkap (Pensil 2B, Pulpen Hitam, Penghapus, dan Papan Ujian).</li>
                <li>Mengenakan pakaian seragam sekolah asal (SMP/MTs) rapi, sopan, bersepatu, dan menutup aurat.</li>
                <li>Hadir di lokasi ujian paling lambat <strong>15 menit</strong> sebelum jadwal ujian dimulai.</li>
            </ol>
        </div>

        <!-- TANDA TANGAN & QR CODE -->
        <div class="ttd-box">
            <div class="qr-wrap">
                <div class="qr-placeholder">
                    <img src="https://api.qrserver.com/v1/create-qr-code/?size=150x150&data=<?= urlencode(base_url('ppdb/cetak_kartu/' . ($siswa->no_pendaftaran ?? $siswa->id))) ?>" style="width: 100%; height: 100%;" alt="QR Verifikasi">
                </div>
                <div style="font-size: 9.5px; color: #64748b; margin-top: 2px;">Scan untuk Verifikasi</div>
            </div>

            <div style="text-align: center; width: 160px;">
                <div style="font-size: 11px; color: #64748b;">Peserta Ujian,</div>
                <div style="height: 45px;"></div>
                <div style="font-weight: 700; border-bottom: 1px solid #334155; padding-bottom: 2px;">
                    <?= strtoupper(htmlspecialchars($siswa->nama_lengkap ?? '', ENT_QUOTES, 'UTF-8')) ?>
                </div>
                <div style="font-size: 10px; color: #64748b;">NISN: <?= htmlspecialchars($siswa->nisn ?? '-', ENT_QUOTES, 'UTF-8') ?></div>
            </div>

            <div style="text-align: center; width: 220px;">
                <div style="font-size: 11px; color: #475569;">
                    Banjar, <?= function_exists('tanggal_indo') ? tanggal_indo(date('Y-m-d')) : date('d F Y') ?>
                </div>
                <div style="font-size: 11px; font-weight: 700; color: #064e3b;">Panitia PMB MAN 3 Banjar</div>
                <div style="height: 45px;"></div>
                <div style="font-weight: 700; border-bottom: 1px solid #334155; padding-bottom: 2px;">
                    Panitia Penerimaan Murid Baru
                </div>
                <div style="font-size: 10px; color: #64748b;">NIP. -</div>
            </div>
        </div>

    </div>

</body>
</html>
