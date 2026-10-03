<?php
/**
 * My Account wrapper.
 *
 * @package BookNest
 * @version 3.5.0
 */

defined( 'ABSPATH' ) || exit;
?>

<div class="container booknest-myaccount-dashboard">
	<?php do_action( 'woocommerce_account_navigation' ); ?>
	<div class="woocommerce-MyAccount-content">
		<?php do_action( 'woocommerce_account_content' ); ?>
	</div>
</div>
