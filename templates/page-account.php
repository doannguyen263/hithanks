<?php
/**
 * Template Name: Page Account
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

get_header(); ?>
<?php while ( have_posts() ) : the_post(); ?>

<div class="container">
  <ul class="nav-list d-flex justify-content-end">
      <li value=""><a href="#" class="active"><?php the_title(); ?></a></li>
  </ul>
  <hr>
</div>

<div class="page__content">
    <div class="container">
        <div class="entry-content">
          <?php the_content() ?>
        </div>
    </div>
</div>

<?php endwhile; ?>
<?php get_footer();