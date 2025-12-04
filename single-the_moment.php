<?php
/**
 * The template for displaying all single posts
 *
 * @package    WordPress
 * @subpackage Dntheme
 * @version 1.0
 */
get_header(); ?>
<div class="wrap__page">
    <div class="wrap__content sc__wrap">
        <?php
            while ( have_posts() ) : the_post();
                get_template_part( 'template-parts/content','single-moment');
            endwhile;
        ?>
    </div>
</div>
<?php get_footer();
