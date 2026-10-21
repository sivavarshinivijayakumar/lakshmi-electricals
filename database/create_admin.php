<?php
// Run once from the command line: php database/create_admin.php
// Do not expose this script through a web server.
if (PHP_SAPI !== 'cli') { http_response_code(403); exit("Run this script from the command line only.\n"); }
require __DIR__ . '/../config/database.php';
$name = trim(readline('Admin full name: '));
$email = strtolower(trim(readline('Admin email: ')));
echo "Admin password (input may be visible in terminal): ";
$password = trim(fgets(STDIN));
if ($name === '' || !filter_var($email, FILTER_VALIDATE_EMAIL) || strlen($password) < 12) {
    fwrite(STDERR, "Name/email invalid, or password is shorter than 12 characters.\n"); exit(1);
}
$st = $pdo->prepare("INSERT INTO users(name,email,password_hash,role,is_active) VALUES(?,?,?,'admin',1)");
try { $st->execute([$name,$email,password_hash($password,PASSWORD_DEFAULT)]); echo "Admin account created.\n"; }
catch (PDOException $e) { fwrite(STDERR, "Could not create admin. The email may already exist.\n"); exit(1); }
