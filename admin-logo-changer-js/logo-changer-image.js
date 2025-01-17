( function($) {
	var file_frame;
	$( '#logo-changer-table' )
		.on( 'click', '.set-image', function(e) {
		    e.preventDefault();

			// Let's start over to make sure everything works
		    if ( file_frame )
		        file_frame.remove();

		    file_frame = wp.media.frames.file_frame = wp.media( {
		        title: $(this).data( 'uploader_title' ),
		        button: {
		            text: $(this).data( 'uploader_button_text' )
		        },
		        multiple: false
		    } );

		    file_frame.on( 'select', function() {
		        var attachment = file_frame.state().get( 'selection' ).first().toJSON();
				$( '#logo-changer-image' ).val( attachment.url );
				$( '#logo-changer-image-container' ).html( '<img src="' + attachment.url + '" alt="" style="max-width:100%;" />' );
		    } );

		    file_frame.open();
		    $( '.remove-image' ).show();
		} )
		.on( 'click', '.remove-image', function(e) {
		    e.preventDefault();
		    $(this).hide();
			$( '#logo-changer-image' ).val( '' );
			$( '#logo-changer-image-container' ).html( '' );
		} );

} )(jQuery);