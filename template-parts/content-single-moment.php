<?php
/**
 * Template part for displaying posts
 *
 * @package    WordPress
 * @subpackage Dntheme
 * @version 1.0
 */
$categories = get_the_terms(get_the_ID(), 'the_moment_cat');
$cat_name = $categories && !is_wp_error($categories) ? $categories[0]->name : '';
$cat_link = $categories && !is_wp_error($categories) ? get_term_link($categories[0]) : '';
?>
<div class="nav-dieuhuong" data-toggle="sticky-onscroll">
  <div class="container">
    <div class="d-flex align-items-center">
      <div class="back-to-top flex-grow-1 py-sm-3">
        <i class="iconz-BackToTop"></i>
      </div>
      <ul class="nav-list d-flex justify-content-md-end ms-auto w-auto w-sm-100">
        <li value=""><a href="<?= $cat_link ?>" class="ps-5 active"><i class="iconz-TurnBack"></i></a></li>
      </ul>
    </div>
  </div>
</div>
<div class="container"><hr class="mt-0"></div>
<div class="page__content pb-5">
  <div class="container">


      <?php $get_color       = get_field('color'); ?>
      <div class="about-heading" style="background: <?= $get_color ?>">
        <h1 class="about-heading__title"><?php the_title(); ?></h1>
        <?php
        $get_field_sub = get_field('sub');
        if ( $get_field_sub ): ?>
          <div class="about-heading__sub h2 mb-0"><?= $get_field_sub ?></div>
        <?php endif ?>
      </div>
      <hr>
      <?php
      if( have_rows('items') ): $i=0?>
        <ul class="about-list">
          <?php
          $i=0;
          while( have_rows('items') ) : the_row(); $i++;
            $title = get_sub_field('title');
            $content = get_sub_field('content');
            $column = get_sub_field('column');
            $content2col = get_sub_field('content2col');
            $content1 = $content2col['content1'];
            $content2 = $content2col['content2'];

            $content3col = get_sub_field('content3col');
            $content3col_content1 = $content3col['content1'];
            $content3col_content2 = $content3col['content2'];
            $content3col_content3 = $content3col['content3'];
            ?>
            <li class="wow fadeInUp">
              <div class="row">
                <?php if( $column == 'half'):
                  ?>
                  <div class="col-md-6 mb-3 mb-md-0">
                    <div class="entry-content -half-style">
                      <?= $content1 ?>
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="entry-content -half-style">
                      <?= $content2 ?>
                    </div>
                  </div>
                <?php elseif( $column == '1p3'):
                  ?>

                  <?php if( $title ): ?>
                    <div class="col-md-12 mb-4">
                      <div class="about-list__title">
                        <?= $title ?>
                      </div>
                    </div>
                  <?php endif; ?>

                  <div class="col-md-4 mb-3 mb-md-0">
                    <div class="entry-content -half-style">
                      <?= $content3col_content1 ?>
                    </div>
                  </div>
                  <div class="col-md-4 mb-3 mb-md-0">
                    <div class="entry-content -half-style">
                      <?= $content3col_content2 ?>
                    </div>
                  </div>
                  <div class="col-md-4">
                    <div class="entry-content -half-style">
                      <?= $content3col_content3 ?>
                    </div>
                  </div>
                <?php elseif( $column == 'full' ): ?>
                  <div class="col-md-12">
                    <div class="entry-content -full-style">
                      <?= $content ?>
                    </div>
                  </div>
                <?php else: ?>
                  <div class="col-md-3 col-lg-3 col-xl-3">
                    <div class="about-list__title">
                      <?= $title ?>
                    </div>
                  </div>
                  <div class="col-md-9 col-lg-9 col-xl-9">
                    <div class="about-list__content">
                      <div class="entry-content">
                      <?php if( $column == 1): ?>
                          <?= $content ?>
                      <?php else: ?>
                        <div class="row">
                          <div class="col-md-6 mb-3 mb-md-0">
                              <?= $content1 ?>
                          </div>
                          <div class="col-md-6">
                              <?= $content2 ?>
                          </div>
                        </div>
                      <?php endif ?>
                      </div>
                    </div>
                  </div>
                <?php endif; ?>
              </div>
            </li>
          <?php endwhile; ?>

        </ul>
      <?php else :
        get_template_part( 'template-parts/content', 'none' );
      endif;
      ?>
  </div>
</div>
