jQuery(document).ready(function($) {

	var css_box = $('#custom-css').parent().parent();
	var checked = $('#add-custom-css');

	// Check if we have any options to enable the custom css box
	if ( checked.is(':not(:checked)') ) {
		// Hide any instance with this class.
		css_box.hide();
	}

	// Run when the Modal Theme selection is used.
	$('#add-custom-css').change(function() {

		// Check if our theme selection is set to none. If so, load our custom css textarea.
		if( checked.is(':checked') ) {
			css_box.slideDown();
		} else {
			css_box.slideUp();
		}
	});
});