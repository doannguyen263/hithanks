<?php

/**
 * Template Name: Page Main: V1
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
get_header(); ?>
<?php while (have_posts()) : the_post();
    $headerTitle = get_field('main_title');
?>
    <div class="nav-dieuhuong" data-toggle="sticky-onscroll">
        <div class="container">
            <div class="d-md-flex align-items-center">
                <div class="nav-dieuhuong__title back-to-top"><?= $headerTitle ?></div>
                <?php
                if (have_rows('main_links')):
                    $items = [];
                    $foundActive = false;
                    while (have_rows('main_links')) : the_row();
                        $title = get_sub_field('title');
                        $link = get_sub_field('link');
                        $classActive = get_the_title() === $title ? 'current_page_item' : '';
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
                <?php endif; ?>
            </div>
        </div>
    </div>
    <div class="container">
        <hr class="mt-0">
    </div>
    <?php
    $main_banner_text_color = get_field('main_banner_text_color');
    $bannerColor1 = get_field('main_banner_color1');
    $bannerColor2 = get_field('main_banner_color2');
    $mainBannerTitle = get_field('main_banner_title');
    $mainBannerSub = get_field('main_banner_sub');
    ?>
    <div class="page__content">
        <div class="container">
            <div class="about-heading d-none d-xl-block" style="background: linear-gradient(45deg, <?= $bannerColor1 ?>,<?= $bannerColor2 ?>); --title-color: <?= $main_banner_text_color ?>">
                <div class="d-flex justify-content-between">
                    <h1 class="about-heading__title"><?= $mainBannerTitle ?></h1>
                </div>
                <div class="row">
                    <div class="col-md-3">
                        <?php if ($mainBannerSub): ?>
                            <div class="about-heading__sub"><?= $mainBannerSub ?></div>
                        <?php endif ?>
                    </div>
                    <div class="col-md-9">
                        <?php
                        if (have_rows('main_banner_links')): ?>
                            <ul class="aboutv2__list">
                                <?php
                                while (have_rows('main_banner_links')) : the_row();
                                    $title = get_sub_field('title');
                                    $link = get_sub_field('link');
                                    $classActive = get_the_title() === $title ? 'current_page_item' : '';
                                ?>
                                    <li class="<?= $classActive ?>"><a href="<?= $link ?>"><?= $title ?></a></li>
                                <?php
                                endwhile; ?>
                            </ul>
                        <?php endif;?>
                    </div>
                </div>
            </div>
            <hr class="mb-0 d-none d-xl-block">

            <?php
            if (have_rows('items')):  ?>
                <div class="home-nav pt-0 pt-xl-2 pb-3">
                    <?php while (have_rows('items')) : the_row();
                        $get_title       = get_sub_field('title');
                        $get_sub         = get_sub_field('sub');
                        $get_excerpt         = get_sub_field('excerpt');
                        $get_link        = get_sub_field('link');
                        $get_color       = get_sub_field('color');
                        $style           = get_sub_field('style');
                        $content         = get_sub_field('content');
                        $bannerSlider = get_sub_field('bannerSlider');
                    ?>
                        <div class="home-nav__item <?php echo ($bannerSlider) ? 'has-slider' : '' ?> <?= $style ?>" style="--title-color:<?= $get_color ?>">
                            <div class="card rounded-0 border-0 mb-2 text-white <?= ($style == 'style3') ? '-link' : "" ?> wow fadeInUp" >
                                <?php if ($bannerSlider): ?>
                                    <div class="main-carousel">
                                        <?php
                                        foreach ($bannerSlider as $value) {
                                            $imageID = $value['image'];
                                            $imageIDPC = $value['image_pc'];
                                        ?>
                                            <div class="carousel-cell">
                                                <div class="carousel-cell__thumb">
                                                    <picture>
                                                        <source srcset="<?php echo wp_get_attachment_image_url($imageIDPC, 'full'); ?>" media="(min-width: 1200px)">
                                                        <img src="<?php echo wp_get_attachment_image_url($imageID, 'full'); ?>" alt="<?php echo get_the_title(); ?>">
                                                    </picture>
                                                </div>
                                            </div>
                                        <?php
                                        }
                                        ?>
                                    </div>
                                <?php endif; ?>

                                <div class="card-body">
                                    <h2 class="card-title text-white text-uppercase mb-0"><a href="<?= $get_link ?>" class="text-white stretched-link"><?= $get_title ?></a>
                                    </h2>
                                    <p class="mt-1 mb-0"><?= $get_sub ?></p>

                                    <div class="card__excerpt mt-1 mb-0"><?= $get_excerpt ?></div>

                                    <div class="card__readmore">
                                        <a href="<?= $get_link ?>" class="btn__readlink">Xem thêm</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endwhile; ?>
                </div>
            <?php endif; ?>
        </div>

    </div>
<?php endwhile; ?>
<?php get_footer();
