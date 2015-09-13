<div style="display: block;">
	<div class="user-profile row-fluid" id="user-profile-1">

		<div class="span3 center">
			<div>
				<span class="profile-picture">
					 {{HTML::image('uploads/img/profile.png', 'Customer Avatar', array('width'=>'180'))}}
				</span>
			</div>

			<div class="space-6"></div>

			<div class="hr hr12 dotted"></div>
			<div class="clearfix">
				<div class="grid1">
					<span class="bigger-110 label label-large label-yellow">N
					@if($customerlog['alltime_spent'] != null)
					{{format_money($customerlog['alltime_spent'])}}
					@else
					0.00
					@endif
					K</span>

					<br>
					All time worth
				</div>
			</div>
			<div class="hr hr16 dotted"></div>
		</div>
 
		<div class="span9">
{{Larasset::start('header')->only('bootstrap_editable', 'bootstrap-datepicker', 'select2')->show('styles')}}
			<div class="tabbable">
				<ul id="myTab" class="nav nav-tabs padding-18">
					<li class="active">
						<a href="#customerdetails" data-toggle="tab">
							<i class="green icon-home bigger-110"></i>
							 Customer details
						</a>
					</li>
					<li>
						<a href="#otherinfo" data-toggle="tab">
							 Other info
						</a>
					</li>
				</ul>

				<div class="tab-content no-border">
					<div class="tab-pane active" id="customerdetails">
							@include('admin.previewcustomerdetails')
					</div>
					<div class="tab-pane" id="otherinfo">
							@include('admin.previewcustomeractivity')
					</div>
				</div>
			</div>
{{Larasset::start('footer')->only('bootstrap_editable', 'bootstrap-datepicker', 'select2')->show('scripts')}}
		</div>

	</div>
</div>


<script>

$(document).ready(function(){
	$.fn.editable.defaults.mode = 'inline';
	$.fn.editableform.loading = "<div class='editableform-loading'><i class='light-blue icon-spinner icon-spin'></i></div>";
    $.fn.editableform.buttons = '<button type="submit" class="btn btn-success editable-submit"><i class="icon-ok icon-white"></i></button>'+
                                '<button type="button" class="btn editable-cancel"><i class="icon-remove"></i></button>';

/** CUSTOMER PERSONAL DETAILS EDITABLE STARTS HERE  **/
    $('#name').editable({
        type: 'text',
		name : 'name',
		url: "{{URL::route('admincustomerupdate')}}",
	});

	$('#birthday').editable({
		type: 'date',
		url: "{{URL::route('admincustomerupdate')}}",
		format: 'yyyy-mm-dd',
		viewformat: 'mm/dd/yyyy',
		datepicker: {
			weekStart: 1
		}
	});

	$('#phone').editable({
        type: 'text',
		name : 'phone',
		url: "{{URL::route('admincustomerupdate')}}",
	});

	$('#email').editable({
        type: 'text',
		name : 'email',
		url: "{{URL::route('admincustomerupdate')}}",
	});

	
	var collectModesFromPHP = {{json_encode(Mode::listModes())}};
	var modes = [];
	$.each(collectModesFromPHP, function(index, value){
		modes.push({id:index, text:value.capitalize()});
	});
		
	$('#mode').editable({
        type: 'select2',
		source: modes,
		name:'mode_id',
		value: function(){
			var modex = 1;
			$.each(modes, function(index, value){
				//_debug(value);
				if(value.text === $.trim($('#mode').text()) ){
					modex = value.id;
				}
			});
			return modex;
		},
		url: "{{URL::route('admincustomerupdate')}}"
	});

});
</script>