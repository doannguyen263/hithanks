<?php

global $post, $product, $woocommerce;

$attachment_ids = $product->get_gallery_image_ids();
$thumb_count = count($attachment_ids)+1;

// Disable thumbnails if there is only one extra image.
if($thumb_count == 1) return;

$rtl = 'false';
$thumb_cell_align = 'left';

if(is_rtl()) {
  $rtl = 'true';
  $thumb_cell_align = 'right';
}

if ( $attachment_ids ) {
  $loop     = 0;
  $columns  = apply_filters( 'woocommerce_product_thumbnails_columns', 3 );

  $gallery_class = array('product-thumbnails','thumbnails');

  if($thumb_count <= 5){
    $gallery_class[] = 'slider-no-arrows';
  }

  $gallery_class[] = 'slider';
  ?>
  <div class="<?php echo implode(' ', $gallery_class); ?>"
    data-flickity='{
              "cellAlign": "<?php echo $thumb_cell_align;?>",
              "wrapAround": false,
              "autoPlay": false,
              "prevNextButtons": true,
              "asNavFor": ".product-gallery-slider",
              "percentPosition": true,
              "imagesLoaded": true,
              "pageDots": false,
              "rightToLeft": <?php echo $rtl; ?>,
              "contain": true
          }'
    ><?php


    if ( has_post_thumbnail() ) : ?>
    <?php
      $image_size = 'thumbnail';

      $gallery_thumbnail = wc_get_image_size( $image_size ); ?>
      <div class="col-3 product__column is-nav-selected first">
        <a>
          <?php
            $image_id = get_post_thumbnail_id($post->ID);
            $image =  wp_get_attachment_image_src( $image_id, 'large' );
            $image = '<img src="'.$image[0].'" width="'.$gallery_thumbnail['width'].'" height="'.$gallery_thumbnail['height'].'" class="attachment-woocommerce_thumbnail" />';
            echo $image;
          ?>
        </a>
      </div>
    <?php endif;

    foreach ( $attachment_ids as $attachment_id ) {

      $classes = array( '' );
      $image_class = esc_attr( implode( ' ', $classes ) );
      $image =  wp_get_attachment_image_src( $attachment_id, apply_filters( 'single_product_small_thumbnail_size', 'medium'));
      $image = '<img src="'.$image[0].'" width="'.$gallery_thumbnail['width'].'" height="'.$gallery_thumbnail['height'].'"  class="attachment-woocommerce_thumbnail" />';

      echo apply_filters( 'woocommerce_single_product_image_thumbnail_html', sprintf( '<div class="col-3 product__column"><a>%s</a></div>', $image ), $attachment_id, $post->ID, $image_class );

      $loop++;
    }
  ?>
  </div><!-- .product-thumbnails -->
  <?php
} ?>
