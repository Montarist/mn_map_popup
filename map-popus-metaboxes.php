<?php 

class PopupDetailsMetaBox{

	private $screen = array(
		
                        
	);

	private $meta_fields = array(
                array(
                    'label'         => 'Authorized Person',
                    'id'            => 'authorized_person',
                    'type'          => 'text',
                ),
    
                array(
                    'label'         => 'Person Media',
                    'id'            => 'popup_person_media',
                    'type'          => 'media',
                    'returnvalue'   => 'url'
                ),
    
                array(
                    'label'         => 'Person Web Site',
                    'id'            => 'popup_person_web_site',
                    'type'          => 'url',
                ),
    
                array(
                    'label'         => 'Person E-mail Address',
                    'id'            => 'popup_person_email',
                    'type'          => 'email',
                ),
    
                array(
                    'label'         => 'Person Phone Number',
                    'id'            => 'popup_person_phone',
                    'type'          => 'tel',
                ),
    
                array(
                    'label'         => 'Person Color Picker',
                    'id'            => 'popup_person_color',
                    'type'          => 'color',
                )

	);

	public function __construct() {
		add_action( 'add_meta_boxes', array( $this, 'add_meta_boxes' ) );
        add_action( 'admin_footer', array( $this, 'media_fields' ) );
		add_action( 'save_post', array( $this, 'save_fields' ) );
	}

    /* Prints the box content */
    public function dynamic_inner_custom_box($post) {
        $sub_items = get_post_meta($post->ID, 'sub_items', true);
        $item_count = 0;
        if ( count( $sub_items ) > 0 ) {
    ?>
            <div id="dynamic_inner_metabox">
                <div class="sub-items">
                    <?php
                        foreach( $sub_items as $sub_item ) { ?>
                            <hr>
                            <div class="sub-item">
                                <div class="head-of-subitem">
                                    <h2 id="sub_items[<?php echo $item_count; ?>]"><?php echo $item_count + 1; ?>. <?php echo __( 'Item', 'map_popup' ); ?></h2>
                                    <span class="remove"><?php echo __( 'Remove Item', 'map_popup' ); ?></span>
                                </div>
                                <table class="form-table lists-popup" id="sub_items[<?php echo $item_count; ?>][table]">
                                    <tbody>
                                        <tr>
                                            <th><label for="sub_items[<?php echo $item_count; ?>][name]"><?php echo __( 'Item Name', 'map_popup' ); ?></label></th>
                                            <td><input id="sub_items[<?php echo $item_count; ?>][name]" name="sub_items[<?php echo $item_count; ?>][name]" type="text" value="<?php echo $sub_item["name"]; ?>"></td>
                                        </tr>
                                        <tr>
                                            <th><label for="sub_items[<?php echo $item_count; ?>][phone_number]"><?php echo __( 'Item Phone Number', 'map_popup' ); ?></label></th>
                                            <td><input id="sub_items[<?php echo $item_count; ?>][phone_number]" name="sub_items[<?php echo $item_count; ?>][phone_number]" type="tel" value="<?php echo $sub_item["phone_number"]; ?>"></td>
                                        </tr>
                                        <tr>
                                            <th><label for="sub_items[<?php echo $item_count; ?>][second_phone_number]"><?php echo __( 'Item 2. Phone Number', 'map_popup' ); ?></label></th>
                                            <td><input id="sub_items[<?php echo $item_count; ?>][second_phone_number]" name="sub_items[<?php echo $item_count; ?>][second_phone_number]" type="tel" value="<?php echo $sub_item["second_phone_number"]; ?>"></td>
                                        </tr>
                                        <tr>
                                            <th><label for="sub_items[<?php echo $item_count; ?>][email]"><?php echo __( 'Item E-mail Address', 'map_popup' ); ?></label></th>
                                            <td><input id="sub_items[<?php echo $item_count; ?>][email]" name="sub_items[<?php echo $item_count; ?>][email]" type="email" value="<?php echo $sub_item["email"]; ?>"></td>
                                        </tr>
                                        <tr>
                                            <th><label for="sub_items[<?php echo $item_count; ?>][address]"><?php echo __( 'Item Address', 'map_popup' ); ?></label></th>
                                            <td><input id="sub_items[<?php echo $item_count; ?>][address]" name="sub_items[<?php echo $item_count; ?>][address]" type="text" value="<?php echo $sub_item["address"]; ?>"></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <?php
                                $item_count++;
                        }
                    }
                    ?>
            </div>
            <span class="add_new_item"><?php echo __('Add New Item', 'map_popup'); ?></span>
            <script>
                jQuery(document).ready(function() {
                    var count = <?php echo $item_count; ?>;
                    jQuery(".head-of-subitem h2").click(function() {
                        var id = jQuery(this).attr('id');
                        jQuery( '.lists-popup + #' + id + '[table]').show();
                        return false;
                    });
                    jQuery(".add_new_item").click(function() {
                        jQuery('.sub-items').append(`
                         <hr>
                            <div class="sub-item">
                                <div class="head-of-subitem">
                                    <h2 id="sub_items[`+ count +`]">`+ count +`. <?php echo __( 'Item', 'map_popup' ); ?></h2>
                                    <span class="remove"><?php echo __( 'Remove Item', 'map_popup' ); ?></span>
                                </div>
                                <table class="form-table lists-popup" id="sub_items[`+ count +`][table]">
                                    <tbody>
                                        <tr>
                                            <th><label for="sub_items[`+ count +`][name]"><?php echo __( 'Item Name', 'map_popup' ); ?></label></th>
                                            <td><input id="sub_items[`+ count +`][name]" name="sub_items[`+ count +`][name]" type="text" value="<?php echo $sub_item["name"]; ?>"></td>
                                        </tr>
                                        <tr>
                                            <th><label for="sub_items[`+ count +`][phone_number]"><?php echo __( 'Item Phone Number', 'map_popup' ); ?></label></th>
                                            <td><input id="sub_items[`+ count +`][phone_number]" name="sub_items[`+ count +`][phone_number]" type="tel" value="<?php echo $sub_item["phone_number"]; ?>"></td>
                                        </tr>
                                        <tr>
                                            <th><label for="sub_items[`+ count +`][second_phone_number]"><?php echo __( 'Item 2. Phone Number', 'map_popup' ); ?></label></th>
                                            <td><input id="sub_items[`+ count +`][second_phone_number]" name="sub_items[`+ count +`][second_phone_number]" type="tel" value="<?php echo $sub_item["second_phone_number"]; ?>"></td>
                                        </tr>
                                        <tr>
                                            <th><label for="sub_items[`+ count +`][email]"><?php echo __( 'Item E-mail Address', 'map_popup' ); ?></label></th>
                                            <td><input id="sub_items[`+ count +`][email]" name="sub_items[`+ count +`][email]" type="email" value="<?php echo $sub_item["email"]; ?>"></td>
                                        </tr>
                                        <tr>
                                            <th><label for="sub_items[`+ count +`][address]"><?php echo __( 'Item Address', 'map_popup' ); ?></label></th>
                                            <td><input id="sub_items[`+ count +`][address]" name="sub_items[`+ count +`][address]" type="text" value="<?php echo $sub_item["address"]; ?>"></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        `);
                        count = count + 1;
                        return false;
                    });
                    jQuery(".remove").on('click', function() {
                        jQuery(this).parent().parent().remove();
                    });
                });
                </script>
                <style>
                    
                    .sub-item {
                        display: block;
                        margin-bottom: 10px;
                    }
                    .head-of-subitem {
                        display: flex;
                        justify-content: space-between;
                        align-items: center;
                    }
                    .head-of-subitem h2 {
                        font-weight: bold !important;
                        color: #333;
                        cursor: pointer;
                        margin: 0;
                        padding: 5px 0 !important;
                    }
                    .head-of-subitem .remove {
                        color: #a00;
                        cursor: pointer;
                    }
                    .add_new_item {
                        color: #333;
                        cursor: pointer;
                        margin: 10px 0;
                        padding: 5px 0 !important;
                        border: 1px solid #333;
                        border-radius: 5px;
                    }
                </style>
            </div>
    <?php }

	public function add_meta_boxes($post_type) {
		$post_types = array( 'popups', 'post' ); 
 
        if ( in_array( $post_type, $post_types ) ) {
            add_meta_box(
				'Popup Metabox',
				'Popup Metabox',
				array( $this, 'meta_box_callback' ),
                $post_type,
                'advanced',
                'high'
            );
        }
	}

	public function meta_box_callback( $post ) {
		wp_nonce_field( 'popup_details_data', 'popup_details_nonce' );
                echo 'Popup Details Description';
		$this->field_generator( $post );
        $this->dynamic_inner_custom_box( $post );
	}
        public function media_fields() {
            ?><script>
                jQuery(document).ready(function($){
                    if ( typeof wp.media !== 'undefined' ) {
                        var _custom_media = true,
                        _orig_send_attachment = wp.media.editor.send.attachment;
                        jQuery('.new-media').click(function(e) {
                            var send_attachment_bkp = wp.media.editor.send.attachment;
                            var button = jQuery(this);
                            var id = button.attr('id').replace('_button', '');
                            _custom_media = true;
                                wp.media.editor.send.attachment = function(props, attachment){
                                if ( _custom_media ) {
                                    if (jQuery('input#' + id).data('return') == 'url') {
                                        jQuery('input#' + id).val(attachment.url);
                                    } else {
                                        jQuery('input#' + id).val(attachment.id);
                                    }
                                    jQuery('div#preview'+id).css('background-image', 'url('+attachment.url+')');
                                } else {
                                    return _orig_send_attachment.apply( this, [props, attachment] );
                                };
                            }
                            wp.media.editor.open(button);
                            return false;
                        });
                        jQuery('.add_media').on('click', function(){
                            _custom_media = false;
                        });
                        jQuery('.remove-media').on('click', function(){
                            var parent = jQuery(this).parents('td');
                            parent.find('input[type="text"]').val('');
                            parent.find('div').css('background-image', 'url()');
                        });
                    }
                });
            </script><?php
        }

	public function field_generator( $post ) {
		$output = '';
		foreach ( $this->meta_fields as $meta_field ) {
			$label = '<label for="' . $meta_field['id'] . '">' . $meta_field['label'] . '</label>';
			$meta_value = get_post_meta( $post->ID, $meta_field['id'], true );
			if ( empty( $meta_value ) ) {
				if ( isset( $meta_field['default'] ) ) {
					$meta_value = $meta_field['default'];
				}
			}
			switch ( $meta_field['type'] ) {
                                case 'media':
                                    $meta_url = '';
                                        if ($meta_value) {
                                            if ($meta_field['returnvalue'] == 'url') {
                                                $meta_url = $meta_value;
                                            } else {
                                                $meta_url = wp_get_attachment_url($meta_value);
                                            }
                                        }
                                    $input = sprintf(
                                        '<input style="display:none;" id="%s" name="%s" type="text" value="%s"  data-return="%s"><div id="preview%s" style="margin-right:10px;border:1px solid #e2e4e7;background-color:#fafafa;display:inline-block;width: 100px;height:100px;background-image:url(%s);background-size:cover;background-repeat:no-repeat;background-position:center;"></div><input style="width: 19%%;margin-right:5px;" class="button new-media" id="%s_button" name="%s_button" type="button" value="Select" /><input style="width: 19%%;" class="button remove-media" id="%s_buttonremove" name="%s_buttonremove" type="button" value="Clear" />',
                                        $meta_field['id'],
                                        $meta_field['id'],
                                        $meta_value,
                                        $meta_field['returnvalue'],
                                        $meta_field['id'],
                                        $meta_url,
                                        $meta_field['id'],
                                        $meta_field['id'],
                                        $meta_field['id'],
                                        $meta_field['id']
                                    );
                                    break;


				default:
                                    $input = sprintf(
                                        '<input %s id="%s" name="%s" type="%s" value="%s">',
                                        $meta_field['type'] !== 'color' ? 'style="width: 100%"' : '',
                                        $meta_field['id'],
                                        $meta_field['id'],
                                        $meta_field['type'],
                                        $meta_value
                                    );
			}
			$output .= $this->format_rows( $label, $input );
		}
		echo '<table class="form-table"><tbody>' . $output . '</tbody></table>';
	}

	public function format_rows( $label, $input ) {
		return '<tr><th>'.$label.'</th><td>'.$input.'</td></tr>';
	}

	public function save_fields( $post_id ) {
		if ( ! isset( $_POST['popup_details_nonce'] ) )
			return $post_id;
		$nonce = $_POST['popup_details_nonce'];
		if ( !wp_verify_nonce( $nonce, 'popup_details_data' ) )
			return $post_id;
		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE )
			return $post_id;
		foreach ( $this->meta_fields as $meta_field ) {
			if ( isset( $_POST[ $meta_field['id'] ] ) ) {
				switch ( $meta_field['type'] ) {
					case 'email':
						$_POST[ $meta_field['id'] ] = sanitize_email( $_POST[ $meta_field['id'] ] );
						break;
					case 'text':
						$_POST[ $meta_field['id'] ] = sanitize_text_field( $_POST[ $meta_field['id'] ] );
						break;
				}
				update_post_meta( $post_id, $meta_field['id'], $_POST[ $meta_field['id'] ] );
			} else if ( $meta_field['type'] === 'checkbox' ) {
				update_post_meta( $post_id, $meta_field['id'], '0' );
			}
		}

        $sub_items = $_POST['sub_items'];
        update_post_meta( $post_id, 'sub_items', $sub_items );
	}
}
?>