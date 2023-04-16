<?php

// Register custom post type
function map_popup_register_post_type()
{
    if (!map_popup_is_api_key_valid()) {
        return;
    }
    $labels = array(
        'name' => __('Popups', 'map-popup'),
        'singular_name' => __('Popups', 'map-popup')
    );

    $args = array(
        'labels' => $labels,
        'public' => true,
        'publicly_queryable' => false,
        'has_archive' => false,
        'supports' => array('title', 'thumbnail', 'custom-fields')
    );

    register_post_type('map_popup_post', $args);
}
add_action('init', 'map_popup_register_post_type');

?>