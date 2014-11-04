//Manually sets the token details of the customer
function manualSetToken(data){
	if( data !== undefined && data.detail !== undefined && data.detail !== null ){
    	var button = '<br><p><button id="use-token-button" class="btn btn-purple">Click here to use Token-ID: <span id="token-id">'+ data.detail+'</span></button></p>';
    	$('#foundTokenAppend').append(button);
	}
}

//Auto sets the token details of the customer
function autoSetToken(data){
	if( data !== undefined && data !== null ){
	//_debug(data);
	$('#token-id').attr('title', data.detail).text( data.detail );
	}
}
  
//This function would update the stock alert
function checkStockAlertAfterPayment(url){
	//This is the counter for almost out of stock update
    $.get(url, function(data){
    	if( $('#productAlert .counter_outofstock').text() < data.count ){
    		soundNotification('galaxys5');

            $.bootstrapGrowl("<b><i class='icon-warning-sign'></i> Product(s) almost or out of stock alert!!!</b>", {
              ele: 'body', // which element to append to
              type: 'error', // (null, 'info', 'error', 'success')
              offset: {from: 'top', amount: 45}, // 'top', or 'bottom'
              align: 'center', // ('left', 'right', or 'center')
              width: 250, // (integer, or 'auto')
              delay: 4000, // Time while the message will be displayed. It's not equivalent to the *demo* timeOut!
              allow_dismiss: true, // If true then will display a cross to close the popup.
              stackup_spacing: 10 // spacing between consecutively stacked growls.
            });
        }

        $('#productAlert .counter_outofstock').text(data.count)
    });
}

function soundNotification(sound){
    var protocolx = window.location.protocol, // http:
        hostx = window.location.host, // localhost
        rootx = window.location.pathname.split('/')[1]; // pos

    $.ionSound({
        sounds: [
            "s3whistle",
            "galaxys5",
            "alarming"
        ],
        path: protocolx+'//'+hostx+'/'+rootx+"/vendor/ionsound/sounds/",
        multiPlay: true,
        volume: "1.0"
    });

    $.ionSound.play(sound);
}


//function to calculate the Vat and the Totalamount. RETURN JSON OBJECT
function vatAndTotalAmount(subtotalamount) {
    var vat = subtotalamount * ($('#vat').text()/100);
    $('.vat').text(format_money(vat,2));

    var totalamount = subtotalamount + vat;
    $('.totalamount').text(format_money(totalamount, 2));

    return {vat:vat, totalamount:totalamount};
}

function removeSidebarAndFitPage(){
    /**Removing the sidebar and enlarging the page STARTS**/
    //Lets remove the sidemenu bar
    $('#main-sidemenu').parent().remove();

    //Enlarging the page
    $('#main-content').parent().prop('class','span12');
    /**Removing the sidebar and enlarging the page ENDS**/
}

function cloneModalbox(obj){
    return obj.clone().addClass('myModalCloned').removeAttr('id')
}

//Auto update the calculation of the expenditure profit margin
function update_expenditure_profitmargin(row, status){

    row = (row === undefined) ? '' : row;
    status = (status === undefined) ? '' : status;

    var thisTrAmount, expenditureAmount = 0, salesProfitmargin, markValue
        //We have to calculate the Expenditure profile margin
       // var thisTrAmount = unformat_money(row.find('td span.amount').text());
       $.each($('tbody tr'), function(){
            if( $(this).find('td span.status').text().toLowerCase() !== 'pending' ){
                expenditureAmount += unformat_money($(this).find('td span.amount').text());
            }
       });

    // _debug(expenditureAmount);

        //Expenditure amount
        //expenditureAmount = unformat_money($('.show_expenditure_amount').text());

        //Get total salesProfitMargin
        salesProfitmargin = unformat_money($('.show_sales_profitmargin').text());

        //markValue = ( status === 0 ) 
        //    ? expenditureAmount - thisTrAmount
        //    : expenditureAmount + thisTrAmount

        $('.show_expenditure_amount').text( format_money(expenditureAmount, 2) );

        $('.show_totalprofitmargin').text( format_money( salesProfitmargin - expenditureAmount ,2) );
}

//Function to get the right amount or subamount for calculation
function getAmount(){
    var amount = $('.subtotalamount').text();
    amount = (amount !== '') ? amount : $('.totalamount').text();
    amount = unformat_money(amount);
    return amount;
}

//Function to get the total amount tendered
function getAmountTendered(){
    var amount = getAmount();
    var enteroveralldiscount = unformat_money($('.enteroveralldiscount input').val());
    var amountTendered = amount - enteroveralldiscount;

    if( amountTendered <= 0 ) {
        $('.enteroveralldiscount input').val('0.00');
        return 0.00;
    }else{
        return amountTendered;
    }
}