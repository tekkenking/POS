{{ Form::open(array('route'=>'postmakepayment', 'id'=>'postmakepayment', 'class'=>'form-horizontal', 'style'=>'position:relative;' )) }}

<div class="row-fluid">
	<div class="span12">
		<div class="span7">
			<div class="control-group">
				<label class="control-label" for="paymentmethod">Payment methods:</label>
				<div class="controls">
					<select name="paymentmethods" id="paymentmethods" class="span11">
					</select>
				</div>
			</div>

			<div class="control-group">
				<label class="control-label" for="amounttendered">Amount tendered:</label>
				<div class="controls">
					<div class="input-prepend input-append">
						<span class="add-on">{{currency()}}</span>
							{{Form::text('amounttendered', '', array('id'=>'amounttendered', 'class'=>'span6', 'autocomplete'=>'off'))}}
						<button class="btn btn-info btn-small" id="addpayment">
							<i class="icon-plus bigger-120"></i> 
							<span class="bolder">Add payment</span>
						</button>
					</div>
				</div>
			</div>

			<div class="control-group">
				<label class="control-label" for="paymentref">Payment Ref:</label>
				<div class="controls">
					{{Form::text('paymentref', '', array('id'=>'paymentref', 'class'=>'span12', 'autocomplete'=>'off'))}}
				</div>
			</div>

			<div>
				@include('payment_details')
			</div>

		</div>

		<div class="span5">
			@include('keyboard_types.calculator')
		</div>
	</div>
</div>

{{Form::close()}}

<script>
$(document).ready(function(){
	var paymenttobemade = $('.totalamounttendered').text();

		$('.modal-header h3').html('Total amount due: <span class="red bolder">{{currency()}}<span class="bolder" id="totalamountdue">'+ paymenttobemade +'</span></span>');

		$('#balanceremaining').text(paymenttobemade);
		$('#amounttendered').val(paymenttobemade);


		var $paymentmodes = "{{Systemsetting::getx('paymentmode')}}";
		$paymentmodes = $paymentmodes.split(',');
		var paymentmodesOptions;
		$.each($paymentmodes, function(index, value){
			value = value.capitalize();
			paymentmodesOptions += "<option value='"+value+"'>"+value+"</option>";
		});

		$('#paymentmethods').html(paymentmodesOptions);

		/*******************************/
		$('#amounttendered').on('keyup', function(e){
			var balanceremaining = unformat_money($('#balanceremaining').text());
			var amountTenderd = unformat_money($(this).val());

			var changex = (amountTenderd > balanceremaining) 
					? amountTenderd - balanceremaining
					: 0;

			$('#payment_details #change').text(format_money(changex,2));
		});

		//This would add the amount tendered to the table list
		$('#addpayment').on('click', function(e){
			e.preventDefault();
			
			updatePaymentDetails($(this));
		});

		//Lets record the payment details on the cart
		$(this).on('click','.record_payment', function(e){
			e.preventDefault();
			e.stopImmediatePropagation();
			e.stopPropagation();

			if( unformat_money($('#balanceremaining').text()) !== 0 ){
				updatePaymentDetails();
			}

			//save the payment detail info to this variable
			var payment_details_content = '<div>'+$('#payment_details').html()+'</div>';

			//Action to take place when the modalbox(Payment place) is hidden
			//$(document).on('hidden', '#myModal', function(){
				//This would disable the payment button and enable preview_print and new transaction button
				bottombuttons('#payment', '#preview_receipt,#new_transaction');

				//This would remove the "x" remove-icon on each table row
				$('.removeProduct').remove();

				//This would disable edit quantity
				$('.quantity_input').attr('disabled',true);

				//This would disable the CustomerInfo from editing
				$('.customer-token-dropdown').addClass('disabled');

				//This would over-write the search input with the payment_details info saved above.
				//From the info saved.. We are removing the balanceremaining block
				$('.fontpageleft')
				.html($(payment_details_content)
				.find('.balanceremaining')
				.remove()
				.end()
				.html());

				/*
				* THEN WE WILL BE DOING SOME AJAX REQUEST HERE. TO SAVE THE RECEIPT INFO INTO DATABASE AND ALSO 
				* REFRESH THE SESSION HOLDING THE CART INFO
				*/
				//_debug('before');
				save_receipt();
				//_debug('after');
			//});
					
			//Close payment place
			$('.myModalCloned').modal('hide');
		});

		//This function would update the neccessary fields
		function updatePaymentDetails(addpayment){
			addpayment = (addpayment === undefined) ? $('#addpayment') : addpayment;

			var currentPaymentmode;
			var currentAmountTendered;
			var currentBalance;
			$('.review_addpayment tbody').append(function(e){
				currentPaymentmode = $('#paymentmethods').val();
				currentAmountTendered = $('#amounttendered').val();
				return '<tr><td class="pm_paymentmethod">'+currentPaymentmode.capitalize()+'</td><td class="pm_amount">{{currency()}}'+format_money(unformat_money(currentAmountTendered),2)+'</td><td class="pm_ref">'+$('#paymentref').val()+'</td></tr>';
			});

			//We minus the remaning from current tendered to current the current balance
			currentBalance = format_money(unformat_money($('#balanceremaining').text()) - unformat_money(currentAmountTendered), 2);

			if( unformat_money(currentBalance) <= 0 ){
				addpayment.attr('disabled', true);//If currentBalance is 0. the Add payments button would be inactive
				currentBalance = '0.00';
			}

			$('#payment_details #totaltendered').text(format_money(unformat_money(currentAmountTendered) + unformat_money($('#payment_details #totaltendered').text()), 2) );
			$('#balanceremaining').text(currentBalance); // Display the current balance
			$('#amounttendered').val(currentBalance); // Display  the current amount tendered

			return currentBalance; // return the current balance
		}

		function save_receipt(){
			//Ajax call for deleting the item in database
			var itemx={}, items = {};

				$('.cart-place tbody > tr').each(function(i,v){
					$that = $(this);
					items[i] = {};

					items[i][$(this).attr('idx')] =	{
								brand 			: $(this).find('td.productname').text().split('/')[0],
								product 		: $(this).find('td.productname').text().split('/')[1],
								productcat		: $(this).find('td.productcat').text(),
								unitprice 		: function(){
									return unformat_money($that.find('td.unitprice span.unitprice').text());
								},
								quantity 		: parseInt($(this).find('td.quantity input').val()),
								discount 		: parseInt($(this).find('td.discount').text()),
								costprice		: unformat_money($(this).find('td.costprice').text()),
								total_unitprice : unformat_money($(this).find('td.total').text()),
								salesmodename 	: $(this).find('td.salesmodename').text(),
								cat_type 		: $(this).find('td.cat_type').text()
							};
				});

					items['others'] = 	{
										totalquantity		: parseInt($('.total_items_in_cart').text()),
										//receipt_content		: $.trim($('#receipt').html()),
										//receipt_number		: $('#receipt .receipt_number').text(),
										customer_token		: $('#cart #token-id').text(),
										vat 				: unformat_money($('#cart #vat').text()),
										vat_price 			: unformat_money($('#cart .vat').text()),
										subtotalamount 		: function(){
																var subtotal = unformat_money($('#cart .subtotalamount').text());
																return subtotal > 0 ? subtotal : unformat_money($('#cart .totalamounttendered').text());
															},
										totalamount 		: unformat_money($('#cart .totalamounttendered').text()),
									
 										amount_tendered 	: unformat_money($('#totaltendered').text()),
										change 				: unformat_money($('#change').text()),
										};

					/** FETCHING PAYMENT DETAILS STARTS HERE  **/
					itemx['paymentmethods'] = {};
							$('#payment_details table.review_addpayment tbody tr')
							.each(function(index, value){
								itemx['paymentmethods'][index] = {
									method: $(this).find('td.pm_paymentmethod').text(),
									amount: unformat_money($(this).find('td.pm_amount').text()),
									reference:  $(this).find('td.pm_ref').text()
								}
							});

					items['paymentdetails'] = {
						paymentmethods 	: itemx['paymentmethods']
					}
					/** FETCHING PAYMENT DETAILS ENDS HERE  **/

			$(this).ajaxrequest({
				dataContent	: items,
				url 		:"{{URL::route('saveReceipt')}}",
				immediatelyAfterAjax_callback: assignReceiptNumber,
			});

			//Function in g56_function.js
			//We update the stock alert counter
			checkStockAlertAfterPayment("{{URL::route('outofstockwarning_count')}}");

			//Remove All icon to adjust price on the fly in cart
			$('.cog_unitprice, .remove_discount_price_on_fly').remove();
			$('td.unitprice span.unitprice').removeClass('editable editable-click').editable({
				disabled : true
			});

			//remove "entered overall discount" input and replace it with span text
			var tempHoldOverallDiscount = $('.enteroveralldiscount input').val();
			$('.enteroveralldiscount').removeClass('input-prepend input-append').html('<span class="red">{{currency()}}<span>'+ format_money(tempHoldOverallDiscount) +'</span>k</span>');
		}

		function assignReceiptNumber(data){
			if( data !== undefined && data.receipt_number !== undefined ){
				$(document.body).append('<div class="hide pull_left" id="hidden_receipt_number">'+data.receipt_number+'</div><div id="hidden_receipt_date">'+data.receipt_date+'</div><div id="hidden_receipt_time">'+data.receipt_time+'</div>');
			}
		}
});
</script>