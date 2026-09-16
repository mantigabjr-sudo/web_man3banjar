<?php $this->load->view('templates/header'); ?>
<?php $this->load->view('templates/sidebar'); ?>

<div class="content">

    <!-- Page Header -->
    <div class="page-header">
        <div class="page-header-info">
            <span class="page-badge-label">
                <i class="bi bi-building"></i> Pengaturan Konten
            </span>
            <h1 class="page-title">Profil Madrasah</h1>
            <p class="page-subtitle">Kelola profil resmi, visi, misi, tujuan, kontak, dan tautan sosial media madrasah.</p>
        </div>
        <div class="page-actions">
            <a href="<?= base_url('website/profil') ?>" target="_blank" class="btn-modern-secondary">
                <i class="bi bi-box-arrow-up-right"></i> Lihat Halaman Profil
            </a>
        </div>
    </div>

    <?php if($this->session->flashdata('success')): ?>
        <div class="alert alert-success border-0 rounded-4 shadow-sm mb-4 d-flex align-items-center gap-2">
            <i class="bi bi-check-circle-fill fs-5"></i>
            <div><?= $this->session->flashdata('success') ?></div>
        </div>
    <?php endif; ?>

    <form method="post" action="<?= base_url('admin_website/save_profil') ?>">
        <div class="row g-4">
            <!-- Kolom Kiri: Informasi Utama & Visi Misi -->
            <div class="col-lg-7">
                <!-- Card 1: Identitas & Deskripsi -->
                <div class="modern-card mb-4">
                    <div class="modern-card-header">
                        <div>
                            <h2 class="modern-card-title"><i class="bi bi-info-circle-fill text-success"></i> Identitas &amp; Pengantar</h2>
                            <p class="modern-card-subtitle">Judul dan ringkasan pengantar profil sekolah.</p>
                        </div>
                    </div>
                    <div class="modern-card-body">
                        <div class="mb-3">
                            <label class="form-label-modern">Judul Profil <span class="text-danger">*</span></label>
                            <input type="text" name="judul_profil" class="input-modern" value="<?= htmlspecialchars($profil->judul_profil ?? '', ENT_QUOTES, 'UTF-8') ?>" placeholder="Contoh: Profil MAN 3 Banjar" required>
                        </div>

                        <div class="mb-0">
                            <label class="form-label-modern">Isi Profil Singkat</label>
                            <textarea name="isi_profil" class="textarea-modern" rows="5" placeholder="Penjelasan umum tentang madrasah..."><?= htmlspecialchars($profil->isi_profil ?? '', ENT_QUOTES, 'UTF-8') ?></textarea>
                        </div>
                    </div>
                </div>

                <!-- Card 2: Visi, Misi & Tujuan -->
                <div class="modern-card">
                    <div class="modern-card-header">
                        <div>
                            <h2 class="modern-card-title"><i class="bi bi-compass-fill text-success"></i> Visi, Misi &amp; Tujuan</h2>
                            <p class="modern-card-subtitle">Arah kebijakan dan target capaian madrasah.</p>
                        </div>
                    </div>
                    <div class="modern-card-body">
                        <div class="mb-3">
                            <label class="form-label-modern">Visi Madrasah</label>
                            <textarea name="visi" class="textarea-modern" rows="3" placeholder="Tuliskan visi..."><?= htmlspecialchars($profil->visi ?? '', ENT_QUOTES, 'UTF-8') ?></textarea>
                        </div>

                        <div class="mb-3">
                            <label class="form-label-modern">Misi Madrasah</label>
                            <textarea name="misi" class="textarea-modern" rows="4" placeholder="Tuliskan poin misi..."><?= htmlspecialchars($profil->misi ?? '', ENT_QUOTES, 'UTF-8') ?></textarea>
                        </div>

                        <div class="mb-0">
                            <label class="form-label-modern">Tujuan Pendidikan</label>
                            <textarea name="tujuan" class="textarea-modern" rows="4" placeholder="Tuliskan poin tujuan pendidikan..."><?= htmlspecialchars($profil->tujuan ?? '', ENT_QUOTES, 'UTF-8') ?></textarea>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Kolom Kanan: Kontak, Layanan & Medsos -->
            <div class="col-lg-5">
                <!-- Card 3: Kontak & Lokasi -->
                <div class="modern-card mb-4">
                    <div class="modern-card-header">
                        <div>
                            <h2 class="modern-card-title"><i class="bi bi-telephone-fill text-success"></i> Kontak &amp; Jam Layanan</h2>
                            <p class="modern-card-subtitle">Saluran komunikasi yang dapat dihubungi publik.</p>
                        </div>
                    </div>
                    <div class="modern-card-body">
                        <div class="mb-3">
                            <label class="form-label-modern">Alamat Lengkap</label>
                            <textarea name="alamat" class="textarea-modern" rows="3" placeholder="Alamat jalan, desa/kelurahan, kecamatan, kabupaten..."><?= htmlspecialchars($profil->alamat ?? '', ENT_QUOTES, 'UTF-8') ?></textarea>
                        </div>

                        <div class="row g-2 mb-3">
                            <div class="col-6">
                                <label class="form-label-modern">Telepon</label>
                                <input type="text" name="telepon" class="input-modern" value="<?= htmlspecialchars($profil->telepon ?? '', ENT_QUOTES, 'UTF-8') ?>" placeholder="(0511) ...">
                            </div>
                            <div class="col-6">
                                <label class="form-label-modern">WhatsApp</label>
                                <input type="text" name="whatsapp" class="input-modern" value="<?= htmlspecialchars($profil->whatsapp ?? '', ENT_QUOTES, 'UTF-8') ?>" placeholder="08...">
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label-modern">Email Resmi</label>
                            <input type="email" name="email" class="input-modern" value="<?= htmlspecialchars($profil->email ?? '', ENT_QUOTES, 'UTF-8') ?>" placeholder="admin@man3banjar.sch.id">
                        </div>

                        <div class="mb-0">
                            <label class="form-label-modern">Jam Layanan Kantor</label>
                            <input type="text" name="jam_layanan" class="input-modern" value="<?= htmlspecialchars($profil->jam_layanan ?? '', ENT_QUOTES, 'UTF-8') ?>" placeholder="Senin - Jumat, 08.00 - 15.00 WITA">
                        </div>
                    </div>
                </div>

                <!-- Card 4: Media Sosial -->
                <div class="modern-card mb-4">
                    <div class="modern-card-header">
                        <div>
                            <h2 class="modern-card-title"><i class="bi bi-share-fill text-success"></i> Media Sosial Resmi</h2>
                            <p class="modern-card-subtitle">Tautan akun publik madrasah.</p>
                        </div>
                    </div>
                    <div class="modern-card-body">
                        <div class="mb-3">
                            <label class="form-label-modern"><i class="bi bi-facebook text-primary me-1"></i> Facebook URL</label>
                            <input type="text" name="facebook_url" class="input-modern" value="<?= htmlspecialchars($profil->facebook_url ?? '', ENT_QUOTES, 'UTF-8') ?>" placeholder="https://facebook.com/...">
                        </div>

                        <div class="mb-3">
                            <label class="form-label-modern"><i class="bi bi-instagram text-danger me-1"></i> Instagram URL</label>
                            <input type="text" name="instagram_url" class="input-modern" value="<?= htmlspecialchars($profil->instagram_url ?? '', ENT_QUOTES, 'UTF-8') ?>" placeholder="https://instagram.com/...">
                        </div>

                        <div class="mb-0">
                            <label class="form-label-modern"><i class="bi bi-youtube text-danger me-1"></i> YouTube Channel URL</label>
                            <input type="text" name="youtube_url" class="input-modern" value="<?= htmlspecialchars($profil->youtube_url ?? '', ENT_QUOTES, 'UTF-8') ?>" placeholder="https://youtube.com/@...">
                        </div>
                    </div>
                </div>

                <!-- Floating Save Button -->
                <div class="modern-card p-3">
                    <button type="submit" class="btn-modern-primary w-100 justify-content-center py-2 fs-6">
                        <i class="bi bi-check2-circle fs-5"></i> Simpan Perubahan Profil
                    </button>
                </div>
            </div>
        </div>
    </form>

</div>

<?php $this->load->view('templates/footer'); ?>