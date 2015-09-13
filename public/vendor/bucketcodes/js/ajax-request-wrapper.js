/*
* YOU MUST SET THE FOLLOWING
* 
* 	<form id="formid">

		// ITS A MUST SET WITHIN THE FORM
		<div class="error-msg" id="businessname-message" style="margin-bottom:5px"></div>
		<div class="ajaxloader" id="businessname-message-ajaxloader"></div>

*	</form>
*
*/
(function($){
	"use strict";
	$.fn.extend({
		ajaxrequest_wrapper : function (options) {
			var formID, formaction, aloader, mplace, o;
			o = $.extend({
						msgPlaceFade:0,
						redirect: false,
						ajaxStatusMsg: '.error-msg',
						wideAjaxStatusMsg : '',
						close: false,
						url:'',
						customFormID: '',
						ajaxType: 'Post',
						responseType: 'json',
						contentType : 'application/x-www-form-urlencoded; charset=UTF-8',
						processData : true,
						cache: true,
						dataContent:'',
						extraContent:'',
						disableSubmitButton: false,
						redirectDelay: 2000,
						validate: false,
						pageReload: false,
						ajaxRefresh: false,
						functionEval: false,
						immediatelyAfterAjax_callback:'',
						beforeAjax_callback:'',
						afterAjax_callback:'',
						clearfields:'',
			},options);
			
			return this.each(function(){
			//	$(this).on('click',function(e){
			//		e.preventDefault();

				formID = (o.customFormID === '') ? $(this).closest('form').attr('id') : o.customFormID;
				
				//_debug($(this).closest('form').attr('id'));

				formaction = $('#' + formID);
				//mplace = (o.wideAjaxStatusMsg == '') 
				//	? '#' + formaction.find(o.ajaxStatusMsg).prop('id')
				//	: '#' + $(o.wideAjaxStatusMsg).prop('id');

				mplace = (o.wideAjaxStatusMsg == '')  ? o.ajaxStatusMsg : o.wideAjaxStatusMsg;

					//_debug(mplace);

				aloader = '#' + formaction.find('.ajaxloader').prop('id');

				if( o.disableSubmitButton == true ){
					o.disableSubmitButton = $(this);
				}

				formaction.ajaxrequest({
					ajaxloader 	: 					aloader,
					msgPlace 	: 					mplace,
					//ajaxStatusMsg: 				o.ajaxStatusMsg,
					wideAjaxStatusMsg: 				o.wideAjaxStatusMsg,
 					disableSubmitButton: 			o.disableSubmitButton,
					msgPlaceFade: 					o.msgPlaceFade,
					redirect: 						o.redirect,
					close: 							o.close,
					url: 							o.url,
					contentType : 					o.contentType,
					processData : 					o.processData,
					cache: 							o.cache,
					ajaxType: 						o.ajaxType,
					responseType: 					o.responseType,
					dataContent: 					o.dataContent,
					extraContent: 					o.extraContent,
					redirectDelay: 					o.redirectDelay,
					validate: 						o.validate,
					pageReload: 					o.pageReload,
					ajaxRefresh: 					o.ajaxRefresh,
					functionEval: 					o.functionEval,
					immediatelyAfterAjax_callback: 	o.immediatelyAfterAjax_callback,
					beforeAjax_callback: 			o.beforeAjax_callback,
					afterAjax_callback: 			o.afterAjax_callback,
					clearfields: 					o.clearfields,

				});	
			//	});
				
			});
			
		}
	});
	
})(jQuery);