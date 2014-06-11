<?php

class tomOptions {

	public function init() {

		$this->tom_options_fields();

		// setting
		add_action( 'admin_init', array( $this, 'tom_settings_init' ) );

		// Add the required scripts and styles
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_admin_styles' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_admin_scripts' ) );

		add_action( 'admin_menu', array( $this, 'add_custom_options_page' ) );

	}

	function tom_settings_init() {

		/* Register setting untuk menyimpan data */
		register_setting( 'tonjoo-tom', 'tom_data', array ( $this, 'validate_options' ) );

		/* Register setting untuk menyimpan options / create options */
		register_setting( 'tom_options', 'tom_options', array ( $this, 'validate_create_options' ) );
		// add_settings_section( '', $title, $callback, $page );

		/* Jika ada file config di folder theme */
		// if ( file_exists( get_template_directory() . "/tonjoo_options.php" ) ) {
		//     require_once( get_template_directory() . "/tonjoo_options.php" );
		// } 

		/* masukkan config ke dalam filter jika ada */
		// if ( function_exists('tonjoo_tom_config') ) {
		// 	add_filter( 'tom_config', 'tonjoo_tom_config');
		// }


    }

    public function validate_create_options( $input ) {
  //   	echo "<pre>";
  //   	print_r($input);
  //   	echo "</pre>";
		// exit();

		// if ( ! isset( $input['reset_theme'] ) ) {
			
			
		// 	return $input;
		// }
		return $input;
		
	}

	/* get all option from file or from database */
	static function tom_options_fields() {

		// if ( file_exists( get_template_directory() . "/tonjoo_options.php" ) ) {
			
		//     require_once( get_template_directory() . "/tonjoo_options.php" );
		// } 

		

		// if ( function_exists( 'tonjoo_tom_options' ) ) {
		// 	$options = tonjoo_tom_options();
		// 	// debug
		// 	// echo "dari file";

		// } else { // Ambil dari db
		// 	$options = unserialize(get_option( 'tom_options' ));
		// 	// debug
		// 	// echo "dari database";
		// } 
		if ( !empty( get_option( 'tom_options' ))) {
			$options_from_db = get_option( 'tom_options' );
		} else {
			$options_from_db = array();
		}
		$options_from_file = apply_filters( 'tom_options', $options_from_db );

		$options = array_merge($options_from_db, $options_from_file);
		
		// echo "<pre>";
		// print_r($options_from_db); exit();
		// echo "</pre>";
		return $options;
	}


	function enqueue_admin_styles() {

		wp_enqueue_style( 'tonjoo-tom', plugin_dir_url( dirname(__FILE__) ) . 'assets/css/style.css', array() );
		wp_enqueue_style( 'wp-color-picker' );
	}
	
	function enqueue_admin_scripts() {
		// Enqueue custom option panel JS
		wp_enqueue_script( 'nestable', plugin_dir_url( dirname(__FILE__) ) . 'assets/js/jquery.nestable.js', array('jquery'));
		wp_enqueue_script( 'tonjoo-script', plugin_dir_url( dirname(__FILE__) ) . 'assets/js/script.js', array( 'jquery','wp-color-picker' ) );
		
		// wp_enqueue_script('tonjoo-tom-datatables-js', plugin_dir_url( dirname(__FILE__) ) . 'js/jquery.dataTables.js');
		// wp_enqueue_script('tonjoo-tom-jquery-ui-js', plugin_dir_url( dirname(__FILE__) ) . 'js/jquery-ui-1.10.4.custom.min.js');
		// wp_enqueue_script('tonjoo-tom-reordering-js', plugin_dir_url( dirname(__FILE__) ) . 'js/jquery.dataTables.rowReordering.js');
		// wp_enqueue_script('tonjoo-tom-sheepit-js', plugin_dir_url( dirname(__FILE__) ) . 'js/jquery.sheepItPlugin.js');
		// wp_enqueue_script('tonjoo-tom-script-js', plugin_dir_url( dirname(__FILE__) ) . 'js/script.js');
	}

	
	// Menu
	static function tom_configs() {

		$config_default = array(

			/* Default cofigurations */
            'mode' => 'full',

            'page_title' => 'Theme Options',
            'page_desc' => "Customize your theme options!, you can add, edit, or delete easily here. Don't forget to save your changes or you will lose it",
			'menu_title' => 'Theme Options',
			'capability' => 'edit_theme_options',
			'menu_slug' => 'tonjoo-tom',
            'icon_url' => 'dashicons-editor-paste-text',
            'position' => '61',

            /* for sub menu */
            'parent_slug' => 'tonjoo-tom',
            'sub_page_title' => 'Theme Options Maker (TOM) Settings',
            'sub_page_desc' => "Customize your theme options!, you can add, edit, or delete easily here. Don't forget to save your changes or you will lose it",
			'sub_menu_title' => 'Create Options',
			'sub_capability' => 'manage_options',
			'sub_menu_slug' => 'create-options',
			'type-options' => array(
								'text' => 'Text',
								'textarea' => 'Textarea',
								'select' => 'Select',
								'radio' => 'Radio',
								'checkbox' => 'Checkbox',
								'multicheck' => 'Multicheck',
								'upload' => 'Image Upload',
								'select-image' => 'Image Select',
								'color' => 'Color Picker',
								'editor' => 'Text Editor',
								)

		);

		/* ambil dari filter barangkali ada custom config dari file */
		$config_from_file = apply_filters( 'tom_config', $config_default );
		$configs = array_merge($config_default, $config_from_file);
		
		return $configs;
	}

	function add_custom_options_page() {

		$config = $this->tom_configs();
		// echo "<pre>";
		// print_r($config); exit();
		// echo "</pre>";
        add_menu_page(
        	$config['page_title'],
        	$config['menu_title'],
        	$config['capability'],
        	$config['menu_slug'],
        	array( $this, 'options_page' ),
        	$config['icon_url'],
        	$config['position']
        );

        if ($config['mode'] == 'full') {
        	add_submenu_page(
		    	$config['parent_slug'],
		    	$config['sub_page_title'],
		    	$config['sub_menu_title'],
		    	$config['sub_capability'],
		    	$config['sub_menu_slug'],
		    	array( $this, 'create_options_page' ) );
        }
	}


	// Halaman tonjoo tom
	function options_page() { ?>

		<div  class="wrap">

		<?php $config = $this->tom_configs(); ?>
		<h2><?php echo esc_html( $config['page_title'] ); ?></h2>
		<p><?php echo esc_html( $config['page_desc'] ); ?></p>

	    <h2 class="nav-tab-wrapper">
	        <?php echo tomGenerate::tom_tabs(); ?>
	    </h2>

	    <?php settings_errors( 'tonjoo-tom' ); ?>

	    <div id="tonjoo-tom-metabox" class="metabox-holder metabox-main">
		    <div id="tonjoo-tom" class="postbox">
				<form action="options.php" method="post">
				<?php settings_fields( 'tonjoo-tom' ); ?>
				<?php tomGenerate::generate_options_fields(); /* Settings */ ?>
				<div id="tonjoo-tom-submit">
					<input type="submit" class="button-primary" name="update" value="Save" />
					<input type="submit" class="reset-button button-secondary" name="reset" value="Reset" onclick="return confirm( '<?php print esc_js('Click OK to reset. Any theme settings will be lost!'); ?>' );" />
					<div class="clear"></div>
				</div>
				</form>
			</div> <!-- / #container -->
		</div>
		<div class="metabox-holder metabox-side">
		  <div class="form-wrap postbox">
		    <h3>
		      Add New Option
		    </h3>
		 	
		  </div>
		</div>
		</div> <!-- / .wrap -->

	<?php
	}

	function create_options_page() { ?>
	<div id="tonjoo-tom-wrap" class="wrap">

		<?php $config = $this->tom_configs(); ?>
		<h2><?php echo esc_html( $config['sub_page_title'] ); ?></h2>
		<p><?php echo esc_html( $config['sub_page_desc'] ); ?></p>

	    <h2 class="nav-tab-wrapper">
	        <?php echo tomGenerate::create_tom_tabs(); ?>
	    </h2>

	    <?php settings_errors( 'tonjoo-tom' ); ?>

	    <div id="tonjoo-tom-metabox" class="metabox-holder metabox-main">
		    <div id="tonjoo-tom" class="postbox">
				<form action="options.php" method="post">
				<?php settings_fields( 'tom_options' ); ?>
				<?php tomGenerate::generate_create_options_fields(); /* Settings */ ?>
				<div id="tonjoo-tom-submit">
					<input type="submit" class="button-primary" name="update" value="Save" />
					<a class="reset-button button-secondary" onclick="return confirm( '<?php print esc_js('Are you sure to delete option group?'); ?>' );">Delete Group</a>
					<div class="clear"></div>
				</div>
				</form>
			</div> <!-- / #container -->
		</div>
		<div class="metabox-holder metabox-side">
		  	<div class="form-wrap postbox">
			    <h3>
			      Add New Option
			    </h3>
			    <div id="add-tom-options">
			    	<label for="tom-id">
			          Option ID :
			        </label>
			        <div class="input">
				        <input id="tom-id" type="text" value="">
				        <p>
				          Option ID (use for key and shortcode).
				        </p>
			        </div>
			      	<label for="tom-name">
			          Name :
			        </label>
			        <div class="input">
			        <input name="name" id="tom-name" type="text" value="">
			        <div class="input">
				        <p>
				          The name of option.
				        </p>
			        </div>
			        <label for="tom-desc">
			          Desription :
			        </label>
			        <div class="input">
				        <textarea name="desc" id="tom-desc"></textarea>
				        <p>
				          Short description of option.
				        </p>
			        </div>
			        <label for="tom-type">
			          Type :
			        </label>
			        <div class="input">
			        <?php $config = $this->tom_configs(); ?>
				        <select name="type" id="tom-type">
			        	<?php  
			        		foreach ($config['type-options'] as $value => $name) {
			        			echo '<option value="'.$value.'">'.$name.'</option>';
			        		}
			        	?>
				        </select>
				        <p>
				          Type of option.
				        </p>
			        </div>
			        <div id="options-container" style="display:none;">
			        	<label for="tom-options">
				          Options :
				        </label>
				        <form id="repeatable-form">
					        <div id="tom-options" class="input-options">
						        <p id="tom-input-repeatable-0"><input id="key-1" class="input-key" name="rep_key_0" type="text" value=""> => <input id="val-1" class="input-val" name="rep_value_0" type="text" value=""></p>
					        </div>
				        </form>
				        <p><a id="new-repeatable" href="#">Add New Field</a></p>
			        </div>
			      	<label for="tom-default">
			          Default :
			        </label>
			        <div class="input">
				        <input name="default" id="tom-default" type="text" value="">
				        <p>
				          Default value.
				        </p>
			        </div>
			    </div>
		  	</div>
		<!-- </div> -->
			<div id="tonjoo-tom-submit">
				<a id="tom-add-options" class="button-primary">Add Option</a>
				<div class="clear"></div>
			</div>
	</div>
	</div>
	<?php
	}

	

	function get_default_values() {
		$output = array();
		$config = $this->tom_options_fields();
		foreach ( (array) $config as $option ) {
			if ( ! isset( $option['id'] ) ) {
				continue;
			}
			if ( ! isset( $option['default'] ) ) {
				continue;
			}
			if ( ! isset( $option['type'] ) ) {
				continue;
			}
		}

		// echo "<pre>";
		// print_r($config); exit();
		// echo "</pre>";
		return $output;
	}

	function validate_options( $input ) {
		// echo "<pre>";
		// print_r($input); 
		// echo "</pre>";
		// exit();

		if ( isset( $_POST['reset'] ) ) {
			add_settings_error( 'tonjoo-tom', 'restore_defaults', 'Default options restored.', 'updated fade' );
			return $this->get_default_values();
		}

		// $clean = array();
		// $options = $this->tom_options_fields();
		// foreach ( $options as $option ) {

		// 	if ( ! isset( $option['id'] ) ) {
		// 		continue;
		// 	}

		// 	if ( ! isset( $option['type'] ) ) {
		// 		continue;
		// 	}

		// 	$id = preg_replace( '/[^a-zA-Z0-9._\-]/', '', strtolower( $option['id'] ) );

		// 	// Set checkbox to false if it wasn't sent in the $_POST
		// 	if ( 'checkbox' == $option['type'] && ! isset( $input[$id] ) ) {
		// 		$input[$id] = false;
		// 	}

		// 	// Set each item in the multicheck to false if it wasn't sent in the $_POST
		// 	if ( 'multicheck' == $option['type'] && ! isset( $input[$id] ) ) {
		// 		foreach ( $option['options'] as $key => $value ) {
		// 			$input[$id][$key] = false;
		// 		}
		// 	}

		// 	if ( has_filter( 'tom_sanitize_' . $option['type'] ) ) {
		// 		$clean[$id] = apply_filters( 'tom_sanitize_' . $option['type'], $input[$id], $option );
		// 	}

		// }

		add_settings_error( 'tonjoo-tom', 'save_options', 'Options saved.', 'updated fade' );

		return $input;
	}

}