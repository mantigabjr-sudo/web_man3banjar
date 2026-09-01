<?php
$nama_madrasah = 'MAN 3 Banjar';

$logo_file = FCPATH.'assets/img/logo-madrasah.png';
$logo_url  = base_url('assets/img/logo-madrasah.png');

$berita_utama   = !empty($berita) ? $berita[0] : null;
$berita_lainnya = !empty($berita) ? array_slice($berita, 1, 6) : [];

if(!function_exists('web_clean')){
    function web_clean($text){
        return htmlspecialchars((string)$text, ENT_QUOTES, 'UTF-8');
    }
}

if(!function_exists('web_limit')){
    function web_limit($text, $limit = 130){
        $text = trim(strip_tags((string)$text));
        if(function_exists('mb_strimwidth')){
            return mb_strimwidth($text, 0, $limit, '...');
        }
        return strlen($text) > $limit ? substr($text, 0, $limit).'...' : $text;
    }
}

if(!function_exists('web_youtube_embed')){
    function web_youtube_embed($url){
        if(preg_match('/youtu\.be\/([^\?]+)/', $url, $match)){
            return 'https://www.youtube.com/embed/'.$match[1];
        }
        if(preg_match('/v=([^&]+)/', $url, $match)){
            return 'https://www.youtube.com/embed/'.$match[1];
        }
        if(strpos($url, 'embed') !== false){
            return $url;
        }
        return '';
    }
}

if(!function_exists('web_text_items')){
    function web_text_items($text){
        $rows = preg_split('/\r\n|\r|\n/', (string)$text);
        $items = [];
        foreach($rows as $row){
            $row = trim($row);
            if($row !== '') $items[] = $row;
        }
        return $items;
    }
}

$video_embed = !empty($video_profil->youtube_url)
    ? web_youtube_embed($video_profil->youtube_url)
    : '';

$fasilitas_items = !empty($profil_website->fasilitas)
    ? web_text_items($profil_website->fasilitas)
    : [];

$prestasi_items = !empty($profil_website->prestasi)
    ? web_text_items($profil_website->prestasi)
    : [];

$ekskul_items = !empty($profil_website->ekstrakurikuler)
    ? web_text_items($profil_website->ekstrakurikuler)
    : [];

$wa_number = '';
if(!empty($profil_website->whatsapp)){
    $wa_number = preg_replace('/[^0-9]/', '', $profil_website->whatsapp);
    if(substr($wa_number, 0, 1) == '0') $wa_number = '62'.substr($wa_number, 1);
} elseif(!empty($profil_website->telepon)){
    $wa_number = preg_replace('/[^0-9]/', '', $profil_website->telepon);
    if(substr($wa_number, 0, 1) == '0') $wa_number = '62'.substr($wa_number, 1);
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title><?= $nama_madrasah ?> — Portal Madrasah Digital</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Portal resmi <?= $nama_madrasah ?>. Akses berita, profil, PPDB, akademik, dan layanan digital madrasah.">

    <link rel="icon" type="image/png" href="<?= base_url('assets/img/favicon.png') ?>">
    <link rel="shortcut icon" type="image/png" href="<?= base_url('assets/img/favicon.png') ?>">
    <link rel="apple-touch-icon" href="<?= base_url('assets/img/favicon.png') ?>">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?= base_url('assets/css/website-home.css?v=21') ?>">
</head>

<body>

<!-- ═══ TOPBAR ═══ -->
<div class="web-topbar">
    <div class="container web-topbar-inner">
        <span><i class="bi bi-building"></i> Portal Resmi <?= $nama_madrasah ?></span>
        <span><i class="bi bi-grid-3x3-gap-fill"></i> Berita • PPDB • Akademik • Tata Usaha</span>
    </div>
</div>

<!-- ═══ NAVBAR ═══ -->
<nav class="navbar navbar-expand-lg web-navbar sticky-top" id="mainNav">
    <div class="container">
        <a href="<?= base_url() ?>" class="navbar-brand web-brand">
            <div class="web-brand-logo">
                <?php if(file_exists($logo_file)): ?>
                    <img src="<?= $logo_url ?>" alt="<?= $nama_madrasah ?>">
                <?php else: ?>
                    M3
                <?php endif; ?>
            </div>
            <div class="web-brand-text">
                <strong><?= $nama_madrasah ?></strong>
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
                        <li><a class="dropdown-item" href="<?= base_url('website/sejarah') ?>">Sejarah</a></li>
                        <li><a class="dropdown-item" href="<?= base_url('website/visi_misi') ?>">Visi &amp; Misi</a></li>
                        <li><a class="dropdown-item" href="<?= base_url('website/fasilitas') ?>">Fasilitas</a></li>
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
                        <li><a class="dropdown-item" href="<?= base_url('website/data_siswa') ?>">Data Siswa (Keadaan)</a></li>
                        <li><a class="dropdown-item" href="<?= base_url('website/pamflet') ?>">Pengumuman / Pamflet</a></li>
                        <li><a class="dropdown-item" href="<?= base_url('website/galeri') ?>">Galeri Kegiatan</a></li>
                        <li><a class="dropdown-item" href="<?= base_url('website/download') ?>">Download File</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item" href="<?= base_url() ?>#media">Media & Video</a></li>
                    </ul>
                </li>

                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        PMB Online
                    </a>
                    <ul class="dropdown-menu shadow-sm">
                        <li><a class="dropdown-item" href="<?= base_url('pmb') ?>">Informasi Pendaftaran</a></li>
                        <li><a class="dropdown-item" href="<?= base_url('pmb/login') ?>">Login Calon Siswa</a></li>
                    </ul>
                </li>

                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        Layanan Akademik
                    </a>
                    <ul class="dropdown-menu shadow-sm">
                        <li><a class="dropdown-item fw-bold text-success" href="<?= base_url('website/monitoring_kbm') ?>"><i class="bi bi-broadcast text-danger me-1"></i> Live Monitoring KBM</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item" href="https://rdm.man3banjar.com" target="_blank" rel="noopener">Rapor Digital (RDM)</a></li>
                    </ul>
                </li>

                <li class="nav-item">
                    <a href="<?= base_url('website/monitoring_kbm') ?>" class="nav-link fw-bold text-success d-none d-xl-block">
                        <i class="bi bi-broadcast text-danger me-1"></i> Live KBM
                    </a>
                </li>

                <li class="nav-item">
                    <a href="<?= base_url('website/alumni') ?>" class="nav-link">Alumni</a>
                </li>

                <li class="nav-item">
                    <a href="<?= base_url() ?>#kontak" class="nav-link">Kontak</a>
                </li>
            </ul>
        </div>
    </div>
</nav>

<?php $this->load->view('public/partials/home_banner_slider'); ?>

<!-- ═══ HERO ═══ -->
<header class="web-hero">
    <div class="container">
        <div class="web-kicker">
            <i class="bi bi-stars"></i> Madrasah Digital Modern
        </div>

        <h1>Portal Informasi dan Layanan Digital <?= $nama_madrasah ?></h1>

        <p class="web-hero-sub">
            Akses berita terbaru, profil madrasah, PMB online, informasi akademik, tata usaha, galeri kegiatan, dan layanan digital madrasah dalam satu halaman.
        </p>

        <div class="web-hero-actions">
            <a href="<?= base_url('website/monitoring_kbm') ?>" class="web-btn web-btn-primary" style="background: #14532d; border-color: #14532d;">
                <i class="bi bi-broadcast text-danger me-1"></i> Live Monitoring KBM
            </a>
            <a href="<?= base_url('pmb') ?>" class="web-btn web-btn-outline">
                <i class="bi bi-pencil-square"></i> Daftar PMB
            </a>
            <a href="#berita" class="web-btn web-btn-outline">
                <i class="bi bi-newspaper"></i> Lihat Berita
            </a>
        </div>

        <div class="web-hero-stat-grid">
            <div class="web-hero-stat reveal">
                <strong><?= !empty($berita) ? count($berita) : 0 ?></strong>
                <span>Berita</span>
            </div>
            <div class="web-hero-stat reveal">
                <strong><?= !empty($ptk_website) ? count($ptk_website) : 0 ?></strong>
                <span>PTK</span>
            </div>
            <div class="web-hero-stat reveal">
                <strong><?= !empty($galeri) ? count($galeri) : 0 ?></strong>
                <span>Galeri</span>
            </div>
        </div>
    </div>
</header>

<!-- ═══ BERITA ═══ -->
<section class="web-section web-soft" id="berita">
    <div class="container">
        <div class="web-section-head web-section-row reveal">
            <div>
                <span class="web-section-label"><i class="bi bi-newspaper"></i> Berita Terbaru</span>
                <h2>Informasi Madrasah Hari Ini</h2>
                <p>Kegiatan, prestasi, pengumuman, dan kabar terbaru dari <?= $nama_madrasah ?>.</p>
            </div>
            <a href="<?= base_url('website/berita') ?>" class="web-more-link">Semua Berita →</a>
        </div>

        <?php if(!empty($berita_utama)): ?>
        <div class="web-news-featured reveal">
            <!-- Featured Card -->
            <?php
            $utama_judul  = web_clean($berita_utama->judul ?? '');
            $utama_isi    = web_limit($berita_utama->isi ?? '', 200);
            $utama_gambar = $berita_utama->gambar ?? '';
            $utama_file   = !empty($utama_gambar) ? FCPATH.'assets/news/'.$utama_gambar : '';
            $utama_key    = !empty($berita_utama->slug) ? $berita_utama->slug : $berita_utama->id;
            ?>
            <a href="<?= base_url('berita/detail/'.$utama_key) ?>" class="web-news-featured-card">
                <?php if(!empty($utama_gambar) && file_exists($utama_file)): ?>
                    <img src="<?= base_url('assets/news/'.$utama_gambar) ?>" alt="<?= $utama_judul ?>">
                <?php else: ?>
                    <div class="web-news-featured-placeholder"><i class="bi bi-newspaper"></i> Berita Utama</div>
                <?php endif; ?>
                <div class="web-news-featured-body">
                    <small>
                        <?= !empty($berita_utama->created_at) ? date('d M Y', strtotime($berita_utama->created_at)) : date('d M Y') ?>
                        <?php if(!empty($berita_utama->kategori)): ?>
                            · <?= web_clean($berita_utama->kategori) ?>
                        <?php endif; ?>
                    </small>
                    <h3><?= $utama_judul ?></h3>
                    <p><?= web_clean($utama_isi) ?></p>
                    <span class="web-read-more">Baca Selengkapnya →</span>
                </div>
            </a>

            <!-- Side Stack -->
            <?php if(!empty($berita_lainnya)): ?>
            <div class="web-news-side-stack">
                <?php foreach(array_slice($berita_lainnya, 0, 4) as $b): ?>
                    <?php
                    $judul   = web_clean($b->judul ?? '');
                    $gambar  = $b->gambar ?? '';
                    $g_file  = !empty($gambar) ? FCPATH.'assets/news/'.$gambar : '';
                    $d_key   = !empty($b->slug) ? $b->slug : $b->id;
                    ?>
                    <a href="<?= base_url('berita/detail/'.$d_key) ?>" class="web-news-side-card">
                        <?php if(!empty($gambar) && file_exists($g_file)): ?>
                            <img src="<?= base_url('assets/news/'.$gambar) ?>" alt="<?= $judul ?>">
                        <?php else: ?>
                            <div class="web-news-side-placeholder"><i class="bi bi-newspaper"></i></div>
                        <?php endif; ?>
                        <div>
                            <small><?= !empty($b->created_at) ? date('d M Y', strtotime($b->created_at)) : date('d M Y') ?></small>
                            <h5><?= $judul ?></h5>
                        </div>
                    </a>
                <?php endforeach; ?>
            </div>
            <?php endif; ?>
        </div>
        <?php else: ?>
            <div class="web-empty reveal">Belum ada berita yang dipublikasikan.</div>
        <?php endif; ?>
    </div>
</section>

<!-- ═══ PROFIL ═══ -->
<section class="web-section" id="profil">
    <div class="container">
        <div class="web-profile-grid reveal">
            <div class="web-profile-card">
                <span class="web-section-label"><i class="bi bi-building"></i> Profil Singkat</span>
                <h2>
                    <?= !empty($profil_website->judul_profil)
                        ? web_clean($profil_website->judul_profil)
                        : 'Madrasah Digital dan Berkarakter' ?>
                </h2>
                <p>
                    <?= !empty($profil_website->isi_profil)
                        ? nl2br(web_clean($profil_website->isi_profil))
                        : $nama_madrasah.' berkomitmen menghadirkan pendidikan yang memadukan ilmu pengetahuan, nilai keislaman, teknologi, dan pelayanan yang transparan.' ?>
                </p>
                <div class="web-profile-points">
                    <div><span><i class="bi bi-check-lg"></i></span> Informasi madrasah cepat dan terbuka.</div>
                    <div><span><i class="bi bi-check-lg"></i></span> Layanan akademik dan administrasi lebih terintegrasi.</div>
                    <div><span><i class="bi bi-check-lg"></i></span> Mendukung pembelajaran dan pelayanan berbasis digital.</div>
                </div>
            </div>

            <div class="web-headmaster-card">
                <?php if(!empty($kepala_madrasah)): ?>
                    <?php
                    $kepala_foto = !empty($kepala_madrasah->foto) ? FCPATH.'uploads/ptk/foto/'.$kepala_madrasah->foto : '';
                    $kepala_nama = web_clean($kepala_madrasah->nama_lengkap ?? '-');
                    $kepala_nip = !empty($kepala_madrasah->nip) ? $kepala_madrasah->nip : '-';
                    $kepala_jabatan = !empty($kepala_madrasah->jabatan) ? $kepala_madrasah->jabatan : 'Kepala Madrasah';
                    ?>
                    <div class="web-headmaster-label"><i class="bi bi-person-badge"></i> Kepala Madrasah</div>
                    <?php if(!empty($kepala_madrasah->foto) && file_exists($kepala_foto)): ?>
                        <img src="<?= base_url('uploads/ptk/foto/'.$kepala_madrasah->foto) ?>" class="web-headmaster-photo" alt="<?= $kepala_nama ?>">
                    <?php else: ?>
                        <div class="web-headmaster-avatar">
                            <?= !empty($kepala_madrasah->nama_lengkap) ? strtoupper(substr($kepala_madrasah->nama_lengkap,0,1)) : 'K' ?>
                        </div>
                    <?php endif; ?>
                    <h3><?= $kepala_nama ?></h3>
                    <p><?= web_clean($kepala_jabatan) ?></p>
                    <div class="web-headmaster-info">
                        <small>NIP</small>
                        <strong><?= web_clean($kepala_nip) ?></strong>
                    </div>
                <?php else: ?>
                    <div class="web-headmaster-label"><i class="bi bi-person-badge"></i> Kepala Madrasah</div>
                    <div class="web-headmaster-avatar">K</div>
                    <h3>Belum Diatur</h3>
                    <p>Silakan pilih kepala madrasah melalui menu Pengaturan.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>

<!-- ═══ SEKILAS MADRASAH ═══ -->
<?php if(!empty($profil_website)): ?>
<section class="web-section web-soft" id="profil-singkat" style="background: #f8fafc; padding: 80px 0;">
    <div class="container">
        <div class="row align-items-center g-5">
            <!-- Kolom Kiri: Sejarah Singkat -->
            <div class="col-lg-6 reveal">
                <span class="web-section-label" style="background: #ecfdf5; color: #10b981; padding: 6px 16px; border-radius: 50px; font-weight: 700; font-size: 13px; display: inline-block; margin-bottom: 16px; border: 1px solid #a7f3d0;"><i class="bi bi-book"></i> Profil Singkat</span>
                <h2 style="font-weight: 800; font-size: 36px; color: #0f172a; margin-bottom: 20px; line-height: 1.3;">Selamat Datang di <br><span style="color: #10b981;"><?= htmlspecialchars($nama_madrasah ?? 'MAN 3 Banjar', ENT_QUOTES, 'UTF-8') ?></span></h2>
                <div style="font-size: 16px; color: #475569; line-height: 1.8; margin-bottom: 30px; text-align: justify;">
                    <p><?= !empty($profil_website->sejarah) ? web_clean(mb_strimwidth(strip_tags($profil_website->sejarah), 0, 350, "...")) : 'Informasi sejarah madrasah belum tersedia.' ?></p>
                </div>
                <div class="d-flex flex-wrap gap-3">
                    <a href="<?= base_url('website/sejarah') ?>" class="web-btn web-btn-primary" style="padding: 12px 24px; border-radius: 50px; background: #10b981; border: none; font-weight: 600; color: white; display: inline-flex; align-items: center; gap: 8px; text-decoration: none; transition: all 0.3s;" onmouseover="this.style.background='#059669'" onmouseout="this.style.background='#10b981'">
                        <i class="bi bi-clock-history"></i> Baca Sejarah
                    </a>
                    <a href="<?= base_url('website/fasilitas') ?>" class="web-btn web-btn-outline-primary" style="padding: 12px 24px; border-radius: 50px; border: 2px solid #10b981; color: #10b981; font-weight: 600; display: inline-flex; align-items: center; gap: 8px; text-decoration: none; transition: all 0.3s;" onmouseover="this.style.background='#10b981'; this.style.color='white'" onmouseout="this.style.background='transparent'; this.style.color='#10b981'">
                        <i class="bi bi-buildings"></i> Lihat Fasilitas
                    </a>
                </div>
            </div>
            <!-- Kolom Kanan: Visi & Misi Ringkas -->
            <div class="col-lg-6 reveal">
                <div class="row g-4">
                    <div class="col-12">
                        <div style="background: #ffffff; border: 1px solid #e2e8f0; border-radius: 20px; padding: 35px; box-shadow: 0 10px 30px rgba(0,0,0,0.02); transition: transform 0.3s;" onmouseover="this.style.transform='translateY(-5px)'" onmouseout="this.style.transform='translateY(0)'">
                            <div style="display: flex; gap: 20px; align-items: flex-start;">
                                <div style="width: 50px; height: 50px; background: #dcfce7; border-radius: 12px; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                                    <i class="bi bi-eye-fill text-success" style="font-size: 24px;"></i>
                                </div>
                                <div>
                                    <h4 style="font-weight: 700; color: #1e293b; margin-bottom: 8px;">Visi Madrasah</h4>
                                    <p style="color: #64748b; line-height: 1.7; font-style: italic; font-size: 15px; margin: 0;">"<?= !empty($profil_website->visi) ? web_clean(mb_strimwidth(strip_tags($profil_website->visi), 0, 150, "...")) : '-' ?>"</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-12">
                        <div style="background: #ffffff; border: 1px solid #e2e8f0; border-radius: 20px; padding: 35px; box-shadow: 0 10px 30px rgba(0,0,0,0.02); transition: transform 0.3s;" onmouseover="this.style.transform='translateY(-5px)'" onmouseout="this.style.transform='translateY(0)'">
                            <div style="display: flex; gap: 20px; align-items: flex-start;">
                                <div style="width: 50px; height: 50px; background: #eff6ff; border-radius: 12px; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                                    <i class="bi bi-bullseye text-primary" style="font-size: 24px;"></i>
                                </div>
                                <div>
                                    <h4 style="font-weight: 700; color: #1e293b; margin-bottom: 8px;">Misi Madrasah</h4>
                                    <p style="color: #64748b; line-height: 1.7; font-size: 15px; margin-bottom: 12px;"><?= !empty($profil_website->misi) ? web_clean(mb_strimwidth(strip_tags($profil_website->misi), 0, 150, "...")) : '-' ?></p>
                                    <a href="<?= base_url('website/visi_misi') ?>" style="color: #3b82f6; font-weight: 600; text-decoration: none; font-size: 14px; display: inline-flex; align-items: center; gap: 4px;" onmouseover="this.style.textDecoration='underline'" onmouseout="this.style.textDecoration='none'">
                                        Selengkapnya <i class="bi bi-arrow-right"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<?php endif; ?>

<!-- ═══ MEDIA ═══ -->
<section class="web-section web-media" id="media">
    <div class="container">

        <?php
        $video_raw_url = '';
        if(!empty($video_profil)){
            if(!empty($video_profil->url_video)) $video_raw_url = $video_profil->url_video;
            elseif(!empty($video_profil->link_video)) $video_raw_url = $video_profil->link_video;
            elseif(!empty($video_profil->url)) $video_raw_url = $video_profil->url;
            elseif(!empty($video_profil->link)) $video_raw_url = $video_profil->link;
        }
        if(!empty($video_embed)) $video_embed_url = $video_embed;
        elseif(function_exists('youtube_embed_url')) $video_embed_url = youtube_embed_url($video_raw_url);
        else $video_embed_url = $video_raw_url;

        if(function_exists('youtube_watch_url')) $video_watch_url = youtube_watch_url($video_raw_url);
        else $video_watch_url = $video_raw_url;
        ?>

        <div class="web-section-head reveal">
            <span class="web-section-label"><i class="bi bi-play-circle"></i> Media Center</span>
            <h2>Video Profil dan Pamflet Informasi</h2>
            <p>Media publikasi madrasah berupa video profil, poster, pamflet, dan informasi visual.</p>
        </div>

        <div class="web-media-grid reveal">
            <div class="web-video-card">
                <?php if(!empty($video_embed_url)): ?>
                    <div class="web-video-frame">
                        <iframe src="<?= htmlspecialchars($video_embed_url, ENT_QUOTES, 'UTF-8') ?>"
                                title="Video Profil Madrasah"
                                allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share"
                                allowfullscreen loading="lazy"></iframe>
                    </div>
                    <div class="web-video-caption">
                        <h4><?= web_clean($video_profil->judul ?? 'Video Profil Madrasah') ?></h4>
                        <p><?= !empty($video_profil->deskripsi) ? web_clean($video_profil->deskripsi) : 'Kenali lebih dekat profil, lingkungan, kegiatan, dan layanan madrasah.' ?></p>
                        <?php if(!empty($video_watch_url)): ?>
                            <a href="<?= htmlspecialchars($video_watch_url, ENT_QUOTES, 'UTF-8') ?>" target="_blank" rel="noopener" class="web-more-link">
                                <i class="bi bi-youtube"></i> Tonton di YouTube →
                            </a>
                        <?php endif; ?>
                    </div>
                <?php else: ?>
                    <div class="web-empty">Video profil belum tersedia.</div>
                <?php endif; ?>
            </div>

            <div class="web-pamflet-card">
                <div class="web-pamflet-head">
                    <div>
                        <h4>Pamflet Terbaru</h4>
                        <span>Informasi visual</span>
                    </div>
                    <a href="<?= base_url('website/pamflet') ?>" class="web-more-link">Semua Pamflet →</a>
                </div>

                <?php if(!empty($pamflet)): ?>
                    <div class="web-pamflet-list">
                        <?php foreach(array_slice($pamflet, 0, 3) as $p): ?>
                            <?php
                            $pamflet_file = !empty($p->gambar) ? FCPATH.'assets/pamflet/'.$p->gambar : '';
                            $pamflet_url  = !empty($p->gambar) ? base_url('assets/pamflet/'.$p->gambar) : '#';
                            ?>
                            <a href="<?= htmlspecialchars($pamflet_url, ENT_QUOTES, 'UTF-8') ?>" target="_blank" rel="noopener" class="web-pamflet-item">
                                <?php if(!empty($p->gambar) && file_exists($pamflet_file)): ?>
                                    <img src="<?= htmlspecialchars($pamflet_url, ENT_QUOTES, 'UTF-8') ?>" alt="<?= web_clean($p->judul ?? 'Pamflet') ?>" loading="lazy">
                                <?php else: ?>
                                    <div class="web-pamflet-placeholder"><i class="bi bi-image"></i></div>
                                <?php endif; ?>
                                <div>
                                    <small><?= !empty($p->tanggal) ? date('d M Y', strtotime($p->tanggal)) : '-' ?></small>
                                    <strong><?= web_clean($p->judul ?? 'Pamflet Informasi') ?></strong>
                                    <span>Lihat pamflet →</span>
                                </div>
                            </a>
                        <?php endforeach; ?>
                    </div>
                <?php else: ?>
                    <div class="web-empty">Pamflet belum tersedia.</div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>



<!-- ═══ PTK ═══ -->
<?php if(!empty($ptk_website)): ?>
<section class="web-section" id="ptk">
    <div class="container">
        <div class="web-section-head reveal">
            <span class="web-section-label"><i class="bi bi-person-workspace"></i> PTK Madrasah</span>
            <h2>Pendidik dan Tenaga Kependidikan</h2>
            <p>Guru dan tenaga kependidikan yang mendukung proses pendidikan dan layanan madrasah.</p>
        </div>

        <div class="web-ptk-grid reveal">
            <?php foreach(array_slice($ptk_website, 0, 8) as $p): ?>
                <?php $foto_file = !empty($p->foto) ? FCPATH.'uploads/ptk/foto/'.$p->foto : ''; ?>
                <div class="web-ptk-card">
                    <?php if(!empty($p->foto) && file_exists($foto_file)): ?>
                        <img src="<?= base_url('uploads/ptk/foto/'.$p->foto) ?>" alt="<?= web_clean($p->nama_lengkap ?? 'PTK') ?>">
                    <?php else: ?>
                        <div class="web-ptk-avatar">
                            <?= !empty($p->nama_lengkap) ? strtoupper(substr($p->nama_lengkap,0,1)) : 'P' ?>
                        </div>
                    <?php endif; ?>
                    <h5><?= web_clean($p->nama_lengkap ?? '-') ?></h5>
                    <p><?= !empty($p->jabatan) ? web_clean($p->jabatan) : 'PTK Madrasah' ?></p>
                    <?php if(!empty($p->mapel_utama)): ?>
                        <span><?= web_clean($p->mapel_utama) ?></span>
                    <?php elseif(!empty($p->tugas_utama)): ?>
                        <span><?= web_clean($p->tugas_utama) ?></span>
                    <?php elseif(!empty($p->jenis_ptk)): ?>
                        <span><?= web_clean($p->jenis_ptk) ?></span>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
        </div>
        <div class="text-center mt-4">
            <a href="<?= base_url('website/ptk') ?>" class="web-more-link">Lihat Semua PTK →</a>
        </div>
    </div>
</section>
<?php endif; ?>

<!-- ═══ GALERI ═══ -->
<?php if(!empty($galeri)): ?>
<section class="web-section web-soft" id="galeri">
    <div class="container">
        <div class="web-section-head reveal">
            <span class="web-section-label"><i class="bi bi-images"></i> Galeri Madrasah</span>
            <h2>Dokumentasi Kegiatan</h2>
            <p>Potret kegiatan, prestasi, dan suasana pembelajaran di <?= $nama_madrasah ?>.</p>
        </div>

        <div class="web-gallery-grid reveal">
            <?php foreach(array_slice($galeri, 0, 8) as $g): ?>
                <?php $gambar_file = !empty($g->gambar) ? FCPATH.'assets/galeri/'.$g->gambar : ''; ?>
                <?php if(!empty($g->gambar) && file_exists($gambar_file)): ?>
                    <a href="<?= base_url('assets/galeri/'.$g->gambar) ?>" target="_blank" class="web-gallery-card">
                        <img src="<?= base_url('assets/galeri/'.$g->gambar) ?>" alt="<?= web_clean($g->judul) ?>">
                        <div>
                            <small><?= !empty($g->tanggal) ? date('d M Y', strtotime($g->tanggal)) : '-' ?></small>
                            <h5><?= web_clean($g->judul) ?></h5>
                        </div>
                    </a>
                <?php endif; ?>
            <?php endforeach; ?>
        </div>
        <div class="text-center mt-4">
            <a href="<?= base_url('website/galeri') ?>" class="web-more-link">Lihat Semua Galeri →</a>
        </div>
    </div>
</section>
<?php endif; ?>

<!-- ═══ LAYANAN ═══ -->
<section class="web-section web-service-section" id="layanan">
    <div class="container">
        <div class="web-service-grid reveal">
            <div>
                <div class="web-section-head text-start mx-0">
                    <span class="web-section-label"><i class="bi bi-grid-3x3-gap"></i> Layanan Digital</span>
                    <h2>Menu Layanan Madrasah</h2>
                    <p>Layanan yang tersedia untuk mendukung informasi, akademik, PPDB, dan administrasi.</p>
                </div>

                <div class="web-service-card-grid">
                    <div class="web-service-card">
                        <span><i class="bi bi-person-plus"></i></span>
                        <h5>PPDB Online</h5>
                        <p>Pendaftaran peserta didik baru secara digital.</p>
                    </div>
                    <div class="web-service-card">
                        <span><i class="bi bi-journal-bookmark"></i></span>
                        <h5>Akademik</h5>
                        <p>Jadwal, absensi, nilai, kelas, dan monitoring.</p>
                    </div>
                    <div class="web-service-card">
                        <span><i class="bi bi-file-earmark-text"></i></span>
                        <h5>Tata Usaha</h5>
                        <p>Surat, izin, inventaris, dan layanan administrasi.</p>
                    </div>
                    <div class="web-service-card">
                        <span><i class="bi bi-megaphone"></i></span>
                        <h5>Informasi</h5>
                        <p>Berita, galeri, pamflet, dan publikasi madrasah.</p>
                    </div>
                </div>
            </div>

            <div class="web-ppdb-card" id="pmb">
                <span>PMB Online</span>
                <h2>Penerimaan Murid Baru</h2>
                <p>Calon peserta didik baru dapat melakukan pendaftaran melalui sistem PMB online yang telah disediakan.</p>
                <a href="<?= base_url('pmb') ?>" class="web-btn web-btn-primary">
                    <i class="bi bi-pencil-square"></i> Daftar Sekarang
                </a>
            </div>
        </div>
    </div>
</section>

<!-- ═══ FOOTER ═══ -->
<footer class="web-footer" style="background: var(--c-emerald-950); color: rgba(255,255,255,.6); padding: 60px 0 20px; font-size: 13px;">
    <div class="container">
        <div class="row g-4 mb-5">
            <!-- Kolom 1: Logo & Deskripsi -->
            <div class="col-lg-3 col-md-6">
                <div class="d-flex align-items-center gap-3 mb-3">
                    <div style="width: 56px; height: 56px; border-radius: 50%; border: 2px solid var(--c-emerald-400); background: #fff; padding: 4px; display: flex; align-items: center; justify-content: center; flex-shrink: 0; overflow: hidden;">
                        <?php if(file_exists(FCPATH.'assets/img/logo-madrasah.png')): ?>
                            <img src="<?= base_url('assets/img/logo-madrasah.png') ?>" alt="Logo" style="width: 100%; height: 100%; object-fit: contain;">
                        <?php else: ?>
                            <strong style="color: var(--c-emerald-900); font-size: 16px;">M3</strong>
                        <?php endif; ?>
                    </div>
                    <div>
                        <h4 style="font-weight: 800; font-size: 14px; letter-spacing: 0.5px; color: #fff; margin: 0; line-height: 1.2;">MADRASAH ALIYAH</h4>
                        <h3 style="font-weight: 900; font-size: 18px; color: var(--c-emerald-400); margin: 0; line-height: 1.2;">NEGERI 3 BANJAR</h3>
                        <small style="font-size: 11px; display: block; color: rgba(255,255,255,0.5);">Kabupaten Banjar</small>
                    </div>
                </div>
                <p style="line-height: 1.7; font-size: 13px; text-align: justify; margin: 0;">
                    <?= !empty($profil_website->isi_profil) ? strip_tags($profil_website->isi_profil) : 'Terwujudnya Madrasah Model Sebagai Pusat Keunggulan dan Rujukan Dalam Kualitas Akademik dan Non Akademik Serta Akhlaq Karimah' ?>
                </p>
            </div>

            <!-- Kolom 2: Tautan Cepat -->
            <div class="col-lg-3 col-md-6">
                <h5 style="color: #fff; font-weight: 700; font-size: 15px; margin-bottom: 20px; display: flex; align-items: center; gap: 8px;">
                    <i class="bi bi-link-45deg text-success" style="font-size: 18px;"></i> Tautan Cepat
                </h5>
                <ul class="list-unstyled" style="display: flex; flex-direction: column; gap: 12px; margin: 0;">
                    <li><a href="<?= base_url('website/sejarah') ?>" style="color: rgba(255,255,255,0.7); text-decoration: none; transition: all 0.2s; display: flex; align-items: center; gap: 8px;" onmouseover="this.style.color='#fff'; this.style.paddingLeft='5px'" onmouseout="this.style.color='rgba(255,255,255,0.7)'; this.style.paddingLeft='0'"><i class="bi bi-chevron-right" style="font-size: 10px;"></i> Sejarah</a></li>
                    <li><a href="<?= base_url('website/visi_misi') ?>" style="color: rgba(255,255,255,0.7); text-decoration: none; transition: all 0.2s; display: flex; align-items: center; gap: 8px;" onmouseover="this.style.color='#fff'; this.style.paddingLeft='5px'" onmouseout="this.style.color='rgba(255,255,255,0.7)'; this.style.paddingLeft='0'"><i class="bi bi-chevron-right" style="font-size: 10px;"></i> Visi & Misi</a></li>
                    <li><a href="<?= base_url('website/fasilitas') ?>" style="color: rgba(255,255,255,0.7); text-decoration: none; transition: all 0.2s; display: flex; align-items: center; gap: 8px;" onmouseover="this.style.color='#fff'; this.style.paddingLeft='5px'" onmouseout="this.style.color='rgba(255,255,255,0.7)'; this.style.paddingLeft='0'"><i class="bi bi-chevron-right" style="font-size: 10px;"></i> Fasilitas</a></li>
                    <li><a href="<?= base_url('website/portal') ?>" style="color: rgba(255,255,255,0.7); text-decoration: none; transition: all 0.2s; display: flex; align-items: center; gap: 8px;" onmouseover="this.style.color='#fff'; this.style.paddingLeft='5px'" onmouseout="this.style.color='rgba(255,255,255,0.7)'; this.style.paddingLeft='0'"><i class="bi bi-chevron-right" style="font-size: 10px;"></i> Portal Berita</a></li>
                </ul>
            </div>

            <!-- Kolom 3: Hubungi Kami -->
            <div class="col-lg-3 col-md-6">
                <h5 style="color: #fff; font-weight: 700; font-size: 15px; margin-bottom: 20px; display: flex; align-items: center; gap: 8px;">
                    <i class="bi bi-telephone-fill text-success" style="font-size: 18px;"></i> Hubungi Kami
                </h5>
                <div style="display: flex; flex-direction: column; gap: 15px;">
                    <div style="display: flex; gap: 12px; align-items: flex-start;">
                        <i class="bi bi-geo-alt" style="color: var(--c-emerald-400); font-size: 16px; margin-top: 2px;"></i>
                        <span style="font-size: 13px; line-height: 1.6; color: rgba(255,255,255,0.8);"><?= !empty($profil_website->alamat) ? htmlspecialchars($profil_website->alamat, ENT_QUOTES, 'UTF-8') : 'Alamat belum diatur' ?></span>
                    </div>
                    <div style="display: flex; gap: 12px; align-items: flex-start;">
                        <i class="bi bi-telephone" style="color: var(--c-emerald-400); font-size: 16px; margin-top: 2px;"></i>
                        <span style="font-size: 14px; font-weight: 600; color: rgba(255,255,255,0.8);"><?= !empty($profil_website->telepon) ? htmlspecialchars($profil_website->telepon, ENT_QUOTES, 'UTF-8') : '-' ?></span>
                    </div>
                    <div style="display: flex; gap: 12px; align-items: flex-start;">
                        <i class="bi bi-envelope" style="color: var(--c-emerald-400); font-size: 16px; margin-top: 2px;"></i>
                        <a href="mailto:<?= !empty($profil_website->email) ? htmlspecialchars($profil_website->email, ENT_QUOTES, 'UTF-8') : '' ?>" style="color: rgba(255,255,255,0.8); font-size: 14px; font-weight: 600; text-decoration: none; transition: color 0.2s;"><?= !empty($profil_website->email) ? htmlspecialchars($profil_website->email, ENT_QUOTES, 'UTF-8') : '-' ?></a>
                    </div>
                </div>

                <!-- Social Media -->
                <div class="d-flex gap-2 mt-4">
                    <?php if(!empty($profil_website->facebook_url)): ?>
                        <a href="<?= htmlspecialchars($profil_website->facebook_url, ENT_QUOTES, 'UTF-8') ?>" target="_blank" style="width: 32px; height: 32px; border-radius: 6px; background: rgba(255,255,255,0.1); display: flex; align-items: center; justify-content: center; color: #fff; text-decoration: none; transition: all 0.2s;" onmouseover="this.style.background='var(--c-emerald-500)'" onmouseout="this.style.background='rgba(255,255,255,0.1)'">
                            <i class="bi bi-facebook" style="font-size: 14px;"></i>
                        </a>
                    <?php endif; ?>
                    <?php if(!empty($profil_website->instagram_url)): ?>
                        <a href="<?= htmlspecialchars($profil_website->instagram_url, ENT_QUOTES, 'UTF-8') ?>" target="_blank" style="width: 32px; height: 32px; border-radius: 6px; background: rgba(255,255,255,0.1); display: flex; align-items: center; justify-content: center; color: #fff; text-decoration: none; transition: all 0.2s;" onmouseover="this.style.background='var(--c-emerald-500)'" onmouseout="this.style.background='rgba(255,255,255,0.1)'">
                            <i class="bi bi-instagram" style="font-size: 14px;"></i>
                        </a>
                    <?php endif; ?>
                    <?php if(!empty($profil_website->youtube_url)): ?>
                        <a href="<?= htmlspecialchars($profil_website->youtube_url, ENT_QUOTES, 'UTF-8') ?>" target="_blank" style="width: 32px; height: 32px; border-radius: 6px; background: rgba(255,255,255,0.1); display: flex; align-items: center; justify-content: center; color: #fff; text-decoration: none; transition: all 0.2s;" onmouseover="this.style.background='var(--c-emerald-500)'" onmouseout="this.style.background='rgba(255,255,255,0.1)'">
                            <i class="bi bi-youtube" style="font-size: 14px;"></i>
                        </a>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Kolom 4: Lokasi Kami (Maps) -->
            <div class="col-lg-3 col-md-6">
                <h5 style="color: #fff; font-weight: 700; font-size: 15px; margin-bottom: 20px; display: flex; align-items: center; gap: 8px;">
                    <i class="bi bi-geo-alt-fill text-success" style="font-size: 18px;"></i> Peta Lokasi
                </h5>
                <?php if(!empty($profil_website->maps_embed_url)): ?>
                    <div style="border-radius: 12px; overflow: hidden; height: 180px; border: 1px solid rgba(255,255,255,0.1); background: rgba(0,0,0,0.2);">
                        <iframe src="<?= web_clean($profil_website->maps_embed_url) ?>" style="width: 100%; height: 100%; border: 0;" allowfullscreen="" loading="lazy"></iframe>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <div style="border-top: 1px solid rgba(255,255,255,0.1); padding-top: 20px; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 12px; font-size: 12px; color: rgba(255,255,255,0.5);">
            <span>© <?= date('Y') ?> <?= $nama_madrasah ?>. All rights reserved.</span>
            <div class="d-flex gap-3">
                <a href="#" style="color: rgba(255,255,255,0.5); text-decoration: none; transition: color 0.2s;" onmouseover="this.style.color='#fff'" onmouseout="this.style.color='rgba(255,255,255,0.5)'">Privacy Policy</a>
                <span>|</span>
                <a href="#" style="color: rgba(255,255,255,0.5); text-decoration: none; transition: color 0.2s;" onmouseover="this.style.color='#fff'" onmouseout="this.style.color='rgba(255,255,255,0.5)'">Terms of Service</a>
            </div>
        </div>
    </div>
</footer>

<!-- ═══ WA FLOATING ═══ -->
<?php if(!empty($wa_number)): ?>
    <a href="https://wa.me/<?= $wa_number ?>?text=Assalamualaikum%20admin%20<?= urlencode($nama_madrasah) ?>"
       class="web-floating-wa" target="_blank" title="Hubungi WhatsApp">
        <i class="bi bi-whatsapp"></i>
    </a>
<?php endif; ?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    /* Navbar scroll */
    const nav = document.getElementById('mainNav');
    if(nav) {
        window.addEventListener('scroll', function() {
            nav.classList.toggle('scrolled', window.scrollY > 10);
        });
    }

    /* Smooth scroll */
    document.querySelectorAll('a[href^="#"]').forEach(function(a) {
        a.addEventListener('click', function(e) {
            const id = this.getAttribute('href');
            if(id === '#') return;
            const el = document.querySelector(id);
            if(el) {
                e.preventDefault();
                const offset = (nav ? nav.offsetHeight : 64) + 16;
                window.scrollTo({ top: el.getBoundingClientRect().top + window.pageYOffset - offset, behavior: 'smooth' });
            }
        });
    });

    /* Reveal on scroll */
    const reveals = document.querySelectorAll('.reveal');
    if('IntersectionObserver' in window) {
        const observer = new IntersectionObserver(function(entries) {
            entries.forEach(function(entry) {
                if(entry.isIntersecting) { entry.target.classList.add('visible'); observer.unobserve(entry.target); }
            });
        }, { threshold: 0.12, rootMargin: '0px 0px -40px 0px' });
        reveals.forEach(function(el) { observer.observe(el); });
    } else {
        reveals.forEach(function(el) { el.classList.add('visible'); });
    }
});
</script>

</body>
</html>
