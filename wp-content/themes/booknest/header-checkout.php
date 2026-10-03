<?php
/**
 * Distraction-free header for checkout and order confirmation.
 *
 * @package BookNest
 */
?><!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="profile" href="https://gmpg.org/xfn/11">
	<?php wp_head(); ?>
</head>
<body <?php body_class( 'booknest-checkout-page' ); ?>>
<?php wp_body_open(); ?>

<a class="skip-link screen-reader-text" href="#primary"><?php esc_html_e( 'Skip to checkout', 'booknest' ); ?></a>

<header class="checkout-header" role="banner">
	<div class="container checkout-header__inner">
		<a class="checkout-header__logo" href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php bloginfo( 'name' ); ?></a>
		<p class="checkout-header__secure"><?php esc_html_e( 'Secure checkout', 'booknest' ); ?></p>
	</div>
</header>

<div id="page" class="site">
