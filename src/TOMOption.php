<?php

namespace Tonjoo\TOM;

class TOMOption
{
	public $options; 

	public function __construct($container){		

		/* If file config exist */
		if ( file_exists( get_template_directory() . "/tonjoo_options.php" ) ) {
		    require_once( get_template_directory() . "/tonjoo_options.php" );
			
			/* Insert value through filter */
			if ( function_exists('tonjoo_tom_config') ) {
				add_filter( 'tom_config', 'tonjoo_tom_config');
			}

			if ( function_exists('tonjoo_tom_default') ) {
				add_filter( 'tom_default', 'tonjoo_tom_default');
			}

			if ( function_exists( 'tonjoo_tom_options' ) ) {
				add_filter( 'tom_options', array($this, 'add_options_from_file' ), 10, 1);
			}
		} 
		
		$this->tom_options_fields();
		// add_action( 'admin_init', array( $this, 'tom_settings_init' ) );

		$page = (isset($_GET['page'])) ? $_GET['page'] : ''; 
		$config = $this->tom_configs();

		if ($page == $config['menu_slug'] || $page == $config['sub_menu_slug']) {
			/* Load Styles and Scripts */
			add_action( 'admin_enqueue_scripts', array( $this, 'tom_enqueue_admin_styles' ) );
			add_action( 'admin_enqueue_scripts', array( $this, 'tom_enqueue_admin_scripts' ) );
		}

		// add_action( 'admin_menu', array( $this, 'tom_admin_page' ) );

		/* Ajax */
		add_action( 'wp_ajax_tom_options', array( $this, 'tom_options_callback' ) );
	}

	function add_options_from_file($options){
		$options = array_merge(tonjoo_tom_options($options), $options);

		return $options;
	}

	function tom_options_callback() {
		global $wpdb;
		$optionsId = $_POST['options'];
		$id = $_POST['id'];

		/* parse form data */
		$formData = array();
 		parse_str($_POST['form_data'], $formData);

		update_option( $optionsId, $formData['tom_options'] );
		
		$data = get_option( 'tom_options' );

		$data = array(
			'data' => $data[$id] , 
			'message' => '<div id="setting-error-save_options" class="updated fade settings-error below-h2"> 
							<p><strong>Options saved.</strong></p></div>'
			);
		echo json_encode($data);
		die();
	}

	function tom_enqueue_admin_styles() {

		wp_enqueue_style( 'tonjoo-tom', TOM_ABS_URL . 'assets/css/style.css', array() );
		wp_enqueue_style( 'font-awesome', TOM_ABS_URL . 'assets/css/font-awesome.min.css', array(), '4.2.0' );
		wp_enqueue_style( 'wp-color-picker' );
	}
	
	function tom_enqueue_admin_scripts() {
			// Enqueue custom option panel JS
			wp_enqueue_script( 'nestable', TOM_ABS_URL . 'assets/js/jquery.nestable.js', array('jquery'));
			wp_enqueue_script( 'zclip', TOM_ABS_URL . 'assets/js/ZeroClipboard.min.js', array( 'jquery' ) );
			wp_enqueue_script( 'font-awesome', TOM_ABS_URL . 'assets/js/ZeroClipboard.min.js', array( 'jquery' ) );
			wp_enqueue_script( 'tonjoo-script', TOM_ABS_URL . 'assets/js/script.js', array( 'jquery','wp-color-picker' ) );
			
			/* Media Uploader */
			if(function_exists('wp_enqueue_media')) {
	            wp_enqueue_media();
	        } else { /* If user use old wordpress */
	            wp_enqueue_script('media-upload');
	            wp_enqueue_script('thickbox');
	            wp_enqueue_style('thickbox');
	        }
			
			/* Custom variable TTOM */
			$config = $this->tom_configs();
			$dir = TOM_ABS_URL;
			echo '<script type="text/javascript">
					var tomMode = "'.$config['mode'].'",
						tomCreatePage = "' . get_admin_url( null, 'admin.php?page=' . $config['sub_menu_slug'] ) .'",
						pluginDir = "' . $dir .'",
						tomAdsEnabled = "' . $config['ads_enabled'] . '",
						tomAdsEndpoint = "' . $config['ads_endpoint'] . '",
						adminUrl = "' . get_admin_url() . '";
				  </script>';
	}

	function tom_configs() {

		$config_default = array(

			/* Default cofigurations */
            'mode' => 'full',

            'page_title' => 'Tonjoo Theme Options Maker (TTOM)',
            'page_desc' => "Customize your theme options!, you can add, edit, or delete easily here. Don't forget to save your changes or you will lose it",
            'page_manual' => '<a href="https://forum.tonjoo.com/thread-category/tonjoo-tom/" target="_blank">Support Forum</a> |<a href="https://tonjoo.com/addons/tonjoo-tom/#manual" target="_blank">Read documentations</a> |<a href="http://wordpress.org/support/view/plugin-reviews/tonjoo-theme-options-maker?rate=5#postform" target="_blank" style="margin-left:10px;">Enjoy with the plugin?, rate us!</a>',
			'menu_title' => 'TTOM Options',
			'capability' => 'edit_theme_options',
			'menu_slug' => 'tonjoo-tom',
            'icon_url' => 'dashicons-editor-paste-text',
            'position' => '61',

            /* for sub menu */
            'sub_page_title' => 'Tonjoo Theme Options Maker (TTOM) Settings',
            'sub_page_desc' => "Customize your theme options!, you can add, edit, or delete easily here. Don't forget to save your changes or you will lose it",
            'sub_page_manual' => '<a href="https://forum.tonjoo.com/thread-category/tonjoo-tom/" target="_blank">Support Forum</a> <a href="https://tonjoo.com/addons/tonjoo-tom/#manual" target="_blank">Read documentations</a> |<a href="http://wordpress.org/support/view/plugin-reviews/tonjoo-theme-options-maker?rate=5#postform" target="_blank" style="margin-left:10px;">Enjoy with the plugin?, rate us!</a>',
			'sub_menu_title' => 'Create Options',
			'sub_capability' => 'manage_options',
			'sub_menu_slug' => 'create-options',
			'type-options' => array(
								'text' => 'Text',
								'url' => 'URL',
								'number' => 'Number',
								'textarea' => 'Textarea',
								'select' => 'Select',
								'radio' => 'Radio',
								'checkbox' => 'Checkbox',
								'multicheck' => 'Multicheck',
								'upload' => 'Image Upload',
								'select-image' => 'Image Select',
								'select-icon' => 'Select Icon',
								'color' => 'Color Picker',
								'editor' => 'Text Editor',
								'typography' => 'Typography'
								),
			'ads_enabled' => false,
			'ads_title' => '',
			'ads_endpoint' => '',
		);

		/* Get configurations from file if exist */
		$config_from_file = apply_filters( 'tom_config', $config_default );
		$configs = array_merge($config_default, $config_from_file);
		
		return $configs;
	}

	function tom_default_options() {

		$opt_default = array(
			'font-size' => range( 9, 71 ),
			'font-face' => array(
				'arial'     => 'Arial',
				'verdana'   => 'Verdana, Geneva',
				'trebuchet' => 'Trebuchet',
				'georgia'   => 'Georgia',
				'times'     => 'Times New Roman',
				'tahoma'    => 'Tahoma, Geneva',
				'palatino'  => 'Palatino',
				'helvetica' => 'Helvetica'
			),
			'font-style' => array(
				'normal'      => 'Normal',
				'italic'      => 'Italic',
				'bold'        => 'Bold',
				'bold italic' => 'Bold Italic',
			),
			'editor-settings' => array(
				'media_buttons' => true,
				'textarea_rows' => 5,
				'tinymce' => array( 'plugins' => 'wordpress' )
			),
			'icon' => array(
				'icon-adjust' => 'icon-adjust',
				'icon-asterisk' => 'icon-asterisk',
				'icon-ban-circle' => 'icon-ban-circle',
				'icon-bar-chart' => 'icon-bar-chart',
				'icon-barcode' => 'icon-barcode',
				'icon-beaker' => 'icon-beaker',
				'icon-beer' => 'icon-beer',
				'icon-bell' => 'icon-bell',
				'icon-bell-alt' => 'icon-bell-alt',
				'icon-bolt' => 'icon-bolt',
				'icon-book' => 'icon-book',
				'icon-bookmark' => 'icon-bookmark',
				'icon-bookmark-empty' => 'icon-bookmark-empty',
				'icon-briefcase' => 'icon-briefcase',
				'icon-bullhorn' => 'icon-bullhorn',
				'icon-calendar' => 'icon-calendar',
				'icon-camera' => 'icon-camera',
				'icon-camera-retro' => 'icon-camera-retro',
				'icon-certificate' => 'icon-certificate',
				'icon-check' => 'icon-check',
				'icon-check-empty' => 'icon-check-empty',
				'icon-circle' => 'icon-circle',
				'icon-circle-blank' => 'icon-circle-blank',
				'icon-cloud' => 'icon-cloud',
				'icon-cloud-download' => 'icon-cloud-download',
				'icon-cloud-upload' => 'icon-cloud-upload',
				'icon-coffee' => 'icon-coffee',
				'icon-cog' => 'icon-cog',
				'icon-cogs' => 'icon-cogs',
				'icon-comment' => 'icon-comment',
				'icon-comment-alt' => 'icon-comment-alt',
				'icon-comments' => 'icon-comments',
				'icon-comments-alt' => 'icon-comments-alt',
				'icon-credit-card' => 'icon-credit-card',
				'icon-dashboard' => 'icon-dashboard',
				'icon-desktop' => 'icon-desktop',
				'icon-download' => 'icon-download',
				'icon-download-alt' => 'icon-download-alt',
				'icon-edit' => 'icon-edit',
				'icon-envelope' => 'icon-envelope',
				'icon-envelope-alt' => 'icon-envelope-alt',
				'icon-exchange' => 'icon-exchange',
				'icon-exclamation-sign' => 'icon-exclamation-sign',
				'icon-external-link' => 'icon-external-link',
				'icon-eye-close' => 'icon-eye-close',
				'icon-eye-open' => 'icon-eye-open',
				'icon-facetime-video' => 'icon-facetime-video',
				'icon-fighter-jet' => 'icon-fighter-jet',
				'icon-film' => 'icon-film',
				'icon-filter' => 'icon-filter',
				'icon-fire' => 'icon-fire',
				'icon-flag' => 'icon-flag',
				'icon-folder-close' => 'icon-folder-close',
				'icon-folder-open' => 'icon-folder-open',
				'icon-folder-close-alt' => 'icon-folder-close-alt',
				'icon-folder-open-alt' => 'icon-folder-open-alt',
				'icon-food' => 'icon-food',
				'icon-gift' => 'icon-gift',
				'icon-glass' => 'icon-glass',
				'icon-globe' => 'icon-globe',
				'icon-group' => 'icon-group',
				'icon-hdd' => 'icon-hdd',
				'icon-headphones' => 'icon-headphones',
				'icon-heart' => 'icon-heart',
				'icon-heart-empty' => 'icon-heart-empty',
				'icon-home' => 'icon-home',
				'icon-inbox' => 'icon-inbox',
				'icon-info-sign' => 'icon-info-sign',
				'icon-key' => 'icon-key',
				'icon-leaf' => 'icon-leaf',
				'icon-laptop' => 'icon-laptop',
				'icon-legal' => 'icon-legal',
				'icon-lemon' => 'icon-lemon',
				'icon-lightbulb' => 'icon-lightbulb',
				'icon-lock' => 'icon-lock',
				'icon-unlock' => 'icon-unlock',
				'icon-magic' => 'icon-magic',
				'icon-magnet' => 'icon-magnet',
				'icon-map-marker' => 'icon-map-marker',
				'icon-minus' => 'icon-minus',
				'icon-minus-sign' => 'icon-minus-sign',
				'icon-mobile-phone' => 'icon-mobile-phone',
				'icon-money' => 'icon-money',
				'icon-move' => 'icon-move',
				'icon-music' => 'icon-music',
				'icon-off' => 'icon-off',
				'icon-ok' => 'icon-ok',
				'icon-ok-circle' => 'icon-ok-circle',
				'icon-ok-sign' => 'icon-ok-sign',
				'icon-pencil' => 'icon-pencil',
				'icon-picture' => 'icon-picture',
				'icon-plane' => 'icon-plane',
				'icon-plus' => 'icon-plus',
				'icon-plus-sign' => 'icon-plus-sign',
				'icon-print' => 'icon-print',
				'icon-pushpin' => 'icon-pushpin',
				'icon-qrcode' => 'icon-qrcode',
				'icon-question-sign' => 'icon-question-sign',
				'icon-quote-left' => 'icon-quote-left',
				'icon-quote-right' => 'icon-quote-right',
				'icon-random' => 'icon-random',
				'icon-refresh' => 'icon-refresh',
				'icon-remove' => 'icon-remove',
				'icon-remove-circle' => 'icon-remove-circle',
				'icon-remove-sign' => 'icon-remove-sign',
				'icon-reorder' => 'icon-reorder',
				'icon-reply' => 'icon-reply',
				'icon-resize-horizontal' => 'icon-resize-horizontal',
				'icon-resize-vertical' => 'icon-resize-vertical',
				'icon-retweet' => 'icon-retweet',
				'icon-road' => 'icon-road',
				'icon-rss' => 'icon-rss',
				'icon-screenshot' => 'icon-screenshot',
				'icon-search' => 'icon-search',
				'icon-share' => 'icon-share',
				'icon-share-alt' => 'icon-share-alt',
				'icon-shopping-cart' => 'icon-shopping-cart',
				'icon-signal' => 'icon-signal',
				'icon-signin' => 'icon-signin',
				'icon-signout' => 'icon-signout',
				'icon-sitemap' => 'icon-sitemap',
				'icon-sort' => 'icon-sort',
				'icon-sort-down' => 'icon-sort-down',
				'icon-sort-up' => 'icon-sort-up',
				'icon-spinner' => 'icon-spinner',
				'icon-star' => 'icon-star',
				'icon-star-empty' => 'icon-star-empty',
				'icon-star-half' => 'icon-star-half',
				'icon-tablet' => 'icon-tablet',
				'icon-tag' => 'icon-tag',
				'icon-tags' => 'icon-tags',
				'icon-tasks' => 'icon-tasks',
				'icon-thumbs-down' => 'icon-thumbs-down',
				'icon-thumbs-up' => 'icon-thumbs-up',
				'icon-time' => 'icon-time',
				'icon-tint' => 'icon-tint',
				'icon-trash' => 'icon-trash',
				'icon-trophy' => 'icon-trophy',
				'icon-truck' => 'icon-truck',
				'icon-umbrella' => 'icon-umbrella',
				'icon-upload' => 'icon-upload',
				'icon-upload-alt' => 'icon-upload-alt',
				'icon-user' => 'icon-user',
				'icon-user-md' => 'icon-user-md',
				'icon-volume-off' => 'icon-volume-off',
				'icon-volume-down' => 'icon-volume-down',
				'icon-volume-up' => 'icon-volume-up',
				'icon-warning-sign' => 'icon-warning-sign',
				'icon-wrench' => 'icon-wrench',
				'icon-zoom-in' => 'icon-zoom-in',
				'icon-zoom-out' => 'icon-zoom-out',
				'icon-file' => 'icon-file',
				'icon-file-alt' => 'icon-file-alt',
				'icon-cut' => 'icon-cut',
				'icon-copy' => 'icon-copy',
				'icon-paste' => 'icon-paste',
				'icon-save' => 'icon-save',
				'icon-undo' => 'icon-undo',
				'icon-repeat' => 'icon-repeat',
				'icon-text-height' => 'icon-text-height',
				'icon-text-width' => 'icon-text-width',
				'icon-align-left' => 'icon-align-left',
				'icon-align-center' => 'icon-align-center',
				'icon-align-right' => 'icon-align-right',
				'icon-align-justify' => 'icon-align-justify',
				'icon-indent-left' => 'icon-indent-left',
				'icon-indent-right' => 'icon-indent-right',
				'icon-font' => 'icon-font',
				'icon-bold' => 'icon-bold',
				'icon-italic' => 'icon-italic',
				'icon-strikethrough' => 'icon-strikethrough',
				'icon-underline' => 'icon-underline',
				'icon-link' => 'icon-link',
				'icon-paper-clip' => 'icon-paper-clip',
				'icon-columns' => 'icon-columns',
				'icon-table' => 'icon-table',
				'icon-th-large' => 'icon-th-large',
				'icon-th' => 'icon-th',
				'icon-th-list' => 'icon-th-list',
				'icon-list' => 'icon-list',
				'icon-list-ol' => 'icon-list-ol',
				'icon-list-ul' => 'icon-list-ul',
				'icon-list-alt' => 'icon-list-alt',
				'icon-angle-left' => 'icon-angle-left',
				'icon-angle-right' => 'icon-angle-right',
				'icon-angle-up' => 'icon-angle-up',
				'icon-angle-down' => 'icon-angle-down',
				'icon-arrow-down' => 'icon-arrow-down',
				'icon-arrow-left' => 'icon-arrow-left',
				'icon-arrow-right' => 'icon-arrow-right',
				'icon-arrow-up' => 'icon-arrow-up',
				'icon-caret-down' => 'icon-caret-down',
				'icon-caret-left' => 'icon-caret-left',
				'icon-caret-right' => 'icon-caret-right',
				'icon-caret-up' => 'icon-caret-up',
				'icon-chevron-down' => 'icon-chevron-down',
				'icon-chevron-left' => 'icon-chevron-left',
				'icon-chevron-right' => 'icon-chevron-right',
				'icon-chevron-up' => 'icon-chevron-up',
				'icon-circle-arrow-down' => 'icon-circle-arrow-down',
				'icon-circle-arrow-left' => 'icon-circle-arrow-left',
				'icon-circle-arrow-right' => 'icon-circle-arrow-right',
				'icon-circle-arrow-up' => 'icon-circle-arrow-up',
				'icon-double-angle-left' => 'icon-double-angle-left',
				'icon-double-angle-right' => 'icon-double-angle-right',
				'icon-double-angle-up' => 'icon-double-angle-up',
				'icon-double-angle-down' => 'icon-double-angle-down',
				'icon-hand-down' => 'icon-hand-down',
				'icon-hand-left' => 'icon-hand-left',
				'icon-hand-right' => 'icon-hand-right',
				'icon-hand-up' => 'icon-hand-up',
				'icon-circle' => 'icon-circle',
				'icon-circle-blank' => 'icon-circle-blank',
				'icon-play-circle' => 'icon-play-circle',
				'icon-play' => 'icon-play',
				'icon-pause' => 'icon-pause',
				'icon-stop' => 'icon-stop',
				'icon-step-backward' => 'icon-step-backward',
				'icon-fast-backward' => 'icon-fast-backward',
				'icon-backward' => 'icon-backward',
				'icon-forward' => 'icon-forward',
				'icon-fast-forward' => 'icon-fast-forward',
				'icon-step-forward' => 'icon-step-forward',
				'icon-eject' => 'icon-eject',
				'icon-fullscreen' => 'icon-fullscreen',
				'icon-resize-full' => 'icon-resize-full',
				'icon-resize-small' => 'icon-resize-small',
				'icon-phone' => 'icon-phone',
				'icon-phone-sign' => 'icon-phone-sign',
				'icon-facebook' => 'icon-facebook',
				'icon-facebook-sign' => 'icon-facebook-sign',
				'icon-twitter' => 'icon-twitter',
				'icon-twitter-sign' => 'icon-twitter-sign',
				'icon-github' => 'icon-github',
				'icon-github-alt' => 'icon-github-alt',
				'icon-github-sign' => 'icon-github-sign',
				'icon-linkedin' => 'icon-linkedin',
				'icon-linkedin-sign' => 'icon-linkedin-sign',
				'icon-pinterest' => 'icon-pinterest',
				'icon-pinterest-sign' => 'icon-pinterest-sign',
				'icon-google-plus' => 'icon-google-plus',
				'icon-google-plus-sign' => 'icon-google-plus-sign',
				'icon-sign-blank' => 'icon-sign-blank',
				'icon-ambulance' => 'icon-ambulance',
				'icon-beaker' => 'icon-beaker',
				'icon-h-sign' => 'icon-h-sign',
				'icon-hospital' => 'icon-hospital',
				'icon-medkit' => 'icon-medkit',
				'icon-plus-sign-alt' => 'icon-plus-sign-alt',
				'icon-stethoscope' => 'icon-stethoscope',
				'icon-user-md' => 'icon-user-md'
			)
		);

		/* Get default options from file if exist */
		$opt_from_file = apply_filters( 'tom_default', $opt_default );
		$default = array_merge($opt_default, $opt_from_file);

		return $default;
	}


	function tom_options_fields() {
		$options = get_option( 'tom_options' );

		$options  = is_string($options) && is_object(json_decode($options )) ? json_decode($options,true ) : $options ;
		

		if ( !empty( $options ) ) {
			$options_from_db = $options;
		} else {
			$options_from_db = array();
		}

		/**
		 * [fixing warning]
		 * convert to array if empty object
		 */
		if ($options_from_db === '[]')
			$options_from_db = array();

		/* Get options from filter */
		$options_from_file = apply_filters( 'tom_options', $options_from_db );
		
		/* Merge filter with options from database */
		$options = array_merge($options_from_db, $options_from_file);

		return $options;
	}

	function tom_get_default_values() {
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
		return $output;
	}

}