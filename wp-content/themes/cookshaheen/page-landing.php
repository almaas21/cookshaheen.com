<?php
/**
 * Template Name: CookShaheen Landing Page
 * 
 * Beautiful landing page template for CookShaheen
 *
 * @package CookShaheen
 */

// Remove default header and footer
remove_action('astra_header', 'astra_header_markup');
remove_action('astra_footer', 'astra_footer_markup');

?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="CookShaheen - Discover delicious recipes, cooking tips, and culinary inspiration for food lovers">
    <?php wp_head(); ?>
</head>
<body <?php body_class('cookshaheen-landing'); ?>>
<?php wp_body_open(); ?>

<!-- Navigation -->
<nav class="cs-navbar" id="navbar">
    <div class="container">
        <a href="<?php echo home_url(); ?>" class="cs-logo">
            <span class="cs-logo-icon">👨‍🍳</span>
            <span>CookShaheen</span>
        </a>
        
        <ul class="cs-nav-links" id="navLinks">
            <li><a href="#home">Home</a></li>
            <li><a href="#features">Features</a></li>
            <li><a href="#recipes">Recipes</a></li>
            <li><a href="#about">About</a></li>
            <li><a href="#contact" class="cs-nav-cta">Get Started</a></li>
        </ul>
        
        <div class="cs-mobile-toggle" id="mobileToggle">
            <span></span>
            <span></span>
            <span></span>
        </div>
    </div>
</nav>

<!-- Hero Section -->
<section class="cs-hero" id="home">
    <div class="cs-hero-content">
        <span class="cs-hero-badge">🌟 Welcome to the Kitchen</span>
        <h1>Discover the Art of <span>Delicious</span> Cooking</h1>
        <p>Explore mouthwatering recipes, expert cooking tips, and culinary inspiration that will transform your kitchen into a gourmet paradise.</p>
        <div class="cs-hero-buttons">
            <a href="#recipes" class="cs-btn cs-btn-primary">
                <i class="fas fa-utensils"></i>
                Explore Recipes
            </a>
            <a href="#about" class="cs-btn cs-btn-outline">
                <i class="fas fa-play-circle"></i>
                Watch Videos
            </a>
        </div>
    </div>
    <div class="cs-scroll-indicator">
        <i class="fas fa-chevron-down fa-2x" style="color: white; opacity: 0.7;"></i>
    </div>
</section>

<!-- Features Section -->
<section class="cs-features" id="features">
    <div class="container">
        <div class="cs-section-header">
            <span class="cs-section-tag">Why Choose Us</span>
            <h2>What Makes Us Special</h2>
            <p>Experience the perfect blend of traditional flavors and modern cooking techniques</p>
        </div>
        
        <div class="cs-features-grid">
            <div class="cs-feature-card">
                <div class="cs-feature-icon">🍳</div>
                <h3>Easy Recipes</h3>
                <p>Step-by-step instructions that make cooking simple and enjoyable for everyone, from beginners to experts.</p>
            </div>
            
            <div class="cs-feature-card">
                <div class="cs-feature-icon">🥗</div>
                <h3>Healthy Options</h3>
                <p>Nutritious and delicious recipes that don't compromise on taste while keeping your health in mind.</p>
            </div>
            
            <div class="cs-feature-card">
                <div class="cs-feature-icon">🌍</div>
                <h3>Global Cuisines</h3>
                <p>Explore flavors from around the world with our diverse collection of international recipes.</p>
            </div>
            
            <div class="cs-feature-card">
                <div class="cs-feature-icon">⏱️</div>
                <h3>Quick Meals</h3>
                <p>Busy schedule? No problem! Discover our collection of quick and easy recipes ready in under 30 minutes.</p>
            </div>
            
            <div class="cs-feature-card">
                <div class="cs-feature-icon">👨‍👩‍👧‍👦</div>
                <h3>Family Friendly</h3>
                <p>Recipes that the whole family will love, with options for picky eaters and adventurous foodies alike.</p>
            </div>
            
            <div class="cs-feature-card">
                <div class="cs-feature-icon">📹</div>
                <h3>Video Tutorials</h3>
                <p>Watch our detailed video guides to master cooking techniques and create restaurant-quality dishes at home.</p>
            </div>
        </div>
    </div>
</section>

<!-- Recipes Section -->
<section class="cs-recipes" id="recipes">
    <div class="container">
        <div class="cs-section-header">
            <span class="cs-section-tag">Our Recipes</span>
            <h2>Popular Recipes</h2>
            <p>Handpicked favorites that have won the hearts of food lovers everywhere</p>
        </div>
        
        <div class="cs-recipes-grid">
            <div class="cs-recipe-card">
                <div class="cs-recipe-image">
                    <img src="https://images.unsplash.com/photo-1565299624946-b28f40a0ae38?w=600&h=400&fit=crop" alt="Homemade Pizza">
                    <span class="cs-recipe-badge">Popular</span>
                    <span class="cs-recipe-time"><i class="fas fa-clock"></i> 45 min</span>
                </div>
                <div class="cs-recipe-content">
                    <h3>Homemade Margherita Pizza</h3>
                    <p>Classic Italian pizza with fresh mozzarella, tomatoes, and basil on a crispy thin crust.</p>
                    <div class="cs-recipe-meta">
                        <span class="cs-recipe-rating">
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star-half-alt"></i>
                            <span style="color: #666; margin-left: 5px;">4.8</span>
                        </span>
                        <a href="#" class="cs-recipe-link">View Recipe <i class="fas fa-arrow-right"></i></a>
                    </div>
                </div>
            </div>
            
            <div class="cs-recipe-card">
                <div class="cs-recipe-image">
                    <img src="https://images.unsplash.com/photo-1512058564366-18510be2db19?w=600&h=400&fit=crop" alt="Biryani">
                    <span class="cs-recipe-badge">Chef's Special</span>
                    <span class="cs-recipe-time"><i class="fas fa-clock"></i> 60 min</span>
                </div>
                <div class="cs-recipe-content">
                    <h3>Aromatic Chicken Biryani</h3>
                    <p>Fragrant basmati rice layered with tender spiced chicken, herbs, and caramelized onions.</p>
                    <div class="cs-recipe-meta">
                        <span class="cs-recipe-rating">
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <span style="color: #666; margin-left: 5px;">5.0</span>
                        </span>
                        <a href="#" class="cs-recipe-link">View Recipe <i class="fas fa-arrow-right"></i></a>
                    </div>
                </div>
            </div>
            
            <div class="cs-recipe-card">
                <div class="cs-recipe-image">
                    <img src="https://images.unsplash.com/photo-1484723091739-30a097e8f929?w=600&h=400&fit=crop" alt="French Toast">
                    <span class="cs-recipe-badge">Breakfast</span>
                    <span class="cs-recipe-time"><i class="fas fa-clock"></i> 20 min</span>
                </div>
                <div class="cs-recipe-content">
                    <h3>Classic French Toast</h3>
                    <p>Golden crispy French toast with maple syrup, fresh berries, and a dusting of powdered sugar.</p>
                    <div class="cs-recipe-meta">
                        <span class="cs-recipe-rating">
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star-half-alt"></i>
                            <span style="color: #666; margin-left: 5px;">4.7</span>
                        </span>
                        <a href="#" class="cs-recipe-link">View Recipe <i class="fas fa-arrow-right"></i></a>
                    </div>
                </div>
            </div>
        </div>
        
        <div style="text-align: center; margin-top: 50px;">
            <a href="#" class="cs-btn cs-btn-primary">
                View All Recipes <i class="fas fa-arrow-right"></i>
            </a>
        </div>
    </div>
</section>

<!-- About Section -->
<section class="cs-about" id="about">
    <div class="container">
        <div class="cs-about-grid">
            <div class="cs-about-images">
                <img src="https://images.unsplash.com/photo-1556909114-f6e7ad7d3136?w=600&h=500&fit=crop" alt="Chef cooking" class="cs-about-img-main">
                <img src="https://images.unsplash.com/photo-1466637574441-749b8f19452f?w=300&h=300&fit=crop" alt="Ingredients" class="cs-about-img-secondary">
            </div>
            
            <div class="cs-about-content">
                <span class="cs-section-tag">About Us</span>
                <h2>Passionate About Food & Flavors</h2>
                <p>At CookShaheen, we believe that cooking is an art that brings people together. Our mission is to inspire home cooks with delicious, approachable recipes that celebrate the joy of cooking.</p>
                <p>Whether you're a seasoned chef or just starting your culinary journey, our recipes are designed to guide you every step of the way with clear instructions and helpful tips.</p>
                
                <div class="cs-about-stats">
                    <div class="cs-stat">
                        <div class="cs-stat-number">500+</div>
                        <div class="cs-stat-label">Recipes</div>
                    </div>
                    <div class="cs-stat">
                        <div class="cs-stat-number">50K+</div>
                        <div class="cs-stat-label">Happy Cooks</div>
                    </div>
                    <div class="cs-stat">
                        <div class="cs-stat-number">100+</div>
                        <div class="cs-stat-label">Video Tutorials</div>
                    </div>
                </div>
                
                <a href="#" class="cs-btn cs-btn-primary">
                    Learn More <i class="fas fa-arrow-right"></i>
                </a>
            </div>
        </div>
    </div>
</section>

<!-- Newsletter Section -->
<section class="cs-newsletter" id="contact">
    <div class="container">
        <h2>Get Fresh Recipes Weekly</h2>
        <p>Subscribe to our newsletter and never miss a delicious recipe!</p>
        <form class="cs-newsletter-form" id="newsletterForm">
            <input type="email" id="subscriberEmail" name="email" placeholder="Enter your email address" required>
            <button type="submit" id="subscribeBtn">Subscribe <i class="fas fa-paper-plane"></i></button>
        </form>
        <div id="newsletterMessage" style="margin-top: 20px; display: none;"></div>
    </div>
</section>

<!-- Testimonials Section -->
<section class="cs-testimonials">
    <div class="container">
        <div class="cs-section-header">
            <span class="cs-section-tag">Testimonials</span>
            <h2>What Our Community Says</h2>
            <p>Real feedback from our amazing community of food lovers</p>
        </div>
        
        <div class="cs-testimonials-grid">
            <div class="cs-testimonial-card">
                <p class="cs-testimonial-text">"CookShaheen has transformed my cooking! The recipes are easy to follow and always turn out delicious. My family loves the variety of dishes I now make."</p>
                <div class="cs-testimonial-author">
                    <img src="https://images.unsplash.com/photo-1494790108377-be9c29b29330?w=100&h=100&fit=crop" alt="Sarah" class="cs-testimonial-avatar">
                    <div>
                        <div class="cs-testimonial-name">Sarah Johnson</div>
                        <div class="cs-testimonial-role">Home Cook</div>
                    </div>
                </div>
            </div>
            
            <div class="cs-testimonial-card">
                <p class="cs-testimonial-text">"As a busy professional, I love the quick meal ideas. The 30-minute recipes are lifesavers! The biryani recipe is now a family favorite."</p>
                <div class="cs-testimonial-author">
                    <img src="https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?w=100&h=100&fit=crop" alt="Ahmed" class="cs-testimonial-avatar">
                    <div>
                        <div class="cs-testimonial-name">Ahmed Khan</div>
                        <div class="cs-testimonial-role">Food Enthusiast</div>
                    </div>
                </div>
            </div>
            
            <div class="cs-testimonial-card">
                <p class="cs-testimonial-text">"The video tutorials are amazing! I've learned so many new techniques. CookShaheen has made me confident in the kitchen. Highly recommended!"</p>
                <div class="cs-testimonial-author">
                    <img src="https://images.unsplash.com/photo-1438761681033-6461ffad8d80?w=100&h=100&fit=crop" alt="Emily" class="cs-testimonial-avatar">
                    <div>
                        <div class="cs-testimonial-name">Emily Chen</div>
                        <div class="cs-testimonial-role">Aspiring Chef</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Footer -->
<footer class="cs-footer">
    <div class="container">
        <div class="cs-footer-grid">
            <div class="cs-footer-brand">
                <a href="<?php echo home_url(); ?>" class="cs-logo">
                    <span class="cs-logo-icon">👨‍🍳</span>
                    <span>CookShaheen</span>
                </a>
                <p>Discover the joy of cooking with our collection of delicious recipes, cooking tips, and culinary inspiration for food lovers everywhere.</p>
                <div class="cs-footer-social">
                    <a href="#" aria-label="Facebook"><i class="fab fa-facebook-f"></i></a>
                    <a href="#" aria-label="Instagram"><i class="fab fa-instagram"></i></a>
                    <a href="#" aria-label="YouTube"><i class="fab fa-youtube"></i></a>
                    <a href="#" aria-label="Pinterest"><i class="fab fa-pinterest-p"></i></a>
                </div>
            </div>
            
            <div>
                <h4>Quick Links</h4>
                <ul class="cs-footer-links">
                    <li><a href="#">Home</a></li>
                    <li><a href="#">Recipes</a></li>
                    <li><a href="#">About Us</a></li>
                    <li><a href="#">Contact</a></li>
                </ul>
            </div>
            
            <div>
                <h4>Categories</h4>
                <ul class="cs-footer-links">
                    <li><a href="#">Breakfast</a></li>
                    <li><a href="#">Lunch</a></li>
                    <li><a href="#">Dinner</a></li>
                    <li><a href="#">Desserts</a></li>
                </ul>
            </div>
            
            <div>
                <h4>Support</h4>
                <ul class="cs-footer-links">
                    <li><a href="#">FAQ</a></li>
                    <li><a href="#">Privacy Policy</a></li>
                    <li><a href="#">Terms of Service</a></li>
                    <li><a href="#">Contact Us</a></li>
                </ul>
            </div>
        </div>
        
        <div class="cs-footer-bottom">
            <p>&copy; <?php echo date('Y'); ?> CookShaheen. All rights reserved. Made with ❤️ for food lovers.</p>
        </div>
    </div>
</footer>

<?php wp_footer(); ?>
</body>
</html>
