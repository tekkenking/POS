{{--tt($items)--}}

<div class="row-fluid modal-preview-suspended">


	<div class="row-fluid">
		<div class="span12">
			<div class="span3">
				<h5>
					<i class="icon-shopping-cart bigger-160"></i>
					<span class="total_items_in_cart badge badge-large badge-success">{{$items['cart_quantity']}}</span> Item(s) in Cart
				</h5>
			</div>

			<div class="span9">
				@if(isset($items['customer_token']))
					<h5 class="label label-large label-yellow"> 
						Customer {{$items['customer_token']}} 
					</h5>
				@endif
			</div>
		</div>
	</div>

	<table class="table table-striped table-bordered">
		<thead>
			<tr>
				<th class="center">Qty</th>
				<th>Description</th>
				<th>Unit price</th>
				<th>%</th>
				<th>Total</th>
				<th>Mode</th>
			</tr>
		</thead>

		<tbody>
			@foreach($items['cart_content'] as $trs)
				@foreach($trs as $tr)
					{{$tr}}
				@endforeach
			@endforeach

		</tbody>
	</table>

	<div class="hr hr8 hr-double hr-dotted"></div>

	<div class="row-fluid">
		<div class="span6 pull-right">
			<h4 class="pull-right">
				Total amount :
				<span class="red">{{currency()}}{{format_money($items['totalamount'])}}k</span>
			</h4>
		</div>
	</div>	

	<div class="row-fluid">
		<div class="span6 pull-right">
			<h4 class="pull-right">
				Overall discount :
				<span class="red">{{currency()}}{{format_money($items['enteroveralldiscount'])}}k</span>
			</h4>
		</div>
	</div>

	<div class="row-fluid">
		<div class="span6 pull-right">
			<h4 class="pull-right">
				Total amount tendered :
				<span class="red">{{currency()}}{{format_money($items['totalamounttendered'])}}k</span>
			</h4>
		</div>
	</div>

</div>

<script type="text/javascript">
$(function(){
	$('.modal-preview-suspended .quantity_input').attr('readonly', 'readonly');
	$('.modal-preview-suspended .cog_unitprice').hide();
	$('.modal-preview-suspended .removeProduct').closest('td').hide();

})
</script>