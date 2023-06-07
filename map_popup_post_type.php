<?php

// Register custom post type
function map_popup_register_post_type()
{
    if (!map_popup_is_api_key_valid()) {
        return;
    }
    $labels = array(
        'name' => __('Popups', 'mn-map-popup'),
        'singular_name' => __('Popups', 'mn-map-popup')
    );

    $args = array(
        'labels' => $labels,
        'public' => true,
        'publicly_queryable' => false,
        'show_ui' => true,
        'show_in_menu' => true,
        'show_in_nav_menus' => true,
        'show_in_admin_bar' => true,
        'menu_position' => 20,
        'menu_icon' => 'dashicons-location-alt',
        'has_archive' => false,
        'supports' => array('title', 'thumbnail')
    );

    register_post_type('map_popup_post', $args);
}
add_action('init', 'map_popup_register_post_type');

?>