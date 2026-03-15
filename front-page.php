<?php
/**
 * front-page.php — Homepage Template
 * =====================================
 * This is the FIRST page visitors see.
 * WordPress uses this file when "Front page displays: A static page" is set,
 * OR automatically as the homepage template.
 *
 * 📚 LEARN: Template hierarchy = WordPress picks templates in a specific order.
 *   front-page.php > home.php > index.php
 */

get_header(); // Loads header.php
?>

<!-- =====================================================
     SECTION 1: HERO
     ===================================================== -->
<section class="hero">
    <!-- Left: Text Content -->
    <div class="hero-content">
        <span class="label hero-label animate-fade-up">
            New Collection — Spring 2025
        </span>

        <h1 class="hero-title animate-fade-up delay-1">
            <?php
            // Text is editable from Appearance → Customize
            $heading = get_theme_mod('nb_hero_heading', 'Dress With Purpose');
            // Split into two parts to style the second part in italic
            $parts = explode(' ', $heading);
            $last_word = array_pop($parts);
            echo esc_html(implode(' ', $parts)) . ' <em>' . esc_html($last_word) . '</em>';
            ?>
        </h1>

        <p class="hero-desc animate-fade-up delay-2">
            <?php echo esc_html(get_theme_mod('nb_hero_desc', 'Curated fashion pieces for the modern wardrobe. Timeless designs, premium quality, delivered to your door.')); ?>
        </p>

        <div class="hero-actions animate-fade-up delay-3">
            <a href="<?php echo esc_url(wc_get_page_permalink('shop')); ?>" class="btn btn--primary">
                Shop Now →
            </a>
            <a href="<?php echo esc_url(home_url('/lookbook')); ?>" class="btn btn--outline">
                View Lookbook
            </a>
        </div>

        <div class="hero-stats animate-fade-up delay-4">
            <div>
                <div class="hero-stat-number">500+</div>
                <div class="hero-stat-label">Products</div>
            </div>
            <div>
                <div class="hero-stat-number">2K+</div>
                <div class="hero-stat-label">Customers</div>
            </div>
            <div>
                <div class="hero-stat-number">24h</div>
                <div class="hero-stat-label">Delivery</div>
            </div>
        </div>
    </div>

    <!-- Right: Hero Image -->
    <div class="hero-image animate-fade-in">
        <?php
        // Use a custom field or fallback to a placeholder
        $hero_image_id = get_theme_mod('nb_hero_image');
        if ($hero_image_id):
            echo wp_get_attachment_image($hero_image_id, 'hero-banner', false, ['alt' => 'Hero fashion']);
        else:
        ?>
            <!-- Placeholder gradient until you set an image in Customizer -->
            <div style="
                width:100%;height:100%;
                background: linear-gradient(135deg, #1a1a1a 0%, #3a3a3a 50%, #0a0a0a 100%);
                display:flex; align-items:center; justify-content:center;
                color:rgba(255,255,255,0.15); font-size:5rem; letter-spacing:0.1em;
                font-family:var(--font-display);
            ">
                FASHION
            </div>
        <?php endif; ?>

        <!-- Floating Badge -->
        <div class="hero-badge">
            <div class="hero-badge-title">New Arrivals</div>
            <div class="hero-badge-sub" style="margin-top:4px">
                ⭐ 4.9 / 5 from 2,000+ reviews
            </div>
        </div>
    </div>
</section>

<!-- =====================================================
     SECTION 2: DELIVERY FEATURES
     ===================================================== -->
<section class="section section--gray">
    <div class="container">
        <div class="features-grid">

            <div class="feature-card">
                <span class="feature-icon">🚚</span>
                <h3 class="feature-title">Fast Livraison</h3>
                <p class="feature-desc">
                    Free delivery on orders over
                    <?php echo nb_format_price((float)get_option('nb_free_delivery_threshold', 150)); ?>.
                    Standard 24–48h delivery.
                </p>
            </div>

            <div class="feature-card">
                <span class="feature-icon">↩️</span>
                <h3 class="feature-title">Easy Returns</h3>
                <p class="feature-desc">
                    14-day hassle-free returns. Not satisfied? We'll sort it out.
                </p>
            </div>

            <div class="feature-card">
                <span class="feature-icon">🔒</span>
                <h3 class="feature-title">Secure Payment</h3>
                <p class="feature-desc">
                    Pay by card, D17, or cash on delivery. 100% secure checkout.
                </p>
            </div>

            <div class="feature-card">
                <span class="feature-icon">💬</span>
                <h3 class="feature-title">Support 7/7</h3>
                <p class="feature-desc">
                    Questions? Our team is available 7 days a week via WhatsApp or email.
                </p>
            </div>

        </div>
    </div>
</section>

<!-- =====================================================
     SECTION 3: FEATURED PRODUCTS
     ===================================================== -->
<section class="section">
    <div class="container">
        <div class="section-header">
            <div class="section-header__row">
                <div>
                    <span class="label" style="color:var(--color-accent)">Handpicked For You</span>
                    <h2 class="section-title">Featured Collection</h2>
                </div>
                <a href="<?php echo wc_get_page_permalink('shop'); ?>" class="btn btn--outline">
                    View All →
                </a>
            </div>

            <!-- Category Filter Pills -->
            <div class="categories-strip" style="margin-top:24px">
                <button class="category-pill active" data-category="all">All</button>
                <?php
                // Load product categories from WooCommerce
                $categories = get_terms([
                    'taxonomy'   => 'product_cat',
                    'hide_empty' => true,
                    'number'     => 8,
                    'exclude'    => [get_term_by('slug', 'uncategorized', 'product_cat')->term_id ?? 0],
                ]);
                if (!is_wp_error($categories)):
                    foreach ($categories as $cat):
                ?>
                    <button class="category-pill" data-category="<?php echo esc_attr($cat->slug); ?>">
                        <?php echo esc_html($cat->name); ?>
                    </button>
                <?php
                    endforeach;
                endif;
                ?>
            </div>
        </div>

        <!-- Products Grid -->
        <div class="products-grid" id="featured-products">
            <?php
            // Query featured products (marked as featured in WooCommerce)
            $featured_args = [
                'post_type'      => 'product',
                'posts_per_page' => 8,
                'post_status'    => 'publish',
                'tax_query'      => [[
                    'taxonomy' => 'product_visibility',
                    'field'    => 'name',
                    'terms'    => 'featured',
                ]],
            ];

            $featured_products = new WP_Query($featured_args);

            if ($featured_products->have_posts()):
                while ($featured_products->have_posts()):
                    $featured_products->the_post();
                    // Load the product card template
                    get_template_part('template-parts/product-card');
                endwhile;
                wp_reset_postdata();
            else:
                // Show 4 demo cards if no WooCommerce products yet
                for ($i = 1; $i <= 8; $i++):
                    get_template_part('template-parts/product-card-demo');
                endfor;
            endif;
            ?>
        </div>
    </div>
</section>

<!-- =====================================================
     SECTION 4: PROMO BANNER
     ===================================================== -->
<section class="section" style="padding-top:0">
    <div class="container">
        <div class="promo-banner">
            <div class="promo-side promo-side--dark">
                <span class="promo-tag">New In</span>
                <h2 style="font-family:var(--font-display); font-size:clamp(1.8rem,3vw,2.8rem); color:var(--color-white); line-height:1.2">
                    Men's Summer<br><em>Essentials</em>
                </h2>
                <p style="color:rgba(255,255,255,0.65); font-size:0.95rem; line-height:1.8">
                    Clean cuts, premium fabrics, built for the warmer months.
                </p>
                <a href="<?php echo home_url('/product-category/hommes'); ?>" class="btn btn--white" style="align-self:flex-start">
                    Shop Men →
                </a>
            </div>
            <div class="promo-side promo-side--light">
                <span class="promo-tag">Up to -40%</span>
                <h2 style="font-family:var(--font-display); font-size:clamp(1.8rem,3vw,2.8rem); line-height:1.2">
                    Women's<br><em>End of Season</em>
                </h2>
                <p style="color:var(--color-gray-dark); font-size:0.95rem; line-height:1.8">
                    Premium pieces at incredible prices. Limited stock.
                </p>
                <a href="<?php echo home_url('/product-category/femmes'); ?>" class="btn btn--primary" style="align-self:flex-start">
                    Shop Women →
                </a>
            </div>
        </div>
    </div>
</section>

<!-- =====================================================
     SECTION 5: DELIVERY TRACKER TEASER
     ===================================================== -->
<section class="section section--gray">
    <div class="container">
        <div class="section-header section-header--centered">
            <span class="label">Order Tracking</span>
            <h2 class="section-title">Where Is My Order?</h2>
            <p class="section-desc" style="margin:0 auto">
                Enter your order number to track your delivery in real time.
            </p>
        </div>

        <!-- Tracker Widget -->
        <div class="tracker-card">
            <h3 class="tracker-title">Track My Order</h3>
            <div class="tracker-input-row">
                <input
                    type="text"
                    class="tracker-input"
                    id="order-tracker-input"
                    placeholder="Order number e.g. 1042"
                    maxlength="20"
                >
                <button class="btn btn--primary" id="track-order-btn">
                    🔍 Track
                </button>
            </div>

            <!-- Steps visualization (updated by JS after tracking) -->
            <div class="tracker-steps" id="tracker-steps">
                <div class="tracker-step done" data-step="0">
                    <div class="tracker-step-dot">✓</div>
                    <div class="tracker-step-label">Order Placed</div>
                </div>
                <div class="tracker-step done" data-step="1">
                    <div class="tracker-step-dot">✓</div>
                    <div class="tracker-step-label">Processing</div>
                </div>
                <div class="tracker-step active" data-step="2">
                    <div class="tracker-step-dot">🚚</div>
                    <div class="tracker-step-label">Shipped</div>
                </div>
                <div class="tracker-step" data-step="3">
                    <div class="tracker-step-dot">4</div>
                    <div class="tracker-step-label">Delivered</div>
                </div>
            </div>

            <div id="tracker-result" style="margin-top:20px; display:none">
                <p id="tracker-message" style="text-align:center; font-weight:600; padding:12px; background:var(--color-gray-light); border-radius:4px;"></p>
            </div>
        </div>
    </div>
</section>

<!-- =====================================================
     SECTION 6: NEW ARRIVALS
     ===================================================== -->
<section class="section">
    <div class="container">
        <div class="section-header">
            <div class="section-header__row">
                <div>
                    <span class="label" style="color:var(--color-accent)">Just Dropped</span>
                    <h2 class="section-title">New Arrivals</h2>
                </div>
                <a href="<?php echo home_url('/product-category/nouveautes'); ?>" class="btn btn--outline">
                    See All New →
                </a>
            </div>
        </div>

        <div class="products-grid">
            <?php
            // Query newest products
            $new_args = [
                'post_type'      => 'product',
                'posts_per_page' => 4,
                'orderby'        => 'date',
                'order'          => 'DESC',
                'post_status'    => 'publish',
            ];

            $new_products = new WP_Query($new_args);

            if ($new_products->have_posts()):
                while ($new_products->have_posts()):
                    $new_products->the_post();
                    get_template_part('template-parts/product-card');
                endwhile;
                wp_reset_postdata();
            else:
                for ($i = 1; $i <= 4; $i++):
                    get_template_part('template-parts/product-card-demo');
                endfor;
            endif;
            ?>
        </div>
    </div>
</section>

<?php get_footer(); // Loads footer.php ?>
