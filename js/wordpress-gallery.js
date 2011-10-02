function showhidepagercontents(){

	if(jQuery('#show_pager').val() == 'true'){
	
		jQuery('#pager_contents').show();
		
		showhidethumbnailsize();
	
	}else{
	
		jQuery('#pager_contents').hide();
	
	}

}

function showhidethumbnailsize(){

	if(jQuery('#show_pager').val() == 'true' && jQuery('#pager_contents_select').val() == 'thumbnails'){
	
		jQuery('#thumbnailsizes').show();
	
	}else{
	
		jQuery('#thumbnailsizes').hide();
	
	}

}