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
  <div class="col-sm-8 order-sm-1 mb-2 mb-sm-0">
      <div class="el-item__meta">
        <h3 class="el-item__title"><a href="<?php the_permalink(); ?>" class="stretched-link"><?php the_title() ?></a>
        </h3>
        <div class="el-item__excerpt text__truncate -n3 d-none d-sm-block"><?php dn_excerpt() ?></div>
      </div>
    </div>
    <div class="col-sm-4">
      <div class="el-item__thumb ratio ratio-16x9">
        <!-- <div class="el-item__stt"><?= $stt ?></div> -->
        <?php the_post_thumbnail('large', array('class' => 'img-fluid', 'alt' => get_the_title())); ?>
      </div>
    </div>
   
  </div>
</div>