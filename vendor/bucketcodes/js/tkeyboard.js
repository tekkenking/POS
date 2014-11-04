/*
* Dependency js files
*
* Jquery.js 
* Jquery ui draggable
* jquery.caret.js v1.5.0 // https://github.com/acdvorak/jquery.caret
* 
*/
//KEYBOARD
(function(c){
	"use strict";
		c.fn.extend({
			tkeyboard: function(options) {
				var o, $write=null, htmlx, htmly, ele, targetDom,  keyCode, charx, $caps=false, $shift=false, cursorPos, currentValue, $this, keybtn;
				o = c.fn.extend({
					keyClass:'',
					draggable: false,
					keyboardID: '',
					switcherID:'keyboard_switcher',
					useSwitcher:false
				}, options);

				//If keyboard ID is not set.. You'll be notified through console or alert popup
				//@return false
				if( o.keyboardID === '' ){ _debug('keyboardID is unknown'); return false; }

				return this.each(function(){
					//We set the keyboard Object by the ID
					ele = c(o.keyboardID);

					targetDom = c(this);

					//If the keyboard switcher is false
					if( o.useSwitcher === false ){
						//We then make sure the keyboard is displayed if class "forcehide" is assigned 
							if( ele.is(':visible') === false ){
								ele.removeClass('forcehide');
								ele.show();
							}
					}else{
						//If switcher is true..
						//Then we'll have to append the HTML code of the switcher to the body of the page
						c('body').append('<div class="btn btn-info keyboard_switcher" id="'+o.switcherID+'"><i class="icon-keyboard"></i></div>');
						
						c('#'+o.switcherID).on('click', function(e){
							ele.toggleClass('forcehide');
						});
					}

					//Enable dragging effect
					if(o.draggable === true) {
						ele.draggable();
						ele.css({'cursor':'move'});
					}
					
					//Select the keyboard keys
					keybtn = c(o.keyboardID + ' button');

					targetDom.on('focus', 'input[type="text"], input[type="password"], textarea, [contenteditable=true]', function(e){

						//We'll assign the focused Element
						$write = c(this);
					});

					//We'll assign optional classes to the Keyboard
					if( ele.attr('data-class') !== undefined ) o.keyClass = ele.attr('data-class');

					keybtn.addClass(o.keyClass);

					keybtn.on('click', function(e){
						e.preventDefault();

						$this = c(this);
						charx = $this.html();

						if( $write === null ) {_debug('No valid input is focused'); return false;}

						//Shift Keys
						if( $this.hasClass('left-shift') || $this.hasClass('right-shift') ){
							c('.letter').toggleClass('uppercase');
							c('.left-shift, .right-shift').find('i').toggleClass('light-green');
							c('.symbol span').toggle();

							$shift = ($shift === true) ? false : true;
							$caps = false;
							return false;
						}

						//Caps Lock
						if( $this.hasClass('capslock') ){
							c('.letter').toggleClass('uppercase');
							$this.find('i').toggleClass('light-green');
							$caps = true;
							return false;
						}

						//Special Chars
						if($this.hasClass('symbol')) charx = c('span:visible', $this).text();
						if($this.hasClass('spacebar')) charx = ' ';
						if($this.hasClass('tab')){ 
							
							if( $write.is('textarea') === false ){
								
								$write.next('input, button').trigger('focus');
								return false;
							}else{
								charx = "\t";
							}
						}
						if($this.hasClass('enter')){
							//If it's false. It means the currently focused is Input field. Enter would submit the parent form
							if( $write.is('textarea') === false ){
								$write.closest('form').submit();
								//.trigger('click')
								//.trigger('keypress');
								return false;
							}else{
								charx = "\n";
							}
						}

						//Uppercase Letter
						if( $this.hasClass('uppercase') ) charx = charx.toUpperCase();

						//Remove Shift once a key is clicked
						if($shift === true){
							$('.symbol span').toggleClass();
							if($caps === false)c('.letter').toggleClass('uppercase');
							$shift = false;
						}

							if($write.attr('contenteditable') !== 'true' ){
								//Get current Value of the focused Input
								currentValue =  $write.val()
								
								//We have to get the Position of the cursor
								cursorPos = $write.caret();

								//We'll write the key character to the target
								if( $this.hasClass('delete') ){
									var cursorPosx = cursorPos - 1;
									cursorPosx = (cursorPosx < 0 ) ? 0 : cursorPosx;
									htmlx = $write.val(currentValue).range(cursorPosx, cursorPos).range('').val();
								}else{
									htmlx = $write.val(currentValue).caret(cursorPos).caret(charx).val();
								}

								$write.val(htmlx)
								.trigger('keyup')
								.trigger('focus');
							}else{
								currentValue = $write.html();

								//We have to get the Position of the cursor
								cursorPos = $write.getCursorPosition();

								htmlx = currentValue.slice(0, cursorPos);
								htmly = currentValue.slice(cursorPos);

								//We detect if it's delete or Not
								htmlx = ($this.hasClass('delete')) 
									? htmlx.substr(0, htmlx.length -1) + htmly 
									: htmlx+charx+htmly;

								$write.html(htmlx);

								$write
								.trigger('keyup')
								.trigger('focus');
							}

					});
				});
			}
		});
})(jQuery);


//Cursor Position
(function(c, undefined){
	c.fn.getCursorPosition = function(){
		var el = c(this).get(0);
		//_debug('Element '+el);
		var pos = 0;

		//_debug(('selectionStart' in el));
		//_debug(('selection' in el));

		if ('selectionStart' in el) {
			pos = el.selectionStart;
		}else if('selection' in document){

			el.focus();
			var Sel = document.selection.createRange();
			var SelLength = document.selection.createRange().text.length;
			Sel.moveStart('character', -el.value.length);
			pos = Sel.text.length - SelLength;
		}
		//_debug(document.selection);
		return pos;
	}
})(jQuery);
