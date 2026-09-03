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

<div class="card border-0 shadow-sm rounded-4 p-3 mb-4 bg-white" style="border: 1px solid #e2e8f0 !important;">
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
        <!-- Tab Status Filter -->
        <div class="d-flex flex-wrap gap-2">
            <a href="<?= base_url('admin_ppdb') ?>" class="btn btn-sm rounded-pill fw-bold <?= empty($status_filter) ? 'btn-success' : 'btn-light border' ?>">
                Semua
            </a>
            <a href="<?= base_url('admin_ppdb?status=Lengkapi Biodata') ?>" class="btn btn-sm rounded-pill fw-bold <?= $status_filter == 'Lengkapi Biodata' ? 'btn-warning text-dark' : 'btn-light border' ?>">
                Lengkapi Biodata
            </a>
            <a href="<?= base_url('admin_ppdb?status=Upload Berkas') ?>" class="btn btn-sm rounded-pill fw-bold <?= $status_filter == 'Upload Berkas' ? 'btn-info text-dark' : 'btn-light border' ?>">
                Upload Berkas
            </a>
            <a href="<?= base_url('admin_ppdb/verifikasi') ?>" class="btn btn-sm rounded-pill fw-bold <?= $status_filter == 'Menunggu Verifikasi Berkas' ? 'btn-primary' : 'btn-light border' ?>">
                ⏳ Menunggu Verifikasi
            </a>
            <a href="<?= base_url('admin_ppdb?status=Lulus Verifikasi') ?>" class="btn btn-sm rounded-pill fw-bold <?= $status_filter == 'Lulus Verifikasi' ? 'btn-success' : 'btn-light border' ?>">
                ✓ Lulus Verifikasi (Tes)
            </a>
            <a href="<?= base_url('admin_ppdb?status=Perlu Perbaikan') ?>" class="btn btn-sm rounded-pill fw-bold <?= $status_filter == 'Perlu Perbaikan' ? 'btn-warning text-dark' : 'btn-light border' ?>">
                ⚠️ Perlu Perbaikan
            </a>
            <a href="<?= base_url('admin_ppdb/diterima') ?>" class="btn btn-sm rounded-pill fw-bold <?= $status_filter == 'Diterima' ? 'btn-success' : 'btn-light border' ?>">
                ★ Diterima
            </a>
            <a href="<?= base_url('admin_ppdb/ditolak') ?>" class="btn btn-sm rounded-pill fw-bold <?= $status_filter == 'Ditolak' ? 'btn-danger' : 'btn-light border' ?>">
                ✗ Ditolak
            </a>
        </div>

        <!-- Tombol Aksi Cloud & Export -->
        <div class="d-flex gap-2">
            <button type="button" class="btn btn-primary btn-sm fw-bold shadow-sm rounded-pill px-3" data-bs-toggle="modal" data-bs-target="#modalSyncCloud">
                <i class="bi bi-cloud-arrow-down-fill me-1"></i> Tarik Cloud
            </button>
            <a href="<?= base_url('admin_ppdb/export_all') ?>" class="btn btn-outline-success btn-sm fw-bold rounded-pill px-3">
                <i class="bi bi-file-earmark-excel-fill me-1"></i> Export Excel
            </a>
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
    <th style="width: 40px;">No</th>
    <th>Calon Peserta</th>
    <th>Jalur &amp; Peminatan</th>
    <th>Asal Sekolah &amp; Kontak</th>
    <th>Status Seleksi</th>
    <th>Hasil Scan OCR</th>
    <th>Migrasi</th>
    <th style="min-width: 140px;">Aksi Cepat</th>
</tr>
</thead>

<tbody>

<?php $no=1; foreach($pendaftar as $p): ?>
<tr>
    <td><?= $no++ ?></td>
    
    <td>
        <strong class="text-dark d-block" style="font-size: 13.5px;"><?= htmlspecialchars($p->nama_lengkap ?? '-') ?></strong>
        <div class="text-muted small" style="font-size: 11.5px;">
            <span>NISN: <?= htmlspecialchars($p->nisn ?? '-') ?></span> &bull; 
            <span>No: <?= htmlspecialchars($p->no_pendaftaran ?? '-') ?></span>
        </div>
        <?php if(!empty($p->no_peserta_tes)): ?>
            <div class="badge bg-success mt-1" style="font-size: 11px;">
                <i class="bi bi-person-badge"></i> No. Tes: <?= htmlspecialchars($p->no_peserta_tes) ?>
            </div>
        <?php endif; ?>
    </td>

    <td>
        <span class="badge bg-light text-success border border-success fw-bold d-inline-block mb-1">
            <?= htmlspecialchars($p->jalur_pendaftaran ?? 'Reguler') ?>
        </span>
        <div class="text-dark small fw-semibold" style="font-size: 11.5px;">
            1. <?= htmlspecialchars($p->pilihan_jurusan_1 ?? 'MIPA') ?><br>
            <span class="text-muted">2. <?= htmlspecialchars($p->pilihan_jurusan_2 ?? 'IPS') ?></span>
        </div>
    </td>

    <td>
        <div class="fw-semibold text-dark small mb-1"><?= htmlspecialchars($p->asal_sekolah ?? '-') ?></div>
        <?php 
        $clean_hp = preg_replace('/[^0-9]/', '', $p->no_hp ?? '');
        if(substr($clean_hp, 0, 1) === '0') $clean_hp = '62' . substr($clean_hp, 1);
        ?>
        <?php if(!empty($clean_hp)): ?>
            <a href="https://wa.me/<?= $clean_hp ?>" target="_blank" class="badge bg-light text-success border border-success text-decoration-none py-1 px-2">
                <i class="bi bi-whatsapp"></i> <?= htmlspecialchars($p->no_hp) ?>
            </a>
        <?php else: ?>
            <span class="text-muted small">-</span>
        <?php endif; ?>
    </td>

    <td>
        <?php if($p->status == 'Diterima'): ?>
            <span class="badge bg-success py-1 px-2"><i class="bi bi-check-circle-fill me-1"></i> Diterima</span>
        <?php elseif($p->status == 'Ditolak'): ?>
            <span class="badge bg-danger py-1 px-2"><i class="bi bi-x-circle-fill me-1"></i> Ditolak</span>
        <?php elseif($p->status == 'Lulus Verifikasi' || $p->status == 'Menuju Tes'): ?>
            <span class="badge bg-success py-1 px-2" style="background:#059669 !important;"><i class="bi bi-calendar-check-fill me-1"></i> Lulus Verifikasi (Tes)</span>
            <?php if(!empty($p->tanggal_tes)): ?>
                <div class="text-muted" style="font-size: 10.5px; margin-top: 2px;">
                    Tes: <?= date('d/m/Y', strtotime($p->tanggal_tes)) ?>
                </div>
            <?php endif; ?>
        <?php elseif($p->status == 'Perlu Perbaikan'): ?>
            <span class="badge bg-warning text-dark py-1 px-2"><i class="bi bi-exclamation-triangle-fill me-1"></i> Perlu Perbaikan</span>
        <?php elseif($p->status == 'Menunggu Verifikasi Berkas'): ?>
            <span class="badge bg-primary py-1 px-2"><i class="bi bi-hourglass-split me-1"></i> Menunggu Verifikasi</span>
        <?php else: ?>
            <span class="badge bg-secondary py-1 px-2"><?= htmlspecialchars($p->status) ?></span>
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
                <div style="display:flex; flex-direction:column; gap:4px;">
                <?php 
                    $docLabels = ['ijazah'=>'Ijazah', 'kk'=>'KK', 'akta_lahir'=>'Akta'];
                    foreach($docLabels as $key => $label): 
                        if(isset($ocr_res[$key])): 
                            $doc = $ocr_res[$key];
                ?>
                            <div style="background:#f8fafc; padding:3px 5px; border-radius:4px; border:1px solid #e2e8f0; font-size:10px;">
                                <strong class="text-dark d-block"><?= $label ?></strong>
                                <?php if($doc['status'] == 'berhasil'): ?>
                                    <div style="display:flex; gap:4px;">
                                        <span title="Nama">N: <?= $doc['nama'] ? '<span class="text-success fw-bold">✓</span>' : '<span class="text-danger">✗</span>' ?></span>
                                        <span title="NIK/NISN">K: <?= ($doc['nik'] || $doc['nisn']) ? '<span class="text-success fw-bold">✓</span>' : '<span class="text-danger">✗</span>' ?></span>
                                    </div>
                                <?php elseif($doc['status'] == 'kosong'): ?>
                                    <span class="text-muted">Belum ada</span>
                                <?php else: ?>
                                    <span class="text-danger">Gagal</span>
                                <?php endif; ?>
                            </div>
                <?php 
                        endif;
                    endforeach; 
                ?>
                </div>
            <?php endif; ?>
        <?php else: ?>
            <span class="text-muted d-block mb-1" style="font-size:11px;">Belum discan</span>
        <?php endif; ?>
        <button class="btn btn-sm btn-outline-primary mt-1 w-100 py-0 px-1 btn-scan-individual" style="font-size: 10.5px; border-radius: 4px;" title="Scan OCR pendaftar ini">
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
        <div class="d-flex flex-column gap-1">
            <!-- Tombol Verifikasi Cepat & Jadwal Tes -->
            <button type="button" class="btn btn-sm btn-success fw-bold py-1 px-2" style="border-radius: 6px; font-size: 11px;" 
                    onclick="bukaModalVerifikasi(<?= htmlspecialchars(json_encode($p), ENT_QUOTES, 'UTF-8') ?>)">
                <i class="bi bi-shield-check"></i> Verifikasi &amp; Jadwal
            </button>

            <!-- Tombol Cetak Kartu -->
            <a href="<?= base_url('ppdb/cetak_kartu/'.$p->id) ?>" target="_blank" class="btn btn-sm btn-outline-primary py-1 px-2 fw-bold" style="border-radius: 6px; font-size: 11px;">
                <i class="bi bi-printer"></i> Cetak Kartu
            </a>

            <!-- Tombol Kirim WhatsApp -->
            <?php if(!empty($clean_hp)): ?>
                <button type="button" class="btn btn-sm btn-outline-success py-1 px-2 fw-bold" style="border-radius: 6px; font-size: 11px;"
                        onclick="kirimWaNotifikasi('<?= $clean_hp ?>', '<?= addslashes($p->nama_lengkap) ?>', '<?= $p->no_pendaftaran ?>', '<?= $p->no_peserta_tes ?? '' ?>', '<?= $p->tanggal_tes ?? '' ?>', '<?= $p->ruang_tes ?? '' ?>', '<?= $p->status ?>')">
                    <i class="bi bi-whatsapp"></i> Kirim WA
                </button>
            <?php endif; ?>

            <a href="<?= base_url('admin_ppdb/detail/'.$p->id) ?>" class="btn btn-sm btn-light border py-1 px-2 text-center text-muted" style="border-radius: 6px; font-size: 11px;">
                Detail Biodata
            </a>
        </div>
    </td>
</tr>
<?php endforeach; ?>

</tbody>
</tbody>
</table>
</div>
</div>

<!-- Modal Verifikasi & Penetapan Jadwal Tes -->
<div class="modal fade" id="modalVerifikasiPpdb" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 16px;">
            <form method="post" action="<?= base_url('admin_ppdb/proses_verifikasi') ?>">
                <input type="hidden" name="id" id="mv_id">
                <input type="hidden" name="redirect_to" value="admin_ppdb">

                <div class="modal-header border-bottom py-3 bg-light" style="border-radius: 16px 16px 0 0;">
                    <div>
                        <h6 class="modal-title fw-bold text-success mb-0"><i class="bi bi-shield-check me-1"></i> Verifikasi Berkas &amp; Jadwal Tes</h6>
                        <small class="text-muted" id="mv_nama_preview">Nama Peserta</small>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label class="form-label fw-bold small text-secondary">Status Verifikasi</label>
                        <select name="status" id="mv_status" class="form-select fw-bold" required onchange="toggleJadwalTes(this.value)">
                            <option value="Lulus Verifikasi" class="text-success fw-bold">✓ Lulus Verifikasi (Menuju Tes Seleksi)</option>
                            <option value="Perlu Perbaikan" class="text-warning fw-bold">⚠️ Perlu Perbaikan Berkas</option>
                            <option value="Menunggu Verifikasi Berkas">⏳ Menunggu Verifikasi</option>
                            <option value="Diterima" class="text-success fw-bold">★ Diterima (Lulus Final)</option>
                            <option value="Ditolak" class="text-danger fw-bold">✗ Ditolak</option>
                        </select>
                    </div>

                    <div id="boxJadwalTes" class="p-3 border rounded-3 bg-light mb-3">
                        <div class="fw-bold text-dark small mb-2"><i class="bi bi-calendar-check text-success me-1"></i> Jadwal Ujian (Otomatis Diterbitkan ke Kartu)</div>
                        <div class="row g-2">
                            <div class="col-12">
                                <label class="form-label small mb-1 text-secondary">Tanggal Tes Seleksi</label>
                                <input type="date" name="tanggal_tes" id="mv_tanggal_tes" class="form-control form-control-sm">
                            </div>
                            <div class="col-6">
                                <label class="form-label small mb-1 text-secondary">Waktu / Jam</label>
                                <input type="text" name="jam_tes" id="mv_jam_tes" class="form-control form-control-sm" placeholder="08:00 - 11.30 WITA">
                            </div>
                            <div class="col-6">
                                <label class="form-label small mb-1 text-secondary">Ruang / Lokasi</label>
                                <input type="text" name="ruang_tes" id="mv_ruang_tes" class="form-control form-control-sm" placeholder="Kampus MAN 3 Banjar">
                            </div>
                        </div>
                    </div>

                    <div class="mb-2">
                        <label class="form-label fw-bold small text-secondary">Catatan Verifikator (Opsional)</label>
                        <textarea name="catatan_verifikasi" id="mv_catatan" class="form-control" rows="2" placeholder="Tuliskan catatan jika ada berkas yang kurang jelas atau instruksi khusus..."></textarea>
                    </div>
                </div>

                <div class="modal-footer border-top py-2 bg-light justify-content-between" style="border-radius: 0 0 16px 16px;">
                    <button type="button" class="btn btn-sm btn-light fw-bold" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-sm btn-success fw-bold px-3">
                        <i class="bi bi-check-circle-fill me-1"></i> Simpan &amp; Terbitkan No Tes
                    </button>
                </div>
            </form>
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
function bukaModalVerifikasi(data) {
    document.getElementById('mv_id').value = data.id;
    document.getElementById('mv_nama_preview').innerText = (data.nama_lengkap || '-') + ' (' + (data.no_pendaftaran || '-') + ')';
    
    // Set status
    const statusSelect = document.getElementById('mv_status');
    if (data.status === 'Lulus Verifikasi' || data.status === 'Menuju Tes') {
        statusSelect.value = 'Lulus Verifikasi';
    } else if (data.status === 'Perlu Perbaikan') {
        statusSelect.value = 'Perlu Perbaikan';
    } else if (data.status === 'Diterima') {
        statusSelect.value = 'Diterima';
    } else if (data.status === 'Ditolak') {
        statusSelect.value = 'Ditolak';
    } else {
        statusSelect.value = 'Lulus Verifikasi';
    }

    document.getElementById('mv_tanggal_tes').value = data.tanggal_tes || '';
    document.getElementById('mv_jam_tes').value = data.jam_tes || '08:00 - 11.30 WITA';
    document.getElementById('mv_ruang_tes').value = data.ruang_tes || 'Kampus MAN 3 Banjar';
    document.getElementById('mv_catatan').value = data.catatan_verifikasi || '';

    toggleJadwalTes(statusSelect.value);

    const modal = new bootstrap.Modal(document.getElementById('modalVerifikasiPpdb'));
    modal.show();
}

function toggleJadwalTes(status) {
    const box = document.getElementById('boxJadwalTes');
    if (status === 'Lulus Verifikasi' || status === 'Menuju Tes' || status === 'Diterima') {
        box.style.display = 'block';
    } else {
        box.style.display = 'none';
    }
}

function kirimWaNotifikasi(noHp, nama, noDaftar, noTes, tglTes, ruangTes, status) {
    const baseUrl = '<?= base_url() ?>';
    let tglText = tglTes ? tglTes : 'Sesuai Pengumuman Panitia';
    let ruangText = ruangTes ? ruangTes : 'Kampus MAN 3 Banjar';
    let tesInfo = noTes ? noTes : 'Diterbitkan saat kartu dicetak';

    let pesan = `*PEMBERITAHUAN VERIFIKASI PMB MAN 3 BANJAR*\n\n` +
        `Assalamu'alaikum Wr. Wb.\n` +
        `Yth. Orang Tua / Calon Siswa,\n` +
        `*Nama:* ${nama}\n` +
        `*No. Pendaftaran:* ${noDaftar}\n\n`;

    if (status === 'Perlu Perbaikan') {
        pesan += `Mohon maaf, berkas pendaftaran Anda memerlukan *PERBAIKAN*. Silakan login ke akun pendaftaran Anda untuk memeriksa dan mengunggah kembali dokumen yang diminta:\n` +
            `${baseUrl}ppdb/login\n\n`;
    } else {
        pesan += `Alhamdulillah, berkas pendaftaran Anda telah *DIVERIFIKASI* dan dinyatakan *LULUS VERIFIKASI (MENUJU TES SELEKSI)*.\n\n` +
            `*JADWAL & LOKASI TES:*\n` +
            `• No. Peserta Ujian: *${tesInfo}*\n` +
            `• Tanggal Ujian: *${tglText}*\n` +
            `• Waktu: *08.00 - Selesai WITA*\n` +
            `• Lokasi/Ruang: *${ruangText}*\n\n` +
            `Silakan unduh dan cetak *KARTU PESERTA UJIAN* Anda melalui tautan resmi berikut:\n` +
            `${baseUrl}ppdb/cetak_kartu/${noDaftar}\n\n` +
            `Harap hadir 15 menit sebelum tes dimulai dengan membawa Kartu Peserta Ujian fisik dan seragam sekolah asal.\n\n`;
    }

    pesan += `Terima kasih.\n*Panitia PMB MAN 3 Banjar*`;

    const waUrl = `https://api.whatsapp.com/send?phone=${noHp}&text=${encodeURIComponent(pesan)}`;
    window.open(waUrl, '_blank');
}

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