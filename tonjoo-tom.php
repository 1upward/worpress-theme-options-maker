<?php
/*
 *	Plugin Name: Theme Options Maker
 *	Plugin URI: 
 *	Description: Interactive Theme Options Maker
 *	Author: tonjoo
 *	Version: 1.0
 *	Author URI: 
 */

require  plugin_dir_path( __FILE__ ) .'ttom-upload.php';

$plugin = plugin_basename(__FILE__); 
add_filter("plugin_action_links_$plugin", 'tonjoo_tom_settings_link');

add_action('admin_menu', 'register_tonjoo_tom_menu');
add_action('admin_init', 'register_tonjoo_tom_menu_settings');
add_action('admin_init', 'tonjoo_tom_enqueue_script', 100);

function tonjoo_tom_settings_link($links)
{ 
    $links[] = '<a href="admin.php?page=tonjoo-tom/settings.php&noheader=true">Settings</a>'; 

    return $links; 
}

function register_tonjoo_tom_menu()
{
	$config = array('name'=>'DataTables','header'=>'');

	if(get_option('tonjoo_tom_config') != "")
	{
		$config = unserialize(get_option('tonjoo_tom_config'));
	}

	new TtomUploader(array('page' => 'tonjoo-tom/admin.php', 'page_type' => 'page'));
	
    add_menu_page('Theme Options', 'Theme Options', 'manage_options', 'tonjoo-tom/admin.php', '', /*plugins_url( 'myplugin/images/icon.png' )*/"", '50.31');
    add_submenu_page('tonjoo-tom/admin.php','Settings', 'Settings', 'manage_options', 'tonjoo-tom/settings.php');
    // add_submenu_page('tonjoo-tom/admin.php','Export & Import', 'Export & Import', 'manage_options', 'tonjoo-tom/backup.php');
}

function register_tonjoo_tom_menu_settings()
{
	register_setting( 'tonjoo_tom', 'tonjoo_tom');
	
	$option_group = 'ttom_admin_setting';

	$option_data = get_option("tonjoo_tom_data");
	$arr_data = json_decode(stripslashes($option_data), true);

	if(is_array($arr_data))
	{
		foreach ($arr_data as $n) 
		{
			register_setting($option_group, $n['1']);
		}
	}	
}

function tonjoo_tom_enqueue_script()
{
	if(isset($_GET['page']) &&$_GET['page'] == 'tonjoo-tom/settings.php')
	{
		$path = plugins_url();

		wp_enqueue_style('tonjoo-tom-css', $path.'/tonjoo-tom/assets/css/style.css');
		
		wp_enqueue_script('tonjoo-tom-datatables-js', $path.'/tonjoo-tom/assets/js/jquery.dataTables.js');
		wp_enqueue_script('tonjoo-tom-jquery-ui-js', $path.'/tonjoo-tom/assets/js/jquery-ui-1.10.4.custom.min.js');
		wp_enqueue_script('tonjoo-tom-reordering-js', $path.'/tonjoo-tom/assets/js/jquery.dataTables.rowReordering.js');
		wp_enqueue_script('tonjoo-tom-sheepit-js', $path.'/tonjoo-tom/assets/js/jquery.sheepItPlugin.js');
		wp_enqueue_script('tonjoo-tom-script-js', $path.'/tonjoo-tom/assets/js/script.js');
	}	
}

add_shortcode('tonjoo_tom', 'tom_shortcode');

function tom_shortcode($attr)
{
	$return = "";

	/* get group data */
	$tonjoo_tom = unserialize(get_option("tonjoo_tom"));

	if(array_key_exists($attr['group'], $tonjoo_tom))
	{
		$tonjoo_tom = $tonjoo_tom[$attr['group']]['data'];

		$arr_data = json_decode(stripslashes($tonjoo_tom), true);

		foreach ($arr_data as $n) 
		{
			if($n['1'] == $attr['name'])
			{
				$value = get_option("tonjoo_tom_data_{$attr['group']}");
				$value = $value ? unserialize($value) : false;

				$option_value = "";

				if($value && isset($value[$n['1']]))
				{
					$option_value = $value[$n['1']];
				}

				/* print options 
				   if SSL enabled use https replace function
				*/
				$return = (is_ssl()) ? https_link($option_value) : $option_value;

				break;
			}
		}
	}

	return $return;
}

// Replace url to https
function https_link($url){
	
	// Check if output from TOM is URL
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