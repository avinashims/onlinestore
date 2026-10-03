<?php
/**
 * Footer template — Kobo-inspired.
 *
 * @package BookNest
 */

if ( function_exists( 'is_checkout' ) && is_checkout() ) {
	load_template( get_template_directory() . '/footer-checkout.php' );
	return;
}
?>

</div><!-- #page -->

<footer class="site-footer" role="contentinfo">
	<div class="container">
		<div class="footer-grid footer-grid--kobo">
			<div class="footer-brand">
				<a class="site-title" href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php bloginfo( 'name' ); ?></a>
				<p class="footer-tagline"><?php esc_html_e( 'eBooks and audiobooks. Read anywhere.', 'booknest' ); ?></p>
			</div>

			<div class="footer-links">
				<h4><?php esc_html_e( 'Store', 'booknest' ); ?></h4>
				<?php
				wp_nav_menu(
					array(
						'theme_location' => 'footer',
						'menu_class'     => 'footer-menu',
						'container'      => false,
						'fallback_cb'    => 'booknest_footer_fallback_menu',
					)
				);
				?>
			</div>

			<div class="footer-links">
				<h4><?php esc_html_e( 'About', 'booknest' ); ?></h4>
				<ul class="footer-menu">
					<li><a href="<?php echo esc_url( home_url( '/about/' ) ); ?>"><?php esc_html_e( 'About us', 'booknest' ); ?></a></li>
					<li><a href="<?php echo esc_url( home_url( '/help/' ) ); ?>"><?php esc_html_e( 'Help', 'booknest' ); ?></a></li>
					<li><a href="<?php echo esc_url( home_url( '/privacy/' ) ); ?>"><?php esc_html_e( 'Privacy', 'booknest' ); ?></a></li>
				</ul>
			</div>

			<div class="footer-payments">
				<h4><?php esc_html_e( 'We accept', 'booknest' ); ?></h4>
				<div class="payment-icons" aria-hidden="true">
					<span class="payment-badge">Visa</span>
					<span class="payment-badge">Mastercard</span>
					<span class="payment-badge">PayPal</span>
					<span class="payment-badge">Stripe</span>
				</div>
			</div>
		</div>

		<div class="footer-bottom">
			<p>&copy; <?php echo esc_html( gmdate( 'Y' ) ); ?> <?php bloginfo( 'name' ); ?>. <?php esc_html_e( 'All rights reserved.', 'booknest' ); ?></p>
		</div>
	</div>
</footer>

<?php wp_footer(); ?>
</body>
</html>
