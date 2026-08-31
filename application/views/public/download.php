<?php $this->load->view('public/partials/archive_header'); ?>

<?php
if (!function_exists('get_file_info')) {
    function get_file_info($filename) {
        $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
        
        $info = [
            'ext' => strtoupper($ext),
            'icon' => 'bi-file-earmark-text',
            'color' => '#64748b',
            'bg' => '#f1f5f9',
            'text' => '#475569'
        ];
        
        switch ($ext) {
            case 'pdf':
                $info['icon'] = 'bi-filetype-pdf';
                $info['color'] = '#ef4444';
                $info['bg'] = '#fef2f2';
                $info['text'] = '#991b1b';
                break;
            case 'doc':
            case 'docx':
                $info['icon'] = 'bi-filetype-docx';
                $info['color'] = '#3b82f6';
                $info['bg'] = '#eff6ff';
                $info['text'] = '#1e40af';
                break;
            case 'xls':
            case 'xlsx':
                $info['icon'] = 'bi-filetype-xlsx';
                $info['color'] = '#10b981';
                $info['bg'] = '#ecfdf5';
                $info['text'] = '#065f46';
                break;
            case 'ppt':
            case 'pptx':
                $info['icon'] = 'bi-filetype-pptx';
                $info['color'] = '#f97316';
                $info['bg'] = '#fff7ed';
                $info['text'] = '#9a3412';
                break;
            case 'zip':
            case 'rar':
                $info['icon'] = 'bi-file-earmark-zip';
                $info['color'] = '#8b5cf6';
                $info['bg'] = '#f5f3ff';
                $info['text'] = '#5b21b6';
                break;
        }
        
        $filepath = FCPATH . 'assets/downloads/' . $filename;
        if (file_exists($filepath)) {
            $bytes = filesize($filepath);
            if ($bytes >= 1048576) {
                $info['size'] = number_format($bytes / 1048576, 2) . ' MB';
            } elseif ($bytes >= 1024) {
                $info['size'] = number_format($bytes / 1024, 1) . ' KB';
            } else {
                $info['size'] = $bytes . ' B';
            }
        } else {
            $info['size'] = 'Unknown';
        }
        
        return $info;
    }
}
?>

<header class="web-archive-hero">
    <div class="container">
        <div class="detail-breadcrumb">
            <a href="<?= base_url() ?>"><i class="bi bi-house-door"></i> Beranda</a>
            <span>/</span>
            <strong>Download</strong>
        </div>
        <h1>Pusat Unduhan</h1>
        <p>Dapatkan berbagai format dokumen, formulir, dan file informasi dari <?= htmlspecialchars($nama_madrasah ?? 'MAN 3 Banjar', ENT_QUOTES, 'UTF-8') ?></p>
    </div>
</header>

<section class="web-section" style="background: #f8fafc; padding: 80px 0;">
    <div class="container">
        <div class="row justify-content-center reveal">
            <div class="col-lg-10">
                <div class="card border-0 shadow-lg" style="border-radius: 20px; overflow:hidden; transform: translateY(-40px); background: #ffffff;">
                    <div class="card-body p-4 p-md-5 p-lg-5">
                        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px; flex-wrap: wrap; gap: 15px;">
                            <div style="display: flex; align-items: center; gap: 16px;">
                                <div style="width: 60px; height: 60px; border-radius: 16px; background: #ecfdf5; display: flex; align-items: center; justify-content: center;">
                                    <i class="bi bi-folder2-open" style="font-size: 28px; color: #10b981;"></i>
                                </div>
                                <div>
                                    <h2 style="font-weight: 800; color: #1e293b; margin: 0;">Daftar File</h2>
                                    <p class="text-muted mb-0" style="font-size: 14px;">Silakan unduh dokumen yang Anda butuhkan di bawah ini.</p>
                                </div>
                            </div>
                        </div>
                        
                        <div class="table-responsive">
                            <table class="table table-hover align-middle" id="downloadTable" style="width: 100%;">
                                <thead style="background: #f1f5f9; border-bottom: 2px solid #e2e8f0;">
                                    <tr>
                                        <th style="width: 60px; color: #475569; font-weight: 700; padding: 15px; border: 0; border-top-left-radius: 12px; border-bottom-left-radius: 12px;">No</th>
                                        <th style="color: #475569; font-weight: 700; padding: 15px; border: 0;">Jenis</th>
                                        <th style="color: #475569; font-weight: 700; padding: 15px; border: 0;">Nama Dokumen</th>
                                        <th style="color: #475569; font-weight: 700; padding: 15px; border: 0;">Tanggal & Ukuran</th>
                                        <th style="width: 120px; text-align: center; color: #475569; font-weight: 700; padding: 15px; border: 0; border-top-right-radius: 12px; border-bottom-right-radius: 12px;">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if(empty($downloads)): ?>
                                        <tr>
                                            <td colspan="5" class="text-center" style="padding: 40px; color: #64748b;">
                                                <i class="bi bi-inbox" style="font-size: 40px; color: #cbd5e1; display: block; margin-bottom: 15px;"></i>
                                                Belum ada file dokumen yang tersedia untuk diunduh.
                                            </td>
                                        </tr>
                                    <?php else: ?>
                                        <?php $no=1; foreach($downloads as $d): ?>
                                            <?php $file_info = get_file_info($d->file_path); ?>
                                            <tr style="border-bottom: 1px solid #f1f5f9;">
                                                <td style="padding: 18px 15px; font-weight: 600; color: #64748b;"><?= $no++ ?></td>
                                                <td style="padding: 18px 15px;">
                                                    <!-- Colored Premium Icon/Logo Box -->
                                                    <div style="width: 48px; height: 48px; border-radius: 12px; background: <?= $file_info['bg'] ?>; display: flex; align-items: center; justify-content: center; position: relative; border: 1px solid rgba(0,0,0,0.03);" title="Tipe berkas: <?= $file_info['ext'] ?>">
                                                        <i class="bi <?= $file_info['icon'] ?>" style="font-size: 24px; color: <?= $file_info['color'] ?>;"></i>
                                                        <span style="position: absolute; bottom: -4px; right: -4px; font-size: 8px; font-weight: 900; background: <?= $file_info['color'] ?>; color: white; padding: 2px 4px; border-radius: 4px; box-shadow: 0 2px 4px rgba(0,0,0,0.15);">
                                                            <?= $file_info['ext'] ?>
                                                        </span>
                                                    </div>
                                                </td>
                                                <td style="padding: 18px 15px;">
                                                    <div style="font-weight: 750; color: #1e293b; margin-bottom: 4px; font-size: 16px;">
                                                        <?= htmlspecialchars($d->judul, ENT_QUOTES, 'UTF-8') ?>
                                                    </div>
                                                    <?php if(!empty($d->keterangan)): ?>
                                                        <div style="font-size: 13px; color: #64748b; line-height: 1.5;">
                                                            <?= htmlspecialchars($d->keterangan, ENT_QUOTES, 'UTF-8') ?>
                                                        </div>
                                                    <?php endif; ?>
                                                </td>
                                                <td style="padding: 18px 15px; color: #64748b; font-size: 13px; font-weight: 600; line-height: 1.6;">
                                                    <div><i class="bi bi-calendar3 me-1"></i> <?= date('d M Y', strtotime($d->tanggal)) ?></div>
                                                    <div style="color: #94a3b8; font-size: 12px;"><i class="bi bi-hdd-network me-1"></i> Ukuran: <?= $file_info['size'] ?></div>
                                                </td>
                                                <td style="padding: 18px 15px; text-align: center;">
                                                    <a href="<?= base_url('assets/downloads/'.$d->file_path) ?>" target="_blank" style="display: inline-flex; align-items: center; justify-content: center; gap: 6px; background: linear-gradient(135deg, #059669, #10b981); color: white; padding: 8px 16px; border-radius: 10px; font-weight: 700; text-decoration: none; font-size: 14px; transition: transform 0.2s ease, box-shadow 0.2s ease; box-shadow: 0 4px 12px rgba(16, 185, 129, 0.2);" onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 6px 15px rgba(16, 185, 129, 0.3)';" onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 4px 12px rgba(16, 185, 129, 0.2)';">
                                                        <i class="bi bi-download"></i> Unduh
                                                    </a>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const reveals = document.querySelectorAll('.reveal');
    if('IntersectionObserver' in window) {
        const observer = new IntersectionObserver(function(entries) {
            entries.forEach(function(entry) {
                if(entry.isIntersecting) { entry.target.classList.add('visible'); observer.unobserve(entry.target); }
            });
        }, { threshold: 0.1 });
        reveals.forEach(function(el) { observer.observe(el); });
    } else {
        reveals.forEach(function(el) { el.classList.add('visible'); });
    }
});
</script>

<?php $this->load->view('public/partials/archive_footer'); ?>

