<?php
/*
Template Name: Custom User Dashboard
*/

get_header();

// Retrieve current user information
$current_user = wp_get_current_user();

// Retrieve recent posts by the current user
$args = array(
    'author'         => $current_user->ID,
    'posts_per_page' => 5,
);
$recent_posts_query = new WP_Query( $args );

// Get the most liked post
$most_liked_post_args = array(
    'posts_per_page' => 1,
    'meta_key'       => 'wp_ulike_likes',
    'orderby'        => 'meta_value_num',
    'order'          => 'DESC',
);
$most_liked_posts_query = new WP_Query( $most_liked_post_args );

// Get the most commented post
$most_commented_post_args = array(
    'posts_per_page' => 1,
    'orderby'        => 'comment_count',
);
$most_commented_posts_query = new WP_Query( $most_commented_post_args );

// Display the custom user dashboard
?>

<style>
    /* Styling for the dashboard */
    .dashboard-container {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
        gap: 20px;
    }

    .dashboard-item {
        background-color: #f7f7f7;
        padding: 20px;
        border-radius: 8px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    }

    .user-details {
        grid-column: span 3;
    }

    .user-avatar {
        float: left;
        margin-right: 20px;
        border-radius: 50%;
    }

    .user-info p {
        margin: 5px 0;
    }

    .recent-posts,
    .most-liked-post,
    .most-commented-post {
        margin-top: 20px;
    }

    .recent-posts h2,
    .most-liked-post h2,
    .most-commented-post h2 {
        margin-bottom: 10px;
    }

    .recent-posts ul,
    .most-liked-post p,
    .most-commented-post p {
        list-style: none;
        padding: 0;
    }

    .recent-posts ul li,
    .most-liked-post p,
    .most-commented-post p {
        margin-bottom: 10px;
    }

    .recent-posts ul li a,
    .most-liked-post a,
    .most-commented-post a {
        text-decoration: none;
        color: #333;
    }

    .recent-posts ul li a:hover,
    .most-liked-post a:hover,
    .most-commented-post a:hover {
        text-decoration: underline;
    }

    .likes-count {
        color: #777;
        font-size: 14px;
    }

    /* Styling for quick links */
    .quick-links {
        margin-top: 20px;
    }

    .quick-link {
        display: inline-block;
        padding: 10px 20px;
        background-color: #007bff;
        color: #fff;
        text-decoration: none;
        border-radius: 5px;
        margin-right: 10px;
    }

    .quick-link:hover {
        background-color: #0056b3;
    }
</style>

<div id="primary" class="content-area">
    <main id="main" class="site-main">
        <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
            <header class="entry-header">
                <h1> Here you will find insights on your activies.</h1>
            </header>

            <div class="entry-content dashboard-container">
                <!-- User details -->
                <div class="dashboard-item user-details">
                    <h2>My information</h2>
                    <div class="user-avatar">
                        <?php echo get_avatar( $current_user->ID, 96 ); ?>
                    </div>
                    <div class="user-info">
                        <p><strong>Username:</strong> <?php echo $current_user->user_login; ?></p>
                        <p><strong>Email:</strong> <?php echo $current_user->user_email; ?></p>
                    </div>
                </div>

                <!-- Recent Posts -->
                <div class="dashboard-item recent-posts">
                    <h2>Recent Posts</h2>
                    <ul>
                        <?php if ( $recent_posts_query->have_posts() ) :
                            while ( $recent_posts_query->have_posts() ) : $recent_posts_query->the_post(); ?>
                                <li>
                                    <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                                    <?php
                                        // Get the post ID
                                        $post_id = get_the_ID();

                                        // Get the number of likes for the post
                                        $likes_count = function_exists('get_ulike_count') ? get_ulike_count($post_id) : 0;
                                        
                                        // Display the number of likes
                                        echo '<span class="likes-count"> - Likes: ' . $likes_count . '</span>';
                                    ?>
                                </li>
                            <?php endwhile;
                            wp_reset_postdata();
                        else : ?>
                            <li>No recent posts found.</li>
                        <?php endif; ?>
                    </ul>
                </div>

                <!-- Most Liked Post -->
                <div class="dashboard-item most-liked-post">
                    <h2>Most Liked Post</h2>
                    <?php if ( $most_liked_posts_query->have_posts() ) :
                        while ( $most_liked_posts_query->have_posts() ) : $most_liked_posts_query->the_post(); ?>
                            <p>
                                <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                                <?php
                                    // Get the number of likes for the post
                                    $likes_count = function_exists('get_ulike_count') ? get_ulike_count(get_the_ID()) : 0;
                                    
                                    // Display the number of likes
                                    echo '<span class="likes-count"> - Likes: ' . $likes_count . '</span>';
                                ?>
                            </p>
                        <?php endwhile;
                        wp_reset_postdata();
                    else : ?>
                        <p>No most liked post found.</p>
                    <?php endif; ?>
                </div>

                <!-- Most Commented Post -->
                <div class="dashboard-item most-commented-post">
                    <h2>Most Commented Post</h2>
                    <?php if ( $most_commented_posts_query->have_posts() ) :
                        while ( $most_commented_posts_query->have_posts() ) : $most_commented_posts_query->the_post(); ?>
                            <p>
                                <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                                <?php
                                    // Get the number of comments for the post
                                    $comment_count = get_comments_number();
                                    
                                    // Display the number of comments
                                    echo '<span class="likes-count"> - Comments: ' . $comment_count . '</span>';
                                ?>
                            </p>
                        <?php endwhile;
                        wp_reset_postdata();
                    else : ?>
                        <p>No most commented post found.</p>
                    <?php endif; ?>
                </div>
            </div><!-- .entry-content -->

            <!-- Quick Links -->
            <div class="quick-links">
                <a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="quick-link">View All Posts</a>
                <a href="<?php echo esc_url( admin_url( 'post-new.php' ) ); ?>" class="quick-link">Add New Post</a>
            </div>
        </article><!-- #post-<?php the_ID(); ?> -->
    </main><!-- #main -->
</div><!-- #primary -->

<?php
get_footer();
?>