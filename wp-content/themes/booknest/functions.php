<?php
/**
 * BookNest theme functions and definitions.
 *
 * @package BookNest
 */

if ( ! defined( 'BOOKNEST_VERSION' ) ) {
	define( 'BOOKNEST_VERSION', '1.0.0' );
}

if ( ! defined( 'BOOKNEST_DIR' ) ) {
	define( 'BOOKNEST_DIR', get_template_directory() );
}

if ( ! defined( 'BOOKNEST_URI' ) ) {
	define( 'BOOKNEST_URI', get_template_directory_uri() );
}

require_once BOOKNEST_DIR . '/inc/template-tags.php';
require_once BOOKNEST_DIR . '/inc/setup.php';
require_once BOOKNEST_DIR . '/inc/enqueue.php';
require_once BOOKNEST_DIR . '/inc/widgets.php';
require_once BOOKNEST_DIR . '/inc/product-meta.php';
require_once BOOKNEST_DIR . '/inc/woocommerce.php';
require_once BOOKNEST_DIR . '/inc/checkout.php';
require_once BOOKNEST_DIR . '/inc/ajax.php';
require_once BOOKNEST_DIR . '/inc/schema.php';
require_once BOOKNEST_DIR . '/inc/my-library.php';
require_once BOOKNEST_DIR . '/inc/landing.php';
