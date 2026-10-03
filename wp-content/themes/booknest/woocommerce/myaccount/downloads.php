<?php
/**
 * Downloads / Library page.
 *
 * @package BookNest
 * @version 7.8.0
 */

defined( 'ABSPATH' ) || exit;

$downloads = WC()->customer->get_downloadable_products();
?>

<h2><?php esc_html_e( 'My Library', 'booknest' ); ?></h2>

<?php if ( $downloads ) : ?>
	<div class="my-library-grid">
		<?php foreach ( $downloads as $download ) : ?>
			<article class="library-card card">
				<?php
				$product = wc_get_product( $download['product_id'] );
				if ( $product && $product->get_image_id() ) {
					echo wp_get_attachment_image( $product->get_image_id(), 'booknest-book-cover', false, array( 'loading' => 'lazy' ) );
				}
				?>
				<h3><?php echo esc_html( $download['download_name'] ); ?></h3>
				<p class="product-card__author"><?php echo esc_html( booknest_get_product_author( $download['product_id'] ) ); ?></p>
				<a class="btn btn--primary" href="<?php echo esc_url( $download['download_url'] ); ?>">
					<?php esc_html_e( 'Download', 'booknest' ); ?>
				</a>
			</article>
		<?php endforeach; ?>
	</div>
<?php else : ?>
	<div class="woocommerce-Message woocommerce-Message--info woocommerce-info">
		<?php esc_html_e( 'No downloads available yet.', 'booknest' ); ?>
	</div>
<?php endif; ?>
