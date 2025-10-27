<?php

/**
 * The Template for displaying all single products
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/single-product.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see 	    https://docs.woocommerce.com/document/template-structure/
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     1.6.4
 */

if (! defined('ABSPATH')) {
	exit; // Exit if accessed directly
}

get_header('shop'); ?>
<div class="nav-dieuhuong">
	<div class="container">
		<div class="d-md-flex align-items-center">
			<div class="nav-dieuhuong__title back-to-top"><?= $title_parent ?></div>
			<ul class="nav-list d-flex justify-content-md-end ms-md-auto">
				<?php single_list_terms_product(); ?>
			</ul>
		</div>
	</div>
</div>
<div class="container">
	<hr class="m-0">
</div>

<div class="nav-dieuhuong" data-toggle="sticky-onscroll">
	<div class="container">
		<div class="d-md-flex align-items-center">
			<h1 class="nav-dieuhuong__title back-to-top product_title entry-title"><?php the_title(); ?></h1>

			<ul class="nav-list d-flex justify-content-md-end ms-md-auto">
				<li>
					<a href="<?php echo esc_url(wc_get_cart_url()); ?>" class="header-total-cart" title="<?php _e('View your shopping cart', 'woothemes'); ?>">
						<img src="<?= get_theme_file_uri('assets/img/box.png') ?>" alt="" width="24" height="24" class="ms-xl-5 me-1">
						<span class="total-cart">
							<span class="total-cart-qty">(<?php echo $woocommerce->cart->cart_contents_count; ?>)</span>
							<span class="total-cart-price ms-1"><?php echo WC()->cart->get_cart_total(); ?></span>
						</span>
					</a>
				</li>
			</ul>
		</div>
	</div>
</div>
<div class="container">
	<hr class="mt-0">
</div>

<div class="page__content">
	<!-- <div class="container">
		<?php
		$get_color       = get_field('color');
		$get_sub       = get_field('sub');
		$get_sub2       = get_field('sub2');
		if ($get_color || $get_sub || $get_sub2):
		?>
			<div class="about-heading" style="background: <?= $get_color ?>">
				<h2 class="about-heading__title"><?php the_field('sub') ?></h2>
				<?php
				if ($get_sub2): ?>
					<div class="about-heading__sub h2"><?= $get_sub2 ?></div>
				<?php endif ?>
			</div>
		  <hr>
		<?php endif; ?>
	</div> -->

	<?php
	/**
	 * woocommerce_before_main_content hook.
	 *
	 * @hooked woocommerce_output_content_wrapper - 10 (outputs opening divs for the content)
	 * @hooked woocommerce_breadcrumb - 20
	 */
	do_action('woocommerce_before_main_content');
	?>

	<?php while (have_posts()) : the_post(); ?>

		<?php wc_get_template_part('content', 'single-product'); ?>

	<?php endwhile; // end of the loop. 
	?>
	<?php
	/**
	 * woocommerce_after_main_content hook.
	 *
	 * @hooked woocommerce_output_content_wrapper_end - 10 (outputs closing divs for the content)
	 */
	do_action('woocommerce_after_main_content');
	?>

</div>
<?php get_footer('shop');

/* Omit closing PHP tag at the end of PHP files to avoid "headers already sent" issues. */
