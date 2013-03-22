<?php

/*
Plugin Name: WordPress Gallery
Description: Upload, edit and present images in a gallery via a shortcode.
Author: Jealous Designs
Author URI: http://wordpressgallery.co.uk
Version: 1.4
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

//NAG

/* Display a notice that can be dismissed */
add_action('admin_notices', 'cp_admin_notice');
function cp_admin_notice() {
    global $current_user ;
        $user_id = $current_user->ID;
        /* Check that the user hasn't already clicked to ignore the message */
    if ( ! get_user_meta($user_id, 'wordpress-gallery_ignore_notice') ) {
        echo '<div class="updated"><p>';
        printf(__('<a href="%1$s">I have subscribed</a>'), '?wordpress-gallery_nag_ignore=0');
        ?>
        <!-- Begin MailChimp Signup Form -->
		<link href="http://cdn-images.mailchimp.com/embedcode/slim-081711.css" rel="stylesheet" type="text/css">
		<style type="text/css">
			#mc_embed_signup{background:none; clear:left; font:14px Helvetica,Arial,sans-serif; }
			#mc_embed_signup .button{color: #333;};
			/* Add your own MailChimp form style overrides in your site stylesheet or in this style block.
			   We recommend moving this block and the preceding CSS link to the HEAD of your HTML file. */
		</style>
		<div id="mc_embed_signup">
		<form action="http://jealousdesigns.us2.list-manage.com/subscribe/post?u=a4a9840b607ebf25275bc7a46&amp;id=8ea7d99292" method="post" id="mc-embedded-subscribe-form" name="mc-embedded-subscribe-form" class="validate" target="_blank">
			<label for="mce-EMAIL">Sign up to our WordPress mailing list to receive news and updates about Fish Can't Whistle plugins</label>
			<input type="email" value="" name="EMAIL" class="email" id="mce-EMAIL" placeholder="email address" required>
			<input type="hidden" value="WordPress Gallery" name="MMERGE3">
			<div class="clear"><input type="submit" value="Subscribe" name="subscribe" id="mc-embedded-subscribe" class="button"></div>
		</form>
		</div>

		<!--End mc_embed_signup-->
        <?php
        echo "</p></div>";
    }
}
add_action('admin_init', 'wpg_nag_ignore');
function wpg_nag_ignore() {
    global $current_user;
        $user_id = $current_user->ID;
        /* If user clicks to ignore the notice, add that to their user meta */
        if ( isset($_GET['wordpress-gallery_nag_ignore']) && '0' == $_GET['wordpress-gallery_nag_ignore'] ) {
             add_user_meta($user_id, 'wordpress-gallery_ignore_notice', 'true', true);
    }
}


?>