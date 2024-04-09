<?php get_header(); ?>

<!-- Artist Profile Section -->
<div class="artist-profile">
    <?php while (have_posts()) : the_post(); ?>
        <!-- Artist Name -->
        <h1><?php the_field('name'); ?></h1>
        
        <!-- Artist Biography -->
        <div class="biography">
            <?php the_field('biography'); ?>
        </div>
        
        <!-- Related Artworks Section -->
        <?php 
        $related_artworks = get_field('related_artworks');
        if($related_artworks): ?>
            <h2>Related Artworks</h2>
            <div class="related-artworks">
                <?php foreach($related_artworks as $artwork): ?>
                    <div class="artwork">
                        <a href="<?php echo get_permalink($artwork->ID); ?>">
                            <!-- Display artwork thumbnail if it exists -->
                            <?php if(get_the_post_thumbnail($artwork->ID)): ?>
                                <div class="artwork-thumbnail">
                                    <?php echo get_the_post_thumbnail($artwork->ID, 'thumbnail'); ?>
                                </div>
                            <?php endif; ?>
                            <!-- Artwork Title -->
                            <h3><?php echo get_the_title($artwork->ID); ?></h3>
                        </a>
                        <!-- Optional: Artwork Excerpt or Custom Field -->
                        <p><?php echo get_the_excerpt($artwork->ID); ?></p>
                    </div>
                <?php endforeach; ?>

            </div>
            
               

        <?php endif; ?>
    <?php endwhile; ?>
</div>

<?php get_footer(); ?>
