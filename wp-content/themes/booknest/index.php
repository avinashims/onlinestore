<?php
/**
 * Main template file.
 *
 * @package BookNest
 */

get_header();
?>

<main id="primary" class="site-main container" role="main">
	<?php if ( have_posts() ) : ?>
		<header class="page-header">
			<?php
			if ( is_home() && ! is_front_page() ) {
				printf( '<h1 class="page-title">%s</h1>', esc_html( single_post_title( '', false ) ) );
			} elseif ( is_archive() ) {
				the_archive_title( '<h1 class="page-title">', '</h1>' );
				the_archive_description( '<div class="archive-description">', '</div>' );
			}
			?>
		</header>

		<div class="posts-grid">
			<?php
			while ( have_posts() ) :
				the_post();
				get_template_part( 'template-parts/content', get_post_type() );
			endwhile;
			?>
		</div>

		<?php the_posts_pagination(); ?>
	<?php else : ?>
		<?php get_template_part( 'template-parts/content', 'none' ); ?>
	<?php endif; ?>
</main>

<?php
get_footer();
