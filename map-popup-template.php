<?php 

$map_popup_options = get_option( 'map_popup_option_name' ); // Array of All Options
$map_main_color = $map_popup_options['map_main_color']; // Settings 1
$map_hover_color = $map_popup_options['map_hover_color']; // Settings 2
$map_which_area = $map_popup_options['map_which_area']; // Settings 3
$selected_map = $map_popup_options['map_selected_area']; // Settings 3

echo $selected_map . "</br>";
echo $map_main_color . "</br>";
echo $map_hover_color . "</br>";
echo $map_which_area . "</br>";

print_r($map_popup_options);

// $args = array(
//     'post_type' => 'popups',
//     'posts_per_page' => -1
// );
// $query = new WP_Query($args);
// if ($query->have_posts() ) : 
// while ( $query->have_posts() ) : $query->the_post();
//     $mytext =   get_post_meta(get_the_ID(), 'sub_items', true);
//     print_r($mytext);
// endwhile;
// wp_reset_postdata();
// endif;

?>


<link href="<?php echo plugins_url( 'jqvmap/jqvmap.css' , __FILE__ ); ?>" media="screen" rel="stylesheet" type="text/css">

<script src="<?php echo plugins_url( 'jqvmap/jquery.vmap.js' , __FILE__ ); ?>"></script>
<script src="<?php echo plugins_url( 'jqvmap/maps/jquery.vmap.'. $selected_map .'.js' , __FILE__ ); ?>" charset="utf-8"></script>

<script src="<?php echo plugins_url( 'assets/jquery.simple-popup.min.js' , __FILE__ ); ?>"></script>
<link href="<?php echo plugins_url( 'assets/jquery.simple-popup.min.css' , __FILE__ ); ?>" rel="stylesheet" type="text/css" />


<a class="two">Click</a>

<div id="popup2" style="display: none;">
    <h1>This is it!!</h1>
    <ul>
        <li>Wow this looks like long content</li>
    </ul>
</div>

<script>
jQuery(document).ready(function() {
    jQuery(document).ready(function() {
        jQuery("a.two").on("click", function(e) {
            e.preventDefault();
            jQuery(this).simplePopup({ type: "html", htmlSelector: "#popup2" });
        });
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
        hoverOpacity: null,
        normalizeFunction: 'linear',
        scaleColors: ['#b6d6ff', '#005ace'],
        selectedColor: '#c9dfaf',
        selectedRegions: null,
        showTooltip: true,
        onRegionClick: function(element, code, region) {
            var message = 'You clicked "'
                + region
                + '" which has the code: '
                + code.toUpperCase();

            alert(message);
        }
    });
});
</script>

<?php require_once(plugin_dir_path(__FILE__) . 'option_list/options_'. $selected_map .'.php'); ?>

<div id="vmap" style="width: 100%; height: 600px"></div>