<?php
/**
 * Search results.
 *
 * @package BookNest
 */

get_header();
?>

<main id="primary" class="site-main container" role="main">
	<header class="page-header" style="padding:2rem 0 1rem;">
		<h1 class="page-title">
			<?php
			printf(
				/* translators: %s: search query */
				esc_html__( 'Search results for: %s', 'booknest' ),
				'<span>' . esc_html( get_search_query() ) . '</span>'
			);
			?>
		</h1>
	</header>

	<div class="posts-grid">
		<?php
		if ( have_posts() ) :
			while ( have_posts() ) :
				the_post();
				if ( 'product' === get_post_type() && function_exists( 'wc_get_template_part' ) ) {
					wc_get_template_part( 'content', 'product' );
				} else {
					get_template_part( 'template-parts/content', get_post_type() );
				}
			endwhile;
			the_posts_pagination();
		else :
			get_template_part( 'template-parts/content', 'none' );
		endif;
		?>
	</div>
</main>

<?php
get_footer();
