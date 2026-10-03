<?php
/**
 * Single post.
 *
 * @package BookNest
 */

get_header();
?>

<main id="primary" class="site-main container" style="padding:2rem 0 3rem;" role="main">
	<?php
	while ( have_posts() ) :
		the_post();
		get_template_part( 'template-parts/content', get_post_type() );
		if ( comments_open() || get_comments_number() ) {
			comments_template();
		}
	endwhile;
	?>
</main>

<?php
get_footer();
