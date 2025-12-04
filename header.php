<?php

/**
 * The header for our theme
 *
 * This is the template that displays all of the <head> section and everything up until <div id="content">
 *
 * @package    WordPress
 * @subpackage Dntheme
 * @version 1.0
 */
$woocommerce_active = class_exists('WooCommerce');
if ($woocommerce_active) {
  global $woocommerce;
}
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>

<head>
  <meta charset="<?php bloginfo('charset'); ?>">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <?php wp_head(); ?>

  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
</head>

<body <?php body_class(); ?>>
  <div id="fb-root"></div>
  <?php wp_body_open(); ?>
  <div class="wrapper">
    <header class="header" <?php echo (is_front_page()) ? 'data-toggle="sticky-onscroll"' : '' ?>>
      <div class="container-fluid">
        <div class="sc__wrap d-flex justify-content-between align-items-center">
          <div class="header__brand">

            <?php
            $logo_img = get_field('logo', 'option');
            if (is_home()): ?>
              <h1 class="logo">
                <a href="<?php echo home_url(); ?>">
                  <?php echo wp_get_attachment_image($logo_img, 'full'); ?>
                </a>
              </h1>
            <?php else: ?>
              <p class="logo">
                <a href="<?php echo home_url(); ?>">
                  <?php echo wp_get_attachment_image($logo_img, 'full'); ?>
                </a>
              </p>
            <?php endif;
            ?>

          </div>
          <!--start main nav-->
          <nav class="main__nav d-flex align-items-center">
            <div class="header__search">
              <button class="header__search--toggle">
                <i class="iconz-search"></i>
              </button>
              <div class="header__search__form">

                <form id="header-search-form" role="search" method="get" class="search-form search__form" action="<?php echo esc_url(home_url()); ?>">
                  <input type="search" class="search-field form-control" placeholder="<?php echo esc_attr_x('Nhập từ khóa cần tìm &hellip;', 'placeholder', 'dntheme'); ?>" value="<?php echo get_search_query(); ?>" name="s" />

                  <button class="search-submit d-none" type="submit">Search</button>
                </form>
              </div>
            </div>
            <?php if ($woocommerce_active && function_exists('wc_get_cart_url')): 
              $cart_contents_count = isset($woocommerce->cart) && $woocommerce->cart ? $woocommerce->cart->cart_contents_count : 0;
            ?>
            <a href="<?php echo esc_url(wc_get_cart_url()); ?>" class="header__cart" title="<?php _e('View your shopping cart', 'woothemes'); ?>">
              <img src="<?= get_theme_file_uri('assets/img/box.png') ?>" alt="" width="24" height="24" class="ms-4 ms-xl-5">
              <span class="total-cart">

                <?php if ($cart_contents_count) {
                  echo '(' . $cart_contents_count . ')';
                } ?>
              </span>
            </a>
            <?php endif; ?>

            <?php if (1 == 2 && $woocommerce_active && function_exists('wc_get_cart_url')): ?>
              <div class="header-cart ms-4">
                <?php $cart_contents_count = isset($woocommerce->cart) && $woocommerce->cart ? $woocommerce->cart->cart_contents_count : 0; ?>
                <a href="<?php echo esc_url(wc_get_cart_url()); ?>" title="<?php _e('View your shopping cart', 'woothemes'); ?>">

                  <i class="iconbg-cart" aria-hidden="true"></i><span class="total-cart">

                    <?php if ($cart_contents_count) {
                      echo '(' . $cart_contents_count . ')';
                    } ?>
                  </span>

                </a>
              </div>
            <?php endif; ?>

            <a href="#menu__mobile" class="mburger ms-4 ms-xl-5">
              <i class="icon-menu"></i>
              <!-- <span><span class="icon-bar"></span><span class="icon-bar"></span><span class="icon-bar"></span></span> -->
            </a>

          </nav>
          <!--end main nav-->
        </div>
      </div>
    </header><!--end header-->



    <?php if (!is_front_page()): ?>
      <div class="dn__breadcrumb d-none" typeof="BreadcrumbList" vocab="https://schema.org/">
        <div class="container">
          <?php if (function_exists('bcn_display')) {
            bcn_display();
          } ?>
        </div>
      </div>

    <?php endif; ?>