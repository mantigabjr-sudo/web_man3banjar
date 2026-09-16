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

    <div class="page-header">
        <div class="page-title-group">
            <span class="page-category">Kelola Website</span>
            <h1 class="page-title">PTK Website</h1>
            <p class="page-subtitle">Atur guru & tenaga kependidikan yang tampil pada direktori website madrasah.</p>
        </div>
        <div class="header-actions">
            <a href="<?= base_url('admin_ptk') ?>" class="btn-modern btn-modern-light">
                <i class="fa fa-users me-1"></i> Data Master PTK
            </a>
        </div>
    </div>

    <?php if($this->session->flashdata('success')): ?>
        <div class="alert alert-success d-flex align-items-center mb-4" role="alert">
            <i class="fa fa-check-circle me-2 fs-5"></i>
            <div><?= $this->session->flashdata('success') ?></div>
        </div>
    <?php endif; ?>

    <?php if($this->session->flashdata('error')): ?>
        <div class="alert alert-danger d-flex align-items-center mb-4" role="alert">
            <i class="fa fa-exclamation-circle me-2 fs-5"></i>
            <div><?= $this->session->flashdata('error') ?></div>
        </div>
    <?php endif; ?>

    <!-- Metrics Strip -->
    <div class="row g-3 mb-4">
        <div class="col-md-4">
            <div class="stat-card">
                <div class="d-flex justify-content-between align-items-center">
                    <span class="stat-title">Total Database PTK</span>
                    <div class="stat-icon-wrapper" style="background:#eff6ff; color:#2563eb;">
                        <i class="fa fa-users"></i>
                    </div>
                </div>
                <div class="stat-value"><?= (int)$total_ptk ?></div>
                <div class="stat-desc text-muted">Guru & Staf terdaftar</div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="stat-card">
                <div class="d-flex justify-content-between align-items-center">
                    <span class="stat-title">Tampil di Website</span>
                    <div class="stat-icon-wrapper" style="background:#ecfdf5; color:#059669;">
                        <i class="fa fa-eye"></i>
                    </div>
                </div>
                <div class="stat-value text-success"><?= (int)$total_tampil ?></div>
                <div class="stat-desc text-muted">Dapat dilihat publik</div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="stat-card">
                <div class="d-flex justify-content-between align-items-center">
                    <span class="stat-title">Disembunyikan</span>
                    <div class="stat-icon-wrapper" style="background:#fef2f2; color:#dc2626;">
                        <i class="fa fa-eye-slash"></i>
                    </div>
                </div>
                <div class="stat-value text-muted"><?= (int)$total_sembunyi ?></div>
                <div class="stat-desc text-muted">Tidak dipublikasikan</div>
            </div>
        </div>
    </div>

    <form method="post" action="<?= base_url('admin_website/save_ptk_urutan') ?>">
        <div class="modern-card">
            <div class="modern-card-header d-flex flex-wrap justify-content-between align-items-center gap-3">
                <div>
                    <h2 class="modern-card-title">Daftar Urutan & Visibilitas PTK</h2>
                    <p class="modern-card-subtitle">Urutan dengan angka terkecil akan diprioritaskan tampil di posisi paling atas.</p>
                </div>
                <button type="submit" class="btn-modern btn-modern-primary">
                    <i class="fa fa-save me-1"></i> Simpan Urutan Tampil
                </button>
            </div>

            <div class="modern-card-body p-0">
                <div class="table-responsive">
                    <table class="table table-modern datatable align-middle mb-0" style="width:100%">
                        <thead>
                            <tr>
                                <th style="width:70px;" class="ps-4">Foto</th>
                                <th>Nama Lengkap & NIP</th>
                                <th>Jabatan & Tugas</th>
                                <th style="width:130px;">Status Website</th>
                                <th style="width:110px;">No. Urut</th>
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
                                                 alt="Foto <?= htmlspecialchars($p->nama_lengkap ?? '', ENT_QUOTES, 'UTF-8') ?>"
                                                 style="width:46px; height:46px; object-fit:cover; border-radius:12px; border:1px solid #e2e8f0;">
                                        <?php else: ?>
                                            <div style="width:46px; height:46px; border-radius:12px; background:#f1f5f9; color:#475569; display:flex; align-items:center; justify-content:center; font-weight:700; font-size:15px; border:1px solid #e2e8f0;">
                                                <?= !empty($p->nama_lengkap) ? strtoupper(substr($p->nama_lengkap,0,1)) : 'P' ?>
                                            </div>
                                        <?php endif; ?>
                                    </td>

                                    <td>
                                        <div style="font-weight:700; color:#0f172a; font-size:14px;">
                                            <?= htmlspecialchars($p->nama_lengkap ?? '-', ENT_QUOTES, 'UTF-8') ?>
                                        </div>
                                        <div style="font-size:12px; color:#64748b; margin-top:2px;">
                                            NIP: <?= !empty($p->nip) ? $p->nip : '<span class="text-muted">-</span>' ?>
                                        </div>
                                    </td>

                                    <td>
                                        <div style="font-weight:600; color:#334155; font-size:13px;">
                                            <?= !empty($p->jabatan) ? htmlspecialchars($p->jabatan, ENT_QUOTES, 'UTF-8') : '-' ?>
                                        </div>
                                        <div style="font-size:12px; color:#64748b; margin-top:2px;">
                                            <?= !empty($p->mapel_utama) ? htmlspecialchars($p->mapel_utama, ENT_QUOTES, 'UTF-8') : (!empty($p->tugas_utama) ? htmlspecialchars($p->tugas_utama, ENT_QUOTES, 'UTF-8') : '<span class="text-muted">-</span>') ?>
                                        </div>
                                    </td>

                                    <td>
                                        <?php if(!empty($p->tampil_website)): ?>
                                            <span class="pill-status pill-success">
                                                <i class="fa fa-eye me-1" style="font-size:10px;"></i> Tampil
                                            </span>
                                        <?php else: ?>
                                            <span class="pill-status pill-muted">
                                                <i class="fa fa-eye-slash me-1" style="font-size:10px;"></i> Sembunyi
                                            </span>
                                        <?php endif; ?>
                                    </td>

                                    <td>
                                        <input type="number"
                                               name="urutan[<?= $p->id ?>]"
                                               class="input-modern text-center py-1 px-2"
                                               style="width:75px; font-weight:700;"
                                               value="<?= (int)($p->urutan_website ?? 0) ?>">
                                    </td>

                                    <td class="text-end pe-4">
                                        <div class="d-inline-flex gap-1">
                                            <a href="<?= base_url('admin_ptk/detail/'.$p->id) ?>"
                                               class="btn-modern btn-modern-light py-1 px-2 text-decoration-none"
                                               style="font-size:12px;"
                                               title="Lihat Detail Profil PTK">
                                                <i class="fa fa-user"></i>
                                            </a>

                                            <?php if(!empty($p->tampil_website)): ?>
                                                <a href="<?= base_url('admin_website/hide_ptk/'.$p->id) ?>"
                                                   class="btn-modern btn-modern-light py-1 px-2 text-warning text-decoration-none"
                                                   style="font-size:12px;"
                                                   onclick="return confirm('Sembunyikan PTK ini dari website?')"
                                                   title="Sembunyikan">
                                                    <i class="fa fa-eye-slash"></i>
                                                </a>
                                            <?php else: ?>
                                                <a href="<?= base_url('admin_website/show_ptk/'.$p->id) ?>"
                                                   class="btn-modern btn-modern-light py-1 px-2 text-success text-decoration-none"
                                                   style="font-size:12px;"
                                                   onclick="return confirm('Tampilkan PTK ini di website?')"
                                                   title="Tampilkan">
                                                    <i class="fa fa-eye"></i>
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
            <div class="p-3 bg-light border-top d-flex justify-content-end">
                <button type="submit" class="btn-modern btn-modern-primary">
                    <i class="fa fa-save me-1"></i> Simpan Urutan Tampil
                </button>
            </div>
        </div>
    </form>

</div>

<?php $this->load->view('templates/footer'); ?>