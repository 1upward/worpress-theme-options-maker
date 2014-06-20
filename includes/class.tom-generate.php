<?php

class tomGenerate {

	static function tom_tabs() {
		$counter = 0;
		$options = tomOptions::tom_options_fields();
		$menu = '';

		// echo "<pre>";
		// print_r($options); exit();
		// echo "</pre>";

		foreach ( $options as $obj_key =>$key ) {
			// Heading for Navigation
			if ( $key['type'] == "heading" ) {
				$counter++;
				$class = '';
				$class = ! empty( $obj_key ) ? $obj_key : $key['name'];
				$class = preg_replace( '/[^a-zA-Z0-9._\-]/', '', strtolower($class) ) . '-tab';
				$menu .= '<a id="options-group-'.  $counter . '-tab" class="nav-tab ' . $class .'" title="' . esc_attr( $key['name'] ) . '" href="' . esc_attr( '#options-group-'.  $counter ) . '">' . esc_html( $key['name'] ) . '</a>';
			}
		}

		return $menu;
	}

	static function create_tom_tabs() {
		$counter = 0;
		$options = tomOptions::tom_options_fields();
		$menu = '';

		// echo "<pre>";
		// print_r($options); exit();
		// echo "</pre>";
		
		foreach ( $options as $obj_key =>$key ) {
			// Heading for Navigation
			if ( $key['type'] == "heading" ) {
				$counter++;
				$class = '';
				$class = ! empty( $obj_key ) ? $obj_key : $key['name'];
				$class = preg_replace( '/[^a-zA-Z0-9._\-]/', '', strtolower($class) ) . '-tab';
				$menu .= '<a id="options-group-'.  $counter . '-tab" class="nav-tab ' . $class .'" title="' . esc_attr( $key['name'] ) . '" href="' . esc_attr( '#options-group-'.  $counter ) . '">' . esc_html( $key['name'] ) . '</a>';
			}
		}

		$menu .= '<a id="new-group-tab" class="nav-tab" title="Create new group" href="#new-group"><i class="dashicons dashicons-plus-alt"></i></a>';

		return $menu;
	}

	
	/**
	 * Generates the options fields that are used in the form.
	 */
	static function generate_options_fields() {

		global $allowedtags;

		$option_name = 'tom_data';

		$settings = get_option($option_name);

		// echo "<pre>";
		// print_r($settings); exit();
		// echo "</pre>";
		$options = tomOptions::tom_options_fields();

		// if (!empty($_GET['group'])) {
		// 	$options = filter_by_value($options_all, 'group', $_GET['group']); 
		// } else {
		// 	$options = filter_by_value($options_all, 'group', '1');
		// }
		// echo "<pre>";
		// print_r($settings);
		// echo "</pre>";
		$counter = 0;
		$menu = '';
		// $lastElement = end($options);
		// echo "<pre>";
		// print_r($lastElement);
		// echo "</pre>";
		// exit();
// // 
		if(!empty($options)) {
			foreach ( $options as $obj_key =>$key ) {
				$name = ! empty( $key['name'] ) ? $key['name'] : '';
				$desc = ! empty( $key['desc'] ) ? $key['desc'] : '';
				$type = ! empty( $key['type'] ) ? $key['type'] : '';
				$options = ! empty( $key['options'] ) ? $key['options'] : array();
				/* Default value from file */
				$val = ! empty( $key['default'] ) ? $key['default'] : '';

				/* change $val default with default value from db if exist */
				if ( $key['type'] != 'heading' ) {
					if ( isset( $settings[($obj_key)]) ) {
						$val = $settings[($obj_key)];
						// Striping slashes of non-array options
						if ( !is_array($val) ) {
							$val = stripslashes( $val );
						}
					}
				}

				$shortcode = '[tom id="'.$obj_key.'"]';

				$output = '';
				// Wrap all options
				// if ( $key['type'] != "heading" ) {

				// 	// Keep all ids lowercase with no spaces
				// 	$obj_key = preg_replace('/[^a-zA-Z0-9._\-]/', '', strtolower($obj_key) );

				// 	$id = 'section-' . $obj_key;

				// 	$class = 'section';
				// 	if ( isset( $key['type'] ) ) {
				// 		$class .= ' section-' . $key['type'];
				// 	}
				// 	if ( isset( $key['class'] ) ) {
				// 		$class .= ' ' . $key['class'];
				// 	}

				// 	$output .= '<div id="' . esc_attr( $id ) .'" class="' . esc_attr( $class ) . '">'."\n";
				// 	if ( isset( $key['name'] ) ) {
				// 		$output .= '<h4 class="heading">' . esc_html( $key['name'] ) . '</h4>' . "\n";
				// 	}
				// 	if ( $key['type'] != 'editor' ) {
				// 		$output .= '<div class="option">' . "\n" . '<div class="controls">' . "\n";
				// 	}
				// 	else {
				// 		$output .= '<div class="option">' . "\n" . '<div>' . "\n";
				// 	}
				// }

				// Set default value to $val
				// if ( isset( $key['default'] ) ) {
				// 	$val = $key['default'];
				// }

				// If the option is already saved, override $val
				

				// If there is a description save it for labels
				$explain_value = '';
				if ( isset( $key['desc'] ) ) {
					$explain_value = $key['desc'];
				}

				// if ( has_filter( 'tonjoo-tom_' . $key['type'] ) ) {
				// 	$output .= apply_filters( 'tonjoo-tom_' . $key['type'], $option_name, $key, $val );
				// }


				switch ( $key['type'] ) {

				// Bikin div container
				case "heading":
					$counter++;
					if ( $counter >= 2 ) {
						$output .= '</tbody>'."\n";
						$output .= '</table>'."\n";
						$output .= '</div>'."\n";
						$output .= '</div>'."\n";
					}
					$class = '';
					$class = ! empty( $obj_key ) ? $obj_key : $key['name'];
					$class = preg_replace('/[^a-zA-Z0-9._\-]/', '', strtolower($class) );
					$output .= '<div id="options-group-' . $counter . '" class="group ' . $class . '">';
					$output .= '<h3>' . esc_html( $key['name'] ) . '</h3>' . "\n";
					$output .= '<div class="container-table">' . "\n";
					$output .= '<table class="tom-options widefat">' . "\n";
					$output .= '<tbody class="container-body">' . "\n";
					break;

				case 'textarea':
					$output .= '<tr class="alternate">' . "\n";
					$output .= '<th scope="row"><label for="' . esc_attr( $obj_key ) . '">' . esc_attr( $name ) . '</label><br><span class="description">' . esc_attr( $desc ) . '</span></th>' . "\n";
					$output .= '<td><textarea class="tom-input" id="' . esc_attr( $obj_key ) . '" name="' . esc_attr( $option_name . '[' . $obj_key . ']' ) . '" placeholder="' . esc_attr( $val ) . '" rows="4" cols="50">' . esc_attr( $val ) . '</textarea></td>' . "\n";
					$output .= '<td class="shortcode">
									<span><a class="button-copy-shortcode" title="Copy Shortcode" href="#"><i class="dashicons dashicons-nametag"></i><span class="shortcodeValue" data-title="'. esc_attr( $name ) .'" style="display:none;">'.$shortcode.'</span></a></span>
								</td>' . "\n";
					$output .= '</tr>' . "\n";
					break;

				case 'select':
					$output .= '<tr class="alternate">' . "\n";
					$output .= '<th scope="row"><label for="' . esc_attr( $obj_key ) . '">' . esc_attr( $name ) . '</label><br><span class="description">' . esc_attr( $desc ) . '</span></th>' . "\n";
					$output .= '<td><select class="tom-input" name="' . esc_attr( $option_name . '[' . $obj_key . ']' ) . '" id="' . esc_attr( $obj_key ) . '">' . "\n";
								foreach ($options as $key => $option ) {
									/* function selected dr wp @http://codex.wordpress.org/Function_Reference/selected */
									$output .= '<option'. selected( $val, $key, false ) .' value="' . esc_attr( $key ) . '">' . esc_html( $option ) . '</option>';
								}	
					$output .= '</td>' . "\n";
					$output .= '<td class="shortcode">
									<span><a class="button-copy-shortcode" title="Copy Shortcode" href="#"><i class="dashicons dashicons-nametag"></i><span class="shortcodeValue" data-title="'. esc_attr( $name ) .'" style="display:none;">'.$shortcode.'</span></a></span>
								</td>' . "\n";
					$output .= '</tr>' . "\n";
					break;

				case "radio":
					$output .= '<tr class="alternate">' . "\n";
					$output .= '<th scope="row"><label for="' . esc_attr( $obj_key ) . '">' . esc_attr( $name ) . '</label><br><span class="description">' . esc_attr( $desc ) . '</span></th>' . "\n";
					$output .= '<td>' . "\n";
								foreach ($options as $key => $option ) {
									/* function selected dr wp @http://codex.wordpress.org/Function_Reference/checked */
									$output .= '<input type="' . esc_attr( $type ) . '" class="tom-input" name="' . esc_attr( $option_name . '[' . $obj_key . ']' ) . '" value="'. esc_attr( $key ) . '" '. checked( $val, $key, false) .'>' . esc_attr( $option ) . "\n";
								}	
					$output .= '</td>' . "\n";
					$output .= '<td class="shortcode">
									<span><a class="button-copy-shortcode" title="Copy Shortcode" href="#"><i class="dashicons dashicons-nametag"></i><span class="shortcodeValue" data-title="'. esc_attr( $name ) .'" style="display:none;">'.$shortcode.'</span></a></span>
								</td>' . "\n";
					$output .= '</tr>' . "\n";
					break;

				case "checkbox":
					$output .= '<tr class="alternate">' . "\n";
					$output .= '<th scope="row"><label for="' . esc_attr( $obj_key ) . '">' . esc_attr( $name ) . '</label><br><span class="description">' . esc_attr( $desc ) . '</span></th>' . "\n";
					$output .= '<td><input id="' . esc_attr( $obj_key ) . '" class="tom-input" type="' . esc_attr( $type ) . '" name="' . esc_attr( $option_name . '[' . $obj_key . ']' ) . '" value="1" '. checked( $val, '1', false) .' /></td>' . "\n";
					$output .= '<td class="shortcode">
									<span><a class="button-copy-shortcode" title="Copy Shortcode" href="#"><i class="dashicons dashicons-nametag"></i><span class="shortcodeValue" data-title="'. esc_attr( $name ) .'" style="display:none;">'.$shortcode.'</span></a></span>
								</td>' . "\n";
					$output .= '</tr>' . "\n";
					break;

				case "upload":
					$image 	 = wp_get_attachment_image_src( $val, 'medium' ); 
					$src 	 = (empty($val)) ? '' : $image[0];
					$display = (empty($val)) ? 'style="display:none;"' : '';
					$output .= '<tr class="alternate">' . "\n";
					$output .= '<th scope="row"><label for="' . esc_attr( $obj_key ) . '">' . esc_attr( $name ) . '</label><br><span class="description">' . esc_attr( $desc ) . '</span></th>' . "\n";
					$output .= '<td>
								<div id="' . esc_attr( $obj_key ) . '" class="tom_media_upload">
									<img class="tom_media_image tom-option-image" src="'.$src.'" '. $display .'/>
									<div>
										<input class="tom_media_url" type="hidden" value="">
										<input class="tom_media_id" type="hidden" name="' . esc_attr( $option_name . '[' . $obj_key . ']' ) . '" value="' . esc_attr( $val ) . '">
										<a href="#" class="tom_button_upload button-secondary">Choose</a>
										<a href="#" class="tom_remove_image button-primary" ' . $display . '>Remove</a>
									</div>
								</div>';
								
					$output .= '</td>' . "\n";
					$output .= '<td class="shortcode">
									<span><a class="button-copy-shortcode" title="Copy Shortcode" href="#"><i class="dashicons dashicons-nametag"></i><span class="shortcodeValue" data-title="'. esc_attr( $name ) .'" style="display:none;">'.$shortcode.'</span></a></span>
								</td>' . "\n";
					$output .= '</tr>' . "\n";

					break;

				case "select-image":
					$output .= '<tr class="alternate">' . "\n";
					$output .= '<th scope="row"><label for="' . esc_attr( $obj_key ) . '">' . esc_attr( $name ) . '</label><br><span class="description">' . esc_attr( $desc ) . '</span></th>' . "\n";
					$output .= '<td><div class="controls">' . "\n";
								foreach ( $options as $key => $option ) {
									$selected = '';
									if ( $val != '' && ($val == $key) ) {
										$selected = ' tom-radio-img-selected';
									}
									$output .= '<input type="radio" id="' . esc_attr( $obj_key .'_'. $key) . '" class="tom-radio-img-radio" value="' . esc_attr( $key ) . '" name="' . esc_attr( $option_name . '[' . $obj_key . ']' ) . '" '. checked( $val, $key, false ) .' />';
									$output .= '<div class="tom-radio-img-label">' . esc_html( $key ) . '</div>';
									$output .= '<img src="' . esc_url( $option ) . '" alt="' . $option .'" class="tom-radio-img-img' . $selected .'" onclick="document.getElementById(\''. esc_attr($obj_key .'_'. $key) .'\').checked=true;" />';
								}
					$output .= '</div></td>' . "\n";
					$output .= '<td class="shortcode">
									<span><a class="button-copy-shortcode" title="Copy Shortcode" href="#"><i class="dashicons dashicons-nametag"></i><span class="shortcodeValue" data-title="'. esc_attr( $name ) .'" style="display:none;">'.$shortcode.'</span></a></span>
								</td>' . "\n";
					$output .= '</tr>' . "\n";
					break;

				case "multicheck":
					$output .= '<tr class="alternate">' . "\n";
					$output .= '<th scope="row"><label for="' . esc_attr( $obj_key ) . '">' . esc_attr( $name ) . '</label><br><span class="description">' . esc_attr( $desc ) . '</span></th>' . "\n";
					$output .= '<td>' . "\n";
								foreach ($options as $key => $option) {
									$checked = '';
									$label = $option;
									$option = preg_replace('/[^a-zA-Z0-9._\-]/', '', strtolower($key));

									$id = $option_name . '-' . $obj_key . '-'. $option;
									$name = $option_name . '[' . $obj_key . '][' . $option .']';

									if ( isset($val[$option]) ) {
										$checked = checked($val[$option], 1, false);
									}

									$output .= '<input id="' . esc_attr( $id ) . '" class="tom-input" type="checkbox" name="' . esc_attr( $name ) . '" value="1" ' . $checked . ' />' . esc_html( $label ) . '<br>' . "\n";
								}
					$output .= '</td>' . "\n";
					$output .= '<td class="shortcode">
									<span><a class="button-copy-shortcode" title="Copy Shortcode" href="#"><i class="dashicons dashicons-nametag"></i><span class="shortcodeValue" data-title="'. esc_attr( $name ) .'" style="display:none;">'.$shortcode.'</span></a></span>
								</td>' . "\n";
					$output .= '</tr>' . "\n";
					break;

				case "color":
					$default_color = '';
					$output .= '<tr class="alternate">' . "\n";
					$output .= '<th scope="row"><label for="' . esc_attr( $obj_key ) . '">' . esc_attr( $name ) . '</label><br><span class="description">' . esc_attr( $desc ) . '</span></th>' . "\n";
					$output .= '<td>' . "\n";
								if ( isset($key['default']) ) {
									if ( $val !=  $key['default'] )
										$default_color = ' data-default-color="' .$key['default'] . '" ';
								}
					$output .= '<input name="' . esc_attr( $option_name . '[' . $obj_key . ']' ) . '" id="' . esc_attr( $obj_key ) . '" class="tom-color"  type="text" value="' . esc_attr( $val ) . '"' . $default_color .' />';
					$output .= '</td>' . "\n";
					$output .= '<td class="shortcode">
									<span><a class="button-copy-shortcode" title="Copy Shortcode" href="#"><i class="dashicons dashicons-nametag"></i><span class="shortcodeValue" data-title="'. esc_attr( $name ) .'" style="display:none;">'.$shortcode.'</span></a></span>
								</td>' . "\n";
					$output .= '</tr>' . "\n";
					break;

				case 'editor':
					$output .= '<tr class="alternate">' . "\n";
					$output .= '<th scope="row"><label for="' . esc_attr( $obj_key ) . '">' . esc_attr( $name ) . '</label><br><span class="description">' . esc_attr( $desc ) . '</span></th>' . "\n";
					$output .= '<td>' . "\n";
								echo $output;
								$textarea_name = esc_attr( $option_name . '[' . $obj_key . ']' );
								$default_editor_settings = array(
									'textarea_name' => $textarea_name,
									'media_buttons' => false,
									'tinymce' => array( 'plugins' => 'wordpress' )
								);
								$editor_settings = array();
								if ( isset( $key['settings'] ) ) {
									$editor_settings = $key['settings'];
								}
								$editor_settings = array_merge( $default_editor_settings, $editor_settings );
								wp_editor( $val, $obj_key, $editor_settings );
								$output = '';
					$output .= '</td>' . "\n";
					$output .= '<td class="shortcode">
									<span><a class="button-copy-shortcode" title="Copy Shortcode" href="#"><i class="dashicons dashicons-nametag"></i><span class="shortcodeValue" data-title="'. esc_attr( $name ) .'" style="display:none;">'.$shortcode.'</span></a></span>
								</td>' . "\n";
					$output .= '</tr>' . "\n";
					break;

				case 'typography':

					unset( $font_size, $font_style, $font_face, $font_color );
					$typography_defaults = array(
						'size' => '',
						'face' => '',
						'style' => '',
						'color' => ''
					);

					$typography_stored = wp_parse_args( $val, $typography_defaults );

					$typography_options = array(
						'sizes' => tom_recognized_font_sizes(),
						'faces' => tom_recognized_font_faces(),
						'styles' => tom_recognized_font_styles(),
						'color' => true
					);

					if ( isset( $key['options'] ) ) {
						$typography_options = wp_parse_args( $key['options'], $typography_options );
					}

					// Font Size
					if ( $typography_options['sizes'] ) {
						$font_size = '<select class="tom-typography tom-typography-size" name="' . esc_attr( $option_name . '[' . $obj_key . '][size]' ) . '" id="' . esc_attr( $obj_key . '_size' ) . '">';
						$sizes = $typography_options['sizes'];
						foreach ( $sizes as $i ) {
							$size = $i . 'px';
							$font_size .= '<option value="' . esc_attr( $size ) . '" ' . selected( $typography_stored['size'], $size, false ) . '>' . esc_html( $size ) . '</option>';
						}
						$font_size .= '</select>';
					}

					// Font Face
					if ( $typography_options['faces'] ) {
						$font_face = '<select class="tom-typography tom-typography-face" name="' . esc_attr( $option_name . '[' . $obj_key . '][face]' ) . '" id="' . esc_attr( $obj_key . '_face' ) . '">';
						$faces = $typography_options['faces'];
						foreach ( $faces as $key => $face ) {
							$font_face .= '<option value="' . esc_attr( $key ) . '" ' . selected( $typography_stored['face'], $key, false ) . '>' . esc_html( $face ) . '</option>';
						}
						$font_face .= '</select>';
					}

					// Font Styles
					if ( $typography_options['styles'] ) {
						$font_style = '<select class="tom-typography tom-typography-style" name="'.$option_name.'['.$obj_key.'][style]" id="'. $obj_key.'_style">';
						$styles = $typography_options['styles'];
						foreach ( $styles as $key => $style ) {
							$font_style .= '<option value="' . esc_attr( $key ) . '" ' . selected( $typography_stored['style'], $key, false ) . '>'. $style .'</option>';
						}
						$font_style .= '</select>';
					}

					// Font Color
					if ( $typography_options['color'] ) {
						$default_color = '';
						if ( isset($key['default']['color']) ) {
							if ( $val !=  $key['default']['color'] )
								$default_color = ' data-default-color="' .$key['default']['color'] . '" ';
						}
						$font_color = '<input name="' . esc_attr( $option_name . '[' . $obj_key . '][color]' ) . '" id="' . esc_attr( $obj_key . '_color' ) . '" class="tom-color tom-typography-color  type="text" value="' . esc_attr( $typography_stored['color'] ) . '"' . $default_color .' />';
					}

					// Allow modification/injection of typography fields
					$typography_fields = compact( 'font_size', 'font_face', 'font_style', 'font_color' );
					$typography_fields = apply_filters( 'tom_typography_fields', $typography_fields, $typography_stored, $option_name, $key );
					

					$output .= '<tr class="alternate">' . "\n";
					$output .= '<th scope="row"><label for="' . esc_attr( $obj_key ) . '">' . esc_attr( $name ) . '</label><br><span class="description">' . esc_attr( $desc ) . '</span></th>' . "\n";
					$output .= '<td>' . "\n";
					$output .= implode( '', $typography_fields );			
					$output .= '</td>' . "\n";
					$output .= '<td class="shortcode">
									<span><a class="button-copy-shortcode" title="Copy Shortcode" href="#"><i class="dashicons dashicons-nametag"></i><span class="shortcodeValue" data-title="'. esc_attr( $name ) .'" style="display:none;">'.$shortcode.'</span></a></span>
								</td>' . "\n";
					$output .= '</tr>' . "\n";

					break;

				// default input
				default:
					$output .= '<tr class="alternate">' . "\n";
					$output .= '<th scope="row"><label for="' . esc_attr( $obj_key ) . '">' . esc_attr( $name ) . '</label><br><span class="description">' . esc_attr( $desc ) . '</span></th>' . "\n";
					$output .= '<td><input class="tom-input" type="' . esc_attr( $type ) . '" id="' . esc_attr( $obj_key ) . '" name="' . esc_attr( $option_name . '[' . $obj_key . ']' ) . '" placeholder="' . esc_attr( $val ) . '" value="' . esc_attr( $val ) . '"></td>' . "\n";
					$output .= '<td class="shortcode">
									<span><a class="button-copy-shortcode" title="Copy Shortcode" href="#"><i class="dashicons dashicons-nametag"></i><span class="shortcodeValue" data-title="'. esc_attr( $name ) .'" style="display:none;">'.$shortcode.'</span></a></span>
								</td>' . "\n";
					$output .= '</tr>' . "\n";
					// $output .= '<input id="' . esc_attr( $obj_key ) . '" class="tom-input" name="' . esc_attr( $option_name . '[' . $obj_key . ']' ) . '" type="text" value="' . esc_attr( $val ) . '" />';
					break;
				}



				// // Password input
				// case 'password':
				// 	$output .= '<input id="' . esc_attr( $obj_key ) . '" class="tom-input" name="' . esc_attr( $option_name . '[' . $obj_key . ']' ) . '" type="password" value="' . esc_attr( $val ) . '" />';
				// 	break;

				// // Textarea
				// case 'textarea':
				// 	$rows = '8';

				// 	if ( isset( $key['settings']['rows'] ) ) {
				// 		$custom_rows = $key['settings']['rows'];
				// 		if ( is_numeric( $custom_rows ) ) {
				// 			$rows = $custom_rows;
				// 		}
				// 	}

				// 	$val = stripslashes( $val );
				// 	$output .= '<textarea id="' . esc_attr( $obj_key ) . '" class="tom-input" name="' . esc_attr( $option_name . '[' . $obj_key . ']' ) . '" rows="' . $rows . '">' . esc_textarea( $val ) . '</textarea>';
				// 	break;

				// // Select Box
				// case 'select':
				// 	$output .= '<select class="tom-input" name="' . esc_attr( $option_name . '[' . $obj_key . ']' ) . '" id="' . esc_attr( $obj_key ) . '">';

				// 	foreach ($key['options'] as $key => $option ) {
				// 		$output .= '<option'. selected( $val, $key, false ) .' value="' . esc_attr( $key ) . '">' . esc_html( $option ) . '</option>';
				// 	}
				// 	$output .= '</select>';
				// 	break;


				// // Radio Box
				// case "radio":
				// 	$name = $option_name .'['. $obj_key .']';
				// 	foreach ($key['options'] as $key => $option) {
				// 		$id = $option_name . '-' . $obj_key .'-'. $key;
				// 		$output .= '<input class="tom-input tom-radio" type="radio" name="' . esc_attr( $name ) . '" id="' . esc_attr( $id ) . '" value="'. esc_attr( $key ) . '" '. checked( $val, $key, false) .' /><label for="' . esc_attr( $id ) . '">' . esc_html( $option ) . '</label>';
				// 	}
				// 	break;

				// // Image Selectors
				// case "images":
				// 	$name = $option_name .'['. $obj_key .']';
				// 	foreach ( $key['options'] as $key => $option ) {
				// 		$selected = '';
				// 		if ( $val != '' && ($val == $key) ) {
				// 			$selected = ' tom-radio-img-selected';
				// 		}
				// 		$output .= '<input type="radio" id="' . esc_attr( $obj_key .'_'. $key) . '" class="tom-radio-img-radio" value="' . esc_attr( $key ) . '" name="' . esc_attr( $name ) . '" '. checked( $val, $key, false ) .' />';
				// 		$output .= '<div class="tom-radio-img-label">' . esc_html( $key ) . '</div>';
				// 		$output .= '<img src="' . esc_url( $option ) . '" alt="' . $option .'" class="tom-radio-img-img' . $selected .'" onclick="document.getElementById(\''. esc_attr($obj_key .'_'. $key) .'\').checked=true;" />';
				// 	}
				// 	break;

				// // Checkbox
				// case "checkbox":
				// 	$output .= '<input id="' . esc_attr( $obj_key ) . '" class="checkbox tom-input" type="checkbox" name="' . esc_attr( $option_name . '[' . $obj_key . ']' ) . '" '. checked( $val, 1, false) .' />';
				// 	$output .= '<label class="explain" for="' . esc_attr( $obj_key ) . '">' . wp_kses( $explain_value, $allowedtags) . '</label>';
				// 	break;

				// // Multicheck
				// case "multicheck":
				// 	foreach ($key['options'] as $key => $option) {
				// 		$checked = '';
				// 		$label = $option;
				// 		$option = preg_replace('/[^a-zA-Z0-9._\-]/', '', strtolower($key));

				// 		$id = $option_name . '-' . $obj_key . '-'. $option;
				// 		$name = $option_name . '[' . $obj_key . '][' . $option .']';

				// 		if ( isset($val[$option]) ) {
				// 			$checked = checked($val[$option], 1, false);
				// 		}

				// 		$output .= '<input id="' . esc_attr( $id ) . '" class="checkbox tom-input" type="checkbox" name="' . esc_attr( $name ) . '" ' . $checked . ' /><label for="' . esc_attr( $id ) . '">' . esc_html( $label ) . '</label>';
				// 	}
				// 	break;

				// // Color picker
				// case "color":
				// 	$default_color = '';
				// 	if ( isset($key['default']) ) {
				// 		if ( $val !=  $key['default'] )
				// 			$default_color = ' data-default-color="' .$key['default'] . '" ';
				// 	}
				// 	$output .= '<input name="' . esc_attr( $option_name . '[' . $obj_key . ']' ) . '" id="' . esc_attr( $obj_key ) . '" class="tom-color"  type="text" value="' . esc_attr( $val ) . '"' . $default_color .' />';

				// 	break;

				// // Uploader
				// case "upload":
				// 	$output .= tomUpload::tom_uploader( $obj_key, $val, null );

				// 	break;

				// // Typography
				// case 'typography':

				// 	unset( $font_size, $font_style, $font_face, $font_color );

				// 	$typography_defaults = array(
				// 		'size' => '',
				// 		'face' => '',
				// 		'style' => '',
				// 		'color' => ''
				// 	);

				// 	$typography_stored = wp_parse_args( $val, $typography_defaults );

				// 	$typography_options = array(
				// 		'sizes' => tom_recognized_font_sizes(),
				// 		'faces' => tom_recognized_font_faces(),
				// 		'styles' => tom_recognized_font_styles(),
				// 		'color' => true
				// 	);

				// 	if ( isset( $key['options'] ) ) {
				// 		$typography_options = wp_parse_args( $key['options'], $typography_options );
				// 	}

				// 	// Font Size
				// 	if ( $typography_options['sizes'] ) {
				// 		$font_size = '<select class="tom-typography tom-typography-size" name="' . esc_attr( $option_name . '[' . $obj_key . '][size]' ) . '" id="' . esc_attr( $obj_key . '_size' ) . '">';
				// 		$sizes = $typography_options['sizes'];
				// 		foreach ( $sizes as $i ) {
				// 			$size = $i . 'px';
				// 			$font_size .= '<option value="' . esc_attr( $size ) . '" ' . selected( $typography_stored['size'], $size, false ) . '>' . esc_html( $size ) . '</option>';
				// 		}
				// 		$font_size .= '</select>';
				// 	}

				// 	// Font Face
				// 	if ( $typography_options['faces'] ) {
				// 		$font_face = '<select class="tom-typography tom-typography-face" name="' . esc_attr( $option_name . '[' . $obj_key . '][face]' ) . '" id="' . esc_attr( $obj_key . '_face' ) . '">';
				// 		$faces = $typography_options['faces'];
				// 		foreach ( $faces as $key => $face ) {
				// 			$font_face .= '<option value="' . esc_attr( $key ) . '" ' . selected( $typography_stored['face'], $key, false ) . '>' . esc_html( $face ) . '</option>';
				// 		}
				// 		$font_face .= '</select>';
				// 	}

				// 	// Font Styles
				// 	if ( $typography_options['styles'] ) {
				// 		$font_style = '<select class="tom-typography tom-typography-style" name="'.$option_name.'['.$obj_key.'][style]" id="'. $obj_key.'_style">';
				// 		$styles = $typography_options['styles'];
				// 		foreach ( $styles as $key => $style ) {
				// 			$font_style .= '<option value="' . esc_attr( $key ) . '" ' . selected( $typography_stored['style'], $key, false ) . '>'. $style .'</option>';
				// 		}
				// 		$font_style .= '</select>';
				// 	}

				// 	// Font Color
				// 	if ( $typography_options['color'] ) {
				// 		$default_color = '';
				// 		if ( isset($key['default']['color']) ) {
				// 			if ( $val !=  $key['default']['color'] )
				// 				$default_color = ' data-default-color="' .$key['default']['color'] . '" ';
				// 		}
				// 		$font_color = '<input name="' . esc_attr( $option_name . '[' . $obj_key . '][color]' ) . '" id="' . esc_attr( $obj_key . '_color' ) . '" class="tom-color tom-typography-color  type="text" value="' . esc_attr( $typography_stored['color'] ) . '"' . $default_color .' />';
				// 	}

				// 	// Allow modification/injection of typography fields
				// 	$typography_fields = compact( 'font_size', 'font_face', 'font_style', 'font_color' );
				// 	$typography_fields = apply_filters( 'tom_typography_fields', $typography_fields, $typography_stored, $option_name, $key );
				// 	$output .= implode( '', $typography_fields );

				// 	break;

				// // Background
				// case 'background':

				// 	$background = $val;

				// 	// Background Color
				// 	$default_color = '';
				// 	if ( isset( $key['default']['color'] ) ) {
				// 		if ( $val !=  $key['default']['color'] )
				// 			$default_color = ' data-default-color="' .$key['default']['color'] . '" ';
				// 	}
				// 	$output .= '<input name="' . esc_attr( $option_name . '[' . $obj_key . '][color]' ) . '" id="' . esc_attr( $obj_key . '_color' ) . '" class="tom-color tom-background-color"  type="text" value="' . esc_attr( $background['color'] ) . '"' . $default_color .' />';

				// 	// Background Image
				// 	if ( !isset($background['image']) ) {
				// 		$background['image'] = '';
				// 	}

				// 	$output .= tomUpload::tom_uploader( $obj_key, $background['image'], null, esc_attr( $option_name . '[' . $obj_key . '][image]' ) );

				// 	$class = 'tom-background-properties';
				// 	if ( '' == $background['image'] ) {
				// 		$class .= ' hide';
				// 	}
				// 	$output .= '<div class="' . esc_attr( $class ) . '">';

				// 	// Background Repeat
				// 	$output .= '<select class="tom-background tom-background-repeat" name="' . esc_attr( $option_name . '[' . $obj_key . '][repeat]'  ) . '" id="' . esc_attr( $obj_key . '_repeat' ) . '">';
				// 	$repeats = tom_recognized_background_repeat();

				// 	foreach ($repeats as $key => $repeat) {
				// 		$output .= '<option value="' . esc_attr( $key ) . '" ' . selected( $background['repeat'], $key, false ) . '>'. esc_html( $repeat ) . '</option>';
				// 	}
				// 	$output .= '</select>';

				// 	// Background Position
				// 	$output .= '<select class="tom-background tom-background-position" name="' . esc_attr( $option_name . '[' . $obj_key . '][position]' ) . '" id="' . esc_attr( $obj_key . '_position' ) . '">';
				// 	$positions = tom_recognized_background_position();

				// 	foreach ($positions as $key=>$position) {
				// 		$output .= '<option value="' . esc_attr( $key ) . '" ' . selected( $background['position'], $key, false ) . '>'. esc_html( $position ) . '</option>';
				// 	}
				// 	$output .= '</select>';

				// 	// Background Attachment
				// 	$output .= '<select class="tom-background tom-background-attachment" name="' . esc_attr( $option_name . '[' . $obj_key . '][attachment]' ) . '" id="' . esc_attr( $obj_key . '_attachment' ) . '">';
				// 	$attachments = tom_recognized_background_attachment();

				// 	foreach ($attachments as $key => $attachment) {
				// 		$output .= '<option value="' . esc_attr( $key ) . '" ' . selected( $background['attachment'], $key, false ) . '>' . esc_html( $attachment ) . '</option>';
				// 	}
				// 	$output .= '</select>';
				// 	$output .= '</div>';

				// 	break;

				// // Editor
				// case 'editor':
				// 	$output .= '<div class="explain">' . wp_kses( $explain_value, $allowedtags ) . '</div>'."\n";
				// 	echo $output;
				// 	$textarea_name = esc_attr( $option_name . '[' . $obj_key . ']' );
				// 	$default_editor_settings = array(
				// 		'textarea_name' => $textarea_name,
				// 		'media_buttons' => false,
				// 		'tinymce' => array( 'plugins' => 'wordpress' )
				// 	);
				// 	$editor_settings = array();
				// 	if ( isset( $key['settings'] ) ) {
				// 		$editor_settings = $key['settings'];
				// 	}
				// 	$editor_settings = array_merge( $default_editor_settings, $editor_settings );
				// 	wp_editor( $val, $obj_key, $editor_settings );
				// 	$output = '';
				// 	break;

				


				// if ( $key['type'] != "heading" ) {
				// 	$output .= '</div>';
				// 	if ( ( $key['type'] != "checkbox" ) && ( $key['type'] != "editor" ) ) {
				// 		$output .= '<div class="explain">' . wp_kses( $explain_value, $allowedtags) . '</div>'."\n";
				// 	}
				// 	$output .= '</div></div>'."\n";
				// }
				
				echo $output;
			}
			/* tutup table terakhir */
			echo '</tbody></table></div>';

			if ( tomGenerate::tom_tabs() != '' ) {
				echo '</div>';
			}

			/* Submit Button */
			$submit =  '<div id="tonjoo-tom-submit">
							<input type="submit" class="button-primary" name="update" value="Save" />
							<input type="submit" class="reset-button button-secondary" name="reset" value="Reset" onclick="return confirm( \'Are you sure to reset. Any theme settings will be lost!\');" />
							<div class="clear"></div>
						</div>';


		} else { 

		/* Handle if empty options  */
		$config = tomOptions::tom_configs();
		$output =  '<div id="empty-group" class="group empty-group">
					  <h3>
					    Oops! your options look empty..
					  </h3>
					  <div class="container-table">
					  	<div class="empty-options">
							<h1>Oops! your options look empty..</h1>
							<h4>Please create options or put options file on theme directory</h4>';
		/* If Create options enabled, display button to create it */
		if ($config['mode'] == 'full') {
		$output .=	  	   '<div class="please-create-options">
					  			<a href="' . get_admin_url( null, 'admin.php?page=' . $config['sub_menu_slug'] ) .'" class="btn">Create Options Now</a>
					  		</div>';
		}
		$output .=	   '</div>
					  </div>
					</div>';
		echo $output;

		/* No Submit Button */
		$submit =  '';

		}

		/* Print Submit Button */
		echo $submit;
	}

	static function generate_create_options_fields() {
		// global $allowedtags;

		$option_name = 'tom_options';
		$options = tomOptions::tom_options_fields();
		$config = tomOptions::tom_configs();

		$counter = 0;
		$initNestable = '';
		
		if(!empty($options)) {
		// echo "<pre>";
		// print_r($options);
		// echo "</pre>";
		// exit();
			foreach ($options as $obj_key =>$key) {
				$name = ! empty( $key['name'] ) ? $key['name'] : '';
				$desc = ! empty( $key['desc'] ) ? $key['desc'] : '';
				$type = ! empty( $key['type'] ) ? $key['type'] : '';
				$configs = tomOptions::tom_configs();
				$types = $configs['type-options'];
				if ($type == 'select') {
					$display = '';
				} else {
					$display = 'style="display:none;"';
				}
				$fieldoptions = ! empty( $key['options'] ) ? $key['options'] : array();
				$val = ! empty( $key['default'] ) ? $key['default'] : '';
				// if ( $key['type'] != 'heading' ) {
				// 	if ( isset( $settings[($obj_key)]) ) {
				// 		$val = $settings[($obj_key)];
				// 		// Striping slashes of non-array options
				// 		if ( !is_array($val) ) {
				// 			$val = stripslashes( $val );
				// 		}
				// 	}
				// }


				$output = '';
				if ( $key['type'] != "heading" ) {

					// Keep all ids lowercase with no spaces
					$obj_key = preg_replace('/[^a-zA-Z0-9._\-]/', '', strtolower($obj_key) );

					$output .= '<li class="dd-item" data-id="'.esc_attr( $obj_key ).'">'."\n";
						$output .= '<div class="dd-handle">' . $key['name'] ."\n";
						/* button action */
						$output .= '<span class="tom-action-buttons">
										<a class="blue edit-nestable" href="#">
											<i class="dashicons dashicons-edit"></i>
										</a>
										<a class="red delete-nestable" href="#">
											<i class="dashicons dashicons-trash"></i>
										</a>
									</span>';
						$output .= '</div>'."\n";
						$output .= '<div class="nestable-input" id="'.esc_attr( $obj_key ).'" style="display:none;">'."\n";
						$output .= 		'<table class="widefat">
											  <tbody>
											    <tr class="inline-edit-row inline-edit-row-page inline-edit-page quick-edit-row quick-edit-row-page inline-edit-page alternate inline-editor">
											      <td colspan="5" class="colspanchange" style="padding-bottom:10px;">
											        <fieldset class="inline-edit-col-left">
											          <div class="inline-edit-col">
											            <h4>Edit Option : <span>'.esc_attr( $obj_key ).'</span></h4>
											            <label>
											              <span class="title">Name</span>
											              <span class="input-text-wrap input">
											                <input type="text" name="' . esc_attr( $option_name . '[' . $obj_key . ']' ) . '[name]" class="" value="' . esc_attr( $name ) . '">
											              </span>
											            </label>
											            <label>
											              <span class="title">
											                Description
											              </span>
											              <span class="input-text-wrap input">
											                <textarea name="' . esc_attr( $option_name . '[' . $obj_key . ']' ) . '[desc]">' . esc_attr( $desc ) . '</textarea>
											              </span>
											            </label>
											            </div>
											          </div>
											        </fieldset>
											        <fieldset class="inline-edit-col-right">
											          <div class="inline-edit-col">
											            <label>
											              <span class="title">
											                Type
											              </span>
											              <span class="input-text-wrap input">
												              <select name="' . esc_attr( $option_name . '[' . $obj_key . ']' ) . '[type]" id="tom-type-'.esc_attr( $obj_key ).'" class="tom-type" data-container="'.esc_attr( $obj_key ).'">'."\n";
												                foreach ($types as $key => $option ) {
																	/* function selected dr wp @http://codex.wordpress.org/Function_Reference/selected */
																	$output .= '<option'. selected( $type, $key, false ) .' value="' . esc_attr( $key ) . '">' . esc_html( $option ) . '</option>';
																}
						$output .= 							  '</select>
									              		  </span>
											            </label>
											            <label id="'.esc_attr( $obj_key ).'-options" '.$display.'>
											              <span class="title">
											                Options
											              </span>
											              <span class="input-text-wrap input">
												           	<div id="opt-container-'.esc_attr( $obj_key ).'" class="options-container" data-default="'.esc_attr( $obj_key ).'">
														        <div id="add-opt-'.esc_attr( $obj_key ).'" class="input-options">'."\n";
																
																switch ($type) {
																	case 'typography':
																		// print_r($fieldoptions);
																		break;
																	
																	default:
																		$order = 1;
																		foreach ($fieldoptions as $key => $value ) {
																			// echo "<pre>";
																			// print_r($value);
																			// echo "</pre>";
																			// exit();
																			// echo $key;
						$output .=											'<div data-order="'.$order.'" class="input-options-group">
																	        	<i class="dashicons dashicons-yes"></i>
																	        	<input class="input-opt input-key" name="' . esc_attr( $option_name . '[' . $obj_key . ']' ) . '[options][opt-key][]" value="'.$key.'" placeholder="Key">
																	        	<input class="input-opt input-val" name="' . esc_attr( $option_name . '[' . $obj_key . ']' ) . '[options][opt-val][]" value="'.esc_attr( $value ).'" placeholder="Value">
																	        	<a class="btn-remove dashicons dashicons-dismiss"></a>
																        	</div>'."\n";
																		$order++;
																		}
																		break;
																}
						$output .= 								'</div>
														        <p><a id="new-repeatable" href="#">Add New Field</a></p>
													        </div>
									              		  </span>
											            </label>
											            <label>
											              <span class="title">
											                Default
											              </span>
											              <span class="input-text-wrap input">';
						// $output .=					     '<input type="hidden" id="'.esc_attr( $obj_key ).'-hidden-default" value="">';
				        $output .=						 '<div id="'.esc_attr( $obj_key ).'-default">';
															/***********************
															* Switch input type
															************************/
															switch ($type) {
																case 'select':
																	if (!empty($fieldoptions)) {
						$output .=			              			'<select name="' . esc_attr( $option_name . '[' . $obj_key . ']' ) . '[default]" id="tom-default-'.esc_attr( $obj_key ).'">';										
																		foreach ($fieldoptions as $key => $option ) {
																			/* function selected dr wp @http://codex.wordpress.org/Function_Reference/selected */
																			$output .= '<option'. selected( $val, $key, false ) .' value="' . esc_attr( $key ) . '">' . esc_html( $option ) . '</option>';
																		}
						$output .=			              			'</select>';										
																	} else {
						$output .=			              			'<select>Select default value</select>';	
																	}	
																	break;
																
																case 'textarea':
						$output .=			              			'<textarea name="' . esc_attr( $option_name . '[' . $obj_key . ']' ) . '[default]" id="tom-default-'.esc_attr( $obj_key ).'">'.esc_attr( $val ).'</textarea>';
																	break;
																
																case 'typography':
																	// print_r($val);
						// $output .=			              			'<textarea name="' . esc_attr( $option_name . '[' . $obj_key . ']' ) . '[default]" id="tom-default-'.esc_attr( $obj_key ).'">'.esc_attr( $val['size'] ).'</textarea>';
																	break;

																default:
						$output .=			              			'<input name="' . esc_attr( $option_name . '[' . $obj_key . ']' ) . '[default]" type="text" value="'.esc_attr( $val ).'">';
																	break;
															}
						$output .=			              	'</div>
											              </span>
											            </label>
											          </div>
											        </fieldset>
											      </tbody>
											  </table>'."\n";
						$output .= '</div>'."\n";
					$output .= '</li>'."\n";
				}

				// Heading for Navigation
				if (is_array($key) && $key['type'] == "heading") {
					$counter++;
					if ( $counter >= 2 ) {
						$output .= '</ol></div></div>'."\n";
					}

					/* init nestable menu */
					$initNestable .= '$("#nestable-' . $counter . '").nestable({"maxDepth":"1"});'."\n";

					$class = '';
					$class = ! empty( $obj_key ) ? $obj_key : $key['name'];
					$class = preg_replace('/[^a-zA-Z0-9._\-]/', '', strtolower($class) );
					$output .= '<div id="options-group-' . $counter . '" class="group ' . $class . '">';
					$output .= '<h3>' . esc_html( $key['name'] ) . '</h3>' . "\n";

					/* output heading ke hidden input biar tetep kesimpen jadi array */
					$output .= '<input name="tom_options['.esc_attr( $obj_key ).'][name]" type="hidden" class="" value="' . $key['name'] .'" />
								<input name="tom_options['.esc_attr( $obj_key ).'][type]" type="hidden" class="" value="' . $key['type'] .'" />';
					// debug
					// $output .= '<textarea id="nestable-output"></textarea>';
					$output .= '<div class="dd" id="nestable-' . $counter . '">' . "\n";
					$output .= '<ol class="dd-list container-body">' . "\n";
				} 

				// if ( $key['type'] != "heading" ) {
				// 	$output .= $key['name'];
				// 	$output .= '</div></li>'."\n";
				// }

				echo $output;
			}

			echo '</ol></div>';
			
			// /* Print Submit and Delete Group Button */
			// echo	'<div id="tonjoo-tom-submit">
			// 			<input type="submit" class="button-primary" name="update" value="Save" />
			// 			<a id="tom-delete-group" class="reset-button button-secondary">Delete Group</a>
			// 			<div class="clear"></div>
			// 		</div>';

			// Outputs closing div if there tabs
			if ( tomGenerate::create_tom_tabs() != '' ) {
				echo '</div>'."\n";
			}
			/* Initial nestable list */
			echo '<script type="text/javascript">
					jQuery(document).ready(function($) {
						'. $initNestable .'
					});
				  </script>';

			

			/* Submit and Delete Group Button */
			$submit =  '<div id="tonjoo-tom-submit">
							<input type="submit" class="button-primary" name="update" value="Save" />
							<a id="tom-delete-group" class="reset-button button-secondary">Delete Group</a>
							<div class="clear"></div>
						</div>';
		} else {

			/* Create New Group Button */
			$submit =  '<div id="tonjoo-tom-submit">
							<input type="submit" class="button-primary" name="update" value="Create" />
							<div class="clear"></div>
						</div>';
			}

			/* Div Create new option group */
			echo '	<div id="new-group" class="group new-group">
					  <h3>
					    Create New Option Group
					  </h3>
					  <div class="container-table">
					  	<table class="widefat">
						  <tbody>
						    <tr class="alternate">
								<th scope="row"><label for="group-name">Group Name</label><br><span class="description">Name of option group</span></th>
								<td><input class="tom-input" type="text" id="group-name" name="tom_options[new-group][name]" placeholder="Group Name" value=""></td>
							</tr>
						    <tr class="alternate">
								<th scope="row"><label for="group-desc">Description</label><br><span class="description">Short descriptipn</span></th>
								<td><textarea class="tom-input" id="group-desc" name="tom_options[new-group][desc]" placeholder="Description" rows="4" cols="50"></textarea></td>
							</tr>
						  </tbody>
						</table>
					  </div>
					</div>';

			/* Print Submit Button */
			echo $submit;
	}

}