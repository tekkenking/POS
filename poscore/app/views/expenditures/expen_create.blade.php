{{Larasset::start('header')->only('bootstrap-datepicker')->show('styles')}}
<div class="banknewentry" id="newbankentry">
	<div class="row-fluid">
		<div class="span12">
			<div class="offset3 span6 offset3 widget-box">
				{{Form::open(array('route'=>'sales.expenditure_save', 'id'=>'createexpenditure', 'class'=>'form-inline'))}}
			
					<div class="widget-header widget-header-blue widget-header-flat">
						<h3 class="lighter">Create new expenditure</h3>
					</div>

			<div class="widget-body">
				<div class="widget-main">
					<div class="row-fluid">
						<div class="span12">

								<div class="control-group">
									<label for="item_name" class="control-label"><span class="red">*</span> Name of Item / Serice <small class="muted">e.g <em>Salary</em></small></label>

									<div class="controls">
										{{Form::text('item_name','', array('class'=>'span12', 'id'=>'item_name', 'validate'=>'required'))}}
									</div>
								</div>
								<div class="control-group">
									<label for="payment_type" class="control-label"><span class="red">*</span> Payment type <small class="muted">e.g <em>Cash</em></small></label>

									<div class="controls">
										@if( ($pmt = Systemsetting::getx('paymentmode')) !== '' )
											<select name="payment_type" id="payment_type" class="span12">
												@foreach(  explode(',', $pmt) as $md)
													<option value={{$md}}>{{istr::title($md)}}</option>
												@endforeach
											</select>
										@else
											{{Form::text('payment_type', 'cash', array('class'=>'span12', 'id'=>'payment_type', 'validate'=>'required'))}}
										@endif
									</div>
								</div>

								<div class="control-group">
									<label for="amount" class="control-label"><span class="red">*</span> Amount <small class="muted">e.g <em>10,000</em></small></label>

									<div class="controls">

										<span class="input-icon display-block">
										{{Form::text('amount','', array('class'=>'span12', 'id'=>'amount', 'validate'=>'required'))}}
										
										<i class="icon-naira">{{currency()}}</i>
										</span>
									</div>

								</div>

								<div class="control-group">
									<label for="date" class="control-label"><span class="red">*</span> Date <small class="muted"> Date of the expenditure.  [ Date format: <em>mm/dd/yyyy</em> ]</small></label>

									<div class="controls">
										{{Form::text('date','', array('class'=>'span12', 'id'=>'date', 'validate'=>'required'))}}
									</div>
								</div>

								<div class="control-group">
									<label for="comment" class="control-label">Comment <small class="muted">e.g <em>A note attached to this record</em></small></label>

									<div class="controls">
										{{Form::text('comment','', array('class'=>'span12', 'id'=>'comment'))}}
									</div>
								</div>

						</div>
					</div>
				</div>
			</div>

				<div id="create-task-msg-error" class="error-msg"></div>

				<div class="form-actions">
					<button class="btn btn-info" type="submit">
						<i class="icon-ok bigger-110"></i>
						Submit
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
		</div>
	</div>
</div>
{{Larasset::start('footer')->only('bootstrap-datepicker', 'ace-element')->show('scripts')}}

<script type="text/javascript">
$(function(){

	$('#date').datepicker().next().on(ace.click_event, function(){
		$(this).prev().focus();
	});

	$('button[type="submit"]').on('click',function(e){
		e.preventDefault();
		$(this).ajaxrequest_wrapper({
			validate: {vtype:'inline', etype:'inline'},
			clearfields: true
		});
	});
});
</script>