<?php
get_header(); // Include the header.

$paged = (get_query_var('paged')) ? absint(get_query_var('paged')) : 1;

$args = array(
    'post_type' => 'artwork_submissions',
    'posts_per_page' => 10,
    'paged' => $paged,
);

// The Query
$query = new WP_Query($args);

// Check if there are posts to display.
if ($query->have_posts()) : 

    echo '<div class="artworks-archive">'; // A container for posts.

    // Start the Loop.
    while ($query->have_posts()) : $query->the_post();
        // This is where you display each post.
        echo '<div class="artwork-entry">';
        // Display the featured image if it exists.
        if (has_post_thumbnail()) {
            the_post_thumbnail('thumbnail');
        }
        // Display the title as a link to the single post.
        echo '<h2><a href="' . esc_url(get_permalink()) . '">' . get_the_title() . '</a></h2>';
        // Optionally, display the excerpt or full content.
        the_content(); 
        echo '</div>';
    endwhile;

    // Pagination.
    echo paginate_links(array(
        'total' => $query->max_num_pages,
        'mid_size'  => 2,
        'prev_text' => __('Back', 'textdomain'),
        'next_text' => __('Next', 'textdomain'),
    ));

    echo '</div>'; // Close the .artworks-archive container.

else : 
    // If no posts are found, include the content-none.php template.
    get_template_part('content', 'none');
endif; 

wp_reset_postdata(); // Always reset postdata after a custom WP_Query.

get_footer(); // Include the footer.
?>
