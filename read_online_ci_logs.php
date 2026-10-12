<?php
header('Content-Type: text/plain');
$log_dir = __DIR__ . '/application/logs/';
if (is_dir($log_dir)) {
    $files = scandir($log_dir);
    foreach ($files as $f) {
        if (strpos($f, 'log-2026-10') !== false) {
            echo "=== LOG FILE: $f ===\n";
            $content = file_get_contents($log_dir . $f);
            // Cari kata pengunggah atau upload atau nama file
            $lines = explode("\n", $content);
            foreach ($lines as $line) {
                if (stripos($line, 'upload') !== false || stripos($line, 'website') !== false || stripos($line, '17917') !== false) {
                    echo $line . "\n";
                }
            }
        }
    }
} else {
    echo "Log dir not found\n";
}
