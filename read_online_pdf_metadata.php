<?php
header('Content-Type: text/plain');

$files = [
    'assets/downloads/1791780202_1a-presedur-operasional-tetap-sop.pdf',
    'assets/downloads/1791762452_1b-penerapan-prosedur-operasional-tetap-sop.pdf',
    'assets/downloads/1791762567_2a-pengukuran-kinerja-menggunakan-teknologi.pdf',
    'assets/downloads/1791762722_2d-monev-pemanfaatan-teknologi-informasi.pdf',
    'assets/downloads/1791762289_sop.pdf',
    'assets/downloads/1791772071_sk-wakamad-man-3-banjar.docx'
];

foreach ($files as $rel) {
    $f = __DIR__ . '/' . $rel;
    echo "=== FILE: $rel ===\n";
    if (!file_exists($f)) {
        echo "File tidak ditemukan.\n";
        continue;
    }
    
    if (substr($f, -5) === '.docx') {
        $zip = new ZipArchive();
        if ($zip->open($f) === TRUE) {
            $props = $zip->getFromName('docProps/core.xml');
            if ($props) {
                echo "DOCX Props:\n" . $props . "\n";
            }
            $zip->close();
        }
        continue;
    }

    $c = file_get_contents($f);
    preg_match_all('/\/(Author|Creator|Producer|Title)\s*(\([^\)]+\)|<[^>]+>)/i', $c, $m);
    if (!empty($m[0])) {
        foreach (array_unique($m[0]) as $val) {
            echo "  $val\n";
        }
    }
    
    preg_match_all('/<(?:dc:creator|xmp:CreatorTool|pdf:Producer|dc:title|pdfx:[^>]+)>([^<]+)<\//i', $c, $xm);
    if (!empty($xm[0])) {
        foreach (array_unique($xm[0]) as $xval) {
            echo "  XMP: $xval\n";
        }
    }

    // Cari juga bila ada teks nama di dalam PDF (misal di halaman pertama/terakhir: NIP, Nama, Mengetahui, Penyusun)
    preg_match_all('/(?:Disusun|Penyusun|Pengunggah|Oleh|Nama|Ketua|Koordinator)[\s\:\/]{1,15}([A-Za-z\s\,\.]{3,40})/i', $c, $matches_names);
    if (!empty($matches_names[0])) {
        echo "  Detected Name Hints:\n";
        foreach (array_slice(array_unique($matches_names[0]), 0, 5) as $nh) {
            echo "    - " . trim($nh) . "\n";
        }
    }
}
