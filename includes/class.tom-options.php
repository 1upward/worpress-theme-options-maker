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
		register_setting( 'tonjoo-tom', 'tom_data' );

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

		wp_enqueue_style( 'optionsframework', plugin_dir_url( dirname(__FILE__) ) . 'css/style.css', array() );
		wp_enqueue_style( 'wp-color-picker' );
	}
	
	function enqueue_admin_scripts() {
		// Enqueue custom option panel JS
		wp_enqueue_script( 'options-custom', plugin_dir_url( dirname(__FILE__) ) . 'js/options-custom.js', array( 'jquery','wp-color-picker' ) );

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
			'menu_title' => 'Theme Options',
			'capability' => 'edit_theme_options',
			'menu_slug' => 'tonjoo-tom',
            'icon_url' => 'dashicons-admin-generic',
            'position' => '61',

            // for sub menu
            'parent_slug' => 'tonjoo-tom',
            'sub_page_title' => 'Create Options',
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

	    <h2 class="nav-tab-wrapper">
	        <?php echo tomGenerate::tom_tabs(); ?>
	    </h2>

	    <?php settings_errors( 'tonjoo-tom' ); ?>

	    <div id="optionsframework-metabox" class="metabox-holder">
		    <div id="optionsframework" class="postbox">
				<form action="options.php" method="post">
				<?php settings_fields( 'tonjoo-tom' ); ?>
				<?php tomGenerate::generate_options_fields(); /* Settings */ ?>
				<div id="optionsframework-submit">
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
		<div id="optionsframework-wrap" class="wrap">

		<?php $menu = $this->tom_menu_settings(); ?>
		<h2><?php echo esc_html( $menu['page_title'] ); ?></h2>

	    <h2 class="nav-tab-wrapper">
	        <?php echo tomGenerate::create_tom_tabs(); ?>
	    </h2>

	    <?php settings_errors( 'tonjoo-tom' ); ?>

	    <div id="optionsframework-metabox" class="metabox-holder">
		    <div id="optionsframework" class="postbox">
				<form action="options.php" method="post">
				<?php settings_fields( 'optionsframework_create_options' ); ?>
				<?php tomGenerate::generate_create_options_fields(); /* Settings */ ?>
				<div id="optionsframework-submit">
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

}