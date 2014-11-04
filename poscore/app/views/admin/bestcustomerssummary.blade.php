<div id="bestcustomerssummary" style="height:250px">
	<table class="table table-bordered">
		<thead>
			<tr style="background:none repeat scroll 0 0 #FFF4F9">
				<th>
					<small>C. Name</small>
				</th>

				<th>
					No. Qty. | <br/> No. Visits
				</th>

				<th>
					<span>Phone Number</span> <br />
					<small class="blue">Total. Amt.</small>
				</th>

				<th class="hidden-phone">
					<span> Last date </span> <br />
					<small class="red">Ago</small>
				</th>
			</tr>
		</thead>

		<tbody>
		@if( !empty($bestcustomerssummary['customers']) )
			@foreach( $bestcustomerssummary['customers'] as $customer )
					<tr>
						<td><span class="bolder">@if( $customer['customer_id'] > 0 ){{Customer::getCustomerByID($customer['customer_id'])}}@else <span class="blue">Unregistered</span> @endif</span></td>

						<td>
							<span class="bolder">{{$customer['alltime_quantity']}} | {{$customer['alltime_visits']}}</span>
						</td>

						<td>
							<small><span class="bolder">{{$customer['customer']['phone']}}</span></small><br />
							<span class="blue bolder">{{currency()}}{{format_money($customer['alltime_spent'])}}k</span>
						</td>

						<td>
							<span class="bolder">{{format_date2($customer['updated_at'])}} <br /> <small class="red">{{ago_date_format($customer['updated_at'])}}</small></span>
						</td>
					</tr>
			@endforeach
		@endif
		</tbody>
	</table>
	
</div>

<!--<div class="tab-footer">
</div>-->

<script>
$(document).ready(function(){
	$('#bestcustomerssummary').slimScroll({
		height: '250px',
		alwaysVisible : true,
		railVisible:true
	});
});
</script>