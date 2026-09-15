<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($title ?? 'Pilih Foto Ijazah') ?> - MAN 3 Banjar</title>
    
    <!-- Bootstrap 5 CSS & Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    
    <!-- Google Fonts Inter -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <style>
        body {
            font-family: 'Inter', sans-serif;
            background: #f8fafc;
            min-height: 100vh;
            color: #1e293b;
        }
        .navbar-verif {
            background: #ffffff;
            border-bottom: 1px solid #e2e8f0;
            padding: 12px 20px;
            box-shadow: 0 4px 16px rgba(0,0,0,0.03);
        }
        .brand-badge {
            width: 38px;
            height: 38px;
            border-radius: 12px;
            background: linear-gradient(135deg, #064e3b, #10b981);
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 18px;
        }
        .user-card {
            background: #ffffff;
            border-radius: 20px;
            border: 1px solid #e2e8f0;
            padding: 18px 24px;
            box-shadow: 0 6px 18px rgba(0,0,0,0.03);
            margin-bottom: 24px;
        }
        .photo-card {
            background: #ffffff;
            border-radius: 18px;
            border: 1.5px solid #e2e8f0;
            overflow: hidden;
            transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
            box-shadow: 0 4px 12px rgba(0,0,0,0.04);
            cursor: pointer;
        }
        .photo-card:hover {
            transform: translateY(-5px);
            border-color: #10b981;
            box-shadow: 0 14px 28px rgba(16, 185, 129, 0.18);
        }
        .photo-img-wrap {
            height: 220px;
            overflow: hidden;
            background: #0f172a;
            position: relative;
        }
        .photo-img-wrap img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.3s ease;
        }
        .photo-card:hover .photo-img-wrap img {
            transform: scale(1.05);
        }
        .photo-card .btn-claim {
            background: linear-gradient(135deg, #059669, #10b981);
            color: #ffffff;
            border: none;
            font-weight: 700;
            border-radius: 12px;
            font-size: 13px;
            padding: 8px 12px;
            width: 100%;
            transition: all 0.2s;
        }
        .photo-card .btn-claim:hover {
            background: linear-gradient(135deg, #047857, #059669);
            box-shadow: 0 6px 16px rgba(16, 185, 129, 0.3);
            color: #ffffff;
        }
        .zoom-overlay {
            position: absolute;
            top: 10px;
            right: 10px;
            background: rgba(0,0,0,0.65);
            backdrop-filter: blur(4px);
            color: white;
            border-radius: 50%;
            width: 32px;
            height: 32px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 13px;
            opacity: 0.8;
            transition: opacity 0.2s;
        }
        .photo-card:hover .zoom-overlay {
            opacity: 1;
            background: #10b981;
        }
    </style>
</head>
<body>

<!-- ═══ NAVBAR ═══ -->
<nav class="navbar-verif sticky-top mb-4">
    <div class="container d-flex justify-content-between align-items-center">
        <div class="d-flex align-items-center gap-3">
            <div class="brand-badge">
                <i class="bi bi-mortarboard-fill"></i>
            </div>
            <div>
                <strong class="d-block text-dark" style="font-size: 15px; line-height: 1.2;">Verifikasi Foto Ijazah</strong>
                <small class="text-muted" style="font-size: 12px;">MAN 3 Banjar &bull; Kelas XII</small>
            </div>
        </div>
        <div>
            <a href="<?= base_url('verifikasi_foto_ijazah/logout') ?>" class="btn btn-outline-danger btn-sm rounded-pill px-3 fw-bold" onclick="return confirm('Keluar dari sesi verifikasi Anda?')">
                <i class="bi bi-box-arrow-right me-1"></i> Ganti Siswa
            </a>
        </div>
    </div>
</nav>

<div class="container pb-5">

    <!-- Flash Messages -->
    <?php if($this->session->flashdata('error')): ?>
        <div class="alert alert-danger alert-dismissible fade show rounded-4 border-0 shadow-sm mb-4" role="alert">
            <i class="bi bi-exclamation-triangle-fill me-2 fs-5"></i>
            <?= $this->session->flashdata('error') ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <!-- ═══ KARTU IDENTITAS SISWA AKTIF ═══ -->
    <div class="user-card">
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-3">
            <div class="d-flex align-items-center gap-3">
                <div class="p-3 bg-success-subtle text-success rounded-4 fs-3">
                    <i class="bi bi-person-badge"></i>
                </div>
                <div>
                    <span class="badge bg-success-subtle text-success fw-bold px-3 py-1 rounded-pill mb-1">
                        SISWA KELAS <?= htmlspecialchars($siswa['nama_kelas']) ?>
                    </span>
                    <h4 class="fw-bold text-dark mb-0"><?= htmlspecialchars($siswa['nama_lengkap']) ?></h4>
                    <span class="text-muted small">
                        NISN: <strong><?= !empty($siswa['nisn']) ? htmlspecialchars($siswa['nisn']) : '-' ?></strong> &bull; 
                        NIS: <strong><?= !empty($siswa['nis']) ? htmlspecialchars($siswa['nis']) : '-' ?></strong>
                    </span>
                </div>
            </div>
            <div class="text-md-end">
                <span class="badge bg-warning-subtle text-warning fw-bold px-3 py-2 rounded-pill fs-6">
                    <i class="bi bi-clock-history me-1"></i> Menunggu Verifikasi
                </span>
            </div>
        </div>
    </div>

    <!-- ═══ PANDUAN LANGKAH ═══ -->
    <div class="alert alert-success border-0 rounded-4 shadow-sm p-4 mb-4 text-dark" style="background: #ecfdf5;">
        <div class="d-flex gap-3 align-items-start">
            <i class="bi bi-info-circle-fill text-success fs-3 flex-shrink-0 mt-1"></i>
            <div>
                <h6 class="fw-bold text-success mb-1">Panduan Memilih Foto Ijazah Anda:</h6>
                <ol class="mb-0 ps-3 small text-muted" style="line-height: 1.6;">
                    <li>Cari dan temukan foto diri Anda pada galeri foto di bawah ini.</li>
                    <li>Klik pada kartu foto untuk <strong>melihat pratinjau zoom ukuran penuh</strong> agar dapat memeriksa ketajaman dan kerapian seragam.</li>
                    <li>Jika sudah 100% yakin itu adalah foto Anda, klik tombol <strong>"Ini Foto Saya &rarr;"</strong>.</li>
                    <li>Sistem akan otomatis mengunci dan menyimpan foto tersebut dengan format resmi <strong><?= htmlspecialchars($siswa['nisn'] ?? 'NISN') ?>.jpg</strong>.</li>
                </ol>
            </div>
        </div>
    </div>

    <!-- ═══ SEARCH BOX & INFO FOTO ═══ -->
    <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
        <div>
            <h5 class="fw-bold text-dark mb-0">Galeri Foto Mentah</h5>
            <small class="text-muted">Tersedia <?= count($foto_list) ?> foto yang belum diverifikasi</small>
        </div>
        <div style="max-width: 260px; width: 100%;">
            <div class="input-group input-group-sm">
                <span class="input-group-text bg-white border-end-0 rounded-start-pill"><i class="bi bi-search text-muted"></i></span>
                <input type="text" id="searchPhoto" class="form-control border-start-0 rounded-end-pill" placeholder="Cari nama file foto...">
            </div>
        </div>
    </div>

    <!-- ═══ GRID GALERI FOTO ═══ -->
    <?php if(empty($foto_list)): ?>
        <div class="card border-0 rounded-4 shadow-sm text-center py-5">
            <div class="card-body">
                <i class="bi bi-images fs-1 text-muted d-block mb-3"></i>
                <h5 class="fw-bold text-dark">Belum Ada Foto Mentah Tersedia</h5>
                <p class="text-muted small mb-0">Foto mentah ijazah belum diunggah oleh panitia madrasah. Silakan coba beberapa saat lagi atau hubungi admin.</p>
            </div>
        </div>
    <?php else: ?>
        <div class="row g-3" id="photoGrid">
            <?php foreach($foto_list as $f): ?>
                <div class="col-xl-2 col-lg-3 col-md-4 col-6 photo-item" data-filename="<?= strtolower($f['file_mentah']) ?>">
                    <div class="photo-card" onclick="openClaimModal('<?= $f['id'] ?>', '<?= base_url('uploads/foto_ijazah/mentah/' . $f['file_mentah']) ?>', '<?= htmlspecialchars(addslashes($f['file_mentah'])) ?>')">
                        <div class="photo-img-wrap">
                            <img src="<?= base_url('uploads/foto_ijazah/mentah/' . $f['file_mentah']) ?>" alt="Foto Ijazah" loading="lazy">
                            <span class="zoom-overlay" title="Perbesar"><i class="bi bi-zoom-in"></i></span>
                        </div>
                        <div class="p-2 text-center">
                            <button type="button" class="btn btn-claim">
                                <i class="bi bi-check-circle-fill me-1"></i> Ini Foto Saya
                            </button>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

</div>

<!-- ═══ MODAL KONFIRMASI & ZOOM PRATINJAU FOTO ═══ -->
<div class="modal fade" id="modalClaimPhoto" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content rounded-4 border-0 shadow overflow-hidden">
            <div class="modal-header py-3 px-4 bg-light border-bottom">
                <div>
                    <h6 class="modal-title fw-bold text-dark mb-0"><i class="bi bi-person-check-fill text-success me-2"></i> Konfirmasi Foto Ijazah</h6>
                    <small class="text-muted" style="font-size: 11.5px;">Pastikan wajah, kerapian jas, dan jilbab/dasi sudah sesuai.</small>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <?= form_open('verifikasi_foto_ijazah/klaim') ?>
            <input type="hidden" name="foto_id" id="modalFotoId" value="">

            <div class="modal-body p-4 text-center">
                <!-- Foto Zoom Besar -->
                <div class="mb-3 p-2 bg-dark rounded-4 shadow-inner" style="max-height: 420px; overflow: hidden; display: inline-block;">
                    <img id="modalPreviewImg" src="" alt="Pratinjau Foto" class="img-fluid rounded-3" style="max-height: 380px; object-fit: contain;">
                </div>

                <!-- Konfirmasi Data Siswa -->
                <div class="alert alert-light border rounded-3 text-start p-3 mb-3">
                    <div class="row g-1 small">
                        <div class="col-4 text-muted">Nama Siswa:</div>
                        <div class="col-8 fw-bold text-dark"><?= htmlspecialchars($siswa['nama_lengkap']) ?></div>
                        <div class="col-4 text-muted">Kelas:</div>
                        <div class="col-8 fw-bold text-dark"><?= htmlspecialchars($siswa['nama_kelas']) ?></div>
                        <div class="col-4 text-muted">NISN Resmi:</div>
                        <div class="col-8 fw-bold text-success font-monospace"><?= htmlspecialchars($siswa['nisn'] ?? '-') ?></div>
                    </div>
                </div>

                <!-- Checkbox Konfirmasi -->
                <div class="form-check text-start p-2 bg-success bg-opacity-10 rounded-3 border border-success border-opacity-25 mb-2">
                    <input class="form-check-input ms-1 me-2" type="checkbox" id="checkPernyataan" required>
                    <label class="form-check-label small fw-semibold text-dark" for="checkPernyataan" style="font-size: 12.5px;">
                        Saya menyatakan dan mengonfirmasi dengan sadar bahwa foto di atas adalah benar <strong>foto ijazah resmi saya</strong>.
                    </label>
                </div>
            </div>

            <div class="modal-footer border-top bg-light py-2 px-4 justify-content-between">
                <button type="button" class="btn btn-light rounded-pill px-3" data-bs-dismiss="modal">Batal &amp; Pilih Lain</button>
                <button type="submit" id="btnSubmitClaim" class="btn btn-success rounded-pill px-4 fw-bold" disabled>
                    <i class="bi bi-check-circle-fill me-1"></i> Ya, Simpan Foto Ini
                </button>
            </div>
            <?= form_close() ?>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
let claimModalInstance = null;

function openClaimModal(fotoId, imgUrl, fileName){
    document.getElementById('modalFotoId').value = fotoId;
    document.getElementById('modalPreviewImg').src = imgUrl;
    
    // Reset checkbox
    const check = document.getElementById('checkPernyataan');
    const btnSubmit = document.getElementById('btnSubmitClaim');
    check.checked = false;
    btnSubmit.disabled = true;

    check.onchange = function(){
        btnSubmit.disabled = !this.checked;
    };

    if(!claimModalInstance){
        claimModalInstance = new bootstrap.Modal(document.getElementById('modalClaimPhoto'));
    }
    claimModalInstance.show();
}

// Fitur pencarian filter nama file
document.getElementById('searchPhoto').addEventListener('input', function(){
    const q = this.value.toLowerCase().trim();
    const items = document.querySelectorAll('.photo-item');
    items.forEach(item => {
        const name = item.getAttribute('data-filename') || '';
        if(name.includes(q)){
            item.style.display = '';
        } else {
            item.style.display = 'none';
        }
    });
});
</script>

</body>
</html>
