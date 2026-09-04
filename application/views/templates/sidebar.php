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
            'admin'            => 'Admin Master',
            'admin_master'     => 'Admin Master',
            'admin_pmb'        => 'Panitia PMB',
            'admin_ppdb'       => 'Panitia PMB',
            'admin_humas'      => 'Admin Humas',
            'wakil_humas'      => 'Wakil Humas',
            'operator_humas'   => 'Operator Humas',
            'admin_kesiswaan'  => 'Admin Kesiswaan',
            'admin_kurikulum'  => 'Admin Kurikulum',
            'admin_sarpras'    => 'Admin Sarpras',
            'guru'             => 'Guru',
            'teknisi'          => 'Teknisi'
        ];

        return $labels[$role] ?? strtoupper((string)$role);
    }
}

$canWebsite   = is_admin_panel() && can_admin_menu('website');
$canPPDB      = is_admin_panel() && can_admin_menu('ppdb');
$canAkademik  = is_admin_panel() && can_admin_menu('akademik');
$canKurikulum = is_admin_panel() && can_admin_menu('kurikulum');
$canSarpras   = is_admin_panel() && can_admin_menu('sarpras');

$canMasterMenu = is_admin_panel() && is_admin_master();

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
    background: linear-gradient(135deg, #064e3b 0%, #059669 60%, #10b981 100%);
    color: #ffffff;
    box-shadow: 0 12px 28px rgba(5, 150, 105, 0.22);
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
    background: #dcfce7;
    color: #15803d;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 800;
    font-size: 16px;
    border: 1px solid #bbf7d0;
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
    color: #047857;
    background: #ecfdf5;
    font-size: 11px;
    font-weight: 700;
    padding: 1.5px 8px;
    border-radius: 6px;
    margin-top: 3px;
    border: 1px solid #d1fae5;
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
        background: linear-gradient(135deg, #064e3b, #10b981);
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
        <span>L</span>
        <div>
            LabSys
            <div style="font-size:11px;color:#64748b;font-weight:600;line-height:1;">
                MAN 3 Banjar
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
                <i class="bi bi-mortarboard-fill"></i>
            </div>
            <div class="brand-title">
                <strong>LabSys</strong>
                <small>MAN 3 Banjar</small>
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

            <!-- 1. UTAMA -->
            <div class="menu-section">Menu Utama</div>

            <a href="<?= base_url('dashboard') ?>"
               class="menu-link <?= is_active_menu('dashboard',$current) ?>">
                <span class="menu-ico"><i class="bi bi-grid-1x2-fill"></i></span>
                <span>Dashboard</span>
            </a>

            <?php if(is_admin_panel()): ?>

                <div class="menu-section">Modul Madrasah</div>

                <!-- WEBSITE MADRASAH -->
                <?php if($canWebsite): ?>
                    <button class="menu-toggle <?= is_toggle_active(['berita','admin_website','admin_struktur'], $current) ?>"
                            type="button"
                            data-bs-toggle="collapse"
                            data-bs-target="#menuWebsite"
                            aria-expanded="<?= is_open_menu(['berita','admin_website','admin_struktur'], $current) ? 'true' : 'false' ?>">
                        <span class="menu-toggle-main">
                            <span class="menu-ico"><i class="bi bi-globe2"></i></span>
                            <span>Website Madrasah</span>
                        </span>
                        <i class="bi bi-chevron-down chev"></i>
                    </button>

                    <div class="collapse submenu <?= is_open_menu(['berita','admin_website','admin_struktur'], $current) ?>" id="menuWebsite">
						<a class="<?= is_active_menu('berita',$current) ?>"
						   href="<?= base_url('berita') ?>">
							<span class="sub-dot"></span>
							Kelola Berita
						</a>

						<a class="<?= is_active_menu('admin_website/profil',$current) ?>"
						   href="<?= base_url('admin_website/profil') ?>">
							<span class="sub-dot"></span>
							Profil Website
						</a>

                        <a class="<?= is_active_menu('admin_struktur',$current) ?>"
                           href="<?= base_url('admin_struktur') ?>">
                            <span class="sub-dot"></span>
                            Struktur Organisasi
                        </a>

						<a class="<?= is_active_menu('admin_website/video',$current) ?>"
						   href="<?= base_url('admin_website/video') ?>">
							<span class="sub-dot"></span>
							Video Profil
						</a>
						<a class="<?= is_active_menu('admin_website/pamflet',$current) ?>"
						   href="<?= base_url('admin_website/pamflet') ?>">
							<span class="sub-dot"></span>
							Pamflet Informasi
						</a>
						<a class="<?= is_active_menu('admin_website/ptk',$current) ?>"
						   href="<?= base_url('admin_website/ptk') ?>">
							<span class="sub-dot"></span>
							PTK Website
						</a>
						<a class="<?= is_active_menu('admin_website/tentang',$current) ?>"
						   href="<?= base_url('admin_website/tentang') ?>">
							<span class="sub-dot"></span>
							Tentang Madrasah
						</a>

						<a class="<?= is_active_menu('admin_website/galeri',$current) ?>"
						   href="<?= base_url('admin_website/galeri') ?>">
							<span class="sub-dot"></span>
							Galeri Madrasah
						</a>
						
						<a class="<?= is_active_menu('admin_website/download',$current) ?>"
						   href="<?= base_url('admin_website/download') ?>">
							<span class="sub-dot"></span>
							Data Download
						</a>
					</div>
                <?php endif; ?>

                <!-- TATA USAHA -->
                <?php if($canMasterMenu): ?>
                    <button class="menu-toggle <?= is_toggle_active(['admin_tata_usaha'], $current) ?>"
                            type="button"
                            data-bs-toggle="collapse"
                            data-bs-target="#menuTU"
                            aria-expanded="<?= is_open_menu(['admin_tata_usaha'], $current) ? 'true' : 'false' ?>">
                        <span class="menu-toggle-main">
                            <span class="menu-ico"><i class="bi bi-folder2-open"></i></span>
                            <span>Tata Usaha</span>
                        </span>
                        <i class="bi bi-chevron-down chev"></i>
                    </button>

                    <div class="collapse submenu <?= is_open_menu(['admin_tata_usaha'], $current) ?>" id="menuTU">
                        <a href="<?= base_url('admin_tata_usaha') ?>"
                           class="<?= $seg1 == 'admin_tata_usaha' && empty($seg2) ? 'active-menu' : '' ?>">
                            <span class="sub-dot"></span>
                            Dashboard TU
                        </a>

                        <a href="<?= base_url('admin_tata_usaha/surat') ?>"
                           class="<?= is_active_menu('admin_tata_usaha/surat',$current) ?>">
                            <span class="sub-dot"></span>
                            Surat
                        </a>

                        <a href="<?= base_url('admin_tata_usaha/izin_siswa') ?>"
                           class="<?= is_active_menu('admin_tata_usaha/izin_siswa',$current) ?>">
                            <span class="sub-dot"></span>
                            Izin Siswa
                        </a>

                        <a href="<?= base_url('admin_tata_usaha/izin_guru') ?>"
                           class="<?= is_active_menu('admin_tata_usaha/izin_guru',$current) ?>">
                            <span class="sub-dot"></span>
                            Izin Guru
                        </a>

                        <?php if($role == 'admin_master'): ?>
                            <a href="<?= base_url('kepala_cuti') ?>"
                               class="<?= is_active_menu('kepala_cuti',$current) ?>">
                                <span class="sub-dot"></span>
                                Approval Cuti
                            </a>
                        <?php else: ?>
                            <a href="<?= base_url('admin_tata_usaha/cuti_asn') ?>"
                               class="<?= is_active_menu('admin_tata_usaha/cuti_asn',$current) ?>">
                                <span class="sub-dot"></span>
                                Cuti ASN
                            </a>
                        <?php endif; ?>

                        <a href="<?= base_url('admin_tata_usaha/rekap') ?>"
                           class="<?= is_active_menu('admin_tata_usaha/rekap',$current) ?>">
                            <span class="sub-dot"></span>
                            Export Rekap TU
                        </a>
                    </div>
                <?php endif; ?>

                <!-- SARANA PRASARANA -->
                <?php if($canSarpras): ?>
                    <button class="menu-toggle <?= is_toggle_active(['admin_sarpras','inventory','maintenance','admin_tata_usaha/mutasi_barang','admin_tata_usaha/peminjaman_barang'], $current) ?>"
                            type="button"
                            data-bs-toggle="collapse"
                            data-bs-target="#menuSarpras"
                            aria-expanded="<?= is_open_menu(['admin_sarpras','inventory','maintenance','admin_tata_usaha/mutasi_barang','admin_tata_usaha/peminjaman_barang'], $current) ? 'true' : 'false' ?>">
                        <span class="menu-toggle-main">
                            <span class="menu-ico"><i class="bi bi-box-seam-fill"></i></span>
                            <span>Sarana Prasarana</span>
                        </span>
                        <i class="bi bi-chevron-down chev"></i>
                    </button>

                    <div class="collapse submenu <?= is_open_menu(['admin_sarpras','inventory','maintenance','admin_tata_usaha/mutasi_barang','admin_tata_usaha/peminjaman_barang'], $current) ?>" id="menuSarpras">
                        <a class="<?= ($current == 'admin_sarpras') ? 'active-menu' : '' ?>"
                           href="<?= base_url('admin_sarpras') ?>">
                            <span class="sub-dot"></span>
                            Inventaris Terpadu (Kelas &amp; Lab)
                        </a>

                        <a class="<?= is_active_menu('admin_sarpras/laporan_kerusakan',$current) ?>"
                           href="<?= base_url('admin_sarpras/laporan_kerusakan') ?>">
                            <span class="sub-dot"></span>
                            Laporan Kerusakan
                        </a>

                        <a class="<?= is_active_menu('inventory',$current) ?>"
                           href="<?= base_url('inventory') ?>">
                            <span class="sub-dot"></span>
                            Data Aset Lab
                        </a>

                        <a class="<?= is_active_menu('admin_tata_usaha/mutasi_barang',$current) ?>"
                           href="<?= base_url('admin_tata_usaha/mutasi_barang') ?>">
                            <span class="sub-dot"></span>
                            Mutasi Barang
                        </a>

                        <a class="<?= is_active_menu('admin_tata_usaha/peminjaman_barang',$current) ?>"
                           href="<?= base_url('admin_tata_usaha/peminjaman_barang') ?>">
                            <span class="sub-dot"></span>
                            Peminjaman Barang
                        </a>

                        <a class="<?= is_active_menu('maintenance',$current) ?>"
                           href="<?= base_url('maintenance') ?>">
                            <span class="sub-dot"></span>
                            Maintenance
                        </a>
                    </div>
                <?php endif; ?>

                <!-- KELOLA PMB -->
                <?php if($canPPDB): ?>
                    <button class="menu-toggle <?= is_toggle_active(['admin_ppdb'], $current) ?>"
                            type="button"
                            data-bs-toggle="collapse"
                            data-bs-target="#menuPPDB"
                            aria-expanded="<?= is_open_menu(['admin_ppdb'], $current) ? 'true' : 'false' ?>">
                        <span class="menu-toggle-main">
                            <span class="menu-ico"><i class="bi bi-mortarboard-fill"></i></span>
                            <span>Kelola PMB</span>
                        </span>
                        <i class="bi bi-chevron-down chev"></i>
                    </button>

                    <div class="collapse submenu <?= is_open_menu(['admin_ppdb'], $current) ?>" id="menuPPDB">
                        <a class="<?= is_active_menu('admin_ppdb/dashboard',$current) ?>"
                           href="<?= base_url('admin_ppdb/dashboard') ?>">
                            <span class="sub-dot"></span>
                            Dashboard PMB
                        </a>

                        <a class="<?= ($current == 'admin_ppdb') ? 'active-menu' : '' ?>"
                           href="<?= base_url('admin_ppdb') ?>">
                            <span class="sub-dot"></span>
                            Calon Peserta
                        </a>

                        <a class="<?= is_active_menu('admin_ppdb/verifikasi',$current) ?>"
                           href="<?= base_url('admin_ppdb/verifikasi') ?>">
                            <span class="sub-dot"></span>
                            Verifikasi Berkas
                        </a>
                        
                        <a class="<?= is_active_menu('admin_ppdb/monitoring_berkas',$current) ?>"
                           href="<?= base_url('admin_ppdb/monitoring_berkas') ?>">
                            <span class="sub-dot"></span>
                            Monitoring Berkas
                        </a>

                        <a class="<?= is_active_menu('admin_ppdb/diterima',$current) ?>"
                           href="<?= base_url('admin_ppdb/diterima') ?>">
                            <span class="sub-dot"></span>
                            Peserta Diterima
                        </a>

                        <a class="<?= is_active_menu('admin_ppdb/ditolak',$current) ?>"
                           href="<?= base_url('admin_ppdb/ditolak') ?>">
                            <span class="sub-dot"></span>
                            Peserta Ditolak
                        </a>

                        <a class="<?= is_active_menu('admin_ppdb/migrasi_data',$current) ?>"
                           href="<?= base_url('admin_ppdb/migrasi_data') ?>">
                            <span class="sub-dot"></span>
                            Migrasi Data
                        </a>

                        <a class="<?= is_active_menu('admin_ppdb/settings',$current) ?>"
                           href="<?= base_url('admin_ppdb/settings') ?>">
                            <span class="sub-dot"></span>
                            Pengaturan PMB
                        </a>
                    </div>
                <?php endif; ?>

                <!-- DATA PTK -->
                <?php if($canMasterMenu): ?>
                    <button class="menu-toggle <?= is_toggle_active(['admin_ptk','guru_mengajar','wali_absensi','kepala_rekap'], $current) ?>"
                            type="button"
                            data-bs-toggle="collapse"
                            data-bs-target="#menuPTK"
                            aria-expanded="<?= is_open_menu(['admin_ptk','guru_mengajar','wali_absensi','kepala_rekap'], $current) ? 'true' : 'false' ?>">
                        <span class="menu-toggle-main">
                            <span class="menu-ico"><i class="bi bi-person-badge-fill"></i></span>
                            <span>Data PTK</span>
                        </span>
                        <i class="bi bi-chevron-down chev"></i>
                    </button>

                    <div class="collapse submenu <?= is_open_menu(['admin_ptk','admin_ptk/akg','guru_mengajar','wali_absensi','kepala_rekap'], $current) ?>" id="menuPTK">
                        <a class="<?= ($current == 'admin_ptk') ? 'active-menu' : '' ?>"
                           href="<?= base_url('admin_ptk') ?>">
                            <span class="sub-dot"></span>
                            Daftar PTK
                        </a>
						<a class="<?= is_active_menu('admin_ptk/akg',$current) ?>"
                           href="<?= base_url('admin_ptk/akg') ?>">
                            <span class="sub-dot"></span>
                            Kebutuhan Guru (AKG)
                        </a>
						<a class="<?= is_active_menu('admin_jenis_berkas_ptk',$current) ?>"
                           href="<?= base_url('admin_jenis_berkas_ptk') ?>">
                            <span class="sub-dot"></span>
                            Jenis Berkas PTK
                        </a>
						<a class="<?= is_active_menu('admin_berkas_ptk',$current) ?>"
                           href="<?= base_url('admin_berkas_ptk') ?>">
                            <span class="sub-dot"></span>
                            Monitoring Berkas PTK
                        </a>
                        <a class="<?= is_active_menu('admin_ptk/wali_kelas',$current) ?>"
                           href="<?= base_url('admin_ptk/wali_kelas') ?>">
                            <span class="sub-dot"></span>
                            Wali Kelas
                        </a>

                        <a class="<?= is_active_menu('guru_mengajar',$current) ?>"
                           href="<?= base_url('guru_mengajar') ?>">
                            <span class="sub-dot"></span>
                            Kelas yang Diajar
                        </a>

                        <a class="<?= is_active_menu('wali_absensi',$current) ?>"
                           href="<?= base_url('wali_absensi') ?>">
                            <span class="sub-dot"></span>
                            Rekap Wali Kelas
                        </a>

                        <a class="<?= is_active_menu('kepala_rekap',$current) ?>"
                           href="<?= base_url('kepala_rekap') ?>">
                            <span class="sub-dot"></span>
                            Rekap Global
                        </a>
                    </div>
                <?php endif; ?>

                <!-- KURIKULUM -->
                <?php if($canKurikulum): ?>
                    <button class="menu-toggle <?= is_toggle_active(['admin_tugas_mengajar','admin_jadwal_mengajar','admin_mapel'], $current) ?>"
                            type="button"
                            data-bs-toggle="collapse"
                            data-bs-target="#menuKurikulum"
                            aria-expanded="<?= is_open_menu(['admin_tugas_mengajar','admin_jadwal_mengajar','admin_mapel'], $current) ? 'true' : 'false' ?>">
                        <span class="menu-toggle-main">
                            <span class="menu-ico"><i class="bi bi-journal-bookmark-fill"></i></span>
                            <span>Kurikulum</span>
                        </span>
                        <i class="bi bi-chevron-down chev"></i>
                    </button>

                    <div class="collapse submenu <?= is_open_menu(['admin_tugas_mengajar','admin_jadwal_mengajar','admin_mapel'], $current) ?>" id="menuKurikulum">
                        <a class="<?= is_active_menu('admin_mapel',$current) ?>"
                           href="<?= base_url('admin_mapel') ?>">
                            <span class="sub-dot"></span>
                            Data Mapel
                        </a>

                        <a class="<?= ($current == 'admin_tugas_mengajar') ? 'active-menu' : '' ?>"
                           href="<?= base_url('admin_tugas_mengajar') ?>">
                            <span class="sub-dot"></span>
                            Tugas Mengajar
                        </a>

                        <a class="<?= is_active_menu('admin_tugas_mengajar/bulk',$current) ?>"
                           href="<?= base_url('admin_tugas_mengajar/bulk') ?>">
                            <span class="sub-dot"></span>
                            Input Cepat Mengajar
                        </a>

                        <a class="<?= is_active_menu('admin_tugas_mengajar/rekap',$current) ?>"
                           href="<?= base_url('admin_tugas_mengajar/rekap') ?>">
                            <span class="sub-dot"></span>
                            Rekap Matriks Mengajar
                        </a>

                        <a class="<?= ($current == 'admin_jadwal_mengajar') ? 'active-menu' : '' ?>"
                           href="<?= base_url('admin_jadwal_mengajar') ?>">
                            <span class="sub-dot"></span>
                            Jadwal Mengajar
                        </a>

                        <a class="<?= is_active_menu('admin_jadwal_mengajar/builder',$current) ?>"
                           href="<?= base_url('admin_jadwal_mengajar/builder') ?>">
                            <span class="sub-dot"></span>
                            Jadwal Builder
                        </a>

                        <a class="<?= is_active_menu('admin_jadwal_mengajar/rekap',$current) ?>"
                           href="<?= base_url('admin_jadwal_mengajar/rekap') ?>">
                            <span class="sub-dot"></span>
                            Rekap Jadwal
                        </a>

                        <a class="<?= is_active_menu('admin_ptk/akg',$current) ?>"
                           href="<?= base_url('admin_ptk/akg') ?>">
                            <span class="sub-dot"></span>
                            Kebutuhan Guru (AKG)
                        </a>

                        <a class="<?= is_active_menu('admin_jadwal_mengajar/pengaturan',$current) ?>"
                           href="<?= base_url('admin_jadwal_mengajar/pengaturan') ?>">
                            <span class="sub-dot"></span>
                            Pengaturan Jadwal
                        </a>

                        <a class="<?= ($current == 'admin_laboratorium') ? 'active-menu' : '' ?>"
                           href="<?= base_url('admin_laboratorium') ?>">
                            <span class="sub-dot"></span>
                            Jadwal Laboratorium
                        </a>
                    </div>
                <?php endif; ?>

                <!-- DATA AKADEMIK -->
                <?php if($canAkademik): ?>
                    <button class="menu-toggle <?= is_toggle_active(['admin_siswa','admin_kelas','admin_penempatan','admin_mutasi','admin_alumni','admin_absensi','admin_nilai'], $current) ?>"
                            type="button"
                            data-bs-toggle="collapse"
                            data-bs-target="#menuAkademik"
                            aria-expanded="<?= is_open_menu(['admin_siswa','admin_kelas','admin_penempatan','admin_mutasi','admin_alumni','admin_absensi','admin_nilai'], $current) ? 'true' : 'false' ?>">
                        <span class="menu-toggle-main">
                            <span class="menu-ico"><i class="bi bi-book-half"></i></span>
                            <span>Data Akademik</span>
                        </span>
                        <i class="bi bi-chevron-down chev"></i>
                    </button>

                    <div class="collapse submenu <?= is_open_menu(['admin_siswa','admin_kelas','admin_penempatan','admin_mutasi','admin_alumni','admin_absensi','admin_nilai'], $current) ?>" id="menuAkademik">
                        <a class="<?= is_active_menu('admin_siswa',$current) ?>"
                           href="<?= base_url('admin_siswa') ?>">
                            <span class="sub-dot"></span>
                            Data Master Siswa
                        </a>

                        <a class="<?= ($current == 'admin_kelas') ? 'active-menu' : '' ?>"
                           href="<?= base_url('admin_kelas') ?>">
                            <span class="sub-dot"></span>
                            Data Master Kelas
                        </a>

                        <a class="<?= is_active_menu('admin_penempatan',$current) ?>"
                           href="<?= base_url('admin_penempatan') ?>">
                            <span class="sub-dot"></span>
                            Penempatan Siswa
                        </a>

                        <a href="<?= base_url('admin_absensi') ?>"
                           class="<?= $seg1 == 'admin_absensi' ? 'active-menu' : '' ?>">
                            <span class="sub-dot"></span>
                            Rekap Absensi
                        </a>

                        <a href="<?= base_url('admin_nilai') ?>"
                           class="<?= $seg1 == 'admin_nilai' ? 'active-menu' : '' ?>">
                            <span class="sub-dot"></span>
                            Monitoring Nilai
                        </a>

                        <a class="<?= is_active_menu('admin_mutasi',$current) ?>"
                           href="<?= base_url('admin_mutasi') ?>">
                            <span class="sub-dot"></span>
                            Mutasi Siswa
                        </a>

                        <a class="<?= is_active_menu('admin_alumni',$current) ?>"
                           href="<?= base_url('admin_alumni') ?>">
                            <span class="sub-dot"></span>
                            Data Alumni
                        </a>
                    </div>
                <?php endif; ?>

                <!-- SISTEM & INTEGRASI -->
                <div class="menu-section">Sistem &amp; Pengaturan</div>

                <!-- PENGATURAN -->
                <?php if($canMasterMenu): ?>
					<button class="menu-toggle <?= is_toggle_active(['admin_settings','admin_users'], $current) ?>"
							type="button"
							data-bs-toggle="collapse"
							data-bs-target="#menuPengaturan"
							aria-expanded="<?= is_open_menu(['admin_settings','admin_users'], $current) ? 'true' : 'false' ?>">
						<span class="menu-toggle-main">
							<span class="menu-ico"><i class="bi bi-gear-fill"></i></span>
							<span>Pengaturan</span>
						</span>
						<i class="bi bi-chevron-down chev"></i>
					</button>

					<div class="collapse submenu <?= is_open_menu(['admin_settings','admin_users'], $current) ?>" id="menuPengaturan">
						<a href="<?= base_url('admin_settings/periode_akademik') ?>"
						   class="<?= ($seg1 == 'admin_settings' && $seg2 == 'periode_akademik') ? 'active-menu' : '' ?>">
							<span class="sub-dot"></span>
							Periode Akademik
						</a>
						<a href="<?= base_url('admin_settings/kepala_madrasah') ?>"
						   class="<?= ($seg1 == 'admin_settings' && $seg2 == 'kepala_madrasah') ? 'active-menu' : '' ?>">
							<span class="sub-dot"></span>
							Setting Kepala Madrasah
						</a>
						<a href="<?= base_url('admin_users') ?>"
						   class="<?= $seg1 == 'admin_users' ? 'active-menu' : '' ?>">
							<span class="sub-dot"></span>
							Setting User
						</a>
						<a href="<?= base_url('admin_cctv') ?>"
						   class="<?= $seg1 == 'admin_cctv' ? 'active-menu' : '' ?>">
							<span class="sub-dot"></span>
							Kelola CCTV &amp; Kamera
						</a>
					</div>
                <?php endif; ?>

                <!-- SINKRONISASI CLOUD -->
                <?php if(is_admin_panel() && !in_array($role, ['admin_pmb', 'admin_ppdb'])): ?>
                    <a href="<?= base_url('admin_cloud_sync') ?>"
                       class="menu-link <?= is_active_menu('admin_cloud_sync',$current) ?>">
                        <span class="menu-ico"><i class="bi bi-clouds-fill"></i></span>
                        <span>Sinkronisasi Cloud</span>
                    </a>
                <?php endif; ?>

            <?php endif; ?>

            <!-- PORTAL GURU -->
            <?php if($role == 'guru'): ?>
                <div class="menu-section">Portal Guru</div>

                <a href="<?= base_url('booking') ?>"
                   class="menu-link <?= is_active_menu('booking',$current) ?>">
                    <span class="menu-ico"><i class="bi bi-calendar2-check-fill"></i></span>
                    <span>Booking Lab</span>
                </a>

                <a href="<?= base_url('maintenance') ?>"
                   class="menu-link <?= is_active_menu('maintenance',$current) ?>">
                    <span class="menu-ico"><i class="bi bi-exclamation-triangle-fill"></i></span>
                    <span>Lapor Kerusakan</span>
                </a>
            <?php endif; ?>

            <!-- PORTAL TEKNISI -->
            <?php if($role == 'teknisi'): ?>
                <div class="menu-section">Portal Teknisi</div>

                <a href="<?= base_url('maintenance') ?>"
                   class="menu-link <?= is_active_menu('maintenance',$current) ?>">
                    <span class="menu-ico"><i class="bi bi-tools"></i></span>
                    <span>Maintenance</span>
                </a>

                <a href="<?= base_url('inventory') ?>"
                   class="menu-link <?= is_active_menu('inventory',$current) ?>">
                    <span class="menu-ico"><i class="bi bi-boxes"></i></span>
                    <span>Inventaris</span>
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