<?php

/**
 * The Template for displaying product archives, including the main shop page which is a post type archive
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/archive-product.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce/Templates
 * @version 3.4.0
 */

defined('ABSPATH') || exit;

$term = get_queried_object();
$term_id = $term->term_id;

get_header('shop');

/**
 * Hook: woocommerce_before_main_content.
 *
 * @hooked woocommerce_output_content_wrapper - 10 (outputs opening divs for the content)
 * @hooked woocommerce_breadcrumb - 20
 * @hooked WC_Structured_Data::generate_website_data() - 30
 */
do_action('woocommerce_before_main_content');
$page_id = wc_get_page_id('shop');

// Kiểm tra nếu đang ở main product page thì lấy page ID
$main_product_page_id = null;

if (is_shop()) {
	// Đang ở trang shop chính
	$main_product_page_id = wc_get_page_id('shop');
	$current_page_id = get_queried_object_id();

	// Có thể sử dụng $main_product_page_id cho logic khác
	// Ví dụ: hiển thị shortcode urn comparison chỉ ở trang chính
	if ($main_product_page_id && $main_product_page_id == $current_page_id) {
		// Đây là trang shop chính, có thể thêm logic đặc biệt ở đây
		$is_main_product_page = true;
	}
} elseif (is_product_category()) {
	// Đang ở trang category
	$current_page_id = get_queried_object_id();
	$category_id = $current_page_id;

	// Có thể sử dụng $category_id cho logic khác
}
$termchildren = get_term_children($term_id, 'product_cat');

$subpage_title = get_field('subpage_title');
?>

<div class="nav-dieuhuong" data-toggle="sticky-onscroll">
	<div class="nav-dieuhuong__container d-md-flex align-items-center">
		<div class="nav-dieuhuong__title back-to-top">
			<?php if (!is_search()): ?>
				<h1 class="nav-dieuhuong__title back-to-top">
					<?php woocommerce_page_title(); ?>
				</h1>
			<?php else: ?>
				<?= $subpage_title ?? $title_parent ?>

			<?php endif; ?>
		</div>
		<?php
		if (have_rows('main_links', $main_product_page_id)):
			$items = [];
			$foundActive = false;
			while (have_rows('main_links', $main_product_page_id)) : the_row();
				$title = get_sub_field('title');
				$link = get_sub_field('link');
				$classActive = (untrailingslashit(get_permalink()) === untrailingslashit($link)) ? 'current_page_item' : '';
				if ($classActive) $foundActive = true;
				$items[] = [
					'title' => $title,
					'link' => $link,
					'class' => $classActive
				];
			endwhile;
			// Nếu không có classActive nào thì gán cho phần tử đầu tiên
			if (!$foundActive && count($items) > 0) {
				$items[0]['class'] = 'current_page_item';
			}
		?>
			<ul class="nav-list d-flex justify-content-md-end ms-md-auto">
				<?php foreach ($items as $item): ?>
					<li class="<?= $item['class'] ?>"><a href="<?= $item['link'] ?>"><?= $item['title'] ?></a></li>
				<?php endforeach; ?>
			</ul>
		<?php else: ?>
			<ul class="nav-list d-flex justify-content-md-end ms-md-auto">
				<?php
				$args = array(
					'taxonomy' => 'product_cat',
					'hide_empty' => false,
					'parent' => 0
				);
				$terms_query = get_terms($args);
				if ($terms_query && !is_wp_error($terms_query)) : ?>
					<?php foreach ($terms_query as $term_query) {
						$class_active = ($term_id == $term_query->term_id) ? 'active' : '';
					?>
						<li class="page_item"><a href="<?php echo get_term_link($term_query); ?>" class="<?= $class_active ?>">
								<?php echo $term_query->name; ?></a></li>
					<?php } ?>
				<?php
				endif;
				?>
			</ul>
		<?php endif; ?>
	</div>
</div>
<hr class="mt-0">

<div class="page__content">

	<?php
	$get_color       = get_field('color', $term);
	$sub       = get_field('sub', $term);
	$sub = ($sub) ? $sub : $term->name;
	$get_field_sub2 = get_field('sub2', $term);

	if (is_shop()) {
		$sub = get_field('sub', $main_product_page_id);
		$get_field_sub2 = get_field('sub2', $main_product_page_id);
	}
	?>
	<div class="about-heading d-none d-xl-block" style="background: <?= $get_color ?>">
		<h1 class="about-heading__title"><?= $sub ?></h1>
		<?php
		if ($get_field_sub2 && !is_shop()): ?>
			<div class="about-heading__sub h2"><?= $get_field_sub2 ?></div>
		<?php endif ?>


		<?php
		if (have_rows('sub_menu_list', $main_product_page_id)): ?>
			<div class="row">
				<div class="col-md-3">
					<?php
					$get_field_sub2 = get_field('sub2', $main_product_page_id);
					if ($get_field_sub2): ?>
						<div class="about-heading__sub"><?= $get_field_sub2 ?></div>
					<?php endif ?>
				</div>
				<div class="col-md-9">

					<ul class="aboutv2__list">
						<?php
						while (have_rows('sub_menu_list', $main_product_page_id)) : the_row();
							$i++;
							$title = get_sub_field('title');
							$link = get_sub_field('link');
						?>
							<li class="wow fadeInUp">
								<a href="<?= $link ?>"><?= $title ?></a>
							</li>
						<?php endwhile; ?>
					</ul>

				</div>
			</div>
		<?php else: ?>
			<?php
			$get_field_sub2 = get_field('sub2');
			if ($get_field_sub2): ?>
				<div class="about-heading__sub h2"><?= $get_field_sub2 ?></div>
			<?php endif ?>
		<?php
		endif;
		?>
	</div>
	<hr class="d-none d-xl-block">

	<div class="archive__content">


		<div class="row">
			<div class="col-lg-3">
				<aside class="widget-area widget__left widget__fix">
					<div class="sidebar__inner">
						<?php dynamic_sidebar('product'); ?>
					</div>
				</aside><!-- #secondary -->

			</div>
			<div class="col-lg-9">
				<form role="search" method="get" class="woocommerce-product-search mb-4" action="<?php echo esc_url(home_url('/')); ?>">
					<label class="screen-reader-text" for="woocommerce-product-search-field"><?php _e('Tìm kiếm sản phẩm:', 'woocommerce'); ?></label>
					<input type="search" id="woocommerce-product-search-field" class="search-field" placeholder="<?php echo esc_attr__('Tìm kiếm sản phẩm…', 'woocommerce'); ?>" value="<?php echo get_search_query(); ?>" name="s" />
					<button type="submit" value="<?php echo esc_attr_x('Tìm kiếm', 'submit button', 'woocommerce'); ?>"><i class="fa fa-search"></i></button>
					<input type="hidden" name="post_type" value="product" />
				</form>
				<?php
				if (is_shop()) {

					if (is_search()) {
						if (woocommerce_product_loop()) {
							woocommerce_product_loop_start();

							if (wc_get_loop_prop('total')) {
								while (have_posts()) {
									the_post();

									/**
									 * Hook: woocommerce_shop_loop.
									 *
									 * @hooked WC_Structured_Data::generate_product_data() - 10
									 */
									do_action('woocommerce_shop_loop');

									echo '<div class="col-md-4 col-6">';
									wc_get_template_part('content', 'product');
									echo '</div>';
								}
							}

							woocommerce_product_loop_end();
							/**
							 * Hook: woocommerce_after_shop_loop.
							 *
							 * @hooked woocommerce_pagination - 10
							 */
							do_action('woocommerce_after_shop_loop');
						} else {
							/**
							 * Hook: woocommerce_no_products_found.
							 *
							 * @hooked wc_no_products_found - 10
							 */
							do_action('woocommerce_no_products_found');
						}
					} else {
						$args = array(
							'taxonomy' => 'product_cat',
							'hide_empty' => false,
							'parent' => 0
						);
						$terms_query = get_terms($args);
						if ($terms_query && !is_wp_error($terms_query)) : ?>
							<?php foreach ($terms_query as $term_query) {
							?>
								<section class="shop-tax">
									<div class="shop-tax__header">
										<div class="shop-tax__title"><a href="<?php echo get_term_link($term_query); ?>">
												<?php echo $term_query->name; ?></a></div>
									</div>
									<?php
									$args = array(
										'post_type' => 'product',
										'posts_per_page' => 3,
										'tax_query' => array(
											array(
												'taxonomy' => 'product_cat',
												'field' => 'term_id',
												'terms' => array($term_query->term_id),
											),
										),
									);

									$query = new WP_Query($args);
									if ($query->have_posts()):
									?>
										<div class="shop-tax__content">
											<div class="row">
												<?php while ($query->have_posts()) : $query->the_post();
													global $post; ?>
													<?php
													echo '<div class="col-md-4 col-6">';
													wc_get_template_part('content', 'product');
													echo '</div>';
													?>
												<?php endwhile;
												wp_reset_postdata(); ?>
											</div>
										</div>
									<?php
									else :
										get_template_part('template-parts/content', 'none');
									endif; ?>
								</section>
							<?php }
						endif;
					}
				} else {

					if ($termchildren) {
						$args = array(
							'taxonomy' => 'product_cat',
							'hide_empty' => false,
							'include' => $termchildren
						);
						$terms_query = get_terms($args);
						if ($terms_query && !is_wp_error($terms_query)) : ?>
							<?php foreach ($terms_query as $term_query) {
							?>
								<section class="shop-tax">
									<div class="shop-tax__header">
										<div class="shop-tax__title"><a href="<?php echo get_term_link($term_query); ?>">
												<?php echo $term_query->name; ?></a></div>
										<?php if ($term_query->description): ?>
											<div class="shop-tax__desc">
												<?php echo $term_query->description; ?></div>
										<?php endif; ?>
									</div>
									<?php
									$args = array(
										'post_type' => 'product',
										'posts_per_page' => 3,
										'tax_query' => array(
											array(
												'taxonomy' => 'product_cat',
												'field' => 'term_id',
												'terms' => array($term_query->term_id),
											),
										),
									);

									$query = new WP_Query($args);
									if ($query->have_posts()):
									?>
										<div class="shop-tax__content">
											<div class="row">
												<?php while ($query->have_posts()) : $query->the_post();
													global $post; ?>
													<?php
													echo '<div class="col-md-4 col-6">';
													wc_get_template_part('content', 'product');
													echo '</div>';
													?>
												<?php endwhile;
												wp_reset_postdata(); ?>
											</div>
										</div>
									<?php
									else :
										get_template_part('template-parts/content', 'none');
									endif; ?>
								</section>
				<?php }
						endif;
					} else {
						if (woocommerce_product_loop()) {
							echo '<div class="wrap__archive--product mb-4 d-sm-flex  justify-content-between">';
							/**
							 * Hook: woocommerce_before_shop_loop.
							 *
							 * @hooked wc_print_notices - 10
							 * @hooked woocommerce_result_count - 20
							 * @hooked woocommerce_catalog_ordering - 30
							 */
							do_action('woocommerce_before_shop_loop');
							echo '</div>';

							woocommerce_product_loop_start();

							if (wc_get_loop_prop('total')) {
								while (have_posts()) {
									the_post();

									/**
									 * Hook: woocommerce_shop_loop.
									 *
									 * @hooked WC_Structured_Data::generate_product_data() - 10
									 */
									do_action('woocommerce_shop_loop');

									echo '<div class="col-md-4 col-6">';
									wc_get_template_part('content', 'product');
									echo '</div>';
								}
							}

							woocommerce_product_loop_end();
							/**
							 * Hook: woocommerce_after_shop_loop.
							 *
							 * @hooked woocommerce_pagination - 10
							 */
							do_action('woocommerce_after_shop_loop');
						} else {
							/**
							 * Hook: woocommerce_no_products_found.
							 *
							 * @hooked wc_no_products_found - 10
							 */
							do_action('woocommerce_no_products_found');
						}
					}
				}
				?>
			</div>
		</div>
	</div>
</div>
<?php
/**
 * Hook: woocommerce_after_main_content.
 *
 * @hooked woocommerce_output_content_wrapper_end - 10 (outputs closing divs for the content)
 */
do_action('woocommerce_after_main_content');

get_footer('shop');
