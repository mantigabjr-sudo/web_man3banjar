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
| Kalau helper access sudah dibuat, fungsi ini tidak akan bentrok.
| Kalau belum dibuat, sidebar tetap aman jalan.
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
    --side-w:292px;
    --side-dark:#0f172a;
    --side-muted:#64748b;
    --side-border:#e2e8f0;
    --side-green:#16a34a;
    --side-green-dark:#14532d;
    --side-soft:#ecfdf5;
}

.sidebar{
    position:fixed;
    left:0;
    top:0;
    bottom:0;
    width:var(--side-w);
    z-index:1040;
    background:
        radial-gradient(circle at top right, rgba(34,197,94,.13), transparent 34%),
        rgba(255,255,255,.96);
    backdrop-filter:blur(20px);
    border-right:1px solid rgba(226,232,240,.9);
    box-shadow:18px 0 55px rgba(15,23,42,.08);
    display:flex;
    flex-direction:column;
    overflow:hidden;
}

.sidebar-inner{
    height:100%;
    display:flex;
    flex-direction:column;
    padding:18px;
    overflow:hidden;
}

.sidebar-brand{
    display:flex;
    align-items:center;
    gap:12px;
    padding:12px;
    border-radius:24px;
    background:
        radial-gradient(circle at top right, rgba(255,255,255,.22), transparent 34%),
        linear-gradient(135deg,#14532d,#16a34a,#22c55e);
    color:white;
    box-shadow:0 16px 34px rgba(22,163,74,.25);
    margin-bottom:14px;
}

.brand-icon{
    width:52px;
    height:52px;
    border-radius:20px;
    background:rgba(255,255,255,.20);
    border:1px solid rgba(255,255,255,.30);
    display:flex;
    align-items:center;
    justify-content:center;
    font-size:22px;
    font-weight:950;
    flex-shrink:0;
}

.brand-title strong{
    display:block;
    font-size:20px;
    line-height:1.05;
    font-weight:950;
}

.brand-title small{
    display:block;
    margin-top:4px;
    color:rgba(255,255,255,.84);
    font-size:12px;
    font-weight:800;
}

.sidebar-user{
    display:flex;
    align-items:center;
    gap:12px;
    padding:14px;
    border-radius:22px;
    background:#f8fafc;
    border:1px solid var(--side-border);
    margin-bottom:14px;
}

.user-mini-avatar{
    width:42px;
    height:42px;
    border-radius:17px;
    background:#dcfce7;
    color:#166534;
    display:flex;
    align-items:center;
    justify-content:center;
    font-weight:950;
    flex-shrink:0;
}

.user-mini-info strong{
    display:block;
    color:#0f172a;
    font-size:13px;
    font-weight:950;
    line-height:1.25;
}

.user-mini-info small{
    display:block;
    color:#64748b;
    font-size:12px;
    font-weight:800;
    margin-top:2px;
}

.sidebar-scroll{
    flex:1;
    overflow-y:auto;
    padding-right:4px;
}

.sidebar-scroll::-webkit-scrollbar{
    width:6px;
}

.sidebar-scroll::-webkit-scrollbar-thumb{
    background:#cbd5e1;
    border-radius:999px;
}

.menu-section{
    margin:18px 10px 8px;
    color:#94a3b8;
    font-size:11px;
    font-weight:950;
    letter-spacing:.8px;
    text-transform:uppercase;
}

.menu-link,
.menu-toggle{
    width:100%;
    min-height:46px;
    display:flex;
    align-items:center;
    gap:11px;
    padding:11px 13px;
    border-radius:17px;
    color:#334155;
    background:transparent;
    border:0;
    text-decoration:none;
    font-size:14px;
    font-weight:850;
    transition:.18s ease;
    position:relative;
}

.menu-link:hover,
.menu-toggle:hover{
    color:#166534;
    background:#ecfdf5;
}

.menu-link.active-menu{
    color:#166534;
    background:#dcfce7;
    box-shadow:inset 0 0 0 1px #bbf7d0;
}

.menu-link.active-menu:before{
    content:"";
    position:absolute;
    left:-18px;
    top:12px;
    bottom:12px;
    width:4px;
    border-radius:999px;
    background:#16a34a;
}

.menu-toggle{
    justify-content:space-between;
    cursor:pointer;
}

.menu-toggle-main{
    display:flex;
    align-items:center;
    gap:11px;
}

.menu-toggle.active-toggle,
.menu-toggle[aria-expanded="true"]{
    color:#166534;
    background:#ecfdf5;
}

.menu-ico{
    width:28px;
    height:28px;
    border-radius:12px;
    background:#f1f5f9;
    color:#166534;
    display:flex;
    align-items:center;
    justify-content:center;
    font-size:12px;
    font-weight:950;
    flex-shrink:0;
}

.menu-link.active-menu .menu-ico,
.menu-toggle.active-toggle .menu-ico,
.menu-toggle[aria-expanded="true"] .menu-ico{
    background:#bbf7d0;
}

.chev{
    transition:.18s ease;
    color:#94a3b8;
    font-weight:950;
}

.menu-toggle[aria-expanded="true"] .chev{
    transform:rotate(180deg);
    color:#16a34a;
}

.submenu{
    margin:5px 0 6px 38px;
    padding:5px 0 5px 10px;
    border-left:1px dashed #cbd5e1;
}

.submenu a{
    min-height:38px;
    display:flex;
    align-items:center;
    gap:9px;
    padding:8px 11px;
    border-radius:14px;
    color:#475569;
    text-decoration:none;
    font-size:13px;
    font-weight:800;
    transition:.18s ease;
}

.submenu a:hover{
    color:#166534;
    background:#f0fdf4;
}

.submenu a.active-menu{
    color:#166534;
    background:#dcfce7;
}

.sub-dot{
    width:7px;
    height:7px;
    border-radius:50%;
    background:#cbd5e1;
    flex-shrink:0;
}

.submenu a.active-menu .sub-dot,
.submenu a:hover .sub-dot{
    background:#16a34a;
}

.sidebar-footer{
    padding-top:14px;
    border-top:1px solid var(--side-border);
    margin-top:14px;
}

.logout-link{
    color:#991b1b;
}

.logout-link .menu-ico{
    background:#fee2e2;
    color:#991b1b;
}

.logout-link:hover{
    color:#991b1b;
    background:#fee2e2;
}

.admin-mobile-topbar{
    display:none;
}

.sidebar-backdrop{
    display:none;
}

.content{
    margin-left:var(--side-w);
}

@media(max-width:991px){
    .content{
        margin-left:0;
        padding-top:76px;
    }

    .admin-mobile-topbar{
        position:fixed;
        top:0;
        left:0;
        right:0;
        height:64px;
        z-index:1035;
        display:flex;
        align-items:center;
        justify-content:space-between;
        gap:12px;
        padding:10px 14px;
        background:rgba(255,255,255,.92);
        backdrop-filter:blur(18px);
        border-bottom:1px solid rgba(226,232,240,.9);
        box-shadow:0 10px 30px rgba(15,23,42,.06);
    }

    .mobile-brand{
        display:flex;
        align-items:center;
        gap:10px;
        color:#14532d;
        font-weight:950;
    }

    .mobile-brand span{
        width:38px;
        height:38px;
        border-radius:15px;
        background:linear-gradient(135deg,#14532d,#22c55e);
        color:white;
        display:flex;
        align-items:center;
        justify-content:center;
        font-weight:950;
    }

    .sidebar-open-btn{
        width:42px;
        height:42px;
        border:0;
        border-radius:16px;
        background:#ecfdf5;
        color:#166534;
        font-size:22px;
        font-weight:950;
    }

    .sidebar{
        transform:translateX(-105%);
        transition:.25s ease;
    }

    .sidebar.sidebar-show{
        transform:translateX(0);
    }

    .sidebar-backdrop{
        position:fixed;
        inset:0;
        z-index:1039;
        background:rgba(15,23,42,.45);
        backdrop-filter:blur(2px);
    }

    .sidebar-backdrop.show{
        display:block;
    }
}
</style>

<div class="admin-mobile-topbar">
    <div class="mobile-brand">
        <span>L</span>
        <div>
            LabSys
            <div style="font-size:11px;color:#64748b;font-weight:800;line-height:1;">
                MAN 3 Banjar
            </div>
        </div>
    </div>

    <button type="button" class="sidebar-open-btn" id="sidebarOpenBtn">
        ☰
    </button>
</div>

<div class="sidebar-backdrop" id="sidebarBackdrop"></div>

<aside class="sidebar" id="adminSidebar">
    <div class="sidebar-inner">

        <div class="sidebar-brand">
            <div class="brand-icon">L</div>
            <div class="brand-title">
                <strong>LabSys</strong>
                <small>MAN 3 Banjar</small>
            </div>
        </div>

        <div class="sidebar-user">
            <div class="user-mini-avatar">
                <?= $userInitial ?>
            </div>
            <div class="user-mini-info">
                <strong><?= !empty($username) ? $username : 'Administrator' ?></strong>
                <small><?= $roleText ?></small>
            </div>
        </div>

        <div class="sidebar-scroll">

            <div class="menu-section">Main Menu</div>

            <a href="<?= base_url('dashboard') ?>"
               class="menu-link <?= is_active_menu('dashboard',$current) ?>">
                <span class="menu-ico">⌂</span>
                <span>Dashboard</span>
            </a>

            <?php if(is_admin_panel()): ?>

                <?php if($canWebsite): ?>
                    <div class="menu-section">Website</div>

                    <button class="menu-toggle <?= is_toggle_active(['berita','admin_website','admin_struktur'], $current) ?>"
                            type="button"
                            data-bs-toggle="collapse"
                            data-bs-target="#menuWebsite"
                            aria-expanded="<?= is_open_menu(['berita','admin_website','admin_struktur'], $current) ? 'true' : 'false' ?>">
                        <span class="menu-toggle-main">
                            <span class="menu-ico">WB</span>
                            <span>Website Madrasah</span>
                        </span>
                        <span class="chev">⌄</span>
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


                <?php if($canMasterMenu): ?>
                    <div class="menu-section">Tata Usaha</div>

                    <button class="menu-toggle <?= is_toggle_active(['admin_tata_usaha'], $current) ?>"
                            type="button"
                            data-bs-toggle="collapse"
                            data-bs-target="#menuTU"
                            aria-expanded="<?= is_open_menu(['admin_tata_usaha'], $current) ? 'true' : 'false' ?>">
                        <span class="menu-toggle-main">
                            <span class="menu-ico">TU</span>
                            <span>Tata Usaha</span>
                        </span>
                        <span class="chev">⌄</span>
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


                <?php if($canSarpras): ?>
                    <div class="menu-section">Sarpras</div>

                    <button class="menu-toggle <?= is_toggle_active(['admin_sarpras','inventory','maintenance','admin_tata_usaha/mutasi_barang','admin_tata_usaha/peminjaman_barang'], $current) ?>"
                            type="button"
                            data-bs-toggle="collapse"
                            data-bs-target="#menuSarpras"
                            aria-expanded="<?= is_open_menu(['admin_sarpras','inventory','maintenance','admin_tata_usaha/mutasi_barang','admin_tata_usaha/peminjaman_barang'], $current) ? 'true' : 'false' ?>">
                        <span class="menu-toggle-main">
                            <span class="menu-ico">SP</span>
                            <span>Sarana Prasarana</span>
                        </span>
                        <span class="chev">⌄</span>
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


                <?php if($canPPDB): ?>
                    <div class="menu-section">PMB</div>

                    <button class="menu-toggle <?= is_toggle_active(['admin_ppdb'], $current) ?>"
                            type="button"
                            data-bs-toggle="collapse"
                            data-bs-target="#menuPPDB"
                            aria-expanded="<?= is_open_menu(['admin_ppdb'], $current) ? 'true' : 'false' ?>">
                        <span class="menu-toggle-main">
                            <span class="menu-ico">PM</span>
                            <span>Kelola PMB</span>
                        </span>
                        <span class="chev">⌄</span>
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


                <?php if($canMasterMenu): ?>
                    <div class="menu-section">PTK</div>

                    <button class="menu-toggle <?= is_toggle_active(['admin_ptk','guru_mengajar','wali_absensi','kepala_rekap'], $current) ?>"
                            type="button"
                            data-bs-toggle="collapse"
                            data-bs-target="#menuPTK"
                            aria-expanded="<?= is_open_menu(['admin_ptk','guru_mengajar','wali_absensi','kepala_rekap'], $current) ? 'true' : 'false' ?>">
                        <span class="menu-toggle-main">
                            <span class="menu-ico">PT</span>
                            <span>Data PTK</span>
                        </span>
                        <span class="chev">⌄</span>
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


                <?php if($canKurikulum): ?>
                    <div class="menu-section">Kurikulum</div>

                    <button class="menu-toggle <?= is_toggle_active(['admin_tugas_mengajar','admin_jadwal_mengajar','admin_mapel'], $current) ?>"
                            type="button"
                            data-bs-toggle="collapse"
                            data-bs-target="#menuKurikulum"
                            aria-expanded="<?= is_open_menu(['admin_tugas_mengajar','admin_jadwal_mengajar','admin_mapel'], $current) ? 'true' : 'false' ?>">
                        <span class="menu-toggle-main">
                            <span class="menu-ico">KR</span>
                            <span>Kurikulum</span>
                        </span>
                        <span class="chev">⌄</span>
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


                <?php if($canAkademik): ?>
                    <div class="menu-section">Akademik</div>

                    <button class="menu-toggle <?= is_toggle_active(['admin_siswa','admin_kelas','admin_penempatan','admin_mutasi','admin_alumni','admin_absensi','admin_nilai'], $current) ?>"
                            type="button"
                            data-bs-toggle="collapse"
                            data-bs-target="#menuAkademik"
                            aria-expanded="<?= is_open_menu(['admin_siswa','admin_kelas','admin_penempatan','admin_mutasi','admin_alumni','admin_absensi','admin_nilai'], $current) ? 'true' : 'false' ?>">
                        <span class="menu-toggle-main">
                            <span class="menu-ico">AK</span>
                            <span>Data Akademik</span>
                        </span>
                        <span class="chev">⌄</span>
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


                <?php if($canMasterMenu): ?>
                    <div class="menu-section">Pengaturan</div>

					<button class="menu-toggle <?= is_toggle_active(['admin_settings','admin_users'], $current) ?>"
							type="button"
							data-bs-toggle="collapse"
							data-bs-target="#menuPengaturan"
							aria-expanded="<?= is_open_menu(['admin_settings','admin_users'], $current) ? 'true' : 'false' ?>">
						<span class="menu-toggle-main">
							<span class="menu-ico">⚙</span>
							<span>Pengaturan</span>
						</span>
						<span class="chev">⌄</span>
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

            <?php endif; ?>


            <?php if($role == 'guru'): ?>

                <div class="menu-section">Guru</div>

                <a href="<?= base_url('booking') ?>"
                   class="menu-link <?= is_active_menu('booking',$current) ?>">
                    <span class="menu-ico">BL</span>
                    <span>Booking Lab</span>
                </a>

                <a href="<?= base_url('maintenance') ?>"
                   class="menu-link <?= is_active_menu('maintenance',$current) ?>">
                    <span class="menu-ico">LP</span>
                    <span>Lapor Kerusakan</span>
                </a>

            <?php endif; ?>


            <?php if($role == 'teknisi'): ?>

                <div class="menu-section">Teknisi</div>

                <a href="<?= base_url('maintenance') ?>"
                   class="menu-link <?= is_active_menu('maintenance',$current) ?>">
                    <span class="menu-ico">MT</span>
                    <span>Maintenance</span>
                </a>

                <a href="<?= base_url('inventory') ?>"
                   class="menu-link <?= is_active_menu('inventory',$current) ?>">
                    <span class="menu-ico">IN</span>
                    <span>Inventaris</span>
                </a>

            <?php endif; ?>

        </div>

        <div class="sidebar-footer">
            <a href="<?= base_url('auth/logout') ?>" class="menu-link logout-link">
                <span class="menu-ico">⎋</span>
                <span>Logout</span>
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