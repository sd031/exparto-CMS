/**
* Anonymous function that is immediately called
* for making sure that we can use the $-shortcut for jQuery.
*/
(function($) {

	/**
	* Rights tooltip plugin.
	* @param Object options Plugin options
	* @return the jQuery element
	*/
	$.fn.tooltip = function(options) {

		// Default values
		var defaults = {
			title: ''
		};

		// Merge the options with the defaults
		var settings = $.extend(defaults, options);

		// Run this for each selected element
		return this.each(function() {

			var $this = $(this);
			var title = this.title;
			var $tooltip;

			// Make sure the item has a title
			if( $this.attr('title').length>0 ) {

				// Empty the title
                this.title = '';

                // Actions to be taken when hovering
                $this.hover(function(e) {

                	// Build the tooltip and append it to the body
					$tooltip = $('<div id="rightsTooltip" />')
					.appendTo('body')
					.hide();

					// Check if we have a title
					if( settings.title.length>0 ) {
						// If so, append it to the tooltip
						$('<div class="heading" />')
						.appendTo($tooltip)
						.html(settings.title);
					}

					// Append the content to the tooltip
					$('<div class="content" />')
					.appendTo($tooltip)
					.html(title);

					// Set the tooltip position and fade it in
					$tooltip.css({
						top: e.pageY+10,
						left: e.pageX+20
					})
					.fadeIn(350);
                }, function() {

                	// Remove the tooltip
                    $tooltip.remove();
                });

                // Bind a mouse move function
                $this.mousemove(function(e) {

                	// Move the tooltip relative to the mouse
	                $tooltip.css({
	                    top: e.pageY+10,
	                    left: e.pageX+20
	                });
            	});
            }
		});
	};

})(jQuery);