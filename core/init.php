<?php
require_once get_template_directory() . '/core/disable_dash.php';

if( function_exists('acf_add_options_page') ) {

	acf_add_options_page(array(
		'page_title' 	=> 'Theme General Settings',
		'menu_title'	=> 'Theme Settings',
		'menu_slug' 	=> 'theme-general-settings',
		'capability'	=> 'edit_posts',
		'redirect'		=> false
	));
}

$show_admin_bar = get_field('adminbar', 'option');

if (!is_user_logged_in() || isset($show_admin_bar) && $show_admin_bar == FALSE){
	add_filter('show_admin_bar', '__return_false');
}

// Add style in admin
add_action( 'admin_enqueue_scripts', 'load_custom_admin_style' );
function load_custom_admin_style(){
    wp_enqueue_style( 'admin_css', get_bloginfo('template_directory'). '/core/admin/style-admin.css' );
}

function load_custom_admin_scripts($hook) {
    // Only add to the edit.php admin page.
    // See WP docs.
    $screen = get_current_screen();

    wp_enqueue_script( 'admin_js', get_bloginfo('template_directory'). '/core/admin/script-admin.js' );
}

add_action('admin_enqueue_scripts', 'load_custom_admin_scripts');

function login_css() {
	wp_enqueue_style( 'login_css', get_bloginfo('template_directory'). '/core/admin/login.css' );
}
add_action('login_head', 'login_css');

if( is_admin()){
	$disallow_file_edit = get_option('options_disallow_file_edit');
	$disallow_file_mods = get_option('options_disallow_file_mods');

	if($disallow_file_edit) defined('DISALLOW_FILE_MODS') or define('DISALLOW_FILE_MODS', 'true');
	if($disallow_file_mods) defined('DISALLOW_FILE_EDIT') or define('DISALLOW_FILE_EDIT', 'true');
}
