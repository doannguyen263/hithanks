<?php

/**
 * Template part for displaying posts with excerpts
 *
 * Used in Search Results and for Recent Posts in Front Page panels.
 *
 *
 * @package    WordPress
 * @subpackage Dntheme
 * @version 1.0
 */

global $stt;
?>
<div class="el-item el-item--project">
  <div class="row">
    <div class="col-5">
      <div class="el-item__thumb ratio ratio-16x9">
        <?php the_post_thumbnail('thumbnail', array('class' => 'img-fluid', 'alt'   => get_the_title())); ?>
      </div>
    </div>
    <div class="col-7">
      <div class="el-item__meta">
        <h3 class="el-item__title"><a href="<?php the_permalink(); ?>" class="stretched-link"><?php the_title() ?></a></h3>
        <div class="el-item__excerpt"><?= get_field('sub') ?></div>
      </div>
    </div>
  </div>
</div>