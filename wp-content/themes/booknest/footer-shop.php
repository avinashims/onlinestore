<?php
/**
 * WooCommerce footer — minimal layout on checkout.
 *
 * @package BookNest
 */
if ( function_exists( 'is_checkout' ) && is_checkout() ) {
	get_footer( 'checkout' );
	return;
}
get_footer();
