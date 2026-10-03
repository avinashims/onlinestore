<?php
/**
 * Shop toolbar.
 *
 * @package BookNest
 */
?>
<div class="shop-toolbar">
	<div class="view-toggle" role="group" aria-label="<?php esc_attr_e( 'View mode', 'booknest' ); ?>">
		<button type="button" class="is-active" data-view-toggle data-view="grid" aria-pressed="true"><?php esc_html_e( 'Grid', 'booknest' ); ?></button>
		<button type="button" data-view-toggle data-view="list" aria-pressed="false"><?php esc_html_e( 'List', 'booknest' ); ?></button>
	</div>
	<?php woocommerce_catalog_ordering(); ?>
</div>
