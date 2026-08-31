<?php
if(isset($GLOBALS['mobile_bottom_nav_loaded'])){
    return;
}

$GLOBALS['mobile_bottom_nav_loaded'] = true;

$seg1 = $this->uri->segment(1);
$seg2 = $this->uri->segment(2);

$ptk_id_nav = $this->session->userdata('ptk_id');

if(!function_exists('nav_bk_text_match')){
    function nav_bk_text_match($text){

        $text = strtolower((string)$text);
        $compact = preg_replace('/[^a-z0-9]/', '', $text);

        if(in_array($compact, ['bk', 'bp', 'bkbp'])){
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

        if(preg_match('/(^|[^a-z0-9])(bk|bp)([^a-z0-9]|$)/i', $text)){
            return true;
        }

        return false;
    }
}

$setting_nav = $this->db->get('settings')->row();

$tahun_nav = ($setting_nav && !empty($setting_nav->tahun_ajaran))
    ? $setting_nav->tahun_ajaran
    : date('Y').'-'.(date('Y') + 1);

$semester_nav = 'Ganjil';

if($setting_nav){
    if(!empty($setting_nav->semester_aktif)){
        $semester_nav = $setting_nav->semester_aktif;
    } elseif(!empty($setting_nav->semester)){
        $semester_nav = $setting_nav->semester;
    }
}

/*
 * Deteksi akses guru biasa.
 */
if(!isset($can_input_nilai) || !isset($can_jadwal_mengajar) || !isset($can_absensi_mengajar)){

    $jumlah_mengajar_nav = 0;

    if(!empty($ptk_id_nav) && $this->db->table_exists('tugas_mengajar')){
        $jumlah_mengajar_nav = $this->db
            ->where('ptk_id', $ptk_id_nav)
            ->where('tahun_ajaran', $tahun_nav)
            ->where('semester', $semester_nav)
            ->where('status', 'Aktif')
            ->count_all_results('tugas_mengajar');
    }

    if(!isset($can_jadwal_mengajar)){
        $can_jadwal_mengajar = $jumlah_mengajar_nav > 0;
    }

    if(!isset($can_absensi_mengajar)){
        $can_absensi_mengajar = $jumlah_mengajar_nav > 0;
    }

    if(!isset($can_input_nilai)){
        $can_input_nilai = $jumlah_mengajar_nav > 0;
    }
}

/*
 * Deteksi Wali Kelas.
 */
if(!isset($is_wali_kelas)){

    $is_wali_kelas = false;

    if(!empty($ptk_id_nav) && $this->db->table_exists('wali_kelas')){
        $is_wali_kelas = $this->db
            ->where('ptk_id', $ptk_id_nav)
            ->where('tahun_ajaran', $tahun_nav)
            ->where('status', 'Aktif')
            ->count_all_results('wali_kelas') > 0;
    }
}

if(!isset($is_kurikulum)){
    $is_kurikulum = false;
}

if(!isset($can_tata_usaha)){
    $can_tata_usaha = false;
}

/*
 * Deteksi BK/BP.
 * Jangan pakai if(!isset($is_bk_bp)) karena kalau sudah diset false
 * dari controller/layout, deteksi tidak akan berjalan.
 */
$is_bk_bp_from_controller = !empty($is_bk_bp);
$is_bk_bp = $is_bk_bp_from_controller;

if(!$is_bk_bp && !empty($ptk_id_nav)){

    /*
     * 1. Cek dari data PTK.
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
            $text_ptk .= ' '.($ptk_nav->nama_tugas ?? '');

            if(nav_bk_text_match($text_ptk)){
                $is_bk_bp = true;
            }
        }
    }

    /*
     * 2. Cek dari mapel BK/BP di tugas mengajar.
     * Dibuat tanpa filter tahun/semester dulu supaya tidak gagal karena setting beda.
     */
    if(!$is_bk_bp && $this->db->table_exists('tugas_mengajar') && $this->db->table_exists('mapel')){

        $select = [
            'tugas_mengajar.tahun_ajaran',
            'tugas_mengajar.semester',
            'tugas_mengajar.status',
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

            if(nav_bk_text_match($text_mapel)){
                $is_bk_bp = true;
                break;
            }
        }
    }
}

?>

<style>
.mobile-bottom-nav{
    position:fixed !important;
    left:0 !important;
    right:0 !important;
    bottom:0 !important;
    z-index:9999999 !important;
    padding:8px 12px calc(8px + env(safe-area-inset-bottom)) !important;
    pointer-events:none;
}

.mobile-bottom-inner{
    pointer-events:auto;
    max-width:520px;
    margin:0 auto;
    background:rgba(255,255,255,.96);
    border:1px solid #e2e8f0;
    border-radius:30px;
    box-shadow:0 18px 45px rgba(15,23,42,.16);
    padding:8px 16px;
    display:flex;
    align-items:center;
    justify-content:space-between;
    backdrop-filter:blur(18px);
}

.mobile-nav-item{
    text-decoration:none;
    color:#64748b;
    font-size:11px;
    font-weight:900;
    display:flex;
    flex-direction:column;
    align-items:center;
    justify-content:center;
    gap:4px;
    flex: 1;
}

.mobile-nav-item span{
    font-size:22px;
    line-height:1;
    font-weight:950;
}

.mobile-nav-item.active{
    color:#16a34a;
}

.floating-home-btn{
    width:64px;
    height:64px;
    background:linear-gradient(135deg,#15803d,#22c55e);
    border-radius:50%;
    display:flex;
    align-items:center;
    justify-content:center;
    color:white;
    font-size:26px;
    box-shadow:0 12px 30px rgba(34,197,94,.3);
    transform:translateY(-20px);
    text-decoration:none;
    border: 4px solid #f8fafc;
}

.mobile-content{
    padding-bottom:120px !important;
}

.custom-mobile-menu {
    position: fixed;
    bottom: -100%; left: 0; right: 0;
    background: #f8fafc;
    z-index: 99999999;
    padding: 24px 24px calc(120px + env(safe-area-inset-bottom));
    border-radius: 30px 30px 0 0;
    box-shadow: 0 -10px 40px rgba(0,0,0,0.1);
    transition: 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
}
.custom-mobile-menu.show {
    bottom: 0;
}
.menu-overlay {
    position: fixed; top: 0; left: 0; right: 0; bottom: 0;
    background: rgba(15,23,42,0.6);
    backdrop-filter: blur(4px);
    z-index: 99999998;
    opacity: 0; pointer-events: none;
    transition: 0.3s;
}
.menu-overlay.show {
    opacity: 1; pointer-events: auto;
}
</style>

<div class="menu-overlay" id="customPtkOverlay" onclick="document.getElementById('customPtkMenu').classList.remove('show'); this.classList.remove('show');"></div>

<div class="mobile-bottom-nav">
    <div class="mobile-bottom-inner">

        <!-- KIRI: Tombol Buka Menu Mengambang -->
        <a href="#" class="mobile-nav-item" onclick="document.getElementById('customPtkMenu').classList.add('show'); return false;">
            <span>☰</span>
            Menu
        </a>

        <!-- TENGAH: Beranda (Floating Bulat) -->
        <div style="flex: 1; display:flex; justify-content:center;">
            <a href="<?= base_url('user_dashboard') ?>" class="floating-home-btn">
                ⌂
            </a>
        </div>

        <!-- KANAN: Profil -->
        <a href="<?= base_url('user_profil') ?>" class="mobile-nav-item <?= $seg1 == 'user_profil' ? 'active' : '' ?>">
            <span>👤</span>
            Profil
        </a>

    </div>
</div>

<div class="custom-mobile-menu" id="customPtkMenu">
    <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom: 24px; border-bottom: 1px solid #e2e8f0; padding-bottom: 16px;">
        <h5 style="margin:0; font-weight:900; color:#0f172a;">Menu Navigasi</h5>
        <button type="button" style="background:none; border:none; font-size:24px; color:#64748b; font-weight:900;" onclick="document.getElementById('customPtkMenu').classList.remove('show'); document.getElementById('customPtkOverlay').classList.remove('show');">×</button>
    </div>
    
    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 12px; max-height: 60vh; overflow-y: auto;">
        
        <?php if(!empty($can_jadwal_mengajar)): ?>
            <a href="<?= base_url('user_jadwal') ?>" class="btn" style="background: white; border: 1px solid #cbd5e1; border-radius: 16px; padding: 12px; text-align: left; font-weight: 800; color: #334155; text-decoration: none;">
                <span style="font-size: 20px; display: block; margin-bottom: 6px;">📅</span> Jadwal KBM
            </a>
            <a href="<?= base_url('user_tugas') ?>" class="btn" style="background: white; border: 1px solid #cbd5e1; border-radius: 16px; padding: 12px; text-align: left; font-weight: 800; color: #334155; text-decoration: none;">
                <span style="font-size: 20px; display: block; margin-bottom: 6px;">📚</span> E-Learning
            </a>
        <?php endif; ?>

        <?php if(!empty($can_absensi_mengajar)): ?>
            <a href="<?= base_url('user_absensi') ?>" class="btn" style="background: white; border: 1px solid #cbd5e1; border-radius: 16px; padding: 12px; text-align: left; font-weight: 800; color: #334155; text-decoration: none;">
                <span style="font-size: 20px; display: block; margin-bottom: 6px;">📋</span> Absensi KBM
            </a>
            <a href="<?= base_url('user_absensi/jurnal_rekap') ?>" class="btn" style="background: white; border: 1px solid #cbd5e1; border-radius: 16px; padding: 12px; text-align: left; font-weight: 800; color: #334155; text-decoration: none;">
                <span style="font-size: 20px; display: block; margin-bottom: 6px;">📖</span> Jurnal Mengajar
            </a>
        <?php endif; ?>

        <?php if(!empty($can_input_nilai)): ?>
            <a href="<?= base_url('user_nilai') ?>" class="btn" style="background: white; border: 1px solid #cbd5e1; border-radius: 16px; padding: 12px; text-align: left; font-weight: 800; color: #334155; text-decoration: none;">
                <span style="font-size: 20px; display: block; margin-bottom: 6px;">📝</span> Penilaian
            </a>
        <?php endif; ?>

        <?php if(!empty($is_wali_kelas)): ?>
            <a href="<?= base_url('user_wali_kelas') ?>" class="btn" style="background: #fdf2f8; border: 1px solid #fbcfe8; border-radius: 16px; padding: 12px; text-align: left; font-weight: 800; color: #9d174d; text-decoration: none;">
                <span style="font-size: 20px; display: block; margin-bottom: 6px;">👥</span> Wali Kelas
            </a>
            <a href="<?= base_url('user_wali_kelas/pengajuan') ?>" class="btn" style="background: #fdf2f8; border: 1px solid #fbcfe8; border-radius: 16px; padding: 12px; text-align: left; font-weight: 800; color: #9d174d; text-decoration: none;">
                <span style="font-size: 20px; display: block; margin-bottom: 6px;">✉️</span> Izin Siswa
            </a>
        <?php endif; ?>

        <?php if(!empty($is_bk_bp)): ?>
            <a href="<?= base_url('user_bk_bp') ?>" class="btn" style="background: #eff6ff; border: 1px solid #bfdbfe; border-radius: 16px; padding: 12px; text-align: left; font-weight: 800; color: #1e40af; text-decoration: none;">
                <span style="font-size: 20px; display: block; margin-bottom: 6px;">🧭</span> BK Dashboard
            </a>
            <a href="<?= base_url('user_bk_bp/konseling') ?>" class="btn" style="background: #eff6ff; border: 1px solid #bfdbfe; border-radius: 16px; padding: 12px; text-align: left; font-weight: 800; color: #1e40af; text-decoration: none;">
                <span style="font-size: 20px; display: block; margin-bottom: 6px;">💬</span> Konseling BK
            </a>
            <a href="<?= base_url('user_bk_bp/rekap') ?>" class="btn" style="background: #eff6ff; border: 1px solid #bfdbfe; border-radius: 16px; padding: 12px; text-align: left; font-weight: 800; color: #1e40af; text-decoration: none;">
                <span style="font-size: 20px; display: block; margin-bottom: 6px;">📊</span> Rekap BK
            </a>
        <?php endif; ?>

        <?php if(!empty($can_tata_usaha)): ?>
            <a href="<?= base_url('user_tata_usaha') ?>" class="btn" style="background: white; border: 1px solid #cbd5e1; border-radius: 16px; padding: 12px; text-align: left; font-weight: 800; color: #334155; text-decoration: none;">
                <span style="font-size: 20px; display: block; margin-bottom: 6px;">📁</span> Tata Usaha
            </a>
        <?php endif; ?>
        
        <?php if(!empty($is_kurikulum)): ?>
            <a href="<?= base_url('user_kurikulum') ?>" class="btn" style="background: white; border: 1px solid #cbd5e1; border-radius: 16px; padding: 12px; text-align: left; font-weight: 800; color: #334155; text-decoration: none;">
                <span style="font-size: 20px; display: block; margin-bottom: 6px;">🎯</span> Kurikulum
            </a>
        <?php endif; ?>
        
        <?php if(!empty($is_kepala)): ?>
            <a href="<?= base_url('user_rekap_global') ?>" class="btn" style="background: white; border: 1px solid #cbd5e1; border-radius: 16px; padding: 12px; text-align: left; font-weight: 800; color: #334155; text-decoration: none;">
                <span style="font-size: 20px; display: block; margin-bottom: 6px;">📈</span> Rekap Global
            </a>
        <?php endif; ?>

    </div>
</div>

<script>
    document.querySelector('.mobile-nav-item[onclick*="customPtkMenu"]').addEventListener('click', function(){
        document.getElementById('customPtkOverlay').classList.add('show');
    });
</script>
<?php