<?php $this->load->view('public/partials/archive_header'); ?>

<?php
if (!function_exists('get_initials_struktur')) {
    function get_initials_struktur($name) {
        $clean = preg_replace('/\b(S\.Pd|S\.Pd\.I|M\.Pd|M\.H|Lc|S\.Si|Drs\.|Dra\.|S\.Ag|H\.|Hj\.|S\.Kom|M\.M|S\.Sos|S\.H)\b/i', '', (string)$name);
        $words = array_values(array_filter(explode(' ', trim($clean))));
        $in = '';
        if(count($words) >= 2) {
            $in = mb_substr($words[0], 0, 1) . mb_substr($words[1], 0, 1);
        } elseif(count($words) == 1) {
            $in = mb_substr($words[0], 0, 2);
        }
        return !empty($in) ? strtoupper($in) : 'PTK';
    }
}
?>

<header class="web-archive-hero text-center" style="background: radial-gradient(circle at top right, rgba(16, 185, 129, 0.35), transparent 50%), radial-gradient(circle at bottom left, rgba(2, 44, 34, 0.5), transparent 50%), linear-gradient(135deg, #064e3b 0%, #065f46 50%, #047857 100%) !important; color: #ffffff !important; padding: 55px 0 50px 0 !important;">
    <div class="container d-flex flex-column align-items-center text-center">
        <div class="detail-breadcrumb d-flex justify-content-center align-items-center mb-3">
            <a href="<?= base_url() ?>" style="color: #a7f3d0 !important; text-decoration: none; font-weight: 600;"><i class="bi bi-house-door"></i> Beranda</a>
            <span style="color: rgba(255,255,255,0.4); margin: 0 6px;">/</span>
            <strong style="color: #ffffff; font-weight: 700;">Struktur Organisasi</strong>
        </div>
        <h1 style="color: #ffffff !important; font-weight: 900; font-size: clamp(1.8rem, 3.5vw, 2.4rem); letter-spacing: -0.02em; margin-bottom: 8px; text-shadow: 0 2px 10px rgba(0,0,0,0.15);"><?= htmlspecialchars($kategori_nama, ENT_QUOTES, 'UTF-8') ?></h1>
        <p style="color: #ecfdf5 !important; font-size: 14.5px; line-height: 1.6; margin: 0 auto; max-width: 680px; font-weight: 500;">Bagan Struktur Organisasi <?= htmlspecialchars($kategori_nama, ENT_QUOTES, 'UTF-8') ?> <?= htmlspecialchars($nama_madrasah ?? 'MAN 3 Banjar', ENT_QUOTES, 'UTF-8') ?></p>
    </div>
</header>

<section class="web-section" style="background: #f8fafc; padding: 40px 0 80px 0;">
    <div class="container">

        <!-- Navigasi Kategori Tab -->
        <div class="web-filter-grid mb-4" style="display:flex; gap:12px; flex-wrap:wrap; justify-content:center;">
            <a href="<?= base_url('website/struktur/tenaga-pendidik') ?>" class="web-btn <?= $kategori_slug == 'tenaga-pendidik' ? 'web-btn-primary' : 'web-btn-outline' ?>">
                <i class="bi bi-person-workspace me-1"></i> Tenaga Pendidik
            </a>
            <a href="<?= base_url('website/struktur/kependidikan') ?>" class="web-btn <?= $kategori_slug == 'kependidikan' ? 'web-btn-primary' : 'web-btn-outline' ?>">
                <i class="bi bi-file-earmark-person me-1"></i> Kependidikan (Tata Usaha)
            </a>
            <a href="<?= base_url('website/struktur/koordinator') ?>" class="web-btn <?= $kategori_slug == 'koordinator' ? 'web-btn-primary' : 'web-btn-outline' ?>">
                <i class="bi bi-diagram-3 me-1"></i> Koordinator &amp; Ekstrakurikuler
            </a>
        </div>

        <?php if ($kategori_slug == 'tenaga-pendidik'): ?>
            <!-- ══════════════════════════════════════════════════════════ -->
            <!-- 🏛️ BAGAN STRUKTUR HIRARKI TERPADU TENAGA PENDIDIK 🏛️ -->
            <!-- ══════════════════════════════════════════════════════════ -->
            <div class="struktur-canvas">
                
                <!-- ═══ LEVEL 1: PIMPINAN (KEPALA MADRASAH & KAUR TU DAMPINGAN) ═══ -->
                <div class="tree-group mb-5">
                    <div class="group-badge-wrapper mb-3">
                        <span class="group-title-badge badge-pimpinan">
                            <i class="bi bi-award-fill me-1"></i> Pimpinan Madrasah
                        </span>
                    </div>

                    <!-- Layout Pimpinan: Kepala Madrasah di Tengah, Kaur TU di Samping Koordinasi -->
                    <div class="pimpinan-hierarki-wrapper">
                        
                        <!-- Kepala Madrasah (Pusat Komando) -->
                        <?php if (!empty($kepala_madrasah)): ?>
                            <?php foreach($kepala_madrasah as $km): ?>
                                <?php 
                                $gbr = $km->foto ?? '';
                                $file_path = !empty($gbr) ? FCPATH.'uploads/ptk/foto/'.$gbr : '';
                                $img_src = (!empty($gbr) && file_exists($file_path)) ? base_url('uploads/ptk/foto/'.$gbr) : null;
                                ?>
                                <div class="kamad-card-box">
                                    <div class="card-glow-kamad">
                                        <div class="kamad-photo-wrap">
                                            <?php if ($img_src): ?>
                                                <img src="<?= $img_src ?>" alt="<?= htmlspecialchars($km->nama_lengkap, ENT_QUOTES, 'UTF-8') ?>">
                                            <?php else: ?>
                                                <div class="avatar-fallback"><i class="bi bi-person-fill"></i></div>
                                            <?php endif; ?>
                                            <span class="kamad-crown"><i class="bi bi-shield-check"></i></span>
                                        </div>
                                        <div class="kamad-info">
                                            <span class="badge-role-kamad">KEPALA MADRASAH</span>
                                            <h4 class="kamad-name"><?= htmlspecialchars($km->nama_lengkap, ENT_QUOTES, 'UTF-8') ?></h4>
                                            <?php if(!empty($km->nip)): ?>
                                                <span class="kamad-nip"><i class="bi bi-card-text me-1"></i>NIP. <?= htmlspecialchars($km->nip, ENT_QUOTES, 'UTF-8') ?></span>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php endif; ?>

                        <!-- Garis Koordinasi Horizontal ke Kaur TU -->
                        <?php if (!empty($kaur_tu)): ?>
                            <div class="garis-koordinasi-wrapper">
                                <div class="garis-koordinasi-line"></div>
                                <span class="garis-koordinasi-label">Garis Koordinasi</span>
                            </div>

                            <!-- Kaur TU (Staf Pimpinan / Tata Usaha) -->
                            <?php foreach($kaur_tu as $kt): ?>
                                <?php 
                                $gbr = $kt->foto ?? '';
                                $file_path = !empty($gbr) ? FCPATH.'uploads/ptk/foto/'.$gbr : '';
                                $img_src = (!empty($gbr) && file_exists($file_path)) ? base_url('uploads/ptk/foto/'.$gbr) : null;
                                ?>
                                <div class="kaurtu-card-box">
                                    <div class="card-glow-kaurtu">
                                        <div class="kaurtu-photo-wrap">
                                            <?php if ($img_src): ?>
                                                <img src="<?= $img_src ?>" alt="<?= htmlspecialchars($kt->nama_lengkap, ENT_QUOTES, 'UTF-8') ?>">
                                            <?php else: ?>
                                                <div class="avatar-fallback"><i class="bi bi-person-fill"></i></div>
                                            <?php endif; ?>
                                        </div>
                                        <div class="kaurtu-info">
                                            <span class="badge-role-kaurtu">KEPALA TATA USAHA</span>
                                            <h5 class="kaurtu-name"><?= htmlspecialchars($kt->nama_lengkap, ENT_QUOTES, 'UTF-8') ?></h5>
                                            <?php if(!empty($kt->nip)): ?>
                                                <span class="kaurtu-nip">NIP. <?= htmlspecialchars($kt->nip, ENT_QUOTES, 'UTF-8') ?></span>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php endif; ?>

                    </div>
                </div>

                <!-- Garis Komando Utama ke Bawah -->
                <div class="main-tree-connector-down"></div>

                <!-- ═══ LEVEL 2: WAKIL KEPALA MADRASAH (WAKAMAD 4 BIDANG) ═══ -->
                <?php if (!empty($wakamad)): ?>
                    <div class="tree-group mb-5">
                        <div class="group-badge-wrapper mb-3">
                            <span class="group-title-badge badge-wakamad-title">
                                <i class="bi bi-person-badge-fill me-1"></i> Wakil Kepala Madrasah (Wakamad)
                            </span>
                        </div>
                        
                        <div class="group-connector-vertical"></div>
                        <div class="group-row-wrapper">
                            <div class="group-connector-horizontal"></div>
                            <div class="group-members wakamad-grid">
                                <?php foreach($wakamad as $wk): ?>
                                    <?php 
                                    $gbr = $wk->foto ?? '';
                                    $file_path = !empty($gbr) ? FCPATH.'uploads/ptk/foto/'.$gbr : '';
                                    $img_src = (!empty($gbr) && file_exists($file_path)) ? base_url('uploads/ptk/foto/'.$gbr) : null;
                                    ?>
                                    <div class="member-card-wrapper">
                                        <div class="card-connector-vertical"></div>
                                        <div class="wakamad-premium-card" style="border-top: 4px solid <?= $wk->color ?? '#10b981' ?>;">
                                            <div class="wakamad-img-wrap">
                                                <?php if ($img_src): ?>
                                                    <img src="<?= $img_src ?>" alt="<?= htmlspecialchars($wk->nama_lengkap, ENT_QUOTES, 'UTF-8') ?>">
                                                <?php else: ?>
                                                    <div class="avatar-fallback"><i class="bi bi-person"></i></div>
                                                <?php endif; ?>
                                                <span class="wakamad-icon-badge" style="background: <?= $wk->color ?? '#10b981' ?>;">
                                                    <i class="bi <?= $wk->icon ?? 'bi-person-badge' ?>"></i>
                                                </span>
                                            </div>
                                            <div class="wakamad-card-body">
                                                <span class="wakamad-role-pill" style="color: <?= $wk->color ?? '#10b981' ?>; background: <?= $wk->color_bg ?? '#ecfdf5' ?>;">
                                                    <?= htmlspecialchars($wk->jabatan_clean ?? $wk->jabatan, ENT_QUOTES, 'UTF-8') ?>
                                                </span>
                                                <h5 class="wakamad-name" title="<?= htmlspecialchars($wk->nama_lengkap, ENT_QUOTES, 'UTF-8') ?>">
                                                    <?= htmlspecialchars($wk->nama_lengkap, ENT_QUOTES, 'UTF-8') ?>
                                                </h5>
                                                <small class="wakamad-bidang-sub"><?= $wk->bidang ?? 'Bidang Madrasah' ?></small>
                                                <?php if(!empty($wk->nip)): ?>
                                                    <span class="wakamad-nip">NIP. <?= htmlspecialchars($wk->nip, ENT_QUOTES, 'UTF-8') ?></span>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>

                <!-- Garis Komando Penghubung ke Level Bawah -->
                <div class="main-tree-connector-down"></div>

                <!-- ═══ LEVEL 3: TUGAS TAMBAHAN KHUSUS, KOORDINATOR & KEPALA LAB ═══ -->
                <?php if (!empty($tugas_khusus)): ?>
                    <div class="tree-group mb-5">
                        <div class="group-badge-wrapper mb-3">
                            <span class="group-title-badge badge-koordinator-title">
                                <i class="bi bi-diagram-3-fill me-1"></i> Kepala Lab, Perpustakaan, Koordinator &amp; Pembina
                            </span>
                        </div>
                        
                        <div class="group-connector-vertical"></div>
                        <div class="group-row-wrapper">
                            <div class="group-connector-horizontal"></div>
                            <div class="group-members">
                                <?php foreach($tugas_khusus as $tk): ?>
                                    <?php 
                                    $gbr = $tk->foto ?? '';
                                    $file_path = !empty($gbr) ? FCPATH.'uploads/ptk/foto/'.$gbr : '';
                                    $img_src = (!empty($gbr) && file_exists($file_path)) ? base_url('uploads/ptk/foto/'.$gbr) : null;
                                    $tags = !empty($tk->cleaned_tags) ? $tk->cleaned_tags : array_filter(array_map('trim', explode(',', $tk->tugas_display ?? '')));
                                    ?>
                                    <div class="member-card-wrapper">
                                        <div class="card-connector-vertical"></div>
                                        <div class="member-glass-card tugas-khusus-card">
                                            <div class="member-card-img-wrap">
                                                <?php if ($img_src): ?>
                                                    <img src="<?= $img_src ?>" alt="<?= htmlspecialchars($tk->nama_lengkap, ENT_QUOTES, 'UTF-8') ?>">
                                                <?php else: ?>
                                                    <div class="avatar-initials-gradient">
                                                        <span><?= get_initials_struktur($tk->nama_lengkap) ?></span>
                                                    </div>
                                                <?php endif; ?>
                                            </div>
                                            <div class="member-card-body">
                                                <div class="tugas-tags-container mb-2">
                                                    <?php 
                                                    foreach($tags as $t_trim): 
                                                        if(empty($t_trim)) continue;
                                                        $tag_class = 'tag-blue';
                                                        if(stripos($t_trim, 'Lab') !== false) $tag_class = 'tag-emerald';
                                                        elseif(stripos($t_trim, 'Perpustakaan') !== false) $tag_class = 'tag-purple';
                                                        elseif(stripos($t_trim, 'Keagamaan') !== false || stripos($t_trim, '5 K') !== false || stripos($t_trim, '5K') !== false) $tag_class = 'tag-purple';
                                                        elseif(stripos($t_trim, 'Kokurikuler') !== false) $tag_class = 'tag-blue';
                                                        elseif(stripos($t_trim, 'Pembina Kelas') !== false || stripos($t_trim, 'BK') !== false) $tag_class = 'tag-teal';
                                                        elseif(stripos($t_trim, 'Pembina') !== false || stripos($t_trim, 'Ekskul') !== false || stripos($t_trim, 'OSIM') !== false) $tag_class = 'tag-amber';
                                                    ?>
                                                        <span class="custom-tugas-badge <?= $tag_class ?>">
                                                            <?= htmlspecialchars($t_trim, ENT_QUOTES, 'UTF-8') ?>
                                                        </span>
                                                    <?php endforeach; ?>
                                                </div>
                                                <h6 class="member-name" title="<?= htmlspecialchars($tk->nama_lengkap, ENT_QUOTES, 'UTF-8') ?>">
                                                    <?= htmlspecialchars($tk->nama_lengkap, ENT_QUOTES, 'UTF-8') ?>
                                                </h6>
                                                <?php if(!empty($tk->nip)): ?>
                                                    <span class="member-nip">NIP. <?= htmlspecialchars($tk->nip, ENT_QUOTES, 'UTF-8') ?></span>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>

                <!-- Garis Komando Penghubung ke Level Wali Kelas -->
                <div class="main-tree-connector-down"></div>

                <!-- ═══ LEVEL 4: JAJARAN WALI KELAS (X, XI, XII) ═══ -->
                <?php if (!empty($wali_kelas_grouped)): ?>
                    <div class="tree-group mb-5">
                        <div class="group-badge-wrapper mb-3">
                            <span class="group-title-badge badge-wali-title">
                                <i class="bi bi-mortarboard-fill me-1"></i> Jajaran Wali Kelas (Tingkat X, XI, XII)
                            </span>
                        </div>

                        <!-- Tab Filter Tingkat Wali Kelas -->
                        <div class="d-flex justify-content-center gap-2 mb-4 flex-wrap">
                            <button type="button" class="wali-filter-btn active" onclick="filterWaliTingkat('all', this)">
                                Semua Wali Kelas (<?= count($wali_kelas) ?>)
                            </button>
                            <button type="button" class="wali-filter-btn" onclick="filterWaliTingkat('X', this)">
                                🟢 Tingkat X (<?= count($wali_kelas_grouped['Tingkat X'] ?? []) ?>)
                            </button>
                            <button type="button" class="wali-filter-btn" onclick="filterWaliTingkat('XI', this)">
                                🔵 Tingkat XI (<?= count($wali_kelas_grouped['Tingkat XI'] ?? []) ?>)
                            </button>
                            <button type="button" class="wali-filter-btn" onclick="filterWaliTingkat('XII', this)">
                                🟣 Tingkat XII (<?= count($wali_kelas_grouped['Tingkat XII'] ?? []) ?>)
                            </button>
                        </div>

                        <div class="group-members wali-grid-container">
                            <?php foreach($wali_kelas as $wk): ?>
                                <?php 
                                $gbr = $wk->foto ?? '';
                                $file_path = !empty($gbr) ? FCPATH.'uploads/ptk/foto/'.$gbr : '';
                                $img_src = (!empty($gbr) && file_exists($file_path)) ? base_url('uploads/ptk/foto/'.$gbr) : null;
                                ?>
                                <div class="member-card-wrapper wali-item-card" data-tingkat="<?= $wk->tingkat ?>">
                                    <div class="member-glass-card wali-card-styled">
                                        <div class="member-card-img-wrap">
                                            <?php if ($img_src): ?>
                                                <img src="<?= $img_src ?>" alt="<?= htmlspecialchars($wk->nama_lengkap, ENT_QUOTES, 'UTF-8') ?>">
                                            <?php else: ?>
                                                <div class="avatar-fallback"><i class="bi bi-person"></i></div>
                                            <?php endif; ?>
                                            <span class="wali-class-badge badge-tingkat-<?= strtolower($wk->tingkat) ?>">
                                                Kelas <?= htmlspecialchars($wk->nama_kelas, ENT_QUOTES, 'UTF-8') ?>
                                            </span>
                                        </div>
                                        <div class="member-card-body">
                                            <span class="wali-role-caption">Wali Kelas</span>
                                            <h6 class="member-name" title="<?= htmlspecialchars($wk->nama_lengkap, ENT_QUOTES, 'UTF-8') ?>">
                                                <?= htmlspecialchars($wk->nama_lengkap, ENT_QUOTES, 'UTF-8') ?>
                                            </h6>
                                            <?php if(!empty($wk->nip)): ?>
                                                <span class="member-nip">NIP. <?= htmlspecialchars($wk->nip, ENT_QUOTES, 'UTF-8') ?></span>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                <?php endif; ?>

                <!-- Garis Komando Penghubung ke Level Guru -->
                <div class="main-tree-connector-down"></div>

                <!-- ═══ LEVEL 5: DEWAN GURU / TENAGA PENDIDIK LENGKAP ═══ -->
                <?php if (!empty($guru)): ?>
                    <div class="tree-group">
                        <div class="group-badge-wrapper mb-3">
                            <span class="group-title-badge badge-guru-title">
                                <i class="bi bi-people-fill me-1"></i> Dewan Guru &amp; Tenaga Pendidik (<?= count($guru) ?> Guru)
                            </span>
                        </div>

                        <!-- Live Search Dewan Guru -->
                        <div class="row justify-content-center mb-4">
                            <div class="col-md-6 col-lg-5">
                                <div class="input-group search-guru-box shadow-sm">
                                    <span class="input-group-text bg-white border-end-0"><i class="bi bi-search text-muted"></i></span>
                                    <input type="text" id="searchGuruInput" class="form-control border-start-0" placeholder="Cari nama guru, NIP, atau mata pelajaran..." onkeyup="searchGuruTable(this.value)">
                                </div>
                            </div>
                        </div>

                        <div class="group-members dewan-guru-grid" id="dewanGuruContainer">
                            <?php foreach($guru as $g): ?>
                                <?php 
                                $gbr = $g->foto ?? '';
                                $file_path = !empty($gbr) ? FCPATH.'uploads/ptk/foto/'.$gbr : '';
                                $img_src = (!empty($gbr) && file_exists($file_path)) ? base_url('uploads/ptk/foto/'.$gbr) : null;
                                ?>
                                <div class="member-card-wrapper guru-card-item" 
                                     data-name="<?= strtolower(htmlspecialchars($g->nama_lengkap)) ?>" 
                                     data-nip="<?= strtolower($g->nip ?? '') ?>"
                                     data-mapel="<?= strtolower(htmlspecialchars($g->mapel_diampu ?? '')) ?>">
                                    <div class="member-glass-card guru-clean-card">
                                        <div class="member-card-img-wrap">
                                            <?php if ($img_src): ?>
                                                <img src="<?= $img_src ?>" alt="<?= htmlspecialchars($g->nama_lengkap, ENT_QUOTES, 'UTF-8') ?>">
                                            <?php else: ?>
                                                <div class="avatar-fallback"><i class="bi bi-person"></i></div>
                                            <?php endif; ?>
                                        </div>
                                        <div class="member-card-body">
                                            <h6 class="member-name" title="<?= htmlspecialchars($g->nama_lengkap, ENT_QUOTES, 'UTF-8') ?>">
                                                <?= htmlspecialchars($g->nama_lengkap, ENT_QUOTES, 'UTF-8') ?>
                                            </h6>
                                            <?php if(!empty($g->mapel_diampu)): ?>
                                                <div class="guru-mapel-text" title="<?= htmlspecialchars($g->mapel_diampu, ENT_QUOTES, 'UTF-8') ?>">
                                                    <i class="bi bi-book me-1"></i><?= htmlspecialchars($g->mapel_diampu, ENT_QUOTES, 'UTF-8') ?>
                                                </div>
                                            <?php else: ?>
                                                <div class="guru-mapel-text text-muted fst-italic">Guru Mata Pelajaran</div>
                                            <?php endif; ?>
                                            <?php if(!empty($g->nip)): ?>
                                                <span class="member-nip mt-1">NIP. <?= htmlspecialchars($g->nip, ENT_QUOTES, 'UTF-8') ?></span>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                <?php endif; ?>

            </div>

        <?php elseif ($kategori_slug == 'kependidikan'): ?>
            <!-- ══════════════════════════════════════════════════════════ -->
            <!-- 📁 STRUKTUR TENAGA KEPENDIDIKAN (TATA USAHA) 📁 -->
            <!-- ══════════════════════════════════════════════════════════ -->
            <div class="struktur-canvas">
                <?php if (empty($anggota)): ?>
                    <div class="text-center text-white py-5">Belum ada data Tenaga Kependidikan.</div>
                <?php else: ?>
                    <?php 
                    $kaur = $anggota[0]; 
                    $gbr_kaur = $kaur->foto ?? '';
                    $file_path_kaur = !empty($gbr_kaur) ? FCPATH.'uploads/ptk/foto/'.$gbr_kaur : '';
                    $img_src_kaur = (!empty($gbr_kaur) && file_exists($file_path_kaur)) ? base_url('uploads/ptk/foto/'.$gbr_kaur) : null;
                    ?>
                    <!-- Kaur TU -->
                    <div class="tree-group mb-5">
                        <div class="group-badge-wrapper mb-3">
                            <span class="group-title-badge badge-pimpinan">
                                <i class="bi bi-building me-1"></i> Kepala Urusan Tata Usaha (Kaur TU)
                            </span>
                        </div>
                        <div class="d-flex justify-content-center">
                            <div class="card-glow-kaurtu" style="max-width: 320px; width: 100%;">
                                <div class="kaurtu-photo-wrap">
                                    <?php if ($img_src_kaur): ?>
                                        <img src="<?= $img_src_kaur ?>" alt="<?= htmlspecialchars($kaur->nama_lengkap, ENT_QUOTES, 'UTF-8') ?>">
                                    <?php else: ?>
                                        <div class="avatar-fallback"><i class="bi bi-person-fill"></i></div>
                                    <?php endif; ?>
                                </div>
                                <div class="kaurtu-info">
                                    <span class="badge-role-kaurtu">KEPALA TATA USAHA</span>
                                    <h5 class="kaurtu-name"><?= htmlspecialchars($kaur->nama_lengkap, ENT_QUOTES, 'UTF-8') ?></h5>
                                    <?php if(!empty($kaur->nip)): ?>
                                        <span class="kaurtu-nip">NIP. <?= htmlspecialchars($kaur->nip, ENT_QUOTES, 'UTF-8') ?></span>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Staf TU -->
                    <?php if (count($anggota) > 1): ?>
                        <div class="main-tree-connector-down"></div>
                        <div class="tree-group">
                            <div class="group-badge-wrapper mb-3">
                                <span class="group-title-badge badge-tu-title">
                                    <i class="bi bi-people-fill me-1"></i> Staf &amp; Pengadministrasi Tata Usaha
                                </span>
                            </div>
                            <div class="group-members">
                                <?php for($i = 1; $i < count($anggota); $i++): ?>
                                    <?php 
                                    $p = $anggota[$i]; 
                                    $gbr = $p->foto ?? '';
                                    $file_path = !empty($gbr) ? FCPATH.'uploads/ptk/foto/'.$gbr : '';
                                    $img_src = (!empty($gbr) && file_exists($file_path)) ? base_url('uploads/ptk/foto/'.$gbr) : null;
                                    ?>
                                    <div class="member-card-wrapper">
                                        <div class="member-glass-card">
                                            <div class="member-card-img-wrap">
                                                <?php if ($img_src): ?>
                                                    <img src="<?= $img_src ?>" alt="<?= htmlspecialchars($p->nama_lengkap, ENT_QUOTES, 'UTF-8') ?>">
                                                <?php else: ?>
                                                    <div class="avatar-initials-gradient">
                                                        <span><?= get_initials_struktur($wk->nama_lengkap ?? $g->nama_lengkap ?? $p->nama_lengkap ?? 'PTK') ?></span>
                                                    </div>
                                                <?php endif; ?>
                                            </div>
                                            <div class="member-card-body">
                                                <span class="badge bg-warning text-dark mb-1" style="font-size: 9px;"><?= htmlspecialchars($p->jabatan ?? 'Staf TU', ENT_QUOTES, 'UTF-8') ?></span>
                                                <h6 class="member-name"><?= htmlspecialchars($p->nama_lengkap, ENT_QUOTES, 'UTF-8') ?></h6>
                                                <?php if(!empty($p->nip)): ?>
                                                    <span class="member-nip">NIP. <?= htmlspecialchars($p->nip, ENT_QUOTES, 'UTF-8') ?></span>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    </div>
                                <?php endfor; ?>
                            </div>
                        </div>
                    <?php endif; ?>
                <?php endif; ?>
            </div>

        <?php else: ?>
            <!-- ══════════════════════════════════════════════════════════ -->
            <!-- 🎯 STRUKTUR KOORDINATOR & EKSTRAKURIKULER 🎯 -->
            <!-- ══════════════════════════════════════════════════════════ -->
            <div class="struktur-canvas">
                <?php if (empty($anggota)): ?>
                    <div class="text-center text-white py-5">Belum ada data Koordinator Ekstrakurikuler.</div>
                <?php else: ?>
                    <div class="tree-group">
                        <div class="group-badge-wrapper mb-3">
                            <span class="group-title-badge badge-koordinator-title">
                                <i class="bi bi-flag-fill me-1"></i> Koordinator &amp; Pembina Ekstrakurikuler
                            </span>
                        </div>
                        <div class="group-members">
                            <?php foreach($anggota as $p): ?>
                                <?php 
                                $gbr = $p->foto ?? '';
                                $file_path = !empty($gbr) ? FCPATH.'uploads/ptk/foto/'.$gbr : '';
                                $img_src = (!empty($gbr) && file_exists($file_path)) ? base_url('uploads/ptk/foto/'.$gbr) : null;
                                ?>
                                <div class="member-card-wrapper">
                                    <div class="member-glass-card">
                                        <div class="member-card-img-wrap">
                                            <?php if ($img_src): ?>
                                                <img src="<?= $img_src ?>" alt="<?= htmlspecialchars($p->nama_lengkap, ENT_QUOTES, 'UTF-8') ?>">
                                            <?php else: ?>
                                                <div class="avatar-fallback"><i class="bi bi-person"></i></div>
                                            <?php endif; ?>
                                        </div>
                                        <div class="member-card-body">
                                            <span class="badge bg-pink text-white mb-1" style="background:#db2777; font-size: 9px;"><?= htmlspecialchars($p->jabatan, ENT_QUOTES, 'UTF-8') ?></span>
                                            <h6 class="member-name"><?= htmlspecialchars($p->nama_lengkap, ENT_QUOTES, 'UTF-8') ?></h6>
                                            <?php if(!empty($p->nip)): ?>
                                                <span class="member-nip">NIP. <?= htmlspecialchars($p->nip, ENT_QUOTES, 'UTF-8') ?></span>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        <?php endif; ?>

    </div>
</section>

<style>
/* ═════════════════════════════════════════════════════════════════
   ESTETIKA BAGAN STRUKTUR ORGANISASI (ROYAL EMERALD & GLASSMORPHISM)
   ═════════════════════════════════════════════════════════════════ */

.struktur-canvas {
    opacity: 1 !important;
    visibility: visible !important;
    display: block !important;
    background: radial-gradient(circle at 50% 10%, rgba(34, 197, 94, 0.28), transparent 50%),
                radial-gradient(circle at 10% 90%, rgba(16, 185, 129, 0.18), transparent 45%),
                radial-gradient(circle at 90% 90%, rgba(5, 150, 105, 0.2), transparent 45%),
                linear-gradient(145deg, #092e1b 0%, #061c10 100%);
    border-radius: 32px;
    padding: 55px 30px;
    box-shadow: inset 0 0 50px rgba(0,0,0,0.3), 0 25px 60px rgba(6, 28, 16, 0.2);
    border: 1.5px solid rgba(255, 255, 255, 0.12);
    position: relative;
}

/* Group & Section Headers */
.tree-group {
    text-align: center;
    position: relative;
}

.group-badge-wrapper {
    display: flex;
    justify-content: center;
    position: relative;
    z-index: 5;
}

.group-title-badge {
    display: inline-block;
    font-weight: 800;
    font-size: 12px;
    text-transform: uppercase;
    letter-spacing: 1.2px;
    padding: 9px 26px;
    border-radius: 50px;
    color: #ffffff;
    box-shadow: 0 6px 20px rgba(0, 0, 0, 0.25), inset 0 2px 4px rgba(255, 255, 255, 0.25);
}

.badge-pimpinan {
    background: linear-gradient(135deg, #eab308, #ca8a04);
    border: 1.5px solid #fde047;
    color: #1e293b;
}

.badge-wakamad-title {
    background: linear-gradient(135deg, #2563eb, #1d4ed8);
    border: 1.5px solid #93c5fd;
}

.badge-koordinator-title {
    background: linear-gradient(135deg, #059669, #047857);
    border: 1.5px solid #6ee7b7;
}

.badge-wali-title {
    background: linear-gradient(135deg, #7c3aed, #6d28d9);
    border: 1.5px solid #c4b5fd;
}

.badge-guru-title {
    background: linear-gradient(135deg, #0d9488, #0f766e);
    border: 1.5px solid #5eead4;
}

.badge-tu-title {
    background: linear-gradient(135deg, #ea580c, #c2410c);
    border: 1.5px solid #fdba74;
}

/* Connector Lines */
.main-tree-connector-down {
    width: 2px;
    height: 45px;
    background: linear-gradient(180deg, rgba(255,255,255,0.4), rgba(255,255,255,0.1));
    margin: 15px auto 35px auto;
    position: relative;
}

.group-connector-vertical {
    width: 2px;
    height: 25px;
    background: rgba(255, 255, 255, 0.35);
    margin: 0 auto;
}

.group-row-wrapper {
    position: relative;
    padding-top: 1px;
}

.group-connector-horizontal {
    height: 2px;
    background: rgba(255, 255, 255, 0.35);
    width: calc(100% - 140px);
    max-width: 980px;
    margin: 0 auto;
    position: relative;
}

.card-connector-vertical {
    width: 2px;
    height: 20px;
    background: rgba(255, 255, 255, 0.35);
    margin: 0 auto;
}

/* ═══════════════════════════════════════════════════════════
   LEVEL 1: PIMPINAN HIERARKI (KAMAD & KAUR TU DAMPINGAN)
   ═══════════════════════════════════════════════════════════ */
.pimpinan-hierarki-wrapper {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 20px;
    flex-wrap: wrap;
    position: relative;
    max-width: 820px;
    margin: 0 auto;
}

/* Kartu Kepala Madrasah */
.kamad-card-box {
    position: relative;
    z-index: 10;
}

.card-glow-kamad {
    background: linear-gradient(145deg, rgba(255, 255, 255, 0.16), rgba(255, 255, 255, 0.05));
    backdrop-filter: blur(16px);
    border: 2px solid #eab308;
    border-radius: 24px;
    padding: 16px 24px;
    display: flex;
    align-items: center;
    gap: 18px;
    box-shadow: 0 12px 35px rgba(234, 179, 8, 0.25), inset 0 0 20px rgba(234, 179, 8, 0.1);
    transition: transform 0.3s ease;
}
.card-glow-kamad:hover {
    transform: translateY(-4px);
    box-shadow: 0 18px 45px rgba(234, 179, 8, 0.35);
}

.kamad-photo-wrap {
    width: 90px;
    height: 105px;
    border-radius: 16px;
    overflow: hidden;
    position: relative;
    border: 2.5px solid #fde047;
    flex-shrink: 0;
    background: #064e3b;
}
.kamad-photo-wrap img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}
.kamad-crown {
    position: absolute;
    bottom: 2px;
    right: 2px;
    background: #eab308;
    color: #1e293b;
    width: 24px;
    height: 24px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 13px;
    box-shadow: 0 2px 6px rgba(0,0,0,0.3);
}

.kamad-info {
    text-align: left;
}
.badge-role-kamad {
    background: linear-gradient(135deg, #eab308, #ca8a04);
    color: #0f172a;
    font-weight: 850;
    font-size: 10px;
    letter-spacing: 1px;
    padding: 4px 10px;
    border-radius: 6px;
    display: inline-block;
    margin-bottom: 6px;
}
.kamad-name {
    color: #ffffff;
    font-weight: 800;
    font-size: 16px;
    margin-bottom: 4px;
    letter-spacing: -0.2px;
}
.kamad-nip {
    color: #fef08a;
    font-size: 11px;
    font-family: monospace;
}

/* Garis Koordinasi ke Kaur TU */
.garis-koordinasi-wrapper {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    min-width: 90px;
}
.garis-koordinasi-line {
    width: 100%;
    height: 0px;
    border-top: 2.5px dashed rgba(253, 186, 116, 0.7);
    position: relative;
}
.garis-koordinasi-label {
    font-size: 8.5px;
    color: #fdba74;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    font-weight: 700;
    margin-top: 4px;
    white-space: nowrap;
}

/* Kartu Kaur TU */
.kaurtu-card-box {
    position: relative;
    z-index: 10;
}
.card-glow-kaurtu {
    background: linear-gradient(145deg, rgba(255, 255, 255, 0.12), rgba(255, 255, 255, 0.04));
    backdrop-filter: blur(14px);
    border: 1.5px solid rgba(253, 186, 116, 0.6);
    border-radius: 20px;
    padding: 12px 18px;
    display: flex;
    align-items: center;
    gap: 14px;
    box-shadow: 0 8px 25px rgba(234, 88, 12, 0.15);
    transition: transform 0.3s ease;
}
.card-glow-kaurtu:hover {
    transform: translateY(-3px);
    border-color: #fdba74;
}
.kaurtu-photo-wrap {
    width: 65px;
    height: 75px;
    border-radius: 12px;
    overflow: hidden;
    position: relative;
    border: 2px solid #fdba74;
    flex-shrink: 0;
    background: #064e3b;
}
.kaurtu-photo-wrap img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}
.kaurtu-info {
    text-align: left;
}
.badge-role-kaurtu {
    background: #ea580c;
    color: #ffffff;
    font-weight: 800;
    font-size: 9px;
    letter-spacing: 0.8px;
    padding: 3px 8px;
    border-radius: 6px;
    display: inline-block;
    margin-bottom: 4px;
}
.kaurtu-name {
    color: #ffffff;
    font-weight: 700;
    font-size: 13.5px;
    margin-bottom: 2px;
}
.kaurtu-nip {
    color: #fed7aa;
    font-size: 10px;
    font-family: monospace;
}

/* ═══════════════════════════════════════════════════════════
   LEVEL 2: WAKAMAD PREMIUM CARDS (4 BIDANG)
   ═══════════════════════════════════════════════════════════ */
.wakamad-grid {
    display: flex;
    flex-wrap: wrap;
    justify-content: center;
    gap: 16px 20px;
}

.wakamad-premium-card {
    background: linear-gradient(145deg, rgba(255, 255, 255, 0.1), rgba(255, 255, 255, 0.04));
    backdrop-filter: blur(12px);
    border: 1.5px solid rgba(255, 255, 255, 0.16);
    border-radius: 20px;
    padding: 14px 12px;
    width: 190px;
    text-align: center;
    box-shadow: 0 8px 24px rgba(0,0,0,0.15);
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    display: flex;
    flex-direction: column;
    align-items: center;
}
.wakamad-premium-card:hover {
    transform: translateY(-6px);
    background: rgba(255, 255, 255, 0.16);
    border-color: rgba(255, 255, 255, 0.35);
    box-shadow: 0 16px 36px rgba(0,0,0,0.25);
}

.wakamad-img-wrap {
    width: 100px;
    height: 120px;
    border-radius: 14px;
    overflow: hidden;
    position: relative;
    border: 2px solid rgba(255, 255, 255, 0.3);
    margin-bottom: 10px;
    background: rgba(255,255,255,0.05);
}
.wakamad-img-wrap img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}
.wakamad-icon-badge {
    position: absolute;
    bottom: 4px;
    right: 4px;
    color: #ffffff;
    width: 22px;
    height: 22px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 11px;
    box-shadow: 0 2px 5px rgba(0,0,0,0.3);
}

.wakamad-card-body {
    width: 100%;
}
.wakamad-role-pill {
    display: block;
    font-weight: 800;
    font-size: 10px;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    padding: 3px 6px;
    border-radius: 6px;
    margin-bottom: 6px;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}
.wakamad-name {
    color: #ffffff;
    font-weight: 700;
    font-size: 12.5px;
    margin-bottom: 2px;
    line-height: 1.3;
    height: 32px;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}
.wakamad-bidang-sub {
    display: block;
    color: rgba(255, 255, 255, 0.7);
    font-size: 9.5px;
    margin-bottom: 4px;
}
.wakamad-nip {
    color: rgba(255, 255, 255, 0.55);
    font-size: 9px;
    font-family: monospace;
}

/* ═══════════════════════════════════════════════════════════
   LEVEL 3, 4, 5: GRID KARTU GURU & WALI KELAS
   ═══════════════════════════════════════════════════════════ */
.group-members {
    display: flex;
    flex-wrap: wrap;
    justify-content: center;
    gap: 12px 18px;
}

.member-glass-card {
    background: linear-gradient(145deg, rgba(255, 255, 255, 0.12) 0%, rgba(255, 255, 255, 0.04) 100%);
    backdrop-filter: blur(14px);
    border: 1px solid rgba(255, 255, 255, 0.22);
    border-radius: 20px;
    padding: 12px 10px;
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    display: flex;
    flex-direction: column;
    width: 172px;
    text-align: center;
    box-shadow: 0 8px 24px rgba(0,0,0,0.18);
}
.member-glass-card:hover {
    transform: translateY(-6px);
    background: linear-gradient(145deg, rgba(255, 255, 255, 0.18) 0%, rgba(255, 255, 255, 0.08) 100%);
    border-color: rgba(255, 255, 255, 0.45);
    box-shadow: 0 16px 36px rgba(16, 185, 129, 0.3);
}

.member-card-img-wrap {
    height: 155px;
    border-radius: 14px;
    overflow: hidden;
    position: relative;
    background: rgba(255, 255, 255, 0.06);
    border: 1.5px solid rgba(255, 255, 255, 0.25);
    box-shadow: 0 4px 12px rgba(0,0,0,0.15);
}
.member-card-img-wrap img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.avatar-initials-gradient {
    width: 100%;
    height: 100%;
    display: flex;
    align-items: center;
    justify-content: center;
    background: radial-gradient(circle at top left, #34d399 0%, #059669 50%, #064e3b 100%);
    color: #ffffff;
    font-weight: 900;
    font-size: 32px;
    letter-spacing: 1px;
    text-shadow: 0 2px 10px rgba(0,0,0,0.35);
}

.avatar-fallback {
    width: 100%;
    height: 100%;
    display: flex;
    align-items: center;
    justify-content: center;
    background: rgba(255, 255, 255, 0.05);
    color: rgba(255, 255, 255, 0.35);
    font-size: 52px;
}

.member-card-body {
    padding-top: 10px;
    display: flex;
    flex-direction: column;
    flex-grow: 1;
}

.member-name {
    font-weight: 800;
    color: #ffffff;
    font-size: 12px;
    line-height: 1.35;
    margin-bottom: 4px;
    min-height: 32px;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
    text-shadow: 0 1px 4px rgba(0,0,0,0.3);
}

.member-nip {
    font-size: 9.5px;
    color: #a7f3d0;
    font-family: monospace;
    font-weight: 600;
}

/* Tugas Khusus Badges */
.custom-tugas-badge {
    display: inline-block;
    padding: 3px 8px;
    border-radius: 6px;
    font-size: 9px;
    font-weight: 750;
    line-height: 1.25;
    margin-bottom: 4px;
    box-shadow: 0 2px 6px rgba(0,0,0,0.2);
    white-space: normal;
    text-align: center;
}
}
.tag-emerald { background: #059669; color: #ffffff; border: 1px solid #10b981; }
.tag-blue { background: #2563eb; color: #ffffff; border: 1px solid #3b82f6; }
.tag-teal { background: #0d9488; color: #ffffff; border: 1px solid #14b8a6; }
.tag-amber { background: #d97706; color: #ffffff; border: 1px solid #f59e0b; }
.tag-purple { background: #7c3aed; color: #ffffff; border: 1px solid #8b5cf6; }

/* Wali Kelas Role Caption */
.wali-role-caption {
    font-size: 9px;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    color: #a7f3d0;
    margin-bottom: 3px;
    display: block;
}

/* Wali Kelas Badges */
.wali-class-badge {
    position: absolute;
    bottom: 6px;
    left: 6px;
    right: 6px;
    padding: 3px 6px;
    border-radius: 6px;
    font-weight: 800;
    font-size: 9.5px;
    color: #ffffff;
    box-shadow: 0 2px 6px rgba(0,0,0,0.3);
}
.badge-tingkat-x { background: #16a34a; }
.badge-tingkat-xi { background: #2563eb; }
.badge-tingkat-xii { background: #7c3aed; }

/* Filter Buttons Wali */
.wali-filter-btn {
    border-radius: 50px;
    font-weight: 700;
    font-size: 11.5px;
    padding: 6px 16px;
    border: 1px solid rgba(255, 255, 255, 0.25);
    background: rgba(255, 255, 255, 0.1);
    color: #ffffff;
    transition: all 0.2s;
}
.wali-filter-btn:hover, .wali-filter-btn.active {
    background: #ffffff;
    color: #0f172a;
    border-color: #ffffff;
    box-shadow: 0 4px 12px rgba(0,0,0,0.15);
}

/* Dewan Guru Specific */
.guru-mapel-text {
    font-size: 10px;
    color: #a7f3d0;
    font-weight: 600;
    line-height: 1.25;
    height: 25px;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

.search-guru-box input {
    border-radius: 0 12px 12px 0;
    font-size: 13px;
    padding: 10px 14px;
}
.search-guru-box .input-group-text {
    border-radius: 12px 0 0 12px;
}

/* Media Query Responsive */
@media (max-width: 768px) {
    .pimpinan-hierarki-wrapper {
        flex-direction: column;
        gap: 16px;
    }
    .garis-koordinasi-wrapper {
        min-width: unset;
        width: 100%;
    }
    .garis-koordinasi-line {
        width: 40px;
        margin: 0 auto;
    }
    .wakamad-premium-card {
        width: 155px;
    }
    .member-glass-card {
        width: 135px;
    }
    .member-card-img-wrap {
        height: 140px;
    }
    .group-connector-horizontal {
        width: calc(100% - 60px);
    }
    .struktur-canvas {
        padding: 35px 15px;
        border-radius: 20px;
    }
}
</style>

<script>
// Filter Tingkat Wali Kelas
function filterWaliTingkat(tingkat, btn) {
    document.querySelectorAll('.wali-filter-btn').forEach(b => b.classList.remove('active'));
    if(btn) btn.classList.add('active');

    const items = document.querySelectorAll('.wali-item-card');
    items.forEach(it => {
        if(tingkat === 'all' || it.getAttribute('data-tingkat') === tingkat) {
            it.style.display = 'flex';
        } else {
            it.style.display = 'none';
        }
    });
}

// Live Search Dewan Guru
function searchGuruTable(keyword) {
    const q = keyword.toLowerCase().trim();
    const items = document.querySelectorAll('.guru-card-item');
    items.forEach(it => {
        const name = it.getAttribute('data-name') || '';
        const nip = it.getAttribute('data-nip') || '';
        const mapel = it.getAttribute('data-mapel') || '';
        if(q === '' || name.includes(q) || nip.includes(q) || mapel.includes(q)) {
            it.style.display = 'flex';
        } else {
            it.style.display = 'none';
        }
    });
}
</script>

<?php $this->load->view('public/partials/archive_footer'); ?>
