<?php $this->load->view('public/partials/archive_header'); ?>

<header class="web-archive-hero">
    <div class="container">
        <div class="detail-breadcrumb justify-content-center" style="margin-bottom: 16px;">
            <a href="<?= base_url() ?>"><i class="bi bi-house-door"></i> Beranda</a>
            <span>/</span>
            <strong>Profil Madrasah</strong>
        </div>
        <h1>Tentang Madrasah</h1>
        <p><?= htmlspecialchars($nama_madrasah ?? 'MAN 3 Banjar', ENT_QUOTES, 'UTF-8') ?></p>
    </div>
</header>

<section class="web-section" id="visi-misi" style="background: #ffffff; padding: 60px 0;">
    <div class="container">
        <div class="web-section-head reveal">
            <span class="web-section-label"><i class="bi bi-compass"></i> Visi dan Misi</span>
            <h2>Arah dan Tujuan Madrasah</h2>
            <p>Dasar pengembangan pendidikan, karakter, dan layanan madrasah.</p>
        </div>

        <div class="web-vision-grid reveal">
            <div class="web-vision-card web-vision-main">
                <span>V</span>
                <h4>Visi</h4>
                <p><?= !empty($profil_website->visi) ? nl2br(web_clean($profil_website->visi)) : '-' ?></p>
            </div>
            <div class="web-vision-card">
                <span>M</span>
                <h4>Misi</h4>
                <p><?= !empty($profil_website->misi) ? nl2br(web_clean($profil_website->misi)) : '-' ?></p>
            </div>
            <div class="web-vision-card">
                <span>T</span>
                <h4>Tujuan</h4>
                <p><?= !empty($profil_website->tujuan) ? nl2br(web_clean($profil_website->tujuan)) : '-' ?></p>
            </div>
        </div>
    </div>
</section>

<section class="web-section web-soft" id="profil" style="background: #f8fafc; padding: 60px 0;">
    <div class="container">
        <div class="web-section-head reveal">
            <span class="web-section-label"><i class="bi bi-book"></i> Sejarah Madrasah</span>
            <h2>Perjalanan dan Profil Singkat</h2>
        </div>

        <div class="row g-5 reveal">
            <div class="col-lg-8">
                <div class="card border-0 shadow-sm" style="border-radius: 16px; overflow:hidden;">
                    <div class="card-body p-4 p-md-5">
                        <h3 style="font-weight: 800; color: #1e293b; margin-bottom: 24px;">Sejarah Singkat</h3>
                        <div style="color: #475569; line-height: 1.8; font-size: 15px;">
                            <?= !empty($profil_website->sejarah) ? nl2br(web_clean($profil_website->sejarah)) : 'Sejarah madrasah belum diisi.' ?>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-4">
                <?php if(!empty($profil_website->maps_embed_url)): ?>
                    <div class="card border-0 shadow-sm mb-4" style="border-radius: 16px; overflow:hidden;">
                        <div class="card-header bg-white border-0 pt-4 pb-0 px-4">
                            <h5 style="font-weight: 700;"><i class="bi bi-geo-alt-fill text-success"></i> Peta Lokasi</h5>
                        </div>
                        <div class="card-body p-4">
                            <div class="web-map-box" style="margin: 0; padding-bottom: 100%;">
                                <iframe src="<?= web_clean($profil_website->maps_embed_url) ?>" loading="lazy" referrerpolicy="no-referrer-when-downgrade" style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; border: 0; border-radius: 12px;"></iframe>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>
                
                <div class="card border-0 shadow-sm mb-4" style="border-radius: 16px; overflow:hidden;">
                    <div class="card-body p-4">
                        <h5 style="font-weight: 700; margin-bottom: 20px;"><i class="bi bi-telephone-fill text-success"></i> Kontak & Layanan</h5>
                        <ul class="list-unstyled mb-0" style="color: #475569; line-height: 2;">
                            <?php if(!empty($profil_website->alamat)): ?>
                                <li><i class="bi bi-house me-2"></i> <?= web_clean($profil_website->alamat) ?></li>
                            <?php endif; ?>
                            <?php if(!empty($profil_website->telepon)): ?>
                                <li><i class="bi bi-telephone me-2"></i> <?= web_clean($profil_website->telepon) ?></li>
                            <?php endif; ?>
                            <?php if(!empty($profil_website->email)): ?>
                                <li><i class="bi bi-envelope me-2"></i> <?= web_clean($profil_website->email) ?></li>
                            <?php endif; ?>
                            <?php if(!empty($profil_website->jam_layanan)): ?>
                                <li><i class="bi bi-clock me-2"></i> <?= web_clean($profil_website->jam_layanan) ?></li>
                            <?php endif; ?>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="web-section" id="fasilitas" style="background: #ffffff; padding: 60px 0 100px;">
    <div class="container">
        <div class="row g-5 reveal">
            <div class="col-md-6">
                <div class="web-section-head" style="text-align: left; margin-bottom: 30px;">
                    <span class="web-section-label"><i class="bi bi-buildings"></i> Fasilitas Madrasah</span>
                    <h2>Sarana Pendukung</h2>
                </div>
                <div class="row g-3">
                    <?php if(!empty($fasilitas_items)): ?>
                        <?php foreach($fasilitas_items as $item): ?>
                            <div class="col-sm-6">
                                <div style="background: #f0fdf4; border: 1px solid #bbf7d0; padding: 15px 20px; border-radius: 12px; display: flex; align-items: flex-start; gap: 12px; font-weight: 600; color: #15803d;">
                                    <i class="bi bi-check-circle-fill text-success" style="margin-top: 2px;"></i> <span><?= web_clean($item) ?></span>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <div class="col-12"><p class="text-muted">Fasilitas belum ditambahkan.</p></div>
                    <?php endif; ?>
                </div>
            </div>
            <div class="col-md-6">
                <div class="web-section-head" style="text-align: left; margin-bottom: 30px;">
                    <span class="web-section-label"><i class="bi bi-trophy"></i> Prestasi Madrasah</span>
                    <h2>Pencapaian Kami</h2>
                </div>
                <div class="row g-3">
                    <?php if(!empty($prestasi_items)): ?>
                        <?php foreach($prestasi_items as $item): ?>
                            <div class="col-sm-6">
                                <div style="background: #fffbeb; border: 1px solid #fde68a; padding: 15px 20px; border-radius: 12px; display: flex; align-items: flex-start; gap: 12px; font-weight: 600; color: #b45309;">
                                    <i class="bi bi-star-fill text-warning" style="margin-top: 2px;"></i> <span><?= web_clean($item) ?></span>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <div class="col-12"><p class="text-muted">Prestasi belum ditambahkan.</p></div>
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
