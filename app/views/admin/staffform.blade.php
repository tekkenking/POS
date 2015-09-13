{{Larasset::start('header')->only('bootstrap-datepicker')->show('styles')}}

{{ Form::open(array('route'=>'staffregistration', 'id'=>'registerstaffform', 'class'=>'', 'style'=>'position:relative;' )) }}
<div class="profile-edit-tab-content">
	<div class="tab-pane in active" id="edit-basic">
		<div class="row-fluid">
			<div class="span12">
				<div class="span6">
					<h5 class="header blue smaller">STAFF PERSONAL INFO</h5>

					<!--<div class="control-group">
						<div class="ace-file-input"><input type="file" id="id-input-file-2" placeholder=""><label data-title="Choose"><span data-title="Upload staff photo"><i class="icon-upload-alt"></i></span></label><a href="#" class="remove"><i class="icon-remove"></i></a></div>
					</div>-->

					<div class="input-icon">
						 {{Form::text('name', '', array('id'=>'inputfullname', 'class'=>'span12 input-block-level', 'autocomplete'=>'off', 'placeholder'=>'Full name', 'validate'=>'required|fullname'))}}
						 <i class="icon-user"></i>
					 </div>
					 <div class="input-icon">
							 {{Form::text('birthday', '', array('id'=>'staff_dob', 'class'=>'span12 input-block-level date-picker', 'autocomplete'=>'off', 'placeholder'=>'Enter customer birthday', 'data-date-format'=>"dd-mm-yyyy", 'validate'=>'required'))}}
								<i class="icon-gift"></i>
					</div>

					<div class="control-group">
						<label class="control-label">Gender</label>

						<div class="controls">
							<div class="space-2"></div>

							<label class="inline">
								<input type="radio" value="male" name="gender" validate="required">
								<span class="lbl"> Male</span>
							</label>

							&nbsp; &nbsp; &nbsp;
							<label class="inline">
								<input type="radio" value="female" name="gender" validate="required">
								<span class="lbl"> Female</span>
							</label>
						</div>
					</div>

					<div class="space"></div>
					<h5 class="header blue smaller">STAFF CONTACT INFO</h5>

					 <div class="input-icon">
						 {{Form::text('phone', '', array('id'=>'inputphonenumber', 'class'=>'span12 input-block-level', 'autocomplete'=>'off', 'placeholder'=>'Phone number', 'validate'=>'required|phone'))}}
						 <i class="icon-phone"></i>
					 </div>

					 <div class="input-icon">
						 {{Form::text('email', '', array('id'=>'emailaddress', 'class'=>'span12 input-block-level', 'autocomplete'=>'off', 'placeholder'=>'Email address', 'validate'=>'required|email'))}}
						 <i class="icon-envelop">@</i>
					 </div>

					 <div class="input-icon">
						 {{Form::text('houseaddress', '', array('id'=>'houseaddress', 'class'=>'span12 input-block-level', 'autocomplete'=>'off', 'placeholder'=>'House address', 'validate'=>'required'))}}
						 <i class="icon-map-marker"></i>
					 </div>

					 <div class="error-msg"></div>

				</div>


				<div class="span6">
					<h5 class="header green smaller">STAFF GUARANTOR INFO</h5>

					<!--<div class="control-group">
						<div class="ace-file-input"><input type="file" id="id-input-file-2" placeholder=""><label data-title="Choose"><span data-title="Upload staff guarantor photo"><i class="icon-upload-alt"></i></span></label><a href="#" class="remove"><i class="icon-remove"></i></a></div>
					</div>-->

					<div class="input-icon">
						 {{Form::text('guarantor_name', '', array('id'=>'inputfullname', 'class'=>'span12 input-block-level', 'autocomplete'=>'off', 'placeholder'=>'Guarantor full name', 'validate'=>'required|fullname'))}}
						 <i class="icon-user"></i>
					 </div>

					<div class="control-group">
						<label class="control-label">Gender</label>

						<div class="controls">
							<div class="space-2"></div>

							<label class="inline">
								<input type="radio" value="male" name="guarantor_gender" validate="required">
								<span class="lbl"> Male</span>
							</label>

							&nbsp; &nbsp; &nbsp;
							<label class="inline">
								<input type="radio" value="female" name="guarantor_gender" validate="required">
								<span class="lbl"> Female</span>
							</label>
						</div>
					</div>

					<div class="space"></div>
					<h5 class="header green smaller">STAFF GUARANTOR CONTACT INFO</h5>

					 <div class="input-icon">
						 {{Form::text('guarantor_phone', '', array('id'=>'inputphonenumber', 'class'=>'span12 input-block-level', 'autocomplete'=>'off', 'placeholder'=>'Guarantor phone number', 'validate'=>'required|phone'))}}
						 <i class="icon-phone"></i>
					 </div>

					 <div class="input-icon">
						 {{Form::text('guarantor_email', '', array('id'=>'emailaddress', 'class'=>'span12 input-block-level', 'autocomplete'=>'off', 'placeholder'=>'Guarantor email address', 'validate'=>'required|email'))}}
						 <i class="icon-envelop">@</i>
					 </div>

					 <div class="input-icon">
						 {{Form::text('guarantor_houseaddress', '', array('id'=>'houseaddress', 'class'=>'span12 input-block-level', 'autocomplete'=>'off', 'placeholder'=>'Guarantor house address', 'validate'=>'required'))}}
						 <i class="icon-map-marker"></i>
					 </div>


					<div class="space"></div>
					<h5 class="header green smaller">STAFF GUARANTOR WORK-PLACE CONTACT INFO</h5>

					 <div class="input-icon">
						 {{Form::text('guarantor_workplacephone', '', array('id'=>'inputphonenumber', 'class'=>'span12 input-block-level', 'autocomplete'=>'off', 'placeholder'=>'Guarantor work-place phone number', 'validate'=>'required|phone'))}}
						 <i class="icon-phone"></i>
					 </div>


					 <div class="input-icon">
						 {{Form::text('guarantor_workplaceaddress', '', array('id'=>'houseaddress', 'class'=>'span12 input-block-level', 'autocomplete'=>'off', 'placeholder'=>'Guarantor work-place address', 'validate'=>'required'))}}
						 <i class="icon-map-marker"></i>
					 </div>

					  <button class="btn btn-success" type="submit"> Register </button>

				</div>


			</div>
		</div>
	</div>
</div>
{{Form::close()}}
{{Larasset::start('footer')->only('bootstrap-datepicker', 'ace-element')->show('scripts')}}
<script>
$(document).ready(function(){

	$('.date-picker').datepicker().next().on(ace.click_event, function(){
		$(this).prev().focus();
	});

	$(this).on('click', '[type="submit"]', function(e){
		e.preventDefault();

		$(this).ajaxrequest_wrapper({
			//wideAjaxStatusMsg: '.widearea',
			//pageReload: true
			//ajaxRefresh:true
			validate:{vtype:'inline', etype:'inline'},
			//functionCallback:"autoSetToken(data)",
			//close: true
		});
	});
});
</script>