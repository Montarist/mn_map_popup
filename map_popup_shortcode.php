<?php

// Utils
require_once plugin_dir_path(__FILE__) . 'utils.php';

// Create shortcode
function mn_map_popup_shortcode($atts)
{
    if (!map_popup_is_api_key_valid()) {
        echo '<p style="color: red;">' . __('Valid API key is required.', 'mn-map-popup') . '</p>';
        return;
    }

    ob_start();
    $args = array(
        'post_type' => 'map_popup_post',
        'posts_per_page' => -1,
        'orderby' => 'date',
        'order' => 'ASC',
    );

    $map_popup_posts = new WP_Query($args);

    if (!$map_popup_posts->have_posts()) {
        echo __("Please add a popup window to show the map", 'mn-map-popup');
        return '';
    }

    $mn_map_popup_options = get_option('mn_map_popup_option_name'); // Array of All Options
    $api_key_0 = $mn_map_popup_options['api_key_0']; // API Key
    $map_background_color_1 = $mn_map_popup_options['map_background_color_1']; // Map Background Color
    $map_hover_color_2 = $mn_map_popup_options['map_hover_color_2']; // Map Hover Color
    $map_selected_area_color_3 = $mn_map_popup_options['map_selected_area_color_3']; // Map Selected Area Color
    $map_mouseover_area_color_10 = $mn_map_popup_options['map_mouseover_area_color_10']; // Map Selected Area Color
    $popup_header_color_4 = $mn_map_popup_options['popup_header_color_4']; // Popup Header Color
    $popup_text_color_5 = $mn_map_popup_options['popup_text_color_5']; // Popup Text Color
    $popup_button_background_color_6 = $mn_map_popup_options['popup_button_background_color_6']; // Popup Button Background Color
    $popup_button_text_color_7 = $mn_map_popup_options['popup_button_text_color_7']; // Popup Button Text Color
    $representative_title_text_8 = $mn_map_popup_options['representative_title_text_8']; // Representative Title Text
    $dealer_title_text_9 = $mn_map_popup_options['dealer_title_text_9']; // Dealer Title Text

    echo '<div class="map-popup-container">';

    // Turkey Map
    require_once plugin_dir_path(__FILE__) . 'view/turkey-map.php';

    echo '<div class="all-dealers">';

    while ($map_popup_posts->have_posts()) {
        $map_popup_posts->the_post();

        $representative_fields_data = get_post_meta(get_the_ID(), 'representative_fields_data', true);
        $dealer_fields_data = get_post_meta(get_the_ID(), 'dealer_fields_data', true);
        
        if (isset($dealer_fields_data['dealer_name']) && is_array($dealer_fields_data['dealer_name'])) {
            $dealer_field_number = count($dealer_fields_data['dealer_name']);
        } else {
            $dealer_field_number = 0;
        }

        if (isset($representative_fields_data['representative_name']) && is_array($representative_fields_data['representative_name'])) {
            $representative_field_number = count($representative_fields_data['representative_name']);
        } else {
            $representative_field_number = 0;
        }

        $selected_cities = get_post_meta(get_the_ID(), 'selected_cities', true);
        if ($selected_cities) {
            $cities = get_cities_array();
            $cities_alan_kodu = array();

            foreach ($cities as $city) {
                if (isset($selected_cities[$city['id']]) && $selected_cities[$city['id']] == '1') {
                    array_push($cities_alan_kodu, $city['alankodu']);
                }
            }
        } 

        $cities_alan_kodu_json = json_encode($cities_alan_kodu);

        ?>
        <div class="dealers-on-region" data-alankodu='<?php echo $cities_alan_kodu_json; ?>'>
            <i class="fa-solid fa-map-location-dot"></i>
            <div class="close">
                <i class="fa-solid fa-circle-xmark"></i>
            </div>
            <?php if ($representative_field_number > 0) { ?>
                <div class="common-information">
                    <h4>
                        <?php echo __($representative_title_text_8, 'mn-map-popup'); ?>
                    </h4>
                    <div class="common-content">
                        <?php for ($i = 0; $i < $representative_field_number; $i++) { ?>
                            <div class="representative">
                                <?php if ($representative_fields_data['representative_photo'][$i]) { ?>
                                    <div class="common-photo">
                                        <img src="<?php echo esc_attr($representative_fields_data['representative_photo'][$i]); ?>"
                                            alt="<?php echo esc_attr($representative_fields_data['representative_photo'][$i]); ?>"
                                            class="image-preview">
                                    </div>
                                <?php } ?>
                                <div class="common-detail">
                                    <?php if ($representative_fields_data['representative_name'][$i]) { ?>
                                        <h3>
                                            <i class="fa-solid fa-user"></i>
                                            <?php echo esc_attr($representative_fields_data['representative_name'][$i]); ?>
                                        </h3>
                                    <?php } ?>
                                    <?php if ($representative_fields_data['representative_position'][$i]) { ?>
                                        <p>
                                            <i class="fa-solid fa-bars-progress"></i>
                                            <span>
                                                <?php echo esc_attr($representative_fields_data['representative_position'][$i]); ?>
                                            </span>
                                        </p>
                                    <?php } ?>
                                    <?php if ($representative_fields_data['representative_email'][$i]) { ?>
                                        <p>
                                            <i class="fa-solid fa-envelope"></i>
                                            <span>
                                                <?php echo __('Officer Email', 'mn-map-popup'); ?>:
                                            </span>
                                            <a href="mailto:<?php echo esc_attr($representative_fields_data['representative_email'][$i]); ?>"
                                                target="_blank"><?php echo esc_attr($representative_fields_data['representative_email'][$i]); ?>
                                            </a>
                                        </p>
                                    <?php } ?>
                                    <?php if ($representative_fields_data['representative_phone'][$i]) { ?>
                                        <p>
                                            <i class="fa-solid fa-phone-volume"></i>
                                            <span>
                                                <?php echo __('Officer Phone', 'mn-map-popup'); ?>:
                                            </span>
                                            <a href="tel://<?php echo trim(esc_attr($representative_fields_data['representative_phone'][$i])); ?>"
                                                target="_blank">
                                                <?php echo esc_attr($representative_fields_data['representative_phone'][$i]); ?>
                                            </a>
                                        </p>
                                    <?php } ?>
                                </div>
                            </div>
                        <?php } ?>
                    </div>

                </div>
            <?php } ?>
            <?php if ($dealer_field_number > 0) { ?>
                <div class="common-information">
                    <h4>
                        <?php echo __($dealer_title_text_9, 'mn-map-popup'); ?>
                    </h4>
                    <div class="common-content">
                        <?php for ($i = 0; $i < $dealer_field_number; $i++) { ?>
                            <div class="dealer">
                                <?php if ($dealer_fields_data['dealer_photo'][$i]) { ?>
                                    <div class="common-photo">
                                        <img src="<?php echo esc_attr($dealer_fields_data['dealer_photo'][$i]); ?>"
                                            alt="<?php echo esc_attr($dealer_fields_data['dealer_photo'][$i]); ?>" class="image-preview">
                                    </div>
                                <?php } ?>
                                <div class="common-detail">

                                    <?php if ($dealer_fields_data['dealer_name'][$i]) { ?>
                                        <h3>
                                            <i class="fa-solid fa-cubes-stacked"></i>
                                            <?php echo esc_attr($dealer_fields_data['dealer_name'][$i]); ?>
                                        </h3>
                                    <?php } ?>

                                    <?php if ($dealer_fields_data['dealer_email'][$i]) { ?>
                                        <p>
                                            <i class="fa-solid fa-envelope"></i>
                                            <span>
                                                <?php echo __('Dealer Email', 'mn-map-popup'); ?>:
                                            </span>
                                            <a href="mailto:<?php echo esc_attr($dealer_fields_data['dealer_email'][$i]); ?>"
                                                target="_blank">
                                                <?php echo esc_attr($dealer_fields_data['dealer_email'][$i]); ?>
                                            </a>
                                        </p>
                                    <?php } ?>

                                    <?php if ($dealer_fields_data['dealer_website'][$i]) { ?>
                                        <p>
                                            <i class="fa-solid fa-earth-europe"></i>
                                            <span>
                                                <?php echo __('Dealer Website', 'mn-map-popup'); ?>:
                                            </span>
                                            <a href="<?php echo esc_attr($dealer_fields_data['dealer_website'][$i]); ?>" target="_blank">
                                                <?php echo esc_attr($dealer_fields_data['dealer_website'][$i]); ?>
                                            </a>
                                        </p>
                                    <?php } ?>

                                    <?php if ($dealer_fields_data['dealer_phone'][$i]) { ?>
                                        <p>
                                            <i class="fa-solid fa-phone-volume"></i>
                                            <span>
                                                <?php echo __('Dealer Phone', 'mn-map-popup'); ?>:
                                            </span>
                                            <a href="tel://<?php echo trim(esc_attr($dealer_fields_data['dealer_phone'][$i])); ?>"
                                                target="_blank">
                                                <?php echo esc_attr($dealer_fields_data['dealer_phone'][$i]); ?>
                                            </a>
                                        </p>
                                    <?php } ?>

                                    <?php if ($dealer_fields_data['dealer_fax'][$i]) { ?>
                                        <p>
                                            <i class="fa-solid fa-fax"></i>
                                            <span>
                                                <?php echo __('Dealer Fax', 'mn-map-popup'); ?>:
                                            </span>
                                            <?php echo esc_attr($dealer_fields_data['dealer_fax'][$i]); ?>
                                        </p>
                                    <?php } ?>

                                    <?php if ($dealer_fields_data['dealer_address'][$i]) { ?>
                                        <p>
                                            <i class="fa-solid fa-location-dot"></i>
                                            <span>
                                                <?php echo __('Dealer Address', 'mn-map-popup'); ?>:
                                            </span>
                                            <?php echo esc_attr($dealer_fields_data['dealer_address'][$i]); ?>
                                        </p>
                                    <?php } ?>

                                    <?php if ($dealer_fields_data['dealer_phone'][$i]) { ?>
                                        <p class="helper-buttons">
                                            <a href="https://www.google.com/maps/dir/<?php echo esc_attr($dealer_fields_data['dealer_address'][$i]); ?>"
                                                target="_blank" class="helper-button"><?php echo __('Get Directions', 'mn-map-popup'); ?></a>
                                            <a href="tel://<?php echo trim(esc_attr($dealer_fields_data['dealer_phone'][$i])); ?>"
                                                target="_blank" class="helper-button"><?php echo __('Call Dealer', 'mn-map-popup'); ?></a>
                                        </p>
                                    <?php } ?>

                                </div>
                            </div>
                        <?php } ?>
                    </div>
                </div>
            <?php } ?>
        </div>

        <?php

    }

    echo '</div>';
    echo '</div>';

    ?>
    <style>
        :root {
            --map_background_color_1:
                <?php echo $map_background_color_1; ?>
            ;
            --map_hover_color_2:
                <?php echo $map_hover_color_2; ?>
            ;
            --map_selected_area_color_3:
                <?php echo $map_selected_area_color_3; ?>
            ;
            --map_mouseover_area_color_10:
                <?php echo $map_mouseover_area_color_10; ?>
            ;
            --popup_header_color_4:
                <?php echo $popup_header_color_4; ?>
            ;
            --popup_text_color_5:
                <?php echo $popup_text_color_5; ?>
            ;
            --popup_button_background_color_6:
                <?php echo $popup_button_background_color_6; ?>
            ;
            --popup_button_text_color_7:
                <?php echo $popup_button_text_color_7; ?>
            ;
        }

        #svg-turkiye-haritasi path {
            fill: var(--map_background_color_1);
        }

        #svg-turkiye-haritasi path:hover {
            fill: var(--map_hover_color_2);
        }
        .il-isimleri div {
            background: var(--map_mouseover_area_color_10) !important;
        }

        .map-popup-container {
            position: relative;
        }

        .all-dealers {
            max-width: 40%;
            overflow: hidden;
        }

        .dealers-on-region {
            background: #fff;
            width: 40%;
            position: absolute;
            top: 10%;
            left: 30%;
            margin: 20px;
            padding: 10px 20px;
            border-radius: 4px;
            -webkit-box-shadow: 0px 0px 15px 0px rgba(0, 0, 0, 0.15);
            -moz-box-shadow: 0px 0px 15px 0px rgba(0, 0, 0, 0.15);
            box-shadow: 0px 0px 15px 0px rgba(0, 0, 0, 0.15);
            overflow-y: auto;
            max-height: 80%;
            z-index: 99;
        }

        .close {
            position: absolute;
            top: 0;
            right: 0;
            padding: 10px 15px;
            cursor: pointer;
        }

        .dealer,
        .representative {
            overflow: hidden;
            font-family: "Open Sans" !important;
            border-bottom: 1px solid #f7f7f7;
            display: flex;
            width: 100%;
            gap: 10px;
            padding-bottom: 15px;
            margin-bottom: 15px;
        }

        .common-content {
            display: flex;
            flex-direction: column;
        }

        .common-information {
            width: 100%;
            display: block;
            overflow: hidden;
            margin-bottom: 10px;
        }

        .common-information h4 {
            margin: 0 0 10px 0;
            color: var(--popup_header_color_4);
            font-size: 24px;
            font-weight: bold;
        }

        .common-information h3 {
            color: var(--popup_text_color_5);
            font-size: 16px;
            font-weight: bold;
            line-height: 16px;
            margin: 0;
            margin-bottom: 7px;
        }

        .common-information p {
            margin: 0;
            color: var(--popup_text_color_5);
            font-size: 12px;
            line-height: 16px;
        }

        .common-photo img {
            width: 125px;
            height: 125px;
            display: block;
            border-radius: 5px;
        }

        .common-detail {
            color: var(--popup_text_color_5);
            font-size: 12px;
            line-height: 16px;
            flex: 1;
        }

        .common-detail span {
            font-weight: 200;
            min-width: 125px;
            display: inline-block;
        }

        .common-detail p {
            margin: 0;
            padding: 0;
            font-size: 14px;
            margin-bottom: 7px;
        }

        .common-information i {
            width: 16px;
            height: 16px;
            margin-right: 2px;
            text-align: center;
        }

        .common-detail a {
            color: var(--popup_text_color_5);
            text-decoration: none;
            font-size: 14px;
        }

        .helper-buttons {
            display: block;
        }

        .helper-button {
            position: relative;
            display: inline-block;
            padding: 10px 20px;
            background-color: var(--popup_button_background_color_6);
            color: var(--popup_button_text_color_7) !important;
            text-align: center;
            border-radius: 5px;
            transition: all 0.5s;
            z-index: 1;
        }

        .helper-button::before {
            position: absolute;
            content: '';
            top: 0;
            right: 0;
            bottom: 0;
            left: 0;
            background-color: var(--popup_button_background_color_6);
            filter: brightness(100%);
            z-index: -1;
            border-radius: 5px;
            transition: all 0.5s linear;
            opacity: 0;
        }

        .helper-button:hover::before {
            filter: brightness(90%);
            opacity: 1;
            transition: all 0.5s linear;
        }

        @media only screen and (max-width: 767px) {
            /* phones */

            .svg-turkiye-haritasi {
                max-width: 100%;
                float: none;
                display: block;
            }

            .all-dealers {
                float: none;
                max-width: 100%;
                display: block;
            }

            .common-detail {
                float: none;
                width: 100%;
            }

            .common-photo {
                margin-right: 10px;
            }

            .common-detail p {
                font-size: 12px;
            }

            .dealer {
                width: 76.5%;
                top: 10%;
                left: 0;
            }

            .common-information {
                width: 100%;
                display: block;
            }
        }

        @media only screen and (max-width: 767px) and (orientation: portrait) {
            /* portrait phones */

            .svg-turkiye-haritasi {
                max-width: 100%;
                float: none;
                display: block;
            }

            .all-dealers {
                float: none;
                max-width: 100%;
                display: block;
            }

            .dealers-on-region {
                width: 100%;
                left: -5%;
            }

            .common-information h4 {
                font-size: 18px;
            }
        }
    </style>
    <script>
        jQuery(document).ready(function ($) {

            const turkiye = $("#turkiye g");
            const dealers = $(".dealers-on-region");
            const close = $(".close");
            const selectedAreaColor = "<?php echo $map_selected_area_color_3; ?>"

            dealers.hide();

            turkiye.click(function () {
                var alankodu = $(this).attr("data-alankodu");
                dealers.hide();

                $('.dealers-on-region[data-alankodu*="' + alankodu + '"]').show();
            });

            close.click(function () {
                dealers.hide();
            });

            dealers.each(function () {
                var alankodlari = JSON.parse($(this).attr("data-alankodu"));
                alankodlari.forEach(function (alankodu) {
                    var matchedG = $("g[data-alankodu='" + alankodu + "']");
                    if (matchedG.length) {
                        matchedG.find("path").css("fill", selectedAreaColor);
                    }
                });
            });
        });
    </script>
    <?php

    echo '</div>';

    wp_reset_postdata();

    $output = ob_get_contents();
    ob_end_clean();
    return $output;
}
add_shortcode('mn_map_popup', 'mn_map_popup_shortcode');

?>