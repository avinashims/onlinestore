<?php
/**
 * Simplified checkout for digital goods.
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
 * Use classic checkout so billing fields and theme template apply.
 *
 * @param string $block_content Block HTML.
 * @param array  $block         Block data.
 * @return string
 */
function booknest_classic_checkout_block( $block_content, $block ) {
	if ( ! is_checkout() ) {
		return $block_content;
	}

	if ( isset( $block['blockName'] ) && 'woocommerce/checkout' === $block['blockName'] ) {
		return do_shortcode( '[woocommerce_checkout]' );
	}

	return $block_content;
}
add_filter( 'render_block', 'booknest_classic_checkout_block', 10, 2 );

/**
 * Whether the cart contains only virtual / downloadable products.
 *
 * @return bool
 */
function booknest_cart_is_digital_only() {
	if ( ! WC()->cart || WC()->cart->is_empty() ) {
		return false;
	}

	foreach ( WC()->cart->get_cart() as $item ) {
		$product = isset( $item['data'] ) ? $item['data'] : null;
		if ( ! $product instanceof WC_Product ) {
			continue;
		}
		if ( $product->needs_shipping() && ! $product->is_downloadable() ) {
			return false;
		}
	}

	return true;
}

/**
 * Enable guest checkout via filter (reinforce theme intent).
 *
 * @param string $value Option value.
 * @return string
 */
function booknest_guest_checkout( $value ) {
	return 'yes';
}
add_filter( 'pre_option_woocommerce_enable_guest_checkout', 'booknest_guest_checkout' );

/**
 * Remove order notes field.
 *
 * @param array $fields Checkout fields.
 * @return array
 */
function booknest_remove_order_notes( $fields ) {
	unset( $fields['order']['order_comments'] );
	unset( $fields['order'] );
	return $fields;
}
add_filter( 'woocommerce_checkout_fields', 'booknest_remove_order_notes' );
add_filter( 'woocommerce_enable_order_notes_field', '__return_false' );

/**
 * Field keys hidden on digital-only checkout (email stays visible).
 *
 * @return string[]
 */
function booknest_digital_checkout_hidden_billing_keys() {
	return array(
		'billing_first_name',
		'billing_last_name',
		'billing_company',
		'billing_country',
		'billing_address_1',
		'billing_address_2',
		'billing_city',
		'billing_state',
		'billing_postcode',
		'billing_phone',
	);
}

/**
 * Mark billing fields optional and hide them for digital carts.
 *
 * @param array $fields Billing fields.
 * @return array
 */
function booknest_minimal_billing_fields( $fields ) {
	if ( ! booknest_cart_is_digital_only() ) {
		return $fields;
	}

	foreach ( booknest_digital_checkout_hidden_billing_keys() as $key ) {
		unset( $fields[ $key ] );
	}

	return $fields;
}
add_filter( 'woocommerce_billing_fields', 'booknest_minimal_billing_fields' );

/**
 * Checkout fields wrapper.
 *
 * @param array $fields Fields.
 * @return array
 */
function booknest_minimal_checkout_fields( $fields ) {
	if ( ! booknest_cart_is_digital_only() ) {
		return $fields;
	}

	foreach ( booknest_digital_checkout_hidden_billing_keys() as $key ) {
		unset( $fields['billing'][ $key ] );
	}

	unset( $fields['shipping'] );

	return $fields;
}
add_filter( 'woocommerce_checkout_fields', 'booknest_minimal_checkout_fields' );

/**
 * Default hidden billing values so validation passes.
 *
 * @param array $data Posted checkout data.
 * @return array
 */
function booknest_checkout_posted_data( $data ) {
	if ( ! booknest_cart_is_digital_only() ) {
		return $data;
	}

	$email = isset( $data['billing_email'] ) ? sanitize_email( $data['billing_email'] ) : '';
	$name  = $email ? sanitize_text_field( strstr( $email, '@', true ) ) : __( 'Reader', 'booknest' );

	if ( empty( $data['billing_first_name'] ) ) {
		$data['billing_first_name'] = $name ? $name : __( 'Reader', 'booknest' );
	}
	if ( empty( $data['billing_last_name'] ) ) {
		$data['billing_last_name'] = '.';
	}
	if ( empty( $data['billing_country'] ) ) {
		$data['billing_country'] = WC()->countries->get_base_country();
	}
	if ( empty( $data['billing_city'] ) ) {
		$data['billing_city'] = '-';
	}
	if ( empty( $data['billing_address_1'] ) ) {
		$data['billing_address_1'] = '-';
	}
	if ( empty( $data['billing_postcode'] ) ) {
		$data['billing_postcode'] = '-';
	}

	return $data;
}
add_filter( 'woocommerce_checkout_posted_data', 'booknest_checkout_posted_data' );

/**
 * Prefill hidden billing fields on the form.
 *
 * @param mixed  $value Field value.
 * @param string $input Field key.
 * @return mixed
 */
function booknest_checkout_default_value( $value, $input ) {
	if ( $value || ! booknest_cart_is_digital_only() ) {
		return $value;
	}

	switch ( $input ) {
		case 'billing_country':
			return WC()->countries->get_base_country();
		case 'billing_first_name':
			return __( 'Reader', 'booknest' );
		case 'billing_last_name':
			return '.';
		case 'billing_city':
		case 'billing_address_1':
		case 'billing_postcode':
			return '-';
	}

	return $value;
}
add_filter( 'woocommerce_checkout_get_value', 'booknest_checkout_default_value', 10, 2 );

/**
 * Hide shipping on checkout when not needed.
 *
 * @param bool $needs_shipping Needs shipping.
 * @return bool
 */
function booknest_cart_needs_shipping( $needs_shipping ) {
	if ( booknest_cart_is_digital_only() ) {
		return false;
	}
	return $needs_shipping;
}
add_filter( 'woocommerce_cart_needs_shipping', 'booknest_cart_needs_shipping' );

/**
 * Softer checkout copy for ebooks.
 *
 * @param string $translated Translated string.
 * @param string $text       Original.
 * @param string $domain     Text domain.
 * @return string
 */
function booknest_checkout_strings( $translated, $text, $domain ) {
	if ( 'woocommerce' !== $domain || ! function_exists( 'is_checkout' ) || ! is_checkout() || is_wc_endpoint_url( 'order-received' ) ) {
		return $translated;
	}

	if ( ! booknest_cart_is_digital_only() ) {
		return $translated;
	}

	if ( 'Billing details' === $text || 'Billing address' === $text ) {
		return __( 'Contact', 'booknest' );
	}

	return $translated;
}
add_filter( 'gettext', 'booknest_checkout_strings', 10, 3 );

/**
 * Skip related-product upsell on thank-you — keep focus on download.
 */
function booknest_remove_thankyou_upsell() {
	remove_action( 'woocommerce_thankyou', 'booknest_thankyou_upsell', 20 );
}
add_action( 'wp', 'booknest_remove_thankyou_upsell' );

/**
 * Enqueue checkout styles and auto-download script on order received.
 */
function booknest_checkout_assets() {
	if ( function_exists( 'is_checkout' ) && is_checkout() ) {
		wp_enqueue_style( 'booknest-checkout', BOOKNEST_URI . '/assets/css/checkout.css', array( 'booknest-woocommerce' ), BOOKNEST_VERSION );
	}

	if ( ! is_order_received_page() ) {
		return;
	}

	global $wp;
	$order_id = isset( $wp->query_vars['order-received'] ) ? absint( $wp->query_vars['order-received'] ) : 0;
	$order    = $order_id ? wc_get_order( $order_id ) : false;

	if ( ! $order || ! $order->has_downloadable_item() ) {
		return;
	}

	$downloads = $order->get_downloadable_items();
	if ( empty( $downloads ) ) {
		return;
	}

	$first = reset( $downloads );
	$url   = isset( $first['download_url'] ) ? $first['download_url'] : '';

	if ( ! $url ) {
		return;
	}

	wp_enqueue_script( 'booknest-checkout', BOOKNEST_URI . '/assets/js/checkout.js', array(), BOOKNEST_VERSION, true );
	wp_localize_script(
		'booknest-checkout',
		'booknestCheckout',
		array(
			'downloadUrl' => esc_url_raw( $url ),
		)
	);
}
add_action( 'wp_enqueue_scripts', 'booknest_checkout_assets', 30 );
