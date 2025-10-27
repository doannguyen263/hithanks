<?php
/**
 * Template Name: Page Faqs
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
<div class="nav-dieuhuong js-scrollbar" data-toggle="sticky-onscroll">
  <div class="container">
    <div class="d-md-flex align-items-center">
      <div class="nav-dieuhuong__title back-to-top"><?php the_title() ?></div>
      <?php
        if( have_rows('items') ): $i=0?>
          <ul class="nav-list d-flex justify-content-md-end ms-md-auto">
            <?php
            $i=0;
            while( have_rows('items') ) : the_row(); $i++;
              $title = get_sub_field('title');
              $class_active = ( $i==1) ? 'active' : '';
              ?>
              <li><a href="#faqs_<?= $i; ?>" class="<?= $class_active ?>"><?= $title; ?></a></li>
            <?php endwhile; ?>
          </ul>
        <?php endif; ?>
    </div>
  </div>
</div>
<div class="container"><hr class="mt-0"></div>

<div class="page__content">
    <div class="container">

      <?php $get_color       = get_field('color'); ?>
      <div class="about-heading" style="background: <?= $get_color ?>">
        <h2 class="about-heading__title"><?php the_field('sub') ?></h2>
        <?php
        $get_field_sub2 = get_field('sub2');
        if ( $get_field_sub2 ): ?>
          <div class="about-heading__sub h2"><?= $get_field_sub2 ?></div>
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
              ?>
              <li id="faqs_<?= $i; ?>" class="wow fadeInUp">
                <div class="row">
                  <div class="col-md-3 col-lg-3 col-xl-3">
                    <div class="about-list__title pt-md-2 mt-md-2">
                      <?= $title ?>
                    </div>
                  </div>
                  <div class="col-md-9 col-lg-9 col-xl-9">
                    <div class="about-list__content">
                      <?php if( $content ): ?>
                        <div class="entry-content">
                          <?= $content ?>
                        </div>
                      <?php endif; ?>
                      <?php
                      if( have_rows('items_sub') ): $j=0; ?>
                      <div class="accordion accordion-flush" id="accordionFlushExample<?= $i ?>">
                        <?php while( have_rows('items_sub') ) : the_row(); $j++;
                             $get_title = get_sub_field('sub_title');
                             $get_content = get_sub_field('sub_content');
                            ?>
                            <div class="accordion-item">
                              <h2 class="accordion-header" id="flush-heading<?= $i ?>_<?= $j ?>">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#flush-<?= $i ?>_<?= $j ?>" aria-expanded="false" aria-controls="flush-<?= $i ?>_<?= $j ?>">
                                  <?= $get_title ?>
                                </button>
                              </h2>
                              <div id="flush-<?= $i ?>_<?= $j ?>" class="accordion-collapse collapse" aria-labelledby="flush-heading<?= $i ?>_<?= $j ?>">
                                <div class="accordion-body"><?= $get_content ?></div>
                              </div>
                            </div>
                        <?php endwhile; ?>
                      </div>
                      <?php endif; ?>
                    </div>
                  </div>
                </div>
              </li>
            <?php endwhile; ?>
          </ul>
        <?php else :
          get_template_part( 'template-parts/content', 'none' );
        endif;
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
</div>

<?php endwhile; ?>
<?php get_footer();