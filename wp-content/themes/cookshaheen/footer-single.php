    </div><!-- #content -->

<!-- Footer for Single Posts -->
<footer class="cs-single-footer">
    <div class="cs-footer-top">
        <div class="container">
            <div class="cs-footer-grid">
                <!-- Brand -->
                <div class="cs-footer-brand">
                    <a href="<?php echo esc_url(home_url('/')); ?>" class="cs-footer-logo">
                        <span class="cs-logo-icon">🍳</span>
                        <span>CookShaheen</span>
                    </a>
                    <p>Your ultimate destination for delicious recipes, cooking tips, and culinary inspiration from around the world.</p>
                    <div class="cs-footer-social">
                        <a href="https://www.instagram.com/cookshaheen" target="_blank"><i class="fab fa-instagram"></i></a>
                        <a href="https://in.pinterest.com/cookshaheen/" target="_blank"><i class="fab fa-pinterest"></i></a>
                        <a href="https://www.youtube.com/channel/UCMz3YgTS-WhOb0_hcfgdw2w" target="_blank"><i class="fab fa-youtube"></i></a>
                        <a href="https://www.facebook.com/cookshaheenYT" target="_blank"><i class="fab fa-facebook"></i></a>
                    </div>
                </div>
                
                <!-- Quick Links -->
                <div class="cs-footer-links">
                    <h4>Quick Links</h4>
                    <ul>
                        <li><a href="<?php echo esc_url(home_url('/')); ?>">Home</a></li>
                        <li><a href="<?php echo esc_url(home_url('/recipes')); ?>">All Recipes</a></li>
                        <li><a href="<?php echo esc_url(home_url('/about')); ?>">About Us</a></li>
                        <li><a href="<?php echo esc_url(home_url('/contact')); ?>">Contact</a></li>
                        <li><a href="<?php echo esc_url(home_url('/privacy-policy')); ?>">Privacy Policy</a></li>
                    </ul>
                </div>
                
                <!-- Categories -->
                <div class="cs-footer-links">
                    <h4>Categories</h4>
                    <ul>
                        <?php
                        $categories = get_categories(array('number' => 6));
                        foreach ($categories as $cat) {
                            echo '<li><a href="' . esc_url(get_category_link($cat->term_id)) . '">' . esc_html($cat->name) . '</a></li>';
                        }
                        ?>
                    </ul>
                </div>
                
                <!-- Newsletter -->
                <div class="cs-footer-newsletter">
                    <h4>Get Recipe Updates</h4>
                    <p>Subscribe for new recipes delivered to your inbox!</p>
                    <form class="cs-mini-newsletter" action="#" method="post">
                        <input type="email" placeholder="Your email" required>
                        <button type="submit"><i class="fas fa-paper-plane"></i></button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    
    <div class="cs-footer-bottom">
        <div class="container">
            <p>&copy; <?php echo date('Y'); ?> CookShaheen. All rights reserved. Made with <span style="color: #e85a4f;">❤</span> for food lovers.</p>
        </div>
    </div>
</footer>

</div><!-- #page -->

<?php wp_footer(); ?>
</body>
</html>
