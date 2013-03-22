<?php

new wordpress_gallery_setup;

class wordpress_gallery_setup {

	public $image_sizes = array(
		array( 'name'=>'100x100', 'width'=>100, 'height'=>100, 'crop'=>true )
	);

	function wordpress_gallery_setup(){
		$this->__construct();
	} // function

	function __construct(){

		$this->image_sizes[1]['name'] = "wordpress_gallery";
		$this->image_sizes[1]['width'] = get_option('image_size_x');
		$this->image_sizes[1]['height'] = get_option('image_size_y');
		$this->image_sizes[1]['crop'] = "true";
		add_action("admin_print_scripts", array( &$this, 'js_libs' ));
		add_action("wp_enqueue_scripts", array( &$this, 'js_libs_front_end' ));
		add_action("admin_print_styles", array( &$this, 'style_libs' ));
		add_action("wp_enqueue_scripts", array( &$this, 'style_libs_front_end' ));
		add_action('admin_menu', array( &$this, 'jealous_gallery_add_page'));
		add_filter( 'intermediate_image_sizes', array( &$this, 'add_custom_image_sizes' ) );

		foreach ( $this->image_sizes as $image_size ){
			add_image_size( $image_size['name'], $image_size['width'], $image_size['height'], $image_size['crop'] );
			update_option( $image_size['name']."_size_w", $image_size['width'] );
			update_option( $image_size['name']."_size_h", $image_size['height'] );
			update_option( $image_size['name']."_crop", $image_size['crop'] );
		}

		add_shortcode( 'gallery', array( &$this, 'wordpress_gallery_display' ) );

		add_shortcode( 'wp-gallery', array( &$this, 'wordpress_gallery_display' ) );

	} //function

	function js_libs() {
		if($_GET['page'] == 'gallery' || $_GET['page'] == 'gallery_settings'){
		wp_enqueue_script('utils');
		wp_enqueue_script('image-edit');
		wp_enqueue_script( 'wp-ajax-response' );
		wp_enqueue_script('swfupload-all');
		wp_enqueue_script('swfupload-handlers');
		wp_enqueue_script('set-post-thumbnail' );
	    wp_enqueue_script( 'jquery-tools', WJG_url.'/js/jquery.MultiFile.js', '', 1.0  );
		wp_enqueue_script('jquery-multi', 'http://cdn.jquerytools.org/1.2.7/jquery.tools.min.js', '', 1.0 );
		wp_enqueue_script('common');
		wp_enqueue_script('wp-lists');
		wp_enqueue_script('postbox');
		wp_enqueue_script('jquery-depend-class', WJG_url.'/js/jquery.dependClass.js', '', 1.0 );
		wp_enqueue_script('jquery-slider', WJG_url.'/js/jquery.slider-min.js', '', 1.0 );
		wp_enqueue_script('wordpress-gallery', WJG_url.'/js/wordpress-gallery.js', '', 1.0 );
		}
	}

	function style_libs() {
		global $wp_styles;
		wp_enqueue_style('imgareaselect');
		wp_enqueue_style('jealous_gallery_styles', WJG_url.'/css/style.css');
		wp_enqueue_style('jslider-style-round', WJG_url.'/css/jslider.round.css');
		wp_enqueue_style('jslider-style', WJG_url.'/css/jslider.css');

		wp_register_style('jslider-style-round-ie6', WJG_url.'/css/jslider.round.ie6.css');
		wp_register_style('jslider-style-ie6', WJG_url.'/css/jslider.ie6.css');
		$wp_styles->add_data('jslider-style-round-ie6', 'conditional', 'IE');
		$wp_styles->add_data('jslider-style-ie6', 'conditional', 'IE');
	} //function

	function js_libs_front_end(){
		wp_deregister_script('jquery');
		wp_register_script('jquery', "http" . ($_SERVER['SERVER_PORT'] == 443 ? "s" : "") . "://ajax.googleapis.com/ajax/libs/jquery/1.8.1/jquery.min.js", false, null);
		wp_enqueue_script('jquery');
		wp_enqueue_script('jquery-cycle', 'http://cloud.github.com/downloads/malsup/cycle/jquery.cycle.all.latest.js', array('jquery'), 1.0 );
		wp_enqueue_script('jquery-lightbox-script', WJG_url.'/js/jquery.lightbox-0.5.min.js', array('jquery'), 1.0 );

		$data = array( 'img_url' => WJG_url . '/img/' );
		wp_localize_script( 'jquery-lightbox-script', 'localize_settings', $data );

	}

	function style_libs_front_end(){
		wp_enqueue_style('jquery-lightbox-style', WJG_url.'/css/jquery.lightbox-0.5.css');
		wp_enqueue_style('wordpress-gallery', WJG_url.'/css/wordpress-gallery.css');
	}

	function jealous_gallery_add_page() {
		add_menu_page('Gallery', 'Gallery', 'edit_pages', 'gallery', array( &$this, 'jm_gallery_admin_page' ), WJG_url.'/img/gallery.png', 16);
		add_submenu_page('gallery', 'Settings', 'Settings', 'edit_pages', 'gallery_settings', array( &$this, 'jm_gallery_admin_page_settings' ), 2);
	} //function

	function jm_gallery_admin_page(){
		global $wordpress_gallery_file_upload_class;
		new wordpress_gallery_admin_page($wordpress_gallery_file_upload_class);
	}

	function jm_gallery_admin_page_settings(){
		new wordpress_gallery_settings_page();
	}

	function add_custom_image_sizes( $sizes ){
		foreach ( $this->image_sizes as $image_size ){
			$sizes[] = $image_size['name'];
		}
		return $sizes;
	}

	function wordpress_gallery_display(){
		new wordpress_gallery_display();
	}

}