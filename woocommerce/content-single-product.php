<?php

/**
 * The template for displaying product content in the single-product.php template
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/content-single-product.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce/Templates
 * @version 3.6.0
 */

defined('ABSPATH') || exit;

global $product;
/**
 * Hook: woocommerce_before_single_product.
 *
 * @hooked wc_print_notices - 10
 */
do_action('woocommerce_before_single_product');

if (post_password_required()) {
  echo get_the_password_form(); // WPCS: XSS ok.
  return;
}
?>
<div id="product-<?php the_ID(); ?>" <?php wc_product_class('', $product); ?>>
  <div class="product__single--main">
    <div class="row">
      <div class="col-lg-6">
        <?php
        /**
         * woocommerce_before_single_product_summary hook.
         *
         * @hooked woocommerce_show_product_sale_flash - 10
         * @hooked woocommerce_show_product_images - 20
         */
        do_action('woocommerce_before_single_product_summary');
        ?>
      </div>
      <div class="col-lg-6">
        <div class="summary entry-summary">

          <?php
          /**
           * Hook: woocommerce_single_product_summary.
           *
           * @hooked woocommerce_template_single_title - 5
           * @hooked woocommerce_template_single_rating - 10
           * @hooked woocommerce_template_single_price - 10
           * @hooked woocommerce_template_single_excerpt - 20
           * @hooked woocommerce_template_single_add_to_cart - 30
           * @hooked woocommerce_template_single_meta - 40
           * @hooked woocommerce_template_single_sharing - 50
           * @hooked WC_Structured_Data::generate_product_data() - 60
           */
          do_action('woocommerce_single_product_summary');
          ?>

        </div><!-- .summary -->
      </div>
    </div>
  </div>


  <hr>
  <div>
    <header class="header__title">
      <div class="box__title">
        <h2 class="title__box"><span>Thông tin sản phẩm</span></h2>
      </div>
    </header>
    <div class="entry-content">
      <?php the_content() ?>
    </div>
  </div>
  <?php if(1 ===2 ): ?>
  <hr>
  <div>

    <?php
    if (have_rows('items')): $i = 0 ?>
      <header class="header__title">
        <div class="box__title">
          <h2 class="title__box"><span>Thông số kỹ thuật</span></h2>
        </div>
      </header>
      <ul class="about-list">
        <?php
        $i = 0;
        while (have_rows('items')) : the_row();
          $i++;
          $title = get_sub_field('title');
          $content = get_sub_field('content');
          $column = get_sub_field('column');
          $content2col = get_sub_field('content2col');
          $content1 = $content2col['content1'];
          $content2 = $content2col['content2'];

          $content3col = get_sub_field('content3col');
          $content3col_content1 = $content3col['content1'];
          $content3col_content2 = $content3col['content2'];
          $content3col_content3 = $content3col['content3'];
        ?>
          <li class="wow fadeInUp">
            <div class="row">
              <?php if ($column == 'half'):
              ?>
                <div class="col-md-6 mb-3 mb-md-0">
                  <div class="entry-content -half-style">
                    <?= $content1 ?>
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="entry-content -half-style">
                    <?= $content2 ?>
                  </div>
                </div>
              <?php elseif ($column == '1p3'):
              ?>

                <?php if ($title): ?>
                  <div class="col-md-12 mb-4">
                    <div class="about-list__title">
                      <?= $title ?>
                    </div>
                  </div>
                <?php endif; ?>

                <div class="col-md-4 mb-3 mb-md-0">
                  <div class="entry-content -half-style">
                    <?= $content3col_content1 ?>
                  </div>
                </div>
                <div class="col-md-4 mb-3 mb-md-0">
                  <div class="entry-content -half-style">
                    <?= $content3col_content2 ?>
                  </div>
                </div>
                <div class="col-md-4">
                  <div class="entry-content -half-style">
                    <?= $content3col_content3 ?>
                  </div>
                </div>
              <?php elseif ($column == 'full'): ?>
                <div class="col-md-12">
                  <div class="entry-content -full-style">
                    <?= $content ?>
                  </div>
                </div>
              <?php else: ?>
                <div class="col-md-3 col-lg-3 col-xl-3">
                  <div class="about-list__title">
                    <?= $title ?>
                  </div>
                </div>
                <div class="col-md-9 col-lg-9 col-xl-9">
                  <div class="about-list__content">
                    <div class="entry-content">
                      <?php if ($column == 1): ?>
                        <?= $content ?>
                      <?php else: ?>
                        <div class="row">
                          <div class="col-md-6 mb-3 mb-md-0">
                            <?= $content1 ?>
                          </div>
                          <div class="col-md-6">
                            <?= $content2 ?>
                          </div>
                        </div>
                      <?php endif ?>
                    </div>
                  </div>
                </div>
              <?php endif; ?>
            </div>
          </li>
        <?php endwhile; ?>

      </ul>
    <?php endif;
    ?>
  </div>
  <?php endif ?>
  <hr>
  <?php
  /**
   * Hook: woocommerce_after_single_product_summary.
   *
   * @hooked woocommerce_output_product_data_tabs - 10
   * @hooked woocommerce_upsell_display - 15
   * @hooked woocommerce_output_related_products - 20
   */
  do_action('woocommerce_after_single_product_summary');
  ?>

</div>

<?php do_action('woocommerce_after_single_product'); ?>