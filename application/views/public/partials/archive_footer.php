<?php
$nama_madrasah = $nama_madrasah ?? 'MAN 3 Banjar';

if(!function_exists('archive_footer_clean')){
    function archive_footer_clean($text){
        return htmlspecialchars((string)$text, ENT_QUOTES, 'UTF-8');
    }
}

if(!function_exists('archive_footer_limit')){
    function archive_footer_limit($text, $limit = 180){
        $text = trim(strip_tags((string)$text));

        if(function_exists('mb_strimwidth')){
            return mb_strimwidth($text, 0, $limit, '...');
        }

        return strlen($text) > $limit ? substr($text, 0, $limit).'...' : $text;
    }
}
?>

<footer class="web-footer" style="background: var(--c-emerald-950); color: rgba(255,255,255,.6); padding: 60px 0 20px; font-size: 13px;">
    <div class="container">
        <div class="row g-4 mb-5">
            <!-- Kolom 1: Logo & Deskripsi -->
            <div class="col-lg-3 col-md-6">
                <div class="d-flex align-items-center gap-3 mb-3">
                    <div style="width: 56px; height: 56px; border-radius: 50%; border: 2px solid var(--c-emerald-400); background: #fff; padding: 4px; display: flex; align-items: center; justify-content: center; flex-shrink: 0; overflow: hidden;">
                        <?php if(file_exists(FCPATH.'assets/img/logo-madrasah.png')): ?>
                            <img src="<?= base_url('assets/img/logo-madrasah.png') ?>" alt="Logo" style="width: 100%; height: 100%; object-fit: contain;">
                        <?php else: ?>
                            <strong style="color: var(--c-emerald-900); font-size: 16px;">M3</strong>
                        <?php endif; ?>
                    </div>
                    <div>
                        <h4 style="font-weight: 800; font-size: 14px; letter-spacing: 0.5px; color: #fff; margin: 0; line-height: 1.2;">MADRASAH ALIYAH</h4>
                        <h3 style="font-weight: 900; font-size: 18px; color: var(--c-emerald-400); margin: 0; line-height: 1.2;">NEGERI 3 BANJAR</h3>
                        <small style="font-size: 11px; display: block; color: rgba(255,255,255,0.5);">Kabupaten Banjar</small>
                    </div>
                </div>
                <p style="line-height: 1.7; font-size: 13px; text-align: justify; margin: 0;">
                    <?= !empty($profil_website->isi_profil) ? strip_tags($profil_website->isi_profil) : 'Terwujudnya Madrasah Model Sebagai Pusat Keunggulan dan Rujukan Dalam Kualitas Akademik dan Non Akademik Serta Akhlaq Karimah' ?>
                </p>
            </div>

            <!-- Kolom 2: Tautan Cepat -->
            <div class="col-lg-3 col-md-6">
                <h5 style="color: #fff; font-weight: 700; font-size: 15px; margin-bottom: 20px; display: flex; align-items: center; gap: 8px;">
                    <i class="bi bi-link-45deg text-success" style="font-size: 18px;"></i> Tautan Cepat
                </h5>
                <ul class="list-unstyled" style="display: flex; flex-direction: column; gap: 12px; margin: 0;">
                    <li><a href="<?= base_url('website/sejarah') ?>" style="color: rgba(255,255,255,0.7); text-decoration: none; transition: all 0.2s; display: flex; align-items: center; gap: 8px;" onmouseover="this.style.color='#fff'; this.style.paddingLeft='5px'" onmouseout="this.style.color='rgba(255,255,255,0.7)'; this.style.paddingLeft='0'"><i class="bi bi-chevron-right" style="font-size: 10px;"></i> Sejarah</a></li>
                    <li><a href="<?= base_url('website/visi_misi') ?>" style="color: rgba(255,255,255,0.7); text-decoration: none; transition: all 0.2s; display: flex; align-items: center; gap: 8px;" onmouseover="this.style.color='#fff'; this.style.paddingLeft='5px'" onmouseout="this.style.color='rgba(255,255,255,0.7)'; this.style.paddingLeft='0'"><i class="bi bi-chevron-right" style="font-size: 10px;"></i> Visi & Misi</a></li>
                    <li><a href="<?= base_url('website/fasilitas') ?>" style="color: rgba(255,255,255,0.7); text-decoration: none; transition: all 0.2s; display: flex; align-items: center; gap: 8px;" onmouseover="this.style.color='#fff'; this.style.paddingLeft='5px'" onmouseout="this.style.color='rgba(255,255,255,0.7)'; this.style.paddingLeft='0'"><i class="bi bi-chevron-right" style="font-size: 10px;"></i> Fasilitas</a></li>
                    <li><a href="<?= base_url('website/portal') ?>" style="color: rgba(255,255,255,0.7); text-decoration: none; transition: all 0.2s; display: flex; align-items: center; gap: 8px;" onmouseover="this.style.color='#fff'; this.style.paddingLeft='5px'" onmouseout="this.style.color='rgba(255,255,255,0.7)'; this.style.paddingLeft='0'"><i class="bi bi-chevron-right" style="font-size: 10px;"></i> Portal Berita</a></li>
                </ul>
            </div>

            <!-- Kolom 3: Hubungi Kami -->
            <div class="col-lg-3 col-md-6">
                <h5 style="color: #fff; font-weight: 700; font-size: 15px; margin-bottom: 20px; display: flex; align-items: center; gap: 8px;">
                    <i class="bi bi-telephone-fill text-success" style="font-size: 18px;"></i> Hubungi Kami
                </h5>
                <div style="display: flex; flex-direction: column; gap: 15px;">
                    <div style="display: flex; gap: 12px; align-items: flex-start;">
                        <i class="bi bi-geo-alt" style="color: var(--c-emerald-400); font-size: 16px; margin-top: 2px;"></i>
                        <span style="font-size: 13px; line-height: 1.6; color: rgba(255,255,255,0.8);"><?= !empty($profil_website->alamat) ? archive_footer_clean($profil_website->alamat) : 'Alamat belum diatur' ?></span>
                    </div>
                    <div style="display: flex; gap: 12px; align-items: flex-start;">
                        <i class="bi bi-telephone" style="color: var(--c-emerald-400); font-size: 16px; margin-top: 2px;"></i>
                        <span style="font-size: 14px; font-weight: 600; color: rgba(255,255,255,0.8);"><?= !empty($profil_website->telepon) ? archive_footer_clean($profil_website->telepon) : '-' ?></span>
                    </div>
                    <div style="display: flex; gap: 12px; align-items: flex-start;">
                        <i class="bi bi-envelope" style="color: var(--c-emerald-400); font-size: 16px; margin-top: 2px;"></i>
                        <a href="mailto:<?= !empty($profil_website->email) ? archive_footer_clean($profil_website->email) : '' ?>" style="color: rgba(255,255,255,0.8); font-size: 14px; font-weight: 600; text-decoration: none; transition: color 0.2s;"><?= !empty($profil_website->email) ? archive_footer_clean($profil_website->email) : '-' ?></a>
                    </div>
                </div>

                <!-- Social Media -->
                <div class="d-flex gap-2 mt-4">
                    <?php if(!empty($profil_website->facebook_url)): ?>
                        <a href="<?= archive_footer_clean($profil_website->facebook_url) ?>" target="_blank" style="width: 32px; height: 32px; border-radius: 6px; background: rgba(255,255,255,0.1); display: flex; align-items: center; justify-content: center; color: #fff; text-decoration: none; transition: all 0.2s;" onmouseover="this.style.background='var(--c-emerald-500)'" onmouseout="this.style.background='rgba(255,255,255,0.1)'">
                            <i class="bi bi-facebook" style="font-size: 14px;"></i>
                        </a>
                    <?php endif; ?>
                    <?php if(!empty($profil_website->instagram_url)): ?>
                        <a href="<?= archive_footer_clean($profil_website->instagram_url) ?>" target="_blank" style="width: 32px; height: 32px; border-radius: 6px; background: rgba(255,255,255,0.1); display: flex; align-items: center; justify-content: center; color: #fff; text-decoration: none; transition: all 0.2s;" onmouseover="this.style.background='var(--c-emerald-500)'" onmouseout="this.style.background='rgba(255,255,255,0.1)'">
                            <i class="bi bi-instagram" style="font-size: 14px;"></i>
                        </a>
                    <?php endif; ?>
                    <?php if(!empty($profil_website->youtube_url)): ?>
                        <a href="<?= archive_footer_clean($profil_website->youtube_url) ?>" target="_blank" style="width: 32px; height: 32px; border-radius: 6px; background: rgba(255,255,255,0.1); display: flex; align-items: center; justify-content: center; color: #fff; text-decoration: none; transition: all 0.2s;" onmouseover="this.style.background='var(--c-emerald-500)'" onmouseout="this.style.background='rgba(255,255,255,0.1)'">
                            <i class="bi bi-youtube" style="font-size: 14px;"></i>
                        </a>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Kolom 4: Lokasi Kami (Maps) -->
            <div class="col-lg-3 col-md-6">
                <h5 style="color: #fff; font-weight: 700; font-size: 15px; margin-bottom: 20px; display: flex; align-items: center; gap: 8px;">
                    <i class="bi bi-geo-alt-fill text-success" style="font-size: 18px;"></i> Peta Lokasi
                </h5>
                <?php if(!empty($profil_website->maps_embed_url)): ?>
                    <div style="border-radius: 12px; overflow: hidden; height: 180px; border: 1px solid rgba(255,255,255,0.1); background: rgba(0,0,0,0.2);">
                        <iframe src="<?= archive_footer_clean($profil_website->maps_embed_url) ?>" style="width: 100%; height: 100%; border: 0;" allowfullscreen="" loading="lazy"></iframe>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <div style="border-top: 1px solid rgba(255,255,255,0.1); padding-top: 20px; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 12px; font-size: 12px; color: rgba(255,255,255,0.5);">
            <span>© <?= date('Y') ?> <?= $nama_madrasah ?>. All rights reserved.</span>
            <div class="d-flex gap-3">
                <a href="#" style="color: rgba(255,255,255,0.5); text-decoration: none; transition: color 0.2s;" onmouseover="this.style.color='#fff'" onmouseout="this.style.color='rgba(255,255,255,0.5)'">Privacy Policy</a>
                <span>|</span>
                <a href="#" style="color: rgba(255,255,255,0.5); text-decoration: none; transition: color 0.2s;" onmouseover="this.style.color='#fff'" onmouseout="this.style.color='rgba(255,255,255,0.5)'">Terms of Service</a>
            </div>
        </div>
    </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>