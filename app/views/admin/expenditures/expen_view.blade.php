<div class="row-fluid">

	<div class="row-fluid">
		<div class="span12">
			<div class="span5">

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

			<div class="span5">
				<div class="inline alert alert-info bolder">

					<i class="icon-time"></i>
					<span class="record_date">
							 {{display_date_range($from, $to)}}
					</span>

				</div>
			</div>

			<div class="span2">
				<div class="inline pull-right">
				    <button class="btn btn-small btn-danger multiple_delete">Delete all selected</button>
				</div>
			</div>

		</div>
	</div>

	{{--$entries->links()--}}

	<div id="create-task-msg-error" class="error-msg"></div>

	<div class="row-fluid">
		<div class="span12">

			<div class="alert alert-success">
				<span class="">Total profit on sales & services: <span class="bolder">{{currency()}}<span class="show_sales_profitmargin">{{format_money($profitmargin)}}</span>k</span></span><br>

				<span class="">Total of expenditures: <span class="bolder">{{currency()}}<span class="show_expenditure_amount"></span>k</span></span><br>

				<span class="">Total profit margin: <span class="bolder">{{currency()}}<span class="show_totalprofitmargin"></span>k</span></span><br>
				
			</div>

		</div>
	</div>

	<table id="expenditureentry-table" class="table table-bordered table-hover">
		<thead>
			<tr style="" class="blue">
				<th class="center">
					<label>
						<input type="checkbox" id="default_checkbox"/>
						<span class="lbl"></span>
					</label>
				</th>
				<th>Item / Service name</th>
				<th>Payment type</th>
				<th>Amount</th>
				<th>Expenditure Date</th>
				<th>Registrar Name</th>
				<th>Entry Date</th>
				<th>Status</th>
				<th></th>
			</tr>
		</thead>

		<tbody>
			@if( $entries !== '' || $entries !== null )
				@foreach( $entries as $entry )
				<tr id="data-{{$entry->id}}" class="bolder force-smalltext deletethis">
					<td class="center">
						<label>
							<input type="checkbox" name="checkbox" value="{{$entry->id}}"/>
							<span class="lbl"></span>
						</label>
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
						<a href="#myModal" row-id="{{$entry->id}}" data-expenditure-status="{{$entry->status}}" data-expenditure-status-url="{{URL::route('expenditure.saveedit')}}"><span class="status badge badge-{{$labeltype}}">{{$translated}}</span></a>
					</td>

					<td class="delete td-actions">
						<div class="hidden-phone visible-desktop action-buttons">
							<a class="blue expenditure_edit" href="#" edit_id="{{$entry->id}}" edit_url="{{URL::route('expenditure.showedit')}}">
								<i class="icon-edit bigger-130"></i>
							</a>
<br><br>
							<a class="red single_delete" href="#">
								<i class="icon-trash bigger-130"></i>
							</a>
						</div>
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

		$('.show_totalprofitmargin').text(format_money( ( unformat_money( $('.show_sales_profitmargin').text() ) - totalExpenditureAmount), 2));


		/***Deleting table row***/
		$('.single_delete, .multiple_delete').deleteItemx({
			url: "{{URL::route('expenditure.delete')}}",
			afterDelete: update_expenditure_profitmargin
		});

		/***Dataset table***/
		var oTable1 = $('table#expenditureentry-table').dataTable( {
						"aoColumns": [
							{ "bSortable": false }, { "bSortable": false }, { "bSortable": false }, { "bSortable": false }, { "bSortable": false }, { "bSortable": false }, { "bSortable": false }, { "bSortable": false }, { "bSortable": false }
						],

						"bPaginate": false,
					} );
		
		/****Checkboxes****/
		$('table th input:checkbox').on('click' , function(){
			var that = this;
			$(this).closest('table').find('tr > td:first-child input:checkbox')
			.each(function(){
				this.checked = that.checked;
				$(this).closest('tr').toggleClass('selected');
			});
		});

		//Pagination relook
		$('.pagination').addClass('no-margin');

		//Calling date rangepicker feature
		$('#record_range').daterangepicker().prev().on(ace.click_event, function(){
			$(this).next().focus();
		});
		
		/*****Call modal for Edit Expenditures*****/
		var expendituresEdit = function(e) {
			e.preventDefault();
			var rowID 	= $(this).attr('edit_id'),
				url 	= $(this).attr('edit_url'),
				$that	= $(this);

				//Unbind click event
				$that.off('click.expendituresEdit', expendituresEdit);
				
				//Lets highlight the active role
				$(this).closest('tr').addClass('khaki-bg');
				
				//Lets get the edit form in a modalbox
				$.get(url, {id:rowID}, function(data) {

					cloneModalbox( $('#myModal') )
					.css({'width':'550px'})
					.centerModal()
					.find('.modal-body').html(data)
					.end()
					.find('.modal-header h3')
					.text('Edit Expenditure')
					.end()
					.find('.modal-footer [data-ref="submit-form"]')
					.hide()
					.end()
					.modal();

					//Rebind click event
					$that.on('click.expendituresEdit', expendituresEdit);

				}, 'html');

		};

		$('.expenditure_edit').on('click.expendituresEdit', expendituresEdit)

		//Lets Unhighlight the active role
		$(document).on('hidden', '.myModalCloned', function(){
			$('tr.khaki-bg').removeClass('khaki-bg');
		});

		//Lets change row status ajax call
		$('a[data-expenditure-status-url]').on('click.expenditure_status',function(e){
			e.preventDefault();
			e.stopPropagation();

			var url, rowID, thisTr, vals, highlightedClass='khaki-bg', statusCode, statusCodeTranslate, vx, cl;

			//Lets get the current Row ID
			rowID = $(this).attr('row-id');

			//Lets get the url
			url = $(this).attr('data-expenditure-status-url');

			//Lets assign status code in vice - versa
			statusCode = (parseInt($(this).attr('data-expenditure-status')) === 0) ? 1 : 0;

			//Lets translate the status
			statusCodeTranslate = (statusCode === 0) ? 'PEND' : 'APPROVE';

			//Lets mark the TR
			thisTr = $('tr#data-'+rowID);

			//Lets highlight the active role
			thisTr.addClass(highlightedClass);

			//Lets set the values sent to server
			vals = {id:rowID, status:statusCode};


			//Lets confirm through dialog box
			bootbox.dialog(
					"You're about to <strong class='blue'>[ "+statusCodeTranslate+" ]</strong> this highlighted record, are you sure?",
					[
						{
							"label" : "No",
							"class" : "btn-gray",
							"callback" : function(){
								//We remove the highlight frm active role
								thisTr.removeClass(highlightedClass);
							}
						},
						{
							"label" : "Yes",
							"class" : "btn-success",
							"callback" : function(){
								//Ajax call for updating the row in database
								$(this).ajaxrequest({
									dataContent: vals,
									url:url,
									wideAjaxStatusMsg: '.error-msg',
									msgPlaceFade: 3000,
									immediatelyAfterAjax_callback: response,
									//pageReload: o.pageReload,
									//ajaxRefresh: o.ajaxRefresh,
								});

								//This function would handle the ajax return response
								function response(result){
									if( result.message !== undefined ){
									
										$.each(result.message, function(i,v){
											
											var row = thisTr.find('span.'+i);
											
											//_debug(row);

											if( i === 'status' ){
												v = parseInt(v);
												vx = (v === 0) ? 'Pending' : 'Approved';
												cl = (v === 0) ? 'badge-important' : 'badge-success';
												//row = row.find('a');
												//Lets set the attrs val
												row.closest('a').attr('data-expenditure-status', v);
												row.removeClass('badge-important');
												row.removeClass('badge-success');
												row.addClass(cl);
												row.text(vx);

												//G56 function file
												update_expenditure_profitmargin(thisTr, v);
												return;
											}

											//row.text(v);
										});
									}
									
									//We remove the highlight frm active role
									thisTr.removeClass(highlightedClass);

								}

							}
						}
					]
				);
		});

	});
</script>