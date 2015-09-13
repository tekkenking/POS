//Manually sets the token details of the customer
function manualSetToken(data){
	if( data !== undefined && data.detail !== undefined && data.detail !== null ){
    	var button = '<br><p><button id="use-token-button" class="btn btn-purple">Click here to use Token-ID: <span id="token-id">'+ data.detail+'</span></button></p>';
    	$('#foundTokenAppend').append(button);
	}
}

//Auto sets the token details of the customer
function autoSetToken(data, saveCartUrl){
	if( data !== undefined && data !== null ){
	   $('#token-id').attr('title', data.detail).text( data.detail );
        saveCart(saveCartUrl, {
            customer_token : data.detail
        });
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

//Save the current presence of the receipt for about 10mins
function saveCart(url, jsondata){
    $(document).ajaxrequest({
        dataContent: jsondata,
        url:url
    });
}

function updateCartInfo(e, elemTr, saveCartUrl){
    var parentx, id, $dis;
    if( e != undefined && e != null ){
        e.stopPropagation();

        //We first get the TR we are working on.
        parentx = elemTr.closest('tr');
    }else{
        parentx = elemTr;
    }

    //We get the class number of this tr
    id = parentx.attr('idx');
    $dis = parentx.find('.quantity');
    //_debug(id);

    //SaleMode
    var salesmodename = parentx.find('td.salesmodename').text();
    //Then we get the current quantity, before loosing focus(blur)
    var currentQuantity = $dis.find('input').val();

    //We set the hidden_quantity. For the purpose of refresh to get the correct quantity
    set_input_value($dis, currentQuantity);
    //$(this).find('input').val(currentQuantity);
    //Then we get the current Unitprice then unformat the money state
    /*var currentUnitPrice = ( parentx.find('span.discount_price_on_fly').text() !== '' ) 
                            ? unformat_money(parentx.find('span.discount_price_on_fly').text())
                            : unformat_money(parentx.find('span.unitprice').text());*/
    var currentUnitPrice = unformat_money(parentx.find('span.unitprice').text());

    //Then we get the current discount then convert to Int( Just to be careful )
    var currentDiscount = parseInt(parentx.find('span.discount').text());

    //We get the quantity in stock
    var quantityinstock = parseInt(parentx.find('.quantityinstock').text());
    //Item category type
    var cat_type = parentx.find('.cat_type').text();

    if ($('tr[idx='+id+']').get().length >= 1){
        //Lets get there quantity;
        var totalChosenQty = 0;
        $('tr[idx='+id+'] td.quantity input').each(function(){
            totalChosenQty += ($(this).val() * 1);
        });

        //_debug(totalChosenQty);
        //_debug(quantityinstock);

        //We check the total amount of quantity entered if it's greater than what's in stock
        if( totalChosenQty > quantityinstock && cat_type === 'product' ){
            bootbox.alert('The quantity you entered is more than quantity in stock. Enter '+ quantityinstock +' or less before you can checkout');

            if( $('tr[idx='+id+']').hasClass('red') === false ) $('tr[idx='+id+']').addClass('red');

            //Lets update the Item in our Cart and Enable or Disable Checkout button
            updateCartAndActivateCheckoutButton(false);
            return false;
        }else{
            if( $('tr[idx='+id+']').hasClass('red') !== false )
             $('tr[idx='+id+']').removeClass('red');
        }
    }

    //If the currentQuantity is not a number and is not lesser than 1 and is not invalid Float
    //We'll process the updated Total price
    //Else we'll popup a modal-box; describing the error occured
    //_debug( parseInt(currentQuantity) === currentQuantity );
    if(parseInt(currentQuantity) === +currentQuantity && currentQuantity >= 1 ){

        var updatedPrice = (currentUnitPrice - (currentUnitPrice * currentDiscount/100)) * parseInt(currentQuantity);

        if( parentx.hasClass('red') ) parentx.removeAttr('class');

        parentx.find('span.total').text(format_money(updatedPrice,2));

        var updatedsubtotalamount=0;
        //This would update total amount
        $('span.total').each(function(i){
            updatedsubtotalamount += unformat_money($(this).text());
        });

        $('.subtotalamount').text(format_money(updatedsubtotalamount, 2));

        //Lets update the Item in our Cart and Enable or Disable Checkout button
        updateCartAndActivateCheckoutButton();

        //We call a function to calculate the Vat and the Totalamount. RETURN JSON OBJECT
        //vatAndTotalAmount [ function in g56_function.js file ]
        var result = vatAndTotalAmount( updatedsubtotalamount );

        //Getting the total amount tendered after overall discount
        $('.totalamounttendered').text(format_money( getAmountTendered(), 2 ) );

        //Save the current presence of the cart
        var newtr = '<tr idx="'+ id +'">'+ parentx.html() + '</tr>||'+id+'||'+salesmodename;

        saveCart(saveCartUrl, {
            cart_content        :newtr, 
            subtotalamount      :updatedsubtotalamount, 
            totalamount         :result.totalamount, 
            vat                 :result.vat, 
            cart_quantity       :$('.total_items_in_cart').text(),
            enteroveralldiscount: $('.enteroveralldiscount input').val(), 
            totalamounttendered : unformat_money($('.totalamounttendered').text())
        });

    }else{
        bootbox.alert('[ ' + currentQuantity + ' ] is an INVALID Quantity value.');
        if( parentx.hasClass('red') == false ) parentx.addClass('red');
        //Lets update the Item in our Cart and Enable or Disable Checkout button
        updateCartAndActivateCheckoutButton();
    }
}

    //we'll save the new value of input to .hidden_quantity_input at the tr
    function set_input_value(obj, quantity){
        obj.find('input').removeAttr('value').attr('value',quantity)
    }