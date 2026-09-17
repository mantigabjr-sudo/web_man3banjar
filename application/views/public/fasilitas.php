<?php $this->load->view('public/partials/archive_header'); ?>

<header class="web-archive-hero">
    <div class="container">
        <div class="detail-breadcrumb">
            <a href="<?= base_url() ?>"><i class="bi bi-house-door"></i> Beranda</a>
            <span>/</span>
            <strong>Profil Madrasah</strong>
            <span>/</span>
            <strong>Fasilitas</strong>
        </div>
        <h1>Fasilitas Madrasah</h1>
        <p>Sarana, prasarana, dan ruang belajar modern pendukung prestasi <?= htmlspecialchars($nama_madrasah ?? 'MAN 3 Banjar', ENT_QUOTES, 'UTF-8') ?></p>
    </div>
</header>

<?php 
$active_profil_tab = 'fasilitas'; 
$this->load->view('public/partials/subnav_profil', ['active_profil_tab' => $active_profil_tab]); 
?>

<section class="web-section" style="background: #f8fafc; padding: 20px 0 80px 0;">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <div class="row g-4 reveal">
                    <?php if(!empty($fasilitas_items)): ?>
                        <?php foreach($fasilitas_items as $index => $item): 
                            $colors = [
                                ['bg' => '#ecfdf5', 'icon' => '#059669', 'badge' => 'Fasilitas Unggulan'],
                                ['bg' => '#eff6ff', 'icon' => '#2563eb', 'badge' => 'Sarana Akademik'],
                                ['bg' => '#fef2f2', 'icon' => '#dc2626', 'badge' => 'Aktivitas Siswa'],
                                ['bg' => '#fffbeb', 'icon' => '#d97706', 'badge' => 'Ruang Kolaborasi'],
                                ['bg' => '#f5f3ff', 'icon' => '#7c3aed', 'badge' => 'Pusat Kreativitas']
                            ];
                            $color = $colors[$index % count($colors)];
                            $icon_list = ['bi-building', 'bi-book-half', 'bi-pc-display', 'bi-dribbble', 'bi-heart-pulse', 'bi-mic-fill', 'bi-palette', 'bi-wifi', 'bi-trophy'];
                            $icon = $icon_list[$index % count($icon_list)];
                        ?>
                            <div class="col-md-6 col-lg-4">
                                <div class="web-content-card h-100 p-4 text-center d-flex flex-column align-items-center justify-content-center">
                                    <div style="width: 72px; height: 72px; border-radius: 20px; background: <?= $color['bg'] ?>; display: inline-flex; align-items: center; justify-content: center; margin-bottom: 18px; transition: transform 0.3s ease;">
                                        <i class="bi <?= $icon ?>" style="font-size: 32px; color: <?= $color['icon'] ?>;"></i>
                                    </div>
                                    <h5 style="font-weight: 700; color: #0f172a; margin-bottom: 8px; font-size: 1.1rem; line-height: 1.4;"><?= web_clean($item) ?></h5>
                                    <span class="badge bg-light text-secondary border px-2.5 py-1" style="font-size: 11px; font-weight: 600; border-radius: 12px;"><?= $color['badge'] ?></span>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <div class="col-12 text-center py-5">
                            <div class="web-content-card p-5 mx-auto" style="max-width: 500px;">
                                <div style="width: 80px; height: 80px; border-radius: 50%; background: #f1f5f9; display: inline-flex; align-items: center; justify-content: center; margin-bottom: 20px;">
                                    <i class="bi bi-inbox-fill" style="font-size: 36px; color: #94a3b8;"></i>
                                </div>
                                <h4 style="color: #334155; font-weight: 700;">Data Fasilitas Belum Tersedia</h4>
                                <p style="color: #64748b; margin-bottom: 0;">Fasilitas madrasah belum ditambahkan ke dalam sistem.</p>
                            </div>
                        </div>
                    <?php endif; ?>
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
