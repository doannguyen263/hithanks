<?php

/**
 * Template Name: Page Home
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
<?php while (have_posts()) : the_post(); ?>

  <?php
  if (have_rows('items')):  ?>
    <div class="home-nav py-3">
      <div class="container-fluid">
        <?php while (have_rows('items')) : the_row();
          $get_title       = get_sub_field('title');
          $get_sub         = get_sub_field('excerpt');
          $get_link_button = get_sub_field('link_button');
          $get_link        = get_sub_field('link');
          $get_link_text   = get_sub_field('link_text');
          $get_color       = get_sub_field('color');
          $style           = get_sub_field('style');
          $content         = get_sub_field('content');
          $sub         = get_sub_field('sub');
          $bannerSlider = get_sub_field('bannerSlider');


          $styleHasSub = ($style == 'style2') && have_rows('submenu');
        ?>
          <div class="home-nav__item <?php echo ($bannerSlider) ? 'has-slider' : '' ?> <?= $style ?>  <?= ($style != 'style3') ? 'js-plus' : '' ?> ">
            <div class="card rounded-0 border-0 mb-2 text-white <?= ($style == 'style3') ? '-link' : "" ?>" style="background: <?= $bannerSlider ? '' : $get_color ?>">
              <?php if ($bannerSlider): ?>
                <div class="main-carousel">
                  <?php
                  foreach ($bannerSlider as $value) {
                    $imageID = $value['image'];
                  ?>
                    <div class="carousel-cell">
                      <div class="carousel-cell__thumb">
                        <?php echo wp_get_attachment_image($imageID, 'full'); ?>
                      </div>
                    </div>
                  <?php
                  }
                  ?>
                </div>
              <?php endif; ?>

              <div class="card-body">

                <?php if (!$get_sub): ?>
                  <h2 class="card-title text-uppercase mb-0"><a href="<?= $get_link ?>" class="text-white stretched-link"><?= $get_title ?></a></h2>
                  <p class="mt-1"><?= $sub ?></p>
                  <?php if ($styleHasSub): ?>
                    <div class="button-plus__wrap ms-auto">
                      <div class="button-plus"></div>
                    </div>
                  <?php endif; ?>

                <?php else: ?>
                  <h2 class="card-title text-white text-uppercase mb-0 <?= ($style == 'linkshowcontent') ? 'd-flex' : '' ?>"><a href="<?= $get_link ?>" class="text-white stretched-link"><?= $get_title ?></a>

                    <?php if ($style == 'linkshowcontent'): ?>
                      <div class="button-plus__wrap ms-auto">
                        <div class="button-plus"></div>
                      </div>
                    <?php endif; ?>
                  </h2>
                  <p class="mt-1 mb-0"><?= $sub ?></p>


                   <?php if ($styleHasSub): ?>
                    <div class="button-plus__wrap ms-auto">
                      <div class="button-plus"></div>
                    </div>
                  <?php endif; ?>
                  
                  <?php if ($style == 'linkshowcontent' || $style == 'style3'): ?>
                    <div class="card-text h4 w-100 pe-5 <?= $style ?>__card-text">
                      <div class="js-plus-get-height js-slidetoggle-content" style="display: none;"><?= $get_sub ?></div>
                    </div>
                  <?php endif; ?>

                  <?php if ( $style != 'linkshowcontent'): ?>
                    <div class="card-text h4 w-100 mt-auto pe-5 <?= $style ?>__card-text">
                      <?= $get_sub ?>
                    </div>
                  <?php endif; ?>

                <?php endif; ?>

                <?php if ($style == 'style1'): ?>
                  <div class="card-meta pe-5 pe-md-0" style="display: none;">
                    <div class="row text-start mb-4">
                      <?= $content ?>
                    </div>
                  </div>
                <?php endif; ?>
              </div>


            </div>
            <?php if ($style == 'style2'): ?>
              <div class="card-meta mb-2" style="display: none;">
                <?php
                if (have_rows('submenu')):  ?>
                  <ul class="el-list">
                    <?php while (have_rows('submenu')) : the_row();
                      $get_sub_title = get_sub_field('title');
                      $get_sub_sub = get_sub_field('sub');
                      $get_sub_link = get_sub_field('link');
                      $get_sub_color = get_sub_field('color') ? get_sub_field('color') : 'linear-gradient(45deg,#3cb3b1,#71c7c6)';
                    ?>
                      <li style="background: <?= $get_sub_color ?>;"><a href="<?= $get_sub_link ?>"><?= $get_sub_title ?><p><?= $get_sub_sub ?></p></a></li>
                    <?php endwhile; ?>
                  </ul>
                <?php endif; ?>

              </div>
            <?php endif; ?>
          </div>
        <?php endwhile; ?>
      </div>
    </div>
  <?php endif; ?>

<?php endwhile; ?>
<?php get_footer();
