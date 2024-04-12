<?php
/**
 * Template Name: Weekly Challenge Template
 * @package Theme Freesia
 * @subpackage Pixgraphy
 * @since Pixgraphy 1.0
 */
get_header();

$current_theme = get_current_week_theme();

$daysUntilEnd = 7 - date('w'); // Assuming the week ends on Sunday

# "<h2>Weekly Challenge: $current_theme</h2>";
echo "<h5>Time Remaining: $daysUntilEnd Day(s)</h5>";

if (is_user_logged_in()) {
    echo '<div class="quick-links">';
    echo '<a href="' . esc_url(admin_url('post-new.php?post_type=weekly_challenges')) . '" class="quick-link">Upload Your Art</a>';
    echo '</div>';
    echo '<br><br>';
} else {
    echo '<p>You need to <a href="' . wp_login_url(get_permalink()) . '">login</a> or <a href="' . wp_registration_url() . '">register</a> to upload your art.</p>';
}

echo '<h2>Latest Challenge Submissions</h2>';
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
);
$the_query = new WP_Query($args);

if ($the_query->have_posts()) {
    echo '<div class="dashboard-container">';
    while ($the_query->have_posts()) {
        $the_query->the_post();

        $name = get_field('name');
        $upload_image_description = get_field('upload_image_description');
        $image = get_field('image'); // Assuming this returns an array with the image details

        // Make only the image and the title clickable
        echo '<div class="dashboard-item">';
        echo '<a href="' . get_the_permalink() . '">';
        if ($image) {
            // Assuming $image is an array. If $image is an ID, use wp_get_attachment_image() instead
            echo '<img src="' . esc_url($image['url']) . '" alt="' . esc_attr($image['alt']) . '" />';
        }
        echo '<h3>' . esc_html($name) . '</h3>';
        echo '</a>';

        if ($upload_image_description) {
            echo '<div>' . $upload_image_description . '</div>';
        }

        echo '<p>By: ' . get_the_author() . '</p>';
                echo do_shortcode('[yasr_overall_rating]');  
                echo get_the_date('Y-m-d');
        echo '</div>'; // Close dashboard-item
     
    }
    echo '</div>'; // Close dashboard-container
} else {
    echo '<p>No submissions to show for this week.</p>';
}
wp_reset_postdata();

echo '<h2>Weekly Winners Spotlight</h2>';
echo do_shortcode('[yasr_ov_ranking rows="2"]');

get_footer();
?>