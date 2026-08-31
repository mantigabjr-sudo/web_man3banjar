<?php $this->load->view('public/partials/archive_header'); ?>

<?php
if(!function_exists('pamflet_clean')){
    function pamflet_clean($text){
        return htmlspecialchars((string)$text, ENT_QUOTES, 'UTF-8');
    }
}
?>

<header class="web-archive-hero">
    <div class="container">
        <div class="detail-breadcrumb">
            <a href="<?= base_url() ?>"><i class="bi bi-house-door"></i> Beranda</a>
            <span>/</span>
            <strong>Pamflet Informasi</strong>
        </div>
        <h1>Pamflet Informasi</h1>
        <p>Kumpulan poster, pengumuman, dan publikasi visual dari <?= htmlspecialchars($nama_madrasah ?? 'MAN 3 Banjar', ENT_QUOTES, 'UTF-8') ?>.</p>
    </div>
</header>

<section class="web-section">
    <div class="container">

        <form method="get" action="<?= base_url('website/pamflet') ?>" class="web-archive-filter">
            <div class="web-filter-grid">
                <input type="text"
                       name="q"
                       value="<?= pamflet_clean($q ?? '') ?>"
                       placeholder="Cari pamflet...">

                <input type="month"
                       name="bulan"
                       value="<?= pamflet_clean($bulan ?? '') ?>">

                <button>Cari Pamflet</button>

                <a href="<?= base_url('website/pamflet') ?>">Reset</a>
            </div>
        </form>
		<div class="web-archive-result-info">
			Menampilkan data halaman <?= (int)$current_page ?> dari total <?= (int)$total_rows ?> data.
		</div>

        <?php if(!empty($pamflet)): ?>
            <div class="web-archive-pamflet-grid">
                <?php foreach($pamflet as $p): ?>
                    <?php
                    $gambar = $p->gambar ?? '';
                    $gambar_file = !empty($gambar) ? FCPATH.'assets/pamflet/'.$gambar : '';
                    ?>

                    <a href="<?= !empty($gambar) ? base_url('assets/pamflet/'.$gambar) : '#' ?>"
                       target="_blank"
                       class="web-archive-pamflet-card">

                        <?php if(!empty($gambar) && file_exists($gambar_file)): ?>
                            <img src="<?= base_url('assets/pamflet/'.$gambar) ?>"
                                 alt="<?= pamflet_clean($p->judul ?? '') ?>">
                        <?php else: ?>
                            <div class="web-archive-image-empty">Pamflet</div>
                        <?php endif; ?>

                        <div>
                            <small><?= !empty($p->tanggal) ? date('d M Y', strtotime($p->tanggal)) : '-' ?></small>
                            <h5><?= pamflet_clean($p->judul ?? '-') ?></h5>
                            <p><?= pamflet_clean($p->deskripsi ?? '') ?></p>
                        </div>
                    </a>
                <?php endforeach; ?>
            </div>
			<?php if(!empty($pagination)): ?>
				<?= $pagination ?>
			<?php endif; ?>

        <?php else: ?>
            <div class="web-empty">
                Tidak ada pamflet yang ditemukan.
            </div>
        <?php endif; ?>

    </div>
</section>

<?php $this->load->view('public/partials/archive_footer'); ?>