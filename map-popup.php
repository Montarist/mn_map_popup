<?php

/**
 *
 * @link              https://herenkeskin.com
 * @since             1.0.0
 * @package           Map Popup
 *
 * @wordpress-plugin
 * Plugin Name:       Map Popup Plugin 
 * Plugin URI:        localhost
 * Description:       Map popup plugin with JQvmap
 * Version:           1.0.0
 * Author:            Hasan Eren Keskin
 * Author URI:        https://herenkeskin.com
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       map-popup
 * Domain Path:       /languages
 */

//Register shortcode
add_shortcode( 'map_popup', 'map_popup_page' );
function map_popup_page() {
   	$template_name = 'map-popup-template.php';
	$located = plugin_dir_path(__FILE__) . $template_name; 
    load_template( $located, true );
}

require_once(plugin_dir_path(__FILE__) . 'map-popup-admin-settings-page.php');