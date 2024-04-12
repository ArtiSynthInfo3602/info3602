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

// Latest comments on user's posts
$comments_args = array(
    'post_author__in' => array($current_user->ID),
    'number'          => 5,
    'status'          => 'approve'
);
$comments_query = new WP_Comment_Query;
$comments = $comments_query->query($comments_args);

// User's posts by category breakdown
$category_posts_count = array();
$categories = get_categories();
foreach ($categories as $category) {
    $category_posts_count[$category->name] = count(get_posts(array(
        'author'        => $current_user->ID,
        'category'      => $category->term_id,
        'posts_per_page'=> -1
    )));
}

// User's most active posts
$active_posts_args = array(
    'author'         => $current_user->ID,
    'orderby'        => 'comment_count',
    'order'          => 'DESC',
    'posts_per_page' => 5
);
$active_posts_query = new WP_Query($active_posts_args);


?>

<style>
body, h1, h2, h3, h4, h5, h6, h7, h8, .dashboard-container {
    font-family: 'Open Sans', sans-serif;
    color: #333; /* Dark grey for text for readability */
}

:root {
    --primary-color: #6c757d; /* Neutral tone for a professional look */
    --secondary-color: #495057; /* Complementary dark shade for contrast */
    --accent-color: #007bff; /* A bright accent color for interactive elements */
    --background-light: #f8f9fa; /* Light background to keep the focus on content */
    --card-background: #ffffff; /* White card background to enhance readability */
    --card-shadow: rgba(0, 0, 0, 0.1); /* Soft shadow for depth */
}

.dashboard-container {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
    gap: 20px;
    padding: 20px;
    background-color: var(--background-light);
}

.dashboard-item {
    background-color: var(--card-background);
    padding: 20px;
    border-radius: 10px;
    box-shadow: 0 4px 6px var(--card-shadow);
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}

.dashboard-item:hover {
    transform: translateY(-5px);
    box-shadow: 0 6px 10px var(--card-shadow);
}


a, .quick-link {
    color: var(--primary-color);
    text-decoration: none;
}

.quick-links a {
    background-color: var(--accent-color);
    color: #ffffff;
    padding: 10px 20px;
    border-radius: 5px;
    transition: background-color 0.3s, transform 0.3s;
}

.quick-links a:hover {
    background-color: darken(var(--accent-color), 10%);
    transform: translateY(-3px);
}

.user-avatar-link {
    display: inline-block;
    border-radius: 50%;
    overflow: hidden;
    width: 100px;
    height: 100px;
    border: 3px solid #007bff;
    box-shadow: 0 2px 5px rgba(0,0,0,0.1);
}

.user-avatar {
    width: 100%;
    height: 100%;
}


.entry-header {
    background-color: var(--secondary-color);
    padding: 20px 30px;
    border-radius: 10px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
    text-align: center;
    margin-bottom: 30px;
    background-image: linear-gradient(to right, var(--secondary-color), var(--primary-color)); /* Gradient background for a modern look */
}

.entry-title {
    font-size: 2.5em;
    color: #ffffff;
    font-weight: 700;
    text-transform: uppercase;
    text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.5);
}
</style>

<div id="primary" class="content-area">
    <main id="main" class="site-main">
        <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
            <header class="entry-header">
                <h1>My Dashboard</h1>
            </header>

            <div class="dashboard-container">
                <!-- User Details and Activity -->
                <div class="dashboard-item user-info">
                    <a href="<?php echo esc_url(get_edit_user_link($current_user->ID)); ?>" class="user-avatar-link">
                        <?php echo get_avatar($current_user->ID, 96, '', '', array('class' => 'user-avatar')); ?>
                    </a>
                    <h2>Welcome, <?php echo esc_html($current_user->display_name); ?></h2>
                    <p>Email: <?php echo esc_html($current_user->user_email); ?></p>
                </div>

                <!-- Recent Posts -->
                <div class="dashboard-item recent-posts">
                    <h2>Recent Posts</h2>
                    <ul>
                        <?php if ($recent_posts_query->have_posts()): ?>
                            <?php while ($recent_posts_query->have_posts()): $recent_posts_query->the_post(); ?>
                                <li><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></li>
                            <?php endwhile; wp_reset_postdata(); ?>
                        </ul>
                    <?php else: ?>
                        <p>No recent posts found.</p>
                    <?php endif; ?>
                </div>

                <!-- Most Rated Post -->
                <div class="dashboard-item most-rated">
                    <h2>Top Rated Post</h2>
                    <?php echo $most_rated_posts_output; ?>
                </div>

                <div class="dashboard-item user-categories">
                    <h2>Posts by Category</h2>
                    <ul>
                        <?php foreach ($category_posts_count as $cat_name => $count): ?>
                            <li><?php echo esc_html($cat_name) . ': ' . esc_html($count) . ' Posts'; ?></li>
                        <?php endforeach; ?>
                    </ul>
            </div>


                <!-- Latest Comments -->
                <div class="dashboard-item latest-comments">
                    <h2>Latest Comments on My Posts</h2>
                    <ul>
                        <?php if (!empty($comments)): ?>
                            <?php foreach ($comments as $comment): ?>
                                <li><?php echo $comment->comment_content; ?> - On <a href="<?php echo get_permalink($comment->comment_post_ID); ?>"><?php echo get_the_title($comment->comment_post_ID); ?></a></li>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <p>No recent comments.</p>
                        <?php endif; ?>
                    </ul>
                </div>

                <!-- Most Active Posts -->
                <div class="dashboard-item most-active-posts">
                    <h2>Most Active Posts</h2>
                    <ul>
                        <?php if ($active_posts_query->have_posts()): ?>
                            <?php while ($active_posts_query->have_posts()): $active_posts_query->the_post(); ?>
                                <li><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a> - <?php comments_number(); ?></li>
                            <?php endwhile; wp_reset_postdata(); ?>
                        </ul>
                    <?php else: ?>
                        <p>No active posts.</p>
                    <?php endif; ?>
                </div>
            </div>
        </article>
    </main>
</div>

<?php get_footer(); ?>
