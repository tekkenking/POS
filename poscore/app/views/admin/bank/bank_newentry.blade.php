{{Larasset::start('header')->only('bootstrap-datepicker')->show('styles')}}
<div class="banknewentry" id="newbankentry">
	<div class="row-fluid">
		<div class="span12">
			<div class="offset2 span8 offset2 widget-box">
				{{Form::open(array('route'=>'createentry', 'id'=>'createentry', 'class'=>'form-inline'))}}
			
					<div class="widget-header widget-header-blue widget-header-flat">
						<h3 class="lighter">Enter new bank entry</h3>
					</div>

			<div class="widget-body">
				<div class="widget-main">
					<div class="row-fluid">
						<div class="span12">

							<div class="span6">

								<div class="control-group">
									<label for="bank_name" class="control-label">Bank name <small class="muted">e.g <em>Zenith bank</em></small></label>

									<div class="controls">
										{{Form::text('bank_name','', array('class'=>'span12', 'id'=>'bank_name', 'validate'=>'required'))}}
									</div>
								</div>

								<div class="control-group">
									<label for="depositor_name" class="control-label">Depositor's name <small class="muted">e.g <em>Lara Doe</em></small></label>

									<div class="controls">
										{{Form::text('depositor_name','', array('class'=>'span12', 'id'=>'depositor_name', 'validate'=>'required|name'))}}
									</div>
								</div>

								<div class="control-group">
									<label for="depositor_number" class="control-label">Depositor's number <small class="muted">e.g <em>08033333333</em></small>
									</label>

									<div class="controls">
										{{Form::text('depositor_number','', array('class'=>'span12', 'id'=>'depositor_number', 'validate'=>'required|phone'))}}
									</div>
								</div>

								<div class="control-group">
									<label for="comment" class="control-label">Note <small class="muted">e.g <em>lorem ipsum</em></small></label>

									<div class="controls">
										{{Form::text('comment','', array('class'=>'span12', 'id'=>'comment', 'placeholder'=>''))}}
									</div>
								</div>

							</div>

							<div class="span6">

								<div class="control-group">
									<label for="teller_number" class="control-label">Teller number <small class="muted">e.g <em>6784032</em></small></label>

									<div class="controls">
										{{Form::text('teller_number','', array('class'=>'span12', 'id'=>'teller_number', 'validate'=>'required'))}}
									</div>
								</div>
								<div class="control-group">
									<label for="payment_type" class="control-label">Payment type <small class="muted">e.g <em>Cash</em></small></label>

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
									<label for="amount" class="control-label">Amount <small class="muted">e.g <em>10,000</em></small></label>

									<div class="controls">
										{{Form::text('amount','', array('class'=>'span12', 'id'=>'amount', 'validate'=>'required'))}}
									</div>
								</div>

								<div class="control-group">
									<label for="depositdate" class="control-label">Deposit date <small class="muted">e.g <em>mm/dd/yyyy</em></small></label>

									<div class="controls">
										{{Form::text('deposit_date','', array('class'=>'span12', 'id'=>'depositdate', 'validate'=>'required'))}}
									</div>
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

	$('#depositdate').datepicker().next().on(ace.click_event, function(){
		$(this).prev().focus();
	});

	$('button[type="submit"]').on('click',function(e){
		e.preventDefault();
		$(this).ajaxrequest_wrapper({
			validate: {vtype:'inline', etype:'inline'},
			clearfields:'teller_number,amount'
		});
	});
});
</script>