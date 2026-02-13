<?php
/**
 * Template Name: Recipes Archive
 * 
 * Custom template for displaying all recipes
 */
get_header('single'); ?>

<main id="primary" class="site-main cs-archive-page">
    
    <!-- Archive Hero -->
    <section class="cs-archive-hero">
        <div class="container">
            <span class="cs-archive-label">Browse</span>
            <h1 class="cs-archive-title">All Recipes</h1>
            <p class="cs-archive-description">Discover our collection of delicious recipes from around the world. From quick weeknight dinners to special occasion feasts!</p>
            <?php
            $total_recipes = wp_count_posts('post');
            ?>
            <div class="cs-archive-count">
                <?php printf('%d delicious recipes to explore', $total_recipes->publish); ?>
            </div>
        </div>
    </section>
    
    <!-- Category Filter -->
    <section class="cs-category-filter">
        <div class="container">
            <div class="cs-filter-buttons">
                <a href="<?php echo get_permalink(); ?>" class="cs-filter-btn active">All</a>
                <?php
                $categories = get_categories(array('orderby' => 'count', 'order' => 'DESC', 'number' => 8));
                foreach ($categories as $cat) {
                    echo '<a href="' . esc_url(get_category_link($cat->term_id)) . '" class="cs-filter-btn">' . esc_html($cat->name) . '</a>';
                }
                ?>
            </div>
        </div>
    </section>
    
    <!-- Recipe Grid -->
    <section class="cs-archive-content">
        <div class="container">
            <?php
            $paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
            $recipes_query = new WP_Query(array(
                'post_type' => 'post',
                'posts_per_page' => 12,
                'paged' => $paged,
                'orderby' => 'date',
                'order' => 'DESC'
            ));
            
            if ($recipes_query->have_posts()) : ?>
                <div class="cs-recipe-grid">
                    <?php while ($recipes_query->have_posts()) : $recipes_query->the_post(); ?>
                        <article class="cs-recipe-card">
                            <a href="<?php the_permalink(); ?>" class="cs-recipe-image">
                                <?php if (has_post_thumbnail()) : ?>
                                    <?php the_post_thumbnail('medium_large'); ?>
                                <?php else : ?>
                                    <img src="https://images.unsplash.com/photo-1546069901-ba9599a7e63c?w=400&h=300&fit=crop" alt="<?php the_title(); ?>">
                                <?php endif; ?>
                                
                                <?php
                                $categories = get_the_category();
                                if ($categories) : ?>
                                    <span class="cs-recipe-badge"><?php echo esc_html($categories[0]->name); ?></span>
                                <?php endif; ?>
                            </a>
                            
                            <div class="cs-recipe-content">
                                <h3><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
                                <p><?php echo wp_trim_words(get_the_excerpt(), 15, '...'); ?></p>
                                
                                <div class="cs-recipe-meta">
                                    <span class="cs-recipe-date">
                                        <i class="fas fa-calendar-alt"></i> <?php echo get_the_date('M j, Y'); ?>
                                    </span>
                                    <a href="<?php the_permalink(); ?>" class="cs-recipe-link">
                                        View Recipe <i class="fas fa-arrow-right"></i>
                                    </a>
                                </div>
                            </div>
                        </article>
                    <?php endwhile; ?>
                </div>
                
                <!-- Pagination -->
                <div class="cs-pagination">
                    <?php
                    echo paginate_links(array(
                        'total' => $recipes_query->max_num_pages,
                        'current' => $paged,
                        'prev_text' => '<i class="fas fa-chevron-left"></i> Previous',
                        'next_text' => 'Next <i class="fas fa-chevron-right"></i>',
                        'type' => 'list',
                    ));
                    ?>
                </div>
                
                <?php wp_reset_postdata(); ?>
                
            <?php else : ?>
                <div class="cs-no-posts">
                    <div class="cs-no-posts-icon">🍳</div>
                    <h2>No recipes yet</h2>
                    <p>Check back soon for delicious recipes!</p>
                    <a href="<?php echo home_url('/'); ?>" class="cs-btn-primary">Back to Home</a>
                </div>
            <?php endif; ?>
        </div>
    </section>
    
</main>

<?php get_footer('single'); ?>
