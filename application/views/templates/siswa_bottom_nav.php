<?php
if(isset($GLOBALS['siswa_bottom_nav_loaded'])){
    return;
}

$GLOBALS['siswa_bottom_nav_loaded'] = true;

$seg1 = $this->uri->segment(1);
$seg2 = $this->uri->segment(2);
?>

<style>
.mobile-bottom-nav {
    position: fixed !important;
    left: 0 !important;
    right: 0 !important;
    bottom: 0 !important;
    z-index: 9999999 !important;
    padding: 8px 12px calc(8px + env(safe-area-inset-bottom)) !important;
    pointer-events: none;
}

.mobile-bottom-inner {
    pointer-events: auto;
    max-width: 520px;
    margin: 0 auto;
    background: rgba(255, 255, 255, 0.96);
    border: 1px solid #e2e8f0;
    border-radius: 30px;
    box-shadow: 0 18px 45px rgba(15, 23, 42, 0.16);
    padding: 8px 16px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    backdrop-filter: blur(18px);
}

.mobile-nav-item {
    text-decoration: none;
    color: #64748b;
    font-size: 11px;
    font-weight: 900;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    gap: 4px;
    transition: 0.3s;
    flex: 1;
}

.mobile-nav-item span {
    font-size: 22px;
    line-height: 1;
    font-weight: 950;
    margin-bottom: 2px;
}

.mobile-nav-item.active {
    color: #0ea5e9;
}

.floating-home-btn{
    width:64px;
    height:64px;
    background:linear-gradient(135deg,#3b82f6,#8b5cf6);
    border-radius:50%;
    display:flex;
    align-items:center;
    justify-content:center;
    color:white;
    font-size:26px;
    box-shadow:0 12px 30px rgba(99,102,241,.3);
    transform:translateY(-20px);
    text-decoration:none;
    border: 4px solid #f4f7fe;
}
</style>

<div class="mobile-bottom-nav">
    <div class="mobile-bottom-inner">

        <!-- KIRI: Menu -->
        <a href="#" class="mobile-nav-item" onclick="document.getElementById('customSiswaMenu').classList.add('show'); return false;">
            <span>☰</span>
            Menu
        </a>

        <!-- TENGAH: Home Beranda -->
        <div style="flex: 1; display:flex; justify-content:center;">
            <a href="<?= base_url('siswa_dashboard') ?>" class="floating-home-btn">
                🏠
            </a>
        </div>

        <!-- KANAN: Profil -->
        <a href="<?= base_url('siswa_dashboard/profil') ?>"
           class="mobile-nav-item <?= $seg2 == 'profil' ? 'active' : '' ?>">
            <span>👤</span>
            Profil
        </a>

    </div>
</div>

<style>
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

<div class="menu-overlay" id="customSiswaOverlay" onclick="document.getElementById('customSiswaMenu').classList.remove('show'); this.classList.remove('show');"></div>

<div class="custom-mobile-menu" id="customSiswaMenu">
    <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom: 24px; border-bottom: 1px solid #e2e8f0; padding-bottom: 16px;">
        <h5 style="margin:0; font-weight:900; color:#0f172a;">Menu Siswa</h5>
        <button type="button" style="background:none; border:none; font-size:24px; color:#64748b; font-weight:900;" onclick="document.getElementById('customSiswaMenu').classList.remove('show'); document.getElementById('customSiswaOverlay').classList.remove('show');">×</button>
    </div>
    
    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 12px;">
        <a href="<?= base_url('siswa_dashboard/jadwal') ?>" class="btn" style="background: white; border: 1px solid #cbd5e1; border-radius: 16px; padding: 16px; text-align: left; font-weight: 800; color: #334155; text-decoration: none;">
            <span style="font-size: 24px; display: block; margin-bottom: 8px;">📅</span> Jadwal<br>Pelajaran
        </a>
        <a href="<?= base_url('siswa_dashboard/tugas') ?>" class="btn" style="background: white; border: 1px solid #cbd5e1; border-radius: 16px; padding: 16px; text-align: left; font-weight: 800; color: #334155; text-decoration: none;">
            <span style="font-size: 24px; display: block; margin-bottom: 8px;">📚</span> Tugas<br>Belajar
        </a>
        <a href="<?= base_url('siswa_dashboard/absensi') ?>" class="btn" style="background: white; border: 1px solid #cbd5e1; border-radius: 16px; padding: 16px; text-align: left; font-weight: 800; color: #334155; text-decoration: none;">
            <span style="font-size: 24px; display: block; margin-bottom: 8px;">📋</span> Absensi<br>Izin / Sakit
        </a>
    </div>
</div>

<script>
    document.querySelector('.mobile-nav-item[onclick*="customSiswaMenu"]').addEventListener('click', function(){
        document.getElementById('customSiswaOverlay').classList.add('show');
    });
</script>
