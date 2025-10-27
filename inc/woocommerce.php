<?php
add_action( 'after_setup_theme', 'woocommerce_support' );
function woocommerce_support() {
    add_theme_support( 'woocommerce' );
}
add_filter( 'woocommerce_enqueue_styles', 'ken_dequeue_styles' );
function ken_dequeue_styles( $enqueue_styles ) {
  unset( $enqueue_styles['woocommerce-general'] );
  unset( $enqueue_styles['woocommerce-layout'] );
  unset( $enqueue_styles['woocommerce-smallscreen'] );
  return $enqueue_styles;
}
// add_filter( 'woocommerce_enqueue_styles', '__return_false' );

add_action( 'wp_enqueue_scripts', 'crunchify_disable_woocommerce_loading_css_js' );
function crunchify_disable_woocommerce_loading_css_js() {
  // Check if WooCommerce plugin is active
  if( function_exists( 'is_woocommerce' ) ){

    // Check if it's any of WooCommerce page
    if(! is_woocommerce() && ! is_cart() && ! is_checkout()&& !is_home() && !is_account_page() ) {

      ## Dequeue WooCommerce styles

      wp_dequeue_style('woocommerce-layout');
      wp_dequeue_style('woocommerce-general');
      wp_dequeue_style('woocommerce-smallscreen');

      ## Dequeue WooCommerce scripts
      wp_dequeue_script('wc-cart-fragments');
      wp_dequeue_script('woocommerce');
      wp_dequeue_script('wc-add-to-cart');

      wp_deregister_script( 'js-cookie' );
      wp_dequeue_script( 'js-cookie' );

    }

    wp_dequeue_style('wc-block-style');

  }
}
/**
 * woocommerce : Display related products
 * @author Đoàn Nguyễn
 */
add_filter( 'woocommerce_output_related_products_args', 'jk_related_products_args' );
  function jk_related_products_args( $args ) {
    $args['posts_per_page'] = 4; // 4 related products
    return $args;
}

remove_action( 'woocommerce_before_single_product_summary', 'woocommerce_show_product_sale_flash', 10 );


if( !function_exists('flatsome_wc_get_gallery_image_html') ) {
  // Copied and modified from woocommerce plugin and wc_get_gallery_image_html helper function.
  function flatsome_wc_get_gallery_image_html( $attachment_id, $main_image = false, $size = 'woocommerce_single' ) {
    $gallery_thumbnail = wc_get_image_size( 'gallery_thumbnail' );
    $thumbnail_size    = apply_filters( 'woocommerce_gallery_thumbnail_size', array( $gallery_thumbnail['width'], $gallery_thumbnail['height'] ) );
    $image_size        = apply_filters( 'woocommerce_gallery_image_size', $size );
    $full_size         = apply_filters( 'woocommerce_gallery_full_size', apply_filters( 'woocommerce_product_thumbnails_large_size', 'post-thumbnail' ) );
    $thumbnail_src     = wp_get_attachment_image_src( $attachment_id, $thumbnail_size );
    $full_src          = wp_get_attachment_image_src( $attachment_id, $full_size );

    $image             = wp_get_attachment_image( $attachment_id, 'large', false, array(
      'title'                   => get_post_field( 'post_title', $attachment_id ),
      'data-caption'            => get_post_field( 'post_excerpt', $attachment_id ),
      'data-src'                => $full_src[0],
      'data-large_image'        => $full_src[0],
      'data-large_image_width'  => $full_src[1],
      'data-large_image_height' => $full_src[2],
      'class'                   => $main_image ? 'wp-post-image' : '',
    ) );

    $image_wrapper_class = $main_image ? 'slide first' : 'slide';

    return '<div data-thumb="' . esc_url( $thumbnail_src[0] ) . '" class="woocommerce-product-gallery__image '.$image_wrapper_class.'"><a href="' . esc_url( $full_src[0] ) . '" data-fancybox="group">' . $image . '</a></div>';
  }
}



// Check price empty
add_filter('woocommerce_empty_price_html', 'dntheme__woocommerce_get_price_html');
function dntheme__woocommerce_get_price_html() {
  echo _e('Contact','dntheme');
}


// add_filter( 'woocommerce_billing_fields', 'wc_npr_filter_phone', 10, 1);
function wc_npr_filter_phone( $address_fields ) {
    // $address_fields['billing_phone']['required'] = true;
    return $address_fields;
}
//make shipping fields not required in checkout
// add_filter( 'woocommerce_shipping_fields','wc_npr_filter_shipping_fields', 10, 1 );
function wc_npr_filter_shipping_fields( $address_fields ) {
    // $address_fields['shipping_first_name']['required'] = false;
    return $address_fields;
}

/**
 * Woocommerce : Remove address fields
 * @author Đoàn Nguyễn
 */
// add_filter( 'woocommerce_default_address_fields', 'misha_remove_fields' );
function misha_remove_fields( $fields ) {

  $fields['first_name']['class'] = array( 'form-row-wide' );
  $fields['company']['class'] = array( 'd-none' );
  $fields['postcode']['class'] = array( 'd-none' );
  $fields['country']['class'] = array( 'd-none' );


  $fields['city']['required'] = false;
  $fields['city']['class'] = array( 'd-none' );

  $fields['last_name']['required'] = false;
  $fields['last_name']['class'] = array( 'd-none' );

  return $fields;
}


/**
 * Woocommerce : Remove checkout fields
 * @author Đoàn Nguyễn
 */
// add_filter( 'woocommerce_checkout_fields' , 'custom_override_checkout_fields' );
function custom_override_checkout_fields( $fields ) {
     // unset($fields['billing']['billing_city']);
     unset($fields['billing']['billing_last_name']);

     // unset($fields['billing']['billing_country']);
     // unset($fields['billing']['billing_company']);
     // unset($fields['billing']['billing_address_2']);
     unset($fields['billing']['billing_postcode']);
     // unset($fields['billing']['billing_state']);


     // unset($fields['shipping']['shipping_last_name']);
     // unset($fields['shipping']['shipping_state']);
     // unset($fields['shipping']['shipping_city']);
     // unset($fields['shipping']['shipping_country']);
     // unset($fields['shipping']['shipping_address_2']);
     // unset($fields['shipping']['shipping_postcode']);

    //////

    $fields['billing']['billing_first_name']['class']   = array('form-row-wide');
    $fields['shipping']['shipping_first_name']['class']   = array('form-row-wide');

    return $fields;
}

/**
 * Woocommerce : Remove checkout fields in MyAccount
 * @author Đoàn Nguyễn
 */
function storefront_child_remove_unwanted_form_fields($fields) {
    // unset( $fields ['company'] );
    // unset( $fields ['address_2'] );

  unset($fields['city']);
  unset($fields['country']);
  unset($fields['company']);
  unset($fields['address_2']);
  unset($fields['postcode']);
  unset($fields['state']);

    return $fields;
}
// add_filter( 'woocommerce_default_address_fields', 'storefront_child_remove_unwanted_form_fields' );

// My account
add_filter('woocommerce_save_account_details_required_fields', 'ts_hide_last_name');
function ts_hide_last_name($required_fields)
{
unset($required_fields["account_last_name"]);
return $required_fields;
}


// Remove the breadcrumbs
add_action( 'init', 'dntheme_remove_wc_breadcrumbs' );
function dntheme_remove_wc_breadcrumbs() {
    remove_action( 'woocommerce_before_main_content', 'woocommerce_breadcrumb', 20, 0 );
}
/* Remove the tabs */
remove_action( 'woocommerce_after_single_product_summary', 'woocommerce_output_product_data_tabs', 10);
remove_action( 'woocommerce_after_single_product_summary', 'woocommerce_upsell_display', 10);

//Remove heading description
add_filter('woocommerce_product_description_heading', '__return_empty_string');

/* Ajax mini cart */
add_filter('woocommerce_add_to_cart_fragments', 'woocommerce_header_add_to_cart_fragment');
function woocommerce_header_add_to_cart_fragment( $fragments ) {
  global $woocommerce;
  ob_start();
  //$woocommerce->cart->cart_contents_count
  ?>
  <span class="total-cart-qty"><?php echo $woocommerce->cart->cart_contents_count; ?></span>
  <span class="total-cart-price"><?php echo WC()->cart->get_cart_total(); ?></span>
  <?php

  $fragments['span.total-cart'] = ob_get_clean();

  return $fragments;

}

/* Search Product */
function wds_cpt_search( $query ) {
    if ( is_search() && $query->is_main_query() && $query->get( 's' ) ){
        $query->set('post_type', array('product'));
    }
    return $query;
};

// add_filter('pre_get_posts', 'wds_cpt_search');

function woocommerce_page_title( $echo = true ) {

    if ( is_search() ) {
      $page_title = sprintf( __( 'Tìm kiếm với từ khóa: &ldquo;%s&rdquo;', 'dntheme' ), get_search_query() );

      if ( get_query_var( 'paged' ) ) {
        $page_title .= sprintf( __( '&nbsp;&ndash; Page %s', 'dntheme' ), get_query_var( 'paged' ) );
      }
    } elseif ( is_tax() ) {

      $page_title = single_term_title( '', false );

    } else {

      $shop_page_id = wc_get_page_id( 'shop' );
      $page_title   = get_the_title( $shop_page_id );

    }

    $page_title = apply_filters( 'woocommerce_page_title', $page_title );

    if ( $echo ) {
      echo $page_title; // WPCS: XSS ok.
    } else {
      return $page_title;
    }
}

// Remove Tabs
add_filter( 'woocommerce_product_tabs', 'woo_remove_product_tabs', 98 );

function woo_remove_product_tabs( $tabs ) {

    //unset( $tabs['description'] );        // Remove the description tab
    //unset( $tabs['reviews'] );      // Remove the reviews tab
    unset( $tabs['additional_information'] );   // Remove the additional information tab
    //unset( $tabs['comment_tab'] );   // Remove the additional information tab

    return $tabs;

}
/**
 * Rename product data tabs
 */
// add_filter( 'woocommerce_product_tabs', 'woo_rename_tabs', 98 );
function woo_rename_tabs( $tabs ) {

  $tabs['description']['title'] = __( 'Thông tin chi tiết' );   // Rename the description tab
  // $tabs['reviews']['title'] = __( 'Ratings' );        // Rename the reviews tab
  // $tabs['additional_information']['title'] = __( 'Product Data' );  // Rename the additional information tab

  return $tabs;

}

// Remove sale flash in single product
remove_action( 'woocommerce_before_single_product_summary', 'woocommerce_show_product_sale_flash', 10 );



// Hook in
add_filter( 'woocommerce_checkout_fields' , 'custom_override_class_checkout_fields' );
// Our hooked in function - $fields is passed via the filter!
function custom_override_class_checkout_fields( $fields ) {

 // $fields['billing']['billing_country']['priority'] = 10;
  $fields['shipping']['shipping_country']['priority'] = 80;
  return $fields;
}



// breadcrumbs_woo
function breadcrumbs_woo(){
  if(is_cart()||is_checkout()){
    $class1 = '';$class2 = '';$class3 = '';
    if(is_cart()) $class1 = 'current';//Check cart
    if (is_checkout()&&!is_order_received_page()) $class2 = 'current'; //Check checkout
    if(is_order_received_page()) $class3 = 'current'; //Check checkout complete
    ?>
    <nav class="breadcrumbs breadcrumbs__divider text-center">
      <div class="container container-larger">
      <div class="wrap__all">
      <a href="<?php echo wc_get_cart_url(); ?>" class="<?php echo $class1; ?>"><?php _e( 'Shopping Cart', 'dntheme' ); ?></a>
      <span class="divider"><i class="fa fa-angle-right d-none d-sm-inline-block" aria-hidden="true"></i></span>
      <a href="<?php echo wc_get_checkout_url() ; ?>" class="<?php echo $class2; ?>"><?php _e( 'Checkout details', 'dntheme' ); ?></a>
      <span class="divider"><i class="fa fa-angle-right d-none d-sm-inline-block" aria-hidden="true"></i></span>
      <a href="#" class="<?php echo $class3; ?> no-click"><?php _e( 'Order Complete', 'dntheme' ); ?></a>
    </div>
    </div>
    </nav>
    <?php
  }
}



// Add save percent next to sale item prices.
add_filter( 'woocommerce_sale_price_html', 'woocommerce_custom_sales_price', 10, 2 );
function woocommerce_custom_sales_price( $price, $product ) {
  $percentage = round( ( ( $product->regular_price - $product->sale_price ) / $product->regular_price ) * 100 );
  return $price . sprintf( __(' Save %s', 'woocommerce' ), $percentage . '%' );
}


function wooc_extra_register_fields() {?>
  <p class="form-row form-row-first">
  <label for="reg_billing_first_name"><?php _e( 'First name', 'woocommerce' ); ?><span class="required">*</span></label>
  <input type="text" class="input-text" name="billing_first_name" id="reg_billing_first_name" value="<?php if ( ! empty( $_POST['billing_first_name'] ) ) esc_attr_e( $_POST['billing_first_name'] ); ?>" />
  </p>
  <p class="form-row form-row-last">
  <label for="reg_billing_last_name"><?php _e( 'Last name', 'woocommerce' ); ?><span class="required">*</span></label>
  <input type="text" class="input-text" name="billing_last_name" id="reg_billing_last_name" value="<?php if ( ! empty( $_POST['billing_last_name'] ) ) esc_attr_e( $_POST['billing_last_name'] ); ?>" />
  </p>
  <div class="clear"></div>
  <?php
 }
 // add_action( 'woocommerce_register_form_start', 'wooc_extra_register_fields' );

// Remove menu Tiep thi
add_filter('woocommerce_marketing_menu_items','__return_empty_array');

add_action('woocommerce_checkout_process', 'my_custom_checkout_field_process');

function my_custom_checkout_field_process() {
    global $woocommerce;

    // Check if set, if its not set add an error. This one is only requite for companies
    if ( ! (preg_match('/^[0-9]{10,11}$/D', $_POST['billing_phone'] ))){
        wc_add_notice( "Vui lòng điền đúng số điện thoại"  ,'error' );
    }
}

add_filter( 'yith_wcwl_is_wishlist_responsive', '__return_false' );

/**
 * To validate WooCommerce registration form custom fields.
 */
function text_domain_woo_validate_reg_form_fields($username, $email, $validation_errors) {
    if (isset($_POST['billing_first_name']) && empty($_POST['billing_first_name'])) {
        $validation_errors->add('billing_first_name_error', __('<strong>Error</strong>: Họ và tên là bắt buộc!', 'dntheme'));
    }

    if (isset($_POST['username']) && empty($_POST['username'])) {
        $validation_errors->add('username_error', __('<strong>Error</strong>: Số điện thoại là bắt buộc!', 'dntheme'));
    }

    if (isset($_POST['username']) && $_POST['username'] ) {

      $phone = str_replace(array('+','-', '.', ' '), '', $_POST['username']);
      $regEx = '/^0(\d{9})$/';

      $match = preg_match($regEx, $phone);

      if(!$match){
          $validation_errors->add('username_error', __('<strong>Error</strong>: Số điện thoại không đúng định dạng', 'dntheme'));
      }

    }

    if (isset($_POST['password2']) && $_POST['password'] != $_POST['password2'] ) {
        $validation_errors->add('username_error', __('<strong>Error</strong>: Mật khẩu không trùng nhau!', 'dntheme'));
    }




    return $validation_errors;
}

add_action('woocommerce_register_post', 'text_domain_woo_validate_reg_form_fields', 10, 3);

// Add term and conditions check box on registration form
add_action( 'woocommerce_register_form', 'njengah_terms_and_conditions_to_registration', 20 );
function njengah_terms_and_conditions_to_registration() {
    if ( wc_get_page_id( 'terms' ) > 0 && is_account_page() ) {
        ?>
        <p class="form-row terms wc-terms-and-conditions">
            <label class="woocommerce-form__label woocommerce-form__label-for-checkbox checkbox">
                <input type="checkbox" class="woocommerce-form__input woocommerce-form__input-checkbox input-checkbox" name="terms" <?php checked( apply_filters( 'woocommerce_terms_is_checked_default', isset( $_POST['terms'] ) ), true ); ?> id="terms" /> <span><?php printf( __( 'Chấp nhận chính sách bảo mật của Hithanks và đồng ý để Hithanks lưu thông tin cá nhân của tôi <a href="%s" target="_blank" class="woocommerce-terms-and-conditions-link">chính sách riêng tư </a> của chúng tôi.', 'dntheme' ), esc_url( wc_get_page_permalink( 'terms' ) ) ); ?></span> <span class="required">*</span>
            </label>
            <input type="hidden" name="terms-field" value="1" />
        </p>
    <?php
    }
}
// Validate required term and conditions check box
add_action( 'woocommerce_register_post', 'terms_and_conditions_validation', 20, 3 );
function terms_and_conditions_validation( $username, $email, $validation_errors ) {
    if ( ! isset( $_POST['terms'] ) )
        $validation_errors->add( 'terms_error', __( 'Terms and condition are not checked!', 'woocommerce' ) );
    return $validation_errors;
}

function woomatrix_swatches_get_attr_option_description($attribute){

  global $product;
  $values = wc_get_product_terms( $product->id, $attribute, array( 'fields' =>  'all' ) );
  if( $values ){
      echo '<dl class="value__custom-meta mt-3">';
          foreach ( $values as $term ){
              echo '<dd class="'.$term->slug.'">' . term_description( $term->term_id ) . '</dd>';
          }
      echo '</dl>';
  }


}
add_filter('wcva_after_attribute_swatches', 'woomatrix_swatches_get_attr_option_description', 10, 3);

// Sắp xếp sản phẩm trên trang taxonomy product (danh mục sản phẩm) theo ngày đăng từ mới đến cũ
add_action( 'pre_get_posts', 'custom_order_product_tax_by_date_desc' );
function custom_order_product_tax_by_date_desc( $query ) {
    if ( ! is_admin() && $query->is_main_query() && is_tax( get_object_taxonomies( 'product' ) ) ) {
        $query->set( 'orderby', 'date' );
        $query->set( 'order', 'DESC' );
    }
}
