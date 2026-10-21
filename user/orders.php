<?php
require_once __DIR__.'/../includes/bootstrap.php';require_login();
$st=$pdo->prepare("SELECT * FROM orders WHERE user_id=? ORDER BY created_at DESC");$st->execute([current_user()['id']]);$orders=$st->fetchAll();
$pageTitle='My orders';require __DIR__.'/../includes/header.php';
?>
<section class="page-heading"><span class="eyebrow">ACCOUNT</span><h1>My order requests</h1><p>See your submitted requests and the latest status.</p></section>
<?php if(!$orders): ?><div class="empty-state"><h3>No requests yet</h3><p>Your submitted order requests will appear here.</p><a class="btn btn-gold" href="/lakshmi-electricals/products/index.php">Browse products</a></div><?php else: ?><div class="orders-list"><?php foreach($orders as $o): ?><article class="order-card"><div class="order-top"><div><small>REQUEST #<?= (int)$o['id'] ?></small><h2><?= date('d M Y, h:i A',strtotime($o['created_at'])) ?></h2></div><span class="status"><?= e($o['status']) ?></span></div><p><?= e(ucfirst($o['fulfilment'])) ?> · <?= money($o['total_amount']) ?> estimated</p><details><summary>View requested items</summary><?php $it=$pdo->prepare("SELECT * FROM order_items WHERE order_id=?");$it->execute([$o['id']]);foreach($it as $line): ?><p><?= e($line['product_name']) ?> — <?= (int)$line['quantity'] ?> × <?= money($line['unit_price']) ?></p><?php endforeach; ?></details></article><?php endforeach; ?></div><?php endif; ?>
<?php require __DIR__.'/../includes/footer.php'; ?>
