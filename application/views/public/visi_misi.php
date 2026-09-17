<?php $this->load->view('public/partials/archive_header'); ?>

<header class="web-archive-hero">
    <div class="container">
        <div class="detail-breadcrumb">
            <a href="<?= base_url() ?>"><i class="bi bi-house-door"></i> Beranda</a>
            <span>/</span>
            <strong>Profil Madrasah</strong>
            <span>/</span>
            <strong>Visi &amp; Misi</strong>
        </div>
        <h1>Visi, Misi &amp; Tujuan</h1>
        <p>Arah, landasan pengembangan karakter, dan cita-cita luhur <?= htmlspecialchars($nama_madrasah ?? 'MAN 3 Banjar', ENT_QUOTES, 'UTF-8') ?></p>
    </div>
</header>

<?php 
$active_profil_tab = 'visi_misi'; 
$this->load->view('public/partials/subnav_profil', ['active_profil_tab' => $active_profil_tab]); 
?>

<section class="web-section" style="background: #f8fafc; padding: 20px 0 80px 0;">
    <div class="container">
        
        <!-- VISI -->
        <div class="row justify-content-center reveal mb-5">
            <div class="col-lg-10">
                <div class="web-content-card p-4 p-md-5 text-center" style="border-top: 5px solid #059669 !important; background: #ffffff;">
                    <div style="width: 74px; height: 74px; border-radius: 50%; background: #ecfdf5; display: inline-flex; align-items: center; justify-content: center; margin-bottom: 20px;">
                        <i class="bi bi-compass" style="font-size: 34px; color: #059669;"></i>
                    </div>
                    <span class="d-block text-uppercase fw-bold text-success mb-1" style="font-size: 13px; letter-spacing: 1.5px;">Visi Utama Madrasah</span>
                    <h2 style="font-weight: 900; color: #0f172a; margin-bottom: 24px; font-size: clamp(1.6rem, 3vw, 2.2rem);">VISI</h2>
                    <div style="color: #1e293b; line-height: 2; font-size: clamp(17px, 2vw, 20px); font-weight: 600; font-style: italic; max-width: 850px; margin: 0 auto;">
                        "<?= !empty($profil_website->visi) ? nl2br(web_clean($profil_website->visi)) : 'Visi madrasah belum diisi.' ?>"
                    </div>
                </div>
            </div>
        </div>

        <!-- MISI & TUJUAN -->
        <div class="row justify-content-center reveal">
            <div class="col-lg-10">
                <div class="row g-4">
                    <div class="col-md-6">
                        <div class="web-content-card h-100 p-4 p-md-5">
                            <div style="display: flex; align-items: center; gap: 16px; margin-bottom: 24px; border-bottom: 2px solid #f1f5f9; padding-bottom: 16px;">
                                <div style="width: 50px; height: 50px; border-radius: 14px; background: #eff6ff; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                                    <i class="bi bi-bullseye" style="font-size: 24px; color: #2563eb;"></i>
                                </div>
                                <div>
                                    <span class="badge bg-primary-subtle text-primary px-2.5 py-1 mb-1" style="border-radius: 12px; font-size: 11px; font-weight: 700;">Langkah Strategis</span>
                                    <h3 style="font-weight: 800; color: #0f172a; margin: 0; font-size: 1.35rem;">MISI</h3>
                                </div>
                            </div>
                            <div style="color: #475569; line-height: 2; font-size: 15px;">
                                <?= !empty($profil_website->misi) ? nl2br(web_clean($profil_website->misi)) : 'Misi madrasah belum diisi.' ?>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="web-content-card h-100 p-4 p-md-5">
                            <div style="display: flex; align-items: center; gap: 16px; margin-bottom: 24px; border-bottom: 2px solid #f1f5f9; padding-bottom: 16px;">
                                <div style="width: 50px; height: 50px; border-radius: 14px; background: #fef3c7; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                                    <i class="bi bi-flag-fill" style="font-size: 24px; color: #d97706;"></i>
                                </div>
                                <div>
                                    <span class="badge bg-warning-subtle text-warning-emphasis px-2.5 py-1 mb-1" style="border-radius: 12px; font-size: 11px; font-weight: 700;">Sasaran Capaian</span>
                                    <h3 style="font-weight: 800; color: #0f172a; margin: 0; font-size: 1.35rem;">TUJUAN</h3>
                                </div>
                            </div>
                            <div style="color: #475569; line-height: 2; font-size: 15px;">
                                <?= !empty($profil_website->tujuan) ? nl2br(web_clean($profil_website->tujuan)) : 'Tujuan madrasah belum diisi.' ?>
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
