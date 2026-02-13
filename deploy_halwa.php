<?php
/**
 * Gajar Ka Halwa Recipe Deployment Script
 * This script creates a WordPress post for the Gajar ka Halwa recipe.
 * IMPORTANT: Delete this file after visiting it in your browser!
 */

// Load WordPress environment
$wp_load_path = __DIR__ . '/wp-load.php';
if (!file_exists($wp_load_path)) {
    die("Error: wp-load.php not found. Please place this script in your WordPress root directory.");
}
require_once($wp_load_path);

// Check if user is logged in as admin (optional but safer)
if (!current_user_can('manage_options')) {
    // Basic security: require a secret key in the URL
    $secret = 'halwa2026'; // Change this if you want
    if (!isset($_GET['key']) || $_GET['key'] !== $secret) {
        die("Unauthorized access. Visit this script with ?key=$secret to execute.");
    }
}

require_once(ABSPATH . 'wp-admin/includes/image.php');
require_once(ABSPATH . 'wp-admin/includes/file.php');
require_once(ABSPATH . 'wp-admin/includes/media.php');

$title = "Authentic Gajar Ka Halwa (Slow Cooked Carrot Pudding)";
$description = "A traditional, slow-cooked Gajar ka Halwa made with fresh red carrots, full cream milk, khoya, and dry fruits. No pressure cooker shortcuts, just patience and authentic taste. This recipe brings the magic of slow cooking to your kitchen, resulting in a rich, melt-in-the-mouth texture.";

$ingredients = [
    "1 kg Fresh Red Carrots (grated)",
    "0.5 Liter Full Cream Milk",
    "400g Sugar (adjust to taste)",
    "250g Khoya (Mawa)",
    "50g Cashews",
    "50g Makhana (Foxnuts)",
    "Desi Ghee (as needed for cooking, sauteing and tempering)",
    "1/2 tsp Cardamom Powder",
    "2 Green Cardamom Pods",
    "1 Clove"
];

$instructions = [
    "Take a heavy bottom pan and add all the grated carrots to it.",
    "Add 0.5 liter of full cream milk and cook on low heat. Avoid using a pressure cooker for the best texture.",
    "Let the carrots and milk cook slowly on low flame, stirring occasionally, until all the milk is absorbed and the mixture becomes dry.",
    "Add Desi Ghee to the pan and mix well to bring out the aroma.",
    "Add 400g of sugar. Note that the halwa will release some water after adding sugar and its texture will loosen.",
    "Cook for another 2-4 minutes until the sugar water dries up.",
    "Add 1/2 tsp of cardamom powder (elaichi powder) for a beautiful fragrance.",
    "Take 250g of Khoya, crumble it by hand, and add most of it to the pan (save a little for garnish). Mash and mix well so no lumps remain.",
    "Add 50g of cashews and sauté for 2-3 minutes on low flame to allow the flavors to meld.",
    "Add another spoonful of Desi Ghee at this stage for a professional shine and extra flavor.",
    "For the premium touch, prepare a tempering: Heat a little Desi Ghee in a small ladle, add 2 cardamom pods and 1 clove. Pour this over the halwa and mix.",
    "In a separate pan, fry 50g of makhana in a little Desi Ghee for 2-3 minutes until they become crunchy.",
    "Plate the warm halwa, garnish with the saved khoya, cashews, and the crunchy fried makhana."
];

$tips = [
    "Slow cooking is the secret to authentic Gajar ka Halwa; don't give in to 'fast' shortcuts.",
    "Always add sugar at the end as it prevents the carrots from softening further.",
    "Manual grating of carrots gives a better texture than using a food processor.",
    "Full cream milk is essential for that rich, creamy grainy texture."
];

// Build HTML content
$content = '<div class="recipe-content">';
$content .= '<div class="recipe-meta">';
$content .= '<p><strong>Cuisine:</strong> Indian</p>';
$content .= '<p><strong>Difficulty:</strong> Medium</p>';
$content .= '<p><strong>Prep Time:</strong> 15 minutes</p>';
$content .= '<p><strong>Cook Time:</strong> 90 minutes</p>';
$content .= '<p><strong>Servings:</strong> 6-8</p>';
$content .= '</div>';
$content .= '<div class="recipe-description"><p>' . $description . '</p></div>';
$content .= '<div class="recipe-ingredients"><h3>🥘 Ingredients</h3><ul>';
foreach ($ingredients as $ing) $content .= "<li>$ing</li>";
$content .= '</ul></div>';
$content .= '<div class="recipe-instructions"><h3>👨‍🍳 Instructions</h3><ol>';
foreach ($instructions as $step) $content .= "<li>$step</li>";
$content .= '</ol></div>';
$content .= '<div class="recipe-tips"><h3>💡 Tips</h3><ul>';
foreach ($tips as $tip) $content .= "<li>$tip</li>";
$content .= '</ul></div>';
$content .= '<div class="recipe-footer"><p><em>Recipe analyzed and generated from cooking transcript. Adjust quantities and cooking times based on your preferences.</em></p></div>';
$content .= '</div>';

// Create Post Data
$post_data = array(
    'post_title'    => $title,
    'post_content'  => $content,
    'post_status'   => 'publish',
    'post_author'   => 1, // Change to your user ID if needed
    'post_type'     => 'post',
);

// Insert the post
$post_id = wp_insert_post($post_data);

if ($post_id) {
    echo "<h1>✅ Post Created Successfully!</h1>";
    echo "<p>Post ID: $post_id</p>";
    echo "<p><a href='" . get_permalink($post_id) . "'>View Post</a></p>";

    // Handle Image
    $image_file = __DIR__ . '/gajar-halwa.png';
    if (file_exists($image_file)) {
        $file_array = array(
            'name'     => 'gajar-halwa.png',
            'tmp_name' => $image_file,
        );

        // Do the upload
        $id = media_handle_sideload($file_array, $post_id, "Gajar Ka Halwa");

        if (!is_wp_error($id)) {
            set_post_thumbnail($post_id, $id);
            echo "<p>✅ Featured image attached successfully!</p>";
        } else {
            echo "<p>❌ Error attaching image: " . $id->get_error_message() . "</p>";
        }
    } else {
        echo "<p>⚠️ Notice: Image file 'gajar-halwa.png' not found in root. No featured image added.</p>";
    }

    echo "<h3>⚠️ IMPORTANT: Please delete 'deploy_halwa.php' and 'gajar-halwa.png' from your server now!</h3>";
} else {
    echo "<h1>❌ Error: Failed to create post.</h1>";
}
