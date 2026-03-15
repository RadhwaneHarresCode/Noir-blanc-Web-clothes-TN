<?php
/**
 * template-parts/product-card-demo.php
 * ========================================
 * 📚 LEARN: This is a "fallback" template.
 * It shows demo content before you add real products in WooCommerce.
 * Delete this once you have real products added!
 */

// Demo products array
static $demo_index = 0;
$demo_products = [
    ['name' => 'Classic Linen Shirt', 'cat' => 'Men',   'price' => '89.00',  'sale' => null,    'badge' => 'new',  'emoji' => '👔'],
    ['name' => 'Wide-Leg Trousers',   'cat' => 'Women', 'price' => '129.00', 'sale' => '89.00', 'badge' => 'sale', 'emoji' => '👖'],
    ['name' => 'Merino Wool Coat',    'cat' => 'Unisex','price' => '320.00', 'sale' => null,    'badge' => 'hot',  'emoji' => '🧥'],
    ['name' => 'Silk Midi Dress',     'cat' => 'Women', 'price' => '185.00', 'sale' => null,    'badge' => 'new',  'emoji' => '👗'],
    ['name' => 'Cargo Chino Pants',   'cat' => 'Men',   'price' => '99.00',  'sale' => '69.00', 'badge' => 'sale', 'emoji' => '🩳'],
    ['name' => 'Knit Turtleneck',     'cat' => 'Unisex','price' => '115.00', 'sale' => null,    'badge' => null,   'emoji' => '🧶'],
    ['name' => 'Oversized Blazer',    'cat' => 'Women', 'price' => '210.00', 'sale' => null,    'badge' => 'hot',  'emoji' => '🪱'],
    ['name' => 'Oxford Button-Down',  'cat' => 'Men',   'price' => '75.00',  'sale' => null,    'badge' => 'new',  'emoji' => '👕'],
];

$p = $demo_products[$demo_index % count($demo_products)];
$demo_index++;

$badge_class = [
    'new'  => 'badge--new',
    'sale' => 'badge--sale',
    'hot'  => 'badge--hot',
][$p['badge'] ?? ''] ?? '';
?>

<article class="product-card demo-card">
    <div class="product-card__image">
        <!-- Decorative placeholder -->
        <div style="
            width:100%; height:100%;
            background: linear-gradient(135deg, #e8e8e4 0%, #d0d0cc 100%);
            display: flex; align-items: center; justify-content: center;
            font-size: 4rem;
        ">
            <?php echo $p['emoji']; ?>
        </div>

        <?php if ($p['badge']): ?>
            <span class="product-card__badge <?php echo $badge_class; ?>">
                <?php echo ucfirst($p['badge']); ?>
            </span>
        <?php endif; ?>

        <div class="product-card__actions">
            <button class="product-card__action-btn" title="Wishlist">♡</button>
            <button class="product-card__action-btn" title="Quick View">👁</button>
        </div>
    </div>

    <div class="product-card__body">
        <div class="product-card__category"><?php echo esc_html($p['cat']); ?></div>

        <h3 class="product-card__name">
            <a href="#"><?php echo esc_html($p['name']); ?></a>
        </h3>

        <div class="product-card__price-row">
            <span class="price--current">
                <?php echo nb_format_price((float)($p['sale'] ?: $p['price'])); ?>
            </span>
            <?php if ($p['sale']): ?>
                <span class="price--old"><?php echo nb_format_price((float)$p['price']); ?></span>
            <?php endif; ?>
        </div>

        <div class="product-card__sizes">
            <?php foreach (['XS', 'S', 'M', 'L', 'XL'] as $sz): ?>
                <span class="size-dot"><?php echo $sz; ?></span>
            <?php endforeach; ?>
        </div>

        <button class="add-to-cart-btn" style="cursor:default; opacity:0.6">
            <?php nb_icon('cart'); ?>
            Add to Cart
        </button>
    </div>
</article>
