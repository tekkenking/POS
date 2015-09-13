@if( !empty($salesummary['sales']) )
	@foreach( $salesummary['sales'] as $time => $sales )
		<div class="itemdiv commentdiv">
					<div class="user">
						<i class="pull-left icon-user bigger-180 orange"></i>
					</div>
					<div class="body">
						<div class="name bolder">
							{{istr::title(User::selectUserDetailByID($sales['user_id'], 'username'))}}
						</div>
						<div class="time">
							<i class="icon-time bigger-110"></i>
							<span class="">{{{format_date($sales['created_at'])}}}</span>
						</div>
						<div class="text">
							<i class="icon-quote-left"></i>
		    					Made sale worth <span class="red">{{currency()}}{{format_money($sales['receipt_worth'])}}</span>. Receipt #: <a href="#" class="underline">{{Receipt::buildReceipt($sales['id'])}}</a>
						</div>
					</div>

		</div>
	@endforeach
@else
	<h2 class="center lighter red" style=""><i class="icon-money"></i> No recent sale !</h2>
@endif