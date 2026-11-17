<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Layanan Mandiri Pengajuan Cuti ASN - MAN 3 Banjar</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- FontAwesome 6 -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" rel="stylesheet">
    <!-- Google Fonts Poppins -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <!-- Select2 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" rel="stylesheet" />

    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f0f4f8;
            color: #2d3748;
            min-height: 100vh;
        }

        .header-bg {
            background: linear-gradient(135deg, #0d5c3a 0%, #198754 50%, #20c997 100%);
            color: white;
            padding: 35px 0 25px 0;
            box-shadow: 0 4px 15px rgba(13, 92, 58, 0.2);
            border-bottom: 4px solid #ffc107;
        }

        .card-custom {
            border: none;
            border-radius: 14px;
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.06);
            background: #ffffff;
            margin-bottom: 24px;
            overflow: hidden;
        }

        .card-header-green {
            background-color: #f8fafc;
            border-bottom: 2px solid #e2e8f0;
            padding: 16px 22px;
            font-weight: 600;
            color: #1e3a2b;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .saldo-box {
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 10px;
            padding: 12px 14px;
            text-align: center;
            transition: all 0.2s ease;
        }

        .saldo-box:hover {
            border-color: #198754;
            transform: translateY(-2px);
        }

        .saldo-num {
            font-size: 1.5rem;
            font-weight: 700;
            color: #198754;
            line-height: 1.2;
        }

        .saldo-label {
            font-size: 0.75rem;
            color: #64748b;
            text-transform: uppercase;
            font-weight: 600;
        }

        .jenis-cuti-card {
            border: 2px solid #e2e8f0;
            border-radius: 10px;
            padding: 14px 16px;
            cursor: pointer;
            transition: all 0.2s ease;
            height: 100%;
        }

        .jenis-cuti-card:hover {
            border-color: #198754;
            background-color: #f0fdf4;
        }

        .jenis-cuti-card.active {
            border-color: #198754;
            background-color: #ecfdf5;
            box-shadow: 0 0 0 1px #198754;
        }

        .regulasi-badge {
            font-size: 0.72rem;
            padding: 3px 8px;
            border-radius: 6px;
            background: #e0f2fe;
            color: #0369a1;
            font-weight: 600;
        }

        .nav-tabs .nav-link {
            font-weight: 600;
            color: #64748b;
            border: none;
            padding: 12px 24px;
            border-bottom: 3px solid transparent;
        }

        .nav-tabs .nav-link.active {
            color: #198754;
            border-bottom: 3px solid #198754;
            background: transparent;
        }

        .info-callout {
            border-left: 4px solid #198754;
            background-color: #f0fdf4;
            padding: 14px 18px;
            border-radius: 0 8px 8px 0;
            margin-bottom: 20px;
        }

        .select2-container--bootstrap-5 .select2-selection {
            min-height: 46px;
            font-size: 1rem;
            border-radius: 8px;
        }

        .btn-submit {
            background: linear-gradient(135deg, #198754 0%, #157347 100%);
            border: none;
            font-weight: 600;
            padding: 12px 30px;
            border-radius: 8px;
            font-size: 1.05rem;
            transition: all 0.3s;
        }

        .btn-submit:hover {
            background: linear-gradient(135deg, #157347 0%, #0f5132 100%);
            box-shadow: 0 4px 12px rgba(21, 115, 71, 0.3);
            transform: translateY(-1px);
        }
    </style>
</head>
<body>

    <!-- HEADER BRANDING -->
    <div class="header-bg">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-9 d-flex align-items-center">
                    <img src="<?= base_url('assets/logo-kemenag.png') ?>" alt="Kemenag" height="65" class="me-3" onerror="this.style.display='none'">
                    <div>
                        <div class="text-warning text-uppercase small fw-bold tracking-wide">Kementerian Agama Republik Indonesia</div>
                        <h2 class="fw-bold mb-1">MAN 3 BANJAR</h2>
                        <div class="small opacity-90">Layanan Mandiri Pengajuan Formulir Cuti Pegawai ASN (PNS & PPPK) Sesuai Regulasi BKN</div>
                    </div>
                </div>
                <div class="col-md-3 text-md-end mt-3 mt-md-0 d-flex justify-content-md-end align-items-center gap-2">
                    <?php if (!empty($is_admin)): ?>
                        <button type="button" class="btn btn-sm btn-light text-success fw-bold rounded-pill px-3 py-1 shadow-sm" onclick="syncGoogleSheet(this)" title="Fitur Admin: Sinkronkan data jatah cuti dari Google Spreadsheet">
                            <i class="fa fa-sync me-1"></i> Sinkron Spreadsheet
                        </button>
                    <?php else: ?>
                        <a href="<?= site_url('auth') ?>" class="btn btn-sm btn-light text-success fw-bold rounded-pill px-3 py-1 shadow-sm" title="Login ke Sistem Informasi Madrasah">
                            <i class="fa fa-right-to-bracket me-1"></i> Login Akun
                        </a>
                    <?php endif; ?>
                    <span class="badge bg-warning text-dark px-3 py-2 rounded-pill fw-semibold">
                        <i class="fa fa-calendar-check me-1"></i> Tahun <?= date('Y') ?>
                    </span>
                </div>
            </div>
        </div>
    </div>

    <!-- MAIN CONTAINER -->
    <div class="container my-4">

        <!-- FLASH MESSAGES -->
        <?php if ($this->session->flashdata('error')): ?>
            <div class="alert alert-danger alert-dismissible fade show shadow-sm" role="alert">
                <i class="fa fa-exclamation-triangle me-2"></i><?= $this->session->flashdata('error') ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <!-- NAV TABS -->
        <ul class="nav nav-tabs mb-4 bg-white rounded-3 shadow-sm px-3 pt-2" id="cutiTabs" role="tablist">
            <li class="nav-item">
                <button class="nav-link active" id="form-tab" data-bs-toggle="tab" data-bs-target="#tab-form" type="button">
                    <i class="fa fa-pen-to-square me-2"></i>Formulir Pengajuan Cuti
                </button>
            </li>
            <li class="nav-item">
                <button class="nav-link" id="status-tab" data-bs-toggle="tab" data-bs-target="#tab-status" type="button">
                    <i class="fa fa-list-check me-2"></i>Hasil & Status Pengajuan Cuti
                </button>
            </li>
            <li class="nav-item">
                <button class="nav-link" id="regulasi-tab" data-bs-toggle="tab" data-bs-target="#tab-regulasi" type="button">
                    <i class="fa fa-scale-balanced me-2"></i>Ketentuan Pemerintah (BKN)
                </button>
            </li>
        </ul>

        <div class="tab-content" id="cutiTabsContent">

            <!-- TAB 1: FORM PENGAJUAN CUTI (MODE MANDIRI BYPASS) -->
            <div class="tab-pane fade show active" id="tab-form" role="tabpanel">

                <!-- INFORMASI ALUR BYPASS -->
                <div class="alert alert-success border-0 shadow-sm d-flex align-items-center mb-4 py-3 px-4 rounded-3" style="background-color: #ecfdf5;">
                    <i class="fa fa-shield-halved fa-2x text-success me-3"></i>
                    <div>
                        <strong class="d-block text-success">Layanan Pengajuan Cuti Mandiri (Mode Bypass Tanpa Login)</strong>
                        <span class="text-secondary small">
                            Fasilitas ini disediakan untuk mempermudah Bapak/Ibu guru dan staf ASN mengajukan permohonan cuti secara cepat. 
                            Setiap permohonan yang diajukan berstatus <strong>Menunggu Persetujuan</strong> dan wajib diverifikasi serta disetujui terlebih dahulu oleh Kepala Madrasah dan Admin Tata Usaha sebelum formulir resmi dapat disahkan.
                        </span>
                    </div>
                </div>

                <form action="<?= site_url('cuti/submit') ?>" method="POST" enctype="multipart/form-data" id="formCuti">

                    <!-- LANGKAH 1: IDENTITAS PEGAWAI (AUTOCOMPLETE NIP) -->
                    <div class="card card-custom">
                        <div class="card-header-green">
                            <span><i class="fa fa-id-card me-2 text-success"></i>1. Identitas Pegawai Pemohon</span>
                            <span class="badge bg-success-subtle text-success">Tinggal Masukkan NIP</span>
                        </div>
                        <div class="card-body p-4">
                            <div class="row g-3">
                                <div class="col-md-12">
                                    <label class="form-label fw-bold">Pilih Pegawai / Masukkan NIP <span class="text-danger">*</span></label>
                                    <select class="form-select select2-pegawai" id="select_ptk" name="ptk_selector" required>
                                        <option value="">-- Ketik Nama atau NIP Anda di sini --</option>
                                        <?php foreach ($pegawai_list as $p): ?>
                                            <option value="<?= $p->nip ?>" data-id="<?= $p->id ?>" data-nama="<?= htmlspecialchars($p->nama_lengkap) ?>">
                                                <?= htmlspecialchars($p->nama_lengkap) ?> - NIP. <?= $p->nip ?: '(Non-NIP)' ?> (<?= $p->status_kepegawaian ?: 'ASN' ?>)
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                    <div class="form-text mt-1 text-muted">
                                        <i class="fa fa-info-circle me-1 text-primary"></i>Pilih nama atau ketik NIP Anda. Sistem akan otomatis mengisi jabatan, masa kerja, dan saldo cuti Anda.
                                    </div>
                                </div>
                            </div>

                            <input type="hidden" name="ptk_id" id="val_ptk_id">

                            <!-- DETAIL OTOMATIS TAMPIL KETIKA NIP DIPILIH -->
                            <div id="detail_pegawai_box" class="mt-4 pt-3 border-top" style="display: none;">
                                <div class="row g-3">
                                    <div class="col-md-4">
                                        <label class="form-label text-muted small fw-bold">Nama Lengkap</label>
                                        <input type="text" class="form-control bg-light fw-bold" id="res_nama" readonly>
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label text-muted small fw-bold">NIP</label>
                                        <input type="text" class="form-control bg-light" id="res_nip" readonly>
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label text-muted small fw-bold">Status Kepegawaian</label>
                                        <input type="text" class="form-control bg-light" id="res_status" readonly>
                                    </div>
                                    <div class="col-md-5">
                                        <label class="form-label text-muted small fw-bold">Jabatan</label>
                                        <input type="text" class="form-control bg-light" id="res_jabatan" readonly>
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-label text-muted small fw-bold">Pangkat / Golongan</label>
                                        <input type="text" class="form-control bg-light" id="res_pangkat" readonly>
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label text-muted small fw-bold">Masa Kerja (TMT NIP)</label>
                                        <input type="text" class="form-control bg-light fw-bold text-success" id="res_masakerja" readonly>
                                    </div>
                                </div>

                                <!-- KOTAK REKAPITULASI SALDO CUTI BKN -->
                                <div class="mt-4 p-3 rounded-3" style="background: #f8fafc; border: 1px solid #cbd5e1;">
                                    <div class="d-flex justify-content-between align-items-center mb-3">
                                        <div class="fw-bold text-dark">
                                            <i class="fa fa-calculator text-success me-2"></i>Catatan Saldo Cuti Tahunan (Perka BKN No. 24/2017)
                                        </div>
                                        <span class="badge bg-success" id="res_total_badge">Total Hak Cuti: 0 Hari</span>
                                    </div>
                                    <div class="row g-2">
                                        <div class="col-6 col-md-3">
                                            <div class="saldo-box">
                                                <div class="saldo-label">Tahun N-2 (<span id="lbl_thn_n2"><?= date('Y')-2 ?></span>)</div>
                                                <div class="saldo-num" id="res_sisa_n2">0</div>
                                                <div class="small text-muted">Sisa (Maks 6 Hari)</div>
                                            </div>
                                        </div>
                                        <div class="col-6 col-md-3">
                                            <div class="saldo-box">
                                                <div class="saldo-label">Tahun N-1 (<span id="lbl_thn_n1"><?= date('Y')-1 ?></span>)</div>
                                                <div class="saldo-num" id="res_sisa_n1">0</div>
                                                <div class="small text-muted">Sisa (Maks 6 Hari)</div>
                                            </div>
                                        </div>
                                        <div class="col-6 col-md-3">
                                            <div class="saldo-box">
                                                <div class="saldo-label">Tahun N (<span id="lbl_thn_n"><?= date('Y') ?></span>)</div>
                                                <div class="saldo-num" id="res_sisa_n">12</div>
                                                <div class="small text-muted">Jatah Tahun Berjalan</div>
                                            </div>
                                        </div>
                                        <div class="col-6 col-md-3">
                                            <div class="saldo-box bg-success-subtle border-success">
                                                <div class="saldo-label text-success">Total Tersedia</div>
                                                <div class="saldo-num text-success" id="res_sisa_total">12</div>
                                                <div class="small text-success fw-semibold">Dapat Diambil (Hari Kerja)</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>

                    <!-- LANGKAH 2: PILIHAN JENIS CUTI BERDASARKAN KETENTUAN PEMERINTAH -->
                    <div class="card card-custom">
                        <div class="card-header-green">
                            <span><i class="fa fa-list-check me-2 text-success"></i>2. Jenis Cuti Yang Diambil</span>
                            <span class="regulasi-badge">Sesuai Peraturan Pemerintah & BKN</span>
                        </div>
                        <div class="card-body p-4">
                            <div class="row g-3">
                                <!-- Cuti Tahunan -->
                                <div class="col-md-4 col-sm-6">
                                    <div class="jenis-cuti-card active" onclick="pilihJenisCuti('Cuti Tahunan', this)">
                                        <div class="d-flex justify-content-between align-items-center mb-1">
                                            <h6 class="fw-bold mb-0 text-success">Cuti Tahunan</h6>
                                            <i class="fa fa-circle-check text-success check-icon"></i>
                                        </div>
                                        <small class="text-muted d-block">Hak 12 hari kerja per tahun. Akumulasi N-1 & N-2 maks 6 hari.</small>
                                    </div>
                                </div>
                                <!-- Cuti Besar -->
                                <div class="col-md-4 col-sm-6">
                                    <div class="jenis-cuti-card" onclick="pilihJenisCuti('Cuti Besar', this)">
                                        <div class="d-flex justify-content-between align-items-center mb-1">
                                            <h6 class="fw-bold mb-0">Cuti Besar</h6>
                                            <i class="fa fa-circle text-muted check-icon"></i>
                                        </div>
                                        <small class="text-muted d-block">Masa kerja &ge; 5 tahun terus-menerus. Paling lama 3 bulan.</small>
                                    </div>
                                </div>
                                <!-- Cuti Sakit -->
                                <div class="col-md-4 col-sm-6">
                                    <div class="jenis-cuti-card" onclick="pilihJenisCuti('Cuti Sakit', this)">
                                        <div class="d-flex justify-content-between align-items-center mb-1">
                                            <h6 class="fw-bold mb-0">Cuti Sakit</h6>
                                            <i class="fa fa-circle text-muted check-icon"></i>
                                        </div>
                                        <small class="text-muted d-block">Wajib melampirkan surat keterangan dokter / rumah sakit.</small>
                                    </div>
                                </div>
                                <!-- Cuti Melahirkan -->
                                <div class="col-md-4 col-sm-6">
                                    <div class="jenis-cuti-card" onclick="pilihJenisCuti('Cuti Melahirkan', this)">
                                        <div class="d-flex justify-content-between align-items-center mb-1">
                                            <h6 class="fw-bold mb-0">Cuti Melahirkan</h6>
                                            <i class="fa fa-circle text-muted check-icon"></i>
                                        </div>
                                        <small class="text-muted d-block">Kelahiran anak ke-1 s.d ke-3. Lamanya 3 bulan penuh.</small>
                                    </div>
                                </div>
                                <!-- Cuti Karena Alasan Penting -->
                                <div class="col-md-4 col-sm-6">
                                    <div class="jenis-cuti-card" onclick="pilihJenisCuti('Cuti Alasan Penting', this)">
                                        <div class="d-flex justify-content-between align-items-center mb-1">
                                            <h6 class="fw-bold mb-0">Cuti Alasan Penting</h6>
                                            <i class="fa fa-circle text-muted check-icon"></i>
                                        </div>
                                        <small class="text-muted d-block">Keluarga sakit/wafat, nikah, bencana, istri operasi sesar (maks 1 bulan).</small>
                                    </div>
                                </div>
                                <!-- Cuti di Luar Tanggungan Negara -->
                                <div class="col-md-4 col-sm-6">
                                    <div class="jenis-cuti-card" onclick="pilihJenisCuti('Cuti diluar Tanggungan Negara', this)">
                                        <div class="d-flex justify-content-between align-items-center mb-1">
                                            <h6 class="fw-bold mb-0">Cuti di Luar Tanggungan Negara</h6>
                                            <i class="fa fa-circle text-muted check-icon"></i>
                                        </div>
                                        <small class="text-muted d-block">Masa kerja &ge; 5 tahun untuk alasan mendesak, s.d 3 tahun.</small>
                                    </div>
                                </div>
                            </div>

                            <input type="hidden" name="jenis_cuti" id="input_jenis_cuti" value="Cuti Tahunan">

                            <!-- SUB-PILIHAN SUMBER KUOTA CUTI TAHUNAN -->
                            <div id="sub_opsi_cuti_tahunan" class="mt-3 p-3 bg-light rounded-3 border">
                                <label class="form-label fw-bold small text-dark mb-2">
                                    <i class="fa fa-sliders me-1 text-success"></i>Pilih Sumber Kuota Cuti Tahunan yang Digunakan:
                                </label>
                                <div class="row g-2">
                                    <div class="col-md-4">
                                        <div class="form-check p-2 bg-white rounded border h-100">
                                            <input class="form-check-input ms-1" type="radio" name="alokasi_cuti_tahunan" id="opt_thn_n" value="Cuti Tahunan" checked onchange="ubahAlokasiCuti(this.value)">
                                            <label class="form-check-label ms-2 small fw-semibold" for="opt_thn_n">
                                                Tahun Berjalan (N / <?= date('Y') ?>)
                                                <div class="text-success fw-bold">Sisa: <span id="lbl_opt_sisa_n">12</span> Hari</div>
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-check p-2 bg-white rounded border h-100" id="box_opt_n1">
                                            <input class="form-check-input ms-1" type="radio" name="alokasi_cuti_tahunan" id="opt_thn_n1" value="Cuti Tahunan N-1" onchange="ubahAlokasiCuti(this.value)">
                                            <label class="form-check-label ms-2 small fw-semibold" for="opt_thn_n1">
                                                Sisa Tahun Lalu (N-1 / <?= date('Y')-1 ?>)
                                                <div class="text-primary fw-bold">Sisa: <span id="lbl_opt_sisa_n1">0</span> Hari</div>
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-check p-2 bg-white rounded border h-100" id="box_opt_n2">
                                            <input class="form-check-input ms-1" type="radio" name="alokasi_cuti_tahunan" id="opt_thn_n2" value="Cuti Tahunan N-2" onchange="ubahAlokasiCuti(this.value)">
                                            <label class="form-check-label ms-2 small fw-semibold" for="opt_thn_n2">
                                                Sisa 2 Thn Lalu (N-2 / <?= date('Y')-2 ?>)
                                                <div class="text-muted fw-bold">Sisa: <span id="lbl_opt_sisa_n2">0</span> Hari</div>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- PENJELASAN KETENTUAN REGULASI DINAMIS -->
                            <div class="info-callout mt-4" id="box_info_regulasi">
                                <h6 class="fw-bold text-success mb-1" id="regulasi_title"><i class="fa fa-book-open me-2"></i>Ketentuan Cuti Tahunan:</h6>
                                <p class="mb-0 small text-secondary" id="regulasi_desc">
                                    Sesuai Peraturan BKN No. 24 Tahun 2017, PNS/PPPK yang telah bekerja paling kurang 1 tahun berhak atas cuti tahunan 12 hari kerja. Pengambilan cuti tahunan dihitung berdasarkan <strong>hari kerja efektif</strong> (tidak termasuk hari libur nasional dan akhir pekan).
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- LANGKAH 3: ALASAN, TANGGAL & DURASI CUTI -->
                    <div class="card card-custom">
                        <div class="card-header-green">
                            <span><i class="fa fa-clock me-2 text-success"></i>3. Waktu, Alasan, dan Durasi Cuti</span>
                            <span class="badge bg-secondary-subtle text-secondary">Perhitungan Otomatis Hari Kerja</span>
                        </div>
                        <div class="card-body p-4">
                            <div class="row g-3">
                                <div class="col-md-12">
                                    <label class="form-label fw-bold">Alasan Menjalankan Cuti <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" name="alasan_cuti" id="input_alasan" placeholder="Contoh: Acara Keluarga / Pernikahan / Menemani Orang Tua Sakit / Melahirkan" required>
                                    <div class="d-flex gap-2 mt-2 flex-wrap">
                                        <button type="button" class="btn btn-sm btn-outline-secondary" onclick="isiAlasan('Acara Keluarga')">Acara Keluarga</button>
                                        <button type="button" class="btn btn-sm btn-outline-secondary" onclick="isiAlasan('Pernikahan Keluarga')">Pernikahan Keluarga</button>
                                        <button type="button" class="btn btn-sm btn-outline-secondary" onclick="isiAlasan('Mengantar anak ke pondok pesantren')">Antar Anak ke Pondok</button>
                                        <button type="button" class="btn btn-sm btn-outline-secondary" onclick="isiAlasan('Orangtua (Mertua) Sakit')">Orangtua Sakit</button>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <label class="form-label fw-bold">Tanggal Mulai Cuti <span class="text-danger">*</span></label>
                                    <input type="date" class="form-control" name="tanggal_mulai" id="tgl_mulai" required value="<?= date('Y-m-d') ?>">
                                </div>

                                <div class="col-md-4">
                                    <label class="form-label fw-bold">Tanggal Selesai Cuti <span class="text-danger">*</span></label>
                                    <input type="date" class="form-control" name="tanggal_selesai" id="tgl_selesai" required value="<?= date('Y-m-d') ?>">
                                </div>

                                <div class="col-md-4">
                                    <label class="form-label fw-bold">Lama Cuti (Dihitung Sistem)</label>
                                    <div class="input-group">
                                        <input type="number" class="form-control fw-bold text-success bg-light text-center" name="lama_cuti_jumlah" id="lama_cuti_jumlah" value="1" min="1" required>
                                        <select class="form-select bg-light" name="lama_cuti_satuan" id="lama_cuti_satuan" style="max-width: 110px;">
                                            <option value="Hari" selected>Hari</option>
                                            <option value="Bulan">Bulan</option>
                                            <option value="Tahun">Tahun</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="col-12" id="info_hitung_hari" style="display: none;">
                                    <div class="alert alert-info py-2 px-3 small mb-0">
                                        <i class="fa fa-info-circle me-1"></i><span id="txt_hitung_hari"></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- LANGKAH 4: ALAMAT & KONTAK SELAMA CUTI -->
                    <div class="card card-custom">
                        <div class="card-header-green">
                            <span><i class="fa fa-map-location-dot me-2 text-success"></i>4. Alamat & Kontak Selama Cuti</span>
                            <span class="badge bg-light text-dark">Data Komunikasi</span>
                        </div>
                        <div class="card-body p-4">
                            <div class="row g-3">
                                <div class="col-md-7">
                                    <label class="form-label fw-bold">Alamat Lengkap Selama Cuti <span class="text-danger">*</span></label>
                                    <textarea class="form-control" name="alamat_cuti" id="input_alamat" rows="2" placeholder="Tuliskan alamat/kota tempat Anda menjalankan cuti" required></textarea>
                                </div>
                                <div class="col-md-5">
                                    <label class="form-label fw-bold">No. Telepon / WhatsApp <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" name="telepon_cuti" id="input_telepon" placeholder="Contoh: 08123456789" required>
                                    <div class="form-text">Nomor aktif yang dapat dihubungi saat cuti.</div>
                                </div>
                                <div class="col-md-12">
                                    <label class="form-label fw-bold">Unggah Dokumen Bukti Pendukung (Opsional / Wajib untuk Cuti Sakit/Besar/Alasan Penting)</label>
                                    <input type="file" class="form-control" name="file_lampiran" id="file_lampiran" accept=".pdf,.doc,.docx,.jpg,.jpeg,.png">
                                    <div class="form-text">Format: PDF, Word, atau Gambar (Maksimal 5MB). Lampirkan Surat Dokter, Surat Keterangan Kematian/RS, dll. jika diperlukan.</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- SUBMIT BUTTON -->
                    <div class="text-center my-4 pb-4">
                        <button type="submit" class="btn btn-success btn-submit text-white shadow-lg px-5">
                            <i class="fa fa-paper-plane me-2"></i>Kirim Pengajuan Cuti Mandiri
                        </button>
                        <div class="small text-muted mt-2">
                            <i class="fa fa-circle-info text-primary me-1"></i>Permohonan berstatus <strong>Menunggu Persetujuan</strong> dan akan diverifikasi oleh Kepala Madrasah / Admin TU terlebih dahulu.
                        </div>
                    </div>

                </form>

            </div>

            <!-- TAB 2: HASIL & MONITORING STATUS PENGAJUAN CUTI (LANGSUNG TAMPIL TANPA NIP) -->
            <div class="tab-pane fade" id="tab-status" role="tabpanel">
                <div class="card card-custom">
                    <div class="card-header-green">
                        <span><i class="fa fa-clipboard-list me-2 text-success"></i>Daftar Pengajuan & Status Cuti Pegawai ASN</span>
                        <span class="badge bg-success-subtle text-success">Monitoring Terbuka Tanpa Login</span>
                    </div>
                    <div class="card-body p-4">

                        <!-- INFO KETENTUAN MONITORING & CETAK -->
                        <div class="alert alert-light border d-flex align-items-center mb-4 py-2 px-3 small">
                            <i class="fa fa-shield-halved fa-2x text-warning me-3"></i>
                            <div>
                                <strong>Informasi Monitoring & Cetak:</strong> Tabel ini menampilkan status seluruh pengajuan cuti pegawai secara transparan. 
                                Sesuai ketentuan, <strong>pencetakan formulir resmi BKN hanya dapat dilakukan oleh Admin Tata Usaha atau melalui Akun Pegawai masing-masing</strong> setelah permohonan disetujui oleh Kepala Madrasah.
                            </div>
                        </div>

                        <!-- LIVE FILTER & SEARCH -->
                        <div class="row g-2 mb-3 align-items-center">
                            <div class="col-md-7">
                                <div class="input-group">
                                    <span class="input-group-text bg-white"><i class="fa fa-search text-muted"></i></span>
                                    <input type="text" class="form-control" id="search_monitoring" placeholder="Ketik Nama Pegawai atau NIP untuk menyaring data..." onkeyup="filterTabelMonitoring()">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <select class="form-select" id="filter_status_select" onchange="filterTabelMonitoring()">
                                    <option value="">-- Semua Status Permohonan --</option>
                                    <option value="Draft">Menunggu Verifikasi TU</option>
                                    <option value="Disetujui Kepala">Disetujui Kepala Madrasah</option>
                                    <option value="Aktif">Disetujui Penuh (Aktif)</option>
                                    <option value="Ditolak">Ditolak</option>
                                </select>
                            </div>
                            <div class="col-md-2 text-md-end text-muted small">
                                Total: <strong id="count_monitoring"><?= count($riwayat_cuti) ?></strong> Data
                            </div>
                        </div>

                        <!-- TABEL MONITORING PENGAJUAN (LANGSUNG TAMPIL) -->
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover align-middle mb-0" id="table_monitoring">
                                <thead class="table-light">
                                    <tr class="text-nowrap" style="font-size: 0.85rem;">
                                        <th class="text-center" width="4%">No</th>
                                        <th width="10%">Tanggal</th>
                                        <th width="24%">Nama Pegawai & NIP</th>
                                        <th width="14%">Jenis Cuti</th>
                                        <th width="8%" class="text-center">Durasi</th>
                                        <th width="16%">Rentang Pelaksanaan</th>
                                        <th width="12%">Alasan</th>
                                        <th width="12%" class="text-center">Status</th>
                                        <th width="10%" class="text-center">Hak Cetak</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (empty($riwayat_cuti)): ?>
                                        <tr>
                                            <td colspan="9" class="text-center text-muted py-4">Belum ada data pengajuan cuti yang tercatat.</td>
                                        </tr>
                                    <?php else: ?>
                                        <?php $no = 1; foreach ($riwayat_cuti as $r): ?>
                                            <?php
                                            $tgl_surat = !empty($r->tanggal_surat) && $r->tanggal_surat != '0000-00-00' ? date('d/m/Y', strtotime($r->tanggal_surat)) : '-';
                                            $tgl_mulai = !empty($r->tanggal_mulai) && $r->tanggal_mulai != '0000-00-00' ? date('d M Y', strtotime($r->tanggal_mulai)) : '-';
                                            $tgl_selesai = !empty($r->tanggal_selesai) && $r->tanggal_selesai != '0000-00-00' ? date('d M Y', strtotime($r->tanggal_selesai)) : '-';
                                            
                                            $badge_class = 'bg-secondary';
                                            $status_label = $r->status;
                                            if ($r->status == 'Draft') {
                                                $badge_class = 'bg-warning text-dark';
                                                $status_label = '<i class="fa fa-clock me-1"></i>Menunggu TU';
                                            } elseif ($r->status == 'Disetujui Kepala') {
                                                $badge_class = 'bg-info text-dark';
                                                $status_label = '<i class="fa fa-check-circle me-1"></i>Disetujui Kamad';
                                            } elseif ($r->status == 'Aktif') {
                                                $badge_class = 'bg-success';
                                                $status_label = '<i class="fa fa-check-double me-1"></i>Disetujui (Aktif)';
                                            } elseif ($r->status == 'Ditolak') {
                                                $badge_class = 'bg-danger';
                                                $status_label = '<i class="fa fa-times-circle me-1"></i>Ditolak';
                                            }
                                            ?>
                                            <tr class="row-monitoring" data-nama="<?= strtolower(htmlspecialchars($r->nama_lengkap ?? '')) ?>" data-nip="<?= htmlspecialchars($r->nip ?? '') ?>" data-status="<?= htmlspecialchars($r->status) ?>">
                                                <td class="text-center small text-muted"><?= $no++ ?></td>
                                                <td class="small text-muted"><?= $tgl_surat ?></td>
                                                <td>
                                                    <div class="fw-bold text-dark" style="font-size: 0.9rem;"><?= htmlspecialchars($r->nama_lengkap ?? '-') ?></div>
                                                    <div class="text-muted small">NIP. <?= htmlspecialchars($r->nip ?? '-') ?></div>
                                                </td>
                                                <td>
                                                    <span class="badge bg-success-subtle text-success fw-semibold" style="font-size: 0.78rem;">
                                                        <?= htmlspecialchars($r->jenis_cuti) ?>
                                                    </span>
                                                </td>
                                                <td class="text-center fw-bold" style="font-size: 0.88rem;">
                                                    <?= $r->lama_cuti_jumlah ?> <?= $r->lama_cuti_satuan ?>
                                                </td>
                                                <td class="small">
                                                    <div><?= $tgl_mulai ?></div>
                                                    <div class="text-muted">s.d <?= $tgl_selesai ?></div>
                                                </td>
                                                <td class="small text-muted" style="max-width: 160px;">
                                                    <?= htmlspecialchars($r->alasan_cuti ?: '-') ?>
                                                </td>
                                                <td class="text-center">
                                                    <span class="badge <?= $badge_class ?>" style="font-size: 0.75rem;"><?= $status_label ?></span>
                                                </td>
                                                <td class="text-center">
                                                    <span class="badge bg-light text-muted border py-1 px-2" title="Pencetakan formulir resmi BKN hanya dapat dilakukan oleh Admin Tata Usaha atau melalui Akun Pegawai masing-masing setelah disetujui." style="cursor: help; font-size: 0.72rem;">
                                                        <i class="fa fa-lock me-1 text-secondary"></i>Via Akun/Admin
                                                    </span>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>

                        <!-- PANEL OPSIONAL: CEK RINCIAN SALDO CUTI PRIBADI -->
                        <div class="mt-4 pt-3 border-top">
                            <button class="btn btn-sm btn-outline-secondary" type="button" data-bs-toggle="collapse" data-bs-target="#boxCekSaldoPribadi">
                                <i class="fa fa-id-card me-1"></i>Ingin Cek Rincian Sisa Jatah Cuti Anda? Klik di Sini
                            </button>
                            <div class="collapse mt-3" id="boxCekSaldoPribadi">
                                <div class="p-3 bg-light rounded-3 border">
                                    <div class="row g-2 align-items-center">
                                        <div class="col-md-6">
                                            <label class="form-label small fw-bold mb-1">Masukkan NIP Anda</label>
                                            <div class="input-group input-group-sm">
                                                <input type="text" class="form-control" id="cari_nip_status" placeholder="Masukkan 18 digit NIP Anda (Contoh: 198311282011012012)">
                                                <button class="btn btn-success px-3" type="button" onclick="cekStatusCuti()">
                                                    <i class="fa fa-search me-1"></i> Cek Saldo
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                    <div id="hasil_status_box" class="mt-3 pt-3 border-top" style="display: none;">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div>
                                                <strong class="text-success" id="status_nama_pegawai"></strong>
                                                <div class="text-muted small" id="status_nip_pegawai"></div>
                                            </div>
                                            <span class="badge bg-success" id="status_sisa_cuti"></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>

            <!-- TAB 3: PANDUAN REGULASI PEMERINTAH (BKN) -->
            <div class="tab-pane fade" id="tab-regulasi" role="tabpanel">
                <div class="card card-custom">
                    <div class="card-header-green">
                        <span><i class="fa fa-book-bookmark me-2 text-success"></i>Regulasi & Ketentuan Pemberian Cuti ASN</span>
                        <span class="regulasi-badge">Perka BKN No. 24/2017 & No. 7/2021</span>
                    </div>
                    <div class="card-body p-4">
                        <div class="accordion" id="accordionRegulasi">

                            <!-- 1. Cuti Tahunan -->
                            <div class="accordion-item mb-2 border rounded-2">
                                <h2 class="accordion-header">
                                    <button class="accordion-button fw-bold text-success" type="button" data-bs-toggle="collapse" data-bs-target="#reg1">
                                        1. Cuti Tahunan (12 Hari Kerja)
                                    </button>
                                </h2>
                                <div id="reg1" class="accordion-collapse collapse show">
                                    <div class="accordion-body small text-secondary">
                                        <ul>
                                            <li>Diberikan kepada PNS/PPPK yang telah bekerja sekurang-kurangnya 1 (satu) tahun secara terus-menerus.</li>
                                            <li>Lamanya hak cuti tahunan adalah <strong>12 (dua belas) hari kerja</strong>.</li>
                                            <li>Hak cuti tahunan yang tidak digunakan dalam tahun bersangkutan dapat digunakan dalam tahun berikutnya untuk paling lama <strong>6 (enam) hari kerja</strong> (N-1 maks 6 hari kerja).</li>
                                            <li>Hak cuti tahunan yang tidak digunakan 2 (dua) tahun berturut-turut dapat digunakan dalam tahun berikutnya untuk paling lama <strong>6 (enam) hari kerja</strong> (N-2 maks 6 hari kerja).</li>
                                            <li>Pengambilan cuti tahunan dihitung hanya pada <strong>hari kerja aktif</strong> (Sabtu, Minggu, dan Hari Libur Nasional tidak dihitung mengurangi kuota).</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>

                            <!-- 2. Cuti Besar -->
                            <div class="accordion-item mb-2 border rounded-2">
                                <h2 class="accordion-header">
                                    <button class="accordion-button collapsed fw-bold" type="button" data-bs-toggle="collapse" data-bs-target="#reg2">
                                        2. Cuti Besar (Paling Lama 3 Bulan)
                                    </button>
                                </h2>
                                <div id="reg2" class="accordion-collapse collapse">
                                    <div class="accordion-body small text-secondary">
                                        <ul>
                                            <li>Diberikan kepada PNS yang telah bekerja sekurang-kurangnya <strong>5 (lima) tahun terus-menerus</strong>.</li>
                                            <li>Lamanya cuti besar adalah paling lama <strong>3 (tiga) bulan</strong>.</li>
                                            <li>PNS yang menggunakan hak cuti besar tidak berhak lagi atas cuti tahunan dalam tahun yang bersangkutan.</li>
                                            <li>Dapat digunakan untuk menunaikan ibadah keagamaan (seperti Haji / Umrah).</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>

                            <!-- 3. Cuti Sakit -->
                            <div class="accordion-item mb-2 border rounded-2">
                                <h2 class="accordion-header">
                                    <button class="accordion-button collapsed fw-bold" type="button" data-bs-toggle="collapse" data-bs-target="#reg3">
                                        3. Cuti Sakit
                                    </button>
                                </h2>
                                <div id="reg3" class="accordion-collapse collapse">
                                    <div class="accordion-body small text-secondary">
                                        <ul>
                                            <li>Sakit 1 s.d 14 hari: Wajib melampirkan surat keterangan dokter.</li>
                                            <li>Sakit lebih dari 14 hari: Wajib melampirkan surat keterangan dokter rumah sakit pemerintah.</li>
                                            <li>Hak cuti sakit diberikan paling lama 1 (satu) tahun dan dapat diperpanjang paling lama 6 (enam) bulan jika ada rekomendasi tim penguji kesehatan.</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>

                            <!-- 4. Cuti Melahirkan -->
                            <div class="accordion-item mb-2 border rounded-2">
                                <h2 class="accordion-header">
                                    <button class="accordion-button collapsed fw-bold" type="button" data-bs-toggle="collapse" data-bs-target="#reg4">
                                        4. Cuti Melahirkan (3 Bulan)
                                    </button>
                                </h2>
                                <div id="reg4" class="accordion-collapse collapse">
                                    <div class="accordion-body small text-secondary">
                                        <ul>
                                            <li>Diberikan untuk kelahiran anak pertama sampai dengan anak ketiga sejak diangkat sebagai PNS/PPPK.</li>
                                            <li>Lamanya cuti melahirkan adalah <strong>3 (tiga) bulan</strong>.</li>
                                            <li>Untuk kelahiran anak keempat dan seterusnya, diberikan hak Cuti Besar.</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>

                            <!-- 5. Cuti Karena Alasan Penting -->
                            <div class="accordion-item mb-2 border rounded-2">
                                <h2 class="accordion-header">
                                    <button class="accordion-button collapsed fw-bold" type="button" data-bs-toggle="collapse" data-bs-target="#reg5">
                                        5. Cuti Karena Alasan Penting (CAP)
                                    </button>
                                </h2>
                                <div id="reg5" class="accordion-collapse collapse">
                                    <div class="accordion-body small text-secondary">
                                        <ul>
                                            <li>Ibu, bapak, isteri/suami, anak, adik, kakak, mertua, atau menantu sakit keras atau meninggal dunia.</li>
                                            <li>Melangsungkan perkawinan (pernikahan pertama pegawai).</li>
                                            <li>Mengalami musibah bencana alam.</li>
                                            <li>PNS laki-laki yang isterinya melahirkan secara operasi sesar / mengalami penyulit (maksimal 1 bulan).</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>

                            <!-- 6. CLTN -->
                            <div class="accordion-item mb-2 border rounded-2">
                                <h2 class="accordion-header">
                                    <button class="accordion-button collapsed fw-bold" type="button" data-bs-toggle="collapse" data-bs-target="#reg6">
                                        6. Cuti di Luar Tanggungan Negara (CLTN)
                                    </button>
                                </h2>
                                <div id="reg6" class="accordion-collapse collapse">
                                    <div class="accordion-body small text-secondary">
                                        <ul>
                                            <li>Telah bekerja sekurang-kurangnya 5 (lima) tahun terus-menerus karena alasan pribadi dan mendesak.</li>
                                            <li>Diberikan paling lama 3 (tiga) tahun dan dapat diperpanjang paling lama 1 (satu) tahun.</li>
                                            <li>Selama menjalankan CLTN, PNS tidak berhak menerima penghasilan dari negara dan masa kerja tidak dihitung.</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>

        </div>

    </div>

    <!-- FOOTER -->
    <footer class="text-center py-4 text-muted small border-top bg-white">
        &copy; <?= date('Y') ?> Urusan Tata Usaha - MAN 3 Banjar &bull; Sistem Presensi & Kepegawaian Terpadu LabSys
    </footer>

    <!-- SCRIPTS -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <script>
        const regulasiMap = {
            'Cuti Tahunan': {
                title: '<i class="fa fa-book-open me-2"></i>Ketentuan Cuti Tahunan:',
                desc: 'Hak 12 hari kerja per tahun bagi pegawai yang telah bekerja &ge; 1 tahun. Akumulasi sisa tahun sebelumnya maksimal 6 hari kerja (N-1 dan N-2). Perhitungan hanya menghitung <strong>hari kerja efektif</strong> (akhir pekan & hari libur nasional tidak memotong cuti).'
            },
            'Cuti Besar': {
                title: '<i class="fa fa-certificate me-2"></i>Ketentuan Cuti Besar:',
                desc: 'Diberikan kepada PNS yang telah bekerja sekurang-kurangnya <strong>5 tahun terus-menerus</strong>. Durasi maksimal <strong>3 bulan</strong>. Menggunakan cuti besar meniadakan hak cuti tahunan pada tahun berjalan.'
            },
            'Cuti Sakit': {
                title: '<i class="fa fa-hospital me-2"></i>Ketentuan Cuti Sakit:',
                desc: 'Sakit 1 s.d 14 hari wajib melampirkan surat keterangan dokter. Sakit &gt; 14 hari wajib surat dokter tim penguji kesehatan pemerintah. <strong>Silakan unggah surat dokter pada kolom berkas lampiran</strong>.'
            },
            'Cuti Melahirkan': {
                title: '<i class="fa fa-baby me-2"></i>Ketentuan Cuti Melahirkan:',
                desc: 'Diberikan untuk kelahiran anak ke-1 sampai ke-3. Durasi adalah <strong>3 bulan penuh</strong>. Lampirkan surat keterangan perkiraan lahir / surat keterangan dokter/bidan.'
            },
            'Cuti Alasan Penting': {
                title: '<i class="fa fa-triangle-exclamation me-2"></i>Ketentuan Cuti Alasan Penting (CAP):',
                desc: 'Diberikan apabila anggota keluarga inti sakit keras/meninggal dunia, melangsungkan perkawinan, mengalami musibah, atau mendampingi istri bersalin operasi sesar (maksimal 1 bulan).'
            },
            'Cuti diluar Tanggungan Negara': {
                title: '<i class="fa fa-file-contract me-2"></i>Ketentuan Cuti di Luar Tanggungan Negara (CLTN):',
                desc: 'Diberikan untuk alasan mendesak setelah bekerja &ge; 5 tahun terus-menerus. Paling lama 3 tahun. Selama cuti, gaji dihentikan dan masa kerja tidak diperhitungkan.'
            }
        };

        $(document).ready(function() {
            // Inisialisasi Select2
            $('.select2-pegawai').select2({
                theme: 'bootstrap-5',
                placeholder: '-- Ketik Nama atau NIP Anda di sini --',
                allowClear: true
            });

            // Event saat pegawai dipilih
            $('#select_ptk').on('change', function() {
                const nip = $(this).val();
                if (!nip) {
                    $('#detail_pegawai_box').slideUp();
                    $('#val_ptk_id').val('');
                    return;
                }

                $.ajax({
                    url: '<?= site_url("cuti/ajax_get_ptk") ?>',
                    type: 'POST',
                    data: { nip: nip },
                    dataType: 'json',
                    beforeSend: function() {
                        $('#detail_pegawai_box').slideUp();
                    },
                    success: function(res) {
                        if (res.status === 'success') {
                            const d = res.data;
                            $('#val_ptk_id').val(d.id);
                            $('#res_nama').val(d.nama_lengkap);
                            $('#res_nip').val(d.nip);
                            $('#res_status').val(d.status_kepegawaian);
                            $('#res_jabatan').val(d.jabatan);
                            $('#res_pangkat').val(d.pangkat_golongan);
                            $('#res_masakerja').val(d.masa_kerja);
                            
                            if (d.alamat && !$('#input_alamat').val()) {
                                $('#input_alamat').val(d.alamat);
                            }
                            if (d.no_hp && !$('#input_telepon').val()) {
                                $('#input_telepon').val(d.no_hp);
                            }

                            // Update Saldo Box
                            $('#lbl_thn_n2').text(d.saldo.tahun_n2);
                            $('#lbl_thn_n1').text(d.saldo.tahun_n1);
                            $('#lbl_thn_n').text(d.saldo.tahun_n);

                            $('#res_sisa_n2').text(d.saldo.sisa_n2);
                            $('#res_sisa_n1').text(d.saldo.sisa_n1);
                            $('#res_sisa_n').text(d.saldo.sisa_n);
                            $('#res_sisa_total').text(d.saldo.total_hak);
                            $('#res_total_badge').text('Total Hak Cuti: ' + d.saldo.total_hak + ' Hari');

                            // Update Opsi Sumber Kuota Cuti Tahunan
                            $('#lbl_opt_sisa_n').text(d.saldo.sisa_n);
                            $('#lbl_opt_sisa_n1').text(d.saldo.sisa_n1);
                            $('#lbl_opt_sisa_n2').text(d.saldo.sisa_n2);

                            if (d.saldo.sisa_n1 <= 0) {
                                $('#opt_thn_n1').prop('disabled', true);
                                $('#box_opt_n1').addClass('opacity-50');
                            } else {
                                $('#opt_thn_n1').prop('disabled', false);
                                $('#box_opt_n1').removeClass('opacity-50');
                            }

                            if (d.saldo.sisa_n2 <= 0) {
                                $('#opt_thn_n2').prop('disabled', true);
                                $('#box_opt_n2').addClass('opacity-50');
                            } else {
                                $('#opt_thn_n2').prop('disabled', false);
                                $('#box_opt_n2').removeClass('opacity-50');
                            }

                            $('#detail_pegawai_box').slideDown();
                            hitungHariKerja();
                        } else {
                            alert(res.message);
                        }
                    },
                    error: function() {
                        alert('Gagal mengambil data pegawai. Periksa koneksi jaringan.');
                    }
                });
            });

            // Event tanggal berubah
            $('#tgl_mulai, #tgl_selesai').on('change', function() {
                hitungHariKerja();
            });
        });

        function pilihJenisCuti(jenis, el) {
            $('.jenis-cuti-card').removeClass('active');
            $('.jenis-cuti-card').find('.check-icon').removeClass('fa-circle-check text-success').addClass('fa-circle text-muted');

            $(el).addClass('active');
            $(el).find('.check-icon').removeClass('fa-circle text-muted').addClass('fa-circle-check text-success');

            // Ganti deskripsi regulasi
            if (regulasiMap[jenis]) {
                $('#regulasi_title').html(regulasiMap[jenis].title);
                $('#regulasi_desc').html(regulasiMap[jenis].desc);
            }

            if (jenis === 'Cuti Tahunan') {
                $('#sub_opsi_cuti_tahunan').slideDown();
                const alokasi = $('input[name="alokasi_cuti_tahunan"]:checked').val() || 'Cuti Tahunan';
                $('#input_jenis_cuti').val(alokasi);
                $('#lama_cuti_satuan').val('Hari');
                hitungHariKerja();
            } else {
                $('#sub_opsi_cuti_tahunan').slideUp();
                $('#input_jenis_cuti').val(jenis);

                // Sesuaikan satuan jika Cuti Melahirkan / Cuti Besar
                if (jenis === 'Cuti Melahirkan') {
                    $('#lama_cuti_jumlah').val(3);
                    $('#lama_cuti_satuan').val('Bulan');
                } else {
                    $('#lama_cuti_satuan').val('Hari');
                    hitungHariKerja();
                }
            }
        }

        function ubahAlokasiCuti(val) {
            $('#input_jenis_cuti').val(val);
            hitungHariKerja();
        }

        function syncGoogleSheet(btn) {
            const $btn = $(btn);
            const origHtml = $btn.html();
            $btn.prop('disabled', true).html('<i class="fa fa-spinner fa-spin me-1"></i> Menyinkronkan...');

            $.ajax({
                url: '<?= site_url("cuti/sync_kuota_sheet") ?>',
                type: 'POST',
                dataType: 'json',
                success: function(res) {
                    $btn.prop('disabled', false).html(origHtml);
                    if (res.status === 'success') {
                        alert(res.message);
                        const curNip = $('#select_ptk').val();
                        if (curNip) {
                            $('#select_ptk').trigger('change');
                        }
                    } else {
                        alert(res.message);
                    }
                },
                error: function() {
                    $btn.prop('disabled', false).html(origHtml);
                    alert('Gagal menghubungi server untuk sinkronisasi Google Spreadsheet.');
                }
            });
        }

        function isiAlasan(text) {
            $('#input_alasan').val(text);
        }

        function hitungHariKerja() {
            const start = $('#tgl_mulai').val();
            const end = $('#tgl_selesai').val();
            const jenis = $('#input_jenis_cuti').val();

            if (!start || !end) return;

            $.ajax({
                url: '<?= site_url("cuti/ajax_hitung_hari") ?>',
                type: 'POST',
                data: {
                    tanggal_mulai: start,
                    tanggal_selesai: end,
                    jenis_cuti: jenis
                },
                dataType: 'json',
                success: function(res) {
                    if (res.status === 'success') {
                        if (jenis === 'Cuti Tahunan') {
                            $('#lama_cuti_jumlah').val(res.work_days);
                            $('#lama_cuti_satuan').val('Hari');
                            
                            let info = 'Terhitung: <strong>' + res.work_days + ' Hari Kerja Aktif</strong> (Total kalender: ' + res.calendar_days + ' hari).';
                            if (res.holidays && res.holidays.length > 0) {
                                info += ' Termasuk libur nasional: ' + res.holidays.join(', ');
                            }
                            $('#txt_hitung_hari').html(info);
                            $('#info_hitung_hari').slideDown();
                        } else {
                            $('#lama_cuti_jumlah').val(res.calendar_days);
                            $('#lama_cuti_satuan').val('Hari');
                            $('#txt_hitung_hari').html('Terhitung: <strong>' + res.calendar_days + ' Hari Kalender</strong>.');
                            $('#info_hitung_hari').slideDown();
                        }
                    }
                }
            });
        }

        function filterTabelMonitoring() {
            const keyword = $('#search_monitoring').val().toLowerCase().trim();
            const statusFilter = $('#filter_status_select').val();

            let visibleCount = 0;
            $('.row-monitoring').each(function() {
                const nama = $(this).data('nama') || '';
                const nip = String($(this).data('nip') || '');
                const status = String($(this).data('status') || '');

                const matchKeyword = !keyword || (nama.indexOf(keyword) !== -1 || nip.indexOf(keyword) !== -1);
                const matchStatus = !statusFilter || (status === statusFilter);

                if (matchKeyword && matchStatus) {
                    $(this).show();
                    visibleCount++;
                } else {
                    $(this).hide();
                }
            });

            $('#count_monitoring').text(visibleCount);
        }

        $(document).ready(function() {
            if (window.location.hash === '#tab-status') {
                const triggerEl = document.querySelector('#status-tab');
                if (triggerEl) {
                    const tabInstance = new bootstrap.Tab(triggerEl);
                    tabInstance.show();
                }
            }
        });

        function cekStatusCuti() {
            const nip = $('#cari_nip_status').val().trim();
            if (!nip) {
                alert('Silakan masukkan NIP Anda.');
                return;
            }

            $.ajax({
                url: '<?= site_url("cuti/ajax_cek_status") ?>',
                type: 'POST',
                data: { nip: nip },
                dataType: 'json',
                success: function(res) {
                    if (res.status === 'success') {
                        $('#status_nama_pegawai').text(res.pegawai.nama);
                        $('#status_nip_pegawai').text('NIP. ' + res.pegawai.nip + ' | ' + res.pegawai.jabatan);
                        $('#status_sisa_cuti').text('Sisa Hak Cuti ' + res.saldo.tahun_n + ': ' + res.saldo.total_hak + ' Hari');
                        $('#hasil_status_box').slideDown();

                        // Otomatis saring tabel monitoring utama dengan NIP yang dicari
                        $('#search_monitoring').val(res.pegawai.nip);
                        filterTabelMonitoring();
                    } else {
                        alert(res.message);
                    }
                },
                error: function() {
                    alert('Gagal mengambil data. Pastikan NIP yang dimasukkan benar.');
                }
            });
        }
    </script>
</body>
</html>
