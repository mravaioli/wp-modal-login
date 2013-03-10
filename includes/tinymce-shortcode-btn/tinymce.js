function insertShortcode() {

	var login_text = $( '#login-text' ).val();
	var logout_text = $( '#logout-text' ).val();
	var logout_url = $( '#logout-url' ).val();

	var shortcode_val = '[wp-modal-login';

	if ( login_text ) {
		shortcode_val += ' login_text="' + login_text + '"';
	}

	if ( logout_text ) {
		shortcode_val += ' logout_text="' + logout_text + '"';
	}

	if ( logout_url ) {
		shortcode_val += ' logout_url="' + logout_url + '"';
	}

	shortcode_val += ']';



	if ( window.tinyMCE ) {
		window.tinyMCE.execInstanceCommand( 'content', 'mceInsertContent', false, shortcode_val );

		// Peforms a clean up of the current editor HTML.
		// tinyMCEPopup.editor.execCommand( 'mceCleanup' );

		// Repaints the editor. Sometimes the browser has graphic glitches.
		tinyMCEPopup.editor.execCommand( 'mceRepaint' );
		tinyMCEPopup.close();
	}

	return;
}