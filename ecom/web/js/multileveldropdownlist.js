/**
 * 	Reference : http://www.bootply.com/nZaxpxfiXz
 */

(function($){
	$(document).ready(function(){
		
		//Make dropdown-menu linkable
		$('ul.dropdown-menu [data-toggle=dropdown]').on('click', function(event) {
			event.preventDefault(); 
			event.stopPropagation(); 
			var href = $(this).attr('href');
			location.href = href;
		});
		
		//Expand sub-menu
		$('ul.dropdown-menu [data-toggle=dropdown]').on('mouseover', function(event) {
			event.preventDefault(); 
			event.stopPropagation(); 
			$(this).parent().siblings().removeClass('open');
			$(this).parent().toggleClass('open');
		});
		
		
	});
})(jQuery);