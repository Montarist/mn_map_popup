<?php

function map_popup_enqueue_color_picker()
{
    wp_enqueue_style('wp-color-picker');
    wp_enqueue_script('wp-color-picker');
}
add_action('admin_enqueue_scripts', 'map_popup_enqueue_color_picker');

// Create settings page

function map_popup_create_settings_page()
{
    if (!map_popup_is_api_key_valid()) {
        add_menu_page(
            __('Map Popup Settings', 'map-popup'),
            __('Map Popup', 'map-popup'),
            'manage_options',
            'map-popup-settings',
            'map_popup_render_api_key_page',
            'dashicons-location-alt',
            100
        );
    } else {
        add_menu_page(
            __('Map Popup Settings', 'map-popup'),
            __('Map Popup', 'map-popup'),
            'manage_options',
            'map-popup-settings',
            'map_popup_render_settings_page',
            'dashicons-location-alt',
            100
        );
    }
}
add_action('admin_menu', 'map_popup_create_settings_page');

function map_popup_render_api_key_page()
{
    ?>
    <div class="wrap">
        <h1>
            <?php _e('Map Popup Settings', 'map-popup'); ?>
        </h1>

        <form method="post" action="options.php">
            <?php
            settings_fields('map_popup_settings');
            do_settings_sections('map-popup-api-key');
            submit_button();
            ?>
        </form>
    </div>
    <?php
}

function map_popup_register_api_key_settings()
{
    register_setting('map_popup_settings', 'map_api_key');
    add_settings_section('map_popup_api_key_section', null, null, 'map-popup-api-key');
    add_settings_field(
        'map_api_key',
        __('API Key', 'map-popup'),
        'map_popup_api_key',
        'map-popup-api-key',
        'map_popup_api_key_section'
    );
}
add_action('admin_init', 'map_popup_register_api_key_settings');

function map_popup_init()
{
    if (!map_popup_is_api_key_valid()) {
        // Geçerli bir API anahtarı yoksa, eklenti işlemlerini başlatmayın
        return;
    }

    // Eklenti işlemlerini burada başlatın (shortcode, filtre
}
add_action('init', 'map_popup_init');

function map_popup_admin_notices()
{
    if (!map_popup_is_api_key_valid()) {
        ?>
        <div class="notice notice-warning is-dismissible">
            <p>
                <?php _e('Map Popup: Please enter a valid API key. Otherwise, the plugin will not work.', 'map-popup'); ?>
            </p>
        </div>
        <?php
    }
}
add_action('admin_notices', 'map_popup_admin_notices');


// Render settings page
function map_popup_render_settings_page()
{

    ?>
    <div class="wrap">
        <h1>
            <?php _e('Map Popup Settings', 'map-popup'); ?>
        </h1>
        <p>
            <?php _e('Usage: [map_popup]', 'map-popup'); ?>
        </p>

        <form method="post" action="options.php">
            <?php
            settings_fields('map_popup_settings');
            do_settings_sections('map_popup_settings');
            submit_button();
            ?>
        </form>
    </div>
    <?php
}

// Register settings
function map_popup_register_settings()
{
    register_setting('map_popup_settings', 'map_api_key');

    // 'map_background_color' seçeneği için varsayılan değeri ayarlayın
    if (map_popup_is_api_key_valid()) {
        update_option('map_background_color', '#222222');
        update_option('map_hover_color', '#1094F6');
        update_option('map_selected_area_color', '#7C96AB');
        update_option('popup_header_color', '#E74646');
        update_option('popup_text_color', '#454545');
        update_option('popup_button_background_color', '#E74646');
        update_option('popup_button_text_color', '#FFFFFF');
    }
    register_setting('map_popup_settings', 'map_background_color');
    register_setting('map_popup_settings', 'map_hover_color');
    register_setting('map_popup_settings', 'map_selected_area_color');
    register_setting('map_popup_settings', 'popup_header_color');
    register_setting('map_popup_settings', 'popup_text_color');
    register_setting('map_popup_settings', 'popup_button_background_color');
    register_setting('map_popup_settings', 'popup_button_text_color');

    add_settings_section('map_popup_main_section', null, null, 'map_popup_settings');

    add_settings_field(
        'map_api_key',
        __('API Key', 'map-popup'),
        'map_popup_api_key',
        'map_popup_settings',
        'map_popup_main_section'
    );

    add_settings_field(
        'map_background_color',
        __('Map Background Color', 'map-popup'),
        'map_popup_render_color_1',
        'map_popup_settings',
        'map_popup_main_section'
    );
    add_settings_field(
        'map_hover_color',
        __('Map Hover Color', 'map-popup'),
        'map_popup_render_color_2',
        'map_popup_settings',
        'map_popup_main_section'
    );
    add_settings_field(
        'map_selected_area_color',
        __('Map Selected Area Color', 'map-popup'),
        'map_popup_render_color_5',
        'map_popup_settings',
        'map_popup_main_section'
    );
    add_settings_field(
        'popup_header_color',
        __('Popup Header Color', 'map-popup'),
        'map_popup_render_color_3',
        'map_popup_settings',
        'map_popup_main_section'
    );
    add_settings_field(
        'popup_text_color',
        __('Popup Text Color', 'map-popup'),
        'map_popup_render_color_4',
        'map_popup_settings',
        'map_popup_main_section'
    );
    add_settings_field(
        'popup_button_background_color',
        __('Popup Button Background Color', 'map-popup'),
        'map_popup_render_color_6',
        'map_popup_settings',
        'map_popup_main_section'
    );
    add_settings_field(
        'popup_button_text_color',
        __('Popup Button Text Color', 'map-popup'),
        'map_popup_render_color_7',
        'map_popup_settings',
        'map_popup_main_section'
    );
}
add_action('admin_init', 'map_popup_register_settings');
function is_valid_api_key($api_key)
{
    // Web sitesi URL'sini alın
    $website_url = get_site_url();

    // API isteği için URL'yi oluşturun
    $request_url = 'https://herenkeskin.com/api-key-validation.php?api_key=' . urlencode($api_key) . '&website=' . urlencode($website_url);

    // Uzak isteği gerçekleştirin
    $response = wp_remote_get($request_url);

    if (is_wp_error($response)) {
        // Hata durumunda uygun bir hata mesajı döndürün
        return false;
    }

    $data = json_decode(wp_remote_retrieve_body($response), true);

    return isset($data['status']) && $data['status'] === 'success';
}

function map_popup_is_api_key_valid()
{
    $api_key = get_option('map_api_key');
    return !empty($api_key) && is_valid_api_key($api_key);
}

function map_popup_api_key()
{
    $api_key = get_option('map_api_key');

    if (!map_popup_is_api_key_valid()) {
        echo '<p style="color: red;">' . __('Valid API key is required.', 'map-popup') . '</p>';
    }

    ?>
    <input type="text" name="map_api_key" value="<?php echo esc_attr($api_key); ?>">
    <?php
}

function map_popup_render_color_1()
{
    $color = get_option('map_background_color');
    ?>
    <input type="text" name="map_background_color" value="<?php echo esc_attr($color); ?>" class="map-popup-color-field">
    <?php
}

function map_popup_render_color_2()
{
    $color = get_option('map_hover_color');
    ?>
    <input type="text" name="map_hover_color" value="<?php echo esc_attr($color); ?>" class="map-popup-color-field">
    <?php
}

function map_popup_render_color_3()
{
    $color = get_option('popup_header_color');
    ?>
    <input type="text" name="popup_header_color" value="<?php echo esc_attr($color); ?>" class="map-popup-color-field">
    <?php
}
function map_popup_render_color_4()
{
    $color = get_option('popup_text_color');
    ?>
    <input type="text" name="popup_text_color" value="<?php echo esc_attr($color); ?>" class="map-popup-color-field">
    <?php
}
function map_popup_render_color_5()
{
    $color = get_option('map_selected_area_color');
    ?>
    <input type="text" name="map_selected_area_color" value="<?php echo esc_attr($color); ?>" class="map-popup-color-field">
    <?php
}
function map_popup_render_color_6()
{
    $color = get_option('popup_button_background_color');
    ?>
    <input type="text" name="popup_button_background_color" value="<?php echo esc_attr($color); ?>"
        class="map-popup-color-field">
    <?php
}
function map_popup_render_color_7()
{
    $color = get_option('popup_button_text_color');
    ?>
    <input type="text" name="popup_button_text_color" value="<?php echo esc_attr($color); ?>" class="map-popup-color-field">
    <?php
}

function map_popup_admin_footer_script()
{
    ?>
    <script>
        (function ($) {
            $(function () {
                $('.map-popup-color-field').wpColorPicker();
            });
        })(jQuery);
    </script>
    <?php
}
add_action('admin_footer', 'map_popup_admin_footer_script');

?>