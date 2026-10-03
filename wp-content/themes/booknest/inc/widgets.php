<?php
/**
 * Widget areas.
 *
 * @package BookNest
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Register widget areas.
 */
function booknest_widgets_init() {
	register_sidebar(
		array(
			'name'          => esc_html__( 'Shop Sidebar', 'booknest' ),
			'id'            => 'shop-sidebar',
			'description'   => esc_html__( 'Filters and widgets on the shop page.', 'booknest' ),
			'before_widget' => '<section id="%1$s" class="widget card %2$s">',
			'after_widget'  => '</section>',
			'before_title'  => '<h3 class="widget-title">',
			'after_title'   => '</h3>',
		)
	);

	register_sidebar(
		array(
			'name'          => esc_html__( 'Footer', 'booknest' ),
			'id'            => 'footer-widgets',
			'description'   => esc_html__( 'Footer widget column.', 'booknest' ),
			'before_widget' => '<div id="%1$s" class="footer-widget %2$s">',
			'after_widget'  => '</div>',
			'before_title'  => '<h4 class="footer-widget-title">',
			'after_title'   => '</h4>',
		)
	);
}
add_action( 'widgets_init', 'booknest_widgets_init' );
