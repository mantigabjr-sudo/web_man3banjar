<?php
// Script diagnosa untuk melihat data database dan file di server hosting
$secret_token = 'MAN3BANJAR_DEPLOY_SECRET_KEY_2026';
if (!isset($_GET['secret']) || $_GET['secret'] !== $secret_token) {
    die("Akses ditolak");
}

header('Content-Type: text/plain; charset=utf-8');

echo "=== DIAGNOSA SERVER ONLINE ===\n";

// 1. Cek koneksi DB lokal server
define('BASEPATH', true);
require_once __DIR__ . '/application/config/database.php';
$db_conf = $db['default'];
$m = new mysqli($db_conf['hostname'], $db_conf['username'], $db_conf['password'], $db_conf['database']);
if ($m->connect_error) {
    echo "DB Connect Error: " . $m->connect_error . "\n";
} else {
    echo "DB Connected: {$db_conf['database']}\n\n";

    echo "--- DAFTAR TABEL DI DATABASE ONLINE ---\n";
    $tables = $m->query("SHOW TABLES");
    while ($t = $tables->fetch_row()) {
        echo " - " . $t[0] . "\n";
    }

    echo "\n--- ISI TABEL WEBSITE_DOWNLOAD ONLINE SAAT INI ---\n";
    $q = $m->query("SELECT id, judul, kategori_pilar, area_zi, file_path, link_drive, created_at FROM website_download ORDER BY id ASC");
    if ($q) {
        while ($r = $q->fetch_assoc()) {
            echo "[{$r['id']}] [{$r['kategori_pilar']}] [{$r['area_zi']}] {$r['judul']} (File: {$r['file_path']}, Drive: {$r['link_drive']})\n";
        }
    } else {
        echo "Query Error: " . $m->error . "\n";
    }

    echo "\n--- CEK TABEL BACKUP ATAU RECYCLE ---\n";
    $bak = $m->query("SHOW TABLES LIKE '%download%'");
    while ($b = $bak->fetch_row()) {
        echo " - " . $b[0] . "\n";
    }
}

// 2. Cek file-file di folder uploads dan assets/downloads
echo "\n--- FILE DI ASSETS/DOWNLOADS SERVER ONLINE ---\n";
$dl_files = glob(__DIR__ . '/assets/downloads/*');
foreach ($dl_files as $f) {
    echo " - " . basename($f) . " (" . round(filesize($f) / 1024, 1) . " KB)\n";
}

echo "\n--- FILE DI UPLOADS SERVER ONLINE ---\n";
function list_files_rec($dir, $depth = 0) {
    if ($depth > 2) return;
    $items = glob($dir . '/*');
    foreach ($items as $item) {
        if (is_dir($item)) {
            echo str_repeat("  ", $depth) . "[DIR] " . basename($item) . "\n";
            list_files_rec($item, $depth + 1);
        } else {
            echo str_repeat("  ", $depth) . " - " . basename($item) . " (" . round(filesize($item) / 1024, 1) . " KB)\n";
        }
    }
}
list_files_rec(__DIR__ . '/uploads');

