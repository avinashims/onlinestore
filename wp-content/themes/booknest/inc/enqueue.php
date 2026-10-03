<?php
/**
 * Enqueue scripts and styles.
 *
 * @package BookNest
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Register and enqueue front-end assets.
 */
function booknest_enqueue_assets() {
	wp_enqueue_style(
		'booknest-fonts',
		'https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Source+Serif+4:opsz,wght@8..60,600;8..60,700&display=swap',
		array(),
		null
	);

	wp_enqueue_style( 'booknest-main', BOOKNEST_URI . '/assets/css/main.css', array( 'booknest-fonts' ), BOOKNEST_VERSION );
	wp_enqueue_style( 'booknest-kobo', BOOKNEST_URI . '/assets/css/kobo-theme.css', array( 'booknest-main' ), BOOKNEST_VERSION );
	wp_enqueue_style( 'booknest-theme', get_stylesheet_uri(), array( 'booknest-kobo' ), BOOKNEST_VERSION );

	if ( is_front_page() ) {
		wp_enqueue_style( 'booknest-landing', BOOKNEST_URI . '/assets/css/landing.css', array( 'booknest-kobo' ), BOOKNEST_VERSION );
	}

	if ( class_exists( 'WooCommerce' ) ) {
		wp_enqueue_style( 'booknest-woocommerce', BOOKNEST_URI . '/assets/css/woocommerce.css', array( 'booknest-main' ), BOOKNEST_VERSION );
		if ( is_woocommerce() || is_cart() || is_checkout() || is_front_page() ) {
			wp_enqueue_script( 'wc-add-to-cart' );
		}
	}

	wp_enqueue_script( 'booknest-main', BOOKNEST_URI . '/assets/js/main.js', array(), BOOKNEST_VERSION, true );

	if ( class_exists( 'WooCommerce' ) ) {
		wp_enqueue_script( 'booknest-shop', BOOKNEST_URI . '/assets/js/shop.js', array( 'booknest-main' ), BOOKNEST_VERSION, true );
		wp_enqueue_script( 'booknest-cart', BOOKNEST_URI . '/assets/js/cart.js', array( 'booknest-main' ), BOOKNEST_VERSION, true );
	}

	wp_localize_script(
		'booknest-main',
		'booknestData',
		array(
			'ajaxUrl'   => admin_url( 'admin-ajax.php' ),
			'nonce'     => wp_create_nonce( 'booknest_nonce' ),
			'homeUrl'   => home_url( '/' ),
			'isShop'    => function_exists( 'is_shop' ) && ( is_shop() || is_product_taxonomy() ),
			'darkMode'  => booknest_is_dark_mode(),
			'i18n'      => array(
				'addedToCart' => esc_html__( 'Added to cart', 'booknest' ),
				'viewCart'    => esc_html__( 'View cart', 'booknest' ),
				'noResults'   => esc_html__( 'No books found.', 'booknest' ),
			),
		)
	);
}
add_action( 'wp_enqueue_scripts', 'booknest_enqueue_assets' );

/**
 * Dequeue block library CSS on front if not needed (performance).
 */
function booknest_dequeue_block_css() {
	if ( ! is_admin() && ! current_user_can( 'edit_posts' ) ) {
		wp_dequeue_style( 'wp-block-library' );
		wp_dequeue_style( 'wp-block-library-theme' );
	}
}
add_action( 'wp_enqueue_scripts', 'booknest_dequeue_block_css', 100 );
