<?php
/**
 * The sidebar containing the main widget area
 *
 * @package    WordPress
 * @subpackage Dntheme
 * @version 1.0
 */

// if ( ! is_active_sidebar( 'sidebar-1' ) ) {
// 	return;
// }
?>

<aside class="widget-area widget__left widget__fix">
	<div class="sidebar__inner">
		<?php dynamic_sidebar( 'blog' ); ?>
	</div>
</aside><!-- #secondary -->
