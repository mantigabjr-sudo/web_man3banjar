<?php $this->load->view('templates/header'); ?>
<?php $this->load->view('templates/sidebar'); ?>

<?php
if(!function_exists('mon_e')){
    function mon_e($text){
        return htmlspecialchars((string)$text, ENT_QUOTES, 'UTF-8');
    }
}
$status_filter = isset($status_filter) ? $status_filter : 'Menunggu Verifikasi Berkas';
?>

<div class="content">

<style>
.monitoring-page { max-width: 1400px; margin: 0 auto; }
.monitoring-hero {
    background: radial-gradient(circle at top right, rgba(250,204,21,.18), transparent 30%),
                linear-gradient(135deg, #0284c7, #0369a1);
    color: white; border-radius: 28px; padding: 24px; margin-bottom: 20px;
    box-shadow: 0 20px 50px rgba(2,132,199,.15); display: flex; justify-content: space-between; gap: 18px; align-items: center; flex-wrap: wrap;
}
.monitoring-hero h2 { margin: 0; font-weight: 950; letter-spacing: -0.5px; }
.monitoring-hero p { margin: 7px 0 0; color: rgba(255, 255, 255, 0.82); font-weight: 600; }
.btn-monitoring-back {
    min-height: 42px; border-radius: 14px; padding: 0 18px; display: inline-flex; align-items: center; justify-content: center;
    font-weight: 900; text-decoration: none; background: rgba(255, 255, 255, 0.15); color: white; border: 1px solid rgba(255, 255, 255, 0.25); transition: all 0.2s ease;
}
.btn-monitoring-back:hover { background: white; color: #0369a1; }
.monitoring-card { background: white; border: 1px solid #e2e8f0; border-radius: 24px; box-shadow: 0 16px 42px rgba(15,23,42,.06); overflow: hidden; margin-bottom: 20px; }
.monitoring-card-head { padding: 20px 24px; border-bottom: 1px solid #e2e8f0; background: radial-gradient(circle at top right, rgba(2,132,199,.06), transparent 25%), #ffffff; }
.monitoring-card-head h5 { color: #0c4a6e; font-weight: 950; margin: 0; }
.monitoring-card-head p { color: #64748b; font-weight: 650; margin: 5px 0 0; font-size: 13.5px; }
.monitoring-card-body { padding: 24px; }
.btn-action-mon {
    min-height: 34px; border-radius: 12px; padding: 0 14px; font-size: 12px; font-weight: 900; display: inline-flex; align-items: center; justify-content: center;
    text-decoration: none; margin: 2px; border: 0; transition: all 0.2s ease;
}
.btn-mon-detail { background: #e0f2fe; color: #0369a1; }
.btn-mon-detail:hover { background: #bae6fd; color: #0369a1; }
.btn-mon-terima { background: #dcfce7; color: #166534; }
.btn-mon-terima:hover { background: #bbf7d0; color: #166534; }
.btn-mon-tolak { background: #fee2e2; color: #991b1b; }
.btn-mon-tolak:hover { background: #fecaca; color: #991b1b; }
.btn-mon-revisi { background: #fef3c7; color: #92400e; }
.btn-mon-revisi:hover { background: #fde68a; color: #92400e; }
.siswa-info-box strong { display: block; color: #0f172a; font-weight: 950; }
.siswa-info-box small { display: block; color: #64748b; font-weight: 700; margin-top: 2px; }
</style>

<div class="monitoring-page">

    <div class="monitoring-hero">
        <div>
            <h2>Monitoring Berkas PPDB</h2>
            <p>
                Pantau dokumen fisik calon peserta didik baru dan verifikasi kelengkapannya.
            </p>
        </div>

        <div class="d-flex gap-2">
            <a href="<?= base_url('admin_ppdb') ?>" class="btn-monitoring-back">
                ← Kembali ke Daftar PPDB
            </a>
        </div>
    </div>

    <?php if($this->session->flashdata('success')): ?>
        <div class="alert alert-success rounded-4 fw-bold">
            <?= mon_e($this->session->flashdata('success')) ?>
        </div>
    <?php endif; ?>

    <div class="monitoring-card">
        <div class="monitoring-card-head">
            <h5>Verifikasi Dokumen Pendaftar</h5>
            <p>Periksa Ijazah, KK, dan Akta Kelahiran peserta.</p>
        </div>

        <div class="monitoring-card-body">
            
            <div class="d-flex justify-content-between align-items-center mb-3 mt-2">
                <form method="GET" action="<?= base_url('admin_ppdb/monitoring_berkas') ?>" class="d-flex align-items-center gap-2">
                    <label for="status" class="fw-bold mb-0 text-nowrap" style="font-size: 13px;">Filter Status:</label>
                    <select name="status" id="status" class="form-select form-select-sm border-0 bg-light" style="width: 230px; border-radius: 8px; font-weight: 600;" onchange="this.form.submit()">
                        <option value="">-- Semua Status --</option>
                        <option value="Menunggu Verifikasi Berkas" <?= $status_filter == 'Menunggu Verifikasi Berkas' ? 'selected' : '' ?>>⏳ Menunggu Verifikasi</option>
                        <option value="Perlu Perbaikan" <?= $status_filter == 'Perlu Perbaikan' ? 'selected' : '' ?>>⚠️ Perlu Perbaikan</option>
                        <option value="Upload Berkas" <?= $status_filter == 'Upload Berkas' ? 'selected' : '' ?>>📁 Upload Berkas (Belum Kirim)</option>
                    </select>
                </form>
                <div>
                    <button class="btn btn-warning btn-sm fw-bold border-0 text-dark" id="btnRunMassOcr" style="border-radius:8px;">
                        <i class="bi bi-search"></i> Jalankan Auto-Scan OCR
                    </button>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table table-bordered table-striped datatable nowrap align-middle" style="width:100%">
                    <thead class="table-dark">
                        <tr>
                            <th style="width: 50px;">No</th>
                            <th class="text-nowrap">Pendaftar</th>
                            <th class="text-nowrap" style="width:25%">Ijazah</th>
                            <th class="text-nowrap" style="width:25%">Kartu Keluarga</th>
                            <th class="text-nowrap" style="width:25%">Akta Kelahiran</th>
                        </tr>
                    </thead>

                    <tbody>
                        <?php $no=1; foreach($pendaftar as $p): ?>
                            <tr>
                                <td><?= $no++ ?></td>
                                
                                <td>
                                    <div class="siswa-info-box mb-2">
                                        <strong><?= mon_e($p->nama_lengkap ?? '-') ?></strong>
                                        <small>NISN: <?= mon_e($p->nisn ?? '-') ?></small>
                                        <small>No: <?= mon_e($p->no_pendaftaran ?? '-') ?></small>
                                    </div>
                                    <span class="badge bg-secondary mb-2"><?= mon_e($p->status) ?></span>
                                    
                                    <div class="d-flex flex-column gap-1 mt-2">
                                        <a href="<?= base_url('admin_ppdb/detail/'.$p->id) ?>" class="btn-action-mon btn-mon-detail">Lihat Biodata</a>
                                        <?php if($p->status != 'Diterima' && $p->status != 'Ditolak'): ?>
                                            <a href="<?= base_url('admin_ppdb/terima/'.$p->id) ?>" class="btn-action-mon btn-mon-terima" onclick="return confirm('Terima peserta ini?')">Terima</a>
                                            <a href="<?= base_url('admin_ppdb/perbaikan/'.$p->id) ?>" class="btn-action-mon btn-mon-revisi" onclick="return confirm('Tandai perlu perbaikan?')">Revisi</a>
                                        <?php endif; ?>
                                    </div>
                                </td>

                                <?php 
                                $docs = [
                                    'ijazah' => ['label' => 'Ijazah', 'file' => $p->ijazah_file],
                                    'kk' => ['label' => 'Kartu Keluarga', 'file' => $p->kk_file],
                                    'akta_lahir' => ['label' => 'Akta Kelahiran', 'file' => $p->akta_lahir_file]
                                ];
                                
                                $ocr_res = json_decode($p->ocr_results_json ?? '{}', true);
                                $has_scan = !empty($p->ocr_scanned_at);
                                ?>

                                <?php foreach($docs as $key => $d): ?>
                                    <td class="ocr-status-cell" data-id="<?= $p->id ?>" data-doc-key="<?= $key ?>">
                                        <?php if(!empty($d['file'])): ?>
                                            <a href="<?= base_url('uploads/ppdb/'.$d['file']) ?>" 
                                               target="_blank" 
                                               class="text-decoration-none fw-semibold text-primary d-block mb-3 text-truncate" style="max-width: 200px;" title="<?= mon_e($d['file']) ?>">
                                                <?= mon_e($d['file']) ?> ↗
                                            </a>
                                        <?php else: ?>
                                            <span class="text-muted italic d-block mb-3">Tidak ada file</span>
                                        <?php endif; ?>

                                        <!-- OCR Result for this specific doc -->
                                        <div class="doc-ocr-result border-top border-light pt-2">
                                            <?php 
                                            if($has_scan && isset($ocr_res[$key])): 
                                                $res = $ocr_res[$key];
                                                if($res['status'] == 'berhasil'):
                                            ?>
                                                <div style="font-size: 11px; background:#f8fafc; padding:6px; border-radius:6px; border:1px solid #e2e8f0; margin-bottom: 6px;">
                                                    <div style="display:flex; justify-content:space-between; margin-bottom:2px;">
                                                        <span class="text-muted">Nama:</span>
                                                        <?= $res['nama'] ? '<span class="text-success fw-bold">✓</span>' : '<span class="text-danger">✗</span>' ?>
                                                    </div>
                                                    <div style="display:flex; justify-content:space-between; margin-bottom:2px;">
                                                        <span class="text-muted">NIK/NISN:</span>
                                                        <?= ($res['nik'] || $res['nisn']) ? '<span class="text-success fw-bold">✓</span>' : '<span class="text-danger">✗</span>' ?>
                                                    </div>
                                                </div>
                                                <div style="font-size: 10px; color: #94a3b8; text-align:right;">
                                                    <?= date('d/m/y H:i', strtotime($p->ocr_scanned_at)) ?>
                                                </div>
                                            <?php elseif($res['status'] == 'kosong'): ?>
                                                <span class="text-muted d-block mb-1" style="font-size:12px;">Belum diunggah</span>
                                            <?php else: ?>
                                                <span class="text-danger d-block mb-1" style="font-size:12px;"><i class="bi bi-x-circle"></i> Bukan Gambar</span>
                                            <?php endif; ?>
                                                <button class="btn btn-sm btn-outline-secondary py-0 px-2 mt-2 w-100 btn-scan-individual" style="font-size: 11px; border-radius: 6px;"><i class="bi bi-arrow-repeat"></i> Scan Ulang</button>
                                            <?php else: ?>
                                                <span class="text-muted d-block mb-1" style="font-size:12px;">Belum discan OCR</span>
                                                <button class="btn btn-sm btn-outline-primary py-0 px-2 mt-1 w-100 btn-scan-individual" style="font-size: 11px; border-radius: 6px;">
                                                    <i class="bi bi-search"></i> Cek OCR
                                                </button>
                                            <?php endif; ?>
                                        </div>
                                    </td>
                                <?php endforeach; ?>
                            </tr>
                        <?php endforeach; ?>

                    </tbody>
                </table>
            </div>

        </div>
    </div>

</div>

<!-- Custom Alert Modal -->
<div class="modal fade" id="customAlertModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 16px;">
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title fw-bold" id="customAlertTitle">Informasi</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body py-4" id="customAlertMessage"></div>
            <div class="modal-footer border-0 pt-0 justify-content-end">
                <button type="button" class="btn btn-light fw-bold" id="customAlertBtnCancel" data-bs-dismiss="modal" style="display:none; border-radius: 8px;">Batal</button>
                <button type="button" class="btn btn-primary fw-bold px-4" id="customAlertBtnConfirm" style="border-radius: 8px;">OK</button>
            </div>
        </div>
    </div>
</div>

<script>
function showCustomAlert(title, message, isConfirm, onConfirm) {
    document.getElementById('customAlertTitle').innerText = title;
    document.getElementById('customAlertMessage').innerHTML = message;
    
    const btnCancel = document.getElementById('customAlertBtnCancel');
    const btnConfirm = document.getElementById('customAlertBtnConfirm');
    
    if (isConfirm) {
        btnCancel.style.display = 'inline-block';
        btnConfirm.className = 'btn btn-warning fw-bold px-4 text-dark';
        btnConfirm.innerText = 'Ya, Lanjutkan';
    } else {
        btnCancel.style.display = 'none';
        btnConfirm.className = 'btn btn-primary fw-bold px-4';
        btnConfirm.innerText = 'OK';
    }
    
    const modalEl = document.getElementById('customAlertModal');
    let modal = bootstrap.Modal.getInstance(modalEl);
    if (!modal) {
        modal = new bootstrap.Modal(modalEl);
    }
    
    const newBtnConfirm = btnConfirm.cloneNode(true);
    btnConfirm.parentNode.replaceChild(newBtnConfirm, btnConfirm);
    
    newBtnConfirm.addEventListener('click', function() {
        modal.hide();
        if (onConfirm) onConfirm();
    });
    
    modal.show();
}

async function scanPpdb(cell) {
    const ppdbId = cell.getAttribute('data-id');
    const docKey = cell.getAttribute('data-doc-key');
    const cellContainer = cell.querySelector('.doc-ocr-result');
    
    cellContainer.innerHTML = '<span class="text-warning fw-bold"><span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Memindai...</span>';

    try {
        const formData = new FormData();
        formData.append('id', ppdbId);

        const response = await fetch('<?= base_url("admin_ppdb/ajax_mass_check_ocr") ?>', {
            method: 'POST',
            body: formData
        });

        const data = await response.json();

        if (data.status === 'success') {
            const ocr_res = data.results;
            
            // Because ajax_mass_check_ocr scans all 3, we update all 3 cells in this row!
            const row = cell.closest('tr');
            const cells = row.querySelectorAll('.ocr-status-cell');
            
            cells.forEach(c => {
                const k = c.getAttribute('data-doc-key');
                const container = c.querySelector('.doc-ocr-result');
                if(ocr_res[k]) {
                    const res = ocr_res[k];
                    let html = '';
                    if(res.status == 'berhasil'){
                        html += '<div style="font-size: 11px; background:#f8fafc; padding:6px; border-radius:6px; border:1px solid #e2e8f0; margin-bottom: 6px;">';
                        html += '<div style="display:flex; justify-content:space-between; margin-bottom:2px;"><span class="text-muted">Nama:</span>'+(res.nama ? '<span class="text-success fw-bold">✓</span>' : '<span class="text-danger">✗</span>')+'</div>';
                        html += '<div style="display:flex; justify-content:space-between; margin-bottom:2px;"><span class="text-muted">NIK/NISN:</span>'+((res.nik || res.nisn) ? '<span class="text-success fw-bold">✓</span>' : '<span class="text-danger">✗</span>')+'</div>';
                        html += '</div>';
                        html += '<div style="font-size: 10px; color: #94a3b8; text-align:right;">Baru saja</div>';
                    } else if(res.status == 'kosong'){
                        html += '<span class="text-muted d-block mb-1" style="font-size:12px;">Belum diunggah</span>';
                    } else {
                        html += '<span class="text-danger d-block mb-1" style="font-size:12px;"><i class="bi bi-x-circle"></i> Bukan Gambar</span>';
                    }
                    html += '<button class="btn btn-sm btn-outline-secondary py-0 px-2 mt-2 w-100 btn-scan-individual" style="font-size: 11px; border-radius: 6px;"><i class="bi bi-arrow-repeat"></i> Scan Ulang</button>';
                    container.innerHTML = html;
                }
            });
            
            return true;
        } else {
            cellContainer.innerHTML = '<span class="badge bg-secondary" title="'+data.message+'"><i class="bi bi-info-circle"></i> Gagal</span><br><button class="btn btn-sm btn-outline-secondary py-0 px-2 mt-2 w-100 btn-scan-individual" style="font-size: 11px; border-radius: 6px;"><i class="bi bi-arrow-repeat"></i> Scan Ulang</button>';
            return false;
        }
    } catch (error) {
        console.error('Error scanning document', error);
        cellContainer.innerHTML = '<span class="badge bg-danger"><i class="bi bi-x-circle"></i> Error Jaringan</span><br><button class="btn btn-sm btn-outline-secondary py-0 px-2 mt-2 w-100 btn-scan-individual" style="font-size: 11px; border-radius: 6px;"><i class="bi bi-arrow-repeat"></i> Scan Ulang</button>';
        return false;
    }
}

document.addEventListener('DOMContentLoaded', function() {
    const btnRunMassOcr = document.getElementById('btnRunMassOcr');
    
    document.addEventListener('click', async function(e) {
        if(e.target.closest('.btn-scan-individual')) {
            e.preventDefault();
            const btn = e.target.closest('.btn-scan-individual');
            const cell = btn.closest('.ocr-status-cell');
            await scanPpdb(cell);
        }
    });

    if (btnRunMassOcr) {
        btnRunMassOcr.addEventListener('click', async function() {
            // Count unique rows, because scanning 1 cell in a row scans all 3 docs via backend
            const rows = document.querySelectorAll('tbody tr');
            if (rows.length === 0) {
                showCustomAlert('Perhatian', 'Tidak ada pendaftar di tabel ini.', false);
                return;
            }

            showCustomAlert('Konfirmasi Auto-Scan', `Anda akan menjalankan Auto-Scan OCR untuk <b>${rows.length}</b> pendaftar secara berurutan.<br><br>Lanjutkan?`, true, async function() {
                
                btnRunMassOcr.disabled = true;
                btnRunMassOcr.innerHTML = '<span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span> Memindai... (0/' + rows.length + ')';

                let scannedCount = 0;
                let errorCount = 0;

                for (let i = 0; i < rows.length; i++) {
                    const cell = rows[i].querySelector('.ocr-status-cell'); // pick first cell
                    if(cell) {
                        const success = await scanPpdb(cell);
                        if (!success) errorCount++;
                    }
                    scannedCount++;
                    btnRunMassOcr.innerHTML = '<span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span> Memindai... (' + scannedCount + '/' + rows.length + ')';
                }

                btnRunMassOcr.innerHTML = '<i class="bi bi-check2-all"></i> Scan Selesai!';
                btnRunMassOcr.classList.remove('btn-warning');
                btnRunMassOcr.classList.add('btn-success');
                
                setTimeout(() => {
                    showCustomAlert('Selesai', `Pengecekan OCR massal selesai!<br><br><b>Berhasil:</b> ${scannedCount - errorCount}<br><b>Gagal/Error:</b> ${errorCount}`, false, function() {
                        // no reload needed as rows updated
                    });
                }, 500);

            }); 
        });
    }
});
</script>

<?php $this->load->view('templates/footer'); ?>
