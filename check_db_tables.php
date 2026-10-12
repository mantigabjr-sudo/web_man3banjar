<?php
$secret_token = 'MAN3BANJAR_DEPLOY_SECRET_KEY_2026';
if (!isset($_GET['secret']) || $_GET['secret'] !== $secret_token) {
    die("Akses ditolak");
}

header('Content-Type: text/plain; charset=utf-8');

$m = new mysqli('localhost', 'manbanj2_web', 'RKJ;oWKs8F,ox@)g', 'manbanj2_webman');
if ($m->connect_error) {
    die("DB Error: " . $m->connect_error);
}

echo "=== DAFTAR TABEL DI DATABASE ONLINE manbanj2_webman ===\n";
$res = $m->query("SHOW TABLES");
while ($r = $res->fetch_row()) {
    echo "Table: " . $r[0] . "\n";
}

echo "\n=== CEK TABEL TERKAIT DOWNLOAD ATAU BACKUP ===\n";
$res2 = $m->query("SHOW TABLES LIKE '%down%'");
while ($r2 = $res2->fetch_row()) {
    echo "Match: " . $r2[0] . "\n";
}
