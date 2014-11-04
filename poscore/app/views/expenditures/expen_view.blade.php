<div class="row-fluid">

	<div class="row-fluid">
		<div class="span12">
			<div class="span6">

				<div class="inline"> 
					{{ Form::open(array('route'=>'expenditures', 'method' => 'get', 'id'=>'recordsearchform', 'class'=>'form-inline') ) }}
						
						<div class="control-group">
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
				<div class="inline pull-right alert alert-info bolder">

					<i class="icon-time"></i>
					<span class="record_date">
							 {{display_date_range($from, $to)}}
					</span>

				</div>
			</div>

		</div>
	</div>

	{{--$entries->links()--}}

	<div id="create-task-msg-error" class="error-msg"></div>

	<div class="row-fluid">
		<div class="span12">

			<div class="alert alert-success bolder">
				<span class="">Total Expenditures Amount: {{currency()}}<span class="show_expenditure_amount"></span>k</span><br>
				
			</div>

		</div>
	</div>

	<table id="expenditureentry-table" class="table table-bordered table-hover">
		<thead>
			<tr style="" class="blue">
				<th class="center">
					#
				</th>
				<th>Item / Service name</th>
				<th>Payment type</th>
				<th>Amount</th>
				<th>Expenditure Date</th>
				<th>Registrar Name</th>
				<th>Entry Date</th>
				<th>Status</th>
			</tr>
		</thead>

		<tbody>
			@if( $entries !== '' || $entries !== null )
				<?php $counter = 0; ?>
				@foreach( $entries as $entry )
				<tr id="data-{{$entry->id}}" class="bolder force-smalltext deletethis">
					<td class="center">
						{{++$counter}}
					</td>

					<td>
						@if($entry->comment !== '')
							<i class="icon-book orange comment" title="{{$entry->comment}}" style="cursor:pointer"></i>
						@endif
						<span class="item_name">{{istr::title($entry->item_name)}}</span>
					</td>

					<td>
						<span class="payment_type">{{istr::title($entry->payment_type)}}</span>
					</td>

					<td>
						{{currency()}}<span class="amount">{{format_money($entry->amount)}}</span>k
					</td>

					<td class="date">
						<i class="icon-time"></i> <span class="date">{{custom_date_format('M j, Y', $entry->date)}} </span>
					</td>

					<td>
						<span class="registrar_name"> {{istr::title($entry->user['name'])}} </span>
					</td>

					<td class="created_at">
						<span> {{custom_date_format("M j, Y", $entry->created_at)}} </span><br>
						<span> {{custom_date_format("h:i:s A", $entry->created_at)}} </span>
					</td>

					<td>
							<?php 
								$translated = 'Pending';
								$labeltype = 'important';
								if( $entry->status === 1 ){
									$translated = 'Approved';
									$labeltype = 'success';
								}
							 ?>
						<span class="status badge badge-{{$labeltype}}">{{$translated}}</span>
					</td>

				</tr>
				@endforeach
			@endif
		</tbody>
	</table>

	{{--$entries->links()--}}
</div>

{{Larasset::start('footer')->only('ace-element', 'moment', 'daterangepicker')->show('scripts')}}

<script type="text/javascript">
	$(function(){

		/***Lets calculate the final profit***/
		var amountObjArray, totalExpenditureAmount =0;

		//We first get all the amount seletor object
		amountObjArray = $('table#expenditureentry-table tbody tr');

		//We alterate through the objects and extract the amount
		//Then unformat the money format
		//Then at it up
		$.each(amountObjArray, function(){

			//We will only app up table row that is approved
			if( $.trim($(this).find('td span.status').text()).toLowerCase() === 'approved' ){
				totalExpenditureAmount += unformat_money($(this).find('td span.amount').text());
			}
		})

		$('.show_expenditure_amount').text(format_money(totalExpenditureAmount, 2));


		/***Dataset table***/
		var oTable1 = $('table#expenditureentry-table').dataTable( {
						"aoColumns": [
							{ "bSortable": false }, { "bSortable": false }, { "bSortable": false }, { "bSortable": false }, { "bSortable": false }, { "bSortable": false }, { "bSortable": false }, { "bSortable": false }
						],

						"bPaginate": false,
					} );
		
		//Pagination relook
		$('.pagination').addClass('no-margin');

		//Calling date rangepicker feature
		$('#record_range').daterangepicker().prev().on(ace.click_event, function(){
			$(this).next().focus();
		});
		

	});
</script>