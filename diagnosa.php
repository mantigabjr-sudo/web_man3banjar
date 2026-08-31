<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<h2>🛠️ DIAGNOSA SISTEM DOMAINESIA</h2>";

// 1. Cek PHP Version
echo "<p><strong>1. Versi PHP:</strong> " . PHP_VERSION . "</p>";

// 2. Cek Koneksi Database
echo "<p><strong>2. Uji Database:</strong> ";
$db_user = 'manbanj2_web';
$db_pass = 'RKJ;oWKs8F,ox@)g';
$db_name = 'manbanj2_webman';

$conn = @new mysqli('localhost', $db_user, $db_pass, $db_name);
if ($conn->connect_error) {
    echo "<span style='color:red;'>❌ GAGAL: " . $conn->connect_error . "</span></p>";
} else {
    echo "<span style='color:green;'>✅ OK</span></p>";

    // Cek tabel krusial
    $tables = ['website_profil', 'website_banner', 'website_video', 'website_pamflet', 'website_galeri', 'berita', 'ptk', 'settings'];
    echo "<ul>";
    foreach($tables as $t){
        $res = $conn->query("SHOW TABLES LIKE '$t'");
        if($res && $res->num_rows > 0){
            $count = $conn->query("SELECT count(*) FROM `$t`")->fetch_row()[0];
            echo "<li>Tabel <code>$t</code>: <span style='color:green;'>ADA ({$count} baris)</span></li>";
        } else {
            echo "<li>Tabel <code>$t</code>: <span style='color:red;'>BELUM ADA (Harus diimport dari database/db_web_madrasah_cloud.sql)</span></li>";
        }
    }
    echo "</ul>";
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

// 4. Test Booting CodeIgniter
echo "<p><strong>4. Booting CodeIgniter Core:</strong> ";
try {
    define('BASEPATH', __DIR__ . '/system/');
    define('APPPATH', __DIR__ . '/application/');
    define('VIEWPATH', __DIR__ . '/application/views/');
    define('ENVIRONMENT', 'development');

    if(file_exists(__DIR__ . '/system/core/Common.php')){
        require_once __DIR__ . '/system/core/Common.php';
        echo "<span style='color:green;'>Common.php OK</span> | ";
    }
    echo "<span style='color:green;'>Semua pengecekan awal selesai.</span></p>";
} catch (Throwable $e) {
    echo "<span style='color:red;'>ERROR: " . $e->getMessage() . " di " . $e->getFile() . ":" . $e->getLine() . "</span></p>";
}
?>
