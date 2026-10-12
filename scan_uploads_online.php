<?php
$secret_token = 'MAN3BANJAR_DEPLOY_SECRET_KEY_2026';
if (!isset($_GET['secret']) || $_GET['secret'] !== $secret_token) {
    die("Akses ditolak");
}

header('Content-Type: text/plain; charset=utf-8');

echo "=== DAFTAR SELURUH BERKAS DI FOLDER UPLOADS HOSTING ONLINE ===\n";

function scan_all($dir) {
    if (!is_dir($dir)) {
        echo "Direktori $dir tidak ditemukan.\n";
        return;
    }
    $it = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($dir, RecursiveDirectoryIterator::SKIP_DOTS));
    foreach ($it as $file) {
        $path = $file->getPathname();
        $rel = str_replace(__DIR__ . DIRECTORY_SEPARATOR, '', $path);
        $size = round($file->getSize() / 1024, 1);
        $time = date('Y-m-d H:i:s', $file->getMTime());
        echo "$rel | {$size} KB | {$time}\n";
    }
}

scan_all(__DIR__ . '/uploads');

echo "\n=== DAFTAR BERKAS DI ASSETS/DOWNLOADS HOSTING ONLINE ===\n";
scan_all(__DIR__ . '/assets/downloads');

// Cek apakah ada file backup .sql di server (misalnya cpanel backup, db dump)
echo "\n=== PENCARIAN FILE BACKUP .SQL DI ROOT & PARENT DIR ===\n";
$parent = dirname(__DIR__);
$sql_files = glob(__DIR__ . '/*.sql');
$sql_files = array_merge($sql_files, glob(__DIR__ . '/*/*.sql'));
$sql_files = array_merge($sql_files, glob($parent . '/*.sql'));
$sql_files = array_merge($sql_files, glob($parent . '/*/*.sql'));
foreach ($sql_files as $sf) {
    echo "SQL: $sf (" . round(filesize($sf) / 1024, 1) . " KB | " . date('Y-m-d H:i:s', filemtime($sf)) . ")\n";
}

