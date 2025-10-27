<?php
/**
 * Template Name: Page Register
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

<div class="container">
  <ul class="nav-list d-flex justify-content-end">
      <li value=""><a href="#" class="active"><?php the_title(); ?></a></li>
  </ul>
  <hr>
</div>


<div class="page__content pb-5">
    <div class="container">

      <?php $get_color       = get_field('color'); ?>
      <div class="about-heading" style="background: <?= $get_color ?>">
        <h1 class="about-heading__title"><?= get_field('sub') ?></h1>
      </div>
      <hr>

      <?php
      if ( ! defined( 'ABSPATH' ) ) {
        exit; // Exit if accessed directly.
      }

      do_action( 'woocommerce_before_customer_login_form' ); ?>

      <div class="u-columns col2-set" id="customer_login">

        <div class="u-column2">

          <h2><?php esc_html_e( 'Register', 'woocommerce' ); ?></h2>

          <form method="post" class="woocommerce-form woocommerce-form-register register" <?php do_action( 'woocommerce_register_form_tag' ); ?> >

            <?php do_action( 'woocommerce_register_form_start' ); ?>

            <?php if ( 'no' === get_option( 'woocommerce_registration_generate_username' ) ) : ?>

              <p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
                <label for="reg_username"><?php esc_html_e( 'Username', 'woocommerce' ); ?>&nbsp;<span class="required">*</span></label>
                <input type="text" class="woocommerce-Input woocommerce-Input--text input-text" name="username" id="reg_username" autocomplete="username" value="<?php echo ( ! empty( $_POST['username'] ) ) ? esc_attr( wp_unslash( $_POST['username'] ) ) : ''; ?>" /><?php // @codingStandardsIgnoreLine ?>
              </p>

            <?php endif; ?>

            <p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
              <label for="reg_email"><?php esc_html_e( 'Email address', 'woocommerce' ); ?>&nbsp;<span class="required">*</span></label>
              <input type="email" class="woocommerce-Input woocommerce-Input--text input-text" name="email" id="reg_email" autocomplete="email" value="<?php echo ( ! empty( $_POST['email'] ) ) ? esc_attr( wp_unslash( $_POST['email'] ) ) : ''; ?>" /><?php // @codingStandardsIgnoreLine ?>
            </p>

            <?php if ( 'no' === get_option( 'woocommerce_registration_generate_password' ) ) : ?>

              <p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
                <label for="reg_password"><?php esc_html_e( 'Password', 'woocommerce' ); ?>&nbsp;<span class="required">*</span></label>
                <input type="password" class="woocommerce-Input woocommerce-Input--text input-text" name="password" id="reg_password" autocomplete="new-password" />
              </p>

            <?php else : ?>

              <p><?php esc_html_e( 'A password will be sent to your email address.', 'woocommerce' ); ?></p>

            <?php endif; ?>

            <?php do_action( 'woocommerce_register_form' ); ?>

            <p class="woocommerce-FormRow form-row">
              <?php wp_nonce_field( 'woocommerce-register', 'woocommerce-register-nonce' ); ?>
              <button type="submit" class="woocommerce-Button button" name="register" value="<?php esc_attr_e( 'Register', 'woocommerce' ); ?>"><?php esc_html_e( 'Register', 'woocommerce' ); ?></button>
            </p>

            <?php do_action( 'woocommerce_register_form_end' ); ?>

          </form>

        </div>

      </div>

      <?php do_action( 'woocommerce_after_customer_login_form' ); ?>



    </div>
</div>

<?php endwhile; ?>
<?php get_footer();