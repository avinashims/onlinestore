<?php
/**
 * WooCommerce header — minimal layout on checkout.
 *
 * @package BookNest
 */
if ( function_exists( 'is_checkout' ) && is_checkout() ) {
	get_header( 'checkout' );
	return;
}
get_header();
