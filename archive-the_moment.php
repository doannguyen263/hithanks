<?php
/**
 * The template for displaying archive pages
 *
 * @package    WordPress
 * @subpackage Dntheme
 * @version 1.0
 */

get_header();

$page_title = 'The Moment';
// Check if this is taxonomy archive or post type archive
$is_taxonomy = is_tax('the_moment_cat');
$is_post_type_archive = is_post_type_archive('the_moment');

// Always get all parent categories (parent = 0) for navigation
$categories = get_terms(array(
  'taxonomy' => 'the_moment_cat',
  'parent' => 0,
  'hide_empty' => false
));

if ($is_taxonomy) {
  // Taxonomy archive - get term data
  $term = get_queried_object();
  $term_id = $term->term_id;
  $term_parent = $term->parent;
  
  // Get lienket_danhmuc field
  if( $term_parent ){
    $lienket_danhmuc = get_field('lienket_danhmuc','the_moment_cat_'.$term_parent);
  } else {
    $lienket_danhmuc = get_field('lienket_danhmuc','the_moment_cat_'.$term_id);
  }
} else {
  // Post type archive - set default values
  $term = null;
  $term_id = 0;
  $term_parent = 0;
  $lienket_danhmuc = null;
}

?>

<div class="nav-dieuhuong" data-toggle="sticky-onscroll">
  <div class="container">

    <div class="d-md-flex align-items-center">
      <div class="nav-dieuhuong__title back-to-top"><?= $page_title ?></div>
      <ul class="nav-list d-flex justify-content-md-end ms-md-auto">
        <?php if( $categories && !empty($categories) && !is_wp_error($categories) ):
          foreach ($categories as $item) {
            $item_term_id = $item->term_id;
            $item_name = $item->name;
            // Check if current term belongs to this parent category (for active state)
            $class_active = '';
            if ($is_taxonomy && $term_id) {
              // Check if current term is this category or a child of this category
              $term_ancestors = get_ancestors($term_id, 'the_moment_cat');
              if ($term_id == $item_term_id || in_array($item_term_id, $term_ancestors)) {
                $class_active = 'active';
              }
            }
            echo '<li><a href="'.get_term_link($item_term_id).'" class="'.$class_active.'">'. $item_name.'</a></li>';
          }
        endif; ?>

        <?php
          if( $lienket_danhmuc ){
            echo '<li><a href="'.get_term_link( $lienket_danhmuc->term_id ).'" class="">'.$lienket_danhmuc->name.'</a></li>';
          }
        ?>
      </ul>
    </div>

  </div>
</div>
<div class="container"><hr class="mt-0"></div>

<div class="wrap__page">

  <div class="container">
    <?php
    if ($is_taxonomy && $term_id) {
      $get_title = get_field('title','the_moment_cat_'.$term_id);
      $get_sub = get_field('sub','the_moment_cat_'.$term_id);
    } else {
      $get_title = null;
      $get_sub = null;
    }
    ?>
    <div class="about-heading text-white">
      <?php if($get_title): ?>
      <h1 class="about-heading__title"><?php echo $get_title;?></h1>
      <div class="about-heading__sub h2"><?php echo $get_sub;?></div>
      <?php else: ?>
        <h1 class="about-heading__title"><?php echo $is_taxonomy ? single_term_title('', false) : post_type_archive_title('', false);?></h1>
      <?php endif; ?>
    </div>
    <hr>

    <div class="archive__content mb-5">
      <?php
      if ( have_posts() ) : $i=0;
        while ( have_posts() ) : the_post(); $i++;
          if( $i < 10) $i = '0'.$i;
          $GLOBALS['stt'] = $i; ?>
            <?php get_template_part( 'template-parts/content','archive'); ?>
        <?php
        endwhile;
        dntheme_paging_nav();
      else :
        get_template_part( 'template-parts/content', 'none' );
      endif;
      ?>
    </div>
  </div><!-- .wrap -->
</div>
<?php get_footer();
