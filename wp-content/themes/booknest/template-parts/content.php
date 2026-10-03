<?php
/**
 * Post card.
 *
 * @package BookNest
 */
?>
<article id="post-<?php the_ID(); ?>" <?php post_class( 'card' ); ?>>
	<?php if ( has_post_thumbnail() ) : ?>
		<a href="<?php the_permalink(); ?>" class="post-thumbnail">
			<?php the_post_thumbnail( 'medium_large', array( 'loading' => 'lazy', 'alt' => the_title_attribute( array( 'echo' => false ) ) ) ); ?>
		</a>
	<?php endif; ?>
	<div style="padding:1.25rem;">
		<h2 class="entry-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
		<div class="entry-meta"><?php echo esc_html( get_the_date() ); ?></div>
		<div class="entry-summary"><?php the_excerpt(); ?></div>
	</div>
</article>
