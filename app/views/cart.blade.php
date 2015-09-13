<div id="cart" style="background:#fff">
	<div class="widget-box invoice-box">

		<div class="widget-header widget-header-small header-color-grey">
		    <div class="row-fluid">
				<div class="span12">
					<div class="span3">
						<div style="">
							<i class="icon-shopping-cart bigger-160"></i>
							<span class="total_items_in_cart badge badge-large badge-success">{{--Cartsession::getCurrent('cart_quantity', 0)--}}</span> Item(s) in Cart
						</div>
					</div>

					<div class="span9">
						<div class="btn-group">
							<button class="customer-token-dropdown btn btn-mini btn-yellow dropdown-toggle" data-toggle="dropdown">Customer actions <span class="caret"></span></button>
							<ul class="dropdown-menu">
							   <li><a class="get-customer-token" href="#"><i class="icon-refresh"></i> Get / Change detail </a></li>
							  <li><a class="register_new_customer" href="#"><i class="icon-pencil"></i> Register new customer </a></li>
							  <li><a class="discard-customer-token" href="#"><i class="icon-eraser"></i> Discard detail</a></li>
							</ul>

						</div>
						
						<span class="token-id" id="token-id" title="" style="color:#ffffff; font-size:14px">{{Cartsession::getCurrent('customer_token')}}</span>
					</div>
				</div>
			</div>
		</div>
			<table class="table cart">
				<thead>
					<tr>
						<!--<th>Brand</th>-->
						<th style="padding-left:5px" class="" width="7%"><div>Qty</div></th>
						<th width="40%"><div>Description</div></th>
						<th width="15%"><div>Unit. Price</div></th>
						<th width="8%"><div>%</div></th>
						<th width="17%"><div>Total</div></th>
						<th width="10%"><div><small>Mode</small></div></th>
						<th class="pull-right"><a href="{{URL::route('clearcart')}}" id="clearCart"><i class="icon icon-trash red"></i></a></th>
					</tr>
				</thead>
			</table>
		<div class="row-fluid cart-place">
			<table class="table table-striped">
				<tbody>
					@if( ($r = Cartsession::getCurrent('cart_content', '')) != '' )
					{{--tt($r)--}}
						@foreach($r as $trs)
							@foreach($trs as $tr)
								{{$tr}}
							@endforeach
						@endforeach
					@endif
					
				</tbody>
			</table>
		</div>

		<div class="amountpaid-table-wrapper">
			<table class="table bolder table-bordered cart-footer">
				<?php $vat = Systemsetting::getx('vat') ?>
				@if($vat > 0 )
				<tr class="sub-total">
					<td class="first" width=80%>
						<div>
							Sub total:
						</div>
					</td>
					<td>
						<span class="red">{{currency()}}<span class="subtotalamount">{{format_money(Cartsession::getCurrent('subtotalamount', 0.00))}}</span>k</span>
					</td>
				</tr>

				<tr>
					<td class="first">
						Vat <span id="vat">{{$vat}}</span>% :
					</td>

					<td>
						<span class="red">{{currency()}}<span class="vat">{{format_money(Cartsession::getCurrent('vat', 0.00))}}</span>k</span>
					</td>
				</tr>
				@endif

				<tr>
					<td class="first" width=80%>
						<div>
							Total amount:
						</div>
					</td>
					<td>
						<span class="red totalamountwrapper">{{currency()}}<span class="totalamount">{{format_money(Cartsession::getCurrent('totalamount', 0.00))}}</span>k</span>
					</td>
				</tr>

				<tr>
					<td class="first">
						<div>
							Entered Overall Discount:
						</div>
					</td>
					<td>
						<div class="input-prepend input-append enteroveralldiscount">
							<?php $overalldiscountValue = Cartsession::getCurrent('enteroveralldiscount', '0.00'); ?>
							<span class="add-on red">{{currency()}}</span>
								<input type="text" name="overalldiscount" style="width: 80px; background:#EEE8AA"  value={{$overalldiscountValue}} />
							<span class="add-on red">k</span>
						</div>
					</td>
				</tr>

				<tr>
					<td class="first">
						<div>
							Total Amount Tendered:
						</div>
					</td>
					<td>
						<div>
							<span class="red totalamounttenderedwrapper">{{currency()}}<span class="totalamounttendered">{{format_money(Cartsession::getCurrent('totalamounttendered', 0.00))}}</span>k</span>
						</div>
					</td>
				</tr>
			</table>
		</div>

	<div id="buttombuttons">
		<div class="pull-left">
			<a href="javascript:void(0)" class="btn btn-light" id="preview_receipt" disabled> Preview Receipt </a>
			<a href="javascript:void(0)" class="btn btn-light" id="suspend_transaction" disabled> Suspend </a>
		</div>
		<div class="pull-right">
			<a href="javascript:void(0)" class="btn btn-light" id="payment" disabled> Make Payment </a>
			<a href="{{URL::route('clearcart')}}" class="btn btn-light" id="new_transaction" disabled> New Transaction </a>
		</div>
	</div>

	</div>
</div>

<script>

//This function auto updates the number of items in Cart
//We update Cart In Either of this three operations:
// REMOVING OF ITEM
// ADDING OF ITEM
// ADJUSTING QUANTITY
function updateCartAndActivateCheckoutButton(shouldUpdateCart){
	var total_items_in_cart=0;
	var disableButton = ( shouldUpdateCart === undefined ) ? false : shouldUpdateCart;

	if( $('.cart-place tbody tr td.quantity').length > 0 ){
			$('.cart-place tbody tr').each(function(){

				if( $(this).hasClass('red') ){
					disableButton = true;
				}

				total_items_in_cart += parseInt($(this).find('input').val());
			});

		if( disableButton === true ){
			bottombuttons('#payment,#new_transaction,#preview_receipt,#suspend_transaction');
			return false;
		}
	}

	$('.total_items_in_cart').text(format_thousand(total_items_in_cart));
	
	if( Number($('.total_items_in_cart').text()) > 0 ){ 
		bottombuttons('', '#payment,#suspend_transaction'); 
	}else{ 
		bottombuttons('#payment,#suspend_transaction'); 
	}
}

//React bottom buttons
function bottombuttons(disablex, enablex){
	if( disablex !== undefined && disablex !== ''){
		disablex = disablex.split(',');
		$.each(disablex, function(index, value){
			$(value).attr('disabled', true).addClass('btn-light').removeClass('btn-yellow');
		});
	}

	if( enablex !== undefined ){
		enablex = enablex.split(',');
		$.each(enablex, function(index, value){
			$(value).removeAttr('disabled', true).removeClass('btn-light').addClass('btn-yellow');
		});
	}
}

$(function(){

	//Register new customer
	var registerNewCustomer = function (e){
		e.preventDefault();

		var $that = $(this), url = "{{URL::route('customerform')}}";

		//Unbind Click event
		$that.off('click.registerNewCustomer', registerNewCustomer);

		$.get(url, function(data) {

			cloneModalbox($('#myModal'))
			.css({'width':'300px'})
			.centerModal()
			.find('.modal-body').html(data)
			.end()
			.find('.modal-header h3')
			.text('Register new customer')
			.end()
			.find('.modal-footer [data-ref="submit-form"]')
			.hide()
			.end()
			.modal({
				backdrop: 'static'
			});

			//Re-bind click event
			$that.on('click.registerNewCustomer', registerNewCustomer);
		});
	};
	$('.register_new_customer').on('click.registerNewCustomer', registerNewCustomer);

	//Search for existing customer
	var getCustomerToken = function (e){
		e.preventDefault();

		var url = "{{URL::route('show_searchCustomerToken')}}", $that = $(this);
			
		$that.off('click.getCustomerToken', getCustomerToken);

		$.get(url, function(data) {

			cloneModalbox( $('#myModal') )
			.css({'width':'400px'})
			.centerModal()
			.find('.modal-body').html(data)
			.end()
			.find('.modal-header h3')
			.text('Search for existing customer')
			.end()
			.find('.modal-footer [data-ref="submit-form"]')
			.hide()
			.end()
			.modal({
				backdrop: 'static'
			});

			$that.on('click.getCustomerToken', getCustomerToken);

		});
	};

	$('.get-customer-token').on('click.getCustomerToken', getCustomerToken);

	var suspendTransaction = function(e){
		e.preventDefault();

		var $that = $(this);

		if( $that.attr('disabled') || Number($('.total_items_in_cart').text()) <= 0 ){
			return false;
		}

		//Unbind click event
		$that.off('click.suspend_transaction', suspendTransaction);
		$that.attr('disabled', 'disabled');

		var modalClone = cloneModalbox( $('#myModal') );
		modalClone
		.find('.modal-body')
		.html("You're about to suspend, item(s) in cart. Are you sure?")
		.end()
		.find('.modal-header h3').text('Suspend cart')
		.end()
		.find('.modal-footer [data-ref="submit-form"]')
		.addClass('confirm-suspend')
		.find('span')
		.text('Suspend')
		modalClone.modal();

		$that.on('click.suspend_transaction', suspendTransaction);
		$that.removeAttr('disabled');
	}

	$('#suspend_transaction').on('click.suspend_transaction', suspendTransaction);

	$('body').on('click', '.confirm-suspend', function(e){
		window.location.href = "{{URL::route('suspendcart')}}";
	});

	//Payment Place
	var paymentx = function (e){
		e.preventDefault();
		e.stopPropagation();

		var $that = $(this);
		
		//Lets check if its not disabled. If disabled return false, before calling the modalbox
		if( $that.attr('disabled') ) return false;

		//Unbind click event
		$that.off('click.paymentx', paymentx);
		
		$that.attr('disabled', 'disabled');

		var url = "{{URL::route('makepayment')}}";
		var modalClone = cloneModalbox( $('#myModal') );
		$.get(url, function(data){
	
			modalClone
			.css({'width':'850px'})
			.centerModal()
			.find('.modal-body').html(data)
			.end()
			.find('.modal-footer [data-ref="submit-form"] > i')
			.next()
			.text('Pay')
			.closest('button')
			.addClass('record_payment');

			modalClone.modal({
				backdrop: 'static'
			});

			//Re-bind click event
			$that.on('click.paymentx', paymentx);
		});
		
		$that.removeAttr('disabled');
	};
	$('#payment').on('click.paymentx', paymentx);

//This would preview the receipt of the recent sale
//Modalbox
	var previewReceipt = function (e){
		e.preventDefault();
		var $that = $(this);

		//Lets check if its not disabled. If disabled return false, before calling the modalbox
		if( $that.attr('disabled') ) return false;

		$that.off('click.previewReceipt', previewReceipt);

		var url = "{{URL::route('previewreceipt')}}";
		var modalClone = cloneModalbox( $('#myModal') );

		$.get(url, function(data){

			modalClone
			.css({'width':'850px'})
			.centerModal()
			.find('.modal-body').html(data)
			.end()
			.find('.modal-header h3').text('Preview & Print receipt')
			.end()
			.find('.modal-footer [data-ref="submit-form"] > i')
			.next()
			.text('Print receipt')
			.closest('button')
			.removeClass('record_payment')
			.addClass('print-previewx')
			.end();

			modalClone.modal();

			$that.on('click.previewReceipt', previewReceipt);
		});
	}

	$('#preview_receipt').on('click.previewReceipt', previewReceipt);


//This would prepare the Cart for New transaction
	$('#new_transaction').on('click', function(e){
		e.preventDefault();
		if($(this).attr("disabled") === undefined){
			window.location.href = "{{URL::route('clearcart')}}";
			return false;
		}
	});

	$('.discard-customer-token').on('click', function(e){
		e.preventDefault();
			$('#token-id').text('--');
			saveCart("{{URL::route('autoSaveCart')}}", {customer_token:'--'});

	});

	//This code would AUTO adjust the total price; base on the quantity entered and the discount available
	//Edit quantity on the fly
	$(this).on('blur', '.quantity', function(e){
		updateCartInfo(e, $(this), "{{URL::route('autoSaveCart')}}");
	});

	//This will remove product from the listing and also adjust the total amount
	$(this).on('click', '.removeProduct', function(e){
		e.preventDefault();
		//We first get the TR we are working on.
		var parentx = $(this).closest('tr');
		var id = parentx.attr('idx');
		//SaleMode
		var salesmodename = parentx.find('td.salesmodename').text();
		//We get the current product total price and unformat_money
		var amount = unformat_money(parentx.find('span.total').text());
		var currentsubtotalamount = getAmount();
		var updatedsubtotalamount = currentsubtotalamount - amount;

		$('.subtotalamount').text(format_money(updatedsubtotalamount, 2));

		//We remove the TR
		parentx.remove();

		//Lets update the Item in our Cart
		updateCartAndActivateCheckoutButton();

		//We call a function to calculate the Vat and the Totalamount. RETURN JSON OBJECT
		var result = vatAndTotalAmount( updatedsubtotalamount );

		//Getting the total amount tendered after overall discount
		$('.totalamounttendered').text(format_money( getAmountTendered(), 2 ) );

		//Save the current presence of the cart
		saveCart("{{URL::route('autoSaveCart')}}", {
			cart_content :'remove||'+id+'||'+salesmodename, 
			subtotalamount:updatedsubtotalamount, 
			totalamount:result.totalamount, 
			vat:result.vat, 
			cart_quantity:$('.total_items_in_cart').text(),
			enteroveralldiscount: $('.enteroveralldiscount input').val(), 
			totalamounttendered: unformat_money($('.totalamounttendered').text())
		});

	});

	//We'll update the total items
	updateCartAndActivateCheckoutButton();

// UPDATE PRICE ON THE FLY
	$('.cart-place > table').on('click', '.cog_unitprice', function(e){
		e.preventDefault();
		e.stopImmediatePropagation();

		var newtr, 
			updatedsubtotalamount, 
			trx, 
			px = $(this).closest('td'),
			value,
			discount,
			disco,
			oldx = px.find('.unitprice'),
			ttv;

		value = prompt('Set new price', '');

		if(value === null){ return false; }

		value = unformat_money(value);

		px.find('.hidden')
		.removeClass('hidden')
		.end()
		.find('.discount_price_on_fly')
		.html(format_money(value,2));

		//Lets work on Percentage value
		discount = (value * 100) / unformat_money(oldx.text());

		if( discount > 100 ){
			px.next('td.discount').find('.discount').text(0);
			//return false;
		}else{
			disco = 100 - discount;
			disco = disco + '';

			if( disco.length > 5){
				disco = format_money(disco);
			}

			px.next('td.discount').find('.discount').text(disco);
		}

		//Lets work on Total price
		trx = px.closest('tr');
		ttv = trx.find('td.quantity input').val() * value;
		trx.find('td.total .total').text(format_money(ttv,2));

		//This would update total amount
		updatedsubtotalamount=0;
		$('.cart-place span.total').each(function(i){
			updatedsubtotalamount += unformat_money($(this).text());
		});
		$('.subtotalamount').text(format_money(updatedsubtotalamount, 2));
		$('.totalamount').text(format_money(updatedsubtotalamount, 2));

		//Getting the total amount tendered after overall discount
		$('.totalamounttendered').text(format_money( getAmountTendered(), 2 ) );

		//Save the current presence of the cart
		var idx = trx.attr('idx');
		var salesmodenamex = trx.find('td.salesmodename').text();
		newtr = '<tr idx="'+ idx +'">'+ trx.html() + '</tr>||'+ idx +'||'+salesmodenamex;

		saveCart("{{URL::route('autoSaveCart')}}", {
			cart_content	:newtr, 
			//subtotalamount	:updatedsubtotalamount, 
			totalamount 	:updatedsubtotalamount, 
			//cart_quantity 	:$('.total_items_in_cart').text(),
            //enteroveralldiscount: $('.enteroveralldiscount input').val(), 
            totalamounttendered : unformat_money($('.totalamounttendered').text())
		});

		newtr = '';
		updatedsubtotalamount = '';
	});

	// REMOVE THE NEW UPDATED PRICE ON THE FLY
	$('.cart-place > table').on('click', '.remove_discount_price_on_fly', function(e){
		e.preventDefault();
		
		var $parentv = $(this).closest('tr');
		var quantityv = $parentv.find('td.quantity .quantity_input').val();
		var unitpricev = $parentv.find('td.unitprice span.unitprice').removeClass('oldUnitPrice').text();

		//Lets resign the total unit price
		$parentv.find('td.total .total').text(format_money( quantityv * unformat_money(unitpricev) ));

		//Lets reset percentage back to 0
		$parentv.find('td.discount span.discount').text(0);

		//This would update total amount
		var updatedsubtotalamount=0;
		$('.cart-place span.total').each(function(i){
			updatedsubtotalamount += unformat_money($(this).text());
		});
		$('.subtotalamount').text(format_money(updatedsubtotalamount, 2));
		$('.totalamount').text(format_money(updatedsubtotalamount, 2));

			//Getting the total amount tendered after overall discount
			$('.totalamounttendered').text(format_money( getAmountTendered(), 2 ) );

		//Then lets remove the discounted price
		$parentv.find('.discount_price_on_fly').text('')
		.parent('span')
		.addClass('hidden');

		//Save the current presence of the cart
		var idx = $parentv.attr('idx');
		var salesmodenamex = $parentv.find('td.salesmodename').text();
		var newtr = '<tr idx="'+ idx +'">'+ $parentv.html() + '</tr>||'+ idx +'||'+salesmodenamex;
		saveCart("{{URL::route('autoSaveCart')}}", {
			cart_content:newtr, 
			subtotalamount:updatedsubtotalamount, 
			totalamount:updatedsubtotalamount, 
			cart_quantity:$('.total_items_in_cart').text(),
			enteroveralldiscount: $('.enteroveralldiscount input').val(), 
			totalamounttendered: unformat_money($('.totalamounttendered').text())
		});

	});

	//Adjust the Total Amount Tendered when giving discount
	$('.enteroveralldiscount input').on('keyup', function(e){

			//Getting the total amount tendered after overall discount
			var efx = getAmountTendered();

			if(isNaN(efx) === true){
				bootbox.alert('Overall discounted value is incorrect');
				updateCartAndActivateCheckoutButton(true);
				return false;
			}else{
				updateCartAndActivateCheckoutButton(false);
			}

			var ttm = ( efx == 0.00 ) ? unformat_money($('.totalamount').text()) : efx ;
			
			$('.totalamounttendered').text(format_money( ttm, 2 ) );

			saveCart("{{URL::route('autoSaveCart')}}", {
				enteroveralldiscount: $('.enteroveralldiscount input').val(), 
				totalamounttendered: unformat_money($('.totalamounttendered').text())
			});

	});

});
</script>