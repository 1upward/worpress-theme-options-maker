<?php
/*
 *	Plugin Name: Theme Options Maker
 *	Plugin URI: 
 *	Description: Theme options framework and generator for WordPress Theme. Available as a plugin or library
 *	Author:  tonjoo
 *	Version: 1.0.1
 *	Author URI: https://tonjoo.com
 *  Contributor: Todi Adiyatmo Wijoyo, Lafif Astahdziq
 */

define("TONJOO_HSCOMMENT", 'show-hide-comment');
define('TOM_VERSION','1.0.0');
define('HSCOMMENT_BASE_PATH',__DIR__);
define('HSCOMMENT_DIR_NAME', str_replace("/hide-show-comment.php", "", plugin_basename(__FILE__)));

// require_once( plugin_dir_path( __FILE__ ) . 'src/ajax.php');

//Included Files
include __DIR__.'/vendor/autoload.php';


// Plugin loaded
add_action('plugins_loaded', 'tom_generate_init');

function tom_generate_init()
{
	$tom = new Lotus\Almari\Container();
	$tom_option =  new Tonjoo\TOM\TOMOption($tom);	
	$tom_generate =  new Tonjoo\TOM\TOMGenerate($tom,$tom_option);
	// $tom_notice =  new Tonjoo\TOM\TOMNotice($tom,$tom_option);

	$tom->register('tom',$tom);
	$tom->register('tom_generate',function(){});
	$tom->register('tom_option',$tom_option);
	$tom->register('tom_generate',$tom_generate);	
	// $tom->register('tom_notice',$tom_notice);	

	// Load the alias mapper
	$aliasMapper = Lotus\Almari\AliasMapper::getInstance();

	// Create facade for TOM
	$alias['TOM'] = 'Tonjoo\TOM\Facade\TOMFacade';
	$alias['TOMOption'] = 'Tonjoo\TOM\Facade\TOMOptionFacade';
	$alias['TOMGenerate'] = 'Tonjoo\TOM\Facade\TOMGenerateFacade';
	// $alias['TOMNotice'] = 'Tonjoo\TOM\Facade\TOMNoticeFacade';
	
	$aliasMapper->facadeClassAlias($alias);

	//Register container to facade
	Tonjoo\TOM\Facade\TOMFacade::setFacadeContainer($tom);
	Tonjoo\TOM\Facade\TOMOptionFacade::setFacadeContainer($tom);
	Tonjoo\TOM\Facade\TOMGenerateFacade::setFacadeContainer($tom);
	// Tonjoo\TOM\Facade\TOMNoticeFacade::setFacadeContainer($tom);

}
include __DIR__.'/hooks/tom-back-end.php';
// include __DIR__.'/hooks/hsc-front-end.php';



/**************
* SHORTCODE 
**************/
function tom_shortcode( $atts = NULL ) {
    $param = shortcode_atts( array(
        'id' => '',
        'default' => '',
    ), $atts );

   	$id = $param['id'];

    $data = get_option( 'tom_data' );
    $options = tomOptions::tom_options_fields();

	$type = @$options[$id]['type'];
	$val = @$data[$id];
	$default = @$param['default'];

	$type = (!empty($type)) ? $type : '';
	$val = (!empty($val)) ? $val : '';
   	$default = (!empty($default)) ? $default : @$options[$id]['default'];
   	
	/* Switch option type for special handling */
	switch ($type) {
		case 'multicheck':
			$value = serialize($val);
			break;

		case 'upload':
			$image = wp_get_attachment_image_src( $val, 'full' );
			$value = (is_numeric($val)) ? $image[0] : $val;
			break;

		case 'typography':
			$value = serialize($val);
			break;

		default:
			$value =  $val;
			break;
	}
	// print_r($value); exit();

    /* If value empty try to get default value from shortcode */
	$tom_data = (!empty($value)) ? $value : $default;

	/* If SSL Enabled use https replace */
	$tom_data = (is_ssl()) ? tom_https_link($tom_data) : $tom_data;

    return $tom_data;
}

add_shortcode( 'tom', 'tom_shortcode' );

/* Replace url to https */
function tom_https_link($url){
	/* Validate value is URL */
	if(filter_var($url, FILTER_VALIDATE_URL)) {
		// Parse to get domain from url
		$parse_base = parse_url(get_site_url());
		$parse_url = parse_url($url);

		if ($parse_url['host'] == $parse_base['host']) {
			$url = str_replace('http://', 'https://', $url );
		}
	}
	return $url;
}