<?php
/**
 * Minimal footer for landing page.
 *
 * @package BookNest
 */
?>

</div><!-- #page -->

<footer class="landing-footer" role="contentinfo">
	<div class="container landing-footer__inner">
		<p>&copy; <?php echo esc_html( gmdate( 'Y' ) ); ?> <?php bloginfo( 'name' ); ?>. <?php esc_html_e( 'All rights reserved.', 'booknest' ); ?></p>
		<p class="landing-footer__links">
			<a href="<?php echo esc_url( home_url( '/privacy/' ) ); ?>"><?php esc_html_e( 'Privacy', 'booknest' ); ?></a>
			<a href="<?php echo esc_url( home_url( '/terms/' ) ); ?>"><?php esc_html_e( 'Terms', 'booknest' ); ?></a>
			<a href="<?php echo esc_url( class_exists( 'WooCommerce' ) ? wc_get_page_permalink( 'myaccount' ) : wp_login_url() ); ?>"><?php esc_html_e( 'My library', 'booknest' ); ?></a>
		</p>
	</div>
</footer>

<?php wp_footer(); ?>
</body>
</html>
