<?php
require_once __DIR__.'/../includes/bootstrap.php';require_login();
block_admin_purchases();
$cart=$_SESSION['cart']??[];$error='';
if(!$cart){flash('Your cart is empty.','error');redirect('/lakshmi-electricals/cart/index.php');}
if($_SERVER['REQUEST_METHOD']==='POST'){
 verify_csrf();$phone=trim($_POST['phone']??'');$fulfilment=$_POST['fulfilment']??'pickup';$notes=trim($_POST['notes']??'');
 if(!in_array($fulfilment,['pickup','delivery'],true))$error='Choose a valid fulfilment preference.';
 else{
  try{$pdo->beginTransaction();$ids=array_map('intval',array_keys($cart));$in=implode(',',array_fill(0,count($ids),'?'));$st=$pdo->prepare("SELECT id,name,price,stock_quantity,is_active FROM products WHERE id IN ($in) FOR UPDATE");$st->execute($ids);$rows=$st->fetchAll();$byId=[];foreach($rows as $r)$byId[$r['id']]=$r;
   $total=0;$orderItems=[];foreach($cart as $id=>$qty){$id=(int)$id;$qty=(int)$qty;if(!isset($byId[$id])||!$byId[$id]['is_active'])throw new RuntimeException('A product in your cart is no longer available.');$p=$byId[$id];if($p['price']===null)throw new RuntimeException('A product requires a price confirmation. Please remove it and contact the shop.');if($qty<1||$qty>(int)$p['stock_quantity'])throw new RuntimeException('Stock changed for '.$p['name'].'. Update your cart and try again.');$sub=(float)$p['price']*$qty;$total+=$sub;$orderItems[]=[$id,$p['name'],$qty,$p['price'],$sub];}
   $st=$pdo->prepare("INSERT INTO orders(user_id,contact_phone,fulfilment,notes,status,total_amount) VALUES(?,?,?,?, 'Pending',?)");$st->execute([current_user()['id'],$phone?:null,$fulfilment,$notes?:null,$total]);$orderId=(int)$pdo->lastInsertId();$st=$pdo->prepare("INSERT INTO order_items(order_id,product_id,product_name,quantity,unit_price,subtotal) VALUES(?,?,?,?,?,?)");foreach($orderItems as $it)$st->execute([$orderId,$it[0],$it[1],$it[2],$it[3],$it[4]]);
   $pdo->commit();unset($_SESSION['cart']);flash('Order request submitted. The shop will contact you to confirm availability and details.');redirect('/lakshmi-electricals/user/orders.php');
  }catch(Throwable $ex){if($pdo->inTransaction())$pdo->rollBack();$error=$ex instanceof RuntimeException?$ex->getMessage():'Could not submit your request. Please try again.';}
 }
}
$st=$pdo->prepare("SELECT name,email,phone FROM users WHERE id=?");$st->execute([current_user()['id']]);$profile=$st->fetch();
$pageTitle='Submit order request';require __DIR__.'/../includes/header.php';
?>
<section class="page-heading"><span class="eyebrow">FINAL STEP</span><h1>Order request</h1><p>Review your details. This does not take payment or guarantee stock until the shop confirms.</p></section>
<div class="checkout-grid"><form class="form-card" method="post"><h2>Contact and fulfilment</h2><?php if($error): ?><div class="alert error"><?= e($error) ?></div><?php endif; ?><input type="hidden" name="csrf_token" value="<?= e(csrf_token()) ?>"><label>Name<input value="<?= e($profile['name']) ?>" disabled></label><label>Email<input value="<?= e($profile['email']) ?>" disabled></label><label>Contact phone<input name="phone" value="<?= e($profile['phone']??'') ?>" maxlength="30"></label><label>Preference<select name="fulfilment"><option value="pickup">Pickup from shop</option><option value="delivery">Delivery — confirm with shop</option></select></label><label>Notes / requirements<textarea name="notes" rows="4" maxlength="1000" placeholder="Optional details"></textarea></label><button class="btn btn-gold full">Submit order request</button></form><aside class="summary-card"><h2>Order summary</h2><?php $sum=0; foreach($cart as $id=>$qty): $st=$pdo->prepare("SELECT name,price FROM products WHERE id=?");$st->execute([(int)$id]);$p=$st->fetch();if($p):$sub=(float)$p['price']*(int)$qty;$sum+=$sub; ?><div class="summary-line"><span><?= e($p['name']) ?> × <?= (int)$qty ?></span><strong><?= money($sub) ?></strong></div><?php endif; endforeach; ?><hr><div class="summary-line total-line"><span>Estimated total</span><strong><?= money($sum) ?></strong></div><p class="muted">Final availability and details require shop confirmation. No payment is collected here.</p></aside></div>
<?php require __DIR__.'/../includes/footer.php'; ?>
