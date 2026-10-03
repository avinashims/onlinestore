<?php
/**
 * Front page — focused landing for 1–2 eBooks.
 *
 * Mark products as "Featured" in WooCommerce to choose which appear here (max 2).
 *
 * @package BookNest
 */

get_header( 'landing' );

$products = booknest_get_landing_products();
$primary  = isset( $products[0] ) ? $products[0] : null;
$secondary = isset( $products[1] ) ? $products[1] : null;
?>

<main id="landing-main" class="landing-page">
	<?php if ( ! $primary ) : ?>
		<section class="landing-empty container">
			<h1><?php esc_html_e( 'Your eBook landing page is ready', 'booknest' ); ?></h1>
			<p><?php esc_html_e( 'Publish 1–2 WooCommerce products and star them as Featured — they will show here automatically.', 'booknest' ); ?></p>
			<?php if ( current_user_can( 'edit_products' ) ) : ?>
				<a class="btn btn--landing-primary" href="<?php echo esc_url( admin_url( 'edit.php?post_type=product' ) ); ?>"><?php esc_html_e( 'Add products', 'booknest' ); ?></a>
			<?php endif; ?>
		</section>
	<?php else : ?>

	<section class="landing-hero">
		<div class="container landing-hero__inner">
			<div class="landing-hero__copy">
				<p class="landing-eyebrow landing-eyebrow--light"><?php esc_html_e( 'Digital eBook store', 'booknest' ); ?></p>
				<h1 class="landing-hero__headline">
					<?php esc_html_e( 'Start reading', 'booknest' ); ?>
					<span><?php echo esc_html( $primary->get_name() ); ?></span>
					<?php esc_html_e( 'today', 'booknest' ); ?>
				</h1>
				<p class="landing-hero__lead">
					<?php
					echo $primary->get_short_description()
						? esc_html( wp_strip_all_tags( $primary->get_short_description() ) )
						: esc_html__( 'One payment. Instant download. Read on phone, tablet, or eReader — anytime.', 'booknest' );
					?>
				</p>
				<div class="landing-hero__cta">
					<a class="btn btn--landing-primary btn--lg" href="<?php echo esc_url( booknest_product_buy_now_url( $primary ) ); ?>">
						<?php esc_html_e( 'Buy now', 'booknest' ); ?> — <?php echo wp_kses_post( $primary->get_price_html() ); ?>
					</a>
					<a class="btn btn--landing-white" href="#books"><?php esc_html_e( 'See what’s inside', 'booknest' ); ?></a>
				</div>
			</div>
			<div class="landing-hero__cover" aria-hidden="true">
				<?php
				$img_id = $primary->get_image_id();
				if ( $img_id ) {
					echo wp_get_attachment_image( $img_id, 'booknest-book-cover-large', false, array( 'class' => 'landing-hero__img', 'alt' => '' ) );
				} else {
					echo '<img class="landing-hero__img" src="' . esc_url( wc_placeholder_img_src( 'booknest-book-cover-large' ) ) . '" alt="" width="280" height="373">';
				}
				?>
			</div>
		</div>
	</section>

	<section class="landing-trust">
		<div class="container landing-trust__grid">
			<div><strong><?php esc_html_e( 'Instant download', 'booknest' ); ?></strong><span><?php esc_html_e( 'Delivered to your inbox', 'booknest' ); ?></span></div>
			<div><strong><?php esc_html_e( 'Secure payment', 'booknest' ); ?></strong><span><?php esc_html_e( 'Stripe, PayPal & more', 'booknest' ); ?></span></div>
			<div><strong><?php esc_html_e( 'Lifetime access', 'booknest' ); ?></strong><span><?php esc_html_e( 'Re-download anytime', 'booknest' ); ?></span></div>
		</div>
	</section>

	<section id="books" class="landing-books">
		<div class="container">
			<header class="landing-section-head">
				<h2 class="landing-section-head__title"><?php esc_html_e( 'Choose your eBook', 'booknest' ); ?></h2>
				<p><?php esc_html_e( 'We keep our catalog small on purpose — only books worth your time.', 'booknest' ); ?></p>
			</header>

			<?php
			$product = $primary;
			$is_primary = true;
			include locate_template( 'template-parts/landing/product-showcase.php' );

			if ( $secondary ) :
				$product = $secondary;
				$is_primary = false;
				?>
				<p class="landing-books__divider"><?php esc_html_e( 'Also available', 'booknest' ); ?></p>
				<?php
				include locate_template( 'template-parts/landing/product-showcase.php' );
			endif;
			?>
		</div>
	</section>

	<section id="why" class="landing-why">
		<div class="container landing-why__grid">
			<div class="landing-why__copy">
				<h2 class="landing-section-head__title"><?php esc_html_e( 'Why readers choose digital', 'booknest' ); ?></h2>
				<ul class="landing-checklist">
					<li><?php esc_html_e( 'Carry thousands of pages in your pocket', 'booknest' ); ?></li>
					<li><?php esc_html_e( 'Adjust font size and theme for comfortable reading', 'booknest' ); ?></li>
					<li><?php esc_html_e( 'No shipping, no waiting — start in seconds', 'booknest' ); ?></li>
					<li><?php esc_html_e( 'Own your copy forever in your BookNest library', 'booknest' ); ?></li>
				</ul>
			</div>
			<div class="landing-why__card">
				<h3><?php esc_html_e( 'Works on your favorite apps', 'booknest' ); ?></h3>
				<p><?php esc_html_e( 'EPUB & PDF files open in Apple Books, Google Play Books, Kindle (where supported), and free readers on desktop.', 'booknest' ); ?></p>
			</div>
		</div>
	</section>

	<section class="landing-testimonials">
		<div class="container">
			<h2 class="landing-section-head__title"><?php esc_html_e( 'Readers are talking', 'booknest' ); ?></h2>
			<div class="landing-testimonials__grid">
				<blockquote class="landing-quote">
					<p><?php esc_html_e( '“Checkout took less than a minute and the download link was in my email right away. Exactly what I wanted.”', 'booknest' ); ?></p>
					<cite>— <?php esc_html_e( 'Verified buyer', 'booknest' ); ?></cite>
				</blockquote>
				<blockquote class="landing-quote">
					<p><?php esc_html_e( '“Beautiful formatting on my tablet. I’ve already recommended it to two friends.”', 'booknest' ); ?></p>
					<cite>— <?php esc_html_e( 'Verified buyer', 'booknest' ); ?></cite>
				</blockquote>
			</div>
		</div>
	</section>

	<section id="faq" class="landing-faq">
		<div class="container landing-faq__inner">
			<h2 class="landing-section-head__title"><?php esc_html_e( 'Frequently asked questions', 'booknest' ); ?></h2>
			<div class="landing-faq__list" data-landing-faq>
				<details class="landing-faq__item" open>
					<summary><?php esc_html_e( 'How do I get my eBook after purchase?', 'booknest' ); ?></summary>
					<p><?php esc_html_e( 'After payment you’ll receive a download link by email and in your account under My Library. Most orders are ready in under 60 seconds.', 'booknest' ); ?></p>
				</details>
				<details class="landing-faq__item">
					<summary><?php esc_html_e( 'Which file format should I use?', 'booknest' ); ?></summary>
					<p><?php esc_html_e( 'EPUB is best for phones and tablets. PDF is ideal if you want fixed layout or printing. Your purchase includes the formats listed on each book.', 'booknest' ); ?></p>
				</details>
				<details class="landing-faq__item">
					<summary><?php esc_html_e( 'Can I get a refund?', 'booknest' ); ?></summary>
					<p><?php esc_html_e( 'Because digital files are delivered instantly, refunds are handled case-by-case. Contact support if you have download issues — we’ll make it right.', 'booknest' ); ?></p>
				</details>
				<details class="landing-faq__item">
					<summary><?php esc_html_e( 'Do I need an account?', 'booknest' ); ?></summary>
					<p><?php esc_html_e( 'Guest checkout is available. Creating an account lets you re-download your books anytime from My Library.', 'booknest' ); ?></p>
				</details>
			</div>
		</div>
	</section>

	<section class="landing-final-cta">
		<div class="container landing-final-cta__inner">
			<h2><?php esc_html_e( 'Ready to start reading?', 'booknest' ); ?></h2>
			<p><?php esc_html_e( 'Join readers who picked up their copy today.', 'booknest' ); ?></p>
			<a class="btn btn--landing-primary btn--lg" href="<?php echo esc_url( booknest_product_buy_now_url( $primary ) ); ?>">
				<?php esc_html_e( 'Get', 'booknest' ); ?> <?php echo esc_html( $primary->get_name() ); ?>
			</a>
		</div>
	</section>

	<?php endif; ?>
</main>

<?php
get_footer( 'landing' );
