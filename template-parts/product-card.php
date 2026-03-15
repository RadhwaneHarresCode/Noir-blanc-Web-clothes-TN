<?php
/**
 * template-parts/product-card.php
 * ===================================
 * 📚 LEARN: Template parts are reusable pieces of HTML.
 * Call them with: get_template_part('template-parts/product-card')
 * This uses WordPress + WooCommerce to pull real product data.
 */

// Get the WooCommerce product object from current loop post
global $product;
$product = wc_get_product(get_the_ID());

if (!$product) return;

$product_id   = $product->get_id();
$product_name = $product->get_name();
$price        = $product->get_price();
$regular      = $product->get_regular_price();
$sale         = $product->get_sale_price();
$is_on_sale   = $product->is_on_sale();
$is_new       = (time() - strtotime($product->get_date_created())) < (30 * 24 * 60 * 60); // < 30 days
$is_featured  = $product->is_featured();
$categories   = wp_get_post_terms($product_id, 'product_cat');
$cat_name     = !empty($categories) ? $categories[0]->name : '';
$product_url  = get_permalink($product_id);
$img_url      = get_the_post_thumbnail_url($product_id, 'product-card');

// Get available sizes (attribute 'pa_size')
$sizes = [];
if ($product->is_type('variable')) {
    $attributes = $product->get_variation_attributes();
    $sizes = $attributes['attribute_pa_taille'] ?? $attributes['attribute_pa_size'] ?? [];
}
?>

<article class="product-card" data-product-id="<?php echo esc_attr($product_id); ?>">

    <!-- Image -->
    <div class="product-card__image">
        <?php if ($img_url): ?>
            <img
                src="<?php echo esc_url($img_url); ?>"
                alt="<?php echo esc_attr($product_name); ?>"
                loading="lazy"
            >
        <?php else: ?>
            <!-- Placeholder if no image uploaded -->
            <div style="width:100%;height:100%;background:var(--color-gray-light);display:flex;align-items:center;justify-content:center;color:var(--color-gray-mid);font-size:3rem;">
                👔
            </div>
        <?php endif; ?>

        <!-- Badge: NEW or SALE -->
        <?php if ($is_on_sale): ?>
            <span class="product-card__badge badge--sale">Sale</span>
        <?php elseif ($is_new): ?>
            <span class="product-card__badge badge--new">New</span>
        <?php elseif ($is_featured): ?>
            <span class="product-card__badge badge--hot">★ Hot</span>
        <?php endif; ?>

        <!-- Hover action buttons -->
        <div class="product-card__actions">
            <button
                class="product-card__action-btn wishlist-btn"
                title="Add to Wishlist"
                data-product-id="<?php echo esc_attr($product_id); ?>"
            >
                ♡
            </button>
            <a href="<?php echo esc_url($product_url); ?>" class="product-card__action-btn" title="Quick View">
                👁
            </a>
        </div>
    </div><!-- .product-card__image -->

    <!-- Body -->
    <div class="product-card__body">
        <div class="product-card__category"><?php echo esc_html($cat_name); ?></div>

        <h3 class="product-card__name">
            <a href="<?php echo esc_url($product_url); ?>">
                <?php echo esc_html($product_name); ?>
            </a>
        </h3>

        <!-- Price -->
        <div class="product-card__price-row">
            <span class="price--current">
                <?php echo nb_format_price((float)($sale ?: $price)); ?>
            </span>
            <?php if ($is_on_sale && $regular): ?>
                <span class="price--old"><?php echo nb_format_price((float)$regular); ?></span>
            <?php endif; ?>
        </div>

        <!-- Sizes (if variable product) -->
        <?php if (!empty($sizes)): ?>
            <div class="product-card__sizes">
                <?php foreach ($sizes as $size): ?>
                    <span class="size-dot"><?php echo esc_html(strtoupper($size)); ?></span>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <!-- Add to Cart Button -->
        <button
            class="add-to-cart-btn nb-add-to-cart"
            data-product-id="<?php echo esc_attr($product_id); ?>"
            data-product-name="<?php echo esc_attr($product_name); ?>"
            <?php if (!$product->is_purchasable() || !$product->is_in_stock()): ?>
                disabled style="opacity:0.5;cursor:not-allowed"
            <?php endif; ?>
        >
            <?php if ($product->is_in_stock()): ?>
                <?php nb_icon('cart'); ?>
                Add to Cart
            <?php else: ?>
                Out of Stock
            <?php endif; ?>
        </button>
    </div><!-- .product-card__body -->

</article>
