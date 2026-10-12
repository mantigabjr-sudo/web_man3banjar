<?php
$files = glob('/home/manbanj2/tmp/sess_*');
foreach ($files as $f) {
    $content = file_get_contents($f);
    if (strpos($content, '__ci_last_regenerate') !== false || strpos($content, 'zi_unlocked') !== false || strpos($content, 'user') !== false) {
        echo "=== CI SESSION: " . basename($f) . " ===\n";
        echo $content . "\n";
    }
}
