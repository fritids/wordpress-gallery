<?php 

/*
Plugin Name: WordPress Gallery
Description: Upload, edit and present images in a gallery via a shortcode.
Author: Jealous Designs
Author URI: http://wordpressgallery.co.uk
Version: 0.7.1
*/

register_activation_hook( __FILE__, 'activate_wordpress_gallery' );

function activate_wordpress_gallery(){
	update_option('trans_type', 'fade');
	update_option('timeout', '4000');
	update_option('trans_time', '1000'); 
	update_option('image_size_x', '500'); 
	update_option('image_size_y', '400'); 
	update_option('enable_lightbox', 'yes'); 
	update_option('hard_crop', 'true');
	update_option('pause', 'false');
}

if (!defined("WJG_url")) { define("WJG_url", WP_PLUGIN_URL.'/wordpress-gallery'); } //NO TRAILING SLASH

if (!defined("WJG_dir")) { define("WJG_url", WP_PLUGIN_DIR.'/wordpress-gallery'); } //NO TRAILING SLASH

$ak = get_option('s3_access_key');
$sk = get_option('s3_secret_key');
$bn = get_option('s3_bucket_name');

include_once('includes/class-wordpress-gallery-setup.php'); //Set up

include_once('includes/class-edit-image.php'); //edit image class

include_once('includes/class-wordpress-gallery-display.php'); //shortcode class

$wordpress_gallery_file_upload_class = new wordpress_gallery_file_upload_class($ak, $sk, $bn);

include_once('includes/class-wordpress-gallery-admin-page.php'); //main admin page

include_once('includes/class-wordpress-gallery-settings-page.php'); //settings page

function wordpress_gallery(){ //function to allow theme developers to use this function rather than the shortcode

	new wordpress_gallery_display();

}



?>