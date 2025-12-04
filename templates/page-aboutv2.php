<?php

/**
 * Template Name: Page About V2
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
get_header();
while (have_posts()) : the_post();
  $get_the_ID = get_the_ID();
  if ($post->post_parent) {
    $title_parent = get_the_title($post->post_parent);

    $fix_title = get_field('title', $post->post_parent);
    if ($fix_title) {
      $title_parent = $fix_title;
    }
  } else {
    $title_parent = get_the_title();

    $fix_title = get_field('title', $post->post_parent);
    if ($fix_title) {
      $title_parent = $fix_title;
    }
  }

  $subpage_title = get_field('subpage_title');

?>
  <div class="nav-dieuhuong" data-toggle="sticky-onscroll">
    <div class="container">
      <div class="d-md-flex align-items-center">
        <div class="nav-dieuhuong__title back-to-top"><?= $subpage_title ?? $title_parent ?></div>

        <?php
        if (have_rows('main_links')):
          $items = [];
          $foundActive = false;
          while (have_rows('main_links')) : the_row();
            $title = get_sub_field('title');
            $link = get_sub_field('link');
            $classActive = (untrailingslashit(get_permalink()) === untrailingslashit($link)) ? 'current_page_item' : '';
            if ($classActive) $foundActive = true;
            $items[] = [
              'title' => $title,
              'link' => $link,
              'class' => $classActive
            ];
          endwhile;
          // Nếu không có classActive nào thì gán cho phần tử đầu tiên
          if (!$foundActive && count($items) > 0) {
            $items[0]['class'] = 'current_page_item';
          }
        ?>
          <ul class="nav-list d-flex justify-content-md-end ms-md-auto js-nav-list">
            <?php foreach ($items as $item): ?>
              <li class="<?= $item['class'] ?>"><a href="<?= $item['link'] ?>"><?= $item['title'] ?></a></li>
            <?php endforeach; ?>
          </ul>
        <?php else: ?>
          <ul class="nav-list d-flex justify-content-md-end ms-md-auto js-nav-list">
            <?php echo rt_list_child_pagesv2(); ?>
          </ul>
        <?php endif; ?>
      </div>
    </div>
  </div>
  <div class="container">
    <hr class="mt-0">
  </div>

  <div class="page__content">
    <div class="container">
      <?php $get_color       = get_field('color'); ?>
      <div class="about-heading" style="background: <?= $get_color ?>">
        <h1 class="about-heading__title"><?= get_field('sub') ?></h1>

        <?php
        if (have_rows('sub_menu_list')): ?>
          <div class="row">
            <div class="col-md-3">
              <?php
              $get_field_sub2 = get_field('sub2');
              if ($get_field_sub2): ?>
                <div class="about-heading__sub"><?= $get_field_sub2 ?></div>
              <?php endif ?>

            </div>
            <div class="col-md-9">

              <ul class="aboutv2__list">
                <?php
                while (have_rows('sub_menu_list')) : the_row();
                  $i++;
                  $title = get_sub_field('title');
                  $link = get_sub_field('link');
                ?>
                  <li class="wow fadeInUp">
                    <a href="<?= $link ?>"><?= $title ?></a>
                  </li>
                <?php endwhile; ?>
              </ul>

            </div>
          </div>
        <?php else: ?>
          <?php
          $get_field_sub2 = get_field('sub2');
          if ($get_field_sub2): ?>
            <div class="about-heading__sub h2"><?= $get_field_sub2 ?></div>
          <?php endif ?>
        <?php
        endif;
        ?>
      </div>
      <hr>

      <?php
      if (have_rows('items')): $i = 0 ?>
        <ul class="about-list">
          <?php
          $i = 0;
          while (have_rows('items')) : the_row();
            $i++;
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
                <?php if ($column == 'half'):
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
                <?php elseif ($column == '1p3'):
                ?>

                  <?php if ($title): ?>
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
                  <div class="col-md-4">
                    <div class="entry-content -half-style">
                      <?= $content3col_content2 ?>
                    </div>
                  </div>
                  <div class="col-md-4">
                    <div class="entry-content -half-style">
                      <?= $content3col_content3 ?>
                    </div>
                  </div>
                <?php elseif ($column == 'full'): ?>
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
                        <?php if ($column == 1): ?>
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

          <?php
          $get_the_content = get_the_content();
          ?>
          <?php if ($get_the_content): ?>
            <li class="wow fadeInUp">
              <div class="entry-content">
                <?php the_content() ?>
              </div>
            </li>
          <?php endif; ?>

        </ul>
      <?php endif;
      ?>


    <?php endwhile; ?>


    <?php
    // Phân trang ở PAge : Static Front Page
    if (get_query_var('paged')) $paged = get_query_var('paged');
    elseif (get_query_var('page')) $paged = get_query_var('page');
    else $paged = 1;

    $projectCatID = get_field('project-cat');

    $custom_query_args = array(
      'post_type' => 'project',
      'posts_per_page' => 9,
      'paged' => $paged,
      'tax_query' => array(
        array(
          'taxonomy' => 'project_cat',
          'field'    => 'term_id',
          'terms'    => $projectCatID,
        ),
      ),
    );
    $wp_query = new WP_Query($custom_query_args);

    if ($wp_query->have_posts()) :
      while ($wp_query->have_posts()) : $wp_query->the_post(); ?>
        <?php get_template_part('template-parts/content', 'archive-project'); ?>
        <hr>
    <?php
      endwhile;
      dntheme_paging_nav();
      wp_reset_postdata(); // reset the query
    endif;
    ?>


    <?php
    if (have_rows('menu_link', $get_the_ID)):  ?>
      <ul class="page-list">
        <?php while (have_rows('menu_link', $get_the_ID)) : the_row();
          $get_title = get_sub_field('title');
          $get_link = get_sub_field('link');
        ?>
          <li><a href="<?= $get_link ?>"><?= $get_title ?></a></li>
        <?php endwhile; ?>
      </ul>
    <?php endif; ?>
    </div>
  </div>




  <?php get_footer();
