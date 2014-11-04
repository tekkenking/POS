@section('sidebar')
<div class="red logoarea">
	<a href={{URL::route('adminShowCategory', array(slug($brand->name)))}}>{{imging($brand->brandlogo)}}</a>
</div>
 
<li>
	<a href="#myModal" modal-urlx={{ URL::route( 'adminAddProduct', array($addProductLink['brand_id'],$addProductLink['productcat_id'] )) }} title="Add Service">
		<i class="icon-plus bigger-160 light-orange"></i>
		<span class="menu-text">Add Service</span>
	</a>
</li>

<li>
	<a href="#" class="multipletogglestatus">
		<i class="icon-ok-sign bigger-80 green"></i><span class="menu-text">On</span> 
		| 
		<i class="icon-minus-sign bigger-120 red"></i><span class="menu-text">Off</span> Status
	</a>
</li>

<li>
	<a class="multiple_delete" href="#">
		<i class="icon-trash bigger-160 red"></i>
		<span class="menu-text">Delete Service</span>
	</a>
</li>

<li>
	<a href="../{{slug($brand->name)}}">
		<i class="icon-arrow-left bigger-160 orange"></i>
		<span class="menu-text">One step back</span>
	</a>
</li>

@stop

<div class="alert  alert-danger" style="margin:5px 0">
	<div class="row-fluid">
		<div class="span12">
			<div class="span6">
				<i class="icon-briefcase bigger-160 orange"></i>
				<h4 style="display:inline-block" class="small">
					SERVICES <i class="icon-double-angle-right"></i> {{--ucwords($mode)--}}
					<small class="">
						<strong>
						<i class="icon-ellipsis-vertical"></i>
						{{ucwords($cat)}}
						</strong>
					</small>
					<span class="loadertargetplace"></span>
				</h4>
			</div>
			<div class="span6">

			</div>
		</div>
	</div>
</div>


<div class="row-fluid">
	<table id="sample-table-2" class="table table-bordered table-hover">
		<thead>
			<tr style="">
				<th class="center">
					<label>
						<input type="checkbox" id="default_checkbox"/>
						<span class="lbl"></span>
					</label>
				</th>
				<th>#</th>
				<th>Services</th>
				<th><small>Status</small></th>
				<!--<th><small>Qty / <br> Qty.Warning</small></th>-->
				<th> Price </th>
				<th><small>Discount / <br>Discounted Price </small></th>
				<th>Final price</th>
				<th></th>
			</tr>
		</thead>

		<tbody>
		
 @if( isset($products) && $products != null)
 <?php $productCounter=0; ?>
	@foreach($products as $product)
			<tr id="data-{{$product['id']}}" class="deletethis">
				<td class="center">
					<label>
						<input type="checkbox" name="checkbox" value="{{$product['id']}}"/>
						<span class="lbl"></span>
					</label>
				</td>
				<td class="center">{{++$productCounter}}</td>
				<td class="productname">
					<div class="span12">

						<span class="blue tooltip-info force-smalltext force-bolder" style="border-bottom:none;" data-name='name' data-title='Change product name' data-pk="{{{$product['id']}}}" data-placement="right" data-productname="{{{$product['id']}}}">
							{{{istr::title($product['name'])}}}
						</span>

					</div>
				</td>
				
				<td class="status center"> 
					<a href="#" class="togglepublished" status-id={{$product['id']}} status-url={{URL::route('productStatus')}}> 
						<i class="@if($product['published'] == 1) green icon-ok-sign @else red icon-minus-sign @endif bigger-160"></i>
					</a> 
				</td>

				<td class="blue force-bolder">
					{{{currency()}}}<span class="tooltip-warning" style="border-bottom:none; font-weight:bold" data-name='price' data-title='Set unit price' data-pk="{{{$product['id']}}}" data-price="{{{$product['id']}}}">{{{format_money($product['price'])}}}</span>k
				</td>

				<td> 
					<span class="blue"><span style="border-bottom:none;" class="" data-name='discount' data-title='Set discount. E.g 10' data-pk="{{{$product['id']}}}" data-discount="{{{$product['id']}}}">{{{$product['discount']}}}</span>%</span>

					<span class="label label-large label-yellow arrowed-in">
						{{{currency()}}}<span datax-name='discountedprice' datax-pk="{{{$product['id']}}}">{{{format_money($product['discountedprice'])}}}</span>k
					</span>
				</td>
				
				<td>
					<span class="bolder">
	<?php 
		$totalprice = 1 * ( (int)$product['discountedprice'] > 0 ? $product['discountedprice'] : $product['price'] );
	?>
						{{{currency()}}}<span datax-name='totalprice' datax-pk="{{{$product['id']}}}">{{{format_money($totalprice)}}}</span>k
					</span>
				</td>

				<td class="td-actions">
					<div class="hidden-phone visible-desktop action-buttons">
						<a class="red single_delete" href="#" ref-type="delete" ref-url="" title="Delete {{{$product['name']}}}">
							<i class="icon-trash bigger-130"></i>
						</a>
					</div>
				</td>
			</tr>
@endforeach	
@endif
		</tbody>
	</table>
</div>


<!--inline scripts related to this page-->
<script type="text/javascript">
$(document).ready(function() {

	//Toggles status for products
	$('.togglepublished, .multipletogglestatus').toggleStatus();

	var oTable1 = $('#sample-table-2').dataTable( {
		"bPaginate": false,
		"aoColumns": [
					{ "bSortable": false }, null, null, null, null, null, null, { "bSortable": false }
				]
			} );
	
	$('table th input:checkbox').on('click' , function(){
		var that = this;
		$(this).closest('table').find('tr > td:first-child input:checkbox')
		.each(function(){
			this.checked = that.checked;
			$(this).closest('tr').toggleClass('selected');
		});
	});


//Xeditable Bootstrap starts
		$(this).on('click', 'a[data-controller]', function(e){
			e.preventDefault();
		    e.stopPropagation();
		    var val = $(this).attr('data-controller');
		    var ctype = $(this).attr('clicked');

			$('span[data-'+ctype+'="'+ val +'"]').editable({
			    display: function(value) {
			       $('span[data-'+ctype+'="'+ val +'"]').text(value);
			    }
			});

			$('span[data-'+ctype+'="'+ val +'"]').editable('toggle');

		});

		$('span[data-pk]').editable({
			validate: function(value) {
				var v = $.trim(value);
			    if(v == '') {
			        return 'This field is required';
			    }
			},

			placement 	: 'top',
			type 		: 'text',
			url 		: "{{URL::route('updateproduct')}}",
			params 		:{mode:"{{$mode}}", cat_type:'service'},
			success 	:ajaxAction,
			highlight 	:false,
			emptytext 	:false,
		});

		function ajaxAction(response){
			$(document).ajaxComplete(function() {
				if(response.status == 'success'){
				    if( response.ctype !== undefined ){
				        $('tr#data-'+response.id+ ' td span[data-name="'+ response.ctype +'"]').text(response.value);

				    	//Function in g56_function.js
						//We update the stock alert counter
						//checkStockAlertAfterPayment("{{URL::route('outofstockwarning_count')}}");

						//We'll set the total cost price difference
						if( response.totalcost_price !== undefined ){
							$('span[datax-name="totalcostprice"][datax-pk="'+ response.id +'"]')
				        .text( response.totalcost_price );
						}

						//We'll set the price difference value
				        $('tr#data-'+response.id+ ' span.price_difference').text( 
				         	format_money(unformat_money($('span[data-name="price"][data-pk="'+response.id+'"]').text()) - unformat_money($('span[data-name="costprice"][data-pk="'+response.id+'"]').text()), 2)
				         	);

				     	$('tr#data-'+response.id+ ' span.totalprice_difference').text( 
				         	format_money(unformat_money(response.total_price) - unformat_money(response.totalcost_price), 2)
				         	);
					}

				    if( response.total_price !== undefined )

				        $('span[datax-name="totalprice"][datax-pk="'+ response.id +'"]')
				        .text(response.total_price);

				         /** WORKING TO GET THE ALL TOTAL PRICE **/
				        var sum_total_money=0;

				      	$('span[datax-name="totalprice"]').each(function(){
				        	sum_total_money += unformat_money($(this).text());
				        });

				      	//This is where we'll sum total price in real time
				        $('.sum_total_money').text( format_money(sum_total_money, 2) );

				        /** WORKING TO GET THE ALL TOTAL COST PRICE **/
				        /*var sum_cost_money=0;

				       $('span[datax-name="totalcostprice"]').each(function(){
				        	sum_cost_money += unformat_money($(this).text());
				        });

				        //This is where we'll sum total price in real time
				        $('.sum_cost_money').text( format_money(sum_cost_money, 2) );

				        // WORKING TO GET THE ALL PROFIT MARGIN
				        $('.sum_profit_margin').text( format_money((sum_total_money - sum_cost_money), 2) );*/

				    if( response.discounted_price !== undefined )
				        $('span[datax-name="discountedprice"][datax-pk="'+ response.id +'"]')
				        .text(response.discounted_price);

				    if( response.name !== undefined )
				     	$('span[data-name="name"][data-pk="'+ response.id +'"]')
				        .text(response.name);

				    if( response.barcodeid !== undefined )
				     	$('span[data-name="barcodeid"][data-pk="'+ response.id +'"]')
				        .text(response.barcodeid);

				        //COST PRICE
				   /* if( response.costprice !== undefined ){
				    	$('span[data-name="costprice"][data-pk="'+ response.id +'"]')
				        .text(response.costprice);

				        //Setting the profit margin on a single item
				         $('tr#data-'+response.id+ ' span.price_difference').text( 
				         	format_money(unformat_money($('span[data-name="price"][data-pk="'+ response.id +'"]').text()) - unformat_money(response.costprice), 2)
				         	);

				         //Setting the total cost price on a single item
				        $('span[datax-name="totalcostprice"][datax-pk="'+ response.id +'"]')
				        .text(response.totalcostprice);

				        //Setting the total profit margin on a single item
				         $('tr#data-'+response.id+ ' span.totalprice_difference').text( format_money(unformat_money(  $('tr#data-'+response.id+ ' span[datax-name="totalprice"]').text() ) - unformat_money(response.totalcostprice), 2) );
						
						// WORKING TO GET THE ALL PROFIT MARGIN
						var sum_cost_moneyx = 0;
						 $('span[datax-name="totalcostprice"]').each(function(){
						 	sum_cost_moneyx += unformat_money($(this).text());
						 });

						 $('.sum_cost_money').text(format_money(sum_cost_moneyx, 2));

				        $('.sum_profit_margin').text( format_money((unformat_money($('span.sum_total_money').text()) - sum_cost_moneyx), 2) );
				    }*/

			    }else if( response.status == 'error' ){
			    	if( response.name !== undefined )
				     	$('span[data-name="name"][data-pk="'+ response.id +'"]')
				     	.text(response.name);

				     	bootbox.alert(response.message);
			    }

			    $(this).unbind('ajaxComplete');
			   // $(this).unbind('alert');
			});
		}

		$.fn.editableform.buttons = '<button type="submit" class="btn btn-success editable-submit btn-mini"><i class="icon-ok icon-white"></i></button>' +
 '<button type="button" class="btn editable-cancel btn-mini"><i class="icon-remove"></i></button>';
//Xeditable Bootstrap ends	
	

		//Deleting of items
	$('.single_delete, .multiple_delete').deleteItemx({
		url: "{{URL::route('deleteproducts')}}",
		rollNameClass:'productname',
		afterDelete:checkStockAlertAfterPayment,
		afterDelete_args:"{{URL::route('outofstockwarning_count')}}"
	});


	//Modal call for Add Service
	var addProduct = function (e){
		var $that = $(this), url = $(this).attr('modal-urlx');

		$("a[modal-urlx]").off('click.addProduct', addProduct);

		$.get(url, function(data) {

			cloneModalbox($('#myModal'))
			.css({'width':'600px'})
			.centerModal()
			.find('.modal-body').html(data)
				.end()
			.find('.modal-header h3')
			.text('Add Services')
			.css({'color':'white'}).removeClass('red lighter')
			.end()
			.modal();

			$("a[modal-urlx]").on('click.addProduct', addProduct);

		});
	};

	$("a[modal-urlx]").on('click.addProduct', addProduct);

});
</script>