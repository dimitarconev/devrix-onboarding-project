(function($) {
	'use strict';

	/**
	 * All of the code for plugin public JavaScript source
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

	var pageNumber = 1;


	function load_students( posts_per_page ){
		pageNumber++;

		var data = {
			'action': 'load_more_students',
			'pageNumber': pageNumber,
			'posts_per_page': posts_per_page
		};
		$.post(ajax_posts.ajaxurl, data, function(response) {
			
			var $response = $(response);
				if($response.length){
					$("#students-list").append($response);
					$("#more_posts").hide();
				} else{
					$("#more_posts").attr("disabled",true);
				}
		});
		
		return false;
	}
	$(document).on("click", "div[id='more_posts']", function () { 
		let posts_per_page = $("#posts_per_page").text() ;

		$("#more_posts").attr("disabled",true); 
		load_students( posts_per_page );
		$(this).insertAfter('#students-list'); 

	});
})(jQuery);
