<?php $this->load->view('templates/header'); ?>
<?php $this->load->view('templates/sidebar'); ?>

<div class="content">

<style>
.webset-hero{
    background:
        radial-gradient(circle at top right, rgba(34,197,94,.16), transparent 34%),
        linear-gradient(135deg,#ecfdf5,#ffffff);
    border:1px solid #dcfce7;
    border-radius:24px;
    padding:22px 26px;
    box-shadow:0 14px 35px rgba(15,23,42,.06);
    margin-bottom:24px;
}

.webset-hero p{
    color:#64748b;
    font-weight:600;
    margin:6px 0 0;
    font-size:14px;
}

.webset-card{
    background:#fff;
    border:1px solid #e2e8f0;
    border-radius:22px;
    box-shadow:0 10px 30px rgba(15,23,42,.04);
    margin-bottom:24px;
    overflow:hidden;
    transition:box-shadow .2s ease;
}

.webset-card:hover{
    box-shadow:0 14px 36px rgba(15,23,42,.07);
}

.webset-head{
    padding:18px 24px;
    border-bottom:1px solid #e2e8f0;
    background:#fafcfb;
    display:flex;
    align-items:center;
    gap:12px;
}

.webset-head-icon{
    width:42px;
    height:42px;
    border-radius:12px;
    display:flex;
    align-items:center;
    justify-content:center;
    font-size:20px;
}

.webset-head h5{
    margin:0;
    color:#0f172a;
    font-size:16px;
    font-weight:900;
}

.webset-head small{
    display:block;
    color:#64748b;
    font-weight:600;
    font-size:12.5px;
    margin-top:2px;
}

.webset-body{
    padding:24px;
}

.webset-grid{
    display:grid;
    grid-template-columns:repeat(2,1fr);
    gap:18px;
}

.webset-field label{
    display:flex;
    justify-content:space-between;
    align-items:center;
    color:#334155;
    font-size:13px;
    font-weight:800;
    margin-bottom:7px;
}

.webset-field label span.hint{
    font-size:11.5px;
    font-weight:600;
    color:#94a3b8;
}

.webset-input,
.webset-textarea{
    width:100%;
    border:1.5px solid #cbd5e1;
    background:#f8fafc;
    border-radius:14px;
    padding:11px 14px;
    color:#0f172a;
    font-weight:600;
    font-size:13.5px;
    outline:none;
    transition:border-color .2s, box-shadow .2s, background-color .2s;
}

.webset-input{
    min-height:46px;
}

.webset-textarea{
    min-height:120px;
    resize:vertical;
    line-height:1.65;
}

.webset-input:focus,
.webset-textarea:focus{
    background:white;
    border-color:#16a34a;
    box-shadow:0 0 0 4px rgba(22,163,74,.12);
}

.webset-full{
    grid-column:1 / -1;
}

.btn-webset-save{
    min-height:48px;
    border:0;
    border-radius:16px;
    padding:0 26px;
    background:linear-gradient(135deg,#15803d,#22c55e);
    color:white;
    font-size:14px;
    font-weight:900;
    box-shadow:0 12px 26px rgba(22,163,74,.24);
    display:inline-flex;
    align-items:center;
    gap:9px;
    cursor:pointer;
    transition:transform .15s, box-shadow .15s;
}

.btn-webset-save:hover{
    transform:translateY(-1px);
    box-shadow:0 15px 30px rgba(22,163,74,.32);
}

.sambutan-preview-wrap{
    display:flex;
    align-items:center;
    gap:18px;
    padding:14px;
    background:#f1f5f9;
    border-radius:16px;
    margin-bottom:14px;
}

.sambutan-preview-img{
    width:72px;
    height:90px;
    object-fit:cover;
    border-radius:10px;
    box-shadow:0 4px 10px rgba(0,0,0,.1);
    border:2px solid #fff;
    background:#fff;
}

@media(max-width:768px){
    .webset-grid{
        grid-template-columns:1fr;
    }
    .webset-body{
        padding:18px;
    }
    .btn-webset-save{
        width:100%;
        justify-content:center;
    }
}
</style>

<div class="webset-hero">
    <div class="d-flex justify-content-between align-items-center gap-3 flex-wrap">
        <div>
            <h2 class="glow mb-1" style="font-weight:900; font-size:26px; color:#0f172a;">Manajemen Portal Website</h2>
            <p>Atur seluruh teks headline beranda, sambutan kepala madrasah, visi misi, profil, dan kontak secara dinamis.</p>
        </div>
        <div class="d-flex gap-2 flex-wrap">
            <a href="<?= base_url() ?>" target="_blank" class="btn btn-outline-dark fw-bold d-inline-flex align-items-center rounded-4 px-3" style="border-width:2px; font-weight:800; font-size:13px;">
                <i class="bi bi-box-arrow-up-right me-1.5"></i> Lihat Beranda Website
            </a>
            <a href="<?= base_url('admin_cloud_sync/sync_website') ?>" class="btn btn-outline-success fw-bold d-inline-flex align-items-center rounded-4 px-3" style="border-width:2px; font-weight:800; font-size:13px;" onclick="return confirm('Kirim seluruh profil website lokal ke man3banjar.sch.id?');">
                <i class="bi bi-cloud-arrow-up-fill me-1.5"></i> Kirim ke Online
            </a>
            <a href="<?= base_url('admin_cloud_sync/pull_website') ?>" class="btn btn-outline-primary fw-bold d-inline-flex align-items-center rounded-4 px-3" style="border-width:2px; font-weight:800; font-size:13px;" onclick="return confirm('Tarik data profil & konten website terbaru dari man3banjar.sch.id ke lokal?');">
                <i class="bi bi-cloud-arrow-down-fill me-1.5"></i> Tarik dari Online
            </a>
        </div>
    </div>
</div>

<?php if($this->session->flashdata('success')): ?>
    <div class="alert alert-success rounded-4 d-flex align-items-center gap-2 mb-4" style="border:1px solid #bbf7d0;">
        <i class="bi bi-check-circle-fill text-success fs-5"></i>
        <div class="fw-bold"><?= $this->session->flashdata('success') ?></div>
    </div>
<?php endif; ?>

<?php if($this->session->flashdata('error')): ?>
    <div class="alert alert-danger rounded-4 d-flex align-items-center gap-2 mb-4">
        <i class="bi bi-exclamation-triangle-fill text-danger fs-5"></i>
        <div class="fw-bold"><?= $this->session->flashdata('error') ?></div>
    </div>
<?php endif; ?>

<form method="post" action="<?= base_url('admin_website/save_profil') ?>" enctype="multipart/form-data">

    <!-- SEKSI 1: HERO & HEADLINE UTAMA BERANDA -->
    <div class="webset-card">
        <div class="webset-head">
            <div class="webset-head-icon" style="background:#e0f2fe; color:#0284c7;">
                <i class="bi bi-megaphone-fill"></i>
            </div>
            <div>
                <h5>Pengaturan Hero & Headline Utama Beranda</h5>
                <small>Teks sambutan pertama yang dilihat oleh pengunjung saat membuka halaman depan website.</small>
            </div>
        </div>

        <div class="webset-body">
            <div class="webset-grid">

                <div class="webset-field webset-full">
                    <label>Badge / Label Tagline Atas <span class="hint">Tampil di atas judul utama hero</span></label>
                    <input type="text"
                           name="hero_badge"
                           class="webset-input"
                           placeholder="Contoh: Portal Resmi &amp; Layanan Digital Terpadu"
                           value="<?= htmlspecialchars($profil->hero_badge ?? 'Portal Resmi & Layanan Digital Terpadu', ENT_QUOTES, 'UTF-8') ?>">
                </div>

                <div class="webset-field webset-full">
                    <label>Judul Utama Hero (Headline) <span class="hint">Gunakan kalimat inspiratif madrasah</span></label>
                    <input type="text"
                           name="hero_judul"
                           class="webset-input"
                           placeholder="Contoh: Mewujudkan Generasi Islami, Unggul, Berkarakter &amp; Melek Digital"
                           value="<?= htmlspecialchars($profil->hero_judul ?? 'Mewujudkan Generasi Islami, Unggul, Berkarakter & Melek Digital', ENT_QUOTES, 'UTF-8') ?>">
                </div>

                <div class="webset-field webset-full">
                    <label>Deskripsi Ringkas Hero <span class="hint">Paragraf pengantar di bawah judul hero</span></label>
                    <textarea name="hero_deskripsi" class="webset-textarea" style="min-height:90px;" placeholder="Tuliskan gambaran singkat tentang madrasah..."><?= htmlspecialchars($profil->hero_deskripsi ?? 'Selamat datang di Portal Resmi Madrasah Aliyah Negeri 3 Banjar. Pusat informasi akademik, layanan digital terintegrasi, dan wadah prestasi bagi seluruh civitas madrasah.', ENT_QUOTES, 'UTF-8') ?></textarea>
                </div>

                <div class="webset-field">
                    <label>Teks Tombol Aksi Utama <span class="hint">Tombol tombol sorotan pertama</span></label>
                    <input type="text"
                           name="hero_tombol_utama_teks"
                           class="webset-input"
                           placeholder="Contoh: Pendaftaran Siswa Baru (PPDB)"
                           value="<?= htmlspecialchars($profil->hero_tombol_utama_teks ?? 'Pendaftaran Siswa Baru (PPDB)', ENT_QUOTES, 'UTF-8') ?>">
                </div>

                <div class="webset-field">
                    <label>Link / URL Tombol Aksi Utama <span class="hint">Bisa berupa path lokal (ppdb) atau URL lengkap</span></label>
                    <input type="text"
                           name="hero_tombol_utama_url"
                           class="webset-input"
                           placeholder="Contoh: ppdb"
                           value="<?= htmlspecialchars($profil->hero_tombol_utama_url ?? 'ppdb', ENT_QUOTES, 'UTF-8') ?>">
                </div>

                <div class="webset-field">
                    <label>Teks Tombol Kedua <span class="hint">Tombol sekunder / penjelajahan</span></label>
                    <input type="text"
                           name="hero_tombol_kedua_teks"
                           class="webset-input"
                           placeholder="Contoh: Jelajahi Profil Madrasah"
                           value="<?= htmlspecialchars($profil->hero_tombol_kedua_teks ?? 'Jelajahi Profil Madrasah', ENT_QUOTES, 'UTF-8') ?>">
                </div>

                <div class="webset-field">
                    <label>Link / URL Tombol Kedua</label>
                    <input type="text"
                           name="hero_tombol_kedua_url"
                           class="webset-input"
                           placeholder="Contoh: website/profil"
                           value="<?= htmlspecialchars($profil->hero_tombol_kedua_url ?? 'website/profil', ENT_QUOTES, 'UTF-8') ?>">
                </div>

            </div>
        </div>
    </div>

    <!-- SEKSI 2: SAMBUTAN KEPALA MADRASAH -->
    <div class="webset-card">
        <div class="webset-head">
            <div class="webset-head-icon" style="background:#fef3c7; color:#d97706;">
                <i class="bi bi-person-badge-fill"></i>
            </div>
            <div>
                <h5>Pengaturan Sambutan Kepala Madrasah</h5>
                <small>Bagian resmi ucapan sambutan pimpinan madrasah pada beranda portal.</small>
            </div>
        </div>

        <div class="webset-body">
            <div class="webset-grid">

                <div class="webset-field webset-full">
                    <label>Judul Bagian Sambutan <span class="hint">Misal: Sambutan Kepala MAN 3 Banjar</span></label>
                    <input type="text"
                           name="sambutan_judul"
                           class="webset-input"
                           placeholder="Contoh: Sambutan Kepala MAN 3 Banjar"
                           value="<?= htmlspecialchars($profil->sambutan_judul ?? 'Sambutan Kepala Madrasah', ENT_QUOTES, 'UTF-8') ?>">
                </div>

                <div class="webset-field">
                    <label>Nama Kepala Madrasah <span class="hint">Lengkap dengan gelar akademik</span></label>
                    <input type="text"
                           name="sambutan_nama"
                           class="webset-input"
                           placeholder="Contoh: Drs. H. Ahmad Sauqi, M.Pd"
                           value="<?= htmlspecialchars($profil->sambutan_nama ?? '', ENT_QUOTES, 'UTF-8') ?>">
                </div>

                <div class="webset-field">
                    <label>Jabatan / Keterangan Pangkat <span class="hint">Misal: Kepala Madrasah Aliyah Negeri 3 Banjar</span></label>
                    <input type="text"
                           name="sambutan_jabatan"
                           class="webset-input"
                           placeholder="Contoh: Kepala MAN 3 Banjar"
                           value="<?= htmlspecialchars($profil->sambutan_jabatan ?? 'Kepala Madrasah Aliyah Negeri 3 Banjar', ENT_QUOTES, 'UTF-8') ?>">
                </div>

                <div class="webset-field webset-full">
                    <label>Isi Sambutan Kepala Madrasah <span class="hint">Pesan, visi penguatan, atau motivasi bagi siswa dan masyarakat</span></label>
                    <textarea name="sambutan_isi" class="webset-textarea" style="min-height:140px;" placeholder="Tuliskan sambutan kepala madrasah di sini..."><?= htmlspecialchars($profil->sambutan_isi ?? '', ENT_QUOTES, 'UTF-8') ?></textarea>
                </div>

                <div class="webset-field webset-full">
                    <label>Foto Resmi Kepala Madrasah <span class="hint">Format JPG/PNG/WebP, disarankan rasio 3:4 portrait</span></label>
                    
                    <?php if(!empty($profil->sambutan_foto)): ?>
                        <div class="sambutan-preview-wrap">
                            <img src="<?= base_url('uploads/website/' . $profil->sambutan_foto) ?>" alt="Foto Sambutan" class="sambutan-preview-img" onerror="this.src='<?= base_url('assets/img/user-default.png') ?>'">
                            <div>
                                <div class="fw-bold text-dark" style="font-size:13.5px;">Foto Aktif Saat Ini</div>
                                <div class="text-muted small"><?= htmlspecialchars($profil->sambutan_foto, ENT_QUOTES, 'UTF-8') ?></div>
                                <div class="badge bg-success-subtle text-success border border-success-subtle rounded-pill mt-1">Terpasang</div>
                            </div>
                        </div>
                    <?php endif; ?>

                    <input type="file"
                           name="sambutan_foto"
                           accept="image/*"
                           class="webset-input"
                           style="padding-top:8px;">
                    <small class="text-muted mt-1 d-block">Biarkan kosong jika tidak ingin mengubah foto sambutan yang ada.</small>
                </div>

            </div>
        </div>
    </div>

    <!-- SEKSI 3: PROFIL SINGKAT, VISI & MISI -->
    <div class="webset-card">
        <div class="webset-head">
            <div class="webset-head-icon" style="background:#dcfce7; color:#16a34a;">
                <i class="bi bi-book-half"></i>
            </div>
            <div>
                <h5>Profil Singkat, Visi, Misi &amp; Tujuan</h5>
                <small>Informasi jati diri dan cita-cita madrasah yang tampil di profil dan beranda.</small>
            </div>
        </div>

        <div class="webset-body">
            <div class="webset-grid">

                <div class="webset-field webset-full">
                    <label>Judul Profil Madrasah</label>
                    <input type="text"
                           name="judul_profil"
                           class="webset-input"
                           placeholder="Contoh: Profil MAN 3 Banjar"
                           value="<?= htmlspecialchars($profil->judul_profil ?? 'Profil MAN 3 Banjar', ENT_QUOTES, 'UTF-8') ?>">
                </div>

                <div class="webset-field webset-full">
                    <label>Deskripsi Singkat Profil Madrasah</label>
                    <textarea name="isi_profil" class="webset-textarea"><?= htmlspecialchars($profil->isi_profil ?? '', ENT_QUOTES, 'UTF-8') ?></textarea>
                </div>

                <div class="webset-field">
                    <label>Visi Madrasah</label>
                    <textarea name="visi" class="webset-textarea"><?= htmlspecialchars($profil->visi ?? '', ENT_QUOTES, 'UTF-8') ?></textarea>
                </div>

                <div class="webset-field">
                    <label>Misi Madrasah</label>
                    <textarea name="misi" class="webset-textarea"><?= htmlspecialchars($profil->misi ?? '', ENT_QUOTES, 'UTF-8') ?></textarea>
                </div>

                <div class="webset-field webset-full">
                    <label>Tujuan Madrasah</label>
                    <textarea name="tujuan" class="webset-textarea"><?= htmlspecialchars($profil->tujuan ?? '', ENT_QUOTES, 'UTF-8') ?></textarea>
                </div>

            </div>
        </div>
    </div>

    <!-- SEKSI 4: KONTAK, LEGALITAS & SOSIAL MEDIA -->
    <div class="webset-card">
        <div class="webset-head">
            <div class="webset-head-icon" style="background:#f3e8ff; color:#9333ea;">
                <i class="bi bi-geo-alt-fill"></i>
            </div>
            <div>
                <h5>Kontak, Identitas Lembaga &amp; Media Sosial</h5>
                <small>Informasi saluran komunikasi, lokasi, dan akun resmi madrasah.</small>
            </div>
        </div>

        <div class="webset-body">
            <div class="webset-grid">

                <div class="webset-field">
                    <label>Nomor Statistik Madrasah (NSM)</label>
                    <input type="text"
                           name="nsm"
                           class="webset-input"
                           placeholder="Contoh: 131163030003"
                           value="<?= htmlspecialchars($profil->nsm ?? '', ENT_QUOTES, 'UTF-8') ?>">
                </div>

                <div class="webset-field">
                    <label>Nomor Pokok Sekolah Nasional (NPSN)</label>
                    <input type="text"
                           name="npsn"
                           class="webset-input"
                           placeholder="Contoh: 30315264"
                           value="<?= htmlspecialchars($profil->npsn ?? '', ENT_QUOTES, 'UTF-8') ?>">
                </div>

                <div class="webset-field webset-full">
                    <label>Alamat Lengkap Madrasah</label>
                    <textarea name="alamat" class="webset-textarea" style="min-height:85px;"><?= htmlspecialchars($profil->alamat ?? '', ENT_QUOTES, 'UTF-8') ?></textarea>
                </div>

                <div class="webset-field">
                    <label>Telepon Kantor</label>
                    <input type="text"
                           name="telepon"
                           class="webset-input"
                           placeholder="Contoh: (0511) 4721234"
                           value="<?= htmlspecialchars($profil->telepon ?? '', ENT_QUOTES, 'UTF-8') ?>">
                </div>

                <div class="webset-field">
                    <label>Email Resmi</label>
                    <input type="email"
                           name="email"
                           class="webset-input"
                           placeholder="Contoh: humas@man3banjar.sch.id"
                           value="<?= htmlspecialchars($profil->email ?? '', ENT_QUOTES, 'UTF-8') ?>">
                </div>

                <div class="webset-field">
                    <label>WhatsApp Layanan / Konsultasi</label>
                    <input type="text"
                           name="whatsapp"
                           class="webset-input"
                           placeholder="Contoh: 08123456789"
                           value="<?= htmlspecialchars($profil->whatsapp ?? '', ENT_QUOTES, 'UTF-8') ?>">
                </div>

                <div class="webset-field">
                    <label>Jam Operasional / Pelayanan</label>
                    <input type="text"
                           name="jam_layanan"
                           class="webset-input"
                           placeholder="Contoh: Senin - Jumat, 07.30 - 15.30 WITA"
                           value="<?= htmlspecialchars($profil->jam_layanan ?? '', ENT_QUOTES, 'UTF-8') ?>">
                </div>

                <div class="webset-field webset-full">
                    <label>URL Rapor Digital Madrasah (RDM) <span class="hint">Tautan portal aplikasi RDM siswa</span></label>
                    <input type="text"
                           name="rdm_url"
                           class="webset-input"
                           placeholder="Contoh: https://rdm.man3banjar.com"
                           value="<?= htmlspecialchars($profil->rdm_url ?? 'https://rdm.man3banjar.com', ENT_QUOTES, 'UTF-8') ?>">
                </div>

                <div class="webset-field">
                    <label>Facebook Resmi URL</label>
                    <input type="text"
                           name="facebook_url"
                           class="webset-input"
                           placeholder="https://facebook.com/man3banjar"
                           value="<?= htmlspecialchars($profil->facebook_url ?? '', ENT_QUOTES, 'UTF-8') ?>">
                </div>

                <div class="webset-field">
                    <label>Instagram Resmi URL</label>
                    <input type="text"
                           name="instagram_url"
                           class="webset-input"
                           placeholder="https://instagram.com/man3banjar_official"
                           value="<?= htmlspecialchars($profil->instagram_url ?? '', ENT_QUOTES, 'UTF-8') ?>">
                </div>

                <div class="webset-field webset-full">
                    <label>YouTube Channel Resmi URL</label>
                    <input type="text"
                           name="youtube_url"
                           class="webset-input"
                           placeholder="https://youtube.com/@man3banjar"
                           value="<?= htmlspecialchars($profil->youtube_url ?? '', ENT_QUOTES, 'UTF-8') ?>">
                </div>

            </div>
        </div>
    </div>

    <!-- TOMBOL SIMPAN GLOBAL -->
    <div class="d-flex justify-content-end mb-5">
        <button type="submit" class="btn-webset-save">
            <i class="bi bi-check2-circle fs-5"></i> Simpan Seluruh Pengaturan Portal Website
        </button>
    </div>

</form>

</div>

<?php $this->load->view('templates/footer'); ?>