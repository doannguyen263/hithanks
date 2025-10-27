<?php 
/**
 * The template related category
 *
 * @package    WordPress
 * @subpackage Dntheme
 * @version 1.0
 */
?>

<article class="related__item">
	<div class="item__thumb">
		<a href="<?php the_permalink(); ?>" class="dnfix__thumb dnfixs5">
			<?php the_post_thumbnail('medium',array( 'class' => 'img-fluid','alt'   => get_the_title() )); ?>
		</a>
	</div><!-- .post-thumbnail -->

	<a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>" class="item__title"><?php the_title(); ?></a>
</article><!-- #post-## -->
