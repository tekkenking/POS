

<h1 class="lighter center"> Change password: </h1>
{{ Form::open(array('route'=>'changepassword', 'id'=>'form-login', 'class'=>'form-logreg' )) }}
	<div class="bolder">Enter old password</div>
	{{Form::password('oldpassword', array('placeholder'=>'', 'class'=>'span12', 'validate'=>'required'))}}

	<div class="bolder">Enter new password</div>
	{{Form::password('newpassword', array('placeholder'=>'', 'class'=>'span12', 'validate'=>'required'))}}

	<div class="bolder">Confirm new password</div>
	{{Form::password('confirm_newpassword', array('placeholder'=>'', 'class'=>'span12', 'validate'=>'required'))}}
	
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
		$(this).ajaxrequest_wrapper({
			extraContent: {'submit' : $(this).val()},
			validate: 'inline'
		});

	});
});

</script>