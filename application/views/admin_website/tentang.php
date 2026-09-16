<?php $this->load->view('templates/header'); ?>
<?php $this->load->view('templates/sidebar'); ?>

<div class="content">
    <div class="container-fluid py-4">

        <!-- ALERT NOTIFIKASI -->
        <?php if($this->session->flashdata('success')): ?>
            <div class="alert alert-success alert-dismissible fade show rounded-4 border-0 shadow-sm mb-4" role="alert">
                <i class="bi bi-check-circle-fill me-2 fs-5"></i>
                <?= $this->session->flashdata('success') ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>
        <?php if($this->session->flashdata('error')): ?>
            <div class="alert alert-danger alert-dismissible fade show rounded-4 border-0 shadow-sm mb-4" role="alert">
                <i class="bi bi-exclamation-triangle-fill me-2 fs-5"></i>
                <?= $this->session->flashdata('error') ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>

        <!-- ═══ HEADER BANNER (TEMA FOTO IJAZAH) ═══ -->
        <div class="card border-0 rounded-4 shadow-sm mb-4 text-white position-relative overflow-hidden" 
             style="background: linear-gradient(135deg, #064e3b 0%, #059669 60%, #10b981 100%);">
            <div class="card-body p-4 p-md-5 position-relative" style="z-index: 2;">
                <div class="row align-items-center g-3">
                    <div class="col-lg-8">
                        <span class="badge bg-white text-success fw-bold px-3 py-1 rounded-pill mb-2" style="font-size: 11.5px;">
                            <i class="bi bi-info-circle-fill me-1"></i> INFORMASI &amp; SARANA PRASARANA
                        </span>
                        <h2 class="fw-bold mb-2 text-white">Tentang Madrasah &amp; Fasilitas</h2>
                        <p class="mb-3 text-white-50" style="font-size: 14px; max-width: 620px;">
                            Kelola sejarah berdirinya madrasah, daftar sarana &amp; fasilitas unggulan, prestasi peserta didik, kegiatan ekstrakurikuler, dan sematan peta lokasi Google Maps.
                        </p>
                        <div class="d-flex flex-wrap gap-2 pt-1">
                            <a href="<?= base_url('tentang') ?>" target="_blank" class="btn btn-light text-dark fw-bold rounded-pill px-3 shadow-sm">
                                <i class="bi bi-box-arrow-up-right text-success me-1"></i> Pratinjau di Website
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Hiasan Bulat Transparan -->
            <div style="position: absolute; right: -40px; top: -40px; width: 180px; height: 180px; background: radial-gradient(circle, rgba(255,255,255,0.2) 0%, transparent 70%); border-radius: 50%;"></div>
        </div>

        <form method="post" action="<?= base_url('admin_website/save_tentang') ?>">
            <div class="row g-4">
                
                <!-- Kolom Kiri: Sejarah & Peta Lokasi -->
                <div class="col-lg-7">
                    <div class="card border-0 rounded-4 shadow-sm mb-4">
                        <div class="card-header bg-white border-bottom pt-3 pb-2 px-4">
                            <h6 class="fw-bold mb-0 text-dark"><i class="bi bi-book-fill text-success me-2"></i> Sejarah / Gambaran Umum Madrasah</h6>
                        </div>
                        <div class="card-body p-4">
                            <textarea name="sejarah" 
                                      class="form-control rounded-3" 
                                      rows="9"
                                      placeholder="Tuliskan sejarah berdirinya MAN 3 Banjar..."
                                      style="line-height:1.7;"><?= htmlspecialchars($profil->sejarah ?? '', ENT_QUOTES, 'UTF-8') ?></textarea>
                            <div class="form-text mt-2 text-muted small">
                                Tuliskan paragraf dengan rapi agar nyaman dibaca pengunjung website.
                            </div>
                        </div>
                    </div>

                    <div class="card border-0 rounded-4 shadow-sm mb-4">
                        <div class="card-header bg-white border-bottom pt-3 pb-2 px-4">
                            <h6 class="fw-bold mb-0 text-dark"><i class="bi bi-geo-alt-fill text-success me-2"></i> Sematan Peta Google Maps</h6>
                        </div>
                        <div class="card-body p-4">
                            <div class="mb-3">
                                <label class="form-label fw-bold small text-muted">Google Maps Embed URL</label>
                                <input type="text"
                                       name="maps_embed_url"
                                       class="form-control rounded-3"
                                       value="<?= htmlspecialchars($profil->maps_embed_url ?? '', ENT_QUOTES, 'UTF-8') ?>"
                                       placeholder="https://www.google.com/maps/embed?pb=...">
                                <div class="form-text mt-2 text-muted small">
                                    Dari Google Maps: Bagikan &rarr; Sematkan peta (Embed a map) &rarr; Salin URL pada atribut <code>src="..."</code>.
                                </div>
                            </div>

                            <?php if(!empty($profil->maps_embed_url)): ?>
                                <div class="rounded-4 overflow-hidden border shadow-sm" style="height: 220px;">
                                    <iframe src="<?= htmlspecialchars($profil->maps_embed_url, ENT_QUOTES, 'UTF-8') ?>" 
                                            width="100%" 
                                            height="100%" 
                                            style="border:0;" 
                                            allowfullscreen="" 
                                            loading="lazy"></iframe>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <!-- Kolom Kanan: Fasilitas, Prestasi, Ekstrakurikuler -->
                <div class="col-lg-5">
                    <div class="card border-0 rounded-4 shadow-sm mb-4">
                        <div class="card-header bg-white border-bottom pt-3 pb-2 px-4">
                            <h6 class="fw-bold mb-0 text-dark"><i class="bi bi-building-check text-success me-2"></i> Fasilitas Madrasah (1 item/baris)</h6>
                        </div>
                        <div class="card-body p-4">
                            <textarea name="fasilitas" class="form-control rounded-3" rows="4" placeholder="Laboratorium Komputer&#10;Perpustakaan Digital&#10;Musholla"><?= htmlspecialchars($profil->fasilitas ?? '', ENT_QUOTES, 'UTF-8') ?></textarea>
                        </div>
                    </div>

                    <div class="card border-0 rounded-4 shadow-sm mb-4">
                        <div class="card-header bg-white border-bottom pt-3 pb-2 px-4">
                            <h6 class="fw-bold mb-0 text-dark"><i class="bi bi-trophy-fill text-warning me-2"></i> Prestasi Siswa &amp; Guru (1 item/baris)</h6>
                        </div>
                        <div class="card-body p-4">
                            <textarea name="prestasi" class="form-control rounded-3" rows="4" placeholder="Juara 1 KSM Tingkat Provinsi 2025&#10;Medali Emas Robotik"><?= htmlspecialchars($profil->prestasi ?? '', ENT_QUOTES, 'UTF-8') ?></textarea>
                        </div>
                    </div>

                    <div class="card border-0 rounded-4 shadow-sm mb-4">
                        <div class="card-header bg-white border-bottom pt-3 pb-2 px-4">
                            <h6 class="fw-bold mb-0 text-dark"><i class="bi bi-stars text-primary me-2"></i> Ekstrakurikuler (1 item/baris)</h6>
                        </div>
                        <div class="card-body p-4">
                            <textarea name="ekstrakurikuler" class="form-control rounded-3" rows="4" placeholder="Pramuka Gugus Depan&#10;PMR&#10;Paskibraka&#10;Robotik &amp; Coding"><?= htmlspecialchars($profil->ekstrakurikuler ?? '', ENT_QUOTES, 'UTF-8') ?></textarea>
                        </div>
                    </div>

                    <!-- Tombol Simpan -->
                    <div class="card border-0 rounded-4 shadow-sm p-3 mb-4">
                        <button type="submit" class="btn btn-success fw-bold rounded-pill w-100 py-3 shadow-sm fs-6">
                            <i class="bi bi-check-circle-fill me-2"></i> Simpan Konten Tentang Madrasah
                        </button>
                    </div>
                </div>

            </div>
        </form>

    </div>
</div>

<?php $this->load->view('templates/footer'); ?>