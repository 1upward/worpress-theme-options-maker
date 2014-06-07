<?php
/* Text */

add_filter( 'tom_sanitize_text', 'sanitize_text_field' );

/* Password */

add_filter( 'tom_sanitize_password', 'sanitize_text_field' );

/* Textarea */

function tom_sanitize_textarea(  $input) {
	global $allowedposttags;
	$output = wp_kses( $input, $allowedposttags);
	return $output;
}

add_filter( 'tom_sanitize_textarea', 'tom_sanitize_textarea' );

/* Select */

add_filter( 'tom_sanitize_select', 'tom_sanitize_enum', 10, 2 );

/* Radio */

add_filter( 'tom_sanitize_radio', 'tom_sanitize_enum', 10, 2 );

/* Images */

add_filter( 'tom_sanitize_images', 'tom_sanitize_enum', 10, 2 );

/* Checkbox */

function tom_sanitize_checkbox( $input ) {
	if ( $input ) {
		$output = '1';
	} else {
		$output = false;
	}
	return $output;
}
add_filter( 'tom_sanitize_checkbox', 'tom_sanitize_checkbox' );

/* Multicheck */

function tom_sanitize_multicheck( $input, $option ) {
	$output = '';
	if ( is_array( $input ) ) {
		foreach( $option['options'] as $key => $value ) {
			$output[$key] = false;
		}
		foreach( $input as $key => $value ) {
			if ( array_key_exists( $key, $option['options'] ) && $value ) {
				$output[$key] = "1";
			}
		}
	}
	return $output;
}
add_filter( 'tom_sanitize_multicheck', 'tom_sanitize_multicheck', 10, 2 );

/* Color Picker */

add_filter( 'tom_sanitize_color', 'tom_sanitize_hex' );

/* Uploader */

function tom_sanitize_upload( $input ) {
	$output = '';
	$filetype = wp_check_filetype( $input );
	if ( $filetype["ext"] ) {
		$output = esc_url( $input );
	}
	return $output;
}
add_filter( 'tom_sanitize_upload', 'tom_sanitize_upload' );

/* Editor */

function tom_sanitize_editor( $input ) {
	if ( current_user_can( 'unfiltered_html' ) ) {
		$output = $input;
	}
	else {
		global $allowedtags;
		$output = wpautop(wp_kses( $input, $allowedtags));
	}
	return $output;
}
add_filter( 'tom_sanitize_editor', 'tom_sanitize_editor' );

/* Allowed Tags */

function tom_sanitize_allowedtags( $input ) {
	global $allowedtags;
	$output = wpautop( wp_kses( $input, $allowedtags ) );
	return $output;
}

/* Allowed Post Tags */

function tom_sanitize_allowedposttags( $input ) {
	global $allowedposttags;
	$output = wpautop( wp_kses( $input, $allowedposttags) );
	return $output;
}
add_filter( 'tom_sanitize_info', 'tom_sanitize_allowedposttags' );

/* Check that the key value sent is valid */

function tom_sanitize_enum( $input, $option ) {
	$output = '';
	if ( array_key_exists( $input, $option['options'] ) ) {
		$output = $input;
	}
	return $output;
}

/* Background */

function tom_sanitize_background( $input ) {
	$output = wp_parse_args( $input, array(
		'color' => '',
		'image'  => '',
		'repeat'  => 'repeat',
		'position' => 'top center',
		'attachment' => 'scroll'
	) );

	$output['color'] = apply_filters( 'tom_sanitize_hex', $input['color'] );
	$output['image'] = apply_filters( 'tom_sanitize_upload', $input['image'] );
	$output['repeat'] = apply_filters( 'tom_background_repeat', $input['repeat'] );
	$output['position'] = apply_filters( 'tom_background_position', $input['position'] );
	$output['attachment'] = apply_filters( 'tom_background_attachment', $input['attachment'] );

	return $output;
}
add_filter( 'tom_sanitize_background', 'tom_sanitize_background' );

function tom_sanitize_background_repeat( $value ) {
	$recognized = tom_recognized_background_repeat();
	if ( array_key_exists( $value, $recognized ) ) {
		return $value;
	}
	return apply_filters( 'tom_default_background_repeat', current( $recognized ) );
}
add_filter( 'tom_background_repeat', 'tom_sanitize_background_repeat' );

function tom_sanitize_background_position( $value ) {
	$recognized = tom_recognized_background_position();
	if ( array_key_exists( $value, $recognized ) ) {
		return $value;
	}
	return apply_filters( 'tom_default_background_position', current( $recognized ) );
}
add_filter( 'tom_background_position', 'tom_sanitize_background_position' );

function tom_sanitize_background_attachment( $value ) {
	$recognized = tom_recognized_background_attachment();
	if ( array_key_exists( $value, $recognized ) ) {
		return $value;
	}
	return apply_filters( 'tom_default_background_attachment', current( $recognized ) );
}
add_filter( 'tom_background_attachment', 'tom_sanitize_background_attachment' );


/* Typography */

function tom_sanitize_typography( $input, $option ) {

	$output = wp_parse_args( $input, array(
		'size'  => '',
		'face'  => '',
		'style' => '',
		'color' => ''
	) );

	if ( isset( $option['options']['faces'] ) && isset( $input['face'] ) ) {
		if ( !( array_key_exists( $input['face'], $option['options']['faces'] ) ) ) {
			$output['face'] = '';
		}
	}
	else {
		$output['face']  = apply_filters( 'tom_font_face', $output['face'] );
	}

	$output['size']  = apply_filters( 'tom_font_size', $output['size'] );
	$output['style'] = apply_filters( 'tom_font_style', $output['style'] );
	$output['color'] = apply_filters( 'tom_sanitize_color', $output['color'] );
	return $output;
}
add_filter( 'tom_sanitize_typography', 'tom_sanitize_typography', 10, 2 );

function tom_sanitize_font_size( $value ) {
	$recognized = tom_recognized_font_sizes();
	$value_check = preg_replace('/px/','', $value);
	if ( in_array( (int) $value_check, $recognized ) ) {
		return $value;
	}
	return apply_filters( 'tom_default_font_size', $recognized );
}
add_filter( 'tom_font_size', 'tom_sanitize_font_size' );


function tom_sanitize_font_style( $value ) {
	$recognized = tom_recognized_font_styles();
	if ( array_key_exists( $value, $recognized ) ) {
		return $value;
	}
	return apply_filters( 'tom_default_font_style', current( $recognized ) );
}
add_filter( 'tom_font_style', 'tom_sanitize_font_style' );


function tom_sanitize_font_face( $value ) {
	$recognized = tom_recognized_font_faces();
	if ( array_key_exists( $value, $recognized ) ) {
		return $value;
	}
	return apply_filters( 'tom_default_font_face', current( $recognized ) );
}
add_filter( 'tom_font_face', 'tom_sanitize_font_face' );

/*
 Get recognized background repeat settings
 */
function tom_recognized_background_repeat() {
	$default = array(
		'no-repeat' => 'No Repeat',
		'repeat-x'  => 'Repeat Horizontally',
		'repeat-y'  => 'Repeat Vertically',
		'repeat'    => 'Repeat All',
		);
	return apply_filters( 'tom_recognized_background_repeat', $default );
}

/*
Get recognized background positions 
 */
function tom_recognized_background_position() {
	$default = array(
		'top left'      => 'Top Left',
		'top center'    => 'Top Center',
		'top right'     => 'Top Right',
		'center left'   => 'Middle Left',
		'center center' => 'Middle Center',
		'center right'  => 'Middle Right',
		'bottom left'   => 'Bottom Left',
		'bottom center' => 'Bottom Center',
		'bottom right'  => 'Bottom Right'
		);
	return apply_filters( 'tom_recognized_background_position', $default );
}

/* 
Get recognized background attachment
 */
function tom_recognized_background_attachment() {
	$default = array(
		'scroll' => 'Scroll Normally',
		'fixed'  => 'Fixed in Place'
		);
	return apply_filters( 'tom_recognized_background_attachment', $default );
}

/**
 * Sanitize a color represented in hexidecimal notation.
 */

function tom_sanitize_hex( $hex, $default = '' ) {
	if ( tom_validate_hex( $hex ) ) {
		return $hex;
	}
	return $default;
}


function tom_recognized_font_sizes() {
	$sizes = range( 9, 71 );
	$sizes = apply_filters( 'tom_recognized_font_sizes', $sizes );
	$sizes = array_map( 'absint', $sizes );
	return $sizes;
}

function tom_recognized_font_faces() {
	$default = array(
		'arial'     => 'Arial',
		'verdana'   => 'Verdana, Geneva',
		'trebuchet' => 'Trebuchet',
		'georgia'   => 'Georgia',
		'times'     => 'Times New Roman',
		'tahoma'    => 'Tahoma, Geneva',
		'palatino'  => 'Palatino',
		'helvetica' => 'Helvetica*'
		);
	return apply_filters( 'tom_recognized_font_faces', $default );
}

function tom_recognized_font_styles() {
	$default = array(
		'normal'      => 'Normal',
		'italic'      => 'Italic',
		'bold'        => 'Bold',
		'bold italic' => 'Bold Italic'
		);
	return apply_filters( 'tom_recognized_font_styles', $default );
}


function tom_validate_hex( $hex ) {
	$hex = trim( $hex );
	/* Strip recognized prefixes. */
	if ( 0 === strpos( $hex, '#' ) ) {
		$hex = substr( $hex, 1 );
	}
	elseif ( 0 === strpos( $hex, '%23' ) ) {
		$hex = substr( $hex, 3 );
	}
	/* Regex match. */
	if ( 0 === preg_match( '/^[0-9a-fA-F]{6}$/', $hex ) ) {
		return false;
	}
	else {
		return true;
	}
}