<?php
/**
 * The template for displaying 404 pages (not found)
 *
 * @package    WordPress
 * @subpackage Dntheme
 * @version 1.0
 */
global $dn_options;
get_header(); ?>

<section class="page__header text-center">
  <div class="container">
    <div class="text-center">
			<h1 class="exception__code entry-title page__header__title">404</h1>
			<p class="exception__text mt-4">ERROR</p>
		</div>
  </div>
</section>

<div class="wrap__page">
	<div class="archive__content">
	  <div class="container">
	      <section class="error-404 not-found text-center">

					<header class="page-header">
						<p class="page-title"><?php _e( 'Oops!', 'dntheme' ); ?></p>
						<p><?php _e('Lỗi không tìm thấy trang','dntheme'); ?></p>
					</header><!-- .page-header -->
					<div class="page-content entry-content">
						<p><?php _e( "Có vẻ như các trang mà bạn đang cố gắng tiếp cận không tồn tại nữa hoặc có thể nó vừa di chuyển.", 'dntheme' ); ?></p>
						<a href="<?php echo home_url(); ?>" class="return__home"><?php _e( 'Back Homepage', 'dntheme' ); ?></a>
					</div><!-- .page-content -->

				</section><!-- .error-404 -->
	    </div>
	</div>
</div>

<?php get_footer();
