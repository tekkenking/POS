<h1 class="lighter center"> Create administrator login info: </h1>
{{ Form::open(array('route'=>'createAdmin.save', 'id'=>'form-create-admin', 'class'=>'form-logreg form-horizontal' )) }}
	<div class="bolder">What's your full name:</div>
	{{Form::text('name', '', array('placeholder'=>'', 'class'=>'span12', 'validate'=>'required|name'))}}

	<div class="bolder">Enter new password</div>
	{{Form::text('password', '', array('placeholder'=>'', 'class'=>'span12', 'validate'=>'required'))}}

	<div class="bolder">What's your phone number</div>
	{{Form::text('phone', '', array('placeholder'=>'', 'class'=>'span12', 'validate'=>'required|phone'))}}

	<div class="bolder">Your email is required</div>
	{{Form::text('email', '', array('placeholder'=>'', 'class'=>'span12', 'validate'=>'required|email'))}}


	<div class="bolder">Gender</div>
	<label class="inline">
		<input type="radio" value="male" name="gender" validate="required">
		<span class="lbl"> Male</span>
	</label>

	&nbsp; &nbsp; &nbsp;
	<label class="inline">
		<input type="radio" value="female" name="gender" validate="required">
		<span class="lbl"> Female</span>
	</label>

	<button type="submit" name="submit" value="noredirect" class="btn btn-info btn-block bolder original_btn">
		<h4 class="lighter ">Submit</h4>
	</button>

	&nbsp; &nbsp; &nbsp;
	<span id="create-task-ajaxloader" class="ajaxloader" style="display:none;">{{HTML::image('vendor/bucketcodes/img/ajax-loader.gif')}}</span>

<div id="create-task-msg-error" class="error-msg"></div>
{{ Form::close() }}

<script>
$(document).ready(function(){
	$('button[type="submit"]').on('click',function(e){
		e.preventDefault();
		e.stopImmediatePropagation();
		
		$(this).ajaxrequest_wrapper({
			extraContent: {'submit' : $(this).val()},
			validate: 'inline',
			clearfields: true,
			afterAjax_callback:hideForm,
		});

	});

	function hideForm(){
		//$('#form-create-admin').find('button[type="submit"]').attr('disabled', 'disabled');
	}
});

</script>