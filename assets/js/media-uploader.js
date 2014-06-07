jQuery(document).ready(function($){

	var tonjoo_tom_upload;
	var tonjoo_tom_selector;

	function tonjoo_tom_add_file(event, selector) {

		var upload = $(".uploaded-file"), frame;
		var $el = $(this);
		tonjoo_tom_selector = selector;

		event.preventDefault();

		// If the media frame already exists, reopen it.
		if ( tonjoo_tom_upload ) {
			tonjoo_tom_upload.open();
		} else {
			// Create the media frame.
			tonjoo_tom_upload = wp.media.frames.tonjoo_tom_upload =  wp.media({
				// Set the title of the modal.
				title: $el.data('choose'),

				// Customize the submit button.
				button: {
					// Set the text of the button.
					text: $el.data('update'),
					// Tell the button not to close the modal, since we're
					// going to refresh the page when the image is selected.
					close: false
				}
			});

			// When an image is selected, run a callback.
			tonjoo_tom_upload.on( 'select', function() {
				// Grab the selected attachment.
				var attachment = tonjoo_tom_upload.state().get('selection').first();
				tonjoo_tom_upload.close();
				tonjoo_tom_selector.find('.upload').val(attachment.attributes.url);
				if ( attachment.attributes.type == 'image' ) {
					tonjoo_tom_selector.find('.screenshot').empty().hide().append('<img src="' + attachment.attributes.url + '"><a class="remove-image">Remove</a>').slideDown('fast');
				}
				tonjoo_tom_selector.find('.upload-button').unbind().addClass('remove-file').removeClass('upload-button').val(tonjoo_tom_l10n.remove);
				tonjoo_tom_selector.find('.tom-background-properties').slideDown();
				tonjoo_tom_selector.find('.remove-image, .remove-file').on('click', function() {
					tonjoo_tom_remove_file( $(this).parents('.section') );
				});
			});

		}

		// Finally, open the modal.
		tonjoo_tom_upload.open();
	}

	function tonjoo_tom_remove_file(selector) {
		selector.find('.remove-image').hide();
		selector.find('.upload').val('');
		selector.find('.tom-background-properties').hide();
		selector.find('.screenshot').slideUp();
		selector.find('.remove-file').unbind().addClass('upload-button').removeClass('remove-file').val(tonjoo_tom_l10n.upload);
		// We don't display the upload button if .upload-notice is present
		// This means the user doesn't have the WordPress 3.5 Media Library Support
		if ( $('.section-upload .upload-notice').length > 0 ) {
			$('.upload-button').remove();
		}
		selector.find('.upload-button').on('click', function(event) {
			tonjoo_tom_add_file(event, $(this).parents('.section'));
		});
	}

	$('.remove-image, .remove-file').on('click', function() {
		tonjoo_tom_remove_file( $(this).parents('.section') );
    });

    $('.upload-button').click( function( event ) {
    	tonjoo_tom_add_file(event, $(this).parents('.section'));
    });

});