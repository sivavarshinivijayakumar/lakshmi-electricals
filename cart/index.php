<?php
require_once __DIR__ . '/../includes/bootstrap.php';
require_once __DIR__ . '/../includes/auth.php';

block_admin_purchases();
if($_SERVER['REQUEST_METHOD']==='POST'){verify_csrf();$action=$_POST['action']??'';$id=(int)($_POST['product_id']??0);if($action==='remove'){unset($_SESSION['cart'][$id]);}elseif($action==='update'){foreach(($_POST['quantities']??[]) as $pid=>$qty){$pid=(int)$pid;$qty=(int)$qty;if($qty<1)unset($_SESSION['cart'][$pid]);else $_SESSION['cart'][$pid]=min(100,$qty);}}redirect('/lakshmi-electricals/cart/index.php');}
$cart=$_SESSION['cart']??[];$items=[];$total=0;
if($cart){$ids=array_map('intval',array_keys($cart));$in=implode(',',array_fill(0,count($ids),'?'));$st=$pdo->prepare("SELECT id,name,price,unit,stock_quantity,is_active FROM products WHERE id IN ($in)");$st->execute($ids);foreach($st as $p){$qty=(int)$cart[$p['id']];$p['quantity']=$qty;$p['subtotal']=(float)$p['price']*$qty;$items[]=$p;$total+=$p['subtotal'];}}
$pageTitle='Your cart | Lakshmi Electricals';require __DIR__.'/../includes/header.php';
?>
<section class="page-heading"><span class="eyebrow">YOUR SELECTION</span><h1>Shopping cart</h1><p>This is an order request; the shop will confirm availability and final details.</p></section>
<?php if(!$items): ?><div class="empty-state"><h3>Your cart is empty</h3><p>Explore the catalogue and add products you need.</p><a class="btn btn-gold" href="/lakshmi-electricals/products/index.php">Explore products</a></div><?php else: ?>
<form method="post" class="cart-table"><input type="hidden" name="csrf_token" value="<?= e(csrf_token()) ?>"><input type="hidden" name="action" value="update"><div class="table-scroll"><table><thead><tr><th>Product</th><th>Unit price</th><th>Quantity</th><th>Subtotal</th><th></th></tr></thead><tbody><?php foreach($items as $it): ?><tr><td><?= e($it['name']) ?><?= !$it['is_active']?' <span class="muted">(inactive)</span>':'' ?></td><td><?= $it['price']!==null?money($it['price']):'On enquiry' ?></td><td><input class="qty-input" type="number" name="quantities[<?= (int)$it['id'] ?>]" value="<?= (int)$it['quantity'] ?>" min="0" max="100"></td><td><?= money($it['subtotal']) ?></td><td><button class="link-button" form="remove-<?= (int)$it['id'] ?>">Remove</button></td></tr><?php endforeach; ?></tbody></table></div><div class="cart-bottom"><button class="btn btn-outline">Update cart</button><div><span>Total (estimate)</span><strong class="price"><?= money($total) ?></strong></div></div></form>
<?php foreach($items as $it): ?><form id="remove-<?= (int)$it['id'] ?>" method="post" hidden><input name="csrf_token" value="<?= e(csrf_token()) ?>"><input name="action" value="remove"><input name="product_id" value="<?= (int)$it['id'] ?>"></form><?php endforeach; ?>
<div class="checkout-panel"><div><h2>Ready to send your request?</h2><p>Sign in to submit an order request and track its status. No online payment is taken.</p></div><a class="btn btn-gold" href="/lakshmi-electricals/cart/submit-order.php">Continue to order request →</a></div>
<?php endif; ?>
<?php require __DIR__.'/../includes/footer.php'; ?>
