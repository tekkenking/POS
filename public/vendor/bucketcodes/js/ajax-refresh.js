/********************** AJAX ELEMENT REFRESH *************************/
(function ($) {
	"use strict";
	
	$.fn.extend({
		ajaxrefresh : function(options){
			var o = $.extend({
				timer : false,
			}, options);
			
			//return this.each(function(){
				if( o.timer != false ){
					var ob = setInterval( function(){refresh()}, o.timer );
					//clearInterval(ob);
				}else{
					refresh();
				}
			
			
				function refresh(){
					$.ajax({
						  url: "",
						  context: document.body,
						})
					.done(function(s,x){
							$(this).html(s);
						 });
				}

			//});
		},
	});
})(jQuery);