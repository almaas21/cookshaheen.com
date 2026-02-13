<?php
/**
 * Template Name: Social Links (Linktree)
 * 
 * Linktree-style social links page
 *
 * @package CookShaheen
 */
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Links - CookShaheen</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;600;700&family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
    <?php wp_head(); ?>
</head>
<body>

<style>
    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }
    
    body {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%) !important;
        min-height: 100vh;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 20px;
        font-family: 'Poppins', sans-serif;
    }
    
    .linktree-container {
        max-width: 450px;
        margin: 0 auto;
        text-align: center;
    }
    
    .linktree-profile {
        margin-bottom: 30px;
    }
    
    .linktree-avatar {
        width: 120px;
        height: 120px;
        border-radius: 50%;
        margin: 0 auto 20px;
        border: 5px solid rgba(255, 255, 255, 0.3);
        overflow: hidden;
        background: #fff;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 60px;
    }
    
    .linktree-name {
        color: #fff;
        font-size: 28px;
        font-weight: bold;
        margin-bottom: 8px;
        font-family: 'Playfair Display', serif;
        letter-spacing: 1px;
    }
    
    .linktree-handle {
        color: rgba(255, 255, 255, 0.8);
        font-size: 14px;
        margin-bottom: 10px;
        text-transform: uppercase;
        letter-spacing: 2px;
    }
    
    .linktree-bio {
        color: rgba(255, 255, 255, 0.9);
        font-size: 14px;
        line-height: 1.6;
        margin-bottom: 30px;
    }
    
    .linktree-links {
        display: flex;
        flex-direction: column;
        gap: 12px;
        margin-bottom: 30px;
    }
    
    .linktree-link {
        display: block;
        padding: 16px 24px;
        background: rgba(255, 255, 255, 0.15);
        color: #fff;
        text-decoration: none;
        border-radius: 50px;
        font-weight: 600;
        font-size: 16px;
        transition: all 0.3s ease;
        border: 2px solid rgba(255, 255, 255, 0.3);
        text-transform: uppercase;
        letter-spacing: 0.5px;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 10px;
        backdrop-filter: blur(10px);
    }
    
    .linktree-link:hover {
        background: #667eea;
        color: #fff;
        transform: translateY(-3px);
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2);
    }
    
    /* Pinterest */
    .linktree-link:nth-child(1):hover {
        background: #E60023;
    }
    
    /* Browse Recipes */
    .linktree-link:nth-child(2):hover {
        background: #FF6B35;
    }
    
    /* YouTube */
    .linktree-link:nth-child(3):hover {
        background: #FF0000;
    }
    
    /* Instagram */
    .linktree-link:nth-child(4):hover {
        background: #E4405F;
    }
    
    /* Facebook */
    .linktree-link:nth-child(5):hover {
        background: #1877F2;
    }
    
    .linktree-socials {
        display: flex;
        justify-content: center;
        gap: 20px;
        margin-top: 30px;
        padding-top: 30px;
        border-top: 1px solid rgba(255, 255, 255, 0.2);
    }
    
    .linktree-social-icon {
        width: 50px;
        height: 50px;
        border-radius: 50%;
        background: rgba(255, 255, 255, 0.2);
        display: flex;
        align-items: center;
        justify-content: center;
        color: #fff;
        text-decoration: none;
        font-size: 24px;
        transition: all 0.3s ease;
        border: 2px solid rgba(255, 255, 255, 0.3);
    }
    
    .linktree-social-icon:hover {
        background: rgba(255, 255, 255, 0.3);
        transform: scale(1.1);
        border-color: rgba(255, 255, 255, 0.6);
    }
    
    .linktree-footer {
        color: rgba(255, 255, 255, 0.6);
        font-size: 12px;
        margin-top: 40px;
    }
    
    .linktree-footer a {
        color: rgba(255, 255, 255, 0.8);
        text-decoration: none;
    }
    
    .linktree-footer a:hover {
        text-decoration: underline;
    }
    
    /* Override default WordPress styles */
    body.cookshaheen-linktree .site-header,
    body.cookshaheen-linktree .site-footer {
        display: none;
    }
    
    body.cookshaheen-linktree #primary {
        max-width: 100%;
        padding: 0;
    }
</style>

<main id="primary" class="site-main">
    <div class="linktree-container">
        <!-- Profile Section -->
        <div class="linktree-profile">
            <div class="linktree-avatar">👨‍🍳</div>
            <h1 class="linktree-name">Cook Shaheen</h1>
            <p class="linktree-handle">@FoodiesFoodLovers</p>
            <p class="linktree-bio">Discover delicious recipes, cooking tips, and culinary inspiration. Join our food journey! 🍳✨</p>
        </div>
        
        <!-- Main Links -->
        <div class="linktree-links">
            <a href="https://in.pinterest.com/cookshaheen/" class="linktree-link" target="_blank">
                <i class="fab fa-pinterest"></i>
                Follow on Pinterest
            </a>
            <a href="<?php echo home_url('/recipes'); ?>" class="linktree-link">
                <i class="fas fa-utensils"></i>
                Browse Recipes
            </a>
            <a href="https://www.youtube.com/channel/UCMz3YgTS-WhOb0_hcfgdw2w" class="linktree-link" target="_blank">
                <i class="fab fa-youtube"></i>
                YouTube Channel
            </a>
            <a href="https://www.instagram.com/cookshaheen" class="linktree-link" target="_blank">
                <i class="fab fa-instagram"></i>
                Instagram
            </a>
            <a href="https://www.facebook.com/cookshaheenYT" class="linktree-link" target="_blank">
                <i class="fab fa-facebook"></i>
                Facebook
            </a>
        </div>
        
        <!-- Social Icons -->
        <div class="linktree-socials">
            <a href="https://in.pinterest.com/cookshaheen/" class="linktree-social-icon" target="_blank" title="Pinterest">
                <i class="fab fa-pinterest"></i>
            </a>
            <a href="https://www.youtube.com/channel/UCMz3YgTS-WhOb0_hcfgdw2w" class="linktree-social-icon" target="_blank" title="YouTube">
                <i class="fab fa-youtube"></i>
            </a>
            <a href="https://www.instagram.com/cookshaheen" class="linktree-social-icon" target="_blank" title="Instagram">
                <i class="fab fa-instagram"></i>
            </a>
            <a href="https://www.facebook.com/cookshaheenYT" class="linktree-social-icon" target="_blank" title="Facebook">
                <i class="fab fa-facebook"></i>
            </a>
        </div>
        
        <!-- Footer -->
        <div class="linktree-footer">
            <p>Follow, cook, and share your food journey with #FoodiesFoodLovers</p>
        </div>
    </div>

<?php wp_footer(); ?>
</body>
</html>
