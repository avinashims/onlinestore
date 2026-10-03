<?php
/**
 * Slide-in mini cart.
 *
 * @package BookNest
 */
if ( ! class_exists( 'WooCommerce' ) ) {
	return;
}
?>
<aside class="cart-drawer" data-cart-drawer aria-hidden="true" aria-label="<?php esc_attr_e( 'Shopping cart', 'booknest' ); ?>">
	<div class="cart-drawer__overlay" tabindex="-1"></div>
	<div class="cart-drawer__panel" role="dialog" aria-modal="true" aria-labelledby="cart-drawer-title">
		<div class="cart-drawer__header">
			<h2 id="cart-drawer-title"><?php esc_html_e( 'Your cart', 'booknest' ); ?></h2>
			<button type="button" class="btn btn--ghost" data-cart-close aria-label="<?php esc_attr_e( 'Close cart', 'booknest' ); ?>">&times;</button>
		</div>
		<div class="cart-drawer__body" data-mini-cart-body>
			<?php woocommerce_mini_cart(); ?>
		</div>
		<div class="cart-drawer__footer">
			<a class="btn btn--primary" style="width:100%;" href="<?php echo esc_url( wc_get_checkout_url() ); ?>">
				<?php esc_html_e( 'Checkout', 'booknest' ); ?>
			</a>
		</div>
	</div>
</aside>
