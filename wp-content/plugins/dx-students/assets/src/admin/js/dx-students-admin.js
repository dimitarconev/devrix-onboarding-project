(function ($) {
	'use strict';
	/**
	 * All of the code for plugin admin JavaScript source
	 * should reside in this file.
	 *
	 * Note: It has been assumed we will write jQuery code here, so the
	 * $ function reference has been prepared for usage within the scope
	 * of this function.
	 *
	 * This enables us to define handlers, for when the DOM is ready:
	 *
	 * $(function() {
	 *
	 * });
	 *
	 * When the window is loaded:
	 *
	 * $( window ).load(function() {
	 *
	 * });
	 *
	 * ...and/or other possibilities.
	 *
	 * Ideally, it is not considered best practise to attach more than a
	 * single DOM-ready or window-load handler for a particular page.
	 */
	 $(document).on("click", "input[class='ajax_students_checkbox']", function (e) {
		let checked = e.target.checked ;
		let name = e.target.name;
		var data = {
			'action': 'update_students_ajax_options',
			'checked': checked,
			'name': name
		};
		$.post(ajaxurl, data, function(response) {

			$( '#save_result' ).html( "<div id='saveMessage' class='success'>Settings save success</div>" );
			
		});
   	});

	   $(document).on("click", "input[class='students-column-active-checkbox']", function (e) {
		let checked = e.target.checked ;
		let post_id = e.target.value;
		var data = {
			'action': 'update_single_student_active_status',
			'checked': checked,
			'id': post_id
		};
		$.post(ajaxurl, data, function(response) {
			
		});
   	});

})(jQuery);