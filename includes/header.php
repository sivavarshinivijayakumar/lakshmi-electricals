<?php
require_once __DIR__ . '/bootstrap.php';
$pageTitle = $pageTitle ?? 'Lakshmi Electricals and Hardwares';
$u = current_user();
$flashMessage = $_SESSION['flash'] ?? null;
unset($_SESSION['flash']);
$base = '/lakshmi-electricals';
?>
<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1">
<title><?= e($pageTitle) ?></title>
<meta name="description" content="Browse hardware, electrical, plumbing and agricultural supplies at Lakshmi Electricals and Hardwares.">
<link rel="stylesheet" href="<?= $base ?>/assets/css/style.css">
</head>
<body>
<header class="site-header">
  <a class="brand" href="<?= $base ?>/index.php">
    <img src="<?= $base ?>/assets/images/lakshmi-logo.png" alt="Lakshmi Electricals and Hardwares" class="brand-logo">
  </a>
  <button class="nav-toggle" aria-label="Toggle navigation" onclick="document.querySelector('.nav-links').classList.toggle('open')">☰</button>
  <nav class="nav-links">
    <a href="<?= $base ?>/index.php">Home</a><a href="<?= $base ?>/products/index.php">Products</a><a href="<?= $base ?>/contact.php">Contact</a>
    <?php if ($u): ?>
      <a href="<?= $base ?>/user/orders.php">My Orders</a>
      <?php if ($u['role']==='admin'): ?><a href="<?= $base ?>/admin/dashboard.php">Admin</a><?php endif; ?>
      <a href="<?= $base ?>/logout.php">Logout</a>
    <?php else: ?><a href="<?= $base ?>/login.php">Login</a><a class="nav-cta" href="<?= $base ?>/register.php">Create account</a><?php endif; ?>
    <a href="<?= $base ?>/cart/index.php">Cart (<?= array_sum($_SESSION['cart'] ?? []) ?>)</a>
  </nav>
</header>
<main class="page-shell">
<?php if ($flashMessage): ?><div class="alert <?= e($flashMessage['type']) ?>"><?= e($flashMessage['message']) ?></div><?php endif; ?>
