<?php
require('../../../wp-load.php');

include_once('wordpress-gallery.php'); //hook in to the plugin

include_once('includes/class-edit-image.php'); //edit image class

$wordpress_gallery_file_upload_class = new wordpress_gallery_file_upload_class($ak, $sk, $bn);

$wordpress_gallery_file_upload_class->do_upload();

?>