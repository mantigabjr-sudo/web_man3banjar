<div class="content">

    <!-- Minimalist Page Header -->
    <div class="page-header">
        <div class="page-header-info">
            <span class="page-badge-label">
                <i class="bi bi-clouds-fill"></i> Cloud Server Online &bull; MAN 3 Banjar
            </span>
            <h1 class="page-title">Dashboard Admin Website</h1>
            <p class="page-subtitle">
                Selamat datang kembali, <strong><?= htmlspecialchars($username ?? 'Administrator') ?></strong>. Kelola publikasi informasi, media visual, dan layanan madrasah.
            </p>
        </div>
        <div class="page-actions">
            <a href="<?= base_url() ?>" target="_blank" class="btn-modern-secondary">
                <i class="bi bi-box-arrow-up-right"></i> Lihat Website Depan
            </a>
            <a href="<?= base_url('berita') ?>" class="btn-modern-primary">
                <i class="bi bi-plus-lg"></i> Kelola Berita
            </a>
        </div>
    </div>

    <!-- Stat Metrics Grid -->
    <div class="row g-3 mb-4">
        <!-- Metric 1: Berita -->
        <div class="col-xl-3 col-md-6">
            <div class="stat-card">
                <div class="stat-header">
                    <div class="stat-icon stat-icon-emerald">
                        <i class="bi bi-newspaper"></i>
                    </div>
                    <span class="pill-status pill-status-success">Aktif</span>
                </div>
                <div>
                    <div class="stat-label">Total Publikasi Berita</div>
                    <div class="stat-value"><?= $total_berita ?? 0 ?></div>
                    <div class="stat-footer">
                        <strong class="text-success"><?= $total_berita_pub ?? 0 ?></strong> tayang di publik
                    </div>
                </div>
            </div>
        </div>

        <!-- Metric 2: Media & Slider -->
        <div class="col-xl-3 col-md-6">
            <div class="stat-card">
                <div class="stat-header">
                    <div class="stat-icon stat-icon-amber">
                        <i class="bi bi-images"></i>
                    </div>
                    <span class="pill-status pill-status-warning"><?= $total_banner ?? 0 ?> Slide</span>
                </div>
                <div>
                    <div class="stat-label">Banner &amp; Media Visual</div>
                    <div class="stat-value"><?= ($total_banner ?? 0) + ($total_galeri ?? 0) ?></div>
                    <div class="stat-footer">
                        <?= $total_galeri ?? 0 ?> foto galeri &bull; <?= $total_pamflet ?? 0 ?> pamflet
                    </div>
                </div>
            </div>
        </div>

        <!-- Metric 3: Foto Ijazah Kelas XII -->
        <div class="col-xl-3 col-md-6">
            <div class="stat-card">
                <div class="stat-header">
                    <div class="stat-icon stat-icon-blue">
                        <i class="bi bi-mortarboard-fill"></i>
                    </div>
                    <span class="pill-status pill-status-neutral"><?= $total_pending_foto ?? 0 ?> Review</span>
                </div>
                <div>
                    <div class="stat-label">Foto Ijazah Terverifikasi</div>
                    <div class="stat-value"><?= $total_verified_foto ?? 0 ?></div>
                    <div class="stat-footer">
                        Format siap cetak {NISN}.jpg &bull; Max 1MB
                    </div>
                </div>
            </div>
        </div>

        <!-- Metric 4: PMB Online / Superadmin -->
        <div class="col-xl-3 col-md-6">
            <div class="stat-card">
                <div class="stat-header">
                    <div class="stat-icon stat-icon-indigo">
                        <i class="bi bi-people-fill"></i>
                    </div>
                    <span class="pill-status pill-status-success">Online 24h</span>
                </div>
                <div>
                    <div class="stat-label"><?= in_array($role ?? '', ['admin', 'admin_master']) ? 'Pendaftar PMB' : 'Portal Informasi' ?></div>
                    <div class="stat-value"><?= in_array($role ?? '', ['admin', 'admin_master']) ? ($total_ppdb ?? 0) : 'Live' ?></div>
                    <div class="stat-footer">
                        <?= in_array($role ?? '', ['admin', 'admin_master']) ? 'Calon siswa mendaftar' : 'Sinkronisasi Cloud server normal' ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Navigation Hub Cards -->
    <div class="row g-4 mb-4">
        <!-- Kolom Kiri: Modul Cepat Konten Website -->
        <div class="col-lg-8">
            <div class="modern-card">
                <div class="modern-card-header">
                    <div>
                        <h2 class="modern-card-title"><i class="bi bi-grid-fill text-success"></i> Kelola Konten &amp; Informasi Publik</h2>
                        <p class="modern-card-subtitle">Pusat kendali konten yang ditampilkan pada website publik madrasah.</p>
                    </div>
                </div>
                <div class="modern-card-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <a href="<?= base_url('berita') ?>" class="modern-card modern-card-hover p-3 d-flex align-items-center gap-3 text-decoration-none text-dark">
                                <div class="stat-icon stat-icon-emerald flex-shrink-0">
                                    <i class="bi bi-newspaper"></i>
                                </div>
                                <div class="min-w-0">
                                    <div class="fw-bold" style="font-size: 14.5px;">Artikel &amp; Berita</div>
                                    <div class="text-muted" style="font-size: 12px;">Tulis, sunting, dan publikasikan warta kegiatan.</div>
                                </div>
                                <i class="bi bi-chevron-right text-muted ms-auto"></i>
                            </a>
                        </div>

                        <div class="col-md-6">
                            <a href="<?= base_url('admin_banner') ?>" class="modern-card modern-card-hover p-3 d-flex align-items-center gap-3 text-decoration-none text-dark">
                                <div class="stat-icon stat-icon-amber flex-shrink-0">
                                    <i class="bi bi-images"></i>
                                </div>
                                <div class="min-w-0">
                                    <div class="fw-bold" style="font-size: 14.5px;">Banner Slider Beranda</div>
                                    <div class="text-muted" style="font-size: 12px;">Atur carousel hero dan highlight utama beranda.</div>
                                </div>
                                <i class="bi bi-chevron-right text-muted ms-auto"></i>
                            </a>
                        </div>

                        <div class="col-md-6">
                            <a href="<?= base_url('admin_website/profil') ?>" class="modern-card modern-card-hover p-3 d-flex align-items-center gap-3 text-decoration-none text-dark">
                                <div class="stat-icon stat-icon-blue flex-shrink-0">
                                    <i class="bi bi-building"></i>
                                </div>
                                <div class="min-w-0">
                                    <div class="fw-bold" style="font-size: 14.5px;">Profil &amp; Visi Misi</div>
                                    <div class="text-muted" style="font-size: 12px;">Informasi madrasah, jam layanan, kontak &amp; medsos.</div>
                                </div>
                                <i class="bi bi-chevron-right text-muted ms-auto"></i>
                            </a>
                        </div>

                        <div class="col-md-6">
                            <a href="<?= base_url('admin_struktur') ?>" class="modern-card modern-card-hover p-3 d-flex align-items-center gap-3 text-decoration-none text-dark">
                                <div class="stat-icon stat-icon-indigo flex-shrink-0">
                                    <i class="bi bi-diagram-3-fill"></i>
                                </div>
                                <div class="min-w-0">
                                    <div class="fw-bold" style="font-size: 14.5px;">Struktur Organisasi</div>
                                    <div class="text-muted" style="font-size: 12px;">Bagan pengelola, wakamad, guru, dan staf madrasah.</div>
                                </div>
                                <i class="bi bi-chevron-right text-muted ms-auto"></i>
                            </a>
                        </div>

                        <div class="col-md-6">
                            <a href="<?= base_url('admin_website/galeri') ?>" class="modern-card modern-card-hover p-3 d-flex align-items-center gap-3 text-decoration-none text-dark">
                                <div class="stat-icon stat-icon-emerald flex-shrink-0">
                                    <i class="bi bi-camera-reels-fill"></i>
                                </div>
                                <div class="min-w-0">
                                    <div class="fw-bold" style="font-size: 14.5px;">Galeri &amp; Dokumentasi</div>
                                    <div class="text-muted" style="font-size: 12px;">Koleksi foto kegiatan dan prestasi madrasah.</div>
                                </div>
                                <i class="bi bi-chevron-right text-muted ms-auto"></i>
                            </a>
                        </div>

                        <div class="col-md-6">
                            <a href="<?= base_url('admin_foto_ijazah') ?>" class="modern-card modern-card-hover p-3 d-flex align-items-center gap-3 text-decoration-none text-dark">
                                <div class="stat-icon stat-icon-blue flex-shrink-0">
                                    <i class="bi bi-camera-fill"></i>
                                </div>
                                <div class="min-w-0">
                                    <div class="fw-bold" style="font-size: 14.5px;">Foto Ijazah Kelas XII</div>
                                    <div class="text-muted" style="font-size: 12px;">Review verifikasi dan download batch ZIP 1MB.</div>
                                </div>
                                <i class="bi bi-chevron-right text-muted ms-auto"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Kolom Kanan: Tautan Cepat & Status Layanan -->
        <div class="col-lg-4">
            <div class="modern-card mb-4">
                <div class="modern-card-header">
                    <div>
                        <h2 class="modern-card-title"><i class="bi bi-link-45deg text-success"></i> Tautan Publik</h2>
                        <p class="modern-card-subtitle">Akses cepat menuju halaman publik.</p>
                    </div>
                </div>
                <div class="modern-card-body d-flex flex-column gap-2 p-3">
                    <a href="<?= base_url('verifikasi_foto_ijazah') ?>" target="_blank" class="p-2 px-3 rounded-3 border bg-light text-decoration-none text-dark d-flex align-items-center justify-content-between hover-bg">
                        <div>
                            <div class="fw-bold small">Portal Siswa Foto Ijazah</div>
                            <div class="text-muted" style="font-size: 11px;">/verifikasi_foto_ijazah</div>
                        </div>
                        <i class="bi bi-box-arrow-up-right text-success small"></i>
                    </a>

                    <a href="<?= base_url('ppdb') ?>" target="_blank" class="p-2 px-3 rounded-3 border bg-light text-decoration-none text-dark d-flex align-items-center justify-content-between hover-bg">
                        <div>
                            <div class="fw-bold small">Pendaftaran PMB Online</div>
                            <div class="text-muted" style="font-size: 11px;">/ppdb</div>
                        </div>
                        <i class="bi bi-box-arrow-up-right text-primary small"></i>
                    </a>

                    <a href="<?= base_url() ?>" target="_blank" class="p-2 px-3 rounded-3 border bg-light text-decoration-none text-dark d-flex align-items-center justify-content-between hover-bg">
                        <div>
                            <div class="fw-bold small">Beranda Website Utama</div>
                            <div class="text-muted" style="font-size: 11px;">man3banjar.sch.id</div>
                        </div>
                        <i class="bi bi-box-arrow-up-right text-success small"></i>
                    </a>
                </div>
            </div>

            <!-- Kartu Info Admin -->
            <div class="modern-card p-4 text-center">
                <div class="user-mini-avatar mx-auto mb-2" style="width:52px;height:52px;font-size:20px;border-radius:16px;">
                    <?= $userInitial ?? 'A' ?>
                </div>
                <h5 class="fw-bold mb-0" style="font-size:15px;"><?= htmlspecialchars($username ?? 'Administrator') ?></h5>
                <span class="pill-status pill-status-success mt-2 mb-3"><?= htmlspecialchars($roleText ?? 'Admin Website') ?></span>
                <p class="text-muted mb-3" style="font-size:12px;">
                    Pastikan selalu melakukan logout setelah selesai mengelola konten demi keamanan akun.
                </p>
                <a href="<?= base_url('auth/logout') ?>" class="btn-modern-danger w-100 justify-content-center" onclick="return confirm('Keluar dari sesi admin?')">
                    <i class="bi bi-box-arrow-right"></i> Keluar (Logout)
                </a>
            </div>
        </div>
    </div>

</div>
