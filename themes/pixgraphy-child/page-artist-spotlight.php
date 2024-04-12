<?php
/**
 * Template Name: Artist Spotlight
 *
 * @package Theme Freesia
 * @subpackage Pixgraphy
 * @since Pixgraphy 1.0
 */
get_header();


render_artist_setting();
// Function to render the weekly theme settings form
function render_artist_setting() {
    // Check if the current user can manage options (moderator or higher)
    if (current_user_can('moderate_comments')) {
        // Render the button with a link to the weekly theme settings page
        echo '<div class="quick-links">';
        echo '<a href="' . admin_url('edit.php?post_type=artists') . '" class="quick-link">Artists Spotlight Edit</a> <br><br>';
        echo '</div>';
    }
}

// Attempt to retrieve the weekly featured artist ID from the transient
$featured_artist_id = get_transient('weekly_featured_artist');

if (false === $featured_artist_id) {
    // If no transient exists or it's expired, query for a new random artist
    $args = array(
        'post_type' => 'artists', 
        'posts_per_page' => 1,
        'orderby' => 'rand',
    );

    $random_artist_query = new WP_Query($args);

    if ($random_artist_query->have_posts()) {
        $random_artist_query->the_post();
        $featured_artist_id = get_the_ID();

        // Store the newly fetched artist ID in a transient for a week
        set_transient('weekly_featured_artist', $featured_artist_id, WEEK_IN_SECONDS);
    }
    wp_reset_postdata();
}

// Display the featured artist if we have one
if (false !== $featured_artist_id) {
    // Fetch the artist post by ID
    $post = get_post($featured_artist_id);
    setup_postdata($post);

    echo '<section class="artist-spotlight">';
    echo '<h2>Artist Spotlight:  ' . get_the_title() . '</h2>';
    if (has_post_thumbnail()) {
        // Display the artist's portrait or representative image
        the_post_thumbnail('full', ['class' => 'artist-image']);
    }
    // Display the artist's bio or description
    echo '<br><br><br><br>';
    echo '<section class="artist-featured-post">';
    echo '<h2>Artists Bio:</h2>';
    echo '<div class="artist-featured-post-content">';
    // Output the title of the featured post as a link to the post
    echo '<h3><a ' . get_the_title() . '</a></h3>';
    // Output the content of the featured post
    the_content();
    echo '</div>';
    echo '</section>';

    wp_reset_postdata();
} else {
    echo '<p>No featured artist this week. Check back next week for more!</p>';
    
}

// Most Commented Artwork of the Month- query
$date_query = array(
    array(
        'after'     => '1 month ago',
        'inclusive' => true,
    ),
);
$args = array(
    'post_type'      => 'artwork_submissions', 
    'posts_per_page' => 1, // We only want the top one
    'orderby'        => 'comment_count',
    'order'          => 'DESC',
    'date_query'     => $date_query,
    'comment_count'  => array(
        'value'   => '1',
        'compare' => '>=',
    ),
);
// $most_commented_query = new WP_Query($args);

// if ($most_commented_query->have_posts()) : 
//     while ($most_commented_query->have_posts()) : $most_commented_query->the_post();
//         echo '<section class="most-commented-artwork">';
//         echo '<h2>Most Commented Artwork of the Month</h2>';
//         // Output the title and whatever content you want to display for the artwork
//         the_title('<h3>', '</h3>');
//         the_content();
//         echo '</section>';
//     endwhile;
// else:
//     echo '<p>No artworks have received comments in the last month.</p>';
// endif;
// wp_reset_postdata();

// Display comments section
comments_template();

get_footer();
?>
