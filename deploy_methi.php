<?php
/**
 * Methi Matar Malai Recipe Update Script
 * Updates the existing post content with WPRM formatting.
 */

// Load WordPress
$wp_load_path = __DIR__ . '/wp-load.php';
if (!file_exists($wp_load_path)) {
    die("Error: wp-load.php not found.");
}
require_once($wp_load_path);

// Security check
$secret = 'methi2026';
if (!isset($_GET['key']) || $_GET['key'] !== $secret) {
    die("Unauthorized. Use ?key=$secret");
}

// Find the post
$title = "🍃 Creamy Methi Matar Malai (Dhaba & Restaurant Style)";
$post = get_page_by_title($title, OBJECT, 'post');

if (!$post) {
    // Try by slug if title match fails (handling emoji issues)
    $args = array(
        'name'        => '🍃-creamy-methi-matar-malai-dhaba-restaurant-style',
        'post_type'   => 'post',
        'post_status' => 'publish',
        'numberposts' => 1
    );
    $posts = get_posts($args);
    if ($posts) {
        $post = $posts[0];
    }
}

if (!$post) {
    // Fallback: search by partial title
    $args = array(
        's'           => 'Methi Matar Malai',
        'post_type'   => 'post',
        'post_status' => 'publish',
        'numberposts' => 1
    );
    $posts = get_posts($args);
    if ($posts) {
        $post = $posts[0];
    }
}

if (!$post) {
    die("❌ Error: Post not found.");
}

echo "Found Post: " . $post->post_title . " (ID: " . $post->ID . ")<br>";

// HTML Content (WPRM Style)
$content = <<<HTML
<div class="wprm-recipe-container">
    <div class="wprm-recipe-name">🍃 Creamy Methi Matar Malai</div>
    
    <div class="wprm-recipe-details">
        <div class="wprm-recipe-block-container">
            <span class="wprm-recipe-detail-label">Cuisine</span>
            <span class="wprm-recipe-detail-value">North Indian</span>
        </div>
        <div class="wprm-recipe-block-container">
            <span class="wprm-recipe-detail-label">Prep Time</span>
            <span class="wprm-recipe-detail-value">15 mins</span>
        </div>
        <div class="wprm-recipe-block-container">
            <span class="wprm-recipe-detail-label">Cook Time</span>
            <span class="wprm-recipe-detail-value">20 mins</span>
        </div>
        <div class="wprm-recipe-block-container">
            <span class="wprm-recipe-detail-label">Servings</span>
            <span class="wprm-recipe-detail-value">4-5</span>
        </div>
    </div>
    
    <div class="wprm-recipe-summary">
        <p>If you think Methi Matar Malai only tastes good at restaurants and dhabas, then this video will change your mind forever! This recipe is so creamy, so smooth, and so perfectly balanced that even dhaba owners will ask you for the recipe.</p>
    </div>
    
    <div class="wprm-recipe-ingredients">
        <h3 class="wprm-recipe-header">🥘 Ingredients</h3>
        
        <div class="wprm-recipe-ingredient-group">
            <h4 class="wprm-recipe-group-name">For Methi Preparation:</h4>
            <ul class="wprm-recipe-ingredients-list">
                <li class="wprm-recipe-ingredient">200-250 grams Fresh Methi (Fenugreek) - remove hard stems, use only soft leaves</li>
                <li class="wprm-recipe-ingredient">Salt (for removing bitterness)</li>
                <li class="wprm-recipe-ingredient">Water (for washing)</li>
            </ul>
        </div>
        
        <div class="wprm-recipe-ingredient-group">
            <h4 class="wprm-recipe-group-name">For the Gravy:</h4>
            <ul class="wprm-recipe-ingredients-list">
                <li class="wprm-recipe-ingredient">1 cup Fresh or Frozen Peas</li>
                <li class="wprm-recipe-ingredient">4 Medium Onions (roughly chopped)</li>
                <li class="wprm-recipe-ingredient">1 tablespoon Garlic (finely chopped)</li>
                <li class="wprm-recipe-ingredient">1 tablespoon Ginger (finely chopped)</li>
                <li class="wprm-recipe-ingredient">2-3 Green Chilies (finely chopped)</li>
            </ul>
        </div>
    </div>
    
    <div class="wprm-recipe-instructions">
        <h3 class="wprm-recipe-header">👨‍🍳 Instructions</h3>
        <ul class="wprm-recipe-instructions-list">
            <li class="wprm-recipe-instruction">
                <div class="wprm-recipe-instruction-number">1</div>
                <div class="wprm-recipe-instruction-text"><strong>Prepare Methi:</strong> Massage chopped methi with salt for 2-3 minutes, then wash thoroughly to remove bitterness.</div>
            </li>
            <li class="wprm-recipe-instruction">
                <div class="wprm-recipe-instruction-number">2</div>
                <div class="wprm-recipe-instruction-text"><strong>Onion-Cashew Paste:</strong> Sauté onions, whole spices, and cashews. Blend into a smooth paste.</div>
            </li>
            <li class="wprm-recipe-instruction">
                <div class="wprm-recipe-instruction-number">3</div>
                <div class="wprm-recipe-instruction-text"><strong>Cook:</strong> Sauté garlic, ginger, and methi in butter. Add spices and the onion-cashew paste.</div>
            </li>
            <li class="wprm-recipe-instruction">
                <div class="wprm-recipe-instruction-number">4</div>
                <div class="wprm-recipe-instruction-text"><strong>Finish:</strong> Add peas, cream, and a pinch of sugar. Simmer until oil separates. Garnish and serve!</div>
            </li>
        </ul>
    </div>
    
    <div class="recipe-tips">
        <h3>💡 Chef's Secret Tips</h3>
        <ul>
            <li><strong>Remove Bitterness:</strong> Massage chopped methi with salt for 2-3 minutes, then wash thoroughly.</li>
            <li><strong>Less Turmeric:</strong> Use less turmeric than usual - just for color.</li>
            <li><strong>Cashew Magic:</strong> Adding cashews gives the curry its creamy texture.</li>
            <li><strong>Sugar Balance:</strong> A pinch of sugar balances bitterness and enhances taste.</li>
            <li><strong>Low Flame:</strong> Keep flame low while simmering to prevent burning.</li>
        </ul>
    </div>
</div>
HTML;

// Update the post
$updated_post = array(
    'ID'           => $post->ID,
    'post_content' => $content
);

$post_id = wp_update_post($updated_post, true);

if (is_wp_error($post_id)) {
    echo "<br>❌ Error updating post: " . $post_id->get_error_message();
} else {
    echo "<br>✅ Post updated successfully! Visit: <a href='" . get_permalink($post_id) . "'>" . get_permalink($post_id) . "</a>";
    echo "<br><h3>⚠️ Please delete 'deploy_methi.php' now!</h3>";
}
?>
