<?php $this->load->view('public/partials/archive_header'); ?>

<?php
$total = count($alumni);
$kuliah = 0;
$kerja = 0;
$wirausaha = 0;
$belum = 0;
$lainnya = 0;

foreach($alumni as $a) {
    $st = strtolower((string)$a->status_lanjut);
    if(strpos($st, 'kuliah') !== false) $kuliah++;
    elseif(strpos($st, 'kerja') !== false) $kerja++;
    elseif(strpos($st, 'wirausaha') !== false) $wirausaha++;
    elseif(strpos($st, 'belum') !== false || strpos($st, 'mencari') !== false) $belum++;
    else $lainnya++;
}

$p_kuliah = $total > 0 ? round(($kuliah / $total) * 100) : 0;
$p_kerja = $total > 0 ? round(($kerja / $total) * 100) : 0;
$p_wirausaha = $total > 0 ? round(($wirausaha / $total) * 100) : 0;
$p_belum = $total > 0 ? round(($belum / $total) * 100) : 0;
$p_lainnya = $total > 0 ? round(($lainnya / $total) * 100) : 0;
?>

<header class="web-archive-hero">
    <div class="container">
        <div class="detail-breadcrumb">
            <a href="<?= base_url() ?>"><i class="bi bi-house-door"></i> Beranda</a>
            <span>/</span>
            <strong>Alumni</strong>
        </div>
        <h1>Direktori Alumni</h1>
        <p>Temukan dan hubungkan kembali dengan para alumni <?= htmlspecialchars($nama_madrasah ?? 'MAN 3 Banjar', ENT_QUOTES, 'UTF-8') ?> yang telah berkiprah di berbagai bidang.</p>
    </div>
</header>

<section class="web-section" style="background: #f8fafc; padding: 60px 0;">
    <div class="container">
        
        <!-- Statistik Alumni Section (UX/UI Premium) -->
        <div class="row g-4 mb-5 reveal">
            <div class="col-lg-4">
                <div class="card border-0 h-100 shadow-sm" style="border-radius: 20px; background: linear-gradient(135deg, var(--c-emerald-700) 0%, var(--c-emerald-900) 100%); color: white;">
                    <div class="card-body p-4 p-md-5 d-flex flex-column justify-content-center">
                        <div style="width: 50px; height: 50px; border-radius: 12px; background: rgba(255,255,255,0.1); display: flex; align-items: center; justify-content: center; margin-bottom: 20px;">
                            <i class="bi bi-mortarboard-fill" style="font-size: 24px; color: white;"></i>
                        </div>
                        <h4 class="mb-2" style="font-weight: 600; opacity: 0.9;">Total Alumni Terdata</h4>
                        <h2 style="font-weight: 900; font-size: 3.5rem; margin: 0; line-height: 1;"><?= number_format($total, 0, ',', '.') ?></h2>
                        <p class="mb-0 mt-3" style="font-size: 14px; opacity: 0.8; font-weight: 500;"><i class="bi bi-info-circle me-1"></i> Data diperbarui secara dinamis berdasarkan kelulusan siswa.</p>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-8">
                <div class="card border-0 h-100 shadow-sm" style="border-radius: 20px; background: #ffffff;">
                    <div class="card-body p-4 p-md-5">
                        <h4 class="mb-4" style="font-weight: 800; color: #1e293b;"><i class="bi bi-graph-up text-success me-2"></i> Status Kelanjutan Pasca Lulus</h4>
                        
                        <div class="row g-4">
                            <div class="col-md-6">
                                <!-- Kuliah -->
                                <div class="mb-4">
                                    <div class="d-flex justify-content-between align-items-center mb-2" style="font-weight: 700; font-size: 14px; color: #334155;">
                                        <span><i class="bi bi-mortarboard me-2 text-primary"></i> Kuliah (Melanjutkan Studi)</span>
                                        <span><?= $kuliah ?> (<?= $p_kuliah ?>%)</span>
                                    </div>
                                    <div class="progress" style="height: 10px; border-radius: 999px; background-color: #f1f5f9;">
                                        <div class="progress-bar bg-primary" role="progressbar" style="width: <?= $p_kuliah ?>%; border-radius: 999px;" aria-valuenow="<?= $p_kuliah ?>" aria-valuemin="0" aria-valuemax="100"></div>
                                    </div>
                                </div>
                                
                                <!-- Kerja -->
                                <div class="mb-4">
                                    <div class="d-flex justify-content-between align-items-center mb-2" style="font-weight: 700; font-size: 14px; color: #334155;">
                                        <span><i class="bi bi-briefcase me-2 text-success"></i> Bekerja (Karyawan/PNS)</span>
                                        <span><?= $kerja ?> (<?= $p_kerja ?>%)</span>
                                    </div>
                                    <div class="progress" style="height: 10px; border-radius: 999px; background-color: #f1f5f9;">
                                        <div class="progress-bar bg-success" role="progressbar" style="width: <?= $p_kerja ?>%; border-radius: 999px;" aria-valuenow="<?= $p_kerja ?>" aria-valuemin="0" aria-valuemax="100"></div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <!-- Wirausaha -->
                                <div class="mb-4">
                                    <div class="d-flex justify-content-between align-items-center mb-2" style="font-weight: 700; font-size: 14px; color: #334155;">
                                        <span><i class="bi bi-shop me-2 text-warning"></i> Wirausaha (Mandiri)</span>
                                        <span><?= $wirausaha ?> (<?= $p_wirausaha ?>%)</span>
                                    </div>
                                    <div class="progress" style="height: 10px; border-radius: 999px; background-color: #f1f5f9;">
                                        <div class="progress-bar bg-warning" role="progressbar" style="width: <?= $p_wirausaha ?>%; border-radius: 999px;" aria-valuenow="<?= $p_wirausaha ?>" aria-valuemin="0" aria-valuemax="100"></div>
                                    </div>
                                </div>
                                
                                <!-- Lainnya / Belum Bekerja -->
                                <div class="mb-4">
                                    <div class="d-flex justify-content-between align-items-center mb-2" style="font-weight: 700; font-size: 14px; color: #334155;">
                                        <span><i class="bi bi-hourglass-split me-2 text-secondary"></i> Mencari Kerja / Lainnya</span>
                                        <span><?= ($belum + $lainnya) ?> (<?= ($p_belum + $p_lainnya) ?>%)</span>
                                    </div>
                                    <div class="progress" style="height: 10px; border-radius: 999px; background-color: #f1f5f9;">
                                        <div class="progress-bar bg-secondary" role="progressbar" style="width: <?= ($p_belum + $p_lainnya) ?>%; border-radius: 999px;" aria-valuenow="<?= ($p_belum + $p_lainnya) ?>" aria-valuemin="0" aria-valuemax="100"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filter & Table Card -->
        <div class="row justify-content-center reveal">
            <div class="col-lg-12">
                <div class="card border-0 shadow-lg" style="border-radius: 20px; overflow:hidden; background: #ffffff;">
                    <div class="card-body p-4 p-md-5">
                        
                        <!-- Filter Bar -->
                        <div style="background: #f8fafc; border-radius: 16px; padding: 20px; margin-bottom: 30px; border: 1px solid #e2e8f0;">
                            <form method="get" action="<?= base_url('website/alumni') ?>" class="row g-3 align-items-center justify-content-between">
                                <div class="col-md-6 col-lg-5">
                                    <h5 class="mb-0 fw-bold" style="color: #1e293b;"><i class="bi bi-search me-1 text-success"></i> Cari Alumni</h5>
                                    <p class="text-muted small mb-0 mt-1">Gunakan tabel di bawah atau filter berdasarkan tahun kelulusan.</p>
                                </div>
                                <div class="col-md-4 col-lg-4 d-flex gap-2 justify-content-end">
                                    <select name="tahun" class="form-select" style="border-radius: 12px; min-height: 44px; font-weight: 600;" onchange="this.form.submit()">
                                        <option value="">Semua Tahun Lulus</option>
                                        <?php foreach($list_tahun as $lt): ?>
                                            <option value="<?= htmlspecialchars($lt->tahun, ENT_QUOTES, 'UTF-8') ?>" <?= $tahun_pilihan == $lt->tahun ? 'selected' : '' ?>>
                                                Lulusan <?= htmlspecialchars($lt->tahun, ENT_QUOTES, 'UTF-8') ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                    <?php if(!empty($tahun_pilihan)): ?>
                                        <a href="<?= base_url('website/alumni') ?>" class="btn btn-outline-secondary" style="border-radius: 12px; display: inline-flex; align-items: center; justify-content: center; min-height: 44px; font-weight: 700;">
                                            <i class="bi bi-x-circle"></i>
                                        </a>
                                    <?php endif; ?>
                                </div>
                            </form>
                        </div>
                        
                        <div class="table-responsive">
                            <style>
                            .status-pill {
                                display: inline-flex;
                                padding: 6px 12px;
                                border-radius: 999px;
                                font-size: 11px;
                                font-weight: 800;
                            }
                            .sp-kuliah { background: #eff6ff; color: #1e40af; border: 1px solid #bfdbfe; }
                            .sp-kerja { background: #ecfdf5; color: #065f46; border: 1px solid #a7f3d0; }
                            .sp-wirausaha { background: #fff7ed; color: #9a3412; border: 1px solid #fed7aa; }
                            .sp-belum { background: #fef2f2; color: #991b1b; border: 1px solid #fecaca; }
                            .sp-lainnya { background: #f1f5f9; color: #475569; border: 1px solid #e2e8f0; }
                            </style>
                            
                            <table class="table table-hover align-middle" id="alumniTable" style="width: 100%;">
                                <thead style="background: #f1f5f9; border-bottom: 2px solid #e2e8f0;">
                                    <tr>
                                        <th style="width: 60px; color: #475569; font-weight: 700; padding: 15px; border: 0; border-top-left-radius: 12px; border-bottom-left-radius: 12px;">No</th>
                                        <th style="color: #475569; font-weight: 700; padding: 15px; border: 0;">Nama Alumni</th>
                                        <th style="color: #475569; font-weight: 700; padding: 15px; border: 0;">Jenis Kelamin</th>
                                        <th style="color: #475569; font-weight: 700; padding: 15px; border: 0;">Tahun Lulus</th>
                                        <th style="color: #475569; font-weight: 700; padding: 15px; border: 0;">Status Setelah Lulus</th>
                                        <th style="color: #475569; font-weight: 700; padding: 15px; border: 0; border-top-right-radius: 12px; border-bottom-right-radius: 12px;">Keterangan / Instansi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if(empty($alumni)): ?>
                                        <tr>
                                            <td colspan="6" class="text-center" style="padding: 40px; color: #64748b;">
                                                <i class="bi bi-people" style="font-size: 40px; color: #cbd5e1; display: block; margin-bottom: 15px;"></i>
                                                Tidak ada data alumni yang ditemukan.
                                            </td>
                                        </tr>
                                    <?php else: ?>
                                        <?php $no=1; foreach($alumni as $a): ?>
                                            <?php 
                                            $status_lanjut = $a->status_lanjut ?? 'Belum Diisi';
                                            $pill_class = 'sp-lainnya';
                                            $st_lower = strtolower($status_lanjut);
                                            
                                            if (strpos($st_lower, 'kuliah') !== false) {
                                                $pill_class = 'sp-kuliah';
                                            } elseif (strpos($st_lower, 'kerja') !== false) {
                                                $pill_class = 'sp-kerja';
                                            } elseif (strpos($st_lower, 'wirausaha') !== false) {
                                                $pill_class = 'sp-wirausaha';
                                            } elseif (strpos($st_lower, 'belum') !== false || strpos($st_lower, 'mencari') !== false) {
                                                $pill_class = 'sp-belum';
                                            }
                                            ?>
                                            <tr style="border-bottom: 1px solid #f1f5f9;">
                                                <td style="padding: 18px 15px; font-weight: 600; color: #64748b;"><?= $no++ ?></td>
                                                <td style="padding: 18px 15px; font-weight: 750; color: #1e293b; font-size: 15px;">
                                                    <?= htmlspecialchars($a->nama_lengkap, ENT_QUOTES, 'UTF-8') ?>
                                                </td>
                                                <td style="padding: 18px 15px; color: #475569; font-size: 14px;">
                                                    <?= $a->jk == 'L' ? 'Laki-laki' : 'Perempuan' ?>
                                                </td>
                                                <td style="padding: 18px 15px; color: #10b981; font-weight: 700; font-size: 14px;">
                                                    Lulusan <?= htmlspecialchars($a->tahun_ajaran_lulus, ENT_QUOTES, 'UTF-8') ?>
                                                </td>
                                                <td style="padding: 18px 15px;">
                                                    <span class="status-pill <?= $pill_class ?>"><?= $status_lanjut ?></span>
                                                </td>
                                                <td style="padding: 18px 15px; color: #475569; font-size: 14px; font-weight: 600;">
                                                    <?= !empty($a->keterangan) ? htmlspecialchars($a->keterangan, ENT_QUOTES, 'UTF-8') : '<em class="text-muted" style="font-size: 12px; font-weight: 500;">Belum ada keterangan</em>' ?>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const reveals = document.querySelectorAll('.reveal');
    if('IntersectionObserver' in window) {
        const observer = new IntersectionObserver(function(entries) {
            entries.forEach(function(entry) {
                if(entry.isIntersecting) { entry.target.classList.add('visible'); observer.unobserve(entry.target); }
            });
        }, { threshold: 0.1 });
        reveals.forEach(function(el) { observer.observe(el); });
    } else {
        reveals.forEach(function(el) { el.classList.add('visible'); });
    }
});
</script>

<?php $this->load->view('public/partials/archive_footer'); ?>
