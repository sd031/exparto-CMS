/*
 * Copyright (c) 2009 MetaYii
 * Licensed under the MIT License: http://www.opensource.org/licenses/mit-license.php
 */
(function($){  
	$.fn.message_center = function(options)
   {
      var id = (options.id.toString() != '') ? '#'+options.id.toString() : '#message_center';

      $(id).css('top', -$(id).innerHeight().toString()+'px');
      $(id).show();
      $(id).animate({top:"0px"}, options.delay);

      $(id+'_close').click(function(){$(id).animate({top:-$(id).innerHeight().toString()+'px'}, options.delay, '', function(){$(id).hide()});});
   }
})(jQuery);