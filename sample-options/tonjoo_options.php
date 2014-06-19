<?php

function tonjoo_tom_config($config) {

#	$config['mode'] 			= 'lite';
#   $config['page_title'] 		= 'Your Theme Option';
#   $config['page_desc'] 		= 'Your custom descriptions';
#	$config['menu_title'] 		= 'Your Custom Title';
#	$config['capability'] 		= 'edit_theme_options';
#	$config['menu_slug'] 		= '';
#   $config['icon_url']			= 'dashicons-editor-paste-text';
#   $config['position'] 		= '61';
#   $config['sub_page_title'] 	= 'Your Sub Page Title';
#   $config['sub_page_desc'] 	= 'Your custom descriptions';
#	$config['sub_menu_title'] 	= 'Your Sub Page Title';
#	$config['sub_capability'] 	= '';
#	$config['sub_menu_slug'] 	= '';


	return $config;
}

function tonjoo_tom_options() {

	// Test data
	$test_array = array(
		'satu'	=> 'Satu',
		'dua' 	=> 'Dua',
		'tiga' 	=> 'Tiga',
		'empat' => 'Empat',
		'lima' 	=> 'Lima',
	);

	
	$options_categories = array();
	$options_categories_obj = get_categories();
	foreach ($options_categories_obj as $category) {
		$options_categories[$category->cat_ID] = $category->cat_name;
	}

	$options_tags = array();
	$options_tags_obj = get_tags();
	foreach ( $options_tags_obj as $tag ) {
		$options_tags[$tag->term_id] = $tag->name;
	}

	$options_pages = array();
	$options_pages_obj = get_pages('sort_column=post_parent,menu_order');
	$options_pages[''] = 'Select a page:';
	foreach ($options_pages_obj as $page) {
		$options_pages[$page->ID] = $page->post_title;
	}

	$imagepath =  get_template_directory_uri() . '/images/';

	$options = array();

	/**********
	Tab Homepage
	***********/
	$options['homepage'] = array(
		'name' => 'Homepage',
		'type' => 'heading',
		'desc' => 'Test description');
	
	$options['sample-text'] = array(
		'name' => 'Input Text',
		'desc' => 'Sample input text',
		'default' => 'Default Value',
		'type' => 'text');

	$options['sample-textarea'] = array(
		'name' => 'Textarea',
		'desc' => 'Sample textarea',
		'default' => 'Default Text',
		'type' => 'textarea');
	
	$options['sample-select-db'] = array(
		'name' => 'Input Select From DB',
		'desc' => 'Sample Select',
		'default' => 'dua',
		'type' => 'select',
		'options' => $test_array);

	$options['sample-select-page'] = array(
		'name' => 'Select Page',
		'desc' => 'Sample select page',
		'type' => 'select',
		'options' => $options_pages);

	$options['sample-select-cat'] = array(
		'name' => 'Select Category',
		'desc' => 'Sample select category',
		'type' => 'select',
		'options' => $options_categories);

	if ( $options_tags ) {
	$options['sample-select-tag'] = array(
		'name' => 'Select Tag',
		'desc' => 'Sample select tag',
		'type' => 'select',
		'options' => $options_tags);
	}

	$options['sample-radio'] = array(
		'name' => 'Input Radio',
		'desc' => 'Sample input radio',
		'default' => 'dua',
		'type' => 'radio',
		'options' => $test_array);

	$options['sample-checkbox'] = array(
		'name' => 'Input Checkbox',
		'desc' => 'Sample input checkbox',
		'default' => 'true',
		'type' => 'checkbox');


	/**********
	Tab About
	***********/
	 $options['about'] = array(
	 	'name' => 'About',
	 	'type' => 'heading');

	 $options['sample-upload'] = array(
	 	'name' => 'Image',
	 	'desc' => 'Sample image',
	 	'type' => 'upload');

	 $options['sample-select-image'] = array(
	 	'name' => "Example Image Select",
	 	'desc' => "Images for layout.",
	 	'default' => "2",
	 	'type' => "select-image",
	 	'options' => array(
	 		'1' => $imagepath . '1col.png',
	 		'2' => $imagepath . '2cl.png',
	 		'3' => $imagepath . '2cr.png')
	 );

	 $options['sample-multicheck'] = array(
	 	'name' => 'Multicheck',
	 	'desc' => 'Sample multicheck',
	 	'default' => '', // These items get checked by default
	 	'type' => 'multicheck',
	 	'options' => $test_array);

	 $options['sample-color'] = array(
	 	'name' => 'Color picker',
	 	'desc' => 'Sample color picker',
	 	'default' => '',
	 	'type' => 'color' );

	 // /* text */
	 $options['sample-text-tab'] = array(
	 	'name' => 'Sample Text Editor',
	 	'type' => 'heading');


	 $editor_settings = array(
	 	'wpautop' => true, // Default
	 	'textarea_rows' => 5,
	 	'group' => '3',
	 	'tinymce' => array( 'plugins' => 'wordpress' )
	 );

	 $options['sample-text-editor'] = array(
	 	'name' => 'Text editor',
	 	'desc' => 'Sample text editor',
	 	'type' => 'editor',
	 	'settings' => $editor_settings );

	 $with_media = array(
	 	'wpautop' => true, // Default
	 	'textarea_rows' => 5,
	 	'group' => '3',
	 	'media_buttons' => true
	 );

	 $options['sample-editor-media'] = array(
	 	'name' => 'Text editor with image',
	 	'desc' => 'Sample text editor with media button enabled',
	 	'type' => 'editor',
	 	'settings' => $with_media );

	 /* Typography Defaults */
	 $typography_defaults = array(
	 	'size' => '15px',
	 	'face' => 'georgia',
	 	'style' => 'bold',
	 	'color' => '#bada55' );

	 /*Typography Options*/
	 $typography_options = array(
	 	'sizes' => array( '6','12','14','16','20' ),
	 	'faces' => array( 'Helvetica Neue' => 'Helvetica Neue','Arial' => 'Arial' ),
	 	'styles' => array( 'normal' => 'Normal','bold' => 'Bold' ),
	 	'color' => false
	 );

	 $options['sample-typography'] = array( 
	 	'name' => 'Typography',
	 	'desc' => 'Sample typography',
	 	// 'default' => $typography_defaults,
	 	'type' => 'typography' );

	 // $options[] = array(
	 // 	'name' => 'Custom Typography',
	 // 	'desc' => 'Sample Custom Typography',
	 // 	'id' => "custom_typography",
	 // 	'default' => $typography_defaults,
	 // 	'type' => 'typography',
	 // 	'options' => $typography_options );

	return $options;
}