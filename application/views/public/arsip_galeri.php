<?php $this->load->view('public/partials/archive_header'); ?>

<?php
if(!function_exists('galeri_clean')){
    function galeri_clean($text){
        return htmlspecialchars((string)$text, ENT_QUOTES, 'UTF-8');
    }
}
?>

<header class="web-archive-hero">
    <div class="container">
        <div class="detail-breadcrumb">
            <a href="<?= base_url() ?>"><i class="bi bi-house-door"></i> Beranda</a>
            <span>/</span>
            <strong>Galeri Madrasah</strong>
        </div>
        <h1>Galeri Madrasah</h1>
        <p>Dokumentasi kegiatan, prestasi, pembelajaran, dan suasana di <?= htmlspecialchars($nama_madrasah ?? 'MAN 3 Banjar', ENT_QUOTES, 'UTF-8') ?>.</p>
    </div>
</header>

<section class="web-section">
    <div class="container">

        <form method="get" action="<?= base_url('website/galeri') ?>" class="web-archive-filter">
            <div class="web-filter-grid">
                <input type="text"
                       name="q"
                       value="<?= galeri_clean($q ?? '') ?>"
                       placeholder="Cari galeri...">

                <input type="month"
                       name="bulan"
                       value="<?= galeri_clean($bulan ?? '') ?>">

                <button>Cari Galeri</button>

                <a href="<?= base_url('website/galeri') ?>">Reset</a>
            </div>
        </form>
		<div class="web-archive-result-info">
			Menampilkan data halaman <?= (int)$current_page ?> dari total <?= (int)$total_rows ?> data.
		</div>

        <?php if(!empty($galeri)): ?>
            <div class="web-gallery-grid">
                <?php foreach($galeri as $g): ?>
                    <?php
                    $gambar = $g->gambar ?? '';
                    $gambar_file = !empty($gambar) ? FCPATH.'assets/galeri/'.$gambar : '';
                    ?>

                    <?php if(!empty($gambar) && file_exists($gambar_file)): ?>
                        <a href="<?= base_url('assets/galeri/'.$gambar) ?>"
                           target="_blank"
                           class="web-gallery-card">

                            <img src="<?= base_url('assets/galeri/'.$gambar) ?>"
                                 alt="<?= galeri_clean($g->judul ?? '') ?>">

                            <div>
                                <small><?= !empty($g->tanggal) ? date('d M Y', strtotime($g->tanggal)) : '-' ?></small>
                                <h5><?= galeri_clean($g->judul ?? '-') ?></h5>
                            </div>
                        </a>
                    <?php endif; ?>
                <?php endforeach; ?>
            </div>
			<?php if(!empty($pagination)): ?>
				<?= $pagination ?>
			<?php endif; ?>

       <?php else: ?>
            <div class="web-empty">
                Tidak ada galeri yang ditemukan.
            </div>
        <?php endif; ?>

    </div>
</section>

<?php $this->load->view('public/partials/archive_footer'); ?>