jQuery(document).ready(function($){
	/*theme color*/
    $('.wgt-theme-color').wpColorPicker();
	$('.wgt-theme-textcolor').wpColorPicker();
	/*logo*/
	var file_frame,$button = $('.upload-image'),$removeButton = $('.remove-image'), $logoPreview  = $('#logo-preview');

	$button.on( 'click', function( event ){

		event.preventDefault();
		// If the media frame already exists, reopen it.
		if ( file_frame ) {
			file_frame.open();
			return;
		}
		// Create the media frame.
		file_frame = wp.media.frames.file_frame = wp.media({
			title: jQuery( this ).data( 'uploader_title' ),
			button: {
				text: jQuery( this ).data( 'uploader_button_text' ),
			},
			library: {
				type: 'image'
			},
			multiple: false  // Set to true to allow multiple files to be selected
		});
		// When an image is selected, run a callback.
		file_frame.on( 'select', function() {
			// We set multiple to false so only get one image from the uploader
			attachment = file_frame.state().get('selection').first().toJSON();
			console.log(attachment);
			// set input
			$('#wgt_logo').val( attachment.id );
			// set preview
			img = '<img src="'+ attachment.url +'" width="150" height="125"/><br />';
			//img = '<img src="'+ attachment.sizes.thumbnail.url +'" /><br />';
			//img += '<a href="'+ attachment.url +'">' + logoUpload.textFullSize + '</a>';
			$logoPreview.html( img );

		});

		// Finally, open the modal
		file_frame.open();
	});

	$removeButton.on( 'click', function( event ){

		event.preventDefault();

		$('#wgt_logo').val('');
		$logoPreview.html( '' );

	});
});
