<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php wp_head(); // WordPress loads all styles, scripts, SEO here ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<!-- =====================================================
     ANNOUNCEMENT BAR
     📚 Text is editable via Appearance → Customize → Shop Settings
     ===================================================== -->
<div class="announcement-bar">
    <?php echo esc_html(get_theme_mod('nb_announcement_text', '🚚 Free delivery on orders over 150 DT — ')); ?>
    <a href="<?php echo esc_url(wc_get_page_permalink('shop')); ?>">
        <?php esc_html_e('Shop Now', 'noirblancshop'); ?>
    </a>
</div>

<!-- =====================================================
     SITE HEADER
     ===================================================== -->
<header class="site-header" id="site-header">
    <div class="container">
        <div class="header-inner">

            <!-- Logo -->
            <a href="<?php echo esc_url(home_url('/')); ?>" class="site-logo">
                <?php
                // If a custom logo is set, use it. Otherwise show text logo.
                if (has_custom_logo()):
                    the_custom_logo();
                else:
                ?>
                    NOIR<span>&</span>BLANC
                <?php endif; ?>
            </a>

            <!-- Main Navigation -->
            <nav class="main-nav" role="navigation" aria-label="Main navigation">
                <?php
                wp_nav_menu([
                    'theme_location' => 'primary',
                    'menu_class'     => '',
                    'container'      => false,
                    'fallback_cb'    => function() {
                        // Fallback if no menu is set in WP admin
                        echo '<ul>
                            <li><a href="' . home_url('/') . '">Home</a></li>
                            <li><a href="' . wc_get_page_permalink("shop") . '">Shop</a></li>
                            <li><a href="' . home_url('/lookbook') . '">Lookbook</a></li>
                            <li><a href="' . home_url('/livraison') . '">Livraison</a></li>
                            <li><a href="' . home_url('/contact') . '">Contact</a></li>
                        </ul>';
                    }
                ]);
                ?>
            </nav>

            <!-- Header Action Icons -->
            <div class="header-actions">

                <!-- Search -->
                <button class="header-icon-btn" aria-label="Search" onclick="document.getElementById('search-modal').classList.toggle('open')">
                    <?php nb_icon('search'); ?>
                </button>

                <!-- Wishlist (requires WooCommerce Wishlist plugin) -->
                <button class="header-icon-btn" aria-label="Wishlist">
                    <?php nb_icon('heart'); ?>
                </button>

                <!-- Account -->
                <a href="<?php echo esc_url(wc_get_page_permalink('myaccount')); ?>" class="header-icon-btn" aria-label="My Account">
                    <?php nb_icon('user'); ?>
                </a>

                <!-- Cart with item count -->
                <button class="header-icon-btn" aria-label="Open Cart" id="open-cart-btn">
                    <?php nb_icon('cart'); ?>
                    <?php $count = nb_cart_count(); if ($count > 0): ?>
                        <span class="cart-count" id="cart-count-badge"><?php echo $count; ?></span>
                    <?php endif; ?>
                </button>

                <!-- Mobile menu toggle -->
                <button class="menu-toggle" aria-label="Toggle mobile menu" id="mobile-menu-btn">
                    <span></span>
                    <span></span>
                    <span></span>
                </button>

            </div><!-- .header-actions -->
        </div><!-- .header-inner -->
    </div><!-- .container -->
</header><!-- .site-header -->

<!-- =====================================================
     MINI CART SIDEBAR
     ===================================================== -->
<div class="cart-overlay" id="cart-overlay"></div>

<aside class="cart-sidebar" id="cart-sidebar" role="dialog" aria-label="Shopping cart">
    <div class="cart-sidebar__header">
        <h2 class="cart-sidebar__title">Your Cart (<?php echo nb_cart_count(); ?>)</h2>
        <button class="cart-close-btn" id="close-cart-btn" aria-label="Close cart">
            <?php nb_icon('close'); ?>
        </button>
    </div>

    <div class="cart-sidebar__items" id="cart-sidebar-items">
        <?php
        // Load mini-cart template
        if (function_exists('WC')) {
            woocommerce_mini_cart();
        } else {
            // Demo content if WooCommerce not active
            echo '<p style="padding:16px;color:#999;text-align:center">Your cart is empty</p>';
        }
        ?>
    </div>

    <div class="cart-sidebar__footer">
        <?php if (function_exists('WC') && WC()->cart->get_cart_contents_count() > 0):
            $delivery = nb_get_delivery_price(WC()->cart->get_subtotal());
        ?>
            <?php if ($delivery['remaining'] ?? 0): ?>
                <p class="cart-delivery-note">
                    Add <?php echo nb_format_price($delivery['remaining']); ?> more for free delivery!
                </p>
            <?php endif; ?>

            <div class="cart-total-row">
                <span class="cart-total-label">Total</span>
                <span class="cart-total-amount"><?php echo WC()->cart->get_cart_total(); ?></span>
            </div>

            <a href="<?php echo wc_get_checkout_url(); ?>" class="btn btn--primary" style="width:100%; justify-content:center; margin-bottom:8px;">
                Checkout →
            </a>
            <a href="<?php echo wc_get_cart_url(); ?>" class="btn btn--outline" style="width:100%; justify-content:center;">
                View Full Cart
            </a>
        <?php else: ?>
            <p class="cart-delivery-note">Your cart is empty</p>
            <a href="<?php echo wc_get_page_permalink('shop'); ?>" class="btn btn--primary" style="width:100%; justify-content:center;">
                Start Shopping →
            </a>
        <?php endif; ?>
    </div>
</aside>

<!-- =====================================================
     TOAST NOTIFICATION CONTAINER
     (Used by JS to show "Added to cart!" etc.)
     ===================================================== -->
<div class="toast-container" id="toast-container" aria-live="polite"></div>

<!-- =====================================================
     MOBILE NAV OVERLAY
     ===================================================== -->
<div class="mobile-nav-overlay" id="mobile-nav-overlay">
    <div class="mobile-nav-inner">
        <div class="mobile-nav-header">
            <span class="site-logo">NOIR<span>&</span>BLANC</span>
            <button class="cart-close-btn" id="close-mobile-nav">
                <?php nb_icon('close'); ?>
            </button>
        </div>
        <nav>
            <a href="<?php echo home_url('/'); ?>">Home</a>
            <a href="<?php echo wc_get_page_permalink('shop'); ?>">Shop</a>
            <a href="<?php echo home_url('/lookbook'); ?>">Lookbook</a>
            <a href="<?php echo home_url('/livraison'); ?>">Livraison</a>
            <a href="<?php echo home_url('/contact'); ?>">Contact</a>
        </nav>
    </div>
</div>

<!-- Push page content below fixed header -->
<div class="page-wrapper">
