<?php
/**
 * CookShaheen Theme Functions
 *
 * @package CookShaheen
 * @since 1.0.0
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

// Include newsletter functionality
require_once get_stylesheet_directory() . '/inc/newsletter.php';

/**
 * Enqueue theme styles and scripts
 */
function cookshaheen_enqueue_styles() {
    // Main theme style
    wp_enqueue_style(
        'cookshaheen-style',
        get_stylesheet_uri(),
        array(),
        wp_get_theme()->get('Version')
    );
    
    // Google Fonts
    wp_enqueue_style(
        'cookshaheen-fonts',
        'https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;500;600;700&family=Poppins:wght@300;400;500;600;700&display=swap',
        array(),
        null
    );
    
    // Font Awesome for icons
    wp_enqueue_style(
        'font-awesome',
        'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css',
        array(),
        '6.5.1'
    );
    
    // Custom landing page script
    wp_enqueue_script(
        'cookshaheen-scripts',
        get_stylesheet_directory_uri() . '/assets/js/main.js',
        array('jquery'),
        wp_get_theme()->get('Version'),
        true
    );
    
    // Localize script for AJAX
    wp_localize_script('cookshaheen-scripts', 'cookshaheen_ajax', array(
        'ajax_url' => admin_url('admin-ajax.php'),
        'nonce' => wp_create_nonce('cookshaheen_newsletter_nonce')
    ));
}
add_action('wp_enqueue_scripts', 'cookshaheen_enqueue_styles');

/**
 * Theme setup
 */
function cookshaheen_setup() {
    // Add theme support
    add_theme_support('title-tag');
    add_theme_support('post-thumbnails');
    add_theme_support('custom-logo', array(
        'height'      => 100,
        'width'       => 300,
        'flex-height' => true,
        'flex-width'  => true,
    ));
    
    // Register navigation menus
    register_nav_menus(array(
        'primary' => __('Primary Menu', 'cookshaheen'),
        'footer'  => __('Footer Menu', 'cookshaheen'),
    ));
}
add_action('after_setup_theme', 'cookshaheen_setup');

/**
 * Register custom widget areas
 */
function cookshaheen_widgets_init() {
    register_sidebar(array(
        'name'          => __('Footer Widget 1', 'cookshaheen'),
        'id'            => 'footer-1',
        'description'   => __('Add widgets here for footer column 1.', 'cookshaheen'),
        'before_widget' => '<div id="%1$s" class="widget %2$s">',
        'after_widget'  => '</div>',
        'before_title'  => '<h4 class="widget-title">',
        'after_title'   => '</h4>',
    ));
    
    register_sidebar(array(
        'name'          => __('Footer Widget 2', 'cookshaheen'),
        'id'            => 'footer-2',
        'description'   => __('Add widgets here for footer column 2.', 'cookshaheen'),
        'before_widget' => '<div id="%1$s" class="widget %2$s">',
        'after_widget'  => '</div>',
        'before_title'  => '<h4 class="widget-title">',
        'after_title'   => '</h4>',
    ));
}
add_action('widgets_init', 'cookshaheen_widgets_init');

/**
 * Custom excerpt length
 */
function cookshaheen_excerpt_length($length) {
    return 20;
}
add_filter('excerpt_length', 'cookshaheen_excerpt_length');

/**
 * Custom excerpt more
 */
function cookshaheen_excerpt_more($more) {
    return '...';
}
add_filter('excerpt_more', 'cookshaheen_excerpt_more');
