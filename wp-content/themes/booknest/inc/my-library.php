<?php
/**
 * My Library on account dashboard.
 *
 * @package BookNest
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'WooCommerce' ) ) {
	return;
}

/**
 * Render library section on dashboard.
 */
function booknest_my_library_section() {
	if ( ! is_user_logged_in() ) {
		return;
	}

	$downloads = function_exists( 'wc_get_customer_available_downloads' )
		? wc_get_customer_available_downloads( get_current_user_id() )
		: array();
	?>
	<section class="booknest-my-library" aria-labelledby="my-library-title">
		<h2 id="my-library-title"><?php esc_html_e( 'My Library', 'booknest' ); ?></h2>
		<?php if ( $downloads ) : ?>
			<div class="my-library-grid">
				<?php foreach ( $downloads as $download ) : ?>
					<article class="library-card card">
						<?php
						$product = wc_get_product( $download['product_id'] );
						if ( $product ) {
							echo wp_get_attachment_image( $product->get_image_id(), 'booknest-book-cover', false, array( 'loading' => 'lazy' ) );
							echo '<h3>' . esc_html( $product->get_name() ) . '</h3>';
						} else {
							echo '<h3>' . esc_html( $download['download_name'] ) . '</h3>';
						}
						?>
						<a class="download" href="<?php echo esc_url( $download['download_url'] ); ?>">
							<?php esc_html_e( 'Download', 'booknest' ); ?>
						</a>
					</article>
				<?php endforeach; ?>
			</div>
		<?php else : ?>
			<p><?php esc_html_e( 'Your purchased eBooks will appear here.', 'booknest' ); ?></p>
			<a class="btn btn--primary" href="<?php echo esc_url( wc_get_page_permalink( 'shop' ) ); ?>"><?php esc_html_e( 'Browse eBooks', 'booknest' ); ?></a>
		<?php endif; ?>
	</section>
	<?php
}
add_action( 'woocommerce_account_dashboard', 'booknest_my_library_section', 5 );

/**
 * Whether an order contains only digital / downloadable products.
 *
 * @param WC_Order $order Order.
 * @return bool
 */
function booknest_order_is_digital_only( $order ) {
	if ( ! $order instanceof WC_Order || ! $order->has_downloadable_item() ) {
		return false;
	}

	foreach ( $order->get_items() as $item ) {
		if ( ! $item instanceof WC_Order_Item_Product ) {
			continue;
		}
		$product = $item->get_product();
		if ( ! $product instanceof WC_Product ) {
			continue;
		}
		if ( $product->needs_shipping() && ! $product->is_downloadable() ) {
			return false;
		}
	}

	return true;
}

/**
 * COD and other offline methods do not mark orders paid; WooCommerce withholds
 * download permissions until completed. eBook orders should unlock immediately.
 *
 * @param int $order_id Order ID.
 */
function booknest_release_digital_downloads( $order_id ) {
	$order = wc_get_order( $order_id );
	if ( ! $order || ! booknest_order_is_digital_only( $order ) ) {
		return;
	}

	if ( $order->get_downloadable_items() ) {
		return;
	}

	$order->update_status(
		'completed',
		__( 'Digital order — download access granted.', 'booknest' ),
		true
	);
}
add_action( 'woocommerce_thankyou', 'booknest_release_digital_downloads', 1 );

/**
 * Grant downloads before scripts run on the order-received page.
 */
function booknest_release_digital_downloads_early() {
	if ( ! is_order_received_page() ) {
		return;
	}

	global $wp;
	$order_id = isset( $wp->query_vars['order-received'] ) ? absint( $wp->query_vars['order-received'] ) : 0;
	if ( $order_id ) {
		booknest_release_digital_downloads( $order_id );
	}
}
add_action( 'template_redirect', 'booknest_release_digital_downloads_early', 5 );

/**
 * Thank you page download emphasis.
 *
 * @param int $order_id Order ID.
 */
function booknest_thankyou_downloads( $order_id ) {
	$order = wc_get_order( $order_id );
	if ( ! $order ) {
		return;
	}

	if ( booknest_order_is_digital_only( $order ) && ! $order->get_downloadable_items() ) {
		booknest_release_digital_downloads( $order_id );
		$order = wc_get_order( $order_id );
	}

	$downloads = $order->get_downloadable_items();
	if ( ! $downloads ) {
		if ( $order->has_downloadable_item() ) {
			echo '<div class="booknest-thankyou-downloads card" style="padding:1.5rem;margin:1.5rem 0;">';
			echo '<p>' . esc_html__( 'Your download is being prepared. Refresh this page in a moment or check WooCommerce → Orders that the product has a downloadable file attached.', 'booknest' ) . '</p>';
			echo '</div>';
		}
		return;
	}
	echo '<div class="booknest-thankyou-downloads card" style="padding:1.5rem;margin:1.5rem 0;">';
	echo '<h2>' . esc_html__( 'Download your eBooks', 'booknest' ) . '</h2>';
	echo '<p class="booknest-auto-download-note">' . esc_html__( 'Your download should start automatically. If it does not, use the button below.', 'booknest' ) . '</p><ul>';
	foreach ( $downloads as $download ) {
		printf(
			'<li><a class="btn btn--primary" href="%s">%s</a></li>',
			esc_url( $download['download_url'] ),
			esc_html( $download['download_name'] )
		);
	}
	echo '</ul></div>';
}
add_action( 'woocommerce_thankyou', 'booknest_thankyou_downloads', 5 );
