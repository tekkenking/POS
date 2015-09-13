/** A PRINTING PLUGIN **/
// Dependency on BT2 modalbox plugin
(function($){
	"user strict";
		$.fn.extend({
			printMe : function(options){
				var opt, $element, printPlugin, conf;
				opt = $.extend({
					preview : true, // true | false | only
					previewContainer: '#myModal',
					previewContainerWidth: '400px',
					previewTitle: 'Print preview',
					printButton: 'Print',
					printx: '', // Highly required for printing
				}, options);

				return this.each(function(){
					$element = $(this);

					//We check for the settings of printx
					if( opt.printx === '' ){
						alert('printx must be assign a Jquery object.. example[ printx : { plugin:"printThis", options: {opt1: A, opt2: B, opt3: B} } ] . Where | PLUGIN | = Means the jquery printing plugin you want to use. | OPTIONS | = Means the options of the jquery plugin if any');
						return false;
					}

					conf = opt.printx;
					//printPlugin = conf.plugin;
					printOpt = conf.options;

					//Only preview don't print
					if( opt.preview === 'only' ){modalBoxCall(); return false;}

					//Preview and print on clicking 'Print button'
					if( opt.preview === true ){modalBoxCall()}

					//No preview direct print
					if( opt.preview !== true ){callPrint()}

					//print receipt function
					function callPrint(){
						$element.printThis(printOpt);
					}

					//This would print the receipt onclick
					$(document).on('click', '.print-previewx', function(e){
						e.preventDefault();
						e.stopImmediatePropagation();

						callPrint();
					});

					//Receipt preview modalbox
					function modalBoxCall(){
						//var modalClone = $(opt.previewContainer).clone();
						cloneModalbox( $(opt.previewContainer) )
						.css({'width': opt.previewContainerWidth})
						.centerModal()
						.find('.modal-body').html($element)
							.end()
						.find('.modal-header h3')
						.text(opt.previewTitle)
						.css({'color':'white'}).removeClass('red lighter')
							.end()
						.find('.modal-footer > [data-ref="submit-form"]').text(opt.printButton).addClass('print-previewx')
							.end()
						.modal();
					}
				});
			}
		});
})(jQuery);

/** TOGGLES STATUS FOR BOTH SINGLE AND MULTIPLE REQUEST **/
(function($){
	"use strict";
		$.fn.extend({
			toggleStatus : function(options){
				var o, checkboxes =[], ID, URL, pageReload=false;

				o= $.extend({

				});

				return this.each(function(){
					$(this).on('click', function(e){
					e.preventDefault();
					e.stopImmediatePropagation(); // This would stop if from Double event fire
						if( $(this).hasClass('multipletogglestatus') ){
							
							$('input:checked').not('#default_checkbox').each(function(i){
								checkboxes[i] = $(this).val();
								if( URL === undefined ){
									URL = $(this).closest('tr').find('a.togglepublished').attr('status-url');
								}
							});

							if( checkboxes.length == 0 ){
								bootbox.alert('Nothing to publish or unpublish');
								return false;
							}

							//Assigns the IDs
							ID = checkboxes;

							//Reloads the Page for Multiple toggle
							pageReload = true;

							//Ajax Call to update the status in database
							callAjax($(this));

							//Lets empty the saved Arrays
							checkboxes = []; ID = '';
						}

						//Single Toggle
						if( $(this).hasClass('togglepublished') ){
							var ID = $(this).attr('status-id');
							URL = $(this).attr('status-url');

							//Ajax Call to update the status in database
							callAjax($(this));

							$(this).find('i')
							.addClass(function(){
								if( $(this).hasClass('icon-ok-sign') ){
									$(this).removeClass('icon-ok-sign green');
									//that.attr('title','Unpublished for sale')
									return 'icon-minus-sign red';
								}else{
									$(this).removeClass('icon-minus-sign red');
									//that.attr('title','Published for sale')
									return 'icon-ok-sign green';
								}
							});
						}

						function callAjax(that){
						//Ajax request
							that.ajaxrequest({
								dataContent:{id:ID},
								url: URL,
								pageReload: pageReload,
							});
						}

					});

				});
			}
		});

})(jQuery);

/********************** DELETING ITEM *************************/
(function ($) {
	"use strict";
	
	$.fn.extend({
		deleteItemx : function(options){
			var refval, o, checkboxes = [], confirmMessage;

			o = $.extend({
				url:'',
				rollNameClass : '',
				emptyDeleteMsg: 'Nothing was selected',
				pageReload: false,
				ajaxRefresh: false,
				afterDelete: false, // CALLBACK FUNCTION : FALSE;
				afterDelete_args:'',
				highlightedClass: 'khaki-bg',
				confirmationMessage: "You're about to delete <b>[ :nameholder ]</b>. Are you sure?"
			}, options);
			
			return this.each(function(){
				$(this).on('click', function(e){
					e.preventDefault();
					e.stopImmediatePropagation(); // This would stop it from Double event fire

					if( $(this).hasClass('single_delete') ){
						refval = [$(this).closest('tr').find('[name="checkbox"]').val()];

						//Lets highlight the active role
						$(this).closest('tr').addClass(o.highlightedClass);
 
						confirmMessage = (o.rollNameClass === '') 
						?
							$(this).closest('tr').find('td').eq(1).text()
						:
							$(this).closest('tr').find('.'+ o.rollNameClass).text();

						confirmDelete($(this), refval, o.url, confirmMessage, 'single');
						return false;
					}


					if( $(this).hasClass('multiple_delete') ){
						$('input:checked').not('#default_checkbox').each(function(i){
							checkboxes[i] = $(this).val();
						});
							
						confirmMessage = checkboxes.length;

						if(confirmMessage === 0){
							bootbox.alert(o.emptyDeleteMsg);
							return false;
						}

						confirmDelete($(this), checkboxes, o.url, confirmMessage, 'multiple');

						//Very important line.
						//It will reset the number of selected checkbox to 0.
						checkboxes = [];
					}


					function confirmDelete(that, vals, urlDelete, confirmMessage, typex){

						bootbox.dialog(o.confirmationMessage.replace(':nameholder', confirmMessage),
						[
							{
								"label": "No",
								"class": "btn-gray",
								"callback": function(){
									//Lets highlight the active role
									that.closest('tr').removeClass(o.highlightedClass);
								}
							},
							{
								"label": "Yes",
								"class": "btn-danger",
								"callback": function(){
									//Ajax call for deleting the item in database
									$(that).ajaxrequest({
										dataContent: vals,
										url:urlDelete,
										wideAjaxStatusMsg: '.error-msg',
										msgPlaceFade: 3000,
										pageReload: o.pageReload,
										ajaxRefresh: o.ajaxRefresh
									});

									if(typex === 'single'){
										//For removing single item DOM
										$(that).closest('.deletethis').fadeOut('slow', function(){
											$(this).remove();
											afterDelete();
										});
									}

									if(typex === 'multiple'){
										$.each(vals, function(index, id){
										//For removing multiple item DOM
											$('input[value="'+ id +'"]').closest('.deletethis').fadeOut('slow', function(){
												$(this).remove();
												afterDelete();
											});
										});
									}

									function afterDelete(){
										if( o.afterDelete !== false ){
											var callbacks = $.Callbacks();
											callbacks.add(o.afterDelete);
											callbacks.fire(o.afterDelete_args);
										}
									}
								}
							}
						]);
					}

				});
			});
		},
	});
})(jQuery);




/********************** SELECTING ITEM *************************/
// HOW TO USE: <a href="#" class="styledcheckbox" checkbox-value="{{$cat['id']}}"></a>
(function ($) {
	"use strict";
	
	$.fn.extend({
		styledCheckbox : function(options){
			var  o, checkboxValues, that, removedClass, addedClass;

			o = $.extend({
				iconcheck: 		'icon-sign-blank',
				iconchecked: 	'icon-ok',
				iconSize: 		'icon-1x', //Optional size of the icon
				iconExtraAttr: 	'', //Optional attributes in <i>. E.g [style="color:red" id="check"]
				checkedClass: 	'success-text', //The applied styled to the icons on checked
				uncheckedClass: 'white-text', //The applied styled to the icons on unchecked
				name: ''
			}, options);
			
			//return this.each(function(){
				checkboxValues = $(this).attr('checkbox-value');

				if( o.name === '' ){
					bootbox.alert('[ styledcheckbox ]. You must set the name option');
					return false;
				}

				$(this).each(function(i){
					i = $(this).attr('checkbox-value');
					$('[checkbox-value="'+ i +'"]')
					.addClass('white-text')
					.append('<i class="'+ o.iconcheck + ' ' + o.iconSize + '" '+ o.iconExtraAttr +'></i><input type="checkbox" value="'+i+'" name="'+o.name+'" class="hide">');
				});

				//Multiple select
				$('[multiple-checkbox]').on('click',function(e){
					e.preventDefault();

					$('[checkbox-value]').each(function(i){
						toggleSelect($(this));

					});
				});


				//Single select
				$('[checkbox-value]').on('click', function(e){
					e.preventDefault();
					
					toggleSelect($(this));

				});

				function toggleSelect(thisobj){
					that = thisobj.find('input');

					if(that.prop('checked') === false){
						that.prop('checked', true);
						removedClass = o.iconcheck + ' ' + o.uncheckedClass;
						addedClass = o.iconchecked + ' ' + o.checkedClass;
					}else{
						that.prop('checked', false);
						addedClass = o.iconcheck + ' ' + o.uncheckedClass;
						removedClass = o.iconchecked + ' ' + o.checkedClass;
					}

					thisobj.find('i').removeClass(removedClass).addClass(addedClass);
				}

			//});
		},
	});
})(jQuery);

//Load Ajax Tab Content
(function(c){
	"use strict";
		c.fn.extend({
			ajaxLoadTabContent : function(options){
				var o, url, targetDiv, that, paramsx='';
				o = c.fn.extend({
					targetDiv:'',
					url:'',
					extraParamsCallback:'',
					loadInterval:false, // 2000 = 2secs
					loader 		: false,
					loaderTargetPlace : '',
					setExtraGet:false,
					
				}, options);

				//return this.each(function(){

					c(this).on('click', function(e){
						that = c(this);
						e.preventDefault();
							work();
					});

					//Problematic currently
					if( o.loadInterval !== false ){
						var ob = setInterval(function(){
							that = c('.active > a.ajaxable');
							work();
						}, parseInt(o.loadInterval));
					}

					function work(){
						url = (o.url === '') ? that.attr('data-url') : o.url;
						targetDiv = (o.targetDiv === '') ? that.attr('href') : o.targetDiv;
							
						if( o.extraParamsCallback !== '' ){
							paramsx = {mode : eval(o.extraParamsCallback) };
						}

						if( o.setExtraGet !== false ){
							paramsx = eval(o.setExtraGet);
						}
						
						if( o.loader !== false ){
							o.loaderTargetPlace = (o.loaderTargetPlace === '') ? targetDiv : o.loaderTargetPlace;
							c(o.loaderTargetPlace).html(o.loader);
							//return false;
							//c(o.loaderTargetPlace).removeClass('hide');
						}

						that.ajaxrequest({
							url: url,
							dataContent: paramsx,
							ajaxType: 'get',
							responseType: 'html',
							targetHTMLplace: targetDiv,
						});
						//c(targetDiv).load(url);

						if( o.loader !== false && o.loaderTargetPlace !== ''){
							c(o.loaderTargetPlace).html('');
						}
					}
				//});
			}
		});
})(jQuery);

/************* JQUERY POPOVER FROM TWITTER BOOTSTRAP *************/
/*
| REQUIRES: bootstrap.js OR bootstrap_popover.js
*/
jQuery.fn.ajaxablePopover = function(options){
	"use strict";
		var o, query;
		 o = $.fn.extend({
				placement	: 'right',
          		html 		: true,
           		animation	: true,
           		delay		: 0,
           		loader 		: false,
           		selector	: '[data-rel="popover"]',
           		//trigger		: 'hover',
		}, options);

	return  this.each(function(){
		o.trigger = ( o.trigger === 'hover' ) ? 'mouseover' : o.trigger;

		$(this).on(o.trigger, o.selector , function(event) 
		{

			o.trigger = ( o.trigger === 'mouseover' ) ? 'hover' : o.trigger;

		    var e = $(this);

		    if (e.data('title') === undefined)
		    {
		        // set the title, so we don't get here again.
		        e.data('title', e.attr('title'));

		       	if( e.data('url') !== undefined ){ //Means Ajax request is required
		       		
		       		if( o.loader !== false ){
			       		// set a loader image, so the user knows we're doing something
			        	e.data('content', o.loader);
			        	e.popover({ html : true, trigger : o.trigger, delay : o.delay}).popover('show');
			        }

			        // retrieve the real content for this popover, from location set in data-href
			        $.get(e.data('url'),{ name: e.data('name'), id: e.data('id') }, function(response)
			        {
			            // set the ajax-content as content for the popover
			            e.data('content', response);
			            
			            if( o.loader !== false ){
				            // replace the popover
				            e.popover('destroy');
			        	}
			            e.popover({ html : o.html, trigger : o.trigger, animation : o.animation, delay : o.delay, placement : o.placement});

			            // check that we're still hovering over the preview, and if so show the popover
			            if (e.is(':hover'))
			            {
			                e.popover('show');
			            }
			        });
			    }else{
			    	//Means Data-content is set
			    	e.popover({ html : o.html, trigger : o.trigger, animation : o.animation, delay : o.delay, placement : o.placement }).popover('show');
			    }
		    }
		});

	});
};


(function(c){
	"use strict";
		c.fn.extend({
			ttypeahead: function (options) {
				var o, url, states, map, jx, $this = this, queryField, monitorQueryLength;
				o = c.fn.extend({
					url : '',
					csrf_token: '',
					salesMode : 1,
					list_items : 8,
					minlen: 2,
					color: '#444',
					hcolor: '#000',
					updaterTemplate:'',
					highlighterTemplate:'',
					excludeSelected: false, //This would remove the already selected option if set to true
					barcodeLen: 8
				}, options);

				$this = $(this);
				//_debug($this.closest('form'));
			return this.typeahead({
				// OTHER CONFIG
				items: o.list_items,
				minLength: o.minlen,
				
				source: function (query, process){
					url = o.url;

					//License checker
					if(c($this).hasClass('searchproduct') === false){ return false; }

					//This would fetch sale mode
					o.salesMode = c($this).closest('form').find('input[name="salemode"]').val();

					//Query Barcode or Name of the db table
					queryField = $('.qf_radio input:checked').val();

					monitorQueryLength = ( queryField === 'name' ) ? o.minlen : o.barcodeLen;

					if( monitorQueryLength !== false ){
						//var dans = $this.closest('form').attr('id');
						//_debug(dans);
						/*$this.on('keydown',function(e){
							if( e.which == 13 ){ 
								//e.preventDefault(); 
								_debug('tueyd');
							}
						});*/

						if( query.length < monitorQueryLength ){
							return false;
						}
					}

					jx = c.post( url, {	
									'q' 	 : $.trim(query), 
									'_token' : o.csrf_token, 
									'l'		 : o.list_items, 
									'mode'	 : o.salesMode,
									'qf'	 : queryField
								});

					jx.done( function(data) {
						states = [];
						map = {};

						//Lets check if the user is still logged In
						//If he's logged in session as expired(PHP)
						//The ajax response for session key would be 'expired'
						if(data.session === 'expired'){
							//Refreshing the page would make PHP redirect the user to login page.
							location.reload(true);
							return false;
						}

						c.each(data, function (i, val) {
							map[val.id] = val;
							states.push(val.id);
						});
						process(states);
					});
				},

				matcher: function (item) {
					//This would auto add the item to cart if searching by barcode
					if( queryField === 'barcodeid') { 
						//This would set the found Item from the search to the cart
						eval(o.updaterTemplate);

						//This would auto erase the barcode in the search field after it's operation
						$this.val('');
						
						//Then exit this script
						return false; 
					}

					//This would remove any existing product in the receipt from the suggested option in the search box
					if( o.excludeSelected == true &&
						c('.cart-place tbody tr[idx='+ map[item].id).text() !== ']' && 
						c('.cart-place tbody tr[idx='+map[item].id+'] td.salesmodename').text() === o.salesMode ) 
						return false;

					//When searching by "BARCODEID"
					//This would check if the queryString is found in the returned result
				    if (map[item].barcodeid.toLowerCase().indexOf(this.query.trim().toLowerCase()) !== -1) return true;

				    //When searching by "NAME"
					//This would check if the queryString is found in the returned result
				    if (map[item].name.toLowerCase().indexOf(this.query.trim().toLowerCase()) !== -1) return true;
				},


				sorter: function (items) {
					return items;
				   //return items.sort();
				},

				highlighter: function (item) {
					if( o.highlighterTemplate === '' ){
						var regex = new RegExp( '(' + this.query + ')', 'gi' );
   						return  map[item].name.replace( regex, "<strong>$1</strong>" );
					}else{
						var query = this.query;
						
						//This would return the searched Item styled
						return eval(o.highlighterTemplate);
					}
				},

				updater: function (item) {
					//console.log(map[item].categories.type);
					if( map[item].quantity > 0  || map[item].categories.type == 'service'){
						return (o.updaterTemplate === '' ) ? map[item].name : eval(o.updaterTemplate);
					}
				}
								
				
			});
		}
	});
})(jQuery);

/*** CENTER MODAL BOX BASE ON WIDTH ***/
(function (c) {
	"use strict";
	c.fn.extend({
		centerModal : function (options) {

			//We get the width of the container before this plugin called
			var containerWidth = c(this).width();

			//We get the width of the window parenting the modalbox
			var winWidth = c(window).innerWidth();
			
			//We calculate the distance of center from left and right base on the width of the modalbox and the window
			var leftAndRightMargin = ((winWidth - containerWidth) / 2) + "px";

			//Then we'll apply css base on position:absolute
			//The return the object, for further customisation by other plugins or functions
			return c(this).css({'position':'absolute', 'right':leftAndRightMargin, 'left':leftAndRightMargin, 'margin-left':0, 'margin-top':'-20px'});
		}
	});
})(jQuery);


(function(c){
	"use strict";
	c.fn.extend({
		bootstrap_selectoptions: function(options){
			var o, selectoptionsDiv, selectoptionsArray, valueArray, selectoptions, ele, defaultValue='', defaultText='', dropdownID, setValueFrom, attributes;

			o = c.extend({
				btnCls : 'btn-info',
				style : '',
				btnDropStyle:'',
				iconCaret: 'icon-angle-down',
				makeDefaultDuplicate:false, // Duplicate leaves or hide the currently selected from the dropdown
				hideSelected: false, // false | true. If set to true it would hide the selected option from dropdown: THIS WOULD NOT WORK IS "makeDefaultDuplicate"  is set to "true"
			}, options);


			return this.each(function(e){
				//e.preventDefault();
			ele = c(this);

			//Lets first set the Input settings
			setValueFrom 	= ( ele.attr('setvaluefrom') !== undefined ) 
							? ele.attr('setvaluefrom') 
							: 'data-value';
			attributes 		= ele.attr('data-attr') !== undefined 
							? ele.attr('data-attr') 
							: '';
			o.btnCls 	= ele.attr('data-class') !== undefined 
							? ele.attr('data-class') 
							: o.btnCls;


				selectoptionsDiv = ele.attr('data-options');

				selectoptionsArray = selectoptionsDiv.split(',');
				dropdownID = ele.attr('name');
				selectoptions  = '<div class="btn-group '+ o.btnDropStyle +'">';
				selectoptions += '<button data-toggle="dropdown" class="btn dropdown-toggle '+ o.btnCls +'" id='+ dropdownID +' style="'+o.style+'">';
				selectoptions += '<span class="defaultoption"></span> ';
				selectoptions += '<i class="'+ o.iconCaret +' icon-on-right"></i>';
				selectoptions += '</button>';
				selectoptions += '<ul class="dropdown-menu">';
					c.each(selectoptionsArray, function(index, value){

						value = $.trim(value);

						if( value === '' ){ return false; }

						valueArray = value.split(':');
						//_debug(defaultValue);
							if( defaultValue === '' ){
								if( ele.attr('data-default') !== undefined && ele.attr('data-default') === valueArray[1]  ){
					    			defaultValue = valueArray[1];
					    			defaultText = valueArray[0];
					    			//_debug(defaultText);
						    	}else if(ele.attr('data-default') === undefined ){
						    		defaultValue = valueArray[1];
						    		defaultText = valueArray[0];
						    		//_debug(defaultText);
						    	}
							}
						  
					   selectoptions += '<li data-value='+valueArray[1]+'><a href="#">'+valueArray[0]+'</a></li>';
					});
				selectoptions +='</ul>';
				
					
				selectoptions +='<input type="hidden" name="'+ dropdownID +'" value="" setvaluefrom='+ setValueFrom +' '+ attributes +'/>';
				selectoptions += '</div>';

				c(selectoptions)
				.insertBefore(this)
				.find('.defaultoption')
				.attr('data-value', defaultValue)
				.text(defaultText);

				//requires bootstrap_dropdown_button plugin to work;
				c('#'+dropdownID).bootstrap_dropdown_button({
					name: dropdownID,
					hideSelected: ( o.makeDefaultDuplicate === true ) ? false : o.hideSelected,
				});

				defaultValue=''; defaultText='';

			});

			
			//return $them;
		}
	});
})(jQuery);

/***  BOOTSTRAP SELECT OPTION STYLED ***/
(function(c){
	"use strict";
	c.fn.extend({
		bootstrap_dropdown_button: function(options){
			var o = c.extend({
				//cookieName:'',
				name:'', //This would be name set for in hidden Input set within the dropdown
				hideSelected: false, // false | true. If set to true it would hide the selected option from dropdown
			}, options);

			var currentlySelectedObj, currentlySelected, clicked, clickedValue, dataValue=1, targetInput, setValueFrom, ele;

			return this.each(function(){
				ele = c(this);

				currentlySelectedObj = c(this).find('[data-value]');
				currentlySelected = c.trim(currentlySelectedObj.text());
				//c.cookie(o.cookieName,  currentlySelected);//Important for CART items salesmode
				
				setValueFrom = setValueFromFunc();

				setValueFrom = ( setValueFrom === 'data-value' ) 
				? currentlySelectedObj.attr('data-value') 
				: currentlySelected;

				//We hide the selected on pageload if "hideSelected" is set to "true"
				if( o.hideSelected === true ){
					ele.next().find('[data-value='+ currentlySelectedObj.attr('data-value') +']').hide();
				}

				targetInput.val(setValueFrom);

				c(this).next().find('li').on('click', function(e){
					e.preventDefault();

					//We grab the currently selected text
					currentlySelected = c.trim(currentlySelectedObj.text());

					//We also select the currently selected value
					dataValue = currentlySelectedObj.attr('data-value');

					//We select the newly clicked text
					clicked = c.trim(c(this).text());

					//We select the newly clicked option
					clickedValue = c(this).attr('data-value');

					setValueFrom = setValueFromFunc();
					setValueFrom = ( setValueFrom === 'data-value' ) ? clickedValue : clicked;

					//We replace the currently selected value and text with the newly clicked
					currentlySelectedObj.attr('data-value', clickedValue).text(clicked);

					//We also replace the newly clicked with the currently selected
					//c(this).attr('data-value', dataValue).html('<a href="#">'+ currentlySelected +'</a>');

					//We hide the selected, if "hideSelected" is set to "true"
					if( o.hideSelected === true ){
						c(this).hide();

						//Then we show the previously hidden
						ele.next().find('[data-value='+ dataValue +']').show();
					}

					//c.cookie(o.cookieName, clicked); //Important for CART items salesmode
					targetInput.val(setValueFrom);
				});
					
			});

				function setValueFromFunc(){
					targetInput = c('input[name='+o.name+']');
					return setValueFrom = (targetInput.attr('setvaluefrom') !== undefined) 
						? targetInput.attr('setvaluefrom') 
						: 'data-value';
				}
		}
	});
})(jQuery);

//IE modern placeholder behaviour-lik
(function(c){
	"use strict";
		c.fn.extend({
			iePlaceholder: function(options){
				
			// Default Value will halt here if the browser
			// has native support for the placeholder attribute
			var nativePlaceholderSupport = (function(){
				var i = document.createElement('input');
				return ('placeholder' in i);
			})();
			
			if(nativePlaceholderSupport){
				return false;
			}
			//////////////////////////////////////////

				var o = c.extend({
									usedAttr : 'placeholder',
									submitClear : true
								}, options );
				
				return this.each(function(){
					var el = c(this);
					var plv = el.attr(o.usedAttr);

					//This would set up the placeholder like effect on IE <= 9
					c(this).find(':text, :password').each(function(i, value){
						if( c(this).prop('type') == 'text' ){
							if( c(this).val() == '' ){
								c(this).val(c(this).attr(o.usedAttr)).css('color','#999');
							}
						}else{
							if( c(this).val() == '' ){
								c(this).prop('type', 'text').attr('temp_type', 'password');
								c(this).val(c(this).attr(o.usedAttr)).css('color','#999');
							}
						}

					});


					if( o.submitClear === true ){
						//Lets work on the Form submission
						c(this).find('[type="submit"]').on('click',function(){

							el.find(':text, :password').each(function(i, value){
								//If the input value is equal to the placeholder. It means the input is empty. We'll have to reset the input to empty
								if(c(this).val() == c(this).attr(o.usedAttr) || c(this).val() == ''){
									c(this).val('');
								}

							});
						});
					}

					c(this).find(':text, :password').focusin(function(){
						c(this).keypress(function(e){ // on keypress mimick firefox and chrome placeholder behavior for IE
							if(c(this).val() == c(this).attr(o.usedAttr) || c(this).val() == ''){
									c(this).val('').css({'color':'#000'}); // Color

									if(c(this).attr('temp_type') != undefined ){
										c(this).prop('type', 'password');
									}
								
							}
						});
					}).focusout(function(){
						if( c(this).val() == c(this).attr(o.usedAttr) || c(this).val() == '' ){
							c(this).val(  c(this).attr(o.usedAttr) ).css({'color':'#999'}); // Color

							if(c(this).attr('temp_type') != undefined ){
								c(this).prop('type','text').attr('temp_type','password');
							}
						}
					});
				});
				
			}
		});
})(jQuery);

//Fixed Floating 2 decimal Places
function toFixedx(value, precision) {
    var power = Math.pow(10, precision || 0);
    return (Math.round(value * power) / power).toFixed(precision);
}

//Format Money
function format_money(num, precision) {
	precision = (precision === undefined) ? 2 : precision;
    var p = toFixedx(num, precision).split(".");

    //Lets check if it a negative number
    var isNegative = ( p[0] < 0 ) ? true : false;
    	if( isNegative ){ p[0] = -1 * p[0]; }

    var result = format_thousand(p[0]) + '.' + p[1];
    return ( isNegative ) ? '-' + result : result;
}

//Unformat MOney
function unformat_money(currency){
	//if( currency === undefined ) return false;

	return Number(currency.replace(/[^0-9\.-]+/g,""));
}

//This would format thousand
function format_thousand(num){
	var newstr='', count=0, chars;
	num +=""; 
	/*var chars = num.split('').reverse();
    for (x in chars) {
        count++;
        if(count%3 == 1 && count != 1) {
            newstr = chars[x] + ',' + newstr;
        } else {
            newstr = chars[x] + newstr;
        }
    }*/
    newstr = num.replace(/\d{1,3}(?=(\d{3})+(?!\d))/g, "$&,");
    return newstr;
}

//Convert first letter to uppercase
String.prototype.capitalize = function(){
    return this.replace( /(^|\s)([a-z])/g , function(m,p1,p2){ return p1+p2.toUpperCase();
    } );
};

function format_date(df, formatx){
	console.log(df + ' ' + formatx);
	momentFormat = formatx || 'MMM Do';
	return moment(df).format( momentFormat  );
}

//This would unset an array
//Array.prototype.unsetArray = function(value) {
//    if(this.indexOf(value) != -1) { // Make sure the value exists
//        this.splice(this.indexOf(value), 1);
//    }   
//}