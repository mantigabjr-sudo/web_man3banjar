<?php $this->load->view('templates/header'); ?>
<?php $this->load->view('templates/sidebar'); ?>

<div class="content">

<style>
.about-admin-hero{
    background:
        radial-gradient(circle at top right, rgba(34,197,94,.16), transparent 34%),
        linear-gradient(135deg,#ecfdf5,#ffffff);
    border:1px solid #dcfce7;
    border-radius:24px;
    padding:22px;
    box-shadow:0 14px 35px rgba(15,23,42,.06);
    margin-bottom:20px;
}

.about-admin-hero p{
    color:#64748b;
    font-weight:700;
    margin:5px 0 0;
}

.about-card{
    background:#fff;
    border:1px solid #e2e8f0;
    border-radius:24px;
    box-shadow:0 14px 35px rgba(15,23,42,.06);
    overflow:hidden;
}

.about-head{
    padding:18px 20px;
    border-bottom:1px solid #e2e8f0;
}

.about-head h5{
    margin:0;
    color:#14532d;
    font-weight:950;
}

.about-head small{
    display:block;
    color:#64748b;
    font-weight:700;
    margin-top:4px;
}

.about-body{
    padding:22px;
}

.about-grid{
    display:grid;
    grid-template-columns:repeat(2,1fr);
    gap:16px;
}

.about-field{
    margin-bottom:0;
}

.about-field label{
    display:block;
    color:#334155;
    font-size:13px;
    font-weight:850;
    margin-bottom:7px;
}

.about-input,
.about-textarea{
    width:100%;
    border:1px solid #cbd5e1;
    background:#f8fafc;
    border-radius:16px;
    padding:11px 13px;
    color:#0f172a;
    font-weight:700;
    outline:none;
}

.about-input{
    min-height:46px;
}

.about-textarea{
    min-height:190px;
    resize:vertical;
    line-height:1.7;
}

.about-input:focus,
.about-textarea:focus{
    background:white;
    border-color:#22c55e;
    box-shadow:0 0 0 4px rgba(34,197,94,.12);
}

.about-full{
    grid-column:1 / -1;
}

.about-help{
    display:block;
    color:#64748b;
    font-size:12px;
    font-weight:700;
    margin-top:6px;
}

.about-footer{
    padding:18px 20px;
    border-top:1px solid #e2e8f0;
    display:flex;
    justify-content:flex-end;
}

.btn-about-save{
    min-height:46px;
    border:0;
    border-radius:16px;
    padding:0 18px;
    background:linear-gradient(135deg,#15803d,#22c55e);
    color:white;
    font-weight:950;
    box-shadow:0 12px 26px rgba(22,163,74,.22);
}

@media(max-width:768px){
    .about-grid{
        grid-template-columns:1fr;
    }

    .about-admin-hero,
    .about-card{
        border-radius:20px;
    }

    .about-body{
        padding:18px;
    }

    .btn-about-save{
        width:100%;
    }
}
</style>

<div class="about-admin-hero">
    <h2 class="glow mb-1">Tentang Madrasah</h2>
    <p>Kelola sejarah, fasilitas, prestasi, ekstrakurikuler, dan lokasi madrasah pada halaman website.</p>
</div>

<?php if($this->session->flashdata('success')): ?>
    <div class="alert alert-success rounded-4">
        <?= $this->session->flashdata('success') ?>
    </div>
<?php endif; ?>

<form method="post" action="<?= base_url('admin_website/save_tentang') ?>">

    <div class="about-card">
        <div class="about-head">
            <h5>Konten Tentang Madrasah</h5>
            <small>Isi setiap data dengan rapi. Untuk daftar fasilitas/prestasi/ekstrakurikuler, isi satu item per baris.</small>
        </div>

        <div class="about-body">
            <div class="about-grid">

                <div class="about-field about-full">
                    <label>Sejarah / Tentang Madrasah</label>
                    <textarea name="sejarah" class="about-textarea"><?= htmlspecialchars($profil->sejarah ?? '', ENT_QUOTES, 'UTF-8') ?></textarea>
                </div>

                <div class="about-field">
                    <label>Fasilitas</label>
                    <textarea name="fasilitas" class="about-textarea"><?= htmlspecialchars($profil->fasilitas ?? '', ENT_QUOTES, 'UTF-8') ?></textarea>
                    <small class="about-help">Contoh: Laboratorium Komputer, Perpustakaan, Musholla. Isi satu per baris.</small>
                </div>

                <div class="about-field">
                    <label>Prestasi</label>
                    <textarea name="prestasi" class="about-textarea"><?= htmlspecialchars($profil->prestasi ?? '', ENT_QUOTES, 'UTF-8') ?></textarea>
                    <small class="about-help">Isi satu prestasi per baris.</small>
                </div>

                <div class="about-field">
                    <label>Ekstrakurikuler</label>
                    <textarea name="ekstrakurikuler" class="about-textarea"><?= htmlspecialchars($profil->ekstrakurikuler ?? '', ENT_QUOTES, 'UTF-8') ?></textarea>
                    <small class="about-help">Isi satu ekstrakurikuler per baris.</small>
                </div>

                <div class="about-field">
                    <label>Google Maps Embed URL</label>
                    <input type="text"
                           name="maps_embed_url"
                           class="about-input"
                           value="<?= htmlspecialchars($profil->maps_embed_url ?? '', ENT_QUOTES, 'UTF-8') ?>"
                           placeholder="https://www.google.com/maps/embed?...">
                    <small class="about-help">Gunakan link embed dari Google Maps, bukan link biasa.</small>
                </div>

            </div>
        </div>

        <div class="about-footer">
            <button class="btn-about-save">
                Simpan Tentang Madrasah
            </button>
        </div>
    </div>

</form>

</div>

<?php $this->load->view('templates/footer'); ?>