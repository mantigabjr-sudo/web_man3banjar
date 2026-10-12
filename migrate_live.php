<?php
/**
 * Script migrasi / update data server live
 */
define('BASEPATH', true);
define('ENVIRONMENT', 'production');
require __DIR__ . '/application/config/database.php';

$conf = $db['default'] ?? null;
if (!$conf) {
    die("Database config tidak ditemukan.");
}

$mysqli = new mysqli($conf['hostname'], $conf['username'], $conf['password'], $conf['database']);
if ($mysqli->connect_error) {
    die("Koneksi gagal: " . $mysqli->connect_error);
}

// Update dokumen Kode etik pegawai dari shared ke area3 (Pokja III)
$sql = "UPDATE website_download 
        SET area_zi = 'area3', lini_unit = 'Tim Pokja III' 
        WHERE (LOWER(judul) LIKE '%kode etik%') AND (area_zi = 'shared' OR area_zi IS NULL OR area_zi = '')";
$mysqli->query($sql);
$affected = $mysqli->affected_rows;

// Tampilkan status data
$check = $mysqli->query("SELECT id, judul, area_zi, pengunggah, lini_unit FROM website_download WHERE LOWER(judul) LIKE '%kode etik%'");
$rows = [];
if ($check) {
    while ($row = $check->fetch_assoc()) {
        $rows[] = $row;
    }
}

echo "Migrasi Selesai. Affected rows: " . $affected . "\n";
echo "Data terkini:\n" . json_encode($rows, JSON_PRETTY_PRINT);
$mysqli->close();
