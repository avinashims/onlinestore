<?php
/**
 * Quick view modal shell.
 *
 * @package BookNest
 */
?>
<div class="modal" data-quick-view-modal role="dialog" aria-modal="true" aria-labelledby="quick-view-title">
	<div class="modal__dialog card">
		<button type="button" class="btn btn--ghost" style="position:absolute;top:0.5rem;right:0.5rem;" data-modal-close aria-label="<?php esc_attr_e( 'Close', 'booknest' ); ?>">&times;</button>
		<h2 id="quick-view-title" class="screen-reader-text"><?php esc_html_e( 'Quick view', 'booknest' ); ?></h2>
		<div data-quick-view-content></div>
	</div>
</div>
