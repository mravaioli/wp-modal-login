jQuery(document).ready(function($) {

	// Load the modal window
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
	$('.close-btn').click(function() {
		$('#overlay, .login-popup').fadeOut('300m', function() {
			$('#overlay').remove();
		});

		return false;
	});


	// Display our different form fields when buttons are clicked
	$('.wpml-content:not(:first)').hide();
	$('.wpml-nav').click(function(e) {

		// Remove any messages that currently exist.
		$('.wpml-content > p.message').remove();

		// Get the link set in the href attribute for the currently clicked element.
		var form_field = $(this).attr('href');

		$('.wpml-content').hide();
		$('.section-container ' + form_field).fadeIn('fast');

		e.preventDefault();

		if(form_field === '#login') {
			$(this).parent().fadeOut().removeClass().addClass('hide-login');
		} else {
			$('a[href="#login"]').parent().removeClass().addClass('inline').fadeIn();
		}
	});


	// Run our login ajax
	$('#login-box #form').on('submit', function(e) {

		// Stop the form from submitting so we can use ajax.
		e.preventDefault();

		// Check what form is currently being submitted so we can return the right values for the ajax request.
		var form_id = $(this).parent().attr('id');

		// Remove any messages that currently exist.
		$('.wpml-content > p.message').remove();

		// Display our loading message while we check the credentials.
		$('.wpml-content > h2').after('<p class="message notice">' + wpml_script.loadingmessage + '</p>');

		// Check if we are trying to login. If so, process all the needed form fields and return a faild or success message.
		if ( form_id === 'login' ) {
			$.ajax({
				type: 'GET',
				dataType: 'json',
				url: wpml_script.ajax,
				data: {
					'action'     : 'ajaxlogin', // Calls our wp_ajax_nopriv_ajaxlogin
					'username'   : $('#form #login_user').val(),
					'password'   : $('#form #login_pass').val(),
					'rememberme' : $('#form #rememberme').val(),
					'login'      : $('#form input[name="login"]').val(),
					'security'   : $('#form #security').val()
				},
				success: function(results) {

					// Check the returned data message. If we logged in successfully, then let our users know and remove the modal window.
					if(results.loggedin === true) {
						$('.wpml-content > p.message').removeClass('notice').addClass('success').text(results.message).show();
						$('#overlay, .login-popup').delay(5000).fadeOut('300m', function() {
							$('#overlay').remove();
						});
						window.location.href = wpml_script.redirecturl;
					} else {
						$('.wpml-content > p.message').removeClass('notice').addClass('error').text(results.message).show();
					}
				}
			});
		} else if ( form_id === 'register' ) {
			$.ajax({
				type: 'GET',
				dataType: 'json',
				url: wpml_script.ajax,
				data: {
					'action'   : 'ajaxlogin', // Calls our wp_ajax_nopriv_ajaxlogin
					'username' : $('#form #reg_user').val(),
					'email'    : $('#form #reg_email').val(),
					'register' : $('#form input[name="register"]').val(),
					'security' : $('#form #security').val()
				},
				success: function(results) {
					if(results.registerd === true) {
						$('.wpml-content > p.message').removeClass('notice').addClass('success').text(results.message).show();
						$('#register #form input:not(#user-submit)').val('');
					} else {
						$('.wpml-content > p.message').removeClass('notice').addClass('error').text(results.message).show();
					}
				}
			});
		} else if ( form_id === 'forgotten' ) {
			$.ajax({
				type: 'GET',
				dataType: 'json',
				url: wpml_script.ajax,
				data: {
					'action'    : 'ajaxlogin', // Calls our wp_ajax_nopriv_ajaxlogin
					'username'  : $('#form #forgot_login').val(),
					'forgotten' : $('#form input[name="forgotten"]').val(),
					'security'  : $('#form #security').val()
				},
				success: function(results) {
					if(results.reset === true) {
						$('.wpml-content > p.message').removeClass('notice').addClass('success').text(results.message).show();
						$('#forgotten #form input:not(#user-submit)').val('');
					} else {
						$('.wpml-content > p.message').removeClass('notice').addClass('error').text(results.message).show();
					}
				}
			});
		} else {
			// if all else fails and we've hit here... something strange happen and notify the user.
			$('.wpml-content > p.message').text('Something  Please refresh your window and try again.');
		}
	});
});