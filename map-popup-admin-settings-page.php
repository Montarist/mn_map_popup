
<?php 

require_once(plugin_dir_path(__FILE__) . 'map-popus-metaboxes.php');


class MapPopup {
   private $map_popup_options;

   public function __construct() {
		$popup_metabox = new PopupDetailsMetabox();
		add_action( 'init', array( $this, 'map_popup_register_post_type' ) );
		add_action( 'admin_menu', array( $this, 'map_popup_add_plugin_page' ) );
		add_action( 'admin_init', array( $this, 'map_popup_page_init' ) );
	}

   public function map_popup_register_post_type() {
		$labels = array(
			'name' 					=> __( 'Popups' , 'map_popup' ), 
			'singular_name' 		=> __( 'Popups' , 'map_popup' ), 
			'add_new'				=> __( 'New Popup' , 'map_popup' ), 
			'add_new_item' 			=> __( 'Add New Popup' , 'map_popup' ), 
			'edit_item' 			=> __( 'Edit Popup' , 'map_popup' ), 
			'new_item' 				=> __( 'New Popup' , 'map_popup' ), 
			'view_item' 			=> __( 'View Popup' , 'map_popup' ), 
			'search_items' 			=> __( 'Search Popups' , 'map_popup' ), 
			'not_found' 			=> __( 'No Popups Found' , 'map_popup' ), 
			'not_found_in_trash' 	=> __( 'No Popups found in Trash' , 'map_popup' ) 
		);
		$args = array(
			'labels' 				=> $labels, 
			'has_archive' 			=> true, 
			'public' 				=> true,
			'show_ui' 				=> true,
			'hierarchical' 			=> false, 
			'supports' 				=> array( 
										'title',
										'editor',
										'excerpt',
										'custom-fields',
										'thumbnail',
										'page-attributes'
									),
			'rewrite'   			=> array( 'slug' => 'popups' ),
			'show_in_rest' 			=> true,
			'show_in_admin_bar' 	=> true,
			'show_in_menu' 			=> true,
			'show_in_nav_menu' 		=> true,
			'can_export' 			=> true,
			'capability_type' 		=> 'post',
			'description' 			=> true,
			'exclude_from_search' 	=> false,
			'menu_icon' 			=> 'dashicons-location-alt',
			'menu_position' 		=> 2,
			'public' 				=> true,
			'publicly_querable' 	=> true,
			'query_var' 			=> true,

		);
		register_post_type( "popups", $args );
		
	}

   public function map_popup_add_plugin_page() {
	   add_menu_page(
		   'MapPopup', // page_title
		   'MapPopup', // menu_title
		   'manage_options', // capability
		   'map_popup', // menu_slug
		   array( $this, 'map_popup_create_admin_page' ), // function
		   'dashicons-location', // icon_url
		   81 // position
	   );

		add_submenu_page(
			'map_popup', 
			'Settings', 
			'Settings', 
			'manage_options', 
			'map_popup_settings' ,
			array( $this, 'map_popup_settings' ), // function
		);
   }

   	public function map_popup_create_admin_page() {
	   $this->map_popup_options = get_option( 'map_popup_option_name' ); ?>

	   <div class="wrap">
		   <h2>MapPopup Plugin</h2>
		   <p>Welcome</p>
	   </div>
   <?php }

	public function map_popup_show_all_popups() {
		$this->map_popup_options = get_option( 'map_popup_option_name' ); ?>

		<div class="wrap">
			<h2>Show All Popups</h2>
		</div>
	<?php }

	public function map_popup_add_new_popup() {
		$this->map_popup_options = get_option( 'map_popup_option_name' ); ?>

		<div class="wrap">
			<h2>Add New Popup</h2>
			<p>Bu da text description text</p>
		</div>
	<?php }

	public function map_popup_settings() {
		$this->map_popup_options = get_option( 'map_popup_option_name' ); ?>

		<div class="wrap">
			<h2>MapPopup Plugin Settings</h2>
			<p>Bu da text description text</p>
			<?php settings_errors(); ?>

			<form method="post" action="options.php">
					<?php
					settings_fields( 'map_popup_option_group' );
					do_settings_sections( 'map-popup-admin' );
					submit_button();
				?>
			</form>
		</div>
	<?php }

   	public function map_popup_page_init() {
	   register_setting(
		   'map_popup_option_group', // option_group
		   'map_popup_option_name', // option_name
		   array( $this, 'map_popup_sanitize' ) // sanitize_callback
	   );

	   add_settings_section(
		   'map_popup_setting_section', // id
		   __( 'Settings' , 'map_popup' ), // title
		   array( $this, 'map_popup_section_info' ), // callback
		   'map-popup-admin' // page
	   );

	   add_settings_field(
		   'map_main_color', // id
		   __( 'Main Color' , 'map_popup' ), // title
		   array( $this, 'map_main_color_callback' ), // callback
		   'map-popup-admin', // page
		   'map_popup_setting_section' // section
	   );

	   add_settings_field(
		   'map_hover_color', // id
		   __( 'Hover Color' , 'map_popup' ), // title
		   array( $this, 'map_hover_color_callback' ), // callback
		   'map-popup-admin', // page
		   'map_popup_setting_section' // section
	   );

	   add_settings_field(
		   'map_which_area', // id
		   __( 'Select Map Area' , 'map_popup' ), // title
		   array( $this, 'map_which_area_callback' ), // callback
		   'map-popup-admin', // page
		   'map_popup_setting_section' // section
	   );

	   add_settings_field(
		   'map_selected_area', // id
		   __( 'Selected Area' , 'map_popup' ), // title
		   array( $this, 'map_selected_area_callback' ), // callback
		   'map-popup-admin', // page
		   'map_popup_setting_section' // section
	   );

	   add_settings_field(
		   'settings_4_3', // id
		   'Settings 4', // title
		   array( $this, 'settings_4_3_callback' ), // callback
		   'map-popup-admin', // page
		   'map_popup_setting_section' // section
	   );

	   add_settings_field(
		   'settings_5_4', // id
		   'Settings 5', // title
		   array( $this, 'settings_5_4_callback' ), // callback
		   'map-popup-admin', // page
		   'map_popup_setting_section' // section
	   );
   }

   public function map_popup_sanitize($input) {
	   $sanitary_values = array();
	   if ( isset( $input['map_main_color'] ) ) {
		   $sanitary_values['map_main_color'] = sanitize_text_field( $input['map_main_color'] );
	   }

	   if ( isset( $input['map_hover_color'] ) ) {
		   $sanitary_values['map_hover_color'] = sanitize_text_field( $input['map_hover_color'] );
	   }

	   if ( isset( $input['map_which_area'] ) ) {
		   	$sanitary_values['map_which_area'] = $input['map_which_area'];
			$selected_map = "";
			switch($sanitary_values['map_which_area']) {
				case 'Turkey':
					$selected_map = 'turkey';
					break;
				case 'World Wide':
					$selected_map = 'world_en';
					break;
				case 'Europe':
					$selected_map = 'europe_en';
					break;
				case 'Usa':
					$selected_map = 'usa_en';
					break;
				case 'Asia':
					$selected_map = 'asia';
					break;
				case 'Africa':
					$selected_map = 'africa';
					break;
				case 'Australia':
					$selected_map = 'australia';
					break;
				default:
					$selected_map = 'turkey';
					break;
			}
			$sanitary_values['map_selected_area'] = $selected_map;
	   }



	   if ( isset( $input['settings_4_3'] ) ) {
		   $sanitary_values['settings_4_3'] = $input['settings_4_3'];
	   }

	   if ( isset( $input['settings_5_4'] ) ) {
		   $sanitary_values['settings_5_4'] = $input['settings_5_4'];
	   }

	   return $sanitary_values;
   }

   public function map_popup_section_info() {
	   
   }

   public function map_main_color_callback() { ?>
	<input class="regular-text" type="color" name="map_popup_option_name[map_main_color]" id="map_main_color" 
		 value="<?php echo isset( $this->map_popup_options['map_main_color'] ) ? esc_attr( $this->map_popup_options['map_main_color']) : ''; ?>">
	<?php
	}

	public function map_hover_color_callback() { ?>
	<input class="regular-text" type="color" name="map_popup_option_name[map_hover_color]" id="map_hover_color" 
			value="<?php echo isset( $this->map_popup_options['map_hover_color'] ) ? esc_attr( $this->map_popup_options['map_hover_color']) : ''; ?>">
	<?php
	}

   public function map_which_area_callback() {
	   ?> <select name="map_popup_option_name[map_which_area]" id="map_which_area">
		   <?php $map_which_area = $this->map_popup_options['map_which_area']; ?>
		   <?php $selected = (isset( $map_which_area ) && $map_which_area === 'World Wide') ? 'selected' : '' ; ?>
		   	<option <?php echo $selected;?>><?php echo __( 'World Wide' , 'map_popup' ); ?></option>
		   <?php $selected = (isset( $map_which_area ) && $map_which_area === 'Turkey') ? 'selected' : '' ; ?>
		   <option <?php echo $selected; ?>><?php echo __( 'Turkey' , 'map_popup' ); ?></option>
		   <?php $selected = (isset( $map_which_area ) && $map_which_area === 'Algeria') ? 'selected' : '' ; ?>
		   <option <?php echo $selected; ?>><?php echo __( 'Algeria' , 'map_popup' ); ?></option>
		   <?php $selected = (isset( $map_which_area ) && $map_which_area === 'Argentina') ? 'selected' : '' ; ?>
		   <option <?php echo $selected; ?>><?php echo __( 'Argentina' , 'map_popup' ); ?></option>
		   <?php $selected = (isset( $map_which_area ) && $map_which_area === 'Brazil') ? 'selected' : '' ; ?>
		   <option <?php echo $selected; ?>><?php echo __( 'Brazil' , 'map_popup' ); ?></option>
		   <?php $selected = (isset( $map_which_area ) && $map_which_area === 'Canada') ? 'selected' : '' ; ?>
		   <option <?php echo $selected; ?>><?php echo __( 'Canada' , 'map_popup' ); ?></option>
		   <?php $selected = (isset( $map_which_area ) && $map_which_area === 'Croatia') ? 'selected' : '' ; ?>
		   <option <?php echo $selected; ?>><?php echo __( 'Croatia' , 'map_popup' ); ?></option>
		   <?php $selected = (isset( $map_which_area ) && $map_which_area === 'Europe') ? 'selected' : '' ; ?>
		   <option <?php echo $selected; ?>><?php echo __( 'Europe' , 'map_popup' ); ?></option>
	   </select> <?php
   }

   public function map_selected_area_callback() {
	?> 
		<input type="text" placeholder="<?php echo $this->map_popup_options['map_selected_area']; ?>" disabled>
	<?php
}

   public function settings_4_3_callback() {
	   printf(
		   '<input type="checkbox" name="map_popup_option_name[settings_4_3]" id="settings_4_3" value="settings_4_3" %s> <label for="settings_4_3">Descccc</label>',
		   ( isset( $this->map_popup_options['settings_4_3'] ) && $this->map_popup_options['settings_4_3'] === 'settings_4_3' ) ? 'checked' : ''
	   );
   }

   public function settings_5_4_callback() {
	   ?> <fieldset><?php $checked = ( isset( $this->map_popup_options['settings_5_4'] ) && $this->map_popup_options['settings_5_4'] === 'Opt 1' ) ? 'checked' : '' ; ?>
	   <label for="settings_5_4-0"><input type="radio" name="map_popup_option_name[settings_5_4]" id="settings_5_4-0" value="Opt 1" <?php echo $checked; ?>> Opt 1</label><br>
	   <?php $checked = ( isset( $this->map_popup_options['settings_5_4'] ) && $this->map_popup_options['settings_5_4'] === 'Opt 2' ) ? 'checked' : '' ; ?>
	   <label for="settings_5_4-1"><input type="radio" name="map_popup_option_name[settings_5_4]" id="settings_5_4-1" value="Opt 2" <?php echo $checked; ?>> Opt 2</label></fieldset> <?php
   }

}

/* 
* Retrieve this value with:
* $map_popup_options = get_option( 'map_popup_option_name' ); // Array of All Options
* $map_main_color = $map_popup_options['map_main_color']; // Settings 1
* $map_hover_color = $map_popup_options['map_hover_color']; // Settings 2
* $map_which_area = $map_popup_options['map_which_area']; // Settings 3
* $settings_4_3 = $map_popup_options['settings_4_3']; // Settings 4
* $settings_5_4 = $map_popup_options['settings_5_4']; // Settings 5
*/


// Meta Box Class: PopupDetailsMetaBox
// Get the field value: $metavalue = get_post_meta( $post_id, $field_id, true );

if ( is_admin() ) {
	$map_popup = new MapPopup();
}