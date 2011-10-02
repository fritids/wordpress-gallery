<?php

class wordpress_gallery_settings_page{

	function wordpress_gallery_settings_page(){
		$this->__construct();
	}
	
	function __construct(){ 
	
		if(isset($_POST['submit_wordpress_gallery_settings'])){
			$this->update_wordpress_gallery_settings();
		}
			 
		add_meta_box(	'gallery_settings_meta_box_s3_set_up', __('Amazon S3'), array( &$this, 's3_box_content' ), 'gallery_settings', 'side', 'core');
		
		add_meta_box(	'gallery_settings_meta_box_gallery_settings', __('Gallery Settings'), array( &$this, 'gallery_settings_box_content' ), 'gallery_settings', 'side', 'core');
		
		wp_nonce_field( 'closedpostboxes', 'closedpostboxesnonce', false );
		wp_nonce_field( 'meta-box-order', 'meta-box-order-nonce', false );		
				
		?>		
				
		<script type="text/javascript" charset="utf-8">
			//<![CDATA[
			jQuery(document).ready( function($) {
				$('.if-js-closed').removeClass('if-js-closed').addClass('closed');
				postboxes.add_postbox_toggles('gallery_settings');
				$(".help[title]").tooltip();
				$("#trans_time").slider({ from: 500, to: 5000, step: 50, round: 1, dimension: 'MS', skin: "round" });
				
				showhidepagercontents();
				
				showhidethumbnailsize();
				
			});			
			//]]>
		</script>	

		<div class="wrap">
		    
			<div id="poststuff" class="wordpress_gallery_settings">
		    
		    	<h2><img src="<?php echo  WJG_url.'/img/gallery_large.png'; ?>" /> Gallery - Settings</h2>   
		    	
		    	<form method="POST" action="<?php echo admin_url( 'admin.php?page=gallery_settings' ); ?>"> 			    	
		    	
		    		<?php $meta_boxes = do_meta_boxes('gallery_settings', 'side', null); ?>	
		    		
		    		<input class="button-primary imgedit-submit-btn" type="submit" name="submit_wordpress_gallery_settings" value="Save Settings" id="" />
		    		
		    	</form>
		
		    </div>
		    
		</div>
	
	<?php } //function
	
	function s3_box_content(){ ?>
		
		<p>This is an optional part of the plugin. If you have an S3 account enter the details below. Otherwise leave them blank and the plugin will use your WordPress uploads folders. <br><br>If you don't have an Amazon S3 account and would like one go <a href='http://aws.amazon.com/s3/' target='_blank'>here</a> to sign up.</p>
		
		<br>
		
			
		<label>Access Key: </label><input type="text" name="s3_access_key" id="s3_access_key" value="<?php echo get_option('s3_access_key'); ?>" /><br>
				
		<label>Secret Key: </label><input type="text" name="s3_secret_key" id="s3_secret_key" value="<?php echo get_option('s3_secret_key'); ?>" /><br>
				
		<label>Bucket Name: </label><input type="text" name="s3_bucket_name" id="s3_bucket_name" value="<?php echo get_option('s3_bucket_name'); ?>" /><br>
					
	
	
	<?php }
	
	function gallery_settings_box_content(){ ?>
	
		<label>Image Size:</label><input type="text" name="image_size_x" id="image_size_x" value="<?php echo get_option('image_size_x'); ?>" size="5" />px (width) x <input type="text" name="image_size_y" id="image_size_y" value="<?php echo get_option('image_size_y'); ?>" size="5" />px (height)
		
		<br><br>
	
		<label>Image Transistion: </label>
		<select name="trans_type">
			<option <?php if(get_option('trans_type') == 'blindX'){echo "selected=\"selected\"";} ?> value="blindX">Blind X</option>
			<option <?php if(get_option('trans_type') == 'blindY'){echo "selected=\"selected\"";} ?> value="blindY">Blind Y</option>
			<option <?php if(get_option('trans_type') == 'blindZ'){echo "selected=\"selected\"";} ?> value="blindZ">Blind Z</option>
			<option <?php if(get_option('trans_type') == 'cover'){echo "selected=\"selected\"";} ?> value="cover">Cover</option>
			<option <?php if(get_option('trans_type') == 'curtainX'){echo "selected=\"selected\"";} ?> value="curtainX">Curtain X</option>
			<option <?php if(get_option('trans_type') == 'curtainY'){echo "selected=\"selected\"";} ?> value="curtainY">Curtain Y</option>
			<option <?php if(get_option('trans_type') == 'fade'){echo "selected=\"selected\"";} ?> value="fade">Fade</option>
			<option <?php if(get_option('trans_type') == 'fadeZoom'){echo "selected=\"selected\"";} ?> value="fadeZoom">Fade Zoom</option>
			<option <?php if(get_option('trans_type') == 'growX'){echo "selected=\"selected\"";} ?> value="growX">Grow X</option>
			<option <?php if(get_option('trans_type') == 'growY'){echo "selected=\"selected\"";} ?> value="growY">Grow Y</option>
			<option <?php if(get_option('trans_type') == 'none'){echo "selected=\"selected\"";} ?> value="none">None</option>
			<option <?php if(get_option('trans_type') == 'shuffle'){echo "selected=\"selected\"";} ?> value="shuffle">Shuffle</option>
			<option <?php if(get_option('trans_type') == 'scrollUp'){echo "selected=\"selected\"";} ?> value="scrollUp">Scroll Up</option>
			<option <?php if(get_option('trans_type') == 'scrollDown'){echo "selected=\"selected\"";} ?> value="scrollDown">Scroll Down</option>
			<option <?php if(get_option('trans_type') == 'scrollLeft'){echo "selected=\"selected\"";} ?> value="scrollLeft">Scroll Left</option>
			<option <?php if(get_option('trans_type') == 'scrollRight'){echo "selected=\"selected\"";} ?> value="scrollRight">Scroll Right</option>			
			<option <?php if(get_option('trans_type') == 'scrollHorz'){echo "selected=\"selected\"";} ?> value="scrollHorz">Scroll Horz</option>
			<option <?php if(get_option('trans_type') == 'scrollVert'){echo "selected=\"selected\"";} ?> value="scrollVert">Scroll Vert</option>
			<option <?php if(get_option('trans_type') == 'slideX'){echo "selected=\"selected\"";} ?> value="slideX">Slide X</option>
			<option <?php if(get_option('trans_type') == 'slideY'){echo "selected=\"selected\"";} ?> value="slideY">Slide Y</option>
			<option <?php if(get_option('trans_type') == 'toss'){echo "selected=\"selected\"";} ?> value="toss">Toss</option>
			<option <?php if(get_option('trans_type') == 'turnUp'){echo "selected=\"selected\"";} ?> value="turnUp">Turn Up</option>
			<option <?php if(get_option('trans_type') == 'turnDown'){echo "selected=\"selected\"";} ?> value="turnDown">Turn Down</option>
			<option <?php if(get_option('trans_type') == 'turnLeft'){echo "selected=\"selected\"";} ?> value="turnLeft">Turn Left</option>
			<option <?php if(get_option('trans_type') == 'turnRight'){echo "selected=\"selected\"";} ?> value="turnRight">Turn Right</option>
			<option <?php if(get_option('trans_type') == 'uncover'){echo "selected=\"selected\"";} ?> value="uncover">Uncover</option>
			<option <?php if(get_option('trans_type') == 'wipe'){echo "selected=\"selected\"";} ?> value="wipe">Wipe</option>
			<option <?php if(get_option('trans_type') == 'zoom'){echo "selected=\"selected\"";} ?> value="zoom">Zoom</option>
		</select><br>
		
		<br><br>
		
		<label>Transition Speed: </label>

		<div id="trans_time_slider">
			<input id="trans_time" type="slider" name="trans_time" value="<?php if(get_option('trans_time') !=''){echo get_option('trans_time');}else{echo "1000";} ?>" />
		</div>
		
		<br style="clear:both;"><br>
		
		<label>Enable Lightbox: </label>
		<select name="enable_lightbox">
			<option <?php if(get_option('enable_lightbox') == 'yes'){echo "selected=\"selected\"";} ?> value="yes">Yes</option>
			<option <?php if(get_option('enable_lightbox') == 'no'){echo "selected=\"selected\"";} ?> value="no">No</option>
		</select>
		<img class="help" title="Enable lightbox to allow the images to be clickable and when clicked show the original fullsize image in a 'lightbox'." src="<?php echo WJG_url; ?>/img/info.png" />
		
		<br><br>
		
		<label>Hard Crop: </label>
		<select name="hard_crop">
			<option <?php if(get_option('hard_crop') == 'true'){echo "selected=\"selected\"";} ?> value="true">Yes</option>
			<option <?php if(get_option('hard_crop') == 'false'){echo "selected=\"selected\"";} ?> value="false">No</option>
		</select>
		<img class="help" title="Enabling hard crop will force all images uploaded to be exactly the dimensions you specify above. <br><br>If not enabled images will keep their proportions and will be cropped to fit within the dimensions specified above.<br><br>Changing this option will not change previously uploaded images." src="<?php echo WJG_url; ?>/img/info.png" />
		
		<br><br>
		
		<label>Enable pager: </label>	
		<select name="show_pager" onChange="showhidepagercontents();" id="show_pager">
			<option <?php if(get_option('show_pager') == 'true'){echo "selected=\"selected\"";} ?> value="true">Yes</option>
			<option <?php if(get_option('show_pager') == 'false'){echo "selected=\"selected\"";} ?> value="false">No</option>
		</select>
		<img class="help" title="The pager enables functionality for viewers of the gallery to see what slide they are on and to switch manually switch to another." src="<?php echo WJG_url; ?>/img/info.png" />
		
		<div id="pager_contents">
		
			<br><br>	
		
			<label>Pager contents: </label>	
			<select name="pager_contents" onChange="showhidethumbnailsize();" id="pager_contents_select">
				<option <?php if(get_option('pager_contents') == 'nothing'){echo "selected=\"selected\"";} ?> value="nothing">Nothing</option>
				<option <?php if(get_option('pager_contents') == 'numbers'){echo "selected=\"selected\"";} ?> value="numbers">Numbers</option>
				<option <?php if(get_option('pager_contents') == 'thumbnails'){echo "selected=\"selected\"";} ?> value="thumbnails">Thumbnails</option>
			</select>
			<img class="help" title="Nothing:<br>Just show some empty squares which will show the current position and allo the user to click to another. Basic css is applied but to style further target #pager a{} and #pager a.activeSlide{}. <br><br>Numbers:<br>Show numbers inside the boxes. Also clickable.<br><br>Thumbnails:<br>Show thumbnails of the slides and allow viewers to click on them." src="<?php echo WJG_url; ?>/img/info.png" />
		
		</div>
		
		<div id="thumbnailsizes">
		
			<br><br>
			
			<label>Thumbnail size: </label>
			Height: <input type="text" size="3" name="thumbnailsize_height" value="<?php echo get_option('thumbnailsize_height'); ?>" /> Width: <input type="text" size="3" name="thumbnailsize_width" value="<?php echo get_option('thumbnailsize_width'); ?>" />
		
		</div>
	
	<?php }	
	
	function update_wordpress_gallery_settings(){
		
		if($_POST['s3_access_key'] != '' && 
		$_POST['s3_secret_key'] != '' && 
		$_POST['s3_bucket_name'] != '' ){
			update_option('use_s3', 'true');
			update_option( 'bucket_name', $_POST['s3_bucket_name'] );
		}else{
			update_option('use_s3', 'false');
		}
	
		update_option('s3_access_key', $_POST['s3_access_key']);
		update_option('s3_secret_key', $_POST['s3_secret_key']); 
		update_option('s3_bucket_name', $_POST['s3_bucket_name']); 
		update_option('trans_type', $_POST['trans_type']);
		update_option('trans_time', $_POST['trans_time']); 
		update_option('image_size_x', $_POST['image_size_x']); 
		update_option('image_size_y', $_POST['image_size_y']); 
		update_option('enable_lightbox', $_POST['enable_lightbox']); 
		update_option('hard_crop', $_POST['hard_crop']);
		update_option('show_pager', $_POST['show_pager']);
		update_option('pager_contents', $_POST['pager_contents']);
		update_option('thumbnailsize_height', $_POST['thumbnailsize_height']);
		update_option('thumbnailsize_width', $_POST['thumbnailsize_width']);
		
		echo '<div class="updated">Settings Updated!</div>';
	
	}

}

?>