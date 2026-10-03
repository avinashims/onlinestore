<?php
/**
 * Minimal footer for checkout flow.
 *
 * @package BookNest
 */
?>

</div><!-- #page -->

<footer class="checkout-footer" role="contentinfo">
	<div class="container checkout-footer__inner">
		<p>&copy; <?php echo esc_html( gmdate( 'Y' ) ); ?> <?php bloginfo( 'name' ); ?></p>
	</div>
</footer>

<?php wp_footer(); ?>
</body>
</html>
