<?php
/**
 * Header template — Kobo-inspired layout.
 *
 * @package BookNest
 */

if ( function_exists( 'is_checkout' ) && is_checkout() ) {
	load_template( get_template_directory() . '/header-checkout.php' );
	return;
}

$shop_url    = function_exists( 'wc_get_page_permalink' ) ? wc_get_page_permalink( 'shop' ) : home_url( '/' );
$account_url = class_exists( 'WooCommerce' ) ? wc_get_page_permalink( 'myaccount' ) : wp_login_url();
$cart_url    = function_exists( 'wc_get_cart_url' ) ? wc_get_cart_url() : '#';
$is_logged   = is_user_logged_in();
?><!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="profile" href="https://gmpg.org/xfn/11">
	<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<a class="skip-link screen-reader-text" href="#primary"><?php esc_html_e( 'Skip to main content', 'booknest' ); ?></a>

<header class="site-header kobo-header" id="site-header" role="banner">
	<div class="header-utility">
		<div class="container header-utility__inner">
			<p class="header-utility__store"><?php esc_html_e( 'eBooks · Audiobooks · Instant download', 'booknest' ); ?></p>
			<ul class="header-utility__links">
				<li><a href="<?php echo esc_url( home_url( '/help/' ) ); ?>"><?php esc_html_e( 'Help', 'booknest' ); ?></a></li>
				<li><a href="<?php echo esc_url( home_url( '/gift-cards/' ) ); ?>"><?php esc_html_e( 'Gift Cards', 'booknest' ); ?></a></li>
			</ul>
		</div>
	</div>

	<div class="header-main">
		<div class="container header-main__inner">
			<div class="site-branding">
				<?php if ( has_custom_logo() ) : ?>
					<?php the_custom_logo(); ?>
				<?php else : ?>
					<a class="site-logo" href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home" aria-label="<?php bloginfo( 'name' ); ?>">
						<span class="site-logo__mark"><?php esc_html_e( 'BookNest', 'booknest' ); ?></span>
					</a>
				<?php endif; ?>
			</div>

			<form class="header-search" role="search" action="<?php echo esc_url( home_url( '/' ) ); ?>" method="get" autocomplete="off">
				<label class="screen-reader-text" for="header-search-input"><?php esc_html_e( 'Search', 'booknest' ); ?></label>
				<input type="search" id="header-search-input" name="s" placeholder="<?php esc_attr_e( 'Search on BookNest', 'booknest' ); ?>" value="<?php echo esc_attr( get_search_query() ); ?>" data-live-search aria-expanded="false" aria-controls="live-search-results">
				<input type="hidden" name="post_type" value="product">
				<button type="submit" class="header-search__submit" aria-label="<?php esc_attr_e( 'Search', 'booknest' ); ?>">
					<svg width="20" height="20" viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="M21 21l-4.35-4.35M11 18a7 7 0 100-14 7 7 0 000 14z" stroke="currentColor" stroke-width="2" stroke-linecap="round"/></svg>
				</button>
				<div id="live-search-results" class="live-search-results" hidden></div>
			</form>

			<div class="header-actions">
				<a class="header-action-tile" href="<?php echo esc_url( $account_url ); ?>" aria-label="<?php esc_attr_e( 'Wishlist', 'booknest' ); ?>">
					<svg width="22" height="22" viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="M12 21s-7-4.5-9.5-9C.5 8.5 2.5 5 6 5c2 0 3.5 1.5 4 2.5C10.5 6.5 12 5 14 5c3.5 0 5.5 3.5 3.5 7-2.5 4.5-9.5 9-9.5 9z" stroke="currentColor" stroke-width="2"/></svg>
					<span><?php esc_html_e( 'Wishlist', 'booknest' ); ?></span>
				</a>

				<button type="button" class="header-action-tile cart-trigger" data-cart-open aria-label="<?php esc_attr_e( 'Cart', 'booknest' ); ?>">
					<svg width="22" height="22" viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="M6 6h15l-1.5 9h-12zM6 6l-1-3H2M9 20a1 1 0 100-2 1 1 0 000 2zm8 0a1 1 0 100-2 1 1 0 000 2z" stroke="currentColor" stroke-width="2"/></svg>
					<span><?php esc_html_e( 'Cart', 'booknest' ); ?></span>
					<?php if ( class_exists( 'WooCommerce' ) ) : ?>
						<?php booknest_cart_count_markup(); ?>
					<?php endif; ?>
				</button>

				<div class="header-auth">
					<?php if ( $is_logged ) : ?>
						<a class="btn btn--kobo" href="<?php echo esc_url( $account_url ); ?>"><?php esc_html_e( 'My account', 'booknest' ); ?></a>
					<?php else : ?>
						<a class="btn btn--kobo" href="<?php echo esc_url( $account_url ); ?>"><?php esc_html_e( 'Create account', 'booknest' ); ?></a>
						<a class="header-auth__signin" href="<?php echo esc_url( $account_url ); ?>"><?php esc_html_e( 'Sign in', 'booknest' ); ?></a>
					<?php endif; ?>
				</div>

				<button type="button" class="nav-toggle" aria-expanded="false" aria-controls="header-nav" data-nav-toggle>
					<span class="nav-toggle__bar"></span>
					<span class="screen-reader-text"><?php esc_html_e( 'Menu', 'booknest' ); ?></span>
				</button>
			</div>
		</div>
	</div>

	<nav class="header-nav" id="header-nav" aria-label="<?php esc_attr_e( 'Store', 'booknest' ); ?>">
		<div class="container">
			<?php
			wp_nav_menu(
				array(
					'theme_location' => 'primary',
					'menu_id'        => 'primary-menu',
					'menu_class'     => 'kobo-menu',
					'container'      => false,
					'fallback_cb'    => 'booknest_kobo_fallback_menu',
				)
			);
			?>
		</div>
	</nav>

	<div class="header-quicklinks">
		<div class="container header-quicklinks__scroll">
			<a href="<?php echo esc_url( add_query_arg( 'booknest_bestsellers', '1', $shop_url ) ); ?>"><?php esc_html_e( 'Bestselling eBooks', 'booknest' ); ?></a>
			<a href="<?php echo esc_url( $shop_url ); ?>?orderby=date"><?php esc_html_e( 'Recent Additions', 'booknest' ); ?></a>
			<a href="<?php echo esc_url( add_query_arg( 'max_price', '10', $shop_url ) ); ?>"><?php esc_html_e( 'Deals under $10', 'booknest' ); ?></a>
			<a href="<?php echo esc_url( $shop_url ); ?>"><?php esc_html_e( 'Exclusive picks', 'booknest' ); ?></a>
		</div>
	</div>
</header>

<?php get_template_part( 'template-parts/cart', 'drawer' ); ?>
<?php get_template_part( 'template-parts/modal', 'quick-view' ); ?>

<div id="page" class="site">
