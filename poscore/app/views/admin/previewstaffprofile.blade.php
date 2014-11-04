<div style="display: block;">
	<div class="user-profile row-fluid" id="user-profile-1">

		<div class="span3 center">
			<div class="width-80 label label-info label-large">
				<div class="inline position-relative">
					<span class="white middle bigger-120">
				{{{ucwords($staff['name'])}}} 
					</span>
				</div>
			</div>

			<div>
				<span class="profile-picture">
				<?php $imagex = "uploads/img/{$staff['gender']}.jpg"; ?>
					{{HTML::image($imagex, 'Staff Avatar', array('width'=>'180'))}}
				</span>
			</div>

			<div class="space-6"></div>

			<div class="profile-contact-info">
				<div class="profile-contact-links align-left">
					<a href="#" class="btn btn-link">
						<i class="icon-envelope bigger-120 pink"></i>
						<span id="sendmessage" data-pk="{{$staff['id']}}">Send a message</span>
					</a>
				</div>
			</div>

			<div class="hr hr12 dotted"></div>
			<div class="clearfix">
				<div class="grid1">
					<span class="bigger-110 label label-large label-yellow">N{{format_money($salesworth)}}K</span>

					<br>
					Overall sales worth
				</div>
			</div>
			<div class="hr hr16 dotted"></div>
		</div>
 
		<div class="span9">
{{Larasset::start('header')->only('bootstrap_editable', 'bootstrap-datepicker')->show('styles')}}
			<div class="tabbable">
				<ul id="myTab" class="nav nav-tabs padding-18">
					<li class="active">
						<a href="#staffpersonaldetails" data-toggle="tab">
							<i class="green icon-home bigger-110"></i>
							 Staff personal details
						</a>
					</li>

					<li class="">
						<a href="#staffguarantordetails" data-toggle="tab">
							Staff guarantor details
						</a>
					</li>

					<li class="">
						<a href="#staffactivity" data-toggle="tab">
							Last 10 Recent Staff Activities
						</a>
					</li>
				</ul>

				<div class="tab-content no-border">
					<div class="tab-pane active" id="staffpersonaldetails">
							@include('admin.previewstaffdetails')
					</div>

					<div class="tab-pane" id="staffguarantordetails">
							@include('admin.previewstaffguarantordetails')
					</div>

					<div class="tab-pane" id="staffactivity">
							@include('activity_layouts.previewstaffactivity')
					</div>

				</div>
			</div>
{{Larasset::start('footer')->only('bootstrap-datepicker','bootstrap_editable')->show('scripts')}}
		</div>

	</div>
</div>


<script>

$(document).ready(function(){
	$.fn.editable.defaults.mode = 'inline';
	//$.fn.editableform.loading = "<div class='editableform-loading'><i class='light-blue icon-spinner icon-spin'></i></div>";
    $.fn.editableform.buttons = '<button type="submit" class="btn btn-success editable-submit"><i class="icon-ok icon-white"></i></button>'+
                                '<button type="button" class="btn editable-cancel"><i class="icon-remove"></i></button>';


    $('#sendmessage').editable({
    	mode:'popup',
        type: 'textarea',
        placement: 'right',
        params:{sentby_id:"{{Auth::user()->id}}"},
		name : 'inbox',
		url: "{{URL::route('inbox')}}",
		display:false,
		success: function(response, newValue){
			bootbox.alert(response.message);
		}
	});


/** STAFF PERSONAL DETAILS EDITABLE STARTS HERE  **/
    $('#name').editable({
        type: 'text',
		name : 'name',
		url: "{{URL::route('staffupdate')}}",
		success: function(response, newValue){
			$(document).find('#username').text(response.username);
		}
	});

    $('#password').editable({
        type: 'text',
		name : 'password',
		url: "{{URL::route('staffupdate')}}",
	});

	$('#birthday').editable({
		type: 'date',
		url: "{{URL::route('staffupdate')}}",
		format: 'yyyy-mm-dd',
		viewformat: 'mm/dd/yyyy',
		datepicker: {
			weekStart: 1
		}
	});

	$('#houseaddress').editable({
        type: 'text',
		name : 'houseaddress',
		url: "{{URL::route('staffupdate')}}",
	});

	$('#phone').editable({
        type: 'text',
		name : 'phone',
		url: "{{URL::route('staffupdate')}}",
	});

	$('#email').editable({
        type: 'text',
		name : 'email',
		url: "{{URL::route('staffupdate')}}",
	});

	
	var collectRolesFromPHP = {{json_encode(Config::get('role'))}};
	var roles = [];
	var selectedRoles = [];
	var innerSelectedRoles = [];
		$.each($('#role').text().split(','), function(i,v){
			innerSelectedRoles.push($.trim(v));
		});

	$.each(collectRolesFromPHP, function(value, index){
		roles.push({value:index, text:value});
			if( $.inArray( value, innerSelectedRoles ) > -1 ){
				selectedRoles.push(index);
			}
	});
	//_debug(roles);
	//_debug(innerSelectedRoles);
		
	$('#role').editable({
        type: 'checklist',
        title: 'Select staff roles',
		source: roles,
		value: selectedRoles,
		//sourceCache: true,
		url: "{{URL::route('staffupdate')}}",
		mode: 'popup'
	});

	//Disable and Enable of users
	var status = [{value:1, text:'Enabled'}, {value:0, text:'Disabled'}];
	$('#isenabled').editable({
        type: 'select',
		source: status,
		value: function(){
			var rolex = 1;
			$.each(status, function(index, value){
				if(value.text === $.trim($('#isenabled').text()) ){
					rolex = value.value;
				}
			});
			return rolex;
		},
		url: "{{URL::route('staffupdate')}}"
	});

/** STAFF GUARANTOR DETAILS EDITABLE STARTS HERE  **/
	$('#guarantor_name').editable({
        type: 'text',
		url: "{{URL::route('staffupdate')}}",
		name: 'guarantor_name'
	});

	$('#guarantor_address').editable({
        type: 'text',
		url: "{{URL::route('staffupdate')}}",
		name: 'guarantor_address'
	});

	$('#guarantor_phone').editable({
        type: 'text',
		url: "{{URL::route('staffupdate')}}",
		name: 'guarantor_phone'
	});

	$('#guarantor_email').editable({
        type: 'text',
		url: "{{URL::route('staffupdate')}}",
		name: 'guarantor_email'
	});

	$('#guarantor_workplace_phone').editable({
        type: 'text',
		url: "{{URL::route('staffupdate')}}",
		name: 'guarantor_workplace_phone'
	});

	$('#guarantor_workplace_address').editable({
        type: 'text',
		url: "{{URL::route('staffupdate')}}",
		name: 'guarantor_workplace_address'
	});

});
</script>