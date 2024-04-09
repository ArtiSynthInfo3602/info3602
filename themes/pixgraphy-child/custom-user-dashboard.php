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
$recent_posts_query = new WP_Query($args);

// Get the most rated posts shortcode output
$most_rated_posts_output = do_shortcode('[yasr_most_or_highest_rated_posts rows="1"]');

// Display the custom user dashboard
?>
<style>
/* Add to your style.css */
body, h1, h2, h3, h4, h5, h6, .dashboard-container {
    font-family: 'Open Sans', sans-serif; /* Example font */
}

:root {
    --primary-color: #007bff; /* Primary color */
    --secondary-color: #6c757d; /* Secondary color */
}

.dashboard-container {
    color: var(--secondary-color);
}

a, .quick-link {
    color: var(--primary-color);
}

    .dashboard-container {
        display: flex;
        flex-wrap: wrap;
        gap: 20px;
    }

    .dashboard-item {
        flex: 1;
        padding: 20px;
        border: 1px solid #ddd;
        margin-bottom: 20px;
        background-color: #f9f9f9;
    }

    .quick-links a {
        display: inline-block;
        background-color: #0073aa;
        color: #ffffff;
        padding: 10px 15px;
        margin-right: 10px;
        text-decoration: none;
        border-radius: 4px;
    }

    .quick-links a:hover {
        background-color: #005177;
    }
</style>

<div id="primary" class="content-area">
    <main id="main" class="site-main">
        <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
            <header class="entry-header">
                <h1 class="entry-title">Dashboard</h1>
            </header>
            
            <div class="entry-content">
                <div class="dashboard-container">
                <div class="user-avatar">
                            <?php echo get_avatar( $current_user->ID, 96 ); ?>
                        </div>
                    <div class="dashboard-item user-info">
                        <h2>Welcome, <?php echo esc_html($current_user->display_name); ?></h2>
                        <p>Email: <?php echo esc_html($current_user->user_email); ?></p>
                        <?php if (!empty($current_user->user_description)): ?>
                            <p>Bio: <?php echo esc_html($current_user->user_description); ?></p>
                        <?php endif; ?>
                    </div>

                    <div class="dashboard-item recent-posts">
                        <h2>Recent Posts</h2>
                        <?php if ($recent_posts_query->have_posts()): ?>
                            <ul>
                                <?php while ($recent_posts_query->have_posts()): $recent_posts_query->the_post(); ?>
                                    <li><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></li>
                                <?php endwhile; wp_reset_postdata(); ?>
                            </ul>
                        <?php else: ?>
                            <p>No recent posts found.</p>
                        <?php endif; ?>
                    </div>

                    <div class="dashboard-item most-rated">
                        <h2>Top Rated Post</h2>
                        <?php echo $most_rated_posts_output; ?>
                    </div>
                </div>

                <div class="quick-links">
                    <a href="<?php echo esc_url(admin_url('post-new.php')); ?>">Create New Post</a>
                <a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="quick-link">View All Posts</a>
                </div>

            </div>
        </article>
    </main>
</div>

<?php get_footer(); ?>
