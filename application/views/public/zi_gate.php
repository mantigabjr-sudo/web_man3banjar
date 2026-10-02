<?php
$page_title = 'Gerbang Akses Eviden Zona Integritas (WBK/WBBM)';
$this->load->view('public/partials/archive_header');
?>

<style>
.zi-gate-wrapper {
    min-height: 80vh;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 40px 15px 70px 15px;
    background: radial-gradient(circle at 10% 20%, rgba(16, 185, 129, 0.08) 0%, transparent 40%),
                radial-gradient(circle at 90% 80%, rgba(245, 158, 11, 0.08) 0%, transparent 40%),
                #f8fafc;
}

.zi-gate-card {
    max-width: 480px;
    width: 100%;
    background: #ffffff;
    border-radius: 24px;
    box-shadow: 0 20px 45px -15px rgba(6, 78, 59, 0.15), 0 0 0 1px rgba(226, 232, 240, 0.9);
    overflow: hidden;
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}

.zi-gate-header {
    background: linear-gradient(135deg, #064e3b 0%, #059669 65%, #10b981 100%);
    padding: 35px 30px 30px 30px;
    text-align: center;
    color: #ffffff;
    position: relative;
}

.zi-gate-iconbox {
    width: 76px;
    height: 76px;
    border-radius: 20px;
    background: rgba(255, 255, 255, 0.15);
    backdrop-filter: blur(10px);
    display: inline-flex;
    align-items: center;
    justify-content: center;
    font-size: 36px;
    color: #fde047;
    border: 2px solid rgba(255, 255, 255, 0.25);
    margin-bottom: 16px;
    box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
}

.zi-gate-badge {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    background: rgba(255, 255, 255, 0.2);
    border: 1px solid rgba(255, 255, 255, 0.35);
    color: #ffffff;
    font-size: 11px;
    font-weight: 700;
    letter-spacing: 0.5px;
    padding: 5px 14px;
    border-radius: 999px;
    text-transform: uppercase;
    margin-bottom: 12px;
}

.zi-gate-body {
    padding: 35px 30px 30px 30px;
}

.zi-pin-input-group {
    position: relative;
}

.zi-pin-input {
    font-size: 22px;
    letter-spacing: 6px;
    text-align: center;
    font-weight: 800;
    padding: 14px 45px 14px 45px;
    border-radius: 14px;
    border: 2px solid #e2e8f0;
    transition: all 0.2s ease;
    color: #064e3b;
    background-color: #f8fafc;
}

.zi-pin-input:focus {
    border-color: #059669;
    background-color: #ffffff;
    box-shadow: 0 0 0 4px rgba(5, 150, 105, 0.15);
    outline: none;
}

.zi-pin-input::placeholder {
    font-size: 14px;
    letter-spacing: 0;
    font-weight: 500;
    color: #94a3b8;
}

.btn-toggle-pin {
    position: absolute;
    right: 14px;
    top: 50%;
    transform: translateY(-50%);
    background: none;
    border: none;
    color: #64748b;
    font-size: 18px;
    cursor: pointer;
    padding: 4px;
    z-index: 5;
}

.btn-toggle-pin:hover {
    color: #059669;
}

.zi-btn-submit {
    background: linear-gradient(135deg, #059669 0%, #047857 100%);
    color: #ffffff;
    font-weight: 700;
    font-size: 15px;
    padding: 13px 20px;
    border-radius: 14px;
    border: none;
    width: 100%;
    transition: all 0.25s ease;
    box-shadow: 0 8px 20px -4px rgba(5, 150, 105, 0.4);
}

.zi-btn-submit:hover {
    background: linear-gradient(135deg, #047857 0%, #064e3b 100%);
    color: #ffffff;
    transform: translateY(-1px);
    box-shadow: 0 12px 25px -4px rgba(5, 150, 105, 0.5);
}

.zi-gate-footer-note {
    background-color: #f8fafc;
    border-top: 1px solid #f1f5f9;
    padding: 18px 25px;
    font-size: 12.5px;
    color: #64748b;
    text-align: center;
    border-radius: 0 0 24px 24px;
}
</style>

<div class="zi-gate-wrapper">
    <div class="zi-gate-card">
        
        <!-- Header Banner -->
        <div class="zi-gate-header">
            <div class="zi-gate-iconbox">
                <i class="bi bi-shield-lock-fill"></i>
            </div>
            <div>
                <span class="zi-gate-badge">
                    <i class="bi bi-award-fill"></i> Pembangunan Zona Integritas
                </span>
            </div>
            <h4 class="fw-bold mb-1 text-white">Portal Eviden WBK / WBBM</h4>
            <p class="small text-white-50 mb-0"><?= htmlspecialchars($nama_madrasah ?? 'MAN 3 Banjar', ENT_QUOTES, 'UTF-8') ?></p>
        </div>

        <!-- Body Form -->
        <div class="zi-gate-body">
            
            <?php if($this->session->flashdata('error')): ?>
                <div class="alert alert-danger rounded-3 py-2 px-3 small d-flex align-items-center gap-2 mb-3" role="alert">
                    <i class="bi bi-exclamation-octagon-fill fs-6 flex-shrink-0"></i>
                    <div><?= $this->session->flashdata('error') ?></div>
                </div>
            <?php endif; ?>

            <?php if($this->session->flashdata('info')): ?>
                <div class="alert alert-info rounded-3 py-2 px-3 small d-flex align-items-center gap-2 mb-3" role="alert">
                    <i class="bi bi-info-circle-fill fs-6 flex-shrink-0"></i>
                    <div><?= $this->session->flashdata('info') ?></div>
                </div>
            <?php endif; ?>

            <p class="text-secondary text-center small mb-4" style="line-height: 1.6;">
                Halaman ini terproteksi untuk <strong>Tim Pokja ZI</strong> dan <strong>Tim Penilai (TPI Kemenag / TPN KemenPAN-RB)</strong>. Silakan masukkan PIN Akses resmi madrasah.
            </p>

            <form action="<?= base_url('website/unlock_zi') ?>" method="POST">
                <div class="mb-4">
                    <label class="form-label small fw-bold text-muted text-uppercase d-block text-center" style="letter-spacing: 0.5px;">
                        Masukkan PIN Akses Eviden ZI
                    </label>
                    <div class="text-center mb-3">
                        <span class="badge bg-warning-subtle text-dark border border-warning px-3 py-1 font-monospace" style="font-size: 12px;">
                            <i class="bi bi-shield-lock-fill text-warning me-1"></i> PIN Default Madrasah: <strong>123456</strong>
                        </span>
                    </div>
                    <div class="zi-pin-input-group">
                        <input type="password" name="pin_zi" id="inputPinZi" class="form-control zi-pin-input" placeholder="Ketik 123456..." required autofocus autocomplete="off">
                        <button type="button" class="btn-toggle-pin" onclick="togglePinVisibility()" title="Lihat PIN">
                            <i class="bi bi-eye-slash-fill" id="eyeIcon"></i>
                        </button>
                    </div>
                    <div class="text-center mt-2 text-muted" style="font-size: 11.5px;">
                        <i class="bi bi-info-circle me-1"></i> Masukkan <strong>123456</strong> untuk membuka dan melihat berkas eviden ZI.
                    </div>
                </div>

                <button type="submit" class="zi-btn-submit d-flex align-items-center justify-content-center gap-2">
                    <i class="bi bi-unlock-fill fs-5"></i>
                    <span>Buka Portal Eviden ZI</span>
                </button>
            </form>

            <div class="text-center mt-4 pt-2 border-top">
                <a href="<?= base_url('website/download') ?>" class="text-decoration-none text-muted small">
                    <i class="bi bi-arrow-left me-1"></i> Kembali ke Berkas Publik
                </a>
            </div>

        </div>

        <!-- Footer Note -->
        <div class="zi-gate-footer-note">
            <i class="bi bi-lock-fill text-success me-1"></i> Dilindungi Sistem Keamanan Terenkripsi LabSys Madrasah
        </div>

    </div>
</div>

<script>
function togglePinVisibility() {
    const input = document.getElementById('inputPinZi');
    const icon = document.getElementById('eyeIcon');
    if (input.type === 'password') {
        input.type = 'text';
        icon.className = 'bi bi-eye-fill text-success';
    } else {
        input.type = 'password';
        icon.className = 'bi bi-eye-slash-fill';
    }
}
</script>

<?php $this->load->view('public/partials/archive_footer'); ?>
