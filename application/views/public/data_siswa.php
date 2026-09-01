<?php $this->load->view('public/partials/archive_header'); ?>

<style>
/* Custom Style Keadaan Siswa */
.web-archive-hero {
    background: radial-gradient(circle at top right, rgba(16, 185, 129, 0.35), transparent 50%),
                radial-gradient(circle at bottom left, rgba(2, 44, 34, 0.5), transparent 50%),
                linear-gradient(135deg, #064e3b 0%, #065f46 50%, #047857 100%) !important;
    color: #ffffff !important;
    padding: 50px 0 45px 0 !important;
    position: relative;
    overflow: hidden;
}
.web-archive-hero h1 {
    color: #ffffff !important;
    font-weight: 900 !important;
    font-size: clamp(1.8rem, 3.5vw, 2.4rem) !important;
    letter-spacing: -0.02em !important;
    margin-bottom: 8px !important;
    text-shadow: 0 2px 10px rgba(0,0,0,0.15) !important;
}
.web-archive-hero p {
    color: #ecfdf5 !important;
    font-size: 14.5px !important;
    line-height: 1.6 !important;
    margin: 0 !important;
    font-weight: 500 !important;
}
.web-archive-hero .detail-breadcrumb {
    font-size: 13px !important;
    margin-bottom: 14px !important;
}
.web-archive-hero .detail-breadcrumb a {
    color: #a7f3d0 !important;
    text-decoration: none !important;
    font-weight: 600 !important;
}
.web-archive-hero .detail-breadcrumb span {
    color: rgba(255,255,255,0.4) !important;
    margin: 0 6px !important;
}
.web-archive-hero .detail-breadcrumb strong {
    color: #ffffff !important;
    font-weight: 700 !important;
}

.stat-card-siswa {
    background: #ffffff;
    border-radius: 18px;
    border: 1px solid #e2e8f0;
    box-shadow: 0 4px 20px -2px rgba(0, 0, 0, 0.05);
    transition: all 0.25s ease;
    position: relative;
    overflow: hidden;
}
.stat-card-siswa:hover {
    transform: translateY(-4px);
    box-shadow: 0 12px 25px -4px rgba(0, 0, 0, 0.1);
}
.stat-card-primary {
    background: linear-gradient(135deg, #064e3b 0%, #047857 50%, #059669 100%);
    color: #ffffff;
    border: none;
}
.stat-icon-wrap {
    width: 48px;
    height: 48px;
    border-radius: 14px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 22px;
}
.tab-custom-btn {
    padding: 10px 22px;
    border-radius: 12px;
    font-weight: 700;
    font-size: 14px;
    transition: all 0.2s ease;
    border: 1.5px solid transparent;
}
.tab-custom-btn.active {
    background: #047857 !important;
    color: #ffffff !important;
    border-color: #047857 !important;
    box-shadow: 0 4px 14px rgba(4, 120, 87, 0.3);
}
.filter-tingkat-btn {
    padding: 6px 16px;
    border-radius: 20px;
    font-size: 13px;
    font-weight: 600;
    border: 1px solid #cbd5e1;
    background: #ffffff;
    color: #475569;
    cursor: pointer;
    transition: all 0.2s;
}
.filter-tingkat-btn:hover, .filter-tingkat-btn.active {
    background: #047857;
    color: #ffffff;
    border-color: #047857;
}
.progress-ratio {
    height: 8px;
    border-radius: 4px;
    overflow: hidden;
    background: #f1f5f9;
    display: flex;
}
.gender-bar-l {
    background: #3b82f6;
    height: 100%;
}
.gender-bar-p {
    background: #ec4899;
    height: 100%;
}

@media print {
    .web-archive-hero, .no-print, nav, footer, .btn, .nav-tabs, .filter-tingkat-wrap {
        display: none !important;
    }
    body {
        background: #ffffff !important;
        color: #000000 !important;
        font-size: 12pt;
    }
    .print-only-header {
        display: block !important;
        text-align: center;
        margin-bottom: 25px;
    }
    .card {
        border: none !important;
        box-shadow: none !important;
        padding: 0 !important;
    }
    .table {
        width: 100% !important;
        border: 1px solid #000000 !important;
    }
    .table th, .table td {
        border: 1px solid #000000 !important;
        padding: 6px 8px !important;
        color: #000000 !important;
    }
}
.print-only-header {
    display: none;
}
</style>

<!-- Print Only Header -->
<div class="print-only-header">
    <h3 style="font-weight: 800; margin: 0; text-transform: uppercase;"><?= htmlspecialchars($nama_madrasah ?? 'MAN 3 BANJAR', ENT_QUOTES, 'UTF-8') ?></h3>
    <h4 style="font-weight: 700; margin: 5px 0;">REKAPITULASI KEADAAN SISWA</h4>
    <p style="margin: 0;">Tahun Pelajaran: <strong><?= htmlspecialchars($tahun_ajaran, ENT_QUOTES, 'UTF-8') ?></strong> | Dicetak pada: <?= date('d F Y') ?></p>
    <hr style="border-top: 2px solid #000; margin-top: 10px;">
</div>

<!-- Header Hero Section -->
<header class="web-archive-hero" style="background: linear-gradient(135deg, #064e3b 0%, #047857 50%, #059669 100%); color: white; padding: 50px 0 45px 0;">
    <div class="container">
        <div class="detail-breadcrumb mb-3">
            <a href="<?= base_url() ?>" style="color: rgba(255,255,255,0.85); text-decoration: none;"><i class="bi bi-house-door"></i> Beranda</a>
            <span style="color: rgba(255,255,255,0.5); margin: 0 8px;">/</span>
            <strong style="color: #ffffff;">Keadaan Siswa</strong>
        </div>
        
        <div class="row align-items-center g-3">
            <div class="col-lg-8">
                <div class="d-inline-flex align-items-center gap-2 px-3 py-1 mb-2 rounded-pill" style="background: rgba(255,255,255,0.18); backdrop-filter: blur(8px); font-size: 13px; font-weight: 600;">
                    <i class="bi bi-mortarboard-fill text-warning"></i>
                    <span>Tahun Pelajaran <?= htmlspecialchars($tahun_ajaran, ENT_QUOTES, 'UTF-8') ?></span>
                    <?php if(!empty($is_active_ta)): ?>
                        <span class="badge bg-success text-white" style="font-size: 11px;">Aktif Berjalan</span>
                    <?php endif; ?>
                </div>
                <h1 style="font-weight: 900; font-size: 2.3rem; letter-spacing: -0.5px; margin-bottom: 8px;">Keadaan & Statistik Siswa</h1>
                <p style="font-size: 15px; opacity: 0.9; margin: 0; max-width: 680px;">
                    Statistika resmi, rasio gender, komposisi rombongan belajar, dan rekapitulasi data siswa di <?= htmlspecialchars($nama_madrasah ?? 'MAN 3 Banjar', ENT_QUOTES, 'UTF-8') ?>.
                </p>
            </div>
            
            <div class="col-lg-4 text-lg-end no-print">
                <form action="<?= base_url('website/data_siswa') ?>" method="GET" class="d-inline-block text-start w-100" style="max-width: 320px;">
                    <label class="text-white small fw-bold mb-1" style="font-size: 12px; opacity: 0.95;">
                        <i class="bi bi-calendar-event me-1"></i> Pilih Tahun Pelajaran:
                    </label>
                    <div class="input-group">
                        <select name="ta" class="form-select border-0 shadow-sm" onchange="this.form.submit()" style="border-radius: 12px 0 0 12px; font-weight: 600; font-size: 14px;">
                            <?php foreach($list_ta as $ta_item): ?>
                                <option value="<?= htmlspecialchars($ta_item, ENT_QUOTES, 'UTF-8') ?>" <?= ($ta_item == $tahun_ajaran) ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($ta_item, ENT_QUOTES, 'UTF-8') ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                        <button type="submit" class="btn btn-warning fw-bold px-3" style="border-radius: 0 12px 12px 0;">
                            <i class="bi bi-arrow-repeat"></i>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</header>

<section class="web-section" style="background: #f8fafc; padding: 40px 0 60px 0;">
    <div class="container">
        
        <!-- 5 Summary Stat Cards -->
        <?php 
        $pct_l = $total > 0 ? round(($total_l / $total) * 100, 1) : 0;
        $pct_p = $total > 0 ? round(($total_p / $total) * 100, 1) : 0;
        ?>
        <div class="row g-3 mb-4">
            <!-- 1. Total Siswa -->
            <div class="col-xl-3 col-lg-6 col-md-6">
                <div class="stat-card-siswa stat-card-primary p-4 h-100">
                    <div style="position: absolute; top: -15px; right: -15px; width: 100px; height: 100px; background: rgba(255,255,255,0.08); border-radius: 50%;"></div>
                    <div class="d-flex align-items-center justify-content-between mb-3 position-relative z-1">
                        <span class="small fw-bold text-uppercase" style="letter-spacing: 0.5px; opacity: 0.9;">Total Siswa</span>
                        <div class="stat-icon-wrap" style="background: rgba(255,255,255,0.18);">
                            <i class="bi bi-people-fill text-white"></i>
                        </div>
                    </div>
                    <div class="position-relative z-1">
                        <h2 class="fw-bold mb-1" style="font-size: 2.4rem; line-height: 1;"><?= number_format($total, 0, ',', '.') ?></h2>
                        <span class="small" style="opacity: 0.85;">Orang Siswa Terdaftar</span>
                    </div>
                </div>
            </div>

            <!-- 2. Laki-laki -->
            <div class="col-xl-2 col-lg-3 col-md-6 col-6">
                <div class="stat-card-siswa p-3 p-md-4 h-100" style="border-top: 4px solid #3b82f6;">
                    <div class="d-flex align-items-center justify-content-between mb-2">
                        <span class="small fw-bold text-muted">Laki-laki</span>
                        <div class="stat-icon-wrap" style="background: #eff6ff; color: #3b82f6; width: 38px; height: 38px; font-size: 18px;">
                            <i class="bi bi-gender-male"></i>
                        </div>
                    </div>
                    <h3 class="fw-bold text-dark mb-1" style="font-size: 1.8rem;"><?= number_format($total_l, 0, ',', '.') ?></h3>
                    <span class="badge bg-primary bg-opacity-10 text-primary fw-bold" style="font-size: 11px;">
                        <?= $pct_l ?>% dari total
                    </span>
                </div>
            </div>

            <!-- 3. Perempuan -->
            <div class="col-xl-2 col-lg-3 col-md-6 col-6">
                <div class="stat-card-siswa p-3 p-md-4 h-100" style="border-top: 4px solid #ec4899;">
                    <div class="d-flex align-items-center justify-content-between mb-2">
                        <span class="small fw-bold text-muted">Perempuan</span>
                        <div class="stat-icon-wrap" style="background: #fdf2f8; color: #ec4899; width: 38px; height: 38px; font-size: 18px;">
                            <i class="bi bi-gender-female"></i>
                        </div>
                    </div>
                    <h3 class="fw-bold text-dark mb-1" style="font-size: 1.8rem;"><?= number_format($total_p, 0, ',', '.') ?></h3>
                    <span class="badge bg-danger bg-opacity-10 text-danger fw-bold" style="font-size: 11px;">
                        <?= $pct_p ?>% dari total
                    </span>
                </div>
            </div>

            <!-- 4. Total Rombel -->
            <div class="col-xl-2 col-lg-6 col-md-6 col-6">
                <div class="stat-card-siswa p-3 p-md-4 h-100" style="border-top: 4px solid #10b981;">
                    <div class="d-flex align-items-center justify-content-between mb-2">
                        <span class="small fw-bold text-muted">Rombel</span>
                        <div class="stat-icon-wrap" style="background: #ecfdf5; color: #10b981; width: 38px; height: 38px; font-size: 18px;">
                            <i class="bi bi-door-open-fill"></i>
                        </div>
                    </div>
                    <h3 class="fw-bold text-dark mb-1" style="font-size: 1.8rem;"><?= $total_rombel ?></h3>
                    <span class="small text-muted" style="font-size: 11px;">Kelas Belajar</span>
                </div>
            </div>

            <!-- 5. Rata-rata per Kelas -->
            <div class="col-xl-3 col-lg-6 col-md-6 col-6">
                <div class="stat-card-siswa p-3 p-md-4 h-100" style="border-top: 4px solid #f59e0b;">
                    <div class="d-flex align-items-center justify-content-between mb-2">
                        <span class="small fw-bold text-muted">Rata-rata/Kelas</span>
                        <div class="stat-icon-wrap" style="background: #fffbeb; color: #f59e0b; width: 38px; height: 38px; font-size: 18px;">
                            <i class="bi bi-calculator"></i>
                        </div>
                    </div>
                    <h3 class="fw-bold text-dark mb-1" style="font-size: 1.8rem;"><?= $avg_siswa ?></h3>
                    <span class="small text-muted" style="font-size: 11px;">Siswa per Rombel</span>
                </div>
            </div>
        </div>

        <!-- Navigation Tabs -->
        <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-4 no-print">
            <ul class="nav nav-pills gap-2 p-1 bg-white rounded-4 shadow-sm border" id="siswaTab" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link tab-custom-btn active" id="grafik-tab" data-bs-toggle="pill" data-bs-target="#grafik-pane" type="button" role="tab">
                        <i class="bi bi-pie-chart-fill me-1"></i> Visual Grafik
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link tab-custom-btn text-secondary" id="tabel-tab" data-bs-toggle="pill" data-bs-target="#tabel-pane" type="button" role="tab">
                        <i class="bi bi-table me-1"></i> Matriks Rincian Kelas
                    </button>
                </li>
            </ul>

            <button onclick="window.print()" class="btn btn-outline-dark fw-bold rounded-3 px-3 py-2 shadow-sm">
                <i class="bi bi-printer-fill me-1"></i> Cetak Rekapitulasi
            </button>
        </div>

        <!-- Tab Content Panes -->
        <div class="tab-content" id="siswaTabContent">
            
            <!-- TAB 1: VISUAL GRAFIK INTERAKTIF -->
            <div class="tab-pane fade show active" id="grafik-pane" role="tabpanel">
                
                <div class="row g-4 mb-4">
                    <!-- Chart 1: Donut Gender -->
                    <div class="col-lg-4 col-md-12">
                        <div class="card border-0 shadow-sm h-100" style="border-radius: 18px; background: #ffffff;">
                            <div class="card-body p-4 d-flex flex-column justify-content-between">
                                <div>
                                    <div class="d-flex align-items-center justify-content-between mb-3">
                                        <h6 class="fw-bold text-dark mb-0">
                                            <i class="bi bi-gender-ambiguous text-primary me-2"></i> Rasio Jenis Kelamin
                                        </h6>
                                        <span class="badge bg-light text-dark fw-normal">Total: <?= $total ?></span>
                                    </div>
                                    <div style="height: 240px; position: relative;">
                                        <canvas id="chartGenderSiswa"></canvas>
                                    </div>
                                </div>
                                <div class="row text-center mt-3 pt-3 border-top g-2">
                                    <div class="col-6">
                                        <div class="p-2 rounded-3" style="background: #eff6ff;">
                                            <span class="small text-primary fw-bold d-block"><i class="bi bi-gender-male me-1"></i> Laki-laki</span>
                                            <span class="fw-bold text-dark"><?= $total_l ?> (<?= $pct_l ?>%)</span>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="p-2 rounded-3" style="background: #fdf2f8;">
                                            <span class="small text-danger fw-bold d-block"><i class="bi bi-gender-female me-1"></i> Perempuan</span>
                                            <span class="fw-bold text-dark"><?= $total_p ?> (<?= $pct_p ?>%)</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Chart 2: Bar Siswa per Tingkat -->
                    <div class="col-lg-4 col-md-6">
                        <div class="card border-0 shadow-sm h-100" style="border-radius: 18px; background: #ffffff;">
                            <div class="card-body p-4 d-flex flex-column justify-content-between">
                                <div>
                                    <div class="d-flex align-items-center justify-content-between mb-3">
                                        <h6 class="fw-bold text-dark mb-0">
                                            <i class="bi bi-bar-chart-fill text-success me-2"></i> Distribusi per Tingkat
                                        </h6>
                                        <span class="badge bg-success bg-opacity-10 text-success fw-bold">X, XI, XII</span>
                                    </div>
                                    <div style="height: 240px; position: relative;">
                                        <canvas id="chartTingkatSiswa"></canvas>
                                    </div>
                                </div>
                                <div class="d-flex justify-content-around text-center mt-3 pt-3 border-top">
                                    <?php foreach(['X', 'XI', 'XII'] as $tg): 
                                        $t_tot = $rekap_tingkat[$tg]['total'] ?? 0;
                                    ?>
                                    <div>
                                        <span class="small text-muted d-block fw-semibold">Kelas <?= $tg ?></span>
                                        <span class="fw-bold text-dark"><?= $t_tot ?> Siswa</span>
                                    </div>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Chart 3: Grouped Bar Gender per Tingkat -->
                    <div class="col-lg-4 col-md-6">
                        <div class="card border-0 shadow-sm h-100" style="border-radius: 18px; background: #ffffff;">
                            <div class="card-body p-4 d-flex flex-column justify-content-between">
                                <div>
                                    <div class="d-flex align-items-center justify-content-between mb-3">
                                        <h6 class="fw-bold text-dark mb-0">
                                            <i class="bi bi-people-fill text-warning me-2"></i> Gender per Tingkat
                                        </h6>
                                        <span class="badge bg-warning bg-opacity-10 text-dark fw-semibold">L vs P</span>
                                    </div>
                                    <div style="height: 240px; position: relative;">
                                        <canvas id="chartGenderPerTingkat"></canvas>
                                    </div>
                                </div>
                                <div class="d-flex align-items-center justify-content-center gap-3 mt-3 pt-3 border-top small text-muted">
                                    <span><span class="badge bg-primary me-1">&nbsp;</span> Laki-laki</span>
                                    <span><span class="badge bg-danger me-1">&nbsp;</span> Perempuan</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Mini Cards Summary Tingkat X, XI, XII -->
                <div class="row g-3">
                    <?php 
                    $tingkat_colors = [
                        'X' => ['border' => '#059669', 'bg' => '#ecfdf5', 'text' => '#059669', 'icon' => '1-square-fill'],
                        'XI' => ['border' => '#3b82f6', 'bg' => '#eff6ff', 'text' => '#3b82f6', 'icon' => '2-square-fill'],
                        'XII' => ['border' => '#8b5cf6', 'bg' => '#f5f3ff', 'text' => '#8b5cf6', 'icon' => '3-square-fill']
                    ];
                    foreach(['X', 'XI', 'XII'] as $tg):
                        $t_data = $rekap_tingkat[$tg] ?? ['total' => 0, 'L' => 0, 'P' => 0, 'rombel' => 0];
                        $c = $tingkat_colors[$tg];
                    ?>
                    <div class="col-md-4">
                        <div class="card border-0 shadow-sm p-3 h-100" style="border-radius: 16px; background: #ffffff; border-left: 5px solid <?= $c['border'] ?> !important;">
                            <div class="d-flex align-items-center justify-content-between mb-2">
                                <div class="d-flex align-items-center gap-2">
                                    <span class="fw-bold" style="color: <?= $c['text'] ?>; font-size: 16px;">Tingkat <?= $tg ?></span>
                                    <span class="badge bg-light text-secondary border"><?= $t_data['rombel'] ?> Kelas</span>
                                </div>
                                <span class="fw-bold text-dark fs-5"><?= $t_data['total'] ?> <small class="text-muted fs-6">Siswa</small></span>
                            </div>
                            
                            <?php 
                            $t_pct_l = $t_data['total'] > 0 ? round(($t_data['L'] / $t_data['total']) * 100) : 0;
                            $t_pct_p = $t_data['total'] > 0 ? round(($t_data['P'] / $t_data['total']) * 100) : 0;
                            ?>
                            <div class="progress-ratio mb-2">
                                <div class="gender-bar-l" style="width: <?= $t_pct_l ?>%;"></div>
                                <div class="gender-bar-p" style="width: <?= $t_pct_p ?>%;"></div>
                            </div>
                            
                            <div class="d-flex justify-content-between small text-muted">
                                <span><i class="bi bi-gender-male text-primary"></i> <?= $t_data['L'] ?> (<?= $t_pct_l ?>%)</span>
                                <span><i class="bi bi-gender-female text-danger"></i> <?= $t_data['P'] ?> (<?= $t_pct_p ?>%)</span>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>

            </div>

            <!-- TAB 2: TABEL RINCIAN KELAS / ROMBEL -->
            <div class="tab-pane fade" id="tabel-pane" role="tabpanel">
                
                <div class="card border-0 shadow-sm" style="border-radius: 18px; background: #ffffff; overflow: hidden;">
                    <div class="card-body p-4 p-md-5">
                        
                        <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-4 filter-tingkat-wrap">
                            <div>
                                <h5 class="fw-bold text-dark mb-1"><i class="bi bi-table text-success me-2"></i> Rincian Jumlah Siswa per Kelas</h5>
                                <p class="text-muted small mb-0">Rincian jenis kelamin L/P dan rasio per rombongan belajar</p>
                            </div>

                            <!-- Filter buttons -->
                            <div class="d-flex align-items-center gap-2">
                                <button class="filter-tingkat-btn active" data-filter="all">Semua Kelas</button>
                                <button class="filter-tingkat-btn" data-filter="X">Kelas X</button>
                                <button class="filter-tingkat-btn" data-filter="XI">Kelas XI</button>
                                <button class="filter-tingkat-btn" data-filter="XII">Kelas XII</button>
                            </div>
                        </div>

                        <div class="table-responsive">
                            <table class="table table-hover align-middle text-center border mb-0" id="tableRekapSiswa" style="border-radius: 12px; overflow: hidden;">
                                <thead style="background: #f1f5f9; color: #334155;">
                                    <tr>
                                        <th rowspan="2" class="align-middle text-start ps-4" style="font-weight: 700; width: 25%;">Nama Kelas / Rombel</th>
                                        <th rowspan="2" class="align-middle" style="font-weight: 700; width: 12%;">Tingkat</th>
                                        <th colspan="2" style="font-weight: 700; border-bottom: 1px solid #cbd5e1;">Jenis Kelamin</th>
                                        <th rowspan="2" class="align-middle" style="font-weight: 700; width: 20%;" class="no-print">Komposisi Gender</th>
                                        <th rowspan="2" class="align-middle" style="font-weight: 800; background: #e2e8f0; width: 15%;">Jumlah Siswa</th>
                                    </tr>
                                    <tr style="background: #f8fafc;">
                                        <th style="color: #3b82f6; font-weight: 700; width: 14%;">Laki-laki (L)</th>
                                        <th style="color: #ec4899; font-weight: 700; width: 14%;">Perempuan (P)</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php 
                                    $sort_order = ['X' => 1, 'XI' => 2, 'XII' => 3];
                                    uasort($rekap_kelas, function($a, $b) use ($sort_order) {
                                        $t1 = $sort_order[$a['tingkat']] ?? 99;
                                        $t2 = $sort_order[$b['tingkat']] ?? 99;
                                        if($t1 == $t2) {
                                            return strnatcasecmp($a['nama_kelas'], $b['nama_kelas']);
                                        }
                                        return $t1 - $t2;
                                    });

                                    $current_tingkat = '';
                                    $sub_l = 0; $sub_p = 0; $sub_total = 0;
                                    $last_key = array_key_last($rekap_kelas);

                                    if(empty($rekap_kelas)):
                                    ?>
                                    <tr>
                                        <td colspan="6" class="py-5 text-muted">
                                            <i class="bi bi-info-circle fs-3 d-block mb-2 text-secondary"></i>
                                            Belum ada data siswa untuk Tahun Pelajaran <?= htmlspecialchars($tahun_ajaran, ENT_QUOTES, 'UTF-8') ?>
                                        </td>
                                    </tr>
                                    <?php 
                                    else:
                                    foreach($rekap_kelas as $k_id => $k): 
                                        if($current_tingkat != '' && $current_tingkat != $k['tingkat']):
                                    ?>
                                        <!-- Subtotal row per tingkat -->
                                        <tr class="row-subtotal row-tingkat-<?= $current_tingkat ?>" style="background: #f1f5f9; font-weight: 800; border-top: 2px solid #cbd5e1; border-bottom: 2px solid #cbd5e1;">
                                            <td colspan="2" class="text-start ps-4" style="color: #1e293b;">
                                                <i class="bi bi-calculator-fill text-success me-2"></i> SUB TOTAL KELAS <?= $current_tingkat ?>
                                            </td>
                                            <td style="color: #2563eb;"><?= $sub_l ?></td>
                                            <td style="color: #db2777;"><?= $sub_p ?></td>
                                            <td class="no-print">
                                                <?php 
                                                $st_pct_l = $sub_total > 0 ? round(($sub_l / $sub_total) * 100) : 0;
                                                $st_pct_p = $sub_total > 0 ? round(($sub_p / $sub_total) * 100) : 0;
                                                ?>
                                                <div class="progress-ratio">
                                                    <div class="gender-bar-l" style="width: <?= $st_pct_l ?>%;"></div>
                                                    <div class="gender-bar-p" style="width: <?= $st_pct_p ?>%;"></div>
                                                </div>
                                            </td>
                                            <td style="background: #e2e8f0; color: #0f172a; font-size: 15px;"><?= $sub_total ?></td>
                                        </tr>
                                    <?php 
                                            $sub_l = 0; $sub_p = 0; $sub_total = 0;
                                        endif; 

                                        $current_tingkat = $k['tingkat'];
                                        $sub_l += $k['L'];
                                        $sub_p += $k['P'];
                                        $sub_total += $k['Total'];

                                        $k_pct_l = $k['Total'] > 0 ? round(($k['L'] / $k['Total']) * 100) : 0;
                                        $k_pct_p = $k['Total'] > 0 ? round(($k['P'] / $k['Total']) * 100) : 0;
                                    ?>
                                        <tr class="row-data-kelas row-tingkat-<?= $k['tingkat'] ?>">
                                            <td class="text-start ps-4 fw-bold text-dark">
                                                <i class="bi bi-door-closed text-muted me-2"></i> <?= htmlspecialchars($k['nama_kelas'], ENT_QUOTES, 'UTF-8') ?>
                                            </td>
                                            <td>
                                                <span class="badge bg-light text-secondary border fw-semibold">Kelas <?= htmlspecialchars($k['tingkat'], ENT_QUOTES, 'UTF-8') ?></span>
                                            </td>
                                            <td class="fw-semibold" style="color: #3b82f6;"><?= $k['L'] ?></td>
                                            <td class="fw-semibold" style="color: #ec4899;"><?= $k['P'] ?></td>
                                            <td class="no-print">
                                                <div class="progress-ratio" title="L: <?= $k_pct_l ?>% | P: <?= $k_pct_p ?>%">
                                                    <div class="gender-bar-l" style="width: <?= $k_pct_l ?>%;"></div>
                                                    <div class="gender-bar-p" style="width: <?= $k_pct_p ?>%;"></div>
                                                </div>
                                                <div class="d-flex justify-content-between text-muted" style="font-size: 10px; margin-top: 2px;">
                                                    <span><?= $k_pct_l ?>% L</span>
                                                    <span><?= $k_pct_p ?>% P</span>
                                                </div>
                                            </td>
                                            <td class="fw-bold" style="background: #f8fafc; color: #0f172a;"><?= $k['Total'] ?></td>
                                        </tr>

                                        <?php if($k_id == $last_key): ?>
                                        <!-- Last Subtotal row -->
                                        <tr class="row-subtotal row-tingkat-<?= $current_tingkat ?>" style="background: #f1f5f9; font-weight: 800; border-top: 2px solid #cbd5e1; border-bottom: 2px solid #cbd5e1;">
                                            <td colspan="2" class="text-start ps-4" style="color: #1e293b;">
                                                <i class="bi bi-calculator-fill text-success me-2"></i> SUB TOTAL KELAS <?= $current_tingkat ?>
                                            </td>
                                            <td style="color: #2563eb;"><?= $sub_l ?></td>
                                            <td style="color: #db2777;"><?= $sub_p ?></td>
                                            <td class="no-print">
                                                <?php 
                                                $st_pct_l = $sub_total > 0 ? round(($sub_l / $sub_total) * 100) : 0;
                                                $st_pct_p = $sub_total > 0 ? round(($sub_p / $sub_total) * 100) : 0;
                                                ?>
                                                <div class="progress-ratio">
                                                    <div class="gender-bar-l" style="width: <?= $st_pct_l ?>%;"></div>
                                                    <div class="gender-bar-p" style="width: <?= $st_pct_p ?>%;"></div>
                                                </div>
                                            </td>
                                            <td style="background: #e2e8f0; color: #0f172a; font-size: 15px;"><?= $sub_total ?></td>
                                        </tr>
                                        <?php endif; ?>
                                    <?php endforeach; ?>
                                    <?php endif; ?>
                                </tbody>
                                <tfoot>
                                    <tr style="background: #064e3b; color: #ffffff; font-weight: 900;">
                                        <td colspan="2" class="text-start ps-4 py-3" style="letter-spacing: 0.5px;">
                                            <i class="bi bi-check-all me-1"></i> TOTAL KESELURUHAN (<?= $total_rombel ?> KELAS)
                                        </td>
                                        <td class="py-3" style="color: #93c5fd; font-size: 16px;"><?= $total_l ?></td>
                                        <td class="py-3" style="color: #fbcfe8; font-size: 16px;"><?= $total_p ?></td>
                                        <td class="py-3 no-print">
                                            <div class="progress-ratio" style="background: rgba(255,255,255,0.2);">
                                                <div class="gender-bar-l" style="width: <?= $pct_l ?>%; background: #60a5fa;"></div>
                                                <div class="gender-bar-p" style="width: <?= $pct_p ?>%; background: #f472b6;"></div>
                                            </div>
                                        </td>
                                        <td class="py-3" style="background: #047857; color: #ffffff; font-size: 18px;">
                                            <?= number_format($total, 0, ',', '.') ?>
                                        </td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>

                    </div>
                </div>

            </div>

        </div>

    </div>
</section>

<!-- Chart.js CDN -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    
    // Tab switching style
    const tabButtons = document.querySelectorAll('#siswaTab .nav-link');
    tabButtons.forEach(btn => {
        btn.addEventListener('shown.bs.tab', function(e) {
            tabButtons.forEach(b => {
                b.classList.remove('active');
                b.classList.add('text-secondary');
            });
            e.target.classList.add('active');
            e.target.classList.remove('text-secondary');
        });
    });

    // Filter Buttons Tingkat
    const filterBtns = document.querySelectorAll('.filter-tingkat-btn');
    const rowsData = document.querySelectorAll('.row-data-kelas');
    const rowsSubtotal = document.querySelectorAll('.row-subtotal');

    filterBtns.forEach(btn => {
        btn.addEventListener('click', function() {
            filterBtns.forEach(b => b.classList.remove('active'));
            this.classList.add('active');
            const target = this.getAttribute('data-filter');

            if(target === 'all') {
                rowsData.forEach(r => r.style.display = '');
                rowsSubtotal.forEach(r => r.style.display = '');
            } else {
                rowsData.forEach(r => {
                    if(r.classList.contains('row-tingkat-' + target)) {
                        r.style.display = '';
                    } else {
                        r.style.display = 'none';
                    }
                });
                rowsSubtotal.forEach(r => {
                    if(r.classList.contains('row-tingkat-' + target)) {
                        r.style.display = '';
                    } else {
                        r.style.display = 'none';
                    }
                });
            }
        });
    });

    // 1. Chart Rasio Gender Donut
    const ctxGender = document.getElementById('chartGenderSiswa');
    if(ctxGender) {
        new Chart(ctxGender, {
            type: 'doughnut',
            data: {
                labels: ['Laki-laki', 'Perempuan'],
                datasets: [{
                    data: [<?= (int)$total_l ?>, <?= (int)$total_p ?>],
                    backgroundColor: ['#3b82f6', '#ec4899'],
                    borderWidth: 3,
                    borderColor: '#ffffff',
                    hoverOffset: 4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: { boxWidth: 12, font: { weight: '600', size: 12 } }
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                let val = context.raw || 0;
                                let total = <?= (int)$total ?>;
                                let pct = total > 0 ? ((val / total) * 100).toFixed(1) : 0;
                                return ' ' + context.label + ': ' + val + ' Siswa (' + pct + '%)';
                            }
                        }
                    }
                },
                cutout: '70%'
            }
        });
    }

    // 2. Chart Distribusi per Tingkat Bar
    const ctxTingkat = document.getElementById('chartTingkatSiswa');
    if(ctxTingkat) {
        new Chart(ctxTingkat, {
            type: 'bar',
            data: {
                labels: ['Kelas X', 'Kelas XI', 'Kelas XII'],
                datasets: [{
                    label: 'Jumlah Siswa',
                    data: [
                        <?= (int)($rekap_tingkat['X']['total'] ?? 0) ?>,
                        <?= (int)($rekap_tingkat['XI']['total'] ?? 0) ?>,
                        <?= (int)($rekap_tingkat['XII']['total'] ?? 0) ?>
                    ],
                    backgroundColor: ['#10b981', '#3b82f6', '#8b5cf6'],
                    borderRadius: 8,
                    maxBarThickness: 45
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                return ' ' + context.raw + ' Siswa';
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: { color: '#f1f5f9' },
                        ticks: { stepSize: 10 }
                    },
                    x: {
                        grid: { display: false },
                        ticks: { font: { weight: '600' } }
                    }
                }
            }
        });
    }

    // 3. Chart Gender per Tingkat Grouped Bar
    const ctxGPT = document.getElementById('chartGenderPerTingkat');
    if(ctxGPT) {
        new Chart(ctxGPT, {
            type: 'bar',
            data: {
                labels: ['Kelas X', 'Kelas XI', 'Kelas XII'],
                datasets: [
                    {
                        label: 'Laki-laki',
                        data: [
                            <?= (int)($rekap_tingkat['X']['L'] ?? 0) ?>,
                            <?= (int)($rekap_tingkat['XI']['L'] ?? 0) ?>,
                            <?= (int)($rekap_tingkat['XII']['L'] ?? 0) ?>
                        ],
                        backgroundColor: '#3b82f6',
                        borderRadius: 6,
                        maxBarThickness: 24
                    },
                    {
                        label: 'Perempuan',
                        data: [
                            <?= (int)($rekap_tingkat['X']['P'] ?? 0) ?>,
                            <?= (int)($rekap_tingkat['XI']['P'] ?? 0) ?>,
                            <?= (int)($rekap_tingkat['XII']['P'] ?? 0) ?>
                        ],
                        backgroundColor: '#ec4899',
                        borderRadius: 6,
                        maxBarThickness: 24
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                return ' ' + context.dataset.label + ': ' + context.raw + ' Siswa';
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: { color: '#f1f5f9' }
                    },
                    x: {
                        grid: { display: false },
                        ticks: { font: { weight: '600' } }
                    }
                }
            }
        });
    }

});
</script>

<?php $this->load->view('public/partials/archive_footer'); ?>
