{{ Form::open(array('route'=>'save_receiptsystemsettings', 'id'=>'savereceiptsettings', 'class'=>'', 'style'=>'position:relative;' )) }}
<div class="profile-edit-tab-content">
	<div class="tab-pane in active" id="edit-basic">
		<div class="row-fluid">
			<div class="span12">
				<div class="span6">

					<fieldset>
						<legend class="header blue smaller">BUSINESS INFO</legend>

						<label class="bolder" for="businessname"> Business Name </label>
						<div class="input-icon">
							{{Form::text('name', Systemsetting::getx('name'), array('id'=>'businessname', 'class'=>'span12', 'autocomplete'=>'off', 'placeholder'=>'Business name', 'validate'=>'required'))}}
							<i class="icon-briefcase"></i>
						</div>

						<label class="bolder" for="registerednumber"> Business Registration Code <small class="muted"> (Optional) </small></label>
						<div class="input-icon">
							{{Form::text('registerednumber', Systemsetting::getx('registerednumber'), array('id'=>'registerednumber', 'class'=>'span12', 'autocomplete'=>'off', 'placeholder'=>'RC123ABC', 'validate'=>''))}}
							<i class="icon-barcode"></i>
						</div>

						<label class="bolder" for="businessaddress"> Business Adress </label>
						<div class="input-icon">
							{{Form::text('address', Systemsetting::getx('address'), array('id'=>'businessaddress', 'class'=>'span12', 'autocomplete'=>'off', 'placeholder'=>'Address'))}}
							<i class="icon-map-marker"></i>
						</div>

						<label class="bolder" for="businessphone"> Business Phone Number </label>
						<div class="input-icon">
							{{Form::text('phone', Systemsetting::getx('phone'), array('id'=>'businessphone', 'class'=>'span12', 'autocomplete'=>'off', 'placeholder'=>'Phone number'))}}
							<i class="icon-phone"></i>
						</div>

						<label class="bolder" for="businessemail"> Business Email Address </label>
						<div class="input-icon">
							{{Form::text('email', Systemsetting::getx('email'), array('id'=>'businessemail', 'class'=>'span12', 'autocomplete'=>'off', 'placeholder'=>'Emails'))}}
							<i class="icon-envelope"></i>
						</div>

						<label class="bolder" for="businesswebsite"> Business Website </label>
						<div class="input-icon">
							{{Form::text('website', Systemsetting::getx('website'), array('id'=>'businesswebsite', 'class'=>'span12', 'autocomplete'=>'off', 'placeholder'=>'Website'))}}
							<i class="icon-globe"></i>
						</div>
					</fieldset>					

					<br>
					<div class="error-msg"></div>
				</div>


				<div class="span6">
					<fieldset>
						<legend class="header blue smaller">Paper width</legend>
					<label class="bolder" for="businesswebsite"> Choose receipt paper size </label>

						<div name="receipt_paperwidth" data-options="Choose paper width:, 2 1/4 inches (57mm):57, 2 3/4 inches (70mm):70, 3 inches (76mm):76, 3 1/4 inches (83mm):83, 4 inches (102mm):102, 4 1/2 inches (114mm):114" rel="selectoption" data-attr="validate='required'" data-default={{Systemsetting::getx('receipt_paperwidth')}} setvaluefrom="data-value"></div>
					</fieldset>

					<fieldset>
						<legend class="header blue smaller">Show / Hide</legend>
							<div class="span12">
								<div class="span4">
									<div class="controls">
										<label>
									<?php $sal = Systemsetting::getx('show_salesperson') ?>
											<input type="checkbox" name="show_salesperson" @if($sal == 1)checked="checked" @endif>
											<span class="lbl"> Salesperson </span>
										</label>
									</div>
								</div>

								<!--<div class="span4">
									<div class="controls">
										<label>
									<?php $cus = Systemsetting::getx('show_customerdetails') ?>
											<input type="checkbox" name="show_customerdetails" @if($cus == 1)checked="checked" @endif>
											<span class="lbl"> Customer details </span>
										</label>
									</div>
								</div>

								<div class="span4">
									<div class="controls">
										<label>
									<?php $pay = Systemsetting::getx('show_paymentmode') ?>
											<input type="checkbox" name="show_paymentmode" @if($pay == 1)checked="checked" @endif>
											<span class="lbl"> Payment mode </span>
										</label>
									</div>
								</div>-->
							</div>
				</fieldset>
			<div class="row-fluid">
				<div class="span12">
					<div class="span6">
						<fieldset>
							<legend class="header blue smaller">Note comment</legend>
							{{Form::textarea('receipt_note', Systemsetting::getx('receipt_note'), array('id'=>'receipt_note', 'class'=>'span12', 'autocomplete'=>'off', 'rows'=>'3'))}}

							<div class="span12">
								<div class="span6">
									<label for="receipt_note_alignment">Alignment:</label>
									<div name="receipt_note_alignment" data-options="Left:left, Center:center, Right:right" rel="selectoption" data-class="btn-success btn-small" data-default={{Systemsetting::getx('receipt_note_alignment')}} setvaluefrom="data-value"></div>
								</div>

								<div class="span6">
									<label for="receipt_note_fontsize">Font size:</label>
									<div name="receipt_note_fontsize" data-options="Normal:inherit, Small:12px, Big:18px" rel="selectoption" data-class="btn-success btn-small" data-default={{Systemsetting::getx('receipt_note_fontsize')}} setvaluefrom="data-value"></div>
								</div>
							</div>
						</fieldset>
					</div>
					<div class="span6">
						<fieldset>
							<legend class="header blue smaller">Foot comment</legend>
								{{Form::textarea('receipt_footer', Systemsetting::getx('receipt_footer'), array('id'=>'receipt_footer', 'class'=>'span12', 'autocomplete'=>'off', 'rows'=>'3'))}}

								<div class="span12">
									<div class="span6">
										<label for="receipt_footer_alignment">Alignment:</label>
										<div name="receipt_footer_alignment" data-options="Left:left, Center:center, Right:right" rel="selectoption" data-class="btn-success btn-small" data-default={{Systemsetting::getx('receipt_footer_alignment')}} setvaluefrom="data-value"></div>
									</div>

									<div class="span6">
										<label for="receipt_footer_fontsize">Font size:</label>
										<div name="receipt_footer_fontsize" data-options="Normal:inherit, Small:12px" rel="selectoption" data-class="btn-success btn-small" data-default={{Systemsetting::getx('receipt_footer_fontsize')}} setvaluefrom="data-value"></div>
									</div>
								</div>
						</fieldset>
					</div>
				</div>
			</div>

				<br>
				<button class="btn btn-info" type="submit"> Save info </button>
				<a class="btn btn-purple" id="preview_receipt"> Preview Receipt </a>
				</div>
			</div>
		</div>
	</div>
</div>
{{Form::close()}}

<script>
$(document).ready(function(){

	$('[rel="selectoption"]').bootstrap_selectoptions({
			//makeDefaultDuplicate: true,
			style: 'width:100%; overflow:hidden',
			hideSelected: true,
			btnCls: 'btn-small btn-yellow'
		});

	//Preview Receipt
	var preview_receipt = function (e){
		e.preventDefault();

		var $that = $(this), url = "{{URL::route('systemsettings.preview_receipt')}}";

		$that.off('click.preview_receipt', preview_receipt);

		$.get(url, function(data) {
			cloneModalbox( $('#myModal') )
			.css({'width':'600px'})
			.centerModal()
			.find('.modal-body').html( data )
			.end()
			.find('.modal-header h3')
			.text('Preview receipt settings')
			.end()
			.find('.modal-footer [data-ref="submit-form"]')
			//.hide()
			.end()
			.modal();

			$that.on('click.preview_receipt', preview_receipt);
		});

	}

	$('#preview_receipt').on('click.preview_receipt', preview_receipt);

});
</script>