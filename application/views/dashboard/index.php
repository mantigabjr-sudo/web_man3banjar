<div class="content p-4">
    <div class="container-fluid">

        <!-- Welcome Banner -->
        <div class="p-4 rounded-4 mb-4 text-white shadow-sm" style="background: linear-gradient(135deg, #064e3b 0%, #059669 50%, #10b981 100%);">
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-3">
                <div>
                    <span class="badge bg-white bg-opacity-25 text-white fw-bold px-3 py-1 rounded-pill mb-2">
                        <i class="bi bi-clouds-fill me-1"></i> Cloud Server MAN 3 Banjar
                    </span>
                    <h3 class="fw-bold mb-1">Selamat Datang, <?= htmlspecialchars($username ?? 'Administrator') ?>!</h3>
                    <p class="mb-0 text-white-50" style="font-size: 14px;">
                        Portal Administrasi Terpadu untuk mengelola Website Madrasah, Penerimaan Murid Baru, dan Verifikasi Foto Ijazah.
                    </p>
                </div>
                <div class="d-flex gap-2 flex-wrap">
                    <a href="<?= base_url() ?>" target="_blank" class="btn btn-light text-dark fw-bold rounded-pill px-3 shadow-sm">
                        <i class="bi bi-globe me-1"></i> Buka Website Publik
                    </a>
                </div>
            </div>
        </div>

        <!-- Section: Fitur Utama -->
        <h5 class="fw-bold text-dark mb-3"><i class="bi bi-grid-fill text-success me-2"></i> Modul Layanan Aktif</h5>

        <div class="row g-3 mb-4">

            <!-- Card 1: Verifikasi Foto Ijazah -->
            <div class="col-lg-4 col-md-6">
                <div class="card border-0 rounded-4 shadow-sm h-100 p-3 hover-shadow" style="transition: all 0.2s; border-left: 5px solid #10b981 !important;">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <div class="p-3 rounded-3 bg-success-subtle text-success fs-3">
                            <i class="bi bi-mortarboard-fill"></i>
                        </div>
                        <span class="badge bg-success text-white fw-bold rounded-pill px-3 py-1">Aktif</span>
                    </div>
                    <h5 class="fw-bold text-dark mb-1">Foto Ijazah Kelas XII</h5>
                    <p class="text-muted small mb-3">
                        Kelola verifikasi mandiri foto ijazah siswa kelas XII, verifikasi langsung admin, dan download ZIP siap cetak format {NISN}.jpg.
                    </p>
                    <div class="d-flex justify-content-between p-2 rounded-3 bg-light border mb-3 small">
                        <span>Terverifikasi: <strong><?= $total_verified_foto ?></strong></span>
                        <span>Foto Mentah: <strong><?= $total_pending_foto ?></strong></span>
                    </div>
                    <a href="<?= base_url('admin_foto_ijazah') ?>" class="btn btn-success rounded-pill fw-bold w-100 mt-auto shadow-sm">
                        <i class="bi bi-camera-fill me-1"></i> Buka Kelola Foto Ijazah &rarr;
                    </a>
                </div>
            </div>

            <!-- Card 2: PPDB / PMB Online -->
            <div class="col-lg-4 col-md-6">
                <div class="card border-0 rounded-4 shadow-sm h-100 p-3 hover-shadow" style="transition: all 0.2s; border-left: 5px solid #3b82f6 !important;">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <div class="p-3 rounded-3 bg-primary-subtle text-primary fs-3">
                            <i class="bi bi-person-lines-fill"></i>
                        </div>
                        <span class="badge bg-primary text-white fw-bold rounded-pill px-3 py-1">PMB Online</span>
                    </div>
                    <h5 class="fw-bold text-dark mb-1">Penerimaan Murid Baru</h5>
                    <p class="text-muted small mb-3">
                        Verifikasi berkas pendaftaran calon peserta didik baru, monitoring pendaftar, dan export kelulusan PMB.
                    </p>
                    <div class="d-flex justify-content-between p-2 rounded-3 bg-light border mb-3 small">
                        <span>Total Pendaftar: <strong><?= $total_ppdb ?> Siswa</strong></span>
                        <span class="text-success fw-bold">Online 24 Jam</span>
                    </div>
                    <a href="<?= base_url('admin_ppdb') ?>" class="btn btn-primary rounded-pill fw-bold w-100 mt-auto shadow-sm">
                        <i class="bi bi-person-check-fill me-1"></i> Buka Kelola PMB &rarr;
                    </a>
                </div>
            </div>

            <!-- Card 3: Kelola Website & Berita -->
            <div class="col-lg-4 col-md-6">
                <div class="card border-0 rounded-4 shadow-sm h-100 p-3 hover-shadow" style="transition: all 0.2s; border-left: 5px solid #f59e0b !important;">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <div class="p-3 rounded-3 bg-warning-subtle text-warning fs-3">
                            <i class="bi bi-globe2"></i>
                        </div>
                        <span class="badge bg-warning text-dark fw-bold rounded-pill px-3 py-1">Website Profil</span>
                    </div>
                    <h5 class="fw-bold text-dark mb-1">Berita &amp; Informasi</h5>
                    <p class="text-muted small mb-3">
                        Publikasi berita kegiatan madrasah, artikel edukasi, pembaruan galeri foto, profil sekolah, dan pamflet pengumuman.
                    </p>
                    <div class="d-flex justify-content-between p-2 rounded-3 bg-light border mb-3 small">
                        <span>Total Berita: <strong><?= $total_berita ?> Artikel</strong></span>
                        <span class="text-muted">Siap Tayang</span>
                    </div>
                    <div class="d-flex gap-2 mt-auto">
                        <a href="<?= base_url('berita') ?>" class="btn btn-outline-warning text-dark rounded-pill fw-bold flex-grow-1 shadow-sm">
                            <i class="bi bi-newspaper me-1"></i> Berita
                        </a>
                        <a href="<?= base_url('admin_website/profil') ?>" class="btn btn-warning rounded-pill fw-bold flex-grow-1 shadow-sm">
                            <i class="bi bi-gear-fill me-1"></i> Profil
                        </a>
                    </div>
                </div>
            </div>

        </div>

        <!-- Quick Access Tautan Siswa -->
        <div class="card border-0 rounded-4 shadow-sm p-4 bg-light">
            <div class="row align-items-center g-3">
                <div class="col-md-8">
                    <h6 class="fw-bold text-dark mb-1"><i class="bi bi-link-45deg text-success fs-5"></i> Tautan Publik untuk Siswa / Orang Tua:</h6>
                    <div class="d-flex gap-2 flex-wrap mt-2">
                        <a href="<?= base_url('verifikasi_foto_ijazah') ?>" target="_blank" class="badge bg-white text-dark border p-2 text-decoration-none shadow-sm">
                            <i class="bi bi-box-arrow-up-right text-success me-1"></i> Portal Verifikasi Foto: <strong>/verifikasi_foto_ijazah</strong>
                        </a>
                        <a href="<?= base_url('ppdb') ?>" target="_blank" class="badge bg-white text-dark border p-2 text-decoration-none shadow-sm">
                            <i class="bi bi-box-arrow-up-right text-primary me-1"></i> Pendaftaran PMB: <strong>/ppdb</strong>
                        </a>
                    </div>
                </div>
                <div class="col-md-4 text-md-end">
                    <a href="<?= base_url('auth/logout') ?>" class="btn btn-outline-danger rounded-pill px-3 fw-bold btn-sm" onclick="return confirm('Keluar dari sesi admin?')">
                        <i class="bi bi-box-arrow-right me-1"></i> Logout Sesi Admin
                    </a>
                </div>
            </div>
        </div>

    </div>
</div>
