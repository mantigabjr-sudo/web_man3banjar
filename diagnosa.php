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

// 2. Handle Reset / Buat User admin_pmb
if(isset($_POST['reset_admin_pmb'])){
    $conn->query("ALTER TABLE users MODIFY COLUMN role VARCHAR(50) DEFAULT 'admin'");
    $hashed = md5('admin');
    $cek = $conn->query("SELECT id FROM users WHERE username = 'admin_pmb'");
    if($cek && $cek->num_rows > 0){
        $conn->query("UPDATE users SET password = '$hashed', role = 'admin_pmb', nama_lengkap = 'Panitia PMB MAN 3 Banjar' WHERE username = 'admin_pmb'");
    } else {
        $conn->query("INSERT INTO users (username, password, nama_lengkap, role, created_at) VALUES ('admin_pmb', '$hashed', 'Panitia PMB MAN 3 Banjar', 'admin_pmb', NOW())");
    }
    echo "<div style='background:#dcfce7;color:#166534;padding:12px;border-radius:8px;margin-bottom:15px;font-weight:bold;'>✅ Akun <u>admin_pmb</u> berhasil dibuat/direset! (Username: <code>admin_pmb</code> | Password: <code>admin</code>)</div>";
}

// 1. Cek PHP Version
echo "<p><strong>1. Versi PHP:</strong> " . PHP_VERSION . "</p>";

// 2. Cek Koneksi Database
echo "<p><strong>2. Uji Database:</strong> ";
if ($conn->connect_error) {
    echo "<span style='color:red;'>❌ GAGAL: " . $conn->connect_error . "</span></p>";
} else {
    echo "<span style='color:green;'>✅ OK</span></p>";

    // Cek Akun Users
    $res_users = $conn->query("SHOW TABLES LIKE 'users'");
    if($res_users && $res_users->num_rows > 0){
        echo "<p><strong>Daftar Akun Pengguna di Server:</strong></p>";
        $q_u = $conn->query("SELECT id, username, nama_lengkap, role FROM users");
        echo "<table border='1' cellpadding='6' cellspacing='0' style='border-collapse:collapse;margin-bottom:15px;font-size:13px;'>
            <tr style='background:#f1f5f9;'><th>ID</th><th>Username</th><th>Nama Lengkap</th><th>Role</th></tr>";
        while($r = $q_u->fetch_assoc()){
            echo "<tr><td>{$r['id']}</td><td><strong>{$r['username']}</strong></td><td>{$r['nama_lengkap']}</td><td><code>{$r['role']}</code></td></tr>";
        }
        echo "</table>";
    }

    echo "<div style='display:flex;gap:10px;margin-bottom:20px;'>
        <form method='POST'>
            <button type='submit' name='reset_admin_pmb' value='1' style='background:#15803d;color:#fff;font-weight:bold;padding:10px 18px;border:none;border-radius:6px;cursor:pointer;'>
                🔑 Buat / Reset Akun admin_pmb (Pass: admin)
            </button>
        </form>
        <form method='POST'>
            <button type='submit' name='import_database' value='1' style='background:#0284c7;color:#fff;font-weight:bold;padding:10px 18px;border:none;border-radius:6px;cursor:pointer;'>
                ⚡ Re-Import Seluruh Database Cloud
            </button>
        </form>
    </div>";

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
}

// 3. Cek File Core
echo "<p><strong>3. Uji File Core:</strong></p><ul>";
$files = [
    'application/config/config.php',
    'application/config/database.php',
    'application/config/routes.php',
    'application/controllers/Home.php',
    'application/views/public/home.php',
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
