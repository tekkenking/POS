<h1 style="padding: 17px; background: none repeat scroll 0px 0px rgba(0, 0, 0, 0.8); border-radius: 35px;" class="center white bolder"> {{HTML::image('vendor/bucketcodes/img/logo1_small_black.jpg', 'Glamour56_logo', array('width'=>'43'))}} {{Systemsetting::getx('name')}} </h1>

<div class="row-fluid">
	<div class="span12">
		<div class="span4"> {{HTML::image('vendor/bucketcodes/img/activa_point3.png', 'Activa logo by BUCKETCODES', array('width'=>'150px'))}}</div>
		<div class="span4">
			
			<div id="signin-form-container">
				{{ Form::open(array('route'=>'loginarea', 'id'=>'form-login', 'class'=>'form-logreg' )) }}
				{{--HTML::image('vendor/bucketcodes/img/logo1_small_white.jpg', 'Glamour56_logo', array('width'=>'40'))--}}	
				<h2 class="bolder grey">
					Staff login
				</h2>
				{{Form::text('username', '', array('placeholder'=>'Username', 'class'=>'span12', 'validate'=>'required', 'autocomplete'=>'off'))}}

				{{Form::password('password', array('placeholder'=>'Password', 'class'=>'span12', 'validate'=>'required'))}}

				<button type="submit" name="submit" value="noredirect" class="btn btn-warning btn-block bolder original_btn">
					<h4 class="lighter ">Login</h4>
				</button>

				&nbsp; &nbsp; &nbsp;
				<span id="create-task-ajaxloader" class="ajaxloader" style="display:none;">{{HTML::image('vendor/bucketcodes/img/ajax-loader.gif')}}</span>
				<br>
			<div id="create-task-msg-error" class="error-msg"></div>
			   {{ Form::close()}}
			</div>

		</div>
		<div class="span4">
			{{--YOUR COMPANY LOGO HERE--}}
		</div>
	</div>
</div>

<script>
$(document).ready(function(){

	$(this).find('.page-content').css({'background':'none'});
	$(this).find('.container-white').css({'border':'none', 'background':'none'});

	$('button[type="submit"]').on('click',function(e){
		e.preventDefault();
		$(this).ajaxrequest_wrapper({
			extraContent: {'submit' : $(this).val()},
			validate: {
						username:{ required : 'is required.' },
						password:{ required : 'can not be empty' },
						//etype:'inline'
					 }
		});
	});

	$.vegas('slideshow', {
	  backgrounds:[
	    { src:'/pos/bg_slider/golden_flower.jpg', fade:1500 },
	    { src:'/pos/bg_slider/golden_egg.jpg', fade:1500 },
	    { src:'/pos/bg_slider/bright_flower.jpg', fade:1500 },
	    { src:'/pos/bg_slider/brooklyn_bridge.jpg', fade:1500 }
	  ]
	})('overlay');

})

</script>