<?php
/**
 * Template Name: Page Checkout
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
$shop_page_url = get_permalink( wc_get_page_id( 'cart' ) );
get_header(); ?>

<div class="wrap__page">
  <main class="site-main" role="main">
    <div class="container">
    <?php
    while ( have_posts() ) : the_post(); ?>
      <?php $get_sub = get_field('sub');?>

      <ul class="nav-list d-flex justify-content-md-end ms-md-auto">
          <li value=""><a href="#" class="active"><?php the_title(); ?></a></li>
          <li><a href="<?= $shop_page_url ?>"><?php _e('Giỏ hàng','dntheme'); ?> </a></li>
      </ul>
      <hr>

      <?php $get_color       = get_field('color'); ?>
      <div class="about-heading" style="background: <?= $get_color ?>">
        <h1 class="about-heading__title">
          <?php if( $get_sub ):
                  echo $get_sub;
              else:
                  the_title();
          endif; ?>
        </h1>
      </div>
      <hr>

      <article class="page__content">
          <?php the_content(); ?>
      </article>

    <?php
    endwhile; // End of the loop.
    ?>

      <?php
      if( have_rows('menu_link') ):  ?>
      <ul class="page-list">
        <?php while( have_rows('menu_link') ) : the_row();
             $get_title = get_sub_field('title');
             $get_link = get_sub_field('link');
            ?>
            <li><a href="<?= $get_link ?>"><?= $get_title ?></a></li>
        <?php endwhile; ?>
      </ul>
      <?php endif;?>

    </div>

  </main>
</div>
<?php get_footer();