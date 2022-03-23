
<?php 

$map_popup_options = get_option( 'map_popup_option_name' ); // Array of All Options
$map_main_color = $map_popup_options['map_main_color']; // Settings 1
$map_hover_color = $map_popup_options['map_hover_color']; // Settings 2
$map_which_area = $map_popup_options['map_which_area']; // Settings 3
$selected_map = $map_popup_options['map_selected_area']; // Settings 3

?>
<link href="<?php echo plugins_url( 'jqvmap/jqvmap.min.css' , __FILE__ ); ?>" media="screen" rel="stylesheet" type="text/css">

<script src="<?php echo plugins_url( 'jqvmap/jquery.vmap.min.js' , __FILE__ ); ?>"></script>
<script src="<?php echo plugins_url( 'jqvmap/maps/jquery.vmap.'. $selected_map .'.js' , __FILE__ ); ?>" charset="utf-8"></script>

<script src="<?php echo plugins_url( 'assets/jquery.simple-popup.min.js' , __FILE__ ); ?>"></script>
<link href="<?php echo plugins_url( 'assets/jquery.simple-popup.min.css' , __FILE__ ); ?>" rel="stylesheet" type="text/css" />

<?php 
$args = array(
    'post_type' => 'popups',
    'posts_per_page' => -1
);
$query = new WP_Query($args);
if ($query->have_posts() ) : ?>

<script>
jQuery(document).ready(function() {
    function pad(num, len) { 
        return ("00000000" + num).substr(-len) 
    };

    jQuery("#contact-list-select").css({
        "width" : "50%",
        "height": "250px"
    });
    jQuery('#vmap').vectorMap({
        map: '<?php echo $selected_map; ?>',
        backgroundColor: '#fff',
        borderColor: '#818181',
        borderOpacity: 0.25,
        borderWidth: 1,
        color: '<?php echo $map_main_color; ?>',
        enableZoom: true,
        hoverColor: '<?php echo $map_hover_color; ?>',
        selectedColor: '#c9dfaf',
        showTooltip: true,
        onRegionClick: function(element, code, region) {
            // var message = 'You clicked "'
            //     + region
            //     + '" which has the code: '
            //     + code.toUpperCase();
            jQuery(this).simplePopup({ type: "html", htmlSelector: ".popup" + code.toUpperCase() });
        }
    });

});
</script>

<div id="vmap" style="width: 100%; height: 700px"></div>

<?php 
while ( $query->have_posts() ) : $query->the_post(); 
$sub_items =   get_post_meta(get_the_ID(), 'sub_items', true);
$metaboxSelect =   get_post_meta(get_the_ID(), 'metaboxSelect', true);
$authorized_person =   get_post_meta(get_the_ID(), 'authorized_person', true);
$popup_person_media =   get_post_meta(get_the_ID(), 'popup_person_media', true);
$popup_person_web_site =   get_post_meta(get_the_ID(), 'popup_person_web_site', true);
$popup_person_email =   get_post_meta(get_the_ID(), 'popup_person_email', true);
$popup_person_phone =   get_post_meta(get_the_ID(), 'popup_person_phone', true);
$popup_person_color =   get_post_meta(get_the_ID(), 'popup_person_color', true);
?>
<?php if($metaboxSelect) : ?>
    <?php 
        foreach($metaboxSelect as $metaboxSelectOption) : 
            $metaboxSelectOption = sprintf("%02d", $metaboxSelectOption);
    ?>
    <div class="popup<?php echo $metaboxSelectOption; ?>" style="display: none;">
        <div class="popup-items">
            <div class="main-person">
                <?php if($authorized_person) : ?>
                    <h2><?php echo $authorized_person; ?></h2>
                    <?php if($popup_person_media) : ?>
                        <img src="<?php echo $popup_person_media; ?>" alt="<?php echo $authorized_person; ?>">
                    <?php endif; ?>
                <?php endif; ?>
                <?php if($popup_person_web_site) : ?>
                    <p><a href="<?php echo $popup_person_web_site; ?>"><?php echo $popup_person_web_site; ?></a></p>
                <?php endif; ?>
                <?php if($popup_person_email) : ?>
                    <p><a href="mailto:<?php echo $popup_person_email; ?>"><?php echo $popup_person_email; ?></a></p>
                <?php endif; ?>
                <?php if($popup_person_phone) : ?>
                    <p><a href="tel:<?php echo $popup_person_phone; ?>"><?php echo $popup_person_phone; ?></a></p>
                <?php endif; ?>
            </div>
            <hr>
        <?php foreach($sub_items as $sub_item) : ?>
            <div class="popup-item">
                <?php if($sub_item["name"]) :  ?>
                    <h5><?php echo $sub_item["name"]; ?></h5>
                <?php endif; ?>
                <?php if($sub_item["email"]) : ?>
                    <p><a href="mailto:<?php echo $sub_item["email"]; ?>"><?php echo $sub_item["email"]; ?></a></p>
                <?php endif; ?>
                <?php if($sub_item["phone_number"]) : ?>
                    <p><a href="tel:<?php echo $sub_item["phone_number"]; ?>"><?php echo $sub_item["phone_number"]; ?></a></p>
                <?php endif; ?>
                <?php if($sub_item["second_phone_number"]) : ?>
                    <p><a href="tel:<?php echo $sub_item["second_phone_number"]; ?>"><?php echo $sub_item["second_phone_number"]; ?></a></p>
                <?php endif; ?>
                <?php if($sub_item["address"]) :  ?>
                    <p><?php echo $sub_item["address"]; ?></p>
                <?php endif; ?>
            </div>
        <?php endforeach; ?>
        </div>
    </div>

    <script>
        jQuery(document).ready(function() {
            jQuery("path#jqvmap1_" + "<?php echo $metaboxSelectOption; ?>").css({
                "fill" : "<?php  echo $map_hover_color; ?>"
            });
        });
    </script>
    <?php endforeach; ?>
<?php endif; ?>
<?php
    endwhile;
    wp_reset_postdata();
    endif;
?>