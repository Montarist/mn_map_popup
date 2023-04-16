<?php

// Utils
require_once plugin_dir_path(__FILE__) . 'utils.php';

// Create shortcode
function map_popup_shortcode($atts)
{
    $api_key = get_option('map_api_key');

    if (!map_popup_is_api_key_valid()) {
        echo '<p style="color: red;">' . __('Valid API key is required.', 'map-popup') . '</p>';
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
        return '';
    }

    $map_background_color = get_option('map_background_color');
    $map_hover_color = get_option('map_hover_color');
    $map_selected_area_color = get_option('map_selected_area_color');
    $popup_header_color = get_option('popup_header_color');
    $popup_text_color = get_option('popup_text_color');
    $popup_button_background_color = get_option('popup_button_background_color');
    $popup_button_text_color = get_option('popup_button_text_color');

    echo '<div class="map-popup-container">';

    // Turkey Map
    require_once plugin_dir_path(__FILE__) . 'view/turkey-map.php';

    // include plugin_dir_path(__FILE__) . 'view/turkey-map.php';

    echo '<div class="all-dealers">';

    while ($map_popup_posts->have_posts()) {
        $map_popup_posts->the_post();

        $representative_fields_data = get_post_meta(get_the_ID(), 'representative_fields_data', true);
        $dealer_fields_data = get_post_meta(get_the_ID(), 'dealer_fields_data', true);


        if (!$representative_fields_data) {
            continue;
        }

        if (!$dealer_fields_data) {
            continue;
        }

        $dealer_field_number = count($dealer_fields_data['dealer_name']);
        $representative_field_number = count($representative_fields_data['representative_name']);

        $selected_cities = get_post_meta(get_the_ID(), 'selected_cities', true);
        if ($selected_cities) {

            $cities = get_cities_array();
            $cities_alan_kodu = array();

            foreach ($cities as $city) {
                if (isset($selected_cities[$city['id']]) && $selected_cities[$city['id']] == '1') {
                    array_push($cities_alan_kodu, $city['alankodu']);
                    // echo '<li>' . $city['iladi'] . ' (Plaka Kodu: ' . $city['plakakodu'] . ', Alan Kodu: ' . $city['alankodu'] . ')</li>';
                }
            }
        }

        $cities_alan_kodu_json = json_encode($cities_alan_kodu);

        // for ($i = 0; $i < $dealer_field_number; $i++) {
        //     echo '<div class="dynamic_field">';
        //     echo '<p>' . esc_attr($dealer_fields_data['dealer_position'][$i]) . '</p>';
        //     echo '<p>' . esc_attr($dealer_fields_data['dealer_phone'][$i]) . '</p>';
        //     echo '<p>' . esc_attr($dealer_fields_data['dealer_email'][$i]) . '</p>';
        //     echo '<p>' . esc_attr($dealer_fields_data['dealer_address'][$i]) . '</p>';
        //     echo '<p>' . esc_attr($dealer_fields_data['dealer_fax'][$i]) . '</p>';
        //     echo '<p><img src="' . esc_attr($dealer_fields_data['dealer_photo'][$i]) . '" class="image-preview" style="max-width: 100px;"></p>';
        //     echo '<hr></div>';
        // }

        // echo '<div id="representative_fields_container">';
        // echo '<h1>Representative</h1>';

        // for ($i = 0; $i < $representative_field_number; $i++) {
        //     echo '<div class="dynamic_field">';
        //     echo '<p>' . esc_attr($representative_fields_data['representative_position'][$i]) . '</p>';
        //     echo '<p>' . esc_attr($representative_fields_data['representative_phone'][$i]) . '</p>';
        //     echo '<p>' . esc_attr($representative_fields_data['representative_email'][$i]) . '</p>';
        //     echo '<p>' . esc_attr($representative_fields_data['representative_address'][$i]) . '</p>';
        //     echo '<p>' . esc_attr($representative_fields_data['representative_fax'][$i]) . '</p>';
        //     echo '<p><img src="' . esc_attr($representative_fields_data['representative_photo'][$i]) . '" class="image-preview" style="max-width: 100px;"></p>';
        //     echo '<hr></div>';
        // }

        // echo '</div>';
        ?>
        <div class="dealers-on-region" data-alankodu='<?php echo $cities_alan_kodu_json; ?>'>
            <i class="fa-solid fa-map-location-dot"></i>
            <div class="close">
                <i class="fa-solid fa-circle-xmark"></i>
            </div>
            <?php if ($representative_field_number > 0) { ?>
                <div class="common-information">
                    <h4>
                        <?php echo __('Responsible Officers in the Region', 'map-popup'); ?>
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
                                                <?php echo __('Officer Email', 'map-popup'); ?>:
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
                                                <?php echo __('Officer Phone', 'map-popup'); ?>:
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
                        <?php echo __('Authorised Dealer in the Region', 'map-popup'); ?>
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
                                                <?php echo __('Dealer Email', 'map-popup'); ?>:
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
                                                <?php echo __('Dealer Website', 'map-popup'); ?>:
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
                                                <?php echo __('Dealer Phone', 'map-popup'); ?>:
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
                                                <?php echo __('Dealer Fax', 'map-popup'); ?>:
                                            </span>
                                            <?php echo esc_attr($dealer_fields_data['dealer_fax'][$i]); ?>
                                        </p>
                                    <?php } ?>

                                    <?php if ($dealer_fields_data['dealer_address'][$i]) { ?>
                                        <p>
                                            <i class="fa-solid fa-location-dot"></i>
                                            <span>
                                                <?php echo __('Dealer Address', 'map-popup'); ?>:
                                            </span>
                                            <?php echo esc_attr($dealer_fields_data['dealer_address'][$i]); ?>
                                        </p>
                                    <?php } ?>

                                    <?php if ($dealer_fields_data['dealer_phone'][$i]) { ?>
                                        <p class="helper-buttons">
                                            <a href="https://www.google.com/maps/dir/<?php echo esc_attr($dealer_fields_data['dealer_address'][$i]); ?>"
                                                target="_blank" class="helper-button"><?php echo __('Get Directions', 'map-popup'); ?></a>
                                            <a href="tel://<?php echo trim(esc_attr($dealer_fields_data['dealer_phone'][$i])); ?>"
                                                target="_blank" class="helper-button"><?php echo __('Call Dealer', 'map-popup'); ?></a>
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
            --map_background_color:
                <?php echo $map_background_color; ?>
            ;
            --map_hover_color:
                <?php echo $map_hover_color; ?>
            ;
            --map_selected_area_color:
                <?php echo $map_selected_area_color; ?>
            ;
            --popup_header_color:
                <?php echo $popup_header_color; ?>
            ;
            --popup_text_color:
                <?php echo $popup_text_color; ?>
            ;
            --popup_button_background_color:
                <?php echo $popup_button_background_color; ?>
            ;
            --popup_button_text_color:
                <?php echo $popup_button_text_color; ?>
            ;
        }

        #svg-turkiye-haritasi path {
            fill: var(--map_background_color);
        }

        #svg-turkiye-haritasi path:hover {
            fill: var(--map_hover_color);
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
            color: var(--popup_header_color);
            font-size: 24px;
            font-weight: bold;
        }

        .common-information h3 {
            color: var(--popup_text_color);
            font-size: 16px;
            font-weight: bold;
            line-height: 16px;
            margin: 0;
            margin-bottom: 7px;
        }

        .common-information p {
            margin: 0;
            color: var(--popup_text_color);
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
            color: var(--popup_text_color);
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
            color: var(--popup_text_color);
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
            background-color: var(--popup_button_background_color);
            color: var(--popup_button_text_color) !important;
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
            background-color: var(--popup_button_background_color);
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
        }
    </style>
    <script>
        jQuery(document).ready(function ($) {

            const turkiye = $("#turkiye g");
            const dealers = $(".dealers-on-region");
            const close = $(".close");
            const selectedAreaColor = "<?php echo $map_selected_area_color; ?>"

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
add_shortcode('map_popup_posts', 'map_popup_shortcode');

?>