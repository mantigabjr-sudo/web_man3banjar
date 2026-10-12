<?php
header('Content-Type: text/plain');

$f = __DIR__ . '/assets/downloads/1791762722_2d-monev-pemanfaatan-teknologi-informasi.pdf';
if (!file_exists($f)) {
    die("File tidak ada\n");
}

$content = file_get_contents($f);

// Ekstrak teks stream flateDecode sederhana
$text = '';
if (preg_match_all('/stream[\r\n]+(.*?)[\r\n]+endstream/s', $content, $matches)) {
    foreach ($matches[1] as $stream) {
        $decompressed = @gzuncompress($stream);
        if ($decompressed) {
            // Bersihkan syntax PDF operators Tj, TJ
            preg_match_all('/\((.*?)\)\s*Tj/s', $decompressed, $tj);
            if (!empty($tj[1])) {
                $text .= implode(" ", $tj[1]) . "\n";
            }
            preg_match_all('/\[(.*?)\]\s*TJ/s', $decompressed, $tjs);
            if (!empty($tjs[1])) {
                foreach ($tjs[1] as $tj_item) {
                    preg_match_all('/\((.*?)\)/s', $tj_item, $words);
                    $text .= implode("", $words[1]) . " ";
                }
                $text .= "\n";
            }
        }
    }
}

// Tampilkan baris-baris yang mengandung nama atau susunan tim
$lines = explode("\n", $text);
foreach ($lines as $l) {
    $l_clean = trim($l);
    if (strlen($l_clean) > 3) {
        if (stripos($l_clean, 'Ketua') !== false || stripos($l_clean, 'Sekretaris') !== false || stripos($l_clean, 'Anggota') !== false || stripos($l_clean, 'Pembina') !== false || stripos($l_clean, 'Penanggung') !== false || stripos($l_clean, 'S.Pd') !== false || stripos($l_clean, 'S.Ag') !== false || stripos($l_clean, 'M.Pd') !== false || stripos($l_clean, 'Drs') !== false || stripos($l_clean, 'NIP') !== false) {
            echo $l_clean . "\n";
        }
    }
}
