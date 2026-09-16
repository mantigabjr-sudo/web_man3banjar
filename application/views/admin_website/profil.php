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
                            <i class="bi bi-building me-1"></i> INFORMASI POKOK MADRASAH
                        </span>
                        <h2 class="fw-bold mb-2 text-white">Profil &amp; Identitas Madrasah</h2>
                        <p class="mb-3 text-white-50" style="font-size: 14px; max-width: 620px;">
                            Kelola data judul profil, deskripsi umum, visi, misi, tujuan, NSM/NPSN, jam operasional layanan, alamat kantor, kontak telepon, WhatsApp, email, dan akun media sosial resmi.
                        </p>
                        <div class="d-flex flex-wrap gap-2 pt-1">
                            <a href="<?= base_url('admin_website/tentang') ?>" class="btn btn-light text-dark fw-bold rounded-pill px-3 shadow-sm">
                                <i class="bi bi-info-circle-fill text-success me-1"></i> Kelola Sejarah &amp; Fasilitas
                            </a>
                            <a href="<?= base_url('tentang') ?>" target="_blank" class="btn btn-outline-light fw-bold rounded-pill px-3 shadow-sm">
                                <i class="bi bi-box-arrow-up-right me-1"></i> Pratinjau di Website
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Hiasan Bulat Transparan -->
            <div style="position: absolute; right: -40px; top: -40px; width: 180px; height: 180px; background: radial-gradient(circle, rgba(255,255,255,0.2) 0%, transparent 70%); border-radius: 50%;"></div>
        </div>

        <form method="post" action="<?= base_url('admin_website/save_profil') ?>">
            <div class="row g-4">
                
                <!-- Kolom Kiri: Identitas Pokok & Visi Misi -->
                <div class="col-lg-7">
                    <!-- Kartu 1: Identitas & Deskripsi Profil -->
                    <div class="card border-0 rounded-4 shadow-sm mb-4">
                        <div class="card-header bg-white border-bottom pt-3 pb-2 px-4">
                            <h6 class="fw-bold mb-0 text-dark"><i class="bi bi-mortarboard-fill text-success me-2"></i> Identitas &amp; Deskripsi Profil</h6>
                        </div>
                        <div class="card-body p-4">
                            <div class="mb-3">
                                <label class="form-label fw-bold small text-muted">Judul / Tagline Profil Madrasah</label>
                                <input type="text" name="judul_profil" class="form-control rounded-3" value="<?= htmlspecialchars($profil->judul_profil ?? '', ENT_QUOTES, 'UTF-8') ?>" placeholder="Contoh: Madrasah Digital dan Berkarakter" required>
                                <div class="form-text small mt-1">Judul atau slogan utama yang tampil di beranda dan header profil.</div>
                            </div>

                            <div class="row g-3 mb-3">
                                <div class="col-md-6">
                                    <label class="form-label fw-bold small text-muted">Nomor Statistik Madrasah (NSM)</label>
                                    <input type="text" name="nsm" class="form-control rounded-3" value="<?= htmlspecialchars($profil->nsm ?? '', ENT_QUOTES, 'UTF-8') ?>" placeholder="Contoh: 131163030003">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-bold small text-muted">NPSN</label>
                                    <input type="text" name="npsn" class="form-control rounded-3" value="<?= htmlspecialchars($profil->npsn ?? '', ENT_QUOTES, 'UTF-8') ?>" placeholder="Contoh: 30315526">
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-bold small text-muted">Jam Layanan Kantor</label>
                                <input type="text" name="jam_layanan" class="form-control rounded-3" value="<?= htmlspecialchars($profil->jam_layanan ?? '', ENT_QUOTES, 'UTF-8') ?>" placeholder="Contoh: Senin - Kamis: 07:30 - 15:30, Jumat: 07:30 - 11:30">
                            </div>

                            <div class="mb-0">
                                <label class="form-label fw-bold small text-muted">Deskripsi Singkat Profil Madrasah</label>
                                <textarea name="isi_profil" class="form-control rounded-3" rows="4" style="line-height:1.6;" placeholder="Tuliskan ringkasan profil mengenai MAN 3 Banjar..."><?= htmlspecialchars($profil->isi_profil ?? '', ENT_QUOTES, 'UTF-8') ?></textarea>
                                <div class="form-text small mt-1">Tampil di footer website dan ringkasan profil publik.</div>
                            </div>
                        </div>
                    </div>

                    <!-- Kartu 2: Visi, Misi & Tujuan -->
                    <div class="card border-0 rounded-4 shadow-sm mb-4">
                        <div class="card-header bg-white border-bottom pt-3 pb-2 px-4">
                            <h6 class="fw-bold mb-0 text-dark"><i class="bi bi-compass-fill text-success me-2"></i> Visi, Misi &amp; Tujuan Madrasah</h6>
                        </div>
                        <div class="card-body p-4">
                            <div class="mb-3">
                                <label class="form-label fw-bold small text-muted">Visi Madrasah</label>
                                <textarea name="visi" class="form-control rounded-3" rows="3" placeholder="Terwujudnya madrasah yang unggul dalam prestasi, berakhlak mulia..."><?= htmlspecialchars($profil->visi ?? '', ENT_QUOTES, 'UTF-8') ?></textarea>
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-bold small text-muted">Misi Madrasah</label>
                                <textarea name="misi" class="form-control rounded-3" rows="5" style="line-height:1.6;" placeholder="1. Menyelenggarakan pendidikan berkualitas...&#10;2. Membentuk karakter islami..."><?= htmlspecialchars($profil->misi ?? '', ENT_QUOTES, 'UTF-8') ?></textarea>
                                <div class="form-text small mt-1 text-muted">Gunakan nomor atau baris baru untuk setiap butir misi.</div>
                            </div>

                            <div class="mb-0">
                                <label class="form-label fw-bold small text-muted">Tujuan Madrasah</label>
                                <textarea name="tujuan" class="form-control rounded-3" rows="4" style="line-height:1.6;" placeholder="Tuliskan tujuan madrasah..."><?= htmlspecialchars($profil->tujuan ?? '', ENT_QUOTES, 'UTF-8') ?></textarea>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Kolom Kanan: Kontak & Medsos -->
                <div class="col-lg-5">
                    <!-- Kartu Kontak -->
                    <div class="card border-0 rounded-4 shadow-sm mb-4">
                        <div class="card-header bg-white border-bottom pt-3 pb-2 px-4">
                            <h6 class="fw-bold mb-0 text-dark"><i class="bi bi-geo-alt-fill text-success me-2"></i> Kontak &amp; Lokasi Kantor</h6>
                        </div>
                        <div class="card-body p-4">
                            <div class="mb-3">
                                <label class="form-label fw-bold small text-muted">Alamat Lengkap</label>
                                <textarea name="alamat" class="form-control rounded-3" rows="3" placeholder="Jl. A. Yani No.Km. 15.200, Gambut, Kec. Gambut, Kab. Banjar"><?= htmlspecialchars($profil->alamat ?? '', ENT_QUOTES, 'UTF-8') ?></textarea>
                            </div>

                            <div class="row g-2 mb-3">
                                <div class="col-6">
                                    <label class="form-label fw-bold small text-muted">Telepon Kantor</label>
                                    <input type="text" name="telepon" class="form-control rounded-3" value="<?= htmlspecialchars($profil->telepon ?? '', ENT_QUOTES, 'UTF-8') ?>" placeholder="0511-xxxxxx">
                                </div>
                                <div class="col-6">
                                    <label class="form-label fw-bold small text-muted">Nomor WhatsApp</label>
                                    <input type="text" name="whatsapp" class="form-control rounded-3" value="<?= htmlspecialchars($profil->whatsapp ?? '', ENT_QUOTES, 'UTF-8') ?>" placeholder="08xxxxxxxxxx">
                                </div>
                            </div>

                            <div class="mb-0">
                                <label class="form-label fw-bold small text-muted">Alamat Email Resmi</label>
                                <input type="email" name="email" class="form-control rounded-3" value="<?= htmlspecialchars($profil->email ?? '', ENT_QUOTES, 'UTF-8') ?>" placeholder="man.tigabjr@gmail.com">
                            </div>
                        </div>
                    </div>

                    <!-- Kartu Medsos -->
                    <div class="card border-0 rounded-4 shadow-sm mb-4">
                        <div class="card-header bg-white border-bottom pt-3 pb-2 px-4">
                            <h6 class="fw-bold mb-0 text-dark"><i class="bi bi-share-fill text-success me-2"></i> Media Sosial Resmi</h6>
                        </div>
                        <div class="card-body p-4">
                            <div class="mb-3">
                                <label class="form-label fw-bold small text-muted"><i class="bi bi-youtube text-danger me-1"></i> URL Channel YouTube</label>
                                <input type="text" name="youtube_url" class="form-control rounded-3" value="<?= htmlspecialchars($profil->youtube_url ?? '', ENT_QUOTES, 'UTF-8') ?>" placeholder="https://youtube.com/@...">
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-bold small text-muted"><i class="bi bi-instagram text-danger me-1"></i> URL Profil Instagram</label>
                                <input type="text" name="instagram_url" class="form-control rounded-3" value="<?= htmlspecialchars($profil->instagram_url ?? '', ENT_QUOTES, 'UTF-8') ?>" placeholder="https://instagram.com/...">
                            </div>

                            <div class="mb-0">
                                <label class="form-label fw-bold small text-muted"><i class="bi bi-facebook text-primary me-1"></i> URL Halaman Facebook</label>
                                <input type="text" name="facebook_url" class="form-control rounded-3" value="<?= htmlspecialchars($profil->facebook_url ?? '', ENT_QUOTES, 'UTF-8') ?>" placeholder="https://facebook.com/...">
                            </div>
                        </div>
                    </div>

                    <!-- Tombol Simpan -->
                    <div class="card border-0 rounded-4 shadow-sm p-3 mb-4">
                        <button type="submit" class="btn btn-success fw-bold rounded-pill w-100 py-3 shadow-sm fs-6">
                            <i class="bi bi-check-circle-fill me-2"></i> Simpan Seluruh Perubahan Profil
                        </button>
                    </div>
                </div>

            </div>
        </form>

    </div>
</div>

<?php $this->load->view('templates/footer'); ?>