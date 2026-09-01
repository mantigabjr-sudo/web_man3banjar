<?php $this->load->view('templates/header'); ?>
<?php $this->load->view('templates/sidebar'); ?>

<div class="content">

<h2 class="glow"><?= $title ?></h2>
<p class="soft-text mb-4">Kelola data calon peserta PPDB MAN 3 Banjar</p>

<?php if($this->session->flashdata('success')): ?>
<div class="alert alert-success">
    <?= $this->session->flashdata('success') ?>
</div>
<?php endif; ?>

<?php if($this->session->flashdata('error')): ?>
<div class="alert alert-danger">
    <?= $this->session->flashdata('error') ?>
</div>
<?php endif; ?>

<div class="card p-4 mb-4">

<div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
    <div class="d-flex flex-wrap gap-2">
        <a href="<?= base_url('admin_ppdb') ?>" class="btn btn-outline-success btn-sm">Semua</a>
        <a href="<?= base_url('admin_ppdb?status=Lengkapi Biodata') ?>" class="btn btn-outline-warning btn-sm">Lengkapi Biodata</a>
        <a href="<?= base_url('admin_ppdb?status=Upload Berkas') ?>" class="btn btn-outline-info btn-sm">Upload Berkas</a>
        <a href="<?= base_url('admin_ppdb/verifikasi') ?>" class="btn btn-outline-primary btn-sm">Menunggu Verifikasi</a>
        <a href="<?= base_url('admin_ppdb?status=Perlu Perbaikan') ?>" class="btn btn-outline-warning btn-sm">Perlu Perbaikan</a>
        <a href="<?= base_url('admin_ppdb/diterima') ?>" class="btn btn-outline-success btn-sm">Diterima</a>
        <a href="<?= base_url('admin_ppdb/ditolak') ?>" class="btn btn-outline-danger btn-sm">Ditolak</a>
    </div>

    <!-- Tombol Tarik Pendaftar dari DomaiNesia -->
    <div>
        <button type="button" class="btn btn-primary btn-sm fw-bold shadow-sm" data-bs-toggle="modal" data-bs-target="#modalSyncCloud">
            <i class="bi bi-cloud-arrow-down-fill me-1"></i> Tarik Pendaftar dari DomaiNesia
        </button>
    </div>
</div>

</div>

<!-- ═══ MODAL SINKRONISASI CLOUD DOMAINESIA ═══ -->
<div class="modal fade" id="modalSyncCloud" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 20px; overflow: hidden;">
            <div class="modal-header bg-primary text-white px-4 py-3">
                <h5 class="modal-title fw-bold"><i class="bi bi-cloud-arrow-down-fill me-2"></i>Tarik Data Pendaftar Cloud</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4 bg-light">
                <p class="text-muted small mb-3">
                    Fitur ini akan mengambil data pendaftar baru yang mendaftar online 24/7 di website DomaiNesia serta otomatis mengunduh berkas KK, Akta, dan Ijazah ke server lokal ini.
                </p>

                <form id="formSyncCloud">
                    <div class="mb-3">
                        <label class="form-label fw-bold small text-muted">URL ENDPOINT CLOUD</label>
                        <input type="text" id="sync_cloud_url" name="cloud_url" class="form-control form-control-sm" value="https://man3banjar.sch.id/api/ppdb/sync" required>
                        <small class="text-muted" style="font-size: 11px;">Contoh: https://domainmadrasah.sch.id/api/ppdb/sync</small>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold small text-muted">API SECRET KEY</label>
                        <input type="password" id="sync_api_key" name="api_key" class="form-control form-control-sm" value="LABSYS_SYNC_SECRET_KEY_MAN3BANJAR_2026" required>
                    </div>

                    <div id="sync_status_box" class="d-none alert py-2 px-3 small rounded-3 mb-0"></div>
                </form>
            </div>
            <div class="modal-footer bg-white border-top px-4 py-3">
                <button type="button" class="btn btn-secondary btn-sm rounded-3" data-bs-dismiss="modal">Batal</button>
                <button type="button" id="btnProsesSync" class="btn btn-primary btn-sm fw-bold rounded-3 px-3" onclick="jalankanSyncCloud()">
                    <i class="bi bi-arrow-repeat me-1"></i> Mulai Sinkronisasi
                </button>
            </div>
        </div>
    </div>
</div>

<script>
function jalankanSyncCloud(){
    let btn = document.getElementById('btnProsesSync');
    let statusBox = document.getElementById('sync_status_box');
    let cloudUrl = document.getElementById('sync_cloud_url').value;
    let apiKey = document.getElementById('sync_api_key').value;

    btn.disabled = true;
    btn.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span> Menghubungkan ke Cloud...';
    statusBox.className = 'alert alert-info py-2 px-3 small rounded-3 mb-0 d-block';
    statusBox.innerHTML = '<i class="bi bi-hourglass-split me-1"></i> Sedang mengambil data & mengunduh berkas dari DomaiNesia...';

    let formData = new FormData();
    formData.append('cloud_url', cloudUrl);
    formData.append('api_key', apiKey);

    fetch('<?= base_url("admin_ppdb/sync_from_cloud") ?>', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        btn.disabled = false;
        btn.innerHTML = '<i class="bi bi-arrow-repeat me-1"></i> Mulai Sinkronisasi';

        if(data.status === 'success'){
            statusBox.className = 'alert alert-success py-2 px-3 small rounded-3 mb-0 d-block';
            statusBox.innerHTML = '<i class="bi bi-check-circle-fill me-1"></i> ' + data.message;
            setTimeout(() => {
                window.location.reload();
            }, 2500);
        } else {
            statusBox.className = 'alert alert-danger py-2 px-3 small rounded-3 mb-0 d-block';
            statusBox.innerHTML = '<i class="bi bi-exclamation-triangle-fill me-1"></i> ' + data.message;
        }
    })
    .catch(err => {
        btn.disabled = false;
        btn.innerHTML = '<i class="bi bi-arrow-repeat me-1"></i> Mulai Sinkronisasi';
        statusBox.className = 'alert alert-danger py-2 px-3 small rounded-3 mb-0 d-block';
        statusBox.innerHTML = '<i class="bi bi-x-circle-fill me-1"></i> Terjadi kesalahan koneksi jaringan.';
    });
}
</script>

<?php if(empty($pendaftar) && (!empty($keyword) || !empty($status_filter))): ?>
<!-- Empty Result Modal -->
<div class="modal fade" id="emptyResultModalPpdb" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 16px;">
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title fw-bold text-danger"><i class="bi bi-exclamation-circle"></i> Informasi Pencarian</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body py-4" style="font-size: 15px;">
                Maaf, data pendaftar yang Anda cari dengan filter tersebut <strong>tidak ditemukan</strong>.<br><br>
                Silakan coba gunakan kata kunci atau status yang berbeda.
            </div>
            <div class="modal-footer border-0 pt-0 justify-content-end">
                <button type="button" class="btn btn-primary fw-bold px-4" data-bs-dismiss="modal" style="border-radius: 8px;">Mengerti</button>
            </div>
        </div>
    </div>
</div>
<script>
document.addEventListener('DOMContentLoaded', function() {
    let emptyModal = new bootstrap.Modal(document.getElementById('emptyResultModalPpdb'));
    emptyModal.show();
});
</script>
<?php endif; ?>
<div class="mb-3 d-flex flex-wrap gap-2">

    <a href="<?= base_url('admin_ppdb/export_all') ?>" class="btn btn-success btn-sm">
        Export Semua PPDB
    </a>

    <a href="<?= base_url('admin_ppdb/export_diterima') ?>" class="btn btn-primary btn-sm">
        Export Diterima
    </a>

</div>

<div class="card p-4 mb-4" style="border-radius: 24px; box-shadow: 0 16px 42px rgba(15,23,42,.07); border:1px solid #e2e8f0;">
    <form method="GET" action="<?= base_url('admin_ppdb') ?>">
        <div class="row g-3 align-items-end">
            <div class="col-md-3">
                <label class="form-label text-success fw-bold">Filter Status</label>
                <select name="status" class="form-select" style="border-radius: 12px; height: 46px;">
                    <option value="">Semua Status</option>
                    <?php 
                    $statuses = ['Lengkapi Biodata', 'Upload Berkas', 'Menunggu Verifikasi Berkas', 'Perlu Perbaikan', 'Diterima', 'Ditolak'];
                    foreach($statuses as $st):
                    ?>
                        <option value="<?= $st ?>" <?= (isset($status_filter) && $status_filter == $st) ? 'selected' : '' ?>><?= $st ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-7">
                <label class="form-label text-success fw-bold">Pencarian Calon Peserta</label>
                <input type="text" name="keyword" class="form-control" placeholder="Ketik nama, NISN, atau no pendaftaran..." value="<?= isset($keyword) ? htmlspecialchars($keyword) : '' ?>" style="border-radius: 12px; height: 46px;">
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-success w-100 fw-bold" style="border-radius: 12px; height: 46px;">Cari Data</button>
            </div>
        </div>
    </form>
</div>

<div class="card p-4">

<div class="d-flex justify-content-between align-items-center mb-3">
    <h5 class="m-0 text-success fw-bold">Daftar Pendaftar</h5>
    <button class="btn btn-warning fw-bold d-flex align-items-center gap-2 px-3 border-0" id="btnRunMassOcr" style="border-radius:14px; color:#92400e;">
        <i class="bi bi-search"></i> Jalankan Auto-Scan OCR Berkas
    </button>
</div>

<table class="table table-bordered table-striped align-middle datatable nowrap" style="width:100%">

<thead class="table-success">
<tr>
    <th>No</th>
    <th>No Pendaftaran</th>
    <th>Nama</th>
    <th>NISN</th>
    <th>Asal Sekolah</th>
    <th>Status</th>
    <th>Hasil Pindai OCR</th>
    <th>Migrasi</th>
    <th>Aksi</th>
</tr>
</thead>

<tbody>

<?php $no=1; foreach($pendaftar as $p): ?>
<tr>
    <td><?= $no++ ?></td>
    <td><?= $p->no_pendaftaran ?></td>
    <td><?= $p->nama_lengkap ?></td>
    <td><?= $p->nisn ?></td>
    <td><?= $p->asal_sekolah ?></td>

    <td>
        <?php if($p->status == 'Diterima'): ?>
            <span class="badge bg-success">Diterima</span>
        <?php elseif($p->status == 'Ditolak'): ?>
            <span class="badge bg-danger">Ditolak</span>
        <?php elseif($p->status == 'Perlu Perbaikan'): ?>
            <span class="badge bg-warning text-dark">Perlu Perbaikan</span>
        <?php else: ?>
            <span class="badge bg-primary"><?= $p->status ?></span>
        <?php endif; ?>
    </td>

    <td class="ocr-status-cell" data-id="<?= $p->id ?>">
        <?php if(!empty($p->ocr_scanned_at)): ?>
            <div style="font-size: 11px; color: #64748b; margin-bottom: 6px;">
                Discan: <?= date('d/m/y H:i', strtotime($p->ocr_scanned_at)) ?>
            </div>
            <?php 
                $ocr_res = json_decode($p->ocr_results_json, true);
                if($ocr_res): 
            ?>
                <div style="display:flex; flex-direction:column; gap:6px;">
                <?php 
                    $docLabels = ['ijazah'=>'Ijazah', 'kk'=>'KK', 'akta_lahir'=>'Akta Lhr'];
                    foreach($docLabels as $key => $label): 
                        if(isset($ocr_res[$key])): 
                            $doc = $ocr_res[$key];
                ?>
                            <div style="background:#f8fafc; padding:4px 6px; border-radius:4px; border:1px solid #e2e8f0; font-size:10.5px;">
                                <strong class="text-dark d-block mb-1"><?= $label ?></strong>
                                <?php if($doc['status'] == 'berhasil'): ?>
                                    <div style="display:flex; gap:6px;">
                                        <span title="Nama">N: <?= $doc['nama'] ? '<span class="text-success">✓</span>' : '<span class="text-danger">✗</span>' ?></span>
                                        <span title="NIK">K: <?= $doc['nik'] ? '<span class="text-success">✓</span>' : '<span class="text-danger">✗</span>' ?></span>
                                        <span title="NISN">S: <?= $doc['nisn'] ? '<span class="text-success">✓</span>' : '<span class="text-danger">✗</span>' ?></span>
                                    </div>
                                <?php elseif($doc['status'] == 'kosong'): ?>
                                    <span class="text-muted italic">Belum diunggah</span>
                                <?php else: ?>
                                    <span class="text-danger italic">Gagal/Bukan Gambar</span>
                                <?php endif; ?>
                            </div>
                <?php 
                        endif;
                    endforeach; 
                ?>
                </div>
            <?php endif; ?>
        <?php else: ?>
            <span class="text-muted d-block mb-2" style="font-size:12px;">Belum discan</span>
        <?php endif; ?>
        <button class="btn btn-sm btn-outline-primary mt-2 w-100 py-0 px-2 btn-scan-individual" style="font-size: 11px; border-radius: 6px;" title="Scan OCR pendaftar ini">
            <i class="bi bi-search"></i> <?= !empty($p->ocr_scanned_at) ? 'Scan Ulang' : 'Cek OCR' ?>
        </button>
    </td>

    <td>
        <?php if($p->is_migrated == 1): ?>
            <span class="badge bg-success">Sudah</span>
        <?php else: ?>
            <span class="badge bg-secondary">Belum</span>
        <?php endif; ?>
    </td>

    <td>
        <div class="dropdown">
            <button class="btn btn-light btn-sm fw-bold border px-3 dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false" style="border-radius: 8px;">
                Aksi
            </button>
            <ul class="dropdown-menu shadow-sm" style="border-radius: 12px;">
                <li>
                    <a class="dropdown-item" href="<?= base_url('admin_ppdb/detail/'.$p->id) ?>">
                        <i class="bi bi-eye text-info me-2"></i> Detail / Verifikasi
                    </a>
                </li>
                <?php if($p->status != 'Diterima' && $p->status != 'Ditolak'): ?>
                <li><hr class="dropdown-divider"></li>
                <li>
                    <a class="dropdown-item" href="<?= base_url('admin_ppdb/terima/'.$p->id) ?>" onclick="return confirm('Terima peserta ini?')">
                        <i class="bi bi-check-circle text-success me-2"></i> Terima
                    </a>
                </li>
                <li>
                    <a class="dropdown-item" href="<?= base_url('admin_ppdb/tolak/'.$p->id) ?>" onclick="return confirm('Tolak peserta ini?')">
                        <i class="bi bi-x-circle text-danger me-2"></i> Tolak
                    </a>
                </li>
                <li>
                    <a class="dropdown-item" href="<?= base_url('admin_ppdb/perbaikan/'.$p->id) ?>" onclick="return confirm('Tandai peserta ini perlu perbaikan?')">
                        <i class="bi bi-arrow-counterclockwise text-warning me-2"></i> Perlu Perbaikan
                    </a>
                </li>
                <?php endif; ?>
                
                <?php if(($p->status == 'Diterima' || $p->status == 'Ditolak') && $p->is_migrated == 0): ?>
                <li><hr class="dropdown-divider"></li>
                <li>
                    <a class="dropdown-item" href="<?= base_url('admin_ppdb/batal_status/'.$p->id) ?>" onclick="return confirm('Batalkan status peserta ini?')">
                        <i class="bi bi-arrow-left-circle text-secondary me-2"></i> Batal Status
                    </a>
                </li>
                <?php endif; ?>
                
                <?php if($p->status == 'Diterima' && $p->is_migrated == 0): ?>
                <li><hr class="dropdown-divider"></li>
                <li>
                    <a class="dropdown-item" href="<?= base_url('admin_ppdb/migrasi/'.$p->id) ?>" onclick="return confirm('Migrasikan ke data siswa?')">
                        <i class="bi bi-database-add text-primary me-2"></i> Migrasi ke Siswa
                    </a>
                </li>
                <?php endif; ?>
            </ul>
        </div>
    </td>
</tr>
<?php endforeach; ?>

</tbody>

</table>

</div>

<!-- Custom Alert Modal -->
<div class="modal fade" id="customAlertModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 16px;">
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title fw-bold" id="customAlertTitle">Informasi</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body py-4" id="customAlertMessage">
                Pesan
            </div>
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
    cell.innerHTML = '<span class="text-warning fw-bold"><span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Memindai 3 Berkas...</span>';

    try {
        const formData = new FormData();
        formData.append('id', ppdbId);

        const response = await fetch('<?= base_url("admin_ppdb/ajax_mass_check_ocr") ?>', {
            method: 'POST',
            body: formData
        });

        const data = await response.json();

        if (data.status === 'success') {
            let cellHtml = '<div style="font-size: 11px; color: #64748b; margin-bottom: 6px;">Baru saja discan</div>';
            cellHtml += '<div style="display:flex; flex-direction:column; gap:6px;">';
            
            const docLabels = {'ijazah':'Ijazah', 'kk':'KK', 'akta_lahir':'Akta Lhr'};
            const ocr_res = data.results;
            
            for (const key in docLabels) {
                if (ocr_res[key]) {
                    const doc = ocr_res[key];
                    const label = docLabels[key];
                    cellHtml += '<div style="background:#f8fafc; padding:4px 6px; border-radius:4px; border:1px solid #e2e8f0; font-size:10.5px;">';
                    cellHtml += '<strong class="text-dark d-block mb-1">' + label + '</strong>';
                    
                    if (doc.status === 'berhasil') {
                        cellHtml += '<div style="display:flex; gap:6px;">';
                        cellHtml += '<span title="Nama">N: ' + (doc.nama ? '<span class="text-success">✓</span>' : '<span class="text-danger">✗</span>') + '</span>';
                        cellHtml += '<span title="NIK">K: ' + (doc.nik ? '<span class="text-success">✓</span>' : '<span class="text-danger">✗</span>') + '</span>';
                        cellHtml += '<span title="NISN">S: ' + (doc.nisn ? '<span class="text-success">✓</span>' : '<span class="text-danger">✗</span>') + '</span>';
                        cellHtml += '</div>';
                    } else if (doc.status === 'kosong') {
                        cellHtml += '<span class="text-muted italic">Belum diunggah</span>';
                    } else {
                        cellHtml += '<span class="text-danger italic">Gagal/Bukan Gambar</span>';
                    }
                    cellHtml += '</div>';
                }
            }
            cellHtml += '</div>';
            cellHtml += '<button class="btn btn-sm btn-outline-primary mt-2 w-100 py-0 px-2 btn-scan-individual" style="font-size: 11px; border-radius: 6px;"><i class="bi bi-arrow-repeat"></i> Scan Ulang</button>';
            
            cell.innerHTML = cellHtml;
            return true;
        } else {
            cell.innerHTML = '<span class="text-danger"><i class="bi bi-exclamation-circle"></i> ' + data.message + '</span><br><button class="btn btn-sm btn-outline-primary mt-2 w-100 py-0 px-2 btn-scan-individual" style="font-size: 11px; border-radius: 6px;">Scan Ulang</button>';
            return false;
        }
    } catch (error) {
        console.error('Error scanning document', error);
        cell.innerHTML = '<span class="badge bg-danger"><i class="bi bi-x-circle"></i> Error Jaringan</span><br><button class="btn btn-sm btn-outline-primary mt-2 w-100 py-0 px-2 btn-scan-individual" style="font-size: 11px; border-radius: 6px;">Scan Ulang</button>';
        return false;
    }
}
document.addEventListener('DOMContentLoaded', function() {
    const btnRunMassOcr = document.getElementById('btnRunMassOcr');
    
    // Individual scan
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
            const cells = document.querySelectorAll('.ocr-status-cell');
            if (cells.length === 0) {
                showCustomAlert('Perhatian', 'Tidak ada dokumen untuk dipindai di tabel ini.', false);
                return;
            }

            showCustomAlert('Konfirmasi Scan Massal', `Anda akan memindai <b>${cells.length}</b> pendaftar (masing-masing 3 dokumen: Ijazah, KK, Akta) secara berurutan. Proses ini memakan waktu beberapa saat.<br><br>Lanjutkan?`, true, async function() {
                
                btnRunMassOcr.disabled = true;
                btnRunMassOcr.innerHTML = '<span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span> Memindai... (0/' + cells.length + ')';

                let scannedCount = 0;
                let errorCount = 0;

                for (let i = 0; i < cells.length; i++) {
                    const success = await scanPpdb(cells[i]);
                    if (!success) errorCount++;

                    scannedCount++;
                    btnRunMassOcr.innerHTML = '<span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span> Memindai... (' + scannedCount + '/' + cells.length + ')';
                }

                btnRunMassOcr.innerHTML = '<i class="bi bi-check2-all"></i> Scan Selesai!';
                btnRunMassOcr.classList.remove('btn-warning');
                btnRunMassOcr.classList.add('btn-success');
                
                setTimeout(() => {
                    showCustomAlert('Selesai', `Pengecekan OCR massal selesai!<br><br><b>Berhasil:</b> ${scannedCount - errorCount}<br><b>Gagal/Error:</b> ${errorCount}`, false, function() {
                        window.location.reload();
                    });
                }, 500);

            }); // End of onConfirm
        });
    }
});
</script>

<?php $this->load->view('templates/footer'); ?>