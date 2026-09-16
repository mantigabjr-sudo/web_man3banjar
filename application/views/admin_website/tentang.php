<?php $this->load->view('templates/header'); ?>
<?php $this->load->view('templates/sidebar'); ?>

<div class="content">

    <div class="page-header">
        <div class="page-title-group">
            <span class="page-category">Kelola Website</span>
            <h1 class="page-title">Tentang Madrasah</h1>
            <p class="page-subtitle">Kelola narasi sejarah, daftar fasilitas unggulan, prestasi, ekstrakurikuler, dan peta lokasi madrasah.</p>
        </div>
        <div class="header-actions">
            <a href="https://man3banjar.sch.id/tentang" target="_blank" class="btn-modern btn-modern-light">
                <i class="fa fa-external-link-alt me-1"></i> Pratinjau Halaman
            </a>
        </div>
    </div>

    <?php if($this->session->flashdata('success')): ?>
        <div class="alert alert-success d-flex align-items-center mb-4" role="alert">
            <i class="fa fa-check-circle me-2 fs-5"></i>
            <div><?= $this->session->flashdata('success') ?></div>
        </div>
    <?php endif; ?>

    <?php if($this->session->flashdata('error')): ?>
        <div class="alert alert-danger d-flex align-items-center mb-4" role="alert">
            <i class="fa fa-exclamation-circle me-2 fs-5"></i>
            <div><?= $this->session->flashdata('error') ?></div>
        </div>
    <?php endif; ?>

    <form method="post" action="<?= base_url('admin_website/save_tentang') ?>">
        <div class="row g-4">
            
            <!-- Kolom Sejarah & Maps -->
            <div class="col-lg-7">
                <div class="modern-card mb-4">
                    <div class="modern-card-header">
                        <h2 class="modern-card-title">Sejarah & Profil Singkat</h2>
                        <p class="modern-card-subtitle">Ceritakan sejarah berdirinya madrasah, perjalanan, dan perkembangannya.</p>
                    </div>
                    <div class="modern-card-body">
                        <div class="form-group mb-0">
                            <label class="form-label fw-bold">Narasi Sejarah Madrasah</label>
                            <textarea name="sejarah" 
                                      class="input-modern" 
                                      rows="9"
                                      placeholder="Tuliskan sejarah berdirinya MAN 3 Banjar..."
                                      style="line-height:1.7;"><?= htmlspecialchars($profil->sejarah ?? '', ENT_QUOTES, 'UTF-8') ?></textarea>
                            <div class="form-text mt-2 text-muted">
                                <i class="fa fa-info-circle me-1"></i> Gunakan paragraf yang terstruktur agar nyaman dibaca oleh pengunjung website.
                            </div>
                        </div>
                    </div>
                </div>

                <div class="modern-card">
                    <div class="modern-card-header">
                        <h2 class="modern-card-title">Integrasi Peta Lokasi</h2>
                        <p class="modern-card-subtitle">Sematkan peta Google Maps resmi madrasah.</p>
                    </div>
                    <div class="modern-card-body">
                        <div class="form-group mb-3">
                            <label class="form-label fw-bold">Google Maps Embed URL</label>
                            <input type="text"
                                   name="maps_embed_url"
                                   class="input-modern"
                                   value="<?= htmlspecialchars($profil->maps_embed_url ?? '', ENT_QUOTES, 'UTF-8') ?>"
                                   placeholder="https://www.google.com/maps/embed?pb=...">
                            <div class="form-text mt-2 text-muted">
                                <i class="fa fa-map-marker-alt me-1 text-danger"></i> Dapatkan melalui: Google Maps &rarr; Bagikan (Share) &rarr; Sematkan peta (Embed a map) &rarr; Salin URL pada atribut <code>src="..."</code>.
                            </div>
                        </div>

                        <?php if(!empty($profil->maps_embed_url)): ?>
                            <div class="mt-3">
                                <label class="form-label fw-bold text-muted small">Pratinjau Peta Saat Ini:</label>
                                <div style="border-radius:14px; overflow:hidden; border:1px solid #e2e8f0; height:220px;">
                                    <iframe src="<?= htmlspecialchars($profil->maps_embed_url, ENT_QUOTES, 'UTF-8') ?>" 
                                            width="100%" 
                                            height="100%" 
                                            style="border:0;" 
                                            allowfullscreen="" 
                                            loading="lazy" 
                                            referrerpolicy="no-referrer-when-downgrade"></iframe>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <!-- Kolom Fasilitas, Prestasi, Ekstrakurikuler -->
            <div class="col-lg-5">
                <div class="modern-card mb-4">
                    <div class="modern-card-header">
                        <h2 class="modern-card-title">Fasilitas Madrasah</h2>
                        <p class="modern-card-subtitle">Fasilitas penunjang kegiatan belajar mengajar.</p>
                    </div>
                    <div class="modern-card-body">
                        <div class="form-group mb-0">
                            <label class="form-label fw-bold">Daftar Fasilitas (1 item per baris)</label>
                            <textarea name="fasilitas" 
                                      class="input-modern" 
                                      rows="5"
                                      placeholder="Gedung Laboratorium Komputer&#10;Perpustakaan Digital&#10;Musholla As-Salam&#10;Lapangan Futsal & Basket"><?= htmlspecialchars($profil->fasilitas ?? '', ENT_QUOTES, 'UTF-8') ?></textarea>
                            <div class="form-text mt-2 text-muted">
                                Pisahkan setiap fasilitas dengan tombol Enter (baris baru).
                            </div>
                        </div>
                    </div>
                </div>

                <div class="modern-card mb-4">
                    <div class="modern-card-header">
                        <h2 class="modern-card-title">Prestasi Siswa & Guru</h2>
                        <p class="modern-card-subtitle">Pencapaian akademik & non-akademik.</p>
                    </div>
                    <div class="modern-card-body">
                        <div class="form-group mb-0">
                            <label class="form-label fw-bold">Daftar Prestasi (1 item per baris)</label>
                            <textarea name="prestasi" 
                                      class="input-modern" 
                                      rows="5"
                                      placeholder="Juara 1 KSM Kimia Tingkat Provinsi 2025&#10;Juara 2 Lomba Robotik Nasional&#10;Medali Emas Olimpiade Bahasa Arab"><?= htmlspecialchars($profil->prestasi ?? '', ENT_QUOTES, 'UTF-8') ?></textarea>
                            <div class="form-text mt-2 text-muted">
                                Pisahkan setiap prestasi dengan tombol Enter (baris baru).
                            </div>
                        </div>
                    </div>
                </div>

                <div class="modern-card mb-4">
                    <div class="modern-card-header">
                        <h2 class="modern-card-title">Ekstrakurikuler</h2>
                        <p class="modern-card-subtitle">Kegiatan minat dan bakat siswa.</p>
                    </div>
                    <div class="modern-card-body">
                        <div class="form-group mb-0">
                            <label class="form-label fw-bold">Daftar Ekstrakurikuler (1 item per baris)</label>
                            <textarea name="ekstrakurikuler" 
                                      class="input-modern" 
                                      rows="5"
                                      placeholder="Pramuka Gugus Depan MAN 3&#10;PMR (Palang Merah Remaja)&#10;Paskibraka&#10;Klub Robotik & Coding&#10;Hadrah & Seni Islami"><?= htmlspecialchars($profil->ekstrakurikuler ?? '', ENT_QUOTES, 'UTF-8') ?></textarea>
                            <div class="form-text mt-2 text-muted">
                                Pisahkan setiap ekstrakurikuler dengan tombol Enter (baris baru).
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Tombol Simpan -->
                <div class="modern-card bg-white p-3">
                    <button type="submit" class="btn-modern btn-modern-primary w-100 py-3 fs-6">
                        <i class="fa fa-save me-2"></i> Simpan Perubahan Tentang Madrasah
                    </button>
                </div>
            </div>

        </div>
    </form>

</div>

<?php $this->load->view('templates/footer'); ?>