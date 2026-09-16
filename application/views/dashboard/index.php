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
                            <i class="bi bi-globe2 me-1"></i> ADMIN WEBSITE RESMI MAN 3 BANJAR
                        </span>
                        <h2 class="fw-bold mb-2 text-white">Dashboard Pengelolaan Website Madrasah</h2>
                        <p class="mb-3 text-white-50" style="font-size: 14px; max-width: 650px;">
                            Selamat datang kembali, <strong><?= htmlspecialchars($username ?? 'Administrator') ?></strong>. Kelola seluruh publikasi berita, profil madrasah, banner beranda, berkas unduhan, galeri, dan layanan informasi publik.
                        </p>
                        <div class="d-flex flex-wrap gap-2 pt-1">
                            <a href="<?= base_url('berita') ?>" class="btn btn-light text-dark fw-bold rounded-pill px-3 shadow-sm">
                                <i class="bi bi-plus-circle-fill text-success me-1"></i> Kelola Berita
                            </a>
                            <a href="<?= base_url('admin_banner') ?>" class="btn btn-outline-light fw-bold rounded-pill px-3 shadow-sm">
                                <i class="bi bi-images me-1"></i> Banner Slider
                            </a>
                            <a href="<?= base_url('admin_foto_ijazah') ?>" class="btn btn-warning text-dark fw-bold rounded-pill px-3 shadow-sm">
                                <i class="bi bi-camera-fill me-1"></i> Foto Ijazah XII
                            </a>
                            <a href="<?= base_url() ?>" target="_blank" class="btn btn-outline-light fw-bold rounded-pill px-3 shadow-sm">
                                <i class="bi bi-box-arrow-up-right me-1"></i> Kunjungi Website
                            </a>
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="p-3 bg-white bg-opacity-10 rounded-4 border border-white border-opacity-25 backdrop-blur">
                            <div class="d-flex align-items-center justify-content-between mb-2">
                                <span class="text-white fw-bold small"><i class="bi bi-shield-check me-1"></i> Akun Login:</span>
                                <span class="badge bg-white text-success rounded-pill fw-bold" style="font-size: 11px;">Aktif</span>
                            </div>
                            <div class="text-white fw-bold fs-6"><?= htmlspecialchars($username ?? 'Admin') ?></div>
                            <small class="text-white-50 d-block mt-1" style="font-size: 11.5px;">
                                Role: <?= htmlspecialchars($roleText ?? 'Administrator Website') ?>
                            </small>
                            <div class="mt-2 pt-2 border-top border-white border-opacity-25 d-flex justify-content-between align-items-center">
                                <small class="text-white-50" style="font-size: 11px;">man3banjar.sch.id</small>
                                <a href="<?= base_url('auth/logout') ?>" class="text-white fw-bold text-decoration-none small" onclick="return confirm('Keluar dari sesi admin?');">
                                    <i class="bi bi-box-arrow-right me-1"></i> Logout
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Hiasan Bulat Transparan -->
            <div style="position: absolute; right: -40px; top: -40px; width: 180px; height: 180px; background: radial-gradient(circle, rgba(255,255,255,0.2) 0%, transparent 70%); border-radius: 50%;"></div>
        </div>

        <!-- ═══ STATISTIK RINGKASAN (BORDER-START 4PX KHAS FOTO IJAZAH) ═══ -->
        <div class="row g-3 mb-4">
            <div class="col-md-3 col-sm-6">
                <div class="card border-0 rounded-4 shadow-sm h-100 p-3 bg-white border-start border-4 border-success">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <span class="text-muted small fw-bold text-uppercase">Total Berita</span>
                            <h3 class="fw-bold text-dark mb-0 mt-1"><?= (int)($total_berita ?? 0) ?></h3>
                            <small class="text-success fw-semibold"><?= (int)($total_berita_pub ?? 0) ?> tayang publik</small>
                        </div>
                        <div class="p-3 bg-success-subtle text-success rounded-4 fs-3">
                            <i class="bi bi-newspaper"></i>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-3 col-sm-6">
                <div class="card border-0 rounded-4 shadow-sm h-100 p-3 bg-white border-start border-4 border-primary">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <span class="text-muted small fw-bold text-uppercase">Media Visual</span>
                            <h3 class="fw-bold text-primary mb-0 mt-1"><?= (int)(($total_banner ?? 0) + ($total_galeri ?? 0)) ?></h3>
                            <small class="text-muted"><?= (int)($total_banner ?? 0) ?> banner &bull; <?= (int)($total_galeri ?? 0) ?> galeri</small>
                        </div>
                        <div class="p-3 bg-primary-subtle text-primary rounded-4 fs-3">
                            <i class="bi bi-images"></i>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-3 col-sm-6">
                <div class="card border-0 rounded-4 shadow-sm h-100 p-3 bg-white border-start border-4 border-warning">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <span class="text-muted small fw-bold text-uppercase">Foto Ijazah XII</span>
                            <h3 class="fw-bold text-warning mb-0 mt-1"><?= (int)($total_verified_foto ?? 0) ?></h3>
                            <small class="text-muted"><?= (int)($total_pending_foto ?? 0) ?> foto mentah</small>
                        </div>
                        <div class="p-3 bg-warning-subtle text-warning rounded-4 fs-3">
                            <i class="bi bi-mortarboard-fill"></i>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-3 col-sm-6">
                <div class="card border-0 rounded-4 shadow-sm h-100 p-3 bg-white border-start border-4 border-info">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <span class="text-muted small fw-bold text-uppercase">Unduhan Berkas</span>
                            <h3 class="fw-bold text-info mb-0 mt-1"><?= (int)($total_download ?? 0) ?></h3>
                            <small class="text-muted">Dokumen PDF/Word publik</small>
                        </div>
                        <div class="p-3 bg-info-subtle text-info rounded-4 fs-3">
                            <i class="bi bi-file-earmark-arrow-down-fill"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- ═══ MODUL CEPAT KELOLA WEBSITE ═══ -->
        <div class="row g-4 mb-4">
            <div class="col-lg-8">
                <div class="card border-0 rounded-4 shadow-sm mb-4">
                    <div class="card-header bg-white border-bottom pt-3 pb-2 px-4">
                        <h6 class="fw-bold mb-0 text-dark"><i class="bi bi-grid-fill text-success me-2"></i> Menu Pintas Pengelolaan Website</h6>
                    </div>
                    <div class="card-body p-4">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <a href="<?= base_url('berita') ?>" class="p-3 rounded-4 bg-light border text-decoration-none d-flex align-items-center gap-3 text-dark transition-all hover-shadow">
                                    <div class="p-3 bg-success-subtle text-success rounded-4 fs-4 flex-shrink-0">
                                        <i class="bi bi-newspaper"></i>
                                    </div>
                                    <div class="min-w-0">
                                        <strong class="d-block" style="font-size: 14.5px;">Artikel &amp; Berita</strong>
                                        <small class="text-muted" style="font-size: 12px;">Tulis, edit, dan publish warta kegiatan madrasah.</small>
                                    </div>
                                    <i class="bi bi-chevron-right text-muted ms-auto"></i>
                                </a>
                            </div>

                            <div class="col-md-6">
                                <a href="<?= base_url('admin_banner') ?>" class="p-3 rounded-4 bg-light border text-decoration-none d-flex align-items-center gap-3 text-dark transition-all hover-shadow">
                                    <div class="p-3 bg-warning-subtle text-warning rounded-4 fs-4 flex-shrink-0">
                                        <i class="bi bi-images"></i>
                                    </div>
                                    <div class="min-w-0">
                                        <strong class="d-block" style="font-size: 14.5px;">Banner Slider</strong>
                                        <small class="text-muted" style="font-size: 12px;">Atur gambar slide sorotan di beranda website.</small>
                                    </div>
                                    <i class="bi bi-chevron-right text-muted ms-auto"></i>
                                </a>
                            </div>

                            <div class="col-md-6">
                                <a href="<?= base_url('admin_website/profil') ?>" class="p-3 rounded-4 bg-light border text-decoration-none d-flex align-items-center gap-3 text-dark transition-all hover-shadow">
                                    <div class="p-3 bg-primary-subtle text-primary rounded-4 fs-4 flex-shrink-0">
                                        <i class="bi bi-building"></i>
                                    </div>
                                    <div class="min-w-0">
                                        <strong class="d-block" style="font-size: 14.5px;">Profil &amp; Visi Misi</strong>
                                        <small class="text-muted" style="font-size: 12px;">Kelola identitas, sejarah, kontak &amp; medsos resmi.</small>
                                    </div>
                                    <i class="bi bi-chevron-right text-muted ms-auto"></i>
                                </a>
                            </div>

                            <div class="col-md-6">
                                <a href="<?= base_url('admin_website/ptk') ?>" class="p-3 rounded-4 bg-light border text-decoration-none d-flex align-items-center gap-3 text-dark transition-all hover-shadow">
                                    <div class="p-3 bg-info-subtle text-info rounded-4 fs-4 flex-shrink-0">
                                        <i class="bi bi-people-fill"></i>
                                    </div>
                                    <div class="min-w-0">
                                        <strong class="d-block" style="font-size: 14.5px;">Direktori PTK Guru</strong>
                                        <small class="text-muted" style="font-size: 12px;">Atur guru &amp; tenaga kependidikan yang tampil.</small>
                                    </div>
                                    <i class="bi bi-chevron-right text-muted ms-auto"></i>
                                </a>
                            </div>

                            <div class="col-md-6">
                                <a href="<?= base_url('admin_website/galeri') ?>" class="p-3 rounded-4 bg-light border text-decoration-none d-flex align-items-center gap-3 text-dark transition-all hover-shadow">
                                    <div class="p-3 bg-success-subtle text-success rounded-4 fs-4 flex-shrink-0">
                                        <i class="bi bi-camera-reels-fill"></i>
                                    </div>
                                    <div class="min-w-0">
                                        <strong class="d-block" style="font-size: 14.5px;">Galeri Foto Kegiatan</strong>
                                        <small class="text-muted" style="font-size: 12px;">Dokumentasi foto kegiatan dan prestasi madrasah.</small>
                                    </div>
                                    <i class="bi bi-chevron-right text-muted ms-auto"></i>
                                </a>
                            </div>

                            <div class="col-md-6">
                                <a href="<?= base_url('admin_foto_ijazah') ?>" class="p-3 rounded-4 bg-light border text-decoration-none d-flex align-items-center gap-3 text-dark transition-all hover-shadow">
                                    <div class="p-3 bg-primary-subtle text-primary rounded-4 fs-4 flex-shrink-0">
                                        <i class="bi bi-mortarboard-fill"></i>
                                    </div>
                                    <div class="min-w-0">
                                        <strong class="d-block" style="font-size: 14.5px;">Foto Ijazah Kelas XII</strong>
                                        <small class="text-muted" style="font-size: 12px;">Monitoring verifikasi siswa &amp; download batch ZIP.</small>
                                    </div>
                                    <i class="bi bi-chevron-right text-muted ms-auto"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- TAUTAN PUBLIK & INFO SERVER -->
            <div class="col-lg-4">
                <div class="card border-0 rounded-4 shadow-sm mb-4">
                    <div class="card-header bg-white border-bottom pt-3 pb-2 px-4">
                        <h6 class="fw-bold mb-0 text-dark"><i class="bi bi-link-45deg text-success me-2"></i> Akses Cepat Website</h6>
                    </div>
                    <div class="card-body p-3 d-flex flex-column gap-2">
                        <a href="<?= base_url('verifikasi_foto_ijazah') ?>" target="_blank" class="p-3 rounded-3 bg-light border text-decoration-none text-dark d-flex align-items-center justify-content-between hover-bg">
                            <div>
                                <span class="fw-bold d-block small">Portal Siswa Foto Ijazah</span>
                                <small class="text-muted" style="font-size: 11px;">/verifikasi_foto_ijazah</small>
                            </div>
                            <i class="bi bi-box-arrow-up-right text-success small"></i>
                        </a>

                        <a href="<?= base_url('ppdb') ?>" target="_blank" class="p-3 rounded-3 bg-light border text-decoration-none text-dark d-flex align-items-center justify-content-between hover-bg">
                            <div>
                                <span class="fw-bold d-block small">Pendaftaran PMB Online</span>
                                <small class="text-muted" style="font-size: 11px;">/ppdb</small>
                            </div>
                            <i class="bi bi-box-arrow-up-right text-primary small"></i>
                        </a>

                        <a href="<?= base_url() ?>" target="_blank" class="p-3 rounded-3 bg-light border text-decoration-none text-dark d-flex align-items-center justify-content-between hover-bg">
                            <div>
                                <span class="fw-bold d-block small">Beranda Website Utama</span>
                                <small class="text-muted" style="font-size: 11px;">man3banjar.sch.id</small>
                            </div>
                            <i class="bi bi-box-arrow-up-right text-success small"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>
