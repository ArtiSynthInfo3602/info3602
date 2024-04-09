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



function custom_login_page_style() {
    ?>
    <style type="text/css">
        /* Hide the WordPress logo */
        #login h1 a, .login h1 a {
            display: none;
        }
    </style>
    <?php
}
add_action('login_enqueue_scripts', 'custom_login_page_style');



function register_weekly_challenges_cpt() {
    $args = array(
        'public' => true,
        'label'  => 'Weekly Challenges',
        'supports' => array('title', 'editor', 'thumbnail', 'comments'),
        // Assuming you want to allow comments for interaction
        'has_archive' => true,
        'menu_icon' => 'dashicons-calendar-alt',
    );
    register_post_type('weekly_challenges', $args);
}
add_action('init', 'register_weekly_challenges_cpt');


function register_artwork_submissions_cpt() {
        $labels = array(
            'new_item' => __('New Submission', 'text_domain'),
  );

    $args = array(
        'public' => true,
        'label'  => 'Artwork Submissions',
        'supports' => array('title', 'editor', 'thumbnail'),
        // Enables the Featured Image support for this CPT
        'has_archive' => true,
        'menu_icon' => 'dashicons-art',
        'rewrite' => array('slug' => 'artwork-submissions', 'with_front' => false),


    );
    register_post_type('artwork_submissions', $args);
}
add_action('init', 'register_artwork_submissions_cpt');


add_action('admin_post_submit_artwork_weekly_challenge', 'handle_weekly_challenge_submission');
add_action('admin_post_nopriv_submit_artwork_weekly_challenge', 'handle_weekly_challenge_submission'); // For logged-out users, if allowed.

function handle_weekly_challenge_submission() {
    // Check for user permission and nonce for security here (if you've added a nonce to your form)

    if (is_user_logged_in()) {
        $current_user = wp_get_current_user();
        
        $post_data = array(
            'post_title'    => sanitize_text_field($_POST['art_title']),
            'post_content'  => '', // If you have content to add, get it from form data
            'post_status'   => 'publish', // Or 'pending' if you want to review submissions
            'post_type'     => 'artwork_submissions', 
            'post_author'   => $current_user->ID,
        );
        
        // Insert the post into the database
        $post_id = wp_insert_post($post_data);
        
        // Check if the post was created successfully
        if ($post_id !== 0) {
            // Handle file upload - Ensure you have the necessary permissions and security checks
            if (!function_exists('media_handle_upload')) {
                require_once(ABSPATH . "wp-admin" . '/includes/image.php');
                require_once(ABSPATH . "wp-admin" . '/includes/file.php');
                require_once(ABSPATH . "wp-admin" . '/includes/media.php');
            }

            // $_FILES['art_image'] will contain the uploaded file
            $attach_id = media_handle_upload('art_image', $post_id);

            // Set as featured image if the upload was successful
            if (!is_wp_error($attach_id)) {
                set_post_thumbnail($post_id, $attach_id);
            }

            // Redirect or notify the user of success
            wp_redirect(home_url('/thank-you')); // Customize the URL as necessary
            exit;
        } else {
            // Handle error
        }
    } else {
        // Handle the case where the user is not logged in, if necessary
    }
}

function get_weekly_themes() {
    return [
        'Theme 1',
        'Theme 2',
        'Theme 3',
        // Add as many themes as you like.
    ];
}


function get_current_week_theme() {
    $themes = get_weekly_themes();
    $start_date = new DateTime('2023-01-01'); // The start date of the first theme
    $current_date = new DateTime();
    $week_number = intval($start_date->diff($current_date)->days / 7) % count($themes);
    return $themes[$week_number];
}

function set_post_challenge_theme($post_id) {
    // Check if it's the correct post type to avoid affecting other post types
    if (get_post_type($post_id) === 'weekly_challenges') {
        // Prevent recursion during save_post action
        remove_action('save_post', 'set_post_challenge_theme');

        // Fetch and set the current week's theme as a custom field
        update_post_meta($post_id, 'challenge_theme', get_current_week_theme());

        // Re-hook the action to continue listening for save_post events
        add_action('save_post', 'set_post_challenge_theme');
    }
}
add_action('save_post', 'set_post_challenge_theme');

function create_artist_cpt() {
    $args = array(
        'public' => true,
        'label'  => 'Artists',
        'supports' => array('title', 'editor', 'thumbnail'),
        'menu_icon' => 'dashicons-admin-users',
    );
    register_post_type('artists', $args);
}
add_action('init', 'create_artist_cpt');

//To use this shortcode, simply place [artwork_carousel] anywhere 



function artwork_submission_carousel_shortcode() {
    ob_start();
    $args = array(
        'post_type' => 'artwork_submissions',
        'posts_per_page' => 5,
        'orderby' => 'date',
        'order' => 'DESC',
    );
    $query = new WP_Query($args);

    if ($query->have_posts()) {
        echo '<div class="artwork-carousel">';
        while ($query->have_posts()) {
            $query->the_post();
            echo '<div class="carousel-item">';
            the_post_thumbnail();
            echo '<h3><a href="' . get_permalink() . '">' . get_the_title() . '</a></h3>';
            echo '</div>';
        }
        echo '</div>';
    } else {
        echo '<p>No recent submissions.</p>';
    }
    wp_reset_postdata();

    return ob_get_clean();
}
add_shortcode('artwork_carousel', 'artwork_submission_carousel_shortcode');

function random_featured_artist_shortcode() {
    ob_start();
    $args = array(
        'post_type' => 'artists',
        'posts_per_page' => 1,
        'orderby' => 'rand',
    );
    $query = new WP_Query($args);

    if ($query->have_posts()) {
        while ($query->have_posts()) {
            $query->the_post();
            echo '<div class="featured-artist">';
            the_post_thumbnail();
            echo '<h3>' . get_the_title() . '</h3>';
            the_content();
            echo '</div>';
        }
    } else {
        echo '<p>No artists found.</p>';
    }
    wp_reset_postdata();

    return ob_get_clean();
}
add_shortcode('random_featured_artist', 'random_featured_artist_shortcode');





?>
