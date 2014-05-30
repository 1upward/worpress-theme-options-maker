<?php

class tomOptions {

	protected $options_screen = null;
    protected $create_options_screen = null;

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

		// Registers the settings fields and callback
		// to save option value
		register_setting( 'tonjoo-tom', 'tom_data',  array ( $this, 'validate_options' ) );

		// To save option name
		register_setting( 'tom_options', 'tom_options' );

    }

	// Ambil option field dari file atau dari db
	static function tom_options_fields() {

		if ( file_exists( get_template_directory() . "/tonjoo_options.php" ) ) {
		    require_once( get_template_directory() . "/tonjoo_options.php" );
		} 

		if ( function_exists( 'tonjoo_tom_options' ) ) {
			$options = tonjoo_tom_options();

		} else {} // Ambil dari db

		return $options;
	}


	function enqueue_admin_styles() {

		wp_enqueue_style( 'tonjoo-tom', plugin_dir_url( dirname(__FILE__) ) . 'css/style.css', array() );
		wp_enqueue_style( 'wp-color-picker' );
	}
	
	function enqueue_admin_scripts() {
		// Enqueue custom option panel JS
		wp_enqueue_script( 'nestable', plugin_dir_url( dirname(__FILE__) ) . 'js/jquery.nestable.js', array('jquery'));
		wp_enqueue_script( 'tonjoo-script', plugin_dir_url( dirname(__FILE__) ) . 'js/script.js', array( 'jquery','wp-color-picker' ) );
		
		// wp_enqueue_script('tonjoo-tom-datatables-js', plugin_dir_url( dirname(__FILE__) ) . 'js/jquery.dataTables.js');
		// wp_enqueue_script('tonjoo-tom-jquery-ui-js', plugin_dir_url( dirname(__FILE__) ) . 'js/jquery-ui-1.10.4.custom.min.js');
		// wp_enqueue_script('tonjoo-tom-reordering-js', plugin_dir_url( dirname(__FILE__) ) . 'js/jquery.dataTables.rowReordering.js');
		// wp_enqueue_script('tonjoo-tom-sheepit-js', plugin_dir_url( dirname(__FILE__) ) . 'js/jquery.sheepItPlugin.js');
		// wp_enqueue_script('tonjoo-tom-script-js', plugin_dir_url( dirname(__FILE__) ) . 'js/script.js');
	}

	
	// Menu
	static function tom_menu_settings() {

		$menu = array(

			// Modes: submenu, menu
            'mode' => 'create_enabled',

            'page_title' => 'Theme Options',
            'page_desc' => "Customize your theme options!, you can add, edit, or delete easily here. Don't forget to save your changes or you will lose it",
			'menu_title' => 'Theme Options',
			'capability' => 'edit_theme_options',
			'menu_slug' => 'tonjoo-tom',
            'icon_url' => 'dashicons-admin-generic',
            'position' => '61',

            // for sub menu
            'parent_slug' => 'tonjoo-tom',
            'sub_page_title' => 'Theme Options Maker (TOM) Settings',
            'sub_page_desc' => "Customize your theme options!, you can add, edit, or delete easily here. Don't forget to save your changes or you will lose it",
			'sub_menu_title' => 'Create Options',
			'sub_capability' => 'manage_options',
			'sub_menu_slug' => 'create-options',

		);

		return $menu;
	}

	function add_custom_options_page() {

		$menu = $this->tom_menu_settings();

        $this->options_screen = add_menu_page(
        	$menu['page_title'],
        	$menu['menu_title'],
        	$menu['capability'],
        	$menu['menu_slug'],
        	array( $this, 'options_page' ),
        	$menu['icon_url'],
        	$menu['position']
        );

        if (@$menu['mode'] == 'create_enabled') {
        	$this->create_options_screen = add_submenu_page(
		    	$menu['parent_slug'],
		    	$menu['sub_page_title'],
		    	$menu['sub_menu_title'],
		    	$menu['sub_capability'],
		    	$menu['sub_menu_slug'],
		    	array( $this, 'create_options_page' ) );
        }
	}


	// Halaman tonjoo tom
	function options_page() { ?>

		<div class="wrap">

		<?php $menu = $this->tom_menu_settings(); ?>
		<h2><?php echo esc_html( $menu['page_title'] ); ?></h2>
		<p><?php echo esc_html( $menu['page_desc'] ); ?></p>

	    <h2 class="nav-tab-wrapper">
	        <?php echo tomGenerate::tom_tabs(); ?>
	    </h2>

	    <?php settings_errors( 'tonjoo-tom' ); ?>

	    <div id="tonjoo-tom-metabox" class="metabox-holder">
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
		</div> <!-- / .wrap -->

	<?php
	}

	function create_options_page() { ?>
		<div id="tonjoo-tom-wrap" class="wrap">

		<?php $menu = $this->tom_menu_settings(); ?>
		<h2><?php echo esc_html( $menu['sub_page_title'] ); ?></h2>
		<p><?php echo esc_html( $menu['sub_page_desc'] ); ?></p>

	    <h2 class="nav-tab-wrapper">
	        <?php echo tomGenerate::create_tom_tabs(); ?>
	    </h2>

	    <?php settings_errors( 'tonjoo-tom' ); ?>

	    <div id="tonjoo-tom-metabox" class="metabox-holder">
		    <div id="tonjoo-tom" class="postbox">
				<form action="options.php" method="post">
				<?php settings_fields( 'tom_options' ); ?>
				<?php tomGenerate::generate_create_options_fields(); /* Settings */ ?>
				<div id="tonjoo-tom-submit">
					<input type="submit" class="button-primary" name="update" value="Save" />
					<input type="submit" class="reset-button button-secondary" name="reset" value="Reset" onclick="return confirm( '<?php print esc_js('Click OK to reset. Any theme settings will be lost!'); ?>' );" />
					<div class="clear"></div>
				</div>
				</form>
			</div> <!-- / #container -->
		</div>
		</div> <!-- / .wrap -->
	<?php
	}

	

	function get_default_values() {
		$output = array();
		$config = $this->tom_options_fields();
		foreach ( (array) $config as $option ) {
			if ( ! isset( $option['id'] ) ) {
				continue;
			}
			if ( ! isset( $option['std'] ) ) {
				continue;
			}
			if ( ! isset( $option['type'] ) ) {
				continue;
			}
		}
		return $output;
	}

	function validate_options( $input ) {

		if ( isset( $_POST['reset'] ) ) {
			add_settings_error( 'tonjoo-tom', 'restore_defaults', 'Default options restored.', 'updated fade' );
			return $this->get_default_values();
		}

		/*
		 * Update Settings
		 *
		 * This used to check for $_POST['update'], but has been updated
		 * to be compatible with the theme customizer introduced in WordPress 3.4
		 */

		$clean = array();
		$options = $this->tom_options_fields();
		foreach ( $options as $option ) {

			if ( ! isset( $option['id'] ) ) {
				continue;
			}

			if ( ! isset( $option['type'] ) ) {
				continue;
			}

			$id = preg_replace( '/[^a-zA-Z0-9._\-]/', '', strtolower( $option['id'] ) );

			// Set checkbox to false if it wasn't sent in the $_POST
			if ( 'checkbox' == $option['type'] && ! isset( $input[$id] ) ) {
				$input[$id] = false;
			}

			// Set each item in the multicheck to false if it wasn't sent in the $_POST
			if ( 'multicheck' == $option['type'] && ! isset( $input[$id] ) ) {
				foreach ( $option['options'] as $key => $value ) {
					$input[$id][$key] = false;
				}
			}

			// For a value to be submitted to database it must pass through a sanitization filter
			if ( has_filter( 'of_sanitize_' . $option['type'] ) ) {
				$clean[$id] = apply_filters( 'of_sanitize_' . $option['type'], $input[$id], $option );
			}
		}

		// Hook to run after validation
		do_action( 'tonjoo-tom_after_validate', $clean );

		return $clean;
	}

	/**
	 * Display message when options have been saved
	 */

	function save_options_notice() {
		add_settings_error( 'options-framework', 'save_options', 'Options saved.', 'updated fade' );
	}

}