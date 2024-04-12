<?php


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
</style>

<div id="primary" class="content-area">
    <main id="main" class="site-main">
        <div class="gallery-wrapper">

            <?php
            // Custom query to fetch posts from the 'artwork_submissions' CPT
            $artwork_submissions = new WP_Query(array(
                'post_type' => 'artwork_submissions',
                'posts_per_page' => -1, // Display all submissions
            ));

            if ($artwork_submissions->have_posts()) {
                while ($artwork_submissions->have_posts()) {
                    $artwork_submissions->the_post(); ?>
                    <div class="gallery-card">
                        <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
                            <div class="entry-content clearfix">
                                <?php
                                if (has_post_thumbnail()) {
                                    the_post_thumbnail('large', array('class' => 'gallery-thumbnail'));
                                } ?>
                                <h2 class="gallery-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
                                <?php the_excerpt(); ?>
                            </div>
                        </article>
                    </div><!-- .gallery-card -->
                <?php }
                // Restore global post data
                wp_reset_postdata();
            } else { ?>
                <h1 class="entry-title"> <?php esc_html_e('No Artwork Submissions Found.', 'pixgraphy'); ?> </h1>
            <?php } ?>
        </div><!-- .gallery-wrapper -->
    </main><!-- #main -->
</div><!-- #primary -->

<?php get_sidebar();
get_footer(); ?>
