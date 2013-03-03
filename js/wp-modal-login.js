jQuery(document).ready(function($) {
	$('a.login-window').click(function() {

		// Get the value in the href of our button.
		var login_id = $(this).attr('href');

		// Add our overlay to the body and fade it in.
		$('body').append('<div id="overlay"></div>');
		$('#overlay').fadeIn(300);

		// Fade in the modal window.
		$(login_id).fadeIn(300);

		// center our modal window with the browsers.
		var margin_left = ($(login_id).width() + 24) / 2;
		var margin_top = ($(login_id).height() + 24) / 2;

		$(login_id).css({
			'margin-left' : -margin_left,
			'margin-top' : -margin_top
		});

		return false;
	});

	// Close the modal window and overlay when we click the close button or on the overlay
	$('.close-btn, #overlay').on('click', function() {
		$('#overlay, .login-popup').fadeOut('300m', function() {
			$('#overlay').remove();
		});

		return false;
	});
});