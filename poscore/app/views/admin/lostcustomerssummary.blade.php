<div id="lostcustomerssummary" style="height:350px">
	<table class="table table-bordered">
		<thead>
			<tr style="background:none repeat scroll 0 0 #FFFFC0">
				<th>
					<small>C. Name</small>
				</th>

				<th class="hidden-phone">
					No. Qty | <br />No. Visits
				</th>

				<th>
					<span> Phone No. </span><br />
					<small class="blue">Total Amt.</small>
				</th>

				<th>
					Last date<br /> <small class="red">Ago</small>
				</th>
			</tr>
		</thead>

		<tbody>
		@if( !empty($lostcustomerssummary['customers']) )
			@foreach( $lostcustomerssummary['customers'] as $customer )
					<tr>
						<td><span class="bolder">@if( $customer['customer_id'] > 0 ){{Customer::getCustomerByID($customer['customer_id'])}}@else <span class="blue">Unregistered</span> @endif</span></td>

						<td>
							<span class="bolder">{{$customer['alltime_quantity']}} | </span> 
							<span class="bolder">{{$customer['alltime_visits']}}</span>
						</td>

						<td>
							<span class="bolder">{{$customer['customer']['phone']}}</span>
							<br />
							<small class="blue bolder">{{currency()}}{{format_money($customer['alltime_spent'])}}k</small> 
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


<script>
$(document).ready(function(){
	$('#lostcustomerssummary').slimScroll({
		height: '350px',
		alwaysVisible : true,
		railVisible:true
	});
});
</script>