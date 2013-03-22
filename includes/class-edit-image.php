<?php

class wordpress_gallery_file_upload_class{

	private $awsAccessKey;
	private $awsSecretKey;
	public $awsBucket;
	private $use_s3;

	public $file_dir;
	public $file_url;
	public $file_name;
	public $file_type;
	public $file_security;
	public $attach_id;
	public $allowed_filetypes = array('jpg','gif','bmp','png', 'jpeg');
	public $current_upload_directory;
	public $files; //array of files directories - Should also include original files to be uploaded.

	function wordpress_gallery_file_upload_class($ak, $sk, $bn){
		$this->__construct($ak, $sk, $bn);
	}

	function __construct($ak, $sk, $bn){
		if($ak == '' || $sk == '' || $bn == ''){
			$this->use_s3 = FALSE;
			update_option('use_s3', 'false');
		}else{
			$this->use_s3 = TRUE;
			$this->awsAccessKey = $ak;
			$this->awsSecretKey = $sk;
			$this->awsBucket = $bn;
			update_option('use_s3', 'true');
		}
	}


	function show_the_form(){ ?>

		<form name="jealous_upload_new_image" method="POST" enctype="multipart/form-data" action="<?php echo admin_url( 'admin.php?page=gallery' ); ?>">

			<?php
			if ( function_exists('wp_nonce_field') )
				wp_nonce_field('wordpress_jealous_gallery_upload_new');
			?>

			<input type="file" name="jealous_new_file_input[]" class="multi" />

			<input type="submit" name="jealous_new_file_submit" class="button" value="Upload File(s)" />

		</form>

		<div id="jealous_progress_bar">
			<img src="<?php echo WJG_url.'/img/ajax-loader.gif'; ?>" />
		</div>

		<script>

			jQuery(function(){
				jQuery('#jealous_progress_bar').hide();
			})

		</script>

		<?php

		$this->do_upload();

	}

	function do_upload(){

		global $current_blog;

		//update_option('jealous_library', '');

		//echo "here:".get_option('use_s3'); exit;

		include_once('class-s3.php');

		$s3 = new S3($this->awsAccessKey, $this->awsSecretKey);

		if( isset($_POST['jealous_new_file_submit'] ) || isset( $_POST['id'] ) || isset( $_GET['id'] ) || isset( $_POST['edit_id'] ) || isset( $_POST['delete_id'] ) ) {

			if(isset($_POST['delete_id'])){

				check_admin_referer('wordpress_jealous_gallery_delete');

				$this->attach_id = $_POST['delete_id'];

				//print_r($this->get_all_meta($this->attach_id));

				if($this->use_s3){

					$meta = $this->get_all_meta($this->attach_id);

				    if (S3::deleteObject($this->awsBucket, $meta['100x100']) && S3::deleteObject($this->awsBucket, $meta['wordpress_gallery'])) {
				        echo '<div class="updated">Successfully deleted files from S3.</div>';
				    }else{
				    	echo '<div class="error">Files not deleted. You probably tried to delete a file from S3 that was never uploaded to S3.</div> ';
				    }

				}

				$current_library = get_option('jealous_library');

				update_option('jealous_library', str_replace($this->attach_id.',', '', $current_library));
				update_option('jealous_library', str_replace(','.$this->attach_id, '', $current_library));

				wp_delete_post($this->attach_id);

				echo '<div class="updated">Image '.$this->attach_id.' deleted</div>';

			}elseif(isset($_POST['id'])){

				$this->attach_id = $_POST['id'];

				$this->file_dir = get_attached_file( $_POST['id'] );

				$this->file_name = end(explode('/', $this->file_dir));

				$this->current_upload_directory = str_replace($this->file_name, '', $this->file_dir);

				$this->file_security = S3::ACL_PUBLIC_READ;

				$this->process_image();

			}elseif(isset($_POST['edit_id'])){

				$this->attach_id = $_POST['edit_id'];

				$this->file_dir = get_attached_file( $_POST['edit_id'] );

				$this->file_name = end(explode('/', $this->file_dir));

				$this->file_url	= wp_get_attachment_url($this->attach_id);

				$this->current_upload_directory = str_replace($this->file_name, '', $this->file_dir);

				$this->file_security = S3::ACL_PUBLIC_READ;

				$this->process_image();	 ?>

				<form name="" id="wordpress_gallery_submit_edit" method="POST" action="<?php echo admin_url( 'admin.php?page=gallery' ); ?>">
					<table class="linktable slidetoggle describe form-table">

						<tr class="url">
							<th valign="top" scope="row" class="label"><label for="link"><span class="alignleft">Link URL</span><br class="clear"></label></th>
							<?php $imagelink = get_post_meta($this->attach_id, 'link'); ?>
							<td class="field"><input type="text" class="text" id="link" name="link" value="<?php echo $imagelink[0]; ?>"><p class="help">Enter a URL to link this image to.</p></td>
						</tr>

					</table>

					<input type="hidden" name="id" value="<?php echo $this->attach_id; ?>" />

					<input type="submit" class="button-primary imgedit-submit-btn" id="submit_image_upload_button_edit" name="" value="Save changes and update gallery" style="margin-left:0;margin-top:5px;"/>

				</form>

				<div id="ajax_result"></div>

				<script>

					jQuery(function(){
					    jQuery("#wordpress_gallery_submit_edit").submit(function(e){
					       	e.preventDefault();

					       	jQuery('input[type=submit]', this).attr('disabled', 'disabled');

					       	jQuery('#imgedit-open-btn-<?php echo $this->attach_id; ?>').attr('disabled', 'disabled');

					        jQuery.ajax({
					        async: true,
					        type: "POST",
					        url: "<?php echo WJG_url. '/submit.php'; ?>",
					        data: jQuery("#wordpress_gallery_submit_edit").serialize(),
						    success: function(result){
								jQuery("#ajax_result").html(result);
								jQuery('#media-head-<?php echo $this->attach_id; ?>').fadeOut('slow', function(){jQuery('#media-head-<?php echo $this->attach_id; ?>').remove()});
								jQuery('.linktable').fadeOut('slow', function(){jQuery('.linktable').remove()});
								jQuery('#submit_image_upload_button_edit').fadeOut('slow', function(){jQuery('#submit_image_upload_button_edit').remove()});
								jQuery('#image-editor-<?php echo $this->attach_id; ?>').fadeOut('slow', function(){jQuery('#image-editor-<?php echo $this->attach_id; ?>').remove()});
						      	jQuery("#wordpress_gallery_library").html('<img src="<?php echo WJG_url.'/img/ajax-loader.gif'; ?>" />');
						        jQuery.ajax({
						        async: true,
						        type: "GET",
						        url: "<?php echo WJG_url. '/library.php'; ?>",
							    success: function(result){
							      jQuery("#wordpress_gallery_library").html(result);
							    }

						        });

						    }

					        });

					    });
					});

				    jQuery("#jealous_progress_bar").ajaxSend(function(r, s) {
				        jQuery(this).show();
				    });

				    jQuery("#jealous_progress_bar").ajaxStop(function(r, s) {
				        jQuery(this).hide();
				    });

					jQuery(function(){
						jQuery('.slidetoggle .media-item-info td:nth-child(2)').css('display', 'none');
						jQuery('#jealous_progress_bar').hide();
					});

				</script>

			<?php }elseif(isset($_POST['jealous_new_file_submit'])){

				check_admin_referer('wordpress_jealous_gallery_upload_new');

				$files = $_FILES['jealous_new_file_input'];
				foreach ($files['name'] as $key => $value) {
					$ext = end(explode('.', $files['name'][$key]));
					if(in_array($ext, $this->allowed_filetypes)){
						if ($files['name'][$key]) {
							$file = array(
							  'name'     => $files['name'][$key],
							  'type'     => $files['type'][$key],
							  'tmp_name' => $files['tmp_name'][$key],
							  'error'    => $files['error'][$key],
							  'size'     => $files['size'][$key]
							);

							$upload_overrides = array( 'test_form' => false );

							$uploaded_file = wp_handle_upload( $file, $upload_overrides);

							$this->file_dir = $uploaded_file['file'];

							$this->file_url = $uploaded_file['url'];

							$this->file_name = end(explode('/', $this->file_dir));

							$wp_filetype = wp_check_filetype(basename($this->file_dir), null );

							$attachment = array(
							 'post_mime_type' => $wp_filetype['type'],
							 'post_title' => preg_replace('/\.[^.]+$/', '', basename($this->file_dir)),
							 'post_content' => '',
							 'post_status' => 'inherit'
							);

							$this->attach_id = wp_insert_attachment( $attachment, $this->file_dir, 37 );

							//echo "<strong>Attach ID: </strong>" . $this->attach_id;

						}

						if(isset($uploaded_file['error'])){
								echo '<div class="error">' . $uploaded_file['error'] . '</div>';
						}else{

							$this->current_upload_directory = str_replace($this->file_name, '', $this->file_dir);

							$this->file_security = S3::ACL_PUBLIC_READ;

							$this->process_image();

							?>

							<form name="submit_image_upload" id="submit_image_upload-<?php echo $this->attach_id; ?>" method="POST" action="<?php echo admin_url( 'admin.php?page=gallery' ); ?>">

								<input type="hidden" name="id" value="<?php echo $this->attach_id; ?>" />

									<table class="slidetoggle describe form-table">

										<tr class="url">
											<th valign="top" scope="row" class="label"><label for="link"><span class="alignleft">Link URL</span><br class="clear"></label></th>
											<?php $imagelink = get_post_meta($this->attach_id, 'link'); ?>
											<td class="field"><input type="text" class="text" id="link" name="link" value="<?php echo $imagelink[0]; ?>"><p class="help">Enter a URL to link this image to.</p></td>
										</tr>

									</table>

								<input type="submit" class="button-primary imgedit-submit-btn" id="submit_image_upload_button-<?php echo $this->attach_id; ?>" name="" value="Upload and add to gallery" style="margin-top:5px;"/>

							</form>

							<script>

								jQuery(function(){
								    jQuery("#submit_image_upload-<?php echo $this->attach_id; ?>").submit(function(e){
								       	e.preventDefault();

								       	jQuery('input[type=submit]', this).attr('disabled', 'disabled');

								       	jQuery('#imgedit-open-btn-<?php echo $this->attach_id; ?>').attr('disabled', 'disabled');

								        jQuery.ajax({
								        async: true,
								        type: "POST",
								        url: "<?php echo WJG_url. '/submit.php'; ?>",
								        data: jQuery("#submit_image_upload-<?php echo $this->attach_id; ?>").serialize(),
									    success: function(result){
											jQuery("#ajax_result-<?php echo $this->attach_id; ?>").html(result);
											jQuery('#media-head-<?php echo $this->attach_id; ?>').fadeOut('slow', function(){jQuery('#media-head-<?php echo $this->attach_id; ?>').remove()});
											jQuery('.linktable').fadeOut('slow', function(){jQuery('.linktable').remove()});
											jQuery('#submit_image_upload_button-<?php echo $this->attach_id; ?>').fadeOut('slow', function(){jQuery('#submit_image_upload_button-<?php echo $this->attach_id; ?>').remove()});
											jQuery('#imgedit-response-<?php echo $this->attach_id; ?>').fadeOut('slow', function(){jQuery('#imgedit-response-<?php echo $this->attach_id; ?>').remove()});
									      	jQuery("#wordpress_gallery_library").html('<img src="<?php echo WJG_url.'/img/ajax-loader.gif'; ?>" />');
									        jQuery.ajax({
									        async: true,
									        type: "GET",
									        url: "<?php echo WJG_url. '/library.php'; ?>",
										    success: function(result){
										      jQuery("#wordpress_gallery_library").html(result);
										    }

									        });

									    }

								        });

								    });
								});

							    jQuery("#jealous_progress_bar").ajaxSend(function(r, s) {
							        jQuery(this).show();
							    });

							    jQuery("#jealous_progress_bar").ajaxStop(function(r, s) {
							        jQuery(this).hide();
							    });

								jQuery(function(){
									jQuery('.slidetoggle .media-item-info td:nth-child(2)').css('display', 'none');
									jQuery('#jealous_progress_bar').hide();
								});

							</script>

							<div id="ajax_result-<?php echo $this->attach_id; ?>"></div>

							<?php

						}

					}else{
						echo '<div class="error">'.$files['name'][$key] ." could not be uploaded because it isn't a supported image type.</div>";
					}

				}

			}

		}

	}

	function process_image(){

		if(isset($_POST['jealous_new_file_submit']) || isset($_POST['edit_id'])){

				require_once(ABSPATH . 'wp-admin/includes/image.php');

				$attach_data = wp_generate_attachment_metadata( $this->attach_id, $this->file_dir );

				wp_update_attachment_metadata( $this->attach_id, $attach_data );

				add_filter('attachment_fields_to_edit', array( &$this,'jealous_gallery_fields_to_edit'), 10, 2);

				add_filter("attachment_fields_to_save", array( &$this, 'jealous_gallery_fields_to_save'), null , 2);

				echo get_media_item( $this->attach_id, array('toggle' => false, 'send' => false, 'show_title' => false, 'delete' => false, 'thumbnail' => false) );

		}

		$files_data = wp_get_attachment_metadata( $this->attach_id, TRUE );

		$this->files['orig_image'] = $this->file_dir;

		if($files_data['sizes']['100x100']['file']){
			$this->files['100x100'] = $this->current_upload_directory . '/' . $files_data['sizes']['100x100']['file'];
		}else{
			//echo "Original image was too small to resize to 200px x 200px.";
		}
		if($files_data['sizes']['wordpress_gallery']['file']){
			$this->files['wordpress_gallery'] = $this->current_upload_directory . '/' . $files_data['sizes']['wordpress_gallery']['file'];
		}else{
			//echo "Original image was too small to resize to 200px x 200px.";
		}

		if(isset($_POST['id'])){

			$this->upload_the_images($this->files);

		}

		if(isset($_POST['edit_id'])){

			$nonce = wp_create_nonce( "image_editor-$this->attach_id" );

			?>

			<script>

			jQuery(function(){

				imageEdit.open( <?php echo $this->attach_id; ?>, "<?php echo $nonce; ?>" );

			});

			</script>

			<?php

		}

	}



	function upload_the_images($files){

		update_post_meta($this->attach_id, 'link', $_POST['link']);

		global $current_blog;

		include_once('class-s3.php');

		$s3 = new S3($this->awsAccessKey, $this->awsSecretKey);

		echo '<div class="updated">';

		if($this->use_s3){

			foreach($files as $meta_name => $file){

				$filename = end(explode('/', $file));

				if ($s3->putObjectFile($file, $this->awsBucket, $current_blog->blog_id.'/'.$filename, $this->file_security) ) {

					echo $filename . " uploaded to S3!<br>";

					add_post_meta($this->attach_id, $meta_name, $current_blog->blog_id.'/'.$filename);

				}else{
					echo $filename . " not uploaded to S3... :-(<br>";
				}

				$current_library = get_option('jealous_library');

				if(strrpos($current_library, $this->attach_id) === false){

					//if(strrpos($current_library, ',') === FALSE){
						//update_option('jealous_library', $this->attach_id);
					//}else{
						update_option('jealous_library', $current_library.','.$this->attach_id);
					//}

				}

			}

		}else{

			foreach($files as $meta_name => $file){

				$filename = end(explode('/', $file));

				echo $filename . " saved!<br>";

				add_post_meta($this->attach_id, $meta_name, $filename);

				$current_library = get_option('jealous_library');

				if(strrpos($current_library, $this->attach_id) === false){

					//if(strrpos($current_library, ',') === FALSE){
						//update_option('jealous_library', $this->attach_id);
					//}else{
						update_option('jealous_library', $current_library.','.$this->attach_id);
					//}

				}

			}

		}

		echo '</div>';

	}

	function get_all_meta($id = 0){
	    //if we want to run this function on a page of our choosing them the next section is skipped.
	    //if not it grabs the ID of the current page and uses it from now on.
	    if ($id == 0) :
	        global $wp_query;
	        $content_array = $wp_query->get_queried_object();
	        $id = $content_array->ID;
	    endif;

	    $first_array = get_post_custom_keys($id);

	    //first loop puts everything into an array, but its badly composed
	    foreach ($first_array as $key => $value) :
	           $second_array[$value] =  get_post_meta($id, $value, FALSE);

	            //so the second loop puts the data into a associative array
	            foreach($second_array as $second_key => $second_value) :
	                       $result[$second_key] = end($second_value);
	            endforeach;
	     endforeach;

	    //and returns the array.
	    return $result;
	}

	function show_library(){ ?>

		<script>
		$(function() {
			jQuery( "#wordpress_gallery_images" ).sortable({
   				stop: function(event, ui) {
   					var order = '';
   					jQuery('.wordpress_gallery_library_item').each(function(index){
   						if(order == ''){
   							order = jQuery(this).attr('rel');
   						}else{
   							order = order + ',' + jQuery(this).attr('rel');
   						}
   					});
					jQuery.ajax({
						url: '<?php echo WJG_url; ?>/functions/update_order.php?load=<?php echo ABSPATH; ?>&order=' + order,
					  	success: function(data) {
					    	$('#order_result').html(data);
					  	}
					});
   					jQuery('#order_result').fadeOut(3000, function(){jQuery('#order_result').html('<br>').show();});
   				}
			});
			jQuery( "#wordpress_gallery_images" ).disableSelection();
		});
		</script>

		<?php

		include_once('class-s3.php');

		$s3 = new S3($this->awsAccessKey, $this->awsSecretKey);

		if(get_option('jealous_library') != ''){

			$library =  explode(',', get_option('jealous_library'));

			$images = array();

			foreach($library as $item){

				//$file_type = wp_check_filetype(basename($this->file_dir), null );

				$meta = $this->get_all_meta($item);
				//echo '<pre>'; print_r($meta);
				if($meta['100x100'] != ''){

					$images[] = $item;

				}

			}

			echo "<br><em>Drag to order the images.</em><br><div id=\"order_result\"><br></div>";

			echo '<div id="wordpress_gallery_images">';

			foreach($images as $image){

				$meta = $this->get_all_meta($image);

			  	echo "<div class=\"wordpress_gallery_library_item\" rel=\"" . $image . "\">";

			    if($this->use_s3){
				    echo "<img src=\"http://".$this->awsBucket.".s3.amazonaws.com/".$meta['100x100']."\" alt=\"".$meta['orig_image']."\" /><br>";
				}else{
					echo wp_get_attachment_image( $image, '100x100' )."<br>";

				}
				$imagelink = get_post_meta($image, 'link');

				if($imagelink[0] != ''){
					echo end(explode('/', $meta['100x100'])) . "<br><em>Linked to " . $imagelink[0] . '</em><br>';
				}else{
					echo end(explode('/', $meta['100x100'])) . '<br><em>Not linked</em><br>';
				}

			    ?>

				<form name="" method="POST" action="<?php echo admin_url( 'admin.php?page=gallery' ); ?>">

					<input type="hidden" name="edit_id" value="<?php echo $image; ?>" />

					<input type="submit" class="button" name="" value="Edit" style="margin-left:0; margin-top:5px;"/>

				</form>

				<form name="" method="POST" action="<?php echo admin_url( 'admin.php?page=gallery' ); ?>">

					<?php
					if ( function_exists('wp_nonce_field') )
						wp_nonce_field('wordpress_jealous_gallery_delete');
					?>

					<input type="hidden" name="delete_id" value="<?php echo $image; ?>" />

					<input type="submit" class="button" name="" value="Delete" style="margin-left:0; margin-top:5px;"/>

				</form>

			    <?php

				echo " </div>";

			}

			echo '</div>';

			echo '<div style="clear:both;"></div>';

		}

	}

	function jealous_gallery_fields_to_edit( $form_fields, $post) {

		global $jealous_file_upload_class;

		global $current_blog;

		$form_fields = "";
		$form_fields['buttons'] = array(
			'input' => 'hidden'
		);
/*
		$form_fields['url'] = array(
			'label'      => __('Link URL'),
			'input'      => 'html',
			'helps'      => __('Enter a link URL or click above for presets.')
		);
*/
		return $form_fields;
	}

	function jealous_gallery_fields_to_save($post, $attatchment ) {

	}

}

?>