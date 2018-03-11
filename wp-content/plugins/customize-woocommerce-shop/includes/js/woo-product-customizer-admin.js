jQuery(document).ready( function($) {
	var config = {
      '.chosen-select'           : {},
      '.chosen-select-deselect'  : {allow_single_deselect:true},
      '.chosen-select-no-results': {no_results_text:'Oops, nothing found!'},
      '.chosen-select-width'     : {width:"95%"}
    }
   
	jQuery('select').chosen(config);		
	
 	//Image uploader
    $( '.repupresscustomwoocommerceproduct-img-uploader' ).click(function() {
    	
    	var imgfield;
    	imgfield = jQuery(this).prev('input').attr('id');
    	var showfield = jQuery(this).next().next().next().attr('id');
    	
		if(typeof wp == "undefined" || WooOrderExp.new_media_ui != '1' ){// check for media uploader

			tb_show('', 'media-upload.php?type=image&amp;TB_iframe=true');
	    	
			window.original_send_to_editor = window.send_to_editor;
			window.send_to_editor = function(html) {
				
				if(imgfield)  {
					
					var mediaurl = $('img',html).attr('src');
					$('#'+imgfield).val(mediaurl);
					var img_tag = "<img src="+ attachment.url +" />";
					$('#'+showfield).html(img_tag);
					tb_remove();
					imgfield = '';
					
				} else {
					
					window.original_send_to_editor(html);
					
				}
			};
	    	return false;
	    	
		} else { 
		
			var file_frame;
			//window.formfield = '';
			
			//new media uploader
			var button = jQuery(this);
	
			//window.formfield = jQuery(this).closest('.file-input-advanced');
		
			// If the media frame already exists, reopen it.
			if ( file_frame ) {
				//file_frame.uploader.uploader.param( 'post_id', set_to_post_id );
				file_frame.open();
			  return;
			}
	
			// Create the media frame.
			file_frame = wp.media.frames.file_frame = wp.media({
				frame: 'post',
				state: 'insert',
				//title: button.data( 'uploader_title' ),
				/*button: {
					text: button.data( 'uploader_button_text' ),
				},*/
				multiple: false  // Set to true to allow multiple files to be selected
			});
	
			file_frame.on( 'menu:render:default', function(view) {
		        // Store our views in an object.
		        var views = {};
	
		        // Unset default menu items
		        view.unset('library-separator');
		        view.unset('gallery');
		        view.unset('featured-image');
		        view.unset('embed');
	
		        // Initialize the views in our view object.
		        view.set(views);
		    });
	
			// When an image is selected, run a callback.
			file_frame.on( 'insert', function() {
	
				var selection = file_frame.state().get('selection');
				selection.each( function( attachment, index ) {
					attachment = attachment.toJSON();
					if(index == 0){
						// place first attachment in field
						
						$('#'+imgfield).val(attachment.url);
						var img_tag = '<img src="'+ attachment.url +'" />';
						
						$('#'+showfield).html(img_tag);
					} else{
						$('#'+imgfield).val(attachment.url);
					}
				});
			});
	
			// Finally, open the modal
			file_frame.open();
			
		}
    });
});