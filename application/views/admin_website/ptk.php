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

<style>
.webptk-hero{
    background:
        radial-gradient(circle at top right, rgba(34,197,94,.16), transparent 34%),
        linear-gradient(135deg,#ecfdf5,#ffffff);
    border:1px solid #dcfce7;
    border-radius:24px;
    padding:22px;
    box-shadow:0 14px 35px rgba(15,23,42,.06);
    margin-bottom:20px;
}

.webptk-hero p{
    color:#64748b;
    font-weight:700;
    margin:5px 0 0;
}

.webptk-summary{
    display:grid;
    grid-template-columns:repeat(3,1fr);
    gap:14px;
    margin-bottom:20px;
}

.webptk-stat{
    background:#ffffff;
    border:1px solid #e2e8f0;
    border-radius:22px;
    padding:18px;
    box-shadow:0 12px 30px rgba(15,23,42,.05);
}

.webptk-stat small{
    display:block;
    color:#64748b;
    font-size:13px;
    font-weight:850;
}

.webptk-stat strong{
    display:block;
    color:#16a34a;
    font-size:32px;
    line-height:1;
    font-weight:950;
    margin-top:7px;
}

.webptk-card{
    background:#fff;
    border:1px solid #e2e8f0;
    border-radius:24px;
    box-shadow:0 14px 35px rgba(15,23,42,.06);
    overflow:hidden;
}

.webptk-head{
    padding:18px 20px;
    border-bottom:1px solid #e2e8f0;
    display:flex;
    justify-content:space-between;
    align-items:center;
    gap:12px;
    flex-wrap:wrap;
}

.webptk-head h5{
    margin:0;
    color:#14532d;
    font-weight:950;
}

.webptk-head small{
    display:block;
    color:#64748b;
    font-weight:700;
    margin-top:4px;
}

.webptk-save{
    min-height:42px;
    border:0;
    border-radius:15px;
    padding:0 16px;
    background:linear-gradient(135deg,#15803d,#22c55e);
    color:white;
    font-weight:950;
    box-shadow:0 12px 26px rgba(22,163,74,.20);
}

.webptk-photo{
    width:54px;
    height:64px;
    object-fit:cover;
    border-radius:16px;
    border:1px solid #e2e8f0;
    background:#ecfdf5;
}

.webptk-avatar{
    width:54px;
    height:64px;
    border-radius:16px;
    background:#dcfce7;
    color:#166534;
    display:flex;
    align-items:center;
    justify-content:center;
    font-weight:950;
}

.webptk-name{
    color:#0f172a;
    font-weight:950;
    line-height:1.3;
}

.webptk-sub{
    color:#64748b;
    font-size:12px;
    font-weight:700;
    margin-top:4px;
}

.webptk-order{
    width:85px;
    min-height:38px;
    border:1px solid #cbd5e1;
    border-radius:13px;
    background:#f8fafc;
    padding:7px 10px;
    font-weight:900;
    outline:none;
}

.webptk-order:focus{
    background:#fff;
    border-color:#22c55e;
    box-shadow:0 0 0 4px rgba(34,197,94,.12);
}

.status-pill{
    display:inline-flex;
    padding:7px 11px;
    border-radius:999px;
    font-size:12px;
    font-weight:950;
    white-space:nowrap;
}

.status-show{
    background:#dcfce7;
    color:#166534;
}

.status-hide{
    background:#fee2e2;
    color:#991b1b;
}

.webptk-actions{
    display:flex;
    gap:7px;
    flex-wrap:wrap;
}

.webptk-action{
    display:inline-flex;
    min-height:34px;
    padding:0 11px;
    border-radius:12px;
    align-items:center;
    justify-content:center;
    font-size:12px;
    font-weight:950;
    text-decoration:none;
}

.webptk-action-show{
    background:#dcfce7;
    color:#166534;
}

.webptk-action-hide{
    background:#fee2e2;
    color:#991b1b;
}

.webptk-action-detail{
    background:#e0f2fe;
    color:#075985;
}

.webptk-action-show:hover{color:#166534;}
.webptk-action-hide:hover{color:#991b1b;}
.webptk-action-detail:hover{color:#075985;}

.dataTables_wrapper{
    padding:18px 20px 20px;
}

@media(max-width:768px){
    .webptk-summary{
        grid-template-columns:1fr;
    }

    .webptk-hero,
    .webptk-card{
        border-radius:20px;
    }

    .webptk-actions{
        display:grid;
        grid-template-columns:1fr;
    }

    .webptk-action{
        width:100%;
    }

    .webptk-save{
        width:100%;
    }
}
</style>

<div class="webptk-hero">
    <h2 class="glow mb-1">PTK Website</h2>
    <p>Atur PTK yang tampil di halaman website madrasah beserta urutan tampilnya.</p>
</div>

<?php if($this->session->flashdata('success')): ?>
    <div class="alert alert-success rounded-4">
        <?= $this->session->flashdata('success') ?>
    </div>
<?php endif; ?>

<?php if($this->session->flashdata('error')): ?>
    <div class="alert alert-danger rounded-4">
        <?= $this->session->flashdata('error') ?>
    </div>
<?php endif; ?>

<div class="webptk-summary">
    <div class="webptk-stat">
        <small>Total PTK</small>
        <strong><?= (int)$total_ptk ?></strong>
    </div>

    <div class="webptk-stat">
        <small>Tampil Website</small>
        <strong><?= (int)$total_tampil ?></strong>
    </div>

    <div class="webptk-stat">
        <small>Disembunyikan</small>
        <strong><?= (int)$total_sembunyi ?></strong>
    </div>
</div>

<form method="post" action="<?= base_url('admin_website/save_ptk_urutan') ?>">

    <div class="webptk-card">
        <div class="webptk-head">
            <div>
                <h5>Daftar PTK</h5>
                <small>Isi angka urutan, lalu klik Simpan Urutan. Angka kecil tampil lebih dulu.</small>
            </div>

            <button class="webptk-save">
                Simpan Urutan
            </button>
        </div>

        <div class="table-responsive">
            <table class="table table-bordered table-striped datatable nowrap" style="width:100%">
                <thead class="table-success">
                    <tr>
                        <th style="width:80px;">Foto</th>
                        <th>Nama</th>
                        <th>Jabatan</th>
                        <th>Status PTK</th>
                        <th>Status Website</th>
                        <th style="width:110px;">Urutan</th>
                        <th style="width:210px;">Aksi</th>
                    </tr>
                </thead>

                <tbody>
                    <?php foreach($ptk as $p): ?>
                        <?php
                        $foto_file = !empty($p->foto) ? FCPATH.'uploads/ptk/foto/'.$p->foto : '';
                        ?>
                        <tr>
                            <td>
                                <?php if(!empty($p->foto) && file_exists($foto_file)): ?>
                                    <img src="<?= base_url('uploads/ptk/foto/'.$p->foto) ?>" class="webptk-photo">
                                <?php else: ?>
                                    <div class="webptk-avatar">
                                        <?= !empty($p->nama_lengkap) ? strtoupper(substr($p->nama_lengkap,0,1)) : 'P' ?>
                                    </div>
                                <?php endif; ?>
                            </td>

                            <td>
                                <div class="webptk-name">
                                    <?= htmlspecialchars($p->nama_lengkap ?? '-', ENT_QUOTES, 'UTF-8') ?>
                                </div>
                                <div class="webptk-sub">
                                    NIP: <?= !empty($p->nip) ? $p->nip : '-' ?>
                                </div>
                            </td>

                            <td>
                                <div class="webptk-name">
                                    <?= !empty($p->jabatan) ? htmlspecialchars($p->jabatan, ENT_QUOTES, 'UTF-8') : '-' ?>
                                </div>
                                <div class="webptk-sub">
                                    <?= !empty($p->mapel_utama) ? htmlspecialchars($p->mapel_utama, ENT_QUOTES, 'UTF-8') : (!empty($p->tugas_utama) ? htmlspecialchars($p->tugas_utama, ENT_QUOTES, 'UTF-8') : '') ?>
                                </div>
                            </td>

                            <td>
                                <?= !empty($p->status_aktif) ? $p->status_aktif : '-' ?>
                            </td>

                            <td>
                                <?php if(!empty($p->tampil_website)): ?>
                                    <span class="status-pill status-show">Tampil</span>
                                <?php else: ?>
                                    <span class="status-pill status-hide">Sembunyi</span>
                                <?php endif; ?>
                            </td>

                            <td>
                                <input type="number"
                                       name="urutan[<?= $p->id ?>]"
                                       class="webptk-order"
                                       value="<?= (int)($p->urutan_website ?? 0) ?>">
                            </td>

                            <td>
                                <div class="webptk-actions">
                                    <a href="<?= base_url('admin_ptk/detail/'.$p->id) ?>"
                                       class="webptk-action webptk-action-detail">
                                        Detail
                                    </a>

                                    <?php if(!empty($p->tampil_website)): ?>
                                        <a href="<?= base_url('admin_website/hide_ptk/'.$p->id) ?>"
                                           class="webptk-action webptk-action-hide"
                                           onclick="return confirm('Sembunyikan PTK ini dari website?')">
                                            Sembunyi
                                        </a>
                                    <?php else: ?>
                                        <a href="<?= base_url('admin_website/show_ptk/'.$p->id) ?>"
                                           class="webptk-action webptk-action-show"
                                           onclick="return confirm('Tampilkan PTK ini di website?')">
                                            Tampilkan
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

</form>

</div>

<?php $this->load->view('templates/footer'); ?>