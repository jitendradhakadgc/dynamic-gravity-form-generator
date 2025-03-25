<?php
/**
 * Plugin Name: Dynamic Gravity Form Generator
 * Description: Dynamic Gravity Form Generator is a powerful WordPress plugin that allows you to create Gravity Forms dynamically using custom code and AJAX. With just a button click, users can generate new forms instantly without manually setting them up.
 * Version: 1.0
 * Author: Jitendra Dhakad
 * License: GPL2
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}
 
// Define plugin directory path
define('MY_CUSTOM_PLUGIN_PATH', plugin_dir_path(__FILE__));

// Include AJAX handler
require_once MY_CUSTOM_PLUGIN_PATH . 'includes/ajax-handler.php';

/**
 * Add a new admin menu page
 */
function my_custom_plugin_admin_menu() {
    add_menu_page(
        'Custom AJAX Page',  // Page title
        'Gravity Form Generator',       // Menu title
        'manage_options',    // Capability
        'my-custom-ajax',    // Menu slug
        'my_custom_plugin_admin_page', // Callback function
        'dashicons-admin-generic', // Icon
        90 // Position
    );
}
add_action('admin_menu', 'my_custom_plugin_admin_menu');

/**
 * Render the admin page content
 */
function my_custom_plugin_admin_page() {
    ?>
    <div class="wrap">
        <h1>Dynamic Gravity Form Generator</h1>
        <button id="my-ajax-button" class="button button-primary">Create Gravity Form</button>
        <p id="ajax-response"></p>
    </div>
    
    <script>
        var ajaxurl = "<?php echo admin_url('admin-ajax.php'); ?>";
        var security_nonce = "<?php echo wp_create_nonce('my_ajax_nonce'); ?>";
    </script>
    <?php
}

/**
 * Enqueue admin scripts
 */
function my_custom_plugin_enqueue_scripts($hook) {
    if ($hook !== 'toplevel_page_my-custom-ajax') {
        return;
    }
    
    wp_enqueue_script(
        'my-custom-plugin-js',
        plugin_dir_url(__FILE__) . 'assets/admin.js',
        array('jquery'),
        '1.0',
        true
    );
}
add_action('admin_enqueue_scripts', 'my_custom_plugin_enqueue_scripts');
