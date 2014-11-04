<div class="row-fluid">

	<div class="row-fluid">

		<div class="span12">
			<div class="span6">
				<div class="inline"> 
					{{ Form::open(array('route'=>'sale_entries', 'method' => 'get', 'id'=>'recordsearchform', 'class'=>'form-inline') ) }}
						
						<div class="control-group">
							<div class="inline">
							<span class="bolder"> Date range filter: </span>
								<!--<select name="type" id="record_type">
									<?php //$record_types = array('created_at'=>'Entry date', 'deposit_date'=>'Deposit date') ?>
									{{--@foreach($record_types as $v=>$op)
										<option @if($v === Session::get('ss_select_banktype',null)) selected='selected' @endif value="{{$v}}" @if( isset($_GET['record_type']) && $_GET['record_type'] === $v ) selected="selected" @endif> {{$op}}</option>
									@endforeach--}}
								</select>-->
							</div>

							<div class="inline input-prepend">
								<span class="add-on">
									<i class="icon-calendar"></i>
								</span>
								{{Form::text('record_range', '', array('placeholder'=>'Date range', 'class'=>'span8', 'id'=>'record_range'))}}
								<button type="submit" class="btn btn-warning"><i class="icon-search"></i> Search</button>
							</div>
						</div>
					{{Form::close()}}
				</div>
			</div>

			<div class="span6">
				<div class="inline alert alert-info bolder pull-right">
					<i class="icon-time"></i> 
				    <span class="record-date">From: {{format_date2($from)}} - To: {{format_date2($to)}}</span>
				</div>
			</div>
		</div>

	</div>

	{{$entries->appends(array('record_range' => Input::get('record_range', '')))->links()}}

	<div id="create-task-msg-error" class="error-msg"></div>

	<table id="bankentry-table" class="table table-bordered table-hover">
		<thead>
			<tr style="" class="blue">
				<th class="center">
					<label>
						#
					</label>
				</th>
				<th>Bank name</th>
				<th>Teller number</th>
				<th>Payment type</th>
				<th>Amount</th>
				<th><small>Deposited Date<br>Depositors Name<br>Depositor Number</small></th>
				<th>Registrar Name</th>
				<th>Entry Date</th>
				<th>Status</th>
			</tr>
		</thead>

		<tbody>
			@if( $entries !== '' || $entries !== null )
			<?php $counter=0; ?>
				@foreach( $entries as $entry )
				<tr id="data-{{$entry->id}}" class="bolder force-smalltext">
					<td class="center">
						<label>
							{{++$counter}}
						</label>
					</td>

					<td class="bank_name">
						@if($entry->comment !== '')
							<i class="icon-book orange" title="{{$entry->comment}}" style="cursor:pointer"></i>
						@endif
						<span> {{istr::title($entry->bank_name)}} </span>
					</td>

					<td class="teller_number">
						<span> {{$entry->teller_number}} </span>
					</td>

					<td class="payment_type">
						<span> {{istr::title($entry->payment_type)}} </span>
					</td>

					<td class="amount">
						{{currency()}}<span>{{format_money($entry->amount)}}</span>k
					</td>

					<td class="deposit">
						<span class="deposit_date"><i class="icon-time"></i> {{custom_date_format('M j, Y', $entry->deposit_date)}} </span><br>
						<span class="depositor_name"><i class="icon-user"></i> {{istr::title($entry->depositor_name)}} </span><br>
						<span class="depositor_number"><i class="icon-phone"></i> {{$entry->depositor_number}} </span>
					</td>

					<td class="registrar_name ">
						<span> {{istr::title($entry->user['name'])}} </span>
					</td>

					<td class="created_at">
						<span> {{custom_date_format("M j, Y", $entry->created_at)}} </span><br>
						<span> {{custom_date_format("h:i:s A", $entry->created_at)}} </span>
					</td>

					<td class="status">
							<?php 
								$translated = 'pending';
								$labeltype = 'important';
								if( $entry->status === 1 ){
									$translated = 'Approved';
									$labeltype = 'success';
								}
							 ?>
						<span class="badge badge-{{$labeltype}}"> {{$translated}} </span>
					</td>

				</tr>
				@endforeach
			@endif
		</tbody>
	</table>

	{{$entries->appends(array('record_range' => Input::get('record_range', '')))->links()}}
	
</div>

{{Larasset::start('footer')->only('ace-element', 'moment', 'daterangepicker')->show('scripts')}}

<script type="text/javascript">
	$(function(){
		//Dataset table
		/*var oTable1 = $('#bankentry-table').dataTable( {
						"aoColumns": [
							{ "bSortable": false }, { "bSortable": false }, { "bSortable": false }, { "bSortable": false }, { "bSortable": false }, { "bSortable": false }, { "bSortable": false }, { "bSortable": false }, { "bSortable": false }
						],

						"bPaginate": false,
					} );*/
		

		//Pagination relook
		$('.pagination').addClass('no-margin');

		//Calling date rangepicker feature
		$('#record_range').daterangepicker().prev().on(ace.click_event, function(){
			$(this).next().focus();
		});
	});
</script>