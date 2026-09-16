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

        <!-- ═══ HEADER BANNER ═══ -->
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
                            Kelola banner sorotan penting di beranda website madrasah. Anda dapat mengaktifkan atau mematikan penayangannya secara instan kapan saja.
                        </p>
                        <div class="d-flex flex-wrap gap-2 pt-1 align-items-center">
                            <!-- Tombol Sakelar Cepat 1-Klik -->
                            <?php if(!empty($pengumuman->aktif)): ?>
                                <a href="<?= base_url('admin_website/toggle_pengumuman') ?>" class="btn btn-danger fw-bold rounded-pill px-3 shadow-sm">
                                    <i class="bi bi-power me-1"></i> Matikan Pengumuman (Set OFF)
                                </a>
                            <?php else: ?>
                                <a href="<?= base_url('admin_website/toggle_pengumuman') ?>" class="btn btn-warning text-dark fw-bold rounded-pill px-3 shadow-sm">
                                    <i class="bi bi-power me-1"></i> Aktifkan Pengumuman (Set ON)
                                </a>
                            <?php endif; ?>

                            <a href="<?= base_url() ?>" target="_blank" class="btn btn-light text-dark fw-bold rounded-pill px-3 shadow-sm">
                                <i class="bi bi-box-arrow-up-right text-success me-1"></i> Buka Beranda Website
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Hiasan Bulat Transparan -->
            <div style="position: absolute; right: -40px; top: -40px; width: 180px; height: 180px; background: radial-gradient(circle, rgba(255,255,255,0.2) 0%, transparent 70%); border-radius: 50%;"></div>
        </div>

        <form method="post" action="<?= base_url('admin_website/save_pengumuman') ?>" id="formPengumuman">
            <!-- Hidden Input Nilai Aktif (0 atau 1) yang dijamin terkirim -->
            <input type="hidden" name="aktif" id="inputAktif" value="<?= !empty($pengumuman->aktif) ? '1' : '0' ?>">

            <div class="row g-4">
                
                <!-- Kolom Kiri: Form Konfigurasi -->
                <div class="col-lg-6">
                    
                    <!-- ═══ KOTAK SWITCH STATUS ON / OFF ═══ -->
                    <div class="card border-0 rounded-4 shadow-sm mb-4">
                        <div class="card-header bg-white border-bottom pt-3 pb-2 px-4 d-flex justify-content-between align-items-center">
                            <h6 class="fw-bold mb-0 text-dark">
                                <i class="bi bi-toggle2-on text-success me-2"></i> Status Penayangan di Beranda
                            </h6>
                            <span id="badgeStatusHeader" class="badge <?= !empty($pengumuman->aktif) ? 'bg-success' : 'bg-danger' ?> px-3 py-1 rounded-pill" style="font-size: 12px;">
                                <?= !empty($pengumuman->aktif) ? '● AKTIF' : '○ NONAKTIF' ?>
                            </span>
                        </div>
                        <div class="card-body p-4">
                            <p class="small text-muted mb-3">
                                Pilih apakah banner pengumuman ini tampil di halaman depan website atau disembunyikan:
                            </p>

                            <!-- Tombol Pilihan Interaktif Besar -->
                            <div class="row g-3">
                                <div class="col-6">
                                    <button type="button" id="btnPilihAktif" class="btn w-100 py-3 rounded-4 fw-bold border-2 d-flex flex-column align-items-center justify-content-center gap-1 transition-all <?= !empty($pengumuman->aktif) ? 'btn-success shadow' : 'btn-outline-secondary' ?>">
                                        <i class="bi bi-check-circle-fill fs-2"></i>
                                        <span class="fs-6">AKTIF (ON)</span>
                                        <small class="fw-normal" style="font-size: 11px;">Tampil di Halaman Depan</small>
                                    </button>
                                </div>
                                <div class="col-6">
                                    <button type="button" id="btnPilihNonaktif" class="btn w-100 py-3 rounded-4 fw-bold border-2 d-flex flex-column align-items-center justify-content-center gap-1 transition-all <?= empty($pengumuman->aktif) ? 'btn-danger shadow' : 'btn-outline-secondary' ?>">
                                        <i class="bi bi-eye-slash-fill fs-2"></i>
                                        <span class="fs-6">NONAKTIF (OFF)</span>
                                        <small class="fw-normal" style="font-size: 11px;">Sembunyikan dari Beranda</small>
                                    </button>
                                </div>
                            </div>

                            <!-- Indikator Keterangan Status -->
                            <div class="mt-3 p-3 rounded-3 bg-light border d-flex align-items-center gap-2">
                                <i id="iconStatusDesc" class="bi <?= !empty($pengumuman->aktif) ? 'bi-check-circle-fill text-success' : 'bi-info-circle-fill text-danger' ?> fs-5"></i>
                                <span class="small" id="textStatusDesc">
                                    <?= !empty($pengumuman->aktif) 
                                        ? 'Pengumuman saat ini <strong>AKTIF</strong> dan sedang tampil kepada seluruh pengunjung beranda.' 
                                        : 'Pengumuman saat ini <strong>NONAKTIF</strong> (disembunyikan dari pengunjung beranda).' ?>
                                </span>
                            </div>
                        </div>
                    </div>

                    <!-- ═══ KONTEN PENGUMUMAN ═══ -->
                    <div class="card border-0 rounded-4 shadow-sm mb-4">
                        <div class="card-header bg-white border-bottom pt-3 pb-2 px-4">
                            <h6 class="fw-bold mb-0 text-dark"><i class="bi bi-card-heading text-success me-2"></i> Rincian Teks Pengumuman</h6>
                        </div>
                        <div class="card-body p-4">

                            <div class="mb-3">
                                <label class="form-label fw-bold small text-muted">Kategori / Badge Label</label>
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
                                          placeholder="Tuliskan keterangan ringkas pengumuman..."><?= htmlspecialchars($pengumuman->isi ?? '', ENT_QUOTES, 'UTF-8') ?></textarea>
                            </div>

                            <div class="row g-3 mb-3">
                                <div class="col-md-6">
                                    <label class="form-label fw-bold small text-muted">Teks Tombol Aksi (Opsional)</label>
                                    <input type="text" name="tombol_teks" id="inputBtnText" class="form-control rounded-3" 
                                           value="<?= htmlspecialchars($pengumuman->tombol_teks ?? '', ENT_QUOTES, 'UTF-8') ?>" 
                                           placeholder="Contoh: Verifikasi Sekarang →">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-bold small text-muted">Tautan / Link Tujuan</label>
                                    <input type="text" name="tombol_url" class="form-control rounded-3" 
                                           value="<?= htmlspecialchars($pengumuman->tombol_url ?? '', ENT_QUOTES, 'UTF-8') ?>" 
                                           placeholder="verifikasi_foto_ijazah atau https://...">
                                </div>
                            </div>

                            <div class="mb-4">
                                <label class="form-label fw-bold small text-muted">Tema Warna Banner</label>
                                <select name="tema_warna" id="selectTema" class="form-select rounded-3">
                                    <option value="emerald" <?= (($pengumuman->tema_warna ?? 'emerald') === 'emerald') ? 'selected' : '' ?>>Hijau Emerald (Default MAN 3 Banjar)</option>
                                    <option value="blue" <?= (($pengumuman->tema_warna ?? '') === 'blue') ? 'selected' : '' ?>>Biru Samudra (Info Akademik / PMB)</option>
                                    <option value="amber" <?= (($pengumuman->tema_warna ?? '') === 'amber') ? 'selected' : '' ?>>Kuning Amber (Pemberitahuan Mendesak)</option>
                                    <option value="slate" <?= (($pengumuman->tema_warna ?? '') === 'slate') ? 'selected' : '' ?>>Dark Slate (Elegan & Formal)</option>
                                </select>
                            </div>

                            <button type="submit" class="btn btn-success fw-bold rounded-pill w-100 py-3 shadow-sm fs-6">
                                <i class="bi bi-check-circle-fill me-1"></i> Simpan Pengaturan Pengumuman
                            </button>

                        </div>
                    </div>
                </div>

                <!-- Kolom Kanan: Live Interactive Preview -->
                <div class="col-lg-6">
                    <div class="card border-0 rounded-4 shadow-sm mb-4 sticky-top" style="top: 20px; z-index: 10;">
                        <div class="card-header bg-white border-bottom pt-3 pb-2 px-4 d-flex justify-content-between align-items-center">
                            <h6 class="fw-bold mb-0 text-dark"><i class="bi bi-eye-fill text-success me-2"></i> Pratinjau Tampilan Beranda</h6>
                            <span class="badge bg-light text-dark border small">Live Preview</span>
                        </div>
                        <div class="card-body p-4 bg-light">
                            
                            <!-- Box Notifikasi Status Pratinjau -->
                            <div id="previewStatusNotice" class="alert <?= !empty($pengumuman->aktif) ? 'alert-success border-success' : 'alert-danger border-danger' ?> py-2 px-3 rounded-3 mb-3 d-flex align-items-center gap-2">
                                <i id="previewStatusIcon" class="bi <?= !empty($pengumuman->aktif) ? 'bi-check-circle-fill text-success' : 'bi-eye-slash-fill text-danger' ?> fs-5"></i>
                                <div class="small" id="previewStatusText">
                                    <?= !empty($pengumuman->aktif) 
                                        ? '<strong>Banner AKTIF:</strong> Banner ini akan langsung tampil di beranda website pengunjung.' 
                                        : '<strong>Banner NONAKTIF:</strong> Banner ini sedang disembunyikan dari beranda website.' ?>
                                </div>
                            </div>

                            <!-- Live Banner Card Box -->
                            <div id="previewCard" class="p-3 p-md-4 rounded-4 shadow-sm border d-flex flex-column flex-md-row align-items-center justify-content-between gap-3 position-relative"
                                 style="background: linear-gradient(135deg, #064e3b 0%, #059669 100%); color: #ffffff; transition: all 0.3s ease; <?= empty($pengumuman->aktif) ? 'opacity: 0.45; filter: grayscale(40%); border: 2px dashed #dc3545 !important;' : '' ?>">
                                
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
                                <i class="bi bi-shield-check text-success me-1"></i>
                                <strong>Tips:</strong> Anda dapat mengklik tombol <em>"Matikan Pengumuman"</em> atau <em>"Aktifkan Pengumuman"</em> di header atas untuk beralih status secara instan tanpa perlu mengisi ulang form.
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
    const inputAktif     = document.getElementById('inputAktif');
    const btnPilihAktif  = document.getElementById('btnPilihAktif');
    const btnPilihNonaktif = document.getElementById('btnPilihNonaktif');
    const badgeStatusHeader = document.getElementById('badgeStatusHeader');
    const iconStatusDesc = document.getElementById('iconStatusDesc');
    const textStatusDesc = document.getElementById('textStatusDesc');

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

    const previewStatusNotice = document.getElementById('previewStatusNotice');
    const previewStatusIcon   = document.getElementById('previewStatusIcon');
    const previewStatusText   = document.getElementById('previewStatusText');

    function setStatus(isAktif){
        inputAktif.value = isAktif ? '1' : '0';

        if(isAktif){
            btnPilihAktif.className = 'btn w-100 py-3 rounded-4 fw-bold border-2 d-flex flex-column align-items-center justify-content-center gap-1 transition-all btn-success shadow';
            btnPilihNonaktif.className = 'btn w-100 py-3 rounded-4 fw-bold border-2 d-flex flex-column align-items-center justify-content-center gap-1 transition-all btn-outline-secondary';
            
            badgeStatusHeader.className = 'badge bg-success px-3 py-1 rounded-pill';
            badgeStatusHeader.innerHTML = '● AKTIF';

            iconStatusDesc.className = 'bi bi-check-circle-fill text-success fs-5';
            textStatusDesc.innerHTML = 'Pengumuman saat ini <strong>AKTIF</strong> dan sedang tampil kepada seluruh pengunjung beranda.';

            previewStatusNotice.className = 'alert alert-success border-success py-2 px-3 rounded-3 mb-3 d-flex align-items-center gap-2';
            previewStatusIcon.className   = 'bi bi-check-circle-fill text-success fs-5';
            previewStatusText.innerHTML   = '<strong>Banner AKTIF:</strong> Banner ini akan langsung tampil di beranda website pengunjung.';

            previewCard.style.opacity = '1';
            previewCard.style.filter = 'none';
            previewCard.style.border = '1px solid rgba(0,0,0,0.1)';
        } else {
            btnPilihAktif.className = 'btn w-100 py-3 rounded-4 fw-bold border-2 d-flex flex-column align-items-center justify-content-center gap-1 transition-all btn-outline-secondary';
            btnPilihNonaktif.className = 'btn w-100 py-3 rounded-4 fw-bold border-2 d-flex flex-column align-items-center justify-content-center gap-1 transition-all btn-danger shadow';
            
            badgeStatusHeader.className = 'badge bg-danger px-3 py-1 rounded-pill';
            badgeStatusHeader.innerHTML = '○ NONAKTIF';

            iconStatusDesc.className = 'bi bi-info-circle-fill text-danger fs-5';
            textStatusDesc.innerHTML = 'Pengumuman saat ini <strong>NONAKTIF</strong> (disembunyikan dari pengunjung beranda).';

            previewStatusNotice.className = 'alert alert-danger border-danger py-2 px-3 rounded-3 mb-3 d-flex align-items-center gap-2';
            previewStatusIcon.className   = 'bi bi-eye-slash-fill text-danger fs-5';
            previewStatusText.innerHTML   = '<strong>Banner NONAKTIF:</strong> Banner ini sedang disembunyikan dari beranda website.';

            previewCard.style.opacity = '0.45';
            previewCard.style.filter = 'grayscale(40%)';
            previewCard.style.border = '2px dashed #dc3545';
        }
    }

    btnPilihAktif.addEventListener('click', function(){
        setStatus(true);
    });

    btnPilihNonaktif.addEventListener('click', function(){
        setStatus(false);
    });

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
