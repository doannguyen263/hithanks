<?php
/**
 * The template for displaying the footer
 *
 * Contains the closing of the #content div and all content after.
 *
 * @package    WordPress
 * @subpackage Dntheme
 * @version 1.0
 */
?>

<footer class="footer">
  <div class="container-fluid">
    <div class="sc__wrap">
      <div class="row">
        <div class="col-md-5">
          <?php $logo_img = get_field('logo_full', 'option'); ?>
          <div class="logo mb-3 mb-md-0 text-center text-md-start">
              <a href="<?php echo site_url(); ?>">
                <?php echo wp_get_attachment_image( $logo_img, 'full' ); ?>
              </a>
          </div>
        </div>

        <div class="col-md-7 ">
          <div class="footer-widget row justify-content-center justify-content-md-end gx-4 gy-3 g-lg-5">
            <?php dynamic_sidebar( 'footer' ); ?>
          </div>
        </div>

      </div>
    </div>
  </div>
</footer>

<div class="copyright text-end">
  <div class="container-fluid"><?php the_field('copyright','option'); ?></div>
</div>

<nav id="menu__mobile" class="nav__mobile">
  <div class="nav__mobile__header">
    <div class="nav__mobile__logo">
      <?php
      $logo_img = get_field('logo', 'option');?>
      <a href="<?php echo site_url(); ?>" class="stretched-link">
        <?php echo wp_get_attachment_image( $logo_img, 'full' ); ?>
      </a>
    </div>
    <div class="ms-auto ps-5 py-3 mburger__wrap position-relative zindex-1">
        <a href="#menu__mobile" class="mburger stretched-link">
          <span><span class="icon-bar"></span><span class="icon-bar"></span><span class="icon-bar"></span></span>
        </a>
    </div>
  </div>
  <div class="nav__mobile__content">
    <div class="el__wrap">
      <?php
        wp_nav_menu(
          array(
              'theme_location'  => 'mb',
              'container_class' => 'nav__mobile__menu',
              'menu_class'      => 'nav__mobile--ul',
          ));
      ?>
      <!-- <div class="el-language d-flex mt-3 pb-3">
        <p class="flex-grow-1 text-uppercase"><?php _e('Ngôn ngữ','dntheme'); ?></p>
        <div class="ms-auto">
          <?php //dntheme_get_wpml(); ?>
          </div>
      </div> -->
    </div>
  </div>

</nav>

</div><!-- End wrapper -->

<?php wp_footer(); ?>
</body>
</html>
