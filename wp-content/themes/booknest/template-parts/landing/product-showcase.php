<?php
/**
 * Landing product showcase block.
 *
 * @package BookNest
 *
 * @var WC_Product $product   Product.
 * @var bool       $is_primary Hero-style block.
 */

if ( ! isset( $product ) || ! $product instanceof WC_Product ) {
	return;
}

$is_primary = ! empty( $is_primary );
$author     = booknest_get_product_author( $product->get_id() );
$format     = get_post_meta( $product->get_id(), BOOKNEST_META_FORMAT, true );
$pages      = get_post_meta( $product->get_id(), BOOKNEST_META_PAGES, true );
$file_size  = get_post_meta( $product->get_id(), BOOKNEST_META_FILE_SIZE, true );
$formats    = $format ? array_map( 'trim', explode( ',', $format ) ) : array( 'PDF', 'EPUB' );
$img_id     = $product->get_image_id();
$alt        = $product->get_name();
$section_id = 'book-' . $product->get_id();
?>
<article id="<?php echo esc_attr( $section_id ); ?>" class="landing-book <?php echo $is_primary ? 'landing-book--hero' : 'landing-book--secondary'; ?>">
	<div class="landing-book__grid">
		<div class="landing-book__cover-wrap">
			<div class="landing-book__cover">
				<?php
				if ( $img_id ) {
					echo wp_get_attachment_image( $img_id, 'booknest-book-cover-large', false, array( 'loading' => $is_primary ? 'eager' : 'lazy', 'alt' => esc_attr( $alt ) ) );
				} else {
					echo '<img src="' . esc_url( wc_placeholder_img_src( 'booknest-book-cover-large' ) ) . '" alt="' . esc_attr( $alt ) . '" width="400" height="533">';
				}
				?>
			</div>
			<?php if ( $is_primary ) : ?>
				<span class="landing-book__glow" aria-hidden="true"></span>
			<?php endif; ?>
		</div>

		<div class="landing-book__content">
			<?php if ( $is_primary ) : ?>
				<p class="landing-eyebrow"><?php esc_html_e( 'Now available — instant download', 'booknest' ); ?></p>
			<?php endif; ?>

			<h2 class="landing-book__title"><?php echo esc_html( $product->get_name() ); ?></h2>

			<?php if ( $author ) : ?>
				<p class="landing-book__author"><?php printf( esc_html__( 'By %s', 'booknest' ), esc_html( $author ) ); ?></p>
			<?php endif; ?>

			<div class="landing-book__rating">
				<?php echo wc_get_rating_html( $product->get_average_rating() ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
				<?php if ( $product->get_review_count() > 0 ) : ?>
					<span class="landing-book__reviews">(<?php echo esc_html( (string) $product->get_review_count() ); ?> <?php esc_html_e( 'reviews', 'booknest' ); ?>)</span>
				<?php endif; ?>
			</div>

			<div class="landing-book__price price"><?php echo wp_kses_post( $product->get_price_html() ); ?></div>

			<?php if ( $product->get_short_description() ) : ?>
				<div class="landing-book__excerpt"><?php echo wp_kses_post( wpautop( $product->get_short_description() ) ); ?></div>
			<?php endif; ?>

			<ul class="landing-book__meta">
				<?php foreach ( $formats as $f ) : ?>
					<li><?php echo esc_html( strtoupper( $f ) ); ?></li>
				<?php endforeach; ?>
				<?php if ( $pages ) : ?>
					<li><?php printf( esc_html__( '%s pages', 'booknest' ), esc_html( $pages ) ); ?></li>
				<?php endif; ?>
				<?php if ( $file_size ) : ?>
					<li><?php echo esc_html( $file_size ); ?></li>
				<?php endif; ?>
			</ul>

			<div class="landing-book__cta">
				<a class="btn btn--landing-primary" href="<?php echo esc_url( booknest_product_buy_now_url( $product ) ); ?>">
					<?php esc_html_e( 'Buy now & download', 'booknest' ); ?>
				</a>
				<a class="btn btn--landing-secondary add_to_cart_button ajax_add_to_cart" href="<?php echo esc_url( $product->add_to_cart_url() ); ?>" data-product_id="<?php echo esc_attr( (string) $product->get_id() ); ?>" aria-label="<?php echo esc_attr( sprintf( __( 'Add %s to cart', 'booknest' ), $product->get_name() ) ); ?>">
					<?php esc_html_e( 'Add to cart', 'booknest' ); ?>
				</a>
				<a class="btn btn--landing-ghost" href="<?php echo esc_url( $product->get_permalink() ); ?>">
					<?php esc_html_e( 'View details', 'booknest' ); ?>
				</a>
			</div>

			<p class="landing-book__trust">
				<span>🔒 <?php esc_html_e( 'Secure checkout', 'booknest' ); ?></span>
				<span>⚡ <?php esc_html_e( 'Instant access', 'booknest' ); ?></span>
				<span>♾️ <?php esc_html_e( 'Yours forever', 'booknest' ); ?></span>
			</p>
		</div>
	</div>
</article>
