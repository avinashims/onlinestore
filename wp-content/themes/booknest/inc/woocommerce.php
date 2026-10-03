<?php
/**
 * WooCommerce theme integration.
 *
 * @package BookNest
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'WooCommerce' ) ) {
	return;
}

/**
 * Declare WooCommerce support.
 */
function booknest_woocommerce_setup() {
	add_theme_support( 'woocommerce' );
	add_theme_support( 'wc-product-gallery-zoom' );
	add_theme_support( 'wc-product-gallery-lightbox' );
	add_theme_support( 'wc-product-gallery-slider' );
}
add_action( 'after_setup_theme', 'booknest_woocommerce_setup' );

/**
 * WooCommerce content wrappers.
 */
function booknest_woocommerce_wrapper_start() {
	echo '<main id="primary" class="site-main woocommerce-main" role="main">';
}
function booknest_woocommerce_wrapper_end() {
	echo '</main>';
}
remove_action( 'woocommerce_before_main_content', 'woocommerce_output_content_wrapper', 10 );
remove_action( 'woocommerce_after_main_content', 'woocommerce_output_content_wrapper_end', 10 );
add_action( 'woocommerce_before_main_content', 'booknest_woocommerce_wrapper_start', 10 );
add_action( 'woocommerce_after_main_content', 'booknest_woocommerce_wrapper_end', 10 );

/**
 * Products per page.
 *
 * @return int
 */
function booknest_products_per_page() {
	return 12;
}
add_filter( 'loop_shop_per_page', 'booknest_products_per_page', 20 );

/**
 * Default new products to virtual + downloadable.
 *
 * @param WC_Product $product Product.
 */
function booknest_default_virtual_downloadable( $product ) {
	if ( $product && 'product' === $product->get_type() ) {
		$product->set_virtual( true );
		$product->set_downloadable( true );
	}
}
add_action( 'woocommerce_admin_process_product_object', 'booknest_default_virtual_downloadable' );

/**
 * Fragment for cart count in header.
 *
 * @param array $fragments Fragments.
 * @return array
 */
function booknest_cart_fragments( $fragments ) {
	ob_start();
	booknest_cart_count_markup();
	$fragments['.booknest-cart-count'] = ob_get_clean();
	return $fragments;
}
add_filter( 'woocommerce_add_to_cart_fragments', 'booknest_cart_fragments' );

/**
 * Output cart count badge.
 */
function booknest_cart_count_markup() {
	$count = WC()->cart ? WC()->cart->get_cart_contents_count() : 0;
	printf(
		'<span class="booknest-cart-count" aria-live="polite">%s</span>',
		esc_html( (string) $count )
	);
}

/**
 * Mini cart HTML for slide-in panel.
 */
function booknest_get_mini_cart_html() {
	ob_start();
	woocommerce_mini_cart();
	return ob_get_clean();
}

/**
 * Shop toolbar: grid/list + sort placeholder handled in template.
 */
function booknest_before_shop_loop_toolbar() {
	if ( ! is_shop() && ! is_product_taxonomy() ) {
		return;
	}
	get_template_part( 'template-parts/shop', 'toolbar' );
}
add_action( 'woocommerce_before_shop_loop', 'booknest_before_shop_loop_toolbar', 15 );

/**
 * Remove default sidebar.
 */
remove_action( 'woocommerce_sidebar', 'woocommerce_get_sidebar', 10 );

/**
 * Related products count.
 *
 * @param array $args Args.
 * @return array
 */
function booknest_related_products_args( $args ) {
	$args['posts_per_page'] = 4;
	$args['columns']        = 4;
	return $args;
}
add_filter( 'woocommerce_output_related_products_args', 'booknest_related_products_args' );

/**
 * Thank you page upsell.
 */
function booknest_thankyou_upsell() {
	if ( ! is_order_received_page() ) {
		return;
	}
	echo '<section class="booknest-thankyou-upsell container">';
	echo '<h2>' . esc_html__( 'You might also like', 'booknest' ) . '</h2>';
	woocommerce_output_related_products();
	echo '</section>';
}
add_action( 'woocommerce_thankyou', 'booknest_thankyou_upsell', 20 );

/**
 * Buy Now — redirect to checkout with product.
 */
function booknest_buy_now_redirect() {
	if ( isset( $_GET['booknest_buy_now'] ) && isset( $_GET['add-to-cart'] ) ) {
		$product_id = absint( $_GET['add-to-cart'] );
		if ( $product_id && WC()->cart ) {
			WC()->cart->empty_cart();
			WC()->cart->add_to_cart( $product_id );
			wp_safe_redirect( wc_get_checkout_url() );
			exit;
		}
	}
}
add_action( 'template_redirect', 'booknest_buy_now_redirect' );
