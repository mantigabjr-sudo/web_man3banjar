<?php
$current  = uri_string();
$role     = $this->session->userdata('role');
$username = $this->session->userdata('username');

$seg1 = $this->uri->segment(1);
$seg2 = $this->uri->segment(2);

/*
|--------------------------------------------------------------------------
| FALLBACK ACCESS HELPER
|--------------------------------------------------------------------------
*/

if(!function_exists('is_active_menu')){
    function is_active_menu($path, $current){
        return strpos($current, $path) === 0 ? 'active-menu' : '';
    }
}

if(!function_exists('is_open_menu')){
    function is_open_menu($paths, $current){
        foreach($paths as $path){
            if(strpos($current, $path) === 0){
                return 'show';
            }
        }
        return '';
    }
}

if(!function_exists('is_toggle_active')){
    function is_toggle_active($paths, $current){
        foreach($paths as $path){
            if(strpos($current, $path) === 0){
                return 'active-toggle';
            }
        }
        return '';
    }
}

if(!function_exists('sidebar_master_roles')){
    function sidebar_master_roles(){
        return ['admin', 'admin_master'];
    }
}

if(!function_exists('sidebar_admin_roles')){
    function sidebar_admin_roles(){
        return [
            'admin',
            'admin_master',
            'admin_pmb',
            'admin_ppdb',
            'admin_humas',
            'wakil_humas',
            'operator_humas',
            'admin_kesiswaan',
            'admin_kurikulum',
            'admin_sarpras'
        ];
    }
}

if(!function_exists('is_admin_master')){
    function is_admin_master(){
        $CI =& get_instance();
        return in_array($CI->session->userdata('role'), sidebar_master_roles());
    }
}

if(!function_exists('is_admin_panel')){
    function is_admin_panel(){
        $CI =& get_instance();
        return in_array($CI->session->userdata('role'), sidebar_admin_roles());
    }
}

if(!function_exists('can_admin_menu')){
    function can_admin_menu($module){
        $CI =& get_instance();
        $role = $CI->session->userdata('role');

        if(in_array($role, sidebar_master_roles())){
            return true;
        }

        $map = [
            'website'   => ['admin_humas', 'wakil_humas', 'operator_humas'],
            'berita'    => ['admin_humas', 'wakil_humas', 'operator_humas'],
            'ppdb'      => ['admin_kesiswaan', 'admin_pmb', 'admin_ppdb'],
            'akademik'  => ['admin_kesiswaan'],
            'kurikulum' => ['admin_kurikulum'],
            'sarpras'   => ['admin_sarpras'],
        ];

        if(empty($map[$module])){
            return false;
        }

        return in_array($role, $map[$module]);
    }
}

if(!function_exists('role_label')){
    function role_label($role){
        $labels = [
            'admin'            => 'Admin Website & Humas',
            'admin_master'     => 'Admin Website & Humas',
            'admin_humas'      => 'Admin Humas',
            'wakil_humas'      => 'Wakil Humas',
            'operator_humas'   => 'Operator Humas',
            'admin_pmb'        => 'Panitia PPDB',
            'admin_ppdb'       => 'Panitia PPDB',
        ];

        return $labels[$role] ?? 'Admin Website';
    }
}

$is_pmb_role = in_array($role, ['admin_pmb', 'admin_ppdb']);
$is_web_role = in_array($role, ['admin_website', 'admin_humas', 'operator_humas', 'wakil_humas']);
$is_master   = in_array($role, ['admin', 'admin_master']);

// Konfigurasi Branding & Tema Sesuai Role Portal
if($is_pmb_role && !$is_master){
    $portal_title = 'Portal PMB';
    $portal_sub   = 'MAN 3 Banjar';
    $portal_icon  = 'bi-mortarboard-fill';
    $portal_grad  = 'linear-gradient(135deg, #1e3a8a 0%, #2563eb 60%, #3b82f6 100%)';
    $portal_shadow= 'rgba(37, 99, 235, 0.25)';
} elseif($is_web_role && !$is_master){
    $portal_title = 'Admin Website';
    $portal_sub   = 'MAN 3 Banjar';
    $portal_icon  = 'bi-globe2';
    $portal_grad  = 'linear-gradient(135deg, #064e3b 0%, #059669 60%, #10b981 100%)';
    $portal_shadow= 'rgba(5, 150, 105, 0.22)';
} else {
    $portal_title = 'Super Admin';
    $portal_sub   = 'MAN 3 Banjar';
    $portal_icon  = 'bi-shield-shaded';
    $portal_grad  = 'linear-gradient(135deg, #0f172a 0%, #1e293b 60%, #059669 100%)';
    $portal_shadow= 'rgba(15, 23, 42, 0.25)';
}

$roleText = role_label($role);
$userInitial = !empty($username) ? strtoupper(substr($username,0,1)) : 'A';
?>

<style>
:root{
    --side-w: 284px;
    --side-dark: #0f172a;
    --side-muted: #64748b;
    --side-border: #e2e8f0;
    --side-primary: #059669;
    --side-primary-dark: #064e3b;
    --side-primary-light: #ecfdf5;
    --side-primary-border: #a7f3d0;
}

.sidebar{
    position: fixed;
    left: 0;
    top: 0;
    bottom: 0;
    width: var(--side-w);
    z-index: 1040;
    background: #ffffff;
    border-right: 1px solid var(--side-border);
    box-shadow: 12px 0 35px rgba(15, 23, 42, 0.04);
    display: flex;
    flex-direction: column;
    overflow: hidden;
}

.sidebar-inner{
    height: 100%;
    display: flex;
    flex-direction: column;
    padding: 16px 14px;
    overflow: hidden;
}

/* BRAND HEADER */
.sidebar-brand{
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 14px 16px;
    border-radius: 20px;
    background: <?= $portal_grad ?>;
    color: #ffffff;
    box-shadow: 0 12px 28px <?= $portal_shadow ?>;
    margin-bottom: 12px;
    position: relative;
    overflow: hidden;
}
.sidebar-brand::after{
    content: "";
    position: absolute;
    top: -20px;
    right: -20px;
    width: 80px;
    height: 80px;
    background: radial-gradient(circle, rgba(255,255,255,0.22) 0%, transparent 70%);
    border-radius: 50%;
    pointer-events: none;
}
.brand-icon{
    width: 44px;
    height: 44px;
    border-radius: 14px;
    background: rgba(255, 255, 255, 0.2);
    backdrop-filter: blur(6px);
    border: 1px solid rgba(255, 255, 255, 0.35);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 20px;
    font-weight: 900;
    flex-shrink: 0;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
}
.brand-title strong{
    display: block;
    font-size: 18px;
    line-height: 1.15;
    font-weight: 800;
    letter-spacing: -0.3px;
    color: #ffffff;
}
.brand-title small{
    display: block;
    margin-top: 2px;
    color: rgba(255, 255, 255, 0.85);
    font-size: 11.5px;
    font-weight: 600;
    letter-spacing: 0.2px;
}

/* USER PROFILE CARD */
.sidebar-user{
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 10px 14px;
    border-radius: 16px;
    background: #f8fafc;
    border: 1px solid var(--side-border);
    margin-bottom: 14px;
    transition: all 0.2s ease;
}
.sidebar-user:hover{
    background: #f1f5f9;
    border-color: #cbd5e1;
}
.user-avatar-wrap{
    position: relative;
    flex-shrink: 0;
}
.user-mini-avatar{
    width: 40px;
    height: 40px;
    border-radius: 13px;
    background: <?= $is_pmb_role && !$is_master ? '#dbeafe' : '#dcfce7' ?>;
    color: <?= $is_pmb_role && !$is_master ? '#1e40af' : '#15803d' ?>;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 800;
    font-size: 16px;
    border: 1px solid <?= $is_pmb_role && !$is_master ? '#bfdbfe' : '#bbf7d0' ?>;
}
.user-status-dot{
    position: absolute;
    bottom: -1px;
    right: -1px;
    width: 11px;
    height: 11px;
    border-radius: 50%;
    background: #22c55e;
    border: 2px solid #ffffff;
}
.user-mini-info{
    flex: 1;
    min-width: 0;
}
.user-mini-info strong{
    display: block;
    color: #0f172a;
    font-size: 13px;
    font-weight: 750;
    line-height: 1.2;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}
.user-mini-role{
    display: inline-block;
    color: <?= $is_pmb_role && !$is_master ? '#1e40af' : '#047857' ?>;
    background: <?= $is_pmb_role && !$is_master ? '#eff6ff' : '#ecfdf5' ?>;
    font-size: 11px;
    font-weight: 700;
    padding: 1.5px 8px;
    border-radius: 6px;
    margin-top: 3px;
    border: 1px solid <?= $is_pmb_role && !$is_master ? '#dbeafe' : '#d1fae5' ?>;
    line-height: 1.2;
}

/* SCROLLBAR AREA */
.sidebar-scroll{
    flex: 1;
    overflow-y: auto;
    padding-right: 4px;
    margin-right: -4px;
    scrollbar-width: thin;
    scrollbar-color: #cbd5e1 transparent;
}
.sidebar-scroll::-webkit-scrollbar{
    width: 4px;
}
.sidebar-scroll::-webkit-scrollbar-track{
    background: transparent;
}
.sidebar-scroll::-webkit-scrollbar-thumb{
    background: #cbd5e1;
    border-radius: 999px;
}
.sidebar-scroll::-webkit-scrollbar-thumb:hover{
    background: #94a3b8;
}

/* MENU SECTION HEADER */
.menu-section{
    margin: 16px 8px 6px;
    color: #94a3b8;
    font-size: 10.5px;
    font-weight: 750;
    letter-spacing: 0.8px;
    text-transform: uppercase;
    display: flex;
    align-items: center;
    gap: 8px;
}
.menu-section::after{
    content: "";
    flex: 1;
    height: 1px;
    background: #e2e8f0;
}

/* MENU LINK & TOGGLE */
.menu-link,
.menu-toggle{
    width: 100%;
    min-height: 42px;
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 7px 11px;
    border-radius: 13px;
    color: #334155;
    background: transparent;
    border: 0;
    text-decoration: none;
    font-size: 13.5px;
    font-weight: 600;
    transition: all 0.18s ease;
    position: relative;
    cursor: pointer;
    margin-bottom: 2px;
}

.menu-link:hover,
.menu-toggle:hover{
    color: #065f46;
    background: #f0fdf4;
    transform: translateX(2px);
}

.menu-link.active-menu{
    color: #047857;
    background: #ecfdf5;
    font-weight: 700;
    border: 1px solid #a7f3d0;
    box-shadow: 0 2px 8px rgba(16, 185, 129, 0.08);
}

.menu-toggle{
    justify-content: space-between;
}

.menu-toggle-main{
    display: flex;
    align-items: center;
    gap: 10px;
    min-width: 0;
    flex: 1;
}

.menu-toggle-main span{
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}

.menu-toggle.active-toggle,
.menu-toggle[aria-expanded="true"]{
    color: #047857;
    background: #f0fdf4;
    font-weight: 700;
}

/* MENU ICON BOX */
.menu-ico{
    width: 32px;
    height: 32px;
    border-radius: 10px;
    background: #f1f5f9;
    color: #475569;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 15px;
    flex-shrink: 0;
    transition: all 0.18s ease;
}

.menu-link:hover .menu-ico,
.menu-toggle:hover .menu-ico{
    background: #dcfce7;
    color: #15803d;
}

.menu-link.active-menu .menu-ico,
.menu-toggle.active-toggle .menu-ico,
.menu-toggle[aria-expanded="true"] .menu-ico{
    background: #10b981;
    color: #ffffff;
    box-shadow: 0 4px 10px rgba(16, 185, 129, 0.28);
}

/* CHEVRON ARROW */
.chev{
    font-size: 11px;
    color: #94a3b8;
    transition: transform 0.2s ease, color 0.2s ease;
    flex-shrink: 0;
}

.menu-toggle[aria-expanded="true"] .chev{
    transform: rotate(180deg);
    color: #10b981;
}

/* SUBMENU LIST */
.submenu{
    margin: 3px 0 6px 16px;
    padding: 3px 0 3px 12px;
    border-left: 2px solid #e2e8f0;
}

.submenu a{
    min-height: 34px;
    display: flex;
    align-items: center;
    gap: 9px;
    padding: 6px 10px;
    border-radius: 10px;
    color: #64748b;
    text-decoration: none;
    font-size: 12.5px;
    font-weight: 500;
    transition: all 0.16s ease;
    margin-bottom: 2px;
}

.submenu a:hover{
    color: #047857;
    background: #f0fdf4;
    transform: translateX(3px);
}

.submenu a.active-menu{
    color: #047857;
    background: #ecfdf5;
    font-weight: 700;
    border: 1px solid #d1fae5;
}

.sub-dot{
    width: 6px;
    height: 6px;
    border-radius: 50%;
    background: #cbd5e1;
    flex-shrink: 0;
    transition: all 0.16s ease;
}

.submenu a:hover .sub-dot,
.submenu a.active-menu .sub-dot{
    background: #10b981;
    transform: scale(1.3);
}

/* SIDEBAR FOOTER & LOGOUT */
.sidebar-footer{
    padding-top: 10px;
    border-top: 1px solid var(--side-border);
    margin-top: 8px;
}

.logout-link{
    color: #dc2626 !important;
}

.logout-link .menu-ico{
    background: #fee2e2;
    color: #dc2626;
}

.logout-link:hover{
    color: #b91c1c !important;
    background: #fef2f2 !important;
}

.logout-link:hover .menu-ico{
    background: #fca5a5;
    color: #991b1b;
}

.admin-mobile-topbar{
    display: none;
}

.sidebar-backdrop{
    display: none;
}

.content{
    margin-left: var(--side-w);
}

@media(max-width:991px){
    .content{
        margin-left: 0;
        padding-top: 76px;
    }

    .admin-mobile-topbar{
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        height: 64px;
        z-index: 1035;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 12px;
        padding: 10px 16px;
        background: rgba(255,255,255,.94);
        backdrop-filter: blur(18px);
        border-bottom: 1px solid rgba(226,232,240,.9);
        box-shadow: 0 10px 30px rgba(15,23,42,.06);
    }

    .mobile-brand{
        display: flex;
        align-items: center;
        gap: 10px;
        color: #064e3b;
        font-weight: 800;
    }

    .mobile-brand span{
        width: 38px;
        height: 38px;
        border-radius: 12px;
        background: <?= $portal_grad ?>;
        color: white;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 800;
    }

    .sidebar-open-btn{
        width: 42px;
        height: 42px;
        border: 0;
        border-radius: 14px;
        background: #ecfdf5;
        color: #047857;
        font-size: 20px;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .sidebar{
        transform: translateX(-105%);
        transition: transform .25s cubic-bezier(0.4, 0, 0.2, 1);
    }

    .sidebar.sidebar-show{
        transform: translateX(0);
    }

    .sidebar-backdrop{
        position: fixed;
        inset: 0;
        z-index: 1039;
        background: rgba(15,23,42,.45);
        backdrop-filter: blur(2px);
    }

    .sidebar-backdrop.show{
        display: block;
    }
}
</style>

<div class="admin-mobile-topbar">
    <div class="mobile-brand">
        <span><i class="bi <?= $portal_icon ?>"></i></span>
        <div>
            <?= $portal_title ?>
            <div style="font-size:11px;color:#64748b;font-weight:600;line-height:1;">
                <?= $portal_sub ?>
            </div>
        </div>
    </div>

    <button type="button" class="sidebar-open-btn" id="sidebarOpenBtn" aria-label="Buka Navigasi">
        <i class="bi bi-list fs-4"></i>
    </button>
</div>

<div class="sidebar-backdrop" id="sidebarBackdrop"></div>

<aside class="sidebar" id="adminSidebar">
    <div class="sidebar-inner">

        <!-- BRAND HEADER -->
        <div class="sidebar-brand">
            <div class="brand-icon">
                <i class="bi <?= $portal_icon ?>"></i>
            </div>
            <div class="brand-title">
                <strong><?= $portal_title ?></strong>
                <small><?= $portal_sub ?></small>
            </div>
        </div>

        <!-- USER PROFILE -->
        <div class="sidebar-user">
            <div class="user-avatar-wrap">
                <div class="user-mini-avatar">
                    <?= $userInitial ?>
                </div>
                <span class="user-status-dot" title="Online"></span>
            </div>
            <div class="user-mini-info">
                <strong><?= !empty($username) ? htmlspecialchars($username) : 'Administrator' ?></strong>
                <span class="user-mini-role"><?= htmlspecialchars($roleText) ?></span>
            </div>
        </div>

        <!-- SCROLLABLE MENU -->
        <div class="sidebar-scroll">

            <?php if($is_pmb_role && !$is_master): ?>
                <!-- =================================================== -->
                <!-- PORTAL KHUSUS: ADMIN PMB / PPDB                      -->
                <!-- =================================================== -->

                <div class="menu-section">Menu Utama</div>

                <a href="<?= base_url('admin_ppdb/dashboard') ?>"
                   class="menu-link <?= is_active_menu('admin_ppdb/dashboard',$current) ?>">
                    <span class="menu-ico"><i class="bi bi-speedometer2"></i></span>
                    <span>Dashboard PMB</span>
                </a>

                <div class="menu-section">Penerimaan Siswa Baru</div>

                <a href="<?= base_url('admin_ppdb') ?>"
                   class="menu-link <?= ($current == 'admin_ppdb') ? 'active-menu' : '' ?>">
                    <span class="menu-ico"><i class="bi bi-people-fill"></i></span>
                    <span>Data Calon Siswa</span>
                </a>

                <a href="<?= base_url('admin_ppdb/verifikasi') ?>"
                   class="menu-link <?= is_active_menu('admin_ppdb/verifikasi',$current) ?>">
                    <span class="menu-ico"><i class="bi bi-check2-circle"></i></span>
                    <span>Verifikasi Berkas</span>
                </a>

                <a href="<?= base_url('admin_ppdb/diterima') ?>"
                   class="menu-link <?= is_active_menu('admin_ppdb/diterima',$current) ?>">
                    <span class="menu-ico"><i class="bi bi-person-check-fill"></i></span>
                    <span>Siswa Diterima</span>
                </a>

                <a href="<?= base_url('admin_ppdb/ditolak') ?>"
                   class="menu-link <?= is_active_menu('admin_ppdb/ditolak',$current) ?>">
                    <span class="menu-ico"><i class="bi bi-person-x-fill"></i></span>
                    <span>Siswa Ditolak</span>
                </a>

                <div class="menu-section">Konfigurasi PMB</div>

                <a href="<?= base_url('admin_ppdb/settings') ?>"
                   class="menu-link <?= is_active_menu('admin_ppdb/settings',$current) ?>">
                    <span class="menu-ico"><i class="bi bi-sliders"></i></span>
                    <span>Pengaturan PMB</span>
                </a>

                <div class="menu-section">Tautan Publik</div>

                <a href="<?= base_url('ppdb') ?>" target="_blank" class="menu-link">
                    <span class="menu-ico"><i class="bi bi-box-arrow-up-right"></i></span>
                    <span>Form Pendaftaran PMB</span>
                </a>

            <?php elseif($is_web_role && !$is_master): ?>
                <!-- =================================================== -->
                <!-- PORTAL KHUSUS: ADMIN WEBSITE & HUMAS                -->
                <!-- =================================================== -->

                <div class="menu-section">Menu Utama</div>

                <a href="<?= base_url('dashboard') ?>"
                   class="menu-link <?= is_active_menu('dashboard',$current) ?>">
                    <span class="menu-ico"><i class="bi bi-grid-1x2-fill"></i></span>
                    <span>Dashboard Web</span>
                </a>

                <!-- 1. PUBLIKASI & BERITA -->
                <div class="menu-section">Publikasi &amp; Berita</div>

                <a href="<?= base_url('berita') ?>"
                   class="menu-link <?= is_active_menu('berita',$current) ?>">
                    <span class="menu-ico"><i class="bi bi-newspaper"></i></span>
                    <span>Kelola Berita</span>
                </a>

                <a href="<?= base_url('admin_website/pengumuman') ?>"
                   class="menu-link <?= is_active_menu('admin_website/pengumuman',$current) ?>">
                    <span class="menu-ico"><i class="bi bi-megaphone-fill"></i></span>
                    <span>Pengumuman Beranda</span>
                </a>

                <a href="<?= base_url('admin_banner') ?>"
                   class="menu-link <?= is_active_menu('admin_banner',$current) ?>">
                    <span class="menu-ico"><i class="bi bi-images"></i></span>
                    <span>Banner Slider</span>
                </a>

                <a href="<?= base_url('admin_website/pamflet') ?>"
                   class="menu-link <?= is_active_menu('admin_website/pamflet',$current) ?>">
                    <span class="menu-ico"><i class="bi bi-card-image"></i></span>
                    <span>Pamflet Informasi</span>
                </a>

                <!-- 2. PROFIL & LEMBAGA -->
                <div class="menu-section">Profil &amp; Lembaga</div>

                <a href="<?= base_url('admin_website/profil') ?>"
                   class="menu-link <?= is_active_menu('admin_website/profil',$current) ?>">
                    <span class="menu-ico"><i class="bi bi-building"></i></span>
                    <span>Profil Madrasah</span>
                </a>

                <a href="<?= base_url('admin_website/tentang') ?>"
                   class="menu-link <?= is_active_menu('admin_website/tentang',$current) ?>">
                    <span class="menu-ico"><i class="bi bi-info-circle-fill"></i></span>
                    <span>Tentang Madrasah</span>
                </a>

                <a href="<?= base_url('admin_struktur') ?>"
                   class="menu-link <?= is_active_menu('admin_struktur',$current) ?>">
                    <span class="menu-ico"><i class="bi bi-diagram-3-fill"></i></span>
                    <span>Struktur Organisasi</span>
                </a>

                <a href="<?= base_url('admin_website/ptk') ?>"
                   class="menu-link <?= is_active_menu('admin_website/ptk',$current) ?>">
                    <span class="menu-ico"><i class="bi bi-person-badge"></i></span>
                    <span>PTK Website</span>
                </a>

                <!-- 3. MEDIA & UNDUHAN -->
                <div class="menu-section">Media &amp; Unduhan</div>

                <a href="<?= base_url('admin_website/galeri') ?>"
                   class="menu-link <?= is_active_menu('admin_website/galeri',$current) ?>">
                    <span class="menu-ico"><i class="bi bi-camera-reels-fill"></i></span>
                    <span>Galeri Foto</span>
                </a>

                <a href="<?= base_url('admin_website/video') ?>"
                   class="menu-link <?= is_active_menu('admin_website/video',$current) ?>">
                    <span class="menu-ico"><i class="bi bi-play-btn-fill"></i></span>
                    <span>Video Profil</span>
                </a>

                <a href="<?= base_url('admin_website/download') ?>"
                   class="menu-link <?= is_active_menu('admin_website/download',$current) ?>">
                    <span class="menu-ico"><i class="bi bi-cloud-arrow-down-fill"></i></span>
                    <span>Data Download</span>
                </a>

                <!-- 4. LAYANAN SISWA -->
                <div class="menu-section">Layanan Siswa</div>

                <a href="<?= base_url('admin_foto_ijazah') ?>"
                   class="menu-link <?= is_active_menu('admin_foto_ijazah',$current) ?>">
                    <span class="menu-ico"><i class="bi bi-camera-fill"></i></span>
                    <span>Foto Ijazah XII</span>
                </a>

                <!-- 5. TAUTAN PUBLIK -->
                <div class="menu-section">Tautan Publik</div>

                <a href="<?= base_url() ?>" target="_blank" class="menu-link">
                    <span class="menu-ico"><i class="bi bi-box-arrow-up-right"></i></span>
                    <span>Lihat Website Depan</span>
                </a>

            <?php else: ?>
                <!-- =================================================== -->
                <!-- PORTAL LENGKAP: SUPER ADMINISTRATOR (ADMIN / MASTER) -->
                <!-- =================================================== -->

                <div class="menu-section">Menu Utama</div>

                <a href="<?= base_url('dashboard') ?>"
                   class="menu-link <?= is_active_menu('dashboard',$current) ?>">
                    <span class="menu-ico"><i class="bi bi-grid-1x2-fill"></i></span>
                    <span>Dashboard Utama</span>
                </a>

                <!-- SECTION: WEBSITE MADRASAH -->
                <div class="menu-section">Website Madrasah</div>

                <!-- 1. Publikasi & Berita -->
                <button class="menu-toggle <?= is_toggle_active(['berita','admin_website/pengumuman','admin_banner','admin_website/pamflet'], $current) ?>"
                        type="button"
                        data-bs-toggle="collapse"
                        data-bs-target="#menuPublikasi"
                        aria-expanded="<?= is_open_menu(['berita','admin_website/pengumuman','admin_banner','admin_website/pamflet'], $current) ? 'true' : 'false' ?>">
                    <span class="menu-toggle-main">
                        <span class="menu-ico"><i class="bi bi-megaphone-fill"></i></span>
                        <span>Publikasi &amp; Berita</span>
                    </span>
                    <i class="bi bi-chevron-down chev"></i>
                </button>

                <div class="collapse submenu <?= is_open_menu(['berita','admin_website/pengumuman','admin_banner','admin_website/pamflet'], $current) ?>" id="menuPublikasi">
                    <a class="<?= is_active_menu('berita',$current) ?>" href="<?= base_url('berita') ?>">
                        <span class="sub-dot"></span> Kelola Berita
                    </a>
                    <a class="<?= is_active_menu('admin_website/pengumuman',$current) ?>" href="<?= base_url('admin_website/pengumuman') ?>">
                        <span class="sub-dot"></span> Pengumuman Beranda
                    </a>
                    <a class="<?= is_active_menu('admin_banner',$current) ?>" href="<?= base_url('admin_banner') ?>">
                        <span class="sub-dot"></span> Banner Slider
                    </a>
                    <a class="<?= is_active_menu('admin_website/pamflet',$current) ?>" href="<?= base_url('admin_website/pamflet') ?>">
                        <span class="sub-dot"></span> Pamflet Informasi
                    </a>
                </div>

                <!-- 2. Profil & Kelembagaan -->
                <button class="menu-toggle <?= is_toggle_active(['admin_website/profil','admin_website/tentang','admin_struktur','admin_website/ptk'], $current) ?>"
                        type="button"
                        data-bs-toggle="collapse"
                        data-bs-target="#menuProfil"
                        aria-expanded="<?= is_open_menu(['admin_website/profil','admin_website/tentang','admin_struktur','admin_website/ptk'], $current) ? 'true' : 'false' ?>">
                    <span class="menu-toggle-main">
                        <span class="menu-ico"><i class="bi bi-building"></i></span>
                        <span>Profil &amp; Lembaga</span>
                    </span>
                    <i class="bi bi-chevron-down chev"></i>
                </button>

                <div class="collapse submenu <?= is_open_menu(['admin_website/profil','admin_website/tentang','admin_struktur','admin_website/ptk'], $current) ?>" id="menuProfil">
                    <a class="<?= is_active_menu('admin_website/profil',$current) ?>" href="<?= base_url('admin_website/profil') ?>">
                        <span class="sub-dot"></span> Profil Madrasah
                    </a>
                    <a class="<?= is_active_menu('admin_website/tentang',$current) ?>" href="<?= base_url('admin_website/tentang') ?>">
                        <span class="sub-dot"></span> Tentang Madrasah
                    </a>
                    <a class="<?= is_active_menu('admin_struktur',$current) ?>" href="<?= base_url('admin_struktur') ?>">
                        <span class="sub-dot"></span> Struktur Organisasi
                    </a>
                    <a class="<?= is_active_menu('admin_website/ptk',$current) ?>" href="<?= base_url('admin_website/ptk') ?>">
                        <span class="sub-dot"></span> PTK Website
                    </a>
                </div>

                <!-- 3. Media & Unduhan -->
                <button class="menu-toggle <?= is_toggle_active(['admin_website/galeri','admin_website/video','admin_website/download'], $current) ?>"
                        type="button"
                        data-bs-toggle="collapse"
                        data-bs-target="#menuMedia"
                        aria-expanded="<?= is_open_menu(['admin_website/galeri','admin_website/video','admin_website/download'], $current) ? 'true' : 'false' ?>">
                    <span class="menu-toggle-main">
                        <span class="menu-ico"><i class="bi bi-collection-play-fill"></i></span>
                        <span>Media &amp; Unduhan</span>
                    </span>
                    <i class="bi bi-chevron-down chev"></i>
                </button>

                <div class="collapse submenu <?= is_open_menu(['admin_website/galeri','admin_website/video','admin_website/download'], $current) ?>" id="menuMedia">
                    <a class="<?= is_active_menu('admin_website/galeri',$current) ?>" href="<?= base_url('admin_website/galeri') ?>">
                        <span class="sub-dot"></span> Galeri Foto
                    </a>
                    <a class="<?= is_active_menu('admin_website/video',$current) ?>" href="<?= base_url('admin_website/video') ?>">
                        <span class="sub-dot"></span> Video Profil
                    </a>
                    <a class="<?= is_active_menu('admin_website/download',$current) ?>" href="<?= base_url('admin_website/download') ?>">
                        <span class="sub-dot"></span> Data Download
                    </a>
                </div>

                <!-- SECTION: ADMIN PMB / PPDB -->
                <div class="menu-section">Admin PMB / PPDB</div>

                <button class="menu-toggle <?= is_toggle_active(['admin_ppdb'], $current) ?>"
                        type="button"
                        data-bs-toggle="collapse"
                        data-bs-target="#menuPPDB"
                        aria-expanded="<?= is_open_menu(['admin_ppdb'], $current) ? 'true' : 'false' ?>">
                    <span class="menu-toggle-main">
                        <span class="menu-ico"><i class="bi bi-mortarboard-fill"></i></span>
                        <span>Penerimaan Siswa</span>
                    </span>
                    <i class="bi bi-chevron-down chev"></i>
                </button>

                <div class="collapse submenu <?= is_open_menu(['admin_ppdb'], $current) ?>" id="menuPPDB">
                    <a class="<?= is_active_menu('admin_ppdb/dashboard',$current) ?>" href="<?= base_url('admin_ppdb/dashboard') ?>">
                        <span class="sub-dot"></span> Dashboard PMB
                    </a>
                    <a class="<?= ($current == 'admin_ppdb') ? 'active-menu' : '' ?>" href="<?= base_url('admin_ppdb') ?>">
                        <span class="sub-dot"></span> Data Calon Siswa
                    </a>
                    <a class="<?= is_active_menu('admin_ppdb/verifikasi',$current) ?>" href="<?= base_url('admin_ppdb/verifikasi') ?>">
                        <span class="sub-dot"></span> Verifikasi Berkas
                    </a>
                    <a class="<?= is_active_menu('admin_ppdb/diterima',$current) ?>" href="<?= base_url('admin_ppdb/diterima') ?>">
                        <span class="sub-dot"></span> Siswa Diterima
                    </a>
                    <a class="<?= is_active_menu('admin_ppdb/ditolak',$current) ?>" href="<?= base_url('admin_ppdb/ditolak') ?>">
                        <span class="sub-dot"></span> Siswa Ditolak
                    </a>
                    <a class="<?= is_active_menu('admin_ppdb/settings',$current) ?>" href="<?= base_url('admin_ppdb/settings') ?>">
                        <span class="sub-dot"></span> Pengaturan PMB
                    </a>
                </div>

                <!-- SECTION: LAYANAN SISWA -->
                <div class="menu-section">Layanan Siswa</div>

                <a href="<?= base_url('admin_foto_ijazah') ?>"
                   class="menu-link <?= is_active_menu('admin_foto_ijazah',$current) ?>">
                    <span class="menu-ico"><i class="bi bi-camera-fill"></i></span>
                    <span>Foto Ijazah XII</span>
                </a>

                <!-- SECTION: TAUTAN PUBLIK -->
                <div class="menu-section">Tautan Publik</div>

                <a href="<?= base_url() ?>" target="_blank" class="menu-link">
                    <span class="menu-ico"><i class="bi bi-box-arrow-up-right"></i></span>
                    <span>Lihat Website Depan</span>
                </a>
                <a href="<?= base_url('ppdb') ?>" target="_blank" class="menu-link">
                    <span class="menu-ico"><i class="bi bi-box-arrow-up-right"></i></span>
                    <span>Form Pendaftaran PMB</span>
                </a>

            <?php endif; ?>

        </div>

        <!-- FOOTER & LOGOUT -->
        <div class="sidebar-footer">
            <a href="<?= base_url('auth/logout') ?>" class="menu-link logout-link">
                <span class="menu-ico"><i class="bi bi-box-arrow-right"></i></span>
                <span>Keluar (Logout)</span>
            </a>
        </div>

    </div>
</aside>

<script>
document.addEventListener('DOMContentLoaded', function(){
    const sidebar = document.getElementById('adminSidebar');
    const openBtn = document.getElementById('sidebarOpenBtn');
    const backdrop = document.getElementById('sidebarBackdrop');

    function openSidebar(){
        if(sidebar){
            sidebar.classList.add('sidebar-show');
        }
        if(backdrop){
            backdrop.classList.add('show');
        }
    }

    function closeSidebar(){
        if(sidebar){
            sidebar.classList.remove('sidebar-show');
        }
        if(backdrop){
            backdrop.classList.remove('show');
        }
    }

    if(openBtn){
        openBtn.addEventListener('click', openSidebar);
    }

    if(backdrop){
        backdrop.addEventListener('click', closeSidebar);
    }

    document.querySelectorAll('.sidebar a').forEach(function(link){
        link.addEventListener('click', function(){
            if(window.innerWidth <= 991){
                closeSidebar();
            }
        });
    });
});
</script>