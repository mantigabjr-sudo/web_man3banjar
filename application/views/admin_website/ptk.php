<?php $this->load->view('templates/header'); ?>
<?php $this->load->view('templates/sidebar'); ?>

<?php
$total_ptk = !empty($ptk) ? count($ptk) : 0;
$total_tampil = 0;

if(!empty($ptk)){
    foreach($ptk as $p){
        if(!empty($p->tampil_website)){
            $total_tampil++;
        }
    }
}

$total_sembunyi = $total_ptk - $total_tampil;
?>

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
                            <i class="bi bi-people-fill me-1"></i> DIREKTORI GURU &amp; TENAGA KEPENDIDIKAN
                        </span>
                        <h2 class="fw-bold mb-2 text-white">Direktori PTK Website</h2>
                        <p class="mb-3 text-white-50" style="font-size: 14px; max-width: 620px;">
                            Atur guru dan tenaga kependidikan yang ditampilkan pada direktori website madrasah. Tentukan nomor urutan untuk mengatur posisi tampilan personil.
                        </p>
                        <div class="d-flex flex-wrap gap-2 pt-1">
                            <a href="<?= base_url('admin_ptk') ?>" class="btn btn-light text-dark fw-bold rounded-pill px-3 shadow-sm">
                                <i class="bi bi-person-lines-fill text-success me-1"></i> Master Data PTK
                            </a>
                            <a href="<?= base_url('ptk') ?>" target="_blank" class="btn btn-outline-light fw-bold rounded-pill px-3 shadow-sm">
                                <i class="bi bi-box-arrow-up-right me-1"></i> Pratinjau di Website
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Hiasan Bulat Transparan -->
            <div style="position: absolute; right: -40px; top: -40px; width: 180px; height: 180px; background: radial-gradient(circle, rgba(255,255,255,0.2) 0%, transparent 70%); border-radius: 50%;"></div>
        </div>

        <!-- ═══ STATISTIK RINGKASAN (BORDER-START 4PX) ═══ -->
        <div class="row g-3 mb-4">
            <div class="col-md-4">
                <div class="card border-0 rounded-4 shadow-sm h-100 p-3 bg-white border-start border-4 border-primary">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <span class="text-muted small fw-bold text-uppercase">Total Database PTK</span>
                            <h3 class="fw-bold text-dark mb-0 mt-1"><?= (int)$total_ptk ?></h3>
                            <small class="text-muted">Guru &amp; Staf terdaftar</small>
                        </div>
                        <div class="p-3 bg-primary-subtle text-primary rounded-4 fs-3">
                            <i class="bi bi-people-fill"></i>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card border-0 rounded-4 shadow-sm h-100 p-3 bg-white border-start border-4 border-success">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <span class="text-muted small fw-bold text-uppercase">Tampil di Website</span>
                            <h3 class="fw-bold text-success mb-0 mt-1"><?= (int)$total_tampil ?></h3>
                            <small class="text-success fw-semibold"><?= $total_ptk > 0 ? round(($total_tampil / $total_ptk) * 100) : 0 ?>% dipublikasikan</small>
                        </div>
                        <div class="p-3 bg-success-subtle text-success rounded-4 fs-3">
                            <i class="bi bi-eye-fill"></i>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card border-0 rounded-4 shadow-sm h-100 p-3 bg-white border-start border-4 border-warning">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <span class="text-muted small fw-bold text-uppercase">Disembunyikan</span>
                            <h3 class="fw-bold text-warning mb-0 mt-1"><?= (int)$total_sembunyi ?></h3>
                            <small class="text-muted">Tidak tampil ke publik</small>
                        </div>
                        <div class="p-3 bg-warning-subtle text-warning rounded-4 fs-3">
                            <i class="bi bi-eye-slash-fill"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- ═══ TABEL PTK DENGAN SIMPAN URUTAN ═══ -->
        <form method="post" action="<?= base_url('admin_website/save_ptk_urutan') ?>">
            <div class="card border-0 rounded-4 shadow-sm mb-4">
                <div class="card-header bg-white border-bottom pt-3 pb-2 px-4 d-flex justify-content-between align-items-center flex-wrap gap-2">
                    <div>
                        <h6 class="fw-bold mb-0 text-dark"><i class="bi bi-list-ol text-success me-2"></i> Daftar Urutan &amp; Visibilitas PTK</h6>
                        <small class="text-muted">Angka urutan lebih kecil diprioritaskan tampil di posisi paling atas.</small>
                    </div>
                    <button type="submit" class="btn btn-success fw-bold rounded-pill px-4 py-2 shadow-sm">
                        <i class="bi bi-check-circle-fill me-1"></i> Simpan Urutan
                    </button>
                </div>

                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle datatable mb-0" style="width:100%">
                            <thead class="bg-light">
                                <tr>
                                    <th style="width:70px;" class="ps-4">Foto</th>
                                    <th>Nama Lengkap &amp; NIP</th>
                                    <th>Jabatan &amp; Tugas</th>
                                    <th style="width:120px;" class="text-center">Status</th>
                                    <th style="width:100px;" class="text-center">No. Urut</th>
                                    <th style="width:160px;" class="text-end pe-4">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach($ptk as $p): ?>
                                    <?php
                                    $foto_file = !empty($p->foto) ? FCPATH.'uploads/ptk/foto/'.$p->foto : '';
                                    ?>
                                    <tr>
                                        <td class="ps-4">
                                            <?php if(!empty($p->foto) && file_exists($foto_file)): ?>
                                                <img src="<?= base_url('uploads/ptk/foto/'.$p->foto) ?>" 
                                                     alt="Foto" 
                                                     class="rounded-3 shadow-sm"
                                                     style="width:48px; height:48px; object-fit:cover; border:1px solid #e2e8f0;">
                                            <?php else: ?>
                                                <div class="rounded-3 bg-success-subtle text-success fw-bold d-flex align-items-center justify-content-center shadow-sm" style="width:48px; height:48px; font-size:16px;">
                                                    <?= !empty($p->nama_lengkap) ? strtoupper(substr($p->nama_lengkap,0,1)) : 'P' ?>
                                                </div>
                                            <?php endif; ?>
                                        </td>

                                        <td>
                                            <div class="fw-bold text-dark" style="font-size:14px;"><?= htmlspecialchars($p->nama_lengkap ?? '-', ENT_QUOTES, 'UTF-8') ?></div>
                                            <div class="small text-muted">NIP: <?= !empty($p->nip) ? $p->nip : '-' ?></div>
                                        </td>

                                        <td>
                                            <div class="fw-semibold text-dark small"><?= !empty($p->jabatan) ? htmlspecialchars($p->jabatan, ENT_QUOTES, 'UTF-8') : '-' ?></div>
                                            <div class="small text-muted"><?= !empty($p->mapel_utama) ? htmlspecialchars($p->mapel_utama, ENT_QUOTES, 'UTF-8') : (!empty($p->tugas_utama) ? htmlspecialchars($p->tugas_utama, ENT_QUOTES, 'UTF-8') : '') ?></div>
                                        </td>

                                        <td class="text-center">
                                            <?php if(!empty($p->tampil_website)): ?>
                                                <span class="badge bg-success-subtle text-success fw-bold rounded-pill px-3 py-1">Tampil</span>
                                            <?php else: ?>
                                                <span class="badge bg-secondary-subtle text-secondary fw-bold rounded-pill px-3 py-1">Sembunyi</span>
                                            <?php endif; ?>
                                        </td>

                                        <td class="text-center">
                                            <input type="number"
                                                   name="urutan[<?= $p->id ?>]"
                                                   class="form-control form-control-sm text-center fw-bold rounded-3 mx-auto"
                                                   style="width:75px;"
                                                   value="<?= (int)($p->urutan_website ?? 0) ?>">
                                        </td>

                                        <td class="text-end pe-4">
                                            <div class="d-inline-flex gap-1">
                                                <a href="<?= base_url('admin_ptk/detail/'.$p->id) ?>" class="btn btn-sm btn-light rounded-pill px-2 py-1 text-primary shadow-sm" title="Profil">
                                                    <i class="bi bi-person-fill"></i>
                                                </a>

                                                <?php if(!empty($p->tampil_website)): ?>
                                                    <a href="<?= base_url('admin_website/hide_ptk/'.$p->id) ?>" 
                                                       class="btn btn-sm btn-light rounded-pill px-2 py-1 text-warning shadow-sm"
                                                       onclick="return confirm('Sembunyikan PTK ini dari website?')"
                                                       title="Sembunyikan">
                                                        <i class="bi bi-eye-slash-fill"></i>
                                                    </a>
                                                <?php else: ?>
                                                    <a href="<?= base_url('admin_website/show_ptk/'.$p->id) ?>" 
                                                       class="btn btn-sm btn-light rounded-pill px-2 py-1 text-success shadow-sm"
                                                       onclick="return confirm('Tampilkan PTK ini di website?')"
                                                       title="Tampilkan">
                                                        <i class="bi bi-eye-fill"></i>
                                                    </a>
                                                <?php endif; ?>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="card-footer bg-light border-top p-3 d-flex justify-content-end">
                    <button type="submit" class="btn btn-success fw-bold rounded-pill px-4 py-2 shadow-sm">
                        <i class="bi bi-check-circle-fill me-1"></i> Simpan Urutan
                    </button>
                </div>
            </div>
        </form>

    </div>
</div>

<?php $this->load->view('templates/footer'); ?>