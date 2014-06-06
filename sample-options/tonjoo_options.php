<?php

function tonjoo_tom_config($config) {

#	$config['mode'] 			= 'full';
#   $config['page_title'] 		= '';
#   $config['page_desc'] 		= '';
#	$config['menu_title'] 		= 'Test';
#	$config['capability'] 		= '';
#	$config['menu_slug'] 		= '';
#   $config['icon_url']			= '';  /* Please refer to http://melchoyce.github.io/dashicons/ *
#   $config['position'] 		= '';
#   $config['parent_slug'] 		= '';
#   $config['sub_page_title'] 	= '';
#   $config['sub_page_desc'] 	= '';
#	$config['sub_menu_title'] 	= '';
#	$config['sub_capability'] 	= '';
#	$config['sub_menu_slug'] 	= '';

	return $config;
}

function tonjoo_tom_options() {

	$options = array();

	/**********
	Tab Homepage
	***********/
	$options['homepage'] = array(
		'name' => 'Homepage',
		'type' => 'heading',
		'desc' => 'Test description',
		'group' => '1');

	$options['example_text'] = array(
		'name' => 'Input Text',
		'desc' => 'Sample input text',
		'id' => 'example_text',
		'std' => 'Default Value',
		'group' => '1',
		'type' => 'text');

	/* Nyoba bypass config dari db */

	// $options['header_from_db'] = array(
	// 	'name' => 'From Database 2',
	// 	'type' => 'heading',
	// 	'desc' => 'Test description',
	// 	'group' => '1');

	return $options;
}