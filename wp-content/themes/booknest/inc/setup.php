<?php
/**
 * Theme setup.
 *
 * @package BookNest
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Sets up theme defaults and registers support for various WordPress features.
 */
function booknest_setup() {
	load_theme_textdomain( 'booknest', BOOKNEST_DIR . '/languages' );

	add_theme_support( 'automatic-feed-links' );
	add_theme_support( 'title-tag' );
	add_theme_support( 'post-thumbnails' );
	add_theme_support(
		'html5',
		array(
			'search-form',
			'comment-form',
			'comment-list',
			'gallery',
			'caption',
			'style',
			'script',
		)
	);
	add_theme_support( 'customize-selective-refresh-widgets' );
	add_theme_support( 'align-wide' );
	add_theme_support( 'responsive-embeds' );
	add_theme_support(
		'custom-logo',
		array(
			'height'      => 80,
			'width'       => 240,
			'flex-height' => true,
			'flex-width'  => true,
		)
	);

	add_image_size( 'booknest-book-cover', 400, 533, true );
	add_image_size( 'booknest-book-cover-large', 600, 800, true );

	register_nav_menus(
		array(
			'primary' => esc_html__( 'Primary Menu', 'booknest' ),
			'footer'  => esc_html__( 'Footer Menu', 'booknest' ),
		)
	);
}
add_action( 'after_setup_theme', 'booknest_setup' );

/**
 * Content width.
 */
function booknest_content_width() {
	$GLOBALS['content_width'] = apply_filters( 'booknest_content_width', 1200 );
}
add_action( 'after_setup_theme', 'booknest_content_width', 0 );

/**
 * Body classes.
 *
 * @param array $classes Body classes.
 * @return array
 */
function booknest_body_classes( $classes ) {
	if ( is_singular() ) {
		$classes[] = 'singular';
	}
	if ( booknest_is_dark_mode() ) {
		$classes[] = 'booknest-dark';
	}
	return $classes;
}
add_filter( 'body_class', 'booknest_body_classes' );

/**
 * Whether dark mode is active (cookie).
 *
 * @return bool
 */
function booknest_is_dark_mode() {
	return isset( $_COOKIE['booknest_theme'] ) && 'dark' === sanitize_text_field( wp_unslash( $_COOKIE['booknest_theme'] ) );
}
