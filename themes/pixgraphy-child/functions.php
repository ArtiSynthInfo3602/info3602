<?php
add_action( 'wp_enqueue_scripts', 'pixgraphy_enqueue_styles' );
function pixgraphy_enqueue_styles() {
	wp_enqueue_style( 'parent-style', get_template_directory_uri() . '/style.css' );
}
function pixgraphy_child_custom_menu_items($items, $args) {
    // Check if the menu is the custom menu
    if ($args->theme_location == 'primary') {
        if (is_user_logged_in()) {
            // Remove Login and Register items for logged-in users
            $items = str_replace(array('Login', 'Register'), '', $items);
            // Add Logout link for logged-in users
            $items .= '<li><a href="' . wp_logout_url(home_url()) . '">Logout</a></li>';
        } else {
            // Remove My Dashboard item for non-logged-in users
            $items = str_replace('My Dashboard', '', $items);
            // Add Login and Register links for non-logged-in users
            $items .= '<li><a href="' . wp_login_url() . '">Login</a></li>';
            $items .= '<li><a href="' . wp_registration_url() . '">Register</a></li>';
        }
    }
    return $items;
}
add_filter('wp_nav_menu_items', 'pixgraphy_child_custom_menu_items', 10, 2);

?>
