<?php

function map_popup_enqueue_color_picker()
{
    wp_enqueue_style('wp-color-picker');
    wp_enqueue_script('wp-color-picker');
}
add_action('admin_enqueue_scripts', 'map_popup_enqueue_color_picker');

function check_license($api_key)
{

    if (!isset($api_key)) {
        set_transient('api_key_validity', "Please enter a valid API key.", 60);
        return false;
    }

    $website = $_SERVER['HTTP_HOST'];
    $url = 'https://main--montarist-licence-manager.netlify.app/.netlify/functions/check-license-exists';

    $body = array(
        'productId' => 'P2hKsZFARfAPMhgoBEVm',
        'website' => $website,
    );

    $options = array(
        'method' => 'POST',
        'headers' => array(
            'Content-Type' => 'application/json',
        ),
        'body' => json_encode($body),
    );

    $response = wp_remote_post($url, $options);

    if (is_wp_error($response)) {
        $error = $response->get_error_message();
        set_transient('api_key_validity', "An error occurred while verifying the license. Error: $error", 60);
        return false;
    } else {
        $body = json_decode($response['body'], true);

        if ($body['exists'] == false) {
            set_transient('api_key_validity', 'License does not exist for this website.', 60);
            return false;
        } else {
            if (!array_key_exists('licenseKey', $body)) {
                set_transient('api_key_validity', 'No license key returned from the server.', 60);
                return false;
            }

            $licenseKey = $body['licenseKey'];

            if ($licenseKey == $api_key) {
                delete_transient('api_key_validity');
                return true;
            } else {
                set_transient('api_key_validity', 'The API key provided does not match with the existing license.', 60);
                return false;
            }
        }
    }
}

function map_popup_is_api_key_valid()
{
    $mn_map_popup_options = get_option('mn_map_popup_option_name');
    $api_key_0 = $mn_map_popup_options['api_key_0'];
    return !empty($api_key_0) && check_license($api_key_0);
}

class MNMapPopup
{
    private $mn_map_popup_options;

    public function __construct()
    {
        add_action('admin_menu', array($this, 'mn_map_popup_add_plugin_page'));
        add_action('admin_init', array($this, 'mn_map_popup_page_init'));
        add_action('admin_footer', array($this, 'map_popup_admin_footer_script'));
        add_action('admin_notices', array($this, 'mn_map_popup_admin_notices'));
    }


    public function mn_map_popup_add_plugin_page()
    {
        add_menu_page(
            'MN Map Popup',
            'MN Map Popup',
            'manage_options',
            'mn-map-popup',
            array($this, 'mn_map_popup_create_admin_page'),
            'dashicons-location-alt',
            75
        );
    }

    public function mn_map_popup_create_admin_page()
    {
        $this->mn_map_popup_options = get_option('mn_map_popup_option_name'); ?>

        <div class="wrap" style="background: white; padding: 20px; border: 1px solid #ddd; border-radius: 5px;">
            <a href="https://montarist.com">
                <img src="<?php echo esc_url(plugin_dir_url(__FILE__) . 'images/logo-black.png'); ?>"
                    alt="<?php bloginfo('name'); ?>" width="300px">
            </a>
            <h2>MN Map Popup v1.0.0</h2>
            <p>MN Map Popup Plugin allows you to create a custom post type for popups and display dealer or representative
                information on selected regions using dnomak Turkey Map.</p>
            <p><b>Usage</b>: [mn_map_popup]</p>
            <p><b>Usage</b>: echo do_shortcode( '[mn_map_popup]' ); </p>

            <?php settings_errors(); ?>

            <form method="post" action="options.php">
                <?php
                settings_fields('mn_map_popup_option_group');

                if (!map_popup_is_api_key_valid()) { ?>
                    <a href="https://montarist-licence-manager.netlify.app/" target="_blank"
                        style="display: inline-block; margin: 10px 0; background: #ffc800; color: #263a41; border-radius: 5px; padding: 12px 32px; text-align: center; text-decoration: none; font-weight: bold;">Get
                        Free API Key</a>
                    <?php
                }

                do_settings_sections('mn-map-popup-admin-api-section');

                if (map_popup_is_api_key_valid()) {
                    do_settings_sections('mn-map-popup-admin-map-settings-section');
                }

                submit_button();
                ?>
            </form>
        </div>
    <?php }

    public function mn_map_popup_page_init()
    {
        register_setting(
            'mn_map_popup_option_group',
            'mn_map_popup_option_name',
            array($this, 'mn_map_popup_sanitize')
        );

        add_settings_section(
            'mn_map_popup_api_section',
            'API Key Settings',
            array($this, 'mn_map_popup_section_info'),
            'mn-map-popup-admin-api-section'
        );

        add_settings_section(
            'mn_map_popup_setting_section',
            'Map Settings',
            array($this, 'mn_map_popup_section_info'),
            'mn-map-popup-admin-map-settings-section'
        );

        add_settings_field(
            'api_key_0',
            'API Key',
            array($this, 'api_key_0_callback'),
            'mn-map-popup-admin-api-section',
            'mn_map_popup_api_section'
        );

        add_settings_field(
            'map_background_color_1',
            'Map Background Color',
            array($this, 'map_background_color_1_callback'),
            'mn-map-popup-admin-map-settings-section',
            'mn_map_popup_setting_section'
        );

        add_settings_field(
            'map_hover_color_2',
            'Map Hover Color',
            array($this, 'map_hover_color_2_callback'),
            'mn-map-popup-admin-map-settings-section',
            'mn_map_popup_setting_section'
        );

        add_settings_field(
            'map_selected_area_color_3',
            'Map Selected Area Color',
            array($this, 'map_selected_area_color_3_callback'),
            'mn-map-popup-admin-map-settings-section',
            'mn_map_popup_setting_section'
        );

        add_settings_field(
            'map_mouseover_area_color_10',
            'Map Mouseover Area Color',
            array($this, 'map_mouseover_area_color_10_callback'),
            'mn-map-popup-admin-map-settings-section',
            'mn_map_popup_setting_section'
        );

        add_settings_field(
            'representative_title_text_8',
            'Representative Title Text',
            array($this, 'representative_title_text_8_callback'),
            'mn-map-popup-admin-map-settings-section',
            'mn_map_popup_setting_section'
        );

        add_settings_field(
            'dealer_title_text_9',
            'Dealer Title Text',
            array($this, 'dealer_title_text_9_callback'),
            'mn-map-popup-admin-map-settings-section',
            'mn_map_popup_setting_section'
        );

        add_settings_field(
            'popup_header_color_4',
            'Popup Header Color',
            array($this, 'popup_header_color_4_callback'),
            'mn-map-popup-admin-map-settings-section',
            'mn_map_popup_setting_section'
        );

        add_settings_field(
            'popup_text_color_5',
            'Popup Text Color',
            array($this, 'popup_text_color_5_callback'),
            'mn-map-popup-admin-map-settings-section',
            'mn_map_popup_setting_section'
        );

        add_settings_field(
            'popup_button_background_color_6',
            'Popup Button Background Color',
            array($this, 'popup_button_background_color_6_callback'),
            'mn-map-popup-admin-map-settings-section',
            'mn_map_popup_setting_section'
        );

        add_settings_field(
            'popup_button_text_color_7',
            'Popup Button Text Color',
            array($this, 'popup_button_text_color_7_callback'),
            'mn-map-popup-admin-map-settings-section',
            'mn_map_popup_setting_section'
        );

    }

    public function mn_map_popup_sanitize($input)
    {
        $sanitary_values = array();
        if (isset($input['api_key_0'])) {
            $sanitary_values['api_key_0'] = sanitize_text_field($input['api_key_0']);
        }

        if (isset($input['map_background_color_1'])) {
            $sanitary_values['map_background_color_1'] = sanitize_text_field($input['map_background_color_1']);
        }

        if (isset($input['map_hover_color_2'])) {
            $sanitary_values['map_hover_color_2'] = sanitize_text_field($input['map_hover_color_2']);
        }

        if (isset($input['map_selected_area_color_3'])) {
            $sanitary_values['map_selected_area_color_3'] = sanitize_text_field($input['map_selected_area_color_3']);
        }

        if (isset($input['popup_header_color_4'])) {
            $sanitary_values['popup_header_color_4'] = sanitize_text_field($input['popup_header_color_4']);
        }

        if (isset($input['popup_text_color_5'])) {
            $sanitary_values['popup_text_color_5'] = sanitize_text_field($input['popup_text_color_5']);
        }

        if (isset($input['popup_button_background_color_6'])) {
            $sanitary_values['popup_button_background_color_6'] = sanitize_text_field($input['popup_button_background_color_6']);
        }

        if (isset($input['popup_button_text_color_7'])) {
            $sanitary_values['popup_button_text_color_7'] = sanitize_text_field($input['popup_button_text_color_7']);
        }

        if (isset($input['representative_title_text_8'])) {
            $sanitary_values['representative_title_text_8'] = sanitize_text_field($input['representative_title_text_8']);
        }

        if (isset($input['dealer_title_text_9'])) {
            $sanitary_values['dealer_title_text_9'] = sanitize_text_field($input['dealer_title_text_9']);
        }

        if (isset($input['map_mouseover_area_color_10'])) {
            $sanitary_values['map_mouseover_area_color_10'] = sanitize_text_field($input['map_mouseover_area_color_10']);
        }

        return $sanitary_values;
    }

    public function mn_map_popup_section_info()
    {

    }

    function mn_map_popup_admin_notices()
    {
        $mn_map_popup_options = get_option('mn_map_popup_option_name');
        $api_key_0 = $mn_map_popup_options['api_key_0'];

        if ($api_key_0) {
            $api_key_validity = get_transient('api_key_validity');

            if ($api_key_validity !== false) {
                ?>
                <div class="notice notice-warning is-dismissible">
                    <p>
                        <?php _e('MN Map Popup: ' . $api_key_validity, 'mn-map-popup'); ?>
                    </p>
                </div>
                <?php
            }
        } else {
            ?>
            <div class="notice notice-warning is-dismissible">
                <p>
                    <?php _e('MN Map Popup: Please enter a valid API key.', 'mn-map-popup'); ?>
                </p>
            </div>
            <?php
        }
    }


    public function api_key_0_callback()
    {
        printf(
            '<input class="regular-text" type="text" name="mn_map_popup_option_name[api_key_0]" id="api_key_0" value="%s">',
            isset($this->mn_map_popup_options['api_key_0']) ? esc_attr($this->mn_map_popup_options['api_key_0']) : ''
        );
    }

    public function map_background_color_1_callback()
    {
        printf(
            '<input class="regular-text map-popup-color-field" type="text" name="mn_map_popup_option_name[map_background_color_1]" id="map_background_color_1" value="%s">',
            isset($this->mn_map_popup_options['map_background_color_1']) ? esc_attr($this->mn_map_popup_options['map_background_color_1']) : '#264653'
        );
    }

    public function map_hover_color_2_callback()
    {
        printf(
            '<input class="regular-text map-popup-color-field" type="text" name="mn_map_popup_option_name[map_hover_color_2]" id="map_hover_color_2" value="%s">',
            isset($this->mn_map_popup_options['map_hover_color_2']) ? esc_attr($this->mn_map_popup_options['map_hover_color_2']) : '#2a9d8f'
        );
    }

    public function map_selected_area_color_3_callback()
    {
        printf(
            '<input class="regular-text map-popup-color-field" type="text" name="mn_map_popup_option_name[map_selected_area_color_3]" id="map_selected_area_color_3" value="%s">',
            isset($this->mn_map_popup_options['map_selected_area_color_3']) ? esc_attr($this->mn_map_popup_options['map_selected_area_color_3']) : '#e9c46a'
        );
    }

    public function map_mouseover_area_color_10_callback()
    {
        printf(
            '<input class="regular-text map-popup-color-field" type="text" name="mn_map_popup_option_name[map_mouseover_area_color_10]" id="map_mouseover_area_color_10" value="%s">',
            isset($this->mn_map_popup_options['map_mouseover_area_color_10']) ? esc_attr($this->mn_map_popup_options['map_mouseover_area_color_10']) : '#118ab2'
        );
    }

    public function popup_header_color_4_callback()
    {
        printf(
            '<input class="regular-text map-popup-color-field" type="text" name="mn_map_popup_option_name[popup_header_color_4]" id="popup_header_color_4" value="%s">',
            isset($this->mn_map_popup_options['popup_header_color_4']) ? esc_attr($this->mn_map_popup_options['popup_header_color_4']) : '#f4a261'
        );
    }

    public function popup_text_color_5_callback()
    {
        printf(
            '<input class="regular-text map-popup-color-field" type="text" name="mn_map_popup_option_name[popup_text_color_5]" id="popup_text_color_5" value="%s">',
            isset($this->mn_map_popup_options['popup_text_color_5']) ? esc_attr($this->mn_map_popup_options['popup_text_color_5']) : '#e76f51'
        );
    }

    public function popup_button_background_color_6_callback()
    {
        printf(
            '<input class="regular-text map-popup-color-field" type="text" name="mn_map_popup_option_name[popup_button_background_color_6]" id="popup_button_background_color_6" value="%s">',
            isset($this->mn_map_popup_options['popup_button_background_color_6']) ? esc_attr($this->mn_map_popup_options['popup_button_background_color_6']) : '#219ebc'
        );
    }

    public function popup_button_text_color_7_callback()
    {
        printf(
            '<input class="regular-text map-popup-color-field" type="text" name="mn_map_popup_option_name[popup_button_text_color_7]" id="popup_button_text_color_7" value="%s">',
            isset($this->mn_map_popup_options['popup_button_text_color_7']) ? esc_attr($this->mn_map_popup_options['popup_button_text_color_7']) : '#023047'
        );
    }
    public function representative_title_text_8_callback()
    {
        printf(
            '<input class="regular-text" type="text" name="mn_map_popup_option_name[representative_title_text_8]" id="representative_title_text_8" value="%s">',
            isset($this->mn_map_popup_options['representative_title_text_8']) ? esc_attr($this->mn_map_popup_options['representative_title_text_8']) : 'Representative Title Text'
        );
    }

    public function dealer_title_text_9_callback()
    {
        printf(
            '<input class="regular-text" type="text" name="mn_map_popup_option_name[dealer_title_text_9]" id="dealer_title_text_9" value="%s">',
            isset($this->mn_map_popup_options['dealer_title_text_9']) ? esc_attr($this->mn_map_popup_options['dealer_title_text_9']) : 'Dealer Title Text'
        );
    }

    public function map_popup_admin_footer_script()
    {
        ?>
        <script>     (function ($) { $(function () { $('.map-popup-color-field').wpColorPicker(); }); })(jQuery);
        </script>
        <?php
    }

}
if (is_admin())
    $mn_map_popup = new MNMapPopup();

/* 
 * Retrieve this value with:
 * $mn_map_popup_options = get_option( 'mn_map_popup_option_name' ); // Array of All Options
 * $api_key_0 = $mn_map_popup_options['api_key_0']; // API Key
 * $map_background_color_1 = $mn_map_popup_options['map_background_color_1']; // Map Background Color
 * $map_hover_color_2 = $mn_map_popup_options['map_hover_color_2']; // Map Hover Color
 * $map_selected_area_color_3 = $mn_map_popup_options['map_selected_area_color_3']; // Map Selected Area Color
 * $popup_header_color_4 = $mn_map_popup_options['popup_header_color_4']; // Popup Header Color
 * $popup_text_color_5 = $mn_map_popup_options['popup_text_color_5']; // Popup Text Color
 * $popup_button_background_color_6 = $mn_map_popup_options['popup_button_background_color_6']; // Popup Button Background Color
 * $popup_button_text_color_7 = $mn_map_popup_options['popup_button_text_color_7']; // Popup Button Text Color
 * $representative_title_text_8 = $mn_map_popup_options['representative_title_text_8']; // Representative Title Text
 * $dealer_title_text_9 = $mn_map_popup_options['dealer_title_text_9']; // Dealer Title Text
 */