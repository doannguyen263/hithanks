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
$categories = get_the_category();

global $stt;
?>
<div class="el-item">
  <div class="row">
    <div class="col-4">
      <div class="el-item__thumb ratio ratio-16x9">
        <!-- <div class="el-item__stt"><?= $stt ?></div> -->
        <?php the_post_thumbnail('large', array('class' => 'img-fluid', 'alt' => get_the_title())); ?>
      </div>
    </div>
    <div class="col-8">
      <div class="el-item__meta">
        <h3 class="el-item__title"><a href="<?php the_permalink(); ?>" class="stretched-link"><?php the_title() ?></a>
        </h3>
        <div class="el-item__excerpt"><?php dn_excerpt() ?></div>
        <!-- <div class="el-item__excerpt"><div class="text__truncate -n3"><?php //dn_excerpt() ?></div></div> -->
      </div>
    </div>
  </div>
</div>