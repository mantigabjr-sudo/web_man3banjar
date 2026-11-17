<?php 
// Load Header Resmi Website Madrasah
$this->load->view('public/partials/archive_header'); 
?>

<!-- Select2 CSS & FontAwesome -->
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<link href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" rel="stylesheet" />
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" rel="stylesheet">

<style>
    /* Styling Selaras Halaman Kontak Website MAN 3 Banjar */
    .contact-info-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 12px 24px rgba(16, 185, 129, 0.1) !important;
    }

    .cuti-card-main {
        border-radius: 24px;
        background: #ffffff;
        border: none;
        box-shadow: 0 6px 24px rgba(15, 23, 42, 0.05);
    }

    .cuti-nav-pills .nav-link {
        font-weight: 600;
        color: #475569;
        border-radius: 14px;
        padding: 12px 22px;
        transition: all 0.25s ease;
        border: 1px solid #e2e8f0;
        background: #ffffff;
    }

    .cuti-nav-pills .nav-link:hover {
        background: #f1f5f9;
        color: #0f172a;
    }

    .cuti-nav-pills .nav-link.active {
        background: #059669 !important;
        color: #ffffff !important;
        border-color: #059669 !important;
        box-shadow: 0 4px 12px rgba(5, 150, 105, 0.3);
    }

    .saldo-box {
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        border-radius: 14px;
        padding: 14px;
        text-align: center;
        transition: all 0.2s ease;
    }

    .saldo-box:hover {
        border-color: #059669;
        transform: translateY(-2px);
    }

    .saldo-num {
        font-size: 1.6rem;
        font-weight: 800;
        color: #059669;
        line-height: 1.2;
    }

    .saldo-label {
        font-size: 0.75rem;
        color: #64748b;
        text-transform: uppercase;
        font-weight: 700;
        letter-spacing: 0.5px;
    }

    .jenis-cuti-card {
        border: 2px solid #e2e8f0;
        border-radius: 16px;
        padding: 14px 16px;
        cursor: pointer;
        transition: all 0.2s ease;
        height: 100%;
        background: #ffffff;
    }

    .jenis-cuti-card:hover {
        border-color: #059669;
        background-color: #f0fdf4;
    }

    .jenis-cuti-card.active {
        border-color: #059669;
        background-color: #ecfdf5;
        box-shadow: 0 0 0 1px #059669;
    }

    .select2-container--bootstrap-5 .select2-selection {
        min-height: 48px;
        font-size: 0.95rem;
        border-radius: 12px;
        border: 1px solid #cbd5e1;
    }

    .table-monitoring th {
        font-size: 12.5px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        font-weight: 700;
    }

    .table-monitoring td {
        font-size: 13.5px;
    }
</style>

<!-- HERO HEADER WEBSITE RESMI (SEPERTI HALAMAN KONTAK) -->
<header class="web-archive-hero">
    <div class="container">
        <div class="detail-breadcrumb">
            <a href="<?= base_url() ?>"><i class="bi bi-house-door"></i> Beranda</a>
            <span>/</span>
            <a href="<?= base_url('website/kontak') ?>">Layanan</a>
            <span>/</span>
            <strong>Cuti ASN</strong>
        </div>
        <h1>Layanan Mandiri Cuti ASN</h1>
        <p>Portal Pengajuan &amp; Monitoring Status Cuti Pegawai ASN (PNS &amp; PPPK) Sesuai Ketentuan Pemerintah &bull; <?= htmlspecialchars($nama_madrasah ?? 'MAN 3 Banjar', ENT_QUOTES, 'UTF-8') ?></p>
    </div>
</header>

<!-- MAIN CONTENT SECTION (GAYA HALAMAN KONTAK) -->
<section class="web-section" style="background: #f8fafc; padding: 50px 0 80px;">
    <div class="container">

        <!-- NOTIFIKASI FLASH DATA -->
        <?php if($this->session->flashdata('success')): ?>
            <div class="alert alert-success alert-dismissible fade show shadow-sm border-0 mb-4" style="border-radius: 16px; background: #ecfdf5; color: #065f46; border-left: 5px solid #10b981 !important;" role="alert">
                <div class="d-flex align-items-center">
                    <i class="bi bi-check-circle-fill fs-4 me-3 text-success"></i>
                    <div>
                        <h6 class="fw-bold mb-0">Berhasil!</h6>
                        <small><?= $this->session->flashdata('success') ?></small>
                    </div>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <?php if($this->session->flashdata('error')): ?>
            <div class="alert alert-danger alert-dismissible fade show shadow-sm border-0 mb-4" style="border-radius: 16px; background: #fef2f2; color: #991b1b; border-left: 5px solid #ef4444 !important;" role="alert">
                <div class="d-flex align-items-center">
                    <i class="bi bi-exclamation-triangle-fill fs-4 me-3 text-danger"></i>
                    <div>
                        <h6 class="fw-bold mb-0">Pemberitahuan</h6>
                        <small><?= $this->session->flashdata('error') ?></small>
                    </div>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <!-- 3 QUICK INFO CARDS (PERSIS GAYA 3 KARTU KONTAK) -->
        <div class="row g-4 mb-5">
            <!-- 1. Standar Regulasi BKN -->
            <div class="col-lg-4 col-md-6">
                <div class="card border-0 h-100 shadow-sm contact-info-card" style="border-radius: 20px; background: #ffffff; transition: transform 0.25s, box-shadow 0.25s;">
                    <div class="card-body p-4 d-flex align-items-start gap-3">
                        <div style="width: 52px; height: 52px; border-radius: 14px; background: #ecfdf5; color: #059669; display: flex; align-items: center; justify-content: center; font-size: 24px; flex-shrink: 0;">
                            <i class="bi bi-book-half"></i>
                        </div>
                        <div>
                            <h6 class="fw-bold mb-1" style="color: #0f172a; font-size: 16px;">Regulasi Resmi BKN</h6>
                            <p class="mb-0 text-muted" style="font-size: 13.5px; line-height: 1.5;">
                                Berpedoman pada Perka BKN No. 24/2017 &amp; No. 7/2021. Hak cuti tahunan 12 hari kerja dengan akumulasi jatah tahun sebelumnya.
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- 2. Mode Pengajuan Bypass Mandiri -->
            <div class="col-lg-4 col-md-6">
                <div class="card border-0 h-100 shadow-sm contact-info-card" style="border-radius: 20px; background: #ffffff; transition: transform 0.25s, box-shadow 0.25s;">
                    <div class="card-body p-4 d-flex align-items-start gap-3">
                        <div style="width: 52px; height: 52px; border-radius: 14px; background: #eff6ff; color: #2563eb; display: flex; align-items: center; justify-content: center; font-size: 24px; flex-shrink: 0;">
                            <i class="bi bi-shield-check"></i>
                        </div>
                        <div>
                            <h6 class="fw-bold mb-1" style="color: #0f172a; font-size: 16px;">Mode Mandiri (Bypass)</h6>
                            <p class="mb-0 text-muted" style="font-size: 13.5px; line-height: 1.5;">
                                Mempermudah ASN mengajukan cuti tanpa login akun. Berstatus <em>Draft</em> dan wajib diverifikasi Kamad &amp; Admin TU.
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- 3. Urusan Kepegawaian TU -->
            <div class="col-lg-4 col-md-12">
                <div class="card border-0 h-100 shadow-sm contact-info-card" style="border-radius: 20px; background: #ffffff; transition: transform 0.25s, box-shadow 0.25s;">
                    <div class="card-body p-4 d-flex align-items-start gap-3">
                        <div style="width: 52px; height: 52px; border-radius: 14px; background: #faf5ff; color: #9333ea; display: flex; align-items: center; justify-content: center; font-size: 24px; flex-shrink: 0;">
                            <i class="bi bi-printer-fill"></i>
                        </div>
                        <div>
                            <h6 class="fw-bold mb-1" style="color: #0f172a; font-size: 16px;">Ketentuan Pencetakan</h6>
                            <p class="mb-0 text-muted" style="font-size: 13.5px; line-height: 1.5;">
                                Demi tertib administrasi, cetak formulir resmi BKN dilakukan oleh <strong>Admin Tata Usaha</strong> atau login akun masing-masing setelah disetujui.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- NAV TABS MODERN (PILLS) -->
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-3 mb-4">
            <ul class="nav nav-pills cuti-nav-pills gap-2" id="cutiPillsTab" role="tablist">
                <li class="nav-item">
                    <button class="nav-link active d-inline-flex align-items-center" id="pills-form-tab" data-bs-toggle="pill" data-bs-target="#pills-form" type="button">
                        <i class="bi bi-pencil-square me-2"></i> Formulir Pengajuan Cuti
                    </button>
                </li>
                <li class="nav-item">
                    <button class="nav-link d-inline-flex align-items-center" id="pills-monitoring-tab" data-bs-toggle="pill" data-bs-target="#pills-monitoring" type="button">
                        <i class="bi bi-clipboard-data me-2"></i> Hasil &amp; Monitoring Cuti
                    </button>
                </li>
                <li class="nav-item">
                    <button class="nav-link d-inline-flex align-items-center" id="pills-regulasi-tab" data-bs-toggle="pill" data-bs-target="#pills-regulasi" type="button">
                        <i class="bi bi-journal-text me-2"></i> Ketentuan Pemerintah (BKN)
                    </button>
                </li>
            </ul>

            <div class="d-flex align-items-center gap-2">
                <?php if(!empty($is_admin)): ?>
                    <button type="button" class="btn btn-sm btn-outline-success fw-bold rounded-pill px-3 py-2 shadow-sm" onclick="syncGoogleSheet(this)" title="Fitur Khusus Admin: Sinkron data dari Spreadsheet">
                        <i class="bi bi-arrow-repeat me-1"></i> Sinkron Spreadsheet
                    </button>
                <?php else: ?>
                    <a href="<?= base_url('auth') ?>" class="btn btn-sm btn-outline-secondary rounded-pill px-3 py-2 fw-semibold">
                        <i class="bi bi-box-arrow-in-right me-1"></i> Login Akun Madrasah
                    </a>
                <?php endif; ?>
                <span class="badge bg-warning text-dark px-3 py-2 rounded-pill fw-bold" style="font-size: 13px;">
                    <i class="bi bi-calendar-check me-1"></i> Tahun <?= date('Y') ?>
                </span>
            </div>
        </div>

        <div class="tab-content" id="cutiPillsTabContent">

            <!-- TAB 1: FORMULIR PENGAJUAN MANDIRI -->
            <div class="tab-pane fade show active" id="pills-form" role="tabpanel">

                <div class="alert alert-success border-0 shadow-sm d-flex align-items-center mb-4 py-3 px-4 rounded-4" style="background-color: #ecfdf5; border-left: 5px solid #10b981 !important;">
                    <i class="bi bi-info-circle-fill fs-3 text-success me-3"></i>
                    <div>
                        <strong class="d-block text-success" style="font-size: 15px;">Layanan Pengajuan Cuti Mandiri (Mode Bypass Tanpa Login)</strong>
                        <span class="text-secondary small">
                            Silakan pilih nama atau masukkan NIP Anda di bawah. Sistem akan otomatis melengkapi profil, masa kerja, dan saldo cuti Anda. Pengajuan ini berstatus <strong>Menunggu Persetujuan</strong> dan akan diverifikasi oleh Kepala Madrasah &amp; Tata Usaha.
                        </span>
                    </div>
                </div>

                <form action="<?= base_url('cuti/submit') ?>" method="POST" enctype="multipart/form-data" id="formCuti">

                    <!-- LANGKAH 1: IDENTITAS PEGAWAI (AUTOCOMPLETE NIP) -->
                    <div class="card cuti-card-main mb-4">
                        <div class="card-body p-4 p-md-5">
                            <div class="d-flex align-items-center gap-2 mb-2">
                                <span class="badge bg-success-subtle text-success px-3 py-2 rounded-pill fw-bold" style="font-size: 12px;">
                                    <i class="bi bi-person-badge-fill me-1"></i> Langkah 1
                                </span>
                            </div>
                            <h4 class="fw-bold mb-1" style="color: #0f172a;">Identitas Pegawai Pemohon</h4>
                            <p class="text-muted mb-4 small">Ketik nama lengkap atau 18 digit NIP Anda untuk mengisi data secara otomatis.</p>

                            <div class="row g-3">
                                <div class="col-md-12">
                                    <label class="form-label fw-bold text-secondary small">Pilih Pegawai / Masukkan NIP <span class="text-danger">*</span></label>
                                    <select class="form-select select2-pegawai" id="select_ptk" name="ptk_selector" required>
                                        <option value="">-- Ketik Nama atau NIP Anda di sini --</option>
                                        <?php foreach ($pegawai_list as $p): ?>
                                            <option value="<?= $p->nip ?>" data-id="<?= $p->id ?>" data-nama="<?= htmlspecialchars($p->nama_lengkap) ?>">
                                                <?= htmlspecialchars($p->nama_lengkap) ?> - NIP. <?= $p->nip ?: '(Non-NIP)' ?> (<?= $p->status_kepegawaian ?: 'ASN' ?>)
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>

                            <input type="hidden" name="ptk_id" id="val_ptk_id">

                            <!-- DETAIL PEGAWAI (MUNCUL OTOMATIS) -->
                            <div id="detail_pegawai_box" class="mt-4 pt-4 border-top" style="display: none;">
                                <div class="row g-3">
                                    <div class="col-md-4">
                                        <label class="form-label text-secondary small fw-bold">Nama Lengkap</label>
                                        <input type="text" class="form-control bg-light border-0 fw-bold py-2" id="res_nama" readonly style="border-radius: 12px;">
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label text-secondary small fw-bold">NIP</label>
                                        <input type="text" class="form-control bg-light border-0 py-2" id="res_nip" readonly style="border-radius: 12px;">
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label text-secondary small fw-bold">Status Kepegawaian</label>
                                        <input type="text" class="form-control bg-light border-0 py-2" id="res_status" readonly style="border-radius: 12px;">
                                    </div>
                                    <div class="col-md-5">
                                        <label class="form-label text-secondary small fw-bold">Jabatan</label>
                                        <input type="text" class="form-control bg-light border-0 py-2" id="res_jabatan" readonly style="border-radius: 12px;">
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-label text-secondary small fw-bold">Pangkat / Golongan</label>
                                        <input type="text" class="form-control bg-light border-0 py-2" id="res_pangkat" readonly style="border-radius: 12px;">
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label text-secondary small fw-bold">Masa Kerja (TMT NIP)</label>
                                        <input type="text" class="form-control bg-light border-0 fw-bold text-success py-2" id="res_masakerja" readonly style="border-radius: 12px;">
                                    </div>
                                </div>

                                <!-- KOTAK REKAPITULASI SALDO CUTI BKN -->
                                <div class="mt-4 p-4 rounded-4" style="background: #f8fafc; border: 1px solid #e2e8f0;">
                                    <div class="d-flex justify-content-between align-items-center mb-3">
                                        <div class="fw-bold text-dark" style="font-size: 14.5px;">
                                            <i class="bi bi-calculator text-success me-2"></i>Catatan Saldo Cuti Tahunan (Perka BKN No. 24/2017)
                                        </div>
                                        <span class="badge bg-success px-3 py-2 rounded-pill fw-bold" id="res_total_badge">Total Hak Cuti: 0 Hari</span>
                                    </div>
                                    <div class="row g-3">
                                        <div class="col-6 col-md-3">
                                            <div class="saldo-box">
                                                <div class="saldo-label">Tahun N-2 (<span id="lbl_thn_n2"><?= date('Y')-2 ?></span>)</div>
                                                <div class="saldo-num" id="res_sisa_n2">-</div>
                                                <div class="small text-muted" style="font-size: 11px;">Sisa (Maks 6 Hari)</div>
                                            </div>
                                        </div>
                                        <div class="col-6 col-md-3">
                                            <div class="saldo-box">
                                                <div class="saldo-label">Tahun N-1 (<span id="lbl_thn_n1"><?= date('Y')-1 ?></span>)</div>
                                                <div class="saldo-num" id="res_sisa_n1">-</div>
                                                <div class="small text-muted" style="font-size: 11px;">Sisa (Maks 6 Hari)</div>
                                            </div>
                                        </div>
                                        <div class="col-6 col-md-3">
                                            <div class="saldo-box">
                                                <div class="saldo-label">Tahun N (<span id="lbl_thn_n"><?= date('Y') ?></span>)</div>
                                                <div class="saldo-num" id="res_sisa_n">12</div>
                                                <div class="small text-muted" style="font-size: 11px;">Jatah Berjalan</div>
                                            </div>
                                        </div>
                                        <div class="col-6 col-md-3">
                                            <div class="saldo-box bg-success-subtle border-success">
                                                <div class="saldo-label text-success">Total Tersedia</div>
                                                <div class="saldo-num text-success" id="res_sisa_total">12</div>
                                                <div class="small text-success fw-bold" style="font-size: 11px;">Hari Kerja Aktif</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>

                    <!-- LANGKAH 2: PILIHAN JENIS CUTI RESMI BKN -->
                    <div class="card cuti-card-main mb-4">
                        <div class="card-body p-4 p-md-5">
                            <div class="d-flex align-items-center gap-2 mb-2">
                                <span class="badge bg-success-subtle text-success px-3 py-2 rounded-pill fw-bold" style="font-size: 12px;">
                                    <i class="bi bi-list-check me-1"></i> Langkah 2
                                </span>
                            </div>
                            <h4 class="fw-bold mb-1" style="color: #0f172a;">Jenis Cuti Yang Diambil</h4>
                            <p class="text-muted mb-4 small">Pilih salah satu dari 6 jenis cuti ASN yang diatur pemerintah.</p>

                            <div class="row g-3">
                                <!-- Cuti Tahunan -->
                                <div class="col-md-4 col-sm-6">
                                    <div class="jenis-cuti-card active" onclick="pilihJenisCuti('Cuti Tahunan', this)">
                                        <div class="d-flex justify-content-between align-items-center mb-1">
                                            <h6 class="fw-bold mb-0 text-success">Cuti Tahunan</h6>
                                            <i class="bi bi-check-circle-fill text-success check-icon fs-5"></i>
                                        </div>
                                        <small class="text-muted d-block" style="font-size: 12px;">12 hari kerja per tahun. Akumulasi N-1 &amp; N-2 maks 6 hari.</small>
                                    </div>
                                </div>
                                <!-- Cuti Besar -->
                                <div class="col-md-4 col-sm-6">
                                    <div class="jenis-cuti-card" onclick="pilihJenisCuti('Cuti Besar', this)">
                                        <div class="d-flex justify-content-between align-items-center mb-1">
                                            <h6 class="fw-bold mb-0">Cuti Besar</h6>
                                            <i class="bi bi-circle text-muted check-icon fs-5"></i>
                                        </div>
                                        <small class="text-muted d-block" style="font-size: 12px;">Masa kerja &ge; 5 tahun terus-menerus. Paling lama 3 bulan.</small>
                                    </div>
                                </div>
                                <!-- Cuti Sakit -->
                                <div class="col-md-4 col-sm-6">
                                    <div class="jenis-cuti-card" onclick="pilihJenisCuti('Cuti Sakit', this)">
                                        <div class="d-flex justify-content-between align-items-center mb-1">
                                            <h6 class="fw-bold mb-0">Cuti Sakit</h6>
                                            <i class="bi bi-circle text-muted check-icon fs-5"></i>
                                        </div>
                                        <small class="text-muted d-block" style="font-size: 12px;">Wajib melampirkan surat keterangan dokter / rumah sakit.</small>
                                    </div>
                                </div>
                                <!-- Cuti Melahirkan -->
                                <div class="col-md-4 col-sm-6">
                                    <div class="jenis-cuti-card" onclick="pilihJenisCuti('Cuti Melahirkan', this)">
                                        <div class="d-flex justify-content-between align-items-center mb-1">
                                            <h6 class="fw-bold mb-0">Cuti Melahirkan</h6>
                                            <i class="bi bi-circle text-muted check-icon fs-5"></i>
                                        </div>
                                        <small class="text-muted d-block" style="font-size: 12px;">Kelahiran anak ke-1 s.d ke-3. Lamanya 3 bulan penuh.</small>
                                    </div>
                                </div>
                                <!-- Cuti Karena Alasan Penting -->
                                <div class="col-md-4 col-sm-6">
                                    <div class="jenis-cuti-card" onclick="pilihJenisCuti('Cuti Alasan Penting', this)">
                                        <div class="d-flex justify-content-between align-items-center mb-1">
                                            <h6 class="fw-bold mb-0">Cuti Alasan Penting</h6>
                                            <i class="bi bi-circle text-muted check-icon fs-5"></i>
                                        </div>
                                        <small class="text-muted d-block" style="font-size: 12px;">Keluarga sakit/wafat, nikah, bencana, istri sesar (maks 1 bulan).</small>
                                    </div>
                                </div>
                                <!-- Cuti di Luar Tanggungan Negara -->
                                <div class="col-md-4 col-sm-6">
                                    <div class="jenis-cuti-card" onclick="pilihJenisCuti('Cuti diluar Tanggungan Negara', this)">
                                        <div class="d-flex justify-content-between align-items-center mb-1">
                                            <h6 class="fw-bold mb-0">Cuti di Luar Tanggungan</h6>
                                            <i class="bi bi-circle text-muted check-icon fs-5"></i>
                                        </div>
                                        <small class="text-muted d-block" style="font-size: 12px;">Masa kerja &ge; 5 tahun untuk alasan mendesak, s.d 3 tahun.</small>
                                    </div>
                                </div>
                            </div>

                            <input type="hidden" name="jenis_cuti" id="input_jenis_cuti" value="Cuti Tahunan">

                            <!-- SUB-PILIHAN ALOKASI TAHUN (N / N-1 / N-2) -->
                            <div id="sub_opsi_cuti_tahunan" class="mt-4 p-4 bg-light rounded-4 border">
                                <label class="form-label fw-bold small text-dark mb-2">
                                    <i class="bi bi-sliders me-1 text-success"></i>Pilih Sumber Kuota Cuti Tahunan yang Digunakan:
                                </label>
                                <div class="row g-3">
                                    <div class="col-md-4">
                                        <div class="form-check p-3 bg-white rounded-3 border h-100">
                                            <input class="form-check-input ms-1" type="radio" name="alokasi_cuti_tahunan" id="opt_thn_n" value="Cuti Tahunan" checked onchange="ubahAlokasiCuti(this.value)">
                                            <label class="form-check-label ms-2 small fw-semibold" for="opt_thn_n">
                                                Tahun Berjalan (N / <?= date('Y') ?>)
                                                <div class="text-success fw-bold">Sisa: <span id="lbl_opt_sisa_n">12</span> Hari</div>
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-check p-3 bg-white rounded-3 border h-100" id="box_opt_n1">
                                            <input class="form-check-input ms-1" type="radio" name="alokasi_cuti_tahunan" id="opt_thn_n1" value="Cuti Tahunan N-1" onchange="ubahAlokasiCuti(this.value)">
                                            <label class="form-check-label ms-2 small fw-semibold" for="opt_thn_n1">
                                                Sisa Tahun Lalu (N-1 / <?= date('Y')-1 ?>)
                                                <div class="text-primary fw-bold">Sisa: <span id="lbl_opt_sisa_n1">-</span> Hari</div>
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-check p-3 bg-white rounded-3 border h-100" id="box_opt_n2">
                                            <input class="form-check-input ms-1" type="radio" name="alokasi_cuti_tahunan" id="opt_thn_n2" value="Cuti Tahunan N-2" onchange="ubahAlokasiCuti(this.value)">
                                            <label class="form-check-label ms-2 small fw-semibold" for="opt_thn_n2">
                                                Sisa 2 Thn Lalu (N-2 / <?= date('Y')-2 ?>)
                                                <div class="text-muted fw-bold">Sisa: <span id="lbl_opt_sisa_n2">-</span> Hari</div>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- LANGKAH 3: ALASAN, TANGGAL & DURASI CUTI -->
                    <div class="card cuti-card-main mb-4">
                        <div class="card-body p-4 p-md-5">
                            <div class="d-flex align-items-center gap-2 mb-2">
                                <span class="badge bg-success-subtle text-success px-3 py-2 rounded-pill fw-bold" style="font-size: 12px;">
                                    <i class="bi bi-calendar-range me-1"></i> Langkah 3
                                </span>
                            </div>
                            <h4 class="fw-bold mb-1" style="color: #0f172a;">Waktu, Alasan, dan Durasi Cuti</h4>
                            <p class="text-muted mb-4 small">Lama cuti kerja dihitung otomatis berdasarkan kalender kerja efektif madrasah.</p>

                            <div class="row g-3">
                                <div class="col-md-12">
                                    <label class="form-label fw-bold text-secondary small">Alasan Menjalankan Cuti <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control bg-light border-0 py-2" name="alasan_cuti" id="input_alasan" placeholder="Contoh: Acara Keluarga / Pernikahan / Menemani Orang Tua Sakit / Melahirkan" required style="border-radius: 12px; font-size: 14px;">
                                    <div class="d-flex gap-2 mt-2 flex-wrap">
                                        <button type="button" class="btn btn-sm btn-white border rounded-pill px-3 py-1 text-secondary" style="font-size: 12px;" onclick="isiAlasan('Acara Keluarga')">Acara Keluarga</button>
                                        <button type="button" class="btn btn-sm btn-white border rounded-pill px-3 py-1 text-secondary" style="font-size: 12px;" onclick="isiAlasan('Pernikahan Keluarga')">Pernikahan Keluarga</button>
                                        <button type="button" class="btn btn-sm btn-white border rounded-pill px-3 py-1 text-secondary" style="font-size: 12px;" onclick="isiAlasan('Mengantar anak ke pondok pesantren')">Antar Anak ke Pondok</button>
                                        <button type="button" class="btn btn-sm btn-white border rounded-pill px-3 py-1 text-secondary" style="font-size: 12px;" onclick="isiAlasan('Orangtua (Mertua) Sakit')">Orangtua Sakit</button>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <label class="form-label fw-bold text-secondary small">Tanggal Mulai Cuti <span class="text-danger">*</span></label>
                                    <input type="date" class="form-control bg-light border-0 py-2" name="tanggal_mulai" id="tgl_mulai" required style="border-radius: 12px; font-size: 14px;" onchange="hitungHariKerja()">
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label fw-bold text-secondary small">Tanggal Selesai Cuti <span class="text-danger">*</span></label>
                                    <input type="date" class="form-control bg-light border-0 py-2" name="tanggal_selesai" id="tgl_selesai" required style="border-radius: 12px; font-size: 14px;" onchange="hitungHariKerja()">
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label fw-bold text-secondary small">Lamanya Cuti</label>
                                    <div class="input-group">
                                        <input type="number" class="form-control bg-light border-0 fw-bold text-success py-2" name="lama_cuti_jumlah" id="lama_cuti_jumlah" value="1" min="1" required style="border-radius: 12px 0 0 12px; font-size: 15px;">
                                        <input type="text" class="form-control bg-light border-0 text-muted py-2" name="lama_cuti_satuan" id="lama_cuti_satuan" value="Hari" readonly style="border-radius: 0 12px 12px 0; max-width: 80px; font-size: 14px;">
                                    </div>
                                </div>

                                <!-- INFO KALKULASI HARI KERJA -->
                                <div class="col-12" id="info_hitung_hari" style="display: none;">
                                    <div class="p-3 rounded-3 bg-light border text-dark small d-flex align-items-center">
                                        <i class="bi bi-info-circle-fill text-success me-2 fs-5"></i>
                                        <span id="txt_hitung_hari"></span>
                                    </div>
                                </div>

                                <!-- UPLOAD DOKUMEN PENDUKUNG -->
                                <div class="col-md-12 mt-3">
                                    <label class="form-label fw-bold text-secondary small">Dokumen Pendukung / Surat Dokter (Opsional)</label>
                                    <input type="file" class="form-control bg-light border-0" name="file_lampiran" id="file_lampiran" accept=".pdf,.doc,.docx,.jpg,.jpeg,.png" style="border-radius: 12px; font-size: 13px;">
                                    <div class="form-text small text-muted">Format: PDF, JPG, PNG (Maksimal 5MB). Wajib dilampirkan jika mengajukan <strong>Cuti Sakit</strong>.</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- LANGKAH 4: ALAMAT & NOMOR TELEPON SELAMA CUTI -->
                    <div class="card cuti-card-main mb-4">
                        <div class="card-body p-4 p-md-5">
                            <div class="d-flex align-items-center gap-2 mb-2">
                                <span class="badge bg-success-subtle text-success px-3 py-2 rounded-pill fw-bold" style="font-size: 12px;">
                                    <i class="bi bi-geo-alt-fill me-1"></i> Langkah 4
                                </span>
                            </div>
                            <h4 class="fw-bold mb-1" style="color: #0f172a;">Alamat &amp; Kontak Selama Cuti</h4>
                            <p class="text-muted mb-4 small">Alamat dan nomor telepon yang dapat dihubungi selama menjalankan cuti.</p>

                            <div class="row g-3">
                                <div class="col-md-8">
                                    <label class="form-label fw-bold text-secondary small">Alamat Lengkap Selama Cuti <span class="text-danger">*</span></label>
                                    <textarea class="form-control bg-light border-0 p-3" name="alamat_cuti" id="input_alamat" rows="3" placeholder="Tuliskan nama jalan, RT/RW, desa/kelurahan, kecamatan, kab/kota, provinsi" required style="border-radius: 14px; font-size: 14px;"></textarea>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label fw-bold text-secondary small">Nomor Telepon / WhatsApp <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light border-0" style="border-radius: 12px 0 0 12px;"><i class="bi bi-whatsapp text-muted"></i></span>
                                        <input type="text" class="form-control bg-light border-0 py-2" name="telepon_cuti" id="input_telepon" placeholder="08xxxxxxxxxx" required style="border-radius: 0 12px 12px 0; font-size: 14px;">
                                    </div>
                                    <div class="form-text small text-muted mt-2">Pastikan nomor aktif dan dapat dihubungi jika ada keperluan mendesak.</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- TOMBOL KIRIM PENGAJUAN -->
                    <div class="text-center my-4 pb-4">
                        <button type="submit" class="btn btn-success px-5 py-3 fw-bold d-inline-flex align-items-center gap-2 shadow" style="border-radius: 14px; background: #059669; border-color: #059669; font-size: 16px;">
                            <i class="bi bi-send-fill"></i> Kirim Pengajuan Cuti Mandiri
                        </button>
                        <div class="small text-muted mt-2">
                            <i class="bi bi-info-circle text-primary me-1"></i>Permohonan akan berstatus <strong>Menunggu Persetujuan</strong> dan diverifikasi oleh Kepala Madrasah &amp; Admin TU.
                        </div>
                    </div>

                </form>

            </div>

            <!-- TAB 2: HASIL & MONITORING STATUS CUTI (LANGSUNG TAMPIL TANPA NIP) -->
            <div class="tab-pane fade" id="pills-monitoring" role="tabpanel">
                <div class="card cuti-card-main">
                    <div class="card-body p-4 p-md-5">
                        <div class="d-flex align-items-center justify-content-between flex-wrap gap-2 mb-3">
                            <div>
                                <span class="badge bg-success-subtle text-success px-3 py-2 rounded-pill fw-bold mb-2" style="font-size: 12px;">
                                    <i class="bi bi-broadcast me-1"></i> Monitoring Terbuka
                                </span>
                                <h4 class="fw-bold mb-1" style="color: #0f172a;">Daftar Pengajuan &amp; Status Cuti ASN</h4>
                                <p class="text-muted small mb-0">Status permohonan diperbarui secara transparan dan realtime setelah diverifikasi pimpinan.</p>
                            </div>
                            <span class="badge bg-light text-muted border px-3 py-2 rounded-pill">
                                Total: <strong id="count_monitoring" class="text-dark"><?= count($riwayat_cuti) ?></strong> Permohonan
                            </span>
                        </div>

                        <!-- KETENTUAN PENCETAKAN RESMI -->
                        <div class="alert alert-light border d-flex align-items-center mb-4 py-3 px-3 rounded-4 small">
                            <i class="bi bi-shield-lock-fill fs-3 text-warning me-3"></i>
                            <div>
                                <strong>Ketentuan Pencetakan Formulir Resmi:</strong> Demi ketertiban arsip dan regulasi kepegawaian, 
                                <strong>formulir cuti resmi BKN tidak dapat dicetak bebas</strong>. Pencetakan fisik berstempel dilakukan langsung oleh <strong>Admin Tata Usaha</strong> atau login ke Akun Pegawai masing-masing setelah permohonan disetujui.
                            </div>
                        </div>

                        <!-- LIVE SEARCH & FILTER -->
                        <div class="row g-2 mb-4 align-items-center">
                            <div class="col-md-7">
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-0" style="border-radius: 12px 0 0 12px;"><i class="bi bi-search text-muted"></i></span>
                                    <input type="text" class="form-control bg-light border-0 py-2" id="search_monitoring" placeholder="Ketik Nama Pegawai atau NIP untuk menyaring data..." onkeyup="filterTabelMonitoring()" style="border-radius: 0 12px 12px 0; font-size: 14px;">
                                </div>
                            </div>
                            <div class="col-md-5">
                                <select class="form-select bg-light border-0 py-2" id="filter_status_select" onchange="filterTabelMonitoring()" style="border-radius: 12px; font-size: 14px;">
                                    <option value="">-- Semua Status Permohonan --</option>
                                    <option value="Draft">Menunggu Verifikasi TU</option>
                                    <option value="Disetujui Kepala">Disetujui Kepala Madrasah</option>
                                    <option value="Aktif">Disetujui Penuh (Aktif)</option>
                                    <option value="Ditolak">Ditolak</option>
                                </select>
                            </div>
                        </div>

                        <!-- TABEL MONITORING RESMI -->
                        <div class="table-responsive" style="border-radius: 16px; border: 1px solid #e2e8f0; overflow: hidden;">
                            <table class="table table-hover align-middle mb-0 table-monitoring" id="table_monitoring">
                                <thead style="background: #f1f5f9; color: #334155;">
                                    <tr class="text-nowrap">
                                        <th class="text-center py-3" width="4%">No</th>
                                        <th width="10%">Tanggal</th>
                                        <th width="24%">Nama Pegawai &amp; NIP</th>
                                        <th width="14%">Jenis Cuti</th>
                                        <th width="8%" class="text-center">Durasi</th>
                                        <th width="16%">Rentang Tanggal</th>
                                        <th width="12%">Alasan</th>
                                        <th width="12%" class="text-center">Status</th>
                                        <th width="10%" class="text-center">Hak Cetak</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (empty($riwayat_cuti)): ?>
                                        <tr>
                                            <td colspan="9" class="text-center text-muted py-5">Belum ada permohonan cuti yang tercatat.</td>
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
                                                $status_label = '<i class="bi bi-clock-history me-1"></i>Menunggu TU';
                                            } elseif ($r->status == 'Disetujui Kepala') {
                                                $badge_class = 'bg-info text-dark';
                                                $status_label = '<i class="bi bi-check-circle-fill me-1"></i>Disetujui Kamad';
                                            } elseif ($r->status == 'Aktif') {
                                                $badge_class = 'bg-success';
                                                $status_label = '<i class="bi bi-check-all me-1"></i>Disetujui (Aktif)';
                                            } elseif ($r->status == 'Ditolak') {
                                                $badge_class = 'bg-danger';
                                                $status_label = '<i class="bi bi-x-circle-fill me-1"></i>Ditolak';
                                            }
                                            ?>
                                            <tr class="row-monitoring" data-nama="<?= strtolower(htmlspecialchars($r->nama_lengkap ?? '')) ?>" data-nip="<?= htmlspecialchars($r->nip ?? '') ?>" data-status="<?= htmlspecialchars($r->status) ?>">
                                                <td class="text-center text-muted small py-3"><?= $no++ ?></td>
                                                <td class="small text-muted"><?= $tgl_surat ?></td>
                                                <td>
                                                    <div class="fw-bold" style="color: #0f172a; font-size: 14px;"><?= htmlspecialchars($r->nama_lengkap ?? '-') ?></div>
                                                    <div class="text-muted small">NIP. <?= htmlspecialchars($r->nip ?? '-') ?></div>
                                                </td>
                                                <td>
                                                    <span class="badge bg-success-subtle text-success fw-semibold px-2 py-1 rounded-pill" style="font-size: 12px;">
                                                        <?= htmlspecialchars($r->jenis_cuti) ?>
                                                    </span>
                                                </td>
                                                <td class="text-center fw-bold" style="font-size: 13.5px;">
                                                    <?= $r->lama_cuti_jumlah ?> <?= $r->lama_cuti_satuan ?>
                                                </td>
                                                <td class="small">
                                                    <div><?= $tgl_mulai ?></div>
                                                    <div class="text-muted">s.d <?= $tgl_selesai ?></div>
                                                </td>
                                                <td class="small text-muted" style="max-width: 170px;">
                                                    <?= htmlspecialchars($r->alasan_cuti ?: '-') ?>
                                                </td>
                                                <td class="text-center">
                                                    <span class="badge <?= $badge_class ?> px-2 py-1 rounded-pill" style="font-size: 11.5px;"><?= $status_label ?></span>
                                                </td>
                                                <td class="text-center">
                                                    <span class="badge bg-light text-muted border px-2 py-1 rounded-pill" title="Pencetakan formulir resmi BKN dilakukan oleh Tata Usaha atau melalui Akun Pegawai masing-masing." style="cursor: help; font-size: 11px;">
                                                        <i class="bi bi-lock-fill me-1 text-secondary"></i>Via Akun/Admin
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
                            <button class="btn btn-sm btn-outline-secondary rounded-pill px-3 py-1 fw-semibold" type="button" data-bs-toggle="collapse" data-bs-target="#boxCekSaldoPribadi">
                                <i class="bi bi-search me-1"></i> Cek Sisa Jatah Hak Cuti Pribadi (N, N-1, N-2)
                            </button>
                            <div class="collapse mt-3" id="boxCekSaldoPribadi">
                                <div class="p-4 bg-light rounded-4 border">
                                    <div class="row g-2 align-items-center">
                                        <div class="col-md-6">
                                            <label class="form-label small fw-bold mb-1 text-secondary">Masukkan 18 Digit NIP Anda</label>
                                            <div class="input-group">
                                                <input type="text" class="form-control bg-white border-0 py-2" id="cari_nip_status" placeholder="Contoh: 197409032000121001" style="border-radius: 12px 0 0 12px;">
                                                <button class="btn btn-success px-4" type="button" onclick="cekStatusCuti()" style="border-radius: 0 12px 12px 0; background: #059669; border-color: #059669;">
                                                    <i class="bi bi-search me-1"></i> Cek Saldo
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                    <div id="hasil_status_box" class="mt-3 pt-3 border-top" style="display: none;">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div>
                                                <strong class="text-success fs-6" id="status_nama_pegawai"></strong>
                                                <div class="text-muted small" id="status_nip_pegawai"></div>
                                            </div>
                                            <span class="badge bg-success px-3 py-2 rounded-pill fs-6" id="status_sisa_cuti"></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>

            <!-- TAB 3: KETENTUAN PEMERINTAH (BKN) -->
            <div class="tab-pane fade" id="pills-regulasi" role="tabpanel">
                <div class="card cuti-card-main">
                    <div class="card-body p-4 p-md-5">
                        <div class="d-flex align-items-center gap-2 mb-2">
                            <span class="badge bg-success-subtle text-success px-3 py-2 rounded-pill fw-bold" style="font-size: 12px;">
                                <i class="bi bi-book-half me-1"></i> Regulasi Resmi
                            </span>
                        </div>
                        <h4 class="fw-bold mb-1" style="color: #0f172a;">Ketentuan &amp; Tata Cara Pemberian Cuti ASN</h4>
                        <p class="text-muted mb-4 small">Berdasarkan Peraturan BKN Nomor 24 Tahun 2017 &amp; Peraturan BKN Nomor 7 Tahun 2021.</p>

                        <div class="accordion" id="accordionRegulasi">
                            <!-- 1. Cuti Tahunan -->
                            <div class="accordion-item mb-3 border rounded-3 overflow-hidden">
                                <h2 class="accordion-header">
                                    <button class="accordion-button fw-bold text-success" type="button" data-bs-toggle="collapse" data-bs-target="#reg1">
                                        1. Cuti Tahunan (12 Hari Kerja)
                                    </button>
                                </h2>
                                <div id="reg1" class="accordion-collapse collapse show">
                                    <div class="accordion-body small text-secondary">
                                        <ul>
                                            <li>Diberikan kepada PNS/PPPK yang telah bekerja sekurang-kurangnya 1 (satu) tahun terus-menerus.</li>
                                            <li>Lamanya hak cuti tahunan adalah <strong>12 (dua belas) hari kerja</strong>.</li>
                                            <li>Hak cuti tahunan yang tidak digunakan dalam tahun bersangkutan dapat digunakan dalam tahun berikutnya untuk paling lama <strong>6 (enam) hari kerja</strong> (N-1 maks 6 hari kerja).</li>
                                            <li>Hak cuti tahunan yang tidak digunakan 2 (dua) tahun berturut-turut dapat digunakan dalam tahun berikutnya untuk paling lama <strong>6 (enam) hari kerja</strong> (N-2 maks 6 hari kerja).</li>
                                            <li>Pengambilan cuti tahunan dihitung pada <strong>hari kerja efektif</strong> (Sabtu, Minggu, dan Hari Libur Nasional tidak dihitung).</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>

                            <!-- 2. Cuti Besar -->
                            <div class="accordion-item mb-3 border rounded-3 overflow-hidden">
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
                            <div class="accordion-item mb-3 border rounded-3 overflow-hidden">
                                <h2 class="accordion-header">
                                    <button class="accordion-button collapsed fw-bold" type="button" data-bs-toggle="collapse" data-bs-target="#reg3">
                                        3. Cuti Sakit
                                    </button>
                                </h2>
                                <div id="reg3" class="accordion-collapse collapse">
                                    <div class="accordion-body small text-secondary">
                                        <ul>
                                            <li>Sakit 1 s.d 14 hari: Wajib melampirkan surat keterangan dokter.</li>
                                            <li>Sakit lebih dari 14 hari: Wajib melampirkan surat keterangan dokter spesialis / rumah sakit pemerintah.</li>
                                            <li>Diberikan paling lama 1 (satu) tahun dan dapat diperpanjang maksimal 6 bulan berdasarkan rekomendasi tim penguji kesehatan.</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>

                            <!-- 4. Cuti Melahirkan -->
                            <div class="accordion-item mb-3 border rounded-3 overflow-hidden">
                                <h2 class="accordion-header">
                                    <button class="accordion-button collapsed fw-bold" type="button" data-bs-toggle="collapse" data-bs-target="#reg4">
                                        4. Cuti Melahirkan (3 Bulan)
                                    </button>
                                </h2>
                                <div id="reg4" class="accordion-collapse collapse">
                                    <div class="accordion-body small text-secondary">
                                        <ul>
                                            <li>Untuk kelahiran anak pertama sampai dengan anak ketiga pada saat menjadi PNS.</li>
                                            <li>Lamanya cuti melahirkan adalah <strong>3 (tiga) bulan</strong>.</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>

                            <!-- 5. Cuti Alasan Penting -->
                            <div class="accordion-item mb-3 border rounded-3 overflow-hidden">
                                <h2 class="accordion-header">
                                    <button class="accordion-button collapsed fw-bold" type="button" data-bs-toggle="collapse" data-bs-target="#reg5">
                                        5. Cuti Karena Alasan Penting (Maksimal 1 Bulan)
                                    </button>
                                </h2>
                                <div id="reg5" class="accordion-collapse collapse">
                                    <div class="accordion-body small text-secondary">
                                        <ul>
                                            <li>Ibu, bapak, isteri/suami, anak, adik, kakak, mertua, atau menantu sakit keras atau meninggal dunia.</li>
                                            <li>Melangsungkan perkawinan (pernikahan pertama).</li>
                                            <li>Mengalami musibah bencana alam.</li>
                                            <li>PNS laki-laki yang isterinya melahirkan melalui operasi caesar.</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>

                            <!-- 6. Cuti di Luar Tanggungan Negara -->
                            <div class="accordion-item mb-3 border rounded-3 overflow-hidden">
                                <h2 class="accordion-header">
                                    <button class="accordion-button collapsed fw-bold" type="button" data-bs-toggle="collapse" data-bs-target="#reg6">
                                        6. Cuti di Luar Tanggungan Negara (CLTN)
                                    </button>
                                </h2>
                                <div id="reg6" class="accordion-collapse collapse">
                                    <div class="accordion-body small text-secondary">
                                        <ul>
                                            <li>Diberikan kepada PNS yang telah bekerja sekurang-kurangnya 5 (lima) tahun terus-menerus karena alasan pribadi dan mendesak.</li>
                                            <li>Diberikan paling lama 3 (tiga) tahun dan dapat diperpanjang paling lama 1 tahun.</li>
                                            <li>Selama menjalankan CLTN, tidak berhak menerima penghasilan dari negara.</li>
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
</section>

<!-- JAVASCRIPT LOGIC -->
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<script>
$(document).ready(function() {
    // Inisialisasi Select2 Pegawai
    $('#select_ptk').select2({
        theme: 'bootstrap-5',
        placeholder: '-- Ketik Nama Lengkap atau NIP Anda --',
        allowClear: true,
        width: '100%'
    });

    // Ketika Pegawai / NIP dipilih
    $('#select_ptk').on('change', function() {
        const nip = $(this).val();
        if (!nip) {
            $('#detail_pegawai_box').slideUp();
            $('#val_ptk_id').val('');
            return;
        }

        // Ambil detail profil dan saldo cuti via AJAX
        $.ajax({
            url: '<?= base_url("cuti/ajax_get_ptk") ?>',
            type: 'POST',
            data: { nip: nip },
            dataType: 'json',
            success: function(res) {
                if (res.status === 'success') {
                    const d = res.data;
                    $('#val_ptk_id').val(d.id);
                    $('#res_nama').val(d.nama_lengkap);
                    $('#res_nip').val(d.nip || '-');
                    $('#res_status').val(d.status_kepegawaian || 'PNS');
                    $('#res_jabatan').val(d.jabatan);
                    $('#res_pangkat').val(d.pangkat_golongan);
                    $('#res_masakerja').val(d.masa_kerja);

                    if (d.alamat && !$('#input_alamat').val()) {
                        $('#input_alamat').val(d.alamat);
                    }
                    if (d.no_hp && !$('#input_telepon').val()) {
                        $('#input_telepon').val(d.no_hp);
                    }

                    // Isi Saldo Cuti
                    const s = d.saldo;
                    $('#lbl_thn_n2').text(s.tahun_n2);
                    $('#lbl_thn_n1').text(s.tahun_n1);
                    $('#lbl_thn_n').text(s.tahun_n);

                    $('#res_sisa_n2').text(s.sisa_n2 > 0 ? s.sisa_n2 : '-');
                    $('#res_sisa_n1').text(s.sisa_n1 > 0 ? s.sisa_n1 : '-');
                    $('#res_sisa_n').text(s.sisa_n);
                    $('#res_sisa_total').text(s.total_hak);
                    $('#res_total_badge').text('Total Hak Cuti: ' + s.total_hak + ' Hari');

                    $('#lbl_opt_sisa_n').text(s.sisa_n);
                    $('#lbl_opt_sisa_n1').text(s.sisa_n1 > 0 ? s.sisa_n1 : '-');
                    $('#lbl_opt_sisa_n2').text(s.sisa_n2 > 0 ? s.sisa_n2 : '-');

                    $('#detail_pegawai_box').slideDown();
                    hitungHariKerja();
                } else {
                    alert(res.message);
                }
            },
            error: function() {
                alert('Gagal mengambil data pegawai. Periksa koneksi ke server.');
            }
        });
    });

    // Otomatis buka tab monitoring jika ada hash #tab-status atau parameter url
    if (window.location.hash === '#tab-status' || window.location.search.indexOf('tab=monitoring') !== -1) {
        const triggerEl = document.querySelector('#pills-monitoring-tab');
        if (triggerEl) {
            const tabInstance = new bootstrap.Tab(triggerEl);
            tabInstance.show();
        }
    }
});

function pilihJenisCuti(jenis, el) {
    $('.jenis-cuti-card').removeClass('active');
    $(el).addClass('active');

    $('.jenis-cuti-card .check-icon')
        .removeClass('bi-check-circle-fill text-success')
        .addClass('bi-circle text-muted');
    $(el).find('.check-icon')
        .removeClass('bi-circle text-muted')
        .addClass('bi-check-circle-fill text-success');

    if (jenis === 'Cuti Tahunan') {
        $('#sub_opsi_cuti_tahunan').slideDown();
        const alokasi = $('input[name="alokasi_cuti_tahunan"]:checked').val() || 'Cuti Tahunan';
        $('#input_jenis_cuti').val(alokasi);
        $('#lama_cuti_satuan').val('Hari');
        hitungHariKerja();
    } else {
        $('#sub_opsi_cuti_tahunan').slideUp();
        $('#input_jenis_cuti').val(jenis);

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

function isiAlasan(text) {
    $('#input_alasan').val(text);
}

function hitungHariKerja() {
    const start = $('#tgl_mulai').val();
    const end = $('#tgl_selesai').val();
    const jenis = $('#input_jenis_cuti').val();

    if (!start || !end) return;

    $.ajax({
        url: '<?= base_url("cuti/ajax_hitung_hari") ?>',
        type: 'POST',
        data: {
            tanggal_mulai: start,
            tanggal_selesai: end,
            jenis_cuti: jenis
        },
        dataType: 'json',
        success: function(res) {
            if (res.status === 'success') {
                if (jenis === 'Cuti Tahunan' || jenis.indexOf('Tahunan') !== -1) {
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

function cekStatusCuti() {
    const nip = $('#cari_nip_status').val().trim();
    if (!nip) {
        alert('Silakan masukkan NIP Anda.');
        return;
    }

    $.ajax({
        url: '<?= base_url("cuti/ajax_cek_status") ?>',
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

function syncGoogleSheet(btn) {
    const $btn = $(btn);
    const origHtml = $btn.html();
    $btn.prop('disabled', true).html('<i class="bi bi-arrow-repeat me-1 spinner-border spinner-border-sm"></i> Menyinkronkan...');

    $.ajax({
        url: '<?= base_url("cuti/sync_kuota_sheet") ?>',
        type: 'POST',
        dataType: 'json',
        success: function(res) {
            $btn.prop('disabled', false).html(origHtml);
            alert(res.message);
            const curNip = $('#select_ptk').val();
            if (curNip) {
                $('#select_ptk').trigger('change');
            }
        },
        error: function() {
            $btn.prop('disabled', false).html(origHtml);
            alert('Gagal menghubungi server untuk sinkronisasi Google Spreadsheet.');
        }
    });
}
</script>

<?php 
// Load Footer Resmi Website Madrasah
$this->load->view('public/partials/archive_footer'); 
?>
