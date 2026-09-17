<?php $this->load->view('public/partials/archive_header'); ?>

<header class="web-archive-hero">
    <div class="container">
        <div class="detail-breadcrumb">
            <a href="<?= base_url() ?>"><i class="bi bi-house-door"></i> Beranda</a>
            <span>/</span>
            <strong>Profil Madrasah</strong>
            <span>/</span>
            <strong>Sejarah</strong>
        </div>
        <h1>Sejarah Madrasah</h1>
        <p>Jejak langkah, dedikasi, dan perjalanan pengabdian <?= htmlspecialchars($nama_madrasah ?? 'MAN 3 Banjar', ENT_QUOTES, 'UTF-8') ?></p>
    </div>
</header>

<?php 
$active_profil_tab = 'sejarah'; 
$this->load->view('public/partials/subnav_profil', ['active_profil_tab' => $active_profil_tab]); 
?>

<section class="web-section" style="background: #f8fafc; padding: 20px 0 80px 0;">
    <div class="container">
        <div class="row justify-content-center reveal">
            <div class="col-lg-10">
                <div class="web-content-card p-4 p-md-5">
                    <div style="display: flex; align-items: center; gap: 18px; margin-bottom: 30px; border-bottom: 2px solid #f1f5f9; padding-bottom: 20px;">
                        <div style="width: 58px; height: 58px; border-radius: 16px; background: #ecfdf5; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                            <i class="bi bi-clock-history" style="font-size: 28px; color: #059669;"></i>
                        </div>
                        <div>
                            <span class="badge bg-emerald-subtle text-success px-3 py-1 mb-1" style="border-radius: 20px; font-weight: 700; font-size: 11.5px; background: #dcfce7; color: #166534 !important;">Kilas Balik Sejarah</span>
                            <h2 style="font-weight: 800; color: #0f172a; margin: 0; font-size: clamp(1.4rem, 2.5vw, 1.8rem);">Perjalanan Pengabdian Kami</h2>
                        </div>
                    </div>
                    
                    <div style="color: #334155; line-height: 2; font-size: 15.5px; text-align: justify;">
                        <?= !empty($profil_website->sejarah) ? nl2br(web_clean($profil_website->sejarah)) : 'Sejarah madrasah belum diisi.' ?>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="row justify-content-center reveal mt-4">
            <div class="col-lg-10">
                <div class="row g-4">
                    <div class="col-md-6">
                        <div class="web-content-card h-100 p-4 text-center">
                            <div style="width: 60px; height: 60px; border-radius: 16px; background: #fef3c7; display: inline-flex; align-items: center; justify-content: center; margin-bottom: 16px;">
                                <i class="bi bi-award-fill" style="font-size: 30px; color: #d97706;"></i>
                            </div>
                            <h4 style="font-weight: 700; color: #0f172a; margin-bottom: 8px;">Kualitas Terbaik</h4>
                            <p style="color: #64748b; margin: 0; font-size: 14px; line-height: 1.6;">Berkomitmen memberikan layanan pendidikan madrasah bermutu tinggi dengan dedikasi penuh.</p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="web-content-card h-100 p-4 text-center">
                            <div style="width: 60px; height: 60px; border-radius: 16px; background: #eff6ff; display: inline-flex; align-items: center; justify-content: center; margin-bottom: 16px;">
                                <i class="bi bi-people-fill" style="font-size: 30px; color: #2563eb;"></i>
                            </div>
                            <h4 style="font-weight: 700; color: #0f172a; margin-bottom: 8px;">Generasi Unggul</h4>
                            <p style="color: #64748b; margin: 0; font-size: 14px; line-height: 1.6;">Membentuk generasi pembelajar yang berakhlak mulia, cerdas, berprestasi, dan berwawasan masa depan.</p>
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
