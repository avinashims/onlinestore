<?php
/**
 * Template tags and fallbacks.
 *
 * @package BookNest
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Fallback primary menu.
 */
function booknest_fallback_menu() {
	booknest_kobo_fallback_menu();
}

/**
 * Kobo-style store navigation fallback.
 */
function booknest_kobo_fallback_menu() {
	$shop = function_exists( 'wc_get_page_permalink' ) ? wc_get_page_permalink( 'shop' ) : home_url( '/' );
	echo '<ul id="primary-menu" class="kobo-menu">';
	echo '<li><a href="' . esc_url( $shop ) . '">' . esc_html__( 'eBooks', 'booknest' ) . '</a></li>';
	echo '<li><a href="' . esc_url( $shop ) . '">' . esc_html__( 'Audiobooks', 'booknest' ) . '</a></li>';
	echo '<li><a href="' . esc_url( home_url( '/membership/' ) ) . '">' . esc_html__( 'BookNest Plus', 'booknest' ) . '</a></li>';
	echo '<li><a href="' . esc_url( home_url( '/apps/' ) ) . '">' . esc_html__( 'Apps & Devices', 'booknest' ) . '</a></li>';
	echo '</ul>';
}

/**
 * Fallback footer menu.
 */
function booknest_footer_fallback_menu() {
	$shop = function_exists( 'wc_get_page_permalink' ) ? wc_get_page_permalink( 'shop' ) : home_url( '/' );
	echo '<ul class="footer-menu">';
	echo '<li><a href="' . esc_url( $shop ) . '">' . esc_html__( 'All eBooks', 'booknest' ) . '</a></li>';
	echo '<li><a href="' . esc_url( home_url( '/?s=' ) ) . '">' . esc_html__( 'Search', 'booknest' ) . '</a></li>';
	echo '</ul>';
}

/**
 * Render a horizontal product rail (Kobo-style).
 *
 * @param string $title    Section title.
 * @param array  $query_args WP_Query args.
 * @param string $more_url Optional view-all URL.
 */
function booknest_product_rail( $title, $query_args, $more_url = '' ) {
	if ( ! class_exists( 'WooCommerce' ) ) {
		return;
	}

	$query = new WP_Query( $query_args );
	if ( ! $query->have_posts() && isset( $query_args['meta_key'] ) && BOOKNEST_META_BESTSELLER === $query_args['meta_key'] ) {
		$query = new WP_Query(
			array(
				'post_type'      => 'product',
				'posts_per_page' => $query_args['posts_per_page'] ?? 14,
				'post_status'    => 'publish',
				'meta_key'       => 'total_sales',
				'orderby'        => 'meta_value_num',
				'order'          => 'DESC',
			)
		);
	}
	if ( ! $query->have_posts() ) {
		return;
	}

	$rail_id = 'rail-' . sanitize_title( $title );
	?>
	<section class="kobo-section" aria-labelledby="<?php echo esc_attr( $rail_id ); ?>">
		<div class="container">
			<header class="kobo-section__header">
				<h2 id="<?php echo esc_attr( $rail_id ); ?>" class="kobo-section__title"><?php echo esc_html( $title ); ?></h2>
				<a class="kobo-skip-list" href="#<?php echo esc_attr( $rail_id ); ?>-end"><?php esc_html_e( 'Skip this list', 'booknest' ); ?></a>
			</header>
			<div class="kobo-rail-wrap">
				<ul class="products kobo-rail" role="list">
					<?php
					while ( $query->have_posts() ) :
						$query->the_post();
						wc_get_template_part( 'content', 'product' );
					endwhile;
					wp_reset_postdata();
					?>
				</ul>
			</div>
			<?php if ( $more_url ) : ?>
				<p class="kobo-section__more"><a href="<?php echo esc_url( $more_url ); ?>"><?php esc_html_e( 'View all', 'booknest' ); ?> →</a></p>
			<?php endif; ?>
			<span id="<?php echo esc_attr( $rail_id ); ?>-end" class="screen-reader-text" tabindex="-1"></span>
		</div>
	</section>
	<?php
}
