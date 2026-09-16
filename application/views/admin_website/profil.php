<?php $this->load->view('templates/header'); ?>
<?php $this->load->view('templates/sidebar'); ?>

<div class="content">

<style>
.webset-hero{
    background:
        radial-gradient(circle at top right, rgba(34,197,94,.16), transparent 34%),
        linear-gradient(135deg,#ecfdf5,#ffffff);
    border:1px solid #dcfce7;
    border-radius:24px;
    padding:22px;
    box-shadow:0 14px 35px rgba(15,23,42,.06);
    margin-bottom:20px;
}

.webset-hero p{
    color:#64748b;
    font-weight:700;
    margin:5px 0 0;
}

.webset-card{
    background:#fff;
    border:1px solid #e2e8f0;
    border-radius:24px;
    box-shadow:0 14px 35px rgba(15,23,42,.06);
    overflow:hidden;
}

.webset-head{
    padding:18px 20px;
    border-bottom:1px solid #e2e8f0;
}

.webset-head h5{
    margin:0;
    color:#14532d;
    font-weight:950;
}

.webset-head small{
    display:block;
    color:#64748b;
    font-weight:700;
    margin-top:4px;
}

.webset-body{
    padding:22px;
}

.webset-grid{
    display:grid;
    grid-template-columns:repeat(2,1fr);
    gap:16px;
}

.webset-field label{
    display:block;
    color:#334155;
    font-size:13px;
    font-weight:850;
    margin-bottom:7px;
}

.webset-input,
.webset-textarea{
    width:100%;
    border:1px solid #cbd5e1;
    background:#f8fafc;
    border-radius:16px;
    padding:11px 13px;
    color:#0f172a;
    font-weight:700;
    outline:none;
}

.webset-input{
    min-height:46px;
}

.webset-textarea{
    min-height:150px;
    resize:vertical;
    line-height:1.7;
}

.webset-input:focus,
.webset-textarea:focus{
    background:white;
    border-color:#22c55e;
    box-shadow:0 0 0 4px rgba(34,197,94,.12);
}

.webset-full{
    grid-column:1 / -1;
}

.webset-footer{
    padding:18px 20px;
    border-top:1px solid #e2e8f0;
    display:flex;
    justify-content:flex-end;
}

.btn-webset-save{
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
    .webset-grid{
        grid-template-columns:1fr;
    }

    .webset-hero,
    .webset-card{
        border-radius:20px;
    }

    .webset-body{
        padding:18px;
    }

    .btn-webset-save{
        width:100%;
    }
}
</style>

<div class="webset-hero">
    <div class="d-flex justify-content-between align-items-center gap-3 flex-wrap">
        <div>
            <h2 class="glow mb-1">Profil Website</h2>
            <p>Kelola profil singkat, visi, misi, tujuan, dan kontak madrasah yang tampil di halaman website.</p>
        </div>
        <div class="d-flex gap-2 flex-wrap">
            <a href="<?= base_url('admin_cloud_sync/sync_website') ?>" class="btn btn-outline-success fw-bold d-inline-flex align-items-center rounded-4 px-3" style="border-width:2px; font-weight:800;" onclick="return confirm('Kirim seluruh profil website lokal ke man3banjar.sch.id?');">
                🚀 Kirim ke Website Online
            </a>
            <a href="<?= base_url('admin_cloud_sync/pull_website') ?>" class="btn btn-outline-primary fw-bold d-inline-flex align-items-center rounded-4 px-3" style="border-width:2px; font-weight:800;" onclick="return confirm('Tarik data profil & konten website terbaru dari man3banjar.sch.id ke lokal?');">
                📥 Tarik dari Online
            </a>
        </div>
    </div>
</div>

<?php if($this->session->flashdata('success')): ?>
    <div class="alert alert-success rounded-4">
        <?= $this->session->flashdata('success') ?>
    </div>
<?php endif; ?>

<form method="post" action="<?= base_url('admin_website/save_profil') ?>">

    <div class="webset-card">
        <div class="webset-head">
            <h5>Data Profil Website</h5>
            <small>Konten ini akan ditampilkan pada halaman depan website.</small>
        </div>

        <div class="webset-body">
            <div class="webset-grid">

                <div class="webset-field webset-full">
                    <label>Judul Profil</label>
                    <input type="text"
                           name="judul_profil"
                           class="webset-input"
                           value="<?= htmlspecialchars($profil->judul_profil ?? '', ENT_QUOTES, 'UTF-8') ?>">
                </div>

                <div class="webset-field webset-full">
                    <label>Isi Profil Singkat</label>
                    <textarea name="isi_profil" class="webset-textarea"><?= htmlspecialchars($profil->isi_profil ?? '', ENT_QUOTES, 'UTF-8') ?></textarea>
                </div>

                <div class="webset-field">
                    <label>Visi</label>
                    <textarea name="visi" class="webset-textarea"><?= htmlspecialchars($profil->visi ?? '', ENT_QUOTES, 'UTF-8') ?></textarea>
                </div>

                <div class="webset-field">
                    <label>Misi</label>
                    <textarea name="misi" class="webset-textarea"><?= htmlspecialchars($profil->misi ?? '', ENT_QUOTES, 'UTF-8') ?></textarea>
                </div>

                <div class="webset-field webset-full">
                    <label>Tujuan</label>
                    <textarea name="tujuan" class="webset-textarea"><?= htmlspecialchars($profil->tujuan ?? '', ENT_QUOTES, 'UTF-8') ?></textarea>
                </div>

                <div class="webset-field webset-full">
                    <label>Alamat</label>
                    <textarea name="alamat" class="webset-textarea"><?= htmlspecialchars($profil->alamat ?? '', ENT_QUOTES, 'UTF-8') ?></textarea>
                </div>

                <div class="webset-field">
                    <label>Telepon</label>
                    <input type="text"
                           name="telepon"
                           class="webset-input"
                           value="<?= htmlspecialchars($profil->telepon ?? '', ENT_QUOTES, 'UTF-8') ?>">
                </div>

                <div class="webset-field">
                    <label>Email</label>
                    <input type="email"
                           name="email"
                           class="webset-input"
                           value="<?= htmlspecialchars($profil->email ?? '', ENT_QUOTES, 'UTF-8') ?>">
                </div>
				<div class="webset-field">
					<label>WhatsApp</label>
					<input type="text"
						   name="whatsapp"
						   class="webset-input"
						   placeholder="Contoh: 08123456789"
						   value="<?= htmlspecialchars($profil->whatsapp ?? '', ENT_QUOTES, 'UTF-8') ?>">
				</div>

				<div class="webset-field">
					<label>Jam Layanan</label>
					<input type="text"
						   name="jam_layanan"
						   class="webset-input"
						   placeholder="Contoh: Senin - Jumat, 08.00 - 14.00 WITA"
						   value="<?= htmlspecialchars($profil->jam_layanan ?? '', ENT_QUOTES, 'UTF-8') ?>">
				</div>

				<div class="webset-field">
					<label>Facebook URL</label>
					<input type="text"
						   name="facebook_url"
						   class="webset-input"
						   placeholder="https://facebook.com/..."
						   value="<?= htmlspecialchars($profil->facebook_url ?? '', ENT_QUOTES, 'UTF-8') ?>">
				</div>

				<div class="webset-field">
					<label>Instagram URL</label>
					<input type="text"
						   name="instagram_url"
						   class="webset-input"
						   placeholder="https://instagram.com/..."
						   value="<?= htmlspecialchars($profil->instagram_url ?? '', ENT_QUOTES, 'UTF-8') ?>">
				</div>

				<div class="webset-field webset-full">
					<label>YouTube URL</label>
					<input type="text"
						   name="youtube_url"
						   class="webset-input"
						   placeholder="https://youtube.com/..."
						   value="<?= htmlspecialchars($profil->youtube_url ?? '', ENT_QUOTES, 'UTF-8') ?>">
				</div>

            </div>
        </div>

        <div class="webset-footer">
            <button class="btn-webset-save">
                Simpan Profil Website
            </button>
        </div>
    </div>

</form>

</div>

<?php $this->load->view('templates/footer'); ?>