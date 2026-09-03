<?php $this->load->view('public/partials/archive_header'); ?>

<header class="web-archive-hero">
    <div class="container">
        <div class="detail-breadcrumb">
            <a href="<?= base_url() ?>"><i class="bi bi-house-door"></i> Beranda</a>
            <span>/</span>
            <strong>Kontak</strong>
        </div>
        <h1>Hubungi Kami</h1>
        <p>Layanan Informasi, Konsultasi, Pertanyaan &amp; Pengaduan Resmi <?= htmlspecialchars($nama_madrasah ?? 'MAN 3 Banjar', ENT_QUOTES, 'UTF-8') ?></p>
    </div>
</header>

<section class="web-section" style="background: #f8fafc; padding: 60px 0 80px;">
    <div class="container">

        <!-- Notifikasi Flashdata -->
        <?php if($this->session->flashdata('success')): ?>
            <div class="alert alert-success alert-dismissible fade show shadow-sm border-0 mb-4" style="border-radius: 16px; background: #ecfdf5; color: #065f46; border-left: 5px solid #10b981 !important;" role="alert">
                <div class="d-flex align-items-center">
                    <i class="bi bi-check-circle-fill fs-4 me-3 text-success"></i>
                    <div>
                        <h6 class="fw-bold mb-0">Pesan Terkirim!</h6>
                        <small><?= $this->session->flashdata('success') ?></small>
                    </div>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>

        <?php if($this->session->flashdata('error')): ?>
            <div class="alert alert-danger alert-dismissible fade show shadow-sm border-0 mb-4" style="border-radius: 16px; background: #fef2f2; color: #991b1b; border-left: 5px solid #ef4444 !important;" role="alert">
                <div class="d-flex align-items-center">
                    <i class="bi bi-exclamation-triangle-fill fs-4 me-3 text-danger"></i>
                    <div>
                        <h6 class="fw-bold mb-0">Gagal Mengirim Pesan</h6>
                        <small><?= $this->session->flashdata('error') ?></small>
                    </div>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>

        <!-- 3 Quick Contact Info Cards -->
        <div class="row g-4 mb-5">
            <!-- 1. Alamat -->
            <div class="col-lg-4 col-md-6">
                <div class="card border-0 h-100 shadow-sm contact-info-card" style="border-radius: 20px; background: #ffffff; transition: transform 0.25s, box-shadow 0.25s;">
                    <div class="card-body p-4 p-lg-4 d-flex align-items-start gap-3">
                        <div class="contact-icon-wrap" style="width: 52px; height: 52px; border-radius: 14px; background: #ecfdf5; color: #059669; display: flex; align-items: center; justify-content: center; font-size: 24px; flex-shrink: 0;">
                            <i class="bi bi-geo-alt-fill"></i>
                        </div>
                        <div>
                            <h6 class="fw-bold mb-1" style="color: #0f172a; font-size: 16px;">Alamat Kampus</h6>
                            <p class="mb-0 text-muted" style="font-size: 13.5px; line-height: 1.5;">
                                <?= !empty($profil_website->alamat) ? htmlspecialchars($profil_website->alamat, ENT_QUOTES, 'UTF-8') : 'Jl. Pendidikan No. 1, Kab. Banjar, Kalimantan Selatan' ?>
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- 2. WhatsApp & Telepon -->
            <div class="col-lg-4 col-md-6">
                <div class="card border-0 h-100 shadow-sm contact-info-card" style="border-radius: 20px; background: #ffffff; transition: transform 0.25s, box-shadow 0.25s;">
                    <div class="card-body p-4 p-lg-4 d-flex align-items-start gap-3">
                        <div class="contact-icon-wrap" style="width: 52px; height: 52px; border-radius: 14px; background: #eff6ff; color: #2563eb; display: flex; align-items: center; justify-content: center; font-size: 24px; flex-shrink: 0;">
                            <i class="bi bi-whatsapp"></i>
                        </div>
                        <div>
                            <h6 class="fw-bold mb-1" style="color: #0f172a; font-size: 16px;">Telepon &amp; WhatsApp</h6>
                            <p class="mb-1 text-muted" style="font-size: 13.5px;">
                                <?php if(!empty($profil_website->no_telepon)): ?>
                                    <span class="d-block fw-semibold" style="color: #334155;"><i class="bi bi-telephone me-1"></i> <?= htmlspecialchars($profil_website->no_telepon, ENT_QUOTES, 'UTF-8') ?></span>
                                <?php endif; ?>
                                <?php if(!empty($profil_website->wa_number)): ?>
                                    <span class="d-block text-success fw-semibold"><i class="bi bi-whatsapp me-1"></i> <?= htmlspecialchars($profil_website->wa_number, ENT_QUOTES, 'UTF-8') ?></span>
                                <?php endif; ?>
                                <?php if(empty($profil_website->no_telepon) && empty($profil_website->wa_number)): ?>
                                    <span class="text-muted">+62 812-3456-7890</span>
                                <?php endif; ?>
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- 3. Email & Jam Layanan -->
            <div class="col-lg-4 col-md-12">
                <div class="card border-0 h-100 shadow-sm contact-info-card" style="border-radius: 20px; background: #ffffff; transition: transform 0.25s, box-shadow 0.25s;">
                    <div class="card-body p-4 p-lg-4 d-flex align-items-start gap-3">
                        <div class="contact-icon-wrap" style="width: 52px; height: 52px; border-radius: 14px; background: #faf5ff; color: #9333ea; display: flex; align-items: center; justify-content: center; font-size: 24px; flex-shrink: 0;">
                            <i class="bi bi-envelope-at-fill"></i>
                        </div>
                        <div>
                            <h6 class="fw-bold mb-1" style="color: #0f172a; font-size: 16px;">Email &amp; Jam Layanan</h6>
                            <p class="mb-0 text-muted" style="font-size: 13.5px; line-height: 1.5;">
                                <span class="d-block fw-semibold text-primary"><i class="bi bi-envelope me-1"></i> <?= !empty($profil_website->email) ? htmlspecialchars($profil_website->email, ENT_QUOTES, 'UTF-8') : 'info@man3banjar.sch.id' ?></span>
                                <span class="d-block text-secondary mt-1"><i class="bi bi-clock me-1"></i> Senin – Jumat: 07.30 – 16.00 WITA</span>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Content: Form Kontak & Maps -->
        <div class="row g-4 align-items-stretch">
            
            <!-- Kolom Form Kirim Pesan -->
            <div class="col-lg-7">
                <div class="card border-0 shadow-sm h-100" style="border-radius: 24px; background: #ffffff;">
                    <div class="card-body p-4 p-md-5">
                        <div class="d-flex align-items-center gap-2 mb-2">
                            <span class="badge bg-success-subtle text-success px-3 py-2 rounded-pill fw-bold" style="font-size: 12px;">
                                <i class="bi bi-send-fill me-1"></i> Formulir Aspirasi &amp; Pertanyaan
                            </span>
                        </div>
                        <h3 class="fw-bold mb-2" style="color: #0f172a; font-size: 24px;">Kirim Pesan Langsung</h3>
                        <p class="text-muted mb-4" style="font-size: 14px;">
                            Punya pertanyaan seputar akademik, PPDB, legalisir, atau ingin menyampaikan saran? Silakan isi formulir di bawah ini atau hubungi kami langsung via WhatsApp.
                        </p>

                        <form action="<?= base_url('website/kontak') ?>" method="post">
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label fw-bold small text-secondary">Nama Lengkap <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light border-0" style="border-radius: 12px 0 0 12px;"><i class="bi bi-person text-muted"></i></span>
                                        <input type="text" name="nama_lengkap" class="form-control bg-light border-0 py-2" placeholder="Nama Anda" required style="border-radius: 0 12px 12px 0; font-size: 14px;">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-bold small text-secondary">Nomor WhatsApp / HP</label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light border-0" style="border-radius: 12px 0 0 12px;"><i class="bi bi-whatsapp text-muted"></i></span>
                                        <input type="tel" name="no_hp" id="inputNoHp" class="form-control bg-light border-0 py-2" placeholder="08xxxxxxxxxx" style="border-radius: 0 12px 12px 0; font-size: 14px;">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-bold small text-secondary">Alamat Email</label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light border-0" style="border-radius: 12px 0 0 12px;"><i class="bi bi-envelope text-muted"></i></span>
                                        <input type="email" name="email" class="form-control bg-light border-0 py-2" placeholder="email@domain.com" style="border-radius: 0 12px 12px 0; font-size: 14px;">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-bold small text-secondary">Kategori / Subjek</label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light border-0" style="border-radius: 12px 0 0 12px;"><i class="bi bi-tag text-muted"></i></span>
                                        <select name="subjek" id="inputSubjek" class="form-select bg-light border-0 py-2" style="border-radius: 0 12px 12px 0; font-size: 14px;">
                                            <option value="Informasi Umum">Informasi Umum</option>
                                            <option value="Penerimaan Murid Baru (PMB/PPDB)">Penerimaan Murid Baru (PMB/PPDB)</option>
                                            <option value="Pelayanan Administrasi &amp; Surat">Pelayanan Administrasi &amp; Surat</option>
                                            <option value="Kemitraan &amp; Kerjasama">Kemitraan &amp; Kerjasama</option>
                                            <option value="Saran &amp; Pengaduan">Saran &amp; Pengaduan</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <label class="form-label fw-bold small text-secondary">Isi Pesan <span class="text-danger">*</span></label>
                                    <textarea name="pesan" id="inputPesan" rows="4" class="form-control bg-light border-0 p-3" placeholder="Tuliskan pertanyaan, masukan, atau pesan Anda secara lengkap di sini..." required style="border-radius: 14px; font-size: 14px; resize: vertical;"></textarea>
                                </div>
                                <div class="col-12 pt-2 d-flex gap-2 flex-wrap">
                                    <button type="submit" class="btn btn-success px-4 py-2 fw-bold d-inline-flex align-items-center gap-2 shadow-sm" style="border-radius: 12px; background: #059669; border-color: #059669;">
                                        <i class="bi bi-send-fill"></i> Kirim Pesan
                                    </button>
                                    
                                    <?php 
                                    $wa_clean = !empty($profil_website->wa_number) ? preg_replace('/[^0-9]/', '', $profil_website->wa_number) : '';
                                    if(substr($wa_clean, 0, 1) === '0') $wa_clean = '62' . substr($wa_clean, 1);
                                    ?>
                                    <?php if(!empty($wa_clean)): ?>
                                        <button type="button" onclick="kirimViaWA('<?= $wa_clean ?>')" class="btn btn-outline-success px-4 py-2 fw-bold d-inline-flex align-items-center gap-2" style="border-radius: 12px;">
                                            <i class="bi bi-whatsapp"></i> Chat WhatsApp
                                        </button>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Kolom Peta Lokasi & Info Tambahan -->
            <div class="col-lg-5">
                <div class="card border-0 shadow-sm h-100 d-flex flex-column justify-content-between" style="border-radius: 24px; background: #ffffff; overflow: hidden;">
                    <div class="p-4 p-md-4">
                        <div class="d-flex align-items-center justify-content-between mb-3">
                            <h5 class="fw-bold mb-0" style="color: #0f172a;"><i class="bi bi-map-fill text-success me-2"></i> Peta Lokasi Madrasah</h5>
                            <span class="badge bg-light text-secondary rounded-pill px-3 py-1">Google Maps</span>
                        </div>
                        
                        <!-- Map Embed -->
                        <div style="border-radius: 16px; overflow: hidden; height: 260px; border: 1px solid #e2e8f0; background: #f1f5f9;">
                            <?php if(!empty($profil_website->maps_embed_url)): ?>
                                <iframe src="<?= htmlspecialchars($profil_website->maps_embed_url, ENT_QUOTES, 'UTF-8') ?>" style="width: 100%; height: 100%; border: 0;" allowfullscreen="" loading="lazy"></iframe>
                            <?php else: ?>
                                <div class="w-100 h-100 d-flex flex-column align-items-center justify-content-center text-muted p-3 text-center">
                                    <i class="bi bi-geo-alt fs-1 text-success mb-2"></i>
                                    <span class="fw-semibold">Peta Kampus MAN 3 Banjar</span>
                                    <small class="text-secondary"><?= !empty($profil_website->alamat) ? htmlspecialchars($profil_website->alamat, ENT_QUOTES, 'UTF-8') : 'Kabupaten Banjar, Kalimantan Selatan' ?></small>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>

                    <!-- Social Media Links Footer Card -->
                    <div class="p-4 border-top bg-light" style="border-radius: 0 0 24px 24px;">
                        <h6 class="fw-bold mb-3" style="color: #334155; font-size: 13.5px;"><i class="bi bi-share-fill text-success me-1"></i> Media Sosial Resmi:</h6>
                        <div class="d-flex gap-2 flex-wrap">
                            <?php if(!empty($profil_website->facebook_url)): ?>
                                <a href="<?= htmlspecialchars($profil_website->facebook_url, ENT_QUOTES, 'UTF-8') ?>" target="_blank" rel="noopener" class="btn btn-sm btn-white border shadow-sm px-3 py-2 fw-semibold" style="border-radius: 10px; font-size: 12.5px; color: #1877f2; background: #fff;">
                                    <i class="bi bi-facebook me-1"></i> Facebook
                                </a>
                            <?php endif; ?>
                            <?php if(!empty($profil_website->instagram_url)): ?>
                                <a href="<?= htmlspecialchars($profil_website->instagram_url, ENT_QUOTES, 'UTF-8') ?>" target="_blank" rel="noopener" class="btn btn-sm btn-white border shadow-sm px-3 py-2 fw-semibold" style="border-radius: 10px; font-size: 12.5px; color: #e1306c; background: #fff;">
                                    <i class="bi bi-instagram me-1"></i> Instagram
                                </a>
                            <?php endif; ?>
                            <?php if(!empty($profil_website->youtube_url)): ?>
                                <a href="<?= htmlspecialchars($profil_website->youtube_url, ENT_QUOTES, 'UTF-8') ?>" target="_blank" rel="noopener" class="btn btn-sm btn-white border shadow-sm px-3 py-2 fw-semibold" style="border-radius: 10px; font-size: 12.5px; color: #ff0000; background: #fff;">
                                    <i class="bi bi-youtube me-1"></i> YouTube
                                </a>
                            <?php endif; ?>
                            <?php if(!empty($profil_website->tiktok_url)): ?>
                                <a href="<?= htmlspecialchars($profil_website->tiktok_url, ENT_QUOTES, 'UTF-8') ?>" target="_blank" rel="noopener" class="btn btn-sm btn-white border shadow-sm px-3 py-2 fw-semibold" style="border-radius: 10px; font-size: 12.5px; color: #000000; background: #fff;">
                                    <i class="bi bi-tiktok me-1"></i> TikTok
                                </a>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>

        </div>

    </div>
</section>

<style>
.contact-info-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 12px 24px rgba(16, 185, 129, 0.1) !important;
}
</style>

<script>
function kirimViaWA(nomor) {
    const nama = document.querySelector('input[name="nama_lengkap"]').value.trim();
    const subjek = document.getElementById('inputSubjek').value;
    const pesan = document.getElementById('inputPesan').value.trim();

    let text = `Halo Admin MAN 3 Banjar,\n\n`;
    if(nama) text += `*Nama:* ${nama}\n`;
    text += `*Perihal:* ${subjek}\n`;
    if(pesan) text += `*Pesan:*\n${pesan}\n\n`;
    text += `Terima kasih.`;

    const url = `https://wa.me/${nomor}?text=${encodeURIComponent(text)}`;
    window.open(url, '_blank');
}
</script>

<?php $this->load->view('public/partials/archive_footer'); ?>
