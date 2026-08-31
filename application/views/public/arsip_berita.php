<?php $this->load->view('public/partials/archive_header'); ?>

<?php
if(!function_exists('arsip_clean')){
    function arsip_clean($text){
        return htmlspecialchars((string)$text, ENT_QUOTES, 'UTF-8');
    }
}

if(!function_exists('arsip_limit')){
    function arsip_limit($text, $limit = 120){
        $text = trim(strip_tags((string)$text));
        if(function_exists('mb_strimwidth')){
            return mb_strimwidth($text, 0, $limit, '...');
        }
        return strlen($text) > $limit ? substr($text, 0, $limit).'...' : $text;
    }
}

$kategori_options = isset($kategori_options) ? $kategori_options : ['Prestasi','Kegiatan','Pengumuman','PPDB','Akademik','Keagamaan','Ekstrakurikuler'];
$kategori = $kategori ?? '';
?>

<!-- ═══ HERO ARSIP ═══ -->
<header class="web-archive-hero">
    <div class="container">
        <div class="detail-breadcrumb">
            <a href="<?= base_url() ?>"><i class="bi bi-house-door"></i> Beranda</a>
            <span>/</span>
            <strong>Berita Terkini</strong>
        </div>
        <h1>Portal Berita Madrasah</h1>
        <p>Informasi terbaru seputar kegiatan, prestasi, dan pengumuman dari <?= arsip_clean($nama_madrasah ?? 'MAN 3 Banjar') ?></p>
    </div>
</header>

<section class="web-section">
    <div class="container">

        <!-- ═══ FILTER ═══ -->
        <form method="get" action="<?= base_url('website/berita') ?>" class="web-archive-filter">
            <div class="web-filter-grid">
                <input type="text"
                       name="q"
                       value="<?= arsip_clean($q ?? '') ?>"
                       placeholder="🔍 Cari judul atau isi berita...">

                <select name="kategori" class="web-filter-select">
                    <option value="">Semua Kategori</option>
                    <?php foreach($kategori_options as $opt): ?>
                        <option value="<?= arsip_clean($opt) ?>" <?= $kategori == $opt ? 'selected' : '' ?>><?= arsip_clean($opt) ?></option>
                    <?php endforeach; ?>
                </select>

                <input type="month"
                       name="bulan"
                       value="<?= arsip_clean($bulan ?? '') ?>"
                       title="Filter berdasarkan bulan">

                <button type="submit"><i class="bi bi-search"></i> Cari</button>

                <?php if(!empty($q) || !empty($kategori) || !empty($bulan)): ?>
                    <a href="<?= base_url('website/berita') ?>"><i class="bi bi-x-lg"></i> Reset</a>
                <?php endif; ?>
            </div>
        </form>

        <div class="web-archive-result-info">
            <i class="bi bi-info-circle"></i> Menampilkan data halaman <?= (int)$current_page ?> dari total <?= (int)$total_rows ?> berita<?= !empty($kategori) ? ' untuk kategori <strong>'.arsip_clean($kategori).'</strong>' : '' ?>.
        </div>

        <div class="web-portal-layout reveal">
            <!-- ═══ MAIN CONTENT (75%) ═══ -->
            <div class="web-portal-main">

                <?php if(!empty($berita)): ?>

                    <!-- ═══ FEATURED NEWS (Hanya di Halaman 1) ═══ -->
                    <?php if((int)$current_page === 1 && empty($q) && empty($bulan) && empty($kategori) && count($berita) >= 1): ?>
                        <div class="web-portal-featured">
                            
                            <!-- Main Featured (1st Item) -->
                            <?php
                            $f_main = $berita[0];
                            $fm_judul = arsip_clean($f_main->judul ?? '');
                            $fm_kat = arsip_clean($f_main->kategori ?? 'Umum');
                            $fm_tgl = !empty($f_main->published_at) ? $f_main->published_at : (!empty($f_main->created_at) ? $f_main->created_at : date('Y-m-d H:i:s'));
                            $fm_gbr = $f_main->gambar ?? '';
                            $fm_file = !empty($fm_gbr) ? FCPATH.'assets/news/'.$fm_gbr : '';
                            $fm_key = !empty($f_main->slug) ? $f_main->slug : $f_main->id;
                            $fm_view = isset($f_main->view_count) ? (int)$f_main->view_count : 0;
                            ?>
                            <div class="web-portal-featured-main">
                                <a href="<?= base_url('berita/detail/'.$fm_key) ?>" class="web-portal-card" style="text-decoration:none; display:block;">
                                    <?php if(!empty($fm_gbr) && file_exists($fm_file)): ?>
                                        <img src="<?= base_url('assets/news/'.$fm_gbr) ?>" alt="<?= $fm_judul ?>">
                                    <?php else: ?>
                                        <div class="web-news-placeholder" style="width:100%; height:400px; border-radius:12px;"><i class="bi bi-newspaper"></i></div>
                                    <?php endif; ?>
                                    
                                    <div style="padding: 16px 0 0 0;">
                                        <div class="web-news-meta-row" style="margin-bottom: 8px;">
                                            <em style="color:var(--c-emerald-600); background:var(--c-emerald-50); border:1px solid var(--c-emerald-100);"><i class="bi bi-tag"></i> <?= $fm_kat ?></em>
                                        </div>
                                        <h3 style="font-size: 24px; font-weight: 800; color: var(--c-slate-900); margin-bottom: 12px; line-height: 1.4;"><?= $fm_judul ?></h3>
                                        <p style="font-size: 15px; color: var(--c-slate-600); margin-bottom: 16px;"><?= arsip_limit($f_main->isi ?? '', 160) ?></p>
                                        <div style="display:flex; gap:16px; color:var(--c-slate-500); font-size:13px;">
                                            <span><i class="bi bi-calendar3"></i> <?= date('d M Y, H:i', strtotime($fm_tgl)) ?></span>
                                            <span><i class="bi bi-eye"></i> <?= number_format($fm_view, 0, ',', '.') ?> tayangan</span>
                                        </div>
                                    </div>
                                </a>
                            </div>

                            <!-- Secondary Featured (2nd & 3rd Item) -->
                            <?php if(count($berita) >= 2): ?>
                            <div class="web-portal-featured-sub">
                                <?php foreach(array_slice($berita, 1, 2) as $fs): ?>
                                    <?php
                                    $fs_judul = arsip_clean($fs->judul ?? '');
                                    $fs_kat = arsip_clean($fs->kategori ?? 'Umum');
                                    $fs_tgl = !empty($fs->published_at) ? $fs->published_at : (!empty($fs->created_at) ? $fs->created_at : date('Y-m-d H:i:s'));
                                    $fs_gbr = $fs->gambar ?? '';
                                    $fs_file = !empty($fs_gbr) ? FCPATH.'assets/news/'.$fs_gbr : '';
                                    $fs_key = !empty($fs->slug) ? $fs->slug : $fs->id;
                                    $fs_view = isset($fs->view_count) ? (int)$fs->view_count : 0;
                                    ?>
                                    <a href="<?= base_url('berita/detail/'.$fs_key) ?>" class="web-portal-card" style="text-decoration:none; display:block;">
                                        <?php if(!empty($fs_gbr) && file_exists($fs_file)): ?>
                                            <img src="<?= base_url('assets/news/'.$fs_gbr) ?>" alt="<?= $fs_judul ?>">
                                        <?php else: ?>
                                            <div class="web-news-placeholder" style="width:100%; height:200px; border-radius:12px;"><i class="bi bi-newspaper"></i></div>
                                        <?php endif; ?>
                                        <div style="padding: 12px 0 0 0;">
                                            <div class="web-news-meta-row" style="margin-bottom: 6px;">
                                                <em style="font-size:11px; color:var(--c-emerald-600); background:var(--c-emerald-50); border:1px solid var(--c-emerald-100);"><i class="bi bi-tag"></i> <?= $fs_kat ?></em>
                                            </div>
                                            <h3 style="font-size: 16px; font-weight: 700; color: var(--c-slate-900); margin-bottom: 8px; line-height: 1.4;"><?= arsip_limit($fs_judul, 80) ?></h3>
                                            <div style="display:flex; gap:12px; color:var(--c-slate-500); font-size:12px;">
                                                <span><i class="bi bi-calendar3"></i> <?= date('d M Y', strtotime($fs_tgl)) ?></span>
                                                <span><i class="bi bi-eye"></i> <?= number_format($fs_view, 0, ',', '.') ?></span>
                                            </div>
                                        </div>
                                    </a>
                                <?php endforeach; ?>
                            </div>
                            <?php endif; ?>

                            <div style="margin: 32px 0; border-top: 2px solid var(--c-slate-200);"></div>

                        </div>
                    <?php endif; ?>

                    <!-- ═══ LIST BERITA LAINNYA ═══ -->
                    <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom: 24px;">
                        <h2 style="font-size: 20px; font-weight: 800; color: var(--c-slate-800);">Berita Lainnya</h2>
                    </div>

                    <div class="web-list-news">
                        <?php
                        // Jika Halaman 1 & tidak ada filter, lewati 3 berita pertama (karena sudah jadi featured)
                        $start_idx = ((int)$current_page === 1 && empty($q) && empty($bulan) && empty($kategori)) ? 3 : 0;
                        $berita_list = array_slice($berita, $start_idx);
                        ?>

                        <?php foreach($berita_list as $b): ?>
                            <?php
                            $judul = arsip_clean($b->judul ?? '');
                            $isi = arsip_limit($b->isi ?? '', 140);
                            $gambar = $b->gambar ?? '';
                            $gambar_file = !empty($gambar) ? FCPATH.'assets/news/'.$gambar : '';
                            $kategori_item = arsip_clean($b->kategori ?? 'Berita');
                            $detail_key = !empty($b->slug) ? $b->slug : $b->id;
                            $view_count = isset($b->view_count) ? (int)$b->view_count : 0;
                            $tanggal = !empty($b->published_at) ? $b->published_at : (!empty($b->created_at) ? $b->created_at : date('Y-m-d H:i:s'));
                            ?>
                            <div class="web-list-card">
                                <a href="<?= base_url('berita/detail/'.$detail_key) ?>">
                                    <?php if(!empty($gambar) && file_exists($gambar_file)): ?>
                                        <img src="<?= base_url('assets/news/'.$gambar) ?>" alt="<?= $judul ?>">
                                    <?php else: ?>
                                        <div class="web-list-placeholder"><i class="bi bi-newspaper"></i></div>
                                    <?php endif; ?>
                                </a>
                                <div class="web-list-body">
                                    <div class="web-news-meta-row" style="margin-bottom: 8px;">
                                        <em style="font-size:11px; color:var(--c-emerald-600); background:var(--c-emerald-50); border:1px solid var(--c-emerald-100);"><i class="bi bi-tag"></i> <?= $kategori_item ?></em>
                                    </div>
                                    <a href="<?= base_url('berita/detail/'.$detail_key) ?>" style="text-decoration:none;">
                                        <h3><?= $judul ?></h3>
                                        <p><?= $isi ?></p>
                                    </a>
                                    <div style="margin-top:auto; display:flex; gap:16px; color:var(--c-slate-500); font-size:12px; font-weight:500;">
                                        <span><i class="bi bi-calendar3"></i> <?= date('d M Y, H:i', strtotime($tanggal)) ?></span>
                                        <span><i class="bi bi-eye"></i> <?= number_format($view_count, 0, ',', '.') ?> tayangan</span>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>

                    <?php if(!empty($pagination)): ?>
                        <div style="margin-top: 40px;">
                            <?= str_replace('<ul class="pagination">', '<ul class="pagination">', $pagination) ?>
                        </div>
                    <?php endif; ?>

                <?php else: ?>
                    <div class="web-empty">
                        <div style="font-size: 48px; margin-bottom: 16px; color: var(--c-slate-300);"><i class="bi bi-journal-x"></i></div>
                        <div>Tidak ada berita yang ditemukan dengan filter tersebut.</div>
                    </div>
                <?php endif; ?>

            </div>

            <!-- ═══ SIDEBAR (25%) ═══ -->
            <aside class="web-portal-sidebar">
                
                <!-- Widget: Kategori -->
                <div class="web-widget">
                    <h4><i class="bi bi-tags"></i> Kategori Berita</h4>
                    <div class="web-cat-list">
                        <?php foreach($kategori_options as $opt): ?>
                            <a href="<?= base_url('website/berita?kategori='.urlencode($opt)) ?>" class="<?= $kategori == $opt ? 'active' : '' ?>">
                                <?= arsip_clean($opt) ?>
                                <i class="bi bi-chevron-right" style="font-size: 10px;"></i>
                            </a>
                        <?php endforeach; ?>
                    </div>
                </div>

                <!-- Widget: Berita Populer -->
                <?php if(!empty($berita_populer)): ?>
                <div class="web-widget">
                    <h4><i class="bi bi-graph-up-arrow"></i> Berita Populer</h4>
                    <div class="web-pop-list">
                        <?php foreach($berita_populer as $pop): ?>
                            <?php
                            $p_judul = arsip_clean($pop->judul ?? '');
                            $p_gbr = $pop->gambar ?? '';
                            $p_file = !empty($p_gbr) ? FCPATH.'assets/news/'.$p_gbr : '';
                            $p_key = !empty($pop->slug) ? $pop->slug : $pop->id;
                            $p_view = isset($pop->view_count) ? (int)$pop->view_count : 0;
                            ?>
                            <a href="<?= base_url('berita/detail/'.$p_key) ?>" class="web-pop-item" title="<?= $p_judul ?>">
                                <?php if(!empty($p_gbr) && file_exists($p_file)): ?>
                                    <img src="<?= base_url('assets/news/'.$p_gbr) ?>" alt="Thumb">
                                <?php else: ?>
                                    <div style="width:80px; height:80px; border-radius:8px; background:var(--c-slate-100); display:flex; align-items:center; justify-content:center; color:var(--c-slate-400); flex-shrink:0;">
                                        <i class="bi bi-newspaper"></i>
                                    </div>
                                <?php endif; ?>
                                <div class="web-pop-body">
                                    <h5><?= arsip_limit($p_judul, 50) ?></h5>
                                    <small><i class="bi bi-eye"></i> <?= number_format($p_view, 0, ',', '.') ?> tayangan</small>
                                </div>
                            </a>
                        <?php endforeach; ?>
                    </div>
                </div>
                <?php endif; ?>

            </aside>
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