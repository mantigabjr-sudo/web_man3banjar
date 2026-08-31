<?php $this->load->view('public/partials/archive_header'); ?>

<?php
if(!function_exists('ptk_clean')){
    function ptk_clean($text){
        return htmlspecialchars((string)$text, ENT_QUOTES, 'UTF-8');
    }
}

$tot_ptk = (int)($stats_summary->total_ptk ?? 0);
$tot_pendidik = (int)($stats_summary->total_pendidik ?? 0);
$tot_kependidikan = (int)($stats_summary->total_kependidikan ?? 0);
$tot_l = (int)($stats_summary->total_l ?? 0);
$tot_p = (int)($stats_summary->total_p ?? 0);
$tot_pns = (int)($stats_summary->total_pns ?? 0);
$tot_pppk = (int)($stats_summary->total_pppk ?? 0);
$tot_non_asn = (int)($stats_summary->total_non_asn ?? 0);

$pct_pendidik = $tot_ptk > 0 ? round(($tot_pendidik / $tot_ptk) * 100, 1) : 0;
$pct_kependidikan = $tot_ptk > 0 ? round(($tot_kependidikan / $tot_ptk) * 100, 1) : 0;
$pct_l = $tot_ptk > 0 ? round(($tot_l / $tot_ptk) * 100, 1) : 0;
$pct_p = $tot_ptk > 0 ? round(($tot_p / $tot_ptk) * 100, 1) : 0;

$m_pend = $matrix['pendidik'] ?? [];
$m_kep  = $matrix['kependidikan'] ?? [];
?>

<style>
/* ═══ MODERN PTK STATS & DIRECTORY DESIGN SYSTEM ═══ */
.ptk-hero-gradient {
    background: linear-gradient(135deg, #064e3b 0%, #065f46 50%, #047857 100%);
    color: #ffffff;
    padding: 45px 0 35px 0;
    position: relative;
    overflow: hidden;
}
.ptk-hero-gradient::before {
    content: "";
    position: absolute;
    top: -50px; right: -50px;
    width: 300px; height: 300px;
    background: radial-gradient(circle, rgba(16, 185, 129, 0.25) 0%, rgba(0,0,0,0) 70%);
    border-radius: 50%;
    pointer-events: none;
}

/* Stat Cards */
.ptk-stat-card {
    background: #ffffff;
    border-radius: 16px;
    padding: 20px 22px;
    box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.05), 0 8px 10px -6px rgba(0, 0, 0, 0.03);
    border: 1px solid rgba(226, 232, 240, 0.8);
    display: flex;
    align-items: center;
    gap: 18px;
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    position: relative;
    overflow: hidden;
}
.ptk-stat-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 20px 30px -10px rgba(0, 0, 0, 0.1);
    border-color: #cbd5e1;
}
.ptk-stat-icon {
    width: 58px;
    height: 58px;
    border-radius: 14px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.7rem;
    flex-shrink: 0;
}
.icon-emerald { background: #ecfdf5; color: #059669; }
.icon-blue { background: #eff6ff; color: #2563eb; }
.icon-purple { background: #faf5ff; color: #9333ea; }
.icon-amber { background: #fffbeb; color: #d97706; }

.ptk-stat-info h3 {
    font-size: 1.85rem;
    font-weight: 800;
    margin: 0;
    color: #0f172a;
    line-height: 1.1;
}
.ptk-stat-info p {
    font-size: 0.88rem;
    font-weight: 600;
    color: #64748b;
    margin: 3px 0 0 0;
}
.ptk-stat-sub {
    font-size: 0.76rem;
    color: #94a3b8;
    margin-top: 4px;
    display: block;
}

/* Nav Pills Custom */
.ptk-view-tabs {
    background: #f1f5f9;
    padding: 6px;
    border-radius: 12px;
    display: inline-flex;
    gap: 6px;
    border: 1px solid #e2e8f0;
}
.ptk-view-tabs .nav-link {
    color: #475569;
    font-weight: 700;
    font-size: 0.9rem;
    padding: 9px 20px;
    border-radius: 8px;
    transition: all 0.2s ease;
    border: none;
}
.ptk-view-tabs .nav-link.active {
    background: #ffffff;
    color: #059669;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.06);
}

/* Chart Container Box */
.ptk-chart-box {
    background: #ffffff;
    border-radius: 16px;
    padding: 24px;
    border: 1px solid #e2e8f0;
    box-shadow: 0 4px 16px rgba(0, 0, 0, 0.03);
    height: 100%;
    display: flex;
    flex-direction: column;
}
.ptk-chart-box h5 {
    font-size: 1.05rem;
    font-weight: 700;
    color: #1e293b;
    margin-bottom: 18px;
    display: flex;
    align-items: center;
    gap: 8px;
}
.chart-canvas-wrap {
    position: relative;
    flex-grow: 1;
    min-height: 230px;
    display: flex;
    align-items: center;
    justify-content: center;
}

/* Matrix Table Styling */
.table-matrix-ptk {
    background: #ffffff;
    border-radius: 16px;
    overflow: hidden;
    border: 1px solid #e2e8f0;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.04);
}
.table-matrix-ptk thead th {
    background: #f8fafc;
    color: #1e293b;
    font-weight: 700;
    font-size: 0.84rem;
    text-transform: uppercase;
    letter-spacing: 0.03em;
    vertical-align: middle;
    text-align: center;
    border-bottom: 2px solid #cbd5e1;
    padding: 12px 10px;
}
.table-matrix-ptk tbody td {
    vertical-align: middle;
    font-size: 0.88rem;
    padding: 14px 12px;
    border-bottom: 1px solid #f1f5f9;
}
.table-matrix-ptk tfoot td {
    background: #f1f5f9;
    font-weight: 800;
    color: #0f172a;
    font-size: 0.92rem;
    padding: 15px 12px;
    border-top: 2px solid #cbd5e1;
}
.badge-count {
    display: inline-block;
    padding: 4px 9px;
    border-radius: 6px;
    font-weight: 700;
    font-size: 0.82rem;
    min-width: 32px;
    text-align: center;
}
.b-pns { background: #eff6ff; color: #1d4ed8; }
.b-pppk { background: #faf5ff; color: #7e22ce; }
.b-non { background: #fff7ed; color: #c2410c; }
.b-tot { background: #ecfdf5; color: #047857; font-weight: 800; font-size: 0.88rem; }

/* Grid PTK Directory */
.web-ptk-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(260px, 1fr));
    gap: 22px;
}
.web-ptk-card {
    background: #ffffff;
    border-radius: 20px;
    padding: 24px 18px 20px;
    text-align: center;
    border: 1px solid #e2e8f0;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.03);
    transition: all 0.25s cubic-bezier(0.175, 0.885, 0.32, 1.275);
    display: flex;
    flex-direction: column;
    align-items: center;
    position: relative;
}
.web-ptk-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 15px 30px rgba(0,0,0,0.08);
    border-color: #10b981;
}
.web-ptk-img-wrap {
    width: 90px;
    height: 90px;
    border-radius: 50%;
    margin-bottom: 14px;
    position: relative;
    padding: 3px;
    background: linear-gradient(135deg, #10b981, #065f46);
    flex-shrink: 0;
    box-shadow: 0 6px 16px rgba(16, 185, 129, 0.2);
}
.web-ptk-img-wrap img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    border-radius: 50%;
    background: #fff;
}
.web-ptk-avatar-fallback {
    width: 100%;
    height: 100%;
    border-radius: 50%;
    background: #f1f5f9;
    color: #065f46;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 2.2rem;
    font-weight: 800;
}
.web-ptk-avatar-fallback.avatar-kependidikan {
    color: #1e40af;
}
.web-ptk-name {
    font-size: 0.96rem;
    font-weight: 800;
    color: #0f172a;
    margin-bottom: 6px;
    line-height: 1.35;
    min-height: 2.7em;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
    text-overflow: ellipsis;
}
.web-ptk-nip-wrap {
    margin-bottom: 10px;
}
.web-ptk-nip-badge {
    display: inline-flex;
    align-items: center;
    font-size: 0.76rem;
    font-weight: 700;
    color: #047857;
    background: #ecfdf5;
    border: 1px solid #d1fae5;
    padding: 3px 10px;
    border-radius: 99px;
    letter-spacing: 0.02em;
}
.web-ptk-status-badge {
    display: inline-flex;
    align-items: center;
    font-size: 0.76rem;
    font-weight: 700;
    color: #0284c7;
    background: #f0f9ff;
    border: 1px solid #e0f2fe;
    padding: 3px 10px;
    border-radius: 99px;
}
.web-ptk-info-group {
    width: 100%;
    background: #f8fafc;
    border: 1px solid #f1f5f9;
    border-radius: 12px;
    padding: 8px 10px;
    margin-bottom: 12px;
}
.web-ptk-jabatan {
    font-size: 0.86rem;
    font-weight: 800;
    color: #1e293b;
    line-height: 1.3;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}
.web-ptk-golongan {
    font-size: 0.76rem;
    font-weight: 700;
    color: #64748b;
    margin-top: 3px;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}
.web-ptk-badge-role {
    font-size: 0.75rem;
    font-weight: 700;
    padding: 5px 12px;
    border-radius: 20px;
    margin-top: auto;
    display: inline-flex;
    align-items: center;
    gap: 4px;
}
.role-pendidik { background: #dcfce7; color: #166534; }
.role-kependidikan { background: #e0f2fe; color: #075985; }

/* Filter Section */
.ptk-filter-panel {
    background: #f8fafc;
    border-radius: 16px;
    padding: 18px 22px;
    border: 1px solid #e2e8f0;
    margin-bottom: 25px;
}
</style>

<!-- Chart.js CDN -->
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>

<header class="ptk-hero-gradient">
    <div class="container">
        <div class="detail-breadcrumb mb-2" style="color: rgba(255,255,255,0.85);">
            <a href="<?= base_url() ?>" style="color: #ffffff; text-decoration: none;"><i class="bi bi-house-door"></i> Beranda</a>
            <span class="mx-2">/</span>
            <strong>Statistik &amp; Direktori PTK</strong>
        </div>
        <h1 class="fw-bold mb-2" style="font-size: 2.2rem;">Pendidik &amp; Tenaga Kependidikan</h1>
        <p class="mb-0" style="color: rgba(255,255,255,0.9); font-size: 1.05rem;">
            Profil statistik resmi, komposisi kepegawaian, dan direktori lengkap <?= htmlspecialchars($nama_madrasah ?? 'MAN 3 Banjar', ENT_QUOTES, 'UTF-8') ?>.
        </p>
    </div>
</header>

<section class="py-5" style="background: #f8fafc; min-height: 80vh;">
    <div class="container">

        <!-- ═══ 1. STATS HIGHLIGHT CARDS (4 CARDS) ═══ -->
        <div class="row g-3 mb-4">
            <!-- Card Total PTK -->
            <div class="col-6 col-lg-3">
                <div class="ptk-stat-card">
                    <div class="ptk-stat-icon icon-emerald">
                        <i class="bi bi-people-fill"></i>
                    </div>
                    <div class="ptk-stat-info">
                        <h3><?= $tot_ptk ?></h3>
                        <p>Total Seluruh PTK</p>
                        <span class="ptk-stat-sub">Aktif Mengabdi</span>
                    </div>
                </div>
            </div>

            <!-- Card Pendidik (Guru) -->
            <div class="col-6 col-lg-3">
                <div class="ptk-stat-card">
                    <div class="ptk-stat-icon icon-blue">
                        <i class="bi bi-mortarboard-fill"></i>
                    </div>
                    <div class="ptk-stat-info">
                        <h3><?= $tot_pendidik ?></h3>
                        <p>Tenaga Pendidik</p>
                        <span class="ptk-stat-sub"><?= $pct_pendidik ?>% dari total PTK</span>
                    </div>
                </div>
            </div>

            <!-- Card Tenaga Kependidikan (TU) -->
            <div class="col-6 col-lg-3">
                <div class="ptk-stat-card">
                    <div class="ptk-stat-icon icon-purple">
                        <i class="bi bi-building-fill-gear"></i>
                    </div>
                    <div class="ptk-stat-info">
                        <h3><?= $tot_kependidikan ?></h3>
                        <p>Kependidikan (TU)</p>
                        <span class="ptk-stat-sub"><?= $pct_kependidikan ?>% dari total PTK</span>
                    </div>
                </div>
            </div>

            <!-- Card Rasio Gender -->
            <div class="col-6 col-lg-3">
                <div class="ptk-stat-card">
                    <div class="ptk-stat-icon icon-amber">
                        <i class="bi bi-gender-ambiguous"></i>
                    </div>
                    <div class="ptk-stat-info">
                        <h3><?= $tot_l ?>L / <?= $tot_p ?>P</h3>
                        <p>Komposisi Gender</p>
                        <span class="ptk-stat-sub"><?= $pct_l ?>% Pria • <?= $pct_p ?>% Wanita</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- ═══ 2. TABS: VISUAL GRAFIK vs TABEL REKAPITULASI ═══ -->
        <div class="card border-0 shadow-sm rounded-4 mb-5 overflow-hidden">
            <div class="card-header bg-white border-bottom p-3 d-flex flex-wrap align-items-center justify-content-between gap-3">
                <div class="d-flex align-items-center gap-2">
                    <span class="badge bg-emerald-subtle text-emerald px-3 py-2 rounded-pill fw-bold" style="background: #ecfdf5; color: #059669;">
                        <i class="bi bi-bar-chart-line-fill me-1"></i> Rekapitulasi Data PTK
                    </span>
                </div>

                <ul class="nav ptk-view-tabs" id="ptkStatsTab" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="tab-grafik-btn" data-bs-toggle="tab" data-bs-target="#tab-grafik" type="button" role="tab">
                            <i class="bi bi-pie-chart-fill me-1"></i> Visual Grafik
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="tab-tabel-btn" data-bs-toggle="tab" data-bs-target="#tab-tabel" type="button" role="tab">
                            <i class="bi bi-table me-1"></i> Tabel Rincian Data
                        </button>
                    </li>
                </ul>
            </div>

            <div class="card-body p-4">
                <div class="tab-content" id="ptkStatsTabContent">
                    
                    <!-- ═══ TAB 1: VISUAL GRAFIK (3 CHARTS) ═══ -->
                    <div class="tab-pane fade show active" id="tab-grafik" role="tabpanel">
                        <div class="row g-4">
                            <!-- Chart 1: Kategori PTK -->
                            <div class="col-md-4">
                                <div class="ptk-chart-box">
                                    <h5><i class="bi bi-pie-chart-fill text-primary"></i> Kategori PTK</h5>
                                    <div class="chart-canvas-wrap">
                                        <canvas id="chartKategori"></canvas>
                                    </div>
                                    <div class="mt-3 pt-2 border-top d-flex justify-content-around text-center">
                                        <div>
                                            <span class="d-block fw-bold text-primary" style="font-size: 1.1rem;"><?= $tot_pendidik ?></span>
                                            <small class="text-muted">Guru (<?= $pct_pendidik ?>%)</small>
                                        </div>
                                        <div class="vr"></div>
                                        <div>
                                            <span class="d-block fw-bold" style="color: #8b5cf6; font-size: 1.1rem;"><?= $tot_kependidikan ?></span>
                                            <small class="text-muted">TU (<?= $pct_kependidikan ?>%)</small>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Chart 2: Status Kepegawaian (Bar Chart) -->
                            <div class="col-md-4">
                                <div class="ptk-chart-box">
                                    <h5><i class="bi bi-bar-chart-fill text-success"></i> Status Kepegawaian</h5>
                                    <div class="chart-canvas-wrap">
                                        <canvas id="chartStatus"></canvas>
                                    </div>
                                    <div class="mt-3 pt-2 border-top d-flex justify-content-around text-center">
                                        <div>
                                            <span class="d-block fw-bold text-primary"><?= $tot_pns ?></span>
                                            <small class="text-muted">PNS</small>
                                        </div>
                                        <div class="vr"></div>
                                        <div>
                                            <span class="d-block fw-bold" style="color: #9333ea;"><?= $tot_pppk ?></span>
                                            <small class="text-muted">PPPK</small>
                                        </div>
                                        <div class="vr"></div>
                                        <div>
                                            <span class="d-block fw-bold text-warning"><?= $tot_non_asn ?></span>
                                            <small class="text-muted">Non-ASN</small>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Chart 3: Distribusi Gender -->
                            <div class="col-md-4">
                                <div class="ptk-chart-box">
                                    <h5><i class="bi bi-gender-ambiguous text-warning"></i> Proporsi Gender</h5>
                                    <div class="chart-canvas-wrap">
                                        <canvas id="chartGender"></canvas>
                                    </div>
                                    <div class="mt-3 pt-2 border-top d-flex justify-content-around text-center">
                                        <div>
                                            <span class="d-block fw-bold text-info" style="font-size: 1.1rem;"><?= $tot_l ?></span>
                                            <small class="text-muted">Laki-laki (<?= $pct_l ?>%)</small>
                                        </div>
                                        <div class="vr"></div>
                                        <div>
                                            <span class="d-block fw-bold text-danger" style="font-size: 1.1rem;"><?= $tot_p ?></span>
                                            <small class="text-muted">Perempuan (<?= $pct_p ?>%)</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- ═══ TAB 2: TABEL RINCIAN REKAPITULASI DATA PTK ═══ -->
                    <div class="tab-pane fade" id="tab-tabel" role="tabpanel">
                        <div class="table-responsive table-matrix-ptk">
                            <table class="table table-hover table-bordered mb-0 align-middle">
                                <thead>
                                    <tr>
                                        <th rowspan="2" class="align-middle text-center" style="width: 50px;">No</th>
                                        <th rowspan="2" class="align-middle text-start" style="min-width: 180px;">Kategori PTK</th>
                                        <th colspan="3" class="text-center" style="background: #eff6ff; color: #1e40af;">PNS</th>
                                        <th colspan="3" class="text-center" style="background: #faf5ff; color: #6b21a8;">PPPK</th>
                                        <th colspan="3" class="text-center" style="background: #fff7ed; color: #9a3412;">Non-ASN / Honorer</th>
                                        <th colspan="3" class="text-center" style="background: #ecfdf5; color: #065f46;">Total Keseluruhan</th>
                                    </tr>
                                    <tr>
                                        <th class="text-center" style="background: #eff6ff;">L</th>
                                        <th class="text-center" style="background: #eff6ff;">P</th>
                                        <th class="text-center" style="background: #dbeafe; font-weight: 800;">Jml</th>
                                        <th class="text-center" style="background: #faf5ff;">L</th>
                                        <th class="text-center" style="background: #faf5ff;">P</th>
                                        <th class="text-center" style="background: #f3e8ff; font-weight: 800;">Jml</th>
                                        <th class="text-center" style="background: #fff7ed;">L</th>
                                        <th class="text-center" style="background: #fff7ed;">P</th>
                                        <th class="text-center" style="background: #ffedd5; font-weight: 800;">Jml</th>
                                        <th class="text-center" style="background: #ecfdf5;">L</th>
                                        <th class="text-center" style="background: #ecfdf5;">P</th>
                                        <th class="text-center" style="background: #d1fae5; font-weight: 800;">Total</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <!-- Baris 1: Tenaga Pendidik (Guru) -->
                                    <tr>
                                        <td class="text-center fw-bold text-muted">1</td>
                                        <td>
                                            <div class="d-flex align-items-center gap-2">
                                                <div class="rounded-circle p-2 bg-primary-subtle text-primary" style="background: #eff6ff;">
                                                    <i class="bi bi-mortarboard-fill"></i>
                                                </div>
                                                <div>
                                                    <strong class="text-dark d-block">Tenaga Pendidik (Guru)</strong>
                                                    <small class="text-muted">Guru Mata Pelajaran &amp; Pembina</small>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="text-center"><?= (int)($m_pend['pns_l'] ?? 0) ?></td>
                                        <td class="text-center"><?= (int)($m_pend['pns_p'] ?? 0) ?></td>
                                        <td class="text-center fw-bold"><span class="badge-count b-pns"><?= (int)($m_pend['pns_total'] ?? 0) ?></span></td>
                                        <td class="text-center"><?= (int)($m_pend['pppk_l'] ?? 0) ?></td>
                                        <td class="text-center"><?= (int)($m_pend['pppk_p'] ?? 0) ?></td>
                                        <td class="text-center fw-bold"><span class="badge-count b-pppk"><?= (int)($m_pend['pppk_total'] ?? 0) ?></span></td>
                                        <td class="text-center"><?= (int)($m_pend['non_asn_l'] ?? 0) ?></td>
                                        <td class="text-center"><?= (int)($m_pend['non_asn_p'] ?? 0) ?></td>
                                        <td class="text-center fw-bold"><span class="badge-count b-non"><?= (int)($m_pend['non_asn_total'] ?? 0) ?></span></td>
                                        <td class="text-center fw-bold"><?= (int)($m_pend['total_l'] ?? 0) ?></td>
                                        <td class="text-center fw-bold"><?= (int)($m_pend['total_p'] ?? 0) ?></td>
                                        <td class="text-center fw-bold"><span class="badge-count b-tot"><?= (int)($m_pend['total'] ?? 0) ?></span></td>
                                    </tr>

                                    <!-- Baris 2: Tenaga Kependidikan (TU) -->
                                    <tr>
                                        <td class="text-center fw-bold text-muted">2</td>
                                        <td>
                                            <div class="d-flex align-items-center gap-2">
                                                <div class="rounded-circle p-2 bg-purple-subtle text-purple" style="background: #faf5ff; color: #9333ea;">
                                                    <i class="bi bi-building-fill-gear"></i>
                                                </div>
                                                <div>
                                                    <strong class="text-dark d-block">Tenaga Kependidikan</strong>
                                                    <small class="text-muted">Tata Usaha, Laboran, Staf &amp; Satpam</small>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="text-center"><?= (int)($m_kep['pns_l'] ?? 0) ?></td>
                                        <td class="text-center"><?= (int)($m_kep['pns_p'] ?? 0) ?></td>
                                        <td class="text-center fw-bold"><span class="badge-count b-pns"><?= (int)($m_kep['pns_total'] ?? 0) ?></span></td>
                                        <td class="text-center"><?= (int)($m_kep['pppk_l'] ?? 0) ?></td>
                                        <td class="text-center"><?= (int)($m_kep['pppk_p'] ?? 0) ?></td>
                                        <td class="text-center fw-bold"><span class="badge-count b-pppk"><?= (int)($m_kep['pppk_total'] ?? 0) ?></span></td>
                                        <td class="text-center"><?= (int)($m_kep['non_asn_l'] ?? 0) ?></td>
                                        <td class="text-center"><?= (int)($m_kep['non_asn_p'] ?? 0) ?></td>
                                        <td class="text-center fw-bold"><span class="badge-count b-non"><?= (int)($m_kep['non_asn_total'] ?? 0) ?></span></td>
                                        <td class="text-center fw-bold"><?= (int)($m_kep['total_l'] ?? 0) ?></td>
                                        <td class="text-center fw-bold"><?= (int)($m_kep['total_p'] ?? 0) ?></td>
                                        <td class="text-center fw-bold"><span class="badge-count b-tot"><?= (int)($m_kep['total'] ?? 0) ?></span></td>
                                    </tr>
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <td colspan="2" class="text-center text-uppercase fw-bold">Jumlah Total Keseluruhan</td>
                                        <td class="text-center"><?= (int)($m_pend['pns_l'] ?? 0) + (int)($m_kep['pns_l'] ?? 0) ?></td>
                                        <td class="text-center"><?= (int)($m_pend['pns_p'] ?? 0) + (int)($m_kep['pns_p'] ?? 0) ?></td>
                                        <td class="text-center fw-bold"><span class="badge-count b-pns"><?= $tot_pns ?></span></td>
                                        <td class="text-center"><?= (int)($m_pend['pppk_l'] ?? 0) + (int)($m_kep['pppk_l'] ?? 0) ?></td>
                                        <td class="text-center"><?= (int)($m_pend['pppk_p'] ?? 0) + (int)($m_kep['pppk_p'] ?? 0) ?></td>
                                        <td class="text-center fw-bold"><span class="badge-count b-pppk"><?= $tot_pppk ?></span></td>
                                        <td class="text-center"><?= (int)($m_pend['non_asn_l'] ?? 0) + (int)($m_kep['non_asn_l'] ?? 0) ?></td>
                                        <td class="text-center"><?= (int)($m_pend['non_asn_p'] ?? 0) + (int)($m_kep['non_asn_p'] ?? 0) ?></td>
                                        <td class="text-center fw-bold"><span class="badge-count b-non"><?= $tot_non_asn ?></span></td>
                                        <td class="text-center fw-bold"><?= $tot_l ?></td>
                                        <td class="text-center fw-bold"><?= $tot_p ?></td>
                                        <td class="text-center fw-bold"><span class="badge-count b-tot" style="font-size: 1rem; padding: 6px 14px; background: #059669; color: #ffffff;"><?= $tot_ptk ?></span></td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>

                </div>
            </div>
        </div>

        <!-- ═══ 3. FILTER & SEARCH DIREKTORI PTK ═══ -->
        <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-3">
            <div>
                <h3 class="fw-bold mb-1" style="font-size: 1.45rem; color: #0f172a;">
                    <i class="bi bi-person-lines-fill me-2 text-success"></i> Direktori Resmi PTK
                </h3>
                <p class="text-muted mb-0" style="font-size: 0.9rem;">Cari dan temukan informasi tenaga pendidik serta kependidikan terdaftar.</p>
            </div>
            <div class="text-muted fw-semibold" style="font-size: 0.88rem;">
                Menampilkan <strong><?= count($ptk ?? []) ?></strong> dari <strong><?= (int)$total_rows ?></strong> PTK
            </div>
        </div>

        <div class="ptk-filter-panel mb-4">
            <form method="get" action="<?= base_url('website/ptk') ?>" class="row g-3 align-items-center">
                <div class="col-md-6 col-lg-7">
                    <div class="input-group">
                        <span class="input-group-text bg-white border-end-0 text-muted"><i class="bi bi-search"></i></span>
                        <input type="text"
                               name="q"
                               class="form-control border-start-0 ps-0"
                               value="<?= ptk_clean($q ?? '') ?>"
                               placeholder="Ketik nama PTK, jabatan, atau mata pelajaran...">
                    </div>
                </div>

                <div class="col-md-3 col-lg-3">
                    <select name="jenis" class="form-select">
                        <option value="">Semua Kategori (Pendidik &amp; TU)</option>
                        <option value="Pendidik" <?= ($jenis ?? '') == 'Pendidik' ? 'selected' : '' ?>>Tenaga Pendidik (Guru)</option>
                        <option value="Kependidikan" <?= ($jenis ?? '') == 'Kependidikan' ? 'selected' : '' ?>>Tenaga Kependidikan (TU)</option>
                    </select>
                </div>

                <div class="col-md-3 col-lg-2 d-flex gap-2">
                    <button class="btn btn-success fw-bold w-100"><i class="bi bi-funnel-fill me-1"></i> Filter</button>
                    <?php if(!empty($q) || !empty($jenis)): ?>
                        <a href="<?= base_url('website/ptk') ?>" class="btn btn-outline-secondary" title="Reset Filter"><i class="bi bi-arrow-counterclockwise"></i></a>
                    <?php endif; ?>
                </div>
            </form>
        </div>

        <!-- ═══ 4. GRID KARTU PTK ═══ -->
        <?php if(!empty($ptk)): ?>
            <div class="web-ptk-grid mb-5">
                <?php foreach($ptk as $p): ?>
                    <?php
                    $foto_file = !empty($p->foto) ? FCPATH.'uploads/ptk/foto/'.$p->foto : '';
                    $has_foto = !empty($p->foto) && file_exists($foto_file);
                    $is_pendidik = ($p->jenis_ptk == 'Pendidik');

                    // 1. Jabatan
                    $jabatan_raw = trim((string)($p->jabatan ?? ''));
                    if(!empty($jabatan_raw) && !in_array(strtoupper($jabatan_raw), ['GTT', 'PTT', '-'])){
                        $jabatan_clean = $jabatan_raw;
                    } else {
                        if(!empty($p->tugas_utama)){
                            $jabatan_clean = $p->tugas_utama;
                        } elseif($is_pendidik){
                            $jabatan_clean = 'Guru Pengajar';
                        } else {
                            $jabatan_clean = 'Staf Tenaga Kependidikan';
                        }
                    }

                    // 2. Pangkat / Golongan
                    $golongan_raw = trim((string)($p->pangkat_golongan ?? ''));
                    if(!empty($golongan_raw) && $golongan_raw != '-'){
                        $golongan_clean = $golongan_raw;
                    } else {
                        $sk = trim((string)($p->status_kepegawaian ?? ''));
                        if(!empty($sk)){
                            $golongan_clean = $sk;
                        } else {
                            $golongan_clean = $is_pendidik ? 'Tenaga Pendidik' : 'Staf Kependidikan';
                        }
                    }
                    ?>

                    <div class="web-ptk-card">
                        <div class="web-ptk-img-wrap" style="<?= $is_pendidik ? '' : 'background: linear-gradient(135deg, #3b82f6, #1e40af);' ?>">
                            <?php if($has_foto): ?>
                                <img src="<?= base_url('uploads/ptk/foto/'.$p->foto) ?>"
                                     alt="<?= ptk_clean($p->nama_lengkap ?? 'PTK') ?>"
                                     loading="lazy">
                            <?php else: ?>
                                <div class="web-ptk-avatar-fallback <?= $is_pendidik ? '' : 'avatar-kependidikan' ?>">
                                    <?= !empty($p->nama_lengkap) ? strtoupper(substr($p->nama_lengkap,0,1)) : 'P' ?>
                                </div>
                            <?php endif; ?>
                        </div>

                        <h5 class="web-ptk-name" title="<?= ptk_clean($p->nama_lengkap ?? '-') ?>">
                            <?= ptk_clean($p->nama_lengkap ?? '-') ?>
                        </h5>

                        <!-- NIP atau Status Kepegawaian -->
                        <div class="web-ptk-nip-wrap">
                            <?php if(!empty($p->nip)): ?>
                                <span class="web-ptk-nip-badge">
                                    NIP. <?= ptk_clean($p->nip) ?>
                                </span>
                            <?php else: ?>
                                <span class="web-ptk-status-badge">
                                    Status: <?= ptk_clean(!empty($p->status_kepegawaian) ? $p->status_kepegawaian : 'Non-PNS') ?>
                                </span>
                            <?php endif; ?>
                        </div>

                        <!-- Jabatan & Golongan (Di Bawah NIP Sesuai Permintaan) -->
                        <div class="web-ptk-info-group">
                            <div class="web-ptk-jabatan" title="<?= ptk_clean($jabatan_clean) ?>">
                                <?= ptk_clean($jabatan_clean) ?>
                            </div>
                            <div class="web-ptk-golongan" title="<?= ptk_clean($golongan_clean) ?>">
                                <i class="bi bi-award me-1 text-primary"></i><?= ptk_clean($golongan_clean) ?>
                            </div>
                        </div>

                        <!-- Badge Kategori PTK -->
                        <span class="web-ptk-badge-role <?= $is_pendidik ? 'role-pendidik' : 'role-kependidikan' ?>">
                            <i class="bi <?= $is_pendidik ? 'bi-mortarboard-fill' : 'bi-building-fill-gear' ?> me-1"></i>
                            <?= $is_pendidik ? 'Tenaga Pendidik' : 'Tenaga Kependidikan' ?>
                        </span>
                    </div>
                <?php endforeach; ?>
            </div>

            <!-- Pagination -->
            <?php if(!empty($pagination)): ?>
                <div class="d-flex justify-content-center mt-4">
                    <?= $pagination ?>
                </div>
            <?php endif; ?>

        <?php else: ?>
            <div class="card border-0 shadow-sm rounded-4 p-5 text-center my-4 bg-white">
                <i class="bi bi-search text-muted mb-3" style="font-size: 3rem;"></i>
                <h5 class="fw-bold text-dark">Data PTK Tidak Ditemukan</h5>
                <p class="text-muted mb-3">Tidak ada data PTK yang sesuai dengan kata kunci pencarian atau filter yang dipilih.</p>
                <div>
                    <a href="<?= base_url('website/ptk') ?>" class="btn btn-outline-success btn-sm px-3">
                        <i class="bi bi-arrow-counterclockwise me-1"></i> Reset Pencarian
                    </a>
                </div>
            </div>
        <?php endif; ?>

    </div>
</section>

<!-- ═══ CHART.JS INITIALIZATION SCRIPT ═══ -->
<script>
document.addEventListener("DOMContentLoaded", function () {
    // 1. Chart Kategori PTK (Donut)
    const ctxKategori = document.getElementById('chartKategori');
    if (ctxKategori) {
        new Chart(ctxKategori, {
            type: 'doughnut',
            data: {
                labels: ['Tenaga Pendidik (Guru)', 'Tenaga Kependidikan (TU)'],
                datasets: [{
                    data: [<?= $tot_pendidik ?>, <?= $tot_kependidikan ?>],
                    backgroundColor: ['#2563eb', '#8b5cf6'],
                    borderWidth: 2,
                    borderColor: '#ffffff',
                    hoverOffset: 6
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            boxWidth: 12,
                            font: { size: 11, weight: '600' },
                            padding: 14
                        }
                    }
                },
                cutout: '68%'
            }
        });
    }

    // 2. Chart Status Kepegawaian (Bar Chart)
    const ctxStatus = document.getElementById('chartStatus');
    if (ctxStatus) {
        new Chart(ctxStatus, {
            type: 'bar',
            data: {
                labels: ['PNS', 'PPPK', 'Non-ASN'],
                datasets: [
                    {
                        label: 'Pendidik',
                        data: [<?= (int)($m_pend['pns_total'] ?? 0) ?>, <?= (int)($m_pend['pppk_total'] ?? 0) ?>, <?= (int)($m_pend['non_asn_total'] ?? 0) ?>],
                        backgroundColor: '#2563eb',
                        borderRadius: 6
                    },
                    {
                        label: 'Kependidikan',
                        data: [<?= (int)($m_kep['pns_total'] ?? 0) ?>, <?= (int)($m_kep['pppk_total'] ?? 0) ?>, <?= (int)($m_kep['non_asn_total'] ?? 0) ?>],
                        backgroundColor: '#8b5cf6',
                        borderRadius: 6
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            boxWidth: 12,
                            font: { size: 11, weight: '600' },
                            padding: 14
                        }
                    }
                },
                scales: {
                    x: { grid: { display: false } },
                    y: { 
                        beginAtZero: true,
                        ticks: { stepSize: 5 }
                    }
                }
            }
        });
    }

    // 3. Chart Gender (Pie / Donut)
    const ctxGender = document.getElementById('chartGender');
    if (ctxGender) {
        new Chart(ctxGender, {
            type: 'pie',
            data: {
                labels: ['Laki-laki (<?= $pct_l ?>%)', 'Perempuan (<?= $pct_p ?>%)'],
                datasets: [{
                    data: [<?= $tot_l ?>, <?= $tot_p ?>],
                    backgroundColor: ['#0ea5e9', '#ec4899'],
                    borderWidth: 2,
                    borderColor: '#ffffff',
                    hoverOffset: 6
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            boxWidth: 12,
                            font: { size: 11, weight: '600' },
                            padding: 14
                        }
                    }
                }
            }
        });
    }
});
</script>

<?php $this->load->view('public/partials/archive_footer'); ?>