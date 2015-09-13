{{Larasset::start('header')->only('bootstrap-datepicker')->show('styles')}}
<div class="bank_eidtentry" id="bank_editentry">
	{{Form::open(array('route'=>'saveedit', 'id'=>'form_editentry', 'class'=>'form-horizontal'))}}
		{{Form::hidden('id', $row['id'])}}
		
		<div class="control-group">
			<label class="control-label" for="bank_name">Bank name</label>
			<div class="controls">
				{{Form::text('bank_name', istr::title($row['bank_name']), array('id'=>'bank_name', 'validate'=>'required|name'))}}
			</div>
		</div>
		
		<div class="control-group">
			<label class="control-label" for="teller_number">Teller number</label>
			<div class="controls">
				{{Form::text('teller_number', $row['teller_number'], array('id'=>'teller_number', 'validate'=>'required'))}}
			</div>
		</div>
		
		<div class="control-group">
			<label class="control-label" for="payment_type">Payment type</label>
			<div class="controls">
				@if( ($pmt = Systemsetting::getx('paymentmode')) !== '' )
					<select name="payment_type" id="payment_type">
						@foreach(  explode(',', $pmt) as $md)
							<option value={{$md}} @if($md === $row['payment_type']) selected @endif>{{istr::title($md)}}</option>
						@endforeach
					</select>
				@else
					{{Form::text('payment_type', $row['payment_type'], array('id'=>'payment_type', 'validate'=>'required'))}}
				@endif
			</div>
		</div>
		
		<div class="control-group">
			<label for="amount" class="control-label">Amount</label>
			<div class="controls">
				{{Form::text('amount', $row['amount'], array('id'=>'amount', 'validate'=>'required'))}}
			</div>
		</div>
		
		<div class="control-group">
			<label for="depositdate" class="control-label">Deposit date</label>

			<div class="controls">
				{{Form::text('deposit_date', custom_date_format('M j, Y', $row['deposit_date']), array('id'=>'depositdate', 'validate'=>'required'))}}
			</div>
		</div>
		
		<div class="control-group">
			<label for="depositor_name" class="control-label">Depositor's name</label>

			<div class="controls">
				{{Form::text('depositor_name', $row['depositor_name'], array('id'=>'depositor_name', 'validate'=>'required|name'))}}
			</div>
		</div>

		<div class="control-group">
			<label for="depositor_number" class="control-label">Depositor's number</label>

			<div class="controls">
				{{Form::text('depositor_number', $row['depositor_number'], array('id'=>'depositor_number', 'validate'=>'required|phone'))}}
			</div>
		</div>
		
		<div class="control-group">
			<label for="comment" class="control-label">Note <small class="muted">e.g <em>lorem ipsum</em></small></label>

			<div class="controls">
				{{Form::text('comment', $row['comment'], array('id'=>'comment', 'placeholder'=>''))}}
			</div>
		</div>
		
		<div class="control-group">
			<label class="control-label" for="status">Current status</label>
			<div class="controls">
				<select name="status" id="status">
					@foreach( array('pending'=>0, 'approved'=>1) as $k => $v)
						<option value={{$v}} @if($v === $row['status']) selected @endif>{{$k}}</option>
					@endforeach
				</select>
			</div>
		</div>
		
		<div id="entry-msg-error" class="entry-error"></div>

		<div class="form-actions">
			<button class="btn btn-info" type="submit" name="editentry_submit">
				<i class="icon-ok bigger-110"></i>
				Update changes
			</button>

			&nbsp; &nbsp; &nbsp;
			<button class="btn" type="reset">
				<i class="icon-undo bigger-110"></i>
				Reset
			</button>

			&nbsp; &nbsp; &nbsp;
			<span id="create-task-ajaxloader" class="ajaxloader" style="display:none;">{{HTML::image('vendor/bucketcodes/img/ajax-loader.gif')}}</span>
		</div>
		
	{{Form::close()}}
</div>
{{Larasset::start('footer')->only('bootstrap-datepicker', 'ace-element')->show('scripts')}}

<script type="text/javascript">
$(function(){
	
	$('#depositdate').datepicker().next().on(ace.click_event, function(){
		$(this).prev().focus();
	});
	
	$('button[name="editentry_submit"]').on('click',function(e){
	e.preventDefault();
		$(this).ajaxrequest_wrapper({
			validate: {vtype:'inline', etype:'inline'},
			wideAjaxStatusMsg: '.entry-error',
			immediatelyAfterAjax_callback: response,
		});
	});
	
	function response(data){
		var result = data.message, row, rowspan;
		
		row = $('table#bankentry-table').find('tbody tr#data-'+result.id);
		
		$.each(result, function(i,v){
		rowspan = row.find('td span.'+i);
			if( i !== '_token' ){
				if( i === 'status' ){
					v = parseInt(v);
					var vx = (v === 0) ? 'Pending' : 'Approved';
					var cl = (v === 0) ? 'badge-important' : 'badge-success';
					//row = row.find('a');
					//Lets set the attrs val
					rowspan.closest('a').attr('data-bankentries-status', v);
					rowspan.removeClass('badge-important');
					rowspan.removeClass('badge-success');
					rowspan.addClass(cl);
					rowspan.text(vx);
					return;
				}
				
				//We check if it's comment and if it's not empty
				if( i === 'comment' && v !== ''){
				
					//We check if there's already tag representing comment
					if( row.find('td i.'+i).attr('title') !== undefined ){
					
						//If yes with just update the comment value
						row.find('td i.'+i).attr('title', v);
					}else{
					
						//If no comment exists on this row.. Then we'll create a comment tag with it's value
						row.find('td span.bank_name').before('<i class="icon-book orange comment" title="'+v+'" style="cursor:pointer"></i> ');
					}
					return;
				}else if(i === 'comment' && v === ''){ //If comment is set but empty value
					if( row.find('td i.'+i).attr('title') !== undefined ){ // If comment already exist in this row..
						
						//Then we'll delete comment tag
						row.find('td i.'+i).remove();
					}
					return;
				}

				rowspan.text(v);
			}
		});
		
		//Closing the modalbox
		$('.myModalCloned').modal('hide');
	}
	
	$(document).on('hidden', '.myModalCloned', function(){
		
		//Removing the highlight in this row
		$('table#bankentry-table tbody tr').removeClass('khaki-bg');
		
		//Deleting the current cloned modalbox on closing the modalbox
		$('.myModalCloned').remove();
	});
	
});
</script>

