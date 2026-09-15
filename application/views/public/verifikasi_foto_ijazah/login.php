<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($title ?? 'Verifikasi Foto Ijazah Siswa') ?> - MAN 3 Banjar</title>
    
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
            background: linear-gradient(135deg, #f0fdf4 0%, #e2e8f0 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px 14px;
        }
        .card-verif {
            background: #ffffff;
            border-radius: 28px;
            box-shadow: 0 20px 50px rgba(5, 150, 105, 0.12), 0 4px 12px rgba(0,0,0,0.04);
            border: 1px solid rgba(226, 232, 240, 0.9);
            overflow: hidden;
            width: 100%;
            max-width: 520px;
        }
        .header-verif {
            background: linear-gradient(135deg, #064e3b 0%, #059669 60%, #10b981 100%);
            color: #ffffff;
            padding: 32px 28px 24px;
            text-align: center;
            position: relative;
            overflow: hidden;
        }
        .header-verif::after {
            content: "";
            position: absolute;
            top: -30px;
            right: -30px;
            width: 130px;
            height: 130px;
            background: radial-gradient(circle, rgba(255,255,255,0.2) 0%, transparent 70%);
            border-radius: 50%;
        }
        .form-control, .form-select {
            padding: 12px 16px;
            border-radius: 14px;
            border: 1.5px solid #e2e8f0;
            font-size: 14px;
            transition: all 0.2s;
        }
        .form-control:focus, .form-select:focus {
            border-color: #10b981;
            box-shadow: 0 0 0 4px rgba(16, 185, 129, 0.15);
        }
        .btn-verif {
            background: linear-gradient(135deg, #059669, #10b981);
            color: #ffffff;
            border: none;
            padding: 13px;
            font-size: 15px;
            font-weight: 700;
            border-radius: 14px;
            box-shadow: 0 10px 24px rgba(16, 185, 129, 0.3);
            transition: all 0.2s;
        }
        .btn-verif:hover {
            transform: translateY(-2px);
            box-shadow: 0 14px 28px rgba(16, 185, 129, 0.4);
            color: #ffffff;
        }
        .badge-madrasah {
            width: 52px;
            height: 52px;
            border-radius: 18px;
            background: rgba(255,255,255,0.2);
            backdrop-filter: blur(8px);
            border: 1.5px solid rgba(255,255,255,0.35);
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
            margin-bottom: 12px;
            box-shadow: 0 6px 14px rgba(0,0,0,0.08);
        }
    </style>
</head>
<body>

<div class="card-verif">
    <!-- Header -->
    <div class="header-verif">
        <div class="badge-madrasah">
            <i class="bi bi-mortarboard-fill"></i>
        </div>
        <h4 class="fw-bold mb-1 text-white">Verifikasi Foto Ijazah</h4>
        <p class="mb-0 text-white-50 small">Madrasah Aliyah Negeri 3 Banjar &bull; Kelas XII</p>
    </div>

    <!-- Body Form -->
    <div class="p-4 p-md-5">

        <!-- Notifikasi Flash -->
        <?php if($this->session->flashdata('error')): ?>
            <div class="alert alert-danger alert-dismissible fade show rounded-3 border-0 small mb-4" role="alert">
                <i class="bi bi-exclamation-triangle-fill me-1"></i>
                <?= $this->session->flashdata('error') ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>
        <?php if($this->session->flashdata('info')): ?>
            <div class="alert alert-info alert-dismissible fade show rounded-3 border-0 small mb-4" role="alert">
                <i class="bi bi-info-circle-fill me-1"></i>
                <?= $this->session->flashdata('info') ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>

        <div class="alert alert-light border rounded-3 p-3 mb-4 text-dark" style="font-size: 13px; line-height: 1.5;">
            <i class="bi bi-shield-check text-success fs-5 me-1 align-middle"></i>
            Silakan pilih <strong>Kelas</strong> dan <strong>Nama Anda</strong>, lalu masukkan <strong>NISN</strong> atau <strong>Tanggal Lahir</strong> untuk verifikasi identitas sebelum memilih foto.
        </div>

        <?= form_open('verifikasi_foto_ijazah/auth_siswa', ['id' => 'formAuthSiswa']) ?>

            <!-- 1. PILIH KELAS XII -->
            <div class="mb-3">
                <label class="form-label fw-bold small text-dark"><i class="bi bi-door-open-fill text-success me-1"></i> Kelas XII:</label>
                <select name="kelas_id" id="selectKelas" class="form-select" required>
                    <option value="">-- Pilih Kelas Anda --</option>
                    <?php foreach($kelas_list as $k): ?>
                        <option value="<?= $k['id'] ?>"><?= htmlspecialchars($k['nama_kelas']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <!-- 2. PILIH NAMA SISWA -->
            <div class="mb-3">
                <label class="form-label fw-bold small text-dark"><i class="bi bi-person-fill text-success me-1"></i> Nama Lengkap Siswa:</label>
                <select name="siswa_id" id="selectSiswa" class="form-select" required disabled>
                    <option value="">-- Pilih Kelas Terlebih Dahulu --</option>
                </select>
                <div id="loadingSiswa" class="small text-muted mt-1 d-none">
                    <span class="spinner-border spinner-border-sm text-success" role="status"></span> Memuat daftar siswa...
                </div>
            </div>

            <!-- 3. NISN ATAU TANGGAL LAHIR -->
            <div class="mb-3">
                <label class="form-label fw-bold small text-dark"><i class="bi bi-credit-card-2-front-fill text-success me-1"></i> Masukkan NISN Anda:</label>
                <input type="text" name="nisn" id="inputNisn" class="form-control" placeholder="Contoh: 0061234567" autocomplete="off">
                <small class="text-muted" style="font-size: 11px;">10 digit Nomor Induk Siswa Nasional Anda.</small>
            </div>

            <div class="mb-4">
                <label class="form-label fw-bold small text-dark"><i class="bi bi-calendar-event text-success me-1"></i> Atau Tanggal Lahir (Jika belum tahu NISN):</label>
                <input type="date" name="tanggal_lahir" id="inputTglLahir" class="form-control">
            </div>

            <!-- BUTTON SUBMIT -->
            <button type="submit" class="btn btn-verif w-100 mb-3">
                <i class="bi bi-arrow-right-circle-fill me-1"></i> Lanjut Memilih Foto &rarr;
            </button>

            <div class="text-center text-muted small" style="font-size: 11.5px;">
                Mengalami kendala data tidak ditemukan? Silakan hubungi Wali Kelas atau Admin Tata Usaha.
            </div>

        <?= form_close() ?>

    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function(){
    const selectKelas = document.getElementById('selectKelas');
    const selectSiswa = document.getElementById('selectSiswa');
    const loadingSiswa = document.getElementById('loadingSiswa');

    selectKelas.addEventListener('change', function(){
        const kelasId = this.value;
        selectSiswa.innerHTML = '<option value="">-- Memuat siswa... --</option>';
        selectSiswa.disabled = true;

        if(!kelasId){
            selectSiswa.innerHTML = '<option value="">-- Pilih Kelas Terlebih Dahulu --</option>';
            return;
        }

        loadingSiswa.classList.remove('d-none');

        fetch('<?= base_url("verifikasi_foto_ijazah/get_siswa_by_kelas") ?>?kelas_id=' + kelasId)
            .then(res => res.json())
            .then(data => {
                loadingSiswa.classList.add('d-none');
                selectSiswa.innerHTML = '<option value="">-- Pilih Nama Anda --</option>';
                if(data.length > 0){
                    data.forEach(s => {
                        const opt = document.createElement('option');
                        opt.value = s.id;
                        opt.textContent = s.nama_lengkap + (s.nisn ? ' (NISN: ' + s.nisn + ')' : '');
                        selectSiswa.appendChild(opt);
                    });
                    selectSiswa.disabled = false;
                } else {
                    selectSiswa.innerHTML = '<option value="">-- Tidak ada siswa aktif di kelas ini --</option>';
                }
            })
            .catch(err => {
                loadingSiswa.classList.add('d-none');
                selectSiswa.innerHTML = '<option value="">Gagal memuat daftar siswa</option>';
            });
    });
});
</script>

</body>
</html>
