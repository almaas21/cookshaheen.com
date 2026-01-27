<?php
/**
 * Newsletter Subscription Handler
 * 
 * @package CookShaheen
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Handle newsletter subscription AJAX request
 */
function cookshaheen_handle_newsletter_subscription() {
    // Check nonce for security
    check_ajax_referer('cookshaheen_newsletter_nonce', 'nonce');
    
    // Sanitize email
    $email = sanitize_email($_POST['email']);
    
    // Validate email
    if (!is_email($email)) {
        wp_send_json_error(array(
            'message' => 'Please enter a valid email address.'
        ));
    }
    
    // Check if email already exists
    $existing = get_posts(array(
        'post_type' => 'cs_subscriber',
        'meta_key' => 'subscriber_email',
        'meta_value' => $email,
        'posts_per_page' => 1
    ));
    
    if (!empty($existing)) {
        wp_send_json_error(array(
            'message' => 'This email is already subscribed!'
        ));
    }
    
    // Create new subscriber
    $subscriber_id = wp_insert_post(array(
        'post_type' => 'cs_subscriber',
        'post_title' => $email,
        'post_status' => 'publish',
        'meta_input' => array(
            'subscriber_email' => $email,
            'subscribed_date' => current_time('mysql'),
            'subscriber_ip' => $_SERVER['REMOTE_ADDR'],
        )
    ));
    
    if (is_wp_error($subscriber_id)) {
        wp_send_json_error(array(
            'message' => 'Subscription failed. Please try again.'
        ));
    }
    
    // Send confirmation email
    $admin_email = get_option('admin_email');
    $subject = 'New Newsletter Subscriber - CookShaheen';
    $message = "New subscriber: {$email}\nSubscribed on: " . current_time('mysql');
    wp_mail($admin_email, $subject, $message);
    
    // Send welcome email to subscriber
    $welcome_subject = 'Welcome to CookShaheen Newsletter!';
    $welcome_message = "
        <html>
        <body>
            <h2>Welcome to CookShaheen! 🍳</h2>
            <p>Thank you for subscribing to our newsletter!</p>
            <p>You'll now receive weekly recipe updates, cooking tips, and exclusive content straight to your inbox.</p>
            <p>Happy Cooking!<br>The CookShaheen Team</p>
            <hr>
            <p style='font-size:12px; color:#666;'>
                If you wish to unsubscribe, visit: <a href='" . home_url('/unsubscribe?email=' . urlencode($email)) . "'>Unsubscribe</a>
            </p>
        </body>
        </html>
    ";
    
    $headers = array('Content-Type: text/html; charset=UTF-8');
    wp_mail($email, $welcome_subject, $welcome_message, $headers);
    
    wp_send_json_success(array(
        'message' => 'Thank you for subscribing! Check your email for confirmation.'
    ));
}
add_action('wp_ajax_cookshaheen_newsletter', 'cookshaheen_handle_newsletter_subscription');
add_action('wp_ajax_nopriv_cookshaheen_newsletter', 'cookshaheen_handle_newsletter_subscription');

/**
 * Register custom post type for subscribers
 */
function cookshaheen_register_subscriber_post_type() {
    register_post_type('cs_subscriber', array(
        'labels' => array(
            'name' => 'Newsletter Subscribers',
            'singular_name' => 'Subscriber',
            'add_new' => 'Add New Subscriber',
            'add_new_item' => 'Add New Subscriber',
            'edit_item' => 'Edit Subscriber',
            'view_item' => 'View Subscriber',
            'all_items' => 'All Subscribers',
        ),
        'public' => false,
        'show_ui' => true,
        'show_in_menu' => true,
        'menu_icon' => 'dashicons-email-alt',
        'supports' => array('title'),
        'capability_type' => 'post',
        'capabilities' => array(
            'create_posts' => 'do_not_allow',
        ),
        'map_meta_cap' => true,
    ));
}
add_action('init', 'cookshaheen_register_subscriber_post_type');

/**
 * Add custom columns to subscribers list
 */
function cookshaheen_subscriber_columns($columns) {
    return array(
        'cb' => $columns['cb'],
        'title' => 'Email',
        'subscribed_date' => 'Subscribed On',
        'subscriber_ip' => 'IP Address',
    );
}
add_filter('manage_cs_subscriber_posts_columns', 'cookshaheen_subscriber_columns');

function cookshaheen_subscriber_column_content($column, $post_id) {
    switch ($column) {
        case 'subscribed_date':
            echo get_post_meta($post_id, 'subscribed_date', true);
            break;
        case 'subscriber_ip':
            echo get_post_meta($post_id, 'subscriber_ip', true);
            break;
    }
}
add_action('manage_cs_subscriber_posts_custom_column', 'cookshaheen_subscriber_column_content', 10, 2);

/**
 * Export subscribers to CSV
 */
function cookshaheen_export_subscribers() {
    if (!isset($_GET['cookshaheen_export_subscribers']) || !current_user_can('manage_options')) {
        return;
    }
    
    $subscribers = get_posts(array(
        'post_type' => 'cs_subscriber',
        'posts_per_page' => -1,
    ));
    
    header('Content-Type: text/csv');
    header('Content-Disposition: attachment; filename="cookshaheen-subscribers-' . date('Y-m-d') . '.csv"');
    
    $output = fopen('php://output', 'w');
    fputcsv($output, array('Email', 'Subscribed Date', 'IP Address'));
    
    foreach ($subscribers as $subscriber) {
        $email = get_post_meta($subscriber->ID, 'subscriber_email', true);
        $date = get_post_meta($subscriber->ID, 'subscribed_date', true);
        $ip = get_post_meta($subscriber->ID, 'subscriber_ip', true);
        fputcsv($output, array($email, $date, $ip));
    }
    
    fclose($output);
    exit;
}
add_action('admin_init', 'cookshaheen_export_subscribers');

/**
 * Add export button to subscribers page
 */
function cookshaheen_add_export_button() {
    $screen = get_current_screen();
    if ($screen->post_type === 'cs_subscriber') {
        ?>
        <script>
        jQuery(document).ready(function($) {
            $('.wrap h1').after('<a href="<?php echo admin_url('edit.php?post_type=cs_subscriber&cookshaheen_export_subscribers=1'); ?>" class="page-title-action">Export to CSV</a>');
        });
        </script>
        <?php
    }
}
add_action('admin_head', 'cookshaheen_add_export_button');
