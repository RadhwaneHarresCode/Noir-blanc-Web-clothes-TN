# 🛍️ Noir & Blanc — WordPress Clothes Shop Theme
### A full portfolio project by Radhwane

---

## 📁 Project Structure (Explained)

```
noir-blanc-theme/
│
├── style.css              ← 🎨 ALL styles + design variables (START HERE TO MODIFY)
├── functions.php          ← 🧠 Theme brain: setup, menus, AJAX, backend logic
├── header.php             ← 🔝 Top of every page (nav, cart sidebar)
├── footer.php             ← 🔚 Bottom of every page (footer, newsletter)
├── index.php              ← 📄 Fallback template (required by WordPress)
├── front-page.php         ← 🏠 Homepage (hero, products, tracker)
├── page-checkout.php      ← 🛒 Checkout page template
│
├── template-parts/
│   ├── product-card.php       ← 🃏 Reusable product card (real WooCommerce data)
│   └── product-card-demo.php  ← 🎭 Demo cards (before you add real products)
│
├── js/
│   └── main.js            ← ⚡ All JavaScript: cart, AJAX, tracker, animations
│
└── css/
    └── mobile-nav.css     ← 📱 Mobile navigation styles
```

---

## 🚀 How to Install

### Step 1 — Install WordPress
1. Download WordPress from [wordpress.org](https://wordpress.org)
2. Install it on localhost (using **XAMPP** or **Laragon**) or a hosting provider
3. Create a MySQL database for it

### Step 2 — Install the Theme
1. Zip the `noir-blanc-theme` folder
2. Go to **WordPress Admin → Appearance → Themes → Add New → Upload Theme**
3. Upload the zip and click **Activate**

### Step 3 — Install WooCommerce
1. Go to **Plugins → Add New**
2. Search for "WooCommerce", install and activate it
3. Follow the WooCommerce setup wizard

### Step 4 — Add Products
1. Go to **Products → Add New**
2. Set: Name, Price, Category, Images, Stock
3. For size variants: set as "Variable Product" and add attribute "Taille" (XS, S, M, L, XL)

### Step 5 — Set Homepage
1. Create a new Page called "Home"
2. Go to **Settings → Reading**
3. Set "A static page" and select "Home" as the front page

---

## 🎨 How to Customize Design

### Change Colors
Open `style.css` and find the `:root` block at the top:

```css
:root {
  --color-black:  #0a0a0a;   /* Main dark color */
  --color-white:  #f8f8f6;   /* Background color */
  --color-accent: #c8a96e;   /* Gold accent — try #e63946 for red! */
}
```

### Change Fonts
```css
:root {
  --font-display: 'Cormorant Garamond', serif;  /* Headlines */
  --font-body:    'DM Sans', sans-serif;         /* Body text */
}
```
Replace with any Google Font you like!

### Change Border Radius (sharp vs rounded)
```css
:root {
  --border-radius: 2px;   /* Sharp — change to 12px for rounded */
}
```

### Change Accent Color Live (without code)
Go to: **Appearance → Customize → Colors → Accent Color**

---

## ⚙️ Backend Features

| Feature | Location in code |
|---------|-----------------|
| Add to cart (AJAX) | `functions.php` → `noirblancshop_ajax_add_to_cart()` |
| Order tracker | `functions.php` → `noirblancshop_ajax_track_order()` |
| Newsletter signup | `functions.php` → `noirblancshop_ajax_newsletter()` |
| Admin dashboard | `functions.php` → `noirblancshop_admin_page()` |
| Custom DB table | `functions.php` → `noirblancshop_create_tables()` |
| Customizer options | `functions.php` → `noirblancshop_customize_register()` |

---

## 📚 Key WordPress Concepts Used

### Template Hierarchy
WordPress picks which PHP file to use based on the URL:
- Homepage → `front-page.php`
- Shop page → handled by WooCommerce
- Any page → `page-{slug}.php` or `page.php`
- Fallback → `index.php`

### Hooks (add_action / add_filter)
```php
// "When WordPress does X, also run my function"
add_action('wp_enqueue_scripts', 'my_function');
//          ↑ Hook name           ↑ Your function
```

### AJAX Flow
```
JS (fetch) → admin-ajax.php → PHP function → JSON response → JS updates DOM
```

### Template Parts
```php
// Reuse a component from any template:
get_template_part('template-parts/product-card');
```

---

## 🔌 Recommended Plugins

| Plugin | Purpose |
|--------|---------|
| WooCommerce | Product shop & cart |
| Yoast SEO | Search engine optimization |
| Contact Form 7 | Contact page form |
| YITH WooCommerce Wishlist | Wishlist feature |
| WooCommerce PDF Invoices | Auto-generate invoices |

---

## 📝 Pages to Create in WordPress Admin

1. **Home** — use front-page.php (set as static homepage)
2. **Shop** — created automatically by WooCommerce
3. **Cart** — created automatically by WooCommerce
4. **Checkout** — assign "Checkout Page" template
5. **My Account** — created automatically by WooCommerce
6. **Livraison** — create manually, write delivery info
7. **Contact** — create manually, use Contact Form 7

---

## 🌟 Portfolio Tips

When presenting this project:
- ✅ Show the homepage with product grid
- ✅ Demonstrate add-to-cart (no page reload = AJAX)
- ✅ Show the delivery tracker
- ✅ Open Admin → NB Shop dashboard
- ✅ Show the mobile responsive design (resize browser)
- ✅ Mention: WooCommerce, AJAX, Custom Post Types, Theme Customizer

---

Built with ❤️ — رضوان هرس | v1.0.0
