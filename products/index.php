<?php
require_once __DIR__.'/../includes/bootstrap.php';
$pageTitle='Products | Lakshmi Electricals';
$categories=$pdo->query("SELECT id,name FROM categories ORDER BY name")->fetchAll();
$category=(int)($_GET['category']??0);$q=trim($_GET['q']??'');
$sql="SELECT p.*,c.name category_name FROM products p JOIN categories c ON c.id=p.category_id WHERE p.is_active=1";$args=[];
if($category){$sql.=" AND p.category_id=?";$args[]=$category;}
if($q!==''){$sql.=" AND (p.name LIKE ? OR p.description LIKE ? OR p.brand LIKE ?)";$like='%'.$q.'%';array_push($args,$like,$like,$like);}
$sql.=" ORDER BY p.name";$st=$pdo->prepare($sql);$st->execute($args);$products=$st->fetchAll();
require __DIR__.'/../includes/header.php';
?>
<section class="page-heading"><span class="eyebrow">THE CATALOGUE</span><h1>Products & supplies</h1><p>Search products or choose a category to narrow the list.</p></section>
<form class="search-bar" method="get"><input name="q" value="<?= e($q) ?>" placeholder="Search by product, brand or description"><select name="category"><option value="0">All categories</option><?php foreach($categories as $c): ?><option value="<?= (int)$c['id'] ?>" <?= $category===(int)$c['id']?'selected':'' ?>><?= e($c['name']) ?></option><?php endforeach; ?></select><button class="btn btn-gold">Search</button></form>
<?php if($products): ?><div class="product-grid"><?php foreach($products as $p): ?><article class="product-card"><a class="product-image" href="/lakshmi-electricals/products/details.php?id=<?= (int)$p['id'] ?>"><?php if($p['image_path']): ?><img src="/lakshmi-electricals/<?= e($p['image_path']) ?>" alt="<?= e($p['name']) ?>"><?php else: ?><span><?= e(strtoupper(substr($p['name'],0,1))) ?></span><?php endif; ?></a><div class="product-info"><small><?= e($p['category_name']) ?></small><h3><a href="/lakshmi-electricals/products/details.php?id=<?= (int)$p['id'] ?>"><?= e($p['name']) ?></a></h3><p><?= e(mb_strimwidth($p['description']??'',0,100,'…')) ?></p><strong class="price"><?= $p['price']!==null?money($p['price']):'Price on enquiry' ?></strong><p><?= (int)$p['stock_quantity']>0?'In stock':'Currently unavailable' ?></p><a class="text-link" href="/lakshmi-electricals/products/details.php?id=<?= (int)$p['id'] ?>">View details →</a></div></article><?php endforeach; ?></div><?php else: ?><div class="empty-state"><h3>No products found</h3><p>Try another search or category, or contact the shop for assistance.</p><a class="btn btn-outline" href="/lakshmi-electricals/products/index.php">Clear filters</a></div><?php endif; ?>
<?php require __DIR__.'/../includes/footer.php'; ?>
