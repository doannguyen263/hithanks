<?php
/**
 * The template for displaying product content within loops
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/content-product.php.
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

defined( 'ABSPATH' ) || exit;

global $product;

// $product->get_featured()

// Ensure visibility.
if ( empty( $product ) || ! $product->is_visible() ) {
  return;
}
?>
<div class="product__item ef__zoomin">
  <a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>">
  <div class="item__thumb">
    <div class="dnfix__thumb" title="<?php the_title(); ?>">
      <?php the_post_thumbnail('large', array( 'class' => 'img-fluid mx-auto' ) ); ?>
    </div>
  </div>
  <div class="item__meta">
      <h4 class="item__title text__truncate -n2"><?php the_title(); ?></h4>
      <div class="item__price d-flex flex-wrap align-items-center">
      <?php echo $product->get_price_html(); ?>
        <?php
        if ( $product->is_on_sale() ) :
            if ( ! $product->is_in_stock() ) return;
            $regular_price = $product->get_regular_price();
            $sale_price = $product->get_sale_price();
            if (empty($regular_price) && $product->is_type( 'variable' )){
                $available_variations = $product->get_available_variations();
                $variation_id = $available_variations[0]['variation_id'];
                $variation = new WC_Product_Variation( $variation_id );
                $regular_price = $variation ->get_regular_price();
                $sale_price = $variation ->get_sale_price();
            }
            if($regular_price>0){$sale = ceil(( ($regular_price - $sale_price) / $regular_price ) * 100);}
            if ( !empty( $regular_price ) && !empty( $sale_price ) && $regular_price > $sale_price ) :
                echo '<div class="percent"><p>'.$sale.'%</p><p class="percent__text d-none">Sale</p></div>';
            endif;
        endif;
      ?>
      </div>
  </div>
  </a>
</div>