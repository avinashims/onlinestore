<?php
/**
 * Custom product meta for eBooks.
 *
 * @package BookNest
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'BOOKNEST_META_AUTHOR', '_booknest_author' );
define( 'BOOKNEST_META_PAGES', '_booknest_pages' );
define( 'BOOKNEST_META_FORMAT', '_booknest_format' );
define( 'BOOKNEST_META_LANGUAGE', '_booknest_language' );
define( 'BOOKNEST_META_FILE_SIZE', '_booknest_file_size' );
define( 'BOOKNEST_META_SAMPLE_PDF', '_booknest_sample_pdf' );
define( 'BOOKNEST_META_TOC', '_booknest_toc' );
define( 'BOOKNEST_META_BESTSELLER', '_booknest_bestseller' );

/**
 * Add meta box on product edit screen.
 */
function booknest_add_product_meta_box() {
	add_meta_box(
		'booknest_ebook_details',
		esc_html__( 'eBook Details', 'booknest' ),
		'booknest_render_product_meta_box',
		'product',
		'normal',
		'high'
	);
}
add_action( 'add_meta_boxes', 'booknest_add_product_meta_box' );

/**
 * Render meta box fields.
 *
 * @param WP_Post $post Post object.
 */
function booknest_render_product_meta_box( $post ) {
	wp_nonce_field( 'booknest_save_product_meta', 'booknest_product_meta_nonce' );

	$author    = get_post_meta( $post->ID, BOOKNEST_META_AUTHOR, true );
	$pages     = get_post_meta( $post->ID, BOOKNEST_META_PAGES, true );
	$format    = get_post_meta( $post->ID, BOOKNEST_META_FORMAT, true );
	$language  = get_post_meta( $post->ID, BOOKNEST_META_LANGUAGE, true );
	$file_size = get_post_meta( $post->ID, BOOKNEST_META_FILE_SIZE, true );
	$sample    = get_post_meta( $post->ID, BOOKNEST_META_SAMPLE_PDF, true );
	$toc       = get_post_meta( $post->ID, BOOKNEST_META_TOC, true );
	$bestseller = get_post_meta( $post->ID, BOOKNEST_META_BESTSELLER, true );
	?>
	<p>
		<label for="booknest_author"><strong><?php esc_html_e( 'Author', 'booknest' ); ?></strong></label><br>
		<input type="text" class="widefat" id="booknest_author" name="booknest_author" value="<?php echo esc_attr( $author ); ?>">
	</p>
	<p>
		<label for="booknest_pages"><strong><?php esc_html_e( 'Page count', 'booknest' ); ?></strong></label><br>
		<input type="number" min="1" class="widefat" id="booknest_pages" name="booknest_pages" value="<?php echo esc_attr( $pages ); ?>">
	</p>
	<p>
		<label for="booknest_format"><strong><?php esc_html_e( 'Format (comma-separated: PDF, EPUB, MOBI)', 'booknest' ); ?></strong></label><br>
		<input type="text" class="widefat" id="booknest_format" name="booknest_format" value="<?php echo esc_attr( $format ); ?>">
	</p>
	<p>
		<label for="booknest_language"><strong><?php esc_html_e( 'Language', 'booknest' ); ?></strong></label><br>
		<input type="text" class="widefat" id="booknest_language" name="booknest_language" value="<?php echo esc_attr( $language ); ?>">
	</p>
	<p>
		<label for="booknest_file_size"><strong><?php esc_html_e( 'File size', 'booknest' ); ?></strong></label><br>
		<input type="text" class="widefat" id="booknest_file_size" name="booknest_file_size" value="<?php echo esc_attr( $file_size ); ?>" placeholder="e.g. 4.2 MB">
	</p>
	<p>
		<label for="booknest_sample_pdf"><strong><?php esc_html_e( 'Sample PDF URL', 'booknest' ); ?></strong></label><br>
		<input type="url" class="widefat" id="booknest_sample_pdf" name="booknest_sample_pdf" value="<?php echo esc_url( $sample ); ?>">
	</p>
	<p>
		<label for="booknest_toc"><strong><?php esc_html_e( 'Table of Contents', 'booknest' ); ?></strong></label><br>
		<textarea class="widefat" rows="6" id="booknest_toc" name="booknest_toc"><?php echo esc_textarea( $toc ); ?></textarea>
	</p>
	<p>
		<label>
			<input type="checkbox" name="booknest_bestseller" value="1" <?php checked( $bestseller, '1' ); ?>>
			<?php esc_html_e( 'Mark as Bestseller', 'booknest' ); ?>
		</label>
	</p>
	<?php
}

/**
 * Save product meta.
 *
 * @param int $post_id Post ID.
 */
function booknest_save_product_meta( $post_id ) {
	if ( ! isset( $_POST['booknest_product_meta_nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['booknest_product_meta_nonce'] ) ), 'booknest_save_product_meta' ) ) {
		return;
	}
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
		return;
	}
	if ( ! current_user_can( 'edit_post', $post_id ) ) {
		return;
	}

	$fields = array(
		'booknest_author'     => BOOKNEST_META_AUTHOR,
		'booknest_pages'      => BOOKNEST_META_PAGES,
		'booknest_format'     => BOOKNEST_META_FORMAT,
		'booknest_language'   => BOOKNEST_META_LANGUAGE,
		'booknest_file_size'  => BOOKNEST_META_FILE_SIZE,
		'booknest_sample_pdf' => BOOKNEST_META_SAMPLE_PDF,
		'booknest_toc'        => BOOKNEST_META_TOC,
	);

	foreach ( $fields as $key => $meta_key ) {
		if ( isset( $_POST[ $key ] ) ) {
			$value = wp_unslash( $_POST[ $key ] );
			if ( BOOKNEST_META_SAMPLE_PDF === $meta_key ) {
				update_post_meta( $post_id, $meta_key, esc_url_raw( $value ) );
			} elseif ( BOOKNEST_META_TOC === $meta_key ) {
				update_post_meta( $post_id, $meta_key, sanitize_textarea_field( $value ) );
			} else {
				update_post_meta( $post_id, $meta_key, sanitize_text_field( $value ) );
			}
		}
	}

	$bestseller = isset( $_POST['booknest_bestseller'] ) ? '1' : '';
	update_post_meta( $post_id, BOOKNEST_META_BESTSELLER, $bestseller );
}
add_action( 'save_post_product', 'booknest_save_product_meta' );

/**
 * Get author for product.
 *
 * @param int $product_id Product ID.
 * @return string
 */
function booknest_get_product_author( $product_id = 0 ) {
	$product_id = $product_id ? $product_id : get_the_ID();
	return (string) get_post_meta( $product_id, BOOKNEST_META_AUTHOR, true );
}

/**
 * Is product marked bestseller.
 *
 * @param int $product_id Product ID.
 * @return bool
 */
function booknest_is_bestseller( $product_id = 0 ) {
	$product_id = $product_id ? $product_id : get_the_ID();
	return '1' === get_post_meta( $product_id, BOOKNEST_META_BESTSELLER, true );
}
