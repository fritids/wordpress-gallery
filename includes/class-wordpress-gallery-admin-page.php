<?php

class wordpress_gallery_admin_page{

	private $wordpress_gallery_file_upload_class;

	function wordpress_gallery_admin_page($global){
		$this->__construct($global);
	}
	
	function __construct($global){ 
	
		$this->wordpress_gallery_file_upload_class = $global; 
		
		add_meta_box(	'gallery_meta_box_s3_add_new', __('Upload new image'), array( &$this, 'upload_box_content' ), 'gallery', 'side', 'core');
		
		add_meta_box(	'gallery_meta_box_s3_library', __('Library'), array( &$this, 'library_box_content' ), 'gallery', 'side', 'core');
		
		wp_nonce_field( 'closedpostboxes', 'closedpostboxesnonce', false );
		
		wp_nonce_field( 'meta-box-order', 'meta-box-order-nonce', false );					
		
		?>
		
		<script type="text/javascript" charset="utf-8">
			//<![CDATA[
			jQuery(document).ready( function($) {
				$('.if-js-closed').removeClass('if-js-closed').addClass('closed');
				//postboxes.add_postbox_toggles('gallery');
			});			
			//]]>
		</script>		
	
		<div class="wrap">
		    
			<div id="poststuff">
		    
		    	<h2><img src="<?php echo  WJG_url.'/img/gallery_large.png'; ?>" /> Gallery</h2>
		    	
				<?php $meta_boxes = do_meta_boxes('gallery', 'side', null); ?>	
		
		    </div>
		    
		</div>	
	
	<?php }
	
	function upload_box_content(){
		$this->wordpress_gallery_file_upload_class->show_the_form();
	}
	
	function library_box_content(){
		echo '<div id="wordpress_gallery_library">';
		$this->wordpress_gallery_file_upload_class->show_library();
		echo '</div>';
	}

}

?>