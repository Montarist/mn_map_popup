<?php
/**
 *
 * @link              https://montarist.com
 * @since             1.0.0
 * @package           MN Map Popup
 *
 * @wordpress-plugin
 * Plugin Name:       MN Map Popup  
 * Plugin URI:        https://montarist.com/mn-map-popups-plugin
 * Description:       MN Map Popup Plugin allows you to create a custom post type for popups and display dealer or representative information on selected regions using dnomak Turkey Map.
 * Version:           1.0.0
 * Author:            The Montarist Team
 * Author URI:        https://montarist.com
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       mn-map-popup
 * Domain Path:       /languages
 */

// Admin Settings
require_once plugin_dir_path(__FILE__) . 'map_popup_admin_settings.php';

if (map_popup_is_api_key_valid()) {

    // Custom Post Type
    require_once plugin_dir_path(__FILE__) . 'map_popup_post_type.php';

    // Metaboxes
    require_once plugin_dir_path(__FILE__) . 'map_popup_metaboxes.php';

    // Shortcode
    require_once plugin_dir_path(__FILE__) . 'map_popup_shortcode.php';

    function mn_map_popup_enqueue_scripts($hook)
    {
        if ('post.php' === $hook || 'post-new.php' === $hook) {
            wp_enqueue_style('mn-map-popup-fontawesome', 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css', array(), null);
            wp_enqueue_style('mn-map-popup-admin-css', plugin_dir_url(__FILE__) . 'admin.css', array(), '1.0.0');

            wp_enqueue_media();
            wp_enqueue_script('mn-map-popup-media-upload', plugin_dir_url(__FILE__) . 'media-upload.js', array('jquery'));
            wp_enqueue_script('mn-map-popup-admin-script', plugin_dir_url(__FILE__) . 'admin-script.js', array('jquery'), "1.0.0", true);
        }
    }
    add_action('admin_enqueue_scripts', 'mn_map_popup_enqueue_scripts');

    function mn_map_popup_enqueue_assets()
    {
        // Register and enqueue your CSS files
        wp_enqueue_style('mn-map-popup-fontawesome', 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css', array(), null);
        wp_enqueue_style('mn-map-popup-google-fonts', 'https://fonts.googleapis.com/css?family=Open+Sans:400,300,300italic,400italic,600,600italic,700,700italic,800,800italic', array(), null);
        wp_enqueue_style('svg-turkiye-haritasi-css', plugin_dir_url(__FILE__) . 'view/css/svg-turkiye-haritasi.css', array(), '1.0.0');

        // Register and enqueue your JavaScript files
        wp_enqueue_script('svg-turkiye-haritasi-js', plugin_dir_url(__FILE__) . 'view/js/svg-turkiye-haritasi.js', array('jquery'), '1.0.0', false);
    }
    add_action('wp_enqueue_scripts', 'mn_map_popup_enqueue_assets');
}

?>