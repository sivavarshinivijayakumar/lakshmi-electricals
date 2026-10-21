<?php
declare(strict_types=1);
if (session_status() !== PHP_SESSION_ACTIVE) {
    session_set_cookie_params([
        'httponly' => true,
        'secure' => !empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off',
        'samesite' => 'Lax',
    ]);
    session_start();
}
require_once __DIR__ . '/../config/database.php';

function e(?string $value): string {
    return htmlspecialchars($value ?? '', ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}
function csrf_token(): string {
    if (empty($_SESSION['csrf_token'])) $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    return $_SESSION['csrf_token'];
}
function verify_csrf(): void {
    $sent = $_POST['csrf_token'] ?? '';
    if (!is_string($sent) || !hash_equals($_SESSION['csrf_token'] ?? '', $sent)) {
        http_response_code(403); exit('Invalid form token. Refresh the page and try again.');
    }
}
function current_user(): ?array { return $_SESSION['user'] ?? null; }
function require_login(): void {
    if (!current_user()) { header('Location: /lakshmi-electricals/login.php'); exit; }
}
function require_admin(): void {
    require_login();
    if ((current_user()['role'] ?? '') !== 'admin') { http_response_code(403); exit('Access denied.'); }
}
function flash(string $message, string $type='success'): void { $_SESSION['flash'] = ['message'=>$message,'type'=>$type]; }
function redirect(string $path): never { header('Location: '.$path); exit; }
function money($amount): string { return '₹'.number_format((float)$amount, 2); }
function upload_image(string $field, string $folder): ?string {
    if (empty($_FILES[$field]['name'])) return null;
    if ($_FILES[$field]['error'] !== UPLOAD_ERR_OK) throw new RuntimeException('Image upload failed.');
    if ($_FILES[$field]['size'] > 3 * 1024 * 1024) throw new RuntimeException('Image must be 3 MB or smaller.');
    $finfo = new finfo(FILEINFO_MIME_TYPE);
    $mime = $finfo->file($_FILES[$field]['tmp_name']);
    $allowed = ['image/jpeg'=>'jpg','image/png'=>'png','image/webp'=>'webp'];
    if (!isset($allowed[$mime])) throw new RuntimeException('Use a JPG, PNG or WEBP image.');
    $dir = __DIR__ . '/../uploads/' . $folder;
    if (!is_dir($dir)) mkdir($dir, 0755, true);
    $name = bin2hex(random_bytes(16)).'.'.$allowed[$mime];
    if (!move_uploaded_file($_FILES[$field]['tmp_name'], $dir.'/'.$name)) throw new RuntimeException('Could not save uploaded image.');
    return 'uploads/'.$folder.'/'.$name;
}
