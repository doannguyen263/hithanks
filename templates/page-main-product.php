<?php

/**
 * Template Name: Page Main: Product
 *
 * This is the template that displays all pages by default.
 * Please note that this is the WordPress construct of pages
 * and that other 'pages' on your WordPress site may use a
 * different template.
 *
 * @package    WordPress
 * @subpackage Dntheme
 * @version 1.0
 */
get_header();
while (have_posts()) : the_post();
    $get_the_ID = get_the_ID();
    if ($post->post_parent) {
        $title_parent = get_the_title($post->post_parent);

        $fix_title = get_field('title', $post->post_parent);
        if ($fix_title) {
            $title_parent = $fix_title;
        }
    } else {
        $title_parent = get_the_title();

        $fix_title = get_field('title', $post->post_parent);
        if ($fix_title) {
            $title_parent = $fix_title;
        }
    }
?>
    <div class="nav-dieuhuong" data-toggle="sticky-onscroll">
        <div class="container">
            <div class="d-md-flex align-items-center">
                <div class="nav-dieuhuong__title back-to-top"><?= $title_parent ?></div>
                <?php
                if (have_rows('main_links')):
                    $items = [];
                    $foundActive = false;
                    while (have_rows('main_links')) : the_row();
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
                        <?= rt_list_child_pagesv2('Danh mục sản phẩm'); ?>
                    </ul>
                <?php endif; ?>
            </div>
        </div>
    </div>
    <div class="container">
        <hr class="mt-0">
    </div>

    <div class="page__content pb-5">
        <div class="container">
            <?php $get_color       = get_field('color'); ?>
            <div class="about-heading" style="background: <?= $get_color ?>">
                <div class="d-flex justify-content-between">
                    <h1 class="about-heading__title"><?= get_field('sub') ?></h1>

                </div>
                <?php
                if (have_rows('sub_menu_list')): ?>
                    <div class="row">
                        <div class="col-md-3">
                            <?php
                            $get_field_sub2 = get_field('sub2');
                            if ($get_field_sub2): ?>
                                <div class="about-heading__sub"><?= $get_field_sub2 ?></div>
                            <?php endif ?>

                        </div>
                        <div class="col-md-9">
                            <div class="row">
                                <div class="col-md-8">
                                    <ul class="aboutv2__list">
                                        <?php
                                        while (have_rows('sub_menu_list')) : the_row();
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
                                <div class="col-md-4">
                                    <a href="<?= site_url('/san-pham') ?>" class="about__readmore">Tất cả các sản phẩm</a>
                                </div>
                            </div>

                        </div>
                    </div>
                <?php endif; ?>

                <!-- <div class="row">
                    
                    <div class="col-md-3">
                        <?php
                        $get_field_sub2 = get_field('sub2');
                        if ($get_field_sub2): ?>
                            <div class="about-heading__sub"><?= $get_field_sub2 ?></div>
                        <?php endif ?>
                    </div>
                    <div class="col-md-9">
                        <div class="row">
                            <div class="col-md-8">
     
                            </div>
                            <div class="col-md-4">
                                <a href="<?= site_url('/san-pham') ?>" class="about__readmore">Tất cả các sản phẩm</a>
                            </div>
                        </div>

                    </div>
                </div> -->
            </div>
            <hr class="mb-0">

            <?php
            if (have_rows('items')): $i = 0 ?>

                <ul class="about-list">
                    <?php
                    while (have_rows('items')) : the_row();
                        $i++;
                        $title = get_sub_field('title');
                        $title_color = get_sub_field('color_title');
                        $subtitle = get_sub_field('subtitle');
                        $content = get_sub_field('content');
                        $background_start = get_sub_field('background_start');
                        $background_end = get_sub_field('background_end');

                        $product_cat_id = get_sub_field('product_tax');
                        $link = get_sub_field('link');
                    ?>
                        <li class="wow fadeInUp my-2" style="background: linear-gradient(<?= $background_start ?>, <?= $background_end ?>);--title-color:<?= $title_color ?>">
                            <div class="row col--right">
                                <!-- <a href="<?= $link ?>" class="stretched-link">Bấm vào để xem toàn bộ sản phẩm</a> -->
                                <div class="col-md-12 mb-lg-3">
                                    <div class="d-lg-flex justify-content-between gap-3">
                                        <div class="flex-shrink-1">
                                            <div class="about-list__title">
                                                <a href="<?= $link ?>" class="stretched-link"><?= $title ?></a>
                                            </div>
                                            <div class="about-list__subtitle">
                                                <?= $subtitle ?>
                                            </div>
                                        </div>
                                        <div>

                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-12">
                                    <div class="about-list__content">
                                        <div class="entry-content">
                                            <?= $content ?>
                                        </div>
                                    </div>
                                </div>

                                <?php if ($product_cat_id): ?>
                                    <div class="col-12 mt-3 position-relative product-made-carousel-col">
                                        <div class="product-made-carousel">
                                            <?php
                                            // Query sản phẩm từ danh mục được chọn
                                            $args = array(
                                                'post_type' => 'product',
                                                'posts_per_page' => 6,
                                                'tax_query' => array(
                                                    array(
                                                        'taxonomy' => 'product_cat',
                                                        'field' => 'term_id',
                                                        'terms' => $product_cat_id,
                                                    ),
                                                ),
                                            );
                                            $products_query = new WP_Query($args);
                                            
                                            if ($products_query->have_posts()) :
                                                while ($products_query->have_posts()) : $products_query->the_post();
                                                    $product = wc_get_product(get_the_ID());
                                                    $image_id = $product->get_image_id();
                                                    $title = get_the_title();
                                                    $price = $product->get_price_html();
                                                    $link = get_permalink();
                                            ?>
                                                <div class="carousel-cell col-12 col-md-6 me-3">
                                                    <div class="position-relative">
                                                        <div class="carousel-cell__thumb">
                                                            <div class="ratio ratio-1x1">
                                                                <?php echo wp_get_attachment_image($image_id, 'large'); ?>
                                                            </div>
                                                        </div>
                                                        <div class="carousel-cell__content">
                                                            <div class="carousel-cell__title"><a href="<?= $link ?>" class="stretched-link"><?= $title ?></a></div>
                                                            <div class="carousel-cell__price"><?= $price ?></div>
                                                        </div>
                                                    </div>
                                                </div>
                                            <?php
                                                endwhile;
                                                wp_reset_postdata();
                                            endif;
                                            ?>
                                        </div>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </li>
                    <?php endwhile; ?>

                    <?php
                    $get_the_content = get_the_content();
                    ?>
                    <?php if ($get_the_content): ?>
                        <li class="wow fadeInUp">
                            <div class="entry-content">
                                <?php the_content() ?>
                            </div>
                        </li>
                    <?php endif; ?>

                </ul>
            <?php else :
                get_template_part('template-parts/content', 'none');
            endif;
            ?>

            <?php
            if (have_rows('menu_link')):  ?>
                <ul class="page-list">
                    <?php while (have_rows('menu_link')) : the_row();
                        $get_title = get_sub_field('title');
                        $get_link = get_sub_field('link');
                    ?>
                        <li><a href="<?= $get_link ?>"><?= $get_title ?></a></li>
                    <?php endwhile; ?>
                </ul>
            <?php endif; ?>
        </div>
    </div>

<?php endwhile; ?>
<?php get_footer();
