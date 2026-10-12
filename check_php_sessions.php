<?php
header('Content-Type: text/plain');

$dir = '/home/manbanj2/tmp/';
$files = glob($dir . 'sess_*');
foreach ($files as $f) {
    echo "=== SESSION FILE: " . basename($f) . " (Modified: " . date('Y-m-d H:i:s', filemtime($f)) . ") ===\n";
    $content = file_get_contents($f);
    echo $content . "\n\n";
}
