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

echo "=== CARI DOKUMEN DENGAN KATA 'KODE ETIK' ATAU 'POKJA 3' DI SELURUH TABEL ===\n";
$q1 = $m->query("SELECT * FROM website_download WHERE LOWER(judul) LIKE '%etik%' OR LOWER(judul) LIKE '%perilaku%' OR LOWER(keterangan) LIKE '%etik%'");
if ($q1) {
    while ($r = $q1->fetch_assoc()) {
        print_r($r);
    }
} else {
    echo "Query 1 Error: " . $m->error . "\n";
}

echo "\n=== DAFTAR BERKAS AREA3 DI DATABASE ONLINE SAAT INI ===\n";
$q2 = $m->query("SELECT * FROM website_download WHERE area_zi = 'area3'");
if ($q2) {
    while ($r = $q2->fetch_assoc()) {
        echo "[{$r['id']}] {$r['judul']} (File: {$r['file_path']}, Drive: {$r['link_drive']})\n";
    }
}

echo "\n=== CEK SEMUA FILE DI ASSETS/DOWNLOADS ONLINE TERKAIT POKJA 3 ===\n";
$files = glob(__DIR__ . '/assets/downloads/*');
foreach ($files as $f) {
    echo basename($f) . "\n";
}
