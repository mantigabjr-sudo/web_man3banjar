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
                            <i class="bi bi-megaphone-fill me-1"></i> CALLOUT SOROTAN BERANDA
                        </span>
                        <h2 class="fw-bold mb-2 text-white">Banner Pengumuman Beranda</h2>
                        <p class="mb-3 text-white-50" style="font-size: 14px; max-width: 620px;">
                            Kelola banner sorotan penting di beranda website madrasah. Anda dapat mengaktifkan pengumuman untuk verifikasi ijazah, PMB, info libur, atau menonaktifkannya kapan saja dengan satu klik.
                        </p>
                        <div class="d-flex flex-wrap gap-2 pt-1">
                            <a href="<?= base_url() ?>" target="_blank" class="btn btn-light text-dark fw-bold rounded-pill px-3 shadow-sm">
                                <i class="bi bi-box-arrow-up-right text-success me-1"></i> Pratinjau Beranda Website
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Hiasan Bulat Transparan -->
            <div style="position: absolute; right: -40px; top: -40px; width: 180px; height: 180px; background: radial-gradient(circle, rgba(255,255,255,0.2) 0%, transparent 70%); border-radius: 50%;"></div>
        </div>

        <form method="post" action="<?= base_url('admin_website/save_pengumuman') ?>">
            <div class="row g-4">
                
                <!-- Kolom Kiri: Form Konfigurasi -->
                <div class="col-lg-6">
                    <div class="card border-0 rounded-4 shadow-sm mb-4">
                        <div class="card-header bg-white border-bottom pt-3 pb-2 px-4 d-flex justify-content-between align-items-center">
                            <h6 class="fw-bold mb-0 text-dark"><i class="bi bi-sliders text-success me-2"></i> Pengaturan Konten Pengumuman</h6>
                        </div>
                        <div class="card-body p-4">

                            <!-- Toggle Status Aktif -->
                            <div class="p-3 rounded-4 bg-light mb-4 border d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="fw-bold text-dark mb-1">Status Pengumuman di Beranda</h6>
                                    <small class="text-muted">Tampilkan atau sembunyikan banner ini dari halaman depan.</small>
                                </div>
                                <div class="form-check form-switch fs-4 mb-0">
                                    <input class="form-check-input" type="checkbox" role="switch" name="aktif" value="1" id="switchAktif" <?= (!empty($pengumuman->aktif)) ? 'checked' : '' ?>>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-bold small text-muted">Teks Label / Kategori Badge</label>
                                <input type="text" name="badge" id="inputBadge" class="form-control rounded-3" 
                                       value="<?= htmlspecialchars($pengumuman->badge ?? 'PENGUMUMAN PENTING', ENT_QUOTES, 'UTF-8') ?>" 
                                       placeholder="Contoh: PENGUMUMAN KELAS XII">
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-bold small text-muted">Judul Pengumuman <span class="text-danger">*</span></label>
                                <input type="text" name="judul" id="inputJudul" class="form-control rounded-3" 
                                       value="<?= htmlspecialchars($pengumuman->judul ?? '', ENT_QUOTES, 'UTF-8') ?>" 
                                       placeholder="Contoh: Verifikasi Mandiri Foto Ijazah Siswa Telah Dibuka" required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-bold small text-muted">Isi Ringkas Pengumuman</label>
                                <textarea name="isi" id="inputIsi" class="form-control rounded-3" rows="3" 
                                          placeholder="Tuliskan keterangan detail pengumuman..."><?= htmlspecialchars($pengumuman->isi ?? '', ENT_QUOTES, 'UTF-8') ?></textarea>
                            </div>

                            <div class="row g-3 mb-3">
                                <div class="col-md-6">
                                    <label class="form-label fw-bold small text-muted">Teks Tombol Aksi (Opsional)</label>
                                    <input type="text" name="tombol_teks" id="inputBtnText" class="form-control rounded-3" 
                                           value="<?= htmlspecialchars($pengumuman->tombol_teks ?? '', ENT_QUOTES, 'UTF-8') ?>" 
                                           placeholder="Contoh: Verifikasi Sekarang →">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-bold small text-muted">Tautan / URL Tombol</label>
                                    <input type="text" name="tombol_url" class="form-control rounded-3" 
                                           value="<?= htmlspecialchars($pengumuman->tombol_url ?? '', ENT_QUOTES, 'UTF-8') ?>" 
                                           placeholder="verifikasi_foto_ijazah atau https://...">
                                </div>
                            </div>

                            <div class="mb-4">
                                <label class="form-label fw-bold small text-muted">Tema Warna Banner</label>
                                <select name="tema_warna" id="selectTema" class="form-select rounded-3">
                                    <option value="emerald" <?= (($pengumuman->tema_warna ?? 'emerald') === 'emerald') ? 'selected' : '' ?>>Hijau Emerald (Default MAN 3 Banjar)</option>
                                    <option value="blue" <?= (($pengumuman->tema_warna ?? '') === 'blue') ? 'selected' : '' ?>>Biru Samudra (Info Resmi / PMB)</option>
                                    <option value="amber" <?= (($pengumuman->tema_warna ?? '') === 'amber') ? 'selected' : '' ?>>Kuning Amber (Pemberitahuan Mendesak)</option>
                                    <option value="slate" <?= (($pengumuman->tema_warna ?? '') === 'slate') ? 'selected' : '' ?>>Dark Slate (Elegan & Formal)</option>
                                </select>
                            </div>

                            <button type="submit" class="btn btn-success fw-bold rounded-pill w-100 py-2 shadow-sm">
                                <i class="bi bi-check-circle-fill me-1"></i> Simpan Pengaturan Pengumuman
                            </button>

                        </div>
                    </div>
                </div>

                <!-- Kolom Kanan: Live Interactive Preview -->
                <div class="col-lg-6">
                    <div class="card border-0 rounded-4 shadow-sm mb-4">
                        <div class="card-header bg-white border-bottom pt-3 pb-2 px-4">
                            <h6 class="fw-bold mb-0 text-dark"><i class="bi bi-eye-fill text-success me-2"></i> Pratinjau Tampilan Beranda</h6>
                        </div>
                        <div class="card-body p-4 bg-light">
                            <p class="small text-muted mb-3">Beginilah tampilan banner di halaman beranda website publik pengunjung:</p>

                            <!-- Live Card Box -->
                            <div id="previewCard" class="p-3 p-md-4 rounded-4 shadow-sm border d-flex flex-column flex-md-row align-items-center justify-content-between gap-3"
                                 style="background: linear-gradient(135deg, #064e3b 0%, #059669 100%); color: #ffffff; transition: all 0.3s ease;">
                                <div class="d-flex align-items-center gap-3">
                                    <div class="p-3 rounded-4 bg-white text-success fw-bold fs-3 d-flex align-items-center justify-content-center" style="width: 54px; height: 54px; flex-shrink: 0;">
                                        <i class="bi bi-megaphone-fill"></i>
                                    </div>
                                    <div>
                                        <span id="previewBadge" class="badge bg-warning text-dark fw-bold px-3 py-1 rounded-pill mb-1" style="font-size: 11px;">
                                            <?= htmlspecialchars($pengumuman->badge ?? 'PENGUMUMAN', ENT_QUOTES, 'UTF-8') ?>
                                        </span>
                                        <h5 id="previewJudul" class="fw-bold mb-1 text-white" style="font-size: 16px;">
                                            <?= htmlspecialchars($pengumuman->judul ?? 'Judul Pengumuman Beranda', ENT_QUOTES, 'UTF-8') ?>
                                        </h5>
                                        <p id="previewIsi" class="mb-0 text-white-50 small" style="font-size: 13px;">
                                            <?= htmlspecialchars($pengumuman->isi ?? 'Deskripsi pengumuman akan ditampilkan di sini.', ENT_QUOTES, 'UTF-8') ?>
                                        </p>
                                    </div>
                                </div>
                                <div id="previewBtnWrap">
                                    <span id="previewBtn" class="btn btn-warning text-dark fw-bold rounded-pill px-4 py-2 shadow-sm text-nowrap" style="font-size: 13.5px;">
                                        <?= htmlspecialchars($pengumuman->tombol_teks ?? 'Lihat Detail →', ENT_QUOTES, 'UTF-8') ?>
                                    </span>
                                </div>
                            </div>

                            <div class="mt-4 p-3 bg-white rounded-3 border small text-muted">
                                <i class="bi bi-info-circle-fill text-primary me-1"></i>
                                Jika switch status dinonaktifkan (OFF), maka banner ini otomatis tidak akan tampil di beranda dan halaman muka akan langsung menampilkan berita & konten madrasah.
                            </div>

                        </div>
                    </div>
                </div>

            </div>
        </form>

    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function(){
    const inputBadge = document.getElementById('inputBadge');
    const inputJudul = document.getElementById('inputJudul');
    const inputIsi   = document.getElementById('inputIsi');
    const inputBtn   = document.getElementById('inputBtnText');
    const selectTema = document.getElementById('selectTema');

    const previewBadge = document.getElementById('previewBadge');
    const previewJudul = document.getElementById('previewJudul');
    const previewIsi   = document.getElementById('previewIsi');
    const previewBtn   = document.getElementById('previewBtn');
    const previewCard  = document.getElementById('previewCard');

    function updatePreview(){
        previewBadge.textContent = inputBadge.value.trim() || 'PENGUMUMAN';
        previewJudul.textContent = inputJudul.value.trim() || 'Judul Pengumuman Beranda';
        previewIsi.textContent   = inputIsi.value.trim() || 'Deskripsi pengumuman akan ditampilkan di sini.';
        
        if(inputBtn.value.trim()){
            previewBtn.style.display = 'inline-block';
            previewBtn.textContent   = inputBtn.value.trim();
        } else {
            previewBtn.style.display = 'none';
        }

        const tema = selectTema.value;
        if(tema === 'blue'){
            previewCard.style.background = 'linear-gradient(135deg, #1e3a8a 0%, #2563eb 100%)';
        } else if(tema === 'amber'){
            previewCard.style.background = 'linear-gradient(135deg, #b45309 0%, #f59e0b 100%)';
        } else if(tema === 'slate'){
            previewCard.style.background = 'linear-gradient(135deg, #0f172a 0%, #334155 100%)';
        } else {
            previewCard.style.background = 'linear-gradient(135deg, #064e3b 0%, #059669 100%)';
        }
    }

    inputBadge.addEventListener('input', updatePreview);
    inputJudul.addEventListener('input', updatePreview);
    inputIsi.addEventListener('input', updatePreview);
    inputBtn.addEventListener('input', updatePreview);
    selectTema.addEventListener('change', updatePreview);

    updatePreview();
});
</script>

<?php $this->load->view('templates/footer'); ?>
