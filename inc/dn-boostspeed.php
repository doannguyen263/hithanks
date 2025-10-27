<?php

/**
 * Tối ưu contact form
 * @author Đoàn Nguyễn
 */
function dn_load_cf7($arr){
  if ( is_page_template( 'page-contact.php' ) ) {
      return $arr;
  } else {
      return false;
  }
}

// add_filter( 'wpcf7_load_js', 'dn_load_cf7' );
// add_filter( 'wpcf7_load_css', 'dn_load_cf7' );

/**
 * Remove WP Embed Script
 * @author Đoàn Nguyễn
 */
function stop_loading_wp_embed() {
  if (!is_admin()) {
    wp_deregister_script('wp-embed');
  }
}
add_action('init', 'stop_loading_wp_embed');


// Remove the REST API endpoint.
remove_action( 'rest_api_init', 'wp_oembed_register_route' );
// Turn off oEmbed auto discovery.
add_filter( 'embed_oembed_discover', '__return_false' );
// Don't filter oEmbed results.
remove_filter( 'oembed_dataparse', 'wp_filter_oembed_result', 10 );
// Remove oEmbed discovery links.
remove_action( 'wp_head', 'wp_oembed_add_discovery_links' );
// Remove oEmbed-specific JavaScript from the front-end and back-end.
remove_action( 'wp_head', 'wp_oembed_add_host_js' );
//Remove WordPress Version Number
remove_action('wp_head', 'wp_generator');
/**
 * Remove emoji
 * @author Đoàn Nguyễn
 */
remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
remove_action( 'wp_print_styles', 'print_emoji_styles' );

/**
 * Remove Thumbnails default
 * @author Đoàn Nguyễn
 */
remove_image_size('1536x1536');
remove_image_size('2048x2048');
function dntheme_remove_default_image_sizes( $sizes) {
    // unset( $sizes['thumbnail']);
    // unset( $sizes['medium']);
    // unset( $sizes['large']);

    return $sizes;
}
// add_filter('intermediate_image_sizes_advanced', 'dntheme_remove_default_image_sizes');

//Remove Gutenberg Block Library CSS from loading on the frontend
function smartwp_remove_wp_block_library_css(){
    wp_dequeue_style( 'classic-theme-styles' ); // Remove WooCommerce block CSS
}
add_action( 'wp_enqueue_scripts', 'smartwp_remove_wp_block_library_css', 100 );


function disable_wp_blocks() {
    $wstyles = array(
        'wp-block-library',
        'wc-blocks-style',
        'wc-blocks-style-active-filters',
        'wc-blocks-style-add-to-cart-form',
        'wc-blocks-packages-style',
        'wc-blocks-style-all-products',
        'wc-blocks-style-all-reviews',
        'wc-blocks-style-attribute-filter',
        'wc-blocks-style-breadcrumbs',
        'wc-blocks-style-catalog-sorting',
        'wc-blocks-style-customer-account',
        'wc-blocks-style-featured-category',
        'wc-blocks-style-featured-product',
        'wc-blocks-style-mini-cart',
        'wc-blocks-style-price-filter',
        'wc-blocks-style-product-add-to-cart',
        'wc-blocks-style-product-button',
        'wc-blocks-style-product-categories',
        'wc-blocks-style-product-image',
        'wc-blocks-style-product-image-gallery',
        'wc-blocks-style-product-query',
        'wc-blocks-style-product-results-count',
        'wc-blocks-style-product-reviews',
        'wc-blocks-style-product-sale-badge',
        'wc-blocks-style-product-search',
        'wc-blocks-style-product-sku',
        'wc-blocks-style-product-stock-indicator',
        'wc-blocks-style-product-summary',
        'wc-blocks-style-product-title',
        'wc-blocks-style-rating-filter',
        'wc-blocks-style-reviews-by-category',
        'wc-blocks-style-reviews-by-product',
        'wc-blocks-style-product-details',
        'wc-blocks-style-single-product',
        'wc-blocks-style-stock-filter',
        'wc-blocks-style-cart',
        'wc-blocks-style-checkout',
        'wc-blocks-style-mini-cart-contents',
    );

    foreach ( $wstyles as $wstyle ) {
        wp_deregister_style( $wstyle );
    }

    $wscripts = array(
        'wc-blocks-middleware',
        'wc-blocks-data-store'
    );

    foreach ( $wscripts as $wscript ) {
        wp_deregister_script( $wscript );
    }
}

add_action( 'init', 'disable_wp_blocks', 100 );

// Remove unwanted SVG filter injection WP
remove_action( 'wp_enqueue_scripts', 'wp_enqueue_global_styles' );
remove_action( 'wp_body_open', 'wp_global_styles_render_svg_filters' );




// add_action('widgets_init', 'wptangtoc_tat_woocommerce_widgets', 99);
// function wptangtoc_tat_woocommerce_widgets() {
//   unregister_widget('WC_Widget_Products');
//   unregister_widget('WC_Widget_Product_Categories');
//   unregister_widget('WC_Widget_Product_Tag_Cloud');
//   unregister_widget('WC_Widget_Cart');
//   unregister_widget('WC_Widget_Layered_Nav');
//   unregister_widget('WC_Widget_Layered_Nav_Filters');
//   unregister_widget('WC_Widget_Price_Filter');
//   unregister_widget('WC_Widget_Product_Search');
//   unregister_widget('WC_Widget_Recently_Viewed');
//   unregister_widget('WC_Widget_Recent_Reviews');
//   unregister_widget('WC_Widget_Top_Rated_Products');
//   unregister_widget('WC_Widget_Rating_Filter');
// }
