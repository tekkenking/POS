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
			<span class="blue">{{iStr::title($data->products)}}:</span>
				@if( $data->stocktype === 'create' )
					item(s) <span class="blue"><i class="icon-plus bigger-140"></i> created </span>
				@elseif( $data->stocktype === 'update' )
					@if( $data->stocktype_update === 'quantity' )
						@if( $data->stocktype_update_quantity === 'add' )
							Added: <span class="green"> {{$data->stocktype_update_quantity_count}} quantity(s)</span>
						@elseif( $data->stocktype_update_quantity === 'subtract' )
							Subtracted: <span class="light-red"> {{$data->stocktype_update_quantity_count}} quantity(s)</span>
						@else
							Set: <span class="purple"> {{$data->stocktype_update_quantity_count}} quantity(s) </span>
						@endif
					@endif
			@elseif($data->stocktype === 'delete'  )
				item(s) <span class="red"><i class="icon-trash bigger-140"></i> deleted!</span>
			@elseif($data->stocktype === 'status')
				@if($data->products_status === 1)
					item(s) <span class="green"><i class="icon-ok-sign bigger-140"></i> published!</span> 
				
				@else
					item(s) <span class="red"><i class="icon-minus-sign bigger-140"></i> unpublished!</span> 
					
				@endif
			@elseif($data->stocktype === 'discount' || $data->stocktype === 'unitprice' )
					{{istr::title($data->stocktype_unitprice_mode)}} is <span class="label @if( $data->stocktype === 'discount' )label-yellow arrowed-in @else label-inverse arrowed @endif">{{$data->amount}}</span>
			@endif

			@elseif(isset($data->categories))
				<span class="blue">{{iStr::title($data->categories)}}:</span>
			@elseif(isset($data->brand))
				<span class="blue">{{iStr::title($data->brand)}}:</span>
		@endif

	</div>

@endif