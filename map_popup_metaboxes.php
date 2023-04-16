<?php

// Utils
require_once plugin_dir_path(__FILE__) . 'utils.php';

function map_popup_add_meta_boxes()
{
    add_meta_box(
        'map_popup_state_meta_box',
        __('States', 'map-popup'),
        'map_popup_state_meta_box',
        'map_popup_post',
        'normal',
        'default'
    );

    add_meta_box(
        'map_popup_representative_meta_box',
        __('Representative', 'map-popup'),
        'map_popup_representative_meta_box',
        'map_popup_post',
        'normal',
        'default'
    );

    add_meta_box(
        'map_popup_dealer_meta_box',
        __('Dealers', 'map-popup'),
        'map_popup_dealer_meta_box',
        'map_popup_post',
        'normal',
        'default'
    );
}
add_action('add_meta_boxes', 'map_popup_add_meta_boxes');

function map_popup_state_meta_box($post)
{
    // Nonce field for security
    wp_nonce_field('map_popup_state_nonce', 'map_popup_state_nonce_field');

    $cities = get_cities_array();

    $selected_cities = get_post_meta($post->ID, 'selected_cities', true);

    echo '<p>';
    foreach ($cities as $city) {
        $checked = (isset($selected_cities[$city['id']]) && $selected_cities[$city['id']] == '1') ? 'checked' : '';
        echo '<input type="checkbox" id="city_' . $city['id'] . '" name="selected_cities[' . $city['id'] . ']" value="1" ' . $checked . ' />';
        echo '<label for="city_' . $city['id'] . '">' . $city['iladi'] . '</label><br>';
    }
    echo '</p>';
}

function map_popup_state_save_post($post_id)
{

    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return $post_id;
    }

    if (!current_user_can('edit_post', $post_id)) {
        return;
    }

    if (!isset($_POST['map_popup_state_nonce_field']) || !wp_verify_nonce($_POST['map_popup_state_nonce_field'], 'map_popup_state_nonce')) {
        return;
    }

    $selected_cities = isset($_POST['selected_cities']) ? $_POST['selected_cities'] : array();
    update_post_meta($post_id, 'selected_cities', $selected_cities);
}
add_action('save_post', 'map_popup_state_save_post');


function map_popup_representative_meta_box($post)
{
    wp_nonce_field(basename(__FILE__), 'representative_metabox_nonce');

    $representative_fields_data = get_post_meta($post->ID, 'representative_fields_data', true);

    // var_dump($representative_fields_data);

    $field_number = count($representative_fields_data['representative_position']);

    echo '<div id="representative_fields_container">';
    for ($i = 0; $i < $field_number; $i++) { ?>
        <div class="representative_field">
            <p>
                <label>
                    <?php echo __('Name', 'map-popup'); ?>:
                </label>
                <input type="text" name="representative_name[]"
                    value="<?php echo esc_attr($representative_fields_data['representative_name'][$i]); ?>">
            </p>
            <p>
                <label>
                    <?php echo __('Position', 'map-popup'); ?>:
                </label>
                <input type="text" name="representative_position[]"
                    value="<?php echo esc_attr($representative_fields_data['representative_position'][$i]); ?>">
            </p>
            <p>
                <label>
                    <?php echo __('Email', 'map-popup'); ?>:
                </label>
                <input type="text" name="representative_email[]"
                    value="<?php echo esc_attr($representative_fields_data['representative_email'][$i]); ?>">
            </p>
            <p>
                <label>
                    <?php echo __('Phone', 'map-popup'); ?>:
                </label>
                <input type="text" name="representative_phone[]"
                    value="<?php echo esc_attr($representative_fields_data['representative_phone'][$i]); ?>">
            </p>
            <p>
                <label>
                    <?php echo __('Photo', 'map-popup'); ?>:
                </label>
                <input type="hidden" class="image-url" name="representative_photo[]"
                    value="<?php echo esc_attr($representative_fields_data['representative_photo'][$i]); ?>">
                <img src="<?php echo esc_attr($representative_fields_data['representative_photo'][$i]); ?>"
                    class="image-preview" style="max-width: 100px;">
                <input type="button" class="upload-image-button" value="<?php echo __('Upload Image', 'map-popup'); ?>">
            </p>
            <p class="action_buttons remove">
                <i class="fa-solid fa-2xl fa-square-minus"></i>
                <input type="button" class="remove_representative_field_button"
                    value="<?php echo __('Remove Field', 'map-popup'); ?>">
            </p>
            <hr>
        </div>

        <?php
    }

    echo '</div>';

    // Add new field button
    ?>
    <p class="action_buttons add">
        <i class="fa-solid fa-2xl fa-square-plus"></i>
        <input type="button" id="add_representative_field_button" value="<?php echo __('Add Field', 'map-popup'); ?>">
    </p>
    <?php
}

function map_popup_representative_save_post($post_id)
{

    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return $post_id;
    }

    if (!current_user_can('edit_post', $post_id)) {
        return;
    }

    if (!isset($_POST['representative_metabox_nonce']) || !wp_verify_nonce($_POST['representative_metabox_nonce'], basename(__FILE__))) {
        return;
    }

    $representative_fields_data = array();
    $fields = array(
        'representative_name',
        'representative_position',
        'representative_email',
        'representative_phone',
        'representative_photo'
    );

    foreach ($fields as $field) {
        if (isset($_POST[$field])) {
            $representative_fields_data[$field] = $_POST[$field];
        }
    }

    update_post_meta($post_id, 'representative_fields_data', $representative_fields_data);
}
add_action('save_post', 'map_popup_representative_save_post');


function map_popup_dealer_meta_box($post)
{
    wp_nonce_field(basename(__FILE__), 'dealer_metabox_nonce');

    $dealer_fields_data = get_post_meta($post->ID, 'dealer_fields_data', true);

    // var_dump($dealer_fields_data);

    $field_number = count($dealer_fields_data['dealer_name']);

    echo '<div id="dealer_fields_container">';

    for ($i = 0; $i < $field_number; $i++) { ?>
        <div class="dealer_field">
            <p>
                <label>
                    <?php echo __('Name', 'map-popup'); ?>:
                </label>
                <input type="text" name="dealer_name[]" value="<?php echo esc_attr($dealer_fields_data['dealer_name'][$i]); ?>">
            </p>
            <p>
                <label>
                    <?php echo __('Position', 'map-popup'); ?>:
                </label>
                <input type="text" name="dealer_position[]"
                    value="<?php echo esc_attr($dealer_fields_data['dealer_position'][$i]); ?>">
            </p>
            <p>
                <label>
                    <?php echo __('Email', 'map-popup'); ?>:
                </label>
                <input type="text" name="dealer_email[]"
                    value="<?php echo esc_attr($dealer_fields_data['dealer_email'][$i]); ?>">
            </p>
            <p>
                <label>
                    <?php echo __('Phone', 'map-popup'); ?>:
                </label>
                <input type="text" name="dealer_phone[]"
                    value="<?php echo esc_attr($dealer_fields_data['dealer_phone'][$i]); ?>">
            </p>
            <p>
                <label>
                    <?php echo __('Photo', 'map-popup'); ?>:
                </label>
                <input type="hidden" class="image-url" name="dealer_photo[]"
                    value="<?php echo esc_attr($dealer_fields_data['dealer_photo'][$i]); ?>">
                <img src="<?php echo esc_attr($dealer_fields_data['dealer_photo'][$i]); ?>" class="image-preview"
                    style="max-width: 100px;">
                <input type="button" class="upload-image-button" value="<?php echo __('Upload Image', 'map-popup'); ?>">
            </p>
            <p class="action_buttons remove">
                <i class="fa-solid fa-2xl fa-square-minus"></i>
                <input type="button" class="remove_dealer_field_button" value="<?php echo __('Remove Field', 'map-popup'); ?>">
            </p>
            <hr>
        </div>

        <?php
    }

    echo '</div>';
    // Add new field button
    ?>
    <p class="action_buttons add">
        <i class="fa-solid fa-2xl fa-square-plus"></i>
        <input type="button" id="add_dealer_field_button" value="<?php echo __('Add Field', 'map-popup'); ?>">
    </p>
    <?php
}

function map_popup_dealer_save_post($post_id)
{

    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return $post_id;
    }

    if (!current_user_can('edit_post', $post_id)) {
        return;
    }

    if (!isset($_POST['dealer_metabox_nonce']) || !wp_verify_nonce($_POST['dealer_metabox_nonce'], basename(__FILE__))) {
        return;
    }

    $dealer_fields_data = array();
    $fields = array(
        'dealer_name',
        'dealer_email',
        'dealer_website',
        'dealer_phone',
        'dealer_fax',
        'dealer_address',
        'dealer_photo'
    );

    foreach ($fields as $field) {
        if (isset($_POST[$field])) {
            $dealer_fields_data[$field] = $_POST[$field];
        }
    }

    update_post_meta($post_id, 'dealer_fields_data', $dealer_fields_data);
}
add_action('save_post', 'map_popup_dealer_save_post');

?>