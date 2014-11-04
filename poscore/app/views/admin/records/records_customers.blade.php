<table id="sample-table-2" class="table table-bordered table-hover">
		<thead>
			<tr style="">
				<th class="center">
					#
				</th>
				<th>Token</th>
				<th>Name</th>
				<th>Total purchase</th>
			</tr>
		</thead>

		<tbody>
			@if(!empty($records))
				<?php $customerCounter=0; ?>
				@foreach($records as $customer)
				<?php $name_token = Customer::getCustomerByID($customer['customer_id'], true); ?>
					<tr>
						<td class="center">
							<span class="customer_numbering">{{++$customerCounter}}</span>
						</td>
						<td>
							{{$name_token['token']}}
						</td>
						<td>
							{{$name_token['customername']}}
						</td>
						<td>
								<?php $labelBadgeConstant = 'label label-large arrowed ' ?>
							@if( $customerCounter === 1 ) <?php $labelBadgeConstant .= 'label-pink' ?> @endif
							@if( $customerCounter === 2 ) <?php $labelBadgeConstant .= 'label-success' ?> @endif
							@if( $customerCounter === 3 ) <?php $labelBadgeConstant .= 'label-inverse' ?> @endif
							@if( $customerCounter > 3 ) <?php $labelBadgeConstant = '' ?> @endif
							<span class="{{$labelBadgeConstant}}">N<span>{{format_money($customer['alltime_spent'])}}</span>k</span>
						</td>
					</tr>
				@endforeach
			@endif
		</tbody>

</table>

{{--Larasset::start('footer')->only('dataTables-min', 'dataTables-bootstrap')->show('scripts')--}}
<script>
$(document).ready(function(){
	var oTable1 = $('#sample-table-2').dataTable( {
		"bStateSave": true 
				} );
});
</script>