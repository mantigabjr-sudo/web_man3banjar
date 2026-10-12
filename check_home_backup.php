<?php
$secret_token = 'MAN3BANJAR_DEPLOY_SECRET_KEY_2026';
if (!isset($_GET['secret']) || $_GET['secret'] !== $secret_token) {
    die("Akses ditolak");
}

header('Content-Type: text/plain; charset=utf-8');

echo "=== CEK DIREKTORI /home/manbanj2/ ===\n";
$home = '/home/manbanj2';
$entries = scandir($home);
foreach ($entries as $e) {
    if ($e === '.' || $e === '..') continue;
    $full = $home . '/' . $e;
    echo (is_dir($full) ? "[DIR] " : "[FILE] ") . $e . "\n";
}

$backups = glob($home . '/*backup*');
echo "\n--- FOLDER/FILE BACKUP DI HOME ---\n";
foreach ($backups as $b) {
    echo "Backup: $b\n";
}
