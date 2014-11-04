@if(!empty($customerhistory))
	@foreach($customerhistory as $groupBy => $v)
	<tr style="background:#F1FACE" class="bolder">
	<td><span class="bolder"><i class="icon-time bigger-110 hidden-phone"></i> {{ng_datetime_format2($v[0]['created_at'])}}</span></td>
	<td colspan="7"><span class="bolder"><a href="#myModal" data-rel='popover' data-url={{URL::route('popoverReceiptPreview', array($groupBy))}}>{{Receipt::buildReceipt($groupBy)}}</a></span></td>
	<td><i class="icon-user bigger-110 hidden-phone"></i> {{User::getUsernameByID($v[0]['user_id'], false)}}</td>
	</tr>
		@foreach($v as $history)
			<tr>
				<td colspan="2"></td>
				<td>{{ ucwords($history['product']['name']) }}</td>
				<td>{{currency()}}{{format_money($history['unitprice'])}}k</td>
				<td>{{$history['quantity']}}</td>
				<td>{{$history['discount']}}%</td>
				<td>{{currency()}}{{format_money($history['total_unitprice'])}}k</td>

					<?php 
						if( $history['product']['categories']['type'] === 'service' ){
							$modex = 'Services';
						}else{
							$modex = Mode::getModeNameFromID($history['mode_id']);
						}
					?>

				<td colspan="2">{{ucwords($modex)}}</td>
			</tr>
		@endforeach
	@endforeach

@else
	<tr>
		<td colspan="9" class="center"> <h2 class="lighter small"> NO SALE HISTORY : [ <small class="blue datera">{{display_date_range($fromdate, $todate)}}</small> ]</h2> </td>
	</tr>
@endif