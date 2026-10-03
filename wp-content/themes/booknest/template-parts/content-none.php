<?php
/**
 * No content.
 *
 * @package BookNest
 */
?>
<section class="no-results card" style="padding:2rem;text-align:center;">
	<h2><?php esc_html_e( 'Nothing found', 'booknest' ); ?></h2>
	<p><?php esc_html_e( 'Try a different search or browse our shop.', 'booknest' ); ?></p>
	<?php if ( function_exists( 'wc_get_page_permalink' ) ) : ?>
		<a class="btn btn--primary" href="<?php echo esc_url( wc_get_page_permalink( 'shop' ) ); ?>"><?php esc_html_e( 'Browse eBooks', 'booknest' ); ?></a>
	<?php endif; ?>
</section>
