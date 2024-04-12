<?php
/**
 * Template Name: Weekly Challenge Template
 * @package Theme Freesia
 * @subpackage Pixgraphy
 * @since Pixgraphy 1.0
 */
get_header();
?>

<style>
    .gallery-wrapper {
        display: flex;
        flex-wrap: wrap;
        justify-content: space-between;
        margin: -15px; /* Negative margin to counteract padding */
    }

    .gallery-card {
        flex: 0 0 calc(33.333% - 30px); /* Adjust as needed for spacing */
        padding: 15px; /* Spacing between cards */
        box-sizing: border-box;
        transition: transform 0.3s ease-in-out;
        border: 1px solid #ddd; /* Light border around each card */
        border-radius: 5px; /* Rounded corners for the cards */
    }

    .gallery-card:hover {
        transform: scale(1.05); /* Scale up slightly on hover */
    }

    .gallery-thumbnail {
        width: 100%;
        height: auto;
        border-radius: 5px; /* Rounded corners for the images */
        overflow: hidden; /* Hide overflowing parts of the image */
    }

    .gallery-title {
        margin-top: 10px;
        font-size: 18px;
        font-weight: bold;
    }

    .gallery-description {
        margin-top: 10px;
        font-size: 14px;
    }

    .gallery-author {
        margin-top: 5px;
        font-size: 12px;
        color: #888;
    }
</style>

<?php
$current_theme = get_current_week_theme();

$daysUntilEnd = 7 - date('w'); // Assuming the week ends on Sunday

# "<h2>Weekly Challenge: $current_theme</h2>";
echo "<h2>Time Remaining: $daysUntilEnd Day(s)</h2>";

render_weekly_theme_settings_form();
// Function to render the weekly theme settings form
function render_weekly_theme_settings_form() {
    // Check if the current user can manage options (moderator or higher)
    if (current_user_can('moderate_comments')) {
        // Render the button with a link to the weekly theme settings page
        echo '<div class="quick-links">';
        echo '<h3>Weekly Theme Settings</h3>';
        echo '<a href="' . admin_url('admin.php?page=weekly-theme-settings') . '" class="quick-link">Edit Weekly Theme</a> <br><br>';
        echo '</div>';
    }
}


if (is_user_logged_in()) {
    echo '<div class="quick-links">';
    echo '<a href="' . esc_url(admin_url('post-new.php?post_type=weekly_challenges')) . '" class="quick-link">Upload Your Art</a>';
    echo '</div>';
    echo '<br><br>';
} else {
    echo '<p>You need to <a href="' . wp_login_url(get_permalink()) . '">login</a> or <a href="' . wp_registration_url() . '">register</a> to upload your art.</p>';
}

 echo '<h2>Latest Challenge Submission for the theme: ' . get_current_week_theme() . '</h2>';
$args = array(
    'post_type' => 'weekly_challenges',
    'posts_per_page' => -1,
    'meta_query' => array(
        array(
            'key' => 'challenge_theme',
            'value' => get_current_week_theme(),
            'compare' => '='
            
        ),
    ),
    'date_query'     => array(
        array(
            'after'     => '7 days ago', // Posts that are 7 days old or newer
            'inclusive' => true,
        ),
    ),
);
$the_query = new WP_Query($args);
if ($the_query->have_posts()) {
    echo '<div class="gallery-wrapper">';
    while ($the_query->have_posts()) {
        $the_query->the_post();

        // Check if the post has a featured image
        if (has_post_thumbnail()) {
            $name = get_field('name');
            $upload_image_description = get_field('upload_image_description');
            $image = get_field('image'); // Assuming this returns an array with the image details

            // Make only the image and the title clickable
            echo '<div class="gallery-card">';
            echo '<a href="' . get_the_permalink() . '">';
            // Display the featured image
            the_post_thumbnail('thumbnail', array('class' => 'gallery-thumbnail'));
            echo '<h3 class="gallery-title">' . esc_html($name) . '</h3>';
            echo '</a>';

            if ($upload_image_description) {
                echo '<div class="gallery-description">' . $upload_image_description . '</div>';
            }

            echo '<p class="gallery-author">By: ' . get_the_author() . '</p>';
            echo do_shortcode('[yasr_visitor_votes]');  
            echo get_the_date('Y-m-d') . " ";
            echo  get_field('challenge_theme');
            echo '</div>'; // Close gallery-card
        }
    }
    echo '</div>'; // Close gallery-wrapper
} else {
    echo '<p>No submissions to show for this week.</p>';
}
wp_reset_postdata();

echo '<h2>Weekly Winners Spotlight</h2>'; #filtering the post by category, date and printing only 1 row is paywalled by this plugin
echo do_shortcode('[yasr_most_or_highest_rated_posts rows="1"]');


get_footer();
?>
