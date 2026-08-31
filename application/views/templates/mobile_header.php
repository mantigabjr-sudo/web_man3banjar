
<?php
$seg1 = $this->uri->segment(1);

$ptk_id_header = $this->session->userdata('ptk_id');

if(!isset($tahun_ajaran) || !isset($semester)){
    $setting_header = $this->db->get('settings')->row();

    if(!isset($tahun_ajaran)){
        $tahun_ajaran = ($setting_header && !empty($setting_header->tahun_ajaran))
            ? $setting_header->tahun_ajaran
            : date('Y').'-'.(date('Y') + 1);
    }

    if(!isset($semester)){
        $semester = ($setting_header && !empty($setting_header->semester_aktif))
            ? $setting_header->semester_aktif
            : 'Ganjil';
    }
}

if(!isset($can_jadwal_mengajar) || !isset($can_absensi_mengajar) || !isset($can_input_nilai)){

    $jumlah_mengajar_header = 0;

    if(!empty($ptk_id_header)){
        $jumlah_mengajar_header = $this->db
            ->where('ptk_id',$ptk_id_header)
            ->where('tahun_ajaran',$tahun_ajaran)
            ->where('semester',$semester)
            ->where('status','Aktif')
            ->count_all_results('tugas_mengajar');
    }

    if(!isset($can_jadwal_mengajar)){
        $can_jadwal_mengajar = $jumlah_mengajar_header > 0;
    }

    if(!isset($can_absensi_mengajar)){
        $can_absensi_mengajar = $jumlah_mengajar_header > 0;
    }

    if(!isset($can_input_nilai)){
        $can_input_nilai = $jumlah_mengajar_header > 0;
    }
}

if(!isset($can_tata_usaha)){
    $can_tata_usaha = false;
}

if(!isset($is_wali_kelas)){

    $is_wali_kelas = false;

    if(!empty($ptk_id_header) && $this->db->table_exists('wali_kelas')){
        $is_wali_kelas = $this->db
            ->where('ptk_id',$ptk_id_header)
            ->where('tahun_ajaran',$tahun_ajaran)
            ->where('status','Aktif')
            ->count_all_results('wali_kelas') > 0;
    }
}

if(!isset($is_kurikulum)){
    $is_kurikulum = false;
}

if(!isset($is_kepala)){
    $is_kepala = false;
}

$nama_header = isset($ptk) && $ptk ? $ptk->nama_lengkap : 'User';
$inisial_header = isset($ptk) && $ptk ? strtoupper(substr($ptk->nama_lengkap,0,1)) : 'U';
?>
<?php
$seg1 = $this->uri->segment(1);
$seg2 = $this->uri->segment(2);

$ptk_id_nav = $this->session->userdata('ptk_id');

if(!function_exists('desktop_bk_text_match')){
    function desktop_bk_text_match($text){

        $text = strtolower((string)$text);
        $compact = preg_replace('/[^a-z0-9]/', '', $text);

        if(in_array($compact, ['bk', 'bp', 'bkbp'])){
            return true;
        }

        if(strpos($compact, 'bk') !== false){
            return true;
        }

        if(strpos($compact, 'bp') !== false){
            return true;
        }

        if(strpos($compact, 'bkbp') !== false){
            return true;
        }

        if(strpos($compact, 'bimbingankonseling') !== false){
            return true;
        }

        if(strpos($compact, 'bimbinganpenyuluhan') !== false){
            return true;
        }

        if(strpos($text, 'bimbingan konseling') !== false){
            return true;
        }

        if(strpos($text, 'bimbingan dan konseling') !== false){
            return true;
        }

        if(strpos($text, 'konselor') !== false){
            return true;
        }

        return false;
    }
}

$is_bk_bp = false;

if(!empty($ptk_id_nav)){

    /*
     * Cek dari data PTK.
     */
    if($this->db->table_exists('ptk')){

        $ptk_nav = $this->db
            ->where('id', $ptk_id_nav)
            ->get('ptk')
            ->row();

        if($ptk_nav){

            $text_ptk = '';
            $text_ptk .= ' '.($ptk_nav->jabatan ?? '');
            $text_ptk .= ' '.($ptk_nav->jenis_ptk ?? '');
            $text_ptk .= ' '.($ptk_nav->tugas_utama ?? '');
            $text_ptk .= ' '.($ptk_nav->tugas_tambahan ?? '');

            if(desktop_bk_text_match($text_ptk)){
                $is_bk_bp = true;
            }
        }
    }

    /*
     * Cek dari mapel tugas mengajar.
     * Sengaja tidak difilter tahun/semester dulu agar tetap terbaca.
     */
    if(!$is_bk_bp && $this->db->table_exists('tugas_mengajar') && $this->db->table_exists('mapel')){

        $select = [
            'mapel.nama_mapel'
        ];

        if($this->db->field_exists('kode_mapel', 'mapel')){
            $select[] = 'mapel.kode_mapel';
        }

        if($this->db->field_exists('kelompok', 'mapel')){
            $select[] = 'mapel.kelompok';
        }

        $mapel_bk = $this->db
            ->select(implode(',', $select))
            ->from('tugas_mengajar')
            ->join('mapel', 'mapel.id = tugas_mengajar.mapel_id', 'left')
            ->where('tugas_mengajar.ptk_id', $ptk_id_nav)
            ->where('tugas_mengajar.status', 'Aktif')
            ->get()
            ->result();

        foreach($mapel_bk as $m){

            $text_mapel = '';
            $text_mapel .= ' '.($m->nama_mapel ?? '');
            $text_mapel .= ' '.($m->kode_mapel ?? '');
            $text_mapel .= ' '.($m->kelompok ?? '');

            if(desktop_bk_text_match($text_mapel)){
                $is_bk_bp = true;
                break;
            }
        }
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>LabSys User</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.8/css/dataTables.bootstrap5.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.bootstrap5.min.css">

    <style>
        :root{
            --primary:#16a34a;
            --primary-2:#22c55e;
            --primary-dark:#14532d;
            --soft:#ecfdf5;
            --bg:#f3f7fb;
            --card:#ffffff;
            --text:#0f172a;
            --muted:#64748b;
            --border:#e2e8f0;
            --danger:#ef4444;
            --warning:#f59e0b;
            --info:#0ea5e9;
        }

        *{
            box-sizing:border-box;
        }

        body{
            margin:0;
            min-height:100vh;
            background:
                radial-gradient(circle at top left, rgba(34,197,94,.18), transparent 32%),
                radial-gradient(circle at top right, rgba(14,165,233,.12), transparent 30%),
                linear-gradient(135deg,#eef2ff,#f8fafc 48%,#ecfdf5);
            font-family:Inter, system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif;
            color:var(--text);
        }

        a{
            text-decoration:none;
        }

        .user-shell{
            min-height:100vh;
        }

        .user-layout{
            min-height:100vh;
            display:block;
        }

        .user-sidebar{
            display:none;
        }

        .user-main{
            min-height:100vh;
            padding-bottom:86px;
        }

        .glass-panel{
            background:rgba(255,255,255,.78);
            backdrop-filter:blur(18px);
            border:1px solid rgba(255,255,255,.7);
            box-shadow:0 18px 50px rgba(15,23,42,.08);
        }

        .mobile-topbar{
            position:relative;
            overflow:hidden;
            color:white;
            padding:24px 18px 28px;
            border-radius:0 0 34px 34px;
            background:
                radial-gradient(circle at 20% 20%, rgba(255,255,255,.22), transparent 24%),
                linear-gradient(135deg,#14532d,#16a34a 50%,#22c55e);
            box-shadow:0 16px 40px rgba(22,163,74,.25);
        }

        .mobile-topbar:after{
            content:"";
            position:absolute;
            right:-45px;
            top:-45px;
            width:150px;
            height:150px;
            border-radius:50%;
            background:rgba(255,255,255,.12);
        }

        .topbar-row{
            position:relative;
            z-index:1;
            display:flex;
            justify-content:space-between;
            align-items:flex-start;
            gap:14px;
        }

        .user-avatar{
            width:52px;
            height:52px;
            border-radius:20px;
            background:rgba(255,255,255,.20);
            border:1px solid rgba(255,255,255,.35);
            display:flex;
            align-items:center;
            justify-content:center;
            color:white;
            font-size:22px;
            font-weight:900;
            flex-shrink:0;
        }

        .mobile-topbar h4,
        .mobile-topbar h5{
            margin:0;
            font-weight:900;
            letter-spacing:-.3px;
        }

        .mobile-topbar h4{
            font-size:21px;
            line-height:1.18;
        }

        .mobile-topbar h5{
            font-size:14px;
            opacity:.92;
            margin-bottom:4px;
        }

        .period-pill{
            display:inline-flex;
            align-items:center;
            gap:6px;
            margin-top:10px;
            padding:7px 11px;
            border-radius:999px;
            background:rgba(255,255,255,.18);
            border:1px solid rgba(255,255,255,.28);
            color:white;
            font-size:12px;
            font-weight:800;
        }

        .mobile-content{
            padding:18px;
        }

        .app-page-title{
            margin:4px 0 18px;
        }

        .app-page-title h3{
            font-weight:900;
            margin:0;
            letter-spacing:-.4px;
        }

        .app-page-title p{
            color:var(--muted);
            margin:5px 0 0;
        }

        .mobile-card{
            background:rgba(255,255,255,.86);
            backdrop-filter:blur(18px);
            border:1px solid rgba(226,232,240,.9);
            border-radius:28px;
            padding:18px;
            box-shadow:0 18px 45px rgba(15,23,42,.07);
            margin-bottom:16px;
        }

        .section-title{
            display:flex;
            justify-content:space-between;
            align-items:center;
            gap:12px;
            margin-bottom:14px;
        }

        .section-title h6{
            margin:0;
            font-size:15px;
            font-weight:900;
            color:#14532d;
        }

        .section-title small{
            color:var(--muted);
            font-weight:700;
        }

        .mobile-menu-grid{
            display:grid;
            grid-template-columns:repeat(2,1fr);
            gap:12px;
        }

        .app-menu-card{
            position:relative;
            overflow:hidden;
            min-height:118px;
            background:
                radial-gradient(circle at top right, rgba(34,197,94,.18), transparent 35%),
                linear-gradient(135deg,#ffffff,#f8fafc);
            border:1px solid var(--border);
            border-radius:26px;
            padding:16px;
            color:var(--text);
            box-shadow:0 14px 34px rgba(15,23,42,.07);
            transition:.22s;
        }

        .app-menu-card:hover{
            transform:translateY(-3px);
            color:var(--text);
            box-shadow:0 20px 42px rgba(15,23,42,.11);
            border-color:#bbf7d0;
        }

        .app-menu-icon{
            width:42px;
            height:42px;
            border-radius:17px;
            background:linear-gradient(135deg,#dcfce7,#ffffff);
            border:1px solid #bbf7d0;
            display:flex;
            align-items:center;
            justify-content:center;
            color:#15803d;
            font-weight:900;
            margin-bottom:12px;
        }

        .app-menu-card strong{
            display:block;
            color:#0f172a;
            font-size:14px;
            font-weight:900;
            margin-bottom:4px;
        }

        .app-menu-card small{
            display:block;
            color:var(--muted);
            font-size:12px;
            line-height:1.35;
        }

        .status-chip{
            display:inline-flex;
            align-items:center;
            padding:6px 10px;
            border-radius:999px;
            font-size:12px;
            font-weight:900;
        }

        .chip-success{
            background:#dcfce7;
            color:#166534;
        }

        .chip-warning{
            background:#fef3c7;
            color:#92400e;
        }

        .chip-danger{
            background:#fee2e2;
            color:#991b1b;
        }

        .schedule-item{
            display:flex;
            justify-content:space-between;
            align-items:center;
            gap:12px;
            padding:14px;
            border-radius:22px;
            background:#f8fafc;
            border:1px solid var(--border);
            margin-bottom:10px;
        }

        .schedule-item:last-child{
            margin-bottom:0;
        }

        .schedule-item strong{
            color:#0f172a;
            font-weight:900;
        }

        .schedule-item small{
            color:var(--muted);
            font-weight:600;
        }

        .jam-badge{
            min-width:52px;
            padding:8px 10px;
            border-radius:16px;
            text-align:center;
            background:#ecfdf5;
            color:#166534;
            font-weight:900;
            font-size:12px;
            border:1px solid #bbf7d0;
        }

        .responsive-grid{
            display:grid;
            grid-template-columns:1fr;
            gap:14px;
        }

        .mobile-bottom-nav{
            position:fixed;
            bottom:0;
            left:0;
            right:0;
            z-index:999;
            padding:10px 12px 14px;
            background:linear-gradient(180deg,rgba(248,250,252,0),rgba(248,250,252,.96) 28%,rgba(248,250,252,.98));
        }

        .mobile-bottom-inner{
            max-width:560px;
            margin:0 auto;
            display:grid;
            grid-template-columns:repeat(5,1fr);
            gap:6px;
            padding:8px;
            border-radius:26px;
            background:rgba(255,255,255,.92);
            backdrop-filter:blur(18px);
            border:1px solid rgba(226,232,240,.9);
            box-shadow:0 -8px 32px rgba(15,23,42,.10);
        }

        .mobile-nav-item{
            text-align:center;
            padding:9px 3px;
            color:#64748b;
            border-radius:18px;
            font-size:10px;
            font-weight:900;
            transition:.2s;
        }

        .mobile-nav-item span{
            display:block;
            font-size:18px;
            line-height:1;
            margin-bottom:3px;
        }

        .mobile-nav-item.active{
            color:#166534;
            background:#ecfdf5;
        }

        @media(min-width:768px){
            .user-shell{
                max-width:960px;
                margin:22px auto;
                border-radius:34px;
                overflow:hidden;
                box-shadow:0 24px 70px rgba(15,23,42,.16);
                background:rgba(255,255,255,.35);
            }

            .mobile-topbar{
                border-radius:0 0 38px 38px;
                padding:30px;
            }

            .mobile-content{
                padding:26px;
            }

            .mobile-menu-grid{
                grid-template-columns:repeat(3,1fr);
            }

            .responsive-grid{
                grid-template-columns:repeat(2,1fr);
            }
        }

        @media(min-width:1024px){
            .user-shell{
                max-width:none;
                margin:0;
                border-radius:0;
                box-shadow:none;
                background:transparent;
            }

            .user-layout{
                display:grid;
                grid-template-columns:292px 1fr;
                min-height:100vh;
            }

            .user-sidebar{
                display:flex;
                flex-direction:column;
                position:sticky;
                top:0;
                height:100vh;
                padding:22px;
                background:rgba(255,255,255,.86);
                backdrop-filter:blur(24px);
                border-right:1px solid rgba(226,232,240,.86);
                box-shadow:12px 0 50px rgba(15,23,42,.05);
                overflow-y:auto;
            }

            .brand-box{
                display:flex;
                align-items:center;
                gap:12px;
                margin-bottom:22px;
            }

            .brand-logo{
                width:50px;
                height:50px;
                border-radius:20px;
                background:linear-gradient(135deg,#14532d,#22c55e);
                color:white;
                display:flex;
                align-items:center;
                justify-content:center;
                font-weight:900;
                box-shadow:0 14px 30px rgba(22,163,74,.25);
            }

            .brand-text strong{
                display:block;
                font-size:18px;
                font-weight:900;
                line-height:1.1;
            }

            .brand-text small{
                color:var(--muted);
                font-weight:700;
            }

            .side-profile{
                padding:16px;
                border-radius:24px;
                background:
                    radial-gradient(circle at top right, rgba(34,197,94,.20), transparent 35%),
                    #f8fafc;
                border:1px solid var(--border);
                margin-bottom:18px;
            }

            .side-profile strong{
                display:block;
                color:#14532d;
                font-weight:900;
                line-height:1.25;
            }

            .side-profile small{
                color:var(--muted);
                font-weight:700;
            }

            .desktop-menu-label{
                margin:8px 12px 7px;
                color:#94a3b8;
                font-size:11px;
                font-weight:900;
                text-transform:uppercase;
                letter-spacing:.5px;
            }

            .desktop-menu{
                display:flex;
                flex-direction:column;
                gap:7px;
            }

            .desktop-menu a{
                display:flex;
                align-items:center;
                gap:10px;
                color:#334155;
                font-weight:850;
                padding:12px 14px;
                border-radius:18px;
                transition:.2s;
            }

            .desktop-menu a:hover,
            .desktop-menu a.active{
                background:#ecfdf5;
                color:#166534;
            }

            .desktop-menu .mi{
                width:26px;
                height:26px;
                border-radius:11px;
                display:flex;
                align-items:center;
                justify-content:center;
                background:#f8fafc;
                border:1px solid #e2e8f0;
                text-align:center;
                font-size:14px;
            }

            .desktop-menu a.active .mi,
            .desktop-menu a:hover .mi{
                background:#dcfce7;
                border-color:#bbf7d0;
                color:#166534;
            }

            .side-footer{
                margin-top:auto;
                padding-top:16px;
                border-top:1px solid var(--border);
            }

            .side-footer a{
                display:block;
                color:#dc2626;
                font-weight:900;
                padding:12px 14px;
                border-radius:16px;
            }

            .side-footer a:hover{
                background:#fee2e2;
            }

            .user-main{
                padding-bottom:0;
                min-width:0;
            }

            .mobile-bottom-nav{
                display:none;
            }

            .mobile-topbar{
                margin:28px 34px 0;
                border-radius:34px;
            }

            .mobile-content{
                padding:28px 34px 44px;
                max-width:1180px;
                margin:0 auto;
            }

            .mobile-menu-grid{
                grid-template-columns:repeat(4,1fr);
            }

            .responsive-grid{
                grid-template-columns:repeat(3,1fr);
            }
        }

        @media(min-width:1400px){
            .mobile-menu-grid{
                grid-template-columns:repeat(5,1fr);
            }
        }
    </style>
</head>

<body>

<div class="user-shell">
<div class="user-layout">

<aside class="user-sidebar">
    <div class="brand-box">
        <div class="brand-logo">L</div>
        <div class="brand-text">
            <strong>LabSys</strong>
            <?php if($this->session->userdata('role') == 'siswa'): ?>
                <small>Portal Siswa</small>
            <?php else: ?>
                <small>PTK App</small>
            <?php endif; ?>
        </div>
    </div>

    <div class="side-profile">
        <?php 
        $foto_header_url = null;
        if($this->session->userdata('role') == 'siswa'){
            $CI =& get_instance();
            $CI->load->database();
            if($CI->db->table_exists('siswa_dokumen')){
                $q_foto = $CI->db->where('siswa_id', $this->session->userdata('user_id'))
                                 ->where('jenis_dokumen', 'Pas Foto')
                                 ->get('siswa_dokumen')
                                 ->row();
                if($q_foto){
                    $foto_header_url = base_url('uploads/siswa/'.$q_foto->nama_file);
                }
            }
        }
        ?>

        <?php if($foto_header_url): ?>
            <div style="width: 70px; height: 70px; margin: 0 auto 10px; border-radius: 50%; overflow: hidden; border: 3px solid rgba(255,255,255,0.3);">
                <img src="<?= $foto_header_url ?>" alt="Profil" style="width: 100%; height: 100%; object-fit: cover;">
            </div>
        <?php endif; ?>

        <strong><?= $nama_header ?></strong>
        <small><?= $tahun_ajaran ?> • Semester <?= $semester ?></small>
    </div>

    <nav class="desktop-menu">
        <?php if($this->session->userdata('role') == 'siswa'): ?>

            <div class="desktop-menu-label">Menu Utama</div>

            <a href="<?= base_url('siswa_dashboard') ?>" class="<?= $seg1 == 'siswa_dashboard' && empty($seg2) ? 'active' : '' ?>">
                <span class="mi">⌂</span> Beranda
            </a>

            <a href="<?= base_url('siswa_dashboard/jadwal') ?>" class="<?= $seg2 == 'jadwal' ? 'active' : '' ?>">
                <span class="mi">▦</span> Jadwal
            </a>

            <a href="<?= base_url('siswa_dashboard/absensi') ?>" class="<?= $seg2 == 'absensi' ? 'active' : '' ?>">
                <span class="mi">✉</span> Pengajuan Izin
            </a>

            <a href="<?= base_url('siswa_dashboard/tugas') ?>" class="<?= $seg2 == 'tugas' ? 'active' : '' ?>">
                <span class="mi">T</span> Tugas Belajar
            </a>

            <div class="desktop-menu-label">Akun</div>

            <a href="<?= base_url('siswa_dashboard/profil') ?>" class="<?= $seg2 == 'profil' ? 'active' : '' ?>">
                <span class="mi">○</span> Profil Siswa
            </a>

        <?php else: ?>

            <div class="desktop-menu-label">Menu Utama</div>

            <a href="<?= base_url('user_dashboard') ?>" class="<?= $seg1 == 'user_dashboard' ? 'active' : '' ?>">
                <span class="mi">🏠</span> Beranda
            </a>

            <?php if(!empty($can_jadwal_mengajar) || !empty($can_absensi_mengajar) || !empty($can_input_nilai)): ?>
                <div class="desktop-menu-label">KBM & Pembelajaran</div>

                <?php if(!empty($can_jadwal_mengajar)): ?>
                    <a href="<?= base_url('user_jadwal') ?>" class="<?= $seg1 == 'user_jadwal' ? 'active' : '' ?>">
                        <span class="mi">📅</span> Jadwal Mengajar
                    </a>
                <?php endif; ?>

                <?php if(!empty($can_absensi_mengajar)): ?>
                    <a href="<?= base_url('user_absensi') ?>" class="<?= ($seg1 == 'user_absensi' && empty($seg2)) || ($seg1 == 'user_absensi' && $seg2 == 'input') ? 'active' : '' ?>">
                        <span class="mi">📋</span> Absensi Mengajar
                    </a>

                    <a href="<?= base_url('user_absensi/jurnal_rekap') ?>" class="<?= $seg1 == 'user_absensi' && $seg2 == 'jurnal_rekap' ? 'active' : '' ?>">
                        <span class="mi">📖</span> Jurnal Mengajar
                    </a>
                <?php endif; ?>

                <?php if(!empty($can_input_nilai)): ?>
                    <a href="<?= base_url('user_nilai') ?>" class="<?= $seg1 == 'user_nilai' ? 'active' : '' ?>">
                        <span class="mi">📝</span> Input Nilai
                    </a>
                <?php endif; ?>

                <?php if(!empty($can_jadwal_mengajar)): ?>
                    <a href="<?= base_url('user_tugas') ?>" class="<?= $seg1 == 'user_tugas' ? 'active' : '' ?>">
                        <span class="mi">📚</span> E-Learning
                    </a>
                <?php endif; ?>
            <?php endif; ?>

            <?php if(!empty($is_wali_kelas)): ?>
                <div class="desktop-menu-label">Perwalian Kelas</div>

                <a href="<?= base_url('user_wali_kelas') ?>" class="<?= $seg1 == 'user_wali_kelas' && empty($seg2) ? 'active' : '' ?>">
                    <span class="mi">👥</span> Dashboard Wali Kelas
                </a>

                <a href="<?= base_url('user_wali_kelas/pengajuan') ?>" class="<?= $seg1 == 'user_wali_kelas' && $seg2 == 'pengajuan' ? 'active' : '' ?>">
                    <span class="mi">✉️</span> Persetujuan Izin
                </a>
            <?php endif; ?>

            <?php if(!empty($is_bk_bp)): ?>
                <div class="desktop-menu-label">Bimbingan Konseling</div>

                <a href="<?= base_url('user_bk_bp') ?>" class="<?= $seg1 == 'user_bk_bp' && (empty($seg2) || $seg2 == 'index') ? 'active' : '' ?>">
                    <span class="mi">🧭</span> Dashboard BK
                </a>

                <a href="<?= base_url('user_bk_bp/siswa') ?>" class="<?= $seg1 == 'user_bk_bp' && $seg2 == 'siswa' ? 'active' : '' ?>">
                    <span class="mi">🧑‍🎓</span> Data Siswa Bina
                </a>

                <a href="<?= base_url('user_bk_bp/konseling') ?>" class="<?= $seg1 == 'user_bk_bp' && $seg2 == 'konseling' ? 'active' : '' ?>">
                    <span class="mi">💬</span> Layanan Konseling
                </a>

                <a href="<?= base_url('user_bk_bp/rekap') ?>" class="<?= $seg1 == 'user_bk_bp' && $seg2 == 'rekap' ? 'active' : '' ?>">
                    <span class="mi">📊</span> Rekap & Cetak BK
                </a>
            <?php endif; ?>

            <?php if(!empty($can_tata_usaha) || !empty($is_kurikulum) || !empty($is_kepala)): ?>
                <div class="desktop-menu-label">Tugas Tambahan</div>

                <?php if(!empty($can_tata_usaha)): ?>
                    <a href="<?= base_url('user_tata_usaha') ?>" class="<?= $seg1 == 'user_tata_usaha' ? 'active' : '' ?>">
                        <span class="mi">📁</span> Tata Usaha
                    </a>
                <?php endif; ?>

                <?php if(!empty($is_kurikulum)): ?>
                    <a href="<?= base_url('user_kurikulum') ?>" class="<?= $seg1 == 'user_kurikulum' ? 'active' : '' ?>">
                        <span class="mi">🎯</span> Kurikulum
                    </a>
                <?php endif; ?>

                <?php if(!empty($is_kepala)): ?>
                    <a href="<?= base_url('user_rekap_global') ?>" class="<?= $seg1 == 'user_rekap_global' ? 'active' : '' ?>">
                        <span class="mi">📈</span> Rekap Global
                    </a>
                <?php endif; ?>

                <?php if(!empty($is_kepala) || $this->session->userdata('role') == 'admin_master'): ?>
                    <a href="<?= base_url('kepala_cuti') ?>" class="<?= $seg1 == 'kepala_cuti' ? 'active' : '' ?>">
                        <span class="mi">✓</span> Approval Cuti
                    </a>
                <?php endif; ?>
            <?php endif; ?>

            <div class="desktop-menu-label">Akun & Administrasi</div>

            <a href="<?= base_url('user_profil') ?>" class="<?= $seg1 == 'user_profil' ? 'active' : '' ?>">
                <span class="mi">👤</span> Profil PTK
            </a>

            <?php if(isset($ptk) && $ptk && in_array(trim($ptk->status_kepegawaian), ['PNS', 'PPPK'])): ?>
                <a href="<?= base_url('user_cuti') ?>" class="<?= $seg1 == 'user_cuti' ? 'active' : '' ?>">
                    <span class="mi">✂</span> Cuti Saya
                </a>
            <?php endif; ?>

        <?php endif; ?>
    </nav>

    <div class="side-footer">
        <a href="<?= base_url('auth/logout') ?>">Keluar</a>
    </div>
</aside>

<main class="user-main">