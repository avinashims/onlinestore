<?php
/**
 * Shop archive override.
 *
 * @package BookNest
 */

defined( 'ABSPATH' ) || exit;

get_header( 'shop' );

do_action( 'woocommerce_before_main_content' );
?>

<div class="container shop-layout">
	<aside class="shop-sidebar" aria-label="<?php esc_attr_e( 'Shop filters', 'booknest' ); ?>">
		<?php if ( is_active_sidebar( 'shop-sidebar' ) ) : ?>
			<?php dynamic_sidebar( 'shop-sidebar' ); ?>
		<?php else : ?>
			<form class="shop-filters card" data-shop-filters style="padding:1.25rem;">
				<h3><?php esc_html_e( 'Categories', 'booknest' ); ?></h3>
				<ul class="filter-list">
					<?php
					$cats = get_terms( array( 'taxonomy' => 'product_cat', 'hide_empty' => true ) );
					if ( ! is_wp_error( $cats ) ) :
						foreach ( $cats as $cat ) :
							?>
							<li>
								<label>
									<input type="checkbox" name="category[]" value="<?php echo esc_attr( $cat->slug ); ?>">
									<?php echo esc_html( $cat->name ); ?>
								</label>
							</li>
							<?php
						endforeach;
					endif;
					?>
				</ul>

				<h3><?php esc_html_e( 'Price', 'booknest' ); ?></h3>
				<label for="max-price"><?php esc_html_e( 'Max price', 'booknest' ); ?>: <span data-price-output>100</span></label>
				<input type="range" id="max-price" name="max_price" min="0" max="100" value="100" data-price-range>
				<input type="hidden" name="min_price" value="0">

				<h3><?php esc_html_e( 'Rating', 'booknest' ); ?></h3>
				<select name="rating">
					<option value=""><?php esc_html_e( 'Any', 'booknest' ); ?></option>
					<option value="4"><?php esc_html_e( '4★ & up', 'booknest' ); ?></option>
					<option value="3"><?php esc_html_e( '3★ & up', 'booknest' ); ?></option>
				</select>
			</form>
		<?php endif; ?>
	</aside>

	<div class="shop-main" data-shop-products>
		<?php if ( woocommerce_product_loop() ) : ?>
			<?php do_action( 'woocommerce_before_shop_loop' ); ?>
			<?php woocommerce_product_loop_start(); ?>
			<?php
			if ( wc_get_loop_prop( 'total' ) ) {
				while ( have_posts() ) {
					the_post();
					do_action( 'woocommerce_shop_loop' );
					wc_get_template_part( 'content', 'product' );
				}
			}
			?>
			<?php woocommerce_product_loop_end(); ?>
			<?php do_action( 'woocommerce_after_shop_loop' ); ?>
		<?php else : ?>
			<?php do_action( 'woocommerce_no_products_found' ); ?>
		<?php endif; ?>
	</div>
</div>

<?php
do_action( 'woocommerce_after_main_content' );
get_footer( 'shop' );
