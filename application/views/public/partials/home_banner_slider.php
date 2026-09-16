<?php
if(empty($banner_slider)){
    return;
}
?>

<!-- ═══ BANNER SLIDER BERANDA ═══ -->
<section class="web-banner-slider-section py-3 py-lg-4" style="background: #f8fafc;">
    <div class="container">
        <div id="homeBannerCarousel" class="carousel slide carousel-fade shadow-sm overflow-hidden" data-bs-ride="carousel" data-bs-interval="5000" style="border-radius: 24px; border: 1px solid #e2e8f0;">
            
            <!-- Indicators -->
            <?php if(count($banner_slider) > 1): ?>
                <div class="carousel-indicators mb-3">
                    <?php foreach($banner_slider as $idx => $b): ?>
                        <button type="button" 
                                data-bs-target="#homeBannerCarousel" 
                                data-bs-slide-to="<?= $idx ?>" 
                                class="<?= $idx === 0 ? 'active' : '' ?>" 
                                aria-current="<?= $idx === 0 ? 'true' : 'false' ?>" 
                                aria-label="Slide <?= $idx + 1 ?>"
                                style="width: 28px; height: 5px; border-radius: 4px; border: none; transition: all 0.3s ease;"></button>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>

            <!-- Slides -->
            <div class="carousel-inner">
                <?php foreach($banner_slider as $idx => $b): ?>
                    <?php
                    $banner_img = base_url('assets/banner/'.$b->gambar);
                    ?>
                    <div class="carousel-item <?= $idx === 0 ? 'active' : '' ?>" style="background-color: #064e3b; min-height: 280px;">
                        <div class="position-relative" style="max-height: 480px; overflow: hidden;">
                            <img src="<?= $banner_img ?>" 
                                 class="d-block w-100" 
                                 alt="<?= htmlspecialchars($b->judul ?? 'Banner Madrasah', ENT_QUOTES, 'UTF-8') ?>"
                                 style="width: 100%; height: clamp(280px, 42vw, 480px); object-fit: cover; object-position: center;">
                            
                            <!-- Gradient Overlay -->
                            <div class="position-absolute top-0 start-0 w-100 h-100 d-flex align-items-end p-4 p-md-5" 
                                 style="background: linear-gradient(180deg, rgba(6,78,59,0.1) 0%, rgba(15,23,42,0.4) 40%, rgba(15,23,42,0.85) 100%);">
                                <div class="text-white" style="max-width: 780px;">
                                    <?php if(!empty($b->subjudul)): ?>
                                        <span class="badge bg-success text-white fw-bold px-3 py-1 rounded-pill mb-2 shadow-sm" style="font-size: 12px; letter-spacing: 0.5px;">
                                            <?= htmlspecialchars($b->subjudul, ENT_QUOTES, 'UTF-8') ?>
                                        </span>
                                    <?php endif; ?>
                                    
                                    <h2 class="fw-bold mb-2 text-white" style="font-size: clamp(1.3rem, 2.5vw, 2.2rem); text-shadow: 0 2px 4px rgba(0,0,0,0.4); line-height: 1.25;">
                                        <?= htmlspecialchars($b->judul ?? '', ENT_QUOTES, 'UTF-8') ?>
                                    </h2>

                                    <?php if(!empty($b->deskripsi)): ?>
                                        <p class="mb-3 text-white-50 d-none d-md-block" style="font-size: 14.5px; line-height: 1.5; max-width: 650px;">
                                            <?= htmlspecialchars($b->deskripsi, ENT_QUOTES, 'UTF-8') ?>
                                        </p>
                                    <?php endif; ?>

                                    <?php if(!empty($b->button_text) && !empty($b->button_url)): ?>
                                        <div class="pt-1">
                                            <a href="<?= htmlspecialchars($b->button_url, ENT_QUOTES, 'UTF-8') ?>" 
                                               class="btn btn-success fw-bold rounded-pill px-4 py-2 shadow-sm d-inline-flex align-items-center gap-2"
                                               style="background: #10b981; border: none; font-size: 13.5px;">
                                                <span><?= htmlspecialchars($b->button_text, ENT_QUOTES, 'UTF-8') ?></span>
                                                <i class="bi bi-arrow-right"></i>
                                            </a>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>

            <!-- Controls -->
            <?php if(count($banner_slider) > 1): ?>
                <button class="carousel-control-prev" type="button" data-bs-target="#homeBannerCarousel" data-bs-slide="prev" style="width: 50px; opacity: 0.85;">
                    <span class="d-flex align-items-center justify-content-center bg-dark bg-opacity-50 text-white rounded-circle" style="width: 40px; height: 40px; backdrop-filter: blur(4px);">
                        <i class="bi bi-chevron-left fs-5"></i>
                    </span>
                    <span class="visually-hidden">Sebelumnya</span>
                </button>
                <button class="carousel-control-next" type="button" data-bs-target="#homeBannerCarousel" data-bs-slide="next" style="width: 50px; opacity: 0.85;">
                    <span class="d-flex align-items-center justify-content-center bg-dark bg-opacity-50 text-white rounded-circle" style="width: 40px; height: 40px; backdrop-filter: blur(4px);">
                        <i class="bi bi-chevron-right fs-5"></i>
                    </span>
                    <span class="visually-hidden">Berikutnya</span>
                </button>
            <?php endif; ?>

        </div>
    </div>
</section>
