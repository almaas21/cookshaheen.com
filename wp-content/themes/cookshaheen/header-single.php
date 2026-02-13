<?php
/**
 * Single Post Header for CookShaheen
 */
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;600;700&family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <?php wp_head(); ?>
</head>
<body <?php body_class('cs-single-page'); ?>>
<?php wp_body_open(); ?>

<div id="page" class="site">

<!-- Sticky Header for Single Posts -->
<header class="cs-single-header">
    <div class="container">
        <a href="<?php echo esc_url(home_url('/')); ?>" class="cs-single-logo">
            <span class="cs-logo-icon">🍳</span>
            <span class="cs-logo-text">CookShaheen</span>
        </a>
        
        <nav class="cs-single-nav">
            <ul>
                <li><a href="<?php echo esc_url(home_url('/')); ?>">Home</a></li>
                <li><a href="<?php echo esc_url(home_url('/recipes')); ?>">Recipes</a></li>
                <li><a href="<?php echo esc_url(home_url('/about')); ?>">About</a></li>
                <li><a href="<?php echo esc_url(home_url('/contact')); ?>">Contact</a></li>
            </ul>
        </nav>
        
        <div class="cs-single-actions">
            <button class="cs-search-toggle" aria-label="Search">
                <i class="fas fa-search"></i>
            </button>
            <a href="https://in.pinterest.com/cookshaheen/" target="_blank" class="cs-social-icon">
                <i class="fab fa-pinterest"></i>
            </a>
            <a href="https://www.instagram.com/cookshaheen" target="_blank" class="cs-social-icon">
                <i class="fab fa-instagram"></i>
            </a>
        </div>
        
        <button class="cs-mobile-toggle" aria-label="Menu">
            <span></span>
            <span></span>
            <span></span>
        </button>
    </div>
</header>

<div id="content" class="site-content">
