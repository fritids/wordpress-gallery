=== WordPress Gallery ===
Contributors: jealousdesigns
Donate link: http://jealousdesigns.co.uk
Tags: gallery, image, slideshow, S3, amazon, jquery, galleries, 
Requires at least: 3.0
Tested up to: 3.1.3
Stable tag: 0.41

WordPress gallery provides a simple interface for uploading images, editing them and having them displayed on the front end of your site.

== Description ==

WordPress gallery provides a simple interface for uploading images, editing them and having them displayed on the front end of your site via a shortcode or a function call.

The front end gallery can be configured in the following ways-

* Height and width of your gallery.
* Transition effect.
* Transition time.
* Optional lightbox effect to original image.

Uploading images is done through a simple interface that allows you to upload multiple images at once (only limited by your server's execution time and maximum file size). You can then edit the image to adjust crop, scale and rotation. Any changes you make to an image can be easily reverted back to the original image at any time.

If you have an Amazon S3 account your images will be uploaded there saving bandwidth and storage costs.

== Installation ==

1. Upload the folder `wordpress-gallery` to the `/wp-content/plugins/` directory keeping the file structure.
2. Activate the plugin through the 'Plugins' menu in WordPress
3. Adjust the settings from the Gallery->Settings menu.
4. Upload and edit your images from the Gallery (or Gallery->Gallery) menu.
5. To display your gallery in a page or post just insert the shortcode [wp-gallery]. You can also call the gallery from within your theme files by using the function call `<?php wordpress_gallery(); ?>`.

== Frequently Asked Questions ==

= How do I show the gallery in a page? =

Enter the following in any page or post to display your gallery-

[wp-gallery]

= Can I use this in my theme files? =

Yes. Enter the following code where you would like the gallery to display-

`<?php wordpress_gallery(); ?>`

= How do I use Amazon S3 with this plugin? =

You will need to sign up for an Amazon S3 account and obtain an access key and a secret key. See here for more information `http://www.hongkiat.com/blog/amazon-s3-the-beginners-guide/`
You will also need to create a bucket.
Once you have an access key, a secret key and a bucket name enter them in the relevant boxes on the settings page of the plugin.

= Can I have multiple galleries? =

No. Currently this version does not support multiple galleries. However this feature will be coming in a later release.

= Can I put other media in the gallery? =

No. This plugin is currently for images only.

= Wasn't the short code [gallery]? Can I still use that? =

Yes and yes. The shortcode was [gallery] and that can still be used if you don't have any problems with it. Another shortcode [wp-gallery] was added to get rid of problems caused by [gallery] shortcode.

== Screenshots ==

1. The main admin page which shows options to add a new image and view and edit images currently in the gallery.
2. Edit any image in your gallery at any time.
3. Upload multiple images.
4. The new menu item is conveniently placed below your current Media menu item.
5. The main settings page.
6. A plethora of options are available for the transitions between images.

== Changelog ==

= 0.1 =
* First release

= 0.4 =
* Fixed links menu being hidden.

= 0.41 =
* Added an extra shortcode as there is a conflict with the default gallery short code. Users can now use [wp-gallery] as well as [gallery] :-)

== Upgrade Notice ==

= 0.1 =

First release so no update

= 0.41 =

Extra shortcode [wp-gallery] added for users having compatibility problems with [gallery]
