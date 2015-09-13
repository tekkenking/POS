/**
* JQUERY AJAX LOGIN PLUGIN
	DEPENDENCIES FILES FUNCTIONS:
	1.	js-debugger.js
		*_debug()
		*typeString(o)
/*
	
	OPTIONS DESCRIBTION

	ajaxloader = This is where the ajax loader image would be displayed [DEFAULT: ''],
	msgPlace: =  This is 
	ajaxStatusMsg: This wil 'error-msg',
	wideAjaxStatusMsg: '',
	msgPlaceFade:0,
	close: false,
	disableSubmitButton:false,
	url:'',
	ajaxType: 'Post',
	responseType:'json',
	dataContent:'',
	redirectDelay: 2000,
	extraContent:'',
	validate: { 
					'quantity_removed': // Name of the field to validate
						[
							// TYPE:ERROR MESSAGE
							{'required':'Field can not be empty'}, 
							{'integer':'value must be number'}
						]
					},

*/
(function (c) {
	"use strict";
	c.fn.extend({
		ajaxrequest : function (options) {
				//e.preventDefault();
			var isCloseModalBox, o, form, msg='', alerttype, ajaxr, errorContainer ='', data = undefined;
			
			o = c.extend({
					ajaxloader:'',
					msgPlace:'',
					wideAjaxStatusMsg: '', // '.error-msg'
					msgPlaceFade:0,
					close: false,
					disableSubmitButton:false,
					url:'',
					ajaxType: 'Post',
					responseType:'json',
					contentType : 'application/x-www-form-urlencoded; charset=UTF-8',
					processData : true,
					cache: true,
					dataContent:'',
					redirectDelay: 2000,
					extraContent:'',
					validate: false,
					pageReload: false,
					ajaxRefresh: false,
					targetHTMLplace:'',
					immediatelyAfterAjax_callback:'',
					beforeAjax_callback:'',
					afterAjax_callback:'',
					clearfields: '',
					
			},  options );
			
			//Setting the wideAjaxStatusMsg to msgPlace which is usually a form child, while wideAjaxStatusMsg is usually a document child
			o.msgPlace = (
							o.wideAjaxStatusMsg !== '' 
								&& 
							c(document).find(o.wideAjaxStatusMsg).text() !== undefined 
						) 
							? o.wideAjaxStatusMsg
							: o.msgPlace;

			//Overwriting the default Ajax message display place
			//_debug(o.msgPlace);
			var newClass = (o.msgPlace !== '' && typeString(o.msgPlace) === 'string') 
				? o.msgPlace.replace(/[\.]+/g,"") 
				: o.msgPlace;

			//Validation process
			if(o.validate !== false){

				//Lets check if 
				var vtype, etype;
				vtype = o.validate;

				if(typeString(o.validate) === 'object'){
					if( o.validate.vtype !== undefined ) vtype = o.validate.vtype;

					if( o.validate.etype !== undefined ) etype = o.validate.etype;
				}

				errorContainer = c(this).validation({
					rules: vtype,
					errorMessageType: etype
				});

				//If error found. Return Error and quit the script from executing further
				if( errorContainer !== '' ){
					msg = errorContainer;
					alerttype = 'danger';
					statusMessage();

					//Would call our assigned function is assigned
					if( o.functionEval !== false ){
						return eval(o.functionEval);
					}

					return false;
				}
			}
			
			o.msgPlaceFade = parseInt(o.msgPlaceFade);
			
			return this.each(function () {
				form =	c(this);

				//clearfields();
				//return false;
				
				//_debug(o.dataContent);

				//Lets disable submit button if not false
				if( o.disableSubmitButton !== false){
					o.disableSubmitButton.addClass('disabled').attr('disabled', 'disabled');
				}
	
				//Show Ajax Loader
				if(c.trim(o.ajaxloader) !== ''){
					//c(o.ajaxloader).removeClass('display-hidden').addClass('display-inline-block');
					c(o.ajaxloader).show();
				}
				
				//Hide message place if shown before
				c(o.msgPlace).html(msg).hide();

					o.url = (o.url !== '') ? o.url : form.attr('action');

					if(o.dataContent !== false && typeString(o.dataContent) !== 'array'){
						o.dataContent = (o.dataContent !== '') ? $.param(o.dataContent) : form.serialize();
					}

					//this would check if the content is array
					if(typeString(o.dataContent) === 'array'){
						o.dataContent = {'javascriptArrayString' : o.dataContent.toString()};
					}

					//We check if extraContent is supplied..
					//If supplied then we make sure normal is also supplied
					//Then we serialize extra content which is in json format
					//The we concatenate extra content with the original content
					if( o.extraContent !== '' && o.dataContent !== ''){
						o.dataContent = o.dataContent + '&' + $.param(o.extraContent);
					}

					if( o.beforeAjax_callback !== '' ){
							var callbacks = c.Callbacks();
							
							callbacks.add( o.beforeAjax_callback );
							callbacks.fire();
						}

					//Ajax process starts here
					ajaxr = c.ajax({
						url 		: o.url,
						type 		: o.ajaxType,
						dataType 	: o.responseType,
						data 		: o.dataContent,
						cache 		: o.cache,
						processData : o.processData,
						contentType : o.contentType,
					});

					/*ajaxr.always(function(data){

					});*/

					ajaxr.done(function(data){
						if( o.immediatelyAfterAjax_callback !== '' ){
							var callbacks = c.Callbacks();
							// add the function "foo" to the list
							//_debug(data);
							callbacks.add( o.immediatelyAfterAjax_callback );
							callbacks.fire(data);
							return false;
						}

						if( o.responseType !== 'json' ){
							c(o.targetHTMLplace).html(data);
							return false;
						}

						if( data.message !== undefined ){
							if( c.isArray(data.message) ){
								c.each(data.message, function(key, value){
										msg +='<div>' + value + '</div>';
								});
							}else{
								msg = '<div>' + data.message + '</div>';
							}
						}

						if( data.url !== undefined && (data.status !== 'error' || data.status !== 'danger')){
							
							if( msg !== '' ){
								alerttype = (data.alerttype === undefined) ? data.status : data.alerttype;
								statusMessage();

								//setInterval(function(){
									//_debug(data.url);
									//return false;
									window.location.replace(data.url);
								//}, parseInt(o.redirectDelay));
							}

						}else if( data.status !== undefined ){
							//If close variable is set to true. Means if status is not a "fail", the modalbox should close
							if( o.close === true && data.status !== 'error'){
								isCloseModalBox = true;
							}
								
						}else{
							//LaraVel validation Json Object is here with deeper array dept
							//We have to check if we are using laravel 3 or laravel 4 and set the appropriate
							data = (data.errors !== undefined) ? data.errors : data;
							c.each(data, function(key, value){ msg +='<div>' + value + '</div>'; });
							
							//We'll manually assign the error class here for twitter bootstrap
							data.status = 'error';
						}

						//Showing the Ajax Error in the assigned container
						//With the assigned twitter_boostrap alert class attribute, from server
						alerttype = (data.alerttype === undefined) ? data.status : data.alerttype;
						statusMessage();

						//Closing modalbox
						if( isCloseModalBox === true ){
							setInterval(function(){
								form.closest('.modal').modal('hide');
								//window.ajaxrefresh();
							}, parseInt(o.redirectDelay));
						}

						//If ajax page refresh and browser refresh is active at the same time? Ajax refresh would be disabled
						if(o.ajaxRefresh !== false && o.pageReload !== false ){o.ajaxRefresh = false;}

						//Browser refresh
						if(o.pageReload === true && data.status === 'success'){
							//_debug(o.pageReload);
							//window.location.href=window.location.href;
							location.reload(true);
						}

						//Ajax refresh NOT YET COMPLETE
						if(o.ajaxRefresh !== false  && data.status === 'success'){
							c(o.ajaxRefresh).ajaxrefresh();
						}

						//Hide Ajax Loader if show
						if(c.trim(o.ajaxloader) !== ''){
							c(o.ajaxloader).hide();
						}

						//To fadeout status message
						if( o.msgPlaceFade > 0 ){
							c(o.msgPlace).delay(o.msgPlaceFade).fadeOut('slow');
						}
						
						//For submit button
						if(o.disableSubmitButton !== false){
							//If submit button is disabled re-enable
							if( o.disableSubmitButton.hasClass('disabled') === true){
								o.disableSubmitButton.removeClass('disabled').removeAttr('disabled', 'disabled');
							}
						}

						//For selected fields clearance after success
						if( o.clearfields !== '' ){
							clearfields();
						}

						//To call the assigned functionEval
						if( o.functionEval !== false ){
							return eval(o.functionEval);
						}

						if( o.afterAjax_callback !== '' && o.afterAjax_callback !== false ){
							
							var callbacks = c.Callbacks();
							// add the function "foo" to the list
							//_debug(data);
							callbacks.add( o.afterAjax_callback );
							callbacks.fire(data);
							return false;
						}

					});

					ajaxr.fail(function(jqXHR, textStatus){
						//_debug(jqXHR);
						if( jqXHR.responseJSON !== undefined ){
							msg = "<div>ERROR:</div><br>";
							c.each(jqXHR.responseJSON.error, function(typex, valuex){
								msg += "<div>" + typex+': ' +valuex + "</div><br>";
							});

						}else{
							//msg = document.createTextNode(jqXHR.responseText);
							msg = jqXHR.responseText;
						}

						alerttype = 'danger';
						statusMessage();
					});

			});

			
			/* SETS OF FUNCTIONS */
			function statusMessage(){
				c(o.msgPlace).html(msg)
				.prop('class', newClass + ' ' + 'alert alert-'+alerttype )
				.show();
			}

			function clearfields(){
				//For clearfields
				var cfields = [];

				if( o.clearfields === true ){
					var ar = form.find('input[type="text"], input[type="password"], textarea, select').not('input[name="_token"]').get();

					$.each(ar, function(i,v){
						cfields.push($(v).attr('name'));
					});

				}else{
					cfields = o.clearfields.split(',');
				}
				
				$.each(cfields, function(i,v){
					var $cf = $('[name="'+ v +'"]');
					if( $cf.is("input") || $cf.is("textarea") ) $cf.val('');//If it's Input type or Textarea
				});
			}
	

		}
	});
})(jQuery);

//Validation here
(function(c){
	"use strict";
	c.fn.extend({
		validation: function(options){
			var o, errorContainer='', fieldx={}, fieldxRules={}, fields, rules, currentName, catchFirstError_element;
			o = c.extend({
				rules:false,
				errorMessageType:'group'
			}, options);

			//We must remove the error class from error fields
			if( o.errorMessageType !== 'group' ){
				c(this).find('.error').removeClass('error');
			}

			var textFields 	= c(this).find(':input').not(':radio, :checkbox');
			var radios 		= c(this).find(':radio');
			var checkboxes 	= c(this).find(':checkbox');

			//If radio or checkbox is with the form.. Then we can't use inline Error message with the validation..
			//So we forcefully set o.errorMessageType = 'group'
			if( (radios.length > 0 || checkboxes.length > 0)  && o.errorMessageType === 'inline'){
				_debug('This form used the validation errorMessageType:"inline", but it can\'t be used because the form contain either radio or checkbox which does not support the inline errorMessageType. errorMessageType being forcefully set to "group".');
				o.errorMessageType = 'group';
			}

			rules = 
			{ 
				required 		: 'is required',
				email 			: 'is invalid',
				integer 		: 'must be only numbers',
				numerical		: 'must be only numbers',
				phone 			: 'must be 11 digits',
				fullname		: 'must be only letters with space and apostroph',
				name			: 'must be letters',
				date 			: 'is invalid. Accepted format is either mm/dd/yyyy or mm-dd-yyyy',
				range 			: 'out of range'
			};

			var types = {
							email		: 	/([\w-\.]+)@((?:[\w]+\.)+)([a-zA-Z]{2,4})/,
							fullname 	: 	/^[A-Za-z][a-zA-Z \']+$/,
							name 		: 	/^[A-Za-z][a-zA-Z \']+$/,
							phone 		: 	/^[(]?\d{4}[)]?\s?-?\s?\d{3}\s?-?\s?\d{4}$/,
							numerical	: 	/^[0-9]+$/,
							integer		: 	/^[0-9]+$/,
							date 		: 	/\b\d{1,2}[\/-]\d{1,2}[\/-]\d{4}\b/,
							//required	: 	//
						};


			//Text inputs Verified
			if( textFields.length > 0 ){

				c.each(textFields, function(i){
					if( c(this).attr('validate') !== undefined ){
						fieldx[c(this).attr('name')] = {};
						currentName = c(this).attr('name');

						c.each(c(this).attr('validate').split('|'), function(v, k){
							if( k.match(/^(range:)[0-9\-]+/) !== null ){ //Catching Range
								var fromAndTo = k.split(':')[1];
								if( fromAndTo !== undefined ){
									var fromAndToArray = fromAndTo.split('-');
									fieldx[currentName]['range'] = {from:fromAndToArray[0], to:fromAndToArray[1], errormessage:rules['range']}
								}
							}else{
								fieldx[currentName][k] = rules[k];
							}
							
						});
					}
				});

				o.rules = c.extend(fieldx, o.rules);
				processValidation(textFields);
			}
//if( o.errorMessageType === 'inline' ){
			//Radio Verified
			if( radios.length > 0 ){
				var groupedRadios = {};
				radios.each(function(i){
					if( groupedRadios[c(this).attr('name')] === undefined ){
							groupedRadios[c(this).attr('name')] = '';
					}

					if(c(this).is(':checked')){
							groupedRadios[c(this).attr('name')] = c(this).val();
						}

				});

				c.each(groupedRadios, function(i, v){
					if( v === '' ){
						errorContainer += '<div>' + i.capitalize().replace('_', ' ') + ' is required</div>';
					}
				});
			}

			if( checkboxes.length > 0 ){
				//processValidation(checkboxes);
			}

			/*return (errorContainer !== '' && errorContainer !== undefined) 
					? errorContainer.capitalize() 
					: errorContainer;*/
					return errorContainer;

			function processValidation(fields){
				c.each(fields, function(i, field){
					//This line is to break out of the loop if an error message found.
					// FEATURE DISABLED BY COMMENTING "return false;"
					//if(errorContainer !== ''){/*return false;*/}

					//Validation started here
					if( o.rules[field.name] ){
						c.each(o.rules[field.name], function(k, v){
							
							if( k === 'required' ){ // Is required
								if( field.value === '' || field.value === undefined || field.value.length < 1 ){
									var fieldname = field.name;
									setMouseFocusonErrorElement(fieldname);
									fieldname = fieldname.capitalize().replace('_', ' ');

									if( o.errorMessageType === 'inline' ){
										inline_error_msg(field.name);
										errorContainer = (errorContainer === '') ? 'Required' : errorContainer;
									}else{
										errorContainer += '<div>'+ fieldname + ' ' + v + '</div>';
									}
									
									return false;
								}
							}

							//Format example: range	 : {from:1, to:10, errormessage:'Out of range'}
							if( k === 'range' ){ // Range
								var isNumber = new RegExp( types['numerical'] );
								//We'll check if the range is numerical
								if( isNumber.test(field.value) !== false ){
									field.value = parseInt(field.value);
									//We check if the number supplied is out of range
									if( field.value < v.from || field.value > v.to ){
										var fieldname = field.name;
										setMouseFocusonErrorElement(fieldname);
										fieldname = fieldname.capitalize().replace('_', ' ');

										if( o.errorMessageType === 'inline' ){
											inline_error_msg(field.name);
											errorContainer = v.errormessage;
										}else{
											errorContainer += '<div>'+ fieldname + ' ' + v.errormessage +'</div>';
										}

										return false;
									}
								}else{
										var fieldname = field.name;
										setMouseFocusonErrorElement(fieldname);
										fieldname = fieldname.capitalize().replace('_', ' ');

										if( o.errorMessageType === 'inline' ){
											inline_error_msg(field.name);
											errorContainer = rules['numerical'];
										}else{
											errorContainer += '<div>'+ fieldname + ' ' + rules['numerical'] + '</div>';
										}
										return false;
								}
							}

							//Other regex validation							
							if( types[k] !== undefined ){
								var pattern = new RegExp( types[k] );
								if( pattern.test(field.value) === false && field.value.length > 0 ){
									var fieldname = field.name;
									setMouseFocusonErrorElement(fieldname);
									fieldname = fieldname.capitalize().replace('_', ' ');

										if( o.errorMessageType === 'inline' ){
											inline_error_msg(field.name);
											//errorContainer = v;
											errorContainer = (errorContainer === '') ? v : errorContainer;
										}else{
											errorContainer += '<div>'+ fieldname + ' ' + v + '</div>';
										}
									return false;
								}
							}

						});
					}
				});
			}

			//Function for setting focus on failed form element
			function setMouseFocusonErrorElement(fieldname){
				//[ catchFirstError_element ] This would save after catching the first error element
				if( catchFirstError_element === undefined){
					catchFirstError_element = fieldname;
					//We select form element by it's name attribute value
					$('[name="'+catchFirstError_element+'"]').trigger('focus');
				}
			}

			function inline_error_msg(fieldname){
				//Lets check if .control-group is in the form field
				if( $('[name="'+fieldname+'"]').closest('.control-group').html() !== undefined ){
					//We add error class if control-group class is in the form field
					$('[name="'+fieldname+'"]').closest('.control-group').addClass('error');
				}else{
					//Else we add the error class directly to the form input, select or textarea
					$('[name="'+fieldname+'"]').addClass('error');
				}
			}

		}
	});
})(jQuery);