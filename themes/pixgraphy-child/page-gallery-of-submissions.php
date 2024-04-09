<?php
/**
 * Template Name: Gallery of Submissions
 *
 * This template displays all submissions for the weekly challenges.
 */

get_header(); ?>

<div id="primary" class="content-area">
    <main id="main" class="site-main">

    <?php
    // Query for all submissions 
    $args = array(
        'post_type' => 'weekly_challenges', // Replace with your CPT name
        'posts_per_page' => -1, // Show all posts
    );

    $submissions_query = new WP_Query($args);

    if ($submissions_query->have_posts()) {
        echo '<div class="submissions-gallery">';
        while ($submissions_query->have_posts()) {
            $submissions_query->the_post();
            
            // Display each submission
            echo '<div class="submission-item">';
            if (has_post_thumbnail()) {
                the_post_thumbnail();
            }
            echo '<h2><a href="' . get_permalink() . '">' . get_the_title() . '</a></h2>';
            the_excerpt(); // Or the_content() if you want the full content
            echo '</div>';
        }
        echo '</div>';
    } else {
        echo '<p>No submissions to display.</p>';
    }

    wp_reset_postdata();
    ?>

    </main><!-- #main -->
</div><!-- #primary -->

<?php
get_footer();
