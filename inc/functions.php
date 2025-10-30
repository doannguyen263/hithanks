<?php

/**
 * Register Custom Post Type Service
 * @author Đoàn Nguyễn
 */
function service_post_type()
{
    $labels = array(
        'name'                  => _x('Dự án triển khai', 'Dự án triển khai General Name', 'dntheme'),
        'singular_name'         => _x('Dự án triển khai', 'Dự án triển khai Singular Name', 'dntheme'),
        'menu_name'             => __('Dự án triển khai', 'dntheme'),
        'name_admin_bar'        => __('Dự án triển khai', 'dntheme'),
        'add_new_item'          => __('Thêm mới', 'dntheme'),
        'add_new'               => __('Thêm mới', 'dntheme'),
        'new_item'              => __('Thêm', 'dntheme'),

    );

    $args = array(
        'label'                 => __('Dự án triển khai', 'dntheme'),
        'labels'                => $labels,
        'supports'              => array('title', 'thumbnail', 'editor', 'excerpt', 'revisions'),
        'public'                => true,
        'menu_position'         => 10,
        'can_export'            => true,
        'has_archive'           => true,
        'menu_icon' => 'dashicons-category',
        'rewrite' => array('slug' => 'du-an', 'with_front' => false),
    );
    register_post_type('project', $args);

    $labels = array(
        'name'              => _x('Danh mục Dự án triển khai', 'Taxonomy General Name', 'dntheme'),
        'singular_name'     => _x('Danh mục Dự án triển khai', 'Taxonomy Singular Name', 'dntheme'),
        'menu_name'         => __('Danh mục', 'dntheme'),
    );


    register_taxonomy(
        'project_cat',
        'project',
        array(
            'hierarchical' => true,
            'labels' => $labels,
            'show_admin_column' => true,
            'rewrite' => array('slug' => __('danh-muc-du-an'))
        )
    );
    flush_rewrite_rules();
}

add_action('init', 'service_post_type', 5);


/**
 * Register Custom Post Type Service
 * @author Đoàn Nguyễn
 */
function order_post_type()
{
    $labels = array(
        'name'                  => _x('Đơn hàng thiết kế', 'Đơn hàng thiết kế General Name', 'dntheme'),
        'singular_name'         => _x('Đơn hàng thiết kế', 'Đơn hàng thiết kế Singular Name', 'dntheme'),
        'menu_name'             => __('Đơn hàng thiết kế', 'dntheme'),
        'name_admin_bar'        => __('Đơn hàng thiết kế', 'dntheme'),
        'add_new_item'          => __('Thêm mới', 'dntheme'),
        'add_new'               => __('Thêm mới', 'dntheme'),
        'new_item'              => __('Thêm', 'dntheme'),

    );

    $args = array(
        'label'                 => __('Đơn hàng thiết kế', 'dntheme'),
        'labels'                => $labels,
        'supports'              => array('title', 'thumbnail', 'editor', 'excerpt', 'revisions'),
        'public'                => true,
        'menu_position'         => 10,
        'can_export'            => true,
        'has_archive'           => true,
        'menu_icon' => 'dashicons-category',
        'rewrite' => array('slug' => 'don-hang-thiet-ke', 'with_front' => false),
    );
    register_post_type('order-design', $args);

    $labels = array(
        'name'              => _x('Danh mục Đơn hàng thiết kế', 'Taxonomy General Name', 'dntheme'),
        'singular_name'     => _x('Danh mục Đơn hàng thiết kế', 'Taxonomy Singular Name', 'dntheme'),
        'menu_name'         => __('Danh mục', 'dntheme'),
    );


    register_taxonomy(
        'order_cat',
        'order-design',
        array(
            'hierarchical' => true,
            'labels' => $labels,
            'show_admin_column' => true,
            'rewrite' => array('slug' => __('danh-muc-don-hang-thiet-ke'))
        )
    );
    flush_rewrite_rules();
}

add_action('init', 'order_post_type', 5);


function rt_list_child_pages() {
  global $post;
  $string = null;
  if ( is_page() && $post->post_parent ){
    $childpages = "";
    // if( $post->post_parent ){

    //   $get_post = get_post( $post->post_parent );
    //   $childpages =  '<li><a href="'.get_permalink($post->post_parent).'">'.get_the_title($post->post_parent).'</a></li>';
    // }
      $childpages .= wp_list_pages( 'sort_column=menu_order&title_li=&child_of=' . $post->post_parent . '&echo=0' );

  } else {
    $childpages = "";
    // if( !$post->post_parent ){
    //  $childpages =  '<li class="current_page_item"><a href="'.get_permalink($post->ID).'">'.get_the_title($post->post_parent).'</a></li>';
    // }
      $childpages .= wp_list_pages( 'sort_column=menu_order&title_li=&child_of=' . $post->ID . '&echo=0' );
  }
  if ( $childpages ) {
      $string = $childpages;
  }
  return $string;
}
add_shortcode('list-childpages', 'rt_list_child_pages');


function rt_list_child_pagesv2($text = 'Giới thiệu') {
  global $post;
  $string = null;
  if ( is_page() && $post->post_parent ){
    $childpages = "";
    if( $post->post_parent ){
      $get_post = get_post( $post->post_parent );
      $childpages =  '<li><a href="'.get_permalink($post->post_parent).'">'.$text.'</a></li>';
    }
      $childpages .= wp_list_pages( 'sort_column=menu_order&title_li=&child_of=' . $post->post_parent . '&echo=0' );

  } else {
    $childpages = "";
    if( !$post->post_parent ){
     $childpages =  '<li class="current_page_item"><a href="'.get_permalink($post->ID).'">'.$text.'</a></li>';
    }
      $childpages .= wp_list_pages( 'sort_column=menu_order&title_li=&child_of=' . $post->ID . '&echo=0' );
  }
  if ( $childpages ) {
      $string = $childpages;
  }
  return $string;
}
add_shortcode('list-childpagesv2', 'rt_list_child_pagesv2');

function single_list_terms_product()
{
  $args = array(
      'taxonomy' => 'product_cat',
      'hide_empty' => false,
      'parent' => 0
  );

  $parent_categories = get_terms( $args );
  $current_product_id = get_the_ID();

  // Lặp qua danh sách các danh mục cha
  foreach ( $parent_categories as $category ) {
    $class_active = has_term( $category->term_id, 'product_cat', $current_product_id) ? 'active' : '';
      // Kiểm tra xem sản phẩm có thuộc danh mục cha hiện tại không
      echo '<li class="active"><a href="' . get_term_link( $category ) . '" class="'.$class_active.'">' . $category->name . '</a></li>';

  }
}
