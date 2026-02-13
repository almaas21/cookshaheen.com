<?php
/**
 * Archive/Category Template for CookShaheen
 */
get_header('single'); ?>

<main id="primary" class="site-main cs-archive-page">
    
    <!-- Archive Hero -->
    <section class="cs-archive-hero">
        <div class="container">
            <?php if (is_category()) : ?>
                <span class="cs-archive-label">Category</span>
                <h1 class="cs-archive-title"><?php single_cat_title(); ?></h1>
                <?php if (category_description()) : ?>
                    <p class="cs-archive-description"><?php echo category_description(); ?></p>
                <?php endif; ?>
            <?php elseif (is_tag()) : ?>
                <span class="cs-archive-label">Tag</span>
                <h1 class="cs-archive-title"><?php single_tag_title(); ?></h1>
            <?php elseif (is_author()) : ?>
                <span class="cs-archive-label">Recipes by</span>
                <h1 class="cs-archive-title">CookShaheen</h1>
            <?php elseif (is_search()) : ?>
                <span class="cs-archive-label">Search Results</span>
                <h1 class="cs-archive-title"><?php printf('Results for: "%s"', get_search_query()); ?></h1>
            <?php else : ?>
                <span class="cs-archive-label">Browse</span>
                <h1 class="cs-archive-title">All Recipes</h1>
            <?php endif; ?>
            
            <div class="cs-archive-count">
                <?php 
                global $wp_query;
                printf('%d %s found', $wp_query->found_posts, $wp_query->found_posts === 1 ? 'recipe' : 'recipes');
                ?>
            </div>
        </div>
    </section>
    
    <!-- Recipe Grid -->
    <section class="cs-archive-content">
        <div class="container">
            <?php if (have_posts()) : ?>
                <div class="cs-recipe-grid">
                    <?php while (have_posts()) : the_post(); ?>
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
                        'prev_text' => '<i class="fas fa-chevron-left"></i> Previous',
                        'next_text' => 'Next <i class="fas fa-chevron-right"></i>',
                        'type' => 'list',
                    ));
                    ?>
                </div>
                
            <?php else : ?>
                <div class="cs-no-posts">
                    <div class="cs-no-posts-icon">🍳</div>
                    <h2>No recipes found</h2>
                    <p>We couldn't find any recipes in this category. Try browsing our other delicious recipes!</p>
                    <a href="<?php echo home_url('/'); ?>" class="cs-btn-primary">Back to Home</a>
                </div>
            <?php endif; ?>
        </div>
    </section>
    
</main>

<?php get_footer('single'); ?>
