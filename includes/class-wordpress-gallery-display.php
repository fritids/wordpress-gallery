<?php

class wordpress_gallery_display extends wordpress_gallery_file_upload_class{


	function wordpress_gallery_display(){
		$this->__construct();
	}
	
	function __construct(){ 
		
		$this->display_the_gallery();
	
	}
	
	function display_the_gallery(){ 
	
		?>
		<script type="text/javascript">
		jQuery(document).ready(function() {
		    jQuery('#wordpress_gallery').cycle({
				<?php if(get_option('show_pager') == 'true'){ 
					echo 'pager: \'#pager\',';
				} 
				if(get_option('pager_contents') == 'thumbnails'){ ?>
				pagerAnchorBuilder: function(idx, slide) {
					var img = jQuery(slide).children().eq(0).attr("src");
					return '<a class="wpg-thumbnail" href="#"><img src="' + img + '" width="<?php echo get_option('thumbnailsize_width'); ?>" height="<?php echo get_option('thumbnailsize_height'); ?>" /></a>';
				},
				<?php } ?>
				fx: '<?php echo get_option('trans_type'); ?>',
				speed: <?php echo get_option('trans_time'); ?>,
				width: <?php echo get_option('image_size_x'); ?>,
				height: <?php echo get_option('image_size_y'); ?>
			});
			
		    jQuery('#wordpress_gallery a.lightbox').lightBox();
			
			<?php if(get_option('pager_contents') == 'nothing'){ ?>
				jQuery('#pager a').html('');
			<?php } ?>			    
		});
		</script>		
		<?php
	
		if(get_option('jealous_library') != ''){	
	
			$library =  explode(',', get_option('jealous_library'));
		
			$images = array();		
				
			foreach($library as $item){
			
				$file_type = wp_check_filetype(basename($this->file_dir), null );
				
				$meta = $this->get_all_meta($item);			
								
				$images[] = $item;
	
			}
			
			$bucket = get_option( 'bucket_name' );
			
			if(get_option( 'enable_lightbox' ) == 'yes'){
				$lightbox = TRUE;
			}else{
				$lightbox = FALSE;
			}			
			
			echo "<div id=\"wordpress_gallery\">";
						
			foreach($images as $image){
			
				$meta = $this->get_all_meta($image);
				
				$imagelink = get_post_meta($image, 'link');
			  	
			    if(get_option('use_s3') == 'true'){ 
			    	if($lightbox){
			    		echo "<a href=\"http://".$bucket.".s3.amazonaws.com/".$meta['orig_image']."\" class=\"lightbox\">";
			    	}elseif($imagelink[0] != ''){
			    		echo '<a href="' . $imagelink[0] . '" />';
			    	}
				    echo "<img src=\"http://".$bucket.".s3.amazonaws.com/".$meta['wordpress_gallery']."\" alt=\"".$meta['orig_image']."\" />";
				    if($lightbox || $imagelink[0] != ''){echo "</a>";}
				}else{
					if($lightbox){
						$img_src = wp_get_attachment_image_src( $image, 'full' );
						echo "<a href=\"".$img_src[0]."\" class=\"lightbox\">";
					}elseif($imagelink[0] != ''){
			    		echo '<a href="' . $imagelink[0] . '" />';
			    	}
					echo wp_get_attachment_image( $image, 'wordpress_gallery' ); 
					if($lightbox || $imagelink[0] != ''){echo "</a>";}
					
				}
													
			}
			
			echo "</div>";
			
			if(get_option('show_pager') == 'true'){ 
			
				echo '<div id="pager"></div>';
			
			}
			
		}					
	
	}

}

?>