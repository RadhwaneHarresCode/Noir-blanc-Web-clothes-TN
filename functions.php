<?php
/**
 * Noir & Blanc Theme — functions.php
 * =============================================
 * 📚 LEARN: This file is the "brain" of your WordPress theme.
 * It loads styles, registers features, and handles backend logic.
 *
 * HOW TO MODIFY:
 *  - Add a new menu?      → Edit register_nav_menus()
 *  - Add a new feature?   → Create a new function and hook it with add_action()
 *  - Change image sizes?  → Edit add_image_size() calls
 */

defined('ABSPATH') || exit; // Security: stop direct file access

/* ============================================================
   1. THEME SETUP
   ============================================================ */
function noirblancshop_setup() {
    // Allow WordPress to manage the <title> tag
    add_theme_support('title-tag');

    // Enable featured images on posts/pages
    add_theme_support('post-thumbnails');

    // WooCommerce support (the plugin that powers the shop)
    add_theme_support('woocommerce');
    add_theme_support('wc-product-gallery-zoom');
    add_theme_support('wc-product-gallery-lightbox');
    add_theme_support('wc-product-gallery-slider');

    // HTML5 markup for cleaner output
    add_theme_support('html5', ['search-form', 'comment-form', 'gallery', 'caption', 'script', 'style']);

    // Register navigation menus
    register_nav_menus([
        'primary'   => __('Main Navigation', 'noirblancshop'),
        'footer'    => __('Footer Menu', 'noirblancshop'),
        'mobile'    => __('Mobile Menu', 'noirblancshop'),
    ]);

    // Custom image sizes for products
    add_image_size('product-card',   480, 600, true);   // Product grid cards
    add_image_size('product-hero',   900, 1100, true);  // Product detail page
    add_image_size('hero-banner',    1400, 800, true);  // Homepage hero
    add_image_size('category-thumb', 600, 400, true);   // Category thumbnails
}
add_action('after_setup_theme', 'noirblancshop_setup');

/* ============================================================
   2. LOAD STYLES & SCRIPTS
   ============================================================ */
function noirblancshop_scripts() {

    // --- FONTS (Google Fonts) ---
    wp_enqueue_style(
        'noirblancshop-fonts',
        'https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,400;0,600;0,700;1,400;1,600&family=DM+Sans:wght@400;500;600;700&family=JetBrains+Mono:wght@400;700&display=swap',
        [],
        null
    );

    // --- MAIN STYLESHEET (style.css in theme root) ---
    wp_enqueue_style(
        'noirblancshop-main',
        get_stylesheet_uri(),
        ['noirblancshop-fonts'],
        '1.0.0'
    );

    // --- MAIN JS ---
    wp_enqueue_script(
        'noirblancshop-main',
        get_template_directory_uri() . '/js/main.js',
        ['jquery'],   // depends on jQuery (WordPress includes it)
        '1.0.0',
        true          // true = load in footer (better performance)
    );

    // Pass PHP variables to JavaScript (AJAX URL + nonce for security)
    wp_localize_script('noirblancshop-main', 'noirBlancData', [
        'ajaxUrl'   => admin_url('admin-ajax.php'),
        'nonce'     => wp_create_nonce('noirblancshop_nonce'),
        'currency'  => get_option('woocommerce_currency_symbol', 'DT'),
        'siteUrl'   => get_site_url(),
    ]);
}
add_action('wp_enqueue_scripts', 'noirblancshop_scripts');

/* ============================================================
   3. CUSTOM WIDGET AREAS (Sidebars)
   ============================================================ */
function noirblancshop_widgets_init() {
    register_sidebar([
        'name'          => __('Shop Sidebar', 'noirblancshop'),
        'id'            => 'shop-sidebar',
        'description'   => __('Widgets for the shop page (filters, categories)', 'noirblancshop'),
        'before_widget' => '<div class="widget %2$s">',
        'after_widget'  => '</div>',
        'before_title'  => '<h4 class="widget-title">',
        'after_title'   => '</h4>',
    ]);

    register_sidebar([
        'name'          => __('Footer Column 3', 'noirblancshop'),
        'id'            => 'footer-col-3',
        'description'   => __('Footer widget area', 'noirblancshop'),
        'before_widget' => '<div class="footer-widget %2$s">',
        'after_widget'  => '</div>',
        'before_title'  => '<h4 class="footer-col-title">',
        'after_title'   => '</h4>',
    ]);
}
add_action('widgets_init', 'noirblancshop_widgets_init');

/* ============================================================
   4. CUSTOM POST TYPE: LOOKBOOK
   📚 LEARN: Custom Post Types let you add new content types
   beyond "Posts" and "Pages". Here we add "Lookbook" for
   editorial/campaign content.
   ============================================================ */
function noirblancshop_register_cpts() {
    register_post_type('lookbook', [
        'labels' => [
            'name'          => __('Lookbook', 'noirblancshop'),
            'singular_name' => __('Look', 'noirblancshop'),
            'add_new_item'  => __('Add New Look', 'noirblancshop'),
            'edit_item'     => __('Edit Look', 'noirblancshop'),
        ],
        'public'        => true,
        'has_archive'   => true,
        'supports'      => ['title', 'editor', 'thumbnail', 'excerpt'],
        'menu_icon'     => 'dashicons-camera',
        'show_in_rest'  => true,  // enables Gutenberg editor
        'rewrite'       => ['slug' => 'lookbook'],
    ]);
}
add_action('init', 'noirblancshop_register_cpts');

/* ============================================================
   5. CUSTOM TAXONOMY: STYLE / SAISON
   📚 LEARN: Taxonomies are like "categories" or "tags" but
   for any post type. We add "Style" for lookbooks.
   ============================================================ */
function noirblancshop_register_taxonomies() {
    register_taxonomy('style', ['lookbook', 'product'], [
        'labels'        => ['name' => 'Style', 'singular_name' => 'Style'],
        'hierarchical'  => true,  // true = like categories, false = like tags
        'public'        => true,
        'show_in_rest'  => true,
        'rewrite'       => ['slug' => 'style'],
    ]);

    register_taxonomy('saison', ['product'], [
        'labels'        => ['name' => 'Saison', 'singular_name' => 'Saison'],
        'hierarchical'  => false,
        'public'        => true,
        'show_in_rest'  => true,
        'rewrite'       => ['slug' => 'saison'],
    ]);
}
add_action('init', 'noirblancshop_register_taxonomies');

/* ============================================================
   6. AJAX — ADD TO CART
   📚 LEARN: AJAX lets the page update WITHOUT reloading.
   The JS sends a request → PHP processes it → JS gets response.
   ============================================================ */
function noirblancshop_ajax_add_to_cart() {
    // Verify the request is legitimate (security)
    if (!wp_verify_nonce($_POST['nonce'] ?? '', 'noirblancshop_nonce')) {
        wp_send_json_error(['message' => 'Security check failed']);
        return;
    }

    $product_id = absint($_POST['product_id'] ?? 0);
    $quantity   = absint($_POST['quantity']   ?? 1);
    $variation  = sanitize_text_field($_POST['variation'] ?? '');

    if (!$product_id) {
        wp_send_json_error(['message' => 'Invalid product']);
        return;
    }

    // WooCommerce add to cart
    $added = WC()->cart->add_to_cart($product_id, $quantity);

    if ($added) {
        wp_send_json_success([
            'message'    => __('Added to cart!', 'noirblancshop'),
            'cart_count' => WC()->cart->get_cart_contents_count(),
            'cart_total' => WC()->cart->get_cart_total(),
        ]);
    } else {
        wp_send_json_error(['message' => __('Could not add to cart', 'noirblancshop')]);
    }
}
// 'wp_ajax_' = logged-in users | 'wp_ajax_nopriv_' = guests
add_action('wp_ajax_noirblancshop_add_to_cart',        'noirblancshop_ajax_add_to_cart');
add_action('wp_ajax_nopriv_noirblancshop_add_to_cart', 'noirblancshop_ajax_add_to_cart');

/* ============================================================
   7. AJAX — NEWSLETTER SIGNUP
   ============================================================ */
function noirblancshop_ajax_newsletter() {
    if (!wp_verify_nonce($_POST['nonce'] ?? '', 'noirblancshop_nonce')) {
        wp_send_json_error(['message' => 'Security check failed']);
        return;
    }

    $email = sanitize_email($_POST['email'] ?? '');

    if (!is_email($email)) {
        wp_send_json_error(['message' => __('Invalid email address', 'noirblancshop')]);
        return;
    }

    // Save subscriber to custom DB table (or integrate MailChimp here)
    global $wpdb;
    $table = $wpdb->prefix . 'nb_subscribers';

    // Check if already subscribed
    $exists = $wpdb->get_var($wpdb->prepare("SELECT id FROM $table WHERE email = %s", $email));

    if ($exists) {
        wp_send_json_error(['message' => __('Already subscribed!', 'noirblancshop')]);
        return;
    }

    $wpdb->insert($table, [
        'email'      => $email,
        'created_at' => current_time('mysql'),
        'ip_address' => sanitize_text_field($_SERVER['REMOTE_ADDR'] ?? ''),
    ]);

    wp_send_json_success(['message' => __('Welcome! You\'re subscribed 🎉', 'noirblancshop')]);
}
add_action('wp_ajax_noirblancshop_newsletter',        'noirblancshop_ajax_newsletter');
add_action('wp_ajax_nopriv_noirblancshop_newsletter', 'noirblancshop_ajax_newsletter');

/* ============================================================
   8. AJAX — DELIVERY TRACKER (simulated)
   ============================================================ */
function noirblancshop_ajax_track_order() {
    if (!wp_verify_nonce($_POST['nonce'] ?? '', 'noirblancshop_nonce')) {
        wp_send_json_error(['message' => 'Security check failed']);
        return;
    }

    $order_id = absint($_POST['order_id'] ?? 0);

    if (!$order_id) {
        wp_send_json_error(['message' => __('Invalid order number', 'noirblancshop')]);
        return;
    }

    // Get the WooCommerce order
    $order = wc_get_order($order_id);

    if (!$order) {
        wp_send_json_error(['message' => __('Order not found', 'noirblancshop')]);
        return;
    }

    // Map WooCommerce status to delivery steps
    $status_map = [
        'pending'    => 0,
        'processing' => 1,
        'on-hold'    => 1,
        'shipped'    => 2,
        'completed'  => 3,
        'cancelled'  => -1,
    ];

    $wc_status    = $order->get_status();
    $current_step = $status_map[$wc_status] ?? 0;

    // Get order meta for custom tracking number
    $tracking_number = $order->get_meta('_tracking_number') ?: 'NB-' . str_pad($order_id, 6, '0', STR_PAD_LEFT);

    wp_send_json_success([
        'order_id'        => $order_id,
        'status'          => $wc_status,
        'step'            => $current_step,
        'tracking_number' => $tracking_number,
        'customer_name'   => $order->get_billing_first_name(),
        'estimated'       => __('3–5 business days', 'noirblancshop'),
        'items_count'     => $order->get_item_count(),
    ]);
}
add_action('wp_ajax_noirblancshop_track_order',        'noirblancshop_ajax_track_order');
add_action('wp_ajax_nopriv_noirblancshop_track_order', 'noirblancshop_ajax_track_order');

/* ============================================================
   9. DATABASE SETUP (runs on theme activation)
   📚 LEARN: We create a custom table for newsletter subscribers.
   dbDelta() is WordPress's safe way to CREATE/ALTER tables.
   ============================================================ */
function noirblancshop_create_tables() {
    global $wpdb;
    $charset = $wpdb->get_charset_collate();

    // Subscribers table
    $sql = "CREATE TABLE IF NOT EXISTS {$wpdb->prefix}nb_subscribers (
        id          INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        email       VARCHAR(255) NOT NULL UNIQUE,
        created_at  DATETIME    NOT NULL,
        ip_address  VARCHAR(45),
        status      TINYINT     NOT NULL DEFAULT 1
    ) $charset;";

    require_once ABSPATH . 'wp-admin/includes/upgrade.php';
    dbDelta($sql);
}
add_action('after_switch_theme', 'noirblancshop_create_tables');

/* ============================================================
   10. HELPER FUNCTIONS
   📚 LEARN: Reusable functions you can call from template files
   ============================================================ */

/**
 * Get cart item count safely (WooCommerce may not always be active)
 */
function nb_cart_count(): int {
    return function_exists('WC') && WC()->cart ? WC()->cart->get_cart_contents_count() : 0;
}

/**
 * Format price with theme currency
 */
function nb_format_price(float $price): string {
    $symbol = get_option('woocommerce_currency_symbol', 'DT');
    return number_format($price, 2) . ' ' . $symbol;
}

/**
 * Get delivery price based on cart total
 * 📚 LEARN: Free delivery above a threshold is a common ecommerce pattern
 */
function nb_get_delivery_price(float $cart_total = 0): array {
    $free_threshold = (float) get_option('nb_free_delivery_threshold', 150);
    $standard_price = (float) get_option('nb_delivery_price', 8.00);

    if ($cart_total >= $free_threshold) {
        return ['price' => 0, 'label' => __('Free Delivery 🎉', 'noirblancshop')];
    }

    return [
        'price' => $standard_price,
        'label' => sprintf(__('Standard Delivery (%.2f DT)', 'noirblancshop'), $standard_price),
        'remaining' => $free_threshold - $cart_total,
    ];
}

/**
 * Output SVG icons inline
 * Usage: nb_icon('cart') or nb_icon('heart')
 */
function nb_icon(string $name, string $class = ''): void {
    $icons = [
        'cart'    => '<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M6 2L3 6v14a2 2 0 002 2h14a2 2 0 002-2V6l-3-4z"/><line x1="3" y1="6" x2="21" y2="6"/><path d="M16 10a4 4 0 01-8 0"/></svg>',
        'heart'   => '<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20.84 4.61a5.5 5.5 0 00-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 00-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 000-7.78z"/></svg>',
        'search'  => '<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>',
        'user'    => '<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 21v-2a4 4 0 00-4-4H8a4 4 0 00-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>',
        'truck'   => '<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="1" y="3" width="15" height="13"/><polygon points="16 8 20 8 23 11 23 16 16 16 16 8"/><circle cx="5.5" cy="18.5" r="2.5"/><circle cx="18.5" cy="18.5" r="2.5"/></svg>',
        'close'   => '<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>',
        'plus'    => '<svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>',
        'minus'   => '<svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="5" y1="12" x2="19" y2="12"/></svg>',
        'star'    => '<svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor" stroke="currentColor" stroke-width="2"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>',
        'check'   => '<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg>',
        'menu'    => '<svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="3" y1="12" x2="21" y2="12"/><line x1="3" y1="6" x2="21" y2="6"/><line x1="3" y1="18" x2="21" y2="18"/></svg>',
    ];

    $svg = $icons[$name] ?? '';
    if ($svg && $class) {
        $svg = str_replace('<svg ', "<svg class=\"$class\" ", $svg);
    }
    echo $svg;
}

/* ============================================================
   11. THEME CUSTOMIZER OPTIONS
   📚 LEARN: The Customizer (Appearance → Customize) lets the
   client change settings without touching code.
   ============================================================ */
function noirblancshop_customize_register($wp_customize) {

    // --- SECTION: Shop Settings ---
    $wp_customize->add_section('nb_shop_settings', [
        'title'    => __('Shop Settings', 'noirblancshop'),
        'priority' => 30,
    ]);

    // Announcement bar text
    $wp_customize->add_setting('nb_announcement_text', [
        'default'           => '🚚 Free delivery on orders over 150 DT — ',
        'sanitize_callback' => 'sanitize_text_field',
    ]);
    $wp_customize->add_control('nb_announcement_text', [
        'label'   => __('Announcement Bar Text', 'noirblancshop'),
        'section' => 'nb_shop_settings',
        'type'    => 'text',
    ]);

    // Hero heading
    $wp_customize->add_setting('nb_hero_heading', [
        'default'           => 'Dress With Purpose',
        'sanitize_callback' => 'sanitize_text_field',
    ]);
    $wp_customize->add_control('nb_hero_heading', [
        'label'   => __('Hero Heading', 'noirblancshop'),
        'section' => 'nb_shop_settings',
        'type'    => 'text',
    ]);

    // Free delivery threshold
    $wp_customize->add_setting('nb_free_delivery_threshold', [
        'default'           => '150',
        'sanitize_callback' => 'absint',
    ]);
    $wp_customize->add_control('nb_free_delivery_threshold', [
        'label'       => __('Free Delivery Minimum (DT)', 'noirblancshop'),
        'description' => __('Orders above this amount get free delivery', 'noirblancshop'),
        'section'     => 'nb_shop_settings',
        'type'        => 'number',
    ]);

    // Accent color
    $wp_customize->add_setting('nb_accent_color', [
        'default'           => '#c8a96e',
        'sanitize_callback' => 'sanitize_hex_color',
    ]);
    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'nb_accent_color', [
        'label'   => __('Accent Color', 'noirblancshop'),
        'section' => 'colors',
    ]));
}
add_action('customize_register', 'noirblancshop_customize_register');

// Apply Customizer CSS as inline style
function noirblancshop_customizer_css() {
    $accent = get_theme_mod('nb_accent_color', '#c8a96e');
    ?>
    <style>
        :root {
            --color-accent: <?php echo esc_attr($accent); ?>;
        }
    </style>
    <?php
}
add_action('wp_head', 'noirblancshop_customizer_css');

/* ============================================================
   12. ADMIN — ORDERS MANAGEMENT PAGE
   📚 LEARN: You can add custom pages in WordPress admin
   using add_menu_page() or add_submenu_page()
   ============================================================ */
function noirblancshop_admin_menu() {
    add_menu_page(
        'Noir & Blanc Shop',          // Page title
        'NB Shop',                     // Menu label
        'manage_options',              // Capability required
        'nb-shop-dashboard',           // Menu slug
        'noirblancshop_admin_page',    // Callback function
        'dashicons-store',             // Icon
        56                             // Position
    );

    add_submenu_page(
        'nb-shop-dashboard',
        'Subscribers',
        'Newsletter',
        'manage_options',
        'nb-subscribers',
        'noirblancshop_subscribers_page'
    );
}
add_action('admin_menu', 'noirblancshop_admin_menu');

/**
 * Admin Dashboard Page
 */
function noirblancshop_admin_page() {
    // Quick stats from WooCommerce
    $total_orders   = wp_count_posts('shop_order');
    $pending_orders = $total_orders->{'wc-pending'}   ?? 0;
    $processing     = $total_orders->{'wc-processing'} ?? 0;
    $completed      = $total_orders->{'wc-completed'}  ?? 0;
    ?>
    <div class="wrap">
        <h1 style="font-family: Georgia, serif; font-size: 2rem; margin-bottom: 24px;">
            🛍️ Noir & Blanc — Dashboard
        </h1>

        <div style="display: grid; grid-template-columns: repeat(4, 1fr); gap: 16px; margin-bottom: 32px;">
            <?php
            $stats = [
                ['Pending Orders',    $pending_orders, '#fef3c7', '#92400e'],
                ['Processing',        $processing,     '#dbeafe', '#1e40af'],
                ['Completed',         $completed,      '#d1fae5', '#065f46'],
                ['Total Products',    wp_count_posts('product')->publish ?? 0, '#f3e8ff', '#6b21a8'],
            ];
            foreach ($stats as [$label, $count, $bg, $color]): ?>
                <div style="background:<?php echo $bg; ?>; border-radius:8px; padding:20px; border-left:4px solid <?php echo $color; ?>">
                    <div style="font-size:0.75rem; text-transform:uppercase; letter-spacing:0.1em; color:<?php echo $color; ?>; font-weight:700; margin-bottom:8px;">
                        <?php echo esc_html($label); ?>
                    </div>
                    <div style="font-size:2.5rem; font-weight:700; color:<?php echo $color; ?>">
                        <?php echo esc_html($count); ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

        <div style="background:#fff; border-radius:8px; padding:24px; box-shadow:0 2px 8px rgba(0,0,0,0.06)">
            <h2 style="margin-bottom:16px;">Recent Orders</h2>
            <?php
            $recent_orders = wc_get_orders(['limit' => 10, 'orderby' => 'date', 'order' => 'DESC']);
            if ($recent_orders): ?>
                <table class="wp-list-table widefat fixed striped">
                    <thead>
                        <tr>
                            <th>Order #</th>
                            <th>Customer</th>
                            <th>Status</th>
                            <th>Total</th>
                            <th>Date</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($recent_orders as $order): ?>
                            <tr>
                                <td>#<?php echo $order->get_id(); ?></td>
                                <td><?php echo esc_html($order->get_billing_first_name() . ' ' . $order->get_billing_last_name()); ?></td>
                                <td><span class="order-status status-<?php echo $order->get_status(); ?>"><?php echo wc_get_order_status_name($order->get_status()); ?></span></td>
                                <td><?php echo $order->get_formatted_order_total(); ?></td>
                                <td><?php echo $order->get_date_created()->date('d/m/Y'); ?></td>
                                <td><a href="<?php echo get_edit_post_link($order->get_id()); ?>" class="button button-small">View</a></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <p>No orders yet. Share your shop! 🚀</p>
            <?php endif; ?>
        </div>
    </div>
    <?php
}

/**
 * Newsletter Subscribers Page
 */
function noirblancshop_subscribers_page() {
    global $wpdb;
    $table = $wpdb->prefix . 'nb_subscribers';
    $subscribers = $wpdb->get_results("SELECT * FROM $table ORDER BY created_at DESC LIMIT 100");
    ?>
    <div class="wrap">
        <h1>📧 Newsletter Subscribers</h1>
        <p><?php echo count($subscribers); ?> subscriber(s)</p>

        <table class="wp-list-table widefat fixed striped" style="margin-top:16px">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Email</th>
                    <th>Date Subscribed</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($subscribers): ?>
                    <?php foreach ($subscribers as $i => $sub): ?>
                        <tr>
                            <td><?php echo $i + 1; ?></td>
                            <td><?php echo esc_html($sub->email); ?></td>
                            <td><?php echo esc_html($sub->created_at); ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr><td colspan="3">No subscribers yet.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
    <?php
}
