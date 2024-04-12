<?php
/**
 * Template Name: Gallery Template
 *
 * @package Theme Freesia
 * @subpackage Pixgraphy
 * @since Pixgraphy 1.0
 */

get_header(); ?>

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

    .new-post-button {
        margin-top: 20px;
    }

    /* Styling for the button */
    .quick-link {
        background-color: #ff0000; /* Red background color */
        color: #ffffff; /* White text color */
        padding: 10px 20px; /* Padding around the button text */
        border-radius: 5px; /* Rounded corners */
        text-decoration: none; /* Remove underline */
        transition: background-color 0.3s ease-in-out; /* Smooth transition */
    }

    /* Hover effect */
    .quick-link:hover {
        background-color: #cc0000; /* Darker red on hover */
    }
</style>

<div id="primary" class="content-area">
    <main id="main" class="site-main">
        <div class="gallery-wrapper">

            <?php
            // Get all registered post types
            $post_types = get_post_types(array('public' => true), 'names');

            foreach ($post_types as $post_type) {
                // Exclude attachments, as they're handled separately
                if ($post_type !== 'attachment') {
                    // Custom query to fetch posts from each post type
                    $posts = new WP_Query(array(
                        'post_type' => $post_type,
                        'posts_per_page' => -1, // Display all posts
                    ));

                    if ($posts->have_posts()) {
                        while ($posts->have_posts()) {
                            $posts->the_post();
                            // Check if the post has a featured image and no text content
                            if (has_post_thumbnail() && empty(get_the_content())) { ?>
                                <div class="gallery-card">
                                    <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
                                        <div class="entry-content clearfix">
                                            <?php the_post_thumbnail('large', array('class' => 'gallery-thumbnail')); ?>
                                            <h2 class="gallery-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
                                        </div>
                                    </article>
                                </div><!-- .gallery-card -->
                            <?php }
                        }
                        // Restore global post data
                        wp_reset_postdata();
                    }
                }
            } ?>

        </div><!-- .gallery-wrapper -->

        <!-- Section for creating a new post -->
        <div class="new-post-button">
            <?php
            if (is_user_logged_in()) {
                echo '<p>Click the button below and set a featured image to display your art in the gallery:</p>';
                echo '<div class="quick-links">';
                echo '<a href="' . esc_url(admin_url('post-new.php?post_type=artwork_submissions')) . '" class="quick-link">Upload Your Art</a>';
                echo '</div>';
            } else {
                // Prompt non-logged-in users to log in or register
                echo '<p>You need to <a href="' . wp_login_url(get_permalink()) . '">login</a> or <a href="' . wp_registration_url() . '">register</a> to upload your art.</p>';
            }
            ?>
        </div>

    </main><!-- #main -->
</div><!-- #primary -->

<?php get_sidebar();
get_footer(); ?>
