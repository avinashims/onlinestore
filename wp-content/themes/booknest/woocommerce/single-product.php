<?php
/**
 * Single product override.
 *
 * @package BookNest
 */

defined( 'ABSPATH' ) || exit;

get_header( 'shop' );

while ( have_posts() ) :
	the_post();
	global $product;
	$author    = booknest_get_product_author( $product->get_id() );
	$pages     = get_post_meta( $product->get_id(), BOOKNEST_META_PAGES, true );
	$format    = get_post_meta( $product->get_id(), BOOKNEST_META_FORMAT, true );
	$language  = get_post_meta( $product->get_id(), BOOKNEST_META_LANGUAGE, true );
	$file_size = get_post_meta( $product->get_id(), BOOKNEST_META_FILE_SIZE, true );
	$sample    = get_post_meta( $product->get_id(), BOOKNEST_META_SAMPLE_PDF, true );
	$toc       = get_post_meta( $product->get_id(), BOOKNEST_META_TOC, true );
	$formats   = $format ? array_map( 'trim', explode( ',', $format ) ) : array();
	?>

	<main id="primary" class="site-main woocommerce-main single-product container" role="main">
		<div class="single-product-layout">
			<div class="single-product-gallery">
				<?php woocommerce_show_product_images(); ?>
			</div>
			<div class="summary entry-summary">
				<h1 class="product_title entry-title"><?php the_title(); ?></h1>
				<?php if ( $author ) : ?>
					<p class="product-author"><?php printf( esc_html__( 'By %s', 'booknest' ), esc_html( $author ) ); ?></p>
				<?php endif; ?>
				<?php woocommerce_template_single_rating(); ?>
				<?php woocommerce_template_single_price(); ?>

				<?php if ( $formats ) : ?>
					<div class="product-meta-badges">
						<?php foreach ( $formats as $f ) : ?>
							<span class="format-badge"><?php echo esc_html( strtoupper( $f ) ); ?></span>
						<?php endforeach; ?>
					</div>
				<?php endif; ?>

				<ul class="product-specs">
					<?php if ( $pages ) : ?>
						<li><?php printf( esc_html__( '%s pages', 'booknest' ), esc_html( $pages ) ); ?></li>
					<?php endif; ?>
					<?php if ( $language ) : ?>
						<li><?php printf( esc_html__( 'Language: %s', 'booknest' ), esc_html( $language ) ); ?></li>
					<?php endif; ?>
					<?php if ( $file_size ) : ?>
						<li><?php printf( esc_html__( 'File size: %s', 'booknest' ), esc_html( $file_size ) ); ?></li>
					<?php endif; ?>
				</ul>

				<div class="buy-actions">
					<?php woocommerce_template_single_add_to_cart(); ?>
					<a class="btn btn--accent" href="<?php echo esc_url( add_query_arg( array( 'add-to-cart' => $product->get_id(), 'booknest_buy_now' => '1' ), $product->get_permalink() ) ); ?>">
						<?php esc_html_e( 'Buy Now', 'booknest' ); ?>
					</a>
					<?php if ( $sample ) : ?>
						<a class="btn btn--secondary" href="<?php echo esc_url( $sample ); ?>" target="_blank" rel="noopener noreferrer">
							<?php esc_html_e( 'Download sample PDF', 'booknest' ); ?>
						</a>
					<?php endif; ?>
				</div>

				<?php woocommerce_template_single_excerpt(); ?>
			</div>
		</div>

		<div class="product-tabs" data-product-tabs>
			<div class="product-tabs__nav" role="tablist">
				<button type="button" class="is-active" data-tab-target="desc" role="tab"><?php esc_html_e( 'Description', 'booknest' ); ?></button>
				<button type="button" data-tab-target="toc" role="tab"><?php esc_html_e( 'Table of Contents', 'booknest' ); ?></button>
				<button type="button" data-tab-target="reviews" role="tab"><?php esc_html_e( 'Reviews', 'booknest' ); ?></button>
			</div>
			<div class="product-tabs__panel" data-tab-panel="desc" role="tabpanel">
				<?php the_content(); ?>
			</div>
			<div class="product-tabs__panel" data-tab-panel="toc" role="tabpanel" hidden>
				<?php
				if ( $toc ) {
					echo wp_kses_post( wpautop( $toc ) );
				} else {
					echo '<p>' . esc_html__( 'Table of contents not available.', 'booknest' ) . '</p>';
				}
				?>
			</div>
			<div class="product-tabs__panel" data-tab-panel="reviews" role="tabpanel" hidden>
				<?php comments_template(); ?>
			</div>
		</div>

		<?php woocommerce_output_related_products(); ?>
	</main>

	<div class="sticky-atc" data-sticky-atc>
		<div>
			<strong><?php echo esc_html( $product->get_name() ); ?></strong>
			<div class="price"><?php echo wp_kses_post( $product->get_price_html() ); ?></div>
		</div>
		<a class="btn btn--primary" href="<?php echo esc_url( $product->add_to_cart_url() ); ?>"><?php esc_html_e( 'Add to cart', 'booknest' ); ?></a>
	</div>

	<?php
endwhile;

get_footer( 'shop' );
