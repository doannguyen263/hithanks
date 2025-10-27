<?php
/**
 * The template for displaying search results pages
 *
 * @package    WordPress
 * @subpackage Dntheme
 * @version 1.0
 */

get_header(); ?>

<div class="container">
  <ul class="nav-list d-flex justify-content-end pt-4">
      <li value=""><a href="#" class="active"><?php _e('Tìm kiếm','dntheme'); ?></a></li>
  </ul>
  <hr>
</div>

<div class="wrap__page">

	<section class="page__header">
      <div class="container">
        <?php if ( have_posts() ) : ?>
			<h1 class="page__header__title"><?php printf( __( 'Tìm kiếm với từ khóa: %s', 'dntheme' ), '<span>' . get_search_query() . '</span>' ); ?></h1>
		<?php else : ?>
			<h1 class="page__header__title"><?php _e( 'Nothing Found', 'dntheme' ); ?></h1>
		<?php endif; ?>
      </div>
    </section>

	<div class="page__content sc__wrap">

		<div class="archive__content">
			<div class="container">
				<?php
				if ( have_posts() ) : $i=0;
          while ( have_posts() ) : the_post(); $i++;
          if( $i < 10) $i = '0'.$i;
          $GLOBALS['stt'] = $i; ?>
                  <?php get_template_part( 'template-parts/content','archive'); ?>
          <?php
          endwhile;
          dntheme_paging_nav();
				else : ?>
					<div class="box-search">
						<p class="mb-3 text-center"><?php _e( 'Rất tiếc, nhưng không có gì phù hợp với cụm từ tìm kiếm của bạn. Vui lòng thử lại với một số từ khóa khác nhau.', 'dntheme' ); ?></p>

						<?php get_search_form(); ?>
					</div>
				<?php endif; ?>
				</div>
			</div>
		</div>
	</div><!-- .wrap -->
</div>
<?php get_footer();