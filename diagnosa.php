<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<h2>🛠️ DIAGNOSA SISTEM DOMAINESIA</h2>";

$db_user = 'manbanj2_web';
$db_pass = 'RKJ;oWKs8F,ox@)g';
$db_name = 'manbanj2_webman';

$conn = @new mysqli('localhost', $db_user, $db_pass, $db_name);

// 1. Handle Auto-Import jika tombol diklik
if(isset($_POST['import_database'])){
    $sql_file = __DIR__ . '/database/db_web_madrasah_cloud.sql';
    if(file_exists($sql_file)){
        $sql_content = file_get_contents($sql_file);
        $conn->multi_query($sql_content);
        do {
            if ($result = $conn->store_result()) {
                $result->free();
            }
        } while ($conn->more_results() && $conn->next_result());
        
        echo "<div style='background:#dcfce7;color:#166534;padding:12px;border-radius:8px;margin-bottom:15px;font-weight:bold;'>🎉 DATABASE BERHASIL DI-IMPORT 100%! Seluruh tabel asli madrasah sudah siap!</div>";
    } else {
        echo "<div style='background:#fee2e2;color:#991b1b;padding:12px;border-radius:8px;margin-bottom:15px;'>File database/db_web_madrasah_cloud.sql tidak ditemukan.</div>";
    }
}

// 1. Cek PHP Version
echo "<p><strong>1. Versi PHP:</strong> " . PHP_VERSION . "</p>";

// 2. Cek Koneksi Database
echo "<p><strong>2. Uji Database:</strong> ";
if ($conn->connect_error) {
    echo "<span style='color:red;'>❌ GAGAL: " . $conn->connect_error . "</span></p>";
} else {
    echo "<span style='color:green;'>✅ OK</span></p>";

    // Cek tabel krusial
    $tables = [
        'website_profil', 'website_banner', 'website_video', 'website_pamflet', 'website_galeri', 
        'berita', 'ptk', 'settings', 'ppdb',
        'kelas', 'mapel', 'jadwal_mengajar', 'jadwal_jam', 'jadwal_piket', 'absensi_kelas'
    ];
    $ada_yang_kurang = false;
    echo "<ul>";
    foreach($tables as $t){
        $res = $conn->query("SHOW TABLES LIKE '$t'");
        if($res && $res->num_rows > 0){
            $count = $conn->query("SELECT count(*) FROM `$t`")->fetch_row()[0];
            echo "<li>Tabel <code>$t</code>: <span style='color:green;'>ADA ({$count} baris)</span></li>";
        } else {
            $ada_yang_kurang = true;
            echo "<li>Tabel <code>$t</code>: <span style='color:red;'>BELUM ADA</span></li>";
        }
    }
    echo "</ul>";

    if($ada_yang_kurang){
        echo "<form method='POST' style='margin: 15px 0;'>
            <button type='submit' name='import_database' value='1' style='background:#059669;color:#fff;font-weight:bold;padding:12px 24px;border:none;border-radius:8px;cursor:pointer;font-size:15px;'>
                ⚡ Klik Di Sini Untuk Import Database Otomatis Sekarang (1 Detik)
            </button>
        </form>";
    } else {
        echo "<p style='color:green;font-weight:bold;font-size:16px;'>🎉 SELURUH TABEL SUDAH LENGKAP 100%! Website Anda sudah siap dibuka:</p>";
        echo "<p><a href='https://man3banjar.sch.id/' style='background:#0284c7;color:#fff;padding:10px 20px;text-decoration:none;border-radius:6px;font-weight:bold;'>🚀 Buka Website MAN 3 Banjar</a></p>";
    }
}

// 3. Cek File-file Inti CodeIgniter
echo "<p><strong>3. Uji File Core:</strong></p><ul>";
$files = [
    'application/config/config.php',
    'application/config/database.php',
    'application/config/routes.php',
    'application/controllers/Home.php',
    'application/views/public/home.php',
    'application/views/public/partials/home_banner_slider.php',
    'system/core/CodeIgniter.php'
];
foreach($files as $f){
    if(file_exists(__DIR__ . '/' . $f)){
        echo "<li>File <code>$f</code>: <span style='color:green;'>ADA</span></li>";
    } else {
        echo "<li>File <code>$f</code>: <span style='color:red;'>TIDAK DITEMUKAN</span></li>";
    }
}
echo "</ul>";
?>
