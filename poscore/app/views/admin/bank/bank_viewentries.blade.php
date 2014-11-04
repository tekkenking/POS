<div class="row-fluid">

	<div class="row-fluid">
		<div class="span12">
			<div class="span5">
				<div class="inline"> 
				{{ Form::open(array('route'=>'entries', 'method' => 'get', 'id'=>'recordsearchform', 'class'=>'form-inline') ) }}
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

			<div class="span5">
				<div class="inline alert alert-info bolder">
				<i class="icon-time"></i> 
				   <span class="record-date">From: {{format_date2($from)}} - To: {{format_date2($to)}}</span>
				</div>
			</div>

			<div class="span2">
				<div class="inline pull-right">
				    <button class="btn btn-small btn-danger multiple_delete">Delete all selected</button>
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
						<input type="checkbox" id="default_checkbox"/>
						<span class="lbl"></span>
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
						<span class="bank_name">{{istr::title($entry->bank_name)}}</span>
					</td>

					<td>
						<span class="teller_number">{{$entry->teller_number}}</span>
					</td>

					<td>
						<span class="payment_type">{{istr::title($entry->payment_type)}}</span>
					</td>

					<td>
						N<span class="amount">{{format_money($entry->amount)}}</span>k
					</td>

					<td class="deposit">
						<i class="icon-time"></i> <span class="deposit_date">{{custom_date_format('M j, Y', $entry->deposit_date)}} </span><br>
						<i class="icon-user"></i> <span class="depositor_name">{{istr::title($entry->depositor_name)}} </span><br>
						<i class="icon-phone"></i> <span class="depositor_number">{{$entry->depositor_number}} </span>
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
						<a href="#myModal" row-id="{{$entry->id}}" data-bankentries-status="{{$entry->status}}" data-bankentries-status-url="{{URL::route('saveedit')}}"><span class="status badge badge-{{$labeltype}}">{{$translated}}</span></a>
					</td>

					<td class="delete td-actions">
						<div class="hidden-phone visible-desktop action-buttons">
							<a class="blue bankentry_edit" href="#" edit_id="{{$entry->id}}" edit_url="{{URL::route('getedit')}}">
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

	{{$entries->appends(array('record_range' => Input::get('record_range', '')))->links()}}
	
</div>

{{Larasset::start('footer')->only('ace-element', 'moment', 'daterangepicker')->show('scripts')}}

<script type="text/javascript">
	$(function(){
		//Deleting table row
		$('.single_delete, .multiple_delete').deleteItemx({
			url: "{{URL::route('deleteentry')}}"
		});

		//Dataset table
		var oTable1 = $('#bankentry-table').dataTable( {
						"aoColumns": [
							{ "bSortable": false }, { "bSortable": false }, { "bSortable": false }, { "bSortable": false }, { "bSortable": false }, { "bSortable": false }, { "bSortable": false }, { "bSortable": false }, { "bSortable": false }, { "bSortable": false }
						],

						"bPaginate": false,
					} );
		
		//Checkboxes
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
		
		//Call modal for Edit bank Entry
		var bankEntryEdit = function(e) {
			e.preventDefault();
			var rowID 	= $(this).attr('edit_id'),
				url 	= $(this).attr('edit_url'),
				$that	= $(this);

				//Unbind click event
				$that.off('click.bankentry_edit', bankEntryEdit);
				
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
					.text('Edit Bank Entry')
					.end()
					.find('.modal-footer [data-ref="submit-form"]')
					.hide()
					.end()
					.modal();

					//Rebind click event
					$that.on('click.bankentry_edit', bankEntryEdit);

				}, 'html');

		};

		$('.bankentry_edit').on('click.bankentry_edit', bankEntryEdit)

		//Lets Unhighlight the active role
		$(document).on('hidden', '.myModalCloned', function(){
			$('tr.khaki-bg').removeClass('khaki-bg');
		});

		//Lets change row status ajax call
		$(this).on('click', 'a[data-bankentries-status-url]',function(e){
			e.preventDefault();
			var url, rowID, thisTr, vals, highlightedClass='khaki-bg', statusCode, statusCodeTranslate;

			//Lets get the current Row ID
			rowID = $(this).attr('row-id');

			//Lets get the url
			url = $(this).attr('data-bankentries-status-url');

			//Lets assign status code in vice - versa
			statusCode = (parseInt($(this).attr('data-bankentries-status')) === 0) ? 1 : 0;

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
								//Ajax call for deleting the item in database
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
												var vx = (v === 0) ? 'Pending' : 'Approved';
												var cl = (v === 0) ? 'badge-important' : 'badge-success';
												//row = row.find('a');
												//Lets set the attrs val
												row.closest('a').attr('data-bankentries-status', v);
												row.removeClass('badge-important');
												row.removeClass('badge-success');
												row.addClass(cl);
												row.text(vx);
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