{{Larasset::start('header')->only('bootstrap-datepicker')->show('styles')}}
<div id="customerform">
	<div class="row-fluid">
		<div class="span12">
<!--<h3 class="header smaller lighter green"><i class="icon-pencil"></i> Register new customer: </h3>-->
{{ Form::open(array('route'=>'registercustomer', 'id'=>'registercustomerform', 'class'=>'', 'style'=>'position:relative;' )) }}

	<div class="error-msg"></div>

	<div class="input-icon">
		 {{Form::text('name', '', array('id'=>'inputfullname', 'class'=>'span12 input-block-level', 'autocomplete'=>'off', 'placeholder'=>'Full name', 'validate'=>'required'))}}
		 <i class="icon-user"></i>
	 </div>

	 <div class="input-icon">
		 {{Form::text('phone', '', array('id'=>'inputphonenumber', 'class'=>'span12 input-block-level', 'autocomplete'=>'off', 'placeholder'=>'Phone number', 'validate'=>'required|phone'))}}
		 <i class="icon-phone"></i>
	 </div>

	<div class="input-icon">
		 {{Form::text('email', '', array('id'=>'emailaddress', 'class'=>'span12 input-block-level', 'autocomplete'=>'off', 'placeholder'=>'Email address', 'validate'=>'email'))}}
		 <i class="icon-">@</i>
	 </div>

	<div class="input-icon">
		 {{Form::text('birthday', '', array('id'=>'customer_dob', 'class'=>'span12 input-block-level date-picker', 'autocomplete'=>'off', 'placeholder'=>'Enter customer birthday', 'data-date-format'=>"mm-dd-yyyy", 'validate'=>'date'))}}
			<i class="icon-gift"></i>
	</div>

	<select name="customer_type" class="span12 input-block-level" validate="required">
		<option value="">Select Customer type</option>
			
			@if( !empty($modes) )
				@foreach($modes as $idx => $mox)
					<option value="{{$idx}}">{{ucwords($mox)}}</option>
				@endforeach
			@endif

	</select>

	 <!--<div name="customer_type" data-options="Select Customer type:'',Retail:1, Wholesale:2, Distributor:3, Major Distributor:4" data-attr="validate='required'" rel="selectoption" data-default="Select Customer type"></div>-->

	 <button class="btn btn-info btn-small" type="submit"> Register </button>

	 &nbsp; &nbsp; &nbsp;
	<span id="create-task-ajaxloader" class="ajaxloader" style="display:none;">{{HTML::image('vendor/bucketcodes/img/ajax-loader.gif')}}</span>
		<br>
		<br>

{{Form::close()}}
		</div>
	</div>
</div>

{{Larasset::start('footer')->only('bootstrap-datepicker', 'ace-element')->show('scripts')}}

<script>
$(function(){

	$('.date-picker').datepicker({
		format:'dd-mm-yyyy'
	}).next().on(ace.click_event, function(){
		$(this).prev().focus();
	});

	//Placeholder mimick for IE 9 or less
	$('form#registercustomerform').iePlaceholder();

	$(this).on('click', '[type="submit"]', function(e){
		e.preventDefault();
		e.stopImmediatePropagation();

		$(this).ajaxrequest_wrapper({
			//wideAjaxStatusMsg: '.widearea',
			//pageReload: true
			//ajaxRefresh:true
			validate:'inline',
			afterAjax_callback: autoSetToken,
			close: true,
		});
	});

	/*$('[type="submit"]').on('click',function(e){
		e.preventDefault();
			$(this).ajaxrequest_wrapper({
			//wideAjaxStatusMsg: '.widearea',
			//pageReload: true
			//ajaxRefresh:true
			validate: {
						name:
								{
									required:'is required',
									fullname:'is invalid'
								},
						phone:
								{
									required:'is required',
									integer:'is invalid',
									phone:'must be 11 digits'
								},
						email:
								{
									required:'is required',
									email:'is invalid'
								}
						},
			functionCallback:"autoSetToken(data)",
			close: true
		});
	});*/
});
</script>