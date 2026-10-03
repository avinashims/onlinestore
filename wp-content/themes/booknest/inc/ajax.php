<?php
/**
 * AJAX handlers.
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
 * Live product search.
 */
function booknest_ajax_live_search() {
	check_ajax_referer( 'booknest_nonce', 'nonce' );

	$term = isset( $_GET['term'] ) ? sanitize_text_field( wp_unslash( $_GET['term'] ) ) : '';
	if ( strlen( $term ) < 2 ) {
		wp_send_json_success( array( 'items' => array() ) );
	}

	$query = new WP_Query(
		array(
			'post_type'      => 'product',
			'post_status'    => 'publish',
			's'              => $term,
			'posts_per_page' => 8,
		)
	);

	$items = array();
	foreach ( $query->posts as $post ) {
		$product = wc_get_product( $post->ID );
		if ( ! $product ) {
			continue;
		}
		$items[] = array(
			'id'    => $post->ID,
			'title' => get_the_title( $post ),
			'url'   => get_permalink( $post ),
			'price' => wp_strip_all_tags( $product->get_price_html() ),
			'image' => get_the_post_thumbnail_url( $post, 'thumbnail' ),
			'author'=> booknest_get_product_author( $post->ID ),
		);
	}

	wp_send_json_success( array( 'items' => $items ) );
}
add_action( 'wp_ajax_booknest_live_search', 'booknest_ajax_live_search' );
add_action( 'wp_ajax_nopriv_booknest_live_search', 'booknest_ajax_live_search' );

/**
 * Shop filter AJAX.
 */
function booknest_ajax_shop_filter() {
	check_ajax_referer( 'booknest_nonce', 'nonce' );

	$args = array(
		'post_type'      => 'product',
		'post_status'    => 'publish',
		'posts_per_page' => booknest_products_per_page(),
		'paged'          => isset( $_POST['page'] ) ? max( 1, absint( $_POST['page'] ) ) : 1,
	);

	$meta_query = array();
	$tax_query  = array();

	if ( ! empty( $_POST['category'] ) ) {
		$tax_query[] = array(
			'taxonomy' => 'product_cat',
			'field'    => 'slug',
			'terms'    => array_map( 'sanitize_title', (array) wp_unslash( $_POST['category'] ) ),
		);
	}

	if ( ! empty( $_POST['min_price'] ) || ! empty( $_POST['max_price'] ) ) {
		$min = isset( $_POST['min_price'] ) ? floatval( $_POST['min_price'] ) : 0;
		$max = isset( $_POST['max_price'] ) ? floatval( $_POST['max_price'] ) : 999999;
		$meta_query[] = array(
			'key'     => '_price',
			'value'   => array( $min, $max ),
			'compare' => 'BETWEEN',
			'type'    => 'NUMERIC',
		);
	}

	if ( ! empty( $_POST['rating'] ) ) {
		$rating = absint( $_POST['rating'] );
		$meta_query[] = array(
			'key'     => '_wc_average_rating',
			'value'   => $rating,
			'compare' => '>=',
			'type'    => 'NUMERIC',
		);
	}

	if ( $tax_query ) {
		$args['tax_query'] = $tax_query;
	}
	if ( $meta_query ) {
		$args['meta_query'] = $meta_query;
	}

	if ( ! empty( $_POST['orderby'] ) ) {
		$orderby = sanitize_text_field( wp_unslash( $_POST['orderby'] ) );
		switch ( $orderby ) {
			case 'price':
				$args['orderby']  = 'meta_value_num';
				$args['meta_key'] = '_price';
				$args['order']    = 'ASC';
				break;
			case 'price-desc':
				$args['orderby']  = 'meta_value_num';
				$args['meta_key'] = '_price';
				$args['order']    = 'DESC';
				break;
			case 'rating':
				$args['orderby']  = 'meta_value_num';
				$args['meta_key'] = '_wc_average_rating';
				$args['order']    = 'DESC';
				break;
			default:
				$args['orderby'] = 'date';
				$args['order']   = 'DESC';
		}
	}

	$query = new WP_Query( $args );

	ob_start();
	if ( $query->have_posts() ) {
		woocommerce_product_loop_start();
		while ( $query->have_posts() ) {
			$query->the_post();
			wc_get_template_part( 'content', 'product' );
		}
		woocommerce_product_loop_end();
	} else {
		echo '<p class="woocommerce-info">' . esc_html__( 'No products found.', 'booknest' ) . '</p>';
	}
	wp_reset_postdata();
	$html = ob_get_clean();

	wp_send_json_success(
		array(
			'html'  => $html,
			'found' => (int) $query->found_posts,
		)
	);
}
add_action( 'wp_ajax_booknest_shop_filter', 'booknest_ajax_shop_filter' );
add_action( 'wp_ajax_nopriv_booknest_shop_filter', 'booknest_ajax_shop_filter' );

/**
 * Quick view product data.
 */
function booknest_ajax_quick_view() {
	check_ajax_referer( 'booknest_nonce', 'nonce' );

	$product_id = isset( $_POST['product_id'] ) ? absint( $_POST['product_id'] ) : 0;
	$product    = wc_get_product( $product_id );
	if ( ! $product ) {
		wp_send_json_error();
	}

	ob_start();
	$post = get_post( $product_id );
	setup_postdata( $post );
	wc_get_template_part( 'content', 'product' );
	wp_reset_postdata();
	$html = ob_get_clean();

	wp_send_json_success( array( 'html' => $html ) );
}
add_action( 'wp_ajax_booknest_quick_view', 'booknest_ajax_quick_view' );
add_action( 'wp_ajax_nopriv_booknest_quick_view', 'booknest_ajax_quick_view' );

/**
 * Refresh mini cart panel.
 */
function booknest_ajax_mini_cart() {
	check_ajax_referer( 'booknest_nonce', 'nonce' );

	wp_send_json_success(
		array(
			'html'  => booknest_get_mini_cart_html(),
			'count' => WC()->cart ? WC()->cart->get_cart_contents_count() : 0,
		)
	);
}
add_action( 'wp_ajax_booknest_mini_cart', 'booknest_ajax_mini_cart' );
add_action( 'wp_ajax_nopriv_booknest_mini_cart', 'booknest_ajax_mini_cart' );
