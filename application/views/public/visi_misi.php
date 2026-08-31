<?php $this->load->view('public/partials/archive_header'); ?>

<header class="web-archive-hero">
    <div class="container">
        <div class="detail-breadcrumb">
            <a href="<?= base_url() ?>"><i class="bi bi-house-door"></i> Beranda</a>
            <span>/</span>
            <strong>Visi & Misi</strong>
        </div>
        <h1>Visi, Misi & Tujuan</h1>
        <p>Arah pengembangan dan karakter <?= htmlspecialchars($nama_madrasah ?? 'MAN 3 Banjar', ENT_QUOTES, 'UTF-8') ?></p>
    </div>
</header>

<section class="web-section" style="background: #ffffff; padding: 80px 0;">
    <div class="container">
        
        <div class="row justify-content-center reveal mb-5">
            <div class="col-lg-10">
                <div class="card border-0 shadow-lg" style="border-radius: 20px; overflow:hidden; background: #f8fafc; border-top: 6px solid #10b981 !important;">
                    <div class="card-body p-4 p-md-5 text-center">
                        <div style="width: 80px; height: 80px; border-radius: 50%; background: #ecfdf5; display: inline-flex; align-items: center; justify-content: center; margin-bottom: 24px;">
                            <i class="bi bi-eye-fill" style="font-size: 36px; color: #10b981;"></i>
                        </div>
                        <h2 style="font-weight: 800; color: #1e293b; margin-bottom: 20px; font-size: 2.5rem;">VISI</h2>
                        <div style="color: #334155; line-height: 2; font-size: 20px; font-weight: 600; font-style: italic;">
                            "<?= !empty($profil_website->visi) ? nl2br(web_clean($profil_website->visi)) : 'Visi madrasah belum diisi.' ?>"
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row justify-content-center reveal mb-5">
            <div class="col-lg-5 mb-4 mb-lg-0">
                <div class="card border-0 shadow-sm h-100" style="border-radius: 20px; background: #fff; transition: all 0.3s ease; border: 1px solid #e2e8f0;" onmouseover="this.style.transform='translateY(-5px)'; this.style.boxShadow='0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04)'" onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 1px 3px 0 rgba(0, 0, 0, 0.1), 0 1px 2px 0 rgba(0, 0, 0, 0.06)'">
                    <div class="card-body p-4 p-md-5">
                        <div style="display: flex; align-items: center; gap: 16px; margin-bottom: 24px; border-bottom: 2px solid #f1f5f9; padding-bottom: 16px;">
                            <div style="width: 50px; height: 50px; border-radius: 12px; background: #eff6ff; display: flex; align-items: center; justify-content: center;">
                                <i class="bi bi-bullseye" style="font-size: 24px; color: #3b82f6;"></i>
                            </div>
                            <h3 style="font-weight: 800; color: #1e293b; margin: 0;">MISI</h3>
                        </div>
                        <div style="color: #475569; line-height: 2; font-size: 16px;">
                            <?= !empty($profil_website->misi) ? nl2br(web_clean($profil_website->misi)) : 'Misi madrasah belum diisi.' ?>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-5">
                <div class="card border-0 shadow-sm h-100" style="border-radius: 20px; background: #fff; transition: all 0.3s ease; border: 1px solid #e2e8f0;" onmouseover="this.style.transform='translateY(-5px)'; this.style.boxShadow='0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04)'" onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 1px 3px 0 rgba(0, 0, 0, 0.1), 0 1px 2px 0 rgba(0, 0, 0, 0.06)'">
                    <div class="card-body p-4 p-md-5">
                        <div style="display: flex; align-items: center; gap: 16px; margin-bottom: 24px; border-bottom: 2px solid #f1f5f9; padding-bottom: 16px;">
                            <div style="width: 50px; height: 50px; border-radius: 12px; background: #fef3c7; display: flex; align-items: center; justify-content: center;">
                                <i class="bi bi-flag-fill" style="font-size: 24px; color: #d97706;"></i>
                            </div>
                            <h3 style="font-weight: 800; color: #1e293b; margin: 0;">TUJUAN</h3>
                        </div>
                        <div style="color: #475569; line-height: 2; font-size: 16px;">
                            <?= !empty($profil_website->tujuan) ? nl2br(web_clean($profil_website->tujuan)) : 'Tujuan madrasah belum diisi.' ?>
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
