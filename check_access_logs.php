<?php
header('Content-Type: text/plain');

$possible_paths = [
    '/home/manbanj2/access-logs/',
    '/home/manbanj2/logs/',
    '/home/manbanj2/public_html/application/logs/',
    '/home/manbanj2/tmp/',
];

foreach ($possible_paths as $p) {
    echo "=== PATH: $p ===\n";
    if (is_dir($p)) {
        $files = scandir($p);
        foreach ($files as $file) {
            if ($file !== '.' && $file !== '..') {
                $full = $p . $file;
                $size = is_file($full) ? filesize($full) : 0;
                echo "  $file (" . round($size / 1024, 1) . " KB)\n";
                // Jika file access log man3banjar.sch.id
                if (stripos($file, 'man3banjar') !== false || stripos($file, 'access') !== false) {
                    echo "    --- SCANNING CONTENT FOR upload / zona_integritas ---\n";
                    $lines = file($full);
                    $matches = [];
                    foreach ($lines as $line) {
                        if (stripos($line, 'upload_drive') !== false || stripos($line, 'upload') !== false || stripos($line, 'zona_integritas') !== false || stripos($line, 'POST') !== false) {
                            if (stripos($line, '12/Oct/2026:01:') !== false || stripos($line, '12/Oct/2026:06:') !== false || stripos($line, '12/Oct/2026:09:') !== false || stripos($line, '12/Oct/2026:14:') !== false) {
                                $matches[] = trim($line);
                            }
                        }
                    }
                    echo "    Found " . count($matches) . " matching log entries:\n";
                    foreach (array_slice($matches, -20) as $m) {
                        echo "      $m\n";
                    }
                }
            }
        }
    } else {
        echo "  Directory not found.\n";
    }
}
