@if( $activity_type == 'loggedin' || $activity_type == 'loggedout')

		<?php $data = json_decode($message_body) ?>

		<?php $color = ($activity_type == 'loggedin') ? 'green' : 'red'; ?>
		<div class="name" db-id={{$id}}>
			<span class="purple">{{ucwords($data->username)}}</span>:
		</div>
		<div class="time">
			<i class="icon-time bigger-110"></i>
				{{{format_date($activity->created_at)}}}
		</div>
		<div class="text">
			<i class="icon-quote-left"></i>
				<span class="{{$color}}">{{istr::title($activity_type)}}.</span>
		</div>
		
@elseif( $activity_type == 'sale' )
		
		<?php $data = json_decode($message_body); ?>

		<div class="name" db-id={{$id}}>
			<span class="purple bolder">{{ucwords($data->username)}}</span>:
		</div>
		<div class="time">
			<i class="icon-time bigger-110"></i>
			{{{format_date($activity->created_at)}}}
		</div>
		<div class="text">
			<i class="icon-quote-left"></i>
			Made sale worth <span class="red">{{currency()}}{{format_money($data->totalprice)}}k</span> to 
			<span class="muted">
				<em>
				@if($data->customername != null)
					{{ucwords($data->customername)}}.
				@else
					Unregistered.
				@endif 
				</em>
			</span>
				Take a look at the <a href="#myModal" data-rel="popover" class="underline the_receipt" data-url={{URL::route('popoverReceiptPreview', array($data->receipt_number))}}>receipt</a>
		</div>

@elseif($activity_type == 'stock' )

<?php $data = json_decode($message_body); ?>

	<div class="name bolder" db-id={{$activity['id']}}>
			{{ucwords($data->username)}}
	</div>

	<div class="time">
		<i class="icon-time bigger-110"></i>
		<span class="">{{{format_date($activity->created_at)}}}</span>
	</div>
	<div class="text">
			<i class="icon-quote-left"></i>

		@if(isset($data->products))
			<span class="blue"><b>{{iStr::title($data->products)}}</b>: </span>
			@if( $data->stocktype === 'create' )
					item(s) <span class="blue"><i class="icon-plus bigger-140"></i> created </span>
			@elseif( $data->stocktype === 'update' )
					@if( $data->stocktype_update === 'quantity' )
						@if( $data->stocktype_update_quantity === 'add' )
							Added: 
							@if( isset($data->stocktype_new) && isset($data->stocktype_old) )
							<b class="green">{{$data->stocktype_new}} quantity(s)</b> | Before change = <b class="">{{$data->stocktype_old}} quantity(s)</b> in stock
							@else
								<span class="green"> {{$data->stocktype_update_quantity_count}} quantity(s)</span>
							@endif
						@elseif( $data->stocktype_update_quantity === 'subtract' )
							Subtracted:
							@if( isset($data->stocktype_new) && isset($data->stocktype_old) )
							<b class="green">{{$data->stocktype_new}} quantity(s)</b> | Before change = <b class="">{{$data->stocktype_old}} quantity(s)</b> in stock
							@else
								<span class="light-red"> {{$data->stocktype_update_quantity_count}} quantity(s)</span>
							@endif
						@else
							Set: 
							@if( isset($data->stocktype_new) && isset($data->stocktype_old) )
							<b class="green">{{$data->stocktype_new}} quantity(s)</b> | Before change = <b class="">{{$data->stocktype_old}} quantity(s)</b> in stock
							@else
								<span class="purple"> {{$data->stocktype_update_quantity_count}} quantity(s) </span>
							@endif
						@endif
					@elseif( $data->stocktype_update === 'name' || $data->stocktype_update === 'barcodeid')
					Item {{ucfirst($data->stocktype_update)}} changed from 
						<span class="purple">
							<b>{{ucfirst($data->stocktype_update_oldname)}}</b>
						</span> to 
						<span class="blue">
							<b>{{ucfirst($data->stocktype_update_newname)}}</b>
						</span>
					@elseif( $data->stocktype_update === 'almost_finished' )
							{{--tt($data)--}}
							Quantity Warning was set to 
						<b>{{$data->stocktype_new}} quantity(s)</b> | Before change = <b class="">{{$data->stocktype_old}} quantity(s)</b>
					@endif
			@elseif($data->stocktype === 'delete'  )
				@if( isset($data->stock_items) )
					<b class='orange'>{{ucfirst($data->stock_items)}} </b>
				@endif
					item(s) <span class="red"><i class="icon-trash bigger-140"></i> deleted!</span>
			@elseif($data->stocktype === 'status')
				@if($data->products_status == 1)
					item(s) <span class="green"><i class="icon-ok-sign bigger-140"></i> published!</span> 
				
				@else
					item(s) <span class="red"><i class="icon-minus-sign bigger-140"></i> unpublished!</span> 
					
				@endif
			@elseif($data->stocktype === 'unitprice' )
					{{istr::title($data->stocktype_unitprice_mode)}} is 
					<span class="label label-large label-inverse arrowed">
						<b>{{$data->amount}}</b>
					</span> 
						@if( isset($data->old_amount) )
						| Before change = <b> {{$data->old_amount}} </b> 
						@endif
			@elseif( $data->stocktype === 'discount' )
					@if(isset($data->product_name))
						<b class='orange'>{{ucfirst($data->product_name)}}</b> {{ucfirst($data->stocktype_unitprice_mode)}} is 
						<span class="label label-large label-yellow arrowed-in">
							<b>{{$data->new_discount}} {{$data->new_price}}</b>
						</span> 
							| Before change = <b>{{$data->old_discount}} {{$data->old_price}}</b>
					@else
						{{istr::title($data->stocktype_unitprice_mode)}} is 
						<span class="label label-large label-yellow arrowed-in">
							<b>{{$data->amount}} N100.00k</b>
						</span> 
					@endif
			@elseif( $data->stocktype === 'costprice' )
					Cost price is 
					<span class="label label-large label-important arrowed">
						<b>{{$data->amount}}</b>
					</span> 
					@if( isset($data->old_amount) )
					| Before change = <b> {{$data->old_amount}} </b> 
					@endif
			@endif

			@elseif(isset($data->categories))
				<span class="blue">{{iStr::title($data->categories)}}:</span>
			@elseif(isset($data->brand))
				<span class="blue">{{iStr::title($data->brand)}}:</span>
		@endif

	</div>

@endif