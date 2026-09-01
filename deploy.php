<?php
/**
 * ═══════════════════════════════════════════════════════════════════════════
 * GITHUB AUTO-DEPLOY WEBHOOK — MAN 3 BANJAR
 * ═══════════════════════════════════════════════════════════════════════════
 * Script ini menerima sinyal webhook dari GitHub saat ada "git push" baru
 * dan secara otomatis menjalankan "git pull origin main" di hosting DomaiNesia
 * tanpa perlu login ke cPanel atau menekan "Update from Remote".
 */

// Secret Token Keamanan
$secret_token = 'MAN3BANJAR_DEPLOY_SECRET_KEY_2026';

header('Content-Type: application/json; charset=utf-8');

// 1. Verifikasi Token Keamanan
$auth_valid = false;

// Cek via Query Parameter: deploy.php?secret=...
if (isset($_GET['secret']) && $_GET['secret'] === $secret_token) {
    $auth_valid = true;
}

// Cek via GitHub HMAC SHA256 Signature Header (X-Hub-Signature-256)
$github_sig = $_SERVER['HTTP_X_HUB_SIGNATURE_256'] ?? '';
$payload = file_get_contents('php://input');

if (!empty($github_sig) && !empty($payload)) {
    $expected_sig = 'sha256=' . hash_hmac('sha256', $payload, $secret_token);
    if (hash_equals($expected_sig, $github_sig)) {
        $auth_valid = true;
    }
}

if (!$auth_valid) {
    http_response_code(403);
    echo json_encode([
        'status' => 'error',
        'message' => 'Akses ditolak: Secret token tidak valid.'
    ]);
    exit;
}

// 2. Eksekusi Perintah Git Pull
$repo_dir = __DIR__;
$output = [];
$return_var = 0;

// Perintah pembaruan git
$commands = [
    "cd {$repo_dir} 2>&1",
    "git fetch origin main 2>&1",
    "git reset --hard origin/main 2>&1",
    "git pull origin main 2>&1"
];

$cmd = implode(" && ", $commands);

if (function_exists('exec')) {
    exec($cmd, $output, $return_var);
} elseif (function_exists('shell_exec')) {
    $out = shell_exec($cmd);
    $output = explode("\n", (string)$out);
} elseif (function_exists('passthru')) {
    ob_start();
    passthru($cmd, $return_var);
    $out = ob_get_clean();
    $output = explode("\n", (string)$out);
} else {
    echo json_encode([
        'status' => 'warning',
        'message' => 'Fungsi shell_exec/exec dinonaktifkan di server hosting.',
        'repo_dir' => $repo_dir
    ]);
    exit;
}

// 3. Respon JSON Sukses
echo json_encode([
    'status' => ($return_var === 0) ? 'success' : 'info',
    'timestamp' => date('Y-m-d H:i:s'),
    'message' => 'Auto-deploy berhasil dieksekusi.',
    'output' => $output
], JSON_PRETTY_PRINT);
