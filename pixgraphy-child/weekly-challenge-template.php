<?php
/**
 * Template Name: Weekly Challenge Template
 *
 * @package Theme Freesia
 * @subpackage Pixgraphy
 * @since Pixgraphy 1.0
 */
get_header();

$current_theme = get_current_week_theme();
 
$daysUntilEnd = 7 - date('w'); // Assuming the week ends on Sunday

echo "<h2>Weekly Challenge: $current_theme</h2>";
echo "<p>Days Remaining: $daysUntilEnd</p>";

// Provide a link for logged-in users to create a new challenge entry
if (is_user_logged_in()) {
    echo '<div class="quick-links">';
    // Change the post_type query parameter to your weekly challenges CPT
    echo '<a href="' . esc_url(admin_url('post-new.php?post_type=weekly_challenges')) . '" class="quick-link">Upload Your Art</a>';
    echo '</div>';
} else {
    // Prompt non-logged-in users to log in or register
    echo '<p>You need to <a href="' . wp_login_url(get_permalink()) . '">login</a> or <a href="' . wp_registration_url() . '">register</a> to upload your art.</p>';
}

// Display the submissions for the current weekly challenge
echo '<h2>Weekly Challenge Submissions</h2>';
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
        echo '<div class="dashboard-item">';
        echo '<h3><a href="' . get_the_permalink() . '">' . get_the_title() . '</a></h3>';
        the_post_thumbnail();
        echo '<p>By: ';
        the_author_posts_link();
        echo '</p>';
        // If you're using a like system, you can display the like count here
        echo '</div>';
    }
    echo '</div>';
} else {
    echo '<p>No submissions to show for this week.</p>';
}
wp_reset_postdata();

echo '<h2>Weekly Winners Spotlight</h2>';
echo '<p>Winners spotlight functionality not implemented in this template.</p>';

get_footer();
