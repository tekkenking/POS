@section('sidebar')

@include('admin.usersplace_sidemenu')

@stop

<div class="widget-box no-border">
	<div class="widget-header">
		<h4> <i class="red icon-rss bigger-140"></i> STAFF AREA </h4>

		<div class="widget-toolbar no-border">
			<ul id="myTab" class="nav nav-tabs">
				<li class="active">
					<a href="#listofstaffs" data-toggle="tab" class="ajaxable" data-mode="listofstaffs" data-url={{URL::route('adminliststaffs')}} >
						<i class="green icon-reorder bigger-120"></i>
						 LIST OF STAFFS:
					</a>
				</li>

				<li class="">
					<a href="#createnewstaff" data-toggle="tab" class="ajaxable" data-mode="createnewstaff" data-url={{URL::route('adminshowstaffform')}} >
					<i class="orange icon-pencil bigger-120"></i>
						REGISTER NEW STAFF:
					</a>
				</li>
			</ul>

		</div>
	</div>

	<div class="widget-body">
		<div class="no-padding widget-main">
			<div class="no-padding overflow-visible tab-content">
				<div class="tab-pane active" id="listofstaffs">
						@include('admin.liststaffs')
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
	$(this).find('#admin_sidemenu_staffmenu').addClass('active');
});

function getTarget(that){
	targetDiv = that.attr('data-mode');
	$('.tab-content > div').attr('id', targetDiv);
	return targetDiv;
}

</script>