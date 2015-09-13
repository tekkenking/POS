<div id="customersummary" style="height:250px">
	<table class="table table-bordered table-striped">
		<thead>
			<tr>
				<th>
					<i class="icon-caret-right blue"></i>
					<small>C. Name</small>
				</th>

				<th>
					<i class="icon-caret-right blue"></i>
					<small>Rpt. No.</small>
				</th>

				<th>
					<i class="icon-caret-right blue"></i>
					<small>T. Amt. + ({{$customersummary['vat']}}% vat).</small>
				</th>

				<th class="hidden-phone">
					<i class="icon-caret-right blue"></i>
					<small>Time</small>
				</th>
			</tr>
		</thead>

		<tbody>


		@if( !empty($customersummary['customers']) )
			@foreach( $customersummary['customers'] as $time => $customers )
					<tr>
						<td><span class="bolder">@if( $customers['customer_id'] > 0 ){{Customer::getCustomerByID($customers['customer_id'])}}@else <span class="blue">Unregistered</span> @endif</span></td>

						<td><span class="label label-success">{{Receipt::buildReceipt($customers['id'])}}</span></td>

						<td>
							<small><span class="bigger-110 label label-large label-yellow">N{{format_money($customers['receipt_subtotalamount'])}}k</span> + N{{format_money($customers['vat_worth'])}}k</small>
						</td>

						<td>
							<small><span class="label label-inverse label-large">{{format_date($customers['created_at'])}}</span></small>
						</td>
					</tr>
			@endforeach
		@endif
		</tbody>
	</table>
	
</div>

<div class="tab-footer">
T. Amt :
N{{format_money($customersummary['subtotalamount'])}}k + N{{format_money($customersummary['totalvat'])}}k ({{$customersummary['vat']}}% vat) = <span style="font-size:16px" class="label label-important arrowed-in arrowed-right label-large">N{{format_money($customersummary['totalamount'])}}k</span>
</div>

<script>
$(document).ready(function(){
	$('#customersummary').slimScroll({
		height:'250px',
		alwaysVisible : true,
		railVisible:true
	});
});
</script>