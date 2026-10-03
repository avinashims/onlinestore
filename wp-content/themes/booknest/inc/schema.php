<?php
/**
 * Schema.org markup for products.
 *
 * @package BookNest
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Output Book schema on single product.
 */
function booknest_product_schema() {
	if ( ! is_product() ) {
		return;
	}

	global $product;
	if ( ! $product ) {
		return;
	}

	$author = booknest_get_product_author( $product->get_id() );
	$image  = wp_get_attachment_image_url( $product->get_image_id(), 'full' );

	$schema = array(
		'@context'    => 'https://schema.org',
		'@type'       => 'Book',
		'name'        => $product->get_name(),
		'description' => wp_strip_all_tags( $product->get_short_description() ? $product->get_short_description() : $product->get_description() ),
		'image'       => $image ? $image : '',
		'author'      => array(
			'@type' => 'Person',
			'name'  => $author ? $author : get_bloginfo( 'name' ),
		),
		'offers'      => array(
			'@type'         => 'Offer',
			'price'         => $product->get_price(),
			'priceCurrency' => get_woocommerce_currency(),
			'availability'  => $product->is_in_stock() ? 'https://schema.org/InStock' : 'https://schema.org/OutOfStock',
			'url'           => get_permalink( $product->get_id() ),
		),
	);

	if ( $product->get_average_rating() ) {
		$schema['aggregateRating'] = array(
			'@type'       => 'AggregateRating',
			'ratingValue' => $product->get_average_rating(),
			'reviewCount' => $product->get_review_count(),
		);
	}

	printf(
		'<script type="application/ld+json">%s</script>',
		wp_json_encode( $schema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE )
	);
}
add_action( 'wp_footer', 'booknest_product_schema', 5 );
