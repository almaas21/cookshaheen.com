<?php
/**
 * Plugin Name: WP Recipe Maker Pro Unlocker
 * Description: Unlocks WP Recipe Maker Premium features including Gemini AI
 * Version: 1.0
 * Author: CookShaheen
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Force enable premium features
 */
add_filter('wprm_is_premium_active', '__return_true', 999);
add_filter('wprm_settings_premium', '__return_true', 999);

/**
 * Enable AI features (Gemini)
 */
add_filter('wprm_feature_ai', '__return_true', 999);
add_filter('wprm_feature_gemini', '__return_true', 999);

/**
 * Enable all premium features
 */
function wprm_unlock_all_features($features) {
    if (!is_array($features)) {
        $features = array();
    }
    
    // Enable all premium features
    $premium_features = array(
        'adjustable-servings',
        'custom-fields',
        'ingredient-links',
        'nutritional-info',
        'ratings',
        'user-ratings',
        'comments',
        'collections',
        'saved-recipes',
        'recipe-submissions',
        'import-features',
        'unit-conversion',
        'private-notes',
        'recipe-analytics',
        'ai-features',
        'gemini-ai',
        'nutrition-api',
        'equipment',
        'video',
        'custom-taxonomies'
    );
    
    foreach ($premium_features as $feature) {
        $features[$feature] = true;
    }
    
    return $features;
}
add_filter('wprm_settings_features', 'wprm_unlock_all_features', 999);

/**
 * Bypass license check
 */
add_filter('pre_option_wprm_license', function() {
    return array(
        'key' => 'UNLOCKED',
        'email' => get_option('admin_email'),
        'status' => 'valid',
        'expires' => '2099-12-31'
    );
}, 999);

/**
 * Add Gemini AI settings to settings page
 */
function wprm_add_gemini_settings($settings) {
    if (!isset($settings['features'])) {
        $settings['features'] = array();
    }
    
    $settings['features']['ai'] = array(
        'name' => __('AI Features (Gemini)', 'wp-recipe-maker'),
        'type' => 'toggle',
        'default' => true,
    );
    
    $settings['features']['gemini_api_key'] = array(
        'name' => __('Gemini API Key', 'wp-recipe-maker'),
        'description' => __('Get your free API key from https://makersuite.google.com/app/apikey', 'wp-recipe-maker'),
        'type' => 'text',
        'default' => '',
    );
    
    return $settings;
}
add_filter('wprm_settings_structure', 'wprm_add_gemini_settings', 999);

/**
 * Add Gemini AI button to recipe editor
 */
function wprm_add_gemini_button_script() {
    ?>
    <script>
    jQuery(document).ready(function($) {
        // Add Gemini AI button to Import tab
        function addGeminiButton() {
            // Try multiple locations
            var targets = [
                '.wprm-import-text-container',
                '.wprm-recipe-import-text-container', 
                'textarea[placeholder*="Paste or type recipe"]',
                '.wprm-manage-modal-content',
                '.wprm-recipe-form'
            ];
            
            targets.forEach(function(selector) {
                var $target = $(selector);
                if ($target.length && !$('#wprm-gemini-ai-btn-' + selector.replace(/[^a-z]/g, '')).length) {
                    var btnId = 'wprm-gemini-ai-btn-' + selector.replace(/[^a-z]/g, '');
                    
                    var $geminiBtn = $('<button/>', {
                        id: btnId,
                        class: 'button button-primary',
                        type: 'button',
                        html: '✨ Fill with Gemini AI',
                        css: {
                            'margin': '10px 0',
                            'background': '#4285f4',
                            'border-color': '#4285f4',
                            'padding': '8px 16px',
                            'font-weight': 'bold',
                            'display': 'inline-block'
                        }
                    });
                    
                    $geminiBtn.on('click', function(e) {
                        e.preventDefault();
                        e.stopPropagation();
                        wprm_trigger_gemini_fill();
                    });
                    
                    $target.before($geminiBtn);
                    console.log('Gemini button added to: ' + selector);
                }
            });
            
            // Also add to Import tab specifically
            if ($('a[href="#wprm-import"]').length && !$('#wprm-gemini-import-btn').length) {
                var $importBtn = $('<div style="padding: 15px; background: #f0f9ff; border: 2px solid #4285f4; border-radius: 8px; margin: 15px 0;"><button id="wprm-gemini-import-btn" class="button button-primary button-hero" type="button" style="background: #4285f4; border-color: #4285f4; font-size: 16px;"><span style="font-size: 20px;">✨</span> Fill Recipe with Gemini AI</button><p style="margin: 10px 0 0 0; color: #666;">Paste a recipe URL or text and let AI extract the details automatically!</p></div>');
                
                $importBtn.find('button').on('click', function(e) {
                    e.preventDefault();
                    wprm_trigger_gemini_fill();
                });
                
                $('#wprm-import').prepend($importBtn);
            }
        }
            
            // Function to trigger Gemini AI fill
            window.wprm_trigger_gemini_fill = function() {
                var apiKey = '<?php echo esc_js(get_option("wprm_settings_features_gemini_api_key", "")); ?>';
                
                if (!apiKey) {
                    alert('⚠️ Gemini API Key not set!\n\n1. Get your FREE key: https://makersuite.google.com/app/apikey\n2. Go to WP Recipe Maker → Settings\n3. Enter your API key and save');
                    window.open('https://makersuite.google.com/app/apikey', '_blank');
                    return;
                }
                
                // Automatically get content from import textarea
                var $importTextarea = $('textarea[placeholder*="Paste or type recipe"]');
                var recipeContent = $importTextarea.val() || '';
                
                // If textarea is empty, ask for input
                if (!recipeContent.trim()) {
                    recipeContent = prompt('Paste recipe URL or recipe text to analyze with Gemini AI:');
                    if (!recipeContent) return;
                }
                
                // Show loading
                var $loadingMsg = $('<div id="wprm-gemini-loading" style="position: fixed; top: 50%; left: 50%; transform: translate(-50%, -50%); background: white; padding: 30px; border-radius: 10px; box-shadow: 0 10px 40px rgba(0,0,0,0.3); z-index: 999999; text-align: center;"><div style="font-size: 48px; margin-bottom: 15px;">🔄</div><h2 style="margin: 0 0 10px 0;">Analyzing Recipe with Gemini AI...</h2><p style="color: #666;">This may take a few seconds</p></div>');
                $('body').append($loadingMsg);
                
                $.ajax({
                    url: ajaxurl || '/wp-admin/admin-ajax.php',
                    type: 'POST',
                    data: {
                        action: 'wprm_gemini_fill_recipe',
                        nonce: '<?php echo wp_create_nonce("wprm_gemini"); ?>',
                        content: recipeContent
                    },
                    timeout: 60000,
                    success: function(response) {
                        $('#wprm-gemini-loading').remove();
                        
                        if (response.success && response.data) {
                            var data = response.data;
                            console.log('Gemini data received:', data);
                            
                            // COMPREHENSIVE AUTO-FILL FOR ALL SECTIONS
                            var fillField = function(selector, value, eventType) {
                                var $field = $(selector).filter(':visible').first();
                                if ($field.length && value) {
                                    $field.val(value).trigger('input').trigger('change');
                                    if (eventType === 'focus') $field.focus().blur();
                                    return true;
                                }
                                return false;
                            };
                            
                            // GENERAL SECTION
                            fillField('input[name="wprm-recipe-name"], #wprm-recipe-name', data.name);
                            fillField('textarea[name="wprm-recipe-summary"], #wprm-recipe-summary, textarea[placeholder*="Short description"]', data.summary, 'focus');
                            
                            // TIMES SECTION
                            fillField('input[name="wprm-recipe-prep-time"], input[name="wprm_prep_time"]', data.prep_time);
                            fillField('input[name="wprm-recipe-cook-time"], input[name="wprm_cook_time"]', data.cook_time);
                            fillField('input[name="wprm-recipe-total-time"], input[name="wprm_total_time"]', data.total_time);
                            
                            // SERVINGS
                            fillField('input[name="wprm-recipe-servings"], input[name="wprm_servings"]', data.servings);
                            fillField('input[name="wprm-recipe-servings-unit"], input[name="wprm_servings_unit"]', data.servings_unit || 'servings');
                            
                            // CATEGORIES
                            if (data.course) fillField('input[name="wprm-recipe-course[]"], .wprm-recipe-course input', data.course);
                            if (data.cuisine) fillField('input[name="wprm-recipe-cuisine[]"], .wprm-recipe-cuisine input', data.cuisine);
                            if (data.diet) fillField('input[name="wprm-recipe-diet[]"], .wprm-recipe-diet input', data.diet);
                            
                            // EQUIPMENT
                            if (data.equipment && data.equipment.length) {
                                $('.wprm-recipe-equipment-add-button, button[contains("Add Equipment")]').click();
                                setTimeout(function() {
                                    data.equipment.forEach(function(eq, idx) {
                                        if (idx > 0) $('.wprm-recipe-equipment-add-button').click();
                                        setTimeout(function() {
                                            $('input[name="wprm-recipe-equipment[' + idx + '][name]"]').val(eq).trigger('change');
                                        }, 100 * idx);
                                    });
                                }, 500);
                            }
                            
                            // INGREDIENTS
                            if (data.ingredients && data.ingredients.length) {
                                setTimeout(function() {
                                    data.ingredients.forEach(function(ing, idx) {
                                        if (idx > 0) $('.wprm-recipe-ingredients-add-button, button:contains("Add Ingredient")').first().click();
                                        setTimeout(function() {
                                            var $ingField = $('input[name="wprm-recipe-ingredients[' + idx + '][name]"], .wprm-recipe-ingredient-name').eq(idx);
                                            if ($ingField.length) {
                                                $ingField.val(ing).trigger('change').trigger('input');
                                            }
                                        }, 150 * idx);
                                    });
                                }, 800);
                            }
                            
                            // INSTRUCTIONS
                            if (data.instructions && data.instructions.length) {
                                setTimeout(function() {
                                    data.instructions.forEach(function(inst, idx) {
                                        if (idx > 0) $('.wprm-recipe-instructions-add-button, button:contains("Add Instruction")').first().click();
                                        setTimeout(function() {
                                            var $instField = $('textarea[name="wprm-recipe-instructions[' + idx + '][text]"], .wprm-recipe-instruction-text').eq(idx);
                                            if ($instField.length) {
                                                $instField.val(inst).trigger('change').trigger('input');
                                            }
                                        }, 150 * idx);
                                    });
                                }, 1500);
                            }
                            
                            // NOTES
                            if (data.notes) {
                                setTimeout(function() {
                                    fillField('textarea[name="wprm-recipe-notes"], #wprm-recipe-notes', data.notes, 'focus');
                                }, 2000);
                            }
                            
                            // NUTRITION
                            if (data.nutrition) {
                                setTimeout(function() {
                                    fillField('input[name="wprm-recipe-nutrition-calories"]', data.nutrition.calories);
                                    fillField('input[name="wprm-recipe-nutrition-protein"]', data.nutrition.protein);
                                    fillField('input[name="wprm-recipe-nutrition-carbohydrates"]', data.nutrition.carbohydrates);
                                    fillField('input[name="wprm-recipe-nutrition-fat"]', data.nutrition.fat);
                                    fillField('input[name="wprm-recipe-nutrition-fiber"]', data.nutrition.fiber);
                                    fillField('input[name="wprm-recipe-nutrition-sugar"]', data.nutrition.sugar);
                                }, 2500);
                            }
                            
                            // Show success message
                            setTimeout(function() {
                                alert('✅ Recipe Auto-Filled Successfully!\n\nAll sections populated:\n✓ Name & Summary\n✓ Times\n✓ Servings\n✓ Categories\n✓ Equipment\n✓ Ingredients (' + (data.ingredients?.length || 0) + ')\n✓ Instructions (' + (data.instructions?.length || 0) + ')\n✓ Nutrition\n✓ Notes\n\nPlease review and save!');
                            }, 3000);
                            
                            return;
                            
                            // Method 2: Fallback to import text method
                            var importText = '';
                            if (data.name) importText += data.name + '\n\n';
                            if (data.summary) importText += data.summary + '\n\n';
                            if (data.servings) importText += 'Servings: ' + data.servings + '\n';
                            if (data.prep_time) importText += 'Prep Time: ' + data.prep_time + ' minutes\n';
                            if (data.cook_time) importText += 'Cook Time: ' + data.cook_time + ' minutes\n\n';
                            
                            if (data.ingredients && data.ingredients.length) {
                                importText += 'Ingredients:\n' + data.ingredients.join('\n') + '\n\n';
                            }
                            if (data.instructions && data.instructions.length) {
                                importText += 'Instructions:\n';
                                data.instructions.forEach(function(inst, idx) {
                                    importText += (idx + 1) + '. ' + inst + '\n';
                                });
                            }
                            
                            $importTextarea.val(importText).trigger('change').trigger('input');
                            
                            // Auto-click the import button after 1 second
                            setTimeout(function() {
                                var $importBtn = $('.wprm-import-text-import-button, button:contains("Import Recipe"), .wprm-import-button').first();
                                if ($importBtn.length) {
                                    $importBtn.click();
                                    
                                    // After import, try to parse and fill fields
                                    setTimeout(function() {
                                        // Fill name from imported data
                                        if (data.name) {
                                            var $nameField = $('input[name="wprm-recipe-name"], #wprm-recipe-name, input[placeholder*="Recipe Name"]').filter(':visible').first();
                                            $nameField.val(data.name).trigger('change').trigger('input');
                                            console.log('Post-import: Name filled');
                                        }
                                        
                                        // Fill summary from imported data - use multiple selectors
                                        if (data.summary) {
                                            // Try all possible summary field selectors
                                            var summarySelectors = [
                                                'textarea[name="wprm-recipe-summary"]',
                                                '#wprm-recipe-summary',
                                                'textarea[placeholder*="Short description"]',
                                                'textarea[placeholder*="description of this recipe"]',
                                                '.wprm-recipe-summary textarea',
                                                '.wprm-recipe-form textarea'
                                            ];
                                            
                                            var $summaryField = null;
                                            for (var i = 0; i < summarySelectors.length; i++) {
                                                $summaryField = $(summarySelectors[i]).filter(':visible').first();
                                                if ($summaryField.length) {
                                                    console.log('Found summary field with selector:', summarySelectors[i]);
                                                    break;
                                                }
                                            }
                                            
                                            if ($summaryField && $summaryField.length) {
                                                $summaryField.val(data.summary);
                                                $summaryField.trigger('input');
                                                $summaryField.trigger('change');
                                                $summaryField.focus().blur();
                                                console.log('Post-import: Summary filled with:', data.summary);
                                            } else {
                                                console.log('Post-import: Summary field still not found');
                                            }
                                        }
                                        
                                        alert('✅ Recipe imported and auto-filled!\n\nName: ' + (data.name || 'N/A') + '\nSummary: ' + (data.summary ? 'Filled' : 'Not found') + '\n\nPlease review all fields.');
                                    }, 2000);
                                } else {
                                    alert('✅ Recipe extracted successfully!\n\nThe recipe has been filled in the import box. Click "Import Recipe" to complete.');
                                }
                            }, 1000);
                        } else {
                            alert('❌ Failed to analyze recipe.\n\n' + (response.data?.message || 'Please try again or check your API key.'));
                        }
                    },
                    error: function(xhr, status, error) {
                        $('#wprm-gemini-loading').remove();
                        alert('❌ Error: ' + (error || 'Connection failed') + '\n\nPlease check your internet connection and try again.');
                    }
                });
            };
            
            // Add button on page load and keep checking
            addGeminiButton();
            var checkInterval = setInterval(function() {
                addGeminiButton();
            }, 1000);
            
            // Stop checking after 30 seconds
            setTimeout(function() {
                clearInterval(checkInterval);
            }, 30000);
        });
        </script>
        <style>
        #wprm-gemini-import-btn:hover {
            transform: scale(1.05);
            transition: all 0.3s ease;
        }
        </style>
        <?php
}
add_action('admin_footer', 'wprm_add_gemini_button_script');

/**
 * AJAX handler for Gemini AI recipe fill
 */
function wprm_gemini_fill_recipe_ajax() {
    check_ajax_referer('wprm_gemini', 'nonce');
    
    if (!current_user_can('edit_posts')) {
        wp_send_json_error('Permission denied');
    }
    
    $content = sanitize_textarea_field($_POST['content']);
    
    // Check if Gemini API key is set
    $api_key = get_option('wprm_settings_features_gemini_api_key', '');
    
    if (empty($api_key)) {
        wp_send_json_error(array(
            'message' => 'Please set your Gemini API key in WP Recipe Maker settings. Get it from: https://makersuite.google.com/app/apikey'
        ));
    }
    
    // Call Gemini API
    $gemini_url = 'https://generativelanguage.googleapis.com/v1beta/models/gemini-flash-latest:generateContent?key=' . $api_key;
    
    $prompt = "Analyze the following recipe content and extract ALL possible recipe data. Return ONLY a valid JSON object with these fields (use null for missing data):
{
  \"name\": \"recipe name\",
  \"summary\": \"brief description\",
  \"prep_time\": 15,
  \"cook_time\": 30,
  \"total_time\": 45,
  \"servings\": 4,
  \"servings_unit\": \"servings\",
  \"course\": \"Main Course\",
  \"cuisine\": \"Indian\",
  \"diet\": \"Non-Vegetarian\",
  \"equipment\": [\"Pan\", \"Spatula\"],
  \"ingredients\": [\"1 cup flour\", \"2 eggs\"],
  \"instructions\": [\"Mix ingredients\", \"Cook for 10 mins\"],
  \"notes\": \"Any additional tips or variations\",
  \"nutrition\": {
    \"calories\": 250,
    \"protein\": 15,
    \"carbohydrates\": 30,
    \"fat\": 10,
    \"fiber\": 5,
    \"sugar\": 3
  }
}

Recipe content:\n" . $content;
    
    $response = wp_remote_post($gemini_url, array(
        'headers' => array('Content-Type' => 'application/json'),
        'body' => json_encode(array(
            'contents' => array(
                array(
                    'parts' => array(
                        array('text' => $prompt)
                    )
                )
            )
        )),
        'timeout' => 30
    ));
    
    if (is_wp_error($response)) {
        wp_send_json_error(array('message' => $response->get_error_message()));
    }
    
    $body = json_decode(wp_remote_retrieve_body($response), true);
    
    if (isset($body['candidates'][0]['content']['parts'][0]['text'])) {
        $text = $body['candidates'][0]['content']['parts'][0]['text'];
        
        // Clean up text - remove markdown code blocks if present
        $text = preg_replace('/```json\s*/', '', $text);
        $text = preg_replace('/```\s*/', '', $text);
        $text = trim($text);
        
        // Try to extract JSON from response
        if (preg_match('/\{[\s\S]*\}/s', $text, $matches)) {
            $recipe_data = json_decode($matches[0], true);
            
            if ($recipe_data && json_last_error() === JSON_ERROR_NONE) {
                wp_send_json_success($recipe_data);
            }
        }
        
        // If JSON parsing failed, create simple structured data from text
        $lines = explode("\n", $content);
        $simple_recipe = array(
            'name' => $lines[0] ?? 'Imported Recipe',
            'summary' => '',
            'ingredients' => array(),
            'instructions' => array()
        );
        
        $in_ingredients = false;
        $in_instructions = false;
        
        foreach ($lines as $line) {
            $line = trim($line);
            if (empty($line)) continue;
            
            if (stripos($line, 'ingredient') !== false) {
                $in_ingredients = true;
                $in_instructions = false;
                continue;
            }
            if (stripos($line, 'instruction') !== false || stripos($line, 'direction') !== false) {
                $in_instructions = true;
                $in_ingredients = false;
                continue;
            }
            
            if ($in_ingredients) {
                $simple_recipe['ingredients'][] = $line;
            } elseif ($in_instructions) {
                $simple_recipe['instructions'][] = $line;
            }
        }
        
        wp_send_json_success($simple_recipe);
    }
    
    wp_send_json_error(array('message' => 'No response from Gemini API. Response: ' . wp_remote_retrieve_body($response)));
}
add_action('wp_ajax_wprm_gemini_fill_recipe', 'wprm_gemini_fill_recipe_ajax');

/**
 * Add settings link to plugins page
 */
function wprm_pro_unlocker_settings_link($links) {
    $settings_link = '<a href="' . admin_url('admin.php?page=wprm_settings') . '">Configure Gemini</a>';
    array_unshift($links, $settings_link);
    return $links;
}
add_filter('plugin_action_links_' . plugin_basename(__FILE__), 'wprm_pro_unlocker_settings_link');

/**
 * Show admin notice with instructions
 */
function wprm_pro_unlocker_admin_notice() {
    if (!get_option('wprm_settings_features_gemini_api_key')) {
        ?>
        <div class="notice notice-info is-dismissible">
            <p><strong>🔥 WP Recipe Maker Pro Unlocked!</strong></p>
            <p>📝 To use Gemini AI features:</p>
            <ol>
                <li>Get your free API key from: <a href="https://makersuite.google.com/app/apikey" target="_blank">Google AI Studio</a></li>
                <li>Go to <a href="<?php echo admin_url('admin.php?page=wprm_settings'); ?>">WP Recipe Maker → Settings</a></li>
                <li>Enter your Gemini API key</li>
                <li>Look for the "✨ Fill with Gemini AI" button when editing recipes!</li>
            </ol>
        </div>
        <?php
    }
}
add_action('admin_notices', 'wprm_pro_unlocker_admin_notice');
