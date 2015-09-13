{{ Form::open(array('route'=>'save_generalsystemsettings', 'id'=>'savegeneralsettings', 'class'=>'', 'style'=>'position:relative;' )) }}
<div class="profile-edit-tab-content">
	<div class="tab-pane in active" id="edit-basic">
		<div class="row-fluid">
			<div class="span12">
				<div class="span6">

					<fieldset>
						<legend class="header blue smaller">Enter Payment methods</legend>
						<label class="bolder" for="businesswebsite"> List of payment methods </label>
						{{Form::text('paymentmode', Systemsetting::getx('paymentmode'), array('id'=>'paymentmode', 'class'=>'span12', 'autocomplete'=>'off', 'placeholder'=>'Separated by commas. example: cash,credit card', 'validate'=>'required'))}}
					</fieldset>

					<!--<fieldset>
						<legend class="header blue smaller">Enter vat: <small>e.g 5</small></legend>

						{{Form::text('vat', Systemsetting::getx('vat'), array('id'=>'vat', 'class'=>'span2', 'autocomplete'=>'off', 'placeholder'=>'', 'validate'=>'integer'))}}
					</fieldset>-->

					<button class="btn btn-info" type="submit"> Save info </button>
					<br>
					<div class="error-msg"></div>
				</div>


				<div class="span6">
				<fieldset>
					<legend class="header blue smaller">OTHERS</legend>
					<label class="bolder" for="businesswebsite"> Birthday Alert <small class="muted">Days before the birthday </small> </label>
						<div class="input-icon">
							{{Form::text('dob_alert_day', Systemsetting::getx('dob_alert_day'), array('id'=>'dob_alert_day', 'class'=>'span12', 'autocomplete'=>'off', 'placeholder'=>'Birthday alert day'))}}
							<i class="icon-gift"></i>
						</div>
				</fieldset>

				</div>
			</div>
		</div>
	</div>
</div>
{{Form::close()}}