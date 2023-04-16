<?php
/**
 *
 * @link              https://herenkeskin.com
 * @since             1.0.0
 * @package           Map Popup
 *
 * @wordpress-plugin
 * Plugin Name:       Map Popup Plugin 
 * Plugin URI:        https://herenkeskin.com/wordpress-map-popups-plugin
 * Description:       Map popup plugin with JQvmap
 * Version:           1.0.0
 * Author:            Hasan Eren Keskin
 * Author URI:        https://herenkeskin.com
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       map-popup
 * Domain Path:       /languages
 */
// Admin Settings
require_once plugin_dir_path(__FILE__) . 'map_popup_admin_settings.php';

// Custom Post Type
require_once plugin_dir_path(__FILE__) . 'map_popup_post_type.php';

// Metaboxes
require_once plugin_dir_path(__FILE__) . 'map_popup_metaboxes.php';

// Shortcode
require_once plugin_dir_path(__FILE__) . 'map_popup_shortcode.php';

function map_popup_enqueue_scripts($hook)
{
    if ('post.php' === $hook || 'post-new.php' === $hook) {
        wp_enqueue_style('map_popup-fontawesome', 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css', array(), null);
        wp_enqueue_style('map-popup-admin-css', plugin_dir_url(__FILE__) . 'admin.css', array(), '1.0.0');

        wp_enqueue_media();
        wp_enqueue_script('map-popup-media-upload', plugin_dir_url(__FILE__) . 'media-upload.js', array('jquery'));
        wp_enqueue_script('map-popup-admin-script', plugin_dir_url(__FILE__) . 'admin-script.js', array('jquery'), "1.0.0", true);
    }
}
add_action('admin_enqueue_scripts', 'map_popup_enqueue_scripts');

function map_popup_enqueue_assets()
{
    // Register and enqueue your CSS files
    wp_enqueue_style('map_popup-fontawesome', 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css', array(), null);
    wp_enqueue_style('map_popup-google-fonts', 'https://fonts.googleapis.com/css?family=Open+Sans:400,300,300italic,400italic,600,600italic,700,700italic,800,800italic', array(), null);
    wp_enqueue_style('svg-turkiye-haritasi-css', plugin_dir_url(__FILE__) . 'view/css/svg-turkiye-haritasi.css', array(), '1.0.0');

    // Register and enqueue your JavaScript files
    wp_enqueue_script('svg-turkiye-haritasi-js', plugin_dir_url(__FILE__) . 'view/js/svg-turkiye-haritasi.js', array('jquery'), '1.0.0', false);
}
add_action('wp_enqueue_scripts', 'map_popup_enqueue_assets');

?>