<?php
/**
 * Landing page helpers (1–2 eBooks).
 *
 * @package BookNest
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Products to feature on the landing page (max 2).
 * Uses WooCommerce "Featured" products first, then newest published.
 *
 * @return WC_Product[]
 */
function booknest_get_landing_products() {
	if ( ! class_exists( 'WooCommerce' ) ) {
		return array();
	}

	$products = wc_get_products(
		array(
			'limit'    => 2,
			'featured' => true,
			'status'   => 'publish',
			'orderby'  => 'date',
			'order'    => 'DESC',
		)
	);
	$products = array_values( array_filter( $products, function ( $p ) {
		return $p && $p->is_visible();
	} ) );

	if ( count( $products ) < 2 ) {
		$need = 2 - count( $products );
		$exclude = wp_list_pluck( $products, 'id' );
		$query   = new WP_Query(
			array(
				'post_type'      => 'product',
				'posts_per_page' => $need,
				'post_status'    => 'publish',
				'post__not_in'   => $exclude,
				'orderby'        => 'date',
				'order'          => 'DESC',
			)
		);
		foreach ( $query->posts as $post ) {
			$product = wc_get_product( $post->ID );
			if ( $product && $product->is_visible() ) {
				$products[] = $product;
			}
		}
		wp_reset_postdata();
	}

	return array_slice( $products, 0, 2 );
}

/**
 * Buy-now URL for a product.
 *
 * @param WC_Product $product Product.
 * @return string
 */
function booknest_product_buy_now_url( $product ) {
	return add_query_arg(
		array(
			'add-to-cart'        => $product->get_id(),
			'booknest_buy_now'   => '1',
		),
		$product->get_permalink()
	);
}
