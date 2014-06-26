<?php
/*
 *	Plugin Name: Theme Options Maker
 *	Plugin URI: 
 *	Description: Interactive Theme Options Maker
 *	Author: tonjoo
 *	Version: 1.0
 *	Author URI: 
 */

function tonjoo_tom_init() {

	//  permission
	if ( !current_user_can( 'edit_theme_options' ) )
		return;

	// Load other resource
	require plugin_dir_path( __FILE__ ) . 'includes/class.tom-options.php';
	require plugin_dir_path( __FILE__ ) . 'includes/class.tom-generate.php';

	/* if file config exist */
	if ( file_exists( get_template_directory() . "/tonjoo_options.php" ) ) {
	    require_once( get_template_directory() . "/tonjoo_options.php" );
	
		if ( function_exists('tonjoo_tom_config') ) {
			add_filter( 'tom_config', 'tonjoo_tom_config');
		}

		if ( function_exists('tonjoo_tom_default') ) {
			add_filter( 'tom_default', 'tonjoo_tom_default');
		}

		if ( function_exists( 'tonjoo_tom_options' ) ) {
			add_filter( 'tom_options', 'tonjoo_tom_options');
		}
	} 


	// Instantiate the main plugin class.
	$tom = new tomOptions;
	$tom->init();

}
add_action( 'init', 'tonjoo_tom_init', 20 );


function filter_by_value ($array, $index, $value){ 
    if(is_array($array) && count($array)>0)  
    { 
        foreach(array_keys($array) as $key){ 
            $temp[$key] = $array[$key][$index]; 
             
            if ($temp[$key] == $value){ 
                $newarray[$key] = $array[$key]; 
            } 
        } 
      } 
  return $newarray; 
} 

/**************
* SHORTCODE 
**************/
function tom_shortcode( $atts = NULL ) {
    $param = shortcode_atts( array(
        'id' => '',
        'default' => '',
    ), $atts );

   	$id = $param['id'];
   	$default = $param['default'];

    $data = get_option( 'tom_data' );
    $options = tomOptions::tom_options_fields();
	$type = (!empty($options[$id]['type'])) ? $options[$id]['type'] : '';
	$val = (!empty($data[$id])) ? $data[$id] : '';

	/* Switch option type for special handling */
	switch ($type) {
		case 'multicheck':
			$value = serialize($val);
			break;

		case 'upload':
			$image = wp_get_attachment_image_src( $val );
			$value = $image[0];
			break;

		case 'typography':
			$value = serialize($val);
			break;

		default:
			$value =  $val;
			break;
	}
    
    /* If value empty try to get default value from shortcode */
	$tom_data = (!empty($value)) ? $value : $default;

    return $tom_data;
}

add_shortcode( 'tom', 'tom_shortcode' );




function tom_recognized_font_sizes() {
	$sizes = range( 9, 11 );
	$sizes = apply_filters( 'tom_recognized_font_sizes', $sizes );
	$sizes = array_map( 'absint', $sizes );
	return $sizes;
}

function tom_recognized_font_faces() {
	$default = array(
		'arial'     => 'Arial',
		'verdana'   => 'Verdana, Geneva'
		);
	return apply_filters( 'tom_recognized_font_faces', $default );
}
function tom_recognized_font_styles() {
	$default = array(
		'normal'      => 'Normal',
		'italic'      => 'Italic',
		);
	return apply_filters( 'tom_recognized_font_styles', $default );
}
?>