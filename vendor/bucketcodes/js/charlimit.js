/**
* JQUERY charLimitAndCounter PLUGIN
* Params
*		counterPlace:'',
		charLimit:150,
*/
(function (c) {
	"use strict";
	c.fn.extend({
		charLimitAndCounter : function (options) {
			
			//We declare all the variables we'll use
			var o, counterValue, counterPlace, currentVal, counter, countx, percent, color, colorx, charPercent;
			
			//Joining the options sets and defaults into one array
			o = c.extend( {
					charLimit:150, //Character default limit is 150
					remaining: '', // String Example [ Remaining ]
					buttonCharAct: 1,
					initColor:'#333333',
					halfColor:'orange',
					endColor:'crimson',
					halfNumber:50,
					endNumber:90,
							},  options );
			
			//color is an array variable holding the percentage calculations colors
			color = {
				start : o.initColor,
				half  : o.halfColor,
				end   : o.endColor,
			};
			
			return this.each(function () {
				
				o.charLimit = parseInt(c(this).attr('limit'));
				counterValue = c(this).attr('counter'); // We'll get the counter value
				counterPlace = '[counter-place="'+ counterValue +'"]';

				//_debug(o.charLimit);
				c(counterPlace).text(o.charLimit);
				
				$(this).focus(function(){
					counterValue = c(this).attr('counter'); // We'll get the counter value
					counterPlace = '[counter-place="'+ counterValue +'"]';
				});
				
				//Counter get triggered when keyboard and mouse event is on KEYUP KEYDOWN FOCUS
				$(this).on('keyup keydown keypress focus', function(){
					o.charLimit = parseInt(c(this).attr('limit'));
				
					//Gets the value current lenght. [ INTEGER ]
					counter = o.charLimit - c(this).val().length; 
						//If the length is lesser or equals to the limit specified
                    if( counter >= 0 ){
					
							//This will activate or deactivate the Submit button for post
							onOrOffPostButton(counter, o.buttonCharAct, o.charLimit);
							
							//Colored counter function
							charPercent = o.charLimit - counter;
							colorx = percentageColor(charPercent, o, color);
							
						//If counter is specified
						if( counterPlace != '' ){
							//The remaining character limit and color is assigned to the counter element
							c(counterPlace).text(counter + ' ' + o.remaining).css({'color':colorx});
						}
                    }else{
							//If limit is reached. We'll not allow extra characters
							currentVal = c.trim(c(this).val()).substring(0, o.charLimit);
							c(this).val(currentVal);
						
                    }
                
				});

				//THis will show the updated count on page reload
				if( c(this).val().length != '' ){
					countx = o.charLimit - c(this).val().length;
					
					//This will activate or deactivate the Submit button for post
					onOrOffPostButton(countx, o.buttonCharAct, o.charLimit);
					
					//Colored counter function
					charPercent = o.charLimit - countx;
					colorx = percentageColor(charPercent, o, color);
					
					//The remaining character limit and color is assigned to the counter element on page reload
					c(counterPlace).text(countx + ' ' + o.remaining).css({'color':colorx});
				}
			});
		}
	});
})(jQuery);

//FUNCTION TO ASSIGN THE RIGHT COLOR FOR COUNTER STAGE BY PERCENTAGE
function percentageColor(charPercent, o, color){
	
	//percent is an array variable holding the percentage calculations
	percent = { 
		half : Math.floor((o.charLimit * parseInt(o.halfNumber)) / 100),
		end  : Math.floor((o.charLimit * parseInt(o.endNumber)) / 100),
	};	

	//If the counter is lesser than halfpercent assign the initial color ( o.initColor )
	if( charPercent < percent.half ){	return color.start;	}
	//If the counter is greater or Equals to the current halfpercent Assign o.halfColor
	if( charPercent >= percent.half && charPercent < percent.end ){	return color.half;	}
	//If the counter is greater or Equals to the current endpercent Assign o.endColor
	if( charPercent >= percent.end ){	return color.end;	}
	
}

function onOrOffPostButton(counter, buttonCharAct, charLimit, panelBar){

	buttonCharAct = (buttonCharAct > 0) ? buttonCharAct - 1 : buttonCharAct;

	if( (counter + buttonCharAct) < charLimit ){
		if( $('#post-button').is('.btn-info') == false ){
			$('#post-button').addClass('btn-info').removeAttr('disabled');
		}
	}else{
		if( $('#post-button').is('.btn-info') != false ){
			$('#post-button').removeClass('btn-info').attr('disabled', 'disabled');
		}
	}
}