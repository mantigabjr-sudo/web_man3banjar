<?php
$secret_token = 'MAN3BANJAR_DEPLOY_SECRET_KEY_2026';
if (!isset($_GET['secret']) || $_GET['secret'] !== $secret_token) {
    die("Akses ditolak");
}

header('Content-Type: text/plain; charset=utf-8');

echo "=== MENCARI FILE LOG / QUERY HISTORY DI HOSTING ===\n";
$logs = glob(__DIR__ . '/*.log');
$logs = array_merge($logs, glob(__DIR__ . '/application/logs/*.php'));
foreach ($logs as $l) {
    echo "Log: $l (" . round(filesize($l) / 1024, 1) . " KB)\n";
}

// Cek isi log CI terbaru jika ada
if (!empty($logs)) {
    $latest = end($logs);
    echo "\n--- ISI LOG TERBARU ($latest) ---\n";
    $content = file_get_contents($latest);
    echo substr($content, -2000); // 2000 karakter terakhir
}
