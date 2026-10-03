# BookNest WordPress Theme

Premium WooCommerce theme for digital eBook stores — **Kobo-inspired** storefront (red brand, editorial hero, horizontal book rails), instant downloads, and a reader-first shopping experience.

## Requirements

- WordPress 6.0+
- PHP 7.4+
- [WooCommerce](https://wordpress.org/plugins/woocommerce/) 8.x+

## Installation

1. Copy the `booknest` folder to `wp-content/themes/`.
2. In **Appearance → Themes**, activate **BookNest**.
3. Install and activate **WooCommerce** and complete the setup wizard.
4. Go to **Settings → Reading** and set **Your homepage displays** to **A static page** (any page — the theme uses `front-page.php` for a **1–2 eBook landing** automatically).
5. **Landing page books:** In **Products**, mark up to **2 products as Featured** (star icon). They appear on the homepage hero and showcase. Without featured products, the 2 newest products are used.
6. Optional: assign menus under **Appearance → Menus** (shop pages only; the homepage uses a minimal landing header).

## WooCommerce setup (digital products)

1. **WooCommerce → Settings → Products → Downloadable products** — enable downloads.
2. **WooCommerce → Settings → Accounts & Privacy** — enable guest checkout.
3. **WooCommerce → Settings → Shipping** — disable shipping zones if you only sell downloads (theme hides shipping for virtual carts).
4. Create products as **Simple products**, check **Virtual** and **Downloadable**, add a downloadable file, and fill **eBook Details** in the product editor (Author, Format, Sample PDF, etc.).

## Demo products

1. Go to **WooCommerce → Products → Import**.
2. Upload `demo-products.csv` from this theme folder.
3. Map columns and run the import.
4. Assign product images manually or via your media library (CSV includes placeholder image URLs you can replace).

## Recommended plugins

| Plugin | Purpose |
|--------|---------|
| WooCommerce | Store engine (required) |
| WooCommerce Stripe Gateway or Razorpay for WooCommerce | Payments |
| Rank Math SEO | SEO |
| WP Rocket | Performance / caching |
| YITH WooCommerce Wishlist | Wishlist (heart icons on cards) |

## Theme features

- Sticky header with live AJAX search and slide-in mini cart
- Homepage: hero, categories, new arrivals carousel, bestsellers, offer countdown, features, testimonials, newsletter
- Shop sidebar filters with AJAX (category, price, rating)
- Custom product meta: Author, Pages, Format, Language, File size, Sample PDF, TOC, Bestseller flag
- Simplified checkout (minimal billing, no order notes)
- **My Library** on account dashboard and downloads page
- Dark mode toggle (cookie-based)
- Schema.org `Book` markup on product pages

## Development

Assets live in `assets/css`, `assets/js`, and `assets/images`. PHP modules are in `inc/`. WooCommerce template overrides are in `woocommerce/`.

## License

GPL v2 or later (same as WordPress).
