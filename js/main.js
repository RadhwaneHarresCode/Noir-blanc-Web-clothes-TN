/**
 * main.js — Noir & Blanc Theme
 * ================================
 * 📚 LEARN: This file handles ALL interactive behavior:
 *  - Header scroll effect
 *  - Cart sidebar open/close
 *  - Add to cart (AJAX - no page reload)
 *  - Order tracker
 *  - Newsletter signup
 *  - Toast notifications
 *  - Mobile menu
 */

'use strict';

// Wait for DOM to be fully loaded
document.addEventListener('DOMContentLoaded', function () {

    // =========================================================
    // 1. HEADER — Scroll effect (adds shadow when scrolled)
    // =========================================================
    const header = document.getElementById('site-header');

    if (header) {
        window.addEventListener('scroll', function () {
            // classList.toggle adds or removes a class based on condition
            header.classList.toggle('scrolled', window.scrollY > 20);
        }, { passive: true }); // passive: true = better scroll performance
    }


    // =========================================================
    // 2. CART SIDEBAR — Open / Close
    // =========================================================
    const cartSidebar  = document.getElementById('cart-sidebar');
    const cartOverlay  = document.getElementById('cart-overlay');
    const openCartBtn  = document.getElementById('open-cart-btn');
    const closeCartBtn = document.getElementById('close-cart-btn');

    function openCart() {
        cartSidebar?.classList.add('open');
        cartOverlay?.classList.add('open');
        document.body.style.overflow = 'hidden'; // prevent background scroll
    }

    function closeCart() {
        cartSidebar?.classList.remove('open');
        cartOverlay?.classList.remove('open');
        document.body.style.overflow = '';
    }

    openCartBtn?.addEventListener('click', openCart);
    closeCartBtn?.addEventListener('click', closeCart);
    cartOverlay?.addEventListener('click', closeCart);

    // Close on Escape key
    document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape') closeCart();
    });


    // =========================================================
    // 3. TOAST NOTIFICATIONS
    // 📚 LEARN: Toasts are small temporary messages that appear
    // and disappear automatically.
    // =========================================================
    const toastContainer = document.getElementById('toast-container');

    /**
     * Show a toast message
     * @param {string} message - Text to display
     * @param {'success'|'error'|'info'} type - Style of toast
     * @param {number} duration - How long to show (ms)
     */
    window.showToast = function (message, type = 'success', duration = 3000) {
        if (!toastContainer) return;

        const toast = document.createElement('div');
        toast.className = `toast ${type}`;
        toast.innerHTML = `
            <span>${type === 'success' ? '✅' : type === 'error' ? '❌' : 'ℹ️'}</span>
            <span>${message}</span>
        `;

        toastContainer.appendChild(toast);

        // Trigger animation (needs small delay so CSS transition fires)
        requestAnimationFrame(() => {
            requestAnimationFrame(() => toast.classList.add('show'));
        });

        // Auto-remove after duration
        setTimeout(() => {
            toast.classList.remove('show');
            setTimeout(() => toast.remove(), 400); // wait for exit animation
        }, duration);
    };


    // =========================================================
    // 4. ADD TO CART — AJAX (no page reload)
    // 📚 LEARN: We use fetch() to send data to PHP without
    // refreshing. The PHP function returns JSON → we update UI.
    // =========================================================
    document.addEventListener('click', function (e) {
        const btn = e.target.closest('.nb-add-to-cart');
        if (!btn || btn.disabled) return;

        const productId   = btn.dataset.productId;
        const productName = btn.dataset.productName || 'Product';

        if (!productId) return;

        // Show loading state
        const originalText = btn.innerHTML;
        btn.innerHTML = '⏳ Adding...';
        btn.disabled  = true;

        // Prepare form data
        const formData = new FormData();
        formData.append('action',     'noirblancshop_add_to_cart');
        formData.append('product_id', productId);
        formData.append('quantity',   1);
        formData.append('nonce',      noirBlancData.nonce); // passed from PHP

        // Send AJAX request to WordPress
        fetch(noirBlancData.ajaxUrl, {
            method: 'POST',
            body:   formData,
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                // Update cart badge count
                updateCartCount(data.data.cart_count);

                // Show success toast
                showToast(`"${productName}" added to cart!`, 'success');

                // Open cart sidebar
                openCart();
            } else {
                showToast(data.data?.message || 'Error adding to cart', 'error');
            }
        })
        .catch(() => {
            showToast('Network error. Please try again.', 'error');
        })
        .finally(() => {
            // Restore button
            btn.innerHTML = originalText;
            btn.disabled  = false;
        });
    });


    // =========================================================
    // 5. UPDATE CART BADGE COUNT
    // =========================================================
    function updateCartCount(count) {
        let badge = document.getElementById('cart-count-badge');

        if (count > 0) {
            if (!badge) {
                badge = document.createElement('span');
                badge.id        = 'cart-count-badge';
                badge.className = 'cart-count';
                document.getElementById('open-cart-btn')?.appendChild(badge);
            }
            badge.textContent = count;
        } else {
            badge?.remove();
        }
    }


    // =========================================================
    // 6. ORDER TRACKER
    // 📚 LEARN: We send the order number to PHP → it queries
    // WooCommerce → returns the delivery step → we animate it.
    // =========================================================
    const trackBtn   = document.getElementById('track-order-btn');
    const trackInput = document.getElementById('order-tracker-input');
    const trackerSteps  = document.getElementById('tracker-steps');
    const trackerResult = document.getElementById('tracker-result');
    const trackerMsg    = document.getElementById('tracker-message');

    trackBtn?.addEventListener('click', function () {
        const orderId = trackInput?.value.trim();
        if (!orderId) {
            showToast('Please enter an order number', 'error');
            return;
        }

        trackBtn.textContent = '🔍 Searching...';
        trackBtn.disabled    = true;

        const formData = new FormData();
        formData.append('action',   'noirblancshop_track_order');
        formData.append('order_id', orderId);
        formData.append('nonce',    noirBlancData.nonce);

        fetch(noirBlancData.ajaxUrl, {
            method: 'POST',
            body:   formData,
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                const { step, status, estimated, customer_name } = data.data;

                // Update step indicators
                const steps = trackerSteps?.querySelectorAll('.tracker-step');
                steps?.forEach((el, i) => {
                    el.classList.remove('done', 'active');
                    if (i < step)      el.classList.add('done');
                    else if (i === step) el.classList.add('active');
                });

                // Update progress bar (the ::after pseudo-element width)
                const pct = (step / 3) * 100;
                trackerSteps.style.setProperty('--progress', pct + '%');

                // Show result message
                if (trackerResult && trackerMsg) {
                    trackerResult.style.display = 'block';
                    const statusLabels = {
                        'pending':    '⏳ Order received — preparing your items.',
                        'processing': '📦 We\'re packing your order right now!',
                        'shipped':    `🚚 Your order is on the way! Estimated: ${estimated}`,
                        'completed':  '✅ Delivered! Hope you love your items.',
                        'cancelled':  '❌ This order was cancelled.',
                    };
                    trackerMsg.textContent = (customer_name ? `Hey ${customer_name}! ` : '') +
                                             (statusLabels[status] || `Status: ${status}`);
                }

            } else {
                showToast(data.data?.message || 'Order not found', 'error');
            }
        })
        .catch(() => showToast('Network error. Try again.', 'error'))
        .finally(() => {
            trackBtn.innerHTML = '🔍 Track';
            trackBtn.disabled  = false;
        });
    });

    // Track on Enter key press
    trackInput?.addEventListener('keydown', e => {
        if (e.key === 'Enter') trackBtn?.click();
    });


    // =========================================================
    // 7. NEWSLETTER FORM
    // =========================================================
    const newsletterForm = document.getElementById('newsletter-form');

    newsletterForm?.addEventListener('submit', function (e) {
        e.preventDefault(); // Prevent page reload

        const emailInput = this.querySelector('input[type="email"]');
        const email = emailInput?.value.trim();

        if (!email) return;

        const btn = this.querySelector('button[type="submit"]');
        const originalText = btn?.textContent;
        if (btn) btn.textContent = '⏳ Sending...';

        const formData = new FormData();
        formData.append('action', 'noirblancshop_newsletter');
        formData.append('email',  email);
        formData.append('nonce',  noirBlancData.nonce);

        fetch(noirBlancData.ajaxUrl, {
            method: 'POST',
            body:   formData,
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                showToast(data.data.message, 'success', 4000);
                if (emailInput) emailInput.value = '';
            } else {
                showToast(data.data?.message || 'Error. Try again.', 'error');
            }
        })
        .catch(() => showToast('Network error', 'error'))
        .finally(() => {
            if (btn && originalText) btn.textContent = originalText;
        });
    });


    // =========================================================
    // 8. CATEGORY FILTER PILLS
    // (Filters products on homepage without page reload)
    // =========================================================
    const categoryPills = document.querySelectorAll('.category-pill');

    categoryPills.forEach(pill => {
        pill.addEventListener('click', function () {
            // Update active pill
            categoryPills.forEach(p => p.classList.remove('active'));
            this.classList.add('active');

            const category = this.dataset.category;

            // Hide/show product cards based on category
            const cards = document.querySelectorAll('#featured-products .product-card');
            cards.forEach(card => {
                const cardCat = card.querySelector('.product-card__category')?.textContent.toLowerCase() ?? '';

                if (category === 'all' || cardCat.includes(category)) {
                    card.style.display = '';
                    card.style.animation = 'fadeInUp 0.4s ease both';
                } else {
                    card.style.display = 'none';
                }
            });
        });
    });


    // =========================================================
    // 9. MOBILE NAVIGATION
    // =========================================================
    const mobileBtn = document.getElementById('mobile-menu-btn');
    const mobileOverlay = document.getElementById('mobile-nav-overlay');
    const closeMobileBtn = document.getElementById('close-mobile-nav');

    mobileBtn?.addEventListener('click', () => {
        mobileOverlay?.classList.toggle('open');
        document.body.style.overflow = mobileOverlay?.classList.contains('open') ? 'hidden' : '';
    });

    closeMobileBtn?.addEventListener('click', () => {
        mobileOverlay?.classList.remove('open');
        document.body.style.overflow = '';
    });


    // =========================================================
    // 10. SIZE SELECTOR (product cards)
    // =========================================================
    document.addEventListener('click', function (e) {
        const dot = e.target.closest('.size-dot');
        if (!dot) return;

        const card = dot.closest('.product-card');
        card?.querySelectorAll('.size-dot').forEach(d => d.classList.remove('selected'));
        dot.classList.add('selected');
    });


    // =========================================================
    // 11. CHECKOUT — DELIVERY OPTION SELECTOR
    // =========================================================
    document.addEventListener('click', function (e) {
        const option = e.target.closest('.delivery-option');
        if (!option) return;

        document.querySelectorAll('.delivery-option').forEach(o => o.classList.remove('selected'));
        option.classList.add('selected');
    });


    // =========================================================
    // 12. SCROLL REVEAL (simple intersection observer)
    // 📚 LEARN: IntersectionObserver fires when elements
    // enter the viewport — no scroll event needed.
    // =========================================================
    const revealElements = document.querySelectorAll('.product-card, .feature-card, .section-header');

    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.style.opacity = '1';
                entry.target.style.transform = 'translateY(0)';
                observer.unobserve(entry.target); // stop observing after reveal
            }
        });
    }, { threshold: 0.1, rootMargin: '0px 0px -40px 0px' });

    revealElements.forEach(el => {
        el.style.opacity = '0';
        el.style.transform = 'translateY(20px)';
        el.style.transition = 'opacity 0.5s ease, transform 0.5s ease';
        observer.observe(el);
    });


    console.log('🖤 Noir & Blanc theme loaded. Happy coding, Radhwane!');
});
