<?php
/**
 * Archive header.
 *
 * @package BookNest
 */
?>
<header class="page-header" style="padding:2rem 0 1rem;">
	<?php the_archive_title( '<h1 class="page-title">', '</h1>' ); ?>
	<?php the_archive_description( '<div class="archive-description">', '</div>' ); ?>
</header>
