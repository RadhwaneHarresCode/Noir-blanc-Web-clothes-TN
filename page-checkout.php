<?php
/**
 * page-checkout.php — Custom Checkout Template
 * ================================================
 * 📚 LEARN: WordPress allows you to create custom page
 * templates that override the default layout.
 *
 * Template Name: Checkout Page
 */

get_header();
?>

<div class="container" style="padding-top: calc(var(--header-height) + var(--space-lg)); padding-bottom: var(--space-xl);">

    <h1 class="animate-fade-up" style="font-family:var(--font-display); font-size:clamp(2rem,4vw,3rem); margin-bottom:var(--space-lg)">
        Checkout
    </h1>

    <?php if (function_exists('WC') && WC()->cart->is_empty()): ?>
        <!-- Empty cart state -->
        <div style="text-align:center; padding:var(--space-xl) 0">
            <div style="font-size:4rem; margin-bottom:var(--space-md)">🛒</div>
            <h2 style="font-family:var(--font-display); margin-bottom:var(--space-md)">Your cart is empty</h2>
            <a href="<?php echo wc_get_page_permalink('shop'); ?>" class="btn btn--primary">
                Shop Now →
            </a>
        </div>

    <?php else: ?>

    <div class="checkout-grid">

        <!-- LEFT: Forms -->
        <div class="checkout-forms">

            <!-- Contact Information -->
            <div class="form-section animate-fade-up">
                <h3 class="form-section-title">📋 Contact Information</h3>
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label" for="billing_first_name">First Name *</label>
                        <input type="text" id="billing_first_name" class="form-input" placeholder="Radhwane" required autocomplete="given-name">
                    </div>
                    <div class="form-group">
                        <label class="form-label" for="billing_last_name">Last Name *</label>
                        <input type="text" id="billing_last_name" class="form-input" placeholder="Herres" required autocomplete="family-name">
                    </div>
                    <div class="form-group form-row--full">
                        <label class="form-label" for="billing_email">Email *</label>
                        <input type="email" id="billing_email" class="form-input" placeholder="email@example.com" required autocomplete="email">
                    </div>
                    <div class="form-group form-row--full">
                        <label class="form-label" for="billing_phone">Phone / WhatsApp *</label>
                        <input type="tel" id="billing_phone" class="form-input" placeholder="+216 XX XXX XXX" required autocomplete="tel">
                    </div>
                </div>
            </div>

            <!-- Delivery Address -->
            <div class="form-section animate-fade-up delay-1">
                <h3 class="form-section-title">📍 Delivery Address</h3>
                <div class="form-row">
                    <div class="form-group form-row--full">
                        <label class="form-label" for="billing_address">Street Address *</label>
                        <input type="text" id="billing_address" class="form-input" placeholder="123 Rue Ibn Khaldoun" required autocomplete="street-address">
                    </div>
                    <div class="form-group">
                        <label class="form-label" for="billing_city">City *</label>
                        <input type="text" id="billing_city" class="form-input" placeholder="Sfax" required autocomplete="address-level2">
                    </div>
                    <div class="form-group">
                        <label class="form-label" for="billing_state">Governorate</label>
                        <select id="billing_state" class="form-select" autocomplete="address-level1">
                            <option value="">Select Governorate</option>
                            <?php
                            $governorates = ['Ariana','Béja','Ben Arous','Bizerte','Gabès','Gafsa','Jendouba',
                                            'Kairouan','Kasserine','Kébili','Le Kef','Mahdia','La Manouba',
                                            'Médenine','Monastir','Nabeul','Sfax','Sidi Bouzid','Siliana',
                                            'Sousse','Tataouine','Tozeur','Tunis','Zaghouan'];
                            foreach ($governorates as $gov):
                            ?>
                                <option value="<?php echo esc_attr($gov); ?>"
                                    <?php selected($gov, 'Sfax'); ?>>
                                    <?php echo esc_html($gov); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group form-row--full">
                        <label class="form-label" for="order_notes">Order Notes (optional)</label>
                        <textarea id="order_notes" class="form-textarea" rows="3" placeholder="Additional instructions for delivery..."></textarea>
                    </div>
                </div>
            </div>

            <!-- Delivery Method -->
            <div class="form-section animate-fade-up delay-2">
                <h3 class="form-section-title">🚚 Delivery Method</h3>
                <div class="delivery-options">

                    <label class="delivery-option selected">
                        <input type="radio" name="delivery_method" value="standard" checked>
                        <span class="delivery-option__icon">🚚</span>
                        <div>
                            <div class="delivery-option__name">Standard Delivery</div>
                            <div class="delivery-option__desc">2–4 business days</div>
                        </div>
                        <span class="delivery-option__price">
                            <?php echo nb_format_price(8.00); ?>
                        </span>
                    </label>

                    <label class="delivery-option">
                        <input type="radio" name="delivery_method" value="express">
                        <span class="delivery-option__icon">⚡</span>
                        <div>
                            <div class="delivery-option__name">Express Delivery</div>
                            <div class="delivery-option__desc">Next business day</div>
                        </div>
                        <span class="delivery-option__price">
                            <?php echo nb_format_price(15.00); ?>
                        </span>
                    </label>

                    <label class="delivery-option">
                        <input type="radio" name="delivery_method" value="pickup">
                        <span class="delivery-option__icon">🏪</span>
                        <div>
                            <div class="delivery-option__name">In-Store Pickup</div>
                            <div class="delivery-option__desc">Pickup from our store</div>
                        </div>
                        <span class="delivery-option__price">Free</span>
                    </label>

                </div>
            </div>

            <!-- Payment Method -->
            <div class="form-section animate-fade-up delay-3">
                <h3 class="form-section-title">💳 Payment Method</h3>
                <div class="delivery-options">

                    <label class="delivery-option selected">
                        <input type="radio" name="payment_method" value="cod" checked>
                        <span class="delivery-option__icon">💵</span>
                        <div>
                            <div class="delivery-option__name">Cash on Delivery</div>
                            <div class="delivery-option__desc">Pay when you receive</div>
                        </div>
                        <span class="delivery-option__price">+0 DT</span>
                    </label>

                    <label class="delivery-option">
                        <input type="radio" name="payment_method" value="d17">
                        <span class="delivery-option__icon">📱</span>
                        <div>
                            <div class="delivery-option__name">D17 / Flouci</div>
                            <div class="delivery-option__desc">Mobile payment</div>
                        </div>
                        <span class="delivery-option__price">Free</span>
                    </label>

                    <label class="delivery-option">
                        <input type="radio" name="payment_method" value="card">
                        <span class="delivery-option__icon">💳</span>
                        <div>
                            <div class="delivery-option__name">Bank Card</div>
                            <div class="delivery-option__desc">Visa, Mastercard</div>
                        </div>
                        <span class="delivery-option__price">Free</span>
                    </label>

                </div>
            </div>

        </div><!-- .checkout-forms -->

        <!-- RIGHT: Order Summary -->
        <div>
            <div class="order-summary animate-slide-right">
                <h3 class="order-summary__title">Order Summary</h3>

                <?php
                // Display WooCommerce cart items
                if (function_exists('WC')):
                    foreach (WC()->cart->get_cart() as $cart_item):
                        $product   = $cart_item['data'];
                        $qty       = $cart_item['quantity'];
                        $line_total = $cart_item['line_total'];
                ?>
                    <div style="display:flex; justify-content:space-between; margin-bottom:12px; padding-bottom:12px; border-bottom:1px solid rgba(255,255,255,0.1);">
                        <div>
                            <div style="font-weight:600; font-size:0.9rem"><?php echo esc_html($product->get_name()); ?></div>
                            <div style="font-size:0.78rem; opacity:0.6">Qty: <?php echo $qty; ?></div>
                        </div>
                        <span style="font-family:var(--font-mono); font-weight:700">
                            <?php echo nb_format_price($line_total); ?>
                        </span>
                    </div>
                <?php
                    endforeach;
                endif;
                ?>

                <!-- Subtotal -->
                <div class="summary-line">
                    <span>Subtotal</span>
                    <span style="font-family:var(--font-mono)">
                        <?php echo function_exists('WC') ? WC()->cart->get_cart_subtotal() : '0.00 DT'; ?>
                    </span>
                </div>

                <!-- Delivery -->
                <div class="summary-line" id="summary-delivery">
                    <span>Delivery</span>
                    <span style="font-family:var(--font-mono)" id="delivery-cost-display">8.00 DT</span>
                </div>

                <!-- Total -->
                <div class="summary-line total">
                    <span>Total</span>
                    <span style="font-family:var(--font-mono)" id="summary-total">
                        <?php echo function_exists('WC') ? WC()->cart->get_cart_total() : '0.00 DT'; ?>
                    </span>
                </div>

                <!-- Place Order Button -->
                <button
                    type="button"
                    class="btn btn--white"
                    style="width:100%; justify-content:center; margin-top:var(--space-md); font-size:1rem;"
                    id="place-order-btn"
                >
                    Place Order →
                </button>

                <p style="font-size:0.72rem; opacity:0.5; text-align:center; margin-top:12px;">
                    🔒 Your data is secure and encrypted
                </p>
            </div>
        </div>

    </div><!-- .checkout-grid -->

    <?php endif; ?>

</div><!-- .container -->

<?php get_footer(); ?>
