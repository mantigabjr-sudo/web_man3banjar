<?php
$nama_madrasah = 'MAN 3 Banjar';

if(!empty($profil_website->nama_madrasah)){
    $nama_madrasah = $profil_website->nama_madrasah;
}

$logo_file = FCPATH.'assets/img/logo-madrasah.png';
$logo_url  = base_url('assets/img/logo-madrasah.png');

if(!function_exists('detail_clean')){
    function detail_clean($text){
        return htmlspecialchars((string)$text, ENT_QUOTES, 'UTF-8');
    }
}

if(!function_exists('detail_limit')){
    function detail_limit($text, $limit = 110){
        $text = trim(strip_tags((string)$text));
        $text = preg_replace('/\s+/', ' ', $text);
        if(function_exists('mb_strimwidth')){
            return mb_strimwidth($text, 0, $limit, '...');
        }
        return strlen($text) > $limit ? substr($text, 0, $limit).'...' : $text;
    }
}

$judul_raw = $berita->judul ?? 'Detail Berita';
$judul = detail_clean($judul_raw);
$isi_plain = trim(preg_replace('/\s+/', ' ', strip_tags($berita->isi ?? '')));
$deskripsi = detail_limit($isi_plain, 160);
$deskripsi_clean = detail_clean($deskripsi);

$gambar = $berita->gambar ?? '';
$gambar_file = !empty($gambar) ? FCPATH.'assets/news/'.$gambar : '';
$gambar_url = (!empty($gambar) && file_exists($gambar_file))
    ? base_url('assets/news/'.$gambar)
    : $logo_url;

$tanggal = !empty($berita->published_at)
    ? $berita->published_at
    : (!empty($berita->created_at) ? $berita->created_at : date('Y-m-d H:i:s'));

$updated_at = !empty($berita->updated_at) ? $berita->updated_at : $tanggal;
$canonical_url = base_url('berita/detail/'.($berita->id ?? ''));
$site_name = $nama_madrasah.' - Portal Digital Madrasah';
$share_text = rawurlencode($judul_raw.' - '.$canonical_url);

$kategori = !empty($berita->kategori) ? $berita->kategori : 'Berita Madrasah';
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title><?= $judul ?> | <?= detail_clean($nama_madrasah) ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="<?= $deskripsi_clean ?>">
    <meta name="robots" content="index,follow,max-image-preview:large">
    <link rel="canonical" href="<?= detail_clean($canonical_url) ?>">

    <!-- SEO Meta Tags -->
    <meta property="og:type" content="article">
    <meta property="og:site_name" content="<?= detail_clean($site_name) ?>">
    <meta property="og:title" content="<?= $judul ?>">
    <meta property="og:description" content="<?= $deskripsi_clean ?>">
    <meta property="og:url" content="<?= detail_clean($canonical_url) ?>">
    <meta property="og:image" content="<?= detail_clean($gambar_url) ?>">
    <meta property="og:image:secure_url" content="<?= detail_clean($gambar_url) ?>">
    <meta property="og:image:alt" content="<?= $judul ?>">
    <meta property="article:published_time" content="<?= date('c', strtotime($tanggal)) ?>">
    <meta property="article:modified_time" content="<?= date('c', strtotime($updated_at)) ?>">

    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="<?= $judul ?>">
    <meta name="twitter:description" content="<?= $deskripsi_clean ?>">
    <meta name="twitter:image" content="<?= detail_clean($gambar_url) ?>">

    <link rel="icon" type="image/png" href="<?= base_url('assets/img/favicon.png') ?>">
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    
    <!-- Gunakan design system utama -->
    <link rel="stylesheet" href="<?= base_url('assets/css/website-home.css?v=21') ?>">

    <script type="application/ld+json">
    <?= json_encode([
        '@context' => 'https://schema.org',
        '@type' => 'NewsArticle',
        'headline' => $judul_raw,
        'description' => $deskripsi,
        'image' => [$gambar_url],
        'datePublished' => date('c', strtotime($tanggal)),
        'dateModified' => date('c', strtotime($updated_at)),
        'author' => [
            '@type' => 'Organization',
            'name' => $nama_madrasah
        ],
        'publisher' => [
            '@type' => 'Organization',
            'name' => $nama_madrasah,
            'logo' => [
                '@type' => 'ImageObject',
                'url' => $logo_url
            ]
        ],
        'mainEntityOfPage' => [
            '@type' => 'WebPage',
            '@id' => $canonical_url
        ]
    ], JSON_UNESCAPED_SLASHES|JSON_UNESCAPED_UNICODE) ?>
    </script>
</head>

<body>

<!-- ═══ TOPBAR ═══ -->
<div class="web-topbar">
    <div class="container web-topbar-inner">
        <span><i class="bi bi-building"></i> Portal Resmi <?= detail_clean($nama_madrasah) ?></span>
        <span><i class="bi bi-grid-3x3-gap-fill"></i> Berita • PPDB • Akademik • Tata Usaha</span>
    </div>
</div>

<!-- ═══ NAVBAR ═══ -->
<nav class="navbar navbar-expand-lg web-navbar sticky-top">
    <div class="container">
        <a href="<?= base_url() ?>" class="navbar-brand web-brand">
            <div class="web-brand-logo">
                <?php if(file_exists($logo_file)): ?>
                    <img src="<?= $logo_url ?>" alt="<?= detail_clean($nama_madrasah) ?>">
                <?php else: ?>
                    M3
                <?php endif; ?>
            </div>
            <div class="web-brand-text">
                <strong><?= detail_clean($nama_madrasah) ?></strong>
                <small>Portal Digital Madrasah</small>
            </div>
        </a>

        <button class="navbar-toggler web-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#webNavbar">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="webNavbar">
            <ul class="navbar-nav ms-auto me-lg-3 mt-3 mt-lg-0 web-menu">
                <li class="nav-item">
                    <a href="<?= base_url() ?>" class="nav-link">Beranda</a>
                </li>

                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        Profil Madrasah
                    </a>
                    <ul class="dropdown-menu shadow-sm">
                        <li><a class="dropdown-item" href="<?= base_url() ?>#tentang">Sejarah & Fasilitas</a></li>
                        <li><a class="dropdown-item" href="<?= base_url() ?>#visi-misi">Visi & Misi</a></li>
                        <li><a class="dropdown-item" href="<?= base_url() ?>#profil">Profil Singkat</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item" href="<?= base_url('website/struktur/tenaga-pendidik') ?>">Tenaga Pendidik</a></li>
                        <li><a class="dropdown-item" href="<?= base_url('website/struktur/kependidikan') ?>">Kependidikan</a></li>
                        <li><a class="dropdown-item" href="<?= base_url('website/struktur/koordinator') ?>">Koordinator</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item" href="<?= base_url('website/ptk') ?>">Direktori PTK Lengkap</a></li>
                    </ul>
                </li>

                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        Informasi
                    </a>
                    <ul class="dropdown-menu shadow-sm">
                        <li><a class="dropdown-item" href="<?= base_url('website/berita') ?>">Berita Terbaru</a></li>
                        <li><a class="dropdown-item" href="<?= base_url('website/pamflet') ?>">Pengumuman / Pamflet</a></li>
                        <li><a class="dropdown-item" href="<?= base_url('website/galeri') ?>">Galeri Kegiatan</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item" href="<?= base_url() ?>#media">Media & Video</a></li>
                    </ul>
                </li>

                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        PPDB Online
                    </a>
                    <ul class="dropdown-menu shadow-sm">
                        <li><a class="dropdown-item" href="<?= base_url('ppdb') ?>">Informasi Pendaftaran</a></li>
                        <li><a class="dropdown-item" href="<?= base_url('ppdb') ?>">Cek Status Kelulusan</a></li>
                    </ul>
                </li>

                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        Layanan Akademik
                    </a>
                    <ul class="dropdown-menu shadow-sm">

                        <li><a class="dropdown-item" href="https://rdm.man3banjar.com" target="_blank" rel="noopener">Rapor Digital (RDM)</a></li>
                    </ul>
                </li>

                <li class="nav-item">
                    <a href="<?= base_url() ?>#kontak" class="nav-link">Kontak</a>
                </li>
            </ul>

        </div>
    </div>
</nav>

<!-- ═══ HERO ARTIKEL ═══ -->
<header class="detail-news-hero">
    <div class="container">
        <div class="detail-news-hero-inner mx-auto text-center">

            <div class="detail-breadcrumb justify-content-center">
                <a href="<?= base_url() ?>"><i class="bi bi-house-door"></i> Beranda</a>
                <span>/</span>
                <a href="<?= base_url('website/berita') ?>">Berita</a>
                <span>/</span>
                <strong>Detail</strong>
            </div>

            <div class="detail-news-label"><i class="bi bi-tag"></i> <?= detail_clean($kategori) ?></div>

            <h1><?= $judul ?></h1>

            <div class="detail-news-meta justify-content-center">
                <span><i class="bi bi-calendar3"></i> <?= date('d M Y', strtotime($tanggal)) ?></span>
                <span>•</span>
                <span><i class="bi bi-person-circle"></i> <?= detail_clean($nama_madrasah) ?></span>
                <?php if(isset($berita->view_count)): ?>
                    <span>•</span>
                    <span><i class="bi bi-eye"></i> <?= number_format($berita->view_count, 0, ',', '.') ?> tayangan</span>
                <?php endif; ?>
            </div>

        </div>
    </div>
</header>

<main class="detail-news-section pt-0">
    <div class="container">
        <div class="detail-news-layout">

            <!-- ═══ ARTIKEL UTAMA ═══ -->
            <article class="detail-news-article">
                <div class="detail-news-image-wrap">
                    <?php if(!empty($gambar) && file_exists($gambar_file)): ?>
                        <img src="<?= base_url('assets/news/'.$gambar) ?>" alt="<?= $judul ?>" class="detail-news-image">
                    <?php else: ?>
                        <div class="detail-news-image-empty"><i class="bi bi-image" style="font-size:48px; margin-bottom:12px; display:block;"></i> Berita Madrasah</div>
                    <?php endif; ?>
                </div>

                <?php if(!empty($gambar_berita)): ?>
                    <div class="detail-news-gallery">
                        <?php foreach($gambar_berita as $g): ?>
                            <?php $g_file = FCPATH.'assets/news/'.$g->gambar; ?>
                            <?php if(!empty($g->gambar) && file_exists($g_file)): ?>
                                <a href="<?= base_url('assets/news/'.$g->gambar) ?>" target="_blank" rel="noopener">
                                    <img src="<?= base_url('assets/news/'.$g->gambar) ?>" alt="Foto tambahan">
                                </a>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>

                <div class="detail-news-content detail-news-article-content">
                    <?= nl2br(detail_clean($berita->isi ?? '')) ?>
                </div>

                <div class="detail-share-box">
                    <div>
                        <small>Bagikan informasi ini</small>
                        <strong>Apakah artikel ini bermanfaat?</strong>
                    </div>
                    <a href="https://wa.me/?text=<?= $share_text ?>" target="_blank" rel="noopener" class="detail-share-wa">
                        <i class="bi bi-whatsapp"></i> Bagikan ke WhatsApp
                    </a>
                </div>

                <div class="detail-news-footer">
                    <a href="<?= base_url('website/berita') ?>" class="detail-back-btn">
                        <i class="bi bi-arrow-left"></i> Kembali ke Berita
                    </a>
                    <a href="<?= base_url() ?>" class="detail-home-btn">
                        <i class="bi bi-house-door"></i> Halaman Utama
                    </a>
                </div>
            </article>

            <!-- ═══ SIDEBAR ═══ -->
            <aside class="detail-news-sidebar">
                
                <div class="detail-side-card">
                    <h4><i class="bi bi-clock-history"></i> Berita Terbaru</h4>

                    <?php if(!empty($berita_lainnya)): ?>
                        <div class="detail-side-list">
                            <?php foreach(array_slice($berita_lainnya, 0, 5) as $b): ?>
                                <?php
                                $side_judul = detail_clean($b->judul ?? '');
                                $side_gambar = $b->gambar ?? '';
                                $side_file = !empty($side_gambar) ? FCPATH.'assets/news/'.$side_gambar : '';
                                $side_tanggal = !empty($b->published_at) ? $b->published_at : (!empty($b->created_at) ? $b->created_at : date('Y-m-d H:i:s'));
                                ?>
                                <a href="<?= base_url('berita/detail/'.$b->id) ?>" class="detail-side-item">
                                    <?php if(!empty($side_gambar) && file_exists($side_file)): ?>
                                        <img src="<?= base_url('assets/news/'.$side_gambar) ?>" alt="<?= $side_judul ?>">
                                    <?php else: ?>
                                        <div class="detail-side-placeholder"><i class="bi bi-newspaper"></i></div>
                                    <?php endif; ?>

                                    <div>
                                        <small><?= date('d M Y', strtotime($side_tanggal)) ?></small>
                                        <strong><?= $side_judul ?></strong>
                                    </div>
                                </a>
                            <?php endforeach; ?>
                        </div>
                    <?php else: ?>
                        <div class="detail-empty-mini">Belum ada berita lainnya.</div>
                    <?php endif; ?>
                </div>

                <div class="detail-side-card detail-info-card">
                    <h4><i class="bi bi-info-circle"></i> Informasi Madrasah</h4>

                    <div class="detail-info-item">
                        <small>Nama Instansi</small>
                        <strong><?= detail_clean($nama_madrasah) ?></strong>
                    </div>

                    <?php if(!empty($profil_website->telepon)): ?>
                        <div class="detail-info-item">
                            <small>Telepon / Layanan</small>
                            <strong><?= detail_clean($profil_website->telepon) ?></strong>
                        </div>
                    <?php endif; ?>

                    <?php if(!empty($profil_website->email)): ?>
                        <div class="detail-info-item">
                            <small>Alamat Email</small>
                            <strong><?= detail_clean($profil_website->email) ?></strong>
                        </div>
                    <?php endif; ?>
                    
                    <div class="detail-info-item" style="border:none; padding-top: 16px;">
                        <a href="<?= base_url('ppdb') ?>" class="web-btn web-btn-primary" style="width: 100%; min-height: 42px; font-size: 13px;">
                            <i class="bi bi-pencil-square"></i> Info PPDB Baru
                        </a>
                    </div>
                </div>

            </aside>

        </div>
    </div>
</main>

<!-- ═══ FOOTER ═══ -->
<footer class="web-footer">
    <div class="container">
        <div class="web-footer-grid">
            <div>
                <h3><?= detail_clean($nama_madrasah) ?></h3>
                <p>
                    <?= !empty($profil_website->isi_profil)
                        ? detail_clean(detail_limit($profil_website->isi_profil, 220))
                        : 'Portal informasi dan layanan digital madrasah.' ?>
                </p>
            </div>
            <div>
                <h5>Menu Website</h5>
                <a href="<?= base_url() ?>#berita">Berita</a>
                <a href="<?= base_url() ?>#profil">Profil</a>
                <a href="<?= base_url() ?>#visi-misi">Visi Misi</a>
                <a href="<?= base_url() ?>#media">Media</a>
                <a href="<?= base_url() ?>#ptk">PTK</a>
                <a href="<?= base_url() ?>#galeri">Galeri</a>
                <a href="<?= base_url() ?>#kontak">Kontak</a>
            </div>
            <div>
                <h5>Kontak Resmi</h5>
                <span><i class="bi bi-telephone"></i> <?= !empty($profil_website->telepon) ? detail_clean($profil_website->telepon) : '-' ?></span>
                <span><i class="bi bi-envelope"></i> <?= !empty($profil_website->email) ? detail_clean($profil_website->email) : '-' ?></span>

                <?php if(!empty($profil_website->facebook_url)): ?>
                    <a href="<?= detail_clean($profil_website->facebook_url) ?>" target="_blank" rel="noopener"><i class="bi bi-facebook"></i> Facebook</a>
                <?php endif; ?>
                <?php if(!empty($profil_website->instagram_url)): ?>
                    <a href="<?= detail_clean($profil_website->instagram_url) ?>" target="_blank" rel="noopener"><i class="bi bi-instagram"></i> Instagram</a>
                <?php endif; ?>
                <?php if(!empty($profil_website->youtube_url)): ?>
                    <a href="<?= detail_clean($profil_website->youtube_url) ?>" target="_blank" rel="noopener"><i class="bi bi-youtube"></i> YouTube</a>
                <?php endif; ?>
            </div>
        </div>
        <div class="web-footer-bottom">
            <span>© <?= date('Y') ?> <?= detail_clean($nama_madrasah) ?>. All rights reserved.</span>
            <span>Website Madrasah Digital</span>
        </div>
    </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>