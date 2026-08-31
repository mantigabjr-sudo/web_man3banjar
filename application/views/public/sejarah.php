<?php $this->load->view('public/partials/archive_header'); ?>

<header class="web-archive-hero">
    <div class="container">
        <div class="detail-breadcrumb">
            <a href="<?= base_url() ?>"><i class="bi bi-house-door"></i> Beranda</a>
            <span>/</span>
            <strong>Sejarah</strong>
        </div>
        <h1>Sejarah Madrasah</h1>
        <p><?= htmlspecialchars($nama_madrasah ?? 'MAN 3 Banjar', ENT_QUOTES, 'UTF-8') ?></p>
    </div>
</header>

<section class="web-section" style="background: #f8fafc; padding: 80px 0;">
    <div class="container">
        <div class="row justify-content-center reveal">
            <div class="col-lg-10">
                <div class="card border-0 shadow-lg" style="border-radius: 20px; overflow:hidden; transform: translateY(-40px); background: #ffffff;">
                    <div class="card-body p-4 p-md-5 p-lg-5">
                        <div style="display: flex; align-items: center; gap: 16px; margin-bottom: 30px;">
                            <div style="width: 60px; height: 60px; border-radius: 16px; background: #ecfdf5; display: flex; align-items: center; justify-content: center;">
                                <i class="bi bi-clock-history" style="font-size: 28px; color: #10b981;"></i>
                            </div>
                            <h2 style="font-weight: 800; color: #1e293b; margin: 0;">Perjalanan Kami</h2>
                        </div>
                        
                        <div style="color: #475569; line-height: 2; font-size: 16px; text-align: justify;">
                            <?= !empty($profil_website->sejarah) ? nl2br(web_clean($profil_website->sejarah)) : 'Sejarah madrasah belum diisi.' ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="row justify-content-center reveal mt-4">
            <div class="col-lg-10">
                <div class="row g-4">
                    <div class="col-md-6">
                        <div class="card border-0 shadow-sm h-100" style="border-radius: 16px; transition: transform 0.3s ease; cursor: pointer;" onmouseover="this.style.transform='translateY(-5px)'" onmouseout="this.style.transform='translateY(0)'">
                            <div class="card-body p-4 text-center">
                                <i class="bi bi-award-fill" style="font-size: 40px; color: #f59e0b; margin-bottom: 16px; display: inline-block;"></i>
                                <h4 style="font-weight: 700; color: #1e293b;">Kualitas Terbaik</h4>
                                <p style="color: #64748b; margin: 0;">Berkomitmen memberikan pendidikan berkualitas dengan dedikasi tinggi.</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card border-0 shadow-sm h-100" style="border-radius: 16px; transition: transform 0.3s ease; cursor: pointer;" onmouseover="this.style.transform='translateY(-5px)'" onmouseout="this.style.transform='translateY(0)'">
                            <div class="card-body p-4 text-center">
                                <i class="bi bi-people-fill" style="font-size: 40px; color: #3b82f6; margin-bottom: 16px; display: inline-block;"></i>
                                <h4 style="font-weight: 700; color: #1e293b;">Generasi Unggul</h4>
                                <p style="color: #64748b; margin: 0;">Mencetak generasi penerus bangsa yang berprestasi dan berwawasan luas.</p>
                            </div>
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
