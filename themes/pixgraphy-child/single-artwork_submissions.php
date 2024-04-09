<?php get_header(); ?>

<div class="container single-artwork-submission">
    <?php 
    while (have_posts()) : the_post(); 
        echo '<h1>' . get_the_title() . '</h1>'; // Display the post title.
        
        // Display the featured image if it exists
        if (has_post_thumbnail()) {
            the_post_thumbnail('medium'); // Display the featured image at medium size.
        }

        the_content(); // Display the full content of the post.
        
        // Display 'Description' ACF field
        $description = get_field('description');
        if ($description) {
            echo '<div class="acf-description">' . $description . '</div>';
        }

        // Display 'Images' ACF field
        $images = get_field('images');
        if ($images) {
            echo '<div class="acf-images"><img src="' . esc_url($images['url']) . '" alt="' . esc_attr($images['alt']) . '" /></div>';
        }
        
        // Display 'Submission Date' ACF field
        $submission_date = get_field('submission_date_');
        if ($submission_date) {
            echo '<div class="acf-submission-date">Submission Date: ' . esc_html($submission_date) . '</div>';
        }
        
        // Display 'Artist' ACF field (Relationship)
        $artist_post = get_field('artist');
        if ($artist_post) {
            // Assuming return format is Post Object
            echo '<div class="acf-artist">Artist: <a href="' . get_permalink($artist_post->ID) . '">' . esc_html($artist_post->post_title) . '</a></div>';
        }

    endwhile; 
    ?>
</div>

<?php get_footer(); ?>
