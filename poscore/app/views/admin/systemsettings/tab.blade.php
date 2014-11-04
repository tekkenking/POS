@section('sidebar')

@include('admin.usersplace_sidemenu')

@stop

<div class="widget-box">
	<div class="widget-header header-color-dark">
		<h4> <i class="red icon-cogs bigger-140"></i> System settings </h4>

		<div class="widget-toolbar">
			<ul id="myTab" class="nav nav-tabs">

				<li class="active">
					<a href="#receipt" data-toggle="tab" class="ajaxable" data-url={{URL::route('systemsettings')}} >
						Receipt
					</a>
				</li>

				<li class="">
					<a href="#general" data-toggle="tab" class="ajaxable" data-url={{URL::route('systemsettings')}} >
						General
					</a>
				</li>
			</ul>

		</div>
	</div>

	<div class="widget-body">
		<div class="widget-main">
			<div class="no-padding overflow-visible tab-content">
				<div class="tab-pane active" id="general">
						@include('admin.systemsettings.receipt')
				</div>
			</div>
		</div>
	</div>

</div>


<script>

$(document).ready(function(){
	$('form').iePlaceholder();

	$('.ajaxable').ajaxLoadTabContent({
		extraParamsCallback: "getTarget(that)",
		loader: '<span style="text-align:center"><i class="icon-spinner icon-spin"></i> Loading...</span>',
		loaderTargetPlace: '.loadertargetplace',
	});

	//Would add active feature to side menu
	//$(this).find('#admin_sidemenu_staffmenu').addClass('active');
	$(this).on('click', '[type="submit"]', function(e){
		e.preventDefault();
		e.stopImmediatePropagation();
		$(this).ajaxrequest_wrapper({
			//wideAjaxStatusMsg: '.widearea',
			//pageReload: true
			//ajaxRefresh:true
			validate:'inline',
			//functionCallback:"autoSetToken(data)",
			//close: true
		});
	});

	//Function in g56_functions.js
	removeSidebarAndFitPage();
});

function getTarget(that){
	targetDiv = that.prop('href').split('#')[1];
	//_debug(targetDiv);
	$('.tab-content > div').attr('id', targetDiv);
	return targetDiv;
}

</script>