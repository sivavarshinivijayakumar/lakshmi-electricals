<?php
require_once __DIR__.'/includes/bootstrap.php';
$pageTitle='Lakshmi Electricals and Hardwares | Home';
$categories=$pdo->query("SELECT c.*, (SELECT COUNT(*) FROM products p WHERE p.category_id=c.id AND p.is_active=1) product_count FROM categories c ORDER BY c.name")->fetchAll();
$products=$pdo->query("SELECT p.*, c.name category_name FROM products p JOIN categories c ON c.id=p.category_id WHERE p.is_active=1 ORDER BY p.created_at DESC LIMIT 8")->fetchAll();
require __DIR__.'/includes/header.php';
?>
<section class="hero">
  <div class="hero-copy"><span class="eyebrow">YOUR LOCAL SUPPLY PARTNER</span><h1>Reliable supplies.<br><em>Work done right.</em></h1>
  <p>Explore hardware, electrical, plumbing and agricultural essentials from Lakshmi Electricals and Hardwares.</p>
  <div class="hero-actions"><a class="btn btn-gold" href="/lakshmi-electricals/products/index.php">Explore products <span>→</span></a><a class="btn btn-outline" href="/lakshmi-electricals/contact.php">Contact the shop</a></div>
  <div class="hero-note"><span class="dot"></span> Browse the catalogue and send an order request for shop confirmation.</div></div>
  
<div class="hero-art">
    <img src="/lakshmi-electricals/assets/images/lakshmi-background.png"
         alt="Lakshmi Electricals and Hardwares"
         class="hero-logo-image">
</div>
</section>
<section class="section">
  <div class="section-heading"><div><span class="eyebrow">FIND WHAT YOU NEED</span><h2>Shop by category</h2></div><a class="text-link" href="/lakshmi-electricals/products/index.php">View all products →</a></div>
  
<?php if ($categories): ?>
<div class="category-grid">
    <?php foreach ($categories as $c): ?>
        <a class="category-card"
           href="/lakshmi-electricals/products/index.php?category=<?= (int)$c['id'] ?>">

            <div class="category-cover">
                <?php
                $coverImages = [
                    'Agriculture Tools' => 'agriculture.png',
                    'Bolts and Nuts' => 'bolts.png',
                    'CPVC Items' => 'cpvc.png',
                    'Drip Irrigation Fittings' => 'drip-irrigation.png',
                    'Electrical Items' => 'electrical.png',
                    'Hoses' => 'hoses.png',
                    'Nails' => 'nails.png',
                    'Paints' => 'paints.png',
                    'PVC Items' => 'pvc.png',
                    'UPVC Items' => 'UPVC.png'
                ];
                $imageName = $coverImages[$c['name']] ?? 'default-category.jpg';
                ?>
                <img
                    src="/lakshmi-electricals/assets/images/<?= htmlspecialchars($imageName) ?>"
                    alt="<?= htmlspecialchars($c['name']) ?>"
                    class="category-cover-image"
                    onerror="this.style.display='none'"
                >
            </div>

            <div class="category-card-info">
                <span class="category-name"><?= htmlspecialchars($c['name']) ?></span>
                <small><?= (int)$c['product_count'] ?> listed products</small>
            </div>
        </a>
    <?php endforeach; ?>
</div>
<?php else: ?>
    <div class="empty-state">
        <h3>Categories are being set up</h3>
        <p>Please check back soon or contact the shop directly.</p>
    </div>
<?php endif; ?>

    
<section class="section muted-section"><div class="section-heading"><div><span class="eyebrow">RECENTLY ADDED</span><h2>Products in the catalogue</h2></div><a class="text-link" href="/lakshmi-electricals/products/index.php">Browse catalogue →</a></div>
<?php if($products): ?><div class="product-grid"><?php foreach($products as $p): ?><article class="product-card">
  <a class="product-image" href="/lakshmi-electricals/products/details.php?id=<?= (int)$p['id'] ?>"><?php if($p['image_path']): ?><img src="/lakshmi-electricals/<?= e($p['image_path']) ?>" alt="<?= e($p['name']) ?>"><?php else: ?><span><?= e(strtoupper(substr($p['name'],0,1))) ?></span><?php endif; ?></a>
  <div class="product-info"><small><?= e($p['category_name']) ?></small><h3><a href="/lakshmi-electricals/products/details.php?id=<?= (int)$p['id'] ?>"><?= e($p['name']) ?></a></h3><?php if($p['price']!==null): ?><strong class="price"><?= money($p['price']) ?><?= $p['unit']?' / '.e($p['unit']):'' ?></strong><?php else: ?><strong class="price">Price on enquiry</strong><?php endif; ?><p><?= (int)$p['stock_quantity']>0?'In stock':'Currently unavailable' ?></p></div>
</article><?php endforeach; ?></div><?php else: ?><div class="empty-state"><h3>Products will appear here</h3><p>No products have been added yet. Please contact the shop for availability.</p></div><?php endif; ?></section>
<section class="contact-strip"><div><span class="eyebrow">NEED HELP FINDING SOMETHING?</span><h2>Talk to the shop.</h2><p>Call or send an enquiry about product availability and specifications.</p></div><div class="strip-actions"><a class="btn btn-gold" href="tel:9362070460">Call now</a><a class="btn btn-outline" target="_blank" rel="noopener" href="https://wa.me/919362070460">WhatsApp enquiry</a></div></section>
<?php require __DIR__.'/includes/footer.php'; ?>
