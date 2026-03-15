</div><!-- .page-wrapper -->

<!-- =====================================================
     NEWSLETTER SECTION
     ===================================================== -->
<section class="newsletter-section">
    <div class="container">
        <span class="label" style="color:rgba(255,255,255,0.5); margin-bottom:12px; display:block;">
            Stay in the Loop
        </span>
        <h2 style="font-family:var(--font-display); font-size:clamp(1.8rem,4vw,2.8rem); color:var(--color-white);">
            Get Early Access &amp; Exclusives
        </h2>
        <p style="color:rgba(255,255,255,0.6); max-width:420px; margin:12px auto 0;">
            New arrivals, style guides, and special offers — straight to your inbox.
        </p>
        <form class="newsletter-form" id="newsletter-form">
            <input
                type="email"
                class="newsletter-input"
                placeholder="your@email.com"
                name="email"
                required
                autocomplete="email"
            >
            <button type="submit" class="btn btn--accent">Subscribe</button>
        </form>
        <p style="font-size:0.72rem;color:rgba(255,255,255,0.3);margin-top:12px;">
            No spam. Unsubscribe anytime.
        </p>
    </div>
</section>

<!-- =====================================================
     SITE FOOTER
     ===================================================== -->
<footer class="site-footer">
    <div class="container">
        <div class="footer-grid">

            <!-- Brand Column -->
            <div class="footer-brand">
                <a href="<?php echo home_url('/'); ?>" class="site-logo">
                    NOIR<span>&</span>BLANC
                </a>
                <p class="footer-desc">
                    Curated fashion with fast, reliable delivery across Tunisia and beyond.
                </p>
                <div class="footer-social">
                    <a href="#" class="social-btn" aria-label="Instagram">📸</a>
                    <a href="#" class="social-btn" aria-label="Facebook">📘</a>
                    <a href="#" class="social-btn" aria-label="TikTok">🎵</a>
                    <a href="#" class="social-btn" aria-label="Pinterest">📌</a>
                </div>
            </div>

            <!-- Shop Links -->
            <div>
                <h4 class="footer-col-title">Shop</h4>
                <nav class="footer-links">
                    <a href="<?php echo wc_get_page_permalink('shop'); ?>">All Products</a>
                    <a href="<?php echo home_url('/product-category/nouveautes'); ?>">New Arrivals</a>
                    <a href="<?php echo home_url('/product-category/hommes'); ?>">Men</a>
                    <a href="<?php echo home_url('/product-category/femmes'); ?>">Women</a>
                    <a href="<?php echo home_url('/product-category/soldes'); ?>">Sale</a>
                    <a href="<?php echo home_url('/lookbook'); ?>">Lookbook</a>
                </nav>
            </div>

            <!-- Help Links -->
            <div>
                <h4 class="footer-col-title">Help</h4>
                <nav class="footer-links">
                    <a href="<?php echo home_url('/livraison'); ?>">Livraison & Returns</a>
                    <a href="<?php echo home_url('/track-order'); ?>">Track My Order</a>
                    <a href="<?php echo home_url('/size-guide'); ?>">Size Guide</a>
                    <a href="<?php echo home_url('/faq'); ?>">FAQ</a>
                    <a href="<?php echo home_url('/contact'); ?>">Contact Us</a>
                    <?php if (function_exists('WC')): ?>
                        <a href="<?php echo wc_get_page_permalink('myaccount'); ?>">My Account</a>
                    <?php endif; ?>
                </nav>
            </div>

            <!-- Legal Links -->
            <div>
                <h4 class="footer-col-title">Legal</h4>
                <nav class="footer-links">
                    <a href="<?php echo home_url('/privacy-policy'); ?>">Privacy Policy</a>
                    <a href="<?php echo home_url('/terms'); ?>">Terms & Conditions</a>
                    <a href="<?php echo home_url('/returns-policy'); ?>">Returns Policy</a>
                    <a href="<?php echo home_url('/cookies'); ?>">Cookie Policy</a>
                </nav>
            </div>

        </div><!-- .footer-grid -->

        <!-- Footer Bottom Bar -->
        <div class="footer-bottom">
            <p>
                &copy; <?php echo date('Y'); ?>
                <?php bloginfo('name'); ?> —
                Built with ❤️ by Radhwane
            </p>
            <div class="footer-payments">
                <span class="payment-badge">VISA</span>
                <span class="payment-badge">Mastercard</span>
                <span class="payment-badge">Cash on Delivery</span>
                <span class="payment-badge">D17</span>
            </div>
        </div>

    </div><!-- .container -->
</footer><!-- .site-footer -->

<?php wp_footer(); // WordPress loads JS files and plugins here ?>
</body>
</html>
