<?php
/**
 * The template for displaying archive pages
 *
 * @package    WordPress
 * @subpackage Dntheme
 * @version 1.0
 */

get_header();
$term = get_queried_object();
$term_id = $term->term_id;
$term_parent = $term->category_parent;
$categories = get_categories(
    array( 'parent' => $term_id, 'hide_empty' => false)
);


if( $term_parent ){
  $term_parent_object = get_term_by('id', $term_parent, 'category');
  $title_parent = $term_parent_object->name;
  $lienket_danhmuc = get_field('lienket_danhmuc','category_'.$term_parent);

}else{
  $title_parent = single_term_title( '', false );
  $lienket_danhmuc = get_field('lienket_danhmuc',$term);

}
?>

<div class="nav-dieuhuong" data-toggle="sticky-onscroll">
  <div class="container">

    <div class="d-md-flex align-items-center">
      <div class="nav-dieuhuong__title back-to-top"><?= $title_parent ?></div>
      <ul class="nav-list d-flex justify-content-md-end ms-md-auto">
        <?php
        if( $term_parent ): ?>
          <li><a href="<?= get_term_link($term_parent) ?>"><?= $title_parent ?></a></li>
        <?php endif; ?>
        <?php if( $categories ):

          foreach ($categories as $item) {
            echo '<li value=""><a href="'.get_term_link($item->term_id).'">'. $item->name.'</a></li>';
          }

        else: ?>

          <?php
          $categories = get_categories(
              array( 'parent' => $term_parent, 'hide_empty' => false)
          );
          foreach ($categories as $item) {
            $class_active = ($term_id == $item->term_id) ? 'active':'';
            echo '<li><a href="'.get_term_link($item->term_id).'" class="'.$class_active.'">'. $item->name.'</a></li>';
          }
          ?>

        <?php endif; ?>

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
    $get_title = get_field('title',$term);
    $get_sub = get_field('sub',$term);
    ?>
    <div class="about-heading text-white">
      <?php if($get_title): ?>
      <h1 class="about-heading__title"><?php echo $get_title;?></h1>
      <div class="about-heading__sub h2"><?php echo $get_sub;?></div>
      <?php else: ?>
        <h1 class="about-heading__title"><?php echo single_term_title( '', false );?></h1>
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
