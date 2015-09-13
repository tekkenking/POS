{{$records->appends(Input::all())->links()}}

<div class="profit-margin-bar bolder">
	<span style="font-size:16px;"> CURRENT PAGE: </span>
	<span class="">[ Unit sales:
		<span class="orange">{{currency()}}<span class="show_sales_totalunitprice"></span>k</span>
	]</span>

	<!-- Hidden -->
	<span class="hidden">Units:: <small>Total cost price: </small>
		<span class="orange">{{currency()}}<span class="show_sales_totalcostprice"></span>k</span>
	</span>
	<span class="hidden">[ Units:: <small>Total profit margin: </small>
		<span class="orange">{{currency()}}<span class="show_sales_totalprofitmargin"></span>k</span>
	 ]</span>
	 <!-- Hidden -->

	 <span style="color: rgb(0, 206, 209); font-size: 25px;">+ </span>
	<span class="">[ Services:
		<span class="orange">{{currency()}}<span class="show_total_services"></span><span>k</span></span>
	 ]</span> 
	<span style="color: rgb(0, 206, 209); font-size: 25px;">- </span>
	<span class="">[ Discounts:
		<span class="orange">{{--currency()--}}<span class="show_total_cart_discount"></span><span>k</span></span>
	 ]</span> = 
	<span class="">
		<span class="orange">{{currency()}}<span class="overallsales"></span><span>k</span></span>
	 </span>
	 	&nbsp; &nbsp;
	 <span class="btn btn-minier btn-primary more-sales-info" data-toggle="modal" role="button" data-backdrop="false">...</span>


	 <div class="pull-right red" style="font-size: 19px">
	 	{{currency()}}<span class="overallsales_big">{{format_money($sumAmountTendered)}}</span>k
	 </div>
</div>
<table id="sample-table-2" class="table table-bordered">
	<thead>
		<tr>
			<th><i class="icon-time bigger-110 hidden-phone"></i> Date</th>
			<th>Receipt No.</th>
			<th>Unit</th>
			<th class="force_smalltext"><small>Unit Price @if(User::permitted( 'role.admin'))<br> <span class="grey">Cost Price</span> <br> <span class="grey">Profit Margin</span> @endif</small></th>
			<th>Qty.</th>
			<th class="td_discount">Unit Discount %</th>
			<th><small>Total Unit Price @if(User::permitted( 'role.admin'))<br> <span class="grey">Total Cost Price</span> <br> <span class="grey">Total Profit Margin</span> @endif</small></th>
			<th>Unit Mode</th>
			<th class="td_staffname">Staff Name</th>
		</tr>
	</thead>


	<tbody class="customerhistorylist">

	{{--tt($records->toArray()['data'])--}}

	@if(!empty($records))
		@foreach($records->toArray()['data'] as $groupBy => $v)

			<tr class="bolder tr-td-no-border">
				<td>
					<span class="bolder">{{ng_datetime_format2($v['created_at'])}}</span>
				</td>
				<td><span class="bolder"><a href="#myModal" data-rel='popover' data-url={{URL::route('popoverReceiptPreview', array($v['id']))}}>{{Receipt::buildReceipt($v['id'])}}</a></span></td>
				<td colspan="6">

					<?php
						
						if( !isset($v['salelogs'][0]) ){
							echo "ERROR!!";
						}else{
							$customer =  ( $v['salelogs'][0]['customer'] !== null ) ? $v['salelogs'][0]['customer']['name'] : 'Unregisterd';
						}
					?>
					<div class="no-margin bolder label label-large label-grey arrowed-right">[ Customer's name: {{strtoupper($customer)}} ]</div>
				</td>
				<td class="td_staffname red">
					<i class="icon-user bigger-110 hidden-phone"></i> 
					<?php if( isset($v['salelogs'][0]) ) echo User::getUsernameByID($v['salelogs'][0]['user_id'], false) ?>
				</td>
			</tr>
			<?php $total_amountdue=0; ?>
			@foreach($v['salelogs'] as $history)
				<tr class='sales_infos'>
					<td></td>
					<td></td>
					<td>{{ucwords($history['product']['name'])}}</td>
					<td class="bolder force-smalltext">
						<span class="saleslog_unitprice">{{currency()}}{{format_money($history['unitprice'])}}k</span>

						@if(User::permitted( 'role.admin') && $history['product']['categories']['type'] !== 'service')
						<br>
						<span class="grey">{{currency()}}{{format_money($history['costprice'])}}k</span><br>
						<span class="grey">{{currency()}}{{format_money($history['unitprice'] - $history['costprice'])}}k</span>
						@endif

					</td>
					<td><span class="saleslog_quantity">{{$history['quantity']}}</span></td>
					<td class="td_discount"><span>{{$history['discount']}}</span>%</td>
					<td class="bolder force-smalltext">
						<span>{{currency()}}<span class="saleslog_totalunitprice">{{format_money($history['total_unitprice'])}}</span>k</span>

						<?php $total_amountdue += $history['total_unitprice']; ?>

						@if(User::permitted( 'role.admin') && $history['product']['categories']['type'] !== 'service')
						<br>
						<?php $totalcostprice = $history['quantity'] * $history['costprice']; ?>
						<span class="grey">{{currency()}}<span class="saleslog_totalcostprice">{{format_money($totalcostprice)}}</span>k</span><br>
						<span class="grey">{{currency()}}<span class="saleslog_totalprofitmargin">{{format_money($history['total_unitprice'] - $totalcostprice)}}</span>k</span>
						@endif

					</td>	
					<?php 
						if( $history['product']['categories']['type'] === 'service' ){
							$modex = 'Services';
						}else{
							//$modex = '';
							$modex = Mode::getModeNameFromID($history['mode_id']);
						}
					?>
					<td class="sales_mode">{{ucwords($modex)}}</td>
					<td class="td_staffname"></td>
				</tr>
			@endforeach
				<tr class="transaction_detail">
					<td colspan=6 class="bolder light-blue" style="text-align:right">
						<!--<div> Total Unit Price: </div>-->
						<div> Total Amount Due: </div>
						<div> Total Amount Paid: </div>
						<div> Cart Discounted: </div>
						
					</td>
					<td class="bolder alert alert-info">
						<!--<div>
							<?php 
								//$ttunitprice = $history['unitprice'] * (($history['quantity'] > 0) ? $history['quantity'] : 1);

							?>
							<span>{{--currency()--}}</span><span class="total-amount-due">{{--format_money($ttunitprice)--}}</span><span>k</span>
						</div>	-->					

						<div>
							<span>{{currency()}}</span><span class="total-amount-due">{{format_money($total_amountdue)}}</span><span>k</span>
						</div>
						<div>
							<?php $receipt_worth = $v['receipt_worth']; ?>
							<span>{{currency()}}</span><span class="total-amount-paid">{{format_money($receipt_worth)}}</span><span>k</span>
						</div>
						<div>
							<span>{{currency()}}</span><span class="total-amount-discount">{{format_money($total_amountdue - $receipt_worth)}}</span><span>k</span>
							<!--<span>{{--currency()--}}</span><span class="total-amount-discount">{{--format_money($ttunitprice - $receipt_worth)--}}</span><span>k</span>-->
							
						</div>
						<?php $total_amountdue= $receipt_worth = 0; ?>
					</td>
					<td colspan=2></td>
				</tr>
		@endforeach

	@else
		<tr>
			<td colspan="9" class="center"> 

			@if( $fromdate === '' )
				<h2 class="lighter small"> NO SALE, SO FAR : [ <small class="blue datera">{{format_date2($todate)}}</small> ]</h2> 
			@else
				<h2 class="lighter small"> NO SALE HISTORY : [ <small class="blue datera">{{format_date2($fromdate)}} - {{format_date2($todate)}}</small> ]</h2> 
			@endif

			</td>
		</tr>
	@endif

	</tbody>
</table>


{{$records->appends(Input::all())->links()}}

<script>
$(function(){
	/*$('#sample-table-2').dataTable({
		"bSort": false,
		"bPaginate": false,
		"bFilter": false,
	});*/


	//Modal call for print preview receipt
	var printPreviewReceipt = function (e) {
		e.preventDefault();

		var url = $(this).attr('data-url'), $that = $(this);

		$that.off('click.printPreviewReceipt', printPreviewReceipt);

		$.get(url, function(data) {

			cloneModalbox( $('#myModal') )
			.css({'width':'500px', 'position':'fixed'})
			.centerModal()
			.find('.modal-body').html(data)
				.end()
			.find('.modal-header h3')
			.text('Preview receipt')
			.css({'color':'white'}).removeClass('red lighter')
				.end()
			.find('.modal-footer > [data-ref="submit-form"]').text('Print receipt').addClass('print-previewx')
				.end()
			.modal();

			$that.on('click.printPreviewReceipt', printPreviewReceipt);
		});
	}

	$("[data-rel='popover']").on('click.printPreviewReceipt', printPreviewReceipt);

	//WORKING THE TOTAL PROFILE MARGIN
	var saleslog_totalunitprice = 0,
		saleslog_totalcostprice = 0,
		saleslog_totalprofitmargin = 0,
		show_total_services = 0,
		show_total_cart_discount = 0,
		show_total_item_discount = 0,
		overallsales = 0;

	var td_discount_holder = '';

	$('tr.sales_infos').each(function(){
		if( $(this).find('td.sales_mode').text() !== 'Services' ){
			saleslog_totalunitprice += 1 * unformat_money($(this).find('td span.saleslog_totalunitprice').text());
			//console.log(unformat_money($(this).find('td span.saleslog_unitprice').text()) * $(this).find('td span.saleslog_quantity').text());

			//saleslog_totalunitprice += 1 * unformat_money($(this).find('td span.saleslog_unitprice').text()) * $(this).find('td span.saleslog_quantity').text();

			/*if( (td_discount_holder = Number($(this).find('td.td_discount span').text())) != 0 ){
				show_total_item_discount += (unformat_money($(this).find('td .saleslog_unitprice').text()) * Number($(this).find('td .saleslog_quantity').text())) - unformat_money( $(this).find('td .saleslog_totalunitprice').text() );
			}*/

			saleslog_totalcostprice += 1 * unformat_money($(this).find('td span.saleslog_totalcostprice').text());
		}else{
			show_total_services += 1 * unformat_money($(this).find('td span.saleslog_totalunitprice').text());
			
			//show_total_services += 1 * unformat_money($(this).find('td span.saleslog_unitprice').text()) * $(this).find('td span.saleslog_quantity').text();
		}
	});

	$('tr.transaction_detail').each(function(){
		show_total_cart_discount += unformat_money($(this).find('.total-amount-discount').text());
	});


	saleslog_totalprofitmargin = saleslog_totalunitprice - saleslog_totalcostprice;

	overallsales = saleslog_totalunitprice + show_total_services - show_total_cart_discount;
	//overallsales = saleslog_totalunitprice + show_total_services;
	//show_total_cart_discount += show_total_item_discount;

	//show_total_item_discount = format_money(show_total_item_discount, 2);

	$('span.show_sales_totalprofitmargin').text(format_money(saleslog_totalprofitmargin, 2));
	$('span.show_sales_totalunitprice').text(format_money(saleslog_totalunitprice, 2));
	$('span.show_sales_totalcostprice').text(format_money(saleslog_totalcostprice, 2));
	$('span.show_total_services').text(format_money(show_total_services,2));
	$('span.show_total_cart_discount').text(format_money(show_total_cart_discount));
	$('span.overallsales').text(format_money(overallsales, 2));
	//$('span.overallsales_big').text(format_money(overallsales - show_total_item_discount,2));

	//To view more sales info
	var msi = function(e){
		e.preventDefault();

		var $that = $(this);

		//Unbind click event
		$that.off('click.msi', msi);

		cloneModalbox( $('#myModal') )
		.css({'width':'550px'})
		.centerModal()
		.find('.modal-body').html(function(){
			var show_sales_totalunitpricex = unformat_money($('.show_sales_totalunitprice').text());
			var show_sales_totalcostpricex = unformat_money($('.show_sales_totalcostprice').text());
			var show_sales_totalprofitmarginx = unformat_money($('.show_sales_totalprofitmargin').text());
			var show_total_servicesx = unformat_money($('.show_total_services').text());
			var show_total_cart_discountx = unformat_money($('.show_total_cart_discount').text());
			var overallsalesx = unformat_money($('.overallsales').text());
			var totalProfitsx = show_sales_totalprofitmarginx + show_total_servicesx - show_total_cart_discountx;
			//var totalProfits = show_sales_totalprofitmargin + show_total_services;

			return 	'<h3 class="blue nomargin bolder">Total Unit Sales: '+"{{currency()}}"+ format_money(show_sales_totalunitpricex) +'k</h3>'+
					'<h4 class="red"> - Total Unit Costprice: '+"{{currency()}}"+ format_money(show_sales_totalcostpricex) +'k</h4>'+
					'<h4 class="red"> - Total Unit Profits: '+"{{currency()}}"+ format_money(show_sales_totalprofitmarginx) +'k</h4><hr class="nomargin">'+
					'<h3 class="blue nomargin bolder">Total Services: '+"{{currency()}}"+ format_money(show_total_servicesx) +'k</h3><hr class="nomargin">'+
					'<h3 class="blue nomargin bolder">Total Discounted: '+"{{currency()}}"+ format_money(show_total_cart_discountx) +'k</h3><hr class="nomargin">'+
					'<h5 class="muted nomargin bolder"> Total Unit Sales + Total Services - Total Discount</h5>'+
					'<h3 class="blue nomargin bolder">Total Overall Sales: '+"{{currency()}}"+ format_money(overallsalesx) +'k</h3><hr class="nomargin">'+
					'<h5 class="muted nomargin bolder"> Total Unit Profits + Total Services - Total Discount</h5>'+
					'<h2 class="bolder green nomargin"> Total Profit: '+"{{currency()}}"+ format_money(totalProfitsx) +'k</h2>';
		})
		.end()
		.find('.modal-header')
		.css({'background':'grey'})
		.end()
		.find('.modal-header h3')
		.replaceWith('<h5 class="nomargin bolder white"><span>Record(s) '+ $('.record_date').text()+'</span></h5>')
		.end()
		.find('.modal-footer [data-ref="submit-form"]')
		.hide()
		.end()
		.modal();


		$('.myModalCloned').modal({
			keyboard:false,
			show: false,
			backdrop: false
		});
	}

	$('.more-sales-info').on('click.more-sales-info', msi)

	//To print
	$(this).on('click','.print-salesrecordx', function(e){
		e.preventDefault();
		e.stopImmediatePropagation();

		var clonex = $('#print-salesrecordx').clone();
		clonex.css({'font-size':'10px'});
 
		//Then we can print the receipt here
		var pagetitlex = 'Glamour 56';

		//clonex.find('#sample-table-2 .td_discount, #sample-table-2 .td_staffname').hide();
		clonex.find('#sample-table-2').printMe({
			preview: true,
			printx: {	
						plugin : 'printThis',
						options:{ 
								pageTitle:pagetitlex,
								header:pagetitlex,
								selectedPageCss: "link[rel=stylesheet][href*='bootstrap'], link[rel=stylesheet][href*='ace'], link[rel=stylesheet][href*='bucketcodes']",
								extraAttr: 'border=1,style=border-collapse:collapse,cellspacing=5,cellpadding=5',
							}
					},
			previewContainerWidth: '850px',
			printButton: 'Print Record',
		});

	});

});
</script>