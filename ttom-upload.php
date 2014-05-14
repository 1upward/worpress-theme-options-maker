<?php

class TtomUploader {

    /*
     *  Tonjoo Media Uploader
     *
     *  Hook on post / post_type :
     *  
     *  new TtomUploader(array('post_type'=>post))
     *
     *  Hook on page  :
     *
     *  $setting = array(
     *          'page_type'=>'page',
     *          'page' =>'ad_management'
     *       );
     *
     *  new TtomUploader($setting)
     *  
     *  Usage :
     *  
     *  <input  mediaUploadText type="hidden" >
     *  <input type="button" mediaUploadButton  value="Set image">
     *  <div><img mediaUploadImage ></img></div>
     *  
     *  
     */ 

    function __construct($init) {

    	$this->init = $init;

    	add_action( 'admin_enqueue_scripts', array(&$this, 'TtomMediaUpload') );
        
    }

    public function TtomMediaUpload()
    {
        global $post_type;

        $init = $this->init;

        //check if want to hook other than post type
        if(isset($init['page_type']))
        {
            $page = isset($init['page']) ? $init['page'] : '';

            if (isset($_GET["{$init['page_type']}"]) && $_GET["{$init['page_type']}"] == "{$page}")
            {
             
                $this->add_hook();
            }
        }
        //hook post_type
    	elseif (is_admin()&&$post_type==$init['post_type']) 
        {
	       $this->add_hook();
    	}
    }

    private function add_hook()
    {
        wp_enqueue_media();

        $plugin_url = plugin_dir_url(dirname(__FILE__));

        $js = $plugin_url . 'tonjoo-tom/assets/js/TtomMediaUpload.js';

        wp_register_script('TtomMediaUpload-js', $js);
        wp_enqueue_script('TtomMediaUpload-js');

        $css = $plugin_url . 'tonjoo-tom/assets/js/TtomMediaUpload.css';
    }

}