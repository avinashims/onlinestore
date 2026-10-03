<?php
/**
 * 404 template.
 *
 * @package BookNest
 */

get_header();
?>

<main id="primary" class="site-main container" style="padding:4rem 0;text-align:center;" role="main">
	<h1><?php esc_html_e( 'Page not found', 'booknest' ); ?></h1>
	<p><?php esc_html_e( 'This chapter seems missing from our catalog.', 'booknest' ); ?></p>
	<a class="btn btn--primary" href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php esc_html_e( 'Back home', 'booknest' ); ?></a>
</main>

<?php
get_footer();
