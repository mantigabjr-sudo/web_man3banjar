<?php $this->load->view('public/partials/archive_header'); ?>

<header class="web-archive-hero">
    <div class="container">
        <div class="detail-breadcrumb">
            <a href="<?= base_url() ?>"><i class="bi bi-house-door"></i> Beranda</a>
            <span>/</span>
            <strong>Fasilitas</strong>
        </div>
        <h1>Fasilitas Madrasah</h1>
        <p>Sarana dan prasarana pendukung kegiatan belajar mengajar.</p>
    </div>
</header>

<section class="web-section" style="background: #f8fafc; padding: 80px 0;">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <div class="row g-4 reveal">
                    <?php if(!empty($fasilitas_items)): ?>
                        <?php foreach($fasilitas_items as $index => $item): 
                            $colors = [
                                ['bg' => '#ecfdf5', 'icon' => '#10b981', 'border' => '#a7f3d0'],
                                ['bg' => '#eff6ff', 'icon' => '#3b82f6', 'border' => '#bfdbfe'],
                                ['bg' => '#fef2f2', 'icon' => '#ef4444', 'border' => '#fecaca'],
                                ['bg' => '#fffbeb', 'icon' => '#f59e0b', 'border' => '#fde68a'],
                                ['bg' => '#f5f3ff', 'icon' => '#8b5cf6', 'border' => '#ddd6fe']
                            ];
                            $color = $colors[$index % count($colors)];
                            $icon_list = ['bi-building', 'bi-book-half', 'bi-pc-display', 'bi-dribbble', 'bi-heart-pulse', 'bi-mic-fill', 'bi-palette'];
                            $icon = $icon_list[$index % count($icon_list)];
                        ?>
                            <div class="col-md-6 col-lg-4">
                                <div class="card border-0 h-100" style="border-radius: 16px; background: #ffffff; transition: all 0.3s ease; border: 1px solid #e2e8f0; cursor: pointer; box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05), 0 2px 4px -1px rgba(0, 0, 0, 0.03);" onmouseover="this.style.transform='translateY(-8px)'; this.style.boxShadow='0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04)'; this.style.borderColor='<?= $color['border'] ?>'" onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 4px 6px -1px rgba(0, 0, 0, 0.05), 0 2px 4px -1px rgba(0, 0, 0, 0.03)'; this.style.borderColor='#e2e8f0'">
                                    <div class="card-body p-4 text-center">
                                        <div style="width: 70px; height: 70px; border-radius: 50%; background: <?= $color['bg'] ?>; display: inline-flex; align-items: center; justify-content: center; margin-bottom: 20px; transition: transform 0.3s ease;">
                                            <i class="bi <?= $icon ?>" style="font-size: 30px; color: <?= $color['icon'] ?>;"></i>
                                        </div>
                                        <h5 style="font-weight: 700; color: #1e293b; margin-bottom: 12px; font-size: 1.1rem; line-height: 1.4;"><?= web_clean($item) ?></h5>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <div class="col-12 text-center py-5">
                            <div style="width: 80px; height: 80px; border-radius: 50%; background: #f1f5f9; display: inline-flex; align-items: center; justify-content: center; margin-bottom: 20px;">
                                <i class="bi bi-inbox-fill" style="font-size: 36px; color: #94a3b8;"></i>
                            </div>
                            <h4 style="color: #475569; font-weight: 600;">Data Fasilitas Belum Tersedia</h4>
                            <p style="color: #64748b;">Fasilitas madrasah belum ditambahkan ke dalam sistem.</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</section>

<style>
.card:hover .bi { transform: scale(1.1); transition: transform 0.3s ease; }
</style>

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
