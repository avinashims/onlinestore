<?php
/**
 * Minimal header for ebook landing page.
 *
 * @package BookNest
 */

$account_url = class_exists( 'WooCommerce' ) ? wc_get_page_permalink( 'myaccount' ) : wp_login_url();
?><!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="profile" href="https://gmpg.org/xfn/11">
	<?php wp_head(); ?>
</head>
<body <?php body_class( 'booknest-landing' ); ?>>
<?php wp_body_open(); ?>

<a class="skip-link screen-reader-text" href="#landing-main"><?php esc_html_e( 'Skip to content', 'booknest' ); ?></a>

<header class="landing-header" id="site-header" role="banner">
	<div class="container landing-header__inner">
		<a class="landing-header__logo" href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php bloginfo( 'name' ); ?></a>
		<nav class="landing-header__nav" aria-label="<?php esc_attr_e( 'Landing', 'booknest' ); ?>">
			<a href="#books"><?php esc_html_e( 'The books', 'booknest' ); ?></a>
			<a href="#why"><?php esc_html_e( 'Why digital', 'booknest' ); ?></a>
			<a href="#faq"><?php esc_html_e( 'FAQ', 'booknest' ); ?></a>
		</nav>
		<div class="landing-header__actions">
			<button type="button" class="landing-header__cart cart-trigger" data-cart-open aria-label="<?php esc_attr_e( 'Cart', 'booknest' ); ?>">
				<?php esc_html_e( 'Cart', 'booknest' ); ?>
				<?php if ( class_exists( 'WooCommerce' ) ) : ?>
					<?php booknest_cart_count_markup(); ?>
				<?php endif; ?>
			</button>
			<a class="btn btn--landing-primary btn--sm" href="#books"><?php esc_html_e( 'Get your copy', 'booknest' ); ?></a>
		</div>
	</div>
</header>

<?php get_template_part( 'template-parts/cart', 'drawer' ); ?>

<div id="page" class="site">
