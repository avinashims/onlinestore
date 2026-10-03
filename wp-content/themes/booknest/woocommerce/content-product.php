<?php
/**
 * Product card — Kobo-style.
 *
 * @package BookNest
 */

defined( 'ABSPATH' ) || exit;

global $product;

if ( empty( $product ) || ! $product->is_visible() ) {
	return;
}

$author = booknest_get_product_author( $product->get_id() );
$img_id = $product->get_image_id();
$alt    = $img_id ? get_post_meta( $img_id, '_wp_attachment_image_alt', true ) : '';
if ( ! $alt ) {
	$alt = $product->get_name();
}

$review_count = $product->get_review_count();
$is_shop      = is_shop() || is_product_taxonomy();
?>
<li <?php wc_product_class( 'product-card kobo-product', $product ); ?>>
	<?php if ( booknest_is_bestseller( $product->get_id() ) ) : ?>
		<span class="badge badge--bestseller"><?php esc_html_e( 'Bestseller', 'booknest' ); ?></span>
	<?php endif; ?>

	<?php if ( $is_shop ) : ?>
		<button type="button" class="wishlist-btn yith-wcwl-add-to-wishlist" aria-label="<?php esc_attr_e( 'Add to wishlist', 'booknest' ); ?>" data-product-id="<?php echo esc_attr( (string) $product->get_id() ); ?>">
			<span aria-hidden="true">♥</span>
		</button>
	<?php endif; ?>

	<a href="<?php echo esc_url( $product->get_permalink() ); ?>" class="product-card__media">
		<?php
		if ( $img_id ) {
			echo wp_get_attachment_image(
				$img_id,
				'booknest-book-cover',
				false,
				array(
					'loading' => 'lazy',
					'alt'     => esc_attr( $alt ),
				)
			);
		} else {
			echo '<img src="' . esc_url( wc_placeholder_img_src( 'booknest-book-cover' ) ) . '" alt="' . esc_attr( $alt ) . '" loading="lazy" width="400" height="533">';
		}
		?>
	</a>

	<div class="product-card__body">
		<h3 class="product-card__title">
			<a href="<?php echo esc_url( $product->get_permalink() ); ?>"><?php echo esc_html( $product->get_name() ); ?></a>
		</h3>
		<?php if ( $author ) : ?>
			<p class="product-card__author"><?php echo esc_html( $author ); ?></p>
		<?php endif; ?>
		<div class="product-card__rating">
			<?php echo wc_get_rating_html( $product->get_average_rating() ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
			<?php if ( $review_count > 0 ) : ?>
				<span class="product-card__review-count">(<?php echo esc_html( (string) $review_count ); ?>)</span>
			<?php endif; ?>
		</div>
		<div class="product-card__price price"><?php echo wp_kses_post( $product->get_price_html() ); ?></div>

		<?php if ( $is_shop ) : ?>
			<div class="product-card__actions">
				<?php woocommerce_template_loop_add_to_cart(); ?>
				<button type="button" class="btn btn--text" data-quick-view data-product-id="<?php echo esc_attr( (string) $product->get_id() ); ?>">
					<?php esc_html_e( 'Preview', 'booknest' ); ?>
				</button>
			</div>
		<?php endif; ?>
	</div>
</li>
