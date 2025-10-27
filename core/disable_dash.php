<?php
//* REMOVE DASHBOARD WELCOME
remove_action('welcome_panel', 'wp_welcome_panel');
// */


//* REMOVE ADMIN DASHBOARD WIDGETS

// Create the function to use in the action hook
function remove_default_dashboard_widgets() {

    // Main column:
    remove_meta_box( 'dashboard_browser_nag', 'dashboard', 'normal' );
    remove_meta_box( 'dashboard_right_now', 'dashboard', 'normal' );
    remove_meta_box( 'dashboard_recent_comments', 'dashboard', 'normal' );
    remove_meta_box( 'dashboard_incoming_links', 'dashboard', 'normal' );
    remove_meta_box( 'dashboard_plugins', 'dashboard', 'normal' );


    // Side Column:
    remove_meta_box( 'dashboard_quick_press', 'dashboard', 'side' );
    remove_meta_box( 'dashboard_recent_drafts', 'dashboard', 'side' );
    remove_meta_box( 'dashboard_primary', 'dashboard', 'side' );
    remove_meta_box( 'dashboard_secondary', 'dashboard', 'side' );

}

// Hook into the 'wp_dashboard_setup' action to register our function
add_action('wp_dashboard_setup', 'remove_default_dashboard_widgets' );

// */
