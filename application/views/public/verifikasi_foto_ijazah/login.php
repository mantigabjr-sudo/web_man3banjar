<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($title ?? 'Verifikasi Mandiri Foto Ijazah Siswa') ?> - MAN 3 Banjar</title>
    
    <!-- Bootstrap 5 CSS & Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    
    <!-- Google Fonts Inter -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">

    <!-- Select2 CSS & Bootstrap 5 Theme -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css">

    <style>
        :root {
            --primary: #059669;
            --primary-dark: #064e3b;
            --primary-light: #10b981;
            --surface-bg: #f8fafc;
        }

        body {
            font-family: 'Inter', sans-serif;
            background: 
                radial-gradient(circle at top left, rgba(16, 185, 129, 0.14), transparent 38%),
                radial-gradient(circle at bottom right, rgba(250, 204, 21, 0.12), transparent 32%),
                linear-gradient(135deg, #f0fdf4 0%, #f1f5f9 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 30px 16px;
            color: #1e293b;
        }

        /* Responsive Portal Container: Wide on PC, Adapts to screen on Mobile */
        .portal-wrapper {
            width: 100%;
            max-width: 1120px;
            margin: 0 auto;
        }

        .card-verif {
            background: #ffffff;
            border-radius: 28px;
            box-shadow: 0 25px 65px rgba(5, 150, 105, 0.13), 0 4px 20px rgba(0,0,0,0.04);
            border: 1px solid rgba(226, 232, 240, 0.95);
            overflow: hidden;
        }

        .header-verif {
            background: linear-gradient(135deg, #064e3b 0%, #059669 55%, #10b981 100%);
            color: #ffffff;
            padding: 34px 32px 28px;
            position: relative;
            overflow: hidden;
        }

        .header-verif::after {
            content: "";
            position: absolute;
            top: -40px;
            right: -40px;
            width: 220px;
            height: 220px;
            background: radial-gradient(circle, rgba(255,255,255,0.18) 0%, transparent 70%);
            border-radius: 50%;
            pointer-events: none;
        }

        .badge-madrasah {
            width: 58px;
            height: 58px;
            border-radius: 20px;
            background: rgba(255,255,255,0.2);
            backdrop-filter: blur(10px);
            border: 1.5px solid rgba(255,255,255,0.35);
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 28px;
            box-shadow: 0 8px 18px rgba(0,0,0,0.1);
        }

        .form-control, .form-select {
            padding: 12px 16px;
            border-radius: 14px;
            border: 1.5px solid #e2e8f0;
            font-size: 14px;
            color: #1e293b;
            transition: all 0.2s;
        }

        .form-control:focus, .form-select:focus {
            border-color: #10b981;
            box-shadow: 0 0 0 4px rgba(16, 185, 129, 0.15);
        }

        /* Customizing Select2 to match modern styling */
        .select2-container--bootstrap-5 .select2-selection {
            border-radius: 14px !important;
            border: 1.5px solid #e2e8f0 !important;
            min-height: 48px !important;
            padding: 6px 12px !important;
            font-size: 14px !important;
            display: flex !important;
            align-items: center !important;
        }

        .select2-container--bootstrap-5.select2-container--focus .select2-selection,
        .select2-container--bootstrap-5.select2-container--open .select2-selection {
            border-color: #10b981 !important;
            box-shadow: 0 0 0 4px rgba(16, 185, 129, 0.15) !important;
        }

        .select2-dropdown {
            border-radius: 16px !important;
            border: 1px solid #e2e8f0 !important;
            box-shadow: 0 15px 35px rgba(0,0,0,0.1) !important;
            overflow: hidden !important;
            font-size: 13.5px !important;
        }

        .select2-results__option--highlighted {
            background-color: #10b981 !important;
        }

        .btn-verif {
            background: linear-gradient(135deg, #059669, #10b981);
            color: #ffffff;
            border: none;
            padding: 14px;
            font-size: 15px;
            font-weight: 700;
            border-radius: 14px;
            box-shadow: 0 10px 24px rgba(16, 185, 129, 0.28);
            transition: all 0.2s;
        }

        .btn-verif:hover {
            transform: translateY(-2px);
            box-shadow: 0 14px 28px rgba(16, 185, 129, 0.38);
            color: #ffffff;
        }

        /* Monitoring Table Styling */
        .table-scroll-container {
            max-height: 380px;
            overflow-y: auto;
            border-radius: 16px;
            border: 1px solid #e2e8f0;
        }

        .table-scroll-container::-webkit-scrollbar {
            width: 6px;
        }
        .table-scroll-container::-webkit-scrollbar-track {
            background: #f8fafc;
        }
        .table-scroll-container::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 10px;
        }

        .table-monitor thead th {
            position: sticky;
            top: 0;
            background: #f8fafc;
            z-index: 10;
            font-size: 12px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: #64748b;
            font-weight: 700;
            border-bottom: 2px solid #e2e8f0;
            padding: 12px 14px;
        }

        .table-monitor tbody td {
            font-size: 13px;
            padding: 10px 14px;
            vertical-align: middle;
            border-bottom: 1px solid #f1f5f9;
        }

        .table-monitor tbody tr:hover {
            background-color: #f0fdf4;
        }

        .badge-verified {
            background-color: #dcfce7;
            color: #15803d;
            border: 1px solid #86efac;
            font-size: 11.5px;
            font-weight: 700;
            padding: 4px 10px;
            border-radius: 20px;
            display: inline-flex;
            align-items: center;
            gap: 4px;
        }

        .badge-unverified {
            background-color: #fef3c7;
            color: #b45309;
            border: 1px solid #fde68a;
            font-size: 11.5px;
            font-weight: 700;
            padding: 4px 10px;
            border-radius: 20px;
            display: inline-flex;
            align-items: center;
            gap: 4px;
        }

        .stat-box {
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 16px;
            padding: 12px 16px;
            text-align: center;
        }

        .stat-box .stat-val {
            font-size: 22px;
            font-weight: 800;
            line-height: 1.1;
        }

        .stat-box .stat-lbl {
            font-size: 11px;
            font-weight: 600;
            color: #64748b;
            text-transform: uppercase;
        }

        @media (max-width: 991px) {
            body {
                padding: 12px 8px;
            }
            .header-verif {
                padding: 24px 20px 20px;
                text-align: center;
            }
            .header-verif .d-flex {
                flex-direction: column;
                align-items: center !important;
                text-align: center !important;
            }
            .badge-madrasah {
                margin-bottom: 12px;
            }
        }
    </style>
</head>
<body>

<div class="portal-wrapper">
    <div class="card-verif">

        <!-- ═══ HEADER PORTAL ═══ -->
        <div class="header-verif">
            <div class="d-flex align-items-center gap-3">
                <div class="badge-madrasah flex-shrink-0">
                    <i class="bi bi-mortarboard-fill"></i>
                </div>
                <div>
                    <span class="badge bg-white text-success fw-bold px-3 py-1 rounded-pill mb-2" style="font-size: 11px; letter-spacing: 0.5px;">
                        PORTAL RESMI KELAS XII &bull; T.A. 2026/2027
                    </span>
                    <h3 class="fw-bold mb-1 text-white">Verifikasi Mandiri Foto Ijazah Siswa</h3>
                    <p class="mb-0 text-white-50" style="font-size: 13.5px;">
                        Madrasah Aliyah Negeri 3 Banjar &bull; Pemilihan &amp; Validasi Foto Ijazah Resmi
                    </p>
                </div>
            </div>
        </div>

        <!-- ═══ BODY PORTAL (2 KOLOM DI PC, 1 KOLOM DI MOBILE) ═══ -->
        <div class="p-3 p-md-4 p-lg-4">

            <!-- Flash Alert -->
            <?php if($this->session->flashdata('error')): ?>
                <div class="alert alert-danger alert-dismissible fade show rounded-4 border-0 small mb-4" role="alert">
                    <i class="bi bi-exclamation-triangle-fill me-1 fs-6 align-middle"></i>
                    <?= $this->session->flashdata('error') ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php endif; ?>
            <?php if($this->session->flashdata('info')): ?>
                <div class="alert alert-info alert-dismissible fade show rounded-4 border-0 small mb-4" role="alert">
                    <i class="bi bi-info-circle-fill me-1 fs-6 align-middle"></i>
                    <?= $this->session->flashdata('info') ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php endif; ?>

            <div class="row g-4">

                <!-- ═════ KOLOM KIRI: FORM IDENTIFIKASI SISWA ═════ -->
                <div class="col-lg-5">
                    <div class="p-3 p-md-4 rounded-4" style="background: #fafcfb; border: 1.5px solid #e6f4ea;">

                        <div class="d-flex align-items-center gap-2 mb-3">
                            <span class="badge bg-success rounded-circle p-2" style="line-height: 0;">
                                <i class="bi bi-person-bounding-box text-white fs-6"></i>
                            </span>
                            <div>
                                <h6 class="fw-bold mb-0 text-dark">Identifikasi Diri Siswa</h6>
                                <small class="text-muted" style="font-size: 12px;">Pilih data diri untuk mengakses galeri foto Anda</small>
                            </div>
                        </div>

                        <?= form_open('verifikasi_foto_ijazah/auth_siswa', ['id' => 'formAuthSiswa']) ?>

                            <!-- 1. PILIH KELAS XII -->
                            <div class="mb-3">
                                <label class="form-label fw-bold small text-dark mb-1">
                                    <i class="bi bi-door-open-fill text-success me-1"></i> Kelas XII:
                                </label>
                                <select name="kelas_id" id="selectKelas" class="form-select" required>
                                    <option value="">-- Pilih Kelas Anda --</option>
                                    <?php foreach($kelas_list as $k): ?>
                                        <option value="<?= $k['id'] ?>"><?= htmlspecialchars($k['nama_kelas']) ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <!-- 2. PILIH NAMA SISWA (SEARCHABLE VIA SELECT2) -->
                            <div class="mb-3" id="selectSiswaWrapper">
                                <label class="form-label fw-bold small text-dark mb-1 d-flex justify-content-between align-items-center">
                                    <span><i class="bi bi-search text-success me-1"></i> Nama Lengkap Siswa:</span>
                                    <small class="text-muted fw-normal" style="font-size: 11px;">Bisa ketik nama untuk cari</small>
                                </label>
                                <select name="siswa_id" id="selectSiswa" class="form-select" required disabled>
                                    <option value="">-- Pilih Kelas Terlebih Dahulu --</option>
                                </select>
                                <div id="loadingSiswa" class="small text-muted mt-1 d-none">
                                    <span class="spinner-border spinner-border-sm text-success" role="status"></span> Memuat daftar siswa...
                                </div>
                                <!-- Notifikasi Khusus jika siswa sudah verifikasi -->
                                <div id="alertAlreadyVerified" class="alert alert-success border-0 rounded-3 p-2 mt-2 mb-0 small d-none" style="font-size: 12px;">
                                    <i class="bi bi-check-circle-fill text-success me-1"></i>
                                    <strong>Status:</strong> Anda telah memverifikasi foto sebelumnya. Silakan masukkan NISN/Tanggal Lahir untuk melihat atau mengunduh <strong>Bukti Verifikasi</strong>.
                                </div>
                            </div>

                            <!-- 3. NISN ATAU TANGGAL LAHIR -->
                            <div class="mb-3">
                                <label class="form-label fw-bold small text-dark mb-1">
                                    <i class="bi bi-credit-card-2-front-fill text-success me-1"></i> Masukkan NISN Anda:
                                </label>
                                <input type="text" name="nisn" id="inputNisn" class="form-control" placeholder="Contoh: 0061234567" autocomplete="off">
                                <small class="text-muted" style="font-size: 11px;">10 digit Nomor Induk Siswa Nasional Anda.</small>
                            </div>

                            <div class="mb-4">
                                <label class="form-label fw-bold small text-dark mb-1">
                                    <i class="bi bi-calendar-event text-success me-1"></i> Atau Tanggal Lahir (Jika belum tahu NISN):
                                </label>
                                <input type="date" name="tanggal_lahir" id="inputTglLahir" class="form-control">
                            </div>

                            <!-- BUTTON SUBMIT -->
                            <button type="submit" class="btn btn-verif w-100 mb-2">
                                <i class="bi bi-arrow-right-circle-fill me-1"></i> Lanjut Memilih Foto &rarr;
                            </button>

                            <div class="text-center text-muted" style="font-size: 11.5px; line-height: 1.4;">
                                Mengalami kendala nama tidak ditemukan? Silakan hubungi Wali Kelas atau Admin Madrasah.
                            </div>

                        <?= form_close() ?>

                    </div>
                </div>

                <!-- ═════ KOLOM KANAN: TABEL STATUS & MONITORING REAL-TIME ═════ -->
                <div class="col-lg-7">
                    <div class="p-3 p-md-4 rounded-4 h-100" style="background: #ffffff; border: 1.5px solid #e2e8f0;">

                        <!-- Header Kolom Monitoring -->
                        <div class="d-flex flex-wrap align-items-center justify-content-between gap-2 mb-3">
                            <div class="d-flex align-items-center gap-2">
                                <span class="badge bg-primary bg-opacity-10 text-primary rounded-circle p-2" style="line-height: 0;">
                                    <i class="bi bi-card-checklist fs-6"></i>
                                </span>
                                <div>
                                    <h6 class="fw-bold mb-0 text-dark">Status Verifikasi Kelas</h6>
                                    <small class="text-muted" id="kelasStatusTitle" style="font-size: 12px;">Pilih kelas untuk melihat daftar siswa</small>
                                </div>
                            </div>
                            <div id="quickSearchWrapper" class="d-none">
                                <div class="input-group input-group-sm" style="width: 200px;">
                                    <span class="input-group-text bg-light border-end-0"><i class="bi bi-search text-muted"></i></span>
                                    <input type="text" id="tableFilterInput" class="form-control border-start-0 ps-0" placeholder="Cari di tabel...">
                                </div>
                            </div>
                        </div>

                        <!-- Progress Bar & Statistik -->
                        <div id="statPanel" class="d-none mb-3">
                            <div class="row g-2 mb-2">
                                <div class="col-4">
                                    <div class="stat-box">
                                        <div class="stat-val text-dark" id="statTotal">0</div>
                                        <div class="stat-lbl">Total Siswa</div>
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div class="stat-box" style="background: #f0fdf4; border-color: #bbf7d0;">
                                        <div class="stat-val text-success" id="statVerified">0</div>
                                        <div class="stat-lbl text-success">Terverifikasi</div>
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div class="stat-box" style="background: #fffbeb; border-color: #fde68a;">
                                        <div class="stat-val text-warning" id="statUnverified">0</div>
                                        <div class="stat-lbl text-warning">Belum Verif</div>
                                    </div>
                                </div>
                            </div>

                            <!-- Progress Bar -->
                            <div class="d-flex align-items-center justify-content-between small text-muted mb-1" style="font-size: 11.5px;">
                                <span>Kelengkapan Verifikasi Foto</span>
                                <strong id="progressPercentText" class="text-success">0%</strong>
                            </div>
                            <div class="progress" style="height: 8px; border-radius: 6px; background: #e2e8f0;">
                                <div id="progressBar" class="progress-bar bg-success" role="progressbar" style="width: 0%;" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                        </div>

                        <!-- Placeholder jika belum memilih kelas -->
                        <div id="emptyClassPlaceholder" class="text-center py-5 text-muted">
                            <div class="mb-3">
                                <i class="bi bi-people text-muted opacity-50" style="font-size: 48px;"></i>
                            </div>
                            <h6 class="fw-bold text-dark mb-1">Belum Ada Kelas yang Dipilih</h6>
                            <p class="small text-muted mb-0" style="max-width: 320px; margin: 0 auto;">
                                Silakan pilih <strong>Kelas XII</strong> Anda di formulir sebelah kiri untuk melihat daftar siswa dan memantau status verifikasi.
                            </p>
                        </div>

                        <!-- Tabel Siswa Kelas -->
                        <div id="tableWrapper" class="d-none">
                            <div class="table-scroll-container">
                                <table class="table table-hover table-monitor mb-0">
                                    <thead>
                                        <tr>
                                            <th style="width: 40px;" class="text-center">#</th>
                                            <th>Nama Siswa</th>
                                            <th>NISN</th>
                                            <th>Status</th>
                                            <th style="width: 70px;" class="text-center">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody id="tableBodySiswa">
                                        <!-- Diisi via JavaScript -->
                                    </tbody>
                                </table>
                            </div>
                            <div class="d-flex align-items-center justify-content-between mt-2 px-1 text-muted" style="font-size: 11.5px;">
                                <span><i class="bi bi-info-circle me-1"></i> Klik <strong>Pilih</strong> pada baris nama Anda untuk mengisi formulir secara cepat.</span>
                            </div>
                        </div>

                    </div>
                </div>

            </div>

        </div>

        <!-- ═══ FOOTER ═══ -->
        <div class="bg-light px-4 py-3 border-top text-center text-muted" style="font-size: 12px;">
            &copy; <?= date('Y') ?> <strong>MAN 3 Banjar</strong> &bull; Sistem Verifikasi Mandiri Foto Ijazah Siswa Kelas XII
        </div>

    </div>
</div>

<!-- jQuery & Bootstrap 5 Bundle -->
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<!-- Select2 JS -->
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<script>
$(document).ready(function(){

    let allSiswaData = [];

    // Inisialisasi Select2 pada selectSiswa
    $('#selectSiswa').select2({
        theme: 'bootstrap-5',
        placeholder: '-- Pilih Kelas Terlebih Dahulu --',
        width: '100%',
        dropdownParent: $('#selectSiswaWrapper')
    });

    // Event Handler: Saat Kelas XII dipilih
    $('#selectKelas').on('change', function(){
        const kelasId = $(this).val();
        const kelasText = $("#selectKelas option:selected").text();

        // Reset Select2 Siswa
        $('#selectSiswa').empty().append('<option value="">-- Memuat siswa... --</option>').prop('disabled', true).trigger('change');
        $('#alertAlreadyVerified').addClass('d-none');

        if(!kelasId){
            $('#selectSiswa').empty().append('<option value="">-- Pilih Kelas Terlebih Dahulu --</option>').trigger('change');
            $('#emptyClassPlaceholder').removeClass('d-none');
            $('#statPanel').addClass('d-none');
            $('#tableWrapper').addClass('d-none');
            $('#quickSearchWrapper').addClass('d-none');
            $('#kelasStatusTitle').text('Pilih kelas untuk melihat daftar siswa');
            allSiswaData = [];
            return;
        }

        $('#loadingSiswa').removeClass('d-none');
        $('#kelasStatusTitle').text('Memuat data kelas ' + kelasText + '...');

        // Fetch Data Siswa + Status Verifikasi
        $.ajax({
            url: '<?= base_url("verifikasi_foto_ijazah/get_siswa_by_kelas") ?>',
            type: 'GET',
            data: { kelas_id: kelasId },
            dataType: 'json',
            success: function(data){
                $('#loadingSiswa').addClass('d-none');
                allSiswaData = data;

                // 1. Populasi Select2 Nama Siswa
                $('#selectSiswa').empty().append('<option value="">-- Ketik atau Pilih Nama Anda --</option>');
                
                if(data.length > 0){
                    data.forEach(function(s){
                        const isVerif = (s.is_verified == 1);
                        const icon = isVerif ? '✅ ' : '⏳ ';
                        const tag = isVerif ? ' (Sudah Verifikasi)' : (s.nisn ? ' (NISN: ' + s.nisn + ')' : '');
                        const optText = icon + s.nama_lengkap + tag;
                        
                        const newOption = new Option(optText, s.id, false, false);
                        $(newOption).attr('data-verified', isVerif ? '1' : '0');
                        $(newOption).attr('data-nisn', s.nisn || '');
                        $(newOption).attr('data-nama', s.nama_lengkap);
                        $('#selectSiswa').append(newOption);
                    });

                    $('#selectSiswa').prop('disabled', false).trigger('change');
                } else {
                    $('#selectSiswa').empty().append('<option value="">-- Tidak ada siswa aktif di kelas ini --</option>').trigger('change');
                }

                // 2. Tampilkan Statistik & Tabel Kelas
                renderMonitoringTable(data, kelasText);
            },
            error: function(){
                $('#loadingSiswa').addClass('d-none');
                $('#selectSiswa').empty().append('<option value="">Gagal memuat siswa</option>').trigger('change');
                alert('Gagal memuat data siswa kelas. Silakan periksa koneksi internet Anda.');
            }
        });
    });

    // Event Handler: Saat Siswa dipilih pada dropdown
    $('#selectSiswa').on('change', function(){
        const selectedOpt = $(this).find('option:selected');
        const isVerif = selectedOpt.attr('data-verified');
        const nisn = selectedOpt.attr('data-nisn');

        if(isVerif === '1'){
            $('#alertAlreadyVerified').removeClass('d-none');
        } else {
            $('#alertAlreadyVerified').addClass('d-none');
        }

        // Jika NISN tersedia, bantu isikan placeholder atau autofill jika kosong
        if(nisn && !$('#inputNisn').val()){
            $('#inputNisn').attr('placeholder', 'NISN: ' + nisn);
        }
    });

    // Render Tabel Monitoring & Statistik Kelas
    function renderMonitoringTable(data, kelasText){
        $('#emptyClassPlaceholder').addClass('d-none');
        $('#statPanel').removeClass('d-none');
        $('#tableWrapper').removeClass('d-none');
        $('#quickSearchWrapper').removeClass('d-none');
        $('#kelasStatusTitle').html('Kelas <strong>' + kelasText + '</strong> (' + data.length + ' Siswa)');

        const total = data.length;
        const verified = data.filter(s => s.is_verified == 1).length;
        const unverified = total - verified;
        const percent = total > 0 ? Math.round((verified / total) * 100) : 0;

        $('#statTotal').text(total);
        $('#statVerified').text(verified);
        $('#statUnverified').text(unverified);
        $('#progressPercentText').text(percent + '% (' + verified + '/' + total + ')');
        $('#progressBar').css('width', percent + '%').attr('aria-valuenow', percent);

        populateTableRows(data);
    }

    // Populate baris-baris tabel siswa
    function populateTableRows(data){
        const tbody = $('#tableBodySiswa');
        tbody.empty();

        if(data.length === 0){
            tbody.append('<tr><td colspan="5" class="text-center py-4 text-muted small">Tidak ada data siswa.</td></tr>');
            return;
        }

        data.forEach(function(s, index){
            const isVerif = (s.is_verified == 1);
            let statusBadge = '';

            if(isVerif){
                const tgl = s.verified_at_formatted ? '<br><span style="font-size: 10px; color: #166534;"><i class="bi bi-clock me-1"></i>' + s.verified_at_formatted + '</span>' : '';
                statusBadge = '<span class="badge-verified"><i class="bi bi-check-circle-fill"></i> Sudah Verif</span>' + tgl;
            } else {
                statusBadge = '<span class="badge-unverified"><i class="bi bi-hourglass-split"></i> Belum</span>';
            }

            const tr = $('<tr></tr>');
            tr.attr('data-nama', (s.nama_lengkap || '').toLowerCase());
            tr.attr('data-nisn', (s.nisn || '').toLowerCase());

            tr.append('<td class="text-center text-muted fw-bold" style="font-size: 11.5px;">' + (index + 1) + '</td>');
            tr.append('<td><strong class="text-dark d-block">' + escapeHtml(s.nama_lengkap) + '</strong></td>');
            tr.append('<td class="text-muted" style="font-size: 12px;">' + (s.nisn ? s.nisn : '<span class="text-muted fst-italic">-</span>') + '</td>');
            tr.append('<td>' + statusBadge + '</td>');
            tr.append('<td class="text-center"><button type="button" class="btn btn-sm btn-outline-success rounded-pill px-2 py-0 btn-pilih-siswa" data-id="' + s.id + '" style="font-size: 11px;">Pilih</button></td>');

            tbody.append(tr);
        });
    }

    // Event Handler: Tombol "Pilih" di tabel baris
    $(document).on('click', '.btn-pilih-siswa', function(){
        const siswaId = $(this).attr('data-id');
        $('#selectSiswa').val(siswaId).trigger('change');
        
        // Scroll halus ke form jika di layar kecil / mobile
        if(window.innerWidth < 992){
            $('html, body').animate({
                scrollTop: $("#formAuthSiswa").offset().top - 20
            }, 350);
        }

        // Fokuskan ke input NISN
        $('#inputNisn').focus();
    });

    // Event Handler: Quick Search Tabel
    $('#tableFilterInput').on('keyup', function(){
        const query = $(this).val().toLowerCase().trim();
        $('#tableBodySiswa tr').each(function(){
            const nama = $(this).attr('data-nama') || '';
            const nisn = $(this).attr('data-nisn') || '';

            if(!query || nama.includes(query) || nisn.includes(query)){
                $(this).show();
            } else {
                $(this).hide();
            }
        });
    });

    // Helper escape HTML
    function escapeHtml(text) {
        if(!text) return '';
        return $('<div>').text(text).html();
    }

});
</script>

</body>
</html>
