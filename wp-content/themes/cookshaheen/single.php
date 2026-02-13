<?php
/**
 * Single Post Template for CookShaheen
 */
get_header('single'); ?>

<main id="primary" class="site-main cs-single-post">
    
    <?php while (have_posts()) : the_post(); ?>
    
    <!-- Hero Section -->
    <section class="cs-post-hero">
        <div class="cs-post-hero-overlay"></div>
        <?php if (has_post_thumbnail()) : ?>
            <div class="cs-post-hero-image">
                <?php the_post_thumbnail('full'); ?>
            </div>
        <?php endif; ?>
        
        <div class="cs-post-hero-content">
            <div class="cs-post-meta-top">
                <?php
                $categories = get_the_category();
                if ($categories) {
                    foreach ($categories as $cat) {
                        echo '<a href="' . esc_url(get_category_link($cat->term_id)) . '" class="cs-post-category">' . esc_html($cat->name) . '</a>';
                    }
                }
                ?>
            </div>
            
            <h1 class="cs-post-title"><?php the_title(); ?></h1>
            
            <div class="cs-post-meta">
                <span class="cs-post-author">
                    <i class="fas fa-user"></i> CookShaheen
                </span>
                <span class="cs-post-date">
                    <i class="fas fa-calendar-alt"></i> <?php echo get_the_date('F j, Y'); ?>
                </span>
                <span class="cs-post-read-time">
                    <i class="fas fa-clock"></i> <?php echo ceil(str_word_count(get_the_content()) / 200); ?> min read
                </span>
            </div>
        </div>
    </section>
    
    <!-- Post Content -->
    <article class="cs-post-content">
        <div class="container">
            <div class="cs-post-body">
                <?php the_content(); ?>
            </div>
            
            <!-- Tags -->
            <?php
            $tags = get_the_tags();
            if ($tags) : ?>
            <div class="cs-post-tags">
                <span class="cs-tags-label"><i class="fas fa-tags"></i> Tags:</span>
                <?php foreach ($tags as $tag) : ?>
                    <a href="<?php echo esc_url(get_tag_link($tag->term_id)); ?>" class="cs-tag">
                        <?php echo esc_html($tag->name); ?>
                    </a>
                <?php endforeach; ?>
            </div>
            <?php endif; ?>
            
            <!-- Share Buttons -->
            <div class="cs-post-share">
                <span class="cs-share-label">Share this recipe:</span>
                <div class="cs-share-buttons">
                    <a href="https://www.facebook.com/sharer/sharer.php?u=<?php echo urlencode(get_permalink()); ?>" target="_blank" class="cs-share-btn cs-share-facebook">
                        <i class="fab fa-facebook-f"></i>
                    </a>
                    <a href="https://twitter.com/intent/tweet?url=<?php echo urlencode(get_permalink()); ?>&text=<?php echo urlencode(get_the_title()); ?>" target="_blank" class="cs-share-btn cs-share-twitter">
                        <i class="fab fa-twitter"></i>
                    </a>
                    <a href="https://pinterest.com/pin/create/button/?url=<?php echo urlencode(get_permalink()); ?>&media=<?php echo urlencode(get_the_post_thumbnail_url()); ?>&description=<?php echo urlencode(get_the_title()); ?>" target="_blank" class="cs-share-btn cs-share-pinterest">
                        <i class="fab fa-pinterest-p"></i>
                    </a>
                    <a href="https://wa.me/?text=<?php echo urlencode(get_the_title() . ' ' . get_permalink()); ?>" target="_blank" class="cs-share-btn cs-share-whatsapp">
                        <i class="fab fa-whatsapp"></i>
                    </a>
                </div>
            </div>
            
            <!-- Author Box -->
            <div class="cs-author-box">
                <div class="cs-author-avatar">
                    <img src="https://cookshaheen.com/wp-content/uploads/cookshaheen-avatar.png" alt="CookShaheen" onerror="this.src='https://ui-avatars.com/api/?name=Cook+Shaheen&background=e85a4f&color=fff&size=120'">
                </div>
                <div class="cs-author-info">
                    <span class="cs-author-label">Written by</span>
                    <h4 class="cs-author-name">CookShaheen</h4>
                    <p class="cs-author-bio">Passionate home cook sharing delicious recipes from around the world. From authentic Indian cuisine to international favorites - follow along for easy-to-make dishes that bring joy to your kitchen!</p>
                    <div class="cs-author-social">
                        <a href="https://www.instagram.com/cookshaheen" target="_blank"><i class="fab fa-instagram"></i></a>
                        <a href="https://in.pinterest.com/cookshaheen/" target="_blank"><i class="fab fa-pinterest"></i></a>
                        <a href="https://www.youtube.com/channel/UCMz3YgTS-WhOb0_hcfgdw2w" target="_blank"><i class="fab fa-youtube"></i></a>
                        <a href="https://www.facebook.com/cookshaheenYT" target="_blank"><i class="fab fa-facebook"></i></a>
                    </div>
                </div>
            </div>
        </div>
    </article>
    
    <!-- Related Posts -->
    <section class="cs-related-posts">
        <div class="container">
            <h2 class="cs-section-title">You May Also Like</h2>
            <div class="cs-related-grid">
                <?php
                $categories = get_the_category();
                if ($categories) {
                    $cat_ids = array();
                    foreach ($categories as $cat) {
                        $cat_ids[] = $cat->term_id;
                    }
                    
                    $related_query = new WP_Query(array(
                        'category__in' => $cat_ids,
                        'post__not_in' => array(get_the_ID()),
                        'posts_per_page' => 3,
                        'orderby' => 'rand'
                    ));
                    
                    if ($related_query->have_posts()) :
                        while ($related_query->have_posts()) : $related_query->the_post();
                        ?>
                        <article class="cs-related-card">
                            <a href="<?php the_permalink(); ?>" class="cs-related-image">
                                <?php if (has_post_thumbnail()) : ?>
                                    <?php the_post_thumbnail('medium_large'); ?>
                                <?php else : ?>
                                    <img src="<?php echo get_template_directory_uri(); ?>/assets/images/placeholder.jpg" alt="<?php the_title(); ?>">
                                <?php endif; ?>
                            </a>
                            <div class="cs-related-content">
                                <h3><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
                                <span class="cs-related-date"><?php echo get_the_date('M j, Y'); ?></span>
                            </div>
                        </article>
                        <?php
                        endwhile;
                        wp_reset_postdata();
                    endif;
                }
                ?>
            </div>
        </div>
    </section>
    
    <?php endwhile; ?>
    
</main>

<?php get_footer('single'); ?>
